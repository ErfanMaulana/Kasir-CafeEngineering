<!doctype html>
<html lang="id">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Menu Online Ordering · Unsil Cafe Connect</title>
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
        .product-card { transition: transform 0.2s cubic-bezier(0.16, 1, 0.3, 1), box-shadow 0.2s ease; }
        .product-card:hover { transform: translateY(-3px); box-shadow: 0 12px 28px -6px rgba(4, 21, 98, 0.12); }
        .category-tab { transition: all 0.2s ease; }
    </style>
</head>
<body class="min-h-screen bg-[#EEEEEE] text-slate-800 antialiased pb-28 lg:pb-12">

{{-- HEADER UTAMA --}}
<header class="sticky top-0 z-30 border-b border-slate-200/80 bg-white/95 backdrop-blur-md shadow-xs">
    <div class="mx-auto flex max-w-[1440px] items-center justify-between gap-4 px-4 py-3.5 sm:px-6 xl:px-10">
        <div class="flex items-center gap-3">
            <div class="flex h-11 w-11 items-center justify-center rounded-2xl bg-gradient-to-br from-navy to-royal text-2xl text-white shadow-md">
                ☕
            </div>
            <div>
                <div class="flex items-center gap-2">
                    <span class="text-lg font-black tracking-tight text-navy">Unsil Cafe Connect</span>
                    <span class="rounded-md bg-blue-100 px-2 py-0.5 text-[11px] font-black uppercase text-royal">Guest</span>
                </div>
                <p class="text-xs text-slate-500 font-medium">Fakultas Teknik · Universitas Siliwangi</p>
            </div>
        </div>

        <div class="flex items-center gap-3">
            <div class="hidden sm:flex items-center gap-2 rounded-full bg-slate-100 px-3.5 py-1.5 text-xs font-semibold text-slate-600">
                <span class="h-2 w-2 rounded-full bg-emerald-500 animate-pulse"></span>
                <span>Antrean: ~3-5 Menit</span>
            </div>
            <a href="{{ route('admin.dashboard') }}" class="rounded-xl border border-slate-200 bg-white px-3 py-2 text-xs font-bold text-slate-600 hover:border-royal hover:text-royal transition">
                Portal Kasir/KDS
            </a>
        </div>
    </div>
</header>

