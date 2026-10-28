@extends('layouts.admin')
@section('judul', 'Pesanan '.$order->kode_order)
@section('isi')
<p><a class="mu" href="{{ route('admin.pesanan.index') }}">&lsaquo; Kembali</a></p>
<div class="dua">
    <div>
        <div class="card">
            <div class="spasi"><h2 style="margin:0">{{ $order->kode_order }}</h2><span class="badge {{ kelas_status($order->status_pesanan) }}">{{ $order->status_pesanan }}</span></div>
            <p class="mu">{{ $order->created_at->format('d/m/Y H:i') }} &middot; {{ $order->user->nama }} ({{ $order->user->email }}, {{ $order->user->no_hp }})</p>
            <table>
                <thead><tr><th>Produk</th><th>Harga</th><th>Qty</th><th>Subtotal</th></tr></thead>
                <tbody>@foreach ($order->items as $i)<tr><td>{{ $i->product->nama_produk }}</td><td>{{ rupiah($i->harga_satuan) }}</td><td>{{ $i->qty }}</td><td>{{ rupiah($i->subtotal) }}</td></tr>@endforeach</tbody>
            </table>
        </div>
        <div class="card"><h2>Alamat Pengiriman</h2><p class="mu">{{ $order->address->ringkas() }}</p></div>
    </div>
    <div>
        <div class="card">
            <h2>Pembayaran</h2>
            <div class="spasi"><span class="mu">Ongkos kirim</span><span>{{ rupiah($order->ongkir) }}</span></div>
            <div class="spasi"><span>Total</span><span class="total">{{ rupiah($order->total_bayar) }}</span></div>
            <div class="spasi"><span class="mu">Status bayar</span><span class="badge {{ kelas_status($order->payment->status_bayar) }}">{{ $order->payment->status_bayar }}</span></div>
            <div class="spasi"><span class="mu">Metode</span><span>{{ $order->payment->metode_bayar ?? '-' }}</span></div>
            <div class="spasi"><span class="mu">ID transaksi</span><span>{{ $order->payment->transaction_id ?? '-' }}</span></div>
        </div>
        @if ($berikutnya)
        <div class="card">
            <h2>Ubah Status</h2>
            @foreach ($berikutnya as $s)
                <form method="post" action="{{ route('admin.pesanan.status', $order) }}" style="margin-bottom:8px">
                    @csrf @method('PATCH')<input type="hidden" name="status" value="{{ $s }}">
                    <button class="btn full {{ $s === 'Dibatalkan' ? 'abu' : '' }}">Tandai {{ $s }}</button>
                </form>
            @endforeach
        </div>
        @endif
    </div>
</div>
@endsection
