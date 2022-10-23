    <div class="page-content">
      <div class="main-wrapper">
        <? //d($kuea); ?>
        <div class="row justify-content-md-center">
          <div class="col-8">
            <div class="card">
              <div class="card-body">
                <h5 class="card-title"><?= $pageTitle ?></h5>
                <?php
                $foto = (is_null($userData['photo'])) ? base_url().'assets/img/default-red.png' : base_url().'upload/petugas/profil/'.$userData['photo'] ;
                ?> 
                <div class="text-center my-4">
                  <img src="<?php echo $foto; ?>" class="rounded-circle" width="240" height="240" alt="...">
                </div>

                <?php 
                  $attributes = array('class' => 'needs-validation', 'novalidate' => 'novalidate');
                  echo form_open($controller.'/settingUserDetail/'.$userData['id'], $attributes); 
                ?>
                  <div class="row mb-3">
                    <label for="nama" class="col-sm-2 col-form-label">Nama</label>
                    <div class="col-sm-10">
                      <input type="text" class="form-control" id="nama" name="nama" value="<?= $userData['nama'] ?>" required>
                    </div>
                  </div>
                  <div class="row mb-3">
                    <label for="NRK" class="col-sm-2 col-form-label">NRK/ NPJLP</label>
                    <div class="col-sm-10">
                      <input type="text" class="form-control" id="NRK" name="NRK" value="<?= $userData['NRK'] ?>" required>
                    </div>
                  </div>
                  <div class="row mb-3">
                    <label for="NIP" class="col-sm-2 col-form-label">NIP</label>
                    <div class="col-sm-10">
                      <input type="text" class="form-control" id="NIP" name="NIP" value="<?= $userData['NIP'] ?>" required>
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
                      <select class="" name="jabatan_id" id="jabatanid" style="width: 100%" required>
                        <option value="<?= $userData['jabatan_id']; ?>" selected><?= $userData['nama_jabatan']; ?></option>
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
                  <?php
                  $checked = ($userData['active'] == 1) ? ['checked', ''] : ['', 'checked'] ;
                  ?>
                  <fieldset class="row mb-3">
                    <legend class="col-form-label col-sm-2 pt-0">Akun</legend>
                    <div class="col-sm-10">
                      <div class="form-check">
                        <input class="form-check-input" type="radio" name="active" id="gridRadios1" value="1" <?= $checked[0] ?>>
                        <label class="form-check-label" for="gridRadios1">
                          Aktif
                        </label>
                      </div>
                      <div class="form-check">
                        <input class="form-check-input" type="radio" name="active" id="gridRadios2" value="0" <?= $checked[1] ?>>
                        <label class="form-check-label" for="gridRadios2">
                          Non-aktif
                        </label>
                      </div>
                    </div>
                  </fieldset>
                  <div class="btn-group float-end mt-2" role="group" aria-label="Basic example">
                    <a href="<?= base_url().$controller.'/user_setting' ?>" class="btn btn-secondary" role="button">Back</a>
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
        
       