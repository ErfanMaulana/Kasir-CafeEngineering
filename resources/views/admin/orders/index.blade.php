@extends('admin.layout')

@section('title', 'Orders & Transactions')
@section('page_label', 'Riwayat Transaksi')
@section('page_title', 'Orders & Transactions')

@section('content')

{{-- ALERT MESSAGES --}}
@if (session('success'))
    <div id="alert-success" class="mb-5 flex items-center justify-between gap-3 rounded-2xl border border-emerald-200 bg-emerald-50 px-4 py-3 text-xs font-bold text-emerald-800 shadow-xs">
        <div class="flex items-center gap-2.5">
            <span class="flex h-6 w-6 shrink-0 items-center justify-center rounded-full bg-emerald-500 text-white">
                <svg class="h-3.5 w-3.5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="3"><path stroke-linecap="round" stroke-linejoin="round" d="M5 13l4 4L19 7"/></svg>
            </span>
            <span>{{ session('success') }}</span>
        </div>
        <button type="button" onclick="this.closest('#alert-success').remove()" class="rounded-lg p-1 text-emerald-600 hover:bg-emerald-100 transition">
            <svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5"><path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12"/></svg>
        </button>
    </div>
@endif

{{-- FILTER BAR --}}
<div class="mb-6 flex flex-col gap-3 sm:flex-row sm:items-center sm:justify-between">

    {{-- Tombol Filter Periode --}}
    <div class="flex flex-wrap items-center gap-2">
        @foreach ([
            'today'      => 'Hari Ini',
            'this_week'  => 'Minggu Ini',
            'this_month' => 'Bulan Ini',
            'this_year'  => 'Tahun Ini',
            '5_years'    => '5 Tahun',
        ] as $value => $label)
            <a href="{{ route('admin.orders.index', ['period' => $value, 'search' => $search]) }}"
               class="rounded-xl px-4 py-2 text-xs font-bold transition
                      {{ $period === $value
                          ? 'bg-[#041562] text-white shadow-md'
                          : 'bg-white text-slate-600 border border-slate-200 hover:border-[#041562] hover:text-[#041562]' }}">
                {{ $label }}
            </a>
        @endforeach
    </div>

    {{-- Search & Ekspor --}}
    <div class="flex items-center gap-2">
        <form method="GET" action="{{ route('admin.orders.index') }}" class="flex items-center gap-2">
            <input type="hidden" name="period" value="{{ $period }}">
            <div class="relative">
                <span class="absolute left-3 top-1/2 -translate-y-1/2 text-slate-400">
                    <svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/></svg>
                </span>
                <input type="search" name="search" value="{{ $search }}"
                       placeholder="No. Antrean / Nama Pembeli..."
                       class="w-52 rounded-xl border border-slate-200 bg-white py-2 pl-9 pr-3 text-xs font-medium outline-none focus:border-[#11468F] focus:ring-2 focus:ring-[#11468F]/20 transition">
            </div>
            <button type="submit" class="rounded-xl bg-[#041562] px-4 py-2 text-xs font-bold text-white hover:bg-[#11468F] transition">
                Cari
            </button>
        </form>

        {{-- Tombol Ekspor (placeholder) --}}
        <button type="button" onclick="alert('Fitur ekspor laporan akan segera hadir.')"
                class="flex items-center gap-1.5 rounded-xl border border-slate-200 bg-white px-4 py-2 text-xs font-bold text-slate-600 hover:border-[#041562] hover:text-[#041562] transition">
            <svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 10v6m0 0l-3-3m3 3l3-3m2 8H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/></svg>
            Ekspor
        </button>
    </div>
</div>

