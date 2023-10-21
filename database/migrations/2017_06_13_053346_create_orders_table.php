<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateOrdersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('orders', function (Blueprint $table) {
            $table->id();
            $table->string('name')->nullable();
            $table->string('email')->nullable();
            $table->string('phone')->nullable();
            $table->string('payment_method')->nullable();

            $table->text('cc_number')->nullable();
            $table->string('cc_name')->nullable();
            $table->string('cc_expiration')->nullable();
            $table->string('cc_expiration_month')->nullable();
            $table->string('cc_expiration_year')->nullable();
            $table->string('cc_cvv')->nullable();
            $table->string('card_type')->nullable();

            $table->text('gclid')->nullable();

            $table->decimal('tax_rate', 12, 6)->default(0.00);
            $table->decimal('tax', 12, 6)->default(0.00);
            $table->decimal('shipping_cost', 12, 6)->default(0.00);
            $table->decimal('subtotal', 12, 6)->default(0.00);
            $table->decimal('total', 12, 6)->default(0.00);

            $table->unsignedBigInteger('discount_id')->nullable()->index();
            $table->unsignedInteger('order_status_id')->nullable()->index();
            $table->unsignedInteger('shipping_id')->nullable()->index();
            $table->unsignedBigInteger('customer_id')->nullable()->index();

            $table->boolean('confirmed')->default(false);
            $table->timestamp('confirmed_at')->nullable();

            $table->boolean('refunded')->default(false)->nullable();
            $table->timestamp('refunded_at')->nullable();

            $table->boolean('reported')->default(false)->nullable();
            $table->timestamp('reported_at')->nullable();

            $table->boolean('mailed')->nullable();
            $table->timestamp('mailed_at')->nullable();
            
            $table->string('transaction_id')->nullable()->index();
            $table->text('transaction_response')->nullable();
            
            $table->softDeletes();
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
        Schema::dropIfExists('orders');
    }
}
