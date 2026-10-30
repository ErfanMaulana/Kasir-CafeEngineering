<?php

namespace Tests\Feature;

use App\Models\Category;
use App\Models\Order;
use App\Models\Product;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
use Tests\TestCase;

class CafeOrderingTest extends TestCase
{
    use RefreshDatabase;

    public function test_guest_can_view_catalog_with_categories_and_products(): void
    {
        $category = Category::query()->create([
            'name' => 'Kopi',
            'slug' => 'kopi',
        ]);

        $product = Product::query()->create([
            'category_id' => $category->id,
            'name' => 'Kopi Susu Gula Aren',
            'price' => 15000,
            'is_available' => true,
        ]);

        $response = $this->get(route('catalog'));

        $response->assertStatus(200);
        $response->assertSee('Kopi Susu Gula Aren');
        $response->assertSee('Kopi');
    }

    public function test_guest_can_add_item_to_cart_and_proceed_to_checkout(): void
    {
        $category = Category::query()->create([
            'name' => 'Minuman',
            'slug' => 'minuman',
        ]);

        $product = Product::query()->create([
            'category_id' => $category->id,
            'name' => 'Es Teh Manis',
            'price' => 5000,
            'is_available' => true,
        ]);

        // Add to cart
        $addResponse = $this->post(route('cart.add', $product->id));
        $addResponse->assertSessionHas('cart');

        // Add to cart via AJAX JSON and verify items array in response
        $ajaxResponse = $this->postJson(route('cart.add', $product->id));
        $ajaxResponse->assertStatus(200);
        $ajaxResponse->assertJson([
            'success' => true,
            'item_quantity' => 2,
            'cart_count' => 2,
            'cart_total' => 10000.0,
            'items' => [
                [
                    'id' => (string) $product->id,
                    'name' => 'Es Teh Manis',
                    'quantity' => 2,
                    'price' => 5000.0,
                    'subtotal' => 10000.0,
                ],
            ],
        ]);

        // View Cart
        $cartResponse = $this->get(route('cart'));
        $cartResponse->assertStatus(200);
        $cartResponse->assertSee('Es Teh Manis');

        // View Checkout
        $checkoutResponse = $this->get(route('checkout'));
        $checkoutResponse->assertStatus(200);
        $checkoutResponse->assertSee('Konfirmasi Pesanan');

        // Process Checkout
        $processResponse = $this->post(route('checkout.process'), [
            'customer_name' => 'Budi Santoso',
            'order_type' => 'dine_in',
            'table_number' => 'Meja 05',
            'payment_method' => 'qris',
        ]);

        $order = Order::query()->where('customer_name', 'Budi Santoso')->first();
        $this->assertNotNull($order);
        $this->assertSame('A-01', $order->queue_number);
        $this->assertSame(10000.0, (float) $order->total_amount);
        $this->assertSame('pending', $order->order_status);
        $this->assertSame('unpaid', $order->payment_status);

        $processResponse->assertRedirect(route('order.status', $order->guest_token));

        // Check Status page
        $statusResponse = $this->get(route('order.status', $order->guest_token));
        $statusResponse->assertStatus(200);
        $statusResponse->assertSee('A-01');
        $statusResponse->assertSee('Budi Santoso');
    }

    public function test_guest_can_checkout_with_cash_payment_and_see_cashier_payment_notice(): void
    {
        $category = Category::query()->create([
            'name' => 'Makanan',
            'slug' => 'makanan-cash',
        ]);

        $product = Product::query()->create([
            'category_id' => $category->id,
            'name' => 'Ayam Penyet Sambal Bawang',
            'price' => 20000,
            'is_available' => true,
        ]);

        $this->post(route('cart.add', $product->id));

        $response = $this->post(route('checkout.process'), [
            'customer_name' => 'Siti Nurhaliza',
            'order_type' => 'takeaway',
            'payment_method' => 'cash',
        ]);

        $order = Order::query()->where('customer_name', 'Siti Nurhaliza')->first();
        $this->assertNotNull($order);
        $this->assertSame('cash', $order->payment_method);
        $this->assertSame('unpaid', $order->payment_status);
        $this->assertSame('pending', $order->order_status);

        $response->assertRedirect(route('order.status', $order->guest_token));
        $response->assertSessionHas('success');

        $statusResponse = $this->get(route('order.status', $order->guest_token));
        $statusResponse->assertStatus(200);
        $statusResponse->assertSee('Harap lakukan pembayaran tunai');
        $statusResponse->assertSee('Rp 20.000');
    }

