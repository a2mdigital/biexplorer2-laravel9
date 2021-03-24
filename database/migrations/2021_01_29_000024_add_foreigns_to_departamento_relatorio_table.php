<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddForeignsToDepartamentoRelatorioTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('departamento_relatorio', function (Blueprint $table) {
            $table
                ->foreign('departamento_id')
                ->references('id')
                ->on('departamentos')->onDelete('Cascade');
            $table
                ->foreign('relatorio_id')
                ->references('id')
                ->on('relatorios')->onDelete('Cascade');
            $table
                ->foreign('tenant_id')
                ->references('id')
                ->on('tenants')->onDelete('Cascade');    
               
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('departamento_relatorio', function (Blueprint $table) {
            $table->dropForeign(['departamento_id']);
            $table->dropForeign(['relatorio_id']);
            $table->dropForeign(['tenant_id']);     
        });
    }
}
