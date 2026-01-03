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
        
        // 2. Overdue Calculation (With Trashed Books)
        $overdueTransactions = BorrowTransaction::whereNull('returned_at')
                        ->where('due_date', '<', Carbon::now())
                        ->with(['user', 'bookCopy' => function($query) {
                            $query->withTrashed(); // <--- FIX: Load copy even if deleted
                        }, 'bookCopy.book'])
                        ->get();

        $overdueCount = $overdueTransactions->count();

        // 3. Calculate Financials
        $collectedFines = BorrowTransaction::sum('fine_amount');
        
        $pendingFines = 0;
        foreach ($overdueTransactions as $trans) {
            $daysOverdue = Carbon::now()->diffInDays($trans->due_date);
            $daysOverdue = $daysOverdue == 0 ? 1 : $daysOverdue;
            $pendingFines += ($daysOverdue * 5);
        }

        $totalFines = $collectedFines + $pendingFines;

        // 4. Recent Activity (With Trashed Books)
        $recentActivities = BorrowTransaction::with(['user', 'bookCopy' => function($query) {
                            $query->withTrashed(); // <--- FIX: Load copy even if deleted
                        }, 'bookCopy.book'])
                        ->latest()
                        ->take(5)
                        ->get();

        // 5. Overdue List
        $overdueList = $overdueTransactions->take(5);
        
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
        'isbn' => 'required',
        'add_copies' => 'nullable|integer|min:1' // New validation
    ]);

    $book = Book::findOrFail($id);
    
    // 1. Update Details
    $book->update([
        'title' => $request->title,
        'author' => $request->author,
        'isbn' => $request->isbn
    ]);

    // 2. Add New Copies (If requested)
    if ($request->filled('add_copies')) {
        for ($i = 0; $i < $request->add_copies; $i++) {
            BookCopy::create([
                'book_id' => $book->id,
                'accession_number' => 'BK-' . mt_rand(100000, 999999), 
                'status' => 'available'
            ]);
        }
    }

    return back()->with('success', 'Book details updated successfully!');
}
    public function deleteCopy($id)
{
    $copy = BookCopy::findOrFail($id);

    // Safety Check: Don't delete if someone is borrowing it!
    if ($copy->status == 'borrowed') {
        return back()->with('error', 'Cannot remove this copy. It is currently borrowed by a student.');
    }

    $copy->delete();

    return back()->with('success', 'Copy removed from inventory.');
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

        // 1. Find the Copy
        $copy = BookCopy::where('accession_number', $request->accession_number)->first();

        // 2. Find the Active Transaction
        $transaction = BorrowTransaction::where('book_copy_id', $copy->id)
                        ->whereNull('returned_at')
                        ->first();

        if (!$transaction) {
            return back()->with('error', 'This book is not currently marked as borrowed.');
        }

        // 3. Mark as Returned
        $transaction->update([
            'returned_at' => Carbon::now()
        ]);

        // 4. Calculate Fine (₱5.00 per day)
        $fine = 0;
        if (Carbon::now()->gt($transaction->due_date)) {
            $daysOverdue = Carbon::now()->diffInDays($transaction->due_date);
            // If less than 1 day but technically late, charge for 1 day, else calculate total
            $daysOverdue = $daysOverdue == 0 ? 1 : $daysOverdue; 
            
            $fine = $daysOverdue * 5; // ₱5 per day
            $transaction->update(['fine_amount' => $fine]);
        }

        // 5. Update Book Status
        $copy->update(['status' => 'available']);

        // 6. Return Message
        if ($fine > 0) {
            return back()->with('error', 'Book Returned. OVERDUE! Collect Fine: ₱' . number_format($fine, 2));
        }

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