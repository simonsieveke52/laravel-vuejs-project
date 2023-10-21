<?php

use App\Order;
use App\Product;
use App\Category;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Artisan;

class FakeDataSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        try {
            Category::fixTree();
            Artisan::call('responsecache:clear');
            Artisan::call('cache:clear');
            Category::flushCache();
            Product::flushCache();
        } catch (\Exception $e) {
        }

        $categories = factory(App\Category::class, 5)
            ->create([
                'parent_id' => null,
                'on_navbar' => true
            ]);

        $categories->each(function ($category) {
            factory(App\Category::class, mt_rand(5, 10))->create()
                ->each(function ($child) use ($category) {
                    try {
                        $category->appendNode($child);
                    } catch (\Exception $e) {
                        dump($e->getMessage());
                    }
                })
                ->each(function ($child) {
                    factory(App\Category::class, mt_rand(3, 11))->create()
                        ->each(function ($subChild) use ($child) {
                            if ($child->id !== $subChild->id) {
                                $child->appendNode($subChild);
                            }
                        });
                });
        });

        factory(App\Product::class, 300)->create()->each(function ($product) {
            $product->categories()->save(
                Category::inRandomOrder()->first()
            );

            if (mt_rand(0, 3) < 1) {
                $product->children()->saveMany(
                    factory(App\Product::class, mt_rand(2, 10))->create()
                );
            }
        });

        Product::take(50)->get()->each(function ($product) {
            $product->categories()->save(
                Category::whereNull('parent_id')->inRandomOrder()->first()
            );
        });

        factory(Order::class, 30)->create();
    }
}
