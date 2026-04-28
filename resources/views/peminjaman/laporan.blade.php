<x-app-layout>
    <x-slot name="header">
        <div class="flex justify-between items-center">
            <h2 class="font-black text-2xl text-indigo-950 leading-tight uppercase tracking-tight">
                📊 Laporan <span class="text-indigo-600">Peminjaman</span>
            </h2>
            
        </div>
    </x-slot>

    {{-- Filter Laporan --}}
    <div class="max-w-7xl mx-auto sm:px-6 lg:px-8 mt-8">
        <div class="bg-white p-6 rounded-[2rem] shadow-sm border border-gray-100 print:hidden">
            {{-- Grid diubah ke 5 untuk menampung input Nama --}}
            <form action="{{ route('peminjaman.laporan') }}" method="GET" class="grid grid-cols-1 md:grid-cols-5 gap-4 items-end">
                
                {{-- 1. Cari Nama Peminjam --}}
                <div>
                    <label class="text-[10px] font-black uppercase text-indigo-400 ml-2">Cari Peminjam</label>
                    <input type="text" name="peminjam" value="{{ request('peminjam') }}" list="rekomendasi_nama"
                        placeholder="Nama siswa..." autocomplete="off"
                        class="w-full mt-1 rounded-xl border-gray-100 focus:ring-indigo-500 text-sm">
                    
                    <datalist id="rekomendasi_nama">
                        @foreach($peminjamans->pluck('user.NamaLengkap')->unique() as $nama)
                            <option value="{{ $nama }}">
                        @endforeach
                    </datalist>
                </div>

                {{-- 2. Mulai Tanggal --}}
                <div>
                    <label class="text-[10px] font-black uppercase text-indigo-400 ml-2">Mulai Tanggal</label>
                    <input type="date" name="tgl_mulai" value="{{ request('tgl_mulai') }}" 
                        class="w-full mt-1 rounded-xl border-gray-100 focus:ring-indigo-500 text-sm">
                </div>

                {{-- 3. Sampai Tanggal --}}
                <div>
                    <label class="text-[10px] font-black uppercase text-indigo-400 ml-2">Sampai Tanggal</label>
                    <input type="date" name="tgl_selesai" value="{{ request('tgl_selesai') }}" 
                        class="w-full mt-1 rounded-xl border-gray-100 focus:ring-indigo-500 text-sm">
                </div>

                {{-- 4. Status --}}
                <div>
                    <label class="text-[10px] font-black uppercase text-indigo-400 ml-2">Status</label>
                    <select name="status" class="w-full mt-1 rounded-xl border-gray-100 focus:ring-indigo-500 text-sm">
                        <option value="">Semua Status</option>
                        <option value="Dipinjam" {{ request('status') == 'Dipinjam' ? 'selected' : '' }}>Dipinjam</option>
                        <option value="Kembali" {{ request('status') == 'Kembali' ? 'selected' : '' }}>Kembali</option>
                    </select>
                </div>

                {{-- 5. Tombol Aksi --}}
                <div class="flex gap-2">
                    <button type="submit" class="flex-1 bg-indigo-600 text-white py-2.5 rounded-xl font-bold text-xs hover:bg-indigo-700 transition-all">
                        🔍 Filter
                    </button>
                    <a href="{{ route('peminjaman.laporan') }}" class="px-4 py-2.5 bg-gray-100 text-gray-500 rounded-xl font-bold text-xs hover:bg-gray-200 transition-all text-center">
                        🔄 Reset
                    </a>
                </div>
            </form>
        </div>
    </div>

    <div class="py-8 bg-gray-50">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            
            {{-- 1. WIDGET STATISTIK --}}
            <div class="grid grid-cols-1 md:grid-cols-4 gap-6 mb-8 print:hidden">
                <div class="bg-white p-6 rounded-[2rem] shadow-sm border border-gray-100">
                    <div class="text-gray-400 text-[10px] font-black uppercase tracking-widest mb-1">Total Transaksi</div>
                    <div class="text-2xl font-black text-indigo-900">{{ $peminjamans->count() }}</div>
                </div>
                <div class="bg-white p-6 rounded-[2rem] shadow-sm border border-gray-100">
                    <div class="text-amber-500 text-[10px] font-black uppercase tracking-widest mb-1">Masih Dipinjam</div>
                    <div class="text-2xl font-black text-amber-600">{{ $peminjamans->where('StatusPeminjaman', 'Dipinjam')->count() }}</div>
                </div>
                <div class="bg-white p-6 rounded-[2rem] shadow-sm border border-gray-100">
                    <div class="text-emerald-500 text-[10px] font-black uppercase tracking-widest mb-1">Sudah Kembali</div>
                    <div class="text-2xl font-black text-emerald-600">{{ $peminjamans->where('StatusPeminjaman', 'Kembali')->count() }}</div>
                </div>
               <div class="bg-white p-6 rounded-[2rem] shadow-sm border border-gray-100">
    <div class="text-rose-500 text-[10px] font-black uppercase tracking-widest mb-1">Total Denda</div>
    <div class="text-2xl font-black text-rose-600">
        {{-- Ganti kodingan lama dengan variabel baru ini --}}
        Rp {{ number_format($totalDenda, 0, ',', '.') }}
    </div>
