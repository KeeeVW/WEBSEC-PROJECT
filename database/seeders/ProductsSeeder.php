<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Product;
use App\Models\ProductInventory;

class ProductsSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Sample products
        $products = [
            [
                'code' => 'PREM-MEM',
                'name' => 'Premium Membership',
                'model' => 'Subscription',
                'description' => 'Access to exclusive premium content and features',
                'price' => 29.99,
                'photo' => 'premium.jpg',
                'quantity' => 999,
            ],
            [
                'code' => 'STD-MEM',
                'name' => 'Standard Membership',
                'model' => 'Subscription',
                'description' => 'Access to standard features and content',
                'price' => 14.99,
                'photo' => 'standard.jpg',
                'quantity' => 999,
            ],
            [
                'code' => 'WEBSEC-01',
                'name' => 'Web Security Course',
                'model' => 'Digital Course',
                'description' => 'Complete course on web security fundamentals',
                'price' => 49.99,
                'photo' => 'websec.jpg',
                'quantity' => 100,
            ],
            [
                'code' => 'ETHACK-01',
                'name' => 'Ethical Hacking Guide',
                'model' => 'Digital Book',
                'description' => 'Comprehensive guide on ethical hacking techniques',
                'price' => 39.99,
                'photo' => 'hacking.jpg',
                'quantity' => 150,
            ],
            [
                'code' => 'PENTEST-01',
                'name' => 'Penetration Testing Tools',
                'model' => 'Software Bundle',
                'description' => 'Collection of tools for penetration testing',
                'price' => 79.99,
                'photo' => 'pentest.jpg',
                'quantity' => 50,
            ],
        ];

        foreach ($products as $productData) {
            $quantity = $productData['quantity'];
            unset($productData['quantity']);
            
            // Create or update the product
            $product = Product::firstOrCreate(
                ['code' => $productData['code']],
                $productData
            );
            
            // Create or update inventory for the product
            ProductInventory::firstOrCreate(
                ['product_id' => $product->id],
                ['quantity' => $quantity]
            );
        }
    }
}
