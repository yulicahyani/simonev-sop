@extends('metronic.backend.master')

@section('css')
    <style>
        .select2-container .select2-container--bootstrap5 {
            width: 100% !important;
        }
        /* Allow wrapping inside selected option */
        .select2-container--bootstrap5 .select2-selection__rendered {
            white-space: normal !important;
            word-break: break-word !important;
            overflow: visible !important;
            text-overflow: initial !important;
        }
        /* Kadang DataTables menambahkan wrapper dengan overflow, hilangkan efeknya */
        div.dataTables_scrollBody {
            border-left: none !important;
        }

        .loading-overlay {
            position: absolute;
            top: 0; left: 0;
            width: 100%; height: 100%;
            background: rgba(255,255,255,0.7);
            display: flex;
            align-items: center;
            justify-content: center;
            z-index: 10;
        }

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
        <!--begin::Actions-->
        <div class="d-flex align-items-center py-3 py-md-1">
            <!--begin::Button-->
            <a href="{{route('monev-sop.index')}}" data-theme="light" class="btn btn-bg-white btn-active-color-primary">
                <i class="bi bi-arrow-left-circle fs-4"></i>
                Kembali
            </a>
            <!--end::Button-->
        </div>
        <!--end::Actions-->
    </div>
    <!--end::Container-->
</div>
<!--end::Toolbar-->

