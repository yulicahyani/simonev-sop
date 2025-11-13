<?php

namespace App\Http\Controllers\Backend\Dashboard;

use App\Http\Controllers\Controller;
use App\Models\JadwalKonsultasi;
use App\Models\KonsultasiOnline;
use App\Models\PeriodeMonev;
use Illuminate\Http\Request;
use App\Models\ResultMonev;
use App\Models\SOP;
use App\Models\Unit;
use App\Models\User;
use App\Models\UserMapping;
use App\Models\UserSSO;
use App\Services\InstrumenMonevService;
use App\Services\PeriodeMonevService;
use Yajra\DataTables\DataTables;
use Illuminate\Support\Str;

class DashboardController extends Controller
{
    public function index(){
        // dd(session('authUserData'));

        $unitList = Unit::all(); 
        $periodeList = PeriodeMonevService::all();
        $periodeAktif = PeriodeMonevService::activePeriode();

        return view('backend.dashboard.index', [
            'unitList' => $unitList,
            'periodeList' => $periodeList,
            'periodeAktif' => $periodeAktif->id ?? null,
            'defaultUnit' => session('defaultRoleCode') == 'opd' ? session('unit_code') : 'all', 
            'userRole' => session('defaultRoleCode'),
        ]);
    }

    public function getMonevSopStats(Request $request)
    {
        $unit_code = $request->unit_code;
        $periode_id = $request->periode_id;

        // Jika unit_code = all → tidak filter unit
        if ($unit_code == 'all') {
            $total_sop = SOP::count();
            $sudah_monev = ResultMonev::where('periode_monev_id', $periode_id)->count();
            $unit_name = "Pemerintah Kabupaten Badung";
        } else {
            $unit = Unit::where('code', $unit_code)->first();

            $total_sop = SOP::where('unit_code', $unit_code)->count();

            $sudah_monev = ResultMonev::where('unit_code', $unit_code)
                        ->where('periode_monev_id', $periode_id)
                        ->count();

            $unit_name = $unit?->name ?? 'Pemerintah Kabupaten Badung';
        }

        $belum_monev = $total_sop - $sudah_monev;

        return response()->json([
            'total_sop'     => $total_sop,
            'sudah_monev'   => $sudah_monev,
            'belum_monev'   => $belum_monev,
            'unit_name'     => Str::words($unit_name, 3),
        ]);
    }

    public function getBarChartMonevTahunan(Request $request)
    {
        $unit_code = $request->unit_code;

        $periodes = PeriodeMonev::orderBy('periode_year')->distinct('periode_year')->get();

        $labels = [];
        $dataTidakRevisi = [];
        $dataPerluRevisi = [];

        foreach ($periodes as $periode) {
            $periodeId = $periode->id;
            $labels[] = $periode->periode_year;

            $sopsQuery = SOP::with(['monev' => function ($q) use ($periodeId) {
                $q->where('periode_monev_id', $periodeId)->with('result_f01');
            }])
            ->whereHas('monev', fn($q) => $q->where('periode_monev_id', $periodeId));

            if ($unit_code !== 'all') {
                $sopsQuery->where('unit_code', $unit_code);
            }

            $sops = $sopsQuery->get();

            $instrumens = InstrumenMonevService::allF01();
            $additionalInstrumenIds = $instrumens
                ->where('aspek', 'Additional Question')
                ->pluck('id');

            $tidakRevisi = $sops->filter(function ($sop) use ($additionalInstrumenIds) {
                return $sop->monev
                    ->flatMap->result_f01
                    ->where('jawaban', 'tidak')
                    ->whereNotIn('instrumen_id', $additionalInstrumenIds)
                    ->isEmpty();
            })->count();

            $revisi = $sops->filter(function ($sop) use ($additionalInstrumenIds) {
                return $sop->monev
                    ->flatMap->result_f01
                    ->where('jawaban', 'tidak')
                    ->whereNotIn('instrumen_id', $additionalInstrumenIds)
                    ->isNotEmpty();
            })->count();

            $dataTidakRevisi[] = $tidakRevisi;
            $dataPerluRevisi[] = $revisi;
        }

        return response()->json([
            'tahun' => $labels ?? [],
            'tidak_revisi' => $dataTidakRevisi ?? [],
            'perlu_revisi' => $dataPerluRevisi ?? [],
            'periode' => $labels[0].' - '.end($labels),
            'total_sop' => $sops->count(),
        ]);
    }

