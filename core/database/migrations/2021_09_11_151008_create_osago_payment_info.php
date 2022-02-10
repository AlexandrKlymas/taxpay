<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateOsagoPaymentInfo extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('osago_payment_info', function (Blueprint $table) {
            $table->id();
            $table->integer('form_id')->default(0);
            $table->integer('doc_id')->default(0);

            $table->string('fio')->nullable();
            $table->string('address')->nullable();
            $table->string('phone')->nullable();
            $table->string('email',100)->nullable();

            $table->longText('post_info')->nullable();
            $table->longText('vis_table')->nullable();
            $table->longText('payment_order')->nullable();
            $table->longText('payment_order_fields')->nullable();

            $table->string('poluch_name')->nullable();
            $table->string('poluch_account',20)->nullable();
            $table->string('poluch_egrpou',10)->nullable();
            $table->string('poluch_mfo',10)->nullable();
            $table->string('poluch_bank')->nullable();
            $table->string('pagetitle')->nullable();

            $table->decimal('summ_original',10)->default(0.00);
            $table->decimal('summ_komission',10)->default(0.00);
            $table->decimal('itogo',10)->default(0.00);
            $table->decimal('summ_liqpey',10)->default(0.00);
            $table->decimal('summ_bank',10)->default(0.00);
            $table->decimal('summ_ostatok',10)->default(0.00);

            $table->dateTime('date')->default('0000-00-00 00:00:00');
            $table->dateTime('status_date')->default('0000-00-00 00:00:00');

            $table->string('status',20)->default('wait');

            $table->integer('transaction_id')->default(0);
            $table->integer('payment_id')->default(0);

            $table->dateTime('create_date')->default('0000-00-00 00:00:00');
            $table->dateTime('end_date')->default('0000-00-00 00:00:00');

            $table->longText('liqpay_responce')->nullable();

            $table->decimal('receiver_commission',10)->default(0.00);

            $table->string('href_order_pdf')->nullable();

            $table->longText('user_geo_data')->nullable();

            $table->longText('policyDirectLink')->nullable();

            $table->longText('contractId')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('osago_payment_info');
    }
}
