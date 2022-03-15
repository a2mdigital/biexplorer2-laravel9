<?php

namespace App\Http\Controllers\Users;
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
class RelatorioUsersController extends Controller
{
    public function listarGrupos(){
       
      
      
        //buscar relatórios do usuário e do departamento
        $relatorios_user = RelatorioUserPermission::select('relatorio_id')->get();
        $relatorios_departamento = RelatorioDepartamentoPermission::select('relatorio_id')->get();

        $allRelatoriosPermission = Relatorio::
                                    whereIn('id', $relatorios_user)
                                    ->orWhereIn('id', $relatorios_departamento)
                                    ->get();
     
        $subgruposTenant = array();
        foreach($allRelatoriosPermission as $relPermission){
            array_push($subgruposTenant, $relPermission->subgrupo_relatorio_id);
        }
        //buscar grupos com relatórios liberados
        $grupos = SubGrupoRelatorio::whereIn('id', $subgruposTenant)->get();
       
   
        return view('pages.users.relatorios.grupos', compact('grupos'));
     
      
       
 
    }

    public function listarRelatorios(Request $request, $grupo){
       
        $tenant = app(ManagerTenant::class)->getTenantIdentify();
        $grupo = SubGrupoRelatorio::findOrFail($grupo);
      
        if (! Gate::allows('listar-grupo-relatorio-user',$grupo)) {
            abort(403);
        }else{
           
            $relatorios_user = RelatorioUserPermission::select('relatorio_id')->get();
            $relatorios_departamento = RelatorioDepartamentoPermission::select('relatorio_id')->get();
            /*
            $RelatoriosPermission = Relatorio::
                                        whereIn('id', $relatorios_user)
                                        ->orWhereIn('id', $relatorios_departamento)
                                        ->where('subgrupo_relatorio_id', $grupo->id)
                                        ->get();
            */
            $RelatoriosPermission = Relatorio::where(function($query) use ($grupo){
                $query->where('subgrupo_relatorio_id', $grupo->id);
            })
            ->where(function($query) use ($relatorios_user, $relatorios_departamento){
                $query->orWhereIn('id', $relatorios_user);
                $query->orWhereIn('id', $relatorios_departamento);
            })->get();

            if ($request->ajax()) {
                return Datatables::of($RelatoriosPermission)        
                        ->addIndexColumn()
                        ->addColumn('action', function($relatorio){
    
                          $botoes = '
                          <div style="display: flex; justify-content:flex-start">
                            <a href="'. route('users.tenant.relatorios.visualizar',[$relatorio->subgrupo_relatorio_id, $relatorio->id]) .'" class="edit btn btn-primary btn-sm">'.trans('messages.report_button_view').'</a>
                            </div>
                          ';  
    
                          return $botoes;
                        })
                        ->rawColumns(['action'])
                        ->make(true);
            }
            
            return view('pages.users.relatorios.listar', compact('grupo'));

        }
        
      
    }

