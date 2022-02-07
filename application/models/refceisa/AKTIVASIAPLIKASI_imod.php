<?php

class AKTIVASIAPLIKASI_imod extends CI_Model {
	private $TABLENAME = "aktivasi_aplikasi";
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