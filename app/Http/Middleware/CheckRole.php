<?php

namespace App\Http\Middleware;

use App\Helpers\GateHelper;
use App\Helpers\GateSupportHelper;
use App\Models\Permission;
use App\Models\PermissionRole;
use Closure;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Gate;

class CheckRole
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
		// Gate::setUserResolver(function(){
		// 	return true;
		// });

		if(session('UserIsAuthenticated')){

			session(['defaultRoleCode' => (session('defaultRoleCode') == null) ? 0 : session('defaultRoleCode')]);

			$role_id = [];
			$permission_user = [];
			
			if(session('defaultRoleCode') != ''){
				$role_id[] = session('defaultRoleCode');
			}else{
				foreach (session('authUserData')->roles as $role) {
					$role_id[] = $role->role_code;
					// $role_id[] = $role->id;
				}
			}



			$permissions = Permission::all();
			$permission_roles = PermissionRole::whereIn('role_id', $role_id)->get();
			// dd($permissions->pluck('id'), $permission_roles->pluck('permission_id',), session('defaultRoleCode'), $role_id);

			// dd(
			// 	$role_id,
			// 	$permission_roles->pluck('role_id', 'permission_id'),
			// 	$permissions->pluck('name', 'id')
			// );
			
			foreach ($permissions as $permission) {
				Gate::define($permission->name, function() use ($permission, $permission_roles){
					return $permission_roles->contains('permission_id', $permission->id);
				});
			}


		}else{
			session(['urlToRedirect'=>$request->url()]);
            return redirect('authenticateToSSO');
		}

        return $next($request);
    }
}