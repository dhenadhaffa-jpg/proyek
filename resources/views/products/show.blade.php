@extends('layout')

@section('content')

<div class="container">

    <div class="product-detail">

        <div class="product-image">
            <img src="{{ asset('storage/'.$product->gambar) }}">
        </div>

        <div class="product-info">
            <h1>{{ strtoupper($product->nama) }}</h1>

            <p class="detail-price">
                Rp {{ number_format($product->harga) }}
            </p>

            @auth
    @if($product->store->user_id === auth()->id())
        <a href="/products/{{ $product->id }}/edit" class="btn" style="margin-top:20px;">
            EDIT PRODUCT
        </a>
    @endif
@endauth


            <div class="detail-description">
                <p>{{ $product->deskripsi }}</p>
            </div>

            <div class="detail-store">
                <span>Sold by</span>
                <a href="/store/{{ $product->store->id }}">
                    {{ strtoupper($product->store->nama_toko) }}
                </a>
            </div>

        </div>

    </div>

</div>

@endsection
