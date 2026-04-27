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
    Schema::table('users', function (Blueprint $table) {
        // Kita tambah kolom role setelah kolom Alamat
        $table->enum('role', ['admin', 'petugas', 'peminjam'])->default('peminjam')->after('Alamat');
    });
}

public function down(): void {
    Schema::table('users', function (Blueprint $table) {
        $table->dropColumn('role');
    });
}
};
