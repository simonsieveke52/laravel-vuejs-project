<?php

use App\Imports\TaxImport;
use Maatwebsite\Excel\Excel;
use Illuminate\Database\Seeder;

class TaxSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        (new TaxImport)->import(
            storage_path('app/public/imports/zip_tax.csv'),
            null,
            Excel::CSV
        );
    }
}
