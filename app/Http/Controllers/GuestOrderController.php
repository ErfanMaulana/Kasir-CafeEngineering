<?php

namespace App\Http\Controllers;

use App\Models\Category;
use App\Models\Order;
use App\Models\Product;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;
use Illuminate\View\View;

class GuestOrderController extends Controller
{
    /**
     * Tampilkan katalog menu dengan filter kategori dan ringkasan keranjang.
     */
    public function index(Request $request): View
    {
        $categories = Category::query()
            ->with(['products' => function ($query): void {
                $query->where('is_available', true)->orderBy('name');
            }])
            ->orderBy('name')
            ->get();

        $cart = session('cart', []);
        $cartCount = (int) collect($cart)->sum('quantity');
        $cartTotal = (float) collect($cart)->sum(function (array $item): float {
            return (float) ($item['price'] ?? 0) * (int) ($item['quantity'] ?? 0);
        });

        return view('guest.catalog', compact('categories', 'cart', 'cartCount', 'cartTotal'));
    }

    /**
     * Tambahkan produk ke keranjang belanja (Session).
     */
    public function addToCart(Request $request, int $id): RedirectResponse|JsonResponse
    {
        $product = Product::query()->findOrFail($id);
        abort_unless($product->is_available, 404, 'Produk sedang tidak tersedia.');

        $cart = session('cart', []);
        $productId = (string) $product->getKey();

        if (isset($cart[$productId])) {
            $cart[$productId]['quantity']++;
        } else {
            $cart[$productId] = [
                'name' => $product->name,
                'price' => (float) $product->price,
                'quantity' => 1,
                'notes' => '',
            ];
        }

        session(['cart' => $cart]);

        $cartCount = (int) collect($cart)->sum('quantity');
        $cartTotal = (float) collect($cart)->sum(function (array $item): float {
            return (float) ($item['price'] ?? 0) * (int) ($item['quantity'] ?? 0);
        });

        if ($request->expectsJson() || $request->wantsJson()) {
            return response()->json([
                'success' => true,
                'message' => "{$product->name} berhasil ditambahkan ke keranjang.",
                'item_quantity' => $cart[$productId]['quantity'],
                'cart_count' => $cartCount,
                'cart_total' => $cartTotal,
                'cart_total_formatted' => 'Rp '.number_format($cartTotal, 0, ',', '.'),
            ]);
        }

        return back()->with('success', "{$product->name} berhasil ditambahkan ke keranjang.");
    }

    /**
     * Tampilkan rincian keranjang belanja saat ini.
     */
    public function cart(): View
    {
        $cart = session('cart', []);
        $products = Product::query()
            ->whereIn('id', array_keys($cart))
            ->get()
            ->keyBy('id');

        $items = collect($cart)
            ->map(function (array $cartItem, string $productId) use ($products): ?array {
                $product = $products->get($productId);
                if (! $product) {
                    return null;
                }

                $quantity = max(1, (int) ($cartItem['quantity'] ?? 1));

                return [
                    'product' => $product,
                    'quantity' => $quantity,
                    'notes' => (string) ($cartItem['notes'] ?? ''),
                    'subtotal' => (float) $product->price * $quantity,
                ];
            })
            ->filter()
            ->values();

        $cartTotal = (float) $items->sum('subtotal');

        return view('guest.cart', compact('items', 'cartTotal'));
    }

