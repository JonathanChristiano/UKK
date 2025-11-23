<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('products', function (Blueprint $table) {
            $table->id();
            $table->string('name');         // Nama Produk (misal: Tenda Dome)
            $table->integer('price');       // Harga per hari (misal: 75000)
            $table->integer('stock');       // Jumlah stok (misal: 10)
            $table->string('image');        // Link gambar atau nama file gambar
            $table->text('description')->nullable(); // Penjelasan singkat (opsional)
            $table->timestamps();           // Otomatis buat kolom created_at & updated_at
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('products');
    }
};
