<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up()
    {
        Schema::create('products', function (Blueprint $table) {
            $table->id();
            $table->string('nama');
            $table->enum('kategori', ['kemeja','celana','jaket','dress']);
            $table->integer('harga');
            $table->integer('tinggi_min');
            $table->integer('tinggi_max');
            $table->enum('bentuk_badan', ['kurus','sedang','berisi']);
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('products');
    }
};
