@extends('layout')

@section('content')
<div class="container" style="padding: 50px; max-width: 1000px; margin: auto;">
    <h1 style="color: #0A192F; border-bottom: 2px solid #0A192F; padding-bottom: 10px; margin-bottom: 20px;">MODERASI TOKO (ADMIN PANEL)</h1>

    @if(session('success'))
        <div style="background-color: #d4edda; color: #155724; padding: 15px; margin-bottom: 20px; border-radius: 5px;">
            <strong>BERHASIL!</strong> {{ session('success') }}
        </div>
    @endif

    <div style="background-color: #fff; padding: 20px; border: 1px solid #ddd; border-radius: 5px;">
        <table style="width: 100%; border-collapse: collapse; text-align: left;">
            <thead>
                <tr style="background-color: #f9f9f9; border-bottom: 2px solid #ddd;">
                    <th style="padding: 12px;">Nama Toko</th>
                    <th style="padding: 12px;">Pemilik</th>
                    <th style="padding: 12px;">Deskripsi</th>
                    <th style="padding: 12px;">Aksi</th>
                </tr>
            </thead>
            <tbody>
                @forelse($pending_stores as $store)
                <tr style="border-bottom: 1px solid #eee;">
                    <td style="padding: 12px;"><strong>{{ $store->nama_toko }}</strong></td>
                    
                    <td style="padding: 12px;">
                        <strong>{{ $store->user->name ?? 'User Telah Dihapus' }}</strong><br>
                        <small style="color: gray;">{{ $store->user->email ?? 'Email tidak ditemukan' }}</small>
                    </td>
                    
                    <td style="padding: 12px;">{{ $store->deskripsi }}</td>
                    <td style="padding: 12px;">
                        <form action="{{ route('admin.stores.approve', $store->id) }}" method="POST" style="display:inline;">
                            @csrf
                            <button type="submit" style="background-color: #28a745; color: white; padding: 8px 12px; border: none; border-radius: 3px; cursor: pointer; margin-right: 5px;">✔ Terima</button>
                        </form>
                        <form action="{{ route('admin.stores.reject', $store->id) }}" method="POST" style="display:inline;">
                            @csrf
                            <button type="submit" style="background-color: #dc3545; color: white; padding: 8px 12px; border: none; border-radius: 3px; cursor: pointer;">✖ Tolak</button>
                        </form>
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="4" style="text-align: center; padding: 30px; color: gray;">Yeay! Tidak ada toko baru yang perlu di-review.</td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>
@endsection