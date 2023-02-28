<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Admin\SKUController;
use App\Http\Controllers\Admin\TagController;
use App\Http\Controllers\Admin\AuthController;
use App\Http\Controllers\Admin\CityController;
use App\Http\Controllers\Admin\BrandController;
use App\Http\Controllers\Admin\MediaController;
use App\Http\Controllers\Admin\StockController;
use App\Http\Controllers\Admin\RegionController;
use App\Http\Controllers\Admin\CountryController;
use App\Http\Controllers\Admin\ProductController;
use App\Http\Controllers\Admin\CategoryController;
use App\Http\Controllers\Admin\TownshipController;
use App\Http\Controllers\Admin\VariationController;
use App\Http\Controllers\Admin\WarehouseController;
use App\Http\Controllers\Admin\CollectionController;
use App\Http\Controllers\Admin\VariationCategoryController;

header('Access-Control-Allow-Origin: *');
//Access-Control-Allow-Origin: *
header('Access-Control-Allow-Methods:  POST, GET, OPTIONS, PUT, DELETE');
header('Access-Control-Allow-Headers:  Content-Type, X-Auth-Token, Origin, Authorization');

Route::post('login', [AuthController::class,'login']);
Route::post('register', [AuthController::class,'register']);

// Route::middleware(['auth:sanctum', 'abilities:admin'])->group(function () {
Route::get('me', [AuthController::class,'getMe']);
Route::post('logout', [AuthController::class,'logout']);

Route::resource('categories', CategoryController::class);
Route::resource('brands', BrandController::class);
Route::resource('warehouses', WarehouseController::class);
Route::resource('products', ProductController::class);
Route::resource('tags', TagController::class);
Route::resource('collections', CollectionController::class);
Route::resource('media', MediaController::class);
Route::resource('variations', VariationController::class);
Route::resource('sku', SKUController::class);
Route::resource('stock', StockController::class);

Route::resource('countries', CountryController::class);
Route::resource('regions', RegionController::class);
Route::resource('cities', CityController::class);
Route::resource('townships', TownshipController::class);

// });
