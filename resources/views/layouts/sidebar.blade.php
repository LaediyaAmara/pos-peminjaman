<div class="space-y-6">
    <div>
        <p class="text-[10px] font-black text-indigo-400 uppercase tracking-widest px-4 mb-3">Utama</p>
        <a href="{{ route('dashboard') }}" class="flex items-center px-4 py-3 rounded-2xl transition-all {{ request()->routeIs('dashboard') ? 'bg-indigo-600 shadow-lg shadow-indigo-900/50 text-white' : 'hover:bg-white/10 text-indigo-100' }}">
            <span class="mr-3">🏠</span> Dashboard
        </a>
    </div>

    @if(auth()->user()->role == 'admin' || auth()->user()->role == 'petugas')
    <div>
        <p class="text-[10px] font-black text-indigo-400 uppercase tracking-widest px-4 mb-3">Manajemen Data</p>
        <a href="{{ route('buku.index') }}" class="flex items-center px-4 py-3 rounded-2xl hover:bg-white/10 transition-all text-indigo-100 mb-1">
            <span class="mr-3">📖</span> Koleksi Buku
        </a>
        <a href="{{ route('peminjaman.index') }}" class="flex items-center px-4 py-3 rounded-2xl hover:bg-white/10 transition-all text-indigo-100 mb-1">
            <span class="mr-3">🔄</span> Log Peminjaman
        </a>
        
        @if(auth()->user()->role == 'admin')
        <a href="{{ route('kategori.index') }}" class="flex items-center px-4 py-3 rounded-2xl hover:bg-white/10 transition-all text-indigo-100 mb-1">
            <span class="mr-3">🏷️</span> Kategori Buku
        </a>
        <a href="{{ route('user.index') }}" class="flex items-center px-4 py-3 rounded-2xl hover:bg-white/10 transition-all text-indigo-100 mb-1">
            <span class="mr-3">👥</span> Manajemen Member
        </a>
        @endif
    </div>
    @endif

    @if(auth()->user()->role == 'peminjam')
    <div>
        <p class="text-[10px] font-black text-indigo-400 uppercase tracking-widest px-4 mb-3">Layanan Siswa</p>
        <a href="{{ route('peminjam.pinjam') }}" class="flex items-center px-4 py-3 rounded-2xl hover:bg-white/10 transition-all text-indigo-100 mb-1">
            <span class="mr-3">🔍</span> Cari & Pinjam Buku
        </a>
        <a href="{{ route('peminjam.index') }}" class="flex items-center px-4 py-3 rounded-2xl hover:bg-white/10 transition-all text-indigo-100 mb-1">
            <span class="mr-3">📋</span> Pinjaman Saya
        </a>
    </div>
    @endif

    @if(auth()->user()->role == 'admin' || auth()->user()->role == 'petugas')
    <div>
        <p class="text-[10px] font-black text-indigo-400 uppercase tracking-widest px-4 mb-3">Laporan Sakti</p>
        <a href="{{ route('peminjaman.laporan') }}" class="flex items-center px-4 py-3 rounded-2xl hover:bg-white/10 transition-all text-indigo-100">
            <span class="mr-3">📊</span> Generate Report
        </a>
    </div>
    @endif
    
</div>