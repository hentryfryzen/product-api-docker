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


    public function adjustProductPrices(Request $request)
    {
        try {
            $request->validate([
                'adjustmentPercentage' => 'required|numeric',
                'file' => 'required|file|mimes:json',
            ]);

            $adjustmentPercentage = (float) $request->input('adjustmentPercentage');

            // Read JSON file
            $fileContent = file_get_contents($request->file('file')->path());
            $products = json_decode($fileContent, true);

            // Validate JSON format
            if (json_last_error() !== JSON_ERROR_NONE) {
                return ResponseHelper::error('Invalid JSON format: ' . json_last_error_msg(), 400);
            }

            \DB::beginTransaction(); // Start transaction

            $updatedProducts = [];

            foreach ($products as $product) {
                // Check for required fields
                if (!isset($product['id'], $product['price'], $product['name'], $product['link'], $product['image_link'], $product['currency'])) {
                    throw new \Exception("Missing required fields in product data.");
                }

                // Apply price adjustment
                $newPrice = $product['price'] * (1 + $adjustmentPercentage / 100);

                // Insert or update the product in the database
                $updatedProduct = Product::updateOrCreate(
                    ['id' => $product['id']],
                    [
                        'name' => $product['name'],
                        'link' => $product['link'],
                        'image_link' => $product['image_link'],
                        'price' => $newPrice,
                        'currency' => $product['currency'],
                    ]
                );

                $updatedProducts[] = $updatedProduct;
            }

            \DB::commit(); // Commit transaction

            return ResponseHelper::success('Products successfully updated with new prices.', $updatedProducts, 200);
        } catch (\Exception $e) {
            \Log::error('Error updating product prices: ' . $e->getMessage());
            \DB::rollBack(); // Rollback transaction on error
            return ResponseHelper::error('Error updating products: ' . $e->getMessage(), 500);
        }

    }
}
