      <div class="page-content">
        <div class="main-wrapper">
          <? //d($result); ?>
          <div class="row">
            <div class="col-12">
              <div class="card">
                <div class="card-body">
                  <h5 class="card-title"><?= $pageTitle ?></h5>
                  <div class="table-responsive">
                    <table id="" class="list-myData" style="width:100%" >
                      <thead>
                          <tr>
                            <th rowspan="2">#</th>
                            <th rowspan="2">Jenis APD</th>
                            <th rowspan="2">Keberadaan</th>
                            <th rowspan="2">Kondisi</th>
                            <th rowspan="2">Merk</th>
                            <th rowspan="2">Tahun</th>
                            <th rowspan="2">Periode Input</th>
                            <th colspan="3" class="text-center">Pemilik</th>
                          </tr>
                          <tr>
                            <th>Nama (NIP)</th>
                            <th>Jabatan</th>
                            <th>Tempat Tugas</th>
                          </tr>
                      </thead>
                      <tbody>
                        <?php
                        $i = 1;
                        foreach ($result as $row) {
                          echo '
                          <tr>
                            <td>'.$i.'</td>
                            <td>'.$row['jenis_apd'].'</td>
                            <td>'.$row['keberadaan'].'</td>
                            <td>'.$row['nama_kondisi'].'</td>
                            <td>'.$row['merk'].'</td>
                            <td>'.$row['tahun'].'</td>
                            <td>'.$row['periode_input'].'</td>
                            <td>'.$row['nama'].' ('.$row['NIP'].')</td>
                            <td>'.$row['nama_jabatan'].'</td>
                            <td>'.$row['nama_pos'].'</td>
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