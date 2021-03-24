<?php 

namespace App\Parceiro;

use Webpatser\Uuid\Uuid;
use App\Parceiro\ParceiroScope;
use App\Parceiro\ParceiroObserver;

trait ParceiroTraitTenant
{
    public static function boot(){
        
        parent::boot();
        /*PEGAR PARCEIRO ID DO PARCEIRO LOGADO*/
        static::addGlobalScope(new ParceiroScope);

        /*INSERIR O PARCEIRO_ID AUTOMATICO NO CADASTRO DO TENANT*/
        static::observe(new ParceiroObserver);
          /*INSERIR UUID AUTOMÁTICO */
        self::creating(function($model){
            $model->uuid = (string) Uuid::generate(4);
        });


    }
}