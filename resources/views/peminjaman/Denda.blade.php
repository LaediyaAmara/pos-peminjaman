<x-app-layout>
    <x-slot name="header">
        <h2 class="font-bold text-2xl text-rose-800 leading-tight">💰 Manajemen Denda</h2>
    </x-slot>

    <div class="py-12 bg-gray-50">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-xl sm:rounded-[2.5rem] border border-gray-100 p-8">
                <table class="w-full text-left">
                    <thead>
                        <tr class="text-rose-800 uppercase text-[10px] font-black tracking-widest border-b">
                            <th class="px-4 py-4">Peminjam</th>
                            <th class="px-4 py-4">Buku</th>
                            <th class="px-4 py-4">Jumlah Denda</th>
                            <th class="px-4 py-4">Keterangan</th>
                            <th class="px-4 py-4 text-center">Status</th>
                            <th class="px-4 py-4 text-center">Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($dendas as $d)
                        <tr class="border-b border-gray-50">
                            <td class="px-4 py-4 font-bold text-sm">{{ $d->peminjaman->user->NamaLengkap }}</td>
                            <td class="px-4 py-4 text-sm">{{ $d->peminjaman->buku->Judul }}</td>
                            <td class="px-4 py-4 font-black text-rose-600">Rp {{ number_format($d->JumlahDenda) }}</td>
                            <td class="px-4 py-4 text-[10px] text-gray-500 italic">{{ $d->Keterangan }}</td>
                            <td class="px-4 py-4 text-center">
                                <span class="px-3 py-1 rounded-full text-[9px] font-bold uppercase {{ $d->StatusPembayaran == 'Lunas' ? 'bg-emerald-100 text-emerald-600' : 'bg-rose-100 text-rose-600' }}">
                                    {{ $d->StatusPembayaran }}
                                </span>
                            </td>
                            <td class="px-4 py-4 text-center">
                                @if($d->StatusPembayaran == 'Belum Lunas')
                                <form action="{{ route('denda.bayar', $d->DendaID) }}" method="POST">
                                    @csrf @method('PUT')
                                    <button type="submit" class="bg-emerald-500 text-white px-4 py-1.5 rounded-xl text-[10px] font-bold hover:bg-emerald-600 shadow-md transition-all">
                                        Set Lunas
                                    </button>
                                </form>
                                @else
                                <span class="text-emerald-500">✔️ Selesai</span>
                                @endif
                            </td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</x-app-layout>