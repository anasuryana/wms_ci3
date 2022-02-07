<?php

class ZDestinationOffice_mod extends CI_Model {
	private $TABLENAME = "ZKANTOR_TBL";
	public function __construct()
    {
        $this->load->database();
    }
	public function selectAll()
	{		               
        
        $this->db->order_by("URAIAN_KANTOR asc");
		$query = $this->db->get($this->TABLENAME);
		return $query->result_array();
    }
    public function check_Primary($data)
    {
        return $this->db->get_where($this->TABLENAME,$data)->num_rows();
    }
}