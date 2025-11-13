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
        <div class="card mb-5">
            <!--begin::Card header-->
            <div class="card-header border-0 pt-6">
                <!--begin::Card title-->
                <div class="card-title  d-flex flex-column">
                    <h3 class="fw-bold my-1">Jadwal Konsultasi SOP</h3>
                    <!--begin::Count-->
                    <p class="my-1">Total {{ $count }} penjadwalan</p>
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
                        <button type="button" class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#modalAddJadwal" onclick="showAddModal(this)">
                            <span class="svg-icon svg-icon-2">
                                            <svg width="24" height="24" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
                                                <rect opacity="0.5" x="11.364" y="20.364" width="16" height="2" rx="1" transform="rotate(-90 11.364 20.364)" fill="currentColor"></rect>
                                                <rect x="4.36396" y="11.364" width="16" height="2" rx="1" fill="currentColor"></rect>
                                            </svg>
                                        </span>
                            Tambah Jadwal</button>
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
                <table class="table align-middle table-row-dashed fs-6 gy-5" id="jadwal_konsultasi_table">
                    <!--begin::Table head-->
                    <thead>
                        <!--begin::Table row-->
                        <tr class="text-start text-gray-400 fw-bold fs-7 text-uppercase gs-0">
                            <th class="min-w-50px">No</th>
                            <th class="min-w-50px">Actions</th>
                            <th class="min-w-125px">Kegiatan</th>
                            <th class="min-w-70px">Status</th>
                            <th class="min-w-100px">Tanggal</th>
                            <th class="min-w-70px">Waktu</th>
                            <th class="min-w-125px">Unit/OPD</th>
                            <th class="min-w-100px">Created By</th>
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

        <!--begin::Card-->
        <div class="card">
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
    <!--end::Post-->
</div>
<!--end::Container-->

