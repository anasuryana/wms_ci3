<?php
class TECPRTSUB_mod extends CI_Model {	
	private $TABLENAME = "ENG_TECPRTSUB";
	public function __construct()
    {
        $this->load->database();
    }

    public function select_where($columns, $where)
	{
        $this->db->select($columns);
		$query = $this->db->from($this->TABLENAME)->where($where)->get();
		return $query->result_array();
    }
}