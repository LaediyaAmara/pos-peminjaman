<x-app-layout>
    <x-slot name="header">
        <div class="flex items-center">
            <h2 class="font-bold text-2xl text-indigo-900 leading-tight uppercase tracking-tight">
                📊 Laporan Peminjaman Buku
            </h2>
        </div>
    </x-slot>

    <div class="py-12 bg-gray-50">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            
            <div class="bg-white overflow-hidden shadow-xl sm:rounded-3xl border border-gray-100">
                <div class="p-8">
                    <table class="w-full text-left border-collapse border border-gray-300">
                        <thead>
                            <tr class="bg-indigo-50 text-indigo-900 uppercase text-xs font-bold border-b-2 border-gray-300">
                                <th class="border border-gray-300 px-4 py-4 text-center w-12">No</th>
                                <th class="border border-gray-300 px-4 py-4">Informasi Peminjam</th>
                                <th class="border border-gray-300 px-4 py-4">Detail Buku</th>
                                <th class="border border-gray-300 px-4 py-4 text-center">Tgl Pinjam</th>
                                <th class="border border-gray-300 px-4 py-4 text-center">Tgl Kembali</th>
                                <th class="border border-gray-300 px-4 py-4 text-center">Status & Kondisi</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-gray-200">
                            @forelse($peminjamans as $index => $p)
                            <tr class="text-sm hover:bg-gray-50 transition-colors">
                                <td class="border border-gray-300 px-4 py-4 text-center font-medium">{{ $index + 1 }}</td>
                                <td class="border border-gray-300 px-4 py-4">
                                    <div class="font-bold text-gray-900">{{ $p->user->NamaLengkap }}</div>
                                    <div class="text-xs text-gray-500 font-mono italic">{{ $p->user->email }}</div>
                                </td>
                                <td class="border border-gray-300 px-4 py-4">
                                    <span class="font-semibold text-indigo-700">{{ $p->buku->Judul }}</span>
                                </td>
                                <td class="border border-gray-300 px-4 py-4 text-center whitespace-nowrap">
                                    {{ \Carbon\Carbon::parse($p->TanggalPeminjaman)->format('d/m/Y') }}
                                    <div class="text-[10px] text-gray-400 font-medium italic">{{ \Carbon\Carbon::parse($p->TanggalPeminjaman)->format('H:i') }} WIB</div>
                                </td>
                                <td class="border border-gray-300 px-4 py-4 text-center whitespace-nowrap">
                                    @if($p->StatusPeminjaman == 'Kembali' && $p->TanggalPengembalian)
                                        <div class="font-bold text-emerald-600">
                                            {{ \Carbon\Carbon::parse($p->TanggalPengembalian)->format('d/m/Y') }}
                                        </div>
                                        <div class="text-[10px] text-emerald-500 font-medium italic">
                                            {{ \Carbon\Carbon::parse($p->TanggalPengembalian)->format('H:i') }} WIB
                                        </div>
                                    @else
                                        <span class="text-gray-400 italic">Belum Kembali</span>
                                    @endif
                                </td>
                                <td class="border border-gray-300 px-4 py-4 text-center">
                                    @php
                                        $deadline = \Carbon\Carbon::parse($p->TanggalPengembalian); 
                                        $isTerlambat = now()->gt($deadline) && $p->StatusPeminjaman == 'Dipinjam';
                                    @endphp

                                    <div class="flex flex-col items-center gap-1">
                                        @if($isTerlambat)
                                            <span class="text-red-600 font-black text-[10px] uppercase tracking-tighter">⚠️ TERLAMBAT</span>
                                        @else
                                            <span class="px-3 py-0.5 rounded-full text-[9px] font-black uppercase tracking-wider
                                                {{ $p->StatusPeminjaman == 'Dipinjam' ? 'bg-amber-100 text-amber-700' : 'bg-emerald-100 text-emerald-700' }}">
                                                {{ $p->StatusPeminjaman }}
                                            </span>
                                        @endif

                                        @if($p->StatusPeminjaman == 'Kembali')
                                            <span class="text-[10px] font-bold uppercase {{ $p->Kondisi == 'Baik' ? 'text-emerald-600' : ($p->Kondisi == 'Rusak' ? 'text-amber-600' : 'text-rose-600') }}">
                                                Kondisi: {{ $p->Kondisi }}
                                            </span>
                                        @endif
                                    </div>
                                </td>
                            </tr>
                            @empty
                            <tr>
                                <td colspan="6" class="border border-gray-300 px-4 py-16 text-center text-gray-400 italic font-medium">
                                    🚫 Tidak ditemukan data transaksi peminjaman.
                                </td>
                            </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>