<?php

namespace App\Jobs;

use App\Models\Product;
use Illuminate\Support\Str;
use Illuminate\Bus\Queueable;
use App\Imports\ProductImport;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Storage;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Contracts\Queue\ShouldBeUnique;

class ProductJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    protected $category;
    protected $filename;
    
    public function __construct($category, $filename)
    {
        $this->category = $category;
        $this->filename = $filename;
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
        $files = (new ProductImport)->toArray(storage_path('app/public/uploads/' . $this->filename));
        
        foreach ($files[0] as $row) {
            $explodeURL = explode('/', $row[5]);
            $explodeExtension = explode('.', end($explodeURL));
            $filename = time() . Str::random(6) . '.' . end($explodeExtension);

            file_put_contents(storage_path('app/public/products') . '/' . $filename, file_get_contents($row[5]));

            Product::create([
                'name' => $row[0],
                'slug' => $row[0],
                'category_id' => $this->category,
                'description' => $row[1],
                'price' => $row[2],
                'weight' => $row[3],
                'stock' => $row[4],
                'image' => $filename,
                'status' => true
            ]);

        }

        // File::delete(storage_path('app/public/uploads/' . $this->filename));
        Storage::delete('app/public/uploads/' . $this->filename);
    }
}
