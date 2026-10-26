@extends('admin.layout')

@section('title', 'Dashboard')
@section('page_label', 'Sales & Analytics')
@section('page_title', 'Dashboard Admin')

@section('content')

{{-- Sub header --}}
<div class="mb-5 flex flex-wrap items-center justify-between gap-3">
    <div class="flex flex-wrap items-center gap-2">
        <span class="inline-flex items-center gap-1.5 rounded-full bg-orange-100 px-3 py-1 text-[11px] font-bold text-orange-700">
            <span class="h-1.5 w-1.5 rounded-full bg-orange-500"></span>
            TERM 2 &middot; EXAM SPRINT
        </span>
        <span class="text-[12px] text-slate-500">Circuit Node: Hall B Espresso Bar</span>
    </div>
    <div class="flex flex-wrap gap-2">
        <a href="#" class="rounded-lg border border-slate-200 bg-white px-3 py-1.5 text-xs font-semibold text-slate-600 hover:bg-slate-50 transition-colors">Today</a>
        <a href="#" class="rounded-lg bg-navy px-3 py-1.5 text-xs font-bold text-white">Past 7 Days</a>
        <a href="#" class="rounded-lg border border-slate-200 bg-white px-3 py-1.5 text-xs font-semibold text-slate-600 hover:bg-slate-50 transition-colors">Month to Date</a>
        <a href="#" class="rounded-lg border border-red-200 bg-red-50 px-3 py-1.5 text-xs font-bold text-vibrant hover:bg-red-100 transition-colors">&uarr; Finals Rush</a>
    </div>
</div>

{{-- Heading + export --}}
<div class="mb-6 flex flex-wrap items-end justify-between gap-4">
    <div>
        <h2 class="text-2xl font-black leading-tight text-navy">Sales &amp; Turnover</h2>
        <h2 class="text-2xl font-black leading-tight text-navy">Analytics</h2>
    </div>
    <button class="flex items-center gap-2 rounded-xl bg-royal px-4 py-2.5 text-sm font-bold text-white shadow hover:bg-[#0d3a7a] transition-colors">
        <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-4l-4 4m0 0l-4-4m4 4V4"/></svg>
        Export Report (CSV/PDF)
    </button>
</div>

