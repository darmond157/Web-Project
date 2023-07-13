<?php

use App\Http\Controllers\AdminController;
use App\Http\Controllers\CategoryController;
use App\Http\Controllers\LogController;
use App\Http\Controllers\ProductController;
use App\Http\Controllers\UserController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});

Route::apiResource('admins', AdminController::class)->middleware(
    'auth:sanctum'
);

Route::post('/auth/admin', [AdminController::class, 'login']);

Route::group(['prefix' => 'users'], function () {

    Route::get('/', [UserController::class, 'index']);
    Route::get('/{id}', [UserController::class, 'show']);
    Route::post('/', [UserController::class, 'store']);
    Route::put('/{id}', [UserController::class, 'update']);
    Route::delete('/{id}', [UserController::class, 'destroy']);

})->middleware(['auth:sanctum']);

Route::group(['prefix' => 'logs'], function () {

    Route::get('/', [LogController::class, 'index']);
    Route::get('/{id}', [LogController::class, 'show']);
    Route::post('/', [LogController::class, 'store']);
    Route::put('/{id}', [LogController::class, 'update']);
    Route::delete('/{id}', [LogController::class, 'destroy']);

})->middleware(['auth:sanctum']);

Route::group(['prefix' => 'products'], function () {

    Route::get('/', [ProductController::class, 'index']);
    Route::get('/{id}', [ProductController::class, 'show']);
    Route::post('/', [ProductController::class, 'store']);
    Route::put('/{id}', [ProductController::class, 'update']);
    Route::delete('/{id}', [ProductController::class, 'destroy']);

})->middleware(['auth:sanctum']);

Route::group(['prefix' => 'categories'], function () {

    Route::get('/', [CategoryController::class, 'index']);
    Route::get('/{id}', [CategoryController::class, 'show']);
    Route::post('/', [CategoryController::class, 'store']);
    Route::put('/{id}', [CategoryController::class, 'update']);
    Route::delete('/{id}', [CategoryController::class, 'destroy']);

})->middleware(['auth:sanctum']);
