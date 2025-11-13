<?php

namespace App\Http\Controllers\Backend\KonsultasiSOP;

use App\Http\Controllers\Controller;
use App\Models\JadwalKonsultasi;
use App\Models\Unit;
use App\Services\JadwalKonsultasiService;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Session;
use Yajra\DataTables\DataTables;

class JadwalKonsultasiController extends Controller
{
    public function index(){
        $data['title'] = "Jadwal Konsultasi SOP";
        $data['breadcrumbs'] = [
            [
                'title' => 'Beranda',
                'url'   => route('backend.beranda'),
                'active' => false,
            ],
            [
                'title' => 'Konsultasi SOP',
                'url'   => null,
                'active' => false,
            ],
            [
                'title' => 'Jadwal Konsultasi',
                'url'   => null,
                'active' => true,
            ],
        ];

        $data['count'] = JadwalKonsultasiService::count();

        return view('backend.konsultasi-sop.konsultasi-offline.jadwal.index', $data);
    }

    public function datatable(Request $request){
        $datas = JadwalKonsultasiService::all();

        if(!empty($request->unit)){
            $datas = $datas->where('unit_code', $request->unit);
        }

        if(!empty($request->status)){
            $datas = $datas->where('status', $request->status);
        }

        if(!empty($request->date)){
            $datas = $datas->where('date', $request->date);
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
                                    data-string="'.encrypt($row->id).'" data-unitcode="'.$row->unit_code.'">Ubah</button>
                            </div>
                            <div class="menu-item px-3" title="Klik untuk mengedit data">
                                <button class="dropdown-item menu-link px-3" onclick="deleteForm(this)"
                                    data-string="'.encrypt($row->id).'" data-unitcode="'.$row->unit_code.'">Hapus</button>
                            </div>
                        </div>';

                return $btn;
            })
            ->addColumn('kegiatan', function($row){
                return $row->title;
            })
            ->addColumn('status_jadwal', function($row){
                if($row->status == 'Selesai' ){
                    $sts = '<div class="badge badge-light-success fs-7 px-2"><i class="bi bi-check-all fs-7 me-1 text-success"></i>Selesai</div>';
                } else if($row->status == 'Dijadwalkan' ){
                    $sts = '<div class="badge badge-light-primary fs-7 px-2"><i class="bi bi-calendar-check fs-7 me-1 text-primary"></i>Dijadwalkan</div>';
                } else if($row->status == 'Diajukan' ){
                    $sts = '<div class="badge badge-light-warning fs-7 px-2"><i class="bi bi-clock fs-7 me-1 text-warning"></i>Diajukan</div>';
                } else {
                    $sts = '<div class="badge badge-light-danger fs-7 px-2"><i class="bi bi-x-square fs-7 me-1 text-danger"></i>Dibatalkan</div>';
                }
                $status_form_sop = '<div class="text-center">
                                                '.$sts.'
                                            </div>';
                return $status_form_sop;             
            })
            ->addColumn('tgl', function($row){
                $formatted = Carbon::parse($row->date)->translatedFormat('j F Y');
                return $formatted;
            })
            ->addColumn('waktu', function($row){
                return Carbon::parse($row->time)->translatedFormat('H:i');
            })
            ->addColumn('unit', function($row){
                $unit = Unit::where('code', $row->unit_code)->first();
                return $unit->name;
            })
            ->addColumn('created_oleh', function($row){
                return $row->created_by;
            })
            ->rawColumns(['action','kegiatan', 'status_jadwal', 'tgl', 'waktu', 'unit', 'created_oleh'])
            ->make(true);
    }

    public static function ajaxShowFilter()
    {
        $data['status'] = [
            'Diajukan' => 'Diajukan',
            'Dijadwalkan' => 'Dijadwalkan',
            'Selesai' => 'Selesai',
            'Dibatalkan' => 'Dibatalkan',
        ];
        
        $data['units'] = Unit::all();

        $html = view('backend.konsultasi-sop.konsultasi-offline.jadwal.modal_filter', $data)->render();
        $response['html'] = $html;

        return response()->json($response);
    }

    public function delete($id)
    {
        DB::beginTransaction();
        try {
            $jadwal = JadwalKonsultasiService::find(decrypt($id));
            if($jadwal){
                $data = JadwalKonsultasiService::delete(decrypt($id));
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

    public function deleteCalendar($id)
    {
        DB::beginTransaction();
        try {
            $jadwal = JadwalKonsultasiService::find($id);
            if($jadwal){
                $data = JadwalKonsultasiService::delete($id);
            };
            DB::commit();
            return response()->json(['status' => 'success', 'message' => 'Berhasil dihapus']);
        } catch (\Throwable $th) {
            DB::rollback();
            return response()->json(['status' => 'error', 'message' => 'Gagal dihapus'], 500);
        }
    }

    public function ajaxShowDetail($id)
    {
        $data['detail'] = JadwalKonsultasiService::find($id);
        $data['status'] = [
            'Diajukan' => 'Diajukan',
            'Dijadwalkan' => 'Dijadwalkan',
            'Selesai' => 'Selesai',
            'Dibatalkan' => 'Dibatalkan',
        ];
        if(isset($data['detail'])){
            if(session('defaultRoleCode') == 'opd'){
                $data['status'] = [
                    'Selesai' => 'Selesai',
                    'Dibatalkan' => 'Dibatalkan',
                ];
            } else {
                $data['status'] = [
                    'Dijadwalkan' => 'Dijadwalkan',
                    'Selesai' => 'Selesai',
                    'Dibatalkan' => 'Dibatalkan',
                ];
            }
        }else{
            if(session('defaultRoleCode') == 'opd'){
                $data['status'] = [
                    'Diajukan' => 'Diajukan',
                ];
            } else {
                $data['status'] = [
                    'Dijadwalkan' => 'Dijadwalkan',
                ];
            }
        }
        
        $data['units'] = Unit::all();

        $html = view('backend.konsultasi-sop.konsultasi-offline.jadwal.modal_form', $data)->render();
        $response['html'] = $html;
        return response()->json($response);
    }

    public function store(Request $request)
    {
        $request->validate([
            'title' => 'required',
            'date' => 'required',
            'time' => 'required',
            'location' => 'required',
            'unit_code' => 'required',
            'status' => 'required',
        ]);

        DB::beginTransaction();
        try {
            $msg = $request->id ? 'Successfully updated' : 'Successfully created';
            if($request->id){
                JadwalKonsultasiService::update($request->id, $request);
            } else {
                JadwalKonsultasiService::create($request);
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


    public function listJadwal(){

        $jadwal = JadwalKonsultasi::all()->map(function ($row) {
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

}
