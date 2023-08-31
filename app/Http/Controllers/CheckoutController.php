<?php

namespace App\Http\Controllers;

use App\Models\DailySale;
use App\Models\Product;
use App\Models\User;
use App\Models\Voucher;
use App\Models\VoucherRecord;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Str;

class CheckoutController extends Controller
{
    public function checkout(Request $request)
    {
        $user = User::find(Auth::id());

        if ($user->session == false ){
            return response()->json([
                "message" => "Cashier has closed",
            ]);
        }
        $productIds = collect($request->items)->pluck("product_id");
        $products = Product::whereIn("id", $productIds)->get(); // use database
        $total = 0;

        foreach ($request->items as $item) {
            $total += $item["quantity"] * $products->find($item["product_id"])->sale_price;
        }

        $tax = $total * 0.05;
        $netTotal = $total + $tax;

        $voucherNumber = random_int(1000, 9999);

        // Generate a random string of letters and numbers
        $randomString = Str::random(3);

        $voucher = Voucher::create([
            "voucher_number" =>  $voucherNumber.$randomString,
            "total" => $total,
            "tax" => $tax,
            "net_total" => $netTotal,
            "user_id" => Auth::id(),
        ]); // use database

        $session = DailySale::orderBy('id', 'desc')->first();
        $voucherSelector = Voucher::orderBy('id', 'desc')->first();
        $session->voucher_number = $voucherSelector->voucher_number;
        $session->cash = $voucherSelector->total;
        $session->tax = $voucherSelector->tax;
        $session->total = $voucherSelector->net_total;
        $session->save();

        $records = [];

        foreach ($request->items as $item) {

            $currentProduct = $products->find($item["product_id"]);
            $records[] = [
                "voucher_id" => $voucher->id,
                "product_id" => $item["product_id"],
                "price" => $products->find($item["product_id"])->sale_price,
                "quantity" => $item["quantity"],
                "cost" => $item["quantity"] * $currentProduct->sale_price,
                "created_at" => now(),
                "updated_at" => now()
            ];
            Product::where("id", $item["product_id"])->update([
                "total_stock" => $currentProduct->total_stock - $item["quantity"]
            ]);
        }

        $voucherRecords = VoucherRecord::insert($records); // use database
        // dd($voucherRecords);
        $voucherRecordSelector = VoucherRecord::orderBy('id', 'desc')->first();
        $session->count = $voucherRecordSelector->quantity;
        $session->save();



        return $request;
    }
}
