<?php

namespace Database\Seeders;

use App\Models\User;
use App\Models\Seller;
use App\Models\Product;
use App\Models\Category;
use App\Models\SubCategory;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class CartTestSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Create test user
        $user = User::updateOrCreate(
            ['email' => 'test@example.com'],
            [
                'name' => 'Test User',
                'phone' => '01234567890',
                'password' => Hash::make('password123'),
            ]
        );

        // Create test seller
        $seller = Seller::firstOrCreate(
            ['email' => 'seller@example.com'],
            [
                'name' => 'Test Seller',
                'phone' => '01234567891',
                'password' => Hash::make('password123'),
            ]
        );

        // Create test category
        $category = Category::firstOrCreate(
            ['slug' => 'electronics'],
            ['name' => 'Electronics']
        );

        // Create test subcategory
        $subCategory = SubCategory::firstOrCreate(
            ['slug' => 'smartphones'],
            [
                'name' => 'Smartphones',
                'category_id' => $category->id
            ]
        );

        // Create test products
        $products = [
            [
                'name' => 'iPhone 15 Pro',
                'description' => 'Latest iPhone with A17 Pro chip',
                'price' => 1200.00,
                'discounted_price' => 1100.00,
                'quantity' => 50,
            ],
            [
                'name' => 'Samsung Galaxy S24',
                'description' => 'Flagship Samsung smartphone',
                'price' => 1000.00,
                'discounted_price' => 900.00,
                'quantity' => 30,
            ],
            [
                'name' => 'Google Pixel 8',
                'description' => 'Google flagship with AI features',
                'price' => 800.00,
                'discounted_price' => 750.00,
                'quantity' => 20,
            ],
            [
                'name' => 'OnePlus 12',
                'description' => 'Fast charging flagship phone',
                'price' => 700.00,
                'discounted_price' => 650.00,
                'quantity' => 15,
            ],
            [
                'name' => 'Xiaomi 14 Pro',
                'description' => 'High-end Xiaomi smartphone',
                'price' => 600.00,
                'discounted_price' => 550.00,
                'quantity' => 0, // Out of stock product
            ],
        ];

        foreach ($products as $productData) {
            Product::firstOrCreate(
                ['name' => $productData['name']],
                array_merge($productData, [
                    'seller_id' => $seller->id,
                    'sub_category_id' => $subCategory->id,
                ])
            );
        }

        $this->command->info('✅ Cart test data seeded successfully!');
        $this->command->info('📧 Test User Email: test@example.com');
        $this->command->info('🔑 Test User Password: password123');
    }
}
