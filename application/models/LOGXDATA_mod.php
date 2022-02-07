<?php

class LOGXDATA_mod extends CI_Model {
	private $TABLENAME = "LOGXDATA_TBL";
	public function __construct()
    {
        $this->load->database();
    }	
	
	public function insert($data)
    {
        $qry = "INSERT INTO ".$this->TABLENAME." 
		VALUES(?,?,getdate())";
		$this->db->query($qry , array($data['LOGXDATA_USER'],$data['LOGXDATA_MENU']) );
        return $this->db->affected_rows();
    }
    
    public function selectlast_n($pn){
        $this->db->limit($pn);
        $this->db->from($this->TABLENAME.' a'); 
        $this->db->select("a.*, MSTEMP_FNM");        
        $this->db->join('MSTEMP_TBL', 'LOGXDATA_USER=MSTEMP_ID', 'left');                
        $this->db->order_by('LOGXDATA_DT DESC');
        $query = $this->db->get();
		return $query->result_array();
    }
	

}