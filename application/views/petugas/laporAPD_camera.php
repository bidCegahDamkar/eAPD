<style>
    .hide-kus
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
            echo form_open_multipart($controller.'/laporAPD/'.$jenisApd['id_mj'], $attributes);
            //d($dataAPD);
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
                ';
                if(count($masterAPD) > 1){
                    echo '
                    <option selected disabled value="">Pilih Salah Satu</option>
                    ';
                }
                $retVal = (count($masterAPD) == 1) ? 'selected' : '' ;
                foreach ($masterAPD as $key){
                    echo '
                    <option value="'.$key['id_ma'].'" '.$retVal.'>'.$key['merk'].'; '.$key['tahun'].'</option>
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
                        <label class="label" for="'.$dhead[2].'">'.$thead[2].'</label>
                        <select class="form-control custom-select refi-select" id="'.$dhead[2].'" name="'.$dhead[2].'" >
                            <option selected disabled value="">Pilih Salah Satu</option>
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
                        <div class="input-wrapper refi hide-kus">
                            <label class="label" for="'.$dhead[4].'">'.$thead[4].'</label>
                            <textarea id="'.$dhead[4].'" name="'.$dhead[4].'" rows="2" class="form-control"></textarea>
                        </div>
                    </div>
                ';
            ?>

                <div class="section full mt-2 mb-2 refi-kus hide-kus">
                    <div class="label">Upload Foto APD<a class="text-danger">*</a></div>
                    <div class="row">
                        <div class="pb-2 pt-2 col-6">
                            <a style="width:100%; height:100%; display:block" data-toggle="modal" data-target="#ModalBasic" href="#" >
                            <div class="card bg-dark text-white">
                                <img src="<?= base_url();?>assets/img/camera.svg" class="card-img overlay-img" alt="image">
                                <div class="card-img-overlay">
                                    <h5 class="card-title">Foto 1</h5>
                                    <p class="card-text">Click disini untuk tambah foto</p>
                                </div>
                            </div>
                            </a>
                            <div class="valid-feedback">86</div>
                            <div class="invalid-feedback">Harus diisi</div>
                        </div>
                        <div class="pb-2 pt-2 col-6">
                            <div class="card bg-dark text-white">
                                <img src="<?= base_url();?>assets/img/camera.svg" class="card-img overlay-img" alt="image">
                                <div class="card-img-overlay">
                                    <h5 class="card-title">Foto 2</h5>
                                    <p class="card-text">Click disini untuk tambah foto</p>
                                </div>
                            </div>
                            <div class="valid-feedback">86</div>
                            <div class="invalid-feedback">Harus diisi</div>
                        </div>
                    </div>
                </div>

                <input id="image-temp" name="image">
                <input id="thumb-temp" name="thumb">

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

            <!-- Modal Basic -->
        <div class="modal fade modalbox" id="ModalBasic" data-backdrop="static" tabindex="-1" role="dialog">
            <div class="modal-dialog" role="document">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title">Ambil Foto 1</h5>
                        <a href="javascript:;" data-dismiss="modal">Tutup</a>
                    </div>
                    <div class="modal-body p-0">
                        <div id="my_container">
                            <div id="vid_container">
                                <video id="video" autoplay playsinline></video>
                                <div id="video_overlay"></div>
                            </div>
                            <div id="gui_controls">
                                <button
                                id="switchCameraButton"
                                name="switch Camera"
                                type="button"
                                aria-pressed="false"
                                ></button>
                                <button id="takePhotoButton" name="take Photo" type="button"></button>
                                <button
                                id="toggleFullScreenButton"
                                name="toggle FullScreen"
                                type="button"
                                aria-pressed="false"
                                ></button>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <!-- * Modal Basic -->

        </div>
    </div>
