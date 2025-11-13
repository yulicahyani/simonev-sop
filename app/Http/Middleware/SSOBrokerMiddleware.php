<?php

//------------------------------------------//
// SSOBrokerMiddleware Class                //
// Copyright (C) 2018 Nyoman Piarsa         //
// All right reserved                       //
//------------------------------------------//

namespace App\Http\Middleware;

use Closure;

class SSOBrokerMiddleware
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
        if(!session('UserIsAuthenticated')){
            session(['urlToRedirect'=>$request->url()]);
            return redirect('authenticateToSSO');
        }
        return  $response = $next($request);
    }
}