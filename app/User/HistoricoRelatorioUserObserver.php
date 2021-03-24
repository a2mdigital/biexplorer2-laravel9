<?php 

namespace App\User;

use App\Tenant\ManagerTenant;
use App\User\ManagerDepartamentoUser;
use Illuminate\Database\Eloquent\Model;

class HistoricoRelatorioUserObserver
{
    public function creating(Model $model){

        $user= app(ManagerUser::class)->getUserIdentify();
        $tenant = app(ManagerTenant::class)->getTenantIdentify();
        $departamento = app(ManagerDepartamentoUser::class)->getDepartamentoIdentify();
        $model->setAttribute('user_id', $user)
               ->setAttribute('tenant_id', $tenant)
               ->setAttribute('departamento_id', $departamento);
    }

}