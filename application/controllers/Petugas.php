<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Petugas extends CI_Controller {
    public $data = [];

    public function __construct()
	{
        parent::__construct();
        $this->load->database();
        $this->load->library(['ion_auth', 'my_apd', 'form_validation']);
        $this->load->helper(['url', 'language']);
        $this->load->model('petugas_model');
        $this->config->load('petugas');
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
            if ($user_id['controller'] != 'petugas') {
                redirect("my404");
            }
        }else {
            redirect('auth/login', 'refresh');
        }
        $this->data['controller'] = $user_id['controller'];
        //$this->data['user'] = $this->ion_auth->user()->row();
        $this->data['username'] = $user->nama;
        $this->data['user_id'] = $user->id;
        $id_mp = $this->petugas_model->get('kode_pos', 'master_pos', [['id_mp', $user->kode_pos_id]], null, 2 );
        $this->data['kode_pos'] = $id_mp['kode_pos'];
        $this->data['jabatan'] = $this->petugas_model->get('nama_jabatan', 'master_jabatan', [['id_mj', $user->jabatan_id]], null, 2);
        $this->data['penempatan'] = $this->petugas_model->get('nama_pos', 'master_pos', [['id_mp', $user->kode_pos_id]], null, 2);
        $state = $this->my_apd->check_isOpenPeriode();
        $this->data['is_open'] = ($state['is_open']) ? true : false ;
        $this->data['periode'] = $state['periode'];
        $this->data['info_periode_input'] = $state['info_periode_input'];
        $profil_foto_path = 'upload/petugas/profil/'.$user->photo;
        $profil_thumb_foto_path = 'upload/petugas/profil/thumb/thumb_'.$user->photo;
        $this->data['avatar'] = (file_exists($profil_foto_path) && !is_null($user->photo)) ? $profil_foto_path : 'upload/petugas/profil/default.png' ;
        $this->data['thumb_avatar'] = (file_exists($profil_thumb_foto_path) && !is_null($user->photo)) ? $profil_thumb_foto_path : 'upload/petugas/profil/default.png' ;
        $this->data['nrk'] = $user->NRK;
        $this->data['is_plt'] = (strpos($this->data['nrk'], 'plt-') !== false) ? true : false ;
        $this->data['password'] = $user->password;
        $this->data['user_roles'] = $this->ion_auth->get_users_groups($user->id)->result();
        $this->data['user_role'] = $user_id['role_id'];
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
        $result = $this->petugas_model->get_x($select, 'users', $resultType, $default_where, $default_like, $default_join, $orderArr, $limitArr, $or_where, $or_like);
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
        $result = $this->petugas_model->get_x($select, 'apd', $resultType, $default_where, $default_like, $default_join, $orderArr, $limitArr, $or_where, $or_like);
        return $result;
    }

    private function _get_jml_jenis_apd($where=null, $roles_id=2)
    {
        //$roles_id = $this->data['user_roles'];
        $default_where = [['deleted', 0], ['role_id >=', $roles_id]];
        if (! is_null($where) && is_array($where)) {
            foreach ($where as $w) {
                array_push($default_where, $w);
            }
        }
        $result = $this->petugas_model->get_x('id_mj', 'master_jenis_apd', 3, $default_where );
        return $result;
    }

    private function _get_list_jenis_apd($select, $where=null, $resultType=1, $roles_id=2)
    {        
        //$roles_id = $this->data['user_roles'];
        $default_where = [['master_jenis_apd.deleted', 0], ['role_id >=', $roles_id]];
        if (! is_null($where) && is_array($where)) {
            foreach ($where as $w) {
                array_push($default_where, $w);
            }
        }

        $result = $this->petugas_model->get_x($select, 'master_jenis_apd', $resultType, $default_where );
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

    public function index()
    {
        $size_data = $this->petugas_model->get('id', 'users_ukuran', [['users_id', $this->data['user_id'] ]], null, 2);
        if (is_array($size_data)) {
            redirect(''.$this->data['controller'].'/home');
        } else {
            $this->_fill_size();
        }
    }

    public function home()
    {        
        $this->load->helper('date');
        $this->data['pageTitle'] = 'Dashboard';
        $this->data['main_content'] = 'petugas/home';
		$this->load->view('petugas/includes/template', $this->data);
    }

    public function lapor()
    {
        //update rekap apd all users
        /*$list_id = $this->_get_users('id', 1);
        $failed = [];
        foreach ($list_id as $id) {
            if (! $this->_update_my_rekap($id['id'])) {
                $failed[] = $id['id'];
            }
        }
        $this->data['failed'] = $failed;*/

        if (! $this->data['is_open']) {
            redirect("my404");
        }
        $this->data['pageTitle'] = 'Lapor APD';
        //$this->data['test_apd'] = $this->my_apd->get_list_jenis_apd('id_mj, jenis_apd, picture', $this->data['user_roles']);
        //$jenisApd = $this->petugas_model->get('id_mj, jenis_apd, picture', 'master_jenis_apd', [['deleted', 0]], null, 1);
        //$jenisApd = $this->my_apd->get_list_jenis_apd('id_mj, jenis_apd, picture', $this->data['user_roles']);
        $jenisApd = $this->_get_list_jenis_apd('id_mj, jenis_apd, picture');
        $this->data['numJenisApd'] = count($jenisApd);
        $i = 0;
        //$progress = 0;
        foreach ($jenisApd as $apd) {
            //$dataAPD = $this->petugas_model->get('*', 'apd', [['mj_id', $apd['id_mj']],['petugas_id', $this->data['user_id']],['periode_input', $this->data['periode']]], null, 2);
            $dataAPD = $this->my_apd->get_apd('progress', $apd['id_mj'], $this->data['user_id']);
            //$temp = $this->petugas_model->get('status', 'progress', [['mp_id', $this->data['user_id']], ['mj_id', $apd['id_mj']]], null, 2);
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
        if (! $this->data['is_open']) {
            redirect("my404");
        }
        $id_mj = $this->uri->segment(3);
        //$jenisApd = $this->petugas_model->get('*', 'master_jenis_apd', [['id_mj', $id_mj],['deleted', 0]], null, 2);
        //$jenisApd = $this->my_apd->get_list_jenis_apd('id_mj, jenis_apd, mtu_id', $this->data['user_roles'], 2, [['id_mj', $id_mj]]);
        $jenisApd = $this->_get_list_jenis_apd('id_mj, jenis_apd, mtu_id', [['id_mj', $id_mj]], 2);
        if (! is_array($jenisApd) )  {
            redirect("my404");
        }
        $id_mj = $jenisApd['id_mj'];
        //$dataAPD = $this->petugas_model->get('*', 'apd', [['mj_id', $id_mj],['petugas_id', $this->data['user_id']],['periode_input', $this->data['periode']]], null, 2);
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
        //this->load->library('upload');
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
                
				//set upload foto (tidak dipakai)
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
                        //file_put_contents('upload/petugas/test/'.$img_name, file_get_contents($image));
                        file_put_contents($upload_APD_path.$img_name, file_get_contents($image));
                    }
                    /*if (!empty($_POST['thumb'])) {
                        //$type = $this->_get_mimes($_POST['thumb']);
                        //$thumb_name = $this->data['nrk'].'_'.rand(0, 10000);
                        $thumb_name = 'thumb_'.$name.'.'.$type;
                        file_put_contents('upload/petugas/test/'.$thumb_name  , file_get_contents($_POST['thumb']));
                    }*/
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
        $tes = -1;
        if ($this->input->server('REQUEST_METHOD') === 'POST')
		{
            $tes = 0;
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
                //upload foto apd user (tidak jadi pakai metode upload CI)
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
        //$this->data['data_to_store'] = $data_to_store ;
        $select = 'photo, nama, NRK, NIP, users.no_telepon, email';
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
        $this->data['main_content'] = 'petugas/profile';
        $this->load->view('petugas/includes/template', $this->data);
        //$this->load->view('petugas/profile', $this->data);
    }
    
    public function my_apd()
    {
        $this->data['pageTitle'] = 'Data APD Saya';

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

    // lapor sewaktu2 dibuat private dulu, dikembangkan next
    private function list_lapor_sewaktu()
    {
        //get who is the admin
        $subs = substr($this->data['kode_pos'], 0, 4);
        $this->data['admin'] = $this->my_apd->get_admin($subs);

        $select_str = 'lapor_sewaktu.id, jenis_laporan, apd_id, create_at, lapor_sewaktu.progress';
        $where_array = [['lapor_sewaktu.petugas_id', $this->data['user_id']]];
        $this->data['list_lap_sewaktu'] = $this->my_apd->get_list_lap_sewaktu($select_str, 1, $where_array);
        $this->data['pageTitle'] = 'Lapor APD Sewaktu-waktu';
        $this->data['main_content'] = 'petugas/list_lapor_sewaktu';
		$this->load->view('petugas/includes/template', $this->data);
    }

    private function lapor_sewaktu_detail()
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

    private function lapor_sewaktu()
    {
        //get who is the admin
        $subs = substr($this->data['kode_pos'], 0, 4);
        $admin = $this->my_apd->get_admin($subs);

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