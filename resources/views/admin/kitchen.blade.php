@extends('admin.layout')

@section('title', 'Kitchen Display System (KDS)')
@section('page_label', 'Kitchen Display System')
@section('page_title', 'Antrean Pesanan Dapur')

@push('head')
<meta http-equiv="refresh" content="15">
@endpush

@section('content')

{{-- TOP BAR KDS --}}
<div class="mb-5 flex flex-wrap items-center justify-between gap-4">
    <div class="flex items-center gap-2">
        <span class="inline-flex items-center gap-1.5 rounded-full bg-emerald-100 px-3 py-1 text-xs font-bold text-emerald-800">
            <span class="h-2 w-2 rounded-full bg-emerald-500 animate-pulse"></span>
            KDS LIVE DISPATCH
        </span>
        <span class="text-xs text-slate-500 font-medium">Halaman ini otomatis me-refresh setiap 15 detik</span>
    </div>

    <div class="flex items-center gap-2">
        {{-- Jam Digital Realtime --}}
        <div class="flex items-center gap-2 font-mono font-bold text-slate-700 bg-white px-3 py-1.5 rounded-lg border border-slate-200 text-sm">
            <span class="w-2 h-2 rounded-full bg-emerald-500 animate-ping"></span>
            <span id="live-clock">00:00:00 WIB</span>
        </div>
        <button type="button" onclick="window.location.reload()"
                class="inline-flex items-center gap-1 rounded-xl border border-slate-200 bg-white px-3 py-1.5 text-xs font-bold text-slate-700 hover:bg-slate-50 transition">
            <svg class="h-3.5 w-3.5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15"/></svg>
            <span>Refresh Manual</span>
        </button>
    </div>
</div>

{{-- GRID KOLOM STATUS KDS (PENDING -> PREPARING -> READY -> COMPLETED) --}}
<div class="grid grid-cols-1 md:grid-cols-2 xl:grid-cols-4 gap-4">
    @foreach ([
        'pending'   => ['Menunggu',        'Pesanan baru masuk',     'border-vibrant',     'bg-red-50 text-vibrant border-red-200'],
        'preparing' => ['Sedang Dimasak',  'Proses dapur / barista', 'border-amber-500',   'bg-amber-50 text-amber-700 border-amber-200'],
        'ready'     => ['Siap Diambil',    'Panggil pelanggan',      'border-emerald-500', 'bg-emerald-50 text-emerald-700 border-emerald-200'],
        'completed' => ['Selesai Disajikan','Riwayat pesanan hari ini','border-slate-300', 'bg-slate-100 text-slate-500 border-slate-200'],
    ] as $status => [$title, $subtitle, $border, $badgeCls])
        @php
            $columnSubtitle = $subtitle;
            if ($status === 'completed') {
                $columnSubtitle = match($completedFilter ?? 'today') {
                    'this_week'  => 'Riwayat minggu ini',
                    'this_month' => 'Riwayat bulan ini',
                    'this_year'  => 'Riwayat tahun ini',
                    default      => 'Riwayat pesanan hari ini',
                };
            }
            $badgeCount = $status === 'completed'
                ? ($completedCount ?? $orders->where('order_status', 'completed')->count())
                : $orders->where('order_status', $status)->count();
        @endphp

        <section class="flex flex-col">
            {{-- HEADER KOLOM --}}
            <div class="mb-3 bg-white px-4 py-3 rounded-2xl shadow-xs border border-slate-100">
                <div class="flex items-start justify-between">
                    <div>
                        <h2 class="font-black text-navy text-sm">{{ $title }}</h2>
                        <p class="text-[11px] text-slate-400">{{ $columnSubtitle }}</p>
                    </div>
                    <span class="rounded-full px-2.5 py-0.5 text-xs font-black border {{ $badgeCls }}">
                        {{ $badgeCount }}
                    </span>
                </div>

                @if ($status === 'completed')
                    {{-- Filter Waktu Pills --}}
                    <div class="mt-2.5 flex items-center gap-1 border-t border-slate-100 pt-2 flex-wrap">
                        @foreach ([
                            'today'      => 'Hari Ini',
                            'this_week'  => 'Minggu Ini',
                            'this_month' => 'Bulan Ini',
                            'this_year'  => 'Tahun Ini',
                        ] as $fKey => $fLabel)
                            <a href="?filter={{ $fKey }}"
                               class="rounded-lg px-2 py-0.5 text-[10px] font-bold transition {{ ($completedFilter ?? 'today') === $fKey ? 'bg-royal text-white shadow-xs' : 'bg-slate-100 text-slate-600 hover:bg-slate-200' }}">
                                {{ $fLabel }}
                            </a>
                        @endforeach
                    </div>
                @endif
            </div>

            {{-- DAFTAR KARTU PESANAN --}}
            <div class="space-y-3.5 flex-1">
                @forelse ($orders->where('order_status', $status) as $order)
                    <article class="rounded-2xl border-t-8 {{ $border }} bg-white p-4 shadow-sm hover:shadow-md transition">
                        <div class="flex items-start justify-between gap-3">
                            <div>
                                <p class="text-4xl font-black text-navy tracking-tight">{{ $order->queue_number }}</p>
                                <p class="text-sm font-black text-slate-800 mt-0.5">{{ $order->customer_name }}</p>
                            </div>
                            <span class="rounded-full bg-slate-100 px-2.5 py-1 text-[11px] font-bold text-slate-600">
                                {{ $order->order_type === 'dine_in' ? 'Dine-in · Meja ' . ($order->table_number ?: '-') : 'Takeaway' }}
                            </span>
                        </div>

                        {{-- DAFTAR ITEM --}}
                        <ul class="my-3 space-y-2 border-y border-slate-100 py-3 text-xs">
                            @foreach ($order->items as $item)
                                <li>
                                    <div class="flex justify-between gap-2">
                                        <span class="font-medium text-slate-800">
                                            <b class="text-royal font-black mr-1">{{ $item->quantity }}x</b>
                                            {{ $item->product->name ?? 'Menu Produk' }}
                                        </span>
                                        <span class="font-bold text-navy shrink-0">
                                            Rp {{ number_format($item->subtotal, 0, ',', '.') }}
                                        </span>
                                    </div>
                                    @if (!empty($item->notes))
                                        <div class="text-xs text-amber-700 bg-amber-50 border border-amber-200 rounded-md px-2 py-1 mt-1 flex items-center gap-1 font-medium">
                                            <span>📝 Catatan: {{ $item->notes }}</span>
                                        </div>
                                    @endif
                                </li>
                            @endforeach
                        </ul>

                        {{-- TOMBOL TINDAKAN 1-KLIK --}}
                        @if ($status !== 'completed')
                            <div class="grid grid-cols-2 gap-2 mt-3">
                                <form method="POST" action="{{ route('admin.orders.status', $order->id) }}">
                                    @csrf
                                    @method('PATCH')
                                    <input type="hidden" name="order_status"
                                           value="{{ $status === 'pending' ? 'preparing' : ($status === 'preparing' ? 'ready' : 'completed') }}">
                                    <button class="w-full rounded-xl bg-royal px-2 py-2.5 text-xs font-black text-white hover:bg-navy active:scale-95 transition">
                                        {{ $status === 'pending' ? 'Terima Pesanan' : ($status === 'preparing' ? 'Tandai Siap' : 'Selesaikan') }}
                                    </button>
                                </form>

                                @if ($status === 'ready')
                                    <button type="button"
                                            class="announce-button flex items-center justify-center gap-1 rounded-xl border-2 border-vibrant px-2 py-2.5 text-xs font-black text-vibrant hover:bg-red-50 active:scale-95 transition"
                                            data-name="{{ $order->customer_name }}"
                                            data-queue="{{ $order->queue_number }}">
                                        <span>📢 Panggil</span>
                                    </button>
                                @else
                                    <form method="POST" action="{{ route('admin.orders.status', $order->id) }}"
                                          data-confirm="Batalkan pesanan {{ $order->queue_number }} milik {{ $order->customer_name }}?"
                                          data-confirm-title="Batalkan Pesanan?"
                                          data-confirm-btn="Ya, Batalkan">
                                        @csrf
                                        @method('PATCH')
                                        <input type="hidden" name="order_status" value="cancelled">
                                        <button class="w-full rounded-xl border border-red-200 px-2 py-2.5 text-xs font-bold text-rose-500 hover:bg-rose-50 active:scale-95 transition">
                                            Batalkan
                                        </button>
                                    </form>
                                @endif
                            </div>
                        @else
                            <div class="text-right text-[11px] text-slate-400 font-medium">
                                Selesai: {{ ($completedFilter ?? 'today') === 'today' ? $order->updated_at?->format('H:i') . ' WIB' : $order->updated_at?->format('d M, H:i') . ' WIB' }}
                            </div>
                        @endif
                    </article>
                @empty
                    <div class="rounded-2xl border border-dashed border-slate-200 p-8 text-center text-xs text-slate-400">
                        Tidak ada pesanan {{ strtolower($title) }}.
                    </div>
                @endforelse
            </div>
        </section>
    @endforeach
