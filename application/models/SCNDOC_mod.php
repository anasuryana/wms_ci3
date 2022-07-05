<?php

class SCNDOC_mod extends CI_Model {
	private $TABLENAME = "SCNDOC_TBL";
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

    public function select_today(){
        $this->db->from('wms_v_scandoc_today');
		$query = $this->db->get();
		return $query->result_array();
    }
	
	public function updatebyId($pdata, $pkeys)
    {        
        $this->db->where($pkeys);
        $this->db->update($this->TABLENAME, $pdata);
        return $this->db->affected_rows();
    }
    public function lastserialid(){       
        $qry = "select TOP 1 substring(SCR_DOC, 12, 3) lser from SCR_TBL
        WHERE convert(date, SCR_LUPDT) = convert(date,GETDATE())
        ORDER BY convert(bigint,SUBSTRING(SCR_DOC,12,3)) desc";
        $query =  $this->db->query($qry);        
        if ($query->num_rows()>0){
            $ret = $query->row();
            return $ret->lser;
        } else {
            return '0';
        }
    }    
}