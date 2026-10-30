<!doctype html>
<html lang="id">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Engineering Cafe - Online Ordering FT Unsil</title>
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
            {{-- <div class="flex h-11 w-11 items-center justify-center rounded-2xl bg-gradient-to-br from-navy to-royal text-2xl text-white shadow-md">
                ☕
            </div> --}}
            <div>
                <div class="flex items-center gap-2">
                    <span class="text-lg font-black tracking-tight text-navy">Engineering Cafe</span>
                    <span class="rounded-md bg-blue-100 px-2 py-0.5 text-[11px] font-black uppercase text-royal">Pelanggan</span>
                </div>
                <p class="text-xs text-slate-500 font-medium">Fakultas Teknik - Universitas Siliwangi</p>
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

                        <div class="grid grid-cols-2 lg:grid-cols-3 gap-4 lg:gap-6">
                            @forelse ($category->products as $product)
                                @php
                                    $qty = (int) ($cart[(string)$product->id]['quantity'] ?? 0);
                                @endphp
                                <article class="product-card bg-white rounded-2xl p-3 lg:p-4 border border-slate-100 shadow-sm flex flex-col justify-between h-full"
                                         data-name="{{ strtolower($product->name . ' ' . $product->description . ' ' . ($product->ingredients ?? '')) }}">
                                    
                                    {{-- GAMBAR PRODUK (KLIK UNTUK MODAL DETAIL) --}}
                                    <div class="w-full aspect-square relative rounded-xl overflow-hidden bg-slate-100 mb-3 shrink-0 cursor-pointer group"
                                         onclick="openDetailModal({{ $product->id }})" title="Klik untuk lihat detail & komposisi">
                                        @if ($product->image)
                                            <img src="{{ $product->image_url }}" alt="{{ $product->name }}"
                                                 class="w-full h-full object-cover group-hover:scale-105 transition duration-300">
                                        @else
                                            <div class="flex h-full w-full items-center justify-center bg-gradient-to-br from-slate-100 to-slate-200 text-3xl lg:text-4xl group-hover:scale-105 transition duration-300">
                                                ☕
                                            </div>
                                        @endif

                                        {{-- INDIKATOR JUMLAH DI KERANJANG --}}
                                        <span id="badge-{{ $product->id }}"
                                              class="absolute left-2 top-2 lg:left-2.5 lg:top-2.5 {{ $qty > 0 ? 'flex' : 'hidden' }} h-6 min-w-6 lg:h-7 lg:min-w-7 items-center justify-center rounded-full bg-[#DA1212] px-1.5 lg:px-2 text-[10px] lg:text-xs font-black text-white shadow-md z-10">
                                            {{ $qty }}x
                                        </span>

                                        <span class="absolute right-2 top-2 lg:right-2.5 lg:top-2.5 rounded-full bg-white/90 backdrop-blur-xs px-2 py-0.5 lg:px-2.5 lg:py-1 text-[10px] lg:text-[11px] font-bold text-slate-700 shadow-xs z-10">
                                            {{ $category->name }}
                                        </span>

                                        {{-- Info Overlay Hover --}}
                                        <div class="absolute inset-0 bg-navy/20 opacity-0 group-hover:opacity-100 transition flex items-center justify-center">
                                            <span class="rounded-full bg-white/95 px-3 py-1 text-[11px] font-bold text-navy shadow-sm">
                                                🔍 Lihat Detail
                                            </span>
                                        </div>
                                    </div>

                                    {{-- AREA JUDUL PRODUK --}}
                                    <div class="mb-3 min-w-0">
                                        <h3 class="font-bold text-slate-800 text-sm lg:text-base leading-snug truncate cursor-pointer hover:text-royal transition"
                                            onclick="openDetailModal({{ $product->id }})" title="Klik untuk lihat detail & komposisi">
                                            {{ $product->name }}
                                        </h3>
                                    </div>

                                    {{-- AREA BAWAH (HARGA & TOMBOL TAMBAH) --}}
                                    <div class="flex items-center justify-between gap-2 mt-auto pt-2 border-t border-slate-50">
                                        <div class="min-w-0">
                                            <p class="font-extrabold text-slate-900 text-sm lg:text-base whitespace-nowrap">
                                                Rp {{ number_format($product->price, 0, ',', '.') }}
                                            </p>
                                        </div>

                                        <form method="POST" action="{{ route('cart.add', $product->id) }}" class="add-cart-form shrink-0" data-product-id="{{ $product->id }}">
                                            @csrf
                                            <button type="submit"
                                                    class="bg-[#DA1212] hover:bg-red-700 text-white font-medium text-xs lg:text-sm px-3 py-1.5 rounded-lg transition-colors shrink-0 whitespace-nowrap active:scale-95 shadow-xs">
                                                <span>+ Tambah</span>
                                            </button>
                                        </form>
                                    </div>
                                </article>
                            @empty
                                <div class="col-span-2 lg:col-span-3 rounded-2xl border border-dashed border-slate-200 p-6 text-center text-xs text-slate-400">
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

