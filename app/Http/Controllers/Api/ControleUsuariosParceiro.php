<?php

namespace App\Http\Controllers\Api;
use App\Models\User;
use App\Models\Tenant;
use App\Models\Parceiro;
use App\Models\UserTenant;
use Illuminate\Http\Request;
use App\Models\TenantParceiro;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\DB;

class ControleUsuariosParceiro extends Controller
{
    public function getUsuariosParceiro(){
      
        $user = auth('apiParceiro')->user();
      
      //pegar usuários do parceiro com controle de login
      $users_data = DB::select(
        "SELECT 
            u.name as usuario_nome,
            u.email as usuario_email,
            u.ultimo_login as ultimo_login,
            u.created_at as criado_em,
            u.updated_at as atualizado_em,
            u.departamento_id as departamento_id,
            d.nome as departamento_nome,
            u.tenant_id as tenant_id,
            t.nome as tenant_nome
            FROM users u 
            join departamentos d on u.departamento_id = d.id
            join tenants t on t.id = u.tenant_id
            WHERE t.parceiro_id = $user->id"
      );   
      return $users_data ;
    }
}
