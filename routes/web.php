<?php

use App\Http\Controllers\Admin;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\CartController;
use App\Http\Controllers\CheckoutController;
use App\Http\Controllers\OrderController;
use App\Http\Controllers\PaymentController;
use App\Http\Controllers\ShopController;
use Illuminate\Support\Facades\Route;

// Katalog (publik)
Route::get('/', [ShopController::class, 'index'])->name('home');
Route::get('/produk/{product}', [ShopController::class, 'show'])->name('produk.show');

// Webhook Midtrans (tanpa CSRF, diverifikasi lewat signature)
Route::post('/midtrans/notifikasi', [PaymentController::class, 'notifikasi'])->name('midtrans.notifikasi');

// Tamu
Route::middleware('guest')->group(function () {
    Route::get('/masuk', [AuthController::class, 'showLogin'])->name('login');
    Route::post('/masuk', [AuthController::class, 'login']);
    Route::get('/daftar', [AuthController::class, 'showRegister'])->name('register');
    Route::post('/daftar', [AuthController::class, 'register']);
});

// Pelanggan
Route::middleware('auth')->group(function () {
    Route::post('/keluar', [AuthController::class, 'logout'])->name('logout');

    Route::get('/keranjang', [CartController::class, 'index'])->name('keranjang.index');
    Route::post('/keranjang/tambah/{product}', [CartController::class, 'store'])->name('keranjang.store');
    Route::patch('/keranjang/{item}', [CartController::class, 'update'])->name('keranjang.update');
    Route::delete('/keranjang/{item}', [CartController::class, 'destroy'])->name('keranjang.destroy');

    Route::get('/checkout', [CheckoutController::class, 'index'])->name('checkout.index');
    Route::post('/checkout', [CheckoutController::class, 'store'])->name('checkout.store');

    Route::get('/pesanan', [OrderController::class, 'index'])->name('pesanan.index');
    Route::get('/pesanan/{order}', [OrderController::class, 'show'])->name('pesanan.show');
    Route::post('/pesanan/{order}/batal', [OrderController::class, 'batal'])->name('pesanan.batal');

    Route::get('/pembayaran/{order}', [PaymentController::class, 'show'])->name('pembayaran.show');
    Route::post('/pembayaran/{order}/simulasi', [PaymentController::class, 'simulasi'])->name('pembayaran.simulasi');
});

// Admin
Route::prefix('admin')->name('admin.')->middleware(['auth', 'admin'])->group(function () {
    Route::get('/', [Admin\DashboardController::class, 'index'])->name('dashboard');
    Route::resource('produk', Admin\ProductController::class)->except('show')->parameters(['produk' => 'produk']);
    Route::resource('kategori', Admin\CategoryController::class)->except('show')->parameters(['kategori' => 'kategori']);
    Route::get('/pesanan', [Admin\OrderController::class, 'index'])->name('pesanan.index');
    Route::get('/pesanan/{order}', [Admin\OrderController::class, 'show'])->name('pesanan.show');
    Route::patch('/pesanan/{order}/status', [Admin\OrderController::class, 'updateStatus'])->name('pesanan.status');
    Route::get('/laporan', [Admin\ReportController::class, 'index'])->name('laporan');
});
