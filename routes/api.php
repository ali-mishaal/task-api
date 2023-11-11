<?php

use App\Http\Controllers\Api\V1\Admin\LoginController;
use App\Http\Controllers\Api\V1\Admin;

Route::get('travels', \App\Http\Controllers\Api\V1\TravelController::class);

Route::group(['prefix' => 'admin', 'middleware' => ['auth:sanctum']], function () {
    Route::middleware('role:admin')->group(function () {
        Route::post('travels', [Admin\TravelController::class, 'store']);
        Route::post('travels/{travel}/tours', [Admin\TourController::class, 'store']);
    });

    Route::middleware('role:editor')->group(function () {
        Route::put('travels/{travel}', [Admin\TravelController::class, 'update']);
    });

});


Route::get('travels/{travel:slug}/tours', [\App\Http\Controllers\Api\V1\TourController::class, 'index']);

Route::post('login', [LoginController::class, 'login']);




