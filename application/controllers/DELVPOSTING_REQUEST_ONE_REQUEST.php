<?php
defined('BASEPATH') OR exit('No direct script access allowed');
use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;
class DELV extends CI_Controller {
	private $AROMAWI = ['I','II','III', 'IV', 'V', 'VI', 'VII', 'VIII', 'IX', 'X', 'XI' , 'XII'];
	private $MSG_USERS = ['2200120','1190031','ane','206224','200993','28250','1160016','13221'];
	public function __construct()
	{
		parent::__construct();		
		$this->load->helper('url');
		$this->load->library('session');
		$this->load->library('Code39e128');
		$this->load->library('RMCalculator');
		$this->load->model('Usergroup_mod');
		$this->load->model('DELV_mod');
		$this->load->model('DLVSO_mod');
		$this->load->model('DLVPRC_mod');
		$this->load->model('SPL_mod');
		$this->load->model('SER_mod');
		$this->load->model('SPLSCN_mod');
		$this->load->model('ITH_mod');
		$this->load->model('Trans_mod');
		$this->load->model('MEXRATE_mod');
		$this->load->model('SERD_mod');
		$this->load->model('SERC_mod');
		$this->load->model('RCV_mod');
		$this->load->model('refceisa/ZWAYTRANS_mod');
		$this->load->model('refceisa/ZOffice_mod');
		$this->load->model('refceisa/AKTIVASIAPLIKASI_imod');
		$this->load->model('refceisa/MTPB_mod');
		$this->load->model('refceisa/MPurposeDLV_imod');
		$this->load->model('XBGROUP_mod');
		$this->load->model('SI_mod');
		$this->load->model('PTTRN_mod');
		$this->load->model('XSO_mod');
		$this->load->model('MSPP_mod');
		$this->load->model('SISO_mod');
		$this->load->model('TXROUTE_mod');
		$this->load->model('SISCN_mod');
		$this->load->model('MSTITM_mod');
		$this->load->model('ZRPSTOCK_mod');
		$this->load->model('PST_LOG_mod');
		$this->load->model('MSG_mod');
		$this->load->model('refceisa/TPB_HEADER_imod');
		$this->load->model('refceisa/TPB_KEMASAN_imod');
		$this->load->model('refceisa/TPB_DOKUMEN_imod');
		$this->load->model('refceisa/TPB_BARANG_imod');
		$this->load->model('refceisa/TPB_BARANG_TARIF_imod');
		$this->load->model('refceisa/TPB_BAHAN_BAKU_imod');
		$this->load->model('refceisa/TPB_BAHAN_BAKU_TARIF_imod');		
	}	

	function exception_handler($exception) {
		echo $exception->getMessage();
		
	}
	function fatal_handler() {
		$errfile = 'unknown file';
		$errstr = 'shutdown';
		$errno = E_CORE_ERROR;
		$errline = 0;
		$error = error_get_last();
		if( $error !== NULL) {
			$errno = $error['type'];
			$errfile = $error['file'];
			$errline = $error['line'];
			$errstr = $error['message'];			
		}
	}
	function log_error($errno='', $errstr='', $errfile='', $errline=''){
		throw new Exception('ERROR NO : '.$errno.' MESSAGE : '.$errstr.' LINE : '.$errline);		
		// throw new Exception('ERROR NO : '.$errno.' MESSAGE : '.$errstr.' FILE : '.$errfile.' LINE : '.$errline);		
	}

	public function testCalculator(){
		$calc = new RMCalculator();
		$apsn = ['SP-IEI-2021-08-1065','SP-IEI-2021-08-1066'
		,'SP-IEI-2021-08-1067','SP-IEI-2021-08-1068','SP-IEI-2021-08-1069'];
		$rspsn_sup = $calc->tobexported_list_for_serd($apsn);
		$rsused = $this->SERD_mod->selectall_where_psn_in($apsn);			
				
		#decrease usage by Used RM of another JOB
		foreach($rsused as &$d){
			if(!array_key_exists("USED", $d)){
				$d["USED"] = false;					
			}
		}
		unset($d);
				
		foreach($rspsn_sup as &$r){
			foreach($rsused as &$k){
				if(
					$r['SPLSCN_FEDR'] == trim($k['SERD_FR'])
				&& $r['SPLSCN_DOC'] == trim($k['SERD_PSNNO'])
				&& $r['PPSN2_MSFLG'] == trim($k['SERD_MSFLG'])
				&& $r['SPLSCN_ITMCD'] == trim($k['SERD_ITMCD'])
				&& $r['SPLSCN_LOTNO'] == trim($k['SERD_LOTNO'])
				&& $r['SPLSCN_ORDERNO'] == trim($k['SERD_MCZ'])
				&& $r['SPLSCN_LINE'] == trim($k['SERD_LINENO'])
				&& $r['PPSN2_MC'] == trim($k['SERD_MC'])
				&& $r['SPLSCN_CAT'] == trim($k['SERD_CAT'])
				&& $r['PPSN2_PROCD'] == trim($k['SERD_PROCD'])
				&& !$k['USED'] )
				{
					if(intval($r['SPLSCN_QTY'])>$k['SERD_QTY']){
						$r['SPLSCN_QTY'] -= $k['SERD_QTY'];
						$k['SERD_QTY'] = 0;
						$k['USED']=true;							
					} else {
						$k['SERD_QTY'] -= $r['SPLSCN_QTY']*1;
						$r['SPLSCN_QTY'] = 0;
						if($k['SERD_QTY']==0){
							$k['USED'] = true;
						}
						break;
					}
				}
			}
			unset($k);
		}
		unset($r);
		die(json_encode($rspsn_sup));
	}
	
	public function index()
	{
		die("sorry");
	}	

	public function vreturn_rm(){
		$data['lbg'] = $this->XBGROUP_mod->selectall();
		$data['lplatno'] = $this->Trans_mod->selectall();
		$data['ldeliverycode'] = $this->DELV_mod->select_delv_code();
		$data['ldestoffice'] = $this->ZOffice_mod->selectAll();
		$data['lkantorpabean'] = $this->MTPB_mod->selectAll();
		$data['lwaytransport'] = $this->ZWAYTRANS_mod->selectAll();
		$data['ltujuanpengiriman'] = $this->MPurposeDLV_imod->selectAll();
		$this->load->view('wms/vrm_ret_out', $data);
	}

	public function get_usage_rmwelcat_perjob($pjob){
		date_default_timezone_set('Asia/Jakarta');
		$crnt_dt = date('Y-m-d H:i:s');	
		$cuserid = $this->session->userdata('nama');
		$rspsn = $this->SPL_mod->select_z_getpsn_byjob("'".$pjob."'");
		$myar = [];
		$strpsn = '';
		$strdocno = '';
		$strmdlcd = '';
		foreach($rspsn as $r){
			$strpsn .= "'".trim($r['PPSN1_PSNNO'])."',";
			$strdocno = trim($r['PPSN1_DOCNO']);
			$strmdlcd = $r['PPSN1_MDLCD'];
		}
		$strpsn = substr($strpsn,0,strlen($strpsn)-1);
		if(trim($strpsn)!=''){
			$rsMSPP = $this->MSPP_mod->select_byvar(['MSPP_MDLCD' => $strmdlcd]);	
			$rspsnjob_req = $this->SPL_mod->select_psnjob_req($strdocno, $pjob);
			if(count($rspsnjob_req)==0){
				$myar[] = ['cd' => 0, 'msg' => 'pis3 null (welcat)'];
				return('{"status": '.json_encode($myar).'}');
			}
			foreach($rspsnjob_req as &$n){
				if(!array_key_exists("SUPQTY", $n)){
					$n["SUPQTY"] = 0;
				}
			}
			unset($n);

			$rspsn_sup = $this->SPLSCN_mod->select_scannedwelcat_bypsn($strpsn);
			$rspsn_req_d = [];
			if(count($rspsn_sup)==0){
				$myar[] = ['cd' => 1, 'msg' => 'scanned kitting null '.$pjob];
				return('{"status": '.json_encode($myar).'}');
			}

			#MAIN CALCULATION
			foreach($rspsnjob_req as &$n){	
				$processs = true;
				while($processs){
					$isready = false;
					foreach($rspsn_sup as $x){
						if( ( trim($n['PIS3_LINENO']) == trim($x['SPLSCN_LINE']) ) && 	( trim($n['PIS3_FR']) == trim($x['SPLSCN_FEDR']) ) 
						&& (trim($n['PIS3_MC']) == trim($x['PPSN2_MC'])) && (trim($n['PIS3_MCZ']) == trim($x['SPLSCN_ORDERNO']))
						&& (trim($n['PIS3_PROCD']) == trim($x['PPSN2_PROCD'])) && (trim($n['PIS3_MPART']) == trim($x['SPLSCN_ITMCD'])) && (trim($x['PPSN2_MSFLG'])=='M')){
							if($x['SPLSCN_QTY']>0 ){
								$isready=true;
								break;
							}
						}
					}
					if($isready){
						foreach($rspsn_sup as &$x){
							if( ( trim($n['PIS3_LINENO']) == trim($x['SPLSCN_LINE']) ) && ( trim($n['PIS3_FR']) == trim($x['SPLSCN_FEDR']) ) && (trim($n['PIS3_MC']) == trim($x['PPSN2_MC'])) 
								&& (trim($n['PIS3_MCZ']) == trim($x['SPLSCN_ORDERNO'])) && (trim($n['PIS3_PROCD']) == trim($x['PPSN2_PROCD'])) && (trim($n['PIS3_MPART']) == trim($x['SPLSCN_ITMCD'])) 
								&& (trim($x['PPSN2_MSFLG'])=='M')){	
								$process2 = true;
								while($process2){
									if(($n['PIS3_REQQTSUM'] > $n["SUPQTY"]) ){//sinii
										if($x['SPLSCN_QTY']>0 ){
											$n["SUPQTY"]+=1;
											$x['SPLSCN_QTY']--;
											$qtper = $n['MYPER'][0]=="." ? "0".$n['MYPER'] : $n['MYPER'];
											$islotfound = false;
											foreach($rspsn_req_d as &$u){
												if( ($u["SERD_JOB"] == trim($n["PIS3_WONO"])) && ($u["SERD_ITMCD"]== trim($x["SPLSCN_ITMCD"])) 
												&& ($u['SERD_MC'] == trim($x['PPSN2_MC']))  && ($u['SERD_MCZ'] == trim($x['SPLSCN_ORDERNO'])) && ($u['SERD_PROCD'] == trim($x['PPSN2_PROCD'])) 
												&& ($u['SERD_CAT'] ==  trim($x['SPLSCN_CAT']) ) && ( $u['SERD_MSFLG'] == trim($x['PPSN2_MSFLG']) )
												&& ($u['SERD_FR'] == trim($n['PIS3_FR'])) && ($u['SERD_QTPER'] == $qtper)
												&& ($u['SERD_REMARK'] == 'fmain_w_mcz' )){
													$u["SERD_QTY"]+=1;
													$islotfound=true;break;
												}
											}
											unset($u);

											if(!$islotfound){
												$rspsn_req_d[]= ["SERD_JOB" => trim($n["PIS3_WONO"]),"SERD_QTPER" => $qtper ,"SERD_ITMCD" => trim($x['SPLSCN_ITMCD']), "SERD_QTY" => 1 , 
													"SERD_PSNNO" => strtoupper(trim($x['SPLSCN_DOC'])), "SERD_LINENO" => trim($n['PIS3_LINENO']),
													"SERD_CAT" => trim($x['SPLSCN_CAT']), "SERD_FR" => trim($n['PIS3_FR']), "SERD_QTYREQ" => intval($n['PIS3_REQQTSUM']),
													"SERD_MC" => trim($n['PIS3_MC']) , "SERD_MCZ" => trim($x['SPLSCN_ORDERNO']), "SERD_PROCD" => trim($x['PPSN2_PROCD']),
													"SERD_MSFLG" => trim($x['PPSN2_MSFLG']) ,"SERD_USRID" => $cuserid, "SERD_LUPDT" => $crnt_dt,
													"SERD_REMARK" => "fmain_w_mcz" ] ;
											}	
										} else {
											$process2 =false;
										}
									} else {
										$process2 =false;
										$processs = false;
									}
								}
							} else {
								$processs =false;
							}
						}
						unset ($x);		
					} else {
						$processs = false;
					}
				}
			}
			unset($n);

			

			#SUB CALCULATION
			foreach($rspsnjob_req as &$n){	
				$processs = true;
				while($processs){
					$isready = false;
					foreach($rspsn_sup as $x){
						#CHECK MASTER SUB PART
						$issubtitue = false;
						foreach($rsMSPP as $ms){
							if($ms['MSPP_BOMPN'] == $n['PIS3_MPART'] && $ms['MSPP_SUBPN']==$x['SPLSCN_ITMCD']){
								$issubtitue = true;
								break;
							}
						}
						#END CHECK
						if( ( trim($n['PIS3_LINENO']) == trim($x['SPLSCN_LINE']) ) && 	( trim($n['PIS3_FR']) == trim($x['SPLSCN_FEDR']) ) 
						&& (trim($n['PIS3_MC']) == trim($x['PPSN2_MC'])) && (trim($n['PIS3_MCZ']) == trim($x['SPLSCN_ORDERNO']))
						&& (trim($n['PIS3_PROCD']) == trim($x['PPSN2_PROCD'])) &&  (trim($x['PPSN2_MSFLG'])=='S')
						&& $issubtitue ){
							if($x['SPLSCN_QTY']>0 ){
								$isready=true;
								break;
							}
						}
					}
					if($isready){
						foreach($rspsn_sup as &$x){
							#CHECK MASTER SUB PART
							$issubtitue = false;
							foreach($rsMSPP as $ms){
								if($ms['MSPP_BOMPN'] == $n['PIS3_MPART'] && $ms['MSPP_SUBPN']==$x['SPLSCN_ITMCD']){
									$issubtitue = true;
									break;
								}
							}
							#END CHECK
							if( ( trim($n['PIS3_LINENO']) == trim($x['SPLSCN_LINE']) ) && ( trim($n['PIS3_FR']) == trim($x['SPLSCN_FEDR']) ) && (trim($n['PIS3_MC']) == trim($x['PPSN2_MC'])) 
								&& (trim($n['PIS3_MCZ']) == trim($x['SPLSCN_ORDERNO'])) && (trim($n['PIS3_PROCD']) == trim($x['PPSN2_PROCD'])) 
								&& (trim($x['PPSN2_MSFLG'])=='S') && $issubtitue ){
								$process2 = true;
								while($process2){
									if(($n['PIS3_REQQTSUM'] > $n["SUPQTY"]) ){//sinii
										if($x['SPLSCN_QTY']>0 ){
											$n["SUPQTY"]+=1;
											$x['SPLSCN_QTY']--;
											$qtper = substr($n['MYPER'],0,1)=="." ? "0".$n['MYPER'] : $n['MYPER'];										
											$islotfound = false;
											foreach($rspsn_req_d as &$u){
												if( ($u["SERD_JOB"] == trim($n["PIS3_WONO"])) && ($u["SERD_ITMCD"]== trim($x["SPLSCN_ITMCD"])) 
												&& ($u['SERD_MC'] == trim($x['PPSN2_MC']))  && ($u['SERD_MCZ'] == trim($x['SPLSCN_ORDERNO'])) && ($u['SERD_PROCD'] == trim($x['PPSN2_PROCD']))  
												&& ($u['SERD_CAT'] ==  trim($x['SPLSCN_CAT']) ) && ( $u['SERD_MSFLG'] == trim($x['PPSN2_MSFLG']) )
												&& ($u['SERD_FR'] == trim($n['PIS3_FR']) ) ){
													$u["SERD_QTY"]+=1;
													$islotfound=true;break;
												}
											}
											unset($u);

											if(!$islotfound){
												$rspsn_req_d[] = ["SERD_JOB" => trim($n["PIS3_WONO"]),"SERD_QTPER" => $qtper ,"SERD_ITMCD" => trim($x['SPLSCN_ITMCD']), "SERD_QTY" => 1 , 
													"SERD_PSNNO" => strtoupper(trim($x['SPLSCN_DOC'])), "SERD_LINENO" => trim($n['PIS3_LINENO']),
													"SERD_CAT" => trim($x['SPLSCN_CAT']), "SERD_FR" => trim($n['PIS3_FR']), "SERD_QTYREQ" => intval($n['PIS3_REQQTSUM']),
													"SERD_MC" => trim($n['PIS3_MC']) , "SERD_MCZ" => trim($x['SPLSCN_ORDERNO']), "SERD_PROCD" => trim($x['PPSN2_PROCD']),
													"SERD_MSFLG" => trim($x['PPSN2_MSFLG']) ,"SERD_USRID" => $cuserid, "SERD_LUPDT" => $crnt_dt,
													"SERD_REMARK" => "fsub_w_mcz" ] ;
											}	
										} else {
											$process2 =false;
										}
									} else {
										$process2 =false;
										$processs = false;
									}
								}
							} else {
								$processs =false;
							}
						}
						unset ($x);		
					} else {
						$processs = false;
					}
				}
			}
			unset($n);

			##MAIN WITHOUT MCZ
			foreach($rspsnjob_req as &$n){	
				$processs = true;
				while($processs){
					$isready = false;
					foreach($rspsn_sup as $x){
						if( ( trim($n['PIS3_LINENO']) == trim($x['SPLSCN_LINE']) ) && 	( trim($n['PIS3_FR']) == trim($x['SPLSCN_FEDR']) ) 
						&& (trim($n['PIS3_MC']) == trim($x['PPSN2_MC'])) 
						&& (trim($n['PIS3_PROCD']) == trim($x['PPSN2_PROCD'])) && (trim($n['PIS3_MPART']) == trim($x['SPLSCN_ITMCD'])) && (trim($x['PPSN2_MSFLG'])=='M')){
							if($x['SPLSCN_QTY']>0 ){
								$isready=true;
								break;
							}
						}
					}
					if($isready){
						foreach($rspsn_sup as &$x){
							if( ( trim($n['PIS3_LINENO']) == trim($x['SPLSCN_LINE']) ) && ( trim($n['PIS3_FR']) == trim($x['SPLSCN_FEDR']) ) && (trim($n['PIS3_MC']) == trim($x['PPSN2_MC'])) 
								&& (trim($n['PIS3_PROCD']) == trim($x['PPSN2_PROCD'])) && (trim($n['PIS3_MPART']) == trim($x['SPLSCN_ITMCD'])) 
								&& (trim($x['PPSN2_MSFLG'])=='M')){	
								$process2 = true;
								while($process2){
									if(($n['PIS3_REQQTSUM'] > $n["SUPQTY"]) ){//sinii
										if($x['SPLSCN_QTY']>0 ){
											$n["SUPQTY"]+=1;
											$x['SPLSCN_QTY']--;
											$qtper = $n['MYPER'][0]=="." ? "0".$n['MYPER'] : $n['MYPER'];
											$islotfound = false;
											foreach($rspsn_req_d as &$u){
												if( ($u["SERD_JOB"] == trim($n["PIS3_WONO"])) && ($u["SERD_ITMCD"]== trim($x["SPLSCN_ITMCD"])) 
												&& ($u['SERD_MC'] == trim($x['PPSN2_MC']))  	&& ($u['SERD_MCZ'] == trim($x['SPLSCN_ORDERNO'])) && ($u['SERD_PROCD'] == trim($x['PPSN2_PROCD'])) 
												&& ($u['SERD_CAT'] ==  trim($x['SPLSCN_CAT']) ) && ( $u['SERD_MSFLG'] == trim($x['PPSN2_MSFLG']) )
												&& ($u['SERD_FR'] == trim($n['PIS3_FR']) ) ){
													$u["SERD_QTY"]+=1;
													$islotfound=true;break;
												}
											}
											unset($u);

											if(!$islotfound){
												$rspsn_req_d[]= ["SERD_JOB" => trim($n["PIS3_WONO"]),"SERD_QTPER" => $qtper ,"SERD_ITMCD" => trim($x['SPLSCN_ITMCD']), "SERD_QTY" => 1 , 
													"SERD_PSNNO" => strtoupper(trim($x['SPLSCN_DOC'])), "SERD_LINENO" => trim($n['PIS3_LINENO']),
													"SERD_CAT" => trim($x['SPLSCN_CAT']), "SERD_FR" => trim($n['PIS3_FR']), "SERD_QTYREQ" => intval($n['PIS3_REQQTSUM']),
													"SERD_MC" => trim($n['PIS3_MC']) , "SERD_MCZ" => trim($x['SPLSCN_ORDERNO']), "SERD_PROCD" => trim($x['PPSN2_PROCD']),
													"SERD_MSFLG" => trim($x['PPSN2_MSFLG']) ,"SERD_USRID" => $cuserid, "SERD_LUPDT" => $crnt_dt,
													"SERD_REMARK" => "fmain_w/o_mcz" ] ;
											}	
										} else {
											$process2 =false;
										}
									} else {
										$process2 =false;
										$processs = false;
									}
								}
							} else {
								$processs =false;
							}
						}
						unset ($x);		
					} else {
						$processs = false;
					}
				}
			}
			unset($n);
			#n MAIN CALCULATION

			##SUB WITHOUT MCZ
			foreach($rspsnjob_req as &$n){	
				$processs = true;
				while($processs){
					$isready = false;
					foreach($rspsn_sup as $x){
						#CHECK MASTER SUB PART
						$issubtitue = false;
						foreach($rsMSPP as $ms){
							if($ms['MSPP_BOMPN'] == $n['PIS3_MPART'] && $ms['MSPP_SUBPN']==$x['SPLSCN_ITMCD']){
								$issubtitue = true;
								break;
							}
						}
						#END CHECK
						if( ( trim($n['PIS3_LINENO']) == trim($x['SPLSCN_LINE']) ) && 	( trim($n['PIS3_FR']) == trim($x['SPLSCN_FEDR']) ) 
						&& (trim($n['PIS3_MC']) == trim($x['PPSN2_MC'])) 
						&& (trim($n['PIS3_PROCD']) == trim($x['PPSN2_PROCD'])) &&  (trim($x['PPSN2_MSFLG'])=='S')
						&& $issubtitue ){
							if($x['SPLSCN_QTY']>0 ){
								$isready=true;
								break;
							}
						}
					}
					if($isready){
						foreach($rspsn_sup as &$x){
							#CHECK MASTER SUB PART
							$issubtitue = false;
							foreach($rsMSPP as $ms){
								if($ms['MSPP_BOMPN'] == $n['PIS3_MPART'] && $ms['MSPP_SUBPN']==$x['SPLSCN_ITMCD']){
									$issubtitue = true;
									break;
								}
							}
							#END CHECK
							if( ( trim($n['PIS3_LINENO']) == trim($x['SPLSCN_LINE']) ) && ( trim($n['PIS3_FR']) == trim($x['SPLSCN_FEDR']) ) && (trim($n['PIS3_MC']) == trim($x['PPSN2_MC'])) 
								&& (trim($n['PIS3_PROCD']) == trim($x['PPSN2_PROCD'])) 
								&& (trim($x['PPSN2_MSFLG'])=='S') && $issubtitue ){
								$process2 = true;
								while($process2){
									if(($n['PIS3_REQQTSUM'] > $n["SUPQTY"]) ){//sinii
										if($x['SPLSCN_QTY']>0 ){
											$n["SUPQTY"]+=1;
											$x['SPLSCN_QTY']--;
											$qtper = substr($n['MYPER'],0,1)=="." ? "0".$n['MYPER'] : $n['MYPER'];										
											$islotfound = false;
											foreach($rspsn_req_d as &$u){
												if( ($u["SERD_JOB"] == trim($n["PIS3_WONO"])) && ($u["SERD_ITMCD"]== trim($x["SPLSCN_ITMCD"])) 
												&& ($u['SERD_MC'] == trim($x['PPSN2_MC']))  && ($u['SERD_MCZ'] == trim($x['SPLSCN_ORDERNO'])) && ($u['SERD_PROCD'] == trim($x['PPSN2_PROCD']))  
												&& ($u['SERD_CAT'] ==  trim($x['SPLSCN_CAT']) ) && ( $u['SERD_MSFLG'] == trim($x['PPSN2_MSFLG']) )
												&& ($u['SERD_FR'] == trim($n['PIS3_FR']) ) ){
													$u["SERD_QTY"]+=1;
													$islotfound=true;break;
												}
											}
											unset($u);

											if(!$islotfound){
												$rspsn_req_d[] = ["SERD_JOB" => trim($n["PIS3_WONO"]),"SERD_QTPER" => $qtper ,"SERD_ITMCD" => trim($x['SPLSCN_ITMCD']), "SERD_QTY" => 1 , 
													"SERD_PSNNO" => strtoupper(trim($x['SPLSCN_DOC'])), "SERD_LINENO" => trim($n['PIS3_LINENO']),
													"SERD_CAT" => trim($x['SPLSCN_CAT']), "SERD_FR" => trim($n['PIS3_FR']), "SERD_QTYREQ" => intval($n['PIS3_REQQTSUM']),
													"SERD_MC" => trim($n['PIS3_MC']) , "SERD_MCZ" => trim($x['SPLSCN_ORDERNO']), "SERD_PROCD" => trim($x['PPSN2_PROCD']),
													"SERD_MSFLG" => trim($x['PPSN2_MSFLG']) ,"SERD_USRID" => $cuserid, "SERD_LUPDT" => $crnt_dt,
													"SERD_REMARK" => "fsub_w/o_mcz" ] ;
											}	
										} else {
											$process2 =false;
										}
									} else {
										$process2 =false;
										$processs = false;
									}
								}
							} else {
								$processs =false;
							}
						}
						unset ($x);		
					} else {
						$processs = false;
					}
				}
			}
			unset($n);
			#n SUB CALCULATION

			#COMMON CALCULATION
			foreach($rspsnjob_req as &$n){	
				$processs = true;
				while($processs){
					$isready = false;
					foreach($rspsn_sup as $x){
						if( ( trim($n['PIS3_LINENO']) == trim($x['SPLSCN_LINE']) ) && 	( trim($n['PIS3_FR']) == trim($x['SPLSCN_FEDR']) ) 
						&& (trim($n['PIS3_MC']) == trim($x['PPSN2_MC'])) && (trim($n['PIS3_MCZ']) == trim($x['SPLSCN_ORDERNO']))
						&& (trim($n['PIS3_PROCD']) == trim($x['PPSN2_PROCD']))  ){
							if($x['SPLSCN_QTY']>0 ){
								$isready=true;
								break;
							}
						}
					}
					if($isready){
						foreach($rspsn_sup as &$x){
							if( ( trim($n['PIS3_LINENO']) == trim($x['SPLSCN_LINE']) ) && ( trim($n['PIS3_FR']) == trim($x['SPLSCN_FEDR']) ) && (trim($n['PIS3_MC']) == trim($x['PPSN2_MC'])) 
								&& (trim($n['PIS3_MCZ']) == trim($x['SPLSCN_ORDERNO'])) && (trim($n['PIS3_PROCD']) == trim($x['PPSN2_PROCD'])) 
								 ){
								$process2 = true;
								while($process2){
									if(($n['PIS3_REQQTSUM'] > $n["SUPQTY"]) ){//sinii
										if($x['SPLSCN_QTY']>0 ){
											$n["SUPQTY"]+=1;
											$x['SPLSCN_QTY']--;
											$qtper = substr($n['MYPER'],0,1)=="." ? "0".$n['MYPER'] : $n['MYPER'];										
											$islotfound = false;
											foreach($rspsn_req_d as &$u){
												if( ($u["SERD_JOB"] == trim($n["PIS3_WONO"])) && ($u["SERD_ITMCD"]== trim($x["SPLSCN_ITMCD"])) 
												&& ($u['SERD_MC'] == trim($x['PPSN2_MC']))  && ($u['SERD_MCZ'] == trim($x['SPLSCN_ORDERNO'])) && ($u['SERD_PROCD'] == trim($x['PPSN2_PROCD']))  
												&& ($u['SERD_CAT'] ==  trim($x['SPLSCN_CAT']) ) && ( $u['SERD_MSFLG'] == trim($x['PPSN2_MSFLG']) )
												&& ($u['SERD_FR'] == trim($n['PIS3_FR']) ) ){
													$u["SERD_QTY"]+=1;
													$islotfound=true;break;
												}
											}
											unset($u);

											if(!$islotfound){
												$rspsn_req_d[] = ["SERD_JOB" => trim($n["PIS3_WONO"]),"SERD_QTPER" => $qtper ,"SERD_ITMCD" => trim($x['SPLSCN_ITMCD']), "SERD_QTY" => 1 , 
												"SERD_PSNNO" => strtoupper(trim($x['SPLSCN_DOC'])), "SERD_LINENO" => trim($n['PIS3_LINENO']),
													"SERD_CAT" => trim($x['SPLSCN_CAT']), "SERD_FR" => trim($n['PIS3_FR']), "SERD_QTYREQ" => intval($n['PIS3_REQQTSUM']),
													"SERD_MC" => trim($n['PIS3_MC']) , "SERD_MCZ" => trim($x['SPLSCN_ORDERNO']), "SERD_PROCD" => trim($x['PPSN2_PROCD']),
													"SERD_MSFLG" => trim($x['PPSN2_MSFLG']) ,"SERD_USRID" => $cuserid, "SERD_LUPDT" => $crnt_dt,
													"SERD_REMARK" => "fcommon" ] ;
											}	
										} else {
											$process2 =false;
										}
									} else {
										$process2 =false;
										$processs = false;
									}
								}
							} else {
								$processs =false;
							}
						}
						unset ($x);		
					} else {
						$processs = false;
					}
				}
			}
			unset($n);
			#n COMMON CALCULATION
			
			if(count($rspsn_req_d)>0){
				$this->SERD_mod->insertb($rspsn_req_d);
				$myar[] = ['cd' => 1, 'msg' => 'ok inserted (welcat)'];
				return('{"status" : '.json_encode($myar).',"data": '.json_encode($rspsn_req_d).'}');
			} else {
				$myar[] = ['cd' => 0, 'msg' => 'calculated result is 0 (welcat) sinii'];
				return('{"status" : '.json_encode($myar).'}');
			}			
		} else {
			$myar [] = ['cd' => 0, 'msg' => 'Data kurang lengkap (welcat)'];
			return('{"status" : '.json_encode($myar).'}');
		}
	}	

	public function rm_extract(){
		$rs = $this->SERD_mod->select_uncalculated();
		// $this->get_usage_rmwelcat_perjob('20-9A08-208747800');
		ini_set('max_execution_time', '-1'); 
		foreach($rs as $r){
			if($this->SERD_mod->check_Primary(['SERD_JOB' =>  $r['SER_DOC']])==0 ){
				$this->get_usage_rmwelcat_perjob($r['SER_DOC']);
			}
		}
		die('finish');
	}

	
	public function calculate_raw_material(){
		header('Content-Type: application/json');
		$pser = $this->input->get('inunique');
		$pserqty = $this->input->get('inunique_qty');
		$pjob = $this->input->get('inunique_job');
		$Calc_lib = new RMCalculator();		
		$rs = $Calc_lib->calculate_raw_material($pser, $pserqty, $pjob);
		die($rs);
	}
	
	public function calculate_raw_material_welcat(){
		header('Content-Type: application/json');
		$pser = $this->input->get('inunique');
		$pserqty = $this->input->get('inunique_qty');
		$pjob = $this->input->get('inunique_job');
		$myar = [];
		$rsusage = [];
		$showed_cols = ['SERD2_PSNNO','SERD2_LINENO','SERD2_PROCD','SERD2_CAT','SERD2_FR','SERD2_ITMCD', 'SERD2_QTY','SERD2_FGQTY', 'RTRIM(MITM_ITMD1) MITM_ITMD1', 'RTRIM(MITM_SPTNO) MITM_SPTNO' , 'SERD2_QTPER MYPER', 'SERD2_MC', 'SERD2_MCZ'];
		if(count($this->SERC_mod->select_combined_jobs_definition_byreff($pser))>1){#			
			$comb_cols = ['SERC_COMID', 'SERC_COMJOB', 'SERC_COMQTY'];
			$rscomb_d = $this->SERC_mod->select_cols_where_id($comb_cols, $pser);
			$serlist = "";
			foreach($rscomb_d as $n){
				$serlist .= "'".$n['SERC_COMID']."',";
			}
			$serlist = substr($serlist,0,strlen($serlist)-1);
			$rsusage = $this->SERD_mod->select_calculatedlabel_where_in($showed_cols, $serlist); //check child reffno rm
			if(count($rsusage)==0){				
				#check usage per JOB
				$ttlcalculated = 0;
				foreach($rscomb_d as $n){
					if($this->SERD_mod->check_Primary(['SERD_JOB' => $n['SERC_COMJOB']]) ==0){
						$res0 = $this->get_usage_rmwelcat_perjob($n['SERC_COMJOB']);
						$res = json_decode($res0);
						if($res->status[0]->cd!=0){
							$res2 = json_decode($this->get_usage_rm_perjob_peruniq($n['SERC_COMID'], $n['SERC_COMQTY'], $n['SERC_COMJOB']));
							if($res2->status[0]->cd!=0){
								$ttlcalculated++;
							}
						}
					} else {
						$res2 = json_decode($this->get_usage_rm_perjob_peruniq($n['SERC_COMID'], $n['SERC_COMQTY'], $n['SERC_COMJOB']));
						if($res2->status[0]->cd!=0){
							$ttlcalculated++;
						}
					}
				}
				if($ttlcalculated>0){
					$myar[] = ['cd' => 0, 'msg' => 'combined jobs are recalculated ok'];
				} else {
					$myar[]= ['cd' => 0, 'msg' => 'calculating is failed. different jobs are combined'];
				}				
			} else {
				$myar[]= ['cd' => 1, 'msg' => 'combined job list found'];
			}
		} else {
			if($this->SERD_mod->check_Primary(['SERD_JOB' => $pjob]) ==0){
				$res0 = $this->get_usage_rmwelcat_perjob($pjob);			
				$res = json_decode($res0);
				if($res->status[0]->cd!=0){
					$rscalculated = $this->SERD_mod->select_calculatedlabel_where($showed_cols, ['SERD2_SER' => $pser]);
					if(count($rscalculated) >0){
						$issame = false;
						foreach($rscalculated as $r){
							if($pser!=$r['SERD2_FGQTY']){
								$issame = true;
								break;
							}
						}
						if(!$issame){
							$retdel = $this->SERD_mod->deletebyID_label(['SERD2_SER' => $pser]);
							$res2 = json_decode($this->get_usage_rm_perjob_peruniq($pser, $pserqty, $pjob));
							if($res2->status[0]->cd!=0){
								$myar[] = ['cd' => 1, 'msg' => 'recalculated ok'];
								$rsusage = $this->SERD_mod->select_calculatedlabel_where($showed_cols, ['SERD2_SER' => $pser]);
							} else {
								$myar[] = ['cd' => 0, 'msg' => 'recalculating is failed'];
							}
						} else {
							$myar[] = ['cd' => 1, 'msg' => 'just get'];
							$rsusage = $rscalculated;
						}
					} else { //if not yet calculated beforehand
						$res2 = json_decode($this->get_usage_rm_perjob_peruniq($pser, $pserqty, $pjob));
						if($res2->status[0]->cd!=0){
							$myar[] = ['cd' => 1, 'msg' => 'recalculated ok'];
							$rsusage = $this->SERD_mod->select_calculatedlabel_where($showed_cols, ['SERD2_SER' => $pser]);
						} else {
							$myar[] = ['cd' => 0, 'msg' => 'calculating is failed ('.$res->status[0]->msg.')'];
						}
					}
				} else { //if not yet calculated per job
					$myar[] = ['cd' => 0, 'msg' => 'calculating per job is failed ('.$res->status[0]->msg.')'];
				}
			} else {
				$rscalculated 		= $this->SERD_mod->select_calculatedlabel_where($showed_cols, ['SERD2_SER' => $pser]);
				if(count($rscalculated) >0){// if already calculated beforehand
					$issame = false;
					foreach($rscalculated as $r){
						if($pser!=$r['SERD2_FGQTY']){
							$issame = true;
							break;
						}
					}
					if(!$issame){
						$retdel = $this->SERD_mod->deletebyID_label(['SERD2_SER' => $pser]);
						$res2 = json_decode($this->get_usage_rm_perjob_peruniq($pser, $pserqty, $pjob));
						if($res2->status[0]->cd!=0){
							$myar[] = ['cd' => 1, 'msg' => 'recalculated, ok.'];
							$rsusage = $this->SERD_mod->select_calculatedlabel_where($showed_cols, 	['SERD2_SER' => $pser]);
						} else {
							$myar[] = ['cd' => 0, 'msg' => 'recalculating is failed.'];
						}
					} else {
						$myar[] = ['cd' => 1, 'msg' => 'just get.'];
						$rsusage = $rscalculated;
					}
				} else { //if not yet calculated beforehand
					$res2 = json_decode($this->get_usage_rm_perjob_peruniq($pser, $pserqty, $pjob));
					if($res2->status[0]->cd!=0){
						$myar[] = ['cd' => 1, 'msg' => 'recalculated ok.'];
						$rsusage = $this->SERD_mod->select_calculatedlabel_where($showed_cols, ['SERD2_SER' => $pser]);
					} else {
						$myar[]= ['cd' => 0, 'msg' => 'calculating is failed.'];
					}
				}
			}
		}
		
		die('{"status": '.json_encode($myar).', "data": '.json_encode($rsusage).'}');
	}
	

	public function tes(){
		header('Content-Type: application/json');
		$apsn = ['SP-IEI-2021-01-0741'];
		$wono = '21-2T05-219643911';
		$Calc_lib = new RMCalculator();	
		$rs = $Calc_lib->tobexported_list_for_serd(['SP-OMI-2021-07-0131','SP-OMI-2021-07-0132']);
		die(json_encode($rs));
	}

	public function tesperjob(){
		header('Content-Type: application/json');
		$Calc_lib = new RMCalculator();	
		$rs = $Calc_lib->get_usage_rm_perjob('21-7A16-2266363-6');
		die(json_encode($rs));
	}
	
	public function calculate_raw_material_resume(){
		header('Content-Type: application/json');
		ini_set('max_execution_time', '-1'); 
		$pser = $this->input->post('inunique');
		$pserqty = $this->input->post('inunique_qty');
		$pjob = $this->input->post('inunique_job');
		$Calc_lib = new RMCalculator();
		$rs = $Calc_lib->calculate_raw_material_resume($pser, $pserqty, $pjob);		
		die($rs);
	}

	public function calculate_per_uniq(){
		$pser = $this->input->post('inunique');
		$pserqty = $this->input->post('inunique_qty');
		$pjob = $this->input->post('inunique_job');
		$Calc_lib = new RMCalculator();
		$rs = $Calc_lib->get_usage_rm_perjob_peruniq($pser, $pserqty, $pjob);
		die($rs);
	}
		

