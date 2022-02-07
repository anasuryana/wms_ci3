<?php

class MADE_mod extends CI_Model {
	private $TABLENAME = "MMADE_TBL";
	public function __construct()
    {
        $this->load->database();
    }
	public function selectAll()
	{
		$this->db->select("MMADE_CD,MMADE_NM");
		$query = $this->db->get($this->TABLENAME);
		return $query->result_array();
	}
	
	public function insert($data)
    {
        $this->db->insert($this->TABLENAME,$data);
        return $this->db->affected_rows();
	}
	public function updatebyId($pdata, $pkey)
    {        
        $this->db->where('MMADE_CD',$pkey);
        $this->db->update($this->TABLENAME, $pdata);
        return $this->db->affected_rows();
	}
	public function check_Primary($data)
    {        
        return $this->db->get_where($this->TABLENAME,$data)->num_rows();
	}
}