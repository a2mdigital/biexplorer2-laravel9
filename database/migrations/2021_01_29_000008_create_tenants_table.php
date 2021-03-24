<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateTenantsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('tenants', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->string('nome', 255);
            $table->uuid('uuid')->nullable();
            $table->integer('limite_usuarios')->nullable();
            $table->string('utiliza_filtro', 1)->nullable();
            $table->string('filtro_tabela', 100)->nullable();
            $table->string('filtro_coluna', 100)->nullable();
            $table->string('filtro_valor', 100)->nullable();
            $table->string('utiliza_rls', 1)->nullable();
            $table->string('regra_rls', 50)->nullable();
            $table->string('username_rls', 50)->nullable();
            $table->float('valor_usuario')->nullable();
            $table->string('email_administrador');
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
        Schema::dropIfExists('tenants');
    }
}
