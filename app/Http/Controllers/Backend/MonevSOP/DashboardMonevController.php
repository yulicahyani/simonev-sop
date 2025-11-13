<?php

namespace App\Http\Controllers\Backend\MonevSOP;

use App\Http\Controllers\Controller;
use App\Models\ResultMonev;
use App\Models\SOP;
use App\Models\User;
use App\Models\UserMapping;
use App\Models\UserSSO;
use App\Services\InstrumenMonevService;
use App\Services\PeriodeMonevService;
use Illuminate\Http\Request;
use Yajra\DataTables\DataTables;

class DashboardMonevController extends Controller
{
    public function getMonevSopStats($unitCode)
    {
        // dd($unitCode);
        $active_periode = PeriodeMonevService::activePeriode(); 

        // total SOP unit
        $totalSop = SOP::where('unit_code', $unitCode)->count();

        // total SOP yang sudah dimonev (mempunyai record monev)
        $sopSudahMonev = ResultMonev::where('unit_code', $unitCode)->where('periode_monev_id', $active_periode->id)->count();

        $sopIDsUser = UserMapping::where('unit_code', $unitCode)
                    ->where('user_id', session('id'))
                    ->where('role_code', 'opd')
                    ->pluck('sop_id');

        // total SOP yang perlu dimonev (yang belum punya record monev)
        $sopTargetMonevByUser = $sopIDsUser->count();

        // total SOP yang sudah anda monev (berdasarkan user login)
        $sopSudahMonevByUser = ResultMonev::where('unit_code', $unitCode)
            ->where('periode_monev_id', $active_periode->id)
            ->whereIn('sop_id', $sopIDsUser)
            ->count();

        $sopBelumMonevByUser = $sopTargetMonevByUser-$sopSudahMonevByUser;

        $total_pelaksana_sop = UserMapping::where('user_mapping.role_code', 'opd')
                    ->where('user_mapping.unit_code', $unitCode)
                    ->distinct('user_mapping.user_id')
                    ->count();

        return response()->json([
            'total' => $totalSop,
            'sudah_monev' => $sopSudahMonev,
            'target_monev_by_user' => $sopTargetMonevByUser,
            'sudah_monev_by_user' => $sopSudahMonevByUser,
            'belum-monev-by-user' => $sopBelumMonevByUser,
            'total_pelaksana_sop' => $total_pelaksana_sop,
        ]);
    }

    public function getMonevSopSummary($unit_code)
    {
        $active_periode = PeriodeMonevService::activePeriode(); 
        $totalSop = SOP::where('unit_code', $unit_code)->count();

        $sudahMonev =ResultMonev::where('unit_code', $unit_code)->where('periode_monev_id', $active_periode->id)->count();

        $belumMonev = $totalSop - $sudahMonev;

        $periodeId = $active_periode->id;

        $sops = SOP::with(['monev' => function ($q) use ($periodeId) {
                    $q->where('periode_monev_id', $periodeId)
                    ->with('result_f01');
                }])
                ->where('unit_code', $unit_code)
                ->whereHas('monev', function ($q) use ($periodeId) {
                    $q->where('periode_monev_id', $periodeId);
                })
                ->get();

        // dd($sops);

        $instrumens = InstrumenMonevService::allF01();

        $additionalInstrumenIds = $instrumens
            ->where('aspek', 'Additional Question')
            ->pluck('id');
        
        $tidakRevisi = $sops->filter(function ($sop) use ($additionalInstrumenIds) {
            return $sop->monev->flatMap->result_f01->where('jawaban', 'tidak')->whereNotIn('instrumen_id', $additionalInstrumenIds)->isEmpty();
        })->count();

        $revisi = $sops->filter(function($sop) use ($additionalInstrumenIds){
            return $sop->monev->flatMap->result_f01->where('jawaban', 'tidak')->whereNotIn('instrumen_id', $additionalInstrumenIds)->isNotEmpty();
        })->count();

        // // Hitung SOP revisi / tidak revisi (menggunakan instrumen)
        // $revisi = ResultMonev::whereHas('result_f01', function($q){
        //     $q->where('jawaban', 'tidak')
        //     ->where('aspek', '!=', 'Additional Question');
        // })->count();

        // $tidakRevisi = $sudahMonev - $revisi;

        // Untuk progress user login
        $sopIDsUser = UserMapping::where('unit_code', $unit_code)
                    ->where('user_id', session('id'))
                    ->where('role_code', 'opd')
                    ->pluck('sop_id');

        $totalUserTarget = $sopIDsUser->count();
        $userDone = ResultMonev::where('unit_code', $unit_code)
            ->where('periode_monev_id', $active_periode->id)
            ->whereIn('sop_id', $sopIDsUser)
            ->count();
        $userRemain = $totalUserTarget - $userDone;

        return response()->json([
            'total_sop'     => $totalSop,
            'revisi'        => $revisi,
            'tidak_revisi'  => $tidakRevisi,
            'belum_monev'   => $belumMonev,

            'user_total'    => $totalUserTarget,
            'user_done'     => $userDone,
            'user_remain'   => $userRemain,

            'progress_unit' => $totalSop > 0 ? round(($sudahMonev / $totalSop) * 100) : 0,
            'progress_user' => $totalUserTarget > 0 ? round(($userDone / $totalUserTarget) * 100) : 0,
        ]);
    }

