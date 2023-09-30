<?php

namespace App\Http\Controllers;

use App\Models\DailySale;
use App\Models\DailySaleRecord;
use App\Models\Product;
use App\Models\User;
use App\Models\Voucher;
use App\Models\VoucherRecord;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

class CheckoutController extends Controller
{
    public function checkout(Request $request)
    {
        try {
            DB::beginTransaction();

            // Checking session close or not
            $user = User::find(Auth::id());
            $carbon = Carbon::now();

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
                $total += $item["quantity"] * $products->find($item["product_id"])->sale_price;
                $totalActualPrice += $item["quantity"] * $products->find($item["product_id"])->actual_price;
            }

            // Calculate tax and total with tax
            $tax = $total * 0.05;
            $netTotal = $total + $tax;

            //Generate Voucher number
            $voucherNumber = random_int(1000, 9999);

            // Generate a random string of letters and numbers
            $randomString = Str::random(3);

            // Create the voucher and insert it into the database
            $voucher = Voucher::create([
                "voucher_number" => $voucherNumber . $randomString,
                "total" => $total,
                "total_actual_price" => $totalActualPrice,
                "tax" => $tax,
                "net_total" => $netTotal,
                "user_id" => Auth::id(),
            ]);

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
                    "cost" => $item["quantity"] * $price,
                    "created_at" => now(),
                    "updated_at" => now()
                ];

                Product::where("id", $item["product_id"])->update([
                    "total_stock" => $currentProduct->total_stock - $item["quantity"]
                ]);
            }

            $voucherSelector = Voucher::orderBy('id', 'desc')->first();

            // Insert voucher records into the database
            $voucherRecord = VoucherRecord::insert($records);

            // Calculate the total cost for the entire order
            $totalCost = array_sum(array_map(function ($item) {
                return $item['quantity'] * $item['price'];
            }, $records));


            DB::commit(); // Commit the transaction

            // Generate a response
            $output = [
                'voucher_records' => [$voucherRecord],
                'total_cost' => $totalCost,
            ];

            return response()->json($output, 200);
        } catch (Exception $e) {
            DB::rollback(); // Rollback the transaction in case of an exception

            // Handle the exception and return an appropriate response
            return response()->json(['message' => 'An error occurred while processing the request'], 500);
        }
    }
}
