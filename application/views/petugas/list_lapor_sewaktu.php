<div id="appCapsule">
    <div class="section full mt-1">
        <div class="row mx-2">
            <div class="col-12">
                <a href="<? echo base_url().$controller;?>/lapor_sewaktu" class="btn sm-btn btn-primary rounded shadowed mr-1 mb-1 float-right"> Buat Laporan </a>
            </div>
        </div>
    </div>
    <? //d($list_lap_sewaktu); ?>
    <!-- Iconed Multi Listview -->
    <div class="listview-title ">Daftar Laporan Sewaktu-waktu</div>
        <ul class="listview image-listview">

        <?php
        if (count($list_lap_sewaktu) < 1) {
            echo '
            <li class="multi-level">
                <a href="#" class="item">
                    <div class="icon-box bg-primary">
                        <ion-icon name="megaphone-outline"></ion-icon>
                    </div>
                    <div class="in">
                        <div>Belum ada data laporan sewaktu-waktu</div>
                    </div>
                </a>
            </li>
            ';
        } else {
            foreach ($list_lap_sewaktu as $lap) {
                $jenis_lap = ($lap['jenis_laporan'] == 1) ? 'Kerusakan APD' : 'Kehilangan APD' ;
                $link = ($lap['progress'] == 99) ? ['lapor_sewaktu', 'danger', 'Perbaiki'] : ['lapor_sewaktu_detail', 'info', 'detail'] ;
                echo '
                <li class="multi-level">
                    <a href="#" class="item">
                        <div class="icon-box '.$lap['color'].'">
                            <ion-icon name="'.$lap['icons'].'"></ion-icon>
                        </div>
                        <div class="in">
                            <div>'.$lap['jenis_apd'].'</div>
                        </div>
                    </a>
                    <!-- sub menu -->
                    <ul class="listview simple-listview p-0">
                        <div class="col-12">
                            Merk : '.$lap['merk'].'
                        </div>
                        <div class="col-12">
                            Tahun : '.$lap['tahun'].'
                        </div>
                        <div class="col-12">
                            Jenis Laporan : '.$jenis_lap.'
                        </div>
                        <div class="col-12">
                            Progress : '.$lap['next_step'].' '.$admin.'
                        </div>
                        <div class="col-12">
                            Tanggal Laporan : '.sqlDate2htmlminute($lap['create_at']).'
                        </div>
                        <div class="row">
                            <div class="col-12">
                                <a href="'.base_url().$controller.'/'.$link[0].'/'.$lap['id'].'" class="btn sm-btn btn-'.$link[1].' rounded shadowed my-1 mr-1 float-right">'.$link[2].'</a>
                            </div>
                        </div>
                    </ul>
                    <!-- * sub menu -->
                </li>
                ';
            }
        }
        ?>
        </ul>
        <!-- * Iconed Multi Listview -->
