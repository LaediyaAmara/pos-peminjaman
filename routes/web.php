<?php

use App\Http\Controllers\ProfileController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\BukuController;
use Illuminate\Support\Facades\Auth;
use App\Http\Controllers\KategoriController;
use App\Http\Controllers\PeminjamanController;
use App\Http\Controllers\UserController; // Pastikan import Controller-nya di atas

Route::middleware(['auth', 'role:admin'])->group(function () {
    Route::get('/manajemen-user', [UserController::class, 'index'])->name('user.index');
    Route::get('/manajemen-user/tambah', [UserController::class, 'create'])->name('user.create');
    Route::post('/manajemen-user/simpan', [UserController::class, 'store'])->name('user.store');
    Route::get('/manajemen-user/{id}/edit', [UserController::class, 'edit'])->name('user.edit');
    Route::put('/manajemen-user/{id}/update', [UserController::class, 'update'])->name('user.update');
    Route::delete('/manajemen-user/{id}', [UserController::class, 'destroy'])->name('user.destroy');
});

// Jika kamu menggunakan Resource Controller
Route::get('/manajemen-user', [App\Http\Controllers\UserController::class, 'index'])->name('user.index');

Route::post('/pinjam-buku', [PeminjamanController::class, 'store'])
    ->name('peminjaman.store')
    ->middleware(['auth', 'role:peminjam']);

Route::resource('kategori', KategoriController::class)->middleware(['auth', 'role:admin']);

// Hanya Admin dan Petugas yang bisa masuk ke urusan buku
Route::resource('buku', BukuController::class)
    ->middleware(['auth', 'role:admin,petugas']);

Route::get('/', function () {
    return view('welcome');
});

Route::get('/dashboard', function () {
    return view('dashboard');
})->middleware(['auth', 'verified'])->name('dashboard');

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

require __DIR__.'/auth.php';
Route::get('/cek-pangkat', function () {
    return dd(Auth::user()->role);
});
Route::get('/pinjam-buku/{kategori_id?}', [KategoriController::class, 'koleksiPeminjam'])
    ->middleware(['auth', 'role:peminjam'])
    ->name('peminjam.pinjam');

    Route::get('/pinjaman-saya', [PeminjamanController::class, 'index'])
    ->name('peminjam.index')
    ->middleware(['auth', 'role:peminjam']);

    // Menampilkan semua pinjaman
Route::get('/peminjaman', [PeminjamanController::class, 'semuaPeminjaman'])
    ->name('peminjaman.index')
    ->middleware(['auth', 'role:admin,petugas']);

// Proses pengembalian (Gunakan PUT karena kita mengupdate data)
Route::put('/peminjaman/{id}/kembalikan', [PeminjamanController::class, 'kembalikan'])
    ->name('peminjaman.kembalikan')
    ->middleware(['auth', 'role:admin,petugas']);
    Route::get('/laporan', [PeminjamanController::class, 'laporan'])->name('peminjaman.laporan');

// Pastikan name-nya sama persis dengan yang dipanggil di dashboard
Route::get('/laporan-peminjaman', [PeminjamanController::class, 'laporan'])
    ->name('peminjaman.laporan')
    ->middleware(['auth']);
   Route::middleware(['auth'])->group(function () {
    // Akses Khusus Admin & Petugas (Kelola Denda)
    Route::middleware(['role:admin,petugas'])->group(function () {
        Route::get('/denda', [PeminjamanController::class, 'daftarDenda'])->name('denda.index');
        Route::put('/denda/{id}/bayar', [PeminjamanController::class, 'bayarDenda'])->name('denda.bayar');
    });

    // Akses Peminjam (Hanya melihat denda miliknya sendiri di Riwayat)
    // Ini biasanya sudah tercover di fungsi index peminjam yang menampilkan relasi denda
});