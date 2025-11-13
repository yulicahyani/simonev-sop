<?php

namespace App\Services;

use App\Models\InstrumenF01;
use App\Models\InstrumenF02;
use App\Models\PeriodeMonev;
use App\Models\Permission;
use App\Models\PermissionRole;

class InstrumenMonevService {

    ## F01

    public static function allF01(){
        $all = InstrumenF01::select('*')->orderBy('aspek', 'desc')->get();
        return $all;
    }

    public static function countF01(){
        $count = InstrumenF01::count();
        return $count;
    }

    public static function findF01($id){
        return InstrumenF01::find($id);
    }

    public static function createF01($request){
        $create = InstrumenF01::create([
            'instrumen' => $request->instrumen,
            'kategori' => $request->kategori,
            'aspek' => $request->aspek,
            'catatan_des' => $request->catatan_des,
            'tindak_lanjut_des' => $request->tindak_lanjut_des,
        ]);
        return $create;
    }

    public static function updateF01($id, $request)
    {
        $update = InstrumenF01::where('id', $id)->update([
            'instrumen' => $request->instrumen,
            'kategori' => $request->kategori,
            'aspek' => $request->aspek,
            'catatan_des' => $request->catatan_des,
            'tindak_lanjut_des' => $request->tindak_lanjut_des,
        ]);
        return $update;
    }

    public static function deleteF01($id){
        $delete = InstrumenF01::where('id', $id)->delete();
        return $delete;
    }

    ## F02

    public static function allF02(){
        $all = InstrumenF02::select('*')->orderBy('aspek', 'asc')->get();
        return $all;
    }

    public static function countF02(){
        $count = InstrumenF02::count();
        return $count;
    }

    public static function findF02($id){
        return InstrumenF02::find($id);
    }

    public static function createF02($request){
        $create = InstrumenF02::create([
            'instrumen' => $request->instrumen,
            'kategori' => $request->kategori,
            'aspek' => $request->aspek,
        ]);
        return $create;
    }

    public static function updateF02($id, $request)
    {
        $update = InstrumenF02::where('id', $id)->update([
            'instrumen' => $request->instrumen,
            'kategori' => $request->kategori,
            'aspek' => $request->aspek,
        ]);
        return $update;
    }

    public static function deleteF02($id){
        $delete = InstrumenF02::where('id', $id)->delete();
        return $delete;
    }
}