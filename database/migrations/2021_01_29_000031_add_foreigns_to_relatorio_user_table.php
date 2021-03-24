<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddForeignsToRelatorioUserTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('relatorio_user', function (Blueprint $table) {
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
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('relatorio_user', function (Blueprint $table) {
            $table->dropForeign(['user_id']);
            $table->dropForeign(['relatorio_id']);
            $table->dropForeign(['tenant_id']);
        });
    }
}
