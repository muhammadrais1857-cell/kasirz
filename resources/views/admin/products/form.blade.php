@extends('layouts.admin')
@section('judul', $produk->exists ? 'Edit Produk' : 'Tambah Produk')
@section('isi')
<h1>{{ $produk->exists ? 'Edit Produk' : 'Tambah Produk' }}</h1>
<form class="card" style="max-width:640px" method="post" enctype="multipart/form-data"
      action="{{ $produk->exists ? route('admin.produk.update', $produk) : route('admin.produk.store') }}">
    @csrf
    @if ($produk->exists) @method('PUT') @endif
    <label>Nama produk</label><input name="nama_produk" value="{{ old('nama_produk', $produk->nama_produk) }}" required>
    <label>Kategori</label>
    <select name="category_id" required>
        <option value="">- pilih -</option>
        @foreach ($kategori as $k)<option value="{{ $k->id }}" @selected(old('category_id', $produk->category_id) == $k->id)>{{ $k->nama_kategori }}</option>@endforeach
    </select>
    <div class="dua sama" style="gap:12px">
        <div><label>Harga (Rp)</label><input type="number" min="0" name="harga" style="width:100%" value="{{ old('harga', $produk->harga) }}" required></div>
        <div><label>Stok</label><input type="number" min="0" name="stok" style="width:100%" value="{{ old('stok', $produk->stok) }}" required></div>
    </div>
    <label>Deskripsi</label><textarea name="deskripsi">{{ old('deskripsi', $produk->deskripsi) }}</textarea>
    <label>Foto produk (jpg/png/webp, maks. 2 MB)</label>
    @if ($produk->foto_url)<p><img src="{{ $produk->foto_url }}" alt="" style="width:120px;border-radius:6px"></p>@endif
    <input type="file" name="gambar" accept="image/*">
    <p class="baris"><button class="btn">Simpan</button><a class="btn abu" href="{{ route('admin.produk.index') }}">Batal</a></p>
</form>
@endsection
