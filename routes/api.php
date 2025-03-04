<?php


use App\Models\Product;

Route::get('/products', function () {
    return Product::all();
});
