<?php
defined('BASEPATH') OR exit('No direct script access allowed');
use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Csv;
class SPL extends CI_Controller {
	private $AMONTHPATRN = ['1','2','3', '4', '5', '6', '7', '8', '9', 'X', 'Y' , 'Z'];	
	public function __construct()
	{
		parent::__construct();
		$this->load->helper('url');
		$this->load->library('session');
		$this->load->library('Code39e128');		
		$this->load->model('RCV_mod');
		$this->load->model('SPL_mod');
		$this->load->model('SPLREFF_mod');
		$this->load->model('SPLSCN_mod');
		$this->load->model('SPLRET_mod');
		$this->load->model('ITH_mod');
		$this->load->model('MSTITM_mod');
		$this->load->model('SERC_mod');
		$this->load->model('MSPP_mod');
		$this->load->model('C3LC_mod');
		$this->load->model('Usr_mod');
		$this->load->model('XBGROUP_mod');
		$this->load->model('STKTRN_mod');
		$this->load->model('ITMLOC_mod');
		$this->load->model('RQSRMRK_mod');
		$this->load->model('LOGSER_mod');
		$this->load->model('SPLBOOK_mod');
		$this->load->model('SCNDOC_mod');
		$this->load->model('SCNDOCITM_mod');
		$this->load->model('XMBOM_mod');
	}
	public function index()
	{
		echo "sorry";
	}

	function get_doc_today() {					
		header("Access-Control-Allow-Origin: *");	        
		header('Content-Type: application/json');

		$rs = $this->SCNDOC_mod->select_today();
		die(json_encode(['data' => $rs]));
	}

	function get_psn_item_confirmation_list_progress() {
		header("Access-Control-Allow-Origin: *");
		header('Content-Type: application/json');
		$doc = $this->input->get('doc');
		$myar = $this->SCNDOC_mod->check_Primary(['SCNDOC_DOCNO' => $doc]) ? ['cd' => 1, 'msg' => 'OK'] : ['cd' => '0', 'msg' => 'PSN is not found'];
		$rsProgress = $this->SPLSCN_mod->select_supplied_vs_confirmed_progress($doc);
		die(json_encode(['status' => $myar, 'progress' => $rsProgress]));
	}
	function get_psn_item_confirmation_list() {
		header("Access-Control-Allow-Origin: *");
		header('Content-Type: application/json');
		$doc = $this->input->get('doc');
		$myar = $this->SCNDOC_mod->check_Primary(['SCNDOC_DOCNO' => $doc]) ? ['cd' => 1, 'msg' => 'OK'] : ['cd' => '0', 'msg' => 'PSN is not found'];
		$rs = $this->SPLSCN_mod->select_supplied_vs_confirmed($doc);
		die(json_encode(['status' => $myar, 'data' => $rs]));
	}

	function confirm_psn_item_at_plant() {
		date_default_timezone_set('Asia/Jakarta');
        $currentDateTime = date('Y-m-d H:i:s');
		header("Access-Control-Allow-Origin: *");
		header('Content-Type: application/json');
		$doc = $this->input->post('doc');
		$userid = $this->input->post('userid');
		$itemcode = $this->input->post('itemcode');
		$itemqty = $this->input->post('itemqty');
		$itemlot = $this->input->post('itemlot');
		$rsProgress = $this->SPLSCN_mod->select_supplied_vs_confirmed_progress($doc);
		$myar = [];
		$balance = 0;
		foreach($rsProgress as $r) {
			$balance = $r['RQT']-$r['CQT'];
		}
		if($balance>0 && $balance>=$itemqty){
			$WHERE = [
				'SPLSCN_DOC' => $doc,
				'SPLSCN_ITMCD' => $itemcode,
				'SPLSCN_QTY' => $itemqty,
				'SPLSCN_LOTNO' => $itemlot,
			];
			if($this->SPLSCN_mod->check_Primary($WHERE)) {
				$newLine = $this->SCNDOCITM_mod->lastserialid($doc)+1;
				$this->SCNDOCITM_mod->insert([
					'SCNDOCITM_DOCNO' => $doc,
					'SCNDOCITM_ITMCD' => $itemcode,
					'SCNDOCITM_LOTNO' => $itemlot,
					'SCNDOCITM_QTY' => $itemqty,
					'SCNDOCITM_LINE' => $newLine,
					'SCNDOCITM_CREATEDAT' => $currentDateTime,
					'SCNDOCITM_CREATEDBY' => $userid,
				]);
				$myar[] = ['cd' => 1, 'msg' => 'OK'];
			} else {
				$myar[] = ['cd' => 0, 'msg' => 'label is not registered in the document'];				
			}
		} else {
			$myar[] = ['cd' => 1, 'msg' => 'already finished'];
		}
		$rsProgress = $this->SPLSCN_mod->select_supplied_vs_confirmed_progress($doc);
		die(json_encode(['status' => $myar, 'progress' => $rsProgress]));
	}

	function confirm_psn_at_plant() {   
		header("Access-Control-Allow-Origin: *");
		date_default_timezone_set('Asia/Jakarta');
		$current_time = date('Y-m-d H:i:s');
		header('Content-Type: application/json');
		$doc = $this->input->post('doc');
		$userid = $this->input->post('userid');
		$myar = [];		
		if($this->SCNDOC_mod->check_Primary(['SCNDOC_DOCNO' => $doc])) {
			$myar[] = ['cd' => 1, 'msg' => 'aleready scanned'];
		} else {
			if($this->SPL_mod->check_Primary(['SPL_DOC' => $doc])) {
				$this->SCNDOC_mod->insert([
					'SCNDOC_DOCNO' => $doc,
					'SCNDOC_TYPE' => '01',
					'SCNDOC_USRID' => $userid,
					'SCNDOC_SCANNEDAT' => $current_time,
					'SCNDOC_CREATEDAT' => $current_time,
				]);
				$myar[] = ['cd' => 1, 'msg' => 'OK'];
			} else {
				$myar[] = ['cd' => 0, 'msg' => 'Document is not valid'];
			}
		}
		die(json_encode(['status' => $myar]));
	}

	public function get_c3_definition(){
		header('Content-Type: application/json');
		$clotno = $this->input->post('inlot');
		$citemcd = $this->input->post('initemcd');
		$cqty = $this->input->post('inqty');
		$rs = $this->C3LC_mod->selectall_where(['C3LC_ITMCD' => $citemcd, 'C3LC_NLOTNO' => $clotno]);
		die('{"data":'.json_encode($rs).'}');
	}	

	public function create(){
		$rsBG = $this->XBGROUP_mod->selectall();
		$sListBG = '';
		foreach($rsBG as $r){
			$sListBG .= "<option value='".trim($r->MBSG_BSGRP)."'>".$r->MBSG_DESC."</option>";
		}
		$data['lbg'] = $sListBG;	
		$this->load->view('wms/vspl',$data);
	}
	public function form_book_ost(){
		$this->load->view('wms_report/vspl_booked_list');
	}	

	public function form_reference(){
		$this->load->view('wms/vspl_reference');
	}

	public function vkitting_status(){
		$data['lgroup'] = $this->XBGROUP_mod->selectall();
		$this->load->view('wms_report/vspl_status', $data);
	}
	public function vrequest_status(){
		$data['lgroup'] = $this->XBGROUP_mod->selectall();
		$this->load->view('wms_report/vrequestpart_status', $data);
	}
	
	public function vsync(){		
		$this->load->view('wms/vsync_psn');
	}
	
	public function vtracelot(){
		$this->load->view('wms/vtracelot');
    }

	public function get_kitting_status(){
		header('Content-Type: application/json');
		$bg = $this->input->get('bg');
		$rs = $this->SPLSCN_mod->selectby_kitting_status($bg);
		die('{"data":'.json_encode($rs).'}');
	}
	public function get_partreq_status(){
		header('Content-Type: application/json');
		$bg = $this->input->get('bg');
		$rs = $this->SPLSCN_mod->selectby_partreq_status($bg);
		die('{"data":'.json_encode($rs).'}');
	}
	public function get_kitting_status_byjob(){
		header('Content-Type: application/json');
		$jobno = $this->input->get('jobno');
		$rs = $this->SPLSCN_mod->select_kittingstatus_byjob($jobno);
		die('{"data":'.json_encode($rs).'}');
	}

	public function get_bg_not_in_psn(){
		header('Content-Type: application/json');
		$rs = $this->SPL_mod->select_bg_not_in_psn();
		die('{"data":'.json_encode($rs).'}');
	}

	public function get_sim_job_not_in_psn(){
		header('Content-Type: application/json');
		$inbg = $this->input->get('inBG');
		$rs = $this->SPL_mod->select_sim_only($inbg);
		die('{"data":'.json_encode($rs).'}');
	}

	public function v_sim_vs_stock(){
		$this->load->view('wms/vsimvsstock');
	}
	public function form_book(){
		$this->load->view('wms/vspl_book');
	}

	public function simulate_sim_vs_stock(){
		header('Content-Type: application/json');
		$csimno = $this->input->post('insimno');
		$tab = $this->input->post('tab');
		$assycode = $this->input->post('assycode');
		$qty = $this->input->post('qty');
		$rs = [];
		$myar = [];
		if($tab==='simvsstock_byassy-tab') {
			$rs = $this->XMBOM_mod->select_for_simulation($assycode, $qty);
			$rsstock = $this->XMBOM_mod->select_sim_item_stock($assycode);
			foreach($rs as &$n){
				foreach($rsstock as &$k){
					if($n['PIS3_MPART'] == $k['ITEMCODE'] && $k['TSTKQTY'] >0){
						if($n['REQQTY'] > $n['PLOTQTY']){
							$balance = $n['REQQTY'] - $n['PLOTQTY'];
							if($balance > $k['TSTKQTY']){
								$n['PLOTQTY'] += $k['TSTKQTY'];
								$k['TSTKQTY'] = 0;
							} else {
								$n['PLOTQTY'] += $balance;
								$k['TSTKQTY'] -= $balance;
							}
						} else {
							break;
						}
					}
				}
				unset($k);
			}
			unset($n);
			$myar[] = ['cd' => 1, 'msg' => 'OK', 'reff' => $rsstock];
		} else {
			$myar[] = ['cd' => 1, 'msg' => 'OK'];
			$csimnoCount = count($csimno);
			$docno_in = "";
			for($i=0; $i<$csimnoCount; $i++){
				$docno_in .= "'".$csimno[$i]."',";
				$rsget = $this->SPL_mod->select_psnjob_req_for_simulate($csimno[$i]);
				$rs = array_merge($rs, $rsget);
			}
			$docno_in = substr($docno_in,0, strlen($docno_in)-1);
			$rsstock = $this->SPL_mod->select_sim_item_stock($docno_in);
			foreach($rs as &$n){
				foreach($rsstock as &$k){
					if($n['PIS3_MPART'] == $k['ITEMCODE'] && $k['TSTKQTY'] >0){
						if($n['REQQTY'] > $n['PLOTQTY']){
							$balance = $n['REQQTY'] - $n['PLOTQTY'];
							if($balance > $k['TSTKQTY']){
								$n['PLOTQTY'] += $k['TSTKQTY'];
								$k['TSTKQTY'] = 0;
							} else {
								$n['PLOTQTY'] += $balance;
								$k['TSTKQTY'] -= $balance;
							}
						} else {
							break;
						}
					}
				}
				unset($k);
			}
			unset($n);
	
			foreach($rs as &$n){
				foreach($rsstock as &$k){
					if($n['PIS3_ITMCD'] == $k['ITEMCODESUB'] && $k['TSTKSUBQTY'] >0){
						if($n['REQQTY'] > ($n['PLOTQTY'] + $n['PLOTSUBQTY']) ){
							$balance = $n['REQQTY'] - ($n['PLOTQTY']+ $n['PLOTSUBQTY']);
							if($balance > $k['TSTKSUBQTY']){
								$n['PLOTSUBQTY'] += $k['TSTKSUBQTY'];
								$k['TSTKSUBQTY'] = 0;
							} else {
								$n['PLOTSUBQTY'] += $balance;
								$k['TSTKSUBQTY'] -= $balance;
							}
						} else {
							break;
						}
					}
				}
				unset($k);
			}
			unset($n);
		}
		die(json_encode(['data' => $rs, 'status' => $myar]));
	}

	public function requestdoc(){
		$userid = $this->session->userdata('nama');
		$rsemp = $this->Usr_mod->select_NPSI_user_where(['MSTEMP_ID' => $userid]);
		$rsremark = $this->RQSRMRK_mod->selectAll();
		$usrdept = '';
		foreach($rsemp as $r){
			$usrdept = $r['PSIDEPT'] == "" ?  "OTH" : $r['PSIDEPT'];
		}
		$reamrklist = '';
		foreach($rsremark as $r){
			$reamrklist .= '<option value="'.$r['RQSRMRK_CD'].'">'.$r['RQSRMRK_DESC'].'</value>';
		}
		$data['userdept'] = $usrdept;
		$data['remarklist'] = $reamrklist;
		$this->load->view('wms/vrequestpart', $data);
	}
    
    public function getdiff(){
        header('Content-Type: application/json');
        $rs = $this->SPL_mod->selectdiff();
        echo json_encode($rs);
	}

	public function searchdok_partreq(){
		header('Content-Type: application/json');
		$doc = $this->input->get('indoc');
		$doctype = $this->input->get('indoctype');
		$rs = [];
		switch($doctype){
			case 'D1':
				$rs = $this->SPL_mod->select_partreq_h_bydoc($doc);break;			
		}
		$rsret = [];
		foreach($rs as &$r){
			$rsra = $this->SPL_mod->select_reffdoc($r['SPL_DOC']);
			$rano = '';
			foreach($rsra as $ra){
				$rano .= $ra['SPL_REFDOCNO'].",";
			}
			$r['REFFDOC'] = substr($rano,0,strlen($rano)-1);
			$rsret[] = $r;
		}
		unset($r);
		die('{"data":'.json_encode($rsret).'}');
	}

	public function get_saved_partreq(){
		header('Content-Type: application/json');
		$indoc = $this->input->get('indoc');
		$rs = $this->SPL_mod->select_partreq_d_bydoc($indoc);
		die('{"data":'.json_encode($rs).'}');
	}	

	public function requestpart(){
		header('Content-Type: application/json');
		date_default_timezone_set('Asia/Jakarta');
		$docno = $this->input->post('docno');
		$dept = $this->input->post('dept');		
		$category = $this->input->post('category');
		$remark = $this->input->post('remark');
		$line = $this->input->post('line');
		if(strlen($line)==0){
			$line='_';
		} else {
			if($line=='-'){
				$line='_';
			}
		}
		$a_reffdoc = $this->input->post('a_reffdoc');
		$a_partcode = $this->input->post('a_partCode');
		$a_qty = $this->input->post('a_qty');
		$a_line = $this->input->post('a_line');
		$a_partRemark = $this->input->post('a_partRemark');
		$a_model = $this->input->post('a_model');
		$myar = [];
		$shouldContinue = false;
		$currrtime 	= date('Y-m-d H:i:s');
		
		$crn_year = date('Y');
		$crn_month = date('m');
		if(is_array($a_reffdoc)){
			$a_length = count($a_reffdoc);
			if($a_length>0){
				$rsrack = $this->ITMLOC_mod->selectbyitemcdin($a_partcode);
				$u_reffdoc = array_values(array_unique($a_reffdoc));
				$u_reffdoc_length = count($u_reffdoc);
				$rsReffDoc = [];
				$initData2 = '';
				$initData5 = ''; #order number / nomor urut
				$initBISGRUP = NULL;
								
				if($category=="PSN"){
					$rsReffDoc = $this->SPL_mod->select_ppsn2_psno($u_reffdoc);										
					if(count($rsReffDoc)>0){ #IF REFF PSN EXIST ?						
						$aField = explode("-",$u_reffdoc[0]);
						$bg_initial = $aField[1];
						$initData2 = $bg_initial;
						$u_bg = [];
						for($i=0; $i<$u_reffdoc_length; $i++){
							$aField = explode("-",$u_reffdoc[0]);
							$bg_initial = $aField[1];
							if(!in_array($bg_initial, $u_bg)){
								$u_bg[] = $bg_initial;
							}
						}
						if(count($u_bg)>1){
							$myar[] = ['cd' => 0, 'msg' => 'Could not request from more than 1 rows'];
						} else {
							$rsBISGRP = $this->SPL_mod->select_bg_ppsn($u_reffdoc);
							foreach($rsBISGRP as $k){
								$initBISGRUP = $k['PPSN1_BSGRP'];
							}
							$shouldContinue = true;
						}
					} else {
						$myar[] = ['cd' => 0, 'msg' => 'PSN is not found'];
					}
				} elseif($category=="RAD") {
					$rsReffDoc = $this->STKTRN_mod->select_docno($u_reffdoc);
					if(count($rsReffDoc)>0){
						$shouldContinue = true;
						$initData2 = $category;
						foreach($rsReffDoc as $b){
							$initBISGRUP = $b['BSGRP'];
						}
					} else {
						$myar[] = ['cd' => 0, 'msg' => 'RA is not found'];
					}
				} else  {
					$initData2 = $dept;
					$rsbg = $this->SPL_mod->select_bg($a_partcode);
					$rsbg_count = count($rsbg);
					if($rsbg_count>0){
						if($rsbg_count > 1) {
							$myar[] = ['cd' => 3, 'msg' => 'Business Group more than 1'];
						} else {
							$shouldContinue = true;
							foreach($rsbg as $b){
								$initBISGRUP = $b['PPSN2_BSGRP'];
							}
						}
					} else {
						$rsbg = $this->RCV_mod->select_bg($a_partcode);
						$rsbg_count = count($rsbg);
						if($rsbg_count>0){
							if($rsbg_count > 1) {
								$myar[] = ['cd' => 3, 'msg' => 'Business Group more than 1.'];
							} else {
								$shouldContinue = true;
								foreach($rsbg as $b){
									$initBISGRUP = $b['RCV_BSGRP'];
								}
							}
						} else {
							$myar[] = ['cd' => 0, 'msg' => 'Part Code is not found'];
						}
					}
				}
				if($shouldContinue){
					if($this->SPL_mod->check_Primary(['SPL_DOC' => $docno])){
						$rsSPL = [];
						#validate rack					
						for($i=0;$i<$a_length;$i++){
							$isallrack_ready = false;						
							foreach($rsrack as $rk){
								if(trim($a_partcode[$i]) == $rk['ITMLOC_ITM']){
									$isallrack_ready = true;								
									break;
								}
							}
							if(!$isallrack_ready){
								$myar[] = ['cd' => 0, 'msg' => 'Rack is '.trim($a_partcode[$i]).' not found'];
								die('{"status":'.json_encode($myar).'}');
							}
						}
						$ttlsaved = 0;
						$ttlupdated = 0;
						$lastlinedata = $this->SPL_mod->select_last_line_doc($docno)+1;
						for($i=0;$i<$a_length;$i++){
							$therack = '';
							foreach($rsrack as $rk){
								if(trim($a_partcode[$i]) == $rk['ITMLOC_ITM']){
									$therack = $rk['ITMLOC_LOC'];
									break;
								}
							}
							if(strlen($a_line[$i])==0){ #IF NOT SAVED YET
								$rsSPL = [
									'SPL_DOC' => $docno,
									'SPL_LINE' => $line,
									'SPL_CAT' => '_',
									'SPL_FEDR' => '_',
									'SPL_MC' => '_',
									'SPL_ORDERNO' => $lastlinedata,
									'SPL_ITMCD'	=> $a_partcode[$i],
									'SPL_MS' => '_',
									'SPL_RACKNO' => $therack,
									'SPL_QTYUSE' => 1,
									'SPL_QTYREQ' => $a_qty[$i],
									'SPL_LINEDATA' => $lastlinedata,
									'SPL_REFDOCCAT' => $category,
									'SPL_REFDOCNO' => $a_reffdoc[$i],
									'SPL_BG' => $initBISGRUP,
									'SPL_USRGRP' => $dept,
									'SPL_RMRK' => $remark,
									'SPL_ITMRMRK' => $a_partRemark[$i],
									'SPL_FMDL' => $a_model[$i],
									'SPL_LUPDT' => $currrtime,
									'SPL_USRID' => $this->session->userdata('nama')
								];
								$ttlsaved+=$this->SPL_mod->insert($rsSPL);
								$this->SPL_mod->insert_log($rsSPL);
							} else {
								$ttlupdated += $this->SPL_mod->updatebyId(
								[
									'SPL_QTYREQ' => $a_qty[$i]
									,'SPL_ITMCD' => $a_partcode[$i]
									,'SPL_RMRK' => $remark
									,'SPL_RACKNO' => $therack
									,'SPL_ITMRMRK' => $a_partRemark[$i]
									,'SPL_REFDOCNO' => $a_reffdoc[$i]
								], [
									'SPL_DOC' => $docno
									,'SPL_LINEDATA' => $a_line[$i]
									]
								);
							}
						}
						$myar[] = ['cd' => 1, 'msg' => "$ttlsaved row(s) saved and $ttlupdated row(s) updated", 'doc' => $docno];
					} else {
						$lastno = $this->SPL_mod->selectlastno_request($crn_year, $crn_month, $initData2)+1;
						$initData5 = substr('000'.$lastno, -4);
						$rsSPL = [];
						$newPartReqRoc = "PR-".strtoupper($initData2)."-".$crn_year."-".$crn_month."-".$initData5;
						#validate rack
						for($i=0;$i<$a_length;$i++){
							$isallrack_ready = false;						
							foreach($rsrack as $rk){
								if(trim($a_partcode[$i]) == $rk['ITMLOC_ITM']){
									$isallrack_ready = true;								
									break;
								}
							}
							if(!$isallrack_ready){
								$myar[] = ['cd' => 0, 'msg' => 'Rack is '.trim($a_partcode[$i]).' not found'];
								die('{"status":'.json_encode($myar).'}');
							}
						}
						$ttlsaved = 0;						
						for($i=0;$i<$a_length;$i++){
							$therack = '';
							foreach($rsrack as $rk){
								if(trim($a_partcode[$i]) == $rk['ITMLOC_ITM']){
									$therack = $rk['ITMLOC_LOC'];
									break;
								}
							}							
							$rsSPL = [
								'SPL_DOC' => $newPartReqRoc,								
								'SPL_LINE' => $line,
								'SPL_CAT' => '_',
								'SPL_FEDR' => '_',
								'SPL_MC' => '_',
								'SPL_ORDERNO' => $i,
								'SPL_ITMCD'	=> $a_partcode[$i],
								'SPL_MS' => '_',
								'SPL_RACKNO' => $therack,
								'SPL_QTYUSE' => 1,
								'SPL_QTYREQ' => $a_qty[$i],
								'SPL_LINEDATA' => $i,
								'SPL_REFDOCCAT' => $category,
								'SPL_REFDOCNO' => $a_reffdoc[$i],
								'SPL_BG' => $initBISGRUP,
								'SPL_USRGRP' => $dept,
								'SPL_RMRK' => $remark,
								'SPL_ITMRMRK' => $a_partRemark[$i],
								'SPL_FMDL' => $a_model[$i],
								'SPL_LUPDT' => $currrtime,
								'SPL_USRID' => $this->session->userdata('nama')
							];
							$ttlsaved+=$this->SPL_mod->insert($rsSPL);
							$this->SPL_mod->insert_log($rsSPL);
						}
						$myar[] = ['cd' => 1, 'msg' => "$ttlsaved row(s) saved", "doc" => $newPartReqRoc];
					}					
				}
			} else {
				$myar[] = ['cd' => 0, 'msg' => 'nothing will be processed'];
			}
		} else {
			$myar[] = ['cd' => 0, 'msg' => 'array data is required'];
		}
		
		die('{"status":'.json_encode($myar).'}');		
	}

	public function getpsn_byjob(){
		header('Content-Type: application/json');
		$cjob = $this->input->get('injob');		
		$rspsn_distc = $this->SPL_mod->select_z_getpsn_byjob("'".$cjob."'");
		$findpsn = [];
		foreach($rspsn_distc as $r){
			$findpsn[]= $r['PPSN1_PSNNO'];
		}
		$rspsn = $this->SPL_mod->select_allxppsn2_bypsn($findpsn);
		$rsmcz  = $this->SPL_mod->select_mcz_xppsn2_bypsn($findpsn);
		$rsjob = $this->SPL_mod->select_xwo(['PDPP_WORQT'],['PDPP_WONO' => $cjob]);
		die('{"data_h": '.json_encode($rspsn_distc).', "data": '.json_encode($rspsn).', "datajob": '.json_encode($rsjob).', "datamcz" : '.json_encode($rsmcz).'}');
	}
	public function getpsn_byjob_delv(){
		header('Content-Type: application/json');
		$cjob = $this->input->get('injob');
		$rscombinedjob = $this->SERC_mod->select_combined_jobs_definition($cjob);
		$joblist = [];
		$combinedjob = "";
		if(count($rscombinedjob)>0){
			$combinedjob = $cjob;
			$cjob = "";
			foreach($rscombinedjob as $r){
				$cjob .= "'".$r['SERC_COMJOB']."',";
				$joblist[] = $r['SERC_COMJOB'];
			}
			$cjob = substr($cjob,0,strlen($cjob)-1);
		} else {
			$combinedjob = $cjob;
			$joblist[] = $cjob;
			$cjob = "'".$cjob."'";
		}
		$rspsn_distc = $this->SPL_mod->select_z_getpsn_byjob($cjob);
		$findpsn = [];
		foreach($rspsn_distc as $r){
			$findpsn[] = $r['PPSN1_PSNNO'];
		}
		$rspsn = $this->SPL_mod->select_allxppsn2_bypsn($findpsn);
		$rsmcz  = $this->SPL_mod->select_mcz_xppsn2_bypsn($findpsn);
		$rsjob = $this->SPL_mod->select_xwo_delv(['PDPP_WORQT','PDPP_WONO'], $joblist);
		foreach($rsjob as &$r){
			$r['CWONO'] = $combinedjob;
		}
		unset($r);
		die('{"data_h": '.json_encode($rspsn_distc).', "data": '.json_encode($rspsn).', "datajob": '.json_encode($rsjob).', "datamcz" : '.json_encode($rsmcz).'}');
	}
	public function getpsn_byjob_mcz(){
		header('Content-Type: application/json');
		$cjob = $this->input->get('injob');
		$cmcz = $this->input->get('inmcz');
		$rspsn_distc = $this->SPL_mod->select_z_getpsn_byjob("'".$cjob."'");
		$findpsn = [];
		$rspsn = [];
		foreach($rspsn_distc as $r){
			$findpsn[] = $r['PPSN1_PSNNO'];
		}
		if($cmcz=='-'){
			$rspsn = $this->SPL_mod->select_allxppsn2_bypsn($findpsn);
		} else {
			$rspsn = $this->SPL_mod->select_allxppsn2_bypsn_mcz($findpsn, $cmcz);
		}
		$rsjob = $this->SPL_mod->select_xwo(['PDPP_WORQT'],['PDPP_WONO' => $cjob]);
		die('{"data": '.json_encode($rspsn).', "datajob": '.json_encode($rsjob).'}');
	}

	public function cancel_kitting_test(){
		header('Content-Type: application/json');
		date_default_timezone_set('Asia/Jakarta');
		$currrtime 	= date('Y-m-d H:i:s');

		die(json_encode(['time' => $currrtime]));
		$cidscan = $this->input->get('inidscan');
		$myar = [];
		$rs = $this->SPLSCN_mod->selectby_filter(['SPLSCN_ID' => $cidscan]);
		$_psn = $_itemcd = $_timescan = '';
		foreach($rs as $r){
			$_psn = $r['SPLSCN_DOC'];
			$_itemcd = $r['SPLSCN_ITMCD'];
			$_timescan = $r['SPLSCN_LUPDT'];
		}
		$rsBOOK = $this->SPLBOOK_mod->select_book_where(['SPLBOOK_SPLDOC' => $_psn, 'SPLBOOK_ITMCD' => $_itemcd]);
		$shouldReBook = false;
		$isLoopPassed = false;
		foreach($rsBOOK as $r){
			$isLoopPassed = true;
			if($_timescan>$r['SPLBOOK_LUPDTD']){
				$shouldReBook = true;
			}
		}
		die(json_encode(['rsSCN' => $rs
		, 'rsBOOK' => $rsBOOK
		, 'shouldReBook' => $shouldReBook, 'isLoopPassed' => $isLoopPassed]));
	}

	public function select_scanned_per_document(){
		header('Content-Type: application/json');
		$doc = $this->input->get('doc');
		$rs = $this->SPLSCN_mod->selectby_filter(['SPLSCN_DOC' => $doc]);
		die(json_encode(['data_unfixed' => $rs]));
	}

	public function cancel_kitting_per_idscan_array(){
		# Purpose : cancel kitting per id scan
		# Expected transaction : raw material location [+], plant location [-]
		$this->checkSession();
		date_default_timezone_set('Asia/Jakarta');
		$crn_date = date('Y-m-d');
		$cidscan = $this->input->post('inidscan');
		$myar = [];
		$rs = $this->SPLSCN_mod->selectby_ID_whereIn($cidscan);
		$cpsn = '';
		foreach($rs as $r) {
			$cpsn = $r['SPLSCN_DOC'];break;
		}		
		$cwh_out = '';
		$rsbg = $this->SPL_mod->select_bg_partreq([$cpsn]);
		foreach($rsbg as $r){
			switch($r['PPSN1_BSGRP']){
				case 'PSI1PPZIEP':
					$cwh_inc = 'ARWH1';
					$cwh_out = 'PLANT1';
					break;
				case 'PSI2PPZADI':
					$cwh_inc = 'ARWH2';
					$cwh_out = 'PLANT2';
					break;
				case 'PSI2PPZINS':
					$cwh_inc = 'NRWH2';
					$cwh_out = 'PLANT_NA';
					break;
				case 'PSI2PPZOMC':
					$cwh_inc = 'NRWH2';
					$cwh_out = 'PLANT_NA';
					break;
				case 'PSI2PPZOMI':
					$cwh_inc = 'ARWH2';
					$cwh_out = 'PLANT2';
					break;
				case 'PSI2PPZSSI':
					$cwh_inc = 'NRWH2';
					$cwh_out = 'PLANT_NA';
					break;
				case 'PSI2PPZSTY':
					$cwh_inc = 'ARWH2';
					$cwh_out = 'PLANT2';
					break;
				case 'PSI2PPZTDI':
					$cwh_inc = 'ARWH2';
					$cwh_out = 'PLANT2';
					break;
			}
			break;
		}
		if($cwh_out===''){
			$rsbg = $this->SPL_mod->select_bg_ppsn([$cpsn]);
			foreach($rsbg as $r){
				switch($r['PPSN1_BSGRP']){
					case 'PSI1PPZIEP':
						$cwh_inc = 'ARWH1';
						$cwh_out = 'PLANT1';
						break;
					case 'PSI2PPZADI':
						$cwh_inc = 'ARWH2';
						$cwh_out = 'PLANT2';
						break;
					case 'PSI2PPZINS':
						$cwh_inc = 'NRWH2';
						$cwh_out = 'PLANT_NA';
						break;
					case 'PSI2PPZOMC':
						$cwh_inc = 'NRWH2';
						$cwh_out = 'PLANT_NA';
						break;
					case 'PSI2PPZOMI':
						$cwh_inc = 'ARWH2';
						$cwh_out = 'PLANT2';
						break;
					case 'PSI2PPZSSI':
						$cwh_inc = 'NRWH2';
						$cwh_out = 'PLANT_NA';
						break;
					case 'PSI2PPZSTY':
						$cwh_inc = 'ARWH2';
						$cwh_out = 'PLANT2';
						break;
					case 'PSI2PPZTDI':
						$cwh_inc = 'ARWH2';
						$cwh_out = 'PLANT2';
						break;
				}
				break;
			}
		}
		if(!empty($rs)>0){
			foreach($rs as $r){
				$thedoc = trim($r['SPLSCN_DOC'])."|".trim($r['SPLSCN_CAT'])."|".trim($r['SPLSCN_LINE'])."|".trim($r['SPLSCN_FEDR']);
				$theitem = trim($r['SPLSCN_ITMCD']);
				$theqty = $r['SPLSCN_QTY'];
				$where = ['ITH_DOC' => $thedoc, 'ITH_ITMCD' => $theitem , 'ITH_WH' => $cwh_out];
				if($this->ITH_mod->check_Primary($where)>0 ){
					$retith = 0;
					//adjust transaction
					$retith = $this->ITH_mod->insert_cancel_kitting_out(['ITH_ITMCD' => $theitem ,  'ITH_WH' => $cwh_out,
						'ITH_DATE' => $crn_date , 'ITH_DOC' => $thedoc, 'ITH_QTY' => -$theqty, 'ITH_USRID' => $this->session->userdata('nama')]);
					$retith += $this->ITH_mod->insert_cancel_kitting_in(['ITH_ITMCD' => $theitem , 'ITH_WH' => $cwh_inc ,
						'ITH_DATE' => $crn_date , 'ITH_DOC' => $thedoc, 'ITH_QTY' => $theqty, 'ITH_USRID' => $this->session->userdata('nama')]);
					if($retith == 2){
						//delete scannning history
						if($this->SPLSCN_mod->deleteby_filter(['SPLSCN_ID' => $r['SPLSCN_ID'] ]) >0 ){
							$myar[] = ['cd' => 1, 'msg' => 'canceled successfully', 'reff' => $r['SPLSCN_ID']];
						} else {
							$myar[] = ['cd' => 0, 
								'msg' => 'the transaction is successfully adjusted but could not remove scanning history, please contact admin',
								'reff' => $r['SPLSCN_ID'] ];
						}
					} else {
						$myar[] = ['cd' => 0,
						'msg' => 'could not adjust transaction please contact admin',
						'reff' => $r['SPLSCN_ID']];
					}
				} else {
					if($this->ITH_mod->check_Primary(['ITH_DOC' => $thedoc, 'ITH_ITMCD' => $theitem])>0 ){
						$this->ITH_mod->deletebyID(['ITH_DOC' => $thedoc, 'ITH_ITMCD' => $theitem]);
						if($this->SPLSCN_mod->deleteby_filter(['SPLSCN_ID' => $r['SPLSCN_ID'] ]) >0 ){
							$myar[] = ['cd' => 1, 'msg' => 'canceled successfully..' ,'reff' => $r['SPLSCN_ID']];
						} else {
							$myar[] = ['cd' => 0, 
								'msg' => 'the transaction is successfully deleted but could not remove scanning history, please contact admin'
								,'reff' => $r['SPLSCN_ID']];
						}
					} else {
						if($this->SPLSCN_mod->deleteby_filter(['SPLSCN_ID' => $r['SPLSCN_ID']]) >0 ){
							$myar[]  = ['cd' => 1, 'msg' => 'canceled successfully','reff' => $r['SPLSCN_ID']];
						} else {
							$myar[] = ['cd' => 0, 'msg' => 'delete failed','reff' => $r['SPLSCN_ID']];
						}
					}
				}
			}
		} else {
			$myar[] = ['cd' => 0, 'msg' => 'The data is not found, so it could not be canceled', 'reff' => ''];
		}
		die(json_encode(['status' => $myar]));
	}

	public function cancel_kitting(){
		$this->checkSession();
		date_default_timezone_set('Asia/Jakarta');
		$crn_date = date('Y-m-d');
		$cidscan = $this->input->get('inidscan');
		$myar = [];
		$rs = $this->SPLSCN_mod->selectby_filter(['SPLSCN_ID' => $cidscan]);
		$cpsn = $_itemcd = $_timescan = $thebookID ='';
		foreach($rs as $r) {
			$cpsn = $r['SPLSCN_DOC'];
			$_itemcd = $r['SPLSCN_ITMCD'];
			$_timescan = $r['SPLSCN_LUPDT'];
		}
		$rsBOOK = $this->SPLBOOK_mod->select_book_where(['SPLBOOK_SPLDOC' => $cpsn, 'SPLBOOK_ITMCD' => $_itemcd]);
		$shouldReBook = false;		
		foreach($rsBOOK as $r){						
			if($_timescan>$r['SPLBOOK_LUPDTD']){
				$shouldReBook = true;
				$thebookID = $r['SPLBOOK_DOC'];
			}
		}
		$cwh_out = '';
		$rsbg = $this->SPL_mod->select_bg_partreq([$cpsn]);
		foreach($rsbg as $r){
			switch($r['PPSN1_BSGRP']){
				case 'PSI1PPZIEP':
					$cwh_inc = 'ARWH1';
					$cwh_out = 'PLANT1';
					break;
				case 'PSI2PPZADI':
					$cwh_inc = 'ARWH2';
					$cwh_out = 'PLANT2';
					break;
				case 'PSI2PPZINS':
					$cwh_inc = 'NRWH2';
					$cwh_out = 'PLANT_NA';
					break;
				case 'PSI2PPZOMC':
					$cwh_inc = 'NRWH2';
					$cwh_out = 'PLANT_NA';
					break;
				case 'PSI2PPZOMI':
					$cwh_inc = 'ARWH2';
					$cwh_out = 'PLANT2';
					break;
				case 'PSI2PPZSSI':
					$cwh_inc = 'NRWH2';
					$cwh_out = 'PLANT_NA';
					break;
				case 'PSI2PPZSTY':
					$cwh_inc = 'ARWH2';
					$cwh_out = 'PLANT2';
					break;
				case 'PSI2PPZTDI':
					$cwh_inc = 'ARWH2';
					$cwh_out = 'PLANT2';
					break;
			}
			break;
		}
		if(count($rs)>0){
			$thedoc = '';
			$theitem = '';
			$theqty = 0;
			foreach($rs as $r){
				$thedoc = trim($r['SPLSCN_DOC'])."|".trim($r['SPLSCN_CAT'])."|".trim($r['SPLSCN_LINE'])."|".trim($r['SPLSCN_FEDR']);
				$theitem = trim($r['SPLSCN_ITMCD']);
				$theqty = $r['SPLSCN_QTY'];
			}
			$where = ['ITH_DOC' => $thedoc, 'ITH_ITMCD' => $theitem , 'ITH_WH' => $cwh_out];
			if($this->ITH_mod->check_Primary($where)>0 ){
				//adjust transaction
				$retith = $this->ITH_mod->insert_cancel_kitting_out(['ITH_ITMCD' => $theitem ,  'ITH_WH' => $cwh_out,
					'ITH_DATE' => $crn_date , 'ITH_DOC' => $thedoc, 'ITH_QTY' => -$theqty, 'ITH_USRID' => $this->session->userdata('nama')]);
				$retith += $this->ITH_mod->insert_cancel_kitting_in(['ITH_ITMCD' => $theitem , 'ITH_WH' => $cwh_inc ,
					'ITH_DATE' => $crn_date , 'ITH_DOC' => $thedoc, 'ITH_QTY' => $theqty, 'ITH_USRID' => $this->session->userdata('nama')]);
				if($retith ==2 ){
					if($shouldReBook) {
						$datab = [
							'ITH_ITMCD' => $r['SPLSCN_ITMCD'], 'ITH_WH' =>  $cwh_inc , 
							'ITH_DOC' 	=> $thebookID, 'ITH_DATE' => $crn_date,
							'ITH_FORM' 	=> 'BOOK-SPL-3', 'ITH_QTY' => -$r['SPLSCN_QTY'], 
							'ITH_REMARK' => $r['SPLSCN_LOTNO'],
							'ITH_USRID' =>  $this->session->userdata('nama')
						];
						$this->ITH_mod->insert_spl($datab);
					}
					//delete scannning history
					if($this->SPLSCN_mod->deleteby_filter(['SPLSCN_ID' => $cidscan]) >0 ){
						$myar[] = ['cd' => 1, 'msg' => 'canceled successfully'];
					} else {
						$myar[] = ['cd' => 0, 'msg' => 'the transaction is successfully adjusted but could not remove scanning history, please contact admin'];
					}
				} else {
					$myar[] = ['cd' => 0, 'msg' => 'could not adjust transaction please contact admin'];
				}
			} else {
				if($this->ITH_mod->check_Primary(['ITH_DOC' => $thedoc, 'ITH_ITMCD' => $theitem])>0 ){
					$this->ITH_mod->deletebyID(['ITH_DOC' => $thedoc, 'ITH_ITMCD' => $theitem]);
					if($this->SPLSCN_mod->deleteby_filter(['SPLSCN_ID' => $cidscan]) >0 ){
						$myar[] = ['cd' => 1, 'msg' => 'canceled successfully..'];
					} else {
						$myar[] = ['cd' => 0, 'msg' => 'the transaction is successfully deleted but could not remove scanning history, please contact admin'];
					}
					// $myar[] = ['cd' => 0, 'msg' => 'Please recheck your selected warehouse', 'where' => $where];
				} else {
					if($this->SPLSCN_mod->deleteby_filter(['SPLSCN_ID' => $cidscan]) >0 ){
						$myar[]  = ['cd' => 1, 'msg' => 'canceled successfully'];
					} else {
						$myar[] = ['cd' => 0, 'msg' => 'delete failed'];
					}
				}
			}
		} else {
			$myar[] = ['cd' => 0, 'msg' => 'The data is not found, so it could not be canceled'];
		}
		echo '{"status": '.json_encode($myar).'}';
	}
	
