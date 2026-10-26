<!doctype html>
<html lang="id">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Circuit Cafe · Menu</title>
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
<body class="min-h-screen bg-cream text-slate-800">
<header class="border-b border-slate-200 bg-white">
    <div class="mx-auto flex max-w-[1440px] items-center justify-between gap-6 px-6 py-5 xl:px-10">
        <div class="flex items-center gap-4">
            <div class="flex h-14 w-14 items-center justify-center rounded-2xl bg-cream text-3xl">☕</div>
            <div><p class="text-2xl font-black tracking-tight text-navy">Circuit Cafe <span class="ml-1 rounded-full bg-cream px-3 py-1 text-sm font-black text-royal">GUEST</span></p><p class="mt-1 text-sm text-slate-500">📍 Faculty of Eng. Hall E</p></div>
        </div>
        <div class="hidden items-center gap-3 md:flex"><span class="rounded-full bg-cream px-5 py-3 text-sm font-bold text-navy">● &nbsp; Queue: ~4m</span><button class="flex h-12 w-12 items-center justify-center rounded-full bg-royal text-xl text-white" aria-label="Profil">♙</button></div>
    </div>
</header>
<main class="mx-auto max-w-[1440px] px-6 py-7 xl:px-10">
    @if (session('success'))<p class="mb-5 rounded-2xl bg-green-100 p-4 text-green-800">{{ session('success') }}</p>@endif
    @if (session('error'))<p class="mb-5 rounded-2xl bg-red-100 p-4 text-red-800">{{ session('error') }}</p>@endif
    <div class="grid gap-8 lg:grid-cols-[minmax(0,1fr)_360px]">
        <div class="min-w-0">
            <section class="rounded-3xl bg-cream px-6 py-5 shadow-sm"><div class="flex items-center justify-between gap-4"><div class="flex items-center gap-3"><span class="text-3xl text-royal">ϟ</span><div><h2 class="text-lg font-black text-navy">COUNTER B PICKUP NODE</h2><p class="text-sm text-slate-500">Avg wait: 6-8 mins <span class="mx-1">•</span> Live dispatch</p></div></div><span class="hidden rounded-full bg-white px-4 py-2 text-sm font-bold text-navy sm:inline-block">● &nbsp; Low Latency</span></div></section>
            <section class="relative mt-6 overflow-hidden rounded-3xl bg-gradient-to-br from-vibrant via-vibrant to-royal px-7 py-8 text-white shadow-lg"><div class="relative z-10 max-w-2xl"><span class="rounded-full bg-white/20 px-4 py-2 text-sm font-bold">▱ &nbsp; EXAM CRUNCH DEAL</span><h1 class="mt-5 text-4xl font-black tracking-tight sm:text-5xl">Flash Fuel for Finals</h1><p class="mt-3 max-w-xl text-lg leading-7 text-white/80">Power up your late-night lab session. 20% off all cold brew & artisan toast combos with your Faculty badge.</p><div class="mt-6 flex flex-wrap items-center gap-3"><span class="rounded-full bg-white px-5 py-2 font-black text-vibrant">Code: OVERCLOCK20</span><span class="font-semibold text-white/80">Tap to apply</span></div></div><div class="pointer-events-none absolute -bottom-12 right-10 text-[180px] font-black text-white/10">⌁</div></section>
            <section class="mt-7"><label class="relative block"><span class="absolute left-5 top-1/2 -translate-y-1/2 text-2xl text-slate-500">⌕</span><input id="product-search" type="search" placeholder="Search drinks, macros, or caffeine loads..." class="w-full rounded-2xl border-0 bg-cream px-14 py-5 text-lg outline-none ring-2 ring-transparent transition focus:ring-royal"></label><div class="mt-4 flex gap-3 overflow-x-auto pb-2"><button data-filter="all" class="category-tab whitespace-nowrap rounded-full bg-royal px-5 py-2.5 font-bold text-white">All Items</button>@foreach ($categories as $category)<button data-filter="{{ $category->slug }}" class="category-tab whitespace-nowrap rounded-full bg-white px-5 py-2.5 font-semibold text-slate-600">{{ $category->name }}</button>@endforeach</div></section>
            <div class="mt-6 space-y-8">
            @foreach ($categories as $category)
                <section class="category-section" data-category="{{ $category->slug }}"><div class="mb-4 flex items-center justify-between"><h2 class="text-xl font-black text-navy">{{ $category->name }}</h2><span class="text-sm font-semibold text-slate-400">{{ $category->products->count() }} items</span></div><div class="grid gap-4 xl:grid-cols-2">
                @foreach ($category->products as $product)
                    @php($quantity = (int) ($cart[$product->id]['quantity'] ?? 0))
                    <article class="product-card flex min-h-[190px] gap-5 rounded-3xl bg-white p-4 shadow-sm ring-1 ring-slate-100 transition hover:-translate-y-1 hover:shadow-lg" data-name="{{ strtolower($product->name.' '.$product->description) }}"><div class="relative h-40 w-40 shrink-0 overflow-hidden rounded-2xl bg-cream">@if ($product->image)<img src="{{ asset('storage/'.$product->image) }}" alt="{{ $product->name }}" class="h-full w-full object-cover">@else<div class="flex h-full items-center justify-center bg-cream text-6xl">☕</div>@endif @if ($quantity)<span class="absolute left-2 top-2 rounded-full bg-vibrant px-2.5 py-1 text-xs font-black text-white">{{ $quantity }}</span>@endif</div><div class="flex min-w-0 flex-1 flex-col py-1"><div class="flex items-center justify-between gap-3"><span class="text-sm font-bold text-slate-600"><i class="mr-1 inline-block h-3 w-3 rounded-full bg-royal"></i>In Stock</span><span class="rounded-md bg-cream px-2 py-1 text-xs font-bold text-slate-600">Fresh</span></div><h3 class="mt-3 truncate text-xl font-black text-navy">{{ $product->name }}</h3><p class="mt-1 line-clamp-2 text-sm text-slate-500">{{ $product->description ?: 'Freshly prepared for your next study session.' }}</p><div class="mt-auto flex items-end justify-between gap-3"><p class="text-2xl font-black text-royal">Rp {{ number_format($product->price, 0, ',', '.') }}</p><form method="POST" action="{{ route('cart.add', $product->id) }}">@csrf<button class="flex h-12 w-12 items-center justify-center rounded-full bg-royal text-3xl leading-none text-white shadow-md transition hover:bg-navy" aria-label="Tambah {{ $product->name }}">+</button></form></div></div></article>
                @endforeach
                </div></section>
            @endforeach
            </div>
        </div>
        <aside class="hidden lg:block"><div class="sticky top-6 rounded-3xl bg-navy p-5 text-white shadow-xl"><div class="flex items-center justify-between"><div><p class="text-xs font-bold uppercase tracking-[0.2em] text-blue-200">Your order</p><h2 class="mt-1 text-2xl font-black">Quick Cart</h2></div><span class="rounded-full bg-white/10 px-3 py-1 text-sm font-bold">{{ $cartCount }} item</span></div><div class="mt-5 space-y-4 border-y border-white/10 py-5">@forelse ($cart as $productId => $cartItem)<div class="flex items-center justify-between gap-3"><div class="min-w-0"><p class="truncate font-bold">{{ $cartItem['name'] }}</p><p class="text-sm text-blue-200">{{ $cartItem['quantity'] }} × Rp {{ number_format($cartItem['price'], 0, ',', '.') }}</p></div><span class="font-bold">Rp {{ number_format($cartItem['price'] * $cartItem['quantity'], 0, ',', '.') }}</span></div>@empty<p class="py-8 text-center text-sm text-blue-200">Keranjangmu masih kosong.</p>@endforelse</div><div class="flex justify-between text-blue-200"><span>Subtotal</span><span>Rp {{ number_format($cartTotal, 0, ',', '.') }}</span></div><div class="mt-3 flex justify-between text-xl font-black"><span>Total</span><span>Rp {{ number_format($cartTotal, 0, ',', '.') }}</span></div><a href="{{ route('cart') }}" class="mt-6 block rounded-2xl bg-vibrant px-5 py-4 text-center text-lg font-black transition hover:bg-royal">Checkout <span class="ml-2">→</span></a><p class="mt-4 text-center text-xs text-blue-200">Pickup slot: <b class="text-white">Now (~7m)</b></p></div></aside>
    </div>
