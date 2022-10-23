<?php
defined('BASEPATH') OR exit('No direct script access allowed');
?><!doctype html>
<html lang="en">
  <head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="description" content="">
    <meta name="author" content="Kuswantoro">
    <meta name="generator" content="Hugo 0.88.1">
    <title>eAPD</title>

    <link rel="icon" href="<?php echo base_url(); ?>assets/icon/damkar.ico" sizes="32x32" type="ico">


    <!-- Bootstrap core CSS -->
    <link href="<?php echo base_url(); ?>assets/vendor/bootstrap/css/bootstrap.min.css" rel="stylesheet">
    <link href="<?php echo base_url(); ?>assets/admin_sektor/home.css" rel="stylesheet">

    <style>
      body {
        min-height: 75rem;
        padding-top: 4.5rem;
      }
      .bd-placeholder-img {
        font-size: 1.125rem;
        text-anchor: middle;
        -webkit-user-select: none;
        -moz-user-select: none;
        user-select: none;
      }

      @media (min-width: 768px) {
        .bd-placeholder-img-lg {
          font-size: 3.5rem;
        }
      }
    </style>

    <!-- Datatable -->
    <?php
    if(isset($datatable)){
      echo '
      <link href="https://cdn.datatables.net/1.11.3/css/jquery.dataTables.min.css" rel="stylesheet">
      <link href="'.base_url().'assets/vendor/datatable/css/buttons.dataTables.min.css" rel="stylesheet">
      ';
    }
    ?>

    <!-- set global variable-->
    <script>var base_url = '<?php echo base_url() ?>';</script>

  </head>
  <body>

<nav class="navbar navbar-expand-md navbar-dark fixed-top bg-dark">
  <div class="container-fluid">
    <a class="navbar-brand" href="#"><? //echo $jabatan['nama_jabatan']; ?></a>
    <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarCollapse" aria-controls="navbarCollapse" aria-expanded="false" aria-label="Toggle navigation">
      <span class="navbar-toggler-icon"></span>
    </button>
    <div class="collapse navbar-collapse" id="navbarCollapse">
      <ul class="navbar-nav me-auto mb-2 mb-md-0">
        <li class="nav-item">
          <a class="nav-link <? echo $active[0]; ?>" aria-current="page" href="<?php echo base_url().$controllers; ?>/">Home</a>
        </li>
        <li class="nav-item dropdown">
          <a class="nav-link dropdown-toggle <? echo $active[1]; ?>" href="#" id="navbarDarkDropdownMenuLink" role="button" data-bs-toggle="dropdown" aria-expanded="false">
          APD
          </a>
          <ul class="dropdown-menu dropdown-menu-dark" aria-labelledby="navbarDarkDropdownMenuLink">
            <li><a class="dropdown-item <? echo $active[2]; ?>" href="<?php echo base_url().$controllers; ?>/verifikasi">Verifikasi & Validasi APD</a></li>
            <li><a class="dropdown-item <? echo $active[3]; ?>" href="<?php echo base_url().$controllers; ?>/tervalidasi">Daftar APD Tervalidasi</a></li>
            <li><a class="dropdown-item <? echo $active[4]; ?>" href="<?php echo base_url().$controllers; ?>/ditolak">Daftar APD Tertolak</a></li>
          </ul>
        </li>
        <li class="nav-item">
          <a class="nav-link <? echo $active[5]; ?>" href="<?php echo base_url().$controllers; ?>/dataPegawai">Data Pegawai</a>
        </li>
        <li class="nav-item">
          <a class="nav-link <? echo $active[6]; ?>" href="<?php echo base_url().$controllers; ?>/setting">Setting</a>
        </li>
      </ul>
      <div class="d-flex">
        <a class="btn btn-outline-danger" href="#" data-bs-toggle="modal" data-bs-target="#exampleModalCenter">Keluar</a>
      </div>
    </div>
  </div>
</nav>

<!-- Modal -->
<div class="modal fade" id="exampleModalCenter" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1" aria-labelledby="staticBackdropLabel" aria-hidden="true">
  <div class="modal-dialog modal-dialog-centered">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="staticBackdropLabel">Konfirmasi</h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
      </div>
      <div class="modal-body">
        Apakah anda yakin keluar?
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Tidak</button>
        <a class="btn btn-danger" href="<?php echo base_url(); ?>auth/logout">Ya</a>
      </div>
    </div>
  </div>
</div>

<!-- * Notifikasi -->
<div class="modal fade" id="sukses" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
  <div class="modal-dialog">
    <div class="modal-content bg-success text-white">
      <div class="modal-body">
        Sukses
      </div>
    </div>
  </div>
</div>

<div class="modal fade " id="gagal" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
  <div class="modal-dialog">
    <div class="modal-content bg-danger text-white">
      <div class="modal-body">
        Maaf gagal
      </div>
    </div>
  </div>
</div>

<?php
if($this->session->flashdata('pesan')=='sukses'){
    echo'
    <script>
    window.onload = function(){
      var myModal = new bootstrap.Modal(document.getElementById("sukses"))
      myModal.show()
    };
    </script>
    ';
    $this->session->set_flashdata('pesan', '');
}
elseif ($this->session->flashdata('pesan')=='gagal')
{
    echo'
    <script>
    window.onload = function(){
      var myModal = new bootstrap.Modal(document.getElementById("gagal"))
      myModal.show()
    };
    </script>
    ';
    $this->session->set_flashdata('pesan', '');
}
?>
