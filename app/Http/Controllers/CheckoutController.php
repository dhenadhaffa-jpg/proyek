<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Cart;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Auth;

class CheckoutController extends Controller
{
    public function process(Request $request)
    {
        // Ambil ID keranjang dari request (pas awal klik) ATAU dari session (pas lagi nyari kota)
        $selectedIds = $request->selected_carts ?? session('selected_carts');
        
        if (!$selectedIds) {
            return redirect()->route('cart.index')->with('error', 'Pilih minimal satu produk untuk checkout!');
        }

        // Simpan ke session biar datanya nggak hilang pas layar nge-refresh nyari kota
        session(['selected_carts' => $selectedIds]);

        // Hitung total belanjaan
        $carts = Cart::whereIn('id', $selectedIds)->with('product')->get();
        $total_belanja = 0;
        foreach ($carts as $cart) {
            $total_belanja += $cart->product->harga * $cart->qty;
        }

        // Fitur Pencarian Kota (API Baru RajaOngkir by Komerce)
        $search_results = [];
        if ($request->has('cari_kota')) {
            $response = Http::withHeaders([
                'key' => env('RAJAONGKIR_API_KEY')
            ])->get('https://rajaongkir.komerce.id/api/v1/destination/domestic-destination', [
                'search' => $request->cari_kota
            ]);
            
            // Ambil data hasil pencarian
            $search_results = $response->json()['data'] ?? [];
        }

        return view('checkout', compact('carts', 'total_belanja', 'search_results'));
    }

    // Fungsi untuk hitung ongkir
    public function cekOngkir(Request $request)
    {
        $request->validate([
            'destination_id' => 'required',
            'kurir' => 'required'
        ]);

        // Asumsi berat barang 1000 gram (1 kg)
        $weight = 1000; 
        
        // ID valid dari API Komerce 
        $origin_id = '31555'; 

        // Tembak API Ongkir Komerce
        $response = Http::asForm()->withHeaders([
            'key' => env('RAJAONGKIR_API_KEY')
        ])->post('https://rajaongkir.komerce.id/api/v1/calculate/domestic-cost', [
            'origin' => $origin_id,
            'destination' => $request->destination_id,
            'weight' => $weight,
            'courier' => $request->kurir
        ]);

        // Tangkap data ongkirnya (Ganti dd jadi ini)
        $ongkir_results = $response->json()['data'] ?? [];

        // Ambil lagi data keranjang dari session buat nampilin ringkasan
        $selectedIds = session('selected_carts');
        
        // Pengaman kalau session kedaluwarsa
        if (!$selectedIds) {
            return redirect()->route('cart.index')->with('error', 'Sesi checkout habis, silakan ulangi dari keranjang.');
        }

        $carts = Cart::whereIn('id', $selectedIds)->with('product')->get();
        $total_belanja = 0;
        foreach ($carts as $cart) {
            $total_belanja += $cart->product->harga * $cart->qty;
        }

        // Lempar datanya kembali ke halaman checkout (biar nggak layar hitam)
        return view('checkout', compact('carts', 'total_belanja', 'ongkir_results'));
    }

    // Fungsi untuk memproses pembayaran ke Midtrans
    public function pay(Request $request)
    {
        $request->validate([
            'ongkir_cost' => 'required'
        ]);

        // Hitung ulang belanjaan
        $selectedIds = session('selected_carts');
        $carts = Cart::whereIn('id', $selectedIds)->with('product')->get();
        
        $total_belanja = 0;
        foreach ($carts as $cart) {
            $total_belanja += $cart->product->harga * $cart->qty;
        }

        // TOTAL KESELURUHAN (Belanja + Ongkir)
        $grand_total = $total_belanja + $request->ongkir_cost;

        // Bikin ID Pesanan unik
        $order_id = 'OUTFITKU-' . time();

        // Simpan data ke database tabel 'orders' yang kita bikin di awal
        \App\Models\Order::create([
            'order_id_midtrans' => $order_id,
            'user_id' => Auth::id(),
            'total_harga' => $grand_total,
            'ongkir' => $request->ongkir_cost,
            'kurir' => 'Kurir RajaOngkir', // Nanti bisa dibikin dinamis
            'status' => 'pending',
        ]);

        // Setting Midtrans (Gunakan settingan yang udah lu punya sebelumnya)
        \Midtrans\Config::$serverKey = env('MIDTRANS_SERVER_KEY');
        \Midtrans\Config::$isProduction = false;
        \Midtrans\Config::$isSanitized = true;
        \Midtrans\Config::$is3ds = true;

        $params = [
            'transaction_details' => [
                'order_id' => $order_id,
                'gross_amount' => $grand_total,
            ],
            'customer_details' => [
                'first_name' => Auth::user()->name,
                'email' => Auth::user()->email,
            ]
        ];

        // Minta "Kunci" pembayaran ke Midtrans
        $snapToken = \Midtrans\Snap::getSnapToken($params);

        $search_results = [];

        // Balikin ke halaman yang sama tapi bawa kunci $snapToken
        return view('checkout', compact('carts', 'total_belanja', 'snapToken', 'grand_total', 'search_results'));
    }
}