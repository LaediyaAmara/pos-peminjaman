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
    Schema::table('peminjamans', function (Blueprint $table) {
        // Menambahkan kolom kondisi dengan default 'Baik'
        $table->enum('Kondisi', ['Baik', 'Rusak', 'Hilang'])->default('Baik')->after('StatusPeminjaman');
    });
}

    /**
     * Reverse the migrations.
     */
   public function down(): void
{
    Schema::table('peminjamans', function (Blueprint $table) {
        $table->dropColumn('Kondisi');
    });
}
};
