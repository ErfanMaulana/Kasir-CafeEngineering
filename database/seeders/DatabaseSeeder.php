<?php

namespace Database\Seeders;

use App\Models\Category;
use App\Models\Product;
use Illuminate\Database\Seeder;
use Illuminate\Support\Str;

class DatabaseSeeder extends Seeder
{
    public function run(): void
    {
        $catalog = [
            'Makanan' => [
                ['name' => 'Nasi Goreng Spesial', 'description' => 'Nasi goreng dengan telur dan ayam.', 'price' => 18000],
                ['name' => 'Mie Goreng Jawa', 'description' => 'Mie goreng dengan sayuran dan telur.', 'price' => 16000],
            ],
            'Minuman' => [
                ['name' => 'Es Kopi Susu', 'description' => 'Kopi susu gula aren dingin.', 'price' => 15000],
                ['name' => 'Es Teh Manis', 'description' => 'Teh manis dingin yang menyegarkan.', 'price' => 7000],
            ],
            'Snack' => [
                ['name' => 'Kentang Goreng', 'description' => 'Kentang goreng renyah dengan saus.', 'price' => 12000],
                ['name' => 'Pisang Cokelat', 'description' => 'Pisang goreng dengan topping cokelat.', 'price' => 10000],
            ],
        ];

        foreach ($catalog as $categoryName => $products) {
            $category = Category::query()->updateOrCreate(
                ['slug' => Str::slug($categoryName)],
                ['name' => $categoryName],
            );

            foreach ($products as $product) {
                Product::query()->updateOrCreate(
                    ['category_id' => $category->id, 'name' => $product['name']],
                    $product + ['is_available' => true],
                );
            }
        }
    }
}
