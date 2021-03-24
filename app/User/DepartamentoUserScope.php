<?php

namespace App\User;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Scope;

class DepartamentoUserScope implements Scope
{
    public function apply(Builder $builder, Model $model){
        
        $departamento = app(ManagerDepartamentoUser::class)->getDepartamentoIdentify();
        $builder->where('departamento_id', $departamento);
    }
}