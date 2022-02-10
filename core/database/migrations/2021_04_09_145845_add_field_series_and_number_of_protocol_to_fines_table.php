<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddFieldSeriesAndNumberOfProtocolToFinesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('fines', function (Blueprint $table) {
            $table->string('protocol_series',10)->after('fine_doc_id')->nullable();
            $table->string('protocol_number',30)->after('protocol_series')->nullable();

            $table->string('fine_doc_id', '50')->nullable()->comment('not use')->change();
            $table->string('command',255)->nullable()->change();
            $table->text('command_info')->nullable()->change();
            $table->text('data')->nullable()->change();
        });




        $fines = \EvolutionCMS\Main\Services\FinesSearcher\Models\Fine::all();
        foreach ($fines as $fine) {
            $fine->protocol_series = $fine->data['sprotocol'];
            $fine->protocol_number = $fine->data['nprotocol'];
            $fine->save();
        }




    }

    /**
     *
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('fines', function (Blueprint $table) {
            $table->dropColumn('protocol_series');
            $table->dropColumn('protocol_number');

            $table->string('fine_doc_id', '50')->nullable(false)->comment('')->change();
            $table->string('command',255)->nullable(false)->change();
            $table->text('command_info')->nullable(false)->change();
            $table->text('data')->nullable(false)->change();
        });
    }
}
