<!DOCTYPE html>
<html lang="id">
<head>
  <meta charset="UTF-8">
  <title>Hasil Monitoring dan Evaluasi SOP</title>
  <link rel="shortcut icon" href="{{ asset('images/logo/logo1_white_background.png')}}" />
  <style>
    @page { margin: 40px; }
    body {
      font-family: "Helvetica", "DejaVu Sans", sans-serif;
      font-size: 14px;
      line-height: 1.5;
      color: #000000;
    }

    h3 {
      text-align: center;
      font-weight: bold;
      margin-bottom: 4px;
    }

    h2 {
      text-align: center;
      font-weight: bold;
      margin-bottom: 4px;
    }
    hr {
      border: none;
      border-top: 1px solid #000;
      margin: 8px auto 16px;
    }

    .section-title {
      font-weight: bold;
      /* text-transform: uppercase; */
      font-size: 16px;
      /* border-bottom: 1px solid #ccc; */
      margin-top: 20px;
      margin-bottom: 6px;
      padding-bottom: 2px;
    }

    table {
      width: 100%;
      border-collapse: collapse;
      margin-top: 10px;
    }
    th, td {
      padding: 6px 8px;
      /* border: 1px solid #dee2e6; */
      border: 1px solid #000000;
      vertical-align: top;
    }
    th {
      /* background-color: #f8f9fa; */
      font-weight: bold;
      text-align: center;
    }
    .table-borderless td {
      border: none !important;
      padding: 4px 2px;
    }

    .signature {
      margin-top: 60px;
      text-align: right;
    }
    .signature img {
      width: 300px;
      height: auto;
      display: block;
      margin-left: auto;
      margin-top: 5px;
    }
    .fw-bold { font-weight: bold; }
    .text-center { text-align: center; }
    .text-justify { text-align: justify; }
  </style>