<main class="mx-auto max-w-[1440px] px-4 py-6 sm:px-6 xl:px-10">

    {{-- ALERT MESSAGES --}}
    @if (session('success'))
        <div class="mb-5 flex items-center gap-3 rounded-2xl border border-emerald-200 bg-emerald-50 p-4 text-sm font-bold text-emerald-800 shadow-sm animate-fade-in">
            <svg class="h-5 w-5 shrink-0 text-emerald-600" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/></svg>
            <span>{{ session('success') }}</span>
        </div>
    @endif
    @if (session('error'))
        <div class="mb-5 flex items-center gap-3 rounded-2xl border border-rose-200 bg-rose-50 p-4 text-sm font-bold text-rose-800 shadow-sm">
            <svg class="h-5 w-5 shrink-0 text-rose-600" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/></svg>
            <span>{{ session('error') }}</span>
        </div>
    @endif

    <div class="grid gap-8 lg:grid-cols-[minmax(0,1fr)_360px]">

        {{-- KOLOM KATALOG --}}
        <div class="min-w-0">

            {{-- BANNER HERO GUEST CHECKOUT --}}
            <section class="relative overflow-hidden rounded-3xl bg-gradient-to-r from-navy via-royal to-navy p-6 sm:p-8 text-white shadow-xl">
                <div class="relative z-10 max-w-xl">
                    <span class="inline-flex items-center gap-1.5 rounded-full bg-white/20 px-3 py-1 text-xs font-black tracking-wide text-white backdrop-blur-xs">
                        ⚡ GUEST CHECKOUT (TANPA LOGIN)
                    </span>
                    <h1 class="mt-4 text-3xl font-black tracking-tight sm:text-4xl text-white">
                        Pesan Cepat, Bebas Antre di Kasir!
                    </h1>
                    <p class="mt-2 text-sm sm:text-base text-blue-100 font-medium">
                        Pilih menu kopi atau cemilan favoritmu, masukkan nama pemanggil, dan nomor antrean digital langsung terbit otomatis.
                    </p>
                </div>
                <div class="pointer-events-none absolute -bottom-10 -right-6 text-[150px] font-black text-white/5 select-none">
                    ☕
                </div>
            </section>

            {{-- SEARCH & FILTER KATEGORI --}}
            <section class="mt-6 space-y-4">
                <div class="relative">
                    <span class="absolute left-4 top-1/2 -translate-y-1/2 text-slate-400">
                        <svg class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/></svg>
                    </span>
                    <input id="product-search" type="search" placeholder="Cari kopi, makanan berat, atau snack..."
                           class="w-full rounded-2xl border border-slate-200 bg-white py-3.5 pl-12 pr-4 text-sm font-medium shadow-xs outline-none transition focus:border-royal focus:ring-2 focus:ring-royal/20">
                </div>

                {{-- FILTER TABS KATEGORI DINAMIS --}}
                <div class="flex items-center gap-2 overflow-x-auto pb-1 pt-1 no-scrollbar">
                    <button type="button" data-filter="all"
                            class="category-tab whitespace-nowrap rounded-full bg-royal px-5 py-2.5 text-xs font-bold text-white shadow-xs">
                        Semua Menu
                    </button>
                    @foreach ($categories as $category)
                        <button type="button" data-filter="{{ $category->slug }}"
                                class="category-tab whitespace-nowrap rounded-full bg-white px-5 py-2.5 text-xs font-bold text-slate-600 hover:bg-slate-100 hover:text-navy shadow-xs border border-slate-100">
                            {{ $category->name }}
                        </button>
                    @endforeach
                </div>
            </section>

            {{-- DAFTAR PRODUK PER KATEGORI --}}
            <div class="mt-7 space-y-9">
                @foreach ($categories as $category)
                    <section class="category-section" data-category="{{ $category->slug }}">
                        <div class="mb-4 flex items-center justify-between border-b border-slate-200/80 pb-2">
                            <h2 class="text-xl font-black text-navy">{{ $category->name }}</h2>
                            <span class="text-xs font-bold text-slate-400">{{ $category->products->count() }} Pilihan</span>
                        </div>

                        <div class="grid gap-4 sm:grid-cols-2">
                            @forelse ($category->products as $product)
                                @php($qty = (int) ($cart[(string)$product->id]['quantity'] ?? 0))
                                <article class="product-card flex flex-col justify-between rounded-3xl bg-white p-4 shadow-xs border border-slate-100/80"
                                         data-name="{{ strtolower($product->name . ' ' . $product->description) }}">
                                    <div>
                                        <div class="relative h-44 w-full overflow-hidden rounded-2xl bg-cream">
                                            @if ($product->image)
                                                <img src="{{ $product->image_url }}" alt="{{ $product->name }}"
                                                     class="h-full w-full object-cover">
                                            @else
                                                <div class="flex h-full items-center justify-center bg-gradient-to-br from-slate-100 to-slate-200 text-5xl">
                                                    ☕
                                                </div>
                                            @endif

                                            {{-- INDIKATOR JUMLAH DI KERANJANG --}}
                                            <span id="badge-{{ $product->id }}"
                                                  class="absolute left-3 top-3 {{ $qty > 0 ? 'flex' : 'hidden' }} h-7 min-w-7 items-center justify-center rounded-full bg-vibrant px-2 text-xs font-black text-white shadow-md">
                                                {{ $qty }}x
                                            </span>

                                            <span class="absolute right-3 top-3 rounded-full bg-white/90 backdrop-blur-xs px-2.5 py-1 text-[11px] font-bold text-slate-700 shadow-xs">
                                                {{ $category->name }}
                                            </span>
                                        </div>

                                        <div class="mt-3.5">
                                            <h3 class="font-black text-navy text-base leading-tight">{{ $product->name }}</h3>
                                            <p class="mt-1 text-xs text-slate-500 line-clamp-2 leading-relaxed">
                                                {{ $product->description ?: 'Menu lezat siap saji untuk menemani aktivitas harian Anda.' }}
                                            </p>
                                        </div>
                                    </div>

                                    <div class="mt-4 flex items-center justify-between border-t border-slate-100 pt-3">
                                        <div>
                                            <span class="text-[10px] font-bold uppercase tracking-wider text-slate-400">Harga</span>
                                            <p class="text-lg font-black text-royal leading-tight">
                                                Rp {{ number_format($product->price, 0, ',', '.') }}
                                            </p>
                                        </div>

                                        <form method="POST" action="{{ route('cart.add', $product->id) }}" class="add-cart-form" data-product-id="{{ $product->id }}">
                                            @csrf
                                            <button type="submit"
                                                    class="flex items-center gap-1.5 rounded-xl bg-vibrant px-3.5 py-2 text-xs font-black text-white shadow-sm hover:bg-red-700 active:scale-95 transition">
                                                <span>+ Tambah</span>
                                            </button>
                                        </form>
                                    </div>
                                </article>
                            @empty
                                <div class="col-span-2 rounded-2xl border border-dashed border-slate-200 p-6 text-center text-xs text-slate-400">
                                    Belum ada menu yang tersedia pada kategori ini.
                                </div>
                            @endforelse
                        </div>
                    </section>
                @endforeach
            </div>
        </div>

        {{-- SIDEBAR DESKTOP KERANJANG CEPAT --}}
        <aside class="hidden lg:block">
            <div class="sticky top-20 rounded-3xl bg-navy p-6 text-white shadow-2xl">
                <div class="flex items-center justify-between">
                    <div>
                        <span class="text-[10px] font-bold uppercase tracking-widest text-blue-200">Pesanan Aktif</span>
                        <h2 class="mt-0.5 text-xl font-black">Keranjang Anda</h2>
                    </div>
                    <span id="quick-cart-badge" class="rounded-full bg-white/10 px-3 py-1 text-xs font-bold text-white">
                        {{ $cartCount }} Item
                    </span>
                </div>

                <div id="quick-cart-list" class="my-5 max-h-[380px] space-y-3.5 overflow-y-auto pr-1 border-y border-white/10 py-4">
                    @forelse ($cart as $productId => $item)
                        <div class="flex items-start justify-between gap-2 text-xs">
                            <div class="min-w-0 flex-1">
                                <p class="font-bold text-white truncate">{{ $item['name'] }}</p>
                                <p class="text-[11px] text-blue-200">
                                    {{ $item['quantity'] }} &times; Rp {{ number_format($item['price'], 0, ',', '.') }}
                                </p>
                            </div>
                            <span class="font-black text-white shrink-0">
                                Rp {{ number_format($item['price'] * $item['quantity'], 0, ',', '.') }}
                            </span>
                        </div>
                    @empty
                        <p id="empty-cart-text" class="py-10 text-center text-xs text-blue-200">
                            Keranjang belanja Anda masih kosong.
                        </p>
                    @endforelse
                </div>

                <div class="space-y-2 border-b border-white/10 pb-4 text-xs">
                    <div class="flex justify-between text-blue-200 font-medium">
                        <span>Subtotal Produk</span>
                        <span id="quick-cart-subtotal">Rp {{ number_format($cartTotal, 0, ',', '.') }}</span>
                    </div>
                </div>

                <div class="mt-4 flex items-baseline justify-between text-white">
                    <span class="font-bold text-sm">Total Tagihan</span>
                    <span id="quick-cart-total" class="text-2xl font-black">
                        Rp {{ number_format($cartTotal, 0, ',', '.') }}
                    </span>
                </div>

                <div class="mt-6 space-y-2.5">
                    <a href="{{ route('cart') }}"
                       class="flex w-full items-center justify-center gap-2 rounded-2xl bg-vibrant px-4 py-3.5 text-sm font-black text-white shadow-lg shadow-red-950/40 hover:bg-red-700 active:scale-98 transition">
                        <span>Buka Keranjang &amp; Checkout</span>
                        <svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M14 5l7 7m0 0l-7 7m7-7H3"/></svg>
                    </a>
                </div>
            </div>
        </aside>

    </div>
