<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateHistoricoRelatorioUsersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('historico_relatorio_users', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('user_id');
            $table->unsignedBigInteger('relatorio_id');
            $table->unsignedBigInteger('tenant_id');
            $table->unsignedBigInteger('departamento_id');
            $table->string('favorito', 1)->nullable();
            $table->dateTime('ultima_hora_acessada')->nullable();
            $table->integer('qtd_acessos')->nullable();
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
        Schema::dropIfExists('historico_relatorio_users');
    }
}