{{-- STATS CARDS (4 Kartu Ringkasan) --}}
<div class="mb-6 grid grid-cols-2 gap-4 lg:grid-cols-4">

    {{-- Total Omzet --}}
    <div class="stat-card rounded-2xl bg-white p-5 shadow-xs border border-slate-100">
        <div class="flex items-start justify-between">
            <div>
                <p class="text-[11px] font-bold uppercase tracking-widest text-slate-400">Total Omzet</p>
                <p class="mt-1.5 text-xl font-black text-[#041562] leading-tight">
                    Rp {{ number_format($totalRevenue, 0, ',', '.') }}
                </p>
                <p class="mt-1 text-[11px] text-slate-400">
                    @switch($period)
                        @case('today') Hari Ini @break
                        @case('this_week') Minggu Ini @break
                        @case('this_month') Bulan Ini @break
                        @case('this_year') Tahun Ini @break
                        @case('5_years') 5 Tahun Terakhir @break
                    @endswitch
                </p>
            </div>
            <span class="text-2xl">💰</span>
        </div>
    </div>

    {{-- Total Transaksi --}}
    <div class="stat-card rounded-2xl bg-white p-5 shadow-xs border border-slate-100">
        <div class="flex items-start justify-between">
            <div>
                <p class="text-[11px] font-bold uppercase tracking-widest text-slate-400">Total Pesanan</p>
                <p class="mt-1.5 text-xl font-black text-[#041562] leading-tight">{{ $totalOrders }}</p>
                <p class="mt-1 text-[11px] text-slate-400">Transaksi Selesai</p>
            </div>
            <span class="text-2xl">🧾</span>
        </div>
    </div>

    {{-- Rata-rata Nilai Pesanan (AOV) --}}
    <div class="stat-card rounded-2xl bg-white p-5 shadow-xs border border-slate-100">
        <div class="flex items-start justify-between">
            <div>
                <p class="text-[11px] font-bold uppercase tracking-widest text-slate-400">Rata-rata Pesanan</p>
                <p class="mt-1.5 text-xl font-black text-[#041562] leading-tight">
                    Rp {{ number_format($avgOrderValue, 0, ',', '.') }}
                </p>
                <p class="mt-1 text-[11px] text-slate-400">Average Order Value</p>
            </div>
            <span class="text-2xl">📊</span>
        </div>
    </div>

    {{-- QRIS vs Cash --}}
    <div class="stat-card rounded-2xl bg-white p-5 shadow-xs border border-slate-100">
        <div class="flex items-start justify-between">
            <div>
                <p class="text-[11px] font-bold uppercase tracking-widest text-slate-400">Metode Bayar</p>
                <p class="mt-1.5 text-xl font-black text-[#041562] leading-tight">{{ $topPaymentMethod }}</p>
                <p class="mt-1 text-[11px] text-slate-400">
                    QRIS {{ $qrisPct }}% &nbsp;·&nbsp; Rata-rata {{ $cashPct }}%
                </p>
            </div>
            <span class="text-2xl">💳</span>
        </div>
    </div>
</div>

