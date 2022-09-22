<?php

class XWO_mod extends CI_Model {
	private $TABLENAME = "XWO";
	public function __construct()
    {
        $this->load->database();
    }
		
	public function check_Primary($data)
    {        
        return $this->db->get_where($this->TABLENAME,$data)->num_rows();
    }

    function select_cols_where_wono_in($columns,$wono)
    {
        $this->db->select($columns);
        $this->db->from($this->TABLENAME);
        $this->db->where_in("PDPP_WONO", $wono);
		$query = $this->db->get();
		return $query->result_array();
    }
}