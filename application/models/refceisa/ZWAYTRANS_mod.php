<?php

class ZWAYTRANS_mod extends CI_Model {
	private $TABLENAME = "ZCARANG_TBL";
	public function __construct()
    {
        $this->load->database();
    }	

    public function selectAll()
	{		
		$query = $this->db->get($this->TABLENAME);
		return $query->result_array();
    }  
    
    public function selectbyvar($pvar)
	{		
        $this->db->where($pvar);
		$query = $this->db->get($this->TABLENAME);
		return $query->result();
	}
	
	public function insert($data)
    {
        $this->db->insert($this->TABLENAME,$data);
        return $this->db->affected_rows();
	}
	public function updatebyId($pdata, $pwhere)
    {        
        $this->db->where($pwhere);
        $this->db->update($this->TABLENAME, $pdata);
        return $this->db->affected_rows();
	}
	public function check_Primary($data)
    {        
        return $this->db->get_where($this->TABLENAME,$data)->num_rows();
    }
    public function selectbyid_lk($pid)
	{		
		$this->db->like('KODE_CARA_ANGKUT', $pid);
		$query = $this->db->get($this->TABLENAME,10);
		return $query->result();
	}
	public function selectbynm_lk($pid)
	{		
		$this->db->like('URAIAN_CARA_ANGKUT', $pid);
		$query = $this->db->get($this->TABLENAME,10);
		return $query->result();
    }
}