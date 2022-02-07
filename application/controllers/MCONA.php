<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class MCONA extends CI_Controller {
	public function __construct()
	{
		parent::__construct();
		$this->load->helper('url');
		$this->load->library('session');
		$this->load->model('MCONA_mod');
		$this->load->model('MSTITM_mod');
		$this->load->model('XBGROUP_mod');
		$this->load->model('RCV_mod');
	}
	public function index()	{
		echo "sorry";
	}
	public function checkSession(){
		$myar = [];
		if ($this->session->userdata('status') != "login")
        {
			$myar[] = ["cd" => "0", "msg" => "Session is expired please reload page"];
			exit('{"status":'.json_encode($myar).'}');
        }
	}
    public function form() {
		$data['lbg'] = $this->XBGROUP_mod->selectall();
		$data['ljob'] = $this->MCONA_mod->select_1col('MCONA_KNDJOB');
        $this->load->view('wms/vmcona', $data);
    }
    public function form_draft() {				
        $this->load->view('wms/vmcona_draft');
    }
    public function form_report() {
        $this->load->view('wms_report/vrpt_mcona');
    }
	public function remove() {
		header('Content-Type: application/json');
		$lineId = $this->input->post('lineId');
		$docNum = $this->input->post('docNum');
		$result = $this->MCONA_mod->delete(['MCONA_DOC' => $docNum, 'MCONA_LINE' => $lineId]);
		$myar[] = $result ? ['cd' => '1', 'msg' => 'Deleted'] : ['cd' => '0' , 'msg' => 'Could not be deleted'];
		die('{"status":'.json_encode($myar).'}');
	}
	public function save() {
		$this->checkSession();
		header('Content-Type: application/json');
		date_default_timezone_set('Asia/Jakarta');
		$currentDatetime = date('Y-m-d H:i:s');
		$docNum = $this->input->post('docNum');
		$docDate = $this->input->post('docDate');
		$docDueDate = $this->input->post('docDueDate');
		$docBG = $this->input->post('docBG');
		$docCuscd = $this->input->post('docCuscd');
		$jobDesc = $this->input->post('jobDesc');
		$licenseNum = $this->input->post('licenseNum');
		$licenseDate = $this->input->post('licenseDate');
		

		$arm_itemCd = $this->input->post('arm_itemCd');
		$arm_itemQty = $this->input->post('arm_itemQty');
		$arm_itemPrice = $this->input->post('arm_itemPrice');
		$arm_itemRemark = $this->input->post('arm_itemRemark');
		$arm_itemLine = $this->input->post('arm_itemLine');
		$arm_itemBalance = $this->input->post('arm_itemBalance');
		$count_rm = is_array($arm_itemCd) ? count($arm_itemCd) : 0;

		$afg_itemCd = $this->input->post('afg_itemCd');
		$afg_itemQty = $this->input->post('afg_itemQty');
		$afg_itemRemark = $this->input->post('afg_itemRemark');
		$afg_itemLine = $this->input->post('afg_itemLine');
		$count_fg = is_array($afg_itemCd) ? count($afg_itemCd) : 0;
		if(!$this->MCONA_mod->check_Primary(['MCONA_DOC' => $docNum]) ) {
			// Save RM data
			$dataSave = [];
			$dataSaveFG = [];
			$line = 0;
			for($i=0;$i<$count_rm; $i++){
				if($this->MSTITM_mod->check_Primary(['MITM_ITMCD' => $arm_itemCd[$i]]) == 0){
					$myar[] = ['cd' => '0', 'msg' => 'Item Code could not be found, please register first !'];
					die('{"status":'.json_encode($myar).'}');
				}
				$dataSave[] = [
					'MCONA_DOC' => $docNum,
					'MCONA_DATE' => $docDate,
					'MCONA_DUEDATE' => $docDueDate,
					'MCONA_BSGRP' => $docBG,
					'MCONA_CUSCD' => $docCuscd,
					'MCONA_KNDJOB' => $jobDesc,
					'MCONA_LCNSNUM' => $licenseNum,
					'MCONA_LCNSDT' => $licenseDate,
					
					'MCONA_ITMCD' => $arm_itemCd[$i],
					'MCONA_QTY' => $arm_itemQty[$i],
					'MCONA_PERPRC' => $arm_itemPrice[$i],
					'MCONA_BALQTY' => is_numeric($arm_itemBalance[$i]) ? $arm_itemBalance[$i] : 0,
					'MCONA_ITMTYPE' => '0',
					'MCONA_LINE' => $line,
					'MCONA_REMARK' => $arm_itemRemark[$i],
					'MCONA_LUPDT' => $currentDatetime,
					'MCONA_USRID' => $this->session->userdata('nama')
				];
				$line++;
			}
			for($i=0;$i<$count_fg; $i++){
				if($this->MSTITM_mod->check_Primary(['MITM_ITMCD' => $afg_itemCd[$i]]) == 0){
					$myar[] = ['cd' => '0', 'msg' => 'Item Code FG could not be found, please register first !'];
					die('{"status":'.json_encode($myar).'}');
				}
				$dataSaveFG[] = [
					'MCONA_DOC' => $docNum,
					'MCONA_DATE' => $docDate,
					'MCONA_DUEDATE' => $docDueDate,
					'MCONA_BSGRP' => $docBG,
					'MCONA_CUSCD' => $docCuscd,
					'MCONA_KNDJOB' => $jobDesc,
					'MCONA_LCNSNUM' => $licenseNum,
					'MCONA_LCNSDT' => $licenseDate,
					
					'MCONA_ITMCD' => $afg_itemCd[$i],
					'MCONA_QTY' => $afg_itemQty[$i],
					'MCONA_ITMTYPE' => '1',
					'MCONA_LINE' => $line,
					'MCONA_REMARK' => $afg_itemRemark[$i],
					'MCONA_LUPDT' => $currentDatetime,
					'MCONA_USRID' => $this->session->userdata('nama')
				];
				$line++;
			}
			// die(json_encode($dataSave));
			if(count($dataSave)>0) {
				$result  = $this->MCONA_mod->insertb($dataSave);
				if(count($dataSaveFG)) {
					$this->MCONA_mod->insertb($dataSaveFG);
				}
				$myar[] = $result > 0 ?  ['cd' => '1', 'msg' => 'Saved successfully'] : ['cd' => '0', 'msg' => 'sorry, we could not save'];
			} else {
				$myar[] = ['cd' => '0', 'msg' => 'Nothing to be saved'];
			}
			die('{"status":'.json_encode($myar).'}');			
		} else {
			$ttlsaved = 0;
			$ttlupdated = 0;
			#RM 
			for($i=0;$i<$count_rm; $i++){
				if($this->MSTITM_mod->check_Primary(['MITM_ITMCD' => $arm_itemCd[$i]]) == 0){
					$myar[] = ['cd' => '0', 'msg' => 'Item Code could not be found, please register first !'];
					die('{"status":'.json_encode($myar).'}');
				}				
			}
			for($i=0;$i<$count_rm; $i++){				
				if( strlen($arm_itemLine[$i])!=0 ) {
					$ttlupdated += $this->MCONA_mod->update_where(
						['MCONA_DATE' => $docDate,
						'MCONA_DUEDATE' => $docDueDate,
						'MCONA_BSGRP' => $docBG,
						'MCONA_CUSCD' => $docCuscd,
						'MCONA_KNDJOB' => $jobDesc,
						'MCONA_LCNSNUM' => $licenseNum,
						'MCONA_LCNSDT' => $licenseDate,

						'MCONA_ITMCD' => $arm_itemCd[$i],
						'MCONA_QTY' => $arm_itemQty[$i],
						'MCONA_BALQTY' => is_numeric($arm_itemBalance[$i]) ? $arm_itemBalance[$i] : 0,
						'MCONA_PERPRC' => $arm_itemPrice[$i],
						'MCONA_LUPDT' => $currentDatetime,
						'MCONA_USRID' => $this->session->userdata('nama')
						]
						,['MCONA_DOC' => $docNum, 'MCONA_LINE' => $arm_itemLine[$i]]);
				} else {
					$lastLine = $this->MCONA_mod->lastserialid($docNum);
					$lastLine++;
					$ttlsaved += $this->MCONA_mod->insert([
						'MCONA_DOC' => $docNum,
						'MCONA_DATE' => $docDate,
						'MCONA_DUEDATE' => $docDueDate,
						'MCONA_BSGRP' => $docBG,
						'MCONA_CUSCD' => $docCuscd,
						'MCONA_KNDJOB' => $jobDesc,
						'MCONA_LCNSNUM' => $licenseNum,
						'MCONA_LCNSDT' => $licenseDate,
						
						'MCONA_ITMCD' => $arm_itemCd[$i],
						'MCONA_QTY' => $arm_itemQty[$i],
						'MCONA_BALQTY' => is_numeric($arm_itemBalance[$i]) ? $arm_itemBalance[$i] : 0,
						'MCONA_PERPRC' => $arm_itemPrice[$i],
						'MCONA_ITMTYPE' => '0',
						'MCONA_LINE' => $lastLine,
						'MCONA_REMARK' => $arm_itemRemark[$i],
						'MCONA_LUPDT' => $currentDatetime,
						'MCONA_USRID' => $this->session->userdata('nama')
					]);
				}				
			}
			#END
			#FG 
			for($i=0;$i<$count_fg; $i++){
				if($this->MSTITM_mod->check_Primary(['MITM_ITMCD' => $afg_itemCd[$i]]) == 0){
					$myar[] = ['cd' => '0', 'msg' => 'Item Code could not be found, please register first !'];
					die('{"status":'.json_encode($myar).'}');
				}				
			}
			for($i=0;$i<$count_fg; $i++){
				if( strlen($afg_itemLine[$i])!=0 ) {
					$ttlupdated += $this->MCONA_mod->update_where(
						['MCONA_DATE' => $docDate,
						'MCONA_DUEDATE' => $docDueDate,
						'MCONA_BSGRP' => $docBG,
						'MCONA_CUSCD' => $docCuscd,
						'MCONA_KNDJOB' => $jobDesc,
						'MCONA_LCNSNUM' => $licenseNum,
						'MCONA_LCNSDT' => $licenseDate,
						'MCONA_ITMCD' => $afg_itemCd[$i],
						'MCONA_QTY' => $afg_itemQty[$i],
						'MCONA_LUPDT' => $currentDatetime,
						'MCONA_USRID' => $this->session->userdata('nama')
						]
						,['MCONA_DOC' => $docNum, 'MCONA_LINE' => $afg_itemLine[$i]]);
				} else {
					$lastLine = $this->MCONA_mod->lastserialid($docNum);
					$lastLine++;
					$ttlsaved += $this->MCONA_mod->insert([
						'MCONA_DOC' => $docNum,
						'MCONA_DATE' => $docDate,
						'MCONA_DUEDATE' => $docDueDate,
						'MCONA_BSGRP' => $docBG,
						'MCONA_CUSCD' => $docCuscd,
						'MCONA_KNDJOB' => $jobDesc,
						'MCONA_LCNSNUM' => $licenseNum,
						'MCONA_LCNSDT' => $licenseDate,
						'MCONA_ITMCD' => $afg_itemCd[$i],
						'MCONA_QTY' => $afg_itemQty[$i],
						'MCONA_ITMTYPE' => '1',
						'MCONA_LINE' => $lastLine,
						'MCONA_REMARK' => $afg_itemRemark[$i],
						'MCONA_LUPDT' => $currentDatetime,
						'MCONA_USRID' => $this->session->userdata('nama')
					]);
				}				
			}
			#END
			$myar[] = ['cd' => '1', 'msg' => $ttlsaved.' saved, '.$ttlupdated.' updated'];
			die('{"status":'.json_encode($myar).'}');
		}
	}
	function search(){
		header('Content-Type: application/json');
		$searchBy = $this->input->get('searchBy');
		$msearchKey = $this->input->get('searchKey');
		$myar = [];
		$rs = [];
		if($searchBy==='0'){
			$rs = $this->MCONA_mod->select_header(['MCONA_DOC' => $msearchKey]);
		}
		$myar[] = count($rs) ? ['cd' => 1, 'msg' => 'go ahead'] : ['cd' => 0, 'msg' => 'not found'];
		die('{"status":'.json_encode($myar).',"data":'.json_encode($rs).'}');
	}

	function searchCustomer() {
		header('Content-Type: application/json');
		$searchby = $this->input->get('searchby');
		$searchvalue = $this->input->get('searchvalue');
		$bisgrup = $this->input->get('bisgrup');
		$rs = [];
		switch($searchby) {
			case 'nm':
				$rs = $this->RCV_mod->select_customer(['PGRN_BSGRP' => $bisgrup, 'MCUS_CUSNM' => $searchvalue]);
				break;
			case 'cd':
				$rs = $this->RCV_mod->select_customer(['PGRN_BSGRP' => $bisgrup, 'MCUS_CUSCD' => $searchvalue]);
				break;
			case 'ad':
				$rs = $this->RCV_mod->select_customer(['PGRN_BSGRP' => $bisgrup, 'MCUS_ADDR1' => $searchvalue]);
				break;
		}
		die('{"data":'.json_encode($rs).'}');
	}
	function getdata(){
		header('Content-Type: application/json');
		$docNum = $this->input->get('docNum');
		$rs = $this->MCONA_mod->select_detail(['MCONA_DOC' => $docNum]);
		$myar[] = count($rs) ? ['cd' => 1, 'msg' => 'go ahead'] : ['cd' => 0, 'msg' => 'aleready deleted'];
		die('{"status":'.json_encode($myar).',"data":'.json_encode($rs).'}');
	}

	function report() {
		header('Content-Type: application/json');
		$documentNum = $this->input->get('doc');
		$rs_RM_m = $this->MCONA_mod->select_rm_report_m($documentNum);
		$rs_RM_d = $this->MCONA_mod->select_rm_report_d($documentNum);
		$rs_FG_m = $this->MCONA_mod->select_fg_report_m($documentNum);
		$rs_FG_d = $this->MCONA_mod->select_fg_report_d($documentNum);
		$rs_RM_h = [];
		$rs_FG_h = [];
		foreach($rs_FG_d as $r) {
			$isfound = false;
			foreach($rs_FG_h as $n) {
				if($r['DLV_ID'] == $n['DLV_ID']) {
					$isfound = true; break;
				}
			}
			if(!$isfound) {
				$rs_FG_h[] = [
					'DLV_ID' => $r['DLV_ID']
					,'DLV_BCDATE' => $r['DLV_BCDATE']
					,'DLV_NOAJU' => $r['DLV_NOAJU']
				];
			}
		}
		foreach($rs_RM_d as $r) {
			$isfound = false;
			foreach($rs_RM_h as $n) {
				if($r['RCV_DONO'] == $n['RCV_DONO']) {
					$isfound = true; break;
				}
			}
			if(!$isfound) {
				$rs_RM_h[] = [
					'RCV_DONO' => $r['RCV_DONO']
					,'RCV_BCDATE' => $r['RCV_BCDATE']
					,'RCV_BCNO' => $r['RCV_BCNO']
				];
			}
		}
		die('{"data_rm_m":'.json_encode($rs_RM_m).' ,"data_rm_d":'.json_encode($rs_RM_d).', "data_rm_h":'.json_encode($rs_RM_h)
		   .',"data_fg_m":'.json_encode($rs_FG_m).' ,"data_fg_d":'.json_encode($rs_FG_d).', "data_fg_h":'.json_encode($rs_FG_h).'}');
	}

	public function plot() {
		header('Content-Type: application/json');
		$bisgrup = $this->input->get('bisgrup');
		$cuscd = $this->input->get('cuscd');
		$rs = $this->MCONA_mod->select_plot(['MCONA_BSGRP' => $bisgrup, 'MCONA_CUSCD' => $cuscd]);
		die(json_encode(['data' => $rs]));
	}
}
