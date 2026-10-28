@extends('layouts.app')
@section('isi')
@if (! request()->hasAny(['q', 'kategori', 'page']))
<div class="hero">
    <h1>Belanja Mudah, Bayar Online</h1>
    <p>Pilih produk favoritmu, pesan dari rumah, dan bayar lewat QRIS, virtual account, atau e-wallet.</p>
    <a class="btn" href="#produk">Mulai Belanja</a>
</div>
@endif
<div class="chips">
    <a class="chip {{ request('kategori') ? '' : 'on' }}" href="{{ route('home') }}">Semua</a>
    @foreach ($kategori as $k)
        <a class="chip {{ request('kategori') == $k->id ? 'on' : '' }}" href="{{ route('home', ['kategori' => $k->id]) }}">{{ $k->nama_kategori }}</a>
    @endforeach
</div>
<h2 id="produk">{{ request('q') ? 'Hasil pencarian "'.request('q').'"' : 'Produk' }}</h2>
<div class="grid">
    @forelse ($produk as $p)
        <a class="produk" href="{{ route('produk.show', $p) }}">
            @include('partials.foto', ['p' => $p])
            <div class="info">
                <div class="nama">{{ $p->nama_produk }}</div>
                <div class="harga">{{ rupiah($p->harga) }}</div>
                <div class="meta">{{ $p->stok > 0 ? 'Stok '.$p->stok : 'Stok habis' }}</div>
            </div>
        </a>
    @empty
        <p class="mu">Belum ada produk yang cocok.</p>
    @endforelse
</div>
{{ $produk->links() }}
@endsection
