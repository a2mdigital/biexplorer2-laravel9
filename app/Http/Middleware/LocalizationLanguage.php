<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\App;
use Carbon\Carbon;
class LocalizationLanguage
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle(Request $request, Closure $next)
    {
   
       // App::setLocale('pt_PT');
        
        $languagues = explode(',', $request->server('HTTP_ACCEPT_LANGUAGE'));
        if($languagues != null){
            if($languagues[0] == 'pt-BR' || $languagues[0] == 'pt_BR'){
                App::setLocale('pt_BR');
            }else if($languagues[0] == 'pt-pt' || $languagues[0] == 'pt' || $languagues[0] == 'pt_PT' || $languagues[0] == 'pt-PT'){
                App::setLocale('pt_PT');
            }else{
                App::setLocale('en');
            }
        }else{
            App::setLocale('en');
        }
        
        
 
        
      
     
        return $next($request);
    }
}
