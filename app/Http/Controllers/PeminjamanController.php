<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Peminjaman; 
use App\Models\Buku;
use App\Models\KategoriBuku;
use App\Models\Denda; // Pastikan Model Denda sudah dibuat
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

        // 3. PROSES SIMPAN (Default Pinjam 7 Hari)
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
        
        $peminjamans = Peminjaman::with(['user', 'buku', 'denda'])
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

    /**
     * FUNGSI KEMBALIKAN (SUDAH DIPERBAIKI & DIGABUNG DENGAN DENDA)
     */
    public function kembalikan(Request $request, $id)
    {
        $peminjaman = Peminjaman::findOrFail($id);
        $hariIni = now();
        // Deadline adalah tanggal pengembalian yang diset saat awal pinjam
        $deadline = \Carbon\Carbon::parse($peminjaman->TanggalPengembalian);
        
        $totalDenda = 0;
        $keterangan = [];

        // --- LOGIKA 1: Denda Keterlambatan (2000 per hari) ---
        if ($hariIni->gt($deadline)) {
            $selisihHari = $hariIni->diffInDays($deadline);
            $dendaTelat = $selisihHari * 2000; 
            $totalDenda += $dendaTelat;
            $keterangan[] = "Telat $selisihHari hari (Rp " . number_format($dendaTelat) . ")";
        }

        // --- LOGIKA 2: Denda Kondisi ---
        if ($request->kondisi == 'Rusak') {
            $totalDenda += 20000; 
            $keterangan[] = "Buku Rusak (Rp 20.000)";
        } elseif ($request->kondisi == 'Hilang') {
            $totalDenda += 50000; 
            $keterangan[] = "Buku Hilang (Rp 50.000)";
        }

        // --- 1. UPDATE STATUS PEMINJAMAN ---
        $peminjaman->update([
            'TanggalPengembalian' => $hariIni, // Tanggal kembali asli
            'StatusPeminjaman' => 'Kembali',
            'Kondisi' => $request->kondisi
        ]);

        // --- 2. UPDATE STOK BUKU (Hanya bertambah jika tidak Hilang) ---
        if ($request->kondisi != 'Hilang') {
            Buku::where('BukuID', $peminjaman->BukuID)->increment('Stok');
        }

        // --- 3. SIMPAN KE TABEL DENDA (JIKA ADA) ---
        if ($totalDenda > 0) {
            Denda::create([
                'PeminjamanID' => $peminjaman->PeminjamanID,
                'JumlahDenda' => $totalDenda,
                'Keterangan' => implode(", ", $keterangan),
                'StatusPembayaran' => 'Belum Lunas'
            ]);
        }

        $msg = "Buku dikembalikan. Kondisi: $request->kondisi.";
        if ($totalDenda > 0) $msg .= " Denda Otomatis: Rp " . number_format($totalDenda);

        return back()->with('success', $msg);
    }

    // Fungsi untuk Halaman Jelajah Buku Siswa
    public function pinjam(Request $request, $kategori_id = null)
    {
        $search = $request->input('search');
        $query = Buku::with('kategori');

        if ($kategori_id) {
            $query->where('KategoriID', $kategori_id);
        }

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

   public function laporan(Request $request)
{
    $query = Peminjaman::with(['user', 'buku', 'denda']);

    // Filter berdasarkan Tanggal Peminjaman
    if ($request->tgl_mulai && $request->tgl_selesai) {
        $query->whereBetween('TanggalPeminjaman', [$request->tgl_mulai, $request->tgl_selesai]);
    }

    // Filter berdasarkan Status
    if ($request->status) {
        $query->where('StatusPeminjaman', $request->status);
    }

    $peminjamans = $query->latest()->get();

    return view('peminjaman.laporan', compact('peminjamans'));
}

// Tampilan Daftar Denda untuk Admin
public function daftarDenda()
{
    // Cek keamanan tambahan
    if (!in_array(auth()->user()->role, ['admin', 'petugas'])) {
        abort(403, 'Anda tidak memiliki akses ke halaman ini.');
    }

    $dendas = Denda::with(['peminjaman.user', 'peminjaman.buku'])
                ->latest()
                ->get();

    return view('peminjaman.denda', compact('dendas'));
}

// Fungsi untuk Update Status Pembayaran (Lunas/Belum Lunas)
public function bayarDenda($id)
{
    $denda = Denda::findOrFail($id);
    $denda->update(['StatusPembayaran' => 'Lunas']);

    return back()->with('success', 'Status denda berhasil diperbarui menjadi Lunas!');
}

}