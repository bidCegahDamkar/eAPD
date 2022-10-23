<?php
/**
 * Name:    apd
 * Author:  Kuswantoro
 *           kuz1toro@gmail.com
 * 
 * Created:  09.01.2021
 *
 * Description:  abstraction class for eAPD aplication.
 *
 * Requirements: PHP5.6 or above
 *
 * @package    CodeIgniter-eapd
 * @author     Kuswantoro
 * @filesource
 */
defined('BASEPATH') OR exit('No direct script access allowed');

/**
 * Class My_apd
 */
class My_apd
{
    protected $CI;
    public $data = [];

	public function __construct()
	{
		// Check compat first
		//$this->check_compatibility();

		//$this->config->load('ion_auth', TRUE);
		//$this->load->library(['email']);
		//$this->lang->load('ion_auth');
		//$this->load->helper(['cookie', 'language','url']);

		//$this->load->library('session');
        $this->CI =& get_instance();

		$this->CI->load->model('my_models');

        $state = $this->check_isOpenPeriode();
        $this->data['is_open'] = ($state['is_open']) ? true : false ;
        $this->data['periode'] = $state['periode'];
        $this->data['info_periode_input'] = $state['info_periode_input'];
	}

    public function check_isOpenPeriode()
    {
        $state = $this->CI->my_models->get('*', 'master_state', 2, [ ['tipe', 'input'] ]);
        if($state['is_open'] == 1){
            $data = array(
                'is_open' => true,
                'periode' => $state['periode_input'],
                'info_periode_input' => $state['deskripsi']
            );
        }else{
            $data = array(
                'is_open' => false,
                'periode' => null,
                'info_periode_input' => ''
            );
        }
        return $data;
    }

    /**
     * @param 
     * '' = 'id_mj, jenis_apd, picture'
     * $user_roles = array(['descripton' => $value, 'id' => $id, 'name' => $name], .....)
     * $where = [['id', $id], ['name', $name],...]
     * @return
     * $list_apd = array('jenisApd' => ['id_mj' => $value, 'jenis_apd' => $id, 'picture' => $name], .....)
     */
    public function get_list_jenis_apd($select_str, $user_roles, $result_type=1, $where=null)
    {
        $default_where = [['deleted', 0], ['role_id', $user_roles[0]->id]];
        if (count($user_roles)>0) {
            if(is_null($where)){
                $jenisApd = $this->CI->my_models->get($select_str, 'master_jenis_apd', $result_type, $default_where );
            } else {
                foreach ($where as $value) {
                    $default_where[] = $value;
                }
                $jenisApd = $this->CI->my_models->get($select_str, 'master_jenis_apd', $result_type, $default_where );
            }
            
            /*
            foreach ($user_roles as $role) {
                $key = 'APD '.$role->name;
                $jenisApd = $this->CI->my_models->get($select_str, 'master_jenis_apd', 1, [['deleted', 0], ['role_id', $role->id]]);
                $listAPD[$key] = $jenisApd;
            }*/
            return $jenisApd;
        }else{
            return false;
        }
    }

    public function get_apd($select_str, $id_mj, $user_id)
    {
        $join = ['apd', 'master_progress_status', 'master_progress_status.button', 'progress', 'id_mps' ];
        $dataAPD = $this->CI->my_models->get($select_str, 'apd', 2, [['mj_id', $id_mj],['petugas_id', $user_id],['periode_input', $this->data['periode']]], null, [$join]);
        return $dataAPD;
    }

