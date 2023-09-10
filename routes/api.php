<?php

use App\Http\Controllers\CheckoutController;
use App\Http\Controllers\ApiAuthController;
use App\Http\Controllers\BrandController;
use App\Http\Controllers\PhotoController;
use App\Http\Controllers\ProductController;
use App\Http\Controllers\StockController;
use App\Http\Controllers\SessionController;
use App\Http\Controllers\DailySaleRecordController;
use Illuminate\Http\Request;
use App\Http\Controllers\UserController;
use App\Http\Controllers\VoucherController;
use App\Http\Controllers\VoucherRecordController;
use App\Http\Middleware\UserBanMiddleware;
use Illuminate\Support\Facades\Route;

Route::prefix("v1")->group(function () {

    Route::middleware('auth:sanctum')->group(function () {

        Route::apiResource('product', ProductController::class);
        Route::apiResource('brand', BrandController::class);
        Route::apiResource('stock', StockController::class);
        // Route::apiResource('contact', ContactController::class);
        Route::post("logout", [ApiAuthController::class, 'logout']);
        Route::post("logout-all", [ApiAuthController::class, 'logoutAll']);
        Route::get("users", [ApiAuthController::class, 'showAllUser']);
        Route::get("user-current", [ApiAuthController::class, 'showCurrentUser']);
        Route::post("user-ban/{id}", [ApiAuthController::class, 'ban']);
        Route::delete("user-unban/{id}", [ApiAuthController::class, 'unban']);
        // Route::get("devices", [ApiAuthController::class, 'devices']);
        Route::apiResource("voucher", VoucherController::class);
        Route::apiResource("voucher-record", VoucherRecordController::class);
        Route::post("register", [ApiAuthController::class, 'register']);

        Route::apiResource("photo", PhotoController::class);
        Route::post('session-on',[SessionController::class,'sessionOn']);
        Route::post('session-off',[SessionController::class,'sessionOff']);
        Route::get('daily',[DailySaleRecordController::class,'daily']);
        Route::get('daily-total',[DailySaleRecordController::class,'dailyTotal']);
        Route::get('monthly',[DailySaleRecordController::class,'monthly']);
        Route::get('monthly-total',[DailySaleRecordController::class,'monthlyTotal']);
        Route::get('yearly',[DailySaleRecordController::class,'yearly']);
        Route::get('yearly-total',[DailySaleRecordController::class,'yearlyTotal']);
        Route::get('db-test',[DailySaleRecordController::class,'dbTest']);
//        Route::post("session-off", SessionController::class)



//         Route::apiResource("user", UserController::class);

        Route::post("check-out", [CheckoutController::class, 'checkout']);
    });

    Route::post("login", [ApiAuthController::class, 'login']);
//     Route::get("media",[MediaController::class,'index']);
//     Route::get("media/{media}",[MediaController::class,'show']);

//     Route::delete('photo/{photo}',[PhotoController::class,'destroy']);
//     Route::put('photo/{photo}',[PhotoController::class,'update']);
});