</div>
            </div>

            {{-- 2. TABEL DATA --}}
            <div class="bg-white overflow-hidden shadow-xl sm:rounded-[3rem] border border-gray-100">
                <div class="p-4 md:p-10">
                    <div class="overflow-x-auto">
                        <table class="w-full text-left border-collapse">
                            <thead>
                                <tr class="text-indigo-800 uppercase text-[10px] font-black tracking-[0.2em] border-b border-indigo-50">
                                    <th class="px-4 py-5 text-center">No</th>
                                    <th class="px-4 py-5">Peminjam</th>
                                    <th class="px-4 py-5">Buku</th>
                                    <th class="px-4 py-5 text-center">Status</th>
                                    <th class="px-4 py-5 text-center">Kondisi</th>
                                    <th class="px-4 py-5 text-right">Denda</th>
                                </tr>
                            </thead>
                            <tbody class="divide-y divide-gray-50">
                                @forelse($peminjamans as $index => $p)
                                <tr class="hover:bg-gray-50/50 transition-colors">
                                    <td class="px-4 py-6 text-center text-xs font-bold text-gray-400">{{ $index + 1 }}</td>
                                    <td class="px-4 py-6">
                                        <div class="font-black text-indigo-950 text-sm">{{ $p->user->NamaLengkap }}</div>
                                        <div class="text-[10px] text-gray-400 font-bold uppercase">{{ $p->user->email }}</div>
                                    </td>
                                    <td class="px-4 py-6">
                                        <div class="font-bold text-gray-800 text-sm line-clamp-1">{{ $p->buku->Judul }}</div>
                                        <div class="text-[9px] text-indigo-400 font-black italic">Pinjam: {{ \Carbon\Carbon::parse($p->TanggalPeminjaman)->format('d M Y') }}</div>
                                    </td>
                                    <td class="px-4 py-6 text-center">
                                        <span class="px-3 py-1 rounded-full text-[9px] font-black uppercase tracking-widest
                                            {{ $p->StatusPeminjaman == 'Dipinjam' ? 'bg-amber-100 text-amber-700' : 'bg-emerald-500 text-white shadow-lg shadow-emerald-100' }}">
                                            {{ $p->StatusPeminjaman }}
                                        </span>
                                    </td>
                                    <td class="px-4 py-6 text-center text-xs font-bold">
                                        @if($p->StatusPeminjaman == 'Kembali')
                                            <span class="{{ $p->Kondisi == 'Baik' ? 'text-emerald-500' : ($p->Kondisi == 'Rusak' ? 'text-amber-500' : 'text-rose-500') }}">
                                                {{ $p->Kondisi }}
                                            </span>
                                        @else
                                            <span class="text-gray-300 italic font-medium">N/A</span>
                                        @endif
                                    </td>
                                    <td class="px-4 py-6 text-right">
                                        @if($p->denda)
                                            <div class="text-sm font-black text-rose-600">Rp {{ number_format($p->denda->JumlahDenda) }}</div>
                                            <div class="text-[9px] font-black uppercase {{ $p->denda->StatusPembayaran == 'Lunas' ? 'text-emerald-500' : 'text-rose-400' }}">
                                                {{ $p->denda->StatusPembayaran }}
                                            </div>
                                        @else
                                            <span class="text-gray-300 text-xs">-</span>
                                        @endif
                                    </td>
                                </tr>
                                @empty
                                <tr>
                                    <td colspan="6" class="px-6 py-20 text-center">
                                        <p class="text-gray-400 font-black uppercase tracking-widest text-xs">Data laporan tidak ditemukan.</p>
                                    </td>
                                </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>

            {{-- Footer Tanda Tangan (Hanya muncul saat print) --}}
            <div class="mt-12 hidden print:block">
                <div class="flex justify-between">
                    <div></div>
                    <div class="text-center">
                        <p class="text-sm font-bold">Dicetak pada: {{ now()->format('d F Y') }}</p>
                        <p class="text-sm mb-24 font-bold uppercase text-gray-700">Petugas Perpustakaan,</p>
                        <p class="text-sm font-black underline text-indigo-950">{{ Auth::user()->NamaLengkap }}</p>
                    </div>
                </div>
            </div>

        </div>
    </div>
</x-app-layout>