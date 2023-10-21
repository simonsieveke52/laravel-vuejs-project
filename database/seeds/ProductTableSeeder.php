<?php

use App\Imports\ProductImport;
use Illuminate\Database\Seeder;

class ProductTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        (new ProductImport)->import(
            storage_path('app/public/imports/Products.csv'),
            null,
            \Maatwebsite\Excel\Excel::CSV
        );
    }
}
