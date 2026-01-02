<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Book;
use App\Models\BookCopy;
use App\Models\BorrowTransaction;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Support\Facades\Storage;

class LibraryController extends Controller
{
    // ==========================================
    // 1. DASHBOARD
    // ==========================================
    public function index()
{
    // 1. Basic Stats
    $totalBooks = BookCopy::count();
    $borrowedBooks = BookCopy::where('status', 'borrowed')->count();
    
    // 2. Overdue Calculation
    $overdueCount = BorrowTransaction::whereNull('returned_at')
                    ->where('due_date', '<', Carbon::now())
                    ->count();

    // 3. Financials (Total Fines)
    $totalFines = BorrowTransaction::sum('fine_amount');

    // 4. Recent Activity (Last 5 transactions)
    $recentActivities = BorrowTransaction::with(['user', 'bookCopy.book'])
                        ->latest()
                        ->take(5)
                        ->get();

    // 5. Overdue List (Specific people who are late)
    $overdueList = BorrowTransaction::whereNull('returned_at')
                    ->where('due_date', '<', Carbon::now())
                    ->with(['user', 'bookCopy.book'])
                    ->take(5)
                    ->get();
    
    return view('dashboard', compact(
        'totalBooks', 
        'borrowedBooks', 
        'overdueCount', 
        'totalFines', 
        'recentActivities',
        'overdueList'
    ));
}

    // ==========================================
    // 2. INVENTORY (Books)
    // ==========================================
    public function inventory(Request $request)
    {
        $query = Book::with('copies');

        if ($request->has('search')) {
            $search = $request->get('search');
            $query->where('title', 'LIKE', "%{$search}%")
                  ->orWhere('isbn', 'LIKE', "%{$search}%")
                  ->orWhere('author', 'LIKE', "%{$search}%");
        }

        $books = $query->get();
        return view('inventory', compact('books'));
    }

    public function storePurchase(Request $request)
    {
        $request->validate([
            'isbn' => 'required',
            'title' => 'required',
            'quantity' => 'required|integer|min:1',
        ]);

        $book = Book::firstOrCreate(
            ['isbn' => $request->isbn],
            [
                'title' => $request->title,
                'author' => $request->author ?? 'Unknown',
                'publisher' => $request->publisher ?? 'Unknown',
                'publication_year' => $request->year ?? date('Y')
            ]
        );

        $quantity = $request->quantity;
        
        for ($i = 0; $i < $quantity; $i++) {
            BookCopy::create([
                'book_id' => $book->id,
                'accession_number' => 'BK-' . mt_rand(100000, 999999), 
                'purchase_price' => $request->price ?? 0,
                'status' => 'available'
            ]);
        }

        return redirect()->route('inventory.index')->with('success', "$quantity copies added to inventory!");
    }

    public function showBook($id)
    {
        $book = Book::with(['copies.borrowTransactions.user'])->findOrFail($id);
        return view('book_details', compact('book'));
    }

    public function updateBook(Request $request, $id)
    {
        $request->validate([
            'title' => 'required',
            'author' => 'required',
            'isbn' => 'required'
        ]);

        $book = Book::findOrFail($id);
        
        $book->update([
            'title' => $request->title,
            'author' => $request->author,
            'isbn' => $request->isbn
        ]);

        return back()->with('success', 'Book details updated successfully!');
    }

    // ==========================================
    // 3. CIRCULATION (Borrow/Return)
    // ==========================================
    public function circulation()
    {
        $activeTransactions = BorrowTransaction::whereNull('returned_at')
                                ->with(['user', 'bookCopy.book'])
                                ->get();
                                
        return view('circulation', compact('activeTransactions'));
    }

    public function borrowBook(Request $request)
{
    $request->validate([
        'student_id' => 'required|exists:users,student_id', // Validate Student ID exists
        'accession_number' => 'required|exists:book_copies,accession_number'
    ]);

    // 1. Find User by Student ID
    $user = User::where('student_id', $request->student_id)->first();

    // 2. Find Book
    $copy = BookCopy::where('accession_number', $request->accession_number)->first();

    if ($copy->status !== 'available') {
        return back()->with('error', 'This book is currently ' . $copy->status);
    }

    // 3. Create Transaction (We still save the internal ID for relationships)
    BorrowTransaction::create([
        'user_id' => $user->id, 
        'book_copy_id' => $copy->id,
        'borrowed_at' => Carbon::now(),
        'due_date' => Carbon::now()->addDays(7),
    ]);

    $copy->update(['status' => 'borrowed']);

    return back()->with('success', 'Book borrowed successfully!');
}

