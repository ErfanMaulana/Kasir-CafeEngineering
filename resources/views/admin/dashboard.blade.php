@extends('admin.layout')

@section('title', 'Sales & Analytics Dashboard')
@section('page_label', 'Sales & Analytics')
@section('page_title', 'Dashboard Penjualan')

@push('head')
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
@endpush

@section('content')

{{-- SUB HEADER DENGAN ACTION BUTTON MENCALOK VIBRANT RED (#DA1212) --}}
<div class="mb-7 flex flex-wrap items-center justify-between gap-4 bg-white p-5 rounded-3xl shadow-xs border border-slate-200">
    <div>
        <div class="flex items-center gap-2">
            <span class="inline-flex items-center gap-1.5 rounded-full bg-emerald-100 px-3 py-1 text-xs font-black text-emerald-800">
                <span class="h-2 w-2 rounded-full bg-emerald-500 animate-pulse"></span>
                LIVE POS TELEMETRY
            </span>
            <span class="text-xs text-slate-500 font-bold"> Engineering Cafe · Hari Ini: {{ date('d M Y') }}</span>
        </div>
        <h2 class="mt-2 text-2xl font-black text-[#041562]">Ringkasan Kinerja Kasir &amp; Antrean</h2>
    </div>

    {{-- TOMBOL UTAMA BUKA LAYAR DAPUR (KDS) MENCALOK VIBRANT RED (#DA1212) --}}
    <a href="{{ route('admin.kitchen') }}"
       class="inline-flex items-center gap-2.5 rounded-2xl bg-[#DA1212] px-6 py-3.5 text-sm font-black text-white shadow-lg shadow-red-950/25 hover:bg-red-700 active:scale-95 transition">
        <span>📺 Buka Layar Dapur (KDS)</span>
        <svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M14 5l7 7m0 0l-7 7m7-7H3"/></svg>
    </a>
</div>

{{-- GRID 4 WIDGET KARTU STATISTIK KINERJA (WCAG AA HIGH CONTRAST) --}}
<div class="grid gap-5 sm:grid-cols-2 lg:grid-cols-4">

    {{-- 1. TOTAL OMZET HARI INI --}}
    <div class="stat-card rounded-3xl bg-white p-6 shadow-xs border border-slate-200">
        <div class="flex items-start justify-between">
            <div>
                <p class="text-xs font-black uppercase tracking-wider text-slate-500">Total Omzet Hari Ini</p>
                <p class="mt-2 text-2xl lg:text-3xl font-black text-[#041562] tracking-tight">
                    Rp {{ number_format($revenue, 0, ',', '.') }}
                </p>
            </div>
            <div class="flex h-12 w-12 items-center justify-center rounded-2xl bg-emerald-50 text-emerald-600 text-xl font-black shadow-xs">
                💰
            </div>
        </div>
        <div class="mt-4 flex items-center justify-between border-t border-slate-100 pt-3 text-xs">
            <span class="text-slate-500 font-medium">Rata-rata / Tiket</span>
            <span class="font-black text-[#11468F]">Rp {{ number_format($avgTicket, 0, ',', '.') }}</span>
        </div>
    </div>

    {{-- 2. TOTAL PESANAN HARI INI --}}
    <div class="stat-card rounded-3xl bg-white p-6 shadow-xs border border-slate-200">
        <div class="flex items-start justify-between">
            <div>
                <p class="text-xs font-black uppercase tracking-wider text-slate-500">Total Tiket Pesanan</p>
                <p class="mt-2 text-3xl font-black text-[#041562] tracking-tight">{{ $orderCount }}</p>
            </div>
            <div class="flex h-12 w-12 items-center justify-center rounded-2xl bg-blue-50 text-[#11468F] text-xl font-black shadow-xs">
                🧾
            </div>
        </div>
        <div class="mt-4 flex items-center justify-between border-t border-slate-100 pt-3 text-xs">
            <span class="text-slate-500 font-medium">Dine-in / Takeaway</span>
            <span class="font-black text-[#041562]">{{ $dineInCount }} / {{ $takeawayCount }}</span>
        </div>
    </div>

    {{-- 3. PESANAN AKTIF KDS (AKSEN VIBRANT RED) --}}
    <div class="stat-card rounded-3xl bg-white p-6 shadow-xs border-2 border-red-100">
        <div class="flex items-start justify-between">
            <div>
                <p class="text-xs font-black uppercase tracking-wider text-red-600">Pesanan Aktif (KDS)</p>
                <p class="mt-2 text-3xl font-black text-[#DA1212] tracking-tight">{{ $activeCount }}</p>
            </div>
            <div class="flex h-12 w-12 items-center justify-center rounded-2xl bg-red-50 text-[#DA1212] text-xl font-black shadow-xs animate-pulse">
                ⏳
            </div>
        </div>
        <div class="mt-4 flex items-center justify-between border-t border-slate-100 pt-3 text-xs">
            <span class="text-slate-500 font-medium">Perlu Diproses Dapur</span>
            <span class="font-black text-[#DA1212]">{{ $activeCount }} antrean</span>
        </div>
    </div>

    {{-- 4. PESANAN SELESAI --}}
    <div class="stat-card rounded-3xl bg-white p-6 shadow-xs border border-slate-200">
        <div class="flex items-start justify-between">
            <div>
                <p class="text-xs font-black uppercase tracking-wider text-slate-500">Selesai Disajikan</p>
                <p class="mt-2 text-3xl font-black text-emerald-600 tracking-tight">{{ $completedCount }}</p>
            </div>
            <div class="flex h-12 w-12 items-center justify-center rounded-2xl bg-emerald-50 text-emerald-600 text-xl font-black shadow-xs">
                ✓
            </div>
        </div>
        <div class="mt-4 flex items-center justify-between border-t border-slate-100 pt-3 text-xs">
            <span class="text-slate-500 font-medium">Pesanan Dibatalkan</span>
            <span class="font-bold text-slate-500">{{ $cancelledCount }}</span>
        </div>
    </div>

</div>

{{-- DUA KOLOM: GRAFIK PENJUALAN PER JAM & PESANAN TERBARU --}}
<div class="mt-7 grid grid-cols-1 xl:grid-cols-[1fr_380px] gap-6 w-full min-w-0">

    {{-- KOLOM KIRI: DISTRIBUSI VOLUME PENJUALAN PER JAM --}}
    <div class="rounded-3xl bg-white p-6 shadow-xs border border-slate-200 flex flex-col justify-between w-full min-w-0 overflow-hidden">
        <div>
            <div class="flex flex-wrap items-center justify-between gap-2 border-b border-slate-100 pb-4">
                <div>
                    <h3 class="text-lg font-black text-[#041562]">Distribusi Volume Penjualan Per Jam</h3>
                    <p class="text-xs text-slate-500 font-medium">Monitoring grafik lonjakan pesanan dari jam 08:00 sampai 20:00 WIB</p>
                </div>
                <span class="rounded-xl bg-[#11468F]/10 px-3 py-1.5 text-xs font-black text-[#11468F]">
                    Hari Ini
                </span>
            </div>

            {{-- CANVAS CHART.JS BAR CHART --}}
            <div class="mt-5 relative w-full h-[250px] sm:h-[300px] md:h-[350px]">
                <canvas id="hourlySalesChart"></canvas>
            </div>
        </div>

        <div class="mt-4 pt-3 border-t border-slate-100 flex flex-wrap items-center justify-between text-xs text-slate-500 font-medium">
            <span>Warna Biru: Volume Tiket Pesanan Masuk</span>
            <span class="font-black text-[#041562]">Total: {{ $orderCount }} Tiket</span>
        </div>
    </div>

    {{-- KOLOM KANAN: PANEL PESANAN TERBARU DENGAN STATUS BADGE JELAS --}}
    <div class="rounded-3xl bg-white p-6 shadow-xs border border-slate-200 w-full min-w-0 max-w-full overflow-hidden">
        <div class="flex items-center justify-between border-b border-slate-100 pb-4">
            <div>
                <h3 class="text-base font-black text-[#041562]">Pesanan Terbaru</h3>
                <p class="text-xs text-slate-400 font-medium">Live antrean realtime</p>
            </div>
            <span class="flex items-center gap-1.5 rounded-full bg-emerald-50 border border-emerald-200 px-3 py-1 text-xs font-black text-emerald-700">
                <span class="h-2 w-2 rounded-full bg-emerald-500 animate-pulse"></span>
                Live
            </span>
        </div>

        <div class="mt-4 space-y-3 max-h-[360px] overflow-y-auto pr-1">
            @forelse ($recentOrders as $order)
                <div class="rounded-2xl border border-slate-200 p-3.5 hover:border-[#11468F] transition-all bg-white shadow-xs">
                    {{-- Baris 1: No Antrean + Nama Pembeli & Total Harga --}}
                    <div class="flex items-center justify-between gap-2">
                        <div class="flex items-center gap-2 min-w-0">
                            <span class="rounded-lg bg-[#041562] px-2.5 py-1 text-xs font-black text-white shrink-0">
                                {{ $order->queue_number }}
                            </span>
                            <h4 class="font-black text-[#041562] text-sm leading-tight truncate">
                                {{ $order->customer_name }}
                            </h4>
                        </div>
                        <span class="text-sm font-black text-[#11468F] shrink-0 whitespace-nowrap">
                            Rp {{ number_format($order->total_amount, 0, ',', '.') }}
                        </span>
                    </div>

                    {{-- Baris 2: Menu Pesanan --}}
                    <div class="mt-2 text-xs text-slate-500 font-medium truncate">
                        {{ $order->items->first()?->product?->name ?? 'Menu Produk' }}
                        @if ($order->items->count() > 1)
                            <span class="text-slate-700 font-bold">+{{ $order->items->count() - 1 }} item lainnya</span>
                        @endif
                    </div>

                    {{-- Baris 3: Tipe Pesanan & Status Badge --}}
                    <div class="mt-3 flex items-center justify-between text-xs border-t border-slate-100 pt-2.5">
                        <span class="text-slate-500 font-semibold text-[11px] truncate">
                            {{ $order->order_type === 'dine_in' ? 'Dine-in (Meja ' . ($order->table_number ?: '-') . ')' : 'Takeaway' }}
                        </span>

                        @php
                            $statusStyle = match($order->order_status) {
                                'pending'   => ['label' => 'Pending',   'cls' => 'bg-amber-100 text-amber-900 border border-amber-300'],
                                'preparing' => ['label' => 'Preparing', 'cls' => 'bg-blue-100 text-[#11468F] border border-blue-300'],
                                'ready'     => ['label' => 'Ready',     'cls' => 'bg-emerald-100 text-emerald-900 border border-emerald-300'],
                                'completed' => ['label' => 'Completed', 'cls' => 'bg-slate-100 text-slate-700 border border-slate-300'],
                                default     => ['label' => ucfirst($order->order_status), 'cls' => 'bg-rose-100 text-rose-900 border border-rose-300']
                            };
                        @endphp
                        <span class="rounded-full px-3 py-0.5 text-[11px] font-black shrink-0 {{ $statusStyle['cls'] }}">
                            {{ $statusStyle['label'] }}
                        </span>
                    </div>
                </div>
            @empty
                <p class="py-12 text-center text-xs text-slate-400 font-medium">
                    Belum ada antrean pesanan yang masuk hari ini.
                </p>
            @endforelse
        </div>

        <div class="mt-4 border-t border-slate-100 pt-4 text-center">
            <a href="{{ route('admin.kitchen') }}" class="text-xs font-black text-[#11468F] hover:underline">
                Lihat Selengkapnya di Kitchen Display System &rarr;
            </a>
        </div>
    </div>

</div>

{{-- TABEL MENU TERLARIS HARI INI (BAGIAN BAWAH DENGAN HEADER DARK NAVY #041562) --}}
<div class="mt-7 rounded-3xl bg-white shadow-xs border border-slate-200 overflow-hidden">
    <div class="p-6 border-b border-slate-100 flex flex-wrap items-center justify-between gap-2">
        <div>
            <h3 class="text-lg font-black text-[#041562]">Menu Terlaris Hari Ini</h3>
            <p class="text-xs text-slate-500 font-medium">Peringkat menu favorit berdasarkan volume pesanan dan perolehan omzet</p>
        </div>
        <a href="{{ route('admin.products') }}" class="text-xs font-black text-[#11468F] hover:underline">
            Kelola Seluruh Katalog Menu &rarr;
        </a>
    </div>

    <div class="overflow-x-auto">
        <table class="w-full text-left text-xs">
            <thead>
                <tr class="bg-[#041562] text-white">
                    <th class="py-3.5 px-6 font-black uppercase tracking-wider text-[11px]">Rank &amp; Menu Produk</th>
                    <th class="py-3.5 px-4 font-black uppercase tracking-wider text-[11px] text-center">Porsi Terjual</th>
                    <th class="py-3.5 px-4 font-black uppercase tracking-wider text-[11px] text-right">Harga Satuan</th>
                    <th class="py-3.5 px-6 font-black uppercase tracking-wider text-[11px] text-right">Gross Revenue</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-slate-100">
                @forelse ($topProducts as $idx => $prod)
                    <tr class="hover:bg-slate-50/80 transition">
                        <td class="py-4 px-6 font-bold text-[#041562] flex items-center gap-3">
                            <span class="flex h-7 w-7 items-center justify-center rounded-xl bg-[#11468F] text-xs font-black text-white shadow-xs">
                                {{ $idx + 1 }}
                            </span>
                            <div>
                                <p class="font-black text-[#041562] text-sm leading-tight">{{ $prod->name }}</p>
                                <p class="text-[11px] text-slate-400 font-medium mt-0.5">Menu Kafe</p>
                            </div>
                        </td>
                        <td class="py-4 px-4 text-center font-black text-[#11468F] text-sm">
                            {{ $prod->total_sold }} porsi
                        </td>
                        <td class="py-4 px-4 text-right text-slate-700 font-semibold">
                            Rp {{ number_format($prod->price, 0, ',', '.') }}
                        </td>
                        <td class="py-4 px-6 text-right font-black text-[#041562] text-sm">
                            Rp {{ number_format($prod->gross_rev, 0, ',', '.') }}
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="4" class="py-10 text-center text-xs text-slate-400 font-medium">
                            Belum ada riwayat transaksi menu untuk hari ini.
                        </td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>

@endsection

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', () => {
    // Inisialisasi Chart.js untuk Distribusi Penjualan Per Jam
    const ctx = document.getElementById('hourlySalesChart');
    if (ctx) {
        const hourlyData = @json($hourlyData);
        const labels = Object.values(hourlyData).map(item => item.hour);
        const counts = Object.values(hourlyData).map(item => item.count);

        new Chart(ctx, {
            type: 'bar',
            data: {
                labels: labels,
                datasets: [{
                    label: 'Jumlah Pesanan',
                    data: counts,
                    backgroundColor: '#11468F',
                    hoverBackgroundColor: '#041562',
                    borderRadius: 8,
                    borderSkipped: false,
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                plugins: {
                    legend: { display: false },
                    tooltip: {
                        backgroundColor: '#041562',
                        titleFont: { family: 'Plus Jakarta Sans', weight: 'bold' },
                        bodyFont: { family: 'Plus Jakarta Sans' },
                        padding: 10,
                        cornerRadius: 8,
                        callbacks: {
                            label: function(context) {
                                const count = context.raw || 0;
                                return ` ${count} Pesanan Masuk`;
                            }
                        }
                    }
                },
                scales: {
                    x: {
                        grid: { display: false },
                        ticks: {
                            font: { family: 'Plus Jakarta Sans', size: 9, weight: '600' },
                            color: '#64748b',
                            maxRotation: 0,
                            autoSkip: true,
                            maxTicksLimit: 7
                        }
                    },
                    y: {
                        beginAtZero: true,
                        grid: { color: '#f1f5f9' },
                        ticks: { stepSize: 1, font: { family: 'Plus Jakarta Sans', size: 10 }, color: '#64748b' }
                    }
                }
            }
        });
    }
});
</script>
@endpush