<?php

use App\Http\Controllers\Admin\LoginController;
use App\Http\Controllers\Shop\CartController;
use Illuminate\Support\Facades\Route;

Route::get('/user', function (Request $request) {
    return $request->user();
})->middleware('auth:sanctum');

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
    ], function () {
        Route::post('login', [LoginController::class, 'login'])->name('admin.login');
        Route::post('register', [LoginController::class, 'register'])->name('admin.register');

        Route::group([
            'middleware' => ['auth:sanctum'],
        ], function () {
            Route::resources([
                'categories' => \App\Http\Controllers\Admin\CategoryController::class,
                'products' => \App\Http\Controllers\Admin\ProductController::class,
            ], ['except' => ['edit', 'create']]);

            Route::resource('orders', \App\Http\Controllers\Admin\OrderController::class)->only('index', 'update');
        });
    });
});
