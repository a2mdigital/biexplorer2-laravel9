<?php

namespace App\Http\Controllers\Parceiros;
use Alert;
use App\Models\Tenant;
use App\Models\Relatorio;
use Illuminate\Http\Request;
use App\Models\PowerBiParceiro;
use App\Models\SubGrupoRelatorio;
use Illuminate\Support\Facades\DB;
use App\Models\RelatorioUserTenant;
use App\Http\Controllers\Controller;
use App\Models\RelatorioDepartamento;
use Illuminate\Support\Facades\Crypt;
use App\Models\GrupoRelatorioParceiro;
use App\Models\RelatorioTenantParceiro;
use Illuminate\Database\QueryException;
use Yajra\DataTables\Facades\DataTables;
use GuzzleHttp\Exception\ClientException;


class RelatorioController extends Controller
{

    public function buscarGrupos(Request $request){
 
        $grupos = [];
        if($request->has('q')){
            $search = $request->q;
            $grupos =GrupoRelatorioParceiro::select("id", "nome")
            		->where('nome', 'LIKE', "%$search%")
            		->get();
        }
        return response()->json($grupos);
    }
    public function listarGrupos(Request $request){
        $grupos = GrupoRelatorioParceiro::paginate(8);

        if ($request->ajax()) {
    		$view = view('pages.parceiro.relatorios.grupos-scroll',compact('grupos'))->render();
            return response()->json(['html'=>$view]);
        }

        return view('pages.parceiro.relatorios.grupos', compact('grupos'));
    }


    public function salvarGrupos(Request $request){
        
         //valida o formulario
       $this->validate($request, [
        'nome' => 'required',
        ], [
            'nome.required' => 'Digite o nome do Grupo'
        ]);

        $dados = $request->all();

        if(isset($dados['cor'])){
            $cor = $dados['cor'];
        }else{
            $cor = '#727cf5';
        }
        GrupoRelatorioParceiro::create([
            'nome' => $dados['nome'],
            'cor' => $cor
        ]);

        return redirect()->route('parceiro.gruposrelatorio')->with('success', 'Grupo Criado com Sucesso!');
    }

    public function listarSubGrupos($grupo){
        $grupo = GrupoRelatorioParceiro::findOrFail($grupo);
        $subgrupos = SubGrupoRelatorio::where('grp_rel_parceiro_id', '=', $grupo->id)->get();
      
        return view('pages.parceiro.relatorios.subgrupos', compact('subgrupos', 'grupo'));
    }

    public function salvarSubGrupo(Request $request){
        
        //valida o formulario
      $this->validate($request, [
       'nome' => 'required',
       ], [
           'nome.required' => 'Digite o nome do Grupo'
       ]);

       $dados = $request->all();

       if(isset($dados['cor'])){
           $cor = $dados['cor'];
       }else{
           $cor = '#727cf5';
       }
       SubGrupoRelatorio::create([
           'nome' => $dados['nome'],
           'cor' => $cor,
           'grp_rel_parceiro_id' => $dados['id_grupo']
       ]);

       return redirect()->route('parceiro.subgrupos.relatorios', $dados['id_grupo'])->with('success', 'SubGrupo Criado com Sucesso!');
   }

    //RELATORIOS
    public function listarRelatorios(Request $request, $subgrupo){
       
        $subgrupo = SubGrupoRelatorio::findOrFail($subgrupo);
        $grupo = GrupoRelatorioParceiro::findOrFail($subgrupo->grp_rel_parceiro_id);
        if ($request->ajax()) {
            return Datatables::of(Relatorio::query()->where('subgrupo_relatorio_id', '=', $subgrupo->id))
                    ->addIndexColumn()
                    ->addColumn('action', function($relatorio){

                      $botoes = '
                      <div style="display: flex; justify-content:flex-start">
                        <a href="'. route('parceiro.relatorio.editar', $relatorio->id) .'" class="edit btn btn-primary btn-sm">Editar</a>

                            <form action="'. route('parceiro.relatorio.excluir',[$relatorio->subgrupo_relatorio_id, $relatorio->id]). '" style="margin-left: 3px;" method="POST">
                            '.csrf_field().'
                            '.method_field("DELETE").'
                            <button type="submit"  onclick="return confirm(\'Tem certeza que deseja excluir o Relatório?\')" class="btn btn-danger btn-sm">
                            Excluir
                            </button>
                            </form>
                        </div>
                      ';  

                      return $botoes;
                    })
                    ->rawColumns(['action'])
                    ->make(true);
        }
        
        return view('pages.parceiro.relatorios.listar', compact('grupo', 'subgrupo'));
    }

