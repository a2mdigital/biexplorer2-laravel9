<?php

namespace App\Http\Controllers\Administradores;
use App\Models\User;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\Relatorio;
use App\Models\RelatorioTenant;
use App\Models\UserTenant;
use Auth;
class DashboardAdmController extends Controller
{
    public function indexDashboard(){
          $user = Auth::user();
       
          $relatorios_tenant = RelatorioTenant::select('relatorio_id')->get();
          $total_relatorios_tenant = RelatorioTenant::count();
          $relatorios = Relatorio::withoutGlobalScopes()
                        -> whereIn('relatorios.id', $relatorios_tenant)
                        ->where('relatorio_tenant.tenant_id', $user->tenant_id)
                        ->LeftJoin('relatorio_tenant', 'relatorios.id', '=', 'relatorio_tenant.relatorio_id')
                        ->orderBy('relatorio_tenant.created_at', 'desc')
                        ->limit(10)
                        ->get();
          $users = UserTenant::get();    
          
          return view('pages.administrador.dashboard', compact('relatorios', 'total_relatorios_tenant', 'users'));
    }

    public function salvarCustomMenuColor(Request $request){
        $dados = $request->all();
      
        $user = User::findOrFail($dados['user']);
        $user->update(['menu_color' =>  $dados['color']]);
       
    }

    public function salvarCustomMenuContraido(Request $request){
        $dados = $request->all();
      
        $user = User::findOrFail($dados['user']);
        $user->update(['menu_contraido' =>  $dados['contraido']]);
       
    }
}
