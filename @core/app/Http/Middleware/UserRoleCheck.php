<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Auth\Middleware\Authenticate as Middleware;
use Illuminate\Support\Facades\Auth;

class UserRoleCheck
{
    public function handle($request, Closure $next)
    {
        if ( Auth::check() && 'super_admin' === Auth::user()->role){
            return $next($request);
        }
        return redirect()->back();

    }
}
