<x-app-layout>
    <div class="max-w-3xl mx-auto py-12">
        <div class="bg-white rounded-[2.5rem] shadow-xl border border-gray-100 overflow-hidden">
            <div class="bg-indigo-600 p-8 text-white">
                <h3 class="text-2xl font-black">Tambah Anggota Baru</h3>
                <p class="opacity-80 text-sm">Input data siswa untuk akses perpustakaan.</p>
            </div>
            
            <form action="{{ route('user.store') }}" method="POST" class="p-10 space-y-6">
                @csrf
                <div>
                    <label class="block text-xs font-black uppercase tracking-widest text-gray-400 mb-2">Nama Lengkap</label>
                    <input type="text" name="NamaLengkap" class="w-full rounded-2xl border-gray-100 bg-gray-50 focus:ring-indigo-500" required>
                </div>
                <div class="grid grid-cols-2 gap-6">
                    <div>
                        <label class="block text-xs font-black uppercase tracking-widest text-gray-400 mb-2">Username</label>
                        <input type="text" name="Username" class="w-full rounded-2xl border-gray-100 bg-gray-50 focus:ring-indigo-500" required>
                    </div>
                    <div>
                        <label class="block text-xs font-black uppercase tracking-widest text-gray-400 mb-2">Email</label>
                        <input type="email" name="email" class="w-full rounded-2xl border-gray-100 bg-gray-50 focus:ring-indigo-500" required>
                    </div>
                </div>
                <div>
                    <label class="block text-xs font-black uppercase tracking-widest text-gray-400 mb-2">Password Default</label>
                    <input type="password" name="password" class="w-full rounded-2xl border-gray-100 bg-gray-50 focus:ring-indigo-500" required>
                </div>
                
                <div class="flex gap-4 pt-4">
                    <button type="submit" class="flex-1 bg-indigo-600 text-white py-4 rounded-2xl font-black uppercase tracking-widest shadow-lg shadow-indigo-200">Simpan Anggota</button>
                    <a href="{{ route('user.index') }}" class="px-8 py-4 bg-gray-100 text-gray-500 rounded-2xl font-black uppercase tracking-widest">Batal</a>
                </div>
            </form>
        </div>
    </div>
</x-app-layout>