	public function get_usage_rm_perjob_peruniq($pser,$pserqty,$pjob){
		date_default_timezone_set('Asia/Jakarta');
		$currrtime = date('Y-m-d H:i:s');
		$rsjobrm_d = $this->SERD_mod->selectd_byjob($pjob);
		$pserqty = str_replace(",", "", $pserqty);
		$rsjobrm = $this->SERD_mod->selecth_byjob($pjob,$pserqty);
		$rsser_d = [];
		$myar = [];
		if(count($rsjobrm)>0){
			foreach($rsjobrm as &$n){
				$processs = true;
				while($processs){
					$isready = false;
					foreach($rsjobrm_d as $x){
						if(  ($n['SERD_QTPER'] == $x['SERD_QTPER']) && 
						( trim($n['SERD_CAT']) == trim($x['SERD_CAT']) ) && ( trim($n['SERD_LINENO']) == trim($x['SERD_LINENO']) ) && ( trim($n['SERD_PROCD']) == trim($x['SERD_PROCD']) ) && 
						( trim($n['SERD_FR']) == trim($x['SERD_FR']) ) && ( trim($n['SERD_MC']) == trim($x['SERD_MC']) )  && ( trim($n['SERD_MCZ']) == trim($x['SERD_MCZ']) )){
							if($x['SERD_QTY']>0 ){
								$isready=true;
								break;
							}
						}
					}
					if($isready){
						foreach($rsjobrm_d as &$x){
							if(  ($n['SERD_QTPER'] == $x['SERD_QTPER']) &&
							( trim($n['SERD_CAT']) == trim($x['SERD_CAT']) ) && ( trim($n['SERD_LINENO']) == trim($x['SERD_LINENO']) ) && ( trim($n['SERD_PROCD']) == trim($x['SERD_PROCD']) ) && 
							( trim($n['SERD_FR']) == trim($x['SERD_FR']) ) && ( trim($n['SERD_MC']) == trim($x['SERD_MC']) )  && ( trim($n['SERD_MCZ']) == trim($x['SERD_MCZ']) ) ){
								$process2 = true;
								while($process2){
									if(($n['SERREQQTY'] > $n["SUPSERQTY"]) ){//sinii										
										if($x['SERD_QTY']>0 ){ //for awesome plotting , should add 'what next function'
											$n["SUPSERQTY"]+=1;
											$x['SERD_QTY']--;																						
											$islotfound = false;
											foreach($rsser_d as &$u){
												if( ($u["SERD2_JOB"]== trim($n['SERD_JOB'])) 
													&& ($u["SERD2_ITMCD"]== trim($x["SERD_ITMCD"])) 
													&& ($u['SERD2_QTPER'] == $n['SERD_QTPER']) 
													&& ($u["SERD2_LOTNO"]== trim($x["SERD_LOTNO"])) 
													&& ($u['SERD2_MC'] == trim($x['SERD_MC']) )  
													&& ($u['SERD2_MCZ'] == trim($x['SERD_MCZ']) )
													&& ($u['SERD2_PROCD'] == trim($x['SERD_PROCD']) ) 
													&& ($u['SERD2_FR'] == trim($x['SERD_FR']) ) 
													)
												{
													$u["SERD2_QTY"]+=1;
													$islotfound=true;break;
												}
											}
											unset($u);

											if(!$islotfound){
												$rsser_d[] = ["SERD2_SER" => $pser
															, "SERD2_JOB" => trim($n['SERD_JOB']) 
															, "SERD2_QTPER" => $n['SERD_QTPER']
															, "SERD2_ITMCD" => trim($x['SERD_ITMCD'])
															, "SERD2_FGQTY" => intval($pserqty)
															, "SERD2_QTY" => 1
															, "SERD2_LOTNO" => trim($x['SERD_LOTNO'])
															, "SERD2_PSNNO" => trim($x['SERD_PSNNO'])
															, "SERD2_LINENO" => trim($n['SERD_LINENO'])
															, "SERD2_PROCD" => trim($n['SERD_PROCD'])
															, "SERD2_CAT" => trim($n['SERD_CAT'])
															, "SERD2_FR" => trim($n['SERD_FR'])
															, "SERD2_MC" => trim($n['SERD_MC'])
															, "SERD2_MCZ" => trim($n['SERD_MCZ'])
															, "SERD2_MSCANTM" => $x['SERD_MSCANTM']
															,"SERD2_LUPDT" => $currrtime ];
											}											
										} else {
											$process2 = false;
										}
									} else {
										$process2 = false;
										$processs = false;
									}
								}
							}
						}
						unset($x);

					} else {
						$processs = false;
					}
				}
			}
			unset($n);

			if(count($rsser_d)>0){
				$this->SERD_mod->insertb2($rsser_d);
				$myar[] = ['cd' => 1, 'msg' => 'calculated per label ,ok'];
			} else {
				$myar[] = ['cd' => 0, 'msg' => 'calculated per label , not ok'];
			}
		} else {
			$myar[] = ['cd' => 0, 'msg' => 'Raw material usage has not been calculated yet'];
		}
		
		return('{"status": '.json_encode($myar).'}');
	}

	public function get_newdono(){
		header('Content-Type: application/json');
		date_default_timezone_set('Asia/Jakarta');
		$cip = gethostbyaddr($_SERVER['REMOTE_ADDR']);
		$cuserid = $this->session->userdata('nama');
		$myar = [];
		$rs= [];
		if($this->PTTRN_mod->select_total_rows()>0){
			$searchpar = ['PTTRN_USRID' => $cuserid, 'PTTRN_ID' => $cip];
			if($this->PTTRN_mod->check_Primary($searchpar)>0 ){
				$rs = $this->PTTRN_mod->select_all_where($searchpar);
				$myar[] = ['cd' => 1, 'msg' => 'go ahead'];
			} else {

			}
		} else {

		}
		die('{"status" : '.json_encode($myar).', "data" : '.json_encode($rs).'}');
	}

	public function searchcustomer_si(){
		header('Content-Type: application/json');
		$cbg = $this->input->get('cbg');
		$ccolumn = $this->input->get('csrchby');
		$csearch = $this->input->get('cid');
		$myar = [];
		$rs = [];
		switch($ccolumn){
			case 'nm':
				$rs = $this->SI_mod->select_customer_like(['SI_BSGRP' => $cbg ,'MCUS_CUSNM' => $csearch] );
				break;
			case 'cd':
				$rs = $this->SI_mod->select_customer_like(['SI_BSGRP' => $cbg ,'MCUS_CUSCD' => $csearch]);
				break;
			case 'ab':
				$rs = $this->SI_mod->select_customer_like(['SI_BSGRP' => $cbg ,'MCUS_ABBRV' => $csearch]);
				break;
			case 'ad':
				$rs = $this->SI_mod->select_customer_like(['SI_BSGRP' => $cbg ,'MCUS_ADDR1' => $csearch]);
				break;
		}
		if(count($rs)>0){
			$myar[] = ['cd' => 1, 'msg' => 'go ahead'];
		} else {
			$myar[] = ['cd' => 0, 'msg' => 'not found'];
		}
		die('{"status": '.json_encode($myar).', "data": '.json_encode($rs).'}');
	}

	public function activation(){
		header('Content-Type: application/json');
		$rs  = $this->AKTIVASIAPLIKASI_imod->selectAll();
		echo json_encode($rs);
	}

	public function create(){
		$data['lwaytransport'] = $this->ZWAYTRANS_mod->selectAll();
		$data['lplatno'] = $this->Trans_mod->selectall();
		$data['ldestoffice'] = $this->ZOffice_mod->selectAll();
		$data['lbg'] = $this->XBGROUP_mod->selectall();
		$data['ldeliverycode'] = $this->DELV_mod->select_delv_code();
		$data['lkantorpabean'] = $this->MTPB_mod->selectAll();
		$data['ltujuanpengiriman'] = $this->MPurposeDLV_imod->selectAll();
		$this->load->view('wms/vdelv', $data);
	}

	public function print_pickingdoc(){
		date_default_timezone_set('Asia/Jakarta');
		$Year = date('Y');
		$Month = intval(date('m'));
		$pser = '';
        if(isset($_COOKIE["PRINTLABEL_SI"])){
            $pser = $_COOKIE["PRINTLABEL_SI"];
		} else {
			exit('nothing to be printed');
		}
		$rspickingres = $this->DELV_mod->selectAllg_by($pser);
		$cpono = '';
		$rs = $this->SISO_mod->selectSObyYear('PSI1PPZIEP', 'SME007U', $Year, $Month);		
		if(count($rs)>0){
			foreach($rs as $r){
				$cpono = trim($r['SSO2_CPONO']);
			}
		} else {
			if(($Month-1)==0){
				$Month = 12;
				$rs = $this->SISO_mod->selectSObyYear('PSI1PPZIEP', 'SME007U', ($Year-1), $Month);
				foreach($rs as $r){
					$cpono = trim($r['SSO2_CPONO']);
				}
			} else {
				$Month -=1;
				$rs = $this->SISO_mod->selectSObyYear('PSI1PPZIEP', 'SME007U', ($Year), $Month);
				foreach($rs as $r){
					$cpono = trim($r['SSO2_CPONO']);
				}
			}
		}
		$pdf = new PDF_Code39e128('P','mm','A4');
		$pdf->AliasNbPages();

		$no = 1;
		$cury = 24;		
		$h_content = 16;

		$a_plant= [];
		$a_sidoc= [];
		$custDo = '';
		foreach($rspickingres as $r){
			if(!in_array(trim($r['SI_OTHRMRK']), $a_plant)){
				$a_plant[] = trim($r['SI_OTHRMRK']);
				$custDo = $r['DLV_CUSTDO'];
			}
			if(!in_array($r['SI_DOC'], $a_sidoc)){
				$a_sidoc[] = $r['SI_DOC'];				
			}
		}
		$s_sidoc = '';
		foreach($a_sidoc as $r) {
			$s_sidoc .= $r."|";
		}
		$s_sidoc = substr($s_sidoc, 0, strlen($s_sidoc)-1);

		$ttlplant = count($a_plant);

		for($n=0;$n<$ttlplant; $n++){	
			$myplant = '';
			switch($a_plant[$n]){
				case 'D116':
					$myplant = 'EPSON 1/2';break;
				case 'D120':
					$myplant = 'EPSON 3';break;
				case 'D114':
					$myplant = 'EPSON 4';break;
				default:
					$myplant ='';
			}
			$pdf->AddPage();
			$hgt_p = $pdf->GetPageHeight();
			$wid_p = $pdf->GetPageWidth();
			
			$pdf->SetAutoPageBreak(true,1);
			$pdf->SetMargins(0,0);
			$pdf->SetFont('Arial','',8);
			$pdf->SetXY(180,6);
			$pdf->Cell(15,4,'Page '.$pdf->PageNo().' of {nb}',0,0,'R');		
			$pdf->SetFont('Arial','B',11);
			
			$widtex = 50;
			$pdf->SetXY(6,6);
			$pdf->Cell(23,4,$myplant,1,0,'C');
			$pdf->SetXY(($wid_p/2) - ($widtex/2),6);
			$pdf->Cell($widtex,4,'WMS PICKING RESULT',0,0,'C');
			$pdf->SetFont('Arial','B',9);
			$pdf->SetXY(($wid_p/2) - ($widtex/2),11);
			$pdf->Cell($widtex,4,$pser." ($custDo) ($s_sidoc)",0,0,'C');
			$clebar = $pdf->GetStringWidth($pser)+13;	
			$pdf->Code128(($wid_p/2) - ($clebar/2),15,$pser,$clebar,3);
			$pdf->SetFont('Arial','',9);
			$pdf->SetXY(7,20);
			$pdf->Cell(9,4,'No',1,0,'C');		
			$pdf->Cell(25,4,'Item Code',1,0,'C');
			$pdf->Cell(20,4,'Quantity',1,0,'C');
			$pdf->Cell(17,4,'Remark',1,0,'C');
			$pdf->Cell(20,4,'QR',1,0,'C');
			$pdf->SetXY(100,20);
			$pdf->Cell(9,4,'No',1,0,'C');		
			$pdf->Cell(25,4,'Item Code',1,0,'C');
			$pdf->Cell(20,4,'Quantity',1,0,'C');
			$pdf->Cell(17,4,'Remark',1,0,'C');
			$pdf->Cell(20,4,'QR',1,0,'C');
	
			$no = 1;
			$cury = 24;		
			$cury_r = 24;
			$left_y = 24;	
			$right_y = 24;	
			$h_content = 16;
			$ttlrows_perplant = 0;
			foreach($rspickingres as $r){
				if($a_plant[$n]==trim($r['SI_OTHRMRK'])){
					$ttlrows_perplant++;
				}
			}
			$ASPFLAG = false;
			$KDFLAG = false;
			foreach($rspickingres as $r){
				if($a_plant[$n]==trim($r['SI_OTHRMRK'])){
					if(($left_y>=($hgt_p-$h_content)) && ($right_y >=($hgt_p-$h_content)) ){
						$pdf->AddPage();
						$hgt_p = $pdf->GetPageHeight();
						$wid_p = $pdf->GetPageWidth();
						$pdf->SetAutoPageBreak(true,1);
						$pdf->SetMargins(0,0);
						$pdf->SetFont('Arial','',8);
						$pdf->SetXY(180,6);
						$pdf->Cell(15,4,'Page '.$pdf->PageNo().' of {nb}',0,0,'R');		
						$pdf->SetFont('Arial','B',11);
						$widtex = 50;
						$pdf->SetXY(6,6);
						$pdf->Cell(23,4,$myplant,1,0,'C');
						$pdf->SetXY(($wid_p/2) - ($widtex/2),6);
						$pdf->Cell($widtex,4,'WMS PICKING RESULT',0,0,'C');
						$pdf->SetFont('Arial','B',9);
						$pdf->SetXY(($wid_p/2) - ($widtex/2),11);
						$pdf->Cell($widtex,4,$pser,0,0,'C');
						$pdf->SetFont('Arial','',9);
						$pdf->SetXY(7,20);
						$pdf->Cell(9,4,'No',1,0,'C');		
						$pdf->Cell(25,4,'Item Code',1,0,'C');
						$pdf->Cell(20,4,'Quantity',1,0,'C');
						$pdf->Cell(17,4,'Remark',1,0,'C');
						$pdf->Cell(20,4,'QR',1,0,'C');
						$pdf->SetXY(100,20);
						$pdf->Cell(9,4,'No',1,0,'C');		
						$pdf->Cell(25,4,'Item Code',1,0,'C');
						$pdf->Cell(20,4,'Quantity',1,0,'C');
						$pdf->Cell(17,4,'Remark',1,0,'C');
						$pdf->Cell(20,4,'QR',1,0,'C');
		
						// $no =1;
						$cury =24;
						$cury_r =24;
						$left_y =24;
						$right_y=24;
					}
					if (strpos($r['SI_ITMCD'], 'ASP') !== false) {
						$ASPFLAG = true;						
					} else {
						$ASPFLAG = false;						
					}
					if (strpos($r['SI_ITMCD'], 'KD') !== false) {						
						$KDFLAG = true;
					} else {						
						$KDFLAG = false;
					}
					if($no==$ttlrows_perplant){
						if($ASPFLAG || $KDFLAG){
							if($ASPFLAG){
								$image_name = trim($r['SI_ITMCD'])."\t\t\t\t\t".number_format($r['TTLQTY'],0,"","")."\t".number_format($r['TTLQTY'],0,"","")." BOX ".$cpono;
							} else {
								$image_name = trim($r['SI_ITMCD'])."\t\t\t\t\t".number_format($r['TTLQTY'],0,"","")."\t".number_format($r['REMARK'],0,"","")." BOX ".$cpono;
							}
							$image_name = str_replace("KD", "", $image_name);
							$image_name = str_replace("ASP", "", $image_name);							
						} else {
							$image_name = trim($r['SI_ITMCD'])."\t\t\t\t\t".number_format($r['TTLQTY'],0,"","")."\t".number_format($r['REMARK'],0,"","")." BOX ".$cpono." RFID" ;
						}
						$cmd = escapeshellcmd("Python d:\Apache24\htdocs\wms\smt.py \"$image_name\" 2 ");						
					} else {
						if($ASPFLAG || $KDFLAG){
							if($ASPFLAG){
								$image_name = trim($r['SI_ITMCD'])."\t\t\t\t\t".number_format($r['TTLQTY'],0,"","")."\t".number_format($r['TTLQTY'],0,"","")." BOX";								
							} else {								
								$image_name = trim($r['SI_ITMCD'])."\t\t\t\t\t".number_format($r['TTLQTY'],0,"","")."\t".number_format($r['REMARK'],0,"","")." BOX";
							}
							$image_name = str_replace("KD", "", $image_name);
							$image_name = str_replace("ASP", "", $image_name);
						} else {
							$image_name = trim($r['SI_ITMCD'])."\t\t\t\t\t".number_format($r['TTLQTY'],0,"","")."\t".number_format($r['REMARK'],0,"","")." BOX";
						}
						$cmd = escapeshellcmd("Python d:\Apache24\htdocs\wms\smt.py \"$image_name\" 2 ");				
					}
					$op = shell_exec($cmd);					
					$image_name = str_replace("/","xxx", $image_name);
					$image_name = str_replace(" ","___", $image_name);
					$image_name = str_replace("|","lll", $image_name);
					$image_name = str_replace("\t","ttt", $image_name);
															
					if( $left_y>=($hgt_p-$h_content) ){
						$pdf->SetXY(100,$cury_r);
						$pdf->Cell(9,$h_content,$no,1,0,'C');
						$pdf->Cell(25,$h_content,trim($r['SI_ITMCD']),1,0,'C');
						$pdf->Cell(20,$h_content,number_format($r['TTLQTY']),1,0,'C');
						// $pdf->Cell(17,$h_content,number_format($r['REMARK'])." BOX",1,0,'C');
						$pdf->Cell(17,$h_content,number_format($ASPFLAG ? $r['TTLQTY'] : $r['REMARK'])." BOX",1,0,'C');
						$pdf->Cell(20,$h_content,'',1,0,'C');
						$pdf->Image(base_url("assets/imgs/".$image_name.".png"), 174, $cury_r+1);
						$right_y+=$h_content;
						$cury_r+=$h_content;
					} else {
						$pdf->SetXY(7,$cury);
						$pdf->Cell(9,$h_content,$no,1,0,'C');
						$pdf->Cell(25,$h_content,trim($r['SI_ITMCD']),1,0,'C');
						$pdf->Cell(20,$h_content,number_format($r['TTLQTY']),1,0,'C');
						$pdf->Cell(17,$h_content,number_format($ASPFLAG ? $r['TTLQTY'] : $r['REMARK'])." BOX",1,0,'C');
						$pdf->Image(base_url("assets/imgs/".$image_name.".png"), 81, $cury+1);
						$pdf->Cell(20,$h_content,'',1,0,'C');
						$left_y+=$h_content;
						$cury+=$h_content;
					}
					$no++;					
				}
			}
		}
		
		$pdf->Output('I',' FG Picking Result Doc  '.date("d-M-Y").'.pdf');
	}

	public function create_confirmation(){
		$rs = $this->DELV_mod->select_unconfirmed();
		$strtemp = '';
		foreach($rs as $r){
			$strtemp .= "<tr>".
			"<td>$r->DLV_ID</td>".
			"<td>$r->DLV_DATE</td>".
			"<td>$r->MSTEMP_FNM</td>".
			"<td>$r->DLV_POSTTM</td>".
			"<td class='text-center'></td>".
			"<td class='text-center'><button class='btn btn-sm btn-primary'>Confirm</button></td>".
			"</tr>";
		}
		$data['ldata'] = $strtemp;
		$this->load->view('wms/vshipping_confirmation', $data);
	}

	public function removeun(){
		$cser = $this->input->post('inser');
		#validate Already Posted to TPB
		$ttlPostedRows = $this->DELV_mod->check_Primary(['DLV_SER' => $cser, 'DLV_POSTTM IS NOT NULL' => null]);
		if($ttlPostedRows > 0) {
			$myar[] = ["cd" => '0', "msg" => "Could not delete because the data is already posted"];
			die(json_encode(['status' => $myar]));
		}
		#end
		$resdelv = $this->DELV_mod->deleteby_filter(["DLV_SER" => $cser]);
		$myar[] = $resdelv>0 ? ["cd" => strval($resdelv), "msg" => "Deleted"] : 
			["cd" => '0', "msg" => "Data not found, or may be it has been deleted"];				
		die('{"status":'.json_encode($myar).'}');
	}	

	public function removeun_by_txid_item(){
		$txid = $this->input->post('txid');
		$itemid = $this->input->post('itemid');
		#validate Already Posted to TPB
		$ttlPostedRows = $this->DELV_mod->check_Primary(['DLV_ID' => $txid, 'DLV_POSTTM IS NOT NULL' => null]);
		if($ttlPostedRows > 0) {
			$myar[] = ["cd" => '0', "msg" => "Could not delete because the data is already posted"];
			die(json_encode(['status' => $myar]));
		}
		#end
		$rsser = $this->DELV_mod->select_for_delete(['DLV_ID' => $txid, 'SER_ITMID' => $itemid]);
		$ttlrows = count($rsser);
		$ttldeleted = 0;
		foreach($rsser as $r){
			$ttldeleted += $this->DELV_mod->deleteby_filter(["DLV_SER" => $r['DLV_SER'] ]);
		}
		if($ttldeleted>0){
			$res = ["cd" => 1, "msg" => "$ttldeleted Deleted"];
		} else {
			if($ttlrows>0){
				$res = ["cd" => '0', "msg" => "Data not found, or may be it has been deleted"];
			} else {
				$res = ["cd" => '0', "msg" => "Data not found, or it is already posted"];
			}
		}
		$myar[] = $res;
		die('{"status":'.json_encode($myar).'}');
	}

	public function remove_rm(){
		header('Content-Type: application/json');
		$docNum = $this->input->post('docNum');
		$lineId = $this->input->post('lineId');
		$result = $this->DELV_mod->deleteby_filter(['DLV_ID' => $docNum, 'DLV_LINE' => $lineId]);
		$myar[] = $result ? ['cd' => '1', 'msg' => 'Deleted'] : ['cd' => '0' , 'msg' => 'could not be deleted'];
		die('{"status":'.json_encode($myar).'}');
	}

	public function remove_pkg(){
		header('Content-Type: application/json');
		$docNum = $this->input->post('docNum');
		$lineId = $this->input->post('lineId');
		$result = $this->DELV_mod->deleteby_filter_pkg(['DLV_PKG_DOC' => $docNum, 'DLV_PKG_LINE' => $lineId]);
		$myar[] = $result ? ['cd' => '1', 'msg' => 'Deleted'] : ['cd' => '0' , 'msg' => 'could not be deleted'];
		die('{"status":'.json_encode($myar).'}');
	}
	
	public function edit(){
		if ($this->session->userdata('status') != "login")
        {
			$myar [] = ["cd" => "00", "msg" => "Session is expired please reload page"];			
			exit(json_encode($myar));
        }
		date_default_timezone_set('Asia/Jakarta');		
		$crnt_dt = date('Y-m-d H:i:s');
        $ctxid = $this->input->post('intxid');
        $ctxcustDO = $this->input->post('incustdo');
        $ctxdt = $this->input->post('intxdt');
        $ctxinv = $this->input->post('ininv');
        $ctxdodt = $this->input->post('indodt');
        $ctxinvsmt = $this->input->post('ininvsmt');
        $ctxconsig = $this->input->post('inconsig');
        $ctxcus = $this->input->post('incus');        
        $ctxtrans = $this->input->post('intrans');
        $ctxdescr = $this->input->post('indescr');
        $ctxremark = $this->input->post('inremark');        
        $ctxcustomsdoc = $this->input->post('incustoms_doc');        
        $ctxa_ser = $this->input->post('ina_ser');        
        $ctxa_qty = $this->input->post('ina_qty');
        
        $ctxa_so = $this->input->post('ina_so');	
        $cbg = $this->input->post('inbg');	

		$ctxa_sono = $this->input->post('ina_sono');
        $ctxa_soitem = $this->input->post('ina_soitem');
        $ctxa_soqty = $this->input->post('ina_soqty');
		$soCount = 0;
		if(isset($ctxa_sono)) {
			if(is_array($ctxa_sono)) {
				$soCount = count($ctxa_sono);
			}
		}

		$xa_qty = $this->input->post('xa_qty');
        $xa_so = $this->input->post('xa_so');
        $xa_soline = $this->input->post('xa_soline');
        $xa_siline = $this->input->post('xa_siline');
		$xsoCount = 0;
		if(isset($xa_so)) {
			if(is_array($xa_so)) {
				$xsoCount = count($xa_so);
			}
		}

		$myar = [];
		$ttlrow = count($ctxa_ser);
		$datas = [];
		for($i=0;$i<$ttlrow;$i++){
			$ttlrows = $this->DELV_mod->check_Primary(["DLV_SER" => $ctxa_ser[$i]]);
			if($ttlrows==0){
				$qty = $ctxa_qty[$i];
				$qty = str_replace(',','',$qty);
				$datat = [
					'DLV_ID' => $ctxid,
					'DLV_CUSTDO' => $ctxcustDO,
					'DLV_BCDATE' => $ctxdt,
					'DLV_CUSTCD' => $ctxcus,
					'DLV_BSGRP' => $cbg,
					'DLV_CONSIGN' => $ctxconsig,
					'DLV_INVNO' => $ctxinv,
					'DLV_INVDT' => $ctxdt,
					'DLV_BCTYPE' => $ctxcustomsdoc,
					'DLV_SMTINVNO' => $ctxinvsmt, 
					'DLV_TRANS' => $ctxtrans,
					'DLV_DOCREFF' => $ctxa_so[$i],
					'DLV_SER' => $ctxa_ser[$i],
					'DLV_ISPTTRND' => '1',
					'DLV_QTY' => $qty,
					'DLV_RMRK' => $ctxremark,
					'DLV_DSCRPTN' => $ctxdescr,									
					'DLV_CRTD' => $this->session->userdata('nama'),
					'DLV_CRTDTM' => $crnt_dt,
					'DLV_DATE' => $ctxdodt,	
					'DLV_ITMCD' => ''
				];
				$datas[]= $datat;
			}			
		}

		$ttlPostedRows = $this->DELV_mod->check_Primary(['DLV_ID' => $ctxid, 'DLV_POSTTM IS NOT NULL' => null]);
		$dlvUpdated = 0;
		$extraMessage = '';
		if($ttlPostedRows > 0) {
			$extraMessage= " (the data is already posted)";
		} else {
			#update docs properties
			$dlvUpdated = $this->DELV_mod->updatebyVAR(
				['DLV_INVNO' => $ctxinv , 
				'DLV_SMTINVNO' => $ctxinvsmt,
				'DLV_BCTYPE' => $ctxcustomsdoc,
				'DLV_CUSTDO' => $ctxcustDO,
				'DLV_BCDATE' => $ctxdt,
				'DLV_INVDT' => $ctxdt,
				'DLV_DATE' => $ctxdodt,
				'DLV_CONSIGN' => $ctxconsig,
				'DLV_ISPTTRND' => '1'
			], 
			['DLV_ID' => $ctxid]);
		}					
		if(count($datas)>0){			
			if($ttlPostedRows > 0) {				
				$myar[] = ["cd" => '11', "msg" => "Could not add new Data".$extraMessage ];
			} else {
				$cret = $this->DELV_mod->insertb($datas);
				$myar[] = $cret>0 ? ["cd" => '11', "msg" => "Saved successfully" ] : ["cd" => '11', "msg" => "Could not add new Data" ];
			}			
		} else{
			$myar[] = $dlvUpdated > 0 ? ["cd" => '11', "msg" => "Updated" ] : ["cd" => '11', "msg" => "nothing updated"];
		}

		if($this->DLVSO_mod->check_Primary(['DLVSO_DLVID' => $ctxid])) {
			$this->DLVSO_mod->deletebyID(['DLVSO_DLVID' => $ctxid]);
			$setSODLV = [];
			for ($i=0; $i<$soCount; $i++) {
				$setSODLV[] = [
					'DLVSO_DLVID' => $ctxid
					,'DLVSO_ITMCD' => $ctxa_soitem[$i]
					,'DLVSO_CPONO' => $ctxa_sono[$i]
					,'DLVSO_QTY' => $ctxa_soqty[$i]
				];
			}
			if(count($setSODLV)) {
				$this->DLVSO_mod->insertb($setSODLV);
			}
		} else {
			$setSODLV = [];
			for ($i=0; $i<$soCount; $i++) {
				$setSODLV[] = [
					'DLVSO_DLVID' => $ctxid
					,'DLVSO_ITMCD' => $ctxa_soitem[$i]
					,'DLVSO_CPONO' => $ctxa_sono[$i]
					,'DLVSO_QTY' => $ctxa_soqty[$i]
				];
			}
			if(count($setSODLV)) {
				$this->DLVSO_mod->insertb($setSODLV);
			}
		}

		#SO MEGA
		if($xsoCount) {
			#DISTINCT SILINENO
			# fa = Filtered Array
			$fa_siline = [];
			foreach($xa_siline as $i){
				if(!in_array($i, $fa_siline)) {
					$fa_siline[] = $i;
				}
			}
			if(count($fa_siline)) {
				$this->SISO_mod->delete_H_in($fa_siline);
			}
			$datap = [];
			for($i=0;$i<$xsoCount; $i++) {
				$datap[] = [
					'SISO_HLINE' => $xa_siline[$i],
					'SISO_CPONO' => $xa_so[$i],
					'SISO_SOLINE' => $xa_soline[$i],
					'SISO_FLINE' => $xa_siline[$i]."-".$i,
					'SISO_LINE' => $i,
					'SISO_QTY' => $xa_qty[$i]
				];
			}
			$this->SISO_mod->insertb($datap);
		}
		#END

		echo json_encode($myar);

		#SAVE UNSAVED SI
		$cwh_inc = '';
		$cwh_out = '';
		$cfm_inc = '';
		$cfm_out = '';

		$isFreshFG = $this->ITH_mod->check_Primary(['ITH_SER' => $ctxa_ser[0]]);
		if($isFreshFG) {
			$cwh_out = 'AFWH3';
		} else {
			$cwh_out = 'NFWH4RT';
		}

		$dataf_txroute = ['TXROUTE_ID' => 'RECEIVING-FG-SHP', 'TXROUTE_WH' => $cwh_out];
		$rs_txroute = $this->TXROUTE_mod->selectbyVAR($dataf_txroute);
		foreach($rs_txroute as $r){
			$cwh_inc = $r->TXROUTE_WHINC;			
			$cfm_inc = $r->TXROUTE_FORM_INC;
			$cfm_out = $r->TXROUTE_FORM_OUT;
		}		
		//==END			
		$dataw = ['SISCN_PLLT IS NULL' => NULL];
		$rsunsaved = $this->SISCN_mod->selectAll_byserin($dataw,$ctxa_ser);
		$ttlrows = count($rsunsaved);
		if($ttlrows>0){
			//to ith
			$strser = '';
			foreach($rsunsaved as $r){
				$strser .= "'$r[SISCN_SER]',";				
			}
			$strser = substr($strser,0,strlen($strser)-1);
			$rsloc = $this->ITH_mod->selectincfgloc($strser);
			foreach($rsloc as $r){
				foreach($rsunsaved as &$k){
					if($r['ITH_SER']==$k['SISCN_SER']){
						$k['SER_LASTLOC']=$r['ITH_LOC'];break;
					}
				}
				unset($k);
			}
			$tr = 0; //catch retured value when saving process			
			$currrtime = date('Y-m-d H:i:s');
			foreach($rsunsaved as $r){				
				$rsstock = $this->ITH_mod->select_ser_stock($r['SISCN_SER'], $cwh_out);				
				if(count($rsstock)>0){
					$currrtime = date('Y-m-d H:i:s');
					
					$tr += $this->ITH_mod->insert_si_scan(
						trim($r['SER_ITMID']), 
						$cfm_out, $r['SISCN_DOC'], 
						-$r['SISCN_SERQTY'], 
						$cwh_out, 
						$r['SER_LASTLOC'], 
						$r['SISCN_SER'], 
						$this->session->userdata('nama') );
					
					$tr += $this->ITH_mod->insert_si_scan(
						trim($r['SER_ITMID']), 
						$cfm_inc, $r['SISCN_DOC'], 
						$r['SISCN_SERQTY'], 
						$cwh_inc, 
						$r['SER_LASTLOC'], 
						$r['SISCN_SER'], 
						$this->session->userdata('nama') );
				}
			}
			$mlastid = $this->SISCN_mod->lastserialid();
			$mlastid++;
			$newid = $mlastid;
			$datau = ['SISCN_PLLT' => $newid, 'SISCN_LUPDT' => $currrtime];
			$this->SISCN_mod->updatebyId_serin($datau, $dataw, $ctxa_ser);
		}
		#N SAVE UNSAVED SI
	}

    public function set(){
		if ($this->session->userdata('status') != "login")
        {
			$myar [] = ["cd" => "00", "msg" => "Session is expired please reload page"];			
			exit(json_encode($myar));
        }
		date_default_timezone_set('Asia/Jakarta');
		$crnt_dt = date('Y-m-d H:i:s');
        $ctxid = $this->input->post('intxid');
        $ctxcustDO = $this->input->post('incustdo');
        $ctxbctype = $this->input->post('incustoms_doc');
        $ctxdt = $this->input->post('intxdt');
        $ctxinv = $this->input->post('ininv');
        $ctxdodt = $this->input->post('indodt');
        $ctxinvsmt = $this->input->post('ininvsmt');
        $ctxconsig = $this->input->post('inconsig');
        $ctxcus = $this->input->post('incus');
        $ctxbg = $this->input->post('inbg');
        $ctxtrans = $this->input->post('intrans');
        $ctxdescr = $this->input->post('indescr');
        $ctxremark = $this->input->post('inremark');
        $ctxa_ser = $this->input->post('ina_ser');        
        $ctxa_qty = $this->input->post('ina_qty');        
        $ctxa_so = $this->input->post('ina_so');

        $ctxa_sono = $this->input->post('ina_sono');
        $ctxa_soitem = $this->input->post('ina_soitem');
        $ctxa_soqty = $this->input->post('ina_soqty');
		$soCount = 0;
		if(isset($ctxa_sono)) {
			if(is_array($ctxa_sono)) {
				$soCount = count($ctxa_sono);
			}
		}
		$myar = [];		

		$datacheck = ['DLV_ID' => $ctxid];
		if($this->DELV_mod->check_Primary($datacheck)>0){
			$datar = ["cd" => '00', "msg" => "TX ID is already exist" ];
			$myar[] = $datar;
			die(json_encode($myar)) ;			
		} else {
			$adate = explode("-", $ctxdt);
			$mmonth = intval($adate[1]);
			$myear = $adate[0];
			$lastno = $this->DELV_mod->select_lastnodo_patterned($mmonth, $myear)+1;
			$monthroma = '';
			for($i=0;$i<count($this->AROMAWI); $i++){
				if(($i+1) == $mmonth){
					$monthroma = $this->AROMAWI[$i];
					break;
				}
			}
			$ctxid = substr('0000'.$lastno,-4)."/SMT/".$monthroma."/".$myear;
		}

		$ttlrow = count($ctxa_ser);
		$datas = [];
		$serid_sample = '';
		for($i=0;$i<$ttlrow;$i++){
			if($this->DELV_mod->check_Primary(['DLV_SER' => $ctxa_ser[$i]]) == 0){
				$qty = $ctxa_qty[$i];
				$qty = str_replace(',','',$qty);
				$datas[] = [
					'DLV_ID' => $ctxid,
					'DLV_CUSTDO' => $ctxcustDO,
					'DLV_DATE' => $ctxdodt,
					'DLV_CUSTCD' => $ctxcus,
					'DLV_BSGRP' => $ctxbg,
					'DLV_CONSIGN' => $ctxconsig,
					'DLV_INVNO' => $ctxinv,
					'DLV_INVDT' => $ctxdt,
					'DLV_SMTINVNO' => $ctxinvsmt, 
					'DLV_TRANS' => $ctxtrans,				
					'DLV_DOCREFF' => $ctxa_so[$i],
					'DLV_SER' => $ctxa_ser[$i],
					'DLV_ISPTTRND' => '1',
					'DLV_QTY' => $qty,
					'DLV_RMRK' => $ctxremark,
					'DLV_DSCRPTN' => $ctxdescr,
					'DLV_BCTYPE' => $ctxbctype,					
					'DLV_BCDATE' => $ctxdt, #AJU
					'DLV_CRTD' => $this->session->userdata('nama'),
					'DLV_CRTDTM' => $crnt_dt,
					'DLV_ITMCD' => ''
				];
				$serid_sample = $ctxa_ser[$i];		
			}
		}
		if(count($datas)>0){
			$cret = $this->DELV_mod->insertb($datas);
			if($cret>0){		
				#SAVE SO OTHER/ NON MEGA
				$setSODLV = [];
				for ($i=0; $i<$soCount; $i++) {
					$setSODLV[] = [
						'DLVSO_DLVID' => $ctxid
						,'DLVSO_ITMCD' => $ctxa_soitem[$i]
						,'DLVSO_CPONO' => $ctxa_sono[$i]
						,'DLVSO_QTY' => $ctxa_soqty[$i]
					];
				}
				if(count($setSODLV)) {
					$this->DLVSO_mod->insertb($setSODLV);
				}
				#END
				#SAVE UNSAVED SI
				//==START DEFINE WAREHOUSE
				$cwh_inc = '';
				$cwh_out = '';
				$cfm_inc = '';
				$cfm_out = '';
				$rssiwh = $this->SI_mod->select_wh($serid_sample);
				$siwh = '';
				foreach($rssiwh as $r){
					$siwh = $r['SI_WH'];
				}
				$dataf_txroute = ['TXROUTE_ID' => 'RECEIVING-FG-SHP', 'TXROUTE_WH' => $siwh];
				$rs_txroute = $this->TXROUTE_mod->selectbyVAR($dataf_txroute);
				foreach($rs_txroute as $r){
					$cwh_inc = $r->TXROUTE_WHINC;
					$cwh_out = $r->TXROUTE_WHOUT;
					$cfm_inc = $r->TXROUTE_FORM_INC;
					$cfm_out = $r->TXROUTE_FORM_OUT;
				}		
				//==END			
				$dataw = ['SISCN_PLLT IS NULL' => NULL];
				$rsunsaved = $this->SISCN_mod->selectAll_byserin($dataw,$ctxa_ser);
				$ttlrows = count($rsunsaved);
				if($ttlrows>0){
					//to ith
					$strser = '';
					$serin = [];
					foreach($rsunsaved as $r){
						$strser .= "'$r[SISCN_SER]',";
						$serin[] = $r['SISCN_SER'];
					}
					$strser = substr($strser,0,strlen($strser)-1);
					$rsloc = $this->ITH_mod->selectincfgloc($strser);
					foreach($rsloc as $r){
						foreach($rsunsaved as &$k){
							if($r['ITH_SER']==$k['SISCN_SER']){
								$k['SER_LASTLOC']=$r['ITH_LOC'];break;
							}
						}
						unset($k);
					}
					$tr = 0; //catch retured value when saving process
					$trsaved = 0; //catch saved tx
					foreach($rsunsaved as $r){
						$rsstock = $this->ITH_mod->select_ser_stock($r['SISCN_SER'], $cwh_out);
						if(count($rsstock)>0){
							$currrtime = date('Y-m-d H:i:s');							
							$tr += $this->ITH_mod->insert_si_scan(
								trim($r['SER_ITMID']), 
								$cfm_out, $r['SISCN_DOC'], 
								-$r['SISCN_SERQTY'], 
								$cwh_out, 
								$r['SER_LASTLOC'], 
								$r['SISCN_SER'], 
								$this->session->userdata('nama') );
							
							$tr += $this->ITH_mod->insert_si_scan(
								trim($r['SER_ITMID']), 
								$cfm_inc, $r['SISCN_DOC'], 
								$r['SISCN_SERQTY'], 
								$cwh_inc, 
								$r['SER_LASTLOC'], 
								$r['SISCN_SER'], 
								$this->session->userdata('nama') );
						}
					}
					$mlastid = $this->SISCN_mod->lastserialid();
					$mlastid++;
					$newid = $mlastid;
					$currrtime = date('Y-m-d H:i:s');
					$datau = ['SISCN_PLLT' => $newid, 'SISCN_LUPDT' => $currrtime];
					$toret = $this->SISCN_mod->updatebyId_serin($datau, $dataw,$serin);
				}
				#N SAVE UNSAVED SI
				$myar[] = ["cd" => '11', "msg" => "Saved successfully", "dono" => $ctxid ];				 
				die(json_encode($myar));
			} else {
				$myar[] = ["cd" => '00', "msg" => "Could not save.." ];				 
				die(json_encode($myar));
			}
		} else {
			$myar[] = ["cd" => '00', "msg" => "Could not save" ];			
			die(json_encode($myar));
		}
	}	

