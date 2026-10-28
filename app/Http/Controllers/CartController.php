<?php

namespace App\Http\Controllers;

use App\Models\CartItem;
use App\Models\Product;
use Illuminate\Http\Request;

class CartController extends Controller
{
    public function index()
    {
        $items = auth()->user()->cartItems()->with('product')->get();
        $total = $items->sum(fn ($item) => $item->subtotal);

        return view('cart.index', compact('items', 'total'));
    }

    public function store(Request $request, Product $product)
    {
        $data = $request->validate(['qty' => 'nullable|integer|min:1']);
        $qty = $data['qty'] ?? 1;

        if ($product->stok < 1) {
            return back()->with('galat', 'Stok produk habis.');
        }

        $item = CartItem::firstOrNew(['user_id' => auth()->id(), 'product_id' => $product->id]);
        $item->qty = min(($item->exists ? $item->qty : 0) + $qty, $product->stok);
        $item->save();

        if ($request->boolean('langsung')) {
            return redirect()->route('keranjang.index');
        }

        return back()->with('sukses', 'Produk dimasukkan ke keranjang.');
    }

    public function update(Request $request, CartItem $item)
    {
        abort_unless($item->user_id === auth()->id(), 404);
        $data = $request->validate(['qty' => 'required|integer|min:1']);
        $item->update(['qty' => min($data['qty'], max($item->product->stok, 1))]);

        return back();
    }

    public function destroy(CartItem $item)
    {
        abort_unless($item->user_id === auth()->id(), 404);
        $item->delete();

        return back()->with('sukses', 'Item dihapus dari keranjang.');
    }
}
