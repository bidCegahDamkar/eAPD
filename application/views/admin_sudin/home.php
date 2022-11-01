    <div class="page-content">
      <div class="main-wrapper">
        <? //d($listNamaJenisAPD, $list_sudin, $list_sektor, $list_pos); 
            //d($kode_pos_min); ?>
        <?php $now_time = date("H:i:s", now('Asia/Jakarta')); ?>
        <div class="row">
          <div class="col-12">
            <div class="card bg-primary">
                <div class="card-body">
                    <h3 class="text-white"><?= greeting($now_time); ?></h3>
                    <h5 class="text-white mb-0"><?php echo $username; ?></h5>
                    <h5 class="text-white mb-0"><?php echo $jabatan['nama_jabatan']; ?></h5>
                    <h5 class="text-white mb-0"><?php echo $sudin; ?></h5>
                </div>
            </div>
          </div>
          <div class="col-12">
            <div class="card bg-info">
                <div class="card-body">
                <? $retVal = ($is_open) ? 'dibuka' : 'ditutup' ; ?>
                <h5 class="text-white">Periode Verifikasi data APD <? echo $retVal; ?> </h5>
                <p class="text-white"><? echo $info_periode_input ?></p>
                </div>
            </div>
          </div>

          <?php 
          $data_sudin = $list_sudin; 
          //$persen_input = round( $data_sudin['jml_input']/(($data_sudin['jml_pns']+$data_sudin['jml_pjlp'])*$jumJenisApd)*100,2 );
          //$persen_verif = round( $data_sudin['jml_verif']/(($data_sudin['jml_pns']+$data_sudin['jml_pjlp'])*$jumJenisApd)*100,2 );
          ?>

          <div class="col-md-6 col-xl-3">
            <div class="card stat-widget">
              <div class="card-body">
                <h5 class="card-title">Data Petugas (PNS)</h5>
                <h2><? echo $data_sudin['jml_pns']; ?></h2>
                <? $persen = round( ($data_sudin['jml_pns']/($data_sudin['jml_pns']+$data_sudin['jml_pjlp'])*100),1 ) ?>
                <p>Jumlah PNS</p>
                <div class="progress">
                  <div class="progress-bar bg-danger progress-bar-striped" role="progressbar" style="width: <? echo $persen; ?>%" aria-valuenow="<? echo $persen; ?>" aria-valuemin="0" aria-valuemax="100"></div>
                </div>
              </div>
            </div>
          </div>
          <div class="col-md-6 col-xl-3">
            <div class="card stat-widget">
              <div class="card-body">
                <h5 class="card-title">Data Petugas (PJLP)</h5>
                <h2><? echo $data_sudin['jml_pjlp']; ?></h2>
                <? $persen = round( ($data_sudin['jml_pjlp']/($data_sudin['jml_pns']+$data_sudin['jml_pjlp'])*100),1 ) ?>
                <p>Jumlah PJLP</p>
                <div class="progress">
                  <div class="progress-bar bg-warning progress-bar-striped" role="progressbar" style="width: <? echo $persen; ?>%" aria-valuenow="<? echo $persen; ?>" aria-valuemin="0" aria-valuemax="100"></div>
                </div>
              </div>
            </div>
          </div>
          <div class="col-md-6 col-xl-3">
            <div class="card stat-widget">
              <div class="card-body">
                <h5 class="card-title">Input APD</h5>
                <h2><? echo '<span class="badge rounded-pill '.badge_bg_color($persen_input).'">'.$persen_input.' %</span>'; ?></h2>
                <p>Persentase</p>
                <div class="progress">
                  <div class="progress-bar bg-info progress-bar-striped" role="progressbar" style="width: <? echo $persen_input; ?>%" aria-valuenow="<? echo $persen_input; ?>" aria-valuemin="0" aria-valuemax="100"></div>
                </div>
              </div>
            </div>
          </div>
          <div class="col-md-6 col-xl-3">
            <div class="card stat-widget">
              <div class="card-body">
                <h5 class="card-title">APD Terverifikasi</h5>
                <h2><? echo '<span class="badge rounded-pill '.badge_bg_color($persen_verif).'">'.$persen_verif.' %</span>'; ?></h2>
                <p>Persentase</p>
                <div class="progress">
                  <div class="progress-bar bg-success progress-bar-striped" role="progressbar" style="width: <? echo $persen_verif; ?>%" aria-valuenow="<? echo $persen_verif; ?>" aria-valuemin="0" aria-valuemax="100"></div>
                </div>
              </div>
            </div>
          </div>
        </div>
        <div class="row">          
          <div class="col-6">
            <div class="card stat-widget">
                <div class="card-body">
                    <div id="chart_input"></div>
                </div>
            </div>
          </div>
          <div class="col-6">
            <div class="card stat-widget">
                <div class="card-body">
                    <div id="chart_verif"></div>
                </div>
            </div>
          </div>
        </div>
        <div class="row">
          <?php
          echo '<div class="col-6">';
          for ($i=0; $i < count($list_pos); $i+=2) { 
            $pos = $list_pos[$i];
            echo '
              <div class="card">
                  <div class="card-body">
                      <h5 class="mb-0">Data Sektor '.$pos['sektor'].'</h5>
                      <div class="table-sm">
                        <table class="table">
                          <thead>
                            <tr>
                              <th scope="col">POS</th>
                              <th scope="col">PNS</th>
                              <th scope="col">PJLP</th>
                              <th scope="col">Persen Input</th>
                              <th scope="col">Persen Terverif</th>
                            </tr>
                          </thead>
                          <tbody>';
                              foreach ($pos['data'] as $data) {
                                $jum_peg1 = $data['jml_pns']+$data['jml_pjlp'];
                                $jmlApd = (($data['jml_ops']*$jmlJenisApdOps)+($data['jml_non_ops']*$jmlJenisApdNons));
                                if ($jum_peg1 == 0) {
                                  echo '
                                  <tr>
                                      <td>No data</td>
                                  </tr>
                                  ';
                                } else {
                                  $persen_input = round( $data['jml_input']/($jmlApd)*100,2 );
                                  $persen_verif = round( $data['jml_verif']/($jmlApd)*100,2 );
                                  echo '
                                  <tr>
                                      <td>'.$data['nama_pos'].'</td>
                                      <td>'.$data['jml_pns'].'</td>
                                      <td>'.$data['jml_pjlp'].'</td>
                                      <td><span class="badge '.badge_bg_color($persen_input).'">'.$persen_input.' %</span></td>
                                      <td><span class="badge '.badge_bg_color($persen_verif).'">'.$persen_verif.' %</span></td>
                                  </tr>
                                  ';
                                }
                              }
              echo '
                          </tbody>
                        </table>
                      </div>
                  </div>
              </div>
            ';
          }
          echo '</div>';

          echo '<div class="col-6">';
          for ($j=1; $j < count($list_pos); $j+=2) { 
            $pos = $list_pos[$j];
            echo '
              <div class="card">
                  <div class="card-body">
                      <h5 class="mb-0">Data Sektor '.$pos['sektor'].'</h5>
                      <div class="table-sm">
                        <table class="table">
                          <thead>
                            <tr>
                              <th scope="col">POS</th>
                              <th scope="col">PNS</th>
                              <th scope="col">PJLP</th>
                              <th scope="col">Persen Input</th>
                              <th scope="col">Persen Terverif</th>
                            </tr>
                          </thead>
                          <tbody>';
                              foreach ($pos['data'] as $data) {
                                $jum_peg2 = $data['jml_pns']+$data['jml_pjlp'];
                                $jmlApd = (($data['jml_ops']*$jmlJenisApdOps)+($data['jml_non_ops']*$jmlJenisApdNons));
                                if ($jum_peg2 == 0) {
                                  echo '
                                  <tr>
                                      <td>No data</td>
                                  </tr>
                                  ';
                                } else {
                                  $persen_input = round( $data['jml_input']/($jmlApd)*100,2 );
                                  $persen_verif = round( $data['jml_verif']/($jmlApd)*100,2 );
                                  echo '
                                  <tr>
                                      <td>'.$data['nama_pos'].'</td>
                                      <td>'.$data['jml_pns'].'</td>
                                      <td>'.$data['jml_pjlp'].'</td>
                                      <td><span class="badge '.badge_bg_color($persen_input).'">'.$persen_input.' %</span></td>
                                      <td><span class="badge '.badge_bg_color($persen_verif).'">'.$persen_verif.' %</span></td>
                                  </tr>
                                  ';
                                }
                              }
              echo '
                          </tbody>
                        </table>
                      </div>
                  </div>
              </div>
            ';
          }
          echo '</div>';
          ?>
        
      </div>
    </div>
      
</div> <!-- page-container -->
        
       