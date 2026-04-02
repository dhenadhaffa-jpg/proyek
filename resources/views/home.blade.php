@extends('layout')

@section('content')

<div class="container">

    <div class="hero">
        <h1>OUTFITOLOGY</h1>
        <p>Essential Wear. Minimal Style.</p>
    </div>

    <div class="grid">
        @forelse($products as $product)
            <div class="card" style="display: flex; flex-direction: column; justify-content: space-between;">
                <a href="/products/{{ $product->id }}" class="card-link" style="text-decoration: none; color: inherit; flex-grow: 1;">
                    <img src="{{ asset('storage/'.$product->gambar) }}" alt="{{ $product->nama }}" style="width: 100%;">
                    <h3>{{ strtoupper($product->nama) }}</h3>
                    <p class="harga">Rp {{ number_format($product->harga, 0, ',', '.') }}</p>
                </a>
                
                <form action="{{ route('cart.add', $product->id) }}" method="POST" style="margin-top: 15px;">
                    @csrf
                    <button type="submit" style="background-color: #0A192F; color: white; padding: 10px; width: 100%; border: none; border-radius: 5px; cursor: pointer; font-weight: bold; transition: 0.3s;">
                        + Masukkan Keranjang
                    </button>
                </form>
            </div>
        @empty
            <p class="empty">No products available.</p>
        @endforelse
    </div>

</div>

@endsection