    public function cadastrarRelatorio($subgrupo){

        $subgrupo = SubGrupoRelatorio::findOrFail($subgrupo);

        $dados_powerBiAzure = PowerBiParceiro::get()->first();
        
        if(!$dados_powerBiAzure){
            Alert::error('Erro', 'Cadastre as informações do Power BI');
            return redirect()->back();
           // return redirect()->back()->with('error', 'Cadastre as informações do Power BI');
        }
       
            $userPowerBI = $dados_powerBiAzure->user_powerbi;
            $passPowerBI = Crypt::decryptString($dados_powerBiAzure->password_powerbi);
            $clientIdAzure = $dados_powerBiAzure->client_id;
            $clientSecretAzure = $dados_powerBiAzure->client_secret;
            $diretorioIdAzure = $dados_powerBiAzure->diretorio_id;
       
          

        $client = new \GuzzleHttp\Client();
        // $url_autenticacao = 'https://login.windows.net/' . $diretorioIdAzure . '/oauth2/token';
        $url_autenticacao = 'https://login.windows.net/' . $diretorioIdAzure . '/oauth2/token';
        try {
            /** @var GuzzleHttp\Client $client **/
            $response = $client->post(
                //'https://login.windows.net/896fc9ac-5684-488b-9f0c-58b7f50b46ee/oauth2/token',
                $url_autenticacao,
                [
                    "headers" => [
                        "Accept" => "application/json"
                    ],
                    'form_params' => [
                        'resource'      => 'https://analysis.windows.net/powerbi/api',
                        'client_id'     => $clientIdAzure,
                        'client_secret' => $clientSecretAzure,
                        'grant_type'    => 'password',
                        //'grant_type' => 'client_credentials',
                        'username'      => $userPowerBI,
                        'password'      => $passPowerBI,
                        //'scope'         => 'https://analysis.windows.net/powerbi/api/.default',
                    ]
                   // 'query' => ['amr_values' => 'mfa']
                ]
            );

            $body = json_decode($response->getBody()->getContents(), true);

            $token = $body['access_token'];

            $client2 = new \GuzzleHttp\Client();
            $res = $client2->request(
                'GET',
                'https://api.powerbi.com/v1.0/myorg/groups',
                [
                    'headers' =>
                    [
                        'Authorization' => 'Bearer ' . $token,
                        'Accept' => 'application/json',
                        'Content-type' => 'application/json'
                    ]
                ]
            );

            $workspaces = json_decode($res->getBody()->getContents(), true);

            /*
            foreach ($workspace["value"] as $work) {
                echo $work['name'];
                echo '<br>';
            }
            */
        } catch (ClientException $e) {

            //dd($e->getMessage());
            Alert::error('Erro', 'Não foi possível se conectar com o Power BI, verifique as configurações!');
            return redirect()->back();
            //return redirect()->back()->with('error', 'Não foi possível se conectar com o Power BI, verifique as configurações!');

            // return ['error' => $e->getMessage()];
            //return response()->json(["resposta" => $e->getMessage()]);
        }

     
        //fim pegar workspaces
        return view('pages.parceiro.relatorios.cadastrar', compact('subgrupo', 'workspaces'));
    }


