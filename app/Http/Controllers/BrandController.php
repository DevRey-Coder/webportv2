<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreBrandRequest;
use App\Http\Requests\UpdateBrandRequest;
use App\Http\Resources\BrandDetailResource;
use App\Http\Resources\BrandResource;
use App\Models\Brand;
use Illuminate\Support\Facades\Auth;

use function PHPUnit\Framework\returnSelf;

class BrandController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $brands = Brand::latest("id")->paginate(5)->withQueryString();
        return BrandResource::collection($brands);
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
    public function store(StoreBrandRequest $request)
    {
        $brand = Brand::create([
            'name' => $request->name,
            'company' => $request->company,
            'information' => $request->information,
            'user_id' => Auth::id(),
            'photo' => $request->photo
        ]);

        return new BrandDetailResource($brand);
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        $brand = Brand::find($id);
        if (is_null($brand)) {
            return response()->json(['message' => 'Brand not found'], 404);
        }
        return new BrandDetailResource($brand);
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Brand $brand)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateBrandRequest $request, string $id)
    {
        $brand = Brand::find($id);
        if (is_null($brand)) {
            return response()->json([
                "message" => "Brand not found",

            ], 404);
        }

        $brand->update([
            'name' => $request->name,
            'company' => $request->company,
            'information' => $request->information,
            'photo' => $request->photo
        ]);

        return new BrandDetailResource($brand);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        $brand = Brand::find($id);

        if (!$brand) {
            return response()->json(['message' => 'Brand not found'], 404);
        }

        $brand->delete();
        return response()->json(['message' => 'Brand deleted successfully'], 200);
    }
}
