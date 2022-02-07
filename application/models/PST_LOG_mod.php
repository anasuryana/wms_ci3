<?php

class PST_LOG_mod extends CI_Model {
	private $TABLENAME = "PST_LOG_TBL";
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
}