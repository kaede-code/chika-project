<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        // Samakan dengan spec requirement: products table hanya berisi
        // id, nama_produk, kategori(enum Jus/Salad), harga, gambar, created_at, updated_at.
        // Migration ini menghindari field tambahan seperti deskripsi/stok/kategori lain.
        Schema::dropIfExists('products');

        Schema::create('products', function (Blueprint $table) {
            $table->id();

            $table->string('nama_produk');
            $table->enum('kategori', ['Jus', 'Salad']);
            $table->unsignedBigInteger('harga');
            $table->string('gambar')->nullable();

            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('products');
    }
};

