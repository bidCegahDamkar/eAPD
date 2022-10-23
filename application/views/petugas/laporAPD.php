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
<!-- App Capsule -->
<div id="appCapsule">
    <div class="header-large-title">
        <h1 class="title"><? echo $jenisApd['jenis_apd']; ?></h1>
        <h4 class="subtitle">* harus diisi</h4>
    </div>
    <div class="section full ">
        <div class="wide-block pb-1 pt-2">

            <?php 
            $attributes = array('class' => 'needs-validation', 'novalidate' => 'novalidate');
            echo form_open($controller.'/laporAPD/'.$jenisApd['id_mj'], $attributes);
            //d($masterAPD);
                echo '
                <div class="form-group boxed">
                    <div class="input-wrapper">
                        <label class="label" for="'.$dhead[1].'">'.$thead[1].'</label>
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
                <div class="form-group boxed">
                    <div class="input-wrapper refi hide-kus">
                        <label class="label" for="'.$dhead[0].'">'.$thead[0].'</label>
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
                ';

                echo '
                <div class="section full mb-3 refi hide-kus" id="img-apd">
                    
                </div>
                ';

                echo '
                <div class="form-group boxed">
                    <div class="input-wrapper refi hide-kus">
                        <label class="label" for="'.$dhead[2].'">'.$thead[2].'</label>
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
                ';

                //d($post);
                //d(json_decode($listUkuran['daftar_ukuran']));
                $listUkuran = json_decode($listUkuran['daftar_ukuran']);
                $retVal = (count($listUkuran) == 1) ? 'selected' : '' ;
                echo '
                <div class="form-group boxed">
                    <div class="input-wrapper refi hide-kus">
                        <label class="label" for="'.$dhead[3].'">'.$thead[3].'</label>
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
                ';

                echo '
                    <div class="form-group boxed">
                        <div class="input-wrapper refisa hide-no-urut">
                            <label class="label" for="no_urut">Nomor Urut (5 Digit terakhir No.Serial)<a class="text-danger">*</a></label>
                            <input id="no_urut" name="no_urut" class="form-control">
                            <div class="valid-feedback">86</div>
                            <div class="invalid-feedback">Harus diisi</div>
                        </div>
                    </div>';

                echo '
                    <div class="form-group boxed">
                        <div class="input-wrapper refi hide-kus">
                            <label class="label" for="'.$dhead[4].'">'.$thead[4].'</label>
                            <textarea id="'.$dhead[4].'" name="'.$dhead[4].'" rows="2" class="form-control"></textarea>
                        </div>
                    </div>
                ';
            ?>

                <div class="section full mt-2 mb-2 refi-kus hide-kus">
                    <div class="label">Foto APD<span class="text-danger">*</span></div>
                    <div class="row">
                        <div class="pb-2 pt-2 col-12">
                            <div class="custom-file-upload">
                                <input type="file" id="fileuploadInput" name="foto_apd" accept=".gif, .png, .jpg, .jpeg">
                                <label for="fileuploadInput">
                                    <span>
                                        <strong>
                                            <ion-icon name="cloud-upload-outline"></ion-icon>
                                            <i>Upload Foto APD*</i>
                                        </strong>
                                    </span>
                                </label>
                            </div>
                            <div class="valid-feedback">86</div>
                            <div class="invalid-feedback">Harus diisi</div>
                        </div>
                    </div>
                </div>

                <!-- compressed image -->
                <input id="image-temp" name="image" type="hidden">
                <input id="thumb-temp" name="thumb" type="hidden">

                <div class="form-group mt-2 mb-3">
                    <div class="custom-control custom-checkbox mb-1">
                        <input type="checkbox" class="custom-control-input" name="myCheckbox[]" id="customCheckb1" required>
                        <label class="custom-control-label" for="customCheckb1">Saya menyatakan bahwa data yang diisi pada aplikasi eAPD adalah yang sebenar-benarnya</label>
                    </div>
                </div>


                <div class="section full mt-1">
                    <div class="row mx-2">
                        <div class="col-12">
                            <button type="submit" class="btn sm-btn btn-primary mr-1 mb-1 float-right" >
                                Simpan
                            </button>
                            <a href="<? echo base_url().$controller;?>/lapor" class="btn sm-btn btn-primary mr-1 mb-1 float-right"> Cancel </a>
                        </div>
                    </div>
                </div>

            </form>

        </div>
    </div>
