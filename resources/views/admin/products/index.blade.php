@extends('admin.layout')

@section('title', 'Menu & Inventory Management')
@section('page_label', 'Menu & Inventory')
@section('page_title', 'Manajemen Produk & Kategori')

@section('content')

{{-- HEADER ACTIONS --}}
<div class="mb-6 flex flex-wrap items-center justify-between gap-4">
    <div>
        <h2 class="text-xl font-black text-navy">Katalog Menu &amp; Stok Kafe</h2>
        <p class="text-xs text-slate-500 font-medium">Kelola daftar menu, kategori, harga, dan ketersediaan stok secara real-time</p>
    </div>

    <div class="flex items-center gap-2">
        <button type="button" data-open-category-modal
                class="flex items-center gap-2 rounded-xl border border-slate-300 bg-white px-4 py-2.5 text-xs font-bold text-navy shadow-xs hover:bg-slate-50 transition">
            <span>+ Kelola Kategori</span>
        </button>

        <button type="button" data-open-modal
                class="flex items-center gap-2 rounded-xl bg-vibrant px-4 py-2.5 text-xs font-black text-white shadow-md shadow-red-950/20 hover:bg-red-700 active:scale-95 transition">
            <svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M12 4v16m8-8H4"/></svg>
            <span>Tambah Produk</span>
        </button>
    </div>
</div>

