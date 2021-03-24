<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddForeignsToPowerBiParceirosTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('power_bi_parceiros', function (Blueprint $table) {
            $table
                ->foreign('parceiro_id')
                ->references('id')
                ->on('parceiros')->onDelete('Cascade');
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
            $table->dropForeign(['parceiro_id']);
        });
    }
}
