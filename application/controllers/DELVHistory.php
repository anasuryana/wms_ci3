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
		$this->load->model('DELV_mod');
		$this->load->model('RPSAL_BCSTOCK_mod');
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

	function form_reconsiliator(){
		$this->load->view('wms/vreconsiliator_exbc');
	}
	
	function reconsiliator(){
		header('Content-Type: application/json');
		$itemcd = $this->input->get('itemcd');
		$rs = $this->RPSAL_BCSTOCK_mod->selectDiscrepancy($itemcd);
		die(json_encode(['data' => $rs]));
	}
	
	function reconsiliator2(){		
		header('Content-Type: application/json');
		$itemcd = $this->input->get('itemcd');
		$doc = $this->input->get('doc');
		$columns = ['RPSTOCK_REMARK','RPSTOCK_ITMNUM','RPSTOCK_NOAJU','RPSTOCK_QTY','id'];
		$rs = $this->RPSAL_BCSTOCK_mod->selectAllOrderbyIdWhere($columns,['RPSTOCK_ITMNUM' => $itemcd, 'RPSTOCK_REMARK' => $doc]);
		$rs3 = $this->DELV_mod->selectJoinSERLike(['DLV_ID' => $doc, 'SER_DOC' => '-C']);
		die(json_encode(['data' => $rs, 'dataser' => $rs3]));
	}
}