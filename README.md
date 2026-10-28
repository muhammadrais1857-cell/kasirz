# TokoKita - Web E-Commerce dengan Pembayaran Online

Tugas Rekayasa Perangkat Lunak - Kelompok Transistor, Teknik Informatika, Universitas Jabal Ghafur.

| Nama | NPM |
|---|---|
| Teuku Zaki Irfan | 24105111128 |
| Rafiqil A'laa | 24105111164 |
| Muhammad Rais | 24105111139 |

Web toko online ala marketplace: pelanggan dapat mendaftar, melihat katalog, memasukkan produk ke keranjang, checkout, dan membayar online (QRIS / virtual account / e-wallet lewat Midtrans). Admin mengelola produk, kategori, pesanan, dan melihat laporan penjualan.

## Fitur

**Pelanggan:** registrasi dan login, katalog + pencarian + filter kategori, detail produk, keranjang, checkout dengan alamat pengiriman, pembayaran online (Midtrans Snap), riwayat dan status pesanan, batalkan pesanan yang belum dibayar.

**Admin:** dashboard statistik, CRUD produk (termasuk **upload foto**), CRUD kategori, kelola pesanan (Dibayar -> Diproses -> Dikirim -> Selesai), laporan penjualan per rentang tanggal.

**Pembayaran:** status pesanan berubah otomatis lewat webhook Midtrans (`POST /midtrans/notifikasi`, diverifikasi dengan signature SHA-512). Jika kunci Midtrans belum diisi, tersedia tombol *Bayar (Simulasi)* agar alur tetap bisa didemokan.

## Teknologi

Laravel 12 (PHP 8.2+), MySQL (SQLite untuk pengembangan lokal), Blade + CSS biasa (tanpa build frontend), Midtrans Snap (sandbox).

Struktur database mengikuti ERD di laporan: `users`, `categories`, `products`, `cart_items`, `addresses`, `orders`, `order_items`, `payments`.

## Menjalankan di komputer lokal

```bash
composer install
cp .env.example .env
php artisan key:generate
touch database/database.sqlite      # atau atur DB_* ke MySQL di .env
php artisan migrate --seed
php artisan serve
```

Buka http://127.0.0.1:8000

Akun demo (dibuat oleh seeder):

| Peran | Email | Kata sandi |
|---|---|---|
| Admin | admin@tokokita.test | admin12345 (ubah lewat `ADMIN_PASSWORD`) |
| Pelanggan | pelanggan@tokokita.test | pelanggan123 |

Menjalankan test: `php artisan test`

## Mengaktifkan Midtrans (sandbox)

1. Daftar di https://dashboard.sandbox.midtrans.com lalu ambil Server Key dan Client Key (Settings > Access Keys).
2. Isi di `.env` (atau Variables di Railway): `MIDTRANS_SERVER_KEY`, `MIDTRANS_CLIENT_KEY`, `MIDTRANS_IS_PRODUCTION=false`.
3. Di dashboard Midtrans (Settings > Payment > Notification URL) isi: `https://DOMAIN-ANDA/midtrans/notifikasi`.

## Deploy ke Railway

1. Push repo ini ke GitHub, lalu di Railway: **New Project > Deploy from GitHub repo**.
2. Tambahkan layanan **MySQL** di project yang sama.
3. Di Variables service web, isi:
   - `APP_KEY` (hasil `php artisan key:generate --show`), `APP_ENV=production`, `APP_DEBUG=false`
   - `APP_URL` = domain Railway Anda
   - `DB_CONNECTION=mysql`, `DB_HOST=${{MySQL.MYSQLHOST}}`, `DB_PORT=${{MySQL.MYSQLPORT}}`, `DB_DATABASE=${{MySQL.MYSQLDATABASE}}`, `DB_USERNAME=${{MySQL.MYSQLUSER}}`, `DB_PASSWORD=${{MySQL.MYSQLPASSWORD}}`
   - `SESSION_DRIVER=database`, `CACHE_STORE=database`, `QUEUE_CONNECTION=sync`
   - `ADMIN_PASSWORD` (kata sandi admin awal) dan kunci Midtrans
4. Di Settings > Networking klik **Generate Domain**.
5. Migrasi dan seeder otomatis dijalankan setiap deploy lewat `railway.json` (`preDeployCommand`).

**Foto produk:** foto yang diunggah admin disimpan di `public/uploads/produk`. Filesystem Railway bersifat sementara, jadi tambahkan **Volume** dengan mount path `/app/public/uploads` supaya foto tidak hilang saat deploy ulang.

## Struktur singkat

```
app/Http/Controllers      AuthController, ShopController, CartController, CheckoutController,
                          OrderController, PaymentController (Midtrans + webhook)
app/Http/Controllers/Admin  Dashboard, Product, Category, Order, Report
app/Models                User, Category, Product, CartItem, Address, Order, OrderItem, Payment
database/migrations       skema sesuai ERD
resources/views           Blade (pelanggan + admin)
routes/web.php            semua rute
```
