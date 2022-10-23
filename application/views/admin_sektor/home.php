<main class="container">
  <? //d($temp);?>
  <div class="bg-primary text-white p-5 rounded">
    <h1>Selamat Datang</h1>
    <div class="row">
      <div class="col-12">
        <h4><? echo $username; ?></h4>
      </div>
    </div>
  </div>

  <div class="bg-warning mt-2 px-5 py-3 rounded">
    <? $retVal = ($is_open) ? 'dibuka' : 'telah ditutup' ; ?>
    <h5 class="card-title">Periode Verifikasi data APD <? echo $retVal; ?> </h5>
    <p><? echo $info_periode_input ?></p>
  </div>

  <div class="bg-secondary text-white p-5 my-2 rounded">
    
    <h1>Rekap Data Petugas dan APD di Sektor anda </h1>
    <h5>Periode Input : <? echo $periode; ?></h5>
    <? $thead = ['Jumlah PNS', 'Jumlah PJLP', 'Jumlah Pegawai yang belum selesai 100% input APD', 'Jumlah Pegawai yang telah selesai 100% input APD', 'Jumlah total APD',
                  'Jumlah APD belum diverifikasi', 'Jumlah APD terverifikasi', 'Jumlah APD ditolak']; 
       $dhead = [$jmlPNS, $jmlPJLP, $jmlBlmInput, $jumSdhInput, $jmlApd, $jmlNotVerApd, $jmlVerApd, $jmlRefuseApd];
       $ket = ['orang', 'orang', 'orang', 'orang', 'item', 'item','item','item'];
    ?>

    <div class="lead">
    <? 
    for ($i=0; $i < 8; $i++) { 
      echo '
      <div class="row">
        <div class="col-5">
          '.$thead[$i].'
        </div>
        <div class="col-7">
          : <span class="badge bg-primary rounded-pill">'.$dhead[$i].'</span>  '.$ket[$i].'
        </div>
      </div>
      ';
    }
    ?>
    </div>
  </div>
</main>
