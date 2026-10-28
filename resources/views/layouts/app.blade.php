<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>@yield('judul', config('toko.nama'))</title>
    <link rel="stylesheet" href="{{ asset('css/app.css') }}">
    @stack('head')
</head>
<body>
<header class="nav">
    <div class="nav-in">
        <a class="logo" href="{{ route('home') }}">{{ config('toko.nama') }}</a>
        <form class="cari" action="{{ route('home') }}" method="get">
            <input name="q" value="{{ request('q') }}" placeholder="Cari produk di {{ config('toko.nama') }}...">
        </form>
        <nav class="menu">
            @auth
                <a href="{{ route('keranjang.index') }}">Keranjang ({{ auth()->user()->cartItems()->sum('qty') }})</a>
                <a href="{{ route('pesanan.index') }}">Pesanan</a>
                @if (auth()->user()->isAdmin())
                    <a href="{{ route('admin.dashboard') }}">Admin</a>
                @endif
                <form method="post" action="{{ route('logout') }}">@csrf<button class="link">Keluar</button></form>
            @else
                <a href="{{ route('login') }}">Masuk</a>
                <a href="{{ route('register') }}">Daftar</a>
            @endauth
        </nav>
    </div>
</header>
<main class="wrap">
    @include('partials.flash')
    @yield('isi')
</main>
<footer class="foot">&copy; {{ date('Y') }} {{ config('toko.nama') }} &middot; Kelompok 6 &middot; Teknik Informatika, Universitas Jabal Ghafur</footer>
@stack('scripts')
</body>
</html>
