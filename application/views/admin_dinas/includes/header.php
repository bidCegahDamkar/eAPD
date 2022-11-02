<?php
defined('BASEPATH') OR exit('No direct script access allowed');
?><!DOCTYPE html>
<html lang="en">
  <head>
      <meta charset="utf-8">
      <meta http-equiv="X-UA-Compatible" content="IE=edge">
      <meta name="viewport" content="width=device-width, initial-scale=1">
      <meta name="description" content="Responsive Admin Dashboard Template">
      <meta name="keywords" content="admin,dashboard">
      <meta name="author" content="stacks">
      <!-- The above 6 meta tags *must* come first in the head; any other head content must come *after* these tags -->
      
      <!-- Title -->
      <title>eAPD</title>
      <link rel="icon" href="<?php echo base_url(); ?>assets/icon/damkar.ico" sizes="32x32" type="ico">

      <!-- Styles -->
      <link href="https://fonts.googleapis.com/css?family=Poppins:400,500,700,800&display=swap" rel="stylesheet">

      <link href="<?php echo base_url(); ?>assets/vendor/admin-circle/plugins/bootstrap/css/bootstrap.min.css" rel="stylesheet">
      <link href="<?php echo base_url(); ?>assets/vendor/admin-circle/plugins/font-awesome/css/all.min.css" rel="stylesheet">
      <link href="<?php echo base_url(); ?>assets/vendor/admin-circle/plugins/perfectscroll/perfect-scrollbar.css" rel="stylesheet">
      <link href="<?php echo base_url(); ?>assets/vendor/admin-circle/plugins/apexcharts/apexcharts.css" rel="stylesheet">

      <!-- select2 -->
      <?php
      if(isset($select2)){
        echo '
        <link href="'.base_url().'assets/vendor/select2/css/select2.min.css" rel="stylesheet">   
        ';
      }
      ?>


      <!-- Datatable -->
      <?php
      if(isset($datatable)){
        echo '
        <link href="'.base_url().'assets/vendor/admin-circle/plugins/DataTables/datatables.min.css" rel="stylesheet">   
        <link href="'.base_url().'assets/vendor/datatable/css/buttons.dataTables.min.css" rel="stylesheet">
        <link href="https://cdn.datatables.net/fixedcolumns/4.0.2/css/fixedColumns.dataTables.min.css" rel="stylesheet">
        ';
      }
      ?>

      <?php
      $jab_id = (isset($userData['jabatan_id'])) ? $userData['jabatan_id'] : '' ;
      $user_id_plt = (isset($data_jabatan['plt_id'])) ? $data_jabatan['plt_id'] : '' ;
      ?>

      <?php
      if ($this->uri->segment(2) == 'laporAPD') {
          if(! isset($dataAPD['foto_apd'])) {
              echo '<script>var req = true;</script>';
          }else{
              echo '<script>var req = false;</script>';
          }
      }
      ?>

      <!-- set global variable-->
      <script>var base_url = '<?php echo base_url(); ?>';</script>
      <script>var controller = '<?php echo $controller; ?>';</script>
      <script>var jab_id = '<?php echo $jab_id; ?>';</script>
    
      <!-- Theme Styles -->
      <link href="<?php echo base_url(); ?>assets/vendor/admin-circle/css/mymain.css" rel="stylesheet">
      <link href="<?php echo base_url(); ?>assets/vendor/admin-circle/css/custom.css" rel="stylesheet">

      <!-- HTML5 shim and Respond.js for IE8 support of HTML5 elements and media queries -->
      <!-- WARNING: Respond.js doesn't work if you view the page via file:// -->
      <!--[if lt IE 9]>
      <script src="https://oss.maxcdn.com/html5shiv/3.7.3/html5shiv.min.js"></script>
      <script src="https://oss.maxcdn.com/respond/1.4.2/respond.min.js"></script>
      <![endif]-->
  </head>

  <body>
    <div class='loader'>
      <div class='spinner-grow text-primary' role='status'>
        <span class='sr-only'>Loading...</span>
      </div>
    </div>
    <div class="page-container">
      <div class="page-header">
        <nav class="navbar navbar-expand-lg d-flex justify-content-between">
          <div class="" id="navbarNav">
            <ul class="navbar-nav" id="leftNav">
              <li class="nav-item">
                <a class="nav-link" id="sidebar-toggle" href="#"><i data-feather="menu"></i></a>
              </li>
              <li class="nav-item">
                <a class="nav-link" href="javascript:history.back()"><i data-feather="arrow-left"></i></a>
              </li>
            </ul>
            </div>
            <div class="m-r-sm">
              <h4><strong><span class="text-primary">e</span><span class="text-danger">APD</span></strong></h4>
            </div>
            <div>
            <h4>Admin <?php echo $dinas; ?></h4>
            </div>
            <div class="" id="headerNav">
              <ul class="navbar-nav">
                <li class="nav-item dropdown">
                  <a class="nav-link notifications-dropdown" href="#" id="notificationsDropDown" role="button" data-bs-toggle="dropdown" aria-expanded="false">0</a>
                  <div class="dropdown-menu dropdown-menu-end notif-drop-menu" aria-labelledby="notificationsDropDown">
                    <h6 class="dropdown-header">Notifications</h6>
                    <a href="#">
                      <div class="header-notif">
                        <div class="notif-image">
                          <span class="notification-badge bg-info text-white">
                            <i class="fas fa-bullhorn"></i>
                          </span>
                        </div>
                        <div class="notif-text">
                          <p class="bold-notif-text">Tidak ada notifikasi</p>
                          <!--<small>19:00</small>-->
                        </div>
                      </div>
                    </a>
                    <!--<a href="#">
                      <div class="header-notif">
                        <div class="notif-image">
                          <span class="notification-badge bg-primary text-white">
                            <i class="fas fa-bolt"></i>
                          </span>
                        </div>
                        <div class="notif-text">
                          <p class="bold-notif-text">faucibus dolor in commodo lectus mattis</p>
                          <small>18:00</small>
                        </div>
                      </div>
                    </a>
                    <a href="#">
                      <div class="header-notif">
                        <div class="notif-image">
                          <span class="notification-badge bg-success text-white">
                            <i class="fas fa-at"></i>
                          </span>
                        </div>
                        <div class="notif-text">
                          <p>faucibus dolor in commodo lectus mattis</p>
                          <small>yesterday</small>
                        </div>
                      </div>
                    </a>
                    <a href="#">
                      <div class="header-notif">
                        <div class="notif-image">
                          <span class="notification-badge">
                            <img src="" alt="dsds">
                          </span>
                        </div>
                        <div class="notif-text">
                          <p>faucibus dolor in commodo lectus mattis</p>
                          <small>yesterday</small>
                        </div>
                      </div>
                    </a>
                    <a href="#">
                      <div class="header-notif">
                        <div class="notif-image">
                          <span class="notification-badge">
                            <img src="" alt="sdsd">
                          </span>
                        </div>
                        <div class="notif-text">
                          <p>faucibus dolor in commodo lectus mattis</p>
                          <small>yesterday</small>
                        </div>
                      </div>
                    </a> -->
                  </div>
                </li>
                <li class="nav-item dropdown">
                  <a class="nav-link profile-dropdown" href="#" id="profileDropDown" role="button" data-bs-toggle="dropdown" aria-expanded="false"><img src="<?php echo base_url(); ?>assets/icon/damkar.ico" alt=""></a>
                  <div class="dropdown-menu dropdown-menu-end profile-drop-menu" aria-labelledby="profileDropDown">
                    <a class="dropdown-item" href="<?php echo base_url().$controller; ?>/change_password"><i data-feather="user"></i>Password</a>
                    <div class="dropdown-divider"></div>
                    <a class="dropdown-item" href="<?php echo base_url(); ?>auth/logout"><i data-feather="log-out"></i>Logout</a>
                  </div>
                </li>
              </ul>
            </div>
          </div>
        </nav>
      </div>

      <div class="page-sidebar">
          <ul class="list-unstyled accordion-menu">
            <li class="<? echo $active['homeMenu']; ?>">
              <a href="<?php echo base_url().$controller; ?>/home"><i data-feather="home"></i>Dashboard</a>
            </li>
            <li class="<? echo $active['laporMenu']; ?>">
              <a href="#"><i data-feather="book"></i>Lapor APD<i class="fas fa-chevron-right dropdown-icon"></i></a>
              <ul>
                <li ><a class="<? echo $active['lapor']; ?>" href="<?php echo base_url().$controller; ?>/lapor"><i class="far fa-circle"></i>Lapor</a></li>
                <!-- <li><a class="<? //echo $active['sewaktu']; ?>" href="<?php //echo base_url().$controller; ?>/home"><i class="far fa-circle"></i>Laporan Sewaktu-waktu</a></li> -->
              </ul>
            </li>
            <li class="<? echo $active['verifikasiMenu']; ?>">
              <a href="#"><i data-feather="inbox"></i>Verifikasi & Validasi<i class="fas fa-chevron-right dropdown-icon"></i></a>
              <ul>
                <li ><a class="<? echo $active['verifikasi']; ?>" href="<?php echo base_url().$controller; ?>/verifikasi"><i class="far fa-circle"></i>Verifikasi APD</a></li>
                <!-- <li><a class="<? //echo $active['sewaktu']; ?>" href="<?php //echo base_url().$controller; ?>/home"><i class="far fa-circle"></i>Laporan Sewaktu-waktu</a></li> -->
              </ul>
            </li>
            <li class="<? echo $active['dataMenu']; ?>">
              <a href="#"><i data-feather="user"></i>Data APD<i class="fas fa-chevron-right dropdown-icon"></i></a>
              <ul>
                <li><a class="<? echo $active['apdTerverifikasi']; ?>" href="<?php echo base_url().$controller; ?>/tervalidasi"><i class="far fa-circle"></i>APD Terverifikasi</a></li>
                <li><a class="<? echo $active['apdTertolak']; ?>" href="<?php echo base_url().$controller; ?>/ditolak"><i class="far fa-circle"></i>APD Tertolak</a></li>
                <li><a class="<? echo $active['dataUser']; ?>" href="<?php echo base_url().$controller; ?>/dataUser"><i class="far fa-circle"></i>Data APD Pegawai</a></li>
              </ul>
            </li>
            <li class="<? echo $active['laporanMenu']; ?>">
              <a href="#"><i data-feather="file-text"></i>Laporan<i class="fas fa-chevron-right dropdown-icon"></i></a>
              <ul>
                <li><a class="<? echo $active['rekap']; ?>" href="<?php echo base_url().$controller; ?>/rekap_report"><i class="far fa-circle"></i>Rekapitulasi APD</a></li>
                <li><a class="<? echo $active['detail']; ?>" href="<?php echo base_url().$controller; ?>/detail_report"><i class="far fa-circle"></i>Detail APD</a></li>
                <li><a class="<? echo $active['pdf']; ?>" href="<?php echo base_url().$controller; ?>/list_pdf"><i class="far fa-circle"></i>PDF</a></li>
              </ul>
            </li>
            <li class="<? echo $active['setting']; ?>">
              <a href="#"><i data-feather="gift"></i>Setting<i class="fas fa-chevron-right dropdown-icon"></i></a>
              <ul class="">
                <li><a class="<? echo $active['user_setting']; ?>" href="<?php echo base_url().$controller; ?>/user_setting"><i class="far fa-circle"></i>User</a></li>
                <li><a class="<? echo $active['plt_setting']; ?>" href="<?php echo base_url().$controller; ?>/plt_setting"><i class="far fa-circle"></i>PLT</a></li>
                <li><a class="<? echo $active['in_per_setting']; ?>" href="<?php echo base_url().$controller; ?>/input_periode"><i class="far fa-circle"></i>Periode Input</a></li>
              </ul>
            </li>
            <li class="<? echo $active['data_master_menu']; ?>">
              <a href="#"><i data-feather="database"></i>Data Master<i class="fas fa-chevron-right dropdown-icon"></i></a>
              <ul class="">
                <li><a class="<? echo $active['pos']; ?>" href="<?php echo base_url().$controller; ?>/list_pos"><i class="far fa-circle"></i>Pos</a></li>
                <li><a class="<? echo $active['kondisi']; ?>" href="<?php echo base_url().$controller; ?>/list_master_kondisi"><i class="far fa-circle"></i>Kondisi APD</a></li>
                <li><a class="<? echo $active['merk']; ?>" href="<?php echo base_url().$controller; ?>/list_master_merk"><i class="far fa-circle"></i>Merk APD</a></li>
                <!--<li><a class="<? //echo $active['jenis_apd']; ?>" href="<?php //echo base_url().$controller; ?>/list_jenis_apd"><i class="far fa-circle"></i>Jenis APD</a></li>
                <li><a class="<? //echo $active['jenis_kondisi']; ?>" href="<?php //echo base_url().$controller; ?>/list_jenis_kondisi"><i class="far fa-circle"></i>Jenis Kondisi</a></li>
                <li><a class="<? //echo $active['apd']; ?>" href="<?php //echo base_url().$controller; ?>/list_apd"><i class="far fa-circle"></i>APD</a></li> -->
              </ul>
            </li>
          </ul>
      </div>
