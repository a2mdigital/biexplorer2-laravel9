<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddForeignsToGrpAcessPermParcTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('grp_acess_perm_parc', function (Blueprint $table) {
            $table
                ->foreign('permissoes_parceiro_id')
                ->references('id')
                ->on('permissoes_parceiros');
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
        Schema::table('grp_acess_perm_parc', function (Blueprint $table) {
            $table->dropForeign(['permissoes_parceiro_id']);
            $table->dropForeign(['grp_acess_parc_id']);
        });
    }
}
