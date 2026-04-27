<x-app-layout>
    {{-- 1. TAMPILAN UNTUK ADMIN & PETUGAS --}}
    @if(auth()->user()->role == 'admin' || auth()->user()->role == 'petugas')
        <div class="mb-8">
            <div class="bg-white overflow-hidden shadow-xl sm:rounded-[2.5rem] border border-gray-100 mb-10">
                <div class="p-10 flex items-center justify-between bg-gradient-to-r from-indigo-50 to-white">
                    <div>
                        <h3 class="text-2xl font-black text-gray-800">Selamat Datang, {{ auth()->user()->NamaLengkap }}! 👋</h3>
                        <p class="text-gray-500 mt-1 font-medium">Sistem Perpustakaan Digital siap melayani tugas Anda hari ini.</p>
                    </div>
                   
                </div>
            </div>

{{-- Statistik Dashboard Admin --}}
<div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-12">
    
    {{-- 1. Total Semua Koleksi Buku --}}
    <div class="bg-white p-8 rounded-[2.5rem] border border-gray-100 shadow-sm flex items-center">
        <div class="w-16 h-16 bg-indigo-50 text-indigo-600 rounded-[1.5rem] flex items-center justify-center text-3xl mr-6">
            📚
        </div>
        <div>
            <p class="text-[10px] font-black text-gray-400 uppercase tracking-widest mb-1">Total Koleksi Buku</p>
            <p class="text-3xl font-black text-gray-900">
                {{ \App\Models\Buku::count() }} 
                <span class="text-sm font-bold text-gray-400">Judul</span>
            </p>
        </div>
    </div>

    {{-- 2. Total Buku Yang Sedang Dipinjam (Semua User) --}}
    <div class="bg-white p-8 rounded-[2.5rem] border border-gray-100 shadow-sm flex items-center">
        <div class="w-16 h-16 bg-amber-50 text-amber-600 rounded-[1.5rem] flex items-center justify-center text-3xl mr-6">
            🔄
        </div>
        <div>
            <p class="text-[10px] font-black text-gray-400 uppercase tracking-widest mb-1">Buku Sedang Dipinjam</p>
            <p class="text-3xl font-black text-gray-900">
                {{ \App\Models\Peminjaman::where('StatusPeminjaman', 'Dipinjam')->count() }} 
                <span class="text-sm font-bold text-gray-400">Buku</span>
            </p>
        </div>
    </div>

    {{-- 3. Total Anggota Terdaftar --}}
    <div class="bg-white p-8 rounded-[2.5rem] border border-gray-100 shadow-sm flex items-center">
        <div class="w-16 h-16 bg-emerald-50 text-emerald-600 rounded-[1.5rem] flex items-center justify-center text-3xl mr-6">
            👥
        </div>
        <div>
            <p class="text-[10px] font-black text-gray-400 uppercase tracking-widest mb-1">Total Anggota</p>
            <p class="text-3xl font-black text-gray-900">
                {{ \App\Models\User::where('role', 'peminjam')->count() }} 
                <span class="text-sm font-bold text-gray-400">Orang</span>
            </p>
        </div>
    </div>

