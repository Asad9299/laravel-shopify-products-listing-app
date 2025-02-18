<?php

use App\Http\Controllers\API\ProductController;
use App\Http\Controllers\Auth\ShopifyAuthController;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('welcome');
});

# Step 1: Authorize the application
Route::get('/auth', [ShopifyAuthController::class, 'redirectToShopify']);

# Step 2: Handle callback
Route::get('/auth/callback', [ShopifyAuthController::class, 'handleCallback']);

# Step 3: Get the list of products
Route::get('/shopify/products/{shop}', [ProductController::class, 'getProducts']);
