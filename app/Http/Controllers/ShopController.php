<?php

namespace App\Http\Controllers;

use App\Models\Category;
use App\Models\Product;
use Illuminate\Http\Request;

class ShopController extends Controller
{
    public function index(Request $request)
    {
        $produk = Product::with('category')
            ->when($request->q, fn ($q, $cari) => $q->where('nama_produk', 'like', "%{$cari}%"))
            ->when($request->kategori, fn ($q, $kategori) => $q->where('category_id', $kategori))
            ->latest()
            ->paginate(12)
            ->withQueryString();

        $kategori = Category::orderBy('nama_kategori')->get();

        return view('shop.index', compact('produk', 'kategori'));
    }

    public function show(Product $product)
    {
        $product->load('category');

        return view('shop.show', ['produk' => $product]);
    }
}