	public function sync_mega(){
		date_default_timezone_set('Asia/Jakarta');		
		header('Content-Type: application/json');
		$myar = [];
		$mystatus = [];
		$cpsn	= $this->input->get('inpsn');
		$cline	= $this->input->get('inline');
		$ccat	= $this->input->get('incat');
		$cfr	= $this->input->get('infr');
		$bookdate = date('Y-m-d');
		$currrtime 	= date('Y-m-d H:i:s');
		$bookdate_a = explode('-',$bookdate);
		$_year = substr($bookdate_a[0],-2);
		$_month = $bookdate_a[1]*1;
		$_date = $bookdate_a[2];
		$rsdiscrepancy = substr($cpsn,0,2) == 'PR' ? [] : $this->SPLSCN_mod->select_discrepancy_scanned_vs_newsynchronized($cpsn);
		if(count($rsdiscrepancy)>0){
			$fedr_mcz = '';
			$fedr_item = $fedr_cat = $fedr_line = '';
			foreach($rsdiscrepancy as $r){
				$fedr_mcz = $r['SPLSCN_ORDERNO'];
				$fedr_item = $r['SPLSCN_ITMCD'];
				$fedr_cat = $r['SPLSCN_CAT'];
				$fedr_line = $r['SPLSCN_LINE'];
				$fedr_fr = $r['SPLSCN_FEDR'];
			}
			$mystatus[] = ['cd' => '0', 'msg' => 'There is a discrepancy between <b>scanned data</b> and <b>the latest data of MEGA</b>' , 
			'msgdetail' => 'Please check Category <b>'.$fedr_cat.'</b>, Line <b>'.$fedr_line.'</b>, FR <b>'.$fedr_fr.'</b>,  Table <b>'.$fedr_mcz.'</b> and Item <b>'.$fedr_item.' </b> . Canceling scanned data might be required'] ;
			exit('{"status": '.json_encode($mystatus).'}');			
		} else {
			$mystatus[] = ['cd' => '1', 'msg' => 'go ahead'];
		}
		$rs = $this->SPL_mod->xspl_mega($cpsn, $cline, $ccat, $cfr);
		$criteria = ["SPL_DOC" => $cpsn];
		if($this->SPL_mod->check_Primary($criteria)>0 ){
			$this->SPL_mod->deleteby_filter($criteria);
		}
		$ttlrows = count($rs);
		if($ttlrows>0){
			foreach($rs as $r){				
				$datac = [
					'SPL_DOC' 		=> trim($r['PPSN2_PSNNO']),
					'SPL_DOCNO'		=> trim($r['PPSN2_DOCNO']),						
					'SPL_LINE' 		=> trim($r['PPSN2_LINENO']),
					'SPL_CAT' 		=> $r['PPSN2_ITMCAT'],
					'SPL_PROCD' 	=> trim($r['PPSN2_PROCD']),
					'SPL_FEDR' 		=> $r['PPSN2_FR'],
					'SPL_MC' 	=> trim($r['PPSN2_MC']),
					'SPL_ORDERNO' 	=> trim($r['PPSN2_MCZ']),
					'SPL_ITMCD' 	=> trim($r['PPSN2_SUBPN']),
					'SPL_MS' 		=> trim($r['PPSN2_MSFLG']),
					'SPL_BG' 		=> $r['PPSN2_BSGRP']
				];
				if($this->SPL_mod->check_Primary($datac)==0){										
					$datac['SPL_RACKNO']= trim($r['ITMLOC_LOC']);					
					$datac['SPL_QTYUSE']= $r['PPSN2_QTPER'];						
					$datac['SPL_QTYREQ']= $r['PPSN2_REQQT'];
					$datac['SPL_LUPDT']	= $currrtime;
					$datac['SPL_USRID']	= $this->session->userdata('nama');
					$this->SPL_mod->insert($datac);					
				}
			}
			// //BOOK SP & PCB
			// $bookid = '';
			// if($this->SPLBOOK_mod->check_Primary(['SPLBOOK_SPLDOC' => $cpsn])){
			// 	//handle condition when :
			// 	// - synchronize in several times - handled
			// 	// - req. qty is changed
			// 	// - part code is changed - handled
			// 	// - PSN is deleted - handled
			// 	//additional handler should be exist when cancel Kitting
			// 	// $rsCurrentSPL = $this->SPL_mod->select_per_category([$cpsn], ['PCB','SP']);
			// 	$rsBOOK = $this->SPLBOOK_mod->select_book_where(['SPLBOOK_SPLDOC' => $cpsn]);
			// 	$rsDiff = $this->SPL_mod->select_booked_spl_diff($cpsn);
				
			// 	$bookline = 0;
			// 	foreach($rsBOOK as $r){
			// 		$bookid = $r['SPLBOOK_DOC'];
			// 		if($bookline<$r['SPLBOOK_LINE']){
			// 			$bookline = $r['SPLBOOK_LINE'];
			// 		}
			// 	}
			// 	$bookline++;				
			// 	$datas =[];
			// 	foreach($rsDiff as $r){
			// 		if(!$r['SPLBOOK_ITMCD']){
			// 			# handle condition when part code is exist in SPL but it is not exist in BOOK
			// 			$datas[] = [
			// 				'SPLBOOK_DOC' => $bookid,
			// 				'SPLBOOK_SPLDOC' => $cpsn,
			// 				'SPLBOOK_CAT' => $r['SPL_CAT'],
			// 				'SPLBOOK_ITMCD' => $r['SPL_ITMCD'],
			// 				'SPLBOOK_QTY' => $r['RQT'],
			// 				'SPLBOOK_DATE' => $bookdate,
			// 				'SPLBOOK_LINE' => $bookline++,
			// 				'SPLBOOK_LUPDTD' => $currrtime,
			// 				'SPLBOOK_USRID' => $this->session->userdata('nama')
			// 			];
			// 			# end handle 
			// 		} else {
			// 			# handle condition when part code is exist in BOOK but it is not exist in SPL
			// 			$this->SPLBOOK_mod->deleteby_filter(['SPLBOOK_SPLDOC' => $cpsn, 'SPLBOOK_ITMCD' => $r['SPLBOOK_ITMCD']]);
			// 			$this->ITH_mod->deletebyID(['ITH_REMARK' => $cpsn, 'ITH_ITMCD' => $r['SPLBOOK_ITMCD']]);
			// 			# end handle
			// 		}
			// 	}
			// 	if(count($datas)>0)	{
			// 		$this->SPLBOOK_mod->insertb($datas);
			// 	}
			// } else {
			// 	$datas = [];
			// 	//handle condition When User Booked list is not exist
			// 	$rsready = $this->SPLSCN_mod->select_ready_book(['SPL_DOC' => $cpsn]);
			// 	$lastbookid = $this->SPLBOOK_mod->lastserialid($bookdate)+1;
			// 	$newdoc = 'B'.$_year.$this->AMONTHPATRN[($_month-1)].$_date.$lastbookid;				
			// 	$i=1;
			// 	foreach($rsready as $r){
			// 		if(!$this->SPLBOOK_mod->check_Primary(['SPLBOOK_SPLDOC' => $cpsn,'SPLBOOK_ITMCD' => $r['SPL_ITMCD']])){
			// 			$datas[] = [
			// 				'SPLBOOK_DOC' => $newdoc,
			// 				'SPLBOOK_SPLDOC' => $cpsn,
			// 				'SPLBOOK_CAT' => $r['SPL_CAT'],
			// 				'SPLBOOK_ITMCD' => $r['SPL_ITMCD'],
			// 				'SPLBOOK_QTY' => $r['BALQT'],
			// 				'SPLBOOK_DATE' => $bookdate,
			// 				'SPLBOOK_LINE' => $i++,
			// 				'SPLBOOK_LUPDTD' => $currrtime,
			// 				'SPLBOOK_USRID' => $this->session->userdata('nama')
			// 			];
			// 		}
			// 	}
			// 	$bookid = $newdoc;
			// 	if(count($datas)){
			// 		$this->SPLBOOK_mod->insertb($datas);
			// 	}
			// }
			// ///ITH///		
			// $rs = $this->SPLBOOK_mod->select_book_where(['SPLBOOK_DOC' => $bookid]);
			// $this->ITH_mod->deletebyID(['ITH_DOC' => $bookid, 'ITH_FORM' => 'BOOK-SPL-1']);		
			// $ith_data = [];
			// $psnlist = [];
			// foreach($rs as $r){
			// 	if(!in_array($r['SPLBOOK_SPLDOC'], $psnlist)){
			// 		$psnlist[] = $r['SPLBOOK_SPLDOC'];
			// 	}
			// }
			// if(count($psnlist)){
			// 	$rsbg = $this->SPL_mod->select_bg_psn($psnlist);
			// 	foreach($rs as $r){
			// 		$wh = NULL;
			// 		foreach($rsbg as $b){
			// 			if($r['SPLBOOK_SPLDOC']==$b['SPL_DOC']){
			// 				switch($b['SPL_BG']){
			// 					case 'PSI1PPZIEP':
			// 						$wh = 'ARWH1';							
			// 						break;
			// 					case 'PSI2PPZADI':
			// 						$wh = 'ARWH2';							
			// 						break;
			// 					case 'PSI2PPZINS':
			// 						$wh = 'NRWH2';							
			// 						break;
			// 					case 'PSI2PPZOMC':
			// 						$wh = 'NRWH2';							
			// 						break;
			// 					case 'PSI2PPZOMI':
			// 						$wh = 'ARWH2';							
			// 						break;
			// 					case 'PSI2PPZSSI':
			// 						$wh = 'NRWH2';							
			// 						break;
			// 					case 'PSI2PPZSTY':
			// 						$wh = 'ARWH2';							
			// 						break;
			// 					case 'PSI2PPZTDI':
			// 						$wh = 'ARWH2';							
			// 						break;
			// 				}
			// 				break;
			// 			}
			// 		}
			// 		$ith_data[] = [
			// 			'ITH_ITMCD' => $r['SPLBOOK_ITMCD'],
			// 			'ITH_DATE' => $bookdate,
			// 			'ITH_FORM' => 'BOOK-SPL-1',
			// 			'ITH_DOC' => $bookid,
			// 			'ITH_QTY' => -1*$r['SPLBOOK_QTY'],
			// 			'ITH_WH' => $wh,
			// 			'ITH_REMARK' => $r['SPLBOOK_SPLDOC'],
			// 			'ITH_LUPDT' => $currrtime,
			// 			'ITH_USRID' => $this->session->userdata('nama')
			// 		];
			// 	}
			// 	if(count($ith_data)){
			// 		$this->ITH_mod->insertb($ith_data);
			// 	}
			// }
			//END BOOK
			echo '{"data":'.json_encode($rs).',"status": '.json_encode($mystatus).'}';
		} else {
			$this->SPLBOOK_mod->deleteby_filter(['SPLBOOK_SPLDOC' => $cpsn]);
			$this->ITH_mod->deletebyID(['ITH_REMARK' => $cpsn]);
			$myar[] = ["cd" => 1, "msg" => "Data not found ".$cpsn];
			echo json_encode(['status' => $myar]);
		}		
	}

	public function getline_mfg(){
		header('Content-Type: application/json');		
		$rs		= $this->SPL_mod->select_line_mfg();
		echo '{"data":'.json_encode($rs).'}';		
	}
	
	function getWO(){
		header('Content-Type: application/json');
		$cwo	= $this->input->get('inwo');
		$rs		= $this->SPL_mod->selectWO($cwo);
		echo json_encode($rs);
	}
	function getWOOpen(){
		header('Content-Type: application/json');
		$cwo	= $this->input->get('inwo');
		$rs		= $this->SPL_mod->selectWOOpen($cwo);
		echo json_encode($rs);
	}
	function getWOOpen_assy_as_sub(){
		header('Content-Type: application/json');
		$cwo	= $this->input->get('inwo');
		$rs		= $this->SPL_mod->selectWOOpen_assy_as_sub($cwo);
		echo json_encode($rs);
	}

	function checkPSN(){
		$cpsn = $this->input->get('inpsn');
		$myar = [];
		$rs = $this->SPL_mod->select_category($cpsn);
		$rsunfixed = $this->SPLSCN_mod->select_unfully_canceled($cpsn);
		$ttlrows = count($rs);
		if($ttlrows>0){
			$myar[] = ["cd" => $ttlrows, "msg" => "GO AHEAD"];
		} else {
			$myar[] = ["cd" => $ttlrows, "msg" => "Trans No not found"];
		}
		die(json_encode([
			'data' => $rs,
			'data_unfixed' => $rsunfixed,
			'status' => $myar,
		]));		
	}

	function checkPSNCAT(){
		$cpsn = $this->input->get('inpsn');
		$ccat = $this->input->get('incat');
		$myar = [];
		$rs= $this->SPL_mod->select_line($cpsn, $ccat);
		$ttlrows = count($rs);
		if($ttlrows>0){
			$myar[] = ["cd" => $ttlrows, "msg" => "GO AHEAD"];
		} else {
			$myar[] = ["cd" => $ttlrows, "msg" => "Data not found"];
		}				
		echo '{"data":'.json_encode($rs)
		.',"status" : '.json_encode($myar)
		.'}';
	}

	function checkPSNCATLINE(){
		$cpsn = $this->input->get('inpsn');
		$ccat = $this->input->get('incat');
		$cline = $this->input->get('inline');
		$myar = array();
		$rs = $this->SPL_mod->select_fr($cpsn, $ccat, $cline);
		$ttlrows = count($rs);
		if($ttlrows>0){
			$datar = ["cd" => $ttlrows, "msg" => "GO AHEAD"];
		} else {
			$datar = array("cd" => $ttlrows, "msg" => "Data not found" );
		}
		array_push($myar, $datar);
		echo '{"data":';			
		echo json_encode($rs);
		echo ',"status" : '.json_encode($myar);
		echo '}';
	}

	function checkPSN_rack(){
		header('Content-Type: application/json');
		$cpsn = $this->input->get('inpsn');
		$ccat = $this->input->get('incat');
		$cline = $this->input->get('inline');
		$cfr = $this->input->get('infr');
		$crack = $this->input->get('inrack');
		$myar = [];
		$dataw = ["SPL_DOC" => $cpsn, "SPL_CAT" => $ccat, "SPL_LINE" => $cline, "SPL_FEDR" => $cfr, "SPL_RACKNO" => trim($crack)];
		$ttlrows = $this->SPL_mod->check_Primary($dataw);
		$myar[] = ["cd" => $ttlrows, "msg" => $ttlrows>0 ?   "go ahead" : "Data not found"];		
		echo '{"data":'.json_encode($myar).'}';
	}

	function checkPSNCATLINEFEEDR(){		
		header('Content-Type: application/json');
		$cpsn = $this->input->get('inpsn');
		$ccat = $this->input->get('incat');
		$cline = $this->input->get('inline');
		$cfr = $this->input->get('infr');		
		$myar = [];
		$ttlrows = $this->SPL_mod->check_Primary(['SPL_DOC' => $cpsn, 'SPL_CAT' => $ccat, 'SPL_LINE' => $cline, 'SPL_FEDR' => $cfr]);
		if($ttlrows>0){
			$rs = $this->SPL_mod->selectby4par($cpsn, $ccat, $cline, $cfr);
			$rsv = $this->SPL_mod->selectkitby4parv($cpsn, $ccat, $cline, $cfr);
			$rsdetail = $this->SPLSCN_mod->selectby_filter(['SPLSCN_DOC' => $cpsn, 'SPLSCN_CAT' => $ccat , 'SPLSCN_LINE' => $cline, 'SPLSCN_FEDR' => $cfr]);
			foreach($rsdetail as &$d){
				if(!array_key_exists("USED", $d)){
					$d["USED"] = false;
				}
			}
			unset($d);
			foreach($rsv as &$r){
				$think = true;
				while($think){
					$grasp = false;
					foreach($rsdetail as $d){
						if( ( strtoupper(trim($r['SPL_ORDERNO'])) == strtoupper(trim($d['SPLSCN_ORDERNO'])) ) && (strtoupper(trim($r['SPL_ITMCD'])) == strtoupper(trim($d['SPLSCN_ITMCD'])) ) && $d['USED']==false){
							$grasp = true; break;
						}
					}
					if($grasp){
						foreach($rsdetail as &$d){
							if( ( strtoupper(trim($r['SPL_ORDERNO'])) == strtoupper(trim($d['SPLSCN_ORDERNO'])) ) && (trim($r['SPL_ITMCD']) == strtoupper(trim($d['SPLSCN_ITMCD'])) ) && $d['USED']==false){
								$think2 = true;
								while($think2){
									if($r['TTLREQ'] > $r['TTLSCN']){
										if($d['USED']==false){
											$r['TTLSCN'] += $d['SPLSCN_QTY'];
											$d['USED'] = true;
										} else {
											$think2=false;
										}
									} else {
										$think2=false;
										$think=false;
									}
								}
							}
						}
						unset($d);
					} else {
						$think=false;
					}
				}
			}
			unset($r);
			$rs2 = $this->SPL_mod->selecthead($cpsn, $cline, $cfr);
			$rssavedqty = $this->SPLSCN_mod->selectsaved($cpsn, $ccat, $cline, $cfr);
			echo '{"data":'
			.json_encode($rs)
			.',"datahead":'
			.json_encode($rs2)
			.',"datasaved":'
			.json_encode($rssavedqty)
			.',"datav":'
			.json_encode($rsv)
			.'}';
		} else {			
			$myar[] = ["cd" => $ttlrows, "msg" => "Data not found"];
			echo '{"data":'.json_encode($myar).'}';			
		}		
	}

	function checkPSN_only(){		
		header('Content-Type: application/json');
		$cpsn = $this->input->get('inpsn');		
		$myar = [];
		$ttlrows = $this->SPL_mod->check_Primary(['SPL_DOC' => $cpsn]);
		if($ttlrows>0){
			$rs2 = substr($cpsn,0,3)=="PR-" ? [["PPSN1_WONO" => "_"]]  : $this->SPL_mod->selecthead_psnonly($cpsn);				
			$myar[] = ["cd" => $ttlrows, "msg" => "Go ahead"];
			echo '{"status":'
			.json_encode($myar)
			.',"datahead":'
			.json_encode($rs2)
			.'}';
		} else {			
			$myar[] = ["cd" => $ttlrows, "msg" => "Data not found"];
			echo '{"status":'
			.json_encode($myar)
			.'}';
		}		
	}
	
	function checkPSNCATLINENoFR(){		
		header('Content-Type: application/json');
		$cpsn = $this->input->get('inpsn');
		$ccat = $this->input->get('incat');
		$cline = $this->input->get('inline');
				
		$myar = [];
		$ttlrows = $this->SPL_mod->check_Primary(['SPL_DOC' => $cpsn, 'SPL_CAT' => $ccat, 'SPL_LINE' => $cline]);
		if($ttlrows>0){
			$rs = $this->SPL_mod->selectby3par($cpsn, $ccat, $cline);
			$rs2 = substr($cpsn,0,3)=="PR-" ? [["PPSN1_WONO" => "_"]]  : $this->SPL_mod->selecthead_nofr($cpsn, $cline);
			$rssavedqty = $this->SPLSCN_mod->selectsaved_nofr($cpsn, $ccat, $cline);
			$myar[] = ["cd" => "1", "msg" => "Data not found"];			
			echo '{"data":'
			.json_encode($rs)
			.',"datahead":'
			.json_encode($rs2)
			.',"datasaved":'
			.json_encode($rssavedqty)
			.',"status":'
			.json_encode($myar)
			.'}';
		} else {			
			$myar[] = ["cd" => $ttlrows, "msg" => "Data not found"];			
			echo '{"data":'
			.json_encode([])
			.',"status":'
			.json_encode($myar)
			.'}';
		}		
	}	

	function checkPSN_itm(){
		header('Content-Type: application/json');
		$cpsn = $this->input->get('inpsn');
		$ccat = $this->input->get('incat');
		$cline = $this->input->get('inline');
		$cfr = $this->input->get('infr');
		$citemcd = $this->input->get('incode');
		$crack = $this->input->get('inrack');
		$myar = [];
		$datac = [
			'SPL_DOC' => $cpsn, 'SPL_LINE' => $cline,
			'SPL_CAT' => $ccat, 'SPL_FEDR' => $cfr,
			'SPL_ITMCD' => $citemcd , 'SPL_RACKNO' => $crack
		];
		$ttlrows = $this->SPL_mod->check_Primary($datac);
		if($ttlrows>0){
			$rsitem = $this->MSTITM_mod->selectbyid($citemcd);
			$csptno = '';
			foreach($rsitem as $r){
				$csptno = $r->MITM_SPTNO;
			}
			$myar[] = ["cd" => $ttlrows, "msg" => "Go ahead" , "ref" => $csptno];
			echo '{"data":'.json_encode($myar).'}';
		} else {
			$myar[] = ["cd" => 0, "msg" => "Data not found in PSN"];
			echo '{"data":'.json_encode($myar).'}';			
		}
	}

	function checkPSN_itmret(){
		header('Content-Type: application/json');
		$cpsn = $this->input->get('inpsn');
		$ccat = $this->input->get('incat');
		$cline = $this->input->get('inline');
		$cfr = $this->input->get('infr');
		$citemcd = $this->input->get('incode');		
		$myar = [];
		$datac = [
			'SPL_DOC' => $cpsn, 'SPL_LINE' => $cline,
			'SPL_CAT' => $ccat, 'SPL_FEDR' => $cfr,
			'SPL_ITMCD' => $citemcd 
		];
		$ttlrows = $this->SPL_mod->check_Primary($datac);
		if($ttlrows>0){
			$rsitem = $this->MSTITM_mod->selectbyid($citemcd);
			$csptno = '';
			foreach($rsitem as $r){
				$csptno = $r->MITM_SPTNO;
			}
			$myar[] = ["cd" => $ttlrows, "msg" => "Go ahead" , "ref" => $csptno];			
			echo '{"data":'
			.json_encode($myar)
			.'}';
		} else {
			$myar[] = ["cd" => 0, "msg" => "Data not found in PSN"];			
			echo '{"data":'
			.json_encode($myar)
			.'}';
		}
	}

	function checkPSN_itmret_nofr(){
		header('Content-Type: application/json');
		$cpsn = $this->input->get('inpsn');
		$ccat = $this->input->get('incat');
		$cline = $this->input->get('inline');
		
		$citemcd = $this->input->get('incode');		
		$myar = [];
		$datac = ['SPL_DOC' => $cpsn, 'SPL_LINE' => $cline,	'SPL_CAT' => $ccat, 'SPL_ITMCD' => $citemcd ];
		$ttlrows = $this->SPL_mod->check_Primary($datac);
		if($ttlrows>0){
			$rsitem = $this->MSTITM_mod->selectbyid($citemcd);
			$rsrack = $this->SPL_mod->select_where(["SPL_RACKNO"],$datac);
			$csptno = '';
			$crackno = '';
			foreach($rsitem as $r){
				$csptno = $r->MITM_SPTNO;
			}
			foreach($rsrack as $r){
				$crackno = $r['SPL_RACKNO'];break;
			}
			$myar[] = ["cd" => $ttlrows, "msg" => "Go ahead" , "ref" => $csptno, "rackno" => $crackno];
			echo '{"data":'
			.json_encode($myar)
			.'}';
		} else {
			$myar[] = ["cd" => 0, "msg" => "Data not found in PSN"];
			echo '{"data":'
			.json_encode($myar)
			.'}';
		}
	}

	public function checkSession(){
		$myar =[];
		if ($this->session->userdata('status') != "login")
        {
			$myar[] = ["cd" => "0", "msg" => "Session is expired please reload page"];			
			exit(json_encode($myar));
        }
	}

	function scn_set(){
		date_default_timezone_set('Asia/Jakarta');
		$currdate = date('Ymd');
		$currrtime = date('Y-m-d H:i:s');

		$cpsn 	= $this->input->post('inpsn');
		$ccat 	= $this->input->post('incat');
		$cline 	= $this->input->post('inline');
		$cfr 	= $this->input->post('infr');		
		$citm 	= $this->input->post('incode');
		$cqty 	= $this->input->post('inqty');
		$clot 	= $this->input->post('inlot');		
		$corder = $this->input->post('inorder');
		$datac  = ['SPL_DOC' => $cpsn, 'SPL_LINE' => $cline, 'SPL_CAT' => $ccat, 'SPL_FEDR' => $cfr, 'SPL_ITMCD' => $citm];
		if($this->SPL_mod->check_Primary($datac)>0){
			echo "1";							
			$mlastid = $this->SPLSCN_mod->lastserialid();
			$mlastid++;
			$newid = $currdate.$mlastid;
			if ($corder=='-'){
				//check if scanned qty is more than balance value			
				$rs = $this->SPL_mod->selectscan_balancing($cpsn, $ccat, $cline, $cfr, $citm);
				if(count($rs)>0){
					echo "1";
					$datas = array();
					foreach($rs as $r){
						$datas['SPLSCN_ID'] = $newid;
						$datas['SPLSCN_DOC'] = $cpsn;
						$datas['SPLSCN_CAT'] = $ccat;
						$datas['SPLSCN_LINE'] = $cline;
						$datas['SPLSCN_FEDR'] = $cfr;
						$datas['SPLSCN_ORDERNO'] = $r['SPL_ORDERNO'];
						$datas['SPLSCN_ITMCD'] = $citm;
						$datas['SPLSCN_LOTNO'] = trim($clot);
						$datas['SPLSCN_QTY'] = $cqty;
						$datas['SPLSCN_LUPDT'] = $currrtime;
						$datas['SPLSCN_USRID'] = $this->session->userdata('nama');
					}
					$toret = $this->SPLSCN_mod->insert($datas);
					if($toret>0){
						echo 1;
					} else {
						echo 0;
					}
				} else {
					echo 0;
				}
			} else {
				$datas = array();				
				$datas['SPLSCN_ID'] = $newid;
				$datas['SPLSCN_DOC'] = $cpsn;
				$datas['SPLSCN_CAT'] = $ccat;
				$datas['SPLSCN_LINE'] = $cline;
				$datas['SPLSCN_FEDR'] = $cfr;
				$datas['SPLSCN_ORDERNO'] = $corder;
				$datas['SPLSCN_ITMCD'] = $citm;
				$datas['SPLSCN_LOTNO'] = trim($clot);
				$datas['SPLSCN_QTY'] = $cqty;
				$datas['SPLSCN_LUPDT'] = $currrtime;
				$datas['SPLSCN_USRID'] = $this->session->userdata('nama');
				
				$toret = $this->SPLSCN_mod->insert($datas);
				if($toret>0){
					echo 1;
				} else {
					echo 0;
				}
			}									
		} else {
			echo "0";
		}
	}
	

	public function getdetailissue(){
		header('Content-Type: application/json');
		$cpsn 	= $this->input->get('inpsn');
		$ccat 	= $this->input->get('incat');
		$cline 	= $this->input->get('inline');
		$cfr 	= $this->input->get('infr');
		$citm 	= $this->input->get('incode');
		$curut 	= $this->input->get('inurut');
		$dataw = ['SPLSCN_DOC' => $cpsn, 'SPLSCN_CAT' => $ccat, 'SPLSCN_LINE' => $cline, 'SPLSCN_FEDR' => $cfr, 'SPLSCN_ITMCD' => $citm, 'SPLSCN_ORDERNO' => $curut];
		$rs = $this->SPLSCN_mod->selectby_filter($dataw);
		echo '{"data":'
		.json_encode($rs)
		.'}';
	}

	public function save(){
		if(!$this->session->userdata('status')){			
			exit('could not save, because session is expired');
		}
		date_default_timezone_set('Asia/Jakarta');
		$currdate = date('Y-m-d');		
		$cpsn 	= trim($this->input->post('inpsn'));
		$ccat 	= trim($this->input->post('incat'));
		$cline 	= trim($this->input->post('inline'));
		$cfr 	= trim($this->input->post('infr'));
		$cdoc = $cpsn."|".$ccat."|".$cline."|".$cfr;
		//$rs = $this->SPLSCN_mod->selecttocompareith($cdoc);
		$cwh_out = $_COOKIE["CKPSI_WH"];
		$cwh_inc = '';
		$flag_insert = 0;
		$flag_update = 0;
		$rsbg = $this->SPL_mod->select_bg_partreq([$cpsn]);
		foreach($rsbg as $r){
			switch($r['PPSN1_BSGRP']){
				case 'PSI1PPZIEP':
					$cwh_inc = 'ARWH1';
					$cwh_out = 'PLANT1';
					break;
				case 'PSI2PPZADI':
					$cwh_inc = 'ARWH2';
					$cwh_out = 'PLANT2';
					break;
				case 'PSI2PPZINS':
					$cwh_inc = 'NRWH2';
					$cwh_out = 'PLANT_NA';
					break;
				case 'PSI2PPZOMC':
					$cwh_inc = 'NRWH2';
					$cwh_out = 'PLANT_NA';
					break;
				case 'PSI2PPZOMI':
					$cwh_inc = 'ARWH2';
					$cwh_out = 'PLANT2';
					break;
				case 'PSI2PPZSSI':
					$cwh_inc = 'NRWH2';
					$cwh_out = 'PLANT_NA';
					break;
				case 'PSI2PPZSTY':
					$cwh_inc = 'ARWH2';
					$cwh_out = 'PLANT2';
					break;
				case 'PSI2PPZTDI':
					$cwh_inc = 'ARWH2';
					$cwh_out = 'PLANT2';
					break;
			}
			break;
		}
		$rsunsaved = $this->SPLSCN_mod->selectunsaved($cpsn, $ccat, $cline, $cfr);
		foreach($rsunsaved as $r){			
			$toret = $this->SPLSCN_mod->update_setsaved($r['SPLSCN_ID']);
			$flag_update+=$toret;
			if($toret >0 ){				
				$datas = [
						'ITH_ITMCD' => $r['SPLSCN_ITMCD'], 'ITH_WH' =>  $cwh_inc , 
						'ITH_DOC' 	=> $cdoc, 'ITH_DATE' => $currdate,
						'ITH_FORM' 	=> 'OUT-WH-RM', 'ITH_QTY' => -$r['SPLSCN_QTY'], 
						'ITH_REMARK' => $r['SPLSCN_LOTNO'],
						'ITH_USRID' =>  $this->session->userdata('nama')
				];
				$rsbook = $this->SPLBOOK_mod->select_book_like(['SPLBOOK_SPLDOC' => $cpsn, 'SPLBOOK_ITMCD' => $r['SPLSCN_ITMCD']]);
				if(count($rsbook)){
					$thebookID = '';
					foreach($rsbook as $n){
						$thebookID = $n['SPLBOOK_DOC'];
						break;
					}
					$datab = [
						'ITH_ITMCD' => $r['SPLSCN_ITMCD'], 'ITH_WH' =>  $cwh_inc , 
						'ITH_DOC' 	=> $thebookID, 'ITH_DATE' => $currdate,
						'ITH_FORM' 	=> 'BOOK-SPL-2', 'ITH_QTY' => $r['SPLSCN_QTY'], 
						'ITH_REMARK' => $r['SPLSCN_LOTNO'],
						'ITH_USRID' =>  $this->session->userdata('nama')
					];
					$this->ITH_mod->insert_spl($datab);
				}
				$tor = $this->ITH_mod->insert_spl($datas);				
				
				$datas 	= [
					'ITH_ITMCD' => $r['SPLSCN_ITMCD'], 'ITH_WH' =>  $cwh_out , 
					'ITH_DOC' 	=> $cdoc, 'ITH_DATE' => $currdate,
					'ITH_FORM' 	=> 'INC-PRD-RM', 'ITH_QTY' => $r['SPLSCN_QTY'], 
					'ITH_REMARK' => $r['SPLSCN_LOTNO'],
					'ITH_USRID' =>  $this->session->userdata('nama')
				];
				$tor = $this->ITH_mod->insert_spl($datas);
				$flag_insert+=$tor;
			}
		}
		echo "Saved ($flag_insert)  and updated ($flag_update)";
	}

	function removeunsaved(){
		$cid = $this->input->get('inid');
		$dataw = array('SPLSCN_ID' => $cid, "COALESCE(SPLSCN_SAVED,'') !=" => "1");
		$toret = $this->SPLSCN_mod->deleteby_filter($dataw);
		if($toret>0){
			echo "Delete successfully";			
		} else {
			echo "We could not delete it";
		}
	}

	public function remove(){
		header('Content-Type: application/json');
		$spl = $this->input->post('spl');
		$category = $this->input->post('category');
		$line = $this->input->post('line');
		$fr = $this->input->post('fr');
		$itemcd = $this->input->post('itemcd');
		$qty = $this->input->post('qty');
		$tbl = $this->input->post('tbl');
		$data = ['SPL' => $spl, 'category' => $category, 'line' => $line, 'fr' => $fr
			, 'itemcd' => $itemcd, 'qty' => $qty, 'tbl' => $tbl];
		$myar = [];
		$splscnExist = $this->SPLSCN_mod->check_Primary([
			'SPLSCN_DOC' => $spl, 'SPLSCN_CAT' => $category, 'SPLSCN_LINE' => $line, 'SPLSCN_FEDR' => $fr
			, 'SPLSCN_ORDERNO' => $tbl, 'SPLSCN_ITMCD' => $itemcd
		]);
		if($splscnExist==0){
			$result = $this->SPL_mod->deleteby_filter([
				'SPL_DOC' => $spl, 'SPL_CAT' => $category
				,'SPL_LINE' => $line, 'SPL_FEDR' => $fr
				,'SPL_ORDERNO' => $tbl, 'SPL_ITMCD' => $itemcd
			]);			
			$myar[] = $result ? ['cd' => 1, 'msg' => 'ok'] : ['cd' => 0, 'msg' => 'could not be deleted'];
		} else {
			$myar[] = ['cd' => 0, 'msg' => 'Could not be deleted', 'splscn' => $splscnExist];
		}
		die(json_encode(['status' => $myar, 'data' => $data ]));
	}

