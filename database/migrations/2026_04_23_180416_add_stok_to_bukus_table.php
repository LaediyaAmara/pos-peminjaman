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
    Schema::table('bukus', function (Blueprint $table) {
        // Menambah kolom Stok setelah kolom TahunTerbit
        $table->integer('Stok')->default(0)->after('TahunTerbit');
    });
}

public function down(): void
{
    Schema::table('bukus', function (Blueprint $table) {
        $table->dropColumn('Stok');
    });
}
};
