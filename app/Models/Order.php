<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Order extends Model
{
    use HasFactory;

    // Ini "Surat Izin" biar datanya boleh disimpen ke database
    protected $fillable = [
        'order_id_midtrans',
        'user_id',
        'total_harga',
        'ongkir',
        'kurir',
        'status',
        'resi'
    ];

    // Relasi balik ke User (Pembeli)
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    // Relasi ke barang-barang yang dibeli
    public function items()
    {
        return $this->hasMany(OrderItem::class);
    }
}