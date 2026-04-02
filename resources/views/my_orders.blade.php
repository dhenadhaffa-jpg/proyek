@extends('layout')

@section('content')
<div class="container" style="padding: 50px; max-width: 1000px; margin: auto;">
    <h1 style="color: #0A192F; border-bottom: 2px solid #0A192F; padding-bottom: 10px; margin-bottom: 20px;">PESANAN SAYA</h1>

    @if(session('success'))
        <div style="background-color: #d4edda; color: #155724; padding: 15px; margin-bottom: 20px; border-radius: 5px; border: 1px solid #c3e6cb;">
            <strong>BERHASIL!</strong> {{ session('success') }}
        </div>
    @endif

    <div style="background-color: #fff; padding: 20px; border: 1px solid #ddd; border-radius: 5px;">
        <table style="width: 100%; border-collapse: collapse; text-align: left;">
            <thead>
                <tr style="background-color: #f9f9f9; border-bottom: 2px solid #ddd;">
                    <th style="padding: 12px;">Order ID</th>
                    <th style="padding: 12px;">Total Belanja</th>
                    <th style="padding: 12px;">Kurir</th>
                    <th style="padding: 12px;">No. Resi</th>
                    <th style="padding: 12px;">Status</th>
                    <th style="padding: 12px;">Aksi</th>
                </tr>
            </thead>
            <tbody>
                @foreach($orders as $order)
                <tr style="border-bottom: 1px solid #eee;">
                    <td style="padding: 12px;"><strong>{{ $order->order_id_midtrans }}</strong></td>
                    <td style="padding: 12px;">Rp {{ number_format($order->total_harga, 0, ',', '.') }}</td>
                    <td style="padding: 12px; text-transform: uppercase;">{{ $order->kurir }}</td>
                    
                    <td style="padding: 12px; font-weight: bold; color: #0A192F;">
                        {{ $order->resi ? $order->resi : 'Belum ada resi' }}
                    </td>
                    
                    <td style="padding: 12px;">
                        @if($order->status == 'pending')
                            <span style="background-color: #ffeeba; color: #856404; padding: 5px 10px; border-radius: 15px; font-size: 12px;">Menunggu Dikemas</span>
                        @elseif($order->status == 'dikemas')
                            <span style="background-color: #b8daff; color: #004085; padding: 5px 10px; border-radius: 15px; font-size: 12px;">Sedang Dikemas</span>
                        @elseif($order->status == 'dikirim')
                            <span style="background-color: #c3e6cb; color: #155724; padding: 5px 10px; border-radius: 15px; font-size: 12px;">Dalam Perjalanan</span>
                        @elseif($order->status == 'selesai')
                            <span style="background-color: #d4edda; color: #155724; padding: 5px 10px; border-radius: 15px; font-size: 12px;">Selesai</span>
                        @endif
                    </td>
                    
                    <td style="padding: 12px;">
                        @if($order->status == 'dikirim')
                            <form action="{{ route('buyer.orders.complete', $order->id) }}" method="POST">
                                @csrf
                                <button type="submit" style="background-color: #28a745; color: white; padding: 8px 15px; border: none; border-radius: 3px; cursor: pointer; font-weight: bold;">
                                    Pesanan Diterima
                                </button>
                            </form>
                        @elseif($order->status == 'selesai')
                            <span style="color: green; font-weight: bold;">✔ Diterima</span>
                        @else
                            <span style="color: gray; font-style: italic;">Menunggu...</span>
                        @endif
                    </td>
                </tr>
                @endforeach
            </tbody>
        </table>
        
        @if($orders->isEmpty())
            <p style="text-align: center; padding: 20px; color: gray;">Kamu belum pernah belanja cuy. Yuk jajan dulu!</p>
        @endif
    </div>
</div>
@endsection