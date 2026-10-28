@extends('layouts.admin')
@section('judul', 'Kategori')
@section('isi')
<div class="spasi"><h1>Kategori</h1><a class="btn" href="{{ route('admin.kategori.create') }}">+ Tambah Kategori</a></div>
<div class="card" style="padding:0;overflow:hidden">
<table>
    <thead><tr><th>Nama</th><th>Deskripsi</th><th>Jumlah Produk</th><th>Aksi</th></tr></thead>
    <tbody>
    @forelse ($kategori as $k)
        <tr><td><strong>{{ $k->nama_kategori }}</strong></td><td class="mu">{{ $k->deskripsi }}</td><td>{{ $k->products_count }}</td>
            <td class="aksi"><a class="btn sm out" href="{{ route('admin.kategori.edit', $k) }}">Edit</a>
                <form method="post" action="{{ route('admin.kategori.destroy', $k) }}" onsubmit="return confirm('Hapus kategori ini?')">@csrf @method('DELETE')<button class="btn sm abu">Hapus</button></form></td></tr>
    @empty
        <tr><td colspan="4" class="mu">Belum ada kategori.</td></tr>
    @endforelse
    </tbody>
</table>
</div>
@endsection
