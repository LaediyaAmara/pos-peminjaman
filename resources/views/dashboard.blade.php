<x-app-layout>
    <x-slot name="header">
        <div class="flex justify-between items-center">
            <h2 class="font-bold text-2xl text-indigo-800 leading-tight">
                {{ __('Dashboard') }}
            </h2>
            <div class="flex items-center space-x-2">
                <span class="text-sm font-medium text-gray-500">Masuk sebagai:</span>
                <span class="px-4 py-1.5 rounded-full text-xs font-extrabold uppercase tracking-wider shadow-sm
                    {{ auth()->user()->role == 'admin' ? 'bg-red-500 text-white' : 
                       (auth()->user()->role == 'petugas' ? 'bg-blue-500 text-white' : 'bg-emerald-500 text-white') }}">
                    {{ auth()->user()->role }}
                </span>
            </div>
        </div>
    </x-slot>

    <div class="py-12 bg-gray-50">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            
            <div class="bg-white overflow-hidden shadow-xl sm:rounded-3xl border border-gray-100 mb-8">
                <div class="p-8 flex items-center justify-between bg-gradient-to-r from-indigo-50 to-white">
                    <div>
                        <h3 class="text-2xl font-bold text-gray-800">Selamat Datang, {{ auth()->user()->NamaLengkap }}! 👋</h3>
                        <p class="text-gray-600 mt-1">Sistem Perpustakaan Digital siap melayani tugas Anda hari ini.</p>
                    </div>
                </div>
            </div>

            <h4 class="text-lg font-bold text-gray-700 mb-6 flex items-center">
                <span class="bg-indigo-600 w-2 h-6 rounded-full mr-3"></span>
                Menu Utama Aplikasi
            </h4>

            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-8">
                
                {{-- MENU ADMIN & PETUGAS: PENDATAAN BUKU --}}
                @if(auth()->user()->role == 'admin' || auth()->user()->role == 'petugas')
                <a href="{{ route('buku.index') }}" class="group bg-white p-1 rounded-3xl shadow-sm hover:shadow-2xl transition-all duration-300 border border-gray-100">
                    <div class="p-6 bg-indigo-50 rounded-2xl h-full border border-transparent group-hover:border-indigo-200">
                        <div class="w-14 h-14 bg-indigo-600 rounded-2xl flex items-center justify-center text-2xl shadow-lg shadow-indigo-200 mb-4 group-hover:scale-110 transition-transform">
                            📚
                        </div>
                        <h5 class="text-xl font-bold text-indigo-900">Pendataan Buku</h5>
                        <p class="text-sm text-indigo-600 mt-2 leading-relaxed">Kelola inventaris buku, tambah koleksi baru, atau perbarui data.</p>
                    </div>
                </a>

                {{-- MENU ADMIN & PETUGAS: LOG PEMINJAMAN --}}
                <a href="{{ route('peminjaman.index') }}" class="group bg-white p-1 rounded-3xl shadow-sm hover:shadow-2xl transition-all duration-300 border border-gray-100">
                    <div class="p-6 bg-purple-50 rounded-2xl h-full border border-transparent group-hover:border-purple-200">
                        <div class="w-14 h-14 bg-purple-600 rounded-2xl flex items-center justify-center text-2xl shadow-lg shadow-purple-200 mb-4 group-hover:scale-110 transition-transform">
                            🔄
                        </div>
                        <h5 class="text-xl font-bold text-purple-900">Log Peminjaman</h5>
                        <p class="text-sm text-purple-600 mt-2 leading-relaxed">Proses pengembalian buku dan pantau sirkulasi peminjaman siswa.</p>
                    </div>
                </a>
                @endif

                {{-- MENU KHUSUS ADMIN: KATEGORI --}}
                @if(auth()->user()->role == 'admin')
                <a href="{{ route('kategori.index') }}" class="group bg-white p-1 rounded-3xl shadow-sm hover:shadow-2xl transition-all duration-300 border border-gray-100">
                    <div class="p-6 bg-amber-50 rounded-2xl h-full border border-transparent group-hover:border-amber-200">
                        <div class="w-14 h-14 bg-amber-500 rounded-2xl flex items-center justify-center text-2xl shadow-lg shadow-amber-200 mb-4 group-hover:scale-110 transition-transform">
                            🏷️
                        </div>
                        <h5 class="text-xl font-bold text-amber-900">Kategori Buku</h5>
                        <p class="text-sm text-amber-600 mt-2 leading-relaxed">Kelola label kategori seperti Novel, Sains, atau Sejarah.</p>
                    </div>
                </a>

                {{-- MENU KHUSUS ADMIN: GENERATE LAPORAN --}}
                <a href="{{ route('peminjaman.laporan') }}" class="group bg-white p-1 rounded-3xl shadow-sm hover:shadow-2xl transition-all duration-300 border border-gray-100">
                    <div class="p-6 bg-rose-50 rounded-2xl h-full border border-transparent group-hover:border-rose-200">
                        <div class="w-14 h-14 bg-rose-600 rounded-2xl flex items-center justify-center text-2xl shadow-lg shadow-rose-200 mb-4 group-hover:scale-110 transition-transform">
                            📊
                        </div>
                        <h5 class="text-xl font-bold text-rose-900">Generate Laporan</h5>
                        <p class="text-sm text-rose-600 mt-2 leading-relaxed">Cetak laporan peminjaman lengkap untuk arsip perpustakaan.</p>
                    </div>
                </a>
                @endif

                {{-- MENU KHUSUS PEMINJAM (SISWA) --}}
                @if(auth()->user()->role == 'peminjam')
                <a href="{{ route('peminjam.pinjam') }}" class="group bg-white p-1 rounded-3xl shadow-sm hover:shadow-2xl transition-all duration-300 border border-gray-100">
                    <div class="p-6 bg-emerald-50 rounded-2xl h-full border border-transparent group-hover:border-emerald-200">
                        <div class="w-14 h-14 bg-emerald-600 rounded-2xl flex items-center justify-center text-2xl shadow-lg shadow-emerald-200 mb-4 group-hover:scale-110 transition-transform">
                            📖
                        </div>
                        <h5 class="text-xl font-bold text-emerald-900">Cari & Pinjam Buku</h5>
                        <p class="text-sm text-emerald-600 mt-2 leading-relaxed">Lihat koleksi buku yang tersedia dan lakukan peminjaman mandiri.</p>
                    </div>
                </a>

                <a href="{{ route('peminjam.index') }}" class="group bg-white p-1 rounded-3xl shadow-sm hover:shadow-2xl transition-all duration-300 border border-gray-100">
                    <div class="p-6 bg-blue-50 rounded-2xl h-full border border-transparent group-hover:border-blue-200">
                        <div class="w-14 h-14 bg-blue-600 rounded-2xl flex items-center justify-center text-2xl shadow-lg shadow-blue-200 mb-4 group-hover:scale-110 transition-transform">
                            📋
                        </div>
                        <h5 class="text-xl font-bold text-blue-900">Pinjaman Saya</h5>
                        <p class="text-sm text-blue-600 mt-2 leading-relaxed">Cek buku apa saja yang sedang kamu bawa dan tanggal kembalinya.</p>
                    </div>
                </a>
                @endif

            </div>
        </div>
    </div>
</x-app-layout>