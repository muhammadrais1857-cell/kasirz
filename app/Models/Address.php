<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Address extends Model
{
    protected $fillable = ['user_id', 'penerima', 'alamat_lengkap', 'kota', 'kode_pos'];

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function ringkas(): string
    {
        return "{$this->penerima} - {$this->alamat_lengkap}, {$this->kota} {$this->kode_pos}";
    }
}
