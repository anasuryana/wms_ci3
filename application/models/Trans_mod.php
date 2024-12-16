<?php

class Trans_mod extends CI_Model {
	private $TABLENAME = "MSTTRANS_TBL";
	public function __construct()
    {
        $this->load->database();
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
    public function selectbyid($pid){
        $this->db->from($this->TABLENAME);        
        $this->db->where_in('MSTTRANS_ID', $pid);        
		$query = $this->db->get();
		return $query->result();
    }

    public function selectbyid_lk($pid){
        $this->db->from($this->TABLENAME);        
        $this->db->like('MSTTRANS_ID', $pid);        
		$query = $this->db->get();
		return $query->result();
    }

    public function selectall(){
        $this->db->from($this->TABLENAME);    
		$query = $this->db->get();
		return $query->result();
    }

    public function selectallActive(){
        $this->db->from($this->TABLENAME);    
        $this->db->where('deleted_at is null', null, false);    
		$query = $this->db->get();
		return $query->result();
    }

    public function updatebyId($pwhere, $pval)
    {        
        $this->db->where($pwhere);
        $this->db->update($this->TABLENAME, $pval);
        return $this->db->affected_rows();
    }
		
}