<?php

use App\Http\Controllers\Api\V1\UrlAnalyticController;
use App\Http\Controllers\Api\V1\UrlController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

Route::get('/user', function (Request $request) {
    return $request->user();
})->middleware('auth:sanctum');

Route::get('{url}', [UrlController::class, 'show'])->name('urls.show');
Route::post('shorten', [UrlController::class, 'store'])->name('urls.store');
Route::get('analytics/{url}', UrlAnalyticController::class)->name('analytics.show');
