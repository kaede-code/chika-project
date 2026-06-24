<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        // Requirement: products table harus sesuai struktur spesifik.
        // Catatan: ini akan menata ulang tabel `products` sesuai spec.
        Schema::dropIfExists('products');

        Schema::create('products', function (Blueprint $table) {
            $table->id();

            $table->string('nama_produk');
            $table->enum('kategori', ['Jus', 'Salad']);
            $table->unsignedBigInteger('harga');

            // path ke storage image
            $table->string('gambar')->nullable();

            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('products');
    }
};

