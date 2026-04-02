@extends('layout')

@section('content')
<div class="container" style="padding: 50px; max-width: 500px; margin: auto;">
    <h2 style="text-align: center; color: #0A192F;">LUPA PASSWORD?</h2>
    <p style="text-align: center; color: gray; margin-bottom: 20px;">Masukkan email akunmu, kami akan mengirimkan link untuk mereset password.</p>

    @if(session('success'))
        <div style="background-color: #d4edda; color: #155724; padding: 15px; margin-bottom: 20px; border-radius: 5px;">
            {{ session('success') }}
        </div>
    @endif

    @if($errors->any())
        <div style="background-color: #f8d7da; color: #721c24; padding: 15px; margin-bottom: 20px; border-radius: 5px;">
            {{ $errors->first() }}
        </div>
    @endif

    <div style="background-color: #fff; padding: 30px; border: 1px solid #ddd; border-radius: 5px;">
        <form action="{{ route('password.email') }}" method="POST">
            @csrf
            <div style="margin-bottom: 15px;">
                <label style="font-weight: bold; display: block; margin-bottom: 5px;">Alamat Email</label>
                <input type="email" name="email" required style="width: 100%; padding: 10px; border: 1px solid #ccc; border-radius: 3px;">
            </div>
            
            <button type="submit" style="background-color: #0A192F; color: white; padding: 12px; border: none; width: 100%; border-radius: 3px; font-weight: bold; cursor: pointer;">
                KIRIM LINK RESET
            </button>
        </form>
        <div style="text-align: center; margin-top: 15px;">
            <a href="{{ route('login') }}" style="color: #0A192F; text-decoration: none;">&larr; Kembali ke Login</a>
        </div>
    </div>
</div>
@endsection