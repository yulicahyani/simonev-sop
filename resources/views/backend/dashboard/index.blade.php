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

        .fc-event-success {
            /* background-color: rgba(80,205,137,0.1) !important; */
            border-left: 4px solid #50CD89 !important;
            /* color: #50CD89 !important; */
        }
        .fc-event-success .fc-daygrid-event-dot {
            background-color: #50CD89 !important;
            color: #50CD89 !important;
            border: 4px solid #50CD89 !important;
        }

        .fc-event-primary {
            /* background-color: rgba(0,158,247,0.1) !important; */
            border-left: 4px solid #009EF7 !important;
            /* color: #009EF7 !important; */
        }

        .fc-event-primary .fc-daygrid-event-dot{
            background-color: rgba(0,158,247,0.1) !important;
            border: 4px solid #009EF7 !important;
            color: #009EF7 !important;
        }
        
        .fc-event-warning {
            /* background-color: rgba(255,199,0,0.1) !important; */
            border-left: 4px solid #FFC700 !important;
            /* color: #FFC700 !important; */
        }
        .fc-event-warning .fc-daygrid-event-dot {
            background-color: rgba(255,199,0,0.1) !important;
            border: 4px solid #FFC700 !important;
            color: #FFC700 !important;
        }

        .fc-event-danger {
            /* background-color: rgba(241,65,108,0.1) !important; */
            border-left: 4px solid #F1416C !important;
            /* color: #F1416C !important; */
        }
        .fc-event-danger .fc-daygrid-event-dot {
            background-color: rgba(241,65,108,0.1) !important;
            border: 4px solid #F1416C !important;
            color: #F1416C !important;
        }

        .fc-event-info {
            /* background-color: rgba(161,165,183,0.1) !important; */
            border-left: 4px solid #A1A5B7 !important;
            /* color: #A1A5B7 !important; */
        }
        .fc-event-info .fc-daygrid-event-dot {
            background-color: rgba(161,165,183,0.1) !important;
            border: 4px solid #A1A5B7 !important;
            color: #A1A5B7 !important;
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
            <h1 class="d-flex text-white fw-bold my-1 fs-3">Dashboard</h1>
            <!--end::Title-->
            <!--begin::Breadcrumb-->
            <ul class="breadcrumb breadcrumb-separatorless fw-semibold fs-7 my-1">
                <!--begin::Item-->
                <li class="breadcrumb-item text-white opacity-75">
                    <a href="{{route('backend.beranda')}}" class="text-white text-hover-bg-light">Beranda</a>
                </li>
                <!--end::Item-->
                <!--begin::Item-->
                <li class="breadcrumb-item">
                    <span class="bullet bg-white opacity-75 w-5px h-2px"></span>
                </li>
                <!--end::Item-->
                <!--begin::Item-->
                <li class="breadcrumb-item text-white opacity-75">Dashboard</li>
                <!--end::Item-->
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
        <div class="card mb-5">
            <div class="card-body py-4">

                <div class="row">
                    <div class="col-md-5 mb-5 mb-xl-0 mb-lg-0 mb-md-0">
                        <label class="form-label fw-bold">Unit/PD</label>
                        <select id="filter_unit" class="form-select" data-control="select2">
                            <option value="all" {{$defaultUnit == 'all' ? 'selected' : ''}}>Semua Unit/PD</option> <!-- Selalu muncul -->

                            @foreach($unitList as $u)
                                <option value="{{ $u->code }}"
                                    {{ $defaultUnit == $u->code ? 'selected' : '' }}>
                                    {{ $u->name }}
                                </option>
                            @endforeach
                        </select>
                    </div>

                    <div class="col-md-4">
                        <label class="form-label fw-bold">Periode</label>
                        <select id="filter_periode" class="form-select" data-control="select2">
                            @foreach($periodeList as $p)
                                <option value="{{ $p->id }}" 
                                    {{ $periodeAktif == $p->id ? 'selected' : '' }}>
                                    Periode Monev {{ $p->periode_year }}
                                </option>
                            @endforeach
                        </select>
                    </div>

                    <div class="col-md-3 d-flex align-items-end">
                        <button id="btnFilter" class="btn btn-primary w-100">
                            <i class="fas fa-filter"></i> Filter
                        </button>
                    </div>
                </div>

            </div>
        </div>

        <!--begin::Row-->
        <div class="row g-5 g-xl-5">
            <!--begin::Col-->
            <div class="col-xl-4 mb-xl-10">
                <!--begin::Lists Widget 19-->
                <div class="card card-flush mb-5 h-xl-50">
                    <!-- Loader -->
                    <div class="loading-overlay d-none" id="loading-sop">
                        <div class="spinner-border text-primary" role="status"></div>
                    </div>
                    <!--begin::Heading-->
                    <div class="card-header rounded bgi-no-repeat bgi-size-cover bgi-position-y-top bgi-position-x-center align-items-start h-250px"  data-theme="light">
                        <!--begin::Title-->
                        <h3 class="card-title align-items-start flex-column pt-5">
                            <span id="unit_pd_nama" class="fw-bold fs-2 mb-3">Pemerintah Kabupaten Badung </span>
                            <div class="fs-4">
                                <span class="opacity-75">Total </span>
                                <span class="position-relative d-inline-block">
                                    <a href="#sop_table" class="opacity-75-hover fw-bold d-block mb-1"><span id="belum-monev">0</span> SOP</a>
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
                            </div>
                            <!--end::Row-->
                        </div>
                        <!--end::Stats-->
                    </div>
                    <!--end::Body-->
                </div>
                <!--end::Lists Widget 19-->

                <!--begin::Mixed Widget 10-->
                <div class="card card-xl-stretch-50 mb-xl-8">
                    <!--begin::Body-->
                    <div class="card-body p-0 d-flex justify-content-between flex-column overflow-hidden">
                        <!--begin::Hidden-->
                        <div class="d-flex flex-stack flex-wrap flex-grow-1 px-9 pt-0 pb-3">
                            <div class="me-2">
                                <span class="fw-bold text-gray-800 d-block fs-3">Hasil Monev</span>
                                <span id="periode_bar_label" class="text-gray-400 fw-bold">Periode :</span>
                            </div>
                            <div id="total_sop_bar_title" class="fw-bold fs-3 text-primary">0 SOP</div>
                        </div>
                        <!--end::Hidden-->
                        <!--begin::Chart-->
                        <div id="monev-bar-chart-tahunan"  class="monev-bar-chart-tahunan"></div>
                        {{-- <div class="mixed-widget-10-chart" data-kt-color="primary" style="height: 175px"></div> --}}
                        <!--end::Chart-->
                    </div>
                </div>
                <!--end::Mixed Widget 10-->
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
                        <div class="card card-flush h-md-100 mb-xl-10 pb-5">
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
                                        <span class="badge badge-light-success fs-base">SOP</span>
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
                    <div class="col-xl-4 mb-xl-5">
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
                                        <span id="total-sop-summary-donut" class="fs-2hx fw-bold text-dark me-2 lh-1 ls-n2">0</span>
                                        <!--end::Amount-->
                                        <!--begin::Badge-->
                                        <span class="badge badge-light-success fs-base">SOP</span>
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
                                    <div id="monevProgresChart" style="min-width: 70px; min-height: 70px"></div>
                                </div>
                                <!--end::Chart-->
                                <!--begin::Labels-->
                                <div class="d-flex flex-column content-justify-center w-100">
                                    <!--begin::Label-->
                                    <div class="d-flex fs-6 fw-semibold align-items-center">
                                        <!--begin::Bullet-->
                                        <div class="bullet w-8px h-6px rounded-2 bg-warning me-3"></div>
                                        <!--end::Bullet-->
                                        <!--begin::Label-->
                                        <div class="text-gray-500 flex-grow-1 me-4">Total SOP</div>
                                        <!--end::Label-->
                                        <!--begin::Stats-->
                                        <div class="fw-bolder text-gray-700 text-xxl-end" id="total-sop-donut">0</div>
                                        <!--end::Stats-->
                                    </div>
                                    <!--end::Label-->
                                    <!--begin::Label-->
                                    <div class="d-flex fs-6 fw-semibold align-items-center my-3">
                                        <!--begin::Bullet-->
                                        <div class="bullet w-8px h-6px rounded-2 bg-primary me-3"></div>
                                        <!--end::Bullet-->
                                        <!--begin::Label-->
                                        <div class="text-gray-500 flex-grow-1 me-4">Sudah Monev</div>
                                        <!--end::Label-->
                                        <!--begin::Stats-->
                                        <div class="fw-bolder text-gray-700 text-xxl-end" id="sudah-monev-sop-donut">0</div>
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
                                        <div class="fw-bolder text-gray-700 text-xxl-end" id="belum-monev-sop-donut">0</div>
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
                </div>
                <!--end::Row-->
                <!--begin::Tables Widget 9-->
                <div class="card">
                    <!--begin::Body-->
                    <div class="card-body py-5">
                        <!--begin::Table container-->
                        <div class="table-responsive">
                            <!--begin::Table-->
                            <table class="table table-row-dashed table-row-gray-300 align-middle gs-0 gy-5" id="progres_pelaksana_table">
                                <!--begin::Table head-->
                                <thead>
                                    <tr class="fw-bold text-muted">
                                        <th class="min-w-200px">Nama Pelaksana</th>
                                        <th class="min-w-200px">Unit/PD</th>
                                        <th class="min-w-150px">Total SOP Monev</th>
                                        <th class="min-w-150px">Progress</th>
                                    </tr>
                                </thead>
                                <!--end::Table head-->
                                <!--begin::Table body-->
                                <tbody>
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

        <!--begin::Dahsboard Konsultasi Online-->
        <!--begin::Row-->
        <div class="row g-5 g-xl-5">
            <!-- Loader -->
            <div class="loading-overlay d-none" id="loader-konsultasi-summary">
                <div class="spinner-border text-primary" role="status"></div>
            </div>
            <!--begin::Col-->
            <div class="col-xl-3 mb-xl-5">
                <!--begin::Tiles Widget 5-->
                <div class="card card-xxl-stretch">
                    <!--begin::Body-->
                    <div class="card-body d-flex flex-column justify-content-between">
                        <!--begin::Svg Icon | path: icons/duotune/general/gen025.svg-->
                        <span class="svg-icon svg-icon-info svg-icon-2hx ms-n1 flex-grow-1">
                            <svg width="24" height="24" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
                                <rect x="2" y="2" width="9" height="9" rx="2" fill="currentColor" />
                                <rect opacity="0.3" x="13" y="2" width="9" height="9" rx="2" fill="currentColor" />
                                <rect opacity="0.3" x="13" y="13" width="9" height="9" rx="2" fill="currentColor" />
                                <rect opacity="0.3" x="2" y="13" width="9" height="9" rx="2" fill="currentColor" />
                            </svg>
                        </span>
                        <!--end::Svg Icon-->
                        <div class="d-flex flex-column">
                            <div id="total-konsultasi" class="fw-bold fs-1 mb-0 mt-5">0</div>
                            <div class="fw-semibold fs-6">Total Konsultasi SOP (Online)</div>
                        </div>
                    </div>
                    <!--end::Body-->
                </div>
                <!--end::Tiles Widget 5-->
            </div>
            <!--end::Col-->
            <!--begin::Col-->
            <div class="col-xl-3 mb-xl-5">
                <!--begin::Tiles Widget 5-->
                <div class="card card-xxl-stretch bg-body">
                    <!--begin::Body-->
                    <div class="card-body d-flex flex-column justify-content-between">
                        <!--begin::Svg Icon | path: icons/duotune/general/gen002.svg-->
                        <span class="svg-icon svg-icon-primary svg-icon-2hx ms-n1 flex-grow-1">
                            <svg width="24" height="24" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
                                <path opacity="0.3" d="M4.05424 15.1982C8.34524 7.76818 13.5782 3.26318 20.9282 2.01418C21.0729 1.98837 21.2216 1.99789 21.3618 2.04193C21.502 2.08597 21.6294 2.16323 21.7333 2.26712C21.8372 2.37101 21.9144 2.49846 21.9585 2.63863C22.0025 2.7788 22.012 2.92754 21.9862 3.07218C20.7372 10.4222 16.2322 15.6552 8.80224 19.9462L4.05424 15.1982ZM3.81924 17.3372L2.63324 20.4482C2.58427 20.5765 2.5735 20.7163 2.6022 20.8507C2.63091 20.9851 2.69788 21.1082 2.79503 21.2054C2.89218 21.3025 3.01536 21.3695 3.14972 21.3982C3.28408 21.4269 3.42387 21.4161 3.55224 21.3672L6.66524 20.1802L3.81924 17.3372ZM16.5002 5.99818C16.2036 5.99818 15.9136 6.08615 15.6669 6.25097C15.4202 6.41579 15.228 6.65006 15.1144 6.92415C15.0009 7.19824 14.9712 7.49984 15.0291 7.79081C15.0869 8.08178 15.2298 8.34906 15.4396 8.55884C15.6494 8.76862 15.9166 8.91148 16.2076 8.96935C16.4986 9.02723 16.8002 8.99753 17.0743 8.884C17.3484 8.77046 17.5826 8.5782 17.7474 8.33153C17.9123 8.08486 18.0002 7.79485 18.0002 7.49818C18.0002 7.10035 17.8422 6.71882 17.5609 6.43752C17.2796 6.15621 16.8981 5.99818 16.5002 5.99818Z" fill="currentColor" />
                                <path d="M4.05423 15.1982L2.24723 13.3912C2.15505 13.299 2.08547 13.1867 2.04395 13.0632C2.00243 12.9396 1.9901 12.8081 2.00793 12.679C2.02575 12.5498 2.07325 12.4266 2.14669 12.3189C2.22013 12.2112 2.31752 12.1219 2.43123 12.0582L9.15323 8.28918C7.17353 10.3717 5.4607 12.6926 4.05423 15.1982ZM8.80023 19.9442L10.6072 21.7512C10.6994 21.8434 10.8117 21.9129 10.9352 21.9545C11.0588 21.996 11.1903 22.0083 11.3195 21.9905C11.4486 21.9727 11.5718 21.9252 11.6795 21.8517C11.7872 21.7783 11.8765 21.6809 11.9402 21.5672L15.7092 14.8442C13.6269 16.8245 11.3061 18.5377 8.80023 19.9442ZM7.04023 18.1832L12.5832 12.6402C12.7381 12.4759 12.8228 12.2577 12.8195 12.032C12.8161 11.8063 12.725 11.5907 12.5653 11.4311C12.4057 11.2714 12.1901 11.1803 11.9644 11.1769C11.7387 11.1736 11.5205 11.2583 11.3562 11.4132L5.81323 16.9562L7.04023 18.1832Z" fill="currentColor" />
                            </svg>
                        </span>
                        <!--end::Svg Icon-->
                        <div class="d-flex flex-column">
                            <div id="selesai-konsultasi" class="text-dark fw-bold fs-1 mb-0 mt-5">0</div>
                            <div class="fw-semibold fs-6">Selesai</div>
                        </div>
                    </div>
                    <!--end::Body-->
                </div>
                <!--end::Tiles Widget 5-->
            </div>
            <!--end::Col-->
            
            <!--begin::Col-->
            <div class="col-xl-3 mb-xl-5">
                <!--begin::Tiles Widget 5-->
                <div class="card card-xxl-stretch bg-body">
                    <!--begin::Body-->
                    <div class="card-body d-flex flex-column justify-content-between">
                        <!--begin::Svg Icon | path: icons/duotune/general/gen002.svg-->
                        <span class="svg-icon svg-icon-warning svg-icon-2hx ms-n1 flex-grow-1">
                            <svg width="24" height="24" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
                                <path opacity="0.3" d="M20.9 12.9C20.3 12.9 19.9 12.5 19.9 11.9C19.9 11.3 20.3 10.9 20.9 10.9H21.8C21.3 6.2 17.6 2.4 12.9 2V2.9C12.9 3.5 12.5 3.9 11.9 3.9C11.3 3.9 10.9 3.5 10.9 2.9V2C6.19999 2.5 2.4 6.2 2 10.9H2.89999C3.49999 10.9 3.89999 11.3 3.89999 11.9C3.89999 12.5 3.49999 12.9 2.89999 12.9H2C2.5 17.6 6.19999 21.4 10.9 21.8V20.9C10.9 20.3 11.3 19.9 11.9 19.9C12.5 19.9 12.9 20.3 12.9 20.9V21.8C17.6 21.3 21.4 17.6 21.8 12.9H20.9Z" fill="currentColor" />
                                <path d="M16.9 10.9H13.6C13.4 10.6 13.2 10.4 12.9 10.2V5.90002C12.9 5.30002 12.5 4.90002 11.9 4.90002C11.3 4.90002 10.9 5.30002 10.9 5.90002V10.2C10.6 10.4 10.4 10.6 10.2 10.9H9.89999C9.29999 10.9 8.89999 11.3 8.89999 11.9C8.89999 12.5 9.29999 12.9 9.89999 12.9H10.2C10.4 13.2 10.6 13.4 10.9 13.6V13.9C10.9 14.5 11.3 14.9 11.9 14.9C12.5 14.9 12.9 14.5 12.9 13.9V13.6C13.2 13.4 13.4 13.2 13.6 12.9H16.9C17.5 12.9 17.9 12.5 17.9 11.9C17.9 11.3 17.5 10.9 16.9 10.9Z" fill="currentColor" />
                            </svg>
                        </span>
                        <!--end::Svg Icon-->
                        <div class="d-flex flex-column">
                            <div id="proses-revisi-konsultasi" class="text-dark fw-bold fs-1 mb-0 mt-5">0</div>
                            <div class="fw-semibold fs-6">Proses Revisi</div>
                        </div>
                    </div>
                    <!--end::Body-->
                </div>
                <!--end::Tiles Widget 5-->
            </div>
            <!--end::Col-->

            <!--begin::Col-->
            <div class="col-xl-3 mb-xl-5">
                <!--begin::Card widget 4-->
                <div class="card card-flush mb-5 mb-xl-5 pb-5">
                    <!--begin::Card body-->
                    <div class="card-body pt-4 pb-2 d-flex align-items-center">
                        <!--begin::Chart-->
                        <div class="d-flex flex-center me-5 pt-4">
                            <div id="konsultasi-chart" style="min-width: 100px; min-height: 100px"></div>
                        </div>
                        <!--end::Chart-->
                        <!--begin::Labels-->
                        <div class="d-flex flex-column content-justify-center w-100">
                            <!--begin::Label-->
                            <div class="d-flex fs-6 fw-semibold align-items-center">
                                <!--begin::Bullet-->
                                <div class="bullet w-8px h-6px rounded-2 me-3 bg-info"></div>
                                <!--end::Bullet-->
                                <!--begin::Label-->
                                <div class="text-gray-500 flex-grow-1 me-4">Total</div>
                                <!--end::Label-->
                                <!--begin::Stats-->
                                <div class="fw-bolder text-gray-700 text-xxl-end" id="total-konsultasi-donut">0</div>
                                <!--end::Stats-->
                            </div>
                            <!--end::Label-->
                            <!--begin::Label-->
                            <div class="d-flex fs-6 fw-semibold align-items-center my-3">
                                <!--begin::Bullet-->
                                <div class="bullet w-8px h-6px rounded-2 bg-primary me-3"></div>
                                <!--end::Bullet-->
                                <!--begin::Label-->
                                <div class="text-gray-500 flex-grow-1 me-4">Selesai</div>
                                <!--end::Label-->
                                <!--begin::Stats-->
                                <div class="fw-bolder text-gray-700 text-xxl-end" id="selesai-konsultasi-donut">0</div>
                                <!--end::Stats-->
                            </div>
                            <!--end::Label-->
                            <!--begin::Label-->
                            <div class="d-flex fs-6 fw-semibold align-items-center">
                                <!--begin::Bullet-->
                                <div class="bullet w-8px h-6px rounded-2 me-3 bg-warning"></div>
                                <!--end::Bullet-->
                                <!--begin::Label-->
                                <div class="text-gray-500 flex-grow-1 me-4">Proses Revisi</div>
                                <!--end::Label-->
                                <!--begin::Stats-->
                                <div class="fw-bolder text-gray-700 text-xxl-end" id="proses-revisi-konsultasi-donut">0</div>
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
        </div>
        <!--end::Row-->
        <!--end::Dahsboard Konsultasi Online-->

        <!--begin::Dahsboard Konsultasi Offline-->
        <!--begin::Row-->
        <div class="row gy-5 g-xl-8">
            <!-- Loader -->
            <div class="loading-overlay d-none" id="loader-jadwal-konsultasi-summary">
                <div class="spinner-border text-primary" role="status"></div>
            </div>
            <!--begin::Col-->
            <div class="col-xl-4">
                <!--begin::Mixed Widget 2-->
                <div class="card card-xl-stretch">
                    <!--begin::Header-->
                    <div class="card-header rounded bgi-no-repeat bgi-size-cover bgi-position-y-top bgi-position-x-center align-items-start h-250px" style="background-image:url('{{ asset('metronic/assets/media/svg/shapes/top-green.png') }}')" data-theme="light">
                        <!--begin::Title-->
                        <h3 class="card-title align-items-start flex-column text-white pt-15">
                            <span class="fw-bold fs-2x mb-3">Jadwal Konsultasi (Offline)</span>
                        </h3>
                        <!--end::Title-->
                    </div>
                    <!--end::Header-->
                    <!--begin::Body-->
                    <div class="card-body p-0">
                        {{-- <!--begin::Chart-->
                        <div class="card-rounded-bottom bg-danger" data-kt-color="danger" style="height: 200px"></div>
                        <!--end::Chart--> --}}
                        <!--begin::Stats-->
                        <div class="card-p mt-n20 position-relative">
                            <!--begin::Row-->
                            <div class="row g-0">
                                <!--begin::Col-->
                                <div class="col bg-light-warning px-6 py-8 rounded-2 me-7 mb-7">
                                    <!--begin::Svg Icon | path: icons/duotune/general/gen032.svg-->
                                    <span class="svg-icon svg-icon-3x svg-icon-warning d-block my-2">
                                        <i class="bi bi-clock fs-1 me-1 text-warning"></i>
                                    </span>
                                    <!--end::Svg Icon-->
                                    <div class="d-flex flex-column">
                                        <div id="jadwal-diajukan" class="text-dark fw-bold fs-1">0</div>
                                        <div class="text-warning fw-semibold fs-6">Diajukan</div>
                                    </div>
                                </div>
                                <!--end::Col-->
                                <!--begin::Col-->
                                <div class="col bg-light-primary px-6 py-8 rounded-2 mb-7">
                                    <!--begin::Svg Icon | path: icons/duotune/finance/fin006.svg-->
                                    <span class="svg-icon svg-icon-3x svg-icon-primary d-block my-2">
                                        <i class="bi bi-calendar-check fs-1 me-1 text-primary"></i>
                                    </span>
                                    <!--end::Svg Icon-->
                                    <div class="d-flex flex-column">
                                        <div id="jadwal-dijadwalkan" class="text-dark fw-bold fs-1">0</div>
                                        <div class="text-primary fw-semibold fs-6">Dijadwalkan</div>
                                    </div>
                                </div>
                                <!--end::Col-->
                            </div>
                            <!--end::Row-->
                            <!--begin::Row-->
                            <div class="row g-0">
                                <!--begin::Col-->
                                <div class="col bg-light-danger px-6 py-8 rounded-2 me-7">
                                    <!--begin::Svg Icon | path: icons/duotune/abstract/abs027.svg-->
                                    <span class="svg-icon svg-icon-3x svg-icon-danger d-block my-2">
                                        <i class="bi bi-x-square fs-1 me-1 text-danger"></i>
                                    </span>
                                    <!--end::Svg Icon-->
                                    <div class="d-flex flex-column">
                                        <div id="jadwal-dibatalkan" class="text-dark fw-bold fs-1">0</div>
                                        <div class="text-danger fw-semibold fs-6">Dibatalkan</div>
                                    </div>
                                </div>
                                <!--end::Col-->
                                <!--begin::Col-->
                                <div class="col bg-light-success px-6 py-8 rounded-2">
                                    <!--begin::Svg Icon | path: icons/duotune/communication/com010.svg-->
                                    <span class="svg-icon svg-icon-3x svg-icon-success d-block my-2">
                                        <i class="bi bi-check-all fs-1 me-1 text-success"></i>
                                    </span>
                                    <!--end::Svg Icon-->
                                    <div class="d-flex flex-column">
                                        <div id="jadwal-selesai" class="text-dark fw-bold fs-1">0</div>
                                        <div class="text-success fw-semibold fs-6">Selesai</div>
                                    </div>
                                </div>
                                <!--end::Col-->
                            </div>
                            <!--end::Row-->

                            <!--begin::Row-->
                            <div class="row g-0">
                                <!--begin::Col-->
                                <div class="col bg-light-info px-6 py-8 rounded-2 mt-7 text-center">
                                    <!--begin::Svg Icon | path: icons/duotune/abstract/abs027.svg-->
                                    <span class="svg-icon svg-icon-2x svg-icon-info d-block my-2">
                                        <svg width="24" height="24" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
                                            <rect x="2" y="2" width="9" height="9" rx="2" fill="currentColor" />
                                            <rect opacity="0.3" x="13" y="2" width="9" height="9" rx="2" fill="currentColor" />
                                            <rect opacity="0.3" x="13" y="13" width="9" height="9" rx="2" fill="currentColor" />
                                            <rect opacity="0.3" x="2" y="13" width="9" height="9" rx="2" fill="currentColor" />
                                        </svg>
                                    </span>
                                    <!--end::Svg Icon-->
                                    <div class="d-flex flex-column">
                                        <div id="jadwal-total" class="text-dark fw-bold fs-1">0</div>
                                        <div class="text-info fw-semibold fs-6">Total Jadwal Konsultasi</div>
                                    </div>
                                </div>
                                <!--end::Col-->
                            </div>
                            <!--end::Row-->
                        </div>
                        <!--end::Stats-->
                    </div>
                    <!--end::Body-->
                </div>
                <!--end::Mixed Widget 2-->
            </div>
            <!--end::Col-->
            <!--begin::Col-->
            <div class="col-xl-8">
                <!--begin::Card-->
                <div class="card card-xl-stretch mb-5">
                    <!--begin::Card body-->
                    <div class="card-body">
                        <!--begin::Calendar-->
                        <div id="kt_calendar_app"></div>
                        <!--end::Calendar-->
                    </div>
                    <!--end::Card body-->
                </div>
                <!--end::Card-->
            </div>
            <!--end::Col-->
        </div>
        <!--end::Row-->
        <!--end::Dahsboard Konsultasi Offline-->

        {{-- <!--begin::Card-->
        <div class="card">
            <!--begin::Card body-->
            <div class="card-body p-0">
                <!--begin::Wrapper-->
                <div class="card-px text-center pt-10">
                    <!--begin::Title-->
                    <h2 class="fs-2x fw-bold mb-10">Selamat datang di SIMONEV-SOP</h2>
                    <!--end::Title-->
                    <!--begin::Description-->
                    <p class="text-gray-400 fs-4 fw-semibold mb-2 px-5">SIMONEV-SOP adalah platform digital resmi Bagian 
                        Organisasi Pemerintah Kabupaten Badung yang dirancang untuk memfasilitasi pelaksanaan konsultasi, 
                        monitoring, dan evaluasi Standar Operasional Prosedur (SOP) di seluruh perangkat daerah.
                    <br /><br>SISTEM MASIH DALAM TAHAP PENGEMBANGAN</p>
                    <!--end::Description-->
                </div>
                <!--end::Wrapper-->
                <!--begin::Illustration-->
                <div class="text-center px-4 pb-10">
                    <img class="mw-100 mh-300px" alt="" src="{{asset('images/illustrations/hero-services-img.webp')}}" />
                </div>
                <!--end::Illustration-->
            </div>
            <!--end::Card body-->
        </div>
        <!--end::Card--> --}}
    </div>
    <!--end::Post-->
</div>
<!--end::Container-->

<!--begin::Modal Detail-->
<div class="modal fade" id="kt_modal_view_event" tabindex="-1" aria-hidden="true">
    <!--begin::Modal dialog-->
    <div class="modal-dialog modal-dialog-centered mw-650px">
        <!--begin::Modal content-->
        <div class="modal-content">
            <!--begin::Modal header-->
            <div class="modal-header border-0 justify-content-end">
                {{-- <!--begin::Edit-->
                <div class="btn btn-icon btn-sm btn-color-gray-400 btn-active-icon-primary me-2" data-bs-toggle="tooltip" data-bs-dismiss="click" title="Edit Event" id="kt_modal_view_event_edit">
                    <!--begin::Svg Icon | path: icons/duotune/art/art005.svg-->
                    <span class="svg-icon svg-icon-2">
                        <svg width="24" height="24" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
                            <path opacity="0.3" d="M21.4 8.35303L19.241 10.511L13.485 4.755L15.643 2.59595C16.0248 2.21423 16.5426 1.99988 17.0825 1.99988C17.6224 1.99988 18.1402 2.21423 18.522 2.59595L21.4 5.474C21.7817 5.85581 21.9962 6.37355 21.9962 6.91345C21.9962 7.45335 21.7817 7.97122 21.4 8.35303ZM3.68699 21.932L9.88699 19.865L4.13099 14.109L2.06399 20.309C1.98815 20.5354 1.97703 20.7787 2.03189 21.0111C2.08674 21.2436 2.2054 21.4561 2.37449 21.6248C2.54359 21.7934 2.75641 21.9115 2.989 21.9658C3.22158 22.0201 3.4647 22.0084 3.69099 21.932H3.68699Z" fill="currentColor" />
                            <path d="M5.574 21.3L3.692 21.928C3.46591 22.0032 3.22334 22.0141 2.99144 21.9594C2.75954 21.9046 2.54744 21.7864 2.3789 21.6179C2.21036 21.4495 2.09202 21.2375 2.03711 21.0056C1.9822 20.7737 1.99289 20.5312 2.06799 20.3051L2.696 18.422L5.574 21.3ZM4.13499 14.105L9.891 19.861L19.245 10.507L13.489 4.75098L4.13499 14.105Z" fill="currentColor" />
                        </svg>
                    </span>
                    <!--end::Svg Icon-->
                </div>
                <!--end::Edit-->
                <!--begin::Edit-->
                <div class="btn btn-icon btn-sm btn-color-gray-400 btn-active-icon-danger me-2" data-bs-toggle="tooltip" data-bs-dismiss="click" title="Delete Event" id="kt_modal_view_event_delete">
                    <!--begin::Svg Icon | path: icons/duotune/general/gen027.svg-->
                    <span class="svg-icon svg-icon-2">
                        <svg width="24" height="24" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
                            <path d="M5 9C5 8.44772 5.44772 8 6 8H18C18.5523 8 19 8.44772 19 9V18C19 19.6569 17.6569 21 16 21H8C6.34315 21 5 19.6569 5 18V9Z" fill="currentColor" />
                            <path opacity="0.5" d="M5 5C5 4.44772 5.44772 4 6 4H18C18.5523 4 19 4.44772 19 5V5C19 5.55228 18.5523 6 18 6H6C5.44772 6 5 5.55228 5 5V5Z" fill="currentColor" />
                            <path opacity="0.5" d="M9 4C9 3.44772 9.44772 3 10 3H14C14.5523 3 15 3.44772 15 4V4H9V4Z" fill="currentColor" />
                        </svg>
                    </span>
                    <!--end::Svg Icon-->
                </div>
                <!--end::Edit--> --}}
                <!--begin::Close-->
                <div class="btn btn-icon btn-sm btn-color-gray-500 btn-active-icon-primary" data-bs-toggle="tooltip" title="Hide Event" data-bs-dismiss="modal">
                    <!--begin::Svg Icon | path: icons/duotune/arrows/arr061.svg-->
                    <span class="svg-icon svg-icon-1">
                        <svg width="24" height="24" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
                            <rect opacity="0.5" x="6" y="17.3137" width="16" height="2" rx="1" transform="rotate(-45 6 17.3137)" fill="currentColor" />
                            <rect x="7.41422" y="6" width="16" height="2" rx="1" transform="rotate(45 7.41422 6)" fill="currentColor" />
                        </svg>
                    </span>
                    <!--end::Svg Icon-->
                </div>
                <!--end::Close-->
            </div>
            <!--end::Modal header-->
            <!--begin::Modal body-->
            <div class="modal-body pt-0 pb-20 px-lg-17">
                <!--begin::Row-->
                <div class="d-flex">
                    <!--begin::Icon-->
                    <!--begin::Svg Icon | path: icons/duotune/general/gen014.svg-->
                    <span class="svg-icon svg-icon-1 svg-icon-muted me-5 text-primary">
                        <svg width="24" height="24" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
                            <path opacity="0.3" d="M21 22H3C2.4 22 2 21.6 2 21V5C2 4.4 2.4 4 3 4H21C21.6 4 22 4.4 22 5V21C22 21.6 21.6 22 21 22Z" fill="currentColor" />
                            <path d="M6 6C5.4 6 5 5.6 5 5V3C5 2.4 5.4 2 6 2C6.6 2 7 2.4 7 3V5C7 5.6 6.6 6 6 6ZM11 5V3C11 2.4 10.6 2 10 2C9.4 2 9 2.4 9 3V5C9 5.6 9.4 6 10 6C10.6 6 11 5.6 11 5ZM15 5V3C15 2.4 14.6 2 14 2C13.4 2 13 2.4 13 3V5C13 5.6 13.4 6 14 6C14.6 6 15 5.6 15 5ZM19 5V3C19 2.4 18.6 2 18 2C17.4 2 17 2.4 17 3V5C17 5.6 17.4 6 18 6C18.6 6 19 5.6 19 5Z" fill="currentColor" />
                            <path d="M8.8 13.1C9.2 13.1 9.5 13 9.7 12.8C9.9 12.6 10.1 12.3 10.1 11.9C10.1 11.6 10 11.3 9.8 11.1C9.6 10.9 9.3 10.8 9 10.8C8.8 10.8 8.59999 10.8 8.39999 10.9C8.19999 11 8.1 11.1 8 11.2C7.9 11.3 7.8 11.4 7.7 11.6C7.6 11.8 7.5 11.9 7.5 12.1C7.5 12.2 7.4 12.2 7.3 12.3C7.2 12.4 7.09999 12.4 6.89999 12.4C6.69999 12.4 6.6 12.3 6.5 12.2C6.4 12.1 6.3 11.9 6.3 11.7C6.3 11.5 6.4 11.3 6.5 11.1C6.6 10.9 6.8 10.7 7 10.5C7.2 10.3 7.49999 10.1 7.89999 10C8.29999 9.90003 8.60001 9.80003 9.10001 9.80003C9.50001 9.80003 9.80001 9.90003 10.1 10C10.4 10.1 10.7 10.3 10.9 10.4C11.1 10.5 11.3 10.8 11.4 11.1C11.5 11.4 11.6 11.6 11.6 11.9C11.6 12.3 11.5 12.6 11.3 12.9C11.1 13.2 10.9 13.5 10.6 13.7C10.9 13.9 11.2 14.1 11.4 14.3C11.6 14.5 11.8 14.7 11.9 15C12 15.3 12.1 15.5 12.1 15.8C12.1 16.2 12 16.5 11.9 16.8C11.8 17.1 11.5 17.4 11.3 17.7C11.1 18 10.7 18.2 10.3 18.3C9.9 18.4 9.5 18.5 9 18.5C8.5 18.5 8.1 18.4 7.7 18.2C7.3 18 7 17.8 6.8 17.6C6.6 17.4 6.4 17.1 6.3 16.8C6.2 16.5 6.10001 16.3 6.10001 16.1C6.10001 15.9 6.2 15.7 6.3 15.6C6.4 15.5 6.6 15.4 6.8 15.4C6.9 15.4 7.00001 15.4 7.10001 15.5C7.20001 15.6 7.3 15.6 7.3 15.7C7.5 16.2 7.7 16.6 8 16.9C8.3 17.2 8.6 17.3 9 17.3C9.2 17.3 9.5 17.2 9.7 17.1C9.9 17 10.1 16.8 10.3 16.6C10.5 16.4 10.5 16.1 10.5 15.8C10.5 15.3 10.4 15 10.1 14.7C9.80001 14.4 9.50001 14.3 9.10001 14.3C9.00001 14.3 8.9 14.3 8.7 14.3C8.5 14.3 8.39999 14.3 8.39999 14.3C8.19999 14.3 7.99999 14.2 7.89999 14.1C7.79999 14 7.7 13.8 7.7 13.7C7.7 13.5 7.79999 13.4 7.89999 13.2C7.99999 13 8.2 13 8.5 13H8.8V13.1ZM15.3 17.5V12.2C14.3 13 13.6 13.3 13.3 13.3C13.1 13.3 13 13.2 12.9 13.1C12.8 13 12.7 12.8 12.7 12.6C12.7 12.4 12.8 12.3 12.9 12.2C13 12.1 13.2 12 13.6 11.8C14.1 11.6 14.5 11.3 14.7 11.1C14.9 10.9 15.2 10.6 15.5 10.3C15.8 10 15.9 9.80003 15.9 9.70003C15.9 9.60003 16.1 9.60004 16.3 9.60004C16.5 9.60004 16.7 9.70003 16.8 9.80003C16.9 9.90003 17 10.2 17 10.5V17.2C17 18 16.7 18.4 16.2 18.4C16 18.4 15.8 18.3 15.6 18.2C15.4 18.1 15.3 17.8 15.3 17.5Z" fill="currentColor" />
                        </svg>
                    </span>
                    <!--end::Svg Icon-->
                    <!--end::Icon-->
                    <div class="mb-9">
                        <!--begin::Event name-->
                        <div class="d-flex align-items-center mb-2">
                            <span class="fs-3 fw-bold me-3" data-kt-calendar="event_name"></span>
                            {{-- <span class="badge badge-light-success" data-kt-calendar="event_status"></span> --}}
                            <div data-kt-calendar="event_status"></div>
                        </div>
                        <!--end::Event name-->
                        <!--begin::Event description-->
                        <div class="fs-6" data-kt-calendar="event_description"></div>
                        <!--end::Event description-->

                        <!--begin::Event description-->
                        <div class="fs-6" data-kt-calendar="event_unit"></div>
                        <!--end::Event description-->
                    </div>
                </div>
                <!--end::Row-->
                <!--begin::Row-->
                <div class="d-flex align-items-center mb-2">
                    <!--begin::Icon-->
                    <!--begin::Svg Icon | path: icons/duotune/abstract/abs050.svg-->
                    <span class="svg-icon svg-icon-1 svg-icon-success me-5">
                        {{-- <svg xmlns="http://www.w3.org/2000/svg" width="24px" height="24px" viewBox="0 0 24 24" version="1.1">
                            <circle fill="currentColor" cx="12" cy="12" r="8" />
                        </svg> --}}
                        <i class="bi bi-calendar-day fs-3 text-primary"></i>
                    </span>
                    <!--end::Svg Icon-->
                    <!--end::Icon-->
                    <!--begin::Event start date/time-->
                    <div class="fs-6">
                        <span class="fw-bold">Tanggal</span>
                        <span data-kt-calendar="event_date"></span>
                    </div>
                    <!--end::Event start date/time-->
                </div>
                <!--end::Row-->
                <!--begin::Row-->
                <div class="d-flex align-items-center mb-9">
                    <!--begin::Icon-->
                    <!--begin::Svg Icon | path: icons/duotune/abstract/abs050.svg-->
                    <span class="svg-icon svg-icon-1 svg-icon-danger me-5">
                        {{-- <svg xmlns="http://www.w3.org/2000/svg" width="24px" height="24px" viewBox="0 0 24 24" version="1.1">
                            <circle fill="currentColor" cx="12" cy="12" r="8" />
                        </svg> --}}
                        <i class="bi bi-stopwatch fs-3 text-primary"></i>
                    </span>
                    <!--end::Svg Icon-->
                    <!--end::Icon-->
                    <!--begin::Event end date/time-->
                    <div class="fs-6">
                        <span class="fw-bold">Waktu</span>
                        <span data-kt-calendar="event_time"></span>
                    </div>
                    <!--end::Event end date/time-->
                </div>
                <!--end::Row-->
                <!--begin::Row-->
                <div class="d-flex align-items-center">
                    <!--begin::Icon-->
                    <!--begin::Svg Icon | path: icons/duotune/general/gen018.svg-->
                    <span class="svg-icon svg-icon-1 svg-icon-muted me-5 text-primary">
                        <svg width="24" height="24" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
                            <path opacity="0.3" d="M18.0624 15.3453L13.1624 20.7453C12.5624 21.4453 11.5624 21.4453 10.9624 20.7453L6.06242 15.3453C4.56242 13.6453 3.76242 11.4453 4.06242 8.94534C4.56242 5.34534 7.46242 2.44534 11.0624 2.04534C15.8624 1.54534 19.9624 5.24534 19.9624 9.94534C20.0624 12.0453 19.2624 13.9453 18.0624 15.3453Z" fill="currentColor" />
                            <path d="M12.0624 13.0453C13.7193 13.0453 15.0624 11.7022 15.0624 10.0453C15.0624 8.38849 13.7193 7.04535 12.0624 7.04535C10.4056 7.04535 9.06241 8.38849 9.06241 10.0453C9.06241 11.7022 10.4056 13.0453 12.0624 13.0453Z" fill="currentColor" />
                        </svg>
                    </span>
                    <!--end::Svg Icon-->
                    <!--end::Icon-->
                    <!--begin::Event location-->
                    <div class="fs-6" data-kt-calendar="event_location"></div>
                    <!--end::Event location-->
                </div>
                <!--end::Row-->
            </div>
            <!--end::Modal body-->
        </div>
    </div>
</div>
<!--end::Modal Detail-->

@endsection

@section('js')
    <script src="{{ asset('metronic/assets/plugins/custom/sweetalert2/dist/sweetalert2.all.min.js') }}"></script>
    <script src="{{ asset('metronic/assets/plugins/custom/jsvalidation/jquery.validate.js') }}"></script>
    <script src="{{ asset('metronic/assets/plugins/custom/jsvalidation/additional-methods.min.js') }}"></script>
    <script src="{{ asset('js/calendar-dashboard.js') }}"></script>

    <script>
        $(document).ready(function () {
            $('#filter_unit, #filter_periode').select2();

            const defaultUnit = "{{ $defaultUnit }}";
            const defaultPeriode = "{{ $periodeAktif }}";

            $('#filter_unit').val(defaultUnit).trigger('change');
            $('#filter_periode').val(defaultPeriode).trigger('change');

            loadMonevSopStats(defaultUnit, defaultPeriode);
            loadBarChartTahunan(defaultUnit);
            loadMonevSummary(defaultUnit, defaultPeriode);
            reloadProgresPelaksanaTable(defaultUnit, defaultPeriode);
            loadKonsultasiSopStats(defaultUnit);
            initCalendar(defaultUnit);
            loadJadwalKonsultasiSopStats(defaultUnit);

            $('#btnFilter').on('click', function () {
                let unit = $('#filter_unit').val();
                let periode = $('#filter_periode').val();
                loadMonevSopStats(unit, periode);
                loadBarChartTahunan(unit);
                loadMonevSummary(unit, periode);
                reloadProgresPelaksanaTable(unit, periode);
                loadKonsultasiSopStats(unit);
                initCalendar(unit);
                loadJadwalKonsultasiSopStats(unit);
            });
        });

    </script>

    <script>
        function loadJadwalKonsultasiSopStats(unit_code){
            $('#loader-jadwal-konsultasi-summary').removeClass('d-none');

            $.ajax({
                url: "{{ route('dashboard.konsultasi-sop-offline.jadwal.stats') }}",
                method: "GET",
                data: { unit_code },
                success: function(data) {
                    /// ==== Set Text Values ====
                    document.getElementById('jadwal-total').innerText = data.total ?? 0;
                    document.getElementById('jadwal-selesai').innerText = data.jadwal_selesai  ?? 0;
                    document.getElementById('jadwal-diajukan').innerText = data.jadwal_diajukan  ?? 0;
                    document.getElementById('jadwal-dibatalkan').innerText = data.jadwal_dibatalkan ?? 0;
                    document.getElementById('jadwal-dijadwalkan').innerText = data.jadwal_dijadwalkan ?? 0;
                },
                complete: function() {
                    $('#loader-jadwal-konsultasi-summary').addClass('d-none');
                }
            });
        }
    </script>

    <script>
        function loadKonsultasiSopStats(unit_code){
            $('#loader-konsultasi-summary').removeClass('d-none');

            $.ajax({
                url: "{{ route('dashboard.konsultasi.sop.stats') }}",
                method: "GET",
                data: { unit_code },
                success: function(data) {
                    /// ==== Set Text Values ====
                    document.getElementById('total-konsultasi').innerText = data.total ?? 0;
                    document.getElementById('selesai-konsultasi').innerText = data.konsultasi_selesai  ?? 0;
                    document.getElementById('proses-revisi-konsultasi').innerText = data.konsultasi_proses_revisi  ?? 0;
                    document.getElementById('total-konsultasi-donut').innerText = data.total ?? 0;
                    document.getElementById('selesai-konsultasi-donut').innerText = data.konsultasi_selesai  ?? 0;
                    document.getElementById('proses-revisi-konsultasi-donut').innerText = data.konsultasi_proses_revisi  ?? 0;

                    // ==== Donut Chart ====
                    var options = {
                        series: [
                            data.konsultasi_selesai ?? 0,
                            data.konsultasi_proses_revisi ?? 0
                        ],
                        chart: { type: 'donut', height: 120 },
                        labels: ['Selesai', 'Proses Revisi'],
                        legend: { show: false },
                        colors: ['#388da8', '#ffc107'],
                        // dataLabels: { enabled: false }
                    };

                    var chartInstanceSummary = new ApexCharts(document.querySelector("#konsultasi-chart"), options);
                    chartInstanceSummary.render();
                },
                complete: function() {
                    $('#loader-konsultasi-summary').addClass('d-none');
                }
            });
        }
    </script>

    <script>
        function loadMonevSummary(unit_code, periode_id) {

            $('#loader-monev-summary').removeClass('d-none');

            $.ajax({
                url: "{{ route('dashboard.monev.sop.summary') }}",
                method: "GET",
                data: { periode_id, unit_code },
                success: function(res) {
                    // console.log(res);
                    

                    $('#total-sop-summary').text(res.total_sop);
                    $('#rev-sop').text(res.rev);
                    $('#no-rev-sop').text(res.no_rev);
                    $('#belum-monev-sop').text(res.belum_monev);

                    $('#total-unit').text(res.total_sop);
                    $('#unit-remain').text(`Sisa: ${res.belum_monev} SOP`);
                    $('#unit-percent').text(`${res.progress_percent}%`);
                    $('[data-progress-unit]').css('width', `${res.progress_percent}%`);

                    $('#total-sop-summary-donut').text(res.total_sop);
                    $('#total-sop-donut').text(res.total_sop);
                    $('#sudah-monev-sop-donut').text(res.sudah_monev);
                    $('#belum-monev-sop-donut').text(res.belum_monev);

                    renderMonevDonut(res.rev, res.no_rev, res.belum_monev);
                    renderProgresDonut(res.sudah_monev, res.belum_monev);
                },
                complete: function() {
                    $('#loader-monev-summary').addClass('d-none');
                }
            });
        }

        function renderMonevDonut(rev, noRev, belum) {
            var options = {
                chart: { type: 'donut', height: 120 },
                series: [rev, noRev, belum],
                labels: ['Revisi', 'Tidak Revisi', 'Belum Monev'],
                colors: ['#F1416C', '#388da8', '#E4E6EF'],
                legend: { show: false }
            };

            $("#monevChart").html(""); 
            new ApexCharts(document.querySelector("#monevChart"), options).render();
        }

        function renderProgresDonut(sudah, belum) {
            var options = {
                chart: { type: 'donut', height: 120 },
                series: [sudah, belum],
                labels: ['Sudah Monev', 'Belum Monev'],
                colors: ['#388da8', '#E4E6EF'],
                legend: { show: false }
            };

            $("#monevProgresChart").html("");
            new ApexCharts(document.querySelector("#monevProgresChart"), options).render();
        }

    </script>

    <script>
        var barChartMonev;

        function loadBarChartTahunan(unit_code) {

            $.ajax({
                url: "{{ route('dashboard.monev.sop.bar-chart') }}",
                data: { unit_code },
                success: function(res) {
                    // console.log(res);

                    $("#periode_bar_label").text("Periode : " + res.periode);
                    $("#total_sop_bar_title").text(res.total_sop + " SOP");

                    if(barChartMonev) barChartMonev.destroy(); // reset chart

                     // fallback jika null
                    let labels = Array.isArray(res.tahun) ? res.tahun : [];
                    let dataTidakRevisi = Array.isArray(res.tidak_revisi) ? res.tidak_revisi : [];
                    let dataRevisi = Array.isArray(res.perlu_revisi) ? res.perlu_revisi : [];

                    // kalau data kosong, show dummy agar Apex tidak error
                    if (labels.length === 0) {
                        labels = ["-"];
                        dataTidakRevisi = [0];
                        dataRevisi = [0];
                    }

                    var options = {
                        series: [
                            {
                                name: "Tidak Perlu Revisi",
                                data: dataTidakRevisi
                            },
                            {
                                name: "Perlu Revisi",
                                data: dataRevisi
                            }
                        ],
                        chart: {
                            fontFamily: "inherit",
                            type: "bar",
                            height: '175',
                            toolbar: { show: false },
                        },
                        colors: ["#388da8", "#E4E6EF"], // Biru soft & abu pastel
                        plotOptions: {
                            bar: {
                                horizontal: !1,
                                columnWidth: ["50%"],
                                borderRadius: 4,
                            },
                        },
                        stroke: { width: 0 },
                        dataLabels: { enabled: false },
                        legend: { show: false },
                        grid: {
                            borderColor: "#EEF1F8",
                            strokeDashArray: 5,
                            strokeDashArray: 4,
                            yaxis: { lines: { show: !0 } },
                        },
                        xaxis: {
                            categories: labels,
                            axisBorder: { show: false },
                            axisTicks: { show: false },
                            labels: {
                                style: { colors: "#A3A9B7", fontSize: "12px" },
                            },
                        },
                        yaxis: {
                            y: 0,
                            offsetX: 0,
                            offsetY: 0,
                            labels: {
                                style: { colors: "#A3A9B7", fontSize: "12px" },
                            },
                        },
                        fill: { type: "solid" },
                        states: {
                            normal: { filter: { type: "none", value: 0 } },
                            hover: { filter: { type: "none", value: 0 } },
                            active: {
                                allowMultipleDataPointsSelection: !1,
                                filter: { type: "none", value: 0 },
                            },
                        },
                        tooltip: {
                            style: { fontSize: "12px" },
                            y: {
                                formatter: function (e) {
                                    return e + " SOP";
                                },
                            },
                        },
                    };
                    barChartMonev = new ApexCharts(
                        document.querySelector("#monev-bar-chart-tahunan"),
                        options
                    );
                    barChartMonev.render();
                }
            });
        }
    </script>

    <script>
        function loadMonevSopStats(unit_code, periode_id) {
            let loader = document.getElementById("loading-sop");
            loader.classList.remove("d-none");

            $.ajax({
                url: "{{ route('dashboard.monev.sop.stats') }}",
                method: "GET",
                data: {
                    unit_code: unit_code,
                    periode_id: periode_id
                },
                success: function (data) {

                    $('#total-sop').text(data.total_sop ?? 0);
                    $('#sudah-monev').text(data.sudah_monev ?? 0);
                    $('#belum-monev').text(data.belum_monev ?? 0);
                    $('#unit_pd_nama').text(data.unit_name ?? 'Pemerintah Kabupaten Badung');
                },
                complete: function() {
                    loader.classList.add("d-none");
                }
            });
        }
    </script>

    <script>
        function reloadProgresPelaksanaTable(unit_code, periode_id) {
            // $('#progres_pelaksana_table').DataTable().destroy();
            fill_datatable_progres_pelaksana(unit_code, periode_id);
        }

        function fill_datatable_progres_pelaksana(unit_code, periode_id) {
            // ✅ destroy existing instance if exists
            if ($.fn.DataTable.isDataTable('#progres_pelaksana_table')) {
                $('#progres_pelaksana_table').DataTable().destroy();
            }
            
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
                    url: '{{route("dashboard.monev.sop.datatable-progres")}}',
                    dataSrc: 'data',
                    data : function(param){
                        param.segment = $('#segment').val();
                        param.unit_code  = unit_code;
                        param.periode_id = periode_id;
                    }
                },
                columns: [{
                    sortable: false,
                    searchable: true,
                    data: 'nama_pelaksana',
                    name: 'nama_pelaksana',
                    "className": "align-middle text-nowrap",
                },{
                    sortable: false,
                    searchable: true,
                    data: 'unit_name',
                    name: 'unit_name',
                    "className": "align-middle text-wrap",
                    render: function(data){
                        if (!data) return '';
                        let words = data.split(" ");
                        let short = words.slice(0, 3).join(" ");
                        return words.length > 3 ? short + "..." : short;
                    }
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

    </script>

@endsection