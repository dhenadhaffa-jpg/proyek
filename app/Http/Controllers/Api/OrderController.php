<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Cart;
use App\Models\Order;
use App\Models\OrderItem;
use Midtrans\Config;
use Midtrans\Snap;

class OrderController extends Controller
{
    public function checkout(Request $request)
    {
        $request->validate([
            'cart_ids' => 'required|array',
            'ongkir' => 'required|numeric',
            'kurir' => 'required|string',
        ]);

        // 🔥 WAJIB PAKE user_id BIAR GAK KETUKER SAMA KERANJANG ORANG 🔥
        $carts = Cart::with('product')->whereIn('id', $request->cart_ids)->where('user_id', auth()->id())->get();

        if($carts->isEmpty()) {
            return response()->json(['message' => 'Barang nggak ketemu! ID yg dicari: ' . implode(', ', $request->cart_ids)], 400);
        }

        $total_barang = 0;
        foreach($carts as $cart) {
            $total_barang += ($cart->product->harga * $cart->qty);
        }

        $total_semua = $total_barang + $request->ongkir;
        $order_id_midtrans = 'ORDER-' . time() . '-' . auth()->id();

        $order = Order::create([
            'order_id_midtrans' => $order_id_midtrans,
            'user_id' => auth()->id(),
            'total_harga' => $total_semua, 
            'ongkir' => $request->ongkir,
            'kurir' => $request->kurir, 
            'status' => 'pending',
        ]);

        foreach($carts as $cart) {
            OrderItem::create([
                'order_id' => $order->id,
                'product_id' => $cart->product_id,
                'qty' => $cart->qty,
                'price' => $cart->product->harga, 
            ]);
        }

        // ==========================================
        // TEMBAK MIDTRANS DULU SEBELUM HAPUS KERANJANG
        // ==========================================
        Config::$serverKey = env('MIDTRANS_SERVER_KEY');
        Config::$isProduction = env('MIDTRANS_IS_PRODUCTION', false);
        Config::$isSanitized = true;
        Config::$is3ds = true;

        $params = array(
            'transaction_details' => array(
                'order_id' => $order_id_midtrans,
                'gross_amount' => $total_semua,
            ),
            'customer_details' => array(
                'first_name' => auth()->user()->name,
                'email' => auth()->user()->email,
            ),
            'callbacks' => array(
                'finish' => 'outfitology://app/payment'
            )
        );

        try {
            // Minta link Midtrans
            $paymentUrl = Snap::createTransaction($params)->redirect_url;
            
            // 🔥 BARU HAPUS KERANJANG KALO MIDTRANS UDAH NGASIH LINK 🔥
            Cart::whereIn('id', $request->cart_ids)->delete();

            return response()->json([
                'message' => 'Checkout Berhasil Cuy!',
                'redirect_url' => $paymentUrl 
            ], 200);

        } catch (\Exception $e) {
            // Kalo Midtrans gagal, hapus lagi nota yang tadi sempet dibikin biar gak nyampah
            $order->delete();
            return response()->json(['message' => 'Gagal Midtrans (Cek Server Key di .env lu): ' . $e->getMessage()], 500);
        }
    }

    public function history()
    {
        $orders = Order::with('items.product')
            ->where('user_id', auth()->id())
            ->orderBy('created_at', 'desc') 
            ->get();

        if($orders->isEmpty()) {
            return response()->json(['message' => 'Belum ada pesanan cuy.', 'data' => []], 200);
        }

        return response()->json(['message' => 'Berhasil narik riwayat pesanan!', 'data' => $orders], 200);
    }
}