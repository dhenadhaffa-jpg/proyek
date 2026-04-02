@extends('layout')

@section('content')
<div class="container" style="padding: 50px; max-width: 800px; margin: auto;">
    <h1 style="color: #0A192F; border-bottom: 2px solid #0A192F; padding-bottom: 10px; margin-bottom: 20px;">PENGIRIMAN & PEMBAYARAN</h1>

    <div style="background-color: #f9f9f9; padding: 20px; border-radius: 5px; margin-bottom: 20px; border: 1px solid #eee;">
        <h3 style="color: #112240; margin-top: 0;">Ringkasan Pesanan:</h3>
        <ul style="list-style-type: none; padding: 0;">
            @foreach($carts as $item)
                <li style="padding: 10px 0; border-bottom: 1px dashed #ccc; display: flex; justify-content: space-between;">
                    <span>{{ $item->product->nama }} <strong>({{ $item->qty }}x)</strong></span>
                    <span>Rp {{ number_format($item->product->harga * $item->qty, 0, ',', '.') }}</span>
                </li>
            @endforeach
        </ul>
        <h3 style="text-align: right; color: #0A192F;">Total Belanja: Rp {{ number_format($total_belanja, 0, ',', '.') }}</h3>
    </div>

    <div style="background-color: #fff; padding: 20px; border: 1px solid #ddd; border-radius: 5px; margin-bottom: 20px;">
        <h3 style="color: #112240; margin-top: 0;">1. Cari Alamat Tujuan</h3>
        <form action="{{ route('checkout.process') }}" method="POST" style="display: flex; gap: 10px;">
            @csrf
            <input type="text" name="cari_kota" placeholder="Ketik nama Kecamatan atau Kota (contoh: Indramayu)..." value="{{ request('cari_kota') }}" style="flex-grow: 1; padding: 12px; border-radius: 5px; border: 1px solid #ccc; font-size: 16px;" required>
            <button type="submit" style="background-color: #0A192F; color: white; padding: 10px 25px; border: none; border-radius: 5px; cursor: pointer; font-weight: bold;">Cari</button>
        </form>
    </div>

    @if(!empty($search_results))
    <div style="background-color: #e6f7ff; padding: 20px; border: 1px solid #91d5ff; border-radius: 5px;">
        <h3 style="color: #112240; margin-top: 0;">2. Pilih Lokasi & Kurir</h3>
        <form action="{{ route('checkout.ongkir') }}" method="POST">
            @csrf
            <label style="font-weight: bold; display: block; margin-bottom: 5px;">Alamat Tujuan Lengkap:</label>
            <select name="destination_id" style="width: 100%; padding: 12px; border-radius: 5px; border: 1px solid #ccc; font-size: 16px; margin-bottom: 15px;">
                @foreach($search_results as $lokasi)
                    <option value="{{ $lokasi['id'] }}">{{ $lokasi['label'] }}</option>
                @endforeach
            </select>

            <label style="font-weight: bold; display: block; margin-bottom: 5px;">Pilih Kurir Pengiriman:</label>
            <select name="kurir" style="width: 100%; padding: 12px; border-radius: 5px; border: 1px solid #ccc; font-size: 16px; margin-bottom: 20px;" required>
                <option value="">-- Pilih Kurir --</option>
                <option value="jne">JNE</option>
                <option value="jnt">J&T Express</option>
                <option value="sicepat">SiCepat</option>
            </select>
            
            <button type="submit" style="background-color: #FFC107; color: #0A192F; padding: 15px 20px; border: none; font-weight: bold; cursor: pointer; border-radius: 5px; width: 100%; font-size: 16px;">
                Cek Harga Ongkir
            </button>
        </form>
    </div>
    @elseif(request()->has('cari_kota'))
        <p style="color: red; font-style: italic;">Lokasi tidak ditemukan. Coba ketik nama kecamatan atau kota yang lebih spesifik.</p>
    @endif

    @if(isset($ongkir_results) && count($ongkir_results) > 0)
    <div style="background-color: #e6ffe6; padding: 20px; border: 1px solid #b3ffb3; border-radius: 5px; margin-top: 20px;">
        <h3 style="color: #112240; margin-top: 0;">3. Pilih Layanan Pengiriman</h3>
        
        <form action="{{ route('checkout.pay') }}" method="POST">
            @csrf
            
            @foreach($ongkir_results as $ongkir)
                <div style="margin-bottom: 15px; border-bottom: 1px solid #ccc; padding-bottom: 10px;">
                    <label style="cursor: pointer; display: flex; align-items: flex-start; gap: 10px;">
                        <input type="radio" name="ongkir_cost" value="{{ $ongkir['cost'] }}" style="margin-top: 5px;" required>
                        <div>
                            <strong style="font-size: 16px; color: #0A192F;">{{ $ongkir['service'] }}</strong> (Estimasi: {{ $ongkir['etd'] }})<br>
                            <span style="color: #555; font-size: 14px;">{{ $ongkir['description'] }}</span><br>
                            <strong style="color: #FF6B6B; font-size: 16px;">Rp {{ number_format($ongkir['cost'], 0, ',', '.') }}</strong>
                        </div>
                    </label>
                </div>
            @endforeach

            <button type="submit" style="background-color: #0A192F; color: white; padding: 15px 20px; border: none; font-weight: bold; cursor: pointer; border-radius: 5px; width: 100%; font-size: 18px; margin-top: 15px; text-transform: uppercase; letter-spacing: 1px;">
                BAYAR VIA MIDTRANS
            </button>
        </form>
    </div>
    @endif
</div>
@endsection

@if(isset($snapToken))
    <script src="https://app.sandbox.midtrans.com/snap/snap.js" data-client-key="{{ env('MIDTRANS_CLIENT_KEY') }}"></script>
    <script type="text/javascript">
        window.onload = function() {
            window.snap.pay('{{ $snapToken }}', {
                onSuccess: function(result){
                    alert("Asyik! Pembayaran Berhasil!");
                    // Sementara gue arahin ke home dulu kalau berhasil
                    window.location.href = "/"; 
                },
                onPending: function(result){
                    alert("Menunggu Pembayaran!");
                },
                onError: function(result){
                    alert("Yahh, Pembayaran Gagal!");
                },
                onClose: function(){
                    alert('Kamu menutup layar pembayaran sebelum menyelesaikannya');
                }
            });
        };
    </script>
@endif