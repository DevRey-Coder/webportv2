<?php

namespace App\Http\Controllers;

use App\Models\VoucherRecord;
use Illuminate\Contracts\Database\Eloquent\Builder;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class VoucherRecordController extends Controller
{
    public function index()
    {
        $voucherRecord = VoucherRecord::when(request()->has("keyword"), function ($query) {
            $query->where(function (Builder $builder) {
                $keyword = request()->keyword;

                $builder->where("voucher_id", "like", "%" . $keyword . "%");
            });
        })->when(request()->has('price_Under_5000'), function ($query) {
            $query->where('price', '<', 5000);})->latest("id")->paginate(5)->withQueryString();

        return response()->json($voucherRecord);
    }

    public function store(Request $request)
    {
        $request->validate([
            "voucher_id" => 'required',
            "product_id"=> 'required',
            "quantity" => "required",
            "cost" => "required",
        ]);

        $voucherRecord = VoucherRecord::create([
            "voucher_id" => $request->input("voucher_id"),
            "product_id" => $request->input("product_id"),
            "quantity" => $request->input("quantity"),
            "cost" => $request->input("cost"),
        ]);
        return response()->json(['message' => 'Voucher_record created successfully', 'voucher' => $voucherRecord], 201);
    }

    public function show(string $id)
    {
        $voucherRecord = VoucherRecord::find($id);
        if (is_null($voucherRecord)) {
            return response()->json([
                "message" => "Voucher-record not found",
            ], 404);
        }
        return response()->json($voucherRecord);
    }

    public function update(Request $request, string $id)
    {
        //
    }

    public function destroy(string $id)
    {
        $voucherRecord = VoucherRecord::find($id);
        if(is_null($voucherRecord)){
            return response()->json([
                "message" => "Voucher_record not found",
            ],404);
        }
        $voucherRecord->delete();
        return response()->json([
            "message" => "Voucher_record is deleted",
        ]);
    }
}
