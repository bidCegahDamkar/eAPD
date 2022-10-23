    <div class="page-content">
        <div class="main-wrapper">
            <? //d($dataAPD); ?>
            <nav aria-label="breadcrumb">
                <ol class="breadcrumb">
                    <li class="breadcrumb-item"><a href="#">Home</a></li>
                        <li class="breadcrumb-item"><a href="#">Library</a></li>
                    <li class="breadcrumb-item active" aria-current="page">Data</li>
                </ol>
            </nav>
            <?php
            $foto = (is_null($userData['photo'])) ? base_url().'assets/img/default-red.png' : base_url().'upload/petugas/profil/'.$userData['photo'] ;
            ?> 
            <div class="row">
                <div class="col-xl-12">
                    <div class="profile-cover"></div>
                    <div class="profile-header">
                        <div class="profile-img">
                            <img src="<?php echo $foto; ?>" alt="">
                        </div>
                        <div class="profile-name">
                            <h3><? echo $userData['nama']; ?></h3>
                        </div>
                    </div>
                </div>
            </div>
            <div class="row">
                <div class="col-md-12 col-lg-3">
                    <div class="card">
                        <div class="card-body">
                            <h5 class="card-title">Progress Input APD</h5>
                            <p>Periode Input : </p>
                            <p class="mb-0">Persentase Input APD: (<? echo $userData['persen_inputAPD']; ?> %)</p>
                                <div class="progress mb-3">
                                    <div class="progress-bar bg-primary" role="progressbar" style="width: <? echo $userData['persen_inputAPD']; ?>%;" aria-valuenow="<? echo $userData['persen_inputAPD']; ?>"
                                        aria-valuemin="0" aria-valuemax="100">
                                    </div>
                                </div>
                                <p class="mb-0">Persentase APD Tervalidasi: (<? echo $userData['persen_APDterverif']; ?> %)</p>
                                <div class="progress mb-1">
                                    <div class="progress-bar bg-success" role="progressbar" style="width: <? echo $userData['persen_APDterverif']; ?>%;" aria-valuenow="<? echo $userData['persen_APDterverif']; ?>"
                                        aria-valuemin="0" aria-valuemax="100">
                                    </div>
                                </div>
                        </div>
                    </div>
                </div>
                <div class="col-md-12 col-lg-6">
                    <div class="card">
                        <div class="card-body">
                            <h5 class="card-title">Biodata</h5>
                            <ul class="list-unstyled profile-about-list">
                                <li><span class="row"><div class="col-4">Nama</div><div class="col-8">: <? myPrint($userData['nama']); ?></div></span></li>
                                <li><span class="row"><div class="col-4">NRK/ NIP</div><div class="col-8">: <? myPrint($userData['NRK']); ?>/ <? myPrint($userData['NIP']); ?></div></span></li>
                                <li><span class="row"><div class="col-4">Jabatan</div><div class="col-8">: <? myPrint($userData['nama_jabatan']); ?></div></span></li>
                                <li><span class="row"><div class="col-4">Tempat Tugas</div><div class="col-8">: <? myPrint($userData['nama_pos']); ?></div></span></li>
                                <li><span class="row"><div class="col-4">Status Pegawai</div><div class="col-8">: <? myPrint($userData['status']); ?></div></span></li>
                                <li><span class="row"><div class="col-4">email</div><div class="col-8">: <? myPrint($userData['email']); ?></div></span></li>
                                <li><span class="row"><div class="col-4">No Telp</div><div class="col-8">: <? myPrint($userData['no_telepon']); ?></div></span></li>
                            </ul>
                        </div>
                    </div>
                </div>
                <div class="col-md-12 col-lg-3">
                    <div class="card">
                        <div class="card-body">
                            <h5 class="card-title">Data Fisik</h5>
                            <ul class="list-unstyled profile-about-list">
                                <li><span>Tanggal Lahir : <? myPrint($userData['tgl_lahir']); ?></span></li>
                                <li><span>Tinggi Badan: <? myPrint($userData['tinggi_badan']); ?> cm</span></li>
                                <li><span>Berat Badan: <? myPrint($userData['berat_badan']); ?> kg</span></li>
                                <li><span>Ukuran Baret: <? myPrint($userData['uk_baret']); ?></span></li>
                                <li><span>Ukuran Kaos: <? myPrint($userData['uk_kaos']); ?></span></li>
                                <li><span>Ukuran Baju: <? myPrint($userData['uk_baju']); ?></span></li>
                                <li><span>Ukuran Jaket: <? myPrint($userData['uk_jaket']); ?></span></li>
                                <li><span>Ukuran Gloves: <? myPrint($userData['uk_gloves']); ?></span></li>
                                <li><span>Ukuran Celana: <? myPrint($userData['uk_celana']); ?></span></li>
                                <li><span>Ukuran Sepatu: <? myPrint($userData['uk_sepatu']); ?></span></li>
                            </ul>
                        </div>
                    </div>
                </div>
            </div>
            <div class="row">
                <div class="col-md-12 col-lg-12">
                    <div class="card">
                        <div class="card-body">
                            <h5 class="card-title">Data APD</h5>
                            
                            <div class="table-responsive">
                                <table id="list-user-apd" class="stripe row-border order-column" style="width:100%">
                                    <thead>
                                        <tr>
                                            <th scope="col">Aksi</th>
                                            <th scope="col">#</th>
                                            <th scope="col">Jenis APD</th>
                                            <th scope="col">Status</th>
                                            <th scope="col">Keberadaan</th>
                                            <th scope="col">Kondisi</th>
                                            <th scope="col">Foto</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                    </tbody>
                                </table>
                            </div>
                            
                        </div>
                    </div>
                </div>
            </div>
        </div>
                                  
    </div>
      
</div> <!-- page-container -->
        
       