    public function count_progress($user_id, $user_roles)
    {
        $jum_Apd = $this->CI->my_models->get('', 'master_jenis_apd', 3, [['deleted', 0], ['role_id', $user_roles[0]->id]]);
        $jum_apd_terinput = $this->CI->my_models->get('', 'apd', 3, [['petugas_id', $user_id],['periode_input', $this->data['periode']], ['progress >=',1], ['apd.mj_id !=', 0]]);
        $jum_apd_tervalidasi = $this->CI->my_models->get('', 'apd', 3, [['petugas_id', $user_id],['periode_input', $this->data['periode']], ['progress',3], ['apd.mj_id !=', 0]]);
        $jum_apd_tertolak = $this->CI->my_models->get('', 'apd', 3, [['petugas_id', $user_id],['periode_input', $this->data['periode']], ['progress',1], ['apd.mj_id !=', 0]]);
        $progress = [$jum_Apd, $jum_apd_terinput, $jum_apd_tervalidasi, $jum_apd_tertolak];
        return $progress;
    }

    public function get_list_apd($select_str, $result_type=1, $where_array=null, $like_array=null)
    {
        $join1 = ['apd', 'master_apd', 'master_apd.tahun', 'mapd_id', 'id_ma' ];
        $join2 = ['apd', 'master_jenis_apd', 'master_jenis_apd.jenis_apd', 'mj_id', 'id_mj' ];
        $join3 = ['master_apd', 'master_merk', 'master_merk.merk', 'mm_id', 'id_mm' ];
        $join4 = ['apd', 'master_keberadaan', 'master_keberadaan.keberadaan', 'mkp_id', 'id_mkp' ];
        $join5 = ['apd', 'master_kondisi', 'master_kondisi.nama_kondisi', 'kondisi_id', 'id_mk' ];
        $join6 = ['apd', 'master_progress_status', 'master_progress_status.id_mps, master_progress_status.deskripsi', 'progress', 'id_mps' ];
        $join = [$join1, $join2, $join3, $join4, $join5, $join6];
        $default_where = [['apd.mj_id !=', 0]];
        $default_like = null;
        if((! is_null($where_array)) && count($where_array)>0){
            foreach ($where_array as $value) {
                $default_where[] = $value;
            }
        }
        if((! is_null($like_array)) && count($like_array)>0){
            foreach ($like_array as $value) {
                $default_like[] = $value;
            }
        }
        $dataAPD = $this->CI->my_models->get($select_str, 'apd', $result_type, $default_where, $default_like, $join);
        return $dataAPD;
    }

    public function get_status_lap_sewaktu($where_str)
    {
        $data = $this->CI->my_models->get('deskripsi, next_step', 'master_progress_sewaktu', 2, [['id', $where_str]]);
        if(is_array($data)){
            return $data;
        }else{
            return null;
        }
    }

    public function get_list_lap_sewaktu($select_str, $result_type=1, $where_array=null, $like_array=null)
    {
        $join1 = ['lapor_sewaktu', 'apd', 'apd.mj_id', 'apd_id', 'id' ];
        $join2 = ['apd', 'master_apd', 'master_apd.tahun', 'mapd_id', 'id_ma' ];
        $join3 = ['apd', 'master_jenis_apd', 'master_jenis_apd.jenis_apd', 'mj_id', 'id_mj' ];
        $join4 = ['master_apd', 'master_merk', 'master_merk.merk', 'mm_id', 'id_mm' ];
        $join5 = ['lapor_sewaktu', 'master_progress_sewaktu', 'master_progress_sewaktu.next_step, master_progress_sewaktu.icons, master_progress_sewaktu.color', 'progress', 'id' ];
        $join = [$join1, $join2, $join3, $join4, $join5];
        $default_where = null;
        $default_like = null;
        if((! is_null($where_array)) && count($where_array)>0){
            foreach ($where_array as $value) {
                $default_where[] = $value;
            }
        }
        if((! is_null($like_array)) && count($like_array)>0){
            foreach ($like_array as $value) {
                $default_like[] = $value;
            }
        }
        $data = $this->CI->my_models->get($select_str, 'lapor_sewaktu', $result_type, $default_where , $default_like, $join);
        return $data;
    }

    public function get_admin($user_pos)
    {
        $data = $this->CI->my_models->get('nama', 'users', 2, [['NRK', $user_pos]]);
        if(is_array($data)){
            return $data['nama'];
        }else{
            return '';
        }
        
    }

