<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <meta name="csrf-token" content="{{ csrf_token() }}">

        <title>{{ config('app.name', 'Laravel') }}</title>

        <link rel="preconnect" href="https://fonts.bunny.net">
        <link href="https://fonts.bunny.net/css?family=figtree:400,500,600&display=swap" rel="stylesheet" />

        @vite(['resources/css/app.css', 'resources/js/app.js'])
    </head>
    <body class="font-sans antialiased bg-gray-50">
        <div class="flex min-h-screen">
            
            <aside class="w-72 bg-indigo-900 text-white flex-shrink-0 hidden md:flex flex-col shadow-2xl">
                <div class="p-8">
                    <div class="flex items-center space-x-3">
                        <div class="bg-indigo-500 p-2 rounded-xl shadow-lg">📚</div>
                        <h1 class="text-xl font-black tracking-tighter uppercase">Perpus<span class="text-indigo-400">Pro</span></h1>
                    </div>
                </div>

                <nav class="flex-1 px-4 space-y-1 overflow-y-auto">
                    @include('layouts.sidebar') 
                </nav>

                <div class="p-6 border-t border-indigo-800">
                    <div class="mb-4 px-4">
                        <p class="text-xs text-indigo-300 font-bold uppercase tracking-widest">Akun Saya</p>
                        <p class="text-sm font-medium truncate">{{ auth()->user()->NamaLengkap }}</p>
                    </div>
                    <form method="POST" action="{{ route('logout') }}">
                        @csrf
                        <button type="submit" class="flex items-center w-full px-4 py-3 bg-rose-500/10 text-rose-300 rounded-2xl hover:bg-rose-500 hover:text-white transition-all font-bold">
                            <span class="mr-3">🚪</span> Logout
                        </button>
                    </form>
                </div>
            </aside>

            <main class="flex-1 flex flex-col min-w-0">
                <header class="h-20 bg-white border-b border-gray-100 flex items-center justify-between px-10 sticky top-0 z-10">
                    <div>
                        @isset($header)
                            <div class="text-gray-800">
                                {{ $header }}
                            </div>
                        @endisset
                    </div>
                    
                    <div class="flex items-center space-x-4">
                        <span class="px-4 py-1.5 bg-indigo-50 text-indigo-600 rounded-full text-[10px] font-black uppercase tracking-widest border border-indigo-100 shadow-sm">
                            Role: {{ auth()->user()->role }}
                        </span>
                    </div>
                </header>

                <div class="p-10 flex-1">
                    {{ $slot }}
                </div>
            </main>
        </div>
    </body>
</html>