<?php

namespace Tests\Feature;

use App\Models\Category;
use App\Models\Order;
use App\Models\Product;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class TokoTest extends TestCase
{
    use RefreshDatabase;

    private function produk(int $stok = 10): Product
    {
        $kategori = Category::create(['nama_kategori' => 'Fashion']);

        return Product::create([
            'category_id' => $kategori->id, 'nama_produk' => 'Kaos', 'harga' => 50000, 'stok' => $stok,
        ]);
    }

    public function test_beranda_bisa_dibuka_tanpa_login(): void
    {
        $this->produk();
        $this->get('/')->assertOk()->assertSee('Kaos');
    }

    public function test_keranjang_butuh_login(): void
    {
        $this->get('/keranjang')->assertRedirect('/masuk');
    }

    public function test_pelanggan_tidak_boleh_masuk_admin(): void
    {
        $this->actingAs(User::factory()->create())->get('/admin')->assertForbidden();
    }

    public function test_admin_bisa_buka_dashboard(): void
    {
        $this->actingAs(User::factory()->admin()->create())->get('/admin')->assertOk();
    }

    public function test_alur_belanja_sampai_bayar_simulasi(): void
    {
        config(['midtrans.simulasi' => true]);
        $produk = $this->produk(10);
        $user = User::factory()->create();

        $this->actingAs($user)->post(route('keranjang.store', $produk), ['qty' => 2])->assertRedirect();
        $this->assertDatabaseHas('cart_items', ['user_id' => $user->id, 'qty' => 2]);

        $this->actingAs($user)->post('/checkout', [
            'penerima' => 'Budi', 'alamat_lengkap' => 'Jl. Contoh 1', 'kota' => 'Sigli', 'kode_pos' => '24100',
        ])->assertRedirect();

        $order = Order::firstOrFail();
        $this->assertSame(Order::MENUNGGU, $order->status_pesanan);
        $this->assertSame(100000 + config('toko.ongkir'), (int) $order->total_bayar);
        $this->assertSame(8, $produk->fresh()->stok);
        $this->assertDatabaseCount('cart_items', 0);

        $this->actingAs($user)->post(route('pembayaran.simulasi', $order))->assertRedirect();
        $this->assertSame(Order::DIBAYAR, $order->fresh()->status_pesanan);
        $this->assertSame('Berhasil', $order->payment->fresh()->status_bayar);
    }

    public function test_batal_pesanan_mengembalikan_stok(): void
    {
        $produk = $this->produk(5);
        $user = User::factory()->create();

        $this->actingAs($user)->post(route('keranjang.store', $produk), ['qty' => 3]);
        $this->actingAs($user)->post('/checkout', [
            'penerima' => 'Budi', 'alamat_lengkap' => 'Jl. Contoh 1', 'kota' => 'Sigli', 'kode_pos' => '24100',
        ]);
        $order = Order::firstOrFail();
        $this->assertSame(2, $produk->fresh()->stok);

        $this->actingAs($user)->post(route('pesanan.batal', $order))->assertRedirect();
        $this->assertSame(5, $produk->fresh()->stok);
        $this->assertSame(Order::DIBATALKAN, $order->fresh()->status_pesanan);
    }

    public function test_webhook_menolak_signature_salah(): void
    {
        config(['midtrans.server_key' => 'rahasia']);
        $this->postJson('/midtrans/notifikasi', ['order_id' => 'X', 'signature_key' => 'salah'])->assertForbidden();
    }
}
