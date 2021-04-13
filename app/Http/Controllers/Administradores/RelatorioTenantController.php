<?php

namespace App\Http\Controllers\Administradores;
use App\Models\User;
Use Alert;
use App\Models\Tenant;
use App\Models\Relatorio;
use App\Models\TenantUser;
use App\Models\UserTenant;
use App\Models\Departamento;
use Illuminate\Http\Request;
use App\Models\RelatorioUser;
use App\Tenant\ManagerTenant;
use App\Models\RelatorioTenant;
use App\Models\SubGrupoRelatorio;
use App\Models\DepartamentoTenant;
use Illuminate\Support\Facades\DB;
use App\Models\RelatorioUserTenant;
use App\Http\Controllers\Controller;
use App\Models\HistoricoRelatoriosUser;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Gate;
use App\Models\RelatorioDepartamento;
use App\Services\GetTokenPowerBiService;
use Yajra\DataTables\Facades\DataTables;
use App\Services\GetTokenRlsPowerBiService;


class RelatorioTenantController extends Controller
{
    public function listarGrupos(){
       
      
      
        //buscar relatórios da empresa
        $relatorios_tenant = RelatorioTenant::select('relatorio_id')->get();



        $allRelatoriosPermission = Relatorio::whereIn('id', $relatorios_tenant)->get();
     
        $subgruposTenant = array();
        foreach($allRelatoriosPermission as $relPermission){
            array_push($subgruposTenant, $relPermission->subgrupo_relatorio_id);
        }
        //buscar grupos com relatórios liberados
        $grupos = SubGrupoRelatorio::whereIn('id', $subgruposTenant)->get();

   
            return view('pages.administrador.relatorios.grupos', compact('grupos'));
     
      
       
 
    }

    public function listarRelatorios(Request $request, $grupo){
       
        $tenant = app(ManagerTenant::class)->getTenantIdentify();
        $grupo = SubGrupoRelatorio::findOrFail($grupo);
       
        if (! Gate::allows('listar-grupo-relatorio-admin',$grupo)) {
            abort(403);
        }else{

            $RelatoriosPermissions = DB::table('relatorio_tenant')
            ->join('tenants', 'relatorio_tenant.tenant_id', '=', 'tenants.id')
            ->join('relatorios', 'relatorio_tenant.relatorio_id', '=', 'relatorios.id')
            ->select('relatorios.id as id', 'relatorios.nome as nome', 'relatorios.subgrupo_relatorio_id as subgrupo_relatorio_id')
            ->where('tenants.id', '=', $tenant)
            ->where('relatorios.subgrupo_relatorio_id', '=', $grupo->id)->get();
    
            if ($request->ajax()) {
                return Datatables::of($RelatoriosPermissions)        
                        ->addIndexColumn()
                        ->addColumn('action', function($relatorio){
    
                          $botoes = '
                          <div style="display: flex; justify-content:flex-start">
                            <a href="'. route('tenant.relatorios.visualizar',[$relatorio->subgrupo_relatorio_id ,$relatorio->id]) .'" class="edit btn btn-primary btn-sm">'.trans('messages.report_button_view').'</a>
                            </div>
                          ';  
    
                          return $botoes;
                        })
                        ->rawColumns(['action'])
                        ->make(true);
            }
            
            return view('pages.administrador.relatorios.listar', compact('grupo'));

        }
        
      
    }

