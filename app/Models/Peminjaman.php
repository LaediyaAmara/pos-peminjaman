<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Peminjaman extends Model
{
    use HasFactory;

protected $casts = [
    'TanggalPeminjaman' => 'date',
    'TanggalPengembalian' => 'date',
];

    // Tentukan nama tabel jika kamu tidak pakai "peminjamans"
    protected $table = 'peminjamans'; 

    // Tentukan Primary Key-nya sesuai foto ERD
    protected $primaryKey = 'PeminjamanID';

    // IJINKAN kolom-kolom ini untuk diisi (Mass Assignment)
    protected $fillable = [
        'UserID',
        'BukuID',
        'TanggalPeminjaman',
        'TanggalPengembalian',
        'StatusPeminjaman',
        'Kondisi',
    ];

    // Relasi ke User (Satu peminjaman dimiliki oleh satu user)
    public function user()
    {
        return $this->belongsTo(User::class, 'UserID');
    }

    // Relasi ke Buku (Satu peminjaman mencakup satu buku)
    public function buku()
    {
        return $this->belongsTo(Buku::class, 'BukuID');
    }
    public function denda()
{
    return $this->hasOne(Denda::class, 'PeminjamanID', 'PeminjamanID');
}

}