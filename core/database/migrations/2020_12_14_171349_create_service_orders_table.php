<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateServiceOrdersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('service_orders', function (Blueprint $table) {
            $table->engine = 'InnoDB';

            $table->id();
            $table->integer('service_id');

            $table->text('form_data')->nullable();
            $table->text('service_data')->nullable();

            $table->string('full_name',255);
            $table->string('phone',255);
            $table->string('email',255);

            $table->string('status',25);
            $table->string('payment_hash',100)->nullable();

            $table->timestamp('liqpay_payment_date')->nullable();
            $table->text('liqpay_response')->nullable();
            $table->decimal('liqpay_real_commission',10)->nullable();
            $table->string('liqpay_status',30)->nullable();

            $table->decimal('sum',10);
            $table->decimal('service_fee',10);
            $table->decimal('total',10);

            $table->decimal('liqpay_commission_auto_calculated',10);
            $table->decimal('bank_commission',10);
            $table->decimal('profit',10);

            $table->text('user_geo')->nullable();

            $table->text('history')->nullable();

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
        Schema::dropIfExists('service_orders');
    }
}
