<?php

namespace App\Http\Controllers\Backend\ManajemenPengguna;

use App\Http\Controllers\Controller;
use App\Models\Unit;
use App\Services\SopService;
use App\Services\UserService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Crypt;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Session;
use Yajra\DataTables\DataTables as DataTablesDataTables;
use Yajra\DataTables\Facades\DataTables;

class UsersController extends Controller
{
    protected $userService;

    public function __construct(UserService $userService)
    {
        $this->userService = $userService;
    }

    public function index(){

        $data['title'] = "Daftar Pengguna";
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
                'title' => 'Daftar Pengguna',
                'url'   => null,
                'active' => true,
            ],
        ];

        $users = $this->userService->getAllUser();
        $data['count'] = $users->count();

        // dd($users);

        
        return view('backend.manajemen-pengguna.user.index', $data);
    }

    public function datatable(Request $request){
        $datas = $this->userService->getAllUser();

        if(!empty($request->role_code)){
            $datas = $datas->where('role_code', $request->role_code);
        }

        if(!empty($request->unit_id)){
            $datas = $datas->where('unit_id', $request->unit_id);
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
                            <div class="menu-item px-3" title="Klik untuk melakukkan mapping SOP pada pengguna ini" data-bs-toggle="tooltip" data-bs-trigger="hover">
                                <a href="'.route("user.mapping", encrypt($row->id)).'" class="dropdown-item menu-link px-3">Mapping SOP</a>
                            </div>
                        </div>';
                return $btn;
            })
            ->addColumn('nama', function($row){
                return $row->user_name;
            })
            ->addColumn('role', function($row){
                return $row->role_name;
            })
            ->addColumn('unit', function($row){
                return $row->unit_name;
            })
            ->addColumn('status', function($row){
                $color = config('constant.status_user_color')[$row->status];
                $status = config('constant.status_user')[$row->status];
                return '<p class="text-'. $color .'">
                      '.$status . '</p>';
            })
            ->addColumn('created_date', function($row){
                return $row->created_at;
            })
            ->rawColumns(['action','nama', 'role', 'unit','status', 'created_date'])
            ->make(true);
    }

    public static function ajaxShowFilter()
    {
        $data['status'] = config('constant.status_user'); 
        $data['role'] = config('constant.user_role');
        $data['unit'] = Unit::select('id','name')->get();

        $html = view('backend.manajemen-pengguna.user.modal_filter', $data)->render();
        $response['html'] = $html;

        return response()->json($response);
    }

    public function userMappingIndex($user_role_id){

        $data['title'] = "Mapping Pengguna";
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
                'title' => 'Daftar Pengguna',
                'url'   => route('user.index'),
                'active' => false,
            ],

            [
                'title' => 'Mapping Pengguna',
                'url'   => false,
                'active' => true,
            ],
        ];

        $data['user'] = UserService::find(decrypt($user_role_id));
        $data['mapping'] = UserService::getMappingUser($data['user']->user_id, $data['user']->role_code, $data['user']->unit_code);
        $data['count'] = $data['mapping']->count();
        
        return view('backend.manajemen-pengguna.user.mapping.index', $data);
    }

    public function datatableMappingSOP($user_role_id){

        $user = UserService::find($user_role_id);
        $datas = UserService::getMappingUser($user->user_id, $user->role_code, $user->unit_code);

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
                                        data-string="'.encrypt($row->umap_id).'">Hapus</button>
                                </div>
                        </div>';

                return $btn;
            })
            ->addColumn('nomor_sop', function($row){
                return $row->nomor;
            })
            ->addColumn('nama_sop', function($row){
                return $row->nama;
            })
            ->rawColumns(['action', 'nama_sop', 'nomor_sop'])
            ->make(true);
    }

    public function datatableSOP($unit_code){

        $datas = SopService::allWithTrashedByUnit($unit_code)->orderBy('nomor', 'asc')->get();

        return DataTables::of($datas)
            ->addIndexColumn()
            ->addColumn('checkbox', function($row){
                $checkbox = '<div class="form-check form-check-sm form-check-custom form-check-solid">
                                            <input class="form-check-input sop-checkbox" type="checkbox" value="'.$row->id.'" />
                                        </div>';
                return  $checkbox;
            })
            ->addColumn('nama_sop', function($row){
                return $row->nama;
            })
            ->addColumn('nomor_sop', function($row){
                return $row->nomor;
            })
            ->rawColumns(['checkbox','monev', 'status_form_sop', 'nama_sop', 'nomor_sop', 'status_sop', 'file_sop'])
            ->make(true);
    }

    public function storeUserMapping(Request $request)
    {
        $request->validate([
            'sop_ids' => 'required',
            'user_id' => 'required|integer',
            'unit_code' => 'required|string',
            'role_code' => 'required|string',
            'user_role_id' => 'required|integer'
        ]);

        DB::beginTransaction();
        try {
            $msg = 'Successfully updated';
            $sopIds = json_decode($request->sop_ids, true);

            foreach ($sopIds as $sopId) {
                UserService::createUserMapping($request, $sopId);
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

    public function deleteMappingUser($id)
    {
        DB::beginTransaction();
        try {
            $mapping_sop = UserService::findMappingUser(decrypt($id));
            if($mapping_sop){
                $data = UserService::deleteMappingUser(decrypt($id));
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
