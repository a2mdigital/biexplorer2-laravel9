<?php 

namespace App\Http\Controllers\Api;

Use Illuminate\Http\Request;
use App\Models\Relatorio;
use App\Models\TenantUser;
use App\Tenant\ManagerTenant;
use App\Models\SubGrupoRelatorio;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Gate;
use App\Models\RelatorioUserPermission;
use App\Services\GetTokenPowerBiService;
use App\Services\GetTokenRlsPowerBiService;
use App\Models\RelatorioDepartamentoPermission;

class ReportsController extends Controller{
    public function index(Request $request, $grupo){

        $tenant = app(ManagerTenant::class)->getTenantIdentify();
        $grupo = SubGrupoRelatorio::findOrFail($grupo);
        //verifico o usuário da requisição da API..
        //se for admin eu leio os relatórios de uma forma
        //se for usuário eu leio os relatórios de outra forma
        $user = auth()->user();
        if($user->is_admin == 1){
            //se o usuário for admin verifico se ele tem permissão nesse grupo
            //caso contrário aborto
            if (! Gate::allows('listar-grupo-relatorio-admin',$grupo)) {
                return ['response' => 'forbidden', 'reports' => ''];
            }else{
                //leio todos os relatórios deste grupo
                $RelatoriosPermissions = DB::table('relatorio_tenant')
                ->join('tenants', 'relatorio_tenant.tenant_id', '=', 'tenants.id')
                ->join('relatorios', 'relatorio_tenant.relatorio_id', '=', 'relatorios.id')
                ->select('relatorios.id as id', 'relatorios.nome as nome', 'relatorios.subgrupo_relatorio_id as subgrupo_relatorio_id')
                ->where('tenants.id', '=', $tenant)
                ->where('relatorios.subgrupo_relatorio_id', '=', $grupo->id)->get();
                
            }
        }else{
            //verifico se o usuário tem permissão para acessar o grupo do relatório
            //caso contrário dou um abort
            if (! Gate::allows('listar-grupo-relatorio-user',$grupo)) {
                return ['response' => 'forbidden', 'reports' => ''];
            }else{
               
                $relatorios_user = RelatorioUserPermission::select('relatorio_id')->get();
                $relatorios_departamento = RelatorioDepartamentoPermission::select('relatorio_id')->get();
                $RelatoriosPermissions = Relatorio::where(function($query) use ($grupo){
                    $query->where('subgrupo_relatorio_id', $grupo->id);
                })
                ->where(function($query) use ($relatorios_user, $relatorios_departamento){
                    $query->orWhereIn('id', $relatorios_user);
                    $query->orWhereIn('id', $relatorios_departamento);
                })->get();
            }
        }
        
        return ['response' => 'ok', 'reports' => $RelatoriosPermissions];
    }

    public function viewReport($grupo, $id){

        $user = auth()->user();
        //verifico se o usuário que está acessando é admin ou não
        if($user->is_admin == 1){
            $relatorio = Relatorio::where('id', $id)->where('subgrupo_relatorio_id', $grupo)->firstOrFail();
       
            if (! Gate::allows('permissao-visualizar-relatorio-admin',$relatorio)) {
                return ['response' => 'forbidden', 'message' => 'Não Autorizado', 'token' => '' ];
            }else{
                //VERIFICAR OS ACESSOS E RETORNAR O TOKEN PARA O APLICATIVO
                $tenant = TenantUser::firstOrFail();
       
                if($tenant->utiliza_rls == 'S'){
                   if($tenant->regra_rls == ''){
                      return ['response' => 'error', 'message' => 'Verifique o RLS no cadastro da Empresa', 'token' => '' ];
                   }
                 }
                if($tenant->utiliza_filtro == 'S'){
                  if($tenant->filtro_tabela == '' || $tenant->filtro_coluna == '' || $tenant->filtro_valor == ''){
                    return ['response' => 'error', 'message' => 'Verifique os filtros no cadastro da Empresa', 'token' => '' ]; 
                  }
                  
                }
                //GERAR TOKEN RLS OU TOKEM SEM RLS
                if($tenant->utiliza_rls == 'S'){
                   $resposta = GetTokenRlsPowerBiService::getTokenRlsTenant($relatorio, $tenant);  
            
                }else{
                   $resposta = GetTokenPowerBiService::getToken();  
               
                }
                if($resposta['resposta'] == 'ok'){
                    $token = $resposta['token'];
                    return ['response' => 'ok', 'message' => 'Token Gerado', 'tenant' => $tenant, 'report' => $relatorio, 'token' => $token ]; 
                }else{
                    $erro = $resposta['error'];
                    return ['response' => 'ok', 'message' => $erro, 'token' => ''];
                    
                }
            }
        }else{

        }

    }
}