	public function set_rm(){
		header('Content-Type: application/json');
		date_default_timezone_set('Asia/Jakarta');
		$currentDate = date('Y-m-d H:i:s');
		$doNum = $this->input->post('indoc');
		$doDate = $this->input->post('indocdate');
		$customsDate = $this->input->post('incustomsdate');
		$bisgrup = $this->input->post('inbisgrup');
		$consignee = $this->input->post('inconsign');
		$transportNum = $this->input->post('intransportNum');
		$customerCode = $this->input->post('incuscd');
		$dokumenPabean = $this->input->post('indokumenpab');		
		$invNum = $this->input->post('ininvno');
		$invNumSMT = $this->input->post('ininvnosmt');
		$aItemNum = $this->input->post('initem');
		$aItemQty = $this->input->post('inqty');
		$aItemRemark = $this->input->post('inremark');
		$aItemRowID = $this->input->post('inrowid');		
		$itemCount = count($aItemNum);
		$aPKG_Line = $this->input->post('inpkg_line');
		$aPKG_P = $this->input->post('inpkg_p');
		$aPKG_L = $this->input->post('inpkg_l');
		$aPKG_T = $this->input->post('inpkg_t');
		$PKGCount = count($aPKG_Line);

		#validate input item
		for ($i=0; $i<$itemCount; $i++) {
			if($this->MSTITM_mod->check_Primary(['MITM_ITMCD' => $aItemNum[$i]])==0){
				$myar[] = ['cd' => '0', 'msg' => 'Item '.$aItemNum[$i].' is not registered'];
				die('{"status":'.json_encode($myar).'}');
			}
		}
		if($this->DELV_mod->check_Primary(['DLV_ID' => $doNum])){
			$ttlUpdated = 0;
			$ttlSaved = 0;
			$newLine = $this->DELV_mod->select_lastline($doNum)+1;
			$newLine_pkg = $this->DELV_mod->select_lastline_pkg($doNum)+1;

			#insert & update delivery
			$saveRows = [];
			for ($i=0; $i<$itemCount; $i++) {
				$qty = $aItemQty[$i];
				$qty = str_replace(',','',$qty);
				$where = ['DLV_ID' => $doNum, 'DLV_LINE' => $aItemRowID[$i]];
				if( is_numeric($aItemRowID[$i]) ) {
					$ttlUpdated += $this->DELV_mod->updatebyVAR(
						[						
						'DLV_CUSTCD' => $customerCode,
						'DLV_BSGRP' => $bisgrup,																		 
						'DLV_TRANS' => $transportNum,
						'DLV_QTY' => $qty,
						'DLV_RMRK' => $aItemRemark[$i],						
						'DLV_ITMCD' => $aItemNum[$i],
						'DLV_USRID' => $this->session->userdata('nama'),
						'DLV_LUPDT' => $currentDate
						], $where);
				} else {
					$saveRows[] = [
						'DLV_ID' => $doNum,
						'DLV_DATE' => $doDate,
						'DLV_CUSTCD' => $customerCode,
						'DLV_BSGRP' => $bisgrup,
						'DLV_CONSIGN' => $consignee,
						'DLV_INVNO' => $invNum,
						'DLV_INVDT' => $doDate,
						'DLV_SMTINVNO' => $invNumSMT, 
						'DLV_TRANS' => $transportNum,
						'DLV_DOCREFF' => '',
						'DLV_ISPTTRND' => '1',
						'DLV_QTY' => $qty,
						'DLV_RMRK' => $aItemRemark[$i],
						'DLV_BCTYPE' => $dokumenPabean,						
						'DLV_BCDATE' => $customsDate,
						'DLV_CRTD' => $this->session->userdata('nama'),
						'DLV_CRTDTM' => $currentDate,
						'DLV_ITMCD' => $aItemNum[$i],
						'DLV_LINE' => $newLine,
						'DLV_SER' => ''
					];
					$newLine++;
				}
			}
			if(count($saveRows)){
				$ttlSaved += $this->DELV_mod->insertb($saveRows);
			}
			#end

			#update header
			$rshead = $this->DELV_mod->select_header_rm($doNum);
			$this->DELV_mod->updatebyVAR([
				'DLV_ZJENIS_TPB_ASAL' => $rshead['DLV_ZJENIS_TPB_ASAL'],
				'DLV_ZJENIS_TPB_TUJUAN' => $rshead['DLV_ZJENIS_TPB_TUJUAN'],
				'DLV_FROMOFFICE' => $rshead['DLV_FROMOFFICE'],
				'DLV_NOAJU' => $rshead['DLV_NOAJU'],
				'DLV_NOPEN' => $rshead['DLV_NOPEN'],
				'DLV_ZKODE_CARA_ANGKUT' => $rshead['DLV_ZKODE_CARA_ANGKUT'],
				'DLV_ZSKB' => $rshead['DLV_ZSKB']
			], ['DLV_ID' => $doNum]);
			#end

			#update docs properties
			$this->DELV_mod->updatebyVAR(
				['DLV_INVNO' => $invNum, 
				'DLV_SMTINVNO' => $invNumSMT,
				'DLV_BCTYPE' => $dokumenPabean,				
				'DLV_BCDATE' => $customsDate,
				'DLV_INVDT' => $doDate,
				'DLV_DATE' => $doDate,
				'DLV_CONSIGN' => $consignee,
				'DLV_ISPTTRND' => '1'
			], 
				['DLV_ID' => $doNum]);
			#end

			#insert & update packaging
			$saveRows = [];
			for ($i=0; $i<$PKGCount; $i++) {
				if( is_numeric($aPKG_Line[$i]) ) {
					$this->DELV_mod->updatebyVAR_pkg(
					['DLV_PKG_P' => $aPKG_P[$i]
					,'DLV_PKG_L' => $aPKG_L[$i]
					,'DLV_PKG_T' => $aPKG_T[$i]
					], 
					['DLV_PKG_DOC' => $doNum
					,'DLV_PKG_LINE' => $aPKG_Line[$i]
					]);
				} else {
					$saveRows[] = [
						'DLV_PKG_DOC' => $doNum
						,'DLV_PKG_LINE' => $newLine_pkg
						,'DLV_PKG_P' => $aPKG_P[$i]
						,'DLV_PKG_L' => $aPKG_L[$i]
						,'DLV_PKG_T' => $aPKG_T[$i]
					];
					$newLine_pkg++;
				}
			}
			if(count($saveRows)){
				$ttlSaved += $this->DELV_mod->insertb_pkg($saveRows);
			}
			#end

			$myar[] = ['cd' => '11', 'msg' => 'Updated'];
			die('{"status":'.json_encode($myar).'}');
		} else {
			$saveRows = [];
			$adate = explode("-", $doDate);
			$mmonth = intval($adate[1]);
			$myear = $adate[0];
			$lastno = $this->DELV_mod->select_lastnodo_patterned($mmonth, $myear)+1;
			$monthroma = '';
			for($i=0;$i<count($this->AROMAWI); $i++){
				if(($i+1) == $mmonth){
					$monthroma = $this->AROMAWI[$i];
					break;
				}
			}
			$ctxid = substr('0000'.$lastno,-4)."/SMT/".$monthroma."/".$myear;
			for ($i=0; $i<$itemCount; $i++) {
				$qty = $aItemQty[$i];
				$qty = str_replace(',','',$qty);
				$saveRows[] = [
					'DLV_ID' => $ctxid,
					'DLV_DATE' => $doDate,
					'DLV_CUSTCD' => $customerCode,
					'DLV_BSGRP' => $bisgrup,
					'DLV_CONSIGN' => $consignee,
					'DLV_INVNO' => $invNum,
					'DLV_INVDT' => $doDate,
					'DLV_SMTINVNO' => $invNumSMT, 
					'DLV_TRANS' => $transportNum,
					'DLV_DOCREFF' => '',
					'DLV_ISPTTRND' => '1',
					'DLV_QTY' => $qty,
					'DLV_RMRK' => $aItemRemark[$i],
					'DLV_BCTYPE' => $dokumenPabean,					
					'DLV_BCDATE' => $customsDate,
					'DLV_CRTD' => $this->session->userdata('nama'),
					'DLV_CRTDTM' => $currentDate,
					'DLV_ITMCD' => $aItemNum[$i],
					'DLV_LINE' => $i,
					'DLV_SER' => ''
				];
			}
			if(count($saveRows)){
				$this->DELV_mod->insertb($saveRows);
			}
			$saveRows = [];
			for ($i=0; $i<$PKGCount; $i++) {
				$saveRows[] = [
					'DLV_PKG_DOC' =>  $ctxid
					,'DLV_PKG_LINE' =>  $aPKG_Line[$i]
					,'DLV_PKG_P' =>  $aPKG_P[$i]
					,'DLV_PKG_L' =>  $aPKG_L[$i]
					,'DLV_PKG_T' =>  $aPKG_T[$i]
				];
			}
			if(count($saveRows)){
				$this->DELV_mod->insertb_pkg($saveRows);
			}
			$myar[] = ['cd' => '1', 'msg' => 'Saved', 'dono' => $ctxid];
			die('{"status":'.json_encode($myar).'}');
		}
	}
	
	public function search(){
		header('Content-Type: application/json');
		$ckeys = $this->input->get('inkey');
		$cs_by = $this->input->get('insearchby');
		$csearchperiod = $this->input->get('insearchperiod');
		$cmonth = $this->input->get('inmonth');
		$cyear = $this->input->get('inyear');
		if($this->input->get('insearchtype')=='1'){
			if($csearchperiod==0){
				switch($cs_by){
					case 'tx':
						$rs = $this->DELV_mod->select_bytx($ckeys);
						break;
					case 'txdate':
						$rs = $this->DELV_mod->select_bydt($ckeys);
						break;
					case 'cusnm':
						$rs = $this->DELV_mod->select_bycustomer($ckeys);
						break;
				}
			} else {
				switch($cs_by){
					case 'tx':
						$rs = $this->DELV_mod->select_bytx_period($ckeys, $cmonth, $cyear);
						break;
					case 'txdate':
						$rs = $this->DELV_mod->select_bydt($ckeys);
						break;
					case 'cusnm':
						$rs = $this->DELV_mod->select_bycustomer($ckeys);
						break;
				}
			}
		} else {
			if($csearchperiod==0){
				switch($cs_by){
					case 'tx':
						$rs = $this->DELV_mod->select_bytx_rm($ckeys);
						break;
					case 'txdate':
						$rs = $this->DELV_mod->select_bydt_rm($ckeys);
						break;
					case 'cusnm':
						$rs = $this->DELV_mod->select_bycustomer_rm($ckeys);
						break;
				}
			} else {
				switch($cs_by){
					case 'tx':
						$rs = $this->DELV_mod->select_bytx_period_rm($ckeys, $cmonth, $cyear);
						break;
					case 'txdate':
						$rs = $this->DELV_mod->select_bydt_rm($ckeys);
						break;
					case 'cusnm':
						$rs = $this->DELV_mod->select_bycustomer_rm($ckeys);
						break;
				}
			}
		}
		die('{"data":'.json_encode($rs).'}');		
	}

	public function getdetails(){
		header('Content-Type: application/json');
		$cid = $this->input->get('intxid');
		$ctype = $this->input->get('intype');
		if ($ctype==='1') {
			$rs = $this->DELV_mod->select_det_byid($cid);			
			$rsrm_checker = $this->SER_mod->select_sususan_bahan_baku_by_txid($cid);
			$rsrm_notOK = [];
			foreach($rsrm_checker as $r){
				if($r['CALPER']==0 || $r['CALPER'] < $r['MYPER']) {
					if($r['FLG']!='flg_ok') {
						if(substr($r['SER_DOC'],2,2)!='-C') {
							$rsrm_notOK[] = $r;
						}
					}
				}
			}			
			die('{"data":'.json_encode($rs)
				.', "datafocus": '.json_encode($rsrm_notOK)
				.'}');
		} else {
			$rs = $this->DELV_mod->select_det_byid_rm($cid);
			$rspkg = $this->DELV_mod->select_pkg($cid);
			die('{"data":'.json_encode($rs).',"data_pkg":'.json_encode($rspkg).'}');
		}
	}

	public function printdocs(){
		$pid = ''; $pforms = '';		
        if(isset($_COOKIE["CKPDLV_NO"])){
            $pid = $_COOKIE["CKPDLV_NO"];
		} else {
			exit('nothing to be printed');
		}
		if(isset($_COOKIE["CKPDLV_FORMS"])){
            $pforms = $_COOKIE["CKPDLV_FORMS"];
		} else {
			exit('Please select at least one document type');
		}	
		$rs = $this->DELV_mod->select_det_byid_p($pid);
		$pdf = new PDF_Code39e128('P','mm','A4');
		$pdf->SetFont('Arial','',8);
		//start of data base
		$hinv_date ='';
		$hdlv_date ='';
		$hinv_inv ='';
		$hinv_smtinv ='';
		$hinv_nopen ='';
		$hinv_customer ='';
		$hinv_address ='';
		
		$hinv_currency ='';
		$hinv_description ='';
		$hinv_bctype ='';
		$hinv_noaju ='';
		$ar_item = [];
		$ar_itemdesc = [];
		$ar_itemUM = [];
		$ar_qty = [];
		$ar_price = [];
		foreach($rs as $r){
			$hinv_date = $r['DLV_INVDT'];
			$hinv_date = date_create($hinv_date);
			$hinv_date = date_format($hinv_date, "d/m/Y");
			$hdlv_date = $r['DLV_DATE'];
			$hdlv_date = date_create($hdlv_date);
			$hdlv_date = date_format($hdlv_date, "d/m/Y");
			$hinv_inv = $r['DLV_INVNO'];
			$hinv_smtinv = $r['DLV_SMTINVNO'];
			$hinv_nopen = $r['DLV_NOPEN'];
			$hinv_address = $r['MDEL_ADDRCUSTOMS'];
			$hinv_currency = $r['MCUS_CURCD'];
			$hinv_description = $r['DLV_DSCRPTN'];
			$hinv_customer = $r['MDEL_DELNM'];
			$hinv_bctype = $r['DLV_BCTYPE'];
			$hinv_noaju = $r['DLV_NOAJU'];
			break;
		}
		//end of data base
		if(substr($pforms,1,1)=='1'){
			//START INVOICE
			$pdf->AddPage();
			$hgt_p = $pdf->GetPageHeight();
			$wid_p = $pdf->GetPageWidth();
			$pdf->SetAutoPageBreak(true,1);
			$pdf->SetMargins(0,0);
			$rsinv = $this->DELV_mod->select_invoice_bydono($pid);
			$pdf->SetFont('Arial','',8);
			$pdf->Text(144,67,'Nopen : '.$hinv_nopen);
			$pdf->Text(161,78,$hinv_inv);
			$pdf->Text(30,78,$hinv_date);
			$pdf->SetXY(13,80.9);
			$pdf->MultiCell(85.67,4,trim($hinv_customer),0);
			$pdf->SetXY(13,80.9+4);
			$pdf->MultiCell(85.67,4,trim($hinv_address),0);
			$pdf->Text(110,133,$hinv_currency);
			$pdf->SetLineWidth(0.4);
			$curY = 143;
			$pdf->SetXY(14,$curY);
			$pdf->Cell(27,4,'No',1,0,'L');
			$pdf->Cell(55,4,'Description',1,0,'L');
			$pdf->Cell(20,4,'Unit',1,0,'C');
			$pdf->Cell(20,4,'QTY',1,0,'R');
			$pdf->Cell(20,4,'Price',1,0,'R');
			$pdf->Cell(45,4,'Total Amount',1,0,'R');
			$curY = 152;
			$no =1;
			$ttlbrs = 1;
			$gtotalamount = 0;
			$curLine = 147;
			$MAX_INVD_PERPAGE = 8;
			$MAX_INVL_PERPAGE = $curLine + (10*$MAX_INVD_PERPAGE);
			
			foreach($rsinv as $r){
				if($ttlbrs>$MAX_INVD_PERPAGE){
					$ttlbrs=1;
					$pdf->Line(14, $MAX_INVL_PERPAGE, 201, $MAX_INVL_PERPAGE);
					$pdf->AddPage();
					$pdf->SetFont('Arial','',8);
					$pdf->Text(144,67,'Nopen : '.$hinv_nopen);
					$pdf->Text(161,78,$hinv_inv);
					$pdf->Text(30,78,$hinv_date);
					$pdf->SetXY(13,80.9);
					$pdf->MultiCell(85.67,4,trim($hinv_customer),0);
					$pdf->SetXY(13,80.9+4);
					$pdf->MultiCell(85.67,4,trim($hinv_address),0);
					$pdf->Text(110,133,$hinv_currency);
					$curY = 143;
					$pdf->SetXY(14,$curY);
					$pdf->Cell(27,4,'No',1,0,'L');
					$pdf->Cell(55,4,'Description',1,0,'L');
					$pdf->Cell(20,4,'Unit',1,0,'C');
					$pdf->Cell(20,4,'QTY',1,0,'R');
					$pdf->Cell(20,4,'Price',1,0,'R');
					$pdf->Cell(45,4,'Total Amount',1,0,'R');
					$curY = 152;
					$curLine = 147;

				}
				$pdf->SetXY(14,$curY-3);
				$pdf->Cell(27,4,$no,0,0,'L');
				// $pdf->Text(15,$curY,$no);
				// $pdf->Text(45,$curY,$r['MITM_ITMD1']);
				$pdf->SetXY(43,$curY-3);	
				$ttlwidth = $pdf->GetStringWidth(trim($r['MITM_ITMD1']));
				if($ttlwidth > 51){	
					$ukuranfont = 7.5;
					while($ttlwidth>50){
						$pdf->SetFont('Arial','',$ukuranfont);
						$ttlwidth=$pdf->GetStringWidth(trim($r['MITM_ITMD1']));
						$ukuranfont = $ukuranfont - 0.5;
					}
				}
				$pdf->Cell(51,4,$r['MITM_ITMD1'],0,0,'L');	
				$pdf->SetFont('Arial','',8);	
				$pdf->Text(45,$curY+4,"(".trim($r['SSO2_MDLCD']).")");
				$pdf->Text(100,$curY,$r['MITM_STKUOM']);
				$pdf->SetXY(115,$curY-3);
				$pdf->Cell(20.55,4,number_format($r['QTY']),0,0,'R');
				$pdf->SetXY(137,$curY-3);
				$pdf->Cell(17.5,4,substr($r['SSO2_SLPRC'],0,1) =='.' ?'0'.$r['SSO2_SLPRC']:$r['SSO2_SLPRC'] ,0,0,'R');
				$pdf->SetXY(155,$curY-3);
				$pdf->Cell(41.56,4,number_format($r['QTY']*$r['SSO2_SLPRC'],2),0,0,'R');
				$pdf->Line(14, $curLine, 14, $curLine+10);
				$pdf->Line(41, $curLine, 41, $curLine+10);
				$pdf->Line(96, $curLine, 96, $curLine+10);
				$pdf->Line(116, $curLine, 116, $curLine+10);
				$pdf->Line(136, $curLine, 136, $curLine+10);
				$pdf->Line(156, $curLine, 156, $curLine+10);
				$pdf->Line(201, $curLine, 201, $curLine+10);
				// $pdf->Line(41, $curLine, 41, $curLine+10);
				$curY+=10;
				$curLine+=10;
				$no++;
				$ttlbrs++;
				$gtotalamount += ($r['QTY']*$r['SSO2_SLPRC']);
			}
			if($curLine<$MAX_INVL_PERPAGE){#148+(10*8) = max
				$pdf->Line(14, $curLine, 14, $MAX_INVL_PERPAGE);
				$pdf->Line(41, $curLine, 41, $MAX_INVL_PERPAGE);
				$pdf->Line(96, $curLine, 96, $MAX_INVL_PERPAGE);
				$pdf->Line(116, $curLine, 116, $MAX_INVL_PERPAGE);
				$pdf->Line(136, $curLine, 136, $MAX_INVL_PERPAGE);
				$pdf->Line(156, $curLine, 156, $MAX_INVL_PERPAGE);
				$pdf->Line(201, $curLine, 201, $MAX_INVL_PERPAGE);
			}
			$pdf->Line(14, $MAX_INVL_PERPAGE, 201, $MAX_INVL_PERPAGE);
			$pdf->SetXY(155,240);
			$pdf->Cell(41.56,4,number_format($gtotalamount,2),0,0,'R');
			if(trim($hinv_description)!=''){
				$pdf->Text(35,240,$hinv_description. " Purpose Only, On Behalf PT. Sumitronics Indonesia");
			}		
			$pdf->Text(76,246,"Commercial Invoice No are");
			$pdf->Text(76,246+5,"1) From PT. SUMITRONIC INDONESIA To");
			$pdf->Text(76,246+10,trim($hinv_customer) ." ". $hinv_inv);
			$pdf->Text(76,246+15,"2) From PT. SMT INDONESIA To");
			$pdf->Text(76,246+20,"PT. SUMITRONIC INDONESIA ".$hinv_smtinv);
			//END OF INVOICE
		}
		
		if(substr($pforms,-1)=='1'){
			//START PACKING LIST
			$pdf->AddPage();
			$hgt_p = $pdf->GetPageHeight();
			$wid_p = $pdf->GetPageWidth();
			$pdf->SetAutoPageBreak(true,1);
			$pdf->SetMargins(0,0);
			unset($ar_item);
			unset($ar_itemdesc);
			unset($ar_qty);
			unset($ar_itemUM);
			unset($ar_price);
			$rspackinglist = $this->DELV_mod->select_packinglist_bydono($pid);
			$ar_item = [];
			$ar_itemdesc = [];			
			
			

			$ar_grup_item = [];
			$ar_grup_item_ttl = [];
			foreach($rspackinglist as $r){
				if(!in_array($r['SI_ITMCD'], $ar_grup_item)){
					$ar_grup_item[] = $r['SI_ITMCD'];
					$ar_grup_item_ttl[] = 1;					
				} else {
					for($h=0;$h<count($ar_grup_item); $h++){
						if($ar_grup_item[$h]==$r['SI_ITMCD']){
							$ar_grup_item_ttl[$h]++;
							break;
						}
					}
				}
			}
			foreach($rspackinglist as &$r){
				for($h=0;$h<count($ar_grup_item); $h++){
					if($ar_grup_item[$h]==$r['SI_ITMCD']){
						$r['TTLBARIS']=$ar_grup_item_ttl[$h];
						break;
					}
				}
			}
			unset($r);
			
			$pdf->Text(155,59,$hinv_date);
			$pdf->Text(16,70,$hinv_customer);
			$pdf->Text(16,70+4,$hinv_address);
			$pdf->Text(140,91,$hinv_inv);
			$curY = 110;
			$no =0;
		
			$TTLQTY = 0;
			$TTLNET = 0;
			$TTLGRS = 0;
			$TTLBOX = 0;
		
			$par_item = '';
			$dis_item = '';
			$dis_itemnm = '';
			$dis_no = '';			
			$dis_qty = '';
			// $ttlbrs=110;
			
			foreach($rspackinglist as $r) {
				if($curY>200){
					$pdf->AddPage();
					$curY=110;
				}
				if($par_item!=$r['SI_ITMCD'] ){
					$par_item=$r['SI_ITMCD'];
					$dis_item=$r['SI_ITMCD'];
					$dis_itemnm=$r['MITM_ITMD1'];
					$dis_qty=number_format($r['TTLDLV'],0) ;
					$no++;
					$dis_no = $no;
				} else {
					$dis_item='';
					$dis_itemnm='';
					$dis_no='';
					$dis_qty='';
				}							
				$pdf->SetXY(11,$curY-3);
				$pdf->Cell(21,4,$dis_no,0,0,'C');
				$pdf->SetXY(32,$curY-3);
				$ttlwidth = $pdf->GetStringWidth(trim($dis_itemnm));
				if($ttlwidth > 63.73){
					$ukuranfont = 7.5;
					while($ttlwidth>63.73){
						$pdf->SetFont('Arial','',$ukuranfont);
						$ttlwidth=$pdf->GetStringWidth(trim($dis_itemnm));
						$ukuranfont = $ukuranfont - 0.5;
					}
				}
				$pdf->Cell(63.73,4,$dis_itemnm,0,0,'L');	
				$pdf->SetFont('Arial','',8);
				$pdf->SetXY(32,$curY+2);
				$pdf->Cell(63.73,4,$dis_item,0,0,'L');
				$pdf->SetXY(95.73,$curY-3);
				$pdf->Cell(16.63,4, $dis_qty,0,0,'R'); #number_format($r['SISCN_SERQTY'] * $r['TTLBOX'])
				$pdf->SetXY(112.36,$curY-3);
				$pdf->Cell(24.71,4, number_format($r['MITM_NWG'],2),0,0,'R');
				$pdf->SetXY(137.07,$curY-3);
				$pdf->Cell(29.33,4, number_format($r['MITM_GWG'],2),0,0,'R');
				$pdf->SetXY(166.4,$curY-3);
				$pdf->Cell(31.17,4, number_format($r['TTLBOX'])." BOX (X) ".number_format($r['SISCN_SERQTY']),0,0,'R');

				$TTLQTY += 	($r['SISCN_SERQTY']*$r['TTLBOX']);	
				$TTLNET+=$r['MITM_NWG'];
				$TTLGRS+=$r['MITM_GWG'];
				$TTLBOX+=$r['TTLBOX'];						
				$curY+= $r['TTLBARIS']==1 ? 10: 5;
			}
			
			$pdf->SetXY(32,235);
			$pdf->Cell(63.73,4, "Total",0,0,'L');
			$pdf->SetXY(95.73,235);
			$pdf->Cell(16.63,4, number_format($TTLQTY),0,0,'R');
			$pdf->SetXY(112.36,235);
			$pdf->Cell(24.71,4, number_format($TTLNET,2),0,0,'R');
			$pdf->SetXY(137.07,235);
			$pdf->Cell(29.33,4, number_format($TTLGRS,2),0,0,'R');
			$pdf->SetXY(166.4,235);
			$pdf->Cell(31.17,4, number_format($TTLBOX),0,0,'R');
			//END OF PACKING LIST
		}
		
		if(substr($pforms,0,1)=='1'){
			//START DO
			unset($ar_item);
			unset($ar_itemdesc);
			unset($ar_qty);
			unset($ar_itemUM);
			$ar_po = array();
			$ar_item= array();
			$ar_itemdesc = array();
			$ar_itemUM = array();
			$ar_qty= array();
			$rsdo = $this->DLVSO_mod->select_for_DO($pid);
			foreach($rs as $r){
				$isexist =false;
				for($i=0;$i<count($ar_item);$i++){
					if( ($r['SER_ITMID']==$ar_item[$i]) && ($r['SISCN_DOCREFF'] == $ar_po[$i]) ){
						$isexist =true;
						$ar_qty[$i]=$ar_qty[$i]+$r['SISCN_SERQTY'];
						break;
					}
				}
				if(!$isexist){
					$ar_item[]= $r['SER_ITMID'];
					$ar_itemdesc[] = $r['MITM_ITMD1'];
					$ar_itemUM[]=$r['MITM_STKUOM'];
					$ar_qty[] = $r['SISCN_SERQTY'];
					$ar_po[] = $r['SISCN_DOCREFF'];
				}
			}
			$pdf->AddPage();
			$pdf->SetAutoPageBreak(true,1);
			$pdf->SetMargins(0,0);
			$pdf->SetXY(51,60);			
			$pdf->MultiCell(76.04,4,trim($hinv_customer)." \n".$hinv_address,0);
			$pdf->SetXY(155,60);
			$pdf->Cell(31.17,4, $pid,0,0,'L');
			$pdf->SetXY(155,60+5);
			$pdf->Cell(31.17,4, $hdlv_date,0,0,'L');
			$pdf->SetXY(138,70);
			$pdf->Cell(31.17,4, "BC ".$hinv_bctype." : ".trim($hinv_noaju),0,0,'L');
			$pdf->SetXY(138,75);
			$pdf->Cell(31.17,4, "INV NO : ".$hinv_inv,0,0,'L');
			$curY=93;
			$ttlrows = count($ar_item);
			$ttlbaris = 1;
			$tempItem = '';			
			$ItemDis = '';
			$ItemDis2 = '';
			$nourutDO = 0;
			$nourutDODis = 0;
			if($hinv_bctype==='41') {
				$rs41 = $this->DELV_mod->select_DO41($pid);
				foreach($rs41 as $n) {
					if($tempItem!=$n['SER_ITMID']) {
						$nourutDO++;
						$tempItem = $n['SER_ITMID'];
						$ItemDis = $tempItem;
						$ItemDis2 = $n['MITM_ITMD1'];
						$nourutDODis = $nourutDO;
					} else {
						$ItemDis = '';
						$ItemDis2 = '';
						$nourutDODis = '';
					}
					if($ttlbaris>6){
						$ttlbaris=1;
						$curY=93;
						$pdf->AddPage();
					}
					$pdf->SetXY(16,$curY);
					$pdf->Cell(14.75,4, $nourutDODis,0,0,'C');
					$pdf->SetXY(30.75,$curY);
					$pdf->Cell(63.91,4, $ItemDis2,0,0,'L');
					$pdf->SetXY(30.75,$curY+4);
					$pdf->Cell(63.91,4, $ItemDis,0,0,'L');//LINE2
					$pdf->SetXY(94.66,$curY);
					$pdf->Cell(43.43,4, number_format($n['TTLDLV']). " ".$n['MITM_STKUOM'],0,0,'R');
					$pdf->SetXY(138.09,$curY);
					$pdf->Cell(58.34,4, "EX.BC 40 : ". $n['RCV_BCNO'],0,0,'L');
					$pdf->SetXY(138.09,$curY+4);
					$pdf->Cell(58.34,4, "LOT. : ".number_format($n['TTLALLDLV'])." / ".number_format($n['MTTL']),0,0,'L'); //LINE2
					$pdf->SetXY(138.09,$curY+8);
					$pdf->Cell(58.34,4, "Sisa. : ".number_format($n['MTTL']-$n['TTLALLDLV']),0,0,'L'); //LINE
					$pdf->SetXY(138.09,$curY+12);
					$pdf->Cell(58.34,4, "PK. NO : ".$n['DLV_CONA'],0,0,'L'); //LINE
					$curY+=16;
					$ttlbaris++;
				}
			} else {
				if(count($rsdo) > 0) {
					foreach($rsdo as $n) {
						if($tempItem!=$n['DLVSO_ITMCD']) {
							$nourutDO++;
							$tempItem = $n['DLVSO_ITMCD'];
							$ItemDis = $tempItem;
							$ItemDis2 = $n['MITM_ITMD1'];
							$nourutDODis = $nourutDO;
						} else {
							$ItemDis = '';
							$ItemDis2 = '';
							$nourutDODis = '';
						}
						if($ttlbaris>6){
							$ttlbaris=1;
							$curY=93;
							$pdf->AddPage();
						}
						$pdf->SetXY(16,$curY);
						$pdf->Cell(14.75,4, $nourutDODis,0,0,'C');
						$pdf->SetXY(30.75,$curY);
						$pdf->Cell(63.91,4, $ItemDis2,0,0,'L');
						$pdf->SetXY(30.75,$curY+4);
						$pdf->Cell(63.91,4, $ItemDis,0,0,'L');//LINE2
						$pdf->SetXY(94.66,$curY);
						$pdf->Cell(43.43,4, number_format($n['PLOTQTY']). " ".$n['MITM_STKUOM'],0,0,'R');
						$pdf->SetXY(138.09,$curY);
						$pdf->Cell(58.34,4, "PO NO : ". $n['DLVSO_CPONO'],0,0,'L');
						$pdf->SetXY(138.09,$curY+4);
						$pdf->Cell(58.34,4, "OS : ".number_format($n['PLOTQTMAIN'])."/". number_format($n['ORDQT']),0,0,'L'); //LINE2						
						$curY+=8;
						$ttlbaris++;
					}
				} else {
					for($i=0;$i<$ttlrows;$i++){
						if($ttlbaris>6){
							$ttlbaris=1;
							$curY=93;
							$pdf->AddPage();
						}
						$pdf->SetXY(16,$curY);
						$pdf->Cell(14.75,4, ($i+1),0,0,'C');
						$pdf->SetXY(30.75,$curY);
						$pdf->Cell(63.91,4, trim($ar_itemdesc[$i]),0,0,'L');
						$pdf->SetXY(30.75,$curY+4);
						$pdf->Cell(63.91,4, trim($ar_item[$i]),0,0,'L');//LINE2
						$pdf->SetXY(94.66,$curY);
						$pdf->Cell(43.43,4, number_format($ar_qty[$i]). " ".trim($ar_itemUM[$i]),0,0,'R');
						$pdf->SetXY(138.09,$curY);
						$pdf->Cell(58.34,4, "PO NO : ". $ar_po[$i],0,0,'L');
						$pdf->SetXY(138.09,$curY+4);
						$pdf->Cell(58.34,4, "OS. : / ",0,0,'L'); //LINE2
						$curY+=8;
						$ttlbaris++;
					}
				}
			}
			
					
			//END OF DO
		}
		$pdf->Output('I','Delivery Docs '.date("d-M-Y").'.pdf');
	}
	public function change25(){
		date_default_timezone_set('Asia/Jakarta');		
		$crnt_dt = date('Y-m-d H:i:s');		
		$cid = $this->input->get('inid');		
		$cnoaju = $this->input->get('inaju');
		$cnopen = $this->input->get('innopen');
		$ctglpen = $this->input->get('intglpen');
		$cfromoffice = $this->input->get('infromoffice');		
		$ctpb_asal = $this->input->get('injenis_tpb_asal');
		$cskb = $this->input->get('inskb');
		$cskb_tgl = $this->input->get('inskb_tgl');
		$cjenis_sarana_pengangkut = $this->input->get('injenis_sarana');
		$ttlPostedRows = $this->DELV_mod->check_Primary(['DLV_ID' => $cid, 'DLV_POSTTM IS NOT NULL' => null]);
		$ttlPostedAndHasbc = $this->DELV_mod->check_Primary(['DLV_ID' => $cid, "ISNULL(DLV_NOPEN,'')" => '']);
		if($cnopen=='null') {
			$cnopen = '';
		}
		if($ttlPostedRows>0 && $ttlPostedAndHasbc===0) {
			$myar[] = ["cd" => '00', "msg" => "could not update, because the data is already posted"];
			die(json_encode($myar));
		}
		$rsaktivasi = $this->AKTIVASIAPLIKASI_imod->selectAll();
		$rs_head_dlv = $this->DELV_mod->select_header_bydo($cid);
		$czidmodul ='';
		
		foreach($rs_head_dlv as $r){
			$czdocbctype = $r['DLV_BCTYPE'];
			$ccustdate = $r['DLV_BCDATE'];
		}
		foreach($rsaktivasi as $r){
			$czkantorasal = $r['KPPBC'];
			$czidmodul = substr('00000'.$r['ID_MODUL'],-6);			
		}
		if($czidmodul==''){
			$myar[] = ["cd" => '00', "msg" => "Please check Aktivasi CEISA Data" ];
		} else {
			$ocustdate = date_create($ccustdate);
			$cz_ymd = date_format($ocustdate, 'Ymd');
			$znomor_aju = substr($czkantorasal,0,4).$czdocbctype.$czidmodul.$cz_ymd.$cnoaju;
			$ctglpen = strlen(trim($ctglpen))==10 ? $ctglpen : NULL;
			$cskb_tgl = strlen(trim($cskb_tgl))==10 ? $cskb_tgl : NULL;
			$keys = ['DLV_ID' => $cid];
			$vals = [
				'DLV_NOPEN' => $cnopen, 'DLV_NOAJU' => $cnoaju , 'DLV_ZNOMOR_AJU' => $znomor_aju,
				'DLV_ZJENIS_TPB_ASAL' => $ctpb_asal, 'DLV_ZSKB' => $cskb, 'DLV_ZTANGGAL_SKB' => $cskb_tgl,
				'DLV_FROMOFFICE' => $cfromoffice, "DLV_RPDATE" => $ctglpen,
				'DLV_ZKODE_CARA_ANGKUT' => $cjenis_sarana_pengangkut, 
				'DLV_LUPDT' => $crnt_dt, 'DLV_USRID' => $this->session->userdata('nama')
			];
			$ret = $this->DELV_mod->updatebyVAR($vals, $keys);						
			$myar[] = $ret>0 ?  ["cd" => '11', "msg" => "Updated successfully"] : ["cd" => '00', "msg" => "No data to be updated" ];
		}
		die(json_encode($myar));
	}

