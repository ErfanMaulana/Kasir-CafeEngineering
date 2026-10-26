<!doctype html>
<html lang="id">
<head><meta charset="utf-8"><meta name="viewport" content="width=device-width, initial-scale=1"><meta http-equiv="refresh" content="15"><title>Status Pesanan - Kafe Kampus</title><script src="https://cdn.tailwindcss.com"></script><script>tailwind.config={theme:{extend:{colors:{navy:'#041562',cream:'#EEEEEE'}}}}</script></head>
<body class="min-h-screen bg-cream px-4 py-8 text-slate-800">
<main class="mx-auto max-w-lg">
    <section class="rounded-2xl bg-navy p-6 text-center text-white shadow-lg">
        <p class="text-sm tracking-wider text-blue-200">NOMOR ANTRIAN</p><p class="mt-2 text-6xl font-black">{{ $order->queue_number }}</p>
        <span class="mt-4 inline-block rounded-full bg-white/15 px-4 py-2 text-sm font-semibold capitalize">{{ str_replace('_', ' ', $order->order_status) }}</span>
    </section>
    <section class="mt-4 rounded-2xl bg-white p-6 shadow-sm">
        <div class="flex justify-between border-b pb-4"><span class="text-slate-500">Nama</span><strong>{{ $order->customer_name }}</strong></div>
        <div class="flex justify-between border-b py-4"><span class="text-slate-500">Tipe</span><strong>{{ $order->order_type === 'dine_in' ? 'Makan di tempat' : 'Dibawa pulang' }}</strong></div>
        <h2 class="mt-5 font-bold text-navy">Pesanan</h2>
        <ul class="mt-3 space-y-3">@foreach ($order->items as $item)
            <li class="flex justify-between gap-3"><span>{{ $item->quantity }}x {{ $item->product->name ?? 'Produk' }}</span><span>Rp {{ number_format($item->subtotal, 0, ',', '.') }}</span></li>
        @endforeach</ul>
        <div class="mt-5 flex justify-between border-t pt-4 text-lg font-bold text-navy"><span>Total</span><span>Rp {{ number_format($order->total_amount, 0, ',', '.') }}</span></div>
    </section>
    <p class="mt-5 text-center text-sm text-slate-500">Simpan halaman ini untuk melihat status pesananmu.</p>
</main>
</body>
</html>
