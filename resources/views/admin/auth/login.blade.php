<!doctype html>
<html lang="id">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Login Admin · Engineering Cafe</title>
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
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <style>
        body, button, input, select, textarea { font-family: 'Plus Jakarta Sans', sans-serif; }
        .input-underline {
            transition: all 0.2s ease-in-out;
        }
        .input-underline:focus {
            border-color: #11468F;
            box-shadow: 0 4px 12px -2px rgba(17, 70, 143, 0.12);
        }
    </style>
</head>
<body class="min-h-screen w-full bg-white lg:bg-slate-100 flex items-center justify-center p-0 lg:p-8 antialiased selection:bg-blue-600 selection:text-white">

    {{-- Main Container Card --}}
    <div class="w-full min-h-screen lg:min-h-[540px] lg:max-w-4xl bg-white rounded-none lg:rounded-3xl shadow-none lg:shadow-2xl overflow-hidden grid grid-cols-1 lg:grid-cols-12 relative">

        {{-- ======================================================== --}}
        {{-- PANEL KIRI: BRANDING SIDE (5 Kolom Desktop / Top Mobile)  --}}
        {{-- ======================================================== --}}
        <div class="lg:col-span-5 bg-gradient-to-br from-[#041156] via-[#11468F] to-blue-600 text-white relative flex flex-col justify-between p-6 sm:p-8 lg:p-10 overflow-hidden min-h-[280px] lg:min-h-full">

            {{-- Lingkaran dekoratif latar belakang --}}
            <div class="absolute -top-16 -left-16 w-56 h-56 rounded-full bg-white/5 blur-xl pointer-events-none"></div>
            <div class="absolute -bottom-20 -right-10 w-64 h-64 rounded-full bg-blue-400/10 blur-2xl pointer-events-none"></div>

            {{-- Konten Atas / Tengah --}}
            <div class="relative z-20 flex flex-col items-center text-center my-auto pt-2 lg:pt-4">
                <span class="text-xs sm:text-sm font-semibold tracking-wider text-blue-200/90 uppercase mb-3 sm:mb-4">
                    Welcome to
                </span>

                {{-- Logo Bundar Bersinar --}}
                <div class="relative group my-2">
                    <div class="absolute -inset-1 rounded-full bg-white/20 blur group-hover:bg-white/30 transition duration-300"></div>
                    <div class="relative flex h-20 w-20 sm:h-24 sm:w-24 items-center justify-center rounded-full bg-white shadow-xl">
                        <svg class="h-10 w-10 sm:h-12 sm:w-12 text-[#11468F]" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                            <path d="M18 8h1a4 4 0 0 1 0 8h-1"></path>
                            <path d="M2 8h16v9a4 4 0 0 1-4 4H6a4 4 0 0 1-4-4V8z"></path>
                            <line x1="6" y1="1" x2="6" y2="4"></line>
                            <line x1="10" y1="1" x2="10" y2="4"></line>
                            <line x1="14" y1="1" x2="14" y2="4"></line>
                        </svg>
                    </div>
                </div>

                <h1 class="text-2xl sm:text-3xl font-extrabold text-white tracking-tight mt-3">
                    Engineering Cafe
                </h1>

                <p class="text-xs sm:text-sm text-blue-100/85 font-normal max-w-xs leading-relaxed mt-2.5">
                    Sistem Manajemen Kasir &amp; Kitchen Display System
                </p>
            </div>

            {{-- Footer Sisi Kiri --}}
            <div class="relative z-20 pt-4 pb-2 text-center lg:text-left border-t border-white/10 lg:border-none">
                <p class="text-[10px] tracking-widest uppercase font-semibold text-blue-200/70">
                    Fakultas Teknik <span class="mx-1">|</span> Universitas Siliwangi
                </p>
            </div>

            {{-- ======================================================= --}}
            {{-- WAVY CLOUD SVG DIVIDER (Desktop: Kanan / Mobile: Bawah) --}}
            {{-- ======================================================= --}}

            {{-- Kurva Desktop (Sisi Kanan Panel Biru menumpuk ke putih) --}}
            <svg class="hidden lg:block absolute top-0 -right-[1px] h-full w-24 pointer-events-none z-10" viewBox="0 0 120 700" preserveAspectRatio="none">
                {{-- Lapisan 1: Awan tipis transparan --}}
                <path d="M0,0 C45,110 15,220 50,350 C85,480 30,580 60,700 L120,700 L120,0 Z" fill="rgba(255,255,255,0.18)"/>
                {{-- Lapisan 2: Awan sedang transparan --}}
                <path d="M25,0 C68,105 38,235 72,360 C102,475 58,585 88,700 L120,700 L120,0 Z" fill="rgba(255,255,255,0.32)"/>
                {{-- Lapisan 3: Putih solid bersatu mulus dengan form sisi kanan --}}
                <path d="M50,0 C88,95 62,205 96,315 C126,425 78,535 106,625 C115,660 112,685 120,700 L120,0 Z" fill="#ffffff"/>
            </svg>

            {{-- Kurva Mobile (Sisi Bawah Panel Biru menumpuk ke form putih) --}}
            <svg class="lg:hidden absolute bottom-0 left-0 w-full h-10 sm:h-12 pointer-events-none z-10 -mb-[1px]" viewBox="0 0 1200 120" preserveAspectRatio="none">
                {{-- Lapisan 1: Awan tipis transparan --}}
                <path d="M0,35 C160,85 360,10 520,50 C680,85 880,15 1020,55 C1110,75 1170,45 1200,35 L1200,120 L0,120 Z" fill="rgba(255,255,255,0.18)"/>
                {{-- Lapisan 2: Awan sedang transparan --}}
                <path d="M0,55 C210,15 410,85 610,40 C810,85 1010,25 1200,65 L1200,120 L0,120 Z" fill="rgba(255,255,255,0.32)"/>
                {{-- Lapisan 3: Putih solid bersatu mulus dengan card form di bawah --}}
                <path d="M0,75 C190,45 370,95 550,65 C730,35 910,85 1090,55 C1145,48 1180,60 1200,70 L1200,120 L0,120 Z" fill="#ffffff"/>
            </svg>
        </div>

        {{-- ======================================================== --}}
        {{-- PANEL KANAN: LOGIN FORM SIDE (7 Kolom Desktop)           --}}
        {{-- ======================================================== --}}
        <div class="lg:col-span-7 bg-white p-6 sm:p-8 lg:p-10 flex flex-col justify-center relative z-20">

            {{-- Header Form --}}
            <div class="mb-6 lg:mb-8 text-center lg:text-left">
                <h2 class="text-2xl sm:text-3xl font-extrabold text-slate-900 tracking-tight">
                    Login Admin
                </h2>
                <p class="text-xs sm:text-sm text-slate-500 mt-1 font-medium">
                    Masukkan akun untuk mengakses panel Admin
                </p>
            </div>

            {{-- Alert Error Blade --}}
            @if ($errors->any())
                <div class="mb-5 flex items-start gap-3 rounded-2xl bg-rose-50 border border-rose-200 p-3.5 sm:p-4 animate-shake">
                    <svg class="h-5 w-5 text-rose-500 shrink-0 mt-0.5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"/>
                    </svg>
                    <div class="text-xs sm:text-sm text-rose-700 font-medium">
                        @foreach ($errors->all() as $error)
                            <p>{{ $error }}</p>
                        @endforeach
                    </div>
                </div>
            @endif

            {{-- Form Login --}}
            <form method="POST" action="{{ request()->getPathInfo() }}" class="space-y-5">
                @csrf

                {{-- Field 1: Username / E-mail Address --}}
                <div class="space-y-1.5">
                    <label for="username" class="block text-xs font-bold text-slate-600 uppercase tracking-wider">
                        E-mail Address
                    </label>
                    <div class="relative">
                        <div class="pointer-events-none absolute inset-y-0 left-0 flex items-center pl-3.5 text-slate-400">
                            <svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 12a4 4 0 10-8 0 4 4 0 008 0zm0 0v1.5a2.5 2.5 0 005 0V12a9 9 0 10-9 9m4.5-1.206a8.959 8.959 0 01-4.5 1.207"/>
                            </svg>
                        </div>
                        <input
                            type="text"
                            id="username"
                            name="username"
                            value="{{ old('username', old('email')) }}"
                            placeholder="masukkan email anda"
                            autocomplete="username"
                            required
                            class="input-underline w-full rounded-xl border border-slate-200 bg-slate-50/70 py-3 pl-10 pr-4 text-sm font-semibold text-slate-800 placeholder-slate-400 focus:bg-white focus:outline-none @error('username') border-rose-300 bg-rose-50/50 @enderror"
                        >
                    </div>
                </div>

                {{-- Field 2: Password --}}
                <div class="space-y-1.5">
                    <div class="flex items-center justify-between">
                        <label for="password" class="block text-xs font-bold text-slate-600 uppercase tracking-wider">
                            Password
                        </label>
                    </div>
                    <div class="relative">
                        <div class="pointer-events-none absolute inset-y-0 left-0 flex items-center pl-3.5 text-slate-400">
                            <svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z"/>
                            </svg>
                        </div>
                        <input
                            type="password"
                            id="password"
                            name="password"
                            placeholder="••••••••••••"
                            autocomplete="current-password"
                            required
                            class="input-underline w-full rounded-xl border border-slate-200 bg-slate-50/70 py-3 pl-10 pr-11 text-sm font-semibold text-slate-800 placeholder-slate-400 focus:bg-white focus:outline-none @error('password') border-rose-300 bg-rose-50/50 @enderror"
                        >
                        <button
                            type="button"
                            onclick="togglePassword()"
                            class="absolute inset-y-0 right-0 flex items-center pr-3.5 text-slate-400 hover:text-slate-600 focus:outline-none transition"
                            tabindex="-1"
                            title="Tampilkan / Sembunyikan Password"
                        >
                            <svg id="eye-icon" class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/>
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/>
                            </svg>
                            <svg id="eye-off-icon" class="h-4 w-4 hidden" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13.875 18.825A10.05 10.05 0 0112 19c-4.478 0-8.268-2.943-9.543-7a9.97 9.97 0 011.563-3.029m5.858.908a3 3 0 114.243 4.243M9.878 9.878l4.242 4.242M9.88 9.88l-3.29-3.29m7.532 7.532l3.29 3.29M3 3l3.59 3.59m0 0A9.953 9.953 0 0112 5c4.478 0 8.268 2.943 9.543 7a10.025 10.025 0 01-4.132 5.411m0 0L21 21"/>
                            </svg>
                        </button>
                    </div>
                    <p class="text-[11px] text-slate-400 font-medium">
                        Kombinasi minimal 1 huruf besar dan 1 angka/simbol.
                    </p>
                </div>

                {{-- Checkbox Remember Me --}}
                <div class="flex items-center justify-between pt-1">
                    <label class="flex items-center gap-2 cursor-pointer select-none">
                        <input
                            type="checkbox"
                            name="remember"
                            class="h-4 w-4 rounded border-slate-300 text-blue-600 focus:ring-blue-500 accent-blue-600"
                        >
                        <span class="text-xs font-semibold text-slate-600">Ingat sesi saya</span>
                    </label>

                    <a href="{{ route('catalog') }}" class="text-xs font-bold text-blue-600 hover:text-blue-800 transition">
                        Buka Menu Pembeli &rarr;
                    </a>
                </div>

                {{-- Action Buttons --}}
                <div class="pt-3">
                    <button
                        type="submit"
                        class="w-full inline-flex items-center justify-center rounded-xl sm:rounded-full bg-gradient-to-r from-blue-600 to-[#11468F] py-3.5 px-6 text-sm font-extrabold text-white shadow-lg shadow-blue-500/25 hover:shadow-blue-500/40 hover:from-blue-700 hover:to-[#041562] hover:scale-[1.01] active:scale-[0.99] transition-all duration-200"
                    >
                        <span>Sign In ke Panel Admin</span>
                        <svg class="ml-2 h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M14 5l7 7m0 0l-7 7m7-7H3"/>
                        </svg>
                    </button>
                </div>
            </form>

            {{-- Footer Info --}}
            <div class="mt-6 pt-4 border-t border-slate-100 flex items-center justify-between text-[11px] text-slate-400">
                <span>&copy; {{ date('Y') }} Engineering Cafe</span>
                <span class="font-semibold text-slate-500">FT &middot; Mugarsari</span>
            </div>

        </div>

    </div>

    {{-- Script Toggle Password & SweetAlert2 --}}
    <script>
        function togglePassword() {
            const input = document.getElementById('password');
            const eyeIcon = document.getElementById('eye-icon');
            const eyeOffIcon = document.getElementById('eye-off-icon');

            if (input.type === 'password') {
                input.type = 'text';
                eyeIcon.classList.add('hidden');
                eyeOffIcon.classList.remove('hidden');
            } else {
                input.type = 'password';
                eyeIcon.classList.remove('hidden');
                eyeOffIcon.classList.add('hidden');
            }
        }

        @if (session('success'))
            const Toast = Swal.mixin({
                toast: true,
                position: 'top-end',
                showConfirmButton: false,
                timer: 3500,
                timerProgressBar: true,
                customClass: {
                    popup: 'rounded-2xl shadow-xl text-sm font-semibold',
                },
            });
            Toast.fire({
                icon: 'success',
                title: '{{ addslashes(session('success')) }}',
            });
        @endif
    </script>
</body>
</html>