    public function test_admin_can_view_kds_and_update_order_status(): void
    {
        $order = Order::query()->create([
            'queue_number' => 'A-01',
            'customer_name' => 'Alisa',
            'order_type' => 'takeaway',
            'guest_token' => 'test-uuid-1234',
            'total_amount' => 15000,
            'payment_method' => 'qris',
            'payment_status' => 'pending',
            'order_status' => 'pending',
        ]);

        $response = $this->get(route('admin.kitchen'));
        $response->assertStatus(200);
        $response->assertSee('A-01');
        $response->assertSee('Alisa');

        // Update status to preparing
        $updateResponse = $this->patch(route('admin.orders.status', $order->id), [
            'order_status' => 'preparing',
        ]);

        $updateResponse->assertSessionHas('success');
        $this->assertSame('preparing', $order->fresh()->order_status);

        // KDS completed filter test
        Order::query()->create([
            'queue_number' => 'A-02',
            'customer_name' => 'Doni Selesai',
            'order_type' => 'dine_in',
            'guest_token' => 'test-uuid-kds-completed',
            'total_amount' => 15000,
            'payment_method' => 'cash',
            'payment_status' => 'paid',
            'order_status' => 'completed',
        ]);

        $kdsResponse = $this->get(route('admin.kitchen', ['filter' => 'today']));
        $kdsResponse->assertStatus(200);
        $kdsResponse->assertSee('Doni Selesai');
        $kdsResponse->assertSee('Hari Ini');
        $kdsResponse->assertSee('Minggu Ini');
    }

    public function test_guest_can_update_cart_quantity_via_ajax(): void
    {
        $category = Category::query()->create([
            'name' => 'Makanan',
            'slug' => 'makanan',
        ]);

        $product = Product::query()->create([
            'category_id' => $category->id,
            'name' => 'Nasi Goreng Spesial',
            'price' => 18000,
            'is_available' => true,
        ]);

        // Add to cart 1 item
        $this->post(route('cart.add', $product->id));

        // Update to 7 items via AJAX
        $response = $this->postJson(route('cart.update'), [
            'product_id' => (string) $product->id,
            'quantity' => 7,
            'notes' => 'Pedas sedang',
        ]);

        $response->assertStatus(200);
        $response->assertJson([
            'success' => true,
            'product_id' => (string) $product->id,
            'item_quantity' => 7,
            'item_subtotal' => 126000.0,
            'item_subtotal_formatted' => 'Rp 126.000',
            'cart_count' => 7,
            'cart_total' => 126000.0,
            'cart_total_formatted' => 'Rp 126.000',
        ]);

        // Verify session cart has quantity 7 and notes
        $cart = session('cart');
        $this->assertSame(7, $cart[(string) $product->id]['quantity']);
        $this->assertSame('Pedas sedang', $cart[(string) $product->id]['notes']);
    }

    public function test_admin_can_view_dashboard_without_errors(): void
    {
        Order::query()->create([
            'queue_number' => 'A-01',
            'customer_name' => 'Doni',
            'order_type' => 'dine_in',
            'table_number' => 'Meja 1',
            'guest_token' => 'test-uuid-admin-dash',
            'total_amount' => 35000,
            'payment_method' => 'qris',
            'payment_status' => 'paid',
            'order_status' => 'preparing',
        ]);

        $response = $this->get(route('admin.dashboard'));
        $response->assertStatus(200);
        $response->assertSee('Dashboard Penjualan');
        $response->assertSee('A-01');
    }

    public function test_admin_can_store_and_update_product_with_image_upload(): void
    {
        Storage::fake('public');

        $category = Category::query()->create([
            'name' => 'Snack',
            'slug' => 'snack',
        ]);

        $pngContent = base64_decode('iVBORw0KGgoAAAANSUhEUgAAAAEAAAABCAYAAAAfFcSJAAAADUlEQVR42mNk+M9QDwADhgGAWjR9awAAAABJRU5ErkJggg==');
        $fakeImage = UploadedFile::fake()->createWithContent('croissant.png', $pngContent);

        // 1. Store Product with image
        $response = $this->post(route('admin.products.store'), [
            'category_id' => $category->id,
            'name' => 'Butter Croissant',
            'price' => 20000,
            'description' => 'Pastry mentega renyah dan gurih',
            'is_available' => '1',
            'image' => $fakeImage,
        ]);

        $response->assertRedirect();
        $product = Product::query()->where('name', 'Butter Croissant')->first();
        $this->assertNotNull($product);
        $this->assertNotNull($product->image);
        Storage::disk('public')->assertExists($product->image);

        $oldImagePath = $product->image;

        // 2. Update without new image keeps old image
        $this->put(route('admin.products.update', $product->id), [
            'category_id' => $category->id,
            'name' => 'Butter Croissant Premium',
            'price' => 22000,
            'description' => 'Pastry mentega impor Prancis',
            'is_available' => '1',
        ]);

        $product->refresh();
        $this->assertSame('Butter Croissant Premium', $product->name);
        $this->assertSame($oldImagePath, $product->image);
        Storage::disk('public')->assertExists($oldImagePath);

        // 3. Update with new image replaces old image
        $newImage = UploadedFile::fake()->createWithContent('croissant-new.png', $pngContent);
        $this->put(route('admin.products.update', $product->id), [
            'category_id' => $category->id,
            'name' => 'Butter Croissant Premium Gold',
            'price' => 25000,
            'description' => 'Edisi spesial',
            'is_available' => '1',
            'image' => $newImage,
        ]);

        $product->refresh();
        $this->assertNotSame($oldImagePath, $product->image);
        Storage::disk('public')->assertMissing($oldImagePath);
        Storage::disk('public')->assertExists($product->image);
    }