{{-- TABEL PRODUK --}}
<div class="overflow-hidden rounded-3xl bg-white shadow-sm border border-slate-100">
    <div class="overflow-x-auto">
        <table class="w-full min-w-[760px] text-left text-xs">
            <thead class="border-b border-slate-100 bg-slate-50/80">
                <tr class="text-slate-400 font-bold uppercase tracking-wider text-[11px]">
                    <th class="px-6 py-4">Menu Produk</th>
                    <th class="px-4 py-4">Kategori</th>
                    <th class="px-4 py-4">Harga Satuan</th>
                    <th class="px-4 py-4">Status Ketersediaan</th>
                    <th class="px-6 py-4 text-right">Tindakan</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-slate-100">
                @forelse ($products as $product)
                    <tr class="hover:bg-slate-50/70 transition">
                        <td class="px-6 py-4">
                            <div class="flex items-center gap-3.5">
                                @if ($product->image)
                                    <img src="{{ $product->image_url }}" alt=""
                                         class="h-12 w-12 shrink-0 rounded-2xl object-cover bg-cream border border-slate-200/60">
                                @else
                                    <div class="flex h-12 w-12 shrink-0 items-center justify-center rounded-2xl bg-cream text-2xl border border-slate-200/60">
                                        ☕
                                    </div>
                                @endif
                                <div>
                                    <div class="flex items-center gap-2 flex-wrap">
                                        <p class="font-black text-navy text-sm">{{ $product->name }}</p>
                                        @if ($product->daily_stock !== null)
                                            @if ($product->daily_stock <= 0)
                                                <span class="rounded-full bg-slate-100 border border-slate-200 px-2 py-0.5 text-[10px] font-black text-slate-500">
                                                    Habis (0 porsi)
                                                </span>
                                            @elseif ($product->daily_stock <= 5)
                                                <span class="rounded-full bg-rose-50 border border-rose-200 px-2 py-0.5 text-[10px] font-black text-rose-700 animate-pulse">
                                                    Sisa: {{ $product->daily_stock }} porsi
                                                </span>
                                            @else
                                                <span class="rounded-full bg-amber-50 border border-amber-200 px-2 py-0.5 text-[10px] font-bold text-amber-800">
                                                    Sisa: {{ $product->daily_stock }} porsi
                                                </span>
                                            @endif
                                        @else
                                            <span class="rounded-full bg-slate-50 border border-slate-200/60 px-2 py-0.5 text-[10px] font-medium text-slate-400">
                                                Stok Unlimited
                                            </span>
                                        @endif
                                    </div>
                                    <p class="max-w-xs truncate text-[11px] text-slate-400 font-medium">
                                        {{ $product->description ?: 'Tidak ada deskripsi tambahan.' }}
                                    </p>
                                </div>
                            </div>
                        </td>
                        <td class="px-4 py-4">
                            <span class="rounded-full bg-blue-50 px-3 py-1 text-[11px] font-bold text-royal border border-blue-100">
                                {{ $product->category->name ?? '-' }}
                            </span>
                        </td>
                        <td class="px-4 py-4 font-black text-navy text-sm">
                            Rp {{ number_format($product->price, 0, ',', '.') }}
                            @php
                                $costPrice = (int) $product->cost_price;
                                $profit = (int) $product->price - $costPrice;
                                $marginPercent = (int) $product->price > 0
                                    ? round(($profit / (int) $product->price) * 100, 1)
                                    : 0;
                            @endphp
                            <div class="mt-1 text-[10px] font-semibold leading-4 text-slate-500">
                                Modal: Rp {{ number_format($costPrice, 0, ',', '.') }}
                                <span class="text-slate-300">•</span>
                                <span class="{{ $profit > 0 ? 'text-emerald-600' : ($profit < 0 ? 'text-red-600' : 'text-slate-500') }}">
                                    Untung: {{ $profit < 0 ? '-' : '' }}Rp {{ number_format(abs($profit), 0, ',', '.') }}
                                    ({{ $profit < 0 ? '-' : '' }}{{ number_format(abs($marginPercent), 1, '.', '') }}%)
                                </span>
                            </div>
                        </td>
                        <td class="px-4 py-4">
                            <button type="button" class="availability-toggle flex items-center gap-2 cursor-pointer w-full sm:w-auto"
                                    data-url="{{ route('admin.products.availability', $product->id) }}"
                                    data-state="{{ $product->is_available ? 1 : 0 }}">
                                <span class="toggle-track relative h-6 w-11 shrink-0 rounded-full transition-colors {{ $product->is_available ? 'bg-emerald-500' : 'bg-slate-300' }}">
                                    <span class="toggle-dot absolute top-1 h-4 w-4 rounded-full bg-white shadow-md transition-all {{ $product->is_available ? 'left-6' : 'left-1' }}"></span>
                                </span>
                                <span class="toggle-label text-xs font-bold {{ $product->is_available ? 'text-emerald-700' : 'text-slate-500' }}">
                                    <span class="hidden sm:inline">{{ $product->is_available ? 'Tersedia (In Stock)' : 'Habis (Out of Stock)' }}</span>
                                    <span class="inline sm:hidden">{{ $product->is_available ? 'Tersedia' : 'Habis' }}</span>
                                </span>
                            </button>
                        </td>
                        <td class="px-6 py-4 text-right">
                            <div class="inline-flex items-center gap-3">
                                <button type="button" data-open-edit data-product='@json($product)'
                                        class="text-xs font-black text-royal hover:underline">
                                    Edit
                                </button>
                                <span class="text-slate-200">|</span>
                                <form method="POST" action="{{ route('admin.products.destroy', $product->id) }}"
                                      data-confirm="Hapus produk {{ $product->name }}? Tindakan ini tidak dapat dibatalkan."
                                      data-confirm-title="Hapus Produk?"
                                      data-confirm-btn="Ya, Hapus">
                                    @csrf
                                    @method('DELETE')
                                    <button class="text-xs font-bold text-rose-500 hover:text-rose-700 hover:underline">
                                        Hapus
                                    </button>
                                </form>
                            </div>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="5" class="px-6 py-12 text-center text-xs text-slate-400">
                            Belum ada menu produk terdaftar. Klik "+ Tambah Produk" untuk membuat menu baru.
                        </td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>