	public function tobexported_list(){
		//#next add api from Deny
		header('Content-Type: application/json');
		$cpsn = $this->input->get('inpsn');
		$ccat = $this->input->get('incat');
		$cline = $this->input->get('inline');
		$dataw = ['SPLSCN_DOC' => $cpsn, 'SPLSCN_CAT' => $ccat, 'SPLSCN_LINE' => $cline,'SPLSCN_SAVED' => '1'];
		$filterbase = ['PPSN2_PSNNO' => $cpsn, 'PPSN2_ITMCAT' => $ccat, 'PPSN2_LINENO' => $cline];
		$rsbase = $this->SPL_mod->select_ppsn2($filterbase);
		$rsscn = $this->SPLSCN_mod->selectby_filter_formega_v1($dataw);
		foreach($rsbase as &$d){
			if(!array_key_exists("TTLSCN", $d)){
				$d["TTLSCN"] = 0;
			}
		}
		unset($d);
		foreach($rsscn as &$d){
			if(!array_key_exists("USED", $d)){
				$d["USED"] = false;
			}
		}
		unset($d);
		$rsfix = [];						
		
		#try 1st time
		foreach($rsbase as &$r){			
			$think = true;
			while($think){
				$grasp = false;
				foreach($rsscn as $d){
					if( (trim($r['PPSN2_FR']) == trim($d['SPLSCN_FEDR'])) && ( trim($r['PPSN2_MCZ']) == trim($d['SPLSCN_ORDERNO']) ) && (trim($r['PPSN2_SUBPN']) == trim($d['SPLSCN_ITMCD']) ) && $d['USED']==false){
						$grasp = true; break;
					}
				}
				if($grasp){
					foreach($rsscn as &$d){
						if( ( trim($r['PPSN2_MCZ']) == trim($d['SPLSCN_ORDERNO']) ) && (trim($r['PPSN2_SUBPN']) == trim($d['SPLSCN_ITMCD']) ) && $d['USED']==false and (trim($r['PPSN2_FR']) == trim($d['SPLSCN_FEDR']))){
							$think2 = true;
							while($think2){
								if($r['PPSN2_REQQT'] > $r['TTLSCN']){
									if($d['USED']==false){
										if(count($rsfix)==0){											
											$rsfix[] = [
												"SPLSCN_ID" => trim($d["SPLSCN_ID"])
												,"SPLSCN_USRID" => trim($d["SPLSCN_USRID"])
												,"PPSN2_DATANO" => trim($r["PPSN2_DATANO"])
												,"SPLSCN_FEDR" => trim($d["SPLSCN_FEDR"])
												,"SPLSCN_ITMCD" => trim($d["SPLSCN_ITMCD"])
												,"SPLSCN_QTY" => $d["SPLSCN_QTY"]
												,"SPLSCN_LUPDT" => substr($d['SPLSCN_LUPDT'],0,16)
												,"SPLSCN_LOTNO" => trim($d["SPLSCN_LOTNO"])
												,"SPLSCN_ORDERNO" => trim($d["SPLSCN_ORDERNO"])
												,"SPLSCN_LINE" => trim($d["SPLSCN_LINE"])
												,"PPSN2_MC" => trim($r["PPSN2_MC"])
												,"PPSN2_PROCD" => trim($r["PPSN2_PROCD"])  
												,"ISOK" => 1  
											];
											$r['TTLSCN'] += $d['SPLSCN_QTY'];
											$d['USED'] = true;
										} else {											
											$isfound = false;
											foreach($rsfix as &$t){
												if( ( trim($t["PPSN2_MC"]) == trim($r["PPSN2_MC"]) ) && ( trim($t["SPLSCN_ORDERNO"]) == trim($r["PPSN2_MCZ"]) ) 
												&& ( trim($t["SPLSCN_ITMCD"]) == trim($r["PPSN2_SUBPN"]) ) && ( trim($t["PPSN2_PROCD"]) == trim($r["PPSN2_PROCD"])  )
												 ){
													$r['TTLSCN']+=$d['SPLSCN_QTY'];
													$rsfix[] =
														["SPLSCN_ID" => trim($d["SPLSCN_ID"])
														,"SPLSCN_USRID" => trim($d["SPLSCN_USRID"])
														,"PPSN2_DATANO" => trim($r["PPSN2_DATANO"])
														,"SPLSCN_FEDR" => trim($d["SPLSCN_FEDR"])
														,"SPLSCN_ITMCD" => trim($d["SPLSCN_ITMCD"])
														,"SPLSCN_QTY" => $d["SPLSCN_QTY"]
														,"SPLSCN_LUPDT" => substr($d['SPLSCN_LUPDT'],0,16)
														,"SPLSCN_LOTNO" => trim($d["SPLSCN_LOTNO"])
														,"SPLSCN_ORDERNO" => trim($d["SPLSCN_ORDERNO"])
														,"SPLSCN_LINE" => trim($d["SPLSCN_LINE"])
														,"PPSN2_MC" => trim($r["PPSN2_MC"])
														,"PPSN2_PROCD" => trim($r["PPSN2_PROCD"])
														,"ISOK" => 1
														] ;
													$isfound=true;
													$d['USED'] = true;
												 break;
												}
											}
											unset($t);											
											if(!$isfound){
												$rsfix[] = ["SPLSCN_ID" => trim($d["SPLSCN_ID"])
												,"SPLSCN_USRID" => trim($d["SPLSCN_USRID"])
												,"PPSN2_DATANO" => trim($r["PPSN2_DATANO"])
												,"SPLSCN_FEDR" => trim($d["SPLSCN_FEDR"])
												,"SPLSCN_ITMCD" => trim($d["SPLSCN_ITMCD"])
												,"SPLSCN_QTY" => $d["SPLSCN_QTY"]
												,"SPLSCN_LUPDT" => substr($d['SPLSCN_LUPDT'],0,16)
												,"SPLSCN_LOTNO" => trim($d["SPLSCN_LOTNO"])
												,"SPLSCN_ORDERNO" => trim($d["SPLSCN_ORDERNO"])
												,"SPLSCN_LINE" => trim($d["SPLSCN_LINE"])
												,"PPSN2_MC" => trim($r["PPSN2_MC"])
												,"PPSN2_PROCD" => trim($r["PPSN2_PROCD"])
												,"ISOK" => 1
												];
												$r['TTLSCN'] += $d['SPLSCN_QTY'];
												$d['USED'] = true;
											}
										}
									} else {
										$think2=false;
									}
								} else {
									$think2=false;
									$think=false;
								}
							}
						}
					}
					unset($d);
				} else {
					$think = false;
				}
			}
		}
		unset($r);
		#end try

		#try 2nd time
		foreach($rsbase as &$r){
			$think = true;
			while($think){
				$grasp = false;
				foreach($rsscn as $d){
					if( (trim($r['PPSN2_FR']) == trim($d['SPLSCN_FEDR'])) && ( trim($r['PPSN2_MCZ']) == trim($d['SPLSCN_ORDERNO']) ) && (trim($r['PPSN2_SUBPN']) == trim($d['SPLSCN_ITMCD']) ) && $d['USED']==false){
						$grasp = true; break;
					}
				}
				if($grasp){
					foreach($rsscn as &$d){
						if( ( trim($r['PPSN2_MCZ']) == trim($d['SPLSCN_ORDERNO']) ) && (trim($r['PPSN2_SUBPN']) == trim($d['SPLSCN_ITMCD']) ) && $d['USED']==false and (trim($r['PPSN2_FR']) == trim($d['SPLSCN_FEDR']))){
							$think2 = true;
							while($think2){								
								if($d['USED']==false){
									if(count($rsfix)==0){											
										$rsfix[] = [
											"SPLSCN_ID" => trim($d["SPLSCN_ID"])
											,"SPLSCN_USRID" => trim($d["SPLSCN_USRID"])
											,"PPSN2_DATANO" => trim($r["PPSN2_DATANO"])
											,"SPLSCN_FEDR" => trim($d["SPLSCN_FEDR"])
											,"SPLSCN_ITMCD" => trim($d["SPLSCN_ITMCD"])
											,"SPLSCN_QTY" => $d["SPLSCN_QTY"]
											,"SPLSCN_LUPDT" => substr($d['SPLSCN_LUPDT'],0,16)
											,"SPLSCN_LOTNO" => trim($d["SPLSCN_LOTNO"])
											,"SPLSCN_ORDERNO" => trim($d["SPLSCN_ORDERNO"])
											,"SPLSCN_LINE" => trim($d["SPLSCN_LINE"])
											,"PPSN2_MC" => trim($r["PPSN2_MC"])
											,"PPSN2_PROCD" => trim($r["PPSN2_PROCD"])  
											,"ISOK" => 1  
										];
										$r['TTLSCN'] += $d['SPLSCN_QTY'];
										$d['USED'] = true;
									} else {											
										$isfound = false;
										foreach($rsfix as &$t){
											if( ( trim($t["PPSN2_MC"]) == trim($r["PPSN2_MC"]) ) && ( trim($t["SPLSCN_ORDERNO"]) == trim($r["PPSN2_MCZ"]) ) 
											&& ( trim($t["SPLSCN_ITMCD"]) == trim($r["PPSN2_SUBPN"]) ) && ( trim($t["PPSN2_PROCD"]) == trim($r["PPSN2_PROCD"])  )
												){
												$r['TTLSCN']+=$d['SPLSCN_QTY'];
												$rsfix[] =
													["SPLSCN_ID" => trim($d["SPLSCN_ID"])
													,"SPLSCN_USRID" => trim($d["SPLSCN_USRID"])
													,"PPSN2_DATANO" => trim($r["PPSN2_DATANO"])
													,"SPLSCN_FEDR" => trim($d["SPLSCN_FEDR"])
													,"SPLSCN_ITMCD" => trim($d["SPLSCN_ITMCD"])
													,"SPLSCN_QTY" => $d["SPLSCN_QTY"]
													,"SPLSCN_LUPDT" => substr($d['SPLSCN_LUPDT'],0,16)
													,"SPLSCN_LOTNO" => trim($d["SPLSCN_LOTNO"])
													,"SPLSCN_ORDERNO" => trim($d["SPLSCN_ORDERNO"])
													,"SPLSCN_LINE" => trim($d["SPLSCN_LINE"])
													,"PPSN2_MC" => trim($r["PPSN2_MC"])
													,"PPSN2_PROCD" => trim($r["PPSN2_PROCD"])
													,"ISOK" => 1
													] ;
												$isfound=true;
												$d['USED'] = true;
												break;
											}
										}
										unset($t);											
										if(!$isfound){
											$rsfix[] = ["SPLSCN_ID" => trim($d["SPLSCN_ID"])
											,"SPLSCN_USRID" => trim($d["SPLSCN_USRID"])
											,"PPSN2_DATANO" => trim($r["PPSN2_DATANO"])
											,"SPLSCN_FEDR" => trim($d["SPLSCN_FEDR"])
											,"SPLSCN_ITMCD" => trim($d["SPLSCN_ITMCD"])
											,"SPLSCN_QTY" => $d["SPLSCN_QTY"]
											,"SPLSCN_LUPDT" => substr($d['SPLSCN_LUPDT'],0,16)
											,"SPLSCN_LOTNO" => trim($d["SPLSCN_LOTNO"])
											,"SPLSCN_ORDERNO" => trim($d["SPLSCN_ORDERNO"])
											,"SPLSCN_LINE" => trim($d["SPLSCN_LINE"])
											,"PPSN2_MC" => trim($r["PPSN2_MC"])
											,"PPSN2_PROCD" => trim($r["PPSN2_PROCD"])
											,"ISOK" => 1
											];
											$r['TTLSCN'] += $d['SPLSCN_QTY'];
											$d['USED'] = true;
										}
									}
								} else {
									$think2=false;
								}
							}
						}
					}
					unset($d);
				} else {
					$think = false;
				}
			}
		}
		unset($r);
		#end try
		
		#try to check is already uploaded v2
		$itemdistinct = [];
		$frdistinct = [];
		foreach($rsfix as &$d){
			foreach($rsbase as &$r){
				if($r['PPSN2_DATANO'] == $d['PPSN2_DATANO']){					
					if($d['SPLSCN_QTY'] == $r['PPSN2_PACKSZ1'] &&  $r['PPSN2_PICKQT1'] >0){						
						$d['ISOK'] = 0;
						$r['PPSN2_PICKQT1']-=1;
						break;
					} elseif($d['SPLSCN_QTY'] == $r['PPSN2_PACKSZ2'] &&  $r['PPSN2_PICKQT2'] >0){
						$d['ISOK'] = 0;
						$r['PPSN2_PICKQT2']-=1;
						break;
					} elseif($d['SPLSCN_QTY'] == $r['PPSN2_PACKSZ3'] &&  $r['PPSN2_PICKQT3'] >0){
						$d['ISOK'] = 0;
						$r['PPSN2_PICKQT3']-=1;
						break;
					} elseif($d['SPLSCN_QTY'] == $r['PPSN2_PACKSZ4'] &&  $r['PPSN2_PICKQT4'] >0){
						$d['ISOK'] = 0;
						$r['PPSN2_PICKQT4']-=1;
						break;
					} elseif($d['SPLSCN_QTY'] == $r['PPSN2_PACKSZ5'] &&  $r['PPSN2_PICKQT5'] >0){
						$d['ISOK'] = 0;
						$r['PPSN2_PICKQT5']-=1;
						break;
					} elseif($d['SPLSCN_QTY'] == $r['PPSN2_PACKSZ6'] &&  $r['PPSN2_PICKQT6'] >0){
						$d['ISOK'] = 0;
						$r['PPSN2_PICKQT6']-=1;
						break;
					} elseif($d['SPLSCN_QTY'] == $r['PPSN2_PACKSZ7'] &&  $r['PPSN2_PICKQT7'] >0){
						$d['ISOK'] = 0;
						$r['PPSN2_PICKQT7']-=1;
						break;
					} elseif($d['SPLSCN_QTY'] == $r['PPSN2_PACKSZ8'] &&  $r['PPSN2_PICKQT8'] >0){
						$d['ISOK'] = 0;
						$r['PPSN2_PICKQT8']-=1;
						break;
					}

				}
			}
			unset($r);
			
		}
		unset($d);

		$rsfinal = [];
		
		foreach($rsfix as $r){
			if($r['ISOK'] == 1){
				$rsfinal[] = $r;
				if(!in_array($r['SPLSCN_ITMCD'], $itemdistinct)) $itemdistinct[] = $r['SPLSCN_ITMCD'];
				if(!in_array($r['SPLSCN_FEDR'], $frdistinct)) $frdistinct[] = $r['SPLSCN_FEDR'];
			}
		}
		foreach($frdistinct as $n){
			// $this->gotoque($itemdistinct, $cpsn, $ccat, $cline, $n );
		}
		
		
		$myar = count($rsfinal)>0 ?  ["cd" => "1", "msg" => "Go ahead"] : ["cd" => "0", "msg" => "data not found"];		
		echo '{"data":'
		.json_encode($rsfinal)
		.',"ppsn2" : '.json_encode($rsbase)
		.',"scanned" : '.json_encode($rsscn)
		.',"status" : '.json_encode($myar)
		.',"dataku" : '.json_encode($itemdistinct)
		.',"dataku2" : '.json_encode($frdistinct)
		.'}';
	}

	public function export_to_spreadsheet(){
		if(!isset($_COOKIE["CKPSI_DPSN"]) && !isset($_COOKIE["CKPSI_DCAT"]) && !isset($_COOKIE["CKPSI_DLNE"]) && !isset($_COOKIE["CKPSI_DFDR"]) ){
			exit('no data to be found');
		}
		$cpsn = $_COOKIE["CKPSI_DPSN"];
		$ccat = $_COOKIE["CKPSI_DCAT"];
		$cline = $_COOKIE["CKPSI_DLNE"];	
		$dataw = [
			'SPLSCN_DOC' => $cpsn, 'SPLSCN_CAT' => $ccat, 'SPLSCN_LINE' => $cline,
			'SPLSCN_SAVED' => '1'
		];
		$filterbase = ['PPSN2_PSNNO' => $cpsn, 'PPSN2_ITMCAT' => $ccat, 'PPSN2_LINENO' => $cline];
		$rsbase = $this->SPL_mod->select_ppsn2($filterbase);
		$rsscn = $this->SPLSCN_mod->selectby_filter_formega_v1($dataw);
		foreach($rsbase as &$d){
			if(!array_key_exists("TTLSCN", $d)){
				$d["TTLSCN"] = 0;
			}
		}
		unset($d);
		foreach($rsscn as &$d){
			if(!array_key_exists("USED", $d)){
				$d["USED"] = false;
			}
		}
		unset($d);
		$rsfix = [];
		foreach($rsbase as &$r){
			$think = true;
			while($think){
				$grasp = false;
				foreach($rsscn as $d){
					if( (trim($r['PPSN2_FR']) == trim($d['SPLSCN_FEDR'])) && ( trim($r['PPSN2_MCZ']) == trim($d['SPLSCN_ORDERNO']) ) && (trim($r['PPSN2_SUBPN']) == trim($d['SPLSCN_ITMCD']) ) && $d['USED']==false){
						$grasp = true; break;
					}
				}
				if($grasp){
					foreach($rsscn as &$d){
						if( ( trim($r['PPSN2_MCZ']) == trim($d['SPLSCN_ORDERNO']) ) && (trim($r['PPSN2_SUBPN']) == trim($d['SPLSCN_ITMCD']) ) && $d['USED']==false && (trim($r['PPSN2_FR']) == trim($d['SPLSCN_FEDR']))){
							$think2 = true;
							while($think2){
								if($r['PPSN2_REQQT'] > $r['TTLSCN']){
									if($d['USED']==false){
										if(count($rsfix)==0){											
											array_push($rsfix, 
												array("SPLSCN_ID" => trim($d["SPLSCN_ID"]), "SPLSCN_USRID" => trim($d["SPLSCN_USRID"]),
												"PPSN2_DATANO" => trim($r["PPSN2_DATANO"]), "SPLSCN_FEDR" => trim($d["SPLSCN_FEDR"]),
												"SPLSCN_ITMCD" => trim($d["SPLSCN_ITMCD"]), "SPLSCN_QTY" => $d["SPLSCN_QTY"],
												"SPLSCN_LUPDT" => substr($d['SPLSCN_LUPDT'],0,16), "SPLSCN_LOTNO" => trim($d["SPLSCN_LOTNO"]),
												"SPLSCN_ORDERNO" => trim($d["SPLSCN_ORDERNO"]), "SPLSCN_LINE" => trim($d["SPLSCN_LINE"]),
												"PPSN2_MC" => trim($r["PPSN2_MC"]), "PPSN2_PROCD" => trim($r["PPSN2_PROCD"])  ) 
											);
											$r['TTLSCN'] += $d['SPLSCN_QTY'];
											$d['USED'] = true;
										} else {											
											$isfound = false;
											foreach($rsfix as &$t){
												if( ( trim($t["PPSN2_MC"]) ==trim($r["PPSN2_MC"]) ) && ( trim($t["SPLSCN_ORDERNO"]) == trim($r["PPSN2_MCZ"]) ) 
												&& ( trim($t["SPLSCN_ITMCD"]) == trim($r["PPSN2_SUBPN"]) ) && ( trim($t["PPSN2_PROCD"]) == trim($r["PPSN2_PROCD"])  )
												 ){
													$r['TTLSCN']+=$d['SPLSCN_QTY'];
													array_push($rsfix, 
														array("SPLSCN_ID" => trim($d["SPLSCN_ID"]), "SPLSCN_USRID" => trim($d["SPLSCN_USRID"]),
														"PPSN2_DATANO" => trim($r["PPSN2_DATANO"]), "SPLSCN_FEDR" => trim($d["SPLSCN_FEDR"]),
														"SPLSCN_ITMCD" => trim($d["SPLSCN_ITMCD"]), "SPLSCN_QTY" => $d["SPLSCN_QTY"],
														"SPLSCN_LUPDT" => substr($d['SPLSCN_LUPDT'],0,16), "SPLSCN_LOTNO" => trim($d["SPLSCN_LOTNO"]),
														"SPLSCN_ORDERNO" => trim($d["SPLSCN_ORDERNO"]), "SPLSCN_LINE" => trim($d["SPLSCN_LINE"]),
														"PPSN2_MC" => trim($r["PPSN2_MC"]), "PPSN2_PROCD" => trim($r["PPSN2_PROCD"])  ) 
													);
													$isfound=true;
													$d['USED'] = true;
												 break;
												}
											}
											unset($t);											
											if(!$isfound){
												array_push($rsfix, 
													array("SPLSCN_ID" => trim($d["SPLSCN_ID"]), "SPLSCN_USRID" => trim($d["SPLSCN_USRID"]),
													"PPSN2_DATANO" => trim($r["PPSN2_DATANO"]), "SPLSCN_FEDR" => trim($d["SPLSCN_FEDR"]),
													"SPLSCN_ITMCD" => trim($d["SPLSCN_ITMCD"]), "SPLSCN_QTY" => $d["SPLSCN_QTY"],
													"SPLSCN_LUPDT" => substr($d['SPLSCN_LUPDT'],0,16), "SPLSCN_LOTNO" => trim($d["SPLSCN_LOTNO"]),
													"SPLSCN_ORDERNO" => trim($d["SPLSCN_ORDERNO"]), "SPLSCN_LINE" => trim($d["SPLSCN_LINE"]),
													"PPSN2_MC" => trim($r["PPSN2_MC"]), "PPSN2_PROCD" => trim($r["PPSN2_PROCD"])  ) 
												);
												$r['TTLSCN'] += $d['SPLSCN_QTY'];
												$d['USED'] = true;												
											}
										}
									} else {
										$think2=false;
									}
								} else {
									$think2=false;
									$think=false;
								}								
							}							
						}
					}
					unset($d);
				} else {
					$think = false;
				}
			}
		}
		unset($r);

		//$rs=$this->SPLSCN_mod->selectby_filter_formega($dataw);


		$stringjudul = 'RESULT_'.$cpsn;
		$spreadsheet = new Spreadsheet();
		$sheet = $spreadsheet->getActiveSheet();
		$sheet->setTitle('PSN');
		$no=1;
		foreach($rsfix as $r){
			$sheet->setCellValueByColumnAndRow(1,$no, strval($r['SPLSCN_ID']));
			$sheet->setCellValueByColumnAndRow(2,$no, strval($r['SPLSCN_USRID']));
			$sheet->setCellValueByColumnAndRow(3,$no, '');
			$sheet->setCellValueByColumnAndRow(4,$no, strval($r['PPSN2_DATANO']));
			$sheet->setCellValueByColumnAndRow(5,$no, $cpsn);
			$sheet->setCellValueByColumnAndRow(6,$no, trim($ccat));
			$sheet->setCellValueByColumnAndRow(7,$no, $r['SPLSCN_FEDR']);
			$sheet->setCellValueByColumnAndRow(8,$no, $r['SPLSCN_ITMCD']);
			$sheet->setCellValueByColumnAndRow(9,$no, $r['SPLSCN_QTY']);
			$sheet->setCellValueByColumnAndRow(10,$no, substr($r['SPLSCN_LUPDT'],0,16));
			$sheet->setCellValueByColumnAndRow(11,$no, "OK");
			$sheet->setCellValueByColumnAndRow(12,$no, '1');
			$sheet->setCellValueByColumnAndRow(13,$no, $r['SPLSCN_LOTNO']);
			$sheet->setCellValueByColumnAndRow(14,$no, $r['SPLSCN_ORDERNO']);
			$sheet->setCellValueByColumnAndRow(15,$no, $r['SPLSCN_LINE']);
			$no++;
		}
		$this->SPLSCN_mod->update_exported($cpsn, $ccat, $cline);		

		$writer = new Csv($spreadsheet);
		$writer->setDelimiter(',');
		$writer->setEnclosure('');
		$writer->setLineEnding("\r\n");
		$writer->setSheetIndex(0);
		$filename=$stringjudul; //save our workbook as this file name
		
		header('Content-Type: text/csv');
		header('Content-Disposition: attachment;filename="'. $filename .'.csv"'); 
		header('Cache-Control: max-age=0');
		$writer->save('php://output');
	}

