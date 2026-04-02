<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Outfitology</title>

    <link rel="icon" type="image/png" href="{{ asset('images/favicon.png') }}">
    
    <link rel="stylesheet" href="{{ asset('css/style.css') }}">
</head>
<body>

<nav class="navbar">
    <div class="nav-container">

        <div class="nav-left">
            <a href="/" style="margin-right: 20px;">SHOP</a>
            @auth
                <a href="{{ route('buyer.orders') }}" style="font-weight: bold; color: #333;">PESANAN SAYA</a>
            @endauth
        </div>

        <div class="nav-center">
            <a href="/" class="logo">OUTFITOLOGY</a>
        </div>

        <div class="nav-right">
            @auth
                <a href="{{ route('cart.index') }}" style="margin-right: 15px; font-weight: bold;">CART</a>

                @if(auth()->user()->store)
                    <a href="/dashboard" style="margin-right: 15px;">DASHBOARD</a>
                    <a href="/products/create" style="margin-right: 15px;">ADD PRODUCT</a>
                @else
                    <a href="/buka-toko" class="open-store-btn" style="margin-right: 15px;">
                        OPEN STORE
                    </a>
                @endif

                <form action="{{ route('logout') }}" method="POST" style="display:inline;">
                    @csrf
                    <button class="nav-link-btn" style="cursor: pointer; background: none; border: none; font-weight: bold;">LOGOUT</button>
                </form>

            @else
                <a href="{{ route('login') }}" style="margin-right: 15px;">LOGIN</a>
                <a href="{{ route('register') }}">REGISTER</a>
            @endauth
        </div>

    </div>
</nav>

<div class="main-content">
    @yield('content')
</div>

</body>
</html>