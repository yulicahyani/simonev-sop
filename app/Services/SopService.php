<?php

namespace App\Services;

use App\Models\PeriodeMonev;
use App\Models\Permission;
use App\Models\PermissionRole;
use App\Models\SOP;
use App\Models\Unit;
use App\Helpers\FileHelper;

class SopService {

    public static function all(){
        $all = SOP::select('*');
        return $all;
    }

    public static function allByUnit($unit_code){
        $all = SOP::select('*')->where('unit_code', $unit_code);
        return $all;
    }

    public static function allWithTrashed(){
        $all = SOP::withTrashed()->select('*');
        return $all;
    }

    public static function allWithTrashedByUnit($unit_code){
        $all = SOP::withTrashed()->select('*')->where('unit_code', $unit_code);
        return $all;
    }

    public static function count(){
        $count = SOP::withTrashed()->count();
        return $count;
    }

    public static function find($id){
        return SOP::withTrashed()->find($id);
    }

    public static function create($request){
        $unit = Unit::where('code', $request->unit_code)->first();
        $unit_name = str_replace(' ', '_', $unit->name);
        $prefix = 'sop';

        $filename = '';
        $filepath = '';

        if($request->hasFile('file_sop')){
            $store = FileHelper::saveFile(
                $request->file('file_sop'),
                "private/sop/{$unit_name}",
                $prefix ,
                'local'
            );
            $filepath = $store[0];
            $filename = $store[1];

        }

        $create = SOP::create([
            'nama' => $request->nama,
            'nomor' => $request->nomor,
            'tgl_pembuatan' => NULL,
            'tgl_revisi' => NULL,
            'tgl_efektif' => NULL,
            'unit_code' => $request->unit_code,
            'filename' => $filename,
            'filepath' => $filepath,
        ]);
        return $create;
    }

    public static function update($id, $request)
    {
        $unit = Unit::where('code', $request->unit_code)->first();
        $unit_name = str_replace(' ', '_', $unit->name);
        $prefix = 'sop';

        $sop = SOP::withTrashed()->find($id);

        $filename = $sop->filename;
        $filepath = $sop->filepath;

        if($request->hasFile('file_sop')){
            $store = FileHelper::saveFile(
                $request->file('file_sop'),
                "private/sop/{$unit_name}",
                $prefix ,
                'local'
            );
            $filepath = $store[0];
            $filename = $store[1];

        }

        $update = SOP::withTrashed()->where('id', $id)->update([
            'nama' => $request->nama,
            'nomor' => $request->nomor,
            'tgl_pembuatan' => NULL,
            'tgl_revisi' => NULL,
            'tgl_efektif' => NULL,
            'unit_code' => $request->unit_code,
            'filename' => $filename,
            'filepath' => $filepath,
        ]);
        return $update;
    }

    public static function delete($id){
        $delete = SOP::where('id', $id)->delete();
        return $delete;
    }

    public static function restore($id){
        $restore = SOP::withTrashed()->where('id', $id)->restore();
        return $restore;
    }
}