{{-- MODAL DETAIL PRODUK GUEST (POP-UP KOMPOSISI & BAHAN) --}}
<div id="product-detail-modal" class="fixed inset-0 z-50 hidden items-center justify-center bg-navy/70 backdrop-blur-xs p-4 sm:p-6 overflow-y-auto">
    {{-- Kontainer Utama Pembungkus Modal (Outer Shell) --}}
    <div class="relative w-full max-w-lg overflow-hidden rounded-3xl bg-white shadow-2xl transition-all my-auto flex flex-col">
        
        {{-- Tombol Tutup Silang (X) Mengambang di Pojok Kanan Atas --}}
        <button type="button" onclick="closeDetailModal()"
                class="absolute top-6 right-6 z-20 flex h-9 w-9 items-center justify-center rounded-full bg-white/80 backdrop-blur-md hover:bg-white text-slate-700 p-2 shadow-md transition-all"
                title="Tutup Modal">
            <svg class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5"><path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12"/></svg>
        </button>

        {{-- 1. Kartu Gambar Produk dengan Padding Simetris --}}
        <div class="p-4 sm:p-5 pb-0 shrink-0">
            <div class="relative w-full aspect-video rounded-2xl overflow-hidden bg-slate-100 shadow-inner flex items-center justify-center border border-slate-100">
                <img id="detail-image" src="" alt="" class="w-full h-full object-cover">
                <div id="detail-fallback" class="hidden text-7xl select-none">☕</div>
            </div>
        </div>

        {{-- 2. Area Isi (Scrollable Body dengan Custom Scrollbar) --}}
        <div class="p-5 pt-3 space-y-4 overflow-y-auto max-h-[58vh] [&::-webkit-scrollbar]:w-1.5 [&::-webkit-scrollbar-track]:bg-transparent [&::-webkit-scrollbar-thumb]:bg-slate-200 [&::-webkit-scrollbar-thumb]:rounded-full">
            {{-- Judul, Kategori & Status Stok --}}
            <div>
                <div class="flex items-center gap-2 mb-2 flex-wrap">
                    <span id="detail-category" class="rounded-full bg-blue-100 px-3 py-1 text-xs font-bold text-royal">
                        Makanan
                    </span>
                    <span id="detail-stock" class="rounded-full bg-emerald-50 border border-emerald-200 px-3 py-1 text-xs font-bold text-emerald-700 flex items-center gap-1.5">
                        <span class="h-2 w-2 rounded-full bg-emerald-500 animate-pulse"></span>
                        <span>Tersedia (In Stock)</span>
                    </span>
                </div>
                <h3 id="detail-name" class="text-xl sm:text-2xl font-black text-navy leading-tight"></h3>
            </div>

            {{-- Deskripsi Rasa / Menu --}}
            <div>
                <h4 class="text-[11px] font-bold uppercase tracking-wider text-slate-400 mb-1">Deskripsi Menu</h4>
                <p id="detail-description" class="text-xs sm:text-sm text-slate-600 leading-relaxed font-medium"></p>
            </div>

            {{-- Section Komposisi / Bahan-Bahan Pilihan (Ingredients) --}}
            <div id="detail-ingredients-section" class="border-t border-slate-100 pt-4">
                <h4 class="text-[11px] font-bold uppercase tracking-wider text-slate-400 mb-2.5 flex items-center gap-1.5">
                    <span>Bahan-bahan &amp; Komposisi Pilihan (Ingredients)</span>
                </h4>
                <div id="detail-ingredients-list" class="flex flex-wrap gap-2">
                    {{-- Diinjeksi dinamis sebagai pills oleh JavaScript --}}
                </div>
            </div>
        </div>

        {{-- 3. Footer Modal (Fixed / Sticky Bottom) --}}
        <div class="p-4 border-t border-slate-100 bg-white flex items-center justify-between shrink-0">
            <div>
                <span class="block text-[10px] font-bold uppercase tracking-wider text-slate-400">Total Harga</span>
                <p id="detail-price" class="text-xl sm:text-2xl font-black text-[#DA1212] leading-tight">
                    Rp 0
                </p>
            </div>

            <form id="detail-cart-form" method="POST" action="" class="add-cart-form shrink-0" data-product-id="">
                @csrf
                <button type="submit" id="detail-submit-btn"
                        class="flex items-center gap-2 rounded-xl bg-vibrant px-5 py-3 text-xs sm:text-sm font-black text-white shadow-md hover:bg-red-700 active:scale-95 transition">
                    <svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M12 4v16m8-8H4"/></svg>
                    <span>+ Tambah ke Keranjang</span>
                </button>
            </form>
        </div>
    </div>
