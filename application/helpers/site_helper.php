<?php

//Regex to check date is in YYYY-MM-DD format
if(!function_exists('isValidDate'))
{
	function isValidDate($value)
	{
		if (preg_match("/^[0-9]{4}-(0[1-9]|1[0-2])-(0[1-9]|[1-2][0-9]|3[0-1])$/",$value)) {
			return true;
		} else {
			return false;
		}
	}
}

if(!function_exists('msqlDate2html'))
{
	function msqlDate2html($value)
	{
		if(is_null($value) || empty($value) || !isValidDate($value))
		{
			return NULL;
		}else
		{
			$date = date('Y-m-d',strtotime($value));
			$split = explode('-', $date);
            $tanggal = $split[2].'-'.$split[1].'-'.$split[0];
			$temp = date('Y-m-d',strtotime("1500-01-01"));
			if($date < $temp){
				$tgl = NULL;
			} else if ($date >= $temp) {
				$tgl = $tanggal;
			} else {
				$tgl = NULL;
			}
			return ($tgl);
		}
	}
}

if(!function_exists('sqlDate2html'))
{
	function sqlDate2html($value)
	{
		if(is_null($value) || empty($value) || !isValidDate($value))
		{
			return NULL;
		}else
		{
			$bulan = array ('01' => 'Januari',
							'02' => 'Februari',
							'03' => 'Maret',
							'04' => 'April',
							'05' => 'Mei',
							'06' => 'Juni',
							'07' => 'Juli',
							'08' => 'Agustus',
							'09' => 'September',
							'10' => 'Oktober',
							'11' => 'November',
							'12' => 'Desember',
							'00' => NULL
			);
			$date = date('Y-m-d',strtotime($value));
			$split = explode('-', $date);
			$tanggal = $split[2].'-'.$bulan[$split[1]].'-'.$split[0];
			$temp = date('Y-m-d',strtotime("1500-01-01"));
			if($date < $temp){
				$tgl = NULL;
			} else if ($date >= $temp) {
				$tgl = $tanggal;
			} else {
				$tgl = NULL;
			}
			return ($tgl);
		}
	}
}

if(!function_exists('sqlDate2htmlminute'))
{
	function sqlDate2htmlminute($value)
	{
		if(is_null($value) || empty($value) )
		{
			return NULL;
		}else
		{
			$bulan = array ('01' => 'Januari',
							'02' => 'Februari',
							'03' => 'Maret',
							'04' => 'April',
							'05' => 'Mei',
							'06' => 'Juni',
							'07' => 'Juli',
							'08' => 'Agustus',
							'09' => 'September',
							'10' => 'Oktober',
							'11' => 'November',
							'12' => 'Desember',
							'00' => NULL
			);
			$date = date('Y-m-d H:i:s', strtotime($value));
			//$date = date('Y-m-d',strtotime($value));
			$split1 = explode(' ', $date);
			$split2 = explode('-', $split1[0]);
			$tanggal = $split2[2].'-'.$bulan[$split2[1]].'-'.$split2[0].' '.$split1[1];
			$temp = date('Y-m-d',strtotime("1500-01-01"));
			if($date < $temp){
				$tgl = NULL;
			} else if ($date >= $temp) {
				$tgl = $tanggal;
			} else {
				$tgl = NULL;
			}
			return ($tgl);
		}
	}
}

if(!function_exists('sqlDate2htmlDate'))
{
	function sqlDate2htmlDate($value)
	{
		if(is_null($value) || empty($value) )
		{
			return NULL;
		}else
		{
			$bulan = array ('01' => 'Januari',
							'02' => 'Februari',
							'03' => 'Maret',
							'04' => 'April',
							'05' => 'Mei',
							'06' => 'Juni',
							'07' => 'Juli',
							'08' => 'Agustus',
							'09' => 'September',
							'10' => 'Oktober',
							'11' => 'November',
							'12' => 'Desember',
							'00' => NULL
			);
			$date = date('Y-m-d H:i:s', strtotime($value));
			//$date = date('Y-m-d',strtotime($value));
			$split1 = explode(' ', $date);
			$split2 = explode('-', $split1[0]);
			$tanggal = $split2[2].'-'.$bulan[$split2[1]].'-'.$split2[0];
			$temp = date('Y-m-d',strtotime("1500-01-01"));
			if($date < $temp){
				$tgl = NULL;
			} else if ($date >= $temp) {
				$tgl = $tanggal;
			} else {
				$tgl = NULL;
			}
			return ($tgl);
		}
	}
}