{{-- MODAL TAMBAH / EDIT PRODUK --}}
<div id="product-modal" class="fixed inset-0 z-50 hidden items-center justify-center bg-navy/70 backdrop-blur-xs p-4 overflow-y-auto">
    <div class="relative w-full max-w-lg overflow-hidden rounded-3xl bg-white shadow-2xl flex flex-col my-auto">
        <form id="product-form" method="POST" action="{{ route('admin.products.store') }}" enctype="multipart/form-data" class="flex flex-col min-h-0 text-xs">
            @csrf
            <input id="method-field" type="hidden" name="_method" value="">

            {{-- Header Modal (Sticky/Fixed Top) --}}
            <div class="p-6 pb-4 border-b border-slate-100 flex justify-between items-center bg-white shrink-0">
                <h2 id="modal-title" class="text-lg font-black text-navy">Tambah Produk Menu</h2>
                <button type="button" data-close-modal class="rounded-xl p-1.5 text-slate-400 hover:bg-slate-100 hover:text-slate-700 text-xl leading-none">&times;</button>
            </div>

            {{-- Body Modal (Scrollable Area) --}}
            <div class="p-6 overflow-y-auto max-h-[65vh] space-y-4 [&::-webkit-scrollbar]:w-2 [&::-webkit-scrollbar-thumb]:bg-slate-200 [&::-webkit-scrollbar-thumb]:rounded-full">
                <div>
                    <label class="block font-bold text-slate-700">Nama Menu Produk <span class="text-vibrant">*</span></label>
                    <input id="product-name" name="name" required placeholder="Contoh: Es Americano, Toast Keju"
                           class="mt-1.5 w-full rounded-2xl border border-slate-200 bg-slate-50 p-3 text-xs font-medium focus:border-royal focus:bg-white focus:outline-none focus:ring-1 focus:ring-royal">
                </div>

                <div>
                    <label class="block font-bold text-slate-700">Kategori <span class="text-vibrant">*</span></label>
                    <select id="product-category" name="category_id" required
                            class="mt-1.5 w-full rounded-2xl border border-slate-200 bg-slate-50 p-3 text-xs font-medium focus:border-royal focus:bg-white focus:outline-none focus:ring-1 focus:ring-royal">
                        @foreach ($categories as $category)
                            <option value="{{ $category->id }}">{{ $category->name }}</option>
                        @endforeach
                    </select>
                </div>

                <div class="grid grid-cols-1 sm:grid-cols-3 gap-3">
                    <div>
                        <label class="block font-bold text-slate-700">HPP / Modal (Rp) <span class="text-vibrant">*</span></label>
                        <input id="product-cost-price" name="cost_price" type="number" min="0" step="1" required placeholder="Contoh: 8000"
                               class="mt-1.5 w-full rounded-2xl border border-slate-200 bg-slate-50 p-3 text-xs font-medium focus:border-royal focus:bg-white focus:outline-none focus:ring-1 focus:ring-royal">
                    </div>
                    <div>
                        <label class="block font-bold text-slate-700">Harga Jual (Rp) <span class="text-vibrant">*</span></label>
                        <input id="product-price" name="price" type="number" min="0" step="1" required placeholder="Contoh: 15000"
                               class="mt-1.5 w-full rounded-2xl border border-slate-200 bg-slate-50 p-3 text-xs font-medium focus:border-royal focus:bg-white focus:outline-none focus:ring-1 focus:ring-royal">
                    </div>
                    <div>
                        <label class="block font-bold text-slate-700">Keuntungan (Otomatis)</label>
                        <div id="product-profit" class="mt-1.5 flex min-h-[42px] w-full items-center rounded-2xl border border-slate-200 bg-slate-50 px-3 text-xs text-slate-500">
                            Rp 0 (0.0%)
                        </div>
                    </div>
                </div>

                <div>
                    <label class="block font-bold text-slate-700">
                        Kuota Porsi Harian <span class="text-slate-400 font-normal text-[11px]">(Kosongkan jika tanpa batas)</span>
                    </label>
                    <input id="product-daily-stock" name="daily_stock" type="number" min="0" placeholder="Contoh: 20 (opsional, kosongkan jika tanpa batas)"
                           class="mt-1.5 w-full rounded-2xl border border-slate-200 bg-slate-50 p-3 text-xs font-medium focus:border-royal focus:bg-white focus:outline-none focus:ring-1 focus:ring-royal">
                    <p class="mt-1 text-[11px] text-slate-400">Jika diisi, sistem otomatis memotong kuota saat checkout dan mengubah status menjadi 'Habis' ketika kuota mencapai 0.</p>
                </div>

                <div>
                    <label class="block font-bold text-slate-700 mb-1.5">Foto Menu Produk (Opsional)</label>
                    
                    {{-- Drag & Drop Upload Zone --}}
                    <div id="drop-zone" class="relative group flex flex-col items-center justify-center rounded-2xl border-2 border-dashed border-slate-300 bg-slate-50/70 p-4 transition-all hover:border-royal hover:bg-royal/5">
                        <input id="product-image-file" type="file" name="image" accept="image/png, image/jpeg, image/jpg, image/webp"
                               class="absolute inset-0 z-10 h-full w-full opacity-0 cursor-pointer">

                        {{-- Image Preview Container --}}
                        <div id="preview-container" class="hidden flex-col items-center gap-2.5 text-center">
                            <div class="relative">
                                <img id="image-preview" src="#" alt="Pratinjau Foto" class="h-24 w-24 rounded-2xl object-cover shadow-md border-2 border-white ring-2 ring-slate-200">
                                <span id="preview-badge" class="absolute -top-2 -right-2 rounded-full bg-royal px-2 py-0.5 text-[10px] font-bold text-white shadow">
                                    Preview
                                </span>
                            </div>
                            <p id="preview-filename" class="text-xs font-semibold text-slate-700 truncate max-w-[280px]"></p>
                            <p class="text-[11px] font-bold text-royal hover:underline">
                                Klik atau seret file ke sini untuk mengganti foto
                            </p>
                        </div>

                        {{-- Upload Placeholder --}}
                        <div id="upload-placeholder" class="flex flex-col items-center text-center py-2">
                            <div class="flex h-11 w-11 items-center justify-center rounded-2xl bg-royal/10 text-royal transition-transform group-hover:scale-110">
                                <svg class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z" />
                                </svg>
                            </div>
                            <p class="mt-2 text-xs font-bold text-slate-700">
                                <span class="text-royal underline">Klik untuk upload</span> atau seret & lepas file
                            </p>
                            <p class="mt-0.5 text-[11px] text-slate-400">Pilih foto produk menu terbaik</p>
                        </div>
                    </div>

                    {{-- Petunjuk format & tombol batalkan --}}
                    <div class="mt-1.5 flex items-center justify-between text-[11px] text-slate-400">
                        <span>Format: JPG, PNG, WEBP (Maksimal 5 MB)</span>
                        <button type="button" id="btn-cancel-image" class="hidden text-rose-500 hover:text-rose-700 font-bold hover:underline">
                            Batal Pilih
                        </button>
                    </div>
                </div>

                <div>
                    <label class="block font-bold text-slate-700">Deskripsi Menu</label>
                    <textarea id="product-description" name="description" rows="3" placeholder="Jelaskan komposisi rasa atau bahan makanan..."
                              class="mt-1.5 w-full rounded-2xl border border-slate-200 bg-slate-50 p-3 text-xs font-medium focus:border-royal focus:bg-white focus:outline-none focus:ring-1 focus:ring-royal"></textarea>
                </div>

                <div>
                    <label class="block font-bold text-slate-700">Bahan-bahan / Komposisi (Ingredients)</label>
                    <textarea id="product-ingredients" name="ingredients" rows="2" placeholder="Contoh: Daging ayam katsu, telur mata sapi, nasi putih, saus teriyaki, wijen..."
                              class="mt-1.5 w-full rounded-2xl border border-slate-200 bg-slate-50 p-3 text-xs font-medium focus:border-royal focus:bg-white focus:outline-none focus:ring-1 focus:ring-royal"></textarea>
                    <p class="mt-1 text-[11px] text-slate-400">Pisahkan setiap bahan dengan tanda koma ( , ) agar otomatis tampil sebagai pills/label di modal detail produk pembeli.</p>
                </div>

                <label class="flex items-center gap-2 cursor-pointer font-bold text-slate-700 pt-1">
                    <input id="product-available" type="checkbox" name="is_available" value="1" checked class="rounded border-slate-300 text-royal focus:ring-royal">
                    <span>Langsung tersedia untuk dipesan (In Stock)</span>
                </label>
            </div>

            {{-- Footer Modal (Sticky/Fixed Bottom) --}}
            <div class="p-6 pt-4 border-t border-slate-100 flex justify-end gap-3 bg-slate-50 shrink-0">
                <button type="button" data-close-modal class="rounded-xl border border-slate-200 px-4 py-2.5 font-bold text-slate-600 hover:bg-slate-100 transition">
                    Batal
                </button>
                <button type="submit" class="rounded-xl bg-vibrant px-5 py-2.5 font-black text-white hover:bg-red-700 shadow-sm transition">
                    Simpan Produk
                </button>
            </div>
        </form>
    </div>
