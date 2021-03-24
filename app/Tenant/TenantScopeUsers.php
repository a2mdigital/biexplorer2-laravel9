<?php

namespace App\Tenant;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Scope;

class TenantScopeUsers implements Scope
{
    public function apply(Builder $builder, Model $model){
        
        $tenant = app(ManagerTenant::class)->getTenantIdentify();
        $builder->where('users.tenant_id', $tenant);
    }
}