<!DOCTYPE html>
<html lang="id" class="dark">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, viewport-fit=cover">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <meta name="theme-color" content="#08080f">
    <meta name="apple-mobile-web-app-capable" content="yes">
    <meta name="apple-mobile-web-app-status-bar-style" content="black-translucent">
    <title>@yield('title', 'Gudang AFNALINK')</title>
    <link rel="manifest" href="/manifest.json">
    <link rel="icon" type="image/png" href="https://ber.afna.link/storage/uploads/logos/company_logo_1768520380.png">
    <link rel="apple-touch-icon" href="https://ber.afna.link/storage/uploads/logos/company_logo_1768520380.png">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700;800&display=swap" rel="stylesheet">
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    @stack('styles')
    <style>
        body { font-family: 'Inter', system-ui, sans-serif; }
        .bg-deep { background: #08080f; }
        .glass { background: rgba(255,255,255,0.04); border: 1px solid rgba(255,255,255,0.08); }
        .glass-light { background: #ffffff; border: 1px solid #e2e8f0; }
        .light body { background: #f8fafc !important; color: #1e293b; }
        .light .bg-deep { background: #f8fafc !important; }
        .light .glass { background: #ffffff; border: 1px solid #e2e8f0; }
        .light .text-white { color: #1e293b !important; }
        .light .text-zinc-100 { color: #1e293b !important; }
        .light .text-zinc-200 { color: #334155 !important; }
        .light .text-zinc-300 { color: #475569 !important; }
        .light .text-zinc-400 { color: #64748b !important; }
        .light .text-zinc-500 { color: #94a3b8 !important; }
        .light .text-zinc-600 { color: #475569 !important; }
        .light .border-white\/\[0\.06\] { border-color: #e2e8f0 !important; }
        .light .border-white\/\[0\.08\] { border-color: #e2e8f0 !important; }
        /* Badge kategori & general fix light */
        .light .bg-white\/\[0\.06\].text-zinc-300, .light span.bg-white\/\[0\.06\] { background: #f1f5f9 !important; border-color: #cbd5e1 !important; color: #334155 !important; }
        .light .bg-white\/\[0\.06\].border-white\/\[0\.06\] { background: #f1f5f9 !important; border-color: #cbd5e1 !important; }
        /* Light mode sidebar & nav fixes */
        .light #sidebar { background: #ffffff !important; border-color: #e2e8f0 !important; }
        .light #sidebar .border-white\/\[0\.06\] { border-color: #e2e8f0 !important; }
        .light #sidebar .text-zinc-400 { color: #64748b !important; }
        .light #sidebar .text-zinc-500 { color: #94a3b8 !important; }
        .light #sidebar .hover\:text-white:hover { color: #0f172a !important; }
        .light #sidebar .hover\:bg-white\/\[0\.06\]:hover { background: #f1f5f9 !important; }
        .light #sidebar .glass { background: #f8fafc !important; border-color: #e2e8f0 !important; }
        .light #sidebar .bg-\[\#0d0d14\] { background: #ffffff !important; }
        .light header.lg\:hidden { background: #ffffff !important; border-color: #e2e8f0 !important; }
        .light nav.lg\:hidden { background: #ffffff !important; border-color: #e2e8f0 !important; }
        .light main .bg-\[\#08080f\]\/50 { background: rgba(255,255,255,0.8) !important; border-color: #e2e8f0 !important; }
        .light .bg-\[\#0d0d14\] { background: #ffffff !important; }
        .light .bg-\[\#08080f\] { background: #f8fafc !important; }
        .light .bg-white\/\[0\.06\] { background: #f1f5f9 !important; }
        .light .hover\:bg-white\/\[0\.06\]:hover { background: #f1f5f9 !important; }
        .light .hover\:bg-white\/\[0\.08\]:hover { background: #e2e8f0 !important; }
        .light input.bg-white\/\[0\.06\], .light select.bg-white\/\[0\.06\] { background: #ffffff !important; border-color: #e2e8f0 !important; color: #1e293b !important; }
        .light input.bg-\[\#08080f\], .light select.bg-\[\#08080f\], .light textarea.bg-\[\#08080f\] { background: #ffffff !important; border-color: #e2e8f0 !important; color: #1e293b !important; }
    </style>
</head>
<body class="bg-deep text-zinc-100 min-h-screen antialiased overflow-x-hidden">
    <!-- Mobile top bar -->
    <header class="lg:hidden fixed top-0 left-0 right-0 z-40 bg-[#0d0d14] border-b border-white/[0.06] px-4 py-3 flex items-center justify-between">
        <div class="flex items-center gap-3">
            <button id="sidebar-toggle" class="p-2 -ml-2 text-zinc-400 hover:text-white">
                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16"/></svg>
            </button>
            <div class="flex items-center gap-2">
                <img src="https://ber.afna.link/storage/uploads/logos/company_logo_1768520380.png" class="w-7 h-7 rounded-lg">
                <span class="font-bold text-sm tracking-wide">GUDANG AFNALINK</span>
            </div>
        </div>
        <div class="flex items-center gap-2">
            <button id="theme-toggle" class="p-2 text-zinc-400 hover:text-white rounded-lg hover:bg-white/[0.06]">
                <svg id="theme-icon" class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-width="2" d="M20.354 15.354A9 9 0 018.646 3.646 9.003 9.003 0 0012 21a9.003 9.003 0 008.354-5.646z"/></svg>
            </button>
            <form method="POST" action="{{ route('logout') }}" class="inline">
                @csrf
                <button type="submit" class="p-2 text-zinc-400 hover:text-red-400" title="Keluar">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-width="2" d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a2 2 0 01-2 2H6a2 2 0 01-2-2V7a2 2 0 012-2h5a2 2 0 012 2v1"/></svg>
                </button>
            </form>
        </div>
    </header>

    <!-- Sidebar overlay -->
    <div id="sidebar-overlay" class="hidden fixed inset-0 bg-black/50 z-40 lg:hidden"></div>

    <!-- Sidebar -->
    <aside id="sidebar" class="fixed left-0 top-0 bottom-0 w-64 bg-[#0d0d14] border-r border-white/[0.06] z-50 -translate-x-full lg:translate-x-0 transition-transform duration-300 flex flex-col">
        <div class="p-6 border-b border-white/[0.06]">
            <div class="flex items-center gap-3">
                <img src="https://ber.afna.link/storage/uploads/logos/company_logo_1768520380.png" class="w-10 h-10 rounded-xl">
                <div>
                    <div class="font-bold text-sm tracking-widest">GUDANG</div>
                    <div class="font-bold text-sm tracking-widest text-cyan-400 -mt-1">AFNALINK</div>
                    <div class="text-[10px] text-zinc-500 tracking-wide">Warehouse System</div>
                </div>
            </div>
        </div>

        <nav class="flex-1 p-4 space-y-1 overflow-y-auto">
            <a href="{{ route('dashboard') }}" class="flex items-center gap-3 px-3 py-2.5 rounded-xl text-sm {{ request()->routeIs('dashboard') ? 'bg-cyan-500/10 text-cyan-400 border border-cyan-500/20' : 'text-zinc-400 hover:text-white hover:bg-white/[0.06]' }}">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-width="2" d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6"/></svg>
                Dashboard
            </a>
            <a href="{{ route('barang.index') }}" class="flex items-center gap-3 px-3 py-2.5 rounded-xl text-sm {{ request()->routeIs('barang.*') ? 'bg-cyan-500/10 text-cyan-400 border border-cyan-500/20' : 'text-zinc-400 hover:text-white hover:bg-white/[0.06]' }}">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-width="2" d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10m0-10L4 7"/></svg>
                Data Barang
                @php $pendingBarangCount = \App\Models\Barang::where('deletion_status','pending')->count(); @endphp
                @if($pendingBarangCount > 0 && auth()->user()->role === 'admin')<span class="ml-auto text-xs bg-amber-500 text-white px-2 py-0.5 rounded-full font-bold">{{ $pendingBarangCount }}</span>@endif
            </a>
            <a href="{{ route('barang-masuk.index') }}" class="flex items-center gap-3 px-3 py-2.5 rounded-xl text-sm {{ request()->routeIs('barang-masuk.*') ? 'bg-cyan-500/10 text-cyan-400 border border-cyan-500/20' : 'text-zinc-400 hover:text-white hover:bg-white/[0.06]' }}">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-width="2" d="M12 9v3m0 0v3m0-3h3m-3 0H9m12 0a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                Barang Masuk
                <span class="ml-auto text-xs bg-emerald-500/20 text-emerald-400 px-2 py-0.5 rounded-full">IN</span>
            </a>
            <a href="{{ route('barang-keluar.index') }}" class="flex items-center gap-3 px-3 py-2.5 rounded-xl text-sm {{ request()->routeIs('barang-keluar.*') ? 'bg-cyan-500/10 text-cyan-400 border border-cyan-500/20' : 'text-zinc-400 hover:text-white hover:bg-white/[0.06]' }}">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-width="2" d="M15 12H9m12 0a9 9 0 11-18 0 9 9 0 0118 0z"/><path stroke-linecap="round" stroke-width="2" d="M12 9v6"/></svg>
                Barang Keluar
                <span class="ml-auto text-xs bg-red-500/20 text-red-400 px-2 py-0.5 rounded-full">OUT</span>
            </a>
            <a href="{{ route('pegawai.index') }}" class="flex items-center gap-3 px-3 py-2.5 rounded-xl text-sm {{ request()->routeIs('karyawan.*') || request()->routeIs('pegawai.*') ? 'bg-cyan-500/10 text-cyan-400 border border-cyan-500/20' : 'text-zinc-400 hover:text-white hover:bg-white/[0.06]' }}">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z"/></svg>
                Data Pegawai
            </a>
            <a href="{{ route('laporan.index') }}" class="flex items-center gap-3 px-3 py-2.5 rounded-xl text-sm {{ request()->routeIs('laporan.*') ? 'bg-cyan-500/10 text-cyan-400 border border-cyan-500/20' : 'text-zinc-400 hover:text-white hover:bg-white/[0.06]' }}">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z"/></svg>
                Laporan
            </a>
            <a href="{{ route('profile.index') }}" class="flex items-center gap-3 px-3 py-2.5 rounded-xl text-sm {{ request()->routeIs('profile.*') ? 'bg-cyan-500/10 text-cyan-400 border border-cyan-500/20' : 'text-zinc-400 hover:text-white hover:bg-white/[0.06]' }}">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-width="2" d="M5.121 17.804A13.937 13.937 0 0112 16c2.5 0 4.847.655 6.879 1.804M15 10a3 3 0 11-6 0 3 3 0 016 0zm6 2a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                Profile
            </a>

            @if(auth()->user()->role === 'admin')
            <div class="pt-4 mt-4 border-t border-white/[0.06]">
                <div class="text-[10px] font-semibold text-zinc-500 tracking-widest px-3 mb-2">ADMIN</div>
                <a href="{{ route('verifikasi.index') }}" class="flex items-center gap-3 px-3 py-2.5 rounded-xl text-sm {{ request()->routeIs('verifikasi.*') ? 'bg-amber-500/10 text-amber-400 border border-amber-500/20' : 'text-zinc-400 hover:text-white hover:bg-white/[0.06]' }}">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                    Verifikasi Hapus
                    @php $vCount = \App\Models\Barang::where('deletion_status','pending')->count(); @endphp
                    @if($vCount > 0)<span class="ml-auto text-xs bg-amber-500 text-white px-2 py-0.5 rounded-full font-bold">{{ $vCount }}</span>@endif
                </a>
                <a href="{{ route('users.index') }}" class="flex items-center gap-3 px-3 py-2.5 rounded-xl text-sm {{ request()->routeIs('users.*') ? 'bg-cyan-500/10 text-cyan-400 border border-cyan-500/20' : 'text-zinc-400 hover:text-white hover:bg-white/[0.06]' }}">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z"/></svg>
                    Kelola Users
                </a>
            </div>
            @endif
        </nav>

        <div class="p-2.5 pb-[calc(1rem+env(safe-area-inset-bottom))] lg:pb-2.5 border-t border-white/[0.06]">
            <a href="{{ route('profile.index') }}" class="flex items-center gap-2 py-1.5 px-2 rounded-lg hover:bg-white/[0.06] transition group">
                <div class="w-7 h-7 rounded-lg bg-gradient-to-br from-cyan-500 to-indigo-600 flex items-center justify-center text-white font-bold text-[10px] shrink-0">
                    {{ strtoupper(substr(auth()->user()->name, 0, 1)) }}{{ strtoupper(substr(explode(' ', auth()->user()->name)[1] ?? '', 0, 1)) }}
                </div>
                <div class="flex-1 min-w-0">
                    <div class="text-[13px] font-medium truncate group-hover:text-white leading-none">{{ auth()->user()->name }}</div>
                </div>
                <svg class="w-3 h-3 text-zinc-600 group-hover:text-zinc-400 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-width="2" d="M9 5l7 7-7 7"/></svg>
            </a>
            <div class="flex items-center gap-1 mt-3 pb-20 lg:pb-0">
                <button onclick="document.getElementById('theme-toggle').click()" class="w-8 h-8 flex items-center justify-center rounded-lg hover:bg-white/[0.06] text-zinc-500 hover:text-zinc-300" title="Toggle Tema">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-width="2" d="M12 3v1m0 16v1m9-9h-1M4 12H3m15.364 6.364l-.707-.707M6.343 6.343l-.707-.707m12.728 0l-.707.707M6.343 17.657l-.707.707M16 12a4 4 0 11-8 0 4 4 0 018 0z"/></svg>
                </button>
                <form method="POST" action="{{ route('logout') }}">
                    @csrf
                    <button type="submit" class="w-8 h-8 flex items-center justify-center rounded-lg bg-red-500/10 border border-red-500/20 text-red-400 hover:bg-red-500/20 hover:text-red-300" title="Keluar">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-width="2" d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a2 2 0 01-2 2H6a2 2 0 01-2-2V7a2 2 0 012-2h5a2 2 0 012 2v1"/></svg>
                    </button>
                </form>
            </div>
        </div>
    </aside>

    <!-- Main content -->
    <main class="lg:pl-64 pt-[57px] lg:pt-0 min-h-screen">
        <!-- Desktop top bar -->
        <div class="hidden lg:flex items-center justify-between px-8 py-4 border-b border-white/[0.06] bg-[#08080f]/50 backdrop-blur sticky top-0 z-20">
            <div>
                <h1 class="text-lg font-semibold">@yield('page-title', 'Dashboard')</h1>
                <p class="text-xs text-zinc-500">@yield('page-subtitle', 'Kelola gudang AFNALINK')</p>
            </div>
            <div class="flex items-center gap-3">
                <div class="text-right hidden xl:block">
                    <div class="text-sm font-medium">{{ auth()->user()->name }}</div>
                    <div class="text-xs text-zinc-500">{{ auth()->user()->email }}</div>
                </div>
                <button id="theme-toggle-desktop" onclick="document.getElementById('theme-toggle').click()" class="p-2.5 glass rounded-xl text-zinc-400 hover:text-white">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-width="2" d="M12 3v1m0 16v1m9-9h-1M4 12H3m15.364 6.364l-.707-.707M6.343 6.343l-.707-.707m12.728 0l-.707.707M6.343 17.657l-.707.707M16 12a4 4 0 11-8 0 4 4 0 018 0z"/></svg>
                </button>
            </div>
        </div>

        <div class="p-4 lg:p-8 pb-24 lg:pb-8">
            @if(session('success'))
                <div data-flash class="mb-6 glass border-emerald-500/20 bg-emerald-500/10 rounded-xl px-4 py-3 flex items-center gap-3">
                    <div class="w-8 h-8 rounded-lg bg-emerald-500/20 flex items-center justify-center flex-shrink-0">✓</div>
                    <div class="text-sm text-emerald-300">{{ session('success') }}</div>
                </div>
            @endif
            @if(session('import_errors'))
                <div data-flash class="mb-6 glass border-amber-500/20 bg-amber-500/10 rounded-xl px-4 py-3">
                    <div class="text-sm font-medium text-amber-300 mb-1">Beberapa baris gagal import:</div>
                    <ul class="text-xs text-amber-200/80 space-y-1 max-h-32 overflow-auto">
                        @foreach(session('import_errors') as $err)
                            <li>• {{ $err }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif
            @if($errors->any())
                <div data-flash class="mb-6 glass border-red-500/20 bg-red-500/10 rounded-xl px-4 py-3">
                    <div class="text-sm font-medium text-red-300 mb-1">Terjadi kesalahan:</div>
                    <ul class="text-xs text-red-300/80 space-y-1">
                        @foreach($errors->all() as $e)
                            <li>• {{ $e }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif

            @yield('content')
        </div>
    </main>

    <!-- Bottom navbar mobile -->
    <nav class="lg:hidden fixed bottom-0 left-0 right-0 bg-[#0d0d14] border-t border-white/[0.06] flex items-center justify-around py-2 pb-[calc(0.5rem+env(safe-area-inset-bottom))] z-30">
        <a href="{{ route('dashboard') }}" class="flex flex-col items-center gap-1 px-3 py-1 {{ request()->routeIs('dashboard') ? 'text-cyan-400' : 'text-zinc-500' }}">
            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-width="2" d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6"/></svg>
            <span class="text-[10px] font-medium">Home</span>
        </a>
        <a href="{{ route('barang.index') }}" class="flex flex-col items-center gap-1 px-3 py-1 {{ request()->routeIs('barang.*') ? 'text-cyan-400' : 'text-zinc-500' }}">
            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-width="2" d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10m0-10L4 7"/></svg>
            <span class="text-[10px] font-medium">Barang</span>
        </a>
        <a href="{{ route('barang-masuk.create') }}" class="flex flex-col items-center gap-1 px-3 py-1">
            <div class="w-12 h-12 rounded-2xl bg-gradient-to-br from-cyan-500 to-indigo-600 flex items-center justify-center -mt-6 shadow-lg shadow-cyan-500/20">
                <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-width="2" d="M12 4v16m8-8H4"/></svg>
            </div>
        </a>
        <a href="{{ route('barang-keluar.index') }}" class="flex flex-col items-center gap-1 px-3 py-1 {{ request()->routeIs('barang-keluar.*') ? 'text-cyan-400' : 'text-zinc-500' }}">
            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-width="2" d="M15 12H9m12 0a9 9 0 11-18 0 9 9 0 0118 0z"/><path stroke-linecap="round" stroke-width="2" d="M9 12l2 2 4-4"/></svg>
            <span class="text-[10px] font-medium">Keluar</span>
        </a>
        <a href="{{ route('laporan.index') }}" class="flex flex-col items-center gap-1 px-3 py-1 {{ request()->routeIs('laporan.*') ? 'text-cyan-400' : 'text-zinc-500' }}">
            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z"/></svg>
            <span class="text-[10px] font-medium">Laporan</span>
        </a>
    </nav>

    <button id="theme-toggle" class="hidden"></button>
    @stack('scripts')
</body>
</html>
