<?php 
class My404 extends CI_Controller 
{
 public function __construct() 
 {
    parent::__construct();
    $this->load->database();
    $this->load->library('ion_auth');
    $this->load->model('petugas_model');
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
      if(! is_array($user_id)){
         redirect('auth/login', 'refresh');
      }
      $this->data['controller'] = $user_id['controller'];
   }

 public function index() 
 { 
   $this->authenticate();
    $this->output->set_status_header('404'); 
    $this->data['message'] = 'Anda tidak berwenang mengakses halaman ini';
    $this->load->view('errors', $this->data);
 } 
} 