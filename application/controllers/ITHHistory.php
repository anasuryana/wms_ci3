<?php
defined('BASEPATH') OR exit('No direct script access allowed');
class ITHHistory extends CI_Controller {

	public function __construct()
	{
		parent::__construct();
		$this->load->model('ITH_mod');		
	}

	public function index()
	{
		echo "sorry";
	}

    public function getLocations() {
        header('Content-Type: application/json');
        $rs = $this->ITH_mod->select_parent_locations();
        die(json_encode(['data' => $rs]));
    }
	
	function ith_doc_vs_date()
	{
		header('Content-Type: application/json');
		$doc = $this->input->get('doc');
		$date = $this->input->get('date');
		$rs = $this->ITH_mod->select_doc_vs_datec($doc, $date);
		$tmpTime = strtotime($date.' +1 days');
		$date0 = date('Y-m-d', $tmpTime);
		$rsTobeSaved = [];
		foreach($rs as &$r)
		{
			$r['TIME'] = substr($r['ITH_LUPDT'],11,8);
			if($r['TIME']>='00:00:00' && $r['TIME']<='07:00:00') {
				$r['TO_LUPDT'] = $date0." ".$r['TIME'];
			} else 
			{
				$r['TO_LUPDT'] = $date." ".$r['TIME'];
			}
			if($r['ITH_FORM']==='OUT-WH-RM')
			{
				$r['ITH_QTY'] = abs($r['ITH_QTY']);
				$rsTobeSaved[] = $r;
			}
		}
		unset($r);

		die(json_encode(['data' => $rsTobeSaved]));
	}

}