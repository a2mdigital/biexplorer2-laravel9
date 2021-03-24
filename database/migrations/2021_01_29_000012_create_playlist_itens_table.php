<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreatePlaylistItensTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('playlist_itens', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->integer('ordem');
            $table->string('navega_paginas', 1)->nullable();
            $table->unsignedBigInteger('playlist_id');
            $table->unsignedBigInteger('relatorio_id');
            $table->integer('tenant_id');

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
        Schema::dropIfExists('playlist_itens');
    }
}
