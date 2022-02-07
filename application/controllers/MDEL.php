<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class MDEL extends CI_Controller {
	public function __construct()
	{
		parent::__construct();
		$this->load->helper('url');		
		$this->load->model('DELV_mod');	
	}
	public function index()	{
		echo "sorry";
	}
    function form() {
        $this->load->view('wms/vmdelvcode');
    }
    function master() {
        header('Content-Type: application/json');
        $rs = $this->DELV_mod->select_delv_code_master();
        die(json_encode(['data' => $rs]));
    }
    function save() {
        header('Content-Type: application/json');
        $dlvCD = $this->input->post('dlvCD');
        $cusNM = $this->input->post('cusNM');
        $addr = $this->input->post('addr');
        $tax = $this->input->post('tax');
        $tpbno = $this->input->post('tpbno');
        $txcode = $this->input->post('txcode');
        $myar = [];
        if($this->DELV_mod->updatebyVAR_DELCD(['MDEL_ZNAMA' => $cusNM
                        , 'MDEL_ADDRCUSTOMS' => $addr
                        , 'MDEL_ZTAX' => $tax
                        , 'MDEL_ZSKEP' => $tpbno
                        ,'MDEL_TXCD' => $txcode]
                        ,['MDEL_DELCD' => $dlvCD]) 
            ) {
                $myar[] = ['cd' => 1, 'msg' => 'Updated successfully'];
        } else {
            $myar[] = ['cd' => 0, 'msg' => 'Could not be updated'];
        }
        die(json_encode(['status' => $myar]));
    }
}