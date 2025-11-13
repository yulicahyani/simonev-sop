<?php

namespace App\Http\Controllers\Backend\MonevSOP;

use App\Helpers\FileHelper;
use App\Http\Controllers\Controller;
use App\Models\ResultMonev;
use App\Models\SOP;
use App\Models\Unit;
use App\Services\InstrumenMonevService;
use App\Services\PeriodeMonevService;
use App\Services\ResultMonevService;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Session;
use Yajra\DataTables\DataTables;

class FormMonevController extends Controller
{
    public function index($sop_id){


        // $datas = ResultMonev::where('result_monev.sop_id', decrypt(($sop_id)))
        //         ->join('periode_monev', 'periode_monev.id', '=', 'result_monev.periode_monev_id')
        //         ->join('sop', 'sop.id', '=', 'result_monev.sop_id')
        //         ->orderBy('periode_monev.periode_year', 'asc')
        //         ->select('result_monev.*', 'sop.nama', 'sop.nomor', 'sop.filename', 'sop.filepath', 'periode_monev.name',
        //          'periode_monev.start_date', 'periode_monev.start_date', 'periode_monev.end_date', 'periode_monev.periode_year', 'periode_monev.status')
        //         ->get();

        // dd($datas);
        $data['sop'] = SOP::withTrashed()->where('id', decrypt(($sop_id)))->first();
        $unit_code = $data['sop']->unit_code;
        $data['title'] = "Form Monev SOP";
        $data['breadcrumbs'] = [
            [
                'title' => 'Beranda',
                'url'   => route('backend.beranda'),
                'active' => false,
            ],
            [
                'title' => 'Monev SOP',
                'url'   => null,
                'active' => false,
            ],
            [
                'title' => 'Unit Monev',
                'url'   => route('monev-sop.index'),
                'active' => true,
            ],
            [
                'title' => 'Daftar SOP',
                'url'   => route('monev-sop.sop.index', encrypt($unit_code)),
                'active' => true,
            ],
            [
                'title' => 'Form Monev SOP',
                'url'   => null,
                'active' => true,
            ],
        ];
        $data['unit'] = Unit::where('code', $unit_code)->first();
        $data['active_periode'] = PeriodeMonevService::activePeriode();
        $data['result_monev'] = ResultMonevService::findResultMonevBySopPeriodeID(decrypt($sop_id), $data['active_periode']->id);
        $data['count_instrumens'] = InstrumenMonevService::allF01()->count();

        return view('backend.monev-sop.monev.sop.form.index', $data);
    }

    public function datatableResultMonevSOPPeriode($sop_id){

        $datas = ResultMonev::where('result_monev.sop_id', $sop_id)
                ->join('periode_monev', 'periode_monev.id', '=', 'result_monev.periode_monev_id')
                ->join('sop', 'sop.id', '=', 'result_monev.sop_id')
                ->select('result_monev.*', 'sop.nama', 'sop.nomor', 'sop.filename', 'sop.filepath', 'periode_monev.name',
                 'periode_monev.start_date', 'periode_monev.start_date', 'periode_monev.end_date', 'periode_monev.periode_year', 'periode_monev.status')
                ->orderBy('periode_monev.periode_year', 'asc')
                ->get();

        return DataTables::of($datas)
            ->addIndexColumn()
            ->addColumn('action', function($row){

                $btn = ' <div class="d-flex flex-row justify-content-center">
                            <a href="'.route("monev-sop.sop.report.cetak", encrypt($row->id)).'" target="_blank">
                            <button type="button" class="btn btn-sm btn-primary me-3" title="Klik untuk mengunduh hasil monev" data-bs-toggle="tooltip" data-bs-trigger="hover">
                                <i class="bi bi-file-earmark-arrow-down fs-4"></i></button>
                            </a>

                        </div>';

                return $btn;
            })
            ->addColumn('periode', function($row){
                return $row->periode_year;
            })
            ->addColumn('tgl_pelaksanaan', function($row){
                return Carbon::parse($row->start_date)->translatedFormat('j F Y').' - '.Carbon::parse($row->end_date)->translatedFormat('j F Y');
            })
            ->addColumn('status_monev', function($row){

                $start_date = Carbon::parse($row->start_date);
                $end_date   = Carbon::parse($row->end_date);
                $today      = Carbon::today();

                if ($today->between($start_date, $end_date)) {
                     $sts = '<div class="badge badge-light-warning fs-7"><i class="bi bi-clock-history text-warning me-2"></i>Dalam Proses</div>';
                } else {
                    $sts = '<div class="badge badge-light-success fs-7"><i class="bi bi-check-circle-fill text-success me-2"></i>Selesai</div>';
                }
                
                return $sts;
            })
            ->addColumn('nama_sop', function($row){
                return $row->nama;
            })
            ->addColumn('nomor_sop', function($row){
                return $row->nomor;
            })
            ->rawColumns(['action','periode', 'tgl_pelaksanaan', 'status_monev', 'nomor_sop', 'nama_sop'])
            ->make(true);
    }

