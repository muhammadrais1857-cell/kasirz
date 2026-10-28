<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Order;
use App\Models\Product;

class DashboardController extends Controller
{
    public function index()
    {
        $stat = [
            'penjualan' => Order::whereIn('status_pesanan', Order::STATUS_TERJUAL)->sum('total_bayar'),
            'pesanan_baru' => Order::where('status_pesanan', Order::DIBAYAR)->count(),
            'produk' => Product::count(),
            'stok_menipis' => Product::where('stok', '<=', config('toko.stok_menipis'))->count(),
        ];

        $terbaru = Order::with('user', 'payment')->latest()->limit(6)->get();

        return view('admin.dashboard', compact('stat', 'terbaru'));
    }
}
