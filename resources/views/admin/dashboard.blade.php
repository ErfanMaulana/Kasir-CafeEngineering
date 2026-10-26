<!doctype html>
<html lang="id">
<head><meta charset="utf-8"><meta name="viewport" content="width=device-width, initial-scale=1"><title>Dashboard Admin - Kafe Kampus</title><script src="https://cdn.tailwindcss.com"></script><script>tailwind.config={theme:{extend:{colors:{navy:'#041562',royal:'#11468F',vibrant:'#DA1212',cream:'#EEEEEE'}}}}</script></head>
<body class="min-h-screen bg-cream text-slate-800">
<header class="bg-navy px-4 py-5 text-white"><div class="mx-auto flex max-w-6xl items-center justify-between"><div><p class="text-sm text-blue-200">KAFE KAMPUS</p><h1 class="text-2xl font-bold">Dashboard Admin</h1></div><nav class="flex gap-3 text-sm"><a href="{{ route('admin.products') }}" class="rounded-lg bg-white/10 px-3 py-2">Produk</a><a href="{{ route('admin.kitchen') }}" class="rounded-lg bg-vibrant px-3 py-2 font-semibold">Kitchen Display</a></nav></div></header>
<main class="mx-auto max-w-6xl space-y-6 px-4 py-6">
    @if (session('success'))<p class="rounded-lg bg-green-100 p-3 text-green-800">{{ session('success') }}</p>@endif
    <div class="grid gap-4 sm:grid-cols-3">
        <div class="rounded-2xl bg-white p-5 shadow-sm"><p class="text-sm text-slate-500">Omzet hari ini</p><p class="mt-2 text-2xl font-black text-navy">Rp {{ number_format($revenue, 0, ',', '.') }}</p></div>
        <div class="rounded-2xl bg-white p-5 shadow-sm"><p class="text-sm text-slate-500">Pesanan hari ini</p><p class="mt-2 text-2xl font-black text-royal">{{ $orderCount }}</p></div>
        <div class="rounded-2xl bg-white p-5 shadow-sm"><p class="text-sm text-slate-500">Pesanan aktif</p><p class="mt-2 text-2xl font-black text-vibrant">{{ $activeCount }}</p></div>
    </div>
    <section class="rounded-2xl bg-white p-5 shadow-sm"><div class="flex items-center justify-between"><h2 class="text-lg font-bold text-navy">Pesanan terbaru</h2><a href="{{ route('admin.kitchen') }}" class="font-semibold text-royal">Buka KDS &rarr;</a></div>
        <div class="mt-4 overflow-x-auto"><table class="w-full text-left text-sm"><thead><tr class="border-b text-slate-500"><th class="p-3">Antrean</th><th class="p-3">Nama</th><th class="p-3">Total</th><th class="p-3">Status</th></tr></thead><tbody>@forelse ($recentOrders as $order)<tr class="border-b last:border-0"><td class="p-3 font-bold">{{ $order->queue_number }}</td><td class="p-3">{{ $order->customer_name }}</td><td class="p-3">Rp {{ number_format($order->total_amount, 0, ',', '.') }}</td><td class="p-3 capitalize">{{ str_replace('_', ' ', $order->order_status) }}</td></tr>@empty<tr><td colspan="4" class="p-4 text-center text-slate-500">Belum ada pesanan.</td></tr>@endforelse</tbody></table></div>
    </section>
</main>
</body>
</html>
