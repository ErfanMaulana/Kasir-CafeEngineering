<?php

use App\Http\Controllers\Admin\OrderTransactionController;
use App\Http\Controllers\AdminController;
use App\Http\Controllers\GuestOrderController;
use Illuminate\Support\Facades\Route;

// Guest Order Routes (Tanpa Login)
Route::controller(GuestOrderController::class)->group(function (): void {
    Route::get('/', 'index')->name('catalog');
    Route::get('/cart', 'cart')->name('cart');
    Route::post('/cart/add/{id}', 'addToCart')->name('cart.add');
    Route::post('/cart/update', 'updateCart')->name('cart.update');
    Route::post('/cart/remove', 'removeFromCart')->name('cart.remove');
    Route::get('/checkout', 'checkout')->name('checkout');
    Route::post('/checkout', 'processCheckout')->name('checkout.process');
    Route::get('/order/status/{guest_token}', 'orderStatus')->name('order.status');

    // Alias routes sesuai PROJECT_GUIDELINES.md
    Route::get('/catalog', 'index')->name('guest.catalog');
    Route::get('/order/checkout', 'checkout')->name('guest.checkout');
    Route::post('/order/checkout', 'processCheckout')->name('guest.process_checkout');
    Route::get('/orders/status/{guest_token}', 'orderStatus')->name('guest.order_status');
});

// Admin & Staff Panel Routes
Route::prefix('admin')->name('admin.')->controller(AdminController::class)->group(function (): void {
    Route::get('/', 'dashboard')->name('index');
    Route::get('/dashboard', 'dashboard')->name('dashboard');

    // Kitchen Display System (KDS)
    Route::get('/kds', 'kds')->name('kds');
    Route::get('/kitchen', 'kitchen')->name('kitchen');

    // Update Status Pesanan & Pembayaran
    Route::patch('/order/{order}/status', 'updateOrderStatus')->name('order.update_status');
    Route::patch('/orders/{order_id}/status', 'updateOrderStatus')->name('orders.status');
    Route::patch('/order/{order}/payment', 'updatePaymentStatus')->name('order.update_payment');

    // Manajemen Kategori
    Route::post('/categories', 'storeCategory')->name('categories.store');
    Route::delete('/categories/{id}', 'destroyCategory')->name('categories.destroy');

    // Manajemen Produk
    Route::get('/products', 'products')->name('products');
    Route::post('/products', 'storeProduct')->name('products.store');
    Route::put('/products/{id}', 'updateProduct')->name('products.update');
    Route::delete('/products/{id}', 'destroyProduct')->name('products.destroy');
    Route::patch('/products/{id}/availability', 'toggleProductAvailability')->name('products.availability');
});

// Orders & Transactions Panel
Route::get('/admin/orders', [OrderTransactionController::class, 'index'])->name('admin.orders.index');