if(!function_exists('htmlDate2sqlDate'))
{
	function htmlDate2sqlDate($value)
	{
		if(is_null($value) || empty($value))
		{
			return NULL;
		}else
		{
			$bulan = array ('Januari' => '01',
							'Februari' => '02',
							'Maret' => '03',
							'April' => '04',
							'Mei' => '05',
							'Juni' => '06',
							'Juli' => '07',
							'Agustus' => '08',
							'September' => '09',
							'Oktober' => '10',
							'November' => '11',
							'Desember' => '12'
			);
			$split = explode('-', $value);
			$date = $split[0].'-'.$bulan[$split[1]].'-'.$split[2];
			$temp = date('Y-m-d',strtotime("1500-01-01"));
			$value = date('Y-m-d',strtotime("$date"));
			if($value < $temp){
				$tgl = NULL;
			} else if ($value >= $temp) {
				$tgl = $value;
			} else {
				$tgl = NULL;
			}
			return ($tgl);
		}
	}
}

if(!function_exists('badge_color'))
{
	function badge_color($value)
	{
		if ($value <= 50) {
			$color = 'badge-danger';
		} else if ($value <= 75)  {
			$color = 'badge-warning';
		} else if ($value <= 99)  {
			$color = 'badge-primary';
		} else if ($value == 100)  {
			$color = 'badge-success';
		}
		return ($color);
	}
}

if(!function_exists('starSetter'))
{
	function starSetter($value)
	{
		$star0 = '<span class="glyphicon glyphicon-star-empty"></span>';
		$star1 = '<span class="glyphicon glyphicon-star"></span>';
		$star2 = '<span class="glyphicon glyphicon-star"></span><span class="glyphicon glyphicon-star"></span>';
		$star3 = '<span class="glyphicon glyphicon-star"></span><span class="glyphicon glyphicon-star"></span><span class="glyphicon glyphicon-star"></span>';
		$star4 = '<span class="glyphicon glyphicon-star"></span><span class="glyphicon glyphicon-star"></span><span class="glyphicon glyphicon-star"></span><span class="glyphicon glyphicon-star"></span>';
		$star5 = '<span class="glyphicon glyphicon-star"></span><span class="glyphicon glyphicon-star"></span><span class="glyphicon glyphicon-star"></span><span class="glyphicon glyphicon-star"></span><span class="glyphicon glyphicon-star"></span>';
		if($value=='5'){
			$tgl = $star5;
		} else if ($value=='4'){
			$tgl = $star4;
		} else if ($value=='3'){
			$tgl = $star3;
		} else if ($value=='2'){
			$tgl = $star2;
		} else if ($value=='1'){
			$tgl = $star1;
		} else {
			$tgl = $star0;
		}
		return ($tgl);
	}
}

