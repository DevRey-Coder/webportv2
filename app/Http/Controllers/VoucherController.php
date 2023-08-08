<?php

namespace App\Http\Controllers;

use App\Models\Voucher;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class VoucherController extends Controller
{
    public function index()
    {
        $vouchers = Voucher::latest("id")->paginate(5)->withQueryString();
        return response()->json($vouchers);
    }

    public function store(Request $request)
    {
        $request->validate([
            "customer" => 'required',
            "phone" => 'nullable | min:6 | max:12',
            "voucher_number" => "required",
            "total" => "required",
            "tax" => "required",
            "net_total" => "required",
        ]);

        $voucher = Voucher::create([
            "customer" => $request->input("customer"),
            "phone" => $request->input("phone"),
            "voucher_number" => $request->input("voucher_number"),
            "total" => $request->input("total"),
            "tax" => $request->input("tax"),
            "net_total" => $request->input("net_total"),
            "user_id" => Auth::id(),
        ]);
        return response()->json(['message' => 'Voucher created successfully', 'voucher' => $voucher], 201);
    }

    public function show(string $id)
    {
        $voucher = Voucher::find($id);
        if (is_null($voucher)) {
            return response()->json([
                "message" => "Voucher not found",
            ], 404);
        }
        return response()->json($voucher);
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

        $voucher?->update([
            'customer' => $request->input("customer"),
            'phone' => $request->input("phone"),
            'voucher_number' => $request->input("voucher_number"),
            'total' => $request->input("total"),
            'net_total' => $request->input("net_total"),
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
