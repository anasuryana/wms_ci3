<?php
defined('BASEPATH') OR exit('No direct script access allowed');
use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;
class ITH extends CI_Controller {

	public function __construct()
	{
		parent::__construct();
		$this->load->model('ITH_mod');
		$this->load->model('MSTLOCG_mod');
		$this->load->model('SER_mod');
		$this->load->model('MSTITM_mod');
		$this->load->model('XBGROUP_mod');
		$this->load->model('LOGXDATA_mod');
		$this->load->model('RETFG_mod');
		$this->load->model('BCBLC_mod');
		$this->load->model('SCR_mod');
		$this->load->model('RCV_mod');
		$this->load->model('MSPP_mod');
		$this->load->model('RPSAL_INVENTORY_mod');
		$this->load->model('ZRPSTOCK_mod');
		$this->load->model('XITRN_mod');
		$this->load->model('XFTRN_mod');
		$this->load->model('XICYC_mod');
		$this->load->helper('url');
		$this->load->library('session');
		$this->load->library('RMCalculator');
	}
	public function index()
	{
		echo "sorry";
	}

	public function create(){
		$rs = $this->XBGROUP_mod->selectall();
		$todis = '<option value="-">ALL</option>';
		foreach($rs as $r){
			$todis .= '<option value="'.trim($r->MBSG_BSGRP).'">'.trim($r->MBSG_DESC).'</option>';
		}
		$data['lgroup'] = $todis;
		$todiswh  ='';
		$rs = $this->MSTLOCG_mod->selectall();
		foreach($rs as $r){
			$todiswh .= '<option value="'.$r['MSTLOCG_ID'].'">'.$r['MSTLOCG_NM'].'</option>';
		}
		$data['lwh'] = $todiswh;
		$this->load->view('wms/vith', $data);
	}
	
	public function form_fg_slow_moving(){
		$this->load->view('wms_report/vfg_slowmoving');
	}

	function form_minus_stock(){
		$this->load->view('wms_report/vminus_stock');
	}

	function minus_stock(){
		header('Content-Type: application/json');
		$rs = $this->ITH_mod->select_minus_stock();
		die(json_encode(['data' => $rs]));
	}

	public function fg_slow_moving(){
		header('Content-Type: application/json');
		$cbgroup = $this->input->get('bsgrp');	
		$sbgroup ="";
		$absgrp = [];
		if(is_array($cbgroup)){
			for($i=0;$i<count($cbgroup);$i++){
				$sbgroup .= "'$cbgroup[$i]',";
				$absgrp[] = $cbgroup[$i];
			}
			$sbgroup = substr($sbgroup,0,strlen($sbgroup)-1);
			if($sbgroup==''){
				$sbgroup ="''";
			}
		} else {
			$sbgroup = "''";
		}
		$rs = count($absgrp) ? $this->ITH_mod->select_slow_moving_fg_bg($absgrp) : $this->ITH_mod->select_slow_moving_fg();
		die(json_encode(['data' => $rs]));
	}

	public function form_report_internal() {
		$this->load->view('wms_report/vrpt_internal');
	}

	public function get_bs_group(){
		header('Content-Type: application/json');
		$rs = $this->XBGROUP_mod->selectall();
		$rs_j = [];
		foreach($rs as $r){
			$rs_j[] = [
				'id' => trim($r->MBSG_BSGRP)
				,'text' => trim($r->MBSG_DESC)
			];
		}
		exit('{"data":'.json_encode($rs_j).'}');
	}

	public function get_bs_group_ith(){
		header('Content-Type: application/json');
		$rs = $this->XBGROUP_mod->selectall();
		$rs_j = [];
		foreach($rs as $r){
			$rs_j[] = [
				'id' => trim($r->MBSG_BSGRP)
				,'text' => trim($r->MBSG_DESC)
			];
		}
		exit(json_encode($rs_j));
	}

	public function vroutput_prd_daily(){
		$data['lgroup'] = $this->XBGROUP_mod->selectall();
		$this->load->view('wms_report/vrpt_output_prd', $data);
	}
	public function vroutput_qc_daily(){
		$data['lgroup'] = $this->XBGROUP_mod->selectall();
		$this->load->view('wms_report/vrpt_output_qc', $data);
	}
	public function vroutput_qcsa_daily(){
		$data['lgroup'] = $this->XBGROUP_mod->selectall();
		$this->load->view('wms_report/vrpt_output_qcsubassy', $data);
	}
	public function vroutput_prdsa_daily(){
		$data['lgroup'] = $this->XBGROUP_mod->selectall();
		$this->load->view('wms_report/vrpt_output_prdsubassy', $data);
	}
	public function vroutput_wh_daily(){
		$data['lgroup'] = $this->XBGROUP_mod->selectall();
		$this->load->view('wms_report/vrpt_output_wh', $data);
	}
	public function vroutput_wh_rtn_daily(){
		$data['lgroup'] = $this->XBGROUP_mod->selectall();
		$this->load->view('wms_report/vrpt_output_whrtn', $data);
	}
	public function vroutgoing_wh(){
		$data['lgroup'] = $this->XBGROUP_mod->selectall();
		$this->load->view('wms_report/vrpt_outgoing_fg', $data);
	}
	public function vunscan_qcwh(){		
		$this->load->view('wms_report/vunscan_qcwh');
	}
	public function vtxhistory(){
		$todiswh  ='';
		$rs = $this->MSTLOCG_mod->selectall();
		foreach($rs as $r){
			$todiswh .= '<option value="'.$r['MSTLOCG_ID'].'">'.$r['MSTLOCG_NM'].' ('.$r['MSTLOCG_ID'].')</option>';
		}
		$data['lwh'] = $todiswh;
		$this->load->view('wms_report/vrpt_txhistory', $data);
	}
	public function form_txhistory(){
		$todiswh  ='';
		$rs = $this->MSTLOCG_mod->selectall();
		foreach($rs as $r){
			$todiswh .= '<option value="'.$r['MSTLOCG_ID'].'">'.$r['MSTLOCG_NM'].' ('.$r['MSTLOCG_ID'].')</option>';
		}
		$data['lwh'] = $todiswh;
		$this->load->view('wms_report/vrpt_txhistory_parent', $data);
	}
	public function vtxhistory_customs(){		
		$this->load->view('wms_report/vrpt_txhistory_customs');
	}

	public function get_output_prd(){
		header('Content-Type: application/json');
		$cdtfrom = $this->input->get('indate');	
		$cdtto = $this->input->get('indate2');
		$creport = $this->input->get('inreport');
		$cassy = $this->input->get('inassy');
		$cbgroup = $this->input->get('inbgroup');
		$csearchby = $this->input->get('insearchby');
		$sbgroup ="";
		if(is_array($cbgroup)){
			for($i=0;$i<count($cbgroup);$i++){
				$sbgroup .= "'$cbgroup[$i]',";
			}
		} else {
			$sbgroup = "'".$cbgroup."',";
		}
		
		$sbgroup = substr($sbgroup,0,strlen($sbgroup)-1);
		if($sbgroup==''){
			$sbgroup ="''";
		}
		$dtto = '';		
		if($cdtfrom==$cdtto){
			if($creport=='a' || $creport == 'n'){
				$thedate = strtotime($cdtfrom . '+1 days');
				$dtto = date('Y-m-d', $thedate). " 06:59:00";			
			} else {
				$dtto = $cdtfrom. " 18:59:00";
			}
	
			if($creport=='a' || $creport == 'm'){
				$cdtfrom .= ' 07:00:00';
			} else {
				$cdtfrom .= ' 19:00:00';
			}			
		} else {		
			$thedate = strtotime($cdtto. '+1 days');
			$dtto = date('Y-m-d', $thedate). " 06:59:00";
			$cdtfrom .= ' 07:00:00';
		}
		$rs = [];
		if($csearchby=='assy'){
			$rs = $this->ITH_mod->select_output_prd($cdtfrom, $dtto, $cassy, $sbgroup);
		} else {
			$rs = $this->ITH_mod->select_output_prd_byjob($cdtfrom, $dtto, $cassy, $sbgroup);
		}
		echo '{"data":';	
		echo json_encode($rs);	
		echo '}';
	}

	public function get_output_prd_xls(){
		date_default_timezone_set('Asia/Jakarta');
		if(!isset($_COOKIE["CKPSI_DDATE"]) || !isset($_COOKIE["CKPSI_DREPORT"]) || !isset($_COOKIE["CKPSI_DASSY"]) || !isset($_COOKIE["CKPSI_BSGROUP"]) ){
			exit('no data to be found');
		}	
		$cdtfrom = $_COOKIE["CKPSI_DDATE"];
		$cdtto = $_COOKIE["CKPSI_DDATE2"];
		$creport = $_COOKIE["CKPSI_DREPORT"];
		$cassy = $_COOKIE["CKPSI_DASSY"];
		$cbsgroup = $_COOKIE["CKPSI_BSGROUP"];
		if($cbsgroup==''){
			$cbsgroup = "''";
		} else {
			if(strpos($cbsgroup, "|")!== false){
				$agroup = explode("|", $cbsgroup);
				$cbsgroup="";
				for($i=0;$i<count($agroup);$i++){
					$cbsgroup.="'$agroup[$i]'," ;
				}
				$cbsgroup = substr($cbsgroup,0,strlen($cbsgroup)-1);
			} else {
				$cbsgroup = "'$cbsgroup'";
			}
		}
		$dtto = '';	

		if($cdtfrom==$cdtto){
			if($creport=='a' || $creport == 'n'){
				$thedate = strtotime($cdtfrom . '+1 days');
				$dtto = date('Y-m-d', $thedate). " 06:59:00";			
			} else {
				$dtto = $cdtfrom. " 18:59:00";
			}
	
			if($creport=='a' || $creport == 'm'){
				$cdtfrom .= ' 07:00:00';
			} else {
				$cdtfrom .= ' 19:00:00';
			}			
		} else {		
			$thedate = strtotime($cdtto. '+1 days');
			$dtto = date('Y-m-d', $thedate). " 06:59:00";
			$cdtfrom .= ' 07:00:00';
			$creport="a";
		}

		$rs = $this->ITH_mod->select_output_prd($cdtfrom, $dtto, $cassy,$cbsgroup);
		$spreadsheet = new Spreadsheet();
		$sheet = $spreadsheet->getActiveSheet();
		$sheet->setTitle('OUTPUT_PRD');
		$sheet->setCellValueByColumnAndRow(1,1, 'Assy Number');
		$sheet->setCellValueByColumnAndRow(2,1, 'Model');
		$sheet->setCellValueByColumnAndRow(3,1, 'Lot');
		$sheet->setCellValueByColumnAndRow(4,1, 'Job Number');
		$sheet->setCellValueByColumnAndRow(5,1, 'QTY');
		$sheet->setCellValueByColumnAndRow(6,1, 'Reff. Number');
		$sheet->setCellValueByColumnAndRow(7,1, 'PIC');
		$sheet->setCellValueByColumnAndRow(8,1, 'Business Group');
		$sheet->setCellValueByColumnAndRow(9,1, 'Time');
		$no=2;
		foreach($rs as $r){
			$sheet->setCellValueByColumnAndRow(1,$no, trim($r['ITH_ITMCD']));
			$sheet->setCellValueByColumnAndRow(2,$no, trim($r['MITM_ITMD1']));
			$sheet->setCellValueByColumnAndRow(3,$no, $r['SER_LOTNO']);
			$sheet->setCellValueByColumnAndRow(4,$no, trim($r['SER_DOC']));
			$sheet->setCellValueByColumnAndRow(5,$no, $r['ITH_QTY']);
			$sheet->setCellValueByColumnAndRow(6,$no, $r['ITH_SER']);
			$sheet->setCellValueByColumnAndRow(7,$no, $r['PIC']);
			$sheet->setCellValueByColumnAndRow(8,$no, trim($r['PDPP_BSGRP']));
			$sheet->setCellValueByColumnAndRow(9,$no, $r['ITH_LUPDT']);
			$no++;
		}
		$cdtfrom = substr($cdtfrom,0,10);
		$stringjudul = "output produksi $cdtfrom ($creport) ".date(' H i');
		$writer = new Xlsx($spreadsheet);		
		$filename=$stringjudul; //save our workbook as this file name
		
		header('Content-Type: application/vnd.ms-excel');
		header('Content-Disposition: attachment;filename="'. $filename .'.xlsx"'); 
		header('Cache-Control: max-age=0');
		$writer->save('php://output');
	}

	public function get_output_qc(){
		header('Content-Type: application/json');
		$cdtfrom = $this->input->get('indate');	
		$cdtto = $this->input->get('indate2');
		$creport = $this->input->get('inreport');
		$cassy = $this->input->get('inassy');
		$cbgroup = $this->input->get('inbgroup');
		$csearchby = $this->input->get('insearchby');
		$sbgroup ="";
		if(is_array($cbgroup)){
			for($i=0;$i<count($cbgroup);$i++){
				$sbgroup .= "'$cbgroup[$i]',";
			}
		}
		$sbgroup = substr($sbgroup,0,strlen($sbgroup)-1);
		if($sbgroup==''){
			$sbgroup ="''";
		}
		$dtto = '';		
		if($cdtfrom==$cdtto){
			if($creport=='a' || $creport == 'n'){
				$thedate = strtotime($cdtfrom . '+1 days');
				$dtto = date('Y-m-d', $thedate). " 06:59:00";			
			} else {
				$dtto = $cdtfrom. " 18:59:00";
			}
	
			if($creport=='a' || $creport == 'm'){
				$cdtfrom .= ' 07:00:00';
			} else {
				$cdtfrom .= ' 19:00:00';
			}			
		} else {		
			$thedate = strtotime($cdtto. '+1 days');
			$dtto = date('Y-m-d', $thedate). " 06:59:00";
			$cdtfrom .= ' 07:00:00';
		}
		$rs = [];
		if($csearchby=='assy'){
			$rs = $this->ITH_mod->select_output_qc($cdtfrom, $dtto, $cassy,$sbgroup);
		} else {
			$rs = $this->ITH_mod->select_output_qc_byjob($cdtfrom, $dtto, $cassy,$sbgroup);
		}
		echo '{"data":'.json_encode($rs).'}';		
	}

	public function get_output_qcsa(){
		header('Content-Type: application/json');
		$cdtfrom = $this->input->get('indate');	
		$cdtto = $this->input->get('indate2');
		$creport = $this->input->get('inreport');
		$cassy = $this->input->get('inassy');
		$cbgroup = $this->input->get('inbgroup');
		$csearchby = $this->input->get('insearchby');
		$sbgroup ="";
		if(is_array($cbgroup)){
			for($i=0;$i<count($cbgroup);$i++){
				$sbgroup .= "'$cbgroup[$i]',";
			}
		} else {
			$sbgroup .= "'$cbgroup',";
		}
		$sbgroup = substr($sbgroup,0,strlen($sbgroup)-1);
		if($sbgroup==''){
			$sbgroup ="''";
		}
		$dtto = '';		
		if($cdtfrom==$cdtto){
			if($creport=='a' || $creport == 'n'){
				$thedate = strtotime($cdtfrom . '+1 days');
				$dtto = date('Y-m-d', $thedate). " 06:59:00";			
			} else {
				$dtto = $cdtfrom. " 18:59:00";
			}
	
			if($creport=='a' || $creport == 'm'){
				$cdtfrom .= ' 07:00:00';
			} else {
				$cdtfrom .= ' 19:00:00';
			}			
		} else {		
			$thedate = strtotime($cdtto. '+1 days');
			$dtto = date('Y-m-d', $thedate). " 06:59:00";
			$cdtfrom .= ' 07:00:00';
		}
		$rs = [];
		if($csearchby=='assy'){
			$rs = $this->ITH_mod->select_output_qcsa($cdtfrom, $dtto, $cassy,$sbgroup);
		} else {
			$rs = $this->ITH_mod->select_output_qcsa_byjob($cdtfrom, $dtto, $cassy,$sbgroup);
		}
		echo '{"data":'.json_encode($rs).'}';		
	}


	public function get_output_prdsa(){
		header('Content-Type: application/json');
		$cdtfrom = $this->input->get('indate');	
		$cdtto = $this->input->get('indate2');
		$creport = $this->input->get('inreport');
		$cassy = $this->input->get('inassy');
		$cbgroup = $this->input->get('inbgroup');
		$csearchby = $this->input->get('insearchby');
		$sbgroup ="";
		if(is_array($cbgroup)){
			for($i=0;$i<count($cbgroup);$i++){
				$sbgroup .= "'$cbgroup[$i]',";
			}
		} else {
			$sbgroup .= "'$cbgroup',";
		}
		$sbgroup = substr($sbgroup,0,strlen($sbgroup)-1);
		if($sbgroup==''){
			$sbgroup ="''";
		}
		$dtto = '';		
		if($cdtfrom==$cdtto){
			if($creport=='a' || $creport == 'n'){
				$thedate = strtotime($cdtfrom . '+1 days');
				$dtto = date('Y-m-d', $thedate). " 06:59:00";			
			} else {
				$dtto = $cdtfrom. " 18:59:00";
			}
	
			if($creport=='a' || $creport == 'm'){
				$cdtfrom .= ' 07:00:00';
			} else {
				$cdtfrom .= ' 19:00:00';
			}			
		} else {		
			$thedate = strtotime($cdtto. '+1 days');
			$dtto = date('Y-m-d', $thedate). " 06:59:00";
			$cdtfrom .= ' 07:00:00';
		}
		$rs = [];
		if($csearchby=='assy'){
			$rs = $this->ITH_mod->select_output_prdsa($cdtfrom, $dtto, $cassy,$sbgroup);
		} else {
			$rs = $this->ITH_mod->select_output_prdsa_byjob($cdtfrom, $dtto, $cassy,$sbgroup);
		}
		echo '{"data":'.json_encode($rs).'}';		
	}

