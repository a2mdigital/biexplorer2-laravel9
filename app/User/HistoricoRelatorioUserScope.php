<?php

namespace App\User;

use App\Tenant\ManagerTenant;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Scope;
use Illuminate\Database\Eloquent\Builder;

class HistoricoRelatorioUserScope implements Scope
{
    public function apply(Builder $builder, Model $model){
        
        $user = app(ManagerUser::class)->getUserIdentify();
        $tenant = app(ManagerTenant::class)->getTenantIdentify();
        $departamento = app(ManagerDepartamentoUser::class)->getDepartamentoIdentify();
        $builder->where('user_id', $user)
                ->where('tenant_id', $tenant)
                ->where('departamento_id', $departamento);
    }
}