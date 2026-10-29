<?php

namespace App\Http\Controllers;

use App\Models\Category;
use App\Models\Product;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class ProductController extends Controller
{
    /**
     * Tampilkan daftar produk katalog dan kategori.
     */
    public function index(): View
    {
        return view('admin.products.index', [
            'products' => Product::query()->with('category')->latest()->get(),
            'categories' => Category::query()->orderBy('name')->get(),
        ]);
    }

    /**
     * Simpan produk baru ke database.
     */
    public function store(Request $request): RedirectResponse
    {
        $validated = $this->validateProduct($request);
        Product::query()->create($validated);

        return back()->with('success', 'Produk berhasil ditambahkan.');
    }

    /**
     * Perbarui data produk tertentu.
     */
    public function update(Request $request, Product $product): RedirectResponse
    {
        $product->update($this->validateProduct($request));

        return back()->with('success', 'Produk berhasil diperbarui.');
    }

    /**
     * Hapus produk jika belum memiliki riwayat pesanan.
     */
    public function destroy(Product $product): RedirectResponse
    {
        if ($product->orderItems()->exists()) {
            return back()->with('error', 'Produk yang sudah memiliki riwayat pesanan tidak dapat dihapus. Nonaktifkan ketersediaan stoknya.');
        }

        $product->delete();

        return back()->with('success', 'Produk berhasil dihapus.');
    }

    /**
     * Alihkan status ketersediaan stok produk.
     */
    public function toggleAvailability(Request $request, Product $product): RedirectResponse|JsonResponse
    {
        $product->update([
            'is_available' => ! $product->is_available,
        ]);

        if ($request->expectsJson() || $request->wantsJson()) {
            return response()->json([
                'is_available' => (bool) $product->is_available,
                'message' => 'Ketersediaan produk berhasil diperbarui.',
            ]);
        }

        return back()->with('success', 'Ketersediaan produk diperbarui.');
    }

    /**
     * Validasi input produk.
     *
     * @return array{category_id: int, name: string, description: ?string, price: float, image: ?string, is_available: bool}
     */
    private function validateProduct(Request $request): array
    {
        $validated = $request->validate([
            'category_id' => ['required', 'exists:categories,id'],
            'name' => ['required', 'string', 'max:120'],
            'description' => ['nullable', 'string', 'max:1000'],
            'ingredients' => ['nullable', 'string', 'max:2000'],
            'price' => ['required', 'numeric', 'min:0'],
            'image' => ['nullable', 'string', 'max:255'],
            'is_available' => ['nullable', 'boolean'],
        ]);

        if ($request->hasFile('image')) {
            $validated['image'] = $request->file('image')->store('products', 'public');
        }

        $validated['is_available'] = $request->boolean('is_available');

        return $validated;
    }
}
