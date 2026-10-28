<!doctype html>
<html lang="id">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>@yield('title', 'Admin Portal') · Engineering Cafe</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <script>
        tailwind.config = {
            theme: {
                extend: {
                    colors: {
                        navy:    '#041562',
                        royal:   '#11468F',
                        vibrant: '#DA1212',
                        cream:   '#EEEEEE',
                    },
                    fontFamily: {
                        sans: ['"Plus Jakarta Sans"', 'system-ui', 'sans-serif'],
                    },
                }
            }
        }
    </script>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:ital,wght@0,300;0,400;0,500;0,600;0,700;0,800;1,400;1,600;1,700&display=swap" rel="stylesheet">
    <style>
        body, button, input, select, textarea { font-family: 'Plus Jakarta Sans', sans-serif; }
        .nav-link {
            transition: background 0.18s, color 0.18s;
            color: rgba(255, 255, 255, 0.75);
        }
        .nav-link:hover {
            background: rgba(255, 255, 255, 0.12);
            color: #ffffff;
        }
        .nav-link.active {
            background: #11468F;
            color: #ffffff;
            box-shadow: inset 4px 0 0 #DA1212;
            font-weight: 800;
        }
        .nav-link svg { opacity: 0.8; }
        .nav-link:hover svg, .nav-link.active svg { opacity: 1; }
        .stat-card { transition: transform 0.2s, box-shadow 0.2s; }
        .stat-card:hover { transform: translateY(-2px); box-shadow: 0 10px 25px rgba(4, 21, 98, 0.08); }
        #sidebar { transition: transform 0.3s ease; }
        @media (max-width: 1024px) {
            #sidebar { transform: translateX(-100%); position: fixed; z-index: 50; height: 100vh; }
            #sidebar.open { transform: translateX(0); }
        }
        @media (min-width: 1025px) {
            #overlay { display: none !important; }
        }
    </style>
    @stack('head')
</head>
<body class="bg-[#EEEEEE] text-slate-800 antialiased">

<div id="overlay" class="fixed inset-0 z-40 hidden bg-black/60 lg:hidden" onclick="closeSidebar()"></div>

