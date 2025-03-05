<?php

use Illuminate\Support\Facades\Route;
use App\Models\Product;
use App\Http\Controllers\ProductController;

// Route to get all products
Route::get('/products', function () {
    return Product::all();
});

// Route to import products
Route::get('/products', [ProductController::class, 'index']);  // Fetch all products
Route::get('/products/{id}', [ProductController::class, 'show']);
Route::post('products/import', [ProductController::class, 'import']);

Route::fallback(function () {
    return response()->json([
        'statusCode' => 404,
        'message' => 'API endpoint not found.'
    ], 404);
});
