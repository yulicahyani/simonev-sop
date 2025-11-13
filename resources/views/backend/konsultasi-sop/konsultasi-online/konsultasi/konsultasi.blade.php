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
            <a href="{{ route("konsultasi-sop-online.konsultasi.index", encrypt($unit->code)) }}" data-theme="light" class="btn btn-bg-white btn-active-color-primary">
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
            <form id="konsultasi_form" class="form d-flex flex-column flex-lg-row" action="{{ route('konsultasi-sop-online.konsultasi-sop.room.store') }}" enctype="multipart/form-data" method="POST" autocomplete="off">
                @csrf
                <!--begin::Aside column-->
                <div class="d-flex flex-column gap-7 gap-lg-5 w-100 w-lg-300px mb-5 me-lg-5">
                    <!--begin::Konsultasi settings-->
                    <div class="card card-flush py-4">
                        <!--begin::Card header-->
                        <div class="card-header">
                            <!--begin::Card title-->
                            <div class="card-title">
                                <h2>Data Konsultasi</h2>
                            </div>
                            <!--end::Card title-->
                        </div>
                        <!--end::Card header-->
                        <!--begin::Card body-->
                        <div class="card-body pt-0">
                            <input name='id' type='hidden' value='{{ $konsultasi->id ?? '' }}'>
                            <input name='user_id' type='hidden' value='{{ $konsultasi->user_id ?? session('id') }}'>
                            <input name='user_id_chat' type='hidden' value='{{ session('id') }}'>
                            <input name='role_code_chat' type='hidden' value='{{ session('defaultRoleCode') }}'>
                            <input name='created_by_chat' type='hidden' value='{{ session('nama') }}'>

                            {{-- <div class="row col-md-12"> --}}
                                <div class='form-group mb-5'>
                                    <label for='nama_sop' class="required">Nama/Judul SOP</label>
                                    <textarea type="text" class="form-control" rows="2" placeholder="Masukkan nama SOP" id="nama_sop"
                                                name="nama_sop" required @if(isset($konsultasi)) readonly @endif>{{ $konsultasi->nama_sop ?? '' }}</textarea>
                                    <span id='nama_sop-error' class='error' for='nama_sop'></span>
                                </div>

                                <div class='form-group mb-5'>
                                    <label for='unit_code' class="required">Unit/Perangkat Daerah</label>
                                    <select id="unit_code_select" name="unit_code_select" class='form-control form-select has-input-group' style="width: 100%;" data-control="select2" disabled >
                                        <option value="">Pilih Unit/PD</option>
                                        @foreach($units as $u)
                                            @php
                                                $opsiSelect = $u->code == $unit->code ? 'selected' : '';
                                            @endphp
                                            <option value="{{$u->code}}" {{ $opsiSelect }}>{{$u->name}}</option>
                                        @endforeach
                                    </select>
                                    <input type="hidden" id="unit_code" name="unit_code" value="{{ $konsultasi->unit_code ?? $unit->code }}" required readonly>
                                    <span id='unit_code-error' class='error' for='unit_code'></span>
                                </div>

                                <div class='form-group mb-5'>
                                    <label for='created_by' class="required">Dibuat Oleh</label>
                                    <input type="text" class="form-control" placeholder="Masukkan nama" id="created_by"
                                                name="created_by" value="{{ $konsultasi->created_by ?? session('nama') }}" required readonly/>
                                    <span id='created_by-error' class='error' for='created_by'></span>
                                </div>

                                <div class='form-group mb-5'>
                                    <label for='created_at' class="required">Dibuat Pada</label>
                                    @php
                                        $createdAt = $konsultasi?->created_at
                                            ? \Carbon\Carbon::parse($konsultasi->created_at)->translatedFormat('d F Y H:i:s')
                                            : \Carbon\Carbon::parse(now())->translatedFormat('d F Y');
                                    @endphp
                                    <input type="text" class="form-control" placeholder="Masukkan tanggal" id="created_at"
                                                name="created_at" value="{{ $createdAt }}" required readonly/>
                                    <span id='created_at-error' class='error' for='created_at'></span>
                                </div>
                            {{-- </div> --}}
                        </div>
                        <!--end::Card body-->
                    </div>
                    <!--end::Konsultasi settings-->

                    @if ($konsultasi)
                        <!--begin::Status-->
                        <div class="card card-flush py-0">
                            <!--begin::Card header-->
                            <div class="card-header">
                                <!--begin::Card title-->
                                <div class="card-title">
                                    <h2>Status</h2>
                                </div>
                                <!--end::Card title-->
                                <!--begin::Card toolbar-->
                                <div class="card-toolbar">
                                    @if ($konsultasi->status == 'Selesai')
                                        <div class="badge badge-light-success fs-7 px-2"><i class="bi bi-check2-square fs-7 me-1 text-success"></i>Selesai</div>
                                    @else
                                        <div class="badge badge-light-warning fs-7 px-2"><i class="bi bi-pencil-square fs-7 me-1 text-warning"></i>Proses Revisi</div>
                                    @endif
                                </div>
                                <!--begin::Card toolbar-->
                            </div>
                            <!--end::Card header-->
                            <!--begin::Card body-->
                            <div class="card-body pt-0">
                                @if ($konsultasi->status == 'Selesai')
                                    <!--begin::open-->
                                    <button type="button" class="btn btn-primary w-100" title="Klik untuk membuka kembali sesi konsultasi" data-bs-toggle="tooltip" data-string="{{ $konsultasi->id }}" data-status="Proses Revisi" data-bs-trigger="hover" onclick="openKonsultasiForm(this)">
                                        <i class="bi bi-pencil-square fs-4 me-2"></i>
                                        Buka Kembali Konsultasi</button>
                                    <!--end::open-->
                                @else
                                    <!--begin::done-->
                                    <button type="button" class="btn btn-primary w-100" title="Klik untuk mengakhiri sesi konsultasi" data-bs-toggle="tooltip"  data-string="{{ $konsultasi->id }}" data-status="Selesai" data-bs-trigger="hover" onclick="doneKonsultasiForm(this)">
                                        <i class="bi bi-check2-square fs-4 me-2"></i>
                                        Akhiri Konsultasi</button>
                                    <!--end::done-->
                                @endif
                            </div>
                            <!--end::Card body-->
                        </div>
                        <!--end::Status-->
                    @endif
                </div>
                <!--end::Aside column-->
                <!--begin::Main column-->
                <div class="d-flex flex-column flex-row-fluid gap-7 gap-lg-10">
                    <!--begin::General options-->
                    <div class="card card-flush py-4 w-100">
                        <!--begin::Card header-->
                        <div class="card-header">
                            <div class="card-title">
                                <h2>Ruang Konsultasi</h2>
                            </div>
                        </div>
                        <!--end::Card header-->

                        @php
                            $activeRole = session('defaultRoleCode'); // contoh
                        @endphp

                        <!--begin::Card body-->
                        <div class="card-body border-top border-bottom" id="chat_konsultasi_body">
                            <!--begin::Messages-->
                            <div class="scroll-y me-n5 pe-5" data-kt-element="messages" data-kt-scroll="true" data-kt-scroll-activate="true" data-kt-scroll-height="auto" data-kt-scroll-dependencies="#kt_drawer_chat_messenger_header, #chat_konsultasi_footer" data-kt-scroll-wrappers="#chat_konsultasi_body" data-kt-scroll-offset="0px">
                                @if ($konsultasi_chatting)
                                @foreach ($konsultasi_chatting as $chat)
                                    @php
                                        $isOutgoing = ($activeRole == 'opd' && $chat->role_code == 'opd') 
                                                    || ($activeRole == 'admin' && $chat->role_code == 'admin');
                                    @endphp

                                    @if($isOutgoing)
                                        {{-- MESSAGE OUT --}}
                                        <div class="d-flex justify-content-end mb-10">
                                            <div class="d-flex flex-column align-items-end">
                                                <div class="d-flex align-items-center mb-2">
                                                    <div class="me-3">
                                                        <span class="text-muted fs-7 mb-1">{{ \Carbon\Carbon::parse($chat->created_at)->translatedFormat('d M Y H:i') }}</span>
                                                        <a class="fs-5 fw-bold text-gray-900 text-hover-primary ms-1">{{ $chat->created_by }}</a>
                                                    </div>
                                                    <div class="symbol symbol-35px symbol-circle">
                                                        <i class="bi bi-person-circle fs-5"></i>
                                                    </div>
                                                </div>

                                                @if($chat->message_konsultasi)
                                                <div class="p-5 rounded bg-light-primary text-dark fw-semibold mw-lg-400px">
                                                     {!! nl2br(e($chat->message_konsultasi)) !!}
                                                </div>
                                                @endif

                                                @if($chat->filename_konsultasi)
                                                    <div class="mt-2 p-5 rounded bg-light-primary text-dark fw-semibold mw-lg-400px">
                                                        <a href="{{ route('file.view', ['filepath' => encrypt($chat->filepath_konsultasi)]) }}" target="_blank" class="d-flex align-items-center">
                                                            <i class="bi bi-file-earmark-pdf fs-2x me-3"></i>
                                                            <div class="d-flex flex-column">
                                                                <span class="fw-bold text-dark">{{ $chat->filename_konsultasi }}</span>
                                                                <span class="text-muted fs-8">{{ $chat->file_size }}</span>
                                                            </div>
                                                        </a>
                                                    </div>
                                                @endif
                                            </div>
                                        </div>

                                    @else
                                        {{-- MESSAGE IN --}}
                                        <div class="d-flex justify-content-start mb-10">
                                            <div class="d-flex flex-column align-items-start">

                                                <div class="d-flex align-items-center mb-2">
                                                    <div class="symbol symbol-35px symbol-circle">
                                                        <i class="bi bi-person-circle fs-5"></i>
                                                    </div>
                                                    <div class="ms-3">
                                                        <span class="fs-5 fw-bold text-gray-900 text-hover-primary me-1">{{ $chat->created_by }}</span>
                                                        <span class="text-muted fs-7 mb-1">{{ \Carbon\Carbon::parse($chat->created_at)->translatedFormat('d M Y H:i') }}</span>
                                                    </div>
                                                </div>

                                                @if($chat->message_konsultasi)
                                                <div class="p-5 rounded bg-light-info text-dark fw-semibold mw-lg-400px text-start">
                                                     {!! nl2br(e($chat->message_konsultasi)) !!}
                                                </div>
                                                @endif

                                                @if($chat->filename_konsultasi)
                                                    <div class="mt-2 p-5 rounded bg-light-info text-dark fw-semibold mw-lg-400px text-start">
                                                        <a href="{{ route('file.view', ['filepath' => encrypt($chat->filepath_konsultasi)]) }}" target="_blank" class="d-flex align-items-center">
                                                            <i class="bi bi-file-earmark-pdf fs-2x me-3"></i>
                                                            <div class="d-flex flex-column">
                                                                <span class="fw-bold text-dark">{{ $chat->filename_konsultasi }}</span>
                                                                <span class="text-muted fs-8">{{ $chat->file_size }}</span>
                                                            </div>
                                                        </a>
                                                    </div>
                                                @endif

                                            </div>
                                        </div>
                                    @endif
                                @endforeach
                                @endif
                                
                                {{-- <!--begin::Message(in)-->
                                <div class="d-flex justify-content-start mb-10">
                                    <!--begin::Wrapper-->
                                    <div class="d-flex flex-column align-items-start">
                                        <!--begin::User-->
                                        <div class="d-flex align-items-center mb-2">
                                            <!--begin::Avatar-->
                                            <div class="symbol symbol-35px symbol-circle">
                                                <i class="bi bi-person-circle fs-5"></i>
                                            </div>
                                            <!--end::Avatar-->
                                            <!--begin::Details-->
                                            <div class="ms-3">
                                                <span class="fs-5 fw-bold text-gray-900 text-hover-primary me-1">NI MADE YULI CAHYANI</span>
                                                <span class="text-muted fs-7 mb-1">2 Nov 2025 07:45</span>
                                            </div>
                                            <!--end::Details-->
                                        </div>
                                        <!--end::User-->
                                        <!--begin::Text-->
                                        <div class="p-5 rounded bg-light-info text-dark fw-semibold mw-lg-400px text-start" data-kt-element="message-text">How likely are you to recommend our company to your friends and family ?</div>
                                        <!--end::Text-->
                                        <!--begin::File-->
                                        <div class="mt-2 p-5 rounded bg-light-info text-dark fw-semibold mw-lg-400px text-start" data-kt-element="message-text">
                                            <a href="#" target="_blank" class="d-flex align-items-center">
                                                <i class="bi bi-file-earmark-pdf fs-2x me-3"></i>

                                                <div class="d-flex flex-column">
                                                    <span class="fw-bold text-dark">{{ $file_name ?? 'Document.pdf' }}</span>
                                                    <span class="text-muted fs-8">{{ $file_size ?? '250 KB' }}</span>
                                                </div>
                                            </a>
                                        </div>
                                        <!--begin::File-->
                                    </div>
                                    <!--end::Wrapper-->
                                </div>
                                <!--end::Message(in)-->

                                <!--begin::Message(out)-->
                                <div class="d-flex justify-content-end mb-10">
                                    <!--begin::Wrapper-->
                                    <div class="d-flex flex-column align-items-end">
                                        <!--begin::User-->
                                        <div class="d-flex align-items-center mb-2">
                                            <!--begin::Details-->
                                            <div class="me-3">
                                                <span class="text-muted fs-7 mb-1">2 Nov 2025 08.00</span>
                                                <a href="#" class="fs-5 fw-bold text-gray-900 text-hover-primary ms-1">IDA BAGUS PURWANIA</a>
                                            </div>
                                            <!--end::Details-->
                                            <!--begin::Avatar-->
                                            <div class="symbol symbol-35px symbol-circle">
                                                <i class="bi bi-person-circle fs-5"></i>
                                            </div>
                                            <!--end::Avatar-->
                                        </div>
                                        <!--end::User-->
                                        <!--begin::Text-->
                                        <div class="p-5 rounded bg-light-primary text-dark fw-semibold mw-lg-400px text-end" data-kt-element="message-text">Hey there, we’re just writing to let you know that you’ve been subscribed to a repository on GitHub.</div>
                                        <!--end::Text-->
                                        <!--begin::File-->
                                        <div class="mt-2 p-5 rounded bg-light-primary text-dark fw-semibold mw-lg-400px text-end" data-kt-element="message-text">
                                            <a href="#" target="_blank" class="d-flex align-items-center">
                                                <i class="bi bi-file-earmark-pdf fs-2x me-3"></i>

                                                <div class="d-flex flex-column">
                                                    <span class="fw-bold text-dark">{{ $file_name ?? 'Document.pdf' }}</span>
                                                    <span class="text-muted fs-8">{{ $file_size ?? '250 KB' }}</span>
                                                </div>
                                            </a>
                                        </div>
                                        <!--begin::File-->
                                    </div>
                                    <!--end::Wrapper-->
                                </div>
                                <!--end::Message(out)-->

                                <!--begin::Message(out)-->
                                <div class="d-flex justify-content-end mb-10">
                                    <!--begin::Wrapper-->
                                    <div class="d-flex flex-column align-items-end">
                                        <!--begin::User-->
                                        <div class="d-flex align-items-center mb-2">
                                            <!--begin::Details-->
                                            <div class="me-3">
                                                <span class="text-muted fs-7 mb-1">2 Nov 2025 08.15</span>
                                                <a href="#" class="fs-5 fw-bold text-gray-900 text-hover-primary ms-1">IDA BAGUS PURWANIA</a>
                                            </div>
                                            <!--end::Details-->
                                            <!--begin::Avatar-->
                                            <div class="symbol symbol-35px symbol-circle">
                                                <i class="bi bi-person-circle fs-5"></i>
                                            </div>
                                            <!--end::Avatar-->
                                        </div>
                                        <!--end::User-->
                                        <!--begin::Text-->
                                        <div class="p-5 rounded bg-light-primary text-dark fw-semibold mw-lg-400px text-end" data-kt-element="message-text">Hey there, we’re just writing to let you know that you’ve been subscribed to a repository on GitHub.</div>
                                        <!--end::Text-->
                                        <!--begin::File-->
                                        <div class="mt-2 p-5 rounded bg-light-primary text-dark fw-semibold mw-lg-400px text-end" data-kt-element="message-text">
                                            <a href="#" target="_blank" class="d-flex align-items-center">
                                                <i class="bi bi-file-earmark-pdf fs-2x me-3"></i>

                                                <div class="d-flex flex-column">
                                                    <span class="fw-bold text-dark">{{ $file_name ?? 'Document.pdf' }}</span>
                                                    <span class="text-muted fs-8">{{ $file_size ?? '250 KB' }}</span>
                                                </div>
                                            </a>
                                        </div>
                                        <!--begin::File-->
                                    </div>
                                    <!--end::Wrapper-->
                                </div>
                                <!--end::Message(out)-->

                                <!--begin::Message(in)-->
                                <div class="d-flex justify-content-start mb-10">
                                    <!--begin::Wrapper-->
                                    <div class="d-flex flex-column align-items-start">
                                        <!--begin::User-->
                                        <div class="d-flex align-items-center mb-2">
                                            <!--begin::Avatar-->
                                            <div class="symbol symbol-35px symbol-circle">
                                                <i class="bi bi-person-circle fs-5"></i>
                                            </div>
                                            <!--end::Avatar-->
                                            <!--begin::Details-->
                                            <div class="ms-3">
                                                <span class="fs-5 fw-bold text-gray-900 text-hover-primary me-1">NI MADE YULI CAHYANI</span>
                                                <span class="text-muted fs-7 mb-1">2 Nov 2025 08.30</span>
                                            </div>
                                            <!--end::Details-->
                                        </div>
                                        <!--end::User-->
                                        <!--begin::Text-->
                                        <div class="p-5 rounded bg-light-info text-dark fw-semibold mw-lg-400px text-start" data-kt-element="message-text">How likely are you to recommend our company to your friends and family ?</div>
                                        <!--end::Text-->
                                        <!--begin::File-->
                                        <div class="mt-2 p-5 rounded bg-light-info text-dark fw-semibold mw-lg-400px text-start" data-kt-element="message-text">
                                            <a href="#" target="_blank" class="d-flex align-items-center">
                                                <i class="bi bi-file-earmark-pdf fs-2x me-3"></i>

                                                <div class="d-flex flex-column">
                                                    <span class="fw-bold text-dark">{{ $file_name ?? 'Document.pdf' }}</span>
                                                    <span class="text-muted fs-8">{{ $file_size ?? '250 KB' }}</span>
                                                </div>
                                            </a>
                                        </div>
                                        <!--begin::File-->
                                    </div>
                                    <!--end::Wrapper-->
                                </div>
                                <!--end::Message(in)-->

                                <!--begin::Message(in)-->
                                <div class="d-flex justify-content-start mb-10">
                                    <!--begin::Wrapper-->
                                    <div class="d-flex flex-column align-items-start">
                                        <!--begin::User-->
                                        <div class="d-flex align-items-center mb-2">
                                            <!--begin::Avatar-->
                                            <div class="symbol symbol-35px symbol-circle">
                                                <i class="bi bi-person-circle fs-5"></i>
                                            </div>
                                            <!--end::Avatar-->
                                            <!--begin::Details-->
                                            <div class="ms-3">
                                                <span class="fs-5 fw-bold text-gray-900 text-hover-primary me-1">NI MADE YULI CAHYANI</span>
                                                <span class="text-muted fs-7 mb-1">2 Nov 2025 08.35</span>
                                            </div>
                                            <!--end::Details-->
                                        </div>
                                        <!--end::User-->
                                        <!--begin::Text-->
                                        <div class="p-5 rounded bg-light-info text-dark fw-semibold mw-lg-400px text-start" data-kt-element="message-text">How likely are you to recommend our company to your friends and family ?</div>
                                        <!--end::Text-->
                                        <!--begin::File-->
                                        <div class="mt-2 p-5 rounded bg-light-info text-dark fw-semibold mw-lg-400px text-start" data-kt-element="message-text">
                                            <a href="#" target="_blank" class="d-flex align-items-center">
                                                <i class="bi bi-file-earmark-pdf fs-2x me-3"></i>

                                                <div class="d-flex flex-column">
                                                    <span class="fw-bold text-dark">{{ $file_name ?? 'Document.pdf' }}</span>
                                                    <span class="text-muted fs-8">{{ $file_size ?? '250 KB' }}</span>
                                                </div>
                                            </a>
                                        </div>
                                        <!--begin::File-->
                                    </div>
                                    <!--end::Wrapper-->
                                </div>
                                <!--end::Message(in)--> --}}
                                
                            </div>
                            <!--end::Messages-->
                        </div>
                        <!--end::Card body-->
                        <!--begin::Card footer-->
                        <div class="card-footer pt-4" id="chat_konsultasi_footer">
                            <!-- File preview container -->
                            <div id="file_preview_box" class="d-none mt-2">
                                <div class="d-flex align-items-center px-3 py-2 rounded bg-light">
                                    
                                    <div class="flex-grow-1 fs-7">
                                        <span id="file_name" class="fw-semibold text-gray-800"></span>
                                        <span id="file_size" class="text-muted"></span>
                                    </div>

                                    <!-- Small progress bar beside remove button -->
                                    <div class="progress me-3" style="width: 100px; height: 6px;">
                                        <div id="file_progress" class="progress-bar" role="progressbar"></div>
                                    </div>

                                    <!-- Remove file button -->
                                    <button id="file_remove_btn" type="button" class="btn btn-sm p-1">
                                        <i class="bi bi-x-lg fs-7"></i>
                                    </button>
                                </div>
                            </div>

                            <!--begin::Input-->
                            <textarea id="message_konsultasi" name="message_konsultasi" class="form-control form-control-flush mb-3" rows="3" data-kt-element="input" placeholder="Type a message"></textarea>
                            <!--end::Input-->
                            <!--begin:Toolbar-->
                            <div class="d-flex flex-stack">
                                <!--begin::Actions-->
                                <!--end::Upload attachement-->
                                <div class="d-flex align-items-center me-2">
                                    <button id="btn_attach" class="btn btn-sm btn-icon btn-active-light-primary me-1" type="button" data-bs-toggle="tooltip"  data-bs-trigger="hover" title="Klik untuk menambahkan file anda">
                                        <i class="bi bi-paperclip fs-3"></i>
                                    </button>

                                    <!-- Hidden File Input -->
                                    <input type="file" id="file_konsultasi" name="file_konsultasi" class="d-none" accept="application/pdf">
                                    <span id='file_konsultasi-error' class='error' for='file_konsultasi'></span>

                                </div>
                                <!--end::Upload attachement-->
                                <!--end::Actions-->
                                <!--begin::Send-->
                                <button class="btn btn-primary" type="submit" data-kt-element="send"
                                    @if(isset($konsultasi) && $konsultasi->status == 'Selesai') disabled @endif>
                                    <span class="indicator-label"><i class="bi bi-send-fill me-2"></i>Send</span>
                                    <span class="indicator-progress">Please wait...
									<span class="spinner-border spinner-border-sm align-middle ms-2"></span></span>
                                </button>
                                <!--end::Send-->
                            </div>
                            <!--end::Toolbar-->
                        </div>
                        <!--end::Card footer-->
                    </div>
                    <!--end::General options-->
                </div>
                <!--end::Main column-->
            </form>
        </div>
        <!--end::Post-->

    </div>
    <!--end::Post-->
