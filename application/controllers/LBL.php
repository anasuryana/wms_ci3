<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class LBL extends CI_Controller {

	public function __construct()
	{
		parent::__construct();
		$this->load->model('MADE_mod');
		$this->load->helper('url');
		$this->load->library('session');
	}
	public function index()
	{
		echo "sorry";
	}

	public function create(){
		$this->load->view('wms/vmade');
	}

	public function getall(){
		header('Content-Type: application/json');
		$rs = $this->MADE_mod->selectAll();
		echo '{"data":';		
		echo json_encode($rs);
		echo '}';
	}
	
	public function set(){
        date_default_timezone_set('Asia/Jakarta');
        $currrtime = date('Y-m-d H:i:s');
		$ccd	= $this->input->post('incd');
		$cnm	= $this->input->post('innm');
		
		$datac 	= array('MMADE_CD'=> $ccd);
		if($this->MADE_mod->check_Primary($datac)==0){
			$datas = array(
				'MMADE_CD' => $ccd,
                'MMADE_NM' => $cnm,
                'MMADE_LUPDT' => $currrtime,
                'MMADE_USRID' => $this->session->userdata('nama')
			);
			$toret = $this->MADE_mod->insert($datas);
			if($toret>0){ echo "Saved successfully";}
		} else {
			$datau = array(				
                'MMADE_NM' => $cnm,	
                'MMADE_LUPDT' => $currrtime,
                'MMADE_USRID' => $this->session->userdata('nama')			
			);
			$toret = $this->MADE_mod->updatebyId($datau, $ccd);
			if($toret>0){ echo "Updated successfully";}
		}
	}
}
