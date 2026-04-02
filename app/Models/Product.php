<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Product extends Model
{
    protected $fillable = [
        'store_id',
        'nama',
        'harga',
        'deskripsi',
        'gambar'
    ];

    public function store()
    {
        return $this->belongsTo(Store::class);
    }

    protected $appends = ['gambar_url'];

    // 🔥 TAMBAHIN KOKI-NYA DI SINI CUY 🔥
    public function getGambarUrlAttribute()
    {
        // Pastiin path 'storage/' ini sesuai sama tempat lu nyimpen gambar kemaren ya
        return $this->gambar ? url('storage/' . $this->gambar) : null;
    }
}