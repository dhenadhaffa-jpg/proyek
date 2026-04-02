<?php

use App\Http\Controllers\CartController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\StoreController;
use App\Http\Controllers\ProductController;
use App\Http\Controllers\CheckoutController;
use App\Http\Controllers\OrderController; // <-- TAMBAHAN: Import OrderController
use App\Http\Controllers\PasswordResetController;
use App\Http\Controllers\AdminController;

/*
|--------------------------------------------------------------------------
| HOME (LIST PRODUK)
|--------------------------------------------------------------------------
*/
Route::get('/', [ProductController::class, 'index'])->name('home');


/*
|--------------------------------------------------------------------------
| AUTH & CHECKOUT
|--------------------------------------------------------------------------
*/
Route::get('/login', [AuthController::class, 'login'])->name('login');
Route::post('/login', [AuthController::class, 'authenticate']);

Route::get('/register', [AuthController::class, 'registerForm'])->name('register');
Route::post('/register', [AuthController::class, 'register']);

Route::post('/logout', [AuthController::class, 'logout'])->name('logout');
Route::post('/checkout', [CheckoutController::class, 'process'])->name('checkout.process');
Route::post('/checkout/ongkir', [CheckoutController::class, 'cekOngkir'])->name('checkout.ongkir');
Route::post('/checkout/pay', [CheckoutController::class, 'pay'])->name('checkout.pay');

/*
|--------------------------------------------------------------------------
| STORE & SELLER FITUR (LOGIN REQUIRED)
|--------------------------------------------------------------------------
*/
Route::middleware('auth')->group(function () {

    Route::get('/buka-toko', [StoreController::class, 'create']);
    Route::post('/buka-toko', [StoreController::class, 'store']);

    Route::get('/products/create', [ProductController::class, 'create']);
    Route::post('/products', [ProductController::class, 'store']);
    
    // <-- TAMBAHAN: Route Pesanan Masuk ditaruh di sini cuy! -->
    Route::get('/pesanan-masuk', [OrderController::class, 'index'])->name('seller.orders');
    Route::post('/pesanan-masuk/{id}/update-status', [OrderController::class, 'updateStatus'])->name('seller.orders.update');
    // Route Riwayat Pesanan (Buyer)
    Route::get('/pesanan-saya', [OrderController::class, 'myOrders'])->name('buyer.orders');
    Route::post('/pesanan-saya/{id}/selesai', [OrderController::class, 'completeOrder'])->name('buyer.orders.complete');
    // Route Khusus Admin Moderasi Toko
    Route::get('/admin/stores', [AdminController::class, 'index'])->name('admin.stores');
    Route::post('/admin/stores/{id}/approve', [AdminController::class, 'approve'])->name('admin.stores.approve');
    Route::post('/admin/stores/{id}/reject', [AdminController::class, 'reject'])->name('admin.stores.reject');
});

// Route Lupa Password
    Route::get('/forgot-password', [PasswordResetController::class, 'requestForm'])->name('password.request');
    Route::post('/forgot-password', [PasswordResetController::class, 'sendResetLink'])->name('password.email');
    Route::get('/reset-password/{token}', [PasswordResetController::class, 'resetForm'])->name('password.reset');
    Route::post('/reset-password', [PasswordResetController::class, 'updatePassword'])->name('password.update');

/*
|--------------------------------------------------------------------------
| PUBLIC PAGES & OTHERS
|--------------------------------------------------------------------------
*/
Route::get('/store/{store}', [StoreController::class, 'show']);
Route::get('/products/{product}', [ProductController::class, 'show']);
Route::resource('product', ProductController::class);
Route::get('/products/{product}/edit', [ProductController::class, 'edit']);
Route::put('/products/{product}', [ProductController::class, 'update']);
Route::delete('/products/{product}', [ProductController::class, 'destroy']);
Route::get('/dashboard', [StoreController::class, 'dashboard']);

// Route untuk fitur keranjang (dibungkus middleware auth agar khusus user login)
Route::middleware(['auth'])->group(function () {
    Route::post('/cart/add/{id}', [CartController::class, 'add'])->name('cart.add');
    Route::get('/cart', [CartController::class, 'index'])->name('cart.index');
});