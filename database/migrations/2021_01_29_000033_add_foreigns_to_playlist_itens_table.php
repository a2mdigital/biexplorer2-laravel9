<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddForeignsToPlaylistItensTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('playlist_itens', function (Blueprint $table) {
            $table
                ->foreign('playlist_id')
                ->references('id')
                ->on('playlists')->onDelete('Cascade');
            $table
                ->foreign('relatorio_id')
                ->references('id')
                ->on('relatorios')->onDelete('Cascade');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('playlist_itens', function (Blueprint $table) {
            $table->dropForeign(['playlist_id']);
            $table->dropForeign(['relatorio_id']);
        });
    }
}
