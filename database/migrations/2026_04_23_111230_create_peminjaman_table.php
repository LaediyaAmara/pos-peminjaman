<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up()
{
    Schema::create('peminjamans', function (Blueprint $table) {
        $table->id('PeminjamanID');
        $table->foreignId('UserID')->constrained('users', 'id')->onDelete('cascade');
        $table->foreignId('BukuID')->constrained('bukus', 'BukuID')->onDelete('cascade');
        $table->date('TanggalPeminjaman');
        $table->date('TanggalPengembalian');
        $table->string('StatusPeminjaman', 50)->default('Dipinjam');
        $table->timestamps();
    });
}

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('peminjaman');
    }
};
