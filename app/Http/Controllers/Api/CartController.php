<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Cart;
use Illuminate\Http\Request;

class CartController extends Controller
{
    // FUNGSI BUAT NAMPILIN ISI KERANJANG
    public function index()
    {
        // Ambil keranjang milik user yang lagi login, sekalian bawa data produknya (pake relasi)
        $carts = Cart::with('product')->where('user_id', auth()->id())->latest()->get();

        // Benerin link gambar biar full URL
        $carts->map(function ($cart) {
            if ($cart->product) {
                $cart->product->gambar_url = url('storage/' . $cart->product->gambar);
            }
            return $cart;
        });

        return response()->json([
            'message' => 'Berhasil narik data keranjang',
            'data' => $carts
        ], 200);
    }

    // FUNGSI BUAT MASUKIN BARANG KE KERANJANG
    public function store(Request $request)
    {
        // Validasi data yang dikirim dari HP
        $request->validate([
            'product_id' => 'required|exists:products,id',
            'qty' => 'required|integer|min:1'
        ]);

        // Cek apakah barang ini udah ada di keranjang user?
        $cart = Cart::where('user_id', auth()->id())
                    ->where('product_id', $request->product_id)
                    ->first();

        if ($cart) {
            // Kalau udah ada, tambahin aja jumlahnya (qty)
            $cart->qty += $request->qty;
            $cart->save();
        } else {
            // Kalau belum ada, bikin data keranjang baru
            Cart::create([
                'user_id' => auth()->id(),
                'product_id' => $request->product_id,
                'qty' => $request->qty
            ]);
        }

        return response()->json([
            'message' => 'Barang berhasil masuk keranjang cuy!'
        ], 200);
    }

    // FUNGSI BUAT UPDATE JUMLAH BARANG (PLUS/MINUS)
    public function update(Request $request, $id)
    {
        // Validasi minimal 1 barang
        $request->validate([
            'qty' => 'required|integer|min:1'
        ]);
        
        // Cari keranjang berdasarkan ID keranjang dan ID User yang login
        $cart = Cart::where('user_id', auth()->id())->where('id', $id)->first();
        
        if ($cart) {
            $cart->qty = $request->qty;
            $cart->save();
            return response()->json([
                'message' => 'Jumlah berhasil diupdate cuy!'
            ], 200);
        }

        return response()->json([
            'message' => 'Keranjang gak ketemu!'
        ], 404);
    }

    // FUNGSI BUAT HAPUS BARANG DARI KERANJANG (TONG SAMPAH)
    public function destroy($id)
    {
        // Cari keranjang berdasarkan ID keranjang dan ID User yang login
        $cart = Cart::where('user_id', auth()->id())->where('id', $id)->first();
        
        if ($cart) {
            $cart->delete();
            return response()->json([
                'message' => 'Barang berhasil dihapus cuy!'
            ], 200);
        }

        return response()->json([
            'message' => 'Keranjang gak ketemu!'
        ], 404);
    }
}