<?php

use App\Http\Controllers\ApiAuthController;
use App\Http\Controllers\VoucherController;
use App\Http\Controllers\VoucherRecordController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "api" middleware group. Make something great!
|
*/

// Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
//     return $request->user();
// });

Route::prefix("v1")->group(function () {

    Route::middleware('auth:sanctum')->group(function () {

        // Route::apiResource('contact', ContactController::class);

        Route::post("logout", [ApiAuthController::class, 'logout']);
        Route::post("logout-all", [ApiAuthController::class, 'logoutAll']);
        // Route::get("devices", [ApiAuthController::class, 'devices']);
        Route::apiResource("voucher",VoucherController::class);
        Route::apiResource("voucher-record",VoucherRecordController::class);

    });


    // Route::post("register", [ApiAuthController::class, 'register']);
    Route::post("login", [ApiAuthController::class, 'login']);
});
