<?php

use App\Http\Controllers\Shop\CartController;
use Illuminate\Support\Facades\Route;

//Route::get('/user', function (Request $request) {
//    return $request->user();
//})->middleware('auth:sanctum');

Route::group([
    'middleware' => [
    ],
], function () {
    Route::get('categories', [\App\Http\Controllers\Shop\CategoryController::class, 'index'])->name('categories.index');
    Route::get('products', [\App\Http\Controllers\Shop\ProductController::class, 'index'])->name('products.index');

    Route::group([
        'prefix' => 'carts',
        'middleware' => [],
    ], function () {
        Route::post('', [CartController::class, 'store'])->name('cart.products.store');
        Route::put('{cart}/products/add', [CartController::class, 'addProduct'])->name('cart.products.add');
        Route::put('{cart}/products/{product}', [CartController::class, 'updateProduct'])->name('cart.products.update');
        Route::delete('{cart}/products/{product}', [CartController::class, 'deleteProduct'])->name('cart.products.delete');
    });

    Route::post('orders', [\App\Http\Controllers\Shop\OrderController::class, 'store'])->name('cart.orders.store');

    Route::group([
        'prefix' => 'admin',
        'middleware' => [],
    ], function () {
        Route::resources([
            'category' => \App\Http\Controllers\Admin\CategoryController::class,
            'product' => \App\Http\Controllers\Admin\ProductController::class,
            'order' => \App\Http\Controllers\Admin\OrderController::class,
        ], ['except' => ['edit', 'create']]);
    });
});
