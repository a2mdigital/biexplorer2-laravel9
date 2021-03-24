<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddForeignsToGrupoAcessoPermissoesUsuarioTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('grupo_acesso_permissoes_usuario', function (
            Blueprint $table
        ) {
            $table
                ->foreign('grupo_acesso_id')
                ->references('id')
                ->on('grupo_acessos');
            $table
                ->foreign('permi_user_id')
                ->references('id')
                ->on('permissao_usuarios');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('grupo_acesso_permissoes_usuario', function (
            Blueprint $table
        ) {
            $table->dropForeign(['grupo_acesso_id']);
            $table->dropForeign(['permi_user_id']);
        });
    }
}