{{-- TABEL RIWAYAT TRANSAKSI --}}
<div class="overflow-hidden rounded-3xl bg-white shadow-xs border border-slate-100">
    <div class="flex items-center justify-between border-b border-slate-100 px-6 py-4">
        <div>
            <h2 class="text-sm font-black text-navy">Riwayat Transaksi</h2>
            <p class="text-[11px] text-slate-400">
                Menampilkan {{ $orders->firstItem() ?? 0 }}–{{ $orders->lastItem() ?? 0 }}
                dari {{ $orders->total() }} transaksi
            </p>
        </div>
        <span class="rounded-full bg-slate-100 px-3 py-1 text-[11px] font-bold text-slate-500">
            {{ $orders->total() }} Total
        </span>
    </div>

    <div class="overflow-x-auto min-h-[380px] pb-12">
        <table class="w-full min-w-[900px] text-left text-xs">
            <thead class="border-b border-slate-100 bg-slate-50/80">
                <tr class="text-[11px] font-bold uppercase tracking-wider text-slate-400">
                    <th class="px-5 py-3.5">No. Antrean</th>
                    <th class="px-4 py-3.5">Tanggal &amp; Waktu</th>
                    <th class="px-4 py-3.5">Nama Pemanggil</th>
                    <th class="px-4 py-3.5">Tipe</th>
                    <th class="px-4 py-3.5">Total</th>
                    <th class="px-4 py-3.5">Metode Bayar</th>
                    <th class="px-4 py-3.5">Status Bayar</th>
                    <th class="px-4 py-3.5">Status Pesanan</th>
                    <th class="px-5 py-3.5 text-right">Aksi</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-slate-100">
                @forelse ($orders as $order)
                    <tr class="hover:bg-slate-50/70 transition">

                        {{-- No. Antrean --}}
                        <td class="px-5 py-4">
                            <span class="inline-flex items-center justify-center h-8 w-14 rounded-xl bg-[#041562] text-white text-xs font-black">
                                {{ $order->queue_number }}
                            </span>
                        </td>

                        {{-- Tanggal & Waktu --}}
                        <td class="px-4 py-4">
                            <p class="font-bold text-slate-700">{{ $order->created_at->format('d M Y') }}</p>
                            <p class="text-[11px] text-slate-400">{{ $order->created_at->format('H:i') }} WIB</p>
                        </td>

                        {{-- Nama Pemanggil --}}
                        <td class="px-4 py-4 font-bold text-slate-800">{{ $order->customer_name }}</td>

                        {{-- Tipe Pesanan --}}
                        <td class="px-4 py-4">
                            @if ($order->order_type === 'dine_in')
                                <span class="rounded-lg bg-blue-50 px-2.5 py-1 text-[11px] font-bold text-blue-700">
                                    Dine-in{{ $order->table_number ? ' (Meja '.$order->table_number.')' : '' }}
                                </span>
                            @else
                                <span class="rounded-lg bg-amber-50 px-2.5 py-1 text-[11px] font-bold text-amber-700">
                                    Takeaway
                                </span>
                            @endif
                        </td>

                        {{-- Total Pembayaran --}}
                        <td class="px-4 py-4 font-black text-slate-800">
                            Rp {{ number_format($order->total_amount, 0, ',', '.') }}
                        </td>

                        {{-- Metode Bayar --}}
                        <td class="px-4 py-4">
                            @if (strtolower($order->payment_method ?? '') === 'qris')
                                <span class="rounded-lg bg-violet-50 px-2.5 py-1 text-[11px] font-bold text-violet-700">QRIS</span>
                            @elseif (strtolower($order->payment_method ?? '') === 'cash')
                                <span class="rounded-lg bg-green-50 px-2.5 py-1 text-[11px] font-bold text-green-700">Cash</span>
                            @else
                                <span class="rounded-lg bg-slate-100 px-2.5 py-1 text-[11px] font-bold text-slate-500">—</span>
                            @endif
                        </td>

                        {{-- Status Bayar --}}
                        <td class="px-4 py-4">
                            @php
                                $payBadge = match($order->payment_status) {
                                    'paid'    => ['bg-emerald-50 text-emerald-700', '✓ Lunas'],
                                    'failed'  => ['bg-rose-50 text-rose-700', '✗ Gagal'],
                                    default   => ['bg-amber-50 text-amber-700', '⏳ Belum Bayar'],
                                };
                            @endphp
                            <span class="rounded-lg {{ $payBadge[0] }} px-2.5 py-1 text-[11px] font-bold">
                                {{ $payBadge[1] }}
                            </span>
                        </td>

                        {{-- Status Pesanan --}}
                        <td class="px-4 py-4">
                            @php
                                $orderBadge = match($order->order_status) {
                                    'pending'    => ['bg-amber-50 text-amber-700',  '⏳ Pending'],
                                    'preparing'  => ['bg-blue-50 text-blue-700',    '👨‍🍳 Diproses'],
                                    'ready'      => ['bg-teal-50 text-teal-700',    '✅ Siap'],
                                    'completed'  => ['bg-emerald-50 text-emerald-700', '✓ Selesai'],
                                    'cancelled'  => ['bg-rose-50 text-rose-700',    '✗ Dibatalkan'],
                                    default      => ['bg-slate-100 text-slate-500', $order->order_status],
                                };
                            @endphp
                            <span class="rounded-lg {{ $orderBadge[0] }} px-2.5 py-1 text-[11px] font-bold">
                                {{ $orderBadge[1] }}
                            </span>
                        </td>

                        {{-- Aksi (Kebab Dropdown) --}}
                        <td class="px-5 py-4 text-right relative">
                            <div class="relative inline-block text-left">
                                <button type="button"
                                        onclick="toggleActionMenu(this, event)"
                                        class="flex h-8 w-8 items-center justify-center rounded-xl border border-slate-200 bg-white text-slate-600 hover:border-[#041562] hover:text-[#041562] hover:bg-slate-50 transition shadow-xs"
                                        title="Menu Aksi">
                                    <svg class="h-4 w-4" fill="currentColor" viewBox="0 0 20 20">
                                        <path d="M10 6a2 2 0 110-4 2 2 0 010 4zM10 12a2 2 0 110-4 2 2 0 010 4zM10 18a2 2 0 110-4 2 2 0 010 4z"/>
                                    </svg>
                                </button>

                                <div class="action-dropdown-menu absolute right-0 mt-1.5 w-48 origin-top-right rounded-2xl bg-white p-1.5 shadow-xl ring-1 ring-black/5 border border-slate-100 z-30 hidden text-left space-y-0.5">
                                    {{-- 1. Tombol Detail --}}
                                    <button type="button"
                                            onclick="openDetailModal({{ $order->id }}); closeAllDropdowns();"
                                            class="flex w-full items-center gap-2.5 rounded-xl px-3 py-2 text-xs font-bold text-slate-700 hover:bg-slate-100 hover:text-[#041562] transition">
                                        <svg class="h-4 w-4 text-slate-400" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/><path stroke-linecap="round" stroke-linejoin="round" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/></svg>
                                        <span>Lihat Detail</span>
                                    </button>

                                    {{-- 2. Toggle Status Pembayaran --}}
                                    @if ($order->payment_status === 'paid')
                                        <form method="POST" action="{{ route('admin.orders.update-payment', $order->id) }}"
                                              data-confirm="Tandai pesanan {{ $order->queue_number }} ({{ $order->customer_name }}) sebagai BELUM BAYAR?"
                                              data-confirm-title="Ubah Status Bayar?"
                                              data-confirm-icon="question"
                                              data-confirm-color="#F59E0B"
                                              data-confirm-btn="Ya, Tandai Belum Bayar">
                                            @csrf
                                            @method('PATCH')
                                            <input type="hidden" name="payment_status" value="unpaid">
                                            <button type="submit"
                                                    class="flex w-full items-center gap-2.5 rounded-xl px-3 py-2 text-xs font-bold text-amber-700 hover:bg-amber-50 transition">
                                                <svg class="h-4 w-4 text-amber-500" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                                                <span>Tandai Belum Bayar</span>
                                            </button>
                                        </form>
                                    @else
                                        <form method="POST" action="{{ route('admin.orders.update-payment', $order->id) }}"
                                              data-confirm="Tandai pembayaran pesanan {{ $order->queue_number }} ({{ $order->customer_name }}) sebagai LUNAS?"
                                              data-confirm-title="Konfirmasi Pembayaran?"
                                              data-confirm-icon="question"
                                              data-confirm-color="#11468F"
                                              data-confirm-btn="Ya, Tandai Lunas">
                                            @csrf
                                            @method('PATCH')
                                            <input type="hidden" name="payment_status" value="paid">
                                            <button type="submit"
                                                    class="flex w-full items-center gap-2.5 rounded-xl px-3 py-2 text-xs font-bold text-emerald-700 hover:bg-emerald-50 transition">
                                                <svg class="h-4 w-4 text-emerald-500" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5"><path stroke-linecap="round" stroke-linejoin="round" d="M5 13l4 4L19 7"/></svg>
                                                <span>Tandai Lunas</span>
                                            </button>
                                        </form>
                                    @endif

                                    <div class="border-t border-slate-100 my-1"></div>

                                    {{-- 3. Hapus Transaksi --}}
                                    <button type="button"
                                            onclick="confirmDeleteOrder('{{ $order->id }}', '{{ $order->queue_number }}', '{{ addslashes($order->customer_name) }}')"
                                            class="flex w-full items-center gap-2.5 rounded-xl px-3 py-2 text-xs font-bold text-rose-600 hover:bg-rose-50 transition">
                                        <svg class="h-4 w-4 text-rose-500" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/></svg>
                                        <span>Hapus Riwayat</span>
                                    </button>
                                </div>
                            </div>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="9" class="px-6 py-12 text-center">
                            <p class="text-slate-400 text-sm font-bold">Tidak ada transaksi ditemukan.</p>
                            <p class="text-slate-300 text-xs mt-1">Coba ubah filter periode atau kata kunci pencarian.</p>
                        </td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    {{-- PAGINATION --}}
    @if ($orders->hasPages())
        <div class="border-t border-slate-100 px-6 py-4">
            {{ $orders->links('pagination::tailwind') }}
        </div>
    @endif