    public function get_lap_sewaktu($select_str, $id)
    {
        $data = $this->CI->my_models->get($select_str, 'lapor_sewaktu', 2, [['id', $id]]);
        return $data;
    }

    /*
    public function get_list_user($select_str, $result_type=1, $where_array=null, $like_array=null, $like_array1=null)
    {
        $join1 = ['users', 'master_pos', 'master_pos.nama_pos', 'kode_pos', 'kode_pos' ];
        $join2 = ['users', 'master_status', 'master_status.status', 'status_id', 'id_stat' ];
        $join3 = ['users', 'master_jabatan', 'master_jabatan.nama_jabatan, master_jabatan.id_mj', 'jabatan_id', 'id_mj' ];
        //$join4 = ['master_apd', 'master_merk', 'master_merk.merk', 'mm_id', 'id_mm' ];
        //$join5 = ['lapor_sewaktu', 'master_progress_sewaktu', 'master_progress_sewaktu.next_step, master_progress_sewaktu.icons, master_progress_sewaktu.color', 'progress', 'id' ];
        $join = [$join1, $join2, $join3];
        $default_where = [['active', 1]];
        $default_like = null;
        if((! is_null($where_array)) && count($where_array)>0){
            foreach ($where_array as $value) {
                $default_where[] = $value;
            }
        }
        if((! is_null($like_array)) && count($like_array)>0){
            foreach ($like_array as $value) {
                $default_like[] = $value;
            }
        }
        $listUser = $this->CI->my_models->get($select_str, 'users', $result_type, $default_where , $default_like, $join, null, null, $like_array1, 'AND', 'OR');
        $perInput = $this->CI->my_models->get('periode_input', 'master_state', 2, [['tipe', 'input']]);
        $jumJenisApd = $this->CI->my_models->get('id_mj', 'master_jenis_apd', 3 );
        $data = array();
        foreach($listUser as $user)
        {
            $jmlInputAPD = $this->CI->my_models->get('id', 'apd', 3, [['petugas_id', $user['id']], ['apd.mj_id !=', 0], ['progress', 2], ['periode_input', $perInput['periode_input'] ] ]);
            $jmlAPDverified = $this->CI->my_models->get('id', 'apd', 3, [['petugas_id', $user['id']], ['progress', 3], ['periode_input', $perInput['periode_input'] ] ]);
            $jmlAPDrejected = $this->CI->my_models->get('id', 'apd', 3, [['petugas_id', $user['id']], ['progress', 1], ['periode_input', $perInput['periode_input'] ] ]);
            $persenInputAPD = (($jmlInputAPD+$jmlAPDverified+$jmlAPDrejected)/$jumJenisApd*100).' %';
            $persenAPDverified = ($jmlAPDverified/$jumJenisApd*100).' %';
            $data[]= array(
                'id' => $user['id'],
                'nama' => $user['nama'],
                'NRK' => $user['NRK'].'/ '.$user['NIP'],
				'nama_pos' => $user['nama_pos'],
				'nama_jabatan' => $user['nama_jabatan'],
                'status' => $user['status'],
                'photo' => $user['photo'],
                'persenInputAPD' => $persenInputAPD,
				'persenAPDverified' => $persenAPDverified,
				'jmlAPDrejected' => $jmlAPDrejected,
            );
		}
        return $data;
    }*/

