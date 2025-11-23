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
        Schema::create('rentals', function (Blueprint $table) {
            $table->id();

            // Relasi: Siapa yang pinjam? (Terhubung ke tabel users)
            $table->foreignId('user_id')->constrained()->onDelete('cascade');

            // Relasi: Barang apa yang dipinjam? (Terhubung ke tabel products)
            $table->foreignId('product_id')->constrained()->onDelete('cascade');

            $table->integer('amount'); // Jumlah barang
            $table->date('start_date'); // Tanggal mulai
            $table->date('end_date');   // Tanggal kembali

            // Status Transaksi (Menunggu, Disetujui, Selesai/Dikembalikan)
            $table->enum('status', ['pending', 'approved', 'returned'])->default('pending');

            $table->timestamps();
        });
    }
};
