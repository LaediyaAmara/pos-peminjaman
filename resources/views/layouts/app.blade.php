<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>{{ config('app.name', 'PerpusDigital') }}</title>

    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=figtree:400,500,600,800,900&display=swap" rel="stylesheet" />

    @vite(['resources/css/app.css', 'resources/js/app.js'])
    
    {{-- Tambahan Alpine.js untuk fitur buka-tutup menu di HP --}}
    <script defer src="https://unpkg.com/alpinejs@3.x.x/dist/cdn.min.js"></script>
</head>
<body class="font-sans antialiased bg-gray-50 text-gray-900" x-data="{ openSidebar: false }">

    {{-- 1. LAYOUT ADMIN & PETUGAS (DENGAN SIDEBAR RESPONSIF) --}}
    @if(auth()->user()->role == 'admin' || auth()->user()->role == 'petugas')
        <div class="flex min-h-screen">
            
            <aside 
                class="fixed inset-y-0 left-0 z-50 w-72 bg-indigo-950 text-white transform md:translate-x-0 md:static md:inset-0 transition-transform duration-300 ease-in-out flex flex-col shadow-2xl h-screen"
                :class="openSidebar ? 'translate-x-0' : '-translate-x-full'">
                
                <div class="p-8 flex justify-between items-center">
                    <div class="flex items-center space-x-3">
                        <div class="bg-indigo-500 p-2 rounded-xl shadow-lg">📚</div>
                        <h1 class="text-xl font-black tracking-tighter uppercase italic">Perpus<span class="text-indigo-400">Pro</span></h1>
                    </div>
                    <button @click="openSidebar = false" class="md:hidden text-white">
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path></svg>
                    </button>
                </div>

                <nav class="flex-1 px-4 space-y-1 overflow-y-auto no-scrollbar">
                    @include('layouts.sidebar') 
                </nav>

                <div class="p-6 border-t border-indigo-900 bg-indigo-1000">
                    <form method="POST" action="{{ route('logout') }}">
                        @csrf
                        <button type="submit" class="flex items-center justify-center w-full px-4 py-3 bg-rose-500 text-white rounded-2xl hover:bg-rose-600 transition-all font-black text-[10px] uppercase tracking-widest shadow-lg shadow-rose-900/20">
                            🚪 Logout Sistem
                        </button>
                    </form>
                </div>
            </aside>

            <main class="flex-1 flex flex-col min-w-0 h-screen overflow-y-auto">
                <header class="h-20 bg-white border-b border-gray-100 flex items-center justify-between px-6 md:px-10 sticky top-0 z-40">
                    <div class="flex items-center">
                        <button @click="openSidebar = true" class="md:hidden mr-4 text-indigo-950 bg-indigo-50 p-2 rounded-xl">
                            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16m-7 6h7"></path></svg>
                        </button>
                        
                        @isset($header)
                            <div class="text-gray-800 font-black text-sm md:text-lg truncate max-w-[150px] md:max-w-none uppercase tracking-tight">
                                {{ $header }}
                            </div>
                        @endisset
                    </div>
                    
                    <div class="flex items-center">
                        <span class="px-4 py-1.5 bg-indigo-50 text-indigo-600 rounded-full text-[9px] font-black uppercase tracking-widest border border-indigo-100">
                            {{ auth()->user()->role }}
                        </span>
                    </div>
                </header>

                <div class="p-6 md:p-10 flex-1">
                    {{ $slot }}
                </div>
            </main>

            <div x-show="openSidebar" @click="openSidebar = false" class="fixed inset-0 bg-indigo-950/60 z-40 md:hidden backdrop-blur-sm"></div>
        </div>

    {{-- 2. LAYOUT SISWA (FULL SCREEN RESPONSIF) --}}
    @else
        <div class="min-h-screen flex flex-col bg-gray-50">
            <nav class="bg-white/90 backdrop-blur-md sticky top-0 z-50 border-b border-gray-100 py-4 px-6 md:px-12 flex justify-between items-center shadow-sm">
                <div class="flex items-center gap-3">
                    <div class="w-10 h-10 bg-emerald-600 rounded-xl flex items-center justify-center text-white shadow-lg shadow-emerald-200">📚</div>
                    <span class="font-black text-emerald-950 text-lg md:text-xl tracking-tighter italic">Perpus<span class="text-emerald-600">Digital</span></span>
                </div>

                <div class="flex items-center gap-3 md:gap-8">
                    <div class="hidden sm:flex items-center gap-6 text-[10px] font-black uppercase tracking-[0.2em]">
                        <a href="{{ route('dashboard') }}" class="{{ request()->routeIs('dashboard') ? 'text-emerald-600' : 'text-gray-400' }}">Beranda</a>
                    </div>

                    <form method="POST" action="{{ route('logout') }}">
                        @csrf
                        <button type="submit" class="bg-rose-50 text-rose-600 px-4 md:px-6 py-2.5 rounded-xl text-[9px] font-black uppercase tracking-widest active:scale-95 transition-all">
                            Keluar
                        </button>
                    </form>
                </div>
            </nav>

            <main class="flex-1 w-full max-w-7xl mx-auto p-4 md:p-12">
                {{ $slot }}
            </main>

            <div class="md:hidden sticky bottom-0 bg-white border-t border-gray-100 flex justify-around py-4 z-50 shadow-[0_-5px_15px_rgba(0,0,0,0.05)]">
                <a href="{{ route('dashboard') }}" class="flex flex-col items-center {{ request()->routeIs('dashboard') ? 'text-emerald-600' : 'text-gray-400' }}">
                    <span class="text-xl">🏠</span>
                    <span class="text-[8px] font-black uppercase mt-1">Home</span>
                </a>
                <a href="{{ route('peminjam.pinjam') }}" class="flex flex-col items-center {{ request()->routeIs('peminjam.pinjam') ? 'text-emerald-600' : 'text-gray-400' }}">
                    <span class="text-xl">🔍</span>
                    <span class="text-[8px] font-black uppercase mt-1">Cari</span>
                </a>
                <a href="{{ route('peminjam.index') }}" class="flex flex-col items-center {{ request()->routeIs('peminjam.index') ? 'text-emerald-600' : 'text-gray-400' }}">
                    <span class="text-xl">📋</span>
                    <span class="text-[8px] font-black uppercase mt-1">Pinjam</span>
                </a>
            </div>
        </div>
    @endif

</body>
</html>