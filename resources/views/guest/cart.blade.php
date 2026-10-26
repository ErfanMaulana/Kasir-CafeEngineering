<!doctype html>
<html lang="id">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Keranjang Pesanan</title>
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
<body class="min-h-screen bg-cream text-slate-800"><main class="mx-auto max-w-3xl px-4 py-6"><a href="{{ route('catalog') }}" class="text-sm font-bold text-navy">&larr; Kembali ke menu</a><div class="mt-6 flex items-end justify-between"><div><p class="text-xs font-bold uppercase tracking-widest text-royal">Pesananmu</p><h1 class="mt-1 text-3xl font-black text-navy">Keranjang</h1></div><span class="rounded-full bg-white px-3 py-2 text-sm font-bold text-slate-500">{{ $items->sum('quantity') }} item</span></div>
@if (session('success'))<p class="mt-5 rounded-xl bg-green-100 p-3 text-green-800">{{ session('success') }}</p>@endif
@if ($items->isEmpty())<section class="mt-6 rounded-2xl bg-white p-8 text-center shadow-sm"><div class="text-5xl">🛒</div><h2 class="mt-4 text-xl font-black text-navy">Keranjang masih kosong</h2><a href="{{ route('catalog') }}" class="mt-5 inline-block rounded-xl bg-royal px-5 py-3 font-bold text-white">Pilih Menu</a></section>
@else
<form method="POST" action="{{ route('cart.update') }}" class="mt-6 space-y-4">@csrf
@foreach ($items as $item)<article class="rounded-2xl bg-white p-4 shadow-sm"><div class="flex gap-4"><div class="flex h-20 w-20 shrink-0 items-center justify-center rounded-xl bg-cream text-3xl">☕</div><div class="min-w-0 flex-1"><div class="flex justify-between gap-3"><div><h2 class="font-bold text-navy">{{ $item['product']->name }}</h2><p class="text-sm text-slate-500">Rp {{ number_format($item['product']->price, 0, ',', '.') }} / item</p></div><p class="font-black text-royal">Rp {{ number_format($item['subtotal'], 0, ',', '.') }}</p></div><div class="mt-4 flex items-center justify-between gap-3"><div class="flex items-center rounded-xl border border-slate-200"><button type="button" class="quantity-button px-3 py-2 text-lg font-bold text-navy" data-target="quantity-{{ $item['product']->id }}" data-action="minus">−</button><input id="quantity-{{ $item['product']->id }}" name="quantities[{{ $item['product']->id }}]" value="{{ $item['quantity'] }}" min="0" max="99" class="w-12 border-0 p-2 text-center font-bold focus:ring-0"><button type="button" class="quantity-button px-3 py-2 text-lg font-bold text-navy" data-target="quantity-{{ $item['product']->id }}" data-action="plus">+</button></div><button type="submit" form="remove-{{ $item['product']->id }}" class="text-sm font-bold text-vibrant">Hapus</button></div><label class="mt-4 block text-xs font-bold uppercase tracking-wide text-slate-500" for="notes-{{ $item['product']->id }}">Catatan khusus</label><input id="notes-{{ $item['product']->id }}" name="notes[{{ $item['product']->id }}]" value="{{ $item['notes'] }}" maxlength="500" placeholder="Contoh: Es sedikit, pedas" class="mt-1 w-full rounded-xl border-slate-200 bg-slate-50 p-3 text-sm"></div></div></article>@endforeach
<button class="w-full rounded-xl border-2 border-navy bg-white px-4 py-3 font-bold text-navy">Simpan Perubahan</button></form>
@foreach ($items as $item)<form id="remove-{{ $item['product']->id }}" method="POST" action="{{ route('cart.remove') }}" class="hidden">@csrf<input type="hidden" name="product_id" value="{{ $item['product']->id }}"></form>@endforeach
<section class="mt-5 rounded-2xl bg-navy p-5 text-white"><div class="flex justify-between text-sm text-blue-200"><span>Subtotal pesanan</span><span>Rp {{ number_format($items->sum('subtotal'), 0, ',', '.') }}</span></div><div class="mt-4 flex justify-between border-t border-white/20 pt-4 text-xl font-black"><span>Total</span><span>Rp {{ number_format($items->sum('subtotal'), 0, ',', '.') }}</span></div><a href="{{ route('checkout') }}" class="mt-5 block rounded-xl bg-vibrant px-4 py-3 text-center font-black">Lanjut ke Checkout</a></section>
@endif</main>
<script>document.querySelectorAll('.quantity-button').forEach((button) => button.addEventListener('click', () => { const input = document.getElementById(button.dataset.target); const next = Number(input.value) + (button.dataset.action === 'plus' ? 1 : -1); input.value = Math.max(0, Math.min(99, next)); }));</script>
</body></html>