    public function test_admin_can_update_order_payment_status_and_set_paid_at(): void
    {
        $order = Order::query()->create([
            'queue_number' => 'A-02',
            'customer_name' => 'Michie',
            'order_type' => 'takeaway',
            'guest_token' => 'test-uuid-payment-status',
            'total_amount' => 18000,
            'payment_method' => 'cash',
            'payment_status' => 'unpaid',
            'order_status' => 'completed',
        ]);

        $this->assertNull($order->paid_at);

        // 1. Mark as paid
        $response = $this->patch(route('admin.orders.update-payment', $order->id), [
            'payment_status' => 'paid',
        ]);

        $response->assertRedirect();
        $response->assertSessionHas('success');

        $order->refresh();
        $this->assertSame('paid', $order->payment_status);
        $this->assertNotNull($order->paid_at);

        // 2. Mark back as unpaid
        $responseUnpaid = $this->patch(route('admin.orders.update-payment', $order->id), [
            'payment_status' => 'unpaid',
        ]);

        $responseUnpaid->assertRedirect();
        $order->refresh();
        $this->assertSame('unpaid', $order->payment_status);
        $this->assertNull($order->paid_at);
    }

    public function test_admin_can_delete_order_and_its_order_items(): void
    {
        $category = Category::query()->create([
            'name' => 'Makanan',
            'slug' => 'makanan-delete-test',
        ]);

        $product = Product::query()->create([
            'category_id' => $category->id,
            'name' => 'Mie Goreng',
            'price' => 16000,
            'is_available' => true,
        ]);

        $order = Order::query()->create([
            'queue_number' => 'A-03',
            'customer_name' => 'Edo',
            'order_type' => 'takeaway',
            'guest_token' => 'test-uuid-delete-order',
            'total_amount' => 16000,
            'payment_method' => 'qris',
            'payment_status' => 'paid',
            'order_status' => 'completed',
        ]);

        $order->items()->create([
            'product_id' => $product->id,
            'quantity' => 1,
            'price' => 16000,
            'subtotal' => 16000,
        ]);

        $this->assertDatabaseHas('orders', ['id' => $order->id]);
        $this->assertDatabaseHas('order_items', ['order_id' => $order->id]);

        $response = $this->delete(route('admin.orders.destroy', $order->id));
        $response->assertRedirect();
        $response->assertSessionHas('success');

        // Soft delete: baris tetap ada di DB, namun deleted_at terisi
        $this->assertSoftDeleted('orders', ['id' => $order->id]);
        // Order items tetap tersimpan di DB (tidak ikut dihapus)
        $this->assertDatabaseHas('order_items', ['order_id' => $order->id]);
    }

    public function test_cart_notes_are_saved_and_persisted_to_order_items_and_visible_in_kds(): void
    {
        $category = Category::query()->create([
            'name' => 'Snack',
            'slug' => 'snack-toast',
        ]);

        $product = Product::query()->create([
            'category_id' => $category->id,
            'name' => 'Toast Cokelat Keju',
            'price' => 14000,
            'is_available' => true,
        ]);

        // 1. Add to cart
        $this->post(route('cart.add', $product->id));

        // 2. Update cart with custom note and next_step=checkout
        $updateResponse = $this->post(route('cart.update'), [
            'quantities' => [
                $product->id => 1,
            ],
            'notes' => [
                $product->id => 'kejunya banyakin',
            ],
            'next_step' => 'checkout',
        ]);

        $updateResponse->assertRedirect(route('checkout'));

        // Verify session cart has the custom note
        $cart = session('cart');
        $this->assertSame('kejunya banyakin', $cart[(string) $product->id]['notes']);

        // 3. Process checkout
        $checkoutResponse = $this->post(route('checkout.process'), [
            'customer_name' => 'Michie',
            'order_type' => 'dine_in',
            'table_number' => '02',
            'payment_method' => 'cash',
        ]);

        $order = Order::query()->where('customer_name', 'Michie')->latest('id')->first();
        $this->assertNotNull($order);
        $this->assertDatabaseHas('order_items', [
            'order_id' => $order->id,
            'product_id' => $product->id,
            'notes' => 'kejunya banyakin',
        ]);

        // 4. Verify KDS renders the notes badge
        $kdsResponse = $this->get(route('admin.kitchen'));
        $kdsResponse->assertStatus(200);
        $kdsResponse->assertSee('Toast Cokelat Keju');
        $kdsResponse->assertSee('Catatan: kejunya banyakin');
    }
}
