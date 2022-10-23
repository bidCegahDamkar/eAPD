<!-- Extra Header -->
<div class="extraHeader">
    <form class="search-form" action="<? echo base_url().$controller.'/tervalidasi'; ?>" method="GET">
        <div class="form-group input-group searchbox">
            <?php
            $retVal = (! is_null($search) ) ? 'value="'.$search.'"' : '' ;
            echo '<input name="cari" type="text" class="form-control" '.$retVal.' placeholder="Cari berdasarkan nama/NRK/pos/jabatan">';
            ?>
            
            <i class="input-icon">
                <ion-icon name="search-outline"></ion-icon>
            </i>
            <div class="input-group-append">
                <button class="btn btn-outline-secondary" type="submit">Cari</button>
            </div>
        </div>
    </form>
</div>
<!-- * Extra Header -->

<div id="appCapsule">
    <? //d($ApdUser); ?>
    <div class="section full mt-4 mb-2">
        <div class="section-title pt-4"><? echo $section_tittle; ?></div>
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
                        <a href="'.base_url().$controller.'/APDtervalidasi/'.$user['id'].'" class="item">
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