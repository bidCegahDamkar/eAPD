<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Kasi_sarana extends CI_Controller {
    public $data = [];
    public $active = array(
        'homeMenu' => '',
        'verifikasiMenu' => '',
        'verifikasi' => '',
        'sewaktu' => '',
        'dataMenu' => '',
        'apdTerverifikasi' => '',
        'apdTertolak' => '',
        'dataUser' => '',
        'laporanMenu' => '',
        'rekap' => '',
        'detail' => '',
        'pdf' => '',
        'setting' => '',
        'user_setting' => '',
        'plt_setting' => ''
    );

    public function __construct()
	{
        parent::__construct();
        $this->load->database();
        $this->load->library(['ion_auth', 'my_apd', 'form_validation']);
        $this->load->helper(['url', 'language']);
        $this->load->model(['petugas_model', 'admin_model']);
        $this->authenticate();
        $user = $this->ion_auth->user()->row();
        $config = $this->admin_model->get_controller($user->jabatan_id);
        $this->config->load($config['config']);
        //$this->data['user'] = $this->ion_auth->user()->row();
        $this->data['username'] = $user->nama;
        $this->data['user_id'] = $user->id;
        $id_mp = $this->admin_model->get('kode_pos', 'master_pos', 2, [['id_mp', $user->kode_pos_id]] );
        $this->data['full_kode_pos'] = $id_mp['kode_pos'];
        $this->data['kode_pos'] = substr($id_mp['kode_pos'], 0, -1);  //ambil 1 kode pertama dan titik
        $this->data['kode_pos_min'] = substr($id_mp['kode_pos'], 0, -2);  //ambil 1 kode pertama dan tanpa titik
        //$this->data['kode_pos'] = '0.1';
        //$this->data['jabatan'] = $this->petugas_model->get('nama_jabatan, keterangan', 'master_jabatan', [['id_mj', $user->jabatan_id]], null, 2);
        $this->data['penempatan'] = $this->petugas_model->get('nama_pos', 'master_pos', [['kode_pos', $id_mp['kode_pos']]], null, 2);
        $state = $this->my_apd->check_isOpenPeriode();
        $this->data['is_open'] = ($state['is_open']) ? true : false ;
        $this->data['periode'] = $state['periode'];
        $this->data['info_periode_input'] = $state['info_periode_input'];
        $this->data['avatar'] = (! is_null($user->photo)) ? 'upload/petugas/profil/'.$user->photo : 'upload/petugas/profil/default.png' ;
        $this->data['nrk'] = $user->NRK;
        $this->data['is_plt'] = (strpos($this->data['nrk'], 'plt-') !== false) ? true : false ;
        $temp_jabatan = $this->petugas_model->get('nama_jabatan, keterangan', 'master_jabatan', [['id_mj', $user->jabatan_id]], null, 2);
        $jabatan_es['nama_jabatan'] = ($this->data['is_plt']) ? 'plt. '.$temp_jabatan['nama_jabatan'] : $temp_jabatan['nama_jabatan'] ;
        //$this->data['jabatan'] = $this->petugas_model->get('nama_jabatan', 'master_jabatan', [['id_mj', $user->jabatan_id]], null, 2);
        $this->data['jabatan'] = $jabatan_es;
        $this->data['unit'] = $temp_jabatan['keterangan'];

        $this->data['password'] = $user->password;
        $this->data['user_roles'] = $this->ion_auth->get_users_groups($user->id)->result();
        $this->data['group_piket'] = $user->group_piket;
        $this->data['jab_id'] = $user->jabatan_id;
        $this->data['NIP'] = $user->NIP;
        //$this->data['jumJenisApd'] = $this->admin_model->get('id_mj', 'master_jenis_apd', 3, [['deleted', 0]] );
        $list_sudin = array('1.' => 'Sudin Jakarta Pusat', '2.' => 'Sudin Jakarta Utara', '3.' => 'Sudin Jakarta Barat', '4.' => 'Sudin Jakarta Selatan', '5.' => 'Sudin Jakarta Timur');
        $this->data['sudin'] = $list_sudin[ $this->data['kode_pos'] ];
        $this->data['kode_pos_id'] = $user->kode_pos_id;
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
            if ($user_id['controller'] != 'kasi_sarana') {
                redirect("my404");
            }
        }else {
            //redirect('auth/login', 'refresh');
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
                            ['apd', 'master_jenis_apd', 'master_jenis_apd.jenis_apd', 'mj_id', 'id_mj' ]
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

            //get role_id user
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
            return $this->admin_model->updateData('users', ['id', $UserID], $data_user);
        } else {
            return false;
        }
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
        redirect(''.$this->data['controller'].'/home');
    }

    public function home()
    {
        $this->data['jumJenisApd'] = $this->_get_jml_jenis_apd();
        $select1 = 'jml_pns, jml_pjlp, jml_input, jml_verif, jml_ditolak';
        $this->data['list_sudin'] = $this->admin_model->get($select1, 'master_sudin', 1, [['deleted', 0], ['kode', $this->data['kode_pos_min']]]);
        $select2 = 'kode, sektor as sudin, jml_pns, jml_pjlp, jml_input, jml_verif, jml_ditolak, chart_input_APD, chart_verif_APD';
        $select3 = 'nama_pos, jml_pns, jml_pjlp, jml_input, jml_verif, jml_ditolak';
        $list_sektor = $this->admin_model->get($select2, 'master_sektor', 1, [['deleted', 0]], [['kode', $this->data['kode_pos'], 'after']]);
        $this->data['list_sektor'] = $list_sektor;
        foreach ($list_sektor as $sektor) {
            $list_pos = $this->admin_model->get($select3, 'master_pos', 1, [['deleted', 0], ['kode_sektor', $sektor['kode']] ]);
            $data_pos[] = array('sektor' => $sektor['sudin'], 'data' => $list_pos);
        }
        $this->data['list_pos'] = $data_pos;
        //d($list_sektor, $data_pos);
        $listJenisAPD = $this->_get_list_jenis_apd('jenis_apd');
        foreach ($listJenisAPD as $key) {
            $listNamaJenisAPD[] = $key['jenis_apd'];
        }
        $this->data['listNamaJenisAPD'] = $listNamaJenisAPD;
        $this->data['title_input'] = 'Persentase Input APD (per Jenis APD dan Sektor)';
        $this->data['title_verif'] = 'Persentase APD Terverifikasi (per Jenis APD dan Sektor)';

        $active = $this->active;
		$active['homeMenu'] = 'active-page';
		$this->data['active'] = $active;

        $this->data['apexcharts'] = true;

        $this->data['pageTitle'] = 'Dashboard';
        $this->data['main_content'] = 'admin_sudin/home';
		$this->load->view('admin_sudin/includes/template', $this->data);
        //$this->load->view('admin_sudin/home', $this->data);
    }

    public function home1()
    {
        $jab_id_arr = $this->config->item('mcID_list_monitoring');
        foreach ($jab_id_arr as $jab_id) {
            $or_where_arr[] = ['master_jabatan.mc_id', $jab_id];
        }
        $jumJenisApd = $this->_get_jml_jenis_apd();
        $list_sektor = $this->admin_model->get('kode, sektor', 'master_sektor', 1, null, [['kode', $this->data['kode_pos'], 'after']]);
        $joinArr = [['users', 'master_jabatan', 'master_jabatan.mc_id', 'jabatan_id', 'id_mj' ], 
                    ['users', 'master_pos', 'master_pos.kode_sektor', 'kode_pos_id', 'id_mp' ] ];
        $joinArr1 = [['apd', 'users', 'users.jabatan_id', 'petugas_id', 'id' ], 
                    ['users', 'master_jabatan', 'master_jabatan.mc_id', 'jabatan_id', 'id_mj' ],
                    ['users', 'master_pos', 'master_pos.kode_sektor', 'kode_pos_id', 'id_mp' ] ];
        $joinTable1 = ['master_jabatan'];
        $joinTable2 = ['users', 'master_jabatan', 'master_pos'];
        $jmlPNS = 0;
        $jmlPJLP = 0;
        $jumSdhInput = 0;
        $jumVerified = 0;
        $select1 = 'id, master_pos.kode_sektor, master_jabatan.mc_id';
        $select2 = 'apd.id, master_pos.kode_sektor, master_jabatan.mc_id';
        foreach ($list_sektor as $sektor) {
            //tingkat Sektor
            /**$temp_jmlPNS = $this->admin_model->get('id', 'users', 3, [['status_id', 0], ['active', 1], ['master_pos.kode_sektor', $sektor['kode']] ], null, $joinArr, null, null, $or_where_arr );
            $temp_jmlPJLP = $this->admin_model->get('id', 'users', 3, [['status_id', 1], ['active', 1], ['master_pos.kode_sektor', $sektor['kode']] ], null, $joinArr, null, null, $or_where_arr );
            $temp_jumSdhInput = $this->admin_model->get('apd.id', 'apd', 3, [['mj_id !=', 0], ['progress !=', 0], ['master_pos.kode_sektor', $sektor['kode']] ], null, $joinArr1, null, null, $or_where_arr );
            $temp_jumVerified = $this->admin_model->get('apd.id', 'apd', 3, [['mj_id !=', 0], ['progress', 3], ['master_pos.kode_sektor', $sektor['kode']] ], null, $joinArr1, null, null, $or_where_arr );
            $sektor_arr[] = $sektor['sektor'];
            $totalAPD = ($temp_jmlPNS + $temp_jmlPJLP)*$jumJenisApd;
            $jmlPNS[] = $temp_jmlPNS;
            $jmlPJLP[] = $temp_jmlPJLP;
            $persenSdhInput[] = round( (($temp_jumSdhInput/$totalAPD)*100) ,1);
            $persenVerified[] = round( (($temp_jumVerified/$totalAPD)*100) ,1);**/
            //tingkat pos
            $list_pos = $this->admin_model->get('kode_pos, nama_pos', 'master_pos', 1, [['kode_sektor', $sektor['kode']], ['deleted', 0] ]);
            $data_pos = [];
            foreach ($list_pos as $pos) {
                $temp_jmlPNS = $this->_get_users($select1, 3, [['status_id', 0], ['master_pos.kode_pos', $pos['kode_pos']]], null, $or_where_arr, null, $joinTable1);
                //$temp_jmlPNS = $this->admin_model->get('id', 'users', 3, [['status_id', 0], ['active', 1], ['master_pos.kode_pos', $pos['kode_pos']] ], null, $joinArr, null, null, $or_where_arr );
                $temp_jmlPJLP = $this->_get_users($select1, 3, [['status_id', 1], ['master_pos.kode_pos', $pos['kode_pos']]], null, $or_where_arr, null, $joinTable1);
                //$temp_jmlPJLP = $this->admin_model->get('id', 'users', 3, [['status_id', 1], ['active', 1], ['master_pos.kode_pos', $pos['kode_pos']] ], null, $joinArr, null, null, $or_where_arr );
                $temp_jumSdhInput = $this->_get_apds($select2, 3, [['progress !=', 0], ['master_pos.kode_pos', $pos['kode_pos']] ], null, $or_where_arr, null, $joinTable2);
                //$temp_jumSdhInput = $this->admin_model->get('apd.id', 'apd', 3, [['mj_id !=', 0], ['progress !=', 0], ['master_pos.kode_pos', $pos['kode_pos']] ], null, $joinArr1, null, null, $or_where_arr );
                $temp_jumVerified = $this->_get_apds($select2, 3, [['progress', 3], ['master_pos.kode_pos', $pos['kode_pos']] ], null, $or_where_arr, null, $joinTable2);
                //$temp_jumVerified = $this->admin_model->get('apd.id', 'apd', 3, [['mj_id !=', 0], ['progress', 3], ['master_pos.kode_pos', $pos['kode_pos']] ], null, $joinArr1, null, null, $or_where_arr );
                $totalAPD = ($temp_jmlPNS + $temp_jmlPJLP)*$jumJenisApd;
                $jmlPNS += $temp_jmlPNS;
                $jmlPJLP += $temp_jmlPJLP;
                $jumSdhInput += $temp_jumSdhInput;
                $jumVerified += $temp_jumVerified;
                if($totalAPD == 0){
                    $persenSdhInput = 0.0;
                    $persenVerified = 0.0;
                }else{
                    $persenSdhInput = round( (($temp_jumSdhInput/$totalAPD)*100) ,1);
                    $persenVerified = round( (($temp_jumVerified/$totalAPD)*100) ,1);
                }
                $data_pos[] = array('pos' => $pos['nama_pos'],
                                    'jmlPNS' => $temp_jmlPNS,
                                    'jmlPJLP' => $temp_jmlPJLP,
                                    'persenSdhInput' => $persenSdhInput,
                                    'persenVerified' => $persenVerified
                                    );
            }
            $data_sektor[] = array( 'sektor' => $sektor['sektor'],
                                    'data_pos' => $data_pos );
        }

        $this->data['data_sektor'] = $data_sektor;
        $this->data['list_pos'] = $list_pos;
        $persenInput = round( $jumSdhInput/(($jmlPNS+$jmlPJLP)*$jumJenisApd)*100,1 );
        $persenVerif = round( $jumVerified/(($jmlPNS+$jmlPJLP)*$jumJenisApd)*100,1 );
        $this->data['data_sudin'] = [ 'pns' => $jmlPNS, 'pjlp' => $jmlPJLP, 'input' => $persenInput, 'verif' => $persenVerif];

        //chart
        $listJenisApd = $this->_get_list_jenis_apd('id_mj,jenis_apd');
        //$listJenisApd = $this->admin_model->get('id_mj,jenis_apd', 'master_jenis_apd', 1, [['deleted', 0]] );
        $i = 0;
        foreach ($list_sektor as $sektor) {
            $jmlASN = $this->_get_users($select1, 3, null, [['master_pos.kode_pos', $sektor['kode'], 'after']], $or_where_arr, null, $joinTable1);
            //$jmlASN = $this->admin_model->get('id', 'users', 3, [['active', 1]], [['master_pos.kode_pos', $sektor['kode'], 'after']], $joinArr, null, null, $or_where_arr );
            $totalAPD = ($jmlASN)*$jumJenisApd;
            foreach ($listJenisApd as $apd) {
                $temp_jumSdhInput = $this->_get_apds($select2, 3, [['master_apd.mj_id', $apd['id_mj'] ], ['progress !=', 0] ], 
                                    [['master_pos.kode_pos', $sektor['kode'], 'after']], $or_where_arr, null, $joinTable2);
                $temp_jumVerified = $this->_get_apds($select2, 3, [['master_apd.mj_id', $apd['id_mj'] ], ['progress', 3] ], 
                                    [['master_pos.kode_pos', $sektor['kode'], 'after']], $or_where_arr, null, $joinTable2);
                /*$temp_jumSdhInput = $this->admin_model->get('apd.id', 'apd', 3, [['mj_id', $apd['id_mj'] ], ['progress !=', 0] ], [['master_pos.kode_pos', $sektor['kode'], 'after']], 
                                    $joinArr1, null, null, $or_where_arr );
                $temp_jumVerified = $this->admin_model->get('apd.id', 'apd', 3, [['mj_id', $apd['id_mj'] ], ['progress', 3] ], [['master_pos.kode_pos', $sektor['kode'], 'after']], 
                                    $joinArr1, null, null, $or_where_arr );*/
                if($totalAPD == 0){
                    $persenSdhInputChart[] = 0;
                    $persenVerifiedChart[] = 0;
                }else{
                    $persenSdhInputChart[] = round( ( ($temp_jumSdhInput/$totalAPD)*100) ,2);
                    $persenVerifiedChart[] = round( (($temp_jumVerified/$totalAPD)*100) ,2);
                }
                if ($i < 1) {
                    $listNamaJenisAPD[] = $apd['jenis_apd'];
                }
            }
            $input_series[] = array(    'name' => $sektor['sektor'], 
                                        'data' => $persenSdhInputChart);
            $verif_series[] = array(    'name' => $sektor['sektor'], 
                                        'data' => $persenVerifiedChart);
            $i ++;
            $persenSdhInputChart = [];
            $persenVerifiedChart = [];
        }

        $this->data['listNamaJenisAPD'] = $listNamaJenisAPD;
        $this->data['input_series'] = $input_series;
        $this->data['verif_series'] = $verif_series;
        $this->data['title_input'] = 'Persentase Input APD (per Jenis APD dan Sektor)';
        $this->data['title_verif'] = 'Persentase APD Terverifikasi (per Jenis APD dan Sektor)';

        $active = $this->active;
		$active['homeMenu'] = 'active-page';
		$this->data['active'] = $active;

        $this->data['apexcharts'] = true;

        $this->data['pageTitle'] = 'Dashboard';
        $this->data['main_content'] = 'admin_sudin/home';
		$this->load->view('admin_sudin/includes/template', $this->data);
        //$this->load->view('admin_sudin/home', $this->data);
    }

    public function profile()
    {
        //$this->data['segmen'] = $this->uri->segment(3);
        $this->data['pageTitle'] = 'Profil'; 
        $data_to_store = '';
        $tes = -1;
        if ($this->input->server('REQUEST_METHOD') === 'POST')
		{
            $tes = 0;
            $segment = $this->uri->segment(3);
            $this->load->helper('date');
            if ($segment == 'profil') {
                //$this->form_validation->set_rules('nama', 'nama', 'required');
                //$this->form_validation->set_error_delimiters('<div class="alert alert-error"><a class="close" data-dismiss="alert">×</a><strong>', '</strong></div>');
                if (true)
                {
                    $my_time = date("Y-m-d H:i:s", now('Asia/Jakarta'));
                    $data_to_store = array(
                        'no_telepon' => isZonk($this->input->post('no_telepon')),
                        'email' => isZonk($this->input->post('email')),
                        'update_date' => $my_time
                    );
                    //upload foto apd user
                    $this->load->library('upload');
                    $upload_APD_path = 'upload/petugas/profil';
                    $config['upload_path']          = FCPATH.$upload_APD_path;
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
                    }
                    if($this->petugas_model->updateData('users', ['id', $this->data['user_id']], $data_to_store)){
                        $this->session->set_flashdata('flash_message', 'sukses');
                    }else{
                        $this->session->set_flashdata('flash_message', 'gagal');
                    }
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
                redirect(''.$this->data['controller'].'/profile');
            }
           
            
            
        }
        $this->data['data_to_store'] = $data_to_store ;
        $this->data['userData'] = $this->_get_users($select, 2, [['users.id', $this->data['user_id']]], null, null, null, ['master_jabatan']);
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
        //$this->data['main_content'] = 'petugas/profile';
        //$this->load->view('petugas/includes/template', $this->data);
        //$this->load->view('petugas/profile', $this->data);
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

    public function verifikasi()
    {
        $this->authenticate();
        $this->data['active'] = array( 'active', '', '', '', '', '', '');
        //$perInput = $this->admin_model->get('periode_input', 'master_state', 2, [['tipe', 'input']]);

        $jab_id_arr = $this->config->item('verifikasi_list');
        foreach ($jab_id_arr as $jab_id) {
            $or_where_arr[] = ['master_jabatan.mc_id', $jab_id];
        }
        /*$joinArr = [['users', 'master_pos', 'master_pos.nama_pos', 'kode_pos_id', 'id_mp' ], ['users', 'master_status', 'master_status.status', 'status_id', 'id_stat' ], 
        ['users', 'master_jabatan', 'master_jabatan.nama_jabatan', 'jabatan_id', 'id_mj' ] ];*/
        $joinTable = ['master_status', 'master_jabatan'];
        $select = 'id, nama, NRK, NIP, photo';
        $listUser = $this->_get_users($select, 1, [['jml_tobe_verified >', 0]], [['master_pos.kode_pos', $this->data['kode_pos'], 'after']], $or_where_arr, null, $joinTable);
        /*$listUser = $this->admin_model->get('id, nama, NRK, NIP, photo', 'users', 1, [['active', 1]], [['master_pos.kode_pos', $this->data['kode_pos'], 'after']], $joinArr,
                                            null, null, $or_where_arr);*/
        /*$ApdUser = [];
        $i = 0;
        foreach ($listUser as $user) {
            $numAPD = $this->_get_apds('id', 3, [['petugas_id', $user['id']], ['progress', 2] ]);
            //$numAPD = $this->admin_model->get('id', 'apd', 3, [['petugas_id', $user['id']], ['progress', 2], ['mj_id !=', 0], ['periode_input', $perInput['periode_input'] ] ]);
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

        $active = $this->active;
		$active['verifikasiMenu'] = 'active-page';
        $active['verifikasi'] = 'active';
		$this->data['active'] = $active;

        $this->data['datatable'] = true;

        $this->data['ApdUser'] = $listUser;
        $this->data['url2'] = 'verifikasiAPD';
        $this->data['action'] = 'Verif';

        $this->data['pageTitle'] = 'Verifkasi & Validasi';
        $this->data['main_content'] = 'admin_sudin/list_verifikasi';
		$this->load->view('admin_sudin/includes/template', $this->data);
    }

    public function verifikasiAPD()
    {
        $this->authenticate();
        $this->load->helper('date');
        $this->load->library('session');

        //$perInput = $this->admin_model->get('periode_input', 'master_state', 2, [['tipe', 'input']]);
        $UserID = $this->uri->segment(3);
        //cek idUser
        /*$joinArr = [['users', 'master_pos', 'master_pos.nama_pos, master_pos.kode_pos', 'kode_pos_id', 'id_mp' ], ['users', 'master_status', 'master_status.status', 'status_id', 'id_stat' ], 
                    ['users', 'master_jabatan', 'master_jabatan.nama_jabatan', 'jabatan_id', 'id_mj' ] ];*/
        $joinTable = ['master_jabatan', 'master_status'];
        $userData = $this->_get_users('id, nama, NRK, NIP, master_pos.kode_pos', 2, [['id', $UserID]], null, null, null, $joinTable);
        //$userData = $this->admin_model->get('id, nama, NRK, NIP', 'users', 2, [['active', 1], ['id', $UserID]], null,  $joinArr);
        if (is_array($userData)) {
            if( (strpos($userData['kode_pos'], $this->data['kode_pos'])) === false || $userData['NRK'] == $this->data['nrk']){
                redirect("my404");
            }
        }
        $UserID = $userData['id'];
        //get role_id user
        $joinArr2 = [['users', 'master_jabatan', 'master_jabatan.mc_id', 'jabatan_id', 'id_mj' ], 
                        ['master_jabatan', 'master_controller', 'master_controller.role_id', 'mc_id', 'id' ] ];
        $role_id_arr = $this->admin_model->get('users.id', 'users', 2, [['users.id', $UserID]], null, $joinArr2);
        $user_role_id = $role_id_arr['role_id'];

        $jumJenisApd = $this->_get_jml_jenis_apd(null, $user_role_id);

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
                /*$jml_belum_verif = $this->_get_apds('id', 3, [['petugas_id', $UserID], ['progress', 2]]);
                $jml_terverif = $this->_get_apds('id', 3, [['petugas_id', $UserID], ['progress', 3]]);
                $jml_ditolak = $this->_get_apds('id', 3, [['petugas_id', $UserID], ['progress', 1]]);
                $persen_input = round( (($jml_belum_verif+$jml_terverif+$jml_ditolak)/$jumJenisApd)*100, 1);
                $persen_tervalidasi = round( ($jml_terverif/$jumJenisApd)*100, 1);
                $data_user = array( 'persen_inputAPD' => $persen_input,
                                    'persen_APDterverif' => $persen_tervalidasi,
                                    'jml_ditolak' => $jml_ditolak
                                );
                $this->admin_model->updateData('users', ['id', $UserID], $data_user);*/
                
                redirect('kasi_sarana/verifikasiAPD/'.$UserID);
            }
        }
        $select = 'id, mkp_id, ukuran, foto_apd, admin_message, apd.keterangan as keterangan_p, created_at, updated_at, master_apd.tahun, master_kondisi.keterangan';
        $joinTable = ['master_merk', 'master_keberadaan', 'master_kondisi'];
        $listAPD = $this->_get_apds($select, 1, [['petugas_id', $UserID], ['progress', 2]], null, null, null, $joinTable);
        /*$listAPD = $this->admin_model->get('id, mkp_id, ukuran, foto_apd, admin_message, apd.keterangan as keterangan_p, created_at, updated_at', 'apd', 1, 
                    [['petugas_id', $UserID], ['progress', 2], ['apd.mj_id !=', 0], ['periode_input', $perInput['periode_input'] ] ], null, 
                    [['apd', 'master_jenis_apd', 'master_jenis_apd.jenis_apd', 'mj_id', 'id_mj' ], ['apd', 'master_apd', 'master_apd.tahun', 'mapd_id', 'id_ma' ], 
                    ['master_apd', 'master_merk', 'master_merk.merk', 'mm_id', 'id_mm' ], ['apd', 'master_keberadaan', 'master_keberadaan.keberadaan', 'mkp_id', 'id_mkp' ], 
                    ['apd', 'master_kondisi', 'master_kondisi.nama_kondisi, master_kondisi.keterangan', 'kondisi_id', 'id_mk' ]]);*/
        //$post = $this->input->post();
        //d($userData,$listAPD, $post);
        $this->data['UserID'] = $UserID;
        $this->data['jumJenisApd'] = $jumJenisApd;
        $this->data['jumApdTerverifikasi'] = $this->_get_apds('id', 3, [['petugas_id', $UserID], ['progress', 3]]);
        $this->data['jumApdDitolak'] = $this->_get_apds('id', 3, [['petugas_id', $UserID], ['progress', 1]]);
        //$this->data['jumApdTerverifikasi'] = $this->admin_model->get('id', 'apd', 3,  [['petugas_id', $UserID], ['progress', 3], ['mj_id !=', 0]]);
        //$this->data['jumApdDitolak'] = $this->admin_model->get('id', 'apd', 3,  [['petugas_id', $UserID], ['progress', 1], ['mj_id !=', 0]]);
        $this->data['userData'] = $userData;
        $this->data['listAPD'] = $listAPD;
        $this->data['dhead_ada'] = array(['Keberadaan', 'keberadaan'], ['Merk', 'merk'], ['Ukuran', 'ukuran'], ['Tahun', 'tahun'], ['Kondisi', 'nama_kondisi'], ['Keterangan Kondisi', 'keterangan'], ['Keterangan Petugas', 'keterangan_p'] );
        $this->data['dhead_hilang'] = array(['keberadaan', 'keberadaan'], ['Tanggal Input', 'created_at'] );

        $active = $this->active;
		$active['verifikasiMenu'] = 'active-page';
        $active['verifikasi'] = 'active';
		$this->data['active'] = $active;

        $this->data['pageTitle'] = 'Verifkasi APD';
        $this->data['main_content'] = 'admin_sudin/verifikasiAPD';
		$this->load->view('admin_sudin/includes/template', $this->data);
    }

    public function tervalidasi()
    {
        $this->authenticate();
        //$perInput = $this->admin_model->get('periode_input', 'master_state', 2, [['tipe', 'input']]);

        /*$search = null;
        if ($this->input->server('REQUEST_METHOD') === 'GET')
		{
            if (! empty($this->input->get('cari'))) {
                $search = $this->input->get('cari'); 
            }
        }*/
        $jab_id_arr = $this->config->item('verifikasi_list');
        foreach ($jab_id_arr as $jab_id) {
            $or_where_arr[] = ['master_jabatan.mc_id', $jab_id];
        }
        /*$joinArr = [['users', 'master_pos', 'master_pos.nama_pos', 'kode_pos_id', 'id_mp' ], ['users', 'master_status', 'master_status.status', 'status_id', 'id_stat' ], 
        ['users', 'master_jabatan', 'master_jabatan.nama_jabatan', 'jabatan_id', 'id_mj' ], ['master_jabatan', 'master_controller', 'master_controller.level', 'mc_id', 'id' ] ];*/
        $order = ['master_controller.level', 'DESC'];
        $select = 'users.id, nama, NRK, NIP, photo';
        $joinTable = ['master_status', 'master_jabatan', 'master_controller'];
        /*if (is_null($search)) {
            $listUser = $this->_get_users($select, 1, [['persen_APDterverif >', 0]], [['master_pos.kode_pos', $this->data['kode_pos'], 'after']], $or_where_arr, null, $joinTable, $order);
            /*$listUser = $this->admin_model->get('users.id, nama, NRK, NIP, photo', 'users', 1, [['active', 1]], [['master_pos.kode_pos', $this->data['kode_pos'], 'after']], $joinArr,
                                            $order, null, $or_where_arr);*/
        /*} else {
            $or_likeArr = [['users.nama', $search], ['users.NRK', $search], ['master_jabatan.nama_jabatan', $search], ['master_pos.nama_pos', $search] ];
            $listUser = $this->_get_users($select, 1, [['persen_APDterverif >', 0]], [['master_pos.kode_pos', $this->data['kode_pos'], 'after']], $or_where_arr, $or_likeArr, $joinTable, $order);
            /*$listUser = $this->admin_model->get('users.id, nama, NRK, NIP, photo', 'users', 1, [['active', 1]], [['master_pos.kode_pos', $this->data['kode_pos'], 'after']], $joinArr,
                                            $order, null, $or_where_arr, $or_likeArr);*/
        //}
        //$this->data['search'] = $search;
        $listUser = $this->_get_users($select, 1, [['persen_APDterverif >', 0]], [['master_pos.kode_pos', $this->data['kode_pos'], 'after']], $or_where_arr, null, $joinTable, $order);


        $ApdUser = $listUser;
        /*foreach ($listUser as $user) {
            $listAPD = $this->_get_apds('id', 3, [['petugas_id', $user['id']], ['progress', 3]]);
            //$listAPD = $this->admin_model->get('id', 'apd', 3, [['petugas_id', $user['id']], ['progress', 3], ['periode_input', $perInput['periode_input'] ] ]);
            if($listAPD > 0){
                $ApdUser[]=  $user;
            }
        }*/
        /*if (is_null($search)) {
            $this->data['section_tittle'] = 'Menampilkan '.count($ApdUser).' data petugas';
        } else {
            $this->data['section_tittle'] = 'Hasil Pencarian kata "'.$search.'" ditemukan '.count($ApdUser).' data';
        }*/
        
        $this->data['ApdUser'] = $ApdUser;
        $this->data['url2'] = 'APDtervalidasi';
        $this->data['action'] = 'Detail';

        $active = $this->active;
		$active['dataMenu'] = 'active-page';
        $active['apdTerverifikasi'] = 'active';
		$this->data['active'] = $active;

        $this->data['datatable'] = true;

        $this->data['pageTitle'] = 'Daftar Terverifikasi';
        $this->data['main_content'] = 'admin_sudin/list_verifikasi';
		$this->load->view('admin_sudin/includes/template', $this->data);
    }

    public function APDtervalidasi()
    {
        $this->authenticate();
        //$this->load->library('session');
        //$this->load->helper('date');
        //$perInput = $this->admin_model->get('periode_input', 'master_state', 2, [['tipe', 'input']]);
        $UserID = $this->uri->segment(3);
        //cek idUser
        $joinTable = ['master_jabatan', 'master_status'];
        $userData = $this->_get_users('id, nama, NRK, NIP, master_pos.kode_pos', 2, [['id', $UserID]], null, null, null, $joinTable);
        //$userData = $this->admin_model->get('id, nama, NRK, NIP', 'users', 2, [['active', 1], ['id', $UserID]], null, [['users', 'master_pos', 'master_pos.kode_pos, master_pos.nama_pos', 'kode_pos_id', 'id_mp' ], ['users', 'master_status', 'master_status.status', 'status_id', 'id_stat' ], ['users', 'master_jabatan', 'master_jabatan.nama_jabatan', 'jabatan_id', 'id_mj' ] ] );
        if (is_array($userData)) {
            if( (strpos($userData['kode_pos'], $this->data['kode_pos'])) === false || $userData['NRK'] == $this->data['nrk']){
                redirect("my404");
            }
        }
        $UserID = $userData['id'];
        $select = 'id, mkp_id, ukuran, foto_apd, admin_message, apd.keterangan as keterangan_petugas, created_at, updated_at, master_apd.tahun, master_kondisi.keterangan';
        $joinTable = ['master_merk', 'master_keberadaan', 'master_kondisi'];
        $listAPD = $this->_get_apds($select, 1, [['petugas_id', $UserID], ['progress', 3]], null, null, null, $joinTable);
        /*$listAPD = $this->admin_model->get('id, mkp_id, ukuran, foto_apd, admin_message, apd.keterangan as keterangan_petugas, created_at, updated_at', 'apd', 1, 
                                        [['petugas_id', $UserID], ['progress', 3], ['apd.mj_id !=', 0], ['periode_input', $perInput['periode_input'] ] ], null, 
                                        [['apd', 'master_jenis_apd', 'master_jenis_apd.jenis_apd', 'mj_id', 'id_mj' ], ['apd', 'master_apd', 'master_apd.tahun', 'mapd_id', 'id_ma' ], 
                                        ['master_apd', 'master_merk', 'master_merk.merk', 'mm_id', 'id_mm' ], 
                                        ['apd', 'master_keberadaan', 'master_keberadaan.keberadaan', 'mkp_id', 'id_mkp' ], 
                                        ['apd', 'master_kondisi', 'master_kondisi.nama_kondisi, master_kondisi.keterangan', 'kondisi_id', 'id_mk' ]]);*/
        //d($userData,$listAPD, $post);
        $this->data['icon'] = ['checkmark', 'success'];
        $this->data['UserID'] = $UserID;

        //get role_id user
        $joinArr2 = [['users', 'master_jabatan', 'master_jabatan.mc_id', 'jabatan_id', 'id_mj' ], 
                        ['master_jabatan', 'master_controller', 'master_controller.role_id', 'mc_id', 'id' ] ];
        $role_id_arr = $this->admin_model->get('users.id', 'users', 2, [['users.id', $UserID]], null, $joinArr2);
        $user_role_id = $role_id_arr['role_id'];
        $this->data['jumJenisApd'] = $this->_get_jml_jenis_apd(null, $user_role_id);
        $this->data['jumInputApd'] = $this->_get_apds('id', 3, [['petugas_id', $UserID], ['progress', 2]]);
        $this->data['jumApdTerverifikasi'] = $this->_get_apds('id', 3, [['petugas_id', $UserID], ['progress', 3]]);
        $this->data['jumApdDitolak'] = $this->_get_apds('id', 3, [['petugas_id', $UserID], ['progress', 1]]);
        /*$this->data['jumInputApd'] = $this->admin_model->get('id', 'apd', 3,  [['petugas_id', $UserID], ['progress', 2], ['mj_id !=', 0]]);
        $this->data['jumApdTerverifikasi'] = $this->admin_model->get('id', 'apd', 3,  [['petugas_id', $UserID], ['progress', 3], ['mj_id !=', 0]]);
        $this->data['jumApdDitolak'] = $this->admin_model->get('id', 'apd', 3,  [['petugas_id', $UserID], ['progress', 1], ['mj_id !=', 0]]);*/
        $this->data['userData'] = $userData;
        $this->data['listAPD'] = $listAPD;
        $this->data['dhead_ada'] = array(['Keberadaan', 'keberadaan'], ['Merk', 'merk'], ['Ukuran', 'ukuran'], ['Tahun', 'tahun'], ['Kondisi', 'nama_kondisi'], ['Keterangan Kondisi', 'keterangan'], ['Keterangan Petugas', 'keterangan_petugas'], ['Pesan Admin', 'admin_message'] );
        $this->data['dhead_hilang'] = array(['keberadaan', 'keberadaan'], ['Tanggal Input', 'created_at'] );

        $active = $this->active;
		$active['dataMenu'] = 'active-page';
        $active['apdTerverifikasi'] = 'active';
		$this->data['active'] = $active;

        $this->data['pageTitle'] = 'APD Terverifikasi';
        $this->data['main_content'] = 'admin_sudin/apd_detail';
		$this->load->view('admin_sudin/includes/template', $this->data);
    }

    public function ditolak()
    {
        $this->authenticate();
        
        /*$perInput = $this->admin_model->get('periode_input', 'master_state', 2, [['tipe', 'input']]);

        $search = null;
        if ($this->input->server('REQUEST_METHOD') === 'GET')
		{
            if (! empty($this->input->get('cari'))) {
                $search = $this->input->get('cari'); 
            }
        }*/
        $jab_id_arr = $this->config->item('verifikasi_list');
        foreach ($jab_id_arr as $jab_id) {
            $or_where_arr[] = ['master_jabatan.mc_id', $jab_id];
        }
        /*$joinArr = [['users', 'master_pos', 'master_pos.nama_pos', 'kode_pos_id', 'id_mp' ], ['users', 'master_status', 'master_status.status', 'status_id', 'id_stat' ], 
        ['users', 'master_jabatan', 'master_jabatan.nama_jabatan', 'jabatan_id', 'id_mj' ], ['master_jabatan', 'master_controller', 'master_controller.level', 'mc_id', 'id' ] ];
        $order = ['master_controller.level', 'DESC'];
        if (is_null($search)) {
            $listUser = $this->admin_model->get('users.id, nama, NRK, NIP, photo', 'users', 1, [['active', 1]], [['master_pos.kode_pos', $this->data['kode_pos'], 'after']], $joinArr,
                                            $order, null, $or_where_arr);
        } else {
            $or_likeArr = [['users.nama', $search], ['users.NRK', $search], ['master_jabatan.nama_jabatan', $search], ['master_pos.nama_pos', $search] ];
            $listUser = $this->admin_model->get('users.id, nama, NRK, NIP, photo', 'users', 1, [['active', 1]], [['master_pos.kode_pos', $this->data['kode_pos'], 'after']], $joinArr,
                                            $order, null, $or_where_arr, $or_likeArr);
        }
        $this->data['search'] = $search;*/
        $select = 'users.id, nama, NRK, NIP, photo';
        $joinTable = ['master_status', 'master_jabatan', 'master_controller'];
        $listUser = $this->_get_users($select, 1, [['users.jml_ditolak >', 0]], [['master_pos.kode_pos', $this->data['kode_pos'], 'after']], $or_where_arr, null, $joinTable);

        $ApdUser = $listUser;
        /*foreach ($listUser as $user) {
            $listAPD = $this->admin_model->get('id', 'apd', 3, [['petugas_id', $user['id']], ['progress', 1], ['periode_input', $perInput['periode_input'] ] ]);
            if($listAPD > 0){
                $ApdUser[]=  $user;
            }
        }
        if (is_null($search)) {
            $this->data['section_tittle'] = 'Menampilkan '.count($ApdUser).' data petugas';
        } else {
            $this->data['section_tittle'] = 'Hasil Pencarian kata "'.$search.'" ditemukan '.count($ApdUser).' data';
        }*/
        //d($ApdUser);
        $this->data['ApdUser'] = $ApdUser;
        $this->data['title'] = array( 'APD Tertolak', 'Daftar APD Pegawai yang ditolak Laporan nya', 'danger');

        $this->data['url2'] = 'APDtertolak';
        $this->data['action'] = 'Detail';

        $active = $this->active;
		$active['dataMenu'] = 'active-page';
        $active['apdTertolak'] = 'active';
		$this->data['active'] = $active;

        $this->data['datatable'] = true;

        $this->data['pageTitle'] = 'Daftar APD Tertolak';
        $this->data['main_content'] = 'admin_sudin/list_verifikasi';
		$this->load->view('admin_sudin/includes/template', $this->data);
    }

    public function APDtertolak()
    {
        $this->authenticate();
        
        $this->load->library('session');
        $this->load->helper('date');
        $perInput = $this->admin_model->get('periode_input', 'master_state', 2, [['tipe', 'input']]);
        $UserID = $this->uri->segment(3);
        //cek idUser
        $userData = $this->admin_model->get('id, nama, NRK, NIP, master_pos.kode_pos', 'users', 2, [['active', 1], ['id', $UserID]], null, [['users', 'master_pos', 'master_pos.nama_pos', 'kode_pos_id', 'id_mp' ], ['users', 'master_status', 'master_status.status', 'status_id', 'id_stat' ], ['users', 'master_jabatan', 'master_jabatan.nama_jabatan', 'jabatan_id', 'id_mj' ] ] );
        if (is_array($userData)) {
            if( (strpos($userData['kode_pos'], $this->data['kode_pos'])) === false || $userData['NRK'] == $this->data['nrk']){
                redirect("my404");
            }
        }
        $UserID = $userData['id'];
        $listAPD = $this->admin_model->get('id, mkp_id, ukuran, foto_apd, admin_message, apd.keterangan as keterangan_petugas, created_at, updated_at', 'apd', 1, [['petugas_id', $UserID], ['progress', 1], ['apd.mj_id !=', 0], ['periode_input', $perInput['periode_input'] ] ], null, [['apd', 'master_jenis_apd', 'master_jenis_apd.jenis_apd', 'mj_id', 'id_mj' ], ['apd', 'master_apd', 'master_apd.tahun', 'mapd_id', 'id_ma' ], ['master_apd', 'master_merk', 'master_merk.merk', 'mm_id', 'id_mm' ], ['apd', 'master_keberadaan', 'master_keberadaan.keberadaan', 'mkp_id', 'id_mkp' ], ['apd', 'master_kondisi', 'master_kondisi.nama_kondisi, master_kondisi.keterangan', 'kondisi_id', 'id_mk' ]]);
        //d($userData,$listAPD, $post);
        $this->data['title'] = 'Daftar APD Tertolak';
        $this->data['icon'] = ['close', 'danger'];
        $this->data['UserID'] = $UserID;

        //get role_id user
        $joinArr2 = [['users', 'master_jabatan', 'master_jabatan.mc_id', 'jabatan_id', 'id_mj' ], 
                        ['master_jabatan', 'master_controller', 'master_controller.role_id', 'mc_id', 'id' ] ];
        $role_id_arr = $this->admin_model->get('users.id', 'users', 2, [['users.id', $UserID]], null, $joinArr2);
        $user_role_id = $role_id_arr['role_id'];
        $this->data['jumJenisApd'] = $this->_get_jml_jenis_apd(null, $user_role_id);
        $this->data['jumInputApd'] = $this->admin_model->get('id', 'apd', 3,  [['petugas_id', $UserID], ['progress', 2], ['mj_id !=', 0]]);
        $this->data['jumApdTerverifikasi'] = $this->admin_model->get('id', 'apd', 3,  [['petugas_id', $UserID], ['progress', 3], ['mj_id !=', 0]]);
        $this->data['jumApdDitolak'] = $this->admin_model->get('id', 'apd', 3,  [['petugas_id', $UserID], ['progress', 1], ['mj_id !=', 0]]);
        $this->data['userData'] = $userData;
        $this->data['listAPD'] = $listAPD;
        $this->data['dhead_ada'] = array(['Keberadaan', 'keberadaan'], ['Merk', 'merk'], ['Ukuran', 'ukuran'], ['Tahun', 'tahun'], ['Kondisi', 'nama_kondisi'], ['Keterangan Kondisi', 'keterangan'], ['Keterangan Petugas', 'keterangan_petugas'], ['Pesan Admin', 'admin_message'] );
        $this->data['dhead_hilang'] = array(['keberadaan', 'keberadaan'], ['Tanggal Input', 'created_at'] );

        $active = $this->active;
		$active['dataMenu'] = 'active-page';
        $active['apdTertolak'] = 'active';
		$this->data['active'] = $active;

        $this->data['pageTitle'] = 'APD Tertolak';
        $this->data['main_content'] = 'admin_sudin/apd_detail';
		$this->load->view('admin_sudin/includes/template', $this->data);
    }

    public function lap_sewaktu()
    {
        $this->authenticate();
        $this->data['pageTitle'] = 'Verifikasi Laporan Sewaktu';
        $this->data['main_content'] = 'petugas/list_lap_sewaktu_kasektor';
		$this->load->view('petugas/includes/template', $this->data);
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
        $jmlPrsnl =$this->_get_users('id', 3, null, [['kode_pos', $this->data['kode_pos'], 'after']], $or_where_arr);
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
        $tgl = $this->admin_model->get('tgl_update', 'master_sudin', 2, [['kode', $this->data['kode_pos_min'] ]]);
        $periode = str_replace(' ', '', $this->data['periode']);        //remove space if exist
        $report_type = ['Rekap APD', 'KIB APD'];
        $file_name1 = 'rekap-apd_'.$this->data['full_kode_pos'].'_'.$periode.'.pdf';
        $file_name2 = 'kib-apd_'.$this->data['full_kode_pos'].'_'.$periode.'.pdf';
        $file_names = [$file_name1, $file_name2];
        //$file_name2 = 'data-apd_'.$this->data['kode_pos'].'_'.$periode;
        //$file_name3 = 'data-petugas_'.$this->data['kode_pos'].'_'.$periode;
        //$file_names = [$file_name1, $file_name2, $file_name3];
        //cek if file exits in database
        $i = 0;
        foreach ($file_names as $file_name) {
            $cek_db = $this->admin_model->get('id', 'report_pdf', 2, [['filename', $file_name]] );
            if (! is_array($cek_db) ) {
                $data = array(  'kode_pos' => $this->data['full_kode_pos'],
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
        $list_report = $this->admin_model->get('*', 'report_pdf', 1, [['kode_pos', $this->data['full_kode_pos']]], null, null, ['create_at', 'DESC']);
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
        
        $active = $this->active;
		$active['laporanMenu'] = 'active-page';
        $active['pdf'] = 'active';
		$this->data['active'] = $active;

        $this->data['datatable'] = true;

        $this->data['list_report'] = $list_report;
        $this->data['pageTitle'] = 'Laporan PDF';
        $this->data['main_content'] = 'admin_sudin/list_pdf';
		$this->load->view('admin_sudin/includes/template', $this->data);
    }

    public function list_pdf1()
    {
        //authentication
        $this->authenticate();
        $this->load->helper('date');
        $my_time = date("Y-m-d H:i:s", now('Asia/Jakarta'));
        $periode = str_replace(' ', '', $this->data['periode']);        //remove space if exist
        $report_type = ['Rekap APD', 'Data APD', 'Data Petugas'];
        $file_name1 = 'rekap-apd_'.$this->data['full_kode_pos'].'_'.$periode.'.pdf';
        $file_names = [$file_name1 ];
        //$file_name2 = 'data-apd_'.$this->data['kode_pos'].'_'.$periode;
        //$file_name3 = 'data-petugas_'.$this->data['kode_pos'].'_'.$periode;
        //$file_names = [$file_name1, $file_name2, $file_name3];
        //cek if file exits in database
        $i = 0;
        foreach ($file_names as $file_name) {
            $cek_db = $this->admin_model->get('id', 'report_pdf', 1, [['filename', $file_name]] );
            if (count($cek_db) < 1 ) {
                $data = array(  'kode_pos' => $this->data['full_kode_pos'],
                                'nama_laporan' => $report_type[$i],
                                'periode' => $this->data['periode'],
                                'filename' => $file_name,
                                'create_at' => $my_time
                        );
                $this->admin_model->insertData('report_pdf', $data);
                $this->create_report_apd_pdf($file_name, sqlDate2htmlDate($my_time));
                
            }else{
                if ($this->data['is_open']) {
                    $data = array(  
                                'create_at' => $my_time
                        );
                    $this->admin_model->updateData('report_pdf', ['id', $cek_db[0]['id']], $data);
                    $this->create_report_apd_pdf($file_name, sqlDate2htmlDate($my_time));
                }
            }
            $i++;
        }
        //$this->create_report_apd_pdf('sdsa');
        $list_report = $this->admin_model->get('*', 'report_pdf', 1, [['kode_pos', $this->data['full_kode_pos']]], null, null, ['create_at', 'DESC']);
        //$cek_db = $this->admin_model->get('id', 'report_pdf', 3, [['filename', $file_name]] );
        
        $active = $this->active;
		$active['laporanMenu'] = 'active-page';
        $active['pdf'] = 'active';
		$this->data['active'] = $active;

        $this->data['datatable'] = true;

        $this->data['list_report'] = $list_report;
        $this->data['pageTitle'] = 'Laporan PDF';
        $this->data['main_content'] = 'admin_sudin/list_pdf';
		$this->load->view('admin_sudin/includes/template', $this->data);
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

        $data_sektor = $this->admin_model->get('jml_pns, jml_pjlp, jml_verif, chart_verif_APD', 'master_sudin', 2, [['kode', $this->data['kode_pos_min'] ]]);
        $chart_verif_APD = json_decode($data_sektor['chart_verif_APD'], true);

        //$rekapAPD = $this->_calculate_rekap_APD();
        //$rekapAPD['jmlJenisAPD'] = $rekapAPD['jmlAPDTervld'] = $rekapAPD['data']['jenis_apd'] = 1;
        $jab_id_arr = $this->config->item('jabID_list_monitoring');
        foreach ($jab_id_arr as $jab_id) {
            $or_where_arr[] = ['jabatan_id', $jab_id];
        }
        //$jmlPrsnl = $this->_get_users('id', 3, null, [['kode_pos', $this->data['kode_pos'], 'after']], $or_where_arr);
        //$jmlPrsnl = $this->admin_model->get('id', 'users', 3, [['active', 1]], [['kode_pos', $this->data['kode_pos'], 'after']], null, null, null, $or_where_arr );
        $jmlPrsnl = $data_sektor['jml_pns'] + $data_sektor['jml_pjlp'];
        $jmltotalAPD = $jmlPrsnl*$this->_get_jml_jenis_apd();
        $subheading1 = ['Sasaran Program', 'Indikator Kinerja', 'Periode input APD', 'Unit Kerja', 'Tanggal Update'];
        //hilangkan kata 'kantor'
        $penempatan1 = substr($this->data['penempatan']['nama_pos'], strpos($this->data['penempatan']['nama_pos'], 'Sektor'));
        $penempatan2 = substr($this->data['penempatan']['nama_pos'], strpos($this->data['penempatan']['nama_pos'], 'sektor'));
        $penempatan = (strlen($penempatan1)<strlen($penempatan2) ) ? $penempatan1 : $penempatan2 ;
        $subheading2 = [$renkin['sasaran'], $renkin['indikator'], $this->data['periode'], $penempatan, $date];
        for ($i=0; $i < count($subheading1) ; $i++) { 
            $pdf->MultiCell(40, 5, $subheading1[$i], 0, 'L', false, 0, '', '', true);
            $pdf->MultiCell(5, 5, ':', 0, 'C', false, 0, '', '', true);
            $pdf->MultiCell(0, 5, $subheading2[$i], 0, 'L', false, 1, '', '', true);
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

        //$pdf->Ln(20);

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

        $data_sektor = $this->admin_model->get('KIB_APD', 'master_sudin', 2, [['kode', $this->data['kode_pos_min'] ]]);
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

    private function _calculate_rekap_pdf()
    {
        $jenisApd = $this->my_apd->get_list_jenis_apd('id_mj, jenis_apd, akronim', $this->data['user_roles'], 1);
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

    public function dataUser()
    {
        //authentication
        $this->authenticate();

        $jab_id_arr = $this->config->item('mcID_list_monitoring');
        foreach ($jab_id_arr as $jab_id) {
            $or_where_arr[] = ['master_jabatan.mc_id', $jab_id];
        }
        /*$joinArr = [['users', 'master_pos', 'master_pos.nama_pos', 'kode_pos', 'kode_pos' ], ['master_pos', 'master_sektor', 'master_sektor.sektor', 'kode_sektor', 'kode' ],
        ['users', 'master_status', 'master_status.status', 'status_id', 'id_stat' ], 
        ['users', 'master_jabatan', 'master_jabatan.nama_jabatan', 'jabatan_id', 'id_mj' ], ['master_jabatan', 'master_controller', 'master_controller.level', 'mc_id', 'id' ] ];
        $order = ['master_controller.level', 'DESC'];
        $listUser = $this->admin_model->get('users.id, nama, NRK, NIP, persen_inputAPD, persen_APDterverif, jml_ditolak', 'users', 1, [['active', 1]], [['users.kode_pos', $this->data['kode_pos'], 'after']], $joinArr,
                                        $order, null, $or_where_arr);

        $this->data['listUser'] = $listUser;*/

        foreach ($jab_id_arr as $key) {
            $listJab[] = $this->admin_model->get('id, deskripsi', 'master_controller', 2, [['id', $key]] );
        }
        $this->data['listJab'] = $listJab;

        $listSektor = $this->admin_model->get('kode, sektor', 'master_sektor', 1, null, [['kode', $this->data['kode_pos'], 'after']] );
        $this->data['listSektor'] = $listSektor;

        $active = $this->active;
		$active['dataMenu'] = 'active-page';
        $active['dataUser'] = 'active';
		$this->data['active'] = $active;

        $this->data['datatable'] = true;
        $this->data['select2'] = true;

        $this->data['pageTitle'] = 'Daftar Petugas';
        $this->data['main_content'] = 'admin_sudin/list_user';
		$this->load->view('admin_sudin/includes/template', $this->data);
    }

    public function penugasan_select2()
	{
        //$get = $this->input->get();
        $search = $this->input->get("search");
        $kode_pos = $this->input->get("kode_pos");
        //$user_id_plt = $this->input->get("user_id_plt");
        if (! empty($search)) {
            if ($kode_pos == 'all') {
                $list_kode_pos = $this->admin_model->get('id_mp as id, nama_pos as text', 'master_pos', 1, null, [['kode_pos', $this->data['kode_pos'], 'after'], ['nama_pos', $search]] );
            }else{
                $list_kode_pos = $this->admin_model->get('id_mp as id, nama_pos as text', 'master_pos', 1, null, [['kode_pos', $kode_pos, 'after'], ['nama_pos', $search]] );
            }
        } else {
            if ($kode_pos == 'all') {
                $list_kode_pos = $this->admin_model->get('id_mp as id, nama_pos as text', 'master_pos', 1, null, [['kode_pos', $this->data['kode_pos'], 'after']] );
            }else{
                $list_kode_pos = $this->admin_model->get('id_mp as id, nama_pos as text', 'master_pos', 1, null, [['kode_pos', $kode_pos, 'after']] );
            }
        }
        
        $default = [["id" => "all", "text" => "Semua"]];
        $result = array_merge($default, $list_kode_pos);
        $result = ['results' => $result];
		echo json_encode($result);
	}

    public function list_user_datatables()
    {
        $draw = intval($this->input->get("draw"));
        $start = intval($this->input->get("start"));
        $length = intval($this->input->get("length"));
        $order = $this->input->get("order");
        $search= $this->input->get("search");
		$search = $search['value'];
        $jabatan= $this->input->get("jabatan");
        $kode_sektor= $this->input->get("sektor");
        $id_pos= $this->input->get("pos");
        
        $col = 0;
        $dir = "ASC";
        if(!empty($order))
        {
            foreach($order as $o)
            {
                $col = $o['column'];
                $dir= $o['dir'];
            }
        }

        if($dir != "asc" && $dir != "desc")
        {
            $dir = "asc";
        }

        
        $valid_columns = array(
            0=> null,
            1=> null,
            2=>'nama',
            3=>'NRK',
            4=>'NIP',
            5=>'master_jabatan.nama_jabatan',
			6=>'master_sektor.sektor',
			7=> 'master_pos.nama_pos',
			8=> 'persen_inputAPD',
			9=> 'persen_APDterverif',
            10=> 'jml_ditolak'
		);
        if(!isset($valid_columns[$col]))
        {
            $order = ['master_controller.level', 'DESC'];
        }
        else
        {
            $order = [$valid_columns[$col], $dir];
        }
        

        $search_columns = array(
            0=>'nama',
            1=>'NRK',
            2=>'NIP',
			3=>'master_jabatan.nama_jabatan',
			4=>'master_sektor.sektor',
			5=>'master_pos.nama_pos'
        );

        $jab_id_arr = $this->config->item('mcID_list_monitoring');
        foreach ($jab_id_arr as $jab_id) {
            $or_where_arr[] = ['master_jabatan.mc_id', $jab_id];
        }
        $joinArr = [['users', 'master_pos', 'master_pos.nama_pos', 'kode_pos_id', 'id_mp' ], ['master_pos', 'master_sektor', 'master_sektor.sektor', 'kode_sektor', 'kode' ],
        ['users', 'master_status', 'master_status.status', 'status_id', 'id_stat' ], 
        ['users', 'master_jabatan', 'master_jabatan.nama_jabatan', 'jabatan_id', 'id_mj' ], ['master_jabatan', 'master_controller', 'master_controller.level', 'mc_id', 'id' ] ];
	   
        $where_array1 = [['active', 1], ['master_jabatan.mc_id !=', 5]];
        $where_array2 = [['master_controller.id', $jabatan]];
        $where = ($jabatan == 'all') ? $where_array1 : array_merge($where_array1, $where_array2) ;

        $like_array1 = [['master_pos.kode_pos', $this->data['kode_pos'], 'after']];
        $like_array2 = [['master_pos.kode_pos', $kode_sektor, 'after']];
        $like = ($kode_sektor == 'all') ? $like_array1 : $like_array2 ;

        $where_array3 = [['master_pos.id_mp', $id_pos]];
        $where = ($id_pos == 'all') ? $where : array_merge($where, $where_array3) ;

        //new
        $where_array4 = [['master_jabatan.plt_id', null]];
        $where = array_merge($where, $where_array4);

		if($length == -1){
            $listUser = $this->admin_model->get_ajax('users.id as user_id, nama, NRK, NIP, persen_inputAPD, persen_APDterverif, users.jml_ditolak', 'users', 1, $where, $like, 
                        $joinArr, $order, null,  $search, $search_columns, $or_where_arr);
		}else{
            $listUser = $this->admin_model->get_ajax('users.id as user_id, nama, NRK, NIP, persen_inputAPD, persen_APDterverif, users.jml_ditolak', 'users', 1, $where, $like, 
                        $joinArr, $order, [$length, $start],  $search, $search_columns, $or_where_arr);
		}

        $no = ($length == -1) ? 1 : $start+1 ;
        $perInput = $this->admin_model->get('periode_input', 'master_state', 2, [['tipe', 'input']]);
        $jumJenisApd = $this->_get_jml_jenis_apd();
        $data = array();
        foreach($listUser as $user)
        {
            $data[]= array(
                '<a href="'.base_url().'kasi_sarana/dataUserDetail/'.$user['user_id'].'" class="btn btn-primary btn-sm" role="button"><i class="fas fa-external-link-alt text-white loader-animation"></i></a>',
                $no,
                $user['nama'],
                $user['NRK'],
                $user['NIP'],
				$user['nama_jabatan'],
				$user['sektor'],
				$user['nama_pos'],
                $user['persen_inputAPD'],
                $user['persen_APDterverif'],
                $user['jml_ditolak']
            );
            $no++; 
		}
		
        $sum_total_list = $this->admin_model->get_ajax('users.id, persen_APDterverif, users.jml_ditolak', 'users', 3, $where, $like, 
                            $joinArr, null, null,  null, null, $or_where_arr);
        $sum_filtered_list = $this->admin_model->get_ajax('users.id, persen_APDterverif, users.jml_ditolak', 'users', 3, $where, $like, 
                            $joinArr, $order, null,  $search, $search_columns, $or_where_arr);
    
        $output = array(
            "draw" => $draw,
            "recordsTotal" => $sum_total_list,
			"recordsFiltered" => $sum_filtered_list,
            "data" => $data,
            "lenght" => $order,
            "jabatan" => $jabatan
        );

        echo json_encode($output);
        exit();
    }

    private function authenticationDetailUser($UserID)
    {
        $joinArr = [['users', 'master_pos', 'master_pos.kode_sektor', 'kode_pos_id', 'id_mp' ]];
        $userData = $this->admin_model->get('id, NRK', 'users', 2, [['id', $UserID]], null, $joinArr );
        if (is_array($userData)) {
            if( (strpos($userData['kode_sektor'], $this->data['kode_pos'])) === false || $userData['NRK'] == $this->data['nrk']){
                redirect("my404");
            }
        } else {
            redirect("my404");
        }
        return $userData['id'];
    }

    public function dataUserDetail()
    {
        //authentication
        $this->authenticate();
        $UserID = $this->uri->segment(3);
        //cek idUser
        /*$userData = $this->admin_model->get('id, nama, NRK, NIP, users.kode_pos', 'users', 2, [['active', 1], ['id', $UserID]], null, [['users', 'master_pos', 'master_pos.nama_pos', 'kode_pos', 'kode_pos' ], ['users', 'master_status', 'master_status.status', 'status_id', 'id_stat' ], ['users', 'master_jabatan', 'master_jabatan.nama_jabatan', 'jabatan_id', 'id_mj' ] ] );
        if (is_array($userData)) {
            if( (strpos($userData['kode_pos'], $this->data['kode_pos'])) === false || $userData['NRK'] == $this->data['nrk']){
                redirect("my404");
            }
        }*/
        $UserID = $this->authenticationDetailUser($UserID);

        $joinArr = [['users', 'master_pos', 'master_pos.nama_pos', 'kode_pos_id', 'id_mp' ], 
                    ['users', 'master_status', 'master_status.status', 'status_id', 'id_stat' ], 
                    ['users', 'master_jabatan', 'master_jabatan.nama_jabatan', 'jabatan_id', 'id_mj' ] ];
        $select =   'id, nama, photo, NIP, NRK, users.no_telepon, email, tgl_lahir, berat_badan, tinggi_badan, uk_baret, uk_kaos, uk_baju, uk_celana, uk_jaket, uk_sepatu, uk_gloves,
                    persen_inputAPD, persen_APDterverif, users.jml_ditolak';
        $userData = $this->admin_model->get($select, 'users', 2, [['active', 1], ['id', $UserID]], null, $joinArr );
        $this->data['userData'] = $userData;

        /*$list_jenisAPD = $this->_get_list_jenis_apd('id_mj, jenis_apd');
        //$list_jenisAPD = $this->admin_model->get('id_mj, jenis_apd', 'master_jenis_apd', 1, [['deleted', 0]] );
        foreach ($list_jenisAPD as $jenisAPD) {
            $joinArr1 = [['apd', 'master_jenis_apd', 'master_jenis_apd.jenis_apd', 'mj_id', 'id_mj' ], 
                        ['apd', 'master_apd', 'master_apd.tahun', 'mapd_id', 'id_ma' ], 
                        ['master_apd', 'master_merk', 'master_merk.merk', 'mm_id', 'id_mm' ], 
                        ['apd', 'master_keberadaan', 'master_keberadaan.keberadaan', 'mkp_id', 'id_mkp' ], 
                        ['apd', 'master_kondisi', 'master_kondisi.nama_kondisi, master_kondisi.keterangan', 'kondisi_id', 'id_mk' ],
                        ['apd', 'master_progress_status', 'master_progress_status.deskripsi', 'progress', 'id_mps' ]];
            $APD = $this->admin_model->get('id, mkp_id, ukuran, foto_apd, admin_message, apd.keterangan as keterangan_petugas, created_at, updated_at', 'apd', 2, [['petugas_id', $UserID], ['apd.mj_id', $jenisAPD['id_mj']], ['periode_input', $this->data['periode'] ] ], null, $joinArr1);
            if (is_array($APD)) {
                $dataAPD[] = array( 'jenis' => $jenisAPD['jenis_apd'], 
                                    'data' => $APD['deskripsi'],
                                    'keberadaan' => $APD['keberadaan'],
                                    'kondisi' => $APD['nama_kondisi'],
                            );
            }else{
                $dataAPD[] = array( 'jenis' => $jenisAPD['jenis_apd'], 
                                    'data' => 'Belum Input',
                                    'keberadaan' => '',
                                    'kondisi' => '',
                            );
            }
        }
        $this->data['dataAPD'] = $dataAPD;*/
        $this->data['UserID'] = $UserID;


        $active = $this->active;
		$active['dataMenu'] = 'active-page';
        $active['dataUser'] = 'active';
		$this->data['active'] = $active;

        $this->data['datatable'] = true;
        $this->data['crud_reset_apd'] = true;
        $this->data['csrf_name'] = $this->security->get_csrf_token_name();

        $this->data['pageTitle'] = 'Data Petugas';
        $this->data['main_content'] = 'admin_sudin/detail_user';
		$this->load->view('admin_sudin/includes/template', $this->data);
    }

    public function user_setting()
    {
        //authentication
        $this->authenticate();

        $jab_id_arr = $this->config->item('mcID_list_monitoring');
        foreach ($jab_id_arr as $jab_id) {
            $or_where_arr[] = ['master_jabatan.mc_id', $jab_id];
        }
        /*$joinArr = [['users', 'master_pos', 'master_pos.nama_pos', 'kode_pos', 'kode_pos' ], ['master_pos', 'master_sektor', 'master_sektor.sektor', 'kode_sektor', 'kode' ],
        ['users', 'master_status', 'master_status.status', 'status_id', 'id_stat' ], 
        ['users', 'master_jabatan', 'master_jabatan.nama_jabatan', 'jabatan_id', 'id_mj' ], ['master_jabatan', 'master_controller', 'master_controller.level', 'mc_id', 'id' ] ];
        $order = ['master_controller.level', 'DESC'];
        $listUser = $this->admin_model->get('users.id, nama, NRK, NIP, active', 'users', 1, null, [['users.kode_pos', $this->data['kode_pos'], 'after']], $joinArr,
                                        $order, null, $or_where_arr);
        $this->data['listUser'] = $listUser;*/

        foreach ($jab_id_arr as $key) {
            $listJab[] = $this->admin_model->get('id, deskripsi', 'master_controller', 2, [['id', $key]] );
        }
        $this->data['listJab'] = $listJab;

        $listSektor = $this->admin_model->get('kode, sektor', 'master_sektor', 1, null, [['kode', $this->data['kode_pos'], 'after']] );
        $this->data['listSektor'] = $listSektor;

        $active = $this->active;
		$active['setting'] = 'active-page';
        $active['user_setting'] = 'active';
		$this->data['active'] = $active;

        $this->data['datatable'] = true;
        $this->data['select2'] = true;
        //$this->data['crud_master_merk'] = true;
        $this->data['crud_delete_user'] = true;
        $this->data['csrf_name'] = $this->security->get_csrf_token_name();

        $this->data['pageTitle'] = 'Daftar Petugas';
        $this->data['main_content'] = 'admin_sudin/list_user_setting';
		$this->load->view('admin_sudin/includes/template', $this->data);
    }

    public function list_petugas_datatables()
    {
        $draw = intval($this->input->get("draw"));
        $start = intval($this->input->get("start"));
        $length = intval($this->input->get("length"));
        $order = $this->input->get("order");
        $search= $this->input->get("search");
		$search = $search['value'];
        $jabatan= $this->input->get("jabatan");
        $kode_sektor= $this->input->get("sektor");
        $id_pos= $this->input->get("pos");
        
        $col = 0;
        $dir = "ASC";
        if(!empty($order))
        {
            foreach($order as $o)
            {
                $col = $o['column'];
                $dir= $o['dir'];
            }
        }

        if($dir != "asc" && $dir != "desc")
        {
            $dir = "asc";
        }

        
        $valid_columns = array(
            0=> null,
            1=> null,
            2=>'nama',
            3=>'NRK',
            4=>'NIP',
            5=>'master_jabatan.nama_jabatan',
			6=>'master_sektor.sektor',
			7=> 'master_pos.nama_pos',
			8=> 'master_status.status',
            9=> null,
			10=> 'active'
		);
        if(!isset($valid_columns[$col]))
        {
            $order = ['master_controller.level', 'DESC'];
        }
        else
        {
            $order = [$valid_columns[$col], $dir];
        }
        

        $search_columns = array(
            0=>'nama',
            1=>'NRK',
            2=>'NIP',
			3=>'master_jabatan.nama_jabatan',
			4=>'master_sektor.sektor',
			5=>'master_pos.nama_pos',
            6=>'master_status.status'
        );

        $jab_id_arr = $this->config->item('mcID_list_monitoring');
        foreach ($jab_id_arr as $jab_id) {
            $or_where_arr[] = ['master_jabatan.mc_id', $jab_id];
        }
        $joinArr = [['users', 'master_pos', 'master_pos.nama_pos', 'kode_pos_id', 'id_mp' ], ['master_pos', 'master_sektor', 'master_sektor.sektor', 'kode_sektor', 'kode' ],
        ['users', 'master_status', 'master_status.status', 'status_id', 'id_stat' ], 
        ['users', 'master_jabatan', 'master_jabatan.nama_jabatan', 'jabatan_id', 'id_mj' ], ['master_jabatan', 'master_controller', 'master_controller.level', 'mc_id', 'id' ],
        ['users', 'master_group_piket', 'master_group_piket.kode_piket', 'group_piket_id', 'id' ], ];
	   
        $where_array1 = null;
        $where_array2 = [['master_controller.id', $jabatan]];
        $where = ($jabatan == 'all') ? $where_array1 : $where_array2 ;

        $like_array1 = [['master_pos.kode_pos', $this->data['kode_pos'], 'after']];
        $like_array2 = [['master_pos.kode_pos', $kode_sektor, 'after']];
        $like = ($kode_sektor == 'all') ? $like_array1 : $like_array2 ;

        $where_array3 = [['master_pos.id_mp', $id_pos]];
        if (is_null($where)) {
            $where = ($id_pos == 'all') ? $where : $where_array3 ;
        } else {
            $where = ($id_pos == 'all') ? $where : array_merge($where, $where_array3) ;
        }
        
        //$where = ($id_pos == 'all') ? $where : array_merge($where, $where_array3) ;

		if($length == -1){
            $listUser = $this->admin_model->get_ajax('users.id as user_id, nama, NRK, NIP, active', 'users', 1, $where, $like, 
                        $joinArr, $order, null,  $search, $search_columns, $or_where_arr);
		}else{
            $listUser = $this->admin_model->get_ajax('users.id as user_id, nama, NRK, NIP, active', 'users', 1, $where, $like, 
                        $joinArr, $order, [$length, $start],  $search, $search_columns, $or_where_arr);
		}

        $no = ($length == -1) ? 1 : $start+1 ;
        $perInput = $this->admin_model->get('periode_input', 'master_state', 2, [['tipe', 'input']]);
        $jumJenisApd = $this->_get_jml_jenis_apd();
        $data = array();
        foreach($listUser as $user)
        {
            $retVal = ($user['active'] == 1) ? 'Aktif' : 'Non-aktif' ;
            $edit_button = '<div class="btn-group" role="group" aria-label="Basic example">
                <a href="'.base_url().'kasi_sarana/settingUserDetail/'.$user['user_id'].'" class="btn btn-primary btn-sm" 
                role="button" data-toggle="tooltip" title="Edit"><i class="fas fa-external-link-alt text-white loader-animation"></i></a>
                <a href="javascript:void(0)" class="btn btn-success btn-sm" role="button" onclick="reset_pwd_user('."'".$user['user_id']."'".')" data-toggle="tooltip" title="Reset Password"><i class="fas fa-sync-alt"></i></a>
                <a href="javascript:void(0)" class="btn btn-danger btn-sm" role="button" onclick="delete_user('."'".$user['user_id']."'".')" data-toggle="tooltip" title="Delete"><i class="far fa-trash-alt"></i></a>
                </div>';
            $edit_button_plt = '<a href="javascript:void(0)" class="btn btn-success btn-sm" role="button" onclick="reset_pwd_user('."'".$user['user_id']."'".')" data-toggle="tooltip" title="Reset Password"><i class="fas fa-sync-alt"></i></a>
                ';
            $is_edit = (strpos($user['NRK'], 'plt-') !== false) ? $edit_button_plt : $edit_button ;
            $nama_jabatan = (empty($is_edit)) ? 'plt-'.$user['nama_jabatan'] : $user['nama_jabatan'] ;
            $data[]= array(
                $is_edit,
                $no,
                $user['nama'],
                $user['NRK'],
                $user['NIP'],
				$nama_jabatan,
				$user['sektor'],
				$user['nama_pos'],
                $user['status'],
                $user['kode_piket'],
                $retVal
            );
            $no++; 
		}
		
        $sum_total_list = $this->admin_model->get_ajax('users.id', 'users', 3, $where, $like, 
                            $joinArr, null, null,  null, null, $or_where_arr);
        $sum_filtered_list = $this->admin_model->get_ajax('users.id', 'users', 3, $where, $like, 
                            $joinArr, $order, null,  $search, $search_columns, $or_where_arr);
    
        $output = array(
            "draw" => $draw,
            "recordsTotal" => $sum_total_list,
			"recordsFiltered" => $sum_filtered_list,
            "data" => $data,
            "lenght" => $order,
            "jabatan" => $jabatan
        );

        echo json_encode($output);
        exit();
    }

    public function settingUserDetail()
    {
        //authentication
        $this->authenticate();
        $UserID = $this->uri->segment(3);
        $UserID = $this->authenticationDetailUser($UserID);
        $this->load->helper('date');
        $this->load->library('session');

        if ($this->input->server('REQUEST_METHOD') === 'POST')
  		{
            $this->form_validation->set_rules('nama', 'nama', 'required');
            $this->form_validation->set_rules('NRK', 'NRK', 'required');
            $this->form_validation->set_rules('status_id', 'status_id', 'required');
            $this->form_validation->set_rules('jabatan_id', 'jabatan_id', 'required');
            $this->form_validation->set_rules('kode_pos_id', 'kode_pos_id', 'required');
            $this->form_validation->set_rules('group_piket_id', 'group_piket_id', 'required');
            if ( $this->form_validation->run() )
            {
                $id = $this->input->post('apd_id');
                $progress = ($this->input->post('verifikasi') == 1) ? 3 : 1 ;
                $my_time = date("Y-m-d H:i:s", now('Asia/Jakarta'));
                $active = $this->input->post('active');
                $jabatan_id = $this->input->post('jabatan_id');
                $is_eselon = $this->admin_model->get('is_eselon', 'master_jabatan', 2, [['id_mj', $jabatan_id]]);
                $data = array(  'nama' => $this->input->post('nama'),
                                'NRK' => $this->input->post('NRK'),
                                'NIP' => $this->input->post('NIP'),
                                'status_id' => $this->input->post('status_id'),
                                'kode_pos_id' => $this->input->post('kode_pos_id'),
                                'group_piket_id' => $this->input->post('group_piket_id'),
                                'active' => $active
                            );
                if ($active == '0' && $is_eselon['is_eselon'] == 1) {
                    $data['jabatan_id'] = 111;
                } else {
                    $data['jabatan_id'] = $jabatan_id;
                }

                if ($active == '1' && $jabatan_id == 111) {
                    //$data['active'] = 0;
                    $save = false;
                } else {
                    //$data['active'] = $active;
                    $save = true;
                }
                
                if ($save) {
                    if($this->admin_model->updateData('users', ['id', $UserID], $data))
                    {
                        $this->session->set_flashdata('flash_message', 'sukses');
                    }else{
                        $this->session->set_flashdata('flash_message', 'gagal');
                    }
                    //cek jabatan availibility
                    $list_jab = $this->admin_model->get('id_mj, nama_jabatan', 'master_jabatan', 1, [['is_eselon', 1]], [['kode_panggil', $this->data['kode_pos'], 'after']] );
                    //$joinArrUser = [['users', 'master_pos', 'master_pos.kode_pos', 'kode_pos_id', 'id_mp' ]];
                    foreach ($list_jab as $jab) {
                        $numUser = $this->admin_model->get('id', 'users', 3, [['jabatan_id', $jab['id_mj']]] );
                        if($numUser > 0)
                        {
                            $dataJab = ['is_taken' => 1];
                            $this->admin_model->updateData('master_jabatan', ['id_mj', $jab['id_mj']], $dataJab);
                        }else {
                            $dataJab = ['is_taken' => 0];
                            $this->admin_model->updateData('master_jabatan', ['id_mj', $jab['id_mj']], $dataJab);
                        }
                        //$kuea[] = ['id_mj' => $jab['id_mj'], 'jabatan' => $jab['nama_jabatan'], 'jml' => $numUser];
                    }
                }else{
                    $this->session->set_flashdata('flash_message', 'gagal');
                }
                redirect('kasi_sarana/settingUserDetail/'.$UserID);
            }
        }
        //$this->data['kuea'] = $data;
        $joinArr = [['users', 'master_jabatan', 'master_jabatan.nama_jabatan', 'jabatan_id', 'id_mj' ], ['users', 'master_pos', 'master_pos.nama_pos', 'kode_pos_id', 'id_mp' ] ];
        $select =   'id, nama, photo, NIP, NRK, master_pos.kode_pos, group_piket_id, jabatan_id, kode_pos_id, status_id, active';
        $userData = $this->admin_model->get($select, 'users', 2, [['id', $UserID]], null, $joinArr );
        $this->data['userData'] = $userData;

        $this->data['list_group_piket'] = $this->admin_model->get('*', 'master_group_piket', 1 );
        $joinArrPos = [['master_pos', 'master_sektor', 'master_sektor.sektor', 'kode_sektor', 'kode' ]];
        $this->data['list_pos'] = $this->admin_model->get('id_mp, kode_pos, nama_pos', 'master_pos', 1, [['master_pos.deleted', 0]], [['kode_pos', $this->data['kode_pos'], 'after']], $joinArrPos );
        /*$jab_id_arr = $this->config->item('list_jab_pns');
        foreach ($jab_id_arr as $jab_id) {
            $or_where_arr[] = ['master_jabatan.mc_id', $jab_id];
        }
        $this->data['list_jabatan'] = $this->admin_model->get('id_mj, nama_jabatan, mc_id', 'master_jabatan', 1, [['is_taken', 0]], [['kode_panggil', $this->data['kode_pos'], 'after']], null, null, null, $or_where_arr);
        */
        $this->data['list_status'] = $this->admin_model->get('id_stat, status', 'master_status', 1 );

        $active = $this->active;
		$active['setting'] = 'active-page';
        $active['user_setting'] = 'active';
		$this->data['active'] = $active;

        $this->data['select2'] = true;
        $this->data['pageTitle'] = 'Data Petugas';
        $this->data['main_content'] = 'admin_sudin/detail_user_setting';
		$this->load->view('admin_sudin/includes/template', $this->data);
    }


    public function list_jabatan_select2()
	{
        //$get = $this->input->get();
        $search = $this->input->get("search");
        $id = $this->input->get("id");
        $jabID = $this->input->get("jabid");

        if ($id == 0) {
            $jab_id_arr = $this->config->item('list_jab_pns');
            foreach ($jab_id_arr as $jab_id) {
                $or_where_arr[] = ['master_jabatan.mc_id', $jab_id];
            }
        } else {
            $jab_id_arr = $this->config->item('list_jab_nonpns');
            foreach ($jab_id_arr as $jab_id) {
                $or_where_arr[] = ['master_jabatan.mc_id', $jab_id];
            }
        }
        
        if(!empty($search))
		{
            $list_jabatan_es = $this->admin_model->get('id_mj, nama_jabatan, mc_id', 'master_jabatan', 1, [['is_eselon', 1], ['is_taken', 0], ['plt_id', null]], [['nama_jabatan', $search], ['kode_panggil', $this->data['kode_pos'], 'after']], null, null, $or_where_arr);
            $list_jabatan_non_es = $this->admin_model->get('id_mj, nama_jabatan, mc_id', 'master_jabatan', 1, [['is_eselon', 0], ['is_sektor', 1]], [['nama_jabatan', $search]]);
        }else {
            $list_jabatan_es = $this->admin_model->get('id_mj, nama_jabatan, mc_id', 'master_jabatan', 1, [['is_eselon', 1], ['is_taken', 0], ['plt_id', null]], [['kode_panggil', $this->data['kode_pos'], 'after']], null, null, null, $or_where_arr);
            $list_jabatan_non_es = $this->admin_model->get('id_mj, nama_jabatan, mc_id', 'master_jabatan', 1, [['is_eselon', 0], ['is_sektor', 1]]);
        }
        $list_jabatan = array_merge($list_jabatan_es, $list_jabatan_non_es);

        foreach($list_jabatan as $rows)
        {
            $data[]= array(
                'id' => $rows['id_mj'],
                'text' => $rows['nama_jabatan']
            );
        }
        $result = ['results' => $data];
		echo json_encode($result);
	}


    public function plt_setting()
    {
        //authentication
        $this->authenticate();

        $joinArrJab = [['master_jabatan', 'users', 'users.id, users.nama, users.NRK, users.NIP', 'plt_id', 'id' ]];
        $list_jab = $this->admin_model->get('id_mj, nama_jabatan, kode_panggil, keterangan', 'master_jabatan', 1, [['is_eselon', 1], ['is_taken', 0]], 
                    [['kode_panggil', $this->data['kode_pos'], 'after']], $joinArrJab );
        $this->data['list_jab'] = $list_jab;

        $active = $this->active;
		$active['setting'] = 'active-page';
        $active['plt_setting'] = 'active';
		$this->data['active'] = $active;

        $this->data['datatable'] = true;

        $this->data['pageTitle'] = 'Setting PLT Pejabat Eselon 4';
        $this->data['main_content'] = 'admin_sudin/list_plt';
		$this->load->view('admin_sudin/includes/template', $this->data);
    }

    public function detail_plt()
    {
        $this->authenticate();
        $jab_id = $this->uri->segment(3);
        //authentication
        $joinArr = [['master_jabatan', 'master_pos', 'master_pos.id_mp', 'kode_panggil', 'kode_pos' ]];
        $data_jab = $this->admin_model->get('id_mj, plt_id', 'master_jabatan', 2, [['id_mj', $jab_id], ['is_eselon', 1], ['is_taken', 0]], 
        [['kode_panggil', $this->data['kode_pos'], 'after']], $joinArr );
        if (is_array($data_jab)) {
            $jab_id = $data_jab['id_mj'];
        } else {
            redirect("my404"); 
        }

        if ($this->input->server('REQUEST_METHOD') === 'POST')
  		{
            $this->form_validation->set_rules('plt', 'plt', 'required');
            if ( $this->form_validation->run() )
            {
                $user_id_plt = $this->input->post('plt');
                $retVal = ($user_id_plt == 0) ? null : $user_id_plt ;
                //cek if plt already define
                if (!is_null($data_jab['plt_id']) && $data_jab['plt_id'] != $retVal) {
                    $nrk = $this->admin_model->get('NRK', 'users', 2, [['id', $data_jab['plt_id'] ]]);
                    $this->admin_model->hard_delete('users', 'NRK', 'plt-'.$nrk['NRK']);
                }
                $user = $this->_get_users('NRK, password, nama, NIP, jabatan_id, kode_pos_id, status_id, users.deleted', 2, [['id', $retVal]]);
                $data_jab_to_store = array('plt_id' => $retVal );

                if (!is_null($retVal) && is_array($user) && $retVal != $data_jab['plt_id']) {
                    $plt_nrk = 'plt-'.$user['NRK'];
                    $data_user = array( 'NRK' => $plt_nrk,
                                        'password' => $user['password'],
                                        'nama' => $user['nama'],
                                        'NIP' => $user['NIP'],
                                        'jabatan_id' => $data_jab['id_mj'],
                                        'kode_pos_id' => $data_jab['id_mp'],
                                        'status_id' => $user['status_id'],
                                        'deleted' => 0 );
                    $this->admin_model->insertData('users', $data_user);
                } elseif (is_null($retVal) && !is_null($data_jab['plt_id'])) {
                    $nrk = $this->admin_model->get('NRK', 'users', 2, [['id', $data_jab['plt_id'] ]]);
                    $this->admin_model->hard_delete('users', 'NRK', 'plt-'.$nrk['NRK']);
                }

                if($this->admin_model->updateData('master_jabatan', ['id_mj', $jab_id], $data_jab_to_store) )
                {
                    $this->session->set_flashdata('flash_message', 'sukses');
                }else{
                    $this->session->set_flashdata('flash_message', 'gagal');
                }
            }else{
                $this->session->set_flashdata('flash_message', 'gagal');
            }
            redirect('kasi_sarana/detail_plt/'.$jab_id);
        }

        $joinArrJab = [['master_jabatan', 'users', 'users.nama, users.NRK, users.NIP', 'plt_id', 'id' ]];
        $this->data['data_jabatan'] = $this->admin_model->get('id_mj, nama_jabatan, kode_panggil, keterangan, plt_id', 'master_jabatan', 2, [['id_mj', $jab_id]], null, $joinArrJab);

        $active = $this->active;
		$active['setting'] = 'active-page';
        $active['plt_setting'] = 'active';
		$this->data['active'] = $active;

        $this->data['select2'] = true;

        $this->data['pageTitle'] = 'Setting PLT Pejabat Eselon 4';
        $this->data['main_content'] = 'admin_sudin/setting_plt';
		$this->load->view('admin_sudin/includes/template', $this->data);
    }

    public function set_plt_select2()
	{
        //$get = $this->input->get();
        $search = $this->input->get("search");
        //$id = $this->input->get("id");
        //$user_id_plt = $this->input->get("user_id_plt");
        
        $joinArrJab = [['users', 'master_jabatan', 'master_jabatan.nama_jabatan, master_jabatan.id_mj', 'jabatan_id', 'id_mj' ]];
        if(!empty($search))
		{
            $or_likeArr = [['nama', $search], ['NRK', $search], ['NIP', $search]];
            $list_pejabat = $this->admin_model->get('id, nama, NRK, NIP', 'users', 1, [['active', 1], ['eselon', 'IV']], [['kode_panggil', $this->data['kode_pos'], 'after']], 
                            $joinArrJab, null, null,null, $or_likeArr);
        }else {
            $list_pejabat = $this->admin_model->get('id, nama, NRK, NIP', 'users', 1, [['active', 1], ['eselon', 'IV']], [['kode_panggil', $this->data['kode_pos'], 'after']], 
                            $joinArrJab);
        }

        $data[] = array(
            'id' => 0,
            'text' => 'tidak ada'
        );
        foreach($list_pejabat as $rows)
        {
            $data[]= array(
                'id' => $rows['id'],
                'text' => $rows['nama'].' ('.$rows['NRK'].'/ '.$rows['NIP'].')',
            );
        }
        $result = ['results' => $data];
		echo json_encode($result);
	}

    private function _calculate_rekap_APD()
    {
        $jenisApd = $this->_get_list_jenis_apd('id_mj, jenis_apd, akronim');
        //$jenisApd = $this->my_apd->get_list_jenis_apd('id_mj, jenis_apd, akronim', $this->data['user_roles'], 1);
        $list_mc_id_bawahan = $this->config->item('mcID_list_monitoring');
        //$list_jab_id_bawahan[] = $this->data['jab_id'];
        $data = [];
        $tot = $stotbelum = $stothilang = $stotbaik = $stotrr = $stotrs = $stotrb = 0;
        foreach ($jenisApd as $apd) {
            //$akronim[] = $apd['akronim'];
            //$jenis_apd[] = $apd['jenis_apd'];
            $temp_belum = $this->my_apd->get_report_admin_sudin($apd['id_mj'], 'belum', null, null, $this->data['kode_pos'], $list_mc_id_bawahan, 3);
            $temp_hilang = $this->my_apd->get_report_admin_sudin($apd['id_mj'], 'hilang', null, null, $this->data['kode_pos'], $list_mc_id_bawahan, 3);
            $temp_baik = $this->my_apd->get_report_admin_sudin($apd['id_mj'], null, 4, null, $this->data['kode_pos'], $list_mc_id_bawahan, 3);
            $temp_rr = $this->my_apd->get_report_admin_sudin($apd['id_mj'], null, 3, null, $this->data['kode_pos'], $list_mc_id_bawahan, 3);
            $temp_rs = $this->my_apd->get_report_admin_sudin($apd['id_mj'], null, 2, null, $this->data['kode_pos'], $list_mc_id_bawahan, 3);
            $temp_rb = $this->my_apd->get_report_admin_sudin($apd['id_mj'], null, 1, null, $this->data['kode_pos'], $list_mc_id_bawahan, 3);
            $temp_stot = $temp_belum+$temp_hilang+$temp_baik+$temp_rr+$temp_rs+$temp_rb;
            $stotbelum += $temp_belum;
            $stothilang += $temp_hilang;
            //$stotk[] = $temp_belum+$temp_hilang;
            $stotbaik += $temp_baik;
            $stotrr += $temp_rr;
            $stotrs += $temp_rs;
            $stotrb += $temp_rb;
            $baik = ['val' => $temp_baik, 'id_mj' => $apd['id_mj'], 'tipe' => 'kondisi', 'par' => 4];
            $rr = ['val' => $temp_rr, 'id_mj' => $apd['id_mj'], 'tipe' => 'kondisi', 'par' => 3];
            $rs = ['val' => $temp_rs, 'id_mj' => $apd['id_mj'], 'tipe' => 'kondisi', 'par' => 2];
            $rb = ['val' => $temp_rb, 'id_mj' => $apd['id_mj'], 'tipe' => 'kondisi', 'par' => 1];
            $belum = ['val' => $temp_belum, 'id_mj' => $apd['id_mj'], 'tipe' => 'keberadaan', 'par' => 'belum'];
            $hilang = ['val' => $temp_hilang, 'id_mj' => $apd['id_mj'], 'tipe' => 'keberadaan', 'par' => 'hilang'];
            $stot = ['val' => $temp_stot, 'id_mj' => $apd['id_mj'], 'tipe' => 'all', 'par' => 'all'];
            //$stote[] = $temp_baik+$temp_rr+$temp_rs+$temp_rb; 
            
            $data[] = [ $apd['jenis_apd'], $baik, $rr, $rs, $rb, $belum, $hilang, $stot];
            $tot = $tot + $stot['val'];
        }
        $stotbaik = ['val' => $stotbaik, 'id_mj' => 'all', 'tipe' => 'kondisi', 'par' => 4];
        $stotrr = ['val' => $stotrr, 'id_mj' => 'all', 'tipe' => 'kondisi', 'par' => 3];
        $stotrs = ['val' => $stotrs, 'id_mj' => 'all', 'tipe' => 'kondisi', 'par' => 2];
        $stotrb = ['val' => $stotrb, 'id_mj' => 'all', 'tipe' => 'kondisi', 'par' => 1];
        $stotbelum = ['val' => $stotbelum, 'id_mj' => 'all', 'tipe' => 'keberadaan', 'par' => 'belum'];
        $stothilang = ['val' => $stothilang, 'id_mj' => 'all', 'tipe' => 'keberadaan', 'par' => 'hilang'];
        $tot = ['val' => $tot, 'id_mj' => 'all', 'tipe' => 'all', 'par' => 'all'];
        $subTotal = [ 'subTotal', $stotbaik, $stotrr, $stotrs, $stotrb, $stotbelum, $stothilang, $tot];
        /*$data = array('title' => 'Chart rekap data APD', 'group' => $this->data['penempatan']['nama_pos'], 
                                'akronim' => $akronim, 'jenis_apd' => $jenis_apd, 'belum' => $belum, 'hilang' => $hilang, 'baik' => $baik, 
                                'rr' => $rr, 'rs' => $rs, 'rb' => $rb, 'margin' => 250, 'stotk' => $stotk, 'stote' => $stote, 'stot' => $stot);*/
        $result = ['data' => $data, 'subtotal' => $subTotal];
        return $result;
    }

    public function rekap_report()
    {
        $this->load->helper('date');
        $this->data['my_time'] = date("Y-m-d H:i:s", now('Asia/Jakarta'));
        $this->authenticate();
        $active = $this->active;
		$active['laporanMenu'] = 'active-page';
        $active['rekap'] = 'active';
		$this->data['active'] = $active;

        $this->data['result'] = $this->_calculate_rekap_APD();

        $this->data['pageTitle'] = 'Laporan Rekapitulasi APD';
        $this->data['main_content'] = 'admin_sudin/report_rekap';
		$this->load->view('admin_sudin/includes/template', $this->data);
    }

    public function report_rekap_detail()
    {
        $this->authenticate();
        $active = $this->active;
		$active['laporanMenu'] = 'active-page';
        $active['rekap'] = 'active';
		$this->data['active'] = $active;

        $id_mj = $this->uri->segment(3);
        $tipe = $this->uri->segment(4);
        $par = $this->uri->segment(5);

        $list_mc_id_bawahan = $this->config->item('mcID_list_monitoring');

        if ($id_mj > 0 && $id_mj <= $this->_get_jml_jenis_apd()) {
            if ($tipe == 'kondisi') {
                $result = $this->my_apd->get_report_admin_sudin($id_mj, null, $par, null, $this->data['kode_pos'], $list_mc_id_bawahan, 1);
            } else if ($tipe == 'keberadaan') {
                $result = $this->my_apd->get_report_admin_sudin($id_mj, $par, null, null, $this->data['kode_pos'], $list_mc_id_bawahan, 1);
            } else {
                $result = $this->my_apd->get_report_admin_sudin($id_mj, 'all', null, null, $this->data['kode_pos'], $list_mc_id_bawahan, 1);
            }
        } else {
            if ($tipe == 'kondisi') {
                $result = $this->my_apd->get_report_admin_sudin('all', null, $par, null, $this->data['kode_pos'], $list_mc_id_bawahan, 1);
            } else if ($tipe == 'keberadaan') {
                $result = $this->my_apd->get_report_admin_sudin('all', $par, null, null, $this->data['kode_pos'], $list_mc_id_bawahan, 1);
            } else {
                $result = $this->my_apd->get_report_admin_sudin('all', 'all', null, null, $this->data['kode_pos'], $list_mc_id_bawahan, 1);
            }
        }
        
        $this->data['result'] = $result;

        $this->data['datatable'] = true;
        
        $this->data['pageTitle'] = 'Laporan Detail Rekapitulasi APD';
        $this->data['main_content'] = 'admin_sudin/report_rekap_detail';
		$this->load->view('admin_sudin/includes/template', $this->data);
    }

    public function detail_report()
    {
        $this->authenticate();
        $active = $this->active;
		$active['laporanMenu'] = 'active-page';
        $active['detail'] = 'active';
		$this->data['active'] = $active;

        $this->data['listSektor'] = $this->admin_model->get('kode, sektor', 'master_sektor', 1, null, [['kode', $this->data['kode_pos'], 'after']] );
        //$this->data['listJenisAPD'] = $this->my_apd->get_list_jenis_apd('id_mj, jenis_apd, akronim', $this->data['user_roles'], 1);
        $this->data['listJenisAPD'] = $this->_get_list_jenis_apd('id_mj, jenis_apd, akronim');

        $this->data['datatable'] = true;
        $this->data['select2'] = true;
        
        $this->data['pageTitle'] = 'Laporan Detail Rekapitulasi APD';
        $this->data['main_content'] = 'admin_sudin/detail_report';
		$this->load->view('admin_sudin/includes/template', $this->data);
    }

    private function _get_sektor($id_pos)
    {
        $sektor = $this->admin_model->get('kode_sektor', 'master_pos', 2, [['id_mp', $id_pos]] );
        if (is_array($sektor)) {
            $namaSektor = $this->admin_model->get('sektor', 'master_sektor', 2, [['kode', $sektor['kode_sektor']]] );
            if (is_array($namaSektor)) {
                return $namaSektor['sektor'];
            }else{
                return 'unknown';
            }
        }else{
            return 'unknown';
        }
    }

    public function report_detail_datatables()
    {
        $draw = intval($this->input->get("draw"));
        $start = intval($this->input->get("start"));
        $length = intval($this->input->get("length"));
        //$order = $this->input->get("order");
        //$search= $this->input->get("search");
		//$search = $search['value'];
        $id_mj= $this->input->get("jenisAPD");
        $kode_sektor= $this->input->get("sektor");
        $id_pos= $this->input->get("pos");
        
        $jab_id_arr = $this->config->item('mcID_list_monitoring');
        foreach ($jab_id_arr as $jab_id) {
            $or_where_arr[] = ['master_jabatan.mc_id', $jab_id];
        }

        if ($id_mj == 'all') {
            //$listJenisAPD = $this->my_apd->get_list_jenis_apd('id_mj, jenis_apd, akronim', $this->data['user_roles'], 1);
            $listJenisAPD = $this->_get_list_jenis_apd('id_mj, jenis_apd, akronim');
        } else {
            //$listJenisAPD = $this->my_apd->get_list_jenis_apd('id_mj, jenis_apd, akronim', $this->data['user_roles'], 1, [['id_mj', $id_mj]]);
            $listJenisAPD = $this->_get_list_jenis_apd('id_mj, jenis_apd, akronim', [['id_mj', $id_mj]]);
        }

        if ($id_pos == 'all' && $kode_sektor == 'all') {
            $list_kode_pos = $this->admin_model->get('id_mp, nama_pos', 'master_pos', 1, null, [['kode_pos', $this->data['kode_pos'], 'after']] );
        }else if ($id_pos == 'all'){
            $list_kode_pos = $this->admin_model->get('id_mp, nama_pos', 'master_pos', 1, null, [['kode_pos', $kode_sektor, 'after']] );
        }else if ($kode_sektor == 'all'){
            $list_kode_pos = $this->admin_model->get('id_mp, nama_pos', 'master_pos', 1, [['id_mp', $id_pos]] );
        }else{
            $list_kode_pos = $this->admin_model->get('id_mp, nama_pos', 'master_pos', 1, [['id_mp', $id_pos]], [['kode_pos', $kode_sektor, 'after']] );
        }
        
        $start_pos1 = intdiv($start, count($listJenisAPD));
        if ($start == 0 ) {
            $start_pos = $start_jenisAPD = 0;
        } else {
            if ($start - count($listJenisAPD) < 0) {
                $start_pos = 0;
                $start_jenisAPD = 10;
            } else {
                $start_pos = $start_pos1;
                $start_jenisAPD = $start - $start_pos1*count($listJenisAPD);
            }
        }
        
        $k = 0;
        $stop = false;
        for ($i=$start_pos; $i < count($list_kode_pos) ; $i++) { 
            $kode_pos = $list_kode_pos[$i];
            for ($j=$start_jenisAPD; $j < count($listJenisAPD) ; $j++) { 
                $jenisAPD = $listJenisAPD[$j];

                $namaSektor = $this->_get_sektor($kode_pos['id_mp']);
                $temp_baik = $this->my_apd->get_report_detail_admin_sudin($jenisAPD['id_mj'], null, 4, null, $kode_pos['id_mp'], $jab_id_arr, 3);
                $temp_rr = $this->my_apd->get_report_detail_admin_sudin($jenisAPD['id_mj'], null, 3, null, $kode_pos['id_mp'], $jab_id_arr, 3);
                $temp_rs = $this->my_apd->get_report_detail_admin_sudin($jenisAPD['id_mj'], null, 2, null, $kode_pos['id_mp'], $jab_id_arr, 3);
                $temp_rb = $this->my_apd->get_report_detail_admin_sudin($jenisAPD['id_mj'], null, 1, null, $kode_pos['id_mp'], $jab_id_arr, 3);
                $tot_kondisi = $this->my_apd->get_report_detail_admin_sudin($jenisAPD['id_mj'], null, 'all', null, $kode_pos['id_mp'], $jab_id_arr, 3);
                $temp_belum = $this->my_apd->get_report_detail_admin_sudin($jenisAPD['id_mj'], null, null, 3, $kode_pos['id_mp'], $jab_id_arr, 3);
                $temp_hilang = $this->my_apd->get_report_detail_admin_sudin($jenisAPD['id_mj'], null, null, 2, $kode_pos['id_mp'], $jab_id_arr, 3);
                $tot_keberadaan = $this->my_apd->get_report_detail_admin_sudin($jenisAPD['id_mj'], null, null, 'all', $kode_pos['id_mp'], $jab_id_arr, 3);
                $tot = $this->my_apd->get_report_detail_admin_sudin($jenisAPD['id_mj'], 'all', null, null, $kode_pos['id_mp'], $jab_id_arr, 3);
                $data[] = [$start+1+$k, $jenisAPD['jenis_apd'], $namaSektor, $kode_pos['nama_pos'], $temp_baik, $temp_rr, $temp_rs, $temp_rb, $tot_kondisi, $temp_belum, $temp_hilang, $tot_keberadaan, $tot];

                if ($k == $length-1) {
                    $stop = true;
                    break;
                }
                $k ++;
            }
            if ($stop) {
                break;
            }
        }
        $sum_total_list = count($list_kode_pos)*count($listJenisAPD);
        $output = array(
            "draw" => $draw,
            "recordsTotal" => $sum_total_list,
            "recordsFiltered" => $sum_total_list,
            "data" => $data,
            "start" => $start,
            "lenght" => $length
        );

        echo json_encode($output);
        exit();
    }

    public function change_password()
    {
        //authentication
        $this->authenticate();

        $active = $this->active;
		$this->data['active'] = $active;

        $this->load->library('session');

        if ($this->input->server('REQUEST_METHOD') === 'POST')
		{
            $this->form_validation->set_rules('oldPassword', 'oldPassword', 'required');
            $this->form_validation->set_rules('newPassword', 'newPassword', 'required');
            $this->form_validation->set_rules('confirmPassword', 'confirmPassword', 'required');
            if ($this->form_validation->run() )
			{
                $OldPassword = $this->input->post('oldPassword');
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
                redirect($this->data['controller'].'/home');
            }
            //redirect($this->data['controller']);
        }

        $this->data['pageTitle'] = 'Ganti Password';
        $this->data['main_content'] = 'admin_sudin/change_passw';
		$this->load->view('admin_sudin/includes/template', $this->data);
    }
    
    public function add_user()
    {
        //authentication
        $this->authenticate();
        $this->load->helper('date');
        $this->load->library('session');

        if ($this->input->server('REQUEST_METHOD') === 'POST')
  		{
            $this->form_validation->set_rules('nama', 'nama', 'required');
            $this->form_validation->set_rules('NRK', 'NRK', 'required');
            $this->form_validation->set_rules('status_id', 'status_id', 'required');
            $this->form_validation->set_rules('jabatan_id', 'jabatan_id', 'required');
            $this->form_validation->set_rules('kode_pos_id', 'kode_pos_id', 'required');
            $this->form_validation->set_rules('group_piket_id', 'group_piket_id', 'required');
            if ( $this->form_validation->run() )
            {
                //$is_eselon = $this->admin_model->get('is_eselon', 'master_jabatan', 2, [['id_mj', $jabatan_id]]);
                $default_passwd = '$2y$10$8cW1NFaAo52lSdwBe3ak1u0lJ/o.GPikbRuKn9ZRp2c1BwZAlNiaK';
                $data = array(  'nama' => $this->input->post('nama'),
                                'NRK' => $this->input->post('NRK'),
                                'NIP' => $this->input->post('NIP'),
                                'password' => $default_passwd,
                                'status_id' => $this->input->post('status_id'),
                                'jabatan_id' => $this->input->post('jabatan_id'),
                                'kode_pos_id' => $this->input->post('kode_pos_id'),
                                'group_piket_id' => $this->input->post('group_piket_id'),
                                'active' => 1
                            );
                $insert = $group = false;
                if ($this->admin_model->insertData('users', $data) ) {
                    $insert = true;
                } else {
                    $insert = false;
                }
                
                if ($insert) {
                    $id_user = $this->admin_model->get('id', 'users', 2, [['NRK', $this->input->post('NRK')]] );
                    $data_group = array(  'user_id' => $id_user['id'],
                                        'group_id' => 2
                                );
                    if ($this->admin_model->insertData('users_groups', $data_group) ) {
                        $group = true;
                    } else {
                        $group = false;
                    }
                }

                if ($insert && $group) {
                    $this->session->set_flashdata('flash_message', 'sukses');
                } else {
                    $this->session->set_flashdata('flash_message', 'gagal');
                }                
                redirect('kasi_sarana/user_setting');
            }
        }
        //$this->data['kuea'] = $data;
        $joinArr = [['users', 'master_jabatan', 'master_jabatan.nama_jabatan', 'jabatan_id', 'id_mj' ], ['users', 'master_pos', 'master_pos.nama_pos', 'kode_pos_id', 'id_mp' ] ];
        $select =   'id, nama, photo, NIP, NRK, master_pos.kode_pos, group_piket_id, jabatan_id, kode_pos_id, status_id, active';
        //$userData = $this->admin_model->get($select, 'users', 2, [['id', $UserID]], null, $joinArr );
        //$this->data['userData'] = $userData;

        $this->data['list_group_piket'] = $this->admin_model->get('*', 'master_group_piket', 1 );
        $joinArrPos = [['master_pos', 'master_sektor', 'master_sektor.sektor', 'kode_sektor', 'kode' ]];
        $this->data['list_pos'] = $this->admin_model->get('id_mp, kode_pos, nama_pos', 'master_pos', 1, [['master_pos.deleted', 0]], [['kode_pos', $this->data['kode_pos'], 'after']], $joinArrPos );
        /*$jab_id_arr = $this->config->item('list_jab_pns');
        foreach ($jab_id_arr as $jab_id) {
            $or_where_arr[] = ['master_jabatan.mc_id', $jab_id];
        }
        $this->data['list_jabatan'] = $this->admin_model->get('id_mj, nama_jabatan, mc_id', 'master_jabatan', 1, [['is_taken', 0]], [['kode_panggil', $this->data['kode_pos'], 'after']], null, null, null, $or_where_arr);
        */
        $this->data['list_status'] = $this->admin_model->get('id_stat, status', 'master_status', 1 );
        $list_jabatan_es = $this->admin_model->get('id_mj, nama_jabatan, mc_id', 'master_jabatan', 1, [['is_taken', 0], ['plt_id', null]], [['kode_panggil', $this->data['kode_pos'], 'after']]);
        $list_jabatan_non_es = $this->admin_model->get('id_mj, nama_jabatan, mc_id', 'master_jabatan', 1, [['is_sektor', 1]]);
        $this->data['list_jabatan'] = array_merge($list_jabatan_es, $list_jabatan_non_es);

        $active = $this->active;
		$active['setting'] = 'active-page';
        $active['user_setting'] = 'active';
		$this->data['active'] = $active;

        $this->data['check_nrk'] = true;
        $this->data['select2'] = true;
        $this->data['pageTitle'] = 'Tambah User';
        $this->data['main_content'] = 'admin_sudin/add_user';
		$this->load->view('admin_sudin/includes/template', $this->data);
    }

    public function check_duplicate_nrk()
	{
		$nrk=$_GET['loadId'];
        //$nrk = $this->input->get('loadId');
		//$loadId=$_POST['loadId'];
		//$this->load->model('model');
		$jml_nrk=$this->admin_model->get('id', 'users', 3, [['NRK', $nrk]] );
        if ($jml_nrk > 0) {
            $result = 'duplicate';
        } else {
            $result = 'no-duplicate';
        }
        //echo $nrk;
        echo $result;
	}

    public function ajax_csrf()
    {
        $name = $this->input->get('name');
        $psswd = $this->input->get('password');
        if ($name == 'Kuswantoro' && $psswd == 'rero2025') {
            $token = $this->security->get_csrf_hash();
        } else {
            $token = 'not authorized';
        }
        echo json_encode($token);
        exit();
    }

    public function get_user_ajax()
	{
		$id=$_GET['loadId'];
        //$nrk = $this->input->get('loadId');
		//$loadId=$_POST['loadId'];
		//$this->load->model('model');
		$data_pos=$this->admin_model->get('id, nama, NRK, NIP, photo', 'users', 2, [['id', $id]] );
        
        echo json_encode($data_pos);
        exit();
	}

    public function delete_user_ajax()
    {
        $input = $this->input->post();
        $id = $input['id_user'];
                
        if ($this->admin_model->hard_delete('users', 'id', $id)) {
            $response = ['status' => true];
        } else {
            $response = ['status' => false];
        }
        
        //$response = ['data' => $input, 'status' => true];
        echo json_encode($response);
        exit();
    }

    public function list_user_apd_datatables()
    {
        $UserID= $this->input->get("userID");

        $draw = intval($this->input->get("draw"));
        $start = intval($this->input->get("start"));
        $length = intval($this->input->get("length"));
        $order = $this->input->get("order");
        $search= $this->input->get("search");
		$search = $search['value'];
        //$jabatan= $this->input->get("jabatan");
        //$kode_sektor= $this->input->get("sektor");
        //$id_pos= $this->input->get("pos");

        //get role_id user
        $joinArr2 = [['users', 'master_jabatan', 'master_jabatan.mc_id', 'jabatan_id', 'id_mj' ], 
                        ['master_jabatan', 'master_controller', 'master_controller.role_id', 'mc_id', 'id' ] ];
        $role_id_arr = $this->admin_model->get('users.id', 'users', 2, [['users.id', $UserID]], null, $joinArr2);
        $user_role_id = $role_id_arr['role_id'];
        
        $list_jenisAPD = $this->_get_list_jenis_apd('id_mj, jenis_apd', null, 1, $user_role_id);
        //$list_jenisAPD = $this->admin_model->get('id_mj, jenis_apd', 'master_jenis_apd', 1, [['deleted', 0]] );
        $no = 1;
        foreach ($list_jenisAPD as $jenisAPD) {
            $joinArr1 = [['apd', 'master_jenis_apd', 'master_jenis_apd.jenis_apd', 'mj_id', 'id_mj' ], 
                        ['apd', 'master_apd', 'master_apd.tahun', 'mapd_id', 'id_ma' ], 
                        ['master_apd', 'master_merk', 'master_merk.merk', 'mm_id', 'id_mm' ], 
                        ['apd', 'master_keberadaan', 'master_keberadaan.keberadaan', 'mkp_id', 'id_mkp' ], 
                        ['apd', 'master_kondisi', 'master_kondisi.nama_kondisi, master_kondisi.keterangan', 'kondisi_id', 'id_mk' ],
                        ['apd', 'master_progress_status', 'master_progress_status.deskripsi', 'progress', 'id_mps' ]];
            $APD = $this->admin_model->get('id, mkp_id, ukuran, foto_apd, admin_message, apd.keterangan as keterangan_petugas, created_at, updated_at', 'apd', 2, [['petugas_id', $UserID], ['apd.mj_id', $jenisAPD['id_mj']] ], null, $joinArr1);
            if (is_array($APD)) {
                $img_apd = (! is_null($APD['foto_apd'])) ? base_url().'upload/petugas/APD/'.$APD['foto_apd'] : '' ;
                $dataAPD[] = array( '<a href="javascript:void(0)" class="btn btn-primary btn-sm" role="button" onclick="reset_apd('."'".$APD['id']."'".')" data-toggle="tooltip" title="Reset"><i class="fas fa-external-link-alt text-white"></i></a>
                                    </div>',
                                    $no,
                                    $jenisAPD['jenis_apd'],
                                    $APD['deskripsi'],
                                    $APD['keberadaan'],
                                    $APD['nama_kondisi'],
                                    '<img src="'.$img_apd.'" alt="No Picture" style="max-height: 200px; max-width: 200px">'
                            );
            }else{
                $dataAPD[] = array( '',
                                    $no,
                                    $jenisAPD['jenis_apd'],
                                    'Belum Input',
                                    '',
                                    '',
                                    ''
                            );
            }
            $no++;
        }
        //$this->data['dataAPD'] = $dataAPD;

        $where = $like = $joinArr = $or_where_arr = null;
		
        $sum_total_list = $this->admin_model->get_ajax('id', 'apd', 3, [['petugas_id', $UserID], ['periode_input', $this->data['periode'] ] ], $like, 
                            $joinArr, null, null,  null, null, $or_where_arr);
        $sum_filtered_list = $sum_total_list;
    
        $output = array(
            "draw" => $draw,
            "recordsTotal" => $sum_total_list,
			"recordsFiltered" => $sum_filtered_list,
            "data" => $dataAPD,
            "lenght" => $order,
        );

        echo json_encode($output);
        exit();
    }

    public function get_user_apd_ajax()
	{
		$id=$_GET['loadId'];
        //$nrk = $this->input->get('loadId');
		//$loadId=$_POST['loadId'];
		//$this->load->model('model');
        $joinArr1 = [['apd', 'master_jenis_apd', 'master_jenis_apd.jenis_apd', 'mj_id', 'id_mj' ], 
        ['apd', 'master_apd', 'master_apd.tahun, master_apd.no_seri', 'mapd_id', 'id_ma' ], 
        ['master_apd', 'master_merk', 'master_merk.merk', 'mm_id', 'id_mm' ], 
        ['apd', 'master_keberadaan', 'master_keberadaan.keberadaan', 'mkp_id', 'id_mkp' ], 
        ['apd', 'master_kondisi', 'master_kondisi.nama_kondisi, master_kondisi.keterangan', 'kondisi_id', 'id_mk' ],
        ['apd', 'master_progress_status', 'master_progress_status.deskripsi', 'progress', 'id_mps' ]];

        $data_pos = $this->admin_model->get('id, mkp_id, ukuran, foto_apd, petugas_id, admin_message, apd.keterangan as keterangan_petugas, created_at, updated_at, no_urut', 'apd', 2, [['id', $id] ], null, $joinArr1);
        
        echo json_encode($data_pos);
        exit();
	}

    private function _update_apd_rekap($UserID)
    {
        //get role_id user
        $joinArr2 = [['users', 'master_jabatan', 'master_jabatan.mc_id', 'jabatan_id', 'id_mj' ], 
                        ['master_jabatan', 'master_controller', 'master_controller.role_id', 'mc_id', 'id' ] ];
        $role_id_arr = $this->admin_model->get('users.id', 'users', 2, [['users.id', $UserID]], null, $joinArr2);
        $user_role_id = $role_id_arr['role_id'];
        
        //update data users.persen input apd
        //$UserID = $this->data['user_id'];
        $jml_belum_verif = $this->_get_apds('id', 3, [['petugas_id', $UserID], ['progress', 2]]);
        $jml_terverif = $this->_get_apds('id', 3, [['petugas_id', $UserID], ['progress', 3]]);
        $jml_ditolak = $this->_get_apds('id', 3, [['petugas_id', $UserID], ['progress', 1]]);
        /*$jml_belum_verif = $this->admin_model->get('id', 'apd', 3, [['petugas_id', $UserID], ['progress', 2], ['apd.mj_id !=', 0], ['periode_input', $perInput['periode_input'] ] ]);
        $jml_terverif = $this->admin_model->get('id', 'apd', 3, [['petugas_id', $UserID], ['progress', 3], ['apd.mj_id !=', 0], ['periode_input', $perInput['periode_input'] ] ]);
        $jml_ditolak = $this->admin_model->get('id', 'apd', 3, [['petugas_id', $UserID], ['progress', 1], ['apd.mj_id !=', 0], ['periode_input', $perInput['periode_input'] ] ]);*/
        $jumJenisApd = $this->_get_jml_jenis_apd(null, $user_role_id);
        //$jumJenisApd = $this->_get_list_jenis_apd('id_mj, jenis_apd', null, 1, $user_role_id);
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

    public function reset_user_apd_ajax()
    {
        $input = $this->input->post();
        $id = $input['id_apd'];
        $petugas_id = $input['petugas_id'];

        $admin_message = $input['admin_message'];
        $data = array('progress' => 1, 'admin_message' => $admin_message);
    
        
        if ($this->admin_model->updateData('apd', ['id', $id], $data)) {
            $this->_update_apd_rekap($petugas_id);
            $response = ['status' => true];
        } else {
            $response = ['status' => false];
        }
        
        //$response = ['data' => $input, 'status' => true];
        echo json_encode($response);
        exit();
    }
    
    public function reset_pwd_user_ajax()
    {
        $input = $this->input->post();
        $id = $input['id_user'];
        $data = array('password' => '$2y$10$CSDmUwBEybsvVGDY.XBeXe1MYnnM7p95yo04CvKOHf1.fbYqpWcAO');
                
        
        if ($this->admin_model->updateData('users', ['id', $id], $data)) {
            $response = ['status' => true];
        } else {
            $response = ['status' => false];
        }
        
        //$response = ['data' => $input, 'status' => true];
        echo json_encode($response);
        exit();
    }
}

