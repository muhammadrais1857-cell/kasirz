@extends('layouts.app')
@section('judul', 'Pesanan '.$order->kode_order)
@section('isi')
<p><a class="mu" href="{{ route('pesanan.index') }}">&lsaquo; Kembali ke riwayat</a></p>
<div class="dua">
    <div>
        <div class="card">
            <div class="spasi"><h2 style="margin:0">{{ $order->kode_order }}</h2><span class="badge {{ kelas_status($order->status_pesanan) }}">{{ $order->status_pesanan }}</span></div>
            <p class="mu">Dibuat {{ $order->created_at->format('d/m/Y H:i') }}</p>
            @foreach ($order->items as $i)
                <div class="baris" style="padding:8px 0">@include('partials.foto', ['p' => $i->product, 'kelas' => 'kecil'])<span class="grow">{{ $i->product->nama_produk }}<br><span class="mu">{{ rupiah($i->harga_satuan) }} x {{ $i->qty }}</span></span><strong>{{ rupiah($i->subtotal) }}</strong></div>
            @endforeach
        </div>
        <div class="card"><h2>Alamat Pengiriman</h2><p class="mu">{{ $order->address->ringkas() }}</p></div>
    </div>
    <div>
        <div class="card">
            <h2>Pembayaran</h2>
            <div class="spasi"><span class="mu">Ongkos kirim</span><span>{{ rupiah($order->ongkir) }}</span></div>
            <div class="spasi"><span>Total</span><span class="total">{{ rupiah($order->total_bayar) }}</span></div>
            <div class="spasi"><span class="mu">Status bayar</span><span class="badge {{ kelas_status($order->payment->status_bayar) }}">{{ $order->payment->status_bayar }}</span></div>
            @if ($order->payment->metode_bayar)<div class="spasi"><span class="mu">Metode</span><span>{{ $order->payment->metode_bayar }}</span></div>@endif
            @if ($order->status_pesanan === 'Menunggu Pembayaran')
                <p><a class="btn full" href="{{ route('pembayaran.show', $order) }}">Bayar Sekarang</a></p>
                <form method="post" action="{{ route('pesanan.batal', $order) }}" onsubmit="return confirm('Batalkan pesanan ini?')">@csrf<button class="btn abu full">Batalkan Pesanan</button></form>
                <p class="mu">Status diperbarui otomatis setelah pembayaran dikonfirmasi.</p>
            @endif
        </div>
    </div>
</div>
@endsection
