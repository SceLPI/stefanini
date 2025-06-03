<?php

use App\Http\Controllers\AuthController;
use App\Http\Controllers\ProjectController;
use App\Http\Controllers\WeatherController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

Route::prefix('auth')->name('auth.')->group(function () {
    Route::post('register', [AuthController::class, 'register'])->name('register');
    Route::post('login', [AuthController::class, 'login'])->name('login');
    Route::middleware('auth:sanctum')->group(function () {
        Route::delete('logout', [AuthController::class, 'logout'])->name('logout');
        Route::get('me', [AuthController::class, 'me'])->name('me');
    });
});

Route::prefix('v1')->middleware('auth:sanctum')->name('v1.')->group(function () {
    Route::prefix('projects')->name('projects.')->group(function () {
        Route::get('/', [ProjectController::class, 'index'])->name('index');
        Route::post('/', [ProjectController::class, 'store'])->name('store');
        Route::put('/{project}', [ProjectController::class, 'update'])->name('update');
        Route::get('/{project}', [ProjectController::class, 'show'])->name('show');
        Route::delete('/{project}', [ProjectController::class, 'destroy'])->name('destroy');
    });
});

Route::prefix('v1')->name('v1.')->group(function () {
    Route::prefix('weather')->name('weather.')->group(function () {
        Route::get('/{city}', [WeatherController::class, 'show'])->name(name: 'show');
    });
});
