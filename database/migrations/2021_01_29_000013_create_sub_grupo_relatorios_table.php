<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateSubGrupoRelatoriosTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('sub_grupo_relatorios', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->string('nome');
            $table->string('cor', 10)->nullable();
            $table->unsignedBigInteger('grp_rel_parceiro_id');
            $table->unsignedBigInteger('parceiro_id');

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
        Schema::dropIfExists('sub_grupo_relatorios');
    }
}
