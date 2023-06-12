<?php

class MPurposeDLV_imod extends CI_Model {
	private $TABLENAME = "referensi_tujuan_pengiriman";
	public function __construct()
    {
        $this->load->database();
    }
	public function selectAll()
	{		
        $DBUse = $this->load->database('ceisadb',TRUE);
		$qry = "SELECT KODE_DOKUMEN, KODE_TUJUAN_PENGIRIMAN, URAIAN_TUJUAN_PENGIRIMAN FROM referensi_tujuan_pengiriman";
        $query = $DBUse->query($qry);
		return $query->result_array();
	}
}