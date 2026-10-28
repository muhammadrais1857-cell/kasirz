<?php

namespace App\Http\Controllers;

use App\Models\Order;
use Illuminate\Http\Request;

class OrderController extends Controller
{
    public function index(Request $request)
    {
        $status = $request->query('status');

        $pesanan = auth()->user()->orders()
            ->with('items.product', 'payment')
            ->when($status, fn ($q, $s) => $q->where('status_pesanan', $s))
            ->latest()
            ->paginate(8)
            ->withQueryString();

        return view('orders.index', compact('pesanan', 'status'));
    }

    public function show(Order $order)
    {
        abort_unless($order->user_id === auth()->id(), 404);
        $order->load('items.product', 'payment', 'address');

        return view('orders.show', compact('order'));
    }

    public function batal(Order $order)
    {
        abort_unless($order->user_id === auth()->id(), 404);

        if ($order->status_pesanan !== Order::MENUNGGU) {
            return back()->with('galat', 'Hanya pesanan yang belum dibayar yang bisa dibatalkan.');
        }

        $order->batalkan();

        return redirect()->route('pesanan.show', $order)->with('sukses', 'Pesanan dibatalkan dan stok dikembalikan.');
    }
}
