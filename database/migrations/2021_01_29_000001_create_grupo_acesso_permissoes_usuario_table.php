<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateGrupoAcessoPermissoesUsuarioTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('grupo_acesso_permissoes_usuario', function (
            Blueprint $table
        ) {
            $table->unsignedBigInteger('grupo_acesso_id');
            $table->unsignedBigInteger('permi_user_id');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('grupo_acesso_permissoes_usuario');
    }
}
