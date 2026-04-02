<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Product;
use Illuminate\Http\Request;

class ProductController extends Controller
{
    public function index()
    {
        $products = \App\Models\Product::with('store')->get();

        // Kita modif dikit biar link gambarnya full URL, 
        // soalnya HP lu butuh alamat lengkap buat nampilin gambarnya.
        $products->map(function ($product) {
            $product->gambar_url = url('storage/' . $product->gambar);
            return $product;
        });

        return response()->json([
            'message' => 'Sukses ambil data produk',
            'data' => $products
        ], 200);
    }
}