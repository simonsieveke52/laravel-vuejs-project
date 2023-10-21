<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateTrackingNumbersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('tracking_numbers', function (Blueprint $table) {
            $table->id();

            $table->unsignedBigInteger('order_id')->nullable()->index();

            $table->string('carrier_code')->nullable();
            $table->string('carrier_name')->nullable();

            $table->string('shipment_id')->nullable();
            $table->string('number')->index()->nullable();
            
            $table->string('file_name')->nullable();
            $table->text('file_path')->nullable();

            $table->decimal('shipment_cost', 10, 2)->nullable()->default(0);
            $table->decimal('insurance_cost', 10, 2)->nullable()->default(0);
            
            $table->boolean('status')->nullable()->default(true);

            $table->longText('details')->nullable();
            
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
        Schema::dropIfExists('tracking_numbers');
    }
}
