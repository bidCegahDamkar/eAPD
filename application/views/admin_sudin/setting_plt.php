    <div class="page-content">
      <div class="main-wrapper">
        <? //d($data_jabatan); ?>
        <div class="row justify-content-md-center">
          <div class="col-8">
            <div class="card">
              <div class="card-body">
                <h5 class="card-title"><?= $pageTitle ?></h5>
                <?php 
                  $attributes = array('class' => 'needs-validation', 'novalidate' => 'novalidate');
                  echo form_open($controller.'/detail_plt/'.$data_jabatan['id_mj'], $attributes); 
                ?>
                  <input type="hidden" class="form-control" id="id_mj" name="id_mj" value="<?= $data_jabatan['id_mj'] ?>" disabled>
                  <div class="row mb-3">
                    <label for="nama" class="col-sm-2 col-form-label">Jabatan</label>
                    <div class="col-sm-10">
                      <input type="text" class="form-control" id="nama" name="nama" value="<?= $data_jabatan['nama_jabatan'] ?>" disabled>
                    </div>
                  </div>
                  <div class="row mb-3">
                    <label for="kode_panggil" class="col-sm-2 col-form-label">Kode Panggil</label>
                    <div class="col-sm-10">
                      <input type="text" class="form-control" id="kode_panggil" name="kode_panggil" value="<?= $data_jabatan['kode_panggil'] ?>" disabled>
                    </div>
                  </div>
                  <div class="row mb-3">
                    <label for="keterangan" class="col-sm-2 col-form-label">Wilayah</label>
                    <div class="col-sm-10">
                      <input type="text" class="form-control" id="keterangan" name="keterangan" value="<?= $data_jabatan['keterangan'] ?>" disabled>
                    </div>
                  </div>
                  <div class="row mb-3">
                    <label for="plt" class="col-sm-2 col-form-label">PLT</label>
                    <div class="col-sm-10">
                      <select class="" name="plt" id="plt" style="width: 100%" required>
                        <?php
                        if (! is_null($data_jabatan['plt_id'])) {
                          echo '<option value="'.$data_jabatan['plt_id'].'" selected>'.$data_jabatan['nama'].' ('.$data_jabatan['NRK'].'/ '.$data_jabatan['NIP'].')'.'</option>';
                        } else {
                          echo '<option selected disabled>pilih atau Ketik nama atau NRK atau NIP</option>';
                        }
                        
                        ?>
                      </select>
                    </div>
                  </div>
                  <div class="btn-group float-end mt-2" role="group" aria-label="Basic example">
                    <a href="<?= base_url().$controller.'/plt_setting' ?>" class="btn btn-secondary" role="button">Back</a>
                    <button type="submit" class="btn btn-primary">Simpan</button>
                  </div>
                </form>
              </div>
            </div>
          </div>
        </div>
      </div>
    </div>
      
</div> <!-- page-container -->
        
       