</main>

{{-- FLOATING BOTTOM BAR (KHUSUS MOBILE / LAYAR KECIL) --}}
<div id="floating-cart" class="fixed inset-x-3 bottom-3 z-40 lg:hidden {{ $cartCount > 0 ? '' : 'hidden' }}">
    <a href="{{ route('cart') }}"
       class="flex items-center justify-between rounded-2xl bg-navy px-4 py-3.5 text-white shadow-2xl ring-2 ring-white/10 active:scale-98 transition">
        <div class="flex items-center gap-3">
            <div class="relative flex h-10 w-10 items-center justify-center rounded-xl bg-royal text-lg text-white">
                🛒
                <span id="float-cart-count" class="absolute -top-1 -right-1 flex h-5 w-5 items-center justify-center rounded-full bg-vibrant text-[10px] font-black text-white ring-2 ring-navy">
                    {{ $cartCount }}
                </span>
            </div>
            <div>
                <p class="text-xs font-medium text-blue-200">Total Belanja</p>
                <p id="float-cart-total" class="text-base font-black text-white leading-tight">
                    Rp {{ number_format($cartTotal, 0, ',', '.') }}
                </p>
            </div>
        </div>

        <span class="flex items-center gap-1.5 rounded-xl bg-vibrant px-4 py-2.5 text-xs font-black text-white shadow-sm">
            Lihat Pesanan &rarr;
        </span>
    </a>
