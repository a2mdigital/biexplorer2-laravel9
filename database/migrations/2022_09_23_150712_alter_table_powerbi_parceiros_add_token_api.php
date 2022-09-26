<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AlterTablePowerbiParceirosAddTokenApi extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('power_bi_parceiros', function (Blueprint $table) {
            $table->text('bearer_token_api_a2m')->nullable();
            $table->date('data_expira_token')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('power_bi_parceiros', function (Blueprint $table) {
            $table->dropColumn('bearer_token_api_a2m');
            $table->date('data_expira_token');
        });
    }
}
