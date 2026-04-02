<?php

namespace App\Http\Controllers;

use App\Models\Store;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class StoreController extends Controller
{
    public function create()
    {
        if (Auth::user()->store) {
            return redirect()->route('home')
                ->with('error', 'Kamu sudah punya toko!');
        }

        return view('stores.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'nama_toko' => 'required|max:255',
            'deskripsi' => 'nullable'
        ]);

        $store = Store::create([
            'user_id' => Auth::id(),
            'nama_toko' => $request->nama_toko,
            'deskripsi' => $request->deskripsi,
            'status' => 'pending' // <-- INI YANG DITAMBAHIN CUY
        ]);

        // Redirect-nya kita arahin ke dashboard aja biar dia baca pesannya
        return redirect('/dashboard')
            ->with('success', 'Toko berhasil didaftarkan! Silakan tunggu persetujuan dari Admin Outfitology.');
    }

    public function show(Store $store)
    {
        return view('stores.show', compact('store'));
    }

    public function dashboard()
    {
        $store = auth()->user()->store;

        if (!$store) {
            return redirect('/buka-toko');
        }

        $products = $store->products()->latest()->get();

        return view('stores.dashboard', compact('store', 'products'));
    }
}