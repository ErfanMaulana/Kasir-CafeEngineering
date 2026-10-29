<!doctype html>
<html lang="id">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Keranjang Belanja · Engineering Cafe</title>
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
        .cart-item-card { transition: opacity 0.25s ease, transform 0.25s ease; }

        /* Hilangkan panah spinner bawaan browser untuk Chrome, Safari, Edge, Opera */
        input[type=number]::-webkit-inner-spin-button, 
        input[type=number]::-webkit-outer-spin-button { 
            -webkit-appearance: none; 
            margin: 0; 
        }
        /* Hilangkan panah spinner untuk Firefox */
        input[type=number] {
            -moz-appearance: textfield;
            appearance: textfield;
        }
    </style>
</head>
<body class="min-h-screen bg-[#EEEEEE] text-slate-800 antialiased pb-16">

{{-- HEADER NAVIGASI --}}
<header class="border-b border-slate-200 bg-white/95 backdrop-blur-md sticky top-0 z-20">
    <div class="mx-auto flex max-w-3xl items-center justify-between px-4 py-3.5">
        <a href="{{ route('catalog') }}" class="flex items-center gap-2 text-xs font-bold text-royal hover:text-navy transition">
            <svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M15 19l-7-7 7-7"/></svg>
            <span>Kembali Pilih Menu</span>
        </a>
        <span class="text-xs font-bold text-slate-400 uppercase tracking-widest">Guest Cart</span>
    </div>
</header>

