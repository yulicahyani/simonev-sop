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
                            <button type="button" class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#modalAddPermission" onclick="showAddModal(this)">
                                <span class="svg-icon svg-icon-2">
												<svg width="24" height="24" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
													<rect opacity="0.5" x="11.364" y="20.364" width="16" height="2" rx="1" transform="rotate(-90 11.364 20.364)" fill="currentColor"></rect>
													<rect x="4.36396" y="11.364" width="16" height="2" rx="1" fill="currentColor"></rect>
												</svg>
											</span>
                                Tambah Hak Akses</button>
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
                    <table class="table align-middle table-row-dashed fs-6 gy-5" id="permission_table">
                        <!--begin::Table head-->
                        <thead>
                            <!--begin::Table row-->
                            <tr class="text-start text-gray-400 fw-bold fs-7 text-uppercase gs-0">
                                <th class="min-w-50px">No</th>
                                <th class="min-w-50px">Actions</th>
                                <th class="min-w-70px">Is Parent</th>
                                <th class="min-w-125px">Nama Hak Akses</th>
                                <th class="min-w-125px">Nama Hak Akses Ditampilkan</th>
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

{{--modal tambah hak askes start--}}
    <div class="modal fade" id="modalAddPermission" tabindex="-1" aria-labelledby="add_master_label" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <p class="fs-4 my-auto">Form Hak Akses</p>
                    <i class="fa-solid fa-circle-xmark fs-4 cursor-pointer text-gray-700 text-hover-danger" data-bs-dismiss="modal" aria-label="Close"></i>
                </div>
                <form id="form_store_permission" action="{{ route('permission.store') }}" method="POST" autocomplete="off">
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
    {{--modal tambah hak askes end--}}

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

            $('#modalAddPermission').on('shown.bs.modal', function () {
                handleChangeSelect2();
            });

            $('#form_store_permission').validate({
                errorElement: "div",
                validElement: "div",
                errorClass: "text-danger mb-1 col-12",
                validClass: "text-body",
                ignore: [],
                rules: {
                    is_parent: {
                        required: true
                    },
                    parent_id: {
                        required: true,
                    },
                    name: {
                        required: true
                    },
                    display_name: {
                        required: true
                    }
                },
                messages: {
                    is_parent: {
                        required: 'Is parent harus dipilih'
                    },
                    parent_id: {
                        required: 'Parent harus dipilih'
                    },
                    name: {
                        required: 'Name harus diisi'
                    },
                    display_name: {
                        required: 'Display Name harus diisi'
                    }
                },
                errorPlacement: function(error, element) {
                    if (element.hasClass('has-input-group')) {
                        error.insertAfter(element.next('span'));
                    } else {
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
            let fill_datatable = $('#permission_table').DataTable({
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
                    url: '{{route("permission.datatable")}}',
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
                    data: 'parent',
                    name: 'is_parent',
                    "className": "align-middle text-nowrap",
                }, {
                    sortable: true,
                    searchable: true,
                    data: 'nama',
                    name: 'name',
                    "className": "align-middle text-nowrap",
                }, {
                    sortable: true,
                    searchable: true,
                    data: 'display_nama',
                    name: 'display_name',
                    "className": "align-middle text-nowrap",
                }],
                drawCallback: function(settings) {
                    // Re-initialize Metronic menus for dynamically added rows
                    if (typeof KTMenu !== 'undefined') {
                        KTMenu.createInstances();
                    }
                }
            });

            // ajax show detail
            $(document).ready(function () {
                $('#permission_table tbody').on('click', '.viewdetails', function () {
                    const this_id = fill_datatable.row($(this).closest('tr')).data().id;
                    if (this_id > 0) {
                        let url = "{{ route('permission.ajax_show', [':this_id']) }}";
                        url = url.replace(':this_id', this_id);
                        $('#tblempinfo tbody').empty();
                        $.ajax({
                            url: url,
                            dataType: 'json',
                            success: function (response) {
                                $('#tblempinfo tbody').html(response.html);
                                $('#modalAddPermission').modal('show');
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
                    let action = "{{ route('permission.delete', ':id') }}".replace(':id', id);
                    $('#delete_form').attr('action',action);
                    $('#delete_form').submit();
                }
            })
        }

        function showAddModal(){
            $('#modalAddPermission').modal('show');
            var url = "{{ route('permission.ajax_show', [0]) }}";

            $.get(url, function(data) {
                $('#tblempinfo tbody').html(data.html);
                handleChangeSelect2();
            });
        }

        function handleChangeSelect2() {
            $('#is_parent').select2({
                placeholder: 'Pilih Is Parent',
            });
            $('#is_parent').on('change', function () {
                $(this).valid();
            });
            $('#parent_id').select2({
                placeholder: 'Pilih Parent',
            });
            $('#parent_id').on('change', function () {
                $(this).valid();
            });
        }

    </script>

@endsection