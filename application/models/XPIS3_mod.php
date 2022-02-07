<?php 
class XPIS3_mod extends CI_Model {
	private $TABLENAME = "XPIS3";
	public function __construct()
    {
        $this->load->database();
    }		

    public function selectall(){
        $this->db->from($this->TABLENAME);        
		$query = $this->db->get();
		return $query->result();
    }
}