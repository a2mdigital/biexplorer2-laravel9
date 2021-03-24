<?php

namespace App\User;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Scope;

class UserScope implements Scope
{
    public function apply(Builder $builder, Model $model){
        
        $user = app(ManagerUser::class)->getUserIdentify();
        $builder->where('user_id', $user);
    }
}