    public function indexF01($sop_id){
        
        $data['sop'] = SOP::withTrashed()->where('id', decrypt(($sop_id)))->first();
        $unit_code = $data['sop']->unit_code;
        $data['title'] = "Form 01 Monev SOP";
        $data['breadcrumbs'] = [
            [
                'title' => 'Beranda',
                'url'   => route('backend.beranda'),
                'active' => false,
            ],
            [
                'title' => 'Monev SOP',
                'url'   => null,
                'active' => false,
            ],
            [
                'title' => 'Unit Monev',
                'url'   => route('monev-sop.index'),
                'active' => true,
            ],
            [
                'title' => 'Daftar SOP',
                'url'   => route('monev-sop.sop.index', encrypt($unit_code)),
                'active' => true,
            ],
            [
                'title' => 'Form Monev SOP',
                'url'   => null,
                'active' => true,
            ],
        ];
        $data['unit'] = Unit::where('code', $unit_code)->first();
        $data['active_periode'] = PeriodeMonevService::activePeriode();
        $data['units'] = Unit::select('id','name','code')->get();
        $data['periodes'] = PeriodeMonevService::all();
        $data['instrumens'] = InstrumenMonevService::allF01();
        $data['questions'] =  $data['instrumens']->map(function ($item) {
            return [
                'id' => $item->id,
                'aspek' => $item->aspek,
                'instrumen' => $item->instrumen,
                'catatan_des' => $item->catatan_des,
            ];
        })->values();
        $data['result_monev'] = ResultMonevService::findResultMonevBySopPeriodeID(decrypt($sop_id), $data['active_periode']->id);
        $data['result_f01'] = ResultMonevService::findResulF01ByMonevID(isset($data['result_monev'])? $data['result_monev']->id : '');
        $data['oldAnswers'] = $data['result_f01']->mapWithKeys(function ($item) {
            return [
                $item->instrumen_id => [
                    'f01_id' => $item->id ?? '',
                    'answer' => $item->jawaban ?? '',
                    'note' => $item->catatan ?? '',
                    'action' => $item->tindakan ?? '',
                ],
            ];
        });

        return view('backend.monev-sop.monev.sop.form.f01.index', $data);
    }

