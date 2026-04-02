<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Order;

class OrderController extends Controller
{
    // Halaman untuk Seller melihat daftar pesanan masuk
    public function index()
    {
        // Ambil semua pesanan, urutkan dari yang paling baru
        $orders = Order::orderBy('created_at', 'desc')->get();
        return view('seller_orders', compact('orders'));
    }

    // Fungsi untuk mengubah status pesanan & input resi
    public function updateStatus(Request $request, $id)
    {
        $order = Order::findOrFail($id);

        // Kalau statusnya pending, ubah jadi dikemas
        if ($order->status == 'pending') {
            $order->status = 'dikemas';
        } 
        // Kalau statusnya dikemas, berarti dia mau ngirim dan input resi
        elseif ($order->status == 'dikemas') {
            $request->validate([
                'resi' => 'required'
            ]);
            $order->status = 'dikirim';
            $order->resi = $request->resi;
        }

        $order->save();

        return back()->with('success', 'Status pesanan berhasil diperbarui!');
    }

    // Fungsi untuk nampilin riwayat pesanan si Pembeli
    public function myOrders()
    {
        // Tarik data pesanan yang user_id-nya sama dengan yang lagi login aja
        $orders = Order::where('user_id', auth()->id())
                       ->orderBy('created_at', 'desc')
                       ->get();
                       
        return view('my_orders', compact('orders'));
    }

    // Fungsi pas pembeli ngeklik tombol "Pesanan Diterima"
    public function completeOrder($id)
    {
        $order = Order::where('id', $id)->where('user_id', auth()->id())->firstOrFail();

        // Cek kalau statusnya beneran lagi 'dikirim'
        if ($order->status == 'dikirim') {
            $order->status = 'diterima';
            $order->save();
        }

        return back()->with('success', 'Hore! Pesanan telah diterima. Terima kasih sudah belanja!');
    }

    // ... (Ini kodingan fungsi checkout lu yang panjang tadi di atasnya) ...

    // 🔥 TAMBAHIN FUNGSI INI DI BAWAH CHECKOUT 🔥
    public function history()
    {
        // Cari semua pesanan milik user yang lagi login
        // with('items.product') itu jurus buat narik data "Anak" (OrderItem) dan "Cucu" (Product) sekaligus!
        $orders = Order::with('items.product')
            ->where('user_id', auth()->id())
            ->orderBy('created_at', 'desc') // Biar yang paling baru muncul di atas
            ->get();

        if($orders->isEmpty()) {
            return response()->json([
                'message' => 'Belum ada pesanan cuy.',
                'data' => []
            ], 200);
        }

        return response()->json([
            'message' => 'Berhasil narik riwayat pesanan!',
            'data' => $orders
        ], 200);
    }
}

    