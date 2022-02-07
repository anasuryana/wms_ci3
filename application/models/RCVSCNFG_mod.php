<?php

class RCVSCNFG_mod extends CI_Model {
	private $TABLENAME = "RCVSCNFG_TBL";
	public function __construct()
    {
        $this->load->database();
    }

    public function insert($data)
    {                
        $this->db->insert($this->TABLENAME,$data);        
        return $this->db->affected_rows();
    }

    public function lastserialid(){       
        $qry = "select TOP 1 substring(RCVSCNFG_ID, 9, 20) lser from RCVSCNFG_TBL 
        WHERE convert(date, RCVSCNFG_LUPDT) = convert(date,GETDATE())
        ORDER BY convert(bigint,SUBSTRING(RCVSCNFG_ID,9,11)) desc";
        $query =  $this->db->query($qry);        
        if ($query->num_rows()>0){
            $ret = $query->row();
            return $ret->lser;
        } else {
            return '0';
        }        
    }

    
    
}