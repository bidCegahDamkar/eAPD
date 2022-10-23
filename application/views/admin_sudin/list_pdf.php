    <div class="page-content">
      <div class="main-wrapper">
        <? //d($list_report); ?>
        <div class="row justify-content-md-center">
          <div class="col-8">
            <div class="card">
              <div class="card-body">
                <div class="table-responsive">
                  <table class="stripe row-border order-column home-myTable" style="width:100%">
                    <thead>
                        <tr>
                          <th>Aksi</th>
                          <th>#</th>
                          <th>Jenis Laporan</th>
                          <th>Periode</th>
                          <th>Tanggal Update</th>
                        </tr>
                    </thead>
                    <tbody>
                      <?php
                      $i = 1;
                      foreach ($list_report as $report) {
                        echo '
                        <tr>
                          <td><a href="'.base_url().'upload/pdf/'.$report['filename'].'" target="_blank" class="btn btn-primary btn-sm" role="button"><i data-feather="arrow-up-right"></i></a></td>
                          <td>'.$i.'</td>
                          <td>'.$report['nama_laporan'].'</td>
                          <td>'.$report['periode'].'</td>
                          <td>'.msqlDate2html($report['create_at']).'</td>
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
        
       