</div>

{{-- TOAST NOTIFIKASI PANGGIL PELANGGAN --}}
<div id="announce-toast"
     class="pointer-events-none fixed bottom-8 left-1/2 hidden -translate-x-1/2 rounded-2xl bg-navy px-6 py-4 text-center text-white shadow-2xl z-50 ring-2 ring-white/20 transition-all">
    <p class="text-[10px] font-black uppercase tracking-widest text-blue-200">PEMANGGILAN SUARA AKTIF</p>
    <p id="announce-label" class="mt-1 text-lg font-black"></p>
</div>

@endsection

@push('scripts')
<script>
// Jam Digital Realtime
(function updateClock() {
    const now = new Date();
    const h = String(now.getHours()).padStart(2, '0');
    const m = String(now.getMinutes()).padStart(2, '0');
    const s = String(now.getSeconds()).padStart(2, '0');
    const el = document.getElementById('live-clock');
    if (el) { el.textContent = `${h}:${m}:${s} WIB`; }
    setTimeout(updateClock, 1000);
})();

document.querySelectorAll('.announce-button').forEach(btn => btn.addEventListener('click', () => {
    const name = btn.dataset.name;
    const queue = btn.dataset.queue;
    const label = `Nomor antrean ${queue}, atas nama ${name}, pesanan Anda sudah siap diambil di counter!`;
    const toast = document.getElementById('announce-toast');
    document.getElementById('announce-label').textContent = `${queue} · ${name} dipanggil`;
    toast.classList.remove('hidden');

    if ('speechSynthesis' in window) {
        window.speechSynthesis.cancel();
        const utterance = new SpeechSynthesisUtterance(label);
        utterance.lang = 'id-ID';
        utterance.rate = 0.95;
        window.speechSynthesis.speak(utterance);
    }

    setTimeout(() => toast.classList.add('hidden'), 4500);
}));
</script>
@endpush