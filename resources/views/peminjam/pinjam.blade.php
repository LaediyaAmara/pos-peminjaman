<x-app-layout>
    <div class="mb-8">
        <h2 class="font-black text-3xl text-emerald-950 tracking-tight">📖 Jelajah Perpustakaan</h2>
        <p class="text-gray-500 mt-1">Cari judul buku, penulis, atau filter berdasarkan kategori.</p>
    </div>

    {{-- Form Pencarian & Filter --}}
    <div class="mb-10 flex flex-col md:flex-row gap-6 items-center justify-between">
        
        {{-- Search Bar --}}
        <div class="w-full md:w-1/2">
            <form action="{{ route('peminjam.pinjam') }}" method="GET" class="relative group">
                <input 
                    type="text" 
                    name="search" 
                    placeholder="Cari judul buku atau penulis..." 
                    value="{{ request('search') }}"
                    class="w-full bg-white border-gray-100 border-2 rounded-[1.5rem] py-4 pl-14 pr-6 focus:ring-emerald-500 focus:border-emerald-500 shadow-sm group-hover:shadow-md transition-all text-sm font-bold text-gray-700"
                >
                <div class="absolute left-5 top-1/2 -translate-y-1/2 text-emerald-500">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path>
                    </svg>
                </div>
                <button type="submit" class="hidden">Cari</button>
            </form>
        </div>
        {{-- Status Ringkas --}}
        <div class="flex items-center gap-2">
            <span class="px-4 py-2 bg-emerald-50 text-emerald-700 rounded-2xl text-[10px] font-black uppercase tracking-widest border border-emerald-100">
                Total Koleksi: {{ $bukus->count() }} Buku
            </span>
        </div>
    </div>

    {{-- Alert Section --}}
    @if(session('success'))
        <div class="mb-6 p-4 bg-emerald-500 text-white rounded-[1.5rem] shadow-lg shadow-emerald-200 flex items-center font-bold text-sm animate-bounce">
            <span class="mr-3 bg-white/20 p-1 rounded-lg text-lg">✅</span> {{ session('success') }}
        </div>
    @endif

    @if(session('error'))
        <div class="mb-6 p-4 bg-rose-500 text-white rounded-[1.5rem] shadow-lg shadow-rose-200 flex items-center font-bold text-sm">
            <span class="mr-3 bg-white/20 p-1 rounded-lg text-lg">⚠️</span> {{ session('error') }}
        </div>
    @endif

    {{-- Filter Kategori --}}
    <div class="mb-10 flex items-center gap-3 overflow-x-auto pb-4 no-scrollbar">
        <a href="{{ route('peminjam.pinjam') }}" 
           class="px-8 py-3 rounded-2xl whitespace-nowrap text-xs font-black uppercase tracking-widest transition-all
           {{ !$kategori_id ? 'bg-emerald-600 text-white shadow-xl shadow-emerald-200' : 'bg-white text-gray-400 border border-gray-100 hover:border-emerald-300' }}">
            Semua Buku
        </a>
        @foreach($kategoris as $kat)
        <a href="{{ route('peminjam.pinjam', $kat->KategoriID) }}" 
           class="px-8 py-3 rounded-2xl whitespace-nowrap text-xs font-black uppercase tracking-widest transition-all
           {{ $kategori_id == $kat->KategoriID ? 'bg-emerald-600 text-white shadow-xl shadow-emerald-200' : 'bg-white text-gray-400 border border-gray-100 hover:border-emerald-300' }}">
            {{ $kat->NamaKategori }}
        </a>
        @endforeach
    </div>

    {{-- Grid Buku --}}
    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-8">
        @forelse($bukus as $buku)
        <div class="bg-white rounded-[2.5rem] p-2 shadow-sm hover:shadow-2xl transition-all duration-500 border border-gray-50 group flex flex-col">
            {{-- Thumbnail / Icon --}}
            <div class="w-full h-56 bg-emerald-50 rounded-[2rem] mb-4 flex flex-col items-center justify-center relative overflow-hidden">
                <div class="text-7xl group-hover:scale-125 group-hover:rotate-12 transition-transform duration-500 z-10">📘</div>
                
                {{-- Badge Stok --}}
                <div class="absolute top-4 right-4 px-3 py-1 rounded-xl text-[9px] font-black uppercase tracking-widest z-20 shadow-sm
                    {{ $buku->Stok > 0 ? 'bg-white text-emerald-600' : 'bg-rose-100 text-rose-600' }}">
                    {{ $buku->Stok > 0 ? 'Stok: '.$buku->Stok : 'Habis' }}
                </div>
                
                {{-- Decorative circles --}}
                <div class="absolute -bottom-10 -left-10 w-32 h-32 bg-emerald-100/50 rounded-full"></div>
            </div>
            
            {{-- Content --}}
            <div class="px-6 pb-6 flex-grow flex flex-col">
                <h3 class="font-black text-gray-900 text-lg leading-tight mb-2 group-hover:text-emerald-600 transition-colors line-clamp-2">
                    {{ $buku->Judul }}
                </h3>
                <div class="flex items-center text-xs text-gray-400 font-bold mb-6">
                    <span class="mr-2">✍️</span> {{ $buku->Penulis }}
                </div>
                
                <div class="mt-auto flex items-center justify-between gap-4">
                    <div class="text-center">
                        <p class="text-[9px] text-gray-300 font-black uppercase leading-none mb-1">Tahun</p>
                        <span class="text-xs font-bold text-gray-600 tracking-wider">{{ $buku->TahunTerbit }}</span>
                    </div>

                    <form action="{{ route('peminjaman.store') }}" method="POST" class="flex-1">
                        @csrf
                        <input type="hidden" name="BukuID" value="{{ $buku->BukuID }}">
                        
                        @if($buku->Stok > 0)
                            <button type="submit" class="w-full bg-emerald-600 text-white py-3 rounded-2xl text-[10px] font-black uppercase tracking-[0.1em] hover:bg-emerald-700 hover:translate-y-[-2px] transition-all shadow-lg shadow-emerald-100 active:scale-95">
                                Pinjam
                            </button>
                        @else
                            <button type="button" disabled class="w-full bg-gray-100 text-gray-400 py-3 rounded-2xl text-[10px] font-black uppercase tracking-[0.1em] cursor-not-allowed">
                                Habis
                            </button>
                        @endif
                    </form>
                </div>
            </div>
        </div>
        @empty
        <div class="col-span-full py-20 bg-white rounded-[3rem] border-4 border-dashed border-gray-50 flex flex-col items-center">
            <div class="text-6xl mb-6 opacity-20">🔎</div>
            <p class="text-gray-400 font-black uppercase tracking-widest text-sm text-center">
                Maaf, belum ada koleksi<br>
                <span class="text-xs font-medium lowercase">Silakan pilih kategori lain</span>
            </p>
        </div>
        @endforelse
    </div>

    <style>
        .no-scrollbar::-webkit-scrollbar { display: none; }
        .no-scrollbar { -ms-overflow-style: none; scrollbar-width: none; }
    </style>
</x-app-layout>