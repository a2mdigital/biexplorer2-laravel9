<?php

namespace App\Http\Controllers\Administradores;

use Illuminate\Http\Request;
use App\Models\DepartamentoTenant;
use App\Http\Controllers\Controller;
use Illuminate\Database\QueryException;
use Yajra\DataTables\Facades\DataTables;

class DepartamentoController extends Controller
{
    public function listarDepartamentos(Request $request){
        if ($request->ajax()) {
            return Datatables::of( DepartamentoTenant::query())
                    ->addIndexColumn()
                    ->addColumn('action', function($departamento){

                      $botoes = '
                      <div style="display: flex; justify-content:flex-start">
                        <a href="'. route('tenant.departamento.editar', $departamento->id) .'" class="edit btn btn-primary btn-sm">Editar</a>

                            <form action="'. route('tenant.departamento.excluir', $departamento->id). '" style="margin-left: 3px;" method="POST">
                            '.csrf_field().'
                            '.method_field("DELETE").'
                            <button type="submit"  onclick="return confirm(\'Tem certeza que deseja excluir o Departamento?\')" class="btn btn-danger btn-sm">
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
    
        return view('pages.administrador.departamentos.listar');
    }

    public function editarDepartamento($id){

        $departamento = DepartamentoTenant::findOrFail($id);

        return view('pages.administrador.departamentos.editar', compact('departamento'));

    }

    public function cadastrarDepartamento(){
        
        return view('pages.administrador.departamentos.cadastrar');
    }

    public function salvarDepartamento(Request $request){
        $this->validate($request, [
            'nome' => 'required',
            ], [
                'nome.required' => 'Preencha o nome!',
            ]);
            $dados = $request->all();
            $utiliza_filtro = (isset($dados['utiliza_filtro']) == 'on' ? 'S' : 'N');

            DepartamentoTenant::create([
                'nome' => $dados['nome'],
                'utiliza_filtro' => $utiliza_filtro,
                'filtro_tabela' => $dados['filtro_tabela'],
                'filtro_coluna' => $dados['filtro_coluna'],
                'filtro_valor' => $dados['filtro_valor'],
            ]);
        
        return redirect()->route('tenant.departamentos')->with('success', 'Departamento salvo com sucesso!');               
    }

    public function atualizarDepartamento(Request $request, $id){
           //valida o formulario
       $this->validate($request, [
        'nome' => 'required',
        ], [
            'nome.required' => 'Preencha o nome!',
        ]);
        $dados = $request->all();
        if(isset($dados['utiliza_filtro'])){
            $dados['utiliza_filtro'] = 'S';
        }else{
            $dados['utiliza_filtro'] = 'N';
        }    
        $departamento = DepartamentoTenant::findOrFail($id);
        $departamento->update($dados);
        return redirect()->route('tenant.departamentos')->with('toast_success', 'Departamento atualizado com sucesso!');           
    }

    public function excluirDepartamento($id){
        try{
            DepartamentoTenant::find($id)->delete();
    
            return redirect()->route('tenant.departamentos')->with('success', 'Departamento excluido com sucesso!');
            }catch(QueryException $e){
                if ($e->errorInfo[0] == '23000') {
    
                    return redirect()->route('tenant.departamentos')->with('toast_error', 'Departamento está sendo utilizado e não pode ser excluido!');
                }
            }
    }
}
