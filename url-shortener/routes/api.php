<?php

use App\Http\Controllers\Api\V1\UrlController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

Route::get('/user', function (Request $request) {
    return $request->user();
})->middleware('auth:sanctum');

Route::apiResource('urls', UrlController::class)->only(['store', 'show']);
