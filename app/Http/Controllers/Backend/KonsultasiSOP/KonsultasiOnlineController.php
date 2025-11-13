<?php

namespace App\Http\Controllers\Backend\KonsultasiSOP;

use App\Helpers\FileHelper;
use App\Http\Controllers\Controller;
use App\Models\KonsultasiOnline;
use App\Models\KonsultasiOnlineChatting;
use App\Models\Unit;
use App\Services\KonsultasiOnlineService;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Session;
use Yajra\DataTables\DataTables;
use Illuminate\Support\Facades\Storage;

class KonsultasiOnlineController extends Controller
{
    public function index(){
        $data['title'] = "Konsultasi SOP (Online)";
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
                'title' => 'Unit/PD Konsultasi',
                'url'   => null,
                'active' => true,
            ],
        ];

        $data['count'] = Unit::all()->count();
        if(session('defaultRoleCode') == 'opd'){
            $data['count'] = Unit::where('code', session('unit_code') )->count();
        }

        return view('backend.konsultasi-sop.konsultasi-online.index', $data);
    }

    public function datatable(Request $request){

        $datas = Unit::all();
        if(session('defaultRoleCode') == 'opd'){
            $datas = Unit::where('code', session('unit_code') );
        }

        $konsultasi = KonsultasiOnline::all();

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
                            <div class="menu-item px-3" title="Klik untuk konsultasi penyusunan/perbaikan SOP unit/PD ini secara online" data-bs-toggle="tooltip" data-bs-trigger="hover">
                                <a href="'.route("konsultasi-sop-online.konsultasi.index", encrypt($row->code)).'" class="dropdown-item menu-link px-3">Konsultasi SOP</a>
                            </div>
                        </div>';

                return $btn;
            })
            ->addColumn('nama', function($row){
                return $row->name;
            })
            ->addColumn('total_konsultasi', function($row) use ($konsultasi){
                $count = $konsultasi->where('unit_code', $row->code)->count();
                return $count;
            })
            ->addColumn('total_selesai', function($row) use ($konsultasi){
                $count = $konsultasi->where('unit_code', $row->code)->where('status', 'Selesai')->count();
                return $count;
            })
            ->addColumn('total_proses_revisi', function($row) use ($konsultasi){
                $count = $konsultasi->where('unit_code', $row->code)->where('status', 'Proses Revisi')->count();
                return $count;
            })
            ->rawColumns(['action','nama', 'total_konsultasi', 'total_selesai', 'total_proses_revisi'])
            ->make(true);
    }

    public function indexKonsultasi($unit_code){
        $data['title'] = "Daftar Konsultasi SOP";
        $data['breadcrumbs'] = [
            [
                'title' => 'Beranda',
                'url'   => route('backend.beranda'),
                'active' => false,
            ],
            [
                'title' => 'Konsultasi SOP (Online)',
                'url'   => null,
                'active' => false,
            ],
            [
                'title' => 'Unit Konsultasi',
                'url'   => route('konsultasi-sop-online.index'),
                'active' => true,
            ],
            [
                'title' => 'Daftar Konsultasi SOP',
                'url'   => null,
                'active' => true,
            ],
        ];

        $data['unit'] = Unit::where('code', decrypt($unit_code))->first();
        $data['count'] = KonsultasiOnline::where('unit_code', decrypt($unit_code))->count();

        return view('backend.konsultasi-sop.konsultasi-online.konsultasi.index', $data);
    }

    public function datatableKonsultasi($unit_code, Request $request){

        $datas = KonsultasiOnline::where('unit_code', $unit_code)->orderBy('created_at', 'desc')->get();

        if(!empty($request->created_by)){
            $datas = $datas->where('created_by', $request->created_by);
        }

        if(!empty($request->status)){
            $datas = $datas->where('status', $request->status);
        }

        return DataTables::of($datas)
            ->addIndexColumn()
            ->addColumn('action', function($row){

                $btn = '
                        <a href="#" class="btn btn-sm btn-light btn-active-light-primary fs-6" 
                        data-kt-menu-trigger="click" 
                        data-kt-menu-placement="bottom-end">
                            Actions
                            <span class="svg-icon svg-icon-5 rotate-180 ms-2 me-0"><i class="bi bi-chevron-down"></i></span>
                        </a>
                        <div class="menu menu-sub menu-sub-dropdown menu-column menu-rounded menu-gray-600 menu-state-bg-light-primary fw-semibold fs-6 w-150px py-4"
                            data-kt-menu="true" 
                            data-kt-menu-attach="body">';
                         if($row->status == 'Proses Revisi'){
                            $btn .= '<div class="menu-item px-3" title="Klik untuk mengakhiri sesi konsultasi" data-bs-toggle="tooltip" data-bs-trigger="hover">
                                <button class="dropdown-item menu-link px-3" onclick="doneKonsultasiForm(this)"
                                    data-string="'.$row->id.'"  data-status="Selesai">Akhiri Konsultasi</button>
                            </div>';
                         } else {
                            $btn .= '<div class="menu-item px-3" title="Klik untuk buka kembali sesi konsultasi" data-bs-toggle="tooltip" data-bs-trigger="hover">
                                <button class="dropdown-item menu-link px-3" onclick="openKonsultasiForm(this)"
                                    data-string="'.$row->id.'" data-status="Proses Revisi">Buka Konsultasi</button>
                            </div>';
                         }
                            $btn .= '<div class="menu-item px-3" title="Klik untuk menghapus data" data-bs-toggle="tooltip" data-bs-trigger="hover">
                                <button class="dropdown-item menu-link px-3" onclick="deleteForm(this)"
                                    data-string="'.encrypt($row->id).'">Hapus Konsultasi</button>
                            </div>
                        </div>';

                return $btn;
            })
            ->addColumn('konsultasi', function($row){
                $btn = '
                        <a href="'.route('konsultasi-sop-online.konsultasi-sop.room.index', [encrypt($row->unit_code), encrypt($row->id)]).'">
                            <button type="button" class="btn btn-sm btn-light-primary me-3 fs-6" title="Klik untuk mengisi form monev" data-bs-toggle="tooltip" data-bs-trigger="hover"
                                    ><i class="bi bi-chat-dots fs-4"></i>Konsultasi</button>
                        </a>';
                return $btn;
            })
            ->addColumn('status_konsultasi', function($row){
                $sts = $row->status == 'Selesai' ? '<div class="badge badge-light-success fs-7 px-2"><i class="bi bi-check2-square fs-7 me-1 text-success"></i>Selesai</div>' : '<div class="badge badge-light-warning fs-7 px-2"><i class="bi bi-pencil-square fs-7 me-1 text-warning"></i>Proses Revisi</div>';
                $status_form_sop = '<div class="text-center">
                                                '.$sts.'
                                            </div>';
                return $status_form_sop;             
            })
            ->addColumn('nama_pembuat', function($row){
                return $row->created_by;
            })
            ->addColumn('nama_sop_konsultasi', function($row){
                return $row->nama_sop;
            })
            ->addColumn('tgl_pembuatan', function($row){
                return Carbon::parse($row->created_at)->translatedFormat('d M Y H:i');
            })
            ->rawColumns(['action','konsultasi', 'status_konsultasi', 'nama_pembuat', 'nama_sop_konsultasi', 'tgl_pembuatan'])
            ->make(true);
    }

    public static function ajaxShowFilter()
    {
        $data['status'] = [
            'Proses Revisi' => 'Proses Revisi',
            'Selesai' => 'Selesai'];
        
        $data['user'] = KonsultasiOnline::all()->unique('created_by');

        $html = view('backend.konsultasi-sop.konsultasi-online.konsultasi.modal_filter', $data)->render();
        $response['html'] = $html;

        return response()->json($response);
    }

    public function formatBytes($bytes, $precision = 2) {
        $units = ['B', 'KB', 'MB', 'GB', 'TB'];
        $bytes = max($bytes, 0);
        $pow = floor(($bytes ? log($bytes) : 0) / log(1024));
        $pow = min($pow, count($units) - 1);
        $bytes /= (1 << (10 * $pow));
        return round($bytes, $precision) . ' ' . $units[$pow];
    }

    public function indexDetailKonsultasi($unit_code, $konsultasi_id=null){
        $data['konsultasi_id'] = $konsultasi_id? decrypt($konsultasi_id) : $konsultasi_id;
        $data['konsultasi'] = KonsultasiOnline::find($data['konsultasi_id']);
        $data['konsultasi_chatting'] = KonsultasiOnlineChatting::where('konsultasi_online_id',$data['konsultasi_id'])
                                        ->orderBy('created_at', 'asc')
                                        ->get();
        $data['konsultasi_chatting']->transform(function($chat) {
            if ($chat->filename_konsultasi && Storage::disk('local')->exists($chat->filepath_konsultasi)) {
                $chat->file_size = KonsultasiOnlineController::formatBytes(Storage::disk('local')->size($chat->filepath_konsultasi));
            } else {
                $chat->file_size = '';
            }
            return $chat;
        });

        $unit_code = decrypt($unit_code);
        $data['unit'] = Unit::where('code', $unit_code)->first();
        $data['units'] = Unit::select('id','name','code')->get();

        $data['title'] = "Konsultasi SOP";
        $data['breadcrumbs'] = [
            [
                'title' => 'Beranda',
                'url'   => route('backend.beranda'),
                'active' => false,
            ],
            [
                'title' => 'Konsultasi SOP (Online)',
                'url'   => null,
                'active' => false,
            ],
            [
                'title' => 'Unit Konsultasi',
                'url'   => route('konsultasi-sop-online.index'),
                'active' => true,
            ],
            [
                'title' => 'Daftar Konsultasi SOP',
                'url'   => route("konsultasi-sop-online.konsultasi.index", encrypt($unit_code)),
                'active' => true,
            ],
            [
                'title' => 'Room Konsultasi',
                'url'   => null,
                'active' => true,
            ]
        ];

        return view('backend.konsultasi-sop.konsultasi-online.konsultasi.konsultasi', $data);
    }

    public function storeKonsultasi(Request $request)
    {
        // dd($request);
        $request->validate([
            'nama_sop' => 'required',
            'created_by' => 'required',
            'user_id' => 'required',
            'created_at' => 'required',
            'unit_code' => 'required',
            'created_by_chat' => 'required',
            'user_id_chat' => 'required',
            'role_code_chat' => 'required',
            'message_konsultasi' => 'nullable',
            'file_konsultasi' => 'mimes:pdf|max:2048'
        ]);

        DB::beginTransaction();
        try {
            $msg = $request->id ? 'Successfully Sent' : 'Successfully created';
            if($request->id){
                $prefix = 'file';
                $filename = '';
                $filepath = '';

                if($request->hasFile('file_konsultasi')){
                    $store = FileHelper::saveFile(
                        $request->file('file_konsultasi'),
                        "private/konsultasi",
                        $prefix ,
                        'local'
                    );
                    $filepath = $store[0];
                    $file = $request->file('file_konsultasi');
                    $filename = $file->getClientOriginalName();
                }

                $dataKonsultasiChatting = array(
                    'konsultasi_online_id' => $request->id,
                    'message_konsultasi' => $request->message_konsultasi,
                    'filename_konsultasi' => $filename,
                    'filepath_konsultasi' => $filepath,
                    'user_id' => $request->user_id,
                    'role_code' => $request->role_code_chat,
                    'created_by' => $request->created_by_chat,
                    // 'status' => 'Unread',
                );

                $konsultasi_chatting = KonsultasiOnlineService::creatKonsultasiChatting($dataKonsultasiChatting);
            } else {

                $dataKonsultasi = array(
                    'nama_sop' => $request->nama_sop,
                    'unit_code' => $request->unit_code,
                    'user_id' => $request->user_id,
                    'created_by' => $request->created_by,
                    'status' => 'Proses Revisi',
                );

                $konsultasi = KonsultasiOnlineService::createKonsultasi($dataKonsultasi);

                $prefix = 'file';
                $filename = '';
                $filepath = '';

                if($request->hasFile('file_konsultasi')){
                    $store = FileHelper::saveFile(
                        $request->file('file_konsultasi'),
                        "private/konsultasi",
                        $prefix ,
                        'local'
                    );
                    $filepath = $store[0];
                    $file = $request->file('file_konsultasi');
                    $filename = $file->getClientOriginalName();
                }

                $dataKonsultasiChatting = array(
                    'konsultasi_online_id' => $konsultasi->id,
                    'message_konsultasi' => $request->message_konsultasi,
                    'filename_konsultasi' => $filename,
                    'filepath_konsultasi' => $filepath,
                    'user_id' => $request->user_id_chat,
                    'role_code' => $request->role_code_chat,
                    'created_by' => $request->created_by_chat,
                    // 'status' => 'Unread',
                );

                $konsultasi_chatting = KonsultasiOnlineService::creatKonsultasiChatting($dataKonsultasiChatting);
            }
            DB::commit();
            Session::flash('message', 'Success');
            Session::flash('alert-data', $msg);
            return redirect()->route('konsultasi-sop-online.konsultasi-sop.room.index',[encrypt($request->unit_code), encrypt($konsultasi_chatting->konsultasi_online_id)])->with('status', $msg);
        } catch (\Throwable $th) {
            DB::rollback();
            Session::flash('message', 'Failed');
            Session::flash('alert-data', 'Gagal diubah ' . $th->getMessage());
            return back()->with('status', 'Unsuccessfully updated');
        }
    }

    public function updateStatusKonsultasi($id, $status)
    {
        DB::beginTransaction();
        try {

            $msg = $status == 'Selesai' ? 'Successfully updated status' : 'Successfully updated status';
            
            $data = KonsultasiOnlineService::updateStatusKonsultasi($id, $status);

            DB::commit();
            Session::flash('message', 'Success'); 
            Session::flash('alert-data', $msg);
            return back()->with('status', $msg); 
        } catch (\Throwable $th) {
            DB::rollback();
            Session::flash('message', 'Failed');  
            Session::flash('alert-data', 'Gagal Dihapus');
            return back()->with('status', 'Unsuccessfully deleted'); 
        }
    }

    public function deleteKonsultasi($id)
    {
        DB::beginTransaction();
        try {
            $konsultasi = KonsultasiOnlineService::find(decrypt($id));
            if($konsultasi){
                $data = KonsultasiOnlineService::delete(decrypt($id));
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
}
