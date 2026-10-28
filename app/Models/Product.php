<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Product extends Model
{
    protected $fillable = ['category_id', 'nama_produk', 'deskripsi', 'harga', 'stok', 'gambar'];

    public function category(): BelongsTo
    {
        return $this->belongsTo(Category::class);
    }

    public function orderItems(): HasMany
    {
        return $this->hasMany(OrderItem::class);
    }

    public function getFotoUrlAttribute(): ?string
    {
        return $this->gambar ? asset('uploads/produk/'.$this->gambar) : null;
    }
}
