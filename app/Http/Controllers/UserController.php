<?php

namespace App\Http\Controllers;

use App\Models\User; // Pastikan baris ini ada
use Illuminate\Http\Request;

class UserController extends Controller
{
    public function index()
    {
        // Mengambil user dengan role peminjam (Siswa)
        $members = User::where('role', 'peminjam')->get();
        return view('user.index', compact('members'));
    }

    public function destroy($id)
{
    $user = \App\Models\User::findOrFail($id);

    // 1. Hapus dulu semua data di tabel peminjaman yang terkait dengan user ini
    \App\Models\Peminjaman::where('UserID', $id)->delete();

    // 2. Baru hapus usernya
    $user->delete();

    return back()->with('success', 'Member dan seluruh riwayat pinjamannya berhasil dihapus!');
}
public function store(Request $request)
{
    $request->validate([
        'Username' => 'required|unique:users,Username',
        'Email' => 'required|email|unique:users,Email',
        'NamaLengkap' => 'required',
        'Password' => 'required|min:6',
        'Alamat' => 'required',
    ]);

    \App\Models\User::create([
        'Username' => $request->Username,
        'Email' => $request->Email,
        'NamaLengkap' => $request->NamaLengkap,
        'Password' => bcrypt($request->Password),
        'Alamat' => $request->Alamat,
        'role' => 'peminjam', // Otomatis jadi peminjam/anggota
    ]);

    return redirect()->back()->with('success', 'Anggota berhasil ditambahkan!');
}


}