	public function get_output_qc_xls(){
		date_default_timezone_set('Asia/Jakarta');
		if(!isset($_COOKIE["CKPSI_DDATE"]) && !isset($_COOKIE["CKPSI_DREPORT"]) && !isset($_COOKIE["CKPSI_DASSY"]) || !isset($_COOKIE["CKPSI_BSGROUP"])){
			exit('no data to be found');
		}	
		$cdtfrom = $_COOKIE["CKPSI_DDATE"];
		$cdtto = $_COOKIE["CKPSI_DDATE2"];
		$creport = $_COOKIE["CKPSI_DREPORT"];
		$cassy = $_COOKIE["CKPSI_DASSY"];
		$cbsgroup = $_COOKIE["CKPSI_BSGROUP"];
		if($cbsgroup==''){
			$cbsgroup = "''";
		} else {
			if(strpos($cbsgroup, "|")!== false){
				$agroup = explode("|", $cbsgroup);
				$cbsgroup="";
				for($i=0;$i<count($agroup);$i++){
					$cbsgroup.="'$agroup[$i]'," ;
				}
				$cbsgroup = substr($cbsgroup,0,strlen($cbsgroup)-1);
			} else {
				$cbsgroup = "'$cbsgroup'";
			}
		}
		$dtto = '';	

		if($cdtfrom==$cdtto){
			if($creport=='a' || $creport == 'n'){
				$thedate = strtotime($cdtfrom . '+1 days');
				$dtto = date('Y-m-d', $thedate). " 06:59:00";			
			} else {
				$dtto = $cdtfrom. " 18:59:00";
			}
	
			if($creport=='a' || $creport == 'm'){
				$cdtfrom .= ' 07:00:00';
			} else {
				$cdtfrom .= ' 19:00:00';
			}			
		} else {		
			$thedate = strtotime($cdtto. '+1 days');
			$dtto = date('Y-m-d', $thedate). " 06:59:00";
			$cdtfrom .= ' 07:00:00';
		}

		$rs = $this->ITH_mod->select_output_qc($cdtfrom, $dtto, $cassy, $cbsgroup);
		$spreadsheet = new Spreadsheet();
		$sheet = $spreadsheet->getActiveSheet();
		$sheet->setTitle('OUTPUT_PRD');
		$sheet->setCellValueByColumnAndRow(1,1, 'Assy Number');
		$sheet->setCellValueByColumnAndRow(2,1, 'Model');
		$sheet->setCellValueByColumnAndRow(3,1, 'Lot');
		$sheet->setCellValueByColumnAndRow(4,1, 'Job Number');
		$sheet->setCellValueByColumnAndRow(5,1, 'QTY');
		$sheet->setCellValueByColumnAndRow(6,1, 'Reff. Number');
		$sheet->setCellValueByColumnAndRow(7,1, 'PIC');
		$sheet->setCellValueByColumnAndRow(8,1, 'Time');
		$no=2;
		foreach($rs as $r){
			$sheet->setCellValueByColumnAndRow(1,$no, $r['ITH_ITMCD']);
			$sheet->setCellValueByColumnAndRow(2,$no, $r['MITM_ITMD1']);
			$sheet->setCellValueByColumnAndRow(3,$no, $r['SER_LOTNO']);
			$sheet->setCellValueByColumnAndRow(4,$no, $r['SER_DOC']);
			$sheet->setCellValueByColumnAndRow(5,$no, $r['ITH_QTY']);
			$sheet->setCellValueByColumnAndRow(6,$no, $r['ITH_SER']);
			$sheet->setCellValueByColumnAndRow(7,$no, $r['PIC']);
			$sheet->setCellValueByColumnAndRow(8,$no, $r['ITH_LUPDT']);
			$no++;
		}
		$stringjudul = "output QC $cdtfrom ($creport) ";
		$writer = new Xlsx($spreadsheet);
		$filename=$stringjudul.date(' H i'); //save our workbook as this file name
		
		header('Content-Type: application/vnd.ms-excel');
		header('Content-Disposition: attachment;filename="'. $filename .'.xlsx"'); 
		header('Cache-Control: max-age=0');
		$writer->save('php://output');
	}

	public function get_output_wh(){
		header('Content-Type: application/json');
		$csearchby = $this->input->get('insearchby');	
		$cdtfrom = $this->input->get('indate');	
		$cdtto = $this->input->get('indate2');
		$creport = $this->input->get('inreport');
		$cassy = $this->input->get('inassy');
		$cbsgroup = trim($this->input->get('inbsgrp'));
		$dtto = '';
		if($cdtfrom==$cdtto){
			if($creport=='a' || $creport == 'n'){
				$thedate = strtotime($cdtfrom . '+1 days');
				$dtto = date('Y-m-d', $thedate). " 06:59:00";			
			} else {
				$dtto = $cdtfrom. " 18:59:00";
			}
	
			if($creport=='a' || $creport == 'm'){
				$cdtfrom .= ' 07:00:00';
			} else {
				$cdtfrom .= ' 19:00:00';
			}
		} else {		
			$thedate = strtotime($cdtto. '+1 days');
			$dtto = date('Y-m-d', $thedate). " 06:59:00";
			$cdtfrom .= ' 07:00:00';
		}
		$rs = [];
		if($cbsgroup=='-'){
			switch($csearchby){
				case 'assy':
					$rs = $this->ITH_mod->select_output_wh_byassy($cdtfrom, $dtto, $cassy);break;
				case 'job':
					$rs = $this->ITH_mod->select_output_wh_byjob($cdtfrom, $dtto, $cassy);break;
				case 'reff':
					$rs = $this->ITH_mod->select_output_wh_byreff($cdtfrom, $dtto, $cassy);break;
			}
		} else {
			switch($csearchby){
				case 'assy':
					$rs = $this->ITH_mod->select_output_wh_byassy_bg($cdtfrom, $dtto, $cassy, $cbsgroup);break;
				case 'job':
					$rs = $this->ITH_mod->select_output_wh_byjob_bg($cdtfrom, $dtto, $cassy, $cbsgroup);break;
				case 'reff':
					$rs = $this->ITH_mod->select_output_wh_byreff_bg($cdtfrom, $dtto, $cassy, $cbsgroup);break;
			}
		}
		echo '{"data":'.json_encode($rs).'}';		
	}
	public function get_output_whrtn(){
		header('Content-Type: application/json');
		$csearchby = $this->input->get('insearchby');	
		$cdtfrom = $this->input->get('indate');	
		$cdtto = $this->input->get('indate2');
		$creport = $this->input->get('inreport');
		$cassy = $this->input->get('inassy');
		$cbsgroup = trim($this->input->get('inbsgrp'));
		$dtto = '';
		if($cdtfrom==$cdtto){
			if($creport=='a' || $creport == 'n'){
				$thedate = strtotime($cdtfrom . '+1 days');
				$dtto = date('Y-m-d', $thedate). " 06:59:00";			
			} else {
				$dtto = $cdtfrom. " 18:59:00";
			}
	
			if($creport=='a' || $creport == 'm'){
				$cdtfrom .= ' 07:00:00';
			} else {
				$cdtfrom .= ' 19:00:00';
			}
		} else {		
			$thedate = strtotime($cdtto. '+1 days');
			$dtto = date('Y-m-d', $thedate). " 06:59:00";
			$cdtfrom .= ' 07:00:00';
		}
		$rs = [];
		if($cbsgroup=='-'){
			switch($csearchby){
				case 'assy':
					$rs = $this->ITH_mod->select_output_whrtn_byassy($cdtfrom, $dtto, $cassy);break;
				case 'job':
					$rs = $this->ITH_mod->select_output_whrtn_byjob($cdtfrom, $dtto, $cassy);break;
				case 'reff':
					$rs = $this->ITH_mod->select_output_whrtn_byreff($cdtfrom, $dtto, $cassy);break;
			}
		} else {
			switch($csearchby){
				case 'assy':
					$rs = $this->ITH_mod->select_output_whrtn_byassy_bg($cdtfrom, $dtto, $cassy, $cbsgroup);break;
				case 'job':
					$rs = $this->ITH_mod->select_output_whrtn_byjob_bg($cdtfrom, $dtto, $cassy, $cbsgroup);break;
				case 'reff':
					$rs = $this->ITH_mod->select_output_whrtn_byreff_bg($cdtfrom, $dtto, $cassy, $cbsgroup);break;
			}
		}
		echo '{"data":'.json_encode($rs).'}';		
	}

	public function get_qcwh_unscan(){
		$rs = $this->ITH_mod->select_qcwh_unscan();
		$rsscrap = $this->SCR_mod->select_scrapreport_balance();
		$rsdisplay =  [];
		foreach($rs as &$r){
			foreach($rsscrap as &$s){
				if($r['ITH_DOC'] == $s['DOC_NO'] && $s['SCRQTY']>0){					
					if ($r['ITH_QTY']>=$s['SCRQTY']){
						$r['ITH_QTY']-=$s['SCRQTY']; 
						$s['SCRQTY']=0;
					} else {
						$r['ITH_QTY']-=$r['ITH_QTY']; 
						$s['SCRQTY']-=$r['ITH_QTY'];
					}
				}
			}
			unset($s);
			if($r['ITH_QTY']>0){
				$rsdisplay[] = $r;
			}
		}
		unset($r);
		die('{"data":'.json_encode($rsdisplay).',"data_scr":'.json_encode($rsscrap).'}');
	}
	
	public function get_output_wh_xls(){
		date_default_timezone_set('Asia/Jakarta');
		if(!isset($_COOKIE["CKPSI_DDATE"]) && !isset($_COOKIE["CKPSI_DREPORT"]) && !isset($_COOKIE["CKPSI_DASSY"]) ){
			exit('no data to be found');
		}	
		$csearchby = $_COOKIE["CKPSI_SEARCHBY"];
		$cdtfrom = $_COOKIE["CKPSI_DDATE"];
		$cdtto = $_COOKIE["CKPSI_DDATE2"];
		$creport = $_COOKIE["CKPSI_DREPORT"];
		$cassy = $_COOKIE["CKPSI_DASSY"];
		$cbsgroup = $_COOKIE["CKPSI_BSGRP"];
		$dtto = '';	
		$grpid =  $this->session->userdata('gid');
		$usrid =  $this->session->userdata('nama');
		if($cdtfrom==$cdtto){
			if($creport=='a' || $creport == 'n'){
				$thedate = strtotime($cdtfrom . '+1 days');
				$dtto = date('Y-m-d', $thedate). " 06:59:00";			
			} else {
				$dtto = $cdtfrom. " 18:59:00";
			}
	
			if($creport=='a' || $creport == 'm'){
				$cdtfrom .= ' 07:00:00';
			} else {
				$cdtfrom .= ' 19:00:00';
			}			
		} else {		
			$thedate = strtotime($cdtto. '+1 days');
			$dtto = date('Y-m-d', $thedate). " 06:59:00";
			$cdtfrom .= ' 07:00:00';
		}

		$rs= [];
		if($cbsgroup=='-'){
			switch($csearchby){
				case 'assy':
					$rs = $this->ITH_mod->select_output_wh_byassy($cdtfrom, $dtto, $cassy);break;
				case 'job':
					$rs = $this->ITH_mod->select_output_wh_byjob($cdtfrom, $dtto, $cassy);break;
				case 'reff':
					$rs = $this->ITH_mod->select_output_wh_byreff($cdtfrom, $dtto, $cassy);break;
			}
		} else { 
			switch($csearchby){
				case 'assy':
					$rs = $this->ITH_mod->select_output_wh_byassy_bgxp($cdtfrom, $dtto, $cassy, $cbsgroup);break;
				case 'job':
					$rs = $this->ITH_mod->select_output_wh_byjob_bgxp($cdtfrom, $dtto, $cassy, $cbsgroup);break;
				case 'reff':
					$rs = $this->ITH_mod->select_output_wh_byreff_bgxp($cdtfrom, $dtto, $cassy, $cbsgroup);break;
			}
		}
		$this->LOGXDATA_mod->insert(['LOGXDATA_USER' => $usrid, 'LOGXDATA_MENU' => 'EXPORT PPC DATA INC TO MEGA' ]);
		$spreadsheet = new Spreadsheet();
		$sheet = $spreadsheet->getActiveSheet();
		$sheet->setTitle('OUTPUT_WH');
		$sheet->setCellValueByColumnAndRow(1,1, 'WO NO');
		$sheet->setCellValueByColumnAndRow(2,1, 'MODEL CODE');
		$sheet->setCellValueByColumnAndRow(3,1, 'GRN QTY');	
		$no=2;

		$arow = [];
		if($grpid=='INC' || $grpid=='ROOT' ){			
			$aser = [];
			$aqty = [];
			$ajob = [];
			foreach($rs as $r){
				if($r['SER_QTY']>0){
					$aser[] = $r['ITH_SER'];
					$aqty[] = $r['ITH_QTY'];
					$ajob[] = $r['SER_DOC'];
				}

				$this->ITH_mod->update_exported_fg_wh($r['ITH_SER']);				
				
				$isfound = false;
				foreach($arow as &$k){
					if($k['JOB']==trim($r['SER_DOC']) && $k['ITEM']== trim($r['ITH_ITMCD'])){
						$k['QTY']+=$r['ITH_QTY'];
						$isfound = true;
						break;
					}
				}
				if($isfound==false){
					$arow[] = ['JOB' => trim($r['SER_DOC']), 'ITEM' => trim($r['ITH_ITMCD']), 'QTY' => $r['ITH_QTY'] ];
				}
				unset($k);
				
			}
			foreach($arow as $k){
				$sheet->setCellValueByColumnAndRow(1,$no, $k['JOB']);
				$sheet->setCellValueByColumnAndRow(2,$no, $k['ITEM']);
				$sheet->setCellValueByColumnAndRow(3,$no, $k['QTY']);
				$no++;
			}
			if(count($aser)>0){
				$Calc_lib = new RMCalculator();
				$Calc_lib->calculate_later($aser, $aqty, $ajob);
			}
			
		} else {
			$sheet->setCellValueByColumnAndRow(1,$no, '??');
			$sheet->setCellValueByColumnAndRow(2,$no, '??');
			$sheet->setCellValueByColumnAndRow(3,$no, '??');
			$no++;
		}
		

		$tgl = str_replace('-', '',substr($cdtfrom,0,10));
		
		$stringjudul = "$cbsgroup $tgl ";
		$writer = new Xlsx($spreadsheet);		
		$filename=$stringjudul.date('H:::i'); //save our workbook as this file name
		
		header('Content-Type: application/vnd.ms-excel');
		header('Content-Disposition: attachment;filename="'. $filename .'.xlsx"'); 
		header('Cache-Control: max-age=0');
		$writer->save('php://output');		
	}

	public function createfile(){
		echo base_url()."<br>";
		echo site_url()."<br>";
		echo FCPATH."<br>";
		echo $_SERVER['DOCUMENT_ROOT']."<br>";

		#cratetxtfile
		$contentText = "kamu kamu cinta kamu";
		$fp = fopen($_SERVER['DOCUMENT_ROOT']."/wms/assets/data_calculation.txt", "wb");
		fwrite($fp, $contentText);
		fclose($fp);

	}

