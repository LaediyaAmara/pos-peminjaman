<?php

namespace App\Http\Controllers;

use App\Models\Buku;
use App\Models\KategoriBuku;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage; // Tambahkan ini untuk hapus gambar lama

class BukuController extends Controller
{
    public function index(Request $request)
    {
        $search = $request->input('search');
        $query = Buku::with('kategori');

        if ($search) {
            $query->where(function($q) use ($search) {
                $q->where('Judul', 'LIKE', "%{$search}%")
                  ->orWhere('Penulis', 'LIKE', "%{$search}%")
                  ->orWhere('Penerbit', 'LIKE', "%{$search}%");
            });
        }

        $bukus = $query->latest()->get();
        return view('buku.index', compact('bukus'));
    }

    public function create()
    {
        $kategoris = KategoriBuku::all();
        return view('buku.create', compact('kategoris'));
    }

    /**
     * FUNGSI STORE (GABUNGAN)
     */
    public function store(Request $request)
    {
        // 1. Validasi input (termasuk Gambar & Sinopsis)
        $request->validate([
            'Judul'       => 'required',
            'KategoriID'  => 'required',
            'Penulis'     => 'required',
            'Penerbit'    => 'required',
            'TahunTerbit' => 'required|numeric',
            'Stok'        => 'required|numeric',
            'Gambar'      => 'image|mimes:jpeg,png,jpg|max:2048', // Validasi gambar
        ]);

        $data = $request->all();

        // 2. Logika Upload Gambar
        if ($request->hasFile('Gambar')) {
            $namaGambar = time() . '.' . $request->Gambar->extension();
            $request->Gambar->storeAs('public/buku', $namaGambar);
            $data['Gambar'] = $namaGambar;
        }

        // 3. Simpan ke Database (Otomatis membawa Sinopsis karena $request->all())
        Buku::create($data);

        return redirect()->route('buku.index')->with('success', 'Buku berhasil ditambahkan!');
    }

    public function edit($id)
{
    // 1. Cari data buku yang mau diedit
    $buku = Buku::findOrFail($id);

    // 2. AMBIL SEMUA DATA KATEGORI (Ini yang kurang!)
    $kategoris = \App\Models\KategoriBuku::all();

    // 3. Kirim KEDUA variabel ke view edit
    return view('buku.edit', compact('buku', 'kategoris'));
}

    /**
     * FUNGSI UPDATE (GABUNGAN)
     */
    public function update(Request $request, $id)
    {
        $buku = Buku::findOrFail($id);

        // Validasi
        $request->validate([
            'Gambar' => 'image|mimes:jpeg,png,jpg|max:2048',
        ]);

        $data = $request->all();

        // Logika Update Gambar
        if ($request->hasFile('Gambar')) {
            // Hapus gambar lama jika ada agar tidak memenuhi storage
            if ($buku->Gambar) {
                Storage::delete('public/buku/' . $buku->Gambar);
            }

            $namaGambar = time() . '.' . $request->Gambar->extension();
            $request->Gambar->storeAs('public/buku', $namaGambar);
            $data['Gambar'] = $namaGambar;
        }

        $buku->update($data);

        return redirect()->route('buku.index')->with('success', 'Data buku berhasil diperbarui!');
    }

    public function destroy($id)
    {
        $buku = \App\Models\Buku::findOrFail($id);
        
        // Hapus gambar dari folder saat data dihapus
        if ($buku->Gambar) {
            Storage::delete('public/buku/' . $buku->Gambar);
        }

        $buku->delete();
        return redirect()->route('buku.index')->with('success', 'Buku berhasil dihapus!');
    }
}