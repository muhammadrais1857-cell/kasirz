<?php

namespace Database\Seeders;

use App\Models\Category;
use App\Models\Product;
use App\Models\User;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Idempotent: aman dijalankan berulang (dipakai saat setiap deploy).
     */
    public function run(): void
    {
        User::firstOrCreate(['email' => 'admin@tokokita.test'], [
            'nama' => 'Admin Toko',
            'password' => env('ADMIN_PASSWORD', 'admin12345'),
            'no_hp' => '081200000000',
            'role' => 'admin',
        ]);

        User::firstOrCreate(['email' => 'pelanggan@tokokita.test'], [
            'nama' => 'Pelanggan Contoh',
            'password' => 'pelanggan123',
            'no_hp' => '081211111111',
            'role' => 'pelanggan',
        ]);

        $kategori = [];
        foreach ([
            'Fashion' => 'Pakaian, sepatu, dan aksesori',
            'Elektronik' => 'Perangkat dan aksesori elektronik',
            'Rumah Tangga' => 'Perlengkapan rumah',
            'Olahraga' => 'Perlengkapan olahraga',
        ] as $nama => $deskripsi) {
            $kategori[$nama] = Category::firstOrCreate(['nama_kategori' => $nama], ['deskripsi' => $deskripsi]);
        }

        // Foto sengaja dikosongkan; unggah lewat Admin > Produk > Edit.
        foreach ([
            ['Fashion', 'Kaos Polos Premium', 89000, 35, 'Kaos katun combed 30s yang lembut dan adem dipakai sehari-hari.'],
            ['Fashion', 'Sepatu Sneakers', 349000, 12, 'Sneakers ringan dengan sol empuk untuk aktivitas harian.'],
            ['Fashion', 'Tas Ransel Kanvas', 159000, 20, 'Ransel kanvas dengan kompartemen laptop.'],
            ['Fashion', 'Kemeja Flanel', 129000, 18, 'Kemeja flanel kotak-kotak, nyaman dipadukan dengan jaket.'],
            ['Elektronik', 'Headset Bluetooth', 199000, 48, 'Headset nirkabel dengan baterai tahan seharian.'],
            ['Elektronik', 'Mouse Wireless', 85000, 60, 'Mouse nirkabel 2.4 GHz, hemat baterai.'],
            ['Elektronik', 'Jam Tangan Digital', 215000, 9, 'Jam digital tahan air dengan alarm dan stopwatch.'],
            ['Rumah Tangga', 'Botol Minum 1L', 45000, 90, 'Botol minum 1 liter bebas BPA.'],
            ['Olahraga', 'Matras Yoga', 99000, 25, 'Matras yoga anti-slip tebal 6 mm.'],
        ] as [$kat, $nama, $harga, $stok, $deskripsi]) {
            Product::firstOrCreate(['nama_produk' => $nama], [
                'category_id' => $kategori[$kat]->id,
                'harga' => $harga,
                'stok' => $stok,
                'deskripsi' => $deskripsi,
            ]);
        }
    }
}
