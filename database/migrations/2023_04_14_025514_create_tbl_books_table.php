<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('tbl_books', function (Blueprint $table) {
            $table->id();
            $table->string('book_name');
            $table->string('description', 1000);
            $table->integer('category_id');
            $table->integer('author_id');
            $table->double('book_fees',15,2);
            $table->integer('quantity');
            $table->integer('is_active');
            $table->string('book_img');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('tbl_books');
    }
};
