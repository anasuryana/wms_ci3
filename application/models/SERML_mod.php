<?php

class SERML_mod extends CI_Model {
	private $TABLENAME = "SERML_TBL";
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

    public function deletebyID($parr){        
        $this->db->where($parr);
        $this->db->delete($this->TABLENAME);
        return $this->db->affected_rows();
    }

    public function select_for_calculation($pid){
        $this->db->select("SER_ID,SER_DOC,SER_QTY");
        $this->db->from($this->TABLENAME);
        $this->db->join("SER_TBL", "SERML_COMID=SER_ID");
        $this->db->where_in("SERML_NEWID", $pid);
		$query = $this->db->get();
		return $query->result_array();
    }
    public function select_trace($pwhere){
        $this->db->select("SERML_TBL.*, A.SER_DOC MAINJOB,B.SER_DOC COMPJOB, B.SER_QTY COMPQTY, CONCAT(MSTEMP_FNM, ' ', MSTEMP_LNM) PIC");
        $this->db->from($this->TABLENAME);
        $this->db->join("SER_TBL A", "SERML_NEWID=A.SER_ID", "LEFT");
        $this->db->join("SER_TBL B", "SERML_COMID=B.SER_ID", "LEFT");
        $this->db->join("MSTEMP_TBL", "SERML_USRID=MSTEMP_ID", "LEFT");
        $this->db->where($pwhere);
		$query = $this->db->get();
		return $query->result_array();
    }
}