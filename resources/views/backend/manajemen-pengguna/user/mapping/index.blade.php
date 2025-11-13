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
            <a href="{{route('user.index')}}" data-theme="light" class="btn btn-bg-white btn-active-color-primary">
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
            <div class="card">
                <!--begin::Card header-->
                <div class="card-header border-0 pt-6">
                    <!--begin::Card title-->
                    <div class="card-title">
                        <!--begin::Count-->
                        <p class="d-flex fw-bold my-1">Total {{ $count }} baris data</p>
                        <!--end::Count-->
                    </div>
                    <!--begin::Card title-->
                    <!--begin::Card toolbar-->
                    <div class="card-toolbar">
                        <!--begin::Toolbar-->
                        <div class="d-flex justify-content-end" data-kt-customer-table-toolbar="base">
                            
                            <!--begin::Add customer-->
                            <button type="button" class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#modalAddAssignSOP" onclick="showAddModal(this)">
                                <span class="svg-icon svg-icon-2">
												<svg width="24" height="24" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
													<rect opacity="0.5" x="11.364" y="20.364" width="16" height="2" rx="1" transform="rotate(-90 11.364 20.364)" fill="currentColor"></rect>
													<rect x="4.36396" y="11.364" width="16" height="2" rx="1" fill="currentColor"></rect>
												</svg>
											</span>
                                Assign Pengguna</button>
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
                    <table class="table align-middle table-row-dashed fs-6 gy-5" id="mapping_sop_table">
                        <!--begin::Table head-->
                        <thead>
                            <!--begin::Table row-->
                            <tr class="text-start text-gray-400 fw-bold fs-7 text-uppercase gs-0">
                                <th class="min-w-50px">No</th>
                                <th class="min-w-50px">Actions</th>
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

{{--modal tambah assign SOP--}}
    <div class="modal fade" id="modalAddAssignSOP" tabindex="-1" aria-labelledby="add_master_label" aria-hidden="true">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <div class="modal-header">
                    <p class="fs-4 my-auto">Form Assign Pengguna</p>
                    <i class="fa-solid fa-circle-xmark fs-4 cursor-pointer text-gray-700 text-hover-danger" data-bs-dismiss="modal" aria-label="Close"></i>
                </div>
                <form id="form_store_mapping" action="{{ route('user.mapping.store') }}" method="POST" autocomplete="off">
                    @csrf
                    <div class="modal-body">
                        <input type="hidden" name="user_id" value="{{ $user->user_id }}">
                        <input type="hidden" name="unit_code" value="{{ $user->unit_code }}">
                        <input type="hidden" name="role_code" value="{{ $user->role_code }}">
                        <input type="hidden" name="user_role_id" value="{{ $user->id }}">

                        <!-- Hidden input untuk daftar sop terpilih -->
                        <input type="hidden" name="sop_ids" id="selectedSOPInput">

                        <table class="table align-middle table-row-dashed fs-6 gy-5" id="sop_table">
                            <thead>
                                <!--begin::Table row-->
                                <tr class="text-start text-gray-400 fw-bold fs-7 text-uppercase gs-0">
                                    <th class="w-10px">
                                        <div class="form-check form-check-sm form-check-custom form-check-solid me-3">
                                            <input id="check_all" class="form-check-input" type="checkbox" />
                                        </div>
                                    </th>
                                    <th class="min-w-100px">Nomor SOP</th>
                                    <th class="min-w-125px">Nama SOP</th>
                                </tr>
                                <!--end::Table row-->
                            </thead>
                            <tbody class="fw-semibold text-gray-600">
                                
                            </tbody>
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


@endsection

