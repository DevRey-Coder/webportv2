<?php

namespace App\Http\Controllers;

use App\Http\Resources\StockReportResource;
use App\Http\Resources\StockResource;
use App\Models\Brand;
use App\Models\DailySale;
use App\Models\Product;
use App\Models\Stock;
use App\Models\VoucherRecord;
use Carbon\Carbon;
use Illuminate\Http\Request;

class StockReportController extends Controller
{
    public function index()
    {
        $stocks = Stock::latest('id')->get()->map(function ($col) {
            $stockLvl = $col->quantity;
            switch ($stockLvl) {
                case($stockLvl === "0"):
                    $checkStockLvl = "Out of Stock";
                    break;
                case($stockLvl <= 10):
                    $checkStockLvl = "Low Stock";
                    break;
                case($stockLvl >= 11):
                    $checkStockLvl = "In Stock";
                    break;
                default:
                    $checkStockLvl = 'Something went wrong.';
            }
            return collect([
                'id' => $col->id,
                'name' => $col->product->name,
                'band' => $col->product->brand->name,
                'unit' => $col->product->unit,
                'sale price' => $col->product->sale_price,
                'total stock' => $stockLvl,
                'stock level' => $checkStockLvl,
            ]);
        });

        $percentOutOfStock = $stocks->percentage(fn($value) => $value["stock level"] === "Out of Stock") . "%";
        $percentLowStock = $stocks->percentage(fn($value) => $value["stock level"] === "Low Stock") . "%";
        $percentInStock = $stocks->percentage(fn($value) => $value["stock level"] === "In Stock") . "%";
        return response()->json($percentOutOfStock);
    }

    public function stockReport()
    {
        $totalProducts = Product::count('id');
        $totalBrands = Brand::count('id');
        $products = Product::sum('total_stock');

        $startDate = Carbon::now()->subDays(7)->startOfDay();

        $recordsForPastWeek = VoucherRecord::where('created_at', '>=', $startDate)->get()->map(function ($col) {
            $brand = $col->product->brand->name;
            return collect([
                'brand' => $brand
            ]);
        });

        return $recordsForPastWeek;
    }
}
