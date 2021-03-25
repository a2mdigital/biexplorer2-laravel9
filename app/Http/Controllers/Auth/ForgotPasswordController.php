<?php

namespace App\Http\Controllers\Auth;

use Carbon\Carbon; 
use Illuminate\Support\Str;
use Illuminate\Http\Request; 
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Controller;
use App\Jobs\SendMailResetPassword;
use Illuminate\Support\Facades\Mail;
use RealRashid\SweetAlert\Facades\Alert;
class ForgotPasswordController extends Controller
{
    /*
    |--------------------------------------------------------------------------
    | Password Reset Controller
    |--------------------------------------------------------------------------
    |
    | This controller is responsible for handling password reset emails and
    | includes a trait which assists in sending these notifications from
    | your application to your users. Feel free to explore this trait.
    |
    */

   // use SendsPasswordResetEmails;

    public function getEmail(){
        return view('pages.auth.forgot-password');
    }

    public function postEmail(Request $request){
        
        $request->validate([
            'email' => 'required|email|exists:users',
            'email' => 'required|email|exists:parceiros',
        ],
        [
            'email.required' => 'Preencha o e-mail',
            'email.email' => 'Insira um e-mail válido',
            'email.exists' => 'E-mail não está cadastrado'
        ]);
    
        $token = Str::random(64);
        $subdomain = explode('.', request()->getHost());
        $subdomain = $subdomain[0];    
        $host = request()->getHost();
          DB::table('password_resets')->insert(
              ['email' => $request->email, 'token' => $token, 'created_at' => Carbon::now()]
          );

          SendMailResetPassword::dispatch($host, $token, $subdomain, $request->email)->delay(now()->addSeconds('5'));

          /*
          Mail::send('pages.auth.password-email', ['host' => $host, 'token' => $token, 'now' => Carbon::now()], function($message) use($request, $subdomain){
              $message->from('biexplorer@'.$subdomain.'.com.br','Bi Explorer');
              $message->to($request->email);
              $message->subject('Redefinição de Senha');
          });
          */
    
          return back()->with('success', 'O Link de redefinição da senha foi enviado ao seu e-mail!');
    }
}
