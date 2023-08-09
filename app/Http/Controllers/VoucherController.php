<?php

namespace App\Http\Controllers;

use App\Http\Resources\VoucherResource;
use App\Models\Voucher;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class VoucherController extends Controller
{
    public function index()
    {
        $voucher = Voucher::when(request()->has("keyword"), function ($query) {
            $query->where(function (Builder $builder) {
                $keyword = request()->keyword;

                $builder->where("name", "LIKE", "%" . $keyword . "%");
                $builder->orWhere("brand", "LIKE", "%" . $keyword . "%");
            });
        })
            ->latest("id")
            ->paginate(10)
            ->withQueryString();

        return VoucherResource::collection($voucher);
    }

    public function store(Request $request)
    {
        // $request->validate([
        //     "customer" => 'required',
        //     "phone" => 'nullable | min:6 | max:12',
        //     "voucher_number" => "required",
        //     "total" => "required",
        //     "tax" => "required",
        //     "net_total" => "required",
        // ]);

        // $voucher = Voucher::create([
        //     "customer" => $request->input("customer"),
        //     "phone" => $request->input("phone"),
        //     "voucher_number" => $request->input("voucher_number"),
        //     "total" => $request->input("total"),
        //     "tax" => $request->input("tax"),
        //     "net_total" => $request->input("net_total"),
        //     "user_id" => Auth::id(),
        // ]);
        // return response()->json(['message' => 'Voucher created successfully', 'voucher' => $voucher], 201);
        $characters = 'abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ0123456789';
        $charactersLength = strlen($characters);
        $randomString = '';

        for ($i = 0; $i < 6; $i++) {
            $randomString .= $characters[rand(0, $charactersLength - 1)];
        }
        
        $voucher = new Voucher();
        $voucher->customer = $request->customer;
        $voucher->phone = $request->phone;
        $voucher->voucher_number = $randomString;
        $total = 0;
        $voucher->total = $total;
        $voucher->tax = $voucher->total * 0.05;
        $voucher->net_total = $voucher->total + $voucher->tax;
        $voucher->user_id = Auth::id();

        $voucher->save();

        return response()->json([
            "data" => $voucher,
        ]);
    }

    public function show(string $id)
    {
        $voucher = Voucher::find($id);
        if (is_null($voucher)) {
            return response()->json([
                "message" => "Voucher not found",
            ], 404);
        }
        return new VoucherResource($voucher);
    }

    public function update(Request $request, string $id)
    {
        $request->validate([
            "customer" => 'nullable',
            "phone" => 'nullable | min:6 | max:12',
            "voucher_number" => "nullable",
            "total" => "nullable",
            "net_total" => "nullable",
        ]);

        $voucher = Voucher::find($id);
        if (is_null($voucher)) {
            return response()->json([
                "message" => "Voucher not found",
            ], 404);
        }

        // $voucher?->update([
        //     'customer' => $request->input("customer"),
        //     'phone' => $request->input("phone"),
        //     'voucher_number' => $request->input("voucher_number"),
        //     'total' => $request->input("total"),
        //     'net_total' => $request->input("net_total"),
        // ]);
        
        $voucher->update([
            "customer" => $request->customer,
            "phone" => $request->phone,
            "voucher_number" => $request->voucher_number,
            "total" => $request->total,
            "tax" => $request->total * ($request->tax / 100),
            "net_total" => $request->total + $request->total * ($request->tax / 100),
            "user_id" => $request->user_id,
        ]);
        return response()->json(['message' => 'Voucher updated successfully']);
    }

    public function destroy(string $id)
    {
        $voucher = Voucher::find($id);
        if (is_null($voucher)) {
            return response()->json([
                "message" => "Voucher not found",
            ], 404);
        }
        $voucher->delete();
        return response()->json([
            "message" => "Voucher is deleted",
        ]);
    }
}
