<?php

use App\Http\Controllers\Auth\LoginController;
use App\Http\Controllers\Auth\LogoutController;
use App\Http\Controllers\Auth\RegisterController;
use App\Http\Controllers\Auth\VerifyEmailController;
use App\Http\Controllers\Auth\ForgotPasswordController;
use App\Http\Controllers\Auth\ResetPasswordController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

Route::get('/user', function (Request $request) {
    return $request->user();
})->middleware('auth:sanctum');

Route::post('/register', RegisterController::class)->middleware('throttle:6,1');

Route::get('/email/verify/{id}/{hash}', [VerifyEmailController::class, 'verify'])
    ->middleware(['signed', 'throttle:6,1'])
    ->name('verification.verify');

Route::post('/login', LoginController::class)->middleware('throttle:6,1');

Route::post('/logout', LogoutController::class)->middleware('auth:sanctum');

Route::post('/email/verification-notification', function (Request $request) {
    if ($request->user()->hasVerifiedEmail()) {
        return response()->json(['message' => 'Email already verified.']);
    }
    $request->user()->sendEmailVerificationNotification();
    return response()->json(['message' => 'Verification link sent.']);
})->middleware(['auth:sanctum', 'throttle:6,1']);

Route::post('/forgot-password', ForgotPasswordController::class)->middleware('throttle:6,1');

Route::post('/reset-password', ResetPasswordController::class)->middleware('throttle:6,1');
