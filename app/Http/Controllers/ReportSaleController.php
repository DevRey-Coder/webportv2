<?php

namespace App\Http\Controllers;

use App\Http\Resources\DailyTotalResource;
use App\Models\DailySale;
use App\Models\Product;
use App\Models\Voucher;
use App\Models\VoucherRecord;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\DB;

class ReportSaleController extends Controller
{
    public function dailyReport()
    {

        // Weekly Sales
        $date = Carbon::now()->subDays(7);

        $users = User::where('created_at', '>=', $date)->get();

        // Items Report
        $todayDate = Carbon::today()->format('Y-m-d');

        $recordsForToday = VoucherRecord::whereDate('created_at', $todayDate)->get();

        $itemsCount = []; // Create an array to store item quantities

        foreach ($recordsForToday as $record) {
            $items = json_decode($record->items, true); // Convert the "items" JSON to PHP array

            foreach ($items as $item) {
                $itemName = $item['name'];
                $itemQuantity = $item['quantity'];

                if (!isset($itemsCount[$itemName])) {
                    $itemsCount[$itemName] = 0;
                }

                // Increment the item's quantity count
                $itemsCount[$itemName] += $itemQuantity;
            }
        }

        // Sort the items by quantity count in descending order
        arsort($itemsCount);

        $topSellingProducts = array_slice($itemsCount, 0, 5, true);

        $topSellingProductsData = [];

        foreach ($topSellingProducts as $itemName => $quantity) {
            // Retrieve the product associated with the item
            $product = Product::where('name', $itemName)->firstOrFail();

            // Retrieve the brand associated with the product
            $brand = $product->brand;

            $topSellingProductsData[] = [
                'Product Name' => $itemName,
                'Brand Name' => $brand ? $brand->name : 'Local Brand', // Include brand name
                'Quantity Sold' => $quantity,
            ];
        }

        // Output the top 5 most selling products data as an array
        print_r($topSellingProductsData);
    }
}
