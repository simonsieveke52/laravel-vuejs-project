<?php

use App\Review;
use App\Product;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Artisan;

class DatabaseSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $this->call([
            CountryTableSeeder::class,
            StateTableSeeder::class,
            CityTableSeeder::class,
            ZipcodeTableSeeder::class,
            OrderStatusTableSeeder::class,
            AvailabilitiesTableSeeder::class,
            ShippingTableSeeder::class,
            // // TaxSeeder::class,
            CategoryTableSeeder::class,
            ProductTableSeeder::class,
            ProductRelationSeeder::class,
        ]);

        DB::unprepared(
            "INSERT INTO `settings` (`key`, `display_name`, `value`, `details`, `type`, `order`, `group`) VALUES
            ('subscription.discount', 'Discount', '10', NULL, 'text', 6, 'Subscription'),
            ('subscription.frequency', 'Frequency', '30', NULL, 'text', 7, 'Subscription'),
            ('subscription.discount_label', 'Discount text', '<span class=\"text-dark-3 font-weight-bolder\">Save {discount}%</span> when you subscribe', NULL, 'text', 8, 'Subscription'),
            ('free_shipping.threshold', 'Threshold', '35.00', NULL, 'text', 8, 'Free shipping'),
            ('emails.order_bcc', 'Order Reception', 'orders@naturalhouse.com, annie@naturalhouse.com', NULL, 'text', 9, 'Emails')
        "
        );

        Product::fixTree();
    }
}
