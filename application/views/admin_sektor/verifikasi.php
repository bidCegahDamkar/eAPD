<main class="container">
    <div class="bg-primary text-white mt-2 mb-2 px-5 py-3 rounded">
        <h5 class="card-title">Validasi data APD  </h5>
    </div>
    <div class="card mb-2" >
        <div class="card-body">
            <h5 class="card-title">Data Pegawai</h5>
            <div class="row">
                <div class="col-2">Nama</div>
                <div class="col">: <? echo $userData['nama']; ?></div>
            </div>
            <div class="row">
                <div class="col-2">NRK/ NIP</div>
                <div class="col">: <? echo $userData['NRK'].'/ '.$userData['NIP']; ?></div>
            </div>
            <div class="row">
                <div class="col-2">Status</div>
                <div class="col">: <? echo $userData['status']; ?></div>
            </div>
            <div class="row">
                <div class="col-2">Tempat Tugas</div>
                <div class="col">: <? echo $userData['nama_pos']; ?></div>
            </div>
            <div class="row">
                <div class="col-2">Jabatan</div>
                <div class="col">: <? echo $userData['nama_jabatan']; ?></div>
            </div>
        </div>
    </div>
    <div class="row">
        <div class="col">

        </div>
        <div class="col">
            
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
            <div class="row">
                <div class="col-3 text-muted">
                    '.($jmlInputApd+$jumApdTerverifikasi+$jumApdDitolak).' dari '.$jumJenisApd.' APD telah diinput
                </div>
                <div class="col-9">
                    <div class="progress">
                        <div class="progress-bar bg-primary" role="progressbar" style="width: '.$persenInputApd.'%;" aria-valuenow="'.$persenInputApd.'" aria-valuemin="0" aria-valuemax="100">'.$persenInputApd.'%</div>
                    </div>
                </div>
            </div>
            <div class="row">
                <div class="col-3 text-muted">
                    '.$jumApdTerverifikasi.' dari '.$jumJenisApd.' APD telah terverifikasi
                </div>
                <div class="col-9">
                    <div class="progress">
                        <div class="progress-bar bg-success" role="progressbar" style="width: '.$persenVerfApd.'%;" aria-valuenow="'.$persenVerfApd.'" aria-valuemin="0" aria-valuemax="100">'.$persenVerfApd.'%</div>
                    </div>
                </div>
            </div>
            <div class="row">
                <div class="col-3 text-muted">
                    '.$jumApdDitolak.' APD ditolak
                </div>
                <div class="col-9">
                    <div class="progress">
                        <div class="progress-bar bg-danger" role="progressbar" style="width: '.$persenApdDitolak.'%;" aria-valuenow="'.$persenApdDitolak.'" aria-valuemin="0" aria-valuemax="100">'.$persenApdDitolak.'%</div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    ';
    //d($dhead_ada);
    foreach ($listAPD as $apd) {
        $foto = (! is_null($apd['foto_apd']) && $apd['mkp_id'] == 1 ) ? $apd['foto_apd'] : 'no-preview.jpg' ;
        echo '
        <div class="card mb-2" >
            <div class="card-body">
                <h5 class="card-title">'.$apd['jenis_apd'].'</h5>
                <div class="row">
                    <div class="col-6" >
                        <img src="'.base_url().'upload/petugas/APD/'.$foto.'" class="img-fluid" alt="kuswan">
                    </div>
                    <div class="col-6">
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
                            ';
                        }
                $attributes = array('class' => 'needs-validation', 'novalidate' => 'novalidate');
                echo form_open('admin_sektor/verifikasiAPD/'.$UserID, $attributes);
        echo '                   
                        <div class="form-group boxed mx-3 mt-4">
                            <input type="text" name="apd_id" value="'.$apd['id'].'" hidden>
                            <div class="input-wrapper ">
                                <label class="label" for="pesan">Pesan/ Catatan :</label>
                                <textarea id="pesan" name="pesan" rows="2" class="form-control"></textarea>
                            </div>
                            <div class="mb-3 mt-2" >
                                <label for="exampleFormControlInput1" class="form-label">Validasi laporan ini?</label>
                                <div class="form-check">
                                    <input type="radio" class="form-check-input" name="verifikasi" id="setuju'.$apd['id'].'" value="1" required>
                                    <label class="form-check-label" for="setuju'.$apd['id'].'">Ya</label>
                                </div>
                                <div class="form-check">
                                    <input type="radio" class="form-check-input" name="verifikasi" id="tolak'.$apd['id'].'" value="0" required>
                                    <label class="form-check-label" for="tolak'.$apd['id'].'">Tidak</label>
                                </div>
                            </div>
                            <div class="d-grid gap-2 d-md-flex justify-content-md-end">
                                <button class="btn btn-primary" type="submit">Simpan</button>
                            </div>
                        </div>
                    </form>
                    </div>
                </div>
            </div>
        </div>
    ';
    }
   ?>

</main>