<?php

namespace App\Http\Controllers;

use App\Models\Order;
use App\Models\Product;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class CheckoutController extends Controller
{
    public function index()
    {
        $items = auth()->user()->cartItems()->with('product')->get();

        if ($items->isEmpty()) {
            return redirect()->route('keranjang.index')->with('galat', 'Keranjang masih kosong.');
        }

        $alamat = auth()->user()->addresses()->latest()->get();
        $subtotal = $items->sum(fn ($item) => $item->subtotal);
        $ongkir = config('toko.ongkir');

        return view('checkout.index', compact('items', 'alamat', 'subtotal', 'ongkir'));
    }

    public function store(Request $request)
    {
        $user = $request->user();
        $items = $user->cartItems()->with('product')->get();

        if ($items->isEmpty()) {
            return redirect()->route('keranjang.index')->with('galat', 'Keranjang masih kosong.');
        }

        try {
            $order = DB::transaction(function () use ($request, $user, $items) {
                if ($request->filled('address_id')) {
                    $alamat = $user->addresses()->findOrFail($request->input('address_id'));
                } else {
                    $data = $request->validate([
                        'penerima' => 'required|string|max:100',
                        'alamat_lengkap' => 'required|string|max:500',
                        'kota' => 'required|string|max:100',
                        'kode_pos' => 'required|string|max:10',
                    ]);
                    $alamat = $user->addresses()->create($data);
                }

                $subtotal = 0;
                foreach ($items as $item) {
                    $produk = Product::lockForUpdate()->find($item->product_id);
                    if (! $produk || $produk->stok < $item->qty) {
                        throw new \RuntimeException('Stok "'.$item->product->nama_produk.'" tidak cukup (sisa '.($produk->stok ?? 0).').');
                    }
                    $subtotal += $produk->harga * $item->qty;
                }

                $ongkir = config('toko.ongkir');
                $order = Order::create([
                    'user_id' => $user->id,
                    'address_id' => $alamat->id,
                    'kode_order' => Order::buatKode(),
                    'ongkir' => $ongkir,
                    'total_bayar' => $subtotal + $ongkir,
                    'status_pesanan' => Order::MENUNGGU,
                ]);

                foreach ($items as $item) {
                    $produk = $item->product;
                    $order->items()->create([
                        'product_id' => $produk->id,
                        'qty' => $item->qty,
                        'harga_satuan' => $produk->harga,
                        'subtotal' => $produk->harga * $item->qty,
                    ]);
                    $produk->decrement('stok', $item->qty);
                }

                $order->payment()->create(['status_bayar' => 'Menunggu']);
                $user->cartItems()->delete();

                return $order;
            });
        } catch (\RuntimeException $e) {
            return redirect()->route('keranjang.index')->with('galat', $e->getMessage());
        }

        return redirect()->route('pembayaran.show', $order);
    }
}
