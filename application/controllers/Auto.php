<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Auto extends CI_Controller {

    public $data = [];
    public function __construct()
	{
        parent::__construct();
        $this->config->load('auto');
        $this->load->library('my_apd');
        //$this->load->database();
        //$this->load->library(['ion_auth', 'my_apd', 'form_validation']);
        $this->load->helper(['url', 'language']);
        $this->load->model('admin_model');
        $state = $this->my_apd->check_isOpenPeriode();
        $this->data['periode'] = $state['periode'];
        $this->data['is_open'] = ($state['is_open']) ? true : false ;
    }

    //ex $joinTable = ['master_jabatan', 'master_controller']
    private function _get_users($select, $resultType, $where=null, $like=null, $or_where=null, $or_like=null, $joinTable=null, $orderArr=null, $limitArr=null)
    {
        $default_join = [['users', 'master_pos', 'master_pos.nama_pos', 'kode_pos_id', 'id_mp' ]];
        $joinArr = ['master_status' => ['users', 'master_status', 'master_status.status', 'status_id', 'id_stat' ], 
                    'master_jabatan' => ['users', 'master_jabatan', 'master_jabatan.nama_jabatan', 'jabatan_id', 'id_mj' ], 
                    'master_controller' => ['master_jabatan', 'master_controller', 'master_controller.level', 'mc_id', 'id' ],
                    'master_group_piket' => ['users', 'master_group_piket', 'master_group_piket.kode_piket', 'group_piket_id', 'id' ] ];
        if (! is_null($joinTable) && is_array($joinTable)) {
            foreach ($joinTable as $jt) {
                if (isset($joinArr[$jt])) {
                    array_push($default_join, $joinArr[$jt]);
                }
            }
        }
        
        $default_where = [['active', 1], ['users.deleted', 0], ['master_jabatan.plt_id', null], ['master_jabatan.mc_id !=', 13], ['master_jabatan.mc_id !=', 16]];
        if (! is_null($where) && is_array($where)) {
            foreach ($where as $w) {
                array_push($default_where, $w);
            }
        }

        $like_temp = [];
        if (! is_null($like) && is_array($like)) {
            foreach ($like as $l) {
                array_push($like_temp, $l);
            }
        }
        $default_like = (count($like_temp) < 1) ? null : $like_temp ;

        //$selectStrList, $tableName, $resultType, $whereArr=null, $likeArr=null, $joinArr=null, $orderArr=null, $limitArr=null, $or_whereArr=null, $or_likeArr=null )
        $result = $this->admin_model->get($select, 'users', $resultType, $default_where, $default_like, $default_join, $orderArr, $limitArr, $or_where, $or_like);
        return $result;
    }

    private function _get_apds($select, $resultType, $where=null, $like=null, $or_where=null, $or_like=null, $joinTable=null, $periode=TRUE, $orderArr=null, $limitArr=null)
    {
        $default_join = [   ['apd', 'master_apd', 'master_apd.tahun', 'mapd_id', 'id_ma' ],
                            ['master_apd', 'master_jenis_apd', 'master_jenis_apd.jenis_apd', 'mj_id', 'id_mj' ]
                            ];
        $joinArr = ['master_keberadaan' => ['apd', 'master_keberadaan', 'master_keberadaan.keberadaan', 'mkp_id', 'id_mkp' ],
                    'master_merk' => ['master_apd', 'master_merk', 'master_merk.merk', 'mm_id', 'id_mm' ],
                    'master_kondisi' => ['apd', 'master_kondisi', 'master_kondisi.nama_kondisi', 'kondisi_id', 'id_mk' ], 
                    'master_progress_status' => ['apd', 'master_progress_status', 'master_progress_status.id_mps, master_progress_status.button', 'progress', 'id_mps' ],
                    'users' => ['apd', 'users', 'users.jabatan_id', 'petugas_id', 'id' ],
                    'master_jabatan' => ['users', 'master_jabatan', 'master_jabatan.mc_id', 'jabatan_id', 'id_mj' ],
                    'master_pos' => ['users', 'master_pos', 'master_pos.kode_pos', 'kode_pos_id', 'id_mp' ],
                    'verifikator' => ['apd', 'users', 'users.NRK', 'id_pemverifikasi', 'id' ] ];
        if (! is_null($joinTable) && is_array($joinTable)) {
            foreach ($joinTable as $jt) {
                if (isset($joinArr[$jt])) {
                    array_push($default_join, $joinArr[$jt]);
                }
            }
        }
        
        $default_where = [['apd.mj_id !=', 0]];
        if (! is_null($where) && is_array($where)) {
            foreach ($where as $w) {
                array_push($default_where, $w);
            }
        }
        //$default_where = (count($where_temp) < 1) ? null : $where_temp ;

        if ($periode) {
            $where_periode = ['periode_input', $this->data['periode'] ];
            array_push($default_where, $where_periode);
        }

        $like_temp = [];
        if (! is_null($like) && is_array($like)) {
            foreach ($like as $l) {
                array_push($like_temp, $l);
            }
        }
        $default_like = (count($like_temp) < 1) ? null : $like_temp ;

        //$selectStrList, $tableName, $resultType, $whereArr=null, $likeArr=null, $joinArr=null, $orderArr=null, $limitArr=null, $or_whereArr=null, $or_likeArr=null )
        $result = $this->admin_model->get($select, 'apd', $resultType, $default_where, $default_like, $default_join, $orderArr, $limitArr, $or_where, $or_like);
        return $result;
    }

    private function _get_jml_jenis_apd($where=null, $roles_id=2)
    {
        $default_where = [['deleted', 0], ['role_id', $roles_id]];
        if (! is_null($where) && is_array($where)) {
            foreach ($where as $w) {
                array_push($default_where, $w);
            }
        }
        $result = $this->admin_model->get('id_mj', 'master_jenis_apd', 3, $default_where );
        return $result;
    }

    private function _get_list_jenis_apd($select, $where=null, $resultType=1, $roles_id=2)
    {        
        $default_where = [['master_jenis_apd.deleted', 0], ['role_id', $roles_id]];
        if (! is_null($where) && is_array($where)) {
            foreach ($where as $w) {
                array_push($default_where, $w);
            }
        }

        $result = $this->admin_model->get($select, 'master_jenis_apd', $resultType, $default_where );
        return $result;
    }

    private function _fillMasterPos($list_pos=null)
    {
        $table = 'master_pos';
        $this->load->helper('date');

        if (is_null($list_pos)) {
            $list_pos = $this->admin_model->get('id_mp as id, kode_pos as kode, nama_pos as nama, alamat', $table, 1, [['deleted', 0]]);
            //$list_pos = $this->admin_model->get('id_mp as id, kode_pos as kode, nama_pos as nama, alamat', $table, 1, [['deleted', 0]], null, null, null, [25, 0]);
            //$list_pos = $this->admin_model->get('id_mp as id, kode_pos as kode, nama_pos as nama, alamat', $table, 1, [['kode_sektor', '7.2']]);
        }
        $list_jenis_apd = $this->_get_list_jenis_apd('id_mj, jenis_apd, kode_barang, satuan');
        $joinTable1 = ['master_jabatan'];
        $joinTable2 = ['users', 'master_jabatan', 'master_pos'];
        /*$jab_id_arr = $this->config->item('mcID_list_monitoring');
        foreach ($jab_id_arr as $jab_id) {
            $or_where_arr[] = ['master_jabatan.mc_id', $jab_id];
        }*/
        $or_where_arr = null;
        $joinTable3 = [ ['master_apd', 'master_merk', 'master_merk.merk', 'mm_id', 'id_mm' ], 
                        ['master_apd', 'master_jenis_apd', 'master_jenis_apd.jenis_apd', 'mj_id', 'id_mj' ]];
        
        $join_arr = ['master_kondisi', 'users', 'master_pos', 'master_jabatan'];

        $progress = array(  ['where' => ['apd.progress !=', 0], 'col' => 'chart_input_APD'],
                            ['where' => ['apd.progress', 3], 'col' => 'chart_verif_APD'] );
        $failed = $result = $result_kib = [];
        $whr_apd1 = ['active', 1];
        $whr_apd2 = ['users.deleted', 0];
        foreach ($list_pos as $pos) {
            // fill col jml_pns, jml_pjlp, dst
            //$like = [['master_pos.kode_pos', $pos['kode'], 'after']];
            $like = null;
            $where_pos = ['master_pos.kode_pos', $pos['kode']];
            $select1 = 'id';
            $select2 = 'apd.id';
            $jmlPNS = $this->_get_users($select1, 3, [['status_id', 0], $where_pos], $like, $or_where_arr, null, $joinTable1);
            $jmlPJLP = $this->_get_users($select1, 3, [['status_id', 1], $where_pos], $like, $or_where_arr, null, $joinTable1);
            $jumSdhInput = $this->_get_apds($select2, 3, [['progress !=', 0], $where_pos, $whr_apd1, $whr_apd2 ], $like, $or_where_arr, null, $joinTable2);
            $jumVerified = $this->_get_apds($select2, 3, [['progress', 3], $where_pos, $whr_apd1, $whr_apd2 ], $like, $or_where_arr, null, $joinTable2);
            $jumDitolak = $this->_get_apds($select2, 3, [['progress', 1], $where_pos, $whr_apd1, $whr_apd2 ], $like, $or_where_arr, null, $joinTable2);
            $my_time = date("Y-m-d H:i:s", now('Asia/Jakarta'));
            $data = array(  'jml_pns' => $jmlPNS, 
                            'jml_pjlp' => $jmlPJLP,
                            'jml_input' => $jumSdhInput,
                            'jml_verif' => $jumVerified,
                            'jml_ditolak' => $jumDitolak,
                            'tgl_update' => $my_time
                        );
            // fill col chart_input_APD dan chart_verif_APD
            foreach ($progress as $key) {
                foreach ($list_jenis_apd as $jenis_apd) {
                    $jml_baik = $this->_get_apds('apd.id', 3, [['apd.mkp_id', 1],['master_kondisi.kategori', 4], $key['where'], ['apd.mj_id', $jenis_apd['id_mj']], $where_pos, $whr_apd1, $whr_apd2 ], $like, $or_where_arr, null, $join_arr);
                    $jml_rr = $this->_get_apds('apd.id', 3, [['apd.mkp_id', 1],['master_kondisi.kategori', 3], $key['where'], ['apd.mj_id', $jenis_apd['id_mj']], $where_pos, $whr_apd1, $whr_apd2 ], $like, $or_where_arr, null, $join_arr);
                    $jml_rs = $this->_get_apds('apd.id', 3, [['apd.mkp_id', 1],['master_kondisi.kategori', 2], $key['where'], ['apd.mj_id', $jenis_apd['id_mj']], $where_pos, $whr_apd1, $whr_apd2 ], $like, $or_where_arr, null, $join_arr);
                    $jml_rb = $this->_get_apds('apd.id', 3, [['apd.mkp_id', 1],['master_kondisi.kategori', 1], $key['where'], ['apd.mj_id', $jenis_apd['id_mj']], $where_pos, $whr_apd1, $whr_apd2 ], $like, $or_where_arr, null, $join_arr);
                    $jml_blm = $this->_get_apds('apd.id', 3, [['apd.mkp_id', 3], $key['where'], ['apd.mj_id', $jenis_apd['id_mj']], $where_pos, $whr_apd1, $whr_apd2 ], $like, $or_where_arr, null, $join_arr);
                    $jml_hilang = $this->_get_apds('apd.id', 3, [['apd.mkp_id', 2], $key['where'], ['apd.mj_id', $jenis_apd['id_mj']], $where_pos, $whr_apd1, $whr_apd2 ], $like, $or_where_arr, null, $join_arr);
                    $tot_existing = $jml_baik+$jml_rr+$jml_rs+$jml_rb;
                    $tot_kurang = $jml_blm+$jml_hilang;
                    $total = $tot_existing+$tot_kurang;
                    if ($total>0) {
                        $result[$jenis_apd['id_mj']] = array(  'jenis_apd' => $jenis_apd['jenis_apd'],
                                        'jml_baik' => $jml_baik, 
                                        'jml_rr' => $jml_rr,
                                        'jml_rs' => $jml_rs,
                                        'jml_rb' => $jml_rb,
                                        'tot_existing' => ($jml_baik+$jml_rr+$jml_rs+$jml_rb),
                                        'jml_blm' => $jml_blm,
                                        'jml_hilang' => $jml_hilang,
                                        'tot_kurang' => ($jml_blm+$jml_hilang),
                                        'total' => ($jml_baik+$jml_rr+$jml_rs+$jml_rb+$jml_blm+$jml_hilang)
                                        );
                    }
                    
                    // fill col KIB_APD
                    if ($key['col'] == 'chart_input_APD') {         // run sekali aja
                        $list_apd = $this->admin_model->get('id_ma, tahun', 'master_apd', 1, [['master_apd.deleted', 0], ['mj_id', $jenis_apd['id_mj']]], null, $joinTable3);
                        foreach ($list_apd as $apd) {
                            $jml_baik = $this->_get_apds('apd.id', 3, [['apd.mkp_id', 1], ['master_kondisi.kategori', 4], ['apd.progress', 3], ['mapd_id', $apd['id_ma']], $where_pos, $whr_apd1, $whr_apd2 ], $like, $or_where_arr, null, $join_arr);
                            $jml_rr = $this->_get_apds('apd.id', 3, [['apd.mkp_id', 1], ['master_kondisi.kategori', 3], ['apd.progress', 3], ['mapd_id', $apd['id_ma']], $where_pos, $whr_apd1, $whr_apd2 ], $like, $or_where_arr, null, $join_arr);
                            $jml_rs = $this->_get_apds('apd.id', 3, [['apd.mkp_id', 1], ['master_kondisi.kategori', 2], ['apd.progress', 3], ['mapd_id', $apd['id_ma']], $where_pos, $whr_apd1, $whr_apd2 ], $like, $or_where_arr, null, $join_arr);
                            $jml_rb = $this->_get_apds('apd.id', 3, [['apd.mkp_id', 1], ['master_kondisi.kategori', 1], ['apd.progress', 3], ['mapd_id', $apd['id_ma']], $where_pos, $whr_apd1, $whr_apd2 ], $like, $or_where_arr, null, $join_arr);
                            if ($jml_baik != 0 || $jml_rr != 0 || $jml_rs != 0 || $jml_rb != 0) {
                                $result_kib[$apd['id_ma']] = array(  'kode_barang' => $jenis_apd['kode_barang'],
                                                                    'jenis_apd' => $jenis_apd['jenis_apd'],
                                                                    'merk' => $apd['merk'],
                                                                    'tahun' => $apd['tahun'],
                                                                    'jml_baik' => $jml_baik, 
                                                                    'jml_rr' => $jml_rr,
                                                                    'jml_rs' => $jml_rs,
                                                                    'jml_rb' => $jml_rb,
                                                                    'total' => ($jml_baik+$jml_rr+$jml_rs+$jml_rb),
                                                                    'id_mj' => $jenis_apd['id_mj'],
                                                                    'satuan' => $jenis_apd['satuan']
                                                                    );
                            }
                        }
                    }
                }
                $json = json_encode($result);
                $data[$key['col']] = $json;
                $json = '';
                $result = [];
            }
            $json_kib = json_encode($result_kib);
            $data['KIB_APD'] = $json_kib;
            $result_kib = [];

            //save to db
            if ($this->admin_model->updateData('master_pos', ['id_mp', $pos['id']], $data) ) {
                $success = true;
            }else{
                $success = false;
            }
        }
        return $success;
    }

    private function _addJSON($newJSON, $oldArray)
    {
        if (! is_null($newJSON)) {
            $newArray = json_decode($newJSON, true);
        }else{
            $newArray = [];
        }
        
        if (count($oldArray)>0 && count($newArray)>0) {
            //tambahkan setiap numerik $oldArray dengan $newArray berdasarkan key $oldArray
            foreach ($oldArray as $mj_id => $data_array) {
                foreach ($data_array as $key => $value) {
                    if (isset($newArray[$mj_id][$key]) && is_numeric($value)) {
                        if ($key != 'id_mj' && $key != 'tahun' && $key != 'satuan') {
                            $oldArray[$mj_id][$key] = $value + $newArray[$mj_id][$key];
                        }
                    }
                }
            }
            //cek apakah ada id_ma baru pada $newArray, bila ada, tambahkan semua $newArray kedalam $oldArray berdasarkan key $newArray
            foreach ($newArray as $mj_id1 => $data_array1) {
                if (!isset($oldArray[$mj_id1]) ) {
                    $oldArray[$mj_id1] = $data_array1;
                }
            }
        }elseif (count($newArray)>0) {
            $oldArray = $newArray;
        }
        return $oldArray;
    }

    private function _fillMasterSektor($list_sektor=null)
    {
        //ini_set('max_execution_time', 360000);
        set_time_limit(360000);
        $table = 'master_sektor';
        $this->load->helper('date');

        if (is_null($list_sektor)) {
            $list_sektor = $this->admin_model->get('id, kode, sektor', $table, 1, [['deleted', 0]]);
        }
        $list_jenis_apd = $this->_get_list_jenis_apd('id_mj, jenis_apd, kode_barang');
        $failed = [];
        foreach ($list_sektor as $sektor) {
            $select = 'id_mp, jml_pns, jml_pjlp, jml_input, jml_verif, jml_ditolak, chart_input_APD, chart_verif_APD, KIB_APD';
            $list_pos = $this->admin_model->get($select, 'master_pos', 1, [['deleted', 0], ['kode_sektor', $sektor['kode']] ]);
            $jml_pns = $jml_pjlp = $jml_input = $jml_verif = $jml_ditolak = 0;
            $chart_input_APD = $chart_verif_APD = $KIB_APD = [];
            foreach ($list_pos as $pos) {
                $jml_pns += $pos['jml_pns'];
                $jml_pjlp += $pos['jml_pjlp'];
                $jml_input += $pos['jml_input'];
                $jml_verif += $pos['jml_verif'];
                $jml_ditolak += $pos['jml_ditolak'];
                $chart_input_APD = $this->_addJSON($pos['chart_input_APD'], $chart_input_APD);
                $chart_verif_APD = $this->_addJSON($pos['chart_verif_APD'], $chart_verif_APD);
                $KIB_APD = $this->_addJSON($pos['KIB_APD'], $KIB_APD);
            }
            $my_time = date("Y-m-d H:i:s", now('Asia/Jakarta'));
            $data = array(  'jml_pns' => $jml_pns, 
                            'jml_pjlp' => $jml_pjlp,
                            'jml_input' => $jml_input,
                            'jml_verif' => $jml_verif,
                            'jml_ditolak' => $jml_ditolak,
                            'chart_input_APD' => json_encode($chart_input_APD),
                            'chart_verif_APD' => json_encode($chart_verif_APD),
                            'KIB_APD' => json_encode($KIB_APD),
                            'tgl_update' => $my_time
                        );
            if ($this->admin_model->updateData($table, ['id', $sektor['id']], $data) ) {
                $success = true;
            }else{
                $success = false;
            }
        }
        return $success;
    }

    private function _fillMasterSudin($list_sudin=null)
    {
        //ini_set('max_execution_time', 360000);
        set_time_limit(360000);
        $table = 'master_sudin';
        $this->load->helper('date');

        if (is_null($list_sudin)) {
            $list_sudin = $this->admin_model->get('id, kode, sudin', $table, 1, [['deleted', 0]]);
        }
        $failed = [];
        foreach ($list_sudin as $sudin) {
            $select = 'id, jml_pns, jml_pjlp, jml_input, jml_verif, jml_ditolak, chart_input_APD, chart_verif_APD, KIB_APD';
            $list_sektor = $this->admin_model->get($select, 'master_sektor', 1, [['deleted', 0]], [['kode', $sudin['kode'], 'after']]);
            //$list_pos = $this->admin_model->get($select, 'master_pos', 1, [['deleted', 0], ['kode_sektor', $sudin['kode']] ]);
            $jml_pns = $jml_pjlp = $jml_input = $jml_verif = $jml_ditolak = 0;
            $chart_input_APD = $chart_verif_APD = $KIB_APD = [];
            foreach ($list_sektor as $sektor) {
                $jml_pns += $sektor['jml_pns'];
                $jml_pjlp += $sektor['jml_pjlp'];
                $jml_input += $sektor['jml_input'];
                $jml_verif += $sektor['jml_verif'];
                $jml_ditolak += $sektor['jml_ditolak'];
                $chart_input_APD = $this->_addJSON($sektor['chart_input_APD'], $chart_input_APD);
                $chart_verif_APD = $this->_addJSON($sektor['chart_verif_APD'], $chart_verif_APD);
                $KIB_APD = $this->_addJSON($sektor['KIB_APD'], $KIB_APD);
            }
            $my_time = date("Y-m-d H:i:s", now('Asia/Jakarta'));
            $data = array(  'jml_pns' => $jml_pns, 
                            'jml_pjlp' => $jml_pjlp,
                            'jml_input' => $jml_input,
                            'jml_verif' => $jml_verif,
                            'jml_ditolak' => $jml_ditolak,
                            'chart_input_APD' => json_encode($chart_input_APD),
                            'chart_verif_APD' => json_encode($chart_verif_APD),
                            'KIB_APD' => json_encode($KIB_APD),
                            'tgl_update' => $my_time
                        );
            if ($this->admin_model->updateData($table, ['id', $sudin['id']], $data) ) {
                $success = true;
            }else{
                $success = false;
            }
        }
        return $success;
    }

    private function _fillMasterDinas()
    {
        $table = 'master_dinas';
        $this->load->helper('date');

        $list_dinas = $this->admin_model->get('id, kode, dinas', $table, 1, [['deleted', 0]]);
        $failed = [];
        foreach ($list_dinas as $dinas) {
            $select = 'id, jml_pns, jml_pjlp, jml_input, jml_verif, jml_ditolak, chart_input_APD, chart_verif_APD, KIB_APD';
            $list_sudin = $this->admin_model->get($select, 'master_sudin', 1, [['deleted', 0]]);
            //$list_pos = $this->admin_model->get($select, 'master_pos', 1, [['deleted', 0], ['kode_sektor', $dinas['kode']] ]);
            $jml_pns = $jml_pjlp = $jml_input = $jml_verif = $jml_ditolak = 0;
            $chart_input_APD = $chart_verif_APD = $KIB_APD = [];
            foreach ($list_sudin as $sudin) {
                $jml_pns += $sudin['jml_pns'];
                $jml_pjlp += $sudin['jml_pjlp'];
                $jml_input += $sudin['jml_input'];
                $jml_verif += $sudin['jml_verif'];
                $jml_ditolak += $sudin['jml_ditolak'];
                $chart_input_APD = $this->_addJSON($sudin['chart_input_APD'], $chart_input_APD);
                $chart_verif_APD = $this->_addJSON($sudin['chart_verif_APD'], $chart_verif_APD);
                $KIB_APD = $this->_addJSON($sudin['KIB_APD'], $KIB_APD);
            }
            $my_time = date("Y-m-d H:i:s", now('Asia/Jakarta'));
            $data = array(  'jml_pns' => $jml_pns, 
                            'jml_pjlp' => $jml_pjlp,
                            'jml_input' => $jml_input,
                            'jml_verif' => $jml_verif,
                            'jml_ditolak' => $jml_ditolak,
                            'chart_input_APD' => json_encode($chart_input_APD),
                            'chart_verif_APD' => json_encode($chart_verif_APD),
                            'KIB_APD' => json_encode($KIB_APD),
                            'tgl_update' => $my_time
                        );
            if ($this->admin_model->updateData($table, ['id', $dinas['id']], $data) ) {
                $success = true;
            }else{
                $success = false;
            }
        }
        return $success;
    }

    private function _createLogFile()
    {
        //join the table
        $list_pos = $this->admin_model->get('id_mp as id', 'master_pos', 1, [['deleted', 0]]);
        $list_sektor = $this->admin_model->get('id', 'master_sektor', 1, [['deleted', 0]]);
        $list_sudin = $this->admin_model->get('id', 'master_sudin', 1, [['deleted', 0]]);
        $list_dinas = $this->admin_model->get('id', 'master_dinas', 1, [['deleted', 0]]);
        $list_array = array(    ['list' => $list_pos, 'table' => 'master_pos'],
                                ['list' => $list_sektor, 'table' => 'master_sektor'],
                                ['list' => $list_sudin, 'table' => 'master_sudin'],
                                ['list' => $list_dinas, 'table' => 'master_dinas']
                                );
        foreach ($list_array as $array1) {
            foreach ($array1['list'] as $array2) {
                $join_list[] = [    'id' => $array2['id'],
                                    'table' => $array1['table']
                                    ];
            }
        }
        // write iterate to file
        $numMax = count($join_list);
        $iterate = array('now' => 0, 'max' => $numMax-1);
        $iterate_file = 'application/logs/auto.txt';
        $myFileLink = fopen($iterate_file, 'w+') or die("Can't open file.");
        $newContents = json_encode($iterate);
        fwrite($myFileLink, $newContents);
        fclose($myFileLink);
        // write list to file
        $list_file = 'application/logs/list.txt';
        $myFileLink = fopen($list_file, 'w+') or die("Can't open file.");
        $newContents = json_encode($join_list);
        fwrite($myFileLink, $newContents);
        fclose($myFileLink);
    }

    private function _executeCommand($id, $table_name)
    {
        if ($table_name == 'master_pos') {
            $list_pos = $this->admin_model->get('id_mp as id, kode_pos as kode, nama_pos as nama, alamat', $table_name, 1, [['id_mp', $id]]);
            $result = $this->_fillMasterPos($list_pos);
        } else if ($table_name == 'master_sektor') {
            $list_sektor = $this->admin_model->get('id, kode, sektor', $table_name, 1, [['id', $id]]);
            $result = $this->_fillMasterSektor($list_sektor);
        } else if ($table_name == 'master_sudin') {
            $list_sudin = $this->admin_model->get('id, kode, sudin', $table_name, 1, [['id', $id]]);
            $result = $this->_fillMasterSudin($list_sudin);
        } else if ($table_name == 'master_dinas') {
            $result = $this->_fillMasterDinas();
        } else {
            $result = false;
        }
        return $result;
    }

    public function iterate($password = null)
    {
        //$execute = $this->_fillMasterPos();
        //$execute = $this->_fillMasterSektor();
        //$execute = $this->_fillMasterSudin();
        //$execute = $this->_fillMasterDinas();
        //$password = $this->uri->segment(3);
        if ($password === 'rerogoestosapporo2024' && $this->data['is_open']) {
        //if (true) {
            $execute = $this->_fillMasterPos();
            $execute = $this->_fillMasterSektor();
            $execute = $this->_fillMasterSudin();
            $execute = $this->_fillMasterDinas();
            echo 'sukses';
            return true;
        } else {
            echo 'failed';
            return false;
        }

        
        
        /*$keyword = 'rero25022020';
        if ($password != $keyword) {
            //echo 'failed';
            return false;
        }else{
            //read file
            $iterate_file = 'application/logs/auto.txt';
            $list_file = 'application/logs/list.txt';
            $text_content = file_get_contents($iterate_file, FILE_USE_INCLUDE_PATH);
            $array_content = json_decode($text_content, true);
            

            //execute command
            $list_content = file_get_contents($list_file, FILE_USE_INCLUDE_PATH);
            $list = json_decode($list_content, true);
            $id_list = $list[$array_content['now']]['id'];
            $table_name = $list[$array_content['now']]['table'];
            $execute = $this->_executeCommand($id_list, $table_name);

            // modified iterate array
            if ($array_content['now'] == $array_content['max']) {
                $this->_createLogFile();
            }else{
                $array_content['now'] += 1;
                // write iterate array to file
                $myFileLink = fopen($iterate_file, 'w+') or die("Can't open file.");
                $newContents = json_encode($array_content);
                fwrite($myFileLink, $newContents);
                fclose($myFileLink);
            }
            if ($execute) {
                echo 'sukses';
            } else {
                echo 'failed';
            }*/
            //return $execute;
        //}
    }

    private function inputStaf()
    {
        //$password = '$2y$10$CSDmUwBEybsvVGDY.XBeXe1MYnnM7p95yo04CvKOHf1.fbYqpWcAO';
        $default_passwd = '$2y$10$8cW1NFaAo52lSdwBe3ak1u0lJ/o.GPikbRuKn9ZRp2c1BwZAlNiaK';
        $list_staf = $this->admin_model->get('id, nama, nip, nrk, pos_id, mj_id, eselon', 'user_staf', 1 );
        foreach ($list_staf as $staf) {
            $jml_user_active=$this->admin_model->get('id', 'users', 3, [['NRK', $staf['nrk']], ['active', 1]] );
            $inactive_user=$this->admin_model->get('id', 'users', 2, [['NRK', $staf['nrk']], ['active', 0]] );

            if ($jml_user_active > 0) {
                $data_to_store = ['duplicate' => 1];
                $this->admin_model->updateData('user_staf', ['id', $staf['id']], $data_to_store);
            } else if(is_array($inactive_user)){
                if(count($inactive_user) > 0){
                     //hapus data lama
                    $id = $inactive_user['id'];
                    if ($this->admin_model->hard_delete('users', 'id', $id)) {
                        $data_to_store = ['deleted' => 1];
                        $this->admin_model->updateData('user_staf', ['id', $staf['id']], $data_to_store);
                    }

                    //tambahakan data baru
                    $group_piket = ($staf['mj_id'] != 105 && $staf['mj_id'] != 104 && empty($staf['eselon']) ) ? 1 : 0 ;
                    $data_to_store = array(  'nama' => $staf['nama'],
                                    'NRK' => $staf['nrk'],
                                    'NIP' => $staf['nip'],
                                    'password' => $default_passwd,
                                    'status_id' => 0,
                                    'jabatan_id' => $staf['mj_id'],
                                    'kode_pos_id' => $staf['pos_id'],
                                    'group_piket_id' => $group_piket,
                                    'active' => 1
                                );
                    $insert = false;
                    if ($this->admin_model->insertData('users', $data_to_store)) {
                        $insert = true;
                        //$data_to_store1 = ['sukses_update' => 1];
                        //$this->admin_model->updateData('user_staf', ['id', $staf['id']], $data_to_store1);
                    }

                    if ($insert) {
                        $id_user = $this->admin_model->get('id', 'users', 2, [['NRK', $staf['nrk'] ]] );
                        $data_group = array(  'user_id' => $id_user['id'],
                                            'group_id' => 2
                                    );
                        if ($this->admin_model->insertData('users_groups', $data_group) ) {
                            $data_to_store1 = ['sukses_update' => 1];
                            $this->admin_model->updateData('user_staf', ['id', $staf['id']], $data_to_store1);
                        }
                    }
                }
               
            }else {
                $group_piket = ($staf['mj_id'] != 105 && $staf['mj_id'] != 104 && empty($staf['eselon']) ) ? 1 : 0 ;
                $data_to_store = array(  'nama' => $staf['nama'],
                                'NRK' => $staf['nrk'],
                                'NIP' => $staf['nip'],
                                'password' => $default_passwd,
                                'status_id' => 0,
                                'jabatan_id' => $staf['mj_id'],
                                'kode_pos_id' => $staf['pos_id'],
                                'group_piket_id' => $group_piket,
                                'active' => 1
                            );
                $insert = false;
                if ($this->admin_model->insertData('users', $data_to_store)) {
                    $insert = true;
                    //$data_to_store1 = ['sukses_update' => 1];
                    //$this->admin_model->updateData('user_staf', ['id', $staf['id']], $data_to_store1);
                }

                if ($insert) {
                    $id_user = $this->admin_model->get('id', 'users', 2, [['NRK', $staf['nrk'] ]] );
                    $data_group = array(  'user_id' => $id_user['id'],
                                        'group_id' => 2
                                );
                    if ($this->admin_model->insertData('users_groups', $data_group) ) {
                        $data_to_store1 = ['sukses_update' => 1];
                        $this->admin_model->updateData('user_staf', ['id', $staf['id']], $data_to_store1);
                    }
                }
                
            }
            
        }
        return true;
    }

    public function updateStaff()
    {
        $kus = 'gagal';
        if ($this->inputStaf()) {
            $kus = 'sukses';
        }
        echo $kus;
    }

    private function cekKeberadaan()
    {
        $list_staf = $this->admin_model->get('id, nama, nip, nrk, pos_id, mj_id, eselon', 'user_staf', 1 );
        foreach ($list_staf as $staf) {
            $user_active=$this->admin_model->get('id', 'users_tarikan', 2, [['NRK', $staf['nrk']], ['active', 1]] );
            if ($user_active['id'] < 4203) {
                $data_to_store = ['exist_old' => 1];
                $this->admin_model->updateData('user_staf', ['id', $staf['id']], $data_to_store);
            }
            //$jml_user_inactive=$this->admin_model->get('id', 'users', 3, [['NRK', $staf['nrk']], ['active', 0]] );
            /*$data_to_store = [];
            if ($jml_user_active > 0) {
                $data_to_store = ['exist_active' => 1];
                $this->admin_model->updateData('user_staf', ['id', $staf['id']], $data_to_store);
            } else if($jml_user_inactive > 0){
                $data_to_store = ['exist_inactive' => 1];
                $this->admin_model->updateData('user_staf', ['id', $staf['id']], $data_to_store);
            } else {
                $data_to_store = ['exist_active' => 1, 'exist_inactive' => 1];
                $this->admin_model->updateData('user_staf', ['id', $staf['id']], $data_to_store);
            }*/
        }
        return true;
    }

    public function ceknricek()
    {
        $kus = 'gagal cekricek';
        if ($this->cekKeberadaan()) {
            $kus = 'sukses cekricek';
        }
        echo $kus;
    }
    
    private function calc_rekap_petugas()
    {
        $list_user = $this->_get_users('id', 1);
        //$list_user = $this->_get_users('id', 1, null, null, null, null, null, null, [25, 0]);
        foreach ($list_user as $user) {
            $UserID = $user['id'];
            $jml_belum_verif = $this->_get_apds('id', 3, [['petugas_id', $UserID], ['progress', 2]]);
            $jml_terverif = $this->_get_apds('id', 3, [['petugas_id', $UserID], ['progress', 3]]);
            $jml_ditolak = $this->_get_apds('id', 3, [['petugas_id', $UserID], ['progress', 1]]);
            /*$jml_belum_verif = $this->admin_model->get('id', 'apd', 3, [['petugas_id', $UserID], ['progress', 2], ['apd.mj_id !=', 0], ['periode_input', $perInput['periode_input'] ] ]);
            $jml_terverif = $this->admin_model->get('id', 'apd', 3, [['petugas_id', $UserID], ['progress', 3], ['apd.mj_id !=', 0], ['periode_input', $perInput['periode_input'] ] ]);
            $jml_ditolak = $this->admin_model->get('id', 'apd', 3, [['petugas_id', $UserID], ['progress', 1], ['apd.mj_id !=', 0], ['periode_input', $perInput['periode_input'] ] ]);*/
            $jumJenisApd = $this->_get_jml_jenis_apd();
            $jml_input = $jml_belum_verif+$jml_terverif+$jml_ditolak;
            $persen_input = round( ($jml_input/$jumJenisApd)*100, 1);
            $persen_tervalidasi = round( ($jml_terverif/$jumJenisApd)*100, 1);
            $data_user = array( 'persen_inputAPD' => $persen_input,
                                'persen_APDterverif' => $persen_tervalidasi,
                                'jml_ditolak' => $jml_ditolak,
                                'jml_input_APD' => $jml_input,
                                'jml_tobe_verified' => $jml_belum_verif
                            );
            $this->admin_model->updateData('users', ['id', $UserID], $data_user);
        }
        return true;
    }

    public function update_rekap_petugas($password = null)
    {
        if ($password === 'rerogoestosapporo2024') {
        //if ($password == null) {
            $execute = $this->calc_rekap_petugas();
            echo 'sukses';
            return true;
        } else {
            echo 'failed';
            return false;
        }
    }

    //delete apd staff > 3
    public function delete_apd_staf()
    {
        $list_staff = $this->admin_model->get('id', 'users', 1, [['active', 1]], null, null, null, null, [['jabatan_id', 104], ['jabatan_id', 105]] );
        foreach ($list_staff as $staff) {
            $num_apd = $this->admin_model->get('id', 'apd', 3, [['petugas_id', $staff['id']]]);
            if ($num_apd > 3) {
                //$list_user[] = [$staff['id'], $num_apd];
                $list_apd_petugas = [2,3,4,6,8,9,10,11,12,13];
                foreach ($list_apd_petugas as $apd) {
                    $id_apd = $this->admin_model->get('id', 'apd', 2, [['petugas_id', $staff['id']], ['mj_id', $apd]]);
                    if (is_array($id_apd)) {
                        //$id_apds[$staff['id']][] = $id_apd['id'];
                        $this->admin_model->hard_delete('apd', 'id', $id_apd['id']);

                    }
                }
            }
        }
        //d($id_apds);
    }

    private function _get_jml_jenis_apd1($where=null, $roles_id=2)
    {
        $default_where = [['deleted', 0], ['role_id >=', $roles_id]];
        if (! is_null($where) && is_array($where)) {
            foreach ($where as $w) {
                array_push($default_where, $w);
            }
        }
        $result = $this->admin_model->get('id_mj', 'master_jenis_apd', 3, $default_where );
        return $result;
    }

    private function _get_apds1($select, $resultType, $where=null, $like=null, $or_where=null, $or_like=null, $joinTable=null, $periode=TRUE, $orderArr=null, $limitArr=null)
    {
        $default_join = [   ['apd', 'master_apd', 'master_apd.tahun', 'mapd_id', 'id_ma' ],
                            ['master_apd', 'master_jenis_apd', 'master_jenis_apd.jenis_apd', 'mj_id', 'id_mj' ]
                            ];
        $joinArr = ['master_keberadaan' => ['apd', 'master_keberadaan', 'master_keberadaan.keberadaan', 'mkp_id', 'id_mkp' ],
                    'master_merk' => ['master_apd', 'master_merk', 'master_merk.merk', 'mm_id', 'id_mm' ],
                    'master_kondisi' => ['apd', 'master_kondisi', 'master_kondisi.nama_kondisi', 'kondisi_id', 'id_mk' ], 
                    'master_progress_status' => ['apd', 'master_progress_status', 'master_progress_status.id_mps, master_progress_status.button', 'progress', 'id_mps' ],
                    'users' => ['apd', 'users', 'users.jabatan_id', 'petugas_id', 'id' ],
                    'master_jabatan' => ['users', 'master_jabatan', 'master_jabatan.mc_id', 'jabatan_id', 'id_mj' ],
                    'master_pos' => ['users', 'master_pos', 'master_pos.kode_pos', 'kode_pos_id', 'id_mp' ],
                    'verifikator' => ['apd', 'users', 'users.NRK', 'id_pemverifikasi', 'id' ] ];
        if (! is_null($joinTable) && is_array($joinTable)) {
            foreach ($joinTable as $jt) {
                if (isset($joinArr[$jt])) {
                    array_push($default_join, $joinArr[$jt]);
                }
            }
        }
        
        $default_where = [['apd.mj_id !=', 0]];
        if (! is_null($where) && is_array($where)) {
            foreach ($where as $w) {
                array_push($default_where, $w);
            }
        }
        //$default_where = (count($where_temp) < 1) ? null : $where_temp ;

        if ($periode) {
            $where_periode = ['periode_input', $this->data['periode'] ];
            array_push($default_where, $where_periode);
        }

        $like_temp = [];
        if (! is_null($like) && is_array($like)) {
            foreach ($like as $l) {
                array_push($like_temp, $l);
            }
        }
        $default_like = (count($like_temp) < 1) ? null : $like_temp ;

        //$selectStrList, $tableName, $resultType, $whereArr=null, $likeArr=null, $joinArr=null, $orderArr=null, $limitArr=null, $or_whereArr=null, $or_likeArr=null )
        $result = $this->admin_model->get($select, 'apd', $resultType, $default_where, $default_like, $default_join, $orderArr, $limitArr, $or_where, $or_like);
        return $result;
    }

    //update rekap progress apd

    public function update_apd_rekap()
    {
        $joinArr = [['users', 'master_jabatan', 'master_jabatan.mc_id', 'jabatan_id', 'id_mj' ], 
                        ['master_jabatan', 'master_controller', 'master_controller.role_id', 'mc_id', 'id' ] ];
        $list_user = $this->admin_model->get('users.id', 'users', 1, [['active', 1], ['jabatan_id', 104]], null, $joinArr);
        foreach ($list_user as $user) {
            $UserID = $user['id'];
            $role_id = $user['role_id'];
            $jml_belum_verif = $this->_get_apds1('id', 3, [['petugas_id', $UserID], ['progress', 2]]);
            $jml_terverif = $this->_get_apds1('id', 3, [['petugas_id', $UserID], ['progress', 3]]);
            $jml_ditolak = $this->_get_apds1('id', 3, [['petugas_id', $UserID], ['progress', 1]]);
            /*$jml_belum_verif = $this->admin_model->get('id', 'apd', 3, [['petugas_id', $UserID], ['progress', 2], ['apd.mj_id !=', 0], ['periode_input', $perInput['periode_input'] ] ]);
            $jml_terverif = $this->admin_model->get('id', 'apd', 3, [['petugas_id', $UserID], ['progress', 3], ['apd.mj_id !=', 0], ['periode_input', $perInput['periode_input'] ] ]);
            $jml_ditolak = $this->admin_model->get('id', 'apd', 3, [['petugas_id', $UserID], ['progress', 1], ['apd.mj_id !=', 0], ['periode_input', $perInput['periode_input'] ] ]);*/
            $jumJenisApd = $this->_get_jml_jenis_apd1(null, $role_id);
            $jml_input = $jml_belum_verif+$jml_terverif+$jml_ditolak;
            $persen_input = round( ($jml_input/$jumJenisApd)*100, 1);
            $persen_tervalidasi = round( ($jml_terverif/$jumJenisApd)*100, 1);
            $data_user = array( 'persen_inputAPD' => $persen_input,
                                'persen_APDterverif' => $persen_tervalidasi,
                                'jml_ditolak' => $jml_ditolak,
                                'jml_input_APD' => $jml_input,
                                'jml_tobe_verified' => $jml_belum_verif
                            );
            //return $this->petugas_model->updateData('users', ['id', $UserID], $data_user);
            /*if ($this->admin_model->updateData('users', ['id', $UserID], $data_user)) {
                $sukses[] = $UserID;
            } else {
                $gagal[] = $UserID;
            }*/
            $this->admin_model->updateData('users', ['id', $UserID], $data_user);      
        }
        //d($sukses, $gagal);
        echo 'sukses update_apd_rekap';

        //update data users.persen input apd
        //$UserID = $this->data['user_id'];
        
    }

    public function update_apd_rekap_eselon3()
    {
        $joinArr = [['users', 'master_jabatan', 'master_jabatan.mc_id', 'jabatan_id', 'id_mj' ], 
                        ['master_jabatan', 'master_controller', 'master_controller.role_id, master_controller.id as cont_id', 'mc_id', 'id' ] ];
        $orWhere = [['master_controller.id', 15], ['master_controller.id', 16], ['master_controller.id', 17], ['master_controller.id', 18], ['master_controller.id', 19], ['master_controller.id', 27], ['master_controller.id', 28], ['master_controller.id', 29]];
        $list_user = $this->admin_model->get('users.id', 'users', 1, [['active', 1] ], null, $joinArr, null, null, $orWhere);
        foreach ($list_user as $user) {
            $UserID = $user['id'];
            $role_id = $user['role_id'];
            $jml_belum_verif = $this->_get_apds1('id', 3, [['petugas_id', $UserID], ['progress', 2]]);
            $jml_terverif = $this->_get_apds1('id', 3, [['petugas_id', $UserID], ['progress', 3]]);
            $jml_ditolak = $this->_get_apds1('id', 3, [['petugas_id', $UserID], ['progress', 1]]);
            /*$jml_belum_verif = $this->admin_model->get('id', 'apd', 3, [['petugas_id', $UserID], ['progress', 2], ['apd.mj_id !=', 0], ['periode_input', $perInput['periode_input'] ] ]);
            $jml_terverif = $this->admin_model->get('id', 'apd', 3, [['petugas_id', $UserID], ['progress', 3], ['apd.mj_id !=', 0], ['periode_input', $perInput['periode_input'] ] ]);
            $jml_ditolak = $this->admin_model->get('id', 'apd', 3, [['petugas_id', $UserID], ['progress', 1], ['apd.mj_id !=', 0], ['periode_input', $perInput['periode_input'] ] ]);*/
            $jumJenisApd = $this->_get_jml_jenis_apd1(null, $role_id);
            $jml_input = $jml_belum_verif+$jml_terverif+$jml_ditolak;
            $persen_input = round( ($jml_input/$jumJenisApd)*100, 1);
            $persen_tervalidasi = round( ($jml_terverif/$jumJenisApd)*100, 1);
            $data_user = array( 'persen_inputAPD' => $persen_input,
                                'persen_APDterverif' => $persen_tervalidasi,
                                'jml_ditolak' => $jml_ditolak,
                                'jml_input_APD' => $jml_input,
                                'jml_tobe_verified' => $jml_belum_verif
                            );
            //return $this->petugas_model->updateData('users', ['id', $UserID], $data_user);
            /*if ($this->admin_model->updateData('users', ['id', $UserID], $data_user)) {
                $sukses[] = $UserID;
            } else {
                $gagal[] = $UserID;
            }*/
            $this->admin_model->updateData('users', ['id', $UserID], $data_user);      
        }
        //d($list_user);
        echo 'sukses update_apd_rekap_eselon3';

        //update data users.persen input apd
        //$UserID = $this->data['user_id'];
        
    }


}