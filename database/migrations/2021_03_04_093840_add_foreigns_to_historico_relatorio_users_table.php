<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddForeignsToHistoricoRelatorioUsersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('historico_relatorio_users', function (Blueprint $table) {
            $table
            ->foreign('user_id')
            ->references('id')
            ->on('users')->onDelete('Cascade');
            $table
                ->foreign('relatorio_id')
                ->references('id')
                ->on('relatorios')->onDelete('Cascade');
            $table
                ->foreign('tenant_id')
                ->references('id')
                ->on('tenants')->onDelete('Cascade');
            $table
                ->foreign('departamento_id')
                ->references('id')
                ->on('departamentos')->onDelete('Cascade');
        });
   
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('historico_relatorio_users', function (Blueprint $table) {
            $table->dropForeign(['user_id']);
            $table->dropForeign(['relatorio_id']);
            $table->dropForeign(['tenant_id']);
            $table->dropForeign(['departamento_id']);
        });
    }
}
