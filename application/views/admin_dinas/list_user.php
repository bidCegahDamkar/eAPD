    <div class="page-content">
      <div class="main-wrapper">
        <? //d($listJab); ?>
        <div class="row">
          <div class="col-12">
            <div class="card">
              <div class="card-body">
                <h5 class="card-title mb-2"><?= $pageTitle ?></h5>
                <div id="kode_panggil" class="row mx-1 mb-3 border rounded" >
                  <p class="text-muted m-0">filter</p>
                  <div class="mb-1 row">
                    <label for="jabatan" class="col-sm-2 col-form-label">Jabatan</label>
                    <div class="col-sm-5">
                      <select id="jabatan" class="myselect2" style="width: 100%" aria-label="Default select example">
                        <option value="all" selected>Semua</option>
                        <?php
                          foreach ($listJab as $jab) {
                            echo '
                            <option value="'.$jab['id'].'">'.$jab['deskripsi'].'</option>
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
                    <label for="penugasan" class="col-sm-2 col-form-label">Penugasan</label>
                    <div class="col-sm-5">
                      <select id="penugasan" class="" style="width: 100%" aria-label="Default select example">
                        <option value="all" selected>Semua</option>
                      </select>
                    </div>
                  </div>
                </div>
                <div class="table-responsive">
                  <table id="list-user" class="stripe row-border order-column" >
                    <thead>
                        <tr>
                          <th>Aksi</th>
                          <th>#</th>
                          <th>Nama</th>
                          <th>NRK</th>
                          <th>NIP</th>
                          <th>Jabatan</th>
                          <th>Sektor</th>
                          <th>Penugasan</th>
                          <th>% input APD</th>
                          <th>% APD terverifikasi</th>
                          <th>Jumlah APD tertolak</th>
                          <th>Input Ukuran</th>
                        </tr>
                    </thead>
                    <tbody>
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
        
       