<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;

class CheckUserRoles
{
    public function handle($request, Closure $next, ...$roles)
    {
        $activeRoleCode = session()->get('defaultRole') ?? '';
        
        # Check roles
        if (in_array($activeRoleCode, $roles)) {
            return $next($request);
        }

        return redirect()->route('beranda');
    }
}
