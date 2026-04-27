<x-app-layout>
    <x-slot name="header">
        <div class="flex justify-between items-center">
            <h2 class="font-bold text-2xl text-indigo-900 leading-tight">📊 Laporan Peminjaman Buku</h2>
            <button onclick="window.print()" class="bg-indigo-600 text-white px-6 py-2.5 rounded-xl font-bold shadow-lg hover:bg-indigo-700 transition-all print:hidden flex items-center space-x-2">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 17h2a2 2 0 002-2v-4a2 2 0 00-2-2H5a2 2 0 00-2 2v4a2 2 0 002 2h2m2 4h6a2 2 0 002-2v-4a2 2 0 00-2-2H9a2 2 0 00-2 2v4a2 2 0 002 2zm8-12V5a2 2 0 00-2-2H9a2 2 0 00-2 2v4h10z"></path></svg>
                <span>Cetak Laporan</span>
            </button>
        </div>
    </x-slot>

    <div class="py-12 bg-gray-50 print:bg-white print:py-0">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            
            {{-- Header Khusus Saat Dicetak --}}
            <div class="hidden print:block text-center mb-8 border-b-4 border-double border-black pb-6">
                <h1 class="text-4xl font-black uppercase tracking-widest">LAPORAN PERPUSTAKAAN DIGITAL</h1>
                <p class="text-lg font-semibold mt-2">Data Transaksi Peminjaman dan Pengembalian Buku</p>
                <div class="flex justify-between mt-6 text-sm italic font-medium">
                    <span>Dicetak oleh: {{ auth()->user()->NamaLengkap }}</span>
                    <span>Tanggal Cetak: {{ now()->format('d F Y H:i') }}</span>
                </div>
            </div>

            <div class="bg-white overflow-hidden shadow-xl sm:rounded-3xl border border-gray-100 print:shadow-none print:border-none">
                <div class="p-8 print:p-0">
                    <table class="w-full text-left border-collapse border border-gray-300">
                        <thead>
                            <tr class="bg-indigo-50 text-indigo-900 uppercase text-xs font-bold border-b-2 border-gray-300">
                                <th class="border border-gray-300 px-4 py-4 text-center w-12">No</th>
                                <th class="border border-gray-300 px-4 py-4">Informasi Peminjam</th>
                                <th class="border border-gray-300 px-4 py-4">Detail Buku</th>
                                <th class="border border-gray-300 px-4 py-4 text-center">Tgl Pinjam</th>
                                <th class="border border-gray-300 px-4 py-4 text-center">Deadline</th>
                                <th class="border border-gray-300 px-4 py-4 text-center">Status Transaksi</th>
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
                                <td class="border border-gray-300 px-4 py-4 text-center">
                                    {{ \Carbon\Carbon::parse($p->TanggalPeminjaman)->format('d/m/Y') }}
                                </td>
                                <td class="border border-gray-300 px-4 py-4 text-center">
                                    {{ \Carbon\Carbon::parse($p->TanggalPengembalian)->format('d/m/Y') }}
                                </td>
                                <td class="border border-gray-300 px-4 py-4 text-center">
                                    @php
                                        $deadline = \Carbon\Carbon::parse($p->TanggalPengembalian);
                                        $isTerlambat = now()->gt($deadline) && $p->StatusPeminjaman == 'Dipinjam';
                                    @endphp

                                    @if($isTerlambat)
                                        <div class="flex flex-col items-center">
                                            <span class="text-red-600 font-black text-xs uppercase tracking-tighter">⚠️ TERLAMBAT</span>
                                            <small class="text-[10px] text-red-400 italic">Harus segera dikembalikan</small>
                                        </div>
                                    @else
                                        <span class="px-3 py-1 rounded-full text-[10px] font-black uppercase tracking-wider
                                            {{ $p->StatusPeminjaman == 'Dipinjam' ? 'bg-amber-100 text-amber-700' : 'bg-emerald-100 text-emerald-700' }}">
                                            {{ $p->StatusPeminjaman }}
                                        </span>
                                    @endif
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

                    {{-- Footer Tanda Tangan (Hanya muncul saat diprint) --}}
                    <div class="hidden print:grid grid-cols-2 mt-16 text-gray-800">
                        <div></div>
                        <div class="text-center">
                            <p class="mb-1 text-sm">Bandung, {{ now()->format('d F Y') }}</p>
                            <p class="font-semibold">Kepala Perpustakaan,</p>
                            <div class="h-24"></div> {{-- Ruang untuk tanda tangan --}}
                            <p class="font-bold underline text-lg">( {{ auth()->user()->NamaLengkap }} )</p>
                            <p class="text-xs uppercase tracking-widest text-gray-500">NIP. 19820311 200501 1 002</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <style>
        @media print {
            /* Sembunyikan Navigasi, Header, dan Tombol */
            nav, button, header, .header-shadow {
                display: none !important;
            }
            /* Reset Background & Padding */
            body, .py-12, .bg-gray-50 {
                background: white !important;
                padding-top: 0 !important;
            }
            .shadow-xl {
                box-shadow: none !important;
            }
            /* Optimasi Tabel */
            table {
                width: 100% !important;
                border: 2px solid black !important;
                color: black !important;
            }
            th {
                background-color: #f3f4f6 !important; /* Abu-abu terang */
                color: black !important;
                border: 1px solid black !important;
            }
            td {
                border: 1px solid black !important;
                color: black !important;
            }
            /* Paksa browser mencetak warna */
            * {
                -webkit-print-color-adjust: exact !important;
                print-color-adjust: exact !important;
            }
        }
    </style>
</x-app-layout>