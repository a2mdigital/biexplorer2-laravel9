<?php 

namespace App\Tenant;
use App\Tenant\TenantObserver;
use App\Tenant\TenantScopeDepartamentos;

trait TenantTraitDepartamentos
{
    public static function boot(){
        
        parent::boot();
        /*PEGAR TENANT ID DO USUARIO LOGADO*/
        static::addGlobalScope(new TenantScopeDepartamentos);

        /*INSERIR O TENANT_ID AUTOMATICO NO CADASTRO*/
        static::observe(new TenantObserver);
         

    }
}