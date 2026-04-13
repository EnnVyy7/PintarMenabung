<?php

use App\Http\Controllers\AuthController;
use App\Http\Controllers\WalletController;
use App\Models\Category;
use App\Models\Currency;
use Illuminate\Support\Facades\Route;

// Route::get('/user', function (Request $request) {
//     return "halo";
// });

Route::prefix('v1')->group(function () {
    Route::post("/auth/register", [AuthController::class, 'register']);
    Route::post('/auth/login', [AuthController::class, 'login']);

    Route::middleware('auth:sanctum')->group(function () {
        Route::post('/auth/logout', [AuthController::class, 'logout']);

        // Currency
        Route::get('/currencies', function () {
            return response()->json([
                'status' => 'success',
                'message' => 'Get All Currencies succesful',
                'data' => Currency::all()
            ]);
        });

        // Category
        Route::get('/categories', function () {
            return response()->json([
                'status' => 'success',
                'message' => 'Get all categories successful',
                'data' => Category::all()
            ]);
        });

        // Route Wallet
        Route::post('/wallets', [WalletController::class, 'create']);
        Route::put('/wallets/{walletId}', [WalletController::class, 'update']);
        Route::delete('/wallets/{walletId}', [WalletController::class, 'delete']);
        Route::get('/wallets', [WalletController::class, 'index']);
        Route::get('/wallets/{wallet:id}', [WalletController::class, 'find']);
    });
});
