<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Store; // Pastiin model Store lu udah ada ya cuy!

class StoreController extends Controller
{
    // 🔥 FUNGSI BUAT NGECEK USER UDAH PUNYA TOKO APA BELUM 🔥
    public function checkMyStore()
    {
        // Cari toko yang user_id nya sama kayak yang lagi login
        $store = Store::where('user_id', auth()->id())->first();

        if ($store) {
            // Kalau tokonya ketemu
            return response()->json([
                'status' => 'ada',
                'message' => 'Toko ditemukan',
                'data' => $store
            ], 200);
        } else {
            // Kalau belum pernah bikin toko
            return response()->json([
                'status' => 'belum_ada',
                'message' => 'Kamu belum punya toko cuy'
            ], 200);
        }
    }

    // 🔥 FUNGSI BUAT NYIMPEN PRODUK BARU 🔥
    public function storeProduct(Request $request)
    {
        // 1. Cek isiannya lengkap gak
        $request->validate([
            'nama' => 'required|string',
            'harga' => 'required|numeric',
            'deskripsi' => 'required|string',
            'gambar' => 'required|image|mimes:jpeg,png,jpg|max:2048', // Maksimal 2MB
        ]);

        // 2. Cari toko milik user ini
        $store = Store::where('user_id', auth()->id())->first();
        if (!$store) {
            return response()->json(['message' => 'Toko tidak ditemukan!'], 404);
        }

        // 3. Simpen gambarnya ke folder storage/app/public/products
        $imagePath = $request->file('gambar')->store('products', 'public');

        // 4. Masukin ke database
        $product = \App\Models\Product::create([
            'store_id' => $store->id,
            'nama' => $request->nama,
            'harga' => $request->harga,
            'deskripsi' => $request->deskripsi,
            'gambar' => $imagePath,
        ]);

        return response()->json([
            'message' => 'Mantap! Produk berhasil diupload.',
            'data' => $product
        ], 201);
    }

    // 🔥 FUNGSI BUAT NARIK DATA PRODUK DI TOKO INI 🔥
    public function myProducts()
    {
        $store = Store::where('user_id', auth()->id())->first();
        
        if (!$store) {
            return response()->json(['message' => 'Toko belum ada'], 404);
        }

        // Cari semua barang yang store_id nya sama kayak ID toko ini
        // Kita urutin dari yang paling baru di-upload (desc)
        $products = \App\Models\Product::where('store_id', $store->id)
                                        ->orderBy('created_at', 'desc')
                                        ->get();

        return response()->json([
            'message' => 'Berhasil narik data produk',
            'data' => $products
        ], 200);
    }

    // 🔥 FUNGSI BUAT NARIK PESANAN MASUK KE TOKO INI 🔥
    public function incomingOrders()
    {
        $store = Store::where('user_id', auth()->id())->first();
        
        if (!$store) {
            return response()->json(['message' => 'Toko belum ada'], 404);
        }

        // Cari semua Nota (Order) yang di dalamnya ada barang jualan toko ini
        $orders = \App\Models\Order::with('items.product')
            ->whereHas('items.product', function($query) use ($store) {
                $query->where('store_id', $store->id);
            })
            ->orderBy('created_at', 'desc')
            ->get();

        return response()->json([
            'message' => 'Berhasil narik pesanan masuk',
            'data' => $orders
        ], 200);
    }

    // 🔥 FUNGSI BUAT INPUT RESI & UBAH STATUS PESANAN 🔥
    public function updateResi(Request $request, $id)
    {
        $request->validate(['resi' => 'required|string']);

        $order = \App\Models\Order::find($id);
        if(!$order) {
            return response()->json(['message' => 'Pesanan tidak ditemukan'], 404);
        }

        // Simpen resinya dan ubah statusnya jadi dikirim!
        $order->update([
            'resi' => $request->resi,
            'status' => 'dikirim'
        ]);

        return response()->json([
            'message' => 'Resi berhasil diinput, paket siap meluncur!',
            'data' => $order
        ], 200);
    }

    // 🔥 FUNGSI BUAT PROFIL TOKO (DILIHAT SAMA PEMBELI) 🔥
    public function showStoreProfile($id)
    {
        // Cari tokonya ada gak
        $store = Store::find($id);
        if (!$store) {
            return response()->json(['message' => 'Toko tidak ditemukan'], 404);
        }

        // Tarik semua produk milik toko ini
        $products = \App\Models\Product::where('store_id', $id)
                                        ->orderBy('created_at', 'desc')
                                        ->get();

        return response()->json([
            'message' => 'Berhasil memuat profil toko',
            'store' => $store,
            'products' => $products
        ], 200);
    }
}