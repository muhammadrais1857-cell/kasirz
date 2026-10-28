<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>@yield('judul', 'Admin') - {{ config('toko.nama') }}</title>
    <link rel="stylesheet" href="{{ asset('css/app.css') }}">
</head>
<body>
<div class="admin">
    <aside class="side">
        <a class="logo" href="{{ route('admin.dashboard') }}">{{ config('toko.nama') }} Admin</a>
        <a href="{{ route('admin.dashboard') }}" class="{{ request()->routeIs('admin.dashboard') ? 'on' : '' }}">Dashboard</a>
        <a href="{{ route('admin.produk.index') }}" class="{{ request()->routeIs('admin.produk.*') ? 'on' : '' }}">Produk</a>
        <a href="{{ route('admin.kategori.index') }}" class="{{ request()->routeIs('admin.kategori.*') ? 'on' : '' }}">Kategori</a>
        <a href="{{ route('admin.pesanan.index') }}" class="{{ request()->routeIs('admin.pesanan.*') ? 'on' : '' }}">Pesanan</a>
        <a href="{{ route('admin.laporan') }}" class="{{ request()->routeIs('admin.laporan') ? 'on' : '' }}">Laporan Penjualan</a>
        <a href="{{ route('home') }}">Lihat Toko</a>
        <form method="post" action="{{ route('logout') }}">@csrf<button class="link">Keluar</button></form>
    </aside>
    <section class="main">
        @include('partials.flash')
        @yield('isi')
    </section>
</div>
</body>
</html>
