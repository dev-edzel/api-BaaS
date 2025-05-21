<?php

use App\Http\Controllers\{UserController, UserInfoController};
use App\Http\Controllers\Authentication\AuthController;
use Illuminate\Support\Facades\Route;

Route::post('register', [AuthController::class, 'register']);
Route::post('login', [AuthController::class, 'login']);
Route::post('verify-otp', [AuthController::class, 'verify']);
Route::post('password-reset-link', [AuthController::class, 'passwordResetLink']);
Route::post('password-reset', [AuthController::class, 'resetPassword']);

Route::group([
    'middleware' => 'auth.jwt'
], function () {
    Route::post('logout', [AuthController::class, 'logout']);
    Route::post('refresh-token', [AuthController::class, 'refresh']);
    Route::get('profile', [AuthController::class, 'profile']);

    Route::group([
        'prefix' => 'users/trashed',
    ], function () {
        Route::get('', [UserController::class, 'trashed']);
        Route::get('/restore/{id}', [UserController::class, 'restore']);
    });
    Route::resource('users', UserController::class);

    Route::resource('user-info', UserInfoController::class);
});