<!--begin::Container-->
<div id="kt_content_container" class="d-flex flex-column-fluid align-items-start container-xxl">
    <!--begin::Post-->
    <div class="content flex-row-fluid" id="kt_content">

        <!--begin::Post-->
        <div class="content flex-row-fluid" id="kt_content">
            <!--begin::Row-->
            <div class="row g-5 g-xl-5">
                <!--begin::Col-->
                <div class="col-xl-4 mb-xl-10">
                    <!--begin::Lists Widget 19-->
                    <div class="card card-flush h-xl-100">
                        <!-- Loader -->
                        <div class="loading-overlay d-none" id="loading-sop">
                            <div class="spinner-border text-primary" role="status"></div>
                        </div>
                        <!--begin::Heading-->
                        <div class="card-header rounded bgi-no-repeat bgi-size-cover bgi-position-y-top bgi-position-x-center align-items-start h-250px"  data-theme="light">
                            <!--begin::Title-->
                            <h3 class="card-title align-items-start flex-column pt-5">
                                <span class="fw-bold fs-2x mb-3">HALO, {{ session('nama') }}</span>
                                <div class="fs-4">
                                    <span class="opacity-75">Anda memiliki sisa </span>
                                    <span class="position-relative d-inline-block">
                                        <a href="#sop_table" class="opacity-75-hover fw-bold d-block mb-1"><span id="belum-monev-by-user">0</span> SOP</a>
                                        <!--begin::Separator-->
                                        <span class="position-absolute opacity-50 bottom-0 start-0 border-2 border-body border-bottom w-100"></span>
                                        <!--end::Separator-->
                                    </span>
                                    <span class="opacity-75">yang perlu dilakukan Monitoring dan Evaluasi (Monev)</span>
                                </div>
                            </h3>
                            <!--end::Title-->
                        </div>
                        <!--end::Heading-->
                        <!--begin::Body-->
                        <div class="card-body mt-n20">
                            <!--begin::Stats-->
                            <div class="mt-n20 position-relative">
                                <!--begin::Row-->
                                <div class="row g-3 g-lg-6">
                                    <!--begin::Col-->
                                    <div class="col-6">
                                        <!--begin::Items-->
                                        <div class="bg-gray-100 bg-opacity-70 rounded-2 px-6 py-5">
                                            <!--begin::Symbol-->
                                            <div class="symbol symbol-30px me-5 mb-8">
                                                <span class="symbol-label">
                                                    <!--begin::Svg Icon | path: icons/duotune/medicine/med005.svg-->
                                                    <span class="svg-icon svg-icon-1 svg-icon-primary">
                                                        <svg width="24" height="24" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
                                                            <path d="M20 19.725V18.725C20 18.125 19.6 17.725 19 17.725H5C4.4 17.725 4 18.125 4 18.725V19.725H3C2.4 19.725 2 20.125 2 20.725V21.725H22V20.725C22 20.125 21.6 19.725 21 19.725H20Z" fill="currentColor" />
                                                            <path opacity="0.3" d="M22 6.725V7.725C22 8.325 21.6 8.725 21 8.725H18C18.6 8.725 19 9.125 19 9.725C19 10.325 18.6 10.725 18 10.725V15.725C18.6 15.725 19 16.125 19 16.725V17.725H15V16.725C15 16.125 15.4 15.725 16 15.725V10.725C15.4 10.725 15 10.325 15 9.725C15 9.125 15.4 8.725 16 8.725H13C13.6 8.725 14 9.125 14 9.725C14 10.325 13.6 10.725 13 10.725V15.725C13.6 15.725 14 16.125 14 16.725V17.725H10V16.725C10 16.125 10.4 15.725 11 15.725V10.725C10.4 10.725 10 10.325 10 9.725C10 9.125 10.4 8.725 11 8.725H8C8.6 8.725 9 9.125 9 9.725C9 10.325 8.6 10.725 8 10.725V15.725C8.6 15.725 9 16.125 9 16.725V17.725H5V16.725C5 16.125 5.4 15.725 6 15.725V10.725C5.4 10.725 5 10.325 5 9.725C5 9.125 5.4 8.725 6 8.725H3C2.4 8.725 2 8.325 2 7.725V6.725L11 2.225C11.6 1.925 12.4 1.925 13.1 2.225L22 6.725ZM12 3.725C11.2 3.725 10.5 4.425 10.5 5.225C10.5 6.025 11.2 6.725 12 6.725C12.8 6.725 13.5 6.025 13.5 5.225C13.5 4.425 12.8 3.725 12 3.725Z" fill="currentColor" />
                                                        </svg>
                                                    </span>
                                                    <!--end::Svg Icon-->
                                                </span>
                                            </div>
                                            <!--end::Symbol-->
                                            <!--begin::Stats-->
                                            <div class="m-0">
                                                <!--begin::Number-->
                                                <span id="total-sop" class="text-gray-700 fw-bolder d-block fs-2qx lh-1 ls-n1 mb-1">0</span>
                                                <!--end::Number-->
                                                <!--begin::Desc-->
                                                <span class="text-gray-500 fw-semibold fs-6">Total SOP yang dimiliki Unit/PD</span>
                                                <!--end::Desc-->
                                            </div>
                                            <!--end::Stats-->
                                        </div>
                                        <!--end::Items-->
                                    </div>
                                    <!--end::Col-->
                                    <!--begin::Col-->
                                    <div class="col-6">
                                        <!--begin::Items-->
                                        <div class="bg-gray-100 bg-opacity-70 rounded-2 px-6 py-5">
                                            <!--begin::Symbol-->
                                            <div class="symbol symbol-30px me-5 mb-8">
                                                <span class="symbol-label">
                                                    <!--begin::Svg Icon | path: icons/duotune/arrows/arr084.svg-->
                                                    <span class="svg-icon svg-icon-1 svg-icon-primary" style="width:24px; height:24px;">
                                                        {!! file_get_contents(public_path('metronic/assets/media/icons/duotune/arrows/arr084.svg')) !!}
                                                    </span>
                                                    <!--end::Svg Icon-->
                                                </span>
                                            </div>
                                            <!--end::Symbol-->
                                            <!--begin::Stats-->
                                            <div class="m-0">
                                                <!--begin::Number-->
                                                <span id="sudah-monev" class="text-gray-700 fw-bolder d-block fs-2qx lh-1 ls-n1 mb-1">0</span>
                                                <!--end::Number-->
                                                <!--begin::Desc-->
                                                <span class="text-gray-500 fw-semibold fs-6">SOP Unit/PD telah dimonev</span>
                                                <!--end::Desc-->
                                            </div>
                                            <!--end::Stats-->
                                        </div>
                                        <!--end::Items-->
                                    </div>
                                    <!--end::Col-->
                                    <!--begin::Col-->
                                    <div class="col-6">
                                        <!--begin::Items-->
                                        <div class="bg-gray-100 bg-opacity-70 rounded-2 px-6 py-5">
                                            <!--begin::Symbol-->
                                            <div class="symbol symbol-30px me-5 mb-8">
                                                <span class="symbol-label">
                                                    <!--begin::Svg Icon | path: icons/duotune/general/gen020.svg-->
                                                    <span class="svg-icon svg-icon-1 svg-icon-primary" style="width:24px; height:24px;">
                                                        {!! file_get_contents(public_path('metronic/assets/media/icons/duotune/communication/com013.svg')) !!}
                                                    </span>
                                                    <!--end::Svg Icon-->
                                                </span>
                                            </div>
                                            <!--end::Symbol-->
                                            <!--begin::Stats-->
                                            <div class="m-0">
                                                <!--begin::Number-->
                                                <span id="target-monev-by-user" class="text-gray-700 fw-bolder d-block fs-2qx lh-1 ls-n1 mb-1">0</span>
                                                <!--end::Number-->
                                                <!--begin::Desc-->
                                                <span class="text-gray-500 fw-semibold fs-6">Total SOP yang perlu anda monev</span>
                                                <!--end::Desc-->
                                            </div>
                                            <!--end::Stats-->
                                        </div>
                                        <!--end::Items-->
                                    </div>
                                    <!--end::Col-->
                                    <!--begin::Col-->
                                    <div class="col-6">
                                        <!--begin::Items-->
                                        <div class="bg-gray-100 bg-opacity-70 rounded-2 px-6 py-5">
                                            <!--begin::Symbol-->
                                            <div class="symbol symbol-30px me-5 mb-8">
                                                <span class="symbol-label">
                                                    <!--begin::Svg Icon | path: icons/duotune/general/gen013.svg-->
                                                    <span class="svg-icon svg-icon-1 svg-icon-primary" style="width:24px; height:24px;">
                                                        {!! file_get_contents(public_path('metronic/assets/media/icons/duotune/files/fil008.svg')) !!}
                                                    </span>
                                                    <!--end::Svg Icon-->
                                                </span>
                                            </div>
                                            <!--end::Symbol-->
                                            <!--begin::Stats-->
                                            <div class="m-0">
                                                <!--begin::Number-->
                                                <span id="sudah-monev-by-user" class="text-gray-700 fw-bolder d-block fs-2qx lh-1 ls-n1 mb-1">0</span>
                                                <!--end::Number-->
                                                <!--begin::Desc-->
                                                <span class="text-gray-500 fw-semibold fs-6">Total SOP yang telah anda monev</span>
                                                <!--end::Desc-->
                                            </div>
                                            <!--end::Stats-->
                                        </div>
                                        <!--end::Items-->
                                    </div>
                                    <!--end::Col-->
                                </div>
                                <!--end::Row-->
                            </div>
                            <!--end::Stats-->
                        </div>
                        <!--end::Body-->
                    </div>
                    <!--end::Lists Widget 19-->
                </div>
                <!--end::Col-->
                <!--begin::Col-->
                <div class="col-xl-8 mb-5 mb-xl-10">
                    <!--begin::Row-->
                    <div class="row g-5 g-xl-5">
                        <!-- Loader -->
                        <div class="loading-overlay d-none" id="loader-monev-summary">
                            <div class="spinner-border text-primary" role="status"></div>
                        </div>
                        <!--begin::Col-->
                        <div class="col-xl-4 mb-xl-5">
                            <!--begin::Card widget 4-->
                            <div class="card card-flush mb-5 h-md-100 mb-xl-10 pb-5">
                                <!--begin::Header-->
                                <div class="card-header pt-5">
                                    <!--begin::Title-->
                                    <div class="card-title d-flex flex-column">
                                        <!--begin::Info-->
                                        <div class="d-flex align-items-center">
                                            <!--begin::Currency-->
                                            <span class="fs-4 fw-semibold text-gray-400 me-1 align-self-start">*</span>
                                            <!--end::Currency-->
                                            <!--begin::Amount-->
                                            <span id="total-sop-summary" class="fs-2hx fw-bold text-dark me-2 lh-1 ls-n2">0</span>
                                            <!--end::Amount-->
                                            <!--begin::Badge-->
                                            <span class="badge badge-light-success fs-base">
                                            <!--begin::Svg Icon | path: icons/duotune/arrows/arr066.svg-->
                                            {{-- <span class="svg-icon svg-icon-5 svg-icon-success ms-n1">
                                                <svg width="24" height="24" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
                                                    <rect opacity="0.5" x="13" y="6" width="13" height="2" rx="1" transform="rotate(90 13 6)" fill="currentColor" />
                                                    <path d="M12.5657 8.56569L16.75 12.75C17.1642 13.1642 17.8358 13.1642 18.25 12.75C18.6642 12.3358 18.6642 11.6642 18.25 11.25L12.7071 5.70711C12.3166 5.31658 11.6834 5.31658 11.2929 5.70711L5.75 11.25C5.33579 11.6642 5.33579 12.3358 5.75 12.75C6.16421 13.1642 6.83579 13.1642 7.25 12.75L11.4343 8.56569C11.7467 8.25327 12.2533 8.25327 12.5657 8.56569Z" fill="currentColor" />
                                                </svg>
                                            </span> --}}
                                            <!--end::Svg Icon-->SOP</span>
                                            <!--end::Badge-->
                                        </div>
                                        <!--end::Info-->
                                        <!--begin::Subtitle-->
                                        <span class="text-gray-400 pt-1 fw-semibold fs-6">Unit/Perangkat Daerah</span>
                                        <!--end::Subtitle-->
                                    </div>
                                    <!--end::Title-->
                                </div>
                                <!--end::Header-->
                                <!--begin::Card body-->
                                <div class="card-body pt-2 pb-4 d-flex align-items-center">
                                    <!--begin::Chart-->
                                    <div class="d-flex flex-center me-5 pt-2">
                                        <div id="monevChart" style="min-width: 70px; min-height: 70px"></div>
                                    </div>
                                    <!--end::Chart-->
                                    <!--begin::Labels-->
                                    <div class="d-flex flex-column content-justify-center w-100">
                                        <!--begin::Label-->
                                        <div class="d-flex fs-6 fw-semibold align-items-center">
                                            <!--begin::Bullet-->
                                            <div class="bullet w-8px h-6px rounded-2 bg-danger me-3"></div>
                                            <!--end::Bullet-->
                                            <!--begin::Label-->
                                            <div class="text-gray-500 flex-grow-1 me-4">Revisi</div>
                                            <!--end::Label-->
                                            <!--begin::Stats-->
                                            <div class="fw-bolder text-gray-700 text-xxl-end" id="rev-sop">0</div>
                                            <!--end::Stats-->
                                        </div>
                                        <!--end::Label-->
                                        <!--begin::Label-->
                                        <div class="d-flex fs-6 fw-semibold align-items-center my-3">
                                            <!--begin::Bullet-->
                                            <div class="bullet w-8px h-6px rounded-2 bg-primary me-3"></div>
                                            <!--end::Bullet-->
                                            <!--begin::Label-->
                                            <div class="text-gray-500 flex-grow-1 me-4">Tidak Revisi</div>
                                            <!--end::Label-->
                                            <!--begin::Stats-->
                                            <div class="fw-bolder text-gray-700 text-xxl-end" id="no-rev-sop">0</div>
                                            <!--end::Stats-->
                                        </div>
                                        <!--end::Label-->
                                        <!--begin::Label-->
                                        <div class="d-flex fs-6 fw-semibold align-items-center">
                                            <!--begin::Bullet-->
                                            <div class="bullet w-8px h-6px rounded-2 me-3" style="background-color: #E4E6EF"></div>
                                            <!--end::Bullet-->
                                            <!--begin::Label-->
                                            <div class="text-gray-500 flex-grow-1 me-4">Belum Monev</div>
                                            <!--end::Label-->
                                            <!--begin::Stats-->
                                            <div class="fw-bolder text-gray-700 text-xxl-end" id="belum-monev-sop">0</div>
                                            <!--end::Stats-->
                                        </div>
                                        <!--end::Label-->
                                    </div>
                                    <!--end::Labels-->
                                </div>
                                <!--end::Card body-->
                            </div>
                            <!--end::Card widget 4-->
                        </div>
                        <!--end::Col-->
                        <!--begin::Col-->
                        <div class="col-xl-4 mb-5 mb-xl-5">
                            <!--begin::Card widget 5-->
                            <div class="card card-flush h-md-100 mb-xl-10">
                                <!--begin::Header-->
                                <div class="card-header pt-5">
                                    <!--begin::Title-->
                                    <div class="card-title d-flex flex-column">
                                        <!--begin::Info-->
                                        <div class="d-flex align-items-center">
                                            <!--begin::Amount-->
                                            <span id="total-unit" class="fs-2hx fw-bold text-dark me-2 lh-1 ls-n2">0</span>
                                            <!--end::Amount-->
                                            <!--begin::Badge-->
                                            <span class="badge badge-light-primary fs-base">SOP</span>
                                            <!--end::Badge-->
                                        </div>
                                        <!--end::Info-->
                                        <!--begin::Subtitle-->
                                        <span class="text-gray-400 pt-1 fw-semibold fs-6">Unit/Perangkat Daerah</span>
                                        <!--end::Subtitle-->
                                    </div>
                                    <!--end::Title-->
                                </div>
                                <!--end::Header-->
                                <!--begin::Card body-->
                                <div class="card-body d-flex align-items-end pt-0">
                                    <!--begin::Progress-->
                                    <div class="d-flex align-items-center flex-column mt-3 w-100">
                                        <div class="d-flex justify-content-between w-100 mt-auto mb-2">
                                            <span id="unit-remain" class="fw-bolder fs-6 text-dark">Sisa: 0 SOP</span>
                                            <span id="unit-percent" class="fw-bold fs-6 text-gray-400">0%</span>
                                        </div>
                                        <div class="h-8px mx-3 w-100 bg-light-success rounded">
                                            {{-- <div class="bg-success rounded h-8px" role="progressbar" style="width: 72%;" aria-valuenow="72" aria-valuemin="0" aria-valuemax="100"></div> --}}
                                            <div class="bg-success rounded h-8px" data-progress-unit style="width:0%"></div>
                                        </div>
                                    </div>
                                    <!--end::Progress-->
                                </div>
                                <!--end::Card body-->
                            </div>
                            <!--end::Card widget 5-->
                        </div>
                        <!--end::Col-->

                        <!--begin::Col-->
                        <div class="col-xl-4 mb-5 mb-xl-5">
                            <!--begin::Card widget 5-->
                            <div class="card card-flush h-md-100 mb-xl-10">
                                <!--begin::Header-->
                                <div class="card-header pt-5">
                                    <!--begin::Title-->
                                    <div class="card-title d-flex flex-column">
                                        <!--begin::Info-->
                                        <div class="d-flex align-items-center">
                                            <!--begin::Amount-->
                                            <span id="total-user" class="fs-2hx fw-bold text-dark me-2 lh-1 ls-n2">0</span>
                                            <!--end::Amount-->
                                            <!--begin::Badge-->
                                            <span class="badge badge-light-primary fs-base">SOP</span>
                                            <!--end::Badge-->
                                        </div>
                                        <!--end::Info-->
                                        <!--begin::Subtitle-->
                                        <span class="text-gray-400 pt-1 fw-semibold fs-6">yang perlu anda monev</span>
                                        <!--end::Subtitle-->
                                    </div>
                                    <!--end::Title-->
                                </div>
                                <!--end::Header-->
                                <!--begin::Card body-->
                                <div class="card-body d-flex align-items-end pt-0">
                                    <!--begin::Progress-->
                                    <div class="d-flex align-items-center flex-column mt-3 w-100">
                                        <div class="d-flex justify-content-between w-100 mt-auto mb-2">
                                            <span id="user-remain" class="fw-bolder fs-6 text-dark">Sisa: 0 SOP</span>
                                            <span id="user-percent" class="fw-bold fs-6 text-gray-400">0%</span>
                                        </div>
                                        <div class="h-8px mx-3 w-100 bg-light-success rounded">
                                            {{-- <div class="bg-success rounded h-8px" role="progressbar" style="width: 60%;" aria-valuenow="60" aria-valuemin="0" aria-valuemax="100"></div> --}}
                                            <div class="bg-success rounded h-8px" data-progress-user style="width:0%"></div>
                                        </div>
                                    </div>
                                    <!--end::Progress-->
                                </div>
                                <!--end::Card body-->
                            </div>
                            <!--end::Card widget 5-->
                        </div>
                        <!--end::Col-->
                    </div>
                    <!--end::Row-->
                    <!--begin::Tables Widget 9-->
                    <div class="card">
                        <!--begin::Header-->
                        {{-- <div class="card-header border-0 pt-5">
                            <h3 class="card-title align-items-start flex-column">
                                <span class="card-label fw-bold fs-3 mb-1">Progres Pelaksana SOP</span>
                                <span id="total_pelaksana" class="text-muted mt-1 fw-semibold fs-7">Total  pelaksana</span>
                            </h3>
                        </div> --}}
                        <!--end::Header-->
                        <!--begin::Body-->
                        <div class="card-body py-0">
                            <!--begin::Table container-->
                            <div class="table-responsive">
                                <!--begin::Table-->
                                <table class="table table-row-dashed table-row-gray-300 align-middle gs-0 gy-2" id="progres_pelaksana_table">
                                    <!--begin::Table head-->
                                    <thead>
                                        <tr class="fw-bold text-muted">
                                            <th class="min-w-200px">Nama Pelaksana</th>
                                            <th class="min-w-150px">Total SOP Monev</th>
                                            <th class="min-w-150px">Progress</th>
                                        </tr>
                                    </thead>
                                    <!--end::Table head-->
                                    <!--begin::Table body-->
                                    <tbody>
                                        {{-- <tr>
                                            <td>
                                                NI MADE YULI CAHYANI
                                            </td>
                                            <td>
                                                3/5
                                            </td>
                                            <td class="text-end">
                                                <div class="d-flex flex-column w-100 me-2">
                                                    <div class="d-flex flex-stack mb-2">
                                                        <span class="text-muted me-2 fs-7 fw-bold">60%</span>
                                                    </div>
                                                    <div class="progress h-6px w-100">
                                                        <div class="progress-bar bg-primary" role="progressbar" style="width: 60%" aria-valuenow="60" aria-valuemin="0" aria-valuemax="100"></div>
                                                    </div>
                                                </div>
                                            </td>
                                        </tr>
                                        <tr>
                                            <td>
                                                NI WAYAN YULI SARTIKA
                                            </td>
                                            <td>
                                                1/10
                                            </td>
                                            <td class="text-end">
                                                <div class="d-flex flex-column w-100 me-2">
                                                    <div class="d-flex flex-stack mb-2">
                                                        <span class="text-muted me-2 fs-7 fw-bold">10%</span>
                                                    </div>
                                                    <div class="progress h-6px w-100">
                                                        <div class="progress-bar bg-primary" role="progressbar" style="width: 10%" aria-valuenow="10" aria-valuemin="0" aria-valuemax="100"></div>
                                                    </div>
                                                </div>
                                            </td>
                                        </tr> --}}
                                    </tbody>
                                    <!--end::Table body-->
                                </table>
                                <!--end::Table-->
                            </div>
                            <!--end::Table container-->
                        </div>
                        <!--begin::Body-->
                    </div>
                    <!--end::Tables Widget 9-->
                </div>
                <!--end::Col-->
            </div>
            <!--end::Row-->

            <!--begin::Card-->
            <div class="card">
                <!--begin::Card header-->
                <div class="card-header border-0 pt-6">
                    <!--begin::Card title-->
                    <div class="card-title d-flex flex-column">
                        <h3 class="fw-bold my-1">Daftar SOP {{$unit->name}}</h3>
                        <!--begin::Count-->
                        <p class="my-1">Total {{ $count }} baris data</p>
                    </div>
                    <!--begin::Card title-->
                    <!--begin::Card toolbar-->
                    <div class="card-toolbar">
                        <!--begin::Toolbar-->
                        <div class="d-flex justify-content-end" data-kt-customer-table-toolbar="base">
                            <!--begin::Add customer-->
                            <button type="button" class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#modalAddSOP" onclick="showAddModal(this)">
                                <span class="svg-icon svg-icon-2">
												<svg width="24" height="24" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
													<rect opacity="0.5" x="11.364" y="20.364" width="16" height="2" rx="1" transform="rotate(-90 11.364 20.364)" fill="currentColor"></rect>
													<rect x="4.36396" y="11.364" width="16" height="2" rx="1" fill="currentColor"></rect>
												</svg>
											</span>
                                Tambah SOP</button>
                            <!--end::Add customer-->
                        </div>
                        <!--end::Toolbar-->
                    </div>
                    <!--end::Card toolbar-->
                </div>
                <!--end::Card header-->
                <!--begin::Card body-->
                <div class="card-body pt-0">
                    <!--begin::Table-->
                    <table class="table align-middle table-row-dashed fs-6 gy-5" id="sop_table">
                        <!--begin::Table head-->
                        <thead>
                            <!--begin::Table row-->
                            <tr class="text-start text-gray-400 fw-bold fs-7 text-uppercase gs-0">
                                <th class="min-w-50px">No</th>
                                <th class="min-w-50px">Actions</th>
                                <th class="min-w-50px text-center">Monev</th>
                                <th class="min-w-125px text-center">Status Monev</th>
                                <th class="min-w-150px">Nama SOP</th>
                                <th class="min-w-100px">Nomor SOP</th>
                                <th class="min-w-70px">Status SOP</th>
                                <th class="min-w-70px">File SOP</th>
                            </tr>
                            <!--end::Table row-->
                            
                        </thead>
                        <!--end::Table head-->
                        <!--begin::Table body-->
                        <tbody class="fw-semibold text-gray-600">
                        </tbody>
                        <!--end::Table body-->
                    </table>
                    <!--end::Table-->
                </div>
                <!--end::Card body-->
            </div>
            <!--end::Card-->
        </div>
        <!--end::Post-->

    </div>
    <!--end::Post-->