	public function get_output_qcsa_xls(){
		date_default_timezone_set('Asia/Jakarta');
		if(!isset($_COOKIE["CKPSI_DDATE"]) && !isset($_COOKIE["CKPSI_DREPORT"]) && !isset($_COOKIE["CKPSI_DASSY"]) ){
			exit('no data to be found');
		}	
		
		$cdtfrom = $_COOKIE["CKPSI_DDATE"];
		$cdtto = $_COOKIE["CKPSI_DDATE2"];
		$creport = $_COOKIE["CKPSI_DREPORT"];		
		$cbsgroup = $_COOKIE["CKPSI_BSGROUP"];
		$dtto = '';	
		$grpid =  $this->session->userdata('gid');
		$usrid =  $this->session->userdata('nama');
		if($cdtfrom==$cdtto){
			if($creport=='a' || $creport == 'n'){
				$thedate = strtotime($cdtfrom . '+1 days');
				$dtto = date('Y-m-d', $thedate). " 06:59:00";			
			} else {
				$dtto = $cdtfrom. " 18:59:00";
			}
	
			if($creport=='a' || $creport == 'm'){
				$cdtfrom .= ' 07:00:00';
			} else {
				$cdtfrom .= ' 19:00:00';
			}
		} else {		
			$thedate = strtotime($cdtto. '+1 days');
			$dtto = date('Y-m-d', $thedate). " 06:59:00";
			$cdtfrom .= ' 07:00:00';
		}

		
		$rs = $this->ITH_mod->select_output_qcsa_byreff_bgxp($cdtfrom, $dtto, $cbsgroup);
		
		$this->LOGXDATA_mod->insert(['LOGXDATA_USER' => $usrid, 'LOGXDATA_MENU' => 'EXPORT SUB ASSY INC TO MEGA' ]);
		$spreadsheet = new Spreadsheet();
		$sheet = $spreadsheet->getActiveSheet();
		$sheet->setTitle('OUTPUT_WH');
		$sheet->setCellValueByColumnAndRow(1,1, 'WO NO');
		$sheet->setCellValueByColumnAndRow(2,1, 'MODEL CODE');
		$sheet->setCellValueByColumnAndRow(3,1, 'GRN QTY');	
		$no=2;

		$arow = [];
		if($grpid=='OQC2' || $grpid=='ROOT' ){
			$aser = [];
			$aqty = [];
			$ajob = [];
			foreach($rs as $r){
				$aser[] = $r['ITH_SER'];
				$aqty[] = urlencode($r['ITH_QTY']);
				$ajob[] = urlencode($r['SER_DOC']);

				$this->ITH_mod->update_exported_qcsa_wh($r['ITH_SER']);				
				
				$isfound = false;
				foreach($arow as &$k){
					if($k['JOB']==trim($r['SER_DOC']) && $k['ITEM']== trim($r['ITH_ITMCD'])){
						$k['QTY']+=$r['ITH_QTY'];
						$isfound = true;
						break;
					}
				}
				if($isfound==false){
					$arow[] = ['JOB' => trim($r['SER_DOC']), 'ITEM' => trim($r['ITH_ITMCD']), 'QTY' => $r['ITH_QTY'] ];
				}
				unset($k);
				
			}
			foreach($arow as $k){
				$sheet->setCellValueByColumnAndRow(1,$no, $k['JOB']);
				$sheet->setCellValueByColumnAndRow(2,$no, $k['ITEM']);
				$sheet->setCellValueByColumnAndRow(3,$no, $k['QTY']);
				$no++;
			}
			if(count($aser)>0){
				$Calc_lib = new RMCalculator();
				$Calc_lib->calculate_only_raw_material_resume($aser, $aqty, $ajob);
			}
		} else {
			$sheet->setCellValueByColumnAndRow(1,$no, '??');
			$sheet->setCellValueByColumnAndRow(2,$no, '??');
			$sheet->setCellValueByColumnAndRow(3,$no, '??');
			$no++;
		}
		

		$tgl = str_replace('-', '',substr($cdtfrom,0,10));
		
		$stringjudul = "$cbsgroup AWIP1 $tgl ";
		$writer = new Xlsx($spreadsheet);		
		$filename=$stringjudul.date('H:::i'); //save our workbook as this file name
		
		header('Content-Type: application/vnd.ms-excel');
		header('Content-Disposition: attachment;filename="'. $filename .'.xlsx"'); 
		header('Cache-Control: max-age=0');
		$writer->save('php://output');		
	}
	
	public function vtfid(){
		$this->load->view('wms/vtransferlocation');
	}
	
	public function vrincfg(){		
		$this->load->view('wms/vr_incoming_fg');
	}
	public function vrincfgrtn(){
		$data['lgroup'] = $this->RETFG_mod->select_businessgroup();
		$this->load->view('wms_report/vr_incoming_fgrtn', $data);
	}

	public function vrwarehouse_map(){		
		$this->load->view('wms_report/vfg_wh');
	}

	public function getdataincfg(){
		header('Content-Type: application/json');
		$cjob = $this->input->post('injob');
		$csts = $this->input->post('insts');
		$cbg = $this->input->post('inbg');
		$rs = [];
		$a_cjob = explode("#", $cjob);
		if( count($a_cjob) == 2 ) {
			
			if(is_array($cbg)){
				$therevision = $a_cjob[1] == '' ? 0 : $a_cjob[1];				
				$thejob = $a_cjob[0];
				$ttldata = count($cbg);
				for($i=0;$i<$ttldata; $i++){
					$cr_bg = $cbg[$i];
					$rst = $this->ITH_mod->selectincfg_with_revision($thejob,  $csts, $cr_bg, $therevision);
					foreach($rst as $r){
						$rs[] = $r;
					}
				}
			}
		} else {
			if(is_array($cbg)){
				$ttldata = count($cbg);
				for($i=0;$i<$ttldata; $i++){
					$cr_bg = $cbg[$i];
					$rst = $this->ITH_mod->selectincfg($cjob,  $csts, $cr_bg);	
					foreach($rst as $r){
						$rs[] = $r;
					}
				}
			}
		}
		
		die('{"data":'.json_encode($rs).'}');
	}
	public function getdataincfgrtn(){
		header('Content-Type: application/json');
		$cdoc = $this->input->post('indoc');
		$citem = $this->input->post('initem');
		$cbg = $this->input->post('inbg');
		$csts = $this->input->post('insts');
		$rst = $this->ITH_mod->selectincfgrtn($cbg,$cdoc, $citem, $csts);
		die('{"data":'.json_encode($rst).'}');
	}
	public function getdataincfg_prd_qc(){
		header('Content-Type: application/json');
		$cjob = $this->input->post('injob');
		$csts = $this->input->post('insts');
		$cbg = $this->input->post('inbg');
		
		$rs = [];
		if(is_array($cbg)){			
			$ttldata = count($cbg);
			for($i=0;$i<$ttldata; $i++){
				$cr_bg = $cbg[$i];
				$rst = $this->ITH_mod->selectincfg_prd_qc($cjob,  $csts,$cr_bg);	
				foreach($rst as $r){
					$rs[] = $r;
				}				 
			}
		}
		die('{"data":'.json_encode($rs).'}');
	}

	public function r_incfg_mega(){
		$cjob = $_COOKIE["CKR_INCFG_JOB"];
		$cdt = $_COOKIE["CKR_INCFG_DT"];
		$thedate = strtotime($cdt . '+1 days');
		$dtto = date('Y-m-d', $thedate). " 06:59:00";		
		$cdt .= ' 07:00:00';		
		$rs = $this->ITH_mod->selectincfg_mega($cjob, $cdt, $dtto);
		$spr = new Spreadsheet();
		$sht = $spr->getActiveSheet();
		$sht->setTitle("INC_FG");
		$sht->setCellValueByColumnAndRow(1, 1, "WO NO");
		$sht->setCellValueByColumnAndRow(2, 1, "MODEL CODE");
		$sht->setCellValueByColumnAndRow(3, 1, "GRN QTY");
		$no=2;
		foreach($rs as $r){
			$qty = is_null($r['QTY_WH']) ? 0 : number_format($r['QTY_WH']);
			$sht->setCellValueByColumnAndRow(1, $no, trim($r['SER_DOC']));
			$sht->setCellValueByColumnAndRow(2, $no, trim($r['SER_ITMID']));
			$sht->setCellValueByColumnAndRow(3, $no, $qty);
			$no++;
		}
		$writer = new Xlsx($spr);
		$filename = "INC FG FOR MEGA.xlsx";
		header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
		header('Content-Disposition: attachment;filename="'. $filename .'"'); 
		header('Cache-Control: max-age=0');
		$writer->save('php://output');		
	}

	public function vroutfg(){
		echo 'baking....';
	}
    	
	public function search(){
		header('Content-Type: application/json');
		$cid = $this->input->get('inItem');
        $rs = $this->ITH_mod->selectAll($cid);	
		echo json_encode($rs);
	}

	public function vclosing(){
		$data['lwh'] = $this->MSTLOCG_mod->selectall();
		$this->load->view('wms/vclosinginv', $data);
	}

	public function compareinventory(){
		header('Content-Type: application/json');
		$cwh = $this->input->get('inwh');
		$cdate = $this->input->get('indate');
				
		$rs = $this->ITH_mod->select_compare_inventory( $cwh,$cdate);
		echo '{"data":'.json_encode($rs).'}';		
	}

	public function tescat() {
		$rs =$this->MSTITM_mod->select_category();
		die(json_encode($rs));
	}

	public function get_stock_detail_as_xls(){
		ini_set('max_execution_time', '-1');
		$bg = $_COOKIE["CKPSI_BG"];
		$wh = $_COOKIE["CKPSI_WH"];
		$dt = $_COOKIE["CKPSI_DATE"];
		$thewh = $wh =="AFSMT" ? "AFWH3" : $wh;
		$rs = $this->ITH_mod->select_psi_stock_date_wbg_detail($thewh, $dt);
		$spreadsheet = new Spreadsheet();
		$sheet = $spreadsheet->getActiveSheet();
		$sheet->setTitle('stock_detail');	
		$sheet->setCellValueByColumnAndRow(1,1, 'Warehouse');
		$sheet->setCellValueByColumnAndRow(2,1, 'Item Code');		
		$sheet->setCellValueByColumnAndRow(3,1, 'Item Name');
		$sheet->setCellValueByColumnAndRow(4,1, 'SPTNO');
		$sheet->setCellValueByColumnAndRow(5,1, 'End Qty');
		$sheet->setCellValueByColumnAndRow(6,1, 'Unit Measurement');
		$sheet->setCellValueByColumnAndRow(7,1, 'ID');	
		$sheet->fromArray($rs, NULL, 'A2');
		$stringjudul = "stock  ".$dt;
		$writer = new Xlsx($spreadsheet);
		$filename=$stringjudul; //save our workbook as this file name
		
		header('Content-Type: application/vnd.ms-excel');
		header('Content-Disposition: attachment;filename="'. $filename .'.xlsx"'); 
		header('Cache-Control: max-age=0');
		$writer->save('php://output');
	}
	public function get_stock_recap_as_xls(){
		ini_set('max_execution_time', '-1');
		$bg = $_COOKIE["CKPSI_BG"];
		$wh = $_COOKIE["CKPSI_WH"];
		$dt = $_COOKIE["CKPSI_DATE"];
		$citem = "";		
		$rs = [];
		$str_bg = "";
		if(strlen(trim($bg))==0){
			$rs = $this->ITH_mod->select_psi_stock_date_wbg($wh, $citem, $dt);	
		} else {
			if(strpos($bg, ",")!==false){
				$abg = explode(",", $bg);
				$abg_c = count($abg);
				for($i=0; $i<$abg_c; $i++){
					$str_bg .= "'$abg[$i]',";
				}
				$str_bg = substr($str_bg,0,strlen($str_bg)-1);
			} else {
				$str_bg = "'$bg'";
			}
			$rs = $this->ITH_mod->select_psi_stock_date($wh, $citem, $str_bg, $dt);	
		}
		$spreadsheet = new Spreadsheet();
		$sheet = $spreadsheet->getActiveSheet();
		$sheet->setTitle('stock_recap');			
		$sheet->setCellValueByColumnAndRow(1,1, 'Warehouse');		
		$sheet->setCellValueByColumnAndRow(2,1, 'Item Code');		
		$sheet->setCellValueByColumnAndRow(3,1, 'Item Name');
		$sheet->setCellValueByColumnAndRow(4,1, 'SPTNO');
		$sheet->setCellValueByColumnAndRow(5,1, 'Opening');
		$sheet->setCellValueByColumnAndRow(6,1, 'IN');
		$sheet->setCellValueByColumnAndRow(7,1, 'PREPARE');	
		$sheet->setCellValueByColumnAndRow(8,1, 'OUT');	
		$sheet->setCellValueByColumnAndRow(9,1, 'CLOSING');	
		$sheet->setCellValueByColumnAndRow(10,1, 'Unit Measurement');
		$sheet->setCellValueByColumnAndRow(11,1, 'Category');
		$sheet->fromArray($rs, NULL, 'A2');
		foreach(range('A', 'K') as $v) {
			$sheet->getColumnDimension($v)->setAutoSize(true);			
		}
		//left align
		$rang = "A1:D".$sheet->getHighestDataRow();
		$sheet->getStyle($rang)->getAlignment()->setHorizontal(\PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_LEFT);
		if($wh==='AFSMT') {
			$sheet = $spreadsheet->createSheet();
			$sheet->setTitle('resume_category');			
			$sheet->setCellValueByColumnAndRow(1,1, 'Category');
			$sheet->setCellValueByColumnAndRow(2,1, 'Qty');
			$rscat = $this->MSTITM_mod->select_category();
			foreach($rscat as &$c) {
				$c['TTL'] = 0;
			}
			unset($c);
			foreach($rs as $r) {
				foreach($rscat as &$c) {
					if($c['MITM_NCAT'] === $r['MITM_NCAT']) {
						$c['TTL'] += $r['OUTQTY'];
					}
				}
				unset($c);
			}
			$i=2;
			foreach($rscat as $r) {
				$sheet->setCellValueByColumnAndRow(1,$i, $r['MITM_NCAT']);
				$sheet->setCellValueByColumnAndRow(2,$i, $r['TTL']);
				$i++;
			}
			foreach(range('A', 'B') as $v) {
				$sheet->getColumnDimension($v)->setAutoSize(true);
			}
		}

		$stringjudul = "stock ".$dt;
		$writer = new Xlsx($spreadsheet);
		$filename=$stringjudul; //save our workbook as this file name
		
		header('Content-Type: application/vnd.ms-excel');
		header('Content-Disposition: attachment;filename="'. $filename .'.xlsx"'); 
		header('Cache-Control: max-age=0');
		$writer->save('php://output');
	}

	public function form_report_critical_part() {
		$this->load->view('wms_report/vcritical_part');
	}

	public function getstock_wh(){
		date_default_timezone_set('Asia/Jakarta');
		header('Content-Type: application/json');
		$citem = $this->input->get('initem');
		$cdate = $this->input->get('indate');
		$cbgroup = $this->input->get('inbgroup');
		$sbgroup ="";
		if(is_array($cbgroup)){
			for($i=0;$i<count($cbgroup);$i++){
				$sbgroup .= "'$cbgroup[$i]',";
			}
			$sbgroup = substr($sbgroup,0,strlen($sbgroup)-1);
			if($sbgroup==''){
				$sbgroup ="''";
			}
		} else {
			$sbgroup = "-";
		}
		$odate = strtotime($cdate.' +1 days');
		$tomorrow  = date("Y-m-d 07:00:00", $odate);		
		$cwh = $_COOKIE["CKPSI_WH"];
		if($sbgroup=="-"){
			$rs =  $this->ITH_mod->select_psi_stock_date_wbg($cwh, $citem, $cdate);
		} else {
			$rs = $this->ITH_mod->select_psi_stock_date($cwh, $citem, $sbgroup, $cdate);	
		}
		$atomorrow = ['date' => $tomorrow];
		die('{"data":'.json_encode($rs).',"info": '.json_encode($atomorrow).'}');
	}

	public function test_getstock_1sql() {
		header('Content-Type: application/json');
		$citem = $this->input->post('initem');
		$rsfinal = [];
		foreach($citem as $i) {
			$rsfinal[] =  $this->ITH_mod->select_psi_stock_date_wbg_query('AFWH3', $i, '2021-08-11');
		}
		die('{"request":'.json_encode($citem).',"response":'.json_encode($rsfinal).'}');
	}

	public function test_getstock_allsql() {
		header('Content-Type: application/json');
		$citem = $this->input->post('initem');
		$rsfinal = [];
		foreach($citem as $i) {
			$rsfinal[] =  $this->ITH_mod->select_psi_stock_date_wbg_query('AFWH3', $i, '2021-08-11');
		}
		die('{"request":'.json_encode($citem).',"response":'.json_encode($rsfinal).'}');
	}

	public function getqtyrack(){
		$cwh = $_COOKIE["CKPSI_WH"];
		$cid = $this->input->get('inid');
		$dataw = ['SER_ID' => $cid];
		if($this->SER_mod->check_Primary($dataw)>0){
			
		} else {
			$myar = [];
			$myar[] = ["cd" => "00", "msg" => "ID not found"];
			echo '{"data":'.json_encode($myar),'}';
		}
	}

	public function vdisposerm(){
		$this->load->view('wms/vdispose_rm');
	}
	public function vdisposefg(){
		$this->load->view('wms/vdispose_fg');
	}

	public function getdisposerm(){
		header('Content-Type: application/json');
		$rs = $this->ITH_mod->select_stock_scrap_rm();
		echo '{"data":';
		echo json_encode($rs);
		echo '}';
	}
	public function getdisposefg(){
		header('Content-Type: application/json');
		$rs = $this->ITH_mod->select_stock_scrap_fg();
		echo '{"data":';
		echo json_encode($rs);
		echo '}';
	}

	public function dispose_fg_save(){
		date_default_timezone_set('Asia/Jakarta');
		$currdate = date('Ymd');
		$aid = $this->input->post('inid');
		$aitem = $this->input->post('initem');
		$aqty = $this->input->post('inqty');
		$cdate = $this->input->post('indate');
		$cwh = $this->input->post('inwh');
		$myar = array();
		$ttlrows = count($aitem);
		$rs = $this->ITH_mod->select_stock_scrap_fg();
		$lsno = $this->ITH_mod->lastsdoc_fg_dispose();
		$lsno++;
		$lastdoc = "DSPS".$currdate.$lsno;
		$ttlinserted = 0;
		for($i=0;$i< $ttlrows; $i++){
			foreach($rs as  $r){
				if( trim($aid[$i]) == trim($r['ITH_SER']) ){
					if((float)$aqty[$i] <= (float)$r['ITH_QTY']){
						$datas = [
							'ITH_SER' => $aid[$i],
							'ITH_ITMCD' => $aitem[$i],
							'ITH_DATE' => $cdate,
							'ITH_FORM' => 'OUT-SCR-FG',
							'ITH_DOC' => $lastdoc,
							'ITH_QTY' => -(float)$aqty[$i],
							'ITH_WH' =>  $cwh,
							'ITH_USRID' => $this->session->userdata('nama')
						];
						$resith = $this->ITH_mod->insert_disposefg($datas);
						$ttlinserted += $resith;
					}
				}
			}
		}
		if($ttlinserted>0){
			$datar = array("cd" => "1", "msg" => "Saved ". $ttlinserted );
		} else {
			$datar = array("cd" => "0", "msg" => "Nothing saved" );
		}
		array_push($myar, $datar);
		echo '{"info":';
		echo json_encode($myar);
		echo '}';
	}

