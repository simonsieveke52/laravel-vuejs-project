<?php

use App\Imports\ProductBrandsImport;
use App\Imports\BrandImport;
use Illuminate\Database\Seeder;

class ProductBrandsTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        (new ProductBrandsImport)->import(
            storage_path('app/public/imports/AITBrand.csv'),
            null,
            \Maatwebsite\Excel\Excel::CSV
        );
    }
}
