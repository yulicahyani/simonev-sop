<?php

namespace App\Http\Controllers\Backend\MonevSOP;

use App\Http\Controllers\Controller;
use App\Models\PeriodeMonev;
use App\Services\PeriodeMonevService;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Session;
use Yajra\DataTables\DataTables;
use Illuminate\Support\Str;

class PeriodeController extends Controller
{
    protected $periodeMonevService;

    public function __construct(PeriodeMonevService $periodeMonevService)
    {
        $this->periodeMonevService = $periodeMonevService;
    }

    public function index(){
        $data['title'] = "Periode Monev SOP";
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
                'title' => 'Periode Monev',
                'url'   => null,
                'active' => true,
            ],
        ];

        $data['count'] = $this->periodeMonevService->count();

        return view('backend.monev-sop.periode.index', $data);
    }

    public function datatable(Request $request){
        $datas = $this->periodeMonevService->all();

        if(!empty($request->periode_year)){
            $datas = $datas->where('periode_year', $request->periode_year);
        }

        if(!empty($request->status)){
            $datas = $datas->where('status', $request->status);
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
                            </div>
                            <div class="menu-item px-3" title="Klik untuk mengedit data">
                                <button class="dropdown-item menu-link px-3" onclick="deleteForm(this)"
                                    data-string="'.encrypt($row->id).'">Hapus</button>
                            </div>
                        </div>';

                return $btn;
            })
            ->addColumn('nama', function($row){
                return $row->name;
            })
            ->addColumn('deskripsi', function($row){
                return Str::limit($row->description, 100, '...');;
            })
            ->addColumn('tgl_mulai', function($row){
                $formatted = Carbon::parse($row->start_date)->translatedFormat('j F Y');
                return $formatted;
            })
            ->addColumn('tgl_selesai', function($row){
                $formatted = Carbon::parse($row->end_date)->translatedFormat('j F Y');
                return $formatted;
            })
            ->addColumn('tahun_periode', function($row){
                return $row->periode_year;
            })
            ->addColumn('status_periode', function($row){
                $color = config('constant.status_periode_color')[$row->status];
                $status = config('constant.status_periode')[$row->status];
                return '<p class="text-'. $color .'">
                      '.$status . '</p>';
            })
            ->rawColumns(['action','nama', 'deskripsi', 'status_periode', 'tgl_mulai', 'tgl_selesai', 'tahun_periode'])
            ->make(true);
    }

    public function ajaxShowDetail($id)
    {
        $data['detail'] = $this->periodeMonevService->find($id);
        $data['status'] = [
            'active' => 'Aktif',
            'inactive' => 'Tidak Aktif'];
        
        $data['years'] = collect(range(date('Y'), date('Y') - 4))->all();

        $html = view('backend.monev-sop.periode.modal_form', $data)->render();
        $response['html'] = $html;
        return response()->json($response);
    }

    public function store(Request $request)
    {
        // dd($request);
        $request->validate([
            'name' => 'required',
            'start_date' => 'required',
            'end_date' => 'required',
            'periode_year' => 'required',
            'status' => 'required',
            'description' => 'required',
        ]);

        DB::beginTransaction();
        try {
            $msg = $request->id ? 'Successfully updated' : 'Successfully created';
            if($request->id){
                PeriodeMonevService::update($request->id, $request);
            } else {
                PeriodeMonevService::create($request);
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

    public static function ajaxShowFilter()
    {
        $data['status'] = [
            'active' => 'Aktif',
            'inactive' => 'Tidak Aktif'];
        
        $data['years'] = collect(range(date('Y'), date('Y') - 4))->all();

        $html = view('backend.monev-sop.periode.modal_filter', $data)->render();
        $response['html'] = $html;

        return response()->json($response);
    }

    public function delete($id)
    {
        DB::beginTransaction();
        try {
            $periode = PeriodeMonevService::find(decrypt($id));
            if($periode){
                $data = PeriodeMonevService::delete(decrypt($id));
            };
            DB::commit();
            Session::flash('message', 'Success'); 
            Session::flash('alert-data','Berhasil Dihapus');
            return back()->with('status', 'Successfully created'); 
        } catch (\Throwable $th) {
            DB::rollback();
            Session::flash('message', 'Failed');  
            Session::flash('alert-data', 'Gagal Dihapus');
            return back()->with('status', 'Unsuccessfully created'); 
        }
    }
}