	public function form_rm_d_allocation(){
		$this->load->view('wms_report/vith_detail');
	}

	public function dispose_save(){
		date_default_timezone_set('Asia/Jakarta');
		$currdate = date('Ymd');
		$aitem = $this->input->post('initem');
		$aqty = $this->input->post('inqty');
		$cdate = $this->input->post('indate');
		$cwh = $this->input->post('inwh');
		$myar = array();
		$ttlrows = count($aitem);
		$rs = $this->ITH_mod->select_stock_scrap_rm();
		$lsno = $this->ITH_mod->lastsdoc_dispose();
		$lsno++;
		$lastdoc = "DSP".$currdate.$lsno;
		$ttlinserted = 0;
		for($i=0;$i< $ttlrows; $i++){
			foreach($rs as  $r){
				if( trim($aitem[$i]) == trim($r['ITH_ITMCD']) ){				
					if((float)$aqty[$i] <= (float)$r['ITH_QTY']){						
						$datas = array(
							'ITH_ITMCD' => $aitem[$i],
							'ITH_DATE' => $cdate,
							'ITH_FORM' => 'OUT-SCR-RM',
							'ITH_DOC' => $lastdoc,
							'ITH_QTY' => -(float)$aqty[$i],
							'ITH_WH' =>  $cwh,
							'ITH_USRID' => $this->session->userdata('nama')
						);
						$resith = $this->ITH_mod->insert_disposerm($datas);
						$ttlinserted += $resith;
					}
				}
			}
		}
		if($ttlinserted>0){
			$datar = array("cd" => "1", "msg" => "Saved ". $ttlinserted );
		} else {
			$datar = array("cd" => "0", "msg" => "Nothing saved" );
		}
		array_push($myar, $datar);
		echo '{"info":';
		echo json_encode($myar);
		echo '}';
	}

	public function get_vis_fg_location(){
		$citem = $this->input->get('initem');
		$rs =$this->ITH_mod->select_fg_location_ploc($citem);
		$myar = [];		
		$myar[] = (count($rs)>0) ? ["cd" => "1", "msg" => "go ahead"] : ["cd" => "0", "msg" => "not found"] ;		
		die('{"status":'.json_encode($myar).',"data":'.json_encode($rs).'}');		
	}

	public function get_unscanned_FG_v1(){
		$this->checkSession();
		//SUMMARIZE UNSCANNED
		$myar = [];
		$rs = $this->ITH_mod->select_qcwh_unscan_recap_lastscan();
		if(count($rs) > 0) {
			$rack = '';
			foreach($rs as $r){
				$rack = $r['ITH_LOC'];
				break;
			}
			$msgtodis = '=== unscannded list ===<br>';
			$msgtodis .= '=== Loc. : '.$rack.' ===<br>';
			foreach($rs as $r){
				$msgtodis .= $r['ITH_ITMCD'] . ' '. $r['TTLBOX']. ' BOX <br>';
			}
			$myar[] = ['cd' => '1' , 'msg' => $msgtodis];
		} else {
			$myar[] = ['cd' => '0' , 'msg' => ''];
		}
		exit('{"status" : '.json_encode($myar).'}');
	}
	public function get_unscanned_FG_v2(){
		$this->checkSession();
		//SUMMARIZE UNSCANNED
		$myar = [];
		$rs = $this->ITH_mod->select_qcwh_unscan_recap();
		if(count($rs) > 0) {
			$rack = '';
			foreach($rs as $r){
				$rack = $r['ITH_LOC'];
				break;
			}
			$msgtodis = '=== unscannded list ===<br>';
			$msgtodis .= '=== Loc. : '.$rack.' ===<br>';
			foreach($rs as $r){
				$msgtodis .= $r['ITH_ITMCD'] . ' '. $r['TTLBOX']. ' BOX <br>';
			}
			$myar[] = ['cd' => '1' , 'msg' => $msgtodis];
		} else {
			$myar[] = ['cd' => '0' , 'msg' => ''];
		}
		exit('{"status" : '.json_encode($myar).'}');
	}

	public function gettxhistory(){		
		header('Content-Type: application/json');
		$cwh = $this->input->get('inwh');
		$citemcd = trim($this->input->get('initemcode'));
		$cdate1 = $this->input->get('indate1');
		$cdate2 = $this->input->get('indate2');
		$rsbef = $this->ITH_mod->select_txhistory_bef($cwh, $citemcd, $cdate1);
		$rs = $this->ITH_mod->select_txhistory($cwh, $citemcd, $cdate1, $cdate2);
		$rstoret = [];
		$myar = [];
		if(count($rsbef) >0){
			foreach($rsbef as $t){
				$rstoret[] = ['ITH_ITMCD' => $t['ITH_ITMCD'], 'MITM_ITMD1' => $t['MITM_ITMD1'] , 'ITH_FORM' => '', 'ITH_DOC' => '', 'ITH_DATEKU' => '' , 'INCQTY' => '', 'OUTQTY' => '',  'ITH_BAL' => $t['BALQTY']];				
				foreach($rs as $r){
					if($r['ITH_ITMCD'] == $t['ITH_ITMCD']) {
						$rstoret[]= $r;
					}
				}
			}
			$current_balance = 0;
			foreach($rstoret as &$r){
				if($r['ITH_FORM'] ==''){
					$current_balance = $r['ITH_BAL'];				
				} else {					
					$r['ITH_BAL'] = $current_balance+ $r['INCQTY'] + $r['OUTQTY'];
					$current_balance = $r['ITH_BAL'];
				}
				$r['OUTQTY'] = abs((float)$r['OUTQTY']);
			}
			unset($r);
			$myar[] = ['cd' => 1, 'msg' => 'go ahead'];
		} else {
			$myar[] = ['cd' => 0, 'msg' => 'not found'];			
		}
		exit('{"status": '.json_encode($myar).', "data" : '.json_encode($rstoret).'}');
	}
	public function gettxhistory_parent(){
		header('Content-Type: application/json');
		$fg_wh = ['AFWH3','AFWH3RT','NFWH4','NFWH4RT'];
		$cwh = $this->input->get('inwh');
		$citemcd = trim($this->input->get('initemcode'));
		$cdate1 = $this->input->get('indate1');
		$cdate2 = $this->input->get('indate2');
		if(in_array($cwh, $fg_wh)) {
			$rsbef =  $this->ITH_mod->select_txhistory_bef_parent_fg($cwh, $citemcd, $cdate1);
			$rs = $this->ITH_mod->select_txhistory_parent_fg($cwh, $citemcd, $cdate1, $cdate2);
		} else {
			$rsbef =  $this->ITH_mod->select_txhistory_bef_parent($cwh, $citemcd, $cdate1);
			$rs = $this->ITH_mod->select_txhistory_parent($cwh, $citemcd, $cdate1, $cdate2);
		}
		$rstoret = [];
		$myar = [];
		if(!empty($rs) && empty($rsbef)){
			$rsbef = [
				['ITRN_ITMCD' => $citemcd, 'MGAQTY' => 0, 'WQT' => 0]
			];
		}
		if(count($rsbef) >0){
			foreach($rsbef as $t){
				$rstoret[] = ['ITRN_ITMCD' => $t['ITRN_ITMCD'], 'ISUDT' => '' , 'MGAQTY' => '', 'WQT' => '',  'MGABAL' => $t['MGAQTY'], 'WBAL' => $t['WQT']];
				foreach($rs as $r){
					if($r['ITRN_ITMCD'] == $t['ITRN_ITMCD']) {
						$rstoret[]= $r;
					}
				}
			}
			$current_balance = 0;
			$current_balanceWMS = 0;
			foreach($rstoret as &$r){
				if($r['ISUDT'] ==''){
					$current_balance = $r['MGABAL'];				
					$current_balanceWMS = $r['WBAL'];				
				} else {					
					$r['MGABAL'] = $current_balance+ $r['MGAQTY'];
					$r['WBAL'] = $current_balanceWMS+ $r['WQT'];
					$current_balance = $r['MGABAL'];
					$current_balanceWMS = $r['WBAL'];
				}
			}
			unset($r);
			$myar[] = ['cd' => 1, 'msg' => 'go ahead'];
		} else {					
			$myar[] = ['cd' => 0, 'msg' => 'not found'];			
		}
		die(json_encode(['status' => $myar, 'data' => $rstoret]));
	}	
	public function gettxhistory_customs(){
		header('Content-Type: application/json');
		$searchby = $this->input->get('searchby');
		$citemcd = trim($this->input->get('initemcode'));
		$cdate1 = $this->input->get('indate1');
		$cdate2 = $this->input->get('indate2');
		$rsbef = [];
		$rs=[];
		switch($searchby){
			case 'itm':
				$rsbef = $this->ITH_mod->select_txhistory_customs_bef_d1($citemcd, $cdate1);
				$rs = $this->ITH_mod->select_txhistory_customs( ['RPSTOCK_ITMNUM' => $citemcd], $cdate2);
				break;
			case 'aju':
				$rsbef = $this->ITH_mod->select_txhistory_customs_bef_d1_byaju($citemcd);
				$rs = $this->ITH_mod->select_txhistory_customs( ['RPSTOCK_NOAJU' => $citemcd],  $cdate2);
				break;
			case 'daf':
				$rsbef = $this->ITH_mod->select_txhistory_customs_bef_d1_bydaftar($citemcd, $cdate1);
				$rs = $this->ITH_mod->select_txhistory_customs( ['RPSTOCK_BCNUM' => $citemcd],  $cdate2);
				break;
		}		
		$rstoret = [];
		$myar = [];
		if(count($rsbef) >0){
			foreach($rsbef as $t){
				$rstoret[] = [
					'RPSTOCK_ITMNUM' => $t['RPSTOCK_ITMNUM']
					,'MITM_ITMD1' => $t['MITM_ITMD1'] 
					,'AJU' => $t['RPSTOCK_NOAJU']
					,'DAFTAR' => $t['RPSTOCK_BCNUM']
					,'DOC' => $t['RPSTOCK_DOC']
					,'IODATE' => $t['INCDATE']
					,'INCQTY' => '', 'OUTQTY' => ''
					,'BAL' => $t['BALQTY']
					,'INCDATE' => $t['INCDATE']
					,'HEADER' => '1'
				];				
				foreach($rs as &$r){
					if($r['ITMCD'] == $t['RPSTOCK_ITMNUM'] && $r['RPSTOCK_NOAJU'] == $t['RPSTOCK_NOAJU'] && $r['RPSTOCK_DOC'] === $t['RPSTOCK_DOC'] && $r['MUSED'] == '') {
						$rstoret[]= $r;
						$r['MUSED']='PASS';
					}
				}
				unset($r);
			}
			$current_balance = 0;
			foreach($rstoret as &$r){
				if($r['HEADER'] =='1'){
					$current_balance = $r['BAL'];
				} else {
					$r['BAL'] = $current_balance+ $r['INCQTY'] + $r['OUTQTY'];
					$current_balance = $r['BAL'];
				}
				$r['OUTQTY'] = abs((int)$r['OUTQTY']);
			}
			unset($r);
			$myar[] = ['cd' => 1, 'msg' => 'go ahead'];
		} else {
			$myar[] = ['cd' => 0, 'msg' => 'not found'];			
		}
		exit('{"status": '.json_encode($myar).', "data" : '.json_encode($rstoret).'}');
	}	

	public function bcstock(){
		header('Content-Type: application/json');
		$incdoc = $this->input->post('incdoc');
		$incitem = $this->input->post('incitem');
		$outdoc = $this->input->post('outdoc');
		$rs = $this->ZRPSTOCK_mod->select_all_where(['RPSTOCK_REMARK' => $outdoc, 'RPSTOCK_ITMNUM' => $incitem, 'RPSTOCK_DOC' => $incdoc ]);
		die(json_encode(['data' => $rs]));
	}
	public function bcstock_cancel(){
		if ($this->session->userdata('status') != "login")
        {
			$myar[] = ["cd" => 0, "msg" => "Session is expired please reload page"];
			die(json_encode(['status' => $myar]));
        }
		date_default_timezone_set('Asia/Jakarta');
		header('Content-Type: application/json');
		$incdoc = $this->input->post('incdoc');
		$incitem = $this->input->post('incitem');
		$outdoc = $this->input->post('outdoc');
		$inpin = $this->input->post('inpin');
		$deleted_at = date('Y-m-d').' 18:00:00';
		if($inpin!='WEAGREE') {
			$myar[] = ['cd' => 0, 'msg' => 'PIN is not valid'];
			die('{"status":'.json_encode($myar).'}');
		}
		$respon = $this->ZRPSTOCK_mod->updatebyId(['RPSTOCK_REMARK' => $outdoc, 'RPSTOCK_ITMNUM' => $incitem, 'RPSTOCK_DOC' => $incdoc, 'deleted_at is null' => NULL ], ['deleted_at' => $deleted_at] );
		$this->LOGXDATA_mod->insert(['LOGXDATA_MENU' => 'CANCEL EXBC TX', 'LOGXDATA_USER' => $this->session->userdata('nama')]);
		$myar[] = ['cd' => 1, 'msg' => 'OK', 'respon' => $respon];
		die('{"status":'.json_encode($myar).'}');		
	}

