@extends('layout')

@section('content')
<div class="container" style="padding: 50px; max-width: 500px; margin: auto;">
    <h2 style="text-align: center; color: #0A192F;">BUAT PASSWORD BARU</h2>
    <p style="text-align: center; color: gray; margin-bottom: 20px;">Silakan masukkan password baru kamu di bawah ini.</p>

    @if($errors->any())
        <div style="background-color: #f8d7da; color: #721c24; padding: 15px; margin-bottom: 20px; border-radius: 5px;">
            <ul style="margin: 0; padding-left: 20px;">
                @foreach($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <div style="background-color: #fff; padding: 30px; border: 1px solid #ddd; border-radius: 5px;">
        <form action="{{ route('password.update') }}" method="POST">
            @csrf
            <input type="hidden" name="token" value="{{ $token }}">

            <div style="margin-bottom: 15px;">
                <label style="font-weight: bold; display: block; margin-bottom: 5px;">Alamat Email</label>
                <input type="email" name="email" value="{{ $email }}" readonly style="width: 100%; padding: 10px; border: 1px solid #ccc; border-radius: 3px; background-color: #e9ecef; color: #555;">
            </div>
            
            <div style="margin-bottom: 15px;">
                <label style="font-weight: bold; display: block; margin-bottom: 5px;">Password Baru (Minimal 6 karakter)</label>
                <input type="password" name="password" required style="width: 100%; padding: 10px; border: 1px solid #ccc; border-radius: 3px;">
            </div>

            <div style="margin-bottom: 20px;">
                <label style="font-weight: bold; display: block; margin-bottom: 5px;">Ulangi Password Baru</label>
                <input type="password" name="password_confirmation" required style="width: 100%; padding: 10px; border: 1px solid #ccc; border-radius: 3px;">
            </div>
            
            <button type="submit" style="background-color: #0A192F; color: white; padding: 12px; border: none; width: 100%; border-radius: 3px; font-weight: bold; cursor: pointer;">
                SIMPAN PASSWORD BARU
            </button>
        </form>
    </div>
</div>
@endsection