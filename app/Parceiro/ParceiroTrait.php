<?php 

namespace App\Parceiro;


use App\Parceiro\ParceiroScope;
use App\Parceiro\ParceiroObserver;

trait ParceiroTrait
{
    public static function boot(){
        
        parent::boot();
        /*PEGAR PARCEIRO ID DO PARCEIRO LOGADO*/
        static::addGlobalScope(new ParceiroScope);

        /*INSERIR O PARCEIRO_ID AUTOMATICO NO CADASTRO DO TENANT*/
        static::observe(new ParceiroObserver);
       
    }
}