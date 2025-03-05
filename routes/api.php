<?php


use App\Models\Product;
use App\Http\Controllers\ProductController;

Route::get('/products', function () {
    return Product::all();
});

Route::post('products/import', [ProductController::class, 'import']);
