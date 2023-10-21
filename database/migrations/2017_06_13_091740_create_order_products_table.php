<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateOrderProductsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('order_product', function (Blueprint $table) {
            $table->id();
            $table->unsignedInteger('quantity')->default(0);
            $table->decimal('price', 12, 6)->default(0);
            $table->decimal('total', 12, 6)->default(0);
            $table->boolean('is_subscription')->default(false)->nullable()->index();
            $table->string('subscription_id')->nullable();
            $table->boolean('is_active_subscription')->default(false)->nullable()->index();
            $table->timestamp('canceled_at')->nullable();

            $table->unsignedBigInteger('order_id')->nullable()->index();
            $table->unsignedBigInteger('product_id')->nullable()->index();
            $table->text('options')->nullable();
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
        Schema::dropIfExists('order_product');
    }
}
