<?php

namespace App\Http\Controllers;

use App\Models\Category;
use App\Models\Order;
use App\Models\OrderItem;
use App\Models\Product;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use Illuminate\View\View;

class AdminController extends Controller
{
    /**
     * Tampilkan ringkasan analitik dan metrik penjualan hari ini (data riil).
     */
    public function dashboard(): View
    {
        $today = today();
        $todayOrders = Order::query()->whereDate('created_at', $today);

        $revenue = (float) (clone $todayOrders)
            ->where('order_status', '!=', 'cancelled')
            ->sum('total_amount');

        $orderCount = (int) (clone $todayOrders)->count();

        $activeCount = (int) (clone $todayOrders)
            ->whereIn('order_status', ['pending', 'preparing', 'ready'])
            ->count();

        $completedCount = (int) (clone $todayOrders)
            ->where('order_status', 'completed')
            ->count();

        $cancelledCount = (int) (clone $todayOrders)
            ->where('order_status', 'cancelled')
            ->count();

        $avgTicket = $orderCount > 0 ? (float) ($revenue / $orderCount) : 0.0;

        $dineInCount = (int) (clone $todayOrders)->where('order_type', 'dine_in')->count();
        $takeawayCount = (int) (clone $todayOrders)->where('order_type', 'takeaway')->count();

        // Riwayat pesanan terbaru dengan eager loading relasi items.product
        $recentOrders = Order::query()
            ->with(['items.product'])
            ->latest()
            ->limit(10)
            ->get();

        // Distribusi penjualan per jam (08:00 - 20:00)
        $hourlyData = [];
        for ($h = 8; $h <= 20; $h++) {
            $hourlyData[$h] = [
                'hour' => sprintf('%02d:00', $h),
                'count' => 0,
                'revenue' => 0.0,
            ];
        }

        $todayOrdersList = (clone $todayOrders)
            ->where('order_status', '!=', 'cancelled')
            ->get(['created_at', 'total_amount']);

        foreach ($todayOrdersList as $order) {
            $hr = (int) $order->created_at->format('H');
            if (isset($hourlyData[$hr])) {
                $hourlyData[$hr]['count']++;
                $hourlyData[$hr]['revenue'] += (float) $order->total_amount;
            }
        }

        // Produk terlaris hari ini
        $topProducts = OrderItem::query()
            ->join('orders', 'order_items.order_id', '=', 'orders.id')
            ->join('products', 'order_items.product_id', '=', 'products.id')
            ->whereDate('orders.created_at', $today)
            ->where('orders.order_status', '!=', 'cancelled')
            ->selectRaw('products.id, products.name, products.price, SUM(order_items.quantity) as total_sold, SUM(order_items.subtotal) as gross_rev')
            ->groupBy('products.id', 'products.name', 'products.price')
            ->orderByDesc('total_sold')
            ->limit(5)
            ->get();

        return view('admin.dashboard', [
            'revenue' => $revenue,
            'orderCount' => $orderCount,
            'activeCount' => $activeCount,
            'completedCount' => $completedCount,
            'cancelledCount' => $cancelledCount,
            'avgTicket' => $avgTicket,
            'dineInCount' => $dineInCount,
            'takeawayCount' => $takeawayCount,
            'hourlyData' => $hourlyData,
            'topProducts' => $topProducts,
            'recentOrders' => $recentOrders,
        ]);
    }

    /**
     * Kitchen Display System (KDS) live feed antrean dapur.
     */
    public function kds(Request $request): View
    {
        $filter = (string) $request->input('filter', 'today');

        // 1. Pesanan aktif: pending, preparing, ready (selalu realtime)
        $activeOrders = Order::query()
            ->with(['items.product'])
            ->whereIn('order_status', ['pending', 'preparing', 'ready'])
            ->orderByRaw("CASE order_status WHEN 'pending' THEN 1 WHEN 'preparing' THEN 2 ELSE 3 END")
            ->oldest()
            ->get();

        // 2. Pesanan selesai: completed dengan filter waktu & batas limit
        $completedQuery = Order::query()
            ->with(['items.product'])
            ->where('order_status', 'completed');

        match ($filter) {
            'this_week' => $completedQuery->whereBetween('updated_at', [now()->startOfWeek(), now()->endOfWeek()]),
            'this_month' => $completedQuery->whereMonth('updated_at', now()->month)->whereYear('updated_at', now()->year),
            'this_year' => $completedQuery->whereYear('updated_at', now()->year),
            default => $completedQuery->whereDate('updated_at', now()->today()),
        };

        $completedCount = (clone $completedQuery)->count();
        $completedOrders = $completedQuery->latest('updated_at')->limit(20)->get();

        $orders = $activeOrders->concat($completedOrders);

        return view('admin.kitchen', [
            'orders' => $orders,
            'completedFilter' => $filter,
            'completedCount' => $completedCount,
        ]);
    }

    /**
     * Alias kompatibilitas untuk antrean dapur (Kitchen Display System).
     */
    public function kitchen(Request $request): View
    {
        return $this->kds($request);
    }

    /**
     * Perbarui status pesanan (pending, preparing, ready, completed, cancelled).
     */
    public function updateOrderStatus(Request $request, Order|int $order): RedirectResponse|JsonResponse
    {
        $orderInstance = $order instanceof Order ? $order : Order::query()->findOrFail($order);

        $validated = $request->validate([
            'order_status' => ['required', 'string', 'in:pending,preparing,ready,completed,cancelled'],
        ]);

        $orderInstance->update([
            'order_status' => $validated['order_status'],
        ]);

        if ($request->expectsJson() || $request->wantsJson()) {
            return response()->json([
                'success' => true,
                'message' => "Status pesanan {$orderInstance->queue_number} berhasil diperbarui menjadi {$orderInstance->order_status}.",
                'order' => $orderInstance,
            ]);
        }

        return back()->with('success', "Pesanan {$orderInstance->queue_number} diperbarui.");
    }

