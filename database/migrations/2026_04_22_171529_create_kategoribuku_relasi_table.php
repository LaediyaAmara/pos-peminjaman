<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void {
    Schema::create('kategoribuku_relasi', function (Blueprint $table) {
        $table->id('KategoriBukuID');
        $table->foreignId('BukuID')->constrained('bukus', 'BukuID'); // Tali ke Buku
        $table->foreignId('KategoriID')->constrained('kategori_bukus', 'KategoriID'); // Tali ke Kategori
        $table->timestamps();
    });
}

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('kategoribuku_relasi');
    }
};
