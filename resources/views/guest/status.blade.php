<!doctype html>
<html lang="id">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta http-equiv="refresh" content="15">
    <title>Status Pesanan {{ $order->queue_number }}</title>
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
@php($statusLabel = ['pending' => 'Menunggu Pembayaran', 'preparing' => 'Sedang Dimasak', 'ready' => 'Siap Diambil', 'completed' => 'Selesai', 'cancelled' => 'Dibatalkan'][$order->order_status] ?? ucfirst($order->order_status))
<body class="min-h-screen bg-cream px-4 py-6 text-slate-800"><main class="mx-auto max-w-lg"><a href="{{ route('catalog') }}" class="text-sm font-bold text-navy">&larr; Pesan lagi</a><section class="mt-5 overflow-hidden rounded-3xl bg-navy text-center text-white shadow-xl"><div class="bg-royal px-6 py-3 text-xs font-bold tracking-widest">NOMOR ANTREAN</div><div class="px-6 py-7"><p class="text-7xl font-black tracking-tight">{{ $order->queue_number }}</p><p class="mt-3 text-lg font-bold">{{ $order->customer_name }}</p><span class="mt-5 inline-flex items-center gap-2 rounded-full bg-white/15 px-4 py-2 text-sm font-bold"><span class="h-2 w-2 animate-pulse rounded-full bg-green-300"></span>{{ $statusLabel }}</span></div></section>
<section class="mt-4 rounded-2xl bg-white p-5 shadow-sm"><div class="grid grid-cols-2 gap-4 border-b pb-4"><div><p class="text-xs font-bold uppercase text-slate-400">Tipe</p><p class="mt-1 font-bold text-navy">{{ $order->order_type === 'dine_in' ? 'Dine-in · Meja '.$order->table_number : 'Takeaway' }}</p></div><div><p class="text-xs font-bold uppercase text-slate-400">Pembayaran</p><p class="mt-1 font-bold text-navy">QRIS · {{ ucfirst($order->payment_status) }}</p></div></div><h2 class="mt-5 font-black text-navy">Rincian pesanan</h2><ul class="mt-3 space-y-4">@foreach ($order->items as $item)<li><div class="flex justify-between gap-3"><span><b>{{ $item->quantity }}x</b> {{ $item->product->name ?? 'Produk' }}</span><span class="font-bold">Rp {{ number_format($item->subtotal, 0, ',', '.') }}</span></div>@if ($item->notes)<p class="mt-1 text-xs italic text-slate-500">Catatan: {{ $item->notes }}</p>@endif</li>@endforeach</ul><div class="mt-5 flex justify-between border-t pt-4 text-xl font-black text-navy"><span>Total bayar</span><span>Rp {{ number_format($order->total_amount, 0, ',', '.') }}</span></div></section><p class="mt-5 text-center text-sm text-slate-500">Halaman ini diperbarui otomatis setiap 15 detik.</p></main></body></html>
