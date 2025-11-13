<?php

namespace App\Services;

use App\Helpers\FileHelper;
use App\Models\BukuTamuKonsultasi;
use App\Models\PeriodeMonev;
use App\Models\Permission;
use App\Models\PermissionRole;
use App\Models\Unit;

class BukuTamuKonsultasiService {

    public static function all(){
        $all = BukuTamuKonsultasi::select('*')->orderBy('tgl_konsultasi', 'desc')->get();
        return $all;
    }

    public static function count(){
        $count = BukuTamuKonsultasi::count();
        return $count;
    }

    public static function find($id){
        return BukuTamuKonsultasi::find($id);
    }

    public static function delete($id){
        $delete = BukuTamuKonsultasi::where('id', $id)->delete();
        return $delete;
    }

    public static function create($request){
        $sign_path = FileHelper::saveSignBase64(
                    $request->signature,
                    "private/signatures/",
                    'local'
                );

        $unit = Unit::select('id','name','code')->where('code', $request->unit_code)->first();
        $create = BukuTamuKonsultasi::create([
            'unit_code' => $request->unit_code,
            'unit_nama' => $unit->name,
            'kegiatan_konsultasi' => $request->kegiatan_konsultasi,
            'tgl_konsultasi' => $request->tgl_konsultasi,
            'nama' => $request->nama,
            'jabatan' => $request->jabatan,
            'ttd_path' => $sign_path,
        ]);
        return $create;
    }
}