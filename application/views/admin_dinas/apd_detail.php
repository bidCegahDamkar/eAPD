<div class="page-content">
    <div class="main-wrapper">
        <? //d($controller); ?>
        <div class="row">

        <div class="card mb-2" >
            <div class="card-body">
                <h5 class="card-title">Data Pegawai</h5>
                <div class="row">
                    <div class="col-3">Nama</div>
                    <div class="col">: <? echo $userData['nama']; ?></div>
                </div>
                <div class="row">
                    <div class="col-3">NRK/ NIP</div>
                    <div class="col">: <? echo $userData['NRK'].'/ '.$userData['NIP']; ?></div>
                </div>
                <div class="row">
                    <div class="col-3">Status</div>
                    <div class="col">: <? echo $userData['status']; ?></div>
                </div>
                <div class="row">
                    <div class="col-3">Tempat Tugas</div>
                    <div class="col">: <? echo $userData['nama_pos']; ?></div>
                </div>
                <div class="row">
                    <div class="col-3">Jabatan</div>
                    <div class="col">: <? echo $userData['nama_jabatan']; ?></div>
                </div>
            </div>
        </div>
        <?php
        //$jmlInputApd = count($listAPD);
        $persenInputApd = round(($jumInputApd+$jumApdTerverifikasi+$jumApdDitolak)/$jumJenisApd*100, 0);
        $persenVerfApd = round($jumApdTerverifikasi/$jumJenisApd*100, 0);
        $persenApdDitolak = round($jumApdDitolak/$jumJenisApd*100, 0);
        echo '
        <div class="card mb-2" >
            <div class="card-body">
                <h5 class="card-title">Progress input APD</h5>
                <p class="mb-0">'.($jumInputApd+$jumApdTerverifikasi+$jumApdDitolak).' dari '.$jumJenisApd.' APD telah diinput</p>
                <div class="progress mb-1">
                    <div class="progress-bar bg-primary" role="progressbar" style="width: '.$persenInputApd.'%;" aria-valuenow="'.$persenInputApd.'"
                        aria-valuemin="0" aria-valuemax="100">'.$persenInputApd.'%</div>
                </div>

                <p class="mb-0">'.$jumApdTerverifikasi.' dari '.$jumJenisApd.' APD telah terverifikasi</p>
                <div class="progress mb-1">
                    <div class="progress-bar bg-success" role="progressbar" style="width: '.$persenVerfApd.'%;" aria-valuenow="'.$persenVerfApd.'"
                        aria-valuemin="0" aria-valuemax="100">'.$persenVerfApd.'%</div>
                </div>

                <p class="mb-0">'.$jumApdDitolak.' APD ditolak</p>
                <div class="progress mb-1">
                    <div class="progress-bar bg-danger" role="progressbar" style="width: '.$persenApdDitolak.'%;" aria-valuenow="'.$persenApdDitolak.'"
                        aria-valuemin="0" aria-valuemax="100">'.$persenApdDitolak.'%</div>
                </div>

            </div>
        </div>
        ';
        //d($dhead_ada);
        foreach ($listAPD as $apd) {
            //$foto = (! is_null($apd['foto_apd']) && $apd['mkp_id'] == 1 ) ? $apd['foto_apd'] : 'no-preview.jpg' ;
            echo '
            <div class="card mb-2" >
                <div class="card-body">
                    <h5 class="card-title">'.$apd['jenis_apd'].' <ion-icon color="'.$icon[1].'" name="'.$icon[0].'-circle"></ion-icon></h5>';
                if (! is_null($apd['foto_apd']) ) {
                    echo '
                    <div class="row">
                        <div class="col-12" >
                            <img src="'.base_url().'upload/petugas/APD/'.$apd['foto_apd'].'" class="img-fluid" alt="kuswan">
                        </div>
                    </div>
                    ';
                }
            echo '
                    <div class="row">
                        <div class="col-12">
                            <ul class="list-group list-group-flush">';
                            if($apd['mkp_id'] != 3){
                                foreach ($dhead_ada as $dhead) {
                                    echo '
                                    <li class="list-group-item">'.$dhead[0].' : '.$apd[$dhead[1]].'</li>
                                    ';
                                }
                            }else{
                                echo '
                                <li class="list-group-item">Keberadaan : '.$apd['keberadaan'].'</li>
                                <li class="list-group-item">Pesan Admin : '.$apd['admin_message'].'</li>
                                ';
                            }
                            //$phpdate = strtotime( $mysqldate );
                            $tglCreate = date( 'd-m-Y H:i:s', strtotime($apd['created_at']) );
                            //$update = is_null();
                            $tglUpdate = date( 'd-m-Y H:i:s', strtotime($apd['updated_at']) );
                            echo '
                                <li class="list-group-item">Tanggal Input : '.$tglCreate.'</li>
                                ';
                            if(! is_null($apd['updated_at'])){
                                echo '
                                <li class="list-group-item">Tanggal Update : '.$tglUpdate.'</li>
                            </ul>
                                ';
                            }
            echo '                   
                        </div>
                    </div>
                </div>
            </div>
        ';
        }
    ?>

</div>
    </div>