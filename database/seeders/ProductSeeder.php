<?php

namespace Database\Seeders;

use App\Enums\ProductCodeEnum;
use App\Models\Product;
use Illuminate\Database\Seeder;

class ProductSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run(): void
    {
        Product::truncate();
        $products = [
            ['name' => 'Red Widget', 'code' => ProductCodeEnum::RED_WIDGET->value, 'price' => 32.95],
            ['name' => 'Green Widget', 'code' => ProductCodeEnum::GREEN_WIDGET->value, 'price' => 24.95],
            ['name' => 'Blue Widget', 'code' => ProductCodeEnum::BLUE_WIDGET->value, 'price' => 7.95],
        ];

        // Insert products into the database using bulk insert
        Product::insert($products);
    }
}
