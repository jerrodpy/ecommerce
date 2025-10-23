<?php

use App\Http\Controllers\Shop\CategoryController as ShopCategoryController;
use App\Http\Controllers\Shop\ProductController as ShopProductController;
use Illuminate\Support\Facades\Route;

//Route::get('/user', function (Request $request) {
//    return $request->user();
//})->middleware('auth:sanctum');

Route::group([
    'middleware' => [
    ],
], function () {
    Route::get('category', [ShopCategoryController::class, 'index'])->name('category');
    Route::get('product', [ShopProductController::class, 'index'])->name('product');

    Route::group([
        'prefix' => 'admin',
        'middleware' => [],
    ], function () {
    });
});