{{--modal tambah jadwal start--}}
    <div class="modal fade" id="modalAddJadwal" tabindex="-1" aria-labelledby="add_master_label" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered mw-650px">
            <div class="modal-content">
                <div class="modal-header">
                    <p class="fs-4 my-auto">Form Jadwal Konsultasi</p>
                    <i class="fa-solid fa-circle-xmark fs-4 cursor-pointer text-gray-700 text-hover-danger" data-bs-dismiss="modal" aria-label="Close"></i>
                </div>
                <form id="form_store_jadwal" action="{{ route('konsultasi-sop-offline.jadwal.store') }}" method="POST" autocomplete="off">
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
    {{--modal tambah jadwal end--}}

    <!--begin::Modals-->
    <!--begin::Modal Detail-->
    <div class="modal fade" id="kt_modal_view_event" tabindex="-1" aria-hidden="true">
        <!--begin::Modal dialog-->
        <div class="modal-dialog modal-dialog-centered mw-650px">
            <!--begin::Modal content-->
            <div class="modal-content">
                <!--begin::Modal header-->
                <div class="modal-header border-0 justify-content-end">
                    <!--begin::Edit-->
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
                    <!--end::Edit-->
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
    <!--end::Modals-->

    <!--start::Modal filter-->
    <div class="modal fade" id="modalFilter" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content">
                <div class="modal-header">
                    <p class="fs-4 my-auto" id="modal_filter_title" >Filter Data Jadwal</p>
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
    <script src="https://cdnjs.cloudflare.com/ajax/libs/moment.js/2.29.4/moment-with-locales.min.js"></script>
    <script src="{{ asset('js/calendar.js') }}"></script>

    <script>
        const activeRole = "{{ session('defaultRoleCode') }}";
        const activeUnitCode = "{{ session('unit_code') }}";
    </script>


    <script>


        $(document).ready(function(){
            fill_datatable();

            $('#modalAddJadwal').on('shown.bs.modal', function () {
                handleChangeSelect2();

                $('#date').flatpickr({
                    altInput: true,
                    altFormat: "j F Y",   // tampil: 3 Oktober 2025
                    dateFormat: "Y-m-d",  // kirim ke server: 2025-10-03
                    locale: "id",          // Bahasa Indonesia
                    disableMobile: true,
                });

                flatpickr("#time", {
                    enableTime: true,
                    noCalendar: true,
                    dateFormat: "H:i", // Format 24 jam, contoh: 13:45
                    time_24hr: true,
                    disableMobile: true,
                });
            
            });

            $('#form_store_jadwal').validate({
                errorElement: "div",
                validElement: "div",
                errorClass: "text-danger mb-1 col-12",
                validClass: "text-body",
                ignore: [],
                rules: {
                    title: {
                        required: true
                    },
                    date: {
                        required: true,
                    },
                    time: {
                        required: true
                    },
                    location: {
                        required: true
                    },
                    unit_code: {
                        required: true
                    },
                    status: {
                        required: true
                    },
                },
                messages: {
                    title: {
                        required: 'Kegiatan harus diisi'
                    },
                    date: {
                        required: 'Tanggal harus diisi'
                    },
                    time: {
                        required: 'Waktu harus diisi'
                    },
                    location: {
                        required: 'Lokasi harus diisi'
                    },
                    unit_code: {
                        required: 'Unit/PD harus dipilih'
                    },
                    status: {
                        required: 'Status harus dipilih'
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
            let fill_datatable = $('#jadwal_konsultasi_table').DataTable({
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
                    url: '{{route("konsultasi-sop-offline.jadwal.datatable")}}',
                    dataSrc: 'data',
                    data : function(param){
                        param.segment = $('#segment').val();
                        param.unit = $('#unit_filter').val();
                        param.status = $('#status_filter').val();
                        param.date = $('#date_filter').val();
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
                    data: 'kegiatan',
                    name: 'title',
                    "className": "align-middle text-nowrap",
                }, {
                    sortable: true,
                    searchable: true,
                    data: 'status_jadwal',
                    name: 'status',
                    "className": "align-middle text-nowrap text-center",
                }, {
                    sortable: true,
                    searchable: true,
                    data: 'tgl',
                    name: 'date',
                    "className": "text-center align-middle text-nowrap",
                }, {
                    sortable: true,
                    searchable: true,
                    data: 'waktu',
                    name: 'time',
                    "className": "text-center align-middle text-nowrap",
                }, {
                    sortable: true,
                    searchable: true,
                    data: 'unit',
                    name: 'unit_code',
                    "className": "align-middle text-wrap",
                }, {
                    sortable: true,
                    searchable: true,
                    data: 'created_oleh',
                    name: 'created_by',
                    "className": "align-middle text-wrap",
                }, ],
                drawCallback: function(settings) {
                    // Re-initialize Metronic menus for dynamically added rows
                    if (typeof KTMenu !== 'undefined') {
                        KTMenu.createInstances();
                    }
                }
            });

            // ajax show detail
            $(document).ready(function () {
                $('#jadwal_konsultasi_table tbody').on('click', '.viewdetails', function () {
                    const this_id = fill_datatable.row($(this).closest('tr')).data().id;
                    const unit_code = fill_datatable.row($(this).closest('tr')).data().unit_code;
                    if(activeRole != 'admin'){
                        if (unit_code != activeUnitCode) {
                            Swal.fire({
                                text: "Anda tidak dapat mengubah kegiatan ini!",
                                icon: "error",
                                confirmButtonText: "OK",
                                confirmButtonColor: '#388da8',
                                customClass: { confirmButton: "btn btn-primary" },
                            });
                            return;
                        }
                    }
                    if (this_id > 0) {
                        let url = "{{ route('konsultasi-sop-offline.jadwal.ajax_show', [':this_id']) }}";
                        url = url.replace(':this_id', this_id);
                        $('#tblempinfo tbody').empty();
                        $.ajax({
                            url: url,
                            dataType: 'json',
                            success: function (response) {
                                $('#tblempinfo tbody').html(response.html);
                                $('#modalAddJadwal').modal('show');
                                handleChangeSelect2();
                            }
                        });
                    }
                });
            });
        }

        function deleteForm(param) {
            let id = $(param).data("string")
            let unit_code = $(param).data("unitcode")
            if(activeRole != 'admin'){
                if (unit_code != activeUnitCode) {
                    Swal.fire({
                        text: "Anda tidak dapat menghapus kegiatan ini!",
                        icon: "error",
                        confirmButtonText: "OK",
                        confirmButtonColor: '#388da8',
                        customClass: { confirmButton: "btn btn-primary" },
                    });
                    return;
                }
            }
            Swal.fire({
                title: 'Peringatan',
                text: 'Apakah anda yakin akan akan menghapus jadwal ini?',
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#f1416c',
                cancelButtonColor: '#b5b5c3',
                confirmButtonText: "Ya, hapus",
                cancelButtonText: 'Tidak, batalkan',
            }).then((result) => {
                if (result.isConfirmed) {
                    let action = "{{ route('konsultasi-sop-offline.jadwal.delete', ':id') }}".replace(':id', id);
                    $('#delete_form').attr('action',action);
                    $('#delete_form').submit();
                }
            })
        }

        function showAddModal(){
            $('#modalAddJadwal').modal('show');
            var url = "{{ route('konsultasi-sop-offline.jadwal.ajax_show', [0]) }}";

            $.get(url, function(data) {
                $('#tblempinfo tbody').html(data.html);
                handleChangeSelect2();
            });
        }

        function handleChangeSelect2() {
            $('#unit_code').select2({
                placeholder: 'Pilih Unit/PD',
            });

            $('#unit_code').on('change', function () {
                $(this).valid();
            });
                
            $('#status').select2({
                placeholder: 'Pilih Status',
            });

            $('#status').on('change', function () {
                $(this).valid();
            });
        }

        //Filter
        $('#modalFilter').on('shown.bs.modal', function () {
            handleChangeSelect2Filter();

            $('#date_filter').flatpickr({
                altInput: true,
                altFormat: "j F Y",   // tampil: 3 Oktober 2025
                dateFormat: "Y-m-d",  // kirim ke server: 2025-10-03
                locale: "id",          // Bahasa Indonesia
                disableMobile: true,
            });
        });

        $('#filter_data').on('click', function () {
            $('#jadwal_konsultasi_table').DataTable().ajax.reload();
            $('#modalFilter').modal('hide');
            resetModalFilter();
        });

        function handleChangeSelect2Filter() {
            $('#unit_filter').select2({
                placeholder: 'Pilih unit/PD',
            });
            $('#status_filter').select2({
                placeholder: 'Pilih status',
            });
        }

        function showModalFilter(){
            $('#modalFilter').modal('show');
            var url = "{{ route('konsultasi-sop-offline.jadwal.ajax_filter') }}";

            $.get(url, function(data) {
                $('#tblempfilter tbody').html(data.html);
                handleChangeSelect2Filter();
            });
        }

        function resetModalFilter(){
            $('#unit_filter').val('').change()
            $('#status_filter').val('').change()
        }

    </script>

@endsection