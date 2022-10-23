<?php
class My_models extends CI_Model {

    public function __construct()
	{
        $this->load->database();
    }

	public function get_userTipe($jab_id)
    {
        $this->db->select('tipe_user_id');
		$this->db->from('master_jabatan');
        $this->db->where('id_mj', $jab_id);
		$query = $this->db->get();
		return $query->row_array();
    }

    /*
	 common function to get data from database
	 params:
		selectStrList = 'id,name,..'
		tableName = 'name_of_the_table'
		whereArr = [['id', 1], ['name', 'kuswan']] or Null
		likeArr = [['id', 1], ['name', 'kuswan']] or Null
        orderArr = ['id', 'ASC/DESC/RANDOM'] or Null
        limit = [20, 10] or Null
        joinArr = [[$tableLeftName [0], $tableRightName [1], $selectSTR [2], $tableLeftID [3], $tableRightID [4] ]] or Null  // $selectSTR = 'master_kondisi.id_mk, master_kondisi.nama_kondisi'
		resultType = 1 -> result_array
					2 -> row_array
					3 -> num_array
	*/

	public function get($selectStrList, $tableName, $resultType, $whereArr=null, $likeArr=null, $joinArr=null, $orderArr=null, $limitArr=null, $or_whereArr=null, $or_likeArr=null )
    {
        $this->db->select($selectStrList);
		$this->db->from($tableName);

		//joinArr
        if(! is_null($joinArr)){
			foreach ($joinArr as $join) {
				$this->db->select($join[2]);
                $this->db->join($join[1], $join[0].'.'.$join[3].'='.$join[1].'.'.$join[4], 'left');
			}
		}

		//whereArr
		if((! is_null($whereArr)) ){
			$this->db->group_start();
			foreach ($whereArr as $where) {
				$this->db->where($where[0], $where[1]);
			}
			$this->db->group_end();
		}
		
		//likeArr
		if((! is_null($likeArr)) ){
			$this->db->group_start();
			foreach ($likeArr as $like) {
				$retVal = (isset($like[2])) ? $like[2] : 'both' ;
				$this->db->like('LOWER(' .$like[0]. ')', strtolower($like[1]), $retVal );
			}
			$this->db->group_end();
		}

		//or_whereArr
		$i = 0;
		if((! is_null($or_whereArr)) ){
			$this->db->group_start();
			foreach ($or_whereArr as $or_where) {
				if($i == 0){
					$this->db->where($or_where[0], $or_where[1]);
				}else{
					$this->db->or_where($or_where[0], $or_where[1]);
				}
				$i++;
			}
			$this->db->group_end();
		}

		//or_likeArr
		$j = 0;
		if((! is_null($or_likeArr)) ){
			$this->db->group_start();
			foreach ($or_likeArr as $or_like) {
				$retVal = (isset($or_like[2])) ? $or_like[2] : 'both' ;
				if($j == 0){
					$this->db->like('LOWER(' .$or_like[0]. ')', strtolower($or_like[1]), $retVal);
				}else{
					$this->db->or_like('LOWER(' .$or_like[0]. ')', strtolower($or_like[1]), $retVal);
				}
				$j++;
			}
			$this->db->group_end();
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

	public function get_userData($user_id)
	{
		$this->db->select('photo, nama, NRK, NIP, users.no_telepon, email');
		$this->db->from('users');
		//get kondisi
		$this->db->select('master_jabatan.nama_jabatan');
		$this->db->join('master_jabatan', 'users.jabatan_id =master_jabatan.id_mj', 'left');
		//get tempat tugas
		$this->db->select('master_pos.nama_pos');
		$this->db->join('master_pos', 'users.kode_pos = master_pos.kode_pos', 'left');
		$this->db->where('users.id', $user_id);
		$query = $this->db->get();
		return $query->row_array();
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

	/*
	 common function to get data from database
	 params:
		selectStrList = 'id,name,..'
		tableName = 'name_of_the_table'
		whereArr = [['id', 1], ['name', 'kuswan']] or Null
		likeArr = [['id', 1], ['name', 'kuswan']] or Null
        orderArr = ['id', 'ASC/DESC/RANDOM'] or Null
        limit = [20, 10] or Null
        joinArr = [[$tableLeftName [0], $tableRightName [1], $selectSTR [2], $tableLeftID [3], $tableRightID [4] ]] or Null  // $selectSTR = 'master_kondisi.id_mk, master_kondisi.nama_kondisi'
		resultType = 1 -> result_array
					2 -> row_array
					3 -> num_array
		$search = search str
		$search_columns = array(
            0=>'no_gedung',
            1=>'nama_gedung',
			2=>'alamat_gedung',
			3=>'tabel_wilayah.Wilayah',
			4=>'tabel_kecamatan.Kecamatan',);
	*/

	public function get_ajax($selectStrList, $tableName, $resultType, $whereArr=null, $likeArr=null, $joinArr=null, $orderArr=null, $limitArr=null, $search='', $search_columns=[] )
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

        if(! is_null($joinArr)){
			foreach ($joinArr as $join) {
				$this->db->select($join[2]);
                $this->db->join($join[1], $join[0].'.'.$join[3].'='.$join[1].'.'.$join[4], 'left');
			}
		}

        if(! is_null($orderArr)){
			$this->db->order_by($orderArr[0], $orderArr[1]);
		}

		if(!empty($search) )
        {
            $x=0;
            foreach($search_columns as $sterm)
            {
                if($x==0)
                {
					$this->db->group_start();
                    $this->db->like($sterm,$search);
                }
                else
                {
                    $this->db->or_like($sterm,$search);
				}
				if(count($search_columns) - 1 == $x) 
				{
					$this->db->group_end(); 
				}
                $x++;
            }                 
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

	public function get_list_gedung($table_gedung, $table_fungsi, $table_kepemilikkan, $table_status, $coulum_table_gedung, $start, $length, $order, $dir, $search, $mode='array', 
	$limit=TRUE)
	{
		$search_columns = array(
            0=>'no_gedung',
            1=>'nama_gedung',
			2=>'alamat_gedung',
			3=>'tabel_wilayah.Wilayah',
			4=>'tabel_kecamatan.Kecamatan',
			5=>'tabel_kelurahan.Kelurahan',
            6=>'fungsi_gedung',
            7=>'kepemilikkan_gedung',
			8=>'nama_kolom_statusGedung',
			8=>'kategori_kolomHslPemeriksaan',
			9=>'expired'
        );
		$this->db->select($coulum_table_gedung);
		$this->db->from($table_gedung.' as tabelGedung');
		$this->db->select($table_fungsi.'.fungsi_gedung');
		$this->db->join($table_fungsi, 'tabelGedung.fungsi ='.$table_fungsi.'.id_fungsi_gedung', 'left');
		$this->db->select($table_kepemilikkan.'.kepemilikkan_gedung');
		$this->db->join($table_kepemilikkan, 'tabelGedung.kepemilikan ='.$table_kepemilikkan.'.id_kepemilikkan_gedung', 'left');
		$this->db->select($table_status.'.nama_kolom_statusGedung,'.$table_status.'.kategori_kolomHslPemeriksaan');
		$this->db->join($table_status, 'tabelGedung.last_status ='.$table_status.'.id_kolom_statusGedung', 'left');
		$this->db->select('tabel_wilayah.Wilayah as wilayah');
		$this->db->join('tabel_wilayah', 'tabelGedung.Wilayah = tabel_wilayah.id', 'left');
		$this->db->select('tabel_kecamatan.Kecamatan as kecamatan');
		$this->db->join('tabel_kecamatan', 'tabelGedung.Kecamatan = tabel_kecamatan.id', 'left');
		$this->db->select('tabel_kelurahan.Kelurahan as kelurahan');
		$this->db->join('tabel_kelurahan', 'tabelGedung.Kelurahan = tabel_kelurahan.id', 'left');
		$this->db->where('tabelGedung.deleted', 0);
		//$this->db->limit(10);
		if($order !=null)
        {
            $this->db->order_by($order, $dir);
        }
        
        if(!empty($search) )
        {
            $x=0;
            foreach($search_columns as $sterm)
            {
                if($x==0)
                {
					$this->db->group_start();
                    $this->db->like($sterm,$search);
                }
                else
                {
                    $this->db->or_like($sterm,$search);
				}
				if(count($search_columns) - 1 == $x) 
				{
					$this->db->group_end(); 
				}
                $x++;
            }                 
		}
		
		if($limit){
			$this->db->limit($length,$start);
		}
		$query = $this->db->get();
		if($mode == 'array'){
			return $query->result_array();
		}else{
			return $query->num_rows();
		}
	}

    
}