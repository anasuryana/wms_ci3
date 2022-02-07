<?php

class TXROUTE_mod extends CI_Model {
	private $TABLENAME = "TXROUTE_TBL";
	public function __construct()
    {
        $this->load->database();
    }
	
	
	public function insert($data)
    {        
        $this->db->insert($this->TABLENAME,$data);
        return $this->db->affected_rows();
    }
	public function check_Primary($data)
    {        
        return $this->db->get_where($this->TABLENAME,$data)->num_rows();
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

    public function updatebyId($pwhere, $pval)
    {        
        $this->db->where($pwhere);
        $this->db->update($this->TABLENAME, $pval);
        return $this->db->affected_rows();
    }
		
}