    <div class="page-content">
      <div class="main-wrapper">
        <? //d($result); ?>
        <div class="row justify-content-md-center">
          <div class="col-10">
            <div class="card">
              <div class="card-body">
                <h5 class="card-title"><?= $pageTitle ?></h5>
                <h5><?= $sudin ?></h5>
                <h5>per Tanggal : <?= sqlDate2htmlminute($my_time) ?></h5>
                <div class="table-responsive">
                  <table class="table table-striped table-bordered" >
                    <thead class="table-dark strong">
                        <tr>
                          <th rowspan="2"><strong>#</strong></th>
                          <th rowspan="2"><strong>Jenis APD</strong></th>
                          <th colspan="4" class="text-center"><strong>Kondisi</strong></th>
                          <th colspan="2" class="text-center"><strong>Keberadaan</strong></th>
                          <th rowspan="2"><strong>subTotal</strong></th>
                        </tr>
                        <tr>
                          <th><strong>Baik</strong></th>
                          <th><strong>Rusak Ringan</strong></th>
                          <th><strong>Rusak Sedang</strong></th>
                          <th><strong>Rusak Berat</strong></th>
                          <th><strong>Belum Terima</strong></th>
                          <th><strong>Hilang</strong></th>
                        </tr>
                    </thead>
                    <tbody>
                      <?php
                      $i = 1;
                      foreach ($result['data'] as $rows) {
                        echo '<tr>
                                <td>'.$i.'</td>
                                <td>'.$rows[0].'</td>';
                        for ($j=1; $j < count($rows); $j++) { 
                          $retVal = ($j == 7) ? 'class="text-danger"' : '' ;
                          echo '<td class="text-center">
                                  <a '.$retVal.' href="'.base_url().$controller.'/report_rekap_detail/'.$rows[$j]['id_mj'].'/'.$rows[$j]['tipe'].'/'.$rows[$j]['par'].'" >
                                    '.$rows[$j]['val'].'
                                  </a>
                                </td>';
                        }
                        echo '</tr>';
                        $i++;
                      }
                      echo '<tr>
                              <td colspan="2">'.$result['subtotal'][0].'</td>';
                      for ($k=1; $k < count($result['subtotal']); $k++) { 
                        $retVal1 = ($k == 7) ? '<strong>' : '' ;
                        $retVal2 = ($k == 7) ? '</strong>' : '' ;
                        echo '<td class="text-center">
                                '.$retVal1.'
                                <a class="text-danger" href="'.base_url().$controller.'/report_rekap_detail/'.$result['subtotal'][$k]['id_mj'].'/'.$result['subtotal'][$k]['tipe'].'/'.$result['subtotal'][$k]['par'].'" >
                                  '.$result['subtotal'][$k]['val'].'
                                </a>
                                '.$retVal2.'
                              </td>';
                      }
                      echo '<tr>';
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
    <style type="text/css">
      td a{display:block;position:relative}
      td a:hover{background:blue;color:#fff}
    </style>
</div> <!-- page-container -->
        
       