</div>
<!--end::Container-->

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
        function formatFileSize(bytes) {
            const sizes = ['Bytes', 'KB', 'MB', 'GB'];
            if (bytes === 0) return '0 Byte';
            const i = parseInt(Math.floor(Math.log(bytes) / Math.log(1024)));
            return Math.round(bytes / Math.pow(1024, i)) + ' ' + sizes[i];
        }

        $(document).ready(function(){

            document.getElementById('file_konsultasi').addEventListener('change', function (e) {
                const file = e.target.files[0];
                if (!file) return;

                if(file.size > 2097152){
                    document.getElementById('file_progress').classList.add('bg-danger');
                    document.getElementById('file_progress').classList.remove('bg-primary');
                } else {
                    document.getElementById('file_progress').classList.add('bg-primary');
                    document.getElementById('file_progress').classList.remove('bg-danger');
                };

                // Show filename & size
                document.getElementById('file_name').innerText = file.name;
                document.getElementById('file_size').innerText = ` (${formatFileSize(file.size)})`;

                // Show preview box
                document.getElementById('file_preview_box').classList.remove('d-none');

                // Dummy progress animate (optional)
                let progress = 0;
                const interval = setInterval(() => {
                    progress += 10;
                    document.getElementById('file_progress').style.width = progress + '%';
                    if (progress >= 100) clearInterval(interval);
                }, 100);
            });

            // Remove file preview
            document.getElementById('file_remove_btn').addEventListener('click', () => {
                document.getElementById('file_konsultasi').value = "";
                document.getElementById('file_preview_box').classList.add('d-none');
            });

            document.getElementById('btn_attach').addEventListener('click', function () {
                document.getElementById('file_konsultasi').click();
            });

        });

    </script>

    <script>

        $(document).ready(function(){

            $('#unit_code_select').select2({
                placeholder: 'Pilih Unit/PD',
            });

            // Tambah method cek ukuran file
            jQuery.validator.addMethod("filesize", function (value, element, param) {
                if (element.files.length === 0) {
                    return true; // biar tidak error kalau kosong (required sudah handle)
                }
                return element.files[0].size <= param;
            }, "Ukuran file terlalu besar.");

            $('#konsultasi_form').validate({
                errorElement: "div",
                validElement: "div",
                errorClass: "text-danger mb-1 col-12",
                validClass: "text-body",
                ignore: [],
                rules: {
                    nama_sop: {
                        required: true
                    },
                    unit_code: {
                        required: true,
                    },
                    user_id: {
                        required: true
                    },
                    created_by: {
                        required: true
                    },
                    created_at: {
                        required: true
                    },
                    file_konsultasi: {
                        // required: true,
                        extension: "pdf",   // hanya pdf
                        filesize: 2097152,  // 2 MB (dalam byte)
                    }
                },
                messages: {
                    nama_sop: {
                        required: 'Nama SOP yang dikonsultasikan harus diisi'
                    },
                    unit_code: {
                        required: 'Unit/PD harus dipilih'
                    },
                    user_id: {
                        required: 'ID User harus diisi'
                    },
                    created_by: {
                        required: 'Pengguna yang melakukan konsultasi harus diisi'
                    },
                    created_at: {
                        required: 'Tanggak konsultasi dibuat harus diisi'
                    },
                    file_konsultasi: {
                        // required: 'File SOP harus diisi',
                        extension: 'File harus berformat PDF',
                        filesize: 'Ukuran file maksimal 2 MB'
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
                    // Swal.fire({
                    //     title: 'Silakan tunggu, permintaan sedang diproses',
                    //     showConfirmButton: false,
                    //     timerProgressBar: true,
                    //     allowOutsideClick: false,
                    //     allowEscapeKey: false,
                    //     heightAuto: false,
                    //     didOpen: () => {
                    //         Swal.showLoading()
                    //     },
                    // });
                }
            });
        });

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
    </script>

@endsection