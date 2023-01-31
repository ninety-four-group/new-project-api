<?php

use App\Http\Controllers\Admin\AuthController;
use App\Http\Controllers\Admin\BrandController;
use App\Http\Controllers\Admin\CategoryController;
use App\Http\Controllers\Admin\WarehouseController;
use App\Http\Controllers\Admin\ProductController;
use App\Http\Controllers\Admin\TagController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

Route::post('login', [AuthController::class,'login']);
Route::post('register', [AuthController::class,'register']);

Route::middleware(['auth:sanctum', 'abilities:admin'])->group(function () {
    Route::get('me', [AuthController::class,'getMe']);
    Route::post('logout', [AuthController::class,'logout']);

    Route::resource('categories', CategoryController::class);
    Route::resource('brands', BrandController::class);
    Route::resource('warehouses', WarehouseController::class);
    Route::resource('products', ProductController::class);
    Route::resource('tags', TagController::class);
});
