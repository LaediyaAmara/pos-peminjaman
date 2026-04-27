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
        Schema::create('peminjamans', function (Blueprint $table) {
            $table->id('PeminjamanID');
            
            // Perbaikan: Menambahkan cascade agar saat User dihapus, data peminjamannya ikut terhapus
            $table->foreignId('UserID')
                  ->constrained('users')
                  ->onDelete('cascade'); 

            // Perbaikan: Menambahkan cascade agar saat Buku dihapus, riwayat peminjamannya tidak error
            $table->foreignId('BukuID')
                  ->constrained('bukus', 'BukuID')
                  ->onDelete('cascade');

            $table->date('TanggalPeminjaman');
            $table->date('TanggalPengembalian');
            $table->string('StatusPeminjaman', 50);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('peminjamans');
    }
};