<main class="mx-auto max-w-3xl px-4 py-6">

    {{-- JUDUL HALAMAN & BADGE ITEM --}}
    <div class="flex items-end justify-between border-b border-slate-200 pb-4">
        <div>
            <span class="text-[11px] font-bold uppercase tracking-widest text-royal">Rincian Pesanan</span>
            <h1 class="text-2xl sm:text-3xl font-black text-navy">Keranjang Belanja</h1>
        </div>
        <span id="header-cart-count" class="rounded-full bg-white px-3.5 py-1.5 text-xs font-black text-navy shadow-xs border border-slate-200">
            {{ $items->sum('quantity') }} Item
        </span>
    </div>

    {{-- ALERT MESSAGES --}}
    @if (session('success'))
        <div id="flash-message" class="mt-4 flex items-center gap-2.5 rounded-2xl bg-emerald-50 border border-emerald-200 p-3.5 text-xs font-bold text-emerald-800">
            <span>✓</span>
            <span>{{ session('success') }}</span>
        </div>
    @endif
    @if (session('error'))
        <div class="mt-4 flex items-center gap-2.5 rounded-2xl bg-rose-50 border border-rose-200 p-3.5 text-xs font-bold text-rose-800">
            <span>!</span>
            <span>{{ session('error') }}</span>
        </div>
    @endif

    {{-- TOAST UPDATE SUKSES (AJAX FEEDBACK) --}}
    <div id="ajax-toast" class="fixed top-16 right-4 z-50 hidden rounded-2xl bg-navy px-4 py-2.5 text-xs font-bold text-white shadow-xl transition-all border border-white/10">
        <span id="ajax-toast-text">✓ Keranjang diperbarui</span>
    </div>

    {{-- SECTION EMPTY CART (JIKA KOSONG) --}}
    <section id="empty-cart-section" class="mt-8 rounded-3xl bg-white p-10 text-center shadow-xs border border-slate-100 {{ $items->isEmpty() ? '' : 'hidden' }}">
        <div class="text-6xl">🛒</div>
        <h2 class="mt-4 text-xl font-black text-navy">Keranjang Anda Masih Kosong</h2>
        <p class="mt-1 text-xs text-slate-400 max-w-xs mx-auto">
            Silakan pilih makanan atau minuman favorit Anda dari katalog untuk memulai pesanan.
        </p>
        <a href="{{ route('catalog') }}"
           class="mt-6 inline-flex items-center gap-2 rounded-2xl bg-vibrant px-6 py-3.5 text-xs font-black text-white shadow-md shadow-red-950/20 hover:bg-red-700 transition">
            <span>Lihat Katalog Menu &rarr;</span>
        </a>
    </section>

    {{-- SECTION DAFTAR ITEM KERANJANG --}}
    <div id="cart-content-section" class="{{ $items->isEmpty() ? 'hidden' : '' }}">
        <form method="POST" action="{{ route('cart.update') }}" class="mt-6 space-y-4" id="cart-form">
            @csrf

            <div id="items-list" class="space-y-4">
                @foreach ($items as $item)
                    <article id="cart-item-{{ $item['product']->id }}"
                             class="cart-item-card rounded-3xl bg-white p-4 sm:p-5 shadow-xs border border-slate-100/80"
                             data-product-id="{{ $item['product']->id }}"
                             data-price="{{ $item['product']->price }}"
                             data-name="{{ $item['product']->name }}">
                        <div class="flex gap-4">
                            <div class="relative h-20 w-20 shrink-0 overflow-hidden rounded-2xl bg-cream">
                                @if ($item['product']->image)
                                    <img src="{{ $item['product']->image_url }}" alt="{{ $item['product']->name }}"
                                         class="h-full w-full object-cover">
                                @else
                                    <div class="flex h-full items-center justify-center text-3xl">☕</div>
                                @endif
                            </div>

                            <div class="min-w-0 flex-1">
                                <div class="flex flex-wrap items-start justify-between gap-2">
                                    <div>
                                        <h2 class="font-black text-navy text-base leading-tight">{{ $item['product']->name }}</h2>
                                        <p class="text-xs text-slate-400 font-medium mt-0.5">
                                            Rp {{ number_format($item['product']->price, 0, ',', '.') }} / porsi
                                        </p>
                                    </div>
                                    <p id="subtotal-{{ $item['product']->id }}" class="item-subtotal-display text-base font-black text-royal shrink-0">
                                        Rp {{ number_format($item['subtotal'], 0, ',', '.') }}
                                    </p>
                                </div>

                                {{-- KONTROL JUMLAH (+ / -) & HAPUS --}}
                                <div class="mt-4 flex flex-wrap items-center justify-between gap-3 border-t border-slate-100 pt-3">
                                    <div class="flex items-center rounded-xl border border-slate-200 bg-slate-50 overflow-hidden shadow-xs">
                                        <button type="button"
                                                class="btn-qty px-3.5 py-1.5 text-base font-black text-slate-600 hover:bg-slate-200 active:scale-95 transition"
                                                data-product-id="{{ $item['product']->id }}"
                                                data-delta="-1"
                                                title="Kurangi kuantitas">
                                            −
                                        </button>
                                        <input id="qty-{{ $item['product']->id }}"
                                               name="quantities[{{ $item['product']->id }}]"
                                               value="{{ $item['quantity'] }}"
                                               type="number" min="1" max="99"
                                               class="qty-input w-12 border-0 bg-transparent text-center text-sm font-black text-navy focus:outline-none [appearance:textfield] [&::-webkit-outer-spin-button]:appearance-none [&::-webkit-inner-spin-button]:appearance-none"
                                               data-product-id="{{ $item['product']->id }}">
                                        <button type="button"
                                                class="btn-qty px-3.5 py-1.5 text-base font-black text-slate-600 hover:bg-slate-200 active:scale-95 transition"
                                                data-product-id="{{ $item['product']->id }}"
                                                data-delta="1"
                                                title="Tambah kuantitas">
                                            +
                                        </button>
                                    </div>

                                    <button type="button"
                                            class="btn-remove-item flex items-center gap-1 text-xs font-bold text-rose-600 hover:text-rose-800 hover:underline transition"
                                            data-product-id="{{ $item['product']->id }}"
                                            data-name="{{ $item['product']->name }}">
                                        <svg class="h-3.5 w-3.5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/></svg>
                                        <span>Hapus</span>
                                    </button>
                                </div>

                                {{-- CATATAN KHUSUS PER ITEM --}}
                                <div class="mt-3">
                                    <label for="note-{{ $item['product']->id }}" class="block text-[11px] font-bold uppercase tracking-wider text-slate-400">
                                        Catatan Khusus
                                    </label>
                                    <input id="note-{{ $item['product']->id }}"
                                           name="notes[{{ $item['product']->id }}]"
                                           value="{{ $item['notes'] }}"
                                           maxlength="500"
                                           placeholder="Contoh: Es sedikit, pisahkan sambal, jangan terlalu manis..."
                                           class="note-input mt-1 w-full rounded-xl border border-slate-200 bg-slate-50 px-3 py-2 text-xs font-medium text-slate-700 focus:border-royal focus:bg-white focus:outline-none focus:ring-1 focus:ring-royal"
                                           data-product-id="{{ $item['product']->id }}">
                                </div>
                            </div>
                        </div>
                    </article>
                @endforeach
            </div>

            <div class="flex justify-end pt-2">
                <button type="submit" id="btn-save-notes"
                        class="rounded-xl border border-slate-300 bg-white px-4 py-2.5 text-xs font-bold text-navy shadow-xs hover:bg-slate-50 active:scale-98 transition">
                    Simpan Perubahan Jumlah &amp; Catatan
                </button>
            </div>
        </form>

        {{-- FORM HAPUS TERSEMBUNYI PER ITEM (UNTUK FALLBACK REGULAR SUBMIT) --}}
        @foreach ($items as $item)
            <form id="remove-form-{{ $item['product']->id }}" method="POST" action="{{ route('cart.remove') }}" class="hidden">
                @csrf
                <input type="hidden" name="product_id" value="{{ $item['product']->id }}">
            </form>
        @endforeach

        {{-- RINGKASAN TAGIHAN & CTA CHECKOUT --}}
        <section class="mt-6 rounded-3xl bg-navy p-6 text-white shadow-xl">
            <div class="space-y-2 border-b border-white/10 pb-4 text-xs font-medium">
                <div class="flex justify-between text-blue-200">
                    <span id="summary-porsi-label">
                        Subtotal Menu (<span id="summary-porsi-count">{{ $items->sum('quantity') }}</span> porsi)
                    </span>
                    <span id="summary-subtotal">Rp {{ number_format($cartTotal, 0, ',', '.') }}</span>
                </div>
                <div class="flex justify-between text-blue-200">
                    <span>Biaya Layanan &amp; Antrean</span>
                    <span class="text-emerald-400 font-bold">GRATIS</span>
                </div>
            </div>

            <div class="mt-4 flex items-baseline justify-between text-white">
                <span class="text-sm font-bold">Total Pembayaran</span>
                <span id="summary-total" class="text-2xl font-black text-white">
                    Rp {{ number_format($cartTotal, 0, ',', '.') }}
                </span>
            </div>

            <a href="{{ route('checkout') }}"
               class="mt-6 flex w-full items-center justify-center gap-2 rounded-2xl bg-vibrant px-5 py-4 text-center text-sm font-black text-white shadow-lg shadow-red-950/30 hover:bg-red-700 active:scale-98 transition">
                <span>Lanjut ke Form Checkout</span>
                <svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M14 5l7 7m0 0l-7 7m7-7H3"/></svg>
            </a>
        </section>
    </div>

