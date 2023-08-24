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
    public function index()
    {
        // $this->authorize('view-admin');
        $stocks = Stock::latest("id")->paginate(5)->withQueryString();
        return StockResource::collection($stocks);
    }

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