    public function salvarRelatorio(Request $request){
       
        $dados = $request->all();
       
        if($dados['tipo'] == 'relatorio'){
          //valida o formulario
          $this->validate($request, [
            'nome' => 'required',
            'workspace_id' => 'required',
            'report_id' => 'required',
            ], [
                'nome.required' => 'Digite um Nome para o Relatório',
                'workspace_id.required' => 'Selecione um Workspace',
                'report_id.required' => 'Selecione um Relatório',
            ]);
        }
        if($dados['tipo'] == 'dashboard'){
            //valida o formulario
            $this->validate($request, [
              'nome' => 'required',
              'workspace_id' => 'required',
              'dashboard_id' => 'required'
              ], [
                  'nome.required' => 'Digite um Nome para o Relatório',
                  'workspace_id.required' => 'Selecione um Workspace',
                  'dashboard_id.required' => 'Selecione um Dashboard',
              ]);
          }

      $filtro_lateral = (isset($dados['filtro_lateral']) == 'on' ? 'S' : 'N');    
       //TIPO DE RELATORIO
       $tipo = $dados['tipo'];
       if($tipo == 'relatorio'){
           $report_id = $dados['report_id'];
       }else{
           $report_id = $dados['dashboard_id'];
       }
       $utiliza_filtro = (isset($dados['utilizafiltro']) == 'on' ? 'S' : 'N');   
       $utiliza_rls = (isset($dados['utiliza_rls']) == 'on' ? 'S' : 'N');   
       if($utiliza_filtro == 'N' && $utiliza_rls == 'N'){
          $utiliza_filtro_rls = 'N';
          $nivel_filtro_rls = '';
       }else if($utiliza_filtro == 'S'){
            $utiliza_filtro_rls = 'S';
            $nivel_filtro_rls = $dados['nivel_filtro'];
       }else if($utiliza_rls){
            $utiliza_filtro_rls = 'S';
            $nivel_filtro_rls = $dados['nivel_rls'];
       }
    
      Relatorio::create([
        'nome' => $dados['nome'],
        'descricao' => $dados['nome_relatorio'],
        'tipo' => $dados['tipo'],
        'utiliza_filtro_rls' => $utiliza_filtro_rls,
        'nivel_filtro_rls' => $nivel_filtro_rls,
        'filtro_lateral' => $filtro_lateral,
        'report_id' => $report_id,
        'workspace_id' => $dados['workspace_id'],
        'dataset_id' => $dados['dataset_id'],
        'subgrupo_relatorio_id' => $dados['subgrupo_relatorio_id']
      ]);

      return redirect()->route('parceiro.relatorios', $dados['subgrupo_relatorio_id'])->with('success', 'Relatório cadastrado com sucesso!');

    }

    public function editarRelatorio(Request $request, $id){
        
        $relatorio = Relatorio::findOrFail($id);

        if($relatorio->utiliza_filtro_rls == 'S'){
            $utiliza_filtro_rls = 'S';
        }else{
            $utiliza_filtro_rls = 'N';
        }
        if($relatorio->nivel_filtro_rls == 'filtro_relatorio' || $relatorio->nivel_filtro_rls == 'filtro_usuario' || $relatorio->nivel_filtro_rls == 'filtro_departamento'){
            $nivel_filtro_rls = 'filtro';    
        }else if($relatorio->nivel_filtro_rls == 'rls_relatorio' || $relatorio->nivel_filtro_rls == 'rls_usuario'){
            $nivel_filtro_rls = 'rls';  
        }else{
            $nivel_filtro_rls = '';  
        }
     
        return view('pages.parceiro.relatorios.editar', compact('relatorio', 'utiliza_filtro_rls', 'nivel_filtro_rls'));
    }

