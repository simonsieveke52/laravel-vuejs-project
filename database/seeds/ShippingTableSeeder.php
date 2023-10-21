<?php

use App\Shipping;
use Illuminate\Database\Seeder;

class ShippingTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        Shipping::create([
            'name' => 'standard shipping',
            'description' => 'Lorem ipsum dolor sit amet, consectetur adipisicing elit. Voluptates, perferendis.',
            'base_cost' => 5.99,
        ]);
        
        Shipping::create([
            'name' => 'premium shipping',
            'description' => 'Lorem ipsum dolor sit amet, consectetur adipisicing elit. Voluptates, perferendis.',
            'base_cost' => 29.99,
        ]);
    }
}
