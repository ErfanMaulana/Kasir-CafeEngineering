<?php

namespace Database\Seeders;

use App\Models\Category;
use App\Models\Product;
use Illuminate\Database\Seeder;
use Illuminate\Support\Str;

class ProductSeeder extends Seeder
{
    /**
     * Jalankan seeder menu produk dan kategori kafe Engineering Cafe.
     */
    public function run(): void
    {
        $catalog = [
            'Makanan' => [
                [
                    'name' => 'Nasi Goreng Spesial',
                    'description' => 'Nasi goreng dengan telur ceplok, sosis, dan kerupuk',
                    'price' => 18000,
                    'image' => 'https://images.unsplash.com/photo-1603133872878-684f208fb84b?auto=format&fit=crop&w=600&q=80',
                    'is_available' => true,
                ],
                [
                    'name' => 'Mie Goreng Jawa',
                    'description' => 'Mie goreng bumbu kecap manis gurih dengan suwiran ayam',
                    'price' => 16000,
                    'image' => 'https://images.unsplash.com/photo-1585032226651-759b368d7246?auto=format&fit=crop&w=600&q=80',
                    'is_available' => true,
                ],
                [
                    'name' => 'Chicken Katsu Don',
                    'description' => 'Nasi dengan topping chicken katsu renyah dan telur setengah matang',
                    'price' => 22000,
                    'image' => 'https://images.unsplash.com/photo-1569718212165-3a8278d5f624?auto=format&fit=crop&w=600&q=80',
                    'is_available' => true,
                ],
                [
                    'name' => 'Spageti Carbonara',
                    'description' => 'Pasta kremi dengan saus keju dan potongan daging asap',
                    'price' => 25000,
                    'image' => 'https://images.unsplash.com/photo-1612874742237-6526221588e3?auto=format&fit=crop&w=600&q=80',
                    'is_available' => true,
                ],
            ],
            'Minuman' => [
                [
                    'name' => 'Es Kopi Susu Aren',
                    'description' => 'Kopi espresso pilihan dicampur susu segar dan gula aren asli',
                    'price' => 15000,
                    'image' => 'https://images.unsplash.com/photo-1517701550927-30cf4ba1dba5?auto=format&fit=crop&w=600&q=80',
                    'is_available' => true,
                ],
                [
                    'name' => 'Americano Ice',
                    'description' => 'Espresso ganda dengan es batu segar',
                    'price' => 12000,
                    'image' => 'https://images.unsplash.com/photo-1551030173-122aabc4489c?auto=format&fit=crop&w=600&q=80',
                    'is_available' => true,
                ],
                [
                    'name' => 'Matcha Latte Ice',
                    'description' => 'Bubuk matcha jepang asli dicampur susu segar',
                    'price' => 18000,
                    'image' => 'https://images.unsplash.com/photo-1536256263959-770b48d82b0a?auto=format&fit=crop&w=600&q=80',
                    'is_available' => true,
                ],
                [
                    'name' => 'Es Teh Manis',
                    'description' => 'Teh melati manis dingin menyegarkan',
                    'price' => 5000,
                    'image' => 'https://images.unsplash.com/photo-1556679343-c7306c1976bc?auto=format&fit=crop&w=600&q=80',
                    'is_available' => true,
                ],
                [
                    'name' => 'Chocolate Ice',
                    'description' => 'Cokelat pekat dingin bermutu tinggi dengan topping keju',
                    'price' => 16000,
                    'image' => 'https://images.unsplash.com/photo-1541658016709-82535e94bc69?auto=format&fit=crop&w=600&q=80',
                    'is_available' => true,
                ],
            ],
            'Snack' => [
                [
                    'name' => 'Kentang Goreng',
                    'description' => 'French fries renyah dengan bumbu gurih dan saus cocolan',
                    'price' => 12000,
                    'image' => 'https://images.unsplash.com/photo-1576107232684-1279f3908594?auto=format&fit=crop&w=600&q=80',
                    'is_available' => true,
                ],
                [
                    'name' => 'Pisang Cokelat Keju',
                    'description' => 'Pisang goreng krispi dengan limpahan ceres dan keju parut',
                    'price' => 10000,
                    'image' => 'https://images.unsplash.com/photo-1528735602780-2552fd46c7af?auto=format&fit=crop&w=600&q=80',
                    'is_available' => true,
                ],
                [
                    'name' => 'Cireng Bumbu Rujak',
                    'description' => 'Cireng gurih krispi khas Sunda dengan cocolan bumbu rujak pedas manis',
                    'price' => 10000,
                    'image' => 'https://images.unsplash.com/photo-1563379091339-03b21ab4a4f8?auto=format&fit=crop&w=600&q=80',
                    'is_available' => true,
                ],
                [
                    'name' => 'Toast Cokelat Keju',
                    'description' => 'Roti bakar tebal isi mentega, cokelat, dan keju melimpah',
                    'price' => 14000,
                    'image' => 'https://images.unsplash.com/photo-1484723091739-30a097e8f929?auto=format&fit=crop&w=600&q=80',
                    'is_available' => true,
                ],
            ],
        ];

        foreach ($catalog as $categoryName => $products) {
            $category = Category::query()->updateOrCreate(
                ['slug' => Str::slug($categoryName)],
                ['name' => $categoryName]
            );

            foreach ($products as $productData) {
                Product::query()->updateOrCreate(
                    [
                        'category_id' => $category->id,
                        'name' => $productData['name'],
                    ],
                    [
                        'description' => $productData['description'],
                        'price' => $productData['price'],
                        'image' => $productData['image'],
                        'is_available' => $productData['is_available'] ?? true,
                    ]
                );
            }
        }
    }
}
