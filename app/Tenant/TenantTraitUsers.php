<?php 

namespace App\Tenant;
use App\Tenant\TenantObserver;
use App\Tenant\TenantScopeUsers;

trait TenantTraitUsers
{
    public static function boot(){
        
        parent::boot();
        /*PEGAR TENANT ID DO USUARIO LOGADO*/
        static::addGlobalScope(new TenantScopeUsers);

        /*INSERIR O TENANT_ID AUTOMATICO NO CADASTRO*/
        static::observe(new TenantObserver);
         

    }
}