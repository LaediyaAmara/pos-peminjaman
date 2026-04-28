<x-app-layout>
    <x-slot name="header">
        <h2 class="font-bold text-2xl text-indigo-800 leading-tight">📋 Log Peminjaman Buku</h2>
    </x-slot>

    <div class="py-12 bg-gray-50">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            
            {{-- Tombol Kembali --}}
            <div class="mb-6">
                <a href="{{ route('dashboard') }}" class="inline-flex items-center text-sm font-bold text-indigo-600 hover:text-indigo-800 transition-colors group">
                    <div class="w-8 h-8 bg-indigo-100 rounded-lg flex items-center justify-center mr-3 group-hover:bg-indigo-600 group-hover:text-white transition-all">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"></path>
                        </svg>
                    </div>
                    Kembali ke Dashboard
                </a>
            </div>

            {{-- Search Bar --}}
            <div class="mb-8 flex justify-center">
                <form action="{{ route('peminjaman.index') }}" method="GET" class="flex w-full max-w-2xl shadow-sm">
                    <input 
                        type="text" 
                        name="search" 
                        placeholder="Cari nama peminjam atau judul buku..." 
                        value="{{ request('search') }}"
                        class="w-full rounded-l-2xl border-gray-100 focus:ring-indigo-500 focus:border-indigo-500 py-3 px-6"
                    >
                    <button type="submit" class="bg-indigo-600 text-white px-8 py-3 rounded-r-2xl hover:bg-indigo-700 transition-all font-bold">
                        Cari Log
                    </button>
                </form>
            </div>

            <div class="bg-white overflow-hidden shadow-xl sm:rounded-[2.5rem] border border-gray-100">
                <div class="p-4 md:p-8"> {{-- Padding responsif --}}
                    @if(session('success'))
                        <div class="mb-6 p-4 bg-emerald-100 text-emerald-700 rounded-2xl border border-emerald-200 font-bold text-sm">
                            ✅ {{ session('success') }}
                        </div>
                    @endif

                    <div class="overflow-x-auto no-scrollbar">
                        <table class="w-full text-left table-auto"> {{-- Table Auto agar kolom menyesuaikan isi --}}
                            <thead>
                                <tr class="text-indigo-800 uppercase text-[9px] font-black tracking-[0.15em] border-b border-indigo-50">
                                    <th class="px-4 py-5">Peminjam</th>
                                    <th class="px-4 py-5">Buku</th>
                                    <th class="px-4 py-5 text-center">Waktu Pinjam</th>
                                    <th class="px-4 py-5 text-center">Waktu Kembali</th>
                                    <th class="px-4 py-5 text-center">Status</th>
                                    <th class="px-4 py-5 text-center">Aksi</th>
                                </tr>
                            </thead>
                            <tbody class="divide-y divide-gray-50">
                                @forelse($peminjamans as $p)
                                <tr class="hover:bg-indigo-50/30 transition-colors group">
                                    <td class="px-4 py-6">
                                        <div class="font-bold text-gray-900 text-sm leading-tight">{{ $p->user->NamaLengkap }}</div>
                                        <div class="text-[10px] text-gray-400 font-medium truncate max-w-[120px]">{{ $p->user->email }}</div>
                                    </td>
                                    <td class="px-4 py-6">
                                        <div class="text-indigo-600 font-bold text-sm leading-tight line-clamp-1">{{ $p->buku->Judul }}</div>
                                        <div class="text-[9px] text-gray-400 font-black uppercase tracking-tighter">ID: {{ $p->BukuID }}</div>
                                    </td>
                                    <td class="px-4 py-6 text-center whitespace-nowrap">
                                        <div class="text-[11px] text-gray-700 font-bold">{{ \Carbon\Carbon::parse($p->TanggalPeminjaman)->format('d M Y') }}</div>
                                        <div class="text-[10px] text-indigo-400 font-medium italic">{{ \Carbon\Carbon::parse($p->TanggalPeminjaman)->format('H:i') }} WIB</div>
                                    </td>
