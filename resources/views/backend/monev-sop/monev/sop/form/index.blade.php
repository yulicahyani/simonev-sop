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
            <a href="{{route('monev-sop.sop.index', encrypt($unit->code))}}" data-theme="light" class="btn btn-bg-white btn-active-color-primary">
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

                        <!--begin::Card-->
            <div class="card mb-5">
                <!--begin::Card header-->
                <div class="card-header border-0">
                    <!--begin::Card title-->
                    <div class="card-title">
                        <h3 class="fw-bold my-1"> Form Monitoring dan Evaluasi SOP</h3>
                    </div>
                    <!--begin::Card title-->
                </div>
                <!--end::Card header-->
                <!--begin::Card body-->
                <div class="card-body pt-0">
                    <div class="row g-4">
                        <!-- Kolom kiri: Iframe SOP -->
                        <div class="col-12 col-lg-6">
                            <div class="border overflow-hidden" style="height: 100%;">
                                <iframe 
                                    src="{{ route('file.view', ['filepath' => encrypt($sop->filepath)]) }}" 
                                    width="100%" 
                                    height="100%" 
                                    style="border: none;"
                                    title="Pratinjau SOP">
                                </iframe>
                            </div>
                        </div>

                        <!-- Kolom kanan: Detail SOP -->
                        <div class="col-12 col-lg-6">
                            <div class="border-1 h-100">
                                {{-- <div class="card-header bg-light py-3">
                                    <h5 class="card-title mb-0">Detail SOP</h5>
                                </div> --}}
                                <div class="card-body pt-0">
                                    <table class="table table-row-dashed align-middle mb-0 fs-6 gy-5">
                                        <tbody class="fw-semibold text-gray-700">
                                            <tr>
                                                <td class="text-muted">Nomor SOP</td>
                                                <td>:</td>
                                                <td class="">{{ $sop->nomor }}</td>
                                            </tr>
                                            <tr>
                                                <td class="text-muted">Nama SOP</td>
                                                <td>:</td>
                                                <td class=" text-wrap" style="max-width: 250px;">
                                                    {{ $sop->nama }}
                                                </td>
                                            </tr>
                                            <tr>
                                                <td class="text-muted">File SOP</td>
                                                <td>:</td>
                                                <td class="">
                                                    <a href="{{ route('file.view', ['filepath' => encrypt($sop->filepath)]) }}" 
                                                    target="_blank"
                                                    class="text-primary text-decoration-underline fw-semibold d-inline-flex align-items-center gap-1"
                                                    title="Klik untuk mengunduh file SOP"
                                                    data-bs-toggle="tooltip" 
                                                    data-bs-trigger="hover">
                                                        <i class="bi bi-file-earmark-arrow-down-fill fs-5 text-primary text-decoration-underline"></i> Unduh
                                                    </a>
                                                </td>
                                            </tr>
                                            <tr>
                                                <td class="text-muted">Unit/OPD</td>
                                                <td>:</td>
                                                <td class="">{{ $unit->name }}</td>
                                            </tr>
                                            <tr>
                                                <td class="text-muted">Periode Monev</td>
                                                <td>:</td>
                                                <td>{{ \Carbon\Carbon::parse($active_periode->start_date)->translatedFormat('j F Y') }} - {{ \Carbon\Carbon::parse($active_periode->end_date)->translatedFormat('j F Y') }}</td>
                                            </tr>
                                            <tr>
                                                <td class="text-muted">Jumlah Pertanyaan</td>
                                                <td>:</td>
                                                <td class="">{{ $count_instrumens }}</td>
                                            </tr>
                                            <tr>
                                                <td class="text-muted">Status Pengisian</td>
                                                <td>:</td>
                                                <td class="">
                                                    @if (isset($result_monev) && !empty($result_monev->tgl_pengisian_f01))
                                                        <div class="badge badge-light-success fs-7"><i class="bi bi-check-circle text-success me-2"></i>Sudah Terisi</div>
                                                    @else
                                                        <div class="badge badge-light-danger"><i class="bi bi-x-circle text-danger me-2"></i> Belum Terisi</div>
                                                    @endif
                                                </td>
                                            </tr>
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <!--end::Card body-->

                 <div class="card-footer border-1 p-5">
                    <!--begin::Card title-->
                    <div class="card-title text-center">
                        <a href="{{route("monev-sop.sop.form-monev.f01.index", encrypt($sop->id))}}">
                            <button type="button" class="btn btn-sm btn-primary me-3 fs-6" title="Klik untuk mengisi Form Monev SOP" data-bs-toggle="tooltip" data-bs-trigger="hover">
                                <i class="bi bi-pencil-square fs-4"></i> Buka Form Monev</button>
                        </a>
                    </div>
                    <!--begin::Card title-->
                </div>
            </div>
            <!--end::Card-->

            {{-- <!--begin::Card-->
            <div class="card mb-5">
                <!--begin::Card header-->
                <div class="card-header border-0 pt-6">
                    <!--begin::Card title-->
                    <div class="card-title">
                        <h3 class="fw-bold my-1"> {{$unit->name}}</h3>
                    </div>
                    <!--begin::Card title-->
                </div>
                <!--end::Card header-->
                <!--begin::Card body-->
                <div class="card-body pt-0 table-responsive"  style="overflow-x: auto; overflow-y: hidden;">
                    <!--begin::Table-->
                    <table class="table align-middle table-row-dashed fs-6 gy-5" id="form_monev_table">
                        <!--begin::Table body-->
                        <tbody class="fw-semibold text-gray-600">
                            <tr>
                                <td>Nomor SOP</td>
                                <td>:</td>
                                <td>{{$sop->nomor}}</td>
                            </tr>
                            <tr>
                                <td>Nama SOP</td>
                                <td>:</td>
                                <td class="text-wrap">{{$sop->nama}}</td>
                            </tr>
                            <tr>
                                <td>File SOP</td>
                                <td>:</td>
                                <td>
                                    <a href="{{route('file.view', ['filepath' => encrypt($sop->filepath)])}}" target="_blank">
                                        <button type="button" class="btn btn-sm btn-icon btn-primary text-center" title="Klik untuk mengunduh file SOP" data-bs-toggle="tooltip" data-bs-trigger="hover">
                                                <i class="bi bi-file-earmark-arrow-down-fill fs-2"></i></button>
                                    </a>
                                </td>
                            </tr>
                        </tbody>
                        <!--end::Table body-->
                    </table>
                    <!--end::Table-->
                </div>
                <!--end::Card body-->
            </div>
            <!--end::Card--> --}}
            
            {{-- <!--begin::Card-->
            <div class="card mb-5">
                <!--begin::Card header-->
                <div class="card-header border-0 pt-6">
                    <!--begin::Card title-->
                    <div class="card-title">
                        <h3 class="fw-bold my-1">Form Monev SOP Tahun Periode {{$active_periode->periode_year}}</h3>
                    </div>
                    <!--begin::Card title-->
                </div>
                <!--end::Card header-->
                <!--begin::Card body-->
                <div class="card-body pt-0 table-responsive"  style="overflow-x: auto; overflow-y: hidden;">
                    <!--begin::Table-->
                    <table class="table align-middle table-row-dashed fs-6 gy-5" id="form_monev_table">
                        <!--begin::Table head-->
                        <thead>
                            <!--begin::Table row-->
                            <tr class="text-start text-gray-400 fw-bold fs-7 text-uppercase gs-0">
                                <th class="min-w-125px">Actions</th>
                                <th class="min-w-100px text-center">Form</th>
                                <th class="min-w-100px text-center">Peran</th>
                                <th class="min-w-125px text-center">Status</th>
                                <th class="min-w-100px">Nomor SOP</th>
                                <th class="min-w-150px">Nama SOP</th>
                            </tr>
                            <!--end::Table row-->
                            
                        </thead>
                        <!--end::Table head-->
                        <!--begin::Table body-->
                        <tbody class="fw-semibold text-gray-600">
                            <tr>
                                <td class="">
                                    <a href="{{route("monev-sop.sop.form-monev.f01.index", encrypt($sop->id))}}">
                                        <button type="button" class="btn btn-sm btn-primary me-3" title="Klik untuk mengisi F01" data-bs-toggle="tooltip" data-bs-trigger="hover">
                                            <i class="bi bi-pencil-square fs-4"></i> Buka</button>
                                    </a>
                                </td>
                                <td class="text-center">F01</td>
                                @if (session('defaultRoleCode') == 'admin')
                                        <td class="text-center" style="color: #0d6efd">Melihat</td>
                                @else
                                        <td class="text-center" style="color: #198754">Mengisi</td>
                                @endif
                                <td class="text-center">
                                    @if (isset($result_monev) && !empty($result_monev->tgl_pengisian_f01))
                                        <div class="badge badge-light-success">Sudah Terisi</div>
                                    @else
                                        <div class="badge badge-light-danger">Belum Terisi</div>
                                    @endif
                                </td>
                                <td>{{$sop->nomor}}</td>
                                <td class="text-wrap">{{$sop->nama}}</td>
                            </tr>
                            <tr>
                                @if (session('defaultRoleCode') == 'admin')
                                    <td class="">
                                        <a href="{{route("monev-sop.sop.form-monev.f02.index", encrypt($sop->id))}}">
                                        <button type="button" class="btn btn-sm btn-primary me-3" title="Klik untuk mengisi F02" data-bs-toggle="tooltip" data-bs-trigger="hover">
                                            <i class="bi bi-pencil-square fs-4"></i> Buka</button>
                                        </a>
                                    </td>
                                    <td class="text-center">F02</td>
                                    <td class="text-center" style="color: #198754">Mengisi</td>
                                @else
                                    <td class="">
                                        <a href="{{route("monev-sop.sop.form-monev.f02.index", encrypt($sop->id))}}">
                                        <button type="button" class="btn btn-sm btn-primary me-3" title="Klik untuk melihat hasil F02" data-bs-toggle="tooltip" data-bs-trigger="hover">
                                            <i class="bi bi-eye fs-4"></i> Result</button>
                                        </a>
                                    </td>
                                    <td class="text-center">F02</td>
                                    <td class="text-center" style="color: #0d6efd">Melihat</td>
                                @endif
                                <td class="text-center">
                                    @if (isset($result_monev) && !empty($result_monev->tgl_pengisian_f02))
                                        <div class="badge badge-light-success">Sudah Terisi</div>
                                    @else
                                        <div class="badge badge-light-danger">Belum Terisi</div>
                                    @endif
                                </td>
                                <td>{{$sop->nomor}}</td>
                                <td class="text-wrap">{{$sop->nama}}</td>
                            </tr>
                        </tbody>
                        <!--end::Table body-->
                    </table>
                    <!--end::Table-->
                </div>
                <!--end::Card body-->
            </div>
            <!--end::Card--> --}}

            <!--begin::Card-->
            <div class="card">
                <!--begin::Card header-->
                <div class="card-header border-0 pt-6">
                    <!--begin::Card title-->
                    <div class="card-title">
                        <h3 class="fw-bold my-1">Hasil Monitoring dan Evaluasi SOP Per Periode</h3>
                    </div>
                    <!--begin::Card title-->
                    <!--begin::Card toolbar-->
                    <div class="card-toolbar">
                        <!--begin::Toolbar-->
                        <div class="d-flex justify-content-end" data-kt-customer-table-toolbar="base">
                            
                        </div>
                        <!--end::Toolbar-->
                    </div>
                    <!--end::Card toolbar-->
                </div>
                <!--end::Card header-->
                <!--begin::Card body-->
                <div class="card-body pt-0">
                    <!--begin::Table-->
                    <table class="table align-middle table-row-dashed fs-6 gy-5" id="result_monev_sop_table">
                        <!--begin::Table head-->
                        <thead>
                            <!--begin::Table row-->
                            <tr class="text-start text-gray-400 fw-bold fs-7 text-uppercase gs-0">
                                <th class="min-w-50px">No</th>
                                <th class="min-w-70px">Actions</th>
                                <th class="min-w-70px">Status</th>
                                <th class="min-w-100px">Periode Monev</th>
                                <th class="min-w-100px">Tanggal Pelaksanaan</th>
                                <th class="min-w-100px">Nomor SOP</th>
                                <th class="min-w-125px">Nama SOP</th>
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

