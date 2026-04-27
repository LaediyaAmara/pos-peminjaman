<x-app-layout>
    <x-slot name="header">
        <h2 class="font-bold text-2xl text-emerald-800 leading-tight">📖 Jelajah Perpustakaan</h2>
    </x-slot>

    <div class="py-12 bg-gray-50">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            
            @if(session('success'))
                <div class="mb-6 p-4 bg-emerald-100 border border-emerald-200 text-emerald-700 rounded-2xl flex items-center shadow-sm">
                    <span class="mr-2">✅</span> {{ session('success') }}
                </div>
            @endif
            @if(session('error'))
                <div class="mb-6 p-4 bg-red-100 border border-red-200 text-red-700 rounded-2xl flex items-center shadow-sm">
                    <span class="mr-2">⚠️</span> {{ session('error') }}
                </div>
            @endif

            <div class="mb-8 overflow-x-auto pb-4 flex space-x-4 no-scrollbar">
                <a href="{{ route('peminjam.pinjam') }}" 
                   class="px-6 py-2 rounded-full whitespace-nowrap font-bold transition-all {{ !$kategori_id ? 'bg-emerald-600 text-white shadow-lg' : 'bg-white text-emerald-600 border border-emerald-100' }}">
                    Semua Buku
                </a>
                @foreach($kategoris as $kat)
                <a href="{{ route('peminjam.pinjam', $kat->KategoriID) }}" 
                   class="px-6 py-2 rounded-full whitespace-nowrap font-bold transition-all {{ $kategori_id == $kat->KategoriID ? 'bg-emerald-600 text-white shadow-lg' : 'bg-white text-emerald-600 border border-emerald-100' }}">
                    {{ $kat->NamaKategori }}
                </a>
                @endforeach
            </div>

            <div class="grid grid-cols-1 md:grid-cols-3 lg:grid-cols-4 gap-8">
                @forelse($bukus as $buku)
                <div class="bg-white rounded-3xl p-6 shadow-sm border border-gray-100 hover:shadow-2xl transition-all group flex flex-col h-full">
                    <div class="w-full h-48 bg-emerald-50 rounded-2xl mb-4 flex items-center justify-center text-6xl group-hover:scale-105 transition-transform duration-300">
                        📘
                    </div>
                    
                    <div class="flex-grow">
                        <h3 class="font-extrabold text-gray-800 text-xl line-clamp-2 mb-1">{{ $buku->Judul }}</h3>
                        <p class="text-gray-500 text-sm italic">Oleh: {{ $buku->Penulis }}</p>
                        
                        <div class="mt-2 text-xs font-bold {{ $buku->Stok > 0 ? 'text-emerald-600' : 'text-rose-600' }}">
                            Stok Tersedia: {{ $buku->Stok }}
                        </div>
                    </div>
                    
                    <div class="flex items-center justify-between mt-6 pt-4 border-t border-gray-50">
                        <span class="text-xs font-bold text-emerald-700 bg-emerald-50 px-3 py-1 rounded-full">
                            {{ $buku->TahunTerbit }}
                        </span>

                        {{-- Form Peminjaman dengan Proteksi Stok --}}
                        <form action="{{ route('peminjaman.store') }}" method="POST">
                            @csrf
                            <input type="hidden" name="BukuID" value="{{ $buku->BukuID }}">
                            
                            @if($buku->Stok > 0)
                                <button type="submit" class="bg-emerald-600 text-white px-5 py-2.5 rounded-xl text-sm font-bold hover:bg-emerald-700 transition-all shadow-md active:scale-95">
                                    Pinjam
                                </button>
                            @else
                                <button type="button" disabled class="bg-gray-300 text-gray-500 px-5 py-2.5 rounded-xl text-sm font-bold cursor-not-allowed">
                                    Habis
                                </button>
                            @endif
                        </form>
                    </div>
                </div>
                @empty
                <div class="col-span-full text-center py-24 bg-white rounded-3xl border-2 border-dashed border-gray-200 text-gray-400">
                    🔍 Maaf, belum ada koleksi di sini.
                </div>
                @endforelse
            </div>
        </div>
    </div>

    <style>
        .no-scrollbar::-webkit-scrollbar { display: none; }
        .no-scrollbar { -ms-overflow-style: none; scrollbar-width: none; }
    </style>
</x-app-layout>