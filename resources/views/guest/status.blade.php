<!doctype html>
<html lang="id">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta http-equiv="refresh" content="12">
    <title>Status Pesanan {{ $order->queue_number }} · Engineering Cafe</title>
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
    <div class="mx-auto flex max-w-xl items-center justify-between px-4 py-3.5">
        <a href="{{ route('catalog') }}" class="flex items-center gap-1.5 text-xs font-bold text-royal hover:text-navy transition">
            <svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M12 4v16m8-8H4"/></svg>
            <span>Pesan Menu Lain</span>
        </a>
        <div class="flex items-center gap-2">
            <span class="h-2 w-2 rounded-full bg-emerald-500 animate-ping"></span>
            <span class="text-xs font-bold text-slate-500">Live Status</span>
        </div>
    </div>
</header>

<main class="mx-auto max-w-xl px-4 py-6">

    @if (session('success'))
        <div class="mb-5 flex items-center gap-3 rounded-2xl border border-emerald-200 bg-emerald-50 p-4 text-xs font-bold text-emerald-800 shadow-xs">
            <span>✓</span>
            <span>{{ session('success') }}</span>
        </div>
    @endif

    {{-- BANNER PETUNJUK PEMBAYARAN TUNAI DI KASIR --}}
    @if ($order->payment_method === 'cash' && in_array($order->payment_status, ['unpaid', 'pending']))
        <div class="mb-5 rounded-2xl border-2 border-amber-300 bg-amber-50 p-4 text-xs font-semibold text-amber-950 shadow-sm flex items-start gap-3">
            <span class="text-2xl shrink-0 leading-none">⚠️</span>
            <div class="space-y-1">
                <p class="font-black text-sm text-amber-950">Menunggu Pembayaran Tunai di Kasir</p>
                <p class="leading-relaxed">
                    ⚠️ Harap lakukan pembayaran tunai sebesar <strong class="text-vibrant font-black text-sm">Rp {{ number_format($order->total_amount, 0, ',', '.') }}</strong> di Kasir untuk memproses pesanan Anda.
                </p>
                <p class="text-[11px] text-amber-700 font-medium">
                    Tunjukkan nomor antrean <span class="font-black text-navy">{{ $order->queue_number }}</span> atas nama <span class="font-black text-navy">{{ $order->customer_name }}</span> ke kasir kafe.
                </p>
            </div>
        </div>
    @elseif ($order->payment_method === 'qris' && in_array($order->payment_status, ['unpaid', 'pending']))
        <div class="mb-5 rounded-2xl border-2 border-blue-200 bg-blue-50 p-4 text-xs font-semibold text-blue-950 shadow-sm flex items-start gap-3">
            <span class="text-2xl shrink-0 leading-none">📱</span>
            <div class="space-y-1">
                <p class="font-black text-sm text-navy">Menunggu Pembayaran QRIS</p>
                <p class="leading-relaxed text-blue-900">
                    Silakan scan QRIS di counter kasir sebesar <strong class="text-vibrant font-black text-sm">Rp {{ number_format($order->total_amount, 0, ',', '.') }}</strong> untuk menyelesaikan transaksi pesanan Anda.
                </p>
            </div>
        </div>
    @endif

    {{-- KARTU UTAMA NOMOR ANTREAN --}}
    <section class="overflow-hidden rounded-3xl bg-gradient-to-b from-navy via-navy to-royal text-center text-white shadow-2xl">
        <div class="bg-royal/50 px-6 py-2.5 text-[11px] font-black uppercase tracking-widest text-blue-200 border-b border-white/10">
            Nomor Antrean Digital
        </div>

        <div class="p-7 sm:p-9">
            <p class="text-7xl sm:text-8xl font-black tracking-tight text-white drop-shadow-md">
                {{ $order->queue_number }}
            </p>

            <div class="mt-4">
                <span class="text-xs uppercase tracking-wider text-blue-200 font-bold">Nama Pemanggil</span>
                <p class="text-2xl font-black text-white mt-0.5">
                    {{ $order->customer_name }}
                </p>
            </div>

            @php
                $statusConfig = [
                    'pending'   => ['label' => 'Menunggu Pembayaran / Masuk Antrean', 'bg' => 'bg-amber-500/20 text-amber-300 border-amber-400/40', 'dot' => 'bg-amber-400'],
                    'preparing' => ['label' => 'Sedang Dimasak / Disiapkan Dapur', 'bg' => 'bg-sky-500/20 text-sky-300 border-sky-400/40', 'dot' => 'bg-sky-400'],
                    'ready'     => ['label' => 'Siap Diambil di Counter!', 'bg' => 'bg-emerald-500/30 text-emerald-300 border-emerald-400/50', 'dot' => 'bg-emerald-400'],
                    'completed' => ['label' => 'Pesanan Selesai', 'bg' => 'bg-slate-500/20 text-slate-300 border-slate-400/30', 'dot' => 'bg-slate-400'],
                    'cancelled' => ['label' => 'Pesanan Dibatalkan', 'bg' => 'bg-rose-500/20 text-rose-300 border-rose-400/40', 'dot' => 'bg-rose-400'],
                ];
                $currentStatus = $statusConfig[$order->order_status] ?? ['label' => ucfirst($order->order_status), 'bg' => 'bg-blue-500/20 text-white border-blue-400/30', 'dot' => 'bg-white'];
            @endphp

            <div class="mt-6 inline-flex items-center gap-2.5 rounded-full border px-4 py-2 text-xs font-black shadow-inner {{ $currentStatus['bg'] }}">
                <span class="h-2.5 w-2.5 rounded-full {{ $currentStatus['dot'] }} animate-pulse"></span>
                <span>{{ $currentStatus['label'] }}</span>
            </div>

            @if ($order->order_status === 'ready')
                <div class="mt-5 rounded-2xl bg-vibrant/90 p-3 text-xs font-black text-white shadow-lg animate-bounce">
                    🔔 Silakan menuju counter pickup untuk mengambil pesanan Anda!
                </div>
            @endif
        </div>
    </section>

    {{-- PROGRESS STEP TRACKER --}}
    <section class="mt-5 rounded-3xl bg-white p-5 shadow-xs border border-slate-100">
        <h2 class="text-xs font-bold uppercase tracking-widest text-slate-400 mb-4">Alur Pemrosesan Pesanan</h2>

        @php
            $steps = [
                'pending'   => ['step' => 1, 'name' => 'Masuk Antrean', 'desc' => 'Diterima sistem'],
                'preparing' => ['step' => 2, 'name' => 'Sedang Diproses', 'desc' => 'Dapur / Barista'],
                'ready'     => ['step' => 3, 'name' => 'Siap Diambil', 'desc' => 'Counter pickup'],
                'completed' => ['step' => 4, 'name' => 'Selesai', 'desc' => 'Selamat menikmati'],
            ];
            $orderLevels = ['pending' => 1, 'preparing' => 2, 'ready' => 3, 'completed' => 4, 'cancelled' => 0];
            $currentLevel = $orderLevels[$order->order_status] ?? 1;
        @endphp

        <div class="grid grid-cols-4 gap-2 text-center">
            @foreach ($steps as $key => $s)
                @php
                    $isPassed = $currentLevel >= $s['step'] && $order->order_status !== 'cancelled';
                    $isCurrent = $currentLevel === $s['step'] && $order->order_status !== 'cancelled';
                @endphp
                <div class="flex flex-col items-center">
                    <div class="flex h-8 w-8 items-center justify-center rounded-full text-xs font-black transition {{ $isPassed ? 'bg-royal text-white ring-4 ring-blue-100' : 'bg-slate-100 text-slate-400' }}">
                        @if ($isPassed && ! $isCurrent)
                            ✓
                        @else
                            {{ $s['step'] }}
                        @endif
                    </div>
                    <p class="mt-2 text-[11px] font-black {{ $isPassed ? 'text-navy' : 'text-slate-400' }} leading-tight">
                        {{ $s['name'] }}
                    </p>
                    <p class="text-[10px] text-slate-400 mt-0.5 hidden sm:block">
                        {{ $s['desc'] }}
                    </p>
                </div>
            @endforeach
        </div>
    </section>

    {{-- DETAIL METADATA & RINCIAN PESANAN --}}
    <section class="mt-5 rounded-3xl bg-white p-6 shadow-xs border border-slate-100">
        <div class="grid grid-cols-2 gap-4 border-b border-slate-100 pb-4 text-xs">
            <div>
                <span class="font-bold text-slate-400 uppercase tracking-wider text-[10px]">Tipe Pesanan</span>
                <p class="font-black text-navy text-sm mt-0.5">
                    {{ $order->order_type === 'dine_in' ? 'Dine-in · Meja ' . ($order->table_number ?: '-') : 'Takeaway (Bungkus)' }}
                </p>
            </div>
            <div>
                <span class="font-bold text-slate-400 uppercase tracking-wider text-[10px]">Metode Bayar</span>
                <p class="font-black text-navy text-sm mt-0.5">
                    @if (strtolower($order->payment_method ?? '') === 'cash')
                        Tunai (Kasir) · <span class="capitalize {{ in_array($order->payment_status, ['paid', 'lunas']) ? 'text-emerald-600' : 'text-amber-600' }}">{{ in_array($order->payment_status, ['unpaid', 'pending']) ? 'Belum Bayar' : $order->payment_status }}</span>
                    @else
                        QRIS · <span class="capitalize {{ in_array($order->payment_status, ['paid', 'lunas']) ? 'text-emerald-600' : 'text-amber-600' }}">{{ in_array($order->payment_status, ['unpaid', 'pending']) ? 'Belum Bayar' : $order->payment_status }}</span>
                    @endif
                </p>
            </div>
        </div>

        <h3 class="mt-5 font-black text-navy text-sm uppercase tracking-wider">
            Rincian Menu Pesanan
        </h3>

        <ul class="mt-3 divide-y divide-slate-100">
            @foreach ($order->items as $item)
                <li class="py-3">
                    <div class="flex items-start justify-between gap-3 text-xs">
                        <div>
                            <p class="font-bold text-navy">
                                <span class="text-royal font-black mr-1">{{ $item->quantity }}x</span>
                                {{ $item->product->name ?? 'Menu Produk' }}
                            </p>
                            @if ($item->notes)
                                <p class="mt-1 rounded-md bg-amber-50 px-2 py-1 text-[11px] font-medium text-amber-800 inline-block border border-amber-200/50">
                                    Catatan: {{ $item->notes }}
                                </p>
                            @endif
                        </div>
                        <span class="font-black text-navy shrink-0">
                            Rp {{ number_format($item->subtotal, 0, ',', '.') }}
                        </span>
                    </div>
                </li>
            @endforeach
        </ul>

        <div class="mt-4 flex items-baseline justify-between border-t border-slate-100 pt-4">
            <span class="text-xs font-bold text-slate-500">Total Pembayaran</span>
            <span class="text-xl font-black text-royal">
                Rp {{ number_format($order->total_amount, 0, ',', '.') }}
            </span>
        </div>
    </section>

    <p class="mt-6 text-center text-xs text-slate-400 font-medium">
        Halaman status ini otomatis memperbarui informasi setiap 12 detik.
    </p>

</main>

{{-- SweetAlert2 CDN & Theme Helper --}}
@include('partials.sweetalert')

</body>
</html>
