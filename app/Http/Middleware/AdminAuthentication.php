<?php

namespace App\Http\Middleware;

use Closure;
use Session;
use Redirect;

class AdminAuthentication
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle($request, Closure $next)
    {
        if(Session::Get('admin_ID') == "") {            
            Session::flash('error','Please login to continue!');
            return Redirect::route('get:admin:auth:login');
        }        
        
        return $next($request);
    }
}
