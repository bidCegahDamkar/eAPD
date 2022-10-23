<!-- App Capsule -->
<div id="appCapsule">
    <? //d($ApdUser, $listUser); ?>
    <div class="section full mb-2">
        <div class="section-title pt-4">Daftar pegawai yang telah input data APD</div>
        <ul class="listview image-listview">
            <?php
            if (count($ApdUser) < 1) {
                echo '<li class="ml-4">tidak ada data</li>';
            } else {
                foreach ($ApdUser as $user) {
                    $path = base_url().'upload/petugas/profil/';
                    $photo = (! is_null($user['photo'])) ? $user['photo'] : 'default.png' ;
                    echo '
                    <li>
                        <a href="'.base_url().$controller.'/verifikasiAPD/'.$user['id'].'" class="item">
                            <img src="'.$path.$photo.'" alt="image" class="image">
                            <div class="in">
                                <div>
                                    '.$user['nama'].'
                                    <header>'.$user['NRK'].'/ '.$user['NIP'].'</header>
                                    <header>'.$user['nama_jabatan'].'</header>
                                </div>
                            </div>
                        </a>
                    </li>
                    ';
                }
            }
            ?>
        </ul>

    </div>