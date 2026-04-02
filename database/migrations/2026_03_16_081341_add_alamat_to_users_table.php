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
        Schema::table('users', function (Blueprint $table) {
            // Kita bikin nullable() biar user lama yang belum ngisi gak error
            $table->string('province_id')->nullable()->after('password');
            $table->string('city_id')->nullable()->after('province_id');
            $table->text('detail_alamat')->nullable()->after('city_id');
        });
    }

    public function down()
    {
        Schema::table('users', function (Blueprint $table) {
            $table->dropColumn(['province_id', 'city_id', 'detail_alamat']);
        });
    }
};