	public function aa() {
		$cid = $this->input->get('doc');
		$ttlPostedAndHasbc = $this->DELV_mod->check_Primary(['DLV_ID' => $cid, "ISNULL(DLV_NOPEN,'')" => '']);
		echo $ttlPostedAndHasbc;
	}

	public function change27(){
		date_default_timezone_set('Asia/Jakarta');		
		$crnt_dt = date('Y-m-d H:i:s');		
		$cid = $this->input->get('inid');		
		$cnoaju = $this->input->get('inaju');
		$cnopen = $this->input->get('innopen');
		$cfromoffice = $this->input->get('infromoffice');
		$cdestoffice = $this->input->get('indestoffice');
		$cpurpose = $this->input->get('inpurpose');
		$ctpb_asal = $this->input->get('injenis_tpb_asal');
		$ctpb_tujuan = $this->input->get('injenis_tpb_tujuan');
		$ctpb_tgl_daftar = $this->input->get('intgldaftar');
		$cona = $this->input->get('incona');
		$rsaktivasi = $this->AKTIVASIAPLIKASI_imod->selectAll();
		$rs_head_dlv = $this->DELV_mod->select_header_bydo($cid);
		$ttlPostedRows = $this->DELV_mod->check_Primary(['DLV_ID' => $cid, 'DLV_POSTTM IS NOT NULL' => null]);
		$ttlPostedAndHasbc = $this->DELV_mod->check_Primary(['DLV_ID' => $cid, "ISNULL(DLV_NOPEN,'')" => '']);
		if($cnopen=='null') {
			$cnopen = '';
		}
		if($ttlPostedRows>0 && $ttlPostedAndHasbc===0) {
			$myar[] = ["cd" => '00', "msg" => "could not update, because the data is already posted"];
			die(json_encode($myar));
		}
		$czidmodul ='';
		$myar = [];		
		foreach($rs_head_dlv as $r){
			$czdocbctype = $r['DLV_BCTYPE'];
			$ccustdate = $r['DLV_BCDATE'];
		}
		foreach($rsaktivasi as $r){
			$czkantorasal = $r['KPPBC'];
			$czidmodul = substr('00000'.$r['ID_MODUL'],-6);
		}

		if($czidmodul==''){
			$myar[] = ["cd" => '00', "msg" => "Please check Aktivasi CEISA Data" ];
		} else {
			$ocustdate = date_create($ccustdate);
			$cz_ymd = date_format($ocustdate, 'Ymd');
			$znomor_aju = substr($czkantorasal,0,4).$czdocbctype.$czidmodul.$cz_ymd.$cnoaju;
			$ctpb_tgl_daftar = strlen(trim($ctpb_tgl_daftar))==10 ? $ctpb_tgl_daftar : NULL;
			$keys = ['DLV_ID' => $cid];
			$vals = [
				'DLV_NOPEN' => $cnopen, 'DLV_NOAJU' => $cnoaju , 'DLV_ZNOMOR_AJU' => $znomor_aju,
				'DLV_ZJENIS_TPB_ASAL' => $ctpb_asal, 'DLV_ZJENIS_TPB_TUJUAN' => $ctpb_tujuan,
				'DLV_LUPDT' => $crnt_dt, 'DLV_USRID' => $this->session->userdata('nama'),
				'DLV_CONA' => $cona,
				'DLV_FROMOFFICE' => $cfromoffice,'DLV_DESTOFFICE' => $cdestoffice, 'DLV_PURPOSE' => $cpurpose,
				'DLV_ZID_MODUL' => $czidmodul, 'DLV_RPDATE' => $ctpb_tgl_daftar				
			];
			$ret = $this->DELV_mod->updatebyVAR($vals, $keys);
			$myar[] = $ret>0 ? ["cd" => '11', "msg" => "Updated successfully"] : ["cd" => '00', "msg" => "No data to be updated"];
		}		
		die(json_encode($myar));
	}

	public function change41(){
		date_default_timezone_set('Asia/Jakarta');		
		$crnt_dt = date('Y-m-d H:i:s');
		$cid = $this->input->get('inid');
		$cnoaju = $this->input->get('inaju');
		$cnopen = $this->input->get('innopen');		
		$cfromoffice = $this->input->get('infromoffice');
		$cpurpose = $this->input->get('inpurpose');
		$ctpb_asal = $this->input->get('injenis_tpb_asal');
		$ctpb_tgl_daftar = $this->input->get('intgldaftar');
		$cona = $this->input->get('incona');
		$rsaktivasi = $this->AKTIVASIAPLIKASI_imod->selectAll();
		$rs_head_dlv = $this->DELV_mod->select_header_bydo($cid);
		$ttlPostedRows = $this->DELV_mod->check_Primary(['DLV_ID' => $cid, 'DLV_POSTTM IS NOT NULL' => null]);
		$ttlPostedAndHasbc = $this->DELV_mod->check_Primary(['DLV_ID' => $cid, "ISNULL(DLV_NOPEN,'')" => '']);
		if($cnopen=='null') {
			$cnopen = '';
		}
		if($ttlPostedRows>0 && $ttlPostedAndHasbc===0) {
			$myar[] = ["cd" => '00', "msg" => "could not update, because the data is already posted"];
			die(json_encode($myar));
		}
		$czidmodul ='';
		$myar = [];
		foreach($rs_head_dlv as $r){
			$czdocbctype = $r['DLV_BCTYPE'];
			$ccustdate = $r['DLV_BCDATE'];
		}
		foreach($rsaktivasi as $r){
			$czkantorasal = $r['KPPBC'];
			$czidmodul = substr('00000'.$r['ID_MODUL'],-6);			
		}
		if($czidmodul==''){
			$myar[] = ["cd" => '00', "msg" => "Please check Aktivasi CEISA Data" ];
		} else {
			$ocustdate = date_create($ccustdate);
			$cz_ymd = date_format($ocustdate, 'Ymd');
			$znomor_aju = substr($czkantorasal,0,4).$czdocbctype.$czidmodul.$cz_ymd.$cnoaju;
			$ctpb_tgl_daftar = strlen(trim($ctpb_tgl_daftar))==10 ? $ctpb_tgl_daftar : NULL;
			$keys = ['DLV_ID' => $cid];
			$vals = [
				'DLV_NOPEN' => $cnopen, 'DLV_NOAJU' => $cnoaju ,'DLV_ZNOMOR_AJU' => $znomor_aju,
				'DLV_ZJENIS_TPB_ASAL' => $ctpb_asal, 
				'DLV_CONA' => $cona,
				'DLV_LUPDT' => $crnt_dt, 'DLV_USRID' => $this->session->userdata('nama'),
				'DLV_FROMOFFICE' => $cfromoffice, 'DLV_PURPOSE' => $cpurpose,
				'DLV_ZID_MODUL' => $czidmodul, 'DLV_RPDATE' => $ctpb_tgl_daftar
			];
			$ret = $this->DELV_mod->updatebyVAR($vals, $keys);
			$myar[] = $ret>0 ?  ["cd" => '11', "msg" => "Updated successfully" ] : ["cd" => '00', "msg" => "No data to be updated" ];
		}
		die(json_encode($myar));
	}

	public function checkSession(){
		$myar =[];
		if ($this->session->userdata('status') != "login")
        {
			$myar [] = ["cd" => "0", "msg" => "Session is expired please reload page"];			
			exit(json_encode($myar));
        }
	}

	public function approve(){
		$this->checkSession();
		date_default_timezone_set('Asia/Jakarta');		
		$crnt_dt = date('Y-m-d H:i:s');
		$cid = $this->input->post('inid');
		$cdata = ['DLV_APPRV' =>  $this->session->userdata('nama'),	'DLV_APPRVTM' => $crnt_dt];
		$cwhere = [	'DLV_ID' => $cid,	'DLV_APPRV IS NULL' => null	];
		$myar = [];
		$cret = $this->DELV_mod->updatebyVAR($cdata, $cwhere);
		$myar[] = ($cret>0) ? ["cd" => "1", "msg" => "Approved" , "approved_time" => $crnt_dt] : ["cd" => "0", "msg" => "Could not approve, because it has been approved"];		
		die(json_encode($myar));
	}

