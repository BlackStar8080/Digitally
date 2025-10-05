<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Product;

class ProductSeeder extends Seeder
{
    public function run()
    {
        Product::create([
            'pro_name' => 'Product A',
            'pro_des' => 'Description for product A',
            'pro_price' => 100.00,
            'pro_qty' => 50,
            'pro_category' => 'Category 1',
        ]);

        Product::create([
            'pro_name' => 'Product B',
            'pro_des' => 'Description for product B',
            'pro_price' => 150.00,
            'pro_qty' => 30,
            'pro_category' => 'Category 2',
        ]);

        // Add more products as needed...
    }
}
