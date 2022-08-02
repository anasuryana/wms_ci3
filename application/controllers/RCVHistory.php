<?php
defined('BASEPATH') OR exit('No direct script access allowed');
class RCVHistory extends CI_Controller {

	public function __construct()
	{
		parent::__construct();		
		$this->load->model('RCVNI_mod');
        $this->load->helper('url');
	}

	public function index()
	{
		echo "sorry";
	}

	function form_receiving() {
		$this->load->view('wms_report/vrpt_receiving_list');
	}

	function receiving() {
		header('Content-Type: application/json');
		$searchby = $this->input->get('searchby');
		$search = $this->input->get('search');
        $dateFrom = $this->input->get('date0');
        $dateTo = $this->input->get('date1');
        $likeFilter = $searchby=='id' ? ['ITEMCODE' => $search] : ['ITEMNAME' => $search];
		$rs = $this->RCVNI_mod->select_receiving_like($likeFilter,$dateFrom, $dateTo );
        die(json_encode(['data' => $rs]));
	}
}