{{-- Two column layout --}}
<div class="grid gap-5 xl:grid-cols-[1fr_300px]">

    {{-- LEFT --}}
    <div class="space-y-5">

        {{-- Stat cards --}}
        <div class="grid gap-4 sm:grid-cols-2">

            <div class="stat-card rounded-2xl bg-white p-5 shadow-sm">
                <div class="flex items-start justify-between">
                    <div>
                        <p class="text-[11px] font-bold uppercase tracking-widest text-slate-400">Total Orders</p>
                        <p class="mt-1 text-4xl font-black text-navy">{{ $orderCount }}</p>
                        <p class="text-xs text-slate-400">tickets</p>
                    </div>
                    <div class="flex h-10 w-10 items-center justify-center rounded-xl bg-indigo-50 text-royal">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v10a2 2 0 002 2h8a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"/></svg>
                    </div>
                </div>
                <div class="mt-3 flex flex-wrap gap-2">
                    <span class="rounded-lg bg-green-50 px-2 py-1 text-[11px] font-bold text-green-700">Avg Ticket Rp 5.5rb</span>
                    <span class="text-[11px] text-slate-400">Peak: 10:15-11:</span>
                </div>
                <p class="mt-2 text-[11px] font-medium text-orange-600">2nd Wave Spike logged at 2</p>
            </div>

            <div class="stat-card rounded-2xl bg-white p-5 shadow-sm">
                <div class="flex items-start justify-between">
                    <div>
                        <p class="text-[11px] font-bold uppercase tracking-widest text-slate-400">Wait &amp; Prep Latency</p>
                        <p class="mt-1 text-4xl font-black text-navy">4m <span class="text-2xl">18s</span></p>
                    </div>
                    <div class="flex h-10 w-10 items-center justify-center rounded-xl bg-orange-50 text-orange-500">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                    </div>
                </div>
                <div class="mt-3 flex flex-wrap gap-2">
                    <span class="rounded-lg bg-red-50 px-2 py-1 text-[11px] font-bold text-red-600">-45s delta</span>
                    <span class="text-[11px] text-slate-400">vs. 5m SLO target</span>
                </div>
                <p class="mt-2 text-[11px] font-medium text-green-600">Barista Station B Target Met</p>
            </div>
        </div>

        {{-- Hourly chart --}}
        <div class="rounded-2xl bg-white p-5 shadow-sm">
            <div class="mb-3 flex flex-wrap items-start justify-between gap-3">
                <div>
                    <h3 class="font-bold text-navy">Hourly Sales &amp; Rush Distribution</h3>
                    <p class="text-[11px] text-slate-400">Throughput telemetry mapped from 08:00 to 18:00 with campus milestones</p>
                </div>
                <div class="flex gap-4 text-[11px] text-slate-500">
                    <span class="flex items-center gap-1.5"><span class="inline-block h-3 w-3 rounded-sm bg-royal"></span>Revenue ($)</span>
                    <span class="flex items-center gap-1.5"><span class="inline-block h-3 w-3 rounded-sm bg-orange-400"></span>Volume Spike</span>
                </div>
            </div>
            @php $bars = [35,55,80,140,130,90,120,175,165,130,95,80,65,50,40,35,30,28]; $maxBar = max($bars); @endphp
            <div class="flex items-end gap-[3px]" style="height:140px">
                @foreach($bars as $val)
                    <div class="flex-1">
                        <div class="w-full rounded-t-md bg-royal/80 hover:bg-royal transition-colors cursor-pointer"
                             style="height:{{ round(($val/$maxBar)*128) }}px; min-height:4px"></div>
                    </div>
                @endforeach
            </div>
            <div class="mt-1.5 grid text-[9px] text-slate-400" style="grid-template-columns: repeat(11, 1fr)">
                @foreach(['08:00','09:00','10:00','11:00','12:00','13:00','14:00','15:00','16:00','17:00','18:00'] as $t)
                    <span class="text-center">{{ $t }}</span>
                @endforeach
            </div>
            <div class="mt-4 border-t border-slate-100 pt-3">
                <p class="text-[11px] text-slate-500">
                    Peak kitchen throughput reached 54 orders/hr at 10:45 AM during the Circuit Theory I lecture exit.
                    <a href="#" class="ml-1 font-semibold text-royal underline">View Interval Heatmap</a>
                </p>
            </div>
        </div>

        {{-- Orders table --}}
        <div class="rounded-2xl bg-white shadow-sm">
            <div class="flex items-center justify-between px-5 pt-5 pb-4">
                <div>
                    <h3 class="font-bold text-navy">Top-Selling Menu Items &amp; Margins</h3>
                    <span class="mt-0.5 inline-block rounded-full bg-green-100 px-2 py-0.5 text-[10px] font-bold text-green-700">Live POS Rank</span>
                </div>
                <button class="flex items-center gap-1.5 rounded-lg border border-slate-200 px-3 py-1.5 text-xs font-semibold text-slate-600 hover:bg-slate-50 transition-colors">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-3.5 w-3.5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 4a1 1 0 011-1h16a1 1 0 011 1v2a1 1 0 01-.293.707L13 13.414V19a1 1 0 01-.553.894l-4 2A1 1 0 017 21v-7.586L3.293 6.707A1 1 0 013 6V4z"/></svg>
                    Filter Category
                </button>
            </div>
            <div class="overflow-x-auto">
                <table class="w-full min-w-[600px] text-sm">
                    <thead>
                        <tr class="border-y border-slate-100 bg-slate-50 text-left">
                            <th class="px-5 py-3 text-[11px] font-bold uppercase tracking-wide text-slate-400">Rank &amp; Item</th>
                            <th class="px-4 py-3 text-[11px] font-bold uppercase tracking-wide text-slate-400">Units Sold</th>
                            <th class="px-4 py-3 text-[11px] font-bold uppercase tracking-wide text-slate-400">Gross Rev</th>
                            <th class="px-4 py-3 text-[11px] font-bold uppercase tracking-wide text-slate-400">Profit Margin</th>
                            <th class="px-4 py-3 text-[11px] font-bold uppercase tracking-wide text-slate-400">Stock Status</th>
                            <th class="px-4 py-3 text-[11px] font-bold uppercase tracking-wide text-slate-400">Action</th>
                        </tr>
                    </thead>
                    <tbody>
                    @forelse($recentOrders as $order)
                        <tr class="border-b border-slate-100 hover:bg-slate-50 transition-colors">
                            <td class="px-5 py-4">
                                <div class="flex items-center gap-3">
                                    <span class="flex h-7 w-7 flex-shrink-0 items-center justify-center rounded-lg bg-navy text-xs font-black text-white">{{ $loop->iteration }}</span>
                                    <div>
                                        <p class="font-bold text-navy">{{ $order->customer_name }}</p>
                                        <p class="text-[11px] text-slate-400">{{ $order->queue_number }}</p>
                                    </div>
                                </div>
                            </td>
                            <td class="px-4 py-4 font-semibold">{{ $order->items->sum('quantity') }}</td>
                            <td class="px-4 py-4 font-bold text-navy">Rp {{ number_format($order->total_amount, 0, ',', '.') }}</td>
                            <td class="px-4 py-4">
                                <span class="flex items-center gap-1 text-sm font-bold text-green-600">
                                    {{ rand(60, 82) }}%
                                    <svg xmlns="http://www.w3.org/2000/svg" class="h-3 w-3" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="3" d="M5 10l7-7m0 0l7 7m-7-7v18"/></svg>
                                </span>
                            </td>
                            <td class="px-4 py-4">
                                @php
                                    $sMap=['pending'=>['Pending','bg-yellow-100 text-yellow-700'],'preparing'=>['Preparing','bg-blue-100 text-blue-700'],'ready'=>['Ready','bg-green-100 text-green-700'],'completed'=>['Completed','bg-slate-100 text-slate-600'],'cancelled'=>['Cancelled','bg-red-100 text-red-600']];
                                    [$lbl,$cls]=$sMap[$order->order_status]??['--','bg-slate-100 text-slate-500'];
                                @endphp
                                <span class="rounded-full px-2.5 py-1 text-[11px] font-bold {{ $cls }}">{{ $lbl }}</span>
                            </td>
                            <td class="px-4 py-4">
                                <button class="text-slate-300 hover:text-slate-600 transition-colors">
                                    <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 5v.01M12 12v.01M12 19v.01M12 6a1 1 0 110-2 1 1 0 010 2zm0 7a1 1 0 110-2 1 1 0 010 2zm0 7a1 1 0 110-2 1 1 0 010 2z"/></svg>
                                </button>
                            </td>
                        </tr>
                    @empty
                        <tr><td colspan="6" class="px-5 py-8 text-center text-slate-400">Belum ada pesanan.</td></tr>
                    @endforelse
                    </tbody>
                </table>
            </div>
            <div class="flex items-center justify-between border-t border-slate-100 px-5 py-3">
                <p class="text-[12px] text-slate-400">Showing top 5 of {{ $orderCount }} catalog items</p>
                <a href="{{ route('admin.products') }}" class="text-[12px] font-bold text-royal hover:underline">Manage Full Catalog &amp; Recipes &rarr;</a>
            </div>
        </div>

    </div>

    {{-- RIGHT --}}
    <div class="space-y-5">

        {{-- Top category --}}
        <div class="rounded-2xl bg-white p-5 shadow-sm">
            <p class="text-[10px] font-bold uppercase tracking-widest text-slate-400">Top Category Turnover</p>
            <p class="mt-1 text-3xl font-black leading-none text-navy">Coffee</p>
            <p class="text-3xl font-black leading-none text-navy">Boosters</p>
            <div class="mt-3 flex items-end justify-between">
                <div>
                    <p class="text-xl font-black text-navy">Rp {{ number_format($revenue * 0.62, 0, ',', '.') }}</p>
                    <p class="text-[11px] text-slate-400">rev</p>
                </div>
                <span class="rounded-full bg-navy/10 px-3 py-1 text-[12px] font-bold text-navy">62.0% volume share</span>
            </div>
            <div class="mt-3 h-1.5 w-full rounded-full bg-slate-100">
                <div class="h-1.5 rounded-full bg-royal" style="width:62%"></div>
            </div>
            <div class="mt-2 flex items-center justify-between text-[11px]">
                <span class="text-slate-500">Runner Up: Bakery &amp; Sandwiches</span>
                <span class="font-bold text-navy">26%</span>
            </div>
        </div>

        {{-- Payment channels --}}
        <div class="rounded-2xl bg-white p-5 shadow-sm">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-[12px] font-bold text-navy">Payment Channels</p>
                    <p class="text-[10px] text-slate-400">Faculty &amp; Student settlement mix</p>
                </div>
                <button class="text-slate-300 hover:text-slate-500 transition-colors">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                </button>
            </div>
            <div class="my-5 flex justify-center">
                <div class="relative flex h-32 w-32 items-center justify-center rounded-full"
                     style="background: conic-gradient(#11468F 0% 88%, #DA1212 88% 100%)">
                    <div class="flex h-20 w-20 flex-col items-center justify-center rounded-full bg-white shadow-inner">
                        <p class="text-[10px] font-bold text-slate-400">SETTLED</p>
                        <p class="text-[14px] font-black leading-tight text-navy">Rp {{ number_format($revenue/1000, 1) }}k</p>
                        <p class="text-[9px] font-semibold text-green-600">100% Validated</p>
                    </div>
                </div>
            </div>
            <div class="space-y-3">
                <div class="flex items-center justify-between">
                    <div class="flex items-center gap-2">
                        <span class="h-3 w-3 rounded-full bg-royal flex-shrink-0"></span>
                        <div>
                            <p class="text-[12px] font-bold text-navy">QRIS Instant</p>
                            <p class="text-[10px] text-slate-400">330 transactions</p>
                        </div>
                    </div>
                    <div class="text-right">
                        <p class="text-[13px] font-black text-navy">Rp {{ number_format($revenue*0.88, 0, ',', '.') }}</p>
                        <p class="text-[10px] text-slate-400">88.0%</p>
                    </div>
                </div>
                <div class="flex items-center justify-between">
                    <div class="flex items-center gap-2">
                        <span class="h-3 w-3 rounded-full bg-vibrant flex-shrink-0"></span>
                        <div>
                            <p class="text-[12px] font-bold text-navy">Cash Register</p>
                            <p class="text-[10px] text-slate-400">39 drawer transactions</p>
                        </div>
                    </div>
                    <div class="text-right">
                        <p class="text-[13px] font-black text-navy">Rp {{ number_format($revenue*0.12, 0, ',', '.') }}</p>
                        <p class="text-[10px] text-slate-400">12.0%</p>
                    </div>
                </div>
            </div>
        </div>

        {{-- Recent stream --}}
        <div class="rounded-2xl bg-white p-5 shadow-sm">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-[12px] font-bold text-navy">Recent Stream</p>
                    <p class="text-[10px] text-slate-400">Live Hall B POS event stream</p>
                </div>
                <span class="flex items-center gap-1 rounded-full bg-green-50 px-2 py-1 text-[10px] font-bold text-green-700">
                    <span class="h-1.5 w-1.5 rounded-full bg-green-500 animate-pulse"></span> Live Feed
                </span>
            </div>
            <div class="mt-4 space-y-2">
                @forelse($recentOrders->take(4) as $order)
                    <div class="flex items-start gap-3 rounded-xl border border-slate-100 p-3 hover:border-slate-200 transition-colors">
                        <div class="flex-shrink-0 text-center">
                            <p class="text-[11px] font-black text-royal">#E-{{ 42 - $loop->index }}</p>
                            <div class="mt-0.5 flex h-6 w-6 items-center justify-center rounded-full bg-royal text-white text-[9px] font-bold">
                                {{ strtoupper(substr($order->customer_name, 0, 2)) }}
                            </div>
                        </div>
                        <div class="min-w-0 flex-1">
                            <div class="flex items-center justify-between gap-1">
                                <p class="truncate text-[12px] font-bold text-navy">{{ Str::limit($order->customer_name, 10) }}</p>
                                <p class="flex-shrink-0 text-[12px] font-black text-navy">Rp {{ number_format($order->total_amount, 0, ',', '.') }}</p>
                            </div>
                            <p class="mt-0.5 truncate text-[10px] text-slate-400">{{ $order->items->first()?->product?->name ?? 'Pesanan' }}</p>
                            <div class="mt-1 flex items-center gap-1.5">
                                <span class="rounded-full bg-green-100 px-1.5 py-0.5 text-[9px] font-bold text-green-700">QRIS</span>
                                <span class="text-[9px] text-slate-400">{{ $loop->iteration }}m ago</span>
                            </div>
                        </div>
                    </div>
                @empty
                    <p class="py-4 text-center text-[12px] text-slate-400">Belum ada stream.</p>
                @endforelse
            </div>
        </div>

        {{-- Register sync --}}
        <div class="flex items-center justify-between rounded-xl border border-slate-200 bg-white px-4 py-3">
            <p class="text-[11px] text-slate-400">Register sync: <span class="font-bold text-green-600">0.2s</span></p>
            <a href="{{ route('admin.kitchen') }}" class="text-[12px] font-bold text-royal hover:underline">View All Transactions &rsaquo;</a>
        </div>

    </div>
</div>

@endsection