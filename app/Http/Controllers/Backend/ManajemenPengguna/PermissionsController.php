<?php

namespace App\Http\Controllers\Backend\ManajemenPengguna;

use App\Http\Controllers\Controller;
use App\Services\PermissionService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Crypt;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Session;
use Yajra\DataTables\DataTables;

class PermissionsController extends Controller
{
    protected $permissionService;

    public function __construct(PermissionService $permissionService)
    {
        $this->permissionService = $permissionService;
    }

    public function index(){
        $data['title'] = "Daftar Hak Akses";
        $data['breadcrumbs'] = [
            [
                'title' => 'Beranda',
                'url'   => route('backend.beranda'),
                'active' => false,
            ],
            [
                'title' => 'Pengaturan',
                'url'   => null,
                'active' => false,
            ],
            [
                'title' => 'Manajemen Pengguna',
                'url'   => null,
                'active' => false,
            ],
            [
                'title' => 'Daftar Hak Akses',
                'url'   => null,
                'active' => true,
            ],
        ];

        $data['count'] = $this->permissionService->count();

        return view('backend.manajemen-pengguna.permissions.index', $data);
    }

    public function datatable(Request $request){
        $datas = $this->permissionService->all()->orderBy('created_at', 'desc');

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
            ->addColumn('parent', function($row){
                if(!empty($row->is_parent)){
                    if($row->is_parent == 'y'){
                        $is_parent = 'Ya';
                    } else {
                        $is_parent = "Tidak";
                    }
                }
                return $is_parent;
            })
            ->addColumn('nama', function($row){
                return $row->name;
            })
            ->addColumn('display_nama', function($row){
                return $row->display_name;
            })
            ->rawColumns(['action','nama', 'display_nama', 'parent'])
            ->make(true);
    }

    public function ajaxShowDetail($id)
    {
        $data['detail'] = $this->permissionService->find($id);
        $data['is_parent'] = [
            'y' => 'Ya',
            'n' => 'Tidak'];
        $data['parent'] = $this->permissionService->all()->where('permissions.is_parent', 'y')->get();

        $html = view('backend.manajemen-pengguna.permissions.modal_form', $data)->render();
        $response['html'] = $html;
        return response()->json($response);
    }

    public function store(Request $request)
    {
        // dd($request);
        $request->validate([
            'is_parent' => 'required',
            'parent_id' => 'required',
            'name' => 'required',
            'display_name' => 'required',
        ]);

        DB::beginTransaction();
        try {
            $msg = $request->id ? 'Successfully updated' : 'Successfully created';
            if($request->id){
                PermissionService::update($request->id, $request);
            } else {
                PermissionService::create($request);
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

    public function delete($id)
    {
        DB::beginTransaction();
        try {
            $permission = PermissionService::find(decrypt($id));
            if($permission){
                $data = PermissionService::delete(decrypt($id));
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
