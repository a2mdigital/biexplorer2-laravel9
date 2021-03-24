<?php 

namespace App\User;


trait HistoricoRelatorioUserTrait
{
    public static function boot(){
        
        parent::boot();
        /*PEGAR TENANT ID DO USUARIO LOGADO*/
        static::addGlobalScope(new HistoricoRelatorioUserScope);

        /*INSERIR O TENANT_ID AUTOMATICO NO CADASTRO*/
        static::observe(new HistoricoRelatorioUserScope);
         

    }
}