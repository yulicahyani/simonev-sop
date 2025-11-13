<?php

namespace App\Services;

use App\Models\JadwalKonsultasi;
use App\Models\PeriodeMonev;
use App\Models\Permission;
use App\Models\PermissionRole;

class JadwalKonsultasiService {

    public static function all(){
        $all = JadwalKonsultasi::select('*')->orderBy('date', 'desc')->get();
        return $all;
    }

    public static function count(){
        $count = JadwalKonsultasi::count();
        return $count;
    }

    public static function find($id){
        return JadwalKonsultasi::find($id);
    }

    public static function create($request){
        $create = JadwalKonsultasi::create([
            'title' => $request->title,
            'location' => $request->location,
            'date' => $request->date,
            'time' => $request->time,
            'user_id' => $request->user_id,
            'unit_code' => $request->unit_code,
            'role_code' => $request->role_code,
            'created_by' => $request->created_by,
            'status' => $request->status,
        ]);
        return $create;
    }

    public static function update($id, $request)
    {
        $update = JadwalKonsultasi::where('id', $id)->update([
            'title' => $request->title,
            'location' => $request->location,
            'date' => $request->date,
            'time' => $request->time,
            'user_id' => $request->user_id,
            'unit_code' => $request->unit_code,
            'role_code' => $request->role_code,
            'created_by' => $request->created_by,
            'status' => $request->status,
        ]);
        return $update;
    }

    public static function delete($id){
        $delete = JadwalKonsultasi::where('id', $id)->delete();
        return $delete;
    }

    public static function updateStatus($id, $status)
    {
        $update = JadwalKonsultasi::where('id', $id)->update([
            'status' => $status,
        ]);
        return $update;
    }
}