    public function visualizarRelatorio($grupo, $id){ 

        if (! Gate::allows('visualizar-relatorio-user',[$grupo, $id])) {
            abort(403);
        }else{
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
            //Busca os dados do relatório
            $relatorio = Relatorio::find($id);
            //busca a empresa do Usuário
            $tenant = TenantUser::firstOrFail();
            //verifica se o relatório estár permitido para o usuário
            $relatorios_user = RelatorioUserPermission::where('relatorio_id', $id)->first();
            //verifica se o relatório está permitido por departamento
            $relatorios_departamento = RelatorioDepartamentoPermission::where('relatorio_id', $id)->first();
            //pega o usuário logado
            $user = auth()->user();
            //pega o departamento do usuário
            $departamento = $user->departamento()->first();
            //VERIFICA REGRA DE FILTRO DO RELATÓRIO
            $regra = 'sem_filtro_rls';
            if($relatorio->utiliza_filtro_rls == 'S'){
                //RELATÓRIO UTILIZA FILTRO OU RLS
               switch($relatorio->nivel_filtro_rls){
                   case "rls_relatorio":
                        if($relatorios_user != null){
                           
                            if($relatorios_user->utiliza_rls == 'S'){
                                if($relatorios_user->regra_rls == ''){
                                    Alert::error('Erro', 'Verifique o RLS do Usuário no cadastro do Relatório');
                                    return redirect()->back();
                                }
                                $regra = 'rls_relatorio_usuario'; 
                            }else{
                                Alert::error('Erro', 'Ative o RLS do Usuário no cadastro do Relatório');
                                return redirect()->back();
                            }
                         break;
                        }else if($relatorios_departamento != null){
                                //VERIFICO SE O RELATÓRIO FOI DEFINIDO POR DEPARTAMENTO
                                if($relatorios_departamento->utiliza_rls == 'S'){
                                    if($relatorios_departamento->regra_rls == ''){
                                        Alert::error('Erro', 'Verifique o RLS do Departamento no cadastro do Relatório');
                                        return redirect()->back();
                                     }
                                    $regra = 'rls_relatorio_departamento'; 
                                }else{
                                    Alert::error('Erro', 'Ative o RLS do Departamento no cadastro do Relatório');
                                    return redirect()->back();
                                }
                         break;       
                        }
                        break;
                    case "rls_usuario":
                        if($user->regra_rls == ''){
                            $msg_error_rls_usuario = __('messages.msg_error_rls_user');
                            Alert::error('Erro', $msg_error_rls_usuario);
                           // Alert::error('Erro', 'Verifique os RLS no cadastro do Usuário');
                            return redirect()->back();
                        }
                        $regra = 'rls_usuario';
                        break;
                    case "filtro_relatorio":
                      
                        if($relatorios_user != null){
                            //VERIFICO SE O RELATÓRIO FOI DEFINIDO POR USUÁRIO
                          if($relatorios_user->utiliza_filtro == 'S'){
                                //VERIFICA SE TEM FILTRO NO RELATÓRIO PARA O USUÁRIO
                                if($relatorios_user->filtro_tabela == '' || $relatorios_user->filtro_coluna == '' || $relatorios_user->filtro_valor == ''){
                                    Alert::error('Erro', 'Verifique os filtros do Usuário no cadastro do Relatório');
                                    return redirect()->back();
                                }
                                $regra = 'filtro_relatorio_usuario';
                            }else{
                                Alert::error('Erro', 'Ative os filtros do Usuário no cadastro do Relatório');
                                return redirect()->back();
                            } 
                         break;   
                        }else if($relatorios_departamento != null){
                            //VERIFICO SE O RELATÓRIO FOI DEFINIDO POR DEPARTAMENTO
                            //VERIFICA SE TEM FILTRO NO RELATÓRIO PARA O DEPARTAMENTO DO USUÁRIO
                            if($relatorios_departamento->utiliza_filtro == 'S'){
                                if($relatorios_departamento->filtro_tabela == '' || $relatorios_departamento->filtro_coluna == '' || $relatorios_departamento->filtro_valor == ''){
                                    Alert::error('Erro', 'Verifique os filtros do Departamento no cadastro do Relatório');
                                    return redirect()->back();
                                }
                                $regra = 'filtro_relatorio_departamento';
                            }else{
                                Alert::error('Erro', 'Ative os filtros do Departamento no cadastro do Relatório');
                                return redirect()->back();
                            }
                         break;   
                        }
                        break;
                    case "filtro_usuario":
                        if($user->filtro_tabela == '' || $user->filtro_coluna == '' || $user->filtro_valor == ''){
                            Alert::error('Erro', 'Verifique os filtros no cadastro do Usuário');
                            return redirect()->back();
                         }
                        $regra = 'filtro_usuario';
                        break;
                    case "filtro_departamento":
                        if($departamento->filtro_tabela == '' || $departamento->filtro_coluna == '' || $departamento->filtro_valor == ''){
                            Alert::error('Erro', 'Verifique os filtros no cadastro do Departamento');
                            return redirect()->back();
                         }
                        $regra = 'filtro_departamento';
                        break;    
               }

            }else{
                $regra = 'sem_filtro_rls';
            }
            if($tenant->utiliza_rls == 'S'){
                //VERIFICO SE O TENANT UTILIZA O RLS
                if($tenant->regra_rls == ''){
                   Alert::error('Erro', 'Verifique o RLS no cadastro da Empresa');
                   return redirect()->back();
                }

                    $regra_tenant = 'rls_tenant';
              
            }else if($tenant->utiliza_filtro == 'S'){
                     /* VERIFICO SE TEM FILTRO POR TENANT
                     */
                  if($tenant->filtro_tabela == '' || $tenant->filtro_coluna == '' || $tenant->filtro_valor == ''){
                     Alert::error('Erro', 'Verifique os filtros no cadastro da Empresa');
                     return redirect()->back();
                  }
                  $regra_tenant = 'filtro_empresa';
            }else{
                 $regra_tenant = 'sem_filtro';
            }

              //GERAR TOKEN RLS OU TOKEM SEM RLS
            if($tenant->utiliza_rls == 'S' && $relatorio->ignora_filtro_rls != 'S'){ 
            
                $resposta = GetTokenRlsPowerBiService::getTokenRlsTenant($relatorio, $tenant); 
                //verifico se tem rls no tenant e filtro no usuário
                if($user->utiliza_filtro == 'S'){
                    $regra = 'rls_tenant_filtro_usuario';
                }else{
                $regra = 'rls_tenant';
                }
                $tipo_token = 'rls'; 
             }else{
                
                 //SÓ POSSO GERAR TOKEN 1 VEZ OU POR TENANT OU POR USUÁRIO, OU POR DEPARTAMENTO.. 
                 //ENTÃO CASO O TENANT JA USA O RLS NÃO PODERÁ GERAR OUTRO TOKEN RLS PARA O USUÁRIO POR EXEMPLO..
                 switch($regra){
                    case "rls_relatorio_usuario":
                        $resposta = GetTokenRlsPowerBiService::getTokenRlsRelatorioUser($relatorio, $relatorios_user);
                        $tipo_token = 'rls'; 
                        break;
                    case "rls_relatorio_departamento":
                        $resposta = GetTokenRlsPowerBiService::getTokenRlsRelatorioDepartamento($relatorio, $relatorios_departamento);
                        $tipo_token = 'rls'; 
                        break;
                    case "rls_usuario":
                        $resposta = GetTokenRlsPowerBiService::getTokenRlsUser($relatorio, $user);
                        $tipo_token = 'rls'; 
                        break;
                    default: 
            
                     $resposta = GetTokenPowerBiService::getToken();  
                    
                     $tipo_token = 'semrls'; 
                   
                 }
             }
            if($resposta['resposta'] == 'ok'){
                $token = $resposta['token'];
                $expires_in = $resposta['expires_in'];
            }else{
               
                $erro = $resposta['error'];
               
                $token = '';
                $expires_in = 0;
                Alert::error('Erro', 'Não foi possível abrir o relatório '.$erro);
               //Alert::error('Erro', $erro);
            }
            //ALIMENTAR O HISTORICO
            $historico = HistoricoRelatoriosUser::where('relatorio_id', $relatorio->id)->first();
            if(!$historico){
                $qtd_acessos = 1;
            }else{
                $qtd_acessos = $historico->qtd_acessos + 1;
            }
        
            HistoricoRelatoriosUser::updateOrCreate(
                [
                 'relatorio_id' => $relatorio->id,
                 'user_id'  => $user->id,
                 'tenant_id' => $user->tenant_id,
                ],
                [
                'departamento_id' => $user->departamento_id,
                'relatorio_id' => $relatorio->id,
                'qtd_acessos' => $qtd_acessos,
                'ultima_hora_acessada' => $now
                ]
            );
           
            
            return view('pages.users.relatorios.visualizar', compact('relatorio', 'token', 'expires_in', 'tenant', 'user', 'departamento', 'regra', 'regra_tenant', 'relatorios_user', 'relatorios_departamento', 'tipo_token'));   
        }
    
    }

    

}

