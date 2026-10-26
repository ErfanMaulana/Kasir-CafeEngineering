@extends('admin.layout')

@section('title', 'Products')
@section('page_label', 'Menu & Inventory')
@section('page_title', 'Manajemen Produk')

@section('content')

{{-- Header action --}}
<div class="mb-6 flex items-center justify-between gap-4">
    <p class="text-sm text-slate-500">Kelola menu dan ketersediaan stok</p>
    <button type="button" data-open-modal
            class="flex items-center gap-2 rounded-xl bg-vibrant px-4 py-2.5 text-sm font-bold text-white shadow hover:bg-red-700 transition-colors">
        <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/></svg>
        Tambah Produk
    </button>
</div>

{{-- Products table --}}
<div class="overflow-hidden rounded-2xl bg-white shadow-sm">
    <div class="overflow-x-auto">
        <table class="w-full min-w-[760px] text-left text-sm">
            <thead class="border-b border-slate-100 bg-slate-50">
                <tr>
                    <th class="px-5 py-4 text-[11px] font-bold uppercase tracking-wide text-slate-400">Produk</th>
                    <th class="px-4 py-4 text-[11px] font-bold uppercase tracking-wide text-slate-400">Kategori</th>
                    <th class="px-4 py-4 text-[11px] font-bold uppercase tracking-wide text-slate-400">Harga</th>
                    <th class="px-4 py-4 text-[11px] font-bold uppercase tracking-wide text-slate-400">Ketersediaan</th>
                    <th class="px-4 py-4 text-[11px] font-bold uppercase tracking-wide text-slate-400">Aksi</th>
                </tr>
            </thead>
            <tbody>
                @forelse ($products as $product)
                    <tr class="border-b border-slate-100 hover:bg-slate-50 transition-colors">
                        <td class="px-5 py-4">
                            <div class="flex items-center gap-3">
                                @if ($product->image)
                                    <img src="{{ asset('storage/'.$product->image) }}" alt=""
                                         class="h-12 w-12 flex-shrink-0 rounded-xl object-cover">
                                @else
                                    <div class="flex h-12 w-12 flex-shrink-0 items-center justify-center rounded-xl bg-cream text-xl">☕</div>
                                @endif
                                <div>
                                    <p class="font-bold text-navy">{{ $product->name }}</p>
                                    <p class="max-w-xs truncate text-[11px] text-slate-400">{{ $product->description }}</p>
                                </div>
                            </div>
                        </td>
                        <td class="px-4 py-4 text-sm text-slate-600">{{ $product->category->name }}</td>
                        <td class="px-4 py-4 font-bold text-navy">Rp {{ number_format($product->price, 0, ',', '.') }}</td>
                        <td class="px-4 py-4">
                            <button type="button" class="availability-toggle flex items-center gap-2"
                                    data-url="{{ route('admin.products.availability', $product->id) }}"
                                    data-state="{{ $product->is_available ? 1 : 0 }}">
                                <span class="toggle-track relative h-6 w-11 rounded-full {{ $product->is_available ? 'bg-green-500' : 'bg-slate-300' }}">
                                    <span class="toggle-dot absolute top-1 h-4 w-4 rounded-full bg-white shadow transition {{ $product->is_available ? 'left-6' : 'left-1' }}"></span>
                                </span>
                                <span class="toggle-label text-xs font-bold {{ $product->is_available ? 'text-green-700' : 'text-slate-500' }}">
                                    {{ $product->is_available ? 'In Stock' : 'Out of Stock' }}
                                </span>
                            </button>
                        </td>
                        <td class="px-4 py-4">
                            <div class="flex gap-3">
                                <button type="button" data-open-edit data-product='@json($product)'
                                        class="font-bold text-royal hover:underline">Edit</button>
                                <form method="POST" action="{{ route('admin.products.destroy', $product->id) }}"
                                      onsubmit="return confirm('Hapus produk ini?')">
                                    @csrf @method('DELETE')
                                    <button class="font-bold text-vibrant hover:underline">Hapus</button>
                                </form>
                            </div>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="5" class="px-5 py-10 text-center text-slate-400">Belum ada produk. Tambah produk pertama Anda!</td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>

