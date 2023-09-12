<?php

namespace App\Http\Controllers;

use App\Http\Resources\StockReportResource;
use App\Http\Resources\StockResource;
use App\Models\Product;
use App\Models\Stock;
use Illuminate\Http\Request;

class StockReportController extends Controller
{
    public function index()
    {
        $stocks = Stock::latest('id')->get()->map(function ($col) {
            $stockLvl = $col->quantity;
            switch ($stockLvl) {
                case($stockLvl === "0"):
                    $checkStockLvl = "No stock";
                    break;
                case($stockLvl <= 10):
                    $checkStockLvl = "Low stock";
                    break;
                case($stockLvl >= 11):
                    $checkStockLvl = "In stock";
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
        return response()->json($stocks);

    }
}
