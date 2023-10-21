<?php

use App\Category;
use App\Product;
use App\Imports\ProductRelationImport;
use Illuminate\Database\Seeder;

class CategoryProductsTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        (new ProductRelationImport)->import(
            storage_path('app/public/imports/AITproduct_to_category.csv'),
            null,
            \Maatwebsite\Excel\Excel::CSV
        );
    }
}