	public function printkit_all(){
		if(!isset($_COOKIE["CKPSI_DPSN"])  ){
			exit('no data to be found');
		}
		$cpsn = $_COOKIE["CKPSI_DPSN"];
		$rspsn_group = $this->SPL_mod->select_header_psn($cpsn);		
		$pdf = new PDF_Code39e128('P','mm','A4');
		if(substr($cpsn,0,2)=='SP'){
			$rshead = $this->SPL_mod->select_pi_head_bypsn($cpsn);
			$rsdiff_mch = $this->SPL_mod->select_but_diff_machine_bypsn($cpsn);
			$cwos = [];
			$cmodels = [];
			$clotsize = [];
			foreach($rshead as $r){
				if(count($cwos)==0){
					$cwos[] = trim($r['PPSN1_WONO']);
					$cmodels[] = trim($r['MITM_ITMD1']);
					$clotsize[] = trim($r['PPSN1_SIMQT']);
				} else {
					$ttlwo = count($cwos);
					$isexist = false;
					for($i=0;$i<$ttlwo;$i++){
						if($cwos[$i]==trim($r['PPSN1_WONO'])){
							$isexist =true;
							break;
						}
					}
					if(!$isexist){
						$cwos[] = trim($r['PPSN1_WONO']);
						$cmodels[] = trim($r['MITM_ITMD1']);
						$clotsize[] = trim($r['PPSN1_SIMQT']);
					}
				}
			}
			$rs = $this->SPL_mod->selectkitbyPSN($cpsn);
			$dataw = ['SPLSCN_DOC' => $cpsn];
			$rsdetail = $this->SPLSCN_mod->selectby_filter($dataw);
			foreach($rsdetail as &$d){
				if(!array_key_exists("USED", $d)){
					$d["USED"] = false;
				}
			}
			unset($d);
			

			foreach($rs as &$r){
				$think = true;
				while($think){
					$grasp = false;
					foreach($rsdetail as $d){
						if( ( trim($r['SPL_ORDERNO']) == trim($d['SPLSCN_ORDERNO']) ) && (trim($r['SPL_ITMCD']) == trim($d['SPLSCN_ITMCD']) ) && $d['USED']==false){
							$grasp = true; break;
						}
					}
					if($grasp){
						foreach($rsdetail as &$d){
							if( ( trim($r['SPL_ORDERNO']) == trim($d['SPLSCN_ORDERNO']) ) && (trim($r['SPL_ITMCD']) == trim($d['SPLSCN_ITMCD']) ) && $d['USED']==false){
								$think2 = true;
								while($think2){
									if($r['TTLREQ'] > $r['TTLSCN']){
										if($d['USED']==false){
											$r['TTLSCN'] += $d['SPLSCN_QTY'];
											$d['USED'] = true;
										} else {
											$think2=false;
										}
									} else {
										$think2=false;
										$think=false;
									}
								}
							}
						}
						unset($d);
					} else {
						$think=false;
					}
				}
			}
			unset($r);

			foreach($rs as &$r){
				$r['TTLREQ'] -= $r['TTLSCN'];
				foreach($rsdiff_mch as $k) {
					if( trim($r['SPL_ORDERNO'])==trim($k['SPL_ORDERNO']) && trim($r['SPL_ITMCD']) == trim($k['SPL_ITMCD']) ) {
						$r['SPL_ITMCD']= trim($r['SPL_ITMCD']).  ' *';
					}
				}
			}
			unset($r);
	
			$pdf->AliasNbPages();
			$pdf->AddPage();
			$hgt_p = $pdf->GetPageHeight();				
			$pdf->SetAutoPageBreak(true,1);
			$pdf->SetMargins(0,0);
			$pdf->SetFont('Arial','',6);
			$strheader = ''; #format : CATEGORY|LINE|FRONTREAR
			$cury = 4;
			$td_h=7;
			$xWOcount = count($cwos);			
			$firstPage = false;
			foreach($rs as $r) {
				#Print Outstanding QTY Only
				if($r['TTLREQ']>0){
					$ccat = $r['SPL_CAT'];
					$cline = $r['SPL_LINE'];
					$cfedr = $r['SPL_FEDR'];
					#print header
					if($strheader!=$r['SPL_CAT']."|".$r['SPL_LINE']."|".$r['SPL_FEDR']) {
						if(($cury+10)>$hgt_p){
							$cury = 4;
							$pdf->AddPage();
						} else {
							if(!$firstPage) {
								$cury = 4;
								$firstPage=true;
							} else {
								$cury += 4;
							}
						}
						$strheader=$r['SPL_CAT']."|".$r['SPL_LINE']."|".$r['SPL_FEDR'];
						$pdf->SetFont('Arial','',6);
						$clebar = $pdf->GetStringWidth($cpsn)+40;
						$pdf->Code128(3,$cury,$cpsn, $clebar,4);		
						$pdf->Text(3,$cury+7,$cpsn);
						$clebar = $pdf->GetStringWidth($ccat)+17;
						$pdf->Code128(170, $cury,trim($ccat),$clebar,4);						
						$pdf->Text(170,$cury+7,$ccat);
						$clebar = $pdf->GetStringWidth($cline)+17;
						$pdf->Code128(3, $cury+9,$cline, $clebar, 4);
						$pdf->Text(3,$cury+16,$cline);
						$clebar = $pdf->GetStringWidth($cfedr)+17;
						$pdf->Code128(170, $cury+9,$cfedr, $clebar, 4);
						$pdf->Text(170,$cury+16,$cfedr);
						$pdf->SetXY(90,$cury);
						$pdf->SetFont('Arial','BU',10);
						$pdf->Cell(35,4,'Picking Instruction',0,0,'C');
						$pdf->SetXY(100,$cury+11);
						$pdf->SetFont('Arial','',6);
						$pdf->Cell(15,4,'Page '.$pdf->PageNo().' / {nb}',1,0,'R');
						$pdf->SetFont('Arial','B',7);					
						$cury=$cury+18;
						$isleft = true;
						for($j=0;$j<$xWOcount; $j++){
							if( ($j % 2)==0){
								$pdf->SetXY(3,$cury);
								$pdf->Cell(50,4,'Model',1,0,'L');
								$pdf->Cell(40,4,'Job',1,0,'L');
								$pdf->Cell(10,4,'Lot Size',1,0,'C');
								$pdf->SetXY(3,$cury+4);
								$ttlwidth = $pdf->GetStringWidth($cmodels[$j]);
								if($ttlwidth > 50){
									$ukuranfont = 6.5;
									while($ttlwidth>50){
										$pdf->SetFont('Arial','',$ukuranfont);
										$ttlwidth=$pdf->GetStringWidth($cmodels[$j]);
										$ukuranfont = $ukuranfont - 0.5;
									}
								}
								$pdf->Cell(50,4,$cmodels[$j],1,0,'L');
								$pdf->SetFont('Arial','B',7);
								$pdf->Cell(40,4,$cwos[$j],1,0,'L');
								$pdf->Cell(10,4,number_format($clotsize[$j]),1,0,'L');
								$isleft = true;
							} else {
								$pdf->SetXY(105,$cury);
								$pdf->Cell(50,4,'Model',1,0,'L');
								$pdf->Cell(40,4,'Job',1,0,'L');
								$pdf->Cell(10,4,'Lot Size',1,0,'C');
								$pdf->SetXY(105,$cury+4);
								$ttlwidth = $pdf->GetStringWidth($cmodels[$j]);
								if($ttlwidth > 50){
									$ukuranfont = 6.5;
									while($ttlwidth>50){
										$pdf->SetFont('Arial','',$ukuranfont);
										$ttlwidth=$pdf->GetStringWidth($cmodels[$j]);
										$ukuranfont = $ukuranfont - 0.5;
									}
								}
								$pdf->Cell(50,4,$cmodels[$j],1,0,'L');
								$pdf->SetFont('Arial','B',7);
								$pdf->Cell(40,4,$cwos[$j],1,0,'L');
								$pdf->Cell(10,4,number_format($clotsize[$j]),1,0,'L');
								$cury+=8;
								$isleft=false;
							}
						}
	
						$cury+=$isleft ? 9:1;
						$pdf->SetXY(3,$cury);
						$pdf->Cell(20,4,'No Rak',1,0,'L');
						$pdf->Cell(80,4,'Machine No',1,0,'C');		
						$pdf->Cell(25,4,'Part No',1,0,'L');
						$pdf->Cell(30,4,'Part Name',1,0,'L');	
						$pdf->Cell(8,4,'Use',1,0,'C');
						$pdf->Cell(13,4,'Req.',1,0,'R');
						$pdf->Cell(13,4,'Issued',1,0,'R');
						$pdf->Cell(13,4,'Remain',1,0,'R');
						$wd2col = 3+20+80;
						$cury+=4;
					} else {
						if(($cury+10)>$hgt_p){
							$cury = 4;
							$pdf->AddPage();
							$pdf->SetFont('Arial','',6);
							$clebar = $pdf->GetStringWidth($cpsn)+40;
							$pdf->Code128(3,$cury,$cpsn, $clebar,4);		
							$pdf->Text(3,$cury+7,$cpsn);
							$clebar = $pdf->GetStringWidth($ccat)+17;
							$pdf->Code128(170, $cury,trim($ccat),$clebar,4);						
							$pdf->Text(170,$cury+7,$ccat);
							$clebar = $pdf->GetStringWidth($cline)+17;
							$pdf->Code128(3, $cury+9,$cline, $clebar, 4);
							$pdf->Text(3,$cury+16,$cline);
							$clebar = $pdf->GetStringWidth($cfedr)+17;
							$pdf->Code128(170, 13,$cfedr, $clebar, 4);
							$pdf->Text(170,$cury+16,$cfedr);
							$pdf->SetXY(90,$cury);
							$pdf->SetFont('Arial','BU',10);
							$pdf->Cell(35,4,'Picking Instruction',0,0,'C');
							$pdf->SetXY(100,$cury+11);
							$pdf->SetFont('Arial','',6);
							$pdf->Cell(15,4,'Page '.$pdf->PageNo().' / {nb}',1,0,'R');
							$pdf->SetFont('Arial','B',7);					
							$cury=$cury+18;
							$isleft = true;
							for($j=0;$j<$xWOcount; $j++){
								if( ($j % 2)==0){
									$pdf->SetXY(3,$cury);
									$pdf->Cell(50,4,'Model',1,0,'L');
									$pdf->Cell(40,4,'Job',1,0,'L');
									$pdf->Cell(10,4,'Lot Size',1,0,'C');
									$pdf->SetXY(3,$cury+4);
									$ttlwidth = $pdf->GetStringWidth($cmodels[$j]);
									if($ttlwidth > 50){
										$ukuranfont = 6.5;
										while($ttlwidth>50){
											$pdf->SetFont('Arial','',$ukuranfont);
											$ttlwidth=$pdf->GetStringWidth($cmodels[$j]);
											$ukuranfont = $ukuranfont - 0.5;
										}
									}
									$pdf->Cell(50,4,$cmodels[$j],1,0,'L');
									$pdf->SetFont('Arial','B',7);
									$pdf->Cell(40,4,$cwos[$j],1,0,'L');
									$pdf->Cell(10,4,number_format($clotsize[$j]),1,0,'L');
									$isleft = true;
								} else {
									$pdf->SetXY(105,$cury);
									$pdf->Cell(50,4,'Model',1,0,'L');
									$pdf->Cell(40,4,'Job',1,0,'L');
									$pdf->Cell(10,4,'Lot Size',1,0,'C');
									$pdf->SetXY(105,$cury+4);
									$ttlwidth = $pdf->GetStringWidth($cmodels[$j]);
									if($ttlwidth > 50){
										$ukuranfont = 6.5;
										while($ttlwidth>50){
											$pdf->SetFont('Arial','',$ukuranfont);
											$ttlwidth=$pdf->GetStringWidth($cmodels[$j]);
											$ukuranfont = $ukuranfont - 0.5;
										}
									}
									$pdf->Cell(50,4,$cmodels[$j],1,0,'L');
									$pdf->SetFont('Arial','B',7);
									$pdf->Cell(40,4,$cwos[$j],1,0,'L');
									$pdf->Cell(10,4,number_format($clotsize[$j]),1,0,'L');
									$cury+=8;
									$isleft=false;
								}
							}
		
							$cury+=$isleft ? 9:1;
							$pdf->SetXY(3,$cury);
							$pdf->Cell(20,4,'No Rak',1,0,'L');
							$pdf->Cell(80,4,'Machine No',1,0,'C');		
							$pdf->Cell(25,4,'Part No',1,0,'L');
							$pdf->Cell(30,4,'Part Name',1,0,'L');	
							$pdf->Cell(8,4,'Use',1,0,'C');
							$pdf->Cell(13,4,'Req.',1,0,'R');
							$pdf->Cell(13,4,'Issued',1,0,'R');
							$pdf->Cell(13,4,'Remain',1,0,'R');
							$wd2col = 3+20+80;
							$cury+=4;
						}
					}
					$pdf->SetFont('Arial','',8);
					$pdf->SetXY(3,$cury);
					if(strpos($r['SPL_RACKNO'], '-')!== false){
						$pdf->Cell(20,$td_h,'',1,0,'L');
						$achar = explode('-',$r['SPL_RACKNO']);
						$pdf->Text(3.5, $cury+3,$achar[0]);
						$pdf->Text(3.5, $cury+6,$achar[1]);
					} else {
						$pdf->Cell(20,$td_h,$r['SPL_RACKNO'],1,0,'L');
					}
					$lebar =  $pdf->GetStringWidth(trim($r['SPL_ORDERNO']))+17;
					$clebar =  $pdf->GetStringWidth(trim($r['SPL_ORDERNO']))+16;
					$strx =$wd2col - ($lebar+3);
					if(($i%2)>0){							
						$pdf->Code128($wd2col -80 +2 ,$cury+1.5,trim($r['SPL_ORDERNO']),$clebar,3);
						$pdf->Cell(80,$td_h,$r['SPL_ORDERNO'],1,0,'R');
						$pdf->SetFont('Arial','',4);
						$pdf->Text($wd2col -5, $cury+1.5,$r['SPL_PROCD']);
						$pdf->SetFont('Arial','',8);
					} else {							
						$pdf->Code128($strx,$cury+1.5,trim($r['SPL_ORDERNO']),$clebar,3);
						$pdf->Cell(80,$td_h,$r['SPL_ORDERNO'],1,0,'L');
						$pdf->SetFont('Arial','',4);
						$pdf->Text($wd2col -79, $cury+1.5,$r['SPL_PROCD']);
						$pdf->SetFont('Arial','',8);
					}
					$pdf->Cell(25,$td_h,trim($r['SPL_ITMCD']),1,0,'L');
					$ttlwidth = $pdf->GetStringWidth(trim($r['MITM_SPTNO']));
					if($ttlwidth > 28){
						$ukuranfont = 7.5;
						while($ttlwidth>28){
							$pdf->SetFont('Arial','',$ukuranfont);
							$ttlwidth=$pdf->GetStringWidth(trim($r['MITM_SPTNO']));
							$ukuranfont = $ukuranfont - 0.5;
						}
					}						
					$pdf->Cell(30,$td_h,trim($r['MITM_SPTNO']),1,0,'L');	
					$pdf->SetFont('Arial','',8);
					$pdf->Cell(8,$td_h,$r['SPL_QTYUSE']*1,1,0,'C');	
					$pdf->Cell(13,$td_h,number_format($r['TTLREQB4']),1,0,'R');
					$pdf->Cell(13,$td_h,number_format($r['TTLREQB4']-$r['TTLREQ']),1,0,'R');
					$pdf->Cell(13,$td_h,number_format($r['TTLREQ']),1,0,'R');
					$cury+=$td_h;$i++;										
				}

			}
			
			

			#OLDPROG
			// foreach($rspsn_group as $rh){
			// 	$ccat = trim($rh['SPL_CAT']);
			// 	$cline = trim($rh['SPL_LINE']);
			// 	$cfedr = trim($rh['SPL_FEDR']);
			// 	$rshead = $this->SPL_mod->selecthead($cpsn, $cline, $cfedr);
			// 	$rsdiff_mch = $this->SPL_mod->select_but_diff_machine($cpsn, $ccat, $cline, $cfedr);				
			// 	$cwos = [];
			// 	$cmodels = [];
			// 	$clotsize = [];
			// 	foreach($rshead as $r){
			// 		if(count($cwos)==0){
			// 			$cwos[] = trim($r['PPSN1_WONO']);
			// 			$cmodels[] = trim($r['MITM_ITMD1']);
			// 			$clotsize[] = trim($r['PPSN1_SIMQT']);
			// 		} else {
			// 			$ttlwo = count($cwos);
			// 			$isexist = false;
			// 			for($i=0;$i<$ttlwo;$i++){
			// 				if($cwos[$i]==trim($r['PPSN1_WONO'])){
			// 					$isexist =true;
			// 					break;
			// 				}
			// 			}
			// 			if(!$isexist){
			// 				$cwos[] = trim($r['PPSN1_WONO']);
			// 				$cmodels[] = trim($r['MITM_ITMD1']);
			// 				$clotsize[] = trim($r['PPSN1_SIMQT']);
			// 			}
			// 		}
			// 	}
			// 	$rs = $this->SPL_mod->selectkitby4par($cpsn, $ccat, $cline, $cfedr);		
			// 	$dataw = ['SPLSCN_DOC' => $cpsn, 'SPLSCN_CAT' => $ccat , 'SPLSCN_LINE' => $cline, 'SPLSCN_FEDR' => $cfedr];
			// 	$rsdetail = $this->SPLSCN_mod->selectby_filter($dataw);		
		
			// 	foreach($rsdetail as &$d){
			// 		if(!array_key_exists("USED", $d)){
			// 			$d["USED"] = false;
			// 		}
			// 	}
			// 	unset($d);
		
				
			// 	$pdf->AliasNbPages();
			// 	$pdf->AddPage();
			// 	$hgt_p = $pdf->GetPageHeight();				
			// 	$pdf->SetAutoPageBreak(true,1);
			// 	$pdf->SetMargins(0,0);
			// 	$pdf->SetFont('Arial','',6);

			// 	$clebar = $pdf->GetStringWidth($cpsn)+40;
			// 	$pdf->Code128(3, 4,$cpsn, $clebar,4);		
			// 	$pdf->Text(3,11,$cpsn);
			// 	$clebar = $pdf->GetStringWidth($ccat)+17;
			// 	if ($ccat==''){
			// 		$ccat = '??';
			// 	}
			// 	$pdf->Code128(170, 4,$ccat,$clebar,4);		
			// 	$pdf->Text(170,11,$ccat);
			// 	$clebar = $pdf->GetStringWidth($cline)+17;
			// 	$pdf->Code128(3, 13,$cline, $clebar, 4);
			// 	$pdf->Text(3,20,$cline);
			// 	$clebar = $pdf->GetStringWidth($cfedr)+17;
			// 	$pdf->Code128(170, 13,$cfedr, $clebar, 4);
			// 	$pdf->Text(170,20,$cfedr);		
			// 	$pdf->SetXY(90,4);
			// 	$pdf->SetFont('Arial','BU',10);
			// 	$pdf->Cell(35,4,'Picking Instruction',0,0,'C');
			// 	$pdf->SetXY(100,15);
			// 	$pdf->SetFont('Arial','',6);
			// 	$pdf->Cell(15,4,'Page '.$pdf->PageNo().' / {nb}',1,0,'R');
			// 	$pdf->SetFont('Arial','B',7);		
			// 	$cury=22;
			// 	$isleft = true;
		
			// 	$xWOcount = count($cwos);
		
			// 	for($j=0;$j<$xWOcount; $j++){ // print job info
			// 		if( ($j % 2)==0){
			// 			$pdf->SetXY(3,$cury);
			// 			$pdf->SetFont('Arial','',7);
			// 			$pdf->Cell(50,4,'Model',1,0,'L');
			// 			$pdf->Cell(40,4,'Job',1,0,'L');
			// 			$pdf->Cell(10,4,'Lot Size',1,0,'C');
			// 			$pdf->SetFont('Arial','B',7);
			// 			$pdf->SetXY(3,$cury+4);
			// 			$ttlwidth = $pdf->GetStringWidth($cmodels[$j]);
			// 			if($ttlwidth > 50){
			// 				$ukuranfont = 6.5;
			// 				while($ttlwidth>50){
			// 					$pdf->SetFont('Arial','',$ukuranfont);
			// 					$ttlwidth=$pdf->GetStringWidth($cmodels[$j]);
			// 					$ukuranfont = $ukuranfont - 0.5;
			// 				}
			// 			}
			// 			$pdf->Cell(50,4,$cmodels[$j],1,0,'L');
			// 			$pdf->SetFont('Arial','B',7);
			// 			$pdf->Cell(40,4,$cwos[$j],1,0,'L');
			// 			$pdf->Cell(10,4,number_format($clotsize[$j]),1,0,'L');
			// 			$isleft = true;
			// 		} else {
			// 			$pdf->SetXY(105,$cury);
			// 			$pdf->SetFont('Arial','',7);
			// 			$pdf->Cell(50,4,'Model',1,0,'L');
			// 			$pdf->Cell(40,4,'Job',1,0,'L');
			// 			$pdf->Cell(10,4,'Lot Size',1,0,'C');
			// 			$pdf->SetFont('Arial','B',7);
			// 			$pdf->SetXY(105,$cury+4);
			// 			$ttlwidth = $pdf->GetStringWidth($cmodels[$j]);
			// 			if($ttlwidth > 50){
			// 				$ukuranfont = 6.5;
			// 				while($ttlwidth>50){
			// 					$pdf->SetFont('Arial','',$ukuranfont);
			// 					$ttlwidth=$pdf->GetStringWidth($cmodels[$j]);
			// 					$ukuranfont = $ukuranfont - 0.5;
			// 				}
			// 			}
			// 			$pdf->Cell(50,4,$cmodels[$j],1,0,'L');
			// 			$pdf->SetFont('Arial','B',7);
			// 			$pdf->Cell(40,4,$cwos[$j],1,0,'L');
			// 			$pdf->Cell(10,4,number_format($clotsize[$j]),1,0,'L');
			// 			$cury+=8;
			// 			$isleft=false;
			// 		}
			// 	}
		
			// 	$cury+=$isleft ? 9:1;
			// 	#old way
			// 	$pdf->SetXY(3,$cury);
			// 	$pdf->Cell(20,4,'No Rak',1,0,'L');
			// 	$pdf->Cell(80,4,'Machine No',1,0,'C');		
			// 	$pdf->Cell(25,4,'Part No',1,0,'L');
			// 	$pdf->Cell(30,4,'Part Name',1,0,'L');	
			// 	$pdf->Cell(8,4,'Use',1,0,'C');
			// 	$pdf->Cell(13,4,'Req.',1,0,'R');
			// 	$pdf->Cell(13,4,'Issued',1,0,'R');
			// 	$pdf->Cell(13,4,'Remain',1,0,'R');
			// 	#end old way
			// 	$wd2col = 3+20+80;
			// 	$cury+=4;				
			// 	$td_h=7;		
		
			// 	foreach($rs as &$r){
			// 		$think = true;
			// 		while($think){
			// 			$grasp = false;
			// 			foreach($rsdetail as $d){
			// 				if( ( trim($r['SPL_ORDERNO']) == trim($d['SPLSCN_ORDERNO']) ) && (trim($r['SPL_ITMCD']) == trim($d['SPLSCN_ITMCD']) ) && $d['USED']==false){
			// 					$grasp = true; break;
			// 				}
			// 			}
			// 			if($grasp){
			// 				foreach($rsdetail as &$d){
			// 					if( ( trim($r['SPL_ORDERNO']) == trim($d['SPLSCN_ORDERNO']) ) && (trim($r['SPL_ITMCD']) == trim($d['SPLSCN_ITMCD']) ) && $d['USED']==false){
			// 						$think2 = true;
			// 						while($think2){
			// 							if($r['TTLREQ'] > $r['TTLSCN']){
			// 								if($d['USED']==false){
			// 									$r['TTLSCN'] += $d['SPLSCN_QTY'];																			
			// 									$d['USED'] = true;
			// 								} else {
			// 									$think2=false;
			// 								}
			// 							} else {
			// 								$think2=false;
			// 								$think=false;
			// 							}
			// 						}
			// 					}
			// 				}
			// 				unset($d);
			// 			} else {
			// 				$think=false;
			// 			}
			// 		}
			// 	}
			// 	unset($r);
		
			// 	foreach($rs as &$r){
			// 		$r['TTLREQ'] -= $r['TTLSCN'];
			// 		foreach($rsdiff_mch as $k) {
			// 			if( trim($r['SPL_ORDERNO'])==trim($k['SPL_ORDERNO']) && trim($r['SPL_ITMCD']) == trim($k['SPL_ITMCD']) ) {
			// 				$r['SPL_ITMCD']= trim($r['SPL_ITMCD']).  ' *';
			// 			}
			// 		}
			// 	}
			// 	unset($r);				
			
			// 	#current way
			// 	$i=1;
			// 	foreach($rs as $r){
			// 		if($r['TTLREQ']>0){						
			// 			if(($cury+10)>$hgt_p){
			// 				$pdf->AddPage();				
			// 				$pdf->SetFont('Arial','',6);
			// 				$clebar = $pdf->GetStringWidth($cpsn)+40;
			// 				$pdf->Code128(3, 4,$cpsn, $clebar,4);		
			// 				$pdf->Text(3,11,$cpsn);
			// 				$clebar = $pdf->GetStringWidth($ccat)+17;
			// 				$pdf->Code128(170, 4,trim($ccat),$clebar,4);		
			// 				$pdf->Text(170,11,$ccat);
			// 				$clebar = $pdf->GetStringWidth($cline)+17;
			// 				$pdf->Code128(3, 13,$cline, $clebar, 4);
			// 				$pdf->Text(3,20,$cline);
			// 				$clebar = $pdf->GetStringWidth($cfedr)+17;
			// 				$pdf->Code128(170, 13,$cfedr, $clebar, 4);
			// 				$pdf->Text(170,20,$cfedr);
			// 				$pdf->SetXY(90,4);
			// 				$pdf->SetFont('Arial','BU',10);
			// 				$pdf->Cell(35,4,'Picking Instruction',0,0,'C');
			// 				$pdf->SetXY(100,15);
			// 				$pdf->SetFont('Arial','',6);
			// 				$pdf->Cell(15,4,'Page '.$pdf->PageNo().' / {nb}',1,0,'R');
			// 				$pdf->SetFont('Arial','B',7);					
			// 				$cury=22;
			// 				$isleft = true;
			// 				for($j=0;$j<$xWOcount; $j++){
			// 					if( ($j % 2)==0){
			// 						$pdf->SetXY(3,$cury);
			// 						$pdf->Cell(50,4,'Model',1,0,'L');
			// 						$pdf->Cell(40,4,'Job',1,0,'L');
			// 						$pdf->Cell(10,4,'Lot Size',1,0,'C');
			// 						$pdf->SetXY(3,$cury+4);
			// 						$ttlwidth = $pdf->GetStringWidth($cmodels[$j]);
			// 						if($ttlwidth > 50){
			// 							$ukuranfont = 6.5;
			// 							while($ttlwidth>50){
			// 								$pdf->SetFont('Arial','',$ukuranfont);
			// 								$ttlwidth=$pdf->GetStringWidth($cmodels[$j]);
			// 								$ukuranfont = $ukuranfont - 0.5;
			// 							}
			// 						}
			// 						$pdf->Cell(50,4,$cmodels[$j],1,0,'L');
			// 						$pdf->SetFont('Arial','B',7);
			// 						$pdf->Cell(40,4,$cwos[$j],1,0,'L');
			// 						$pdf->Cell(10,4,number_format($clotsize[$j]),1,0,'L');
			// 						$isleft = true;
			// 					} else {
			// 						$pdf->SetXY(105,$cury);
			// 						$pdf->Cell(50,4,'Model',1,0,'L');
			// 						$pdf->Cell(40,4,'Job',1,0,'L');
			// 						$pdf->Cell(10,4,'Lot Size',1,0,'C');
			// 						$pdf->SetXY(105,$cury+4);
			// 						$ttlwidth = $pdf->GetStringWidth($cmodels[$j]);
			// 						if($ttlwidth > 50){
			// 							$ukuranfont = 6.5;
			// 							while($ttlwidth>50){
			// 								$pdf->SetFont('Arial','',$ukuranfont);
			// 								$ttlwidth=$pdf->GetStringWidth($cmodels[$j]);
			// 								$ukuranfont = $ukuranfont - 0.5;
			// 							}
			// 						}
			// 						$pdf->Cell(50,4,$cmodels[$j],1,0,'L');
			// 						$pdf->SetFont('Arial','B',7);
			// 						$pdf->Cell(40,4,$cwos[$j],1,0,'L');
			// 						$pdf->Cell(10,4,number_format($clotsize[$j]),1,0,'L');
			// 						$cury+=8;
			// 						$isleft=false;
			// 					}
			// 				}
		
			// 				$cury+=$isleft ? 9:1;
			// 				$pdf->SetXY(3,$cury);
			// 				$pdf->Cell(20,4,'No Rak',1,0,'L');
			// 				$pdf->Cell(80,4,'Machine No',1,0,'C');		
			// 				$pdf->Cell(25,4,'Part No',1,0,'L');
			// 				$pdf->Cell(30,4,'Part Name',1,0,'L');	
			// 				$pdf->Cell(8,4,'Use',1,0,'C');
			// 				$pdf->Cell(13,4,'Req.',1,0,'R');
			// 				$pdf->Cell(13,4,'Issued',1,0,'R');
			// 				$pdf->Cell(13,4,'Remain',1,0,'R');
			// 				$wd2col = 3+20+80;
			// 				$cury+=4;
			// 			}
			// 			$pdf->SetFont('Arial','',8);
			// 			$pdf->SetXY(3,$cury);
						
			// 			if(strpos($r['SPL_RACKNO'], '-')!== false){
			// 				$pdf->Cell(20,$td_h,'',1,0,'L');
			// 				$achar = explode('-',$r['SPL_RACKNO']);
			// 				$pdf->Text(3.5, $cury+3,$achar[0]);
			// 				$pdf->Text(3.5, $cury+6,$achar[1]);
			// 			} else {
			// 				$pdf->Cell(20,$td_h,$r['SPL_RACKNO'],1,0,'L');
			// 			}
			// 			$lebar =  $pdf->GetStringWidth(trim($r['SPL_ORDERNO']))+17;
			// 			$clebar =  $pdf->GetStringWidth(trim($r['SPL_ORDERNO']))+16;
			// 			$strx =$wd2col - ($lebar+3);
			// 			if(($i%2)>0){							
			// 				$pdf->Code128($wd2col -80 +2 ,$cury+1.5,trim($r['SPL_ORDERNO']),$clebar,3);
			// 				$pdf->Cell(80,$td_h,$r['SPL_ORDERNO'],1,0,'R');
			// 				$pdf->SetFont('Arial','',4);
			// 				$pdf->Text($wd2col -5, $cury+1.5,$r['SPL_PROCD']);
			// 				$pdf->SetFont('Arial','',8);
			// 			} else {							
			// 				$pdf->Code128($strx,$cury+1.5,trim($r['SPL_ORDERNO']),$clebar,3);
			// 				$pdf->Cell(80,$td_h,$r['SPL_ORDERNO'],1,0,'L');
			// 				$pdf->SetFont('Arial','',4);
			// 				$pdf->Text($wd2col -79, $cury+1.5,$r['SPL_PROCD']);
			// 				$pdf->SetFont('Arial','',8);
			// 			}
						
			// 			$pdf->Cell(25,$td_h,trim($r['SPL_ITMCD']),1,0,'L');
			// 			$ttlwidth = $pdf->GetStringWidth(trim($r['MITM_SPTNO']));
			// 			if($ttlwidth > 28){
			// 				$ukuranfont = 7.5;
			// 				while($ttlwidth>28){
			// 					$pdf->SetFont('Arial','',$ukuranfont);
			// 					$ttlwidth=$pdf->GetStringWidth(trim($r['MITM_SPTNO']));
			// 					$ukuranfont = $ukuranfont - 0.5;
			// 				}
			// 			}						
			// 			$pdf->Cell(30,$td_h,trim($r['MITM_SPTNO']),1,0,'L');	
			// 			$pdf->SetFont('Arial','',8);
			// 			$pdf->Cell(8,$td_h,$r['SPL_QTYUSE']*1,1,0,'C');	
			// 			$pdf->Cell(13,$td_h,number_format($r['TTLREQB4']),1,0,'R');
			// 			$pdf->Cell(13,$td_h,number_format($r['TTLREQB4']-$r['TTLREQ']),1,0,'R');
			// 			$pdf->Cell(13,$td_h,number_format($r['TTLREQ']),1,0,'R');
			// 			$cury+=$td_h;$i++;
			// 		}
			// 	}
			// 	#end current way
			// }
		} else {
			if(count($this->SPL_mod->select_header_partreq_unapproved($cpsn))==0){
				die($cpsn.' should be approved first');
			}
			foreach($rspsn_group as $rh){
				$ccat = trim($rh['SPL_CAT']);
				$cline = trim($rh['SPL_LINE']);
				$cfedr = trim($rh['SPL_FEDR']);
				$rshead = $this->SPL_mod->select_reffdoc($cpsn);
				$rs = $this->SPL_mod->selectkitby4par($cpsn, $ccat, $cline, $cfedr);
				$dataw = ['SPLSCN_DOC' => $cpsn, 'SPLSCN_CAT' => $ccat , 'SPLSCN_LINE' => $cline, 'SPLSCN_FEDR' => $cfedr];
				$rsdetail = $this->SPLSCN_mod->selectby_filter($dataw);
				foreach($rs as &$r){
					foreach($rsdetail as &$d){
						if($d['SPLSCN_QTY']>0){
							if($r['SPL_ITMCD']==$d['SPLSCN_ITMCD']){
								if($r['TTLREQ']>0){
									if($r['TTLREQ']==$d['SPLSCN_QTY']){
										$r['TTLREQ'] = 0;
										$d['SPLSCN_QTY'] = 0;
									} elseif($r['TTLREQ']>$d['SPLSCN_QTY']){
										$r['TTLREQ'] -= $d['SPLSCN_QTY'];
										$d['SPLSCN_QTY'] = 0;
									} elseif($r['TTLREQ']<$d['SPLSCN_QTY']){										
										$d['SPLSCN_QTY'] -= $r['TTLREQ'];
										$r['TTLREQ'] = 0;
									}
								}
							}
						}
					}
					unset($d);
				}
				unset($r);
				
				$pdf->AliasNbPages();
				$pdf->AddPage();
				$hgt_p = $pdf->GetPageHeight();				
				$pdf->SetAutoPageBreak(true,1);
				$pdf->SetMargins(0,0);
				$pdf->SetFont('Arial','',6);
				$clebar = $pdf->GetStringWidth($cpsn)+40;
				$pdf->Code128(3, 4,$cpsn, $clebar,4);		
				$pdf->Text(3,11,$cpsn);
				$clebar = $pdf->GetStringWidth($ccat)+17;
				if ($ccat==''){
					$ccat = '??';
				}
				$pdf->Code128(170, 4,$ccat,$clebar,4);		
				$pdf->Text(170,11,$ccat);
				$clebar = $pdf->GetStringWidth($cline)+17;
				$pdf->Code128(3, 13,$cline, $clebar, 4);
				$pdf->Text(3,20,$cline);
				$clebar = $pdf->GetStringWidth($cfedr)+17;
				$pdf->Code128(170, 13,$cfedr, $clebar, 4);
				$pdf->Text(170,20,$cfedr);
				$pdf->SetFont('Arial','BU',10);
				$pdf->SetXY(90,4);
				$pdf->Cell(35,4,'Picking Instruction',0,0,'C');
				$pdf->SetXY(100,15);
				$pdf->SetFont('Arial','',6);
				$pdf->Cell(15,4,'Page '.$pdf->PageNo().' / {nb}',1,0,'R');
				$pdf->SetFont('Arial','B',7);	
				$cury=22;
				$isleft = true;
				$nom = 0;				
				$PSNDocAsReff = [];
				foreach($rshead as $r){
					if($r['REFDOCCAT']=='PSN'){
						$isReffDoc_PSN = true;
						$PSNDocAsReff[] = $r['SPL_REFDOCNO'];
					}
					if( ($nom % 2)==0){
						$pdf->SetXY(3,$cury);
						$pdf->SetFont('Arial','',7);
						$pdf->Cell(100,4,'Reff Document',1,0,'C');
						$pdf->SetFont('Arial','B',7);
						$pdf->SetXY(3,$cury+4);
						$ttlwidth = $pdf->GetStringWidth($r['SPL_REFDOCNO']);
						if($ttlwidth > 100){
							$ukuranfont = 6.5;
							while($ttlwidth>50){
								$pdf->SetFont('Arial','',$ukuranfont);
								$ttlwidth=$pdf->GetStringWidth($r['SPL_REFDOCNO']);
								$ukuranfont = $ukuranfont - 0.5;
							}
						}
						$pdf->Cell(100,4,$r['SPL_REFDOCNO'],1,0,'L');						
						$isleft = true;
					} else {
						$pdf->SetXY(105,$cury);
						$pdf->SetFont('Arial','',7);
						$pdf->Cell(100,4,'Reff Document',1,0,'C');						
						$pdf->SetFont('Arial','B',7);
						$pdf->SetXY(105,$cury+4);
						$ttlwidth = $pdf->GetStringWidth($r['SPL_REFDOCNO']);
						if($ttlwidth > 100){
							$ukuranfont = 6.5;
							while($ttlwidth>50){
								$pdf->SetFont('Arial','',$ukuranfont);
								$ttlwidth=$pdf->GetStringWidth($r['SPL_REFDOCNO']);
								$ukuranfont = $ukuranfont - 0.5;
							}
						}
						$pdf->Cell(100,4,$r['SPL_REFDOCNO'],1,0,'L');						
						$cury+=8;
						$isleft=false;
					}
					$nom++;
				}
				$cury+=$isleft ? 9:1;
				
				if(count($PSNDocAsReff)>0){
					$rsJob = $this->SPL_mod->selecthead_bypsn($PSNDocAsReff);
					$cwos = [];
					$cmodels = [];
					$clotsize = [];
					foreach($rsJob as $r){
						if(count($cwos)==0){
							$cwos[] = trim($r['PPSN1_WONO']);
							$cmodels[] = trim($r['MITM_ITMD1']);
							$clotsize[] = trim($r['PPSN1_SIMQT']);
						} else {
							$ttlwo = count($cwos);
							$isexist = false;
							for($i=0;$i<$ttlwo;$i++){
								if($cwos[$i]==trim($r['PPSN1_WONO'])){
									$isexist =true;
									break;
								}
							}
							if(!$isexist){
								$cwos[] = trim($r['PPSN1_WONO']);
								$cmodels[] = trim($r['MITM_ITMD1']);
								$clotsize[] = trim($r['PPSN1_SIMQT']);
							}
						}
					}
					$xWOcount = count($cwos);	
					if($xWOcount>0)	{
						for($j=0;$j<$xWOcount; $j++){ 
							if( ($j % 2)==0){
								$pdf->SetXY(3,$cury);
								$pdf->SetFont('Arial','',7);
								$pdf->Cell(50,4,'Model',1,0,'L');
								$pdf->Cell(40,4,'Job',1,0,'L');
								$pdf->Cell(10,4,'Lot Size',1,0,'C');
								$pdf->SetFont('Arial','B',7);
								$pdf->SetXY(3,$cury+4);
								$ttlwidth = $pdf->GetStringWidth($cmodels[$j]);
								if($ttlwidth > 50){
									$ukuranfont = 6.5;
									while($ttlwidth>50){
										$pdf->SetFont('Arial','',$ukuranfont);
										$ttlwidth=$pdf->GetStringWidth($cmodels[$j]);
										$ukuranfont = $ukuranfont - 0.5;
									}
								}
								$pdf->Cell(50,4,$cmodels[$j],1,0,'L');
								$pdf->SetFont('Arial','B',7);
								$pdf->Cell(40,4,$cwos[$j],1,0,'L');
								$pdf->Cell(10,4,number_format($clotsize[$j]),1,0,'L');
								$isleft = true;
							} else {
								$pdf->SetXY(105,$cury);
								$pdf->SetFont('Arial','',7);
								$pdf->Cell(50,4,'Model',1,0,'L');
								$pdf->Cell(40,4,'Job',1,0,'L');
								$pdf->Cell(10,4,'Lot Size',1,0,'C');
								$pdf->SetFont('Arial','B',7);
								$pdf->SetXY(105,$cury+4);
								$ttlwidth = $pdf->GetStringWidth($cmodels[$j]);
								if($ttlwidth > 50){
									$ukuranfont = 6.5;
									while($ttlwidth>50){
										$pdf->SetFont('Arial','',$ukuranfont);
										$ttlwidth=$pdf->GetStringWidth($cmodels[$j]);
										$ukuranfont = $ukuranfont - 0.5;
									}
								}
								$pdf->Cell(50,4,$cmodels[$j],1,0,'L');
								$pdf->SetFont('Arial','B',7);
								$pdf->Cell(40,4,$cwos[$j],1,0,'L');
								$pdf->Cell(10,4,number_format($clotsize[$j]),1,0,'L');
								$cury+=8;
								$isleft=false;
							}
						}
						$cury+=$isleft ? 9:1;
					}
				}
				
				$pdf->SetXY(3,$cury);
				$pdf->Cell(20,4,'No Rak',1,0,'L');
				$pdf->Cell(60,4,'Machine No',1,0,'C');		
				$pdf->Cell(25,4,'Part No',1,0,'L');
				$pdf->Cell(40,4,'Part Name',1,0,'L');	
				$pdf->Cell(15,4,'Req',1,0,'R');
				$pdf->Cell(43,4,'Remark',1,0,'L');	
				$wd2col = 3+20+60;
				$cury+=4;				
				$td_h=7;
				$i=1;							
				foreach($rs as $r){				
					if($r['TTLREQ']>0){					
						if(($cury+10)>$hgt_p){
							$pdf->AddPage();					
							$pdf->SetFont('Arial','',6);
							$clebar = $pdf->GetStringWidth($cpsn)+40;
							$pdf->Code128(3, 4,$cpsn, $clebar,4);
							$pdf->Text(3,11,$cpsn);
							$clebar = $pdf->GetStringWidth($ccat)+17;
							if ($ccat==''){
								$ccat = '??';
							}
							$pdf->Code128(170, 4,$ccat,$clebar,4);		
							$pdf->Text(170,11,$ccat);
							$clebar = $pdf->GetStringWidth($cline)+17;
							$pdf->Code128(3, 13,$cline, $clebar, 4);
							$pdf->Text(3,20,$cline);
							$clebar = $pdf->GetStringWidth($cfedr)+17;
							$pdf->Code128(170, 13,$cfedr, $clebar, 4);
							$pdf->Text(170,20,$cfedr);
							$pdf->SetFont('Arial','BU',10);
							$pdf->SetXY(90,4);
							$pdf->Cell(35,4,'Picking Instruction',0,0,'C');
							$pdf->SetXY(100,15);
							$pdf->SetFont('Arial','',6);
							$pdf->Cell(15,4,'Page '.$pdf->PageNo().' / {nb}',1,0,'R');
							$pdf->SetFont('Arial','B',7);	
							$cury=22;
							$isleft = true;
							$nom = 0;
							foreach($rshead as $h){
								if( ($nom % 2)==0){
									$pdf->SetXY(3,$cury);
									$pdf->SetFont('Arial','',7);
									$pdf->Cell(100,4,'Reff Document',1,0,'C');
									$pdf->SetFont('Arial','B',7);
									$pdf->SetXY(3,$cury+4);
									$ttlwidth = $pdf->GetStringWidth($h['SPL_REFDOCNO']);
									if($ttlwidth > 100){
										$ukuranfont = 6.5;
										while($ttlwidth>50){
											$pdf->SetFont('Arial','',$ukuranfont);
											$ttlwidth=$pdf->GetStringWidth($h['SPL_REFDOCNO']);
											$ukuranfont = $ukuranfont - 0.5;
										}
									}
									$pdf->Cell(100,4,$h['SPL_REFDOCNO'],1,0,'L');						
									$isleft = true;
								} else {
									$pdf->SetXY(105,$cury);
									$pdf->SetFont('Arial','',7);
									$pdf->Cell(100,4,'Model',1,0,'C');						
									$pdf->SetFont('Arial','B',7);
									$pdf->SetXY(105,$cury+4);
									$ttlwidth = $pdf->GetStringWidth($h['SPL_REFDOCNO']);
									if($ttlwidth > 100){
										$ukuranfont = 6.5;
										while($ttlwidth>50){
											$pdf->SetFont('Arial','',$ukuranfont);
											$ttlwidth=$pdf->GetStringWidth($h['SPL_REFDOCNO']);
											$ukuranfont = $ukuranfont - 0.5;
										}
									}
									$pdf->Cell(100,4,$h['SPL_REFDOCNO'],1,0,'L');						
									$cury+=8;
									$isleft=false;
								}
								$nom++;
							}
							$cury+=$isleft ? 9:1;
							if(count($PSNDocAsReff)>0){
								$rsJob = $this->SPL_mod->selecthead_bypsn($PSNDocAsReff);
								$cwos = [];
								$cmodels = [];
								$clotsize = [];
								foreach($rsJob as $rj){
									if(count($cwos)==0){
										$cwos[] = trim($rj['PPSN1_WONO']);
										$cmodels[] = trim($rj['MITM_ITMD1']);
										$clotsize[] = trim($rj['PPSN1_SIMQT']);
									} else {
										$ttlwo = count($cwos);
										$isexist = false;
										for($i=0;$i<$ttlwo;$i++){
											if($cwos[$i]==trim($rj['PPSN1_WONO'])){
												$isexist =true;
												break;
											}
										}
										if(!$isexist){
											$cwos[] = trim($rj['PPSN1_WONO']);
											$cmodels[] = trim($rj['MITM_ITMD1']);
											$clotsize[] = trim($rj['PPSN1_SIMQT']);
										}
									}
								}
							}
							$xWOcount = count($cwos);		
							for($j=0;$j<$xWOcount; $j++){ 
								if( ($j % 2)==0){
									$pdf->SetXY(3,$cury);
									$pdf->SetFont('Arial','',7);
									$pdf->Cell(50,4,'Model',1,0,'L');
									$pdf->Cell(40,4,'Job',1,0,'L');
									$pdf->Cell(10,4,'Lot Size',1,0,'C');
									$pdf->SetFont('Arial','B',7);
									$pdf->SetXY(3,$cury+4);
									$ttlwidth = $pdf->GetStringWidth($cmodels[$j]);
									if($ttlwidth > 50){
										$ukuranfont = 6.5;
										while($ttlwidth>50){
											$pdf->SetFont('Arial','',$ukuranfont);
											$ttlwidth=$pdf->GetStringWidth($cmodels[$j]);
											$ukuranfont = $ukuranfont - 0.5;
										}
									}
									$pdf->Cell(50,4,$cmodels[$j],1,0,'L');
									$pdf->SetFont('Arial','B',7);
									$pdf->Cell(40,4,$cwos[$j],1,0,'L');
									$pdf->Cell(10,4,number_format($clotsize[$j]),1,0,'L');
									$isleft = true;
								} else {
									$pdf->SetXY(105,$cury);
									$pdf->SetFont('Arial','',7);
									$pdf->Cell(50,4,'Model',1,0,'L');
									$pdf->Cell(40,4,'Job',1,0,'L');
									$pdf->Cell(10,4,'Lot Size',1,0,'C');
									$pdf->SetFont('Arial','B',7);
									$pdf->SetXY(105,$cury+4);
									$ttlwidth = $pdf->GetStringWidth($cmodels[$j]);
									if($ttlwidth > 50){
										$ukuranfont = 6.5;
										while($ttlwidth>50){
											$pdf->SetFont('Arial','',$ukuranfont);
											$ttlwidth=$pdf->GetStringWidth($cmodels[$j]);
											$ukuranfont = $ukuranfont - 0.5;
										}
									}
									$pdf->Cell(50,4,$cmodels[$j],1,0,'L');
									$pdf->SetFont('Arial','B',7);
									$pdf->Cell(40,4,$cwos[$j],1,0,'L');
									$pdf->Cell(10,4,number_format($clotsize[$j]),1,0,'L');
									$cury+=8;
									$isleft=false;
								}
							}
							$cury+=$isleft ? 9:1;
							$pdf->SetXY(3,$cury);
							$pdf->Cell(20,4,'No Rak',1,0,'L');
							$pdf->Cell(60,4,'Machine No',1,0,'C');		
							$pdf->Cell(25,4,'Part No',1,0,'L');
							$pdf->Cell(40,4,'Part Name',1,0,'L');	
							$pdf->Cell(15,4,'Req',1,0,'R');
							$pdf->Cell(43,4,'Remark',1,0,'L');
							
							$wd2col = 3+20+60;
							$cury+=4;
						}
						$pdf->SetFont('Arial','',8);
						$pdf->SetXY(3,$cury);
						
						if(strpos($r['SPL_RACKNO'], '-')!== false){
							$pdf->Cell(20,$td_h,'',1,0,'L');//$r['SPL_RACKNO']
							$achar = explode('-',$r['SPL_RACKNO']);
							$pdf->Text(3.5, $cury+3,$achar[0]);
							$pdf->Text(3.5, $cury+6,$achar[1]);
						} else {
							$pdf->Cell(20,$td_h,$r['SPL_RACKNO'],1,0,'L');
						}
						$lebar =  $pdf->GetStringWidth(trim($r['SPL_ORDERNO']))+17;
						$clebar =  $pdf->GetStringWidth(trim($r['SPL_ORDERNO']))+16;
						$strx =$wd2col - ($lebar+3);
						if(($i%2)>0){
							//$pdf->Code39(19,$cury+0.5,trim($r['SPL_ORDERNO']),0.5,3);
							$pdf->Code128($wd2col -60 +2 ,$cury+1.5,trim($r['SPL_ORDERNO']),$clebar,3);
							$pdf->Cell(60,$td_h,$r['SPL_ORDERNO'],1,0,'R');
							$pdf->SetFont('Arial','',4);
							$pdf->Text($wd2col -5, $cury+1.5,$r['SPL_PROCD']);
							$pdf->SetFont('Arial','',8);
						} else {
							//$pdf->Code39($strx,$cury+0.5,trim($r['SPL_ORDERNO']),0.5,3);
							$pdf->Code128($strx,$cury+1.5,trim($r['SPL_ORDERNO']),$clebar,3);
							$pdf->Cell(60,$td_h,$r['SPL_ORDERNO'],1,0,'L');
							$pdf->SetFont('Arial','',4);
							$pdf->Text($wd2col -79, $cury+1.5,$r['SPL_PROCD']);
							$pdf->SetFont('Arial','',8);
						}
						
						$pdf->Cell(25,$td_h,trim($r['SPL_ITMCD']),1,0,'L');
						$ttlwidth = $pdf->GetStringWidth(trim($r['MITM_SPTNO']));
						if($ttlwidth > 40){
							$ukuranfont = 7.5;
							while($ttlwidth>39){
								$pdf->SetFont('Arial','',$ukuranfont);
								$ttlwidth=$pdf->GetStringWidth(trim($r['MITM_SPTNO']));
								$ukuranfont = $ukuranfont - 0.5;
							}
						}						
						$pdf->Cell(40,$td_h,trim($r['MITM_SPTNO']),1,0,'L');	
						$pdf->SetFont('Arial','',8);
						$pdf->Cell(15,$td_h,number_format($r['TTLREQ']),1,0,'R');
						$ttlwidth = $pdf->GetStringWidth(trim($r['SPL_ITMRMRK']));
						if($ttlwidth > 43){
							$ukuranfont = 7.5;
							while($ttlwidth>43){
								$pdf->SetFont('Arial','',$ukuranfont);
								$ttlwidth=$pdf->GetStringWidth(trim($r['SPL_ITMRMRK']));
								$ukuranfont = $ukuranfont - 0.5;
							}
						}
						$pdf->Cell(43,$td_h,$r['SPL_ITMRMRK'],1,0,'L');	
						$pdf->SetFont('Arial','',8);
						$cury+=$td_h;$i++;
					}
				}
			}
		}
		$pdf->Output('I','KIT Doc  '.$cpsn.'.pdf');
	}	