@endsection

@section('js')
    <script src="{{ asset('metronic/assets/plugins/custom/sweetalert2/dist/sweetalert2.all.min.js') }}"></script>
    <script src="{{ asset('metronic/assets/plugins/custom/jsvalidation/jquery.validate.js') }}"></script>
    <script src="{{ asset('metronic/assets/plugins/custom/jsvalidation/additional-methods.min.js') }}"></script>

    <script>


        $(document).ready(function(){
            fill_datatable();
        });


        function fill_datatable() {
            let fill_datatable = $('#result_monev_sop_table').DataTable({
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
                    url: '{{route("monev-sop.sop.form-monev.datatable_result", ["sop_id" => $sop->id])}}',
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
                    "className": "align-middle text-nowrap text-center",
                    sortable: false,
                    orderable: false,
                }, {
                    sortable: true,
                    searchable: true,
                    data: 'status_monev',
                    name: 'status_monev',
                    "className": "align-middle text-wrap text-center",
                }, {
                    sortable: false,
                    searchable: true,
                    data: 'periode',
                    name: 'periode_year',
                    "className": "align-middle text-nowrap text-center",
                }, {
                    sortable: false,
                    searchable: true,
                    data: 'tgl_pelaksanaan',
                    name: 'tgl_pelaksanaan',
                    "className": "align-middle text-nowrap text-center",
                }, {
                    sortable: true,
                    searchable: true,
                    data: 'nomor_sop',
                    name: 'nomor',
                    "className": "align-middle text-wrap",
                }, {
                    sortable: true,
                    searchable: true,
                    data: 'nama_sop',
                    name: 'nama',
                    "className": "align-middle text-wrap",
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
            });;
        }

    </script>

@endsection