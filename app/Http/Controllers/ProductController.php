<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreProductRequest;
use App\Http\Requests\UpdateProductRequest;
use App\Http\Resources\ProductDetailResource;
use App\Http\Resources\ProductResource;
use App\Models\Product;
use GuzzleHttp\Psr7\Request;
use Illuminate\Support\Facades\Auth;

//clean
class ProductController extends Controller
{
    public function index()
    {
        $query = request()->input('query');

        $products = Product::when($query, function ($queryBuilder, $query) {
            return $queryBuilder->where('name', 'like', "%$query%");
            // ->orWhere('brand', 'like', "%$query%");
        })
            ->when(request()->has('id'), function ($query) {
                $sortType = request()->id ?? 'asc';
                $query->orderBy("id", $sortType);
            })
            ->latest("id")
            ->paginate(10)
            ->withQueryString();
        return ProductResource::collection($products);
    } 
    
    public function store(StoreProductRequest $request)
    {
        $product = Product::create([
            'name' => $request->name,
            'brand_id' => $request->brand_id,
            'actual_price' => $request->actual_price,
            'sale_price' => $request->sale_price,
            'unit' => $request->unit,
            'more_information' => $request->more_information,
            'user_id' => Auth::id(),
            'photo' => $request->photo,
        ]);
        return new ProductDetailResource($product);
    }

    public function show(string $id)
    {
        $product = Product::find($id);
        if (is_null($product)) {
            return response()->json(['message' => 'Product not found'], 404);
        }
        return new ProductDetailResource($product);
    }

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
            'unit' => $request->unit,
            'more_information' => $request->more_information,
            'photo' => $request->photo,
        ]);

        return new ProductDetailResource($product);
    }

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
