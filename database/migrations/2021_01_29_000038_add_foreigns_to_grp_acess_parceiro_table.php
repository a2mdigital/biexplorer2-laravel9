<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddForeignsToGrpAcessParceiroTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('grp_acess_parceiro', function (Blueprint $table) {
            $table
                ->foreign('parceiro_id')
                ->references('id')
                ->on('parceiros');
            $table
                ->foreign('grp_acess_parc_id')
                ->references('id')
                ->on('grp_acess_parcs');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('grp_acess_parceiro', function (Blueprint $table) {
            $table->dropForeign(['parceiro_id']);
            $table->dropForeign(['grp_acess_parc_id']);
        });
    }
}
