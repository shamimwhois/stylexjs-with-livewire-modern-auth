<?php

use App\Http\Controllers\Api\AuthController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

Route::get('/user', function (Request $request) {
    return $request->user();
})->middleware('auth:sanctum');

Route::post('/v1/auth/check-breach', [AuthController::class, 'checkBreach'])
    ->middleware('throttle:10,1');

Route::prefix('v1/auth')->middleware('throttle:api')->group(function () {
    Route::post('/login', [AuthController::class, 'login'])
        ->middleware('throttle:api-login');

    Route::post('/register', [AuthController::class, 'register'])
        ->middleware('throttle:10,1');

    Route::post('/logout', [AuthController::class, 'logout'])
        ->middleware('auth:sanctum');

    Route::post('/forgot-password', [AuthController::class, 'forgotPassword'])
        ->middleware('throttle:5,1');

    Route::post('/reset-password', [AuthController::class, 'resetPassword'])
        ->middleware('throttle:10,1');

    Route::post('/otp/send', [AuthController::class, 'sendOtp'])
        ->middleware('throttle:5,1');

    Route::post('/otp/verify', [AuthController::class, 'verifyOtp'])
        ->middleware('throttle:10,1');
});
