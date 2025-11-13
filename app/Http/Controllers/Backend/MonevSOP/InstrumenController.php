<?php

namespace App\Http\Controllers\Backend\MonevSOP;

use App\Http\Controllers\Controller;
use App\Services\InstrumenMonevService;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Session;
use Yajra\DataTables\DataTables;
use Illuminate\Support\Str;

class InstrumenController extends Controller
{
    protected $instrumenMonevService;

    public function __construct(InstrumenMonevService $instrumenMonevService)
    {
        $this->instrumenMonevService = $instrumenMonevService;
    }

    # F01

    public function indexF01(){
        $data['title'] = "Instrumen F01 Monev SOP";
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
                'title' => 'Instrumen F01 Monev',
                'url'   => null,
                'active' => true,
            ],
        ];

        $data['count'] = $this->instrumenMonevService->countF01();

        return view('backend.monev-sop.instrumen.form1.index', $data);
    }

    public function datatableF01(Request $request){
        $datas = $this->instrumenMonevService->allF01();

        if(!empty($request->kategori)){
            $datas = $datas->where('kategori', $request->kategori);
        }

        if(!empty($request->aspek)){
            $datas = $datas->where('aspek', $request->aspek);
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
            ->addColumn('nama_instrumen', function($row){
                return $row->instrumen;
            })
            ->addColumn('kategori_instrumen', function($row){
                return $row->kategori;
            })
            ->addColumn('aspek_instrumen', function($row){
                return $row->aspek;
            })
            ->addColumn('catatan_deskripsi', function($row){
                return $row->catatan_des ?? '-';
            })->addColumn('tl_deskripsi', function($row){
                return $row->tindak_lanjut_des ?? '-';
            })
            ->rawColumns(['action','nama_instrumen', 'kategori_instrumen', 'aspek_instrumen', 'catatan_deskripsi', 'tl_deskripsi'])
            ->make(true);
    }

    public function ajaxShowDetailF01($id)
    {
        $data['detail'] = $this->instrumenMonevService->findF01($id);
        $data['kategori'] = [
            'ya/tidak' => 'YA/TIDAK',
        ];
        
        $data['aspek'] = [
            'Monitoring Pelaksanaan SOP' => 'Monitoring Pelaksanaan SOP',
            'Evaluasi Penerapan SOP' => 'Evaluasi Penerapan SOP',
            'Additional Question' => 'Pertanyaan Tambahan'
        ];

        $html = view('backend.monev-sop.instrumen.form1.modal_form', $data)->render();
        $response['html'] = $html;
        return response()->json($response);
    }

    public function storeF01(Request $request)
    {
        // dd($request);
        $request->validate([
            'instrumen' => 'required',
            'kategori' => 'required',
            'aspek' => 'required',
        ]);

        DB::beginTransaction();
        try {
            $msg = $request->id ? 'Successfully updated' : 'Successfully created';
            if($request->id){
                InstrumenMonevService::updateF01($request->id, $request);
            } else {
                InstrumenMonevService::createF01($request);
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

    public static function ajaxShowFilterF01()
    {
        $data['kategori'] = [
            'ya/tidak' => 'YA/TIDAK',
        ];
        
        $data['aspek'] = [
            'Monitoring Pelaksanaan SOP' => 'Monitoring Pelaksanaan SOP',
            'Evaluasi Penerapan SOP' => 'Evaluasi Penerapan SOP',
            'Pertanyaan Tambahan' => 'Pertanyaan Tambahan'
        ];

        $html = view('backend.monev-sop.instrumen.form1.modal_filter', $data)->render();
        $response['html'] = $html;

        return response()->json($response);
    }

    public function deleteF01($id)
    {
        DB::beginTransaction();
        try {
            $instrumen = InstrumenMonevService::findF01(decrypt($id));
            if($instrumen){
                $data = InstrumenMonevService::deleteF01(decrypt($id));
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

    # F02

    public function indexF02(){
        $data['title'] = "Instrumen F02 Monev SOP";
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
                'title' => 'Instrumen F02 Monev',
                'url'   => null,
                'active' => true,
            ],
        ];

        $data['count'] = $this->instrumenMonevService->countF02();

        return view('backend.monev-sop.instrumen.form2.index', $data);
    }

    public function datatableF02(Request $request){
        $datas = $this->instrumenMonevService->allF02();

        if(!empty($request->kategori)){
            $datas = $datas->where('kategori', $request->kategori);
        }

        if(!empty($request->aspek)){
            $datas = $datas->where('aspek', $request->aspek);
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
            ->addColumn('nama_instrumen', function($row){
                return $row->instrumen;
            })
            ->addColumn('kategori_instrumen', function($row){
                return $row->kategori;
            })
            ->addColumn('aspek_instrumen', function($row){
                return $row->aspek;
            })
            ->rawColumns(['action','nama_instrumen', 'kategori_instrumen', 'aspek_instrumen'])
            ->make(true);
    }

    public function ajaxShowDetailF02($id)
    {
        $data['detail'] = $this->instrumenMonevService->findF02($id);
        $data['kategori'] = [
            'ya/tidak' => 'YA/TIDAK',
        ];
        
        $data['aspek'] = [
            'Evaluasi Pemenuhan SOP' => 'Evaluasi Pemenuhan SOP',
            'Evaluasi Substansi SOP' => 'Evaluasi Substansi SOP'
        ];

        $html = view('backend.monev-sop.instrumen.form2.modal_form', $data)->render();
        $response['html'] = $html;
        return response()->json($response);
    }

    public function storeF02(Request $request)
    {
        // dd($request);
        $request->validate([
            'instrumen' => 'required',
            'kategori' => 'required',
            'aspek' => 'required',
        ]);

        DB::beginTransaction();
        try {
            $msg = $request->id ? 'Successfully updated' : 'Successfully created';
            if($request->id){
                InstrumenMonevService::updateF02($request->id, $request);
            } else {
                InstrumenMonevService::createF02($request);
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

    public static function ajaxShowFilterF02()
    {
        $data['kategori'] = [
            'ya/tidak' => 'YA/TIDAK',
        ];
        
        $data['aspek'] = [
            'Evaluasi Pemenuhan SOP' => 'Evaluasi Pemenuhan SOP',
            'Evaluasi Substansi SOP' => 'Evaluasi Substansi SOP'
        ];

        $html = view('backend.monev-sop.instrumen.form2.modal_filter', $data)->render();
        $response['html'] = $html;

        return response()->json($response);
    }

    public function deleteF02($id)
    {
        DB::beginTransaction();
        try {
            $instrumen = InstrumenMonevService::findF02(decrypt($id));
            if($instrumen){
                $data = InstrumenMonevService::deleteF02(decrypt($id));
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
