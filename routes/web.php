<?php

use App\Http\Controllers\ProfileController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\BukuController;
use Illuminate\Support\Facades\Auth;
use App\Http\Controllers\KategoriController;
use App\Http\Controllers\PeminjamanController;
use App\Http\Controllers\UserController; // Pastikan import Controller-nya di atas

Route::middleware(['auth', 'role:admin'])->group(function () {
    // Route untuk halaman Manajemen Member
    Route::get('/manajemen-user', [UserController::class, 'index'])->name('user.index');
    Route::delete('/manajemen-user/{id}', [UserController::class, 'destroy'])->name('user.destroy');
});



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