    public function atualizarRelatorio(Request $request, $id){
        //valida o formulario
        $this->validate($request, [
            'nome' => 'required',
            ], [
                'nome.required' => 'Digite um Nome para o Relatório',
            ]);

        $relatorio = Relatorio::find($id);   
        $dados = $request->all();
       
        $filtro_lateral = (isset($dados['filtro_lateral']) == 'on' ? 'S' : 'N'); 
        $utiliza_filtro = (isset($dados['utiliza_filtro']) == 'on' ? 'S' : 'N');   
        $utiliza_rls = (isset($dados['utiliza_rls']) == 'on' ? 'S' : 'N');   
        if($utiliza_filtro == 'N' && $utiliza_rls == 'N'){
           $utiliza_filtro_rls = 'N';
           $nivel_filtro_rls = '';
        }else if($utiliza_filtro == 'S'){
             $utiliza_filtro_rls = 'S';
             $nivel_filtro_rls = $dados['nivel_filtro'];
        }else if($utiliza_rls){
             $utiliza_filtro_rls = 'S';
             $nivel_filtro_rls = $dados['nivel_rls'];
        }
        $relatorio->update([
            'nome' => $dados['nome'],
            'filtro_lateral' => $filtro_lateral,
            'utiliza_filtro_rls' => $utiliza_filtro_rls,
            'nivel_filtro_rls' =>  $nivel_filtro_rls
        ]);

        return redirect()->route('parceiro.relatorios', $dados['subgrupo_relatorio_id'])->with('toast_success', 'Relatório atualizado com sucesso!');
    }

    public function excluirRelatorio($subgrupo, $id){
        try{
            Relatorio::find($id)->delete();
    
            return redirect()->route('parceiro.relatorios', $subgrupo)->with('toast_success', 'Relatório excluido com sucesso!');
            }catch(QueryException $e){
                if ($e->errorInfo[0] == '23000') {
    
                    return redirect()->route('parceiro.relatorios', $subgrupo)->with('toast_error', 'Relatório está sendo utilizado por algum usuário e não pode ser excluido!');
                }
            }
    }

    /*PERMISSOES*/
    public function permissaoRelatorio(Request $request, $id){
       $relatorio = Relatorio::findOrFail($id);
       $allTenantsPermission = RelatorioTenantParceiro::select('tenant_id')->where('relatorio_id', '=', $id)->get();
       $allTenantsPermissionArray = array();
       foreach($allTenantsPermission as $allten){
           array_push($allTenantsPermissionArray, $allten->tenant_id);
       }
       $tenants = Tenant::whereNotIn('id',  $allTenantsPermissionArray)->get();
       /*
       $RelatoriosPermissions = DB::table('relatorio_tenant')
                ->join('tenants', 'relatorio_tenant.tenant_id', '=', 'tenants.id')
                ->join('relatorios', 'relatorio_tenant.relatorio_id', '=', 'relatorios.id')
                ->select('tenants.nome', 'tenants.id as tenant_id', 'relatorios.id as relatorio_id')
                ->where('relatorio_tenant.relatorio_id', '=', $id)->get();
       */ 
      $RelatoriosPermissions = RelatorioTenantParceiro::
      join('tenants', 'relatorio_tenant.tenant_id', '=', 'tenants.id')
      ->join('relatorios', 'relatorio_tenant.relatorio_id', '=', 'relatorios.id')
      ->select('tenants.nome', 'tenants.id as tenant_id', 'relatorios.id as relatorio_id')
      ->where('relatorio_tenant.relatorio_id', '=', $id)->get();
       if ($request->ajax()) {
           return Datatables::of($RelatoriosPermissions)        
                ->addIndexColumn()
                ->addColumn('action', function($relatorioTenant){
                  $botoes = '
                  <div style="display: flex; justify-content:flex-start">
                        <form action="'. route('parceiro.relatorio.permissao.excluir',[$relatorioTenant->relatorio_id, $relatorioTenant->tenant_id]). '" style="margin-left: 3px;" method="POST">
                        '.csrf_field().'
                        '.method_field("DELETE").'
                        <button type="submit"  onclick="return confirm(\'Tem certeza que deseja excluir a Empresa do Relatório?\')" class="btn btn-danger btn-sm">
                        Excluir
                        </button>
                        </form>
                    </div>
                  ';  

                  return $botoes;
                })
                ->rawColumns(['action'])
                ->make(true);
    }

       return view('pages.parceiro.relatorios.permissoes', compact('relatorio', 'tenants'));
    }

