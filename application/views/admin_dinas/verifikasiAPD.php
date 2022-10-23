<div class="page-content">
    <div class="main-wrapper">
        <? //d($controller); ?>
    <div class="row">
        <div class="card mb-2 bg-primary">
            <div class="card-body text-white">
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
        $jmlInputApd = count($listAPD);
        $persenInputApd = round(($jmlInputApd+$jumApdTerverifikasi+$jumApdDitolak)/$jumJenisApd*100, 0);
        $persenVerfApd = round($jumApdTerverifikasi/$jumJenisApd*100, 0);
        $persenApdDitolak = round($jumApdDitolak/$jumJenisApd*100, 0);
        echo '
        <div class="card mb-2" >
            <div class="card-body">
                <h5 class="card-title">Progress input APD</h5>
                <p class="mb-0">'.($jmlInputApd+$jumApdTerverifikasi+$jumApdDitolak).' dari '.$jumJenisApd.' APD telah diinput</p>
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
                    <h5 class="card-title">'.$apd['jenis_apd'].'</h5>';
            if (! is_null($apd['foto_apd']) ) {
                echo '
                <div class="row">
                    <div class="col-12" >
                        <img src="'.base_url().'upload/petugas/APD/'.$apd['foto_apd'].'" class="img-fluid" alt="">
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
                        </div>
                    </div>';
                            }
                    $attributes = array('class' => 'needs-validation', 'novalidate' => 'novalidate');
                    echo form_open($controller.'/verifikasiAPD/'.$UserID, $attributes);
                    echo '                   
                        <div class="form-group boxed mt-2">
                            <input type="text" name="apd_id" value="'.$apd['id'].'" hidden>
                            <div class="input-wrapper ">
                                <label class="label" for="pesan">Pesan/ Catatan :</label>
                                <textarea id="pesan" name="pesan" rows="2" class="form-control"></textarea>
                            </div>

                            <div class="wide-block p-0 mt-2 mb-2">
                                <div class="input-list">
                                    <label for="exampleFormControlInput1" class="form-label">Validasi laporan ini?</label>
                                    <div class="custom-control custom-radio">
                                        <input type="radio" id="setuju'.$apd['id'].'" name="verifikasi" class="custom-control-input" value="1" required>
                                        <label class="custom-control-label" for="setuju'.$apd['id'].'">Ya</label>
                                    </div>
                                    <div class="custom-control custom-radio">
                                        <input type="radio" id="tolak'.$apd['id'].'" name="verifikasi" class="custom-control-input" value="0" required>
                                        <label class="custom-control-label" for="tolak'.$apd['id'].'">Tidak</label>
                                    </div>
                                </div>
                            </div>

                            <div class="d-grid gap-2 d-md-flex justify-content-md-end">
                                <button class="btn btn-primary" type="submit">Simpan</button>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
            </div></div>
        ';
        }
    ?>


    </div>
