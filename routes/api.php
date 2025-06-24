<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\TerlaporController;

Route::prefix('v1')->group(function () {
    Route::middleware(['auth:sanctum', 'role:mediator'])->prefix('akun')->group(function () {
        Route::get('/', [TerlaporController::class, 'index']);
        Route::post('/', [TerlaporController::class, 'store']);
        Route::get('/{id}', [TerlaporController::class, 'show']);
        Route::patch('/{id}/deactivate', [TerlaporController::class, 'deactivate']);
        Route::patch('/{id}/activate', [TerlaporController::class, 'activate']);
    });
});
