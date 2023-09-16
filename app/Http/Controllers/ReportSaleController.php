<?php

namespace App\Http\Controllers;

use App\Http\Resources\DailyTotalResource;
use App\Models\DailySale;
use App\Models\Product;
use App\Models\Voucher;
use App\Models\VoucherRecord;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\DB;

class ReportSaleController extends Controller
{
    public function weeklyReport()
    {
        // Get today's date
        $today = Carbon::now()->format('Y-m-d');

        // Check if a query parameter is present
        $query = request()->input('query');

        // Calculate the start and end dates for the week
        $startDate = Carbon::now()->subDays(6)->startOfDay();
        $endDate = Carbon::now()->endOfDay();

        // Query for daily totals within the specified date range
        $dailyTotal = DailySale::when($query, function ($queryBuilder, $query) use ($startDate, $endDate) {
            return $queryBuilder->whereBetween('created_at', [$startDate, $endDate]);
        })->when(request()->has('id'), function ($query) {
            $sortType = request()->id ?? 'asc';
            $query->orderBy("id", $sortType);
        })->latest("id")->paginate(5)->withQueryString();

        // Calculate the total daily total
        $totalDailyTotal = $dailyTotal->sum('dailyTotal');

        // Initialize an array to store daily totals
        $dailyTotals = [];

        // Loop through each day in the past week
        while ($startDate <= $endDate) {
            // Query records for the current date
            $recordsForDay = VoucherRecord::whereDate('created_at', $startDate->toDateString())->get();

            // Calculate the total for the current day
            $dailyTotal = $recordsForDay->sum('total_cost');

            // Store the daily total along with the date
            $dailyTotals[] = [
                'id' => $startDate->format('Ymd'),
                'user_id' => auth()->user()->id, // You can replace this with the actual user ID
                'start' => $startDate->format('Y-m-d H:i:s'),
                'end' => $startDate->format('Y-m-d H:i:s'),
                'time' => $startDate->format('d M Y'),
                'vouchers' => $recordsForDay->count(), // Count of vouchers for the day
                'dailyCash' => $recordsForDay->sum('cash'), // Sum of cash for the day
                'dailyTax' => $recordsForDay->sum('tax'), // Sum of tax for the day
                'dailyTotal' => $dailyTotal, // Total cost for the day
                'created_at' => $startDate->toIso8601String(),
                'updated_at' => $startDate->toIso8601String(),
            ];

            // Move to the next day
            $startDate->addDay();
        }

        // Query today's data for VoucherRecord
        $totalSingleDayData = VoucherRecord::where('created_at', '>=', '2023-09-14')->get();

        // Initialize an array for daily data
        $dailyData = [];

        // Loop through the data and extract relevant information
        foreach ($totalSingleDayData as $data) {
            $dailyData[] = [
                'Voucher_id' => $data->voucher_id,
                'Total' => $data->total_cost,
            ];
        }

        // Sort daily data by 'Total' in descending order
        $sortedDailyData = collect($dailyData)->sortByDesc('Total')->toArray();

        // Query weekly data for VoucherRecord
        $date = Carbon::now()->subDays(7);
        $weeklyData = VoucherRecord::where('created_at', '>=', $date)->get();

        // Calculate the total cost for the past week
        $totalCostPastWeek = $weeklyData->sum('total_cost');

        // Initialize an array for the top selling products
        $topSellingProductsData = [];

        // Process the top selling products data
        $itemsCount = [];

        foreach ($weeklyData as $record) {
            $items = json_decode($record->items, true);

            foreach ($items as $item) {
                $itemName = $item['name'];
                $itemQuantity = $item['quantity'];

                if (!isset($itemsCount[$itemName])) {
                    $itemsCount[$itemName] = 0;
                }

                $itemsCount[$itemName] += $itemQuantity;
            }
        }

        // Sort the items by quantity count in descending order
        arsort($itemsCount);

        foreach ($itemsCount as $itemName => $quantity) {
            // Retrieve the product associated with the item
            $product = Product::where('name', $itemName)->first();

            if ($product) {
                // Retrieve the brand associated with the product
                $brand = $product->brand;
                $brandName = $brand ? $brand->name : 'Local Brand';

                $topSellingProductsData[] = [
                    'Product Name' => $itemName,
                    'Brand Name' => $brandName,
                    'Quantity Sold' => $quantity,
                    'Date' => $record->created_at->format('Y-m-d'),
                ];
            }
        }

        // Return the desired data
        return [
            'dailyTotal' => $totalDailyTotal,
            'dailyTotals' => $dailyTotals,
            'dailyData' => $sortedDailyData,
            'weeklyTotalCost' => $totalCostPastWeek,
            'topSellingProductsData' => $topSellingProductsData,
        ];
    }

    function monthlyReport()
    {
        // Get the current month.
        $carbon = Carbon::now();
        $value = $carbon->format('Y-m');

        // Query for all records in the VoucherRecord table where the created_at column is like the $value variable.
        $monthlyData = VoucherRecord::whereDate('created_at', 'like', "%$value%")->get();

        // Calculate the total cost for the past month.
        $totalCostPastMonth = $monthlyData->sum('total_cost');

        // Return the monthly data and the total cost for the past month.
        return [
            'monthlyTotal' => $totalCostPastMonth,
            'monthlyData' => $monthlyData,
        ];
    }

    public function yearlyReport()
    {
        // Get the current year.
        $carbon = Carbon::now();
        $year = $carbon->format('Y');

        // Query for all daily sales records for the current year.
        $yearlySales = DailySale::whereYear('created_at', $year)->get();

        // Calculate the total sales for the year.
        $yearlyTotalSales = $yearlySales->sum('dailyTotal');

        // Group the daily sales records by month name.
        $monthlySales = $yearlySales->groupBy('created_at_month_name');

        // Calculate the total sales for each month.
        $monthlySalesTotals = $monthlySales->map(function ($monthlySales) {
            return $monthlySales->sum('dailyTotal');
        })->filter(function ($totalSales) {
            return $totalSales > 0;
        })->toArray();

        // Return the monthly sales totals and the yearly total sales.
        return [
            'yearlyTotalSales' => $yearlyTotalSales,
            'monthlySalesTotals' => $monthlySalesTotals,
        ];
    }
}
