<div class="space-y-6">
    {{-- Grup: Utama --}}
    <div>
        <p class="text-[10px] font-black text-indigo-400 uppercase tracking-widest px-4 mb-3">Utama</p>
        <a href="{{ route('dashboard') }}" class="flex items-center px-4 py-3 rounded-2xl transition-all {{ request()->routeIs('dashboard') ? 'bg-indigo-600 shadow-lg shadow-indigo-900/50 text-white' : 'hover:bg-white/10 text-indigo-100' }}">
            <span class="mr-3">🏠</span> Dashboard
        </a>
    </div>

    {{-- Grup: Manajemen Data (Hanya muncul untuk Admin & Petugas) --}}
    @if(auth()->user()->role == 'admin' || auth()->user()->role == 'petugas')
    <div>
        <p class="text-[10px] font-black text-indigo-400 uppercase tracking-widest px-4 mb-3">Manajemen Data</p>
        <a href="{{ route('buku.index') }}" class="flex items-center px-4 py-3 rounded-2xl hover:bg-white/10 transition-all text-indigo-100 mb-1 {{ request()->routeIs('buku.*') ? 'bg-white/10 text-white border-l-4 border-indigo-400' : '' }}">
            <span class="mr-3">📖</span> Koleksi Buku 
        </a>
        <a href="{{ route('peminjaman.index') }}" class="flex items-center px-4 py-3 rounded-2xl hover:bg-white/10 transition-all text-indigo-100 mb-1 {{ request()->routeIs('peminjaman.index') ? 'bg-white/10 text-white border-l-4 border-indigo-400' : '' }}">
            <span class="mr-3">🔄</span> Log Peminjaman
        </a>
        
        @if(auth()->user()->role == 'admin')
        <a href="{{ route('kategori.index') }}" class="flex items-center px-4 py-3 rounded-2xl hover:bg-white/10 transition-all text-indigo-100 mb-1 {{ request()->routeIs('kategori.*') ? 'bg-white/10 text-white border-l-4 border-indigo-400' : '' }}">
            <span class="mr-3">🏷️</span> Kategori Buku
        </a>
        <a href="{{ route('user.index') }}" class="flex items-center px-4 py-3 rounded-2xl hover:bg-white/10 transition-all text-indigo-100 mb-1 {{ request()->routeIs('user.*') ? 'bg-white/10 text-white border-l-4 border-indigo-400' : '' }}">
            <span class="mr-3">👥</span> Manajemen Member
        </a>
        <a href="{{ route('denda.index') }}" class="flex items-center px-4 py-3 rounded-2xl hover:bg-white/10 transition-all text-indigo-100 mb-1 {{ request()->routeIs('denda.*') ? 'bg-white/10 text-white border-l-4 border-indigo-400' : '' }}">
            <span class="mr-3">📖</span> Denda 
        </a>
        @endif
    </div>

    {{-- Grup: Laporan --}}
    <div>
        <p class="text-[10px] font-black text-indigo-400 uppercase tracking-widest px-4 mb-3">Laporan Sakti</p>
        <a href="{{ route('peminjaman.laporan') }}" class="flex items-center px-4 py-3 rounded-2xl hover:bg-white/10 transition-all text-indigo-100 {{ request()->routeIs('peminjaman.laporan') ? 'bg-white/10 text-white border-l-4 border-indigo-400' : '' }}">
            <span class="mr-3">📊</span> Generate Report
        </a>
    </div>
    @endif

    