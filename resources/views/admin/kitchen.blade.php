@extends('admin.layout')

@section('title', 'Kitchen Display System (KDS)')
@section('page_label', 'Kitchen Display System')
@section('page_title', 'Antrean Pesanan Dapur')

@push('head')
<meta http-equiv="refresh" content="15">
<style>
    @keyframes kdsSoftPulse {
        0%, 100% {
            box-shadow: 0 0 0 0 rgba(218, 18, 18, 0.65);
            outline: 3px solid #DA1212;
            transform: scale(1);
        }
        50% {
            box-shadow: 0 0 0 12px rgba(245, 158, 11, 0.35);
            outline: 3px solid #F59E0B;
            transform: scale(1.02);
        }
    }
    .kds-new-ticket-pulse {
        animation: kdsSoftPulse 1.2s ease-in-out infinite;
        outline-offset: 2px;
    }
</style>
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
        {{-- Tombol Toggle Suara Notifikasi Pesanan Baru --}}
        <button id="kds-sound-toggle-btn" type="button" onclick="toggleKdsSound()"
                class="inline-flex items-center gap-1.5 rounded-xl border border-slate-200 bg-white px-3 py-1.5 text-xs font-bold transition shadow-xs hover:bg-slate-50"
                title="Aktifkan / Matikan Notifikasi Suara Pesanan Baru">
            <span id="kds-sound-icon">🔔</span>
            <span id="kds-sound-text">Suara Aktif</span>
        </button>

        {{-- Tombol Tes Suara & Izin Audio Browser --}}
        <button id="kds-sound-test-btn" type="button" onclick="testKdsAudioAlert()"
                class="inline-flex items-center gap-1 rounded-xl border border-blue-200 bg-blue-50 px-2.5 py-1.5 text-xs font-bold text-royal hover:bg-blue-100 active:scale-95 transition shadow-xs"
                title="Klik untuk mengizinkan audio browser & menguji suara chime dan pengumuman pesanan">
            <span>🔊</span>
            <span>Tes Suara</span>
        </button>

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
<div class="grid gap-4 xl:grid-cols-4">
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
                    <article id="order-card-{{ $order->id }}" data-order-id="{{ $order->id }}" class="rounded-2xl border-t-8 {{ $border }} bg-white p-4 shadow-sm hover:shadow-md transition">
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

// ==========================================
// 1. MANAJEMEN NOTIFIKASI SUARA (WEB AUDIO API & SPEECH)
// ==========================================
let kdsAudioCtx = null;

function getKdsAudioContext() {
    if (!kdsAudioCtx) {
        const AudioContextClass = window.AudioContext || window.webkitAudioContext;
        if (AudioContextClass) {
            kdsAudioCtx = new AudioContextClass();
        }
    }
    if (kdsAudioCtx && kdsAudioCtx.state === 'suspended') {
        kdsAudioCtx.resume();
    }
    return kdsAudioCtx;
}

// Efek suara Bell/Ding Kasir Cafe (harmoni kristal 3-nada)
function playKitchenDing() {
    try {
        const ctx = getKdsAudioContext();
        if (!ctx) return;

        const now = ctx.currentTime;

        // Nada 1: Bell fundamental (830Hz - G#5)
        const osc1 = ctx.createOscillator();
        const gain1 = ctx.createGain();
        osc1.type = 'sine';
        osc1.frequency.setValueAtTime(830, now);
        gain1.gain.setValueAtTime(0.35, now);
        gain1.gain.exponentialRampToValueAtTime(0.0001, now + 0.9);
        osc1.connect(gain1);
        gain1.connect(ctx.destination);
        osc1.start(now);
        osc1.stop(now + 0.9);

        // Nada 2: Bell overtone (1046.5Hz - C6)
        const osc2 = ctx.createOscillator();
        const gain2 = ctx.createGain();
        osc2.type = 'triangle';
        osc2.frequency.setValueAtTime(1046.5, now + 0.08);
        gain2.gain.setValueAtTime(0.28, now + 0.08);
        gain2.gain.exponentialRampToValueAtTime(0.0001, now + 1.2);
        osc2.connect(gain2);
        gain2.connect(ctx.destination);
        osc2.start(now + 0.08);
        osc2.stop(now + 1.2);

        // Nada 3: Chime resonansi tinggi (1318.5Hz - E6)
        const osc3 = ctx.createOscillator();
        const gain3 = ctx.createGain();
        osc3.type = 'sine';
        osc3.frequency.setValueAtTime(1318.5, now + 0.16);
        gain3.gain.setValueAtTime(0.22, now + 0.16);
        gain3.gain.exponentialRampToValueAtTime(0.0001, now + 1.5);
        osc3.connect(gain3);
        gain3.connect(ctx.destination);
        osc3.start(now + 0.16);
        osc3.stop(now + 1.5);
    } catch (err) {
        console.warn('Gagal memutar audio alert KDS:', err);
    }
}

