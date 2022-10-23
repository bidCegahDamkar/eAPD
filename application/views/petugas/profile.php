<!-- Extra Header -->
<div class="extraHeader p-0" >
    <ul class="nav nav-tabs lined" role="tablist">
        <li class="nav-item">
            <a class="nav-link active" data-toggle="tab" href="#profil_tab" role="tab">
                Profil
            </a>
        </li>
        <li class="nav-item">
            <a class="nav-link" data-toggle="tab" href="#password_tab" role="tab">
                password
            </a>
        </li>
    </ul>
</div>
<!-- * Extra Header -->

<!-- App Capsule -->
<div id="appCapsule" class="extra-header-active">
    <? //d($userData, $tes) ?>
    <div class="tab-content mt-1">
        <!-- profil tab -->
        <div class="tab-pane fade show active" id="profil_tab" role="tabpanel">
            <?php 
                $attributes = array('class' => 'needs-validation', 'novalidate' => 'novalidate');
                echo form_open($controller.'/profile/profil', $attributes);
            ?>
            <div class="section full mt-2 mb-2">
                <div class="wide-block pb-1 pt-2">
                
                    <div class="section full">
                        <div class="section-title">Foto Profil</div>
                        
                        <div class="custom-file-upload">
                            <input type="file" id="fileuploadInput" name="foto_profil" accept=".gif, .png, .jpg, .jpeg">
                            <?
                            $photo_profile = 'upload/petugas/profil/'.$userData['photo'];
                            if(file_exists($photo_profile) && !is_null($userData['photo'])){
                                echo '
                                <label for="fileuploadInput" class="file-uploaded" style="background-image: url(&quot;'.base_url().$photo_profile.'&quot;);">
                                    <span>'.$userData['photo'].'</span>
                                </label>
                                ';
                            } else {
                                echo '
                                <label for="fileuploadInput">
                                    <span>
                                        <strong>
                                            <ion-icon name="cloud-upload-outline"></ion-icon>
                                            <i>Ketuk untuk Upload Foto</i>
                                        </strong>
                                    </span>
                                </label>
                                ';
                            }
                            ?>
                        </div>
                    </div>

                    <!-- compressed image -->
                    <input id="image-temp" name="image" type="hidden">
                    <input id="thumb-temp" name="thumb" type="hidden">

                </div>
            </div>

            <div class="section full mt-2 mb-2">
                <div class="wide-block pb-1 pt-2">
                <?php 
                for ($i = 0; $i <= 6; $i++) {
                    echo '
                    <div class="form-group boxed">
                        <div class="input-wrapper">
                            <label class="label" for="'.$formData[$i]['dhead'].'">'.$formData[$i]['thead'].'</label>
                            <input type="'.$formData[$i]['type'].'" class="form-control" value="'.$userData[$formData[$i]['dhead']].'" id="'.$formData[$i]['dhead'].'" name="'.$formData[$i]['dhead'].'" '.$formData[$i]['disabled'].'>
                        </div>
                    </div>
                    ';
                }
                ?>
                </div>
            </div>

            <div class="section full mt-1">
                <div class="row mx-2">
                    <div class="col-12">
                        <button type="submit" class="btn sm-btn btn-primary mr-1 mb-1 float-right">
                            Update
                        </button>
                        <button type="reset" class="btn sm-btn btn-success mr-1 mb-1 float-right">
                            Reset
                        </button>
                    </div>
                </div>
            </div>


            </form>

            <div class="wide-block pt-2 pb-2">

                <div class="alert alert-outline-info mb-1" role="alert">
                    Apabila terdapat kesalahan pada data NRK, NIP, Jabatan dan Tempat tugas, harap hubungi admin sudin
                </div>
            </div>
        </div>
        <!-- * profil tab -->

        <!-- password tab -->
        <div class="tab-pane fade" id="password_tab" role="tabpanel">
            <div class="section full mt-2 mb-2">
                <div class="wide-block pb-1 pt-2">
                <?php 
                    $attributes = array('class' => 'needs-validation', 'novalidate' => 'novalidate');
                    echo form_open($controller.'/profile/password', $attributes);
                ?>
                <?php 
                for ($i = 0; $i <= 2; $i++) {
                    echo '
                    <div class="form-group boxed">
                        <div class="input-wrapper">
                            <label class="label" for="'.$passwordData[$i]['dhead'].'">'.$passwordData[$i]['thead'].'</label>
                            <input type="password" class="form-control" id="'.$passwordData[$i]['dhead'].'" name="'.$passwordData[$i]['dhead'].'" placeholder="'.$passwordData[$i]['placeholder'].'">
                        </div>
                    </div>
                    ';
                }
                ?>
                </div>
            </div>
            <div class="section full mt-1">
                <div class="row mx-2">
                    <div class="col-12">
                        <button type="submit" class="btn sm-btn btn-primary mr-1 mb-1 float-right">
                            Update
                        </button>
                        <button type="reset" class="btn sm-btn btn-success mr-1 mb-1 float-right">
                            Reset
                        </button>
                    </div>
                </div>
            </div>

            </form>
        </div>
        <!-- * password tab -->

    </div>
</div>
<!-- * App Capsule -->
