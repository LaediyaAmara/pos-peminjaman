<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Peminjaman; 
use App\Models\Buku;
use App\Models\KategoriBuku;
use Illuminate\Support\Facades\Auth;

class PeminjamanController extends Controller
{
    // Fungsi untuk menampilkan riwayat pinjam (opsional)
 public function index()
{
    // Mengambil data peminjaman milik user yang sedang login
    // Pastikan Model Peminjaman sudah di-import di atas (use App\Models\Peminjaman)
   // Gunakan Auth::id() alih-alih auth()->id() untuk menghindari bug intelephense
$pinjamans = Peminjaman::where('UserID', Auth::id())
            ->with('buku')
            ->latest()
            ->get();
    return view('peminjam.index', compact('pinjamans'));
}
    // Fungsi simpan peminjaman
   public function store(Request $request)
{
    $userID = Auth::id();
    
    // CEK BATAS: Hitung berapa buku yang sedang dipinjam (belum dikembalikan)
    $jumlahDipinjam = Peminjaman::where('UserID', $userID)
                        ->where('StatusPeminjaman', 'Dipinjam')
                        ->count();

    if ($jumlahDipinjam >= 3) { // Maksimal 3 buku
        return back()->with('error', 'Gagal! Kamu masih punya 3 buku yang belum dikembalikan.');
    }
    // 1. Cari data buku yang ingin dipinjam
    $buku = \App\Models\Buku::findOrFail($request->BukuID);

    // 2. CEK STOK: Jika stok 0 atau kurang, batalkan peminjaman
    if ($buku->Stok <= 0) {
        return back()->with('error', 'Maaf, stok buku "' . $buku->Judul . '" sedang habis!');
    }

    // 3. Jika stok tersedia, buat data peminjaman
    \App\Models\Peminjaman::create([
       'UserID' => Auth::id(),
        'BukuID' => $request->BukuID,
        'TanggalPeminjaman' => now(),
        'TanggalPengembalian' => now()->addDays(7),
        'StatusPeminjaman' => 'Dipinjam',
    ]);

    // 4. KURANGI STOK: Karena sudah dipinjam satu
    $buku->decrement('Stok');

    return back()->with('success', 'Buku berhasil dipinjam! Sisa stok: ' . $buku->Stok);
}

// Menampilkan semua daftar pinjaman untuk Admin/Petugas
public function semuaPeminjaman()
{
    $peminjamans = Peminjaman::with(['user', 'buku'])->latest()->get();
    return view('peminjaman.index', compact('peminjamans'));
}

// Proses Mengembalikan Buku
public function kembalikan(Request $request, $id)
{
    $peminjaman = Peminjaman::findOrFail($id);
    $kondisi = $request->input('kondisi'); // Menangkap 'Baik', 'Rusak', atau 'Hilang'

    // 1. Update status dan kondisi di tabel peminjaman
    $peminjaman->update([
        'StatusPeminjaman' => 'Kembali',
        'Kondisi' => $kondisi,
        'TanggalPengembalian' => now() // Mencatat waktu asli kembali
    ]);

    // 2. Update Stok Buku (Hanya bertambah jika barang kembali dalam keadaan Baik atau Rusak)
    // Jika Hilang, stok tidak dikembalikan ke rak
    if ($kondisi != 'Hilang') {
        $buku = Buku::findOrFail($peminjaman->BukuID);
        $buku->increment('Stok');
    }

    return back()->with('success', "Buku dikembalikan dengan kondisi: $kondisi");
}
public function laporan()
{
    // Mengambil semua data peminjaman untuk admin
    $peminjamans = Peminjaman::with(['user', 'buku'])->latest()->get();
    return view('peminjaman.laporan', compact('peminjamans'));
}
public function create(Request $request)
{
    $search = $request->input('search');

    $bukus = Buku::with('kategori')
        ->where('Stok', '>', 0) // Hanya tampilkan buku yang ada stoknya
        ->when($search, function ($query, $search) {
            return $query->where('Judul', 'like', "%{$search}%")
                         ->orWhere('Penulis', 'like', "%{$search}%");
        })
        ->get();

    return view('peminjaman.create', compact('bukus'));
}

public function koleksiPeminjam(Request $request, $kategori_id = null)
{
    $search = $request->input('search');
    $kategoris = KategoriBuku::all();

    $bukus = Buku::with('kategori')
        ->when($kategori_id, function ($query, $kategori_id) {
            return $query->where('KategoriID', $kategori_id);
        })
        ->when($search, function ($query, $search) {
            return $query->where('Judul', 'like', "%{$search}%")
                         ->orWhere('Penulis', 'like', "%{$search}%");
        })
        ->get();

    return view('peminjaman.create', compact('bukus', 'kategoris'));
}
}