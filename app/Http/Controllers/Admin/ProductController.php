<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Category;
use App\Models\Product;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class ProductController extends Controller
{
    public function index(Request $request)
    {
        $produk = Product::with('category')
            ->when($request->q, fn ($q, $cari) => $q->where('nama_produk', 'like', "%{$cari}%"))
            ->latest()
            ->paginate(10)
            ->withQueryString();

        return view('admin.products.index', compact('produk'));
    }

    public function create()
    {
        return view('admin.products.form', ['produk' => new Product(), 'kategori' => Category::orderBy('nama_kategori')->get()]);
    }

    public function store(Request $request)
    {
        $data = $this->validasi($request);
        $data['gambar'] = $this->simpanFoto($request);
        Product::create($data);

        return redirect()->route('admin.produk.index')->with('sukses', 'Produk ditambahkan.');
    }

    public function edit(Product $produk)
    {
        return view('admin.products.form', ['produk' => $produk, 'kategori' => Category::orderBy('nama_kategori')->get()]);
    }

    public function update(Request $request, Product $produk)
    {
        $data = $this->validasi($request);
        $data['gambar'] = $this->simpanFoto($request, $produk->gambar);
        $produk->update($data);

        return redirect()->route('admin.produk.index')->with('sukses', 'Produk diperbarui.');
    }

    public function destroy(Product $produk)
    {
        if ($produk->orderItems()->exists()) {
            return back()->with('galat', 'Produk sudah pernah dipesan sehingga tidak bisa dihapus. Set stok ke 0 saja.');
        }

        if ($produk->gambar) {
            @unlink(public_path('uploads/produk/'.$produk->gambar));
        }
        $produk->delete();

        return back()->with('sukses', 'Produk dihapus.');
    }

    private function validasi(Request $request): array
    {
        return $request->validate([
            'category_id' => 'required|exists:categories,id',
            'nama_produk' => 'required|string|max:150',
            'deskripsi' => 'nullable|string',
            'harga' => 'required|integer|min:0',
            'stok' => 'required|integer|min:0',
            'gambar' => 'nullable|image|mimes:jpg,jpeg,png,webp|max:2048',
        ]);
    }

    private function simpanFoto(Request $request, ?string $lama = null): ?string
    {
        if (! $request->hasFile('gambar')) {
            return $lama;
        }

        $file = $request->file('gambar');
        $nama = Str::uuid().'.'.$file->extension();
        $file->move(public_path('uploads/produk'), $nama);

        if ($lama) {
            @unlink(public_path('uploads/produk/'.$lama));
        }

        return $nama;
    }
}
