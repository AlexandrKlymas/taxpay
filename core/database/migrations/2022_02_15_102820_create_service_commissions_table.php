<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateServiceCommissionsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('service_commissions', function (Blueprint $table) {
            $table->id();
            $table->bigInteger('service_recipient_id');
            $table->bigInteger('commissions_recipient_id');
            $table->decimal('percent')->default(0.00);
            $table->decimal('min')->default(0.00);
            $table->decimal('max')->default(0.00);
            $table->decimal('fix')->default(0.00);
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
        Schema::dropIfExists('service_commissions');
    }
}
