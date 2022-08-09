<?php

class POSTING_mod extends CI_Model {
	private $TABLENAME = "POSTING_TBL";	
	public function __construct()
    {
        $this->load->database();
    }
    public function insert($data){
        $this->db->insert($this->TABLENAME,$data);
        return $this->db->affected_rows();
    }
    public function check_Primary($data)
    {        
        return $this->db->get_where($this->TABLENAME,$data)->num_rows();
    }
    public function select_unfinished($doc)
    {        
        $this->db->from($this->TABLENAME);
        $this->db->where("POSTING_DOC", $doc)->where("POSTING_FINISHED_AT IS NULL", null, false);
		$query = $this->db->get();
		return $query->result_array();
    }
    public function update_where($pdata, $pwhere)
    {
        $this->db->where($pwhere);
        $this->db->update($this->TABLENAME, $pdata);
        return $this->db->affected_rows();        
    }
}