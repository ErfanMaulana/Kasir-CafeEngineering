<!doctype html>
<html lang="id">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Checkout Pesanan</title>
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
    </style>
</head>
<body class="min-h-screen bg-cream text-slate-800"><main class="mx-auto max-w-2xl px-4 py-6"><a href="{{ route('cart') }}" class="text-sm font-bold text-navy">&larr; Kembali ke keranjang</a><div class="mt-6"><p class="text-xs font-bold uppercase tracking-widest text-royal">Langkah terakhir</p><h1 class="mt-1 text-3xl font-black text-navy">Konfirmasi Pesanan</h1><p class="mt-2 text-slate-500">Isi detail di bawah agar pesananmu mudah dipanggil.</p></div>
@if ($errors->any())<div class="mt-5 rounded-xl bg-red-100 p-4 text-red-800"><ul class="list-inside list-disc">@foreach ($errors->all() as $error)<li>{{ $error }}</li>@endforeach</ul></div>@endif
<form method="POST" action="{{ route('checkout.process') }}" class="mt-6 space-y-5">@csrf
<section class="rounded-2xl bg-white p-5 shadow-sm"><h2 class="font-black text-navy">Data pemesan</h2><label for="customer_name" class="mt-4 block text-sm font-bold">Nama pemanggil <span class="text-vibrant">*</span></label><input id="customer_name" name="customer_name" value="{{ old('customer_name') }}" required maxlength="50" class="mt-2 w-full rounded-xl border-slate-200 bg-slate-50 p-3" placeholder="Contoh: Budi"><p class="mt-2 text-xs text-slate-500">Nama ini akan dipanggil saat pesanan siap.</p></section>
<section class="rounded-2xl bg-white p-5 shadow-sm"><h2 class="font-black text-navy">Tipe pesanan</h2><div class="mt-4 grid gap-3 sm:grid-cols-2"><label class="cursor-pointer rounded-xl border-2 p-4 has-[:checked]:border-royal has-[:checked]:bg-blue-50"><input type="radio" name="order_type" value="dine_in" class="mr-2" @checked(old('order_type') === 'dine_in')> <span class="font-bold">Dine-in</span><span class="mt-1 block pl-6 text-sm text-slate-500">Makan di tempat</span></label><label class="cursor-pointer rounded-xl border-2 p-4 has-[:checked]:border-royal has-[:checked]:bg-blue-50"><input type="radio" name="order_type" value="takeaway" class="mr-2" @checked(old('order_type', 'takeaway') === 'takeaway')> <span class="font-bold">Takeaway</span><span class="mt-1 block pl-6 text-sm text-slate-500">Bawa pulang</span></label></div><div id="table-field" class="mt-4"><label for="table_number" class="text-sm font-bold">Nomor meja</label><input id="table_number" name="table_number" value="{{ old('table_number') }}" maxlength="20" class="mt-2 w-full rounded-xl border-slate-200 bg-slate-50 p-3" placeholder="Contoh: A12"></div></section>
<section class="rounded-2xl bg-white p-5 shadow-sm"><h2 class="font-black text-navy">Metode pembayaran</h2><label class="mt-4 flex cursor-pointer gap-3 rounded-xl border-2 border-royal bg-blue-50 p-4"><input type="radio" name="payment_method" value="qris" checked class="mt-1"><span><span class="font-bold">QRIS</span><span class="mt-1 block text-sm text-slate-500">Scan QRIS yang ditampilkan kasir, lalu tunjukkan bukti pembayaran saat mengambil pesanan.</span></span></label></section>
<button class="w-full rounded-xl bg-vibrant px-4 py-4 text-center text-lg font-black text-white shadow-lg shadow-red-200">Konfirmasi Pesanan</button>
</form></main>
<script>const tableField=document.getElementById('table-field'); const syncTable=()=>{const dineIn=document.querySelector('input[name="order_type"]:checked')?.value==='dine_in'; tableField.hidden=!dineIn; document.getElementById('table_number').required=dineIn;}; document.querySelectorAll('input[name="order_type"]').forEach((input)=>input.addEventListener('change',syncTable)); syncTable();</script>
</body></html>
