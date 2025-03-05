<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Product;
use Illuminate\Support\Facades\Storage;

class ProductController extends Controller
{
    public function import(Request $request)
    {
        try {
            // Validate file
            $request->validate([
                'products_file' => 'required|mimes:json|max:2048'
            ]);

            // Get the uploaded file
            $file = $request->file('products_file');

            // Read the file content
            $jsonData = file_get_contents($file);

            // Decode the JSON data into an array
            $products = json_decode($jsonData, true);

            // Process each product and insert into the database
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

            return response()->json(['message' => 'Products imported successfully.'], 200);
        } catch (\Exception $e) {
            return response()->json(['error' => 'Failed to import products: ' . $e->getMessage()], 500);
        }
    }
}
