<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateUsersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('users', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->string('name')->nullable();
            $table->string('email');
            $table->timestamp('email_verified_at')->nullable();
            $table->string('password');
            $table->string('troca_senha', 1)->nullable();
            $table->boolean('menu_color')->nullable();
            $table->boolean('menu_contraido')->nullable();
            $table->boolean('is_admin')->nullable();
            $table->string('session_id')->nullable();
            $table->string('utiliza_filtro', 1)->nullable();
            $table->string('filtro_tabela', 100)->nullable();
            $table->string('filtro_coluna', 100)->nullable();
            $table->string('filtro_valor', 100)->nullable();
            $table->string('utiliza_rls', 1)->nullable();
            $table->string('regra_rls', 50)->nullable();
            $table->string('username_rls', 50)->nullable();
            $table->unsignedBigInteger('departamento_id');
            $table->unsignedBigInteger('tenant_id');
            $table->dateTime('ultimo_login')->nullable();

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
        Schema::dropIfExists('users');
    }
}
