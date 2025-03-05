<?php

namespace App\Console\Commands;

use Illuminate\Support\Facades\Http;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Storage;

class ImportProducts extends Command
{
    // Command signature
    protected $signature = 'import:adjust-prices {adjustmentPercentage} {file=products.json}';

    // Command description
    protected $description = 'Import products from a JSON file via API, apply a price adjustment, and update the database.';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        try {
            $file = $this->argument('file'); // Default file: products.json
            $adjustmentPercentage = (float) $this->argument('adjustmentPercentage'); // Price adjustment

            // Check if file exists
            if (!file_exists($file)) {
                $this->error("The file {$file} does not exist.");
                return;
            }

            // Read file in chunks to avoid memory issues
            $fileContent = file_get_contents($file);
            if (!$fileContent) {
                $this->error("Failed to read file {$file}.");
                return;
            }

            // Validate JSON format
            $products = json_decode($fileContent, true);
            if (json_last_error() !== JSON_ERROR_NONE) {
                $this->error('Invalid JSON format in the file.');
                return;
            }

            $fileName = basename($file);
            $tempPath = storage_path("app/{$fileName}");

            // Store file temporarily
            file_put_contents($tempPath, $fileContent);

            // API request in chunks (if needed)
            $chunks = array_chunk($products, 50); // Process 50 records per batch

            foreach ($chunks as $index => $batch) {
                $this->info("Processing batch " . ($index + 1) . " of " . count($chunks));

                $response = Http::attach(
                    'file', file_get_contents($tempPath), $fileName
                )->post(env('APP_URL') . '/products/adjust-prices', [
                    'adjustmentPercentage' => $adjustmentPercentage,
                ]);

                // Handle API response
                if ($response->successful()) {
                    $this->info("Batch " . ($index + 1) . " processed successfully.");
                } else {
                    $responseData = $response->json() ?? [];
                    $errorMessage = $responseData['message'] ?? 'Something went wrong.';
                    $this->error("Batch " . ($index + 1) . " failed: " . $errorMessage);
                }
            }

            // Delete temporary file
            unlink($tempPath);

            $this->info('All batches processed successfully.');

        } catch (\Exception $e) {
            // Catch any exceptions and log the error message
            $this->error('An error occurred: ' . $e->getMessage());
        }
    }
}
