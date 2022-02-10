<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddFieldCheckedAtTOBotUserCarsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('telegram_bot_user_cars', function (Blueprint $table) {
            $table->timestamp('checked_at')->nullable()->after('document_info');
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
            $table->dropColumn('checked_at');
        });
    }
}