	public function printkit(){
		if(!isset($_COOKIE["CKPSI_DPSN"]) && !isset($_COOKIE["CKPSI_DCAT"]) && !isset($_COOKIE["CKPSI_DLNE"]) && !isset($_COOKIE["CKPSI_DFDR"]) ){
			exit('no data to be found');
		}
		$cpsn = $_COOKIE["CKPSI_DPSN"];
		$ccat = $_COOKIE["CKPSI_DCAT"];
		$cline = $_COOKIE["CKPSI_DLNE"];
		$cfedr = $_COOKIE["CKPSI_DFDR"];
		$rshead = $this->SPL_mod->selecthead($cpsn, $cline, $cfedr);
		$rsdiff_mch = $this->SPL_mod->select_but_diff_machine($cpsn, $ccat, $cline, $cfedr);
		$rsmachine = $this->SPL_mod->select_machine(['SPL_DOC' => $cpsn, 'SPL_CAT' => $ccat, 'SPL_LINE' => $cline, 'SPL_FEDR' => $cfedr]);
		$cwos = [];
		$cmodels = [];
		$clotsize = [];
		foreach($rshead as $r){			
			if(count($cwos)==0){
				$cwos[] = trim($r['PPSN1_WONO']);
				$cmodels[] = trim($r['MITM_ITMD1']);
				$clotsize[] = trim($r['PPSN1_SIMQT']);
			} else {
				$ttlwo = count($cwos);
				$isexist = false;
				for($i=0;$i<$ttlwo;$i++){
					if($cwos[$i]==trim($r['PPSN1_WONO'])){
						$isexist =true;
						break;
					}
				}
				if(!$isexist){
					$cwos[] = trim($r['PPSN1_WONO']);
					$cmodels[] = trim($r['MITM_ITMD1']);
					$clotsize[] = trim($r['PPSN1_SIMQT']);
				}
			}
		}
		$rs = $this->SPL_mod->selectkitby4par($cpsn, $ccat, $cline, $cfedr);		
		$dataw = ['SPLSCN_DOC' => $cpsn, 'SPLSCN_CAT' => $ccat , 'SPLSCN_LINE' => $cline, 'SPLSCN_FEDR' => $cfedr];
		$rsdetail = $this->SPLSCN_mod->selectby_filter($dataw);		

		foreach($rsdetail as &$d){
			if(!array_key_exists("USED", $d)){
				$d["USED"] = false;
			}
		}
		unset($d);

		$pdf = new PDF_Code39e128('P','mm','A4');
		$pdf->AliasNbPages();
		$pdf->AddPage();
		$hgt_p = $pdf->GetPageHeight();
		$wid_p = $pdf->GetPageWidth();
		$pdf->SetAutoPageBreak(true,1);
		$pdf->SetMargins(0,0);
		$pdf->SetFont('Arial','',6);
		$clebar = $pdf->GetStringWidth($cpsn)+40;
		$pdf->Code128(3, 4,$cpsn, $clebar,4);		
		$pdf->Text(3,11,$cpsn);
		$clebar = $pdf->GetStringWidth($ccat)+17;
		$pdf->Code128(170, 4,trim($ccat),$clebar,4);		
		$pdf->Text(170,11,$ccat);
		$clebar = $pdf->GetStringWidth($cline)+17;
		$pdf->Code128(3, 13,$cline, $clebar, 4);
		$pdf->Text(3,20,$cline);
		$clebar = $pdf->GetStringWidth($cfedr)+17;
		$pdf->Code128(170, 13,$cfedr, $clebar, 4);
		$pdf->Text(170,20,$cfedr);		
		$pdf->SetXY(90,4);
		$pdf->SetFont('Arial','BU',10);
		$pdf->Cell(35,4,'Picking Instruction',0,0,'C');
		$pdf->SetXY(100,15);
		$pdf->SetFont('Arial','',6);
		$pdf->Cell(15,4,'Page '.$pdf->PageNo().' / {nb}',1,0,'R');
		$cury=22;		
		

		$xWOcount = count($cwos);

		for($j=0;$j<$xWOcount; $j++){ // print job info
			if( ($j % 2)==0){
				$pdf->SetXY(3,$cury);
				$pdf->SetFont('Arial','',7);
				$pdf->Cell(50,4,'Model',1,0,'L');
				$pdf->Cell(40,4,'Job',1,0,'L');
				$pdf->Cell(10,4,'Lot Size',1,0,'C');
				$pdf->SetFont('Arial','B',7);
				$pdf->SetXY(3,$cury+4);
				$ttlwidth = $pdf->GetStringWidth($cmodels[$j]);
				if($ttlwidth > 50){
					$ukuranfont = 6.5;
					while($ttlwidth>50){
						$pdf->SetFont('Arial','',$ukuranfont);
						$ttlwidth=$pdf->GetStringWidth($cmodels[$j]);
						$ukuranfont = $ukuranfont - 0.5;
					}
				}
				$pdf->Cell(50,4,$cmodels[$j],1,0,'L');
				$pdf->SetFont('Arial','B',7);
				$pdf->Cell(40,4,$cwos[$j],1,0,'L');
				$pdf->Cell(10,4,number_format($clotsize[$j]),1,0,'L');
				$isleft = true;
			} else {
				$pdf->SetXY(105,$cury);
				$pdf->SetFont('Arial','',7);
				$pdf->Cell(50,4,'Model',1,0,'L');
				$pdf->Cell(40,4,'Job',1,0,'L');
				$pdf->Cell(10,4,'Lot Size',1,0,'C');
				$pdf->SetFont('Arial','B',7);
				$pdf->SetXY(105,$cury+4);
				$ttlwidth = $pdf->GetStringWidth($cmodels[$j]);
				if($ttlwidth > 50){
					$ukuranfont = 6.5;
					while($ttlwidth>50){
						$pdf->SetFont('Arial','',$ukuranfont);
						$ttlwidth=$pdf->GetStringWidth($cmodels[$j]);
						$ukuranfont = $ukuranfont - 0.5;
					}
				}
				$pdf->Cell(50,4,$cmodels[$j],1,0,'L');
				$pdf->SetFont('Arial','B',7);
				$pdf->Cell(40,4,$cwos[$j],1,0,'L');
				$pdf->Cell(10,4,number_format($clotsize[$j]),1,0,'L');
				$cury+=8;
				$isleft=false;
			}
		}
				
		foreach($rs as &$r){
			$think = true;
			while($think){
				$grasp = false;
				foreach($rsdetail as $d){
					if( ( trim($r['SPL_ORDERNO']) == trim($d['SPLSCN_ORDERNO']) ) && (trim($r['SPL_ITMCD']) == trim($d['SPLSCN_ITMCD']) ) && $d['USED']==false){
						$grasp = true; break;
					}
				}
				if($grasp){
					foreach($rsdetail as &$d){
						if( ( trim($r['SPL_ORDERNO']) == trim($d['SPLSCN_ORDERNO']) ) && (trim($r['SPL_ITMCD']) == trim($d['SPLSCN_ITMCD']) ) && $d['USED']==false){
							$think2 = true;
							while($think2){
								if($r['TTLREQ'] > $r['TTLSCN']){
									if($d['USED']==false){
										$r['TTLSCN'] += $d['SPLSCN_QTY'];																		
										$d['USED'] = true;
									} else {
										$think2=false;
									}
								} else {
									$think2=false;
									$think=false;
								}
							}
						}
					}
					unset($d);
				} else {
					$think=false;
				}
			}
		}
		unset($r);

		foreach($rs as &$r){
			$r['TTLREQ'] -= $r['TTLSCN'];
			foreach($rsdiff_mch as $k) {
				if( trim($r['SPL_ORDERNO'])==trim($k['SPL_ORDERNO']) && trim($r['SPL_ITMCD']) == trim($k['SPL_ITMCD']) ) {
					$r['SPL_ITMCD']= trim($r['SPL_ITMCD']).  ' *';
				}
			}
		}
		unset($r);

		#group by machine mode
		$cury+=$isleft ? 9:1;
		$tempMachine = '';
		$td_h=7;
		$wd2col = 3+20+80;
		// $rs[] = ['SPL_MC' => 'DT401-C2-1', 'SPL_RACKNO' => 'RAKU' 
		// , 'SPL_ORDERNO' => 'MCZKU', 'SPL_ITMCD' => 'ITEMCODEKU', 'MITM_SPTNO' => 'SPTNOKU', 'TTLREQ' => 100, 'SPL_PROCD' => 'SMT-B'];
		$isnewpage = true;
		$i=1;
		foreach($rsmachine as $g){
			foreach($rs as $r){
				if($g['SPL_MC']==$r['SPL_MC'] && $r['TTLREQ']>0){
					if(($cury+10)>$hgt_p){
						$pdf->AddPage();
						$pdf->SetFont('Arial','',6);
						$clebar = $pdf->GetStringWidth($cpsn)+40;
						$pdf->Code128(3, 4,$cpsn, $clebar,4);		
						$pdf->Text(3,11,$cpsn);
						$clebar = $pdf->GetStringWidth($ccat)+17;
						$pdf->Code128(170, 4,trim($ccat),$clebar,4);		
						$pdf->Text(170,11,$ccat);
						$clebar = $pdf->GetStringWidth($cline)+17;
						$pdf->Code128(3, 13,$cline, $clebar, 4);
						$pdf->Text(3,20,$cline);
						$clebar = $pdf->GetStringWidth($cfedr)+17;
						$pdf->Code128(170, 13,$cfedr, $clebar, 4);
						$pdf->Text(170,20,$cfedr);
						$pdf->SetXY(90,4);
						$pdf->SetFont('Arial','BU',10);
						$pdf->Cell(35,4,'Picking Instruction',0,0,'C');
						$pdf->SetXY(100,15);
						$pdf->SetFont('Arial','',6);
						$pdf->Cell(15,4,'Page '.$pdf->PageNo().' / {nb}',1,0,'R');
						$pdf->SetFont('Arial','B',7);
						$cury=22;
						$isleft = true;
						for($j=0;$j<$xWOcount; $j++){#count($cwos)
							if( ($j % 2)==0){
								$pdf->SetXY(3,$cury);
								$pdf->Cell(50,4,'Model',1,0,'L');
								$pdf->Cell(40,4,'Job',1,0,'L');
								$pdf->Cell(10,4,'Lot Size',1,0,'C');
								$pdf->SetXY(3,$cury+4);
								$ttlwidth = $pdf->GetStringWidth($cmodels[$j]);
								if($ttlwidth > 50){
									$ukuranfont = 6.5;
									while($ttlwidth>50){
										$pdf->SetFont('Arial','',$ukuranfont);
										$ttlwidth=$pdf->GetStringWidth($cmodels[$j]);
										$ukuranfont = $ukuranfont - 0.5;
									}
								}
								$pdf->Cell(50,4,$cmodels[$j],1,0,'L');
								$pdf->SetFont('Arial','B',7);
								$pdf->Cell(40,4,$cwos[$j],1,0,'L');
								$pdf->Cell(10,4,number_format($clotsize[$j]),1,0,'L');
								$isleft = true;
							} else {
								$pdf->SetXY(105,$cury);
								$pdf->Cell(50,4,'Model',1,0,'L');
								$pdf->Cell(40,4,'Job',1,0,'L');
								$pdf->Cell(10,4,'Lot Size',1,0,'C');
								$pdf->SetXY(105,$cury+4);
								$ttlwidth = $pdf->GetStringWidth($cmodels[$j]);
								if($ttlwidth > 50){
									$ukuranfont = 6.5;
									while($ttlwidth>50){
										$pdf->SetFont('Arial','',$ukuranfont);
										$ttlwidth=$pdf->GetStringWidth($cmodels[$j]);
										$ukuranfont = $ukuranfont - 0.5;
									}
								}
								$pdf->Cell(50,4,$cmodels[$j],1,0,'L');
								$pdf->SetFont('Arial','B',7);
								$pdf->Cell(40,4,$cwos[$j],1,0,'L');
								$pdf->Cell(10,4,number_format($clotsize[$j]),1,0,'L');
								$cury+=8;
								$isleft=false;
							}
						}
						$cury+=$isleft ? 9:1;
						$isnewpage= true;
					} else {
						$isnewpage= false;
					}
					if($tempMachine!=$g['SPL_MC']){
						$pdf->SetFont('Arial','B',7);
						$pdf->SetXY(3,$cury);
						$pdf->Cell(40,4,'Machine : '.$g['SPL_MC'],1,0,'L');
						$tempMachine=$g['SPL_MC'];
						$cury+=4;
						$pdf->SetXY(3,$cury);
						$pdf->Cell(20,4,'No Rak',1,0,'L');
						$pdf->Cell(80,4,'Machine No',1,0,'C');		
						$pdf->Cell(25,4,'Part No',1,0,'L');
						$pdf->Cell(40,4,'Part Name',1,0,'L');	
						$pdf->Cell(15,4,'Req',1,0,'R');
						$pdf->Cell(5,4,'',1,0,'L');	
						$cury+=4;
						$pdf->SetFont('Arial','',8);
						$pdf->SetXY(3,$cury);
						if(strpos($r['SPL_RACKNO'], '-')!== false){
							$pdf->Cell(20,$td_h,'',1,0,'L');
							$achar = explode('-',$r['SPL_RACKNO']);
							$ttlwidth = $pdf->GetStringWidth($achar[0]);
							if($ttlwidth > 20){
								$ukuranfont = 7.5;
								while($ttlwidth>20){
									$pdf->SetFont('Arial','',$ukuranfont);
									$ttlwidth=$pdf->GetStringWidth($achar[0]);
									$ukuranfont = $ukuranfont - 0.5;
								}
							}
							$pdf->Text(3.5, $cury+3,$achar[0]);
							$pdf->SetFont('Arial','',8);
							$pdf->Text(3.5, $cury+6,$achar[1]);
						} else {
							$pdf->Cell(20,$td_h,$r['SPL_RACKNO'],1,0,'L');
						}
						$lebar =  $pdf->GetStringWidth(trim($r['SPL_ORDERNO']))+17;
						$clebar =  $pdf->GetStringWidth(trim($r['SPL_ORDERNO']))+16;
						$strx =$wd2col - ($lebar+3);
						if(($i%2)>0){
							//$pdf->Code39(19,$cury+0.5,trim($r['SPL_ORDERNO']),0.5,3);
							$pdf->Code128($wd2col -80 +2 ,$cury+1.5,trim($r['SPL_ORDERNO']),$clebar,3);
							$pdf->Cell(80,$td_h,$r['SPL_ORDERNO'],1,0,'R');
							$pdf->SetFont('Arial','',4);
							$pdf->Text($wd2col -5, $cury+1.5,$r['SPL_PROCD']);
							$pdf->SetFont('Arial','',8);
						} else {
							//$pdf->Code39($strx,$cury+0.5,trim($r['SPL_ORDERNO']),0.5,3);
							$pdf->Code128($strx,$cury+1.5,trim($r['SPL_ORDERNO']),$clebar,3);
							$pdf->Cell(80,$td_h,$r['SPL_ORDERNO'],1,0,'L');
							$pdf->SetFont('Arial','',4);
							$pdf->Text($wd2col -79, $cury+1.5,$r['SPL_PROCD']);
							$pdf->SetFont('Arial','',8);
						}
						$ttlwidth = $pdf->GetStringWidth(trim($r['SPL_ITMCD']));
						if($ttlwidth > 25){
							$ukuranfont = 7.5;
							while($ttlwidth>24){
								$pdf->SetFont('Arial','',$ukuranfont);
								$ttlwidth=$pdf->GetStringWidth(trim($r['SPL_ITMCD']));
								$ukuranfont = $ukuranfont - 0.5;
							}
						}
						$pdf->Cell(25,$td_h,trim($r['SPL_ITMCD']),1,0,'L');
						$pdf->SetFont('Arial','',8);
						$ttlwidth = $pdf->GetStringWidth(trim($r['MITM_SPTNO']));
						if($ttlwidth > 39){
							$ukuranfont = 7.5;
							while($ttlwidth>25){
								$pdf->SetFont('Arial','',$ukuranfont);
								$ttlwidth=$pdf->GetStringWidth(trim($r['MITM_SPTNO']));
								$ukuranfont = $ukuranfont - 0.5;
							}
						}						
						$pdf->Cell(40,$td_h,trim($r['MITM_SPTNO']),1,0,'L');	
						$pdf->SetFont('Arial','',8);
						$pdf->Cell(15,$td_h,number_format($r['TTLREQ']),1,0,'R');
						$pdf->Cell(5,$td_h,'',1,0,'L');
					} else {
						if($isnewpage){
							$pdf->SetFont('Arial','B',7);
							$pdf->SetXY(3,$cury);
							$pdf->Cell(40,4,'Machine : '.$g['SPL_MC'],1,0,'L');							
							$cury+=4; 
							$pdf->SetXY(3,$cury);
							$pdf->Cell(20,4,'No Rak',1,0,'L');
							$pdf->Cell(80,4,'Machine No',1,0,'C');		
							$pdf->Cell(25,4,'Part No',1,0,'L');
							$pdf->Cell(40,4,'Part Name',1,0,'L');	
							$pdf->Cell(15,4,'Req',1,0,'R');
							$pdf->Cell(5,4,'',1,0,'L');	
							$cury+=4;						
						}
						$pdf->SetFont('Arial','',8);
						$pdf->SetXY(3,$cury);
						if(strpos($r['SPL_RACKNO'], '-')!== false){
							$pdf->Cell(20,$td_h,'',1,0,'L');
							$achar = explode('-',$r['SPL_RACKNO']);
							$ttlwidth = $pdf->GetStringWidth($achar[0]);
							if($ttlwidth > 20){
								$ukuranfont = 7.5;
								while($ttlwidth>20){
									$pdf->SetFont('Arial','',$ukuranfont);
									$ttlwidth=$pdf->GetStringWidth($achar[0]);
									$ukuranfont = $ukuranfont - 0.5;
								}
							}
							$pdf->Text(3.5, $cury+3,$achar[0]);
							$pdf->SetFont('Arial','',8);
							$pdf->Text(3.5, $cury+6,$achar[1]);
						} else {
							$pdf->Cell(20,$td_h,$r['SPL_RACKNO'],1,0,'L');
						}
						$lebar =  $pdf->GetStringWidth(trim($r['SPL_ORDERNO']))+17;
						$clebar =  $pdf->GetStringWidth(trim($r['SPL_ORDERNO']))+16;
						$strx =$wd2col - ($lebar+3);
						if(($i%2)>0){							
							$pdf->Code128($wd2col -80 +2 ,$cury+1.5,trim($r['SPL_ORDERNO']),$clebar,3);
							$pdf->Cell(80,$td_h,$r['SPL_ORDERNO'],1,0,'R');
							$pdf->SetFont('Arial','',4);
							$pdf->Text($wd2col -5, $cury+1.5,$r['SPL_PROCD']);
							$pdf->SetFont('Arial','',8);
						} else {							
							$pdf->Code128($strx,$cury+1.5,trim($r['SPL_ORDERNO']),$clebar,3);
							$pdf->Cell(80,$td_h,$r['SPL_ORDERNO'],1,0,'L');
							$pdf->SetFont('Arial','',4);
							$pdf->Text($wd2col -79, $cury+1.5,$r['SPL_PROCD']);
							$pdf->SetFont('Arial','',8);
						}
						$ttlwidth = $pdf->GetStringWidth(trim($r['SPL_ITMCD']));
						if($ttlwidth > 25){
							$ukuranfont = 7.5;
							while($ttlwidth>24){
								$pdf->SetFont('Arial','',$ukuranfont);
								$ttlwidth=$pdf->GetStringWidth(trim($r['SPL_ITMCD']));
								$ukuranfont = $ukuranfont - 0.5;
							}
						}
						$pdf->Cell(25,$td_h,trim($r['SPL_ITMCD']),1,0,'L');
						$pdf->SetFont('Arial','',8);
						$ttlwidth = $pdf->GetStringWidth(trim($r['MITM_SPTNO']));
						if($ttlwidth > 39){
							$ukuranfont = 7.5;
							while($ttlwidth>25){
								$pdf->SetFont('Arial','',$ukuranfont);
								$ttlwidth=$pdf->GetStringWidth(trim($r['MITM_SPTNO']));
								$ukuranfont = $ukuranfont - 0.5;
							}
						}						
						$pdf->Cell(40,$td_h,trim($r['MITM_SPTNO']),1,0,'L');	
						$pdf->SetFont('Arial','',8);
						$pdf->Cell(15,$td_h,number_format($r['TTLREQ']),1,0,'R');
						$pdf->Cell(5,$td_h,'',1,0,'L');
					}
					$cury+=7;
					$i++;
				}
			}
			$cury+=10;
		}
		#end group

		// $pdf->SetFont('Arial','B',7);
		// $cury=22;
		// $isleft = true;
		// $cury+=$isleft ? 9:1;
		// $pdf->SetXY(3,$cury);
		// $pdf->Cell(20,4,'No Rak',1,0,'L');
		// $pdf->Cell(80,4,'Machine No',1,0,'C');		
		// $pdf->Cell(25,4,'Part No',1,0,'L');
		// $pdf->Cell(40,4,'Part Name',1,0,'L');	
		// $pdf->Cell(15,4,'Req',1,0,'R');
		// $pdf->Cell(5,4,'',1,0,'L');	
		// $wd2col = 3+20+80;
		// $cury+=4;
		// $td_h=7;

		// $i=1;
		// foreach($rs as $r){
		// 	if($r['TTLREQ']>0){			
		// 		if(($cury+10)>$hgt_p){
		// 			$pdf->AddPage();				
		// 			$pdf->SetFont('Arial','',6);
		// 			$clebar = $pdf->GetStringWidth($cpsn)+40;
		// 			$pdf->Code128(3, 4,$cpsn, $clebar,4);		
		// 			$pdf->Text(3,11,$cpsn);
		// 			$clebar = $pdf->GetStringWidth($ccat)+17;
		// 			$pdf->Code128(170, 4,trim($ccat),$clebar,4);		
		// 			$pdf->Text(170,11,$ccat);
		// 			$clebar = $pdf->GetStringWidth($cline)+17;
		// 			$pdf->Code128(3, 13,$cline, $clebar, 4);
		// 			$pdf->Text(3,20,$cline);
		// 			$clebar = $pdf->GetStringWidth($cfedr)+17;
		// 			$pdf->Code128(170, 13,$cfedr, $clebar, 4);
		// 			$pdf->Text(170,20,$cfedr);
		// 			$pdf->SetXY(90,4);
		// 			$pdf->SetFont('Arial','BU',10);
		// 			$pdf->Cell(35,4,'Picking Instruction',0,0,'C');
		// 			$pdf->SetXY(100,15);
		// 			$pdf->SetFont('Arial','',6);
		// 			$pdf->Cell(15,4,'Page '.$pdf->PageNo().' / {nb}',1,0,'R');
		// 			$pdf->SetFont('Arial','B',7);
		// 			// $pdf->SetXY(3,21);
		// 			// $pdf->Cell(14,4,'No Rak',1,0,'L');
		// 			// $pdf->Cell(80,4,'Machine No',1,0,'C');		
		// 			// $pdf->Cell(25,4,'Part No',1,0,'L');
		// 			// $pdf->Cell(40,4,'Part Name',1,0,'L');				
		// 			// $pdf->Cell(15,4,'Req',1,0,'R');
		// 			// $pdf->Cell(5,4,'',1,0,'L');	
					
		// 			// $cury = 25;
		// 			$cury=22;
		// 			$isleft = true;
		// 			for($j=0;$j<$xWOcount; $j++){#count($cwos)
		// 				if( ($j % 2)==0){
		// 					$pdf->SetXY(3,$cury);
		// 					$pdf->Cell(50,4,'Model',1,0,'L');
		// 					$pdf->Cell(40,4,'Job',1,0,'L');
		// 					$pdf->Cell(10,4,'Lot Size',1,0,'C');
		// 					$pdf->SetXY(3,$cury+4);
		// 					$ttlwidth = $pdf->GetStringWidth($cmodels[$j]);
		// 					if($ttlwidth > 50){
		// 						$ukuranfont = 6.5;
		// 						while($ttlwidth>50){
		// 							$pdf->SetFont('Arial','',$ukuranfont);
		// 							$ttlwidth=$pdf->GetStringWidth($cmodels[$j]);
		// 							$ukuranfont = $ukuranfont - 0.5;
		// 						}
		// 					}
		// 					$pdf->Cell(50,4,$cmodels[$j],1,0,'L');
		// 					$pdf->SetFont('Arial','B',7);
		// 					$pdf->Cell(40,4,$cwos[$j],1,0,'L');
		// 					$pdf->Cell(10,4,number_format($clotsize[$j]),1,0,'L');
		// 					$isleft = true;
		// 				} else {
		// 					$pdf->SetXY(105,$cury);
		// 					$pdf->Cell(50,4,'Model',1,0,'L');
		// 					$pdf->Cell(40,4,'Job',1,0,'L');
		// 					$pdf->Cell(10,4,'Lot Size',1,0,'C');
		// 					$pdf->SetXY(105,$cury+4);
		// 					$ttlwidth = $pdf->GetStringWidth($cmodels[$j]);
		// 					if($ttlwidth > 50){
		// 						$ukuranfont = 6.5;
		// 						while($ttlwidth>50){
		// 							$pdf->SetFont('Arial','',$ukuranfont);
		// 							$ttlwidth=$pdf->GetStringWidth($cmodels[$j]);
		// 							$ukuranfont = $ukuranfont - 0.5;
		// 						}
		// 					}
		// 					$pdf->Cell(50,4,$cmodels[$j],1,0,'L');
		// 					$pdf->SetFont('Arial','B',7);
		// 					$pdf->Cell(40,4,$cwos[$j],1,0,'L');
		// 					$pdf->Cell(10,4,number_format($clotsize[$j]),1,0,'L');
		// 					$cury+=8;
		// 					$isleft=false;
		// 				}
		// 			}

		// 			$cury+=$isleft ? 9:1;
		// 			$pdf->SetXY(3,$cury);
		// 			$pdf->Cell(20,4,'No Rak',1,0,'L');
		// 			$pdf->Cell(80,4,'Machine No',1,0,'C');		
		// 			$pdf->Cell(25,4,'Part No',1,0,'L');
		// 			$pdf->Cell(40,4,'Part Name',1,0,'L');	
		// 			$pdf->Cell(15,4,'Req',1,0,'R');
		// 			$pdf->Cell(5,4,'',1,0,'L');	
		// 			$wd2col = 3+20+80;
		// 			$cury+=4;
		// 		}
		// 		$pdf->SetFont('Arial','',8);
		// 		$pdf->SetXY(3,$cury);
				
		// 		if(strpos($r['SPL_RACKNO'], '-')!== false){
		// 			$pdf->Cell(20,$td_h,'',1,0,'L');//$r['SPL_RACKNO']
		// 			$achar = explode('-',$r['SPL_RACKNO']);
		// 			$ttlwidth = $pdf->GetStringWidth($achar[0]);
		// 			if($ttlwidth > 20){
		// 				$ukuranfont = 7.5;
		// 				while($ttlwidth>20){
		// 					$pdf->SetFont('Arial','',$ukuranfont);
		// 					$ttlwidth=$pdf->GetStringWidth($achar[0]);
		// 					$ukuranfont = $ukuranfont - 0.5;
		// 				}
		// 			}
		// 			$pdf->Text(3.5, $cury+3,$achar[0]);
		// 			$pdf->SetFont('Arial','',8);
		// 			$pdf->Text(3.5, $cury+6,$achar[1]);
		// 		} else {
		// 			$pdf->Cell(20,$td_h,$r['SPL_RACKNO'],1,0,'L');
		// 		}
		// 		$lebar =  $pdf->GetStringWidth(trim($r['SPL_ORDERNO']))+17;
		// 		$clebar =  $pdf->GetStringWidth(trim($r['SPL_ORDERNO']))+16;
		// 		$strx =$wd2col - ($lebar+3);
		// 		if(($i%2)>0){
		// 			//$pdf->Code39(19,$cury+0.5,trim($r['SPL_ORDERNO']),0.5,3);
		// 			$pdf->Code128($wd2col -80 +2 ,$cury+1.5,trim($r['SPL_ORDERNO']),$clebar,3);
		// 			$pdf->Cell(80,$td_h,$r['SPL_ORDERNO'],1,0,'R');
		// 			$pdf->SetFont('Arial','',4);
		// 			$pdf->Text($wd2col -5, $cury+1.5,$r['SPL_PROCD']);
		// 			$pdf->SetFont('Arial','',8);
		// 		} else {
		// 			//$pdf->Code39($strx,$cury+0.5,trim($r['SPL_ORDERNO']),0.5,3);
		// 			$pdf->Code128($strx,$cury+1.5,trim($r['SPL_ORDERNO']),$clebar,3);
		// 			$pdf->Cell(80,$td_h,$r['SPL_ORDERNO'],1,0,'L');
		// 			$pdf->SetFont('Arial','',4);
		// 			$pdf->Text($wd2col -79, $cury+1.5,$r['SPL_PROCD']);
		// 			$pdf->SetFont('Arial','',8);
		// 		}
		// 		$ttlwidth = $pdf->GetStringWidth(trim($r['SPL_ITMCD']));
		// 		if($ttlwidth > 25){
		// 			$ukuranfont = 7.5;
		// 			while($ttlwidth>24){
		// 				$pdf->SetFont('Arial','',$ukuranfont);
		// 				$ttlwidth=$pdf->GetStringWidth(trim($r['SPL_ITMCD']));
		// 				$ukuranfont = $ukuranfont - 0.5;
		// 			}
		// 		}
		// 		$pdf->Cell(25,$td_h,trim($r['SPL_ITMCD']),1,0,'L');
		// 		$pdf->SetFont('Arial','',8);
		// 		$ttlwidth = $pdf->GetStringWidth(trim($r['MITM_SPTNO']));
		// 		if($ttlwidth > 39){
		// 			$ukuranfont = 7.5;
		// 			while($ttlwidth>25){
		// 				$pdf->SetFont('Arial','',$ukuranfont);
		// 				$ttlwidth=$pdf->GetStringWidth(trim($r['MITM_SPTNO']));
		// 				$ukuranfont = $ukuranfont - 0.5;
		// 			}
		// 		}
		// 		//$reqdis = ($r['TTLSCN'] > $r['TTLREQ'] ? 0: $r['TTLREQ']-$r['TTLSCN']);
		// 		$pdf->Cell(40,$td_h,trim($r['MITM_SPTNO']),1,0,'L');	
		// 		$pdf->SetFont('Arial','',8);
		// 		$pdf->Cell(15,$td_h,number_format($r['TTLREQ']),1,0,'R');//-
		// 		$pdf->Cell(5,$td_h,'',1,0,'L');	
		// 		$cury+=$td_h;$i++;
		// 	}
		// }
		$pdf->Output('I','KIT Doc  '.$cpsn.'.pdf');
	}

