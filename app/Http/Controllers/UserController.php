<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\Peminjaman; 
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash; 

class UserController extends Controller
{
    // 1. Menampilkan Daftar Anggota
    public function index()
    {
        $members = User::where('role', 'peminjam')->latest()->get();
        return view('user.index', compact('members'));
    }

    // 2. MENAMPILKAN FORM TAMBAH (Fungsi yang hilang dan bikin error)
    public function create()
    {
        return view('user.create');
    }

    // 3. Menyimpan Anggota Baru
    public function store(Request $request)
{
    $request->validate([
        'NamaLengkap' => 'required|string|max:255',
        'Username'    => 'required|string|unique:users,Username',
        'email'       => 'required|email|unique:users,email',
        'password'    => 'required|min:8',
    ]);

    User::create([
        'NamaLengkap' => $request->NamaLengkap,
        'Username'    => $request->Username,
        'email'       => $request->email,
        'password'    => Hash::make($request->password), 
        'role'        => 'peminjam',
        'Alamat'      => '-', // Tambahkan ini agar database tidak error
    ]);

    return redirect()->route('user.index')->with('success', 'Anggota berhasil ditambahkan!');
}

    // 4. Menampilkan Form Edit
    public function edit($id)
    {
        $user = User::findOrFail($id);
        return view('user.edit', compact('user'));
    }

    // 5. Update Data Anggota
    public function update(Request $request, $id)
    {
        $user = User::findOrFail($id);
        
        $request->validate([
            'NamaLengkap' => 'required|string|max:255',
            'email'       => 'required|email|unique:users,email,'.$id,
        ]);

        $user->NamaLengkap = $request->NamaLengkap;
        $user->email = $request->email;

        if ($request->filled('password')) {
            $user->password = Hash::make($request->password);
        }

        $user->save();

        return redirect()->route('user.index')->with('success', 'Profil anggota berhasil diperbarui!');
    }

    // 6. Hapus Anggota
    public function destroy($id)
    {
        $user = User::findOrFail($id);

        // Hapus riwayat pinjam dulu agar tidak error Foreign Key
        Peminjaman::where('UserID', $id)->delete();

        $user->delete();

        return redirect()->route('user.index')->with('success', 'Member dan riwayatnya berhasil dihapus!');
    }
}