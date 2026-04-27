<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Peminjaman; 
use App\Models\Buku;
use App\Models\Kategori; // Sesuaikan dengan nama Model Kategori kamu
use App\Models\KategoriBuku;
use Illuminate\Support\Facades\Auth;

class PeminjamanController extends Controller
{
    // Tampilan Riwayat Pinjam untuk Siswa
    public function index()
    {
        $pinjamans = Peminjaman::where('UserID', Auth::id())
            ->with('buku')
            ->latest()
            ->get();
            
        return view('peminjam.index', compact('pinjamans'));
    }

    // Fungsi Simpan Peminjaman
    public function store(Request $request)
    {
        $userID = Auth::id();
        
        // 1. CEK BATAS PINJAM
        $jumlahDipinjam = Peminjaman::where('UserID', $userID)
                            ->where('StatusPeminjaman', 'Dipinjam')
                            ->count();

        if ($jumlahDipinjam >= 3) {
            return back()->with('error', 'Gagal! Kamu masih punya 3 buku yang belum dikembalikan.');
        }

        // 2. CEK STOK BUKU
        $buku = Buku::findOrFail($request->BukuID);

        if ($buku->Stok <= 0) {
            return back()->with('error', 'Maaf, stok buku "' . $buku->Judul . '" sedang habis!');
        }

        // 3. PROSES SIMPAN
        Peminjaman::create([
            'UserID' => $userID,
            'BukuID' => $request->BukuID,
            'TanggalPeminjaman' => now(),
            'TanggalPengembalian' => now()->addDays(7),
            'StatusPeminjaman' => 'Dipinjam',
        ]);

        // 4. KURANGI STOK
        $buku->decrement('Stok');

        return back()->with('success', 'Buku berhasil dipinjam! Sisa stok: ' . $buku->Stok);
    }

    // Tampilan Admin: Daftar Semua Peminjaman
    public function semuaPeminjaman(Request $request)
    {
        $search = $request->input('search');
        
        $peminjamans = Peminjaman::with(['user', 'buku'])
            ->when($search, function($query, $search) {
                $query->whereHas('user', function($q) use ($search) {
                    $q->where('NamaLengkap', 'like', "%{$search}%");
                })->orWhereHas('buku', function($q) use ($search) {
                    $q->where('Judul', 'like', "%{$search}%");
                });
            })
            ->latest()
            ->get();

        return view('peminjaman.index', compact('peminjamans'));
    }

    // Proses Mengembalikan Buku
    public function kembalikan(Request $request, $id)
    {
        $peminjaman = Peminjaman::findOrFail($id);
        $kondisi = $request->input('kondisi'); 

        // 1. Update status & kondisi
        $peminjaman->update([
            'StatusPeminjaman' => 'Kembali',
            'Kondisi' => $kondisi,
            'TanggalPengembalian' => now()
        ]);

        // 2. Update Stok (Hanya bertambah jika tidak Hilang)
        if ($kondisi != 'Hilang') {
            Buku::where('BukuID', $peminjaman->BukuID)->increment('Stok');
        }

        return back()->with('success', "Buku dikembalikan dengan kondisi: $kondisi");
    }

    // Fungsi untuk Halaman Jelajah Buku Siswa (Peminjam)
   public function pinjam(Request $request, $kategori_id = null)
{
    // 1. Tangkap input search dari user
    $search = $request->input('search');

    // 2. Query dasar buku (beserta relasi kategori)
    $query = Buku::with('kategori');

    // 3. Logika Filter Kategori (Jika ada kategori yang diklik)
    if ($kategori_id) {
        $query->where('KategoriID', $kategori_id);
    }

    // 4. LOGIKA PENCARIAN (Tambahan agar search berfungsi)
    if ($search) {
        $query->where(function($q) use ($search) {
            $q->where('Judul', 'LIKE', "%{$search}%")
              ->orWhere('Penulis', 'LIKE', "%{$search}%");
        });
    }

    $bukus = $query->latest()->get();
    $kategoris = KategoriBuku::all();

    return view('peminjam.pinjam', compact('bukus', 'kategoris', 'kategori_id'));
}
    public function laporan()
    {
        $peminjamans = Peminjaman::with(['user', 'buku'])->latest()->get();
        return view('peminjaman.laporan', compact('peminjamans'));
    }
}