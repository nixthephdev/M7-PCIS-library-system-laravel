<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
{
    Schema::create('book_copies', function (Blueprint $table) {
        $table->id();
        $table->foreignId('book_id')->constrained()->onDelete('cascade');
        $table->string('accession_number')->unique(); // e.g., BK-1001
        // Status: 1=Available, 2=Borrowed, 3=Lost, 4=Damaged
        $table->enum('status', ['available', 'borrowed', 'lost'])->default('available');
        $table->boolean('is_purchased')->default(true); // Tracking source
        $table->decimal('purchase_price', 8, 2)->nullable();
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
        Schema::dropIfExists('book_copies');
    }
};
