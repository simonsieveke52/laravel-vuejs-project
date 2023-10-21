<?php

use App\Imports\ProductDescriptionImport;
use App\Imports\ProductsMainImport;
use Illuminate\Database\Seeder;

class ProductsMainTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        (new ProductsMainImport)->import(
            storage_path('app/public/imports/AITproduct_description.csv'),
            null,
            \Maatwebsite\Excel\Excel::CSV
        );
        
        (new ProductsMainImport)->import(
            storage_path('app/public/imports/AITproduct.csv'),
            null,
            \Maatwebsite\Excel\Excel::CSV
        );
    }
}
