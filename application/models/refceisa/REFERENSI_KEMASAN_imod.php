<?php

class REFERENSI_KEMASAN_imod extends CI_Model {
	private $TABLENAME = "referensi_kemasan";
	public function __construct()
    {
        $this->load->database();
	}		
	
	public function selectAll()
	{		
        $DBUse = $this->load->database('ceisadb',TRUE);
        $DBUse->order_by("URAIAN_KEMASAN");
		$query = $DBUse->get($this->TABLENAME);
		return $query->result();
	}

	public function select_like($plike)
	{		
        $DBUse = $this->load->database('ceisadb',TRUE);
        $DBUse->like($plike);
		$query = $DBUse->get($this->TABLENAME);
		return $query->result_array();
	}	
}