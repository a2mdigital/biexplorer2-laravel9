<?php

namespace App\Http\Controllers\Users;

use App\Models\Playlist;
use App\Models\TenantUser;
use App\Models\PlaylistIten;
use Illuminate\Http\Request;
use App\Tenant\ManagerTenant;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Controller;
use Yajra\DataTables\Facades\DataTables;

class PlaylistUsersController extends Controller
{
    public function listarPlaylists(Request $request){
        $user = auth()->user()->id;
        if ($request->ajax()) {
            return Datatables::of(Playlist::where('user_id', $user))
                    ->addIndexColumn()
                    ->addColumn('action', function($playlist){

                      $botoes = '
                      <div style="display: flex; justify-content:flex-start">
                        <a href="'. route('users.tenant.playlist.visualizar', $playlist->id) .'" class="btn btn-outline-primary btn-sm">Visualizar</a>     
                        </div>
                      ';  
                 
                      return $botoes;
                    })
                    ->rawColumns(['action'])
                    ->make(true);
        }
        return view('pages.users.playlist.listar');
    }

    public function visualizarPlayList($id){
        $tenant = app(ManagerTenant::class)->getTenantIdentify();
        $user = auth()->user()->id;
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
        ->where('playlists.user_id', $user)
        ->where('relatorio_tenant.tenant_id', '=', $tenant)->orderBy('playlist_itens.ordem', 'asc')->get();
       
       return view('pages.users.playlist.visualizar', compact('tenant_user', 'itens', 'total_itens', 'relatorio', 'tempo_atualizacao')); 

    }
}
