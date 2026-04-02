@extends('layout')

@section('content')

<div class="auth-wrapper">
    <div class="auth-box">

        <h2 class="auth-title">OPEN STORE</h2>
        <p class="auth-subtitle">Start selling your collection</p>

        <form method="POST" action="/buka-toko">
            @csrf

            <div class="form-group">
                <label>Store Name</label>
                <input type="text" name="nama_toko" required>
            </div>

            <div class="form-group">
                <label>Description</label>
                <textarea name="deskripsi" rows="4"></textarea>
            </div>

            <button type="submit" class="btn-primary">
                CREATE STORE
            </button>

        </form>

    </div>
</div>

@endsection
