<?php
class Petugas_model extends CI_Model {

    public function __construct()
	{
        $this->load->database();
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

	public function get_x($selectStrList, $tableName, $resultType, $whereArr=null, $likeArr=null, $joinArr=null, $orderArr=null, $limitArr=null, $or_whereArr=null, $or_likeArr=null )
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
				$retVal = (isset($like[2])) ? $like[2] : 'both' ;
				$this->db->like('LOWER(' .$like[0]. ')', strtolower($like[1]), $retVal );
			}
			$this->db->group_end();
		}

		$i = 0;
		if(! is_null($or_whereArr) ){
			$this->db->group_start();
			foreach ($or_whereArr as $ow) {
				if($i == 0){
					$this->db->where($ow[0], $ow[1]);
				}else{
					$this->db->or_where($ow[0], $ow[1]);
				}
				$i++;
			}
			$this->db->group_end();
		}

		$j = 0;
		if(! is_null($or_likeArr) ){
			$this->db->group_start();
			foreach ($or_likeArr as $like) {
				$retVal = (isset($like[2])) ? $like[2] : 'both' ;
				if($j == 0){
					$this->db->like('LOWER(' .$like[0]. ')', strtolower($like[1]), $retVal);
				}else{
					$this->db->or_like('LOWER(' .$like[0]. ')', strtolower($like[1]), $retVal);
				}
				$j++;
			}
			$this->db->group_end();
		}

        if(! is_null($joinArr)){
			foreach ($joinArr as $join) {
				$this->db->select($join[2]);
                $this->db->join($join[1], $join[0].'.'.$join[3].'='.$join[1].'.'.$join[4], 'left');
			}
		}

        if(! is_null($orderArr)){
			$this->db->order_by($orderArr[0], $orderArr[1]);
		}
        if(! is_null($limitArr)){
			$this->db->limit($limitArr[0], $limitArr[1]);
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

	public function get_masterAPD_groupbyjenis($mj_id)
	{
		$this->db->select('id_ma, tahun, foto_mapd');
		$this->db->from('master_apd');
		$this->db->select('master_merk.merk');
		$this->db->join('master_merk', 'master_apd.mm_id =master_merk.id_mm', 'left');
		$this->db->where('master_apd.mj_id', $mj_id);
		$query = $this->db->get();
		return $query->result_array();
	}

	public function get_masterKondisi_groupbyjenis($mj_id)
	{
		$this->db->select('');
		$this->db->from('master_jenis_kondisi');
		$this->db->select('master_kondisi.id_mk, master_kondisi.nama_kondisi, master_kondisi.keterangan');
		$this->db->join('master_kondisi', 'master_jenis_kondisi.mk_id =master_kondisi.id_mk', 'left');
		$this->db->where('master_jenis_kondisi.mj_id', $mj_id);
		$query = $this->db->get();
		return $query->result_array();
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

	public function get_controller($jab_id)
    {
        $this->db->select('master_jabatan.id_mj');
		$this->db->from('master_jabatan');
        $this->db->select('master_controller.controller, master_controller.config, master_controller.role_id');
		$this->db->join('master_controller', 'master_jabatan.mc_id =master_controller.id', 'left');
        $this->db->where('master_jabatan.id_mj', $jab_id);
		$query = $this->db->get();
		return $query->row_array();
    }

	public function get_list_periode_input()
    {
        $query = $this->db->query("SELECT DISTINCT periode_input
                                FROM apd");
        return $query->result_array();
    }
}