</div>

{{-- MODAL KELOLA KATEGORI --}}
<div id="category-modal" class="fixed inset-0 z-50 hidden items-center justify-center bg-navy/70 backdrop-blur-xs p-4 overflow-y-auto">
    <div class="relative w-full max-w-md overflow-hidden rounded-3xl bg-white shadow-2xl flex flex-col my-auto">
        <div class="flex items-center justify-between border-b border-slate-100 p-6 pb-4 bg-white shrink-0">
            <h2 class="text-base font-black text-navy">Kelola Kategori Menu</h2>
            <button type="button" data-close-category-modal class="rounded-xl p-1.5 text-slate-400 hover:bg-slate-100 hover:text-slate-700 text-xl leading-none">&times;</button>
        </div>

        <div class="p-6 overflow-y-auto max-h-[70vh] space-y-4 [&::-webkit-scrollbar]:w-2 [&::-webkit-scrollbar-thumb]:bg-slate-200 [&::-webkit-scrollbar-thumb]:rounded-full">
            {{-- FORM TAMBAH KATEGORI BARU --}}
            <form method="POST" action="{{ route('admin.categories.store') }}" class="border-b border-slate-100 pb-4">
                @csrf
                <label class="block text-xs font-bold text-slate-700">Nama Kategori Baru</label>
                <div class="mt-1.5 flex gap-2">
                    <input name="name" required placeholder="Contoh: Pastry, Dessert"
                           class="flex-1 rounded-xl border border-slate-200 bg-slate-50 p-2.5 text-xs font-medium focus:border-royal focus:bg-white focus:outline-none focus:ring-1 focus:ring-royal">
                    <button class="rounded-xl bg-royal px-4 py-2.5 text-xs font-black text-white hover:bg-navy transition">
                        Tambah
                    </button>
                </div>
            </form>

            {{-- DAFTAR KATEGORI YANG SUDAH ADA --}}
            <div class="space-y-2">
                <p class="text-[11px] font-bold uppercase tracking-wider text-slate-400 mb-2">Kategori Tersedia</p>
                @foreach ($categories as $cat)
                    <div class="flex items-center justify-between rounded-xl border border-slate-100 p-2.5 text-xs">
                        <div>
                            <span class="font-bold text-navy">{{ $cat->name }}</span>
                            <span class="ml-1 text-[11px] text-slate-400">({{ $cat->products_count }} produk)</span>
                        </div>
                        @if ($cat->products_count === 0)
                            <form method="POST" action="{{ route('admin.categories.destroy', $cat->id) }}"
                                  data-confirm="Hapus kategori {{ $cat->name }}?"
                                  data-confirm-title="Hapus Kategori?"
                                  data-confirm-btn="Ya, Hapus">
                                @csrf
                                @method('DELETE')
                                <button class="text-rose-600 font-bold hover:underline">Hapus</button>
                            </form>
                        @endif
                    </div>
                @endforeach
            </div>
        </div>
    </div>
