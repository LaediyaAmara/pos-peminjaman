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
    Schema::create('bukus', function (Blueprint $table) {
        $table->id('BukuID');
        $table->string('Judul');
        
        // PASTIKAN BARIS INI ADA DAN NAMANYA SAMA PERSIS
        $table->foreignId('KategoriID')->constrained('kategori_bukus', 'KategoriID')->onDelete('cascade');
        
        $table->string('Penulis');
        $table->string('Penerbit');
        $table->integer('TahunTerbit');
        $table->integer('Stok');
        $table->timestamps();
    });
}

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('bukus');
    }
};
