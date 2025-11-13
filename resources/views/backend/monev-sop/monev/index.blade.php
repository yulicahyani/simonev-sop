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
            <div class="card">
                <!--begin::Card header-->
                <div class="card-header border-0 pt-6">
                    <!--begin::Card title-->
                    <div class="card-title  d-flex flex-column">
                        <h3 class="fw-bold my-1">Unit/PD Monitoring dan Evaluasi SOP</h3>
                        <!--begin::Count-->
                        <p class="my-1">Total {{ $count }} baris data</p>
                        <!--end::Count-->
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
                    <table class="table align-middle table-row-dashed fs-6 gy-5" id="unit_monev_table">
                        <!--begin::Table head-->
                        <thead>
                            <!--begin::Table row-->
                            <tr class="text-start text-gray-400 fw-bold fs-7 text-uppercase gs-0">
                                <th rowspan="2" class="min-w-50px align-middle">No</th>
                                <th rowspan="2" class="min-w-50px align-middle">Actions</th>
                                <th rowspan="2" class="min-w-125px align-middle">Unit/PD</th>
                                <th colspan="4" class="min-w-100px align-middle text-center">Monev Tahun Periode {{$active_periode->periode_year}}</th>
                            </tr>
                            {{-- <tr class="text-start text-gray-400 fw-bold fs-7 text-uppercase gs-0">
                                <th rowspan="2" class="min-w-100px align-middle text-center">Total SOP</th>
                                <th colspan="4" class="min-w-100px align-middle text-center" >Pengisian Form Monev</th>
                            </tr> --}}
                            <tr class="text-start text-gray-400 fw-bold fs-7 text-uppercase gs-0">
                                <th class="min-w-100px align-middle text-center">Total SOP</th>
                                <th class="min-w-70px text-center align-middle" >Total SOP Monev</th>
                                <th class="min-w-70px text-center align-middle" >Persentase SOP Monev (%)</th>
                                <th class="min-w-70px text-center align-middle" >Status Monev</th>
                                {{-- <th class="min-w-70px text-center align-middle" >F02</th>
                                <th class="min-w-70px text-center align-middle" >%</th> --}}
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

<!-- Modal Cetak Laporan -->
<div class="modal fade" id="modalCetak" tabindex="-1" aria-labelledby="modalCetakLabel" aria-hidden="true">
  <div class="modal-dialog modal-dialog-centered">
    <div class="modal-content">
      
      <div class="modal-header">
        <h5 class="modal-title" id="modalCetakLabel">Cetak Laporan Monev SOP</h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Tutup"></button>
      </div>

      <form id="formCetak" target="_blank" enctype="multipart/form-data" method="POST" autocomplete="off">
        @csrf
        <div class="modal-body">
          <div class='form-group mb-5'>
                <label for='periode' class="required">Tahun Periode</label>
                <select id="periode" name="periode" class='form-control form-select has-input-group' style="width: 100%;" data-control="select2" data-dropdown-parent="#modalCetak" required >
                    <option value="">Pilih Tahun Periode</option>
                    @foreach($periodes as $periode)
                        <option value="{{$periode->id}}">Tahun {{$periode->periode_year}}</option>
                    @endforeach
                </select>
                <span id='periode-error' class='error' for='periode'></span>
            </div>
        </div>

        <div class="modal-footer">
          <button type="button" class="btn btn-light" data-bs-dismiss="modal">Batal</button>
          <button type="submit" class="btn btn-primary">Cetak PDF</button>
        </div>
      </form>

    </div>
  </div>
</div>

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
            let fill_datatable = $('#unit_monev_table').DataTable({
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
                    url: '{{route("monev-sop.datatable")}}',
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
                    sortable: true,
                    searchable: true,
                    data: 'nama',
                    name: 'name',
                    "className": "align-middle text-nowrap",
                }, {
                    sortable: true,
                    searchable: false,
                    data: 'total_sop',
                    name: 'total_sop',
                    "className": "align-middle text-nowrap text-center",
                }, {
                    sortable: true,
                    searchable: false,
                    data: 'total_f1',
                    name: 'total_f1',
                    "className": "align-middle text-nowrap text-center",
                }, {
                    sortable: true,
                    searchable: false,
                    data: 'persentase_f1',
                    name: 'persentase_f1',
                    "className": "text-center align-middle text-nowrap",
                }, {
                    sortable: true,
                    searchable: false,
                    data: 'status_monev',
                    name: 'status_monev',
                    "className": "text-center align-middle text-nowrap",
                }
                //  {
                //     sortable: true,
                //     searchable: false,
                //     data: 'total_f2',
                //     name: 'total_f2',
                //     "className": "text-center align-middle text-nowrap text-center",
                // },
                //  {
                //     sortable: true,
                //     searchable: false,
                //     data: 'persentase_f2',
                //     name: 'persentase_f2',
                //     "className": "align-middle text-nowrap text-center",
                // }
                ],
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

        // fungsi dipanggil dari tombol (onclick)
        function reportForm(param) {
            const unitCode = $(param).data('string');
            const unitName = $(param).data('name');

            // update judul modal
            $('#modalCetakLabel').text(`Cetak Laporan Monev SOP - ${unitName}`);
            

            // ubah action form
            const actionUrl = "{{ route('monev-sop.sop.unit.report.cetak', ':unit_code') }}".replace(':unit_code', unitCode);
            $('#formCetak').attr('action', actionUrl);

            // tampilkan modal
            $('#modalCetak').modal('show');
        }

    </script>

@endsection