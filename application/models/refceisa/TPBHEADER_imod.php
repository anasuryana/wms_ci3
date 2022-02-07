<?php

class TPBHEADER_imod extends CI_Model {
	private $TABLENAME = "tpb_header";
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

	public function ics_ceisahis()
	{		
        $DBUse = $this->load->database('ics',TRUE);        
		//$query = $DBUse->get($this->TABLENAME);

		$qry = "SELECT * FROM tb_ceisa_hist
		INNER JOIN tb_custom_stock_hist ON tb_ceisa_hist.trans_no=tb_custom_stock_hist.trans_no_rcv and 
		WHERE trans_no!='AWAL'
		LIMIT 100";
        $query = $DBUse->query($qry);
        
		return $query->result_array();
	}
}