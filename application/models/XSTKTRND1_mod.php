<?php

class XSTKTRND1_mod extends CI_Model {
	private $TABLENAME = "XSTKTRND1";
	public function __construct()
    {
        $this->load->database();
    }		

    public function selectall(){
        $this->db->from($this->TABLENAME);
		$query = $this->db->get();
		return $query->result();
    }

    public function selectColGroup($cols) {
        $this->db->select($cols);
        $this->db->from($this->TABLENAME);
        $this->db->group_by($cols);
		$query = $this->db->get();
		return $query->result_array();
    }
   
}