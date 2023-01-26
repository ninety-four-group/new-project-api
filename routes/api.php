<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\DIYController;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\BrandController;
use App\Http\Controllers\CartController;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\ReviewController;
use App\Http\Controllers\ProductController;
use App\Http\Controllers\CategoryController;
use App\Http\Controllers\CollectionController;
use App\Http\Controllers\CouponController;
use App\Http\Controllers\GeneralController;
use App\Http\Controllers\InstallationController;
use App\Http\Controllers\OrderController;
use App\Http\Controllers\ProjectCalculatorController;
use App\Http\Controllers\TagController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\WarehouseController;
use App\Http\Controllers\WishlistController;

Route::post('login', [AuthController::class,'login']);
Route::post('register', [AuthController::class,'register']);

Route::get('home', [HomeController::class,'getHomeData']);


Route::get('countries',[GeneralController::class,'getCountries']);
Route::get('regions/{country_id}',[GeneralController::class,'getRegions']);
Route::get('cities/{region_id}',[GeneralController::class,'getCities']);
Route::get('townships/{city_id}',[GeneralController::class,'getTownships']);

Route::get('collections',[CollectionController::class, 'getCollections']);
Route::get('tags',[TagController::class, 'getTags']);

Route::get('categories', [CategoryController::class,'getCategories']);
Route::get('brands', [BrandController::class,'getBrands']);
Route::get('warehouses', [WarehouseController::class,'getWarehouses']);

Route::get('products', [ProductController::class,'getProductList']);








Route::get('products/{id}', [ProductController::class,'getProductDetail']);
Route::post('reviews', [ReviewController::class,'giveReview']);


Route::get('wishlists', [WishlistController::class,'getWishlist']);
Route::post('wishlists', [WishlistController::class,'addToWishlist']);
Route::delete('wishlists', [WishlistController::class,'deleteWishlist']);


Route::get('carts', [CartController::class,'getCart']);
Route::post('carts', [CartController::class,'addToCart']);
Route::put('carts/{id}', [CartController::class,'updateCart']);

Route::post('coupon-apply', [CouponController::class,'applyCoupon']);

Route::post('orders/cancel', [OrderController::class,'cancelOrder']);
Route::post('orders', [OrderController::class,'saveOrder']);
Route::get('orders', [OrderController::class,'getOrders']);
Route::get('orders/{id}', [OrderController::class,'getOrderDetail']);


Route::post('installations/rating', [InstallationController::class,'saveRating']);
Route::get('installations/contact', [InstallationController::class,'getContact']);
Route::post('installations/request-service', [InstallationController::class,'saveRequestService']);
Route::post('installations/provider-request', [InstallationController::class,'requestProvider']);

Route::get('installations/categories', [InstallationController::class,'getCategories']);
Route::get('installations/categories', [InstallationController::class,'getCategories']);
Route::get('installations', [InstallationController::class,'getInstallation']);
Route::get('installations/{id}', [InstallationController::class,'getInstallationDetail']);

Route::get('diy/categories', [DIYController::class,'getCategories']);
Route::get('diy', [DIYController::class,'getDIY']);
Route::get('diy/{id}', [DIYController::class,'getDIYDetail']);


Route::get('project-calculator/category',[ProjectCalculatorController::class , 'getCategories']);
Route::get('project-calculator/type',[ProjectCalculatorController::class , 'getProductType']);
Route::post('project-calculator/calculate',[ProjectCalculatorController::class , 'calculateProject']);

Route::middleware(['auth:sanctum', 'abilities:user'])->group(function () {
    Route::get('me', [AuthController::class,'getMe']);
    Route::post('logout', [AuthController::class,'logout']);

    Route::get('profile',[UserController::class,'getProfile']);
    Route::put('profile',[UserController::class,'updateProfile']);

});
