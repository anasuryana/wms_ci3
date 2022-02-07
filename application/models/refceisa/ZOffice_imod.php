<?php

class ZOffice_imod extends CI_Model {
	private $TABLENAME = "referensi_kantor_pabean";
	public function __construct()
    {
        $this->load->database();
    }
	public function selectAll()
	{		               
        $DBUse = $this->load->database('ceisadb',TRUE);
        $DBUse->order_by("URAIAN_KANTOR asc");
		$query = $DBUse->get($this->TABLENAME);
		return $query->result_array();
    }
    public function check_Primary($data)
    {
        return $this->db->get_where($this->TABLENAME,$data)->num_rows();
    }
}