</div>

{{-- ============================================================
     MODAL DETAIL PESANAN
     ============================================================ --}}
<div id="order-detail-modal"
     class="fixed inset-0 z-50 hidden items-center justify-center bg-black/50 backdrop-blur-xs px-4">
    <div class="w-full max-w-lg rounded-3xl bg-white shadow-2xl overflow-hidden">

        {{-- Modal Header --}}
        <div class="flex items-center justify-between border-b border-slate-100 px-6 py-5 bg-[#041562]">
            <div>
                <p class="text-[11px] font-bold uppercase tracking-widest text-blue-200">Rincian Pesanan</p>
                <h3 id="modal-queue-number" class="text-lg font-black text-white mt-0.5">—</h3>
            </div>
            <button type="button" onclick="closeDetailModal()"
                    class="rounded-xl p-2 text-blue-200 hover:bg-white/10 transition">
                <svg class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12"/>
                </svg>
            </button>
        </div>

        {{-- Modal Body --}}
        <div class="max-h-[70vh] overflow-y-auto">

            {{-- Info Umum --}}
            <div id="modal-info" class="grid grid-cols-2 gap-4 px-6 py-5 border-b border-slate-100 text-xs">
            </div>

            {{-- Daftar Item Pesanan --}}
            <div class="px-6 py-4">
                <p class="text-[11px] font-black uppercase tracking-widest text-slate-400 mb-3">Item Dipesan</p>
                <div id="modal-items" class="space-y-2.5"></div>
            </div>

            {{-- Total --}}
            <div class="flex items-center justify-between border-t border-slate-100 px-6 py-4">
                <span class="text-xs font-bold text-slate-600">Total Pembayaran</span>
                <span id="modal-total" class="text-base font-black text-[#041562]">—</span>
            </div>

            {{-- Timeline Waktu --}}
            <div id="modal-timeline" class="border-t border-slate-100 px-6 py-4 text-xs space-y-1.5">
                <p class="text-[11px] font-black uppercase tracking-widest text-slate-400 mb-2">Timeline</p>
            </div>
        </div>
    </div>