	function printresult(){
		if(!isset($_COOKIE["CKPSI_DPSN"]) && !isset($_COOKIE["CKPSI_DCAT"]) && !isset($_COOKIE["CKPSI_DLNE"]) && !isset($_COOKIE["CKPSI_DFDR"]) ){
			exit('no data to be found');
		}
		date_default_timezone_set('Asia/Jakarta');
		$currdate = date('d/m/Y');
		$currrtime = date('H:i:s');
		$cpsn = $_COOKIE["CKPSI_DPSN"];
		$ccat = $_COOKIE["CKPSI_DCAT"];
		$cline = $_COOKIE["CKPSI_DLNE"];
		$cfedr = $_COOKIE["CKPSI_DFDR"];
		$cprocess = '';
		$cissu ='';
		$rs = $this->SPL_mod->selectby4par_result($cpsn, $ccat, $cline, $cfedr);
		if(count($rs)==0){
			die('Please choose Category, Line and F/R first');
		}
		$dataw = ['RETSCN_SPLDOC' => $cpsn, 'RETSCN_CAT' => $ccat, 'RETSCN_LINE' => $cline, 'RETSCN_FEDR' => $cfedr];
		$rsret = $this->SPLRET_mod->selectby_filter($dataw);		
		$dataw = ['SPLSCN_DOC' => $cpsn, 'SPLSCN_CAT' => $ccat , 'SPLSCN_LINE' => $cline, 'SPLSCN_FEDR' => $cfedr];
		$rsdetail = $this->SPLSCN_mod->selectby_filter($dataw);
		$d_prc = [];
		$d_mc = [];
		$d_machine = [];
		$d_item = [];
		$d_qty = [];
		$d_qtyg = [];

		$dr_machine = [];
		$dr_used = [];	
		$dr_item = [];
		$dr_qty = [];
		foreach($rsdetail as &$d){
			if(!array_key_exists("USED", $d)){
				$d["USED"] = false;
			}
		}
		unset($d);
		$prissudt = '';
		foreach($rs as &$r){
			$prissudt = date_create($r['PRISSUDT']);
			$think = true;
			while($think){
				$grasp = false;
				foreach($rsdetail as $d){
					if( ( trim($r['SPL_ORDERNO']) == trim($d['SPLSCN_ORDERNO']) ) && (trim($r['SPL_ITMCD']) == trim($d['SPLSCN_ITMCD']) ) && $d['USED']==false){
						$grasp = true; break;
					}
				}
				if($grasp){
					foreach($rsdetail as &$d){
						if( ( trim($r['SPL_ORDERNO']) == trim($d['SPLSCN_ORDERNO']) ) && (trim($r['SPL_ITMCD']) == trim($d['SPLSCN_ITMCD']) ) && $d['USED']==false){
							$think2 = true;
							while($think2){
								if($r['TTLREQ'] > $r['TTLSCN']){
									if($d['USED']==false){
										if(count($d_machine)==0){
											$d_prc[] = trim($r['SPL_PROCD']);
											$d_mc[] = trim($r['SPL_MC']);
											$d_machine[] = trim($d['SPLSCN_ORDERNO']);
											$d_item[] = trim($d['SPLSCN_ITMCD']);
											$d_qty[] = $d['SPLSCN_QTY'];
											$d_qtyg[] = 1;	
											$r['TTLSCN'] += $d['SPLSCN_QTY'];
											$d['USED'] = true;
										} else {
											$ttlroww = count($d_machine);
											$isfound = false;
											for($i = 0; $i<$ttlroww; $i++){
												if( ($d_mc[$i] ==  trim($r['SPL_MC']) ) && ($d_machine[$i] == trim($r['SPL_ORDERNO'])) &&  ($d_item[$i] == trim($r['SPL_ITMCD'])) 
													&& ($d_qty[$i] == $d['SPLSCN_QTY']) && ($d_prc[$i] == trim($r['SPL_PROCD']))  ){
													$r['TTLSCN']+=$d['SPLSCN_QTY'];
													$d['USED'] = true;
													$isfound=true;
													$d_qtyg[$i]++;
													break;
												}
											}
											if(!$isfound){
												$d_prc[] = trim($r['SPL_PROCD']);
												$d_mc[] = trim($r['SPL_MC']);
												$d_machine[] = trim($d['SPLSCN_ORDERNO']);
												$d_item[] = trim($d['SPLSCN_ITMCD']);
												$d_qty[] = $d['SPLSCN_QTY'];
												$d_qtyg[] = 1;	
												$r['TTLSCN'] += $d['SPLSCN_QTY'];
												$d['USED'] = true;
											}
										}
									} else {
										$think2=false;
									}
								} else {
									$think2=false;
									$think=false;
								}								
							}							
						}
					}
					unset($d);
				} else {
					$think = false;
				}
			}
		}
		unset($r);

		//start 2nd loop
		foreach($rsdetail as &$d){
			if($d['USED']==false){
				foreach($rs as &$r){
					if( ( trim($r['SPL_ORDERNO']) == trim($d['SPLSCN_ORDERNO']) ) && (trim($r['SPL_ITMCD']) == trim($d['SPLSCN_ITMCD']) )){
						$ttlroww = count($d_machine);
						$isfound = false;
						for($i = 0; $i<$ttlroww; $i++){
							if( ($d_mc[$i] ==  trim($r['SPL_MC']) ) && ($d_machine[$i] == trim($r['SPL_ORDERNO'])) &&  ($d_item[$i] == trim($r['SPL_ITMCD'])) 
								&& ($d_qty[$i] == $d['SPLSCN_QTY']) && ($d_prc[$i] == trim($r['SPL_PROCD']))  ){
								$r['TTLSCN']+=$d['SPLSCN_QTY'];
								$d['USED'] = true;
								$isfound=true;
								$d_qtyg[$i]++;
								break;
							}
						}
						if(!$isfound){
							$d_prc[] = trim($r['SPL_PROCD']);
							$d_mc[] = trim($r['SPL_MC']);
							$d_machine[] = trim($d['SPLSCN_ORDERNO']);
							$d_item[] = trim($d['SPLSCN_ITMCD']);
							$d_qty[] = $d['SPLSCN_QTY'];
							$d_qtyg[] = 1;	
							$r['TTLSCN'] += $d['SPLSCN_QTY'];
							$d['USED'] = true;
						}
					}
				}
				unset($r);
			}
		}
		unset($d);
		//end 2nd loop
		foreach($rsret as $r){
			if(count($dr_machine)==0){
				$dr_machine[] = trim($r['RETSCN_ORDERNO']);
				$dr_item[] = trim($r['RETSCN_ITMCD']);
				$dr_qty[] = $r['RETSCN_QTYAFT'];
				$dr_used[] = false;
			} else {
				$isfound = false;
				$ttldata = count($dr_machine);
				for($i=0;$i<$ttldata;$i++){
					if( ($dr_machine[$i] == trim($r['RETSCN_ORDERNO'])) && ($d_item[$i] == trim($r['RETSCN_ITMCD'])) ){
						$dr_qty[$i] += $r['RETSCN_QTYAFT'];
						$isfound = true; break;
					}
				}
				if(!$isfound){
					$dr_machine[] = trim($r['RETSCN_ORDERNO']);
					$dr_item[] = trim($r['RETSCN_ITMCD']);
					$dr_qty[] = $r['RETSCN_QTYAFT'];
					$dr_used[] = false;
				}
			}
		}
		$mc_count = count($dr_machine);
		
		if(substr($cpsn,0,3) == 'PR-'){
			$pdf = new PDF_Code39e128('L','mm','A4');
			$pdf->AliasNbPages();
			//header
			$pdf->AddPage();
			$hgt_p = $pdf->GetPageHeight();
			$wid_p = $pdf->GetPageWidth();
			$pdf->SetAutoPageBreak(true,1);
			$pdf->SetMargins(0,0);
			
			$pdf->SetFont('Arial','B',7);
			$pdf->Text(7,9,"MEX9PSN - Print PSN Pick List");
			$pdf->Text(119,9,"WMS");
			$pdf->Text(162,9,"PT. SMT INDONESIA");
			$pdf->Text(237,9,$currdate);
			$pdf->Text(251,9,$currrtime);
			$pdf->SetXY(280,6);
			$pdf->Cell(15,4,'Page '.$pdf->PageNo().' of {nb}',0,0,'R');
			$pdf->Line(7,10,7+283,10);
			$pdf->SetFont('Arial','UB',9);
			$pdf->Text(7,14,"PART SUPPLY LIST AND MFG. LOT NUMBER TRACIBILITY");
			$clebar = $pdf->GetStringWidth($cpsn)+40;
			$pdf->Code128(7, 16,$cpsn, $clebar,4);
			$pdf->SetFont('Arial','',4);	
			$pdf->Text(7,21.5,$cpsn);
			$clebar = $pdf->GetStringWidth($ccat)+20;
			$pdf->Code128(90, 16,$ccat, $clebar,4);			
			$pdf->Text(90,21.5,$ccat);
			$clebar = $pdf->GetStringWidth($cline)+20;
			$pdf->Code128(7, 23,$cline, $clebar,4);			
			$pdf->Text(7,28.5,$cline);
			$clebar = $pdf->GetStringWidth($cfedr)+20;
			$pdf->Code128(90, 23,$cfedr, $clebar,4);			
			$pdf->Text(90,28.5,$cfedr);
			$pdf->SetFont('Arial','',8);
			$pdf->SetXY(170,12);
			$pdf->MultiCell(119.77,4,"*Dilarang untuk menulis manual, part code dan part name. \n**Actual Return lebih besar Logical harus hitung ulang. \n***Actual Return lebih besar Logical harus lapor ke leader.\n****Laporkan ke leader bila ada hal aneh atau ragu-ragu.",1);
			//h3
			$pdf->SetFont('Arial','',8);
			$pdf->SetXY(7,30);
			$pdf->Cell(15,4,'Trans No',1,0,'L');
			$pdf->SetFont('Arial','B',8);
			$pdf->Cell(50,4,$cpsn,1,0,'C');
			$pdf->SetFont('Arial','',8);
			$pdf->Cell(15,4,'Date',1,0,'L');
			$pdf->SetFont('Arial','B',8);			
			$pdf->Cell(20,4,date_format($prissudt,"d-M-Y"),1,0,'C');
			$pdf->SetFont('Arial','',8);
			$pdf->Cell(20,4,'Line',1,0,'L');
			$pdf->SetFont('Arial','B',8);
			$pdf->Cell(20,4,$cline,1,0,'C');
			$pdf->SetXY(7,34);
			$pdf->SetFont('Arial','',8);
			$pdf->Cell(15,4,'Process',1,0,'L');
			$pdf->SetFont('Arial','B',8);
			$pdf->Cell(50,4,'_',1,0,'C');
			$pdf->SetFont('Arial','',8);
			$pdf->Cell(15,4,'Cat',1,0,'L');
			$pdf->SetFont('Arial','B',8);
			$pdf->Cell(20,4,$ccat,1,0,'C');
			$pdf->SetFont('Arial','',8);
			$pdf->Cell(20,4,'Feeder LOC',1,0,'L');
			$pdf->SetFont('Arial','B',8);
			$pdf->Cell(20,4,$cfedr,1,0,'C');
			//end h3
			//h4
			$pdf->SetFont('Arial','B',8);
			$pdf->SetXY(7,40);
			$pdf->Cell(6,4,'No',1,0,'C');
			$pdf->Cell(39,4,'Model',1,0,'C');
			$pdf->Cell(35,4,'Job No',1,0,'C');
			$pdf->Cell(15,4,'Lot Size',1,0,'C');
			//r
			$pdf->Cell(6,4,'No',1,0,'C');
			$pdf->Cell(39,4,'Model',1,0,'C');
			$pdf->Cell(35,4,'Job No',1,0,'C');
			$pdf->Cell(15,4,'Lot Size',1,0,'C');
			//r
			$pdf->Cell(6,4,'No',1,0,'C');
			$pdf->Cell(39,4,'Model',1,0,'C');
			$pdf->Cell(35,4,'Job No',1,0,'C');
			$pdf->Cell(15,4,'Lot Size',1,0,'C');
	
			$pdf->SetFont('Arial','',8);
			$pdf->SetXY(7,44);
			$pdf->Cell(6,4,'1',1,0,'C');			
			$pdf->Cell(39,4,'',1,0,'C');
			$pdf->SetFont('Arial','',8);
			$pdf->Cell(35,4,'',1,0,'C');
			$pdf->Cell(15,4,'',1,0,'C');
			// //r2
			$pdf->Cell(6,4,'2',1,0,'C');			
			$pdf->Cell(39,4,'',1,0,'C');
			$pdf->SetFont('Arial','',8);
			$pdf->Cell(35,4,'',1,0,'C');
			$pdf->Cell(15,4,'',1,0,'C');
			// //r2
			$pdf->Cell(6,4,'3',1,0,'C');			
			$pdf->Cell(39,4,'',1,0,'C');
			$pdf->SetFont('Arial','',8);
			$pdf->Cell(35,4,'',1,0,'C');
			$pdf->Cell(15,4,'',1,0,'C');
	
			$pdf->SetFont('Arial','B',8);
			$pdf->SetXY(7,48);
			$pdf->Cell(6,4,'No',1,0,'C');
			$pdf->Cell(39,4,'Model',1,0,'C');
			$pdf->Cell(35,4,'Job No',1,0,'C');
			$pdf->Cell(15,4,'Lot Size',1,0,'C');
			//r
			$pdf->Cell(6,4,'No',1,0,'C');
			$pdf->Cell(39,4,'Model',1,0,'C');
			$pdf->Cell(35,4,'Job No',1,0,'C');
			$pdf->Cell(15,4,'Lot Size',1,0,'C');
			//r
			$pdf->Cell(6,4,'No',1,0,'C');
			$pdf->Cell(39,4,'Model',1,0,'C');
			$pdf->Cell(35,4,'Job No',1,0,'C');
			$pdf->Cell(15,4,'Lot Size',1,0,'C');
			
			$pdf->SetFont('Arial','',8);
			$pdf->SetXY(7,52);
			$pdf->Cell(6,4,'4',1,0,'C');			
			$pdf->Cell(39,4,'',1,0,'C');
			$pdf->SetFont('Arial','',8);
			$pdf->Cell(35,4,'',1,0,'C');
			$pdf->Cell(15,4,'' ,1,0,'C');
			// //r2
			$pdf->Cell(6,4,'5',1,0,'C');			
			$pdf->Cell(39,4,'',1,0,'C');
			$pdf->SetFont('Arial','',8);
			$pdf->Cell(35,4,'',1,0,'C');
			$pdf->Cell(15,4,'',1,0,'C');
			// //r2
			$pdf->Cell(6,4,'6',1,0,'C');			
			$pdf->Cell(39,4,'',1,0,'C');
			$pdf->SetFont('Arial','',8);
			$pdf->Cell(35,4,'',1,0,'C');
			$pdf->Cell(15,4,'',1,0,'C');
			
			$pdf->SetFont('Arial','B',8);
			$pdf->SetXY(7,56);
			$pdf->Cell(6,4,'No',1,0,'C');
			$pdf->Cell(39,4,'Model',1,0,'C');
			$pdf->Cell(35,4,'Job No',1,0,'C');
			$pdf->Cell(15,4,'Lot Size',1,0,'C');
			//r
			$pdf->Cell(6,4,'No',1,0,'C');
			$pdf->Cell(39,4,'Model',1,0,'C');
			$pdf->Cell(35,4,'Job No',1,0,'C');
			$pdf->Cell(15,4,'Lot Size',1,0,'C');
			//r
			$pdf->Cell(6,4,'No',1,0,'C');
			$pdf->Cell(39,4,'Model',1,0,'C');
			$pdf->Cell(35,4,'Job No',1,0,'C');
			$pdf->Cell(15,4,'Lot Size',1,0,'C');
			
			$pdf->SetFont('Arial','',8);
			$pdf->SetXY(7,60);
			$pdf->Cell(6,4,'7',1,0,'C');			
			$pdf->Cell(39,4,'',1,0,'C');
			$pdf->SetFont('Arial','',8);
			$pdf->Cell(35,4,'',1,0,'C');
			$pdf->Cell(15,4,'',1,0,'C');
			// //r2
			$pdf->Cell(6,4,'8',1,0,'C');			
			$pdf->Cell(39,4,'',1,0,'C');
			$pdf->SetFont('Arial','',8);
			$pdf->Cell(35,4,'',1,0,'C');
			$pdf->Cell(15,4,'' ,1,0,'C');
			// //r2
			$pdf->Cell(6,4,'9',1,0,'C');			
			$pdf->Cell(39,4,'',1,0,'C');
			$pdf->SetFont('Arial','',8);
			$pdf->Cell(35,4,'',1,0,'C');
			$pdf->Cell(15,4, '' ,1,0,'C');
			$pdf->SetFont('Arial','',7);
			$pdf->SetXY(7,65);
			$pdf->MultiCell(14.32,12,'No. Urut','1','C');
			$pdf->SetXY(21.32,65);
			$pdf->MultiCell(12.84,12,'No. Rak','1','C');
			$pdf->SetXY(34.16,65);
			$pdf->MultiCell(30.62,12,'Part Code','1','C');
			$pdf->SetXY(64.78,65);
			$pdf->MultiCell(42.97,12,'Part Name','1','C');
			$pdf->SetXY(107.75,65);
			$pdf->MultiCell(8.15,6,'Qty Use','1','C');
			$pdf->SetXY(115.9,65);
			$pdf->MultiCell(7.41,12,'M/S','1','C');
			$pdf->SetXY(123.31,65);
			$pdf->MultiCell(16.05,12,'Qty Req','1','C');
			$pdf->SetXY(139.36,65);
			$pdf->MultiCell(100.73,6,'Issue Qty (PPC)','1','C');
			$pdf->SetXY(139.36,65+6);
			$pdf->MultiCell(12.59,6,'1','1','C');
			$pdf->SetXY(151.95,65+6);
			$pdf->MultiCell(12.59,6,'2','1','C');
			$pdf->SetXY(164.54,65+6);
			$pdf->MultiCell(12.59,6,'3','1','C');
			$pdf->SetXY(177.13,65+6);
			$pdf->MultiCell(12.59,6,'4','1','C');
			$pdf->SetXY(189.72,65+6);
			$pdf->MultiCell(12.59,6,'5','1','C');
			$pdf->SetXY(202.31,65+6);
			$pdf->MultiCell(12.59,6,'6','1','C');
			$pdf->SetXY(214.9,65+6);
			$pdf->MultiCell(12.59,6,'7','1','C');
			$pdf->SetXY(227.49,65+6);
			$pdf->MultiCell(12.59,6,'8','1','C');
			$pdf->SetXY(240.08,65);
			$pdf->MultiCell(15.80,12,'Total Issue','1','C');
			$pdf->SetXY(255.88,65);
			$pdf->MultiCell(15.06,4,'Logical Balance PPC','1','C');
			$pdf->SetXY(270.94,65);
			$pdf->MultiCell(17.29,6,'Actual Return (PPC)','1','C');
			$footerstring = 'IF FINISH PRODUCTION JOB, PLEASE RETURN THIS PART SUPPLY LIST';
			$strwidth = $pdf->GetStringWidth($footerstring)+2;
			$pdf->SetXY(($wid_p/2) - ($strwidth/2), $hgt_p-10);
			$pdf->Cell($strwidth,5,$footerstring,0,'C');
			$cury = 77;			
			//end header
			$tempstr = $cprocess;
			foreach($rs as $r){
				if($tempstr!=trim($r['SPL_PROCD'])){
					//header
					$pdf->AddPage();
					$hgt_p = $pdf->GetPageHeight();
					$wid_p = $pdf->GetPageWidth();
					$pdf->SetAutoPageBreak(true,1);
					$pdf->SetMargins(0,0);
					
					$pdf->SetFont('Arial','B',7);
					$pdf->Text(7,9,"MEX9PSN - Print PSN Pick List");
					$pdf->Text(119,9,"WMS");
					$pdf->Text(162,9,"PT. SMT INDONESIA");
					$pdf->Text(237,9,$currdate);
					$pdf->Text(251,9,$currrtime);
					$pdf->SetXY(280,6);
					$pdf->Cell(15,4,'Page '.$pdf->PageNo().' of {nb}',0,0,'R');
					$pdf->Line(7,10,7+283,10);
					$pdf->SetFont('Arial','UB',9);
					$pdf->Text(7,14,"PART SUPPLY LIST AND MFG. LOT NUMBER TRACIBILITY");
					$clebar = $pdf->GetStringWidth($cpsn)+40;
					$pdf->Code128(7, 16,$cpsn, $clebar,4);
					$pdf->SetFont('Arial','',4);	
					$pdf->Text(7,21.5,$cpsn);
					$clebar = $pdf->GetStringWidth($ccat)+20;
					$pdf->Code128(90, 16,$ccat, $clebar,4);			
					$pdf->Text(90,21.5,$ccat);
					$clebar = $pdf->GetStringWidth($cline)+20;
					$pdf->Code128(7, 23,$cline, $clebar,4);			
					$pdf->Text(7,28.5,$cline);
					$clebar = $pdf->GetStringWidth($cfedr)+20;
					$pdf->Code128(90, 23,$cfedr, $clebar,4);	
					$pdf->Text(90,28.5,$cfedr);
					$pdf->SetFont('Arial','',8);
					$pdf->SetXY(170,12);
					$pdf->MultiCell(119.77,4,"*Dilarang untuk menulis manual, part code dan part name. \n**Actual Return lebih besar Logical harus hitung ulang. \n***Actual Return lebih besar Logical harus lapor ke leader.\n****Laporkan ke leader bila ada hal aneh atau ragu-ragu.",1);
					//h3
					$pdf->SetFont('Arial','',8);
					$pdf->SetXY(7,30);
					$pdf->Cell(15,4,'Trans No',1,0,'L');
					$pdf->SetFont('Arial','B',8);
					$pdf->Cell(50,4,$cpsn,1,0,'C');
					$pdf->SetFont('Arial','',8);
					$pdf->Cell(15,4,'Date',1,0,'L');
					$pdf->SetFont('Arial','B',8);
					$pdf->Cell(20,4,date_format($prissudt,"d-M-Y"),1,0,'C');
					$pdf->SetFont('Arial','',8);
					$pdf->Cell(20,4,'Line',1,0,'L');
					$pdf->SetFont('Arial','B',8);
					$pdf->Cell(20,4,$cline,1,0,'C');
					$pdf->SetXY(7,34);
					$pdf->SetFont('Arial','',8);
					$pdf->Cell(15,4,'Process',1,0,'L');
					$pdf->SetFont('Arial','B',8);
					$pdf->Cell(50,4,$r['SPL_PROCD'],1,0,'C');
					$pdf->SetFont('Arial','',8);
					$pdf->Cell(15,4,'Cat',1,0,'L');
					$pdf->SetFont('Arial','B',8);
					$pdf->Cell(20,4,$ccat,1,0,'C');
					$pdf->SetFont('Arial','',8);
					$pdf->Cell(20,4,'Feeder LOC',1,0,'L');
					$pdf->SetFont('Arial','B',8);
					$pdf->Cell(20,4,$cfedr,1,0,'C');
					//end h3
					//h4
					$pdf->SetFont('Arial','B',8);
					$pdf->SetXY(7,40);
					$pdf->Cell(6,4,'No',1,0,'C');
					$pdf->Cell(39,4,'Model',1,0,'C');
					$pdf->Cell(35,4,'Job No',1,0,'C');
					$pdf->Cell(15,4,'Lot Size',1,0,'C');
					//r
					$pdf->Cell(6,4,'No',1,0,'C');
					$pdf->Cell(39,4,'Model',1,0,'C');
					$pdf->Cell(35,4,'Job No',1,0,'C');
					$pdf->Cell(15,4,'Lot Size',1,0,'C');
					//r
					$pdf->Cell(6,4,'No',1,0,'C');
					$pdf->Cell(39,4,'Model',1,0,'C');
					$pdf->Cell(35,4,'Job No',1,0,'C');
					$pdf->Cell(15,4,'Lot Size',1,0,'C');
	
					$pdf->SetFont('Arial','',8);
					$pdf->SetXY(7,44);
					$pdf->Cell(6,4,'1',1,0,'C');					
					$pdf->Cell(39,4,'',1,0,'C');
					$pdf->Cell(35,4,'',1,0,'C');
					$pdf->Cell(15,4,'',1,0,'C');
					// //r2
					$pdf->Cell(6,4,'2',1,0,'C');					
					$pdf->Cell(39,4,'',1,0,'C');
					$pdf->SetFont('Arial','',8);
					$pdf->Cell(35,4,'',1,0,'C');
					$pdf->Cell(15,4,'',1,0,'C');
					// //r2
					$pdf->Cell(6,4,'3',1,0,'C');					
					$pdf->Cell(39,4,'',1,0,'C');
					$pdf->SetFont('Arial','',8);
					$pdf->Cell(35,4,'',1,0,'C');
					$pdf->Cell(15,4,'',1,0,'C');
	
					$pdf->SetFont('Arial','B',8);
					$pdf->SetXY(7,48);
					$pdf->Cell(6,4,'No',1,0,'C');
					$pdf->Cell(39,4,'Model',1,0,'C');
					$pdf->Cell(35,4,'Job No',1,0,'C');
					$pdf->Cell(15,4,'Lot Size',1,0,'C');
					//r
					$pdf->Cell(6,4,'No',1,0,'C');
					$pdf->Cell(39,4,'Model',1,0,'C');
					$pdf->Cell(35,4,'Job No',1,0,'C');
					$pdf->Cell(15,4,'Lot Size',1,0,'C');
					//r
					$pdf->Cell(6,4,'No',1,0,'C');
					$pdf->Cell(39,4,'Model',1,0,'C');
					$pdf->Cell(35,4,'Job No',1,0,'C');
					$pdf->Cell(15,4,'Lot Size',1,0,'C');
					
					$pdf->SetFont('Arial','',8);
					$pdf->SetXY(7,52);
					$pdf->Cell(6,4,'4',1,0,'C');					
					$pdf->Cell(39,4,'',1,0,'C');
					$pdf->SetFont('Arial','',8);
					$pdf->Cell(35,4,'',1,0,'C');
					$pdf->Cell(15,4,'',1,0,'C');
					// //r2
					$pdf->Cell(6,4,'5',1,0,'C');					
					$pdf->Cell(39,4,'',1,0,'C');
					$pdf->SetFont('Arial','',8);
					$pdf->Cell(35,4,'',1,0,'C');
					$pdf->Cell(15,4,'',1,0,'C');
					// //r2
					$pdf->Cell(6,4,'6',1,0,'C');					
					$pdf->Cell(39,4,'',1,0,'C');
					$pdf->SetFont('Arial','',8);
					$pdf->Cell(35,4,'',1,0,'C');
					$pdf->Cell(15,4,'',1,0,'C');
					
					$pdf->SetFont('Arial','B',8);
					$pdf->SetXY(7,56);
					$pdf->Cell(6,4,'No',1,0,'C');
					$pdf->Cell(39,4,'Model',1,0,'C');
					$pdf->Cell(35,4,'Job No',1,0,'C');
					$pdf->Cell(15,4,'Lot Size',1,0,'C');
					//r
					$pdf->Cell(6,4,'No',1,0,'C');
					$pdf->Cell(39,4,'Model',1,0,'C');
					$pdf->Cell(35,4,'Job No',1,0,'C');
					$pdf->Cell(15,4,'Lot Size',1,0,'C');
					//r
					$pdf->Cell(6,4,'No',1,0,'C');
					$pdf->Cell(39,4,'Model',1,0,'C');
					$pdf->Cell(35,4,'Job No',1,0,'C');
					$pdf->Cell(15,4,'Lot Size',1,0,'C');
					
					$pdf->SetFont('Arial','',8);
					$pdf->SetXY(7,60);
					$pdf->Cell(6,4,'7',1,0,'C');					
					$pdf->Cell(39,4,'',1,0,'C');
					$pdf->SetFont('Arial','',8);
					$pdf->Cell(35,4,'',1,0,'C');
					$pdf->Cell(15,4,'',1,0,'C');
					// //r2
					$pdf->Cell(6,4,'8',1,0,'C');					
					$pdf->Cell(39,4,'',1,0,'C');
					$pdf->SetFont('Arial','',8);
					$pdf->Cell(35,4,'',1,0,'C');
					$pdf->Cell(15,4,'',1,0,'C');
					// //r2
					$pdf->Cell(6,4,'9',1,0,'C');					
					$pdf->Cell(39,4,'',1,0,'C');
					$pdf->SetFont('Arial','',8);
					$pdf->Cell(35,4,'',1,0,'C');
					$pdf->Cell(15,4,'',1,0,'C');
					$pdf->SetFont('Arial','',7);
					$pdf->SetXY(7,65);
					$pdf->MultiCell(14.32,12,'No. Urut','1','C');
					$pdf->SetXY(21.32,65);
					$pdf->MultiCell(12.84,12,'No. Rak','1','C');
					$pdf->SetXY(34.16,65);
					$pdf->MultiCell(30.62,12,'Part Code','1','C');
					$pdf->SetXY(64.78,65);
					$pdf->MultiCell(42.97,12,'Part Name','1','C');
					$pdf->SetXY(107.75,65);
					$pdf->MultiCell(8.15,6,'Qty Use','1','C');
					$pdf->SetXY(115.9,65);
					$pdf->MultiCell(7.41,12,'M/S','1','C');
					$pdf->SetXY(123.31,65);
					$pdf->MultiCell(16.05,12,'Qty Req','1','C');
					$pdf->SetXY(139.36,65);
					$pdf->MultiCell(100.73,6,'Issue Qty (PPC)','1','C');
					$pdf->SetXY(139.36,65+6);
					$pdf->MultiCell(12.59,6,'1','1','C');
					$pdf->SetXY(151.95,65+6);
					$pdf->MultiCell(12.59,6,'2','1','C');
					$pdf->SetXY(164.54,65+6);
					$pdf->MultiCell(12.59,6,'3','1','C');
					$pdf->SetXY(177.13,65+6);
					$pdf->MultiCell(12.59,6,'4','1','C');
					$pdf->SetXY(189.72,65+6);
					$pdf->MultiCell(12.59,6,'5','1','C');
					$pdf->SetXY(202.31,65+6);
					$pdf->MultiCell(12.59,6,'6','1','C');
					$pdf->SetXY(214.9,65+6);
					$pdf->MultiCell(12.59,6,'7','1','C');
					$pdf->SetXY(227.49,65+6);
					$pdf->MultiCell(12.59,6,'8','1','C');
					$pdf->SetXY(240.08,65);
					$pdf->MultiCell(15.80,12,'Total Issue','1','C');
					$pdf->SetXY(255.88,65);
					$pdf->MultiCell(15.06,4,'Logical Balance PPC','1','C');
					$pdf->SetXY(270.94,65);
					$pdf->MultiCell(17.29,6,'Actual Return (PPC)','1','C');				
					$pdf->SetXY(($wid_p/2) - ($strwidth/2), $hgt_p-10);
					$pdf->Cell($strwidth,5,$footerstring,0,'C');
					$cury = 77;
					//end header
					$tempstr = trim($r['SPL_PROCD']);
				} else {
					if(($cury+20)>$hgt_p){
						//header
						$pdf->AddPage();
						$hgt_p = $pdf->GetPageHeight();
						$wid_p = $pdf->GetPageWidth();
						$pdf->SetAutoPageBreak(true,1);
						$pdf->SetMargins(0,0);
						
						$pdf->SetFont('Arial','B',7);
						$pdf->Text(7,9,"MEX9PSN - Print PSN Pick List");
						$pdf->Text(119,9,"WMS");
						$pdf->Text(162,9,"PT. SMT INDONESIA");
						$pdf->Text(237,9,$currdate);
						$pdf->Text(251,9,$currrtime);
						$pdf->SetXY(280,6);
						$pdf->Cell(15,4,'Page '.$pdf->PageNo().' of {nb}',0,0,'R');
						$pdf->Line(7,10,7+283,10);
						$pdf->SetFont('Arial','UB',9);
						$pdf->Text(7,14,"PART SUPPLY LIST AND MFG. LOT NUMBER TRACIBILITY");
						$clebar = $pdf->GetStringWidth($cpsn)+40;
						$pdf->Code128(7, 16,$cpsn, $clebar,4);
						$pdf->SetFont('Arial','',4);	
						$pdf->Text(7,21.5,$cpsn);
						$clebar = $pdf->GetStringWidth($ccat)+20;
						$pdf->Code128(90, 16,$ccat, $clebar,4);			
						$pdf->Text(90,21.5,$ccat);
						$clebar = $pdf->GetStringWidth($cline)+20;
						$pdf->Code128(7, 23,$cline, $clebar,4);			
						$pdf->Text(7,28.5,$cline);
						$clebar = $pdf->GetStringWidth($cfedr)+20;
						$pdf->Code128(90, 23,$cfedr, $clebar,4);	
						$pdf->Text(90,28.5,$cfedr);
						$pdf->SetFont('Arial','',8);
						$pdf->SetXY(170,12);
						$pdf->MultiCell(119.77,4,"*Dilarang untuk menulis manual, part code dan part name. \n**Actual Return lebih besar Logical harus hitung ulang. \n***Actual Return lebih besar Logical harus lapor ke leader.\n****Laporkan ke leader bila ada hal aneh atau ragu-ragu.",1);
						//h3
						$pdf->SetFont('Arial','',8);
						$pdf->SetXY(7,30);
						$pdf->Cell(15,4,'Trans No',1,0,'L');
						$pdf->SetFont('Arial','B',8);
						$pdf->Cell(50,4,$cpsn,1,0,'C');
						$pdf->SetFont('Arial','',8);
						$pdf->Cell(15,4,'Date',1,0,'L');
						$pdf->SetFont('Arial','B',8);
						$pdf->Cell(20,4,date_format($prissudt,"d-M-Y"),1,0,'C');
						$pdf->SetFont('Arial','',8);
						$pdf->Cell(20,4,'Line',1,0,'L');
						$pdf->SetFont('Arial','B',8);
						$pdf->Cell(20,4,$cline,1,0,'C');
						$pdf->SetXY(7,34);
						$pdf->SetFont('Arial','',8);
						$pdf->Cell(15,4,'Process',1,0,'L');
						$pdf->SetFont('Arial','B',8);
						$pdf->Cell(50,4,$r['SPL_PROCD'],1,0,'C');
						$pdf->SetFont('Arial','',8);
						$pdf->Cell(15,4,'Cat',1,0,'L');
						$pdf->SetFont('Arial','B',8);
						$pdf->Cell(20,4,$ccat,1,0,'C');
						$pdf->SetFont('Arial','',8);
						$pdf->Cell(20,4,'Feeder LOC',1,0,'L');
						$pdf->SetFont('Arial','B',8);
						$pdf->Cell(20,4,$cfedr,1,0,'C');
						//end h3
						//h4
						$pdf->SetFont('Arial','B',8);
						$pdf->SetXY(7,40);
						$pdf->Cell(6,4,'No',1,0,'C');
						$pdf->Cell(39,4,'Model',1,0,'C');
						$pdf->Cell(35,4,'Job No',1,0,'C');
						$pdf->Cell(15,4,'Lot Size',1,0,'C');
						//r
						$pdf->Cell(6,4,'No',1,0,'C');
						$pdf->Cell(39,4,'Model',1,0,'C');
						$pdf->Cell(35,4,'Job No',1,0,'C');
						$pdf->Cell(15,4,'Lot Size',1,0,'C');
						//r
						$pdf->Cell(6,4,'No',1,0,'C');
						$pdf->Cell(39,4,'Model',1,0,'C');
						$pdf->Cell(35,4,'Job No',1,0,'C');
						$pdf->Cell(15,4,'Lot Size',1,0,'C');
		
						$pdf->SetFont('Arial','',8);
						$pdf->SetXY(7,44);
						$pdf->Cell(6,4,'1',1,0,'C');						
						$pdf->Cell(39,4,'',1,0,'C');
						$pdf->Cell(35,4,'',1,0,'C');
						$pdf->Cell(15,4,'',1,0,'C');
						// //r2
						$pdf->Cell(6,4,'2',1,0,'C');						
						$pdf->Cell(39,4,'',1,0,'C');
						$pdf->SetFont('Arial','',8);
						$pdf->Cell(35,4,'',1,0,'C');
						$pdf->Cell(15,4,'',1,0,'C');
						// //r2
						$pdf->Cell(6,4,'3',1,0,'C');
						
						$pdf->Cell(39,4,'',1,0,'C');
						$pdf->SetFont('Arial','',8);
						$pdf->Cell(35,4,'',1,0,'C');
						$pdf->Cell(15,4,'',1,0,'C');
		
						$pdf->SetFont('Arial','B',8);
						$pdf->SetXY(7,48);
						$pdf->Cell(6,4,'No',1,0,'C');
						$pdf->Cell(39,4,'Model',1,0,'C');
						$pdf->Cell(35,4,'Job No',1,0,'C');
						$pdf->Cell(15,4,'Lot Size',1,0,'C');
						//r
						$pdf->Cell(6,4,'No',1,0,'C');
						$pdf->Cell(39,4,'Model',1,0,'C');
						$pdf->Cell(35,4,'Job No',1,0,'C');
						$pdf->Cell(15,4,'Lot Size',1,0,'C');
						//r
						$pdf->Cell(6,4,'No',1,0,'C');
						$pdf->Cell(39,4,'Model',1,0,'C');
						$pdf->Cell(35,4,'Job No',1,0,'C');
						$pdf->Cell(15,4,'Lot Size',1,0,'C');
						
						$pdf->SetFont('Arial','',8);
						$pdf->SetXY(7,52);
						$pdf->Cell(6,4,'4',1,0,'C');						
						$pdf->Cell(39,4,'',1,0,'C');
						$pdf->SetFont('Arial','',8);
						$pdf->Cell(35,4,'',1,0,'C');
						$pdf->Cell(15,4,'' ,1,0,'C');
						// //r2
						$pdf->Cell(6,4,'5',1,0,'C');						
						$pdf->Cell(39,4,'',1,0,'C');
						$pdf->SetFont('Arial','',8);
						$pdf->Cell(35,4,'',1,0,'C');
						$pdf->Cell(15,4,'',1,0,'C');
						// //r2
						$pdf->Cell(6,4,'6',1,0,'C');						
						$pdf->Cell(39,4,'',1,0,'C');
						$pdf->SetFont('Arial','',8);
						$pdf->Cell(35,4,'',1,0,'C');
						$pdf->Cell(15,4,'',1,0,'C');
						
						$pdf->SetFont('Arial','B',8);
						$pdf->SetXY(7,56);
						$pdf->Cell(6,4,'No',1,0,'C');
						$pdf->Cell(39,4,'Model',1,0,'C');
						$pdf->Cell(35,4,'Job No',1,0,'C');
						$pdf->Cell(15,4,'Lot Size',1,0,'C');
						//r
						$pdf->Cell(6,4,'No',1,0,'C');
						$pdf->Cell(39,4,'Model',1,0,'C');
						$pdf->Cell(35,4,'Job No',1,0,'C');
						$pdf->Cell(15,4,'Lot Size',1,0,'C');
						//r
						$pdf->Cell(6,4,'No',1,0,'C');
						$pdf->Cell(39,4,'Model',1,0,'C');
						$pdf->Cell(35,4,'Job No',1,0,'C');
						$pdf->Cell(15,4,'Lot Size',1,0,'C');
						
						$pdf->SetFont('Arial','',8);
						$pdf->SetXY(7,60);
						$pdf->Cell(6,4,'7',1,0,'C');						
						$pdf->Cell(39,4,'',1,0,'C');
						$pdf->SetFont('Arial','',8);
						$pdf->Cell(35,4,'',1,0,'C');
						$pdf->Cell(15,4,'',1,0,'C');
						// //r2
						$pdf->Cell(6,4,'8',1,0,'C');
						
						$pdf->Cell(39,4,'',1,0,'C');
						$pdf->SetFont('Arial','',8);
						$pdf->Cell(35,4,'',1,0,'C');
						$pdf->Cell(15,4,'',1,0,'C');
						// //r2
						$pdf->Cell(6,4,'9',1,0,'C');						
						$pdf->Cell(39,4,'',1,0,'C');
						$pdf->SetFont('Arial','',8);
						$pdf->Cell(35,4,'',1,0,'C');
						$pdf->Cell(15,4,'' ,1,0,'C');
						$pdf->SetFont('Arial','',7);
						$pdf->SetXY(7,65);
						$pdf->MultiCell(14.32,12,'No. Urut','1','C');
						$pdf->SetXY(21.32,65);
						$pdf->MultiCell(12.84,12,'No. Rak','1','C');
						$pdf->SetXY(34.16,65);
						$pdf->MultiCell(30.62,12,'Part Code','1','C');
						$pdf->SetXY(64.78,65);
						$pdf->MultiCell(42.97,12,'Part Name','1','C');
						$pdf->SetXY(107.75,65);
						$pdf->MultiCell(8.15,6,'Qty Use','1','C');
						$pdf->SetXY(115.9,65);
						$pdf->MultiCell(7.41,12,'M/S','1','C');
						$pdf->SetXY(123.31,65);
						$pdf->MultiCell(16.05,12,'Qty Req','1','C');
						$pdf->SetXY(139.36,65);
						$pdf->MultiCell(100.73,6,'Issue Qty (PPC)','1','C');
						$pdf->SetXY(139.36,65+6);
						$pdf->MultiCell(12.59,6,'1','1','C');
						$pdf->SetXY(151.95,65+6);
						$pdf->MultiCell(12.59,6,'2','1','C');
						$pdf->SetXY(164.54,65+6);
						$pdf->MultiCell(12.59,6,'3','1','C');
						$pdf->SetXY(177.13,65+6);
						$pdf->MultiCell(12.59,6,'4','1','C');
						$pdf->SetXY(189.72,65+6);
						$pdf->MultiCell(12.59,6,'5','1','C');
						$pdf->SetXY(202.31,65+6);
						$pdf->MultiCell(12.59,6,'6','1','C');
						$pdf->SetXY(214.9,65+6);
						$pdf->MultiCell(12.59,6,'7','1','C');
						$pdf->SetXY(227.49,65+6);
						$pdf->MultiCell(12.59,6,'8','1','C');
						$pdf->SetXY(240.08,65);
						$pdf->MultiCell(15.80,12,'Total Issue','1','C');
						$pdf->SetXY(255.88,65);
						$pdf->MultiCell(15.06,4,'Logical Balance PPC','1','C');
						$pdf->SetXY(270.94,65);
						$pdf->MultiCell(17.29,6,'Actual Return (PPC)','1','C');				
						$pdf->SetXY(($wid_p/2) - ($strwidth/2), $hgt_p-10);
						$pdf->Cell($strwidth,5,$footerstring,0,'C');
						$cury = 77;
						//end header
					}
				}
				
				$pdf->SetXY(7,$cury);
				$pdf->MultiCell(14.32,12,trim($r['SPL_ORDERNO']),'1','C');
				$ttlwidth = $pdf->GetStringWidth(trim($r['SPL_RACKNO']));
				if($ttlwidth > 10){
					$ukuranfont = 6.5;
					while($ttlwidth>9.5){
						$pdf->SetFont('Arial','',$ukuranfont);
						$ttlwidth=$pdf->GetStringWidth($r['SPL_RACKNO']);
						$ukuranfont = $ukuranfont - 0.5;
					}
				}
				$pdf->SetXY(21.32,$cury);
				$pdf->MultiCell(12.84,12,trim($r['SPL_RACKNO']),'1','C');
				$pdf->SetFont('Arial','',7);			
				$pdf->SetXY(34.16,$cury);
				$pdf->MultiCell(30.62,12,trim($r['SPL_ITMCD']),'1','C');
				$pdf->SetXY(64.78,$cury);
				$pdf->MultiCell(42.97,12,trim($r['MITM_SPTNO']),'1','C');
				$pdf->SetXY(107.75,$cury);
				$pdf->MultiCell(8.15,12, number_format($r['SPL_QTYUSE']),'1','C');
				$pdf->SetXY(115.9,$cury);
				$pdf->MultiCell(7.41,12,trim($r['SPL_MS']),'1','C');
				$pdf->SetXY(123.31,$cury);
				$pdf->MultiCell(16.05,12,number_format($r['TTLREQ']),'1','R');			
				$pulskol =8;
				$kol =139.36;
				
				for($m=0;$m<count($d_machine);$m++){
					if(($d_prc[$m] == trim($r['SPL_PROCD'])) && ($d_mc[$m] == trim($r['SPL_MC'])) && ($d_machine[$m] == trim($r['SPL_ORDERNO'])) && ($d_item[$m] == trim($r['SPL_ITMCD'])) ){
						$pdf->SetXY($kol,$cury);
						$pdf->MultiCell(12.59,6, number_format($d_qty[$m])." \n $d_qtyg[$m]",'1','C');
						$pdf->SetDash(0.5,0.5);
						$pdf->Line($kol,$cury+6,$kol+12.59,$cury+6);
						$pdf->SetDash();
						$kol+=12.59;
						$pulskol--;
					}
				}
				for($j = 0; $j<$pulskol; $j++){
					$pdf->SetXY($kol,$cury);
					$pdf->MultiCell(12.59,6, " \n ",'1','C');
					$pdf->SetDash(0.5,0.5);
					$pdf->Line($kol,$cury+6,$kol+12.59,$cury+6);
					$pdf->SetDash();
					$kol+=12.59;
				}
				$pdf->SetXY(240.08,$cury);
				$pdf->MultiCell(15.80,12,number_format($r['TTLSCN']),'1','R');
				$logicbal = $r['TTLSCN'] - $r['TTLREQ'];
				$pdf->SetXY(255.88,$cury);
				$pdf->MultiCell(15.06,12, number_format($logicbal),'1','R');
				$retval = 0;
				
				for($j=0; $j<$mc_count;$j++ ){
					if( (trim($r['SPL_ORDERNO']) == $dr_machine[$j]) && (trim($r['SPL_ITMCD']) == $dr_item[$j] ) && $dr_used[$j] ==false ){
						$retval = $dr_qty[$j]; 
						$dr_used[$j]=true;
					}
				}
				$pdf->SetXY(270.94,$cury);
				$pdf->MultiCell(17.29,12,number_format($retval),'1','R');
				$cury+=12;
			}
		} else {
			$rshead = $this->SPL_mod->selecthead($cpsn, $cline, $cfedr);
			if(count($rshead)==0){
				die('Please choose category, line and F/R first');
			}									
				
			$cwos = [];
			$cmodels = [];
			$clotsize = [];
			foreach($rshead as $r){			
				if(count($cwos)==0){
					$cwos[] = trim($r['PPSN1_WONO']);
					$cmodels[] = trim($r['MITM_ITMD1']);
					$clotsize[] = trim($r['PPSN1_SIMQT']);
				} else {
					$ttlwo = count($cwos);
					$isexist = false;
					for($i=0;$i<$ttlwo;$i++){
						if($cwos[$i]==trim($r['PPSN1_WONO'])){
							$isexist =true;
						}
					}
					if(!$isexist){
						$cwos[] = trim($r['PPSN1_WONO']);
						$cmodels[] = trim($r['MITM_ITMD1']);
						$clotsize[] = trim($r['PPSN1_SIMQT']);
					}
				}
			}
			foreach($rshead as $r){
				$cprocess = trim($r['PPSN1_PROCD']); $cissu = date_create($r['PPSN1_ISUDT']);
				break;
			}
			
			$ttlwos = count($cwos);
			$sisa = 9-$ttlwos;		
			if($sisa>0){//FILL THE REST ARRAY SIZE WITH ''	 BECAUSE WE AVOID NULL
				for($i=0;$i<$sisa;$i++){
					$cwos[] = '';
					$cmodels[] = '';
					$clotsize[] = '';
				}
			}
			
			$pdf = new PDF_Code39e128('L','mm','A4');
			$pdf->AliasNbPages();
			//header
			$pdf->AddPage();
			$hgt_p = $pdf->GetPageHeight();
			$wid_p = $pdf->GetPageWidth();
			$pdf->SetAutoPageBreak(true,1);
			$pdf->SetMargins(0,0);
			
			$pdf->SetFont('Arial','B',7);
			$pdf->Text(7,9,"MEX9PSN - Print PSN Pick List");
			$pdf->Text(119,9,"WMS");
			$pdf->Text(162,9,"PT. SMT INDONESIA");
			$pdf->Text(237,9,$currdate);
			$pdf->Text(251,9,$currrtime);
			$pdf->SetXY(280,6);
			$pdf->Cell(15,4,'Page '.$pdf->PageNo().' of {nb}',0,0,'R');
			$pdf->Line(7,10,7+283,10);
			$pdf->SetFont('Arial','UB',9);
			$pdf->Text(7,14,"PART SUPPLY LIST AND MFG. LOT NUMBER TRACIBILITY");
			$pdf->Code39(7, 15,$cpsn);
			$pdf->SetFont('Arial','',4);	
			$pdf->Text(7,21.5,$cpsn);
			$pdf->Code39(90, 15,trim($ccat));		
			$pdf->Text(90,21.5,$ccat);
			$pdf->Code39(7, 22,$cline);
			$pdf->Text(7,28.5,$cline);
			$pdf->Code39(90, 22,$cfedr);
			$pdf->Text(90,28.5,$cfedr);
			$pdf->SetFont('Arial','',8);
			$pdf->SetXY(170,12);
			$pdf->MultiCell(119.77,4,"*Dilarang untuk menulis manual, part code dan part name. \n**Actual Return lebih besar Logical harus hitung ulang. \n***Actual Return lebih besar Logical harus lapor ke leader.\n****Laporkan ke leader bila ada hal aneh atau ragu-ragu.",1);
			//h3
			$pdf->SetFont('Arial','',8);
			$pdf->SetXY(7,30);
			$pdf->Cell(15,4,'Trans No',1,0,'L');
			$pdf->SetFont('Arial','B',8);
			$pdf->Cell(50,4,$cpsn,1,0,'C');
			$pdf->SetFont('Arial','',8);
			$pdf->Cell(15,4,'Date',1,0,'L');
			$pdf->SetFont('Arial','B',8);
			
			$pdf->Cell(20,4,date_format($cissu,"d-M-Y"),1,0,'C');
			$pdf->SetFont('Arial','',8);
			$pdf->Cell(20,4,'Line',1,0,'L');
			$pdf->SetFont('Arial','B',8);
			$pdf->Cell(20,4,$cline,1,0,'C');
			$pdf->SetXY(7,34);
			$pdf->SetFont('Arial','',8);
			$pdf->Cell(15,4,'Process',1,0,'L');
			$pdf->SetFont('Arial','B',8);
			$pdf->Cell(50,4,$cprocess,1,0,'C');
			$pdf->SetFont('Arial','',8);
			$pdf->Cell(15,4,'Cat',1,0,'L');
			$pdf->SetFont('Arial','B',8);
			$pdf->Cell(20,4,$ccat,1,0,'C');
			$pdf->SetFont('Arial','',8);
			$pdf->Cell(20,4,'Feeder LOC',1,0,'L');
			$pdf->SetFont('Arial','B',8);
			$pdf->Cell(20,4,$cfedr,1,0,'C');
			//end h3
			//h4
			$pdf->SetFont('Arial','B',8);
			$pdf->SetXY(7,40);
			$pdf->Cell(6,4,'No',1,0,'C');
			$pdf->Cell(39,4,'Model',1,0,'C');
			$pdf->Cell(35,4,'Job No',1,0,'C');
			$pdf->Cell(15,4,'Lot Size',1,0,'C');
			//r
			$pdf->Cell(6,4,'No',1,0,'C');
			$pdf->Cell(39,4,'Model',1,0,'C');
			$pdf->Cell(35,4,'Job No',1,0,'C');
			$pdf->Cell(15,4,'Lot Size',1,0,'C');
			//r
			$pdf->Cell(6,4,'No',1,0,'C');
			$pdf->Cell(39,4,'Model',1,0,'C');
			$pdf->Cell(35,4,'Job No',1,0,'C');
			$pdf->Cell(15,4,'Lot Size',1,0,'C');
	
			$pdf->SetFont('Arial','',8);
			$pdf->SetXY(7,44);
			$pdf->Cell(6,4,'1',1,0,'C');
			$ttlwidth = $pdf->GetStringWidth($cmodels[0]);
			if($ttlwidth > 39){
				$ukuranfont = 7.5;
				while($ttlwidth>38){
					$pdf->SetFont('Arial','',$ukuranfont);
					$ttlwidth=$pdf->GetStringWidth($cmodels[0]);
					$ukuranfont = $ukuranfont - 0.5;
				}
			}		
			$pdf->Cell(39,4,$cmodels[0],1,0,'C');
			$pdf->SetFont('Arial','',8);
			$pdf->Cell(35,4,$cwos[0],1,0,'C');
			$pdf->Cell(15,4,$clotsize[0] == '' ? '' : number_format($clotsize[0]),1,0,'C');
			// //r2
			$pdf->Cell(6,4,'2',1,0,'C');
			$ttlwidth = $pdf->GetStringWidth($cmodels[1]);
			if($ttlwidth > 39){
				$ukuranfont = 7.5;
				while($ttlwidth>38){
					$pdf->SetFont('Arial','',$ukuranfont);
					$ttlwidth=$pdf->GetStringWidth($cmodels[1]);
					$ukuranfont = $ukuranfont - 0.5;
				}
			}
			$pdf->Cell(39,4,$cmodels[1],1,0,'C');
			$pdf->SetFont('Arial','',8);
			$pdf->Cell(35,4,$cwos[1],1,0,'C');
			$pdf->Cell(15,4,$clotsize[1] == '' ? '' : number_format($clotsize[1]),1,0,'C');
			// //r2
			$pdf->Cell(6,4,'3',1,0,'C');
			$ttlwidth = $pdf->GetStringWidth($cmodels[2]);
			if($ttlwidth > 39){
				$ukuranfont = 7.5;
				while($ttlwidth>38){
					$pdf->SetFont('Arial','',$ukuranfont);
					$ttlwidth=$pdf->GetStringWidth($cmodels[2]);
					$ukuranfont = $ukuranfont - 0.5;
				}
			}
			$pdf->Cell(39,4,$cmodels[2],1,0,'C');
			$pdf->SetFont('Arial','',8);
			$pdf->Cell(35,4,$cwos[2],1,0,'C');
			$pdf->Cell(15,4,$clotsize[2] == '' ? '' : number_format($clotsize[2]),1,0,'C');
	
			$pdf->SetFont('Arial','B',8);
			$pdf->SetXY(7,48);
			$pdf->Cell(6,4,'No',1,0,'C');
			$pdf->Cell(39,4,'Model',1,0,'C');
			$pdf->Cell(35,4,'Job No',1,0,'C');
			$pdf->Cell(15,4,'Lot Size',1,0,'C');
			//r
			$pdf->Cell(6,4,'No',1,0,'C');
			$pdf->Cell(39,4,'Model',1,0,'C');
			$pdf->Cell(35,4,'Job No',1,0,'C');
			$pdf->Cell(15,4,'Lot Size',1,0,'C');
			//r
			$pdf->Cell(6,4,'No',1,0,'C');
			$pdf->Cell(39,4,'Model',1,0,'C');
			$pdf->Cell(35,4,'Job No',1,0,'C');
			$pdf->Cell(15,4,'Lot Size',1,0,'C');
			
			$pdf->SetFont('Arial','',8);
			$pdf->SetXY(7,52);
			$pdf->Cell(6,4,'4',1,0,'C');
			$ttlwidth = $pdf->GetStringWidth($cmodels[3]);
			if($ttlwidth > 39){
				$ukuranfont = 7.5;
				while($ttlwidth>38){
					$pdf->SetFont('Arial','',$ukuranfont);
					$ttlwidth=$pdf->GetStringWidth($cmodels[3]);
					$ukuranfont = $ukuranfont - 0.5;
				}
			}		
			$pdf->Cell(39,4,$cmodels[3],1,0,'C');
			$pdf->SetFont('Arial','',8);
			$pdf->Cell(35,4,$cwos[3],1,0,'C');
			$pdf->Cell(15,4,$clotsize[3]=='' ? '' :number_format($clotsize[3]),1,0,'C');
			// //r2
			$pdf->Cell(6,4,'5',1,0,'C');
			$ttlwidth = $pdf->GetStringWidth($cmodels[4]);
			if($ttlwidth > 39){
				$ukuranfont = 7.5;
				while($ttlwidth>38){
					$pdf->SetFont('Arial','',$ukuranfont);
					$ttlwidth=$pdf->GetStringWidth($cmodels[4]);
					$ukuranfont = $ukuranfont - 0.5;
				}
			}
			$pdf->Cell(39,4,$cmodels[4],1,0,'C');
			$pdf->SetFont('Arial','',8);
			$pdf->Cell(35,4,$cwos[4],1,0,'C');
			$pdf->Cell(15,4,$clotsize[4]==''?'': number_format($clotsize[4]),1,0,'C');
			// //r2
			$pdf->Cell(6,4,'6',1,0,'C');
			$ttlwidth = $pdf->GetStringWidth($cmodels[5]);
			if($ttlwidth > 39){
				$ukuranfont = 7.5;
				while($ttlwidth>38){
					$pdf->SetFont('Arial','',$ukuranfont);
					$ttlwidth=$pdf->GetStringWidth($cmodels[5]);
					$ukuranfont = $ukuranfont - 0.5;
				}
			}
			$pdf->Cell(39,4,$cmodels[5],1,0,'C');
			$pdf->SetFont('Arial','',8);
			$pdf->Cell(35,4,$cwos[5],1,0,'C');
			$pdf->Cell(15,4,$clotsize[5] =='' ? '' : number_format($clotsize[5]),1,0,'C');
			
			$pdf->SetFont('Arial','B',8);
			$pdf->SetXY(7,56);
			$pdf->Cell(6,4,'No',1,0,'C');
			$pdf->Cell(39,4,'Model',1,0,'C');
			$pdf->Cell(35,4,'Job No',1,0,'C');
			$pdf->Cell(15,4,'Lot Size',1,0,'C');
			//r
			$pdf->Cell(6,4,'No',1,0,'C');
			$pdf->Cell(39,4,'Model',1,0,'C');
			$pdf->Cell(35,4,'Job No',1,0,'C');
			$pdf->Cell(15,4,'Lot Size',1,0,'C');
			//r
			$pdf->Cell(6,4,'No',1,0,'C');
			$pdf->Cell(39,4,'Model',1,0,'C');
			$pdf->Cell(35,4,'Job No',1,0,'C');
			$pdf->Cell(15,4,'Lot Size',1,0,'C');
			
			$pdf->SetFont('Arial','',8);
			$pdf->SetXY(7,60);
			$pdf->Cell(6,4,'7',1,0,'C');
			$ttlwidth = $pdf->GetStringWidth($cmodels[6]);
			if($ttlwidth > 39){
				$ukuranfont = 7.5;
				while($ttlwidth>38){
					$pdf->SetFont('Arial','',$ukuranfont);
					$ttlwidth=$pdf->GetStringWidth($cmodels[6]);
					$ukuranfont = $ukuranfont - 0.5;
				}
			}		
			$pdf->Cell(39,4,$cmodels[6],1,0,'C');
			$pdf->SetFont('Arial','',8);
			$pdf->Cell(35,4,$cwos[6],1,0,'C');
			$pdf->Cell(15,4,$clotsize[6]=='' ? '' : number_format($clotsize[6]),1,0,'C');
			// //r2
			$pdf->Cell(6,4,'8',1,0,'C');
			$ttlwidth = $pdf->GetStringWidth($cmodels[7]);
			if($ttlwidth > 39){
				$ukuranfont = 7.5;
				while($ttlwidth>38){
					$pdf->SetFont('Arial','',$ukuranfont);
					$ttlwidth=$pdf->GetStringWidth($cmodels[7]);
					$ukuranfont = $ukuranfont - 0.5;
				}
			}
			$pdf->Cell(39,4,$cmodels[7],1,0,'C');
			$pdf->SetFont('Arial','',8);
			$pdf->Cell(35,4,$cwos[7],1,0,'C');
			$pdf->Cell(15,4,$clotsize[7]=='' ? '' : number_format($clotsize[7]),1,0,'C');
			// //r2
			$pdf->Cell(6,4,'9',1,0,'C');
			$ttlwidth = $pdf->GetStringWidth($cmodels[8]);
			if($ttlwidth > 39){
				$ukuranfont = 7.5;
				while($ttlwidth>38){
					$pdf->SetFont('Arial','',$ukuranfont);
					$ttlwidth=$pdf->GetStringWidth($cmodels[8]);
					$ukuranfont = $ukuranfont - 0.5;
				}
			}
			$pdf->Cell(39,4,$cmodels[8],1,0,'C');
			$pdf->SetFont('Arial','',8);
			$pdf->Cell(35,4,$cwos[8],1,0,'C');
			$pdf->Cell(15,4,$clotsize[8] =='' ? '' : number_format($clotsize[8]),1,0,'C');
			$pdf->SetFont('Arial','',7);
			$pdf->SetXY(7,65);
			$pdf->MultiCell(14.32,12,'No. Urut','1','C');
			$pdf->SetXY(21.32,65);
			$pdf->MultiCell(12.84,12,'No. Rak','1','C');
			$pdf->SetXY(34.16,65);
			$pdf->MultiCell(30.62,12,'Part Code','1','C');
			$pdf->SetXY(64.78,65);
			$pdf->MultiCell(42.97,12,'Part Name','1','C');
			$pdf->SetXY(107.75,65);
			$pdf->MultiCell(8.15,6,'Qty Use','1','C');
			$pdf->SetXY(115.9,65);
			$pdf->MultiCell(7.41,12,'M/S','1','C');
			$pdf->SetXY(123.31,65);
			$pdf->MultiCell(16.05,12,'Qty Req','1','C');
			$pdf->SetXY(139.36,65);
			$pdf->MultiCell(100.73,6,'Issue Qty (PPC)','1','C');
			$pdf->SetXY(139.36,65+6);
			$pdf->MultiCell(12.59,6,'1','1','C');
			$pdf->SetXY(151.95,65+6);
			$pdf->MultiCell(12.59,6,'2','1','C');
			$pdf->SetXY(164.54,65+6);
			$pdf->MultiCell(12.59,6,'3','1','C');
			$pdf->SetXY(177.13,65+6);
			$pdf->MultiCell(12.59,6,'4','1','C');
			$pdf->SetXY(189.72,65+6);
			$pdf->MultiCell(12.59,6,'5','1','C');
			$pdf->SetXY(202.31,65+6);
			$pdf->MultiCell(12.59,6,'6','1','C');
			$pdf->SetXY(214.9,65+6);
			$pdf->MultiCell(12.59,6,'7','1','C');
			$pdf->SetXY(227.49,65+6);
			$pdf->MultiCell(12.59,6,'8','1','C');
			$pdf->SetXY(240.08,65);
			$pdf->MultiCell(15.80,12,'Total Issue','1','C');
			$pdf->SetXY(255.88,65);
			$pdf->MultiCell(15.06,4,'Logical Balance PPC','1','C');
			$pdf->SetXY(270.94,65);
			$pdf->MultiCell(17.29,6,'Actual Return (PPC)','1','C');
			$footerstring = 'IF FINISH PRODUCTION JOB, PLEASE RETURN THIS PART SUPPLY LIST';
			$strwidth = $pdf->GetStringWidth($footerstring)+2;
			$pdf->SetXY(($wid_p/2) - ($strwidth/2), $hgt_p-10);
			$pdf->Cell($strwidth,5,$footerstring,0,'C');
			$cury = 77;
			
			//end header
			$tempstr = $cprocess;
			foreach($rs as $r){
				if($tempstr!=trim($r['SPL_PROCD'])){
					//header
					$pdf->AddPage();
					$hgt_p = $pdf->GetPageHeight();
					$wid_p = $pdf->GetPageWidth();
					$pdf->SetAutoPageBreak(true,1);
					$pdf->SetMargins(0,0);
					
					$pdf->SetFont('Arial','B',7);
					$pdf->Text(7,9,"MEX9PSN - Print PSN Pick List");
					$pdf->Text(119,9,"WMS");
					$pdf->Text(162,9,"PT. SMT INDONESIA");
					$pdf->Text(237,9,$currdate);
					$pdf->Text(251,9,$currrtime);
					$pdf->SetXY(280,6);
					$pdf->Cell(15,4,'Page '.$pdf->PageNo().' of {nb}',0,0,'R');
					$pdf->Line(7,10,7+283,10);
					$pdf->SetFont('Arial','UB',9);
					$pdf->Text(7,14,"PART SUPPLY LIST AND MFG. LOT NUMBER TRACIBILITY");
					$pdf->Code39(7, 15,$cpsn);
					$pdf->SetFont('Arial','',4);	
					$pdf->Text(7,21.5,$cpsn);
					$pdf->Code39(90, 15,trim($ccat));		
					$pdf->Text(90,21.5,$ccat);
					$pdf->Code39(7, 22,$cline);
					$pdf->Text(7,28.5,$cline);
					$pdf->Code39(90, 22,$cfedr);
					$pdf->Text(90,28.5,$cfedr);
					$pdf->SetFont('Arial','',8);
					$pdf->SetXY(170,12);
					$pdf->MultiCell(119.77,4,"*Dilarang untuk menulis manual, part code dan part name. \n**Actual Return lebih besar Logical harus hitung ulang. \n***Actual Return lebih besar Logical harus lapor ke leader.\n****Laporkan ke leader bila ada hal aneh atau ragu-ragu.",1);
					//h3
					$pdf->SetFont('Arial','',8);
					$pdf->SetXY(7,30);
					$pdf->Cell(15,4,'Trans No',1,0,'L');
					$pdf->SetFont('Arial','B',8);
					$pdf->Cell(50,4,$cpsn,1,0,'C');
					$pdf->SetFont('Arial','',8);
					$pdf->Cell(15,4,'Date',1,0,'L');
					$pdf->SetFont('Arial','B',8);
					$pdf->Cell(20,4,date_format($cissu,"d-M-Y"),1,0,'C');
					$pdf->SetFont('Arial','',8);
					$pdf->Cell(20,4,'Line',1,0,'L');
					$pdf->SetFont('Arial','B',8);
					$pdf->Cell(20,4,$cline,1,0,'C');
					$pdf->SetXY(7,34);
					$pdf->SetFont('Arial','',8);
					$pdf->Cell(15,4,'Process',1,0,'L');
					$pdf->SetFont('Arial','B',8);
					$pdf->Cell(50,4,$r['SPL_PROCD'],1,0,'C');
					$pdf->SetFont('Arial','',8);
					$pdf->Cell(15,4,'Cat',1,0,'L');
					$pdf->SetFont('Arial','B',8);
					$pdf->Cell(20,4,$ccat,1,0,'C');
					$pdf->SetFont('Arial','',8);
					$pdf->Cell(20,4,'Feeder LOC',1,0,'L');
					$pdf->SetFont('Arial','B',8);
					$pdf->Cell(20,4,$cfedr,1,0,'C');
					//end h3
					//h4
					$pdf->SetFont('Arial','B',8);
					$pdf->SetXY(7,40);
					$pdf->Cell(6,4,'No',1,0,'C');
					$pdf->Cell(39,4,'Model',1,0,'C');
					$pdf->Cell(35,4,'Job No',1,0,'C');
					$pdf->Cell(15,4,'Lot Size',1,0,'C');
					//r
					$pdf->Cell(6,4,'No',1,0,'C');
					$pdf->Cell(39,4,'Model',1,0,'C');
					$pdf->Cell(35,4,'Job No',1,0,'C');
					$pdf->Cell(15,4,'Lot Size',1,0,'C');
					//r
					$pdf->Cell(6,4,'No',1,0,'C');
					$pdf->Cell(39,4,'Model',1,0,'C');
					$pdf->Cell(35,4,'Job No',1,0,'C');
					$pdf->Cell(15,4,'Lot Size',1,0,'C');
	
					$pdf->SetFont('Arial','',8);
					$pdf->SetXY(7,44);
					$pdf->Cell(6,4,'1',1,0,'C');
					$ttlwidth = $pdf->GetStringWidth($cmodels[0]);
					if($ttlwidth > 39){
						$ukuranfont = 7.5;
						while($ttlwidth>38){
							$pdf->SetFont('Arial','',$ukuranfont);
							$ttlwidth=$pdf->GetStringWidth($cmodels[0]);
							$ukuranfont = $ukuranfont - 0.5;
						}
					}		
					$pdf->Cell(39,4,$cmodels[0],1,0,'C');
					$pdf->Cell(35,4,$cwos[0],1,0,'C');
					$pdf->Cell(15,4,number_format($clotsize[0]),1,0,'C');
					// //r2
					$pdf->Cell(6,4,'2',1,0,'C');
					$ttlwidth = $pdf->GetStringWidth($cmodels[1]);
					if($ttlwidth > 39){
						$ukuranfont = 7.5;
						while($ttlwidth>38){
							$pdf->SetFont('Arial','',$ukuranfont);
							$ttlwidth=$pdf->GetStringWidth($cmodels[1]);
							$ukuranfont = $ukuranfont - 0.5;
						}
					}
					$pdf->Cell(39,4,$cmodels[1],1,0,'C');
					$pdf->SetFont('Arial','',8);
					$pdf->Cell(35,4,$cwos[1],1,0,'C');
					$pdf->Cell(15,4,$clotsize[1] == '' ? '' :number_format($clotsize[1]),1,0,'C');
					// //r2
					$pdf->Cell(6,4,'3',1,0,'C');
					$ttlwidth = $pdf->GetStringWidth($cmodels[2]);
					if($ttlwidth > 39){
						$ukuranfont = 7.5;
						while($ttlwidth>38){
							$pdf->SetFont('Arial','',$ukuranfont);
							$ttlwidth=$pdf->GetStringWidth($cmodels[2]);
							$ukuranfont = $ukuranfont - 0.5;
						}
					}
					$pdf->Cell(39,4,$cmodels[2],1,0,'C');
					$pdf->SetFont('Arial','',8);
					$pdf->Cell(35,4,$cwos[2],1,0,'C');
					$pdf->Cell(15,4,$clotsize[2]=='' ? '' : number_format($clotsize[2]),1,0,'C');
	
					$pdf->SetFont('Arial','B',8);
					$pdf->SetXY(7,48);
					$pdf->Cell(6,4,'No',1,0,'C');
					$pdf->Cell(39,4,'Model',1,0,'C');
					$pdf->Cell(35,4,'Job No',1,0,'C');
					$pdf->Cell(15,4,'Lot Size',1,0,'C');
					//r
					$pdf->Cell(6,4,'No',1,0,'C');
					$pdf->Cell(39,4,'Model',1,0,'C');
					$pdf->Cell(35,4,'Job No',1,0,'C');
					$pdf->Cell(15,4,'Lot Size',1,0,'C');
					//r
					$pdf->Cell(6,4,'No',1,0,'C');
					$pdf->Cell(39,4,'Model',1,0,'C');
					$pdf->Cell(35,4,'Job No',1,0,'C');
					$pdf->Cell(15,4,'Lot Size',1,0,'C');
					
					$pdf->SetFont('Arial','',8);
					$pdf->SetXY(7,52);
					$pdf->Cell(6,4,'4',1,0,'C');
					$ttlwidth = $pdf->GetStringWidth($cmodels[3]);
					if($ttlwidth > 39){
						$ukuranfont = 7.5;
						while($ttlwidth>38){
							$pdf->SetFont('Arial','',$ukuranfont);
							$ttlwidth=$pdf->GetStringWidth($cmodels[3]);
							$ukuranfont = $ukuranfont - 0.5;
						}
					}		
					$pdf->Cell(39,4,$cmodels[3],1,0,'C');
					$pdf->SetFont('Arial','',8);
					$pdf->Cell(35,4,$cwos[3],1,0,'C');
					$pdf->Cell(15,4,$clotsize[3]=='' ? '' :number_format($clotsize[3]),1,0,'C');
					// //r2
					$pdf->Cell(6,4,'5',1,0,'C');
					$ttlwidth = $pdf->GetStringWidth($cmodels[4]);
					if($ttlwidth > 39){
						$ukuranfont = 7.5;
						while($ttlwidth>38){
							$pdf->SetFont('Arial','',$ukuranfont);
							$ttlwidth=$pdf->GetStringWidth($cmodels[4]);
							$ukuranfont = $ukuranfont - 0.5;
						}
					}
					$pdf->Cell(39,4,$cmodels[4],1,0,'C');
					$pdf->SetFont('Arial','',8);
					$pdf->Cell(35,4,$cwos[4],1,0,'C');
					$pdf->Cell(15,4,$clotsize[4]==''?'': number_format($clotsize[4]),1,0,'C');
					// //r2
					$pdf->Cell(6,4,'6',1,0,'C');
					$ttlwidth = $pdf->GetStringWidth($cmodels[5]);
					if($ttlwidth > 39){
						$ukuranfont = 7.5;
						while($ttlwidth>38){
							$pdf->SetFont('Arial','',$ukuranfont);
							$ttlwidth=$pdf->GetStringWidth($cmodels[5]);
							$ukuranfont = $ukuranfont - 0.5;
						}
					}
					$pdf->Cell(39,4,$cmodels[5],1,0,'C');
					$pdf->SetFont('Arial','',8);
					$pdf->Cell(35,4,$cwos[5],1,0,'C');
					$pdf->Cell(15,4,$clotsize[5] =='' ? '' : number_format($clotsize[5]),1,0,'C');
					
					$pdf->SetFont('Arial','B',8);
					$pdf->SetXY(7,56);
					$pdf->Cell(6,4,'No',1,0,'C');
					$pdf->Cell(39,4,'Model',1,0,'C');
					$pdf->Cell(35,4,'Job No',1,0,'C');
					$pdf->Cell(15,4,'Lot Size',1,0,'C');
					//r
					$pdf->Cell(6,4,'No',1,0,'C');
					$pdf->Cell(39,4,'Model',1,0,'C');
					$pdf->Cell(35,4,'Job No',1,0,'C');
					$pdf->Cell(15,4,'Lot Size',1,0,'C');
					//r
					$pdf->Cell(6,4,'No',1,0,'C');
					$pdf->Cell(39,4,'Model',1,0,'C');
					$pdf->Cell(35,4,'Job No',1,0,'C');
					$pdf->Cell(15,4,'Lot Size',1,0,'C');
					
					$pdf->SetFont('Arial','',8);
					$pdf->SetXY(7,60);
					$pdf->Cell(6,4,'7',1,0,'C');
					$ttlwidth = $pdf->GetStringWidth($cmodels[6]);
					if($ttlwidth > 39){
						$ukuranfont = 7.5;
						while($ttlwidth>38){
							$pdf->SetFont('Arial','',$ukuranfont);
							$ttlwidth=$pdf->GetStringWidth($cmodels[6]);
							$ukuranfont = $ukuranfont - 0.5;
						}
					}		
					$pdf->Cell(39,4,$cmodels[6],1,0,'C');
					$pdf->SetFont('Arial','',8);
					$pdf->Cell(35,4,$cwos[6],1,0,'C');
					$pdf->Cell(15,4,$clotsize[6]=='' ? '' : number_format($clotsize[6]),1,0,'C');
					// //r2
					$pdf->Cell(6,4,'8',1,0,'C');
					$ttlwidth = $pdf->GetStringWidth($cmodels[7]);
					if($ttlwidth > 39){
						$ukuranfont = 7.5;
						while($ttlwidth>38){
							$pdf->SetFont('Arial','',$ukuranfont);
							$ttlwidth=$pdf->GetStringWidth($cmodels[7]);
							$ukuranfont = $ukuranfont - 0.5;
						}
					}
					$pdf->Cell(39,4,$cmodels[7],1,0,'C');
					$pdf->SetFont('Arial','',8);
					$pdf->Cell(35,4,$cwos[7],1,0,'C');
					$pdf->Cell(15,4,$clotsize[7]=='' ? '' : number_format($clotsize[7]),1,0,'C');
					// //r2
					$pdf->Cell(6,4,'9',1,0,'C');
					$ttlwidth = $pdf->GetStringWidth($cmodels[8]);
					if($ttlwidth > 39){
						$ukuranfont = 7.5;
						while($ttlwidth>38){
							$pdf->SetFont('Arial','',$ukuranfont);
							$ttlwidth=$pdf->GetStringWidth($cmodels[8]);
							$ukuranfont = $ukuranfont - 0.5;
						}
					}
					$pdf->Cell(39,4,$cmodels[8],1,0,'C');
					$pdf->SetFont('Arial','',8);
					$pdf->Cell(35,4,$cwos[8],1,0,'C');
					$pdf->Cell(15,4,$clotsize[8] =='' ? '' : number_format($clotsize[8]),1,0,'C');
					$pdf->SetFont('Arial','',7);
					$pdf->SetXY(7,65);
					$pdf->MultiCell(14.32,12,'No. Urut','1','C');
					$pdf->SetXY(21.32,65);
					$pdf->MultiCell(12.84,12,'No. Rak','1','C');
					$pdf->SetXY(34.16,65);
					$pdf->MultiCell(30.62,12,'Part Code','1','C');
					$pdf->SetXY(64.78,65);
					$pdf->MultiCell(42.97,12,'Part Name','1','C');
					$pdf->SetXY(107.75,65);
					$pdf->MultiCell(8.15,6,'Qty Use','1','C');
					$pdf->SetXY(115.9,65);
					$pdf->MultiCell(7.41,12,'M/S','1','C');
					$pdf->SetXY(123.31,65);
					$pdf->MultiCell(16.05,12,'Qty Req','1','C');
					$pdf->SetXY(139.36,65);
					$pdf->MultiCell(100.73,6,'Issue Qty (PPC)','1','C');
					$pdf->SetXY(139.36,65+6);
					$pdf->MultiCell(12.59,6,'1','1','C');
					$pdf->SetXY(151.95,65+6);
					$pdf->MultiCell(12.59,6,'2','1','C');
					$pdf->SetXY(164.54,65+6);
					$pdf->MultiCell(12.59,6,'3','1','C');
					$pdf->SetXY(177.13,65+6);
					$pdf->MultiCell(12.59,6,'4','1','C');
					$pdf->SetXY(189.72,65+6);
					$pdf->MultiCell(12.59,6,'5','1','C');
					$pdf->SetXY(202.31,65+6);
					$pdf->MultiCell(12.59,6,'6','1','C');
					$pdf->SetXY(214.9,65+6);
					$pdf->MultiCell(12.59,6,'7','1','C');
					$pdf->SetXY(227.49,65+6);
					$pdf->MultiCell(12.59,6,'8','1','C');
					$pdf->SetXY(240.08,65);
					$pdf->MultiCell(15.80,12,'Total Issue','1','C');
					$pdf->SetXY(255.88,65);
					$pdf->MultiCell(15.06,4,'Logical Balance PPC','1','C');
					$pdf->SetXY(270.94,65);
					$pdf->MultiCell(17.29,6,'Actual Return (PPC)','1','C');				
					$pdf->SetXY(($wid_p/2) - ($strwidth/2), $hgt_p-10);
					$pdf->Cell($strwidth,5,$footerstring,0,'C');
					$cury = 77;
					//end header
					$tempstr = trim($r['SPL_PROCD']);
				} else {
					if(($cury+20)>$hgt_p){
						//header
						$pdf->AddPage();
						$hgt_p = $pdf->GetPageHeight();
						$wid_p = $pdf->GetPageWidth();
						$pdf->SetAutoPageBreak(true,1);
						$pdf->SetMargins(0,0);
						
						$pdf->SetFont('Arial','B',7);
						$pdf->Text(7,9,"MEX9PSN - Print PSN Pick List");
						$pdf->Text(119,9,"WMS");
						$pdf->Text(162,9,"PT. SMT INDONESIA");
						$pdf->Text(237,9,$currdate);
						$pdf->Text(251,9,$currrtime);
						$pdf->SetXY(280,6);
						$pdf->Cell(15,4,'Page '.$pdf->PageNo().' of {nb}',0,0,'R');
						$pdf->Line(7,10,7+283,10);
						$pdf->SetFont('Arial','UB',9);
						$pdf->Text(7,14,"PART SUPPLY LIST AND MFG. LOT NUMBER TRACIBILITY");
						$pdf->Code39(7, 15,$cpsn);
						$pdf->SetFont('Arial','',4);	
						$pdf->Text(7,21.5,$cpsn);
						$pdf->Code39(90, 15,trim($ccat));		
						$pdf->Text(90,21.5,$ccat);
						$pdf->Code39(7, 22,$cline);
						$pdf->Text(7,28.5,$cline);
						$pdf->Code39(90, 22,$cfedr);
						$pdf->Text(90,28.5,$cfedr);
						$pdf->SetFont('Arial','',8);
						$pdf->SetXY(170,12);
						$pdf->MultiCell(119.77,4,"*Dilarang untuk menulis manual, part code dan part name. \n**Actual Return lebih besar Logical harus hitung ulang. \n***Actual Return lebih besar Logical harus lapor ke leader.\n****Laporkan ke leader bila ada hal aneh atau ragu-ragu.",1);
						//h3
						$pdf->SetFont('Arial','',8);
						$pdf->SetXY(7,30);
						$pdf->Cell(15,4,'Trans No',1,0,'L');
						$pdf->SetFont('Arial','B',8);
						$pdf->Cell(50,4,$cpsn,1,0,'C');
						$pdf->SetFont('Arial','',8);
						$pdf->Cell(15,4,'Date',1,0,'L');
						$pdf->SetFont('Arial','B',8);
						$pdf->Cell(20,4,date_format($cissu,"d-M-Y"),1,0,'C');
						$pdf->SetFont('Arial','',8);
						$pdf->Cell(20,4,'Line',1,0,'L');
						$pdf->SetFont('Arial','B',8);
						$pdf->Cell(20,4,$cline,1,0,'C');
						$pdf->SetXY(7,34);
						$pdf->SetFont('Arial','',8);
						$pdf->Cell(15,4,'Process',1,0,'L');
						$pdf->SetFont('Arial','B',8);
						$pdf->Cell(50,4,$r['SPL_PROCD'],1,0,'C');
						$pdf->SetFont('Arial','',8);
						$pdf->Cell(15,4,'Cat',1,0,'L');
						$pdf->SetFont('Arial','B',8);
						$pdf->Cell(20,4,$ccat,1,0,'C');
						$pdf->SetFont('Arial','',8);
						$pdf->Cell(20,4,'Feeder LOC',1,0,'L');
						$pdf->SetFont('Arial','B',8);
						$pdf->Cell(20,4,$cfedr,1,0,'C');
						//end h3
						//h4
						$pdf->SetFont('Arial','B',8);
						$pdf->SetXY(7,40);
						$pdf->Cell(6,4,'No',1,0,'C');
						$pdf->Cell(39,4,'Model',1,0,'C');
						$pdf->Cell(35,4,'Job No',1,0,'C');
						$pdf->Cell(15,4,'Lot Size',1,0,'C');
						//r
						$pdf->Cell(6,4,'No',1,0,'C');
						$pdf->Cell(39,4,'Model',1,0,'C');
						$pdf->Cell(35,4,'Job No',1,0,'C');
						$pdf->Cell(15,4,'Lot Size',1,0,'C');
						//r
						$pdf->Cell(6,4,'No',1,0,'C');
						$pdf->Cell(39,4,'Model',1,0,'C');
						$pdf->Cell(35,4,'Job No',1,0,'C');
						$pdf->Cell(15,4,'Lot Size',1,0,'C');
		
						$pdf->SetFont('Arial','',8);
						$pdf->SetXY(7,44);
						$pdf->Cell(6,4,'1',1,0,'C');
						$ttlwidth = $pdf->GetStringWidth($cmodels[0]);
						if($ttlwidth > 39){
							$ukuranfont = 7.5;
							while($ttlwidth>38){
								$pdf->SetFont('Arial','',$ukuranfont);
								$ttlwidth=$pdf->GetStringWidth($cmodels[0]);
								$ukuranfont = $ukuranfont - 0.5;
							}
						}		
						$pdf->Cell(39,4,$cmodels[0],1,0,'C');
						$pdf->Cell(35,4,$cwos[0],1,0,'C');
						$pdf->Cell(15,4,number_format($clotsize[0]),1,0,'C');
						// //r2
						$pdf->Cell(6,4,'2',1,0,'C');
						$ttlwidth = $pdf->GetStringWidth($cmodels[1]);
						if($ttlwidth > 39){
							$ukuranfont = 7.5;
							while($ttlwidth>38){
								$pdf->SetFont('Arial','',$ukuranfont);
								$ttlwidth=$pdf->GetStringWidth($cmodels[1]);
								$ukuranfont = $ukuranfont - 0.5;
							}
						}
						$pdf->Cell(39,4,$cmodels[1],1,0,'C');
						$pdf->SetFont('Arial','',8);
						$pdf->Cell(35,4,$cwos[1],1,0,'C');
						$pdf->Cell(15,4,$clotsize[1] =='' ? '' : number_format($clotsize[1]),1,0,'C');
						// //r2
						$pdf->Cell(6,4,'3',1,0,'C');
						$ttlwidth = $pdf->GetStringWidth($cmodels[2]);
						if($ttlwidth > 39){
							$ukuranfont = 7.5;
							while($ttlwidth>38){
								$pdf->SetFont('Arial','',$ukuranfont);
								$ttlwidth=$pdf->GetStringWidth($cmodels[2]);
								$ukuranfont = $ukuranfont - 0.5;
							}
						}
						$pdf->Cell(39,4,$cmodels[2],1,0,'C');
						$pdf->SetFont('Arial','',8);
						$pdf->Cell(35,4,$cwos[2],1,0,'C');
						$pdf->Cell(15,4,$clotsize[2] =='' ? '' : number_format($clotsize[2]),1,0,'C');
		
						$pdf->SetFont('Arial','B',8);
						$pdf->SetXY(7,48);
						$pdf->Cell(6,4,'No',1,0,'C');
						$pdf->Cell(39,4,'Model',1,0,'C');
						$pdf->Cell(35,4,'Job No',1,0,'C');
						$pdf->Cell(15,4,'Lot Size',1,0,'C');
						//r
						$pdf->Cell(6,4,'No',1,0,'C');
						$pdf->Cell(39,4,'Model',1,0,'C');
						$pdf->Cell(35,4,'Job No',1,0,'C');
						$pdf->Cell(15,4,'Lot Size',1,0,'C');
						//r
						$pdf->Cell(6,4,'No',1,0,'C');
						$pdf->Cell(39,4,'Model',1,0,'C');
						$pdf->Cell(35,4,'Job No',1,0,'C');
						$pdf->Cell(15,4,'Lot Size',1,0,'C');
						
						$pdf->SetFont('Arial','',8);
						$pdf->SetXY(7,52);
						$pdf->Cell(6,4,'4',1,0,'C');
						$ttlwidth = $pdf->GetStringWidth($cmodels[3]);
						if($ttlwidth > 39){
							$ukuranfont = 7.5;
							while($ttlwidth>38){
								$pdf->SetFont('Arial','',$ukuranfont);
								$ttlwidth=$pdf->GetStringWidth($cmodels[3]);
								$ukuranfont = $ukuranfont - 0.5;
							}
						}		
						$pdf->Cell(39,4,$cmodels[3],1,0,'C');
						$pdf->SetFont('Arial','',8);
						$pdf->Cell(35,4,$cwos[3],1,0,'C');
						$pdf->Cell(15,4,$clotsize[3]=='' ? '' :number_format($clotsize[3]),1,0,'C');
						// //r2
						$pdf->Cell(6,4,'5',1,0,'C');
						$ttlwidth = $pdf->GetStringWidth($cmodels[4]);
						if($ttlwidth > 39){
							$ukuranfont = 7.5;
							while($ttlwidth>38){
								$pdf->SetFont('Arial','',$ukuranfont);
								$ttlwidth=$pdf->GetStringWidth($cmodels[4]);
								$ukuranfont = $ukuranfont - 0.5;
							}
						}
						$pdf->Cell(39,4,$cmodels[4],1,0,'C');
						$pdf->SetFont('Arial','',8);
						$pdf->Cell(35,4,$cwos[4],1,0,'C');
						$pdf->Cell(15,4,$clotsize[4]==''?'': number_format($clotsize[4]),1,0,'C');
						// //r2
						$pdf->Cell(6,4,'6',1,0,'C');
						$ttlwidth = $pdf->GetStringWidth($cmodels[5]);
						if($ttlwidth > 39){
							$ukuranfont = 7.5;
							while($ttlwidth>38){
								$pdf->SetFont('Arial','',$ukuranfont);
								$ttlwidth=$pdf->GetStringWidth($cmodels[5]);
								$ukuranfont = $ukuranfont - 0.5;
							}
						}
						$pdf->Cell(39,4,$cmodels[5],1,0,'C');
						$pdf->SetFont('Arial','',8);
						$pdf->Cell(35,4,$cwos[5],1,0,'C');
						$pdf->Cell(15,4,$clotsize[5] =='' ? '' : number_format($clotsize[5]),1,0,'C');
						
						$pdf->SetFont('Arial','B',8);
						$pdf->SetXY(7,56);
						$pdf->Cell(6,4,'No',1,0,'C');
						$pdf->Cell(39,4,'Model',1,0,'C');
						$pdf->Cell(35,4,'Job No',1,0,'C');
						$pdf->Cell(15,4,'Lot Size',1,0,'C');
						//r
						$pdf->Cell(6,4,'No',1,0,'C');
						$pdf->Cell(39,4,'Model',1,0,'C');
						$pdf->Cell(35,4,'Job No',1,0,'C');
						$pdf->Cell(15,4,'Lot Size',1,0,'C');
						//r
						$pdf->Cell(6,4,'No',1,0,'C');
						$pdf->Cell(39,4,'Model',1,0,'C');
						$pdf->Cell(35,4,'Job No',1,0,'C');
						$pdf->Cell(15,4,'Lot Size',1,0,'C');
						
						$pdf->SetFont('Arial','',8);
						$pdf->SetXY(7,60);
						$pdf->Cell(6,4,'7',1,0,'C');
						$ttlwidth = $pdf->GetStringWidth($cmodels[6]);
						if($ttlwidth > 39){
							$ukuranfont = 7.5;
							while($ttlwidth>38){
								$pdf->SetFont('Arial','',$ukuranfont);
								$ttlwidth=$pdf->GetStringWidth($cmodels[6]);
								$ukuranfont = $ukuranfont - 0.5;
							}
						}		
						$pdf->Cell(39,4,$cmodels[6],1,0,'C');
						$pdf->SetFont('Arial','',8);
						$pdf->Cell(35,4,$cwos[6],1,0,'C');
						$pdf->Cell(15,4,$clotsize[6]=='' ? '' : number_format($clotsize[6]),1,0,'C');
						// //r2
						$pdf->Cell(6,4,'8',1,0,'C');
						$ttlwidth = $pdf->GetStringWidth($cmodels[7]);
						if($ttlwidth > 39){
							$ukuranfont = 7.5;
							while($ttlwidth>38){
								$pdf->SetFont('Arial','',$ukuranfont);
								$ttlwidth=$pdf->GetStringWidth($cmodels[7]);
								$ukuranfont = $ukuranfont - 0.5;
							}
						}
						$pdf->Cell(39,4,$cmodels[7],1,0,'C');
						$pdf->SetFont('Arial','',8);
						$pdf->Cell(35,4,$cwos[7],1,0,'C');
						$pdf->Cell(15,4,$clotsize[7]=='' ? '' : number_format($clotsize[7]),1,0,'C');
						// //r2
						$pdf->Cell(6,4,'9',1,0,'C');
						$ttlwidth = $pdf->GetStringWidth($cmodels[8]);
						if($ttlwidth > 39){
							$ukuranfont = 7.5;
							while($ttlwidth>38){
								$pdf->SetFont('Arial','',$ukuranfont);
								$ttlwidth=$pdf->GetStringWidth($cmodels[8]);
								$ukuranfont = $ukuranfont - 0.5;
							}
						}
						$pdf->Cell(39,4,$cmodels[8],1,0,'C');
						$pdf->SetFont('Arial','',8);
						$pdf->Cell(35,4,$cwos[8],1,0,'C');
						$pdf->Cell(15,4,$clotsize[8] =='' ? '' : number_format($clotsize[8]),1,0,'C');
						$pdf->SetFont('Arial','',7);
						$pdf->SetXY(7,65);
						$pdf->MultiCell(14.32,12,'No. Urut','1','C');
						$pdf->SetXY(21.32,65);
						$pdf->MultiCell(12.84,12,'No. Rak','1','C');
						$pdf->SetXY(34.16,65);
						$pdf->MultiCell(30.62,12,'Part Code','1','C');
						$pdf->SetXY(64.78,65);
						$pdf->MultiCell(42.97,12,'Part Name','1','C');
						$pdf->SetXY(107.75,65);
						$pdf->MultiCell(8.15,6,'Qty Use','1','C');
						$pdf->SetXY(115.9,65);
						$pdf->MultiCell(7.41,12,'M/S','1','C');
						$pdf->SetXY(123.31,65);
						$pdf->MultiCell(16.05,12,'Qty Req','1','C');
						$pdf->SetXY(139.36,65);
						$pdf->MultiCell(100.73,6,'Issue Qty (PPC)','1','C');
						$pdf->SetXY(139.36,65+6);
						$pdf->MultiCell(12.59,6,'1','1','C');
						$pdf->SetXY(151.95,65+6);
						$pdf->MultiCell(12.59,6,'2','1','C');
						$pdf->SetXY(164.54,65+6);
						$pdf->MultiCell(12.59,6,'3','1','C');
						$pdf->SetXY(177.13,65+6);
						$pdf->MultiCell(12.59,6,'4','1','C');
						$pdf->SetXY(189.72,65+6);
						$pdf->MultiCell(12.59,6,'5','1','C');
						$pdf->SetXY(202.31,65+6);
						$pdf->MultiCell(12.59,6,'6','1','C');
						$pdf->SetXY(214.9,65+6);
						$pdf->MultiCell(12.59,6,'7','1','C');
						$pdf->SetXY(227.49,65+6);
						$pdf->MultiCell(12.59,6,'8','1','C');
						$pdf->SetXY(240.08,65);
						$pdf->MultiCell(15.80,12,'Total Issue','1','C');
						$pdf->SetXY(255.88,65);
						$pdf->MultiCell(15.06,4,'Logical Balance PPC','1','C');
						$pdf->SetXY(270.94,65);
						$pdf->MultiCell(17.29,6,'Actual Return (PPC)','1','C');				
						$pdf->SetXY(($wid_p/2) - ($strwidth/2), $hgt_p-10);
						$pdf->Cell($strwidth,5,$footerstring,0,'C');
						$cury = 77;
						//end header
					}
				}
				
				$pdf->SetXY(7,$cury);
				$pdf->MultiCell(14.32,12,trim($r['SPL_ORDERNO']),'1','C');
				$ttlwidth = $pdf->GetStringWidth(trim($r['SPL_RACKNO']));
				if($ttlwidth > 10){
					$ukuranfont = 6.5;
					while($ttlwidth>9.5){
						$pdf->SetFont('Arial','',$ukuranfont);
						$ttlwidth=$pdf->GetStringWidth($r['SPL_RACKNO']);
						$ukuranfont = $ukuranfont - 0.5;
					}
				}
				$pdf->SetXY(21.32,$cury);
				$pdf->MultiCell(12.84,12,trim($r['SPL_RACKNO']),'1','C');
				$pdf->SetFont('Arial','',7);			
				$pdf->SetXY(34.16,$cury);
				$pdf->MultiCell(30.62,12,trim($r['SPL_ITMCD']),'1','C');
				$pdf->SetXY(64.78,$cury);
				$pdf->MultiCell(42.97,12,trim($r['MITM_SPTNO']),'1','C');
				$pdf->SetXY(107.75,$cury);
				$pdf->MultiCell(8.15,12, number_format($r['SPL_QTYUSE']),'1','C');
				$pdf->SetXY(115.9,$cury);
				$pdf->MultiCell(7.41,12,trim($r['SPL_MS']),'1','C');
				$pdf->SetXY(123.31,$cury);
				$pdf->MultiCell(16.05,12,number_format($r['TTLREQ']),'1','R');			
				$pulskol =8;
				$kol =139.36;
				
				for($m=0;$m<count($d_machine);$m++){
					if(($d_prc[$m] == trim($r['SPL_PROCD'])) && ($d_mc[$m] == trim($r['SPL_MC'])) && ($d_machine[$m] == trim($r['SPL_ORDERNO'])) && ($d_item[$m] == trim($r['SPL_ITMCD'])) ){
						$pdf->SetXY($kol,$cury);
						$pdf->MultiCell(12.59,6, number_format($d_qty[$m])." \n $d_qtyg[$m]",'1','C');
						$pdf->SetDash(0.5,0.5);
						$pdf->Line($kol,$cury+6,$kol+12.59,$cury+6);
						$pdf->SetDash();
						$kol+=12.59;
						$pulskol--;
					}
				}
				for($j = 0; $j<$pulskol; $j++){
					$pdf->SetXY($kol,$cury);
					$pdf->MultiCell(12.59,6, " \n ",'1','C');
					$pdf->SetDash(0.5,0.5);
					$pdf->Line($kol,$cury+6,$kol+12.59,$cury+6);
					$pdf->SetDash();
					$kol+=12.59;
				}
				$pdf->SetXY(240.08,$cury);
				$pdf->MultiCell(15.80,12,number_format($r['TTLSCN']),'1','R');
				$logicbal = $r['TTLSCN'] - $r['TTLREQ'];
				$pdf->SetXY(255.88,$cury);
				$pdf->MultiCell(15.06,12, number_format($logicbal),'1','R');
				$retval = 0;
				
				for($j=0; $j<$mc_count;$j++ ){
					if( (trim($r['SPL_ORDERNO']) == $dr_machine[$j]) && (trim($r['SPL_ITMCD']) == $dr_item[$j] ) && $dr_used[$j] ==false ){
						$retval = $dr_qty[$j]; 
						$dr_used[$j]=true;
						break;
					}
				}
				$pdf->SetXY(270.94,$cury);
				$pdf->MultiCell(17.29,12,number_format($retval),'1','R');
				$cury+=12;
			}
		}
		
		
		//end h4
		$pdf->Output('I','Result Doc  '.date("d-M-Y").'.pdf');
		// echo json_encode($d_qty);
	}

