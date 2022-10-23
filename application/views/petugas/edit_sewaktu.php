<style>
    .hide-kus
    {
        display: none;
    }
</style>
<!-- App Capsule -->
<div id="appCapsule">
    <? d($detail_lap_sewaktu, $tes); ?>
    <div class="header-large-title">
        <h1 class="title"><? echo $pageTitle; ?></h1>
        <h4 class="subtitle"><a class="text-danger">*</a> harus diisi</h4>
    </div>
    <div class="section full ">
        <div class="wide-block pb-1 pt-2">
            <?php 
                $attributes = array('class' => 'needs-validation', 'novalidate' => 'novalidate');
                echo form_open_multipart($controller.'/lapor_sewaktu/'.$detail_lap_sewaktu['id'].'', $attributes);
            ?>
            <!-- Jenis Laporan -->
            <div class="form-group boxed">
                <div class="input-wrapper">
                    <label class="label" for="jenis_lap">Jenis Laporan<a class="text-danger">*</a></label>
                    <select class="form-control custom-select" id="jenis_lap" name="jenis_lap" disabled>
                        <?
                        if($detail_lap_sewaktu['jenis_laporan'] == 1)
                        {
                            echo '
                            <option selected value="1"> Kerusakan APD</option>
                            ';
                        }else{
                            echo '
                            <option selected value="2"> Kehilangan APD</option>
                            ';
                        }
                        ?>
                    </select>
                    <div class="invalid-feedback">Harus diisi</div>
                </div>
            </div>

            <!-- Pilih APD -->
            <div class="form-group boxed">
                <div class="input-wrapper">
                    <label class="label" for="apd">APD<a class="text-danger">*</a></label>
                    <select class="form-control custom-select" id="apd" name="apd" disabled>
                        <?
                        echo '
                        <option selected value="'.$detail_lap_sewaktu['id'].'"> '.$detail_lap_sewaktu['jenis_apd'].', '.$detail_lap_sewaktu['merk'].', 
                        '.$detail_lap_sewaktu['tahun'].'</option>
                        ';
                        ?>
                    </select>
                    <div class="invalid-feedback">Harus diisi</div>
                </div>
            </div>

            <!-- tingkat rusak -->
            <?
            if($detail_lap_sewaktu['jenis_laporan'] == 1)
            {
                echo '
                <div class="form-group boxed lvl_rsk">
                    <div class="input-wrapper">
                        <label class="label" for="lvl_rsk">Tingkat Kerusakan<a class="text-danger">*</a></label>
                        <select class="form-control custom-select" id="lvl_rsk" name="lvl_rsk" required disabled>
                            <option value="1" selected> Rusak Berat, tidak dapat dipakai atau perlindungan tidak ada</option>
                        </select>
                        <div class="invalid-feedback">Harus diisi</div>
                    </div>
                </div>
                ';
            }
            ?>
            

            <!-- Tanggal -->
            <div class="form-group boxed">
                <div class="input-wrapper">
                    <label class="label" for="tgl">Tanggal kejadian</label>
                    <input type="text" class="form-control datepicker" id="tgl" name="tgl_kej" readonly="true" value="<? echo sqlDate2html($detail_lap_sewaktu['tgl_kej']); ?>">
                    <div class="invalid-feedback">Harus diisi</div>
                </div>
            </div>

            <!-- Deskripsi -->
            <div class="form-group boxed">
                <div class="input-wrapper">
                    <label class="label" for="deskripsi">Deskripsi Singkat/ Kronologis<a class="text-danger">*</a></label>
                    <textarea id="deskripsi" name="deskripsi" rows="4" class="form-control" required><? echo $detail_lap_sewaktu['deskripsi_laporan']; ?></textarea>
                    <div class="invalid-feedback">Harus diisi</div>
                </div>
            </div>

            <!-- foto -->
            <div class="section full mt-2 mb-2">
                <div class="label">Upload Foto APD/ Laporan Kehilangan<a class="text-danger">*</a></div>
                <div class="wide-block pb-2 pt-2">
                    <div class="custom-file-upload">
                        <input type="file" id="fileuploadInput" name="foto_lap" accept=".gif, .png, .jpg, .jpeg">
                        <?
                        if(! is_null($detail_lap_sewaktu['photo'])){
                            echo '
                            <label for="fileuploadInput" class="file-uploaded" style="background-image: url(&quot;'.base_url().'upload/petugas/laporan_sewaktu/'.$detail_lap_sewaktu['photo'].'&quot;);">
                                <span>'.$detail_lap_sewaktu['photo'].'</span>
                            </label>
                            ';
                        } else {
                            echo '
                            <label for="fileuploadInput">
                                <span>
                                    <strong>
                                        <ion-icon name="cloud-upload-outline"></ion-icon>
                                        <i>Ketuk untuk Upload</i>
                                    </strong>
                                </span>
                            </label>
                            ';
                        }
                        ?>
                    </div>
                    <div class="invalid-feedback">Harus diisi</div>
                </div>
            </div>

            <div class="form-group mt-2 mb-3">
                <div class="custom-control custom-checkbox mb-1">
                    <input type="checkbox" class="custom-control-input" name="myCheckbox[]" id="customCheckb1" checked disabled>
                    <label class="custom-control-label" for="customCheckb1">Saya menyatakan bahwa data yang diisi pada aplikasi eAPD adalah yang sebenar-benarnya</label>
                </div>
            </div>


            <div class="section full mt-1">
                <div class="row mx-2">
                    <div class="col-12">
                        <button type="submit" class="btn sm-btn btn-primary mr-1 mb-1 float-right" >
                            Simpan
                        </button>
                        <a href="<? echo base_url().$controller;?>/list_lapor_sewaktu" class="btn sm-btn btn-primary mr-1 mb-1 float-right"> Cancel </a>
                    </div>
                </div>
            </div>
            </form>
        </div>
    </div>