    public function storeF01(Request $request)
    {
        // dd($request);
        $request->validate([
            'sop_id' => 'required',
            'unit_code' => 'required',
            'tanggal_pengisian' => 'required',
            'periode_monev' => 'required',
            'nama_pelaksana' => 'required',
            'signature' => 'required',
            'question_ids' => 'required',
            'answers' => 'required',
        ]);

        DB::beginTransaction();
        try {
            $msg = $request->result_monev_id ? 'Successfully updated' : 'Successfully created';
            if($request->result_monev_id){

                $sign_path = FileHelper::saveSignBase64(
                    $request->signature,
                    "private/signatures/",
                    'local'
                );

                $dataResultMonev = array(
                    'sop_id' => $request->sop_id,
                    'unit_code' => $request->unit_code,
                    'tgl_pengisian_f01' => $request->tanggal_pengisian,
                    'periode_monev_id' => $request->periode_monev,
                    'nama_pelaksana_sop' => $request->nama_pelaksana,
                    'ttd_path_pelaksana_sop' => $sign_path,
                    'ttd_base64_pelaksana_sop' => $request->signature,
                );
                ResultMonevService::updateResultMonevForF01($request->result_monev_id, $dataResultMonev);

                foreach($request->question_ids as $index => $q_id){
                    
                    $data_f01 = array(
                        'result_monev_id' => $request->result_monev_id,
                        'instrumen_id' => $q_id,
                        'jawaban' => $request->answers[$index],
                        'catatan' => $request->notes[$index] ?? NULL,
                        'tindakan' => $request->actions[$index] ?? NULL,
                    );
                    

                    $result_monev_f01 = ResultMonevService::updateResultF01($request->f01_ids[$index], $data_f01);
                }
                
            } else {
                $sign_path = FileHelper::saveSignBase64(
                    $request->signature,
                    "private/signatures/",
                    'local'
                );

                $dataResultMonev = array(
                    'sop_id' => $request->sop_id,
                    'unit_code' => $request->unit_code,
                    'tgl_pengisian_f01' => $request->tanggal_pengisian,
                    'tgl_pengisian_f02' => NULL,
                    'periode_monev_id' => $request->periode_monev,
                    'nama_pelaksana_sop' => $request->nama_pelaksana,
                    'ttd_path_pelaksana_sop' => $sign_path,
                    'ttd_base64_pelaksana_sop' => $request->signature,
                    'nama_evaluator_sop' => "",
                    'ttd_path_evaluator_sop' => "",
                    'ttd_base64_evaluator_sop' => "",
                );
                $result_monev = ResultMonevService::createResultMonev($dataResultMonev);


                foreach($request->question_ids as $index => $q_id){
                    
                    $data_f01 = array(
                        'result_monev_id' => $result_monev->id,
                        'instrumen_id' => $q_id,
                        'jawaban' => $request->answers[$index],
                        'catatan' => $request->notes[$index] ?? NULL,
                        'tindakan' => $request->actions[$index] ?? NULL,
                    );

                    $result_monev_f01 = ResultMonevService::createResultF01($data_f01);
                }
            }
            DB::commit();
            Session::flash('message', 'Success');
            Session::flash('alert-data', $msg);
            return redirect()->route('monev-sop.sop.form-monev.index', encrypt($request->sop_id))->with('status', $msg);
        } catch (\Throwable $th) {
            DB::rollback();
            Session::flash('message', 'Failed');
            Session::flash('alert-data', 'Gagal diubah ' . $th->getMessage());
            return back()->with('status', 'Unsuccessfully updated');
        }
    }

    public function indexF02($sop_id){

        $data['sop'] = SOP::withTrashed()->where('id', decrypt(($sop_id)))->first();
        $unit_code = $data['sop']->unit_code;
        $data['title'] = "Form 02 Monev SOP";
        $data['breadcrumbs'] = [
            [
                'title' => 'Beranda',
                'url'   => route('backend.beranda'),
                'active' => false,
            ],
            [
                'title' => 'Monev SOP',
                'url'   => null,
                'active' => false,
            ],
            [
                'title' => 'Unit Monev',
                'url'   => route('monev-sop.index'),
                'active' => true,
            ],
            [
                'title' => 'Daftar SOP',
                'url'   => route('monev-sop.sop.index', encrypt($unit_code)),
                'active' => true,
            ],
            [
                'title' => 'Form Monev SOP',
                'url'   => null,
                'active' => true,
            ],
        ];
        $data['unit'] = Unit::where('code', $unit_code)->first();
        $data['active_periode'] = PeriodeMonevService::activePeriode();
        $data['units'] = Unit::select('id','name','code')->get();
        $data['periodes'] = PeriodeMonevService::all();
        $data['instrumens'] = InstrumenMonevService::allF02();
        $data['questions'] =  $data['instrumens']->map(function ($item) {
            return [
                'id' => $item->id,
                'aspek' => $item->aspek,
                'instrumen' => $item->instrumen,
            ];
        })->values();
        $data['result_monev'] = ResultMonevService::findResultMonevBySopPeriodeID(decrypt($sop_id), $data['active_periode']->id);
        $data['result_f02'] = ResultMonevService::findResulF02ByMonevID(isset($data['result_monev'])? $data['result_monev']->id : '');
        $data['oldAnswers'] = $data['result_f02']->mapWithKeys(function ($item) {
            return [
                $item->instrumen_id => [
                    'f02_id' => $item->id ?? '',
                    'answer' => $item->jawaban ?? '',
                    'note' => $item->catatan ?? '',
                    'action' => $item->tindakan ?? '',
                ],
            ];
        });

        return view('backend.monev-sop.monev.sop.form.f02.index', $data);
    }

