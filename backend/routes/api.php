<?php

use App\Http\Controllers\Admin\StatusController;
use App\Http\Controllers\LoginController;
use App\Http\Controllers\Shop\CartController;
use App\Http\Controllers\Shop\OrderController;
use Illuminate\Support\Facades\Route;

Route::get('/user', function (Request $request) {
    return $request->user();
})->middleware('auth:sanctum');

Route::group([
    'middleware' => [],
], function () {
    Route::post('login', [LoginController::class, 'login'])->name('admin.login');
    Route::post('register', [LoginController::class, 'register'])->name('admin.register');

    Route::group([
        'middleware' => ['auth.optional:sanctum'],
    ], function () {
        Route::get('categories', [\App\Http\Controllers\Shop\CategoryController::class, 'index'])->name('categories.index');
        Route::get('products', [\App\Http\Controllers\Shop\ProductController::class, 'index'])->name('products.index');

        Route::group([
            'prefix' => 'carts',
            'middleware' => [],
        ], function () {
            Route::get('/', [CartController::class, 'show'])->name('cart.show');
            Route::post('items', [CartController::class, 'store'])->name('cart.products.store');
            Route::put('{cart}/products/{product}', [CartController::class, 'updateProduct'])->name('cart.products.update');
            Route::delete('{cart}/products/{product}', [CartController::class, 'deleteProduct'])->name('cart.products.delete');
        });

        Route::post('orders', [OrderController::class, 'store'])->name('cart.orders.store');

        Route::middleware('auth:sanctum')->group(function () {
            Route::get('orders', [OrderController::class, 'index'])->name('user.orders.index');
        });
    });

    Route::group([
        'prefix' => 'admin',
    ], function () {
        Route::group([
            'middleware' => ['auth:sanctum'],
        ], function () {
            Route::post('products/{product}/image', [\App\Http\Controllers\Admin\ProductController::class, 'uploadImage'])
                ->name('admin.products.uploadImage');

            Route::resources([
                'categories' => \App\Http\Controllers\Admin\CategoryController::class,
                'products' => \App\Http\Controllers\Admin\ProductController::class,
            ], ['except' => ['edit', 'create']]);

            Route::resource('orders', \App\Http\Controllers\Admin\OrderController::class)->only('index', 'update');
            Route::get('status', [StatusController::class, 'index'])->name('status');
        });
    });
});
