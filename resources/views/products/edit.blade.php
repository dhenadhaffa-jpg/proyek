@extends('layout')

@section('content')

<div class="auth-wrapper">
    <div class="auth-box">

        <h2 class="auth-title">EDIT PRODUCT</h2>
        <p class="auth-subtitle">Update your product details</p>

        <form method="POST" action="/products/{{ $product->id }}" enctype="multipart/form-data">
            @csrf
            @method('PUT')

            <div class="form-group">
                <label>Product Name</label>
                <input type="text" name="nama" value="{{ $product->nama }}" required>
            </div>

            <div class="form-group">
                <label>Price</label>
                <input type="number" name="harga" value="{{ $product->harga }}" required>
            </div>

            <div class="form-group">
                <label>Description</label>
                <textarea name="deskripsi" rows="4" required>{{ $product->deskripsi }}</textarea>
            </div>

            <div class="form-group">
                <label>Current Image</label>
                <img src="{{ asset('storage/'.$product->gambar) }}" style="width:100%; margin-bottom:15px;">
            </div>

            <div class="form-group">
                <label>Change Image (optional)</label>
                <input type="file" name="gambar">
            </div>

            <button type="submit" class="btn-primary">
                UPDATE PRODUCT
            </button>

        </form>

    </div>
</div>

@endsection
