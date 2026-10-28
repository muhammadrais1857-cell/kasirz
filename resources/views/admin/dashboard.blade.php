@extends('layouts.admin')
@section('judul', 'Dashboard')
@section('isi')
<h1>Dashboard</h1>
<div class="stat">
    <div class="card"><div class="mu">Total Penjualan</div><div class="angka">{{ rupiah($stat['penjualan']) }}</div></div>
    <div class="card"><div class="mu">Pesanan Baru (Dibayar)</div><div class="angka">{{ $stat['pesanan_baru'] }}</div></div>
    <div class="card"><div class="mu">Produk Aktif</div><div class="angka">{{ $stat['produk'] }}</div></div>
    <div class="card"><div class="mu">Stok Menipis</div><div class="angka" style="color:var(--or)">{{ $stat['stok_menipis'] }}</div></div>
</div>
<div class="card" style="padding:0;overflow:hidden">
    <div class="spasi" style="padding:16px 20px"><h2 style="margin:0">Pesanan Terbaru</h2><a style="color:var(--or);font-weight:600" href="{{ route('admin.pesanan.index') }}">Lihat semua</a></div>
    <table>
        <thead><tr><th>Kode</th><th>Pelanggan</th><th>Total</th><th>Pembayaran</th><th>Status</th><th>Aksi</th></tr></thead>
        <tbody>
        @forelse ($terbaru as $o)
            <tr><td><strong>{{ $o->kode_order }}</strong></td><td>{{ $o->user->nama }}</td><td>{{ rupiah($o->total_bayar) }}</td>
                <td><span class="badge {{ kelas_status($o->payment->status_bayar) }}">{{ $o->payment->status_bayar }}</span></td>
                <td><span class="badge {{ kelas_status($o->status_pesanan) }}">{{ $o->status_pesanan }}</span></td>
                <td><a style="color:var(--or);font-weight:600" href="{{ route('admin.pesanan.show', $o) }}">Detail</a></td></tr>
        @empty
            <tr><td colspan="6" class="mu">Belum ada pesanan.</td></tr>
        @endforelse
        </tbody>
    </table>
</div>
@endsection