</div>
<!--end::Container-->

{{--modal tambah periode start--}}
    <div class="modal fade" id="modalAddSOP" tabindex="-1" aria-labelledby="add_master_label" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <p class="fs-4 my-auto">Form SOP</p>
                    <i class="fa-solid fa-circle-xmark fs-4 cursor-pointer text-gray-700 text-hover-danger" data-bs-dismiss="modal" aria-label="Close"></i>
                </div>
                <form id="form_store_sop" action="{{ route('monev-sop.sop.store') }}" enctype="multipart/form-data" method="POST" autocomplete="off">
                    @csrf
                    <div class="modal-body">
                        <table class="" id="tblempinfo">
                            <tbody></tbody>
                        </table>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-light btn-active-light-primary me-2 btn-sm" data-bs-dismiss="modal">Batal</button>
                        <button type="submit" class="btn btn-primary btn-sm">
                            <i class="fa-solid fa-floppy-disk fs-5 me-2"></i>
                            Simpan
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
    {{--modal tambah periode end--}}

    {{-- Delete Form --}}
    <form id="delete_form" action="" method="POST" hidden>
        @csrf
    </form>

    {{-- Restore Form --}}
    <form id="restore_form" action="" method="POST" hidden>
        @csrf
    </form>

@endsection

@section('js')
    <script src="{{ asset('metronic/assets/plugins/custom/sweetalert2/dist/sweetalert2.all.min.js') }}"></script>
    <script src="{{ asset('metronic/assets/plugins/custom/jsvalidation/jquery.validate.js') }}"></script>
    <script src="{{ asset('metronic/assets/plugins/custom/jsvalidation/additional-methods.min.js') }}"></script>

    <script>
    document.addEventListener("DOMContentLoaded", function () {

        let loader_summary = document.getElementById("loader-monev-summary");

        fetch('{{ route("dashboard.monev-sop.summary", ["unit_code" => $unit->code]) }}')
            .then(res => res.json())
            .then(data => {

                // ==== Set Text Values ====
                document.getElementById('total-sop-summary').innerText = data.total_sop ?? 0;

                document.getElementById('rev-sop').innerText = data.revisi  ?? 0;
                document.getElementById('no-rev-sop').innerText = data.tidak_revisi  ?? 0;
                document.getElementById('belum-monev-sop').innerText = data.belum_monev  ?? 0;

                document.getElementById('total-unit').innerText = data.total_sop ?? 0;
                document.getElementById('unit-remain').innerText = `Sisa: ${data.belum_monev ?? 0} SOP`;
                document.getElementById('unit-percent').innerText = `${data.progress_unit ?? 0}%`;

                document.getElementById('total-user').innerText = data.user_total ?? 0;
                document.getElementById('user-remain').innerText = `Sisa: ${data.user_remain ?? 0} SOP`;
                document.getElementById('user-percent').innerText = `${data.progress_user ?? 0}%`;

                // ==== Progress Bars ====
                document.querySelector('[data-progress-unit]').style.width = `${data.progress_unit ?? 0}%`;
                document.querySelector('[data-progress-user]').style.width = `${data.progress_user ?? 0}%`;

                // ==== Donut Chart ====
                var options = {
                    series: [
                        data.revisi ?? 0,
                        data.tidak_revisi ?? 0,
                        data.belum_monev ?? 0
                    ],
                    chart: { type: 'donut', height: 120 },
                    labels: ['Revisi', 'Tidak Revisi', 'Belum Monev'],
                    legend: { show: false },
                    colors: ['#F1416C', '#388da8', '#E4E6EF'],
                    // dataLabels: { enabled: false }
                };

                var chartInstanceSummary = new ApexCharts(document.querySelector("#monevChart"), options);
                chartInstanceSummary.render();

            })
            .catch(err => console.error("Error load summary:", err))
            .finally(() => {
                loader_summary.classList.add("d-none");
            });

    });
    </script>


    <script>
        document.addEventListener("DOMContentLoaded", function () {

            let loader = document.getElementById('loading-sop');
            loader.classList.remove('d-none'); // show loading

            fetch('{{ route("dashboard.monev-sop.stats", ["unit_code" => $unit->code]) }}')
                .then(response => response.json())
                .then(data => {
                    // console.log(data);
                    document.getElementById('belum-monev-by-user').innerText = data.belum_monev_by_user ?? 0;
                    document.getElementById('total-sop').innerText = data.total ?? 0;
                    document.getElementById('sudah-monev').innerText = data.sudah_monev ?? 0;
                    document.getElementById('target-monev-by-user').innerText = data.target_monev_by_user ?? 0;
                    document.getElementById('sudah-monev-by-user').innerText = data.sudah_monev_by_user ?? 0;
                    // document.getElementById('total_pelaksana').innerText = `Total ${data.total_pelaksana_sop ?? 0} pelaksana`;
                })
                .catch(error => console.error("Load data error:", error))
                .finally(() => {
                    loader.classList.add('d-none'); // hide loading
                });
        });

    </script>

    <script>
        $(document).ready(function(){
            fill_datatable_progres_pelaksana();
        });
        function fill_datatable_progres_pelaksana() {
            let fill_datatable_pelaksana = $('#progres_pelaksana_table').DataTable({
                paging: true,
                ordering: false, 
                pageLength: 2,
                lengthChange: false,
                processing: true,
                serverSide: true,
                stateSave: true,
                searching: true,
                scrollX: true, // <-- ini penting
               dom:"<'row mb-3'<'col-md-6 d-flex align-items-center'<'table-title fw-bolder fs-3 text-dark'>><'col-md-6 d-flex justify-content-end'f>>" +
                    "rt" +
                    "<'row mt-2'<'col-md-6'i><'col-md-6'p>>",
                "order": [
                    [0, 'asc']
                ],
                ajax: {
                    url: '{{route("dashboard.monev-sop.datatable-progres", ["unit_code" => $unit->code])}}',
                    dataSrc: 'data',
                    data : function(param){
                        param.segment = $('#segment').val();
                    }
                },
                columns: [{
                    sortable: false,
                    searchable: true,
                    data: 'nama_pelaksana',
                    name: 'nama_pelaksana',
                    "className": "align-middle text-nowrap",
                }, {
                    sortable: false,
                    searchable: true,
                    data: 'total_sop_monev',
                    name: 'total_sop_monev',
                    "className": "align-middle text-nowrap text-center",
                }, {
                    sortable: false,
                    searchable: false,
                    data: 'progres',
                    name: 'progres',
                    "className": "align-middle text-nowrap text-end",
                },],
            });

            // inject judul dan total pelaksana dari ajax
            fill_datatable_pelaksana.on('xhr.dt', function () {
                let json = fill_datatable_pelaksana.ajax.json();
                $('.table-title').html(`
                    Progres Pelaksana SOP<br>
                    <span class="text-muted fs-6">Total ${json.total_pelaksana} pelaksana</span>
                `);
            });
        }
    </script>

    <script>


        $(document).ready(function(){
            fill_datatable();

            $('#modalAddSOP').on('shown.bs.modal', function () {
                handleChangeSelect2();
            });

            // Tambah method cek ukuran file
            jQuery.validator.addMethod("filesize", function (value, element, param) {
                if (element.files.length === 0) {
                    return true; // biar tidak error kalau kosong (required sudah handle)
                }
                return element.files[0].size <= param;
            }, "Ukuran file terlalu besar.");


            $('#form_store_sop').validate({
                errorElement: "div",
                validElement: "div",
                errorClass: "text-danger mb-1 col-12",
                validClass: "text-body",
                ignore: [],
                rules: {
                    nama: {
                        required: true,
                    },
                    nomor: {
                        required: true,
                    },
                    unit_code: {
                        required: true,
                    },
                    file_sop: {
                        // required: true,
                        extension: "pdf",   // hanya pdf
                        filesize: 2097152,  // 2 MB (dalam byte)
                    }
                },
                messages: {
                    nama: {
                        required: 'Nama SOP harus diisi'
                    },
                    nomor: {
                        required: 'Nomor SOP harus diisi'
                    },
                    unit_code: {
                        required: 'Unit SOP harus dipilih'
                    },
                    file_sop: {
                        // required: 'File SOP harus diisi',
                        extension: 'File harus berformat PDF',
                        filesize: 'Ukuran file maksimal 2 MB'
                    }
                },
                errorPlacement: function(error, element) {
                    if (element.hasClass('has-input-group')) {
                        error.insertAfter(element.next('span'));
                    } else if (element.hasClass('has-upload-file')) {
                        error.insertAfter(element.next('div'));
                    }
                    else {
                        error.insertAfter(element);
                    }
                },
                submitHandler: function(form) {
                    form.submit()
                    Swal.fire({
                        title: 'Silakan tunggu, permintaan sedang diproses',
                        showConfirmButton: false,
                        timerProgressBar: true,
                        allowOutsideClick: false,
                        allowEscapeKey: false,
                        heightAuto: false,
                        didOpen: () => {
                            Swal.showLoading()
                        },
                    });
                }
            });
        });


        function fill_datatable() {
            let fill_datatable = $('#sop_table').DataTable({
                paging: true,
                processing: true,
                serverSide: true,
                stateSave: true,
                searching: true,
                scrollX: true, // <-- ini penting
                dom: "<'row'<'col-sm-7 col-md-5 d-flex align-items-center justify-content-center justify-content-md-start'l><'col-sm-5 col-md-7 d-flex align-items-center justify-content-center justify-content-md-end'f>>" +
                    "<'row'<'col-sm-12'tr>>" +
                    "<'row'<'col-sm-12 col-md-5 d-flex align-items-center justify-content-center justify-content-md-start'li><'col-sm-12 col-md-7 d-flex align-items-center justify-content-center justify-content-md-end'p<'bg-info'>>>",
                "order": [
                    [0, 'asc']
                ],
                ajax: {
                    url: '{{route("monev-sop.sop.datatable", ["unit_code" => $unit->code])}}',
                    dataSrc: 'data',
                    data : function(param){
                        param.segment = $('#segment').val();
                    }
                },
                columns: [{
                    data: null,
                    sortable: false,
                    searchable: false,
                    "className": "text-center align-middle",
                    render: function(data, type, row, meta) {
                        return meta.row + meta.settings._iDisplayStart + 1;
                    }
                },{
                    data: 'action',
                    "className": "text-center align-middle text-nowrap",
                    sortable: false,
                    orderable: false,
                }, {
                    sortable: false,
                    searchable: false,
                    data: 'monev',
                    name: 'monev',
                    "className": "align-middle text-nowrap",
                }, {
                    sortable: false,
                    searchable: false,
                    data: 'status_form_sop',
                    name: 'status_form_sop',
                    "className": "align-middle text-nowrap",
                }, {
                    sortable: true,
                    searchable: true,
                    data: 'nama_sop',
                    name: 'nama',
                    "className": "align-middle text-wrap",
                }, {
                    sortable: true,
                    searchable: true,
                    data: 'nomor_sop',
                    name: 'nomor',
                    "className": "align-middle text-nowrap",
                }, {
                    sortable: true,
                    searchable: true,
                    data: 'status_sop',
                    name: 'status_sop',
                    "className": "align-middle text-nowrap text-center",
                }, {
                    sortable: false,
                    searchable: false,
                    data: 'file_sop',
                    name: 'filepath',
                    "className": "align-middle text-nowrap",
                },],
                drawCallback: function(settings) {
                    // Re-initialize Metronic menus for dynamically added rows
                    if (typeof KTMenu !== 'undefined') {
                        KTMenu.createInstances();
                    }
                }
            }).on('draw.dt', function () {
                // Re-init Bootstrap 5 tooltip
                var tooltipTriggerList = [].slice.call(document.querySelectorAll('[data-bs-toggle="tooltip"]'))
                tooltipTriggerList.map(function (tooltipTriggerEl) {
                    return new bootstrap.Tooltip(tooltipTriggerEl)
                });
            });

            // ajax show detail
            $(document).ready(function () {
                $('#sop_table tbody').on('click', '.viewdetails', function () {
                    const this_id = fill_datatable.row($(this).closest('tr')).data().id;
                    if (this_id > 0) {
                        let url = "{{ route('monev-sop.sop.ajax_show', [':this_id', $unit->code]) }}";
                        url = url.replace(':this_id', this_id);
                        $('#tblempinfo tbody').empty();
                        $.ajax({
                            url: url,
                            dataType: 'json',
                            success: function (response) {
                                $('#tblempinfo tbody').html(response.html);
                                $('#modalAddSOP').modal('show');
                                handleChangeSelect2();
                            }
                        });
                    }
                });
            });
        }

        function deleteForm(param) {
            let id = $(param).data("string")
            Swal.fire({
                title: 'Peringatan',
                text: 'Apakah anda yakin akan akan menghapus data ini?',
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#f1416c',
                cancelButtonColor: '#b5b5c3',
                confirmButtonText: "Ya, hapus",
                cancelButtonText: 'Tidak, batalkan',
            }).then((result) => {
                if (result.isConfirmed) {
                    let action = "{{ route('monev-sop.sop.delete', ':id') }}".replace(':id', id);
                    $('#delete_form').attr('action',action);
                    $('#delete_form').submit();
                }
            })
        }

        function restoreForm(param) {
            let id = $(param).data("string")
            Swal.fire({
                title: 'Peringatan',
                text: 'Apakah anda yakin akan akan merestore data ini?',
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#198754',
                cancelButtonColor: '#b5b5c3',
                confirmButtonText: "Ya, restore",
                cancelButtonText: 'Tidak, batalkan',
            }).then((result) => {
                if (result.isConfirmed) {
                    let action = "{{ route('monev-sop.sop.restore', ':id') }}".replace(':id', id);
                    $('#restore_form').attr('action',action);
                    $('#restore_form').submit();
                }
            })
        }

        function showAddModal(){
            $('#modalAddSOP').modal('show');
            var url = "{{ route('monev-sop.sop.ajax_show', [0, $unit->code]) }}";

            $.get(url, function(data) {
                $('#tblempinfo tbody').html(data.html);
                handleChangeSelect2();
            });
        }

        function handleChangeSelect2() {
            $('#unit_code').select2({
                placeholder: 'Pilih Unit',
            });

            $('#unit_code').on('change', function () {
                $(this).valid();
            });
        }

    </script>

@endsection