    public function getMonevSopSummary(Request $request)
    {
        $periodeId = $request->periode_id;
        $unitCode = $request->unit_code == 'all'? null : $request->unit_code;

        // Total SOP by unit & periode
        $totalSop = Sop::when($unitCode, fn($q) => $q->where('unit_code', $unitCode))
                    ->count();

        $sops = SOP::with(['monev' => function ($q) use ($periodeId) {
                    $q->where('periode_monev_id', $periodeId)
                    ->with('result_f01');
                }])
                ->when($unitCode, fn($q) => $q->where('unit_code', $unitCode))
                ->whereHas('monev', function ($q) use ($periodeId) {
                    $q->where('periode_monev_id', $periodeId);
                })
                ->get();

        $instrumens = InstrumenMonevService::allF01();

        $additionalInstrumenIds = $instrumens
            ->where('aspek', 'Additional Question')
            ->pluck('id');

        // Status count

        $rev = $sops->filter(function ($sop) use ($additionalInstrumenIds) {
            return $sop->monev->flatMap->result_f01->where('jawaban', 'tidak')->whereNotIn('instrumen_id', $additionalInstrumenIds)->isEmpty();
        })->count();

        $noRev = $sops->filter(function($sop) use ($additionalInstrumenIds){
            return $sop->monev->flatMap->result_f01->where('jawaban', 'tidak')->whereNotIn('instrumen_id', $additionalInstrumenIds)->isNotEmpty();
        })->count();

        $belum =  $totalSop - $sops->count();

        // progress unit
        $progressPercentage = $totalSop > 0 
            ? round((($rev + $noRev) / $totalSop) * 100, 2)
            : 0;

        return response()->json([
            'total_sop'        => $totalSop,
            'rev'              => $rev,
            'no_rev'           => $noRev,
            'belum'            => $belum,
            'progress_percent' => $progressPercentage,
            'sudah_monev'      => $rev + $noRev,
            'belum_monev'      => $belum
        ]);
    }


    public function datatableProgresPelaksanaSOP(Request $request)
    {
        $unit_code   = $request->unit_code == 'all'? null : $request->unit_code;
        $periode_id  = $request->periode_id;

        // Ambil periode aktif jika periode_id tidak dikirim
        $active_periode = $periode_id 
            ? PeriodeMonev::find($periode_id) 
            : PeriodeMonevService::activePeriode();

        // Ambil data pelaksana
        $datas = UserMapping::where('user_mapping.role_code', 'opd')
            ->when($unit_code, fn($q) => $q->where('user_mapping.unit_code', $unit_code))
            ->distinct('user_mapping.user_id')
            ->get();

        $totalPelaksana = $datas->count();

        return DataTables::of($datas)
            ->addColumn('nama_pelaksana', function($row){
                $user = UserSSO::find($row->user_id);
                return $user->name ?? '-';
            })
            ->addColumn('unit_name', function($row){
                $unit = Unit::where('code',$row->unit_code)->first();
                return $unit->name ?? '-';
            })
            ->addColumn('total_sop_monev', function($row) use ($active_periode) {

                $sopIDsUser = UserMapping::where('unit_code', $row->unit_code)
                    ->where('user_id', $row->user_id)
                    ->where('role_code', 'opd')
                    ->pluck('sop_id');

                $sopTargetMonevByUser = $sopIDsUser->count();

                $sopSudahMonevByUser = ResultMonev::where('unit_code', $row->unit_code)
                    ->where('periode_monev_id', $active_periode->id)
                    ->whereIn('sop_id', $sopIDsUser)
                    ->count();

                return $sopSudahMonevByUser.'/'.$sopTargetMonevByUser;
            })
            ->addColumn('progres', function($row) use ($active_periode){

                $sopIDsUser = UserMapping::where('unit_code', $row->unit_code)
                    ->where('user_id', $row->user_id)
                    ->where('role_code', 'opd')
                    ->pluck('sop_id');

                $totalUserTarget = $sopIDsUser->count();

                $userDone = ResultMonev::where('unit_code', $row->unit_code)
                    ->where('periode_monev_id', $active_periode->id)
                    ->whereIn('sop_id', $sopIDsUser)
                    ->count();

                $progress_user = $totalUserTarget > 0 
                    ? round(($userDone / $totalUserTarget) * 100) 
                    : 0;

                return '
                <div class="d-flex flex-column w-100 me-2">
                    <div class="d-flex flex-stack mb-2">
                        <span class="text-muted me-2 fs-7 fw-bold">'.$progress_user.'%</span>
                    </div>
                    <div class="progress h-6px w-100">
                        <div class="progress-bar bg-primary" role="progressbar" style="width: '.$progress_user.'%" aria-valuenow="'.$progress_user.'" aria-valuemin="0" aria-valuemax="100"></div>
                    </div>
                </div>';
            })
            ->rawColumns(['nama_pelaksana', 'total_sop_monev', 'progres', 'unit_name'])
            ->with([
                'total_pelaksana' => $totalPelaksana
            ])
            ->make(true);
    }

