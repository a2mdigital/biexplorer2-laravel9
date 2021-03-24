<?php

namespace App\Http\Controllers\Auth;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\Parceiro;
use App\Models\TenantUser;
use App\Models\UserTenant;
use Illuminate\Support\Facades\Session;
use Illuminate\Foundation\Auth\AuthenticatesUsers;
use Auth;
use Carbon\Carbon;

class LoginController extends Controller
{
    /*
    |--------------------------------------------------------------------------
    | Login Controller
    |--------------------------------------------------------------------------
    |
    | This controller handles authenticating users for the application and
    | redirecting them to your home screen. The controller uses a trait
    | to conveniently provide its functionality to your applications.
    |
    */

    use AuthenticatesUsers;

    /**
     * Where to redirect users after login.
     *
     * @var string
     */
   // protected $redirectTo = '/home';

    /**
     * Create a new controller instance.
     *
     * @return void
     */
    /*
    public function __construct()
    {
        //$this->middleware('guest')->except('logout');
        //$this->middleware('guest:parceiro')->except('logout');
    }
    */
 

    public function showParceiroLoginForm()
    {
        return view('pages.auth.login', ['url' => 'parceiro']);
    }

    public function login(Request $request)
    {
       
       
        $this->validate($request, [
            'email'   => 'required|email',
            'password' => 'required|min:5'
        ],
        [
            'password.required' => 'Senha não pode ficar em branco',
            'password.min' => 'A Senha deve conter no mínimo 5 caracteres',
            'email.required' => 'Preencha o e-mail',
            'email.email' => 'Insira um e-mail válido',
        ]);  
       
        if (Auth::guard('parceiro')->attempt(['email' => $request->email, 'password' => $request->password])) {
                $troca_senha = Auth::guard('parceiro')->user()->troca_senha;
                if($troca_senha != 'S'){
                    return redirect()->route('dashboard-parceiro')->with('toast_success', 'Bem Vindo!');
                }else{
                    //trocar a senha inicial 
                    return redirect()->route('parceiro.trocar.senha.inicial')->with('toast_success', 'Troque seua senha!');
                }
           
        }else if (Auth::guard('web')->attempt(['email' => $request->email, 'password' => $request->password])) {
        
            if(auth()->user()->is_admin){
                //grava a data e hora do ultimo login
                $user = Auth::guard('web')->user();
                $user->update([
                    'ultimo_login' => Carbon::now('America/Sao_Paulo')
                ]);
                return redirect()->route('dashboard-admin')->with('toast_success', 'Bem Vindo!');
            }else{

                $troca_senha = Auth::guard('web')->user()->troca_senha;
                if($troca_senha != 'S'){
                      //grava a data e hora do ultimo login
                    $user = Auth::guard('web')->user();
                    $user->update([
                        'ultimo_login' => Carbon::now('America/Sao_Paulo')
                    ]);
                    return redirect()->route('dashboard-users')->with('toast_success', 'Bem Vindo!');
                }else{
                    //trocar a senha inicial 
                    return redirect()->route('users.tenant.trocar.senha.inicial')->with('toast_success', 'Troque seua senha!');
                
                }

            }
          
        }else{
           return redirect()->back()->with('toast_error', 'Usuário ou Senha Inválidos');
        }
    }

    public function parceiroLogout(Request $request){

        $rota_parceiro = Auth::guard('parceiro')->user()->rota_login_logout;
        header("cache-Control:no-store,no-cache, must-revalidate");
        header("cache-Control:post-check=0,pre-check=0",false);
        header("Pragma:no-cache");
        header("Expires: Sat,26 Jul 1997 05:00:00: GMT");
        $request->session()->flush();
        $request->session()->regenerate();
        Auth::guard('parceiro')->logout();

        if($rota_parceiro == 'padrao'){
             return redirect()->route('form-login')->with('toast_success', 'Sessão Finalizada');
        }else{
              return redirect()->route($rota_parceiro)->with('toast_success', 'Sessão Finalizada');
        }
        
    }

    public function adminLogout(Request $request){
        $tenant_user = TenantUser::first();
        $parceiro = $tenant_user->parceiro()->first();
        
        header("cache-Control:no-store,no-cache, must-revalidate");
        header("cache-Control:post-check=0,pre-check=0",false);
        header("Pragma:no-cache");
        header("Expires: Sat,26 Jul 1997 05:00:00: GMT");
        $request->session()->flush();
        $request->session()->regenerate();
        Auth::guard('web')->logout();

        if($parceiro->rota_login_logout == 'padrao'){
            return redirect()->route('form-login')->with('toast_success', 'Sessão Finalizada');
       }else{
             return redirect()->route($parceiro->rota_login_logout)->with('toast_success', 'Sessão Finalizada');
       }
    }

    public function userLogout(Request $request){
        $tenant_user = TenantUser::first();
        $parceiro = $tenant_user->parceiro()->first();
        
        header("cache-Control:no-store,no-cache, must-revalidate");
        header("cache-Control:post-check=0,pre-check=0",false);
        header("Pragma:no-cache");
        header("Expires: Sat,26 Jul 1997 05:00:00: GMT");
        $request->session()->flush();
        $request->session()->regenerate();
        Auth::guard('web')->logout();

        if($parceiro->rota_login_logout == 'padrao'){
            return redirect()->route('form-login')->with('toast_success', 'Sessão Finalizada');
       }else{
             return redirect()->route($parceiro->rota_login_logout)->with('toast_success', 'Sessão Finalizada');
       }
        
    }

}
