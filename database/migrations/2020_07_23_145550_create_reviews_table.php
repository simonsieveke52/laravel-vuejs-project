<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateReviewsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('reviews', function (Blueprint $table) {
            $table->id();
            $table->unsignedInteger('product_id')->nullable()->index();
            $table->string('name');
            $table->string('email')->nullable();
            $table->string('title')->nullable();
            $table->text('description')->nullable();
            $table->integer('grade')->nullable();

            $table->unsignedBigInteger('like_counter')->default(0);
            $table->unsignedBigInteger('dislike_counter')->default(0);
            $table->unsignedBigInteger('report_counter')->default(0);

            $table->boolean('status')->nullable()->default(true);
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
        Schema::dropIfExists('reviews');
    }
}
