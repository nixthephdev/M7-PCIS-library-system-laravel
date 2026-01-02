<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Book;
use App\Models\BookCopy;
use App\Models\BorrowTransaction;
use App\Models\User;
use Carbon\Carbon;

class LibraryController extends Controller
{
    // ==========================================
    // 1. VIEW METHODS (Pages)
    // ==========================================

    /**
     * Show the main dashboard
     */
    public function index()
    {
        // Calculate some stats for the dashboard
        $totalBooks = BookCopy::count();
        $borrowedBooks = BookCopy::where('status', 'borrowed')->count();
        
        return view('dashboard', compact('totalBooks', 'borrowedBooks'));
    }

    /**
     * Show the Inventory Page (List of books + Purchase Form)
     */
    public function inventory(Request $request)
{
    $query = Book::with('copies');

    // If there is a search term in the URL
    if ($request->has('search')) {
        $search = $request->get('search');
        $query->where('title', 'LIKE', "%{$search}%")
              ->orWhere('isbn', 'LIKE', "%{$search}%")
              ->orWhere('author', 'LIKE', "%{$search}%");
    }

    $books = $query->get();
    
    return view('inventory', compact('books'));
}

    /**
     * Show the Circulation Page (Borrow/Return Forms)
     */
    public function circulation()
    {
        // Get active transactions to show who has books
        $activeTransactions = BorrowTransaction::whereNull('returned_at')
                                ->with(['user', 'bookCopy.book'])
                                ->get();
                                
        return view('circulation', compact('activeTransactions'));
    }

    // ==========================================
    // 2. ACTION METHODS (Logic)
    // ==========================================

    /**
     * STOCK IN: Purchase new books and add to inventory
     */
    public function storePurchase(Request $request)
    {
        // 1. Validate Input
        $request->validate([
            'isbn' => 'required',
            'title' => 'required',
            'quantity' => 'required|integer|min:1',
        ]);

        // 2. Create or Find the Book Catalog entry (Metadata)
        $book = Book::firstOrCreate(
            ['isbn' => $request->isbn],
            [
                'title' => $request->title,
                'author' => $request->author ?? 'Unknown',
                'publisher' => $request->publisher ?? 'Unknown',
                'publication_year' => $request->year ?? date('Y')
            ]
        );

        // 3. Add the physical copies (Inventory)
        $quantity = $request->quantity;
        
        for ($i = 0; $i < $quantity; $i++) {
            BookCopy::create([
                'book_id' => $book->id,
                // Generate a unique Accession Number (e.g., BK-169823)
                'accession_number' => 'BK-' . mt_rand(100000, 999999), 
                'purchase_price' => $request->price ?? 0,
                'status' => 'available'
            ]);
        }

        return redirect()->route('inventory.index')->with('success', "$quantity copies added to inventory!");
    }

    /**
     * STOCK OUT: Borrow a book
     */
    public function borrowBook(Request $request)
    {
        $request->validate([
            'user_id' => 'required|exists:users,id',
            'accession_number' => 'required|exists:book_copies,accession_number'
        ]);

        // 1. Find the copy by Accession Number (Barcode)
        $copy = BookCopy::where('accession_number', $request->accession_number)->first();

        // 2. Check availability
        if ($copy->status !== 'available') {
            return back()->with('error', 'This book is currently ' . $copy->status);
        }

        // 3. Create Transaction
        BorrowTransaction::create([
            'user_id' => $request->user_id,
            'book_copy_id' => $copy->id,
            'borrowed_at' => Carbon::now(),
            'due_date' => Carbon::now()->addDays(7), // Due in 7 days
        ]);

        // 4. Update Copy Status
        $copy->update(['status' => 'borrowed']);

        return back()->with('success', 'Book borrowed successfully!');
    }

    /**
     * RETURN: Register book return
     */
    public function returnBook(Request $request)
    {
        $request->validate([
            'accession_number' => 'required|exists:book_copies,accession_number'
        ]);

        // 1. Find the copy
        $copy = BookCopy::where('accession_number', $request->accession_number)->first();

        // 2. Find the active transaction
        $transaction = BorrowTransaction::where('book_copy_id', $copy->id)
                        ->whereNull('returned_at')
                        ->first();

        if (!$transaction) {
            return back()->with('error', 'This book is not currently marked as borrowed.');
        }

        // 3. Mark as returned
        $transaction->update([
            'returned_at' => Carbon::now()
        ]);

        // 4. Calculate Fine (Simple logic)
        // If returned after due date, $5 fine per day (Example)
        if (Carbon::now()->gt($transaction->due_date)) {
            $daysOverdue = Carbon::now()->diffInDays($transaction->due_date);
            $transaction->update(['fine_amount' => $daysOverdue * 5]);
        }

        // 5. Make book available again
        $copy->update(['status' => 'available']);

        return back()->with('success', 'Book returned successfully.');
    }
    public function showBook($id)
{
    $book = Book::with(['copies.borrowTransactions.user'])->findOrFail($id);
    return view('book_details', compact('book'));
}
// Show User List & Form
public function usersIndex()
{
    $users = User::all();
    return view('users', compact('users'));
}

// Create New User
public function userStore(Request $request)
{
    $request->validate([
        'name' => 'required',
        'email' => 'required|email|unique:users',
    ]);

    // Create user with a default password (e.g., 'password')
    User::create([
        'name' => $request->name,
        'email' => $request->email,
        'password' => bcrypt('password'), // In a real app, you might generate this
    ]);

    return back()->with('success', 'New Member Registered!');
}
}