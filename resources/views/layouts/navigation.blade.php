<x-app-layout>
    <x-slot name="header">
        <div class="flex justify-between items-center py-2">
            <div>
                <h2 class="font-black text-3xl text-emerald-950 tracking-tight leading-tight">
                    📚 Pinjaman <span class="text-emerald-600 underline decoration-emerald-200">Saya</span>
                </h2>
                <p class="text-xs text-gray-500 font-bold uppercase tracking-[0.2em] mt-1">Riwayat & Koleksi Buku Aktif</p>
            </div>
            <a href="{{ route('peminjam.pinjam') }}" class="group flex items-center gap-3 px-6 py-3 bg-emerald-600 text-white rounded-[1.5rem] text-xs font-black uppercase tracking-widest shadow-xl shadow-emerald-100 hover:bg-emerald-700 transition-all active:scale-95">
                <span class="text-lg group-hover:rotate-90 transition-transform">+</span>
                Pinjam Buku
            </a>
        </div>
    </x-slot>

    <div class="py-10 bg-gray-50/50 min-h-screen">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            
            {{-- Alert Denda dengan Desain Lebih Modern --}}
            @php
                // Menggunakan flatMap karena denda biasanya relasi one-to-one atau one-to-many
                $totalDenda = $pinjamans->sum(fn($p) => $p->denda && $p->denda->StatusPembayaran == 'Belum Lunas' ? $p->denda->JumlahDenda : 0);
                $jumlahDenda = $pinjamans->filter(fn($p) => $p->denda && $p->denda->StatusPembayaran == 'Belum Lunas')->count();
            @endphp

            @if($jumlahDenda > 0)
                <div class="mb-8 bg-white border-l-[6px] border-rose-500 p-6 rounded-[2rem] shadow-sm flex flex-col md:flex-row md:items-center justify-between gap-4">
                    <div class="flex items-center">
                        <div class="w-12 h-12 bg-rose-100 text-rose-600 rounded-2xl flex items-center justify-center text-2xl mr-4">
                            💸
                        </div>
                        <div>
                            <h3 class="text-rose-900 font-black text-sm uppercase tracking-widest">Tagihan Denda Terdeteksi</h3>
                            <p class="text-gray-500 text-xs font-bold mt-0.5">Segera lakukan pelunasan ke petugas perpustakaan.</p>
                        </div>
                    </div>
                    <div class="bg-rose-50 px-6 py-3 rounded-2xl border border-rose-100 text-center">
                        <span class="block text-[10px] font-black text-rose-400 uppercase tracking-tighter">Total Tagihan</span>
                        <span class="text-xl font-black text-rose-600 italic">Rp{{ number_format($totalDenda, 0, ',', '.') }}</span>
                    </div>
                </div>
            @endif

            {{-- Navigasi Beranda --}}
            <div class="mb-6">
                <a href="{{ route('dashboard') }}" class="inline-flex items-center gap-3 text-gray-400 hover:text-emerald-600 transition-colors group">
                    <span class="w-8 h-8 bg-white rounded-xl shadow-sm flex items-center justify-center group-hover:bg-emerald-50 transition-all">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"></path></svg>
                    </span>
                    <span class="text-[10px] font-black uppercase tracking-[0.2em]">Kembali ke Dashboard</span>
                </a>
            </div>

            {{-- Table Card --}}
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-[3rem] border border-gray-100">
                <div class="overflow-x-auto">
                    <table class="w-full text-left border-collapse">
                        <thead>
                            <tr class="bg-gray-50/50 text-emerald-900 uppercase text-[10px] font-black tracking-[0.2em] border-b border-gray-100">
                                <th class="px-8 py-6">Detail Buku</th>
                                <th class="px-8 py-6">Peminjaman</th>
                                <th class="px-8 py-6">Pengembalian</th>
                                <th class="px-8 py-6 text-center">Status & Kondisi</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-gray-50">
                            @forelse($pinjamans as $pinjam)
                            <tr class="hover:bg-emerald-50/20 transition-colors group">
                                <td class="px-8 py-7">
                                    <div class="flex items-center gap-4">
                                        <div class="w-10 h-10 bg-emerald-100 text-emerald-600 rounded-xl flex items-center justify-center font-black">
                                            {{ substr($pinjam->buku->Judul, 0, 1) }}
                                        </div>
                                        <div>
                                            <div class="font-black text-emerald-950 text-sm leading-tight group-hover:text-emerald-600 transition-colors">{{ $pinjam->buku->Judul }}</div>
                                            <div class="inline-block px-2 py-0.5 bg-gray-100 text-gray-400 text-[9px] font-black mt-1 uppercase rounded-md">
                                                {{ $pinjam->buku->kategori->NamaKategori ?? 'Umum' }}
                                            </div>
                                        </div>
                                    </div>
                                </td>
                                <td class="px-8 py-7">
                                    <div class="text-sm font-bold text-gray-800 tracking-tight">{{ \Carbon\Carbon::parse($pinjam->TanggalPeminjaman)->translatedFormat('d M Y') }}</div>
                                    <div class="text-[10px] text-emerald-500 font-black italic mt-1">{{ \Carbon\Carbon::parse($pinjam->TanggalPeminjaman)->format('H:i') }} WIB</div>
                                </td>
                                <td class="px-8 py-7">
                                    @if($pinjam->StatusPeminjaman == 'Kembali')
                                        <div class="text-sm font-bold text-emerald-600 tracking-tight">{{ \Carbon\Carbon::parse($pinjam->TanggalPengembalian)->translatedFormat('d M Y') }}</div>
                                        <div class="text-[10px] text-emerald-400 font-black italic mt-1 leading-none italic">Selesai</div>
                                    @else
                                        <div class="text-sm font-bold text-rose-600 tracking-tight">{{ \Carbon\Carbon::parse($pinjam->TanggalPengembalian)->translatedFormat('d M Y') }}</div>
                                        <div class="text-[9px] font-black text-rose-400 uppercase tracking-widest mt-1">⚠️ Jatuh Tempo</div>
                                    @endif
                                </td>
                                <td class="px-8 py-7">
                                    <div class="flex flex-col items-center gap-2">
                                        <span class="px-4 py-1.5 rounded-full text-[9px] font-black uppercase tracking-widest
                                            {{ $pinjam->StatusPeminjaman == 'Dipinjam' ? 'bg-amber-100 text-amber-600 border border-amber-200' : 'bg-emerald-600 text-white shadow-lg shadow-emerald-100' }}">
                                            {{ $pinjam->StatusPeminjaman }}
                                        </span>
                                        
                                        @if($pinjam->StatusPeminjaman == 'Kembali')
                                            <div class="flex items-center gap-1.5">
                                                <span class="w-1.5 h-1.5 rounded-full {{ $pinjam->Kondisi == 'Baik' ? 'bg-emerald-500' : 'bg-amber-500' }}"></span>
                                                <span class="text-[10px] font-bold text-gray-400 uppercase">
                                                    {{ $pinjam->Kondisi ?? 'Baik' }}
                                                </span>
                                            </div>
                                        @endif
                                    </div>
                                </td>
                            </tr>
                            @empty
                            <tr>
                                <td colspan="4" class="px-8 py-32 text-center">
                                    <div class="flex flex-col items-center">
                                        <span class="text-5xl grayscale opacity-30 mb-4">📖</span>
                                        <h4 class="text-gray-400 font-black uppercase tracking-[0.3em] text-xs">Rak Pinjaman Kosong</h4>
                                        <p class="text-gray-300 text-[10px] mt-2 font-bold italic">Mulai jelajahi ilmu dengan meminjam buku pertama kamu!</p>
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
</x-app-layout>