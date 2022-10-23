    <div class="page-content">
      <div class="main-wrapper">
        <? //d($ApdUser); ?>
        <div class="row">
          <div class="col-12">
            <div class="card">
              <div class="card-body">
                <div class="table-responsive">
                  <table id="myTable" class="display home-myTable" style="width:100%">
                    <thead>
                        <tr>
                            <th>#</th>
                            <th>Nama</th>
                            <th>NRK</th>
                            <th>NIP</th>
                            <th>Jabatan</th>
                            <th>Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                      <?php
                      $i = 1;
                      foreach ($ApdUser as $user) {
                        echo '
                        <tr>
                          <td>'.$i.'</td>
                          <td>'.$user['nama'].'</td>
                          <td>'.$user['NRK'].'</td>
                          <td>'.$user['NIP'].'</td>
                          <td>'.$user['nama_jabatan'].'</td>
                          <td><a href="'.base_url().$controller.'/'.$url2.'/'.$user['id'].'" class="btn btn-primary" role="button">'.$action.'</a></td>
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
        
       