	public function getunexported(){
		header('Content-Type: application/json');
		$rs = $this->SPLSCN_mod->selectunexported();
		$myar = [];
		if(count($rs)>0){
			$myar = ["cd" => "1", "msg" => "Go ahead"];
		} else {
			$myar = ["cd" => "0", "msg" => "data not found"];
		}
		echo '{"data":'.json_encode($rs).',"status" : '.json_encode($myar).'}';		
	}

	public function tracelot(){
		$cpsn = $this->input->get('inpsn');
		$citmcd = $this->input->get('initmcd');
		$citmlot = $this->input->get('initmlot');
		$myar = [];
		$rs = $this->SPLSCN_mod->selectby_filter_like(['SPLSCN_DOC' => $cpsn, 'SPLSCN_ITMCD' => $citmcd, 'SPLSCN_LOTNO' => $citmlot]);
		$rsreff = $this->SPLREFF_mod->selectby_filter_like(['SPLREFF_DOC' => $cpsn, 'SPLREFF_ACT_PART' => $citmcd, 'SPLREFF_ACT_LOTNUM' => $citmlot]);
		$rsmix = array_merge($rs, $rsreff);
		if(count($rsmix) >0 ){
			$myar[] = ['cd' => 1, 'msg' => 'go ahead'];
		} else {
			$myar[] = ['cd' => 0, 'msg' => 'not found'];
		}
		exit('{"status" : '.json_encode($myar).', "data": '.json_encode($rsmix).'}');
	}