</div>

{{-- Form tersembunyi untuk eksekusi hapus transaksi via SweetAlert2 --}}
<form id="delete-order-form" method="POST" action="" class="hidden">
    @csrf
    @method('DELETE')
</form>

{{-- Data embed untuk modal --}}
@php
    $ordersJson = $orders->getCollection()->map(function ($o) {
        return [
            'id'             => $o->id,
            'queue_number'   => $o->queue_number,
            'customer_name'  => $o->customer_name,
            'order_type'     => $o->order_type,
            'table_number'   => $o->table_number,
            'total_amount'   => $o->total_amount,
            'payment_method' => $o->payment_method,
            'payment_status' => $o->payment_status,
            'order_status'   => $o->order_status,
            'created_at'     => $o->created_at->format('d M Y, H:i'),
            'updated_at'     => $o->updated_at->format('d M Y, H:i'),
            'items'          => $o->items->map(function ($i) {
                return [
                    'name'     => optional($i->product)->name ?? 'Produk Dihapus',
                    'quantity' => $i->quantity,
                    'price'    => $i->price,
                    'subtotal' => $i->subtotal,
                    'notes'    => $i->notes,
                ];
            })->values(),
        ];
    })->values();
@endphp
<script>
const ordersData = @json($ordersJson);

function formatRupiah(val) {
    return 'Rp ' + Number(val).toLocaleString('id-ID');
}

