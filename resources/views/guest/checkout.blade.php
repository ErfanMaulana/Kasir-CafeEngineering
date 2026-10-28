<!doctype html>
<html lang="id">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Konfirmasi Checkout · Unsil Cafe Connect</title>
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
    </style>
</head>
<body class="min-h-screen bg-[#EEEEEE] text-slate-800 antialiased pb-16">

<header class="border-b border-slate-200 bg-white/95 backdrop-blur-md sticky top-0 z-20">
    <div class="mx-auto flex max-w-2xl items-center justify-between px-4 py-3.5">
        <a href="{{ route('cart') }}" class="flex items-center gap-2 text-xs font-bold text-royal hover:text-navy transition">
            <svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M15 19l-7-7 7-7"/></svg>
            <span>Kembali ke Keranjang</span>
        </a>
        <span class="text-xs font-bold text-slate-400 uppercase tracking-widest">Guest Checkout</span>
    </div>
</header>

<main class="mx-auto max-w-2xl px-4 py-6">

    <div class="border-b border-slate-200 pb-4">
        <span class="text-[11px] font-bold uppercase tracking-widest text-royal">Langkah Terakhir</span>
        <h1 class="text-2xl sm:text-3xl font-black text-navy">Konfirmasi Pesanan</h1>
        <p class="mt-1 text-xs sm:text-sm text-slate-500 font-medium">
            Pesanan Anda langsung diteruskan ke Kitchen Display System (KDS) dan diterbitkan nomor antrean.
        </p>
    </div>

    {{-- ALERT ERRORS --}}
    @if ($errors->any())
        <div class="mt-4 rounded-2xl border border-rose-200 bg-rose-50 p-4 text-xs font-bold text-rose-800 shadow-xs">
            <p class="text-sm font-black mb-1">Periksa kembali data Anda:</p>
            <ul class="list-inside list-disc space-y-1 font-semibold">
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <form method="POST" action="{{ route('checkout.process') }}" class="mt-6 space-y-5">
        @csrf

        {{-- 1. IDENTITAS PEMANGGIL --}}
        <section class="rounded-3xl bg-white p-5 sm:p-6 shadow-xs border border-slate-100">
            <div class="flex items-center gap-3">
                <span class="flex h-7 w-7 items-center justify-center rounded-lg bg-blue-100 text-xs font-black text-royal">1</span>
                <h2 class="font-black text-navy text-base">Nama Pemanggil</h2>
            </div>

            <div class="mt-4">
                <label for="customer_name" class="block text-xs font-bold text-slate-700">
                    Nama Pemesan / Panggilan <span class="text-vibrant">*</span>
                </label>
                <input id="customer_name" name="customer_name"
                       value="{{ old('customer_name') }}"
                       required maxlength="50"
                       placeholder="Contoh: Budi Prasetyo, Bu Rina, Andi FT"
                       class="mt-1.5 w-full rounded-2xl border border-slate-200 bg-slate-50 p-3.5 text-sm font-semibold text-slate-800 placeholder:text-slate-400 focus:border-royal focus:bg-white focus:outline-none focus:ring-2 focus:ring-royal/20 transition">
                <p class="mt-2 text-[11px] text-slate-400 font-medium">
                    Nama ini akan dipanggil oleh staf dapur / barista saat pesanan Anda selesai disiapkan.
                </p>
            </div>
        </section>

        {{-- 2. TIPE PESANAN (DINE-IN ATAU TAKEAWAY) --}}
        <section class="rounded-3xl bg-white p-5 sm:p-6 shadow-xs border border-slate-100">
            <div class="flex items-center gap-3">
                <span class="flex h-7 w-7 items-center justify-center rounded-lg bg-blue-100 text-xs font-black text-royal">2</span>
                <h2 class="font-black text-navy text-base">Tipe Pesanan</h2>
            </div>

            <div class="mt-4 grid gap-3 sm:grid-cols-2">
                <label class="relative flex cursor-pointer rounded-2xl border-2 p-4 transition has-[:checked]:border-royal has-[:checked]:bg-blue-50/60 border-slate-200">
                    <input type="radio" name="order_type" value="dine_in" class="mt-0.5 text-royal focus:ring-royal"
                           @checked(old('order_type') === 'dine_in')>
                    <div class="ml-3">
                        <span class="block font-black text-navy text-sm">Dine-in (Makan di Tempat)</span>
                        <span class="mt-0.5 block text-xs text-slate-500 font-medium">Santap di area meja kafe fakultas</span>
                    </div>
                </label>

                <label class="relative flex cursor-pointer rounded-2xl border-2 p-4 transition has-[:checked]:border-royal has-[:checked]:bg-blue-50/60 border-slate-200">
                    <input type="radio" name="order_type" value="takeaway" class="mt-0.5 text-royal focus:ring-royal"
                           @checked(old('order_type', 'takeaway') === 'takeaway')>
                    <div class="ml-3">
                        <span class="block font-black text-navy text-sm">Takeaway (Bawa Pulang)</span>
                        <span class="mt-0.5 block text-xs text-slate-500 font-medium">Bungkus untuk dibawa ke lab / ruang kelas</span>
                    </div>
                </label>
            </div>

            <div id="table-field" class="mt-4 border-t border-slate-100 pt-4 hidden">
                <label for="table_number" class="block text-xs font-bold text-slate-700">
                    Nomor Meja <span class="text-vibrant">*</span>
                </label>
                <input id="table_number" name="table_number"
                       value="{{ old('table_number') }}"
                       maxlength="20"
                       placeholder="Contoh: Meja 04, Hall B-02"
                       class="mt-1.5 w-full rounded-2xl border border-slate-200 bg-slate-50 p-3.5 text-sm font-semibold text-slate-800 placeholder:text-slate-400 focus:border-royal focus:bg-white focus:outline-none focus:ring-2 focus:ring-royal/20 transition">
                <p class="mt-1.5 text-[11px] text-slate-400 font-medium">
                    Lihat stiker nomor meja pada meja yang Anda tempati saat ini.
                </p>
            </div>
        </section>

        {{-- 3. METODE PEMBAYARAN --}}
        <section class="rounded-3xl bg-white p-5 sm:p-6 shadow-xs border border-slate-100">
            <div class="flex items-center gap-3">
                <span class="flex h-7 w-7 items-center justify-center rounded-lg bg-blue-100 text-xs font-black text-royal">3</span>
                <h2 class="font-black text-navy text-base">Metode Pembayaran</h2>
            </div>

            <label class="mt-4 flex cursor-pointer items-start gap-3.5 rounded-2xl border-2 border-royal bg-blue-50/50 p-4">
                <input type="radio" name="payment_method" value="qris" checked class="mt-1 text-royal focus:ring-royal">
                <div>
                    <div class="flex items-center gap-2">
                        <span class="font-black text-navy text-sm">QRIS Instant Settlement</span>
                        <span class="rounded bg-emerald-100 px-2 py-0.5 text-[10px] font-black text-emerald-800 uppercase">Resmi</span>
                    </div>
                    <p class="mt-1 text-xs text-slate-600 font-medium leading-relaxed">
                        Dapat discan menggunakan semua aplikasi e-Wallet (GoPay, OVO, ShopeePay, DANA) maupun Mobile Banking (BCA, Mandiri, BNI, BRI, BSI). Tunjukkan bukti pembayaran saat mengambil pesanan.
                    </p>
                </div>
            </label>
        </section>

        {{-- 4. RINGKASAN TAGIHAN --}}
        <section class="rounded-3xl bg-navy p-6 text-white shadow-xl">
            <div class="flex items-center justify-between border-b border-white/10 pb-3">
                <span class="text-xs font-bold uppercase tracking-wider text-blue-200">Total Pembayaran</span>
                <span class="text-xs font-semibold text-blue-200">{{ $cartCount }} Item Menu</span>
            </div>

            <div class="mt-4 flex items-baseline justify-between">
                <span class="text-sm font-medium text-blue-200">Total yang harus dibayar</span>
                <span class="text-3xl font-black text-white">
                    Rp {{ number_format($cartTotal, 0, ',', '.') }}
                </span>
            </div>

            <button type="submit"
                    class="mt-6 flex w-full items-center justify-center gap-2 rounded-2xl bg-vibrant px-5 py-4 text-center text-base font-black text-white shadow-lg shadow-red-950/40 hover:bg-red-700 active:scale-98 transition">
                <span>Konfirmasi &amp; Ambil Nomor Antrean</span>
                <svg class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M5 13l4 4L19 7"/></svg>
            </button>
        </section>

    </form>
</main>

<script>
document.addEventListener('DOMContentLoaded', () => {
    const tableField = document.getElementById('table-field');
    const tableInput = document.getElementById('table_number');
    const radios = document.querySelectorAll('input[name="order_type"]');

    function syncOrderType() {
        const selected = document.querySelector('input[name="order_type"]:checked')?.value;
        const isDineIn = selected === 'dine_in';
        if (isDineIn) {
            tableField.classList.remove('hidden');
            tableInput.required = true;
        } else {
            tableField.classList.add('hidden');
            tableInput.required = false;
        }
    }

    radios.forEach(r => r.addEventListener('change', syncOrderType));
    syncOrderType();
});
</script>

</body>
</html>
