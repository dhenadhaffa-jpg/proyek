@extends('layout')

@section('content')

<div class="container" style="padding: 50px; margin: auto; max-width: 800px;">
    <h1 style="color: #0A192F; border-bottom: 2px solid #0A192F; padding-bottom: 10px; margin-bottom: 20px;">KERANJANG BELANJA</h1>

    @if(session('success'))
        <p style="color: green; font-weight: bold; text-align: center;">{{ session('success') }}</p>
    @endif

    @if(count($carts) > 0)
    <form method="POST" action="/checkout" id="cart-form">
        @csrf
        
        @php $total = 0; @endphp

        @foreach($carts as $item)
            <div style="margin-bottom:20px; border-bottom: 1px solid #eee; padding-bottom: 15px; display: flex; align-items: center; gap: 20px;">
                
                <input type="checkbox" name="selected_carts[]" value="{{ $item->id }}" class="cart-checkbox" data-price="{{ $item->product->harga }}" data-id="{{ $item->id }}" checked style="width: 20px; height: 20px; cursor: pointer;">

                <img src="{{ asset('storage/'.$item->product->gambar) }}" alt="{{ $item->product->nama }}" style="width: 80px; height: 80px; object-fit: cover; border-radius: 5px; border: 1px solid #ddd;">

                <div style="flex-grow: 1;">
                    <h3 style="color: #112240; margin-bottom: 5px; margin-top: 0;">{{ strtoupper($item->product->nama) }}</h3>
                    <p style="margin: 0; color: #555; display: flex; align-items: center; gap: 10px;">
                        Rp {{ number_format($item->product->harga, 0, ',', '.') }} x 
                        
                        <input type="number" name="qty[{{ $item->id }}]" value="{{ $item->qty }}" min="1" class="cart-qty" data-id="{{ $item->id }}" style="width: 60px; padding: 5px; border: 1px solid #ccc; border-radius: 5px; text-align: center;">
                    </p>
                </div>
                
                <div style="text-align: right; font-weight: bold; color: #0A192F; font-size: 1.1em;" id="subtotal-{{ $item->id }}">
                    Rp {{ number_format($item->product->harga * $item->qty, 0, ',', '.') }}
                </div>
            </div>
            @php $total += $item->product->harga * $item->qty; @endphp
        @endforeach

        <div style="display: flex; justify-content: space-between; align-items: center; margin-top: 30px; padding: 15px; background-color: #f9f9f9; border-radius: 5px;">
            <h2 style="margin: 0; color: #333;">Total Pembayaran:</h2>
            <h2 style="margin: 0; color: #0A192F; font-size: 2rem;" id="grand-total">Rp {{ number_format($total, 0, ',', '.') }}</h2>
        </div>

        <button type="submit" id="btn-checkout" style="background-color: #0A192F; color: white; padding: 15px 20px; border: none; font-weight: bold; cursor: pointer; border-radius: 5px; width: 100%; margin-top: 20px; font-size: 18px; transition: 0.3s; text-transform: uppercase; letter-spacing: 1px;">
            Lanjut ke Pembayaran (Midtrans)
        </button>
    </form>
    @else
        <div style="text-align: center; padding: 50px 0;">
            <p style="color: gray; font-style: italic; font-size: 1.2rem;">Keranjang belanja kamu masih kosong nih.</p>
            <a href="/" style="background-color: #0A192F; color: white; padding: 10px 20px; text-decoration: none; border-radius: 5px; font-weight: bold; display: inline-block; margin-top: 15px;">
                Belanja Sekarang
            </a>
        </div>
    @endif
</div>

<script>
    document.addEventListener('DOMContentLoaded', function() {
        const checkboxes = document.querySelectorAll('.cart-checkbox');
        const qtyInputs = document.querySelectorAll('.cart-qty');
        const grandTotalEl = document.getElementById('grand-total');
        const btnCheckout = document.getElementById('btn-checkout');

        // Fungsi format angka jadi format Rupiah
        function formatRupiah(angka) {
            return 'Rp ' + angka.toString().replace(/\B(?=(\d{3})+(?!\d))/g, ".");
        }

        // Fungsi hitung ulang total saat checkbox diklik atau qty diubah
        function hitungTotal() {
            let total = 0;
            let adaYangDiceklis = false;

            checkboxes.forEach(function(checkbox) {
                const id = checkbox.getAttribute('data-id');
                const harga = parseInt(checkbox.getAttribute('data-price'));
                const qtyInput = document.querySelector(`.cart-qty[data-id="${id}"]`);
                const subtotalEl = document.getElementById(`subtotal-${id}`);
                
                let qty = parseInt(qtyInput.value);
                if(isNaN(qty) || qty < 1) {
                    qty = 1;
                    qtyInput.value = 1;
                }

                // Update teks harga subtotal per item
                let subtotal = harga * qty;
                subtotalEl.innerText = formatRupiah(subtotal);

                // Tambahkan ke grand total akhir HANYA JIKA diceklis
                if (checkbox.checked) {
                    total += subtotal;
                    adaYangDiceklis = true;
                }
            });

            // Update teks Grand Total
            grandTotalEl.innerText = formatRupiah(total);
            
            // Matikan tombol checkout kalau tidak ada satupun yang diceklis
            if(!adaYangDiceklis) {
                btnCheckout.style.backgroundColor = '#ccc';
                btnCheckout.style.cursor = 'not-allowed';
                btnCheckout.disabled = true;
            } else {
                btnCheckout.style.backgroundColor = '#0A192F';
                btnCheckout.style.cursor = 'pointer';
                btnCheckout.disabled = false;
            }
        }

        // Pasang sensor (Event Listener) ke setiap checkbox dan input kotak jumlah
        checkboxes.forEach(cb => cb.addEventListener('change', hitungTotal));
        qtyInputs.forEach(input => input.addEventListener('input', hitungTotal));
    });
</script>

@endsection