    /**
     * Perbarui kuantitas dan catatan khusus pada keranjang.
     */
    public function updateCart(Request $request): RedirectResponse|JsonResponse
    {
        // 1. Single item update (AJAX tombol +/- atau input angka)
        if ($request->has('product_id') && $request->has('quantity')) {
            $validatedSingle = $request->validate([
                'product_id' => ['required', 'string'],
                'quantity' => ['required', 'integer', 'min:0', 'max:99'],
                'notes' => ['nullable', 'string', 'max:500'],
            ]);

            $cart = session('cart', []);
            $pId = $validatedSingle['product_id'];

            if ($validatedSingle['quantity'] <= 0) {
                unset($cart[$pId]);
            } elseif (isset($cart[$pId])) {
                $cart[$pId]['quantity'] = $validatedSingle['quantity'];
                if ($request->has('notes')) {
                    $cart[$pId]['notes'] = $validatedSingle['notes'] ?? '';
                }
            }

            session(['cart' => $cart]);

            $cartCount = (int) collect($cart)->sum('quantity');
            $cartTotal = (float) collect($cart)->sum(function (array $item): float {
                return (float) ($item['price'] ?? 0) * (int) ($item['quantity'] ?? 0);
            });

            $itemQuantity = isset($cart[$pId]) ? (int) $cart[$pId]['quantity'] : 0;
            $itemSubtotal = isset($cart[$pId]) ? ((float) ($cart[$pId]['price'] ?? 0) * $itemQuantity) : 0.0;

            if ($request->expectsJson() || $request->wantsJson()) {
                return response()->json([
                    'success' => true,
                    'message' => 'Keranjang diperbarui.',
                    'product_id' => $pId,
                    'item_quantity' => $itemQuantity,
                    'item_subtotal' => $itemSubtotal,
                    'item_subtotal_formatted' => 'Rp '.number_format($itemSubtotal, 0, ',', '.'),
                    'cart_count' => $cartCount,
                    'cart_total' => $cartTotal,
                    'cart_total_formatted' => 'Rp '.number_format($cartTotal, 0, ',', '.'),
                ]);
            }

            return back()->with('success', 'Keranjang diperbarui.');
        }

        // 2. Bulk form update (misal form submit "Simpan Perubahan Jumlah & Catatan")
        $validated = $request->validate([
            'quantities' => ['required', 'array'],
            'quantities.*' => ['integer', 'min:0', 'max:99'],
            'notes' => ['nullable', 'array'],
            'notes.*' => ['nullable', 'string', 'max:500'],
        ]);

        $cart = session('cart', []);
        foreach ($validated['quantities'] as $productId => $quantity) {
            $pKey = (string) $productId;
            if (! isset($cart[$pKey])) {
                continue;
            }

            if ($quantity <= 0) {
                unset($cart[$pKey]);
            } else {
                $cart[$pKey]['quantity'] = $quantity;
            }

            if (isset($cart[$pKey]) && array_key_exists($pKey, $validated['notes'] ?? [])) {
                $cart[$pKey]['notes'] = $validated['notes'][$pKey] ?? '';
            }
        }

        session(['cart' => $cart]);

        $cartCount = (int) collect($cart)->sum('quantity');
        $cartTotal = (float) collect($cart)->sum(function (array $item): float {
            return (float) ($item['price'] ?? 0) * (int) ($item['quantity'] ?? 0);
        });

        if ($request->expectsJson() || $request->wantsJson()) {
            return response()->json([
                'success' => true,
                'message' => 'Keranjang berhasil diperbarui.',
                'cart_count' => $cartCount,
                'cart_total' => $cartTotal,
                'cart_total_formatted' => 'Rp '.number_format($cartTotal, 0, ',', '.'),
            ]);
        }

        return back()->with('success', 'Keranjang berhasil diperbarui.');
    }

    /**
     * Hapus satu item dari keranjang.
     */
    public function removeFromCart(Request $request): RedirectResponse|JsonResponse
    {
        $validated = $request->validate(['product_id' => ['required', 'string']]);
        $cart = session('cart', []);
        unset($cart[$validated['product_id']]);
        session(['cart' => $cart]);

        $cartCount = (int) collect($cart)->sum('quantity');
        $cartTotal = (float) collect($cart)->sum(function (array $item): float {
            return (float) ($item['price'] ?? 0) * (int) ($item['quantity'] ?? 0);
        });

        if ($request->expectsJson() || $request->wantsJson()) {
            return response()->json([
                'success' => true,
                'message' => 'Produk dihapus dari keranjang.',
                'product_id' => $validated['product_id'],
                'cart_count' => $cartCount,
                'cart_total' => $cartTotal,
                'cart_total_formatted' => 'Rp '.number_format($cartTotal, 0, ',', '.'),
            ]);
        }

        return back()->with('success', 'Produk dihapus dari keranjang.');
    }

