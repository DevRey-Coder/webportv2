<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreProductRequest;
use App\Http\Requests\UpdateProductRequest;
use App\Http\Resources\ProductDetailResource;
use App\Http\Resources\ProductResource;
use App\Models\Product;
use Dotenv\Util\Str;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;

class ProductController extends Controller
{
    
    // 112
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $products = Product::latest("id")->paginate(5)->withQueryString();
        return ProductResource::collection($products);
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
    public function store(StoreProductRequest $request)
    {

        $product = Product::create([
            'name' => $request->name,
            'brand_id' => $request->brand_id,
            'actual_price' => $request->actual_price,
            'sale_price' => $request->sale_price,
            'total_price' => $request->total_price,
            'unit' => $request->unit,
            'more_information' => $request->more_information,
            'user_id' => Auth::id(),
            'photo' => $request->photo
        ]);

        return new ProductDetailResource($product);
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        $product = Product::find($id);
        if (is_null($product)) {
            return response()->json(['message' => 'Product not found'], 404);
        }

        return new ProductDetailResource($product);
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Product $product)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateProductRequest $request, string $id)
    {
        $product = Product::find($id);
        if (is_null($product)) {
            return response()->json([
                // "success" => false,
                "message" => "Product not found",

            ], 404);
        }

        $product->update([
            'name' => $request->name,
            'brand_id' => $request->brand_id,
            'actual_price' => $request->actual_price,
            'sale_price' => $request->sale_price,
            'total_price' => $request->total_price,
            'unit' => $request->unit,
            'more_information' => $request->more_information,
            'photo' => $request->photo
        ]);

        return new ProductDetailResource($product);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        $product = Product::find($id);
        if (is_null($product)) {
            return response()->json([
                // "success" => false,
                "message" => "product not found",

            ], 404);
        }
        $product->delete();

        // return response()->json([],204);
        return response()->json([
            "message" => "product is deleted",
        ]);
    }
}
