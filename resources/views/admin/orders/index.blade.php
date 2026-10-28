@extends('layouts.admin')
@section('judul', 'Pesanan')
@section('isi')
<h1>Pesanan</h1>
<form class="cari-adm" method="get">
    <input name="q" value="{{ request('q') }}" placeholder="Cari kode pesanan...">
    <select name="status" style="max-width:220px"><option value="">Semua status</option>
        @foreach (\App\Models\Order::SEMUA_STATUS as $s)<option @selected(request('status') === $s)>{{ $s }}</option>@endforeach
    </select>
    <button class="btn abu">Filter</button>
</form>
<div class="card" style="padding:0;overflow:hidden">
<table>
    <thead><tr><th>Kode</th><th>Tanggal</th><th>Pelanggan</th><th>Total</th><th>Pembayaran</th><th>Status</th><th>Aksi</th></tr></thead>
    <tbody>
    @forelse ($pesanan as $o)
        <tr><td><strong>{{ $o->kode_order }}</strong></td><td>{{ $o->created_at->format('d/m/Y H:i') }}</td><td>{{ $o->user->nama }}</td><td>{{ rupiah($o->total_bayar) }}</td>
            <td><span class="badge {{ kelas_status($o->payment->status_bayar) }}">{{ $o->payment->status_bayar }}</span></td>
            <td><span class="badge {{ kelas_status($o->status_pesanan) }}">{{ $o->status_pesanan }}</span></td>
            <td><a class="btn sm out" href="{{ route('admin.pesanan.show', $o) }}">Detail</a></td></tr>
    @empty
        <tr><td colspan="7" class="mu">Tidak ada pesanan.</td></tr>
    @endforelse
    </tbody>
</table>
</div>
{{ $pesanan->links() }}
@endsection
