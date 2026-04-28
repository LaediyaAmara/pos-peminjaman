<x-app-layout>
    <x-slot name="header">
        <div class="flex justify-between items-center">
            <div class="flex items-center space-x-4">
                <a href="{{ route('dashboard') }}" class="p-2 bg-white border border-gray-200 rounded-xl text-gray-400 hover:text-amber-600 transition-all shadow-sm">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"></path></svg>
                </a>
                <h2 class="font-bold text-2xl text-amber-800 leading-tight">🏷️ Kelola Kategori Buku</h2>
            </div>
            
            
        </div>
    </x-slot>
<button onclick="openAddModal()" class="inline-flex items-center px-5 py-2.5 bg-amber-500 hover:bg-amber-600 text-white text-sm font-medium rounded-xl shadow-lg shadow-amber-100 transition-all">
                + Tambah Kategori
            </button>
    <div class="py-12 bg-gray-50">
        <div class="max-w-4xl mx-auto sm:px-6 lg:px-8">
            {{-- Notifikasi Sukses --}}
            @if(session('success'))
                <div class="mb-4 p-4 bg-emerald-100 text-emerald-700 rounded-2xl border border-emerald-200">
                    {{ session('success') }}
                </div>
            @endif

            <div class="bg-white overflow-hidden shadow-xl sm:rounded-3xl border border-gray-100">
                <table class="w-full text-sm text-left">
                    <thead class="bg-amber-50 text-amber-800 uppercase font-bold">
                        <tr>
                            <th class="px-6 py-4 w-20">ID</th>
                            <th class="px-6 py-4">Nama Kategori</th>
                            <th class="px-6 py-4 text-center">Aksi</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-100">
                        @forelse($kategoris as $kat)
                        <tr class="hover:bg-amber-50/30 transition-colors text-gray-700">
                            <td class="px-6 py-4 font-mono text-gray-400">{{ $kat->KategoriID }}</td>
                            <td class="px-6 py-4 font-bold">{{ $kat->NamaKategori }}</td>
                            <td class="px-6 py-4 flex justify-center space-x-4">
                                {{-- Tombol Edit (Memanggil JavaScript) --}}
                                <button onclick="openEditModal('{{ $kat->KategoriID }}', '{{ $kat->NamaKategori }}')" 
                                        class="text-amber-600 hover:text-amber-800 font-bold">
                                    Edit
                                </button>

                                <form action="{{ route('kategori.destroy', $kat->KategoriID) }}" method="POST" onsubmit="return confirm('Hapus kategori ini juga akan berdampak pada relasi buku. Yakin?')">
                                    @csrf @method('DELETE')
                                    <button class="text-rose-600 hover:text-rose-800 font-medium italic">Hapus</button>
                                </form>
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="3" class="px-6 py-10 text-center text-gray-400 font-medium">Belum ada kategori yang terdaftar.</td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    {{-- MODAL FORM (Digunakan untuk Tambah & Edit) --}}
    <div id="modalKategori" class="hidden fixed inset-0 bg-gray-900/60 backdrop-blur-sm flex items-center justify-center z-50">
        <div class="bg-white p-8 rounded-3xl shadow-2xl w-96 transform transition-all">
            <h3 id="modalTitle" class="text-xl font-bold mb-6 text-gray-800">Tambah Kategori Baru</h3>
            
            <form id="formKategori" action="{{ route('kategori.store') }}" method="POST">
                @csrf
                <div id="methodField"></div> {{-- Tempat untuk @method('PUT') saat edit --}}
                
                <div class="mb-6">
                    <label class="block text-xs font-bold uppercase text-gray-400 mb-2">Nama Kategori</label>
                    <input type="text" id="inputNama" name="NamaKategori" 
                           class="w-full rounded-xl border-gray-200 focus:ring-amber-500 focus:border-amber-500 shadow-sm" 
                           placeholder="Misal: Novel, Sejarah..." required>
                </div>

                <div class="flex justify-end items-center space-x-4">
                    <button type="button" onclick="closeModal()" class="text-gray-400 font-bold hover:text-gray-600">Batal</button>
                    <button type="submit" class="px-6 py-2.5 bg-amber-500 text-white rounded-xl font-bold shadow-lg shadow-amber-100 hover:bg-amber-600 active:scale-95 transition-all">
                        Simpan Data
                    </button>
                </div>
            </form>
        </div>
    </div>

    <script>
        const modal = document.getElementById('modalKategori');
        const form = document.getElementById('formKategori');
        const title = document.getElementById('modalTitle');
        const input = document.getElementById('inputNama');
        const methodField = document.getElementById('methodField');

        function openAddModal() {
            title.innerText = "Tambah Kategori Baru";
            form.action = "{{ route('kategori.store') }}";
            input.value = "";
            methodField.innerHTML = ""; // Kosongkan method karena store pakai POST
            modal.classList.remove('hidden');
        }

        function openEditModal(id, nama) {
            title.innerText = "Edit Kategori";
            // Sesuaikan URL action ke route update (Pastikan route kategori.update sudah ada)
            form.action = `/kategori/${id}`; 
            input.value = nama;
            methodField.innerHTML = '@method("PUT")'; // Tambahkan method PUT untuk update
            modal.classList.remove('hidden');
        }

        function closeModal() {
            modal.classList.add('hidden');
        }
    </script>
</x-app-layout>