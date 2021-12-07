<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateCustomThemeTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('custom_theme', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->string('cor_titulo_menu_lateral');
            $table->string('cor_fundo_menu_lateral');
            $table->string('cor_texto_menu_lateral');
            $table->string('cor_texto_hover_menu_lateral');
            $table->string('cor_fundo_barra_superior');
            $table->string('cor_texto_barra_superior');
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
        Schema::dropIfExists('custom_theme');
    }
}
