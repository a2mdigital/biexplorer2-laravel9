<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Support\Facades\Auth;

class RedirectIfAuthenticated
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @param  string|null  $guard
     * @return mixed
     */
    public function handle($request, Closure $next, $guard = null)
    {
        /*
        if ($guard == "parceiro" && Auth::guard($guard)->check()) {
            return redirect()->route('admin');
        }
        if ($guard == "web" && Auth::guard($guard)->check()) {
            return redirect()->route('dashboard');
        }
        */
        if (Auth::guard($guard)->check()) {
            
            return redirect()->route('login-novamente');
            //return redirect('login');
           // return redirect(RouteServiceProvider::HOME);
        }


        return $next($request);
    }

}