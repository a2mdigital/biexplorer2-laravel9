<?php

namespace App\Http\Controllers\Api;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\HistoricoRelatoriosUser;

class FavoritosController extends Controller
{
    public function index(){
        $user = auth()->user();
        if($user->is_admin == 1){
            //se o usuário for admin por enqto não tem favoritos
        }else{
            $favoritos = HistoricoRelatoriosUser::with('relatorios')
            ->where('favorito', 'S')    
            ->orderBy('ultima_hora_acessada', 'desc')
            ->get();  
          return ['response' => 'ok', 'reports' => $favoritos];

        }
        
    }

    public function save(Request $request){
        $dados = $request->all();
      
        $salvarFavorito = HistoricoRelatoriosUser::where('relatorio_id', $dados['relatorio_id'])
                            ->update([
                            'favorito' => $dados['favorito'],
                            ]);
        if($salvarFavorito){
                return response()->json(["resposta" => 'salvou']);
        }else{
                return response()->json(["resposta" => 'erro']);
        }                
    }

}
