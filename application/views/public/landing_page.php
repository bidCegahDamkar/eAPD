<main>

  <div id="myCarousel" class="carousel slide" data-bs-ride="carousel">
    <div class="carousel-indicators">
      <button type="button" data-bs-target="#myCarousel" data-bs-slide-to="0" class="active" aria-current="true" aria-label="Slide 1"></button>
      <button type="button" data-bs-target="#myCarousel" data-bs-slide-to="1" aria-label="Slide 2"></button>
    </div>
    <div class="carousel-inner">
      <div class="carousel-item active">
        <img src="<?php echo base_url(); ?>assets/landing_page/landing_page1.jpg" class="bd-placeholder-img" alt="image" height="100%">

        <div class="container">
          <div class="carousel-caption text-start">
            <h1>Welcome</h1>
            <p>Selamat Datang di Sistem Informasi eAPD</p>
          </div>
        </div>
      </div>
      <div class="carousel-item">
        <img src="<?php echo base_url(); ?>assets/landing_page/brand2_sm.jpg" class="bd-placeholder-img" alt="image" height="100%">
        <div class="container">
          <div class="carousel-caption">
            <h1>Login</h1>
            <p>Untuk masuk ke akun eapd</p>
            <p><a class="btn btn-lg btn-primary" href="<?= base_url().'auth/login'; ?>">Login</a></p>
          </div>
        </div>
      </div>
    </div>
    <button class="carousel-control-prev" type="button" data-bs-target="#myCarousel" data-bs-slide="prev">
      <span class="carousel-control-prev-icon" aria-hidden="true"></span>
      <span class="visually-hidden">Previous</span>
    </button>
    <button class="carousel-control-next" type="button" data-bs-target="#myCarousel" data-bs-slide="next">
      <span class="carousel-control-next-icon" aria-hidden="true"></span>
      <span class="visually-hidden">Next</span>
    </button>
  </div>


  <!-- Marketing messaging and featurettes
  ================================================== -->
  <!-- Wrap the rest of the page in another container to center all the content. -->

  <div class="container marketing">

    <!-- START THE FEATURETTES -->

    <hr class="featurette-divider">

    <div class="row featurette">
      <div class="col-md-7">
        <h2 class="featurette-heading">Kata Sambutan</h2>
        <p class="lead">Kepala Dinas Penanggulangan Kebakaran dan Penyelamatan</p>
        <p><a class="btn btn-lg btn-primary" href="<?= base_url().'welcome/intro'; ?>">Baca Disini</a></p>
      </div>
      <div class="col-md-5">
        <img src="<?php echo base_url(); ?>assets/landing_page/kadis.jpg" class="bd-placeholder-img bd-placeholder-img-lg featurette-image img-fluid mx-auto" alt="image" height="100%">
      </div>
    </div>

    <hr class="featurette-divider">

    <div class="row featurette">
      <div class="col-md-7 order-md-2">
        <h2 class="featurette-heading mt-0">Panduan Penggunaan Sistem Informasi <span class="text-muted">eAPD</span></h2>
        <p><a class="btn btn-lg btn-primary" href="<?= base_url().'assets/landing_page/manual_book/manual_personil.pdf';?>" target="_blank">User Manual Akun Personil</a></p>
        <p><a class="btn btn-lg btn-primary" href="<?= base_url().'assets/landing_page/manual_book/manual_katon.pdf';?>" target="_blank">User Manual Akun Katon</a></p>
        <p><a class="btn btn-lg btn-primary" href="<?= base_url().'assets/landing_page/manual_book/manual_kasektor.pdf';?>" target="_blank">User Manual Akun Kasektor/Kasi Dalkarmat</a></p>
        <p><a class="btn btn-lg btn-primary" href="<?= base_url().'assets/landing_page/manual_book/manual_admin_sudin.pdf';?>" target="_blank">User Manual Akun Admin Sudin</a></p>
      </div>
      <div class="col-md-5 order-md-1">
        <img src="<?php echo base_url(); ?>assets/landing_page/user_manual.jpg" class="bd-placeholder-img bd-placeholder-img-lg featurette-image img-fluid mx-auto" alt="image" height="100%">

      </div>
    </div>

    <hr class="featurette-divider">

    <div class="row featurette">
      <div class="col-md-7">
        <h2 class="featurette-heading mt-0">Format Surat Pernyataan Kebenaran dan Laporan Kehilangan</h2>
        <p><a class="btn btn-lg btn-primary" href="<?= base_url().'assets/landing_page/manual_book/surat_kebenaran.pdf';?>" target="_blank">Format Surat Pernyataan Kebenaran</a></p>
        <p class="lead">Surat Pernyataan Kebenaran Laporan APD Wajib dibuat untuk semua petugas Disgulkarmat</p>
        <p><a class="btn btn-lg btn-primary" href="<?= base_url().'assets/landing_page/manual_book/kehilangan.pdf';?>" target="_blank">Format Laporan Kehilangan APD</a></p>
        <p><a class="btn btn-lg btn-primary" href="<?= base_url().'assets/landing_page/manual_book/kronologis.pdf';?>" target="_blank">Format Kronologis Kehilangan APD</a></p>
        <p class="lead">Surat Laporan Kehilangan APD dan Surat Kronologis Kehilangan APD Wajib dibuat untuk petugas Disgulkarmat yang mengalami kehilangan APD</p>
      </div>
      <div class="col-md-5">
        <img src="<?php echo base_url(); ?>assets/landing_page/surat_pernyataan.jpg" class="bd-placeholder-img bd-placeholder-img-lg featurette-image img-fluid mx-auto" alt="image" height="100%">

      </div>
    </div>
    

    <hr class="featurette-divider">

    <!-- /END THE FEATURETTES -->

  </div><!-- /.container -->
