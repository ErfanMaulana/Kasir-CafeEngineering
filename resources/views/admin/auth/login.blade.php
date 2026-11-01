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
        body { font-family: 'Plus Jakarta Sans', sans-serif; }
        .login-bg {
            background: linear-gradient(135deg, #041562 0%, #11468F 50%, #041562 100%);
        }
        .glass-card {
            background: rgba(255, 255, 255, 0.97);
            backdrop-filter: blur(20px);
        }
        .input-field {
            transition: border-color 0.2s, box-shadow 0.2s;
        }
        .input-field:focus {
            border-color: #11468F;
            box-shadow: 0 0 0 3px rgba(17, 70, 143, 0.12);
        }
        .btn-login {
            background: linear-gradient(135deg, #041562 0%, #11468F 100%);
            transition: opacity 0.2s, transform 0.15s;
        }
        .btn-login:hover { opacity: 0.92; }
        .btn-login:active { transform: scale(0.98); }

        /* Floating particles background */
        .particle {
            position: absolute;
            border-radius: 50%;
            background: rgba(255,255,255,0.06);
            animation: float 8s ease-in-out infinite;
        }
        @keyframes float {
            0%, 100% { transform: translateY(0px) rotate(0deg); }
            50% { transform: translateY(-20px) rotate(180deg); }
        }
    </style>
</head>
<body class="login-bg min-h-screen flex items-center justify-center px-4 py-10 relative overflow-hidden">

    {{-- Decorative particles --}}
    <div class="particle w-32 h-32 top-10 left-10" style="animation-delay: 0s;"></div>
    <div class="particle w-20 h-20 top-1/3 right-20" style="animation-delay: 2s;"></div>
    <div class="particle w-16 h-16 bottom-20 left-1/4" style="animation-delay: 4s;"></div>
    <div class="particle w-24 h-24 bottom-10 right-10" style="animation-delay: 1s;"></div>
    <div class="particle w-10 h-10 top-1/2 left-1/3" style="animation-delay: 3s;"></div>

    {{-- Decorative grid lines --}}
    <div class="absolute inset-0 opacity-5" style="background-image: linear-gradient(rgba(255,255,255,0.3) 1px, transparent 1px), linear-gradient(90deg, rgba(255,255,255,0.3) 1px, transparent 1px); background-size: 40px 40px;"></div>

    <div class="relative z-10 w-full max-w-md">

        {{-- Logo / Brand Header --}}
        <div class="text-center mb-8">
            <div class="inline-flex items-center justify-center w-16 h-16 rounded-2xl bg-white shadow-xl mb-4">
                <span class="text-navy font-black text-xl">EC</span>
            </div>
            <h1 class="text-2xl font-black text-white tracking-tight">Engineering Cafe</h1>
            <p class="text-blue-200 text-sm font-medium mt-1">Fakultas Teknik · Universitas Siliwangi</p>
        </div>

        {{-- Login Card --}}
        <div class="glass-card rounded-3xl shadow-2xl p-8">

            {{-- Card Header --}}
            <div class="text-center mb-7">
                <h2 class="text-xl font-black text-navy">Portal Staf Admin</h2>
                <p class="text-slate-500 text-sm mt-1">Masukkan kredensial akun admin Anda untuk melanjutkan</p>
            </div>

            {{-- Error Alert --}}
            @if ($errors->any())
                <div class="mb-5 flex items-start gap-3 rounded-2xl bg-red-50 border border-red-200 px-4 py-3.5">
                    <svg class="h-5 w-5 text-red-500 shrink-0 mt-0.5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
                    </svg>
                    <div>
                        @foreach ($errors->all() as $error)
                            <p class="text-red-700 text-sm font-medium">{{ $error }}</p>
                        @endforeach
                    </div>
                </div>
            @endif

            {{-- Login Form --}}
            <form method="POST" action="{{ route('admin.login.post') }}" class="space-y-5">
                @csrf

                {{-- Email Field --}}
                <div>
                    <label for="email" class="block text-xs font-bold text-slate-700 uppercase tracking-wider mb-2">
                        Email Admin
                    </label>
                    <div class="relative">
                        <div class="pointer-events-none absolute inset-y-0 left-0 flex items-center pl-4">
                            <svg class="h-4 w-4 text-slate-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"/>
                            </svg>
                        </div>
                        <input
                            type="email"
                            id="email"
                            name="email"
                            value="{{ old('email') }}"
                            placeholder="admin@engineeringcafe.unsil.ac.id"
                            autocomplete="email"
                            required
                            class="input-field w-full rounded-2xl border border-slate-200 bg-slate-50 py-3.5 pl-11 pr-4 text-sm font-medium text-slate-800 placeholder-slate-400 outline-none @error('email') border-red-300 bg-red-50 @enderror"
                        >
                    </div>
                </div>

                {{-- Password Field --}}
                <div>
                    <label for="password" class="block text-xs font-bold text-slate-700 uppercase tracking-wider mb-2">
                        Password
                    </label>
                    <div class="relative">
                        <div class="pointer-events-none absolute inset-y-0 left-0 flex items-center pl-4">
                            <svg class="h-4 w-4 text-slate-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
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
                            class="input-field w-full rounded-2xl border border-slate-200 bg-slate-50 py-3.5 pl-11 pr-12 text-sm font-medium text-slate-800 placeholder-slate-400 outline-none @error('password') border-red-300 bg-red-50 @enderror"
                        >
                        <button
                            type="button"
                            onclick="togglePassword()"
                            class="absolute inset-y-0 right-0 flex items-center pr-4 text-slate-400 hover:text-slate-600 transition"
                            tabindex="-1"
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
                    <p class="mt-2 text-[11px] text-slate-400 font-medium">
                        Password harus mengandung 1 huruf besar & 1 angka/simbol
                    </p>
                </div>

                {{-- Remember Me --}}
                <div class="flex items-center gap-2">
                    <input
                        type="checkbox"
                        id="remember"
                        name="remember"
                        class="h-4 w-4 rounded border-slate-300 text-navy accent-navy"
                    >
                    <label for="remember" class="text-sm text-slate-600 font-medium cursor-pointer">
                        Ingat saya di perangkat ini
                    </label>
                </div>

                {{-- Submit Button --}}
                <button
                    type="submit"
                    class="btn-login w-full rounded-2xl py-3.5 text-sm font-black text-white shadow-lg mt-2"
                >
                    Masuk Ke Panel Kasir →
                </button>
            </form>

            {{-- Divider --}}
            <div class="relative my-6">
                <div class="absolute inset-0 flex items-center">
                    <div class="w-full border-t border-slate-100"></div>
                </div>
                <div class="relative flex justify-center">
                    <span class="bg-white px-3 text-xs text-slate-400 font-medium">Akses Terbatas</span>
                </div>
            </div>

            {{-- Security Note --}}
            <div class="flex items-center gap-3 rounded-2xl bg-slate-50 border border-slate-100 px-4 py-3">
                <svg class="h-4 w-4 text-slate-400 shrink-0" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z"/>
                </svg>
                <p class="text-[11px] text-slate-500 font-medium leading-relaxed">
                    Halaman ini hanya dapat diakses oleh staf administrasi yang berwenang. Sesi akan diamankan secara otomatis.
                </p>
            </div>
        </div>

        {{-- Footer --}}
        <p class="text-center text-blue-200/70 text-xs font-medium mt-6">
            © {{ date('Y') }} Engineering Cafe · Fakultas Teknik · Universitas Siliwangi
        </p>

    </div>

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

