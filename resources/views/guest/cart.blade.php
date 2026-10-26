<!doctype html>
<html lang="id">
<head><meta charset="utf-8"><meta name="viewport" content="width=device-width, initial-scale=1"><title>Keranjang - Kafe Kampus</title><script src="https://cdn.tailwindcss.com"></script><script>tailwind.config={theme:{extend:{colors:{navy:'#041562',vibrant:'#DA1212',cream:'#EEEEEE'}}}}</script></head>
<body class="min-h-screen bg-cream px-4 py-8 text-slate-800">
<main class="mx-auto max-w-2xl rounded-2xl bg-white p-6 shadow-sm">
    <a href="{{ route('catalog') }}" class="text-sm font-semibold text-navy">&larr; Kembali ke menu</a>
    <h1 class="mt-5 text-2xl font-bold text-navy">Keranjang Belanja</h1>
    @if ($items->isEmpty())
        <p class="mt-6 text-slate-500">Keranjang masih kosong.</p>
        <a href="{{ route('catalog') }}" class="mt-4 inline-block rounded-xl bg-navy px-4 py-2 font-semibold text-white">Pilih Produk</a>
    @else
        <form method="POST" action="{{ route('cart.update') }}" class="mt-6 space-y-4">
            @csrf
            @foreach ($items as $item)
                <div class="flex items-center justify-between gap-4 border-b pb-4">
                    <div><p class="font-semibold">{{ $item['product']->name }}</p><p class="text-sm text-slate-500">Rp {{ number_format($item['product']->price, 0, ',', '.') }}</p></div>
                    <div class="flex items-center gap-3"><input type="number" name="quantities[{{ $item['product']->id }}]" value="{{ $item['quantity'] }}" min="0" max="99" class="w-20 rounded-lg border-slate-300 p-2 text-center"><span class="w-28 text-right font-semibold">Rp {{ number_format($item['subtotal'], 0, ',', '.') }}</span></div>
                </div>
            @endforeach
            <button class="rounded-xl border border-navy px-4 py-2 font-semibold text-navy">Perbarui Keranjang</button>
        </form>
        <a href="{{ route('checkout') }}" class="mt-5 block w-full rounded-xl bg-vibrant px-4 py-3 text-center font-bold text-white">Lanjut Checkout</a>
    @endif
</main>
</body>
</html>
