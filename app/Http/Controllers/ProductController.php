<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Product;
use App\Helpers\ResponseHelper;

class ProductController extends Controller
{
    // Method to fetch all products
    public function index()
    {
        try {
            $products = Product::orderBy('id', 'desc')->get();
            return ResponseHelper::success('Products fetched successfully.', $products, 200);
        } catch (\Exception $e) {
            return ResponseHelper::error('Failed to fetch products: ' . $e->getMessage(), 500);
        }
    }

    // Method to fetch a single product by ID
    public function show($id)
    {
        try {
            $product = Product::find($id);

            if (!$product) {
                return ResponseHelper::error('Product not found', 404);
            }

            return ResponseHelper::success('Product fetched successfully.', $product, 200);
        } catch (\Exception $e) {
            return ResponseHelper::error('Failed to fetch product: ' . $e->getMessage(), 500);
        }
    }

    // Method to import products
    public function import(Request $request)
    {
        try {
            // Validate the uploaded file
            $request->validate([
                'products_file' => 'required|mimes:json|max:2048'
            ]);

            // Get and read the file
            $file = $request->file('products_file');
            $jsonData = file_get_contents($file);
            $products = json_decode($jsonData, true);

            // Insert or update products
            foreach ($products as $product) {
                Product::updateOrCreate(
                    ['id' => $product['id']],
                    [
                        'name' => $product['name'],
                        'link' => $product['link'],
                        'image_link' => $product['image_link'],
                        'price' => $product['price'],
                        'currency' => $product['currency']
                    ]
                );
            }

            return ResponseHelper::success('Products imported successfully.', $products, 200);
        } catch (\Exception $e) {
            return ResponseHelper::error('Failed to import products: ' . $e->getMessage(), 500);
        }
    }
}
