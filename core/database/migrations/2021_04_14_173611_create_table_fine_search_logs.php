<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateTableFineSearchLogs extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('fine_search_logs', function (Blueprint $table) {
            $table->id();
            $table->string('license_plate',20);
            $table->string('tax_number',20)->nullable();
            $table->string('tech_passport',20)->nullable();
            $table->string('driving_license',20)->nullable();
            $table->timestamp('driving_license_date_issue')->nullable();
            $table->string('fine_series',20)->nullable();
            $table->string('fine_number',20)->nullable();
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
        Schema::dropIfExists('fine_search_logs');
    }
}