</div>

@php
    $allCatalogProducts = $categories->flatMap(function($cat) {
        return $cat->products->map(function($p) use ($cat) {
            return [
                'id' => $p->id,
                'name' => $p->name,
                'category_name' => $cat->name,
                'description' => $p->description,
                'ingredients' => $p->ingredients,
                'price' => (float) $p->price,
                'price_formatted' => 'Rp ' . number_format($p->price, 0, ',', '.'),
                'image_url' => $p->image_url,
                'is_available' => (bool) $p->is_available,
                'add_url' => route('cart.add', $p->id),
            ];
        });
    })->keyBy('id');
@endphp

{{-- JAVASCRIPT: FILTER, SEARCH, MODAL DETAIL, & ASYNC ADD TO CART --}}
<script>
const productsCatalog = @json($allCatalogProducts);

function openDetailModal(id) {
    const p = productsCatalog[id];
    if (!p) return;

    document.getElementById('detail-name').textContent = p.name;
    document.getElementById('detail-category').textContent = p.category_name;
    document.getElementById('detail-description').textContent = p.description || 'Menu lezat siap saji yang diracik khusus untuk Anda.';
    document.getElementById('detail-price').textContent = p.price_formatted;

    // Image
    const imgEl = document.getElementById('detail-image');
    const fallbackEl = document.getElementById('detail-fallback');
    if (p.image_url) {
        imgEl.src = p.image_url;
        imgEl.alt = p.name;
        imgEl.classList.remove('hidden');
        fallbackEl.classList.add('hidden');
    } else {
        imgEl.src = '';
        imgEl.classList.add('hidden');
        fallbackEl.classList.remove('hidden');
    }

    // Stock badge
    const stockEl = document.getElementById('detail-stock');
    if (p.is_available) {
        stockEl.className = 'rounded-full bg-emerald-50 border border-emerald-200 px-3 py-1 text-xs font-bold text-emerald-700 flex items-center gap-1.5';
        stockEl.innerHTML = '<span class="h-2 w-2 rounded-full bg-emerald-500 animate-pulse"></span><span>Tersedia (In Stock)</span>';
    } else {
        stockEl.className = 'rounded-full bg-slate-100 border border-slate-200 px-3 py-1 text-xs font-bold text-slate-500 flex items-center gap-1.5';
        stockEl.innerHTML = '<span class="h-2 w-2 rounded-full bg-slate-400"></span><span>Habis (Out of Stock)</span>';
    }

    // Ingredients pills
    const ingSection = document.getElementById('detail-ingredients-section');
    const ingList = document.getElementById('detail-ingredients-list');
    ingList.innerHTML = '';

    if (p.ingredients && p.ingredients.trim()) {
        const items = p.ingredients.split(',').map(s => s.trim()).filter(Boolean);
        if (items.length > 0) {
            items.forEach(item => {
                const pill = document.createElement('span');
                pill.className = 'inline-flex items-center gap-1.5 rounded-full bg-slate-100 border border-slate-200/80 px-3 py-1 text-xs font-semibold text-slate-700 shadow-xs hover:bg-slate-200 transition';
                pill.innerHTML = `<span class="h-1.5 w-1.5 rounded-full bg-[#11468F]"></span><span>${item}</span>`;
                ingList.appendChild(pill);
            });
            ingSection.classList.remove('hidden');
        } else {
            ingSection.classList.add('hidden');
        }
    } else {
        ingSection.classList.add('hidden');
    }

    // Cart form
    const form = document.getElementById('detail-cart-form');
    form.action = p.add_url;
    form.dataset.productId = p.id;

    const modal = document.getElementById('product-detail-modal');
    modal.classList.remove('hidden');
    modal.classList.add('flex');
    document.body.classList.add('overflow-hidden');
}

