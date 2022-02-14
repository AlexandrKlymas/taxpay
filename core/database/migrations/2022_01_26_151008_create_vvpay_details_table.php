<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateVVPayDetailsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('vvpay_details', function (Blueprint $table) {
            $table->id();
            $table->string('region',100)->nullable();
            $table->string('district',100)->nullable();
            $table->string('recipient',100)->nullable();
            $table->string('iban',29);
            $table->string('mfo',100)->nullable();
            $table->string('edrpou',100)->nullable();
            $table->tinyInteger('active')->default(1);
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('vvpay_details');
    }
}