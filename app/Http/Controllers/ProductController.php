<?php

namespace App\Http\Controllers;

use App\Models\Product;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;


class ProductController extends Controller
{
    // FORM TAMBAH PRODUK
    public function create()
    {
        $store = auth()->user()->store;

        // GEMBOK KEAMANAN: Tendang kalau toko belum aktif
        if (!$store || $store->status !== 'aktif') {
            return redirect('/dashboard')->with('error', 'Toko kamu belum aktif! Tidak bisa menambah produk.');
        }

        return view('products.create');
    }

    // SIMPAN PRODUK
    public function store(Request $request)
    {
        $store = auth()->user()->store;

        // GEMBOK KEAMANAN EKSTRA: Jaga-jaga ada yang nembak data langsung dari luar
        if (!$store || $store->status !== 'aktif') {
            return redirect('/dashboard')->with('error', 'Toko kamu belum aktif! Tidak bisa menyimpan produk.');
        }

        $request->validate([
            'nama' => 'required',
            'harga' => 'required|numeric',
            'deskripsi' => 'required',
            'gambar' => 'required|image'
        ]);

        $path = $request->file('gambar')->store('products', 'public');

        Product::create([
            'store_id' => $store->id,
            'nama' => $request->nama,
            'harga' => $request->harga,
            'deskripsi' => $request->deskripsi,
            'gambar' => $path
        ]);

        return redirect()->route('home');
    }

    // DETAIL PRODUK
    public function show($id)
    {
        $product = Product::findOrFail($id);
        return view('products.show', compact('product'));
    }


    // HALAMAN HOME (LIST PRODUK)
    public function index(Request $request)
    {
        $query = Product::with('store');

        if ($request->search) {
            $query->where('nama', 'like', '%' . $request->search . '%');
        }

        $products = $query->latest()->get();

        return view('home', compact('products'));
    }

    public function edit(Product $product)
    {
        // Pastikan hanya pemilik toko yang bisa edit
        if ($product->store->user_id !== auth()->id()) {
            abort(403);
        }

        return view('products.edit', compact('product'));
    }


    public function update(Request $request, Product $product)
    {
        if ($product->store->user_id !== auth()->id()) {
            abort(403);
        }

        $request->validate([
            'nama' => 'required',
            'harga' => 'required|numeric',
            'deskripsi' => 'required',
            'gambar' => 'nullable|image'
        ]);

        $data = [
            'nama' => $request->nama,
            'harga' => $request->harga,
            'deskripsi' => $request->deskripsi,
        ];

        // Jika upload gambar baru
        if ($request->hasFile('gambar')) {

            // Hapus gambar lama
            if ($product->gambar) {
                Storage::disk('public')->delete($product->gambar);
            }

            $data['gambar'] = $request->file('gambar')->store('products', 'public');
        }

        $product->update($data);

        return redirect('/products/' . $product->id);
    }
    
    public function destroy(Product $product)
    {
        if ($product->store->user_id !== auth()->id()) {
            abort(403);
        }

        if ($product->gambar) {
            Storage::disk('public')->delete($product->gambar);
        }

        $product->delete();

        return redirect('/dashboard');
    }

}