// Pengumuman suara berbasis Web Speech API (bahasa Indonesia)
function speakKdsAnnouncement(text) {
    if ('speechSynthesis' in window) {
        window.speechSynthesis.cancel();
        const utterance = new SpeechSynthesisUtterance(text);
        utterance.lang = 'id-ID';
        utterance.rate = 1.0;
        utterance.pitch = 1.0;
        window.speechSynthesis.speak(utterance);
    }
}

// ==========================================
// 2. TOGGLE MUTE / UNMUTE DI LOCALSTORAGE & TES SUARA
// ==========================================
function isKdsSoundEnabled() {
    const saved = localStorage.getItem('kds_sound_enabled');
    return saved === null ? true : saved === 'true';
}

function updateSoundToggleButtonUI(enabled) {
    const btn = document.getElementById('kds-sound-toggle-btn');
    const icon = document.getElementById('kds-sound-icon');
    const text = document.getElementById('kds-sound-text');
    if (!btn || !icon || !text) return;

    if (enabled) {
        icon.textContent = '🔔';
        text.textContent = 'Suara Aktif';
        btn.className = 'inline-flex items-center gap-1.5 rounded-xl border border-emerald-200 bg-emerald-50 px-3 py-1.5 text-xs font-bold text-emerald-800 hover:bg-emerald-100 transition shadow-xs';
    } else {
        icon.textContent = '🔕';
        text.textContent = 'Suara Mati';
        btn.className = 'inline-flex items-center gap-1.5 rounded-xl border border-slate-200 bg-slate-100 px-3 py-1.5 text-xs font-bold text-slate-500 hover:bg-slate-200 transition shadow-xs';
    }
}

function toggleKdsSound() {
    const current = isKdsSoundEnabled();
    const newState = !current;
    localStorage.setItem('kds_sound_enabled', String(newState));
    updateSoundToggleButtonUI(newState);

    // Aktifkan AudioContext browser & bunyikan preview 1 kali saat di-unmute
    if (newState) {
        getKdsAudioContext();
        playKitchenDing();
    }
}

// Tombol Tes Suara (mengaktifkan izin browser audio autoplay & uji suara lonceng + pengumuman)
function testKdsAudioAlert() {
    getKdsAudioContext();
    if (!isKdsSoundEnabled()) {
        localStorage.setItem('kds_sound_enabled', 'true');
        updateSoundToggleButtonUI(true);
    }
    playKitchenDing();
    setTimeout(() => {
        speakKdsAnnouncement('Tes suara berhasil! Sistem notifikasi pesanan dapur aktif.');
    }, 600);

    if (typeof Swal !== 'undefined') {
        const Toast = Swal.mixin({
            toast: true,
            position: 'top-end',
            showConfirmButton: false,
            timer: 3500,
            timerProgressBar: true,
            customClass: {
                popup: 'rounded-2xl shadow-xl text-sm font-semibold',
            },
        });
        Toast.fire({
            icon: 'success',
            title: '🔊 Tes Suara & Izin Audio Browser Aktif!',
        });
    }
}

