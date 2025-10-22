<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

//Route::get('/user', function (Request $request) {
//    return $request->user();
//})->middleware('auth:sanctum');

Route::group([
    'prefix' => 'api/v1/',
    'middleware' => [
        
    ],
], function () {

    Route::get('/', function () {
        return 'good';
    })->name('home-api');
});