    public function datatableProgresPelaksanaSOP($unit_code){

        $datas = UserMapping::where('user_mapping.role_code', 'opd')
                    ->where('user_mapping.unit_code', $unit_code)
                    ->distinct('user_mapping.user_id')
                    ->get();

        $totalPelaksana = $datas->count();

        return DataTables::of($datas)
            // ->addIndexColumn()
            ->addColumn('nama_pelaksana', function($row){
                $user = UserSSO::find($row->user_id);
                return $user->name;
            })
            ->addColumn('total_sop_monev', function($row){
                $active_periode = PeriodeMonevService::activePeriode(); 
                $sopIDsUser = UserMapping::where('unit_code', $row->unit_code)
                    ->where('user_id', $row->user_id)
                    ->where('role_code', 'opd')
                    ->pluck('sop_id');

                // total SOP yang perlu dimonev (yang belum punya record monev)
                $sopTargetMonevByUser = $sopIDsUser->count();

                // total SOP yang sudah anda monev (berdasarkan user login)
                $sopSudahMonevByUser = ResultMonev::where('unit_code', $row->unit_code)
                    ->where('periode_monev_id', $active_periode->id)
                    ->whereIn('sop_id', $sopIDsUser)
                    ->count();

                return $sopSudahMonevByUser.'/'.$sopTargetMonevByUser;
            })
            ->addColumn('progres', function($row){

                $active_periode = PeriodeMonevService::activePeriode(); 
                $sopIDsUser = UserMapping::where('unit_code', $row->unit_code)
                    ->where('user_id', $row->user_id)
                    ->where('role_code', 'opd')
                    ->pluck('sop_id');

                $totalUserTarget = $sopIDsUser->count();
                $userDone = ResultMonev::where('unit_code', $row->unit_code)
                    ->where('periode_monev_id', $active_periode->id)
                    ->whereIn('sop_id', $sopIDsUser)
                    ->count();

                $progress_user = $totalUserTarget > 0 ? round(($userDone / $totalUserTarget) * 100) : 0;

                $progres = '<div class="d-flex flex-column w-100 me-2">
                                <div class="d-flex flex-stack mb-2">
                                    <span class="text-muted me-2 fs-7 fw-bold">'.$progress_user.'%</span>
                                </div>
                                <div class="progress h-6px w-100">
                                    <div class="progress-bar bg-primary" role="progressbar" style="width: '.$progress_user.'%" aria-valuenow="'.$progress_user.'" aria-valuemin="0" aria-valuemax="100"></div>
                                </div>
                            </div>';
                return $progres;
            })
            ->rawColumns(['nama_pelaksana', 'total_sop_monev', 'progres'])
             ->with([
                'total_pelaksana' => $totalPelaksana
            ])
            ->make(true);
    }
}
