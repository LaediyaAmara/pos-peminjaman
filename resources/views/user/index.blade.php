<x-app-layout>
    <div class="mb-8">
        <h3 class="text-3xl font-black text-gray-900 tracking-tight">👥 Manajemen Anggota</h3>
        <p class="text-gray-500 font-medium">Daftar siswa dan alamat email yang terdaftar di sistem.</p>
    </div>

    <div class="bg-white overflow-hidden shadow-sm sm:rounded-[2.5rem] border border-gray-100">
        <div class="p-10">
            <div class="overflow-x-auto">
                <table class="w-full text-left border-collapse">
                    <thead>
                        <tr class="text-[10px] font-black text-indigo-500 uppercase tracking-widest border-b border-indigo-50">
                            <th class="px-6 py-5">Nama Lengkap</th>
                            <th class="px-6 py-5">Alamat Email</th> {{-- Kolom Email --}}
                            <th class="px-6 py-5 text-center">Aksi</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-50">
                        @forelse($members as $m)
                        <tr class="hover:bg-indigo-50/30 transition-colors group">
                            {{-- Kolom Nama --}}
                            <td class="px-6 py-5">
                                <div class="flex items-center">
                                    <div class="w-10 h-10 bg-indigo-100 text-indigo-600 rounded-xl flex items-center justify-center font-bold mr-4 group-hover:bg-indigo-600 group-hover:text-white transition-all">
                                        {{ substr($m->NamaLengkap, 0, 1) }}
                                    </div>
                                    <span class="font-bold text-gray-800">{{ $m->NamaLengkap }}</span>
                                </div>
                            </td>

                            {{-- Kolom Email (Menampilkan email dari database) --}}
                            <td class="px-6 py-5">
                                <div class="flex items-center text-indigo-600 font-semibold text-sm tracking-tight">
                                    <svg class="w-4 h-4 mr-2.5 opacity-40" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"></path>
                                    </svg>
                                    {{ $m->email }} {{-- Ini mengambil data email asli dari registrasi --}}
                                </div>
                            </td>
                           
                            {{-- Kolom Aksi --}}
                            <td class="px-6 py-5">
                                <div class="flex justify-center">
                                    <form action="{{ route('user.destroy', $m->id) }}" method="POST" onsubmit="return confirm('Hapus member ini?')">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="bg-rose-50 text-rose-600 p-3 rounded-2xl hover:bg-rose-600 hover:text-white transition-all shadow-sm active:scale-95">
                                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path>
                                            </svg>
                                        </button>
                                    </form>
                                </div>
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="3" class="px-6 py-20 text-center text-gray-400 font-medium">Belum ada anggota terdaftar.</td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</x-app-layout>