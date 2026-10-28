@extends('layouts.app')
@section('judul', 'Checkout')
@section('isi')
<h1>Checkout</h1>
<form method="post" action="{{ route('checkout.store') }}" class="dua">
    @csrf
    <div>
        <div class="card">
            <h2>Alamat Pengiriman</h2>
            @foreach ($alamat as $a)
                <label class="opsi"><input type="radio" name="address_id" value="{{ $a->id }}" {{ $loop->first && ! old('penerima') ? 'checked' : '' }}> {{ $a->ringkas() }}</label>
            @endforeach
            @if ($alamat->isNotEmpty())
                <label class="opsi"><input type="radio" name="address_id" value="" {{ old('penerima') ? 'checked' : '' }}> Gunakan alamat baru (isi form di bawah)</label>
            @endif
            <label>Nama penerima</label><input name="penerima" value="{{ old('penerima') }}">
            <label>Alamat lengkap</label><textarea name="alamat_lengkap">{{ old('alamat_lengkap') }}</textarea>
            <div class="dua sama" style="gap:12px">
                <div><label>Kota</label><input name="kota" value="{{ old('kota') }}" style="width:100%"></div>
                <div><label>Kode pos</label><input name="kode_pos" value="{{ old('kode_pos') }}" style="width:100%"></div>
            </div>
        </div>
        <div class="card">
            <h2>Pesanan Anda</h2>
            @foreach ($items as $i)
                <div class="baris" style="padding:8px 0">
                    @include('partials.foto', ['p' => $i->product, 'kelas' => 'kecil'])
                    <span class="grow">{{ $i->product->nama_produk }}</span>
                    <span class="mu">x{{ $i->qty }}</span>
                    <strong>{{ rupiah($i->subtotal) }}</strong>
                </div>
            @endforeach
        </div>
        <div class="card">
            <h2>Metode Pembayaran</h2>
            <p class="mu">Metode (QRIS, virtual account, atau e-wallet) dipilih pada halaman pembayaran setelah pesanan dibuat. Pembayaran diproses lewat payment gateway Midtrans.</p>
        </div>
    </div>
    <div class="card">
        <h2>Ringkasan Pembayaran</h2>
        <div class="spasi"><span class="mu">Subtotal produk</span><span>{{ rupiah($subtotal) }}</span></div>
        <div class="spasi"><span class="mu">Ongkos kirim</span><span>{{ rupiah($ongkir) }}</span></div>
        <hr style="border:0;border-top:1px solid var(--bd)">
        <div class="spasi"><span>Total pembayaran</span><span class="total">{{ rupiah($subtotal + $ongkir) }}</span></div>
        <p><button class="btn full">Buat Pesanan &amp; Bayar</button></p>
    </div>
</form>
@endsection
