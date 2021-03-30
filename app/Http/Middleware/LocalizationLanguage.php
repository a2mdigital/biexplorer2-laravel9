<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\App;

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
        
        $languagues = explode(',', $request->server('HTTP_ACCEPT_LANGUAGE'));
        if($languagues != null){
            App::setLocale($languagues[0]);
        }else{
            App::setLocale($languagues['en']);
        }
     
        return $next($request);
    }
}
