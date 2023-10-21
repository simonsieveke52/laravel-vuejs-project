<?php

namespace App\Imports;

use App\Product;
use App\ProductImage;
use Illuminate\Support\Arr;
use Illuminate\Support\Str;
use Illuminate\Support\Collection;
use Maatwebsite\Excel\Concerns\Importable;
use Maatwebsite\Excel\Concerns\ToCollection;

class ProductImport implements ToCollection
{
    use Importable;

    /**
    * @param Collection $collection
    */
    public function collection(Collection $collection)
    {
        $collection->each(function ($row, $index) {
            if ($index === 0) {
                return true;
            }

            $slug = Str::slug($row[1]);

            $nameArray = explode(' ', trim($row[1]));
            $optionName = collect(Arr::wrap($nameArray))->take(-2)->first();

            if ((int) $optionName === 0) {
                $optionName = '';
            }

            $product = Product::create([
                'name' => $row[1],
                'slug' => $slug,
                'sku' => $row[13],
                'mpn' => $row[13],
                'status' => true,
                'weight' => 3,
                'width' => 8,
                'height' => 8,
                'length' => 8,
                'is_free_shipping' => false,
                'quantity' => rand(1, 100),
                'availability_id' => 1,
                'price' => $row[5] != null ? $row[5] : 29.99,
                'searchable_text' =>  strip_tags($row[1]). ' ' . trim(strip_tags($row[2])),
                'description' => $row[2],
                'short_description' => substr($row[2], 0, 150),
                'ingredients' => $row[19],
                'how_to_use' => $row[20],
                'main_image' => '/products/' . trim($row[11]),
                'option_name' => $optionName,
            ]);
        });

        Product::fixTree();

        Product::all()->each(function ($product) {
            $childrens = Product::where('slug', 'like', substr($product->slug, 0, -10).'%')->orderBy('price', 'asc')->get();

            foreach ($childrens as $node) {
                try {
                    $node->parent()->associate($product)->save();
                } catch (\Exception $e) {
                }
            }
        });
    }
}
