@extends('admin.layout')

@section('title', 'Kitchen Live Feed')
@section('page_label', 'Kitchen Display System')
@section('page_title', 'Antrean Pesanan')

@push('head')
<meta http-equiv="refresh" content="20">
@endpush

@section('content')

{{-- KDS Column grid --}}
<div class="grid gap-4 xl:grid-cols-4">
    @foreach ([
        'pending'   => ['Menunggu',      'Pesanan baru masuk',    'border-vibrant',     'bg-red-50 text-vibrant'],
        'preparing' => ['Sedang Dimasak','Pesanan sedang diproses','border-yellow-500', 'bg-yellow-50 text-yellow-700'],
        'ready'     => ['Siap Diambil',  'Panggil pelanggan',     'border-green-500',  'bg-green-50 text-green-700'],
        'completed' => ['Selesai',       'Riwayat hari ini',      'border-slate-300',  'bg-slate-100 text-slate-500'],
    ] as $status => [$title, $subtitle, $border, $badgeCls])

        <section>
            {{-- Column header --}}
            <div class="mb-3 flex items-end justify-between">
                <div>
                    <h2 class="font-black text-navy">{{ $title }}</h2>
                    <p class="text-xs text-slate-400">{{ $subtitle }}</p>
                </div>
                <span class="rounded-full bg-white px-3 py-1 text-xs font-bold shadow-sm {{ $badgeCls }}">
                    {{ $orders->where('order_status', $status)->count() }}
                </span>
            </div>

            {{-- Order cards --}}
            <div class="space-y-4">
                @forelse ($orders->where('order_status', $status) as $order)
                    <article class="rounded-2xl border-t-8 {{ $border }} bg-white p-4 shadow-sm hover:shadow-md transition-shadow">
                        <div class="flex items-start justify-between gap-3">
                            <div>
                                <p class="text-4xl font-black text-navy">{{ $order->queue_number }}</p>
                                <p class="font-bold text-slate-700">{{ $order->customer_name }}</p>
                            </div>
                            <span class="rounded-full bg-slate-100 px-2 py-1 text-[11px] font-bold text-slate-500">
                                {{ $order->order_type === 'dine_in' ? 'Dine-in · Meja '.$order->table_number : 'Takeaway' }}
                            </span>
                        </div>

                        <ul class="my-4 space-y-2 border-y border-slate-100 py-4">
                            @foreach ($order->items as $item)
                                <li>
                                    <div class="flex justify-between gap-2 text-sm">
                                        <span><b>{{ $item->quantity }}x</b> {{ $item->product->name ?? 'Produk' }}</span>
                                        <span class="font-bold text-navy">Rp {{ number_format($item->subtotal, 0, ',', '.') }}</span>
                                    </div>
                                    @if ($item->notes)
                                        <p class="mt-1 rounded-lg bg-amber-50 px-2 py-1 text-[11px] italic text-amber-800">
                                            Catatan: {{ $item->notes }}
                                        </p>
                                    @endif
                                </li>
                            @endforeach
                        </ul>

                        @if ($status !== 'completed')
                            <div class="grid grid-cols-2 gap-2">
                                <form method="POST" action="{{ route('admin.orders.status', $order->id) }}">
                                    @csrf @method('PATCH')
                                    <input type="hidden" name="order_status"
                                           value="{{ $status === 'pending' ? 'preparing' : ($status === 'preparing' ? 'ready' : 'completed') }}">
                                    <button class="w-full rounded-xl bg-royal px-2 py-2.5 text-xs font-black text-white hover:bg-[#0d3a7a] transition-colors">
                                        {{ $status === 'pending' ? 'Terima Pesanan' : ($status === 'preparing' ? 'Tandai Siap' : 'Selesaikan') }}
                                    </button>
                                </form>
                                @if ($status === 'ready')
                                    <button type="button" class="announce-button rounded-xl border-2 border-vibrant px-2 py-2.5 text-xs font-black text-vibrant hover:bg-red-50 transition-colors"
                                            data-name="{{ $order->customer_name }}"
                                            data-queue="{{ $order->queue_number }}">
                                        Panggil Nama
                                    </button>
                                @else
                                    <form method="POST" action="{{ route('admin.orders.status', $order->id) }}">
                                        @csrf @method('PATCH')
                                        <input type="hidden" name="order_status" value="cancelled">
                                        <button class="w-full rounded-xl border border-red-200 px-2 py-2.5 text-xs font-bold text-red-500 hover:bg-red-50 transition-colors">
                                            Batalkan
                                        </button>
                                    </form>
                                @endif
                            </div>
                        @endif
                    </article>
                @empty
                    <div class="rounded-2xl border border-dashed border-slate-200 p-6 text-center text-sm text-slate-400">
                        Belum ada pesanan.
                    </div>
                @endforelse
            </div>
        </section>
    @endforeach
</div>

{{-- Announce toast --}}
<div id="announce-toast"
     class="pointer-events-none fixed bottom-6 left-1/2 hidden -translate-x-1/2 rounded-2xl bg-navy px-6 py-4 text-center text-white shadow-2xl z-50">
    <p class="text-xs text-blue-200">MEMANGGIL PELANGGAN</p>
    <p id="announce-label" class="mt-1 text-xl font-black"></p>
</div>

@endsection

@push('scripts')
<script>
document.querySelectorAll('.announce-button').forEach(btn => btn.addEventListener('click', () => {
    const label = `${btn.dataset.name}, pesanan ${btn.dataset.queue} siap diambil`;
    const toast = document.getElementById('announce-toast');
    document.getElementById('announce-label').textContent = label;
    toast.classList.remove('hidden');
    if ('speechSynthesis' in window) {
        window.speechSynthesis.cancel();
        window.speechSynthesis.speak(new SpeechSynthesisUtterance(label));
    }
    setTimeout(() => toast.classList.add('hidden'), 5000);
}));
</script>
@endpush