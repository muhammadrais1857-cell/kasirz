@extends('layouts.app')
@section('judul', 'Masuk')
@section('isi')
<div class="auth card">
    <h1>Masuk</h1>
    <form method="post" action="{{ route('login') }}">
        @csrf
        <label>Email</label><input type="email" name="email" value="{{ old('email') }}" required autofocus>
        <label>Kata sandi</label><input type="password" name="password" required>
        <label class="opsi" style="border:0;padding:8px 0"><input type="checkbox" name="ingat" value="1" style="width:auto"> Ingat saya</label>
        <button class="btn full">Masuk</button>
    </form>
    <p class="mu">Belum punya akun? <a href="{{ route('register') }}" style="color:var(--or);font-weight:600">Daftar</a></p>
</div>
@endsection
