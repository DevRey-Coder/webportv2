<?php

namespace Database\Seeders;

use App\Models\Stock;
use App\Models\Product;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class StockSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $stocks = [];
        $product_stock = [];
        for($i=1;$i<=20;$i++){
            $currentQuantity = rand(1,100);
            $stocks[] = [
                "user_id" => 1,
                "product_id" => $i,
                "quantity" => $currentQuantity,
                "created_at" => now(),
                "updated_at" => now(),
            ];
            // $product_stock[] = [
            //     "id" => $i,
            //     "total_stock" => $currentQuantity
            // ];
            $currentProduct = Product::find($i);
            $currentProduct->total_stock = $currentQuantity;
            $currentProduct->update();
        }

        Stock::insert($stocks);
        // Product::update

        // Stock::factory(10)->create();
    }
}
