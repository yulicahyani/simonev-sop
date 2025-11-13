<?php

namespace App\Services;

use App\Models\PeriodeMonev;
use App\Models\Permission;
use App\Models\PermissionRole;

class PeriodeMonevService {

    public static function all(){
        $all = PeriodeMonev::select('*')->orderBy('created_at', 'asc')->get();
        return $all;
    }

    public static function count(){
        $count = PeriodeMonev::count();
        return $count;
    }

    public static function activePeriode(){
        return PeriodeMonev::where('status', 'active')->first();
    }

    public static function find($id){
        return PeriodeMonev::find($id);
    }

    public static function create($request){
        $create = PeriodeMonev::create([
            'name' => $request->name,
            'start_date' => $request->start_date,
            'end_date' => $request->end_date,
            'periode_year' => $request->periode_year,
            'description' => $request->description,
            'status' => $request->status,
        ]);
        return $create;
    }

    public static function update($id, $request)
    {
        $update = PeriodeMonev::where('id', $id)->update([
            'name' => $request->name,
            'start_date' => $request->start_date,
            'end_date' => $request->end_date,
            'periode_year' => $request->periode_year,
            'description' => $request->description,
            'status' => $request->status,
        ]);
        return $update;
    }

    public static function delete($id){
        $delete = PeriodeMonev::where('id', $id)->delete();
        return $delete;
    }
}