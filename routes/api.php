<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\AuthController;
use App\Http\Controllers\Api\ProductController;
use App\Http\Controllers\Api\CartController;

// URL yang bisa diakses BEBAS (tanpa login)
Route::post('/register', [AuthController::class, 'register']);
Route::post('/login', [AuthController::class, 'login']);
Route::get('/products', [ProductController::class, 'index']);

// URL yang WAJIB pakai Karcis/Token (Harus Login Dulu)
Route::middleware('auth:sanctum')->group(function () {
    Route::post('/logout', [AuthController::class, 'logout']);
    
    // Nanti kita tambahin URL buat fetch Profile, bikin Toko, dll di dalam sini cuy!
    Route::get('/user', function (Request $request) {
        return $request->user();
    }); // <-- Nah, fungsi /user ditutup di sini!

    // API KERANJANG (Sekarang posisinya udah bener, sejajar sama rute lain)
    Route::get('/cart', [CartController::class, 'index']); // Buat narik data
    Route::post('/cart', [CartController::class, 'store']); // Buat nambah barang

    Route::put('/cart/{id}', [CartController::class, 'update']); 
    Route::delete('/cart/{id}', [CartController::class, 'destroy']);
    // API ORDER / CHECKOUT
    Route::post('/checkout', [\App\Http\Controllers\Api\OrderController::class, 'checkout']);
    // API RIWAYAT PESANAN
    Route::get('/orders', [\App\Http\Controllers\Api\OrderController::class, 'history']);
    // API TOKO SAYA
    Route::get('/my-store', [\App\Http\Controllers\Api\StoreController::class, 'checkMyStore']);
    // API NAMBAH PRODUK
    Route::post('/my-store/products', [\App\Http\Controllers\Api\StoreController::class, 'storeProduct']);
    // API NARIK DATA PRODUK TOKO
    Route::get('/my-store/products', [\App\Http\Controllers\Api\StoreController::class, 'myProducts']);
    // API NARIK PESANAN MASUK
    Route::get('/my-store/orders', [\App\Http\Controllers\Api\StoreController::class, 'incomingOrders']);
    // API INPUT RESI
    Route::post('/my-store/orders/{id}/resi', [\App\Http\Controllers\Api\StoreController::class, 'updateResi']);
    // API PROFIL TOKO & PRODUKNYA
    Route::get('/stores/{id}/profile', [\App\Http\Controllers\Api\StoreController::class, 'showStoreProfile']);
    // API RAJAONGKIR
    Route::get('/provinces', [\App\Http\Controllers\Api\RajaOngkirController::class, 'getProvinces']);
    Route::get('/cities/{province_id}', [\App\Http\Controllers\Api\RajaOngkirController::class, 'getCities']);
    Route::post('/check-cost', [\App\Http\Controllers\Api\RajaOngkirController::class, 'checkCost']);
    Route::post('/update-alamat', [\App\Http\Controllers\Api\AuthController::class, 'updateAlamat']);
});