</div>

@endsection

@push('scripts')
<script>
// 1. Modal Tambah / Edit Produk
const modal = document.getElementById('product-modal');
const form  = document.getElementById('product-form');
const openModal  = () => { modal.classList.remove('hidden'); modal.classList.add('flex'); };
const closeModal = () => { modal.classList.add('hidden'); modal.classList.remove('flex'); };

// Elemen Upload & Pratinjau Foto
const fileInput = document.getElementById('product-image-file');
const previewContainer = document.getElementById('preview-container');
const uploadPlaceholder = document.getElementById('upload-placeholder');
const imagePreview = document.getElementById('image-preview');
const previewFilename = document.getElementById('preview-filename');
const previewBadge = document.getElementById('preview-badge');
const btnCancelImage = document.getElementById('btn-cancel-image');
const dropZone = document.getElementById('drop-zone');

const costPriceInput = document.getElementById('product-cost-price');
const sellingPriceInput = document.getElementById('product-price');
const profitDisplay = document.getElementById('product-profit');

const formatRupiah = (value) => new Intl.NumberFormat('id-ID', {
    maximumFractionDigits: 0
}).format(Math.abs(value));

const updateProfitCalculator = () => {
    const costPrice = Number(costPriceInput.value) || 0;
    const sellingPrice = Number(sellingPriceInput.value) || 0;
    const profit = sellingPrice - costPrice;
    const marginPercent = sellingPrice > 0 ? ((profit / sellingPrice) * 100).toFixed(1) : '0.0';

    profitDisplay.textContent = `${profit < 0 ? '-' : ''}Rp ${formatRupiah(profit)} (${profit < 0 ? '-' : ''}${Math.abs(Number(marginPercent)).toFixed(1)}%)`;
    profitDisplay.classList.remove('text-emerald-600', 'text-red-600', 'text-slate-500', 'font-semibold');

    if (profit > 0) {
        profitDisplay.classList.add('text-emerald-600', 'font-semibold');
    } else if (profit < 0) {
        profitDisplay.classList.add('text-red-600', 'font-semibold');
    } else {
        profitDisplay.classList.add('text-slate-500');
    }
};

