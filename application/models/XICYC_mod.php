<?php

class XICYC_mod extends CI_Model {
	private $TABLENAME = "XICYC";
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

    function select_date($pWhere)
    {
        $this->db->select("CONVERT(DATE,ICYC_STKDT) ICYC_STKDT");
        $this->db->from($this->TABLENAME);
        $this->db->where($pWhere);
        $this->db->group_by('ICYC_STKDT');
		$query = $this->db->get();
		return $query->result();
    }
    
    public function select_for_it_inventory( $dataw){
        $this->db->select("UPPER(RTRIM(ICYC_ITMCD)) ITH_ITMCD,ICYC_STKQT STOCKQTY,UPPER(RTRIM(ICYC_WHSCD)) ICYC_WHSCD");
        $this->db->from($this->TABLENAME);
        $this->db->where($dataw);
		$query = $this->db->get();
		return $query->result_array();
    }
   
}