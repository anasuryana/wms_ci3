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
        $data['ldeliverycode'] = $this->DELV_mod->select_delv_code_where(['MDEL_3RDP_AS' => 1]);
        $this->load->view('wms/vmdelvcode', $data);
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
        $cusNIB = $this->input->post('cusNIB');
        $addr = $this->input->post('addr');
        $tax = $this->input->post('tax');
        $tpbno = $this->input->post('tpbno');
        $tpbDate = $this->input->post('tpbDate');
        $txcode = $this->input->post('txcode');
        $parentConsignment = $this->input->post('parentConsignment');
        $tpbKind = $this->input->post('tpbKind');
        $myar = [];
        if($this->DELV_mod->updatebyVAR_DELCD([
            'MDEL_ZNAMA' => $cusNM
            ,'MDEL_DELNM' => $cusNM
            ,'MDEL_NIB' => $cusNIB
            ,'MDEL_ADDRCUSTOMS' => $addr
            ,'MDEL_ZTAX' => $tax
            ,'MDEL_ZSKEP' => $tpbno
            ,'MDEL_TXCD' => $txcode
            ,'MDEL_ZSKEP_DATE' => $tpbDate
            ,'PARENT_DELCD' => $parentConsignment
            ,'MDEL_JENIS_TPB' => $tpbKind
            ]
            ,['MDEL_DELCD' => $dlvCD]) 
            ) 
        {
            $myar[] = ['cd' => 1, 'msg' => 'Updated successfully'];
        } else {
            $myar[] = ['cd' => 0, 'msg' => 'Could not be updated'];
        }
        die(json_encode(['status' => $myar]));
    }
    function set(){
        header('Content-Type: application/json');
        $delcode = $this->input->post('delcode');
        $delname = $this->input->post('delname');
        $deladdr = $this->input->post('deladdr');
        $myar = [];
        $data = [];
        if($this->DELV_mod->check_Primary_address(['MDEL_DELCD' => $delcode])){
            $myar[] = ['cd' => '0', 'msg' => 'It is already exist'];
        } else {
            $lastnum = $this->DELV_mod->select_lastno_address()+1;
            $lastnum = substr('0'.$lastnum,-2);
            $data = [
                "MDEL_DELCD" => $delcode,
                "MDEL_TXCD" => $lastnum,
                "MDEL_ZNAMA" => $delname,
                "MDEL_DELNM" => $delname,
                "MDEL_ADDRCUSTOMS" => $deladdr,
                "MDEL_3RDP_AS" => 1
            ];
            $this->DELV_mod->insert_address($data);
            $myar[] = ['cd' => '1', 'msg' => 'Saved'];
        }
        die(json_encode(['status' => $myar]));
    }
}