    public function salvarPermissaoRelatorio(Request $request){
        //valida o formulario
        $this->validate($request, [
            'tenants_id' => 'required',
            ], [
                'tenants_id.required' => 'Selecione ao menos uma empresa',
            ]);
        $dados = $request->all();
        $relatorio = Relatorio::find($dados['id_relatorio']);
        foreach($dados['tenants_id'] as $tenant){
            RelatorioTenantParceiro::create([
                'relatorio_id' => $dados['id_relatorio'],
                'tenant_id' => $tenant
                ]);
        }
  
        return redirect()->route('parceiro.relatorio.permissao', $relatorio->id)->with('success', 'Permissão Adicionada!');
    }

    public function excluirPermissaoRelatorio($relatorio, $tenant){
        RelatorioTenantParceiro::where('tenant_id', '=', $tenant)->where('relatorio_id', '=', $relatorio)->delete();
        RelatorioUserTenant::withoutGlobalScopes()->where('tenant_id', '=', $tenant)->where('relatorio_id', '=', $relatorio)->delete();
        RelatorioDepartamento::withoutGlobalScopes()->where('tenant_id', '=', $tenant)->where('relatorio_id', '=', $relatorio)->delete();
        
        return redirect()->route('parceiro.relatorio.permissao', $relatorio)->with('success', 'Permissão excluida!');

    }

    public function excluirGrupo($id){
      
       try{
        GrupoRelatorioParceiro::find($id)->delete();

        return redirect()->route('parceiro.gruposrelatorio')->with('success', 'Grupo excluido com sucesso!');
        }catch(QueryException $e){
            if ($e->errorInfo[0] == '23000') {
                return redirect()->route('parceiro.gruposrelatorio')->with('toast_error', 'Grupo está sendo utilizado e não pode ser excluido!');
            }
        }
    }

    public function atualizarGrupo(Request $request){
        
        //valida o formulario
      $this->validate($request, [
       'nomeEdit' => 'required',
       ], [
           'nomeEdit.required' => 'Digite o nome do Grupo'
       ]);

       $dados = $request->all();

       if(isset($dados['corEdit'])){
           $cor = $dados['corEdit'];
       }else{
           $cor = '#727cf5';
       }
       GrupoRelatorioParceiro::where('id', '=', $dados['idGrupoEdit'])
       ->update([
           'nome' => $dados['nomeEdit'],
           'cor' => $cor
       ]);

       return redirect()->route('parceiro.gruposrelatorio')->with('toast_success', 'Grupo atualizado com Sucesso!');
   }

   public function excluirSubGrupo($id){
    $subgrupo = SubGrupoRelatorio::find($id);
    try{
     SubGrupoRelatorio::find($id)->delete();

     return redirect()->route('parceiro.subgrupos.relatorios', $subgrupo->grp_rel_parceiro_id)->with('success', 'SubGrupo excluido com Sucesso!');
     }catch(QueryException $e){
         if ($e->errorInfo[0] == '23000') {
            return redirect()->route('parceiro.subgrupos.relatorios', $subgrupo->grp_rel_parceiro_id)->with('success', 'SubGrupo está sendo utilizado e não pode ser excluido!');
         }
     }
 }

 public function atualizarSubGrupo(Request $request){
     
     //valida o formulario
   $this->validate($request, [
    'nomeEdit' => 'required',
    ], [
        'nomeEdit.required' => 'Digite o nome do SubGrupo'
    ]);

    $dados = $request->all();

    if(isset($dados['corEdit'])){
        $cor = $dados['corEdit'];
    }else{
        $cor = '#727cf5';
    }
    $subgrupo = SubGrupoRelatorio::find($dados['idSubGrupoEdit']);

    SubGrupoRelatorio::where('id', '=', $subgrupo->id)
    ->update([
        'nome' => $dados['nomeEdit'],
        'cor' => $cor
    ]);

    return redirect()->route('parceiro.subgrupos.relatorios', $subgrupo->grp_rel_parceiro_id)->with('toast_success', 'SubGrupo atualizado com Sucesso!');
}



}
