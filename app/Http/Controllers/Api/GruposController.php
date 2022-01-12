<?php 

namespace App\Http\Controllers\Api;

Use Illuminate\Http\Request;
use App\Models\Relatorio;
use App\Models\RelatorioTenant;
use App\Models\SubGrupoRelatorio;
use App\Http\Controllers\Controller;
use App\Models\RelatorioUserPermission;
use App\Models\RelatorioDepartamentoPermission;

class GruposController extends Controller{
    public function index(){

        $user = auth()->user();
        if($user->is_admin == 1){
            $relatorios_tenant = RelatorioTenant::select('relatorio_id')->get();
            $allRelatoriosPermission = Relatorio::whereIn('id', $relatorios_tenant)->get();
         
            $subgruposTenant = array();
            foreach($allRelatoriosPermission as $relPermission){
                array_push($subgruposTenant, $relPermission->subgrupo_relatorio_id);
            }
            //buscar grupos com relatórios liberados
            $grupos = SubGrupoRelatorio::whereIn('id', $subgruposTenant)->get();
            
            return ['resposta' => 'ok', 'groups' => $grupos];
        }else{
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

            return ['resposta' => 'ok', 'groups' => $grupos];
        }
    }
}