      /*VISUALIZAR RELATÓRIO*/
      public function visualizarRelatorio($grupo, $relatorio){

        $relatorio = Relatorio::where('id', $relatorio)->where('subgrupo_relatorio_id', $grupo)->firstOrFail();
       
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
         if($tenant->utiliza_rls == 'S'){
            $resposta = GetTokenRlsPowerBiService::getTokenRlsTenant($relatorio, $tenant);  
           
         }else{
            $resposta = GetTokenPowerBiService::getToken();  
        
         }
       
         
         if($resposta['resposta'] == 'ok'){
             $token = $resposta['token'];
         }else{
             $erro = $resposta['error'];
             $token = '';
             Alert::error('Erro', 'Não foi possível abrir o relatório');

         }
        return view('pages.administrador.relatorios.visualizar', compact('relatorio', 'token', 'tenant'));
        }
        
     }

     /*PERMISSOES USUÁRIOS*/
     public function permissaoRelatorioUsuarios(Request $request, $id){

    
        $relatorioTenant = (bool) RelatorioTenant::where('relatorio_id', '=', $id)->count();
        if($relatorioTenant){

            $relatorio = Relatorio::findOrFail($id);
            $usuariosCadastrados = RelatorioUserTenant::select('user_id')->where('relatorio_id', '=', $id)->get();
            $usuarios = UserTenant::where('is_admin', '<>', 1)
                                  ->whereNotIn('id', $usuariosCadastrados)  
                                  ->get();
        }else{
            abort(404);
        }
        if ($request->ajax()) {
            return Datatables::of(RelatorioUser::query()->join('users', 'relatorio_user.user_id', '=', 'users.id')
            ->join('relatorios', 'relatorio_user.relatorio_id', '=', 'relatorios.id')
            ->select('users.name', 'users.id as user_id', 'relatorios.id as relatorio_id', 'relatorio_user.filtro_tabela', 'relatorio_user.filtro_coluna', 'relatorio_user.filtro_valor', 'relatorio_user.regra_rls', 'relatorio_user.username_rls')
            ->where('relatorio_user.relatorio_id', '=', $id))        
                 ->addIndexColumn()
                 ->addColumn('action', function($user){
                   $botoes = '
                   <div style="display: flex; justify-content:flex-start">
                         <form action="'. route('tenant.relatorio.permissao.usuarios.excluir',[$user->relatorio_id, $user->user_id]). '" style="margin-left: 3px;" method="POST">
                         '.csrf_field().'
                         '.method_field("DELETE").'
                         <button type="submit"  onclick="return confirm(\'Tem certeza que deseja excluir o Usuário do Relatório?\')" class="btn btn-danger btn-sm">
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
 
        return view('pages.administrador.relatorios.permissoes-usuarios', compact('relatorio', 'usuarios'));
     }
 
     public function salvarPermissaoRelatorioUsuarios(Request $request){
        
         //valida o formulario
         $this->validate($request, [
             'user_id' => 'required',
             ], [
                 'user_id.required' => 'Selecione um Usuário',
             ]);
         $dados = $request->all();
         
         $utiliza_filtro = (isset($dados['utiliza_filtro']) == 'on' ? 'S' : 'N'); 
         $utiliza_rls = (isset($dados['utiliza_rls']) == 'on' ? 'S' : 'N'); 
       
             RelatorioUser::create([
                 'user_id' => $dados['user_id'],
                 'relatorio_id' => $dados['id_relatorio'],
                 'utiliza_filtro' => $utiliza_filtro,
                 'tipo_filtro' => '',
                 'filtro_tabela' => $dados['filtro_tabela'],
                 'filtro_coluna' => $dados['filtro_coluna'],
                 'filtro_valor' => $dados['filtro_valor'],
                 'utiliza_rls' => $utiliza_rls,
                 'regra_rls' => $dados['regra_rls'],
                 'username_rls' => $dados['username_rls']
                 ]);
      
   
         return redirect()->route('tenant.relatorio.permissao.usuarios', $dados['id_relatorio'])->with('success', 'Permissão Adicionada!');
     }
 
     public function excluirPermissaoRelatorio($relatorio, $usuario){
         RelatorioUserTenant::where('user_id', '=', $usuario)
                       ->where('relatorio_id', '=', $relatorio)
                       ->delete();

         //EXCLUIR O HISTÓRICO              
         HistoricoRelatoriosUser::withoutGlobalScopes() 
                                ->where('user_id', '=', $usuario)
                                ->where('relatorio_id', '=', $relatorio)
                                ->delete();                 
 
         return redirect()->route('tenant.relatorio.permissao.usuarios', $relatorio)->with('success', 'Permissão excluida!');
 
     }
 

      /*PERMISSOES DEPARTAMENTOS*/
      public function permissaoRelatorioDepartamentos(Request $request, $id){
      
        $relatorioTenant = (bool) RelatorioTenant::where('relatorio_id', '=', $id)->count();
        if($relatorioTenant){
            $relatorio = Relatorio::findOrFail($id);
            $departamentosCadastrados = RelatorioDepartamento::withoutGlobalScopes()->select('departamento_id')->where('relatorio_id', '=', $id)->get();
            $departamentos = DepartamentoTenant::whereNotIn('id', $departamentosCadastrados)  
                                            ->get();
        }else{
            abort(404);
        }
     
     
      
        if ($request->ajax()) {
            return Datatables::of(RelatorioDepartamento::query()->join('departamentos', 'departamento_relatorio.departamento_id', '=', 'departamentos.id')
            ->join('relatorios', 'departamento_relatorio.relatorio_id', '=', 'relatorios.id')
            ->select('departamentos.nome', 'departamentos.id as departamento_id', 'relatorios.id as relatorio_id', 'departamento_relatorio.filtro_tabela', 'departamento_relatorio.filtro_coluna', 'departamento_relatorio.filtro_valor', 'departamento_relatorio.regra_rls', 'departamento_relatorio.username_rls')
            ->where('departamento_relatorio.relatorio_id', '=', $id))     
                 ->addIndexColumn()
                 ->addColumn('action', function($departamento){
                   $botoes = '
                   <div style="display: flex; justify-content:flex-start">
                         <form action="'. route('tenant.relatorio.permissao.departamento.excluir',[$departamento->relatorio_id, $departamento->departamento_id]). '" style="margin-left: 3px;" method="POST">
                         '.csrf_field().'
                         '.method_field("DELETE").'
                         <button type="submit"  onclick="return confirm(\'Tem certeza que deseja excluir o Departamento do Relatório?\')" class="btn btn-danger btn-sm">
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
 
        return view('pages.administrador.relatorios.permissoes-departamentos', compact('relatorio', 'departamentos'));
     }

     public function salvarPermissaoRelatorioDepartamento(Request $request){
          //valida o formulario
          $this->validate($request, [
            'departamento_id' => 'required',
            ], [
                'departamento_id.required' => 'Selecione um Departamento',
            ]);
        $dados = $request->all();
       
        $utiliza_filtro = (isset($dados['utiliza_filtro']) == 'on' ? 'S' : 'N'); 
        $utiliza_rls = (isset($dados['utiliza_rls']) == 'on' ? 'S' : 'N'); 
      
            RelatorioDepartamento::create([
                'departamento_id' => $dados['departamento_id'],
                'relatorio_id' => $dados['id_relatorio'],
                'utiliza_filtro' => $utiliza_filtro,
                'tipo_filtro' => '',
                'filtro_tabela' => $dados['filtro_tabela'],
                'filtro_coluna' => $dados['filtro_coluna'],
                'filtro_valor' => $dados['filtro_valor'],
                'utiliza_rls' => $utiliza_rls,
                'regra_rls' => $dados['regra_rls'],
                'username_rls' => $dados['username_rls']
                ]);
     
  
        return redirect()->route('tenant.relatorio.permissao.departamentos', $dados['id_relatorio'])->with('success', 'Permissão Adicionada!');
     }

     public function excluirPermissaoRelatorioDepartamento($relatorio, $departamento){
        RelatorioDepartamento::withoutGlobalScopes()->where('departamento_id', '=', $departamento)
                            ->where('relatorio_id', '=', $relatorio)
                            ->delete();

        //EXCLUIR O HISTÓRICO              
        HistoricoRelatoriosUser::withoutGlobalScopes() 
        ->where('departamento_id', '=', $departamento)
        ->where('relatorio_id', '=', $relatorio)
        ->delete();                             
                            
        return redirect()->route('tenant.relatorio.permissao.departamentos', $relatorio)->with('success', 'Permissão excluida!');
     }

     /*FIM PERMISSOES*/

   
}

