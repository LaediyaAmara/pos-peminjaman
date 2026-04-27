<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Buku extends Model
{
    use HasFactory;

    // Nama tabelnya (pastikan sama dengan di phpMyAdmin)
    protected $table = 'bukus';

    // Kunci utamanya (karena di database kamu pakai BukuID)
    protected $primaryKey = 'BukuID';

    // Kolom yang BOLEH diisi secara massal
  protected $fillable = [
    'Judul', 
    'KategoriID', // Tambahkan ini!
    'Penulis', 
    'Penerbit', 
    'TahunTerbit', 
    'Stok'
];
public function kategori()
{
    // Gunakan belongsTo, BUKAN belongsToMany
    return $this->belongsTo(KategoriBuku::class, 'KategoriID', 'KategoriID');
}
}