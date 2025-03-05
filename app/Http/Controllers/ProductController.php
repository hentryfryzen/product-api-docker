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
        // Validate request
        $request->validate([
            'adjustmentPercentage' => 'required|numeric',
            'file' => 'required|file|mimes:json',
        ]);

        $adjustmentPercentage = (float) $request->input('adjustmentPercentage'); // Price adjustment (e.g., 10 for +10%)

        // Read JSON file
        $fileContent = file_get_contents($request->file('file')->path());
        $products = json_decode($fileContent, true);

        // Check for JSON errors
        if (json_last_error() !== JSON_ERROR_NONE) {
            return response()->json(['message' => 'Invalid JSON format in the provided file.'], 400);
        }

        \DB::beginTransaction(); // Start transaction

        try {
            foreach ($products as $product) {
                // Apply price adjustment
                $newPrice = $product['price'] * (1 + $adjustmentPercentage / 100);

                // Insert or update the product in the database
                Product::updateOrCreate(
                    ['id' => $product['id']], // Assuming 'id' exists in the JSON data
                    [
                        'name' => $product['name'],
                        'link' => $product['link'],
                        'image_link' => $product['image_link'],
                        'price' => $newPrice,
                        'currency' => $product['currency'],
                    ]
                );
            }

            \DB::commit(); // Commit transaction
            return response()->json([
                'message' => 'Products successfully updated with new prices.',
                'products' => Product::whereIn('id', array_column($products, 'id'))->get()
            ], 200);

        } catch (\Exception $e) {
            \DB::rollBack(); // Rollback transaction on error
            return response()->json(['message' => 'Error updating products: ' . $e->getMessage()], 500);
        }
    }
}