    public function get_list_user($select_str, $result_type=1, $where_array=null, $like_array=null, $or_whereArr=null, $or_likeArr=null)
    {
        $join1 = ['users', 'master_pos', 'master_pos.nama_pos', 'kode_pos', 'kode_pos' ];
        $join2 = ['users', 'master_status', 'master_status.status', 'status_id', 'id_stat' ];
        $join3 = ['users', 'master_jabatan', 'master_jabatan.nama_jabatan, master_jabatan.id_mj', 'jabatan_id', 'id_mj' ];
        $join4 = ['master_jabatan', 'master_controller', 'master_controller.level', 'mc_id', 'id' ];
        //$join5 = ['lapor_sewaktu', 'master_progress_sewaktu', 'master_progress_sewaktu.next_step, master_progress_sewaktu.icons, master_progress_sewaktu.color', 'progress', 'id' ];
        $join = [$join1, $join2, $join3, $join4];
        $default_where = [['active', 1]];
        if((! is_null($where_array)) && count($where_array)>0){
            foreach ($where_array as $value) {
                $default_where[] = $value;
            }
        }
        $listUser = $this->CI->my_models->get($select_str, 'users', $result_type, $default_where , $like_array, $join, ['master_controller.level', 'DESC'], null, $or_whereArr, $or_likeArr);
        $perInput = $this->CI->my_models->get('periode_input', 'master_state', 2, [['tipe', 'input']]);
        $jumJenisApd = $this->CI->my_models->get('id_mj', 'master_jenis_apd', 3 );
        $data = array();
        foreach($listUser as $user)
        {
            $jmlInputAPD = $this->CI->my_models->get('id', 'apd', 3, [['petugas_id', $user['id']], ['apd.mj_id !=', 0], ['progress', 2], ['periode_input', $perInput['periode_input'] ] ]);
            $jmlAPDverified = $this->CI->my_models->get('id', 'apd', 3, [['petugas_id', $user['id']], ['progress', 3], ['periode_input', $perInput['periode_input'] ] ]);
            $jmlAPDrejected = $this->CI->my_models->get('id', 'apd', 3, [['petugas_id', $user['id']], ['progress', 1], ['periode_input', $perInput['periode_input'] ] ]);
            $persenInputAPD = round((($jmlInputAPD+$jmlAPDverified+$jmlAPDrejected)/$jumJenisApd*100), 1);
            $persenAPDverified = round(($jmlAPDverified/$jumJenisApd*100),1);
            $data[]= array(
                'id' => $user['id'],
                'nama' => $user['nama'],
                'NRK' => $user['NRK'].'/ '.$user['NIP'],
				'nama_pos' => $user['nama_pos'],
				'nama_jabatan' => $user['nama_jabatan'],
                'status' => $user['status'],
                'photo' => $user['photo'],
                'no_telepon' => $user['no_telepon'],
                'email' => $user['email'],
                'persenInputAPD' => $persenInputAPD,
				'persenAPDverified' => $persenAPDverified,
				'jmlAPDrejected' => $jmlAPDrejected,
            );
		}
        return $data;
    }

