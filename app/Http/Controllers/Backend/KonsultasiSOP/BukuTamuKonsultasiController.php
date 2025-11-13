<?php

namespace App\Http\Controllers\Backend\KonsultasiSOP;

use App\Http\Controllers\Controller;
use App\Models\Unit;
use App\Services\BukuTamuKonsultasiService;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Session;
use Yajra\DataTables\DataTables;

class BukuTamuKonsultasiController extends Controller
{
    public function index(){
        $data['title'] = "Buku Tamu Konsultasi SOP";
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
                'title' => 'Buku Tamu',
                'url'   => null,
                'active' => true,
            ],
        ];

        $data['count'] = BukuTamuKonsultasiService::count();

        return view('backend.konsultasi-sop.konsultasi-offline.buku-tamu.index', $data);
    }

    public function datatable(Request $request){
        $datas = BukuTamuKonsultasiService::all();

        if(!empty($request->unit)){
            $datas = $datas->where('unit_code', $request->unit);
        }

        if(!empty($request->date)){
            $datas = $datas->where('tgl_konsultasi', $request->date);
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
                            <div class="menu-item px-3" title="Klik untuk mengedit data">
                                <button class="dropdown-item menu-link px-3" onclick="deleteForm(this)"
                                    data-string="'.encrypt($row->id).'">Hapus</button>
                            </div>
                        </div>';

                return $btn;
            })
            ->addColumn('kegiatan', function($row){
                return $row->kegiatan_konsultasi;
            })
            ->addColumn('unit', function($row){
                return $row->unit_nama;
            })
            ->addColumn('tgl', function($row){
                $formatted = Carbon::parse($row->tgl_konsultasi)->translatedFormat('j F Y');
                return $formatted;
            })
            ->addColumn('nama_konsul', function($row){
                return $row->nama;
            })
            ->addColumn('jabatan_konsul', function($row){
                return $row->jabatan;
            })
            ->addColumn('ttd', function($row){
                $signaturePath = $row?->ttd_path
                      ? storage_path('app/private/' . $row?->ttd_path)
                      : null;
                if($signaturePath && file_exists($signaturePath) && is_file($signaturePath)){
                    $ttd = '<img src="data:image/png;base64,'.base64_encode(file_get_contents($signaturePath)).'"
                        style="width:200px; height:auto;">';
                }else{
                    $ttd = '<em>-</em>';
                }
                return $ttd;
            })
            ->rawColumns(['action','kegiatan', 'unit', 'tgl', 'nama_konsul', 'jabatan_konsul', 'ttd'])
            ->make(true);
    }

    public static function ajaxShowFilter()
    {
        
        $data['units'] = Unit::all();

        $html = view('backend.konsultasi-sop.konsultasi-offline.buku-tamu.modal_filter', $data)->render();
        $response['html'] = $html;

        return response()->json($response);
    }

    public function delete($id)
    {
        DB::beginTransaction();
        try {
            $periode = BukuTamuKonsultasiService::find(decrypt($id));
            if($periode){
                $data = BukuTamuKonsultasiService::delete(decrypt($id));
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

    public function add(){
        $data['title'] = "Form Buku Tamu Konsultasi SOP";
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
                'title' => 'Buku Tamu',
                'url'   => route('konsultasi-sop-offline.buku-tamu.index'),
                'active' => false,
            ],
            [
                'title' => 'Form Buku Tamu',
                'url'   => null,
                'active' => true,
            ],
        ];

        $data['units'] = Unit::select('id','name','code')->get();

        return view('backend.konsultasi-sop.konsultasi-offline.buku-tamu.add', $data);
    }

    public function store(Request $request)
    {
        // dd($request);
        $request->validate([
            'kegiatan_konsultasi' => 'required',
            'tgl_konsultasi' => 'required',
            'unit_code' => 'required',
            'nama' => 'required',
            'jabatan' => 'required',
            'signature' => 'required',
        ]);

        DB::beginTransaction();
        try {
            $msg = 'Successfully created';

            BukuTamuKonsultasiService::create($request);

            DB::commit();
            Session::flash('message', 'Success');
            Session::flash('alert-data', $msg);

            if(session()->has('UserIsAuthenticated') && session('UserIsAuthenticated') == 1){
                return redirect()->route('konsultasi-sop-offline.buku-tamu.index')->with('status', $msg);
            } else {
                return response()->view('metronic.backend.partials.redirect-alert', [
                    'message' => 'Terima kasih sudah mengisi buku tamu!',
                    'redirectUrl' => route('/')
                ]);
            }
        } catch (\Throwable $th) {
            DB::rollback();
            Session::flash('message', 'Failed');
            Session::flash('alert-data', 'Gagal diubah ' . $th->getMessage());
            return back()->with('status', 'Unsuccessfully created');
        }
    }
}
