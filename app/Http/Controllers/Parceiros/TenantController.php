<?php

namespace App\Http\Controllers\Parceiros;

use App\Models\Tenant;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Database\QueryException;
use Yajra\DataTables\Facades\DataTables;

class TenantController extends Controller
{
    public function listarTenants(Request $request){
      
        if ($request->ajax()) {
            return Datatables::of(Tenant::query())
                    ->addIndexColumn()
                    ->addColumn('action', function($tenant){

                      $botoes = '
                      <div style="display: flex; justify-content:flex-start">
                        <a href="'. route('parceiro.tenant.editar', $tenant->id) .'" class="edit btn btn-primary btn-sm">Editar</a>

                            <form action="'. route('parceiro.tenant.excluir', $tenant->id). '" style="margin-left: 3px;" method="POST">
                            '.csrf_field().'
                            '.method_field("DELETE").'
                            <button type="submit"  onclick="return confirm(\'Tem certeza que deseja excluir a empresa?\')" class="btn btn-danger btn-sm">
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
        
        return view('pages.parceiro.tenants.listar');
    }


    public function cadastrarTenant(){

      

        return view('pages.parceiro.tenants.cadastrar');
    }

    public function salvarTenant(Request $request){
       
        //valida o formulario
       $this->validate($request, [
            'nome' => 'required',
            'email_administrador' => 'required|email|unique:tenants,email_administrador|unique:parceiros,email|unique:users,email',
            'senha_administrador' => 'required|min:5'
        ], [
            'nome.required' => 'Preencha o nome!',
            'senha_administrador.required' => 'Senha não pode ficar em branco',
            'senha_administrador.min' => 'A Senha deve conter no mínimo 5 caracteres',
            'email_administrador.required' => 'Preencha o e-mail do administrador',
            'email_administrador.unique' => 'E-mail já cadastrado',
            'email_administrador.email' => 'Insira um e-mail válido'
        ]);
        $dados = $request->all();
        $utiliza_filtro = (isset($dados['utilizafiltro']) == 'on' ? 'S' : 'N');
        $utiliza_rls = (isset($dados['utiliza_rls']) == 'on' ? 'S' : 'N');
       
        DB::transaction(function () use($dados, $utiliza_filtro, $utiliza_rls) {
            //CRIANDO A EMPRESA
                $tenant = Tenant::create([
                    'nome' => $dados['nome'],
                    'email_administrador' => $dados['email_administrador'],
                    'limite_usuarios'   => $dados['limite_usuarios'],
                    'utiliza_filtro' => $utiliza_filtro,
                    'filtro_tabela' => $dados['tabela'],
                    'filtro_coluna' => $dados['coluna'],
                    'filtro_valor' => $dados['valor'],
                    'utiliza_rls'  => $utiliza_rls,
                    'regra_rls' => $dados['regra_rls'],
                    'username_rls' => $dados['username_rls']
                ]);
              //CRIANDO O DEPARTAMENTO DA EMPRESA
              $departamento = $tenant->departamentos()->create([
                'nome' => 'TI',
              ]);  
              //CRIANDO O ADMINISTRADOR DA EMPRESA
              $departamento->users()->create([
                'name' => 'Administrador',
                'email' => $tenant->email_administrador,
                'password' => bcrypt($dados['senha_administrador']),
                'is_admin' => 1,
                'troca_senha' => 'N',
                'tenant_id' => $tenant->id
              ]); 
        });  
       // return redirect()->route('parceiro.tenants')->with('toast_success', 'Empresa Cadastrada com sucesso!');
       return redirect()->route('parceiro.tenants')->with('success', 'Empresa Cadastrada com sucesso!');
    }

    public function editarTenant($id){
        $tenant = Tenant::findOrFail($id);
   
        $user = User::where('is_admin', '=', 1)->where('tenant_id', $id)->first();
        
        return view('pages.parceiro.tenants.editar', compact('tenant', 'user'));
    }

    public function atualizarTenant(Request $request, $id){

        $tenant = Tenant::find($id);
       // dd($tenant);
        //valida o formulário
        $this->validate($request, [
            'nome' => 'required',
            'email_administrador' => 'required|email|unique:tenants,email_administrador,'.$id.'|unique:parceiros,email|unique:users,email',
            'senha_administrador' => 'required|min:5'
        ], [
            'nome.required' => 'Preencha o nome!',
            'senha_administrador.required' => 'Senha não pode ficar em branco',
            'senha_administrador.min' => 'A Senha deve conter no mínimo 5 caracteres',
            'email_administrador.required' => 'Preencha o e-mail do administrador',
            'email_administrador.unique' => 'E-mail já cadastrado',
            'email_administrador.email' => 'Insira um e-mail válido'
        ]);

        $dados = $request->all();
        $utiliza_filtro = (isset($dados['utilizafiltro']) == 'on' ? 'S' : 'N');
        $utiliza_rls = (isset($dados['utiliza_rls']) == 'on' ? 'S' : 'N');
        $dados['utiliza_filtro'] = $utiliza_filtro;
        $dados['utiliza_rls'] = $utiliza_rls;    
   
        //pegando o usuário admin  
        $user = User::find($dados['usuario_admin']);
       
        $dados_user['email'] = $dados['email_administrador'];
        if ($user->password == $dados['senha_administrador']) {

            unset($dados_user['password']);
        } else {

            $dados_user['password'] = bcrypt($dados['senha_administrador']);
        }

        DB::transaction(function () use($tenant, $dados, $user, $dados_user) {
            $tenant->update($dados);  
            $user->update($dados_user);
           
        });
       
        return redirect()->route('parceiro.tenants')->with('toast_success', 'Empresa Atualizada com sucesso!');
    }

    public function excluirTenant($id){
        try{
        Tenant::find($id)->delete();

        return redirect()->route('parceiro.tenants')->with('success', 'Empresa excluida com sucesso!');
        }catch(QueryException $e){
            if ($e->errorInfo[0] == '23000') {

                return redirect()->route('parceiro.tenants')->with('toast_error', 'Empresa está sendo utilizada por algum usuário e não pode ser excluida!');
            }
        }
    }


}
