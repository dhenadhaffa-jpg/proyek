<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Cart extends Model
{
    use HasFactory;

    // 1. Kasih tau kolom apa aja yang boleh diisi
    protected $fillable = [
        'user_id',
        'product_id',
        'qty'
    ];

    // 2. Kenalin relasinya ke tabel Produk
    public function product()
    {
        return $this->belongsTo(Product::class, 'product_id', 'id');
    }

    // (Opsional) Kenalin relasinya ke tabel User
    public function user()
    {
        return $this->belongsTo(User::class, 'user_id', 'id');
    }
}