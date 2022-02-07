<?php

class DLVCK_mod extends CI_Model {
	private $TABLENAME = "DLVCK_TBL";
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

    public function updatebyVAR($pdata, $pwhere)
    {        
        $this->db->where($pwhere);
        $this->db->update($this->TABLENAME, $pdata);
        return $this->db->affected_rows();
    }

    public function select_lastline($pdoc){
        $qry = "SELECT MAX(DLVCK_LINE) lser FROM $this->TABLENAME WHERE DLVCK_TXID=?";
        $query = $this->db->query($qry, [$pdoc]);
        if ($query->num_rows()>0){
            $ret = $query->row();
            return $ret->lser;
        } else {
            return 0;
        }
    }

    public function select_display($ptxid){        
        $this->db->from($this->TABLENAME." A");
        $this->db->where("DLVCK_TXID", $ptxid);        
		$query = $this->db->get();
		return $query->result_array();
    }
    public function select_display_header($pLike){
        $this->db->group_by("DLVCK_TXID,DLVCK_CUSTDO");
        $this->db->select("DLVCK_TXID,DLVCK_CUSTDO");
        $this->db->from($this->TABLENAME." A");
        $this->db->like($pLike);
		$query = $this->db->get();
		return $query->result_array();
    }

    public function deleteby_filter($pwhere)
    {          
        $this->db->where($pwhere);
        $this->db->delete($this->TABLENAME);
        return $this->db->affected_rows();
    }

}