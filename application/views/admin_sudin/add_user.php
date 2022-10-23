    <div class="page-content">
      <div class="main-wrapper">
        <? //d($list_jabatan); ?>
        <div class="row justify-content-md-center">
          <div class="col-8">
            <div class="card">
              <div class="card-body">
                <h5 class="card-title"><?= $pageTitle ?></h5>

                <?php 
                  echo form_open($controller.'/add_user');
                ?>
                  <div class="row mb-3">
                    <label for="nama" class="col-sm-2 col-form-label">Nama</label>
                    <div class="col-sm-10">
                      <input type="text" class="form-control" id="nama" name="nama" required>
                    </div>
                  </div>
                  <div class="row mb-3">
                    <label for="NRK" class="col-sm-2 col-form-label">NRK/ NPJLP</label>
                    <div class="col-sm-10">
                      <input type="text" class="form-control" id="NRK" name="NRK" required>
                      <div id="validationServerUsernameFeedback" class="invalid-feedback">
                        error duplikasi NRK/ NPJLP atau terdapat spasi atau kosong
                      </div>
                    </div>
                  </div>
                  <div class="row mb-3">
                    <label for="NIP" class="col-sm-2 col-form-label">NIP</label>
                    <div class="col-sm-10">
                      <input type="text" class="form-control" id="NIP" name="NIP" required>
                    </div>
                  </div>
                  <div class="row mb-3">
                    <label for="statusid" class="col-sm-2 col-form-label">Status</label>
                    <div class="col-sm-10">
                      <select class="" name="status_id" id="statusid" style="width: 100%" required>
                        <?php
                          foreach ($list_status as $stat) {
                            $selected = ($stat['id_stat'] == $userData['status_id']) ? 'selected' : '' ;
                            echo '
                            <option value="'.$stat['id_stat'].'" '.$selected.'>'.$stat['status'].'</option>
                            ';
                          }
                        ?>
                      </select>
                    </div>
                  </div>
                  <div class="row mb-3">
                    <label for="jabatanid" class="col-sm-2 col-form-label">Jabatan</label>
                    <div class="col-sm-10">
                      <select class="" name="jabatan_id" id="jabatan_id" style="width: 100%" required>
                        <?php
                          foreach ($list_jabatan as $jab) {
                            echo '
                            <option value="'.$jab['id_mj'].'" >'.$jab['nama_jabatan'].'</option>
                            ';
                          }
                        ?>
                      </select>
                    </div>
                  </div>
                  <div class="row mb-3">
                    <label for="kodepos" class="col-sm-2 col-form-label">Tempat Tugas</label>
                    <div class="col-sm-10">
                      <select class="select2-basic-single" name="kode_pos_id" id="kodepos" style="width: 100%" required>
                        <?php
                          foreach ($list_pos as $pos) {
                            $retVal = (is_null($pos['sektor']) ) ? '' : ', Sektor '.$pos['sektor'] ;
                            $selected = ($pos['id_mp'] == $userData['kode_pos_id']) ? 'selected' : '' ;
                            echo '
                            <option value="'.$pos['id_mp'].'" '.$selected.'>'.$pos['kode_pos'].', '.$pos['nama_pos'].$retVal.'</option>
                            ';
                          }
                        ?>
                      </select>
                    </div>
                  </div>
                  <div class="row mb-3">
                    <label for="grouppiket" class="col-sm-2 col-form-label">Group Piket</label>
                    <div class="col-sm-10">
                      <select class="select2-basic-single" name="group_piket_id" id="grouppiket" style="width: 100%" required>
                        <?php
                          foreach ($list_group_piket as $gp) {
                            $selected = ($gp['id'] == $userData['group_piket_id']) ? 'selected' : '' ;
                            echo '
                            <option value="'.$gp['id'].'" '.$selected.'>'.$gp['deskripsi_group'].'</option>
                            ';
                          }
                        ?>
                      </select>
                    </div>
                  </div>
                  <div class="btn-group float-end mt-2" role="group" aria-label="Basic example">
                    <a href="<?= base_url().$controller.'/user_setting' ?>" class="btn btn-secondary" role="button">Back</a>
                    <button type="submit" id="submit_button" class="btn btn-primary" disabled>Simpan</button>
                  </div>
                </form>
              </div>
            </div>
          </div>
        </div>
      </div>
    </div>
      
</div> <!-- page-container -->
        
       