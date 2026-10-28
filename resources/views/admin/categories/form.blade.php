@extends('layouts.admin')
@section('judul', $kategori->exists ? 'Edit Kategori' : 'Tambah Kategori')
@section('isi')
<h1>{{ $kategori->exists ? 'Edit Kategori' : 'Tambah Kategori' }}</h1>
<form class="card" style="max-width:520px" method="post" action="{{ $kategori->exists ? route('admin.kategori.update', $kategori) : route('admin.kategori.store') }}">
    @csrf
    @if ($kategori->exists) @method('PUT') @endif
    <label>Nama kategori</label><input name="nama_kategori" value="{{ old('nama_kategori', $kategori->nama_kategori) }}" required>
    <label>Deskripsi</label><textarea name="deskripsi">{{ old('deskripsi', $kategori->deskripsi) }}</textarea>
    <p class="baris"><button class="btn">Simpan</button><a class="btn abu" href="{{ route('admin.kategori.index') }}">Batal</a></p>
</form>
@endsection