<div class="flex min-h-screen">

    {{-- SIDEBAR UTAMA DENGAN WARNA PRIMARY NAVY (#041562) --}}
    <aside id="sidebar" class="flex w-[240px] flex-shrink-0 flex-col bg-[#041562] text-white lg:static lg:translate-x-0 shadow-2xl">

        {{-- Brand / Logo --}}
        <div class="flex items-center gap-3 px-5 py-6 border-b border-white/10">
            <div class="flex h-11 w-11 items-center justify-center rounded-2xl bg-[#DA1212] text-white text-xl font-black shadow-lg">
                ☕
            </div>
            <div>
                <p class="text-[10px] font-black uppercase tracking-widest text-blue-200">Admin Portal</p>
                <p class="text-base font-black text-white leading-tight">Engineering Cafe</p>
                <p class="text-[10px] text-blue-200/80 font-medium">Unsil Cafe Connect</p>
            </div>
        </div>

        {{-- Location badge --}}
        <div class="mx-4 my-4 flex items-center gap-2 rounded-xl px-3 py-2 bg-white/10 text-white">
            <span class="text-xs">📍</span>
            <span class="text-xs font-bold text-blue-100">FT · Espresso Node</span>
        </div>

        {{-- Navigation Menu --}}
        <nav class="flex-1 space-y-1 px-3">

            <a href="{{ route('admin.dashboard') }}"
               class="nav-link flex items-center gap-3 rounded-xl px-3.5 py-3 text-xs font-bold {{ request()->routeIs('admin.dashboard') || request()->routeIs('admin.index') ? 'active' : '' }}">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 shrink-0" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z"/></svg>
                <span>Sales &amp; Analytics</span>
            </a>

            <a href="{{ route('admin.products') }}"
               class="nav-link flex items-center gap-3 rounded-xl px-3.5 py-3 text-xs font-bold {{ request()->routeIs('admin.products*') ? 'active' : '' }}">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 shrink-0" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v10a2 2 0 002 2h8a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"/></svg>
                <span>Menu &amp; Inventory</span>
            </a>

            <a href="{{ route('admin.kitchen') }}"
               class="nav-link flex items-center gap-3 rounded-xl px-3.5 py-3 text-xs font-bold {{ request()->routeIs('admin.kitchen') || request()->routeIs('admin.kds') ? 'active' : '' }}">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 shrink-0" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0z"/></svg>
                <span>Kitchen Live Feed (KDS)</span>
            </a>

            <div class="my-4 border-t border-white/10"></div>

            <a href="{{ route('catalog') }}" target="_blank"
               class="nav-link flex items-center gap-3 rounded-xl px-3.5 py-3 text-xs font-bold">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 shrink-0" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 6H6a2 2 0 00-2 2v10a2 2 0 002 2h10a2 2 0 002-2v-4M14 4h6m0 0v6m0-6L10 14"/></svg>
                <span>Buka Menu Pembeli &rarr;</span>
            </a>
        </nav>

        {{-- User footer --}}
        <div class="flex items-center justify-between p-4 border-t border-white/10 bg-black/10">
            <div class="flex items-center gap-3">
                <div class="flex h-9 w-9 items-center justify-center rounded-xl bg-[#11468F] text-white font-black text-xs ring-2 ring-white/20">
                    EC
                </div>
                <div>
                    <p class="text-xs font-bold text-white leading-tight">Staff Kasir</p>
                    <p class="text-[10px] text-blue-200">Fakultas Teknik</p>
                </div>
            </div>
            <a href="{{ route('catalog') }}" class="text-blue-200 hover:text-white transition" title="Lihat Toko">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a2 2 0 01-2 2H5a2 2 0 01-2-2V7a2 2 0 012-2h6a2 2 0 012 2v1"/></svg>
            </a>
        </div>
    </aside>

    {{-- MAIN AREA CONTENT --}}
    <div class="flex flex-1 flex-col min-w-0">

        {{-- TOP BAR --}}
        <header class="flex items-center justify-between border-b border-slate-200 bg-white px-5 sm:px-8 py-3.5 shadow-xs sticky top-0 z-30">
            <div class="flex items-center gap-4">
                <button onclick="openSidebar()" class="lg:hidden rounded-xl p-2 text-slate-600 hover:bg-slate-100">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16"/></svg>
                </button>

                <div>
                    <p class="text-[11px] font-bold uppercase tracking-widest text-[#11468F]">@yield('page_label', 'Admin Portal')</p>
                    <h1 class="text-xl font-black text-[#041562]">@yield('page_title', 'Dashboard')</h1>
                </div>
            </div>

            <div class="flex items-center gap-3">
                <div class="hidden sm:flex items-center gap-2 rounded-full bg-emerald-50 border border-emerald-200 px-3.5 py-1.5">
                    <span class="relative flex h-2 w-2">
                        <span class="absolute inline-flex h-full w-full animate-ping rounded-full bg-emerald-400 opacity-75"></span>
                        <span class="relative inline-flex h-2 w-2 rounded-full bg-emerald-500"></span>
                    </span>
                    <span class="text-xs font-bold text-emerald-700">Live POS Connected</span>
                </div>

                <div class="flex h-9 w-9 items-center justify-center rounded-xl bg-[#041562] text-white font-black text-xs shadow-md">
                    FT
                </div>
            </div>
        </header>

        {{-- PAGE CONTENT CONTAINER --}}
        <main class="flex-1 overflow-auto p-5 sm:p-7">
            @if (session('success'))
                <div class="mb-5 flex items-center gap-3 rounded-2xl bg-emerald-50 border border-emerald-200 p-4 text-xs font-bold text-emerald-800 shadow-xs">
                    <span>✅</span> {{ session('success') }}
                </div>
            @endif
            @if (session('error'))
                <div class="mb-5 flex items-center gap-3 rounded-2xl bg-rose-50 border border-rose-200 p-4 text-xs font-bold text-rose-800 shadow-xs">
                    <span>❌</span> {{ session('error') }}
                </div>
            @endif

            @yield('content')
        </main>
    </div>
</div>

<script>
    function openSidebar() {
        document.getElementById('sidebar').classList.add('open');
        document.getElementById('overlay').classList.remove('hidden');
    }
    function closeSidebar() {
        document.getElementById('sidebar').classList.remove('open');
        document.getElementById('overlay').classList.add('hidden');
    }
</script>

@stack('scripts')
</body>
</html>