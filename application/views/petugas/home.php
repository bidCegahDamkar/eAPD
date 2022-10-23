<!-- App Capsule -->
<div id="appCapsule">

    <div class="card text-white bg-danger my-2 mx-1">
        <div class="card-body">
            <?php $retVal = ($controller == 'danton') ? $group_piket : '' ; ?>
            <h1 class="title text-white">Selamat Datang</h1>
            <h4 class="subtitle text-white mb-0"><?php echo $username; ?></h4>
            <h4 class="subtitle text-white mb-0"><?php echo $jabatan['nama_jabatan'].' '.$retVal; ?></h4>
            <h4 class="subtitle text-white mb-0"><?php echo $penempatan['nama_pos']; ?></h4>
        </div>
    </div>

    <?php
    if ($controller == 'eselon_4' && isset($data_sektor)) {
        $jmlPNS = $data_sektor['jml_pns'];
        $jmlPJLP = $data_sektor['jml_pjlp'];
        $jumTotApdTerinput = $data_sektor['jml_input'];
        $jmlVerApd = $data_sektor['jml_verif'];
        $jmlRefuseApd = $data_sektor['jml_ditolak'];
        if ($jmlApd == 0) {
            $persenInput = 0;
            $persenValidate = 0;
        } else {
            $persenInput = round(($jumTotApdTerinput/$jmlApd*100), 1);
            $persenValidate = round(($jmlVerApd/$jmlApd*100), 1);
        }

        echo '
        <div class="card text-white bg-primary my-2 mx-1">
            <div class="card-body">
                <h5 class="card-title">Rekap Data Petugas di sektor/ seksi anda:</h5>
                <p class="mb-0">Jumlah PNS : '.$jmlPNS.' orang</p>
                <p class="mb-0">Jumlah PJLP : '.$jmlPJLP.' orang</p>
                <p class="mb-0">Jumlah Total : '.($jmlPNS+$jmlPJLP).' orang</p>
            </div>
        </div>

        <div class="card text-white bg-secondary my-2 mx-1">
            <div class="card-body">
                <h5 class="card-title">Rekap Progress Input eAPD di sektor/ seksi anda:</h5>
                <p class="mb-0">Jumlah Total APD : '.$jmlApd.' APD</p>
                <p class="mb-0">Jumlah APD terinput : '.$jumTotApdTerinput.' APD</p>
                <p class="mb-0">Jumlah APD tervalidasi : '.$jmlVerApd.' APD</p>
                <p class="mb-0">Jumlah APD ditolak : '.$jmlRefuseApd.' APD</p>
                <p class="mb-0">Persentase Input APD di sektor anda: ('.$persenInput.' %)</p>
                <div class="progress mb-1">
                    <div class="progress-bar bg-primary" role="progressbar" style="width: '.$persenInput.'%;" aria-valuenow="'.$persenInput.'"
                        aria-valuemin="0" aria-valuemax="100"></div>
                </div>
                <p class="mb-0">Persentase APD Tervalidasi di sektor anda: ('.$persenValidate.' %)</p>
                <div class="progress mb-1">
                    <div class="progress-bar bg-success" role="progressbar" style="width: '.$persenValidate.'%;" aria-valuenow="'.$persenValidate.'"
                        aria-valuemin="0" aria-valuemax="100"></div>
                </div>
            </div>
        </div>
        ';
    }
    ?>
    

    <div class="card text-white bg-info my-2 mx-1">
        <div class="card-body">
            <? $retVal = ($is_open) ? 'dibuka' : 'ditutup' ; ?>
            <h5 class="card-title">Periode Pemutakhiran data APD <? echo $retVal; ?> </h5>
                <p><? echo $info_periode_input ?></p>
        </div>
    </div>

    <? //d($user_roles); echo 'Current PHP version: ' . phpversion();?>
    <? 
    if ($is_plt) {
        $disable = 'disabled' ;
        $disable_myapd = 'disabled' ;
    } else {
        $disable = ($is_open) ? '' : 'disabled' ;
        $disable_myapd = '' ;
    }
    
    ?>

    <div class="card bg-dark text-white mx-1">
        <img src="<?php echo base_url(); ?>assets/img/petugas/home1.png" class="card-img overlay-img" alt="image">
        <div class="card-img-overlay">
            <h5 class="card-title">Lapor APD</h5>
            <p class="card-text">
                Menu untuk melaporkan kepemilikan, jenis dan kondisi APD
            </p>
            <a href="<?php echo base_url().$controller; ?>/lapor" class="btn btn-primary <? echo $disable; ?>" style="position: absolute; bottom: 20px; left: 10;">
                <ion-icon name="paper-plane"></ion-icon>
                Lapor
            </a>
        </div>
    </div>

    <div class="card bg-dark text-white mx-1 mt-2">
        <img src="<?php echo base_url(); ?>assets/img/petugas/home2.jpg" class="card-img overlay-img" alt="image">
        <div class="card-img-overlay">
            <h5 class="card-title">Data APD</h5>
            <p class="card-text">
                Menu untuk melihat status dan data APD yang telah dilaporkan
            </p>
            <a href="<?php echo base_url().$controller; ?>/my_apd" class="btn btn-primary <? echo $disable_myapd; ?>" style="position: absolute; bottom: 20px; left: 10;">
                <ion-icon name="layers-outline"></ion-icon>
                APD ku
            </a>
        </div>
    </div>

    <!-- app footer -->
    <?php 
        if($pageTitle == 'Dashboard'){
            echo '
            <div class="appFooter">
                <img src="'.base_url().'assets/img/logo-eapd.png" alt="icon" class="footer-logo mb-2">
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
            ';
        }
        ?>
        <!-- * app footer -->

    </div>
    <!-- * App Capsule -->