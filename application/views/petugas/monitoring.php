 <!-- Extra Header -->
 <div class="extraHeader">
    <form class="search-form" action="<? echo base_url().$controller.'/monitoring'; ?>" method="GET">
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

<!-- App Capsule -->
<div id="appCapsule">
    <? //d($admin); ?>
    <div class="section full mt-4 mb-2">
        <div class="section-title pt-4"><? echo $section_tittle; ?></div>
            <ul class="listview image-listview mb-2">
                
                <?php
                foreach ($list_bawahan as $ls) {
                    $path = base_url().'upload/petugas/profil/thumb/';
                    $photo = (! is_null($ls['photo'])) ? 'thumb_'.$ls['photo'] : 'default.png' ;
                    echo '
                    <li class="multi-level">
                        <a href="#" class="item">
                            <div class="imageWrapper mr-1">
                                <img src="'.$path.$photo.'" alt="image" class="image">
                            </div>
                            <div class="in mx-0 px-0">
                                <div class="col-12 pl-0">
                                    '.$ls['nama'].'
                                    <div class="row pl-0 ml-0">
                                            <div class="col-6">% input</div>
                                            <div class="col-6">: <span class="badge-sm '.badge_color($ls['persen_inputAPD']).'">'.$ls['persen_inputAPD'].' %</span></div>
                                    </div>
                                    <div class="row pl-0 ml-0">
                                            <div class="col-6">% terverifikasi</div>
                                            <div class="col-6">: <span class="badge-sm '.badge_color($ls['persen_APDterverif']).'">'.$ls['persen_APDterverif'].' %</span></div>
                                    </div>
                                </div>
                            </div>
                        </a>
                        <!-- sub menu -->
                        <ul class="listview px-4">
                            <div class="row">
                                <div class="col-3">NRK</div>
                                <div class="col-9">: '.$ls['NRK'].'</div>
                            </div>
                            <div class="row">
                                <div class="col-3">Jabatan</div>
                                <div class="col-9">: '.$ls['nama_jabatan'].'</div>
                            </div>
                            <div class="row">
                                <div class="col-3">status</div>
                                <div class="col-9">: '.$ls['status'].'</div>
                            </div>
                            <div class="row">
                                <div class="col-3">Penugasan</div>
                                <div class="col-9">: '.$ls['nama_pos'].'</div>
                            </div>
                            <div class="row">
                                <div class="col-12">Jumlah APD ditolak : '.$ls['jml_ditolak'].'</div>
                            </div>
                            <div class="row">
                                <div class="col-3">No Telp</div>
                                <div class="col-9">: '.$ls['no_telepon'].'</div>
                            </div>
                            <div class="row">
                                <div class="col-3">email</div>
                                <div class="col-9">: '.$ls['email'].'</div>
                            </div>
                        </ul>
                        <!-- * sub menu -->
                    </li>
                    ';
                }
                ?>
            </ul>
            <? //d($kode_pos); ?>
    </div>