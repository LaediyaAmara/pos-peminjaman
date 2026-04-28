<x-app-layout>
    <x-slot name="header">
        <h2 class="font-bold text-xl text-indigo-800 leading-tight">✏️ Edit Data Buku</h2>
    </x-slot>

    <div class="py-12 bg-gray-50">
        <div class="max-w-3xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white shadow-xl sm:rounded-2xl p-8 border border-gray-100">
                <form method="POST" action="{{ route('buku.update', $buku->BukuID) }}">
                    @csrf
                    @method('PUT')
                    
                    <div class="space-y-6">
                        <div>
                            <x-input-label for="Judul" :value="__('Judul Buku')" />
                            <x-text-input id="Judul" class="block mt-1 w-full rounded-xl" type="text" name="Judul" value="{{ $buku->Judul }}" required />
                        </div>

                        <div class="grid grid-cols-2 gap-4">
                            <div>
                                <x-input-label for="Penulis" :value="__('Penulis')" />
                                <x-text-input id="Penulis" class="block mt-1 w-full rounded-xl" type="text" name="Penulis" value="{{ $buku->Penulis }}" required />
                            </div>
                            <div>
                                <x-input-label for="Penerbit" :value="__('Penerbit')" />
                                <x-text-input id="Penerbit" class="block mt-1 w-full rounded-xl" type="text" name="Penerbit" value="{{ $buku->Penerbit }}" required />
                            </div>
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

                        <div class="grid grid-cols-2 gap-4">
                            <div>
                                <x-input-label for="TahunTerbit" :value="__('Tahun Terbit')" />
                                <x-text-input id="TahunTerbit" class="block mt-1 w-full rounded-xl" type="number" name="TahunTerbit" value="{{ $buku->TahunTerbit }}" required />
                            </div>
                            <div>
                                <x-input-label for="Stok" :value="__('Jumlah Stok Buku')" />
                                <x-text-input id="Stok" class="block mt-1 w-full rounded-xl" type="number" name="Stok" value="{{ $buku->Stok }}" min="0" required />
                            </div>
                        </div>
                    </div>

                    <div class="flex items-center justify-end mt-8 pt-6 border-t">
                        <a href="{{ route('buku.index') }}" class="text-gray-500 mr-4 font-medium hover:text-gray-700">Batal</a>
                        <x-primary-button class="bg-indigo-600 hover:bg-indigo-700 shadow-lg shadow-indigo-200">
                            Simpan Perubahan
                        </x-primary-button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</x-app-layout>