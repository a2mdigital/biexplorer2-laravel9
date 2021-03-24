<?php 

namespace App\Parceiro;


use App\Parceiro\ParceiroScopeRelatorioTenant;
use App\Parceiro\ParceiroObserver;

trait ParceiroTraitRelatorioTenant
{
    public static function boot(){
        
        parent::boot();
        /*PEGAR PARCEIRO ID DO PARCEIRO LOGADO*/
        static::addGlobalScope(new ParceiroScopeRelatorioTenant);

        /*INSERIR O PARCEIRO_ID AUTOMATICO NO CADASTRO DO TENANT*/
        static::observe(new ParceiroObserver);
       
    }
}