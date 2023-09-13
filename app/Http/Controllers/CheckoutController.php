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
        $user = User::find(Auth::id());

        if ($user->session == false) {
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
            "voucher_number" =>  $voucherNumber . $randomString,
            "total" => $total,
            "tax" => $tax,
            "net_total" => $netTotal,
            "user_id" => Auth::id(),
        ]); // use database
        $carbon = Carbon::now();
        $voucherSelector = Voucher::orderBy('id', 'desc')->first();
        $saleRecord = DailySaleRecord::create([
            "voucher_number" => $voucherSelector->voucher_number,
            'cash' => $voucherSelector->total,
            'tax' => $voucherSelector->tax,
            'time' => $carbon->format('h:iA'),
            'total' => $voucherSelector->net_total,
        ]);

        // $products = collect($request->items)->pluck('product_id');
        // $quantity = collect($request->items)->pluck('quantity');

        // $productDetails = [];
        // foreach ($products as $index => $productId) {
        //     $product = Product::find($productId);
        //     if ($product) {
        //         $productDetails[] = [
        //             'name' => $product->name,
        //             'quantity' => $quantity[$index],
        //             'sale' => $product->sale_price
        //         ];
        //     }
        // }

        $records = [];
        $productDetails = [];

        foreach ($request->items as $item) {
            $product = Product::find($item['product_id']);

            if ($product) {
                $price = $product->sale_price;

                $productDetails[] = [
                    'name' => $product->name,
                    'quantity' => $item['quantity'],
                    'price' => $price, 
                ];

                // Update product stock
                $product->total_stock -= $item['quantity'];
                $product->save();
            }
        }

        // Calculate the total cost for the entire order
        $totalCost = array_sum(array_map(function ($item) {
            return $item['quantity'] * $item['price'];
        }, $productDetails));

        // Create a single voucher record for the entire order
        $records[] = [
            'voucher_id' => $voucher->id,
            'items' => json_encode($productDetails),
            'total_cost' => $totalCost,
            'created_at' => now(),
            'updated_at' => now(),
        ];

        $voucherRecords = VoucherRecord::insert($records); // use database
        // dd($voucherRecords);
        $date = Carbon::now()->format('Y-m-d H:i:s');

        $voucherRecordSelector = VoucherRecord::Where('created_at', $date)->get();
        $record = DailySaleRecord::orderBy('id', 'desc')->first();
        $record->count = $voucherRecordSelector->sum('quantity');
        $record->save();

        return $voucherRecords;
    }
}
