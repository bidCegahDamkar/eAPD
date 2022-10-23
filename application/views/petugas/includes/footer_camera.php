        


    <!-- App Sidebar -->
    <div class="modal fade panelbox panelbox-left" id="sidebarPanel" tabindex="-1" role="dialog">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-body p-0">

                    <!-- profile box -->
                    <div class="profileBox">
                        <div class="image-wrapper">
                            <img src="<?php echo base_url().$avatar ?>" alt="image" class="imaged rounded">
                        </div>
                        <div class="in">
                            <strong><?php echo $username ?></strong>
                            <div class="text-muted">
                                <?php echo $jabatan['nama_jabatan'] ?>
                            </div>
                            <div class="text-muted">
                                <?php echo $penempatan['nama_pos'] ?>
                            </div>
                        </div>
                    </div>
                    <!-- * profile box -->
                    <div class="list-sidebar-kus">
                    <ul class="listview flush transparent no-line image-listview">
                        <li>
                            <a href="<?php echo base_url().$controller; ?>/home" class="item">
                                <div class="icon-box bg-primary">
                                    <ion-icon name="home"></ion-icon>
                                </div>
                                <div class="in">
                                    Dashboard
                                </div>
                            </a>
                        </li>
                        <li>
                            <a href="<?php echo base_url().$controller; ?>/profile" class="item">
                                <div class="icon-box bg-primary">
                                    <ion-icon name="person"></ion-icon>
                                </div>
                                <div class="in">
                                    Profil
                                </div>
                            </a>
                        </li>
                        <li>
                            <a href="<?php echo base_url().$controller; ?>/list_lapor_sewaktu" class="item">
                                <div class="icon-box bg-warning">
                                    <ion-icon name="infinite"></ion-icon>
                                </div>
                                <div class="in">
                                    Laporan Sewaktu-waktu
                                </div>
                            </a>
                        </li>
                        <?php
                        if($controller == 'eselon_4' )
                        {
                            echo '
                            <li>
                                <a class="item" data-toggle="collapse" href="#subMenu" role="button" aria-expanded="false" aria-controls="subMenu">
                                    <div class="icon-box bg-primary">
                                        <ion-icon name="shield"></ion-icon>
                                    </div>
                                    <div class="in">
                                        Verifikasi&Validasi
                                    </div>
                                </a>
                                <div class="collapse" id="subMenu">
                                    <ul class="listview flush transparent no-line image-listview ml-2">
                                        <li>
                                            <a href="'.base_url().$controller.'/verifikasi" class="item">
                                                <div class="icon-box bg-warning">
                                                    <ion-icon name="infinite"></ion-icon>
                                                </div>
                                                <div class="in">
                                                    Verifikasi
                                                </div>
                                            </a>
                                        </li>
                                        <li>
                                            <a href="'.base_url().$controller.'/tervalidasi" class="item">
                                                <div class="icon-box bg-success">
                                                    <ion-icon name="shield-checkmark"></ion-icon>
                                                </div>
                                                <div class="in">
                                                    Terverifikasi
                                                </div>
                                            </a>
                                        </li>
                                        <li>
                                            <a href="'.base_url().$controller.'/ditolak" class="item">
                                                <div class="icon-box bg-danger">
                                                    <ion-icon name="remove-circle"></ion-icon>
                                                </div>
                                                <div class="in">
                                                    Tertolak
                                                </div>
                                            </a>
                                        </li>
                                        <li>
                                            <a href="'.base_url().$controller.'/lap_sewaktu" class="item">
                                                <div class="icon-box bg-warning">
                                                    <ion-icon name="infinite"></ion-icon>
                                                </div>
                                                <div class="in">
                                                    Laporan Sewaktu2
                                                </div>
                                            </a>
                                        </li>
                                    </ul>
                                </div>
                            </li>
                            ';
                        }
                        if($controller == 'danton' || $controller == 'danton_bid_penyelamat' || $controller == 'eselon_4')
                        {
                            echo '
                            <li>
                                <a href="'.base_url().$controller.'/monitoring" class="item">
                                    <div class="icon-box bg-info">
                                        <ion-icon name="eye"></ion-icon>
                                    </div>
                                    <div class="in">
                                        Monitoring
                                    </div>
                                </a>
                            </li>
                            ';
                        }
                        if($controller == 'danton')
                        {
                            echo '
                            <li>
                                <a href="'.base_url().$controller.'/report_rekap_APD" class="item">
                                    <div class="icon-box bg-success">
                                        <ion-icon name="bar-chart-outline"></ion-icon>
                                    </div>
                                    <div class="in">
                                        Laporan
                                    </div>
                                </a>
                            </li>
                            ';
                        }else if($controller == 'eselon_4')
                        {
                            echo '
                            <li>
                                <a class="item" data-toggle="collapse" href="#report123" role="button" aria-expanded="false" aria-controls="report123">
                                    <div class="icon-box bg-primary">
                                        <ion-icon name="bar-chart-outline"></ion-icon>
                                    </div>
                                    <div class="in">
                                        Laporan
                                    </div>
                                </a>
                                <div class="collapse" id="report123">
                                    <ul class="listview flush transparent no-line image-listview ml-2">
                                        <li>
                                            <a href="'.base_url().$controller.'/report_rekap_APD" class="item">
                                                <div class="icon-box bg-warning">
                                                    <ion-icon name="infinite"></ion-icon>
                                                </div>
                                                <div class="in">
                                                    Rekap Data APD
                                                </div>
                                            </a>
                                        </li>
                                        <li>
                                            <a href="'.base_url().$controller.'/list_pdf" class="item">
                                                <div class="icon-box bg-success">
                                                    <ion-icon name="shield-checkmark"></ion-icon>
                                                </div>
                                                <div class="in">
                                                    Laporan PDF
                                                </div>
                                            </a>
                                        </li>
                                    </ul>
                                </div>
                            </li>
                            ';
                        }
                        ?>
                        <li>
                            <div class="item">
                                <div class="icon-box bg-secondary">
                                    <ion-icon name="moon"></ion-icon>
                                </div>
                                <div class="in">
                                    <div>Dark Mode</div>
                                    <div class="custom-control custom-switch">
                                        <input type="checkbox" class="custom-control-input dark-mode-switch"
                                            id="darkmodesidebar">
                                        <label class="custom-control-label" for="darkmodesidebar"></label>
                                    </div>
                                </div>
                            </div>
                        </li>
                        <li>
                            <a href="app-pages.html" class="item" data-toggle="modal" data-target="#logoutActionSheet">
                                <div class="icon-box bg-danger">
                                    <ion-icon name="log-out"></ion-icon>
                                </div>
                                <div class="in">
                                    <div>Logout</div>
                                </div>
                            </a>
                        </li>
                    </ul>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <!-- * App Sidebar -->


    <!-- Logout Action Sheet -->
    <div class="modal fade dialogbox" id="logoutActionSheet" data-backdrop="static" tabindex="-1" role="dialog">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Yakin keluar ?</h5>
                </div>
                <div class="modal-footer">
                    <div class="btn-inline">
                        <a href="#" class="btn btn-text-success" data-dismiss="modal"><ion-icon name="close-outline"></ion-icon> Tidak</a>
                        <a href="<?php echo base_url(); ?>auth/logout" class="btn btn-text-danger" ><ion-icon name="log-out"></ion-icon> Ya</a>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <!-- * Logout Action Sheet -->

    <!-- FAB bottom right -->
    <div class="fab-button animate bottom-right dropdown">
        <a href="#" class="fab" data-toggle="dropdown">
            <ion-icon name="apps"></ion-icon>
        </a>
        <div class="dropdown-menu">
            <a class="dropdown-item" href="<?php echo base_url().$controller; ?>/home">
                <ion-icon name="home-outline"></ion-icon>
                <p>Dashboard</p>
            </a>
            <a class="dropdown-item" href="<?php echo base_url().$controller; ?>/profile">
                <ion-icon name="person-outline"></ion-icon>
                <p>Profil</p>
            </a>
            <a class="dropdown-item" href="<?php echo base_url().$controller; ?>/my_apd">
                <ion-icon name="color-filter-outline"></ion-icon>
                <p>Data APD ku</p>
            </a>
            <a class="dropdown-item" href="javascript: history.back()">
                <ion-icon name="arrow-back-outline"></ion-icon>
                <p>Kembali ke Halaman Sebelumnya</p>
            </a>
        </div>
    </div>
        <!-- * bottom right -->



    <!-- welcome notification  -->
    <?php 
        if($pageTitle == 'Dashboard125'){
            echo '
            <div id="notification-welcome1" class="notification-box">
                <div class="notification-dialog android-style">
                    <div class="notification-header">
                        <div class="in">
                            <img src="'.base_url().'assets/mobilekit/img/icon/72x72.png" alt="image" class="imaged w24">
                            <strong>Mobilekit</strong>
                            <span>just now</span>
                        </div>
                        <a href="#" class="close-button">
                            <ion-icon name="close"></ion-icon>
                        </a>
                    </div>
                    <div class="notification-content">
                        <div class="in">
                            <h3 class="subtitle">Welcome to Mobilekit</h3>
                            <div class="text">
                                Mobilekit is a PWA ready Mobile UI Kit Template.
                                Great way to start your mobile websites and pwa projects.
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <script>
                setTimeout(() => {
                    notification("notification-welcome", 5000);
                }, 2000);
            </script>
            ';
        }
    ?>
    <!-- * welcome notification -->

    <p class="footer">Page rendered in <strong>{elapsed_time}</strong> seconds. <?php echo  (ENVIRONMENT === 'development') ?  'CodeIgniter Version <strong>' . CI_VERSION . '</strong>' : '' ?></p>

