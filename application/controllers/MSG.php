<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class MSG extends CI_Controller {
	public function __construct()
	{
		parent::__construct();
		$this->load->helper('url');		
		$this->load->model('MSG_mod');
		$this->load->model('ITH_mod');
		$this->load->model('SER_mod');
        $this->load->library('session');
	}
	public function index()	{
		echo "sorry";
	}
    function form_report(){
        $this->load->view('wms_report/vrpt_message_user');
    }

    function getbyUser() {
        $rs = $this->MSG_mod->select_total_unread_byuser($this->session->userdata('nama'));
       die(json_encode($rs));
    }

    function getdetailbyUser(){
        $mmonth = $this->input->get('month');
        $myear = $this->input->get('year');
        $rs = $this->MSG_mod->select_byuser($this->session->userdata('nama'),$myear, $mmonth );
        foreach($rs as &$r) {
            if(!array_key_exists("CONFIRMDATE", $r)){
                if($r['MSG_DOC']) {
                    $rsc = $this->ITH_mod->select_confirmdate($r['MSG_DOC']);
                    $thedate = null;
                    foreach($rsc as $n) {
                        $thedate=$n['ITH_LUPDT'];
                    }
                    $r["CONFIRMDATE"] = $thedate;
                } else {
                    $r["CONFIRMDATE"] = '';                    
                }
			}
            if($r['MSG_TOPIC']==='SKIP_COMPONENT') {
                if($r['MSG_REFFDATA']) {
                    $rsa = json_decode($r['MSG_REFFDATA']);
                    $newrs = [];
                    foreach($rsa as $a) {
                        $rsmaster = $this->SER_mod->select_master_delivery($a->ID);
                        foreach($rsmaster as $n) {
                            $newrs[] = [
                                'ID' => $a->ID
                                ,'WO' => $n['SER_DOC']
                                ,'TXID' => $n['DLV_ID']
                            ];
                        }
                    }
                    $r['MSG_REFFDATA'] = json_encode($newrs);
                }
            }
        }
        unset($r);        
        die(json_encode(['data'=>$rs]));
    }

    function read() {
        header('Content-Type: application/json');
        date_default_timezone_set('Asia/Jakarta');
		$current_datetime = date('Y-m-d H:i:s');
        $increatedat = $this->input->post('increatedat');
        $inline = $this->input->post('inline');
        $myar = [];
        if($this->MSG_mod->updateReadyId(['MSG_TO' => $this->session->userdata('nama'), 'MSG_CREATEDAT' => $increatedat, 'MSG_LINE' => $inline], ['MSG_READAT' => $current_datetime])) {
            $myar[] =['cd' => 1, 'msg' => 'updated'];
        } else {
            $myar[] =['cd' => 0, 'msg' => 'could not be updated'];
        }
        die(json_encode(['status' => $myar]));
    }
}