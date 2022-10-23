<?php
defined('BASEPATH') OR exit('No direct script access allowed');

$config['id'] = 'kabid_sapras';
$config['mcID_list_monitoring'] = [19,18,27,28,29,16,17,22,23,24,30,25,32,15,14,13,12,11,10,9,8,7,6,31];
$config['jabID_list_monitoring'] = [107, 102, 101, 104];
$config['verifikasi_list'] = [19,18,27,28,29,16,17,22,23,24,32,13,14,31];
$config['list_jab_pns'] = [19,18,27,28,29,16,17,22,23,24,30,25,32,15,14,13,12,11,10,9,8,7,6,31];
$config['list_jab_nonpns'] = [9, 8, 7, 6];
$config['list_user_setting'] = [15, 22, 23, 30, 13, 11, 10, 21, 9, 8, 7, 6, 26];
$config['report_apd'] = [15, 22, 23, 30, 13, 11, 10, 21, 9, 8, 7, 6];
$config['es4_list'] = [10, 11, 12];
$config['upload_path'] = 'upload/sosialisasi/barat';
$config['thumb_upload_path'] = 'upload/sosialisasi/barat';
$config['file_sos_map'] = 'kel_jak_barat.geojson';
$config['skpd'] = 'Suku Dinas Penanggulangan Kebakaran dan Penyelamatan Kota Administrasi Jakarta Barat';
$config['select_wil_opt'] = 3;

/*suggested variable name

$namaDB = $this->config->item('nama_database');
$tabelGedung = $this->config->item('nama_tabel_gedung');
$tabelPemeriksaan = $this->config->item('nama_tabel_pemeriksaan');
$tabelPokja = $this->config->item('nama_tabel_pokja');
$tabelFireHist = $this->config->item('nama_tabel_fire_hist');
$tabelFsm = $this->config->item('nama_tabel_fsm');
$myFile = $this->config->item('file_time');
$file_pdfRekapGdg = $this->config->item('rekap_gdg_pdf');
$file_pdfDataGdg = $this->config->item('data_gdg_pdf');
$skpd = $this->config->item('skpd');
$ba_image_path = $this->config->item('ba_image_path');
$denah_gedung_path = $this->config->item('denah_gedung_path');
$controller = $this->config->item('controller');
$select_wil_opt = $this->config->item('select_wil_opt');
$prefix_kodeGdg = $this->config->item('prefix_kodeGdg');
$view_folder = $this->config->item('view_folder');
$map_set = $this->config->item('map_set');
$sosialisasi_path = $this->config->item('sosialisasi_path');
*/