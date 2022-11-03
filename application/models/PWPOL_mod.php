<?php

class PWPOL_mod extends CI_Model {
	private $TABLENAME = "PWPOL_TBL";
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

    public function updatebyId($pdata, $pkey)
    {        
        $this->db->where($pkey);
        $this->db->update($this->TABLENAME, $pdata);
        return $this->db->affected_rows();
    }

    public function select()
	{
        $this->db->from($this->TABLENAME);        
		$query = $this->db->get();
		return $query->result_array();
    }
}