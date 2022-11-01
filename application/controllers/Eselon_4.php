<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Eselon_4 extends CI_Controller {
    public $data = [];

    public function __construct()
	{
        parent::__construct();
        $this->load->database();
        $this->load->library(['ion_auth', 'my_apd', 'form_validation']);
        $this->load->helper(['url', 'language']);
        $this->load->model(['petugas_model', 'admin_model']);
        $this->authenticate();
        $user = $this->ion_auth->user()->row();
        $config = $this->petugas_model->get_controller($user->jabatan_id);
        $this->config->load($config['config']);
        //$this->data['user'] = $this->ion_auth->user()->row();
        $this->data['username'] = $user->nama;
        $this->data['user_id'] = $user->id;
        $id_mp = $this->admin_model->get('kode_pos', 'master_pos', 2, [['id_mp', $user->kode_pos_id]] );
        $this->data['kode_pos'] = $id_mp['kode_pos'];
        
        //$this->data['kode_pos'] = '0.1';
        $this->data['nrk'] = $user->NRK;
        $this->data['is_plt'] = (strpos($this->data['nrk'], 'plt-') !== false) ? true : false ;
        //$plt = $this->admin_model->get('id_mj', 'master_jabatan', 2, [['plt_id', $this->data['user_id'] ]] );
        $temp_jabatan = $this->petugas_model->get('nama_jabatan, keterangan', 'master_jabatan', [['id_mj', $user->jabatan_id]], null, 2);
        $jabatan_es['nama_jabatan'] = ($this->data['is_plt']) ? 'plt. '.$temp_jabatan['nama_jabatan'] : $temp_jabatan['nama_jabatan'] ;
        //$this->data['jabatan'] = $this->petugas_model->get('nama_jabatan', 'master_jabatan', [['id_mj', $user->jabatan_id]], null, 2);
        $this->data['jabatan'] = $jabatan_es;
        $this->data['unit'] = $temp_jabatan['keterangan'];

        $this->data['penempatan'] = $this->petugas_model->get('nama_pos', 'master_pos', [['id_mp', $user->kode_pos_id]], null, 2);
        $state = $this->my_apd->check_isOpenPeriode();
        $this->data['is_open'] = ($state['is_open']) ? true : false ;
        $this->data['periode'] = $state['periode'];
        $this->data['info_periode_input'] = $state['info_periode_input'];
        $profil_foto_path = 'upload/petugas/profil/'.$user->photo;
        $profil_thumb_foto_path = 'upload/petugas/profil/thumb/thumb_'.$user->photo;
        $this->data['avatar'] = (file_exists($profil_foto_path) && !is_null($user->photo)) ? $profil_foto_path : 'upload/petugas/profil/default.png' ;
        $this->data['thumb_avatar'] = (file_exists($profil_thumb_foto_path) && !is_null($user->photo)) ? $profil_thumb_foto_path : 'upload/petugas/profil/default.png' ;
        
        $this->data['password'] = $user->password;
        $this->data['user_roles'] = $this->ion_auth->get_users_groups($user->id)->result();
        $this->data['group_piket'] = $user->group_piket;
        $this->data['jab_id'] = $user->jabatan_id;
        $this->data['NIP'] = $user->NIP;
        $this->data['kode_pos_id'] = $user->kode_pos_id;
        $this->data['user_role'] = $config['role_id'];
        //$this->data['jumJenisApd'] = $this->admin_model->get('id_mj', 'master_jenis_apd', 3, [['deleted', 0]] );
    }

    private function authenticate(){
        // verifikasi pangilan
        $user = $this->ion_auth->user()->row();
        if (!$this->ion_auth->logged_in())
		{
			redirect('auth/login', 'refresh');
		}
        $id = $this->config->item('id');        //controller name
        $jab_id = $user->jabatan_id;
        $user_id = $this->petugas_model->get_controller($jab_id);
        if(is_array($user_id)){
            if ($user_id['controller'] != 'eselon_4') {
                redirect("my404");
            }
        }else {
            redirect('auth/login', 'refresh');
        }
        $this->data['controller'] = $user_id['controller'];
    }

    private function check_isOpenPeriode()
    {
        $state = $this->petugas_model->get('*', 'master_state', null, null, 2);
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
    * Manage uploadImage
    * @return Response
   */

   private function resizeImage($filename, $upload_path, $thumbs)
   {
        $this->load->library('image_lib');
        $source_path = FCPATH.$upload_path.'/'.$filename;
        $target_path = FCPATH.$upload_path.'/'.'thumbs/';
        $config_manip = array(
            'image_library' => 'gd2',
            'source_image' => $source_path,
            'maintain_ratio' => TRUE,
        );
        if ($thumbs) {
            $config_manip['new_image'] = $target_path ;
            $config_manip['create_thumb'] = true ;
            $config_manip['thumb_marker'] = '_thumb' ;
            $config_manip['width'] = 150 ;
            $config_manip['height'] = 150 ;
        } else {
            $config_manip['width'] = 1024 ;
            $config_manip['height'] = 768 ;
        }
        $this->image_lib->clear();
        $this->image_lib->initialize($config_manip);
        //$this->image_lib->resize();
        //$this->load->library('image_lib', $config_manip);
        if (!$this->image_lib->resize()) {
            echo $this->image_lib->display_errors();
        }
        //$this->image_lib->clear();
   }

   public function index()
    {
        $size_data = $this->petugas_model->get('id', 'users_ukuran', [['users_id', $this->data['user_id'] ]], null, 2);
        if (is_array($size_data) || $this->data['is_plt']) {
            redirect(''.$this->data['controller'].'/home');
        } else {
            $this->_fill_size();
        }
    }

    //ex $joinTable = ['master_status', 'master_jabatan']
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
        
        $default_where = [['active', 1], ['users.deleted', 0]];
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
        $default_where = [['deleted', 0], ['role_id >=', $roles_id]];
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
        $default_where = [['master_jenis_apd.deleted', 0], ['role_id >=', $roles_id]];
        if (! is_null($where) && is_array($where)) {
            foreach ($where as $w) {
                array_push($default_where, $w);
            }
        }

        $result = $this->admin_model->get($select, 'master_jenis_apd', $resultType, $default_where );
        return $result;
    }

    private function _get_mimes($base64_img)
    {
        $data = explode( ',', $base64_img );
        $split = explode( '/', $data[0] );
        $split = explode( ';', $split[1] );
        return $split[0];
    }

    private function _update_my_rekap()
    {
        //update data users.persen input apd
        $UserID = $this->data['user_id'];
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
        return $this->petugas_model->updateData('users', ['id', $UserID], $data_user);
    }

    private function _update_user_rekap($UserID=null)
    {
        //update data users.persen input apd
        //$UserID = $this->data['user_id'];
        if (! is_null($UserID) && ! empty($UserID)) {
            $jml_belum_verif = $this->_get_apds('id', 3, [['petugas_id', $UserID], ['progress', 2]]);
            $jml_terverif = $this->_get_apds('id', 3, [['petugas_id', $UserID], ['progress', 3]]);
            $jml_ditolak = $this->_get_apds('id', 3, [['petugas_id', $UserID], ['progress', 1]]);
            /*$jml_belum_verif = $this->admin_model->get('id', 'apd', 3, [['petugas_id', $UserID], ['progress', 2], ['apd.mj_id !=', 0], ['periode_input', $perInput['periode_input'] ] ]);
            $jml_terverif = $this->admin_model->get('id', 'apd', 3, [['petugas_id', $UserID], ['progress', 3], ['apd.mj_id !=', 0], ['periode_input', $perInput['periode_input'] ] ]);
            $jml_ditolak = $this->admin_model->get('id', 'apd', 3, [['petugas_id', $UserID], ['progress', 1], ['apd.mj_id !=', 0], ['periode_input', $perInput['periode_input'] ] ]);*/

            $joinArr2 = [['users', 'master_jabatan', 'master_jabatan.mc_id', 'jabatan_id', 'id_mj' ], 
                        ['master_jabatan', 'master_controller', 'master_controller.role_id', 'mc_id', 'id' ] ];
            $role_id_arr = $this->admin_model->get('users.id', 'users', 2, [['users.id', $UserID]], null, $joinArr2);
            $user_role_id = $role_id_arr['role_id'];
            
            $jumJenisApd = $this->_get_jml_jenis_apd(null, $user_role_id);
            $jml_input = $jml_belum_verif+$jml_terverif+$jml_ditolak;
            $persen_input = round( ($jml_input/$jumJenisApd)*100, 1);
            $persen_tervalidasi = round( ($jml_terverif/$jumJenisApd)*100, 1);
            $data_user = array( 'persen_inputAPD' => $persen_input,
                                'persen_APDterverif' => $persen_tervalidasi,
                                'jml_ditolak' => $jml_ditolak,
                                'jml_input_APD' => $jml_input,
                                'jml_tobe_verified' => $jml_belum_verif
                            );
            return $this->petugas_model->updateData('users', ['id', $UserID], $data_user);
        } else {
            return false;
        }
    }

    public function home()
    {
        $this->load->helper('date');
        $jab_id_arr = $this->config->item('jabID_list_monitoring');
        foreach ($jab_id_arr as $jab_id) {
            $or_where_arr[] = ['jabatan_id', $jab_id];
        }
        //$or_where_arr[] = ['jabatan_id', $this->data['jab_id']];
        $orderArr = ['kode_pos', 'ASC'];

        $select = 'jml_pns, jml_pjlp, jml_input, jml_verif, jml_ditolak, chart_input_APD, chart_verif_APD, jml_ops, jml_non_ops';
        if ($this->data['jab_id'] == 20 || $this->data['jab_id'] == 21 || $this->data['jab_id'] == 22) {
            $this->data['data_sektor'] = $data_sektor = $this->admin_model->get($select, 'master_pos', 2, [['deleted', 0], ['kode_pos', $this->data['kode_pos']] ]);
        } else {
            $this->data['data_sektor'] = $data_sektor = $this->admin_model->get($select, 'master_sektor', 2, [['deleted', 0], ['kode', $this->data['kode_pos']] ]);
        }

        $jmlJenisApdOps = $this->_get_jml_jenis_apd(null, 2);
        $jmlJenisApdNons = $this->_get_jml_jenis_apd(null, 9);

        //$jumJenisApd = $this->_get_jml_jenis_apd();
        $this->data['jmlApd'] = (($data_sektor['jml_ops']*$jmlJenisApdOps)+($data_sektor['jml_non_ops']*$jmlJenisApdNons));
        
        //$this->data['jmlPNS'] = $this->_get_users('id', 3, [['status_id', 0]], [['master_pos.kode_pos', $this->data['kode_pos'], 'after']]);
        //$this->data['jmlPJLP'] = $this->_get_users('id', 3, [['status_id', 1]], [['master_pos.kode_pos', $this->data['kode_pos'], 'after']]);
        /*
        $list_pos = $this->admin_model->get('kode_pos, nama_pos', 'master_pos', 1, [['deleted', 0]], [['kode_pos', $this->data['kode_pos'], 'after']], null, $orderArr);
        foreach ($list_pos as $pos) {
            $pns = $this->admin_model->get('id', 'users', 3, [['status_id', 0], ['active', 1], ['kode_pos', $pos['kode_pos']]], null, null, null, null, $or_where_arr );
            $pjlp = $this->admin_model->get('id', 'users', 3, [['status_id', 1], ['active', 1], ['kode_pos', $pos['kode_pos']]], null, null, null, null, $or_where_arr );
            $subTotal = $pns+$pjlp;
            $list_detail_p[] = ['pos' => $pos['nama_pos'], 'pns' => $pns, 'pjlp' => $pjlp, 'subTotal' => $subTotal];
        }
        */
        /*$jumJenisApd = $this->_get_jml_jenis_apd();
        $listPeg = $this->_get_users('id', 1, null, [['master_pos.kode_pos', $this->data['kode_pos'], 'after']], $or_where_arr);
        $jumSdhInputAll = 0;
        foreach($listPeg as $peg){
            $numApd = $this->admin_model->get('id', 'apd', 3, [ ['petugas_id', $peg['id']], ['progress', 2], ['periode_input',$this->data['periode'] ] ] );
            if ($numApd >= $jumJenisApd) {
                $jumSdhInputAll++;
            }
        }
        $this->data['decode'] = base64_decode('Cjo6X19jb25zdHJ1Y3QoKTsgJHRoaXMtPmxvYWQtPmRhdGFiYXNlKCk7ICR0aGlzLT5sb2FkLT5saWJyYXJ5KFsnaW9uX2F1dGgnLCAnZm9ybV92YWxpZGF0aW9uJ10pOyAkdGhpcy0+bG9hZC0+aGVscGVyKFsndXJsJywgJ2xhbmd1YWdlJ10pOyAkdGhpcy0+bG9hZC0+bW9kZWwoJ2FkbWluX21vZGVsJyk7IC8vIHZlcmlmaWthc2kgcGFuZ2lsYW4gJHVzZXIgPSAkdGhpcy0+aW9uX2F1dGgtPnVzZXIoKS0+cm93KCk7IGlmICghJHRoaXMtPmlvbl9hdXRoLT5sb2dnZWRfaW4oKSkgeyByZWRpcmVjdCgnYXV0aC9sb2dpbicsICdyZWZyZXNoJyk7IH0gJGphYl9pZCA9ICR1c2VyLT5qYWJhdGFuX2lkOyAkdXNlcl90aXBlID0gJHRoaXMtPmFkbWluX21vZGVsLT5nZXRfdXNlclRpcGUoJGphYl9pZCk7IGlmKGlzX2FycmF5KCR1c2VyX3RpcGUpKXsgaWYgKCEgJHVzZXJfdGlwZVsndGlwZV91c2VyX2lkJ10gPT0gMykgeyAkdGhpcy0+c2hvd19teUVycm9yKCdBbmRhIHRpZGFrIG1lbWlsaWtpIGtld2VuYW5nYW4gdW50dWsgbWVtYnVrYSBoYWxhbWFuIGluaScpOyB9IH0gLy8kdGhpcy0+ZGF0YVsndXNlciddID0gJHRoaXMtPmlvbl9hdXRoLT51c2VyKCktPnJvdygpOyAkdGhpcy0+ZGF0YVsndXNlcm5hbWUnXSA9ICR1c2VyLT5uYW1hOyAkdGhpcy0+ZGF0YVsndXNlcl9pZCddID0gJHVzZXItPmlkOyAkdGhpcy0+ZGF0YVsnamFiYXRhbiddID0gJHRoaXMtPmFkbWluX21vZGVsLT5nZXQoJ25hbWFfamFiYXRhbicsICdtYXN0ZXJfamFiYXRhbicsIDIsIFtbJ2lkX21qJywgJHVzZXItPmphYmF0YW5faWRdXSk7ICR0aGlzLT5kYXRhWydwZW5lbXBhdGFuJ10gPSAkdGhpcy0+YWRtaW5fbW9kZWwtPmdldCgnbmFtYV9wb3MnLCAnbWFzdGVyX3BvcycsIDIsIFtbJ2tvZGVfcG9zJywgJHVzZXItPmtvZGVfcG9zXV0pOyAkc3RhdGUgPSAkdGhpcy0+Y2hlY2tfaXNWZXJpZmljYXRpb25PcGVuKCk7ICR0aGlzLT5kYXRhWydpc19vcGVuJ10gPSAoJHN0YXRlWydpc19vcGVuJ10pID8gdHJ1ZSA6IGZhbHNlIDsgJHRoaXMtPmRhdGFbJ3BlcmlvZGUnXSA9ICRzdGF0ZVsncGVyaW9kZSddOyAkdGhpcy0+ZGF0YVsnaW5mb19wZXJpb2RlX2lucHV0J10gPSAkc3RhdGVbJ2luZm9fcGVyaW9kZV9pbnB1dCddOyAkdGhpcy0+ZGF0YVsnYXZhdGFyJ10gPSAoISBpc19udWxsKCR1c2VyLT5waG90bykpID8gJ3VwbG9hZC9wZXR1Z2FzL3Byb2ZpbC8nLiR1c2VyLT5waG90byA6ICd1cGxvYWQvcGV0dWdhcy9wcm9maWwvZGVmYXVsdC5wbmcnIDsgJHRoaXMtPmRhdGFbJ25yayddID0gJHVzZXItPk5SSzsgJHRoaXMtPmRhdGFbJ3Bhc3N3b3JkJ10gPSAkdXNlci0+cGFzc3dvcmQ7ICR0aGlzLT5kYXRhWydrb2RlX3BvcyddID0gJHVzZXItPmtvZGVfcG9zOyAkdGhpcy0+ZGF0YVsnc3RhdHVzX2lkJ10gPSAkdXNlci0+c3RhdHVzX2k=');
        $this->data['jmlBlmInput'] = count($listPeg) - $jumSdhInputAll;
        $this->data['jumSdhInputAll'] = $jumSdhInputAll;
        $this->data['jmlApd'] = count($listPeg) * $jumJenisApd;
        $this->data['jmlNotVerApd'] = $this->_get_apds('apd.id', 3, [ ['progress', 2], ['master_apd.mj_id !=', 0], ['periode_input',$this->data['periode'] ] ], [ ['master_pos.kode_pos', $this->data['kode_pos'], 'after'] ], $or_where_arr, null, ['users', 'master_pos']);
        $this->data['jmlVerApd'] = $this->_get_apds('apd.id', 3, [ ['progress', 3], ['master_apd.mj_id !=', 0], ['periode_input',$this->data['periode'] ] ], [ ['master_pos.kode_pos', $this->data['kode_pos'], 'after'] ], $or_where_arr, null, ['users', 'master_pos']);
        $this->data['jmlRefuseApd'] = $this->_get_apds('apd.id', 3, [ ['progress', 1], ['master_apd.mj_id !=', 0], ['periode_input',$this->data['periode'] ] ], [ ['master_pos.kode_pos', $this->data['kode_pos'], 'after'] ], $or_where_arr, null, ['users', 'master_pos']);
        */
        //$this->data['list_pos'] = $list_detail_p;
        $this->data['pageTitle'] = 'Dashboard';
        $this->data['main_content'] = 'petugas/home';
		$this->load->view('petugas/includes/template', $this->data);
    }

    public function lapor()
    {
        if (! $this->data['is_open'] || $this->data['is_plt']) {
            redirect("my404");
        }
        $this->data['pageTitle'] = 'Lapor APD';
        $jenisApd = $this->_get_list_jenis_apd('id_mj, jenis_apd, picture');
        $this->data['numJenisApd'] = count($jenisApd);
        $i = 0;
        foreach ($jenisApd as $apd) {
            //$dataAPD = $this->_get_apds('progress', 2, [['master_apd.mj_id', $apd['id_mj']],['petugas_id', $this->data['user_id']],['periode_input', $this->data['periode']]], null, null, null, ['master_progress_status']);
            $dataAPD = $this->my_apd->get_apd('progress', $apd['id_mj'], $this->data['user_id']);
            if (is_array($dataAPD)) {
                $statusArr = $this->petugas_model->get('button', 'master_progress_status', [['id_mps', $dataAPD['progress']]], null, 2);
                $buttonProp = json_decode($statusArr['button']);
                $is_finish = ($dataAPD['progress'] > 1) ? true : false ;
                //if($is_finish){$progress++;}
            } else {
                $is_finish = false;
                $statusArr = $this->petugas_model->get('button', 'master_progress_status', [['id_mps', 0]], null, 2);
                $buttonProp = json_decode($statusArr['button']); 
            }
            $jenisApd[$i]['is_finish'] = $is_finish;
            $jenisApd[$i]['buttonProp'] = $buttonProp;
            $i++;
        }
        //$persenProgress = $progress/$this->data['numJenisApd']*100;
        $this->data['jenisApd'] = $jenisApd;
        $data_rekap = $this->_get_users('jml_input_APD, persen_inputAPD, persen_APDterverif, users.jml_ditolak', 2, [['id', $this->data['user_id']]]);
        $jml_terverif = round(($data_rekap['persen_APDterverif']/100)*$this->data['numJenisApd'], 0 );
        //$this->data['progress'] = $this->my_apd->count_progress($this->data['user_id'], $this->data['user_roles']);
        $this->data['progress'] = array($this->data['numJenisApd'], $data_rekap['jml_input_APD'], $jml_terverif, $data_rekap['jml_ditolak'] );
        //$this->data['persenProgress'] = $persenProgress;
        $this->data['buttonProp'] = $buttonProp;
        
        //d($buttonProp);
        //d($this->data['user_id']);
        $this->data['main_content'] = 'petugas/lapor';
		$this->load->view('petugas/includes/template', $this->data);
        //$this->load->view('petugas/lapor', $this->data);
    }


    public function laporAPD()
    {
        if (! $this->data['is_open'] || $this->data['is_plt']) {
            redirect("my404");
        }
        $id_mj = $this->uri->segment(3);
        $jenisApd = $this->_get_list_jenis_apd('id_mj, jenis_apd, mtu_id', [['id_mj', $id_mj]], 2);
        if (! is_array($jenisApd) )  {
            redirect("my404");
        }
        $id_mj = $jenisApd['id_mj'];
        //$dataAPD = $this->petugas_model->get('*', 'apd', [['mj_id', $id_mj],['petugas_id', $this->data['user_id']],['periode_input', $this->data['periode']]], null, 2);
        //$dataAPD = $this->_get_apds('*', 2, [['master_apd.mj_id', $id_mj],['petugas_id', $this->data['user_id']]], null, null, null, ['master_progress_status']);
        $dataAPD = $this->my_apd->get_apd('*', $id_mj, $this->data['user_id']);
        //cek apakah sudah ada data ini
        if (is_array($dataAPD) )
        {
            //sudah ada maka cek apakah masih boleh edit
            if ($dataAPD['progress'] > 2) {
                redirect("my404");
            }
            $noData = false;
        }else
        {
            $noData = true;
        }
        
        $this->load->helper('date');
        //upload foto apd user
        //$this->load->library('upload');
		$upload_APD_path = 'upload/petugas/APD/';
		/*$config['upload_path']          = FCPATH.$upload_APD_path;
		$config['allowed_types']        = 'gif|jpg|png|jpeg';
		$config['max_size']             = 5000;
		$config['remove_spaces']		= TRUE;  //it will remove all spaces
        // if its editing then use rewrite mode
        if(! $noData){
            $config['overwrite']        = true;
        }
		$this->upload->initialize($config);*/
        if ($this->input->server('REQUEST_METHOD') === 'POST')
		{
            //form validation
            $this->form_validation->set_rules('mkp_id', 'mkp_id', 'required');
            $mkp_id = isZonk($this->input->post('mkp_id'));
            if($mkp_id != '3')
            {
                $this->form_validation->set_rules('mapd_id', 'mapd_id', 'required');
                $this->form_validation->set_rules('kondisi_id', 'kondisi_id', 'required');
			    $this->form_validation->set_rules('ukuran', 'ukuran', 'required');
            }
			$this->form_validation->set_error_delimiters('<div class="alert alert-error"><a class="close" data-dismiss="alert">×</a><strong>', '</strong></div>');
            // check agreement checkbox
            if(is_array($this->input->post('myCheckbox')))
            {
                $aggreement = ($this->input->post('myCheckbox')[0] == 'on') ? true : false ;
            }else{
                $aggreement = false;
            }
            //set progress status
            $progress = 2;
			//if the form has passed through the validation
			if ($this->form_validation->run() && $aggreement)
			{
				$my_time = date("Y-m-d H:i:s", now('Asia/Jakarta'));
				$data_basic = array(
                    'mj_id' => $id_mj,
					'mkp_id' => $mkp_id,
					'petugas_id' => $this->data['user_id'],
                    'periode_input' => $this->data['periode'],
                    'progress' => $progress
				);
                if ($mkp_id == '1' || $mkp_id == '2') {
                    $data_add = array(
                        'mapd_id' => isZonk($this->input->post('mapd_id')),
                        'kondisi_id' => isZonk($this->input->post('kondisi_id')),
                        'ukuran' => isZonk($this->input->post('ukuran')),
                        'keterangan' => isZonk($this->input->post('keterangan'))
                    );
                } else {
                    $data_add = array(
                        'mapd_id' => null,
                        'kondisi_id' => null,
                        'ukuran' => null,
                        'keterangan' => null,
                        'foto_apd' => null
                    );
                }
                $data_to_store = array_merge($data_basic, $data_add);

                if ($noData) {
                    $data_to_store['created_at'] = $my_time;
                } else {
                    $data_to_store['updated_at'] = $my_time;
                }
                
                if (! empty($this->input->post('no_urut'))) {
                    $data_to_store['no_urut'] = $this->input->post('no_urut');
                }
                
				//set upload denah gedung
				/*if( $this->upload->do_upload('foto_apd'))
				{
					$upload_data = $this->upload->data();
					$raw = $upload_data['raw_name'];
					$file_type = $upload_data['file_ext'];
					$data_to_store['foto_apd'] = $raw.$file_type;
                    $this->resizeImage($data_to_store['foto_apd'], $upload_APD_path, false);  // resize image
                    $this->resizeImage($data_to_store['foto_apd'], $upload_APD_path, true);  //create thumb
				}*/

                //upload foto jika keberadaan ada
                if($mkp_id == '1')
                {
                    $image = $_POST['image'];
                    if (!empty($image)) {
                        $type = $this->_get_mimes($image);
                        $name = $this->data['nrk'].'_'.rand(0, 10000);
                        $img_name = $name.'.'.$type;
                        $data_to_store['foto_apd'] = $img_name;
                        file_put_contents($upload_APD_path.$img_name, file_get_contents($image));
                    }
                }

                // check wheather its new data or editing data
                if ($noData) {
                    //if the insert has returned true then we show the flash message
                    if($this->petugas_model->insertData('apd', $data_to_store)){
                        $this->session->set_flashdata('flash_message', 'sukses');
                    }else{
                        $this->session->set_flashdata('flash_message', 'gagal');
                    }
                } else {
                    if($this->petugas_model->updateData('apd', ['id', $dataAPD['id']], $data_to_store)){
                        $this->session->set_flashdata('flash_message', 'sukses');
                    }else{
                        $this->session->set_flashdata('flash_message', 'gagal');
                    }
                }
                //update data users.persen input apd
                $this->_update_my_rekap();

				//redirect('Prainspeksi_gedung/update/'.$id.'');
				redirect(''.$this->data['controller'].'/lapor');
			}//validation run
        }

        $masterAPD = $this->petugas_model->get_masterAPD_groupbyjenis($id_mj);
        $listKeberadaan = $this->petugas_model->get('*', 'master_keberadaan', null, null, 1);
        $listKondisi = $this->petugas_model->get_masterKondisi_groupbyjenis($id_mj);
        $listUkuran = $this->petugas_model->get('id_mtu, daftar_ukuran', 'master_tipe_ukuran', [['id_mtu', $jenisApd['mtu_id']]], null, 2);
        
        $this->data['post'] = $this->input->post();
        $this->data['masterAPD'] = $masterAPD;
        $this->data['listKeberadaan'] = $listKeberadaan;
        $this->data['listKondisi'] = $listKondisi;
        $this->data['listUkuran'] = $listUkuran;
        $this->data['thead'] = array(
			'Merk & Tahun<a class="text-danger">*</a>','Keberadaan<a class="text-danger">*</a>', 'Kondisi<a class="text-danger">*</a>', 'Ukuran<a class="text-danger">*</a>' , 'Keterangan'
		);
		$this->data['dhead'] = array(
			'mapd_id', 'mkp_id', 'kondisi_id', 'ukuran', 'keterangan'
		);


        //d($jenisApd);
        //d($dataAPD);
        $pageTitle = 'Lapor '.$jenisApd['jenis_apd'];
        if (strlen($pageTitle) >= 17) {
            //$pageTitle = substr($pageTitle, 0, 10). " ... " . substr($pageTitle, -5);
            $pageTitle = substr($pageTitle, 0, 12). " ... ";
        }
        
        $this->data['picSelect'] = true;
        $this->data['pageTitle'] = $pageTitle;
        $this->data['jenisApd'] = $jenisApd;
        $this->data['dataAPD'] = $dataAPD;
        if ($noData) {
            $this->data['main_content'] = 'petugas/laporAPD';
        } else {
            $this->data['main_content'] = 'petugas/editAPD';
        }
        
		$this->load->view('petugas/includes/template', $this->data);
        //$this->load->view('petugas/editAPD', $this->data);
    }

    public function profile()
    {
        //$this->data['segmen'] = $this->uri->segment(3);
        $this->data['pageTitle'] = 'Profil'; 
        $data_to_store = '';
        //$tes = -1;
        if ($this->input->server('REQUEST_METHOD') === 'POST')
		{
            //$tes = 0;
            $segment = $this->uri->segment(3);
            $this->load->helper('date');
            if ($segment == 'profil') {
                //$this->form_validation->set_rules('nama', 'nama', 'required');
                //$this->form_validation->set_error_delimiters('<div class="alert alert-error"><a class="close" data-dismiss="alert">×</a><strong>', '</strong></div>');
                $my_time = date("Y-m-d H:i:s", now('Asia/Jakarta'));
                $data_to_store = array(
                    'no_telepon' => isZonk($this->input->post('no_telepon')),
                    'email' => isZonk($this->input->post('email')),
                    'update_date' => $my_time
                );
                //upload foto apd user
                //$this->load->library('upload');
                $upload_foto_path = 'upload/petugas/profil/';
                $upload_thumb_foto_path = 'upload/petugas/profil/thumb/';
                /*$config['upload_path']          = FCPATH.$upload_APD_path;
                $config['allowed_types']        = 'gif|jpg|png|jpeg';
                $config['max_size']             = 5000;
                $config['remove_spaces']		= TRUE;  //it will remove all spaces
                $this->upload->initialize($config);
                if( $this->upload->do_upload('foto_profil'))
                {
                    $upload_data = $this->upload->data();
                    $raw = $upload_data['raw_name'];
                    $file_type = $upload_data['file_ext'];
                    $data_to_store['photo'] = $raw.$file_type;
                }*/

                $image = $_POST['image'];
                $thumb_image = $_POST['thumb'];
                if (!empty($image)) {
                    $type = $this->_get_mimes($image);
                    $name = $this->data['nrk'].'_'.rand(0, 10000);
                    $img_name = $name.'.'.$type;
                    $data_to_store['photo'] = $img_name;
                    //file_put_contents('upload/petugas/test/'.$img_name, file_get_contents($image));
                    file_put_contents($upload_foto_path.$img_name, file_get_contents($image));
                }
                if (!empty($thumb_image)) {
                    //$type = $this->_get_mimes($thumb_image);
                    //$thumb_name = $this->data['nrk'].'_'.rand(0, 10000);
                    $thumb_name = 'thumb_'.$img_name;
                    file_put_contents($upload_thumb_foto_path.$thumb_name, file_get_contents($thumb_image));
                }

                if($this->petugas_model->updateData('users', ['id', $this->data['user_id']], $data_to_store)){
                    $this->session->set_flashdata('flash_message', 'sukses');
                }else{
                    $this->session->set_flashdata('flash_message', 'gagal');
                }
    
            } else if($segment == 'password'){
                $OldPassword = $this->input->post('password');
                //$hashOldPassword = $this->ion_auth_model->hash_password($OldPassword, $this->data['nrk']);
                $NewPassword = $this->input->post('newPassword');
                $NewPasswordConfirm = $this->input->post('confirmPassword');
                if( $this->ion_auth_model->verify_password($OldPassword, $this->data['password'], $this->data['nrk']))
                {
                    if( $NewPassword === $NewPasswordConfirm)
                    {
                        if($this->ion_auth_model->change_password($this->data['nrk'], $OldPassword, $NewPassword))
                        {
                            $this->session->set_flashdata('flash_message', 'sukses');
                        }
                    }else
                    {
                        $this->session->set_flashdata('flash_message', 'gagal');
                    }
                }
                else
                { 	
                    $this->session->set_flashdata('flash_message', 'gagal');
                }
            }
            redirect(''.$this->data['controller'].'/profile');
        }
        $this->data['data_to_store'] = $data_to_store ;
        $select = 'photo, nama, NRK, NIP, users.no_telepon, email';
        $joinTable = ['master_jabatan'];
        $this->data['userData'] = $this->_get_users($select, 2, [['users.id', $this->data['user_id']]], null, null, null, $joinTable);
        //$this->data['userData'] = $this->petugas_model->get_userData($this->data['user_id']);
        $this->data['formData'] = array(
            array(  'dhead' => 'nama',
                    'thead' => 'Nama Lengkap',
                    'type' => 'text',
                    'disabled' => 'disabled'
            ),
            array(  'dhead' => 'NRK',
                    'thead' => 'NRK/ Username',
                    'type' => 'text',
                    'disabled' => 'disabled'
            ),
            array(  'dhead' => 'NIP',
                    'thead' => 'NIP',
                    'type' => 'text',
                    'disabled' => 'disabled'
            ),
            array(  'dhead' => 'nama_jabatan',
                    'thead' => 'Jabatan',
                    'type' => 'text',
                    'disabled' => 'disabled'
            ),
            array(  'dhead' => 'no_telepon',
                    'thead' => 'No Telp',
                    'type' => 'text',
                    'disabled' => ''
            ),
            array(  'dhead' => 'nama_pos',
                    'thead' => 'Tempat Tugas',
                    'type' => 'text',
                    'disabled' => 'disabled'
            ),
            array(  'dhead' => 'email',
                    'thead' => 'e-mail',
                    'type' => 'email',
                    'disabled' => ''
            )
        );
        $this->data['passwordData'] = array(
            array(  'dhead' => 'password',
                    'thead' => 'Password Lama',
                    'placeholder' => 'Masukkan password lama'
            ),
            array(  'dhead' => 'newPassword',
                    'thead' => 'Password Baru',
                    'placeholder' => 'Masukkan password baru'
            ),
            array(  'dhead' => 'confirmPassword',
                    'thead' => 'Konfirmasi Password Baru',
                    'placeholder' => 'Masukkan kembali password baru'
        ));
        $this->data['main_content'] = 'petugas/profile';
        $this->load->view('petugas/includes/template', $this->data);
        //$this->load->view('petugas/profile', $this->data);
    }
    
    public function my_apd()
    {
        $this->data['pageTitle'] = 'Data APD';

        //$jenisApd = $this->my_apd->get_list_jenis_apd('id_mj, jenis_apd, picture', $this->data['user_roles']);
        /*$list_periode_input = $this->petugas_model->get_list_periode_input();
        foreach ($list_periode_input as $periode) {
            $where_array = [['petugas_id', $this->data['user_id']], ['periode_input', $periode['periode_input']]];
            $listAPD = $this->my_apd->get_list_apd('ukuran, foto_apd, mkp_id', 1, $where_array);
            $dataAPD[$periode['periode_input']] = $listAPD;
        }*/
        $where_array = [['petugas_id', $this->data['user_id']] ];
        $listAPD = $this->my_apd->get_list_apd('ukuran, foto_apd, mkp_id', 1, $where_array);
        //$this->data['key_array'] = array_keys($dataAPD);
        $this->data['dhead1'] = ['keberadaan', 'merk', 'ukuran', 'tahun', 'nama_kondisi', 'deskripsi'];
        $this->data['thead1'] = ['Keberadaan', 'Merk', 'Ukuran', 'Tahun', 'Kondisi', 'Status'];
        $this->data['dhead2'] = ['keberadaan', 'deskripsi'];
        $this->data['thead2'] = ['Keberadaan', 'Status'];
        $this->data['dataAPD'] = $listAPD;
        $this->data['main_content'] = 'petugas/my_apd';
		$this->load->view('petugas/includes/template', $this->data);
        //$this->load->view('petugas/lapor', $this->data);
    }

    public function list_lapor_sewaktu()
    {
        //get who is the admin
        //$subs = substr($this->data['kode_pos'], 0, 4);
        //$this->data['admin'] = $this->my_apd->get_admin($subs);
        $this->data['admin'] = 'Admin Dinas';

        $select_str = 'lapor_sewaktu.id, jenis_laporan, apd_id, create_at, lapor_sewaktu.progress';
        $where_array = [['lapor_sewaktu.petugas_id', $this->data['user_id']]];
        $this->data['list_lap_sewaktu'] = $this->my_apd->get_list_lap_sewaktu($select_str, 1, $where_array);
        $this->data['pageTitle'] = 'Lapor APD Sewaktu-waktu';
        $this->data['main_content'] = 'petugas/list_lapor_sewaktu';
		$this->load->view('petugas/includes/template', $this->data);
    }

    public function lapor_sewaktu_detail()
    {
        $id_lap = $this->uri->segment(3);
        $select_str = 'lapor_sewaktu.id, lapor_sewaktu.petugas_id, lapor_sewaktu.photo, jenis_laporan, apd_id, create_at, admin_respon, history, deskripsi_laporan';
        $where_array = [['lapor_sewaktu.id', $id_lap]];
        $detail_lap_sewaktu = $this->my_apd->get_list_lap_sewaktu($select_str, 2, $where_array);
        //authetication
        if (is_array($detail_lap_sewaktu)) {
            if ($detail_lap_sewaktu['petugas_id'] != $this->data['user_id']) {
                redirect("my404");
            }
        } else {
            redirect("my404");
        }
        $id_lap = $detail_lap_sewaktu['petugas_id'];
        $this->data['detail_lap_sewaktu'] = $detail_lap_sewaktu;
        $this->data['pageTitle'] = 'Detail Laporan';
        $this->data['main_content'] = 'petugas/lapor_sewaktu_detail';
		$this->load->view('petugas/includes/template', $this->data);
    }

    public function lapor_sewaktu()
    {
        //get who is the admin
        //$subs = substr($this->data['kode_pos'], 0, 4);
        //$admin = $this->my_apd->get_admin($subs);
        $admin = 'Admin Dinas';
        

        $this->data['pageTitle'] = 'Lapor APD Sewaktu-waktu';
        $id_lap = $this->uri->segment(3);       
        $select_str = 'lapor_sewaktu.id, lapor_sewaktu.petugas_id, lapor_sewaktu.photo, jenis_laporan, apd_id, create_at, admin_respon, history, 
                        lapor_sewaktu.progress, tgl_kej, deskripsi_laporan';
        $where_array = [['lapor_sewaktu.id', $id_lap]];
        $detail_lap_sewaktu = $this->my_apd->get_list_lap_sewaktu($select_str, 2, $where_array);
        //authetication dan cek apakah status data bisa di edit
        if (is_array($detail_lap_sewaktu)) {
            if ($detail_lap_sewaktu['petugas_id'] != $this->data['user_id']) {
                redirect("my404");
            }
            /*if ($detail_lap_sewaktu['progress'] != 99) {
                redirect("my404");
            }*/
            $noData = false;
            $id_lap = $detail_lap_sewaktu['id'];
        } else {
            $noData = true;
        }
        $this->data['noData'] = $noData;
        $this->data['detail_lap_sewaktu'] = $detail_lap_sewaktu;

        $this->load->helper('date');
        //upload foto apd user
        $this->load->library('upload');
		$upload_APD_path = 'upload/petugas/laporan_sewaktu';
		$config['upload_path']          = FCPATH.$upload_APD_path;
		$config['allowed_types']        = 'gif|jpg|png|jpeg';
		$config['max_size']             = 5000;
		$config['remove_spaces']		= TRUE;  //it will remove all spaces
        // if its editing then use rewrite mode
        if(! $noData){
            $config['overwrite']        = true;
        }
		$this->upload->initialize($config);
        $tes = 0;
        if ($this->input->server('REQUEST_METHOD') === 'POST')
		{
            // check agreement checkbox
            if ($noData) {
                //form validation
                $this->form_validation->set_rules('jenis_lap', 'jenis_lap', 'required');
                $this->form_validation->set_rules('apd', 'apd', 'required');
                $this->form_validation->set_rules('deskripsi', 'deskripsi', 'required');
                if(is_array($this->input->post('myCheckbox')))
                {
                    $aggreement = ($this->input->post('myCheckbox')[0] == 'on') ? true : false ;
                }else{
                    $aggreement = false;
                }
            } else {
                $this->form_validation->set_rules('deskripsi', 'deskripsi', 'required');
                $aggreement = true;
            }
            $this->form_validation->set_error_delimiters('<div class="alert alert-error"><a class="close" data-dismiss="alert">×</a><strong>', '</strong></div>');
            $tes = 1;
            
            //set progress status
            $progress = 1;
            $message = $this->my_apd->get_status_lap_sewaktu($progress);
            $deskripsi = $message['deskripsi'];
            $next_step = $message['next_step'];
            
            //$color = ($progress != 99) ? 'bg-success' : 'bg-danger' ;
            
			//if the form has passed through the validation
			if ($this->form_validation->run() && $aggreement)
			{
                $tes = 2;
				$my_time = date("Y-m-d H:i:s", now('Asia/Jakarta'));
                $time = sqlDate2htmlDate($my_time);
                $history_nodata = array( array(    'deskripsi' => $deskripsi.' '.$admin,
                                            'next_step' => $next_step.' '.$admin,
                                            'color' => 'bg-success',
                                            'time' => $time,
                                            'verified_by' => '',
                                            'admin_respon' => ''
                        ));
                $tgl_kej = htmlDate2sqlDate(isZonk($this->input->post('tgl_kej')));
                if ($noData) {
                    $data_to_store = array(
                        'petugas_id' => $this->data['user_id'],
                        'jenis_laporan' => isZonk($this->input->post('jenis_lap')),
                        'apd_id' => isZonk($this->input->post('apd')),
                        'tgl_kej' => $tgl_kej,
                        'deskripsi_laporan' => isZonk($this->input->post('deskripsi')),
                        'create_at' => $my_time,
                        'history' => json_encode($history_nodata),
                        'progress' => $progress,
                        'is_finished' => 0
                    );
                } else {
                    $history_wdata = array( 'deskripsi' => $deskripsi.' '.$admin,
                                            'next_step' => $next_step.' '.$admin,
                                            'color' => 'bg-danger',
                                            'time' => $time,
                                            'verified_by' => '',
                                            'admin_respon' => ''
                                );
                    $saved_history = json_decode($detail_lap_sewaktu['history'], true);
                    $saved_history[] = $history_wdata;
                    $data_to_store = array(
                        'tgl_kej' => $tgl_kej,
                        'deskripsi_laporan' => isZonk($this->input->post('deskripsi')),
                        'update_at' => $my_time,
                        'history' => json_encode($saved_history),
                        'progress' => $progress
                    );
                }
                
				//set upload denah gedung
				if( $this->upload->do_upload('foto_lap'))
				{
					$upload_data = $this->upload->data();
					$raw = $upload_data['raw_name'];
					$file_type = $upload_data['file_ext'];
					$data_to_store['photo'] = $raw.$file_type;
                    $this->resizeImage($data_to_store['photo'], $upload_APD_path, false);  // resize image
                    $this->resizeImage($data_to_store['photo'], $upload_APD_path, true);  //create thumb
				}
                // check wheather its new data or editing data
                if ($noData) {
                    //if the insert has returned true then we show the flash message
                    if($this->petugas_model->insertData('lapor_sewaktu', $data_to_store)){
                        $this->session->set_flashdata('flash_message', 'sukses');
                    }else{
                        $this->session->set_flashdata('flash_message', 'gagal');
                    }
                } else {
                    if($this->petugas_model->updateData('lapor_sewaktu', ['id', $id_lap], $data_to_store)){
                        $this->session->set_flashdata('flash_message', 'sukses');
                    }else{
                        $this->session->set_flashdata('flash_message', 'gagal');
                    }
                }

				//redirect('Prainspeksi_gedung/update/'.$id.'');
				redirect(''.$this->data['controller'].'/list_lapor_sewaktu');
                
			}//validation run
        }

        $listAPDRusak = null;
        $listAPDHilang = null;
        $where_array1 = [['petugas_id', $this->data['user_id']], ['periode_input', $this->data['periode']], ['mkp_id', 1], ['master_kondisi.wearable', 1], ['progress', 3]];
        $listAPD1 = $this->my_apd->get_list_apd('apd.id, ukuran, foto_apd, mkp_id', 1, $where_array1);
        if (count($listAPD1)>0) {
            $i = 0;
            foreach ($listAPD1 as $apd) {
                //cek apakah apd tsb sedang proses pengajuan
                $lap_sewaktu = $this->petugas_model->get('id', 'lapor_sewaktu', [['apd_id',$apd['id']], ['is_finished', 0]], null, 1);
                if (count($lap_sewaktu) < 1) {
                    $listAPDRusak[$i] = ['val' => $apd['id'], 'text' => $apd['jenis_apd'].', '.$apd['merk'].', '.$apd['tahun']];
                    $i++;
                }
            }
        } else {
            $listAPDRusak = null;
        }
        $listAPDRusak = json_encode($listAPDRusak);
        
        $where_array2 = [['petugas_id', $this->data['user_id']], ['periode_input', $this->data['periode']], ['mkp_id', 1], ['progress', 3]];
        $listAPD2 = $this->my_apd->get_list_apd('apd.id, ukuran, foto_apd, mkp_id', 1, $where_array2);
        if (count($listAPD2)>0) {
            $i = 0;
            foreach ($listAPD2 as $apd) {
                //cek apakah apd tsb sedang proses pengajuan
                $lap_sewaktu = $this->petugas_model->get('id', 'lapor_sewaktu', [['apd_id',$apd['id']], ['is_finished', 0]], null, 1);
                if (count($lap_sewaktu) < 1) {
                    $listAPDHilang[$i] = ['val' => $apd['id'], 'text' => $apd['jenis_apd'].', '.$apd['merk'].', '.$apd['tahun']];
                    $i++;
                }
            }
        } else {
            $listAPDHilang = null;
        }
        $listAPDHilang = json_encode($listAPDHilang);
        $this->data['tes'] = $tes ;
        $this->data['listAPDRusak'] = $listAPDRusak;
        $this->data['listAPDHilang'] = $listAPDHilang;
        if ($noData) {
            $this->data['main_content'] = 'petugas/lapor_sewaktu';
        } else {
            $this->data['main_content'] = 'petugas/edit_sewaktu';
        }
        //$this->data['main_content'] = 'petugas/lapor_sewaktu';
		$this->load->view('petugas/includes/template', $this->data);
        //$this->load->view('petugas/lapor_sewaktu', $this->data);
    }

    /* todo : tampilkan detail input per orang 
    */
    public function monitoring()
    {
        $this->data['section_tittle'] = 'Monitoring Progress Input data APD';
        //authentication
        $this->authenticate();
        $search = null;
        if ($this->input->server('REQUEST_METHOD') === 'GET')
		{
            if (! empty($this->input->get('cari'))) {
                $search = $this->input->get('cari'); 
            }
        }

        $jab_id_arr = $this->config->item('jabID_list_monitoring');
        foreach ($jab_id_arr as $jab_id) {
            $or_where_arr[] = ['jabatan_id', $jab_id];
        }
        $select = 'users.id, nama, NRK, NIP, photo, users.no_telepon, email, persen_inputAPD, persen_APDterverif, users.jml_ditolak';
        $join = ['master_status', 'master_jabatan', 'master_controller'];

        //cek apakah user kasi bidang penyelamat
        if ($this->data['jab_id'] == 20 || $this->data['jab_id'] == 21 || $this->data['jab_id'] == 22) {
            $kode = 'kode_pos';
        } else {
            $kode = 'kode_sektor';
        }
        if (is_null($search)) {
            $list_bawahan = $this->_get_users($select, 1, [[$kode, $this->data['kode_pos']]], null, $or_where_arr, null, $join, ['master_controller.level', 'DESC']);
            $this->data['section_tittle'] = 'Menampilkan '.count($list_bawahan).' data petugas';
        } else {
            $or_like = [['users.nama', $search], ['users.NRK', $search], ['master_jabatan.nama_jabatan', $search], ['master_pos.nama_pos', $search] ];
            $list_bawahan = $this->_get_users($select, 1, [[$kode, $this->data['kode_pos']]], null, $or_where_arr, $or_like, $join, ['master_controller.level', 'DESC']);
            $this->data['section_tittle'] = 'Hasil Pencarian kata "'.$search.'" ditemukan '.count($list_bawahan).' data';
            }
        
        //$select_str = 'lapor_sewaktu.id, jenis_laporan, apd_id, create_at, lapor_sewaktu.progress';
        //$where_array = [['lapor_sewaktu.petugas_id', $this->data['user_id']]];
        $this->data['search'] = $search;
        $this->data['list_bawahan'] = $list_bawahan;
        $this->data['pageTitle'] = 'Monitoring Anggota';
        $this->data['main_content'] = 'petugas/monitoring';
		$this->load->view('petugas/includes/template', $this->data);
        //$this->load->view('petugas/monitoring', $this->data);
    }

    public function verifikasi()
    {
        $this->authenticate();
        //$perInput = $this->admin_model->get('periode_input', 'master_state', 2, [['tipe', 'input']]);
        $search = null;
        if ($this->input->server('REQUEST_METHOD') === 'GET')
		{
            if (! empty($this->input->get('cari'))) {
                $search = $this->input->get('cari'); 
            }
        }

        $jab_id_arr = $this->config->item('jabID_list_monitoring');
        foreach ($jab_id_arr as $jab_id) {
            $or_where_arr[] = ['jabatan_id', $jab_id];
        }
        /*$joinArr = [['users', 'master_pos', 'master_pos.nama_pos', 'kode_pos', 'kode_pos' ], ['users', 'master_status', 'master_status.status', 'status_id', 'id_stat' ], 
        ['users', 'master_jabatan', 'master_jabatan.nama_jabatan', 'jabatan_id', 'id_mj' ] ];*/
        $join = ['master_status', 'master_jabatan', 'master_controller'];
        $order = ['master_controller.level', 'DESC'];
        //$listUser = $this->_get_users('users.id, nama, NRK, NIP, photo', 1, [['jml_tobe_verified >', 0]], [['kode_pos', $this->data['kode_pos'], 'after']], $or_where_arr, null, $join, $order);
        
        //cek apakah user kasi bidang penyelamat
        if ($this->data['jab_id'] == 20 || $this->data['jab_id'] == 21 || $this->data['jab_id'] == 22) {
            $kode = 'kode_pos';
        } else {
            $kode = 'kode_sektor';
        }
        if (is_null($search)) {
            $listUser = $this->_get_users('users.id, nama, NRK, NIP, photo', 1, [['jml_tobe_verified >', 0], [ $kode, $this->data['kode_pos']]], null, $or_where_arr, null, $join, $order);
        } else {
            $or_likeArr = [['users.nama', $search], ['users.NRK', $search], ['master_jabatan.nama_jabatan', $search], ['master_pos.nama_pos', $search] ];
            $listUser = $this->_get_users('users.id, nama, NRK, NIP, photo', 1, [['jml_tobe_verified >', 0], [ $kode, $this->data['kode_pos']]], null, $or_where_arr, $or_likeArr, $join, $order);
        }
        $this->data['search'] = $search;
        /*$listUser = $this->admin_model->get('id, nama, NRK, NIP, photo', 'users', 1, [['active', 1]], [['users.kode_pos', $this->data['kode_pos'], 'after']], $joinArr,
                                            null, null, $or_where_arr);*/
        /*$ApdUser = [];
        $i = 0;
        foreach ($listUser as $user) {
            $numAPD = $this->admin_model->get('id', 'apd', 3, [['petugas_id', $user['id']], ['progress', 2], ['mj_id !=', 0], ['periode_input', $perInput['periode_input'] ] ]);
            //$numAPD = count($listAPD);
            //$jumJenisApd = $this->admin_model->get('id_mj', 'master_jenis_apd', 3 );
            if($numAPD > 0){
                $ApdUser[$i]=  $user;
                //$ApdUser[$i]['jmlAPDdiinput'] = $numAPD;
                //$ApdUser[$i]['jmlJenisAPD'] = $jumJenisApd;
                $i++;
            }
        }*/
        /*$listUser = $this->admin_model->get('id, nama, kode_pos', 'users', 1, [['active', 1]] );
        $temp = [];
        foreach ($listUser as $user) {
            $listKodePos = $this->admin_model->get('id_mp, kode_pos', 'master_pos', 1, [['deleted', 0], ['kode_pos', $user['kode_pos'] ]]);
            if(count($listKodePos) == 0){
                $temp[]=  $user;
            }
        }*/
        //d($ApdUser);
        if (is_null($search)) {
            $this->data['section_tittle'] = 'Menampilkan '.count($listUser).' data petugas';
        } else {
            $this->data['section_tittle'] = 'Hasil Pencarian kata "'.$search.'" ditemukan '.count($listUser).' data';
        }

        $this->data['ApdUser'] = $listUser;
        $this->data['pageTitle'] = 'Verifkasi & Validasi';
        //$this->data['main_content'] = 'petugas/verifikasi_kasektor';
        $this->data['main_content'] = 'petugas/list_verifikasi_kasektor';
		$this->load->view('petugas/includes/template', $this->data);
    }

    public function verifikasiAPD()
    {
        $this->authenticate();
        $this->load->helper('date');
        $this->data['pageTitle'] = 'Verifkasi & Validasi';
        $this->load->library('session');

        //$perInput = $this->admin_model->get('periode_input', 'master_state', 2, [['tipe', 'input']]);
        $UserID = $this->uri->segment(3);
        //cek idUser
        /*$joinArr = [['users', 'master_pos', 'master_pos.nama_pos', 'kode_pos', 'kode_pos' ], ['users', 'master_status', 'master_status.status', 'status_id', 'id_stat' ], 
                    ['users', 'master_jabatan', 'master_jabatan.nama_jabatan', 'jabatan_id', 'id_mj' ] ];*/
        $join = ['master_status', 'master_jabatan'];
        $userData = $this->_get_users('id, nama, NRK, NIP, master_pos.kode_pos', 2, [['id', $UserID]], null, null, null, $join);
        //$userData = $this->admin_model->get('id, nama, NRK, NIP, users.kode_pos', 'users', 2, [['active', 1], ['id', $UserID]], null,  $joinArr);
        if (is_array($userData)) {
            if( (strpos($userData['kode_pos'], $this->data['kode_pos'])) === false || $userData['NRK'] == $this->data['nrk']){
                redirect("my404");
            }
        }
        $UserID = $userData['id'];

        if ($this->input->server('REQUEST_METHOD') === 'POST')
  		{
            $this->form_validation->set_rules('apd_id', 'apd_id', 'required');
            $this->form_validation->set_rules('verifikasi', 'verifikasi', 'required');
            if ( $this->form_validation->run() )
            {
                $apd_id = $this->input->post('apd_id');
                $progress = ($this->input->post('verifikasi') == 1) ? 3 : 1 ;
                $my_time = date("Y-m-d H:i:s", now('Asia/Jakarta'));
                $data = array('admin_message' => $this->input->post('pesan'),
                                'progress' => $progress,
                                'id_pemverifikasi' => $this->data['user_id'],
                                'verified_at' => $my_time
                            );
                if ($progress == 1) {
                    $data['is_read'] = 0;
                }
                if($this->admin_model->updateData('apd', ['id', $apd_id], $data))
                {
                    $this->session->set_flashdata('flash_message', 'sukses');
                    $update_rekap = true;
                }else{
                    $this->session->set_flashdata('flash_message', 'gagal');
                    $update_rekap = false;
                }
                
                //update data users.persen input apd
                if ($update_rekap) {
                    $this->_update_user_rekap($UserID);
                }
                /*$jml_belum_verif = $this->admin_model->get('id', 'apd', 3, [['petugas_id', $UserID], ['progress', 2], ['apd.mj_id !=', 0], ['periode_input', $perInput['periode_input'] ] ]);
                $jml_terverif = $this->admin_model->get('id', 'apd', 3, [['petugas_id', $UserID], ['progress', 3], ['apd.mj_id !=', 0], ['periode_input', $perInput['periode_input'] ] ]);
                $jml_ditolak = $this->admin_model->get('id', 'apd', 3, [['petugas_id', $UserID], ['progress', 1], ['apd.mj_id !=', 0], ['periode_input', $perInput['periode_input'] ] ]);
                $persen_input = round( (($jml_belum_verif+$jml_terverif+$jml_ditolak)/$this->_get_jml_jenis_apd())*100, 1);
                $persen_tervalidasi = round( ($jml_terverif/$this->_get_jml_jenis_apd())*100, 1);
                $data_user = array( 'persen_inputAPD' => $persen_input,
                                    'persen_APDterverif' => $persen_tervalidasi,
                                    'jml_ditolak' => $jml_ditolak
                                );
                $this->admin_model->updateData('users', ['id', $UserID], $data_user);*/

                redirect('eselon_4/verifikasiAPD/'.$UserID);
            }
        }
        
        $listAPD = $this->admin_model->get('id, mkp_id, ukuran, foto_apd, no_urut, admin_message, apd.keterangan as keterangan_p, created_at, updated_at', 'apd', 1, 
                    [['petugas_id', $UserID], ['progress', 2], ['apd.mj_id !=', 0], ['periode_input', $this->data['periode'] ] ], null, 
                    [['apd', 'master_jenis_apd', 'master_jenis_apd.jenis_apd', 'mj_id', 'id_mj' ], ['apd', 'master_apd', 'master_apd.tahun, master_apd.no_seri', 'mapd_id', 'id_ma' ], 
                    ['master_apd', 'master_merk', 'master_merk.merk', 'mm_id', 'id_mm' ], ['apd', 'master_keberadaan', 'master_keberadaan.keberadaan', 'mkp_id', 'id_mkp' ], 
                    ['apd', 'master_kondisi', 'master_kondisi.nama_kondisi, master_kondisi.keterangan', 'kondisi_id', 'id_mk' ]]);
        //$post = $this->input->post();
        //d($userData,$listAPD, $post);
        $joinArr2 = [['users', 'master_jabatan', 'master_jabatan.mc_id', 'jabatan_id', 'id_mj' ], 
                        ['master_jabatan', 'master_controller', 'master_controller.role_id', 'mc_id', 'id' ] ];
        $role_id_arr = $this->admin_model->get('users.id', 'users', 2, [['users.id', $UserID]], null, $joinArr2);
        $user_role_id = $role_id_arr['role_id'];

        $this->data['UserID'] = $UserID;
        $this->data['jumJenisApd'] = $this->_get_jml_jenis_apd(null, $user_role_id);
        $this->data['jumApdTerverifikasi'] = $this->admin_model->get('id', 'apd', 3,  [['petugas_id', $UserID], ['progress', 3], ['mj_id !=', 0]]);
        $this->data['jumApdDitolak'] = $this->admin_model->get('id', 'apd', 3,  [['petugas_id', $UserID], ['progress', 1], ['mj_id !=', 0]]);
        $this->data['userData'] = $userData;
        $this->data['listAPD'] = $listAPD;
        $this->data['dhead_ada'] = array(['Keberadaan', 'keberadaan'], ['Merk', 'merk'], ['Ukuran', 'ukuran'], ['Tahun', 'tahun'], ['Kondisi', 'nama_kondisi'], 
                                        ['Keterangan Kondisi', 'keterangan'], ['Keterangan Petugas', 'keterangan_p'] );
        $this->data['dhead_seri'] = array(['Keberadaan', 'keberadaan'], ['Merk', 'merk'], ['Ukuran', 'ukuran'], ['Tahun', 'tahun'], ['No Urut', 'no_urut'], ['Kondisi', 'nama_kondisi'], 
                                        ['Keterangan Kondisi', 'keterangan'], ['Keterangan Petugas', 'keterangan_p'] );
        $this->data['dhead_hilang'] = array(['keberadaan', 'keberadaan'], ['Tanggal Input', 'created_at'] );
        $this->data['active'] = array( '', 'active', 'active', '', '', '', '');
        $this->data['main_content'] = 'petugas/verifikasiAPD_kasektor';
		$this->load->view('petugas/includes/template', $this->data);
    }

    public function tervalidasi()
    {
        $this->authenticate();
        //$perInput = $this->admin_model->get('periode_input', 'master_state', 2, [['tipe', 'input']]);

        $search = null;
        if ($this->input->server('REQUEST_METHOD') === 'GET')
		{
            if (! empty($this->input->get('cari'))) {
                $search = $this->input->get('cari'); 
            }
        }
        $jab_id_arr = $this->config->item('jabID_list_monitoring');
        foreach ($jab_id_arr as $jab_id) {
            $or_where_arr[] = ['jabatan_id', $jab_id];
        }
        /*$joinArr = [['users', 'master_pos', 'master_pos.nama_pos', 'kode_pos', 'kode_pos' ], ['users', 'master_status', 'master_status.status', 'status_id', 'id_stat' ], 
        ['users', 'master_jabatan', 'master_jabatan.nama_jabatan', 'jabatan_id', 'id_mj' ], ['master_jabatan', 'master_controller', 'master_controller.level', 'mc_id', 'id' ] ];*/
        $joinTable = ['master_status', 'master_jabatan', 'master_controller'];
        $order = ['master_controller.level', 'DESC'];

        //cek apakah user kasi bidang penyelamat
        if ($this->data['jab_id'] == 20 || $this->data['jab_id'] == 21 || $this->data['jab_id'] == 22) {
            $kode = 'kode_pos';
        } else {
            $kode = 'kode_sektor';
        }
        if (is_null($search)) {
            $listUser = $this->_get_users('users.id, nama, NRK, NIP, photo', 1, [['persen_APDterverif >', 0], [$kode, $this->data['kode_pos']]], null, $or_where_arr, null, $joinTable, $order);
        } else {
            $or_likeArr = [['users.nama', $search], ['users.NRK', $search], ['master_jabatan.nama_jabatan', $search], ['master_pos.nama_pos', $search] ];
            $listUser = $this->_get_users('users.id, nama, NRK, NIP, photo', 1, [['persen_APDterverif >', 0], [$kode, $this->data['kode_pos']]], null, $or_where_arr, $or_likeArr, $joinTable, $order);
        }
        $this->data['search'] = $search;

        /*$ApdUser = [];
        foreach ($listUser as $user) {
            $listAPD = $this->admin_model->get('id', 'apd', 3, [['petugas_id', $user['id']], ['progress', 3], ['periode_input', $perInput['periode_input'] ] ]);
            if($listAPD > 0){
                $ApdUser[]=  $user;
            }
        }*/
        if (is_null($search)) {
            $this->data['section_tittle'] = 'Menampilkan '.count($listUser).' data petugas';
        } else {
            $this->data['section_tittle'] = 'Hasil Pencarian kata "'.$search.'" ditemukan '.count($listUser).' data';
        }
        
        $this->data['ApdUser'] = $listUser;
        //$this->data['datatable'] = true;
        $this->data['pageTitle'] = 'Daftar Terverifikasi';
        $this->data['main_content'] = 'petugas/list_tervalidasi_kasektor';
		$this->load->view('petugas/includes/template', $this->data);
        //$this->load->view('admin_sektor/verifikasi', $this->data);
    }

    public function APDtervalidasi()
    {
        $this->authenticate();
        $this->data['pageTitle'] = 'APD Terverifikasi';
        $this->load->library('session');
        $this->load->helper('date');
        $perInput = $this->admin_model->get('periode_input', 'master_state', 2, [['tipe', 'input']]);
        $UserID = $this->uri->segment(3);
        //cek idUser
        $joinTable = ['master_status', 'master_jabatan'];
        $userData = $this->_get_users('users.id, nama, NRK, NIP, master_pos.kode_pos', 2, [['users.id', $UserID]], null, null, null, $joinTable);
        //$userData = $this->admin_model->get('id, nama, NRK, NIP, users.kode_pos', 'users', 2, [['active', 1], ['id', $UserID]], null, [['users', 'master_pos', 'master_pos.nama_pos', 'kode_pos', 'kode_pos' ], ['users', 'master_status', 'master_status.status', 'status_id', 'id_stat' ], ['users', 'master_jabatan', 'master_jabatan.nama_jabatan', 'jabatan_id', 'id_mj' ] ] );
        if (is_array($userData)) {
            if( (strpos($userData['kode_pos'], $this->data['kode_pos'])) === false || $userData['NRK'] == $this->data['nrk']){
                redirect("my404");
            }
        }
        $UserID = $userData['id'];
        $listAPD = $this->admin_model->get('id, mkp_id, ukuran, foto_apd, admin_message, apd.keterangan as keterangan_petugas, created_at, updated_at', 'apd', 1, 
                                        [['petugas_id', $UserID], ['progress', 3], ['apd.mj_id !=', 0], ['periode_input', $this->data['periode'] ] ], null, 
                                        [['apd', 'master_jenis_apd', 'master_jenis_apd.jenis_apd', 'mj_id', 'id_mj' ], ['apd', 'master_apd', 'master_apd.tahun', 'mapd_id', 'id_ma' ], 
                                        ['master_apd', 'master_merk', 'master_merk.merk', 'mm_id', 'id_mm' ], 
                                        ['apd', 'master_keberadaan', 'master_keberadaan.keberadaan', 'mkp_id', 'id_mkp' ], 
                                        ['apd', 'master_kondisi', 'master_kondisi.nama_kondisi, master_kondisi.keterangan', 'kondisi_id', 'id_mk' ]]);
        //d($userData,$listAPD, $post);
        $joinArr2 = [['users', 'master_jabatan', 'master_jabatan.mc_id', 'jabatan_id', 'id_mj' ], 
                        ['master_jabatan', 'master_controller', 'master_controller.role_id', 'mc_id', 'id' ] ];
        $role_id_arr = $this->admin_model->get('users.id', 'users', 2, [['users.id', $UserID]], null, $joinArr2);
        $user_role_id = $role_id_arr['role_id'];

        $this->data['icon'] = ['checkmark', 'success'];
        $this->data['UserID'] = $UserID;
        $this->data['jumJenisApd'] = $this->_get_jml_jenis_apd(null, $user_role_id);
        $this->data['jumInputApd'] = $this->admin_model->get('id', 'apd', 3,  [['petugas_id', $UserID], ['progress', 2], ['mj_id !=', 0]]);
        $this->data['jumApdTerverifikasi'] = $this->admin_model->get('id', 'apd', 3,  [['petugas_id', $UserID], ['progress', 3], ['mj_id !=', 0]]);
        $this->data['jumApdDitolak'] = $this->admin_model->get('id', 'apd', 3,  [['petugas_id', $UserID], ['progress', 1], ['mj_id !=', 0]]);
        $this->data['userData'] = $userData;
        $this->data['listAPD'] = $listAPD;
        $this->data['dhead_ada'] = array(['Keberadaan', 'keberadaan'], ['Merk', 'merk'], ['Ukuran', 'ukuran'], ['Tahun', 'tahun'], ['Kondisi', 'nama_kondisi'], ['Keterangan Kondisi', 'keterangan'], ['Keterangan Petugas', 'keterangan_petugas'], ['Pesan Admin', 'admin_message'] );
        $this->data['dhead_hilang'] = array(['keberadaan', 'keberadaan'], ['Tanggal Input', 'created_at'] );
        $this->data['main_content'] = 'petugas/apd_tervalidasi_kasektor';
		$this->load->view('petugas/includes/template', $this->data);
    }

    public function ditolak()
    {
        $this->authenticate();
        $this->data['pageTitle'] = 'Daftar APD Tertolak';
        //$perInput = $this->admin_model->get('periode_input', 'master_state', 2, [['tipe', 'input']]);

        $search = null;
        if ($this->input->server('REQUEST_METHOD') === 'GET')
		{
            if (! empty($this->input->get('cari'))) {
                $search = $this->input->get('cari'); 
            }
        }
        $jab_id_arr = $this->config->item('jabID_list_monitoring');
        foreach ($jab_id_arr as $jab_id) {
            $or_where_arr[] = ['jabatan_id', $jab_id];
        }
        $joinArr = ['master_status', 'master_jabatan', 'master_controller'];
        $order = ['master_controller.level', 'DESC'];
        $select = 'users.id, nama, NRK, NIP, photo';

        //cek apakah user kasi bidang penyelamat
        if ($this->data['jab_id'] == 20 || $this->data['jab_id'] == 21 || $this->data['jab_id'] == 22) {
            $kode = 'kode_pos';
        } else {
            $kode = 'kode_sektor';
        }
        if (is_null($search)) {
            $listUser = $this->_get_users($select, 1, [['users.jml_ditolak >', 0], [$kode, $this->data['kode_pos']]], null, $or_where_arr, null, $joinArr, $order);
            /*$listUser = $this->admin_model->get($select, 'users', 1, [['active', 1]], [['users.kode_pos', $this->data['kode_pos'], 'after']], $joinArr,
                                            $order, null, $or_where_arr);*/
        } else {
            $or_likeArr = [['users.nama', $search], ['users.NRK', $search], ['master_jabatan.nama_jabatan', $search], ['master_pos.nama_pos', $search] ];
            $listUser = $this->_get_users($select, 1, [['users.jml_ditolak >', 0], [$kode, $this->data['kode_pos']]], null, $or_where_arr, $or_likeArr, $joinArr, $order);
            /*$listUser = $this->admin_model->get('users.id, nama, NRK, NIP, photo', 'users', 1, [['active', 1]], [['users.kode_pos', $this->data['kode_pos'], 'after']], $joinArr,
                                            $order, null, $or_where_arr, $or_likeArr);*/
        }
        $this->data['search'] = $search;

        /*$ApdUser = [];
        foreach ($listUser as $user) {
            $listAPD = $this->admin_model->get('id', 'apd', 3, [['petugas_id', $user['id']], ['progress', 1], ['periode_input', $perInput['periode_input'] ] ]);
            if($listAPD > 0){
                $ApdUser[]=  $user;
            }
        }*/
        if (is_null($search)) {
            $this->data['section_tittle'] = 'Menampilkan '.count($listUser).' data petugas';
        } else {
            $this->data['section_tittle'] = 'Hasil Pencarian kata "'.$search.'" ditemukan '.count($listUser).' data';
        }
        //d($listUser);
        $this->data['ApdUser'] = $listUser;
        $this->data['title'] = array( 'APD Tertolak', 'Daftar APD Pegawai yang ditolak Laporan nya', 'danger');
        $this->data['main_content'] = 'petugas/list_tertolak_kasektor';
		$this->load->view('petugas/includes/template', $this->data);
        //$this->load->view('admin_sektor/verifikasi', $this->data);
    }

    public function APDtertolak()
    {
        $this->authenticate();
        $this->data['pageTitle'] = 'APD Tertolak';
        $this->load->library('session');
        $this->load->helper('date');
        $perInput = $this->admin_model->get('periode_input', 'master_state', 2, [['tipe', 'input']]);
        $UserID = $this->uri->segment(3);
        //cek idUser
        $join = ['master_status', 'master_jabatan'];
        $userData = $this->_get_users('id, nama, NRK, NIP, master_pos.kode_pos', 2, [['users.id', $UserID]], null, null, null, $join);
        //$userData = $this->admin_model->get('id, nama, NRK, NIP, users.kode_pos', 'users', 2, [['active', 1], ['id', $UserID]], null, [['users', 'master_pos', 'master_pos.nama_pos', 'kode_pos', 'kode_pos' ], ['users', 'master_status', 'master_status.status', 'status_id', 'id_stat' ], ['users', 'master_jabatan', 'master_jabatan.nama_jabatan', 'jabatan_id', 'id_mj' ] ] );
        if (is_array($userData)) {
            if( (strpos($userData['kode_pos'], $this->data['kode_pos'])) === false || $userData['NRK'] == $this->data['nrk']){
                redirect("my404");
            }
        }
        $UserID = $userData['id'];
        $listAPD = $this->admin_model->get('id, mkp_id, ukuran, foto_apd, admin_message, apd.keterangan as keterangan_petugas, created_at, updated_at', 'apd', 1, [['petugas_id', $UserID], ['progress', 1], ['apd.mj_id !=', 0], ['periode_input', $this->data['periode'] ] ], null, [['apd', 'master_jenis_apd', 'master_jenis_apd.jenis_apd', 'mj_id', 'id_mj' ], ['apd', 'master_apd', 'master_apd.tahun', 'mapd_id', 'id_ma' ], ['master_apd', 'master_merk', 'master_merk.merk', 'mm_id', 'id_mm' ], ['apd', 'master_keberadaan', 'master_keberadaan.keberadaan', 'mkp_id', 'id_mkp' ], ['apd', 'master_kondisi', 'master_kondisi.nama_kondisi, master_kondisi.keterangan', 'kondisi_id', 'id_mk' ]]);
        //d($userData,$listAPD, $post);
        $joinArr2 = [['users', 'master_jabatan', 'master_jabatan.mc_id', 'jabatan_id', 'id_mj' ], 
                        ['master_jabatan', 'master_controller', 'master_controller.role_id', 'mc_id', 'id' ] ];
        $role_id_arr = $this->admin_model->get('users.id', 'users', 2, [['users.id', $UserID]], null, $joinArr2);
        $user_role_id = $role_id_arr['role_id'];

        $this->data['title'] = 'Daftar APD Tertolak';
        $this->data['icon'] = ['close', 'danger'];
        $this->data['UserID'] = $UserID;
        $this->data['jumJenisApd'] = $this->_get_jml_jenis_apd(null, $user_role_id);
        $this->data['jumInputApd'] = $this->admin_model->get('id', 'apd', 3,  [['petugas_id', $UserID], ['progress', 2], ['mj_id !=', 0]]);
        $this->data['jumApdTerverifikasi'] = $this->admin_model->get('id', 'apd', 3,  [['petugas_id', $UserID], ['progress', 3], ['mj_id !=', 0]]);
        $this->data['jumApdDitolak'] = $this->admin_model->get('id', 'apd', 3,  [['petugas_id', $UserID], ['progress', 1], ['mj_id !=', 0]]);
        $this->data['userData'] = $userData;
        $this->data['listAPD'] = $listAPD;
        $this->data['dhead_ada'] = array(['Keberadaan', 'keberadaan'], ['Merk', 'merk'], ['Ukuran', 'ukuran'], ['Tahun', 'tahun'], ['Kondisi', 'nama_kondisi'], ['Keterangan Kondisi', 'keterangan'], ['Keterangan Petugas', 'keterangan_petugas'], ['Pesan Admin', 'admin_message'] );
        $this->data['dhead_hilang'] = array(['keberadaan', 'keberadaan'], ['Tanggal Input', 'created_at'] );
        $this->data['main_content'] = 'petugas/apd_tertolak_kasektor';
		$this->load->view('petugas/includes/template', $this->data);
    }

    public function lap_sewaktu()
    {
        $this->authenticate();
        $this->data['pageTitle'] = 'Verifikasi Laporan Sewaktu';
        $this->data['main_content'] = 'petugas/list_lap_sewaktu_kasektor';
		$this->load->view('petugas/includes/template', $this->data);
    }

    private function _calculate_rekap_APD()
    {
        $jenisApd = $this->_get_list_jenis_apd('id_mj, jenis_apd, akronim');
        //$jenisApd = $this->my_apd->get_list_jenis_apd('id_mj, jenis_apd, akronim', $this->data['user_roles'], 1);
        $list_jab_id_bawahan = $this->config->item('jabID_list_monitoring');
        //$list_jab_id_bawahan[] = $this->data['jab_id'];
        foreach ($jenisApd as $apd) {
            $akronim[] = $apd['akronim'];
            $jenis_apd[] = $apd['jenis_apd'];
            $temp_belum = $this->my_apd->get_report($apd['id_mj'], 'belum', null, $this->data['periode'], null, $this->data['kode_pos'], $list_jab_id_bawahan);
            $temp_hilang = $this->my_apd->get_report($apd['id_mj'], 'hilang', null, $this->data['periode'], null, $this->data['kode_pos'], $list_jab_id_bawahan);
            $temp_baik = $this->my_apd->get_report($apd['id_mj'], null, 4, $this->data['periode'], null, $this->data['kode_pos'], $list_jab_id_bawahan);
            $temp_rr = $this->my_apd->get_report($apd['id_mj'], null, 3, $this->data['periode'], null, $this->data['kode_pos'], $list_jab_id_bawahan);
            $temp_rs = $this->my_apd->get_report($apd['id_mj'], null, 2, $this->data['periode'], null, $this->data['kode_pos'], $list_jab_id_bawahan);
            $temp_rb = $this->my_apd->get_report($apd['id_mj'], null, 1, $this->data['periode'], null, $this->data['kode_pos'], $list_jab_id_bawahan);
            $belum[] = $temp_belum;
            $hilang[] = $temp_hilang;
            $stotk[] = $temp_belum+$temp_hilang;
            $baik[] = $temp_baik;
            $rr[] = $temp_rr;
            $rs[] = $temp_rs;
            $rb[] = $temp_rb;
            $stote[] = $temp_baik+$temp_rr+$temp_rs+$temp_rb;
            $stot[] = $temp_belum+$temp_hilang+$temp_baik+$temp_rr+$temp_rs+$temp_rb;
        }
        
        $data = array('title' => 'Chart rekap data APD', 'group' => $this->data['penempatan']['nama_pos'], 
                                'akronim' => $akronim, 'jenis_apd' => $jenis_apd, 'belum' => $belum, 'hilang' => $hilang, 'baik' => $baik, 
                                'rr' => $rr, 'rs' => $rs, 'rb' => $rb, 'margin' => 250, 'stotk' => $stotk, 'stote' => $stote, 'stot' => $stot);
        $result = ['jmlJenisAPD' => count($jenisApd), 'jmlAPDTervld' => array_sum($stot), 'data' => $data];
        return $result;
    }

    public function report_rekap_APD()
    {
        $this->data['section_tittle'] = 'Laporan data APD';
        //authentication
        $this->authenticate();
        // ['tipe' => '', 'belum' => '', 'hilang' => '', 'b' => '', 'rr' => '', 'rs' => '', 'rb' => '']
        //$this->data['highchart'] = true;
        $rekapAPD = $this->_calculate_rekap_APD();
        $this->data['result'] = $rekapAPD['data'];
        $jab_id_arr = $this->config->item('jabID_list_monitoring');
        foreach ($jab_id_arr as $jab_id) {
            $or_where_arr[] = ['jabatan_id', $jab_id];
        }
        //$or_where_arr[] = ['jabatan_id', $this->data['jab_id']];
        $jmlPrsnl = $this->_get_users('id', 3, [['kode_sektor', $this->data['kode_pos']]], null, $or_where_arr);
        //$jmlPrsnl = $this->admin_model->get('id', 'users', 3, [['active', 1]], [['kode_pos', $this->data['kode_pos'], 'after']], null, null, null, $or_where_arr );
        $this->data['jmlAPDTervld'] = $rekapAPD['jmlAPDTervld'];
        $this->data['jmltotalAPD'] = $jmlPrsnl*$rekapAPD['jmlJenisAPD'];
        $this->data['Persentase'] = round($this->data['jmlAPDTervld']/$this->data['jmltotalAPD']*100,1);
        $this->data['pageTitle'] = 'Laporan data APD';
        $this->data['main_content'] = 'petugas/report_rekap_APD';
		$this->load->view('petugas/includes/template', $this->data);
    }

    public function list_pdf()
    {
        //authentication
        $this->authenticate();
        //$this->load->helper('date');
        //$my_time = date("Y-m-d H:i:s", now('Asia/Jakarta'));
        $tgl = $this->admin_model->get('tgl_update', 'master_sektor', 2, [['kode', $this->data['kode_pos'] ]]);
        $periode = str_replace(' ', '', $this->data['periode']);        //remove space if exist
        $report_type = ['Rekap APD', 'KIB APD'];
        $file_name1 = 'rekap-apd_'.$this->data['kode_pos'].'_'.$periode.'.pdf';
        $file_name2 = 'kib-apd_'.$this->data['kode_pos'].'_'.$periode.'.pdf';
        $file_names = [$file_name1, $file_name2];
        //$file_name2 = 'data-apd_'.$this->data['kode_pos'].'_'.$periode;
        //$file_name3 = 'data-petugas_'.$this->data['kode_pos'].'_'.$periode;
        //$file_names = [$file_name1, $file_name2, $file_name3];
        //cek if file exits in database
        $i = 0;
        foreach ($file_names as $file_name) {
            $cek_db = $this->admin_model->get('id', 'report_pdf', 2, [['filename', $file_name]] );
            if (! is_array($cek_db) ) {
                $data = array(  'kode_pos' => $this->data['kode_pos'],
                                'nama_laporan' => $report_type[$i],
                                'periode' => $this->data['periode'],
                                'filename' => $file_name,
                                'create_at' => $tgl['tgl_update']
                        );
                $this->admin_model->insertData('report_pdf', $data);
                if ($report_type[$i] == 'Rekap APD') {
                    $this->create_report_apd_pdf($file_name, sqlDate2htmlminute($tgl['tgl_update']));
                } else if ($report_type[$i] == 'KIB APD') {
                    $this->create_report_kib_pdf($file_name, sqlDate2htmlminute($tgl['tgl_update']));
                }
            }else{
                if ($this->data['is_open']) {
                    $data = array(  
                                'create_at' => $tgl['tgl_update']
                        );
                    $this->admin_model->updateData('report_pdf', ['id', $cek_db['id']], $data);
                }
                if ($report_type[$i] == 'Rekap APD') {
                    $this->create_report_apd_pdf($file_name, sqlDate2htmlminute($tgl['tgl_update']));
                } else if ($report_type[$i] == 'KIB APD') {
                    $this->create_report_kib_pdf($file_name, sqlDate2htmlminute($tgl['tgl_update']));
                }
            }
            $i++;
        }
        //$this->create_report_apd_pdf('sdsa');
        $list_report = $this->admin_model->get('*', 'report_pdf', 1, [['kode_pos', $this->data['kode_pos']]], null, null, ['create_at', 'DESC']);
        //$cek_db = $this->admin_model->get('id', 'report_pdf', 3, [['filename', $file_name]] );
        /*$this->data['rekapAPD'] = $this->_calculate_rekap_APD();
        $data_sektor = $this->admin_model->get('chart_verif_APD', 'master_sektor', 2, [['kode', $this->data['kode_pos'] ]]);
        $this->data['data_sektor'] = json_decode($data_sektor['chart_verif_APD'], true);
        $list_pos = $this->admin_model->get('chart_verif_APD', 'master_pos', 1, [['kode_sektor', $this->data['kode_pos'] ]]);
        foreach ($list_pos as $pos) {
            $data_pos[] = json_decode($pos['chart_verif_APD'], true);
        }
        $this->data['list_pos'] = $data_pos;*/

        //$data_sektor = $this->admin_model->get('KIB_APD', 'master_sektor', 2, [['kode', $this->data['kode_pos'] ]]);
        //$this->data['data_sektor'] = json_decode($data_sektor['KIB_APD'], true);
        
        $this->data['list_report'] = $list_report;
        $this->data['pageTitle'] = 'Laporan PDF';
        $this->data['main_content'] = 'petugas/list_pdf';
		$this->load->view('petugas/includes/template', $this->data);
    }

    private function create_report_apd_pdf($file_name, $date)
    {
        //authentication
        $this->authenticate();
        $this->load->library('pdf');

        // create new PDF document
        //$pdf = new Pdf('L', 'mm', 'FOLIO', true, 'UTF-8', false);
        $pdf = new Pdf(PDF_PAGE_ORIENTATION, PDF_UNIT, PDF_PAGE_FORMAT, true, 'UTF-8', false);
		// set document information
		$pdf->SetCreator(PDF_CREATOR);
		$pdf->SetAuthor('kuz1toro@gmail.com');
		$pdf->SetTitle('Rekap APD Petugas');
		//$pdf->CellSetSubject('TCPDF Tutorial');
		$pdf->SetKeywords('TCPDF, PDF, example, test, guide');

		$pdf->setPrintHeader(false);
		// set default monospaced font
		$pdf->SetDefaultMonospacedFont(PDF_FONT_MONOSPACED);

        // set margins
		$PDF_MARGIN_TOP = 20;
		$pdf->SetMargins(PDF_MARGIN_LEFT, $PDF_MARGIN_TOP, PDF_MARGIN_RIGHT);
		$pdf->SetHeaderMargin(PDF_MARGIN_HEADER);
		$pdf->SetFooterMargin(PDF_MARGIN_FOOTER);

		// set auto page breaks
		$pdf->SetAutoPageBreak(TRUE, PDF_MARGIN_BOTTOM);

		// set image scale factor
		$pdf->setImageScale(PDF_IMAGE_SCALE_RATIO);

		// set some language-dependent strings (optional)
		if (@file_exists(dirname(__FILE__).'/lang/eng.php')) {
			require_once(dirname(__FILE__).'/lang/eng.php');
			$pdf->setLanguageArray($l);
		}

		// ---------------------------------------------------------

        // set default font subsetting mode
        $pdf->setFontSubsetting(true);
        //$pdf->setFillColor(255, 255, 127);
        // Set font
        // dejavusans is a UTF-8 Unicode font, if you only need to
        // print standard ASCII chars, you can use core fonts like
        // helvetica or times to reduce file size.
        $pdf->setFont('Times', 'B', 14, '', true);

        // Add a page
        // This method has several options, check the source code documentation for more information.
        //$pdf->AddPage();
        $pdf->AddPage('L', 'FOLIO');

        $pdf->MultiCell(0, 5, 'Laporan Realisasi Pendataan APD', 0, 'C', false, 1, '', '', true);
        
        $pdf->setFont('Times', '', 12, '', true);

        $pdf->Ln(5);

        //subheading
        $joinArr = [['master_controller', 'master_jabatan', 'master_jabatan.id_mj', 'id', 'mc_id' ]];
        $mc_id = $this->admin_model->get('master_controller.id', 'master_controller', 2, [['master_jabatan.id_mj', $this->data['jab_id']]], null, $joinArr);
        $renkin = $this->admin_model->get('*', 'renkin', 2, [['mc_id', $mc_id['id']]]);
        if (! is_array($renkin)) {
            redirect("my404");
        }

        $data_sektor = $this->admin_model->get('jml_pns, jml_pjlp, jml_verif, chart_verif_APD', 'master_sektor', 2, [['kode', $this->data['kode_pos'] ]]);
        $chart_verif_APD = json_decode($data_sektor['chart_verif_APD'], true);

        //$rekapAPD = $this->_calculate_rekap_APD();
        //$rekapAPD['jmlJenisAPD'] = $rekapAPD['jmlAPDTervld'] = $rekapAPD['data']['jenis_apd'] = 1;
        $jab_id_arr = $this->config->item('jabID_list_monitoring');
        foreach ($jab_id_arr as $jab_id) {
            $or_where_arr[] = ['jabatan_id', $jab_id];
        }

        $kode_sektor = $this->admin_model->get('kode_sektor', 'master_pos', 2, [['id_mp', $this->data['kode_pos_id']]]);
        $nama_sektor = $this->admin_model->get('sektor', 'master_sektor', 2, [['kode', $kode_sektor['kode_sektor']]]);
        $nama_sektor = $nama_sektor['sektor'];
        //$jmlPrsnl = $this->_get_users('id', 3, null, [['kode_pos', $this->data['kode_pos'], 'after']], $or_where_arr);
        //$jmlPrsnl = $this->admin_model->get('id', 'users', 3, [['active', 1]], [['kode_pos', $this->data['kode_pos'], 'after']], null, null, null, $or_where_arr );
        $jmlPrsnl = $data_sektor['jml_pns'] + $data_sektor['jml_pjlp'];
        $jmltotalAPD = $jmlPrsnl*$this->_get_jml_jenis_apd();
        $subheading1 = ['Sasaran Program', 'Indikator Kinerja', 'Periode input APD', 'Unit Kerja', 'Tanggal Update'];
        //hilangkan kata 'kantor'
        $penempatan1 = substr($this->data['penempatan']['nama_pos'], strpos($this->data['penempatan']['nama_pos'], 'Sektor'));
        $penempatan2 = substr($this->data['penempatan']['nama_pos'], strpos($this->data['penempatan']['nama_pos'], 'sektor'));
        $penempatan = (strlen($penempatan1)<strlen($penempatan2) ) ? $penempatan1 : $penempatan2 ;
        $subheading2 = [$renkin['sasaran'].' '.$nama_sektor, $renkin['indikator'].' '.$nama_sektor, $this->data['periode'], $penempatan, $date];
        for ($i=0; $i < count($subheading1) ; $i++) { 
            $pdf->MultiCell(40, 5, $subheading1[$i], 0, 'L', false, 0, '', '', true);
            $pdf->MultiCell(5, 5, ':', 0, 'C', false, 0, '', '', true);
            $pdf->MultiCell(160, 5, $subheading2[$i], 0, 'L', false, 1, '', '', true);
        }

        $dki = FCPATH.'assets/login/logo_dki.png';
        $damkar = FCPATH.'assets/login/logo_damkar_dki.png';
        $pdf->Image($dki, 230, 32, 20, '', 'PNG', '', 'T', false, 300, '', false, false, 0, false, false, false);
        $pdf->Image($damkar, 250, 32, 20, '', 'PNG', '', 'T', false, 300, '', false, false, 0, false, false, false);

         // set style for barcode
         $style = array(
            'border' => 2,
            'vpadding' => 'auto',
            'hpadding' => 'auto',
            'fgcolor' => array(0,0,0),
            'bgcolor' => false, //array(255,255,255)
            'module_width' => 1, // width of a single module in points
            'module_height' => 1 // height of a single module in points
        );
        $pdf->write2DBarcode(base_url().'upload/pdf/'.$file_name, 'QRCODE,H', 280, 32, 40, 40, $style, 'N');

        //$pdf->Ln(20);
        $pdf->SetY(80);

        /*$pdf->MultiCell(0, 5, 'A. Tabel Rekapitulasi Data APD Tervalidasi', 1, 'L', false, 1, '', '', true);
        $th = [['No', 10], ['Jenis APD', 45], ['Baik', 10], ['Rusak Ringan', 20], ['Rusak Sedang', 20], ['Rusak Berat', 20], ['Belum Terima', 20], ['Hilang', 15], ['Subtotal', 20]];
        for ($i=0; $i < (count($th)-1); $i++) { 
            $pdf->MultiCell($th[$i][1], 10, $th[$i][0], 1, 'C', false, 0, '', '', true);
        }
        $pdf->MultiCell($th[8][1], 10, $th[8][0], 1, 'C', false, 1, '', '', true);
        for ($i=0; $i < 14; $i++) { 
            $pdf->MultiCell(10, 5, $i+1, 1, 'C', false, 1, '', '', true);
        }*/

        $th1 = [['No', 5], ['Indikator', 36], ['Target', 8], ['Satuan', 9], ['Total APD Tervalidasi', 11], ['Total APD', 7], ['Persentase Realisasi', 13], ['Capaian', 11]];
        $html1 = '
        <table cellspacing="0" cellpadding="2" border="1">
            <thead>
                <tr>
                    <th colspan="8"> A. Tabel Perhitungan Realisasi dan Capaian</th>
                </tr>
                <tr>';
        for ($i=0; $i < count($th1); $i++) { 
            $html1 = $html1.'<th width="'.$th1[$i][1].'%" style="text-align: center;">'.$th1[$i][0].'</th>';
        }
        $html1 = $html1.'</tr>
                <tr>';

        $th2 = ['A', 'B', 'C', 'D', 'E', 'F', 'G=(E/F)*100%', 'H=(G/C)*100%'];
        for ($i=0; $i < count($th2); $i++) { 
            $html1 = $html1.'<th style="text-align: center;">'.$th2[$i].'</th>';
        }
        $html1 = $html1.'</tr>
        </thead><tbody><tr>';
        if ($jmltotalAPD == 0) {
            $persenRealisasi = $verified = 0;
        } else {
            $persenRealisasi = round(($data_sektor['jml_verif']/$jmltotalAPD*100),2);
            $verified = $data_sektor['jml_verif'];
        }
        
        $persenCapaian = round(($persenRealisasi/$renkin['target']*100),2);
        $td1 = [[1, 5], [$renkin['indikator'], 36], [$renkin['target'], 8], [$renkin['satuan'], 9], [$verified, 11], [$jmltotalAPD, 7], 
                [$persenRealisasi.' %', 13], [$persenCapaian.' %', 11]];
        for ($i=0; $i < count($td1); $i++) { 
            $retVal = ($i==1) ? 'left' : 'center' ;
            $html1 = $html1."
                <td width=\"{$td1[$i][1]}%\" style=\"text-align: {$retVal};\">{$td1[$i][0]}</td>
            ";
        }
        $html1 = $html1.'</tr></tbody></table>';
        
        $pdf->writeHTMLCell(302, 0, 14, '', $html1, 0, 1, 0, true, '', true);
        // ---------------------------------------------------------
        
        $pdf->Ln(10);

        //get atasan
        $kodePgglAtasan = $this->admin_model->get('kode_wilayah', 'master_pos', 2, [['id_mp', $this->data['kode_pos_id']]] );
        $kodePgglAtasan = $kodePgglAtasan['kode_wilayah'];
        $dinas = (strlen($kodePgglAtasan) > 1) ? true : false ;

        if (! $dinas) {
            $kodePgglAtasan = $kodePgglAtasan.'.3';
        }

        $dataJabatan = $this->admin_model->get('id_mj, nama_jabatan', 'master_jabatan', 2, [['kode_panggil', $kodePgglAtasan]] );
        $dataAtasan = $this->admin_model->get('nama, NIP, NRK', 'users', 2, [['jabatan_id', $dataJabatan['id_mj'] ]] );
        //$this->data['is_plt'] = (strpos($dataAtasan['NRK'], 'plt-') !== false) ? true : false ;
        if (strpos($dataAtasan['NRK'], 'plt-') !== false) {
            $dataJabatan['nama_jabatan'] = 'plt. '.$dataJabatan['nama_jabatan'];
        }
        
        
        $html2 = '
        <table cellspacing="0" cellpadding="2" border="0">
            <tbody>
                <tr>
                    <td width="40%" style="text-align: center"></td>
                    <td width="20%" style="text-align: center"></td>
                    <td width="40%" style="text-align: center">Jakarta, ...............................................................</td>
                </tr>
                <tr>
                    <td style="text-align: center">Mengetahui</td>
                    <td style="text-align: center"></td>
                    <td style="text-align: center">'.$this->data['jabatan']['nama_jabatan'].'</td>
                </tr>
                <tr>
                    <td style="text-align: center">'.$dataJabatan['nama_jabatan'].'</td>
                    <td style="text-align: center"></td>
                    <td style="text-align: center">'.$this->data['unit'].'</td>
                </tr>
                <tr>
                    <td colspan="3" style="text-align: center"></td>
                </tr>
                <tr>
                    <td colspan="3" style="text-align: center"></td>
                </tr>
                <tr>
                    <td colspan="3" style="text-align: center"></td>
                </tr>
                <tr>
                    <td colspan="3" style="text-align: center"></td>
                </tr>
                <tr>
                    <td style="text-align: center">'.$dataAtasan['nama'].'</td>
                    <td style="text-align: center"></td>
                    <td style="text-align: center">'.$this->data['username'].'</td>
                </tr>
                <tr>
                    <td style="text-align: center">NIP '.$dataAtasan['NIP'].'</td>
                    <td style="text-align: center"></td>
                    <td style="text-align: center">NIP '.$this->data['NIP'].'</td>
                </tr>
            </tbody>
        </table>';
        $pdf->writeHTMLCell(302, 0, 14, '', $html2, 0, 1, 0, true, '', true);
        
        $pdf->AddPage('L', 'FOLIO');

        $pdf->MultiCell(0, 5, 'LAMPIRAN', 0, 'C', false, 1, '', '', true);
        $pdf->MultiCell(0, 5, 'Laporan Realisasi Pendataan APD', 0, 'C', false, 1, '', '', true);
        $pdf->MultiCell(0, 5, $penempatan, 0, 'C', false, 1, '', '', true);
        $pdf->Ln(10);
        $w = [5, 25, 5, 8, 9, 8, 10, 8, 7, 10, 5];
        $th = [['Baik', 5], ['Rusak Ringan', 8], ['Rusak Sedang', 9], ['Rusak Berat', 8], ['Subtotal Existing', 10], ['Belum Terima', 8], ['Hilang', 7], ['Subtotal Kurang', 10] ];
        $th1 = ['A', 'B', 'C', 'D', 'E', 'F', 'G', 'H', 'I', 'J', 'K'];
        $html = '
        <table cellspacing="0" cellpadding="2" border="1">
            <thead>
                <tr>
                    <th colspan="9">B. Tabel Rekapitulasi Data APD Tervalidasi</th>
                </tr>
                <tr>
                    <th rowspan="2" width="5%" style="text-align: center;">No</th>
                    <th rowspan="2" width="25%" style="text-align: center;">Jenis APD</th>
                    <th colspan="5" width="40%" style="text-align: center;">Jumlah APD Berdasarkan Kondisi</th>
                    <th colspan="3" width="25%" style="text-align: center;">Jumlah APD Berdasarkan Keberadaan</th>
                    <th rowspan="2" width="5%" style="text-align: center;">Sub Total</th>
                </tr>
                <tr>
                ';
        for ($i=0; $i < count($th); $i++) { 
            $html = $html.'<th width="'.$th[$i][1].'%" style="text-align: center;">'.$th[$i][0].'</th>';
        }
        $html = $html.'</tr><tr>';
        for ($j=0; $j < count($th1); $j++) { 
            $html = $html.'<th width="'.$w[$j].'%" style="text-align: center;">'.$th1[$j].'</th>';
        }
        $html = $html.'</tr>
        </thead><tbody>';

        $i=0;
        $sum = [0,0,0,0,0,0,0,0,0];
        if (is_array($chart_verif_APD)) {
            foreach ($chart_verif_APD as $key1 => $value1) {
                $j=0;
                $html = $html.'
                <tr>
                    <td width="'.$w[$j].'%" style="text-align: center;">'.($i+1).'</td>';
                    $j++;
                foreach ($value1 as $key2 => $value2) {
                    if ($j == 1) {
                        $html = $html.'<td width="'.$w[$j].'%" >'.$value2.'</td>';
                    } else {
                        $html = $html.'<td width="'.$w[$j].'%" style="text-align: center;">'.$value2.'</td>';
                        $sum[$j-2] = $sum[$j-2] + $value2;
                    }
                    $j++;
                }
                $i++;
                $html = $html.'</tr>';
            }
            
            $html = $html."<tr>
                        <td colspan=\"2\" style=\"text-align: center;\"><strong>Total</strong></td>";
            
                        foreach ($sum as $val) {
                            $html = $html."<td style=\"text-align: center;\"><strong>$val</strong></td>";
                        }
        } else {
            $html = $html.'
                <tr>
                    <td width="100%" colspan="11" style="text-align: center;"> No Data </td>';
        }
        
        
        $html = $html.'</tr></tbody></table>';
        
        // set text shadow effect
        //$pdf->setTextShadow(array('enabled'=>true, 'depth_w'=>0.2, 'depth_h'=>0.2, 'color'=>array(196,196,196), 'opacity'=>1, 'blend_mode'=>'Normal'));

        // Print table using writeHTMLCell()
        $pdf->writeHTMLCell(302, 0, 14, '', $html, 0, 1, 0, true, '', true);

        $pdf->setFont('Times', '', 10, '', true);

        $pdf->Ln(3);

        $pdf->MultiCell(0, 5, 'Keterangan :', 0, 'L', false, 1, '', '', true);
        $pdf->MultiCell(0, 5, 'G = C + D + E + F', 0, 'L', false, 1, '', '', true);
        $pdf->MultiCell(0, 5, 'J = H + I', 0, 'L', false, 1, '', '', true);
        $pdf->MultiCell(0, 5, 'K = G + J', 0, 'L', false, 1, '', '', true);
        
        // Close and output PDF document
        // This method has several options, check the source code documentation for more information.
        //$pdf->Output('example_001.pdf', 'I');
        $pdf->Output(FCPATH.'upload/pdf/'.$file_name, 'F');

        
    }

    private function create_report_kib_pdf($file_name, $date)
    {
        //authentication
        $this->authenticate();
        $this->load->library('pdf');

        // create new PDF document
        //$pdf = new Pdf('L', 'mm', 'FOLIO', true, 'UTF-8', false);
        $pdf = new Pdf(PDF_PAGE_ORIENTATION, PDF_UNIT, PDF_PAGE_FORMAT, true, 'UTF-8', false);
		// set document information
		$pdf->SetCreator(PDF_CREATOR);
		$pdf->SetAuthor('kuz1toro@gmail.com');
		$pdf->SetTitle('KIB APD Petugas');
		//$pdf->CellSetSubject('TCPDF Tutorial');
		$pdf->SetKeywords('TCPDF, PDF, example, test, guide');

		$pdf->setPrintHeader(false);
		// set default monospaced font
		$pdf->SetDefaultMonospacedFont(PDF_FONT_MONOSPACED);

        // set margins
		$PDF_MARGIN_TOP = 20;
		$pdf->SetMargins(PDF_MARGIN_LEFT, $PDF_MARGIN_TOP, PDF_MARGIN_RIGHT);
		$pdf->SetHeaderMargin(PDF_MARGIN_HEADER);
		$pdf->SetFooterMargin(PDF_MARGIN_FOOTER);

		// set auto page breaks
		$pdf->SetAutoPageBreak(TRUE, PDF_MARGIN_BOTTOM);

		// set image scale factor
		$pdf->setImageScale(PDF_IMAGE_SCALE_RATIO);

		// set some language-dependent strings (optional)
		if (@file_exists(dirname(__FILE__).'/lang/eng.php')) {
			require_once(dirname(__FILE__).'/lang/eng.php');
			$pdf->setLanguageArray($l);
		}

		// ---------------------------------------------------------

        // set default font subsetting mode
        $pdf->setFontSubsetting(true);
        //$pdf->setFillColor(255, 255, 127);
        // Set font
        // dejavusans is a UTF-8 Unicode font, if you only need to
        // print standard ASCII chars, you can use core fonts like
        // helvetica or times to reduce file size.
        $pdf->setFont('Times', 'B', 14, '', true);

        // Add a page
        // This method has several options, check the source code documentation for more information.
        //$pdf->AddPage();
        $pdf->AddPage('L', 'FOLIO');

        $pdf->MultiCell(0, 5, 'Kartu Inventaris Barang APD', 0, 'C', false, 1, '', '', true);
        
        $pdf->setFont('Times', '', 12, '', true);

        $pdf->Ln(5);

        //subheading
        /*$joinArr = [['master_controller', 'master_jabatan', 'master_jabatan.id_mj', 'id', 'mc_id' ]];
        $mc_id = $this->admin_model->get('master_controller.id', 'master_controller', 2, [['master_jabatan.id_mj', $this->data['jab_id']]], null, $joinArr);
        $renkin = $this->admin_model->get('*', 'renkin', 2, [['mc_id', $mc_id['id']]]);
        if (! is_array($renkin)) {
            redirect("my404");
        }*/

        $data_sektor = $this->admin_model->get('KIB_APD', 'master_sektor', 2, [['kode', $this->data['kode_pos'] ]]);
        $KIB_APD = json_decode($data_sektor['KIB_APD'], true);
        $unit = $this->admin_model->get('dinas', 'master_dinas', 2, [['id', 1 ]]);
        $sub_unit = $this->admin_model->get('nama_jabatan, keterangan', 'master_jabatan', 2, [['id_mj', $this->data['jab_id'] ]]);
        $satker = str_replace('Kepala ', '', $sub_unit['nama_jabatan']);

        //$rekapAPD = $this->_calculate_rekap_APD();
        //$rekapAPD['jmlJenisAPD'] = $rekapAPD['jmlAPDTervld'] = $rekapAPD['data']['jenis_apd'] = 1;
        //$jmlPrsnl = $this->_get_users('id', 3, null, [['kode_pos', $this->data['kode_pos'], 'after']], $or_where_arr);
        //$jmlPrsnl = $this->admin_model->get('id', 'users', 3, [['active', 1]], [['kode_pos', $this->data['kode_pos'], 'after']], null, null, null, $or_where_arr );
        //$jmlPrsnl = $data_sektor['jml_pns'] + $data_sektor['jml_pjlp'];
        //$jmltotalAPD = $jmlPrsnl*$this->_get_jml_jenis_apd();
        $subheading1 = ['Provinsi', 'Unit Organisasi', 'Sub Unit Organisasi', 'Satuan Kerja', 'Periode Pendataan'];
        $subheading2 = ['DKI Jakarta', $unit['dinas'], $sub_unit['keterangan'], $satker, $this->data['periode'] ];
        //hilangkan kata 'kantor'
        $penempatan1 = substr($this->data['penempatan']['nama_pos'], strpos($this->data['penempatan']['nama_pos'], 'Sektor'));
        $penempatan2 = substr($this->data['penempatan']['nama_pos'], strpos($this->data['penempatan']['nama_pos'], 'sektor'));
        $penempatan = (strlen($penempatan1)<strlen($penempatan2) ) ? $penempatan1 : $penempatan2 ;
        
        for ($i=0; $i < count($subheading1) ; $i++) { 
            $pdf->MultiCell(40, 5, $subheading1[$i], 0, 'L', false, 0, '', '', true);
            $pdf->MultiCell(0, 5, ': '.$subheading2[$i], 0, 'L', false, 1, '', '', true);
        }

        //$pdf->SetXY(20, 20);
        $dki = FCPATH.'assets/login/logo_dki.png';
        $damkar = FCPATH.'assets/login/logo_damkar_dki.png';
        $pdf->Image($dki, 230, 32, 20, '', 'PNG', '', 'T', false, 300, '', false, false, 0, false, false, false);
        $pdf->Image($damkar, 250, 32, 20, '', 'PNG', '', 'T', false, 300, '', false, false, 0, false, false, false);

        // set style for barcode
        $style = array(
            'border' => 2,
            'vpadding' => 'auto',
            'hpadding' => 'auto',
            'fgcolor' => array(0,0,0),
            'bgcolor' => false, //array(255,255,255)
            'module_width' => 1, // width of a single module in points
            'module_height' => 1 // height of a single module in points
        );
        $pdf->write2DBarcode(base_url().'upload/pdf/'.$file_name, 'QRCODE,H', 280, 32, 40, 40, $style, 'N');

        //$pdf->Ln(20);
        $pdf->SetY(70);
        $th1 = [['No', 3], ['Kode Barang', 10], ['Nama APD', 18], ['Merk', 19], ['Tahun', 8], ['Jumlah APD Berdasarkan Kondisi', 28], ['Total', 6], ['Satuan', 8]];
        $th2 = [['Baik', 5], ['Kurang Baik', 9], ['Rusak', 5], ['Rusak Berat', 9] ];
        $w = [3, 10, 18, 19, 8, 5, 9, 5, 9, 6, 8];
        //$pdf->MultiCell(0, 5, 'A. Tabel Rekapitulasi Data APD Tervalidasi', 1, 'L', false, 1, '', '', true);

        $html = '
        <table cellspacing="0" cellpadding="2" border="1">
            <thead>
                <tr>';
        for ($i=0; $i < (count($th1)); $i++) { 
            if ($i == 5) {
                $html = $html.'<th colspan="4" width="'.$th1[$i][1].'%" style="text-align: center;">'.$th1[$i][0].'</th>';
            } else {
                $html = $html.'<th rowspan="2" width="'.$th1[$i][1].'%" style="text-align: center;"><div style="font-size:6pt">&nbsp;</div>'.$th1[$i][0].'</th>';
            }
            
        }
        $html = $html.'</tr><tr>';
        for ($i=0; $i < count($th2); $i++) { 
            $html = $html.'<th width="'.$th2[$i][1].'%" style="text-align: center;">'.$th2[$i][0].'</th>';
        }
        $html = $html.'
        </tr>
        </thead><tbody>';
        $i=1;
        if (is_array($KIB_APD)) {
            foreach ($KIB_APD as $key1 => $value1) {
                $html = $html.'<tr><td width="'.$w[0].'%" style="text-align: center;">'.$i.'</td>';
                $j=1;
                foreach ($value1 as $key2 => $value2) {
                    if ($key2 != 'id_mj') {
                        if ($key2=='jenis_apd' || $key2=='merk' || $key2=='satuan') {
                            $html = $html.'<td width="'.$w[$j].'%">'.$value2.'</td>';
                        } else if($key2=='tahun') {
                            $html = $html.'<td width="'.$w[$j].'%" style="text-align: right;">'.$value2.'</td>';
                        }
                        else {
                            $html = $html.'<td width="'.$w[$j].'%" style="text-align: center;">'.$value2.'</td>';
                        }
                        $j++;
                    }
                }
                $html = $html.'</tr>';
                $i++;
            }
        } else {
            $html = $html.'<tr><td width="100%" style="text-align: center;">No Data</td></tr>';
        }
        
        

        $html = $html.'</tbody>
        </table>';

        /*$pdf->MultiCell(0, 5, 'A. Tabel Rekapitulasi Data APD Tervalidasi', 1, 'L', false, 1, '', '', true);
        $th = [['No', 10], ['Jenis APD', 45], ['Baik', 10], ['Rusak Ringan', 20], ['Rusak Sedang', 20], ['Rusak Berat', 20], ['Belum Terima', 20], ['Hilang', 15], ['Subtotal', 20]];
        for ($i=0; $i < (count($th)-1); $i++) { 
            $pdf->MultiCell($th[$i][1], 10, $th[$i][0], 1, 'C', false, 0, '', '', true);
        }
        $pdf->MultiCell($th[8][1], 10, $th[8][0], 1, 'C', false, 1, '', '', true);
        for ($i=0; $i < 14; $i++) { 
            $pdf->MultiCell(10, 5, $i+1, 1, 'C', false, 1, '', '', true);
        }*/

        $pdf->writeHTMLCell(302, 0, 14, '', $html, 0, 1, 0, true, '', true);

        $pdf->Ln(10);

        //batas 128
        //$pdf->SetY(128);
        $coor_y = $pdf->GetY();
        if ($coor_y > 128) {
            $pdf->AddPage('L', 'FOLIO');
            $pdf->Ln(30);
        }

        //get atasan
        $kodePgglAtasan = $this->admin_model->get('kode_wilayah', 'master_pos', 2, [['id_mp', $this->data['kode_pos_id']]] );
        $kodePgglAtasan = $kodePgglAtasan['kode_wilayah'];
        $dinas = (strlen($kodePgglAtasan) > 1) ? true : false ;

        if (! $dinas) {
            $kodePgglAtasan = $kodePgglAtasan.'.3';
        }

        $dataJabatan = $this->admin_model->get('id_mj, nama_jabatan', 'master_jabatan', 2, [['kode_panggil', $kodePgglAtasan]] );
        $dataAtasan = $this->admin_model->get('nama, NIP, NRK', 'users', 2, [['jabatan_id', $dataJabatan['id_mj'] ]] );
        //$this->data['is_plt'] = (strpos($dataAtasan['NRK'], 'plt-') !== false) ? true : false ;
        if (strpos($dataAtasan['NRK'], 'plt-') !== false) {
            $dataJabatan['nama_jabatan'] = 'plt. '.$dataJabatan['nama_jabatan'];
        }
        
        

        $html2 = '
        <table cellspacing="0" cellpadding="2" border="0">
            <tbody>
                <tr>
                    <td width="40%" style="text-align: center"></td>
                    <td width="20%" style="text-align: center"></td>
                    <td width="40%" style="text-align: center">Jakarta, ...............................................................</td>
                </tr>
                <tr>
                    <td style="text-align: center">Mengetahui</td>
                    <td style="text-align: center"></td>
                    <td style="text-align: center">'.$this->data['jabatan']['nama_jabatan'].'</td>
                </tr>
                <tr>
                    <td style="text-align: center">'.$dataJabatan['nama_jabatan'].'</td>
                    <td style="text-align: center"></td>
                    <td style="text-align: center">'.$this->data['unit'].'</td>
                </tr>
                <tr>
                    <td colspan="3" style="text-align: center"></td>
                </tr>
                <tr>
                    <td colspan="3" style="text-align: center"></td>
                </tr>
                <tr>
                    <td colspan="3" style="text-align: center"></td>
                </tr>
                <tr>
                    <td colspan="3" style="text-align: center"></td>
                </tr>
                <tr>
                    <td style="text-align: center">'.$dataAtasan['nama'].'</td>
                    <td style="text-align: center"></td>
                    <td style="text-align: center">'.$this->data['username'].'</td>
                </tr>
                <tr>
                    <td style="text-align: center">NIP '.$dataAtasan['NIP'].'</td>
                    <td style="text-align: center"></td>
                    <td style="text-align: center">NIP '.$this->data['NIP'].'</td>
                </tr>
            </tbody>
        </table>';
        $pdf->writeHTMLCell(302, 0, 14, '', $html2, 0, 1, 0, true, '', true);
        
        // Close and output PDF document
        // This method has several options, check the source code documentation for more information.
        //$pdf->Output('example_001.pdf', 'I');
        $pdf->Output(FCPATH.'upload/pdf/'.$file_name, 'F');

        
    }

    public function get_img_apd_ajax()
	{
		$id=$_GET['loadId'];
        //$nrk = $this->input->get('loadId');
		//$loadId=$_POST['loadId'];
		//$this->load->model('model');
        
        $data_pos = $this->petugas_model->get('foto_mapd, no_seri', 'master_apd', [['id_ma', $id] ], null, 2);
        
        echo json_encode($data_pos);
        exit();
	}

    private function _fill_size()
    {
        $this->load->helper('date');
        $user_role = $this->data['user_role'];
        if ($user_role > 2) {
            $list_apd_temp = ['uk_kaos_temp', 'uk_baju_dinas_temp', 'uk_celana_dinas_temp', 'uk_sepatu_dinas_temp', 'ukuran_baret_temp', 'uk_fire_jaket_temp', 'uk_sepatu_rescue_boots_temp'];
            $list_apd = ['uk_kaos', 'uk_baju_dinas', 'uk_celana_dinas', 'uk_sepatu_dinas', 'ukuran_baret', 'uk_fire_jaket', 'uk_sepatu_rescue_boots'];
        } else {
            $list_apd_temp = ['uk_kaos_temp', 'uk_baju_dinas_temp', 'uk_celana_dinas_temp', 'uk_sepatu_dinas_temp', 'ukuran_baret_temp', 'uk_fire_jaket_temp', 'uk_sepatu_rescue_boots_temp', 'ukuran_sepatu_fire_boots_temp', 'uk_gloves_temp', 'uk_jumpsuit_temp'];
            $list_apd = ['uk_kaos', 'uk_baju_dinas', 'uk_celana_dinas', 'uk_sepatu_dinas', 'ukuran_baret', 'uk_fire_jaket', 'uk_sepatu_rescue_boots', 'ukuran_sepatu_fire_boots', 'uk_gloves', 'uk_jumpsuit'];
        }

        $this->data['list_label'] = ['Ukuran Kaos', 'Ukuran Baju PDH/PDL/Olah Raga', 'Ukuran Celana PDH/PDL/Olah Raga', 'Ukuran Sepatu PDH/PDL/Olah Raga', 'Ukuran Baret', 'Ukuran Fire Jacket', 'Ukuran Sepatu Rescue Boots', 'Ukuran Sepatu Fire Boots', 'Ukuran Gloves', 'Ukuran Jumpsuit'];
        $this->data['list_tipe_ukuran'] = [3,3,3,2,4,3,2,2,3,3];

        $arr_ukuran_huruf = $this->petugas_model->get('daftar_ukuran', 'master_tipe_ukuran', [['id_mtu', 3] ], null, 2);
        $this->data['list_ukuran_huruf'] = json_decode($arr_ukuran_huruf['daftar_ukuran'], true);

        $arr_ukuran_angka = $this->petugas_model->get('daftar_ukuran', 'master_tipe_ukuran', [['id_mtu', 2] ], null, 2);
        $this->data['list_ukuran_angka'] = json_decode($arr_ukuran_angka['daftar_ukuran'], true);

        $arr_ukuran_baret = $this->petugas_model->get('daftar_ukuran', 'master_tipe_ukuran', [['id_mtu', 4] ], null, 2);
        $this->data['list_ukuran_baret'] = json_decode($arr_ukuran_baret['daftar_ukuran'], true);
        
        $this->data['my_time'] = date("Y-m-d H:i:s", now('Asia/Jakarta'));
        $this->data['list_apd'] = $list_apd;
        $this->data['list_apd_temp'] = $list_apd_temp;
        $this->load->view('petugas/intro', $this->data);
    }

    public function simpan_ukuran_ajax()
    {
        $input = $this->input->post();
        $valid = true;
        if (!isset($input['users_id']) || !isset($input['uk_sepatu_dinas']) || !isset($input['uk_fire_jaket']) || !isset($input['waktu'])) {
            $valid = false;
        }
        foreach ($input as $key => $value) {
            if (empty($value)) {
                $valid = false;
            }
        }
        
        if ($valid) {
            if ($this->petugas_model->insertData('users_ukuran', $input)) {
                $response = ['status' => true];
            } else {
                $response = ['status' => false];
            }
            //$response = ['status' => true];
        } else {
            $response = ['status' => false];
        }
        
        echo json_encode($response);
        exit();
    }

}