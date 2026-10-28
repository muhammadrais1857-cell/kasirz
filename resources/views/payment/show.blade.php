@extends('layouts.app')
@section('judul', 'Pembayaran')
@push('head')
@unless ($simulasi)
<script src="{{ config('midtrans.is_production') ? 'https://app.midtrans.com' : 'https://app.sandbox.midtrans.com' }}/snap/snap.js" data-client-key="{{ config('midtrans.client_key') }}"></script>
@endunless
@endpush
@section('isi')
<h1>Pembayaran</h1>
<div class="card" style="max-width:640px">
    <div class="spasi"><span class="mu">Kode pesanan</span><strong>{{ $order->kode_order }}</strong></div>
    @foreach ($order->items as $i)
        <div class="spasi"><span>{{ $i->product->nama_produk }} <span class="mu">x{{ $i->qty }}</span></span><span>{{ rupiah($i->subtotal) }}</span></div>
    @endforeach
    <div class="spasi"><span class="mu">Ongkos kirim</span><span>{{ rupiah($order->ongkir) }}</span></div>
    <hr style="border:0;border-top:1px solid var(--bd)">
    <div class="spasi"><span>Total yang harus dibayar</span><span class="total">{{ rupiah($order->total_bayar) }}</span></div>

    @if ($simulasi)
        <div class="flash ok" style="margin-top:16px">Mode simulasi aktif (kunci Midtrans belum diisi). Tombol di bawah mensimulasikan pembayaran berhasil.</div>
        <form method="post" action="{{ route('pembayaran.simulasi', $order) }}">@csrf<button class="btn full">Bayar (Simulasi)</button></form>
    @else
        <p class="mu">Klik tombol di bawah untuk memilih QRIS, virtual account, atau e-wallet.</p>
        <button id="bayar" class="btn full">Bayar Sekarang</button>
    @endif
    <p class="mu" style="margin-top:12px"><a href="{{ route('pesanan.show', $order) }}">Bayar nanti, lihat detail pesanan</a></p>
</div>
@endsection
@push('scripts')
@unless ($simulasi)
<script>
    const tujuan = @json(route('pesanan.show', $order));
    document.getElementById('bayar').addEventListener('click', function () {
        snap.pay(@json($snapToken), {
            onSuccess: function () { window.location = tujuan; },
            onPending: function () { window.location = tujuan; },
            onError: function () { alert('Pembayaran gagal, silakan coba lagi.'); },
            onClose: function () {}
        });
    });
</script>
@endunless
@endpush
