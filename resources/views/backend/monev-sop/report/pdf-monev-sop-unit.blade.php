<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Laporan Hasil Monev SOP {{ $unit->name }}</title>
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
          table-layout: fixed; /* penting */
        }

        th, td {
          border: 1px solid #000;
          padding: 6px;
          font-size: 11px;
          vertical-align: top;
          word-wrap: break-word;
          white-space: normal !important;
        }

        th {
          text-align: center;
          font-weight: bold;
        }

        /* Mencegah elemen terpotong di PDF DomPDF */
        .page-break {
          page-break-after: always;
        }

        tr {
          page-break-inside: avoid !important;
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

        .fw-bold { font-weight: bold; }
        .text-center { text-align: center; }
        .text-justify { text-align: justify; }
        ul { margin: 4px 0 4px 16px; }
        .signature { text-align: right; margin-top: 50px; }
    </style>
</head>
<body>

    <h3>LAPORAN HASIL MONITORING DAN EVALUASI SOP</h3>
    <table class="table-borderless">
        <tr>
            <td width="25%" style="font-size: 14px !important">Periode</td>
            <td width="3%" style="font-size: 14px !important">:</td>
            <td style="font-size: 14px !important">{{ $periode->periode_year ?? '-' }}</td>
        </tr>
        <tr>
            <td width="25%" style="font-size: 14px !important">Tanggal Pelaksanaan</td>
            <td width="3%" style="font-size: 14px !important">:</td>
            <td style="font-size: 14px !important">{{ $periode ? \Carbon\Carbon::parse($periode->start_date)->translatedFormat('d F Y'). ' s.d. ' .\Carbon\Carbon::parse($periode->end_date)->translatedFormat('d F Y')  : '-' }}</td>
        </tr>
        <tr>
            <td style="font-size: 14px !important">Perangkat Daerah / Unit</td>
            <td style="font-size: 14px !important">:</td>
            <td style="font-size: 14px !important">{{ $unit->name }}</td>
        </tr>
    </table>

    <div class="section-title">A. Pendahuluan</div>
    <p class="text-justify">
        Monitoring dan Evaluasi (Monev) Standar Operasional Prosedur (SOP) dilaksanakan untuk menilai sejauh mana implementasi SOP pada setiap kegiatan di lingkungan {{ $unit->name }} telah berjalan sesuai dengan ketentuan yang berlaku. 
        Kegiatan ini bertujuan untuk memastikan bahwa pelaksanaan SOP mampu mendukung peningkatan efektivitas, efisiensi, dan akuntabilitas kinerja organisasi, serta menjadi dasar dalam melakukan penyempurnaan kebijakan dan prosedur kerja.
    </p>

    <div class="section-title">B. Gambaran Umum Pelaksanaan Monev SOP</div>
    <p class="text-justify">
        Pelaksanaan Monev dilakukan terhadap seluruh SOP yang berlaku di {{ $unit->name }}, dengan menggunakan instrumen penilaian yang terdiri atas dua aspek utama:
    </p>
    <ul>
        <li>Monitoring Pelaksanaan SOP, untuk melihat sejauh mana SOP dijalankan sesuai ketentuan.</li>
        <li>Evaluasi Penerapan SOP, untuk menilai kesesuaian isi SOP dengan kebutuhan dan kondisi aktual pelaksanaan.</li>
        <li>Pertanyaan tambahan untuk mengidentifikasi apakah ada kegiatan/aktivitas dalam unit kerja yang belum memiliki SOP.</li>
    </ul>
    <p class="text-justify">
        Proses pengisian dilakukan oleh pelaksana SOP melalui sistem SIMONEV-SOP (simonev-sop.badungkab.go.id), dan hasilnya dihimpun untuk dianalisis secara keseluruhan.
    </p>

    <div class="section-title">C. Hasil Monitoring dan Evaluasi SOP</div>

    <p><strong>1. Hasil Monev per SOP</strong></p>
    <table>
        <thead>
            <tr>
                <th width="5%">No</th>
                <th width="18%">Nomor SOP</th>
                <th width="28%">Nama SOP</th>
                <th width="18%">Pelaksana SOP</th>
                <th width="15%">TTD</th>
                <th width="16%">Hasil Monev</th>
            </tr>
        </thead>
        <tbody>
          @forelse ($sops as $index => $sop)
              @php
                  $monev = $sop->monev->first(); 
                  $signaturePath = $monev?->ttd_path_pelaksana_sop
                      ? storage_path('app/private/' . $monev->ttd_path_pelaksana_sop)
                      : null;
              @endphp

              <tr>
                  <td class="text-center">{{ $index + 1 }}</td>
                  <td>{{ $sop->nomor }}</td>
                  <td>{{ $sop->nama }}</td>

                  {{-- Nama Pelaksana --}}
                  <td>{{ $monev?->nama_pelaksana_sop ?? '-' }}</td>

                  {{-- Tanda Tangan Pelaksana --}}
                  <td class="text-center">
                      @if($signaturePath && file_exists($signaturePath) && is_file($signaturePath))
                          <img src="data:image/png;base64,{{ base64_encode(file_get_contents($signaturePath)) }}"
                              style="width:90px; height:auto;">
                      @else
                          <em>-</em>
                      @endif
                  </td>

                  {{-- Link hasil monev per SOP --}}
                  <td class="">
                      @php $m = $sop->monev->first(); @endphp
                      @if($m)
                          <a href="{{ route('monev-sop.sop.report.cetak.public', $m->id) }}" target="_blank">
                              {{ route('monev-sop.sop.report.cetak.public', $m->id) }}
                          </a>
                      @else
                          <span>-</span>
                      @endif
                  </td>
              </tr>
          @empty
              <tr>
                  <td colspan="6" class="text-center">Tidak ada data Monev SOP pada periode ini</td>
              </tr>
          @endforelse
      </tbody>

    </table>

    <p><strong>2. Ringkasan Hasil Monev per SOP</strong></p>
    <table>
        <thead>
            <tr>
                <th width="5%">No</th>
                <th width="18%">Nomor SOP</th>
                <th width="28%">Nama SOP</th>
                <th width="18%">Jawaban “Ya”</th>
                <th width="18%">Jawaban “Tidak”</th>
                <th width="18%">Keterangan</th>
            </tr>
        </thead>
        <tbody>
            @forelse ($sops as $index => $sop)
                @php
                    $instrumenIds = $instrumens
                          ->where('aspek', '!=', 'Additional Question')
                          ->pluck('id')
                          ->toArray();

                    $ya = $sop->monev
                        ->flatMap->result_f01
                        ->whereIn('instrumen_id', $instrumenIds)
                        ->where('jawaban', 'ya')
                        ->count();

                    $tidak = $sop->monev
                        ->flatMap->result_f01
                        ->whereIn('instrumen_id', $instrumenIds)
                        ->where('jawaban', 'tidak')
                        ->count();

                    $status = $tidak > 0 ? 'Perlu Revisi' : 'Tidak Perlu Revisi';
                @endphp
                <tr>
                    <td>{{ $index + 1 }}</td>
                    <td>{{ $sop->nomor }}</td>
                    <td>{{ $sop->nama }}</td>
                    <td class="text-center">{{ $ya }}</td>
                    <td class="text-center">{{ $tidak }}</td>
                    <td class="text-center">{{ $status }}</td>
                </tr>
           @empty
                <tr><td colspan="6" class="text-center">Tidak ada data Monev SOP pada periode ini</td></tr>
            @endforelse
        </tbody>
    </table>

    <p><strong>3. Analisis Umum</strong></p>
    @php
        $totalSop = $sops->count();
        // Ambil ID instrumen yang termasuk aspek Additional Question
        $additionalInstrumenIds = $instrumens
            ->where('aspek', 'Additional Question')
            ->pluck('id');
        
        $sopOptimal = $sops->filter(function ($sop) use ($additionalInstrumenIds) {
            return $sop->monev->flatMap->result_f01->where('jawaban', 'tidak')->whereNotIn('instrumen_id', $additionalInstrumenIds)->isEmpty();
        });

        $sopBelumOptimal = $sops->filter(function($sop) use ($additionalInstrumenIds){
            return $sop->monev->flatMap->result_f01->where('jawaban', 'tidak')->whereNotIn('instrumen_id', $additionalInstrumenIds)->isNotEmpty();
        });

        $kegiatanTambahanSOP = $sops->map(function($sop) use ($additionalInstrumenIds) {
            $catatan = $sop->monev
                ->flatMap->result_f01
                ->whereIn('instrumen_id', $additionalInstrumenIds)
                ->where('jawaban', 'tidak')
                ->pluck('catatan')
                ->filter();

            return [
                'nomor' => $sop->nomor,
                'nama'  => $sop->nama,
                'kegiatan' => $catatan
            ];
        })->filter(fn($item) => $item['kegiatan']->isNotEmpty());
    @endphp

    <p class="text-justify">
        Berdasarkan hasil monitoring dan evaluasi terhadap seluruh SOP yang telah dilaksanakan di 
        {{ $unit->name }} periode {{ $periode->periode_year ?? '-' }}, diperoleh gambaran umum sebagai berikut:
    </p>
    <ul>
        @if($sopOptimal->count() > 0)
             @if($sopOptimal->count() != $totalSop)
                <li>Terdapat {{ $sopOptimal->count() }} SOP telah dilaksanakan dengan baik sesuai dengan ketentuan yang berlaku.</li>
            @else
                <li>Semua SOP telah dilaksanakan dengan baik sesuai dengan ketentuan yang berlaku.</li>
            @endif
        @endif

        @if($sopBelumOptimal->count() > 0)
            @if($sopBelumOptimal->count() != $totalSop)
                <li>Terdapat {{ $sopBelumOptimal->count() }} SOP yang pelaksanaannya belum berjalan optimal dan memerlukan perbaikan terhadap isi, tahapan dan penerapan SOP.</li>
            @else
                <li>Semua SOP pelaksanaannya belum berjalan optimal dan memerlukan perbaikan terhadap isi, tahapan dan penerapan SOP.</li>
            @endif
        @endif

        @if($kegiatanTambahanSOP->count() > 0)
            <li>Terdapat aktivitas/kegiatan yang perlu dilakukan penyusunan SOP, yaitu : 
              @foreach($kegiatanTambahanSOP as $index => $item)
                @foreach($item['kegiatan'] as $catatan)
                      {{ $catatan }} @if ($index+1 != $kegiatanTambahanSOP->count()), @endif
                  @endforeach
               @endforeach
               .
            </li>
          @endif
    </ul>


    <!-- D. Rekomendasi Tindak Lanjut -->
    <div class="section-title">D. Rekomendasi Tindak Lanjut</div>

    @php $no = 1; @endphp

    @if ($sopOptimal->count() > 0)
    <p class="text-justify">
        {{ $no++ }}. Untuk penerapan SOP yang dinilai telah berjalan dengan baik dan sesuai dengan ketentuan yang berlaku, 
        disarankan agar pelaksanaan SOP tersebut dipertahankan dan dilakukan pemantauan secara berkala 
        untuk menjaga konsistensi dan efektivitas berkelanjutan.
    </p>
    @endif

    @if($sopBelumOptimal->count() > 0)
        <p class="text-justify">
            {{ $no++ }}. Diperlukan pelaksanaan evaluasi menyeluruh dan revisi terhadap SOP berikut:
        </p>

        <table style="width:100%; border-collapse:collapse; margin-top:8px;">
            <thead>
                <tr>
                    <th style="border:1px solid #000; text-align:center; width:5%;">No</th>
                    <th style="border:1px solid #000; text-align:center; width:20%;">Nomor SOP</th>
                    <th style="border:1px solid #000; text-align:center; width:20%;">Nama SOP</th>
                    <th style="border:1px solid #000; text-align:center; width:45%;">Keterangan</th>
                </tr>
            </thead>
            <tbody>
                @foreach($sopBelumOptimal as $i => $sop)
                  <tr>
                      <td style="border:1px solid #000; text-align:center;">{{ $i + 1 }}</td>
                      <td style="border:1px solid #000;">{{ $sop->nomor ?? '-' }}</td>
                      <td style="border:1px solid #000;">{{ $sop->nama }}</td>

                      @php
                          // Ambil instrumen_id yang jawabannya 'tidak'
                          $instrumenIds = $sop->monev->flatMap->result_f01
                              ->where('jawaban', 'tidak')
                              ->pluck('instrumen_id');

                          // Filter instrumens berdasarkan id tsb, kecuali Additional Question
                          $instrumenTidak = $instrumens
                              ->whereIn('id', $instrumenIds)
                              ->where('aspek', '!=', 'Additional Question');
                      @endphp

                      <td style="border:1px solid #000;">
                          <ul style="margin:4px 0; padding-left:16px;">
                              @forelse($instrumenTidak as $instrumen)
                                  <li>{{ $instrumen->tindak_lanjut_des }}</li>
                              @empty
                                  <li>-</li>
                              @endforelse
                          </ul>
                      </td>
                  </tr>
                @endforeach
            </tbody>
        </table>

        <p class="text-justify" style="margin-top:8px;">
            Sebagai tindak lanjut, disarankan agar dilakukan evaluasi mendalam dan revisi terhadap pelaksanaan SOP, 
            disertai dengan penyempurnaan substansi SOP agar implementasi SOP dapat berjalan secara efektif dan konsisten.
        </p>
    @endif

    @if($kegiatanTambahanSOP->count())
        <p class="text-justify" style="margin-top:10px;">
            {{ $no++ }}. Ditemukan bahwa tidak semua aktivitas atau kegiatan dalam unit kerja telah memiliki SOP. 
            Oleh karena itu, perlu dilakukan penyusunan SOP untuk kegiatan yaitu:
            @foreach($kegiatanTambahanSOP as $index => $item)
                @foreach($item['kegiatan'] as $catatan)
                      {{ $catatan }} @if ($index+1 != $kegiatanTambahanSOP->count()), @endif
                  @endforeach
               @endforeach
               . Penyusunan SOP terhadap kegiatan tersebut diharapkan dapat memperjelas tanggung jawab pelaksana, 
            serta meningkatkan kualitas dan konsistensi layanan organisasi.
        </p>
    @endif


    <div class="section-title">E. Penutup</div>
    <p class="text-justify">
        Secara umum, pelaksanaan Monitoring dan Evaluasi SOP pada {{ $unit->name }} telah memberikan gambaran yang jelas mengenai tingkat kepatuhan dan efektivitas penerapan SOP di lapangan. 
        Hasil ini diharapkan menjadi dasar dalam peningkatan kualitas tata kelola dan penyempurnaan prosedur kerja serta penguatan budaya kerja berbasis standar di lingkungan {{ $unit->name }}.
    </p>

    {{-- <div class="signature">
        <p>Mangupura, {{ \Carbon\Carbon::now()->translatedFormat('d F Y') }}</p>
        <p><strong>Tim Pelaksana Monev SOP</strong></p>
        <br><br><br>
        <p>(...........................................)</p>
    </div> --}}

</body>
</html>
