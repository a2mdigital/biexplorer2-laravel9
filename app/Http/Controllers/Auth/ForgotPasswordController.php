<?php

namespace App\Http\Controllers\Auth;

use Carbon\Carbon; 
use Illuminate\Support\Str;
use Illuminate\Http\Request; 
use Illuminate\Support\Facades\DB;
use App\Jobs\SendMailResetPassword;
use Illuminate\Support\Facades\App;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Mail;
use RealRashid\SweetAlert\Facades\Alert;
use Illuminate\Support\Facades\Validator;

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
        $locale = App::currentLocale();
        

       $request->validate([
            'email' => 'required|email',
            //'email' => 'required|email|exists:parceiros',
        ]
       );
      
        $token = Str::random(64);
        $subdomain = explode('.', request()->getHost());
        $subdomain = $subdomain[0];    
        $host = request()->getHost();
          DB::table('password_resets')->insert(
              ['email' => $request->email, 'token' => $token, 'created_at' => Carbon::now()]
          );

          SendMailResetPassword::dispatch($host, $token, $subdomain, $request->email, $locale)->delay(now()->addSeconds('5'));

          /*
          Mail::send('pages.auth.password-email', ['host' => $host, 'token' => $token, 'now' => Carbon::now()], function($message) use($request, $subdomain){
              $message->from('biexplorer@a2mdigital.com.br','Bi Explorer');
              $message->to($request->email);
              $message->subject('Redefinição de Senha');
          });
          */
    
          return back()->with('success', trans('auth.message_forgot_password'));
    }
}