	public function get_do_bom_as_xls(){
		if(!isset($_COOKIE["CKPSI_DOBOM"]) ){
			exit('no data to be found');
		}
		$do = $_COOKIE["CKPSI_DOBOM"];
		$rs = $this->DELV_mod->select_bom($do);
		$spreadsheet = new Spreadsheet();
		$sheet = $spreadsheet->getActiveSheet();
		$sheet->setTitle('DOBOM');
		$sheet->setCellValueByColumnAndRow(1,1, 'DO : '.$do);
		$sheet->setCellValueByColumnAndRow(1,2, 'ASSY CODE');
		$sheet->setCellValueByColumnAndRow(2,2, 'FG QTY');		
		$sheet->setCellValueByColumnAndRow(3,2, 'ITEM CODE');
		$sheet->setCellValueByColumnAndRow(4,2, 'USE QTY');
		$sheet->setCellValueByColumnAndRow(5,2, 'SUPPLIED QTY');
		$sheet->setCellValueByColumnAndRow(6,2, 'REFF NO');
		$sheet->setCellValueByColumnAndRow(7,2, 'JOB');
				
		$no=3;
		$isSubAssyFound = false;
		$smtFontBold = ['font' => ['bold' => true]];
		foreach($rs as $r){
			if($r['MITM_MODEL']=='1'){
				$isSubAssyFound = true;
				$sheet->getStyle('C'.$no.':C'.$no)->applyFromArray($smtFontBold);
			}
			$sheet->setCellValueByColumnAndRow(1,$no, $r['SER_ITMID']);
			$sheet->setCellValueByColumnAndRow(2,$no, $r['SERD2_FGQTY']);					
			$sheet->setCellValueByColumnAndRow(3,$no, $r['SERD2_ITMCD']);
			$sheet->setCellValueByColumnAndRow(4,$no, $r['MYPER']);
			$sheet->setCellValueByColumnAndRow(5,$no, $r['SERD2_FGQTY']*$r['MYPER']);
			$sheet->setCellValueByColumnAndRow(6,$no, $r['SERD2_SER']);
			$sheet->setCellValueByColumnAndRow(7,$no, $r['SER_DOC']);
			$no++;
		}
		$sheet->getColumnDimension('A')->setAutoSize(true);
        $sheet->getColumnDimension('B')->setAutoSize(true);
        $sheet->getColumnDimension('C')->setAutoSize(true);
        $sheet->getColumnDimension('D')->setAutoSize(true);
        $sheet->getColumnDimension('E')->setAutoSize(true);
        $sheet->getColumnDimension('F')->setAutoSize(true);
        $sheet->getColumnDimension('G')->setAutoSize(true);

		#CREATE NEW SHEET FOR SUB ASSY DATA, IF EXIST
		if($isSubAssyFound){
			$sheet = $spreadsheet->createSheet();
			$sheet->setTitle("SUB_ASSY");
			$sheet->setCellValueByColumnAndRow(1,1, 'DO : '.$do);
			$sheet->setCellValueByColumnAndRow(1,2, 'ASSY CODE');
			$sheet->setCellValueByColumnAndRow(2,2, 'FG QTY');
			$sheet->setCellValueByColumnAndRow(3,2, 'ITEM CODE');
			$sheet->setCellValueByColumnAndRow(4,2, 'USE QTY');
			$sheet->setCellValueByColumnAndRow(5,2, 'SUPPLIED QTY');
			$sheet->setCellValueByColumnAndRow(6,2, 'REFF NO');
			$sheet->setCellValueByColumnAndRow(7,2, 'JOB');
			$sheet->setCellValueByColumnAndRow(8,2, 'MAIN REFF NO');
			$rs = $this->DELV_mod->select_bom_subassy($do);
			$no=3;
			foreach($rs as $r){
				$sheet->setCellValueByColumnAndRow(1,$no, $r['SER_ITMID']);
				$sheet->setCellValueByColumnAndRow(2,$no, $r['SERD2_FGQTY']);					
				$sheet->setCellValueByColumnAndRow(3,$no, $r['SERD2_ITMCD']);
				$sheet->setCellValueByColumnAndRow(4,$no, $r['MYPER']);
				$sheet->setCellValueByColumnAndRow(5,$no, $r['SERD2_FGQTY']*$r['MYPER']);
				$sheet->setCellValueByColumnAndRow(6,$no, $r['SERD2_SER']);
				$sheet->setCellValueByColumnAndRow(7,$no, $r['SER_DOC']);
				$sheet->setCellValueByColumnAndRow(8,$no, $r['DLV_SER']);
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
		}
		#END CREATE

		$dodis = str_replace("/", "#", $do);
		$stringjudul = "DO BOM ".$dodis;
		$writer = new Xlsx($spreadsheet);
		$filename=$stringjudul; //save our workbook as this file name
		
		header('Content-Type: application/vnd.ms-excel');
		header('Content-Disposition: attachment;filename="'. $filename .'.xlsx"'); 
		header('Cache-Control: max-age=0');
		$writer->save('php://output');
	}	
	public function export_to_so_mega_as_xls(){
		if(!isset($_COOKIE["CKPSI_DOBOM"]) ){
			exit('no data to be found');
		}
		$do = $_COOKIE["CKPSI_DOBOM"];
		$rs = $this->DELV_mod->select_shipping_for_mega($do);
		$spreadsheet = new Spreadsheet();
		$sheet = $spreadsheet->getActiveSheet();
		$sheet->setTitle('shippingmega');
		
		$sheet->setCellValueByColumnAndRow(1,1, 'TRANS NO');
		$sheet->setCellValueByColumnAndRow(2,1, 'TRANS DATE');		
		$sheet->setCellValueByColumnAndRow(3,1, 'ITEM CODE');
		$sheet->setCellValueByColumnAndRow(4,1, 'DELIVERY QTY');
		$sheet->setCellValueByColumnAndRow(5,1, 'ORDER NO.');
		$sheet->setCellValueByColumnAndRow(6,1, 'CPO Line No');	
		$sheet->getStyle('A1:F1')->getFill()->setFillType(\PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID)
		->getStartColor()->setARGB('98D1FC');
		foreach(range('A','F') as $columnID) {
			$sheet->getColumnDimension($columnID)
				->setAutoSize(true);
		}
		$no=2;
		foreach($rs as $r){
			$sheet->setCellValueByColumnAndRow(1,$no, $do);
			$sheet->setCellValueByColumnAndRow(2,$no, $r['MXDLV_DATE']);					
			$sheet->setCellValueByColumnAndRow(3,$no, trim($r['SSO2_MDLCD']));
			$sheet->setCellValueByColumnAndRow(4,$no, $r['SISOQTY']);
			$sheet->setCellValueByColumnAndRow(5,$no, $r['SISO_CPONO']);
			$sheet->setCellValueByColumnAndRow(6,$no, $r['SISO_SOLINE']);			
			$no++;
		}

		$dodis = str_replace("/", " ", $do);

		$stringjudul = $dodis;
		$writer = new Xlsx($spreadsheet);
		$filename=$stringjudul; //save our workbook as this file name
		
		header('Content-Type: application/vnd.ms-excel');
		header('Content-Disposition: attachment;filename="'. $filename .'.xlsx"'); 
		header('Cache-Control: max-age=0');
		$writer->save('php://output');
	}

	public function posting27_initdata(){
		header('Content-Type: application/json');
		ini_set('max_execution_time', '-1');
		date_default_timezone_set('Asia/Jakarta');		
		$csj = $this->input->get('insj');		

		$czsj = $csj;
		$rs_head_dlv = $this->DELV_mod->select_header_bydo($csj);
		$rs_rm_null = $this->DELV_mod->select_rm_null($csj);
		if(count($rs_rm_null)>0){
			#check com job
			$ser_com_calcualted = [];
			foreach($rs_rm_null as $r){
				$rs_def = $this->SERC_mod->select_cols_where_id(['SERC_COMID','SERC_COMJOB','SERC_COMQTY'], $r['DLV_SER']); #detail combined ser
				foreach($rs_def as $k){
					$countrm_ = $this->SERD_mod->select_perlabel_resume_item($k['SERC_COMID']); #detail combined ser -> rm count
					if($countrm_ == 0 ){
						$ser_com_calcualted[] = ['NEWID' => $r['DLV_SER'], 'COMID' => $k['SERC_COMID'], 'RM' => $countrm_ ];
					}
				}
			}

			#filter rs_rm_null 
			$rs_filtered = [];
			foreach($rs_rm_null as $r){
				foreach($ser_com_calcualted as $n){
					if($r['DLV_SER'] == $n['NEWID']){
						$isfound = false;
						foreach($rs_filtered as $n){
							if($n['DLV_SER'] == $r['DLV_SER']){
								$isfound=true;break;
							}
						}
						if(!$isfound){
							$rs_filtered[] = $r;
						}
					}
				}
			}
			if(count($rs_filtered)>0){
				$myar[] = ['cd' => 100 ,'msg' => 'RM is null, please check again data in the table below'];
				die('{"status" : '.json_encode($myar).', "data": '.json_encode($rs_filtered).'}'); #$rs_rm_null
			} else {
				#go ahead
			}			
		}
		$rsaktivasi = $this->AKTIVASIAPLIKASI_imod->selectAll();
		$ccustdate ='';
		$nomoraju = '';
		$ccustomer_do = '';
		$cbusiness_group = '';
		$czcurrency ='';
		$czdocbctype ='-';
		$cz_KODE_JENIS_TPB ='';
		$cz_KODE_TUJUAN_TPB ='';
		$czinvoice = '';
		$czkantorasal = $czidmodul = $czidmodul_asli  ='' ;
		$consignee = '-';
		$cznmpenerima = '';
		$czalamatpenerima = '';
		$czizinpenerima = '';
		$cznomorpolisi = '-';
		$cznamapengangkut = '';	
		$czinvoicedt = '';
		$czidpenerima = '';
		$cztujuanpengiriman = '-';
		$czkantortujuan = '';
		
		foreach($rs_head_dlv as $r){
			if($r['DLV_BCDATE']){
				$consignee = $r['DLV_CONSIGN'];
				$czdocbctype = $r['DLV_BCTYPE'];
				$ccustdate = $r['DLV_BCDATE'];
				$nomoraju = $r['DLV_NOAJU'];
				$ccustomer_do = $r['DLV_CUSTDO'];
				$czinvoice = trim($r['DLV_INVNO']);
				$cbusiness_group = $r['DLV_BSGRP'];
				$czcurrency = trim($r['MCUS_CURCD']);
				$cz_KODE_JENIS_TPB = $r['DLV_ZJENIS_TPB_ASAL'];
				$cz_KODE_TUJUAN_TPB = $r['DLV_ZJENIS_TPB_TUJUAN'];
				$cznmpenerima = $r['MDEL_ZNAMA'];
				$czalamatpenerima = $r['MDEL_ADDRCUSTOMS'];
				$czizinpenerima = $r['MDEL_ZSKEP'];
				$cznomorpolisi = $r['DLV_TRANS'];
				$cznamapengangkut = $r['MSTTRANS_TYPE'];
				$czinvoicedt = $r['DLV_INVDT'];
				$czidpenerima = str_replace(".","",$r['MCUS_TAXREG']);
				$czidpenerima = str_replace("-","", $czidpenerima);
				$cztujuanpengiriman = $r['DLV_PURPOSE'];
				$czkantortujuan = $r['DLV_DESTOFFICE'];				
				break;
			}
		}
		
		if($cztujuanpengiriman=='-'){
			$myar[] = ['cd' => 0 ,'msg' => 'please set TUJUAN PENGIRIMAN ('.$cztujuanpengiriman.')'];
			die('{"status" : '.json_encode($myar).'}');
		}

		if($consignee=='-'){
			$myar[] = ['cd' => 0 ,'msg' => 'please set consignment ('.$consignee.')'];
			die('{"status" : '.json_encode($myar).'}');
		} 

		if($czinvoice==''){
			$myar[] = ['cd' => 0 ,'msg' => 'please add invoice number ('.$czinvoice.') custdate ('.$ccustdate.') consign ('.$consignee.')'];
			die('{"status" : '.json_encode($myar).'}');
		}
		$ocustdate = date_create($ccustdate);
		$cz_ymd = date_format($ocustdate, 'Ymd');
		
		$czidpengusaha = '';
		$cznmpengusaha = '';
		$czalamatpengusaha = '';
		$czizinpengusaha = '';

		foreach($rsaktivasi as $r){
			$czkantorasal = $r['KPPBC'];
			$czidmodul = substr('00000'.$r['ID_MODUL'],-6);
			$czidmodul_asli = $r['ID_MODUL'];
			$czidpengusaha = $r['NPWP'];
			$cznmpengusaha = $r['NAMA_PENGUSAHA'];
			$czalamatpengusaha = $r['ALAMAT_PENGUSAHA'];
			$czizinpengusaha = $r['NOMOR_SKEP'];		
		}

		if($czdocbctype=='-'){
			$myar[] = ['cd' => 0 ,'msg' => 'please select bc type!'];
			die('{"status" : '.json_encode($myar).'}');
		}

		if(strlen($nomoraju)!=6){
			$myar[] = ['cd' => 0 ,'msg' => 'NOMOR AJU is not valid, please re-check ('.$nomoraju.')'];
			die('{"status" : '.json_encode($myar).'}');
		}
		
		$cnoaju = substr($czkantorasal,0,4).$czdocbctype.$czidmodul.$cz_ymd.$nomoraju;

		if($this->TPB_HEADER_imod->check_Primary(['NOMOR_AJU' => $cnoaju])>0){
			$myar[] = ['cd' => 0 ,'msg' => 'the NOMOR AJU is already posted'];
			die('{"status" : '.json_encode($myar).'}');
		}

		if(($cbusiness_group=='PSI2PPZOMC' || $cbusiness_group =='PSI2PPZOMI' ) ){			
			if(strlen($ccustomer_do)<5){
				$myar[] = ['cd' => 0 ,'msg' => 'please enter valid Customer DO!'];
				die('{"status" : '.json_encode($myar).'}');
			}
			$czsj = $ccustomer_do;
		}
		if(strlen($nomoraju)!=6){
			$myar[] = ['cd' => 0 ,'msg' => 'please enter valid Nomor Pengajuan!'];
			die('{"status" : '.json_encode($myar).'}');
		}						
		
		$rsdlv = $this->DELV_mod->select_det_byid_p($csj);
		$cz_JUMLAH_KEMASAN = count($rsdlv);
		
		$myar = [];
		
		if(strlen($ccustdate)!=10){
			$myar[] = ['cd' => 0 ,'msg' => 'please enter valid customs date!'];
			die('{"status" : '.json_encode($myar).'}');
		}

		if($this->DELV_mod->check_Primary(['DLV_ID' => $csj])==0){
			$myar[] = ['cd' => 0 ,'msg' => 'DO is not found'];
			die('{"status" : '.json_encode($myar).'}');
		}	

		$rscurr = $this->MEXRATE_mod->selectfor_posting($ccustdate,$czcurrency);
		if(count($rscurr)==0){
			$myar[] = ["cd" => "0", "msg" => "Please fill exchange rate data !"];
			die('{"status":'.json_encode($myar).'}');
		} else{
			foreach($rscurr as  $r){
				$czharga_matauang = $r->MEXRATE_VAL;break;
			}
		}

		#HEADER_HARGA
		$cz_h_CIF_FG = 0;
		$cz_h_HARGA_PENYERAHAN_FG = 0;
		$rsitem_p_price = $this->DELV_mod->select_item_per_price($csj);

		#INIT PRICE
		$rsresume = [];
		$rsmultiprice = [];
		foreach($rsitem_p_price as &$k){
			if($k['SISO_SOLINE']=='X'){
				$rs_mst_price = $this->XSO_mod->select_latestprice($k['SI_BSGRP'], $k['SI_CUSCD'], "'".$k['SSO2_MDLCD']."'" );
				foreach($rs_mst_price as $r){
					$k['SSO2_SLPRC'] = substr($r['MSPR_SLPRC'],0,1) =='.' ? '0'.$r['MSPR_SLPRC'] : $r['MSPR_SLPRC'];					
					$k['CIF'] = $k['SSO2_SLPRC']*$k['SISOQTY'];					
				}
			}
			$isfound = false;
			foreach($rsresume as &$n){
				if($n['RSI_ITMCD'] == $k['SSO2_MDLCD'] && $n['RSSO2_SLPRC'] == $k['SSO2_SLPRC']) {
						$n['RCOUNT']++;
					$isfound = true;
					break;
				}
			}
			unset($n);

			if(!$isfound){
				$rsresume[] = [
					'RSI_ITMCD' => $k['SSO2_MDLCD'] 
					,'RSSO2_SLPRC' => $k['SSO2_SLPRC']
					,'RCOUNT' => 1
				];
			}
		}
		unset($k);

		foreach($rsresume as $k){
			if($k['RCOUNT'] > 1){
				$rsmultiprice[] = $k;
			}
		}		
		#END INIT PRICE

		

		$rsplotrm_per_fgprice = $this->perprice($csj, $rsitem_p_price);
		die(json_encode(['data' => $rsplotrm_per_fgprice]));

		
		$cz_h_JUMLAH_BARANG = count($rsitem_p_price);
		$cz_h_NETTO = 0;
		$cz_h_BRUTO = 0;
		$tpb_barang = [];
		$SERI_BARANG = 1;
		foreach($rsitem_p_price as $r){
			$t_HARGA_PENYERAHAN = $r['CIF']*$czharga_matauang;
			$cz_h_CIF_FG += $r['CIF'];
			$cz_h_NETTO += $r['NWG'];
			$cz_h_BRUTO += $r['GWG'];
			$cz_h_HARGA_PENYERAHAN_FG += $t_HARGA_PENYERAHAN;
			$tpb_barang[] = [
				'KODE_BARANG' => $r['SSO2_MDLCD']
				,'POS_TARIF' => $r['MITM_HSCD']
				,'URAIAN' => $r['MITM_ITMD1']
				,'JUMLAH_SATUAN' => $r['SISOQTY']
				,'KODE_SATUAN' => $r['MITM_STKUOM']=='PCS' ? 'PCE' : $r['MITM_STKUOM']
				,'NETTO' => $r['NWG']
				,'CIF' => round($r['CIF'],2)
				,'HARGA_PENYERAHAN' => $t_HARGA_PENYERAHAN
				,'SERI_BARANG' => $SERI_BARANG
				,'KODE_STATUS' => '02'
			];
			$SERI_BARANG++;
		}
		#N
		#BAHAN BAKU
		$tpb_bahan_baku = [];
		$requestResume = [];
		$responseResume = [];

		
		$requestGroup = [];
		foreach($rsplotrm_per_fgprice as $r){			
			$isfound = false;
			foreach($requestResume as &$n){
				if($n['ITEM'] == $r['RITEMCD']){
					$n['QTY'] += $r['RQTY'];
					$isfound = true;
				}
			}
			unset($n);
			if(!$isfound){
				$requestResume[] = ['ITEM' => $r['RITEMCD'], 'QTY' => $r['RQTY']];
			}

			$isfound = false;
			foreach($requestGroup as &$n){
				if($n['XASSY'] == $r['RASSYCODE'] && $n['XPRICE'] == $r['RPRICEGROUP']){						
					$isfound = true;break;
				}
			}
			unset($n);
			if(!$isfound){
				$requestGroup[] = ['XASSY' => $r['RASSYCODE'], 'XPRICE' => $r['RPRICEGROUP']];
			}
		}
		$ch = curl_init();
		curl_setopt($ch, CURLOPT_URL, 'http://192.168.0.29:8081/api_inventory/api/inventory/sendqueueexbc');
		curl_setopt($ch, CURLOPT_POST, true);
		curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
		curl_setopt($ch, CURLOPT_CONNECTTIMEOUT, 0);
		curl_setopt($ch, CURLOPT_TIMEOUT, 3600);
		$responseList = [];	
		foreach($requestGroup as $k){
			$ary_item = [];
			$ary_qty = [];
			$ary_lot = [];
			foreach($rsplotrm_per_fgprice as $r){
				if($k['XASSY'] == $r['RASSYCODE'] && $k['XPRICE'] == $r['RPRICEGROUP']){
					$ary_item[] = $r['RITEMCD'];
					$ary_qty[] = $r['RQTY'];
					$ary_lot[] = $r['RLOTNO'];
				}
			}
			$fields = [
				'bc' => $czdocbctype,
				'tujuan' => $cztujuanpengiriman,
				'date_out' => $ccustdate,
				'doc' => $csj,
				'kontrak' => "",
				'item_num' => $ary_item,
				'qty' => $ary_qty,
				'lot' => $ary_lot
			];
			$fields_string = http_build_query($fields);
			curl_setopt($ch, CURLOPT_POSTFIELDS,$fields_string);			
			// $rstemp = curl_exec($ch);
			// $responseList[] = $rstemp;
		}
		
		$myar[] = ['cd' => 110 ,'msg' => 'Done init data and get exbc'];
		die(json_encode(['status' => $myar, 'response' => $responseList]));
	}

	public function posting27_combinejob(){
		$csj = $this->input->get('insj');
		$rs_rm_null = $this->DELV_mod->select_rm_null($csj);
		$ser_com_calcualted = [];
		if(count($rs_rm_null)>0){
			#check com job
			$ser_com_calcualted = [];
			foreach($rs_rm_null as $r){
				$rs_def = $this->SERC_mod->select_cols_where_id(['SERC_COMID','SERC_COMJOB','SERC_COMQTY'], $r['DLV_SER']); #detail combined ser				
				if(count($rs_def)) {
					foreach($rs_def as $k){
						$countrm_ = $this->SERD_mod->select_perlabel_resume_item($k['SERC_COMID']); #detail combined ser -> rm count
						if($countrm_ == 0 ){
							$ser_com_calcualted[] = ['NEWID' => $r['DLV_SER'], 'COMID' => $k['SERC_COMID'], 'RM' => $countrm_ ];
						}
					}
				} else {					
					$rs_def = $this->SERC_mod->select_cols_where_id(['SERC_COMID','SERC_COMJOB','SERC_COMQTY'], $r['SER_REFNO']); #detail combined ser
					foreach($rs_def as $k){						
						$countrm_ = $this->SERD_mod->select_perlabel_resume_item($k['SERC_COMID']); #detail combined ser -> rm count
						if($countrm_ == 0 ){
							$ser_com_calcualted[] = ['NEWID' => $r['DLV_SER'], 'COMID' => $k['SERC_COMID'], 'RM' => $countrm_ ];
						}
					}
				}
				
			}

			#filter rs_rm_null 
			$rs_filtered = [];
			foreach($rs_rm_null as $r){
				foreach($ser_com_calcualted as $n){
					if($r['DLV_SER'] == $n['NEWID']){
						$isfound = false;
						foreach($rs_filtered as $n){
							if($n['DLV_SER'] == $r['DLV_SER']){
								$isfound=true;break;
							}
						}
						if(!$isfound){
							$rs_filtered[] = $r;
						}
					}
				}
			}
			if(count($rs_filtered)>0){
				$myar[] = ['cd' => 100 ,'msg' => 'RM is null, please check again data in the table below'];
				die('{"status" : '.json_encode($myar).', "data": '.json_encode($rs_filtered).'}');
			} else {
				#go ahead				
			}
		}
		die(json_encode(['datacalcombine' => $ser_com_calcualted]));
	}

	public function posting27(){
		set_exception_handler([$this,'exception_handler']);
		set_error_handler([$this, 'log_error']);
		register_shutdown_function([$this, 'fatal_handler']);

		header('Content-Type: application/json');
		ini_set('max_execution_time', '-1');
		date_default_timezone_set('Asia/Jakarta');	
		$currentDate = date('Y-m-d H:i:s');	
		$csj = $this->input->get('insj');

		$czsj = $csj;
		$rs_head_dlv = $this->DELV_mod->select_header_bydo($csj);
		$rs_rm_null = $this->DELV_mod->select_rm_null($csj);
		if(count($rs_rm_null)>0){
			#check com job
			$ser_com_calcualted = [];
			foreach($rs_rm_null as $r){
				$rs_def = $this->SERC_mod->select_cols_where_id(['SERC_COMID','SERC_COMJOB','SERC_COMQTY'], $r['DLV_SER']); #detail combined ser
				if(count($rs_def)) {
					foreach($rs_def as $k){
						$countrm_ = $this->SERD_mod->select_perlabel_resume_item($k['SERC_COMID']); #detail combined ser -> rm count
						if($countrm_ == 0 ){
							$ser_com_calcualted[] = ['NEWID' => $r['DLV_SER'], 'COMID' => $k['SERC_COMID'], 'RM' => $countrm_ ];
						}
					}
				} else {
					$rs_def = $this->SERC_mod->select_cols_where_id(['SERC_COMID','SERC_COMJOB','SERC_COMQTY'], $r['SER_REFNO']); #detail combined ser
					foreach($rs_def as $k){						
						$countrm_ = $this->SERD_mod->select_perlabel_resume_item($k['SERC_COMID']); #detail combined ser -> rm count
						if($countrm_ == 0 ){
							$ser_com_calcualted[] = ['NEWID' => $r['DLV_SER'], 'COMID' => $k['SERC_COMID'], 'RM' => $countrm_ ];
						}
					}
				}
			}

			#filter rs_rm_null 
			$rs_filtered = [];
			foreach($rs_rm_null as $r){
				foreach($ser_com_calcualted as $n){
					if($r['DLV_SER'] == $n['NEWID']){
						$isfound = false;
						foreach($rs_filtered as $n){
							if($n['DLV_SER'] == $r['DLV_SER']){
								$isfound=true;break;
							}
						}
						if(!$isfound){
							$rs_filtered[] = $r;
						}
					}
				}
			}
			if(count($rs_filtered)>0){
				$myar[] = ['cd' => 100 ,'msg' => 'RM is null, please check again data in the table below'];
				die('{"status" : '.json_encode($myar).', "data": '.json_encode($rs_filtered).'}');
			} else {
				#go ahead
			}			
		}
		$rsaktivasi = $this->AKTIVASIAPLIKASI_imod->selectAll();
		$ccustdate ='';
		$nomoraju = '';
		$ccustomer_do = '';
		$cbusiness_group = '';
		$czcurrency ='';
		$czdocbctype ='-';
		$cz_KODE_JENIS_TPB ='';
		$cz_KODE_TUJUAN_TPB ='';
		$czinvoice = '';
		$czkantorasal = $czidmodul = $czidmodul_asli  ='' ;
		$consignee = '-';
		$cznmpenerima = '';
		$czalamatpenerima = '';
		$czizinpenerima = '';
		$cznomorpolisi = '-';
		$cznamapengangkut = '';	
		$czinvoicedt = '';
		$czidpenerima = '';
		$cztujuanpengiriman = '-';
		$czkantortujuan = '';
		$czConaNo = '';
		$czConaDate = '';
		foreach($rs_head_dlv as $r){
			if($r['DLV_BCDATE']){
				$consignee = $r['DLV_CONSIGN'];
				$czdocbctype = $r['DLV_BCTYPE'];
				$ccustdate = $r['DLV_BCDATE'];
				$nomoraju = $r['DLV_NOAJU'];
				$ccustomer_do = $r['DLV_CUSTDO'];
				$czinvoice = trim($r['DLV_INVNO']);
				$cbusiness_group = $r['DLV_BSGRP'];
				$czcurrency = trim($r['MCUS_CURCD']);
				$cz_KODE_JENIS_TPB = $r['DLV_ZJENIS_TPB_ASAL'];
				$cz_KODE_TUJUAN_TPB = $r['DLV_ZJENIS_TPB_TUJUAN'];
				$cznmpenerima = $r['MDEL_ZNAMA'];
				$czalamatpenerima = $r['MDEL_ADDRCUSTOMS'];
				$czizinpenerima = $r['MDEL_ZSKEP'];
				$cznomorpolisi = $r['DLV_TRANS'];
				$cznamapengangkut = $r['MSTTRANS_TYPE'];
				$czinvoicedt = $r['DLV_INVDT'];
				$czidpenerima = str_replace(".","",$r['MCUS_TAXREG']);
				$czidpenerima = str_replace("-","", $czidpenerima);
				$cztujuanpengiriman = $r['DLV_PURPOSE'];
				$czkantortujuan = $r['DLV_DESTOFFICE'];
				$czConaNo = $r['DLV_CONA'];
				$czConaDate = $r['MCONA_DATE'];
				break;
			}
		}
		
		if($cztujuanpengiriman=='-'){
			$myar[] = ['cd' => 0 ,'msg' => 'please set TUJUAN PENGIRIMAN ('.$cztujuanpengiriman.')'];
			die('{"status" : '.json_encode($myar).'}');
		}

		if($consignee=='-'){
			$myar[] = ['cd' => 0 ,'msg' => 'please set consignment ('.$consignee.')'];
			die('{"status" : '.json_encode($myar).'}');
		} 

		if($czinvoice==''){
			$myar[] = ['cd' => 0 ,'msg' => 'please add invoice number ('.$czinvoice.') custdate ('.$ccustdate.') consign ('.$consignee.')'];
			die('{"status" : '.json_encode($myar).'}');
		}
		$ocustdate = date_create($ccustdate);
		$cz_ymd = date_format($ocustdate, 'Ymd');
		
		$czidpengusaha = '';
		$cznmpengusaha = '';
		$czalamatpengusaha = '';
		$czizinpengusaha = '';

		foreach($rsaktivasi as $r){
			$czkantorasal = $r['KPPBC'];
			$czidmodul = substr('00000'.$r['ID_MODUL'],-6);
			$czidmodul_asli = $r['ID_MODUL'];
			$czidpengusaha = $r['NPWP'];
			$cznmpengusaha = $r['NAMA_PENGUSAHA'];
			$czalamatpengusaha = $r['ALAMAT_PENGUSAHA'];
			$czizinpengusaha = $r['NOMOR_SKEP'];		
		}

		if($czdocbctype=='-'){
			$myar[] = ['cd' => 0 ,'msg' => 'please select bc type!'];
			die('{"status" : '.json_encode($myar).'}');
		}

		if(strlen($nomoraju)!=6){
			$myar[] = ['cd' => 0 ,'msg' => 'NOMOR AJU is not valid, please re-check ('.$nomoraju.')'];
			die('{"status" : '.json_encode($myar).'}');
		}
		
		$cnoaju = substr($czkantorasal,0,4).$czdocbctype.$czidmodul.$cz_ymd.$nomoraju;

		if($this->TPB_HEADER_imod->check_Primary(['NOMOR_AJU' => $cnoaju])>0){
			$myar[] = ['cd' => 0 ,'msg' => 'the NOMOR AJU is already posted'];
			die('{"status" : '.json_encode($myar).'}');
		}

		if(($cbusiness_group=='PSI2PPZOMC' || $cbusiness_group =='PSI2PPZOMI' ) ){			
			if(strlen($ccustomer_do)<5){
				$myar[] = ['cd' => 0 ,'msg' => 'please enter valid Customer DO!'];
				die('{"status" : '.json_encode($myar).'}');
			}
			$czsj = $ccustomer_do;
		}
		if(strlen($nomoraju)!=6){
			$myar[] = ['cd' => 0 ,'msg' => 'please enter valid Nomor Pengajuan!'];
			die('{"status" : '.json_encode($myar).'}');
		}						
		
		$rsdlv = $this->DELV_mod->select_det_byid_p($csj);
		$cz_JUMLAH_KEMASAN = count($rsdlv);
		
		$myar = [];
		
		if(strlen($ccustdate)!=10){
			$myar[] = ['cd' => 0 ,'msg' => 'please enter valid customs date!'];
			die('{"status" : '.json_encode($myar).'}');
		}

		if($this->DELV_mod->check_Primary(['DLV_ID' => $csj])==0){
			$myar[] = ['cd' => 0 ,'msg' => 'DO is not found'];
			die('{"status" : '.json_encode($myar).'}');
		}	

		$rscurr = $this->MEXRATE_mod->selectfor_posting($ccustdate,$czcurrency);
		if(count($rscurr)==0){
			$myar[] = ["cd" => "0", "msg" => "Please fill exchange rate data !"];
			die('{"status":'.json_encode($myar).'}');
		} else{
			foreach($rscurr as  $r){
				$czharga_matauang = $r->MEXRATE_VAL;break;
			}
		}

		#HEADER_HARGA
		$cz_h_CIF_FG = 0;
		$cz_h_HARGA_PENYERAHAN_FG = 0;
		$rsitem_p_price = $this->DELV_mod->select_item_per_price($csj);

		#INIT PRICE
		$rsresume = [];
		$rsmultiprice = [];
		foreach($rsitem_p_price as &$k){
			if($k['SISO_SOLINE']=='X'){
				$rs_mst_price = $this->XSO_mod->select_latestprice($k['SI_BSGRP'], $k['SI_CUSCD'], "'".$k['SSO2_MDLCD']."'" );
				foreach($rs_mst_price as $r){
					$k['SSO2_SLPRC'] = substr($r['MSPR_SLPRC'],0,1) =='.' ? '0'.$r['MSPR_SLPRC'] : $r['MSPR_SLPRC'];					
					$k['CIF'] = $k['SSO2_SLPRC']*$k['SISOQTY'];					
				}
			}
			$isfound = false;
			foreach($rsresume as &$n){
				if($n['RSI_ITMCD'] == $k['SSO2_MDLCD'] && $n['RSSO2_SLPRC'] != $k['SSO2_SLPRC']) {
						$n['RCOUNT']++;
					$isfound = true;
					break;
				}
			}
			unset($n);

			if(!$isfound){
				$rsresume[] = [
					'RSI_ITMCD' => $k['SSO2_MDLCD'] 
					,'RSSO2_SLPRC' => $k['SSO2_SLPRC']
					,'RCOUNT' => 1
				];
			}
		}
		unset($k);

		foreach($rsresume as $k){
			if($k['RCOUNT'] > 1){
				$rsmultiprice[] = $k;
			}
		}

		if(count($rsmultiprice)>0){
			$myar[] = ["cd" => "0", "msg" => "Multi price detected please, click 'Price Detail' to confirm "];
			die('{"status":'.json_encode($myar).',"data":'.json_encode($rsitem_p_price).',"data2":'.json_encode($rsmultiprice).'}');
		}
		#END INIT PRICE
		log_message('error', $_SERVER['REMOTE_ADDR'].', step0#, DO:'.$csj);
		log_message('error', $_SERVER['REMOTE_ADDR'].', step1#, start, group by assy code , price, item');
		$rsplotrm_per_fgprice = $this->perprice($csj, $rsitem_p_price);
		$cz_h_JUMLAH_BARANG = count($rsitem_p_price);
		$cz_h_NETTO = 0;
		$cz_h_BRUTO = 0;
		$tpb_barang = [];
		$SERI_BARANG = 1;
		foreach($rsitem_p_price as $r){
			$t_HARGA_PENYERAHAN = $r['CIF']*$czharga_matauang;
			$cz_h_CIF_FG += $r['CIF'];
			$cz_h_NETTO += $r['NWG'];
			$cz_h_BRUTO += $r['GWG'];
			$cz_h_HARGA_PENYERAHAN_FG += $t_HARGA_PENYERAHAN;
			$tpb_barang[] = [
				'KODE_BARANG' => $r['SSO2_MDLCD']
				,'POS_TARIF' => $r['MITM_HSCD']
				,'URAIAN' => $r['MITM_ITMD1']
				,'JUMLAH_SATUAN' => $r['SISOQTY']
				,'KODE_SATUAN' => $r['MITM_STKUOM']=='PCS' ? 'PCE' : $r['MITM_STKUOM']
				,'NETTO' => $r['NWG']
				,'CIF' => round($r['CIF'],2)
				,'HARGA_PENYERAHAN' => $t_HARGA_PENYERAHAN
				,'SERI_BARANG' => $SERI_BARANG
				,'KODE_STATUS' => '02'
			];
			$SERI_BARANG++;
		}
		#N
		log_message('error', $_SERVER['REMOTE_ADDR'].',step1#, finish, group by assy code , price, item');
		#BAHAN BAKU
		$tpb_bahan_baku = [];
		$requestResume = [];
		$responseResume = [];
		$rsExceeded = [];		
		try {			
			$requestGroup = [];
			log_message('error', $_SERVER['REMOTE_ADDR'].',step2#, start, posting group by assy code , price, item');
			foreach($rsplotrm_per_fgprice as $r){				
				$isfound = false;
				foreach($requestResume as &$n){
					if($n['ITEM'] == $r['RITEMCD']){
						$n['QTY'] += $r['RQTY'];
						$isfound = true;
					}
				}
				unset($n);
				if(!$isfound){
					$requestResume[] = ['ITEM' => $r['RITEMCD'],'ITEMGR' => $r['RITEMCDGR'], 'QTY' => $r['RQTY'], 'QTYPLOT' => 0];
				}

				$isfound = false;
				foreach($requestGroup as &$n){
					if($n['XASSY'] == $r['RASSYCODE'] && $n['XPRICE'] == $r['RPRICEGROUP']){						
						$isfound = true;break;
					}
				}
				unset($n);
				if(!$isfound){
					$requestGroup[] = ['XASSY' => $r['RASSYCODE'], 'XPRICE' => $r['RPRICEGROUP']];
				}
			}
			log_message('error', $_SERVER['REMOTE_ADDR'].',step2#, finish, posting group by assy code , price, item');
			$ch = curl_init();
			curl_setopt($ch, CURLOPT_URL, 'http://192.168.0.29:8081/api_inventory/api/inventory/getStockBCArray');
			curl_setopt($ch, CURLOPT_POST, true);
			curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
			curl_setopt($ch, CURLOPT_CONNECTTIMEOUT, 0);
			curl_setopt($ch, CURLOPT_TIMEOUT, 3600);	
			foreach($requestGroup as $k){
				$ary_item = [];
				$ary_qty = [];
				$ary_lot = [];
				foreach($rsplotrm_per_fgprice as $r){
					if($k['XASSY'] == $r['RASSYCODE'] && $k['XPRICE'] == $r['RPRICEGROUP']){
						$ary_item[] = $r['RITEMCDGR'] ==='' ? $r['RITEMCD'] : $r['RITEMCDGR'] ;
						$ary_qty[] = $r['RQTY'];
						$ary_lot[] = $r['RLOTNO'];
					}
				}
				$fields = [
					'bc' => $czdocbctype,
					'tujuan' => $cztujuanpengiriman,
					'date_out' => $ccustdate,
					'doc' => $csj,
					'kontrak' => $czConaNo,
					'item_num' => $ary_item,
					'qty' => $ary_qty //,
					// 'lot' => $ary_lot
				];
				$fields_string = http_build_query($fields);
				curl_setopt($ch, CURLOPT_POSTFIELDS,$fields_string);
				log_message('error', $_SERVER['REMOTE_ADDR'].',step3#, start, send request');
				$rstemp = curl_exec($ch);				
				log_message('error', $_SERVER['REMOTE_ADDR'].',step3#, start, receive request');
				$rsbc = json_decode($rstemp);				
				if(!is_null($rsbc)){
					if( count($rsbc)>0 ){						
						foreach($rsbc as $o){
							foreach($o->data as $v){
								if($v->BC_QTY>0) {
									$isfound = false;
									foreach($responseResume as &$n){
										if($n['ITEM'] == $v->BC_ITEM){
											$n['QTY'] += $v->BC_QTY;
											$n['BALRES'] += $v->BC_QTY;
											$isfound = true;
										}
									}
									unset($n);
									if(!$isfound){
										$responseResume[] = ['ITEM' => $v->BC_ITEM, 'QTY' => $v->BC_QTY, 'BALRES' => $v->BC_QTY];
									}
									
									if($v->RCV_KPPBC!='-'){
										$tpb_bahan_baku[] = [
											'KODE_JENIS_DOK_ASAL' => $v->BC_TYPE
											,'NOMOR_DAFTAR_DOK_ASAL' => $v->BC_NUM
											,'TANGGAL_DAFTAR_DOK_ASAL' => $v->BC_DATE
											,'KODE_KANTOR' => $v->RCV_KPPBC
											,'NOMOR_AJU_DOK_ASAL' => $v->BC_AJU
											,'SERI_BARANG_DOK_ASAL' => $v->RCV_ZNOURUT
											,'SPESIFIKASI_LAIN' => NULL
					
											,'CIF' => substr($v->RCV_PRPRC,0,1)=='.' ? ('0'.$v->RCV_PRPRC* $v->BC_QTY) : ($v->RCV_PRPRC * $v->BC_QTY)
											,'HARGA_PENYERAHAN' => 0
					
											,'KODE_BARANG' => $v->BC_ITEM 
											,'KODE_STATUS' => "03"
											,'POS_TARIF' => $v->RCV_HSCD
											,'URAIAN' => $v->MITM_ITMD1
											
											,'JUMLAH_SATUAN' => $v->BC_QTY
											,'JENIS_SATUAN' => ($v->MITM_STKUOM=='PCS') ? 'PCE' : $v->MITM_STKUOM						
											,'KODE_ASAL_BAHAN_BAKU' => ($v->BC_TYPE == '27' || $v->BC_TYPE == '23' ) ? '0' : '1'
					
											,'RASSYCODE' => $k['XASSY']
											,'RPRICEGROUP' => $k['XPRICE']
											,'RBM' => substr($v->RCV_BM,0,1) == '.' ? ('0'.$v->RCV_BM) : ($v->RCV_BM)											
										];
									} else {
										$tpb_bahan_baku[] = [
											'KODE_JENIS_DOK_ASAL' => $v->BC_TYPE
											,'NOMOR_DAFTAR_DOK_ASAL' => $v->BC_NUM
											,'TANGGAL_DAFTAR_DOK_ASAL' => $v->BC_DATE
											,'KODE_KANTOR' => NULL
											,'NOMOR_AJU_DOK_ASAL' => $v->BC_AJU 
											,'SERI_BARANG_DOK_ASAL' => $v->RCV_ZNOURUT
											,'SPESIFIKASI_LAIN' => NULL
						
											,'CIF' => 0
											,'HARGA_PENYERAHAN' => 0
						
											,'KODE_BARANG' => trim($v->BC_ITEM)
											,'KODE_STATUS' => "02"
											,'POS_TARIF' => NULL
											,'URAIAN' => NULL
											
											,'JUMLAH_SATUAN' => $v->BC_QTY 
											,'JENIS_SATUAN' => 'PCE'						
											,'KODE_ASAL_BAHAN_BAKU' => 0
						
											,'RASSYCODE' => $k['XASSY']
											,'RPRICEGROUP' => $k['XPRICE']
											,'RBM' => 0										
										];
									}
								}																
							}
						}
					} else {
						$myar[] = ["cd" => "0", "msg" => "Could not find exbc, please contact admin !", "api_respon" => $rstemp];
						$this->inventory_cancelDO($csj);
						die('{"status":'.json_encode($myar).'}');
					}
				} else {
					$this->inventory_cancelDO($csj);
					$myar[] = ["cd" => "0", "msg" => "Could not find exbc, please contact admin", "request" => $fields , "api_respon" => $rstemp];
					die('{"status":'.json_encode($myar).'}');
				}
				log_message('error', $_SERVER['REMOTE_ADDR'].',step3#, finish, receive request');
			}
			curl_close($ch);
			$listNeedExBC = [];
			foreach($requestResume as &$r){
				foreach($responseResume as &$n){
					if(($r['ITEM'] == $n['ITEM'] || $r['ITEMGR'] == $n['ITEM']) && $n['BALRES'] >0){
						$balreq = $r['QTY'] - $r['QTYPLOT'];
						if($balreq>$n['BALRES']){						
							$r['QTYPLOT'] +=  $n['BALRES'];
							$n['BALRES'] = 0;
						} else {
							$n['BALRES'] -= $balreq;
							$r['QTYPLOT'] += $balreq;
						}
						if($r['QTY']==$r['QTYPLOT']){
							break;
						}						
					}
				}
				unset($n);
			}
			unset($r);
			foreach($requestResume as $r){ 
				if($r['QTY']!=$r['QTYPLOT']) {
					$listNeedExBC[] = ['ITMCD' => $r['ITEM'], 'QTY' => $r['QTY'], 'LOTNO' => '?'];
				}
			}
			if(count($listNeedExBC)>0){
				$this->inventory_cancelDO($csj);
				$myar[] = ['cd' => 110 ,'msg' => 'EX-BC for '.count($listNeedExBC). ' item(s) is not found. ', "doctype" => $czdocbctype, "tujuankirim" => $cztujuanpengiriman ];
				die('{"status" : '.json_encode($myar).', "data":'.json_encode($listNeedExBC).',"rawdata":'.json_encode($tpb_bahan_baku).'}');
			}
			#clear
			$ary_item = [];
			$ary_qty = [];
			$ary_lot = [];
			$requestResume = [];
			$responseResume = [];

			unset($ary_item);
			unset($ary_qty);
			unset($ary_lot);
			unset($requestResume);
			unset($responseResume);
			#end
			
		} catch (Exception $e) {
			$this->inventory_cancelDO($csj);
			$myar[] = ['cd' => 110 ,'msg' => $e->getMessage()];
			die('{"status" : '.json_encode($myar).',"data":"'.$rstemp.'"}');
		}

		if(count($rsExceeded)) {			
			$msgs = [];
			$nom = 1;
			foreach($this->MSG_USERS as $r) {
				$msgs[] = [
					'MSG_FR' => 'ane'
					,'MSG_TO' => $r
					,'MSG_TXT' => 'Request > EX-BC Stock'
					,'MSG_REFFDATA' => json_encode($rsExceeded)
					,'MSG_DOC' => $csj
					,'MSG_LINE' => $nom
					,'MSG_TOPIC' => 'COMPONENT_EXCEEDED'
					,'MSG_CREATEDAT' => $currentDate
				];
				$nom++;
			}
			$this->MSG_mod->insertb($msgs);
			#berdasarkan persetujuan Asisten Manajer PPIC
			#diperbolehkan mengambil EXBC terakhir jika memang tidak ada lagi stock EXBC
			#jika ada EXBC berlebih, maka catat
			$this->PST_LOG_mod->insertb($rsExceeded);
		}

		foreach($rsplotrm_per_fgprice as $r){
			$nomor=1;
			foreach($tpb_bahan_baku as &$n){
				if($r['RASSYCODE'] == $n['RASSYCODE'] 
				&& $r['RPRICEGROUP']==$n['RPRICEGROUP'] ){
					$n['SERI_BAHAN_BAKU']=$nomor;
					$nomor++;
				}
			}
			unset($n);
		}
		#N

		#BAHAN BAKU DOKUMEN
		$tpb_bahan_baku_tarif = [];	
		foreach($tpb_bahan_baku as $r){			
			$tpb_bahan_baku_tarif[] = [
				'JENIS_TARIF' => 'BM'
				,'KODE_TARIF' => 1
				,'NILAI_BAYAR' => 0
				,'NILAI_FASILITAS' => 0
				,'KODE_FASILITAS' => 0
				,'TARIF_FASILITAS' => 100
				,'TARIF' => $r['RBM']
				,'SERI_BAHAN_BAKU' => $r['SERI_BAHAN_BAKU']

				,'RASSYCODE' => $r['RASSYCODE']
				,'RPRICEGROUP' => $r['RPRICEGROUP']						
				
				,'RITEMCD' => $r['KODE_BARANG']
			];
			$tpb_bahan_baku_tarif[] = [
				'JENIS_TARIF' => 'PPN'
				,'KODE_TARIF' => 1
				,'NILAI_BAYAR' => 0
				,'NILAI_FASILITAS' => 0
				,'KODE_FASILITAS' => 0
				,'TARIF_FASILITAS' => 100
				,'TARIF' => 10
				,'SERI_BAHAN_BAKU' => $r['SERI_BAHAN_BAKU']

				,'RASSYCODE' => $r['RASSYCODE']
				,'RPRICEGROUP' => $r['RPRICEGROUP']						
				
				,'RITEMCD' => $r['KODE_BARANG']
			];
			$tpb_bahan_baku_tarif[] = [
				'JENIS_TARIF' => 'PPH'
				,'KODE_TARIF' => 1
				,'NILAI_BAYAR' => 0
				,'NILAI_FASILITAS' => 0
				,'KODE_FASILITAS' => 0
				,'TARIF_FASILITAS' => 100
				,'TARIF' => 2.5
				,'SERI_BAHAN_BAKU' => $r['SERI_BAHAN_BAKU']

				,'RASSYCODE' => $r['RASSYCODE']
				,'RPRICEGROUP' => $r['RPRICEGROUP']						
				
				,'RITEMCD' => $r['KODE_BARANG']
			];			
		}
		#N		
				
		$tpb_header = [
			"NOMOR_AJU" => $cnoaju, "KODE_KANTOR" => $czkantorasal , "KODE_KANTOR_TUJUAN" => $czkantortujuan,
			"KODE_JENIS_TPB" => $cz_KODE_JENIS_TPB , "KODE_TUJUAN_TPB" => $cz_KODE_TUJUAN_TPB , "KODE_TUJUAN_PENGIRIMAN" => $cztujuanpengiriman,
			
			"KODE_ID_PENGUSAHA" => "1", "ID_PENGUSAHA" => $czidpengusaha, "NAMA_PENGUSAHA" => $cznmpengusaha,
			"ALAMAT_PENGUSAHA" => $czalamatpengusaha, "NOMOR_IJIN_TPB" => $czizinpengusaha , 

			"KODE_ID_PENERIMA_BARANG" => "1", "ID_PENERIMA_BARANG" => $czidpenerima,
			"NAMA_PENERIMA_BARANG" => $cznmpenerima , "ALAMAT_PENERIMA_BARANG" => $czalamatpenerima,
			"NOMOR_IJIN_TPB_PENERIMA" => $czizinpenerima,		
			
			"KODE_VALUTA" => $czcurrency, "CIF" => round($cz_h_CIF_FG,2), "HARGA_PENYERAHAN" => $cz_h_HARGA_PENYERAHAN_FG,

			"NAMA_PENGANGKUT" => $cznamapengangkut, "NOMOR_POLISI" => $cznomorpolisi,

			"BRUTO" => $cz_h_BRUTO , "NETTO" => $cz_h_NETTO, "JUMLAH_BARANG" => $cz_h_JUMLAH_BARANG,

			"KOTA_TTD" => "CIKARANG", "TANGGAL_TTD" =>$ccustdate, 

			"KODE_DOKUMEN_PABEAN" => $czdocbctype, "ID_MODUL" => $czidmodul_asli, "VERSI_MODUL" => NULL,

			"VOLUME" => 0, "KODE_STATUS" => '00'
		];
		$tpb_kemasan = [];
		$tpb_kemasan[] = ["JUMLAH_KEMASAN" =>$cz_JUMLAH_KEMASAN ,"KODE_JENIS_KEMASAN" => "BX"];

		$tpb_dokumen = [];
		$tpb_dokumen[] = ["KODE_JENIS_DOKUMEN" => "380", "NOMOR_DOKUMEN" => $czinvoice , "TANGGAL_DOKUMEN" => $czinvoicedt, "TIPE_DOKUMEN" => "02", "SERI_DOKUMEN" => 1]; //invoice		
		$tpb_dokumen[] = ["KODE_JENIS_DOKUMEN" => "217", "NOMOR_DOKUMEN" => $czinvoice , "TANGGAL_DOKUMEN" => $czinvoicedt, "TIPE_DOKUMEN" => "02","SERI_DOKUMEN" => 2]; //packing list
		$tpb_dokumen[] = ["KODE_JENIS_DOKUMEN" => "640", "NOMOR_DOKUMEN" => $czsj ,  "TANGGAL_DOKUMEN" =>  $ccustdate , "TIPE_DOKUMEN" => "02", "SERI_DOKUMEN" => 3 ]; //surat jalan				
		if(strlen($czConaNo)>3) {
			$tpb_dokumen[] = ["KODE_JENIS_DOKUMEN" => "315", "NOMOR_DOKUMEN" => $czConaNo ,  "TANGGAL_DOKUMEN" =>  $czConaDate , "TIPE_DOKUMEN" => "02", "SERI_DOKUMEN" => 4 ]; //kontrak
		}

		log_message('error', $_SERVER['REMOTE_ADDR'].',step4#, start, INSERT TPB');
		#INSERT CEISA
		##1 TPB HEADER
		$ZR_TPB_HEADER = $this->TPB_HEADER_imod->insert($tpb_header);		
		##N

		##2 TPB DOKUMEN
		foreach($tpb_dokumen as &$n){
			$n['ID_HEADER'] = $ZR_TPB_HEADER;
		}
		unset($n);
		$ZR_TPB_DOKUMEN = $this->TPB_DOKUMEN_imod->insertb($tpb_dokumen);
		##N
		
		##3 TPB KEMASAN
		foreach($tpb_kemasan as &$n){
			$n['ID_HEADER'] = $ZR_TPB_HEADER;
		}
		unset($n);
		$ZR_TPB_KEMASAN = $this->TPB_KEMASAN_imod->insertb($tpb_kemasan);
		##N

		##4 TPB BARANG
		foreach($tpb_barang as &$n){
			$n['ID_HEADER'] = $ZR_TPB_HEADER;
			foreach($tpb_bahan_baku as $j){
				if($n['KODE_BARANG']==$j['RASSYCODE'] && $n['CIF'] == $j['RPRICEGROUP']){
					if(!isset($n['JUMLAH_BAHAN_BAKU'])){
						$n['JUMLAH_BAHAN_BAKU']=1;
					} else {
						$n['JUMLAH_BAHAN_BAKU']++;
					}
				}
			}
		}
		unset($n);
		
		##N

		##4 TPB BARANG & BAHAN BAKU
		foreach($tpb_barang as $n){
			$ZR_TPB_BARANG = $this->TPB_BARANG_imod->insert($n);			
			foreach($tpb_bahan_baku as $b){
				if($n['KODE_BARANG']==$b['RASSYCODE'] && $n['CIF'] == $b['RPRICEGROUP']){
					$ZR_TPB_BAHAN_BAKU = $this->TPB_BAHAN_BAKU_imod
						->insert([
							'KODE_JENIS_DOK_ASAL' => $b['KODE_JENIS_DOK_ASAL']
							,'NOMOR_DAFTAR_DOK_ASAL' => $b['NOMOR_DAFTAR_DOK_ASAL']
							,'TANGGAL_DAFTAR_DOK_ASAL' => $b['TANGGAL_DAFTAR_DOK_ASAL']
							,'KODE_KANTOR' => $b['KODE_KANTOR']
							,'NOMOR_AJU_DOK_ASAL' => $b['NOMOR_AJU_DOK_ASAL']
							,'SERI_BARANG_DOK_ASAL' => $b['SERI_BARANG_DOK_ASAL']
							,'SPESIFIKASI_LAIN' => $b['SPESIFIKASI_LAIN']

							,'CIF' => ($b['CIF'])
							,'HARGA_PENYERAHAN' => $b['HARGA_PENYERAHAN']
							
							,'KODE_BARANG' => $b['KODE_BARANG']
							,'KODE_STATUS' => $b['KODE_STATUS']
							,'POS_TARIF' => $b['POS_TARIF']
							,'URAIAN' => $b['URAIAN']
							
							,'JUMLAH_SATUAN' => $b['JUMLAH_SATUAN']
							,'JENIS_SATUAN' => $b['JENIS_SATUAN']
							
							,'KODE_ASAL_BAHAN_BAKU' => $b['KODE_ASAL_BAHAN_BAKU']
							
							,'NDPBM' => 0
							,'NETTO' => 0
							,'SERI_BAHAN_BAKU' => $b['SERI_BAHAN_BAKU']
							,'SERI_BARANG' => $n['SERI_BARANG']
							,'ID_BARANG' => $ZR_TPB_BARANG
							,'ID_HEADER' => $ZR_TPB_HEADER
						]);
					
					foreach($tpb_bahan_baku_tarif as $t){
						if($b['RASSYCODE'] == $t['RASSYCODE'] && $b['RPRICEGROUP'] == $t['RPRICEGROUP'] 
						&& $b['KODE_BARANG'] == $t['RITEMCD'] && $b['SERI_BAHAN_BAKU'] == $t['SERI_BAHAN_BAKU']){
							$ZR_TPB_BAHAN_BAKU_TARIF = $this->TPB_BAHAN_BAKU_TARIF_imod
							->insert([
								'JENIS_TARIF' => $t['JENIS_TARIF']
								,'KODE_TARIF' => $t['KODE_TARIF']
								,'NILAI_BAYAR' => $t['NILAI_BAYAR']
								,'NILAI_FASILITAS' => $t['NILAI_FASILITAS']
								,'KODE_FASILITAS' => $t['KODE_FASILITAS']
								,'TARIF_FASILITAS' => $t['TARIF_FASILITAS']
								,'TARIF' =>  $t['TARIF']
								,'SERI_BAHAN_BAKU' =>  $t['SERI_BAHAN_BAKU']
								,'ID_BAHAN_BAKU' => $ZR_TPB_BAHAN_BAKU
								,'ID_BARANG' => $ZR_TPB_BARANG
								,'ID_HEADER' => $ZR_TPB_HEADER
							]);
						}
					}					
				}
				
			}
		}
		##N
		log_message('error', $_SERVER['REMOTE_ADDR'].',step4#, finish, INSERT TPB ');
		$this->DELV_mod->updatebyVAR(['DLV_POST' => $this->session->userdata('nama'), 'DLV_POSTTM' => $currentDate],['DLV_ID' => $csj]);
		$myar[] = ['cd' => '1' ,'msg' => 'Done, check your TPB' ];
		$this->setPrice(base64_encode($csj));
		$this->gotoque($csj);
		die('{"status" : '.json_encode($myar).'}');
	}

	public function posting41_initdata() {
		set_exception_handler([$this,'exception_handler']);
		set_error_handler([$this, 'log_error']);
		register_shutdown_function([$this, 'fatal_handler']);

		header('Content-Type: application/json');
		ini_set('max_execution_time', '-1');
		date_default_timezone_set('Asia/Jakarta');
		$currentDate = date('Y-m-d H:i:s');	
		$csj = $this->input->get('insj');
		$czsj = $csj;
		$rs_head_dlv = $this->DELV_mod->select_header_bydo($csj);
		$rs_rm_null = $this->DELV_mod->select_rm_null($csj);
		if(count($rs_rm_null)>0){			
			#check com job
			$ser_com_calcualted = [];
			foreach($rs_rm_null as $r){
				$rs_def = $this->SERC_mod->select_cols_where_id(['SERC_COMID','SERC_COMJOB','SERC_COMQTY'], $r['DLV_SER']); #detail combined ser
				foreach($rs_def as $k){
					$countrm_ = $this->SERD_mod->select_perlabel_resume_item($k['SERC_COMID']); #detail combined ser -> rm count
					if($countrm_ == 0 ){
						$ser_com_calcualted[] = ['NEWID' => $r['DLV_SER'], 'COMID' => $k['SERC_COMID'], 'RM' => $countrm_ ];
					}
				}
			}

			#filter rs_rm_null 
			$rs_filtered = [];
			foreach($rs_rm_null as $r){
				foreach($ser_com_calcualted as $n){
					if($r['DLV_SER'] == $n['NEWID']){
						$isfound = false;
						foreach($rs_filtered as $n){
							if($n['DLV_SER'] == $r['DLV_SER']){
								$isfound=true;break;
							}
						}
						if(!$isfound){
							$rs_filtered[] = $r;
						}
					}
				}
			}
			if(count($rs_filtered)>0){
				$myar[] = ['cd' => 100 ,'msg' => 'RM is null, please check again data in the table below'];
				die('{"status" : '.json_encode($myar).', "data": '.json_encode($rs_filtered).'}'); #$rs_rm_null
			} else {
				#go ahead
			}
		}
		$rsaktivasi = $this->AKTIVASIAPLIKASI_imod->selectAll();
		$rs_rm_null = $this->DELV_mod->select_rm_null($csj);
		if(count($rs_rm_null)>0){
			$myar[] = ['cd' => 100 ,'msg' => 'RM is null, please check again data in the table below'];
			die('{"status" : '.json_encode($myar).', "data": '.json_encode($rs_rm_null).'}');
		}
		$ccustdate ='';
		$nomoraju = '';	
		$czcurrency ='';
		$czdocbctype ='-';
		$cz_KODE_JENIS_TPB ='';
		$czinvoice = '';
		$czkantorasal = $czidmodul = $czidmodul_asli  ='' ;
		$consignee = '-';
		$cznmpenerima = '';
		$czalamatpenerima = '';		
		$cznomorpolisi = '-';
		$cznamapengangkut = '';	
		$czinvoicedt = '';
		$czidpenerima = '';
		$cztujuanpengiriman = '-';
		$czConaNo = '';
		$czConaDate = '';
		foreach($rs_head_dlv as $r){
			if($r['DLV_BCDATE']){
				$consignee = $r['DLV_CONSIGN'];
				$czdocbctype = $r['DLV_BCTYPE'];
				$ccustdate = $r['DLV_BCDATE'];
				$nomoraju = $r['DLV_NOAJU'];				
				$czinvoice = trim($r['DLV_INVNO']);				
				$czcurrency = trim($r['MCUS_CURCD']);
				$cz_KODE_JENIS_TPB = $r['DLV_ZJENIS_TPB_ASAL'];				
				$cznmpenerima = $r['MDEL_ZNAMA'];
				$czalamatpenerima = $r['MDEL_ADDRCUSTOMS'];
				$cznomorpolisi = $r['DLV_TRANS'];
				$cznamapengangkut = $r['MSTTRANS_TYPE'];
				$czinvoicedt = $r['DLV_INVDT'];
				$czidpenerima = str_replace(".","",$r['MCUS_TAXREG']);
				$czidpenerima = str_replace("-","", $czidpenerima);
				$cztujuanpengiriman = $r['DLV_PURPOSE'];
				$czConaNo = $r['DLV_CONA'];
				$czConaDate = $r['MCONA_DATE'];
				break;
			}
		}
		
		if($cztujuanpengiriman=='-'){
			$myar[] = ['cd' => 0 ,'msg' => 'please set TUJUAN PENGIRIMAN ('.$cztujuanpengiriman.')'];
			die('{"status" : '.json_encode($myar).'}');
		}

		if($consignee=='-'){
			$myar[] = ['cd' => 0 ,'msg' => 'please set consignment ('.$consignee.')'];
			die('{"status" : '.json_encode($myar).'}');
		} 

		if($czinvoice==''){
			$myar[] = ['cd' => 0 ,'msg' => 'please add invoice number ('.$czinvoice.') custdate ('.$ccustdate.') consign ('.$consignee.')'];
			die('{"status" : '.json_encode($myar).'}');
		}
		$ocustdate = date_create($ccustdate);
		$cz_ymd = date_format($ocustdate, 'Ymd');
		
		$czidpengusaha = '';
		$cznmpengusaha = '';
		$czalamatpengusaha = '';
		$czizinpengusaha = '';

		foreach($rsaktivasi as $r){
			$czkantorasal = $r['KPPBC'];
			$czidmodul = substr('00000'.$r['ID_MODUL'],-6);
			$czidmodul_asli = $r['ID_MODUL'];
			$czidpengusaha = $r['NPWP'];
			$cznmpengusaha = $r['NAMA_PENGUSAHA'];
			$czalamatpengusaha = $r['ALAMAT_PENGUSAHA'];
			$czizinpengusaha = $r['NOMOR_SKEP'];		
		}

		if($czdocbctype=='-'){
			$myar[] = ['cd' => 0 ,'msg' => 'please select bc type!'];
			die('{"status" : '.json_encode($myar).'}');
		}

		if(strlen($nomoraju)!=6){
			$myar[] = ['cd' => 0 ,'msg' => 'NOMOR AJU is not valid, please re-check ('.$nomoraju.')'];
			die('{"status" : '.json_encode($myar).'}');
		}
		
		$cnoaju = substr($czkantorasal,0,4).$czdocbctype.$czidmodul.$cz_ymd.$nomoraju;

		if($this->TPB_HEADER_imod->check_Primary(['NOMOR_AJU' => $cnoaju])>0){
			$myar[] = ['cd' => 0 ,'msg' => 'the NOMOR AJU is already posted'];
			die('{"status" : '.json_encode($myar).'}');
		}
		
		if(strlen($nomoraju)!=6){
			$myar[] = ['cd' => 0 ,'msg' => 'please enter valid Nomor Pengajuan!'];
			die('{"status" : '.json_encode($myar).'}');
		}
		
		$rsdlv = $this->DELV_mod->select_det_byid_p($csj);
		$cz_JUMLAH_KEMASAN = count($rsdlv);
		
		$myar =[];
		
		if(strlen($ccustdate)!=10){
			$myar[] = ['cd' => 0 ,'msg' => 'please enter valid customs date!'];
			die('{"status" : '.json_encode($myar).'}');
		}

		if($this->DELV_mod->check_Primary(['DLV_ID' => $csj])==0){
			$myar[] = ['cd' => 0 ,'msg' => 'DO is not found'];
			die('{"status" : '.json_encode($myar).'}');
		}	

		$rscurr = $this->MEXRATE_mod->selectfor_posting($ccustdate,$czcurrency);
		if(count($rscurr)==0){			
			$dar = ["cd" => "0", "msg" => "Please fill exchange rate data !"];
			$myar[] = $dar;
			die('{"status":'.json_encode($myar).'}');
		} else{
			foreach($rscurr as  $r){
				$czharga_matauang = $r->MEXRATE_VAL;break;				
			}
		}

		#HEADER_HARGA	
		log_message('error', $_SERVER['REMOTE_ADDR'].', step0#, DO:'.$csj);
		log_message('error', $_SERVER['REMOTE_ADDR'].', step1#, start, group by assy code , price, item');	
		$cz_h_HARGA_PENYERAHAN_FG = 0;
		$rsitem_p_price = $this->DELV_mod->select_item_per_price($csj);
		$rsplotrm_per_fgprice = $this->perprice($csj, $rsitem_p_price);
		$cz_h_JUMLAH_BARANG = count($rsitem_p_price);
		$cz_h_NETTO = 0;
		$cz_h_BRUTO = 0;
		$tpb_barang = [];
		$SERI_BARANG = 1;
		foreach($rsitem_p_price as $r){
			$t_HARGA_PENYERAHAN = $r['CIF'];			
			$cz_h_NETTO += $r['NWG'];
			$cz_h_BRUTO += $r['GWG'];
			$cz_h_HARGA_PENYERAHAN_FG += $t_HARGA_PENYERAHAN;
			$tpb_barang[] = [
				'KODE_BARANG' => $r['SSO2_MDLCD']
				,'POS_TARIF' => $r['MITM_HSCD']
				,'URAIAN' => $r['MITM_ITMD1']
				,'JUMLAH_SATUAN' => $r['SISOQTY']
				,'KODE_SATUAN' => $r['MITM_STKUOM']=='PCS' ? 'PCE' : $r['MITM_STKUOM']
				,'NETTO' => $r['NWG']				
				,'HARGA_PENYERAHAN' => round($r['CIF'],2)
				,'SERI_BARANG' => $SERI_BARANG
				,'KODE_STATUS' => '02'
			];
			$SERI_BARANG++;
		}
		$tpb_bahan_baku = [];
		$requestResume = [];
		
			
		$requestGroup = [];
		$requestGroup_send = [];
		log_message('error', $_SERVER['REMOTE_ADDR'].',step2#, start, posting group by assy code , price, item');
		foreach($rsplotrm_per_fgprice as $r){								
			$isfound = false;
			foreach($requestResume as &$n){
				if($n['ITEM'] == $r['RITEMCD']){
					$n['QTY'] += $r['RQTY'];
					$isfound = true;
				}
			}
			unset($n);
			if(!$isfound){
				$requestResume[] = ['ITEM' => $r['RITEMCD'], 'QTY' => $r['RQTY']];
			}

			$isfound = false;
			foreach($requestGroup as &$n){
				if($n['XASSY'] == $r['RASSYCODE'] && $n['XPRICE'] == $r['RPRICEGROUP']){						
					$isfound = true;break;
				}
			}
			unset($n);
			if(!$isfound){
				$requestGroup[] = ['XASSY' => $r['RASSYCODE'], 'XPRICE' => $r['RPRICEGROUP']];
			}
		}
		$xno = 0;
		foreach($requestGroup as $k){ 
			$ary_item = [];
			$ary_qty = [];
			$ary_lot = [];
			foreach($rsplotrm_per_fgprice as $r){
				if($k['XASSY'] == $r['RASSYCODE'] && $k['XPRICE'] == $r['RPRICEGROUP']){
					$ary_item[] = $r['RITEMCD'];
					$ary_qty[] = $r['RQTY'];
					$ary_lot[] = $r['RLOTNO'];
					$requestGroup_send[] = [
						'xno' => $xno
						, 'dataitem' => $r['RITEMCD']
						, 'dataitem_qty' => $r['RQTY']
						, 'dataitem_lot' => $r['RLOTNO']
					];
				}
			}			
			$xno++;
		}
		die(json_encode(['data' => $rsplotrm_per_fgprice
				,'cona' => $czConaNo 
				,'bctype' => $czdocbctype
				,'tujuankirim' => $cztujuanpengiriman
				,'datasend' => $requestGroup_send]
		));
	}

	public function posting41(){
		set_exception_handler([$this,'exception_handler']);
		set_error_handler([$this, 'log_error']);
		register_shutdown_function([$this, 'fatal_handler']);

		header('Content-Type: application/json');
		ini_set('max_execution_time', '-1');
		date_default_timezone_set('Asia/Jakarta');
		$currentDate = date('Y-m-d H:i:s');	
		$csj = $this->input->get('insj');
		$czsj = $csj;
		$rs_head_dlv = $this->DELV_mod->select_header_bydo($csj);
		$rs_rm_null = $this->DELV_mod->select_rm_null($csj);
		if(count($rs_rm_null)>0){			
			#check com job
			$ser_com_calcualted = [];
			foreach($rs_rm_null as $r){
				$rs_def = $this->SERC_mod->select_cols_where_id(['SERC_COMID','SERC_COMJOB','SERC_COMQTY'], $r['DLV_SER']); #detail combined ser
				foreach($rs_def as $k){
					$countrm_ = $this->SERD_mod->select_perlabel_resume_item($k['SERC_COMID']); #detail combined ser -> rm count
					if($countrm_ == 0 ){
						$ser_com_calcualted[] = ['NEWID' => $r['DLV_SER'], 'COMID' => $k['SERC_COMID'], 'RM' => $countrm_ ];
					}
				}
			}

			#filter rs_rm_null 
			$rs_filtered = [];
			foreach($rs_rm_null as $r){
				foreach($ser_com_calcualted as $n){
					if($r['DLV_SER'] == $n['NEWID']){
						$isfound = false;
						foreach($rs_filtered as $n){
							if($n['DLV_SER'] == $r['DLV_SER']){
								$isfound=true;break;
							}
						}
						if(!$isfound){
							$rs_filtered[] = $r;
						}
					}
				}
			}
			if(count($rs_filtered)>0){
				$myar[] = ['cd' => 100 ,'msg' => 'RM is null, please check again data in the table below'];
				die('{"status" : '.json_encode($myar).', "data": '.json_encode($rs_filtered).'}'); #$rs_rm_null
			} else {
				#go ahead
			}
		}
		$rsaktivasi = $this->AKTIVASIAPLIKASI_imod->selectAll();
		$rs_rm_null = $this->DELV_mod->select_rm_null($csj);
		if(count($rs_rm_null)>0){
			$myar[] = ['cd' => 100 ,'msg' => 'RM is null, please check again data in the table below'];
			die('{"status" : '.json_encode($myar).', "data": '.json_encode($rs_rm_null).'}');
		}
		$ccustdate ='';
		$nomoraju = '';	
		$czcurrency ='';
		$czdocbctype ='-';
		$cz_KODE_JENIS_TPB ='';
		$czinvoice = '';
		$czkantorasal = $czidmodul = $czidmodul_asli  ='' ;
		$consignee = '-';
		$cznmpenerima = '';
		$czalamatpenerima = '';		
		$cznomorpolisi = '-';
		$cznamapengangkut = '';	
		$czinvoicedt = '';
		$czidpenerima = '';
		$cztujuanpengiriman = '-';
		$czConaNo = '';
		$czConaDate = '';
		foreach($rs_head_dlv as $r){
			if($r['DLV_BCDATE']){
				$consignee = $r['DLV_CONSIGN'];
				$czdocbctype = $r['DLV_BCTYPE'];
				$ccustdate = $r['DLV_BCDATE'];
				$nomoraju = $r['DLV_NOAJU'];				
				$czinvoice = trim($r['DLV_INVNO']);				
				$czcurrency = trim($r['MCUS_CURCD']);
				$cz_KODE_JENIS_TPB = $r['DLV_ZJENIS_TPB_ASAL'];				
				$cznmpenerima = $r['MDEL_ZNAMA'];
				$czalamatpenerima = $r['MDEL_ADDRCUSTOMS'];
				$cznomorpolisi = $r['DLV_TRANS'];
				$cznamapengangkut = $r['MSTTRANS_TYPE'];
				$czinvoicedt = $r['DLV_INVDT'];
				$czidpenerima = str_replace(".","",$r['MCUS_TAXREG']);
				$czidpenerima = str_replace("-","", $czidpenerima);
				$cztujuanpengiriman = $r['DLV_PURPOSE'];
				$czConaNo = $r['DLV_CONA'];
				$czConaDate = $r['MCONA_DATE'];
				break;
			}
		}
		
		if($cztujuanpengiriman=='-'){
			$myar[] = ['cd' => 0 ,'msg' => 'please set TUJUAN PENGIRIMAN ('.$cztujuanpengiriman.')'];
			die('{"status" : '.json_encode($myar).'}');
		}

		if($consignee=='-'){
			$myar[] = ['cd' => 0 ,'msg' => 'please set consignment ('.$consignee.')'];
			die('{"status" : '.json_encode($myar).'}');
		} 

		if($czinvoice==''){
			$myar[] = ['cd' => 0 ,'msg' => 'please add invoice number ('.$czinvoice.') custdate ('.$ccustdate.') consign ('.$consignee.')'];
			die('{"status" : '.json_encode($myar).'}');
		}
		$ocustdate = date_create($ccustdate);
		$cz_ymd = date_format($ocustdate, 'Ymd');
		
		$czidpengusaha = '';
		$cznmpengusaha = '';
		$czalamatpengusaha = '';
		$czizinpengusaha = '';

		foreach($rsaktivasi as $r){
			$czkantorasal = $r['KPPBC'];
			$czidmodul = substr('00000'.$r['ID_MODUL'],-6);
			$czidmodul_asli = $r['ID_MODUL'];
			$czidpengusaha = $r['NPWP'];
			$cznmpengusaha = $r['NAMA_PENGUSAHA'];
			$czalamatpengusaha = $r['ALAMAT_PENGUSAHA'];
			$czizinpengusaha = $r['NOMOR_SKEP'];		
		}

		if($czdocbctype=='-'){
			$myar[] = ['cd' => 0 ,'msg' => 'please select bc type!'];
			die('{"status" : '.json_encode($myar).'}');
		}

		if(strlen($nomoraju)!=6){
			$myar[] = ['cd' => 0 ,'msg' => 'NOMOR AJU is not valid, please re-check ('.$nomoraju.')'];
			die('{"status" : '.json_encode($myar).'}');
		}
		
		$cnoaju = substr($czkantorasal,0,4).$czdocbctype.$czidmodul.$cz_ymd.$nomoraju;

		if($this->TPB_HEADER_imod->check_Primary(['NOMOR_AJU' => $cnoaju])>0){
			$myar[] = ['cd' => 0 ,'msg' => 'the NOMOR AJU is already posted'];
			die('{"status" : '.json_encode($myar).'}');
		}
		
		if(strlen($nomoraju)!=6){
			$myar[] = ['cd' => 0 ,'msg' => 'please enter valid Nomor Pengajuan!'];
			die('{"status" : '.json_encode($myar).'}');
		}
		
		$rsdlv = $this->DELV_mod->select_det_byid_p($csj);
		$cz_JUMLAH_KEMASAN = count($rsdlv);
		
		$myar =[];
		
		if(strlen($ccustdate)!=10){
			$myar[] = ['cd' => 0 ,'msg' => 'please enter valid customs date!'];
			die('{"status" : '.json_encode($myar).'}');
		}

		if($this->DELV_mod->check_Primary(['DLV_ID' => $csj])==0){
			$myar[] = ['cd' => 0 ,'msg' => 'DO is not found'];
			die('{"status" : '.json_encode($myar).'}');
		}	

		$rscurr = $this->MEXRATE_mod->selectfor_posting($ccustdate,$czcurrency);
		if(count($rscurr)==0){			
			$dar = ["cd" => "0", "msg" => "Please fill exchange rate data !"];
			$myar[] = $dar;
			die('{"status":'.json_encode($myar).'}');
		} else{
			foreach($rscurr as  $r){
				$czharga_matauang = $r->MEXRATE_VAL;break;				
			}
		}

		#HEADER_HARGA	
		log_message('error', $_SERVER['REMOTE_ADDR'].', step0#, DO:'.$csj);
		log_message('error', $_SERVER['REMOTE_ADDR'].', step1#, start, group by assy code , price, item');	
		$cz_h_HARGA_PENYERAHAN_FG = 0;
		$rsitem_p_price = $this->DELV_mod->select_item_per_price($csj);
		$rsplotrm_per_fgprice = $this->perprice($csj, $rsitem_p_price);
		$cz_h_JUMLAH_BARANG = count($rsitem_p_price);
		$cz_h_NETTO = 0;
		$cz_h_BRUTO = 0;
		$tpb_barang = [];
		$SERI_BARANG = 1;
		foreach($rsitem_p_price as $r){
			$t_HARGA_PENYERAHAN = $r['CIF'];			
			$cz_h_NETTO += $r['NWG'];
			$cz_h_BRUTO += $r['GWG'];
			$cz_h_HARGA_PENYERAHAN_FG += $t_HARGA_PENYERAHAN;
			$tpb_barang[] = [
				'KODE_BARANG' => $r['SSO2_MDLCD']
				,'POS_TARIF' => $r['MITM_HSCD']
				,'URAIAN' => $r['MITM_ITMD1']
				,'JUMLAH_SATUAN' => $r['SISOQTY']
				,'KODE_SATUAN' => $r['MITM_STKUOM']=='PCS' ? 'PCE' : $r['MITM_STKUOM']
				,'NETTO' => $r['NWG']				
				,'HARGA_PENYERAHAN' => round($r['CIF'],2)
				,'SERI_BARANG' => $SERI_BARANG
				,'KODE_STATUS' => '02'
			];
			$SERI_BARANG++;
		}
		#N
		log_message('error', $_SERVER['REMOTE_ADDR'].',step1#, finish, group by assy code , price, item');
		#BAHAN BAKU
		$tpb_bahan_baku = [];
		$requestResume = [];
		$responseResume = [];
		try {		
			$requestGroup = [];
			log_message('error', $_SERVER['REMOTE_ADDR'].',step2#, start, posting group by assy code , price, item');
			foreach($rsplotrm_per_fgprice as $r){								
				$isfound = false;
				foreach($requestResume as &$n){
					if($n['ITEM'] == $r['RITEMCD']){
						$n['QTY'] += $r['RQTY'];
						$isfound = true;
					}
				}
				unset($n);
				if(!$isfound){
					$requestResume[] = ['ITEM' => $r['RITEMCD'], 'QTY' => $r['RQTY']];
				}

				$isfound = false;
				foreach($requestGroup as &$n){
					if($n['XASSY'] == $r['RASSYCODE'] && $n['XPRICE'] == $r['RPRICEGROUP']){						
						$isfound = true;break;
					}
				}
				unset($n);
				if(!$isfound){
					$requestGroup[] = ['XASSY' => $r['RASSYCODE'], 'XPRICE' => $r['RPRICEGROUP']];
				}
			}
			log_message('error', $_SERVER['REMOTE_ADDR'].',step2#, finish, posting group by assy code , price, item');
			foreach($requestGroup as $k){ 
				$ary_item = [];
				$ary_qty = [];
				$ary_lot = [];
				foreach($rsplotrm_per_fgprice as $r){
					if($k['XASSY'] == $r['RASSYCODE'] && $k['XPRICE'] == $r['RPRICEGROUP']){
						$ary_item[] = $r['RITEMCD'];
						$ary_qty[] = $r['RQTY'];
						$ary_lot[] = $r['RLOTNO'];
					}
				}
				log_message('error', $_SERVER['REMOTE_ADDR'].',step3#, start, send request');
				$rstemp = $this->inventory_getstockbc_v2($czdocbctype,$cztujuanpengiriman,$csj, $ary_item, $ary_qty, $ary_lot, $ccustdate, $czConaNo);
				log_message('error', $_SERVER['REMOTE_ADDR'].',step3#, start, receive request');
				$rsbc = json_decode($rstemp);
				if(!is_null($rsbc)){
					if( count($rsbc)>0 ){
						foreach($rsbc as $o){
							foreach($o->data as $v){
								$isfound = false;
								foreach($responseResume as &$n){
									if($n['ITEM'] == $v->BC_ITEM){
										$n['QTY'] += $v->BC_QTY;
										$isfound = true;
									}
								}
								unset($n);
								if(!$isfound){
									$responseResume[] = ['ITEM' => $v->BC_ITEM, 'QTY' => $v->BC_QTY];
								}
								//THE ADDITIONAL INFO						
								if($v->RCV_KPPBC!='-'){
									$tpb_bahan_baku[] = [								
										'KODE_JENIS_DOK_ASAL' => $v->BC_TYPE
										,'NOMOR_DAFTAR_DOK_ASAL' => $v->BC_NUM
										,'TANGGAL_DAFTAR_DOK_ASAL' => $v->BC_DATE
										,'KODE_KANTOR' => $v->RCV_KPPBC
										,'NOMOR_AJU_DOK_ASAL' => $v->BC_AJU
										,'SERI_BARANG_DOK_ASAL' => $v->RCV_ZNOURUT
												
										,'HARGA_PENYERAHAN' => substr($v->RCV_PRPRC,0,1)=='.' ? ('0'.$v->RCV_PRPRC* $v->BC_QTY) : ($v->RCV_PRPRC * $v->BC_QTY)
				
										,'KODE_BARANG' => trim($v->BC_ITEM)
										,'KODE_STATUS' => "02"
										,'URAIAN' => $v->MITM_ITMD1
										
										,'JUMLAH_SATUAN' => $v->BC_QTY
										,'JENIS_SATUAN' => ($v->MITM_STKUOM=='PCS') ? 'PCE' : $v->MITM_STKUOM								
				
										,'KODE_ASAL_BAHAN_BAKU' => 1
										,'RASSYCODE' => $k['XASSY']
										,'RPRICEGROUP' => $k['XPRICE']
										,'RQTY' => $r['RQTY']				
										,'RBM' => substr($v->RCV_BM,0,1) == '.' ? ('0'.$v->RCV_BM) : ($v->RCV_BM)
										
									];
								} else {
									$tpb_bahan_baku[] = [
										'KODE_JENIS_DOK_ASAL' => $v->BC_TYPE
										,'NOMOR_DAFTAR_DOK_ASAL' => $v->BC_NUM
										,'TANGGAL_DAFTAR_DOK_ASAL' => $v->BC_DATE
										,'KODE_KANTOR' => NULL
										,'NOMOR_AJU_DOK_ASAL' => $v->BC_AJU
										,'SERI_BARANG_DOK_ASAL' => $v->RCV_ZNOURUT
													
										,'HARGA_PENYERAHAN' => 0
					
										,'KODE_BARANG' => $v->BC_ITEM 
										,'KODE_STATUS' => "02"								
										,'URAIAN' => $v->MITM_ITMD1
										
										,'JUMLAH_SATUAN' => $v->BC_QTY
										,'JENIS_SATUAN' => 'PCE'														
										
										,'KODE_ASAL_BAHAN_BAKU' => 1
										,'RASSYCODE' => $k['XASSY']
										,'RPRICEGROUP' => $k['XPRICE']					
										,'RQTY' => $r['RQTY']
										,'RBM' => 0										
									];
								}					
							}
						}
					} else {
						$this->inventory_cancelDO($csj);
						$myar[] = ["cd" => "0", "msg" => "Could not find exbc, please contact admin !"];
						die('{"status":'.json_encode($myar).'}');
					}
				} else {
					$this->inventory_cancelDO($csj);
					$myar[] = ["cd" => "0", "msg" => "Could not find exbc, please contact admin"];
					die(json_encode([
						'status' => $myar
						,'request_h' => ['doc_type' => $czdocbctype]
						,'request_d' => ['item' => $ary_item, 'qty' => $ary_qty, 'lot' => $ary_lot]
						, 'respon' => $rstemp
					])
					);
				}
				log_message('error', $_SERVER['REMOTE_ADDR'].',step3#, finish, receive request');
			}																	
			$listNeedExBC = [];
			foreach($requestResume as $r){
				$isfound = false;
				foreach($responseResume as $n){
					if($r['ITEM'] == $n['ITEM']){
						$isfound = true;
						if($r['QTY']!= $n['QTY']){
							$listNeedExBC[] = ['ITMCD' => $r['ITEM'], 'QTY' => $r['QTY']-$n['QTY'] , 'LOTNO' => '?'];
						}
					}
				}
				if(!$isfound){
					$listNeedExBC[] = ['ITMCD' => $r['ITEM'], 'QTY' => $r['QTY'], 'LOTNO' => '?'];
				}
			}
			if(count($listNeedExBC)>0){
				$this->inventory_cancelDO($csj);
				$myar[] = ['cd' => 110 ,'msg' => 'EX-BC for '.count($listNeedExBC). ' item(s) is not found. ', "doctype" => $czdocbctype, "tujuankirim" => $cztujuanpengiriman ];
				die('{"status" : '.json_encode($myar).', "data":'.json_encode($listNeedExBC).',"rawdata":'.json_encode($tpb_bahan_baku).'}');
			}
			#clear
			$ary_item = [];
			$ary_qty = [];
			$ary_lot = [];
			$requestResume = [];
			$responseResume = [];

			unset($ary_item);
			unset($ary_qty);
			unset($ary_lot);
			unset($requestResume);
			unset($responseResume);
			#end
		} catch (Exception $e) {
			$this->inventory_cancelDO($csj);
			$myar[] = ['cd' => 110 ,'msg' => $e->getMessage()];
			die('{"status" : '.json_encode($myar).',"data":"'.$rstemp.'"}');
		}
		foreach($rsplotrm_per_fgprice as $r){
			$nomor=1;
			foreach($tpb_bahan_baku as &$n){
				if($r['RASSYCODE'] == $n['RASSYCODE'] 
				&& $r['RPRICEGROUP']==$n['RPRICEGROUP'] ){
					$n['SERI_BAHAN_BAKU']=$nomor;
					$nomor++;
				}
			}
			unset($n);
		}
		#N			
		
		$tpb_header = [
			"NOMOR_AJU" => $cnoaju, "KODE_KANTOR" => $czkantorasal , 
			"KODE_JENIS_TPB" => $cz_KODE_JENIS_TPB ,  "KODE_TUJUAN_PENGIRIMAN" => $cztujuanpengiriman,
			
			"KODE_ID_PENGUSAHA" => "1", "ID_PENGUSAHA" => $czidpengusaha, "NAMA_PENGUSAHA" => $cznmpengusaha,
			"ALAMAT_PENGUSAHA" => $czalamatpengusaha, "NOMOR_IJIN_TPB" => $czizinpengusaha , 

			"KODE_ID_PENERIMA_BARANG" => "1", "ID_PENERIMA_BARANG" => $czidpenerima,
			"NAMA_PENERIMA_BARANG" => $cznmpenerima , "ALAMAT_PENERIMA_BARANG" => $czalamatpenerima,
						
			"HARGA_PENYERAHAN" => $cz_h_HARGA_PENYERAHAN_FG,

			"NAMA_PENGANGKUT" => $cznamapengangkut, "NOMOR_POLISI" => $cznomorpolisi,

			"BRUTO" => $cz_h_BRUTO , "NETTO" => $cz_h_NETTO, "JUMLAH_BARANG" => $cz_h_JUMLAH_BARANG,

			"KOTA_TTD" => "CIKARANG", "TANGGAL_TTD" =>$ccustdate, 

			"KODE_DOKUMEN_PABEAN" => $czdocbctype, "ID_MODUL" => $czidmodul_asli, "VERSI_MODUL" => NULL,

			"VOLUME" => 0, "KODE_STATUS" => '00'
		];
		$tpb_kemasan = [];
		$tpb_kemasan[] = ["JUMLAH_KEMASAN" =>$cz_JUMLAH_KEMASAN ,"KODE_JENIS_KEMASAN" => "BX"];	

		$tpb_dokumen = [];
		$tpb_dokumen[] = ["KODE_JENIS_DOKUMEN" => "380", "NOMOR_DOKUMEN" => $czinvoice , "TANGGAL_DOKUMEN" => $czinvoicedt, "TIPE_DOKUMEN" => "02", "SERI_DOKUMEN" => 1]; //invoice		
		$tpb_dokumen[] = ["KODE_JENIS_DOKUMEN" => "217", "NOMOR_DOKUMEN" => $czinvoice , "TANGGAL_DOKUMEN" => $czinvoicedt, "TIPE_DOKUMEN" => "02","SERI_DOKUMEN" => 2]; //packing list
		$tpb_dokumen[] = ["KODE_JENIS_DOKUMEN" => "640", "NOMOR_DOKUMEN" => $czsj ,  "TANGGAL_DOKUMEN" =>  $ccustdate , "TIPE_DOKUMEN" => "02", "SERI_DOKUMEN" => 3 ]; //surat jalan		
		$tpb_dokumen[] = ["KODE_JENIS_DOKUMEN" => "315", "NOMOR_DOKUMEN" => $czConaNo ,  "TANGGAL_DOKUMEN" =>  $czConaDate , "TIPE_DOKUMEN" => "02", "SERI_DOKUMEN" => 4 ]; //kontrak

		#exbc list
		$tpb_dokumen_40 = [];
		$tpb_dokumen_40_tgl = [];
		foreach($tpb_bahan_baku as $k){
			if(!in_array($k['NOMOR_DAFTAR_DOK_ASAL'], $tpb_dokumen_40)){
				$tpb_dokumen_40[] = $k['NOMOR_DAFTAR_DOK_ASAL'];
				$tpb_dokumen_40_tgl[] = $k['TANGGAL_DAFTAR_DOK_ASAL'];
			}
		}
		
		$noseri = 5;
		$cO_exBC = count($tpb_dokumen_40);
		for($i=0; $i<$cO_exBC; $i++){
			$tpb_dokumen[] = ["KODE_JENIS_DOKUMEN" => "40"
								,"NOMOR_DOKUMEN" => $tpb_dokumen_40[$i] 
								,"TANGGAL_DOKUMEN" =>  $tpb_dokumen_40_tgl[$i]
								,"TIPE_DOKUMEN" => "01"
								,"SERI_DOKUMEN" => $noseri
							];
			$noseri++;
		}
		log_message('error', $_SERVER['REMOTE_ADDR'].',step4#, start, INSERT TPB');
		#INSERT CEISA
		##1 TPB HEADER
		$ZR_TPB_HEADER = $this->TPB_HEADER_imod->insert($tpb_header);		
		##N

		##2 TPB DOKUMEN
		foreach($tpb_dokumen as &$n){
			$n['ID_HEADER'] = $ZR_TPB_HEADER;
		}
		unset($n);
		$this->TPB_DOKUMEN_imod->insertb($tpb_dokumen);
		##N
		
		##3 TPB KEMASAN
		foreach($tpb_kemasan as &$n){
			$n['ID_HEADER'] = $ZR_TPB_HEADER;
		}
		unset($n);
		$this->TPB_KEMASAN_imod->insertb($tpb_kemasan);
		##N

		##4 TPB BARANG
		foreach($tpb_barang as &$n){
			$n['ID_HEADER'] = $ZR_TPB_HEADER;
			foreach($tpb_bahan_baku as $j){
				if($n['KODE_BARANG']==$j['RASSYCODE'] && $n['HARGA_PENYERAHAN'] == $j['RPRICEGROUP']){
					if(!isset($n['JUMLAH_BAHAN_BAKU'])){
						$n['JUMLAH_BAHAN_BAKU']=1;
					} else {						
						$n['JUMLAH_BAHAN_BAKU']++;
					}
				}
			}
		}
		unset($n);
		
		##N

		##4 TPB BARANG & BAHAN BAKU
		foreach($tpb_barang as $n){
			$ZR_TPB_BARANG = $this->TPB_BARANG_imod->insert($n);			
			foreach($tpb_bahan_baku as $b){
				if($n['KODE_BARANG']==$b['RASSYCODE'] && $n['HARGA_PENYERAHAN'] == $b['RPRICEGROUP']){
					$this->TPB_BAHAN_BAKU_imod
						->insert([
							'KODE_JENIS_DOK_ASAL' => $b['KODE_JENIS_DOK_ASAL']
							,'NOMOR_DAFTAR_DOK_ASAL' => $b['NOMOR_DAFTAR_DOK_ASAL']
							,'TANGGAL_DAFTAR_DOK_ASAL' => $b['TANGGAL_DAFTAR_DOK_ASAL']
							,'KODE_KANTOR' => $b['KODE_KANTOR']
							,'NOMOR_AJU_DOK_ASAL' => $b['NOMOR_AJU_DOK_ASAL']
							,'SERI_BARANG_DOK_ASAL' => $b['SERI_BARANG_DOK_ASAL']
							
							,'HARGA_PENYERAHAN' => $b['HARGA_PENYERAHAN']
							
							,'KODE_BARANG' => $b['KODE_BARANG']
							,'KODE_STATUS' => $b['KODE_STATUS']							
							,'URAIAN' => $b['URAIAN']
							
							,'JUMLAH_SATUAN' => $b['JUMLAH_SATUAN']
							,'JENIS_SATUAN' => $b['JENIS_SATUAN']
														
							
							,'KODE_ASAL_BAHAN_BAKU'	=> $b['KODE_ASAL_BAHAN_BAKU']
							,'NETTO' => 0
							,'SERI_BAHAN_BAKU' => $b['SERI_BAHAN_BAKU']
							,'SERI_BARANG' => $n['SERI_BARANG']
							,'ID_BARANG' => $ZR_TPB_BARANG
							,'ID_HEADER' => $ZR_TPB_HEADER
						]);												
				}				
			}
		}
		log_message('error', $_SERVER['REMOTE_ADDR'].',step4#, finish, INSERT TPB ');
		##N
		$this->DELV_mod->updatebyVAR(['DLV_POST' => $this->session->userdata('nama'), 'DLV_POSTTM' => $currentDate],['DLV_ID' => $csj]);
		#N				
		$myar[] = ['cd' => 1 ,'msg' => 'Done, check your TPB' ];
		$this->setPrice(base64_encode($csj));
		$this->gotoque($csj);
		die('{"status" : '.json_encode($myar).'}');
	}

	public function posting25_initdata(){
		set_exception_handler([$this,'exception_handler']);
		set_error_handler([$this, 'log_error']);
		register_shutdown_function([$this, 'fatal_handler']);

		header('Content-Type: application/json');
		ini_set('max_execution_time', '-1');
		date_default_timezone_set('Asia/Jakarta');
		$currentDate = date('Y-m-d H:i:s');			
		$csj = $this->input->get('insj');
		$czsj = $csj;
		$rs_head_dlv = $this->DELV_mod->select_header_bydo($csj);
		$rs_rm_null = $this->DELV_mod->select_rm_null($csj);
		if(count($rs_rm_null)>0){			
			#check com job
			$ser_com_calcualted = [];
			foreach($rs_rm_null as $r){
				$rs_def = $this->SERC_mod->select_cols_where_id(['SERC_COMID','SERC_COMJOB','SERC_COMQTY'], $r['DLV_SER']); #detail combined ser
				foreach($rs_def as $k){
					$countrm_ = $this->SERD_mod->select_perlabel_resume_item($k['SERC_COMID']); #detail combined ser -> rm count
					if($countrm_ == 0 ){
						$ser_com_calcualted[] = ['NEWID' => $r['DLV_SER'], 'COMID' => $k['SERC_COMID'], 'RM' => $countrm_ ];
					}
				}
			}

			#filter rs_rm_null 
			$rs_filtered = [];
			foreach($rs_rm_null as $r){
				foreach($ser_com_calcualted as $n){
					if($r['DLV_SER'] == $n['NEWID']){
						$isfound = false;
						foreach($rs_filtered as $n){
							if($n['DLV_SER'] == $r['DLV_SER']){
								$isfound=true;break;
							}
						}
						if(!$isfound){
							$rs_filtered[] = $r;
						}
					}
				}
			}
			if(count($rs_filtered)>0){
				$myar[] = ['cd' => 100 ,'msg' => 'RM is null, please check again data in the table below'];
				die('{"status" : '.json_encode($myar).', "data": '.json_encode($rs_filtered).'}'); #$rs_rm_null
			} else {
				#go ahead
			}
		}
		$rsaktivasi = $this->AKTIVASIAPLIKASI_imod->selectAll();
		
		$ccustdate ='';
		$nomoraju = '';
		$ccustomer_do = '';
		$cbusiness_group = '';
		$czcurrency ='';
		$czdocbctype ='';
		$cz_KODE_JENIS_TPB ='';		
		$czinvoice = '';
		$czkantorasal = $czidmodul = $czidmodul_asli  ='' ;
		$consignee = '-';
		$cznmpenerima = '';
		$czalamatpenerima = '';
		$czKODE_CARA_ANGKUT = '';
		$czSKB = '';
		$czinvoicedt = '';
		$czidpenerima = '';
		$cz_h_NDPBM = 0;
		
		foreach($rs_head_dlv as $r){
			if($r['DLV_BCDATE']){
				$consignee = $r['DLV_CONSIGN'];
				$czdocbctype = $r['DLV_BCTYPE'];
				$ccustdate = $r['DLV_BCDATE'];
				$nomoraju = $r['DLV_NOAJU'];
				$ccustomer_do = $r['DLV_CUSTDO'];
				$czinvoice = trim($r['DLV_INVNO']);
				$cbusiness_group = $r['DLV_BSGRP'];
				$czcurrency = trim($r['MCUS_CURCD']);
				$cz_KODE_JENIS_TPB = $r['DLV_ZJENIS_TPB_ASAL'];				
				$cznmpenerima = $r['MDEL_ZNAMA'];
				$czalamatpenerima = $r['MDEL_ADDRCUSTOMS'];
				$czKODE_CARA_ANGKUT = $r['DLV_ZKODE_CARA_ANGKUT'];
				$czSKB = $r['DLV_ZSKB'];
				$czinvoicedt = $r['DLV_INVDT'];
				$czidpenerima = str_replace(".","",$r['MCUS_TAXREG']);
				$czidpenerima = str_replace("-","", $czidpenerima);
				break;
			}
		}
		
		if($consignee=='-'){
			$myar[] = ['cd' => 0 ,'msg' => 'please set consignment ('.$consignee.')'];
			die('{"status" : '.json_encode($myar).'}');
		} 

		if($czinvoice==''){
			$myar[] = ['cd' => 0 ,'msg' => 'please add invoice number ('.$czinvoice.') custdate ('.$ccustdate.') consign ('.$consignee.')'];
			die('{"status" : '.json_encode($myar).'}');
		}
		$ocustdate = date_create($ccustdate);
		$cz_ymd = date_format($ocustdate, 'Ymd');
		
		$czidpengusaha = '';
		$cznmpengusaha = '';
		$czalamatpengusaha = '';
		$czizinpengusaha = '';

		foreach($rsaktivasi as $r){
			$czkantorasal = $r['KPPBC'];
			$czidmodul = substr('00000'.$r['ID_MODUL'],-6);
			$czidmodul_asli = $r['ID_MODUL'];
			$czidpengusaha = $r['NPWP'];
			$cznmpengusaha = $r['NAMA_PENGUSAHA'];
			$czalamatpengusaha = $r['ALAMAT_PENGUSAHA'];
			$czizinpengusaha = $r['NOMOR_SKEP'];		
		}

		if($czdocbctype==''){
			$myar[] = ['cd' => 0 ,'msg' => 'please select bc type!'];
			die('{"status" : '.json_encode($myar).'}');
		}

		if(strlen($nomoraju)!=6){
			$myar[] = ['cd' => 0 ,'msg' => 'NOMOR AJU is not valid, please re-check ('.$nomoraju.')'];
			die('{"status" : '.json_encode($myar).'}');
		}
		
		$cnoaju = substr($czkantorasal,0,4).$czdocbctype.$czidmodul.$cz_ymd.$nomoraju;

		if($this->TPB_HEADER_imod->check_Primary(['NOMOR_AJU' => $cnoaju])>0){
			$myar[] = ['cd' => 0 ,'msg' => 'the NOMOR AJU is already posted'];
			die('{"status" : '.json_encode($myar).'}');
		}

		if(($cbusiness_group=='PSI2PPZOMC' || $cbusiness_group =='PSI2PPZOMI' ) ){			
			if(strlen($ccustomer_do)<5){
				$myar[] = ['cd' => 0 ,'msg' => 'please enter valid Customer DO!'];
				die('{"status" : '.json_encode($myar).'}');
			}
			$czsj = $ccustomer_do;
		}
		if(strlen($nomoraju)!=6){
			$myar[] = ['cd' => 0 ,'msg' => 'please enter valid Nomor Pengajuan!'];
			die('{"status" : '.json_encode($myar).'}');
		}						
		
		$rsdlv = $this->DELV_mod->select_det_byid_p($csj);
		$r_barang_kemasan = [];
		foreach($rsdlv as $r){
			$isfound = false;
			foreach($r_barang_kemasan as &$h){
				if($r['SER_ITMID']==$h['SER_ITMID']){
					$h['JUMLAH_KEMASAN']++;
					$isfound=true;
				}
			}
			unset($h);
			if(!$isfound){
				$r_barang_kemasan[] = ['SER_ITMID' => $r['SER_ITMID'] , 'JUMLAH_KEMASAN' => 1];
			}
		}
		$cz_JUMLAH_KEMASAN = count($rsdlv);				
		
		if(strlen($ccustdate)!=10){
			$myar[] = ['cd' => 0 ,'msg' => 'please enter valid customs date!'];
			die('{"status" : '.json_encode($myar).'}');
		}

		if($this->DELV_mod->check_Primary(['DLV_ID' => $csj])==0){
			$myar[] = ['cd' => 0 ,'msg' => 'DO is not found'];
			die('{"status" : '.json_encode($myar).'}');
		}
			
		$rscurr = $this->MEXRATE_mod->selectfor_posting($ccustdate,$czcurrency);
		if(count($rscurr)==0){			
			$dar = ["cd" => "0", "msg" => "Please fill exchange rate data !"];
			$myar[] = $dar;
			die('{"status":'.json_encode($myar).'}');
		} else{
			foreach($rscurr as  $r){
				if($czcurrency=='RPH'){
					$czharga_matauang = 1;
					$cz_h_NDPBM = $r->MEXRATE_VAL;
					break;
				} else {
					$czharga_matauang = $r->MEXRATE_VAL;break;
				}
			}
		}

		#HEADER_HARGA
		$cz_h_CIF_FG = 0;
		$cz_h_HARGA_PENYERAHAN_FG = 0;
		$rsitem_p_price = $this->DELV_mod->select_item_per_price($csj);
		$rsplotrm_per_fgprice = $this->perprice($csj, $rsitem_p_price);
		die('{"message": "done init data","data":'.json_encode($rsplotrm_per_fgprice).'}');
	}

	public function posting25(){
		set_exception_handler([$this,'exception_handler']);
		set_error_handler([$this, 'log_error']);
		register_shutdown_function([$this, 'fatal_handler']);

		header('Content-Type: application/json');
		ini_set('max_execution_time', '-1');
		date_default_timezone_set('Asia/Jakarta');
		$currentDate = date('Y-m-d H:i:s');
		$csj = $this->input->get('insj');
		$czsj = $csj;
		$rs_head_dlv = $this->DELV_mod->select_header_bydo($csj);
		$rs_rm_null = $this->DELV_mod->select_rm_null($csj);
		if(count($rs_rm_null)>0){			
			#check com job
			$ser_com_calcualted = [];
			foreach($rs_rm_null as $r){
				$rs_def = $this->SERC_mod->select_cols_where_id(['SERC_COMID','SERC_COMJOB','SERC_COMQTY'], $r['DLV_SER']); #detail combined ser
				foreach($rs_def as $k){
					$countrm_ = $this->SERD_mod->select_perlabel_resume_item($k['SERC_COMID']); #detail combined ser -> rm count
					if($countrm_ == 0 ){
						$ser_com_calcualted[] = ['NEWID' => $r['DLV_SER'], 'COMID' => $k['SERC_COMID'], 'RM' => $countrm_ ];
					}
				}
			}

			#filter rs_rm_null 
			$rs_filtered = [];
			foreach($rs_rm_null as $r){
				foreach($ser_com_calcualted as $n){
					if($r['DLV_SER'] == $n['NEWID']){
						$isfound = false;
						foreach($rs_filtered as $n){
							if($n['DLV_SER'] == $r['DLV_SER']){
								$isfound=true;break;
							}
						}
						if(!$isfound){
							$rs_filtered[] = $r;
						}
					}
				}
			}
			if(count($rs_filtered)>0){
				$myar[] = ['cd' => 100 ,'msg' => 'RM is null, please check again data in the table below'];
				die('{"status" : '.json_encode($myar).', "data": '.json_encode($rs_filtered).'}'); #$rs_rm_null
			} else {
				#go ahead
			}
		}
		$rsaktivasi = $this->AKTIVASIAPLIKASI_imod->selectAll();
		
		$ccustdate ='';
		$nomoraju = '';
		$ccustomer_do = '';
		$cbusiness_group = '';
		$czcurrency ='';
		$czdocbctype ='';
		$cz_KODE_JENIS_TPB ='';		
		$czinvoice = '';
		$czkantorasal = $czidmodul = $czidmodul_asli  ='' ;
		$consignee = '-';
		$cznmpenerima = '';
		$czalamatpenerima = '';
		$czKODE_CARA_ANGKUT = '';
		$czSKB = '';
		$czinvoicedt = '';
		$czidpenerima = '';
		$cz_h_NDPBM = 0;
		
		foreach($rs_head_dlv as $r){
			if($r['DLV_BCDATE']){
				$consignee = $r['DLV_CONSIGN'];
				$czdocbctype = $r['DLV_BCTYPE'];
				$ccustdate = $r['DLV_BCDATE'];
				$nomoraju = $r['DLV_NOAJU'];
				$ccustomer_do = $r['DLV_CUSTDO'];
				$czinvoice = trim($r['DLV_INVNO']);
				$cbusiness_group = $r['DLV_BSGRP'];
				$czcurrency = trim($r['MCUS_CURCD']);
				$cz_KODE_JENIS_TPB = $r['DLV_ZJENIS_TPB_ASAL'];				
				$cznmpenerima = $r['MDEL_ZNAMA'];
				$czalamatpenerima = $r['MDEL_ADDRCUSTOMS'];
				$czKODE_CARA_ANGKUT = $r['DLV_ZKODE_CARA_ANGKUT'];
				$czSKB = $r['DLV_ZSKB'];
				$czinvoicedt = $r['DLV_INVDT'];
				$czidpenerima = str_replace(".","",$r['MCUS_TAXREG']);
				$czidpenerima = str_replace("-","", $czidpenerima);
				break;
			}
		}
		
		if($consignee=='-'){
			$myar[] = ['cd' => 0 ,'msg' => 'please set consignment ('.$consignee.')'];
			die('{"status" : '.json_encode($myar).'}');
		} 

		if($czinvoice==''){
			$myar[] = ['cd' => 0 ,'msg' => 'please add invoice number ('.$czinvoice.') custdate ('.$ccustdate.') consign ('.$consignee.')'];
			die('{"status" : '.json_encode($myar).'}');
		}
		$ocustdate = date_create($ccustdate);
		$cz_ymd = date_format($ocustdate, 'Ymd');
		
		$czidpengusaha = '';
		$cznmpengusaha = '';
		$czalamatpengusaha = '';
		$czizinpengusaha = '';

		foreach($rsaktivasi as $r){
			$czkantorasal = $r['KPPBC'];
			$czidmodul = substr('00000'.$r['ID_MODUL'],-6);
			$czidmodul_asli = $r['ID_MODUL'];
			$czidpengusaha = $r['NPWP'];
			$cznmpengusaha = $r['NAMA_PENGUSAHA'];
			$czalamatpengusaha = $r['ALAMAT_PENGUSAHA'];
			$czizinpengusaha = $r['NOMOR_SKEP'];		
		}

		if($czdocbctype==''){
			$myar[] = ['cd' => 0 ,'msg' => 'please select bc type!'];
			die('{"status" : '.json_encode($myar).'}');
		}

		if(strlen($nomoraju)!=6){
			$myar[] = ['cd' => 0 ,'msg' => 'NOMOR AJU is not valid, please re-check ('.$nomoraju.')'];
			die('{"status" : '.json_encode($myar).'}');
		}
		
		$cnoaju = substr($czkantorasal,0,4).$czdocbctype.$czidmodul.$cz_ymd.$nomoraju;

		if($this->TPB_HEADER_imod->check_Primary(['NOMOR_AJU' => $cnoaju])>0){
			$myar[] = ['cd' => 0 ,'msg' => 'the NOMOR AJU is already posted'];
			die('{"status" : '.json_encode($myar).'}');
		}

		if(($cbusiness_group=='PSI2PPZOMC' || $cbusiness_group =='PSI2PPZOMI' ) ){			
			if(strlen($ccustomer_do)<5){
				$myar[] = ['cd' => 0 ,'msg' => 'please enter valid Customer DO!'];
				die('{"status" : '.json_encode($myar).'}');
			}
			$czsj = $ccustomer_do;
		}
		if(strlen($nomoraju)!=6){
			$myar[] = ['cd' => 0 ,'msg' => 'please enter valid Nomor Pengajuan!'];
			die('{"status" : '.json_encode($myar).'}');
		}						
		
		$rsdlv = $this->DELV_mod->select_det_byid_p($csj);
		$r_barang_kemasan = [];
		foreach($rsdlv as $r){
			$isfound = false;
			foreach($r_barang_kemasan as &$h){
				if($r['SER_ITMID']==$h['SER_ITMID']){
					$h['JUMLAH_KEMASAN']++;
					$isfound=true;
				}
			}
			unset($h);
			if(!$isfound){
				$r_barang_kemasan[] = ['SER_ITMID' => $r['SER_ITMID'] , 'JUMLAH_KEMASAN' => 1];
			}
		}
		$cz_JUMLAH_KEMASAN = count($rsdlv);				
		
		if(strlen($ccustdate)!=10){
			$myar[] = ['cd' => 0 ,'msg' => 'please enter valid customs date!'];
			die('{"status" : '.json_encode($myar).'}');
		}

		if($this->DELV_mod->check_Primary(['DLV_ID' => $csj])==0){
			$myar[] = ['cd' => 0 ,'msg' => 'DO is not found'];
			die('{"status" : '.json_encode($myar).'}');
		}
			
		$rscurr = $this->MEXRATE_mod->selectfor_posting($ccustdate,$czcurrency);
		if(count($rscurr)==0){			
			$dar = ["cd" => "0", "msg" => "Please fill exchange rate data !"];
			$myar[] = $dar;
			die('{"status":'.json_encode($myar).'}');
		} else{
			foreach($rscurr as  $r){
				if($czcurrency=='RPH'){
					$czharga_matauang = 1;
					$cz_h_NDPBM = $r->MEXRATE_VAL;
					break;
				} else {
					$czharga_matauang = $r->MEXRATE_VAL;break;
				}
			}
		}

		#HEADER_HARGA
		log_message('error', $_SERVER['REMOTE_ADDR'].', step0#, DO:'.$csj);
		log_message('error', $_SERVER['REMOTE_ADDR'].', step1#, start, group by assy code , price, item');
		$cz_h_CIF_FG = 0;
		$cz_h_HARGA_PENYERAHAN_FG = 0;
		$rsitem_p_price = $this->DELV_mod->select_item_per_price($csj);
		$rsplotrm_per_fgprice = $this->perprice($csj, $rsitem_p_price);
		$cz_h_JUMLAH_BARANG = count($rsitem_p_price);
		$cz_h_NETTO = 0;
		$cz_h_BRUTO = 0;
		$tpb_barang = [];
		$SERI_BARANG = 1;
		$cz_h_totalCIF = 0;
		foreach($rsitem_p_price as $r){
			$t_HARGA_PENYERAHAN = $r['CIF']*$czharga_matauang;
			$cz_h_CIF_FG += $r['CIF'];
			$cz_h_NETTO += $r['NWG'];
			$cz_h_BRUTO += $r['GWG'];
			$cz_h_HARGA_PENYERAHAN_FG += $t_HARGA_PENYERAHAN;
			$tpb_barang[] = [
				'KODE_GUNA' => '3'
				,'KATEGORI_BARANG' => '1'
				,'KONDISI_BARANG' => '1'
				,'KODE_BARANG' => $r['SSO2_MDLCD']
				,'KODE_NEGARA_ASAL' => 'ID'
				,'POS_TARIF' => $r['MITM_HSCD']
				,'URAIAN' => $r['MITM_ITMD1']
				,'JUMLAH_SATUAN' => $r['SISOQTY']
				,'KODE_SATUAN' => $r['MITM_STKUOM']=='PCS' ? 'PCE' : $r['MITM_STKUOM']
				,'NETTO' => $r['NWG']
				,'KODE_KEMASAN' => 'BX'
				,'CIF' => $r['CIF']
				,'HARGA_PENYERAHAN' => $t_HARGA_PENYERAHAN
				,'SERI_BARANG' => $SERI_BARANG
				,'KODE_STATUS' => '02'
				,'KODE_PERHITUNGAN' => '0'
			];
			$SERI_BARANG++;
		}
		log_message('error', $_SERVER['REMOTE_ADDR'].',step1#, finish, group by assy code , price, item');
		foreach($tpb_barang as &$r){
			foreach($r_barang_kemasan as $k){
				if($r['KODE_BARANG'] == $k['SER_ITMID']){
					$r['JUMLAH_KEMASAN'] = $k['JUMLAH_KEMASAN'];break;
				}
			}
		}
		unset($r);
		#N

		#BAHAN BAKU
		$tpb_bahan_baku = [];
		$requestResume = [];
		$responseResume = [];
		try {
			$requestGroup = [];
			log_message('error', $_SERVER['REMOTE_ADDR'].',step2#, start, posting group by assy code , price, item');
			foreach($rsplotrm_per_fgprice as $r){												
				$isfound = false;
				foreach($requestResume as &$n){
					if($n['ITEM'] == $r['RITEMCD']){
						$n['QTY'] += $r['RQTY'];						
						$isfound = true;
					}
				}
				unset($n);
				if(!$isfound){
					$requestResume[] = ['ITEM' => $r['RITEMCD'], 'QTY' => $r['RQTY'], 'ITEMGR' => $r['RITEMCDGR'] , 'QTYPLOT' => 0];
				}

				$isfound = false;
				foreach($requestGroup as &$n){
					if($n['XASSY'] == $r['RASSYCODE'] && $n['XPRICE'] == $r['RPRICEGROUP']){						
						$isfound = true;break;
					}
				}
				unset($n);
				if(!$isfound){
					$requestGroup[] = ['XASSY' => $r['RASSYCODE'], 'XPRICE' => $r['RPRICEGROUP']];
				}				
			}
			log_message('error', $_SERVER['REMOTE_ADDR'].',step2#, finish, posting group by assy code , price, item');
			foreach($requestGroup as $k){
				$ary_item = [];
				$ary_qty = [];
				$ary_lot = [];
				foreach($rsplotrm_per_fgprice as $r){
					if($k['XASSY'] == $r['RASSYCODE'] && $k['XPRICE'] == $r['RPRICEGROUP']){
						$ary_item[] = $r['RITEMCD'];
						$ary_qty[] = $r['RQTY'];
						$ary_lot[] = $r['RLOTNO'];
					}
				}
				log_message('error', $_SERVER['REMOTE_ADDR'].',step3#, start, send request');
				$rstemp = $this->inventory_getstockbc_v2($czdocbctype,"-",$csj, $ary_item, $ary_qty, $ary_lot,$ccustdate);
				log_message('error', $_SERVER['REMOTE_ADDR'].',step3#, start, receive request');
				$rsbc = json_decode($rstemp);
				$fields = [
					'bc' => $czdocbctype,					
					'date_out' => $ccustdate,
					'doc' => $csj,					
					'item_num' => $ary_item,
					'qty' => $ary_qty,
					'lot' => $ary_lot
				];			
				if(!is_null($rsbc)){					
					if( count($rsbc)>0 ){
						foreach($rsbc as $o){
							foreach($o->data as $v){
								$isfound = false;
								foreach($responseResume as &$n){
									if($n['ITEM'] == $v->BC_ITEM){
										$n['QTY'] += $v->BC_QTY;
										$n['BALRES'] += $v->BC_QTY;
										$isfound = true;
									}
								}
								unset($n);
								if(!$isfound){
									$responseResume[] = ['ITEM' => $v->BC_ITEM, 'QTY' => $v->BC_QTY, 'BALRES' => $v->BC_QTY];
								}
								//THE ADDITIONAL INFO						
								$tCIF = substr($v->RCV_PRPRC,0,1)=='.' ? ('0'.$v->RCV_PRPRC* $v->BC_QTY) : ($v->RCV_PRPRC * $v->BC_QTY);
								$cz_h_totalCIF+= $tCIF;
								if($v->RCV_KPPBC!='-'){
									$tpb_bahan_baku[] = [
										'KODE_JENIS_DOK_ASAL' => $v->BC_TYPE
										,'NOMOR_DAFTAR_DOK_ASAL' => $v->BC_NUM
										,'TANGGAL_DAFTAR_DOK_ASAL' => $v->BC_DATE
										,'KODE_KANTOR' => $v->RCV_KPPBC
										,'NOMOR_AJU_DOK_ASAL' => $v->BC_AJU
										,'SERI_BARANG_DOK_ASAL' => $v->RCV_ZNOURUT
				
										,'CIF' => $tCIF
										,'CIF_RUPIAH' => ($tCIF*$cz_h_NDPBM)
										,'NDPBM' => $cz_h_NDPBM
										,'HARGA_PENYERAHAN' => 0
				
										,'KODE_BARANG' => trim($v->BC_ITEM)
										,'KODE_STATUS' => "03"
										,'POS_TARIF' => $v->RCV_HSCD
										,'URAIAN' => $v->MITM_ITMD1
										
										,'JUMLAH_SATUAN' => $v->BC_QTY
										,'JENIS_SATUAN' => ($v->MITM_STKUOM=='PCS') ? 'PCE' : $v->MITM_STKUOM						
										,'KODE_ASAL_BAHAN_BAKU' => ($v->BC_TYPE == '27' || $v->BC_TYPE == '23' ) ? '0' : '1'
				
										,'RASSYCODE' => $k['XASSY']
										,'RPRICEGROUP' => $k['XPRICE']
										,'RBM' => substr($v->RCV_BM,0,1) == '.' ? ('0'.$v->RCV_BM) : ($v->RCV_BM)
										
									];
								} else {
									$tpb_bahan_baku[] = [
										'KODE_JENIS_DOK_ASAL' => $v->BC_TYPE
										,'NOMOR_DAFTAR_DOK_ASAL' => $v->BC_NUM
										,'TANGGAL_DAFTAR_DOK_ASAL' => $v->BC_DATE
										,'KODE_KANTOR' => NULL
										,'NOMOR_AJU_DOK_ASAL' => $v->BC_AJU
										,'SERI_BARANG_DOK_ASAL' => $v->RCV_ZNOURUT
										,'SPESIFIKASI_LAIN' => NULL
					
										,'CIF' => $tCIF
										,'CIF_RUPIAH' => ($tCIF*$cz_h_NDPBM)
										,'NDPBM' => $cz_h_NDPBM
										,'HARGA_PENYERAHAN' => 0
					
										,'KODE_BARANG' => $v->BC_ITEM
										,'KODE_STATUS' => "02"
										,'POS_TARIF' => $v->RCV_HSCD
										,'URAIAN' =>  $v->MITM_ITMD1
										
										,'JUMLAH_SATUAN' => $v->BC_QTY 
										,'JENIS_SATUAN' => 'PCE'						
										,'KODE_ASAL_BAHAN_BAKU' => 0
					
										,'RASSYCODE' => $k['XASSY']
										,'RPRICEGROUP' => $k['XPRICE']
										
										,'RBM' => 0										
									];
								}					
							}
						}
					} else {
						$this->inventory_cancelDO($csj);
						$myar[] = ["cd" => "0", "msg" => "Could not find exbc, please contact admin !"];
						die('{"status":'.json_encode($myar).'}');
					}
				} else {
					$this->inventory_cancelDO($csj);
					$myar[] = ["cd" => "0", "msg" => "Could not find exbc, please contact admin", "api_respon" => $rstemp, "request" => $fields];
					die('{"status":'.json_encode($myar).'}');
				}
				log_message('error', $_SERVER['REMOTE_ADDR'].',step3#, finish, receive request');
			}
			$listNeedExBC = [];
			foreach($requestResume as &$r){				
				foreach($responseResume as &$n){
					if($r['ITEM'] == $n['ITEM'] || $r['ITEMGR'] == $n['ITEM'] && $n['BALRES'] >0){
						$balreq = $r['QTY'] - $r['QTYPLOT'];
						if($balreq>$n['BALRES']){						
							$r['QTYPLOT'] +=  $n['BALRES'];
							$n['BALRES'] = 0;
						} else {
							$n['BALRES'] -= $balreq;
							$r['QTYPLOT'] += $balreq;
						}
						if($r['QTY']==$r['QTYPLOT']){
							break;
						}
					}
				}
				unset($n);
			}
			unset($r);
			foreach($requestResume as $r){ 
				if($r['QTY']!=$r['QTYPLOT']) {
					$listNeedExBC[] = ['ITMCD' => $r['ITEM'], 'QTY' => $r['QTY'], 'LOTNO' => '?'];
				}
			}

			if(count($listNeedExBC)){
				$this->inventory_cancelDO($csj);
				$myar[] = ['cd' => 110 ,'msg' => 'EX-BC for '.count($listNeedExBC). ' item(s) is not found. ', "doctype" => $czdocbctype];
				die('{"status" : '.json_encode($myar)
					.', "data":'.json_encode($listNeedExBC)
					.',"rawdata":'.json_encode($tpb_bahan_baku)
					.',"req":'.json_encode($requestResume)
					.',"res":'.json_encode($responseResume).'}');
			}
			#clear
			$ary_item = [];
			$ary_qty = [];
			$ary_lot = [];
			$requestResume = [];
			$responseResume = [];

			unset($ary_item);
			unset($ary_qty);
			unset($ary_lot);
			unset($requestResume);
			unset($responseResume);
			#end
		} catch (Exception $e) {
			$this->inventory_cancelDO($csj);
			$myar[] = ['cd' => 110 ,'msg' => $e->getMessage()];
			die('{"status" : '.json_encode($myar).',"data":"'.$rstemp.'"}');
		}
		foreach($rsplotrm_per_fgprice as $r){
			$nomor=1;
			foreach($tpb_bahan_baku as &$n){
				if($r['RASSYCODE'] == $n['RASSYCODE'] 
				&& $r['RPRICEGROUP']==$n['RPRICEGROUP'] ){
					$n['SERI_BAHAN_BAKU']=$nomor;
					$nomor++;
				}
			}
			unset($n);
		}
		#N


		#BAHAN BAKU DOKUMEN
		$tpb_bahan_baku_tarif = [];		
		foreach($tpb_bahan_baku as $r){			
			$tpb_bahan_baku_tarif[] = [
				'JENIS_TARIF' => 'BM'
				,'KODE_ASAL_BAHAN_BAKU' => $r['KODE_ASAL_BAHAN_BAKU']
				,'KODE_TARIF' => 1
				,'NILAI_BAYAR' => $r['CIF_RUPIAH']*$r['RBM']/100
				,'NILAI_FASILITAS' => 0
				,'KODE_FASILITAS' => 0
				,'TARIF_FASILITAS' => 100
				,'TARIF' => $r['RBM']
				,'SERI_BAHAN_BAKU' => $r['SERI_BAHAN_BAKU']


				,'RASSYCODE' => $r['RASSYCODE']
				,'RPRICEGROUP' => $r['RPRICEGROUP']						
				
				,'RITEMCD' => $r['KODE_BARANG']
			];
			$tpb_bahan_baku_tarif[] = [
				'JENIS_TARIF' => 'PPN'
				,'KODE_ASAL_BAHAN_BAKU' => $r['KODE_ASAL_BAHAN_BAKU']
				,'KODE_TARIF' => 1
				,'NILAI_BAYAR' => $r['CIF_RUPIAH']*10/100
				,'NILAI_FASILITAS' => 0
				,'KODE_FASILITAS' => 0
				,'TARIF_FASILITAS' => 100
				,'TARIF' => 10
				,'SERI_BAHAN_BAKU' => $r['SERI_BAHAN_BAKU']

				,'RASSYCODE' => $r['RASSYCODE']
				,'RPRICEGROUP' => $r['RPRICEGROUP']						
				
				,'RITEMCD' => $r['KODE_BARANG']
			];
			$tpb_bahan_baku_tarif[] = [
				'JENIS_TARIF' => 'PPH'
				,'KODE_ASAL_BAHAN_BAKU' => $r['KODE_ASAL_BAHAN_BAKU']
				,'KODE_TARIF' => 1
				,'NILAI_BAYAR' => $r['CIF_RUPIAH']*2.5
				,'NILAI_FASILITAS' => 0
				,'KODE_FASILITAS' => 0
				,'TARIF_FASILITAS' => 100
				,'TARIF' => 2.5
				,'SERI_BAHAN_BAKU' => $r['SERI_BAHAN_BAKU']

				,'RASSYCODE' => $r['RASSYCODE']
				,'RPRICEGROUP' => $r['RPRICEGROUP']	
				
				,'RITEMCD' => $r['KODE_BARANG']
			];			
		}
		#N
				
		$tpb_header = [
			"NOMOR_AJU" => $cnoaju, "KODE_KANTOR" => $czkantorasal , 
			"KODE_JENIS_TPB" => $cz_KODE_JENIS_TPB , 
			
			"KODE_ID_PENGUSAHA" => "1", "ID_PENGUSAHA" => $czidpengusaha, "NAMA_PENGUSAHA" => $cznmpengusaha,
			"ALAMAT_PENGUSAHA" => $czalamatpengusaha, "NOMOR_IJIN_TPB" => $czizinpengusaha , 
			"KODE_JENIS_API_PENGUSAHA" => 2, "API_PENGUSAHA" => "101600449-B",

			"KODE_ID_PENERIMA_BARANG" => "1", "ID_PENERIMA_BARANG" => $czidpenerima,
			"NAMA_PENERIMA_BARANG" => $cznmpenerima , "ALAMAT_PENERIMA_BARANG" => $czalamatpenerima,
			"NDPBM" => $cz_h_NDPBM,
			
			"KODE_VALUTA" => "USD" , "CIF" => $cz_h_totalCIF, "HARGA_PENYERAHAN" => $cz_h_HARGA_PENYERAHAN_FG,

			"KODE_CARA_ANGKUT" => $czKODE_CARA_ANGKUT,

			"BRUTO" => $cz_h_BRUTO , "NETTO" => $cz_h_NETTO, "JUMLAH_BARANG" => $cz_h_JUMLAH_BARANG,

			"KODE_LOKASI_BAYAR" => 1, "KODE_PEMBAYAR" => 1,

			"KOTA_TTD" => "CIKARANG", "TANGGAL_TTD" =>$ccustdate, 

			"KODE_DOKUMEN_PABEAN" => $czdocbctype, "ID_MODUL" => $czidmodul_asli, "VERSI_MODUL" => NULL,

			"VOLUME" => 0, "KODE_STATUS" => '00'
		];
		$tpb_kemasan = [];
		$tpb_kemasan[] = ["JUMLAH_KEMASAN" =>$cz_JUMLAH_KEMASAN ,"KODE_JENIS_KEMASAN" => "BX"];

		$tpb_dokumen = [];
		$tpb_dokumen[] = ["KODE_JENIS_DOKUMEN" => "380", "NOMOR_DOKUMEN" => $czinvoice , "TANGGAL_DOKUMEN" => $czinvoicedt, "TIPE_DOKUMEN" => "02", "SERI_DOKUMEN" => 1]; //invoice		
		$tpb_dokumen[] = ["KODE_JENIS_DOKUMEN" => "217", "NOMOR_DOKUMEN" => $czinvoice , "TANGGAL_DOKUMEN" => $czinvoicedt, "TIPE_DOKUMEN" => "02","SERI_DOKUMEN" => 2]; //packing list
		$tpb_dokumen[] = ["KODE_JENIS_DOKUMEN" => "640", "NOMOR_DOKUMEN" => $czsj ,  "TANGGAL_DOKUMEN" =>  $ccustdate , "TIPE_DOKUMEN" => "02", "SERI_DOKUMEN" => 3 ]; //surat jalan		
		$tpb_dokumen[] = ["KODE_JENIS_DOKUMEN" => "457", "NOMOR_DOKUMEN" => $czSKB ,  "TANGGAL_DOKUMEN" =>  $ccustdate , "TIPE_DOKUMEN" => "02", "SERI_DOKUMEN" => 4 ]; //surat keterangan bebas
				
		log_message('error', $_SERVER['REMOTE_ADDR'].',step4#, start, INSERT TPB');
		#INSERT CEISA
		##1 TPB HEADER
		$ZR_TPB_HEADER = $this->TPB_HEADER_imod->insert($tpb_header);		
		##N

		##2 TPB DOKUMEN
		foreach($tpb_dokumen as &$n){
			$n['ID_HEADER'] = $ZR_TPB_HEADER;
		}
		unset($n);
		$this->TPB_DOKUMEN_imod->insertb($tpb_dokumen);
		##N
		
		##3 TPB KEMASAN
		foreach($tpb_kemasan as &$n){
			$n['ID_HEADER'] = $ZR_TPB_HEADER;
		}
		unset($n);
		$this->TPB_KEMASAN_imod->insertb($tpb_kemasan);
		##N

		##4 TPB BARANG
		foreach($tpb_barang as &$n){
			$n['ID_HEADER'] = $ZR_TPB_HEADER;
			foreach($tpb_bahan_baku as $j){
				if($n['KODE_BARANG']==$j['RASSYCODE'] && $n['CIF'] == $j['RPRICEGROUP']){
					if(!isset($n['JUMLAH_BAHAN_BAKU'])){
						$n['JUMLAH_BAHAN_BAKU']=1;
					} else {						
						$n['JUMLAH_BAHAN_BAKU']++;
					}
				}
			}
		}
		unset($n);
		
		##N

		##4 TPB BARANG & BAHAN BAKU
		$tpb_barang_tarif = [];
		foreach($tpb_barang as $n){
			$ZR_TPB_BARANG = $this->TPB_BARANG_imod->insert($n);			
			$tpb_barang_tarif[] = [
				'JENIS_TARIF' => 'BM'
				,'KODE_FASILITAS' => 0
				,'KODE_TARIF' => 1
				,'NILAI_BAYAR' => 0
				,'NILAI_FASILITAS' => 0
				,'NILAI_SUDAH_DILUNASI' => 0
				,'SERI_BARANG' => $n['SERI_BARANG']
				,'TARIF' => 5
				,'TARIF_FASILITAS' => 100
				,'ID_BARANG' => $ZR_TPB_BARANG
				,'ID_HEADER' => $ZR_TPB_HEADER
			];
			$tpb_barang_tarif[] = [
				'JENIS_TARIF' => 'PPN'
				,'KODE_FASILITAS' => 0
				,'KODE_TARIF' => 1
				,'NILAI_BAYAR' => 0
				,'NILAI_FASILITAS' => 0
				,'NILAI_SUDAH_DILUNASI' => 0
				,'SERI_BARANG' => $n['SERI_BARANG']
				,'TARIF' => 10
				,'TARIF_FASILITAS' => 100
				,'ID_BARANG' => $ZR_TPB_BARANG
				,'ID_HEADER' => $ZR_TPB_HEADER
			];
			$tpb_barang_tarif[] = [
				'JENIS_TARIF' => 'PPH'
				,'KODE_FASILITAS' => 0
				,'KODE_TARIF' => 1
				,'NILAI_BAYAR' => 0
				,'NILAI_FASILITAS' => 0
				,'NILAI_SUDAH_DILUNASI' => 0
				,'SERI_BARANG' => $n['SERI_BARANG']
				,'TARIF' => 2.5
				,'TARIF_FASILITAS' => 100
				,'ID_BARANG' => $ZR_TPB_BARANG
				,'ID_HEADER' => $ZR_TPB_HEADER
			];
			$this->TPB_BARANG_TARIF_imod->insertb($tpb_barang_tarif);
			foreach($tpb_bahan_baku as $b){
				if($n['KODE_BARANG']==$b['RASSYCODE'] && $n['CIF'] == $b['RPRICEGROUP']){
					$ZR_TPB_BAHAN_BAKU = $this->TPB_BAHAN_BAKU_imod
						->insert([
							'KODE_JENIS_DOK_ASAL' => $b['KODE_JENIS_DOK_ASAL']
							,'NOMOR_DAFTAR_DOK_ASAL' => $b['NOMOR_DAFTAR_DOK_ASAL']
							,'TANGGAL_DAFTAR_DOK_ASAL' => $b['TANGGAL_DAFTAR_DOK_ASAL']
							,'KODE_KANTOR' => $b['KODE_KANTOR']
							,'NOMOR_AJU_DOK_ASAL' => $b['NOMOR_AJU_DOK_ASAL']
							,'SERI_BARANG_DOK_ASAL' => $b['SERI_BARANG_DOK_ASAL']

							,'CIF' => ($b['CIF'])
							,'CIF_RUPIAH' => ($b['CIF_RUPIAH'])
							,'HARGA_PENYERAHAN' => $b['HARGA_PENYERAHAN']
							
							,'KODE_BARANG' => $b['KODE_BARANG']
							,'KODE_STATUS' => $b['KODE_STATUS']
							,'POS_TARIF' => $b['POS_TARIF']
							,'URAIAN' => $b['URAIAN']
							
							,'JUMLAH_SATUAN' => $b['JUMLAH_SATUAN']
							,'JENIS_SATUAN' => $b['JENIS_SATUAN']
							
							,'KODE_ASAL_BAHAN_BAKU' => $b['KODE_ASAL_BAHAN_BAKU']
							
							,'NDPBM' => $b['NDPBM']
							,'NETTO' => 0
							,'SERI_BAHAN_BAKU' => $b['SERI_BAHAN_BAKU']							
							,'SERI_BARANG' => $n['SERI_BARANG']
							,'ID_BARANG' => $ZR_TPB_BARANG
							,'ID_HEADER' => $ZR_TPB_HEADER
						]);
					
					foreach($tpb_bahan_baku_tarif as $t){
						if($b['RASSYCODE'] == $t['RASSYCODE'] && $b['RPRICEGROUP'] == $t['RPRICEGROUP'] 
						&& $b['KODE_BARANG'] == $t['RITEMCD'] && $b['SERI_BAHAN_BAKU'] == $t['SERI_BAHAN_BAKU']){
							$this->TPB_BAHAN_BAKU_TARIF_imod
							->insert([
								'JENIS_TARIF' => $t['JENIS_TARIF']
								,'KODE_ASAL_BAHAN_BAKU' => $t['KODE_ASAL_BAHAN_BAKU']
								,'KODE_TARIF' => $t['KODE_TARIF']
								,'NILAI_BAYAR' => $t['NILAI_BAYAR']
								,'NILAI_FASILITAS' => $t['NILAI_FASILITAS']
								,'KODE_FASILITAS' => $t['KODE_FASILITAS']
								,'TARIF_FASILITAS' => $t['TARIF_FASILITAS']
								,'TARIF' =>  $t['TARIF']
								,'SERI_BAHAN_BAKU' =>  $t['SERI_BAHAN_BAKU']
								,'SERI_BARANG' =>  $n['SERI_BARANG']
								,'ID_BAHAN_BAKU' => $ZR_TPB_BAHAN_BAKU
								,'ID_BARANG' => $ZR_TPB_BARANG
								,'ID_HEADER' => $ZR_TPB_HEADER
							]);
						}
					}					
				}
				
			}
		}
		##N

		#N
		log_message('error', $_SERVER['REMOTE_ADDR'].',step4#, finish, INSERT TPB ');
		$this->DELV_mod->updatebyVAR(['DLV_POST' => $this->session->userdata('nama'), 'DLV_POSTTM' => $currentDate],['DLV_ID' => $csj]);
		$myar[] = ['cd' => 1 ,'msg' => 'Done, check your TPB' ];
		$this->setPrice(base64_encode($csj));
		$this->gotoque($csj);
		die('{"status" : '.json_encode($myar).'}');
	}

	public function control_multibom(){
		header('Content-Type: application/json');
		$DOno = $this->input->get('insj');
		$rs = $this->DELV_mod->select_dlv_ser_rm($DOno);
		$rsnull = $this->DELV_mod->select_dlv_ser_rm_null($DOno);
		$comb_cols = ['SERC_COMID'];
		$serlist = [];
		$rs_reconditional = [];
		foreach($rsnull as $r){
			$rscomb_d = $this->SERC_mod->select_cols_where_id($comb_cols, $r['DLV_SER']);
			$serlist = [];
			foreach($rscomb_d as $n){
				$serlist[] = $n['SERC_COMID'];
			}
			$rscom = $this->DELV_mod->select_dlv_ser_rm_byreff($serlist);
			foreach($rscom as $f){
				$rs_reconditional[] = $f;
			}
		}
		die('{"olddata": '.json_encode($rs).',"newdata":'.json_encode($rs_reconditional).'}');
	}

	public function searchv(){
		header('Content-Type: application/json');
		ini_set('max_execution_time', '-1');
		$csearch = $this->input->get('insearch');
		$cispermonth = $this->input->get('inispermonth');
		$cmonth = $this->input->get('inmonth');
		$cyear = $this->input->get('inyear');
		$cdate = $this->input->get('indate');
		$rs = [];
		if(strlen($cdate)==10){
			$rs = $this->DELV_mod->select_searchv_bydate($csearch, $cdate);
		} else {
			if($cispermonth=='y'){
				$rs = $this->DELV_mod->select_searchv_bymonth($csearch, $cmonth, $cyear);
			} else {
				$rs = $this->DELV_mod->select_searchv($csearch);
			}
		}
		foreach($rs as &$m){
			$m['STATUS'] = $this->getjobstatus_resume($m['DLV_ID']);
		}
		unset($m);
		die('{"data": '.json_encode($rs).'}');
	}
	
	public function perprice($psj, $prs){
		$sj = $psj;
		$rsprice = $prs;
		$rsrm = $this->DELV_mod->select_dlv_ser_rm_only($sj);
		$rssub = $this->DELV_mod->select_dlv_ser_sub_only($sj);
		$rsnull = $this->DELV_mod->select_dlv_ser_rm_null_v1($sj);
		foreach($rsnull as $r){			
			$rscomb_d = $this->SERC_mod->select_cols_where_id(['SERC_COMID'], $r['DLV_SER']);
			$serlist = [];
			if(count($rscomb_d)) {
				foreach($rscomb_d as $n){
					$serlist[] = $n['SERC_COMID'];
				}
				if(count($serlist)>0){
					$rscom = $this->DELV_mod->select_dlv_ser_rm_byreff_forpost($serlist);								
					$rsrm = array_merge($rsrm, $rscom);
				}
			} else {
				$rscomb_d = $this->SERC_mod->select_cols_where_id(['SERC_COMID'], $r['SER_REFNO']);
				foreach($rscomb_d as $n){
					$serlist[] = $n['SERC_COMID'];
				}
				if(count($serlist)>0){
					$rscom = $this->DELV_mod->select_dlv_ser_rm_byreff_forpost($serlist);								
					$rsrm = array_merge($rsrm, $rscom);
				}
			}
		}		
		$rsrm = array_merge($rsrm, $rssub);
		$result = [];
		foreach($rsprice as &$r){
			foreach($rsrm as &$ra){
				if($r['SSO2_MDLCD']==$ra['SER_ITMID'] ){
					if(intval($ra['SERD2_FGQTY'])>0){
						$thereffno = $ra['SERD2_SER'];
						$osreq = $r['SISOQTY'] - $r['SISOQTY_X'];
						$plot = 0;
						if($osreq>0){
							foreach($rsrm as &$x){
								if($thereffno==$x['SERD2_SER']){
									if($osreq>$x['SERD2_FGQTY']){
										$plot=$x['SERD2_FGQTY'];
										$x['SERD2_FGQTY']=0;										
									} else {
										$plot=$osreq;
										$x['SERD2_FGQTY']-=$osreq;
									}
									$x['PRICEFOR'] = $r['SSO2_SLPRC'];
									$x['QTYFOR'] = $plot;
									$x['PRICEGROUP'] = $r['SSO2_SLPRC']*$r['SISOQTY'];
									$result[] = $x;
								}
							}
							unset($x);
						}						
						$r['SISOQTY_X'] += $plot;
					}										
				}
			}
			unset($ra);
		}
		unset($r);	

		$result_resume = [];
		foreach($result as $r){
			$isfound =false;
			foreach($result_resume as &$v){
				if($v['RITEMCD'] == $r['SERD2_ITMCD'] && $v['RLOTNO'] == $r['LOTNO'] 
					&& $v['RASSYCODE'] == $r['SER_ITMID'] && $v['RPRICEGROUP'] == round($r['PRICEGROUP'],2)
					){
					$v['RQTY'] += ($r['SERD2_QTPER'] * $r['QTYFOR'] ); #((int)$r['SERD2_QTPER'] * $r['QTYFOR'] );
					$isfound =true;
					break;
				}
			}
			if(!$isfound){
				$result_resume[] = [
					'RASSYCODE' => $r['SER_ITMID']
					,'RPRICEGROUP' => round($r['PRICEGROUP'],2)
					,'RITEMCD' => $r['SERD2_ITMCD']
					,'RITEMCDGR' => $r['ITMGR']
					,'RLOTNO' => $r['LOTNO']
					,'RQTY' => $r['SERD2_QTPER'] * $r['QTYFOR'] #(int)$r['SERD2_QTPER'] * $r['QTYFOR']
				];
			}
			unset($v);
		}		
		return $result_resume;
		// die('{"data": '.json_encode($result)
		// 	// .',"head" :'.json_encode($rsprice)
		// 	.'}');
	}

	public function perprice_tes(){
		header('Content-Type: application/json');
		$sj  =  $this->input->get('insj');
		$rsprice = [];
		$rsprice[] = ['SSO2_MDLCD' => '05370748190A200', 'SISOQTY' => 5190, 'SISOQTY_X' => 0 ,'SSO2_SLPRC' => 0.90];
		$rsprice[] = ['SSO2_MDLCD' => '2266381-4', 'SISOQTY' => 20, 'SISOQTY_X' => 0 ,'SSO2_SLPRC' => 0.95];
		$rsrm = $this->DELV_mod->select_dlv_ser_rm($sj);
		$result = [];
		foreach($rsprice as &$r){
			foreach($rsrm as &$ra){
				if($r['SSO2_MDLCD']==$ra['SER_ITMID'] ){
					if(intval($ra['SERD2_FGQTY'])>0){
						$thereffno = $ra['SERD2_SER'];
						$osreq = $r['SISOQTY'] - $r['SISOQTY_X'];
						$plot = 0;
						if($osreq>0){
							foreach($rsrm as &$x){
								if($thereffno==$x['SERD2_SER']){
									if($osreq>$x['SERD2_FGQTY']){
										$plot=$x['SERD2_FGQTY'];
										$x['SERD2_FGQTY']=0;										
									} else {
										$plot=$osreq;
										$x['SERD2_FGQTY']-=$osreq;
									}
									$x['PRICEFOR']=$r['SSO2_SLPRC'];
									$x['QTYFOR']=$plot;
									$x['PRICEGROUP']=$r['SSO2_SLPRC']*$r['SISOQTY'];
									$result[] = $x;
								}
							}
							unset($x);
						}						
						$r['SISOQTY_X'] += $plot;
					}
				}				
			}
			unset($ra);
		}
		unset($r);

		$result_resume = [];
		foreach($result as $r){
			$isfound =false;
			foreach($result_resume as &$v){
				if($v['RITEMCD'] == $r['SERD2_ITMCD'] && $v['RLOTNO'] == $r['LOTNO'] 
					&& $v['RASSYCODE'] == $r['SER_ITMID'] && $v['RPRICEGROUP'] == round($r['PRICEGROUP'],3)
					){
					$v['RQTY'] += ((int)$r['SERD2_QTPER'] * $r['QTYFOR'] );
					$isfound =true;
					break;
				}
			}
			if(!$isfound){
				$result_resume[] = [
					'RASSYCODE' => $r['SER_ITMID']
					,'RPRICEGROUP' => round($r['PRICEGROUP'],3)
					,'RITEMCD' => $r['SERD2_ITMCD']
					,'RLOTNO' => $r['LOTNO']
					,'RQTY' => (int)$r['SERD2_QTPER'] * $r['QTYFOR']
				];
			}
			unset($v);
		}

		die('{"data": '.json_encode($result)			
			.',"data_resume" :'.json_encode($result_resume)
			.'}');
	}

	public function getadditional_infobc($bcno, $itemcd){
		$rs = $this->RCV_mod->select_cols_where(
			['RCV_KPPBC','RCV_ZNOURUT', 'RCV_HSCD', 'RTRIM(MITM_ITMD1) MITM_ITMD1','RTRIM(MITM_STKUOM) MITM_STKUOM','RCV_PRPRC', 'isnull(RCV_BM,0) RCV_BM']
			,['RCV_RPNO' => $bcno, 'RCV_ITMCD' => $itemcd]
		);
		if(count($rs) >0 ){
			foreach($rs as $r){
				return $r;
			}
		} else {
			return (object)['RCV_KPPBC' => '-'];
		}
	}

	public function get_info_pendaftaran(){
		header('Content-Type: application/json');
		$cinsj = $this->input->get('insj');
		$rs_head_dlv = $this->DELV_mod->select_header_bydo($cinsj);
		$czkantorasal ='-';
		$czdocbctype = '-';
		$czidmodul ='';
		$nomoraju = '';
		$myar = [];
		$result_data = [];
		foreach($rs_head_dlv as $r){
			$czkantorasal = $r['DLV_FROMOFFICE'];
			$czdocbctype = $r['DLV_BCTYPE'];
			$czidmodul = $r['DLV_ZID_MODUL'];
			$ccustdate = $r['DLV_BCDATE']; //TANGGAL AJU
			$nomoraju = $r['DLV_NOAJU'];
		}
		$ocustdate = date_create($ccustdate);
		$cz_ymd = date_format($ocustdate, 'Ymd');
		if($czkantorasal=='-'){
			$myar[] = ['cd' => 0, 'msg' => 'Please set KANTOR ASAL first !'];
		} else {
			$cnoaju = substr($czkantorasal,0,4).$czdocbctype.$czidmodul.$cz_ymd.$nomoraju;			
			$result_data = $this->TPB_HEADER_imod->select_where(
				["TANGGAL_DAFTAR" ,"coalesce(NOMOR_DAFTAR,0) NOMOR_DAFTAR"],
				['NOMOR_AJU' => $cnoaju]
			);
			if(count($result_data) > 0){
				$myar[] = ['cd' => 1, 'msg' => 'go ahead'];
			} else {
				$myar[] = ['cd' => 0, 'msg' => 'NOMOR AJU is not found in ceisa local data'];
			}
		}
		die('{"status" : '.json_encode($myar).', "data" : '.json_encode($result_data).'}');
	}

	function getjobstatus(){
		header('Content-Type: application/json');
		$cdo = $this->input->get('indo');
		$rs = $this->DELV_mod->select_job($cdo);
		foreach($rs as &$r){
			$rspsn = $this->SPL_mod->select_z_getpsn_byjob("'".$r['SER_DOC']."'");
			$strdocno = '';
			foreach($rspsn as $k){
				$strdocno = trim($k['PPSN1_DOCNO']);
			}
			if(trim($strdocno)!=''){
				#1. requirement
				$rsjobper_req = $this->SPL_mod->select_jobper_req($strdocno, $r['SER_DOC']);
				$ttlqtper_req = 0;
				foreach($rsjobper_req as $n){
					$ttlqtper_req = $n['MYPER'];
				}

				#2. calculated by wms
				$rsjobper_wmscal = $this->SPL_mod->select_jobper_wmscal($r['SER_DOC'], $cdo);
				$ttlqtper_wms = 0;
				$calculatedser = '';
				$calculatedserqty = '';
				$repairstatus = '-';
				foreach($rsjobper_wmscal as $n){
					$ttlqtper_wms = $n['SERD2_QTPER'];
					$calculatedser = $n['SERD2_SER'];
					$calculatedserqty = $n['SERD2_FGQTY'];
					$repairstatus = $n['SER_RMUSE_COMFG'];
				}
				if($ttlqtper_req==$ttlqtper_wms){
					$r['STATUS'] = 'OK';
				} else {
					if($ttlqtper_req>$ttlqtper_wms){
						if($repairstatus=='-'){
							$r['STATUS'] = 'Editing is required';
						} else {
							$r['STATUS'] = 'OK.';
						}
						$r['REQUIREMENT'] = $ttlqtper_req;
						$r['CALCULATED'] = $ttlqtper_wms;
						$r['SER'] = $calculatedser;
						$r['SERQTY'] = $calculatedserqty;
					} else {
						$r['STATUS'] = 'OK'; #Checking is required
						$r['REQUIREMENT'] = $ttlqtper_req;
						$r['CALCULATED'] = $ttlqtper_wms;
						$r['SER'] = $calculatedser;
						$r['SERQTY'] = $calculatedserqty;
					}
				}
			} else {
				$r['STATUS'] = 'PSN NOT FOUND';
			}
		}
		unset($r);
		die('{"data": '.json_encode($rs).'}');
	}

	function getjobstatus_resume($cdo){
		$rs = $this->DELV_mod->select_job($cdo);
		$ttlok = 0;
		foreach($rs as &$r){
			$rspsn = $this->SPL_mod->select_z_getpsn_byjob("'".$r['SER_DOC']."'");
			$strdocno = '';
			foreach($rspsn as $k){
				$strdocno = trim($k['PPSN1_DOCNO']);
			}
			if(trim($strdocno)!=''){
				#1. requirement
				$rsjobper_req = $this->SPL_mod->select_jobper_req($strdocno, $r['SER_DOC']);
				$ttlqtper_req = 0;
				foreach($rsjobper_req as $n){
					$ttlqtper_req = $n['MYPER'];
				}

				#2. calculated by wms
				$rsjobper_wmscal = $this->SPL_mod->select_jobper_wmscal($r['SER_DOC'], $cdo);
				$ttlqtper_wms = 0;
				$repairstatus = '-';
				foreach($rsjobper_wmscal as $n){
					$ttlqtper_wms = $n['SERD2_QTPER'];
					$repairstatus = $n['SER_RMUSE_COMFG'];
				}
				if($ttlqtper_req==$ttlqtper_wms){
					$ttlok++;
				} else {
					if($ttlqtper_req>$ttlqtper_wms && $repairstatus!='-'){
						$ttlok++;
					}					
				}
			} else {
				$r['STATUS'] = 'PSN NOT FOUND';
			}			
		}
		unset($r);
		$ttlrows = count($rs);		
		$toret = $ttlok==$ttlrows ? "ALL OK" :  "Checking is required";
		return $toret;
	}

	public function getjobstatus_compare(){
		header('Content-Type: application/json');
		$cdo = $this->input->get('indo');
		$cjob = $this->input->get('injob');			
		$rspsn = $this->SPL_mod->select_z_getpsn_byjob("'".$cjob."'");
		$strdocno = '';
		$rsreq = [];
		$findpsn = [];
		$rspsndetail = [];
		$strmdlcd = ''; 
		foreach($rspsn as $k){
			$strdocno = trim($k['PPSN1_DOCNO']);
			$findpsn[] = $k['PPSN1_PSNNO'];
			$strmdlcd = $k['PPSN1_MDLCD'];
		}
		if(trim($strdocno)!=''){
			#1. requirement	
			$rsreq = $this->SPL_mod->select_compare_psnjob_req($strdocno, $cjob);
			$rspsndetail = $this->SPL_mod->select_allxppsn2_bypsn($findpsn);
			$rsjobper_wmscal = $this->SPL_mod->select_job_wmscal($cjob, $cdo);
			$rsMSPP = $this->MSPP_mod->select_byvar(['MSPP_MDLCD' => $strmdlcd]);
		}
		$docno = ['docno' => $strdocno];
		die('{
			"datareq": '.json_encode($rsreq).'
			, "datapsn": '.json_encode($rspsndetail).'
			, "datacal": '.json_encode($rsjobper_wmscal).'
			, "datadoc": '.json_encode($docno).'
			, "datamsp": '.json_encode($rsMSPP).'
		}');
	}
	public function getjobstatus_compare_byser(){
		header('Content-Type: application/json');
		$cid = $this->input->get('inid');
		$cjob = $this->input->get('injob');			
		$rspsn = $this->SPL_mod->select_z_getpsn_byjob("'".$cjob."'");
		$strdocno = '';
		$rsreq = [];
		$findpsn = [];
		$rspsndetail = [];
		$strmdlcd = '';
		foreach($rspsn as $k){
			$strdocno = trim($k['PPSN1_DOCNO']);
			$findpsn[] = $k['PPSN1_PSNNO'];
			$strmdlcd = $k['PPSN1_MDLCD'];
		}
		if(trim($strdocno)!=''){
			#1. requirement	
			$rsreq = $this->SPL_mod->select_compare_psnjob_req($strdocno, $cjob);
			$rspsndetail = $this->SPL_mod->select_allxppsn2_bypsn($findpsn);
			$rsjobper_wmscal = $this->SPL_mod->select_job_wmscalculation_byser($cid);			
			$rsMSPP = $this->MSPP_mod->select_byvar(['MSPP_MDLCD' => $strmdlcd]);
		}
		$docno = ['docno' => $strdocno];
		die('{
			"datareq": '.json_encode($rsreq).'
			, "datapsn": '.json_encode($rspsndetail).'
			, "datacal": '.json_encode($rsjobper_wmscal).'
			, "datadoc": '.json_encode($docno).'
			, "datamsp": '.json_encode($rsMSPP).'
		}');
	}

	public function setSO(){
		header('Content-Type: application/json');
		$cdo = $this->input->get('indo');
		$cso = $this->input->get('inso');
		$myar = [];
		$rs = [];
		if($this->DLVSO_mod->check_Primary(['DLVSO_DLVID' => $cdo])==0){
			$rs = $this->SISCN_mod->selectso_bydo($cdo);
			$rstosave = [];
			foreach($rs as &$k){
				if($k['SISO_SOLINE']=='X'){
					$rs_mst_price = $this->XSO_mod->select_latestprice($k['SI_BSGRP'], $k['SI_CUSCD'], "'".$k['SI_ITMCD']."'" );
					foreach($rs_mst_price as $r){
						$k['SSO2_SLPRC'] = substr($r['MSPR_SLPRC'],0,1) =='.' ? '0'.$r['MSPR_SLPRC'] : $r['MSPR_SLPRC'];
					}
				}
				$rstosave[] = [
							'DLVSO_DLVID' => $cdo, 
							'DLVSO_ITMCD' => $k['SI_ITMCD'],
							'DLVSO_QTY' => intval($k['SCNQTY']),
							'DLVSO_CPONO' => $cso,
							'DLVSO_PRICE' => $k['SSO2_SLPRC']];
			}
			unset($k);
			if(count($rstosave)>0){
				$this->DLVSO_mod->insertb($rstosave);
				$myar[] = ['cd' => 1, 'msg' => 'Saved Successfully'];
			}
		} else {
			$myar[] = ['cd' => 1, 'msg' => 'Saved Successfully.'];
		}		
		
		die('{"status": '.json_encode($myar)
			.',"data": '.json_encode($rs)
			.',"so": "'.$cso.'"}');
	}

	public function get_confirmed_so(){
		header('Content-Type: application/json');
		$cdo = $this->input->get('indo');
		$rs = $this->DLVSO_mod->select_SO(['DLVSO_DLVID' => $cdo]);
		die('{"data": '.json_encode($rs).'}');
	}

	public function getunconfirmed(){
		header('Content-Type: application/json');
		$rs = $this->DELV_mod->select_delivery_unconfirmed();
		die('{"data": '.json_encode($rs).'}');
	}

	public function confirm_delivery(){
		$this->checkSession();
		ini_set('max_execution_time', '-1');
		date_default_timezone_set('Asia/Jakarta');
		header('Content-Type: application/json');
		$ITHLUPDT = date('Y-m-d H:i:s');
		$ITHDATE = date('Y-m-d');
		$cdo = $this->input->get('indo');
		$rsser_si = $this->DELV_mod->select_det_byid($cdo);
		// $arrayout = [];
		$resith = 0;
		foreach($rsser_si as $r){
			if($this->ITH_mod->check_Primary(["ITH_SER" => $r['DLV_SER'], "ITH_FORM" => "OUT-SHP-FG"])==0 ){				
				$datam = [
					"ITH_ITMCD" => trim($r['SER_ITMID']),
					"ITH_DATE" => $ITHDATE,
					"ITH_FORM" => "OUT-SHP-FG",
					"ITH_DOC" => $cdo,
					"ITH_QTY" => -$r['SISCN_SERQTY'],
					"ITH_WH" => $r['SI_WH']=="AFWH3" ? "ARSHP" : "ARSHPRTN",
					"ITH_SER" => $r['DLV_SER'],
					"ITH_LUPDT" => $ITHLUPDT, 
					"ITH_USRID" => $this->session->userdata('nama')
				];
				$resith += $this->ITH_mod->insert($datam);
			}
		}			

		// if(count($arrayout)>0){
		// 	$resith = $this->ITH_mod->insertb($arrayout);
			if($resith>0){
				$rsstatus_ith[] = ["cd" => "1", "msg" => "Confirmed", "time" => $ITHLUPDT];
			} else {
				$rsstatus_ith[] = ["cd" => "0", "msg" => "It is weird, nothing saved"];
			}
		// } else {
		// 	$rsstatus_ith[] = ["cd" => "0", "msg" => "It is weird, nothing saved"];
		// }
		die('{"status": '.json_encode($rsstatus_ith).'}');
	}

	public function setPrice($pdoc = ''){
		header('Content-Type: application/json');
		$doc = base64_decode($pdoc);
		$this->DLVPRC_mod->deleteby_filter(['DLVPRC_TXID' => $doc]);
		date_default_timezone_set('Asia/Jakarta');
		$createdAT = date('Y-m-d H:i:s');
		$rs = $this->SISCN_mod->select_forsetPrice($doc);
		$rsPriceItem = [];
		$rsPriceItemSer = [];
		foreach($rs as &$k){
			$k['PLOTPRCQTY'] = 0;
			if($k['SISO_SOLINE']=='X'){
				$rs_mst_price = $this->XSO_mod->select_latestprice($k['SI_BSGRP'], $k['SI_CUSCD'], "'".$k['SI_ITMCD']."'" );
				foreach($rs_mst_price as $r){						
					$k['SSO2_SLPRC'] = substr($r['MSPR_SLPRC'],0,1) =='.' ? '0'.$r['MSPR_SLPRC'] : $r['MSPR_SLPRC'];
				}
			}

			$isfound = false;
			foreach($rsPriceItem as $p) {
				if($p['SILINE'] === $k['SISCN_LINENO'] && $p['PRC'] === $k['SSO2_SLPRC']) {
					$isfound = true;break;
				}
			}
			if(!$isfound) {
				$rsPriceItem[] = ['SILINE' => $k['SISCN_LINENO'], 'PRC' => $k['SSO2_SLPRC'], 'QTY' => $k['PLOTQTY']];
			}
		}
		unset($k);
		$line = 1;
		$isReady = false;
		foreach($rs as &$k){
			$isfound = false;
			foreach($rsPriceItem as &$s){
				$bal = $k['SCNQTY'] - $k['PLOTPRCQTY'];
				if($k['SISCN_LINENO'] === $s['SILINE'] && $bal>0 && $s['QTY'] >0) {
					if($bal>$s['QTY']) {
						$k['PLOTPRCQTY'] += $s['QTY'];
						$rsPriceItemSer[] = ['DLVPRC_TXID' => $doc
												,'DLVPRC_SILINE' => $k['SISCN_LINENO']
												, 'DLVPRC_SER' => $k['SISCN_SER']
												, 'DLVPRC_QTY' => $s['QTY']
												, 'DLVPRC_PRC' => $s['PRC']
												, 'DLVPRC_LINENO' => $line++
												, 'DLVPRC_CREATEDAT' => $createdAT
												, 'DLVPRC_CREATEDBY' => $this->session->userdata('nama')]; $isready = true;
						$s['QTY'] = 0;
						
					} else {
						$s['QTY'] -= $bal;
						$k['PLOTPRCQTY'] += $bal;
						$rsPriceItemSer[] = ['DLVPRC_TXID' => $doc
												,'DLVPRC_SILINE' => $k['SISCN_LINENO']
												, 'DLVPRC_SER' => $k['SISCN_SER']
												, 'DLVPRC_QTY' => $bal
												, 'DLVPRC_PRC' => $s['PRC']
												, 'DLVPRC_LINENO' => $line++
												, 'DLVPRC_CREATEDAT' => $createdAT
												, 'DLVPRC_CREATEDBY' => $this->session->userdata('nama')];$isready = true;
					}
					if($k['SCNQTY'] === $k['PLOTPRCQTY']) {
						break;
					}
				}
			}
			unset($s);
		}
		unset($k);
		if($isready) {
			$this->DLVPRC_mod->insertb($rsPriceItemSer);
		}
		// die(json_encode(['data' => $rs, 'base' => $rsPriceItem, 'dataplot' => $rsPriceItemSer]));
	}

	public function posting_rm25(){
		set_exception_handler([$this,'exception_handler']);
		set_error_handler([$this, 'log_error']);
		register_shutdown_function([$this, 'fatal_handler']);
		header('Content-Type: application/json');
		ini_set('max_execution_time', '-1');
		date_default_timezone_set('Asia/Jakarta');
		$csj = $this->input->get('insj');
		$rsaktivasi = $this->AKTIVASIAPLIKASI_imod->selectAll();

	}

	public function posting_rm41(){
		set_exception_handler([$this,'exception_handler']);
		set_error_handler([$this, 'log_error']);
		register_shutdown_function([$this, 'fatal_handler']);
		header('Content-Type: application/json');
		ini_set('max_execution_time', '-1');
		date_default_timezone_set('Asia/Jakarta');
		$csj = $this->input->get('insj');
		$czsj = $csj;
		$rsaktivasi = $this->AKTIVASIAPLIKASI_imod->selectAll();
		$rsplotrm = $this->DELV_mod->selectRMForPostingCeisa($csj);
		$tpb_barang = [];
		$SERI_BARANG = 1;
		$czdocbctype = '';
		$cztujuanpengiriman = '';
		$ccustdate = '';
		$czConaNo = '';
		$czkantorasal = '';
		$cz_h_NETTO = 0;
		$cz_h_BRUTO = 0;
		$requestResume = [];
		$responseResume = [];
		$arx_item = [];
		$arx_qty = [];
		$arx_lot = [];
		foreach($rsaktivasi as $r){
			$czkantorasal = $r['KPPBC'];
			$czidmodul = substr('00000'.$r['ID_MODUL'],-6);
			$czidmodul_asli = $r['ID_MODUL'];
			$czidpengusaha = $r['NPWP'];
			$cznmpengusaha = $r['NAMA_PENGUSAHA'];
			$czalamatpengusaha = $r['ALAMAT_PENGUSAHA'];
			$czizinpengusaha = $r['NOMOR_SKEP'];		
		}
		foreach($rsplotrm as $r){
			#set basic data
			$czdocbctype = $r['DLV_BCTYPE'];
			$cztujuanpengiriman = $r['DLV_PURPOSE'];
			$ccustdate = $r['DLV_BCDATE'];
			$czConaNo = $r['DLV_CONA'];
			$czConaDate = $r['MCONA_DATE'];
			$cnoaju = $r['DLV_ZNOMOR_AJU'];
			$cz_KODE_JENIS_TPB = $r['DLV_ZJENIS_TPB_ASAL'];
			$czidpenerima = str_replace(".","",$r['MCUS_TAXREG']);
			$czidpenerima = str_replace("-","", $czidpenerima);
			$cznmpenerima = $r['MDEL_ZNAMA'];
			$czalamatpenerima = $r['MDEL_ADDRCUSTOMS'];
			$cznamapengangkut = $r['MSTTRANS_TYPE'];
			$cznomorpolisi = $r['DLV_TRANS'];
			$cz_h_NETTO += $r['NWG'];
			$cz_h_BRUTO += $r['GWG'];
			$czinvoice = trim($r['DLV_INVNO']);
			$czinvoicedt = $r['DLV_INVDT'];
			#end

			#set request data
			$arx_item[] = $r['DLV_ITMCD'];
			$arx_qty[] = $r['DLV_QTY'];
			$arx_lot[] = '';
			$isfound = false;
			foreach($requestResume as &$n){
				if($n['ITEM'] == $r['DLV_ITMCD']){
					$n['QTY'] += $r['DLV_QTY'];
					$isfound = true;
				}
			}
			unset($n);
			if(!$isfound){
				$requestResume[] = ['ITEM' => $r['DLV_ITMCD'], 'QTY' => $r['DLV_QTY']];
			}
			#end

			#set barang for ceisa
			$tpb_barang[] = [
				'KODE_BARANG' => $r['DLV_ITMCD']
				,'POS_TARIF' => $r['MITM_HSCD']
				,'URAIAN' => $r['MITM_ITMD1']
				,'JUMLAH_SATUAN' => $r['DLV_QTY']
				,'KODE_SATUAN' => $r['MITM_STKUOM']=='PCS' ? 'PCE' : $r['MITM_STKUOM']
				,'NETTO' => $r['NWG']
				,'HARGA_PENYERAHAN' => 0
				,'SERI_BARANG' => $SERI_BARANG
				,'KODE_STATUS' => '02'
			];
			#end
		}
		#BAHAN BAKU
		$tpb_bahan_baku = [];
		try {
			$rstemp = $this->inventory_getstockbc_v2($czdocbctype,$cztujuanpengiriman,$csj, $arx_item, $arx_qty, $arx_lot, $ccustdate, $czConaNo);
			$rsbc = json_decode($rstemp);
			if(!is_null($rsbc)){
				if( count($rsbc)>0 ){ 
					foreach($rsbc as $o){
						foreach($o->data as $v){
							$isfound = false;
							foreach($responseResume as &$n){
								if($n['ITEM'] == $v->BC_ITEM){
									$n['QTY'] += $v->BC_QTY;
									$isfound = true;
								}
							}
							unset($n);
							if(!$isfound){
								$responseResume[] = ['ITEM' => $v->BC_ITEM, 'QTY' => $v->BC_QTY];
							}
							//THE ADDITIONAL INFO						
							if($v->RCV_KPPBC!='-'){
								$tpb_bahan_baku[] = [
									'KODE_JENIS_DOK_ASAL' => $v->BC_TYPE
									,'NOMOR_DAFTAR_DOK_ASAL' => $v->BC_NUM
									,'TANGGAL_DAFTAR_DOK_ASAL' => $v->BC_DATE
									,'KODE_KANTOR' => $v->RCV_KPPBC
									,'NOMOR_AJU_DOK_ASAL' => $v->BC_AJU
									,'SERI_BARANG_DOK_ASAL' => $v->RCV_ZNOURUT
									,'HARGA_PENYERAHAN' => substr($v->RCV_PRPRC,0,1)=='.' ? ('0'.$v->RCV_PRPRC* $v->BC_QTY) : ($v->RCV_PRPRC * $v->BC_QTY)
									,'KODE_BARANG' => $v->BC_ITEM
									,'KODE_STATUS' => "02"
									,'URAIAN' => $v->MITM_ITMD1
									,'JUMLAH_SATUAN' => $v->BC_QTY
									,'JENIS_SATUAN' => ($v->MITM_STKUOM=='PCS') ? 'PCE' : $v->MITM_STKUOM
									,'KODE_ASAL_BAHAN_BAKU' => 1
									,'RBM' => substr($v->RCV_BM,0,1) == '.' ? ('0'.$v->RCV_BM) : ($v->RCV_BM)
								];
							} else {
								$tpb_bahan_baku[] = [
									'KODE_JENIS_DOK_ASAL' => $v->BC_TYPE
									,'NOMOR_DAFTAR_DOK_ASAL' => $v->BC_NUM
									,'TANGGAL_DAFTAR_DOK_ASAL' => $v->BC_DATE
									,'KODE_KANTOR' => NULL
									,'NOMOR_AJU_DOK_ASAL' => $v->BC_AJU
									,'SERI_BARANG_DOK_ASAL' => $v->RCV_ZNOURUT
									,'HARGA_PENYERAHAN' => 0
									,'KODE_BARANG' => $v->BC_ITEM 
									,'KODE_STATUS' => "02"
									,'URAIAN' => $v->MITM_ITMD1
									,'JUMLAH_SATUAN' => $v->BC_QTY
									,'JENIS_SATUAN' => 'PCE'
									,'KODE_ASAL_BAHAN_BAKU' => 1
									,'RBM' => 0
								];
							}
						}
					}
					$listNeedExBC = [];
					foreach($requestResume as $r){
						$isfound = false;
						foreach($responseResume as $n){
							if($r['ITEM'] == $n['ITEM']){
								$isfound = true;
								if($r['QTY']!= $n['QTY']){
									$listNeedExBC[] = ['ITMCD' => $r['ITEM'], 'QTY' => $r['QTY']-$n['QTY'] , 'LOTNO' => '?'];
								}		
							}
						}
						if(!$isfound){
							$listNeedExBC[] = ['ITMCD' => $r['ITEM'], 'QTY' => $r['QTY'], 'LOTNO' => '?'];
						}
					}
					if(count($listNeedExBC)>0){
						$this->inventory_cancelDO($czsj);
						$myar[] = ['cd' => 110 ,'msg' => 'EX-BC for '.count($listNeedExBC). ' item(s) is not found. ', "doctype" => $czdocbctype, "tujuankirim" => $cztujuanpengiriman ];
						die('{"status" : '.json_encode($myar).', "data":'.json_encode($listNeedExBC).',"rawdata":'.json_encode($tpb_bahan_baku).'}');
					}
				} else {
					$myar[] = ["cd" => "0", "msg" => "Could not find exbc, please contact admin !"];
					die('{"status":'.json_encode($myar).'}');
				}
			} else {
				$myar[] = ["cd" => "0", "msg" => "Could not find exbc, please contact admin"];
				die('{"status":'.json_encode($myar).'}');
			}
		} catch (Exception $e) {
			$this->inventory_cancelDO($czsj);
			$myar[] = ['cd' => 110 ,'msg' => $e->getMessage()];
			die('{"status" : '.json_encode($myar).'"}');
		}
		$tpb_header = [
			"NOMOR_AJU" => $cnoaju, "KODE_KANTOR" => $czkantorasal , 
			"KODE_JENIS_TPB" => $cz_KODE_JENIS_TPB ,  "KODE_TUJUAN_PENGIRIMAN" => $cztujuanpengiriman,
			
			"KODE_ID_PENGUSAHA" => "1", "ID_PENGUSAHA" => $czidpengusaha, "NAMA_PENGUSAHA" => $cznmpengusaha,
			"ALAMAT_PENGUSAHA" => $czalamatpengusaha, "NOMOR_IJIN_TPB" => $czizinpengusaha , 

			"KODE_ID_PENERIMA_BARANG" => "1", "ID_PENERIMA_BARANG" => $czidpenerima,
			"NAMA_PENERIMA_BARANG" => $cznmpenerima , "ALAMAT_PENERIMA_BARANG" => $czalamatpenerima,
						
			"HARGA_PENYERAHAN" => 0,

			"NAMA_PENGANGKUT" => $cznamapengangkut, "NOMOR_POLISI" => $cznomorpolisi,
			"BRUTO" => $cz_h_BRUTO , "NETTO" => $cz_h_NETTO, "JUMLAH_BARANG" => count($rsplotrm),

			"KOTA_TTD" => "CIKARANG", "TANGGAL_TTD" =>$ccustdate, 
			"KODE_DOKUMEN_PABEAN" => $czdocbctype, "ID_MODUL" => $czidmodul_asli, "VERSI_MODUL" => NULL,
			"VOLUME" => 0, "KODE_STATUS" => '00'
		];
		$tpb_kemasan[] = ["JUMLAH_KEMASAN" => 1 ,"KODE_JENIS_KEMASAN" => "BX"];
		$tpb_dokumen[] = ["KODE_JENIS_DOKUMEN" => "380", "NOMOR_DOKUMEN" => $czinvoice , "TANGGAL_DOKUMEN" => $czinvoicedt, "TIPE_DOKUMEN" => "02", "SERI_DOKUMEN" => 1]; //invoice		
		$tpb_dokumen[] = ["KODE_JENIS_DOKUMEN" => "217", "NOMOR_DOKUMEN" => $czinvoice , "TANGGAL_DOKUMEN" => $czinvoicedt, "TIPE_DOKUMEN" => "02","SERI_DOKUMEN" => 2]; //packing list
		$tpb_dokumen[] = ["KODE_JENIS_DOKUMEN" => "640", "NOMOR_DOKUMEN" => $czsj ,  "TANGGAL_DOKUMEN" =>  $ccustdate , "TIPE_DOKUMEN" => "02", "SERI_DOKUMEN" => 3 ]; //surat jalan		
		$tpb_dokumen[] = ["KODE_JENIS_DOKUMEN" => "315", "NOMOR_DOKUMEN" => $czConaNo ,  "TANGGAL_DOKUMEN" =>  $czConaDate , "TIPE_DOKUMEN" => "02", "SERI_DOKUMEN" => 4 ]; //kontrak

		#set exbc list
		$tpb_dokumen_40 = [];
		$tpb_dokumen_40_tgl = [];
		foreach($tpb_bahan_baku as $k){
			if(!in_array($k['NOMOR_DAFTAR_DOK_ASAL'], $tpb_dokumen_40)){
				$tpb_dokumen_40[] = $k['NOMOR_DAFTAR_DOK_ASAL'];
				$tpb_dokumen_40_tgl[] = $k['TANGGAL_DAFTAR_DOK_ASAL'];
			}
		}
		
		$noseri = 5;
		$cO_exBC = count($tpb_dokumen_40);
		for($i=0; $i<$cO_exBC; $i++){
			$tpb_dokumen[] = ["KODE_JENIS_DOKUMEN" => "40"
								,"NOMOR_DOKUMEN" => $tpb_dokumen_40[$i] 
								,"TANGGAL_DOKUMEN" =>  $tpb_dokumen_40_tgl[$i]
								,"TIPE_DOKUMEN" => "01"
								,"SERI_DOKUMEN" => $noseri
							];
			$noseri++;
		}
		#end

		#INSERT CEISA
		##1 TPB HEADER
		$ZR_TPB_HEADER = $this->TPB_HEADER_imod->insert($tpb_header);		
		#end

		##2 TPB DOKUMEN
		foreach($tpb_dokumen as &$n){
			$n['ID_HEADER'] = $ZR_TPB_HEADER;
		}
		unset($n);
		$this->TPB_DOKUMEN_imod->insertb($tpb_dokumen);
		#end

		##3 TPB KEMASAN
		foreach($tpb_kemasan as &$n){
			$n['ID_HEADER'] = $ZR_TPB_HEADER;
		}
		unset($n);
		$this->TPB_KEMASAN_imod->insertb($tpb_kemasan);
		#end

		##4 TPB BARANG
		foreach($tpb_barang as &$n){
			$n['ID_HEADER'] = $ZR_TPB_HEADER;
			$n['JUMLAH_BAHAN_BAKU']=1;			
		}
		unset($n);		
		#end

		##4 TPB BARANG & BAHAN BAKU
		foreach($tpb_barang as $n){
			$ZR_TPB_BARANG = $this->TPB_BARANG_imod->insert($n);			
			foreach($tpb_bahan_baku as $b){
				if($n['KODE_BARANG']==$b['KODE_BARANG']){
					$this->TPB_BAHAN_BAKU_imod
						->insert([
							'KODE_JENIS_DOK_ASAL' => $b['KODE_JENIS_DOK_ASAL']
							,'NOMOR_DAFTAR_DOK_ASAL' => $b['NOMOR_DAFTAR_DOK_ASAL']
							,'TANGGAL_DAFTAR_DOK_ASAL' => $b['TANGGAL_DAFTAR_DOK_ASAL']
							,'KODE_KANTOR' => $b['KODE_KANTOR']
							,'NOMOR_AJU_DOK_ASAL' => $b['NOMOR_AJU_DOK_ASAL']
							,'SERI_BARANG_DOK_ASAL' => $b['SERI_BARANG_DOK_ASAL']
							
							,'HARGA_PENYERAHAN' => $b['HARGA_PENYERAHAN']
							
							,'KODE_BARANG' => $b['KODE_BARANG']
							,'KODE_STATUS' => $b['KODE_STATUS']							
							,'URAIAN' => $b['URAIAN']
							
							,'JUMLAH_SATUAN' => $b['JUMLAH_SATUAN']
							,'JENIS_SATUAN' => $b['JENIS_SATUAN']
														
							
							,'KODE_ASAL_BAHAN_BAKU'	=> $b['KODE_ASAL_BAHAN_BAKU']
							,'NETTO' => 0
							,'SERI_BAHAN_BAKU' => $b['SERI_BAHAN_BAKU']
							,'SERI_BARANG' => $n['SERI_BARANG']
							,'ID_BARANG' => $ZR_TPB_BARANG
							,'ID_HEADER' => $ZR_TPB_HEADER
						]);
				}
			}
		}
		#end

		$myar[] = ['cd' => 1 ,'msg' => 'Done, check your TPB' ];
		$this->gotoque($csj);
		die('{"status" : '.json_encode($myar).'}');
	}

	function cancelposting() {
		header('Content-Type: application/json');
		$txid = $this->input->post('msj');
		$rs = $this->DELV_mod->select_bytx($txid);
		$bctype = '';
		foreach($rs as $r) {
			$bctype = $r['DLV_BCTYPE'];
		}
		
	}

	function exbclist() {
		header('Content-Type: application/json');
		$txid = $this->input->get('txid');
		$rs = strlen($txid==0) ? [] : $this->ZRPSTOCK_mod->select_byTXID($txid);	
		die(json_encode(['data' => $rs]));
	}	

	function so_dlv() {
		header('Content-Type: application/json');
		$doc = $this->input->get('doc');
		$rs = $this->DLVSO_mod->select_bytxid($doc);
		die(json_encode(['data' => $rs]));
	}
	
	function so_mega_dlv() {
		header('Content-Type: application/json');
		$doc = $this->input->get('doc');
		$rs = $this->DELV_mod->select_plotted_so_mega($doc);
		foreach($rs as &$k){
			if($k['SISO_SOLINE']=='X'){ 
				$rs_mst_price = $this->XSO_mod->select_latestprice($k['DLV_BSGRP'], $k['DLV_CUSTCD'], "'".$k['SER_ITMID']."'" );
				foreach($rs_mst_price as $r){
					$k['SSO2_SLPRC'] = substr($r['MSPR_SLPRC'],0,1) =='.' ? '0'.$r['MSPR_SLPRC'] : $r['MSPR_SLPRC'];									
				}
			}
		}
		unset($k);
		die(json_encode(['data' => $rs]));
	}

	function setflag(){		
		header('Content-Type: application/json');
		date_default_timezone_set('Asia/Jakarta');
		$crnt_dt = date('Y-m-d H:i:s');
		$serlist = $this->input->post('inser');
		$inpin = $this->input->post('inpin');
		$myar = [];
		if($inpin==="WEAGREE") {
			#update DLV_TBL
			$this->DELV_mod->updatebySER(
				['DLV_CALCU_REMARK' => 'flg_ok', 'DLV_CALCU_USR' => $this->session->userdata('nama'), 'DLV_CALCU_DT' => $crnt_dt ]
				,$serlist);
			#end
			#update SER_TBL
			$this->SER_mod->updatebySER(
				['SER_RMUSE_COMFG' => 'flg_ok', 'SER_RMUSE_COMFG_USRID' => $this->session->userdata('nama'), 'SER_RMUSE_COMFG_DT' => $crnt_dt ]
				,$serlist);
			#end
			$myar[] = ['cd' => 1, 'msg' => 'go ahead'];	
			$rs1 = [];

			foreach($serlist as $r)	{
				$rs1[] = ['ID' => $r];
			}

			$msgs = [];
			$nom = 1;
			foreach($this->MSG_USERS as $r) {
				$msgs[] = [
					'MSG_FR' => 'ane'
					,'MSG_TO' => $r
					,'MSG_TXT' => 'A User set some FG  Calculation as OK'
					,'MSG_REFFDATA' => json_encode($rs1)					
					,'MSG_LINE' => $nom
					,'MSG_TOPIC' => 'SKIP_COMPONENT'
					,'MSG_CREATEDAT' => $crnt_dt
				];
				$nom++;
			}
			$this->MSG_mod->insertb($msgs);			
		} else {
			$myar[] = ['cd' => 0, 'msg' => 'The PIN is not valid'];
		}
		die(json_encode(['status' => $myar ]));
	}
	
	public function gotoque($psj){
		$mdo = base64_encode($psj);
		$ch = curl_init();
		curl_setopt($ch, CURLOPT_URL, 'http://192.168.0.29:8081/api_inventory/api/stock/onDelivery/'.$mdo);
		curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
		curl_setopt($ch, CURLOPT_CONNECTTIMEOUT, 5);
		$data = curl_exec($ch);	
		curl_close($ch);
	}

	public function inventory___getstockbc($pbc_type,$ptujuan,$psj , $prm, $pqty, $plot,$pbcdate ){
		$ch = curl_init();
		curl_setopt($ch, CURLOPT_URL, 'http://192.168.0.29:8081/api_inventory/api/inventory/getStockBC');
		curl_setopt($ch, CURLOPT_POST, true);
		curl_setopt($ch, CURLOPT_POSTFIELDS,"bc=$pbc_type&tujuan=$ptujuan&doc=$psj&date_out=$pbcdate&item_num=$prm&qty=$pqty&lot=$plot");
		curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
		curl_setopt($ch, CURLOPT_CONNECTTIMEOUT, 300);//5sdfdfs
		curl_setopt($ch, CURLOPT_TIMEOUT, 660);
		$data = curl_exec($ch);	
		curl_close($ch);
		// return json_decode($data);
		return $data;
	}
	public function inventory_getstockbc_v2($pbc_type,$ptujuan,$psj , $prm, $pqty, $plot,$pbcdate, $pkontrak="" ){
		$fields = [
			'bc' => $pbc_type,
			'tujuan' => $ptujuan,
			'date_out' => $pbcdate,
			'doc' => $psj,
			'kontrak' => $pkontrak,
			'item_num' => $prm,
			'qty' => $pqty //,
			// 'lot' => $plot
		];
		$fields_string = http_build_query($fields);	
		$ch = curl_init();
		curl_setopt($ch, CURLOPT_URL, 'http://192.168.0.29:8081/api_inventory/api/inventory/getStockBCArray');
		curl_setopt($ch, CURLOPT_POST, true);
		curl_setopt($ch, CURLOPT_POSTFIELDS,$fields_string);
		curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
		curl_setopt($ch, CURLOPT_CONNECTTIMEOUT, 0);
		curl_setopt($ch, CURLOPT_TIMEOUT, 3600);
		$data = curl_exec($ch);	
		curl_close($ch);
		// return json_decode($data);
		return $data;
	}
	public function inventory_cancelDO($pdo ){		
		$mdo = base64_encode($pdo);
		$ch = curl_init();
		curl_setopt($ch, CURLOPT_URL, 'http://192.168.0.29:8081/api_inventory/api/inventory/cancelDO/'.$mdo);
		curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
		curl_setopt($ch, CURLOPT_CONNECTTIMEOUT, 300);//5sdfdfs
		curl_setopt($ch, CURLOPT_TIMEOUT, 660);
		$data = curl_exec($ch);	
		curl_close($ch);
		// return json_decode($data);
		return $data;
	}
}