@section('js')
    <script src="{{ asset('metronic/assets/plugins/custom/sweetalert2/dist/sweetalert2.all.min.js') }}"></script>
    <script src="{{ asset('metronic/assets/plugins/custom/jsvalidation/jquery.validate.js') }}"></script>
    <script src="{{ asset('metronic/assets/plugins/custom/jsvalidation/additional-methods.min.js') }}"></script>

    <script>


        $(document).ready(function(){
            fill_datatable();

            $('#modalAddAssignSOP').on('shown.bs.modal', function () {
                if ( $.fn.DataTable.isDataTable('#sop_table') ) {
                    sopTable.columns.adjust().draw(); // ⬅️ wajib biar header sejajar
                } else {
                    fill_datatableSOP(); // kalau belum diinisialisasi
                }
            });

            // fill_datatableSOP();
        });


        function fill_datatable() {
            let fill_datatable = $('#mapping_sop_table').DataTable({
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
                    url: '{{route("user.mapping.datatable", ["user_role_id" => $user->id])}}',
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
                },{
                    sortable: true,
                    searchable: true,
                    data: 'nomor_sop',
                    name: 'nomor',
                    "className": "align-middle text-nowrap",
                }, {
                    sortable: true,
                    searchable: true,
                    data: 'nama_sop',
                    name: 'nama',
                    "className": "align-middle text-nowrap",
                }],
                drawCallback: function(settings) {
                    // Re-initialize Metronic menus for dynamically added rows
                    if (typeof KTMenu !== 'undefined') {
                        KTMenu.createInstances();
                    }
                }
            });

        }
        
        let selectedSOPs = new Set();
        function fill_datatableSOP() {
            
            let fill_datatable_sop = $('#sop_table').DataTable({
                ordering: false, 
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
                    url: '{{route("user.mapping.datatable-sop", ["unit_code" => $user->unit_code])}}',
                    dataSrc: 'data',
                    data : function(param){
                        param.segment = $('#segment').val();
                    }
                },
                columns: [{
                    data: 'checkbox',
                    "className": "text-center align-middle text-nowrap",
                    sortable: false,
                    orderable: false,
                }, {
                    sortable: false,
                    searchable: true,
                    data: 'nomor_sop',
                    name: 'nomor',
                    "className": "align-middle text-nowrap",
                }, {
                    sortable: false,
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
            });

            // ✅ Klik checkbox individual
            $('#sop_table').on('change', '.sop-checkbox', function () {
                const id = $(this).val();
                if (this.checked) {
                    selectedSOPs.add(id);
                } else {
                    selectedSOPs.delete(id);
                }

                // Update state tombol check all
                const rows = fill_datatable_sop.rows({ search: 'applied' }).nodes();
                const allChecked =
                    $('input.sop-checkbox:checked', rows).length === $('input.sop-checkbox', rows).length;
                $('#check_all').prop('checked', allChecked);
            });

            // ✅ Klik "Check All"
            $('#check_all').on('change', function () {
                const rows = fill_datatable_sop.rows({ search: 'applied' }).nodes();
                const checked = this.checked;
                $('input.sop-checkbox', rows).each(function () {
                    $(this).prop('checked', checked);
                    const id = $(this).val();
                    if (checked) selectedSOPs.add(id);
                    else selectedSOPs.delete(id);
                });
            });

            // ✅ Saat redraw (misalnya ganti halaman / search)
            fill_datatable_sop.on('draw', function () {
                fill_datatable_sop.rows().every(function () {
                    const data = this.data();
                    if (selectedSOPs.has(String(data.id))) {
                        $(this.node()).find('.sop-checkbox').prop('checked', true);
                    }
                });

                const rows = fill_datatable_sop.rows({ search: 'applied' }).nodes();
                const allChecked =
                    $('input.sop-checkbox:checked', rows).length === $('input.sop-checkbox', rows).length;
                $('#check_all').prop('checked', allChecked);
            });
        }

        // --- sebelum submit form
        $('#form_store_mapping').on('submit', function (e) {
            if (selectedSOPs.size === 0) {
                e.preventDefault();
                Swal.fire({
                    icon: 'warning',
                    title: 'Tidak ada SOP dipilih!',
                    text: 'Silakan pilih minimal satu SOP sebelum menyimpan.',
                    confirmButtonColor: '#388da8',
                    confirmButtonText: 'OK'
                });
                return false;
            }

            // masukkan SOP terpilih ke hidden input
            $('#selectedSOPInput').val(JSON.stringify(Array.from(selectedSOPs)));
        });

        function showAddModal(){
            $('#modalAddAssignSOP').modal('show');
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
                    let action = "{{ route('user.mapping.delete', ':id') }}".replace(':id', id);
                    $('#delete_form').attr('action',action);
                    $('#delete_form').submit();
                }
            })
        }

    </script>

@endsection