</div>

            <h4 class="text-xs font-black text-gray-400 uppercase tracking-[0.2em] mb-6 flex items-center">
                <span class="bg-indigo-600 w-2 h-4 rounded-full mr-3"></span>
                Manajemen Internal
            </h4>

            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-8">
                {{-- Menu Buku --}}
                <a href="{{ route('buku.index') }}" class="group bg-white p-8 rounded-[2rem] shadow-sm hover:shadow-xl transition-all border border-gray-50">
                    <div class="w-14 h-14 bg-indigo-600 rounded-2xl flex items-center justify-center text-2xl shadow-lg mb-4 group-hover:scale-110 transition-transform">📚</div>
                    <h5 class="text-xl font-bold text-indigo-900">Pendataan Buku</h5>
                    <p class="text-sm text-gray-500 mt-2 leading-relaxed">Kelola inventaris dan tambah koleksi buku baru.</p>
                </a>

                {{-- Menu Peminjaman --}}
                <a href="{{ route('peminjaman.index') }}" class="group bg-white p-8 rounded-[2rem] shadow-sm hover:shadow-xl transition-all border border-gray-50">
                    <div class="w-14 h-14 bg-purple-600 rounded-2xl flex items-center justify-center text-2xl shadow-lg mb-4 group-hover:scale-110 transition-transform">🔄</div>
                    <h5 class="text-xl font-bold text-purple-900">Log Peminjaman</h5>
                    <p class="text-sm text-gray-500 mt-2 leading-relaxed">Proses pengembalian dan pantau sirkulasi buku.</p>
                </a>

                {{-- Menu Kategori (Khusus Admin) --}}
                @if(auth()->user()->role == 'admin')
                <a href="{{ route('kategori.index') }}" class="group bg-white p-8 rounded-[2rem] shadow-sm hover:shadow-xl transition-all border border-gray-50">
                    <div class="w-14 h-14 bg-amber-500 rounded-2xl flex items-center justify-center text-2xl shadow-lg mb-4 group-hover:scale-110 transition-transform">🏷️</div>
                    <h5 class="text-xl font-bold text-amber-900">Kategori Buku</h5>
                    <p class="text-sm text-gray-500 mt-2 leading-relaxed">Kelola label kategori novel, sains, dll.</p>
                </a>
                @endif
            </div>
        </div>

    {{-- 2. TAMPILAN UNTUK PEMINJAM (SISWA) --}}
    @elseif(auth()->user()->role == 'peminjam')
        <div class="mb-10 flex flex-col md:flex-row md:items-center justify-between gap-4">
            <div>
                <h2 class="font-black text-4xl text-emerald-950 tracking-tight">Halo, {{ explode(' ', auth()->user()->NamaLengkap)[0] }}! 👋</h2>
                <p class="text-gray-500 mt-1 font-medium">Buku apa yang ingin kamu baca hari ini?</p>
            </div>
            <div class="w-14 h-14 bg-emerald-100 rounded-[1.5rem] flex items-center justify-center text-2xl shadow-inner border border-emerald-200">
                🎓
            </div>
        </div>

        {{-- Statistik Siswa --}}
        <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-12">
            <div class="bg-white p-8 rounded-[2.5rem] border border-gray-100 shadow-sm flex items-center">
                <div class="w-16 h-16 bg-emerald-50 text-emerald-600 rounded-[1.5rem] flex items-center justify-center text-3xl mr-6">📚</div>
                <div>
                    <p class="text-[10px] font-black text-gray-400 uppercase tracking-widest mb-1">Buku yang kamu bawa</p>
                    <p class="text-3xl font-black text-gray-900">
                        {{ \App\Models\Peminjaman::where('UserID', auth()->id())->where('StatusPeminjaman', 'Dipinjam')->count() }} 
                        <span class="text-sm font-bold text-gray-400">Buku</span>
                    </p>
                </div>
            </div>

            <div class="bg-white p-8 rounded-[2.5rem] border border-gray-100 shadow-sm flex items-center">
                <div class="w-16 h-16 bg-amber-50 text-amber-600 rounded-[1.5rem] flex items-center justify-center text-3xl mr-6">⏳</div>
                <div>
                    <p class="text-[10px] font-black text-gray-400 uppercase tracking-widest mb-1">Maksimal Peminjaman</p>
                    <p class="text-3xl font-black text-gray-900">3 <span class="text-sm font-bold text-gray-400">Buku</span></p>
                </div>
            </div>
        </div>

        {{-- Menu Utama Siswa --}}
        <div class="grid grid-cols-1 md:grid-cols-2 gap-8 mb-12">
            <a href="{{ route('peminjam.pinjam') }}" class="relative group overflow-hidden bg-emerald-600 rounded-[3rem] p-10 text-white transition-all hover:scale-[1.02] shadow-2xl shadow-emerald-200">
                <div class="relative z-10">
                    <h3 class="text-4xl font-black mb-3 leading-tight">Cari<br>Buku</h3>
                    <p class="text-emerald-100 text-sm max-w-[200px] mb-8">Jelajahi ribuan koleksi buku digital kami.</p>
                    <div class="flex items-center font-black text-[10px] uppercase tracking-[0.2em] bg-white/20 w-fit px-6 py-3 rounded-2xl backdrop-blur-md">
                        Mulai Jelajah <span class="ml-2">→</span>
                    </div>
                </div>
                <div class="absolute -right-4 -bottom-4 text-[10rem] opacity-20 transform group-hover:rotate-12 transition-transform duration-500 pointer-events-none">📖</div>
            </a>

            <a href="{{ route('peminjam.index') }}" class="relative group overflow-hidden bg-white rounded-[3rem] p-10 text-gray-900 border-2 border-gray-50 transition-all hover:border-indigo-100 hover:shadow-2xl hover:scale-[1.02]">
                <div class="relative z-10">
                    <h3 class="text-4xl font-black mb-3 leading-tight text-indigo-950">Pinjaman<br>Saya</h3>
                    <p class="text-gray-400 text-sm max-w-[200px] mb-8">Lihat status buku dan riwayat bacaan kamu.</p>
                    <div class="flex items-center font-black text-[10px] uppercase tracking-[0.2em] text-indigo-600 border border-indigo-100 w-fit px-6 py-3 rounded-2xl">
                        Cek Status <span class="ml-2">→</span>
                    </div>
                </div>
                <div class="absolute -right-4 -bottom-4 text-[10rem] opacity-5 transform group-hover:-rotate-12 transition-transform duration-500 pointer-events-none">📋</div>
            </a>
        </div>


        
        {{-- Quote --}}
        <div class="bg-white border border-gray-100 rounded-[2.5rem] p-10 text-center shadow-sm">
            <p class="italic text-gray-500 text-lg leading-relaxed">
                "Buku adalah jendela dunia. Semakin banyak kamu membaca, semakin banyak hal yang akan kamu ketahui."
            </p>
            <div class="mt-4 flex items-center justify-center gap-3">
                <span class="h-px w-8 bg-gray-200"></span>
                <p class="font-black text-gray-400 text-[10px] uppercase tracking-widest">— Dr. Seuss</p>
                <span class="h-px w-8 bg-gray-200"></span>
            </div>
        </div>
    @endif
</x-app-layout>