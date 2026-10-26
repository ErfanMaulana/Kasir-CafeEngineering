<!doctype html>
<html lang="id">
<head>
    <meta charset="utf-8"><meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Kafe Kampus</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <script>tailwind.config={theme:{extend:{colors:{navy:'#041562',royal:'#11468F',vibrant:'#DA1212',cream:'#EEEEEE'}}}}</script>
</head>
<body class="min-h-screen bg-cream pb-28 text-slate-800">
<header class="bg-navy px-4 py-6 text-white"><div class="mx-auto max-w-5xl">
    <p class="text-sm text-blue-200">UNIVERSITY CAFE</p><h1 class="text-2xl font-bold">Pesan makanan favoritmu</h1>
</div></header>
<main class="mx-auto max-w-5xl space-y-8 px-4 py-6">
    @if (session('success'))<p class="rounded-lg bg-green-100 p-3 text-green-800">{{ session('success') }}</p>@endif
    @if (session('error'))<p class="rounded-lg bg-red-100 p-3 text-red-800">{{ session('error') }}</p>@endif
    @foreach ($categories as $category)
        <section><h2 class="mb-3 text-xl font-bold text-navy">{{ $category->name }}</h2>
            <div class="grid grid-cols-1 gap-4 sm:grid-cols-2 lg:grid-cols-3">
                @foreach ($category->products as $product)
                    <article class="rounded-2xl bg-white p-4 shadow-sm">
                        @if ($product->image)<img src="{{ asset('storage/'.$product->image) }}" alt="{{ $product->name }}" class="mb-3 h-40 w-full rounded-xl object-cover">@endif
                        <h3 class="font-semibold">{{ $product->name }}</h3>
                        <p class="mt-1 font-bold text-royal">Rp {{ number_format($product->price, 0, ',', '.') }}</p>
                        <form method="POST" action="{{ route('cart.add', $product) }}" class="mt-3">@csrf
                            <button class="w-full rounded-xl bg-royal px-4 py-2 font-semibold text-white hover:bg-navy">Tambah</button>
                        </form>
                    </article>
                @endforeach
            </div>
        </section>
    @endforeach
</main>
@php($cart = session('cart', []))
<div class="fixed inset-x-0 bottom-0 border-t border-slate-200 bg-white/95 p-4 shadow-lg">
    <div class="mx-auto flex max-w-5xl items-center justify-between gap-4">
        <div><p class="text-sm text-slate-500">Keranjang</p><p class="font-bold text-navy">{{ collect($cart)->sum('quantity') }} item</p></div>
        <a href="{{ route('cart') }}" class="rounded-xl bg-vibrant px-5 py-3 font-bold text-white">Lihat Keranjang</a>
    </div>
</div>
</body>
</html>
