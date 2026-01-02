<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use App\Models\Book;
use App\Models\BookCopy;
use Illuminate\Http\Request;
use App\Models\BorrowTransaction;
use Carbon\Carbon;

return new class extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
{
    Schema::create('borrow_transactions', function (Blueprint $table) {
        $table->id();
        $table->foreignId('user_id')->constrained(); // The student
        $table->foreignId('book_copy_id')->constrained(); // The specific book
        $table->dateTime('borrowed_at');
        $table->dateTime('due_date');
        $table->dateTime('returned_at')->nullable(); // Null means still borrowed
        $table->decimal('fine_amount', 8, 2)->default(0);
        $table->timestamps();
    });
}

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('borrow_transactions');
    }
    public function storePurchase(Request $request)
{
    // 1. Create or Find the Book Catalog entry
    $book = Book::firstOrCreate(
        ['isbn' => $request->isbn],
        [
            'title' => $request->title,
            'author' => $request->author,
            'publisher' => $request->publisher,
            'publication_year' => $request->year
        ]
    );

    // 2. Add the physical copies (Inventory)
    $quantity = $request->quantity; // e.g., 5 copies
    
    for ($i = 0; $i < $quantity; $i++) {
        BookCopy::create([
            'book_id' => $book->id,
            'accession_number' => 'BK-' . rand(1000, 9999), // Generate unique ID
            'purchase_price' => $request->price,
            'status' => 'available'
        ]);
    }

    return back()->with('success', "$quantity copies added to inventory!");
}
public function borrowBook(Request $request)
{
    $copyId = $request->book_copy_id; // Scanned Barcode ID
    $userId = $request->user_id;

    // 1. Find the copy
    $copy = BookCopy::find($copyId);

    // 2. Check availability
    if ($copy->status !== 'available') {
        return back()->with('error', 'Book is already borrowed!');
    }

    // 3. Create Transaction
    BorrowTransaction::create([
        'user_id' => $userId,
        'book_copy_id' => $copyId,
        'borrowed_at' => Carbon::now(),
        'due_date' => Carbon::now()->addDays(7), // Due in 7 days
    ]);

    // 4. Update Copy Status
    $copy->update(['status' => 'borrowed']);

    return back()->with('success', 'Book borrowed successfully!');
}
public function returnBook(Request $request)
{
    $copyId = $request->book_copy_id;

    // 1. Find the active transaction (where returned_at is null)
    $transaction = BorrowTransaction::where('book_copy_id', $copyId)
                    ->whereNull('returned_at')
                    ->first();

    if (!$transaction) {
        return back()->with('error', 'No active borrowing record found.');
    }

    // 2. Mark as returned
    $transaction->update([
        'returned_at' => Carbon::now()
    ]);

    // 3. Make book available again
    $bookCopy = BookCopy::find($copyId);
    $bookCopy->update(['status' => 'available']);

    return back()->with('success', 'Book returned.');
}
};