if(!function_exists('pesanModal'))
{
	function pesanModal()
	{
		$pesan = array('Sukses Ndan', 'Mantab Kali', 'berhasil', 'good job', 'well done', 'nice one', 'very good', 'very nice', 'awesome', 'keep up the good work');
		echo '<div class="modal fade" id="sukses" role="dialog">
			<div class="modal-dialog modal-sm ">
				<div class="modal-content  modal-col-green">
					<div class="modal-body">
						<h3>'.$pesan[rand(0, 9)].'</h3>
					</div>
					<div class="modal-footer">
						<button type="button" class="btn btn-link waves-effect" data-dismiss="modal">Close</button>
					</div>
				</div>
			</div>
		</div>
		<div class="modal fade" id="error" role="dialog">
			<div class="modal-dialog modal-sm ">
				<div class="modal-content">
					<div class="modal-body">
						<h3>Ada Error Komandan :';
						if (isset($GLOBALS['PESAN_ERROR']['error']))
						{
							echo $GLOBALS['PESAN_ERROR']['error'];
						}
						echo '</h3>
					</div>
					<div class="modal-footer">
						<button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
					</div>
				</div>
			</div>
		</div>
		<div class="modal fade" id="gagal" role="dialog">
			<div class="modal-dialog modal-sm ">
				<div class="modal-content">
					<div class="modal-body">
						<h3>Gagal Komandan :(</h3>
					</div>
					<div class="modal-footer">
						<button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
					</div>
				</div>
			</div>
		</div>
		<div class="modal fade" id="deleted" role="dialog">
			<div class="modal-dialog modal-sm ">
				<div class="modal-content modal-col-red">
					<div class="modal-body">
						<h3>Lapor, Data yang terpilih berhasil dihapus</h3>
					</div>
					<div class="modal-footer">
						<button type="button" class="btn btn-link waves-effect" data-dismiss="modal">Close</button>
					</div>
				</div>
			</div>
		</div>
		<div class="modal fade" id="updated" role="dialog">
			<div class="modal-dialog modal-sm ">
				<div class="modal-content modal-col-orange">
					<div class="modal-body">
						<h3>Sukses Ndan, data berhasil dirubah</h3>
					</div>
					<div class="modal-footer">
						<button type="button" class="btn btn-link waves-effect" data-dismiss="modal">Close</button>
					</div>
				</div>
			</div>
		</div>
		<div class="modal fade" id="not_updated" role="dialog">
			<div class="modal-dialog modal-sm ">
				<div class="modal-content">
					<div class="modal-body">
						<h3>Crap, data tidak berhasil dirubah</h3>
					</div>
					<div class="modal-footer">
						<button type="button" class="btn btn-link waves-effect" data-dismiss="modal">Close</button>
					</div>
				</div>
			</div>
		</div>
		<div class="modal fade" id="validated_yes" role="dialog">
			<div class="modal-dialog modal-sm ">
				<div class="modal-content">
					<div class="modal-body">
						<h3>Sukses Ndan, validasi berhasil</h3>
					</div>
					<div class="modal-footer">
						<button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
					</div>
				</div>
			</div>
		</div>
		<div class="modal fade" id="validated_no" role="dialog">
			<div class="modal-dialog modal-sm ">
				<div class="modal-content">
					<div class="modal-body">
						<h3>Data permohonan dikirim kembali ke pokja</h3>
					</div>
					<div class="modal-footer">
						<button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
					</div>
				</div>
			</div>
		</div>
		<div class="modal fade" id="passwordUpdated" role="dialog">
			<div class="modal-dialog modal-sm ">
				<div class="modal-content modal-col-green">
					<div class="modal-body">
						<h3>Sukses, Password berhasil diganti</h3>
					</div>
					<div class="modal-footer">
						<button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
					</div>
				</div>
			</div>
		</div>
		<div class="modal fade" id="oldpassword" role="dialog">
			<div class="modal-dialog modal-sm ">
				<div class="modal-content modal-col-red">
					<div class="modal-body">
						<h3>Gagal, Password lama tidak sesuai</h3>
					</div>
					<div class="modal-footer">
						<button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
					</div>
				</div>
			</div>
		</div>
		<div class="modal fade" id="newpassword" role="dialog">
			<div class="modal-dialog modal-sm ">
				<div class="modal-content modal-col-red">
					<div class="modal-body">
						<h3>Gagal, Konfirmasi Password baru tidak sama</h3>
					</div>
					<div class="modal-footer">
						<button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
					</div>
				</div>
			</div>
		</div>
		<div class="modal fade" id="newpasswordLengh" role="dialog">
			<div class="modal-dialog modal-sm ">
				<div class="modal-content modal-col-red">
					<div class="modal-body">
						<h3>Gagal, Password baru minimal 8 karakter</h3>
					</div>
					<div class="modal-footer">
						<button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
					</div>
				</div>
			</div>
		</div>';
	}
}

if(!function_exists('myRequiredMessage'))
{
	function myRequiredMessage()
	{
		echo 'oninvalid="this.setCustomValidity('."'Kolom ini wajib diisi'".')" oninput="setCustomValidity('."''".')"';
	}
}

//debuging console_log( 'kusw' );
if(!function_exists('console_log'))
{
	function console_log( $data ){
		echo '<script>';
		echo 'console.log('. json_encode( $data ) .')';
		echo '</script>';
	}
}

if(!function_exists('trv_state'))
{
	function trv_state($page, $url1, $url2){
		$CI =& get_instance();
		$controller = $CI->config->item('controller');
		if($page=='home' && $url1=='prainspeksi_gedung' && $url2=='home'){
			echo 'active';
		}elseif ($page=='admin' && $url1=='auth' && $url2=='index') {
			echo 'active';
		}elseif ($page=='group' && $url1=='auth' && $url2=='show_groups') {
			echo 'active';
		}elseif ($page=='gedung' && $url1=='prainspeksi_gedung' && $url2=='index') {
				echo 'active';
		}elseif ($page=='gedung' && $url1=='prainspeksi_gedung' && $url2=='add') {
			echo 'active';
		}elseif ($page=='gedung' && $url1=='prainspeksi_gedung' && $url2=='update') {
			echo 'active';
		}elseif ($page=='permohonan' && $url1=='prainspeksi_permohonan' && $url2=='index') {
			echo 'active';
		}elseif ($page=='permohonan' && $url1=='prainspeksi_permohonan' && $url2=='update') {
			echo 'active';
		}elseif ($page=='permohonan' && $url1=='prainspeksi_permohonan' && $url2=='Add_step1') {
			echo 'active';
		}elseif ($page=='permohonan' && $url1=='prainspeksi_permohonan' && $url2=='Add_step2') {
			echo 'active';
		}elseif ($page=='tutorial' && $url1=='prainspeksi_gedung' && $url2=='tutorial') {
			echo 'active';
		}elseif ($page=='home' && $url1=='disposisi' && $url2=='home') {
			echo 'active';
		}elseif ($page=='gedung' && $url1=='disposisi' && $url2=='list_gedung') {
			echo 'active';
		}elseif ($page=='gedung' && $url1=='disposisi' && $url2=='update_gedung') {
			echo 'active';
		}elseif ($page=='gedung' && $url1=='disposisi' && $url2=='add_gedung') {
			echo 'active';
		}elseif ($page=='permohonan' && $url1=='disposisi' && $url2=='monitoring') {
			echo 'active';
		}elseif ($page=='permohonan' && $url1=='disposisi' && $url2=='update') {
			echo 'active';
		}elseif ($page=='permohonan' && $url1=='disposisi' && $url2=='Add_disposisi_step1') {
			echo 'active';
		}elseif ($page=='permohonan' && $url1=='disposisi' && $url2=='Add_disposisi_step2') {
			echo 'active';
		}elseif ($page=='permohonan' && $url1=='disposisi' && $url2=='validasi') {
			echo 'active';
		}elseif ($page=='permohonan' && $url1=='disposisi' && $url2=='validasi_step2') {
			echo 'active';
		}elseif ($page==='users' && $url1==='admin' && $url2==='index') {
			echo 'active';
		}elseif ($page==='groups' && $url1==='admin' && $url2==='show_groups') {
			echo 'active';
		}elseif ($page==='home' && $url1===$controller && $url2==='home') {
			echo 'active';	
		}elseif ($page==='gedung' && $url1===$controller && ($url2==='list_gedung' || $url2==='read_gedung' || $url2==='add_gedung' || $url2==='edit_gedung')) {
			echo 'active';
		}elseif ($page==='pemeriksaan' && $url1===$controller && ($url2==='list_pemeriksaan' || $url2==='read_pemeriksaan' || $url2==='add_pemeriksaan' || $url2==='edit_pemeriksaan')) {
			echo 'active';
		}elseif ($page==='fsm' && $url1===$controller && ($url2==='list_fsm' || $url2==='add_fsm' || $url2==='edit_fsm')) {
			echo 'active';
		}elseif (($page==='setting' || $page==='fungsiGedung') && $url1===$controller && ($url2==='list_fungsiGedung' || $url2==='edit_fungsiGedung' || $url2==='add_fungsiGedung')) {
			echo 'active';
		}elseif (($page==='setting' || $page==='kepemilknGedung') && $url1===$controller && ($url2==='list_kepemilknGedung' || $url2==='edit_kepemilknGedung' || $url2==='add_kepemilknGedung')) {
			echo 'active';
		}elseif (($page==='setting' || $page==='jalurInfo') && $url1===$controller && ($url2==='list_jalurInfo' || $url2==='edit_jalurInfo' || $url2==='add_jalurInfo')) {
			echo 'active';	
		}elseif (($page==='setting' || $page==='hslPemeriksaan') && $url1===$controller && ($url2==='list_hslPemeriksaan'|| $url2==='edit_hslPemeriksaan' || $url2==='add_hslPemeriksaan')) {
			echo 'active';
		}elseif (($page==='setting' || $page==='statusGedung') && $url1===$controller && ($url2==='list_statusGedung'|| $url2==='edit_statusGedung' || $url2==='add_statusGedung')) {
			echo 'active';
		}elseif (($page==='setting' || $page==='penyebabFire') && $url1===$controller && ($url2==='list_penyebabFire'|| $url2==='edit_penyebabFire' || $url2==='add_penyebabFire')) {
			echo 'active';
		}elseif ($page==='pokja' && $url1===$controller && ($url2==='list_pokja'|| $url2==='edit_pokja' || $url2==='add_pokja')) {
			echo 'active';
		}elseif ($page==='fireHist' && $url1===$controller && ($url2==='list_fireHist'|| $url2==='edit_fireHist' || $url2==='add_fireHist')) {
			echo 'active';
		}elseif ($page==='msds' && $url1===$controller && ($url2==='list_msds' || $url2==='add_msds' || $url2==='read_msds' || $url2==='edit_msds')) {
			echo 'active';
		}elseif ($page==='chart' && $url1===$controller && $url2==='chart') {
			echo 'active';
		}elseif ($page==='map' && $url1===$controller && $url2==='map') {
			echo 'active';
		}elseif ($page==='map_sos' && $url1===$controller && $url2==='map_sos') {
			echo 'active';
		}elseif ($page==='panduan' && $url1===$controller && $url2==='panduan') {
			echo 'active';
		}elseif ($page==='sosialisasi' && $url1===$controller && ($url2==='list_sosialisasi' || $url2==='list_sos_per_kelurahan' || $url2==='add_sosialisasi' || $url2==='read_sos_rw' || $url2==='edit_sosialisasi_rw')) {
			echo 'active';
		}elseif ($page==='list_gedung_dinas' && $url1===$controller && $url2==='dinas') {
			echo 'active';
		}elseif ($page==='list_gedung_pusat' && $url1===$controller && $url2==='pusat') {
			echo 'active';
		}elseif ($page==='list_gedung_utara' && $url1===$controller && $url2==='utara') {
			echo 'active';
		}elseif ($page==='list_gedung_barat' && $url1===$controller && $url2==='barat') {
			echo 'active';
		}elseif ($page==='list_gedung_selatan' && $url1===$controller && $url2==='selatan') {
			echo 'active';
		}elseif ($page==='list_gedung_timur' && $url1===$controller && $url2==='timur') {
			echo 'active';
		}elseif ($page==='gedung' && $url1===$controller && ($url2==='dinas' || $url2==='pusat' || $url2==='utara' || $url2==='barat' || $url2==='selatan' || $url2==='timur')) {
			echo 'menu-open';
		}else{
			echo '';
		}
	}
}

if(!function_exists('jdl_hal'))
{
	function jdl_hal($url1, $url2){
		if($url1=='prainspeksi_gedung' && $url2=='home'){
			echo 'Dashboard';
		}elseif ($url1=='prainspeksi_gedung' && $url2=='index') {
			echo 'Daftar Gedung';
		}elseif ($url1=='prainspeksi_gedung' && $url2=='add') {
			echo 'Tambah Gedung';
		}elseif ($url1=='prainspeksi_gedung' && $url2=='update') {
			echo 'Edit Data Gedung';
		}elseif ($url1=='prainspeksi_permohonan' && $url2=='index') {
			echo 'Daftar Permohonan';
		}elseif ($url1=='prainspeksi_permohonan' && $url2=='update') {
			echo 'Edit Data Permohonan';
		}elseif ($url1=='prainspeksi_permohonan' && $url2=='Add_step1') {
			echo 'Tambah Permohonan';
		}elseif ($url1=='prainspeksi_permohonan' && $url2=='Add_step2') {
			echo 'Tambah Permohonan';
		}elseif ($url1=='disposisi' && $url2=='home') {
			echo 'Dashboard';
		}elseif ($url1=='disposisi' && $url2=='list_gedung') {
			echo 'Daftar Gedung';
		}elseif ($url1=='disposisi' && $url2=='update_gedung') {
			echo 'Edit Data Gedung';
		}elseif ($url1=='disposisi' && $url2=='add_gedung') {
			echo 'Tambah Gedung';
		}elseif ($url1=='disposisi' && $url2=='monitoring') {
			echo 'Monitoring Permohonan';
		}elseif ($url1=='disposisi' && $url2=='update') {
			echo 'Edit Data Permohonan';
		}elseif ($url1=='disposisi' && $url2=='Add_disposisi_step1') {
			echo 'Disposisi Permohonan';
		}elseif ($url1=='disposisi' && $url2=='Add_disposisi_step2') {
			echo 'Disposisi Permohonan';
		}elseif ($url1=='disposisi' && $url2=='validasi') {
			echo 'Validasi Permohonan';
		}else{
			echo '';
		}
	}
}

//chek for empty and null post
if(!function_exists('isZonk'))
{
	function isZonk( $post ){
		if (empty($post) || is_null($post) || $post == ' ' || $post == '  ')
		{
			return NULL;
		}else
		{
			return $post;
		}
	}
}

if(!function_exists('myPrint'))
{
	function myPrint($var)
	{
		$retVal = (is_null($var)) ? 'N/A' : $var;
		echo $retVal;
	}
}

if(!function_exists('badge_bg_color'))
{
	function badge_bg_color($value)
	{
		if ($value <= 50) {
			$color = 'bg-danger';
		} else if ($value <= 75)  {
			$color = 'bg-warning text-dark';
		} else if ($value < 100)  {
			$color = 'bg-info text-dark';
		} else if ($value >= 100)  {
			$color = 'bg-success';
		}
		return ($color);
	}
}

if(!function_exists('greeting'))
{
	function greeting($now_time)
	{
		$split = explode(':', $now_time);
		$menit = $split[0]*60;
		$menit += $split[1];
		
		if ($menit <= 300) {
			$greet = 'Selamat Dini Hari';
		} else if ($menit <= 659)  {
			$greet = 'Selamat Pagi';
		} else if ($menit <= 900)  {
			$greet = 'Selamat Siang';
		} else if ($menit <= 1080)  {
			$greet = 'Selamat Sore';
		} else{
			$greet = 'Selamat Malam';
		}
		return $greet;
	}
}