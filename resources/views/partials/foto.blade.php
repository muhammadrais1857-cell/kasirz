@if ($p->foto_url)
    <img class="foto {{ $kelas ?? '' }}" src="{{ $p->foto_url }}" alt="{{ $p->nama_produk }}">
@else
    <div class="foto kosong {{ $kelas ?? '' }}">Foto</div>
@endif
