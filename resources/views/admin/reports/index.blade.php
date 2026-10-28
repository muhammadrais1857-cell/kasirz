@extends('layouts.admin')
@section('judul', 'Laporan Penjualan')
@section('isi')
<h1>Laporan Penjualan</h1>
<form class="cari-adm" method="get">
    <input type="date" name="dari" value="{{ $dari }}" style="max-width:170px"><input type="date" name="sampai" value="{{ $sampai }}" style="max-width:170px"><button class="btn abu">Tampilkan</button>
</form>
<div class="stat" style="grid-template-columns:repeat(2,1fr)">
    <div class="card"><div class="mu">Total penjualan (pesanan sudah dibayar)</div><div class="angka">{{ rupiah($totalPenjualan) }}</div></div>
    <div class="card"><div class="mu">Jumlah pesanan</div><div class="angka">{{ $pesanan->count() }}</div></div>
</div>
<div class="dua sama">
    <div class="card" style="padding:0;overflow:hidden">
        <h2 style="padding:16px 20px 0">Per Hari</h2>
        <table><thead><tr><th>Tanggal</th><th>Pesanan</th><th>Total</th></tr></thead><tbody>
        @forelse ($perHari as $tgl => $r)<tr><td>{{ \Carbon\Carbon::parse($tgl)->format('d/m/Y') }}</td><td>{{ $r['jumlah'] }}</td><td>{{ rupiah($r['total']) }}</td></tr>
        @empty<tr><td colspan="3" class="mu">Tidak ada penjualan pada rentang ini.</td></tr>@endforelse
        </tbody></table>
    </div>
    <div class="card" style="padding:0;overflow:hidden">
        <h2 style="padding:16px 20px 0">Produk Terlaris</h2>
        <table><thead><tr><th>Produk</th><th>Terjual</th><th>Omzet</th></tr></thead><tbody>
        @forelse ($terlaris as $t)<tr><td>{{ $t->product->nama_produk }}</td><td>{{ $t->terjual }}</td><td>{{ rupiah($t->omzet) }}</td></tr>
        @empty<tr><td colspan="3" class="mu">Belum ada data.</td></tr>@endforelse
        </tbody></table>
    </div>
</div>
@endsection
