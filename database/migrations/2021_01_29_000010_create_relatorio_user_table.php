<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateRelatorioUserTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('relatorio_user', function (Blueprint $table) {
            $table->unsignedBigInteger('user_id');
            $table->unsignedBigInteger('relatorio_id');
            $table->unsignedBigInteger('tenant_id');
            $table->string('utiliza_filtro', 1)->nullable();
            $table->string('tipo_filtro')->nullable();
            $table->string('filtro_tabela', 100)->nullable();
            $table->string('filtro_coluna', 100)->nullable();
            $table->string('filtro_valor', 100)->nullable();
            $table->string('utiliza_rls', 1)->nullable();
            $table->string('regra_rls', 50)->nullable();
            $table->string('username_rls', 50)->nullable();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('relatorio_user');
    }
}
