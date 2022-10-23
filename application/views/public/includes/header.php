<?php
defined('BASEPATH') OR exit('No direct script access allowed');
?>
<!doctype html>
<html lang="en">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <meta name="description" content="Landing Page Sistem Informasi eAPD">
        <meta name="author" content="kuz1toro@gmail.com">
        <meta name="generator" content="Hugo 0.88.1">
        <link rel="icon" href="<?php echo base_url(); ?>assets/icon/damkar.ico" sizes="32x32" type="ico">
        <link rel="apple-touch-icon" sizes="180x180" href="<?php echo base_url(); ?>assets/vendor/mobilekit/img/icon/192x192.png">
        <link rel="stylesheet" href="<?php echo base_url(); ?>assets/vendor/bootstrap/css/bootstrap.min.css">


        <style>
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

        
        <!-- Custom styles for this template -->
        <link rel="stylesheet" href="<?php echo base_url(); ?>assets/landing_page/carousel.css">
    </head>
    <body>
    
        <header>
            <nav class="navbar navbar-expand-lg navbar-dark bg-dark fixed-top">
                <div class="container">
                <a class="navbar-brand" href="#">
                    <img src="<?php echo base_url(); ?>assets/login/logo_dki.png" height="36">
                        <img src="<?php echo base_url(); ?>assets/login/logo_damkar_dki.png" height="36">
                </a>
                <div class="navbar-text text-white d-none d-md-block">
                    <strong>Dinas Penanggulangan Kebakaran dan Penyelamatan</strong>
                </div>
                <div class="navbar-text text-white d-md-none">
                    <strong>eAPD</strong>
                </div>
                <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarSupportedContent" aria-controls="navbarSupportedContent" aria-expanded="false" aria-label="Toggle navigation">
                    <span class="navbar-toggler-icon"></span>
                </button>
                <div class="collapse navbar-collapse" id="navbarSupportedContent">
                    <ul class="navbar-nav ms-auto">
                    <li class="nav-item">
                        <a class="nav-link active" aria-current="page" href="<?= base_url(); ?>">Home</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="<?= base_url().'auth/login'; ?>">Login</a>
                    </li>
                    </ul>
                </div>
                </div>
            </nav>
        </header>