<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateTableTelegramBotUserMessages extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('telegram_bot_user_messages', function (Blueprint $table) {
            $table->id();
            $table->bigInteger('telegram_bot_user_id');
            $table->bigInteger('message_id');
            $table->string('message_type',50);
            $table->integer('entity_id');
            $table->text('meta')->nullable();
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
        Schema::dropIfExists('telegram_bot_user_messages');
    }
}