costPriceInput.addEventListener('input', updateProfitCalculator);
sellingPriceInput.addEventListener('input', updateProfitCalculator);

let currentProductImageUrl = null;

const resetImageUpload = () => {
    fileInput.value = '';
    imagePreview.src = '#';
    previewContainer.classList.add('hidden');
    previewContainer.classList.remove('flex');
    uploadPlaceholder.classList.remove('hidden');
    btnCancelImage.classList.add('hidden');
    previewFilename.textContent = '';
};

const showExistingImage = (url) => {
    if (url) {
        imagePreview.src = url;
        previewBadge.textContent = 'Foto Saat Ini';
        previewBadge.className = 'absolute -top-2 -right-2 rounded-full bg-slate-700 px-2 py-0.5 text-[10px] font-bold text-white shadow';
        previewFilename.textContent = 'Foto saat ini tetap digunakan jika tidak memilih file baru';
        previewContainer.classList.remove('hidden');
        previewContainer.classList.add('flex');
        uploadPlaceholder.classList.add('hidden');
        btnCancelImage.classList.add('hidden');
    } else {
        resetImageUpload();
    }
};

// Validasi ukuran file browser-side (Maks 5 MB) & pratinjau FileReader
fileInput.addEventListener('change', function() {
    const file = this.files && this.files[0];
    if (!file) {
        return;
    }

    const maxSize = 5 * 1024 * 1024; // 5 MB
    if (file.size > maxSize) {
        alert('Peringatan: Ukuran file melebihi batas maksimal 5 MB! Silakan pilih file foto yang lebih kecil.');
        this.value = '';
        if (currentProductImageUrl) {
            showExistingImage(currentProductImageUrl);
        } else {
            resetImageUpload();
        }
        return;
    }

    const reader = new FileReader();
    reader.onload = (e) => {
        imagePreview.src = e.target.result;
        previewBadge.textContent = 'Foto Baru (' + (file.size / (1024 * 1024)).toFixed(2) + ' MB)';
        previewBadge.className = 'absolute -top-2 -right-2 rounded-full bg-emerald-600 px-2 py-0.5 text-[10px] font-bold text-white shadow';
        previewFilename.textContent = file.name;
        previewContainer.classList.remove('hidden');
        previewContainer.classList.add('flex');
        uploadPlaceholder.classList.add('hidden');
        btnCancelImage.classList.remove('hidden');
    };
    reader.readAsDataURL(file);
});

// Tombol batal pilih file baru
btnCancelImage.addEventListener('click', (e) => {
    e.preventDefault();
    e.stopPropagation();
    fileInput.value = '';
    if (currentProductImageUrl) {
        showExistingImage(currentProductImageUrl);
    } else {
        resetImageUpload();
    }
});

// Efek visual Drag & Drop Zone
['dragenter', 'dragover'].forEach(eventName => {
    dropZone.addEventListener(eventName, (e) => {
        e.preventDefault();
        dropZone.classList.add('border-royal', 'bg-royal/10');
    });
});
['dragleave', 'drop'].forEach(eventName => {
    dropZone.addEventListener(eventName, (e) => {
        e.preventDefault();
        dropZone.classList.remove('border-royal', 'bg-royal/10');
    });
});

