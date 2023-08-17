<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreStockRequest;
use App\Http\Requests\UpdateStockRequest;
use App\Http\Resources\StockDetailResource;
use App\Http\Resources\StockResource;
use App\Models\Stock;
use App\Models\Product;
use Dotenv\Util\Str;
use Illuminate\Support\Facades\Auth;

class StockController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $stocks = Stock::latest("id")->paginate(5)->withQueryString();
        return StockResource::collection($stocks);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreStockRequest $request)
    {
        $stock = new Stock([
            'user_id' => Auth::id(),
            'quantity' => $request->quantity,
            'more' => $request->more,
        ]);

        $product = Product::findOrFail($request->product_id);
        $product->stock()->save($stock);

        $product->total_stock += $request->quantity;
        $product->save();

        return new StockDetailResource($stock);
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        $stock = Stock::find($id);
        if (is_null($stock)) {
            return response()->json(['message' => 'Stock not found'], 404);
        }

        return new StockDetailResource($stock);
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Stock $stock)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateStockRequest $request, string $id)
    {
        $stock = Stock::find($id);
        if (is_null($stock)) {
            return response()->json([
                // "success" => false,
                "message" => "Stock not found",

            ], 404);
        }

        $stock->update([
            'product_id' => $request->product_id,
            'quantity' => $request->quantity,
            'more' => $request->more,
        ]);

        return new StockDetailResource($stock);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        $stock = Stock::find($id);
        if (is_null($stock)) {
            return response()->json([
                // "success" => false,
                "message" => "Stock not found",

            ], 404);
        }
        $stock->delete();

        // return response()->json([],204);
        return response()->json([
            "message" => "Stock is deleted",
        ]);
    }
}
