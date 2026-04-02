<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;
use App\Notifications\CustomResetPassword;

class User extends Authenticatable
{
    use HasApiTokens, HasFactory, Notifiable;

    protected $fillable = [
        'name',
        'email',
        'password',
        'role',
        'province_id',    // 🔥 TAMBAHIN INI
        'city_id',        // 🔥 TAMBAHIN INI
        'detail_alamat',  // 🔥 TAMBAHIN INI
    ];

    protected $hidden = [
        'password',
        'remember_token',
    ];

    protected $casts = [
    'email_verified_at' => 'datetime',
];


    public function store()
{
    return $this->hasOne(Store::class);
}

// Fungsi untuk ngebajak email bawaan Laravel
    public function sendPasswordResetNotification($token)
{
        $this->notify(new CustomResetPassword($token));
}

}