</main>
<div class="fixed inset-x-4 bottom-4 z-20 lg:hidden"><a href="{{ route('cart') }}" class="flex items-center justify-between rounded-2xl bg-navy px-5 py-4 text-white shadow-2xl"><div><p class="font-black">{{ $cartCount }} items <span class="mx-1 text-slate-400">•</span> <span class="text-white">Rp {{ number_format($cartTotal, 0, ',', '.') }}</span></p><p class="text-xs text-slate-300">Pickup slot: Now (~7m)</p></div><span class="rounded-xl bg-vibrant px-4 py-3 font-black">Checkout →</span></a></div>
<script>
const tabs=document.querySelectorAll('.category-tab'); const sections=document.querySelectorAll('.category-section'); const search=document.getElementById('product-search');
function filterMenu(){const filter=document.querySelector('.category-tab.bg-royal')?.dataset.filter||'all'; const query=search.value.toLowerCase(); sections.forEach(section=>{let visible=filter==='all'||section.dataset.category===filter; let cards=section.querySelectorAll('.product-card'); cards.forEach(card=>card.hidden=query&&!card.dataset.name.includes(query)); if(query) visible=[...cards].some(card=>!card.hidden); section.hidden=!visible;});}
tabs.forEach(tab=>tab.addEventListener('click',()=>{tabs.forEach(item=>item.className='category-tab whitespace-nowrap rounded-full bg-white px-5 py-2.5 font-semibold text-slate-600');tab.className='category-tab whitespace-nowrap rounded-full bg-royal px-5 py-2.5 font-bold text-white';filterMenu();})); search.addEventListener('input',filterMenu);
</script>
</body>
</html>
