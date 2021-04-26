<?php

namespace App\Http\Controllers\A2m;

use App\DataTables\ParceirosDataTable;
use App\Models\User;
use App\Models\Tenant;
use App\Models\Parceiro;
use Illuminate\Http\Request;
use App\Models\TenantParceiro;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\Facades\Crypt;
use Illuminate\Database\QueryException;
use Yajra\DataTables\Facades\DataTables;

class ParceirosA2mController extends Controller
{
    public function listarParceiros(Request $request){
   
        if (! Gate::allows('a2m')) {
            abort(403);
        }else{
         
            if ($request->ajax()) {
           
                return Datatables::of(Parceiro::query())
                        ->addIndexColumn()
                        ->addColumn('action', function($parceiro){
                          $botoes = '
                          <div style="display: flex; justify-content:flex-start">
                            <a href="'. route('parceiro.editar', $parceiro->id) .'" class="edit btn btn-primary btn-sm">Editar</a>
    
                                <form action="'. route('parceiro.excluir',$parceiro->id). '" style="margin-left: 3px;" method="POST">
                                '.csrf_field().'
                                '.method_field("DELETE").'
                                <button type="submit"  onclick="return confirm(\'Tem certeza que deseja excluir o Parceiro?\')" class="btn btn-danger btn-sm">
                                Excluir
                                </button>
                                </form>
                                <a href="'. route('parceiro.tenants.listar', $parceiro->id) .'" style="margin-left: 3px;" class="edit btn btn-info btn-sm">Auditoria</a>     
                            </div>
                          ';  
    
                          return $botoes;
                        })
                        ->rawColumns(['action'])
                        ->make(true);
            }
            return view('pages.a2m.parceiro.listar');
        }
    }

    public function cadastrarParceiro(){
        if (! Gate::allows('a2m')) {
            abort(403);
        }else{
           
            return view('pages.a2m.parceiro.cadastrar');
        }
    }

    public function salvarParceiro(Request $request){
         //valida o formulário
         $this->validate($request, [
            'name' => 'required',
            'email' => 'required|email|unique:parceiros,email,|unique:tenants,email_administrador|unique:users,email',
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
            $troca_senha = (isset($dados['troca_senha']) == 'on' ? 'S' : 'N');
            Parceiro::create([
                'email' => $dados['email'],
                'name' => $dados['email'],
                'is_admin' => 0,
                'subdomain' => $dados['subdomain'],
                'imagem_login' => $dados['imagem_login'],
                'tamanho_imagem_login' => $dados['tamanho_imagem_login'],
                'fundo_imagem_login' => $dados['fundo_imagem_login'],
                'menu_color' => 1,
                'menu_contraido' => 0,
                'password' => bcrypt($dados['password']),
                'troca_senha' => $troca_senha,
            ]);

            return redirect()->route('parceiros.listar')->with('success', 'Parceiro Cadastrado com Sucesso!');
    }

    public function editarParceiro($id){
        if (! Gate::allows('a2m')) {
            abort(403);
        }else{
            $parceiro = Parceiro::find($id);
            return view('pages.a2m.parceiro.editar', compact('parceiro'));
        }
    }

    public function atualizarParceiro(Request $request, $id){
        //valida o formulário
        $this->validate($request, [
           'name' => 'required',
           'email' => 'required|email|unique:parceiros,email,'.$id.'|unique:tenants,email_administrador|unique:users,email',
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
       
       (isset($dados['troca_senha']) == 'on' ? $dados['troca_senha'] = 'S' : $dados['troca_senha'] = 'N');
       (isset($dados['inativado']) == 'on' ? $dados['inativado'] = 'S' : $dados['inativado'] = 'N');
       $parceiro = Parceiro::find($id);
       if ($parceiro->password == $dados['password']) {
               unset($dados['password']);
           } else {
               $dados['password'] = bcrypt($dados['password']);
       }
       
       $parceiro->update($dados);

       return redirect()->route('parceiros.listar')->with('success', 'Dados Atualizados com Sucesso!');

   }

    public function excluirParceiro($id){
        if (! Gate::allows('a2m')) {
            abort(403);
        }else{
            try{
                Parceiro::find($id)->delete();
        
                return redirect()->route('parceiros.listar')->with('success', 'Parceiro excluido com sucesso!');
                }catch(QueryException $e){
                    if ($e->errorInfo[0] == '23000') {
        
                        return redirect()->route('parceiros.listar')->with('toast_error', 'Parceiro está sendo utilizado por algum registro e não pode ser excluido!');
                    }
                }
        }
    }
     
    public function listarEmpresasParceiro(Request $request, $id){
     
        if (! Gate::allows('a2m')) {
            abort(403);
        }else{
            $parceiro = Parceiro::findOrFail($id);
            if ($request->ajax()) {
                return Datatables::of(TenantParceiro::query()->where('parceiro_id', $id)->get())
                        ->addIndexColumn()
                        ->addColumn('action', function($parceiro){
                       
                            return $parceiro->usersTenant()->count();
                        })
                        ->rawColumns(['action'])
                        ->make(true);
            }
            return view('pages.a2m.auditoria.listar', compact('parceiro'));
        }
    }
    
    


}
