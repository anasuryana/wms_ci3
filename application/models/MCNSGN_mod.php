<?php

class MCNSGN_mod extends CI_Model {
	private $TABLENAME = "MCNSGN_TBL";
	public function __construct()
    {
        $this->load->database();
    }
	public function selectAll()
	{		
		$query = $this->db->get($this->TABLENAME);
		return $query->result_array();
    }
    public function selectbyID($ppar)
	{		
        $this->db->where('MCNSGN_CUSCD', $ppar);
		$query = $this->db->get($this->TABLENAME);
		return $query->result_array();
	}
	
	public function insert($data)
    {
        $this->db->insert($this->TABLENAME,$data);
        return $this->db->affected_rows();
	}
	public function updatebyId($pdata, $pkey)
    {        
        $this->db->where($pkey);
        $this->db->update($this->TABLENAME, $pdata);
        return $this->db->affected_rows();
	}
	public function check_Primary($data)
    {        
        return $this->db->get_where($this->TABLENAME,$data)->num_rows();
    }
    
    public function getdeleted($pwhere)
    {        
        $this->db->where($pwhere);
        $this->db->delete($this->TABLENAME);
        return $this->db->affected_rows();
	}
}