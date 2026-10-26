<!doctype html>
<html lang="id">
<head><meta charset="utf-8"><meta name="viewport" content="width=device-width, initial-scale=1"><title>Checkout - Kafe Kampus</title><script src="https://cdn.tailwindcss.com"></script><script>tailwind.config={theme:{extend:{colors:{navy:'#041562',vibrant:'#DA1212',cream:'#EEEEEE'}}}}</script></head>
<body class="min-h-screen bg-cream px-4 py-8 text-slate-800">
<main class="mx-auto max-w-lg rounded-2xl bg-white p-6 shadow-sm">
    <a href="{{ route('catalog') }}" class="text-sm font-semibold text-navy">&larr; Kembali ke menu</a>
    <h1 class="mt-5 text-2xl font-bold text-navy">Konfirmasi Pesanan</h1>
    <form method="POST" action="{{ route('checkout.process') }}" class="mt-6 space-y-5">@csrf
        <div><label for="customer_name" class="mb-1 block font-medium">Nama pemesan</label>
            <input id="customer_name" name="customer_name" value="{{ old('customer_name') }}" required maxlength="50" class="w-full rounded-xl border-slate-300 p-3" placeholder="Nama untuk dipanggil">
            @error('customer_name')<p class="mt-1 text-sm text-red-600">{{ $message }}</p>@enderror
        </div>
        <fieldset><legend class="mb-2 font-medium">Tipe pesanan</legend>
            <div class="space-y-2">
                <label class="flex items-center gap-2"><input type="radio" name="order_type" value="dine_in" @checked(old('order_type') === 'dine_in')> Makan di tempat</label>
                <label class="flex items-center gap-2"><input type="radio" name="order_type" value="takeaway" @checked(old('order_type', 'takeaway') === 'takeaway')> Dibawa pulang</label>
            </div>
            @error('order_type')<p class="mt-1 text-sm text-red-600">{{ $message }}</p>@enderror
        </fieldset>
        <div><label for="table_number" class="mb-1 block font-medium">Nomor meja <span class="text-sm text-slate-500">(untuk makan di tempat)</span></label>
            <input id="table_number" name="table_number" value="{{ old('table_number') }}" maxlength="20" class="w-full rounded-xl border-slate-300 p-3">
            @error('table_number')<p class="mt-1 text-sm text-red-600">{{ $message }}</p>@enderror
        </div>
        <button class="w-full rounded-xl bg-vibrant px-4 py-3 font-bold text-white hover:bg-red-700">Konfirmasi Pesanan</button>
    </form>
</main>
</body>
</html>
