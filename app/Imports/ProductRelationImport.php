<?php

namespace App\Imports;

use App\Product;
use App\Category;
use Illuminate\Support\Collection;
use Maatwebsite\Excel\Concerns\Importable;
use Maatwebsite\Excel\Concerns\ToCollection;

class ProductRelationImport implements ToCollection
{
    use Importable;

    /**
    * @param Collection $collection
    */
    public function collection(Collection $collection)
    {
        $collection->each(function ($row, $index) {
            if ($index < 2) {
                return true;
            }

            try {
                $product = Product::whereName($row[1])->firstOrFail();
                $category = Category::where('name', $row[17])->firstOrFail();
                $product->categories()->save($category);
            } catch (\Exception $e) {
                dump($e->getMessage());
            }
        });
    }
}
