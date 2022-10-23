<!-- Extra Header -->
<div class="extraHeader p-0">
    <ul class="nav nav-tabs lined" role="tablist">
        <?php
        $i = 0;
        foreach ($key_array as $key) {
            $href = preg_replace('/\s+/', '', $key);
            $active = ($i == 0) ? 'active' : '' ;
            echo '
            <li class="nav-item">
                <a class="nav-link '.$active.'" data-toggle="tab" href="#'.$href.'_'.$i.'" role="tab">
                    '.$key.'
                </a>
            </li>
            ';
            $i++;
        }
        ?>
    </ul>
</div>
<!-- * Extra Header -->

<!-- App Capsule -->
<div id="appCapsule"  class="extra-header-active">
    <? //d($dataAPD); ?>
    <div class="tab-content mt-1">

    <?php
    $i = 0;
    $upload_path = base_url().'upload/petugas/APD/';
    foreach ($key_array as $key) {
        $href = preg_replace('/\s+/', '', $key);
        $active = ($i == 0) ? 'show active' : '' ;
        echo '<div class="tab-pane fade '.$active.'" id="'.$href.'_'.$i.'" role="tabpanel">';
        foreach ($dataAPD[$key] as $apd) {
            if($apd['mkp_id'] != 3){
                $foto = (! is_null($apd['foto_apd'])) ? $apd['foto_apd'] : 'no-preview.jpg' ;
                echo '
                <div class="section mt-2">
                    <div class="card">
                        <img src="'.$upload_path.$foto.'" class="card-img-top" alt="image">
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
        echo '</div>';
        $i++;
    }
    ?>
    </div>