function closeDetailModal() {
    const modal = document.getElementById('product-detail-modal');
    if (!modal) return;
    modal.classList.add('hidden');
    modal.classList.remove('flex');
    document.body.classList.remove('overflow-hidden');
}

// Close listeners
const detailModalEl = document.getElementById('product-detail-modal');
if (detailModalEl) {
    detailModalEl.addEventListener('click', (e) => {
        if (e.target === detailModalEl) closeDetailModal();
    });
}
window.addEventListener('keydown', (e) => {
    if (e.key === 'Escape') closeDetailModal();
});

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

    // 2. Render Rincian Item Keranjang Samping
    function escapeHtml(str) {
        if (!str) return '';
        return String(str).replace(/[&<>'"]/g, 
            tag => ({
                '&': '&amp;',
                '<': '&lt;',
                '>': '&gt;',
                "'": '&#39;',
                '"': '&quot;'
            }[tag] || tag)
        );
    }

    function renderQuickCartList(items) {
        const listContainer = document.getElementById('quick-cart-list');
        if (!listContainer) return;

        if (!items || items.length === 0) {
            listContainer.innerHTML = `
                <p id="empty-cart-text" class="py-10 text-center text-xs text-blue-200">
                    Keranjang belanja Anda masih kosong.
                </p>
            `;
            return;
        }

        let html = '';
        items.forEach(item => {
            html += `
                <div class="flex items-start justify-between gap-2 text-xs">
                    <div class="min-w-0 flex-1">
                        <p class="font-bold text-white truncate">${escapeHtml(item.name)}</p>
                        <p class="text-[11px] text-blue-200">
                            ${item.quantity} &times; ${item.price_formatted}
                        </p>
                    </div>
                    <span class="font-black text-white shrink-0">
                        ${item.subtotal_formatted}
                    </span>
                </div>
            `;
        });
        listContainer.innerHTML = html;
    }

    // 3. AJAX Add-to-Cart
    const forms = document.querySelectorAll('.add-cart-form');
    forms.forEach(form => {
        form.addEventListener('submit', async (e) => {
            e.preventDefault();
            const btn = form.querySelector('button');
            if (!btn) return;

            // Animasi tekan visual cepat tanpa mengganti teks / warna tombol
            btn.classList.add('scale-95');
            setTimeout(() => btn.classList.remove('scale-95'), 150);

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

                    // Update floating bar (Mobile)
                    const floatBar = document.getElementById('floating-cart');
                    const floatCount = document.getElementById('float-cart-count');
                    const floatTotal = document.getElementById('float-cart-total');
                    if (floatBar) {
                        if (data.cart_count > 0) {
                            floatBar.classList.remove('hidden');
                        } else {
                            floatBar.classList.add('hidden');
                        }
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

                    // Render list rincian item keranjang
                    renderQuickCartList(data.items);
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
