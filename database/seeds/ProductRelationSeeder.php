<?php

use App\Product;
use App\Category;
use Illuminate\Database\Seeder;
use App\Imports\ProductRelationImport;

class ProductRelationSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        (new ProductRelationImport)->import(
            storage_path('app/public/imports/Products.csv'),
            null,
            \Maatwebsite\Excel\Excel::CSV
        );
    }
}
