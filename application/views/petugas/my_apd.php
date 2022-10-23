<!-- App Capsule -->
<div id="appCapsule">
    <? //d($dataAPD); ?>

    <?php
    $i = 0;
    $upload_path = 'upload/petugas/APD/';
    $web_path = base_url().'upload/petugas/APD/';
    foreach ($dataAPD as $apd) {
        if($apd['mkp_id'] != 3){
            $foto = (file_exists($upload_path.$apd['foto_apd']) && !is_null($apd['foto_apd'])) ? $apd['foto_apd'] : 'no-preview.jpg' ;
            echo '
            <div class="section mt-2">
                <div class="card">
                    <img src="'.$web_path.$foto.'" class="card-img-top" alt="image">
                    <div class="card-body">
                        <h5 class="card-title">'.$apd['jenis_apd'].'</h5>';
                        for ($j=0; $j < 6 ; $j++) { 
                            echo '<p class="card-text mb-0">'.$thead1[$j].' : '.$apd[$dhead1[$j]].'</p>';
                        }
            echo '
                    </div>
                </div>
            </div>
            ';
        }else{
            echo '
            <div class="section mt-2">
                <div class="card">
                    <div class="card-body">
                        <h5 class="card-title">'.$apd['jenis_apd'].'</h5>';
                        for ($j=0; $j < 2 ; $j++) { 
                            echo '<p class="card-text mb-0">'.$thead2[$j].' : '.$apd[$dhead2[$j]].'</p>';
                        }
            echo '
                    </div>
                </div>
            </div>
            ';
        }
        # code...
    }
    ?>
