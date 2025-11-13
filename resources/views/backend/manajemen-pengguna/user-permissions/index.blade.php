@extends('metronic.backend.master')

@section('css')
    <style>
    </style>
@endsection

@section('content')

<!--begin::Toolbar-->
<div class="toolbar py-5 py-lg-15" id="kt_toolbar">
    <!--begin::Container-->
    <div id="kt_toolbar_container" class="container-xxl d-flex flex-stack flex-wrap">
        <!--begin::Page title-->
        <div class="page-title d-flex flex-column me-3">
            <!--begin::Title-->
            <h1 class="d-flex text-white fw-bold my-1 fs-3">{{ $title }}</h1>
            <!--end::Title-->
            <!--begin::Breadcrumb-->
            <ul class="breadcrumb breadcrumb-separatorless fw-semibold fs-7 my-1">
                @foreach($breadcrumbs as $breadcrumb)
                    <!--begin::Item-->
                    <li class="breadcrumb-item text-white opacity-75 {{ $breadcrumb['active'] ? 'fw-bold' : '' }}">
                        @if($breadcrumb['url'])
                            <a href="{{ $breadcrumb['url'] }}" class="text-white text-hover-bg-light">
                                {{ $breadcrumb['title'] }}
                            </a>
                        @else
                            {{ $breadcrumb['title'] }}
                        @endif
                    </li>
                    <!--end::Item-->

                    @if(!$loop->last)
                        <!--begin::Item-->
                        <li class="breadcrumb-item">
                            <span class="bullet bg-white opacity-75 w-5px h-2px"></span>
                        </li>
                        <!--end::Item-->
                    @endif
                @endforeach
            </ul>
            <!--end::Breadcrumb-->
        </div>
        <!--end::Page title-->
    </div>
    <!--end::Container-->
</div>
<!--end::Toolbar-->

<!--begin::Container-->
<div id="kt_content_container" class="d-flex flex-column-fluid align-items-start container-xxl">
    <!--begin::Post-->
    <div class="content flex-row-fluid" id="kt_content">

        <!--begin::Card-->
        <div class="card">
            <!--begin::Card body-->
            <div class="card-body p-0">
                <div class="card-px pt-10 mb-4">
                    <label for="role" class="required form-label">Role Pengguna</label>
                    <select class="form-select text-dark" data-control="select2" data-placeholder="-- Role Pengguna --"
                        id="role" name="role">
                        <option></option>
                        @foreach (session('authUserData')->broker_roles as $value)
                        <option value="{{ $value->code }}" {{ ($role_id == $value->code) ? 'selected' : '' }}>
                            {{ $value->code }}</option>
                        @endforeach
                    </select>
                </div>
                <div class="card-px mb-10">
                    <label for="list_permission" class="required form-label">Hak Akses Pengguna </label>
                    <div class="rounded border border-dashed border-gray-500 p-2 p-lg-4 mb-4">
                        <div id="list_permission">
                            <?php
                                echo (new App\Services\PermissionService)->show_permission($role_id);	
                            ?>
                        </div>
                    </div>
                </div>
                <div class="card-px mt-4 mb-10">
                    <button type="button" id="save_role" class="btn btn-flex btn-primary text-right">
                        <i class="las la-save fs-2 me-2"></i>SIMPAN
                    </button>
                </div>
            </div>
            <!--end::Card body-->
        </div>
        <!--end::Card-->
    </div>
    <!--end::Post-->
</div>
<!--end::Container-->

@endsection

@section('js')
    <script src="{{ asset('metronic/assets/plugins/custom/sweetalert2/dist/sweetalert2.all.min.js') }}"></script>
    <script src="{{ asset('metronic/assets/plugins/custom/jsvalidation/jquery.validate.js') }}"></script>
    <script src="{{ asset('metronic/assets/plugins/custom/jsvalidation/additional-methods.min.js') }}"></script>

    @include('backend.manajemen-pengguna.user-permissions._js')

    <script>

    </script>
    

@endsection