<?php

class MSTLOCG_mod extends CI_Model {
	private $TABLENAME = "MSTLOCG_TBL";
	public function __construct()
    {
        $this->load->database();
	}
	
	public function selectall(){
		$this->db->select("MSTLOCG_ID,MSTLOCG_NM");
		$this->db->order_by("MSTLOCG_NM asc");
		$query = $this->db->get($this->TABLENAME);
		return $query->result_array();
	}
	public function selectall_where_CODE_in($pcode){
		$this->db->select("MSTLOCG_ID,MSTLOCG_NM");
		$this->db->where_in("MSTLOCG_ID", $pcode);
		$this->db->order_by("MSTLOCG_NM asc");
		$query = $this->db->get($this->TABLENAME);
		return $query->result_array();
	}
	public function selectall_by_dedict($pval){
		$this->db->select("MSTLOCG_ID,MSTLOCG_NM");
		$this->db->where("MSTLOCG_DEDICATED", $pval);
		$this->db->order_by("MSTLOCG_NM asc");
		$query = $this->db->get($this->TABLENAME);
		return $query->result_array();
	}

	public function select_cols_lk($pcols, $plikes)
	{
		$this->db->select($pcols);
		$this->db->like($plikes);
		$query = $this->db->get($this->TABLENAME);
		return $query->result();
	}
	

	public function selectbynm_lk($pid)
	{
		$this->db->select("MSTLOCG_ID,MSTLOCG_NM");		
		$this->db->like('MSTLOCG_NM', $pid);
		$query = $this->db->get($this->TABLENAME,100);
		return $query->result();
	}

	public function selectbynm_lk_pnd()
	{
		$this->db->select("MSTLOCG_ID,MSTLOCG_NM");
		$this->db->from($this->TABLENAME);
		$this->db->join("TXROUTE_TBL" , "MSTLOCG_ID=TXROUTE_WHOUT");
		$this->db->like('TXROUTE_ID', 'PEN');
		$query = $this->db->get();
		return $query->result_array();
	}

	public function selectbyID($pwhere)
	{
		$this->db->select("MSTLOCG_ID,MSTLOCG_NM");
		$this->db->where('MSTLOCG_ID', $pwhere);
		$query = $this->db->get($this->TABLENAME);
		return $query->result();
	}

	public function selectLastOne()
    {
		$query = $this->db->get('v_locltsid');
		$ret = $query->row();
        return $ret->lastid;
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
	
	public function updatebyId($pdata, $pkey)
    {        
        $this->db->where('MSTLOCG_ID',$pkey);
        $this->db->update($this->TABLENAME, $pdata);
        return $this->db->affected_rows();
    }
}