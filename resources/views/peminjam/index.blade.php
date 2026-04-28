<x-app-layout>
    <x-slot name="header">
        <div class="flex justify-between items-center">
            <h2 class="font-black text-2xl text-emerald-950 leading-tight italic">📚 Pinjaman <span class="text-emerald-600">Saya</span></h2>
            <a href="{{ route('peminjam.pinjam') }}" class="px-5 py-2.5 bg-emerald-600 text-white rounded-2xl text-xs font-black uppercase tracking-widest shadow-lg shadow-emerald-200 hover:bg-emerald-700 transition-all active:scale-95">
                + Pinjam Buku Lagi
            </a>
        </div>
    </x-slot>

    <div class="py-12 bg-gray-50">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            
            {{-- Tombol Kembali ke Dashboard --}}
            <div class="mb-8">
                <a href="{{ route('dashboard') }}" class="inline-flex items-center gap-3 bg-white border border-gray-100 px-5 py-2.5 rounded-2xl shadow-sm hover:shadow-md transition-all group">
                    <span class="w-7 h-7 bg-emerald-100 text-emerald-600 rounded-xl flex items-center justify-center group-hover:bg-emerald-600 group-hover:text-white transition-all">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"></path>
                        </svg>
                    </span>
                    <span class="text-xs font-black uppercase tracking-widest text-gray-500 group-hover:text-emerald-700">Beranda</span>
                </a>
            </div>

            <div class="bg-white overflow-hidden shadow-xl sm:rounded-[3rem] border border-gray-100">
                <div class="p-4 md:p-10">
                    <div class="overflow-x-auto no-scrollbar">
                        <table class="w-full text-left border-collapse">
                            <thead>
                                <tr class="text-emerald-800 uppercase text-[10px] font-black tracking-[0.2em] border-b border-emerald-50">
                                    <th class="px-6 py-5">Buku</th>
                                    <th class="px-6 py-5">Waktu Pinjam</th>
                                    <th class="px-6 py-5">Waktu Kembali</th>
                                    <th class="px-6 py-5 text-center">Status & Kondisi</th>
                                </tr>
                            </thead>
                            <tbody class="divide-y divide-gray-50">
                                @forelse($pinjamans as $pinjam)
                                <tr class="hover:bg-emerald-50/30 transition-colors group">
                                    <td class="px-6 py-6">
                                        <div class="font-black text-emerald-950 text-sm leading-tight">{{ $pinjam->buku->Judul }}</div>
                                        <div class="text-[10px] text-gray-400 font-bold mt-1 uppercase tracking-tighter">Kategori: {{ $pinjam->buku->kategori->NamaKategori ?? 'Umum' }}</div>
                                    </td>
                                    <td class="px-6 py-6">
                                        <div class="text-sm font-bold text-gray-800">{{ \Carbon\Carbon::parse($pinjam->TanggalPeminjaman)->format('d M Y') }}</div>
                                        <div class="text-[10px] text-emerald-500 font-black italic">{{ \Carbon\Carbon::parse($pinjam->TanggalPeminjaman)->format('H:i') }} WIB</div>
                                    </td>
                                    <td class="px-6 py-6">
                                        @if($pinjam->StatusPeminjaman == 'Kembali')
                                            <div class="text-sm font-bold text-emerald-600">{{ \Carbon\Carbon::parse($pinjam->TanggalPengembalian)->format('d M Y') }}</div>
                                            <div class="text-[10px] text-emerald-400 font-black italic">{{ \Carbon\Carbon::parse($pinjam->TanggalPengembalian)->format('H:i') }} WIB</div>
                                        @else
                                            <div class="text-sm font-bold text-rose-600">{{ \Carbon\Carbon::parse($pinjam->TanggalPengembalian)->format('d M Y') }}</div>
                                            <div class="text-[10px] text-rose-400 font-black uppercase tracking-widest">Deadline</div>
                                        @endif
                                    </td>
                                    <td class="px-6 py-6 text-center">
                                        <div class="flex flex-col items-center gap-2">
                                            <span class="px-4 py-1.5 rounded-xl text-[9px] font-black uppercase tracking-[0.15em] shadow-sm
                                                {{ $pinjam->StatusPeminjaman == 'Dipinjam' ? 'bg-amber-100 text-amber-700 border border-amber-200' : 'bg-emerald-600 text-white' }}">
                                                {{ $pinjam->StatusPeminjaman }}
                                            </span>
                                            
                                            {{-- Tambahan Kondisi Saat Dikembalikan --}}
                                            @if($pinjam->StatusPeminjaman == 'Kembali')
                                                <span class="text-[10px] font-black uppercase {{ $pinjam->Kondisi == 'Baik' ? 'text-emerald-500' : ($pinjam->Kondisi == 'Rusak' ? 'text-amber-500' : 'text-rose-500') }}">
                                                    Kondisi: {{ $pinjam->Kondisi ?? 'Baik' }}
                                                </span>
                                            @endif
                                        </div>
                                    </td>
                                </tr>
                                @empty
                                <tr>
                                    <td colspan="4" class="px-6 py-20 text-center">
                                        <div class="flex flex-col items-center">
                                            <div class="text-6xl mb-4 opacity-20 italic">📚</div>
                                            <p class="text-gray-400 font-black uppercase tracking-widest text-xs italic">Kamu belum meminjam buku apapun.</p>
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
{{-- Letakkan di atas tabel pinjaman siswa --}}
@php
    $dendaBelumLunas = $pinjamans->where('denda.StatusPembayaran', 'Belum Lunas');
@endphp

@if($dendaBelumLunas->count() > 0)
    <div class="mb-6 p-4 bg-rose-50 border-l-4 border-rose-500 rounded-r-2xl shadow-sm">
        <div class="flex items-center">
            <span class="text-2xl mr-3">💸</span>
            <div>
                <h3 class="text-rose-800 font-black text-xs uppercase tracking-widest">Tagihan Denda</h3>
                <p class="text-rose-600 text-sm font-bold">
                    Kamu memiliki {{ $dendaBelumLunas->count() }} denda yang belum lunas. Total: 
                    <span class="underline">Rp{{ number_format($dendaBelumLunas->sum('denda.JumlahDenda')) }}</span>
                </p>
            </div>
        </div>
    </div>
@endif