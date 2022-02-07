<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Invoice extends CI_Controller {
	public function __construct()
	{
		parent::__construct();
		$this->load->helper('url');
		$this->load->helper('download');
		$this->load->library('session');
        $this->load->model('RCV_mod');			
	}
	public function index()
	{
		echo "sorry";
	}

    public function save(){
        header('Content-Type: application/json');
        $bisgrup_l = $this->input->post('bisgrup_l');
        $supno_l = $this->input->post('supno_l');
        $invno = $this->input->post('invno');        
        $supno_lc = count($supno_l);
        for($i=0;$i<$supno_lc; $i++){
            $this->RCV_mod->updatebyVAR(['RCV_INVNOACT' => $invno], 
            ['RCV_DONO' => $supno_l[$i], 'RCV_BSGRP' => $bisgrup_l[$i] ] );
        }
        $myar = [];
        $myar[]= ['cd' => '1', 'msg' => 'OK'];
        die(json_encode(['status' => $myar]));
    }

    public function get_inc(){
        header('Content-Type: application/json');
        $search = $this->input->get('search');
        $rs = $this->RCV_mod->select_like_group(['RCV_INVNOACT','MSUP_SUPCD','MSUP_SUPNM','RCV_INVNOACT'], ['RCV_INVNOACT' => $search]);
        die(json_encode(['data' => $rs]));
    }

    public function cancel(){
        header('Content-Type: application/json');
        $invno = $this->input->post('invno');
        $dono = $this->input->post('dono');
        $this->RCV_mod->updatebyVAR(['RCV_INVNOACT' => '']
        , ['RCV_INVNOACT' => $invno, 'RCV_DONO' => $dono]);
        $myar=[];
        $myar[] = ['cd' => 1, 'msg' => 'OK'];
        die(json_encode(['status' => $myar]));
    }
}