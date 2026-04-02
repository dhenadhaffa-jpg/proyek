<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up()
    {
        Schema::create('carts', function (Blueprint $table) {
            $table->id();
            // Relasi ke user yang punya keranjang
            $table->foreignId('user_id')->constrained('users')->onDelete('cascade');
            // Relasi ke produk yang dimasukin
            $table->foreignId('product_id')->constrained('products')->onDelete('cascade');
            // Jumlah barang
            $table->integer('qty')->default(1);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('carts');
    }
};
