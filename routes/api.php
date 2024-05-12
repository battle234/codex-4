<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\FilmController;
use App\Http\Controllers\SeriesController;

// Registrazione delle route pubbliche
Route::controller(AuthController::class)->group(function () {
    Route::post('register', 'register');
    Route::post('login', 'login');
});

// Route per Film e Series con controllo admin per operazioni sensibili
Route::middleware(['jwt.verify'])->group(function () {
    Route::apiResource('films', FilmController::class)->except(['update', 'destroy']);
    Route::apiResource('series', SeriesController::class)->except(['update', 'destroy']);

    Route::middleware(['admin'])->group(function () {
        Route::put('films/{film}', [FilmController::class, 'update']);
        Route::delete('films/{film}', [FilmController::class, 'destroy']);
        Route::put('series/{series}', [SeriesController::class, 'update']);
        Route::delete('series/{series}', [SeriesController::class, 'destroy']);
        Route::get('/admin/dashboard', function () {
            return response()->json(['message' => 'Admin Dashboard']);
        });
    });

    Route::controller(AuthController::class)->group(function () {
        Route::post('logout', 'logout');
        Route::post('refresh', 'refresh');
        Route::post('me', 'me');
        Route::post('set-admin', 'setAdmin')->middleware('admin'); // Ulteriore protezione per admin
    });

    // Route specifiche per user
    Route::middleware(['user'])->group(function () {
        Route::get('/user/profile', function () {
            return response()->json(['message' => 'User Profile']);
        });
    });
});

