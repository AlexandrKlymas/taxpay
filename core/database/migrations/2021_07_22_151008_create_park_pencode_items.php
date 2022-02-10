<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateParkPenCodeItems extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('park_pencode_items', function (Blueprint $table) {
            $table->id();
            $table->string('name_ua',100)->nullable();
            $table->string('name_ru',100)->nullable();
            $table->string('description',100)->nullable();
            $table->string('region',100)->nullable();
            $table->string('mfo',100)->nullable();
            $table->string('score',100)->nullable();
            $table->string('okpo',100)->nullable();
            $table->tinyInteger('active')->default(1);
            $table->string('iban',29);
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('park_pencode_items');
    }
}
