<?php

namespace App\Services;

use App\Models\User;
use App\Models\UserMapping;
use App\Models\UserRoleSSO;

class UserService {

    public static function getAllUser(){

        $broker_id = session('authUserData')->broker_roles[0]->broker_id;

        $users = UserRoleSSO::select('user_roles.*', 'users.name as user_name', 'units.name as unit_name', 'broker_roles.code as role_code', 'broker_roles.code as role_name')
                ->where('user_roles.broker_id', '=', $broker_id)
                ->join('users', 'users.id', '=', 'user_roles.user_id')
                ->join('units', 'user_roles.unit_id', '=', 'units.id')
                ->join('broker_roles', 'user_roles.broker_role_id', '=', 'broker_roles.id')
                ->get();

        return $users;
    }

    public static function find($user_role_id){
        $broker_id = session('authUserData')->broker_roles[0]->broker_id;
        $user = UserRoleSSO::select('user_roles.*', 'users.name as user_name', 'units.name as unit_name', 'units.code as unit_code', 'broker_roles.code as role_code', 'broker_roles.code as role_name')
                ->where('user_roles.broker_id', '=', $broker_id)
                ->where('user_roles.id', '=', $user_role_id)
                ->join('users', 'users.id', '=', 'user_roles.user_id')
                ->join('units', 'user_roles.unit_id', '=', 'units.id')
                ->join('broker_roles', 'user_roles.broker_role_id', '=', 'broker_roles.id')
                ->first();

        return $user;
    }
    
    public static function getMappingUser($user_id, $role_code, $unit_code){
        $mapping = UserMapping::where('user_id', $user_id)
                    ->where('user_mapping.role_code', $role_code)
                    ->where('user_mapping.unit_code', $unit_code)
                    ->join('sop', 'sop.id', '=', 'user_mapping.sop_id')
                    ->get();

        return $mapping;
    }

    public static function createUserMapping($request, $sopId){
        $create = UserMapping::updateOrCreate(
            [
                'user_role_id' => $request->user_role_id,
                'sop_id' => $sopId
            ],
            [
                'unit_code' => $request->unit_code,
                'role_code' => $request->role_code,
                'user_id' => $request->user_id,
            ]
        );
        return $create;
    }

    public static function findMappingUser($id){
        return UserMapping::find($id);
    }

    public static function deleteMappingUser($id){
        $delete = UserMapping::where('id', $id)->delete();
        return $delete;
    }
}