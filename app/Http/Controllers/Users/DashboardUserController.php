<?php

namespace App\Http\Controllers\Users;
use Auth;
use Carbon\Carbon;
use App\Models\User;
use App\Models\Parceiro;
use App\Models\Relatorio;
use App\Models\TenantUser;
use App\Models\UserTenant;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\HistoricoRelatoriosUser;

class DashboardUserController extends Controller
{
    public function indexDashboard(){
       $user = Auth::user(); 
       $ultimosAcessados = HistoricoRelatoriosUser::with('relatorios')
                            ->orderBy('ultima_hora_acessada', 'desc')
                            ->get();
        $favoritos = HistoricoRelatoriosUser::with('relatorios')
                            ->where('favorito', 'S')    
                            ->orderBy('ultima_hora_acessada', 'desc')
                            ->get();
        $maisAcessados = HistoricoRelatoriosUser::with('relatorios')
                            ->orderBy('qtd_acessos', 'desc')
                            ->get();                    
        return view('pages.users.dashboard', compact('user', 'ultimosAcessados', 'favoritos', 'maisAcessados'));
    }

    public function salvarCustomMenuColor(Request $request){
        $dados = $request->all();
      
        $user = User::findOrFail($dados['user']);
        $user->update(['menu_color' =>  $dados['color']]);
       
    }

    public function salvarCustomMenuContraido(Request $request){
        $dados = $request->all();
      
        $user = User::findOrFail($dados['user']);
        $user->update(['menu_contraido' =>  $dados['contraido']]);
       
    }

    public function salvarFavorito(Request $request){
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
    
    public function trocarSenhaInicial(){
        $usuario = Auth::guard('web')->user();
        $subdomain = explode('.', request()->getHost());
        $img = Parceiro::select('imagem_login', 'tamanho_imagem_login', 'fundo_imagem_login')
            ->where('subdomain', $subdomain[0])->first();
        if(!$img){
            $imagem_login = 'logo-a2m.png';
            $tamanho_imagem = '75%';
            $background = 'bg.jpg';
        }else{
            $imagem_login = $img->imagem_login;
            $tamanho_imagem = $img->tamanho_imagem_login;
            $background = $img->fundo_imagem_login;
        }
        return view('pages.users.users.trocar-senha-inicial', compact('usuario', 'background'));
    }
    public function atualizarSenhaInicial(Request $request, $id){

         //valida o formulário
         $this->validate($request, [
               'password' => 'required|confirmed|min:5'
        ]);

        $dados = $request->all();
        $usuario = User::find($id);
        $usuario->update([
            'password' => bcrypt($dados['password']),
            'troca_senha' => 'N',
        ]);  
        if (Auth::guard('web')->attempt(['email' => $usuario->email, 'password' => $dados['password']])) {
         
            $user = Auth::guard('web')->user();
            $user->update([
                'ultimo_login' => Carbon::now('America/Sao_Paulo')
            ]);
            return redirect()->route('dashboard-users')->with('toast_success',trans('messages.welcome'));
           
        }else{
        return redirect()->route('login');
        }
    }

    public function trocarSenha(){

        $user = Auth::guard('web')->user();

        return view('pages.users.users.trocar-senha', compact('user'));
    }

    public function atualizarSenha(Request $request, $id){
        //valida o formulário
        $this->validate($request, [
           'name' => 'required',
           'email' => 'required|email|unique:users,email,'.$id.'|unique:parceiros,email',
           'password' => 'required|min:5'
       ]);

       $dados = $request->all();
       $usuario = UserTenant::find($id);
       if ($usuario->password == $dados['password']) {
                   unset($dados['password']);
       } else {
                   $dados['password'] = bcrypt($dados['password']);
       }
       $usuario->update($dados);     
       
       return redirect()->route('dashboard-users')->with('toast_success', trans('messages.message_update_data'));      
    
   }
}
