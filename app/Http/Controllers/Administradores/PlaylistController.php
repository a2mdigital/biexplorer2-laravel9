<?php

namespace App\Http\Controllers\Administradores;

use App\Models\Playlist;
use App\Models\Relatorio;
use App\Models\TenantUser;
use App\Models\UserTenant;
use App\Models\PlaylistIten;
use Illuminate\Http\Request;
use App\Tenant\ManagerTenant;
use App\Models\RelatorioTenant;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Controller;
use Illuminate\Database\QueryException;
use Yajra\DataTables\Facades\DataTables;

class PlaylistController extends Controller
{
    public function listarPlaylists(Request $request){
        $tenant = app(ManagerTenant::class)->getTenantIdentify();
        if ($request->ajax()) {
            return Datatables::of( Playlist::withoutGlobalScopes()->join('users', 'users.id', '=', 'playlists.user_id')
                                    ->where('playlists.tenant_id', $tenant)
                                    ->select('playlists.id as id', 'playlists.nome as nome', 'playlists.tempo_atualizacao as tempo_atualizacao', 'users.name as nome_user')
                    )
                    ->addIndexColumn()
                    ->addColumn('action', function($playlist){

                      $botoes = '
                      <div style="display: flex; justify-content:flex-start">
                        <a href="'. route('tenant.playlist.visualizar', $playlist->id) .'" class="btn btn-outline-primary btn-sm">Visualizar</a>     
                        <a href="'. route('tenant.playlist.editar', $playlist->id) .'" class="edit btn btn-primary btn-sm" style="margin-left: 3px;">Editar</a>

                            <form action="'. route('tenant.playlist.excluir', $playlist->id). '" style="margin-left: 3px;" method="POST">
                            '.csrf_field().'
                            '.method_field("DELETE").'
                            <button type="submit"  onclick="return confirm(\'Tem certeza que deseja excluir a Playlist?\')" class="btn btn-danger btn-sm">
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
    
        return view('pages.administrador.playlist.listar');
    }

    public function cadastrarPlaylist(){
        $tenant = app(ManagerTenant::class)->getTenantIdentify();
        $relatorios = DB::table('relatorio_tenant')
            ->join('tenants', 'relatorio_tenant.tenant_id', '=', 'tenants.id')
            ->join('relatorios', 'relatorio_tenant.relatorio_id', '=', 'relatorios.id')
            ->select('relatorios.id as id', 'relatorios.nome as nome',  'relatorios.tipo as tipo')
            ->where('tenants.id', '=', $tenant)->get();
        $usuarios = UserTenant::where('is_admin', '<>', 1)->get();

        return view('pages.administrador.playlist.cadastrar', compact('relatorios', 'usuarios'));
    }

    public function salvarPlaylist(Request $request){
        $this->validate($request, [
            'nome' => 'required',
            'tempo_atualizacao' => 'required',
            'user_id'   => 'required', 
            'itensplaylist' => 'required' 
        ], [
                'nome.required' => 'Preencha o nome!',
                'tempo_atualizacao.required' => 'Digite o tempo de atualização',
                'user_id.required' => 'Selecione um Usuário', 
                'itensplaylist.required' => 'Selecione os itens da Playlist'
        ]);
        $dados = $request->all();
  
        $playlist = Playlist::updateOrCreate(
            ['id' => $dados['id_playlist']],
            [
                    'nome' => $dados['nome'],
                    'tempo_atualizacao' => $dados['tempo_atualizacao'],
                    'user_id' => $dados['user_id']
        ]);

        PlaylistIten::where('playlist_id', $playlist->id)->delete();
        $ordem = 1;
        for ($i = 0; $i < count($request->itensplaylist['uid_dash']); $i++) {
            if(isset($request->itensplaylist['navega_paginas'][$ordem])){
                if($request->itensplaylist['navega_paginas'][$ordem] == 'N') {
                    $navega_paginas = 'N';
                }else{
                    $navega_paginas = 'S';
                }
            }else{
                $navega_paginas = 'N';
            }
           
            $playlistitens = PlaylistIten::updateOrCreate(
                [
                    'playlist_id' =>   $playlist->id,
                    'relatorio_id' =>  $request->itensplaylist['uid_dash'][$i]
                ],
                [
                    'playlist_id' => $playlist->id,
                    'relatorio_id' => $request->itensplaylist['uid_dash'][$i],
                    'ordem' => $request->itensplaylist['ordem'][$i],
                    'navega_paginas' => $navega_paginas
                ]
            );


            $ordem ++;

        } 
        
        return redirect()->route('tenant.playlists')->with('success', 'Playlist criada com sucesso!');                 

    }

    public function editarPlaylist($id){
        $tenant = app(ManagerTenant::class)->getTenantIdentify();
        $playlist = Playlist::findOrFail($id);
        $playlistitens = PlaylistIten::withoutGlobalScopes()->leftjoin('relatorios', 'relatorios.id', '=', 'playlist_itens.relatorio_id')->where('playlist_id', $id)->orderBy('ordem', 'asc')->get();

        $itens = [];
        foreach ($playlistitens as $item) {

            $itens[] = $item->relatorio_id;
        }

        $usuarios = UserTenant::where('is_admin', '<>', 1)->get();
        $relatorios = DB::table('relatorio_tenant')
            ->join('tenants', 'relatorio_tenant.tenant_id', '=', 'tenants.id')
            ->join('relatorios', 'relatorio_tenant.relatorio_id', '=', 'relatorios.id')
            ->select('relatorios.id as id', 'relatorios.nome as nome',  'relatorios.tipo as tipo')
            ->whereNotIn('relatorios.id', $itens)
            ->where('tenants.id', '=', $tenant)->get();

       return view('pages.administrador.playlist.editar', compact('relatorios', 'playlist', 'playlistitens', 'usuarios'));     
    }

    public function excluirPlaylist($id){
        try{
            Playlist::find($id)->delete();
    
            return redirect()->route('tenant.playlists')->with('success', 'Playlist excluida com sucesso!');
            }catch(QueryException $e){
                if ($e->errorInfo[0] == '23000') {
    
                    return redirect()->route('tenant.playlists')->with('toast_error', 'Playlist está sendo utilizada e não pode ser excluida!');
                }
            }
    }

    public function visualizarPlayList($id){
        $tenant = app(ManagerTenant::class)->getTenantIdentify();
        $playlist = Playlist::findOrFail($id);
        $tempo_atualizacao = $playlist->tempo_atualizacao;
        $itens = PlaylistIten::where('playlist_id', $id)->get();
       
        $total_itens = count($itens);
        $tenant_user = TenantUser::firstOrFail();
        $relatorio = DB::table('relatorio_tenant')
        ->join('tenants', 'relatorio_tenant.tenant_id', '=', 'tenants.id')
        ->join('relatorios', 'relatorio_tenant.relatorio_id', '=', 'relatorios.id')
        ->join('playlist_itens', 'relatorios.id', '=', 'playlist_itens.relatorio_id')
        ->join('playlists', 'playlists.id', '=', 'playlist_itens.playlist_id')
        ->where('playlists.id', $id)
        ->where('relatorio_tenant.tenant_id', '=', $tenant)->orderBy('playlist_itens.ordem', 'asc')->get();

       return view('pages.administrador.playlist.visualizar', compact('tenant_user', 'itens', 'total_itens', 'relatorio', 'tempo_atualizacao')); 

    }


}
