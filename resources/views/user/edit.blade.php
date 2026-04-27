<x-app-layout>
    <div class="max-w-3xl mx-auto py-12">
        <div class="bg-white rounded-[2.5rem] shadow-xl overflow-hidden border border-gray-100">
            <div class="bg-amber-500 p-8 text-white">
                <h3 class="text-2xl font-black">Edit Profil Anggota</h3>
                <p class="opacity-80 text-sm">Perbarui informasi akun siswa.</p>
            </div>
            
            <form action="{{ route('user.update', $user->id) }}" method="POST" class="p-10 space-y-6">
                @csrf @method('PUT')
                <div>
                    <label class="block text-[10px] font-black uppercase tracking-widest text-gray-400 mb-2">Nama Lengkap</label>
                    <input type="text" name="NamaLengkap" value="{{ $user->NamaLengkap }}" class="w-full rounded-2xl border-gray-100 bg-gray-50 focus:ring-amber-500" required>
                </div>
                <div>
                    <label class="block text-[10px] font-black uppercase tracking-widest text-gray-400 mb-2">Email</label>
                    <input type="email" name="email" value="{{ $user->email }}" class="w-full rounded-2xl border-gray-100 bg-gray-50 focus:ring-amber-500" required>
                </div>
                
                <div>
                    <label class="block text-[10px] font-black uppercase tracking-widest text-gray-400 mb-2">Password Baru (Kosongkan jika tidak diubah)</label>
                    <input type="password" name="password" class="w-full rounded-2xl border-gray-100 bg-gray-50 focus:ring-amber-500">
                </div>
                
                <div class="flex gap-4 pt-4">
                    <button type="submit" class="flex-1 bg-amber-500 text-white py-4 rounded-2xl font-black uppercase tracking-widest shadow-lg shadow-amber-100">Update Profil</button>
                    <a href="{{ route('user.index') }}" class="px-8 py-4 bg-gray-100 text-gray-500 rounded-2xl font-black uppercase tracking-widest">Batal</a>
                </div>
            </form>
        </div>
    </div>
</x-app-layout>