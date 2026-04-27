<?php

namespace App\Http\Controllers;
use App\Models\Buku;
use App\Models\KategoriBuku;
use Illuminate\Http\Request;

class BukuController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $bukus = \App\Models\Buku::all(); // Ambil semua data buku
    return view('buku.index', compact('bukus')); // Kirim ke tampilan
    // 1. Ambil kata kunci dari input search
    $search = $request->input('search');

    // 2. Query buku dengan relasi kategori
    $bukus = Buku::with('kategori')
        ->when($search, function ($query, $search) {
            return $query->where('Judul', 'like', "%{$search}%")
                         ->orWhere('Penulis', 'like', "%{$search}%");
        })
        ->get();

    // 3. Kirim data ke view
    return view('buku.index', compact('bukus'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $kategoris = KategoriBuku::all();
        return view('buku.create', compact('kategoris'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
{
    // 1. Validasi input
    $request->validate([
        'Judul'       => 'required',
        'KategoriID'  => 'required', // Tambahkan validasi ini
        'Penulis'     => 'required',
        'Penerbit'    => 'required',
        'TahunTerbit' => 'required|numeric',
        'Stok'        => 'required|numeric',
    ]);

    // 2. Simpan ke Database
    Buku::create([
        'Judul'       => $request->Judul,
        'KategoriID'  => $request->KategoriID, // Pastikan ini ada
        'Penulis'     => $request->Penulis,
        'Penerbit'    => $request->Penerbit,
        'TahunTerbit' => $request->TahunTerbit,
        'Stok'        => $request->Stok,
    ]);

    return redirect()->route('buku.index')->with('success', 'Buku berhasil ditambahkan!');
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
    $buku = \App\Models\Buku::findOrFail($id);
    return view('buku.edit', compact('buku'));
}

    /**
     * Update the specified resource in storage.
     */
   public function update(Request $request, $id)
{
    $buku = Buku::findOrFail($id);
    $buku->update([
        'Judul'     => $request->Judul,
        'Penulis'   => $request->Penulis,
        'Penerbit'  => $request->Penerbit,
        'TahunTerbit' => $request->TahunTerbit,
        'Stok'      => $request->Stok, // Wajib ada baris ini!
    ]);

    return redirect()->route('buku.index')->with('success', 'Data buku berhasil diperbarui!');
}

    /**
     * Remove the specified resource from storage.
     */
    public function destroy($id)
{
    $buku = \App\Models\Buku::findOrFail($id);
    $buku->delete();

    return redirect()->route('buku.index')->with('success', 'Buku berhasil dihapus!');
}
}
