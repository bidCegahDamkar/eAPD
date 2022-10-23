<?php defined('BASEPATH') OR exit('No direct script access allowed'); ?>

<!doctype html>
<html lang="en">

<head>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
    <meta name="viewport"
        content="width=device-width, initial-scale=1, minimum-scale=1, maximum-scale=1, viewport-fit=cover" />
    <meta name="apple-mobile-web-app-capable" content="yes" />
    <meta name="apple-mobile-web-app-status-bar-style" content="black-translucent">
    <meta name="theme-color" content="#000000">
    <title>Mobilekit Mobile UI Kit</title>
    <?php $base_url = 'http://localhost/eapd-ci/'; ?>
    <meta name="description" content="eAPD Dinas Penanggulangan Kebakaran dan Penyelamatan">
    <meta name="keywords" content="bootstrap 4, mobile template, cordova, phonegap, mobile, html" />
    <link rel="icon" href="<?php echo $base_url; ?>assets/icon/damkar.ico" sizes="32x32" type="ico">
    <link rel="apple-touch-icon" sizes="180x180" href="<?php echo $base_url; ?>assets/vendor/mobilekit/img/icon/192x192.png">
    <link rel="stylesheet" href="<?php echo $base_url; ?>assets/vendor/mobilekit/css/style.css">
    <link rel="manifest" href="<?php echo $base_url; ?>assets/vendor/mobilekit/__manifest.json">
</head>

<body class="bg-white">


    <!-- App Capsule -->
    <div id="appCapsule">

        <div class="error-page">
            <img src="https://medicillin.com/admin/upload/images/information/584b70848889f.png" alt="alt" class="imaged square w200">
            <h1 class="title mt-3">Maaf Komandan, situs dalam perbaikan!</h1>
            <div class="text mb-5">
                Mohon kunjungi lagi setelah tanggal 24 Juni 2022, pukul 18.00 WIB
            </div>

            <div class="fixed-footer">
            <div class="appFooter">
                <img src="<?php echo $base_url; ?>assets/img/logo-eapd.png" alt="icon" class="footer-logo mb-2">
                <div class="footer-title">
                    Copyright © 2021. All Rights Reserved.
                </div>
                <div>Dinas Penanggulangan Kebakaran dan Penyelamatan</div>
                Provinsi DKI Jakarta

                <div class="mt-2">
                    <a href="https://www.youtube.com/c/humasjakfire" target="_blank" class="btn btn-icon btn-sm btn-youtube">
                        <ion-icon name="logo-youtube"></ion-icon>
                    </a>
                    <a href="https://web.facebook.com/humasjakfire?_rdc=1&_rdr" target="_blank" class="btn btn-icon btn-sm btn-facebook">
                        <ion-icon name="logo-facebook"></ion-icon>
                    </a>
                    <a href="https://twitter.com/humasjakfire" target="_blank" class="btn btn-icon btn-sm btn-twitter">
                        <ion-icon name="logo-twitter"></ion-icon>
                    </a>
                    <a href="https://www.instagram.com/humasjakfire/?hl=en" target="_blank" class="btn btn-icon btn-sm btn-instagram">
                        <ion-icon name="logo-instagram"></ion-icon>
                    </a>
                </div>
            </div>
            </div>
        </div>

    </div>
    <!-- * App Capsule -->


    <!-- ///////////// Js Files ////////////////////  -->
    <!-- Jquery -->
    <script src="<?php echo $base_url; ?>assets/vendor/mobilekit/js/lib/jquery-3.4.1.min.js"></script>
    <!-- Bootstrap-->
    <script src="<?php echo $base_url; ?>assets/vendor/mobilekit/js/lib/popper.min.js"></script>
    <script src="<?php echo $base_url; ?>assets/vendor/mobilekit/js/lib/bootstrap.min.js"></script>
    <!-- Ionicons -->
    <script type="module" src="https://unpkg.com/ionicons@5.0.0/dist/ionicons/ionicons.js"></script>


</body>

</html>