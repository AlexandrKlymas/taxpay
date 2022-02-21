<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateCommissionsRecipientsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('commissions_recipients', function (Blueprint $table) {
            $table->id();
            $table->string('recipient_name');
            $table->integer('edrpou');
            $table->integer('mfo');
            $table->string('iban');
            $table->string('purpose_template');
            $table->string('recipient_type',30);
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
        Schema::dropIfExists('commissions_recipients');
    }
}
