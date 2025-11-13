<?php

namespace App\Http\Controllers\Backend\ManajemenPengguna;

use App\Http\Controllers\Controller;
use App\Models\PermissionRole;
use App\Services\PermissionService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class UserPermissionsController extends Controller
{
    public function index(Request $request){
        $data['title'] = "Hak Akses Pengguna";
        $data['breadcrumbs'] = [
            [
                'title' => 'Beranda',
                'url'   => route('backend.beranda'),
                'active' => false,
            ],
            [
                'title' => 'Pengaturan',
                'url'   => null,
                'active' => false,
            ],
            [
                'title' => 'Manajemen Pengguna',
                'url'   => null,
                'active' => false,
            ],
            [
                'title' => 'Hak Akses Pengguna',
                'url'   => null,
                'active' => true,
            ],
        ];
        $data['role_id'] = (isset($request->role_id)) ? $request->role_id : '';
        return view('backend.manajemen-pengguna.user-permissions.index', $data);
    }

    public function store(Request $request)
    {
        //
        DB::beginTransaction();
		try {
			PermissionRole::where('role_id', $request->role)->delete();
            $permission = $request->permission;
            foreach ($permission as $value1) {
                $permission1 = PermissionService::find($value1);
                if($permission1->parent_id != '0'){
                    array_push($permission, $permission1->parent_id);
                }
            }
            foreach (array_unique($permission) as $value2) {
                $permission2 = PermissionService::find($value2);
                if($permission2->parent_id != '0'){
                    array_push($permission, $permission2->parent_id);
                }
            }
            foreach (array_unique($permission) as $value3) {
                $permission3 = PermissionService::find($value3);
                if($permission3->parent_id != '0'){
                    array_push($permission, $permission3->parent_id);
                }
            }
			foreach (array_unique($permission) as $permission) {
				PermissionRole::create([
					'role_id' => $request->role,
					'permission_id' => $permission,
				]);
			}
			DB::commit();
			return response()->json('Success', 201);
		} catch (\Throwable $th) {
			DB::rollback();
			return response()->json('Failed' . $th, 500);
		}
    }
}
