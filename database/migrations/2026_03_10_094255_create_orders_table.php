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
        Schema::create('orders', function (Blueprint $table) {
            $table->id();
            $table->string('order_id_midtrans')->unique(); // ID unik untuk Midtrans
            $table->foreignId('user_id')->constrained('users')->onDelete('cascade'); // Pembeli
            
            // Asumsi lu udah punya tabel stores/toko untuk seller
            // $table->foreignId('store_id')->constrained('stores')->onDelete('cascade'); 
            
            $table->integer('total_harga'); // Harga barang + Ongkir
            $table->integer('ongkir');
            $table->string('kurir'); // jne, pos, tiki
            
            // Status pesanan yang direquest Dena
            $table->enum('status', ['pending', 'dikemas', 'dikirim', 'diterima'])->default('pending');
            
            $table->string('resi')->nullable(); // Resi diisi seller kalau status 'dikirim'
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('orders');
    }
};