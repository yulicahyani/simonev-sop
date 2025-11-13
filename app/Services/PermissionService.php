<?php

namespace App\Services;

use App\Models\Permission;
use App\Models\PermissionRole;

class PermissionService {

    public static function all(){
        $all = Permission::select('*');
        return $all;
    }

    public static function count(){
        $count = Permission::count();
        return $count;
    }

    public static function find($id){
        return Permission::find($id);
    }

    public static function create($request){
        $create = Permission::create([
            'is_parent' => $request->is_parent,
            'parent_id' => $request->parent_id,
            'name' => $request->name,
            'display_name' => $request->display_name
        ]);
        return $create;
    }

    public static function update($id, $request)
    {
        $update = Permission::where('id', $id)->update([
            'is_parent' => $request->is_parent,
            'parent_id' => $request->parent_id,
            'name' => $request->name,
            'display_name' => $request->display_name
        ]);
        return $update;
    }

    public static function delete($id){
        $delete = Permission::where('id', $id)->delete();
        return $delete;
    }

    private $listPermission = "";
    public function rekursive_permission($parent = 0, $role_id = 0){
		$permissions = PermissionRole::get_permission_role($parent, $role_id);
		if(!empty($permissions)){
            $this->listPermission .= "<ul>";
            foreach ($permissions as $value) {
                $opened = '"opened": true';
                $selected = (!empty($value->role_id) && $value->is_parent == 'n') ? '"selected": true' : '';
                $this->listPermission .= "<li id='" . $value->id . "' data-jstree='{ " . $selected . " }'>";
                $this->listPermission .= $value->display_name;
                $this->rekursive_permission($value->id, $role_id);
                $this->listPermission .= '</li>';
            }
            $this->listPermission .= "</ul>";
        }
	}

	public function show_permission($role_id){
        $this->rekursive_permission(0, $role_id);
        return $this->listPermission;
    }
}