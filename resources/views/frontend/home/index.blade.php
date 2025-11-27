@extends('frontend.master.index')

@section('content')
  <!-- Hero Section -->
  <section id="hero" class="hero section">
    <div class="hero-bg">
      <img src="{{ asset('quickstart/assets/img/hero-bg-light.webp') }}" alt="">
    </div>
    <div class="container text-center">
      <div class="d-flex flex-column justify-content-center align-items-center">
        <h1 data-aos="fade-up">Selamat Datang di <span>SIMONEV-SOP</span></h1>
        <p data-aos="fade-up" data-aos-delay="100">Sistem Informasi Konsultasi, Monitoring & Evaluasi SOP Pemerintah Kabupaten Badung<br></p>
        <div class="d-flex" data-aos="fade-up" data-aos-delay="200">
          <a href="{{route('backend.beranda')}}" class="btn-get-started">Jelajahi Sistem</a>
          <a href="https://klik.badungkab.go.id/u/PanduanSIMONEVSOP" class="btn-watch-video d-flex align-items-center" target="_blank"><i class="bi bi-journal-text"></i><span>Baca Panduan</span></a>
        </div>
        <img src="{{ asset('images/photo/bupwabup3.png') }}" class="img-fluid hero-img" alt="" data-aos="zoom-out" data-aos-delay="300">
      </div>
    </div>

  </section>
  <!-- /Hero Section -->

  <!-- Featured Services Section -->
  <section id="featured-services" class="featured-services section light-background">

    <div class="container">

      <div class="row gy-4">

        <div class="col-xl-4 col-lg-6" data-aos="fade-up" data-aos-delay="100">
          <div class="service-item d-flex">
            <div class="icon flex-shrink-0"><i class="bi bi-chat-dots"></i></div>
            <div>
              <h4 class="title"><a href="#" class="stretched-link">Konsultasi SOP</a></h4>
              <p class="description">Kemudahan konsultasi SOP untuk seluruh perangkat daerah dengan sistem terintegrasi.</p>
            </div>
          </div>
        </div>
        <!-- End Service Item -->

        <div class="col-xl-4 col-lg-6" data-aos="fade-up" data-aos-delay="200">
          <div class="service-item d-flex">
            <div class="icon flex-shrink-0"><i class="bi bi-clipboard-data"></i></div>
            <div>
              <h4 class="title"><a href="#" class="stretched-link">Monitoring SOP</a></h4>
              <p class="description">Pantau implementasi SOP di seluruh perangkat daerah secara real-time dan transparan.</p>
            </div>
          </div>
        </div><!-- End Service Item -->

        <div class="col-xl-4 col-lg-6" data-aos="fade-up" data-aos-delay="300">
          <div class="service-item d-flex">
            <div class="icon flex-shrink-0"><i class="bi bi-card-checklist"></i></div>
            <div>
              <h4 class="title"><a href="#" class="stretched-link">Evaluasi SOP</a></h4>
              <p class="description">Evaluasi efektifitas SOP di seluruh perangkat daerah dengan laporan berbasis data.</p>
            </div>
          </div>
        </div><!-- End Service Item -->

      </div>

    </div>

  </section>
  <!-- /Featured Services Section -->

  <!-- About Section -->
  <section id="about" class="about section">

    <div class="container">

      <div class="row gy-4">

        <div class="col-lg-6 content" data-aos="fade-up" data-aos-delay="100">
          <p class="who-we-are">SIMONEV-SOP</p>
          <h3>Sistem Informasi Konsultasi, Monitoring & Evaluasi SOP Pemerintah Kabupaten Badung</h3>
          <p class="fst-italic">
            SIMONEV-SOP adalah platform digital resmi Bagian Organisasi Pemerintah Kabupaten Badung 
            yang dirancang untuk memfasilitasi pelaksanaan konsultasi, monitoring, dan evaluasi 
            Standar Operasional Prosedur (SOP) di seluruh perangkat daerah.
            SIMONEV-SOP dapat membantu perangkat daerah dalam menyusun, 
            meninjau, dan mengevaluasi SOP dengan lebih efisien, serta dapat menyediakan ruang komunikasi 
            interaktif antara perangkat daerah dengan Bagian Organisasi. Hal ini memastikan setiap 
            SOP yang disusun sesuai dengan standar, mudah diimplementasikan, serta mendukung 
            pelayanan publik yang cepat dan tepat.
          </p>
          <a href="{{route('backend.beranda')}}" class="read-more"><span>Jelajahi Sistem</span><i class="bi bi-arrow-right"></i></a>
        </div>

        <div class="col-lg-6 about-images" data-aos="fade-up" data-aos-delay="200">
          <div class="row gy-4">
            <div class="col-lg-6 p-xl-3 p-lg-3">
              <img src="{{ asset('images/photo/photo_8.jpeg')}}" class="img-fluid" alt="">
            </div>
            <div class="col-lg-6">
              <div class="row gy-4">
                <div class="col-lg-12">
                  <img src="{{ asset('images/photo/photo_5.jpeg')}}" class="img-fluid" alt="">
                </div>
                <div class="col-lg-12">
                  <img src="{{ asset('images/photo/photo_7.jpeg')}}" class="img-fluid" alt="">
                </div>
              </div>
            </div>
          </div>

        </div>

      </div>

    </div>
  </section>
  <!-- /About Section -->

  <!-- Features Details Section -->
  <section id="features-details" class="features-details section">

    <div class="container">

      <div class="row gy-4 justify-content-between features-item">

        <div class="col-lg-6" data-aos="fade-up" data-aos-delay="100">
          <img src="{{ asset('images/photo/photo_1.jpeg')}}" class="img-fluid" alt="">
        </div>

        <div class="col-lg-5 d-flex align-items-center" data-aos="fade-up" data-aos-delay="200">
          <div class="content">
            <h3>Konsultasi SOP</h3>
            <p>
              Memfasilitasi perangkat daerah untuk mendapatkan pendampingan 
              dalam penyusunan maupun perbaikan SOP.
              Konsultasi dapat dilakukan secara digital melalui sistem maupun dengan datang langsung ke kantor Bagian Organisasi.
              Melalui konsultasi ini, perangkat daerah dapat menyampaikan rancangan SOP, memperoleh masukan, serta mendapatkan 
              arahan penyempurnaan secara efektif.
            </p>
            <a href="{{route('backend.beranda')}}" class="btn more-btn">Pelajari Lebih Lanjut</a>
          </div>
        </div>

      </div><!-- Features Item -->

      <div class="row gy-4 justify-content-between features-item">

        <div class="col-lg-5 d-flex align-items-center order-2 order-lg-1" data-aos="fade-up" data-aos-delay="100">

          <div class="content">
            <h3>Monitoring & Evaluasi SOP</h3>
            <p>
              Proses monitoring pelaksanaan SOP di perangkat daerah dilakukan 
              secara terpusat dan transparan. Fitur ini membantu mengawasi sejauh mana SOP 
              dijalankan sesuai ketentuan dan menjadi dasar perbaikan berkelanjutan.
            </p>
            <p>
              Fitur Evaluasi SOP memudahkan perangkat daerah menilai efektivitas SOP yang diterapkan. 
              Hasil evaluasi menjadi acuan dalam peningkatan kualitas prosedur kerja, sehingga mendukung 
              terciptanya pelayanan publik yang lebih efisien dan akuntabel.
            </p>
            <a href="{{route('backend.beranda')}}" class="btn more-btn">Pelajari Lebih Lanjut</a>
          </div>

        </div>

        <div class="col-lg-6 order-1 order-lg-2" data-aos="fade-up" data-aos-delay="200">
          <img src="{{ asset('images/photo/photo_3.jpeg')}}" class="img-fluid" alt="">
        </div>

      </div><!-- Features Item -->

    </div>

  </section><!-- /Features Details Section -->

@endsection

@push('js')

@endpush