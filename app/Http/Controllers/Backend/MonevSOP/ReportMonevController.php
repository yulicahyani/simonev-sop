<?php

namespace App\Http\Controllers\Backend\MonevSOP;

use App\Http\Controllers\Controller;
use App\Models\InstrumenF01;
use App\Models\PeriodeMonev;
use App\Models\ResultMonev;
use App\Models\ResultMonevF01;
use App\Models\SOP;
use App\Models\Unit;
use App\Services\InstrumenMonevService;
use App\Services\PeriodeMonevService;
use App\Services\ResultMonevService;
use Barryvdh\DomPDF\Facade\Pdf;
use Carbon\Carbon;
use Illuminate\Http\Request;

class ReportMonevController extends Controller
{
    public function cetakReportSopPdfPublic($id)
    {
        $monev = ResultMonev::findOrFail($id);

        $sop = SOP::findOrFail($monev->sop_id);

        $data_periode = PeriodeMonev::findOrFail($monev->periode_monev_id);

        $periode = Carbon::parse($data_periode->start_date)->translatedFormat('d F Y') . ' s.d. ' .
                   Carbon::parse($data_periode->end_date)->translatedFormat('d F Y');

        $unit = Unit::where('code', $monev->unit_code)->first();

        $signaturePath = storage_path('app/private/'.$monev->ttd_path_pelaksana_sop);

        $result_f01 = ResultMonevF01::where('result_monev_f01.result_monev_id', $monev->id)
                    ->join('instrumen_f01', 'instrumen_f01.id', '=', 'result_monev_f01.instrumen_id')
                    ->select('result_monev_f01.*', 'instrumen_f01.instrumen', 'instrumen_f01.aspek', 'instrumen_f01.tindak_lanjut_des')
                    ->get();

        // dd($result_f01);

        $instrumens = InstrumenMonevService::allF01();

        $pdf = Pdf::loadView('backend.monev-sop.report.pdf-monev-sop', compact('sop', 'monev', 'periode', 'unit','signaturePath', 'result_f01', 'instrumens'))
                  ->setPaper('A4', 'portrait');

        return $pdf->stream('Hasil_Monev_SOP_' . $sop->id . '_' . Carbon::now()->format('Ymd_His'). '.pdf');
    }

    public function cetakReportSopPdf($id)
    {
        $monev = ResultMonev::findOrFail(decrypt($id));

        $sop = SOP::findOrFail($monev->sop_id);

        $data_periode = PeriodeMonev::findOrFail($monev->periode_monev_id);

        $periode = Carbon::parse($data_periode->start_date)->translatedFormat('d F Y') . ' s.d. ' .
                   Carbon::parse($data_periode->end_date)->translatedFormat('d F Y');

        $unit = Unit::where('code', $monev->unit_code)->first();

        $signaturePath = storage_path('app/private/'.$monev->ttd_path_pelaksana_sop);

        $result_f01 = ResultMonevF01::where('result_monev_f01.result_monev_id', $monev->id)
                    ->join('instrumen_f01', 'instrumen_f01.id', '=', 'result_monev_f01.instrumen_id')
                    ->select('result_monev_f01.*', 'instrumen_f01.instrumen', 'instrumen_f01.aspek', 'instrumen_f01.tindak_lanjut_des')
                    ->get();

        // dd($result_f01);

        $instrumens = InstrumenMonevService::allF01();

        $pdf = Pdf::loadView('backend.monev-sop.report.pdf-monev-sop', compact('sop', 'monev', 'periode', 'unit','signaturePath', 'result_f01', 'instrumens'))
                  ->setPaper('A4', 'portrait');

        return $pdf->stream('Hasil_Monev_SOP_' . $sop->id . '_' . Carbon::now()->format('Ymd_His'). '.pdf');
    }

    public function cetakReportSopUnitPdf($unit_code, Request $request)
    {
        $unit = Unit::where('code', decrypt($unit_code))->firstOrFail();

        // Periode bisa diambil dari input request atau set default
        $periode = $request->periode ? PeriodeMonev::where('id', $request->periode)->firstOrFail() : PeriodeMonevService::activePeriode();
        $periodeId = $periode->id;

        // Ambil semua SOP beserta hasil monev dan jawaban
        $sops = SOP::with(['monev' => function ($q) use ($periodeId) {
                    $q->where('periode_monev_id', $periodeId)
                    ->with('result_f01');
                }])
                ->where('unit_code', decrypt($unit_code))
                ->whereHas('monev', function ($q) use ($periodeId) {
                    $q->where('periode_monev_id', $periodeId);
                })
                ->get();

        // dd($sops);

        $instrumens = InstrumenMonevService::allF01();

        // dd($instrumens);

        // Buat PDF
        $pdf = Pdf::loadView('backend.monev-sop.report.pdf-monev-sop-unit', compact('unit', 'sops', 'periode','instrumens'))
                  ->setPaper('a4', 'portrait');

        $filename = 'Laporan_Hasil_Monev_SOP_' . str_replace(' ', '_', $unit->name) . '_' . Carbon::now()->format('Ymd_His'). '.pdf';
        return $pdf->stream($filename);
    }

}
