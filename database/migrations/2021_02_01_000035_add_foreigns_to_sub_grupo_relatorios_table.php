<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddForeignsToSubGrupoRelatoriosTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('sub_grupo_relatorios', function (Blueprint $table) {
            $table
                ->foreign('grp_rel_parceiro_id')
                ->references('id')
                ->on('grupo_relatorio_parceiros')->onDelete('Cascade');

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
        Schema::table('sub_grupo_relatorios', function (Blueprint $table) {
            $table->dropForeign(['grp_rel_parceiro_id']);
            $table->dropForeign(['parceiro_id']);
        });
    }
}
