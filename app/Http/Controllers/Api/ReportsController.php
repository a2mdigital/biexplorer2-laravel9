<?php 

namespace App\Http\Controllers\Api;
use Alert;
use Carbon\Carbon;
use App\Models\Relatorio;
Use Illuminate\Http\Request;
use App\Models\TenantUser;
use App\Tenant\ManagerTenant;
use App\Models\SubGrupoRelatorio;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\App;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Gate;
use App\Models\HistoricoRelatoriosUser;
use App\Models\RelatorioUserPermission;
use App\Services\GetTokenPowerBiService;
use App\Services\GetTokenRlsPowerBiService;
use App\Models\RelatorioDepartamentoPermission;
use App\Models\RelatorioTenant;

class ReportsController extends Controller{
    public function index(Request $request, $grupo){

        $tenant = app(ManagerTenant::class)->getTenantIdentify();
        $grupo = SubGrupoRelatorio::findOrFail($grupo);
        //verifico o usuário da requisição da API..
        //se for admin eu leio os relatórios de uma forma
        //se for usuário eu leio os relatórios de outra forma
        $user = auth()->user();
        if($user->is_admin == 1){
            //return "admin";
            //se o usuário for admin verifico se ele tem permissão nesse grupo
            //caso contrário aborto
            if (! Gate::allows('listar-grupo-relatorio-admin',$grupo)) {
                return ['response' => 'forbidden', 'reports' => ''];
            }else{
                //leio todos os relatórios deste grupo
                $relatorios_tenant = RelatorioTenant::select('relatorio_id')->get();
                $RelatoriosPermissions = Relatorio::where(function($query) use ($grupo){
                    $query->where('relatorios.subgrupo_relatorio_id', '=', $grupo->id);
                })
                ->where(function($query) use ($relatorios_tenant){
                    $query->orWhereIn('relatorios.id', $relatorios_tenant);
                })->leftJoin('historico_relatorio_users as hru', function($join) use($user){
                    $join->on('relatorios.id', '=', 'hru.relatorio_id');
                    $join->where('hru.user_id', '=', $user->id);
                })
                ->select(
                        'relatorios.id as relatorio_id', 
                        'relatorios.subgrupo_relatorio_id as subgrupo_relatorio_id',
                        'relatorios.nome as nome',
                        'relatorios.descricao as descricao',
                        'relatorios.tipo as tipo',
                        'relatorios.report_id as report_id',
                        'relatorios.workspace_id as workspace_id',
                        'relatorios.dataset_id as dataset_id',
                        'relatorios.parceiro_id as parceiro_id',
                        'hru.user_id as user_id',
                        'hru.tenant_id as tenant_id',
                        'hru.departamento_id as departamento_id',
                        'hru.favorito as favorito',
                        'hru.qtd_acessos as qtd_acessos'
                        )
                ->get();
                /*
                $RelatoriosPermissions = DB::table('relatorio_tenant')
                ->join('tenants', 'relatorio_tenant.tenant_id', '=', 'tenants.id')
                ->join('relatorios', 'relatorio_tenant.relatorio_id', '=', 'relatorios.id')
                ->select('relatorios.id as id', 'relatorios.nome as nome', 'relatorios.subgrupo_relatorio_id as subgrupo_relatorio_id')
                ->where('tenants.id', '=', $tenant)
                ->where('relatorios.subgrupo_relatorio_id', '=', $grupo->id)->get();
                */
                
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
                    $query->orWhereIn('relatorios.id', $relatorios_user);
                    $query->orWhereIn('relatorios.id', $relatorios_departamento);
                })
                ->leftjoin('historico_relatorio_users as hru', 'relatorios.id', '=', 'hru.relatorio_id')
                ->where('hru.user_id', $user->id)
                ->select(
                    'relatorios.id as relatorio_id', 
                    'relatorios.subgrupo_relatorio_id as subgrupo_relatorio_id',
                    'relatorios.nome as nome',
                    'relatorios.descricao as descricao',
                    'relatorios.tipo as tipo',
                    'relatorios.report_id as report_id',
                    'relatorios.workspace_id as workspace_id',
                    'relatorios.dataset_id as dataset_id',
                    'relatorios.parceiro_id as parceiro_id',
                    'hru.user_id as user_id',
                    'hru.tenant_id as tenant_id',
                    'hru.departamento_id as departamento_id',
                    'hru.favorito as favorito',
                    'hru.qtd_acessos as qtd_acessos'
                    )
                ->get();
             
            }
        }
        
        return ['response' => 'ok', 'reports' => $RelatoriosPermissions];
    }

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
        $user = auth()->user();
        //verifico se o usuário que está acessando é admin ou não
        if($user->is_admin == 1){
           $viewReport = $this->viewReportAdmin($tenant, $relatorio);
        }else{
           $viewReport =  $this->viewReportUser($grupo, $id);
        }    

       return $viewReport;

    }

    public function viewReportAdmin($tenant, $relatorio){
       
                //verificar permissão se o relatório está disponível para a empresa
        if (! Gate::allows('permissao-visualizar-relatorio-admin',$relatorio)) {
                    return ['response' => 'error', 'msg' => 'Acesso Negado'];
        }else{
          
                   //empresa tem acesso ao relatório
                   $verifica_regra_tenant = $this->getRegraTenant();
                   /*RETORNOS */
                   /*
                   sem_filtro => Tenant não utiliza filtro
                   filtro_empresa => Tenant utiliza filtro
                   rls_tenant => Tenant utiliza RLS
                   */
                if($verifica_regra_tenant['response'] == 'ok'){
                    //se o filtro com tenant está tudo certo
                    $regra_tenant = $verifica_regra_tenant['regra_tenant'];
                }else{
                    //caso tenha alguma regra inválida mostra o erro
                    return ['response' => 'error', 'msg' => $verifica_regra_tenant['msg']];
                } 
                
                
                if($regra_tenant == 'rls_tenant'){
                    $resposta = GetTokenRlsPowerBiService::getTokenRlsTenant($relatorio, $tenant); 
                    $filtros = [];
                //ver daqui pra baixo
                }else  if($regra_tenant == 'filtro_empresa'){
                    //MONTO O FILTRO POR EMPRESA
                    $filtro_tabela_tenant = $tenant->filtro_tabela;
                    $filtro_coluna_tenant = $tenant->filtro_coluna;
                    $filtro_valor_tenant = $tenant->filtro_valor;
                    $array_filtros_tenant = explode(',', $filtro_valor_tenant);
                    $array_formatado_filtros_tenant = [];
                    foreach($array_filtros_tenant as $val_format){
                        if(is_numeric($val_format)){
                            $transforma_int = (int)$val_format;
                            array_push($array_formatado_filtros_tenant,$transforma_int);
                        }else{
                            array_push($array_formatado_filtros_tenant,$val_format);
                        }
                    }
                    $json_filtros_tenant = [
                        '$schema' => 'http://powerbi.com/product/schema#basic',
                        'target' => [
                            'table' => $filtro_tabela_tenant,
                            'column' => $filtro_coluna_tenant
                        ],
                        'operator' => 'In',
                        'values' => $array_formatado_filtros_tenant,
                        'displaySettings' => [
                            'isLockedInViewMode' => true
                        ]
                    ];
                  
                    $resposta = GetTokenPowerBiService::getToken();  
                   // $filtros = json_encode($json_filtros_tenant);
                   $filtros = $json_filtros_tenant;
                }else{
                    $resposta = GetTokenPowerBiService::getToken();  
                    $filtros = [];
                }
                //verificar se existe filtro ou não
                if(empty($filtros)){
                    $existe_filtros = 'n';
                }else{
                    $existe_filtros = 's';
                }
                //pegar o token do relatório para o admin
                if($resposta['resposta'] == 'ok'){
                   
                    $token = $resposta['token'];
                    $expires_in = $resposta['expires_in'];
                    return [
                        'response' => 'ok',
                        'tenant' => $tenant,
                        'report' => $relatorio,
                        'regra_tenant' => $regra_tenant, 
                        'regra_relatorio' => '',
                        'existe_filtros' => $existe_filtros,
                        'filtros' => $filtros,
                        'token' => $token
                    ];        
                }else{
                  
                    $erro = $resposta['error'];
                    $token = '';
                    $expires_in = 0;
                    return [
                        'response' => 'error', 
                        'msg' => 'Não foi possível obter o token', 
                        'tenant' => $tenant,
                        'existe_filtros' => $existe_filtros,
                        'filtros' =>  $filtros, 
                        'token' => '', 
                        'expires_in' => 0
                    ];
                
                }
               
        
        }//fim else empresa tem acesso ao relatório 
    }

    public function viewReportuser($grupo, $id){
                //busca a empresa do Usuário
                $tenant = TenantUser::firstOrFail();  
                //VERIFICAR SE O USUÁRIO TEM PERMISSÃO PARA ACESSAR O RELATÓRIO
                if (! Gate::allows('visualizar-relatorio-user',[$grupo, $id])) {
                    return ['response' => 'error', 'msg' => 'Acesso Negado'];
                 }else{
                     //USUÁRIO TEM ACESSO AO RELATÓRIO
                     
                     //Busca os dados do relatório
                     $relatorio = Relatorio::find($id);
                    
                     //verifica se o relatório estár permitido para o usuário
                     $relatorios_user = RelatorioUserPermission::where('relatorio_id', $id)->first();
                     //verifica se o relatório está permitido por departamento
                     $relatorios_departamento = RelatorioDepartamentoPermission::where('relatorio_id', $id)->first();
                     //pega o usuário logado
                     $user = auth()->user();
                     //pega o departamento do usuário
                     $departamento = $user->departamento()->first();
                     //VERIFICA REGRA DE FILTRO DO RELATÓRIO
                      $verifica_regra_relatorio = $this->getRegraRelatorio($id);
                        /*RETORNOS*/
                        /*
                        filtro_relatorio_departamento => Pegar filtros do relatório da permissão de departamento
                        filtro_relatorio_usuario => Pegar filtros do relatório da permissão por usuário
                        filtro_usuario => Pegar Filtros do cadastro do Usuário
                        filtro_departamento => Pegar Filtros do cadastro do departamento
                        sem_filtro_rls => Não tem nenhum filtro
        
                        rls_relatorio_usuario => Pegar Regra RLS do relatório da permissão por usuário
                        rls_relatorio_departamento => Pegar Regra RLS do relatório da permissao por departamento
                        rls_usuario => Pegar Regra RLS do cadastro do usuário
                        */
        
                     //CHAMAR A FUNÇÃO PARA GERAR O TOKEN PASSANDO O ID DO RELATÓRIO
                    // $gerarToken = $this->gerarToken($id);
                    $verifica_regra_tenant = $this->getRegraTenant();
                       /*RETORNOS */
                       /*
                       sem_filtro => Tenant não utiliza filtro
                       filtro_empresa => Tenant utiliza filtro
                       rls_tenant => Tenant utiliza RLS
                       */
                    if($verifica_regra_tenant['response'] == 'ok'){
                        //se o filtro com tenant está tudo certo
                        $regra_tenant = $verifica_regra_tenant['regra_tenant'];
                    }else{
                        //caso tenha alguma regra inválida mostra o erro
                        return ['response' => 'error', 'msg' => $verifica_regra_tenant['msg']];
                    }
                    if($verifica_regra_relatorio['response'] == 'ok'){
                        $regra_relatorio = $verifica_regra_relatorio['regra_relatorio'];
                      
                        //SE O RETORNO FOI OK É PORQUE ESTÁ TUDO CERTO COM OS FILTROS
                    }else{
                        //mostra o erro se tiver alguma regra de filtro inválida
                        return ['response' => 'error', 'msg' => $verifica_regra_relatorio['msg']];
                    }
                    $pegar_token = $this->gerarToken($regra_tenant, $regra_relatorio, $id);
                    if($pegar_token['response'] == 'ok'){
                        $token = $pegar_token['token'];
                        $expires_in = $pegar_token['expires_in'];
                    }else{
                        return ['response' => 'error', 'msg' => $pegar_token['msg']];
                    }
        
                    $pegar_filtros = $this->getReportFilter($regra_tenant, $regra_relatorio, $id);
                    $filtros = $pegar_filtros['filtros'];
                    //verificar se existe filtro ou não
                    if(empty($filtros)){
                        $existe_filtros = 'n';
                    }else{
                        $existe_filtros = 's';
                    }
                    return [
                        'response' => 'ok',
                        'tenant' => $tenant,
                        'report' => $relatorio,
                        'regra_tenant' => $regra_tenant, 
                        'regra_relatorio' => $regra_relatorio,
                        'existe_filtros' => $existe_filtros,
                        'filtros' => $filtros,
                        'token' => $token
                    ];
                 }//FIM ELSE USUÁRIO TEM ACESSO AO RELATÓRIO
    }

    public function getRegraRelatorio($id){
        $relatorio = Relatorio::find($id);
        $relatorios_user = RelatorioUserPermission::where('relatorio_id', $id)->first();
        //verifica se o relatório está permitido por departamento
        $relatorios_departamento = RelatorioDepartamentoPermission::where('relatorio_id', $id)->first();
        //pega o usuário logado
        $user = auth()->user();
        //pega o departamento do usuário
        $departamento = $user->departamento()->first();
        $regra = 'sem_filtro_rls';
     
        if($relatorio->utiliza_filtro_rls == 'S'){
 
            //RELATÓRIO UTILIZA FILTRO OU RLS
           switch($relatorio->nivel_filtro_rls){
               case "rls_relatorio":
                    if($relatorios_user != null){
                       
                        if($relatorios_user->utiliza_rls == 'S'){
                            if($relatorios_user->regra_rls == ''){
                                return ['response' => 'error', 'msg' => 'Verifique o RLS do Usuário no cadastro do Relatório', 'regra_relatorio' => ''];
                            }
                            $regra = 'rls_relatorio_usuario'; 
                        }else{
                            return ['response' => 'error', 'msg' => 'Verifique o RLS do Usuário no cadastro do Relatório', 'regra_relatorio' => ''];
                        }
                     break;
                    }else if($relatorios_departamento != null){
                            //VERIFICO SE O RELATÓRIO FOI DEFINIDO POR DEPARTAMENTO
                            if($relatorios_departamento->utiliza_rls == 'S'){
                                if($relatorios_departamento->regra_rls == ''){
                                 return ['response' => 'error', 'msg' => 'Verifique o RLS do Departamento no cadastro do Relatório', 'regra_relatorio' => ''];
                                 }
                                $regra = 'rls_relatorio_departamento'; 
                            }else{
                                return ['response' => 'error', 'msg' => 'Verifique o RLS do Departamento no cadastro do Relatório', 'regra_relatorio' => ''];
                            }
                     break;       
                    }
                    break;
                case "rls_usuario":
                    if($user->regra_rls == ''){
                        return ['response' => 'error', 'msg' => 'Verifique o RLS no cadastro do Usuário', 'regra_relatorio' => ''];
                }
                $regra = 'rls_usuario';
                break;
                case "filtro_relatorio":
                 
                    if($relatorios_user != null){
                        //VERIFICO SE O RELATÓRIO FOI DEFINIDO POR USUÁRIO
                      if($relatorios_user->utiliza_filtro == 'S'){
                            //VERIFICA SE TEM FILTRO NO RELATÓRIO PARA O USUÁRIO
                            if($relatorios_user->filtro_tabela == '' || $relatorios_user->filtro_coluna == '' || $relatorios_user->filtro_valor == ''){
                               return ['response' => 'error', 'msg' => 'Ative os filtros do Usuário no cadastro do Relatório', 'regra_relatorio' => ''];
                            }
                            $regra = 'filtro_relatorio_usuario';
                       
                        }else{
                            return ['response' => 'error', 'msg' => 'Ative os filtros do Usuário no cadastro do Relatório', 'regra_relatorio' => ''];
                      
                        } 
                     break;   
                    }else if($relatorios_departamento != null){
                        //VERIFICO SE O RELATÓRIO FOI DEFINIDO POR DEPARTAMENTO
                        //VERIFICA SE TEM FILTRO NO RELATÓRIO PARA O DEPARTAMENTO DO USUÁRIO
                        if($relatorios_departamento->utiliza_filtro == 'S'){
                            if($relatorios_departamento->filtro_tabela == '' || $relatorios_departamento->filtro_coluna == '' || $relatorios_departamento->filtro_valor == ''){
                                return ['response' => 'error', 'msg' => 'Ative os filtros do Departamento no cadastro do Relatório', 'regra_relatorio' => ''];
                            }else{
                                $regra = 'filtro_relatorio_departamento';
                            }
                           
                        }else{
                            return ['response' => 'error', 'msg' => 'Ative os filtros do Departamento no cadastro do Relatório', 'regra_relatorio' => ''];
                      
                        } 
                     break;   
                    }
                    break;
                case "filtro_usuario":
                    if($user->filtro_tabela == '' || $user->filtro_coluna == '' || $user->filtro_valor == ''){

                        return ['response' => 'error', 'msg' => 'Verifique os filtros no cadastro do Usuário', 'regra_relatorio' => ''];
                     }
                    $regra = 'filtro_usuario';
                    break;
                case "filtro_departamento":
                    if($departamento->filtro_tabela == '' || $departamento->filtro_coluna == '' || $departamento->filtro_valor == ''){
                       return ['response' => 'error', 'msg' => 'Verifique os filtros no cadastro do departamento', 'regra_relatorio' => ''];
                     }
                    $regra = 'filtro_departamento';
                    break;    
           }
           }else{
            $regra = 'sem_filtro_rls';
           }

           return ['response' => 'ok', 'msg' => '', 'regra_relatorio' => $regra];    
    }

    public function getRegraTenant(){
        $tenant = TenantUser::firstOrFail();
        if($tenant->utiliza_rls == 'S'){
            //VERIFICO SE O TENANT UTILIZA O RLS
            if($tenant->regra_rls == ''){
                return ['response' => 'error', 'msg' => 'Verifique o RLS no cadastro da Empresa', 'regra_tenant' => ''];
            }
            $regra_tenant = 'rls_tenant';
          
        }else if($tenant->utiliza_filtro == 'S'){
                 /* VERIFICO SE TEM FILTRO POR TENANT
                 */
              if($tenant->filtro_tabela == '' || $tenant->filtro_coluna == '' || $tenant->filtro_valor == ''){
                return ['response' => 'error', 'msg' => 'Verifique os filtros no cadastro da empresa', 'regra_tenant' => ''];
              }
              $regra_tenant = 'filtro_empresa';
        }else{
             $regra_tenant = 'sem_filtro';
        }
        return ['response' => 'ok', 'msg' => '', 'regra_tenant' => $regra_tenant];        
    }

    public function gerarToken($regra_tenant, $regra_relatorio, $id){
        $relatorio = Relatorio::find($id);
        $tenant = TenantUser::firstOrFail();
        //verifica se o relatório estár permitido para o usuário
        $relatorios_user = RelatorioUserPermission::where('relatorio_id', $id)->first();
        //verifica se o relatório está permitido por departamento
        $relatorios_departamento = RelatorioDepartamentoPermission::where('relatorio_id', $id)->first();
        //pega o usuário logado
        $user = auth()->user();
        //pega o departamento do usuário
        $departamento = $user->departamento()->first();

        //VERIFICAR SE TENANT UTILIZA OU NAO RLS
        /* RESPOSTA REGRA_TENANT
         sem_filtro => Tenant não utiliza filtro
         filtro_empresa => Tenant utiliza filtro
         rls_tenant => Tenant utiliza RLS
        */
        if($regra_tenant == 'rls_tenant'){
            $resposta = GetTokenRlsPowerBiService::getTokenRlsTenant($relatorio, $tenant); 
        //ver daqui pra baixo
        }else{
                         /*VALORES QUE PODEM VIR NA VARIAVEL $REGRA_RELATORIO
                         * filtro_relatorio_departamento => Pegar filtros do relatório da permissão de departamento
                            filtro_relatorio_usuario => Pegar filtros do relatório da permissão por usuário
                            filtro_usuario => Pegar Filtros do cadastro do Usuário
                            filtro_departamento => Pegar Filtros do cadastro do departamento
                            sem_filtro_rls => Não tem nenhum filtro

                            rls_relatorio_usuario => Pegar Regra RLS do relatório da permissão por usuário
                            rls_relatorio_departamento => Pegar Regra RLS do relatório da permissao por departamento
                            rls_usuario => Pegar Regra RLS do cadastro do usuário
                         */
                         //SÓ POSSO GERAR TOKEN 1 VEZ OU POR TENANT OU POR USUÁRIO, OU POR DEPARTAMENTO.. 
                         //ENTÃO CASO O TENANT JA USA O RLS NÃO PODERÁ GERAR OUTRO TOKEN RLS PARA O USUÁRIO POR EXEMPLO..
                         switch($regra_relatorio){
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
                    return [
                            'response' => 'ok', 
                            'token' => $token, 
                            'expires_in' => $expires_in
                        ];    
                    }else{
                        $erro = $resposta['error'];
                        $token = '';
                        $expires_in = 0;
                        return [
                            'response' => 'error', 
                            'msg' => 'Não foi possível obter o token', 
                            'filtros' => '', 
                            'token' => '', 
                            'expires_in' => 0
                        ];
                    
                    }
                   
    }

    public function getReportFilter($regra_tenant, $regra_relatorio, $id){
        /* RESPOSTA REGRA_TENANT
         sem_filtro => Tenant não utiliza filtro
         filtro_empresa => Tenant utiliza filtro
         rls_tenant => Tenant utiliza RLS
        */
         /*VALORES QUE PODEM VIR NA VARIAVEL $REGRA_RELATORIO
        *   filtro_relatorio_departamento => Pegar filtros do relatório da permissão de departamento
            filtro_relatorio_usuario => Pegar filtros do relatório da permissão por usuário
            filtro_usuario => Pegar Filtros do cadastro do Usuário
            filtro_departamento => Pegar Filtros do cadastro do departamento
            sem_filtro_rls => Não tem nenhum filtro
            rls_relatorio_usuario => Pegar Regra RLS do relatório da permissão por usuário
            rls_relatorio_departamento => Pegar Regra RLS do relatório da permissao por departamento
            rls_usuario => Pegar Regra RLS do cadastro do usuário
         */
        $relatorio = Relatorio::find($id);
        $tenant = TenantUser::firstOrFail();
        //verifica se o relatório estár permitido para o usuário
        $relatorios_user = RelatorioUserPermission::where('relatorio_id', $id)->first();
        //verifica se o relatório está permitido por departamento
        $relatorios_departamento = RelatorioDepartamentoPermission::where('relatorio_id', $id)->first();
        //pega o usuário logado
        $user = auth()->user();
        //pega o departamento do usuário
        $departamento = $user->departamento()->first();
                    //FILTRO HABILITADO NA EMPRESA
            if($regra_tenant == 'filtro_empresa'){
               
                        //MONTO O FILTRO POR EMPRESA
                        $filtro_tabela_tenant = $tenant->filtro_tabela;
                        $filtro_coluna_tenant = $tenant->filtro_coluna;
                        $filtro_valor_tenant = $tenant->filtro_valor;
                        $array_filtros_tenant = explode(',', $filtro_valor_tenant);
                        $array_formatado_filtros_tenant = [];
                        foreach($array_filtros_tenant as $val_format){
                          
                            if(is_numeric($val_format)){
                                $transforma_int = (int)$val_format;
                                array_push($array_formatado_filtros_tenant,$transforma_int);
                            }else{
                                array_push($array_formatado_filtros_tenant,$val_format);
                            }
                        }
                      
                        $json_filtros_tenant = [
                            '$schema' => 'http://powerbi.com/product/schema#basic',
                            'target' => [
                                'table' => $filtro_tabela_tenant,
                                'column' => $filtro_coluna_tenant
                            ],
                            'operator' => 'In',
                            'values' =>  $array_formatado_filtros_tenant,
                            'displaySettings' => [
                                'isLockedInViewMode' => true
                            ]
                        ];
                      
                    
                        switch($regra_relatorio){
                            case 'filtro_relatorio_usuario':
                             //MONTAR O ARRAY DE FILTROS PARA FILTROS DO USUÁRIO NO RELATÓRIO
                             $filtro_tabela_relatorio_user = $relatorios_user->filtro_tabela;
                             $filtro_coluna_relatorio_user = $relatorios_user->filtro_coluna;
                             $filtro_valor_relatorio_user = $relatorios_user->filtro_valor;
                             $array_filtros_relatorio_user = explode(',', $filtro_valor_relatorio_user);
                             $array_formatado_filtros_relatorio_user = [];
                             foreach($array_filtros_relatorio_user as $val_format){
                                 if(is_numeric($val_format)){
                                     $transforma_int = (int)$val_format;
                                     array_push($array_formatado_filtros_relatorio_user,$transforma_int);
                                 }else{
                                     array_push($array_formatado_filtros_relatorio_user,$val_format);
                                 }
                             }
                             $json_filtros_relatorio_user = [
                                 '$schema' => 'http://powerbi.com/product/schema#basic',
                                 'target' => [
                                     'table' => $filtro_tabela_relatorio_user,
                                     'column' => $filtro_coluna_relatorio_user
                                 ],
                                 'operator' => 'In',
                                 'values' => $array_formatado_filtros_relatorio_user,
                                 'displaySettings' => [
                                     'isLockedInViewMode' => true
                                 ]
                             ];
                             $filtros_tenant_relatorio_user = [$json_filtros_tenant,  $json_filtros_relatorio_user];
                             //$filtros = json_encode($filtros_tenant_relatorio_user);
                             $filtros = $filtros_tenant_relatorio_user;
                             break;
                             case 'filtro_relatorio_departamento':
                              //MONTAR O ARRAY DE FILTROS PARA FILTROS DO DEPARTAMENTO NO RELATÓRIO
                              $filtro_tabela_relatorio_departamento = $relatorios_departamento->filtro_tabela;
                              $filtro_coluna_relatorio_departamento = $relatorios_departamento->filtro_coluna;
                              $filtro_valor_relatorio_departamento = $relatorios_departamento->filtro_valor;
                              $array_filtros_relatorio_departamento = explode(',', $filtro_valor_relatorio_departamento);
                              $array_formatado_filtros_relatorio_departamento = [];
                              foreach($array_filtros_relatorio_departamento as $val_format){
                                  if(is_numeric($val_format)){
                                      $transforma_int = (int)$val_format;
                                      array_push($array_formatado_filtros_relatorio_departamento,$transforma_int);
                                  }else{
                                      array_push($array_formatado_filtros_relatorio_departamento,$val_format);
                                  }
                              }
                              $json_filtros_relatorio_departamento = [
                                  '$schema' => 'http://powerbi.com/product/schema#basic',
                                  'target' => [
                                      'table' => $filtro_tabela_relatorio_departamento,
                                      'column' => $filtro_coluna_relatorio_departamento
                                  ],
                                  'operator' => 'In',
                                  'values' => $array_formatado_filtros_relatorio_departamento,
                                  'displaySettings' => [
                                      'isLockedInViewMode' => true
                                  ]
                              ];
                              $filtros_tenant_relatorio_departamento = [$json_filtros_tenant,  $json_filtros_relatorio_departamento];
                              //$filtros = json_encode($filtros_tenant_relatorio_departamento);   
                              $filtros = $filtros_tenant_relatorio_departamento;
                             break;
                             case 'filtro_usuario':
                              //MONTAR O ARRAY DE FILTROS PARA FILTROS DO USUÁRIO
                              $filtro_tabela_user = $user->filtro_tabela;
                              $filtro_coluna_user = $user->filtro_coluna;
                              $filtro_valor_user = $user->filtro_valor;
                              $array_filtros_user = explode(',', $filtro_valor_user);
                              $array_formatado_filtros_user = [];
                              foreach($array_filtros_user as $val_format){
                                  if(is_numeric($val_format)){
                                      $transforma_int = (int)$val_format;
                                      array_push($array_formatado_filtros_user,$transforma_int);
                                  }else{
                                      array_push($array_formatado_filtros_user,$val_format);
                                  }
                              }
                              $json_filtros_user = [
                                  '$schema' => 'http://powerbi.com/product/schema#basic',
                                  'target' => [
                                      'table' => $filtro_tabela_user,
                                      'column' => $filtro_coluna_user
                                  ],
                                  'operator' => 'In',
                                  'values' => $array_formatado_filtros_user,
                                  'displaySettings' => [
                                      'isLockedInViewMode' => true
                                  ]
                              ];
                              $filtros_tenant_user = [$json_filtros_tenant,  $json_filtros_user];
                              //$filtros = json_encode($filtros_tenant_user);   
                              $filtros = $filtros_tenant_user;
                             break;   
                             case 'filtro_departamento':
                              //MONTAR O ARRAY DE FILTROS PARA FILTROS DO DEPARTAMENTO
                              $filtro_tabela_departamento = $departamento->filtro_tabela;
                              $filtro_coluna_departamento = $departamento->filtro_coluna;
                              $filtro_valor_departamento = $departamento->filtro_valor;
                              $array_filtros_departamento = explode(',', $filtro_valor_departamento);
                              $array_formatado_filtros_departamento = [];
                              foreach($array_filtros_departamento as $val_format){
                                  if(is_numeric($val_format)){
                                      $transforma_int = (int)$val_format;
                                      array_push($array_formatado_filtros_departamento,$transforma_int);
                                  }else{
                                      array_push($array_formatado_filtros_departamento,$val_format);
                                  }
                              }
                              $json_filtros_departamento = [
                                  '$schema' => 'http://powerbi.com/product/schema#basic',
                                  'target' => [
                                      'table' => $filtro_tabela_departamento,
                                      'column' => $filtro_coluna_departamento
                                  ],
                                  'operator' => 'In',
                                  'values' => $array_formatado_filtros_departamento,
                                  'displaySettings' => [
                                      'isLockedInViewMode' => true
                                  ]
                              ];
                              $filtros_tenant_departamento = [$json_filtros_tenant,  $json_filtros_departamento];
                             // $filtros = json_encode($filtros_tenant_departamento);  
                             $filtros =  $filtros_tenant_departamento;
                             break;
                             default:
                             //$filtros = json_encode($json_filtros_tenant);
                             $filtros = $json_filtros_tenant;
                             break;
                        }
                        //RETORNO OS FILTROS E O TOKEN
                        return [
                            'response' => 'ok', 
                            'filtros' => $filtros,    
                        ];
                   
                        //AQUI MONTO O ARRAY DE FILTROS DO TENANT + DO USUÁRIO, RELATORIO OU DEPARTAMENTO
        
            }else if($regra_tenant == 'rls_tenant'){
                     
                        //AQUI MONTAR O ARRAY DE RLS DO TENANT + FILTROS DO USUÁRIO, RELATORIO OU DEPARTAMENTO
                        switch($regra_relatorio){
                            case 'filtro_relatorio_usuario':
                             //MONTAR O ARRAY DE FILTROS PARA FILTROS DO USUÁRIO NO RELATÓRIO
                             $filtro_tabela_relatorio_user = $relatorios_user->filtro_tabela;
                             $filtro_coluna_relatorio_user = $relatorios_user->filtro_coluna;
                             $filtro_valor_relatorio_user = $relatorios_user->filtro_valor;
                             $array_filtros_relatorio_user = explode(',', $filtro_valor_relatorio_user);
                             $array_formatado_filtros_relatorio_user = [];
                              foreach($array_filtros_relatorio_user as $val_format){
                                  if(is_numeric($val_format)){
                                      $transforma_int = (int)$val_format;
                                      array_push($array_formatado_filtros_relatorio_user,$transforma_int);
                                  }else{
                                      array_push($array_formatado_filtros_relatorio_user,$val_format);
                                  }
                              }
                             $json_filtros_relatorio_user = [
                                 '$schema' => 'http://powerbi.com/product/schema#basic',
                                 'target' => [
                                     'table' => $filtro_tabela_relatorio_user,
                                     'column' => $filtro_coluna_relatorio_user
                                 ],
                                 'operator' => 'In',
                                 'values' => $array_formatado_filtros_relatorio_user,
                                 'displaySettings' => [
                                     'isLockedInViewMode' => true
                                 ]
                             ];
                             //$filtros = json_encode($json_filtros_relatorio_user);
                             $filtros = $json_filtros_relatorio_user;
                             break;
                             case 'filtro_relatorio_departamento':
                              //MONTAR O ARRAY DE FILTROS PARA FILTROS DO DEPARTAMENTO NO RELATÓRIO
                              $filtro_tabela_relatorio_departamento = $relatorios_departamento->filtro_tabela;
                              $filtro_coluna_relatorio_departamento = $relatorios_departamento->filtro_coluna;
                              $filtro_valor_relatorio_departamento = $relatorios_departamento->filtro_valor;
                              $array_filtros_relatorio_departamento = explode(',', $filtro_valor_relatorio_departamento);
                              $array_formatado_filtros_relatorio_departamento = [];
                              foreach($array_filtros_relatorio_departamento as $val_format){
                                  if(is_numeric($val_format)){
                                      $transforma_int = (int)$val_format;
                                      array_push($array_formatado_filtros_relatorio_departamento,$transforma_int);
                                  }else{
                                      array_push($array_formatado_filtros_relatorio_departamento,$val_format);
                                  }
                              }
                              $json_filtros_relatorio_departamento = [
                                  '$schema' => 'http://powerbi.com/product/schema#basic',
                                  'target' => [
                                      'table' => $filtro_tabela_relatorio_departamento,
                                      'column' => $filtro_coluna_relatorio_departamento
                                  ],
                                  'operator' => 'In',
                                  'values' => $array_formatado_filtros_relatorio_departamento,
                                  'displaySettings' => [
                                      'isLockedInViewMode' => true
                                  ]
                              ];
                             // $filtros = json_encode($json_filtros_relatorio_departamento);   
                             $filtros = $json_filtros_relatorio_departamento;
                             break;
                             case 'filtro_usuario':
                              //MONTAR O ARRAY DE FILTROS PARA FILTROS DO USUÁRIO
                              $filtro_tabela_user = $user->filtro_tabela;
                              $filtro_coluna_user = $user->filtro_coluna;
                              $filtro_valor_user = $user->filtro_valor;
                              $array_filtros_user = explode(',', $filtro_valor_user);
                              $array_formatado_filtros_user = [];
                              foreach($array_filtros_user as $val_format){
                                  if(is_numeric($val_format)){
                                      $transforma_int = (int)$val_format;
                                      array_push($array_formatado_filtros_user,$transforma_int);
                                  }else{
                                      array_push($array_formatado_filtros_user,$val_format);
                                  }
                              }
                              $json_filtros_user = [
                                  '$schema' => 'http://powerbi.com/product/schema#basic',
                                  'target' => [
                                      'table' => $filtro_tabela_user,
                                      'column' => $filtro_coluna_user
                                  ],
                                  'operator' => 'In',
                                  'values' => $array_formatado_filtros_user,
                                  'displaySettings' => [
                                      'isLockedInViewMode' => true
                                  ]
                              ];
                             // $filtros = json_encode($json_filtros_user);   
                             $filtros = $json_filtros_user;
                             break;   
                             case 'filtro_departamento':
                              //MONTAR O ARRAY DE FILTROS PARA FILTROS DO DEPARTAMENTO
                              $filtro_tabela_departamento = $departamento->filtro_tabela;
                              $filtro_coluna_departamento = $departamento->filtro_coluna;
                              $filtro_valor_departamento = $departamento->filtro_valor;
                              $array_filtros_departamento = explode(',', $filtro_valor_departamento);
                              $array_formatado_filtros_departamento = [];
                              foreach($array_filtros_departamento as $val_format){
                                  if(is_numeric($val_format)){
                                      $transforma_int = (int)$val_format;
                                      array_push($array_formatado_filtros_departamento,$transforma_int);
                                  }else{
                                      array_push($array_formatado_filtros_departamento,$val_format);
                                  }
                              }
                              $json_filtros_departamento = [
                                  '$schema' => 'http://powerbi.com/product/schema#basic',
                                  'target' => [
                                      'table' => $filtro_tabela_departamento,
                                      'column' => $filtro_coluna_departamento
                                  ],
                                  'operator' => 'In',
                                  'values' => $array_formatado_filtros_departamento,
                                  'displaySettings' => [
                                      'isLockedInViewMode' => true
                                  ]
                              ];
                              //$filtros = json_encode($json_filtros_departamento);    
                              $filtros =  $json_filtros_departamento;
                             break;
                             default:
                             //relatório não tem filtro e nem tenant tem filtro
                             $filtros = [];
                             break;
                        }
                        //RETORNO OS FILTROS E O TOKEN
                        return [
                            'response' => 'ok', 
                            'filtros' => $filtros
                        ];
            }else if($regra_tenant == 'sem_filtro'){
                       
                        //AQUI MONTAR ARRAY DE FILTROS CASO O TENANT NÃO TIVER FILTROS
                        switch($regra_relatorio){
                            case 'filtro_relatorio_usuario':
                             //MONTAR O ARRAY DE FILTROS PARA FILTROS DO USUÁRIO NO RELATÓRIO
                             $filtro_tabela_relatorio_user = $relatorios_user->filtro_tabela;
                             $filtro_coluna_relatorio_user = $relatorios_user->filtro_coluna;
                             $filtro_valor_relatorio_user = $relatorios_user->filtro_valor;
                             $array_filtros_relatorio_user = explode(',', $filtro_valor_relatorio_user);
                             $array_formatado_filtros_relatorio_user= [];
                             foreach($array_filtros_relatorio_user as $val_format){
                                 if(is_numeric($val_format)){
                                     $transforma_int = (int)$val_format;
                                     array_push($array_formatado_filtros_relatorio_user,$transforma_int);
                                 }else{
                                     array_push($array_formatado_filtros_relatorio_user,$val_format);
                                 }
                             }
                             $json_filtros_relatorio_user = [
                                 '$schema' => 'http://powerbi.com/product/schema#basic',
                                 'target' => [
                                     'table' => $filtro_tabela_relatorio_user,
                                     'column' => $filtro_coluna_relatorio_user
                                 ],
                                 'operator' => 'In',
                                 'values' => $array_formatado_filtros_relatorio_user,
                                 'displaySettings' => [
                                     'isLockedInViewMode' => true
                                 ]
                             ];
                            // $filtros = json_encode($json_filtros_relatorio_user);
                            $filtros = $json_filtros_relatorio_user;
                             break;
                             case 'filtro_relatorio_departamento':
                              //MONTAR O ARRAY DE FILTROS PARA FILTROS DO DEPARTAMENTO NO RELATÓRIO
                              $filtro_tabela_relatorio_departamento = $relatorios_departamento->filtro_tabela;
                              $filtro_coluna_relatorio_departamento = $relatorios_departamento->filtro_coluna;
                              $filtro_valor_relatorio_departamento = $relatorios_departamento->filtro_valor;
                              $array_filtros_relatorio_departamento = explode(',', $filtro_valor_relatorio_departamento);
                              $array_formatado_filtros_relatorio_departamento = [];
                              foreach($array_filtros_relatorio_departamento as $val_format){
                                  if(is_numeric($val_format)){
                                      $transforma_int = (int)$val_format;
                                      array_push($array_formatado_filtros_relatorio_departamento,$transforma_int);
                                  }else{
                                      array_push($array_formatado_filtros_relatorio_departamento,$val_format);
                                  }
                              }
                              $json_filtros_relatorio_departamento = [
                                  '$schema' => 'http://powerbi.com/product/schema#basic',
                                  'target' => [
                                      'table' => $filtro_tabela_relatorio_departamento,
                                      'column' => $filtro_coluna_relatorio_departamento
                                  ],
                                  'operator' => 'In',
                                  'values' => $array_formatado_filtros_relatorio_departamento,
                                  'displaySettings' => [
                                      'isLockedInViewMode' => true
                                  ]
                              ];
                              //$filtros = json_encode($json_filtros_relatorio_departamento);   
                              $filtros = $json_filtros_relatorio_departamento;
                             break;
                             case 'filtro_usuario':
                              //MONTAR O ARRAY DE FILTROS PARA FILTROS DO USUÁRIO
                              $filtro_tabela_user = $user->filtro_tabela;
                              $filtro_coluna_user = $user->filtro_coluna;
                              $filtro_valor_user = $user->filtro_valor;
                              $array_filtros_user = explode(',', $filtro_valor_user);
                              $array_formatado_filtros_user = [];
                              foreach($array_filtros_user as $val_format){
                                  if(is_numeric($val_format)){
                                      $transforma_int = (int)$val_format;
                                      array_push($array_formatado_filtros_user,$transforma_int);
                                  }else{
                                      array_push($array_formatado_filtros_user,$val_format);
                                  }
                              }
                              $json_filtros_user = [
                                  '$schema' => 'http://powerbi.com/product/schema#basic',
                                  'target' => [
                                      'table' => $filtro_tabela_user,
                                      'column' => $filtro_coluna_user
                                  ],
                                  'operator' => 'In',
                                  'values' => $array_formatado_filtros_user,
                                  'displaySettings' => [
                                      'isLockedInViewMode' => true
                                  ]
                              ];
                              //$filtros = json_encode($json_filtros_user);   
                              $filtros = $json_filtros_user;
                             break;   
                             case 'filtro_departamento':
                              //MONTAR O ARRAY DE FILTROS PARA FILTROS DO DEPARTAMENTO
                              $filtro_tabela_departamento = $departamento->filtro_tabela;
                              $filtro_coluna_departamento = $departamento->filtro_coluna;
                              $filtro_valor_departamento = $departamento->filtro_valor;
                              $array_filtros_departamento = explode(',', $filtro_valor_departamento);
                              $array_formatado_filtros_departamento = [];
                              foreach($array_filtros_departamento as $val_format){
                                  if(is_numeric($val_format)){
                                      $transforma_int = (int)$val_format;
                                      array_push($array_formatado_filtros_departamento,$transforma_int);
                                  }else{
                                      array_push($array_formatado_filtros_departamento,$val_format);
                                  }
                              }
                              $json_filtros_departamento = [
                                  '$schema' => 'http://powerbi.com/product/schema#basic',
                                  'target' => [
                                      'table' => $filtro_tabela_departamento,
                                      'column' => $filtro_coluna_departamento
                                  ],
                                  'operator' => 'In',
                                  'values' => $array_formatado_filtros_departamento,
                                  'displaySettings' => [
                                      'isLockedInViewMode' => true
                                  ]
                              ];
                              //$filtros = json_encode($json_filtros_departamento);      
                              $filtros = $json_filtros_departamento;
                             break;
                             default:
                             //relatório não tem filtro e nem tenant tem filtro
                             $filtros = [];
                             break;
                        }
                        //RETORNO OS FILTROS E O TOKEN
                        return [
                            'response' => 'ok', 
                            'filtros' => $filtros, 
                          
                        ];
                    }//fim else regra_tenant tem filtros
    }

    //VISUALIZAR RELATORIO
    /*
    public function viewReportFilter($grupo, $id){ 

        if (! Gate::allows('visualizar-relatorio-user',[$grupo, $id])) {
           return ['response' => 'error', 'msg' => 'Acesso Negado'];
        }else{
            //pegar o local que está acessando o relatório 
             // para definir o timezone
            
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
                                 //   Alert::error('Erro', 'Verifique o RLS do Usuário no cadastro do Relatório');
                                 //   return redirect()->back();
                                }
                                $regra = 'rls_relatorio_usuario'; 
                            }else{
                             //   Alert::error('Erro', 'Ative o RLS do Usuário no cadastro do Relatório');
                             //   return redirect()->back();
                            }
                         break;
                        }else if($relatorios_departamento != null){
                                //VERIFICO SE O RELATÓRIO FOI DEFINIDO POR DEPARTAMENTO
                                if($relatorios_departamento->utiliza_rls == 'S'){
                                    if($relatorios_departamento->regra_rls == ''){
                                     //   Alert::error('Erro', 'Verifique o RLS do Departamento no cadastro do Relatório');
                                     //   return redirect()->back();
                                     }
                                    $regra = 'rls_relatorio_departamento'; 
                                }else{
                                  //  Alert::error('Erro', 'Ative o RLS do Departamento no cadastro do Relatório');
                                  //   return redirect()->back();
                                }
                         break;       
                        }
                        break;
                    case "rls_usuario":
                        if($user->regra_rls == ''){
                         //   Alert::error('Erro', 'Verifique os RLS no cadastro do Usuário');
                         //   return redirect()->back();
                        }
                        $regra = 'rls_usuario';
                        break;
                    case "filtro_relatorio":
                     
                        if($relatorios_user != null){
                            //VERIFICO SE O RELATÓRIO FOI DEFINIDO POR USUÁRIO
                          if($relatorios_user->utiliza_filtro == 'S'){
                                //VERIFICA SE TEM FILTRO NO RELATÓRIO PARA O USUÁRIO
                                if($relatorios_user->filtro_tabela == '' || $relatorios_user->filtro_coluna == '' || $relatorios_user->filtro_valor == ''){
                                  //  Alert::error('Erro', 'Verifique os filtros do Usuário no cadastro do Relatório');
                                  //  return redirect()->back();
                                }
                                $regra = 'filtro_relatorio_usuario';
                           
                            }else{
                                return ['response' => 'error', 'msg' => 'Ative os filtros do Usuário no cadastro do Relatório'];
                          
                            } 
                         break;   
                        }else if($relatorios_departamento != null){
                            //VERIFICO SE O RELATÓRIO FOI DEFINIDO POR DEPARTAMENTO
                            //VERIFICA SE TEM FILTRO NO RELATÓRIO PARA O DEPARTAMENTO DO USUÁRIO
                            if($relatorios_departamento->utiliza_filtro == 'S'){
                                if($relatorios_departamento->filtro_tabela == '' || $relatorios_departamento->filtro_coluna == '' || $relatorios_departamento->filtro_valor == ''){
                                    return ['response' => 'error', 'msg' => 'Ative os filtros do Departamento no cadastro do Relatório'];
                                }else{
                                    $regra = 'filtro_relatorio_departamento';
                                }
                               
                            }
                         break;   
                        }
                        break;
                    case "filtro_usuario":
                        if($user->filtro_tabela == '' || $user->filtro_coluna == '' || $user->filtro_valor == ''){

                            return ['response' => 'error', 'msg' => 'Verifique os filtros no cadastro do Usuário'];
                         }
                        $regra = 'filtro_usuario';
                        break;
                    case "filtro_departamento":
                        if($departamento->filtro_tabela == '' || $departamento->filtro_coluna == '' || $departamento->filtro_valor == ''){
                           return ['response' => 'error', 'msg' => 'Verifique os filtros no cadastro do departamento'];
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
                    return ['response' => 'error', 'msg' => 'Verifique o RLS no cadastro da Empresa'];
                }

                    $regra_tenant = 'rls_tenant';
              
            }else if($tenant->utiliza_filtro == 'S'){
                     // VERIFICO SE TEM FILTRO POR TENANT
                     
                  if($tenant->filtro_tabela == '' || $tenant->filtro_coluna == '' || $tenant->filtro_valor == ''){
                    return ['response' => 'error', 'msg' => 'Verifique os filtros no cadastro da empresa'];
                  }
                  $regra_tenant = 'filtro_empresa';
            }else{
                 $regra_tenant = 'sem_filtro';
            }

              //GERAR TOKEN RLS OU TOKEM SEM RLS
            if($tenant->utiliza_rls == 'S'){ 
                $resposta = GetTokenRlsPowerBiService::getTokenRlsTenant($relatorio, $tenant); 
                //verifico se tem rls no tenant e filtro no usuário
                if($relatorios_user->utiliza_filtro == 'S'){
                    $regra = 'rls_tenant_filtro_relatorio_user';
                  }else{
                    if($user->utiliza_filtro == 'S'){
                        $regra = 'rls_tenant_filtro_usuario';
                    }else{
                        //não tem filtro no relatório para o usuário e nem no cadastro do usuário
                        //ver se existe filtro no departamento na permissão do relatório
                        if($relatorios_departamento != null){
                            if($relatorios_departamento->utiliza_filtro == 'S'){
                         
                                $regra = 'rls_tenant_filtro_relatorio_departamento';
                               
                              }
                         }else{
                           
                              //TEM FILTRO PARA O DEPARTAMENTO NA PERMISSÃO DO RELATÓRIO
                              if($departamento->utiliza_filtro == 'S'){
                                $regra = 'rls_tenant_filtro_departamento';
                              }else{
                                  $regra = 'rls_tenant';
                              }

                          }
                    //$regra = 'rls_tenant';
                    }
                    
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
                return [
                    'response' => 'error', 
                    'msg' => 'Não foi possível obter o token', 
                    'filtros' => '', 
                    'token' => '', 
                    'expires_in' => 0
                ];
            
            }
            //FILTRO HABILITADO NA EMPRESA
            if($regra_tenant == 'filtro_empresa'){
                //MONTO O FILTRO POR EMPRESA
                $filtro_tabela_tenant = $tenant->filtro_tabela;
                $filtro_coluna_tenant = $tenant->filtro_coluna;
                $filtro_valor_tenant = $tenant->filtro_valor;
                $array_filtros_tenant = explode(',', $filtro_valor_tenant);
                $json_filtros_tenant = [
                    '$schema' => 'http://powerbi.com/product/schema#basic',
                    'target' => [
                        'table' => $filtro_tabela_tenant,
                        'column' => $filtro_coluna_tenant
                    ],
                    'operator' => 'In',
                    'values' => $array_filtros_tenant,
                    'displaySettings' => [
                        'isLockedInViewMode' => true
                    ]
                ];
                //filtros do tenant
                //$filtros_tenant = json_encode($json_filtros_tenant);
            
                switch($regra){
                    case 'filtro_relatorio_usuario':
                     //MONTAR O ARRAY DE FILTROS PARA FILTROS DO USUÁRIO NO RELATÓRIO
                     $filtro_tabela_relatorio_user = $relatorios_user->filtro_tabela;
                     $filtro_coluna_relatorio_user = $relatorios_user->filtro_coluna;
                     $filtro_valor_relatorio_user = $relatorios_user->filtro_valor;
                     $array_filtros_relatorio_user = explode(',', $filtro_valor_relatorio_user);
                     $json_filtros_relatorio_user = [
                         '$schema' => 'http://powerbi.com/product/schema#basic',
                         'target' => [
                             'table' => $filtro_tabela_relatorio_user,
                             'column' => $filtro_coluna_relatorio_user
                         ],
                         'operator' => 'In',
                         'values' => $array_filtros_relatorio_user,
                         'displaySettings' => [
                             'isLockedInViewMode' => true
                         ]
                     ];
                     $filtros_tenant_relatorio_user = [$json_filtros_tenant,  $json_filtros_relatorio_user];
                     $filtros = json_encode($filtros_tenant_relatorio_user);
                     break;
                     case 'filtro_relatorio_departamento':
                      //MONTAR O ARRAY DE FILTROS PARA FILTROS DO DEPARTAMENTO NO RELATÓRIO
                      $filtro_tabela_relatorio_departamento = $relatorios_departamento->filtro_tabela;
                      $filtro_coluna_relatorio_departamento = $relatorios_departamento->filtro_coluna;
                      $filtro_valor_relatorio_departamento = $relatorios_departamento->filtro_valor;
                      $array_filtros_relatorio_departamento = explode(',', $filtro_valor_relatorio_departamento);
                      $json_filtros_relatorio_departamento = [
                          '$schema' => 'http://powerbi.com/product/schema#basic',
                          'target' => [
                              'table' => $filtro_tabela_relatorio_departamento,
                              'column' => $filtro_coluna_relatorio_departamento
                          ],
                          'operator' => 'In',
                          'values' => $array_filtros_relatorio_departamento,
                          'displaySettings' => [
                              'isLockedInViewMode' => true
                          ]
                      ];
                      $filtros_tenant_relatorio_departamento = [$json_filtros_tenant,  $json_filtros_relatorio_departamento];
                      $filtros = json_encode($filtros_tenant_relatorio_departamento);   
                     break;
                     case 'filtro_usuario':
                      //MONTAR O ARRAY DE FILTROS PARA FILTROS DO USUÁRIO
                      $filtro_tabela_user = $user->filtro_tabela;
                      $filtro_coluna_user = $user->filtro_coluna;
                      $filtro_valor_user = $user->filtro_valor;
                      $array_filtros_user = explode(',', $filtro_valor_user);
                      $json_filtros_user = [
                          '$schema' => 'http://powerbi.com/product/schema#basic',
                          'target' => [
                              'table' => $filtro_tabela_user,
                              'column' => $filtro_coluna_user
                          ],
                          'operator' => 'In',
                          'values' => $array_filtros_user,
                          'displaySettings' => [
                              'isLockedInViewMode' => true
                          ]
                      ];
                      $filtros_tenant_user = [$json_filtros_tenant,  $json_filtros_user];
                      $filtros = json_encode($filtros_tenant_user);   
                     break;   
                     case 'filtro_departamento':
                      //MONTAR O ARRAY DE FILTROS PARA FILTROS DO DEPARTAMENTO
                      $filtro_tabela_departamento = $departamento->filtro_tabela;
                      $filtro_coluna_departamento = $departamento->filtro_coluna;
                      $filtro_valor_departamento = $departamento->filtro_valor;
                      $array_filtros_departamento = explode(',', $filtro_valor_departamento);
                      $json_filtros_departamento = [
                          '$schema' => 'http://powerbi.com/product/schema#basic',
                          'target' => [
                              'table' => $filtro_tabela_departamento,
                              'column' => $filtro_coluna_departamento
                          ],
                          'operator' => 'In',
                          'values' => $array_filtros_departamento,
                          'displaySettings' => [
                              'isLockedInViewMode' => true
                          ]
                      ];
                      $filtros_tenant_departamento = [$json_filtros_tenant,  $json_filtros_departamento];
                      $filtros = json_encode($filtros_tenant_departamento);      
                     break;
                     default:
                     $filtros = json_encode($json_filtros_tenant);
                     break;
                }
                //RETORNO OS FILTROS E O TOKEN
                return [
                    'response' => 'ok', 
                    'msg' => '', 
                    'filtros' => $filtros, 
                    'token' => $token, 
                    'expires_in' => $expires_in
                ];
           
                //AQUI MONTO O ARRAY DE FILTROS DO TENANT + DO USUÁRIO, RELATORIO OU DEPARTAMENTO

            }else if($regra_tenant == 'rls_tenant'){
             
                //AQUI MONTAR O ARRAY DE RLS DO TENANT + FILTROS DO USUÁRIO, RELATORIO OU DEPARTAMENTO
                switch($regra){
                    case 'rls_tenant_filtro_relatorio_user':
                     //MONTAR O ARRAY DE FILTROS PARA FILTROS DO USUÁRIO NO RELATÓRIO
                     $filtro_tabela_relatorio_user = $relatorios_user->filtro_tabela;
                     $filtro_coluna_relatorio_user = $relatorios_user->filtro_coluna;
                     $filtro_valor_relatorio_user = $relatorios_user->filtro_valor;
                     $array_filtros_relatorio_user = explode(',', $filtro_valor_relatorio_user);
                     $json_filtros_relatorio_user = [
                         '$schema' => 'http://powerbi.com/product/schema#basic',
                         'target' => [
                             'table' => $filtro_tabela_relatorio_user,
                             'column' => $filtro_coluna_relatorio_user
                         ],
                         'operator' => 'In',
                         'values' => $array_filtros_relatorio_user,
                         'displaySettings' => [
                             'isLockedInViewMode' => true
                         ]
                     ];
                     $filtros = json_encode($json_filtros_relatorio_user);
                     break;
                     case 'rls_tenant_filtro_relatorio_departamento':
                      //MONTAR O ARRAY DE FILTROS PARA FILTROS DO DEPARTAMENTO NO RELATÓRIO
                      $filtro_tabela_relatorio_departamento = $relatorios_departamento->filtro_tabela;
                      $filtro_coluna_relatorio_departamento = $relatorios_departamento->filtro_coluna;
                      $filtro_valor_relatorio_departamento = $relatorios_departamento->filtro_valor;
                      $array_filtros_relatorio_departamento = explode(',', $filtro_valor_relatorio_departamento);
                      $json_filtros_relatorio_departamento = [
                          '$schema' => 'http://powerbi.com/product/schema#basic',
                          'target' => [
                              'table' => $filtro_tabela_relatorio_departamento,
                              'column' => $filtro_coluna_relatorio_departamento
                          ],
                          'operator' => 'In',
                          'values' => $array_filtros_relatorio_departamento,
                          'displaySettings' => [
                              'isLockedInViewMode' => true
                          ]
                      ];
                      $filtros = json_encode($json_filtros_relatorio_departamento);   
                     break;
                     case 'rls_tenant_filtro_usuario':
                      //MONTAR O ARRAY DE FILTROS PARA FILTROS DO USUÁRIO
                      $filtro_tabela_user = $user->filtro_tabela;
                      $filtro_coluna_user = $user->filtro_coluna;
                      $filtro_valor_user = $user->filtro_valor;
                      $array_filtros_user = explode(',', $filtro_valor_user);
                      $json_filtros_user = [
                          '$schema' => 'http://powerbi.com/product/schema#basic',
                          'target' => [
                              'table' => $filtro_tabela_user,
                              'column' => $filtro_coluna_user
                          ],
                          'operator' => 'In',
                          'values' => $array_filtros_user,
                          'displaySettings' => [
                              'isLockedInViewMode' => true
                          ]
                      ];
                      $filtros = json_encode($json_filtros_user);   
                     break;   
                     case 'rls_tenant_filtro_departamento':
                      //MONTAR O ARRAY DE FILTROS PARA FILTROS DO DEPARTAMENTO
                      $filtro_tabela_departamento = $departamento->filtro_tabela;
                      $filtro_coluna_departamento = $departamento->filtro_coluna;
                      $filtro_valor_departamento = $departamento->filtro_valor;
                      $array_filtros_departamento = explode(',', $filtro_valor_departamento);
                      $json_filtros_departamento = [
                          '$schema' => 'http://powerbi.com/product/schema#basic',
                          'target' => [
                              'table' => $filtro_tabela_departamento,
                              'column' => $filtro_coluna_departamento
                          ],
                          'operator' => 'In',
                          'values' => $array_filtros_departamento,
                          'displaySettings' => [
                              'isLockedInViewMode' => true
                          ]
                      ];
                      $filtros = json_encode($json_filtros_departamento);      
                     break;
                     default:
                     //relatório não tem filtro e nem tenant tem filtro
                     $filtros = [];
                     break;
                }
                //RETORNO OS FILTROS E O TOKEN
                return [
                    'response' => 'ok', 
                    'msg' => '', 
                    'filtros' => $filtros, 
                    'token' => $token, 
                    'expires_in' => $expires_in
                ];
            }else if($regra_tenant == 'sem_filtro'){
               
                //AQUI MONTAR ARRAY DE FILTROS CASO O TENANT NÃO TIVER FILTROS
                switch($regra){
                    case 'filtro_relatorio_usuario':
                     //MONTAR O ARRAY DE FILTROS PARA FILTROS DO USUÁRIO NO RELATÓRIO
                     $filtro_tabela_relatorio_user = $relatorios_user->filtro_tabela;
                     $filtro_coluna_relatorio_user = $relatorios_user->filtro_coluna;
                     $filtro_valor_relatorio_user = $relatorios_user->filtro_valor;
                     $array_filtros_relatorio_user = explode(',', $filtro_valor_relatorio_user);
                     $json_filtros_relatorio_user = [
                         '$schema' => 'http://powerbi.com/product/schema#basic',
                         'target' => [
                             'table' => $filtro_tabela_relatorio_user,
                             'column' => $filtro_coluna_relatorio_user
                         ],
                         'operator' => 'In',
                         'values' => $array_filtros_relatorio_user,
                         'displaySettings' => [
                             'isLockedInViewMode' => true
                         ]
                     ];
                     $filtros = json_encode($json_filtros_relatorio_user);
                     break;
                     case 'filtro_relatorio_departamento':
                      //MONTAR O ARRAY DE FILTROS PARA FILTROS DO DEPARTAMENTO NO RELATÓRIO
                      $filtro_tabela_relatorio_departamento = $relatorios_departamento->filtro_tabela;
                      $filtro_coluna_relatorio_departamento = $relatorios_departamento->filtro_coluna;
                      $filtro_valor_relatorio_departamento = $relatorios_departamento->filtro_valor;
                      $array_filtros_relatorio_departamento = explode(',', $filtro_valor_relatorio_departamento);
                      $json_filtros_relatorio_departamento = [
                          '$schema' => 'http://powerbi.com/product/schema#basic',
                          'target' => [
                              'table' => $filtro_tabela_relatorio_departamento,
                              'column' => $filtro_coluna_relatorio_departamento
                          ],
                          'operator' => 'In',
                          'values' => $array_filtros_relatorio_departamento,
                          'displaySettings' => [
                              'isLockedInViewMode' => true
                          ]
                      ];
                      $filtros = json_encode($json_filtros_relatorio_departamento);   
                     break;
                     case 'filtro_usuario':
                      //MONTAR O ARRAY DE FILTROS PARA FILTROS DO USUÁRIO
                      $filtro_tabela_user = $user->filtro_tabela;
                      $filtro_coluna_user = $user->filtro_coluna;
                      $filtro_valor_user = $user->filtro_valor;
                      $array_filtros_user = explode(',', $filtro_valor_user);
                      $json_filtros_user = [
                          '$schema' => 'http://powerbi.com/product/schema#basic',
                          'target' => [
                              'table' => $filtro_tabela_user,
                              'column' => $filtro_coluna_user
                          ],
                          'operator' => 'In',
                          'values' => $array_filtros_user,
                          'displaySettings' => [
                              'isLockedInViewMode' => true
                          ]
                      ];
                      $filtros = json_encode($json_filtros_user);   
                     break;   
                     case 'filtro_departamento':
                      //MONTAR O ARRAY DE FILTROS PARA FILTROS DO DEPARTAMENTO
                      $filtro_tabela_departamento = $departamento->filtro_tabela;
                      $filtro_coluna_departamento = $departamento->filtro_coluna;
                      $filtro_valor_departamento = $departamento->filtro_valor;
                      $array_filtros_departamento = explode(',', $filtro_valor_departamento);
                      $json_filtros_departamento = [
                          '$schema' => 'http://powerbi.com/product/schema#basic',
                          'target' => [
                              'table' => $filtro_tabela_departamento,
                              'column' => $filtro_coluna_departamento
                          ],
                          'operator' => 'In',
                          'values' => $array_filtros_departamento,
                          'displaySettings' => [
                              'isLockedInViewMode' => true
                          ]
                      ];
                      $filtros = json_encode($json_filtros_departamento);      
                     break;
                     default:
                     //relatório não tem filtro e nem tenant tem filtro
                     $filtros = [];
                     break;
                }
                //RETORNO OS FILTROS E O TOKEN
                return [
                    'response' => 'ok', 
                    'msg' => '', 
                    'filtros' => $filtros, 
                    'token' => $token, 
                    'expires_in' => $expires_in
                ];
            }//fim else regra_tenant tem filtros
       
          
           //return ['regra' => $regra];
            
           // return view('pages.users.relatorios.visualizar', compact('relatorio', 'token', 'expires_in', 'tenant', 'user', 'departamento', 'regra', 'regra_tenant', 'relatorios_user', 'relatorios_departamento', 'tipo_token'));   
        }
    
    }
    */
    //FIM VISUALIZAR RELATORIO
}