<td class="px-4 py-6 text-center whitespace-nowrap">
    @if($p->StatusPeminjaman == 'Kembali')
        {{-- 1. TAMPILAN JIKA SUDAH KEMBALI --}}
        <div class="text-[11px] text-emerald-600 font-bold">
            {{ \Carbon\Carbon::parse($p->TanggalPengembalian)->format('d M Y') }}
        </div>
        <div class="text-[10px] text-emerald-400 font-medium italic">
            {{ \Carbon\Carbon::parse($p->TanggalPengembalian)->format('H:i') }} WIB
        </div>
    @else
        {{-- 2. LOGIKA DINAMIS UNTUK DEADLINE & KETERLAMBATAN --}}
        @php
            $deadline = \Carbon\Carbon::parse($p->TanggalPengembalian);
            $hariIni = now();
            $selisihHari = $hariIni->diffInDays($deadline, false); // false agar menghasilkan angka negatif jika lewat
        @endphp

        @if($hariIni->gt($deadline))
            {{-- TAMPILAN JIKA TELAT --}}
            <div class="flex flex-col items-center">
                <span class="text-[9px] text-rose-600 font-black px-2 py-1 bg-rose-50 rounded-lg border border-rose-100 uppercase tracking-widest animate-pulse">
                    ⚠️ Telat Pengembalian
                </span>
                <div class="text-[9px] text-rose-400 mt-1 font-bold italic">
                    Lewat {{ abs($selisihHari) }} Hari
                </div>
            </div>
        @else
            {{-- TAMPILAN INFORMASI DEADLINE (BELUM TELAT) --}}
            <div class="flex flex-col items-center">
                <span class="text-[9px] text-amber-500 font-black px-2 py-1 bg-amber-50 rounded-lg border border-amber-100 uppercase tracking-widest">
                    Batas: {{ $deadline->format('d M Y') }}
                </span>
                <div class="text-[10px] {{ $selisihHari <= 1 ? 'text-rose-500 animate-bounce' : 'text-gray-400' }} mt-1 font-medium italic">
                    {{ $selisihHari == 0 ? 'Terakhir Hari Ini!' : $selisihHari . ' Hari lagi' }}
                </div>
            </div>
        @endif
    @endif
</td>
                                    <td class="px-4 py-6 text-center">
                                        <div class="flex flex-col items-center gap-1.5">
                                            <span class="px-3 py-1 rounded-full text-[8px] font-black uppercase tracking-widest shadow-sm
                                                {{ $p->StatusPeminjaman == 'Dipinjam' ? 'bg-amber-100 text-amber-600 border border-amber-200' : 'bg-emerald-500 text-white shadow-emerald-100' }}">
                                                {{ $p->StatusPeminjaman }}
                                            </span>
                                            @if($p->StatusPeminjaman == 'Kembali')
                                                <span class="text-[9px] font-black px-2 py-0.5 rounded-md border {{ $p->Kondisi == 'Baik' ? 'text-emerald-500 border-emerald-100 bg-emerald-50' : ($p->Kondisi == 'Rusak' ? 'text-amber-500 border-amber-100 bg-amber-50' : 'text-rose-500 border-rose-100 bg-rose-50') }}">
                                                    {{ $p->Kondisi }}
                                                </span>
                                            @endif
                                        </div>
                                    </td>
                                    <td class="px-4 py-6">
                                        @if($p->StatusPeminjaman == 'Dipinjam')
                                        <form action="{{ route('peminjaman.kembalikan', $p->PeminjamanID) }}" method="POST">
                                            @csrf 
                                            @method('PUT')
                                            <div class="flex items-center justify-center gap-1.5">
                                                <button type="submit" name="kondisi" value="Baik" class="bg-emerald-50 text-emerald-600 px-3 py-2 rounded-xl hover:bg-emerald-600 hover:text-white transition-all text-[9px] font-black shadow-sm">
                                                    BAIK
                                                </button>
                                                <button type="submit" name="kondisi" value="Rusak" class="bg-amber-50 text-amber-600 px-3 py-2 rounded-xl hover:bg-amber-600 hover:text-white transition-all text-[9px] font-black shadow-sm">
                                                    RUSAK
                                                </button>
                                                <button type="submit" name="kondisi" value="Hilang" class="bg-rose-50 text-rose-600 px-3 py-2 rounded-xl hover:bg-rose-600 hover:text-white transition-all text-[9px] font-black shadow-sm">
                                                    HILANG
                                                </button>
                                            </div>
                                        </form>
                                        @else
                                        <div class="flex items-center justify-center opacity-30 pointer-events-none scale-75">
                                            <svg class="w-6 h-6 text-emerald-600" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"></path></svg>
                                        </div>
                                        @endif
                                    </td>
                                </tr>
                                @empty
                                <tr>
                                    <td colspan="6" class="px-6 py-20 text-center">
                                        <div class="flex flex-col items-center">
                                            <div class="text-5xl mb-4 opacity-20">📂</div>
                                            <p class="text-gray-400 font-bold uppercase tracking-widest text-[10px]">Data tidak ditemukan</p>
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

    <style>
        .no-scrollbar::-webkit-scrollbar { display: none; }
        .no-scrollbar { -ms-overflow-style: none; scrollbar-width: none; }
    </style>
</x-app-layout>