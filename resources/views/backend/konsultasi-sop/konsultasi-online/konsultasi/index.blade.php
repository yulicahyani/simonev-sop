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
            <a href="{{route('konsultasi-sop-online.index')}}" data-theme="light" class="btn btn-bg-white btn-active-color-primary">
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
                                <div class="fw-semibold fs-6">Total Konsultasi SOP</div>
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

            <!--begin::Card-->
            <div class="card">
                <!--begin::Card header-->
                <div class="card-header border-0 pt-6">
                    <!--begin::Card title-->
                    <div class="card-title d-flex flex-column">
                        <h3 class="fw-bold my-1">Daftar Konsultasi SOP {{$unit->name}}</h3>
                        <!--begin::Count-->
                        <p class="my-1">Total {{ $count }} baris data</p>
                    </div>
                    <!--begin::Card title-->
                    <!--begin::Card toolbar-->
                    <div class="card-toolbar">
                        <!--begin::Toolbar-->
                        <div class="d-flex justify-content-end" data-kt-customer-table-toolbar="base">

                            <!--begin::Filter-->
                            <button onclick="showModalFilter()" type="button" class="btn btn-light-primary me-3" data-kt-menu-trigger="click" data-kt-menu-placement="bottom-end">
                            <!--begin::Svg Icon | path: icons/duotune/general/gen031.svg-->
                            <span class="svg-icon svg-icon-2">
                                <svg width="24" height="24" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
                                    <path d="M19.0759 3H4.72777C3.95892 3 3.47768 3.83148 3.86067 4.49814L8.56967 12.6949C9.17923 13.7559 9.5 14.9582 9.5 16.1819V19.5072C9.5 20.2189 10.2223 20.7028 10.8805 20.432L13.8805 19.1977C14.2553 19.0435 14.5 18.6783 14.5 18.273V13.8372C14.5 12.8089 14.8171 11.8056 15.408 10.964L19.8943 4.57465C20.3596 3.912 19.8856 3 19.0759 3Z" fill="currentColor" />
                                </svg>
                            </span>
                            <!--end::Svg Icon-->Filter</button>
                            <!--end::Filter-->

                            <!--begin::Add customer-->
                            <a href="{{ route('konsultasi-sop-online.konsultasi-sop.room.index', [encrypt($unit->code)])}}">
                            <button type="button" class="btn btn-primary">
                                <span class="svg-icon svg-icon-2">
												<svg width="24" height="24" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
													<rect opacity="0.5" x="11.364" y="20.364" width="16" height="2" rx="1" transform="rotate(-90 11.364 20.364)" fill="currentColor"></rect>
													<rect x="4.36396" y="11.364" width="16" height="2" rx="1" fill="currentColor"></rect>
												</svg>
											</span>
                                Tambah Konsultasi</button>
                            </a>
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
                    <table class="table align-middle table-row-dashed fs-6 gy-5" id="konsultasi_table">
                        <!--begin::Table head-->
                        <thead>
                            <!--begin::Table row-->
                            <tr class="text-start text-gray-400 fw-bold fs-7 text-uppercase gs-0">
                                <th class="min-w-50px">No</th>
                                <th class="min-w-50px">Actions</th>
                                <th class="min-w-100px text-center">Konsultasi</th>
                                <th class="min-w-70px text-center">Status</th>
                                <th class="min-w-150px text-center">Created By</th>
                                <th class="min-w-150px">Nama SOP</th>
                                <th class="min-w-100px text-center">Created At</th>
                            </tr>
                            <!--end::Table row-->
                            
                        </thead>
                        <!--end::Table head-->
                        <!--begin::Table body-->
                        <tbody class="fw-semibold text-gray-600">
                            {{-- <tr>
                                <td>1</td>
                                <td>
                                    <a href="#" class="btn btn-sm btn-light btn-active-light-primary fs-6" 
                                    data-kt-menu-trigger="click" 
                                    data-kt-menu-placement="bottom-end">
                                        Actions
                                        <span class="svg-icon svg-icon-5 rotate-180 ms-2 me-0"><i class="bi bi-chevron-down"></i></span>
                                    </a>
                                    <div class="menu menu-sub menu-sub-dropdown menu-column menu-rounded menu-gray-600 menu-state-bg-light-primary fw-semibold fs-6 w-125px py-4"
                                        data-kt-menu="true" 
                                        data-kt-menu-attach="body">
                                        <div class="menu-item px-3" title="Klik untuk mengedit data" data-bs-toggle="tooltip" data-bs-trigger="hover">
                                            <button class="dropdown-item menu-link px-3" onclick="doneKonsultasiForm(this)"
                                                data-string="{{ encrypt(9) }}">Akhiri Konsultasi</button>
                                        </div>
                                        <div class="menu-item px-3" title="Klik untuk mengedit data" data-bs-toggle="tooltip" data-bs-trigger="hover">
                                            <button class="dropdown-item menu-link px-3" onclick="openKonsultasiForm(this)"
                                                data-string="{{ encrypt(9) }}">Buka Kembali Konsultasi</button>
                                        </div>
                                        <div class="menu-item px-3" title="Klik untuk menghapus data" data-bs-toggle="tooltip" data-bs-trigger="hover">
                                            <button class="dropdown-item menu-link px-3" onclick="deleteForm(this)"
                                                data-string="{{ encrypt(9) }}">Hapus Konsultasi</button>
                                        </div>
                                    </div>
                                </td>
                                <td>
                                    <a href="{{route('konsultasi-sop-online.konsultasi-sop.room.index', [encrypt($unit->code), encrypt(9)])}}">
                                        <button type="button" class="btn btn-sm btn-light-primary me-3 fs-6" title="Klik untuk mengisi form monev" data-bs-toggle="tooltip" data-bs-trigger="hover"
                                                ><i class="bi bi-chat-dots fs-4"></i>Konsultasi</button>
                                    </a>
                                </td>
                                <td>
                                    <div class="text-center">
                                        <div class="badge badge-light-success fs-7 px-2"><i class="bi bi-check2-square fs-7 me-1 text-success"></i>Selesai</div>
                                    </div>
                                </td>
                                <td>NI MADE YULI CAHYANI</td>
                                <td class="text-wrap">Tata Cara Untuk Memberikan Nama Standar Operasional Prosedur</td>
                                <td>2 November 2025 02:37:10</td>
                            </tr> --}}
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

