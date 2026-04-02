@extends('layout')

@section('content')

<div class="container">

    <div class="store-header">
        <h1>{{ strtoupper($store->nama_toko) }}</h1>
        <p>Seller Dashboard</p>
    </div>

    @if($store->status == 'pending')
        <div style="background-color: #fff3cd; color: #856404; padding: 20px; border-radius: 5px; margin-top: 20px; margin-bottom: 40px; border: 1px solid #ffeeba;">
            <h3 style="margin-top: 0;">⏳ TOKO SEDANG DI-REVIEW!</h3>
            <p style="margin-bottom: 0;">Sabar ya cuy, tim Admin Outfitology sedang mengecek toko kamu. Kamu baru bisa menambahkan produk dan menerima pesanan setelah toko disetujui.</p>
        </div>

    @elseif($store->status == 'ditolak')
        <div style="background-color: #f8d7da; color: #721c24; padding: 20px; border-radius: 5px; margin-top: 20px; margin-bottom: 40px; border: 1px solid #f5c6cb;">
            <h3 style="margin-top: 0;">❌ TOKO DITOLAK!</h3>
            <p style="margin-bottom: 0;">Maaf, pengajuan toko kamu ditolak oleh Admin. Silakan hubungi tim support Outfitology untuk informasi lebih lanjut.</p>
        </div>

    @else
        <div style="margin-bottom:40px;">
            <a href="/products/create" class="btn">ADD PRODUCT</a>
            <a href="{{ route('seller.orders') }}" style="border: 1px solid #0A192F; background-color: #0A192F; padding: 10px 20px; text-decoration: none; color: white; display: inline-block; margin-left: 10px; font-weight: bold;">CEK PESANAN MASUK</a>
        </div>

        <div class="grid">
            @forelse($products as $product)
                <div class="card">

                    <img src="{{ asset('storage/'.$product->gambar) }}">

                    <h3>{{ strtoupper($product->nama) }}</h3>
                    <p class="harga">Rp {{ number_format($product->harga) }}</p>

                    <div style="margin-top:15px;">
                        <a href="/products/{{ $product->id }}/edit" class="btn" style="margin-right:10px;">EDIT</a>

                        <form action="/products/{{ $product->id }}" method="POST" style="display:inline;">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="btn" style="border-color:red; color:red; cursor: pointer;">
                                DELETE
                            </button>
                        </form>
                    </div>

                </div>
            @empty
                <p class="empty">No products yet.</p>
            @endforelse
        </div>
    @endif

</div>

@endsection