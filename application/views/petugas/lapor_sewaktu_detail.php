<div id="appCapsule">
    <? //d($detail_lap_sewaktu); ?>

    <?php
    $picture = (! is_null($detail_lap_sewaktu['photo'])) ? $detail_lap_sewaktu['photo'] : 'no-preview.jpg' ;
    $pict_url = base_url().'upload/petugas/laporan_sewaktu/'.$picture;
    $jenis_lap = ($detail_lap_sewaktu['jenis_laporan'] == 1) ? 'Kerusakan APD' : 'Kehilangan APD' ;
    $histroy = json_decode($detail_lap_sewaktu['history'], true);
    //d($histroy);
    ?>
    <div class="section mt-2">
        <div class="card">
            <img src="<? echo $pict_url; ?>" class="card-img-top" alt="image">
            <div class="card-body">
                <h5 class="card-title"><? echo $detail_lap_sewaktu['jenis_apd']; ?></h5>
                <div class="col-12">
                    Merk : <? echo $detail_lap_sewaktu['merk']; ?> 
                </div>
                <div class="col-12">
                    Tahun : <? echo $detail_lap_sewaktu['tahun']; ?> 
                </div>
                <div class="col-12">
                    Jenis Laporan : <? echo $jenis_lap; ?>
                </div>
                <div class="col-12">
                    Deskripsi : <? echo $detail_lap_sewaktu['deskripsi_laporan']; ?>
                </div>
                <div class="col-12">
                    Tanggal Laporan : <? echo sqlDate2htmlminute($detail_lap_sewaktu['create_at']); ?> 
                </div>
                <?
                if (! is_null($detail_lap_sewaktu['admin_respon'])) {
                    echo '
                    <div class="col-12">
                        Keterangan admin : '.$detail_lap_sewaktu['admin_respon'].'
                    </div>
                    ';
                }
                ?>
                <div class="section full mt-2">
                    <div class="section-title">Progress Timeline</div>
                        <div class="wide-block">
                            <!-- timeline -->
                            <div class="timeline timed">
                                <?php
                                $num = count($histroy);
                                for ($i=0; $i < $num; $i++) { 
                                    echo '
                                    <div class="item">
                                        <span class="time">'.$histroy[$i]['time'].'</span>
                                        <div class="dot '.$histroy[$i]['color'].'"></div>
                                        <div class="content">
                                            <div class="text">'.$histroy[$i]['deskripsi'].'</div>
                                        </div>
                                    </div>
                                    ';
                                    if(($num-$i) == 1){
                                        echo '
                                        <div class="item">
                                            <span class="time"> ?? </span>
                                            <div class="dot"></div>
                                            <div class="content">
                                                <div class="text">'.$histroy[$i]['next_step'].'</div>
                                            </div>
                                        </div>
                                        ';
                                    }
                                }
                                ?>
                            </div>
                            <!-- * timeline -->
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>