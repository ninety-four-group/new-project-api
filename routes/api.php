<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\DIYController;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\CartController;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\ReviewController;
use App\Http\Controllers\ProductController;
use App\Http\Controllers\CategoryController;
use App\Http\Controllers\CouponController;
use App\Http\Controllers\InstallationController;
use App\Http\Controllers\OrderController;
use App\Http\Controllers\WishlistController;

Route::post('login', [AuthController::class,'login']);
Route::post('register', [AuthController::class,'register']);

Route::get('home', [HomeController::class,'getHomeData']);

Route::get('categories', [CategoryController::class,'getCategories']);
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


Route::middleware(['auth:sanctum', 'abilities:user'])->group(function () {
    Route::get('me', [AuthController::class,'getMe']);
    Route::post('logout', [AuthController::class,'logout']);
});