</main>

{{-- JAVASCRIPT REAL-TIME RECALCULATION & AJAX SYNC --}}
<script>
document.addEventListener('DOMContentLoaded', () => {
    const csrfToken = document.querySelector('meta[name="csrf-token"]')?.content;
    const toast = document.getElementById('ajax-toast');
    const toastText = document.getElementById('ajax-toast-text');

    function showToast(message) {
        if (!toast) return;
        toastText.textContent = message;
        toast.classList.remove('hidden');
        setTimeout(() => toast.classList.add('hidden'), 2000);
    }

    // Format angka ke format Rupiah
    function formatRupiah(amount) {
        return 'Rp ' + Number(amount).toLocaleString('id-ID');
    }

    // Hitung ulang seluruh subtotal dan total dari elemen DOM secara instan
    function recalculateDomTotals() {
        const itemCards = document.querySelectorAll('.cart-item-card');
        let totalAmount = 0;
        let totalPorsi = 0;

        itemCards.forEach(card => {
            const pId = card.dataset.productId;
            const price = parseFloat(card.dataset.price) || 0;
            const input = document.getElementById(`qty-${pId}`);
            const qty = parseInt(input?.value, 10) || 0;
            const itemSubtotal = price * qty;

            totalAmount += itemSubtotal;
            totalPorsi += qty;

            const subtotalEl = document.getElementById(`subtotal-${pId}`);
            if (subtotalEl) {
                subtotalEl.textContent = formatRupiah(itemSubtotal);
            }
        });

        // Update bagian summary & header badge
        const summarySubtotal = document.getElementById('summary-subtotal');
        const summaryTotal = document.getElementById('summary-total');
        const summaryPorsi = document.getElementById('summary-porsi-count');
        const headerCount = document.getElementById('header-cart-count');

        if (summarySubtotal) summarySubtotal.textContent = formatRupiah(totalAmount);
        if (summaryTotal) summaryTotal.textContent = formatRupiah(totalAmount);
        if (summaryPorsi) summaryPorsi.textContent = totalPorsi;
        if (headerCount) headerCount.textContent = `${totalPorsi} Item`;

        // Jika semua item 0 / kosong, tampilkan empty cart
        if (itemCards.length === 0 || totalPorsi === 0) {
            document.getElementById('cart-content-section')?.classList.add('hidden');
            document.getElementById('empty-cart-section')?.classList.remove('hidden');
        }
    }

    // Mengirimkan request AJAX ke server untuk memperbarui session cart
    async function syncQuantityToServer(productId, quantity) {
        try {
            const noteInput = document.getElementById(`note-${productId}`);
            const notes = noteInput ? noteInput.value : '';

            const res = await fetch("{{ route('cart.update') }}", {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': csrfToken,
                    'Accept': 'application/json',
                },
                body: JSON.stringify({
                    product_id: productId,
                    quantity: quantity,
                    notes: notes
                })
            });

            if (res.ok) {
                const data = await res.json();
                // Sinkronkan kembali dengan data resmi dari server
                if (data.item_subtotal_formatted) {
                    const subtotalEl = document.getElementById(`subtotal-${productId}`);
                    if (subtotalEl) subtotalEl.textContent = data.item_subtotal_formatted;
                }
                if (data.cart_total_formatted) {
                    const summarySubtotal = document.getElementById('summary-subtotal');
                    const summaryTotal = document.getElementById('summary-total');
                    if (summarySubtotal) summarySubtotal.textContent = data.cart_total_formatted;
                    if (summaryTotal) summaryTotal.textContent = data.cart_total_formatted;
                }
                if (data.cart_count !== undefined) {
                    const summaryPorsi = document.getElementById('summary-porsi-count');
                    const headerCount = document.getElementById('header-cart-count');
                    if (summaryPorsi) summaryPorsi.textContent = data.cart_count;
                    if (headerCount) headerCount.textContent = `${data.cart_count} Item`;
                }
                showToast('✓ Jumlah pesanan diperbarui');
            }
        } catch (e) {
            console.error('Gagal sinkronisasi kuantitas:', e);
        }
    }

    // Fungsi hapus item dari keranjang
    async function removeItem(productId, productName) {
        if (!confirm(`Hapus "${productName}" dari keranjang?`)) {
            return;
        }

        const card = document.getElementById(`cart-item-${productId}`);
        if (card) {
            card.style.opacity = '0.3';
            card.style.transform = 'scale(0.97)';
        }

        try {
            const res = await fetch("{{ route('cart.remove') }}", {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': csrfToken,
                    'Accept': 'application/json',
                },
                body: JSON.stringify({ product_id: productId })
            });

            if (res.ok) {
                if (card) card.remove();
                recalculateDomTotals();
                showToast(`✓ ${productName} dihapus`);
            } else {
                document.getElementById(`remove-form-${productId}`)?.submit();
            }
        } catch (e) {
            document.getElementById(`remove-form-${productId}`)?.submit();
        }
    }

    // Handler klik tombol (+) dan (-)
    document.querySelectorAll('.btn-qty').forEach(btn => {
        btn.addEventListener('click', () => {
            const pId = btn.dataset.productId;
            const delta = parseInt(btn.dataset.delta, 10);
            const input = document.getElementById(`qty-${pId}`);
            if (!input) return;

            const current = parseInt(input.value, 10) || 1;
            const card = document.getElementById(`cart-item-${pId}`);
            const productName = card?.dataset.name || 'Menu';

            // Jika kuantitas saat ini 1 dan tombol (-) ditekan
            if (current === 1 && delta === -1) {
                removeItem(pId, productName);
                return;
            }

            const next = Math.max(1, Math.min(99, current + delta));
            input.value = next;

            // Recalculate secara instan di UI
            recalculateDomTotals();

            // Sync ke backend session
            syncQuantityToServer(pId, next);
        });
    });

    // Handler perubahan langsung pada input angka
    document.querySelectorAll('.qty-input').forEach(input => {
        input.addEventListener('change', () => {
            const pId = input.dataset.productId;
            const card = document.getElementById(`cart-item-${pId}`);
            const productName = card?.dataset.name || 'Menu';
            let val = parseInt(input.value, 10);

            if (isNaN(val) || val <= 0) {
                removeItem(pId, productName);
                return;
            }

            val = Math.max(1, Math.min(99, val));
            input.value = val;

            recalculateDomTotals();
            syncQuantityToServer(pId, val);
        });
    });

    // Handler klik tombol hapus
    document.querySelectorAll('.btn-remove-item').forEach(btn => {
        btn.addEventListener('click', () => {
            const pId = btn.dataset.productId;
            const pName = btn.dataset.name;
            removeItem(pId, pName);
        });
    });

    // Handler submit form "Simpan Perubahan Jumlah & Catatan"
    const cartForm = document.getElementById('cart-form');
    if (cartForm) {
        cartForm.addEventListener('submit', async (e) => {
            // Biarkan form melakukan submit standar jika ada catatan khusus yang disimpan
            const btn = document.getElementById('btn-save-notes');
            if (btn) {
                btn.innerHTML = '<span>Menyimpan...</span>';
            }
        });
    }
});
</script>

</body>
</html>