	public function tracelot_trial(){
		header('Content-Type: application/json');
		$cpsn = $this->input->get('inpsn');
		$citmcd = $this->input->get('initmcd');
		$citmlot = $this->input->get('initmlot');
		$myar = [];
		$rs = $this->SPLSCN_mod->selectby_filter_like_wjob(['SPLSCN_DOC' => $cpsn, 'SPLSCN_ITMCD' => $citmcd, 'SPLSCN_LOTNO' => $citmlot]);
		if(count($rs) >0 ){
			$myar[] = ['cd' => 1, 'msg' => 'go ahead'];
		} else {
			$myar[] = ['cd' => 0, 'msg' => 'not found'];
		}
		exit('{"status" : '.json_encode($myar).', "data": '.json_encode($rs).'}');
	}

	public function tracelot_head(){
		$cpsn = $this->input->get('inpsn');
		$clineno = $this->input->get('inline');
		$cfedr = $this->input->get('infedr');
		
		$myar = [];
		$rs = $this->SPL_mod->select_xppsn1_byvar(['PPSN1_PSNNO' => $cpsn, 'PPSN1_LINENO' => $clineno, 'PPSN1_FR' => $cfedr]);
		if(count($rs) >0 ){
			$myar[] = ['cd' => 1, 'msg' => 'go ahead'];
		} else {
			$myar[] = ['cd' => 0, 'msg' => 'not found'];
		}
		die('{"status" : '.json_encode($myar).', "data": '.json_encode($rs).'}');
	}

	public function vreport_cims_vs_psn(){
		$this->load->view('wms_report/vrpt_cims_vs_psn');
	}	

	public function get_psn_process(){
		header('Content-Type: application/json');
		$cdocno = $this->input->get('indocno');	
		$cjob = $this->input->get('injob');
		$cassy = $this->input->get('inassy');
		$cmpart = $this->input->get('inmpart');
		$cmcz = $this->input->get('inmcz');
		$citemname = base64_decode($this->input->get('initemname'));
		$rsMSPP = $this->MSPP_mod->select_byvar(['MSPP_MDLCD' => $cassy, 'MSPP_BOMPN' => $cmpart]);
		$apsn = [];
		$rspsn = $this->SPL_mod->select_z_getpsn_byjob("'".$cjob."'");
		$rs_merg = [];
		foreach($rspsn as  $r){
			$apsn[] = $r['PPSN1_PSNNO'];
		}
		if(count($rsMSPP)==0){
			$rsMSPP = $this->MSPP_mod->select_byvar(['MSPP_MDLCD' => $cassy, 'MSPP_SUBPN' => $cmpart]);
			$thepart = [];
			$thepart[] = $cmpart;
			foreach($rsMSPP as $r){
				$thepart[] = $r['MSPP_BOMPN'];
			}
		} else {
			$thepart = [];
			$thepart[] = $cmpart;
			foreach($rsMSPP as $r){
				$thepart[] = $r['MSPP_SUBPN'];
			}
		}
		$rs = $this->SPL_mod->select_xppsn2_byvar_partin(['PPSN2_DOCNO' => $cdocno], $thepart, $apsn );		
		#FILTER MCZ		
		$rsbymcz = $this->SPL_mod->select_xppsn2_byvar_(['PPSN2_MCZ' => $cmcz], $apsn);		
		$rsbyitemname = $this->SPL_mod->select_xppsn2_byvar_like(['MITM_ITMD1' => $citemname], $apsn) ;
		$rs_merg = array_merge($rs,$rsbymcz, $rsbyitemname);
		$rsfix = [];				
		foreach($rs_merg as $r){
			$isfound = false;
			foreach($rsfix as $m){
				if(trim($r['PPSN2_DATANO']) == trim($m['PPSN2_DATANO'])){
					$isfound = true;
					break;
				}
			}
			if(!$isfound){
				$rsfix[] = $r;
			}
		}
		die('{"data": '.json_encode($rsfix).'}');
	}
	public function get_psn_process_special(){
		header('Content-Type: application/json');
		$cdocno = $this->input->get('indocno');	
		$cjob = $this->input->get('injob');
		$cassy = $this->input->get('inassy');
		$cmpart = $this->input->get('inmpart');
		$cmcz = $this->input->get('inmcz');
		$citemname = base64_decode($this->input->get('initemname'));
		$rsMSPP = $this->MSPP_mod->select_byvar(['MSPP_MDLCD' => $cassy, 'MSPP_BOMPN' => $cmpart]);
		$apsn = [];
		$rspsn = $this->SPL_mod->select_z_getpsn_byjob("'".$cjob."'");
		$rs_merg = [];
		foreach($rspsn as  $r){
			$apsn[] = $r['PPSN1_PSNNO'];
		}
		if(count($rsMSPP)==0){
			$rsMSPP = $this->MSPP_mod->select_byvar(['MSPP_MDLCD' => $cassy, 'MSPP_SUBPN' => $cmpart]);
			$thepart = [];
			$thepart[] = $cmpart;
			foreach($rsMSPP as $r){
				if(trim($r['MSPP_BOMPN'])!=$cmpart){
					$thepart[] = $r['MSPP_BOMPN'];
				}
			}
		} else {
			$thepart = [];
			$thepart[] = $cmpart;
			foreach($rsMSPP as $r){
				if(trim($r['MSPP_SUBPN'])!=$cmpart){
					$thepart[] = $r['MSPP_SUBPN'];
				}
			}
		}		
		$rs = $this->SPL_mod->select_xppsn2_byvar_partin(['PPSN2_DOCNO' => $cdocno, 'PPSN2_SUBPN !=' => $cmpart], $thepart, $apsn );		
		#FILTER MCZ		
		$rsbymcz = $this->SPL_mod->select_xppsn2_byvar_(['PPSN2_MCZ' => $cmcz, 'PPSN2_SUBPN !=' => $cmpart], $apsn);
		$rsbyitemname = $this->SPL_mod->select_xppsn2_byvar_like_withException(['MITM_ITMD1' => $citemname], $apsn, [$cmpart]) ;
		$rs_merg = array_merge($rs,$rsbymcz, $rsbyitemname);
		$rsfix = [];				
		foreach($rs_merg as $r){
			$isfound = false;
			foreach($rsfix as $m){
				if(trim($r['PPSN2_DATANO']) == trim($m['PPSN2_DATANO'])){
					$isfound = true;
					break;
				}
			}
			if(!$isfound){
				$rsfix[] = $r;
			}
		}
		die('{"data": '.json_encode($rsfix).'}');
	}

	public function get_job_bypsn(){
		$psn = $this->input->get('psn');
		$rs = $this->SPL_mod->select_job_bypsn($psn);
		die('{"data": '.json_encode($rs).'}');
	}

	public function remove_partreq(){
		$this->checkSession();
		date_default_timezone_set('Asia/Jakarta');
		$currrtime 	= date('Y-m-d H:i:s');
		$docno = $this->input->post('docno');
		$line = $this->input->post('line');
		$myar = [];
		if($this->SPLSCN_mod->check_Primary(['SPLSCN_DOC' => $docno, 'SPLSCN_ORDERNO' => $line])){
			$myar[] = ['cd' => 0, 'msg' => 'Could not delete, because it was already issued'];
		} else {
			if($this->SPL_mod->delete_partreq_by_filter(['SPL_DOC' => $docno, 'SPL_LINEDATA' => $line])){
				$myar[] = ['cd' => 1, 'msg' => 'OK'];
				$this->LOGSER_mod->insert_([
					'LOGSER_KEYS' => '{SPL_DOC:'.$docno.',SPL_LINEDATA:'.$line.'}'
				, 'LOGSER_DT' =>  $currrtime
				, 'LOGSER_USRID' => $this->session->userdata('nama')
				]);
			} else {
				$myar[] = ['cd' => 0, 'msg' => 'Could not delete, because we could not find the data'];
			}
		}
		die('{"status": '.json_encode($myar).'}');
	}

	public function getPRListperiod(){
		header('Content-Type: application/json');
		$dt1 = $this->input->get('dt1');
		$dt2 = $this->input->get('dt2');
		$business = $this->input->get('business');
		$rs = $this->SPL_mod->select_recap_partreq_business($dt1, $dt2, $business);
		die('{"data":'.json_encode($rs).'}');
	}

	public function getppsn2(){
		header('Content-Type: application/json');
		$ppsn = $this->input->get('ppsn');
		$rs = $this->SPL_mod->select_all_ppsn2(
			[
			'RTRIM(PPSN2_MCZ) PPSN2_MCZ'
			, 'RTRIM(PPSN2_MC) PPSN2_MC'
			, 'RTRIM(PPSN2_SUBPN) PPSN2_SUBPN'
			, 'RTRIM(MITM_SPTNO) MITM_SPTNO'
			], ['PPSN2_PSNNO' => $ppsn]
		);
		die('{"data":'.json_encode($rs).'}');
	}

	public function get_request_status_d(){
		header('Content-Type: application/json');
		$doc = $this->input->get('doc');
		$rs = $this->SPL_mod->select_partreq_status_d($doc);
		die('{"data":'.json_encode($rs).'}');
	}

	public function approve_partreq(){		
		header('Content-Type: application/json');
		$this->checkSession();
		date_default_timezone_set('Asia/Jakarta');
		$currrtime = date('Y-m-d H:i:s');
		$userid = $this->session->userdata('nama');
		$doc = $this->input->post('doc');
		$myar = [];
		if($this->session->userdata('gid')=="MSPV" || $this->session->userdata('gid')=="QACT"){
			$ret = $this->SPL_mod->updatebyId(
				['SPL_APPRV_BY' => $userid, 'SPL_APPRV_TM' => $currrtime]
				,['SPL_DOC' => $doc, 'SPL_APPRV_BY' => NULL] );
			if($ret>0){
				$myar[] = ['cd' => 1, 'msg' => 'Approved'];
			} else {
				$myar[] = ['cd' => 1, 'msg' => 'Already Approved'];
			}
		} else {
			$myar[] = ['cd' => 0, 'msg' => 'You could not approve this document'];
		}
		die('{"status":'.json_encode($myar).'}');
	}

	public function getPartReqRA(){
		header('Content-Type: application/json');
		$radoc = $this->input->get('inRA');
		$rs = $this->SPLSCN_mod->select_scanned_partreq_by_reffdoc($radoc);
		die('{"data":'.json_encode($rs).'}');
	}

	function outstanding() {
		header('Content-Type: application/json');
		$psnnum = $this->input->get('psnnum');
		$rs = $this->SPL_mod->select_outstanding($psnnum);
		die(json_encode(['data' => $rs]));
	}

	function savereference() {
		header('Content-Type: application/json');
		date_default_timezone_set('Asia/Jakarta');
		$currrtime 	= date('Y-m-d H:i:s');
		$psnnum = $this->input->post('psnnum');
		$confirmdate = $this->input->post('confirmdate');
		$a_r_part = $this->input->post('a_r_part');
		$a_r_qty = $this->input->post('a_r_qty');
		$a_r_category = $this->input->post('a_r_category');
		$a_r_lineprd = $this->input->post('a_r_lineprd');
		$a_r_fedr = $this->input->post('a_r_fedr');
		$a_r_mcz = $this->input->post('a_r_mcz');

		$a_s_part = $this->input->post('a_s_part');
		$a_s_qty = $this->input->post('a_s_qty');
		$a_s_lot = $this->input->post('a_s_lot');
		$a_r_line = $this->input->post('a_r_line');
		$c = count($a_r_part);
		$datas = [];
		$myar = [];
		if($this->SPLREFF_mod->check_Primary(['SPLREFF_DOC' => $psnnum])) {
			$rLine = $this->SPLREFF_mod->select_maxline(['SPLREFF_DOC' => $psnnum])+1;
			for($i=0; $i< $c; $i++) {
				if($this->SPLREFF_mod->check_Primary(['SPLREFF_DOC' => $psnnum , 'SPLREFF_LINE' => $a_r_line[$i]])) {

				} else {
					$datas[] = [
						'SPLREFF_DOC' => $psnnum
						,'SPLREFF_REQ_PART' => $a_r_part[$i]
						,'SPLREFF_REQ_QTY' => str_replace(',','', $a_r_qty[$i])
						,'SPLREFF_ITMCAT' => $a_r_category[$i]
						,'SPLREFF_LINEPRD' => $a_r_lineprd[$i]
						,'SPLREFF_FEDR' => $a_r_fedr[$i]
						,'SPLREFF_MCZ' => $a_r_mcz[$i]
						,'SPLREFF_ACT_PART' => $a_s_part[$i]
						,'SPLREFF_ACT_QTY' => str_replace(',','', $a_s_qty[$i])
						,'SPLREFF_ACT_LOTNUM' => $a_s_lot[$i]
						,'SPLREFF_LINE' => $rLine
						,'SPLREFF_DATE' => $confirmdate
						,'SPLREFF_CREATEDAT' => $currrtime
						,'SPLREFF_CREATEDBY' => $this->session->userdata('nama')
					];
					$rLine++;
				}
			}
			if(count($datas)) {
				$myar[] = ['cd' => 1, 'msg' => 'Saved successfully.'];
				$this->SPLREFF_mod->insertb($datas);
			} else {
				$myar[] = ['cd' => 1, 'msg' => 'OK'];				
			}
		} else {			
			for($i=0; $i< $c; $i++) {
				$datas[] = [
					'SPLREFF_DOC' => $psnnum
					,'SPLREFF_REQ_PART' => $a_r_part[$i]
					,'SPLREFF_REQ_QTY' => str_replace(',','',$a_r_qty[$i])
					,'SPLREFF_ITMCAT' => $a_r_category[$i]
					,'SPLREFF_LINEPRD' => $a_r_lineprd[$i]
					,'SPLREFF_FEDR' => $a_r_fedr[$i]
					,'SPLREFF_MCZ' => $a_r_mcz[$i]
					,'SPLREFF_ACT_PART' => $a_s_part[$i]
					,'SPLREFF_ACT_QTY' => str_replace(',','', $a_s_qty[$i])
					,'SPLREFF_ACT_LOTNUM' => $a_s_lot[$i]
					,'SPLREFF_LINE' => $i
					,'SPLREFF_DATE' => $confirmdate
					,'SPLREFF_CREATEDAT' => $currrtime
					,'SPLREFF_CREATEDBY' => $this->session->userdata('nama')
				];
			}
			if(count($datas)) {
				$myar[] = ['cd' => 1, 'msg' => 'Saved successfully'];
				$this->SPLREFF_mod->insertb($datas);
			}
		}
		#save ITH
		$rstoITH = $this->SPLREFF_mod->select_to_ith([$psnnum]);
		$rssave = [];
		foreach($rstoITH as $r) {
			$cdoc = $r['SPLREFF_DOC'].'|'.$r['SPLREFF_ITMCAT'].'|'.$r['SPLREFF_LINEPRD'].'|'.$r['SPLREFF_FEDR'];
			$rssave[] = [
				'ITH_ITMCD' => $r['SPLREFF_ACT_PART'], 'ITH_WH' =>  $r['WHOUT'] , 
				'ITH_DOC' 	=> $cdoc, 'ITH_DATE' => $r['SPLREFF_DATE'],
				'ITH_FORM' 	=> 'OUT-WH-RM_R', 'ITH_QTY' => -(int)$r['TTLSCN'], 
				'ITH_USRID' =>  $this->session->userdata('nama'),
				'ITH_LUPDT' => $r['SPLREFF_DATE']." 13:13:13"
			];
			$rssave[] = [
				'ITH_ITMCD' => $r['SPLREFF_ACT_PART'], 'ITH_WH' =>  $this->getWH_INC_SUP($r['SPL_BG']), 
				'ITH_DOC' 	=> $cdoc, 'ITH_DATE' => $r['SPLREFF_DATE'],
				'ITH_FORM' 	=> 'INC-PRD-RM_R', 'ITH_QTY' => (int)$r['TTLSCN'], 				
				'ITH_USRID' =>  $this->session->userdata('nama'),
				'ITH_LUPDT' => $r['SPLREFF_DATE']." 13:13:13"
			];
		}
		if(count($rssave)) {
			$this->ITH_mod->insertb($rssave);
			$this->SPLREFF_mod->updatebyVAR(['SPLREFF_SAVED' => '1'], ['SPLREFF_DOC' => $psnnum]);
		}
		#END
		die(json_encode(['status' => $myar]));
	}

	private function getWH_INC_SUP($pbisgrup){
		$cwh_inc = '';
		switch($pbisgrup){
			case 'PSI1PPZIEP':
				$cwh_inc = 'PLANT1';				
				break;
			case 'PSI2PPZADI':
				$cwh_inc = 'PLANT2';				
				break;
			case 'PSI2PPZINS':
				$cwh_inc = 'PLANT_NA';				
				break;
			case 'PSI2PPZOMC':
				$cwh_inc = 'PLANT_NA';				
				break;
			case 'PSI2PPZOMI':
				$cwh_inc = 'PLANT2';				
				break;
			case 'PSI2PPZSSI':
				$cwh_inc = 'PLANT_NA';				
				break;
			case 'PSI2PPZSTY':
				$cwh_inc = 'PLANT2';
				break;
			case 'PSI2PPZTDI':
				$cwh_inc = 'PLANT2';
				break;
		}	
		return $cwh_inc;			
	}

	public function load_reference() {
		header('Content-Type: application/json');
		$cpsn = $this->input->get('psnnum');
		$rs = $this->SPLREFF_mod->select_saved([$cpsn]);
		die(json_encode(['data' => $rs]));
	}

	public function remove_reference(){
		header('Content-Type: application/json');
		$psn = $this->input->post('psn');
		$idline = $this->input->post('idline');
		$myar = [];
		if($this->SPLREFF_mod->deleteby_filter(['SPLREFF_DOC' => $psn, 'SPLREFF_LINE' => $idline])) {
			$myar[] = ['cd' => 1, 'msg' => 'Deleted'];
		} else {
			$myar[] = ['cd' => 0, 'msg' => 'Not found'];
		}
		die(json_encode(['status' => $myar]));
	}

	public function gotoque($p_item, $p_psn, $p_cat,$p_line, $p_fr){	
		$fields = [
			'item' => $p_item,
			'psn' => $p_psn,
			'cat' => $p_cat,
			'line' => $p_line,
			'feeder' => $p_fr
		];
		$fields_string = http_build_query($fields);	
		$ch = curl_init();		
		// curl_setopt($ch, CURLOPT_URL, "http://192.168.0.29:8081/api_inventory/api/stock/onKitting/$p_item/$p_psn/$p_cat/$p_line/$p_fr");
		curl_setopt($ch, CURLOPT_URL, "http://192.168.0.29:8081/api-report-custom/api/stock/onKittingMultiItem");
		curl_setopt($ch, CURLOPT_POST, true);
		curl_setopt($ch, CURLOPT_POSTFIELDS,$fields_string);
		curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
		curl_setopt($ch, CURLOPT_CONNECTTIMEOUT, 5);
		curl_exec($ch);	
		curl_close($ch);		
	}

	public function info(){
		$psnno = $this->input->get('psnno');
		$rs = $this->SPL_mod->select_userinfo_group(['MSTEMP_FNM', 'MSTEMP_LNM','MSTEMP_ID'], ['SPL_DOC' => $psnno], 'SPL_USRID');
		$rsApproved = $this->SPL_mod->select_userinfo_group(['MSTEMP_FNM', 'MSTEMP_LNM','MSTEMP_ID'], ['SPL_DOC' => $psnno], 'SPL_APPRV_BY');
		die('{"createdBy":'.json_encode($rs).',"approvedBy":'.json_encode($rsApproved).'}');
	}

	public function search_ready_to_book(){
		header('Content-Type: application/json');
		$search = $this->input->get('search');
		$searchby = $this->input->get('searchby');
		$rs = $searchby=='PSN' ? $this->SPLSCN_mod->select_ready_book(['SPL_DOC' => $search]) : $this->SPLSCN_mod->select_ready_book_bywo($search);
		die(json_encode(['data' => $rs]));
	}

	public function book(){
		date_default_timezone_set('Asia/Jakarta');
		header('Content-Type: application/json');
		$currrtime 	= date('Y-m-d H:i:s');
		$bookid = $this->input->post('hbookid');
		$bookdate = $this->input->post('hbookdate');
		$bookdate_a = explode('-',$bookdate);
		$_year = substr($bookdate_a[0],-2);
		$_month = $bookdate_a[1]*1;
		$_date = $bookdate_a[2];
		$inid = $this->input->post('dinid');
		$inpsn = $this->input->post('dinpsn');
		$incat = $this->input->post('dincat');
		$inpc = $this->input->post('dinpc');
		$inqt = $this->input->post('dinqt');
		$ttlrows = is_array($inpc) ? count($inpc) : 0;
		$myar = [];
		$datas = [];
		if($this->SPLBOOK_mod->check_Primary(['SPLBOOK_DOC' => $bookid])){
			$lastline = $this->SPLBOOK_mod->select_maxline($bookid)+1;
			for($i=0;$i<$ttlrows; $i++){
				if(strlen($inid[$i])>0){
					$this->SPLBOOK_mod->updatebyVars(['SPLBOOK_QTY' => $inqt[$i]], ['SPLBOOK_DOC' => $bookid,'SPLBOOK_LINE' => $inid[$i]]);
				} else {
					if(!$this->SPLBOOK_mod->check_Primary(['SPLBOOK_SPLDOC' => $inpsn[$i],'SPLBOOK_ITMCD' => $inpc[$i]])){
						$datas[] = [
							'SPLBOOK_DOC' => $bookid,
							'SPLBOOK_SPLDOC' => $inpsn[$i],
							'SPLBOOK_CAT' => $incat[$i],
							'SPLBOOK_ITMCD' => $inpc[$i],
							'SPLBOOK_QTY' => $inqt[$i],
							'SPLBOOK_DATE' => $bookdate,
							'SPLBOOK_LINE' => $lastline++,
							'SPLBOOK_LUPDTD' => $currrtime,
							'SPLBOOK_USRID' => $this->session->userdata('nama')
						];
					}
				}
			}
			if(count($datas)){
				$this->SPLBOOK_mod->insertb($datas);
			}
			$myar[] = ['cd' => 1, 'msg' => 'Updated', 'doc' => $bookid];
		} else {			
			$lastbookid = $this->SPLBOOK_mod->lastserialid($bookdate)+1;
			$newdoc = 'B'.$_year.$this->AMONTHPATRN[($_month-1)].$_date.$lastbookid;
			$bookid = $newdoc;
			for($i=0;$i<$ttlrows; $i++){
				if(!$this->SPLBOOK_mod->check_Primary(['SPLBOOK_SPLDOC' => $inpsn[$i],'SPLBOOK_ITMCD' => $inpc[$i]])){
					$datas[] = [
						'SPLBOOK_DOC' => $newdoc,
						'SPLBOOK_SPLDOC' => $inpsn[$i],
						'SPLBOOK_CAT' => $incat[$i],
						'SPLBOOK_ITMCD' => $inpc[$i],
						'SPLBOOK_QTY' => $inqt[$i],
						'SPLBOOK_DATE' => $bookdate,
						'SPLBOOK_LINE' => ($i+1),
						'SPLBOOK_LUPDTD' => $currrtime,
						'SPLBOOK_USRID' => $this->session->userdata('nama')
					];
				}
			}
			if(count($datas)){
				$this->SPLBOOK_mod->insertb($datas);
				$myar[] = ['cd' => 1, 'msg' => 'Saved', 'doc' => $newdoc];
			} else{
				$myar[] = ['cd' => 0, 'msg' => 'the PSN is already booked'];
			}
		}
		///ITH///		
		$rs = $this->SPLBOOK_mod->select_book_where(['SPLBOOK_DOC' => $bookid]);
		$this->ITH_mod->deletebyID(['ITH_DOC' => $bookid, 'ITH_FORM' => 'BOOK-SPL-1']);		
		$ith_data = [];
		$psnlist = [];
		foreach($rs as $r){
			if(!in_array($r['SPLBOOK_SPLDOC'], $psnlist)){
				$psnlist[] = $r['SPLBOOK_SPLDOC'];
			}
		}
		$rsbg = $this->SPL_mod->select_bg_psn($psnlist);
		foreach($rs as $r){
			$wh = NULL;
			foreach($rsbg as $b){
				if($r['SPLBOOK_SPLDOC']==$b['SPL_DOC']){
					switch($b['SPL_BG']){
						case 'PSI1PPZIEP':
							$wh = 'ARWH1';							
							break;
						case 'PSI2PPZADI':
							$wh = 'ARWH2';							
							break;
						case 'PSI2PPZINS':
							$wh = 'NRWH2';							
							break;
						case 'PSI2PPZOMC':
							$wh = 'NRWH2';							
							break;
						case 'PSI2PPZOMI':
							$wh = 'ARWH2';							
							break;
						case 'PSI2PPZSSI':
							$wh = 'NRWH2';							
							break;
						case 'PSI2PPZSTY':
							$wh = 'ARWH2';							
							break;
						case 'PSI2PPZTDI':
							$wh = 'ARWH2';							
							break;
					}
					break;
				}
			}
			$ith_data[] = [
				'ITH_ITMCD' => $r['SPLBOOK_ITMCD'],
				'ITH_DATE' => $bookdate,
				'ITH_FORM' => 'BOOK-SPL-1',
				'ITH_DOC' => $bookid,
				'ITH_QTY' => -1*$r['SPLBOOK_QTY'],
				'ITH_WH' => $wh,
				'ITH_REMARK' => $r['SPLBOOK_SPLDOC'],
				'ITH_LUPDT' => $bookdate." 09:00:00",
				'ITH_USRID' => $this->session->userdata('nama')
			];
		}
		// if(count($ith_data)){
		// 	$this->ITH_mod->insertb($ith_data);
		// }
		die(json_encode(['status' => $myar, 'data' => $ith_data]));
	}

	public function book_header(){
		header('Content-Type: application/json');
		$search = $this->input->get('search');
		$searchby = $this->input->get('searchby');
		$like = $searchby=='PSN' ? ['SPLBOOK_SPLDOC' => $search] : ['SPLBOOK_DOC' => $search];
		$rs = $this->SPLBOOK_mod->select_book_like($like);
		die(json_encode(['data' => $rs]));
	}

	public function book_detail(){
		header('Content-Type: application/json');
		$doc = $this->input->get('doc');
		$rs = $this->SPLBOOK_mod->select_book_where(['SPLBOOK_DOC' => $doc]);
		die(json_encode(['data' => $rs]));
	}

	public function remove_booked(){
		header('Content-Type: application/json');
		$doc = $this->input->post('doc');
		$rowid = $this->input->post('rowid');
		$itemcd = $this->input->post('itemcd');
		$returned = $this->SPLBOOK_mod->deleteby_filter(['SPLBOOK_DOC' => $doc, 'SPLBOOK_LINE' => $rowid]);
		$this->ITH_mod->deletebyID(['ITH_DOC' => $doc, 'ITH_ITMCD' => $itemcd ]);
		$myar=[$returned ? ['cd' => 1, 'msg' => 'Deleted'] : ['cd' => 0, 'msg' => 'not found']];
		die(json_encode(['status' => $myar]));
	}

	public function book_detail_ost(){
		header('Content-Type: application/json');
		$rs = $this->SPLBOOK_mod->select_book_ost();
		die(json_encode(['data' => $rs]));
	}

	public function book_closing(){
		header('Content-Type: application/json');
		date_default_timezone_set('Asia/Jakarta');
		$currentDateTime = date('Y-m-d H:i:s');
		$bookid = $this->input->post('bookid');
		$itemcd = $this->input->post('itemcd');
		$issuedQTY = $this->input->post('issuedQTY');		
		$myar = [];
		$ret1 = $this->SPLBOOK_mod->updatebyVars(['SPLBOOK_QTY' => $issuedQTY, 'SPLBOOK_CLOSEDDT' => $currentDateTime]
		, ['SPLBOOK_DOC' => $bookid, 'SPLBOOK_ITMCD' => $itemcd]);
		$ret2 = 0;
		if($ret1){
			$ret2 = $this->ITH_mod->updatebyId(['ITH_FORM' => 'BOOK-SPL-1', 'ITH_DOC' => $bookid, 'ITH_ITMCD' => $itemcd], ['ITH_QTY' => -1*$issuedQTY]);
			if($ret2){
				$myar[] = ['cd' => 1, 'msg' => 'Closed'];
			} else {
				$myar[] = ['cd' => 1, 'msg' => 'Transaction could not be closed'];
			}
		} else {
			$myar[] = ['cd' => 0, 'msg' => 'Could not be closed'];
		}
		die(json_encode(['status' => $myar]));
	}
}
