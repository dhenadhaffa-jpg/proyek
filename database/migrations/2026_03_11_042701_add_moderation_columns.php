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
        // Nambahin kolom status di tabel stores (defaultnya pending)
        Schema::table('stores', function (Blueprint $table) {
            $table->string('status')->default('pending');
        });

        // Nambahin kolom role di tabel users (buat ngebedain admin sama user biasa)
        Schema::table('users', function (Blueprint $table) {
            $table->string('role')->default('user');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        // Ngehapus kolom status kalau migration di-rollback
        Schema::table('stores', function (Blueprint $table) {
            $table->dropColumn('status');
        });

        // Ngehapus kolom role kalau migration di-rollback
        Schema::table('users', function (Blueprint $table) {
            $table->dropColumn('role');
        });
    }
};