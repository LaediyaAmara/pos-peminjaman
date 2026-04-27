<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class KategoriBuku extends Model
{
    use HasFactory;

    protected $table = 'kategori_bukus'; // Sesuaikan dengan nama di phpMyAdmin
    protected $primaryKey = 'KategoriID';
    protected $fillable = ['NamaKategori'];

    // Relasi ke tabel buku (Many to Many)
   public function bukus()
{
    return $this->hasMany(Buku::class, 'KategoriID', 'KategoriID');
}
    
}