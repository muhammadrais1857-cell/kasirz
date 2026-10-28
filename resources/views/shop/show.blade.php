@extends('layouts.app')
@section('judul', $produk->nama_produk)
@section('isi')
<p class="mu"><a href="{{ route('home') }}">Beranda</a> &gt; <a href="{{ route('home', ['kategori' => $produk->category_id]) }}">{{ $produk->category->nama_kategori }}</a> &gt; {{ $produk->nama_produk }}</p>
<div class="card detail">
    <div>@include('partials.foto', ['p' => $produk])</div>
    <div>
        <h1>{{ $produk->nama_produk }}</h1>
        <div class="harga" style="background:#fafafa;padding:14px 16px;border-radius:4px;margin-bottom:16px">{{ rupiah($produk->harga) }}</div>
        <p class="mu">Kategori: {{ $produk->category->nama_kategori }} &middot; Stok: {{ $produk->stok }}</p>
        @if ($produk->stok > 0)
            @auth
            <form method="post" action="{{ route('keranjang.store', $produk) }}">
                @csrf
                <label>Jumlah</label>
                <input type="number" name="qty" value="1" min="1" max="{{ $produk->stok }}">
                <p class="baris">
                    <button class="btn out">Masukkan Keranjang</button>
                    <button class="btn" name="langsung" value="1">Beli Sekarang</button>
                </p>
            </form>
            @else
                <p><a class="btn" href="{{ route('login') }}">Masuk untuk membeli</a></p>
            @endauth
        @else
            <p><span class="badge b-merah">Stok habis</span></p>
        @endif
    </div>
</div>
<div class="card">
    <h2>Deskripsi Produk</h2>
    <p class="mu">{!! nl2br(e($produk->deskripsi ?: 'Belum ada deskripsi.')) !!}</p>
</div>
@endsection