    public function get_report($apd_mj_id, $report_type=null, $kondisi, $perInput, $group_piket=null, $kode_pos, $list_jab_id_bawahan)
    {
        $join1 = ['apd', 'users', 'users.jabatan_id, users.group_piket', 'petugas_id', 'id' ];
        $join2 = ['apd', 'master_jenis_apd', 'master_jenis_apd.jenis_apd', 'mj_id', 'id_mj' ];
        $join3 = ['master_apd', 'master_merk', 'master_merk.merk', 'mm_id', 'id_mm' ];
        $join4 = ['apd', 'master_keberadaan', 'master_keberadaan.keberadaan', 'mkp_id', 'id_mkp' ];
        $join5 = ['apd', 'master_kondisi', 'master_kondisi.nama_kondisi', 'kondisi_id', 'id_mk' ];
        $join6 = ['apd', 'master_progress_status', 'master_progress_status.id_mps, master_progress_status.deskripsi', 'progress', 'id_mps' ];
        $join7 = ['users', 'master_pos', 'master_pos.kode_pos', 'kode_pos_id', 'id_mp' ];
        if (! is_null($report_type) ) {
            $join = [$join1, $join7];
            $keberadaan = ($report_type == 'belum') ? 3 : 2 ;
            if (is_null($group_piket)) {
                $default_where = [ ['apd.mj_id', $apd_mj_id], ['mkp_id', $keberadaan], ['progress', 3], ['periode_input', $perInput ] ];
            } else {
                $default_where = [ ['apd.mj_id', $apd_mj_id], ['mkp_id', $keberadaan], ['progress', 3], ['periode_input', $perInput ], ['users.group_piket', $group_piket ] ];
            }
            $default_like = [ ['master_pos.kode_pos', $kode_pos, 'after']  ];
            $i=0;
            foreach ($list_jab_id_bawahan as $jab_id) {
                $or_where_arr[$i] = ['users.jabatan_id', $jab_id];
                $i++;
            }
            $result = $this->CI->my_models->get('apd.id', 'apd', 3, $default_where, $default_like, $join, null, null, $or_where_arr );
        } else {
            $join = [$join1, $join5, $join7];
            if (is_null($group_piket)) {
                $default_where = [ ['apd.mj_id', $apd_mj_id], ['mkp_id', 1], ['progress', 3], ['periode_input', $perInput ], ['master_kondisi.kategori', $kondisi] ];
            } else {
                $default_where = [ ['apd.mj_id', $apd_mj_id], ['mkp_id', 1], ['progress', 3], ['periode_input', $perInput ], ['users.group_piket', $group_piket ], ['master_kondisi.kategori', $kondisi] ];
            }
            $default_like = [ ['master_pos.kode_pos', $kode_pos, 'after']  ];
            $i=0;
            foreach ($list_jab_id_bawahan as $jab_id) {
                $or_where_arr[$i] = ['users.jabatan_id', $jab_id];
                $i++;
            }
            $result = $this->CI->my_models->get('apd.id', 'apd', 3, $default_where, $default_like, $join, null, null, $or_where_arr );
        }
        return $result;
    }

    public function get_report_admin_sudin($apd_mj_id, $report_type=null, $kondisi, $group_piket=null, $kode_pos, $list_mc_id_bawahan, $result_type)
    {
        $select1 = 'users.nama, users.NRK, users.NIP';
        $join1 = ['apd', 'users', $select1, 'petugas_id', 'id' ];
        $join2 = ['apd', 'master_jenis_apd', 'master_jenis_apd.jenis_apd', 'mj_id', 'id_mj' ];
        $join3 = ['master_apd', 'master_merk', 'master_merk.merk', 'mm_id', 'id_mm' ];
        $join4 = ['apd', 'master_keberadaan', 'master_keberadaan.keberadaan', 'mkp_id', 'id_mkp' ];
        $join5 = ['apd', 'master_kondisi', 'master_kondisi.nama_kondisi', 'kondisi_id', 'id_mk' ];
        $join6 = ['apd', 'master_progress_status', 'master_progress_status.id_mps, master_progress_status.deskripsi', 'progress', 'id_mps' ];
        $join7 = ['users', 'master_jabatan', 'master_jabatan.nama_jabatan', 'jabatan_id', 'id_mj' ];
        $join8 = ['master_jabatan', 'master_controller', 'master_controller.level', 'mc_id', 'id' ];
        $join9 = ['users', 'master_pos', 'master_pos.nama_pos', 'kode_pos_id', 'id_mp' ];
        $join10 = ['apd', 'master_apd', 'master_apd.tahun', 'mapd_id', 'id_ma' ];
        $default_like = [ ['master_pos.kode_pos', $kode_pos, 'after']  ];
        $i=0;
        foreach ($list_mc_id_bawahan as $mc_id) {
            $or_where_arr[$i] = ['master_jabatan.mc_id', $mc_id];
            $i++;
        }
        $orderArr = ["master_controller.level desc", "users.id asc"];
        $join = [$join1, $join9, $join7, $join8, $join5, $join2, $join10, $join3, $join4];
        if($report_type == 'all'){
            
            if ($apd_mj_id == 'all') {
                $default_where = [ ['progress', 3] ];
            }else{
                $default_where = [ ['apd.mj_id', $apd_mj_id], ['progress', 3] ];
            }
        } else if (! is_null($report_type) ) {
            //$join = [$join1, $join9, $join7, $join8];
            $keberadaan = ($report_type == 'belum') ? 3 : 2 ;
            if ($apd_mj_id == 'all') {
                $default_where = [ ['mkp_id', $keberadaan], ['progress', 3] ];
            }else{
                $default_where = [ ['apd.mj_id', $apd_mj_id], ['mkp_id', $keberadaan], ['progress', 3] ];
            }
        } else {
            //$join = [$join1, $join9, $join7, $join8, $join5];
            if ($apd_mj_id == 'all') {
                $default_where = [ ['mkp_id', 1], ['progress', 3], ['master_kondisi.kategori', $kondisi] ];
            }else{
                $default_where = [ ['apd.mj_id', $apd_mj_id], ['mkp_id', 1], ['progress', 3], ['master_kondisi.kategori', $kondisi] ];
            }
        }
        $result = $this->CI->my_models->get('apd.id, periode_input', 'apd', $result_type, $default_where, $default_like, $join, $orderArr, null, $or_where_arr );
        return $result;
    }

