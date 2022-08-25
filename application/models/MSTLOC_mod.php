<?php

class MSTLOC_mod extends CI_Model {
	private $TABLENAME = "MSTLOC_TBL";
	public function __construct()
    {
        $this->load->database();
    }
	public function selectAll()
	{
		$this->db->from($this->TABLENAME.' a');
		$this->db->join('MSTLOCG_TBL b', 'a.MSTLOC_GRP=b.MSTLOCG_ID');
		$query = $this->db->get();
		return $query->result_array();
	}

	public function selectnamebyid($pid){
		$this->db->select("MITM_SPTNO");
		$this->db->where('MITM_ITMCD', $pid);
		$query = $this->db->get($this->TABLENAME);
		return $query->result_array();
	}

	public function selectwithparent_byid($parentid){
		$this->db->from($this->TABLENAME.' a');
		$this->db->join('MSTLOCG_TBL b', 'a.MSTLOC_GRP=b.MSTLOCG_ID');
		$this->db->where('MSTLOC_GRP', $parentid);
		$query = $this->db->get();
		return $query->result_array();
	}
	
	function select_subloc($subloc)
	{
		$this->db->select("ITMLOC_BG,ITMLOC_ITM,RTRIM(MITM_SPTNO) SPTNO,RTRIM(MITM_ITMD1) ITMD1,MSTLOC_GRP,MSTLOC_CD");
		$this->db->join("ITMLOC_TBL", "MSTLOC_CD=ITMLOC_LOC AND MSTLOC_GRP=ITMLOC_LOCG", "LEFT");
		$this->db->join("MITM_TBL", "ITMLOC_ITM=MITM_ITMCD", "LEFT");
		$this->db->like("MSTLOC_CD", $subloc);
		$query = $this->db->get($this->TABLENAME);
		return $query->result_array();
	}

	public function insert($data)
    {        
        $this->db->insert($this->TABLENAME,$data);
        return $this->db->affected_rows();
    }
	public function check_Primary($data)
    {        
        return $this->db->get_where($this->TABLENAME,$data)->num_rows();
	}
	
	public function updatebyId($pdata, $pkeys)
    {        
        $this->db->where($pkeys);
        $this->db->update($this->TABLENAME, $pdata);
        return $this->db->affected_rows();
	}
	public function updatebyUnique($pdata, $pkeys)
    {        
        $this->db->where($pkeys);
        $this->db->update($this->TABLENAME, $pdata);
        return $this->db->affected_rows();
	}
	public function selectLastOne()
    {
		$query = $this->db->get('v_locdltsid');
		$ret = $query->row();
        return $ret->lastid;
    }
}