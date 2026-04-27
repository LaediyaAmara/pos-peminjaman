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
    Schema::create('ulasan_bukus', function (Blueprint $table) {
        $table->id('UlasanID');
        $table->foreignId('UserID')->constrained('users'); // Siapa yang kasih ulasan
        $table->foreignId('BukuID')->constrained('bukus', 'BukuID'); // Buku mana yang diulas
        $table->text('Ulasan'); // Komentarnya
        $table->integer('Rating'); // Kasih bintang berapa (1-5)
        $table->timestamps();
    });
}

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('ulasan_bukus');
    }
};
