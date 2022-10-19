<?php

class DLVH_mod extends CI_Model {
	private $TABLENAME = "DLVH_TBL";
	public function __construct()
    {
        $this->load->database();
    }

    public function insert($data)
    {
        $this->db->insert($this->TABLENAME,$data);        
        return $this->db->affected_rows();
    }   
    
    public function insertb($data)
    {        
        $this->db->insert_batch($this->TABLENAME,$data);
        return $this->db->affected_rows();
    }  

    public function check_Primary($data)
    {
        return $this->db->get_where($this->TABLENAME,$data)->num_rows();
    }
    
    public function updatebyVAR($pdata, $pwhere)
    {        
        $this->db->where($pwhere);
        $this->db->update($this->TABLENAME, $pdata);
        return $this->db->affected_rows();
    }  
}