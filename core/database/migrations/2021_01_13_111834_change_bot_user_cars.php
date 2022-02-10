<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class ChangeBotUserCars extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('telegram_bot_user_cars', function (Blueprint $table) {
            $table->dropColumn('document');
            $table->string('document_type',255)->after('car_number');
            $table->text('document_info')->after('document_type');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('telegram_bot_user_cars', function (Blueprint $table) {
            $table->string('document',50);
            $table->dropColumn('document_type');
            $table->dropColumn('document_info');
        });
    }
}
