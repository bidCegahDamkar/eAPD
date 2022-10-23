<!-- App Capsule -->
<div id="appCapsule">
    <div class="section full mt-1">
        <div class="row mx-2">
            <div class="col-6">
            </div>
            <? //d($dataAPD); 
            if(is_array($progress)){
                $disabled = ($progress['status'] > 0) ? 'disabled' : '' ;
            }else{
                $disabled = '';
            }
            
            ?>
            <div class="col-6">
                <button type="button" class="btn btn-icon btn-success mr-1 mb-1 float-right" onclick="location.href='<? echo base_url().$controller;?>/APD/'" <? echo $disabled ?>>
                    <ion-icon name="paper-plane-outline"></ion-icon>
                </button>
                <button type="button" class="btn btn-icon btn-primary mr-1 mb-1 float-right" onclick="location.href='<? echo base_url().$controller;?>/addAPD/<? echo $jenisApd['id_mj'] ?>'" <? echo $disabled ?>>
                    <ion-icon name="add-outline"></ion-icon>
                </button>
            </div>

            <div class="col-12">
                <h3> <? echo $jenisApd['jenis_apd']; ?> </h3>
            </div>
        </div>
    </div>
    <ul class="listview image-listview">
        <?php
        if(count($dataAPD) == 0)
        {
            echo '
            <li>
                <a href="#" class="item">
                    <div class="in">
                        <div>
                            Belum ada data untuk APD '.$jenisApd['jenis_apd'].'
                            <footer>Apabila anda memiliki APD ini, click tombol tambah kemudian lengkapi datanya dan click tombol kirim</footer>
                            <footer>Apabila anda memang belum menerima APD ini, biarkan kosong dan click tombol kirim</footer>
                        </div>
                    </div>
                </a>
            </li>
            ';
        }else{
            foreach($dataAPD as $APD){
                echo '
                <li>
                    <a href="'.$APD['href'].'" class="item">
                        <img src="'.base_url().'upload/petugas/APD/'.$APD['foto_apd'].'" alt="image" class="image">
                        <div class="in">
                            <div>
                                '.$APD['merk'].', '.$APD['tahun'].'
                                <header>'.$APD['keberadaan'].'; '.$APD['nama_kondisi'].', '.$APD['keterangan'].'</header>
                                <footer>Status : '.$APD['deskripsi'].'</footer>
                            </div>
                            <span class="text-muted">Edit</span>
                        </div>
                    </a>
                </li>
                ';
            }
        }
        
        ?>
    </ul>
</div>