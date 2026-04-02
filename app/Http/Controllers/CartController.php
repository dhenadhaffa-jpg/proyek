<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Cart;
use App\Models\Product;
use Illuminate\Support\Facades\Auth;

class CartController extends Controller
{
    // Fungsi untuk menambah produk ke keranjang
    public function add(Request $request, $id)
    {
        // Pastikan user sudah login (jaga-jaga)
        if (!Auth::check()) {
            return redirect()->route('login')->with('error', 'Silakan login terlebih dahulu!');
        }

        $cart = Cart::where('user_id', Auth::id())->where('product_id', $id)->first();

        if ($cart) {
            // Kalau barang sudah ada, tambah qty-nya
            $cart->qty += 1;
            $cart->save();
        } else {
            // Kalau belum ada, buat baru di keranjang
            Cart::create([
                'user_id' => Auth::id(),
                'product_id' => $id,
                'qty' => 1
            ]);
        }

        return redirect()->back()->with('success', 'Produk berhasil ditambahkan ke keranjang!');
    }

    // Fungsi untuk menampilkan halaman keranjang
    public function index()
    {
        // Ambil data keranjang milik user yang sedang login beserta data produknya
        $carts = Cart::where('user_id', Auth::id())->with('product')->get();
        return view('cart.index', compact('carts'));
    }
}