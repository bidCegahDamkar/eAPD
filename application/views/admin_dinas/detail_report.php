    <div class="page-content">
      <div class="main-wrapper">
        <? //d($listUser); ?>
        <div class="row">
          <div class="col-12">
            <div class="card">
              <div class="card-body">
                <h5 class="card-title mb-2"><?= $pageTitle ?></h5>
                <div id="kode_panggil" class="row mx-1 mb-3 border rounded" >
                  <p class="text-muted m-0">filter</p>
                  <div class="mb-1 row">
                    <label for="sektor" class="col-sm-2 col-form-label">Jenis APD</label>
                    <div class="col-sm-5">
                      <select id="jenisAPD" class="myselect2" style="width: 100%" aria-label="Default select example">
                        <option value="all" selected>Semua</option>
                        <?php
                          foreach ($listJenisAPD as $apd) {
                            echo '
                            <option value="'.$apd['id_mj'].'">'.$apd['jenis_apd'].'</option>
                            ';
                          }
                        ?>
                      </select>
                    </div>
                  </div>
                  <div class="mb-1 row">
                    <label for="sektor" class="col-sm-2 col-form-label">Sektor</label>
                    <div class="col-sm-5">
                      <select id="sektor" class="myselect2" style="width: 100%" aria-label="Default select example">
                        <option value="all" selected>Semua</option>
                        <?php
                          foreach ($listSektor as $sektor) {
                            echo '
                            <option value="'.$sektor['kode'].'">'.$sektor['sektor'].'</option>
                            ';
                          }
                        ?>
                      </select>
                    </div>
                  </div>
                  <div class="mb-1 row">
                    <label for="penugasan" class="col-sm-2 col-form-label">Pos</label>
                    <div class="col-sm-5">
                      <select id="penugasan" class="" style="width: 100%" aria-label="Default select example">
                        <option value="all" selected>Semua</option>
                      </select>
                    </div>
                  </div>
                </div>
                <div class="table-responsive">
                  <table id="detail-report" class="stripe row-border order-column" >
                    <thead>
                        <tr>
                          <th rowspan="2">#</th>
                          <th rowspan="2">Jenis APD</th>
                          <th rowspan="2">Sektor</th>
                          <th rowspan="2">Pos</th>
                          <th class="text-center" colspan="5">Kondisi</th>
                          <th class="text-center" colspan="3">Keberadaan</th>
                          <th rowspan="2">subTotal</th>
                        </tr>
                        <tr>
                          <th>Baik</th>
                          <th>Rusak Ringan</th>
                          <th>Rusak Sedang</th>
                          <th>Rusak Berat</th>
                          <th>subTotal</th>
                          <th>Belum Terima</th>
                          <th>Hilang</th>
                          <th>subTotal</th>
                        </tr>
                    </thead>
                    <tbody>
                      <?php
                      /*$i = 1;
                      foreach ($listUser as $user) {
                        echo '
                        <tr>
                          <td>
                            <div class="btn-group " role="group" aria-label="Basic example">
                              <a href="'.base_url().'kasi_sarana/settingUserDetail/'.$user['id'].'" class="btn btn-primary btn-sm" 
                                role="button" data-toggle="tooltip" title="Edit">
                                <i class="feather-16" data-feather="arrow-up-right"></i>
                              </a>
                            </div>
                          </td>
                          <td>'.$i.'</td>
                          <td>'.$user['nama'].'</td>
                          <td>'.$user['NRK'].'/ '.$user['NIP'].'</td>
                          <td>'.$user['nama_jabatan'].'</td>
                          <td>'.$user['sektor'].'</td>
                          <td>'.$user['nama_pos'].'</td>
                          <td>'.$user['status'].'</td>';
                          $retVal = ($user['active'] == 1) ? 'Aktif' : 'Non-aktif' ;
                      echo '<td>'.$retVal.'</td>
                        </tr>
                        ';
                        $i++;
                      }*/
                      ?>
                    </tbody>
                  </table>
                </div>
              </div>
            </div>
          </div>
        </div>
      </div>
    </div>
      
</div> <!-- page-container -->
        
       