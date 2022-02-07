<?php

class ZDOK_imod extends CI_Model {
	private $TABLENAME = "referensi_dokumen";
	public function __construct()
    {
        $this->load->database();
    }
	public function selectAll()
	{		
        $DBUse = $this->load->database('ceisadb',TRUE);        
		$query = $DBUse->get($this->TABLENAME);
		return $query->result_array();
	}
}