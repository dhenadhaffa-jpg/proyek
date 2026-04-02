@extends('layout')

@section('content')

<div class="auth-wrapper">
    <div class="auth-box">

        <h2 class="auth-title">ADD PRODUCT</h2>
        <p class="auth-subtitle">Upload your new collection</p>

        <form method="POST" action="/products" enctype="multipart/form-data">
            @csrf

            <div class="form-group">
                <label>Product Name</label>
                <input type="text" name="nama" required>
            </div>

            <div class="form-group">
                <label>Price</label>
                <input type="number" name="harga" required>
            </div>

            <div class="form-group">
                <label>Description</label>
                <textarea name="deskripsi" rows="4"></textarea>
            </div>

            <div class="form-group">
                <label>Category</label>
                <select name="kategori">
                    <option value="Kemeja">Kemeja</option>
                    <option value="Celana">Celana</option>
                    <option value="Jaket">Jaket</option>
                </select>
            </div>

            <div class="form-group">
                <label>Product Image</label>
                <input type="file" name="gambar" required>
            </div>

            <button type="submit" class="btn-primary">
                UPLOAD PRODUCT
            </button>

        </form>
    </div>
</div>

@endsection
