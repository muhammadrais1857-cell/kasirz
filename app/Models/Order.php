<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

class Order extends Model
{
    public const MENUNGGU = 'Menunggu Pembayaran';
    public const DIBAYAR = 'Dibayar';
    public const DIPROSES = 'Diproses';
    public const DIKIRIM = 'Dikirim';
    public const SELESAI = 'Selesai';
    public const DIBATALKAN = 'Dibatalkan';

    public const SEMUA_STATUS = [self::MENUNGGU, self::DIBAYAR, self::DIPROSES, self::DIKIRIM, self::SELESAI, self::DIBATALKAN];

    /** Status yang dihitung sebagai penjualan (sudah dibayar). */
    public const STATUS_TERJUAL = [self::DIBAYAR, self::DIPROSES, self::DIKIRIM, self::SELESAI];

    protected $fillable = ['user_id', 'address_id', 'kode_order', 'ongkir', 'total_bayar', 'status_pesanan'];

    public static function buatKode(): string
    {
        do {
            $kode = 'INV-'.now()->format('ymd').'-'.strtoupper(Str::random(5));
        } while (self::where('kode_order', $kode)->exists());

        return $kode;
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function address(): BelongsTo
    {
        return $this->belongsTo(Address::class);
    }

    public function items(): HasMany
    {
        return $this->hasMany(OrderItem::class);
    }

    public function payment(): HasOne
    {
        return $this->hasOne(Payment::class);
    }

    public function tandaiDibayar(?string $metode = null, ?string $transaksiId = null): void
    {
        DB::transaction(function () use ($metode, $transaksiId) {
            $this->payment()->update([
                'status_bayar' => 'Berhasil',
                'metode_bayar' => $metode,
                'transaction_id' => $transaksiId,
                'paid_at' => now(),
            ]);

            if ($this->status_pesanan === self::MENUNGGU) {
                $this->update(['status_pesanan' => self::DIBAYAR]);
            }
        });
    }

    /** Batalkan pesanan yang belum dibayar dan kembalikan stok. */
    public function batalkan(): void
    {
        if ($this->status_pesanan !== self::MENUNGGU) {
            return;
        }

        DB::transaction(function () {
            foreach ($this->items as $item) {
                Product::where('id', $item->product_id)->increment('stok', $item->qty);
            }
            $this->payment()->update(['status_bayar' => 'Gagal']);
            $this->update(['status_pesanan' => self::DIBATALKAN]);
        });
    }
}
