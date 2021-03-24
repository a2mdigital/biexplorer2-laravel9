<?php

namespace App\Http\Controllers\Parceiros;

use App\Models\User;
use App\Models\Tenant;
use App\Models\Parceiro;
use App\Models\Relatorio;
use Illuminate\Http\Request;
use App\Models\TenantParceiro;
use App\Http\Controllers\Controller;

class ParceirosDashboardController extends Controller
{
  


    public function indexDashboard(){

            $total_empresas = Tenant::count();
            $total_relatorios = Relatorio::count();
            $tenants = Tenant::get();
            $total_users = 0;
            $tenants_id = array();
            foreach($tenants as $tenant){
                $users_tenant = $tenant->users()->count();
                $total_users = $total_users + $users_tenant;
                array_push($tenants_id, $tenant->id);
            }
            $usuarios = User::whereIn('tenant_id', $tenants_id)->orderBy('ultimo_login', 'desc')->get();
           
      return view('pages.parceiro.dashboard', compact('total_empresas', 'total_users', 'total_relatorios', 'usuarios'));
    }

    public function salvarCustomMenuColor(Request $request){
        $dados = $request->all();
      
        $userParceiro = Parceiro::findOrFail($dados['user']);
        $userParceiro->update(['menu_color' =>  $dados['color']]);
       
    }

    public function salvarCustomMenuContraido(Request $request){
        $dados = $request->all();
      
        $userParceiro = Parceiro::findOrFail($dados['user']);
        $userParceiro->update(['menu_contraido' =>  $dados['contraido']]);
       
    }
}
