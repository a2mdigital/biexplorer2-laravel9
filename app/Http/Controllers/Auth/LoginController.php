<?php

namespace App\Http\Controllers\Auth;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\Parceiro;
use App\Models\TenantParceiro;
use App\Models\TenantUser;
use App\Models\UserTenant;
use Illuminate\Support\Facades\Session;
use Illuminate\Foundation\Auth\AuthenticatesUsers;
use Auth;
use Carbon\Carbon;
use RealRashid\SweetAlert\Facades\Alert;

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
 
    public function showFormLogin(){
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
        /*
        if($subdomain == 'biexplorer' || 'dados' || 'a2m'){
            $imagem_login = Parceiro::select('imagem_login')
                            ->where('subdomain', 'a2m')->first();
            $imagem_login = $imagem_login->imagem_login;         
        }else{
            $imagem_login = Parceiro::select('imagem_login')
            ->where('subdomain', $subdomain)->first();
            $imagem_login = $imagem_login->imagem_login;
        }
        */
        return view('pages.auth.login', compact('imagem_login', 'tamanho_imagem', 'background'));
    }

    public function showParceiroLoginForm()
    {

        //return view('pages.auth.login', ['url' => 'parceiro']);
    }

    public function login(Request $request)
    {
       
       
        $this->validate($request, [
            'email'   => 'required|email',
            'password' => 'required|min:5'
        ],
       );  
       
        if (Auth::guard('parceiro')->attempt(['email' => $request->email, 'password' => $request->password])) {
            //SE O PARCEIRO ESTIVER INATIVADO NÃO DEIXO LOGAR NA PLATAFORMA    
            $inativado = Auth::guard('parceiro')->user()->inativado;
                if($inativado == 'S'){
                    return redirect()->route('login')->with('toast_error', trans('auth.inactive'));
                    $request->session()->flush();
                    $request->session()->regenerate();
                    Auth::guard('parceiro')->logout();
                }
                //SE ESTIVER HABILITADO PARA TROCAR A SENHA ABRE O FORMULARIO
                $troca_senha = Auth::guard('parceiro')->user()->troca_senha;
                if($troca_senha != 'S'){
                    return redirect()->route('dashboard-parceiro')->with('toast_success', trans('messages.welcome'));
                }else{
                    //trocar a senha inicial 
                    return redirect()->route('parceiro.trocar.senha.inicial')->with('toast_success', trans('auth.change_password'));
                }
           
        }else if (Auth::guard('web')->attempt(['email' => $request->email, 'password' => $request->password])) {
            //VERIFICAR SE O PARCEIRO ESTÁ INATIVADO
            $tenant_user = Auth::guard('web')->user()->tenant_id;
            $tenant = TenantParceiro::find($tenant_user);
            $parceiro_tenant = Parceiro::find($tenant->parceiro_id);
            if($parceiro_tenant->inativado == 'S'){
                return redirect()->route('login')->with('toast_error', trans('auth.inactive'));
                $request->session()->flush();
                $request->session()->regenerate();
                Auth::guard('web')->logout();
            }
            //SE O USUÁRIO FOR ADMIN
            if(auth()->user()->is_admin){
                //grava a data e hora do ultimo login
                $user = Auth::guard('web')->user();
                $user->update([
                    'ultimo_login' => Carbon::now('America/Sao_Paulo')
                ]);
                return redirect()->route('dashboard-admin')->with('toast_success',trans('messages.welcome'));
            }else{

                //VERIFICAR SE O PARCEIRO ESTÁ INATIVADO
                $tenant_user = Auth::guard('web')->user()->tenant_id;
                $tenant = TenantParceiro::find($tenant_user);
                $parceiro_tenant = Parceiro::find($tenant->parceiro_id);
                if($parceiro_tenant->inativado == 'S'){
                    return redirect()->route('login')->with('toast_error', trans('auth.inactive'));
                    $request->session()->flush();
                    $request->session()->regenerate();
                    Auth::guard('web')->logout();
                }
                //TROCAR A SENHA INICIAL
                $troca_senha = Auth::guard('web')->user()->troca_senha;
                if($troca_senha != 'S'){
                      //grava a data e hora do ultimo login
                    $user = Auth::guard('web')->user();
                    $user->update([
                        'ultimo_login' => Carbon::now('America/Sao_Paulo')
                    ]);
                    return redirect()->route('dashboard-users')->with('toast_success', trans('messages.welcome'));
                }else{
                    //trocar a senha inicial 
                    return redirect()->route('users.tenant.trocar.senha.inicial')->with('toast_success', trans('auth.change_password'));
                
                }

            }
          
        }else{
           return redirect()->back()->with('toast_error', trans('auth.failed'));
        }
    }

    /*
    public function preLogin(){
        alert()->question('Title','Lorem Lorem Lorem');

    }
    */
    public function parceiroLogout(Request $request){

        $rota_parceiro = Auth::guard('parceiro')->user()->rota_login_logout;
        header("cache-Control:no-store,no-cache, must-revalidate");
        header("cache-Control:post-check=0,pre-check=0",false);
        header("Pragma:no-cache");
        header("Expires: Sat,26 Jul 1997 05:00:00: GMT");
        $request->session()->flush();
        $request->session()->regenerate();
        Auth::guard('parceiro')->logout();

        return redirect()->route('form-login')->with('toast_success', trans('auth.logout'));
       
        
    }

    public function adminLogout(Request $request){
        $tenant_user = TenantUser::first();
        $parceiro = $tenant_user->parceiro()->first();
        //gravar que o usuário saiu..
        Auth::user()->session_id = 'offline';
        Auth::user()->save();
        header("cache-Control:no-store,no-cache, must-revalidate");
        header("cache-Control:post-check=0,pre-check=0",false);
        header("Pragma:no-cache");
        header("Expires: Sat,26 Jul 1997 05:00:00: GMT");
        $request->session()->flush();
        $request->session()->regenerate();
        Auth::guard('web')->logout();

        return redirect()->route('form-login')->with('toast_success', trans('auth.logout'));
      
    }

    public function userLogout(Request $request){
        $tenant_user = TenantUser::first();
        $parceiro = $tenant_user->parceiro()->first();
        //gravar que o usuário saiu..
        Auth::user()->session_id = 'offline';
        Auth::user()->save();
        header("cache-Control:no-store,no-cache, must-revalidate");
        header("cache-Control:post-check=0,pre-check=0",false);
        header("Pragma:no-cache");
        header("Expires: Sat,26 Jul 1997 05:00:00: GMT");
        $request->session()->flush();
        $request->session()->regenerate();
        Auth::guard('web')->logout();

        return redirect()->route('form-login')->with('toast_success', trans('auth.logout'));
       
        
    }

}
