<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Order;
use Illuminate\Http\Request;

class OrderController extends Controller
{
    /** Alur status yang diizinkan. */
    private const ALUR = [
        Order::MENUNGGU => [Order::DIBATALKAN],
        Order::DIBAYAR => [Order::DIPROSES],
        Order::DIPROSES => [Order::DIKIRIM],
        Order::DIKIRIM => [Order::SELESAI],
    ];

    public function index(Request $request)
    {
        $pesanan = Order::with('user', 'payment')
            ->when($request->status, fn ($q, $s) => $q->where('status_pesanan', $s))
            ->when($request->q, fn ($q, $cari) => $q->where('kode_order', 'like', "%{$cari}%"))
            ->latest()
            ->paginate(10)
            ->withQueryString();

        return view('admin.orders.index', compact('pesanan'));
    }

    public function show(Order $order)
    {
        $order->load('user', 'address', 'items.product', 'payment');
        $berikutnya = self::ALUR[$order->status_pesanan] ?? [];

        return view('admin.orders.show', compact('order', 'berikutnya'));
    }

    public function updateStatus(Request $request, Order $order)
    {
        $data = $request->validate(['status' => 'required|string']);
        $boleh = self::ALUR[$order->status_pesanan] ?? [];

        if (! in_array($data['status'], $boleh, true)) {
            return back()->with('galat', 'Perubahan status tidak diizinkan dari "'.$order->status_pesanan.'".');
        }

        if ($data['status'] === Order::DIBATALKAN) {
            $order->batalkan();
        } else {
            $order->update(['status_pesanan' => $data['status']]);
        }

        return back()->with('sukses', 'Status pesanan diperbarui menjadi '.$data['status'].'.');
    }
}
