    <div class="page-content">
      <div class="main-wrapper">
        <? //d($list_jab); ?>
        <div class="row justify-content-md-center">
          <div class="col-10">
            <div class="card">
              <div class="card-body">
                <div class="table-responsive">
                  <table class="stripe row-border order-column home-myTable" >
                    <thead>
                        <tr>
                          <th>Aksi</th>
                          <th>#</th>
                          <th>Jabatan</th>
                          <th>Kode Panggi</th>
                          <th>Wilayah</th>
                          <th>PLT</th>
                        </tr>
                    </thead>
                    <tbody>
                      <?php
                      $i = 1;
                      foreach ($list_jab as $jab) {
                        $retVal = (is_null($jab['id']) ) ? 'N/A' : $jab['nama'].' ('.$jab['NRK'].'/ '.$jab['NIP'].')' ;
                        echo '
                        <tr>
                          <td><a href="'.base_url().'kabid_sapras/detail_plt/'.$jab['id_mj'].'" class="btn btn-primary btn-sm" role="button"><i data-feather="arrow-up-right"></i></a></td>
                          <td>'.$i.'</td>
                          <td>'.$jab['nama_jabatan'].'</td>
                          <td>'.$jab['kode_panggil'].'</td>
                          <td>'.$jab['keterangan'].'</td>
                          <td>'.$retVal.'</td>
                        </tr>
                        ';
                        $i++;
                      }
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
        
       