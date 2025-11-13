<?php

namespace App\Services;

use App\Models\InstrumenF01;
use App\Models\InstrumenF02;
use App\Models\PeriodeMonev;
use App\Models\Permission;
use App\Models\PermissionRole;
use App\Models\ResultMonev;
use App\Models\ResultMonevF01;
use App\Models\ResultMonevF02;

class ResultMonevService {

    ## Result Monev
    public static function findResultMonevBySopID($sop_id){
        return ResultMonev::where('sop_id', $sop_id)->get();
    }

    public static function findResultMonevBySopPeriodeID($sop_id, $periode_id){
        return ResultMonev::where('sop_id', $sop_id)->where('periode_monev_id', $periode_id)->first();
    }

    public static function findResulF01ByMonevID($result_monev_id){
        return ResultMonevF01::where('result_monev_id', $result_monev_id)->get();
    }

    public static function findResulF02ByMonevID($result_monev_id){
        return ResultMonevF02::where('result_monev_id', $result_monev_id)->get();
    }

    public static function createResultMonev($data){
        
         $create = ResultMonev::create([
            'sop_id' => $data['sop_id'],
            'unit_code' => $data['unit_code'],
            'tgl_pengisian_f01' => $data['tgl_pengisian_f01'],
            'tgl_pengisian_f02' => $data['tgl_pengisian_f02'],
            'periode_monev_id' => $data['periode_monev_id'],
            'nama_pelaksana_sop' => $data['nama_pelaksana_sop'],
            'ttd_path_pelaksana_sop' => $data['ttd_path_pelaksana_sop'],
            'ttd_base64_pelaksana_sop' => $data['ttd_base64_pelaksana_sop'],
            'nama_evaluator_sop' => $data['nama_evaluator_sop'],
            'ttd_path_evaluator_sop' => $data['ttd_path_evaluator_sop'],
            'ttd_base64_evaluator_sop' => $data['ttd_base64_evaluator_sop'],
        ]);

        return $create;
    }

    public static function createResultF01($data){
         $create = ResultMonevF01::create([
            'result_monev_id' => $data['result_monev_id'],
            'instrumen_id' => $data['instrumen_id'],
            'jawaban' => $data['jawaban'],
            'catatan' => $data['catatan'],
            'tindakan' => $data['tindakan'],
        ]);

        return $create;
    }

    public static function updateResultMonevForF01($id, $data){
         $create = ResultMonev::where('id', $id)->update([
            'sop_id' => $data['sop_id'],
            'unit_code' => $data['unit_code'],
            'tgl_pengisian_f01' => $data['tgl_pengisian_f01'],
            'periode_monev_id' => $data['periode_monev_id'],
            'nama_pelaksana_sop' => $data['nama_pelaksana_sop'],
            'ttd_path_pelaksana_sop' => $data['ttd_path_pelaksana_sop'],
            'ttd_base64_pelaksana_sop' => $data['ttd_base64_pelaksana_sop'],
        ]);

        return $create;
    }

    public static function updateResultF01($id, $data)
    {
        $update = ResultMonevF01::where('id', $id)->update([
            'result_monev_id' => $data['result_monev_id'],
            'instrumen_id' => $data['instrumen_id'],
            'jawaban' => $data['jawaban'],
            'catatan' => $data['catatan'],
            'tindakan' => $data['tindakan'],
        ]);
        
        return $update;
    }

    public static function createResultF02($data){
         $create = ResultMonevF02::create([
            'result_monev_id' => $data['result_monev_id'],
            'instrumen_id' => $data['instrumen_id'],
            'jawaban' => $data['jawaban'],
            'catatan' => $data['catatan'],
            'tindakan' => $data['tindakan'],
        ]);

        return $create;
    }

    public static function updateResultMonevForF02($id, $data){
         $create = ResultMonev::where('id', $id)->update([
            'sop_id' => $data['sop_id'],
            'unit_code' => $data['unit_code'],
            'tgl_pengisian_f02' => $data['tgl_pengisian_f02'],
            'periode_monev_id' => $data['periode_monev_id'],
            'nama_evaluator_sop' => $data['nama_evaluator_sop'],
            'ttd_path_evaluator_sop' => $data['ttd_path_evaluator_sop'],
            'ttd_base64_evaluator_sop' => $data['ttd_base64_evaluator_sop'],
        ]);

        return $create;
    }

    public static function updateResultF02($id, $data)
    {
        $update = ResultMonevF02::where('id', $id)->update([
            'result_monev_id' => $data['result_monev_id'],
            'instrumen_id' => $data['instrumen_id'],
            'jawaban' => $data['jawaban'],
            'catatan' => $data['catatan'],
            'tindakan' => $data['tindakan'],
        ]);
        return $update;
    }
    
}