    public function getKonsultasiSopStats(Request $request)
    {
        $unit_code   = $request->unit_code == 'all'? null : $request->unit_code;
        $konsultasi = KonsultasiOnline::when($unit_code, fn($q) => $q->where('unit_code', $unit_code))->get();
        $totalKonsultasiSop = $konsultasi->count();
        $konsultasiSelesai = $konsultasi->where('status', 'Selesai')->count();
        $konsultasiProsesRevisi = $konsultasi->where('status', 'Proses Revisi')->count();

        return response()->json([
            'total' => $totalKonsultasiSop,
            'konsultasi_selesai' => $konsultasiSelesai,
            'konsultasi_proses_revisi' => $konsultasiProsesRevisi,
        ]);
    }

    public function listJadwal($unitCode){
        $unit_code = $unitCode == 'all'? null : $unitCode;
        $jadwal_konsultasi = JadwalKonsultasi::when($unit_code, fn($q) => $q->where('unit_code', $unit_code))->get();
        $jadwal = $jadwal_konsultasi->map(function ($row) {
            $unit = Unit::where('code', $row->unit_code)->first();

            return [
                'id' => $row->id,
                'title' => $row->title,
                'description' => $row->description,
                'location' => $row->location,
                'date' => $row->date,
                'time' => $row->time,
                'unit_code' => $row->unit_code,
                'unit' => $unit ? $unit->name : '-', // ← tambahkan nama unit
                'status' => $row->status,
            ];
        });

        return response()->json($jadwal);
    }

    public function getJadwalKonsultasiSopStats(Request $request)
    {
        $unit_code   = $request->unit_code == 'all'? null : $request->unit_code;
        $konsultasi = JadwalKonsultasi::when($unit_code, fn($q) => $q->where('unit_code', $unit_code))->get();
        $totalJadwal = $konsultasi->count();
        $jadwalSelesai = $konsultasi->where('status', 'Selesai')->count();
        $jadwalDiajukan = $konsultasi->where('status', 'Diajukan')->count();
        $jadwalDibatalkan = $konsultasi->where('status', 'Dibatalkan')->count();
        $jadwalDijadwalkan = $konsultasi->where('status', 'Dijadwalkan')->count();

        return response()->json([
            'total' => $totalJadwal,
            'jadwal_selesai' => $jadwalSelesai,
            'jadwal_diajukan' => $jadwalDiajukan,
            'jadwal_dibatalkan' => $jadwalDibatalkan,
            'jadwal_dijadwalkan' => $jadwalDijadwalkan,
        ]);
    }
}
