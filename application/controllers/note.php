<?
//controller petugas

public function APDver2()
    {
        // cek apakah periode input
        if (! $this->data['is_open']) {
            $this->show_myError('Tidak dapat membuka halaman ini, bukan periode input');
            return false;
        }
        // validasi $this->uri->segment(3);
        $id_mj = $this->uri->segment(3);
        $jenisApd = $this->petugas_model->get('id_mj, jenis_apd, picture', 'master_jenis_apd', [['id_mj', $id_mj],['deleted', 0]], null, 2);
        if (! is_array($jenisApd) )  {
            $this->show_myError('Tidak ada data');
            return false;
        }
        $id_mj = $jenisApd['id_mj'];
        $masterAPD = $this->petugas_model->get_masterAPD_groupbyjenis($id_mj);
        $i = 0;
        foreach ($masterAPD as $apd) {
            $temp = $this->petugas_model->get('id, mkp_id, kondisi_id, ukuran_id, msv_id, foto_apd', 'apd', [['mapd_id', $apd['id_ma'] ]], null, 2);
            if(is_array($temp))
            {
                $masterAPD[$i]['id_apd'] = $temp['id'];
                $masterAPD[$i]['kepemilikan'] = $temp['mkp_id'];
                $masterAPD[$i]['kondisi'] = $temp['kondisi_id'];
                $masterAPD[$i]['ukuran'] = $temp['ukuran_id'];
                $masterAPD[$i]['verif'] = $temp['msv_id'];
                $masterAPD[$i]['foto_apd'] = $temp['foto_apd'];
            }else{
                $masterAPD[$i]['id_apd'] = null;
                $masterAPD[$i]['kepemilikan'] = null;
                $masterAPD[$i]['kondisi'] = null;
                $masterAPD[$i]['ukuran'] = null;
                $masterAPD[$i]['verif'] = null;
                $masterAPD[$i]['foto_apd'] = null;
            }
            $i++;
        }
        $listKepemilikkan = $this->petugas_model->get('*', 'master_kepemilikan', null, null, 1);
        $listKondisi = $this->petugas_model->get_masterKondisi_groupbyjenis($id_mj);
        $listUkuran = $this->petugas_model->get('daftar_ukuran', 'master_tipe_ukuran', [['id_mtu', $id_mj]], null, 2);
        d($masterAPD);
        d($listKepemilikkan);
        d($listKondisi);
        d($listUkuran);
        $this->data['pageTitle'] = 'APD';
        $this->data['jenisApd'] = $jenisApd;
        $this->data['masterAPD'] = $masterAPD;
        $this->data['main_content'] = 'petugas/APD';
		//$this->load->view('petugas/includes/template', $this->data);
        $this->load->view('petugas/APD', $this->data);
    }

    public function addAPD()
    {
        if (! $this->data['is_open']) {
            $this->show_myError('Tidak dapat membuka halaman ini, bukan periode input');
            return false;
        }
        //cek validitas segmen3
        $id_mj = $this->uri->segment(3);
        $jenisApd = $this->petugas_model->get('id_mj, jenis_apd, picture, mtu_id', 'master_jenis_apd', [['id_mj', $id_mj],['deleted', 0]], null, 2);
        if (! is_array($jenisApd) )  {
            $this->show_myError('Tidak ada data');
            return false;
        }
        $id_mj = $jenisApd['id_mj'];
        $this->load->helper('date');
        //upload file denah gedung
        $this->load->library('upload');
		$upload_APD_path = 'upload/petugas/APD';
		$config['upload_path']          = FCPATH.$upload_APD_path;
		$config['allowed_types']        = 'gif|jpg|png|pdf';
		$config['max_size']             = 5000;
		$config['remove_spaces']		= TRUE;  //it will remove all spaces
		$this->upload->initialize($config);
        if ($this->input->server('REQUEST_METHOD') === 'POST')
		{
            //form validation
			$this->form_validation->set_rules('mapd_id', 'mapd_id', 'required');
			$this->form_validation->set_rules('mkp_id', 'mkp_id', 'required');
			$this->form_validation->set_rules('kondisi_id', 'kondisi_id', 'required');
			$this->form_validation->set_rules('ukuran', 'ukuran', 'required');
			$this->form_validation->set_error_delimiters('<div class="alert alert-error"><a class="close" data-dismiss="alert">×</a><strong>', '</strong></div>');
			//if the form has passed through the validation
			if ($this->form_validation->run())
			{
				$my_time = date("Y-m-d H:i:s", now('Asia/Jakarta'));
				$data_to_store = array(
                    'mj_id' => $id_mj,
					'mapd_id' => isZonk($this->input->post('mapd_id')),
					'mkp_id' => isZonk($this->input->post('mkp_id')),
					'petugas_id' => $this->data['user_id'],
					'kondisi_id' => isZonk($this->input->post('kondisi_id')),
					'ukuran' => isZonk($this->input->post('ukuran')),
					'msv_id' => 2,
                    'keterangan' => isZonk($this->input->post('keterangan')),
					'created_at' => $my_time
				);
				//set upload denah gedung
				if( $this->upload->do_upload('foto_apd'))
				{
					$upload_data = $this->upload->data();
					$raw = $upload_data['raw_name'];
					$file_type = $upload_data['file_ext'];
					$data_to_store['foto_apd'] = $raw.$file_type;
				}
				//if the insert has returned true then we show the flash message
				if($this->petugas_model->insertData('apd', $data_to_store)){
					$this->session->set_flashdata('flash_message', 'sukses');
				}else{
					$this->session->set_flashdata('flash_message', 'failed');
				}

				//redirect('Prainspeksi_gedung/update/'.$id.'');
				redirect('petugas/APD/'.$id_mj);

			}//validation run
        }

        $masterAPD = $this->petugas_model->get_masterAPD_groupbyjenis($id_mj);
        $listKeberadaan = $this->petugas_model->get('*', 'master_keberadaan', null, null, 1);
        $listKondisi = $this->petugas_model->get_masterKondisi_groupbyjenis($id_mj);
        $listUkuran = $this->petugas_model->get('id_mtu, daftar_ukuran', 'master_tipe_ukuran', [['id_mtu', $jenisApd['mtu_id']]], null, 2);
        $progress = $this->petugas_model->get('status', 'progress', [['mp_id', $this->data['user_id']], ['mj_id', $id_mj]], null, 2);
        if (is_array($progress) )  {
            if ($progress['status'] > 0) {
                $this->show_myError('Tidak ada data');
                return false;
            }
        }
        
        $this->data['pageTitle'] = 'Tambah APD';
        $this->data['jenisApd'] = $jenisApd;
        $this->data['masterAPD'] = $masterAPD;
        $this->data['listKeberadaan'] = $listKeberadaan;
        $this->data['listKondisi'] = $listKondisi;
        $this->data['listUkuran'] = $listUkuran;
        $this->data['thead'] = array(
			'Merk APD','Keberadaan', 'Kondisi', 'Ukuran' , 'Keterangan'
		);
		$this->data['dhead'] = array(
			'mapd_id', 'mkp_id', 'kondisi_id', 'ukuran', 'keterangan'
		);
        //d($masterAPD, $listKeberadaan, $listKondisi, $listUkuran, $progress);
        $this->data['main_content'] = 'petugas/add_APD';
		$this->load->view('petugas/includes/template', $this->data);
        //$this->load->view('petugas/add_APD', $this->data);

    }

    public function editAPD()
    {
        if (! $this->data['is_open']) {
            $this->show_myError('Tidak dapat membuka halaman ini, bukan periode input');
            return false;
        }
        //cek validitas segmen3
        $id_apd = $this->uri->segment(3);
        $dataApd = $this->petugas_model->get('*', 'apd', [['id', $id_apd],['petugas_id', $this->data['user_id']]], null, 2);
        if (! is_array($dataApd) )  {
            $this->show_myError('Tidak ada data');
            return false;
        }
        $id_apd = $dataApd['id'];
        $id_mj = $this->petugas_model->get('mj_id', 'master_apd', [['id_ma', $dataApd['mapd_id']]], null, 2);
        $id_mj = $id_mj['mj_id'];
        $this->load->helper('date');
        //upload file denah gedung
        $this->load->library('upload');
		$upload_APD_path = 'upload/petugas/APD';
		$config['upload_path']          = FCPATH.$upload_APD_path;
		$config['allowed_types']        = 'gif|jpg|png|pdf';
		$config['max_size']             = 5000;
		$config['remove_spaces']		= TRUE;  //it will remove all spaces
		$this->upload->initialize($config);
        //d($dataApd, $id_mj);
        $jenisApd = $this->petugas_model->get('id_mj, jenis_apd, picture, mtu_id', 'master_jenis_apd', [['id_mj', $id_mj],['deleted', 0]], null, 2);
        $masterAPD = $this->petugas_model->get_masterAPD_groupbyjenis($id_mj);
        $listKeberadaan = $this->petugas_model->get('*', 'master_keberadaan', null, null, 1);
        $listKondisi = $this->petugas_model->get_masterKondisi_groupbyjenis($id_mj);
        $listUkuran = $this->petugas_model->get('id_mtu, daftar_ukuran', 'master_tipe_ukuran', [['id_mtu', $jenisApd['mtu_id']]], null, 2);
        $progress = $this->petugas_model->get('status', 'progress', [['mp_id', $this->data['user_id']], ['mj_id', $id_mj]], null, 2);
        if (is_array($progress) )  {
            if ($progress['status'] > 0) {
                $this->show_myError('Tidak ada data');
                return false;
            }
        }
        
        $this->data['pageTitle'] = 'Edit APD';
        $this->data['dataApd'] = $dataApd;
        $this->data['jenisApd'] = $jenisApd;
        $this->data['masterAPD'] = $masterAPD;
        $this->data['listKeberadaan'] = $listKeberadaan;
        $this->data['listKondisi'] = $listKondisi;
        $this->data['listUkuran'] = $listUkuran;
        $this->data['thead'] = array(
			'Merk APD','Keberadaan', 'Kondisi', 'Ukuran' , 'Keterangan'
		);
		$this->data['dhead'] = array(
			'mapd_id', 'mkp_id', 'kondisi_id', 'ukuran', 'keterangan'
		);
        //d($dataApd, $masterAPD, $listKeberadaan, $listKondisi, $listUkuran, $progress);
        $this->data['main_content'] = 'petugas/edit_APD';
		$this->load->view('petugas/includes/template', $this->data);
        //$this->load->view('petugas/edit_APD', $this->data);
    }

    <div class="wide-block pb-2 pt-2">
         $photo = (! is_null($userData['photo'])) ? $userData['photo'] : 'default.png' ; 
            echo '
            <div class="text-center">
                <img src="'.base_url().'upload/petugas/profil/'.$photo.'" alt="image" class="imaged w-25 rounded">
            </div>
            ';
    
