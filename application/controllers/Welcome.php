<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Welcome extends CI_Controller {

	/**
	 * Index Page for this controller.
	 *
	 * Maps to the following URL
	 * 		http://example.com/index.php/welcome
	 *	- or -
	 * 		http://example.com/index.php/welcome/index
	 *	- or -
	 * Since this controller is set as the default controller in
	 * config/routes.php, it's displayed at http://example.com/
	 *
	 * So any other public methods not prefixed with an underscore will
	 * map to /index.php/welcome/<method_name>
	 * @see https://codeigniter.com/user_guide/general/urls.html
	 */

	public function index()
	{
		//$this->fillUser();
		//$this->fixDuplicate();
		//$this->output->enable_profiler(TRUE);
		//$this->updateStatusId();
		//$this->load->helper('date');

		//$this->load->view('welcome_message');
		//$this->load->view('public/landing_page');
		//$this->data['tanggal'] = date('d/m/Y == H:i:s');;
		$this->data['main_content'] = 'public/landing_page';
		$this->load->view('public/includes/template', $this->data);
		//show_404();
	}

	public function intro()
	{
		//$this->fillUser();
		//$this->fixDuplicate();
		//$this->output->enable_profiler(TRUE);
		//$this->updateStatusId();

		//$this->load->view('welcome_message');
		//$this->load->view('public/landing_page');
		$this->data['main_content'] = 'public/sambutan';
		$this->load->view('public/includes/template', $this->data);
		//show_404();
	}

	/*
	private function fillUser()
	{
		$this->load->model('welcome_model');
		$listPegawai = $this->welcome_model->getMasterPegawai();
		//d($listPegawai);
		foreach ($listPegawai as $pegawai) {
			$data_to_store = array(
				'NRK' => $pegawai['NRK'],
				'password' => '$2y$10$8cW1NFaAo52lSdwBe3ak1u0lJ/o.GPikbRuKn9ZRp2c1BwZAlNiaK',
				'nama' => $pegawai['nama'],
				'NIP' => $pegawai['NIP'],
				'jabatan_id' => $pegawai['jabatan_id'],
				'jabatan' => $pegawai['jabatan'],
				'kode_pos' => $pegawai['kode_pos'],
				'no_telepon' => $pegawai['no_telepon'],
				'group_piket' => $pegawai['group_piket']
			);
			$this->welcome_model->insert($data_to_store);
		}
	}

	private function fixDuplicate()
	{
		$this->load->model('welcome_model');
		$listPegawai = $this->welcome_model->get_duplicate();
		//d($listPegawai);
		//$n = 21;
		foreach ($listPegawai as $list) {
			$id = $list['id'];
			$data = array(
				'NRK' => $list['NIP']
			);
			//$n++;
			$this->welcome_model->update($id, $data);
		}
	}

	private function updateStatusId()
	{
		$this->load->model('welcome_model');
		$listPegawai = $this->welcome_model->get('NIP', 'master_pegawai', [['phl', 1]], null , 1);
		//d($listPegawai);
		$data = ['status_id' => 1];
		foreach ($listPegawai as $list) {
			$this->welcome_model->updateData('users', ['NIP', $list['NIP']], $data);
		}
	}
	*/
}
