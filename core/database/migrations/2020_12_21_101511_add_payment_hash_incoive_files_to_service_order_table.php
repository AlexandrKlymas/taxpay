<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddPaymentHashIncoiveFilesToServiceOrderTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('service_orders', function (Blueprint $table) {
            $table->string('order_hash')->unique()->after('service_id');
            $table->string('invoice_file_pdf',255)->nullable()->after('profit');
            $table->string('invoice_file_html',255)->nullable()->after('profit');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('service_orders', function (Blueprint $table) {
            $table->dropColumn('order_hash');
            $table->dropColumn('invoice_file_pdf');
            $table->dropColumn('invoice_file_html');
        });
    }
}
