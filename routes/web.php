<?php

use Illuminate\Support\Facades\Route;
use App\Models\Product;
use App\Http\Controllers\ProductController;

// Route to get all products
Route::get('/products', function () {
    return Product::all();
});

// Route to import products
Route::post('products/import', [ProductController::class, 'import']);

// Root route to show welcome page
Route::get('/', function () {
    return view('welcome');
});
