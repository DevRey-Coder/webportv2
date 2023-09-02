<?php

namespace App\Http\Controllers;

use App\Models\DailySale;
use App\Models\DailySalesRecords;
use App\Models\Product;
use App\Models\Voucher;
use App\Models\VoucherRecord;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Str;

class CheckoutController extends Controller
{
    public function checkout(Request $request)
    {
        // datetime with carbon
        $dateTime = Carbon::now();
        //->format('Y-m-d H:i:s')

        // Checking Sale before opening time or not.
        $openingTime = $dateTime->copy()->setTime(6, 0, 0);
        if ($dateTime < $openingTime) {
            return response()->json([
                'success' => false,
                'message' => 'Sales are closed before 6:00 AM.',
            ]);
        }

        // Date formatting
        // $formattedDateTime = $dateTime->format('Y-m-d H:i:s');

        // Getting Product data based on product's id
        $productIds = collect($request->items)->pluck("product_id");
        $products = Product::whereIn("id", $productIds)->get(); // use database
        $total = 0;

        foreach ($request->items as $item) {
            $total += $item["quantity"] * $products->find($item["product_id"])->sale_price;
        }

        $tax = $total * 0.05;
        $netTotal = $total + $tax;

        // Generate a random string of letters and numbers
        $voucherNumber = random_int(1000, 9999);
        $randomString = Str::random(3);

        // $query = DailySale::whereDate('date', Carbon::today()->tz('Asia/Yangon'))->whereNotNull('date');
        // $dailySale = $query->first();

        $voucher = Voucher::create([
            "voucher_number" =>  $voucherNumber . $randomString,
            "total" => $total,
            "tax" => $tax,
            "net_total" => $netTotal,
            "user_id" => Auth::id(),
            "date" => $dateTime,
        ]); // use database

        // Fetch or create the DailySale record for today
        $dailySale = DailySale::create(
            [
                'date' => Carbon::today(),
                'tax' => $tax, // Provide an initial value for tax field
                'net_total' => $netTotal, // Provide an initial value for net_total field
                'status' => 'open', // Provide an initial value for status field
            ]
        );

        $records = [];

        foreach ($request->items as $item) {

            $currentProduct = $products->find($item["product_id"]);
            $records[] = [
                "voucher_id" => $voucher->id,
                "product_id" => $item["product_id"],
                "price" => $currentProduct->sale_price,
                "quantity" => $item["quantity"],
                "cost" => $item["quantity"] * $currentProduct->sale_price,
                "created_at" => now(),
                "updated_at" => now()
            ];
            Product::where("id", $item["product_id"])->update([
                "total_stock" => $currentProduct->total_stock - $item["quantity"]
            ]);
        }

        // Create a DailySalesRecords entry
        $dailySaleRecord = DailySalesRecords::create([
            "daily_sale_id" => $dailySale->id,
            "voucher_id" => $voucher->id
        ]);

        $voucherRecords = VoucherRecord::insert($records); // use database
        // dd($voucherRecords);

        return response()->json([
            'success' => true,
            'message' => 'Sale created successfully',
            'data' => [
                'voucher' => $voucherRecords,
                'daily_sale_record' => $dailySaleRecord,
            ]
        ]);
    }
}