//controller auto
private function _fillDataRekap($table, $list_pos)
{
    $joinTable1 = ['master_jabatan'];
    $joinTable2 = ['users', 'master_jabatan', 'master_pos'];
    $jab_id_arr = $this->config->item('mcID_list_monitoring');
    foreach ($jab_id_arr as $jab_id) {
        $or_where_arr[] = ['master_jabatan.mc_id', $jab_id];
    }
    $failed = [];
    foreach ($list_pos as $pos) {
        if ($table == 'master_pos') {
            $like = [['master_pos.kode_pos', $pos['kode'], 'after']];
        } else if ($table == 'master_sektor'){
            $like = [['master_sektor.kode', $pos['kode'], 'after']];
        } else if ($table == 'master_sudin'){
            $like = [['master_sudin.kode', $pos['kode'], 'after']];
        } else if ($table == 'master_dinas'){
            $like = null;
        }

        $select1 = 'id';
        $select2 = 'apd.id';
        $jmlPNS = $this->_get_users($select1, 3, [['status_id', 0]], $like, $or_where_arr, null, $joinTable1);
        $jmlPJLP = $this->_get_users($select1, 3, [['status_id', 1]], $like, $or_where_arr, null, $joinTable1);
        $jumSdhInput = $this->_get_apds($select2, 3, [['progress !=', 0]], $like, $or_where_arr, null, $joinTable2);
        $jumVerified = $this->_get_apds($select2, 3, [['progress', 3]], $like, $or_where_arr, null, $joinTable2);
        $jumDitolak = $this->_get_apds($select2, 3, [['progress', 1]], $like, $or_where_arr, null, $joinTable2);
        /*$result[$pos['id']] = array(  'jml_pns' => $jmlPNS, 
                                    'jml_pjlp' => $jmlPJLP,
                                    'jml_input' => $jumSdhInput,
                                    'jml_verif' => $jumVerified,
                                    'jml_ditolak' => $jumDitolak,
                            );*/
        $data = array(  'jml_pns' => $jmlPNS, 
                        'jml_pjlp' => $jmlPJLP,
                        'jml_input' => $jumSdhInput,
                        'jml_verif' => $jumVerified,
                        'jml_ditolak' => $jumDitolak,
                    );
        if (! $this->admin_model->updateData('master_pos', ['id_mp', $pos['id']], $data) ) {
            $failed[] = $pos['id'];
        }
    }
    return $failed;
}

