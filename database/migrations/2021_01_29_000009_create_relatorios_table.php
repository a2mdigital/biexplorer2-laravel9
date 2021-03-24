<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateRelatoriosTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('relatorios', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->string('nome', 200)->nullable();
            $table->string('descricao', 400)->nullable();
            $table->string('tipo', 50)->nullable();
            $table->string('utiliza_filtro_rls', 1)->nullable();
            $table->string('nivel_filtro_rls', 50)->nullable();
            $table->string('filtro_lateral', 1)->nullable();
            $table->string('report_id', 200)->nullable();
            $table->string('workspace_id', 200)->nullable();
            $table->string('dataset_id', 200)->nullable();
            $table->unsignedBigInteger('subgrupo_relatorio_id');
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
        Schema::dropIfExists('relatorios');
    }
}