<!--start::Modal filter-->
<div class="modal fade" id="modalFilter" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header">
                <p class="fs-4 my-auto" id="modal_filter_title" >Filter Data Periode</p>
                <i class="fa-solid fa-xmark fs-4 cursor-pointer text-gray-700 text-hover-primary" data-bs-dismiss="modal" aria-label="Close"></i>
            </div>
            <div class="modal-body">
                <table class="" id="tblempfilter">
                    <tbody>
                    </tbody>
                </table>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-light btn-active-light-primary me-2 btn-sm" data-bs-dismiss="modal" onclick="resetModalFilter(this)">Reset</button>
                <button type="button" class="btn btn-primary btn-sm" id="filter_data">
                    Apply
                </button>
            </div>
        </div>
    </div>
</div>
<!--end::Modal filter-->

{{-- Delete Form --}}
<form id="delete_form" action="" method="POST" hidden>
    @csrf
</form>

{{-- Akhir Konsultasi Form --}}
<form id="akhiri_konsultasi_form" action="" method="POST" hidden>
    @csrf
</form>

{{-- Buka Kembali Konsultasi Form --}}
<form id="buka_kembali_konsultasi_form" action="" method="POST" hidden>
    @csrf
</form>

@endsection

@section('js')
    <script src="{{ asset('metronic/assets/plugins/custom/sweetalert2/dist/sweetalert2.all.min.js') }}"></script>
    <script src="{{ asset('metronic/assets/plugins/custom/jsvalidation/jquery.validate.js') }}"></script>
    <script src="{{ asset('metronic/assets/plugins/custom/jsvalidation/additional-methods.min.js') }}"></script>

    <script>
    document.addEventListener("DOMContentLoaded", function () {

        let loader_summary = document.getElementById("loader-konsultasi-summary");

        fetch('{{ route("dashboard.konsultasi-sop.stats", ["unit_code" => $unit->code]) }}')
            .then(res => res.json())
            .then(data => {

                // ==== Set Text Values ====
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

            })
            .catch(err => console.error("Error load summary:", err))
            .finally(() => {
                loader_summary.classList.add("d-none");
            });

    });
    </script>

    <script>


        $(document).ready(function(){
            fill_datatable();
        });


        function fill_datatable() {
            let fill_datatable = $('#konsultasi_table').DataTable({
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
                    url: '{{route("konsultasi-sop-online.konsultasi.datatable", ["unit_code" => $unit->code])}}',
                    dataSrc: 'data',
                    data : function(param){
                        param.segment = $('#segment').val();
                        param.created_by = $('#created_by_filter').val();
                        param.status = $('#status_filter').val();
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
                    data: 'konsultasi',
                    name: 'konsultasi',
                    "className": "align-middle text-nowrap",
                }, {
                    sortable: true,
                    searchable: true,
                    data: 'status_konsultasi',
                    name: 'status',
                    "className": "align-middle text-nowrap",
                }, {
                    sortable: true,
                    searchable: true,
                    data: 'nama_pembuat',
                    name: 'created_by',
                    "className": "align-middle text-wrap",
                }, {
                    sortable: true,
                    searchable: true,
                    data: 'nama_sop_konsultasi',
                    name: 'nama_sop',
                    "className": "align-middle text-wrap",
                }, {
                    sortable: true,
                    searchable: true,
                    data: 'tgl_pembuatan',
                    name: 'created_at',
                    "className": "align-middle text-nowrap text-center",
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

        function doneKonsultasiForm(param) {
            let id = $(param).data("string")
            let status = $(param).data("status")
            Swal.fire({
                title: 'Peringatan',
                text: 'Apakah anda yakin akan mengakhiri sesi konsultasi ini? Konsultasi akan dinyatakan selesai!',
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#388da8',
                cancelButtonColor: '#b5b5c3',
                confirmButtonText: "Ya, selesai",
                cancelButtonText: 'Tidak, batalkan',
            }).then((result) => {
                if (result.isConfirmed) {
                    let action = "{{ route('konsultasi-sop-online.konsultasi.update-status', [':id', ':status']) }}"
                                .replace(':id', id)
                                .replace(':status', status);
                    $('#akhiri_konsultasi_form').attr('action',action);
                    $('#akhiri_konsultasi_form').submit();
                }
            })
        }

        function openKonsultasiForm(param) {
            let id = $(param).data("string")
            let status = $(param).data("status")
            Swal.fire({
                title: 'Peringatan',
                text: 'Apakah anda yakin akan membuka kembali sesi konsultasi ini? Konsultasi akan dibuka kembali!',
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#388da8',
                cancelButtonColor: '#b5b5c3',
                confirmButtonText: "Ya, buka kembali",
                cancelButtonText: 'Tidak, batalkan',
            }).then((result) => {
                if (result.isConfirmed) {
                    let action = "{{ route('konsultasi-sop-online.konsultasi.update-status', [':id', ':status']) }}"
                                .replace(':id', id)
                                .replace(':status', status);
                    $('#buka_kembali_konsultasi_form').attr('action',action);
                    $('#buka_kembali_konsultasi_form').submit();
                }
            })
        }

        //Filter

        $('#modalFilter').on('shown.bs.modal', function () {
            handleChangeSelect2Filter();
        });

        $('#filter_data').on('click', function () {
            $('#konsultasi_table').DataTable().ajax.reload();
            $('#modalFilter').modal('hide');
            resetModalFilter();
        });

        function handleChangeSelect2Filter() {
            $('#created_by_filter').select2({
                placeholder: 'Pilih pengguna',
            });
            $('#status_filter').select2({
                placeholder: 'Pilih status',
            });
        }

        function showModalFilter(){
            $('#modalFilter').modal('show');
            var url = "{{ route('konsultasi-sop-online.konsultasi.ajax_filter') }}";

            $.get(url, function(data) {
                $('#tblempfilter tbody').html(data.html);
                handleChangeSelect2Filter();
            });
        }

        function resetModalFilter(){
            $('#created_by_filter').val('').change()
            $('#status_filter').val('').change()
        }

    </script>

@endsection