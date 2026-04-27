<x-app-layout>
    <x-slot name="header">
        <h2 class="font-bold text-2xl text-indigo-800 leading-tight">
            📚 Koleksi Perpustakaan Digital
        </h2>
    </x-slot>

    <div class="py-12 bg-gray-50">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            
            {{-- Bagian Atas: Search & Tombol Tambah --}}
            <div class="mb-6 flex flex-col md:flex-row justify-between items-center gap-4">
                <form action="{{ route('buku.index') }}" method="GET" class="flex w-full max-w-md shadow-sm">
                    <input 
                        type="text" 
                        name="search" 
                        placeholder="Cari judul, penulis, atau penerbit..." 
                        value="{{ request('search') }}"
                        class="w-full rounded-l-2xl border-gray-200 focus:ring-indigo-500 focus:border-indigo-500 text-sm"
                    >
                    <button type="submit" class="bg-indigo-600 text-white px-6 py-2 rounded-r-2xl hover:bg-indigo-700 transition-all flex items-center">
                        <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path></svg>
                        Cari
                    </button>
                </form>

                @if(auth()->user()->role == 'admin' || auth()->user()->role == 'petugas')
                <a href="{{ route('buku.create') }}" class="w-full md:w-auto text-center px-6 py-2.5 bg-emerald-500 hover:bg-emerald-600 text-white text-sm font-bold rounded-2xl transition-all shadow-lg shadow-emerald-100">
                    + Tambah Buku Baru
                </a>
                @endif
            </div>

            <div class="bg-white overflow-hidden shadow-xl sm:rounded-3xl border border-gray-100">
                <div class="p-8">
                    <div class="overflow-x-auto">
                        <table class="w-full text-sm text-left">
                            <thead>
                                <tr class="text-xs text-indigo-900 uppercase bg-indigo-50/50 border-b border-indigo-100">
                                    <th class="px-6 py-4 font-bold">Informasi Buku</th>
                                    <th class="px-6 py-4 font-bold">Kategori</th>
                                    <th class="px-6 py-4 font-bold text-center">Tahun</th>
                                    <th class="px-6 py-4 font-bold text-center">Stok</th>
                                    <th class="px-6 py-4 font-bold text-center">Aksi</th>
                                </tr>
                            </thead>
                            <tbody class="divide-y divide-gray-100">
                                @forelse($bukus as $buku)
                                <tr class="hover:bg-indigo-50/30 transition-colors">
                                    <td class="px-6 py-4">
                                        <div class="font-bold text-gray-900 text-base">{{ $buku->Judul }}</div>
                                        <div class="text-xs text-gray-500">{{ $buku->Penulis }} | <span class="italic">{{ $buku->Penerbit }}</span></div>
                                    </td>
                                    <td class="px-6 py-4">
                                        <span class="px-3 py-1 bg-gray-100 text-gray-600 rounded-lg text-xs font-semibold">
                                            {{ $buku->kategori->NamaKategori ?? 'Umum' }}
                                        </span>
                                    </td>
                                    <td class="px-6 py-4 text-center">
                                        <span class="bg-indigo-100 text-indigo-800 px-2.5 py-1 rounded-md text-xs font-bold">
                                            {{ $buku->TahunTerbit }}
                                        </span>
                                    </td>
                                    <td class="px-6 py-4 text-center">
                                        <span class="text-sm font-black {{ $buku->Stok > 0 ? 'text-emerald-600' : 'text-rose-600' }}">
                                            {{ $buku->Stok }}
                                        </span>
                                    </td>
                                    <td class="px-6 py-4">
                                        <div class="flex justify-center space-x-2">
                                            <a href="{{ route('buku.edit', $buku->BukuID) }}" class="text-indigo-600 hover:bg-indigo-600 hover:text-white p-2 rounded-xl transition-all" title="Edit Buku">
                                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"></path></svg>
                                            </a>

                                            <form action="{{ route('buku.destroy', $buku->BukuID) }}" method="POST" onsubmit="return confirm('Hapus buku ini dari rak?')">
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit" class="text-rose-600 hover:bg-rose-600 hover:text-white p-2 rounded-xl transition-all" title="Hapus Buku">
                                                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path></svg>
                                                </button>
                                            </form>
                                        </div>
                                    </td>
                                </tr>
                                @empty
                                <tr>
                                    <td colspan="5" class="px-6 py-20 text-center">
                                        <div class="flex flex-col items-center justify-center text-gray-400">
                                            <svg class="w-16 h-16 mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5s3.332.477 4.5 1.253v13C19.832 18.477 18.246 18 16.5 18c-1.746 0-3.332.477-4.5 1.253"></path></svg>
                                            <p class="text-lg font-medium">Buku tidak ditemukan</p>
                                            <p class="text-sm italic">Coba kata kunci lain atau <a href="{{ route('buku.index') }}" class="text-indigo-600 font-bold underline">Lihat Semua Buku</a></p>
                                        </div>
                                    </td>
                                </tr>
                                
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>