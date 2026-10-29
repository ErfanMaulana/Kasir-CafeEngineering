PROJECT GUIDELINES & SPECIFICATION
Nama Proyek: Pengembangan Sistem Online Ordering untuk Meningkatkan Efisiensi Pelayanan Kafe di Lingkungan Universitas Siliwangi  
Nama Aplikasi: Engineering Cafe  
Versi: 1.0 (MVP)  
Target Platform: Web Responsive (Pure Laravel + Tailwind CSS)  
Tipe Transaksi: Guest Checkout (Tanpa Login untuk Pembeli)
---
1. DOKUMENTASI RINGKAS PROYEK
1.1 Problem Statement
Antrean panjang di kasir pada jam-jam sibuk (peak hours) di jeda pergantian jam kuliah.
Potensi kesalahan pencatatan pesanan manual (human error).
Waktu tunggu pelanggan yang tidak pasti.
Kesulitan pengelolaan stok menu dan laporan harian secara real-time.
1.2 Solution & Value Proposition
Aplikasi web berbasis Guest Checkout yang memungkinkan mahasiswa dan dosen memesan makanan/minuman tanpa registrasi/login. Sistem langsung menerbitkan nomor antrean digital, mengirim pesanan ke layar Kitchen Display System (KDS) di dapur, dan memfasilitasi pemanggilan nama pelanggan secara efisien.
---
2. PANDUAN DESAIN UI/UX & PALET WARNA
Aplikasi dibangun dengan pendekatan Mobile-First untuk pembeli, dan Tablet/Desktop-Friendly untuk panel Admin & Dapur.
Peran Elemen	Kode Hex	Implementasi Tailwind CSS	Deskripsi & Pengunaan
Primary Navy	`#041562`	`bg-\[#041562]`, `text-\[#041562]`	Header, Navbar, Branding Utama, & Sidebar Admin
Secondary Blue	`#11468F`	`bg-\[#11468F]`, `text-\[#11468F]`	Card Produk, Badge Status, Elemen Sekunder
Vibrant Red	`#DA1212`	`bg-\[#DA1212]`, `hover:bg-red-700`	Tombol Utama (CTA/Checkout, Panggil Nama, Tambah Cart)
Off-White	`#EEEEEE`	`bg-\[#EEEEEE]`	Background utama aplikasi
---
3. ARSITEKTUR KODE & ATURAN PENAMAMAAN
Framework: Pure Laravel (Latest Version)
Styling Engine: Tailwind CSS
Database: MySQL / MariaDB (via Laragon)
Aturan Penamaan (Naming Conventions):
Database Tables: Snake_case, plural (cth: `categories`, `products`, `orders`, `order\_items`).
Database Columns: Snake_case (cth: `customer\_name`, `queue\_number`, `order\_status`).
Models: PascalCase, singular (cth: `Category`, `Product`, `Order`, `OrderItem`).
Controllers: PascalCase, Suffix 'Controller' (cth: `GuestOrderController`, `AdminOrderController`).
Blade Views: Kebab-case, tersimpan dalam sub-folder role (cth: `resources/views/guest/catalog.blade.php`).
---
4. SKEMA DATABASE (MIGRATION REFERENCE)
4.1 Tabel Categories & Products
```php
Schema::create('categories', function (Blueprint $table) {
    $table->id();
    $table->string('name');
    $table->string('slug')->unique();
    $table->timestamps();
});

Schema::create('products', function (Blueprint $table) {
    $table->id();
    $table->foreignId('category\_id')->constrained()->onDelete('cascade');
    $table->string('name');
    $table->text('description')->nullable();
    $table->integer('price');
    $table->string('image')->nullable();
    $table->boolean('is\_available')->default(true);
    $table->timestamps();
});
```
4.2 Tabel Orders & Order Items
```php
Schema::create('orders', function (Blueprint $table) {
    $table->id();
    $table->string('queue\_number'); // Contoh: A-01, A-02
    $table->string('customer\_name'); // Nama pemanggil dari form checkout
    $table->enum('order\_type', \['dine\_in', 'takeaway'])->default('takeaway');
    $table->string('table\_number')->nullable();
    $table->string('guest\_token'); // UUID Session
    $table->integer('total\_amount');
    $table->enum('payment\_status', \['pending', 'paid', 'failed'])->default('pending');
    $table->enum('order\_status', \['pending', 'preparing', 'ready', 'completed', 'cancelled'])->default('pending');
    $table->timestamps();
});

Schema::create('order\_items', function (Blueprint $table) {
    $table->id();
    $table->foreignId('order\_id')->constrained()->onDelete('cascade');
    $table->foreignId('product\_id')->constrained()->onDelete('cascade');
    $table->integer('quantity');
    $table->integer('price');
    $table->integer('subtotal');
    $table->string('notes')->nullable();
    $table->timestamps();
});
```
---
5. ALUR TRANSAKSI GUEST CHECKOUT
Katalog & Cart: Pembeli memilih produk tanpa login. Cart disimpan sementara di `Session::get('cart')`.
Form Checkout: Wajib mengisi `customer\_name` (Nama Pemanggil) dan `order\_type`.
Generasi Transaksi:
Membuat `guest\_token` berbasis `Str::uuid()`.
Membuat `queue\_number` harian (Format: `A-01`, `A-02` dst).
Memindahkan data dari Session Cart ke tabel `orders` dan `order\_items`.
Nota Digital & Status Real-time: Pengguna di-redirect ke halaman `/order/status/{guest\_token}`.
---
6. STRUKTUR ROUTES (`routes/web.php`)
```php
// Guest Order Routes (Tanpa Login)
Route::get('/', \[GuestOrderController::class, 'index'])->name('guest.catalog');
Route::post('/cart/add/{product}', \[GuestOrderController::class, 'addToCart'])->name('cart.add');
Route::get('/checkout', \[GuestOrderController::class, 'checkout'])->name('guest.checkout');
Route::post('/checkout', \[GuestOrderController::class, 'processCheckout'])->name('guest.process\_checkout');
Route::get('/order/status/{guest\_token}', \[GuestOrderController::class, 'orderStatus'])->name('guest.order\_status');

// Admin \& Staff Panel Routes (With Auth)
Route::middleware(\['auth'])->prefix('admin')->group(function () {
    Route::get('/dashboard', \[AdminController::class, 'dashboard'])->name('admin.dashboard');
    Route::get('/kds', \[AdminController::class, 'kds'])->name('admin.kds'); // Kitchen Display System
    Route::patch('/order/{order}/status', \[AdminController::class, 'updateOrderStatus'])->name('admin.order.update\_status');
    Route::resource('products', ProductController::class);
});
```
---
7. PROMPT MASTER UNTUK PENGEMBANGAN LANJUTAN
Gunakan prompt ini setiap kali meminta bantuan AI untuk menuliskan kode atau komponen baru agar hasilnya tetap konsisten:
```text
Atur pengerjaan kode berdasarkan panduan di PROJECT\_GUIDELINES.md.
Aplikasi: Online Ordering Kafe Fakultas Teknik Unsil (Pure Laravel + Tailwind CSS).
Warna: Primary Navy (#041562), Secondary Blue (#11468F), Vibrant Red (#DA1212), Background (#EEEEEE).
Pendekatan: Mobile-First untuk Guest Checkout (tanpa login, menggunakan customer\_name dan queue\_number), serta Dashboard/KDS terpadu untuk Admin.
```