</div>

{{-- JAVASCRIPT: FILTER, SEARCH, & ASYNC ADD TO CART --}}
<script>
document.addEventListener('DOMContentLoaded', () => {
    const tabs = document.querySelectorAll('.category-tab');
    const sections = document.querySelectorAll('.category-section');
    const search = document.getElementById('product-search');

    // 1. Filter Kategori & Search
    function filterMenu() {
        const activeTab = document.querySelector('.category-tab.bg-royal')?.dataset.filter || 'all';
        const query = (search?.value || '').toLowerCase().trim();

        sections.forEach(section => {
            const sectionCategory = section.dataset.category;
            const categoryMatch = (activeTab === 'all' || sectionCategory === activeTab);
            const cards = section.querySelectorAll('.product-card');
            let hasVisibleCards = false;

            cards.forEach(card => {
                const text = card.dataset.name || '';
                const searchMatch = !query || text.includes(query);
                const visible = categoryMatch && searchMatch;
                card.style.display = visible ? 'flex' : 'none';
                if (visible) hasVisibleCards = true;
            });

            section.style.display = hasVisibleCards ? 'block' : 'none';
        });
    }

    tabs.forEach(tab => {
        tab.addEventListener('click', () => {
            tabs.forEach(t => {
                t.className = 'category-tab whitespace-nowrap rounded-full bg-white px-5 py-2.5 text-xs font-bold text-slate-600 hover:bg-slate-100 hover:text-navy shadow-xs border border-slate-100';
            });
            tab.className = 'category-tab whitespace-nowrap rounded-full bg-royal px-5 py-2.5 text-xs font-bold text-white shadow-xs';
            filterMenu();
        });
    });

    if (search) {
        search.addEventListener('input', filterMenu);
    }

    // 2. AJAX Add-to-Cart
    const forms = document.querySelectorAll('.add-cart-form');
    forms.forEach(form => {
        form.addEventListener('submit', async (e) => {
            e.preventDefault();
            const btn = form.querySelector('button');
            const originalText = btn.innerHTML;
            btn.disabled = true;
            btn.innerHTML = '<span>...</span>';

            try {
                const res = await fetch(form.action, {
                    method: 'POST',
                    headers: {
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
                        'Accept': 'application/json',
                    }
                });

                if (res.ok) {
                    const data = await res.json();
                    const pId = form.dataset.productId;
                    const badge = document.getElementById(`badge-${pId}`);
                    if (badge) {
                        badge.textContent = `${data.item_quantity}x`;
                        badge.classList.remove('hidden');
                        badge.classList.add('flex');
                    }

                    // Update floating bar
                    const floatBar = document.getElementById('floating-cart');
                    const floatCount = document.getElementById('float-cart-count');
                    const floatTotal = document.getElementById('float-cart-total');
                    if (floatBar) {
                        floatBar.classList.remove('hidden');
                        if (floatCount) floatCount.textContent = data.cart_count;
                        if (floatTotal) floatTotal.textContent = data.cart_total_formatted;
                    }

                    // Update desktop quick cart
                    const qcBadge = document.getElementById('quick-cart-badge');
                    const qcSub = document.getElementById('quick-cart-subtotal');
                    const qcTot = document.getElementById('quick-cart-total');
                    if (qcBadge) qcBadge.textContent = `${data.cart_count} Item`;
                    if (qcSub) qcSub.textContent = data.cart_total_formatted;
                    if (qcTot) qcTot.textContent = data.cart_total_formatted;

                    btn.classList.add('bg-emerald-600');
                    btn.innerHTML = '<span>✓ Ditambah</span>';
                    setTimeout(() => {
                        btn.classList.remove('bg-emerald-600');
                        btn.innerHTML = originalText;
                        btn.disabled = false;
                    }, 800);
                } else {
                    form.submit();
                }
            } catch (err) {
                form.submit();
            }
        });
    });
});
</script>

</body>
</html>
