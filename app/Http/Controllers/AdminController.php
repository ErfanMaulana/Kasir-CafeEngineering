<?php

namespace App\Http\Controllers;

use App\Models\Category;
use App\Models\Order;
use App\Models\Product;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class AdminController extends Controller
{
    public function dashboard(): View
    {
        $todayOrders = Order::query()->whereDate('created_at', today());

        return view('admin.dashboard', [
            'revenue' => (clone $todayOrders)->where('order_status', '!=', 'cancelled')->sum('total_amount'),
            'orderCount' => (clone $todayOrders)->count(),
            'activeCount' => (clone $todayOrders)->whereIn('order_status', ['pending', 'preparing', 'ready'])->count(),
            'recentOrders' => Order::query()->latest()->limit(8)->get(),
        ]);
    }

    public function products(): View
    {
        return view('admin.products.index', [
            'products' => Product::query()->with('category')->latest()->get(),
            'categories' => Category::query()->orderBy('name')->get(),
        ]);
    }

    public function storeProduct(Request $request): RedirectResponse
    {
        $validated = $this->validateProduct($request);
        Product::query()->create($validated);

        return back()->with('success', 'Produk berhasil ditambahkan.');
    }

    public function updateProduct(Request $request, int $id): RedirectResponse
    {
        $product = Product::query()->findOrFail($id);
        $product->update($this->validateProduct($request));

        return back()->with('success', 'Produk berhasil diperbarui.');
    }

    public function destroyProduct(int $id): RedirectResponse
    {
        $product = Product::query()->findOrFail($id);
        if ($product->orderItems()->exists()) {
            return back()->with('error', 'Produk yang sudah memiliki riwayat pesanan tidak dapat dihapus. Nonaktifkan stoknya.');
        }

        $product->delete();

        return back()->with('success', 'Produk berhasil dihapus.');
    }

    public function toggleProductAvailability(int $id): RedirectResponse
    {
        $product = Product::query()->findOrFail($id);
        $product->update(['is_available' => ! $product->is_available]);

        if (request()->expectsJson()) {
            return response()->json([
                'is_available' => $product->is_available,
                'message' => 'Ketersediaan produk diperbarui.',
            ]);
        }

        return back()->with('success', 'Ketersediaan produk diperbarui.');
    }

    public function kitchen(): View
    {
        return view('admin.kitchen', [
            'orders' => Order::query()
                ->with('items.product')
                ->whereIn('order_status', ['pending', 'preparing', 'ready', 'completed'])
                ->orderByRaw("case order_status when 'pending' then 1 when 'preparing' then 2 else 3 end")
                ->oldest()
                ->get(),
        ]);
    }

    public function updateOrderStatus(Request $request, int $order_id): RedirectResponse
    {
        $validated = $request->validate([
            'order_status' => ['required', 'in:pending,preparing,ready,completed,cancelled'],
        ]);

        $order = Order::query()->findOrFail($order_id);
        $order->update(['order_status' => $validated['order_status']]);

        return back()->with('success', "Pesanan {$order->queue_number} diperbarui.");
    }

    private function validateProduct(Request $request): array
    {
        return $request->validate([
            'category_id' => ['required', 'exists:categories,id'],
            'name' => ['required', 'string', 'max:120'],
            'description' => ['nullable', 'string', 'max:1000'],
            'price' => ['required', 'numeric', 'min:0'],
            'image' => ['nullable', 'string', 'max:255'],
            'is_available' => ['nullable', 'boolean'],
        ]) + ['is_available' => $request->boolean('is_available')];
    }
}
