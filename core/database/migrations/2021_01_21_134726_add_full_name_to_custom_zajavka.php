<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddFullNameToCustomZajavka extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('custom_zajavka', function (Blueprint $table) {
            $table->string('full_name',255);
            $table->dropColumn('surname');
            $table->dropColumn('firstname');
            $table->dropColumn('secondname');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('custom_zajavka', function (Blueprint $table) {
            $table->dropColumn('full_name');
            $table->string('surname',255);
            $table->string('firstname',255);
            $table->string('secondname',255);
        });
    }
}
