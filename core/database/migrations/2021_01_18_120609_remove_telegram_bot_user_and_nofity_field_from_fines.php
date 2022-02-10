<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class RemoveTelegramBotUserAndNofityFieldFromFines extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('fines', function (Blueprint $table) {
            $table->dropColumn('telegram_bot_user_id');
            $table->dropColumn('notify');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('fines', function (Blueprint $table) {
            $table->text('notify')->default('[]')->after('paid');
            $table->integer('telegram_bot_user_id')->nullable();
        });
    }
}