	public function bcstock_rev(){
		header('Content-Type: application/json');
		$rsneed = $this->ZRPSTOCK_mod->query("SELECT RTRIM(RPSTOCK_ITMNUM) ITM,abs(SUM(RPSTOCK_QTY)) ITMQT FROM RPSAL_BCSTOCK WHERE deleted_at IS NULL AND RPSTOCK_REMARK='21/12/04/0006-R' AND RPSTOCK_BCDATE>='2021-10-31'
		GROUP BY RPSTOCK_ITMNUM
		order by 1");

		$rscandidate = $this->ZRPSTOCK_mod->query("SELECT * FROM RPSAL_BCSTOCK WHERE RPSTOCK_ITMNUM IN (
			SELECT RTRIM(RPSTOCK_ITMNUM) FROM RPSAL_BCSTOCK WHERE deleted_at IS NULL AND RPSTOCK_REMARK='21/12/04/0006-R' AND RPSTOCK_BCDATE>='2021-10-31'
			GROUP BY RPSTOCK_ITMNUM
			) AND deleted_at IS NULL AND (RPSTOCK_REMARK LIKE '%adj%' OR RPSTOCK_REMARK LIKE '%MIG%') 
			order by RPSTOCK_BCDATE desc");
		
		$rscanfix = [];
		foreach($rsneed as &$r){
			foreach($rscandidate as &$c){
				if($r['ITM']==RTRIM($c['RPSTOCK_ITMNUM'])){
					$tobe = $c['RPSTOCK_QTY'] + $r['ITMQT'];
					$c['RPSTOCK_QTY']=$tobe;
					$this->ZRPSTOCK_mod->updatebyId(['id' => $c['id']], ['RPSTOCK_QTY' => $tobe]);

					// $c['RPSTOCK_QTY'] += $r['ITMQT'];
					$rscanfix[] = $c;
					break;
				}
			}
			unset($c);
		}
		unset($r);
		die(json_encode(['data' => $rsneed, 'canfix' => $rscanfix]));
	}

	public function gettxhistory_to_xls(){	
		date_default_timezone_set('Asia/Jakarta');	
		if(!isset($_COOKIE["CKPSI_DDT1"]) || !isset($_COOKIE["CKPSI_DDT2"]) || !isset($_COOKIE["CKPSI_DITEMCD"]) || !isset($_COOKIE["CKPSI_DWH"]) ){
			exit('no data to be found');
		}

		$cwh = $_COOKIE["CKPSI_DWH"];
		$citemcd = trim($_COOKIE["CKPSI_DITEMCD"]);
		$cdate1 = $_COOKIE["CKPSI_DDT1"];
		$cdate2 = $_COOKIE["CKPSI_DDT2"];
		$rsbef = $this->ITH_mod->select_txhistory_bef($cwh, $citemcd, $cdate1);
		$rs = $this->ITH_mod->select_txhistory($cwh, $citemcd, $cdate1, $cdate2);
		$rstoret = [];
		$myar = [];
		if(count($rs) >0){
			foreach($rsbef as $t){
				$rstoret[] = ['ITH_ITMCD' => $t['ITH_ITMCD'], 'MITM_ITMD1' => $t['MITM_ITMD1'] , 'ITH_FORM' => '', 'ITH_DOC' => '', 'ITH_DATEKU' => '' , 'INCQTY' => '', 'OUTQTY' => '',  'ITH_BAL' => $t['BALQTY']];				
				foreach($rs as $r){
					if($r['ITH_ITMCD'] == $t['ITH_ITMCD']) {
						$rstoret[] = $r;
					}
				}
			}
			$current_balance = 0;
			foreach($rstoret as &$r){
				if($r['ITH_FORM'] ==''){
					$current_balance = $r['ITH_BAL'];
				} else {
					$r['ITH_BAL'] = $current_balance+ $r['INCQTY'] + $r['OUTQTY'];
					$current_balance = $r['ITH_BAL'];
				}
			}
			unset($r);
			$myar[] = ['cd' => 1, 'msg' => 'go ahead'];
		} else {
			$myar[] = ['cd' => 0, 'msg' => 'not found'];
		}
		$rs = null;
		unset($rs);
		$rsbef = null;
		unset($rsbef);
		$spreadsheet = new Spreadsheet();
		$sheet = $spreadsheet->getActiveSheet();
		$sheet->setTitle('TXHISTORY');
		$sheet->setCellValueByColumnAndRow(1,1, 'Date');
		$sheet->setCellValueByColumnAndRow(2,1, 'Item Code');
		$sheet->setCellValueByColumnAndRow(3,1, 'Item Name');
		$sheet->setCellValueByColumnAndRow(4,1, 'Warehouse');
		$sheet->setCellValueByColumnAndRow(5,1, 'Event');
		$sheet->setCellValueByColumnAndRow(6,1, 'Document');
		$sheet->setCellValueByColumnAndRow(7,1, 'QTY');
		$sheet->setCellValueByColumnAndRow(7,2, 'IN');
		$sheet->setCellValueByColumnAndRow(8,2, 'OUT');
		$sheet->setCellValueByColumnAndRow(9,2, 'BALANCE');
		$sheet->mergeCells('A1:A2');
		$sheet->mergeCells('B1:B2');
		$sheet->mergeCells('C1:C2');
		$sheet->mergeCells('D1:D2');
		$sheet->mergeCells('E1:E2');
		$sheet->mergeCells('F1:F2');
		$sheet->mergeCells('G1:I1');
		$sheet->getStyle('A1')->getAlignment()->setHorizontal('center')	;
		$sheet->getStyle('B1')->getAlignment()->setHorizontal('center')	;
		$sheet->getStyle('C1')->getAlignment()->setHorizontal('center')	;
		$sheet->getStyle('D1')->getAlignment()->setHorizontal('center')	;
		$sheet->getStyle('E1')->getAlignment()->setHorizontal('center')	;
		$sheet->getStyle('F1')->getAlignment()->setHorizontal('center')	;
		$sheet->getStyle('G1')->getAlignment()->setHorizontal('center')	;
		$sheet->getStyle('A1')->getAlignment()->setVertical(\PhpOffice\PhpSpreadsheet\Style\Alignment::VERTICAL_CENTER)	;
		$sheet->getStyle('B1')->getAlignment()->setVertical(\PhpOffice\PhpSpreadsheet\Style\Alignment::VERTICAL_CENTER)	;
		$sheet->getStyle('C1')->getAlignment()->setVertical(\PhpOffice\PhpSpreadsheet\Style\Alignment::VERTICAL_CENTER)	;
		$sheet->getStyle('D1')->getAlignment()->setVertical(\PhpOffice\PhpSpreadsheet\Style\Alignment::VERTICAL_CENTER)	;
		$sheet->getStyle('E1')->getAlignment()->setVertical(\PhpOffice\PhpSpreadsheet\Style\Alignment::VERTICAL_CENTER)	;
		$sheet->getStyle('F1')->getAlignment()->setVertical(\PhpOffice\PhpSpreadsheet\Style\Alignment::VERTICAL_CENTER)	;
		$sheet->getStyle('G1')->getAlignment()->setVertical(\PhpOffice\PhpSpreadsheet\Style\Alignment::VERTICAL_CENTER)	;
		$no=3;
		foreach($rstoret as $r){
			$sheet->setCellValueByColumnAndRow(1,$no, $r['ITH_DATEKU']);
			$sheet->setCellValueByColumnAndRow(2,$no, trim($r['ITH_ITMCD']));
			$sheet->setCellValueByColumnAndRow(3,$no, $r['MITM_ITMD1']);
			$sheet->setCellValueByColumnAndRow(4,$no, $cwh);
			$sheet->setCellValueByColumnAndRow(5,$no, $r['ITH_FORM']);
			$sheet->setCellValueByColumnAndRow(6,$no, $r['ITH_DOC']);
			$sheet->setCellValueByColumnAndRow(7,$no, $r['INCQTY']);
			$sheet->setCellValueByColumnAndRow(8,$no, abs($r['OUTQTY']));
			$sheet->setCellValueByColumnAndRow(9,$no, $r['ITH_BAL']);
			$no++;
		}
		$sheet->getColumnDimension('A')->setAutoSize(true);
        $sheet->getColumnDimension('B')->setAutoSize(true);
        $sheet->getColumnDimension('C')->setAutoSize(true);
        $sheet->getColumnDimension('D')->setAutoSize(true);
        $sheet->getColumnDimension('E')->setAutoSize(true);
        $sheet->getColumnDimension('F')->setAutoSize(true);
        $sheet->getColumnDimension('G')->setAutoSize(true);
        $sheet->getColumnDimension('H')->setAutoSize(true);
        $sheet->getColumnDimension('I')->setAutoSize(true);
		$cdtfrom = substr($cdate1,0,10);
		$stringjudul = "TX HISTORY $cwh, $cdtfrom TO $cdate2 ".date(' H i');
		$writer = new Xlsx($spreadsheet);		
		$filename=$stringjudul; //save our workbook as this file name
		
		header('Content-Type: application/vnd.ms-excel');
		header('Content-Disposition: attachment;filename="'. $filename .'.xlsx"'); 
		header('Cache-Control: max-age=0');
		$writer->save('php://output');
	}

	public function getlogexport($pn = 10){		
		header('Content-Type: application/json');
		$rs=$this->LOGXDATA_mod->selectlast_n($pn);
		echo '{"data": '.json_encode($rs).' }';
	}

	public function checkSession(){
		$myar = [];
		if ($this->session->userdata('status') != "login")
        {
			$myar[] = ["cd" => "0", "msg" => "Session is expired please reload page"];
			exit(json_encode($myar));
        }
	}

	public function traceid(){
		header('Content-Type: application/json');
		$cid = $this->input->get('inid');
		$rs = $this->ITH_mod->selectbyunique($cid);
		$myar = [];
		if(count($rs)>0){
			$myar[] = ['cd' => 1,'msg'=> 'go ahead'];
		} else {
			$myar[] = ['cd' => 0,'msg'=> 'there is no transaction'];
		}
		die('{"status": '.json_encode($myar).', "data":'.json_encode($rs).'}');
	}

	function sync_parent_based_doc() {
		header('Content-Type: application/json');
		$doc = $this->input->post('doc');
		$loccd = $this->input->post('loccd');
		$item = $this->input->post('item');
		$qty = $this->input->post('qty');
		$reff = $this->input->post('reff');
		$isudt = $this->input->post('isudt');
		$rs = [];
		$myar = [];
		if(substr($doc,0,3)=='TRF') {
			$rs[] = [
				'ITH_ITMCD' => $item,
				'ITH_DATE' => $isudt,
				'ITH_FORM' => $qty > 0 ? 'TRFIN-RM' : 'TRFOUT-RM',
				'ITH_DOC' => $doc,
				'ITH_WH' => $loccd,
				'ITH_QTY' => $qty,
				'ITH_REMARK' => $reff,
				'ITH_LUPDT' => $isudt.' 08:08:08',
				'ITH_USRID' => $this->session->userdata('nama'),
			];
			$rs[] = [
				'ITH_ITMCD' => $item,
				'ITH_DATE' => $isudt,
				'ITH_FORM' => $qty*-1 > 0 ? 'TRFIN-RM' : 'TRFOUT-RM',
				'ITH_DOC' => $doc,
				'ITH_WH' => $reff,
				'ITH_QTY' => $qty*-1,
				'ITH_REMARK' => $loccd,
				'ITH_LUPDT' => $isudt.' 08:08:08',
				'ITH_USRID' => $this->session->userdata('nama'),
			];
			$myar[] = ['cd' => 1, 'msg' => 'OK, please refresh to see the changes'];
			$this->ITH_mod->insertb($rs);
		} else {
			$rs[] = [
				'ITH_ITMCD' => $item,
				'ITH_DATE' => $isudt,
				'ITH_FORM' => $qty > 0 ? 'ADJ-INC' : 'ADJ-OUT',
				'ITH_DOC' => $doc,
				'ITH_WH' => $loccd,
				'ITH_QTY' => $qty,
				'ITH_REMARK' => $reff,
				'ITH_LUPDT' => $isudt.' 08:08:08',
				'ITH_USRID' => $this->session->userdata('nama'),
			];
			$this->ITH_mod->insertb($rs);
			$myar[] = ['cd' => 1, 'msg' => 'OK Adjusted, please refresh to see the changes'];
		}
		die(json_encode(['status' => $myar, 'data' => $rs]));
	}

	public function transaction() 
	{
		header('Content-Type: application/json');
		$fg_wh = ['AFWH3','AFWH3RT','NFWH4','NFWH4RT'];
		$date = $this->input->get('date');
		$item = $this->input->get('item');
		$location = $this->input->get('location');
		if(in_array($location, $fg_wh)){
			$rsParent = $this->XFTRN_mod->select_where(
				['FTRN_ISUDT ITRN_ISUDT', 'RTRIM(FTRN_DOCNO) ITRN_DOCNO', "(CASE WHEN FTRN_IOFLG = '1' THEN FTRN_TRNQT ELSE -1*FTRN_TRNQT END) QTY",'RTRIM(FTRN_REFNO1) ITRN_REFNO1']
				, ['FTRN_ISUDT' => $date, 'FTRN_ITMCD' => $item, 'FTRN_LOCCD' => $location] );
		} else {
			$rsParent = $this->XITRN_mod->select_where(
				['ITRN_ISUDT', 'RTRIM(ITRN_DOCNO) ITRN_DOCNO', "(CASE WHEN ITRN_IOFLG = '1' THEN ITRN_TRNQT ELSE -1*ITRN_TRNQT END) QTY",'RTRIM(ITRN_REFNO1) ITRN_REFNO1']
				, ['ITRN_ISUDT' => $date, 'ITRN_ITMCD' => $item, 'ITRN_LOCCD' => $location] );
		}
		
		$rsChild = $this->ITH_mod->select_view_where(['ITH_DATEC' => $date, 'ITH_ITMCD' => $item, 'ITH_WH' => $location]);
		die(json_encode(['parent' => $rsParent, 'child' => $rsChild]));
	}

	public function get_bcblc_tx(){
		header('Content-Type: application/json');
		$citem = $this->input->get('initem');
		$rs = $this->BCBLC_mod->select_balance($citem);
		die('{"data":'.json_encode($rs).'}');
	}

	public function searchbin(){
		header('Content-Type: application/json');
		$csearchby = $this->input->get('insearch_by');
		$csearchval = $this->input->get('insearch_val');
		$rs = [];
		switch($csearchby){
			case 'date':
				$rs = $this->ITH_mod->selectbin_history(['CONVERT(DATE,ITH_DATE)' => $csearchval]);break;
			case 'job':
				$rs = $this->ITH_mod->selectbin_history(['ITH_DOC' => $csearchval]);break;
			case 'reff':
				$rs = $this->ITH_mod->selectbin_history(['ITH_SER' => $csearchval]);break;
		}
		$myar[] = count($rs) > 0 ? ['cd' => 1, 'msg' => 'Go ahead'] : ['cd' => 0, 'msg' => 'not found'];
		die('{"tx":'.json_encode($rs).', "status":'.json_encode($myar).'}');
	}	

	public function change_dt_byreff(){
		$this->checkSession();
		header('Content-Type: application/json');
		$myar = [];
		$reffno = $this->input->post('inreffno');
		$forminc = $this->input->post('inform_trigger_inc');
		$formout = $this->input->post('inform_trigger_out');
		$newdate = $this->input->post('innewdate');
		$result = 0;
		$this->ITH_mod->tobin_backdate($this->session->userdata('nama'),$reffno, $forminc );
		$this->ITH_mod->tobin_backdate($this->session->userdata('nama'),$reffno, $formout );
		$result += $this->ITH_mod->updatebyId(['ITH_FORM' => $forminc, 'ITH_SER' => $reffno], ['ITH_LUPDT' => $newdate]);
		$result += $this->ITH_mod->updatebyId(['ITH_FORM' => $formout, 'ITH_SER' => $reffno], ['ITH_LUPDT' => $newdate]);
		$myar[] = $result>1 ? ['cd' => 1, 'msg' => 'OK'] : ['cd' => 0, 'msg' => 'Please contact admin'];
		die('{"status":'.json_encode($myar).'}');
	}

	public function calculate_rm_cutoff(){
		header('Content-Type: application/json');
		$cdate = $this->input->get('date');
		$rs = $this->SERD_mod->select_reff_cutoff_not_calculated($cdate);
		$pser = [];
		$pserqty = [];
		$pjob = [];
		foreach($rs as $r){
			$pser[] = $r['ITH_SER'];
			$pserqty[] = $r['STKQTY'];
			$pjob[] = $r['SER_DOC'];
		}
		if(count($pser)){
			$Calc_lib = new RMCalculator();
			$rs = $Calc_lib->calculate_raw_material_resume($pser, $pserqty, $pjob);
		}
		die('{"data":'.json_encode($rs).'}');
	}

	public function smtstock(){
		header('Content-Type: application/json');
		$cutoffdate = $this->input->get('date');
		$rschild = [];
		$rs_rm_null_c = $this->ITH_mod->select_rm_null_bo_zeroed_combined($cutoffdate);		
		$rs_rm_null = $this->ITH_mod->select_rm_null_bo_zeroed($cutoffdate); #because of set 0 
		$rs_rm_notnull = $this->SERD_mod->select_nonnull_rm($cutoffdate);
		$rs_rm_subassy = $this->SERD_mod->select_nonnull_rm_subassy($cutoffdate);			
		$a_sample = [];
		foreach($rs_rm_null as $r){
			if(!in_array($r['SERD2SER_SMP'], $a_sample)){
				$a_sample[] = $r['SERD2SER_SMP'];
			}
		}
		$rstemp = count($a_sample) > 0 ? $this->SERD_mod->select_group_byser($a_sample) : [];
		if(count($rstemp)) {
			foreach($rs_rm_null as $r){
				foreach($rstemp as $b){
					if($r['SERD2SER_SMP']==$b['SERD2_SER']){
						$rschild[] = [
							'ITH_ITMCD' => $b['ITH_ITMCD'],
							'BEFQTY' => $b['QTPER']* $r['BEFQTYFG'],
							'PLOTQTY' => 0,
							'ITH_WH' => $r['ITH_WH'],							
							'REMARK' => 'FROM SAMPLE '.$r['SERD2SER_SMP']
						];
					}
				}
			}
		}
		$rssummary =  [];
		if(count($rs_rm_notnull)){
			$rschild = array_merge($rschild, $rs_rm_notnull, $rs_rm_subassy, $rs_rm_null_c);		
			foreach($rschild as $n){
				$isfound = false;
				foreach($rssummary as &$s) {
					if($n['ITH_ITMCD']===$s['ITH_ITMCD']){
						$s['BEFQTY']+=$n['BEFQTY'];
						$isfound = true;
						break;
					}
				}
				unset($s);
				if(!$isfound){
					$rssummary[] = [
						'ITH_ITMCD' => $n['ITH_ITMCD'],
						'BEFQTY' => $n['BEFQTY']
					];
				}
			}
		}
		die(json_encode(['data' => $rssummary]));
	}

	public function getRMFromDeliveredFG() {
		header('Content-Type: application/json');
		$date1 = $this->input->get('date1');
		$date2 = $this->input->get('date2');
		$rs = $this->ITH_mod->select_RMFromDeliveredFG($date1, $date2);
		$rsCJ = $this->ITH_mod->select_RMFromDeliveredFG_CJ($date1, $date2);		
		$isCalculationOk = true;
		foreach($rs as &$r) {
			if(!$r['ITH_ITMCD']) {
				$isCalculationOk = false;
			}
			foreach($rsCJ as &$n) {
				if($r['ITH_ITMCD']===$n['ITH_ITMCD'] && $n['BEFQTY']>0) {
					$r['BEFQTY']+=$n['BEFQTY'];
					$n['BEFQTY']=0;
					break;
				}
			}
			unset($n);
		}
		unset($r);
		$myar = $isCalculationOk ? ['cd' => 1, 'msg' => 'Calculations is OK'] : ['cd' => 0, 'some Calculations are not OK'];
		die(json_encode(['data' => $rs, 'status' => $myar]));
	}

	public function out_wor_wip(){
		ini_set('max_execution_time', '-1'); 
		header('Content-Type: application/json');
		date_default_timezone_set('Asia/Jakarta');
		$date = $this->input->get('date');
		$msg = '';
		if(empty($date)){
			$msg = 'out_wor with current date';
			log_message('error', $msg);
			$date = date('Y-m-d');
		} else {
			$msg = 'out_wor with selected date ('.$date.')';
			log_message('error', $msg);
		}
		$rs = $this->ITH_mod->select_out_wip($date);
		$rszero = $this->ITH_mod->select_out_wip_zeroed($date);
		$a_sample = [];
		foreach($rszero as $r){
			if(!in_array($r['SERD2_SER_SMP'], $a_sample)){
				$a_sample[] = $r['SERD2_SER_SMP'];
			}
		}
		
		$rstemp = count($a_sample) > 0 ? $this->SERD_mod->select_group_byser($a_sample) : [];
		$tosave = [];
		if(count($rstemp)) {
			foreach($rszero as $r){
				foreach($rstemp as $b){
					if($r['SERD2_SER_SMP']==$b['SERD2_SER']){
						$tosave[] = [
							'ITH_FORM' => 'WOR'
							,'ITH_DATE' => substr($r['ITH_LUPDT'],0,10)
							,'ITH_LUPDT' => $r['ITH_LUPDT']
							,'ITH_ITMCD' => $b['ITH_ITMCD']
							,'ITH_DOC' => $r['SER_DOC']
							,'ITH_QTY' => -$r['SER_QTYLOT']*$b['QTPER']
							,'ITH_WH' => $r['OUTWH']
							,'ITH_REMARK' => $r['SER_ID']
						];						
					}
				}
			}
		}
		
		$rowaffected = 0;	
		foreach($rs as $r) {
			$tosave[] = [
				'ITH_FORM' => 'WOR'
				,'ITH_DATE' => substr($r['ITH_LUPDT'],0,10)
				,'ITH_LUPDT' => $r['ITH_LUPDT']
				,'ITH_ITMCD' => $r['SERD2_ITMCD']
				,'ITH_DOC' => $r['SER_DOC']
				,'ITH_QTY' => -$r['QTY']
				,'ITH_WH' => $r['OUTWH']
				,'ITH_REMARK' => $r['SER_ID']
			];
		}
		if($tosave){
			$rowaffected = $this->ITH_mod->insertb($tosave);
		}
		$myar[] = ['cd' => 1, 'msg' => $msg, 'rowa' => $rowaffected];
		log_message('error', 'finish out_wor');
		die(json_encode(['status' => $myar ]));
	}
	public function out_wor_wip_subassy(){
		ini_set('max_execution_time', '-1'); 
		header('Content-Type: application/json');
		date_default_timezone_set('Asia/Jakarta');
		$date = $this->input->get('date');
		$msg = '';
		if(empty($date)){
			$msg = 'out_wor with current date';
			log_message('error', $msg);
			$date = date('Y-m-d');
		} else {
			$msg = 'out_wor with selected date ('.$date.')';
			log_message('error', $msg);
		}
		$rs = $this->ITH_mod->select_out_wip_fromsubassy($date);						
		$tosave = [];	
		
		$rowaffected = 0;	
		foreach($rs as $r) {
			$tosave[] = [
				'ITH_FORM' => 'WOR'
				,'ITH_DATE' => substr($r['ITH_LUPDT'],0,10)
				,'ITH_LUPDT' => $r['ITH_LUPDT']
				,'ITH_ITMCD' => $r['SERD2_ITMCD']
				,'ITH_DOC' => $r['SER_DOC']
				,'ITH_QTY' => -$r['QTY']
				,'ITH_WH' => $r['OUTWH']
				,'ITH_REMARK' => $r['SERML_COMID']
			];
		}
		if($tosave){
			$rowaffected = $this->ITH_mod->insertb($tosave);
		}
		$myar[] = ['cd' => 1, 'msg' => $msg, 'rowa' => $rowaffected];
		log_message('error', 'finish out_wor');
		die(json_encode(['status' => $myar ]));
	}

	public function compare_tx_vs_customs(){
		header('Content-Type: application/json');
		$cutoffdate = $this->input->get('cutoffdate');
		$rschild = [];
		$rs_rm_null_c = $this->ITH_mod->select_rm_null_bo_zeroed_combined($cutoffdate);
		$rs_rm_null = $this->ITH_mod->select_rm_null_bo_zeroed($cutoffdate); #because of set 0 
		$rs_rm_notnull = $this->SERD_mod->select_nonnull_rm($cutoffdate);
		$rs_rm_subassy = $this->SERD_mod->select_nonnull_rm_subassy($cutoffdate);
		$rs_bcstock = $this->RCV_mod->select_bcstock($cutoffdate);
		$rsGrade = $this->MSTITM_mod->selectAllGrade(['MITMGRP_ITMCD','MITMGRP_ITMCD_GRD']);		
		$a_sample = [];
		foreach($rs_rm_null as $r){
			if(!in_array($r['SERD2SER_SMP'], $a_sample)){
				$a_sample[] = $r['SERD2SER_SMP'];
			}
		}
		$rstemp = count($a_sample) > 0 ? $this->SERD_mod->select_group_byser($a_sample) : [];
		if(count($rstemp)) {
			foreach($rs_rm_null as $r){
				foreach($rstemp as $b){
					if($r['SERD2SER_SMP']==$b['SERD2_SER']){
						$rschild[] = [
							'ITH_ITMCD' => $b['ITH_ITMCD'],
							'BEFQTY' => $b['QTPER']* $r['BEFQTYFG'],
							'PLOTQTY' => 0,
							'ITH_WH' => $r['ITH_WH'],							
							'REMARK' => 'FROM SAMPLE '.$r['SERD2SER_SMP']
						];
					}
				}
			}
		}
		if(count($rs_rm_notnull)){
			$rschild = array_merge($rschild, $rs_rm_notnull, $rs_rm_subassy, $rs_rm_null_c);
		}		
		#1st Filter
		foreach($rschild as &$r){
			foreach($rs_bcstock as &$b){
				if($r['ITH_ITMCD']==$b['RPSTOCK_ITMNUM'] && $r['BEFQTY']!=$r['PLOTQTY'] && $b['CURRENTSTOCK']>0){
					$balance = $r['BEFQTY']-$r['PLOTQTY'];
					if($balance>$b['CURRENTSTOCK']){						
						$r['PLOTQTY'] +=  $b['CURRENTSTOCK'];
						$b['CURRENTSTOCK'] = 0;
					} else {
						$b['CURRENTSTOCK'] -= $balance;
						$r['PLOTQTY'] += $balance;
					}
					if($r['BEFQTY']==$r['PLOTQTY']){
						break;
					}
				}
			}
			unset($b);
		}
		unset($r);
		#1st Filter rank
		foreach($rschild as &$r){			
			if ($r['BEFQTY']!=$r['PLOTQTY'] ) {
				foreach($rsGrade as $g) {
					if($g['MITMGRP_ITMCD_GRD']===$r['ITH_ITMCD']) {
						foreach($rs_bcstock as &$b){
							if($g['MITMGRP_ITMCD']==$b['RPSTOCK_ITMNUM'] && $r['BEFQTY']!=$r['PLOTQTY'] && $b['CURRENTSTOCK']>0){
								$balance = $r['BEFQTY']-$r['PLOTQTY'];
								if($balance>$b['CURRENTSTOCK']){							
									$r['PLOTQTY'] += $b['CURRENTSTOCK'];
									$b['CURRENTSTOCK'] = 0;
								} else {
									$b['CURRENTSTOCK'] -= $balance;
									$r['PLOTQTY'] += $balance;									
								}
								$r['REMARK'] .= 'BY '.$g['MITMGRP_ITMCD'];
								if($r['BEFQTY']===$r['PLOTQTY']){
									break;
								}
							}
						}
						unset($b);
						break;
					}
				}
			}
		}
		unset($r);	
		#end



		#2nd Filter
		$a_itemcdonly = [];		
		foreach($rschild as $r){
			if($r['BEFQTY']!=$r['PLOTQTY']){				
				if(!in_array($r['ITH_ITMCD'], $a_itemcdonly)) $a_itemcdonly[] = $r['ITH_ITMCD'];
				foreach($rsGrade as $g) {
					if($g['MITMGRP_ITMCD_GRD']===$r['ITH_ITMCD']) { 
						if(!in_array($g['MITMGRP_ITMCD'], $a_itemcdonly)) $a_itemcdonly[] = $g['MITMGRP_ITMCD'];
						break;
					} 
				}
			}
		}
		$a_itemcdonly_count = count($a_itemcdonly);
		$str = "";
		for($i=0; $i<$a_itemcdonly_count; $i++){
			$str .= "'".$a_itemcdonly[$i]."',";
		}
		$str = substr($str, 0, strlen($str)-1);
		$rsrcv = $this->RCV_mod->select_do_formigration($str, $cutoffdate);
		$rsrcv2 = [];

		foreach($rschild as &$r){
			foreach($rsrcv as &$b){
				if($r['ITH_ITMCD']==$b['PGRN_ITMCD'] && $r['BEFQTY']!=$r['PLOTQTY'] && $b['CURRENTSTOCK']>0){
					$balance = $r['BEFQTY']-$r['PLOTQTY'];
					if($balance>$b['CURRENTSTOCK']){						
						$r['PLOTQTY'] +=  $b['CURRENTSTOCK'];
						$b['CURRENTSTOCK'] = 0;
					} else {
						$b['CURRENTSTOCK'] -= $balance;
						$r['PLOTQTY'] += $balance;
					}
					if($r['BEFQTY']==$r['PLOTQTY']){
						break;
					}
				}
			}
			unset($b);
		}
		unset($r);
		
		#2nd filter rank
		foreach($rschild as &$r){
			if ($r['BEFQTY']!=$r['PLOTQTY'] ) {
				foreach($rsGrade as $g) { 
					if($g['MITMGRP_ITMCD_GRD']===$r['ITH_ITMCD']) { 
						foreach($rsrcv as &$b){
							if($g['MITMGRP_ITMCD']==$b['PGRN_ITMCD'] && $r['BEFQTY']!=$r['PLOTQTY'] && $b['CURRENTSTOCK']>0){
								$balance = $r['BEFQTY']-$r['PLOTQTY'];
								if($balance>$b['CURRENTSTOCK']){						
									$r['PLOTQTY'] +=  $b['CURRENTSTOCK'];
									$b['CURRENTSTOCK'] = 0;
								} else {
									$b['CURRENTSTOCK'] -= $balance;
									$r['PLOTQTY'] += $balance;
								}
								if($r['BEFQTY']==$r['PLOTQTY']){
									break;
								}
							}
						}
						unset($b);
						break;
					}
				}				
			}
		}
		unset($r);

		foreach($rsrcv as $r){
			if($r['CURRENTSTOCK']!=$r['TTLRCV']){
				$rsrcv2[] = $r;
			}
		}
		#end
		#find from ICS
		$itemFindInICS = [];
		foreach($rschild as $r){
			if($r['BEFQTY']!=$r['PLOTQTY']){
				$theitem = $r['ITH_ITMCD'];
				foreach($rsGrade as $g) { 
					if($g['MITMGRP_ITMCD_GRD']===$r['ITH_ITMCD']) { 
						$theitem = $g['MITMGRP_ITMCD'];
						break;
					}
				}
				if(!in_array($theitem, $itemFindInICS)) {
					$itemFindInICS[] = $theitem;
				}
			}
		}
		$rsics = $this->RCV_mod->select_ics($itemFindInICS, $cutoffdate);
		foreach($rschild as &$r){
			foreach($rsics as &$b){
				if($r['ITH_ITMCD']==$b['RPSTOCK_ITMNUM'] && $r['BEFQTY']!=$r['PLOTQTY'] && $b['CURRENTSTOCK']>0){
					$balance = $r['BEFQTY']-$r['PLOTQTY'];
					if($balance>$b['CURRENTSTOCK']){						
						$r['PLOTQTY'] +=  $b['CURRENTSTOCK'];
						$b['CURRENTSTOCK'] = 0;
					} else {
						$b['CURRENTSTOCK'] -= $balance;
						$r['PLOTQTY'] += $balance;
					}
					if($r['BEFQTY']==$r['PLOTQTY']){
						break;
					}
				}
			}
			unset($b);
		}
		unset($r);
		#filter rank ics
		foreach($rschild as &$r){
			if ($r['BEFQTY']!=$r['PLOTQTY'] ) {
				foreach($rsGrade as $g) { 
					if($g['MITMGRP_ITMCD_GRD']===$r['ITH_ITMCD']) { 
						foreach($rsics as &$b){
							if($g['MITMGRP_ITMCD']==$b['RPSTOCK_ITMNUM'] && $r['BEFQTY']!=$r['PLOTQTY'] && $b['CURRENTSTOCK']>0){
								$balance = $r['BEFQTY']-$r['PLOTQTY'];
								if($balance>$b['CURRENTSTOCK']){						
									$r['PLOTQTY'] +=  $b['CURRENTSTOCK'];
									$b['CURRENTSTOCK'] = 0;
								} else {
									$b['CURRENTSTOCK'] -= $balance;
									$r['PLOTQTY'] += $balance;
								}
								if($r['BEFQTY']==$r['PLOTQTY']){
									break;
								}
							}
						}
						unset($b);
						break;
					}
				}				
			}
		}
		unset($r);
		#end filter rank

		$rsics3 = $this->RCV_mod->select_ics3($itemFindInICS, $cutoffdate);
		foreach($rschild as &$r){
			foreach($rsics3 as &$b){
				if($r['ITH_ITMCD']==$b['RPSTOCK_ITMNUM'] && $r['BEFQTY']!=$r['PLOTQTY'] && $b['CURRENTSTOCK']>0){
					$balance = $r['BEFQTY']-$r['PLOTQTY'];
					if($balance>$b['CURRENTSTOCK']){						
						$r['PLOTQTY'] +=  $b['CURRENTSTOCK'];
						$b['CURRENTSTOCK'] = 0;
					} else {
						$b['CURRENTSTOCK'] -= $balance;
						$r['PLOTQTY'] += $balance;
					}
					if($r['BEFQTY']==$r['PLOTQTY']){
						break;
					}
				}
			}
			unset($b);
		}
		unset($r);
		$rsicsused = [];
		foreach($rsics as $r) {
			if($r['CURRENTSTOCK']!=$r['rcv_qty']){ 
				$r['REMARK'] = 'ics';
				$rsicsused[] = $r;
			}
		}
		foreach($rsics3 as $r) {
			if($r['CURRENTSTOCK']!=$r['rcv_qty']){ 
				$r['REMARK'] = 'ics3';
				$rsicsused[] = $r;
			}
		}
		die('{"data": '.json_encode($rschild).',"rsrcv2":'.json_encode($rsrcv2).',"icsdata":'.json_encode($rsicsused).'}');
	}

	public function deepSearch() {
		header('Content-Type: application/json');
		$rs = $this->SERD_mod->select_dedicateList();
		foreach($rs as &$r) {
			$rssub = $this->MSPP_mod->select_sub($r['SERD2_ITMCD'], $r['SER_ITMID']);
			foreach($rssub as $k) {
				$rsinc = $this->RCV_mod->select_do_formigration("'".$k['MSPP_SUBPN']."'", '2021-04-30');
				if(count($rsinc)) {
					$r['REMARK'] = "try ".$k['MSPP_SUBPN'];break;
				}
			}
		}
		unset($r);
		die('{"data":'.json_encode($rs).'}');
	}

	function form_st_itinventory() 
	{
		$this->load->view('wms/vst_itinventory');
	}

	public function adjust_base_mega() {
		$this->checkSession();
		header('Content-Type: application/json');
		date_default_timezone_set('Asia/Jakarta');
		$current_datetime = date('Y-m-d H:i:s');
		$cwh = $this->input->post('inwh');
		$cdate = $this->input->post('indate');
		$cpin = $this->input->post('inpin');
		$usrid =  $this->session->userdata('nama');
		$myar = [];
		#validate PIN
		if($cpin!='NAHCLOSING') {
			$myar[] = ['cd' => "0", 'msg' => 'PIN is not valid'];
			die('{"status":'.json_encode($myar).'}');
		}
		#end

		$dateObj = new DateTime($cdate);
		$dateObj->modify('+1 day');
		$dateTosave = $dateObj->format('Y-m-d');
		$dateTimeTosave = $dateObj->format('Y-m-d 06:59:59');
		$dateformat = $dateObj->format('Ym');
		$rs = $this->ITH_mod->select_compare_inventory( $cwh,$cdate); 
		$rsadj = [];
		foreach($rs as $r) {
			$balance = $r['STOCKQTY']-$r['MGAQTY'];
			if( $balance != 0) {
				if ($balance>0) {
					$ith_form = 'ADJ-I-OUT';
					$balance = -$balance;
				} else {
					$ith_form = 'ADJ-I-INC';
					$balance = abs($balance);
				}
				$rsadj[] = [
					'ITH_ITMCD' => $r['ITH_ITMCD'] ? $r['ITH_ITMCD'] : $r['ITRN_ITMCD'] ,
					'ITH_QTY' => $balance,
					'ITH_DATE' => $dateTosave,
					'ITH_LUPDT' => $dateTimeTosave,
					'ITH_WH' => $cwh,
					'ITH_DOC' => 'DOCINV'.$dateformat,
					'ITH_FORM' => $ith_form,
					'ITH_USRID' => $usrid,
					'ITH_REMARK' => 'do at '.$current_datetime
				];
			}
		}
		if ($this->ITH_mod->insertb($rsadj)) {
			$myar[] = ['cd' => "1", 'msg' => 'OK, just regenerate it'];
		} else {
			$myar[] = ['cd' => "0", 'msg' => 'Nothing adjusted'];
		}
		die('{"status":'.json_encode($myar).',"data":'.json_encode($rsadj).'}');
	}
	public function saveadjust_base_mega() {
		$this->checkSession();
		header('Content-Type: application/json');
		date_default_timezone_set('Asia/Jakarta');
		$current_datetime = date('Y-m-d H:i:s');
		$cwh = $this->input->post('inwh');
		$cdate = $this->input->post('indate');
		$cpin = $this->input->post('inpin');
		$usrid =  $this->session->userdata('nama');
		$myar = [];
		#validate PIN
		if($cpin!='NAHCLOSING_SAVE') {
			$myar[] = ['cd' => "0", 'msg' => 'PIN is not valid'];
			die('{"status":'.json_encode($myar).'}');
		}
		#end
		$cwh_inv = $cwh == 'AFSMT' ? 'AFWH3' : $cwh;

		$dateObj = new DateTime($cdate);
		$_MONTH = $dateObj->format('m');
		$_YEAR = $dateObj->format('Y');
		$WHERE = ['INV_MONTH' => $_MONTH, 'INV_YEAR' => $_YEAR, 'INV_WH' => $cwh_inv];
		$rssaved = $this->RPSAL_INVENTORY_mod->select_compare_where($WHERE);
		$dateObj->modify('+1 day');		
		$dateTimeTosave = $dateObj->format('Y-m-d 06:59:59');		
		$rs = $this->ITH_mod->select_compare_inventory( $cwh,$cdate); 
		$rsadj = [];
		foreach($rs as $r) {
			$balance = $r['STOCKQTY']-$r['MGAQTY'];
			if( $balance != 0 ) {
				if($cwh=='AFSMT' || $cwh=='AFWH3' || $cwh=='PSIEQUIP') {

				} else {
					$res = $this->RPSAL_INVENTORY_mod->check_Primary($WHERE);
					$myar[] = [
						'cd' => "0"
						, 'msg' => 'there is a discrepancy, please fix it first'
						, 'res' => $res, 'where' => $WHERE
						,'rssaved' => $rssaved
						,'diff1row' => $r
					];
					die(json_encode(['status' => $myar]));
					break;
				}
			}
		}
		$ttlupdated = 0;
		$ttlsaved = 0;
		$saverows = [];
		foreach($rs as $r) {
			$isfound = false;
			foreach($rssaved as $s){
				if(strtoupper($r['ITH_ITMCD']) == strtoupper($s['INV_ITMNUM'])){
					if($r['STOCKQTY']*1 != $s['INV_QTY']*1) {
						$WHERE['INV_ITMNUM'] = $s['INV_ITMNUM'];					
						$ttlupdated+=$this->RPSAL_INVENTORY_mod->updatebyVAR(['INV_QTY' => $r['STOCKQTY']*1, 'INV_DATE' =>  $cdate], $WHERE );
					}
					$isfound = true;
					break;
				}
			}
			if(!$isfound){
				$saverows[] = [
					'INV_ITMNUM' => $r['ITH_ITMCD']
					,'INV_MONTH' => $WHERE['INV_MONTH']
					,'INV_YEAR' => $WHERE['INV_YEAR']
					,'INV_WH' => $cwh == 'AFSMT' ? 'AFWH3' : $cwh
					,'INV_QTY' => $r['STOCKQTY']*1
					,'INV_DATE' => $cdate
					,'created_at' => $dateTimeTosave
				];
			}
		}
		if(count($saverows)){
			$ttlsaved = $this->RPSAL_INVENTORY_mod->insertb($saverows);
		}
		if($ttlsaved > 0 || $ttlupdated > 0){
			$myar[] = [
				'cd' => "1"
				, 'msg' => 'done, Total saved : '.$ttlsaved.' , Total updated :'.$ttlupdated
				,'rs' => $rs
			];
		} else {
			$myar[] = [
				'cd' => "1"
				, 'msg' => 'done, nothing changes'
			];
		}
		die('{"status":'.json_encode($myar).',"data":'.json_encode($rsadj).'}');
	}

	function inventory_date() {
		header('Content-Type: application/json');
		$invYear = $this->input->get('year');
		$invMonth = $this->input->get('month');
		$rs = $this->XICYC_mod->select_date(['YEAR(ICYC_STKDT)' => $invYear, 'MONTH(ICYC_STKDT)' => $invMonth]);
		die(json_encode(['data' => $rs]));
	}

	public function MEGAToInventory() {
		header('Content-Type: application/json');
		date_default_timezone_set('Asia/Jakarta');
		$current_datetime = date('Y-m-d H:i:s');
		$cwh = $this->input->post('inwh');
		$cdate = $this->input->post('indate');
		$dateObj = new DateTime($cdate);
		$_MONTH = $dateObj->format('m');
		$_YEAR = $dateObj->format('Y');
		$cwh_inv = $cwh;
		$WHERE = ['INV_MONTH' => $_MONTH, 'INV_YEAR' => $_YEAR, 'INV_WH' => $cwh_inv];
		$rssaved = $this->RPSAL_INVENTORY_mod->select_compare_where($WHERE);
		$rs = $this->XICYC_mod->select_for_it_inventory(['ICYC_STKDT' => $cdate, 'ICYC_WHSCD' => $cwh]);
		$rsadj = [];
		$ttlupdated = 0;
		$ttlsaved = 0;
		$saverows = [];
		foreach($rs as $r) {
			$isfound = false;
			foreach($rssaved as $s){
				if(strtoupper($r['ITH_ITMCD']) == strtoupper($s['INV_ITMNUM'])){
					if($r['STOCKQTY']*1 != $s['INV_QTY']*1) {
						$WHERE['INV_ITMNUM'] = $s['INV_ITMNUM'];					
						$ttlupdated+=$this->RPSAL_INVENTORY_mod->updatebyVAR(['INV_QTY' => $r['STOCKQTY']*1, 'INV_DATE' =>  $cdate], $WHERE );
					}
					$isfound = true;
					break;
				}
			}
			if(!$isfound){
				$saverows[] = [
					'INV_ITMNUM' => $r['ITH_ITMCD']
					,'INV_MONTH' => $WHERE['INV_MONTH']
					,'INV_YEAR' => $WHERE['INV_YEAR']
					,'INV_WH' => $cwh == 'AFSMT' ? 'AFWH3' : $cwh
					,'INV_QTY' => $r['STOCKQTY']*1
					,'INV_DATE' => $cdate
					,'created_at' => $current_datetime
				];
			}
		}
		if(count($saverows)){
			$ttlsaved = $this->RPSAL_INVENTORY_mod->insertb($saverows);
		}
		if($ttlsaved > 0 || $ttlupdated > 0){
			$myar[] = [
				'cd' => "1"
				, 'msg' => 'done, Total saved : '.$ttlsaved.' , Total updated :'.$ttlupdated
				,'rs' => $rs
			];
		} else {
			$myar[] = [
				'cd' => "1"
				, 'msg' => 'done, nothing changes'
				,'data' => $saverows
			];
		}
		die('{"status":'.json_encode($myar).',"data":'.json_encode($rsadj).'}');
	}

	public function MEGAAllLocationToInventory() {
		header('Content-Type: application/json');
		date_default_timezone_set('Asia/Jakarta');
		ini_set('max_execution_time', '-1');
		$current_datetime = date('Y-m-d H:i:s');		
		$cdate = $this->input->post('indate');
		$dateObj = new DateTime($cdate);
		$_MONTH = $dateObj->format('m');
		$_YEAR = $dateObj->format('Y');		
		$WHERE = ['INV_MONTH' => $_MONTH, 'INV_YEAR' => $_YEAR];
		$rssaved = $this->RPSAL_INVENTORY_mod->select_for_compare_mega($WHERE);
		$rs = $this->XICYC_mod->select_for_it_inventory(['ICYC_STKDT' => $cdate]);
		$rsadj = [];
		$ttlupdated = 0;
		$ttlsaved = 0;
		$saverows = [];
		foreach($rs as $r) {
			$isfound = false;
			foreach($rssaved as $s){
				if($r['ITH_ITMCD'] === $s['INV_ITMNUM'] && $r['ICYC_WHSCD']===$s['INV_WH']){
					if($r['STOCKQTY']*1 != $s['INV_QTY']*1) {
						$WHERE['INV_ITMNUM'] = $s['INV_ITMNUM'];
						$WHERE['INV_WH'] = $s['INV_WH'];
						$ttlupdated+=$this->RPSAL_INVENTORY_mod->updatebyVAR(['INV_QTY' => $r['STOCKQTY']*1, 'INV_DATE' =>  $cdate], $WHERE );
					}
					$isfound = true;
					break;
				}
			}
			if(!$isfound){
				$saverows[] = [
					'INV_ITMNUM' => $r['ITH_ITMCD']
					,'INV_MONTH' => $WHERE['INV_MONTH']
					,'INV_YEAR' => $WHERE['INV_YEAR']
					,'INV_WH' => $r['ICYC_WHSCD'] == 'AFSMT' ? 'AFWH3' : $r['ICYC_WHSCD']
					,'INV_QTY' => $r['STOCKQTY']*1
					,'INV_DATE' => $cdate
					,'created_at' => $current_datetime
				];
			}
		}
		if(count($saverows)){
			$ttlsaved = $this->RPSAL_INVENTORY_mod->insertb($saverows);
		}
		if($ttlsaved > 0 || $ttlupdated > 0){
			$myar[] = [
				'cd' => "1"
				,'msg' => 'Uploaded successfully'
				,'rs' => $rs
			];
		} else {
			if(count($rs)) {
				$myar[] = [
					'cd' => "1"
					,'msg' => 'nothing changes'
					,'data' => $saverows
				];
			} else {
				$myar[] = [
					'cd' => "0"
					,'msg' => 'Stock taking on '.$cdate.' is empty'
					,'data' => $saverows
				];
			}
		}
		die('{"status":'.json_encode($myar).',"data":'.json_encode($rsadj).'}');
	}

	public function tesics() {
		header('Content-Type: application/json');
		$rs = $this->RCV_mod->select_ics(['211313600'], '2021-04-30');
		die(json_encode($rs));
	}	

	public function scrap_balance() {
		header('Content-Type: application/json');
		$search = $this->input->get('search');
		$date0 = $this->input->get('date0');
		$rs = $this->ITH_mod->select_fordispose(['MITM_ITMD1' => $search], $date0);
		die(json_encode(['data' => $rs]));
	}

	public function balanceRA(){
		header('Content-Type: application/json');
		$rs = $this->ITH_mod->select_balanceRA();
		die(json_encode(['data' => $rs]));
	}
	
	public function form_unscan_prd_qc(){
		$this->load->view('wms_report/vrpt_unscan_prd_qc');
	}
	public function form_report_kka_mega(){
		$this->load->view('wms_report/vkka_mega');
	}	
	
	function sync_abnormal_transaction($pdate){
		date_default_timezone_set('Asia/Jakarta');
		$dateTimesBin = date('Y-m-d H:i:s');
		$date = $pdate;
		$rs = $this->ITH_mod->select_abnormal_kitting_tx($date);
		$adocs = [];
		$aitems = [];
		$isExist = false;
		$myar = [];
		$rsPatch = [];
		$rsPatchBin = [];
		foreach($rs as $r) {
			if(!in_array($r['ITH_DOC'], $adocs)) {
				$adocs[] = $r['ITH_DOC'];
				$isExist = true;
			}
			if(!in_array($r['ITH_ITMCD'], $aitems)) {
				$aitems[] = $r['ITH_ITMCD'];
				$isExist = true;
			}
		}
		if($isExist) {
			$qtylist = [];
			$rsdetail = $this->ITH_mod->select_abnormal_kitting_tx_detail($adocs, $aitems, $date);
			foreach($rs as $r) {
				if($r['TTLROWS']===1){
					foreach($rsdetail as $d) {
						if($r['ITH_ITMCD'] === trim($d['ITH_ITMCD']) && $r['ITH_DOC'] === trim($d['ITH_DOC'])) {
							$wh = '_';
							switch(trim($d['ITH_WH'])){
								case 'PLANT1' :
									$wh = 'ARWH1';break;
								case 'ARWH1':
									$wh = 'PLANT1';break;
								case 'PLANT2':
									$wh = 'ARWH2';break;
								case 'ARWH2':
									$wh = 'PLANT2';break;
							}							
							$rsPatch[] = [
								'ITH_ITMCD' => $r['ITH_ITMCD'],
								'ITH_DATE' => $d['ITH_DATE'],
								'ITH_FORM' => $d['ITH_FORM']==='INC-PRD-RM' ? 'OUT-WH-RM' : 'INC-PRD-RM',
								'ITH_DOC' => $r['ITH_DOC'],
								'ITH_QTY' => $d['ITH_QTY']*-1,
								'ITH_WH' => $wh,
								'ITH_LUPDT' => $d['ITH_LUPDT'],
								'ITH_USRID' => $d['ITH_USRID']
							];
							$rsPatchBin[] = [
								'ITH_ITMCD' => $r['ITH_ITMCD'],
								'ITH_DATE' => $d['ITH_DATE'],
								'ITH_FORM' => $d['ITH_FORM']==='INC-PRD-RM' ? 'OUT-WH-RM' : 'INC-PRD-RM',
								'ITH_DOC' => $r['ITH_DOC'],
								'ITH_QTY' => $d['ITH_QTY']*-1,
								'ITH_WH' => $wh,
								'ITH_LUPDT' => $d['ITH_LUPDT'],
								'ITH_USRID' => $d['ITH_USRID'],
								'ITH_LUPDTBIN' => $dateTimesBin,
								'ITH_REASON' => 'PATCH'
							];
							break;
						}						
					}
				} else {										
					foreach($rsdetail as $d) {
						if($r['ITH_ITMCD'] === trim($d['ITH_ITMCD']) && $r['ITH_DOC'] === trim($d['ITH_DOC'])) {
							$isfound = false;
							foreach($qtylist as &$n) {
								if($r['ITH_DOC'] === $n['ITH_DOC'] 
								&& $r['ITH_ITMCD'] === $n['ITH_ITMCD']
								&& abs($d['ITH_QTY']) === abs($n['ITH_QTY'])) {
									$n['COUNTQT']++; 
									$isfound = true;
									break;
								}
							}
							unset($n);
							if(!$isfound) {
								$qtylist[] = [
									'ITH_ITMCD' => $r['ITH_ITMCD'],
									'ITH_DOC' => $r['ITH_DOC'],
									'ITH_QTY' => abs($d['ITH_QTY']),
									'COUNTQT' => 1
								];
							}
						}
					}
				}
			}

			# patch for TTLROWS !=1
			$rs2 = array_filter($qtylist, function($value){
				return ($value['COUNTQT']==1);
			});

			# apply patch
			foreach($rs2 as $r) {
				foreach($rsdetail as $d) {
					if($r['ITH_ITMCD'] === trim($d['ITH_ITMCD']) && $r['ITH_DOC'] === trim($d['ITH_DOC']) 
						&& abs($r['ITH_QTY']) === abs($d['ITH_QTY'])
						){
						$wh = '_';
						switch(trim($d['ITH_WH'])){
							case 'PLANT1' :
								$wh = 'ARWH1';break;
							case 'ARWH1':
								$wh = 'PLANT1';break;
							case 'PLANT2':
								$wh = 'ARWH2';break;
							case 'ARWH2':
								$wh = 'PLANT2';break;
						}
						$rsPatch[] = [
							'ITH_ITMCD' => $r['ITH_ITMCD'],
							'ITH_DATE' => $d['ITH_DATE'],
							'ITH_FORM' => $d['ITH_FORM']==='INC-PRD-RM' ? 'OUT-WH-RM' : 'INC-PRD-RM',
							'ITH_DOC' => $r['ITH_DOC'],
							'ITH_QTY' => $d['ITH_QTY']*-1,
							'ITH_WH' => $wh,
							'ITH_LUPDT' => $d['ITH_LUPDT'],
							'ITH_USRID' => $d['ITH_USRID'],							
						];
						$rsPatchBin[] = [
							'ITH_ITMCD' => $r['ITH_ITMCD'],
							'ITH_DATE' => $d['ITH_DATE'],
							'ITH_FORM' => $d['ITH_FORM']==='INC-PRD-RM' ? 'OUT-WH-RM' : 'INC-PRD-RM',
							'ITH_DOC' => $r['ITH_DOC'],
							'ITH_QTY' => $d['ITH_QTY']*-1,
							'ITH_WH' => $wh,
							'ITH_LUPDT' => $d['ITH_LUPDT'],
							'ITH_USRID' => $d['ITH_USRID'],
							'ITH_LUPDTBIN' => $dateTimesBin,
							'ITH_REASON' => 'PATCH'
						];
						break;
					}
				}
			}	
			
			if(!empty($rsPatchBin)) {
				$this->ITH_mod->insertb_bin($rsPatchBin);
				$this->ITH_mod->insertb($rsPatch);
			}

			$myar[] = ['cd' => '1', 'mst' => 'ready to be inserted'
			, 'data' => $rsPatch
			, '$rs' => $rs
			, '$rsdetail' => $rsdetail
			, 'rs2' => $rs2
			, 'qtylist' => $qtylist
			];
		} else {
			$myar[] = ['cd' => '1', 'msg' => 'ok'];
		}
		return ['status' => $myar];
	}

	function sync_today_abnormal_transaction() {
		header('Content-Type: application/json');
		date_default_timezone_set('Asia/Jakarta');
		$date = date('Y-m-d');
		log_message('error', $_SERVER['REMOTE_ADDR'].', step1#, start, sync abnomral tx (today)');
		$result = $this->sync_abnormal_transaction($date);
		log_message('error', $_SERVER['REMOTE_ADDR'].', step1#, finish');
		die(json_encode($result));
	}

	function sync_yesterday_abnormal_transaction(){
		header('Content-Type: application/json');
		date_default_timezone_set('Asia/Jakarta');
		$date = date('Y-m-d',strtotime("-1 days"));
		log_message('error', $_SERVER['REMOTE_ADDR'].', step1#, start, sync abnomral tx (yesterday)');
		$result = $this->sync_abnormal_transaction($date);
		log_message('error', $_SERVER['REMOTE_ADDR'].', step1#, finish');
		die(json_encode($result));
	}

	function validateDate($date, $format = 'Y-m-d')
	{
		$d = DateTime::createFromFormat($format, $date);
		// The Y ( 4 digits year ) returns TRUE for any integer with any number of digits so changing the comparison from == to === fixes the issue.
		return $d && $d->format($format) === $date;
	}

	function sync_atdate_abnormal_transaction(){
		header('Content-Type: application/json');
		date_default_timezone_set('Asia/Jakarta');
		$date = $this->input->get('date');
		if(!$this->validateDate($date)) {
			die('valid date is required');
		}
		log_message('error', $_SERVER['REMOTE_ADDR'].', step1#, start, sync abnomral tx ('.$date.')');
		$result = $this->sync_abnormal_transaction($date);
		log_message('error', $_SERVER['REMOTE_ADDR'].', step1#, finish');
		die(json_encode($result));
	}

	function report_kka_mega(){
		if(!isset($_COOKIE["CKPSI_DDATE"]) || !isset($_COOKIE["CKPSI_DREPORT"]) && !isset($_COOKIE["CKPSI_DDATE2"])){
			exit('no data to be found');
		}	
		$date1 = $_COOKIE["CKPSI_DDATE"];
		$date2 = $_COOKIE["CKPSI_DDATE2"];
		$reportType = $_COOKIE["CKPSI_DREPORT"];
		$rs = [];
		$title = '';
		$spreadsheet = new Spreadsheet();
		$sheet = $spreadsheet->getActiveSheet();
		$sheet->setTitle('RESUME');
		$sheet->setCellValueByColumnAndRow(1,2, 'Item Code');
		$sheet->setCellValueByColumnAndRow(2,2, 'Item Description');
		$sheet->setCellValueByColumnAndRow(3,2, 'Saldo Awal');
		$sheet->setCellValueByColumnAndRow(4,2, 'Pemasukan');
		switch ($reportType) {
			case 'FG':
				$rs = $this->ITH_mod->select_KKA_MEGA_FG($date1, $date2);
				$title = 'Finished Goods';
				$sheet->setCellValueByColumnAndRow(5,2, 'Penyesuaian Pemasukan');
				$sheet->setCellValueByColumnAndRow(6,2, 'Pengeluaran');
				$sheet->setCellValueByColumnAndRow(7,2, 'Penyesuaian Pengeluaran');
				$sheet->setCellValueByColumnAndRow(8,2, 'Saldo Akhir');
				$i = 3;
				foreach($rs as $r) {
					$sheet->setCellValueByColumnAndRow(1,$i, $r['ITRN_ITMCD']);
					$sheet->setCellValueByColumnAndRow(2,$i, $r['MGMITM_ITMD1']);
					$sheet->setCellValueByColumnAndRow(3,$i, $r['B4QTY']); #C
					$sheet->setCellValueByColumnAndRow(4,$i, $r['INCQTY']); #D
					$sheet->setCellValueByColumnAndRow(5,$i, $r['ADJINCQTY']); #E
					$sheet->setCellValueByColumnAndRow(6,$i, $r['DLVQTY']); #F
					$sheet->setCellValueByColumnAndRow(7,$i, $r['ADJOUTQTY']); #G
					$sheet->setCellValueByColumnAndRow(8,$i, "=C$i+D$i+E$i-F$i-G$i");
					$i++;
				}
				foreach(range('A','O') as $r){
					$sheet->getColumnDimension($r)->setAutoSize(true);
				}
				$sheet->getStyle('A:A')->getAlignment()->setHorizontal(\PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_LEFT);
				$sheet->getStyle('A2:G'.($i-1))->getBorders()->getAllBorders()->setBorderStyle(\PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN);
				$sheet->setAutoFilter('A2:H2');
				$sheet->freezePane('C3');
				break;
			case 'FG-RTN':
				$rs = $this->ITH_mod->select_KKA_MEGA_FG_RTN($date1, $date2);
				$title = 'Return Finished Goods';
				$sheet->setCellValueByColumnAndRow(5,2, 'Penyesuaian Pemasukan');
				$sheet->setCellValueByColumnAndRow(6,2, 'Pengeluaran');
				$sheet->setCellValueByColumnAndRow(7,2, 'Penyesuaian Pengeluaran');
				$sheet->setCellValueByColumnAndRow(8,2, 'Saldo Akhir');
				$i = 3;
				foreach($rs as $r) {
					$sheet->setCellValueByColumnAndRow(1,$i, $r['ITRN_ITMCD']);
					$sheet->setCellValueByColumnAndRow(2,$i, $r['MGMITM_ITMD1']);
					$sheet->setCellValueByColumnAndRow(3,$i, $r['B4QTY']);
					$sheet->setCellValueByColumnAndRow(4,$i, $r['INCQTY']);
					$sheet->setCellValueByColumnAndRow(5,$i, $r['ADJINCQTY']);
					$sheet->setCellValueByColumnAndRow(6,$i, $r['DLVQTY']);
					$sheet->setCellValueByColumnAndRow(7,$i, $r['ADJOUTQTY']);
					$sheet->setCellValueByColumnAndRow(8,$i, "=C$i+D$i+E$i-F$i-G$i");
					$i++;
				}
				foreach(range('A','O') as $r){
					$sheet->getColumnDimension($r)->setAutoSize(true);
				}
				$sheet->getStyle('A:A')->getAlignment()->setHorizontal(\PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_LEFT);
				$sheet->getStyle('A2:G'.($i-1))->getBorders()->getAllBorders()->setBorderStyle(\PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN);
				$sheet->setAutoFilter('A2:H2');
				$sheet->freezePane('C3');
				break;
			case 'RM':
				$rs = $this->ITH_mod->select_KKA_MEGA_RM($date1, $date2);
				$title = 'Raw Material';
				$sheet->setCellValueByColumnAndRow(5,2, 'Pemasukan dari Produksi');
				$sheet->setCellValueByColumnAndRow(6,2, 'Penyesuaian Pemasukan');
				$sheet->setCellValueByColumnAndRow(7,2, 'Pengeluaran');
				$sheet->setCellValueByColumnAndRow(8,2, 'Penyesuaian Pengeluaran');
				$sheet->setCellValueByColumnAndRow(9,2, 'Saldo Akhir');
				$i = 3;
				foreach($rs as $r) {
					$sheet->setCellValueByColumnAndRow(1,$i, $r['ITRN_ITMCD']);
					$sheet->setCellValueByColumnAndRow(2,$i, $r['MGMITM_ITMD1']);
					$sheet->setCellValueByColumnAndRow(3,$i, $r['B4QTY']);
					$sheet->setCellValueByColumnAndRow(4,$i, $r['INCQTY']);
					$sheet->setCellValueByColumnAndRow(5,$i, $r['PRDINCQTY']);
					$sheet->setCellValueByColumnAndRow(6,$i, $r['ADJINCQTY']);
					$sheet->setCellValueByColumnAndRow(7,$i, $r['DLVQTY']);
					$sheet->setCellValueByColumnAndRow(8,$i, $r['ADJOUTQTY']);
					$sheet->setCellValueByColumnAndRow(9,$i, "=C$i+D$i+E$i+F$i-G$i-H$i");
					$i++;
				}
				foreach(range('A','O') as $r){
					$sheet->getColumnDimension($r)->setAutoSize(true);
				}
				$sheet->getStyle('A:A')->getAlignment()->setHorizontal(\PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_LEFT);
				$sheet->getStyle('A2:I'.($i-1))->getBorders()->getAllBorders()->setBorderStyle(\PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN);
				$sheet->setAutoFilter('A2:I2');
				$sheet->freezePane('C3');
				break;
		}
		$stringjudul = "KKA ".$title." $date1 to $date2";
		$writer = new Xlsx($spreadsheet);
		$filename=$stringjudul; //save our workbook as this file name
		
		header('Content-Type: application/vnd.ms-excel');
		header('Content-Disposition: attachment;filename="'. $filename .'.xlsx"'); 
		header('Cache-Control: max-age=0');
		$writer->save('php://output');
	}
	
	public function get_prdqc_unscan(){
		header('Content-Type: application/json');
		$rs = $this->ITH_mod->select_discrepancy_prd_qc();
		die(json_encode(['data' => $rs]));
	}

	public function calculate_WO_today(){
		date_default_timezone_set('Asia/Jakarta');
		header('Content-Type: application/json');
		$rsWO = $this->ITH_mod->select_WO_PRD_uncalculated();
		$Calc_lib = new RMCalculator();
		$myar = [];
		$libresponses = [];
		foreach($rsWO as $r){
			$libresponses[] = $Calc_lib->get_usage_rm_perjob($r->ITH_DOC);
		}
		$myar[] = ['cd' => 1, 'msg' => 'done', 'reff' => $libresponses];	
		log_message('error', "TODAY WO-PRD Calculation occur [".count($libresponses)."]");
		die(json_encode(['data' => $myar]));
	}

	public function breakdown_estimation() {	
		date_default_timezone_set('Asia/Jakarta');	
		$date = $this->input->post('date');
		$fglist = $this->input->post('fg');
		$WOStatus = $this->input->post('wostatus');
		$rmlist = $this->input->post('rm');
		if($date=='') die('could not continue');		
		$wh = 'PLANT1';
		$fgstring ="'".implode("','", $fglist)."'";
		$rmstring = "'".implode("','", $rmlist)."'";
		$startDate = date('Y-m-d',strtotime($date." - 60 days"));
		$rspsn = $this->ITH_mod->select_psn_period($startDate, $date, $rmstring);
		$psnstring = "";
		$osWO = [];
		foreach($rspsn as $r){
			$psnstring .= "'".$r['DOC']."',";
		}
		$psnstring = $psnstring=="" ? "''" : substr($psnstring,0,strlen($psnstring)-1);
		$osWO = $WOStatus == 'o' ? $this->ITH_mod->select_wo_side_detail_open($date, $fgstring) : $this->ITH_mod->select_wo_side_detail($date, $fgstring, $psnstring);		
		$rswip = $this->ITH_mod->select_wip_balance($date, $wh, $rmstring);
		
		$rsPlot = [];
		foreach($rswip as &$w) {
			$w['B4QTY'] = $w['MGAQTY'];
			foreach($osWO as &$o) {
				if($w['MGAQTY']>0 && ($w['ITRN_ITMCD'] === $o['PWOP_BOMPN'] || $w['ITRN_ITMCD'] === $o['PWOP_SUBPN']) ){
					$balneed = $o['NEEDQTY']-$o['PLOTQTY'];
					$fixqty = $balneed;
					if($balneed	> $w['MGAQTY']) {
						$fixqty = $w['MGAQTY'];
						$o['PLOTQTY'] += $w['MGAQTY'];
						$w['MGAQTY'] = 0;
					} else {
						$o['PLOTQTY'] += $balneed;
						$w['MGAQTY'] -= $balneed;
					}
					$isfound = false;
					foreach($rsPlot as &$r){
						if($r['WO'] == $o['PDPP_WONO'] && $r['PARTCD'] == $w['ITRN_ITMCD']) {
							$r['PARTQTY'] += $fixqty;
							$isfound = true;break;
						}
					}
					unset($r);
					if(!$isfound) {
						$rsPlot[] = ['WO' => $o['PDPP_WONO'], 'ISSUEDATE' => $o['PDPP_ISUDT'], 'UNIT' => $o['NEEDQTY']/$o['PWOP_PER'] , 'PER' => $o['PWOP_PER'], 'PARTCD' => $w['ITRN_ITMCD'],'REQQTY' => $o['NEEDQTY'], 'PARTQTY' => $fixqty];
					}
					if($w['MGAQTY']==0) {
						break;
					}
				}
			}
			unset($o);
		}
		unset($w);

		$shouldSearchDeep = false;
		foreach($rswip as $r) {
			if($r['MGAQTY']>0 && $r['B4QTY'] == $r['MGAQTY']) {
				$shouldSearchDeep = true;
				break;
			}
		}
		if($shouldSearchDeep) {
			$rspsn = $this->ITH_mod->select_psn_return_period($startDate, $date, $rmstring);
			$psnstring = "";
			$osWO = [];
			foreach($rspsn as $r){
				$psnstring .= "'".$r['DOC']."',";
			}
			$psnstring = $psnstring=="" ? "''" : substr($psnstring,0,strlen($psnstring)-1);
			$osWO = $WOStatus == 'o' ? $this->ITH_mod->select_wo_side_detail_open($date, $fgstring) : $this->ITH_mod->select_wo_side_detail($date, $fgstring, $psnstring);
			foreach($rswip as &$w) {
				if($w['MGAQTY']>0){
					$w['B4QTY'] = $w['MGAQTY'];
					foreach($osWO as &$o) {
						if($w['MGAQTY']>0 && ($w['ITRN_ITMCD'] === $o['PWOP_BOMPN'] || $w['ITRN_ITMCD'] === $o['PWOP_SUBPN']) ){
							$balneed = $o['NEEDQTY']-$o['PLOTQTY'];
							$fixqty = $balneed;
							if($balneed	> $w['MGAQTY']) {
								$fixqty = $w['MGAQTY'];
								$o['PLOTQTY'] += $w['MGAQTY'];
								$w['MGAQTY'] = 0;
							} else {
								$o['PLOTQTY'] += $balneed;
								$w['MGAQTY'] -= $balneed;
							}
							$isfound = false;
							foreach($rsPlot as &$r){
								if($r['WO'] == $o['PDPP_WONO'] && $r['PARTCD'] == $w['ITRN_ITMCD']) {
									$r['PARTQTY'] += $fixqty;
									$isfound = true;break;
								}
							}
							unset($r);
							if(!$isfound) {
								$rsPlot[] = ['WO' => $o['PDPP_WONO'], 'ISSUEDATE' => $o['PDPP_ISUDT'], 'UNIT' => $o['NEEDQTY']/$o['PWOP_PER'] , 'PER' => $o['PWOP_PER'], 'PARTCD' => $w['ITRN_ITMCD'],'REQQTY' => $o['NEEDQTY'], 'PARTQTY' => $fixqty];
							}
							if($w['MGAQTY']==0) {
								break;
							}
						}
					}
					unset($o);
				}
			}
			unset($w);
		}
		
		$rsFGResume = $this->ITH_mod->select_critical_FGStock($date, $fgstring);
		$spreadsheet = new Spreadsheet();
		$sheet = $spreadsheet->getActiveSheet();
		$sheet->setTitle('FG_RESUME');
		$rang = "A1:A".$sheet->getHighestDataRow();
		if(count($rsFGResume)){
			$sheet->fromArray(array_keys($rsFGResume[0]), NULL, 'A1');
			$sheet->fromArray($rsFGResume, NULL, 'A2');
			$sheet->getStyle($rang)->getAlignment()->setHorizontal(\PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_LEFT);
			foreach(range('A', 'K') as $v) {
				$sheet->getColumnDimension($v)->setAutoSize(true);
			}
		}

		if(count($rswip)){
			$sheet = $spreadsheet->createSheet();
			$sheet->setTitle('RM_RESUME');
			$sheet->fromArray(array_keys($rswip[0]), NULL, 'A1');
			$sheet->fromArray($rswip, NULL, 'A2');
			$sheet->getColumnDimension('C')->setVisible(false);
			$sheet->getStyle($rang)->getAlignment()->setHorizontal(\PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_LEFT);
			foreach(range('A', 'K') as $v) {
				$sheet->getColumnDimension($v)->setAutoSize(true);
			}
		}

		if(count($rsPlot)){
			$sheet = $spreadsheet->createSheet();
			$sheet->setTitle('PLOTWO');
			$sheet->fromArray(array_keys($rsPlot[0]), NULL, 'A1');
			$sheet->fromArray($rsPlot, NULL, 'A2');
			$sheet->getColumnDimension('F')->setVisible(false);
			$rang = "C1:C".$sheet->getHighestDataRow();
			$sheet->getStyle($rang)->getAlignment()->setHorizontal(\PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_LEFT);
			foreach(range('A', 'K') as $v) {
				$sheet->getColumnDimension($v)->setAutoSize(true);
			}
		}

		$stringjudul = "Critical Part $date";
		$writer = new Xlsx($spreadsheet);
		$filename=$stringjudul; //save our workbook as this file name
		
		header('Content-Type: application/vnd.ms-excel');
		header('Content-Disposition: attachment;filename="'. $filename .'.xlsx"'); 
		header('Cache-Control: max-age=0');
		$writer->save('php://output');
	}

	public function tx_compare(){
		$location = $this->input->post('location');
		$partcode = $this->input->post('partcode');
		$date = $this->input->post('date');

		
	}
}