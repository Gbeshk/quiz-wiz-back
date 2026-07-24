<?php

use App\Http\Controllers\Auth\ForgotPasswordController;
use App\Http\Controllers\Auth\LoginController;
use App\Http\Controllers\Auth\LogoutController;
use App\Http\Controllers\Auth\RegisterController;
use App\Http\Controllers\Auth\ResendVerificationController;
use App\Http\Controllers\Auth\ResetPasswordController;
use App\Http\Controllers\Auth\VerifyEmailController;
use App\Http\Controllers\QuizController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
})->name('user.show');

Route::post('/register', RegisterController::class)->name('register');

Route::post('/login', LoginController::class)
    ->middleware('throttle:login')
    ->name('login');

Route::post('/logout', LogoutController::class)
    ->middleware('auth:sanctum')
    ->name('logout');

Route::get('/email/verify/{id}/{hash}', [VerifyEmailController::class, 'verify'])
    ->middleware('signed')
    ->name('verification.verify');

Route::post('/email/resend-verification', ResendVerificationController::class)
    ->name('verification.resend');

Route::post('/forgot-password', ForgotPasswordController::class)
    ->name('password.forgot');

Route::post('/verify-reset-token', [ResetPasswordController::class, 'verifyToken'])
    ->name('password.verify-token');

Route::post('/reset-password', ResetPasswordController::class)
    ->name('password.reset');

Route::get('/quizzes', [QuizController::class, 'index'])
    ->name('quizzes.index');

Route::get('/quizzes/{quiz}', [QuizController::class, 'show'])
    ->name('quizzes.show');

Route::get('/categories', [QuizController::class, 'categories'])
    ->name('categories.index');

Route::get('/difficulties', [QuizController::class, 'difficulties'])
    ->name('difficulties.index');
