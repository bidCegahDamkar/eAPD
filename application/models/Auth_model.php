<?php
class Auth_model extends CI_Model {

    public function __construct()
	{
        $this->load->database();
    }

    public function get_controller($jab_id)
    {
        $this->db->select('master_jabatan.id_mj');
		$this->db->from('master_jabatan');
        $this->db->select('master_controller.controller');
		$this->db->join('master_controller', 'master_jabatan.mc_id =master_controller.id', 'left');
        $this->db->where('master_jabatan.id_mj', $jab_id);
		$query = $this->db->get();
		return $query->row_array();
    }
}