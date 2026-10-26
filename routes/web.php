<?php

use App\Http\Controllers\AdminController;
use App\Http\Controllers\GuestOrderController;
use Illuminate\Support\Facades\Route;

Route::controller(GuestOrderController::class)->group(function (): void {
    Route::get('/', 'index')->name('catalog');
    Route::get('/cart', 'cart')->name('cart');
    Route::post('/cart/add/{id}', 'addToCart')->name('cart.add');
    Route::post('/cart/update', 'updateCart')->name('cart.update');
    Route::post('/cart/remove', 'removeFromCart')->name('cart.remove');
    Route::get('/checkout', 'checkout')->name('checkout');
    Route::post('/checkout', 'processCheckout')->name('checkout.process');
    Route::get('/order/status/{guest_token}', 'orderStatus')->name('order.status');
});

Route::prefix('admin')->name('admin.')->controller(AdminController::class)->group(function (): void {
    Route::get('/', 'dashboard')->name('dashboard');
    Route::get('/products', 'products')->name('products');
    Route::post('/products', 'storeProduct')->name('products.store');
    Route::put('/products/{id}', 'updateProduct')->name('products.update');
    Route::delete('/products/{id}', 'destroyProduct')->name('products.destroy');
    Route::patch('/products/{id}/availability', 'toggleProductAvailability')->name('products.availability');
    Route::get('/kitchen', 'kitchen')->name('kitchen');
    Route::patch('/orders/{order_id}/status', 'updateOrderStatus')->name('orders.status');
});
