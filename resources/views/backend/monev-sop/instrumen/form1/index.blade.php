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
                            <button type="button" class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#modalAddInstrumen" onclick="showAddModal(this)">
                                <span class="svg-icon svg-icon-2">
												<svg width="24" height="24" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
													<rect opacity="0.5" x="11.364" y="20.364" width="16" height="2" rx="1" transform="rotate(-90 11.364 20.364)" fill="currentColor"></rect>
													<rect x="4.36396" y="11.364" width="16" height="2" rx="1" fill="currentColor"></rect>
												</svg>
											</span>
                                Tambah Instrumen</button>
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
                    <table class="table align-middle table-row-dashed fs-6 gy-5" id="instrumen_table">
                        <!--begin::Table head-->
                        <thead>
                            <!--begin::Table row-->
                            <tr class="text-start text-gray-400 fw-bold fs-7 text-uppercase gs-0">
                                <th class="min-w-50px">No</th>
                                <th class="min-w-50px">Actions</th>
                                <th class="min-w-125px">Instrumen</th>
                                <th class="min-w-100px">Kategori</th>
                                <th class="min-w-125px">Aspek</th>
                                <th class="min-w-125px">Deskripsi Catatan</th>
                                <th class="min-w-125px">Deskripsi TL</th>
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

{{--modal tambah instrumen start--}}
    <div class="modal fade" id="modalAddInstrumen" tabindex="-1" aria-labelledby="add_master_label" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <p class="fs-4 my-auto">Form Instrumen Monev</p>
                    <i class="fa-solid fa-circle-xmark fs-4 cursor-pointer text-gray-700 text-hover-danger" data-bs-dismiss="modal" aria-label="Close"></i>
                </div>
                <form id="form_store_instrumen" action="{{ route('instrumen-form1.store') }}" method="POST" autocomplete="off">
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
    {{--modal tambah instrumen end--}}

    <!--start::Modal filter-->
    <div class="modal fade" id="modalFilter" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content">
                <div class="modal-header">
                    <p class="fs-4 my-auto" id="modal_filter_title" >Filter Data Instrumen</p>
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

@endsection

@section('js')
    <script src="{{ asset('metronic/assets/plugins/custom/sweetalert2/dist/sweetalert2.all.min.js') }}"></script>
    <script src="{{ asset('metronic/assets/plugins/custom/jsvalidation/jquery.validate.js') }}"></script>
    <script src="{{ asset('metronic/assets/plugins/custom/jsvalidation/additional-methods.min.js') }}"></script>

    <script>


        $(document).ready(function(){
            fill_datatable();

            $('#modalAddInstrumen').on('shown.bs.modal', function () {
                handleChangeSelect2();
            
            });

            $('#form_store_instrumen').validate({
                errorElement: "div",
                validElement: "div",
                errorClass: "text-danger mb-1 col-12",
                validClass: "text-body",
                ignore: [],
                rules: {
                    instrumes: {
                        required: true
                    },
                    kategori: {
                        required: true,
                    },
                    aspek: {
                        required: true
                    },
                },
                messages: {
                    instrumen: {
                        required: 'Nama instrumen harus diisi'
                    },
                    kategori: {
                        required: 'Kategori instrumen harus dipilih'
                    },
                    aspek: {
                        required: 'Aspek instrumen harus dipilih'
                    },
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
            let fill_datatable = $('#instrumen_table').DataTable({
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
                    url: '{{route("instrumen-form1.datatable")}}',
                    dataSrc: 'data',
                    data : function(param){
                        param.segment = $('#segment').val();
                        param.kategori = $('#kategori_filter').val();
                        param.aspek = $('#aspek_filter').val();
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
                    data: 'nama_instrumen',
                    name: 'instrumen',
                    "className": "align-middle text-wrap",
                }, {
                    sortable: true,
                    searchable: true,
                    data: 'kategori_instrumen',
                    name: 'kategori',
                    "className": "align-middle text-nowrap",
                }, {
                    sortable: true,
                    searchable: true,
                    data: 'aspek_instrumen',
                    name: 'aspek',
                    "className": "align-middle text-nowrap",
                }, {
                    sortable: true,
                    searchable: true,
                    data: 'catatan_deskripsi',
                    name: 'catatan_des',
                    "className": "align-middle text-wrap",
                },  {
                    sortable: true,
                    searchable: true,
                    data: 'tl_deskripsi',
                    name: 'tindak_lanjut_des',
                    "className": "align-middle text-wrap",
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
                $('#instrumen_table tbody').on('click', '.viewdetails', function () {
                    const this_id = fill_datatable.row($(this).closest('tr')).data().id;
                    if (this_id > 0) {
                        let url = "{{ route('instrumen-form1.ajax_show', [':this_id']) }}";
                        url = url.replace(':this_id', this_id);
                        $('#tblempinfo tbody').empty();
                        $.ajax({
                            url: url,
                            dataType: 'json',
                            success: function (response) {
                                $('#tblempinfo tbody').html(response.html);
                                $('#modalAddInstrumen').modal('show');
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
                    let action = "{{ route('instrumen-form1.delete', ':id') }}".replace(':id', id);
                    $('#delete_form').attr('action',action);
                    $('#delete_form').submit();
                }
            })
        }

        function showAddModal(){
            $('#modalAddInstrumen').modal('show');
            var url = "{{ route('instrumen-form1.ajax_show', [0]) }}";

            $.get(url, function(data) {
                $('#tblempinfo tbody').html(data.html);
                handleChangeSelect2();
            });
        }

        function handleChangeSelect2() {
                
            $('#kategori').select2({
                placeholder: 'Pilih Kategori',
            });

            $('#aspek').select2({
                placeholder: 'Pilih Aspek',
            });

            $('#aspek').on('change', function () {
                $(this).valid();
            });

            $('#kategori').on('change', function () {
                $(this).valid();
            });
        }

        //Filter

        $('#modalFilter').on('shown.bs.modal', function () {
            handleChangeSelect2Filter();
        });

        $('#filter_data').on('click', function () {
            $('#instrumen_table').DataTable().ajax.reload();
            $('#modalFilter').modal('hide');
            resetModalFilter();
        });

        function handleChangeSelect2Filter() {
            $('#kategori_filter').select2({
                placeholder: 'Pilih kategori',
            });
            $('#aspek_filter').select2({
                placeholder: 'Pilih aspek',
            });
        }

        function showModalFilter(){
            $('#modalFilter').modal('show');
            var url = "{{ route('instrumen-form1.ajax_filter') }}";

            $.get(url, function(data) {
                $('#tblempfilter tbody').html(data.html);
                handleChangeSelect2Filter();
            });
        }

        function resetModalFilter(){
            $('#kategori_filter').val('').change()
            $('#aspek_filter').val('').change()
        }

    </script>

@endsection