</div>
<!-- * App Capsule -->

    <!-- ///////////// Js Files ////////////////////  -->
    <!-- Jquery -->
    <script src="<?php echo base_url(); ?>assets/vendor/mobilekit/js/lib/jquery-3.4.1.min.js"></script>
    <!-- Bootstrap-->
    <script src="<?php echo base_url(); ?>assets/vendor/mobilekit/js/lib/popper.min.js"></script>
    <script src="<?php echo base_url(); ?>assets/vendor/mobilekit/js/lib/bootstrap.min.js"></script>
    <!-- Ionicons -->
    <script type="module" src="https://unpkg.com/ionicons@5.0.0/dist/ionicons/ionicons.js"></script>
    <!-- Owl Carousel -->
    <script src="<?php echo base_url(); ?>assets/vendor/mobilekit/js/plugins/owl-carousel/owl.carousel.min.js"></script>
    <!-- jQuery Circle Progress -->
    <script src="<?php echo base_url(); ?>assets/vendor/mobilekit/js/plugins/jquery-circle-progress/circle-progress.min.js"></script>
    <!-- compressor-->
    <script src="<?php echo base_url(); ?>assets/vendor/compressor/compressor.min.js"></script>
    <!-- Base Js File -->
    <script src="<?php echo base_url(); ?>assets/vendor/mobilekit/js/base.js"></script>
    <!-- Costume Js File -->
    <script src="<?php echo base_url(); ?>assets/petugas/js/costum.js"></script>
    <script>var base_url ="<?php echo base_url(); ?>"</script>
    <!-- lapor APD -->
    <?
        if($this->uri->segment(2) == 'laporAPD'){
            echo '
                <script src="'.base_url().'assets/petugas/js/laporAPD.js"></script>
            ';
        }

        if($this->uri->segment(2) == 'lapor_sewaktu'){
            echo '
                <script src="'.base_url().'assets/vendor/bootstrap-datepicker/js/bootstrap-datepicker.min.js"></script>
                <script src="'.base_url().'assets/vendor/bootstrap-datepicker/locales/bootstrap-datepicker.id.min.js"></script>
                <script>var list_item_rusak = '.$listAPDRusak.';</script>
                <script>var list_item_hilang = '.$listAPDHilang.';</script>
                <script src="'.base_url().'assets/petugas/js/lapor_sewaktu.js"></script>
            ';
        }

        if (isset($highchart)) {
            echo '
            <script src="https://code.highcharts.com/highcharts.js"></script>
            
            <script>var dataChart = '.json_encode($result).';</script>
            <script src="'.base_url().'assets/petugas/js/report_chart.js"></script>
            ';
        }
    ?>

</body>

</html>