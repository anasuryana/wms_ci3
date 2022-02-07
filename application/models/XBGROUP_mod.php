<?php

class XBGROUP_mod extends CI_Model {
	private $TABLENAME = "XMBSG_TBL";
	public function __construct()
    {
        $this->load->database();
    }		

    public function selectall(){
        $this->db->from($this->TABLENAME);        
		$query = $this->db->get();
		return $query->result();
    }

    public function selectbyVAR( $dataw){
        $this->db->from($this->TABLENAME);        
        $this->db->where($dataw);
		$query = $this->db->get();
		return $query->result();
    }
   
}