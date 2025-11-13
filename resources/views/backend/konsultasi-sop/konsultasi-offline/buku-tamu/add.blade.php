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

            @if (session('UserIsAuthenticated') == 1)
                {{-- Jika user sudah login --}}
                <a href="{{route('konsultasi-sop-offline.buku-tamu.index')}}" data-theme="light" class="btn btn-bg-white btn-active-color-primary">
                    <i class="bi bi-arrow-left-circle fs-4"></i>
                    Kembali
                </a>
            @else
                {{-- Jika user belum login --}}
                <a href="{{route('/')}}" data-theme="light" class="btn btn-bg-white btn-active-color-primary">
                    <i class="bi bi-arrow-left-circle fs-4"></i>
                    Kembali
                </a>
            @endif

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
            <form id="form_store_data_tamu" action="{{ route('konsultasi-sop-offline.buku-tamu.store') }}" enctype="multipart/form-data" method="POST" autocomplete="off">
            <!--begin::Card-->
            <div class="card">
                <!--begin::Card header-->
                <div class="card-header border-0 pt-6">
                    <!--begin::Card title-->
                    <div class="card-title">
                        <!--begin::Count-->
                        <p class="d-flex fw-bold my-1">Form Buku Tamu</p>
                        <!--end::Count-->
                    </div>
                    <!--begin::Card title-->
                </div>
                <!--end::Card header-->
                <!--begin::Card body-->
                <div class="card-body pt-0">
                    
                    @csrf
                    <table class="" id="tblempinfo">
                        <tbody>
                            <div class="row col-md-12">

                                <div class='form-group mb-5'>
                                    <label for='tgl_konsultasi' class="required">Tanggal</label>
                                    <input type="text" class="form-control" placeholder="Tanggal Konsultasi" id="tgl_konsultasi"
                                                name="tgl_konsultasi" value="" />
                                    <span id='tgl_konsultasi-error' class='error' for='tgl_konsultasi'></span>
                                </div>
                                <div class='form-group mb-5'>
                                    <label for='kegiatan_konsultasi' class="required">Kegiatan</label>
                                    <textarea type="text" class="form-control" rows="2" placeholder="Kegiatan Konsultasi" id="kegiatan_konsultasi"
                                                name="kegiatan_konsultasi" required></textarea>
                                    <span id='kegiatan_konsultasi-error' class='error' for='kegiatan_konsultasi'></span>
                                </div>
                                <div class='form-group mb-5'>
                                    <label for='unit_code' class="required">Unit/PD</label>
                                    <select id="unit_code" name="unit_code" class='form-control form-select has-input-group' style="width: 100%;" data-control="select2" required >
                                        <option value="">Pilih Unit/PD</option>
                                        @foreach($units as $unit)
                                            <option value="{{$unit->code}}">{{$unit->name}}</option>
                                        @endforeach
                                    </select>
                                    <span id='unit_code-error' class='error' for='unit_code'></span>
                                </div>
                                <div class='form-group mb-5'>
                                    <label for='nama' class="required">Nama</label>
                                    <input type="text" class="form-control" placeholder="Nama" id="nama"
                                                name="nama" value="" required/>
                                    <span id='nama-error' class='error' for='nama'></span>
                                </div>
                                <div class='form-group mb-5'>
                                    <label for='jabatan' class="required">Jabatan</label>
                                    <input type="text" class="form-control" placeholder="Jabatan" id="jabatan"
                                                name="jabatan" value="" required/>
                                    <span id='jabatan-error' class='error' for='jabatan'></span>
                                </div>

                                <div class='form-group mb-5'>
                                    <label for='signature' class="required">TTD/Paraf</label>
                                    <!-- Signature Canvas -->
                                    <div class="mb-3">
                                        <canvas id="signatureCanvas" class="border border-2 rounded w-100" 
                                                style="background-color:#fff; height:200px; max-width:600px;"></canvas>
                                    </div>

                                    <!-- Buttons -->
                                    <div class=" mb-8">
                                        <button type="button" id="clearBtn" class="btn btn-danger btn-sm">
                                        <i class="bi bi-x-circle"></i> Hapus TTD/Paraf
                                        </button>
                                    </div>


                                    <input type="hidden" name="signature" id="signature">
                                    <span id='signature-error' class='error' for='signature'></span>
                                </div>
                            </div>
                        </tbody>
                    </table>
                    
                </div>
                <!--end::Card body-->

                <div class="card-footer d-flex justify-content-between border-0">
                    @if (session('UserIsAuthenticated') == 1)
                    <a href="{{route('konsultasi-sop-offline.buku-tamu.index')}}" data-theme="light" class="btn btn-light btn-active-light-primary">
                        <i class="bi bi-arrow-left-circle fs-5"></i>
                        Kembali
                    </a>
                    @else
                    <a href="{{route('/')}}" data-theme="light" class="btn btn-light btn-active-light-primary">
                        <i class="bi bi-arrow-left-circle fs-5"></i>
                        Kembali
                    </a>
                    @endif
                    <button type="submit" class="btn btn-primary ms-5"><i class="fa-solid fa-floppy-disk fs-5 me-2"></i>Simpan</button>
                </div>
            </div>
            <!--end::Card-->
            </form>
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
    <!-- Signature Pad library -->
    <script src="https://cdn.jsdelivr.net/npm/signature_pad@4.1.6/dist/signature_pad.umd.min.js"></script>

    <script>
        // document.addEventListener("DOMContentLoaded", function () {
            const canvas = document.getElementById('signatureCanvas');
            const signaturePad = new SignaturePad(canvas, {
                backgroundColor: 'white',
                penColor: '#000000'
            });

            // Adjust canvas resolution for responsiveness
            function resizeCanvas() {
                const ratio = Math.max(window.devicePixelRatio || 1, 1);
                const rect = canvas.getBoundingClientRect();
                canvas.width = rect.width * ratio;
                canvas.height = rect.height * ratio;
                canvas.getContext('2d').scale(ratio, ratio);
                signaturePad.clear();
            }

            window.addEventListener('resize', resizeCanvas);
            resizeCanvas();

            // Clear signature
            document.getElementById('clearBtn').addEventListener('click', function () {
                signaturePad.clear();
                resizeCanvas();
            });
        // });
    </script>

    <script>
        $(document).ready(function(){
            $('#tgl_konsultasi').flatpickr({
                altInput: true,
                altFormat: "j F Y",   // tampil: 3 Oktober 2025
                dateFormat: "Y-m-d",  // kirim ke server: 2025-10-03
                locale: "id",          // Bahasa Indonesia
                disableMobile: true,
            });

            $('#unit_code').select2({
                placeholder: 'Pilih Unit/PD',
            });

            $('#unit_code').on('change', function () {
                $(this).valid();
            });

            $('#tgl_konsultasi').on('change', function () {
                $(this).valid();
            });


            $('#form_store_data_tamu').validate({
                errorElement: "div",
                validElement: "div",
                errorClass: "text-danger mb-1 col-12",
                validClass: "text-body",
                ignore: [],
                rules: {
                    kegiatan_konsultasi: {
                        required: true
                    },
                    tgl_konsultasi: {
                        required: true,
                    },
                    unit_code: {
                        required: true
                    },
                    nama: {
                        required: true
                    },
                    jabatan: {
                        required: true
                    },
                },
                messages: {
                    kegiatan_konsultasi: {
                        required: 'Kegiatan konsultasi harus diisi'
                    },
                    tgl_konsultasi: {
                        required: 'Tanggal konsultasi harus diisi'
                    },
                    unit_code: {
                        required: 'Unit/PD harus dipilih'
                    },
                    nama: {
                        required: 'Nama penerima konsultasi harus diisi'
                    },
                    jabatan: {
                        required: 'Jabatan penerima konsultasi harus diisi'
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
                     // Cek tanda tangan
                    if (signaturePad.isEmpty()) {
                        Swal.fire({
                            icon: 'warning',
                            title: 'TTD atau Paraf belum diisi',
                            text: 'Silakan buat tanda tangan/paraf terlebih dahulu.',
                            confirmButtonColor: '#388da8',
                        });
                        return false; // stop submit
                    }

                    // Simpan gambar tanda tangan
                    const data = signaturePad.toDataURL('image/png');
                    $('#signature').val(data);

                     // console
                    // const formData = new FormData(form);
                    // console.log(Object.fromEntries(formData)); 

                    Swal.fire({
                        title: 'Silakan tunggu...',
                        text: 'Data sedang dikirim.',
                        showConfirmButton: false,
                        timerProgressBar: true,
                        allowOutsideClick: false,
                        allowEscapeKey: false,
                        didOpen: () => Swal.showLoading(),
                    });

                    form.submit(); // submit normal ke server
                }
            });
        });


    </script>

@endsection