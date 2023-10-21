<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateZipcodesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('zipcodes', function (Blueprint $table) {
            $table->id();
            $table->string('name')->index();
            $table->decimal('tax_rate', 10, 4)->default(0);
            $table->boolean('status')->nullable()->default(true);
            $table->unsignedBigInteger('state_id')->nullable()->index();
            $table->unsignedBigInteger('city_id')->nullable()->index();
            $table->unsignedBigInteger('country_id')->nullable()->index();
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
        Schema::dropIfExists('zipcodes');
    }
}