    public function get_report_detail_admin_sudin($apd_mj_id, $total=null, $kondisi, $keberadaan, $id_mp, $list_mc_id_bawahan, $result_type)
    {
        $select1 = 'users.nama, users.NRK, users.NIP';
        $join1 = ['apd', 'users', $select1, 'petugas_id', 'id' ];
        $join2 = ['apd', 'master_jenis_apd', 'master_jenis_apd.jenis_apd', 'mj_id', 'id_mj' ];
        $join3 = ['master_apd', 'master_merk', 'master_merk.merk', 'mm_id', 'id_mm' ];
        $join4 = ['apd', 'master_keberadaan', 'master_keberadaan.keberadaan', 'mkp_id', 'id_mkp' ];
        $join5 = ['apd', 'master_kondisi', 'master_kondisi.nama_kondisi', 'kondisi_id', 'id_mk' ];
        $join6 = ['apd', 'master_progress_status', 'master_progress_status.id_mps, master_progress_status.deskripsi', 'progress', 'id_mps' ];
        $join7 = ['users', 'master_jabatan', 'master_jabatan.nama_jabatan', 'jabatan_id', 'id_mj' ];
        $join8 = ['master_jabatan', 'master_controller', 'master_controller.level', 'mc_id', 'id' ];
        $join9 = ['users', 'master_pos', 'master_pos.nama_pos', 'kode_pos_id', 'id_mp' ];
        $join10 = ['apd', 'master_apd', 'master_apd.tahun', 'mapd_id', 'id_ma' ];

        $i=0;
        foreach ($list_mc_id_bawahan as $mc_id) {
            $or_where_arr[$i] = ['master_jabatan.mc_id', $mc_id];
            $i++;
        }
        $orderArr = ["master_controller.level desc", "users.id asc"];
        $join = [$join1, $join9, $join7, $join8, $join5, $join2, $join4];

        $default_where1 = [['apd.mj_id', $apd_mj_id], ['progress', 3], ['master_pos.id_mp', $id_mp]];
        if ($total == 'all') {
            $default_where = $default_where1;
        }else{
            if ($kondisi == 'all') {
                $default_where = array_merge($default_where1, [['mkp_id', 1]]);
            } else if (! is_null($kondisi) ) {
                $default_where = array_merge($default_where1, [['mkp_id', 1], ['master_kondisi.kategori', $kondisi]]);
            } else {
                if ($keberadaan == 'all') {
                    $default_where = array_merge($default_where1, [['mkp_id !=', 1]]);
                } else if (! is_null($keberadaan) ){
                    $default_where = array_merge($default_where1, [['mkp_id', $keberadaan]]);
                }
            }
        }

        $result = $this->CI->my_models->get('apd.id', 'apd', $result_type, $default_where, null, $join, null, null, $or_where_arr );
        return $result;
    }

}
