<?php

namespace App\Repositories;

use App\User;
use App\Product;
use App\ProductImage;
use Illuminate\Http\Request;

class ProductRepository
{
    /**
     * Get product with details
     *
     * @param  mixed|Product $product
     *
     * @return  Product
     *
     * @throws \Exception
     */
    public function getProductWithDetails($product)
    {
        if (! $product instanceof Product) {
            try {
                $product = Product::where('slug', $product)->firstOrFail();
            } catch (\Exception $exception) {
                $product = Product::where('id', $product)->firstOrFail();
            }
        }

        $product->loadMissing(['images', 'categories', 'children', 'brand', 'availability']);

        return $product;
    }

    /**
     * Update products from ftp file
     *
     * @param Product $product
     * @param array $row
     *
     * @return bool
     */
    public function updateProductFromBoh(Product $product, array $row) : bool
    {
        // example of updating product via file
        if (isset($product->price) && isset($row['price'])) {
            $product->price = $row['price'];
        }

        return $product->save();
    }
}