    public function returnBook(Request $request)
    {
        $request->validate([
            'accession_number' => 'required|exists:book_copies,accession_number'
        ]);

        $copy = BookCopy::where('accession_number', $request->accession_number)->first();

        $transaction = BorrowTransaction::where('book_copy_id', $copy->id)
                        ->whereNull('returned_at')
                        ->first();

        if (!$transaction) {
            return back()->with('error', 'This book is not currently marked as borrowed.');
        }

        $transaction->update([
            'returned_at' => Carbon::now()
        ]);

        if (Carbon::now()->gt($transaction->due_date)) {
            $daysOverdue = Carbon::now()->diffInDays($transaction->due_date);
            $transaction->update(['fine_amount' => $daysOverdue * 5]);
        }

        $copy->update(['status' => 'available']);

        return back()->with('success', 'Book returned successfully.');
    }

    // ==========================================
    // 4. MEMBER MANAGEMENT (Users)
    // ==========================================
    public function usersIndex()
    {
        // Get Admins (Staff/Librarians)
        $admins = User::where('role', 'admin')->get();
        
        // Get Students (Members)
        $students = User::where('role', 'member')->latest()->get();

        return view('users', compact('admins', 'students'));
    }

    public function userStore(Request $request)
{
    $request->validate([
        'student_id' => 'required|unique:users,student_id', // Check for duplicates
        'name' => 'required',
        'email' => 'required|email|unique:users',
        'avatar' => 'nullable|image|max:2048',
    ]);

    $avatarPath = null;
    if ($request->hasFile('avatar')) {
        $avatarPath = $request->file('avatar')->store('avatars', 'public');
    }

    User::create([
        'student_id' => $request->student_id, // Save the School ID
        'name' => $request->name,
        'email' => $request->email,
        'password' => bcrypt('password'),
        'role' => 'member',
        'avatar' => $avatarPath,
    ]);

    return back()->with('success', 'New Member Registered!');
}
     public function updateUser(Request $request, $id)
    {
        $request->validate([
            'name' => 'required',
            'email' => 'required|email',
            // Check uniqueness but ignore current user (so you can update name without changing ID)
            'student_id' => ['nullable', 'string', \Illuminate\Validation\Rule::unique('users')->ignore($id)],
            'avatar' => 'nullable|image|max:2048',
        ]);

        $user = User::findOrFail($id);

        // 1. Update Basic Info (Added student_id here)
        $user->name = $request->name;
        $user->email = $request->email;
        $user->student_id = $request->student_id; // <--- THIS WAS MISSING

        // 2. Check if "Remove Avatar" was checked
        if ($request->has('remove_avatar')) {
            if ($user->avatar) {
                Storage::disk('public')->delete($user->avatar);
            }
            $user->avatar = null;
        }

        // 3. Check if New File was uploaded
        if ($request->hasFile('avatar')) {
            if ($user->avatar) {
                Storage::disk('public')->delete($user->avatar);
            }
            $user->avatar = $request->file('avatar')->store('avatars', 'public');
        }

        $user->save();

        return back()->with('success', 'Member details updated.');
    }
    public function deleteUser($id)
    {
        $user = User::findOrFail($id);
        
        // Prevent deleting yourself
        if (auth()->id() == $user->id) {
            return back()->with('error', 'You cannot delete your own account.');
        }

        // Optional: Check if user has unreturned books before deleting
        // This prevents database errors
        $hasActiveLoans = BorrowTransaction::where('user_id', $id)
                            ->whereNull('returned_at')
                            ->exists();

        if ($hasActiveLoans) {
            return back()->with('error', 'Cannot delete user. They still have unreturned books.');
        }

        $user->delete();

        return back()->with('success', 'Member removed from system.');
    }
}