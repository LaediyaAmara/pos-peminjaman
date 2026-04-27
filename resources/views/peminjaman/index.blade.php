<x-app-layout>
    <x-slot name="header">
        <h2 class="font-bold text-2xl text-indigo-800 leading-tight">📋 Log Peminjaman Buku</h2>
    </x-slot>

    <div class="py-12 bg-gray-50">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            
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
                <div class="p-8">
                    @if(session('success'))
                        <div class="mb-6 p-4 bg-emerald-100 text-emerald-700 rounded-2xl border border-emerald-200 font-bold text-sm">
                            ✅ {{ session('success') }}
                        </div>
                    @endif

                    <div class="overflow-x-auto">
                        <table class="w-full text-left">
                            <thead>
                                <tr class="text-indigo-800 uppercase text-[10px] font-black tracking-widest border-b border-indigo-50">
                                    <th class="px-6 py-5">Peminjam</th>
                                    <th class="px-6 py-5">Informasi Buku</th>
                                    <th class="px-6 py-5 text-center">Waktu Pinjam</th>
                                    <th class="px-6 py-5 text-center">Status & Kondisi</th>
                                    <th class="px-6 py-5 text-center">Aksi Pengembalian</th>
                                </tr>
                            </thead>
                            <tbody class="divide-y divide-gray-50">
                                @forelse($peminjamans as $p)
                                <tr class="hover:bg-indigo-50/30 transition-colors group">
                                    <td class="px-6 py-5">
                                        <div class="font-bold text-gray-900">{{ $p->user->NamaLengkap }}</div>
                                        <div class="text-[10px] text-gray-400 font-black uppercase tracking-tighter">{{ $p->user->email }}</div>
                                    </td>
                                    <td class="px-6 py-5">
                                        <div class="text-indigo-600 font-bold">{{ $p->buku->Judul }}</div>
                                        <div class="text-[10px] text-gray-400 italic">ID: {{ $p->BukuID }}</div>
                                    </td>
                                    <td class="px-6 py-5 text-center">
                                        <span class="text-xs text-gray-500 font-medium">{{ \Carbon\Carbon::parse($p->TanggalPeminjaman)->format('d M Y') }}</span>
                                    </td>
                                    <td class="px-6 py-5 text-center">
                                        <div class="flex flex-col items-center gap-1">
                                            <span class="px-3 py-1 rounded-full text-[9px] font-black uppercase tracking-widest
                                                {{ $p->StatusPeminjaman == 'Dipinjam' ? 'bg-amber-100 text-amber-600 border border-amber-200' : 'bg-emerald-100 text-emerald-600 border border-emerald-200' }}">
                                                {{ $p->StatusPeminjaman }}
                                            </span>
                                            @if($p->StatusPeminjaman == 'Kembali')
                                                <span class="text-[10px] font-bold uppercase {{ $p->Kondisi == 'Baik' ? 'text-emerald-500' : ($p->Kondisi == 'Rusak' ? 'text-amber-500' : 'text-rose-500') }}">
                                                    Kondisi: {{ $p->Kondisi }}
                                                </span>
                                            @endif
                                        </div>
                                    </td>
                                    <td class="px-6 py-5">
                                        @if($p->StatusPeminjaman == 'Dipinjam')
                                        <form action="{{ route('peminjaman.kembalikan', $p->PeminjamanID) }}" method="POST">
                                            @csrf 
                                            @method('PUT')
                                            <div class="flex items-center justify-center gap-2">
                                                <button type="submit" name="kondisi" value="Baik" class="group/btn bg-emerald-50 text-emerald-600 p-2 rounded-xl hover:bg-emerald-600 hover:text-white transition-all shadow-sm" title="Kembali Baik">
                                                    <span class="text-xs font-black px-1">BAIK</span>
                                                </button>
                                                <button type="submit" name="kondisi" value="Rusak" class="group/btn bg-amber-50 text-amber-600 p-2 rounded-xl hover:bg-amber-600 hover:text-white transition-all shadow-sm" title="Kembali Rusak">
                                                    <span class="text-xs font-black px-1">RUSAK</span>
                                                </button>
                                                <button type="submit" name="kondisi" value="Hilang" class="group/btn bg-rose-50 text-rose-600 p-2 rounded-xl hover:bg-rose-600 hover:text-white transition-all shadow-sm" title="Buku Hilang">
                                                    <span class="text-xs font-black px-1">HILANG</span>
                                                </button>
                                            </div>
                                        </form>
                                        @else
                                        <div class="flex items-center justify-center text-gray-300">
                                            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path></svg>
                                            <span class="text-[10px] font-black uppercase ml-1">Dikembalikan</span>
                                        </div>
                                        @endif
                                    </td>
                                </tr>
                                @empty
                                <tr>
                                    <td colspan="5" class="px-6 py-20 text-center">
                                        <div class="flex flex-col items-center">
                                            <div class="text-5xl mb-4 opacity-20">📂</div>
                                            <p class="text-gray-400 font-bold uppercase tracking-widest text-xs">Data tidak ditemukan</p>
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