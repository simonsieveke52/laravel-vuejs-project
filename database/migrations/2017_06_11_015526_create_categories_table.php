<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateCategoriesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('categories', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('slug')->index();
            $table->string('cover')->nullable();
            $table->string('dropdown_image')->nullable();
            $table->text('description')->nullable();
            $table->text('marketing_description')->nullable();
            $table->boolean('status')->nullable()->default(true);
            $table->boolean('on_navbar')->default(true);
            $table->boolean('on_filter')->default(true);
            $table->boolean('on_home')->default(false);
            $table->unsignedInteger('sort_order')->nullable()->default(0);
            $table->unsignedBigInteger('_lft')->default(0);
            $table->unsignedBigInteger('_rgt')->default(0);
            $table->unsignedBigInteger('parent_id')->nullable();
            $table->index(['_lft', '_rgt', 'parent_id']);
            $table->timestamps();
            $table->softDeletes();
        });

        Schema::create('category_product', function (Blueprint $table) {
            $table->unsignedBigInteger('category_id')->nullable()->index();
            $table->unsignedBigInteger('product_id')->nullable()->index();
            $table->foreign('category_id')->references('id')->on('categories');
            $table->foreign('product_id')->references('id')->on('products');
            $table->unique(['product_id', 'category_id']);
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('category_product');

        Schema::dropIfExists('categories');
    }
}
