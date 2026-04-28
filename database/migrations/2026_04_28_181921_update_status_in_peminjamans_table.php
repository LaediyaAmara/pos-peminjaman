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
    Schema::table('peminjamans', function (Blueprint $table) {
        // Kita ubah kolom yang sudah ada agar mendukung status baru
        $table->enum('StatusPeminjaman', ['Pending', 'Dipinjam', 'Ditolak', 'Kembali'])
              ->default('Pending')
              ->change();
    });
}

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('peminjamans', function (Blueprint $table) {
            //
        });
    }
};
