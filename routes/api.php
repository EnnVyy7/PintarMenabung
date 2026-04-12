<?php

use App\Http\Controllers\AuthController;
use Illuminate\Support\Facades\Route;

// Route::get('/user', function (Request $request) {
//     return "halo";
// });

Route::prefix('v1')->group(function () {
    Route::post("/auth/register", [AuthController::class, 'register']);
    Route::post('/auth/login', [AuthController::class, 'login']);
});
