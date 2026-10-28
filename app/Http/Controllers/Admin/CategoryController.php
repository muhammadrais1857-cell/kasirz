<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Category;
use Illuminate\Http\Request;

class CategoryController extends Controller
{
    public function index()
    {
        return view('admin.categories.index', ['kategori' => Category::withCount('products')->orderBy('nama_kategori')->get()]);
    }

    public function create()
    {
        return view('admin.categories.form', ['kategori' => new Category()]);
    }

    public function store(Request $request)
    {
        Category::create($this->validasi($request));

        return redirect()->route('admin.kategori.index')->with('sukses', 'Kategori ditambahkan.');
    }

    public function edit(Category $kategori)
    {
        return view('admin.categories.form', compact('kategori'));
    }

    public function update(Request $request, Category $kategori)
    {
        $kategori->update($this->validasi($request));

        return redirect()->route('admin.kategori.index')->with('sukses', 'Kategori diperbarui.');
    }

    public function destroy(Category $kategori)
    {
        if ($kategori->products()->exists()) {
            return back()->with('galat', 'Kategori masih dipakai oleh produk, pindahkan produknya dulu.');
        }

        $kategori->delete();

        return back()->with('sukses', 'Kategori dihapus.');
    }

    private function validasi(Request $request): array
    {
        return $request->validate([
            'nama_kategori' => 'required|string|max:100',
            'deskripsi' => 'nullable|string|max:500',
        ]);
    }
}
