<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('categories', function (Blueprint $table) {
            $table->id();
            $table->string('nama_kategori');
            $table->text('deskripsi')->nullable();
            $table->timestamps();
        });

        Schema::create('products', function (Blueprint $table) {
            $table->id();
            $table->foreignId('category_id')->constrained('categories')->restrictOnDelete();
            $table->string('nama_produk');
            $table->text('deskripsi')->nullable();
            $table->unsignedBigInteger('harga');
            $table->unsignedInteger('stok')->default(0);
            $table->string('gambar')->nullable();
            $table->timestamps();
        });

        Schema::create('cart_items', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained('users')->cascadeOnDelete();
            $table->foreignId('product_id')->constrained('products')->cascadeOnDelete();
            $table->unsignedInteger('qty')->default(1);
            $table->timestamps();
            $table->unique(['user_id', 'product_id']);
        });

        Schema::create('addresses', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained('users')->cascadeOnDelete();
            $table->string('penerima');
            $table->text('alamat_lengkap');
            $table->string('kota');
            $table->string('kode_pos', 10);
            $table->timestamps();
        });

        Schema::create('orders', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained('users')->restrictOnDelete();
            $table->foreignId('address_id')->constrained('addresses')->restrictOnDelete();
            $table->string('kode_order')->unique();
            $table->unsignedBigInteger('ongkir')->default(0);
            $table->unsignedBigInteger('total_bayar');
            $table->string('status_pesanan', 30)->default('Menunggu Pembayaran');
            $table->timestamps();
        });

        Schema::create('order_items', function (Blueprint $table) {
            $table->id();
            $table->foreignId('order_id')->constrained('orders')->cascadeOnDelete();
            $table->foreignId('product_id')->constrained('products')->restrictOnDelete();
            $table->unsignedInteger('qty');
            $table->unsignedBigInteger('harga_satuan');
            $table->unsignedBigInteger('subtotal');
            $table->timestamps();
        });

        Schema::create('payments', function (Blueprint $table) {
            $table->id();
            $table->foreignId('order_id')->unique()->constrained('orders')->cascadeOnDelete();
            $table->string('metode_bayar')->nullable();
            $table->string('status_bayar', 30)->default('Menunggu');
            $table->string('transaction_id')->nullable();
            $table->string('snap_token')->nullable();
            $table->timestamp('paid_at')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('payments');
        Schema::dropIfExists('order_items');
        Schema::dropIfExists('orders');
        Schema::dropIfExists('addresses');
        Schema::dropIfExists('cart_items');
        Schema::dropIfExists('products');
        Schema::dropIfExists('categories');
    }
};
