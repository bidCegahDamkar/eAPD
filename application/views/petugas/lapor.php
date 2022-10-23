    <!-- App Capsule -->
    <div id="appCapsule">

        <div class="card text-white bg-info my-2 mx-1">
            <div class="card-body">
                <h5 class="card-title">Progress anda</h5>
                    <?php
                    $words = ['terinput', 'tervalidasi', 'ditolak'];
                    $colors = ['primary', 'success', 'danger'];
                    $i = 1;
                    foreach ($words as $value) {
                        $persen = round($progress[$i]/$progress[0]*100, 0);
                        echo '
                        <p class="mb-0">'.$progress[$i].' dari '.$progress[0].' data APD, '.$value.'</p>
                        <div class="progress mb-1">
                            <div class="progress-bar bg-'.$colors[$i-1].'" role="progressbar" style="width: '.$persen.'%;" aria-valuenow="'.$persen.'"
                                aria-valuemin="0" aria-valuemax="100">'.$persen.'%</div>
                        </div>
                        ';
                        $i++;
                    }
                    ?>
            </div>
        </div>

        <? //d($user_roles) ?>

        <div class="section mt-2">
            <div class="row">
                <?php
                    
                    foreach($jenisApd as $apd){
                        $url = ($apd['buttonProp']->disabled == 'disabled') ? '#' : base_url().$controller.'/laporAPD/'.$apd['id_mj'] ;
                        echo ' 
                        <div class="col-6 mb-2">
                            <a style="width:100%; height:100%; display:block" href="'.$url.'" >
                            <div class="card product-card">
                                <div class="card-body p-0">
                                    <img src="'.base_url().'assets/img/petugas/lapor/'.$apd['picture'].'" class="image" alt="APD">
                                    <h2 class="title text-center" style="position: absolute; bottom: 0px; left: 0; right: 0; background: '.$apd['buttonProp']->color.'; color: white; ">'.$apd['buttonProp']->message.'</h2>
                                </div>
                            </div>
                            </a>
                        </div>
                        ';
                    }
                ?>
            </div>
        </div>