    <div class="page-content">
      <div class="main-wrapper">
        <div class="col-12">
          <div class="card bg-info">
            <div class="card-body">
              <? $retVal = ($is_open) ? 'dibuka' : 'ditutup' ; ?>
              <h5 class="text-white">Periode Input data APD <? echo $retVal; ?> </h5>
              <p class="text-white"><? echo $info_periode_input ?></p>
            </div>
          </div>
        </div>

        <?php
        //d($jenisApd, $progress, $buttonProp);
        $jmlAPD = $progress[0];
        $jmlInput = $progress[1];
        $jmlVerif = $progress[2];
        $jmlTolak = $progress[3];
        //$jmlInputApd = count($listAPD);
        $persenInputApd = round(($jmlInput)/$jmlAPD*100, 0);
        $persenVerfApd = round($jmlVerif/$jmlAPD*100, 0);
        $persenApdDitolak = round($jmlTolak/$jmlAPD*100, 0);
        echo '
        <div class="card mb-2" >
            <div class="card-body">
                <h5 class="card-title">Progress input APD</h5>
                <p class="mb-0">'.$jmlInput.' dari '.$jmlAPD.' APD telah diinput</p>
                <div class="progress mb-1">
                    <div class="progress-bar bg-primary" role="progressbar" style="width: '.$persenInputApd.'%;" aria-valuenow="'.$persenInputApd.'"
                        aria-valuemin="0" aria-valuemax="100">'.$persenInputApd.'%</div>
                </div>

                <p class="mb-0">'.$jmlVerif.' dari '.$jmlAPD.' APD telah terverifikasi</p>
                <div class="progress mb-1">
                    <div class="progress-bar bg-success" role="progressbar" style="width: '.$persenVerfApd.'%;" aria-valuenow="'.$persenVerfApd.'"
                        aria-valuemin="0" aria-valuemax="100">'.$persenVerfApd.'%</div>
                </div>

                <p class="mb-0">'.$jmlTolak.' APD ditolak</p>
                <div class="progress mb-1">
                    <div class="progress-bar bg-danger" role="progressbar" style="width: '.$persenApdDitolak.'%;" aria-valuenow="'.$persenApdDitolak.'"
                        aria-valuemin="0" aria-valuemax="100">'.$persenApdDitolak.'%</div>
                </div>

            </div>
        </div>

        <div class="row">
        ';
        foreach ($jenisApd as $apd) {
          $btnCol = 'btn-success';
          if ($apd['buttonProp']->message == 'Lengkapi') {
            $btnCol = 'btn-danger';
          } else if ($apd['buttonProp']->message == 'Proses Verifikasi'){
            $btnCol = 'btn-secondary';
          }
          $url = ($apd['buttonProp']->disabled == 'disabled') ? '#' : base_url().$controller.'/laporAPD/'.$apd['id_mj'] ;
          echo '
            <div class="col-4">
              <div class="card text-center">
                <div class="card-body">
                  <h5 class="card-title">'.$apd['jenis_apd'].'</h5>
                  <p class="text-muted">'.$apd['buttonProp']->message.'</p>
                  <a href="'.$url.'" class="btn '.$btnCol.'">Lapor</a>
                </div>
              </div>
            </div>
          ';
        }

        echo '</div>';


        ?>

        

      </div>
    </div>