</head>
<body>

  <h3>Hasil Monitoring dan Evaluasi SOP</h3>
  {{-- <hr> --}}

  <!-- A. Informasi Umum -->
  <div class="section-title">A. Informasi Umum</div>
  <table class="table-borderless">
    <tbody>
      <tr><td width="30%">Nama SOP</td><td width="2%">:</td><td>{{ $sop->nama }}</td></tr>
      <tr><td>Nomor SOP</td><td>:</td><td>{{ $sop->nomor }}</td></tr>
      <tr><td>Unit/OPD</td><td>:</td><td>{{ $unit->name ?? '-' }}</td></tr>
      <tr><td>Tanggal Pengisian Monev</td><td>:</td><td>{{ \Carbon\Carbon::parse($monev->tgl_pengisian_f01)->translatedFormat('d F Y') }}</td></tr>
      <tr><td>Periode Monev</td><td>:</td><td>{{ $periode }}</td></tr>
      <tr><td>Nama Pelaksana SOP</td><td>:</td><td>{{ $monev->nama_pelaksana_sop ?? '-' }}</td></tr>
    </tbody>
  </table>

  <!-- B. Monitoring Pelaksanaan SOP -->
  <div class="section-title">B. Monitoring Pelaksanaan SOP</div>
  <table>
    <thead>
      <tr>
        <th width="5%">No</th>
        <th>Pertanyaan</th>
        <th width="10%">Ya</th>
        <th width="10%">Tidak</th>
        <th>Catatan</th>
      </tr>
    </thead>
    <tbody>
        @php
            $monitoringData = $result_f01->where('aspek', 'Monitoring Pelaksanaan SOP');
        @endphp

        @if($monitoringData->isNotEmpty())
            @foreach ($monitoringData as $index => $item)
                <tr>
                    <td class="text-center">{{ $index + 1 }}</td>
                    <td>{{ $item->instrumen }}</td>
                    <td class="text-center">
                        @if($item->jawaban === 'ya')
                            <span style="font-size: 18px;">&#10003;</span>
                        @endif
                    </td>
                    <td class="text-center">
                        @if($item->jawaban === 'tidak')
                            <span style="font-size: 18px;">&#10003;</span>
                        @endif
                    </td>
                    <td>{{ $item->catatan ?? '' }}</td>
                </tr>
            @endforeach
        @else
            @foreach ($instrumens->where('aspek', 'Monitoring Pelaksanaan SOP') as $index => $item)
                <tr>
                    <td class="text-center">{{ $index + 1 }}</td>
                    <td>{{ $item->instrumen }}</td>
                    <td class="text-center"></td>
                    <td class="text-center"></td>
                    <td></td>
                </tr>
            @endforeach
        @endif
    </tbody>
  </table>

  <!-- C. Evaluasi Penerapan SOP -->
  <div class="section-title">C. Evaluasi Penerapan SOP</div>
  <table>
    <thead>
      <tr>
        <th width="5%">No</th>
        <th>Pertanyaan</th>
        <th width="10%">Ya</th>
        <th width="10%">Tidak</th>
        <th>Catatan</th>
      </tr>
    </thead>
    <tbody>

        @php
            $evaluasiData = $result_f01->whereIn('aspek', ['Evaluasi Penerapan SOP', 'Additional Question']);
        @endphp

        @if($evaluasiData->isNotEmpty())
            @foreach ($evaluasiData as $index => $item)
                <tr>
                    <td class="text-center">{{ $loop->iteration }}</td>
                    <td>{{ $item->instrumen }}</td>
                    <td class="text-center">
                        @if($item->jawaban === 'ya')
                            <span style="font-size: 18px;">&#10003;</span>
                        @endif
                    </td>
                    <td class="text-center">
                        @if($item->jawaban === 'tidak')
                            <span style="font-size: 18px;">&#10003;</span>
                        @endif
                    </td>
                    <td>{{ $item->catatan ?? '' }}</td>
                </tr>
            @endforeach
        @else
            @foreach ($instrumens->whereIn('aspek', ['Evaluasi Penerapan SOP', 'Additional Question']) as $index => $item)
                <tr>
                    <td class="text-center">{{ $loop->iteration }}</td>
                    <td>{{ $item->instrumen }}</td>
                    <td class="text-center"></td>
                    <td class="text-center"></td>
                    <td></td>
                </tr>
            @endforeach
        @endif
    </tbody>
  </table>

  <!-- D. Rekomendasi Tindak Lanjut -->
  <div class="section-title">D. Rekomendasi Tindak Lanjut</div>
  <table class="table-borderless">
  {{-- rekomendasi tindak lanjut 1 --}}
    @php
        // Ambil data yang bukan Additional Question dan jawabannya "tidak"
        $jawabanTidak = $result_f01
            ->where('aspek', '!=', 'Additional Question')
            ->where(fn($r) => strtolower($r->jawaban) === 'tidak');
    @endphp

       {{-- rekomendasi tindak lanjut 2 --}}
    @php
        $pertanyaan_tambahan = $result_f01
            ->where('aspek','Additional Question')
            ->where(fn($r) => strtolower($r->jawaban) === 'tidak');
    @endphp


    <tr>
    @if($jawabanTidak->isNotEmpty() && $result_f01->isNotEmpty())
    @if($pertanyaan_tambahan->isNotEmpty()) <td>1. </td> @endif
    <td class="text-justify">
        {{-- <p class="text-justify"> --}}
            Diperlukan pelaksanaan evaluasi menyeluruh dan revisi terhadap SOP dimaksud, mengingat hasil pemantauan menunjukkan bahwa:
        {{-- </p> --}}

        <ul style="margin-top: 8px;">
            @foreach($jawabanTidak as $item)
            <li>{{ $item->tindak_lanjut_des }}</li>
            @endforeach
        </ul>
        {{-- <p class="text-justify"> --}}
            Sebagai tindak lanjut, disarankan agar dilakukan evaluasi mendalam terhadap pelaksanaan SOP, 
            disertai dengan penyempurnaan substansi SOP agar implementasi SOP dapat berjalan secara efektif dan konsisten.
        {{-- </p> --}}
    </td>
    @elseif ($jawabanTidak->isEmpty() && $result_f01->isNotEmpty())
        @if($pertanyaan_tambahan->isNotEmpty())<td>1.</td> @endif
        <td class="text-justify">
        {{-- <p class="text-justify"> --}}
            Hasil pemantauan menunjukkan bahwa penerapan SOP telah berjalan dengan baik dan sesuai dengan ketentuan yang berlaku. 
            Sebagai tindak lanjut, disarankan agar pelaksanaan SOP tetap dipertahankan dan dilakukan pemantauan 
            secara berkala untuk menjaga konsistensi serta memastikan keberlanjutan efektivitasnya dalam mendukung 
            kinerja dan layanan organisasi.
        {{-- </p> --}}
        </td>
    @endif
    </tr>

    <tr>
    @if($pertanyaan_tambahan->isNotEmpty() && $result_f01->isNotEmpty())
    <td>2.</td>
    <td class="text-justify">
    {{-- <p class="text-justify"> --}}
        Ditemukan bahwa tidak semua aktivitas atau kegiatan 
        dalam unit kerja telah memiliki SOP. Oleh karena itu, perlu dilakukan penyusunan SOP untuk kegiatan yaitu @foreach($pertanyaan_tambahan as $item)
        {{ $item->catatan }}.
        @endforeach Penyusunan SOP terhadap kegiatan tersebut diharapkan dapat memperjelas tanggung jawab pelaksana, dan meningkatkan kualitas serta konsistensi layanan organisasi.
    {{-- </p> --}}
    </td>
    @endif
    </tr>

    </table>


  <!-- Signature -->
    <table style="width:100%; border-collapse:collapse; margin-top:60px;" class="table-borderless">
        <tr>
            <td style="width:60%;"></td> <!-- kolom kosong sebelah kiri -->
            <td style="width:40%; text-align:center;">
            Mangupura, {{ now()->translatedFormat('d F Y') }}<br>
            <strong>Pelaksana SOP</strong><br>

            @if(file_exists($signaturePath) && is_file($signaturePath))
                <img src="data:image/png;base64,{{ base64_encode(file_get_contents($signaturePath)) }}"
                    alt="Tanda Tangan"
                    style="width:300px; height:auto; margin:10px auto; display:block;">
            @else
                <div style="height:60px;"></div> <!-- placeholder jika tidak ada tanda tangan -->
            @endif

            <br>
            <strong>{{ $monev->nama_pelaksana_sop? $monev->nama_pelaksana_sop : '................................' }}</strong><br>
            </td>
        </tr>
    </table>


</body>
</html>
