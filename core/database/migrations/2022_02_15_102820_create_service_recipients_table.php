<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateServiceRecipientsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('service_recipients', function (Blueprint $table) {
            $table->id();
            $table->bigInteger('service_id');
            $table->bigInteger('sub_service_id')
                ->nullable()->default(null);
            $table->string('recipient_name');
            $table->integer('edrpou');
            $table->integer('mfo');
            $table->string('iban');
            $table->string('purpose_template');
            $table->decimal('sum')->nullable()->default(0.00);
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
        Schema::dropIfExists('service_recipients');
    }
}
