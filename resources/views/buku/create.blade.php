<x-app-layout>
    <x-slot name="header">
        <h2 class="font-bold text-xl text-indigo-800 leading-tight">
            ✍️ Tambah Koleksi Buku Baru
        </h2>
    </x-slot>

    <div class="py-12 bg-gray-50">
        <div class="max-w-3xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-xl sm:rounded-2xl p-8 border border-gray-100">
                <form method="POST" action="{{ route('buku.store') }}" class="space-y-6">
                    @csrf
                    
                    {{-- Judul Buku --}}
                    <div>
                        <x-input-label for="Judul" class="font-semibold" :value="__('Judul Buku')" />
                        <x-text-input id="Judul" class="block mt-1 w-full rounded-xl border-gray-300 shadow-sm focus:ring-indigo-500 focus:border-indigo-500" type="text" name="Judul" placeholder="Masukkan judul buku..." required />
                    </div>

                    {{-- Kategori Buku (Dropdown baru yang sudah dirapikan) --}}
                    <div>
                        <x-input-label for="KategoriID" class="font-semibold" :value="__('Kategori Buku')" />
                        <select name="KategoriID" id="KategoriID" class="block mt-1 w-full rounded-xl border-gray-300 shadow-sm focus:ring-indigo-500 focus:border-indigo-500" required>
                            <option value="" disabled selected>-- Pilih Kategori --</option>
                            @foreach($kategoris as $k)
                                <option value="{{ $k->KategoriID }}">{{ $k->NamaKategori }}</option>
                            @endforeach
                        </select>
                    </div>

                    {{-- Penulis & Penerbit --}}
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <div>
                            <x-input-label for="Penulis" class="font-semibold" :value="__('Penulis')" />
                            <x-text-input id="Penulis" class="block mt-1 w-full rounded-xl border-gray-300 shadow-sm focus:ring-indigo-500 focus:border-indigo-500" type="text" name="Penulis" placeholder="Nama penulis..." required />
                        </div>
                        <div>
                            <x-input-label for="Penerbit" class="font-semibold" :value="__('Penerbit')" />
                            <x-text-input id="Penerbit" class="block mt-1 w-full rounded-xl border-gray-300 shadow-sm focus:ring-indigo-500 focus:border-indigo-500" type="text" name="Penerbit" placeholder="Nama penerbit..." required />
                        </div>
                    </div>

                    {{-- Tahun & Stok --}}
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <div>
                            <x-input-label for="TahunTerbit" class="font-semibold" :value="__('Tahun Terbit')" />
                            <x-text-input id="TahunTerbit" class="block mt-1 w-full rounded-xl border-gray-300 shadow-sm focus:ring-indigo-500 focus:border-indigo-500" type="number" name="TahunTerbit" placeholder="Contoh: 2024" required />
                        </div>
                        <div>
                            <x-input-label for="Stok" class="font-semibold" :value="__('Stok Awal')" />
                            <x-text-input id="Stok" class="block mt-1 w-full rounded-xl border-gray-300 shadow-sm focus:ring-indigo-500 focus:border-indigo-500" type="number" name="Stok" min="0" placeholder="Jumlah buku..." required />
                        </div>
                    </div>

                    {{-- Tombol Aksi --}}
                    <div class="flex items-center justify-end mt-8 pt-6 border-t border-gray-100 space-x-4">
                        <a href="{{ route('buku.index') }}" class="text-gray-500 font-medium hover:text-gray-700 transition-colors">Batal</a>
                        <x-primary-button class="bg-indigo-600 hover:bg-indigo-700 px-8 py-3 rounded-xl shadow-lg shadow-indigo-100 transition-all active:scale-95">
                            ✨ Simpan Buku
                        </x-primary-button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</x-app-layout>