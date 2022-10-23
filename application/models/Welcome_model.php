<?php
class Welcome_model extends CI_Model {


	/**
	* Responsable for auto load the database
	* @return void
	*/
	public function __construct()
	{
		$this->load->database();
	}

    public function getMasterPegawai()
    {
        $this->db->select('*');
		$this->db->from('master_pegawai');
		$query = $this->db->get();
		return $query->result_array();
    }

    function insert($data)
	{
		if ($this->db->insert('users', $data)){
			return true;
		}else{
			return false;
		}
	}

    public function get_duplicate()
	{
		$this->db->select('id,NRK,NIP');
		$this->db->from('master_pegawai');
		$this->db->where('NRK', '2019');
		$query = $this->db->get();
		return $query->result_array();
	}

    function update($id, $data)
	{
		$this->db->where('id', $id);
		if ($this->db->update('master_pegawai', $data)){
			return true;
		}else {
			return false;
		}
	}

	/*
	 common function to get data from database
	 params:
		selectStrList = 'id,name,..'
		tableName = 'name_of_the_table'
		whereArr = [['id', 1], ['name', 'kuswan']] or Null
		likeArr = [['id', 1], ['name', 'kuswan']] or Null
		resultType = 1 -> result_array
					2 -> row_array
					3 -> num_array
	*/

	public function get($selectStrList, $tableName, $whereArr=null, $likeArr=null, $resultType)
    {
        $this->db->select($selectStrList);
		$this->db->from($tableName);
		if(! is_null($whereArr)){
			$this->db->group_start();
			foreach ($whereArr as $where) {
				$this->db->where($where[0], $where[1]);
			}
			$this->db->group_end();
		}
		if(! is_null($likeArr)){
			$this->db->group_start();
			foreach ($likeArr as $like) {
				$this->db->like($like[0], $like[1]);
			}
			$this->db->group_end();
		}
		$query = $this->db->get();
		if ($resultType === 1) {
			return $query->result_array();
		} else if ($resultType === 2) {
			return $query->row_array();
		} else if ($resultType === 3) {
			return $query->num_rows();
		} else {
			return null;
		}
    }

	public function insertData($tableName, $data)
	{
		return $this->db->insert($tableName, $data);
	}

	/*
	whereArrS = ['id', 1]
	*/

	public function updateData($table_name, $whereArrS, $data)
	{
		$this->db->where($whereArrS[0], $whereArrS[1]);
		$this->db->update($table_name, $data);
		return $this->db->affected_rows();
    }

	public function softDelete($table_name, $whereArrS)
	{
		$data = ['deleted' => 1];
		$this->db->where($whereArrS[0], $whereArrS[1]);
		$this->db->update($table_name, $data);
		return $this->db->affected_rows();
    }
}