    public function storeF02(Request $request)
    {
        // dd($request);
        $request->validate([
            'sop_id' => 'required',
            'unit_code' => 'required',
            'tanggal_pengisian' => 'required',
            'periode_monev' => 'required',
            'nama_evaluator' => 'required',
            'signature' => 'required',
            'question_ids' => 'required',
            'answers' => 'required',
        ]);

        DB::beginTransaction();
        try {
            $msg = $request->result_monev_id ? 'Successfully updated' : 'Successfully created';
            if($request->result_monev_id){

                $sign_path = FileHelper::saveSignBase64(
                    $request->signature,
                    "private/signatures/",
                    'local'
                );

                $dataResultMonev = array(
                    'sop_id' => $request->sop_id,
                    'unit_code' => $request->unit_code,
                    'tgl_pengisian_f02' => $request->tanggal_pengisian,
                    'periode_monev_id' => $request->periode_monev,
                    'nama_evaluator_sop' => $request->nama_evaluator,
                    'ttd_path_evaluator_sop' => $sign_path,
                    'ttd_base64_evaluator_sop' => $request->signature,
                );
                ResultMonevService::updateResultMonevForF02($request->result_monev_id, $dataResultMonev);


                foreach($request->question_ids as $index => $q_id){
                    
                    $data_f02 = array(
                        'result_monev_id' => $request->result_monev_id,
                        'instrumen_id' => $q_id,
                        'jawaban' => $request->answers[$index],
                        'catatan' => $request->notes[$index],
                        'tindakan' => $request->actions[$index] ?? NULL,
                    );

                    $result_monev_f02 = ResultMonevService::updateResultF02($request->f02_ids[$index], $data_f02);
                }
                
            } else {
                $sign_path = FileHelper::saveSignBase64(
                    $request->signature,
                    "private/signatures/",
                    'local'
                );

                $dataResultMonev = array(
                    'sop_id' => $request->sop_id,
                    'unit_code' => $request->unit_code,
                    'tgl_pengisian_f01' => NULL,
                    'tgl_pengisian_f02' => $request->tanggal_pengisian,
                    'periode_monev_id' => $request->periode_monev,
                    'nama_evaluator_sop' => $request->nama_evaluator,
                    'ttd_path_evaluator_sop' => $sign_path,
                    'ttd_base64_evaluator_sop' => $request->signature,
                    'nama_pelaksana_sop' => "",
                    'ttd_path_pelaksana_sop' => "",
                    'ttd_base64_pelaksana_sop' => "",
                );

                $result_monev = ResultMonevService::createResultMonev($dataResultMonev);


                foreach($request->question_ids as $index => $q_id){
                    
                    $data_f02 = array(
                        'result_monev_id' => $result_monev->id,
                        'instrumen_id' => $q_id,
                        'jawaban' => $request->answers[$index],
                        'catatan' => $request->notes[$index],
                        'tindakan' => $request->actions[$index] ?? NULL,
                    );

                    $result_monev_f02 = ResultMonevService::createResultF02($data_f02);
                }
            }
            DB::commit();
            Session::flash('message', 'Success');
            Session::flash('alert-data', $msg);
            return redirect()->route('monev-sop.sop.form-monev.index', encrypt($request->sop_id))->with('status', $msg);
        } catch (\Throwable $th) {
            DB::rollback();
            Session::flash('message', 'Failed');
            Session::flash('alert-data', 'Gagal diubah ' . $th->getMessage());
            return back()->with('status', 'Unsuccessfully updated');
        }
    }
}
