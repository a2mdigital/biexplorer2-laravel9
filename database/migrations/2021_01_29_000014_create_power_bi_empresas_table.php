<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreatePowerBiEmpresasTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('power_bi_empresas', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->string('user_powerbi', 200);
            $table->longText('password_powerbi');
            $table->string('client_id', 200);
            $table->longText('client_secret');
            $table->string('diretorio_id', 200);
            $table->unsignedBigInteger('tenant_id');

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
        Schema::dropIfExists('power_bi_empresas');
    }
}
