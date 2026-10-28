@extends('layouts.admin')
@section('judul', 'Produk')
@section('isi')
<div class="spasi"><h1>Produk</h1><a class="btn" href="{{ route('admin.produk.create') }}">+ Tambah Produk</a></div>
<form class="cari-adm" method="get"><input name="q" value="{{ request('q') }}" placeholder="Cari produk..."><button class="btn abu">Cari</button></form>
<div class="card" style="padding:0;overflow:hidden">
<table>
    <thead><tr><th>Foto</th><th>Nama</th><th>Kategori</th><th>Harga</th><th>Stok</th><th>Aksi</th></tr></thead>
    <tbody>
    @forelse ($produk as $p)
        <tr>
            <td>@include('partials.foto', ['p' => $p, 'kelas' => 'kecil'])</td>
            <td><strong>{{ $p->nama_produk }}</strong></td><td>{{ $p->category->nama_kategori }}</td><td>{{ rupiah($p->harga) }}</td>
            <td>{{ $p->stok }} @if ($p->stok <= config('toko.stok_menipis'))<span class="badge b-merah">menipis</span>@endif</td>
            <td class="aksi">
                <a class="btn sm out" href="{{ route('admin.produk.edit', $p) }}">Edit</a>
                <form method="post" action="{{ route('admin.produk.destroy', $p) }}" onsubmit="return confirm('Hapus produk ini?')">@csrf @method('DELETE')<button class="btn sm abu">Hapus</button></form>
            </td>
        </tr>
    @empty
        <tr><td colspan="6" class="mu">Belum ada produk.</td></tr>
    @endforelse
    </tbody>
</table>
</div>
{{ $produk->links() }}
@endsection
