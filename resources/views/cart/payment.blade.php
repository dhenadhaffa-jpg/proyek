@extends('layout')

@section('content')

<div class="container">
    <h1>PAYMENT</h1>

    <button id="pay-button" class="btn-primary">
        PAY NOW
    </button>
</div>

<script src="https://app.sandbox.midtrans.com/snap/snap.js"
    data-client-key="{{ config('midtrans.client_key') }}"></script>

<script>
document.getElementById('pay-button').onclick = function () {
    snap.pay('{{ $snapToken }}');
};
</script>

@endsection
