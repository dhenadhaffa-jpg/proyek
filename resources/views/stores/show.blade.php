@extends('layout')

@section('content')

<div class="container">

    <div class="store-header">
        <h1>{{ strtoupper($store->nama_toko) }}</h1>
        <p>{{ $store->deskripsi }}</p>
    </div>

    <div class="grid">
        @forelse($store->products as $product)
            <a href="/products/{{ $product->id }}" class="card-link">
                <div class="card">
                    <img src="{{ asset('storage/'.$product->gambar) }}">
                    <h3>{{ strtoupper($product->nama) }}</h3>
                    <p class="harga">Rp {{ number_format($product->harga) }}</p>
                </div>
            </a>
        @empty
            <p class="empty">No products in this store.</p>
        @endforelse
    </div>

</div>

@endsection
