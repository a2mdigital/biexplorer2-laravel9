<?php

namespace App\Http\Controllers\Administradores;

use App\Models\UserTenant;
use Illuminate\Support\Str;
use Illuminate\Http\Request;
use App\Tenant\ManagerTenant;
use App\Models\DepartamentoTenant;
use App\Http\Controllers\Controller;
use App\Models\TenantUser;
use Illuminate\Database\QueryException;
use Yajra\DataTables\Facades\DataTables;
use RealRashid\SweetAlert\Facades\Alert;
use Auth;
class UsuarioController extends Controller
{
   
    public function listarUsuarios(Request $request){
      
     
         if ($request->ajax()) {
            return Datatables::of( UserTenant::query()->join('departamentos', 'departamentos.id', '=', 'users.departamento_id')
            ->select('users.id as id', 'users.name', 'users.email', 'users.is_admin', 'departamentos.nome as departamento'))
                    ->addIndexColumn()
                    ->addColumn('action', function($user){
                   
                      if($user->is_admin){
                        $botoes = '
                        <div style="display: flex; justify-content:flex-start">
                          <a href="'. route('tenant.usuario.editar', $user->id) .'" class="edit btn btn-primary btn-sm">Editar</a>
                          </div>
                        ';  
                      }else{
                        $botoes = '
                        <div style="display: flex; justify-content:flex-start">
                          <a href="'. route('tenant.usuario.editar', $user->id) .'" class="edit btn btn-primary btn-sm">Editar</a>
  
                              <form action="'. route('tenant.usuario.excluir', $user->id). '" style="margin-left: 3px;" method="POST">
                              '.csrf_field().'
                              '.method_field("DELETE").'
                              <button type="submit"  onclick="return confirm(\'Tem certeza que deseja excluir o Usuário?\')" class="btn btn-danger btn-sm">
                              Excluir
                              </button>
                              </form>
                          </div>
                        ';  
                      }
                     
                 
                      return $botoes;
                    })
                    ->rawColumns(['action'])
                    ->make(true);
        }
      
        
        return view('pages.administrador.usuarios.listar');
    }

    public function cadastrarUsuario(){

        $tenant = TenantUser::first();
        $total_users_tenant = UserTenant::count();
        if($tenant->limite_usuarios > 0){
            $limite_usuarios = $tenant->limite_usuarios;
        }else{
            $limite_usuarios = 999;
        }
      
        if($total_users_tenant >= $limite_usuarios){
            Alert::error('Erro', 'Limite de Usuários Atingido');
            return redirect()->route('tenant.usuarios');
         

        }
        $departamentos = DepartamentoTenant::get();
        return view('pages.administrador.usuarios.cadastrar', compact('departamentos'));
    }

    public function salvarUsuario(Request $request){
          //valida o formulario
       $this->validate($request, [
        'name' => 'required',
        'email' => 'required|email|unique:users,email|unique:parceiros,email',
        'password' => 'required|min:5',
        'departamento_id' => 'required'
    ], [
        'name.required' => 'Preencha o nome!',
        'password.required' => 'Senha não pode ficar em branco',
        'password.min' => 'A Senha deve conter no mínimo 5 caracteres',
        'email.required' => 'Preencha o e-mail',
        'email.unique' => 'E-mail já cadastrado',
        'email.email' => 'Insira um e-mail válido',
        'departamento_id.required' => 'Selecione um Departamento'
    ]);
        $dados = $request->all();
        $troca_senha = (isset($dados['troca_senha']) == 'on' ? 'S' : 'N');
        $utiliza_filtro = (isset($dados['utilizafiltro']) == 'on' ? 'S' : 'N');
        $utiliza_rls = (isset($dados['utiliza_rls']) == 'on' ? 'S' : 'N');
        $nome_departamento = Str::ucfirst($dados['departamento_id']);
        $verifica_departamento = DepartamentoTenant::where('nome', '=', $nome_departamento)
                                             ->orWhere('id', '=', $dados['departamento_id'])
                                             ->count();   
       if($verifica_departamento > 0){
        UserTenant::create([
            'name' => $dados['name'],
            'email'=> $dados['email'],
            'password'=> bcrypt($dados['password']),
            'troca_senha'=> $troca_senha,
            'is_admin'=> 0,
            'utiliza_filtro'=> $utiliza_filtro,
            'filtro_tabela'=> $dados['tabela'],
            'filtro_coluna'=> $dados['coluna'],
            'filtro_valor'=> $dados['valor'],
            'utiliza_rls' => $utiliza_rls,
            'regra_rls' => $dados['regra_rls'],
            'username_rls' => $dados['username_rls'],
            'departamento_id'=> $dados['departamento_id']
           ]); 
     
       }else{
           $departamento = DepartamentoTenant::create([
               'nome' => $nome_departamento
           ]);

           $departamento->usersTenant()->create([
            'name' => $dados['name'],
            'email'=> $dados['email'],
            'password'=> bcrypt($dados['password']),
            'troca_senha'=> $troca_senha,
            'is_admin'=> 0,
            'utiliza_filtro'=> $utiliza_filtro,
            'filtro_tabela'=> $dados['tabela'],
            'filtro_coluna'=> $dados['coluna'],
            'filtro_valor'=> $dados['valor'],
            'utiliza_rls' => $utiliza_rls,
            'regra_rls' => $dados['regra_rls'],
            'username_rls' => $dados['username_rls'],
           ]);
       }

       return redirect()->route('tenant.usuarios')->with('success', 'Usuário Cadastrado com sucesso!');

    }

