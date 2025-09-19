<?php

use App\Http\Controllers\CategoryController;
use App\Http\Controllers\ProductController;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('welcome');
});

// Product Routes (temporariamente em web.php para teste)
Route::prefix('api/products')->group(function () {
    Route::get('/', [ProductController::class, 'index']);
    Route::post('/', [ProductController::class, 'store']);
    Route::put('/{id}', [ProductController::class, 'update']);
    Route::delete('/{id}', [ProductController::class, 'destroy']);
    Route::get('/category/{categoryId}', [ProductController::class, 'findByCategory']);
});

// Category Routes (temporariamente em web.php para teste)
Route::prefix('api/categories')->group(function () {
    Route::post('/', [CategoryController::class, 'store']);
});
