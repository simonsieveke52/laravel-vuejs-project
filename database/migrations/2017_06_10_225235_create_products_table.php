<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateProductsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('products', function (Blueprint $table) {
            $table->id();

            $table->string('name')->nullable();
            $table->string('option_name')->nullable();
            $table->string('slug')->index();
            $table->boolean('status')->nullable()->default(true);

            $table->string('item_id')->index()->nullable();
            $table->string('sku')->index()->nullable();
            $table->string('mpn')->index()->nullable();
            $table->string('upc')->index()->nullable();

            $table->string('main_image', 255)->nullable();
            
            $table->unsignedInteger('availability_id')->nullable()->index();
            $table->unsignedBigInteger('brand_id')->nullable()->index();
            $table->unsignedBigInteger('manufacture_id')->nullable()->index();
            $table->unsignedBigInteger('country_id')->nullable()->index();

            $table->decimal('cost', 12, 6)->default(0)->nullable();
            $table->decimal('price', 12, 6)->default(0)->nullable();
            $table->decimal('original_price', 16, 2)->nullable()->default(0);
            $table->decimal('map_price', 16, 2)->nullable()->default(0);

            $table->boolean('is_on_feed')->default(true)->nullable();
            $table->boolean('is_on_home')->default(false)->nullable();
            $table->boolean('is_map_enabled')->default(false)->nullable();
            $table->unsignedInteger('homepage_order')->default(1)->nullable();

            $table->boolean('is_free_shipping')->default(false)->nullable();
            $table->string('free_shipping_option')->nullable();
            
            $table->integer('quantity')->default(0)->nullable();
            $table->string('quantity_per_case')->nullable();

            $table->text('searchable_text')->nullable();
            $table->text('short_description')->nullable();

            $table->text('ingredients')->nullable();
            $table->text('how_to_use')->nullable();

            $table->longText('description')->nullable();

            $table->string('weight_uom')->default('lbs')->nullable();
            $table->string('length_uom')->default('in')->nullable();
            $table->string('height_uom')->default('in')->nullable();
            $table->string('width_uom')->default('in')->nullable();

            $table->decimal('weight', 10, 2)->default(0)->nullable();
            $table->decimal('width', 10, 2)->default(0)->nullable();
            $table->decimal('height', 10, 2)->default(0)->nullable();
            $table->decimal('length', 10, 2)->default(0)->nullable();

            // helper columns
            $table->unsignedBigInteger('review_count')->default(0)->nullable();
            $table->decimal('review_avg', 4, 2)->default(0)->nullable();
            $table->unsignedBigInteger('clicks_counter')->default(0)->nullable();
            $table->unsignedBigInteger('sales_count')->default(0)->nullable();

            // product parent/childs
            $table->unsignedBigInteger('_lft')->default(0)->nullable();
            $table->unsignedBigInteger('_rgt')->default(0)->nullable();
            $table->unsignedBigInteger('parent_id')->index()->nullable();
            $table->index(['_lft', '_rgt', 'parent_id']);

            $table->timestamps();
            $table->softDeletes();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('products');
    }
}
