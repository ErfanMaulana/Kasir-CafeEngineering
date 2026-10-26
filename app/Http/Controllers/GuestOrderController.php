<?php

namespace App\Http\Controllers;

use App\Models\Category;
use App\Models\Order;
use App\Models\Product;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;
use Illuminate\View\View;

class GuestOrderController extends Controller
{
    public function index(): View
    {
        $categories = Category::query()
            ->with(['products' => fn ($query) => $query->where('is_available', true)])
            ->orderBy('name')
            ->get();

        $cart = session('cart', []);
        $cartCount = collect($cart)->sum('quantity');
        $cartTotal = collect($cart)->sum(fn (array $item): float => (float) ($item['price'] ?? 0) * (int) ($item['quantity'] ?? 0));

        return view('guest.catalog', compact('categories', 'cart', 'cartCount', 'cartTotal'));
    }

    public function addToCart(Request $request, int $id): RedirectResponse
    {
        $product = Product::query()->findOrFail($id);
        abort_unless($product->is_available, 404);

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

        return back()->with('success', "{$product->name} ditambahkan ke keranjang.");
    }

    public function cart(): View
    {
        $cart = session('cart', []);
        $products = Product::query()
            ->whereIn('id', array_keys($cart))
            ->where('is_available', true)
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

        return view('guest.cart', compact('items'));
    }

    public function updateCart(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'quantities' => ['required', 'array'],
            'quantities.*' => ['integer', 'min:0', 'max:99'],
            'notes' => ['nullable', 'array'],
            'notes.*' => ['nullable', 'string', 'max:500'],
        ]);

        $cart = session('cart', []);
        foreach ($validated['quantities'] as $productId => $quantity) {
            if (! isset($cart[$productId])) {
                continue;
            }

            if ($quantity === 0) {
                unset($cart[$productId]);
            } else {
                $cart[$productId]['quantity'] = $quantity;
            }

            if (isset($cart[$productId]) && array_key_exists($productId, $validated['notes'] ?? [])) {
                $cart[$productId]['notes'] = $validated['notes'][$productId] ?? '';
            }
        }

        session(['cart' => $cart]);

        return back()->with('success', 'Keranjang diperbarui.');
    }

    public function removeFromCart(Request $request): RedirectResponse
    {
        $validated = $request->validate(['product_id' => ['required', 'string']]);
        $cart = session('cart', []);
        unset($cart[$validated['product_id']]);
        session(['cart' => $cart]);

        return back()->with('success', 'Produk dihapus dari keranjang.');
    }

    public function checkout(): View|RedirectResponse
    {
        if (empty(session('cart', []))) {
            return redirect()->route('catalog')->with('error', 'Keranjang masih kosong.');
        }

        return view('guest.checkout');
    }

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
                abort_unless($product->is_available, 422, 'Salah satu produk sudah tidak tersedia.');

                $quantity = max(1, (int) $cartItem['quantity']);
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
                'table_number' => $validated['table_number'] ?? null,
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

        return redirect()->route('order.status', $order->guest_token);
    }

    public function orderStatus(string $guest_token): View
    {
        $order = Order::query()
            ->with('items.product')
            ->where('guest_token', $guest_token)
            ->firstOrFail();

        return view('guest.status', compact('order'));
    }

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
