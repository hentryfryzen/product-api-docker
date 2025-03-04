<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\Product;
use Illuminate\Support\Facades\File;

class ImportProducts extends Command
{
    protected $signature = 'import:products {file} {percentage}';
    protected $description = 'Import products from a JSON file and adjust prices by the given percentage';

    public function handle()
    {
        $file = $this->argument('file');
        $percentage = $this->argument('percentage');

        if (!File::exists($file)) {
            $this->error('File does not exist!');
            return;
        }

        $productsData = json_decode(File::get($file), true);

        foreach ($productsData as $data) {
            $price = $data['price'] + ($data['price'] * ($percentage / 100));
            Product::create([
                'name' => $data['name'],
                'link' => $data['link'],
                'image_link' => $data['image_link'],
                'price' => $price,
                'currency' => $data['currency'],
            ]);
        }

        $this->info('Products imported and prices updated!');
    }
}
