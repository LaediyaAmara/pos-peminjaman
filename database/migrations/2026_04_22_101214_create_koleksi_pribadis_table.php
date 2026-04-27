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
    Schema::create('koleksi_pribadis', function (Blueprint $table) {
        $table->id('KoleksiID');
        $table->foreignId('UserID')->constrained('users');
        $table->foreignId('BukuID')->constrained('bukus', 'BukuID');
        $table->timestamps();
    });
}

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('koleksi_pribadis');
    }
};