private function _fillChart($table, $list_pos, $list_jenis_apd, $chart_type='input')
{
    if ($chart_type == 'input') {
        $progress = ['apd.progress', 2];
        $col = 'chart_input_APD';
    } else {
        $progress = ['apd.progress', 3];
        $col = 'chart_verif_APD';
    }
    
    $join_arr = ['master_kondisi', 'users', 'master_pos', 'master_jabatan'];
    $jab_id_arr = $this->config->item('mcID_list_monitoring');
    foreach ($jab_id_arr as $jab_id) {
        $or_where_arr[] = ['master_jabatan.mc_id', $jab_id];
    }

    $failed = [];
    foreach ($list_pos as $pos) {
        if ($table == 'master_pos') {
            $like = [['master_pos.kode_pos', $pos['kode'], 'after']];
        } else if ($table == 'master_sektor'){
            $like = [['master_sektor.kode', $pos['kode'], 'after']];
        } else if ($table == 'master_sudin'){
            $like = [['master_sudin.kode', $pos['kode'], 'after']];
        } else if ($table == 'master_dinas'){
            $like = null;
        }

        foreach ($list_jenis_apd as $jenis_apd) {
            $jml_baik = $this->_get_apds('apd.id', 3, [['apd.mkp_id', 1],['master_kondisi.kategori', 4], $progress, ['apd.mj_id', $jenis_apd['id_mj'] ]], $like, $or_where_arr, null, $join_arr);
            $jml_rr = $this->_get_apds('apd.id', 3, [['apd.mkp_id', 1],['master_kondisi.kategori', 3], $progress, ['apd.mj_id', $jenis_apd['id_mj'] ]], $like, $or_where_arr, null, $join_arr);
            $jml_rs = $this->_get_apds('apd.id', 3, [['apd.mkp_id', 1],['master_kondisi.kategori', 2], $progress, ['apd.mj_id', $jenis_apd['id_mj'] ]], $like, $or_where_arr, null, $join_arr);
            $jml_rb = $this->_get_apds('apd.id', 3, [['apd.mkp_id', 1],['master_kondisi.kategori', 1], $progress, ['apd.mj_id', $jenis_apd['id_mj'] ]], $like, $or_where_arr, null, $join_arr);
            $jml_blm = $this->_get_apds('apd.id', 3, [['apd.mkp_id', 3], $progress, ['apd.mj_id', $jenis_apd['id_mj'] ]], $like, $or_where_arr, null, $join_arr);
            $jml_hilang = $this->_get_apds('apd.id', 3, [['apd.mkp_id', 2], $progress, ['apd.mj_id', $jenis_apd['id_mj'] ]], $like, $or_where_arr, null, $join_arr);
            $result[$jenis_apd['id_mj']] = array(   'jenis_apd' => $jenis_apd['jenis_apd'],
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
        $json = json_encode($result);
        $data = [$col => $json];
        if (! $this->admin_model->updateData('master_pos', ['id_mp', $pos['id']], $data) ) {
            $failed[] = $pos['id'];
        }
        $result = [];
    }
    return $failed;
}

private function _fillKIB($list_pos)
{
    $joinTable3 = [ ['master_apd', 'master_merk', 'master_merk.merk', 'mm_id', 'id_mm' ], 
                    ['master_apd', 'master_jenis_apd', 'master_jenis_apd.jenis_apd', 'mj_id', 'id_mj' ]];
    
    $join_arr = ['master_kondisi', 'users', 'master_pos', 'master_jabatan'];
    $jab_id_arr = $this->config->item('mcID_list_monitoring');
    foreach ($jab_id_arr as $jab_id) {
        $or_where_arr[] = ['master_jabatan.mc_id', $jab_id];
    }

    $failed = [];
    foreach ($list_pos as $pos) {
        if ($table == 'master_pos') {
            $like = [['master_pos.kode_pos', $pos['kode'], 'after']];
        } else if ($table == 'master_sektor'){
            $like = [['master_sektor.kode', $pos['kode'], 'after']];
        } else if ($table == 'master_sudin'){
            $like = [['master_sudin.kode', $pos['kode'], 'after']];
        } else if ($table == 'master_dinas'){
            $like = null;
        }

        foreach ($list_jenis_apd as $jenis_apd) {
            $list_apd = $this->admin_model->get('id_ma, tahun', 'master_apd', 1, [['master_apd.deleted', 0], ['mj_id', $jenis_apd['id_mj']]], null, $joinTable3);
            foreach ($list_apd as $apd) {
                $jml_baik = $this->_get_apds('apd.id', 3, [['master_kondisi.kategori', 4], ['apd.progress', 3], ['mapd_id', $apd['id_ma'] ]], $like, $or_where_arr, null, $join_arr);
                $jml_rr = $this->_get_apds('apd.id', 3, [['master_kondisi.kategori', 3], ['apd.progress', 3], ['mapd_id', $apd['id_ma'] ]], $like, $or_where_arr, null, $join_arr);
                $jml_rs = $this->_get_apds('apd.id', 3, [['master_kondisi.kategori', 2], ['apd.progress', 3], ['mapd_id', $apd['id_ma'] ]], $like, $or_where_arr, null, $join_arr);
                $jml_rb = $this->_get_apds('apd.id', 3, [['master_kondisi.kategori', 1], ['apd.progress', 3], ['mapd_id', $apd['id_ma'] ]], $like, $or_where_arr, null, $join_arr);
                if ($jml_baik != 0 || $jml_rr != 0 || $jml_rs != 0 || $jml_rb != 0) {
                    $result[$jenis_apd['id_mj']][$apd['id_ma']] = array('kode_barang' => $jenis_apd['kode_barang'],
                                                                        'jenis_apd' => $jenis_apd['jenis_apd'],
                                                                        'merk' => $apd['merk'],
                                                                        'tahun' => $apd['tahun'],
                                                                        'jml_baik' => $jml_baik, 
                                                                        'jml_rr' => $jml_rr,
                                                                        'jml_rs' => $jml_rs,
                                                                        'jml_rb' => $jml_rb,
                                                                        'total' => ($jml_baik+$jml_rr+$jml_rs+$jml_rb),
                                                                        );
                }
            }
        }
        $json = json_encode($result);
        $data = ['KIB_APD' => $json];
        if (! $this->admin_model->updateData('master_pos', ['id_mp', $pos['id']], $data) ) {
            $failed[] = $pos['id'];
        }
        $result = [];
    }
    return $failed;
}

    ?>