// Inisialisasi tampilan tombol suara dari localStorage
updateSoundToggleButtonUI(isKdsSoundEnabled());

// ==========================================
// 3. DETEKSI TIKET PESANAN BARU (MENUNGGU)
// ==========================================
@php
    $pendingOrdersList = $orders->where('order_status', 'pending')->map(function ($o) {
        return [
            'id' => $o->id,
            'queue_number' => $o->queue_number,
            'customer_name' => $o->customer_name,
        ];
    })->values();
@endphp

(function checkNewPendingOrders() {
    const currentPendingOrders = @json($pendingOrdersList);

    const currentPendingIds = currentPendingOrders.map(o => o.id);
    const storageKey = 'kds_pending_order_ids';
    const rawStored = localStorage.getItem(storageKey);

    if (rawStored !== null) {
        try {
            const previousIds = JSON.parse(rawStored);
            if (Array.isArray(previousIds)) {
                // Cari pesanan baru yang belum tercatat sebelumnya
                const newOrders = currentPendingOrders.filter(o => !previousIds.includes(o.id));

                if (newOrders.length > 0) {
                    if (isKdsSoundEnabled()) {
                        // Step 1: Putar nada chime/bell
                        playKitchenDing();

                        // Step 2: Ucapkan kalimat pengumuman suara
                        const queueNames = newOrders.map(o => o.queue_number).join(', ');
                        const speechText = newOrders.length === 1
                            ? `Pesanan masuk! Nomor antrean ${newOrders[0].queue_number}`
                            : `Pesanan masuk! Nomor antrean ${queueNames}`;

                        setTimeout(() => {
                            speakKdsAnnouncement(speechText);
                        }, 700);

                        // Tampilkan SweetAlert2 Toast ringan di pojok layar
                        if (typeof Swal !== 'undefined') {
                            const Toast = Swal.mixin({
                                toast: true,
                                position: 'top-end',
                                showConfirmButton: false,
                                timer: 5000,
                                timerProgressBar: true,
                                customClass: {
                                    popup: 'rounded-2xl shadow-xl text-sm font-semibold',
                                },
                            });
                            Toast.fire({
                                icon: 'warning',
                                title: `🔔 Pesanan Baru: ${queueNames}`,
                            });
                        }
                    }

                    // Step 3: Berikan efek highlight pulse & badge selama 5 detik pada kartu tiket baru
                    newOrders.forEach(order => {
                        const card = document.getElementById(`order-card-${order.id}`);
                        if (card) {
                            card.classList.add('kds-new-ticket-pulse');

                            // Tambahkan penanda badge pesanan baru masuk
                            const badge = document.createElement('div');
                            badge.id = `new-badge-${order.id}`;
                            badge.className = 'mb-2 inline-flex items-center gap-1 rounded-full bg-red-100 border border-red-300 px-2.5 py-0.5 text-[11px] font-black text-red-700 animate-pulse';
                            badge.innerHTML = '<span>🔔</span> PESANAN BARU MASUK!';
                            card.insertBefore(badge, card.firstChild);

                            // Hilangkan animasi pulse & badge setelah 5 detik
                            setTimeout(() => {
                                card.classList.remove('kds-new-ticket-pulse');
                                const badgeEl = document.getElementById(`new-badge-${order.id}`);
                                if (badgeEl) badgeEl.remove();
                            }, 5000);
                        }
                    });
                }
            }
        } catch (e) {
            console.warn('Gagal membaca kds_pending_order_ids dari storage:', e);
        }
    }

    // Selalu perbarui cache daftar ID pesanan berstatus 'Menunggu' yang ada saat ini
    localStorage.setItem(storageKey, JSON.stringify(currentPendingIds));
})();

// ==========================================
// 4. PEMANGGILAN SUARA PELANGGAN (TEXT-TO-SPEECH)
// ==========================================
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