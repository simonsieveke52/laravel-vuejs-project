<?php

namespace FME\EloquenceCsvFeed\Handlers;

use App\Product;
use \FME\EloquenceCsvFeed\Helper;
use \FME\EloquenceCsvFeed\Base\EloquenceCsvFeedHandler;

class ProductHandler extends EloquenceCsvFeedHandler
{
    /**
     * @param $model model name
     * @param $startAt limit from
     * @param $endAt limit ends
     */
    public function __construct($model = null)
    {
        $this->model = $model;
    }

    /**
     * Set feed to feed attribute
     *
     * @return [type] [description]
     */
    public function handle() : void
    {
        $data = [];
        $fh = fopen('php://output', 'w');

        $query = Product::with(['images','brand', 'categories'])
            ->where('is_on_feed', true)
            ->where('price', '>', 0);

        if ($this->hasOffset()) {
            $query = $query->offset((int)$this->offset);
        }

        if ($this->hasLimit()) {
            $query = $query->limit((int)$this->limit);
        }

        if ($this->hasOffset() && !$this->hasLimit()) {
            $limit = Product::count();
            $query = $query->limit($limit);
        }

        $query->chunk(150, function ($products) use (&$data) {
            foreach ($products as $product) {
                if (strpos($product->main_image, config('default-variables.default-image')) !== false) {
                    continue;
                }

                if ($product->categories->isEmpty()) {
                    continue;
                }

                $data[] = $this->transform($product);
            }
        });

        $this->setFeed($data);
    }

    /**
     * transform product to array
     *
     * @param product
     * @return array
     */
    public function transform($product) : array
    {
        $categories = $product->parentCategories->pluck('name')->toArray();

        $additionalImage = $product->images->isEmpty() ? '' : asset("storage/" . $product->images->first()->src);

        $description = $product->description_text;

        if (trim($description) === '') {
            $description = ucfirst($product->name);
        }

        $price = $product->selling_price;

        return [

            'id' 			=> $product->id,

            'title' 		=> ucfirst($product->name),

            'description' 	=> $description,

            'link' 			=> route('product.show', $product->slug),

            'condition'		=> 'new',

            'price'    		=> number_format($price, 2, '.', '') . ' USD',

            'availability'  => 'in stock',
            
            'product_type'  => $product->availability->name,

            'image_link'    => asset($product->main_image),

            'gtin'			=> $product->upc,

            'mpn'			=> $product->mpn,

            'brand'			=> html_entity_decode($product->brand->name),

            'google_product_category' => 'Business & Industrial',

            'shipping_weight' => $product->weight,

            'Custom_label_0' => isset($categories[0]) ? $categories[0] : '',

            'Custom_label_1' => isset($categories[1]) ? $categories[1] : '',

            'Custom_label_2' => isset($categories[2]) ? $categories[2] : '',

            'Custom_label_3' => isset($categories[3]) ? $categories[3] : '',

            'Custom_label_4' => isset($categories[4]) ? $categories[4] : '',

            'min_handling_time' => '4',

            'max_handling_time' => '',

            'shipping_label' => (int) $product->is_free_shipping === 1 ? 'Free' : ''
        ];
    }
}
