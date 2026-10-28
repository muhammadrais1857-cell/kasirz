@extends('layouts.app')
@section('judul', 'Riwayat Pesanan')
@section('isi')
<h1>Riwayat Pesanan</h1>
<div class="tabs">
    <a href="{{ route('pesanan.index') }}" class="{{ $status ? '' : 'on' }}">Semua</a>
    @foreach (['Menunggu Pembayaran', 'Dibayar', 'Diproses', 'Dikirim', 'Selesai', 'Dibatalkan'] as $s)
        <a href="{{ route('pesanan.index', ['status' => $s]) }}" class="{{ $status === $s ? 'on' : '' }}">{{ $s }}</a>
    @endforeach
</div>
@forelse ($pesanan as $o)
<div class="card">
    <div class="spasi"><span class="mu">{{ $o->kode_order }} &middot; {{ $o->created_at->format('d/m/Y H:i') }}</span><span class="badge {{ kelas_status($o->status_pesanan) }}">{{ $o->status_pesanan }}</span></div>
    @foreach ($o->items as $i)
        <div class="baris" style="padding:8px 0">@include('partials.foto', ['p' => $i->product, 'kelas' => 'kecil'])<span class="grow">{{ $i->product->nama_produk }}</span><span class="mu">x{{ $i->qty }}</span><strong>{{ rupiah($i->subtotal) }}</strong></div>
    @endforeach
    <hr style="border:0;border-top:1px solid var(--bd)">
    <div class="spasi">
        <span><span class="mu">Total:</span> <span class="total">{{ rupiah($o->total_bayar) }}</span></span>
        <span class="aksi">
            @if ($o->status_pesanan === 'Menunggu Pembayaran')<a class="btn" href="{{ route('pembayaran.show', $o) }}">Bayar Sekarang</a>@endif
            <a class="btn out" href="{{ route('pesanan.show', $o) }}">Detail</a>
        </span>
    </div>
</div>
@empty
    <div class="card"><p class="mu">Belum ada pesanan.</p></div>
@endforelse
{{ $pesanan->links() }}
@endsection
