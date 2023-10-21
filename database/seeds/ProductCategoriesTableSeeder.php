<?php

use App\Imports\ProductCategoriesImport;
use App\Imports\CategoryDescriptionImport;
use Illuminate\Database\Seeder;

class ProductCategoriesTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        (new ProductCategoriesImport)->import(
            storage_path('app/public/imports/AITcategory_description.csv'),
            null,
            \Maatwebsite\Excel\Excel::CSV
        );
        
        (new ProductCategoriesImport)->import(
            storage_path('app/public/imports/AITcategory.csv'),
            null,
            \Maatwebsite\Excel\Excel::CSV
        );
    }
}
