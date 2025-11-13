<?php

namespace App\Http\Controllers\Backend\KonsultasiSOP;

use App\Http\Controllers\Controller;
use App\Models\KonsultasiOnline;
use App\Models\ResultMonev;
use App\Models\SOP;
use App\Models\User;
use App\Models\UserMapping;
use App\Models\UserSSO;
use App\Services\InstrumenMonevService;
use App\Services\PeriodeMonevService;
use Illuminate\Http\Request;
use Yajra\DataTables\DataTables;

class DashboardKonsultasiController extends Controller
{
    public function getKonsultasiSopStats($unitCode)
    {
        $konsultasi = KonsultasiOnline::all();
        $totalKonsultasiSop = $konsultasi->where('unit_code', $unitCode)->count();
        $konsultasiSelesai = $konsultasi->where('unit_code', $unitCode)->where('status', 'Selesai')->count();
        $konsultasiProsesRevisi = $konsultasi->where('unit_code', $unitCode)->where('status', 'Proses Revisi')->count();

        return response()->json([
            'total' => $totalKonsultasiSop,
            'konsultasi_selesai' => $konsultasiSelesai,
            'konsultasi_proses_revisi' => $konsultasiProsesRevisi,
        ]);
    }
}
