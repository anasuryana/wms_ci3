<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class DELVHistory extends CI_Controller {
	public function __construct()
	{
		parent::__construct();
		$this->load->helper('url');
        $this->load->helper('download');
        $this->load->library('session');
		$this->load->model('SISO_mod');
	}
	public function index()
	{
		echo "sorry";
    }

	function priceFG()
	{
		header('Content-Type: application/json');
		$itemcd = $this->input->post('itemcd');
		$reffno = $this->input->post('reffno');
		$reffnostring =  is_array($reffno) ? "'".implode("','", $reffno)."'" : "''";		
		$rs = $this->SISO_mod->select_currentPrice_byReffno($reffnostring, $itemcd);
		die(json_encode(['data' => $rs]));
	}
}