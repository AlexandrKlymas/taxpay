<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateOsagoOrders extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('osago_orders', function (Blueprint $table) {
            $table->id();
            $table->string('contract_id',100);
            $table->tinyInteger('status');
            $table->longText('insurance_params');
            $table->longText('payment_callback')->nullable();
            $table->longText('contract_payment')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('osago_orders');
    }
}
