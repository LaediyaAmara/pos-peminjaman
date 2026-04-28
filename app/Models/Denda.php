<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Denda extends Model
{
    use HasFactory;

    // Nama tabel di database
    protected $table = 'dendas';

    // Primary Key tabel
    protected $primaryKey = 'DendaID';

    // Kolom yang boleh diisi (Mass Assignment)
    protected $fillable = [
        'PeminjamanID',
        'JumlahDenda',
        'Keterangan',
        'StatusPembayaran',
    ];

    /**
     * Relasi Balik ke Peminjaman
     * Satu denda dimiliki oleh satu transaksi peminjaman
     */
    public function peminjaman()
    {
        return $this->belongsTo(Peminjaman::class, 'PeminjamanID', 'PeminjamanID');
    }
}