function openDetailModal(orderId) {
    const order = ordersData.find(o => o.id === orderId);
    if (!order) return;

    document.getElementById('modal-queue-number').textContent = 'Pesanan #' + order.queue_number;

    // Info
    document.getElementById('modal-info').innerHTML = `
        <div>
            <p class="text-slate-400 font-bold mb-0.5">Nama Pemanggil</p>
            <p class="font-black text-slate-800">${order.customer_name}</p>
        </div>
        <div>
            <p class="text-slate-400 font-bold mb-0.5">Tipe Pesanan</p>
            <p class="font-black text-slate-800">${order.order_type === 'dine_in' ? 'Dine-in' + (order.table_number ? ' (Meja '+order.table_number+')' : '') : 'Takeaway'}</p>
        </div>
        <div>
            <p class="text-slate-400 font-bold mb-0.5">Metode Bayar</p>
            <p class="font-black text-slate-800">${order.payment_method ? order.payment_method.toUpperCase() : '—'}</p>
        </div>
        <div>
            <p class="text-slate-400 font-bold mb-0.5">Status Pesanan</p>
            <p class="font-black text-slate-800 capitalize">${order.order_status}</p>
        </div>
    `;

    // Items
    const itemsHtml = order.items.map(item => `
        <div class="flex items-start justify-between gap-2 rounded-xl bg-slate-50 p-3">
            <div class="min-w-0">
                <p class="font-bold text-slate-800 text-xs">${item.name}</p>
                ${item.notes ? `
                    <div class="text-xs text-amber-700 bg-amber-50 border border-amber-200 rounded-md px-2 py-1 mt-1 inline-flex items-center gap-1 font-medium">
                        <span>📝 Catatan: ${item.notes}</span>
                    </div>
                ` : ''}
                <p class="text-[11px] text-slate-500 mt-1">${item.quantity}x × ${formatRupiah(item.price)}</p>
            </div>
            <p class="font-black text-slate-800 text-xs shrink-0">${formatRupiah(item.subtotal)}</p>
        </div>
    `).join('');
    document.getElementById('modal-items').innerHTML = itemsHtml || '<p class="text-slate-400 text-xs">Tidak ada item.</p>';

    // Total
    document.getElementById('modal-total').textContent = formatRupiah(order.total_amount);

    // Timeline
    document.getElementById('modal-timeline').innerHTML = `
        <p class="text-[11px] font-black uppercase tracking-widest text-slate-400 mb-2">Timeline</p>
        <div class="flex items-center gap-2 text-slate-600"><span class="text-slate-400">📥 Dibuat</span><span class="font-bold ml-auto">${order.created_at}</span></div>
        <div class="flex items-center gap-2 text-slate-600"><span class="text-slate-400">🔄 Diperbarui</span><span class="font-bold ml-auto">${order.updated_at}</span></div>
    `;

    const modal = document.getElementById('order-detail-modal');
    modal.classList.remove('hidden');
    modal.classList.add('flex');
}

function closeDetailModal() {
    const modal = document.getElementById('order-detail-modal');
    if (!modal) return;
    modal.classList.add('hidden');
    modal.classList.remove('flex');
}

// Dropdown Action Menu
function toggleActionMenu(button, event) {
    event.stopPropagation();
    const menu = button.nextElementSibling;
    const isHidden = menu.classList.contains('hidden');
    closeAllDropdowns();
    if (isHidden) {
        menu.classList.remove('hidden');
    }
}

function closeAllDropdowns() {
    document.querySelectorAll('.action-dropdown-menu').forEach(el => el.classList.add('hidden'));
}

window.addEventListener('click', closeAllDropdowns);

// Modal Konfirmasi Hapus dengan SweetAlert2
function confirmDeleteOrder(orderId, queueNumber, customerName) {
    closeAllDropdowns();
    Swal.fire({
        title: 'Hapus Riwayat Transaksi?',
        text: `Apakah Anda yakin ingin menonaktifkan riwayat transaksi ${queueNumber} milik ${customerName}? Transaksi ini akan disembunyikan dari laporan dan omzet.`,
        icon: 'warning',
        showCancelButton: true,
        confirmButtonColor: '#DA1212',
        cancelButtonColor: '#94A3B8',
        confirmButtonText: 'Ya, Hapus',
        cancelButtonText: 'Batal',
        reverseButtons: true,
        customClass: {
            popup: 'rounded-3xl shadow-2xl p-6 font-sans',
            title: 'text-lg font-black text-slate-800',
            htmlContainer: 'text-xs text-slate-500 font-medium leading-relaxed',
            confirmButton: 'px-5 py-2.5 rounded-xl text-white font-bold text-xs shadow-md transition active:scale-95',
            cancelButton: 'px-5 py-2.5 rounded-xl border border-slate-200 bg-white text-slate-700 font-bold text-xs hover:bg-slate-50 transition active:scale-95'
        }
    }).then((result) => {
        if (result.isConfirmed) {
            const form = document.getElementById('delete-order-form');
            form.action = `/admin/orders/${orderId}`;
            form.submit();
        }
    });
}

document.getElementById('order-detail-modal').addEventListener('click', function(e) {
    if (e.target === this) closeDetailModal();
});

window.addEventListener('keydown', function(e) {
    if (e.key === 'Escape') {
        closeDetailModal();
        closeDeleteModal();
        closeAllDropdowns();
    }
});
</script>

@endsection

