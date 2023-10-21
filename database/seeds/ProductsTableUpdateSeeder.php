<?php

use Illuminate\Database\Seeder;
use App\Imports\ProductUpdaterImport;

class ProductsTableUpdateSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        (new ProductUpdaterImport)->import(
            storage_path('app/public/imports/_AIT - Product Export_Import - AW-2020-05-13 11_05_19AM.csv'),
            null,
            \Maatwebsite\Excel\Excel::CSV
        );
    }
}
