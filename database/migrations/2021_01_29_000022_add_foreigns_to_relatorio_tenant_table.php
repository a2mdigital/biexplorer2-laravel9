<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddForeignsToRelatorioTenantTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('relatorio_tenant', function (Blueprint $table) {
            $table
                ->foreign('tenant_id')
                ->references('id')
                ->on('tenants')->onDelete('Cascade');
            $table
                ->foreign('relatorio_id')
                ->references('id')
                ->on('relatorios')->onDelete('Cascade');
            $table
                ->foreign('parceiro_id')
                ->references('id')
                ->on('parceiros')->onDelete('Cascade');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('relatorio_tenant', function (Blueprint $table) {
            $table->dropForeign(['tenant_id']);
            $table->dropForeign(['relatorio_id']);
            $table->dropForeign(['parceiro_id']);
        });
    }
}
