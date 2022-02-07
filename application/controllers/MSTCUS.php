<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class MSTCUS extends CI_Controller {

	public function __construct()
	{
		parent::__construct();
		$this->load->model('MSTCUS_mod');
		$this->load->model('refceisa/MTPB_mod');
		$this->load->helper('url');
		$this->load->library('session');
	}
	public function index()
	{
		echo "sorry";
	}

	public function create(){
		$data['ltpb_type'] = $this->MTPB_mod->selectAll();
		$this->load->view('wms/vcus', $data);
	}

	public function getall(){
		header('Content-Type: application/json');
		$rs = $this->MSTCUS_mod->selectAll();
		echo '{"data":';		
		echo json_encode($rs);
		echo '}';
	}

	public function search(){
		header('Content-Type: application/json');
		$cid = $this->input->get('cid');
		$csrchkey = $this->input->get('csrchby');
		$rs = [];
		switch($csrchkey){
			case 'cd':
				$rs = $this->MSTCUS_mod->selectbycd_lk($cid);break;
			case 'nm':
				$rs = $this->MSTCUS_mod->selectbynm_lk($cid);break;
			case 'ab':
				$rs = $this->MSTCUS_mod->selectbyab_lk($cid);break;
			case 'ad':
				$rs = $this->MSTCUS_mod->selectbyad_lk($cid);break;
		}					
		echo json_encode($rs);
	}
	public function set(){
		date_default_timezone_set('Asia/Jakarta');
        $currrtime = date('Y-m-d H:i:s');
		$ccd	= $this->input->post('incd');
		$ccur	= $this->input->post('incur');
		$cnm 	= $this->input->post('innm');
		$cabbr 	= $this->input->post('inabbr');
		$caddr 	= $this->input->post('inaddr');
		$ctpbid 	= $this->input->post('intpbid');
		$ctpbno 	= $this->input->post('intpbno');
		$datac 	= ['MCUS_CUSCD'=> $ccd];
		if($this->MSTCUS_mod->check_Primary($datac)==0){
			$datas = [
				'MCUS_CUSCD' => $ccd,
				'MCUS_CURCD' => $ccur,
				'MCUS_CUSNM' => $cnm,
				'MCUS_ABBRV' => $cabbr,
				'MCUS_ADDR1' => $caddr,
				'MCUS_TPBTYPE' => $ctpbid,
				'MCUS_TPBNO' => $ctpbno,
				'MCUS_LUPDT' => $currrtime,
				'MCUS_USRID' => $this->session->userdata('nama')
			];
			$toret = $this->MSTCUS_mod->insert($datas);
			if($toret>0){ echo "Saved successfully";}
		} else {
			$datau = [
				'MCUS_CURCD' => $ccur,
				'MCUS_CUSNM' => $cnm,
				'MCUS_ABBRV' => $cabbr,
				'MCUS_ADDR1' => $caddr,				
				'MCUS_TPBTYPE' => $ctpbid,
				'MCUS_TPBNO' => $ctpbno,
				'MCUS_LUPDT' => $currrtime,
				'MCUS_USRID' => $this->session->userdata('nama')
			];
			$toret = $this->MSTCUS_mod->updatebyId($datau, $ccd);
			if($toret>0){ echo "Updated successfully";}
		}
	}
}
