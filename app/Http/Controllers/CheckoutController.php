<?php

namespace App\Http\Controllers;

use App\Models\DailySale;
use App\Models\DailySaleRecord;
use App\Models\Product;
use App\Models\User;
use App\Models\Voucher;
use App\Models\VoucherRecord;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Str;

class CheckoutController extends Controller
{
    public function checkout(Request $request)
    {

// Checking session close or not
        $user = User::find(Auth::id());
        if ($user->session == false) {
            return response()->json([
                "message" => "Cashier has closed",
            ]);
        }

// Select product_id from input request
        $productIds = collect($request->items)->pluck("product_id");

//query products with input product_id
        $products = Product::whereIn("id", $productIds)->get();

//Calculate total with input request
        $total = 0;
        $totalActualPrice = 0;
        foreach ($request->items as $item) {
//        $selectedItem = $item["quantity"] * $products->find($item["product_id"]);
            $total +=  $item["quantity"] * $products->find($item["product_id"])->sale_price;
            $totalActualPrice +=  $item["quantity"] * $products->find($item["product_id"])->actual_price;
        }

// Calculate tax and total with tax
        $tax = $total * 0.05;
        $netTotal = $total + $tax;

//Generate Voucher number
        $voucherNumber = random_int(1000, 9999);

// Generate a random string of letters and numbers
        $randomString = Str::random(3);

//Creating Voucher and insert to database
        $voucher = Voucher::create([
            "voucher_number" => $voucherNumber . $randomString,
            "total" => $total,
            "total_actual_price" => $totalActualPrice,
            "tax" => $tax,
            "net_total" => $netTotal,
            "user_id" => Auth::id(),
        ]);


//        $carbon = Carbon::now();
//        $voucherSelector = Voucher::orderBy('id', 'desc')->first();
//        $saleRecord = DailySaleRecord::create([
//            "voucher_id" => $voucherSelector->id,
//            'cash' => $voucherSelector->total,
//            'tax' => $voucherSelector->tax,
//            'time' => $carbon->format('h:iA'),
//            'total' => $voucherSelector->net_total,
//        ]);
//


        $carbon = Carbon::now();
        $records = [];
        foreach ($request->items as $item) {
            $currentProduct = $products->find($item["product_id"]);
            $price = $currentProduct->sale_price;
            $actualPrice = $currentProduct->actual_price;
            $records[] = [
                "voucher_id" => $voucher->id,
                "product_id" => $item["product_id"],
                "price" => $price,
                "actual_price" => $actualPrice,
                'time' => $carbon->format('h:iA'),
                "quantity" => $item["quantity"],
                "cost" => $item["quantity"] * $currentProduct->sale_price,
                "created_at" => now(),
                "updated_at" => now()
            ];
            Product::where("id", $item["product_id"])->update([
                "total_stock" => $currentProduct->total_stock - $item["quantity"]
            ]);
        }

//Adding products from cashier to Voucher_record table
        $voucherRecords = VoucherRecord::insert($records);

//        $date = Carbon::now()->format('Y-m-d H:i:s');
//        $voucherRecordSelector = VoucherRecord::Where('created_at', $date)->get();
//        $record = DailySaleRecord::orderBy('id', 'desc')->first();
//        $record->count = $voucherRecordSelector->sum('quantity');
//        $record->save();
        return $request;
    }
}
