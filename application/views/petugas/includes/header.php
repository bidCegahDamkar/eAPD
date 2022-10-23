<?php
defined('BASEPATH') OR exit('No direct script access allowed');
?><!DOCTYPE HTML>
<html lang="en" class="notranslate" translate="no">

<head>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
    <meta name="viewport"
        content="width=device-width, initial-scale=1, minimum-scale=1, maximum-scale=1, viewport-fit=cover" />
    <meta name="apple-mobile-web-app-capable" content="yes" />
    <meta name="apple-mobile-web-app-status-bar-style" content="black-translucent">
    <meta name="theme-color" content="#000000">
    <title>eAPD</title>
    <meta name="description" content="eAPD Dinas Penanggulangan Kebakaran dan Penyelamatan">
    <meta name="keywords" content="bootstrap 4, mobile template, cordova, phonegap, mobile, html" />
    <meta name="google" content="notranslate" />
    <meta name="author" content="Tim eAPD 2021">
    <link rel="icon" href="<?php echo base_url(); ?>assets/icon/damkar.ico" sizes="32x32" type="ico">
    <link rel="apple-touch-icon" sizes="180x180" href="<?php echo base_url(); ?>assets/vendor/mobilekit/img/icon/192x192.png">
    <link rel="stylesheet" href="<?php echo base_url(); ?>assets/vendor/mobilekit/css/style.css">
    <link rel="manifest" href="<?php echo base_url(); ?>assets/vendor/mobilekit/__manifest.json">
    <?php
    if ($this->uri->segment(2) == 'laporAPD') {
        if(! isset($dataAPD['foto_apd'])) {
            echo '<script>var req = true;</script>';
        }else{
            echo '<script>var req = false;</script>';
        }
    }

    if($this->uri->segment(2) == 'lapor_sewaktu'){
        echo '
            <link rel="stylesheet" href="'.base_url().'assets/vendor/bootstrap-datepicker/css/bootstrap-datepicker.min.css">
        ';
    }

    
    ?>
    <!-- set global variable-->
    <script>var base_url = '<?php echo base_url(); ?>';</script>
    <script>var controller = '<?php echo $controller; ?>';</script>

</head>

<body>
    <!-- toast notifikasi -->
    <?php
    if($this->session->flashdata('flash_message')=='sukses'){
        echo'<script>
        window.onload = function(){
            toastbox("toast-sukses", 2000);
        };
        </script>';
        $this->session->set_flashdata('flash_message', '');
    }
    elseif ($this->session->flashdata('flash_message')=='gagal')
    {
        echo'<script>
        window.onload = function(){
            toastbox("toast-gagal", 2000);
        };
        </script>';
        $this->session->set_flashdata('flash_message', '');
    }
    ?>
    <div id="toast-sukses" class="toast-box toast-top bg-success" style="z-index:20000;">
        <div class="in">
            <div class="text-white">
                Sukses
            </div>
        </div>
    </div>
    <div id="toast-gagal" class="toast-box toast-top bg-danger" style="z-index:20000;">
        <div class="in">
            <div class="text-white">
                Gagal, ada kesalahan
            </div>
        </div>
    </div>
    <!-- toast notifikasi -->

    <!-- loader -->
    <div id="loader">
        <div class="spinner-border text-info" role="status"></div>
    </div> 
    <!-- * loader -->

    <!-- App Header -->
    <div class="appHeader bg-primary">
        <div class="left">
            <img src="<?php echo base_url(); ?>assets/login/logo_dki.png" alt="" class="imaged w24 ml-1 mr-1">
		    <img src="<?php echo base_url(); ?>assets/login/logo_damkar_dki.png" class="imaged w24">
        </div>
        <div class="pageTitle">
            <? echo $pageTitle ?>
        </div>
        <div class="right dropdown">
            <a href="#" class="headerButton bg-primary" data-toggle="dropdown">
                <ion-icon name="notifications-outline"></ion-icon>
                <!--<span class="badge badge-danger">5</span>-->
            </a>
            <div class="dropdown-menu dropdown-menu-right">
                <div class="text">
                    Notifikasi
                </div>
                <!-- <a class="dropdown-item" href="#"><ion-icon name="alert-circle" color="danger"></ion-icon>Copy sdadssadsasdasda dsadsadas dasdsad</a> -->
                <a class="dropdown-item" href="#"><ion-icon name="checkmark-circle" color="success"></ion-icon>Tidak ada notifikasi</a>
                <!-- <div class="dropdown-divider"></div>
                <a class="dropdown-item" href="#">Delete</a>  -->
            </div>
            <a href="#" class="headerButton bg-primary" data-toggle="modal" data-target="#sidebarPanel">
                <ion-icon name="menu-outline"></ion-icon>
            </a>
        </div>
    </div>
    <!-- * App Header -->

    
