<?php

class MTPB_imod extends CI_Model {
	private $TABLENAME = "referensi_jenis_tpb";
	public function __construct()
    {
        $this->load->database();
    }
	public function selectAll()
	{		
        $DBUse = $this->load->database('ceisadb',TRUE);
        $DBUse->select("KODE_JENIS_TPB, URAIAN_JENIS_TPB");
		$query = $DBUse->get($this->TABLENAME);
		return $query->result_array();
	}
}