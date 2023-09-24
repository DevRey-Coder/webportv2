<?php

namespace Database\Seeders;

use App\Models\Product;
use App\Models\Voucher;
use App\Models\VoucherRecord;
use Carbon\Carbon;
use Carbon\CarbonPeriod;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Str;

class VoucherSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $endDate = Carbon::now();
        $startDate = Carbon::create(2022, 7, 1);

        $period = CarbonPeriod::create($startDate, $endDate);
        $id = 1;
        foreach ($period as $index => $day) {
            $vouchers = [];
            $voucherCount = random_int(1, 5);
            for ($i = 1; $i <= $voucherCount; $i++) {
                $ids = [];
                $productId = random_int(1, 6);
                for ($y = 1; $y <= $productId; $y++) {
                    $ids[] = random_int(1, 20);
                }
                $products = Product::whereIn('id', $ids)->get();
                $totalActualPrice = 0;
                $total = 0;

                $records = [];
                foreach ($ids as $itemId) {
                    $quantity = random_int(1, 5);
                    $currentProduct = $products->find($itemId);
                    $totalActualPrice += $quantity * $currentProduct->actual_price;
                    $total += $quantity * $currentProduct->sale_price;
                    $price = $currentProduct->sale_price;

                    $records[] = [
                        "voucher_id" => $id,
                        "product_id" => $itemId,
                        "actual_price" => $currentProduct->actual_price,
                        "price" => $price,
                        "quantity" => $quantity,
                        "cost" => $quantity * $currentProduct->sale_price,
                        'time' => $endDate->format('h:iA'),

                        "created_at" => $day,
                        "updated_at" => $day
                    ];
                    Product::where("id", $itemId)->update([
                        "total_stock" => $currentProduct->total_stock - $quantity
                    ]);
                }
                VoucherRecord::insert($records); // use database
                $voucherNumber = random_int(1000, 9999);
                $randomString = Str::random(3);
                $tax = $total * 0.05;
                $netTotal = $total + $tax;
                $vouchers[] = [
                    "voucher_number" => $voucherNumber . $randomString,
                    "total_actual_price" => $totalActualPrice,
                    "total" => $total,
                    "tax" => $tax,
                    "net_total" => $netTotal,
                    "user_id" => 1,
                    "created_at" => $day,
                    "updated_at" => $day
                ];
                $id++;
            }
            Voucher::insert($vouchers);
        }
    }

}
