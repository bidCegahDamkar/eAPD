    <div class="page-content">
      <div class="main-wrapper">
      <?php
      //d($masterAPD, $listKeberadaan, $listKondisi, $listUkuran);
      ?>

      <?php 
        $attributes = array('class' => 'needs-validation', 'novalidate' => 'novalidate');
        echo form_open_multipart($controller.'/laporAPD/'.$jenisApd['id_mj'], $attributes);
        echo '
        <div class="row">
          <div class="col">
              <div class="card">
                  <div class="card-body">
                      <h5 class="card-title">'.$pageTitle.'</h5>
                      <form>
                          <div class="row mb-3">
                            <label class="label col-2" for="'.$dhead[1].'">'.$thead[1].'</label>
                            <div class="col-10">
                              <select class="form-control custom-select" id="'.$dhead[1].'" name="'.$dhead[1].'" required>
                                <option selected disabled value="">Pilih Salah Satu</option>
                                ';
                              foreach ($listKeberadaan as $key){
                                echo '
                                <option value="'.$key['id_mkp'].'"> '.$key['keberadaan'].'</option>
                                ';
                              }
                              echo '
                                </select>
                                <div class="valid-feedback">86</div>
                                <div class="invalid-feedback">Harus diisi</div>
                              </div>
                            </div>
                            ';
                            
                            echo '
                            <div class="form-group boxed mb-3">
                                <div class="row input-wrapper refi hide-kus">
                                    <label class="label col-2" for="'.$dhead[0].'">'.$thead[0].'</label>
                                    <div class="col-10">
                                      <select class="form-control custom-select refi-select" id="'.$dhead[0].'" name="'.$dhead[0].'" >
                                          <option selected disabled value="">Pilih Salah Satu</option>
                            ';
                            /*if(count($masterAPD) > 1){
                                echo '
                                <option selected disabled value="">Pilih Salah Satu</option>
                                ';
                            }*/
                            //$retVal = (count($masterAPD) == 1) ? 'selected' : '' ;
                            foreach ($masterAPD as $key){
                                echo '
                                <option value="'.$key['id_ma'].'" >'.$key['merk'].'; '.$key['tahun'].'</option>
                                ';
                            }
                            echo '
                                    </select>
                                    <div class="valid-feedback">86</div>
                                    <div class="invalid-feedback">Harus diisi</div>
                                  </div>
                                </div>
                            </div>
                            ';

                            echo '
                            <div class="section full mb-3 refi hide-kus" id="img-apd">
                                
                            </div>
                            ';
                        
                            echo '
                            <div class="form-group boxed mb-3">
                                <div class="row input-wrapper refi hide-kus">
                                    <label class="label col-2" for="'.$dhead[2].'">'.$thead[2].'</label>
                                    <div class="col-10">
                                      <select class="form-control custom-select refi-select" id="'.$dhead[2].'" name="'.$dhead[2].'" >
                                        
                            ';
                            foreach ($listKondisi as $key){
                                echo '
                                <option value="'.$key['id_mk'].'"> '.$key['nama_kondisi'].', '.$key['keterangan'].'</option>
                                ';
                            }
                            echo '
                                    </select>
                                    <div class="valid-feedback">86</div>
                                    <div class="invalid-feedback">Harus diisi</div>
                                </div>
                              </div>
                            </div>
                            ';
            
                            //d($post);
                            //d(json_decode($listUkuran['daftar_ukuran']));
                            $listUkuran = json_decode($listUkuran['daftar_ukuran']);
                            $retVal = (count($listUkuran) == 1) ? 'selected' : '' ;
                            echo '
                            <div class="form-group boxed mb-3">
                                <div class="row input-wrapper refi hide-kus">
                                    <label class="label col-2" for="'.$dhead[3].'">'.$thead[3].'</label>
                                    <div class="col-10">
                                      <select class="form-control custom-select refi-select" id="'.$dhead[3].'" name="'.$dhead[3].'"  >
                            ';
                            if(count($listUkuran) > 1){
                                echo '
                                <option selected disabled value="">Pilih Salah Satu</option>
                                ';
                            }
                            foreach ($listUkuran as $key){
                                echo '
                                <option value="'.$key.'" '.$retVal.'> '.$key.'</option>
                                ';
                            }
                            echo '
                                    </select>
                                    <div class="valid-feedback">86</div>
                                    <div class="invalid-feedback">Harus diisi</div>
                                </div>
                              </div>
                            </div>
                            ';
            
                            echo '
                                <div class="form-group boxed mb-3">
                                    <div class="row input-wrapper refisa hide-no-urut">
                                        <label class="label col-2" for="no_urut">Nomor Urut (5 Digit terakhir No.Serial)<a class="text-danger">*</a></label>
                                        <div class="col-10">
                                          <input id="no_urut" name="no_urut" class="form-control">
                                          <div class="valid-feedback">86</div>
                                          <div class="invalid-feedback">Harus diisi</div>
                                        </div>
                                    </div>
                                </div>';
            
                            echo '
                                <div class="form-group boxed mb-3">
                                    <div class="row input-wrapper refi hide-kus">
                                        <label class="label col-2" for="'.$dhead[4].'">'.$thead[4].'</label>
                                        <div class="col-10">
                                          <textarea id="'.$dhead[4].'" name="'.$dhead[4].'" rows="2" class="form-control"></textarea>
                                        </div>
                                    </div>
                                </div>
                            ';
                        ?>
                            <!-- upload image -->
                            <div class="row mt-2 mb-2 refi-kus hide-kus">
                                <div class="label col-2">Foto APD<span class="text-danger">*</span></div>
                                <div class="col-10">
                                    <input class="form-control" type="file" name="foto_apd" id="fileuploadInput" accept=".gif, .png, .jpg, .jpeg">
                                    <div class="valid-feedback">86</div>
                                    <div class="invalid-feedback">Harus diisi</div>
                                </div>
                            </div>

                            <!-- preview image -->
                            <?php
                            //$preview = (isset($dataAPD['foto_apd']) && !is_null(($dataAPD['foto_apd']))) ? '' : b ;
                            $preview = base_url().'assets/img/master_apd/no-image.png';
                            ?>
                            
                            <div class="text-center refi-kus hide-kus">
                              <img class="img-fluid"  style="height:250px" id="preview_foto_apd" src="<?= $preview; ?>" alt="your image" />
                            </div>
                        
                            <div class="form-group mt-2 mb-3">
                                <div class="custom-control custom-checkbox mb-1">
                                    <input type="checkbox" class="custom-control-input" name="myCheckbox[]" id="customCheckb1" required>
                                    <label class="custom-control-label" for="customCheckb1">Saya menyatakan bahwa data yang diisi pada aplikasi eAPD adalah yang sebenar-benarnya</label>
                                    <div class="invalid-feedback">Harus dipilih</div>
                                  </div>
                            </div>

                            <div class="section full mt-1">
                              <div class="row mx-2">
                                  <div class="col-12">
                                      <button type="submit" class="btn sm-btn btn-primary mr-1 mb-1 float-right">
                                          Simpan
                                      </button>
                                      <a href="<? echo base_url().$controller;?>/lapor" class="btn sm-btn btn-primary mr-1 mb-1 float-left"> Cancel </a>
                                  </div>
                              </div>
                            </div>
                            
                        </form>
                  </div>
              </div>
          </div>
        </div>


      </div>
    </div>

    <style>
        .hide-kus
        {
            display: none;
        } 
        .hide-no-urut
        {
            display: none;
        }
    </style>
