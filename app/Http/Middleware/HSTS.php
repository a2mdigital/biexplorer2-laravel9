<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\App;

class HSTS
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
	   
         $response = $next($request);
 
      
            $response->headers->set(
                'Strict-Transport-Security',
                'max-age=31536000; includeSubdomains',
                true
            );
        
	   
      return $next($request);
    }
}
