<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class MSTSUP extends CI_Controller {

	public function __construct()
	{
		parent::__construct();
		$this->load->model('MSTSUP_mod');
		$this->load->helper('url');
		$this->load->library('session');
	}
	public function index()
	{
		echo "sorry";
	}

	public function form(){
		$this->load->view('wms/vsup');
	}

	public function getall(){
		header('Content-Type: application/json');
		$rs = $this->MSTSUP_mod->selectAll();
		echo '{"data":';
		echo json_encode($rs);
		echo '}';
	}

	public function getbyname(){
		header('Content-Type: application/json');
		$thkey = strtolower($this->input->get('inkey'));
		$rs = $this->MSTSUP_mod->selectbyName($thkey);			
		echo json_encode($rs);
	}

	public function search(){
		header('Content-Type: application/json');
		$cid = $this->input->get('cid');
		$csrchkey = $this->input->get('csrchby');
		$rs = array();
		switch($csrchkey){
			case 'cd':
				$rs = $this->MSTSUP_mod->select_like(['MSUP_SUPCD' => $cid]);break;
			case 'nm':
				$rs = $this->MSTSUP_mod->select_like(['MSUP_SUPNM' => $cid]);break;
			case 'ab':
				$rs = $this->MSTSUP_mod->select_like(['MSUP_ABBRV' => $cid]);break;
			case 'ad':
				$rs = $this->MSTSUP_mod->select_like(['MSUP_ADDR1' => $cid]);break;
		}					
		die(json_encode($rs));
	}

	public function checkSession(){
		$myar = [];
		if ($this->session->userdata('status') != "login")
        {
			$myar[] = ["cd" => 0, "msg" => "Session is expired please reload page"];
			die(json_encode($myar));
        }
	}

	public function search_union(){
		header('Content-Type: application/json');
		$searchKey = $this->input->get('searchKey');
		$rs = $this->MSTSUP_mod->select_union(['MSUP_SUPNM' => $searchKey]);
		$myar[] = count($rs) ? ['cd' => 1, 'msg' => 'go ahead'] : ['cd' => 0, 'msg' => 'not found'];
		die('{"data":'.json_encode($rs).',"status":'.json_encode($myar).'}');
	}

	public function set(){
		$this->checkSession();
		date_default_timezone_set('Asia/Jakarta');
        $currrtime = date('Y-m-d H:i:s');
		$ccd = $this->input->post('incd');
		$ccur = $this->input->post('incur');
		$cnm = $this->input->post('innm');
		$cabbr = $this->input->post('inabbr');
		$caddr = $this->input->post('inaddr');
		$mphone = str_replace(" ", "", $this->input->post('mphone'));
		$mfax = str_replace(" ", "",$this->input->post('mfax'));
		$mtax = str_replace(" ", "",$this->input->post('mtax'));
		$datac = ['MSUP_SUPCD'=> $ccd];
		if($this->MSTSUP_mod->check_Primary($datac)==0){
			$datas = [
				'MSUP_SUPCD' => $ccd,
				'MSUP_SUPCR' => $ccur,
				'MSUP_SUPNM' => $cnm,
				'MSUP_ABBRV' => $cabbr,
				'MSUP_ADDR1' => $caddr,
				'MSUP_TELNO' => $mphone,
				'MSUP_FAXNO' => $mfax,
				'MSUP_TAXREG' => $mtax,
				'MSUP_LUPDT' => $currrtime,
				'MSUP_USRID' => $this->session->userdata('nama')
			];
			$toret = $this->MSTSUP_mod->insert($datas);
			if($toret>0){ echo "Saved successfully";}
		} else {
			$datau = [
				'MSUP_SUPCR' => $ccur,
				'MSUP_SUPNM' => $cnm,
				'MSUP_ABBRV' => $cabbr,
				'MSUP_ADDR1' => $caddr,
				'MSUP_TELNO' => $mphone,
				'MSUP_FAXNO' => $mfax,
				'MSUP_TAXREG' => $mtax,
				'MSUP_LUPDT' => $currrtime,
				'MSUP_USRID' => $this->session->userdata('nama')
			];
			$toret = $this->MSTSUP_mod->updatebyId($datau, $ccd);
			if($toret>0){ echo "Updated successfully";}
		}
	}
}
