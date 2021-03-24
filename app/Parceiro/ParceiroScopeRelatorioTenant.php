<?php

namespace App\Parceiro;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Scope;

class ParceiroScopeRelatorioTenant implements Scope
{
    public function apply(Builder $builder, Model $model){
        
        $parceiro = app(ManagerParceiro::class)->getParceiroIdentify();
        $builder->where('relatorio_tenant.parceiro_id', $parceiro);
    }
}