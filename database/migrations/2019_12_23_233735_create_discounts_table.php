<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateDiscountsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('discounts', function (Blueprint $table) {
            $table->id();
            $table->timestamps();
            $table->text('name')->nullable();
            $table->text('description')->nullable();
            $table->string('coupon_code');
            $table->string('discount_type');// ex. reduction of total, free shipping, reduction on specific product, etc.
            $table->decimal('discount_amount', 8, 2);
            $table->string('discount_method', 25); // ex. Percentage off or dollars off
            $table->dateTime('expiration_date')->default(\Carbon\Carbon::now());
            $table->dateTime('activation_date')->default(\Carbon\Carbon::now());
            $table->boolean('is_active')->default(false);
            $table->boolean('is_triggerable')->default(false);
            $table->decimal('trigger_amount')->nullable();
            $table->boolean('collects_email')->default(false);
            $table->boolean('status')->nullable()->default(true);
            $table->unsignedBigInteger('shipping_id')->index();
            $table->softDeletes();
        });

        Schema::create('discount_product', function (Blueprint $table) {
            $table->primary(['product_id', 'discount_id']);
            $table->unsignedBigInteger('product_id')->index();
            $table->unsignedBigInteger('discount_id')->index();
            $table->timestamps();
        });
        
        Schema::create('category_discount', function (Blueprint $table) {
            $table->primary(['category_id', 'discount_id']);
            $table->unsignedBigInteger('category_id')->index();
            $table->unsignedBigInteger('discount_id')->index();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('discounts');
        Schema::dropIfExists('discount_product');
        Schema::dropIfExists('category_discount');
    }
}
