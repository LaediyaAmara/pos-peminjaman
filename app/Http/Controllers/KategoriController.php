<?php

namespace App\Http\Controllers;

use App\Models\KategoriBuku;
use App\Http\Controllers\Buku;
use Illuminate\Http\Request;

class KategoriController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
       $kategoris = \App\Models\KategoriBuku::all();
    return view('kategori.index', compact('kategoris'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
       $request->validate(['NamaKategori' => 'required']);
    \App\Models\KategoriBuku::create($request->all());
    return back()->with('success', 'Kategori ditambahkan!');
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit($id)
{
    // 1. Cari data kategori berdasarkan ID
    $kategori = KategoriBuku::findOrFail($id);

    // 2. KIRIM ke folder view (Pastikan file edit.blade.php ada di folder kategori)
    return view('kategori.edit', compact('kategori'));
}

    /**
     * Update the specified resource in storage.
     */
public function update(Request $request, $id)
{
    $request->validate([
        'NamaKategori' => 'required'
    ]);

    $kategori = KategoriBuku::findOrFail($id);
    $kategori->update([
        'NamaKategori' => $request->NamaKategori
    ]);

    // WAJIB ADA REDIRECT agar tidak berhenti di halaman putih
    return redirect()->route('kategori.index')->with('success', 'Kategori berhasil diubah!');
}

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        \App\Models\KategoriBuku::destroy($id);
    return back();
    }

public function koleksiPeminjam(Request $request, $kategori_id = null)
{
    $kategoris = \App\Models\KategoriBuku::all();
    
    if ($kategori_id) {
        $kategoriTerpilih = \App\Models\KategoriBuku::with('bukus')->findOrFail($kategori_id);
        $bukus = $kategoriTerpilih->bukus;
    } else {
        $bukus = \App\Models\Buku::all();
    }

    return view('peminjam.pinjam', compact('kategoris', 'bukus', 'kategori_id'));
// 1. Ambil kata kunci pencarian
    $search = $request->input('search');
    
    // 2. Ambil semua kategori untuk navigasi tombol
    $kategoris = \App\Models\KategoriBuku::all();

    // 3. Gabungkan logika filter kategori DAN search dalam satu query
    $bukus = \App\Models\Buku::with('kategori')
        // Jika ada kategori yang dipilih, filter berdasarkan KategoriID
        ->when($kategori_id, function ($query, $kategori_id) {
            return $query->where('KategoriID', $kategori_id);
        })
        // Jika ada pencarian, filter berdasarkan Judul atau Penulis
        ->when($search, function ($query, $search) {
            return $query->where(function($q) use ($search) {
                $q->where('Judul', 'like', "%{$search}%")
                  ->orWhere('Penulis', 'like', "%{$search}%");
            });
        })
        ->get();

    // 4. Kirim semua data ke SATU view saja (peminjaman.create)
    return view('peminjaman.create', compact('bukus', 'kategoris', 'kategori_id'));
}

}