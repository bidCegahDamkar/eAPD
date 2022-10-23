<div class="page-content">
      <div class="main-wrapper">
        <? //d($post, $master_state, $my_time); ?>
        <div class="row justify-content-md-center">
          <div class="col-8">
            <div class="card">
              <div class="card-body">
                <?php 
                  $input_isopen = $master_state[0]['is_open'];
                  $input_deskripsi = $master_state[0]['deskripsi'];
                  $validasi_isopen = $master_state[1]['is_open'];
                  $validasi_deskripsi = $master_state[1]['deskripsi'];
                  $array_input = ($input_isopen == 1) ? ['checked', ''] : ['', 'checked'] ;
                  $array_validasi = ($validasi_isopen == 1) ? ['checked', ''] : ['', 'checked'] ;

                  $attributes = array('class' => 'needs-validation', 'novalidate' => 'novalidate');
                  echo form_open($controller.'/input_periode', $attributes);
                ?>
                <h5 class="card-title">Setting Periode Input dan Validasi</h5>
                  <div class="row mb-3">
                    <label for="nama" class="col-sm-2 col-form-label">Input Setting</label>
                    <div class="col-sm-10">
                      <input type="radio" class="btn-check" name="options_input" id="success-input" autocomplete="off" value="1" required <?= $array_input[0];?> >
                      <label class="btn btn-outline-success" for="success-input">Buka</label>
                      <input type="radio" class="btn-check" name="options_input" id="danger-input" autocomplete="off" value="0" <?= $array_input[1];?>>
                      <label class="btn btn-outline-danger" for="danger-input">Tutup</label>
                    </div>
                  </div>
                  <div class="row mb-3">
                    <label for="nama" class="col-sm-2 col-form-label">Triwulan</label>
                    <div class="col-sm-5">
                      <div class="input-group mb-3">
                        <select class="form-select" id="triwulanSelect" name="triwulanSelect">
                          <?php
                          $list_tw = ['TW1', 'TW2', 'TW3', 'TW4'];
                          $name_tw = ['TW1' => 'Triwulan I', 'TW2' => 'Triwulan II', 'TW3' => 'Triwulan III', 'TW4' => 'Triwulan IV'];
                          foreach ($list_tw as $tw) {
                            echo '<option value="'.$tw.'" ';
                            if ($tw == $periode_input) {
                              echo 'selected';
                            }
                            echo '>';
                            echo $name_tw[$tw];
                            echo '</option>';
                          }
                          ?>
                        </select>
                        <label class="input-group-text" for="triwulanSelect"><?php echo $tahun; ?></label>
                      </div>
                    </div>
                  </div>
                  
                  <div class="row mb-3">
                    <label for="input_deskripsi" class="col-sm-2 col-form-label">Deskripsi</label>
                    <div class="col-sm-10">
                      <input type="text" class="form-control" id="input_deskripsi" name="input_deskripsi" value="<?= $input_deskripsi; ?>" required >
                    </div>
                  </div>
                  </br>
                  <h5 class="card-title">Setting Periode Validasi</h5>
                  <div class="row mb-3">
                    <label for="nama" class="col-sm-2 col-form-label">Validasi Setting</label>
                    <div class="col-sm-10">
                      <input type="radio" class="btn-check" name="options_valid" id="success-valid" autocomplete="off" value="1" required <?= $array_validasi[0];?> >
                      <label class="btn btn-outline-success" for="success-valid">Buka</label>
                      <input type="radio" class="btn-check" name="options_valid" id="danger-valid" autocomplete="off" value="0" <?= $array_validasi[1];?>>
                      <label class="btn btn-outline-danger" for="danger-valid">Tutup</label>
                    </div>
                  </div>
                  <div class="row mb-3">
                    <label for="valid_deskripsi" class="col-sm-2 col-form-label">Deskripsi</label>
                    <div class="col-sm-10">
                      <input type="text" class="form-control" id="valid_deskripsi" name="valid_deskripsi" value="<?= $validasi_deskripsi; ?>" required >
                    </div>
                  </div>
                  
                  <div class="btn-group float-end mt-2" role="group" aria-label="Basic example">
                    <button type="reset" class="btn btn-secondary">Reset</button>
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