document.querySelector('[data-open-modal]').addEventListener('click', () => {
    form.reset();
    currentProductImageUrl = null;
    resetImageUpload();
    document.getElementById('modal-title').textContent = 'Tambah Produk Menu';
    document.getElementById('method-field').value = '';
    document.getElementById('product-ingredients').value = '';
    document.getElementById('product-daily-stock').value = '';
    form.action = "{{ route('admin.products.store') }}";
    updateProfitCalculator();
    openModal();
});

document.querySelectorAll('[data-close-modal]').forEach(b => b.addEventListener('click', closeModal));

document.querySelectorAll('[data-open-edit]').forEach(btn => btn.addEventListener('click', () => {
    const p = JSON.parse(btn.dataset.product);
    document.getElementById('modal-title').textContent = 'Edit Menu Produk';
    document.getElementById('method-field').value = 'PUT';
    form.action = `{{ url('/admin/products') }}/${p.id}`;
    document.getElementById('product-name').value = p.name;
    document.getElementById('product-category').value = p.category_id;
    document.getElementById('product-cost-price').value = (p.cost_price !== null && p.cost_price !== undefined) ? p.cost_price : 0;
    document.getElementById('product-price').value = Math.round(p.price);
    document.getElementById('product-daily-stock').value = (p.daily_stock !== null && p.daily_stock !== undefined) ? p.daily_stock : '';
    document.getElementById('product-description').value = p.description || '';
    document.getElementById('product-ingredients').value = p.ingredients || '';
    document.getElementById('product-available').checked = Boolean(p.is_available);

    currentProductImageUrl = p.image_url || (p.image ? (p.image.startsWith('http') ? p.image : `{{ asset('storage') }}/${p.image}`) : null);
    fileInput.value = '';
    showExistingImage(currentProductImageUrl);
    updateProfitCalculator();

    openModal();
}));

// 2. Modal Kategori
const catModal = document.getElementById('category-modal');
const openCatModal = () => { catModal.classList.remove('hidden'); catModal.classList.add('flex'); };
const closeCatModal = () => { catModal.classList.add('hidden'); catModal.classList.remove('flex'); };

document.querySelector('[data-open-category-modal]').addEventListener('click', openCatModal);
document.querySelectorAll('[data-close-category-modal]').forEach(b => b.addEventListener('click', closeCatModal));

// 3. Toggle Stock Availability Switch Real-time
document.querySelectorAll('.availability-toggle').forEach(btn => btn.addEventListener('click', async () => {
    btn.disabled = true;
    try {
        const res = await fetch(btn.dataset.url, {
            method: 'PATCH',
            headers: {
                'X-CSRF-TOKEN': '{{ csrf_token() }}',
                'Accept': 'application/json'
            }
        });
        if (!res.ok) {
            showToast('Gagal memperbarui status ketersediaan stok.', 'error');
            btn.disabled = false;
            return;
        }
        const data = await res.json();
        const isAvail = Boolean(data.is_available);
        btn.dataset.state = isAvail ? 1 : 0;
        btn.querySelector('.toggle-track').className = `toggle-track relative h-6 w-11 rounded-full transition-colors ${isAvail ? 'bg-emerald-500' : 'bg-slate-300'}`;
        btn.querySelector('.toggle-dot').className   = `toggle-dot absolute top-1 h-4 w-4 rounded-full bg-white shadow-md transition-all ${isAvail ? 'left-6' : 'left-1'}`;
        const label = btn.querySelector('.toggle-label');
        label.textContent = isAvail ? 'Tersedia (In Stock)' : 'Habis (Out of Stock)';
        label.className = `toggle-label text-xs font-bold ${isAvail ? 'text-emerald-700' : 'text-slate-500'}`;
        showToast(isAvail ? 'Stok produk diubah menjadi Tersedia' : 'Stok produk diubah menjadi Habis', 'success');
    } catch (e) {
        showToast('Terjadi kesalahan jaringan.', 'error');
    } finally {
        btn.disabled = false;
    }
}));
</script>
@endpush