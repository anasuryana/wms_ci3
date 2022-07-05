<?php

class SCNDOCITM_mod extends CI_Model {
	private $TABLENAME = "SCNDOCITM_TBL";
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
	
	public function updatebyId($pdata, $pkeys)
    {        
        $this->db->where($pkeys);
        $this->db->update($this->TABLENAME, $pdata);
        return $this->db->affected_rows();
    }

    public function lastserialid($doc){       
        $qry = "select max(SCNDOCITM_LINE) lser from ".$this->TABLENAME." 
        WHERE SCNDOCITM_DOCNO=?";
        $query =  $this->db->query($qry, [$doc]);
        if ($query->num_rows()>0){
            $ret = $query->row();
            return $ret->lser;
        } else {
            return 0;
        }
    }
}