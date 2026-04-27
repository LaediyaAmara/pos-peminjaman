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
    // 1. Ambil input search dari request
    $search = $request->input('search');

    // 2. Ambil semua kategori untuk navigasi tombol filter di view
    // Gunakan KategoriBuku atau Kategori sesuai nama Model kamu (di sini saya samakan dengan kode kamu)
    $kategoris = \App\Models\KategoriBuku::all();

    // 3. Bangun Query Buku dengan relasi kategori
    $query = \App\Models\Buku::with('kategori');

    // 4. JIKA ada kategori yang diklik/dipilih
    if ($kategori_id) {
        $query->where('KategoriID', $kategori_id);
    }

    // 5. JIKA ada kata kunci pencarian (FITUR SEARCH)
    if ($search) {
        $query->where(function($q) use ($search) {
            $q->where('Judul', 'LIKE', "%{$search}%")
              ->orWhere('Penulis', 'LIKE', "%{$search}%");
        });
    }

    // 6. Eksekusi query (Urutkan dari yang terbaru)
    $bukus = $query->latest()->get();

    // 7. Kirim data ke view peminjam.pinjam
    return view('peminjam.pinjam', compact('bukus', 'kategoris', 'kategori_id'));
}
}