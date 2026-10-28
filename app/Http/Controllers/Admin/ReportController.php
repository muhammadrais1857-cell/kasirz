<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Order;
use App\Models\OrderItem;
use Illuminate\Http\Request;

class ReportController extends Controller
{
    public function index(Request $request)
    {
        $data = $request->validate([
            'dari' => 'nullable|date',
            'sampai' => 'nullable|date|after_or_equal:dari',
        ]);

        $dari = ($data['dari'] ?? null) ? \Carbon\Carbon::parse($data['dari'])->startOfDay() : now()->startOfMonth();
        $sampai = ($data['sampai'] ?? null) ? \Carbon\Carbon::parse($data['sampai'])->endOfDay() : now()->endOfDay();

        $pesanan = Order::with('user')
            ->whereIn('status_pesanan', Order::STATUS_TERJUAL)
            ->whereBetween('created_at', [$dari, $sampai])
            ->orderBy('created_at')
            ->get();

        $perHari = $pesanan->groupBy(fn ($o) => $o->created_at->format('Y-m-d'))
            ->map(fn ($g) => ['jumlah' => $g->count(), 'total' => $g->sum('total_bayar')]);

        $terlaris = OrderItem::selectRaw('product_id, SUM(qty) as terjual, SUM(subtotal) as omzet')
            ->whereHas('order', fn ($q) => $q->whereIn('status_pesanan', Order::STATUS_TERJUAL)->whereBetween('created_at', [$dari, $sampai]))
            ->groupBy('product_id')
            ->orderByDesc('terjual')
            ->with('product')
            ->limit(5)
            ->get();

        return view('admin.reports.index', [
            'dari' => $dari->toDateString(),
            'sampai' => $sampai->toDateString(),
            'pesanan' => $pesanan,
            'perHari' => $perHari,
            'terlaris' => $terlaris,
            'totalPenjualan' => $pesanan->sum('total_bayar'),
        ]);
    }
}
