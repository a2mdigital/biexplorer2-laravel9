<?php

namespace App\Http\Controllers\Embedded;
use Alert;
use Carbon\Carbon;
use App\Models\User;
use App\Models\Relatorio;
use App\Models\TenantUser;
use App\Models\UserTenant;
use App\Models\Departamento;
use Illuminate\Http\Request;
use App\Models\RelatorioUser;
use App\Tenant\ManagerTenant;
use Faker\Provider\UserAgent;
use App\Models\RelatorioTenant;
use App\Models\SubGrupoRelatorio;
use App\Models\DepartamentoTenant;
use Illuminate\Support\Facades\DB;
use App\Models\RelatorioUserTenant;
use Illuminate\Support\Facades\App;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\Facades\Lang;
use App\Models\RelatorioDepartamento;
use App\Models\HistoricoRelatoriosUser;
use App\Models\RelatorioUserPermission;
use App\Services\GetTokenPowerBiService;
use Yajra\DataTables\Facades\DataTables;
use App\Services\GetTokenRlsPowerBiService;
use App\Models\RelatorioDepartamentoPermission;

class RelatorioEmbeddedController extends Controller
{

    public function viewReport($grupo, $id){
    
        /*pegar o local que está acessando o relatório 
            * para definir o timezone
           */
           $locale = App::currentLocale();
           if($locale == 'pt_BR'){
               $now = Carbon::now('America/Sao_Paulo');
           }else if($locale == 'pt_PT'){
               $now = Carbon::now('Europe/Lisbon');
           }else{
               $now = Carbon::now('Europe/London');
           }
       //pega os dados do relatório   
       try{     
       $relatorio = Relatorio::findOrFail($id);   
       }catch (\Illuminate\Database\Eloquent\ModelNotFoundException $e){
           return ['response' => 'error', 'msg' => 'Relatório não existe'];
       }   
       //busca a empresa do Usuário
       $tenant = TenantUser::firstOrFail();  
       $user = auth('api')->user();
      //$user = auth()->user();
       //verifico se o usuário que está acessando é admin ou não
       if($user->is_admin == 1){
       
         return $this->verRelatorioAdmin($grupo, $relatorio);
       }else{
        
         return $this->viewReportUser($grupo, $id);
       }    

   }
   
   public function verRelatorioAdmin($grupo, $rel){
    
    
    $relatorio = Relatorio::where('id', $rel->id)->where('subgrupo_relatorio_id', $grupo)->firstOrFail();
    
    
        
    $ignorar_rls_tenant = $relatorio->ignora_filtro_rls;
    if (! Gate::allows('permissao-visualizar-relatorio-admin',$relatorio)) {
        abort(403);
    }else{
     
     $tenant = TenantUser::firstOrFail();
   
     if($tenant->utiliza_rls == 'S'){
        if($tenant->regra_rls == ''){
           Alert::error('Erro', 'Verifique o RLS no cadastro da Empresa');
           return redirect()->back();
        }
      }
     if($tenant->utiliza_filtro == 'S'){
       if($tenant->filtro_tabela == '' || $tenant->filtro_coluna == '' || $tenant->filtro_valor == ''){
          Alert::error('Erro', 'Verifique os filtros no cadastro da Empresa');
          return redirect()->back();
       }
       
     }
     //GERAR TOKEN RLS OU TOKEM SEM RLS
     //CASO O RELATÓRIO IGNORE O RLS PEGAR O TOKEN NORMAL
     if($ignorar_rls_tenant =='S'){
         
        $resposta = GetTokenPowerBiService::getToken();  
       
     }else{
        if($tenant->utiliza_rls == 'S'){
            $resposta = GetTokenRlsPowerBiService::getTokenRlsTenant($relatorio, $tenant);  
     
        }else{
            $resposta = GetTokenPowerBiService::getToken();  
           
        }
    }
    //
    
     if($resposta['resposta'] == 'ok'){
         $token = $resposta['token'];
     }else{
         $erro = $resposta['error'];
         $token = '';
         Alert::error('Erro', $erro);
       //  Alert::error('Erro', 'Não foi possível abrir o relatório');

     } 
    return view('pages.embedded.visualizar-relatorio-admin', compact('relatorio', 'token', 'tenant'));
    }
    
   
   }

   public function viewReportUser($tenant, $relatorio){
    return view('pages.embedded.visualizar-relatorio-user');
   }
 
}
