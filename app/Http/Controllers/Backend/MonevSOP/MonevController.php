<?php

namespace App\Http\Controllers\Backend\MonevSOP;

use App\Http\Controllers\Controller;
use App\Models\ResultMonev;
use App\Models\SOP;
use App\Models\Unit;
use App\Models\UserMapping;
use App\Services\MonevService;
use App\Services\PeriodeMonevService;
use App\Services\ResultMonevService;
use App\Services\SopService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Session;
use Yajra\DataTables\DataTables;

class MonevController extends Controller
{
    protected $monevService;

    public function __construct(MonevService $monevService)
    {
        $this->monevService = $monevService;
    }

    public function index(){
        $data['title'] = "Monitoring dan Evaluasi SOP";
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
                'title' => 'Unit/PD Monev',
                'url'   => null,
                'active' => true,
            ],
        ];

        $data['count'] = $this->monevService->units()->count();
        if(session('defaultRoleCode') == 'opd'){
            $data['count'] = Unit::where('code', session('unit_code') )->count();
        }
        $data['active_periode'] = PeriodeMonevService::activePeriode();
        $data['periodes'] = PeriodeMonevService::all();

        return view('backend.monev-sop.monev.index', $data);
    }

    public function datatable(Request $request){

        $datas = $this->monevService->units();
        if(session('defaultRoleCode') == 'opd'){
            $datas = Unit::where('code', session('unit_code') );
        }

        return DataTables::of($datas)
            ->addIndexColumn()
            ->addColumn('action', function($row){

                $btn = '<a href="#" class="btn btn-sm btn-light btn-active-light-primary fs-6" 
                        data-kt-menu-trigger="click" 
                        data-kt-menu-placement="bottom-end">
                            Actions
                            <span class="svg-icon svg-icon-5 rotate-180 ms-2 me-0"><i class="bi bi-chevron-down"></i></span>
                        </a>
                        <div class="menu menu-sub menu-sub-dropdown menu-column menu-rounded menu-gray-600 menu-state-bg-light-primary fw-semibold fs-6 w-150px py-4"
                            data-kt-menu="true" 
                            data-kt-menu-attach="body">
                            <div class="menu-item px-3" title="Klik untuk mengisi form monev SOP unit ini" data-bs-toggle="tooltip" data-bs-trigger="hover">
                                <a href="'.route("monev-sop.sop.index", encrypt($row->code)).'" class="dropdown-item menu-link px-3">Form Monev</a>
                            </div>
                            <div class="menu-item px-3" title="Klik untuk melihat laporan monev SOP unit ini" data-bs-toggle="tooltip" data-bs-trigger="hover">
                                <button class="dropdown-item menu-link px-3" onclick="reportForm(this)"
                                            data-string="'.encrypt($row->code).'" data-name="'.$row->name.'">Laporan Monev</button>
                            </div>
                        </div>';

                return $btn;
            })
            ->addColumn('nama', function($row){
                return $row->name;
            })
            ->addColumn('total_sop', function($row){
                $count = SopService::allByUnit($row->code)->count();
                return $count;
            })
            ->addColumn('total_f1', function($row){
                $active_periode = PeriodeMonevService::activePeriode();
                $count = ResultMonev::where('unit_code', $row->code)->where('periode_monev_id', $active_periode->id)->whereNotNull('tgl_pengisian_f01')->count();
                return $count;
            })
            ->addColumn('persentase_f1', function($row){
                $total = SopService::allByUnit($row->code)->count();
                $active_periode = PeriodeMonevService::activePeriode();
                $f01 = ResultMonev::where('unit_code', $row->code)->where('periode_monev_id', $active_periode->id)->whereNotNull('tgl_pengisian_f01')->count();
                $persentase = $total > 0 ? ($f01  / $total) * 100 : 0;
                return $persentase.'%';
            })
            ->addColumn('status_monev', function($row){
                $total = SopService::allByUnit($row->code)->count();
                $active_periode = PeriodeMonevService::activePeriode();
                $f01 = ResultMonev::where('unit_code', $row->code)->where('periode_monev_id', $active_periode->id)->whereNotNull('tgl_pengisian_f01')->count();
                $sts_f01 = (($total != 0) && ($f01 >= $total)) ? '<div class="badge badge-light-success fs-7 px-2"><i class="bi bi-check-circle fs-7 me-1 text-success"></i>Selesai</div>' : '<div class="badge badge-light-warning fs-7 px-2"><i class="bi bi-clock-history fs-7 me-1 text-warning"></i>Dalam Proses</div>';
                // $sts_f02 = (isset($result_monev) && !empty($result_monev->tgl_pengisian_f02)) ? '<span class="text-success"> Sudah Terisi</span>' : '<span class="text-danger"> Belum Terisi</span>';
                $status_form_sop = '<div class="text-center">
                                                '.$sts_f01.'
                                            </div>';
                return $status_form_sop;             
            })
            // ->addColumn('total_f2', function($row){
            //     $active_periode = PeriodeMonevService::activePeriode();
            //     $count = ResultMonev::where('unit_code', $row->code)->where('periode_monev_id', $active_periode->id)->whereNotNull('tgl_pengisian_f02')->count();
            //     return $count;
            // })
            // ->addColumn('persentase_f2', function($row){
            //     $total = SopService::allByUnit($row->code)->count();
            //     $active_periode = PeriodeMonevService::activePeriode();
            //     $f01 = ResultMonev::where('unit_code', $row->code)->where('periode_monev_id', $active_periode->id)->whereNotNull('tgl_pengisian_f02')->count();
            //     $persentase = $total > 0 ? ($f01  / $total) * 100 : 0;
            //     return $persentase.'%';
            // })
            ->rawColumns(['action','nama', 'total_sop', 'total_f1', 'persentase_f1', 'status_monev'])
            ->make(true);
    }

    public function indexSop($unit_code){
        $data['title'] = "Daftar SOP";
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
                'url'   => null,
                'active' => true,
            ],
        ];

        $data['unit'] = Unit::where('code', decrypt($unit_code))->first();
        $data['count'] = SopService::allWithTrashedByUnit(decrypt($unit_code))->count();
        $data['active_periode'] = PeriodeMonevService::activePeriode();

        return view('backend.monev-sop.monev.sop.index', $data);
    }

    public function datatableSOP($unit_code){

        $datas = SopService::allWithTrashedByUnit($unit_code)->orderBy('nomor', 'asc')->get();
        if(session('defaultRoleCode') == 'opd'){
            $sopIds = UserMapping::where('user_id', session('id'))
                    ->where('role_code', session('defaultRoleCode'))
                    ->where('unit_code', session('unit_code'))
                    ->pluck('sop_id')
                    ->toArray();
            $datas = SOP::withTrashed()->select('*')->whereIn('id', $sopIds)->get();
        }

        return DataTables::of($datas)
            ->addIndexColumn()
            ->addColumn('action', function($row){

                $btn = '<a href="#" class="btn btn-sm btn-light btn-active-light-primary fs-6" 
                        data-kt-menu-trigger="click" 
                        data-kt-menu-placement="bottom-end">
                            Actions
                            <span class="svg-icon svg-icon-5 rotate-180 ms-2 me-0"><i class="bi bi-chevron-down"></i></span>
                        </a>
                        <div class="menu menu-sub menu-sub-dropdown menu-column menu-rounded menu-gray-600 menu-state-bg-light-primary fw-semibold fs-6 w-125px py-4"
                            data-kt-menu="true" 
                            data-kt-menu-attach="body">
                                <div class="menu-item px-3" title="Klik untuk menghapus data">
                                    <button class="dropdown-item menu-link px-3 viewdetails"
                                        data-string="'.encrypt($row->id).'">Ubah</button>
                                </div>';
                            if($row->deleted_at){
                                $btn .= '<div class="menu-item px-3" title="Klik untuk mengedit data">
                                    <button class="dropdown-item menu-link px-3" onclick="restoreForm(this)"
                                        data-string="'.encrypt($row->id).'">Restore</button>
                                </div>';
                            } else {
                                $btn .= '<div class="menu-item px-3" title="Klik untuk menghapus data">
                                    <button class="dropdown-item menu-link px-3" onclick="deleteForm(this)"
                                        data-string="'.encrypt($row->id).'">Hapus</button>
                                </div>';
                            }
                            $btn .= '
                        </div>';

                return $btn;
            })
            ->addColumn('monev', function($row){
                $btn = ' <a href="'.route("monev-sop.sop.form-monev.index", encrypt($row->id)).'">
                            <button type="button" class="btn btn-sm btn-light-primary me-3 fs-6" title="Klik untuk mengisi form monev" data-bs-toggle="tooltip" data-bs-trigger="hover"
                                    ><i class="bi bi-pencil-square fs-4"></i> Monev</button>
                        </a>';
                return $btn;
            })
            ->addColumn('status_form_sop', function($row){
                $active_periode = PeriodeMonevService::activePeriode();
                $result_monev = ResultMonevService::findResultMonevBySopPeriodeID($row->id, $active_periode->id);
                $sts_f01 = (isset($result_monev) && !empty($result_monev->tgl_pengisian_f01)) ? '<div class="badge badge-light-success fs-7 px-2"><i class="bi bi-check-circle fs-7 me-1 text-success"></i>Sudah Terisi</div>' : '<div class="badge badge-light-danger fs-7 px-2"><i class="bi bi-x-circle fs-7 me-1 text-danger"></i>Belum Terisi</div>';
                // $sts_f02 = (isset($result_monev) && !empty($result_monev->tgl_pengisian_f02)) ? '<span class="text-success"> Sudah Terisi</span>' : '<span class="text-danger"> Belum Terisi</span>';
                $status_form_sop = '<div class="text-center">
                                                '.$sts_f01.'
                                            </div>';
                return $status_form_sop;             
            })
            ->addColumn('nama_sop', function($row){
                return $row->nama;
            })
            ->addColumn('nomor_sop', function($row){
                return $row->nomor;
            })
            ->addColumn('status_sop', function($row){
                $status = 'Berlaku';
                if($row->deleted_at){
                    $status = 'Tidak Berlaku';
                }
                return '<p class="fw-bold text-black">'.$status.'</p>';
            })
            ->addColumn('file_sop', function($row){
                $btn = '<a href="'.route('file.view', ['filepath' => encrypt($row->filepath)]).'" target="_blank">
                            <button type="button" class="btn btn-sm btn-icon btn-primary text-center" title="Klik untuk mengunduh file SOP" data-bs-toggle="tooltip" data-bs-trigger="hover">
                                    <i class="bi bi-file-earmark-arrow-down-fill fs-2"></i></button>
                        </a>';
                return $btn;
            })
            ->rawColumns(['action','monev', 'status_form_sop', 'nama_sop', 'nomor_sop', 'status_sop', 'file_sop'])
            ->make(true);
    }

    public function ajaxShowDetailSop($id, $unit_code)
    {
        $data['detail'] = SopService::find($id);
        $data['units'] = Unit::select('id','name','code')->get();
        $data['unit_active'] = $data['detail']? $data['detail']->unit_code : $unit_code;

        $html = view('backend.monev-sop.monev.sop.modal_form', $data)->render();
        $response['html'] = $html;
        return response()->json($response);
    }

    public function storeSop(Request $request)
    {
        // dd($request);
        $request->validate([
            'nama' => 'required',
            'nomor' => 'required',
            'unit_code' => 'required',
            'file_sop' => 'mimes:pdf|max:2048'
        ]);

        DB::beginTransaction();
        try {
            $msg = $request->id ? 'Successfully updated' : 'Successfully created';
            if($request->id){
                SopService::update($request->id, $request);
            } else {
                SopService::create($request);
            }
            DB::commit();
            Session::flash('message', 'Success');
            Session::flash('alert-data', $msg);
            return back()->with('status', $msg);
        } catch (\Throwable $th) {
            DB::rollback();
            Session::flash('message', 'Failed');
            Session::flash('alert-data', 'Gagal diubah ' . $th->getMessage());
            return back()->with('status', 'Unsuccessfully updated');
        }
    }

    public function deleteSop($id)
    {
        DB::beginTransaction();
        try {
            $sop = SopService::find(decrypt($id));
            if($sop){
                $data = SopService::delete(decrypt($id));
            };
            DB::commit();
            Session::flash('message', 'Success'); 
            Session::flash('alert-data','Berhasil Dihapus');
            return back()->with('status', 'Successfully deleted'); 
        } catch (\Throwable $th) {
            DB::rollback();
            Session::flash('message', 'Failed');  
            Session::flash('alert-data', 'Gagal Dihapus');
            return back()->with('status', 'Unsuccessfully deleted'); 
        }
    }

    public function restoreSop($id)
    {
        DB::beginTransaction();
        try {
            $sop = SopService::find(decrypt($id));
            if($sop){
                $data = SopService::restore(decrypt($id));
            };
            DB::commit();
            Session::flash('message', 'Success'); 
            Session::flash('alert-data','Berhasil Direstore');
            return back()->with('status', 'Successfully created'); 
        } catch (\Throwable $th) {
            DB::rollback();
            Session::flash('message', 'Failed');  
            Session::flash('alert-data', 'Gagal Direstore');
            return back()->with('status', 'Unsuccessfully created'); 
        }
    }
}
