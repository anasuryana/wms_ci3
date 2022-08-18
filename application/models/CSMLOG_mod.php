<?php

class CSMLOG_mod extends CI_Model {
	private $TABLENAME = "CSMLOG_TBL";
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

    public function select_lastLine($doc, $aju){
        $qry = "select ISNULL(MAX(CSMLOG_LINE),0) lser 
        FROM ".$this->TABLENAME." WHERE CSMLOG_DOCNO=? AND CSMLOG_SUPZAJU=?";
        $query =  $this->db->query($qry, [$doc, $aju]);
        if ($query->num_rows()>0){
            $ret = $query->row();
            return $ret->lser;
        } else {
            return 0;
        }
    }
}