    /**
     * Tampilkan formulir checkout pesanan.
     */
    public function checkout(): View|RedirectResponse
    {
        $cart = session('cart', []);
        if (empty($cart)) {
            return redirect()->route('catalog')->with('error', 'Keranjang masih kosong. Silakan pilih menu terlebih dahulu.');
        }

        $cartCount = (int) collect($cart)->sum('quantity');
        $cartTotal = (float) collect($cart)->sum(function (array $item): float {
            return (float) ($item['price'] ?? 0) * (int) ($item['quantity'] ?? 0);
        });

        return view('guest.checkout', compact('cart', 'cartCount', 'cartTotal'));
    }

    /**
     * Proses transaksi checkout guest ke database dalam atomic transaction.
     */
    public function processCheckout(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'customer_name' => ['required', 'string', 'max:50'],
            'order_type' => ['required', 'in:dine_in,takeaway'],
            'table_number' => ['required_if:order_type,dine_in', 'nullable', 'string', 'max:20'],
            'payment_method' => ['required', 'in:qris'],
        ]);

        $cart = session('cart', []);
        if (empty($cart)) {
            return redirect()->route('catalog')->with('error', 'Keranjang masih kosong.');
        }

        $order = DB::transaction(function () use ($validated, $cart): Order {
            $items = [];
            $total = 0.0;

            foreach ($cart as $productId => $cartItem) {
                $product = Product::query()->findOrFail($productId);
                abort_unless($product->is_available, 422, "Menu '{$product->name}' sedang tidak tersedia.");

                $quantity = max(1, (int) ($cartItem['quantity'] ?? 1));
                $unitPrice = (float) $product->price;
                $subtotal = $unitPrice * $quantity;
                $total += $subtotal;

                $items[] = [
                    'product_id' => $product->getKey(),
                    'quantity' => $quantity,
                    'price' => $unitPrice,
                    'subtotal' => $subtotal,
                    'notes' => (string) ($cartItem['notes'] ?? ''),
                ];
            }

            $order = Order::query()->create([
                'queue_number' => $this->nextQueueNumber(),
                'guest_token' => (string) Str::uuid(),
                'customer_name' => $validated['customer_name'],
                'order_type' => $validated['order_type'],
                'table_number' => $validated['order_type'] === 'dine_in' ? ($validated['table_number'] ?? null) : null,
                'total_amount' => $total,
                'payment_method' => $validated['payment_method'],
                'payment_status' => 'pending',
                'order_status' => 'pending',
            ]);

            foreach ($items as $item) {
                $order->items()->create($item);
            }

            return $order;
        });

        session()->forget('cart');

        return redirect()->route('order.status', $order->guest_token)
            ->with('success', "Pesanan berhasil dibuat! Nomor antrean Anda: {$order->queue_number}");
    }

    /**
     * Tampilkan status pesanan real-time berdasarkan guest_token.
     */
    public function orderStatus(Request $request, string $guest_token): View|JsonResponse
    {
        $order = Order::query()
            ->with('items.product')
            ->where('guest_token', $guest_token)
            ->firstOrFail();

        if ($request->expectsJson() || $request->wantsJson() || $request->query('json') === '1') {
            return response()->json([
                'queue_number' => $order->queue_number,
                'customer_name' => $order->customer_name,
                'order_status' => $order->order_status,
                'payment_status' => $order->payment_status,
                'order_type' => $order->order_type,
                'table_number' => $order->table_number,
                'total_amount' => (float) $order->total_amount,
                'updated_at' => $order->updated_at?->toIso8601String(),
            ]);
        }

        return view('guest.status', compact('order'));
    }

    /**
     * Dapatkan nomor antrean berikutnya untuk hari ini (misal: A-01, A-02) secara atomik.
     */
    private function nextQueueNumber(): string
    {
        $latest = Order::query()
            ->whereDate('created_at', today())
            ->latest('id')
            ->lockForUpdate()
            ->first();

        $lastNumber = 0;
        if ($latest && preg_match('/^A-(\d+)$/', (string) $latest->queue_number, $matches)) {
            $lastNumber = (int) $matches[1];
        }

        return sprintf('A-%02d', $lastNumber + 1);
    }
}
