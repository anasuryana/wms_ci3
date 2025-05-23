<?php

class DLVSCR_BB_mod extends CI_Model {
	private $TABLENAME = "DLVSCR_BB_TBL";
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

    public function deletebyID($parr){        
        $this->db->where($parr);
        $this->db->delete($this->TABLENAME);
        return $this->db->affected_rows();
    }

    function select_where($pwhere) {        
        $this->db->from($this->TABLENAME);        
        $this->db->where($pwhere);
		$query = $this->db->get();
		return $query->result_array();
    }

    public function select_lastline($pdoc){
        $qry = "SELECT MAX(DLVSCR_BB_LINE) lser FROM ".$this->TABLENAME." WHERE DLVSCR_BB_TXID=?";
        $query = $this->db->query($qry, [$pdoc]);
        if ($query->num_rows()>0){
            $ret = $query->row();
            return $ret->lser;
        } else {
            return 0;
        }
    }
}