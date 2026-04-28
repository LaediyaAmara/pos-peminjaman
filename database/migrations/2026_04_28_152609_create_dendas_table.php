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
    Schema::create('dendas', function (Blueprint $col) {
        $col->id('DendaID');
        $col->foreignId('PeminjamanID')->constrained('peminjamans', 'PeminjamanID')->onDelete('cascade');
        $col->integer('JumlahDenda');
        $col->string('Keterangan'); // Contoh: "Telat 2 hari & Buku Rusak"
        $col->enum('StatusPembayaran', ['Belum Lunas', 'Lunas'])->default('Belum Lunas');
        $col->timestamps();
    });
}

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('dendas');
    }
};
