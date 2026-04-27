<x-app-layout>
    <x-slot name="header">
        <div class="flex justify-between items-center">
            <h2 class="font-bold text-2xl text-emerald-800 leading-tight">📚 Pinjaman Saya</h2>
            <a href="{{ route('peminjam.pinjam') }}" class="px-4 py-2 bg-emerald-600 text-white rounded-xl text-sm font-bold">
                + Pinjam Buku Lagi
            </a>
        </div>
    </x-slot>

    <div class="py-12 bg-gray-50">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-xl sm:rounded-3xl border border-gray-100">
                <div class="p-8">
                    <table class="w-full text-left border-collapse">
                        <thead>
                            <tr class="text-emerald-800 uppercase text-xs font-extrabold tracking-wider border-b border-emerald-50">
                                <th class="px-6 py-4">Buku</th>
                                <th class="px-6 py-4">Tanggal Pinjam</th>
                                <th class="px-6 py-4">Deadline Kembali</th>
                                <th class="px-6 py-4 text-center">Status</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-gray-50">
                            @forelse($pinjamans as $pinjam)
                            <tr class="hover:bg-gray-50 transition-colors">
                                <td class="px-6 py-4 font-bold text-gray-800">{{ $pinjam->buku->Judul }}</td>
                                <td class="px-6 py-4 text-gray-600">{{ \Carbon\Carbon::parse($pinjam->TanggalPeminjaman)->format('d M Y') }}</td>
                                <td class="px-6 py-4 text-rose-600 font-medium">{{ \Carbon\Carbon::parse($pinjam->TanggalPengembalian)->format('d M Y') }}</td>
                                <td class="px-6 py-4 text-center">
                                    <span class="px-3 py-1 rounded-full text-xs font-bold uppercase
                                        {{ $pinjam->StatusPeminjaman == 'Dipinjam' ? 'bg-amber-100 text-amber-700' : 'bg-emerald-100 text-emerald-700' }}">
                                        {{ $pinjam->StatusPeminjaman }}
                                    </span>
                                </td>
                            </tr>
                            @empty
                            <tr>
                                <td colspan="4" class="px-6 py-12 text-center text-gray-400">
                                    Kamu belum meminjam buku apapun. 😊
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
