    <!-- App Capsule -->
    <div id="appCapsule">

        <div class="card text-white bg-info my-2 mx-1">
            <div class="card-body">
                <h5 class="card-title">Progress anda</h5>
                    <p><? echo $progress ?> dari <? echo $numJenisApd ?> data APD, selesai diinput</p>
                    <div class="progress">
                        <div class="progress-bar bg-warning" role="progressbar" style="width: <? echo $persenProgress ?>%;" aria-valuenow="50"
                            aria-valuemin="0" aria-valuemax="100"><? echo $persenProgress ?>%</div>
                    </div>
            </div>
        </div>

        <? //d($buttonProp) ?>

        <div class="section mt-2">
            <div class="row">
                <?php
                    foreach($jenisApd as $apd){
                        echo '
                        <div class="col-6 mb-2">
                            <div class="card product-card">
                                <div class="card-body">
                                    <img src="'.base_url().'assets/img/petugas/lapor/'.$apd['picture'].'" class="image" alt="APD">
                                    <h2 class="title text-center">'.$apd['jenis_apd'].'</h2>
                                    <a href="'.base_url().'petugas/APD/'.$apd['id_mj'].'" class="btn btn-sm '.$apd['buttonProp']->color.' btn-block '.$apd['buttonProp']->disabled.'">'.$apd['buttonProp']->message.'</a>
                                </div>
                            </div>
                        </div>
                        ';
                    }
                ?>
            </div>
        </div>