    /**
     * Perbarui status pembayaran pesanan (pending, paid, failed).
     */
    public function updatePaymentStatus(Request $request, Order|int $order): RedirectResponse|JsonResponse
    {
        $orderInstance = $order instanceof Order ? $order : Order::query()->findOrFail($order);

        $validated = $request->validate([
            'payment_status' => ['required', 'string', 'in:pending,unpaid,paid,failed'],
        ]);

        $orderInstance->update([
            'payment_status' => $validated['payment_status'],
        ]);

        if ($request->expectsJson() || $request->wantsJson()) {
            return response()->json([
                'success' => true,
                'message' => "Status pembayaran pesanan {$orderInstance->queue_number} berhasil diperbarui menjadi {$orderInstance->payment_status}.",
                'order' => $orderInstance,
            ]);
        }

        return back()->with('success', "Status pembayaran pesanan {$orderInstance->queue_number} diperbarui.");
    }

    /**
     * Tampilkan daftar produk katalog menu dan kategori.
     */
    public function products(): View
    {
        return view('admin.products.index', [
            'products' => Product::query()->with('category')->latest()->get(),
            'categories' => Category::query()->withCount('products')->orderBy('name')->get(),
        ]);
    }

    /**
     * Simpan produk menu baru ke dalam database.
     */
    public function storeProduct(Request $request): RedirectResponse
    {
        $validated = $this->validateProduct($request);

        if ($request->hasFile('image')) {
            $validated['image'] = $request->file('image')->store('products', 'public');
        } else {
            unset($validated['image']);
        }

        Product::query()->create($validated);

        return back()->with('success', 'Produk berhasil ditambahkan.');
    }

    /**
     * Perbarui data produk menu.
     */
    public function updateProduct(Request $request, Product|int $product): RedirectResponse
    {
        $productInstance = $product instanceof Product ? $product : Product::query()->findOrFail($product);
        $validated = $this->validateProduct($request);

        if ($request->hasFile('image')) {
            if ($productInstance->image && ! str_starts_with($productInstance->image, 'http://') && ! str_starts_with($productInstance->image, 'https://')) {
                Storage::disk('public')->delete($productInstance->image);
            }
            $validated['image'] = $request->file('image')->store('products', 'public');
        } else {
            unset($validated['image']);
        }

        $productInstance->update($validated);

        return back()->with('success', 'Produk berhasil diperbarui.');
    }

    /**
     * Hapus produk menu jika belum memiliki riwayat pesanan.
     */
    public function destroyProduct(Product|int $product): RedirectResponse
    {
        $productInstance = $product instanceof Product ? $product : Product::query()->findOrFail($product);

        if ($productInstance->orderItems()->exists()) {
            return back()->with('error', 'Produk yang sudah memiliki riwayat pesanan tidak dapat dihapus. Nonaktifkan ketersediaan stoknya.');
        }

        if ($productInstance->image && ! str_starts_with($productInstance->image, 'http://') && ! str_starts_with($productInstance->image, 'https://')) {
            Storage::disk('public')->delete($productInstance->image);
        }

        $productInstance->delete();

        return back()->with('success', 'Produk berhasil dihapus.');
    }

    /**
     * Alihkan ketersediaan stok produk (In Stock / Out of Stock).
     */
    public function toggleProductAvailability(Request $request, Product|int $product): RedirectResponse|JsonResponse
    {
        $productInstance = $product instanceof Product ? $product : Product::query()->findOrFail($product);
        $productInstance->update([
            'is_available' => ! $productInstance->is_available,
        ]);

        if ($request->expectsJson() || $request->wantsJson()) {
            return response()->json([
                'is_available' => (bool) $productInstance->is_available,
                'message' => 'Ketersediaan produk berhasil diperbarui.',
            ]);
        }

        return back()->with('success', 'Ketersediaan produk diperbarui.');
    }

    /**
     * Simpan kategori baru.
     */
    public function storeCategory(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'name' => ['required', 'string', 'max:50', 'unique:categories,name'],
        ]);

        Category::query()->create([
            'name' => $validated['name'],
            'slug' => Str::slug($validated['name']),
        ]);

        return back()->with('success', "Kategori '{$validated['name']}' berhasil ditambahkan.");
    }

    /**
     * Hapus kategori jika belum memiliki produk.
     */
    public function destroyCategory(int $id): RedirectResponse
    {
        $category = Category::query()->findOrFail($id);

        if ($category->products()->exists()) {
            return back()->with('error', 'Kategori tidak dapat dihapus karena masih memiliki produk.');
        }

        $category->delete();

        return back()->with('success', 'Kategori berhasil dihapus.');
    }

    /**
     * Validasi input formulir produk menu.
     *
     * @return array<string, mixed>
     */
    private function validateProduct(Request $request): array
    {
        $validated = $request->validate([
            'category_id' => ['required', 'exists:categories,id'],
            'name' => ['required', 'string', 'max:120'],
            'description' => ['nullable', 'string', 'max:1000'],
            'ingredients' => ['nullable', 'string', 'max:2000'],
            'price' => ['required', 'numeric', 'min:0'],
            'image' => ['nullable', 'image', 'mimes:jpeg,png,jpg,webp', 'max:5120'],
            'is_available' => ['nullable', 'boolean'],
        ]);

        $validated['is_available'] = $request->boolean('is_available');

        return $validated;
    }
}
