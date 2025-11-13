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

        .question-card {
        background: #fff;
        border-radius: 12px;
        padding: 20px;
        margin-bottom: 20px;
        box-shadow: 0 3px 10px #d4d5d8;

        /* 💛 border dashed */
        border: 2px dashed #e4e6ef;
        transition: all 0.3s ease;
        }

        .question-text { font-weight: 500; color: #343a40; margin-bottom: 15px;  font-size: 15px;}
        .emoji-container { display: flex; gap: 40px; justify-content: center; }

        /* Default abu-abu */
        .lottie-box {
        width: 60px; height: 60px;
        border-radius: 50%;
        background: #e9ecef;
        border: 2px solid #ced4da;
        display: flex; justify-content: center; align-items: center;
        cursor: pointer; transition: all 0.3s ease;
        filter: grayscale(100%); /* animasi tampil abu-abu */
        }

        /* Saat di-hover */
        .lottie-box:hover {
        transform: scale(1.05);
        border-color: #adb5bd;
        }

        /* Saat aktif (dipilih): berwarna kuning cerah */
        .lottie-box.active {
        background: #fff3cd;
        border-color: #ffc107;
        filter: none; 
        box-shadow: 0 0 10px rgba(255,193,7,0.5);
        }

        .lottie-box.active.yes {
            border-color: #2898a7;
            border-color: #c5ecf1;
            filter: none; 
            box-shadow: 0 0 10px #2898a7;
        }

        .lottie-box.active.no {
            border-color: #d56e7e;
            background-color: #f8e5e5;
            filter: none; 
            box-shadow: 0 0 10px #dc354e;
        }

        /* Label teks bawah emoji */
        .emoji-label {
        margin-top: 6px;
        font-weight: 600;
        font-size: 0.9rem;
        color: #6c757d;
        text-align: center;
        }

        .lottie-box.active + .emoji-label {
        color: #000000;
        }

        /* Catatan */
        .note-input {
        margin-top: 5px;
        width: 100%;
        border-radius: 8px;
        border: 1px solid #ced4da;
        padding: 8px 10px;
        font-size: 15px;
        color: #495057;
        resize: vertical;
        min-height: 60px;
        transition: border-color 0.3s ease;
        }

        .note-input:focus {
        outline: none;
        border-color: #6a6a6a;
        }

        /* Tindakan */
        .action-input {
        margin-top: 5px;
        width: 100%;
        border-radius: 8px;
        border: 1px solid #ced4da;
        padding: 8px 10px;
        font-size: 15px;
        color: #495057;
        resize: vertical;
        min-height: 60px;
        transition: border-color 0.3s ease;
        }

        .action-input:focus {
        outline: none;
        border-color: #6a6a6a;
        }

        @media (max-width: 576px) {
        .emoji-container { gap: 25px; }
        .lottie-box { width: 50px; height: 50px; }
        .note-input { font-size: 0.85rem; }
        .action-input { font-size: 0.85rem; }
        }

        #informasi_umum_table {
            border-collapse: collapse; /* rapatkan antar kolom */
        }

        #informasi_umum_table td {
            padding: 6px 8px; /* kecilkan padding */
        }

        #informasi_umum_table td.colon {
            width: 10px;               /* sempitkan kolom */
            text-align: center;
            padding: 0;                /* hilangkan padding default */
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
            <a href="{{route('monev-sop.sop.form-monev.index', encrypt($sop->id))}}" data-theme="light" class="btn btn-bg-white btn-active-color-primary">
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

            <form id="surveyForm" action="{{ route('monev-sop.sop.form-monev.f02.store') }}" enctype="multipart/form-data" method="POST" autocomplete="off">
                @csrf
            <!--begin::Card-->
            <div class="card mb-5">
                <!--begin::Card header-->
                <div class="card-header border-0 pt-6">
                    <!--begin::Card title-->
                    <div class="card-title">
                        <h3 class="fw-bold my-1"> A. Informasi Umum</h3>
                    </div>
                    <!--begin::Card title-->
                </div>
                <!--end::Card header-->
                <!--begin::Card body-->
                <div class="card-body pt-0 table-responsive border-top"  style="overflow-x: auto; overflow-y: hidden;">
                    <!--begin::Table-->
                    <table class="table align-middle table-row-dashed fs-6 gy-5" id="informasi_umum_table">
                        <tbody class="fw-semibold text-gray-700">
                            <!-- Hidden Input -->
                            <input type="hidden" name="result_monev_id" value="{{ $result_monev->id ?? '' }}">
                            <input type="hidden" name="sop_id" value="{{ $sop->id }}">

                            <tr>
                                <td class="">Nama SOP</td>
                                <td class="colon">:</td>
                                <td class="">
                                    <input type="text"  id="nama_sop" name="nama_sop" class="form-control" 
                                        placeholder="Masukkan nama SOP..." value="{{ $sop->nama }}" required>
                                </td>
                            </tr>

                            <tr>
                                <td>Nomor SOP</td>
                                <td class="colon">:</td>
                                <td>
                                    <input type="text" id="nomor_sop" name="nomor_sop" class="form-control" 
                                        placeholder="Masukkan nomor SOP..." value="{{ $sop->nomor }}" required>
                                </td>
                            </tr>

                            <tr>
                                <td>File SOP</td>
                                <td class="colon">:</td>
                                <td>
                                    <a href="{{route('file.view', ['filepath' => encrypt($sop->filepath)])}}" target="_blank">
                                        <button type="button" class="btn btn-sm btn-icon btn-primary text-center" title="Klik untuk mengunduh file SOP" data-bs-toggle="tooltip" data-bs-trigger="hover">
                                                <i class="bi bi-file-earmark-arrow-down-fill fs-2"></i></button>
                                    </a>
                                </td>
                            </tr>

                            <tr>
                                <td>Unit/OPD</td>
                                <td class="colon">:</td>
                                <td>
                                    <select id="unit_code" name="unit_code" class='form-control form-select has-input-group' style="width: 100%;" data-control="select2" required >
                                        <option value="">Pilih Unit</option>
                                        @foreach($units as $u)
                                            @php
                                                $opsiSelect = $u->code == $sop->unit_code ? 'selected' : '';
                                            @endphp
                                            <option value="{{$u->code}}" {{ $opsiSelect }}>{{$u->name}}</option>
                                        @endforeach
                                    </select>
                                </td>
                            </tr>

                            <tr>
                                <td>Tanggal Pengisian Monev</td>
                                <td class="colon">:</td>
                                <td>
                                    <input type="date" id="tanggal_pengisian" name="tanggal_pengisian" class="form-control" placeholder="Masukkan tanggal pengisian monev..." required>
                                </td>
                            </tr>

                            <tr>
                                <td>Periode Monev</td>
                                <td class="colon">:</td>
                                <td>
                                    <select id="periode_monev" name="periode_monev" class='form-control form-select has-input-group' style="width: 100%;" data-control="select2" required >
                                        <option value="">Pilih Periode Monev</option>
                                        @foreach($periodes as $p)
                                            @php
                                             // Tentukan nilai periode_id
                                                if (isset($result_monev) && !empty($result_monev->periode_monev_id)) {
                                                    $periode_id = $result_monev->periode_monev_id;
                                                } else {
                                                    $periode_id = $active_periode->id;
                                                }
                                                $opsiSelect = $p->id == $periode_id ? 'selected' : '';
                                            @endphp
                                            <option value="{{$p->id}}" {{ $opsiSelect }}>{{$p->periode_year}}</option>
                                        @endforeach
                                    </select>
                                </td>
                            </tr>

                            {{-- <tr>
                                <td>Nama Pelaksana SOP</td>
                                <td class="colon">:</td>
                                <td>
                                    <input type="text" name="nama_pelaksana" class="form-control" 
                                        placeholder="Masukkan nama pelaksana SOP..." value="{{ isset($result_monev) && !empty($result_monev->nama_pelaksana_sop) ? $result_monev->nama_pelaksana_sop :  session('nama')}}" required>
                                </td>
                            </tr> --}}

                            <tr>
                                <td>Nama Evaluator / Auditor SOP</td>
                                <td class="colon">:</td>
                                <td>
                                    <input type="text" name="nama_evaluator" class="form-control" 
                                        placeholder="Masukkan nama evaluator atau auditor..." value="{{ isset($result_monev) && !empty($result_monev->nama_evaluator_sop) ? $result_monev->nama_evaluator_sop :  session('nama')}}" required>
                                </td>
                            </tr>
                        </tbody>
                    </table>

                    <!--end::Table-->
                </div>
                <!--end::Card body-->
            </div>
            <!--end::Card-->
            
            <!--begin::Card-->
            <div class="card mb-5">
                <!--begin::Card header-->
                <div class="card-header border-0 pt-6">
                    <!--begin::Card title-->
                    <div class="card-title">
                        <h3 class="fw-bold my-1"> D. Evaluasi Pemenuhan SOP</h3>
                    </div>
                    <!--begin::Card title-->
                </div>
                <!--end::Card header-->
                <!--begin::Card body-->
                <div class="card-body pt-0 border-top" >
                    
                    <div id="firstQuestionCard" class="mt-5"></div>
                </div>
                <!--end::Card body-->
            </div>
            <!--end::Card-->

            <!--begin::Card-->
            <div class="card mb-5">
                <!--begin::Card header-->
                <div class="card-header border-0 pt-6">
                    <!--begin::Card title-->
                    <div class="card-title">
                        <h3 class="fw-bold my-1"> E. Evaluasi Substansi SOP</h3>
                    </div>
                    <!--begin::Card title-->
                </div>
                <!--end::Card header-->
                <!--begin::Card body-->
                <div class="card-body pt-0 border-top" >
                    
                    <div id="otherQuestionsContainer" class="mt-5"></div>
                </div>
                <!--end::Card body-->
            </div>
            <!--end::Card-->

            <!--begin::Card-->
            <div class="card mb-5 text-center">
                <!--begin::Card header-->
                <div class="card-header border-0 pt-6 d-flex justify-content-center">
                    <!--begin::Card title-->
                    <div class="card-title">
                        <h3 class="fw-bold my-1"> Tanda Tangan/Paraf Evaluator SOP</h3>
                    </div>
                    <!--begin::Card title-->
                </div>
                <!--end::Card header-->
                <!--begin::Card body-->
                <div class="card-body pt-5 border-top" >
                    <!-- Signature Canvas -->
                    <div class="mb-3">
                        <canvas id="signatureCanvas" class="border border-2 rounded w-100" 
                                style="background-color:#fff; height:200px; max-width:600px;"></canvas>
                    </div>

                    <!-- Buttons -->
                    @if (session('defaultRoleCode') == 'admin')
                    <div class=" mb-8">
                        <button type="button" id="clearBtn" class="btn btn-danger btn-sm">
                        <i class="bi bi-x-circle"></i> Hapus TTD/Paraf
                        </button>
                    </div>
                    @endif

                    <input type="hidden" name="signature" id="signatureData">

                    @if (session('defaultRoleCode') == 'admin')
                        <button type="submit" class="btn btn-primary px-5">Kirim Jawaban Monev</button>
                    @endif
                    
                </div>
                <!--end::Card body-->
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
    <script src="https://cdnjs.cloudflare.com/ajax/libs/bodymovin/5.12.2/lottie.min.js"></script>
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

            // Example base64 (you’d normally get this from backend or database)
            const base64Signature = "{{isset($result_monev) ? $result_monev->ttd_base64_evaluator_sop : ''}}"; // truncated

            // 🟩 Load base64 image into the signature pad
            if (base64Signature) {
                const img = new Image();
                img.src = base64Signature;
                img.onload = function() {
                    canvas.width = img.width;
                    canvas.height = img.height;
                    const ctx = canvas.getContext('2d');
                    ctx.drawImage(img, 0, 0);

                    // Mark as filled
                    signaturePad._isEmpty = false; 
                };
            }

            // Clear signature
            document.getElementById('clearBtn').addEventListener('click', function () {
                signaturePad.clear();
                resizeCanvas();
            });
        // });
    </script>

    <script>

        $(document).ready(function(){

            $('#unit_code').select2({
                placeholder: 'Pilih Unit',
            });

            $('#unit_code').on('change', function () {
                $(this).valid();
            });

            $('#periode_monev').select2({
                placeholder: 'Pilih Periode Monev',
            });

            $('#periode_monev').on('change', function () {
                $(this).valid();
            });

            const tanggalPengisian = @json(isset($result_monev) && !empty($result_monev->tgl_pengisian_f02) 
            ? $result_monev->tgl_pengisian_f02 
            : 'today');

            $('#tanggal_pengisian').flatpickr({
                altInput: true,
                altFormat: "j F Y",   // tampil: 3 Oktober 2025
                dateFormat: "Y-m-d",  // kirim ke server: 2025-10-03
                locale: "id",          // Bahasa Indonesia
                disableMobile: true,
                defaultDate: tanggalPengisian, 
            });
        });

    
    // 🔹 Data contoh pertanyaan
    // const questions = "{{ $questions }}";
    const questions = @json($questions);

    // 🔹 Jawaban lama user (bisa dari controller Laravel)
    const oldAnswers = @json($oldAnswers);

    const firstContainer = document.getElementById('firstQuestionCard');
    const otherContainer = document.getElementById('otherQuestionsContainer');

    // 🔁 Generate pertanyaan secara dinamis

    const counters = {};
    questions.forEach((q, i) => {
      const qCard = document.createElement('div');
      const old = oldAnswers[q.id] || {};
      counters[q.aspek] = (counters[q.aspek] || 0) + 1;
      qCard.classList.add('question-card');

      // 🟨 Pisahkan pertanyaan pertama
      if (q.aspek === "Evaluasi Pemenuhan SOP") {
        qCard.innerHTML = `
        <div class="question-text">${counters[q.aspek]}. ${q.instrumen}</div>
        <div class="emoji-container">
          <div class="emoji-option">
            <div class="lottie-box yes" id="yes${i}"></div>
            <div class="emoji-label">YA</div>
          </div>
          <div class="emoji-option">
            <div class="lottie-box no" id="no${i}"></div>
            <div class="emoji-label">TIDAK</div>
          </div>
        </div>
        <label for="note${i}" class="fw-semibold mt-3 d-block">Catatan:</label>
        <textarea class="note-input form-control" id="note${i}" name="notes[${i}]" placeholder="Tambahkan catatan (opsional)...">${old.note || ''}</textarea>
        <input type="hidden" name="question_ids[${i}]" value="${q.id}">
        <input type="hidden" name="f02_ids[${i}]" value="${old.f02_id}">
        <input type="hidden" name="answers[${i}]" id="answer${i}" value="${old.answer || ''}">
      `;
        firstContainer.appendChild(qCard);
      } else {
        qCard.innerHTML = `
        <div class="question-text">${counters[q.aspek]}. ${q.instrumen}</div>
        <div class="emoji-container">
          <div class="emoji-option">
            <div class="lottie-box yes" id="yes${i}"></div>
            <div class="emoji-label">YA</div>
          </div>
          <div class="emoji-option">
            <div class="lottie-box no" id="no${i}"></div>
            <div class="emoji-label">TIDAK</div>
          </div>
        </div>
        <label for="note${i}" class="fw-semibold mt-3 d-block">Catatan:</label>
        <textarea class="note-input form-control" id="note${i}" name="notes[${i}]" placeholder="Tambahkan catatan (opsional)...">${old.note || ''}</textarea>
        <input type="hidden" name="question_ids[${i}]" value="${q.id}">
        <input type="hidden" name="f02_ids[${i}]" value="${old.f02_id}">
        <input type="hidden" name="answers[${i}]" id="answer${i}" value="${old.answer || ''}">
      `;
        otherContainer.appendChild(qCard);
      }

      // 🎬 Tambah animasi Lottie (Yes / No)
      const yesAnim = lottie.loadAnimation({
        container: document.getElementById(`yes${i}`),
        renderer: 'svg',
        loop: false,
        autoplay: false,
        path: "{{ asset('animation/EmojiYep.json') }}" // head nod animation
      });

      const noAnim = lottie.loadAnimation({
        container: document.getElementById(`no${i}`),
        renderer: 'svg',
        loop: false,
        autoplay: false,
        path: "{{ asset('animation/EmojiNo.json') }}" // shake head animation
      });

      // ✅ Interaksi klik animasi
      const yesBox = document.getElementById(`yes${i}`);
      const noBox = document.getElementById(`no${i}`);
      const answerInput = document.getElementById(`answer${i}`);

      // 🔸 Aktifkan status sesuai jawaban lama
        if (old.answer === "ya") {
            yesBox.classList.add('active', 'yes');
        } else if (old.answer === "tidak") {
            noBox.classList.add('active', 'no');
        }

      yesBox.addEventListener('click', () => {
        yesAnim.goToAndPlay(0, true);
        yesBox.classList.add('active', 'yes');
        noBox.classList.remove('active', 'no');
        answerInput.value = 'ya';
      });

      noBox.addEventListener('click', () => {
        noAnim.goToAndPlay(0, true);
        noBox.classList.add('active', 'no');
        yesBox.classList.remove('active', 'yes');
        answerInput.value = 'tidak';
      });
    });

    // 🧩 Validasi Required
    document.getElementById('surveyForm').addEventListener('submit', function(e) {
      e.preventDefault();

      let allAnswered = true;
      questions.forEach((q, i) => {
        const answer = document.getElementById(`answer${i}`).value;
        if (!answer) {
          allAnswered = false;
          document.getElementById(`yes${i}`).parentElement.parentElement.scrollIntoView({ behavior: 'smooth', block: 'center' });
          document.getElementById(`yes${i}`).parentElement.parentElement.classList.add('border', 'border-danger', 'rounded-3');
          setTimeout(() => {
            document.getElementById(`yes${i}`).parentElement.parentElement.classList.remove('border', 'border-danger');
          }, 2000);
        }
      });

      if (!allAnswered) {
        Swal.fire({
          icon: 'warning',
          title: 'Jawaban Belum Lengkap',
          text: 'Silakan isi semua pertanyaan sebelum mengirim survei.',
          confirmButtonColor: '#388da8',
        });
        return;
      }

    //   Swal.fire({
    //     icon: 'success',
    //     title: 'Berhasil!',
    //     text: 'Semua jawaban telah disimpan dengan baik.',
    //     confirmButtonColor: '#388da8',
    //   });

        // Save signature
        if (signaturePad.isEmpty()) {
            Swal.fire({
                icon: 'warning',
                title: 'TTD atau Paraf belum diisi',
                text: 'Silakan buat tanda tangan/paraf terlebih dahulu.',
                confirmButtonColor: '#388da8',
            });
            return;
        }

        const data = signaturePad.toDataURL('image/png');
        document.getElementById('signatureData').value = data;

        const formData = new FormData(this);
    //   console.log(Object.fromEntries(formData)); // debug lihat hasil
      
        const userRole = "{{ session('defaultRoleCode') }}";
        if (userRole === 'admin') {
            
            document.getElementById('surveyForm').submit();
        } else {
            Swal.fire({
                icon: 'warning',
                title: 'Tidak bisa menyimpan data',
                text: 'Hanya Admin yang boleh mengisi Form 02 Monev',
                confirmButtonColor: '#388da8',
            });
        }
    });

    </script>

@endsection