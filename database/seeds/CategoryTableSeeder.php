<?php

use App\Category;
use App\Imports\CategoryImport;
use Illuminate\Database\Seeder;
use App\Imports\CategoryDescriptionImport;

class CategoryTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        (new CategoryImport)->import(
            storage_path('app/public/imports/Categories.csv'),
            null,
            \Maatwebsite\Excel\Excel::CSV
        );

        Category::whereNull('parent_id')
            ->orWhere('parent_id', '')
            ->update(['on_home' => true]);
    }
}
