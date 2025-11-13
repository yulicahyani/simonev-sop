<?php

namespace App\Services;

use App\Models\InstrumenF01;
use App\Models\InstrumenF02;
use App\Models\KonsultasiOnline;
use App\Models\KonsultasiOnlineChatting;
use App\Models\PeriodeMonev;
use App\Models\Permission;
use App\Models\PermissionRole;
use App\Models\ResultMonev;
use App\Models\ResultMonevF01;
use App\Models\ResultMonevF02;

class KonsultasiOnlineService {

    public static function find($id){
        return KonsultasiOnline::find($id);
    }

    public static function createKonsultasi($data){
        
        $create = KonsultasiOnline::create([
            'nama_sop' => $data['nama_sop'],
            'unit_code' => $data['unit_code'],
            'user_id' => $data['user_id'],
            'created_by' => $data['created_by'],
            'status' => $data['status'],
        ]);

        return $create;
    }

    public static function creatKonsultasiChatting($data){
         $create = KonsultasiOnlineChatting::create([
            'konsultasi_online_id' => $data['konsultasi_online_id'],
            'message_konsultasi' => $data['message_konsultasi'],
            'filename_konsultasi' => $data['filename_konsultasi'],
            'filepath_konsultasi' => $data['filepath_konsultasi'],
            'user_id' => $data['user_id'],
            'role_code' => $data['role_code'],
            'created_by' => $data['created_by'],
            // 'status' => $data['status'],
        ]);

        return $create;
    }

    public static function updateStatusKonsultasi($id, $status)
    {
        $update = KonsultasiOnline::where('id', $id)->update([
            'status' => $status,
        ]);
        
        return $update;
    }

    public static function delete($id){
        $deleteKonsultasiChatting = KonsultasiOnlineChatting::where('konsultasi_online_id', $id)->delete();
        $deleteKonsultasi = KonsultasiOnline::where('id', $id)->delete();
        return $deleteKonsultasi;
    }
    
}