{{-- Modal --}}
<div id="product-modal" class="fixed inset-0 z-50 hidden items-center justify-center bg-navy/70 p-4">
    <div class="max-h-[90vh] w-full max-w-xl overflow-y-auto rounded-2xl bg-white p-6 shadow-2xl">
        <div class="flex items-center justify-between">
            <h2 id="modal-title" class="text-xl font-black text-navy">Tambah Produk</h2>
            <button type="button" data-close-modal class="rounded-lg p-2 text-slate-400 hover:bg-slate-100 text-2xl leading-none">&times;</button>
        </div>
        <form id="product-form" method="POST" action="{{ route('admin.products.store') }}" class="mt-5 grid gap-4 sm:grid-cols-2">
            @csrf
            <input id="method-field" type="hidden" name="_method" value="">

            <div class="sm:col-span-2">
                <label class="text-sm font-bold text-slate-700">Nama produk</label>
                <input id="product-name" name="name" required
                       class="mt-1 w-full rounded-xl border border-slate-200 bg-slate-50 p-3 text-sm focus:border-royal focus:outline-none focus:ring-1 focus:ring-royal">
            </div>
            <div>
                <label class="text-sm font-bold text-slate-700">Kategori</label>
                <select id="product-category" name="category_id" required
                        class="mt-1 w-full rounded-xl border border-slate-200 bg-slate-50 p-3 text-sm focus:border-royal focus:outline-none focus:ring-1 focus:ring-royal">
                    @foreach ($categories as $category)
                        <option value="{{ $category->id }}">{{ $category->name }}</option>
                    @endforeach
                </select>
            </div>
            <div>
                <label class="text-sm font-bold text-slate-700">Harga</label>
                <input id="product-price" name="price" type="number" min="0" step="100" required
                       class="mt-1 w-full rounded-xl border border-slate-200 bg-slate-50 p-3 text-sm focus:border-royal focus:outline-none focus:ring-1 focus:ring-royal">
            </div>
            <div class="sm:col-span-2">
                <label class="text-sm font-bold text-slate-700">Path gambar</label>
                <input id="product-image" name="image"
                       class="mt-1 w-full rounded-xl border border-slate-200 bg-slate-50 p-3 text-sm focus:border-royal focus:outline-none focus:ring-1 focus:ring-royal">
            </div>
            <div class="sm:col-span-2">
                <label class="text-sm font-bold text-slate-700">Deskripsi</label>
                <textarea id="product-description" name="description" rows="3"
                          class="mt-1 w-full rounded-xl border border-slate-200 bg-slate-50 p-3 text-sm focus:border-royal focus:outline-none focus:ring-1 focus:ring-royal"></textarea>
            </div>
            <label class="flex items-center gap-2 sm:col-span-2 text-sm font-semibold text-slate-700">
                <input id="product-available" type="checkbox" name="is_available" value="1" checked
                       class="rounded border-slate-300">
                In Stock
            </label>
            <button class="rounded-xl bg-vibrant px-4 py-3 font-black text-white hover:bg-red-700 transition-colors sm:col-span-2">
                Simpan Produk
            </button>
        </form>
    </div>
</div>

@endsection

@push('scripts')
<script>
const modal = document.getElementById('product-modal');
const form  = document.getElementById('product-form');
const open  = () => { modal.classList.remove('hidden'); modal.classList.add('flex'); };
const close = () => { modal.classList.add('hidden'); modal.classList.remove('flex'); };

document.querySelector('[data-open-modal]').addEventListener('click', () => {
    form.reset();
    document.getElementById('modal-title').textContent  = 'Tambah Produk';
    document.getElementById('method-field').value = '';
    form.action = "{{ route('admin.products.store') }}";
    open();
});

document.querySelector('[data-close-modal]').addEventListener('click', close);

document.querySelectorAll('[data-open-edit]').forEach(btn => btn.addEventListener('click', () => {
    const p = JSON.parse(btn.dataset.product);
    document.getElementById('modal-title').textContent  = 'Edit Produk';
    document.getElementById('method-field').value = 'PUT';
    form.action = `{{ url('/admin/products') }}/${p.id}`;
    document.getElementById('product-name').value        = p.name;
    document.getElementById('product-category').value    = p.category_id;
    document.getElementById('product-price').value       = p.price;
    document.getElementById('product-image').value       = p.image || '';
    document.getElementById('product-description').value = p.description || '';
    document.getElementById('product-available').checked = Boolean(p.is_available);
    open();
}));

document.querySelectorAll('.availability-toggle').forEach(btn => btn.addEventListener('click', async () => {
    const res  = await fetch(btn.dataset.url, { method:'PATCH', headers:{ 'X-CSRF-TOKEN':'{{ csrf_token() }}', 'Accept':'application/json' } });
    if (!res.ok) { alert('Gagal memperbarui ketersediaan.'); return; }
    const data = await res.json();
    btn.dataset.state = data.is_available ? 1 : 0;
    btn.querySelector('.toggle-track').className = `toggle-track relative h-6 w-11 rounded-full ${data.is_available ? 'bg-green-500' : 'bg-slate-300'}`;
    btn.querySelector('.toggle-dot').className   = `toggle-dot absolute top-1 h-4 w-4 rounded-full bg-white shadow transition ${data.is_available ? 'left-6' : 'left-1'}`;
    btn.querySelector('.toggle-label').textContent  = data.is_available ? 'In Stock' : 'Out of Stock';
    btn.querySelector('.toggle-label').className    = `toggle-label text-xs font-bold ${data.is_available ? 'text-green-700' : 'text-slate-500'}`;
}));
</script>
@endpush