@extends('layouts.app')
@section('judul', 'Keranjang')
@section('isi')
<h1>Keranjang Belanja</h1>
@if ($items->isEmpty())
    <div class="card"><p class="mu">Keranjangmu masih kosong.</p><a class="btn" href="{{ route('home') }}">Belanja sekarang</a></div>
@else
<div class="card" style="padding:0;overflow:hidden">
<table>
    <thead><tr><th>Produk</th><th>Harga Satuan</th><th>Jumlah</th><th>Subtotal</th><th>Aksi</th></tr></thead>
    <tbody>
    @foreach ($items as $i)
        <tr>
            <td><div class="baris">@include('partials.foto', ['p' => $i->product, 'kelas' => 'kecil'])<a href="{{ route('produk.show', $i->product) }}"><strong>{{ $i->product->nama_produk }}</strong></a></div></td>
            <td>{{ rupiah($i->product->harga) }}</td>
            <td>
                <form method="post" action="{{ route('keranjang.update', $i) }}" class="baris">
                    @csrf @method('PATCH')
                    <input type="number" name="qty" value="{{ $i->qty }}" min="1" max="{{ $i->product->stok }}">
                    <button class="btn sm abu">Ubah</button>
                </form>
            </td>
            <td class="harga">{{ rupiah($i->subtotal) }}</td>
            <td><form method="post" action="{{ route('keranjang.destroy', $i) }}">@csrf @method('DELETE')<button class="link" style="color:var(--or)">Hapus</button></form></td>
        </tr>
    @endforeach
    </tbody>
</table>
</div>
<div class="card spasi">
    <span class="mu">{{ $items->count() }} produk</span>
    <span class="baris"><span class="mu">Total:</span><span class="total">{{ rupiah($total) }}</span><a class="btn" href="{{ route('checkout.index') }}">Checkout</a></span>
</div>
@endif
@endsection