    public function editarUsuario($id){

        $usuario = UserTenant::findOrFail($id);
        $departamentos = DepartamentoTenant::get();
       
        return view('pages.administrador.usuarios.editar', compact('usuario', 'departamentos'));
    }

    public function atualizarUsuario(Request $request, $id){
        $this->validate($request, [
            'name' => 'required',
            'email' => 'required|email|unique:users,email,'.$id.'|unique:parceiros,email',
            'password' => 'required|min:5',
            'departamento_id' => 'required'
        ], [
            'name.required' => 'Preencha o nome!',
            'password.required' => 'Senha não pode ficar em branco',
            'password.min' => 'A Senha deve conter no mínimo 5 caracteres',
            'email.required' => 'Preencha o e-mail',
            'email.unique' => 'E-mail já cadastrado',
            'email.email' => 'Insira um e-mail válido',
            'departamento_id.required' => 'Selecione um Departamento'
        ]);
            $dados = $request->all();
         
            $nome_departamento = Str::ucfirst($dados['departamento_id']);
            $verifica_departamento = DepartamentoTenant::where('nome', '=', $nome_departamento)
                                                 ->orWhere('id', '=', $dados['departamento_id'])
                                                 ->count();   
            $usuario = UserTenant::find($id);
            if ($usuario->password == $dados['password']) {
                    unset($dados['password']);
            } else {
                    $dados['password'] = bcrypt($dados['password']);
            }
            if(isset($dados['troca_senha'])){
                $dados['troca_senha'] = 'S';
            }else{
                $dados['troca_senha'] = 'N';
            } 
            if(isset($dados['utiliza_filtro'])){
                $dados['utiliza_filtro'] = 'S';
            }else{
                $dados['utiliza_filtro'] = 'N';
            }    
            if(isset($dados['utiliza_rls'])){
                $dados['utiliza_rls'] = 'S';
            }else{
                $dados['utiliza_rls'] = 'N';
            }              
          

            if($verifica_departamento > 0){

               $usuario->update($dados); 
            }else{
                $departamento = DepartamentoTenant::create([
                    'nome' => $nome_departamento
                ]);
                
               $dados['departamento_id'] = $departamento->id;
               $usuario->update($dados);      
            }
        return redirect()->route('tenant.usuarios')->with('toast_success', 'Usuário atualizado com sucesso!');      
                                                                       
    }

    public function excluirUsuario($id){
        try{
            UserTenant::find($id)->delete();
    
            return redirect()->route('tenant.usuarios')->with('success', 'Usuário excluido com sucesso!');
            }catch(QueryException $e){
                if ($e->errorInfo[0] == '23000') {
    
                    return redirect()->route('tenant.usuarios')->with('toast_error', 'Usuário está sendo utilizado por algum registro e não pode ser excluido!');
                }
            }
    }

    public function trocarSenha(){

        $user = Auth::guard('web')->user();

        return view('pages.administrador.administrador.trocar-senha', compact('user'));
    }

    public function atualizarSenha(Request $request, $id){
         //valida o formulário
         $this->validate($request, [
            'name' => 'required',
            'email' => 'required|email|unique:users,email,'.$id.'|unique:parceiros,email',
            'password' => 'required|min:5'
        ], [
            'name.required' => 'Preencha o nome!',
            'password.required' => 'Senha não pode ficar em branco',
            'password.min' => 'A Senha deve conter no mínimo 5 caracteres',
            'email.required' => 'Preencha o e-mail do administrador',
            'email.unique' => 'E-mail já cadastrado',
            'email.email' => 'Insira um e-mail válido'
        ]);

        $dados = $request->all();
        $usuario = UserTenant::find($id);
        if ($usuario->password == $dados['password']) {
                    unset($dados['password']);
        } else {
                    $dados['password'] = bcrypt($dados['password']);
        }
        $usuario->update($dados);     
        $tenant =  TenantUser::first();
        $tenant->update([
            'email_administrador' => $dados['email']
        ]);
       
        return redirect()->route('dashboard-admin')->with('toast_success', 'Usuário atualizado com sucesso!');      
     
    }
    
}
