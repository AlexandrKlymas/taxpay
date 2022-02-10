<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreatePaymentRecipientsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('payment_recipients', function (Blueprint $table) {
            $table->engine = 'InnoDB';

            $table->id();
            $table->integer('service_order_id');
            $table->string('edrpou',50);
            $table->string('account',100);
            $table->string('mfo',100);
            $table->double('amount',10,2);

            $table->string('check_id',16)->unique();


            $table->string('recipient_name')->nullable();
            $table->string('recipient_bank_name')->nullable();
            $table->string('purpose',500)->nullable();

            $table->string('recipient_type',30);
            $table->string('service_name',100)->nullable();

            $table->string('status')->default('wait');


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
        Schema::dropIfExists('payment_recipients');
    }
}
