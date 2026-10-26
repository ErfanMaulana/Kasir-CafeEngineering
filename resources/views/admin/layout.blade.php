<!doctype html>
<html lang="id">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>@yield('title', 'Admin') · Unsil Cafe Connect</title>
    <script src="https://cdn.tailwindcss.com"><\/script>
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
    <\/script>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:ital,wght@0,300;0,400;0,500;0,600;0,700;0,800;1,400;1,600;1,700&display=swap" rel="stylesheet">
    <style>
        body, button, input, select, textarea { font-family: 'Plus Jakarta Sans', sans-serif; }
        /* Sidebar nav contrast improvements */
        .nav-link {
            transition: background 0.18s, color 0.18s;
            color: rgba(255,255,255,0.70);
        }
        .nav-link:hover {
            background: rgba(255,255,255,0.12);
            color: #ffffff;
        }
        .nav-link.active {
            background: rgba(255,255,255,0.18);
            color: #ffffff;
            box-shadow: inset 3px 0 0 #DA1212;
            font-weight: 700;
        }
        .nav-link svg { opacity: 0.65; }
        .nav-link:hover svg, .nav-link.active svg { opacity: 1; }
        ::-webkit-scrollbar { width: 5px; height: 5px; }
        ::-webkit-scrollbar-track { background: transparent; }
        ::-webkit-scrollbar-thumb { background: #11468F55; border-radius: 9px; }
        .stat-card { transition: transform 0.2s, box-shadow 0.2s; }
        .stat-card:hover { transform: translateY(-2px); box-shadow: 0 8px 30px rgba(4,21,98,.12); }
        #sidebar { transition: transform 0.3s ease; }
        @media (max-width: 1024px) {
            #sidebar { transform: translateX(-100%); position: fixed; z-index: 40; height: 100vh; }
            #sidebar.open { transform: translateX(0); }
        }
        @media (min-width: 1025px) {
            #overlay { display: none !important; }
        }
    </style>
    @stack('head')
</head>
<body class="bg-[#F0F2F9] text-slate-800 antialiased">

<div id="overlay" class="fixed inset-0 z-30 hidden bg-black/50 lg:hidden" onclick="closeSidebar()"></div>

<div class="flex min-h-screen">

    {{-- SIDEBAR --}}
    <aside id="sidebar" class="flex w-[220px] flex-shrink-0 flex-col bg-navy lg:static lg:translate-x-0">

        {{-- Brand --}}
        <div class="flex items-center gap-3 px-5 py-6">
            <div class="flex h-10 w-10 items-center justify-center rounded-xl bg-vibrant text-white text-lg font-black shadow-md">
                ☕
            </div>
            <div>
                <p class="text-[11px] font-bold uppercase tracking-widest" style="color:#7fb3f5">Admin Portal</p>
                <p class="text-[15px] font-black text-white leading-tight">Circuit Cafe</p>
            </div>
        </div>

        {{-- Location badge --}}
        <div class="mx-4 mb-5 flex items-center gap-2 rounded-lg px-3 py-2" style="background:rgba(255,255,255,0.13);">
            <span class="text-xs">📍</span>
            <span class="text-[11px] font-semibold" style="color:#a8ccf0">Eng. Hall B</span>
        </div>

        {{-- Navigation --}}
        <nav class="flex-1 space-y-0.5 px-3">

            <a href="{{ route('admin.dashboard') }}"
               class="nav-link flex items-center gap-3 rounded-xl px-3 py-2.5 text-[13px] {{ request()->routeIs('admin.dashboard') ? 'active' : '' }}">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 flex-shrink-0" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z"/></svg>
                <span>Sales &amp; Analytics</span>
            </a>

            <a href="{{ route('admin.products') }}"
               class="nav-link flex items-center gap-3 rounded-xl px-3 py-2.5 text-[13px] {{ request()->routeIs('admin.products') ? 'active' : '' }}">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 flex-shrink-0" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v10a2 2 0 002 2h8a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"/></svg>
                <span>Menu &amp; Inventory</span>
            </a>

            <a href="{{ route('admin.dashboard') }}"
               class="nav-link flex items-center gap-3 rounded-xl px-3 py-2.5 text-[13px]">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 flex-shrink-0" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 11V7a4 4 0 00-8 0v4M5 9h14l1 12H4L5 9z"/></svg>
                <span>Orders &amp; Transactions</span>
            </a>

            <a href="{{ route('admin.kitchen') }}"
               class="nav-link flex items-center gap-3 rounded-xl px-3 py-2.5 text-[13px] {{ request()->routeIs('admin.kitchen') ? 'active' : '' }}">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 flex-shrink-0" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0z"/></svg>
                <span>Kitchen Live Feed</span>
            </a>

            <div class="my-3 border-t" style="border-color:rgba(255,255,255,0.15)"></div>

            <a href="#"
               class="nav-link flex items-center gap-3 rounded-xl px-3 py-2.5 text-[13px]">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 flex-shrink-0" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10.325 4.317c.426-1.756 2.924-1.756 3.35 0a1.724 1.724 0 002.573 1.066c1.543-.94 3.31.826 2.37 2.37a1.724 1.724 0 001.065 2.572c1.756.426 1.756 2.924 0 3.35a1.724 1.724 0 00-1.066 2.573c.94 1.543-.826 3.31-2.37 2.37a1.724 1.724 0 00-2.572 1.065c-.426 1.756-2.924 1.756-3.35 0a1.724 1.724 0 00-2.573-1.066c-1.543.94-3.31-.826-2.37-2.37a1.724 1.724 0 00-1.065-2.572c-1.756-.426-1.756-2.924 0-3.35a1.724 1.724 0 001.066-2.573c-.94-1.543.826-3.31 2.37-2.37.996.608 2.296.07 2.572-1.065z"/><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/></svg>
                <span>Settings</span>
            </a>
        </nav>

        {{-- User footer --}}
        <div class="flex items-center justify-between px-4 py-4" style="border-top:1px solid rgba(255,255,255,0.15)">
            <div class="flex items-center gap-3">
                <div class="flex h-9 w-9 items-center justify-center rounded-full bg-royal text-white font-bold text-sm ring-2" style="ring-color:rgba(255,255,255,0.25)">
                    AV
                </div>
                <div>
                    <p class="text-[13px] font-bold text-white leading-tight">Alex Vance</p>
                    <p class="text-[10px] font-bold uppercase tracking-wider" style="color:#7fb3f5">Manager</p>
                </div>
            </div>
            <a href="{{ route('catalog') }}" class="transition-colors" style="color:#7fb3f5" onmouseover="this.style.color='#ffffff'" onmouseout="this.style.color='#7fb3f5'" title="Kembali ke Toko">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a2 2 0 01-2 2H5a2 2 0 01-2-2V7a2 2 0 012-2h6a2 2 0 012 2v1"/></svg>
            </a>
        </div>
    </aside>

    {{-- MAIN AREA --}}
    <div class="flex flex-1 flex-col min-w-0">

        {{-- Top bar --}}
        <header class="flex items-center justify-between border-b border-slate-200 bg-white px-6 py-3 shadow-sm">
            <div class="flex items-center gap-4">
                <button onclick="openSidebar()" class="lg:hidden rounded-lg p-2 hover:bg-slate-100">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16"/></svg>
                </button>

                @hasSection('breadcrumb')
                    @yield('breadcrumb')
                @else
                    <div>
                        <p class="text-[11px] font-bold uppercase tracking-widest text-slate-400">@yield('page_label', 'Admin')</p>
                        <h1 class="text-xl font-black text-navy">@yield('page_title', 'Dashboard')</h1>
                    </div>
                @endif
            </div>

            <div class="flex items-center gap-3">
                <div class="hidden items-center gap-2 rounded-full bg-green-50 border border-green-200 px-3 py-1.5 sm:flex">
                    <span class="relative flex h-2 w-2">
                        <span class="absolute inline-flex h-full w-full animate-ping rounded-full bg-green-400 opacity-75"></span>
                        <span class="relative inline-flex h-2 w-2 rounded-full bg-green-500"></span>
                    </span>
                    <span class="text-[11px] font-bold text-green-700">Live Store Stream</span>
                </div>
                <button class="rounded-lg p-2 text-slate-400 hover:bg-slate-100 hover:text-slate-600 transition-colors">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/></svg>
                </button>
                <button class="relative rounded-lg p-2 text-slate-400 hover:bg-slate-100 hover:text-slate-600 transition-colors">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 17h5l-1.405-1.405A2.032 2.032 0 0118 14.158V11a6.002 6.002 0 00-4-5.659V5a2 2 0 10-4 0v.341C7.67 6.165 6 8.388 6 11v3.159c0 .538-.214 1.055-.595 1.436L4 17h5m6 0v1a3 3 0 11-6 0v-1m6 0H9"/></svg>
                    <span class="absolute right-1.5 top-1.5 h-2 w-2 rounded-full bg-vibrant"></span>
                </button>
                <div class="flex h-9 w-9 items-center justify-center rounded-full bg-royal text-white font-bold text-sm shadow">
                    AV
                </div>
            </div>
        </header>

        {{-- Page content --}}
        <main class="flex-1 overflow-auto p-5 lg:p-6">
            @if (session('success'))
                <div class="mb-4 flex items-center gap-3 rounded-xl bg-green-50 border border-green-200 p-4 text-sm text-green-800">
                    <span>✅</span> {{ session('success') }}
                </div>
            @endif
            @if (session('error'))
                <div class="mb-4 flex items-center gap-3 rounded-xl bg-red-50 border border-red-200 p-4 text-sm text-red-800">
                    <span>❌</span> {{ session('error') }}
                </div>
            @endif
            @if ($errors->any())
                <div class="mb-4 rounded-xl bg-red-50 border border-red-200 p-4 text-sm text-red-800">
                    <ul class="list-inside list-disc space-y-1">
                        @foreach ($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
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