<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class OrderItem extends Model
{
    use HasFactory;

    protected $fillable = [
        'order_id', 'product_id', 'qty', 'price'
    ];

    // 🔥 TAMBAHIN INI CUY: Biar dia bisa narik info produknya (nama, gambar, dll)
    public function product()
    {
        return $this->belongsTo(Product::class);
    }
}