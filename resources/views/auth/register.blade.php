@extends('layouts.app')
@section('judul', 'Daftar')
@section('isi')
<div class="auth card">
    <h1>Daftar Akun</h1>
    <form method="post" action="{{ route('register') }}">
        @csrf
        <label>Nama lengkap</label><input name="nama" value="{{ old('nama') }}" required>
        <label>Email</label><input type="email" name="email" value="{{ old('email') }}" required>
        <label>No. HP</label><input name="no_hp" value="{{ old('no_hp') }}" required>
        <label>Kata sandi (min. 8 karakter)</label><input type="password" name="password" required>
        <label>Ulangi kata sandi</label><input type="password" name="password_confirmation" required>
        <p><button class="btn full">Daftar</button></p>
    </form>
    <p class="mu">Sudah punya akun? <a href="{{ route('login') }}" style="color:var(--or);font-weight:600">Masuk</a></p>
</div>
@endsection
