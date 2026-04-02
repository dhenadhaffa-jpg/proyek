@extends('layout')

@section('content')

<form class="filter" method="GET">
  <select name="kategori">
    <option value="">Kategori</option>
    <option value="kemeja">Kemeja</option>
    <option value="celana">Celana</option>
    <option value="jaket">Jaket</option>
  </select>

  <select name="tinggi">
    <option value="">Tinggi Badan</option>
    <option value="150">150 cm</option>
    <option value="160">160 cm</option>
  </select>

  <select name="badan">
    <option value="">Bentuk Badan</option>
    <option value="kurus">Kurus</option>
    <option value="sedang">Sedang</option>
    <option value="berisi">Berisi</option>
  </select>

  <button>Filter</button>
</form>

<div class="grid">
@forelse($products as $p)
  <div class="card">
    <h3>{{ $p->nama }}</h3>
    <p class="kategori">{{ $p->kategori }}</p>
    <p class="harga">Rp {{ number_format($p->harga) }}</p>
  </div>
@empty
  <p class="empty">Produk tidak ditemukan</p>
@endforelse
</div>

@endsection
