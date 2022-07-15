<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class RMCalculator {
	protected $CI;
	public function __construct()
	{
		$this->CI =& get_instance();
		$this->CI->load->library('session');
		$this->CI->load->model('SERD_mod');	
		$this->CI->load->model('SER_mod');
		$this->CI->load->model('ITH_mod');
		$this->CI->load->model('SPL_mod');
		$this->CI->load->model('MSPP_mod');
		$this->CI->load->model('SPLSCN_mod');
		$this->CI->load->model('MSTITM_mod');
		$this->CI->load->model('MITMSA_mod');
		$this->CI->load->model('SERML_mod');
		$this->CI->load->model('PWOP_mod');
		$this->CI->load->model('SPLREFF_mod');
		$this->CI->load->model('MSTSUP_mod');
	}	

	public function tobexported_list_for_serd($parpsn = []){
		$apsn = $parpsn;
		$rsbase = $this->CI->SPL_mod->select_ppsn2_forserd($apsn);
		$rsscn = $this->CI->SPLSCN_mod->selectby_filter_for_serd($apsn);
		$rsscn_reff = $this->CI->SPLREFF_mod->select_forcalculation($apsn);
		$rsAdditional = $this->CI->SPLREFF_mod->select_additional_PSN($apsn);
		$rsfix = [];
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

		
		foreach($rsbase as &$r){
			$think = true;
			while($think){
				$grasp = false;
				foreach($rsscn as $d){
					if( ($r['PPSN2_FR'] == $d['SPLSCN_FEDR']) && ( $r['PPSN2_MCZ'] == $d['SPLSCN_ORDERNO']) 
					&& ($r['PPSN2_SUBPN'] == $d['SPLSCN_ITMCD'])  && ($r['PPSN2_PSNNO'] == $d['SPLSCN_DOC']) 
					&& ($r['PPSN2_LINENO'] == $d['SPLSCN_LINE'] ) && ($r['PPSN2_ITMCAT'] == $d['SPLSCN_CAT']) 
					&& $d['USED']==false){
						$grasp = true; break;
					}
				}
				if($grasp){
					foreach($rsscn as &$d){
						if( ( $r['PPSN2_MCZ'] == $d['SPLSCN_ORDERNO'])  && ($r['PPSN2_SUBPN'] == $d['SPLSCN_ITMCD']) 
						&& $d['USED']==false and ($r['PPSN2_FR'] == $d['SPLSCN_FEDR'])
						&& ($r['PPSN2_LINENO'] == $d['SPLSCN_LINE']) && ($r['PPSN2_ITMCAT'] == $d['SPLSCN_CAT']) 
						&& ($r['PPSN2_PSNNO'] == $d['SPLSCN_DOC'] ) 
						){
							$think2 = true;
							while($think2){
								if($r['PPSN2_REQQT'] > $r['TTLSCN']){
									if($d['USED']==false){
										$isfound = false;
										foreach($rsfix as &$t){
											if( ( $t["PPSN2_MC"] ==$r["PPSN2_MC"])  && ( $t["SPLSCN_ORDERNO"] == $r["PPSN2_MCZ"]) 
											&& ( $t["SPLSCN_ITMCD"] == $r["PPSN2_SUBPN"])  && ( $t["PPSN2_PROCD"] == $r["PPSN2_PROCD"])  
												){
												$r['TTLSCN']+=$d['SPLSCN_QTY'];
												$rsfix[] = [
													"PPSN2_DATANO" => $r["PPSN2_DATANO"]
													,"SPLSCN_FEDR" => $d["SPLSCN_FEDR"]
													,"SPLSCN_ITMCD" => $d["SPLSCN_ITMCD"]
													,"SPLSCN_QTY" => $d["SPLSCN_QTY"]													
													,"SPLSCN_LOTNO" => $d["SPLSCN_LOTNO"]
													,"SPLSCN_ORDERNO" => $d["SPLSCN_ORDERNO"]
													,"SPLSCN_LINE" => $d["SPLSCN_LINE"]
													,"PPSN2_MC" => $r["PPSN2_MC"]
													,"PPSN2_PROCD" => $r["PPSN2_PROCD"]
													,"SPLSCN_CAT" => $d['SPLSCN_CAT']
													,"SPLSCN_DOC" => $r['PPSN2_PSNNO']
													,"SCNTIME" => $d['SPLSCN_LUPDT'] 
													,"PPSN2_MSFLG" => $r['PPSN2_MSFLG']
												];
												$isfound=true;
												$d['USED'] = true;
												break;
											}
										}
										unset($t);

										if(!$isfound){
											$rsfix[]=[
												"PPSN2_DATANO" => $r["PPSN2_DATANO"]
												, "SPLSCN_FEDR" => $d["SPLSCN_FEDR"]
												,"SPLSCN_ITMCD" => $d["SPLSCN_ITMCD"]
												, "SPLSCN_QTY" => $d["SPLSCN_QTY"]												
												, "SPLSCN_LOTNO" => $d["SPLSCN_LOTNO"]
												,"SPLSCN_ORDERNO" => $d["SPLSCN_ORDERNO"]
												,"SPLSCN_LINE" => $d["SPLSCN_LINE"]
												,"PPSN2_MC" => $r["PPSN2_MC"]
												, "PPSN2_PROCD" => $r["PPSN2_PROCD"]
												,"SPLSCN_CAT" => $d['SPLSCN_CAT']
												,"SPLSCN_DOC" => $r['PPSN2_PSNNO']
												, "SCNTIME" => $d['SPLSCN_LUPDT']
												, "PPSN2_MSFLG" => $r['PPSN2_MSFLG']
											];
											$r['TTLSCN'] += $d['SPLSCN_QTY'];
											$d['USED'] = true;
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

		#2nd try for adapt user data edit
		# there was a condition where user edit req.qty data in PSN (it is weird i think)
		foreach($rsbase as &$r){
			$think = true;
			while($think){
				$grasp = false;
				foreach($rsscn as $d){
					if( ($r['PPSN2_FR'] == $d['SPLSCN_FEDR']) && ( $r['PPSN2_MCZ'] == $d['SPLSCN_ORDERNO']) 
					&& ($r['PPSN2_SUBPN'] == $d['SPLSCN_ITMCD'])  && ($r['PPSN2_PSNNO'] == $d['SPLSCN_DOC']) 
					&& ($r['PPSN2_LINENO'] == $d['SPLSCN_LINE'] ) && ($r['PPSN2_ITMCAT'] == $d['SPLSCN_CAT']) 
					&& $d['USED']==false){
						$grasp = true; break;
					}
				}
				if($grasp){
					foreach($rsscn as &$d){
						if( ( $r['PPSN2_MCZ'] == $d['SPLSCN_ORDERNO'])  && ($r['PPSN2_SUBPN'] == $d['SPLSCN_ITMCD']) 
						&& $d['USED']==false and ($r['PPSN2_FR'] == $d['SPLSCN_FEDR'])
						&& ($r['PPSN2_LINENO'] == $d['SPLSCN_LINE']) && ($r['PPSN2_ITMCAT'] == $d['SPLSCN_CAT']) 
						&& ($r['PPSN2_PSNNO'] == $d['SPLSCN_DOC'] ) 
						){
							$think2 = true;
							while($think2){
								if($d['USED']==false){
									$isfound = false;
									foreach($rsfix as &$t){
										if( ( $t["PPSN2_MC"] ==$r["PPSN2_MC"])  && ( $t["SPLSCN_ORDERNO"] == $r["PPSN2_MCZ"]) 
										&& ( $t["SPLSCN_ITMCD"] == $r["PPSN2_SUBPN"])  && ( $t["PPSN2_PROCD"] == $r["PPSN2_PROCD"])  
											){
											$r['TTLSCN']+=$d['SPLSCN_QTY'];
											$rsfix[] = [
												"PPSN2_DATANO" => $r["PPSN2_DATANO"]
												,"SPLSCN_FEDR" => $d["SPLSCN_FEDR"]
												,"SPLSCN_ITMCD" => $d["SPLSCN_ITMCD"]
												,"SPLSCN_QTY" => $d["SPLSCN_QTY"]													
												,"SPLSCN_LOTNO" => $d["SPLSCN_LOTNO"]
												,"SPLSCN_ORDERNO" => $d["SPLSCN_ORDERNO"]
												,"SPLSCN_LINE" => $d["SPLSCN_LINE"]
												,"PPSN2_MC" => $r["PPSN2_MC"]
												,"PPSN2_PROCD" => $r["PPSN2_PROCD"]
												,"SPLSCN_CAT" => $d['SPLSCN_CAT']
												,"SPLSCN_DOC" => $r['PPSN2_PSNNO']
												,"SCNTIME" => $d['SPLSCN_LUPDT'] 
												,"PPSN2_MSFLG" => $r['PPSN2_MSFLG']
											];
											$isfound=true;
											$d['USED'] = true;
											break;
										}
									}
									unset($t);

									if(!$isfound){
										$rsfix[]=[
											"PPSN2_DATANO" => $r["PPSN2_DATANO"]
											, "SPLSCN_FEDR" => $d["SPLSCN_FEDR"]
											,"SPLSCN_ITMCD" => $d["SPLSCN_ITMCD"]
											, "SPLSCN_QTY" => $d["SPLSCN_QTY"]												
											, "SPLSCN_LOTNO" => $d["SPLSCN_LOTNO"]
											,"SPLSCN_ORDERNO" => $d["SPLSCN_ORDERNO"]
											,"SPLSCN_LINE" => $d["SPLSCN_LINE"]
											,"PPSN2_MC" => $r["PPSN2_MC"]
											, "PPSN2_PROCD" => $r["PPSN2_PROCD"]
											,"SPLSCN_CAT" => $d['SPLSCN_CAT']
											,"SPLSCN_DOC" => $r['PPSN2_PSNNO']
											, "SCNTIME" => $d['SPLSCN_LUPDT']
											, "PPSN2_MSFLG" => $r['PPSN2_MSFLG']
										];
										$r['TTLSCN'] += $d['SPLSCN_QTY'];
										$d['USED'] = true;
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
		
		
		/* 
		NEW LOGIC
		SET SHIELDPLATE qty = Balance
		*/		
		foreach($rsbase as $d){
			$bal = $d['PPSN2_REQQT'] - $d['TTLSCN'];
			if($bal > 0) {
				if(strpos($d['MITM_ITMD1'],'SHIELD PLATE')!==false || $d["PPSN2_PROCD"]=='SMT-SP')				
				{
					$rsfix[] = [
						"PPSN2_DATANO" => $d["PPSN2_DATANO"]
						,"SPLSCN_FEDR" => $d["PPSN2_FR"]
						,"SPLSCN_ITMCD" => $d["PPSN2_SUBPN"]
						,"SPLSCN_QTY" => $bal
						,"SPLSCN_LOTNO" => 'AUTO'
						,"SPLSCN_ORDERNO" => $d["PPSN2_MCZ"]
						,"SPLSCN_LINE" => $d["PPSN2_LINENO"]
						,"PPSN2_MC" => $d["PPSN2_MC"]
						,"PPSN2_PROCD" => $d["PPSN2_PROCD"]
						,"SPLSCN_CAT" => $d['PPSN2_ITMCAT']
						,"SPLSCN_DOC" => $d['PPSN2_PSNNO']
						,"SCNTIME" => NULL
						,"PPSN2_MSFLG" => $d['PPSN2_MSFLG']
					];
				} else {
					/* 
					NEW LOGIC
					SET ASAHI, TOYO, STANLEY KITTING DATA AUTOCOMPLETE
					*/					
					if($d['PPSN2_BSGRP'] === 'PSI2PPZTDI' || $d['PPSN2_BSGRP'] === 'PSI2PPZADI' || $d['PPSN2_BSGRP'] === 'PSI2PPZSTY') {
						$rsfix[] = [
							"PPSN2_DATANO" => $d["PPSN2_DATANO"]
							,"SPLSCN_FEDR" => $d["PPSN2_FR"]
							,"SPLSCN_ITMCD" => $d["PPSN2_SUBPN"]
							,"SPLSCN_QTY" => $bal
							,"SPLSCN_LOTNO" => '25AUTO'
							,"SPLSCN_ORDERNO" => $d["PPSN2_MCZ"]
							,"SPLSCN_LINE" => $d["PPSN2_LINENO"]
							,"PPSN2_MC" => $d["PPSN2_MC"]
							,"PPSN2_PROCD" => $d["PPSN2_PROCD"]
							,"SPLSCN_CAT" => $d['PPSN2_ITMCAT']
							,"SPLSCN_DOC" => $d['PPSN2_PSNNO']
							,"SCNTIME" => NULL
							,"PPSN2_MSFLG" => $d['PPSN2_MSFLG']
						];
					}
				}
			}
		}

		#include SPLREFF
		if(count($rsscn_reff)) {
			$rsfix = array_merge($rsfix, $rsscn_reff);
		}
		if(count($rsAdditional)) {
			$rsfix = array_merge($rsfix, $rsAdditional);
		}
		#END
		return $rsfix;		
	}
	public function tobexported_list_for_serd_subassy($parpsn = []){
		$apsn = $parpsn;
		$rsbase = $this->CI->SPL_mod->select_ppsn2_forserd($apsn);
		$rsscn = $this->CI->SPLSCN_mod->selectby_filter_for_serd($apsn);
		$rsscn_reff = $this->CI->SPLREFF_mod->select_forcalculation($apsn);
		$rsfix = [];
		foreach($rsbase as &$d){
			if(!array_key_exists("TTLSCN", $d)){
				if($this->CI->MSTITM_mod->check_Primary_FG_AS_BOM(['PWOP_BOMPN' => $d['PPSN2_SUBPN']])){					
					$d["TTLSCN"] = $d['PPSN2_REQQT'];
					$rsfix[] = [
						"PPSN2_DATANO" => $d["PPSN2_DATANO"]
						,"SPLSCN_FEDR" => $d["PPSN2_FR"]
						,"SPLSCN_ITMCD" => $d["PPSN2_SUBPN"]
						,"SPLSCN_QTY" => $d["TTLSCN"]						
						,"SPLSCN_LOTNO" => NULL
						,"SPLSCN_ORDERNO" => $d["PPSN2_MCZ"]
						,"SPLSCN_LINE" => $d["PPSN2_LINENO"]
						,"PPSN2_MC" => $d["PPSN2_MC"]
						,"PPSN2_PROCD" => $d["PPSN2_PROCD"]
						,"SPLSCN_CAT" => $d['PPSN2_ITMCAT']
						,"SPLSCN_DOC" => $d['PPSN2_PSNNO']
						,"SCNTIME" => NULL
						,"PPSN2_MSFLG" => $d['PPSN2_MSFLG']
					];
				} else {
					$d["TTLSCN"] = 0;
				}
			}
		}
		unset($d);

		foreach($rsscn as &$d){
			if(!array_key_exists("USED", $d)){
				$d["USED"] = false;
			}
		}
		unset($d);

		
		foreach($rsbase as &$r){
			$think = true;
			while($think){
				$grasp = false;
				foreach($rsscn as $d){
					if( ($r['PPSN2_FR'] == $d['SPLSCN_FEDR']) && ( $r['PPSN2_MCZ'] == $d['SPLSCN_ORDERNO'] ) 
					&& ($r['PPSN2_SUBPN'] == $d['SPLSCN_ITMCD'] ) && ($r['PPSN2_PSNNO'] == $d['SPLSCN_DOC'])  
					&& ($r['PPSN2_LINENO'] == $d['SPLSCN_LINE'] ) && ($r['PPSN2_ITMCAT'] == $d['SPLSCN_CAT']) 
					&& $d['USED']==false){
						$grasp = true; break;
					}
				}
				if($grasp){
					foreach($rsscn as &$d){
						if( ( $r['PPSN2_MCZ'] == $d['SPLSCN_ORDERNO'] ) && ($r['PPSN2_SUBPN'] == $d['SPLSCN_ITMCD']) 
						&& $d['USED']==false && ($r['PPSN2_FR'] == $d['SPLSCN_FEDR'])
						&& ($r['PPSN2_LINENO'] == $d['SPLSCN_LINE'])  && ($r['PPSN2_ITMCAT'] == $d['SPLSCN_CAT']) 
						&& ($r['PPSN2_PSNNO'] == $d['SPLSCN_DOC']) 
						){
							$think2 = true;
							while($think2){
								if($r['PPSN2_REQQT'] > $r['TTLSCN']){
									if($d['USED']==false){
										$isfound = false;
										foreach($rsfix as &$t){
											if( ( $t["PPSN2_MC"] ==$r["PPSN2_MC"])  && ( $t["SPLSCN_ORDERNO"] == $r["PPSN2_MCZ"]) 
											&& ( $t["SPLSCN_ITMCD"] == $r["PPSN2_SUBPN"])  && ( $t["PPSN2_PROCD"] == $r["PPSN2_PROCD"]) 
												){
												$r['TTLSCN']+=$d['SPLSCN_QTY'];
												$rsfix[] = [
													"PPSN2_DATANO" => $r["PPSN2_DATANO"]
													,"SPLSCN_FEDR" => $d["SPLSCN_FEDR"]
													,"SPLSCN_ITMCD" => $d["SPLSCN_ITMCD"]
													,"SPLSCN_QTY" => $d["SPLSCN_QTY"]													
													,"SPLSCN_LOTNO" => $d["SPLSCN_LOTNO"]
													,"SPLSCN_ORDERNO" => $d["SPLSCN_ORDERNO"]
													,"SPLSCN_LINE" => $d["SPLSCN_LINE"]
													,"PPSN2_MC" => $r["PPSN2_MC"]
													,"PPSN2_PROCD" => $r["PPSN2_PROCD"]
													,"SPLSCN_CAT" => $d['SPLSCN_CAT']
													,"SPLSCN_DOC" => $r['PPSN2_PSNNO']
													,"SCNTIME" => $d['SPLSCN_LUPDT'] 
													,"PPSN2_MSFLG" => $r['PPSN2_MSFLG']	
												];
												$isfound=true;
												$d['USED'] = true;
												break;
											}
										}
										unset($t);

										if(!$isfound){
											$rsfix[]=[
												"PPSN2_DATANO" => $r["PPSN2_DATANO"]
												, "SPLSCN_FEDR" => $d["SPLSCN_FEDR"]
												,"SPLSCN_ITMCD" => $d["SPLSCN_ITMCD"]
												, "SPLSCN_QTY" => $d["SPLSCN_QTY"]												
												, "SPLSCN_LOTNO" => $d["SPLSCN_LOTNO"]
												,"SPLSCN_ORDERNO" => $d["SPLSCN_ORDERNO"]
												,"SPLSCN_LINE" => $d["SPLSCN_LINE"]
												,"PPSN2_MC" => $r["PPSN2_MC"]
												, "PPSN2_PROCD" => $r["PPSN2_PROCD"]
												,"SPLSCN_CAT" => $d['SPLSCN_CAT']
												,"SPLSCN_DOC" => $r['PPSN2_PSNNO']
												, "SCNTIME" => $d['SPLSCN_LUPDT']
												, "PPSN2_MSFLG" => $r['PPSN2_MSFLG']
											];
											$r['TTLSCN'] += $d['SPLSCN_QTY'];
											$d['USED'] = true;												
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

		//NEW LOGIC
		//SET SHIELDPLATE qty = Balance
		foreach($rsbase as $d){			
			$bal = $d['PPSN2_REQQT'] - $d['TTLSCN'];
			if($bal >0) {
				if(strpos($d['MITM_ITMD1'],'SHIELD PLATE')!==false)
				// if($d['PPSN2_SUBPN']==="163428400" || $d['PPSN2_SUBPN']==="140939500" || $d['PPSN2_SUBPN'] ==="179355900" || $d['PPSN2_SUBPN'] ==="179356000" 
				// || $d['PPSN2_SUBPN'] === "175998300" || $d['PPSN2_SUBPN'] === "175998400"
				// || $d['PPSN2_SUBPN'] === "169701300" || $d['PPSN2_SUBPN'] === "160950601"
				// || $d['PPSN2_SUBPN'] === "169697901" || $d['PPSN2_SUBPN'] === "175998301") 
				{ 
					$rsfix[] = [
						"PPSN2_DATANO" => $d["PPSN2_DATANO"]
						,"SPLSCN_FEDR" => $d["PPSN2_FR"]
						,"SPLSCN_ITMCD" => $d["PPSN2_SUBPN"]
						,"SPLSCN_QTY" => $bal
						,"SPLSCN_LOTNO" => 'AUTO'
						,"SPLSCN_ORDERNO" => $d["PPSN2_MCZ"]
						,"SPLSCN_LINE" => $d["PPSN2_LINENO"]
						,"PPSN2_MC" => $d["PPSN2_MC"]
						,"PPSN2_PROCD" => $d["PPSN2_PROCD"]
						,"SPLSCN_CAT" => $d['PPSN2_ITMCAT']
						,"SPLSCN_DOC" => $d['PPSN2_PSNNO']
						,"SCNTIME" => NULL
						,"PPSN2_MSFLG" => $d['PPSN2_MSFLG']
					];
				} else {
					//NEW LOGIC
					//SET ASAHI, TOYO, STANLEY KITTING DATA AUTOCOMPLETE
					if($d['PPSN2_BSGRP'] === 'PSI2PPZTDI' || $d['PPSN2_BSGRP'] === 'PSI2PPZADI' || $d['PPSN2_BSGRP'] === 'PSI2PPZSTY') {
						$rsfix[] = [
							"PPSN2_DATANO" => $d["PPSN2_DATANO"]
							,"SPLSCN_FEDR" => $d["PPSN2_FR"]
							,"SPLSCN_ITMCD" => $d["PPSN2_SUBPN"]
							,"SPLSCN_QTY" => $bal
							,"SPLSCN_LOTNO" => '25AUTO'
							,"SPLSCN_ORDERNO" => $d["PPSN2_MCZ"]
							,"SPLSCN_LINE" => $d["PPSN2_LINENO"]
							,"PPSN2_MC" => $d["PPSN2_MC"]
							,"PPSN2_PROCD" => $d["PPSN2_PROCD"]
							,"SPLSCN_CAT" => $d['PPSN2_ITMCAT']
							,"SPLSCN_DOC" => $d['PPSN2_PSNNO']
							,"SCNTIME" => NULL
							,"PPSN2_MSFLG" => $d['PPSN2_MSFLG']
						];
					}
				}
			}
		}

		#include SPLREFF
		if(count($rsscn_reff)) {
			$rsfix = array_merge($rsfix, $rsscn_reff);
		}
		#END
		
		return $rsfix;
	}

	public function get_usage_rm_perjob($pjob){
		#version : 1.4
		#-two ways SA Part
		#version : 1.3
		date_default_timezone_set('Asia/Jakarta');
		$crnt_dt = date('Y-m-d H:i:s');
		$cuserid = $this->CI->session->userdata('nama');
		$rspsn = $this->CI->SPL_mod->select_z_getpsn_byjob("'".$pjob."'");
		$myar = [];
		$strpsn = '';
		$strdocno = '';
		$apsn = [];
		$strmdlcd = '';
		$BSGRP = '';
		$deletedSimulation = ['22-6AG28-222011501','21-XA08-221093101ES'];
		foreach($rspsn as $r){
			$strpsn .= "'".$r['PPSN1_PSNNO']."',";
			$apsn[] = $r['PPSN1_PSNNO'];
			$strdocno = $r['PPSN1_DOCNO'];
			$strmdlcd = $r['PPSN1_MDLCD'];
			$BSGRP = $r['PPSN1_BSGRP'];
		}
		$strpsn = substr($strpsn,0,strlen($strpsn)-1);
		if(trim($strpsn)!=''){
			$rsMSPP = $this->CI->MSPP_mod->select_byvar_for_calc($strmdlcd, $pjob);
			$xrssub = $this->CI->MSPP_mod->select_all_byvar_group(["MSPP_ACTIVE" => "Y", "MSPP_MDLCD !=" => $strmdlcd]);			
			$rsSpecial = $this->CI->MITMSA_mod->select_where(['RTRIM(MITMSA_ITMCD) MITMSA_ITMCD', 'RTRIM(MITMSA_ITMCDS) MITMSA_ITMCDS'], ['MITMSA_MDLCD' => $strmdlcd,'MITMSA_DELDT' => NULL]);
			$rspsnjob_req = in_array($pjob, $deletedSimulation) ? $this->CI->SPL_mod->select_psnjob_req_from_CIMS($pjob, $strmdlcd)
				: $this->CI->SPL_mod->select_psnjob_req($strdocno, $pjob);
			$rsWOM = $this->CI->PWOP_mod->select_mainsub($pjob);
			$rsSPLREFF = $this->CI->SPLREFF_mod->select_mainsub($apsn);
			#Is SubAssy ?
			if($this->CI->MSTITM_mod->check_Primary_subassy(['PWOP_MDLCD' => $strmdlcd])){
				$rspsn_kit = $this->CI->SPLSCN_mod->select_spl_vs_splscn_non_fg_as_bom_null($strpsn);
				if(count($rspsn_kit)>0){
					$myar[] = ['cd' => 0, 'msg' => 'not complete kitting sub assy', "data" => $rspsn_kit];
					return('{"status": '.json_encode($myar).'}');
				}
				$rspsn_sup = $this->tobexported_list_for_serd_subassy($apsn);
			} else {
				if ($BSGRP==='PSI2PPZTDI' || $BSGRP==='PSI2PPZADI' || $BSGRP==='PSI2PPZSTY') {	
					//NEW LOGIC
					//DATE : 2021-09-16
					//SET ASAHI, TOYO, STANLEY KITTING DATA AUTOCOMPLETE						
					//PIC : HADI, ZEFRI, GUSTI
				} else {
					$rspsn_kit = $this->CI->SPLSCN_mod->select_spl_vs_splscn_null($strpsn);
					if(count($rspsn_kit)>0){
						$distinct_item = [];
						foreach($rspsn_kit as $r){
							$distinct_item[$r['SPL_ITMCD']] = $r['SPL_ITMCD'];
						}
						$isnullvalue_required = false;
						foreach($distinct_item as $r){
							foreach($rspsnjob_req as $n){
								if($r==$n['PIS3_MPART'] || $r==$n['PIS3_ITMCD']){
									$isnullvalue_required = true;
									break;
								}
							}
							if($isnullvalue_required){
								break;
							}
						}

						if($isnullvalue_required ){
							$isInSPLReffExist = false;
							#check SPLREFF list
							foreach($distinct_item as $r){
								foreach($rsSPLREFF as $f) {
									if($r==$f['SPLREFF_REQ_PART']) {
										$isInSPLReffExist=true;break;
									}
								}
							}
							#END
							if(!$isInSPLReffExist){
								$myar[] = ['cd' => 0, 'msg' => 'not complete kitting', "data" => $rspsn_kit];
								return('{"status": '.json_encode($myar).'}');
							}
						}
					}
				} 
				$rspsn_sup = $this->tobexported_list_for_serd($apsn);
			}			

			// $myar[] = ['cd' => 0, 'msg' => 'DEBUG', 'rspsn_sup' => $rspsn_sup, 'rspsnjob_req' => $rspsnjob_req];
			// 	return('{"status": '.json_encode($myar).'}');
			
			if(count($rspsnjob_req)==0){				
				$myar[] = ['cd' => 0, 'msg' => 'pis3 null'];
				return('{"status": '.json_encode($myar).'}');				
			}
			foreach($rspsnjob_req as &$n){
				if(!array_key_exists("SUPQTY", $n)){
					$n["SUPQTY"] = 0;
				}
			}
			unset($n);			 
			$rsused = $this->CI->SERD_mod->selectall_where_psn_in($apsn);			
				
			#decrease usage by Used RM of another JOB
			foreach($rsused as &$d){
				if(!array_key_exists("USED", $d)){
					$d["USED"] = false;					
				}
			}
			unset($d);	
			
			
			foreach($rspsn_sup as &$r){
				$r['SPLSCN_QTY_BAK'] = $r['SPLSCN_QTY'];
				foreach($rsused as &$k){
					if($r['SPLSCN_FEDR'] == trim($k['SERD_FR'])
					&& $r['SPLSCN_DOC'] == trim($k['SERD_PSNNO'])
					&& $r['PPSN2_MSFLG'] == trim($k['SERD_MSFLG'])
					&& $r['SPLSCN_ITMCD'] == trim($k['SERD_ITMCD'])
					&& $r['SPLSCN_LOTNO'] == trim($k['SERD_LOTNO'])
					&& $r['SPLSCN_ORDERNO'] == trim($k['SERD_MCZ'])					
					&& $r['PPSN2_MC'] == trim($k['SERD_MC'])					
					&& $r['PPSN2_PROCD'] == trim($k['SERD_PROCD'])
					&& !$k['USED'] )
					{
						if(intval($r['SPLSCN_QTY'])>$k['SERD_QTY']){
							$r['SPLSCN_QTY'] -= $k['SERD_QTY'];
							$k['SERD_QTY'] = 0;
							$k['USED']=true;
							$r['SPLSCN_QTY_BAK'] = $r['SPLSCN_QTY'];
						} else {
							$k['SERD_QTY'] -= $r['SPLSCN_QTY']*1;
							$r['SPLSCN_QTY'] = 0;
							$r['SPLSCN_QTY_BAK'] = $r['SPLSCN_QTY'];
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

			$rspsn_req_d = [];
			if(count($rspsn_sup)==0){				
				$myar[] = ['cd' => 0, 'msg' => 'kitting process is not completed in WMS'];
				return('{"status": '.json_encode($myar).'}');
			}		
			
			#MAIN CALCULATION
			foreach($rspsnjob_req as &$n){	
				$processs = true;
				while($processs){
					foreach($rspsn_sup as &$x){
						if( $n['PIS3_MPART'] == $x['SPLSCN_ITMCD'] ){
							$process2 = true;
							while($process2){
								if(($n['PIS3_REQQTSUM'] > $n["SUPQTY"]) ){
									if($x['SPLSCN_QTY']>0 ){
										$n["SUPQTY"]+=1;
										$x['SPLSCN_QTY']--;																					
										$qtper = $n['MYPER'][0]=="." ? "0".$n['MYPER'] : $n['MYPER'];
										$islotfound = false;
										foreach($rspsn_req_d as &$u){
											if(($u["SERD_JOB"] == $n["PIS3_WONO"]) 
											&& ($u["SERD_ITMCD"]== $x["SPLSCN_ITMCD"]) 
											&& ($u["SERD_LOTNO"]== $x["SPLSCN_LOTNO"])
											&& ($u['SERD_MC'] == $n['PIS3_MC'])  
											&& ($u['SERD_MCZ'] == $n['PIS3_MCZ']) 
											&& ($u['SERD_PROCD'] == $n['PIS3_PROCD'])
											&& ($u['SERD_FR'] == $n['PIS3_FR'] ) 
											&& ($u['SERD_QTPER'] == $qtper)
											&& ($u['SERD_REMARK'] == 'xfmain' )){
												$u["SERD_QTY"]+=1;
												$islotfound=true;break;
											}
										}
										unset($u);

										if(!$islotfound){
											$rspsn_req_d[] = [
												"SERD_JOB" => $n["PIS3_WONO"]
												,"SERD_QTPER" => $qtper 
												,"SERD_ITMCD" => $x['SPLSCN_ITMCD']
												,"SERD_QTY" => 1 
												,"SERD_LOTNO" => $x['SPLSCN_LOTNO']
												,"SERD_PSNNO" => $x['SPLSCN_DOC']
												,"SERD_LINENO" => $n['PIS3_LINENO']
												,"SERD_CAT" => ''
												,"SERD_FR" => $n['PIS3_FR']
												,"SERD_QTYREQ" => intval($n['PIS3_REQQTSUM'])
												,"SERD_MSCANTM" => $x['SCNTIME']
												,"SERD_MC" => $n['PIS3_MC']
												,"SERD_MCZ" => $n['PIS3_MCZ']
												,"SERD_PROCD" => $n['PIS3_PROCD']
												,"SERD_MSFLG" => $x['PPSN2_MSFLG']
												,"SERD_MPART" => $n['PIS3_MPART']
												,"SERD_USRID" => $cuserid
												, "SERD_LUPDT" => $crnt_dt,
												"SERD_REMARK" => "xfmain" 
											];
											
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
							$processs = false;
						}
					}
					unset ($x);					
				}
			}
			unset($n);

			#sub1 CALCULATION
			foreach($rspsnjob_req as &$n){	
				$processs = ($n['PIS3_REQQTSUM'] > $n["SUPQTY"]) ? true : false;			
				while($processs){
					foreach($rspsn_sup as &$x){
						$issubtituted = false;
						if($BSGRP=='PSI1PPZIEP'){
							foreach($rsMSPP as $y){
								if(substr($y['MSPP_BOMPN'],0,9) == substr($n['PIS3_MPART'],0,9) && substr($y['MSPP_SUBPN'],0,9) == substr($x['SPLSCN_ITMCD'],0,9) && $x['SPLSCN_QTY']>0){
									$issubtituted = true;									
									break;
								}
							}
						} else {
							foreach($rsMSPP as $y){
								if($y['MSPP_BOMPN'] == $n['PIS3_MPART'] && $y['MSPP_SUBPN'] == $x['SPLSCN_ITMCD'] && $x['SPLSCN_QTY']>0){
									$issubtituted = true;									
									break;
								}
							}
						}
						if($issubtituted){
							#first side sub
							$process2 = true;
							while($process2){
								if(($n['PIS3_REQQTSUM'] > $n["SUPQTY"]) ){
									if($x['SPLSCN_QTY']>0 ){
										$n["SUPQTY"]+=1;
										$x['SPLSCN_QTY']--;												
										$qtper = $n['MYPER'][0]=="." ? "0".$n['MYPER'] : $n['MYPER'];
										$islotfound = false;
										foreach($rspsn_req_d as &$u){
											if(($u["SERD_JOB"] == $n["PIS3_WONO"]) 
											&& ($u["SERD_ITMCD"]== $x["SPLSCN_ITMCD"]) 
											&& ($u["SERD_LOTNO"]== $x["SPLSCN_LOTNO"])
											&& ($u['SERD_MC'] == $n['PIS3_MC'])  
											&& ($u['SERD_MCZ'] == $n['PIS3_MCZ']) 
											&& ($u['SERD_PROCD'] == $n['PIS3_PROCD'])																						
											&& ($u['SERD_FR'] == $n['PIS3_FR'] ) 
											&& ($u['SERD_QTPER'] == $qtper)
											&& ($u['SERD_REMARK'] == 'xfsub' )){
												$u["SERD_QTY"]+=1;
												$islotfound=true;break;
											}
										}
										unset($u);

										if(!$islotfound){
											$rspsn_req_d[] = [
												"SERD_JOB" => $n["PIS3_WONO"]
												,"SERD_QTPER" => $qtper 
												,"SERD_ITMCD" => $x['SPLSCN_ITMCD']
												,"SERD_QTY" => 1 
												,"SERD_LOTNO" => $x['SPLSCN_LOTNO']
												,"SERD_PSNNO" => $x['SPLSCN_DOC']
												,"SERD_LINENO" => $n['PIS3_LINENO']
												,"SERD_CAT" => ''
												,"SERD_FR" => $n['PIS3_FR']
												,"SERD_QTYREQ" => intval($n['PIS3_REQQTSUM'])
												,"SERD_MSCANTM" => $x['SCNTIME']
												,"SERD_MC" => $n['PIS3_MC']
												,"SERD_MCZ" => $n['PIS3_MCZ']
												,"SERD_PROCD" => $n['PIS3_PROCD']
												,"SERD_MSFLG" => $x['PPSN2_MSFLG']
												,"SERD_MPART" => $n['PIS3_MPART']
												,"SERD_USRID" => $cuserid
												, "SERD_LUPDT" => $crnt_dt,
												"SERD_REMARK" => "xfsub" 
											];
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
							$processs = false;
						}
					}
					unset ($x);	
				}
			}
			unset($n);

			#sub2 CALCULATION
			foreach($rspsnjob_req as &$n){	
				$processs = ($n['PIS3_REQQTSUM'] > $n["SUPQTY"]) ? true : false;
				while($processs){
					foreach($rspsn_sup as &$x){
						$issubtituted = false;
						foreach($rsMSPP as $y){
							if($y['MSPP_SUBPN'] == $n['PIS3_MPART'] && $y['MSPP_BOMPN'] == $x['SPLSCN_ITMCD'] && $x['SPLSCN_QTY']>0){
								$issubtituted = true;									
								break;
							}
						}
						if($issubtituted){
							#second side sub
							$process2 = true;
							while($process2){
								if(($n['PIS3_REQQTSUM'] > $n["SUPQTY"]) ){
									if($x['SPLSCN_QTY']>0 ){
										$n["SUPQTY"]+=1;
										$x['SPLSCN_QTY']--;																					
										$qtper = $n['MYPER'][0]=="." ? "0".$n['MYPER'] : $n['MYPER'];
										$islotfound = false;
										foreach($rspsn_req_d as &$u){
											if(($u["SERD_JOB"] == $n["PIS3_WONO"]) 
											&& ($u["SERD_ITMCD"]== $x["SPLSCN_ITMCD"]) 
											&& ($u["SERD_LOTNO"]== $x["SPLSCN_LOTNO"])
											&& ($u['SERD_MC'] == $n['PIS3_MC'])  
											&& ($u['SERD_MCZ'] == $n['PIS3_MCZ']) 
											&& ($u['SERD_PROCD'] == $n['PIS3_PROCD'])
											&& ($u['SERD_FR'] == $n['PIS3_FR'] ) 
											&& ($u['SERD_QTPER'] == $qtper)
											&& ($u['SERD_REMARK'] == 'xfsub2' )){
												$u["SERD_QTY"]+=1;
												$islotfound=true;break;
											}
										}
										unset($u);

										if(!$islotfound){
											$rspsn_req_d[] = [
												"SERD_JOB" => $n["PIS3_WONO"]
												,"SERD_QTPER" => $qtper 
												,"SERD_ITMCD" => $x['SPLSCN_ITMCD']
												,"SERD_QTY" => 1 
												,"SERD_LOTNO" => $x['SPLSCN_LOTNO']
												,"SERD_PSNNO" => $x['SPLSCN_DOC']
												,"SERD_LINENO" => $n['PIS3_LINENO']
												,"SERD_CAT" => ''
												,"SERD_FR" => $n['PIS3_FR']
												,"SERD_QTYREQ" => intval($n['PIS3_REQQTSUM'])
												,"SERD_MSCANTM" => $x['SCNTIME']
												,"SERD_MC" => $n['PIS3_MC']
												,"SERD_MCZ" => $n['PIS3_MCZ']
												,"SERD_PROCD" => $n['PIS3_PROCD']
												,"SERD_MSFLG" => $x['PPSN2_MSFLG']
												,"SERD_MPART" => $n['PIS3_MPART']
												,"SERD_USRID" => $cuserid
												, "SERD_LUPDT" => $crnt_dt,
												"SERD_REMARK" => "xfsub2" 
											];
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
							$processs = false;
						}
					}
					unset ($x);	
				}
			}
			unset($n);


			#sub3 CALCULATION
			foreach($rspsnjob_req as &$n){	
				$processs = ($n['PIS3_REQQTSUM'] > $n["SUPQTY"]) ? true : false;
				while($processs){
					foreach($rspsn_sup as &$x){
						$issubtituted =false;
						foreach($xrssub as $y){
							if($y['MSPP_BOMPN'] == $n['PIS3_MPART'] && $y['MSPP_SUBPN'] == $x['SPLSCN_ITMCD'] && $x['SPLSCN_QTY']>0){
								$issubtituted = true;									
								break;
							}
						}
						if($issubtituted){
							#third side sub
							$process2 = true;
							while($process2){
								if(($n['PIS3_REQQTSUM'] > $n["SUPQTY"]) ){
									if($x['SPLSCN_QTY']>0 ){
										$n["SUPQTY"]+=1;
										$x['SPLSCN_QTY']--;																					
										$qtper = $n['MYPER'][0]=="." ? "0".$n['MYPER'] : $n['MYPER'];
										$islotfound = false;
										foreach($rspsn_req_d as &$u){
											if(($u["SERD_JOB"] == $n["PIS3_WONO"]) 
											&& ($u["SERD_ITMCD"]== $x["SPLSCN_ITMCD"]) 
											&& ($u["SERD_LOTNO"]== $x["SPLSCN_LOTNO"])
											&& ($u['SERD_MC'] == $n['PIS3_MC'])  
											&& ($u['SERD_MCZ'] == $n['PIS3_MCZ']) 
											&& ($u['SERD_PROCD'] == $n['PIS3_PROCD'])																						
											&& ($u['SERD_FR'] == $n['PIS3_FR'] ) 
											&& ($u['SERD_QTPER'] == $qtper)
											&& ($u['SERD_REMARK'] == 'xfsub3' )){
												$u["SERD_QTY"]+=1;
												$islotfound=true;break;
											}
										}
										unset($u);

										if(!$islotfound){
											$rspsn_req_d[] = [
												"SERD_JOB" => $n["PIS3_WONO"]
												,"SERD_QTPER" => $qtper 
												,"SERD_ITMCD" => $x['SPLSCN_ITMCD']
												,"SERD_QTY" => 1 
												,"SERD_LOTNO" => $x['SPLSCN_LOTNO']
												,"SERD_PSNNO" => $x['SPLSCN_DOC']
												,"SERD_LINENO" => $n['PIS3_LINENO']
												,"SERD_CAT" => ''
												,"SERD_FR" => $n['PIS3_FR']
												,"SERD_QTYREQ" => intval($n['PIS3_REQQTSUM'])
												,"SERD_MSCANTM" => $x['SCNTIME']
												,"SERD_MC" => $n['PIS3_MC']
												,"SERD_MCZ" => $n['PIS3_MCZ']
												,"SERD_PROCD" => $n['PIS3_PROCD']
												,"SERD_MSFLG" => $x['PPSN2_MSFLG']
												,"SERD_MPART" => $n['PIS3_MPART']
												,"SERD_USRID" => $cuserid
												, "SERD_LUPDT" => $crnt_dt,
												"SERD_REMARK" => "xfsub3" 
											];
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
							$processs = false;
						}
					}
					unset($x);
				}
			}
			unset($n);

			#sub4 CALCULATION
			foreach($rspsnjob_req as &$n){	
				$processs = ($n['PIS3_REQQTSUM'] > $n["SUPQTY"]) ? true : false;
				while($processs){
					foreach($rspsn_sup as &$x){
						$issubtituted =false;
						foreach($xrssub as $y){
							if($y['MSPP_SUBPN'] == $n['PIS3_MPART'] && $y['MSPP_BOMPN'] == $x['SPLSCN_ITMCD'] && $x['SPLSCN_QTY']>0){
								$issubtituted = true;
								break;
							}
						}
						if($issubtituted){
							#fourth side sub
							$process2 = true;
							while($process2){
								if(($n['PIS3_REQQTSUM'] > $n["SUPQTY"]) ){
									if($x['SPLSCN_QTY']>0 ){
										$n["SUPQTY"]+=1;
										$x['SPLSCN_QTY']--;																					
										$qtper = $n['MYPER'][0]=="." ? "0".$n['MYPER'] : $n['MYPER'];
										$islotfound = false;
										foreach($rspsn_req_d as &$u){
											if(($u["SERD_JOB"] == $n["PIS3_WONO"]) 
											&& ($u["SERD_ITMCD"]== $x["SPLSCN_ITMCD"]) 
											&& ($u["SERD_LOTNO"]== $x["SPLSCN_LOTNO"])
											&& ($u['SERD_MC'] == $n['PIS3_MC'])  
											&& ($u['SERD_MCZ'] == $n['PIS3_MCZ']) 
											&& ($u['SERD_PROCD'] == $n['PIS3_PROCD'])
											&& ($u['SERD_FR'] == $n['PIS3_FR'] ) 
											&& ($u['SERD_QTPER'] == $qtper)
											&& ($u['SERD_REMARK'] == 'xfsub4' )){
												$u["SERD_QTY"]+=1;
												$islotfound=true;break;
											}
										}
										unset($u);

										if(!$islotfound){
											$rspsn_req_d[] = [
												"SERD_JOB" => $n["PIS3_WONO"]
												,"SERD_QTPER" => $qtper 
												,"SERD_ITMCD" => $x['SPLSCN_ITMCD']
												,"SERD_QTY" => 1 
												,"SERD_LOTNO" => $x['SPLSCN_LOTNO']
												,"SERD_PSNNO" => $x['SPLSCN_DOC']
												,"SERD_LINENO" => $n['PIS3_LINENO']
												,"SERD_CAT" => ''
												,"SERD_FR" => $n['PIS3_FR']
												,"SERD_QTYREQ" => intval($n['PIS3_REQQTSUM'])
												,"SERD_MSCANTM" => $x['SCNTIME']
												,"SERD_MC" => $n['PIS3_MC']
												,"SERD_MCZ" => $n['PIS3_MCZ']
												,"SERD_PROCD" => $n['PIS3_PROCD']
												,"SERD_MSFLG" => $x['PPSN2_MSFLG']
												,"SERD_MPART" => $n['PIS3_MPART']
												,"SERD_USRID" => $cuserid
												, "SERD_LUPDT" => $crnt_dt,
												"SERD_REMARK" => "xfsub4" 
											];
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
							$processs = false;
						}
					}
					unset($x);
				}
			}
			unset($n);			
			
			#sub5 CALCULATION Special Acceptance
			foreach($rspsnjob_req as &$n){	
				$processs = ($n['PIS3_REQQTSUM'] > $n["SUPQTY"]) ? true : false;
				while($processs){
					foreach($rspsn_sup as &$x){
						$issubtituted = false;
						foreach($rsSpecial as $y){
							if($y['MITMSA_ITMCD'] == $n['PIS3_MPART'] && $y['MITMSA_ITMCDS'] == $x['SPLSCN_ITMCD'] && $x['SPLSCN_QTY']>0){
								$issubtituted = true;
								break;
							}
						}
						if($issubtituted){
							#third side sub
							$process2 = true;
							while($process2){
								if(($n['PIS3_REQQTSUM'] > $n["SUPQTY"]) ){
									if($x['SPLSCN_QTY']>0 ){
										$n["SUPQTY"]+=1;
										$x['SPLSCN_QTY']--;
										$qtper = $n['MYPER'][0]=="." ? "0".$n['MYPER'] : $n['MYPER'];
										$islotfound = false;
										foreach($rspsn_req_d as &$u){
											if(($u["SERD_JOB"] == $n["PIS3_WONO"]) 
											&& ($u["SERD_ITMCD"]== $x["SPLSCN_ITMCD"]) 
											&& ($u["SERD_LOTNO"]== $x["SPLSCN_LOTNO"])
											&& ($u['SERD_MC'] == $n['PIS3_MC'])  
											&& ($u['SERD_MCZ'] == $n['PIS3_MCZ']) 
											&& ($u['SERD_PROCD'] == $n['PIS3_PROCD'])
											&& ($u['SERD_FR'] == $n['PIS3_FR'] ) 
											&& ($u['SERD_QTPER'] == $qtper)
											&& ($u['SERD_REMARK'] == 'SpeAcp' )){
												$u["SERD_QTY"]+=1;
												$islotfound=true;break;
											}
										}
										unset($u);

										if(!$islotfound){
											$rspsn_req_d[] = [
												"SERD_JOB" => $n["PIS3_WONO"]
												,"SERD_QTPER" => $qtper 
												,"SERD_ITMCD" => $x['SPLSCN_ITMCD']
												,"SERD_QTY" => 1 
												,"SERD_LOTNO" => $x['SPLSCN_LOTNO']
												,"SERD_PSNNO" => $x['SPLSCN_DOC']
												,"SERD_LINENO" => $n['PIS3_LINENO']
												,"SERD_CAT" => ''
												,"SERD_FR" => $n['PIS3_FR']
												,"SERD_QTYREQ" => intval($n['PIS3_REQQTSUM'])
												,"SERD_MSCANTM" => $x['SCNTIME']
												,"SERD_MC" => $n['PIS3_MC']
												,"SERD_MCZ" => $n['PIS3_MCZ']
												,"SERD_PROCD" => $n['PIS3_PROCD']
												,"SERD_MSFLG" => $x['PPSN2_MSFLG']
												,"SERD_MPART" => $n['PIS3_MPART']
												,"SERD_USRID" => $cuserid
												, "SERD_LUPDT" => $crnt_dt,
												"SERD_REMARK" => "SpeAcp" 
											];
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
							$processs = false;
						}
					}
					unset($x);
				}
			}
			unset($n);
			#sub5 CALCULATION Special Acceptance two ways
			foreach($rspsnjob_req as &$n){	
				$processs = ($n['PIS3_REQQTSUM'] > $n["SUPQTY"]) ? true : false;
				while($processs){
					foreach($rspsn_sup as &$x){
						$issubtituted = false;
						foreach($rsSpecial as $y){
							if($y['MITMSA_ITMCDS'] == $n['PIS3_MPART'] && $y['MITMSA_ITMCD'] == $x['SPLSCN_ITMCD'] && $x['SPLSCN_QTY']>0){
								$issubtituted = true;
								break;
							}
						}
						if($issubtituted){
							#third side sub
							$process2 = true;
							while($process2){
								if(($n['PIS3_REQQTSUM'] > $n["SUPQTY"]) ){
									if($x['SPLSCN_QTY']>0 ){
										$n["SUPQTY"]+=1;
										$x['SPLSCN_QTY']--;
										$qtper = $n['MYPER'][0]=="." ? "0".$n['MYPER'] : $n['MYPER'];
										$islotfound = false;
										foreach($rspsn_req_d as &$u){
											if(($u["SERD_JOB"] == $n["PIS3_WONO"]) 
											&& ($u["SERD_ITMCD"]== $x["SPLSCN_ITMCD"]) 
											&& ($u["SERD_LOTNO"]== $x["SPLSCN_LOTNO"])
											&& ($u['SERD_MC'] == $n['PIS3_MC'])  
											&& ($u['SERD_MCZ'] == $n['PIS3_MCZ']) 
											&& ($u['SERD_PROCD'] == $n['PIS3_PROCD'])
											&& ($u['SERD_FR'] == $n['PIS3_FR'] ) 
											&& ($u['SERD_QTPER'] == $qtper)
											&& ($u['SERD_REMARK'] == 'SpeAcp' )){
												$u["SERD_QTY"]+=1;
												$islotfound=true;break;
											}
										}
										unset($u);

										if(!$islotfound){
											$rspsn_req_d[] = [
												"SERD_JOB" => $n["PIS3_WONO"]
												,"SERD_QTPER" => $qtper 
												,"SERD_ITMCD" => $x['SPLSCN_ITMCD']
												,"SERD_QTY" => 1 
												,"SERD_LOTNO" => $x['SPLSCN_LOTNO']
												,"SERD_PSNNO" => $x['SPLSCN_DOC']
												,"SERD_LINENO" => $n['PIS3_LINENO']
												,"SERD_CAT" => ''
												,"SERD_FR" => $n['PIS3_FR']
												,"SERD_QTYREQ" => intval($n['PIS3_REQQTSUM'])
												,"SERD_MSCANTM" => $x['SCNTIME']
												,"SERD_MC" => $n['PIS3_MC']
												,"SERD_MCZ" => $n['PIS3_MCZ']
												,"SERD_PROCD" => $n['PIS3_PROCD']
												,"SERD_MSFLG" => $x['PPSN2_MSFLG']
												,"SERD_MPART" => $n['PIS3_MPART']
												,"SERD_USRID" => $cuserid
												, "SERD_LUPDT" => $crnt_dt,
												"SERD_REMARK" => "SpeAcp" 
											];
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
							$processs = false;
						}
					}
					unset($x);
				}
			}
			unset($n);

			#WO Maintenance
			foreach($rspsnjob_req as &$n){
				$processs = ($n['PIS3_REQQTSUM'] > $n["SUPQTY"]) ? true : false;
				while($processs){
					foreach($rspsn_sup as &$x){
						$issubtituted =false;						
						foreach($rsWOM as $y){
							if($y['PWOP_BOMPN'] === $n['PIS3_MPART'] && $y['PWOP_SUBPN'] === $x['SPLSCN_ITMCD'] && $x['SPLSCN_QTY']>0){
								$issubtituted = true;									
								break;
							}
						}
						if($issubtituted){
							#third side sub
							$process2 = true;
							while($process2){
								if(($n['PIS3_REQQTSUM'] > $n["SUPQTY"]) ){
									if($x['SPLSCN_QTY']>0 ){
										$n["SUPQTY"]+=1;
										$x['SPLSCN_QTY']--;																					
										$qtper = $n['MYPER'][0]=="." ? "0".$n['MYPER'] : $n['MYPER'];
										$islotfound = false;
										foreach($rspsn_req_d as &$u){
											if(($u["SERD_JOB"] == $n["PIS3_WONO"]) 
											&& ($u["SERD_ITMCD"]== $x["SPLSCN_ITMCD"]) 
											&& ($u["SERD_LOTNO"]== $x["SPLSCN_LOTNO"])
											&& ($u['SERD_MC'] == $n['PIS3_MC'])  
											&& ($u['SERD_MCZ'] == $n['PIS3_MCZ']) 
											&& ($u['SERD_PROCD'] == $n['PIS3_PROCD'])
											&& ($u['SERD_FR'] == $n['PIS3_FR'] ) 
											&& ($u['SERD_QTPER'] == $qtper)
											&& ($u['SERD_REMARK'] == 'WOM' )){
												$u["SERD_QTY"]+=1;
												$islotfound=true;break;
											}
										}
										unset($u);

										if(!$islotfound){
											$rspsn_req_d[] = [
												"SERD_JOB" => $n["PIS3_WONO"]
												,"SERD_QTPER" => $qtper 
												,"SERD_ITMCD" => $x['SPLSCN_ITMCD']
												,"SERD_QTY" => 1 
												,"SERD_LOTNO" => $x['SPLSCN_LOTNO']
												,"SERD_PSNNO" => $x['SPLSCN_DOC']
												,"SERD_LINENO" => $n['PIS3_LINENO']
												,"SERD_CAT" => ''
												,"SERD_FR" => $n['PIS3_FR']
												,"SERD_QTYREQ" => intval($n['PIS3_REQQTSUM'])
												,"SERD_MSCANTM" => $x['SCNTIME']
												,"SERD_MC" => $n['PIS3_MC']
												,"SERD_MCZ" => $n['PIS3_MCZ']
												,"SERD_PROCD" => $n['PIS3_PROCD']
												,"SERD_MSFLG" => $x['PPSN2_MSFLG']
												,"SERD_MPART" => $n['PIS3_MPART']
												,"SERD_USRID" => $cuserid
												, "SERD_LUPDT" => $crnt_dt,
												"SERD_REMARK" => "WOM" 
											];
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
							$processs = false;
						}
					}
					unset($x);
				}
			}
			unset($n);

			#SPLREFF
			foreach($rspsnjob_req as &$n){
				$processs = ($n['PIS3_REQQTSUM'] > $n["SUPQTY"]) ? true : false;
				if($processs){
					foreach($rspsn_sup as &$x){						
						$issubtituted = false;
						foreach($rsSPLREFF as $y){
							if($y['SPLREFF_REQ_PART'] === $n['PIS3_MPART'] && $y['SPLREFF_ACT_PART'] === $x['SPLSCN_ITMCD'] && $x['SPLSCN_QTY']>0){
								$issubtituted = true;
								break;
							}
						}
						if($issubtituted){
							#third side sub							
							$balreq = $n['PIS3_REQQTSUM'] - $n["SUPQTY"];							
							if($balreq && $x['SPLSCN_QTY']>0){
								$serdqty = 0;
								$qtper = $n['MYPER'][0]=="." ? "0".$n['MYPER'] : $n['MYPER'];								
								if($balreq >$x['SPLSCN_QTY'] ){
									$n['SUPQTY'] +=  $x['SPLSCN_QTY'];
									$serdqty = (int)$x['SPLSCN_QTY'];
									$x['SPLSCN_QTY'] = 0;
								} else {
									$x['SPLSCN_QTY']-=$balreq;
									$n['SUPQTY'] += $balreq;
									$serdqty = $balreq;
								}

								$rspsn_req_d[] = [
									"SERD_JOB" => $n["PIS3_WONO"]
									,"SERD_QTPER" => $qtper 
									,"SERD_ITMCD" => $x['SPLSCN_ITMCD']
									,"SERD_QTY" => $serdqty
									,"SERD_LOTNO" => $x['SPLSCN_LOTNO']
									,"SERD_PSNNO" => $x['SPLSCN_DOC']
									,"SERD_LINENO" => $n['PIS3_LINENO']
									,"SERD_CAT" => ''
									,"SERD_FR" => $n['PIS3_FR']
									,"SERD_QTYREQ" => intval($n['PIS3_REQQTSUM'])
									,"SERD_MSCANTM" => $x['SCNTIME']
									,"SERD_MC" => $n['PIS3_MC']
									,"SERD_MCZ" => $n['PIS3_MCZ']
									,"SERD_PROCD" => $n['PIS3_PROCD']
									,"SERD_MSFLG" => $x['PPSN2_MSFLG']
									,"SERD_MPART" => $n['PIS3_MPART']
									,"SERD_USRID" => $cuserid
									, "SERD_LUPDT" => $crnt_dt,
									"SERD_REMARK" => "splreff" 
								];
							} 							
						}
					}
					unset($x);
				}
			}
			unset($n);
			

			#MAX RUN Logic		
				$distinct_ost_item_only = [];
				$distinct_ost_item = [];			
				$mxr_item_suplied = [];
				#GET OST ITEM
				foreach($rspsnjob_req as $n){
					if($n['PIS3_REQQTSUM'] > $n["SUPQTY"]){
						if(!in_array($n['PIS3_MPART'], $distinct_ost_item_only)) {
							$distinct_ost_item_only[] = $n['PIS3_MPART'];
						}
					}
				}
				#END
				#GET TOTAL LINE OF OST ITEM
				foreach($distinct_ost_item_only as $d) {
					foreach($rspsnjob_req as $r) { 
						if($d===$r['PIS3_MPART']) {
							$isfound = false;
							foreach($distinct_ost_item as &$o) {
								if($d === $o['ITEMCD']) {
									$o['COUNT']+=$r['MYPER'];
									$o['COUNTROW']++;
									$isfound = true;
									break;
								}
							}
							unset($o);
							if(!$isfound) {
								$distinct_ost_item[] = [
									'ITEMCD' => $d
									,'COUNT' => $r['MYPER']
									,'COUNTROW' => 1
								];
							}
						}
					}
				}
				#END

				#GET TOTAL SUPPLIED
				foreach($rspsn_sup as $r) {
					$isfound = false;
					foreach($mxr_item_suplied as &$k) {
						if($r['SPLSCN_ITMCD']===$k['ITEM']) {
							$k['QTY']+=$r['SPLSCN_QTY_BAK'];
							$isfound=true;break;
						}
					}        
					unset($k);
					if(!$isfound) {
						$mxr_item_suplied[] = ['ITEM' => $r['SPLSCN_ITMCD'], 'QTY' => $r['SPLSCN_QTY_BAK']];
					}
				}
				#END

				#RESET REQCALCULATION & SUPCALCULATION FOR SPECIFIC ITEM
				foreach($rspsnjob_req as &$n) {
					$isfound = false;
					foreach($distinct_ost_item as $k) {
						if($k['ITEMCD']===$n['PIS3_MPART'] &&  $k['COUNTROW']>1) {
							$isfound = true;
							break;
						}
					}
					if($isfound) {
						$n['SUPQTY']=0;
					}
				}
				unset($n);

				foreach($rspsn_req_d as $n => $val) {
					foreach($distinct_ost_item as $k ) {
						if($k['ITEMCD']===$val['SERD_ITMCD'] &&  $k['COUNTROW']>1) {
							unset($rspsn_req_d[$n]);
						}
					}
				}    
				#END

				#RECALCULATE FOR SPECIFIC ITEM
				foreach($rspsnjob_req as &$n) { 
					if($n['PIS3_REQQTSUM'] > $n["SUPQTY"]) {
						$maxsup = 0;
						$tmount = 0;
						foreach($distinct_ost_item as $o){
							if($n['PIS3_MPART']==$o['ITEMCD']) {
								$tmount = $o['COUNT'];
								break;
							}
						}						
						if($tmount) {                
							foreach($mxr_item_suplied as $s) {
								if($n['PIS3_MPART']===$s['ITEM']) {
									$maxsup = floor($n['MYPER']/$tmount*$s['QTY']);
								}
							}							
							if($maxsup) {                    
								foreach($rspsn_sup as &$x){
									$balreq = $maxsup - $n["SUPQTY"];
									
									if( $n['PIS3_MPART'] == $x['SPLSCN_ITMCD'] && $balreq && $x['SPLSCN_QTY_BAK']>0){                             
										$qtper = $n['MYPER'][0]=="." ? "0".$n['MYPER'] : $n['MYPER'];
										$serdqty = 0;
										if($balreq>$x['SPLSCN_QTY_BAK']) {
											$n['SUPQTY'] +=  $x['SPLSCN_QTY_BAK'];
											$serdqty = (int)$x['SPLSCN_QTY_BAK'];
											$x['SPLSCN_QTY_BAK'] = 0;
										} else {
											$x['SPLSCN_QTY_BAK']-=$balreq;
											$n['SUPQTY'] += $balreq;
											$serdqty = $balreq;
										}
										if($serdqty<0){
											$serdqty= abs($serdqty);											
										}
										$rspsn_req_d[] = [											
											"SERD_JOB" => $n["PIS3_WONO"]
											,"SERD_QTPER" => $qtper 
											,"SERD_ITMCD" => $x['SPLSCN_ITMCD']
											,"SERD_QTY" => $serdqty
											,"SERD_LOTNO" => $x['SPLSCN_LOTNO']
											,"SERD_PSNNO" => $x['SPLSCN_DOC']
											,"SERD_LINENO" => $n['PIS3_LINENO']
											,"SERD_CAT" => ''
											,"SERD_FR" => $n['PIS3_FR']
											,"SERD_QTYREQ" => intval($n['PIS3_REQQTSUM'])
											,"SERD_MSCANTM" => $x['SCNTIME']
											,"SERD_MC" => $n['PIS3_MC']
											,"SERD_MCZ" => $n['PIS3_MCZ']
											,"SERD_PROCD" => $n['PIS3_PROCD']
											,"SERD_MSFLG" => $x['PPSN2_MSFLG']
											,"SERD_MPART" => $n['PIS3_MPART']
											,"SERD_USRID" => $cuserid
											, "SERD_LUPDT" => $crnt_dt,
											"SERD_REMARK" => "maxrun" 
										];
									}
								}
								unset($x);
							}
						}
					}
				}
				unset($n);
				#END
			#MAX RUN END
			
			if(count($rspsn_req_d) >0){
				$this->CI->SERD_mod->insertb($rspsn_req_d);
				$myar[] = ['cd' => 1, 'msg' => 'ok inserted ?'];
				return('{"status" : '.json_encode($myar).'}');
			} else {
				$myar[] = ['cd' => 0, 'msg' => 'calculated result is 0'];
				return('{"status" : '.json_encode($myar).'}');
			}
		} else {
			$myar[]= ['cd' => 0, 'msg' => 'PSN is not found'];
			return('{"status" : '.json_encode($myar).'}');
		}
	}
	public function get_usage_rm_perjob_base_pwop($pjob){
		#version : 1.3
		date_default_timezone_set('Asia/Jakarta');
		$crnt_dt = date('Y-m-d H:i:s');
		$cuserid = $this->CI->session->userdata('nama');
		$rspsn = $this->CI->SPL_mod->select_z_getpsn_byjob("'".$pjob."'");
		$myar = [];
		$strpsn = '';
		$strdocno = '';
		$apsn = [];
		$strmdlcd = '';
		$BSGRP = '';
		foreach($rspsn as $r){
			$strpsn .= "'".$r['PPSN1_PSNNO']."',";
			$apsn[] = $r['PPSN1_PSNNO'];
			$strdocno = $r['PPSN1_DOCNO'];
			$strmdlcd = $r['PPSN1_MDLCD'];
			$BSGRP = $r['PPSN1_BSGRP'];
		}
		$strpsn = substr($strpsn,0,strlen($strpsn)-1);
		if(trim($strpsn)!=''){
			$rsMSPP = $this->CI->MSPP_mod->select_byvar_for_calc($strmdlcd, $pjob);
			$xrssub = $this->CI->MSPP_mod->select_all_byvar_group(["MSPP_ACTIVE" => "Y", "MSPP_MDLCD !=" => $strmdlcd]);
			$rsSpecial = $this->CI->MITMSA_mod->select_where(['RTRIM(MITMSA_ITMCD) MITMSA_ITMCD', 'MITMSA_ITMCDS'], ['MITMSA_MDLCD' => $strmdlcd]);
			$rspsnjob_req = $this->CI->SPL_mod->select_psnjob_req($strdocno, $pjob);
			$rsWOM = $this->CI->PWOP_mod->select_mainsub($pjob);
			$rsSPLREFF = $this->CI->SPLREFF_mod->select_mainsub($apsn);
			#Is SubAssy ?
			if($this->CI->MSTITM_mod->check_Primary_subassy(['PWOP_MDLCD' => $strmdlcd])){
				$rspsn_kit = $this->CI->SPLSCN_mod->select_spl_vs_splscn_non_fg_as_bom_null($strpsn);
				if(count($rspsn_kit)>0){
					$myar[] = ['cd' => 0, 'msg' => 'not complete kitting sub assy', "data" => $rspsn_kit];
					return('{"status": '.json_encode($myar).'}');
				}
				$rspsn_sup = $this->tobexported_list_for_serd_subassy($apsn);
			} else {
				if ($BSGRP==='PSI2PPZTDI' || $BSGRP==='PSI2PPZADI' || $BSGRP==='PSI2PPZSTY') {	
					//NEW LOGIC
					//DATE : 2021-09-16
					//SET ASAHI, TOYO, STANLEY KITTING DATA AUTOCOMPLETE						
					//PIC : HADI, ZEFRI, GUSTI
				} else {
					$rspsn_kit = $this->CI->SPLSCN_mod->select_spl_vs_splscn_null($strpsn);
					if(count($rspsn_kit)>0){
						$distinct_item = [];
						foreach($rspsn_kit as $r){
							$distinct_item[$r['SPL_ITMCD']] = $r['SPL_ITMCD'];
						}
						$isnullvalue_required = false;
						foreach($distinct_item as $r){
							foreach($rspsnjob_req as $n){
								if($r==$n['PIS3_MPART'] || $r==$n['PIS3_ITMCD']){
									$isnullvalue_required = true;
									break;
								}
							}
							if($isnullvalue_required){
								break;
							}
						}

						if($isnullvalue_required ){
							$isInSPLReffExist = false;
							#check SPLREFF list
							foreach($distinct_item as $r){
								foreach($rsSPLREFF as $f) {
									if($r==$f['SPLREFF_REQ_PART']) {
										$isInSPLReffExist=true;break;
									}
								}
							}
							#END
							if(!$isInSPLReffExist){
								$myar[] = ['cd' => 0, 'msg' => 'not complete kitting', "data" => $rspsn_kit];
								return('{"status": '.json_encode($myar).'}');
							}
						}
					}					
				} 
				$rspsn_sup = $this->tobexported_list_for_serd($apsn);
			}
			
			if(count($rspsnjob_req)==0){
				$myar[] = ['cd' => 0, 'msg' => 'pis3 null'];
				return('{"status": '.json_encode($myar).'}');
			}
			foreach($rspsnjob_req as &$n){
				if(!array_key_exists("SUPQTY", $n)){
					$n["SUPQTY"] = 0;
				}
			}
			unset($n);			 
			$rsused = $this->CI->SERD_mod->selectall_where_psn_in($apsn);			
				
			#decrease usage by Used RM of another JOB
			foreach($rsused as &$d){
				if(!array_key_exists("USED", $d)){
					$d["USED"] = false;					
				}
			}
			unset($d);								
			foreach($rspsn_sup as &$r){
				$r['SPLSCN_QTY_BAK'] = $r['SPLSCN_QTY'];
				foreach($rsused as &$k){
					if($r['SPLSCN_FEDR'] == trim($k['SERD_FR'])
					&& $r['SPLSCN_DOC'] == trim($k['SERD_PSNNO'])
					&& $r['PPSN2_MSFLG'] == trim($k['SERD_MSFLG'])
					&& $r['SPLSCN_ITMCD'] == trim($k['SERD_ITMCD'])
					&& $r['SPLSCN_LOTNO'] == trim($k['SERD_LOTNO'])
					&& $r['SPLSCN_ORDERNO'] == trim($k['SERD_MCZ'])
					&& $r['SPLSCN_LINE'] == trim($k['SERD_LINENO'])
					&& $r['PPSN2_MC'] == trim($k['SERD_MC'])					
					&& $r['PPSN2_PROCD'] == trim($k['SERD_PROCD'])
					&& !$k['USED'] )
					{
						if(intval($r['SPLSCN_QTY'])>$k['SERD_QTY']){
							$r['SPLSCN_QTY'] -= $k['SERD_QTY'];
							$k['SERD_QTY'] = 0;
							$k['USED']=true;
							$r['SPLSCN_QTY_BAK'] = $r['SPLSCN_QTY'];
						} else {
							$k['SERD_QTY'] -= $r['SPLSCN_QTY']*1;
							$r['SPLSCN_QTY'] = 0;
							$r['SPLSCN_QTY_BAK'] = $r['SPLSCN_QTY'];
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

			$rspsn_req_d = [];
			if(count($rspsn_sup)==0){				
				$myar[] = ['cd' => 0, 'msg' => 'kitting process is not completed in WMS'];
				return('{"status": '.json_encode($myar).'}');
			}			
			#MAIN CALCULATION
			foreach($rspsnjob_req as &$n){	
				$processs = true;
				while($processs){
					foreach($rspsn_sup as &$x){
						if( $n['PIS3_MPART'] == $x['SPLSCN_ITMCD'] ){
							$process2 = true;
							while($process2){
								if(($n['PIS3_REQQTSUM'] > $n["SUPQTY"]) ){
									if($x['SPLSCN_QTY']>0 ){
										$n["SUPQTY"]+=1;
										$x['SPLSCN_QTY']--;																					
										$qtper = $n['MYPER'][0]=="." ? "0".$n['MYPER'] : $n['MYPER'];
										$islotfound = false;
										foreach($rspsn_req_d as &$u){
											if(($u["SERD_JOB"] == $n["PIS3_WONO"]) 
											&& ($u["SERD_ITMCD"]== $x["SPLSCN_ITMCD"]) 
											&& ($u["SERD_LOTNO"]== $x["SPLSCN_LOTNO"])
											&& ($u['SERD_MC'] == $n['PIS3_MC'])  
											&& ($u['SERD_MCZ'] == $n['PIS3_MCZ']) 
											&& ($u['SERD_PROCD'] == $n['PIS3_PROCD'])
											&& ($u['SERD_FR'] == $n['PIS3_FR'] ) 
											&& ($u['SERD_QTPER'] == $qtper)
											&& ($u['SERD_REMARK'] == 'xfmain' )){
												$u["SERD_QTY"]+=1;
												$islotfound=true;break;
											}
										}
										unset($u);

										if(!$islotfound){
											$rspsn_req_d[] = [
												"SERD_JOB" => $n["PIS3_WONO"]
												,"SERD_QTPER" => $qtper 
												,"SERD_ITMCD" => $x['SPLSCN_ITMCD']
												,"SERD_QTY" => 1 
												,"SERD_LOTNO" => $x['SPLSCN_LOTNO']
												,"SERD_PSNNO" => $x['SPLSCN_DOC']
												,"SERD_LINENO" => $n['PIS3_LINENO']
												,"SERD_CAT" => ''
												,"SERD_FR" => $n['PIS3_FR']
												,"SERD_QTYREQ" => intval($n['PIS3_REQQTSUM'])
												,"SERD_MSCANTM" => $x['SCNTIME']
												,"SERD_MC" => $n['PIS3_MC']
												,"SERD_MCZ" => $n['PIS3_MCZ']
												,"SERD_PROCD" => $n['PIS3_PROCD']
												,"SERD_MSFLG" => $x['PPSN2_MSFLG']
												,"SERD_MPART" => $n['PIS3_MPART']
												,"SERD_USRID" => $cuserid
												, "SERD_LUPDT" => $crnt_dt,
												"SERD_REMARK" => "xfmain" 
											];
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
							$processs = false;
						}
					}
					unset ($x);					
				}
			}
			unset($n);

			#sub1 CALCULATION
			foreach($rspsnjob_req as &$n){	
				$processs = ($n['PIS3_REQQTSUM'] > $n["SUPQTY"]) ? true : false;			
				while($processs){
					foreach($rspsn_sup as &$x){
						$issubtituted = false;
						if($BSGRP=='PSI1PPZIEP'){
							foreach($rsMSPP as $y){
								if(substr($y['MSPP_BOMPN'],0,9) == substr($n['PIS3_MPART'],0,9) && substr($y['MSPP_SUBPN'],0,9) == substr($x['SPLSCN_ITMCD'],0,9) && $x['SPLSCN_QTY']>0){
									$issubtituted = true;									
									break;
								}
							}
						} else {
							foreach($rsMSPP as $y){
								if($y['MSPP_BOMPN'] == $n['PIS3_MPART'] && $y['MSPP_SUBPN'] == $x['SPLSCN_ITMCD'] && $x['SPLSCN_QTY']>0){
									$issubtituted = true;									
									break;
								}
							}
						}
						if($issubtituted){
							#first side sub
							$process2 = true;
							while($process2){
								if(($n['PIS3_REQQTSUM'] > $n["SUPQTY"]) ){
									if($x['SPLSCN_QTY']>0 ){
										$n["SUPQTY"]+=1;
										$x['SPLSCN_QTY']--;												
										$qtper = $n['MYPER'][0]=="." ? "0".$n['MYPER'] : $n['MYPER'];
										$islotfound = false;
										foreach($rspsn_req_d as &$u){
											if(($u["SERD_JOB"] == $n["PIS3_WONO"]) 
											&& ($u["SERD_ITMCD"]== $x["SPLSCN_ITMCD"]) 
											&& ($u["SERD_LOTNO"]== $x["SPLSCN_LOTNO"])
											&& ($u['SERD_MC'] == $n['PIS3_MC'])  
											&& ($u['SERD_MCZ'] == $n['PIS3_MCZ']) 
											&& ($u['SERD_PROCD'] == $n['PIS3_PROCD'])																						
											&& ($u['SERD_FR'] == $n['PIS3_FR'] ) 
											&& ($u['SERD_QTPER'] == $qtper)
											&& ($u['SERD_REMARK'] == 'xfsub' )){
												$u["SERD_QTY"]+=1;
												$islotfound=true;break;
											}
										}
										unset($u);

										if(!$islotfound){
											$rspsn_req_d[] = [
												"SERD_JOB" => $n["PIS3_WONO"]
												,"SERD_QTPER" => $qtper 
												,"SERD_ITMCD" => $x['SPLSCN_ITMCD']
												,"SERD_QTY" => 1 
												,"SERD_LOTNO" => $x['SPLSCN_LOTNO']
												,"SERD_PSNNO" => $x['SPLSCN_DOC']
												,"SERD_LINENO" => $n['PIS3_LINENO']
												,"SERD_CAT" => ''
												,"SERD_FR" => $n['PIS3_FR']
												,"SERD_QTYREQ" => intval($n['PIS3_REQQTSUM'])
												,"SERD_MSCANTM" => $x['SCNTIME']
												,"SERD_MC" => $n['PIS3_MC']
												,"SERD_MCZ" => $n['PIS3_MCZ']
												,"SERD_PROCD" => $n['PIS3_PROCD']
												,"SERD_MSFLG" => $x['PPSN2_MSFLG']
												,"SERD_MPART" => $n['PIS3_MPART']
												,"SERD_USRID" => $cuserid
												, "SERD_LUPDT" => $crnt_dt,
												"SERD_REMARK" => "xfsub" 
											];
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
							$processs = false;
						}
					}
					unset ($x);	
				}
			}
			unset($n);

			#sub2 CALCULATION
			foreach($rspsnjob_req as &$n){	
				$processs = ($n['PIS3_REQQTSUM'] > $n["SUPQTY"]) ? true : false;
				while($processs){
					foreach($rspsn_sup as &$x){
						$issubtituted = false;
						foreach($rsMSPP as $y){
							if($y['MSPP_SUBPN'] == $n['PIS3_MPART'] && $y['MSPP_BOMPN'] == $x['SPLSCN_ITMCD'] && $x['SPLSCN_QTY']>0){
								$issubtituted = true;									
								break;
							}
						}
						if($issubtituted){
							#second side sub
							$process2 = true;
							while($process2){
								if(($n['PIS3_REQQTSUM'] > $n["SUPQTY"]) ){
									if($x['SPLSCN_QTY']>0 ){
										$n["SUPQTY"]+=1;
										$x['SPLSCN_QTY']--;																					
										$qtper = $n['MYPER'][0]=="." ? "0".$n['MYPER'] : $n['MYPER'];
										$islotfound = false;
										foreach($rspsn_req_d as &$u){
											if(($u["SERD_JOB"] == $n["PIS3_WONO"]) 
											&& ($u["SERD_ITMCD"]== $x["SPLSCN_ITMCD"]) 
											&& ($u["SERD_LOTNO"]== $x["SPLSCN_LOTNO"])
											&& ($u['SERD_MC'] == $n['PIS3_MC'])  
											&& ($u['SERD_MCZ'] == $n['PIS3_MCZ']) 
											&& ($u['SERD_PROCD'] == $n['PIS3_PROCD'])
											&& ($u['SERD_FR'] == $n['PIS3_FR'] ) 
											&& ($u['SERD_QTPER'] == $qtper)
											&& ($u['SERD_REMARK'] == 'xfsub2' )){
												$u["SERD_QTY"]+=1;
												$islotfound=true;break;
											}
										}
										unset($u);

										if(!$islotfound){
											$rspsn_req_d[] = [
												"SERD_JOB" => $n["PIS3_WONO"]
												,"SERD_QTPER" => $qtper 
												,"SERD_ITMCD" => $x['SPLSCN_ITMCD']
												,"SERD_QTY" => 1 
												,"SERD_LOTNO" => $x['SPLSCN_LOTNO']
												,"SERD_PSNNO" => $x['SPLSCN_DOC']
												,"SERD_LINENO" => $n['PIS3_LINENO']
												,"SERD_CAT" => ''
												,"SERD_FR" => $n['PIS3_FR']
												,"SERD_QTYREQ" => intval($n['PIS3_REQQTSUM'])
												,"SERD_MSCANTM" => $x['SCNTIME']
												,"SERD_MC" => $n['PIS3_MC']
												,"SERD_MCZ" => $n['PIS3_MCZ']
												,"SERD_PROCD" => $n['PIS3_PROCD']
												,"SERD_MSFLG" => $x['PPSN2_MSFLG']
												,"SERD_MPART" => $n['PIS3_MPART']
												,"SERD_USRID" => $cuserid
												, "SERD_LUPDT" => $crnt_dt,
												"SERD_REMARK" => "xfsub2" 
											];
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
							$processs = false;
						}
					}
					unset ($x);	
				}
			}
			unset($n);


			#sub3 CALCULATION
			foreach($rspsnjob_req as &$n){	
				$processs = ($n['PIS3_REQQTSUM'] > $n["SUPQTY"]) ? true : false;
				while($processs){
					foreach($rspsn_sup as &$x){
						$issubtituted =false;
						foreach($xrssub as $y){
							if($y['MSPP_BOMPN'] == $n['PIS3_MPART'] && $y['MSPP_SUBPN'] == $x['SPLSCN_ITMCD'] && $x['SPLSCN_QTY']>0){
								$issubtituted = true;									
								break;
							}
						}
						if($issubtituted){
							#third side sub
							$process2 = true;
							while($process2){
								if(($n['PIS3_REQQTSUM'] > $n["SUPQTY"]) ){
									if($x['SPLSCN_QTY']>0 ){
										$n["SUPQTY"]+=1;
										$x['SPLSCN_QTY']--;																					
										$qtper = $n['MYPER'][0]=="." ? "0".$n['MYPER'] : $n['MYPER'];
										$islotfound = false;
										foreach($rspsn_req_d as &$u){
											if(($u["SERD_JOB"] == $n["PIS3_WONO"]) 
											&& ($u["SERD_ITMCD"]== $x["SPLSCN_ITMCD"]) 
											&& ($u["SERD_LOTNO"]== $x["SPLSCN_LOTNO"])
											&& ($u['SERD_MC'] == $n['PIS3_MC'])  
											&& ($u['SERD_MCZ'] == $n['PIS3_MCZ']) 
											&& ($u['SERD_PROCD'] == $n['PIS3_PROCD'])																						
											&& ($u['SERD_FR'] == $n['PIS3_FR'] ) 
											&& ($u['SERD_QTPER'] == $qtper)
											&& ($u['SERD_REMARK'] == 'xfsub3' )){
												$u["SERD_QTY"]+=1;
												$islotfound=true;break;
											}
										}
										unset($u);

										if(!$islotfound){
											$rspsn_req_d[] = [
												"SERD_JOB" => $n["PIS3_WONO"]
												,"SERD_QTPER" => $qtper 
												,"SERD_ITMCD" => $x['SPLSCN_ITMCD']
												,"SERD_QTY" => 1 
												,"SERD_LOTNO" => $x['SPLSCN_LOTNO']
												,"SERD_PSNNO" => $x['SPLSCN_DOC']
												,"SERD_LINENO" => $n['PIS3_LINENO']
												,"SERD_CAT" => ''
												,"SERD_FR" => $n['PIS3_FR']
												,"SERD_QTYREQ" => intval($n['PIS3_REQQTSUM'])
												,"SERD_MSCANTM" => $x['SCNTIME']
												,"SERD_MC" => $n['PIS3_MC']
												,"SERD_MCZ" => $n['PIS3_MCZ']
												,"SERD_PROCD" => $n['PIS3_PROCD']
												,"SERD_MSFLG" => $x['PPSN2_MSFLG']
												,"SERD_MPART" => $n['PIS3_MPART']
												,"SERD_USRID" => $cuserid
												, "SERD_LUPDT" => $crnt_dt,
												"SERD_REMARK" => "xfsub3" 
											];
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
							$processs = false;
						}
					}
					unset($x);
				}
			}
			unset($n);

			#sub4 CALCULATION
			foreach($rspsnjob_req as &$n){	
				$processs = ($n['PIS3_REQQTSUM'] > $n["SUPQTY"]) ? true : false;
				while($processs){
					foreach($rspsn_sup as &$x){
						$issubtituted =false;
						foreach($xrssub as $y){
							if($y['MSPP_SUBPN'] == $n['PIS3_MPART'] && $y['MSPP_BOMPN'] == $x['SPLSCN_ITMCD'] && $x['SPLSCN_QTY']>0){
								$issubtituted = true;
								break;
							}
						}
						if($issubtituted){
							#fourth side sub
							$process2 = true;
							while($process2){
								if(($n['PIS3_REQQTSUM'] > $n["SUPQTY"]) ){
									if($x['SPLSCN_QTY']>0 ){
										$n["SUPQTY"]+=1;
										$x['SPLSCN_QTY']--;																					
										$qtper = $n['MYPER'][0]=="." ? "0".$n['MYPER'] : $n['MYPER'];
										$islotfound = false;
										foreach($rspsn_req_d as &$u){
											if(($u["SERD_JOB"] == $n["PIS3_WONO"]) 
											&& ($u["SERD_ITMCD"]== $x["SPLSCN_ITMCD"]) 
											&& ($u["SERD_LOTNO"]== $x["SPLSCN_LOTNO"])
											&& ($u['SERD_MC'] == $n['PIS3_MC'])  
											&& ($u['SERD_MCZ'] == $n['PIS3_MCZ']) 
											&& ($u['SERD_PROCD'] == $n['PIS3_PROCD'])
											&& ($u['SERD_FR'] == $n['PIS3_FR'] ) 
											&& ($u['SERD_QTPER'] == $qtper)
											&& ($u['SERD_REMARK'] == 'xfsub4' )){
												$u["SERD_QTY"]+=1;
												$islotfound=true;break;
											}
										}
										unset($u);

										if(!$islotfound){
											$rspsn_req_d[] = [
												"SERD_JOB" => $n["PIS3_WONO"]
												,"SERD_QTPER" => $qtper 
												,"SERD_ITMCD" => $x['SPLSCN_ITMCD']
												,"SERD_QTY" => 1 
												,"SERD_LOTNO" => $x['SPLSCN_LOTNO']
												,"SERD_PSNNO" => $x['SPLSCN_DOC']
												,"SERD_LINENO" => $n['PIS3_LINENO']
												,"SERD_CAT" => ''
												,"SERD_FR" => $n['PIS3_FR']
												,"SERD_QTYREQ" => intval($n['PIS3_REQQTSUM'])
												,"SERD_MSCANTM" => $x['SCNTIME']
												,"SERD_MC" => $n['PIS3_MC']
												,"SERD_MCZ" => $n['PIS3_MCZ']
												,"SERD_PROCD" => $n['PIS3_PROCD']
												,"SERD_MSFLG" => $x['PPSN2_MSFLG']
												,"SERD_MPART" => $n['PIS3_MPART']
												,"SERD_USRID" => $cuserid
												, "SERD_LUPDT" => $crnt_dt,
												"SERD_REMARK" => "xfsub4" 
											];
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
							$processs = false;
						}
					}
					unset($x);
				}
			}
			unset($n);

			
			#sub5 CALCULATION Special Acceptance
			foreach($rspsnjob_req as &$n){	
				$processs = ($n['PIS3_REQQTSUM'] > $n["SUPQTY"]) ? true : false;
				while($processs){
					foreach($rspsn_sup as &$x){
						$issubtituted = false;
						foreach($rsSpecial as $y){
							if($y['MITMSA_ITMCD'] == $n['PIS3_MPART'] && $y['MITMSA_ITMCDS'] == $x['SPLSCN_ITMCD'] && $x['SPLSCN_QTY']>0){
								$issubtituted = true;
								break;
							}
						}
						if($issubtituted){
							#third side sub
							$process2 = true;
							while($process2){
								if(($n['PIS3_REQQTSUM'] > $n["SUPQTY"]) ){
									if($x['SPLSCN_QTY']>0 ){
										$n["SUPQTY"]+=1;
										$x['SPLSCN_QTY']--;
										$qtper = $n['MYPER'][0]=="." ? "0".$n['MYPER'] : $n['MYPER'];
										$islotfound = false;
										foreach($rspsn_req_d as &$u){
											if(($u["SERD_JOB"] == $n["PIS3_WONO"]) 
											&& ($u["SERD_ITMCD"]== $x["SPLSCN_ITMCD"]) 
											&& ($u["SERD_LOTNO"]== $x["SPLSCN_LOTNO"])
											&& ($u['SERD_MC'] == $n['PIS3_MC'])  
											&& ($u['SERD_MCZ'] == $n['PIS3_MCZ']) 
											&& ($u['SERD_PROCD'] == $n['PIS3_PROCD'])
											&& ($u['SERD_FR'] == $n['PIS3_FR'] ) 
											&& ($u['SERD_QTPER'] == $qtper)
											&& ($u['SERD_REMARK'] == 'SpeAcp' )){
												$u["SERD_QTY"]+=1;
												$islotfound=true;break;
											}
										}
										unset($u);

										if(!$islotfound){
											$rspsn_req_d[] = [
												"SERD_JOB" => $n["PIS3_WONO"]
												,"SERD_QTPER" => $qtper 
												,"SERD_ITMCD" => $x['SPLSCN_ITMCD']
												,"SERD_QTY" => 1 
												,"SERD_LOTNO" => $x['SPLSCN_LOTNO']
												,"SERD_PSNNO" => $x['SPLSCN_DOC']
												,"SERD_LINENO" => $n['PIS3_LINENO']
												,"SERD_CAT" => ''
												,"SERD_FR" => $n['PIS3_FR']
												,"SERD_QTYREQ" => intval($n['PIS3_REQQTSUM'])
												,"SERD_MSCANTM" => $x['SCNTIME']
												,"SERD_MC" => $n['PIS3_MC']
												,"SERD_MCZ" => $n['PIS3_MCZ']
												,"SERD_PROCD" => $n['PIS3_PROCD']
												,"SERD_MSFLG" => $x['PPSN2_MSFLG']
												,"SERD_MPART" => $n['PIS3_MPART']
												,"SERD_USRID" => $cuserid
												, "SERD_LUPDT" => $crnt_dt,
												"SERD_REMARK" => "SpeAcp" 
											];
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
							$processs = false;
						}
					}
					unset($x);
				}
			}
			unset($n);

			#WO Maintenance
			foreach($rspsnjob_req as &$n){
				$processs = ($n['PIS3_REQQTSUM'] > $n["SUPQTY"]) ? true : false;
				while($processs){
					foreach($rspsn_sup as &$x){
						$issubtituted =false;						
						foreach($rsWOM as $y){
							if($y['PWOP_BOMPN'] === $n['PIS3_MPART'] && $y['PWOP_SUBPN'] === $x['SPLSCN_ITMCD'] && $x['SPLSCN_QTY']>0){
								$issubtituted = true;									
								break;
							}
						}
						if($issubtituted){
							#third side sub
							$process2 = true;
							while($process2){
								if(($n['PIS3_REQQTSUM'] > $n["SUPQTY"]) ){
									if($x['SPLSCN_QTY']>0 ){
										$n["SUPQTY"]+=1;
										$x['SPLSCN_QTY']--;																					
										$qtper = $n['MYPER'][0]=="." ? "0".$n['MYPER'] : $n['MYPER'];
										$islotfound = false;
										foreach($rspsn_req_d as &$u){
											if(($u["SERD_JOB"] == $n["PIS3_WONO"]) 
											&& ($u["SERD_ITMCD"]== $x["SPLSCN_ITMCD"]) 
											&& ($u["SERD_LOTNO"]== $x["SPLSCN_LOTNO"])
											&& ($u['SERD_MC'] == $n['PIS3_MC'])  
											&& ($u['SERD_MCZ'] == $n['PIS3_MCZ']) 
											&& ($u['SERD_PROCD'] == $n['PIS3_PROCD'])
											&& ($u['SERD_FR'] == $n['PIS3_FR'] ) 
											&& ($u['SERD_QTPER'] == $qtper)
											&& ($u['SERD_REMARK'] == 'WOM' )){
												$u["SERD_QTY"]+=1;
												$islotfound=true;break;
											}
										}
										unset($u);

										if(!$islotfound){
											$rspsn_req_d[] = [
												"SERD_JOB" => $n["PIS3_WONO"]
												,"SERD_QTPER" => $qtper 
												,"SERD_ITMCD" => $x['SPLSCN_ITMCD']
												,"SERD_QTY" => 1 
												,"SERD_LOTNO" => $x['SPLSCN_LOTNO']
												,"SERD_PSNNO" => $x['SPLSCN_DOC']
												,"SERD_LINENO" => $n['PIS3_LINENO']
												,"SERD_CAT" => ''
												,"SERD_FR" => $n['PIS3_FR']
												,"SERD_QTYREQ" => intval($n['PIS3_REQQTSUM'])
												,"SERD_MSCANTM" => $x['SCNTIME']
												,"SERD_MC" => $n['PIS3_MC']
												,"SERD_MCZ" => $n['PIS3_MCZ']
												,"SERD_PROCD" => $n['PIS3_PROCD']
												,"SERD_MSFLG" => $x['PPSN2_MSFLG']
												,"SERD_MPART" => $n['PIS3_MPART']
												,"SERD_USRID" => $cuserid
												, "SERD_LUPDT" => $crnt_dt,
												"SERD_REMARK" => "WOM" 
											];
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
							$processs = false;
						}
					}
					unset($x);
				}
			}
			unset($n);

			#SPLREFF
			foreach($rspsnjob_req as &$n){
				$processs = ($n['PIS3_REQQTSUM'] > $n["SUPQTY"]) ? true : false;
				if($processs){
					foreach($rspsn_sup as &$x){						
						$issubtituted = false;
						foreach($rsSPLREFF as $y){
							if($y['SPLREFF_REQ_PART'] === $n['PIS3_MPART'] && $y['SPLREFF_ACT_PART'] === $x['SPLSCN_ITMCD'] && $x['SPLSCN_QTY']>0){
								$issubtituted = true;
								break;
							}
						}
						if($issubtituted){
							#third side sub							
							$balreq = $n['PIS3_REQQTSUM'] - $n["SUPQTY"];							
							if($balreq && $x['SPLSCN_QTY']>0){
								$serdqty = 0;
								$qtper = $n['MYPER'][0]=="." ? "0".$n['MYPER'] : $n['MYPER'];								
								if($balreq >$x['SPLSCN_QTY'] ){
									$n['SUPQTY'] +=  $x['SPLSCN_QTY'];
									$serdqty = (int)$x['SPLSCN_QTY'];
									$x['SPLSCN_QTY'] = 0;
								} else {
									$x['SPLSCN_QTY']-=$balreq;
									$n['SUPQTY'] += $balreq;
									$serdqty = $balreq;
								}

								$rspsn_req_d[] = [
									"SERD_JOB" => $n["PIS3_WONO"]
									,"SERD_QTPER" => $qtper 
									,"SERD_ITMCD" => $x['SPLSCN_ITMCD']
									,"SERD_QTY" => $serdqty
									,"SERD_LOTNO" => $x['SPLSCN_LOTNO']
									,"SERD_PSNNO" => $x['SPLSCN_DOC']
									,"SERD_LINENO" => $n['PIS3_LINENO']
									,"SERD_CAT" => ''
									,"SERD_FR" => $n['PIS3_FR']
									,"SERD_QTYREQ" => intval($n['PIS3_REQQTSUM'])
									,"SERD_MSCANTM" => $x['SCNTIME']
									,"SERD_MC" => $n['PIS3_MC']
									,"SERD_MCZ" => $n['PIS3_MCZ']
									,"SERD_PROCD" => $n['PIS3_PROCD']
									,"SERD_MSFLG" => $x['PPSN2_MSFLG']
									,"SERD_MPART" => $n['PIS3_MPART']
									,"SERD_USRID" => $cuserid
									, "SERD_LUPDT" => $crnt_dt,
									"SERD_REMARK" => "splreff" 
								];
							} 							
						}
					}
					unset($x);
				}
			}
			unset($n);

			#MAX RUN Logic		
				$distinct_ost_item_only = [];
				$distinct_ost_item = [];			
				$mxr_item_suplied = [];
				#GET OST ITEM
				foreach($rspsnjob_req as $n){
					if($n['PIS3_REQQTSUM'] > $n["SUPQTY"]){
						if(!in_array($n['PIS3_MPART'], $distinct_ost_item_only)) {
							$distinct_ost_item_only[] = $n['PIS3_MPART'];
						}
					}
				}
				#END
				#GET TOTAL LINE OF OST ITEM
				foreach($distinct_ost_item_only as $d) {
					foreach($rspsnjob_req as $r) { 
						if($d===$r['PIS3_MPART']) {
							$isfound = false;
							foreach($distinct_ost_item as &$o) {
								if($d === $o['ITEMCD']) {
									$o['COUNT']+=$r['MYPER'];
									$o['COUNTROW']++;
									$isfound = true;
									break;
								}
							}
							unset($o);
							if(!$isfound) {
								$distinct_ost_item[] = [
									'ITEMCD' => $d
									,'COUNT' => $r['MYPER']
									,'COUNTROW' => 1
								];
							}
						}
					}
				}
				#END

				#GET TOTAL SUPPLIED
				foreach($rspsn_sup as $r) {
					$isfound = false;
					foreach($mxr_item_suplied as &$k) {
						if($r['SPLSCN_ITMCD']===$k['ITEM']) {
							$k['QTY']+=$r['SPLSCN_QTY_BAK'];
							$isfound=true;break;
						}
					}        
					unset($k);
					if(!$isfound) {
						$mxr_item_suplied[] = ['ITEM' => $r['SPLSCN_ITMCD'], 'QTY' => $r['SPLSCN_QTY_BAK']];
					}
				}
				#END

				#RESET REQCALCULATION & SUPCALCULATION FOR SPECIFIC ITEM
				foreach($rspsnjob_req as &$n) {
					$isfound = false;
					foreach($distinct_ost_item as $k) {
						if($k['ITEMCD']===$n['PIS3_MPART'] &&  $k['COUNTROW']>1) {
							$isfound = true;
							break;
						}
					}
					if($isfound) {
						$n['SUPQTY']=0;
					}
				}
				unset($n);

				foreach($rspsn_req_d as $n => $val) {
					foreach($distinct_ost_item as $k ) {
						if($k['ITEMCD']===$val['SERD_ITMCD'] &&  $k['COUNTROW']>1) {
							unset($rspsn_req_d[$n]);
						}
					}
				}    
				#END

				#RECALCULATE FOR SPECIFIC ITEM
				foreach($rspsnjob_req as &$n) { 
					if($n['PIS3_REQQTSUM'] > $n["SUPQTY"]) {
						$maxsup = 0;
						$tmount = 0;
						foreach($distinct_ost_item as $o){
							if($n['PIS3_MPART']==$o['ITEMCD']) {
								$tmount = $o['COUNT'];
								break;
							}
						}						
						if($tmount) {                
							foreach($mxr_item_suplied as $s) {
								if($n['PIS3_MPART']===$s['ITEM']) {
									$maxsup = floor($n['MYPER']/$tmount*$s['QTY']);
								}
							}							
							if($maxsup) {                    
								foreach($rspsn_sup as &$x){
									$balreq = $maxsup - $n["SUPQTY"];
									if( $n['PIS3_MPART'] == $x['SPLSCN_ITMCD'] && $balreq && $x['SPLSCN_QTY_BAK']>0){                             
										$qtper = $n['MYPER'][0]=="." ? "0".$n['MYPER'] : $n['MYPER'];
										$serdqty = 0;
										if($balreq>$x['SPLSCN_QTY_BAK']) {
											$n['SUPQTY'] +=  $x['SPLSCN_QTY_BAK'];
											$serdqty = (int)$x['SPLSCN_QTY_BAK'];
											$x['SPLSCN_QTY_BAK'] = 0;
										} else {
											$x['SPLSCN_QTY_BAK']-=$balreq;
											$n['SUPQTY'] += $balreq;
											$serdqty = $balreq;
										}										
										$rspsn_req_d[] = [											
											"SERD_JOB" => $n["PIS3_WONO"]
											,"SERD_QTPER" => $qtper 
											,"SERD_ITMCD" => $x['SPLSCN_ITMCD']
											,"SERD_QTY" => $serdqty
											,"SERD_LOTNO" => $x['SPLSCN_LOTNO']
											,"SERD_PSNNO" => $x['SPLSCN_DOC']
											,"SERD_LINENO" => $n['PIS3_LINENO']
											,"SERD_CAT" => ''
											,"SERD_FR" => $n['PIS3_FR']
											,"SERD_QTYREQ" => intval($n['PIS3_REQQTSUM'])
											,"SERD_MSCANTM" => $x['SCNTIME']
											,"SERD_MC" => $n['PIS3_MC']
											,"SERD_MCZ" => $n['PIS3_MCZ']
											,"SERD_PROCD" => $n['PIS3_PROCD']
											,"SERD_MSFLG" => $x['PPSN2_MSFLG']
											,"SERD_MPART" => $n['PIS3_MPART']
											,"SERD_USRID" => $cuserid
											, "SERD_LUPDT" => $crnt_dt,
											"SERD_REMARK" => "maxrun" 
										];
									}
								}
								unset($x);
							}
						}
					}
				}
				unset($n);
				#END
			#MAX RUN END
			if(count($rspsn_req_d) >0){
				$this->CI->SERD_mod->insertb($rspsn_req_d);
				$myar[] = ['cd' => 1, 'msg' => 'ok inserted ?'];
				return('{"status" : '.json_encode($myar).'}');
			} else {
				$myar[] = ['cd' => 0, 'msg' => 'calculated result is 0'];
				return('{"status" : '.json_encode($myar).'}');
			}
		} else {
			$myar[]= ['cd' => 0, 'msg' => 'PSN is not found'];
			return('{"status" : '.json_encode($myar).'}');
		}
	}
	public function get_usage_rm_oldata_perjob($pjob){
		#version : 1.2
		date_default_timezone_set('Asia/Jakarta');
		$crnt_dt = date('Y-m-d H:i:s');
		$cuserid = $this->CI->session->userdata('nama');
		$rspsn = $this->CI->SPL_mod->select_z_getpsn_byjob("'".$pjob."'");
		$myar = [];
		$strpsn = '';
		$strdocno = '';
		$apsn = [];
		$strmdlcd = '';
		foreach($rspsn as $r){
			$strpsn .= "'".$r['PPSN1_PSNNO']."',";
			$apsn[] = $r['PPSN1_PSNNO'];
			$strdocno = $r['PPSN1_DOCNO'];
			$strmdlcd = $r['PPSN1_MDLCD'];
		}
		$strpsn = substr($strpsn,0,strlen($strpsn)-1);
		if(trim($strpsn)!=''){
			$rsMSPP = $this->CI->MSPP_mod->select_byvar(['MSPP_MDLCD' => $strmdlcd]);
			$xrssub = $this->CI->MSPP_mod->select_all_byvar_group(["MSPP_ACTIVE" => "Y", "MSPP_MDLCD !=" => $strmdlcd]);
			$rsSpecial = $this->CI->MITMSA_mod->select_where(['RTRIM(MITMSA_ITMCD) MITMSA_ITMCD', 'RTRIM(MITMSA_ITMCDS) MITMSA_ITMCDS'], ['MITMSA_MDLCD' => $strmdlcd]);
			$rspsnjob_req = $this->CI->SPL_mod->select_psnjob_req($strdocno, $pjob);
			#Is SubAssy ?
			if($this->CI->MSTITM_mod->check_Primary_subassy(['PWOP_MDLCD' => $strmdlcd])){
				$rspsn_kit = $this->CI->SPLSCN_mod->select_spl_vs_splscn_non_fg_as_bom_null($strpsn);
				if(count($rspsn_kit)>0){
					$myar[] = ['cd' => 0, 'msg' => 'not complete kitting sub assy', "data" => $rspsn_kit];
					return('{"status": '.json_encode($myar).'}');
				}
				$rspsn_sup = $this->CI->SPLSCN_mod->select_scannedwelcat_bypsn_sa($strpsn);
			} else {
				$rspsn_kit = $this->CI->SPLSCN_mod->select_spl_vs_splscn_null_old($strpsn);
				if(count($rspsn_kit)>0){
					$distinct_item = [];
					foreach($rspsn_kit as $r){
						$distinct_item[$r['SPL_ITMCD']] = $r['SPL_ITMCD'];
					}
					$isnullvalue_required = false;
					foreach($distinct_item as $r){
						foreach($rspsnjob_req as $n){
							if($r==$n['PIS3_MPART']){
								$isnullvalue_required = true;
								break;
							}
						}
						if($isnullvalue_required){
							break;
						}
					}
					if(!$isnullvalue_required){
						foreach($distinct_item as $r){
							foreach($rspsnjob_req as $n){
								if($r==$n['PIS3_ITMCD']){
									$isnullvalue_required = true;
									break;
								}
							}
							if($isnullvalue_required){
								break;
							}
						}
					}
					if($isnullvalue_required){
						$myar[] = ['cd' => 0, 'msg' => 'not complete kitting', "data" => $rspsn_kit];
						return('{"status": '.json_encode($myar).'}');
					}					
				} 
				$rspsn_sup = $this->CI->SPLSCN_mod->select_scannedwelcat_bypsn($strpsn);
			}

			
			if(count($rspsnjob_req)==0){
				$myar[] = ['cd' => 0, 'msg' => 'pis3 null'];
				return('{"status": '.json_encode($myar).'}');
			}
			foreach($rspsnjob_req as &$n){
				if(!array_key_exists("SUPQTY", $n)){
					$n["SUPQTY"] = 0;
				}
			}
			unset($n);			 			

			$rspsn_req_d = [];
			if(count($rspsn_sup)==0){			
				$myar[] = ['cd' => 0, 'msg' => 'kitting process is not completed in MEGA'];
				return('{"status": '.json_encode($myar).'}');				
			}

			#MAIN CALCULATION
			foreach($rspsnjob_req as &$n){	
				$processs = true;
				while($processs){
					foreach($rspsn_sup as &$x){
						if( $n['PIS3_MPART'] == $x['SPLSCN_ITMCD'] ){
							$process2 = true;
							while($process2){
								if(($n['PIS3_REQQTSUM'] > $n["SUPQTY"]) ){
									if($x['SPLSCN_QTY']>0 ){
										$n["SUPQTY"]+=1;
										$x['SPLSCN_QTY']--;																					
										$qtper = $n['MYPER'][0]=="." ? "0".$n['MYPER'] : $n['MYPER'];
										$islotfound = false;
										foreach($rspsn_req_d as &$u){
											if(($u["SERD_JOB"] == $n["PIS3_WONO"]) 
											&& ($u["SERD_ITMCD"]== $x["SPLSCN_ITMCD"]) 
											&& ($u["SERD_LOTNO"]== $x["SPLSCN_LOTNO"])
											&& ($u['SERD_MC'] == $n['PIS3_MC'])  
											&& ($u['SERD_MCZ'] == $n['PIS3_MCZ']) 
											&& ($u['SERD_PROCD'] == $n['PIS3_PROCD'])
											&& ($u['SERD_CAT'] == $x['SPLSCN_CAT'])  
											&& ($u['SERD_MSFLG'] == $x['PPSN2_MSFLG']) 
											&& ($u['SERD_FR'] == $n['PIS3_FR'] ) 
											&& ($u['SERD_QTPER'] == $qtper)
											&& ($u['SERD_REMARK'] == 'xfmain' )){
												$u["SERD_QTY"]+=1;
												$islotfound=true;break;
											}
										}
										unset($u);

										if(!$islotfound){
											$rspsn_req_d[] = [
												"SERD_JOB" => $n["PIS3_WONO"]
												,"SERD_QTPER" => $qtper 
												,"SERD_ITMCD" => $x['SPLSCN_ITMCD']
												,"SERD_QTY" => 1 
												,"SERD_LOTNO" => $x['SPLSCN_LOTNO']
												,"SERD_PSNNO" => $x['SPLSCN_DOC']
												,"SERD_LINENO" => $n['PIS3_LINENO']
												,"SERD_CAT" => $x['SPLSCN_CAT']
												,"SERD_FR" => $n['PIS3_FR']
												,"SERD_QTYREQ" => intval($n['PIS3_REQQTSUM'])
												,"SERD_MSCANTM" => $x['SCNTIME']
												,"SERD_MC" => $n['PIS3_MC']
												,"SERD_MCZ" => $n['PIS3_MCZ']
												,"SERD_PROCD" => $n['PIS3_PROCD']
												,"SERD_MSFLG" => $x['PPSN2_MSFLG']
												,"SERD_MPART" => $n['PIS3_MPART']
												,"SERD_USRID" => $cuserid
												, "SERD_LUPDT" => $crnt_dt,
												"SERD_REMARK" => "xfmain" 
											];
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
							$processs = false;
						}
					}
					unset ($x);					
				}
			}
			unset($n);

			#sub1 CALCULATION
			foreach($rspsnjob_req as &$n){
				$processs = ($n['PIS3_REQQTSUM'] > $n["SUPQTY"]) ? true : false;				
				while($processs){
					foreach($rspsn_sup as &$x){
						$issubtituted = false;
						foreach($rsMSPP as $y){
							if($y['MSPP_BOMPN'] == $n['PIS3_MPART'] && $y['MSPP_SUBPN'] == $x['SPLSCN_ITMCD'] && $x['SPLSCN_QTY']>0){
								$issubtituted = true;									
								break;
							}
						}
						if($issubtituted){
							#first side sub
							$process2 = true;
							while($process2){
								if(($n['PIS3_REQQTSUM'] > $n["SUPQTY"]) ){
									if($x['SPLSCN_QTY']>0 ){
										$n["SUPQTY"]+=1;
										$x['SPLSCN_QTY']--;																					
										$qtper = $n['MYPER'][0]=="." ? "0".$n['MYPER'] : $n['MYPER'];
										$islotfound = false;
										foreach($rspsn_req_d as &$u){
											if(($u["SERD_JOB"] == $n["PIS3_WONO"]) 
											&& ($u["SERD_ITMCD"]== $x["SPLSCN_ITMCD"]) 
											&& ($u["SERD_LOTNO"]== $x["SPLSCN_LOTNO"])
											&& ($u['SERD_MC'] == $n['PIS3_MC'])  
											&& ($u['SERD_MCZ'] == $n['PIS3_MCZ']) 
											&& ($u['SERD_PROCD'] == $n['PIS3_PROCD'])
											&& ($u['SERD_CAT'] == $x['SPLSCN_CAT'])  
											&& ($u['SERD_MSFLG'] == $x['PPSN2_MSFLG']) 
											&& ($u['SERD_FR'] == $n['PIS3_FR'] ) 
											&& ($u['SERD_QTPER'] == $qtper)
											&& ($u['SERD_REMARK'] == 'xfsub' )){
												$u["SERD_QTY"]+=1;
												$islotfound=true;break;
											}
										}
										unset($u);

										if(!$islotfound){
											$rspsn_req_d[] = [
												"SERD_JOB" => $n["PIS3_WONO"]
												,"SERD_QTPER" => $qtper 
												,"SERD_ITMCD" => $x['SPLSCN_ITMCD']
												,"SERD_QTY" => 1 
												,"SERD_LOTNO" => $x['SPLSCN_LOTNO']
												,"SERD_PSNNO" => $x['SPLSCN_DOC']
												,"SERD_LINENO" => $n['PIS3_LINENO']
												,"SERD_CAT" => $x['SPLSCN_CAT']
												,"SERD_FR" => $n['PIS3_FR']
												,"SERD_QTYREQ" => intval($n['PIS3_REQQTSUM'])
												,"SERD_MSCANTM" => $x['SCNTIME']
												,"SERD_MC" => $n['PIS3_MC']
												,"SERD_MCZ" => $n['PIS3_MCZ']
												,"SERD_PROCD" => $n['PIS3_PROCD']
												,"SERD_MSFLG" => $x['PPSN2_MSFLG']
												,"SERD_MPART" => $n['PIS3_MPART']
												,"SERD_USRID" => $cuserid
												, "SERD_LUPDT" => $crnt_dt,
												"SERD_REMARK" => "xfsub" 
											];
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
							$processs = false;
						}
					}
					unset ($x);	
				}
			}
			unset($n);

			#sub2 CALCULATION
			foreach($rspsnjob_req as &$n){	
				$processs = ($n['PIS3_REQQTSUM'] > $n["SUPQTY"]) ? true : false;
				while($processs){
					foreach($rspsn_sup as &$x){
						$issubtituted = false;
						foreach($rsMSPP as $y){
							if($y['MSPP_SUBPN'] == $n['PIS3_MPART'] && $y['MSPP_BOMPN'] == $x['SPLSCN_ITMCD'] && $x['SPLSCN_QTY']>0){
								$issubtituted = true;									
								break;
							}
						}
						if($issubtituted){
							#second side sub
							$process2 = true;
							while($process2){
								if(($n['PIS3_REQQTSUM'] > $n["SUPQTY"]) ){
									if($x['SPLSCN_QTY']>0 ){
										$n["SUPQTY"]+=1;
										$x['SPLSCN_QTY']--;																					
										$qtper = $n['MYPER'][0]=="." ? "0".$n['MYPER'] : $n['MYPER'];
										$islotfound = false;
										foreach($rspsn_req_d as &$u){
											if(($u["SERD_JOB"] == $n["PIS3_WONO"]) 
											&& ($u["SERD_ITMCD"]== $x["SPLSCN_ITMCD"]) 
											&& ($u["SERD_LOTNO"]== $x["SPLSCN_LOTNO"])
											&& ($u['SERD_MC'] == $n['PIS3_MC'])  
											&& ($u['SERD_MCZ'] == $n['PIS3_MCZ']) 
											&& ($u['SERD_PROCD'] == $n['PIS3_PROCD'])
											&& ($u['SERD_CAT'] == $x['SPLSCN_CAT'])  
											&& ($u['SERD_MSFLG'] == $x['PPSN2_MSFLG']) 
											&& ($u['SERD_FR'] == $n['PIS3_FR'] ) 
											&& ($u['SERD_QTPER'] == $qtper)
											&& ($u['SERD_REMARK'] == 'xfsub2' )){
												$u["SERD_QTY"]+=1;
												$islotfound=true;break;
											}
										}
										unset($u);

										if(!$islotfound){
											$rspsn_req_d[] = [
												"SERD_JOB" => $n["PIS3_WONO"]
												,"SERD_QTPER" => $qtper 
												,"SERD_ITMCD" => $x['SPLSCN_ITMCD']
												,"SERD_QTY" => 1 
												,"SERD_LOTNO" => $x['SPLSCN_LOTNO']
												,"SERD_PSNNO" => $x['SPLSCN_DOC']
												,"SERD_LINENO" => $n['PIS3_LINENO']
												,"SERD_CAT" => $x['SPLSCN_CAT']
												,"SERD_FR" => $n['PIS3_FR']
												,"SERD_QTYREQ" => intval($n['PIS3_REQQTSUM'])
												,"SERD_MSCANTM" => $x['SCNTIME']
												,"SERD_MC" => $n['PIS3_MC']
												,"SERD_MCZ" => $n['PIS3_MCZ']
												,"SERD_PROCD" => $n['PIS3_PROCD']
												,"SERD_MSFLG" => $x['PPSN2_MSFLG']
												,"SERD_MPART" => $n['PIS3_MPART']
												,"SERD_USRID" => $cuserid
												, "SERD_LUPDT" => $crnt_dt,
												"SERD_REMARK" => "xfsub2" 
											];
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
							$processs = false;
						}
					}
					unset ($x);	
				}
			}
			unset($n);


			#sub3 CALCULATION
			foreach($rspsnjob_req as &$n){	
				$processs = ($n['PIS3_REQQTSUM'] > $n["SUPQTY"]) ? true : false;
				while($processs){
					foreach($rspsn_sup as &$x){
						$issubtituted =false;
						foreach($xrssub as $y){
							if($y['MSPP_BOMPN'] == $n['PIS3_MPART'] && $y['MSPP_SUBPN'] == $x['SPLSCN_ITMCD'] && $x['SPLSCN_QTY']>0){
								$issubtituted = true;									
								break;
							}
						}
						if($issubtituted){
							#third side sub
							$process2 = true;
							while($process2){
								if(($n['PIS3_REQQTSUM'] > $n["SUPQTY"]) ){
									if($x['SPLSCN_QTY']>0 ){
										$n["SUPQTY"]+=1;
										$x['SPLSCN_QTY']--;																					
										$qtper = $n['MYPER'][0]=="." ? "0".$n['MYPER'] : $n['MYPER'];
										$islotfound = false;
										foreach($rspsn_req_d as &$u){
											if(($u["SERD_JOB"] == $n["PIS3_WONO"]) 
											&& ($u["SERD_ITMCD"]== $x["SPLSCN_ITMCD"]) 
											&& ($u["SERD_LOTNO"]== $x["SPLSCN_LOTNO"])
											&& ($u['SERD_MC'] == $n['PIS3_MC'])  
											&& ($u['SERD_MCZ'] == $n['PIS3_MCZ']) 
											&& ($u['SERD_PROCD'] == $n['PIS3_PROCD'])
											&& ($u['SERD_CAT'] == $x['SPLSCN_CAT'])  
											&& ($u['SERD_MSFLG'] == $x['PPSN2_MSFLG']) 
											&& ($u['SERD_FR'] == $n['PIS3_FR'] ) 
											&& ($u['SERD_QTPER'] == $qtper)
											&& ($u['SERD_REMARK'] == 'xfsub3' )){
												$u["SERD_QTY"]+=1;
												$islotfound=true;break;
											}
										}
										unset($u);

										if(!$islotfound){
											$rspsn_req_d[] = [
												"SERD_JOB" => $n["PIS3_WONO"]
												,"SERD_QTPER" => $qtper 
												,"SERD_ITMCD" => $x['SPLSCN_ITMCD']
												,"SERD_QTY" => 1 
												,"SERD_LOTNO" => $x['SPLSCN_LOTNO']
												,"SERD_PSNNO" => $x['SPLSCN_DOC']
												,"SERD_LINENO" => $n['PIS3_LINENO']
												,"SERD_CAT" => $x['SPLSCN_CAT']
												,"SERD_FR" => $n['PIS3_FR']
												,"SERD_QTYREQ" => intval($n['PIS3_REQQTSUM'])
												,"SERD_MSCANTM" => $x['SCNTIME']
												,"SERD_MC" => $n['PIS3_MC']
												,"SERD_MCZ" => $n['PIS3_MCZ']
												,"SERD_PROCD" => $n['PIS3_PROCD']
												,"SERD_MSFLG" => $x['PPSN2_MSFLG']
												,"SERD_MPART" => $n['PIS3_MPART']
												,"SERD_USRID" => $cuserid
												, "SERD_LUPDT" => $crnt_dt,
												"SERD_REMARK" => "xfsub3" 
											];
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
							$processs = false;
						}
					}
					unset($x);
				}
			}
			unset($n);

			#sub4 CALCULATION
			foreach($rspsnjob_req as &$n){	
				$processs = ($n['PIS3_REQQTSUM'] > $n["SUPQTY"]) ? true : false;
				while($processs){
					foreach($rspsn_sup as &$x){
						$issubtituted =false;
						foreach($xrssub as $y){
							if($y['MSPP_SUBPN'] == $n['PIS3_MPART'] && $y['MSPP_BOMPN'] == $x['SPLSCN_ITMCD'] && $x['SPLSCN_QTY']>0){
								$issubtituted = true;
								break;
							}
						}
						if($issubtituted){
							#fourth side sub
							$process2 = true;
							while($process2){
								if(($n['PIS3_REQQTSUM'] > $n["SUPQTY"]) ){
									if($x['SPLSCN_QTY']>0 ){
										$n["SUPQTY"]+=1;
										$x['SPLSCN_QTY']--;																					
										$qtper = $n['MYPER'][0]=="." ? "0".$n['MYPER'] : $n['MYPER'];
										$islotfound = false;
										foreach($rspsn_req_d as &$u){
											if(($u["SERD_JOB"] == $n["PIS3_WONO"]) 
											&& ($u["SERD_ITMCD"]== $x["SPLSCN_ITMCD"]) 
											&& ($u["SERD_LOTNO"]== $x["SPLSCN_LOTNO"])
											&& ($u['SERD_MC'] == $n['PIS3_MC'])  
											&& ($u['SERD_MCZ'] == $n['PIS3_MCZ']) 
											&& ($u['SERD_PROCD'] == $n['PIS3_PROCD'])
											&& ($u['SERD_CAT'] == $x['SPLSCN_CAT'])  
											&& ($u['SERD_MSFLG'] == $x['PPSN2_MSFLG']) 
											&& ($u['SERD_FR'] == $n['PIS3_FR'] ) 
											&& ($u['SERD_QTPER'] == $qtper)
											&& ($u['SERD_REMARK'] == 'xfsub4' )){
												$u["SERD_QTY"]+=1;
												$islotfound=true;break;
											}
										}
										unset($u);

										if(!$islotfound){
											$rspsn_req_d[] = [
												"SERD_JOB" => $n["PIS3_WONO"]
												,"SERD_QTPER" => $qtper 
												,"SERD_ITMCD" => $x['SPLSCN_ITMCD']
												,"SERD_QTY" => 1 
												,"SERD_LOTNO" => $x['SPLSCN_LOTNO']
												,"SERD_PSNNO" => $x['SPLSCN_DOC']
												,"SERD_LINENO" => $n['PIS3_LINENO']
												,"SERD_CAT" => $x['SPLSCN_CAT']
												,"SERD_FR" => $n['PIS3_FR']
												,"SERD_QTYREQ" => intval($n['PIS3_REQQTSUM'])
												,"SERD_MSCANTM" => $x['SCNTIME']
												,"SERD_MC" => $n['PIS3_MC']
												,"SERD_MCZ" => $n['PIS3_MCZ']
												,"SERD_PROCD" => $n['PIS3_PROCD']
												,"SERD_MSFLG" => $x['PPSN2_MSFLG']
												,"SERD_MPART" => $n['PIS3_MPART']
												,"SERD_USRID" => $cuserid
												, "SERD_LUPDT" => $crnt_dt,
												"SERD_REMARK" => "xfsub4" 
											];
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
							$processs = false;
						}
					}
					unset($x);
				}
			}
			unset($n);

			#sub5 CALCULATION Special Acceptance
			foreach($rspsnjob_req as &$n){	
				$processs = ($n['PIS3_REQQTSUM'] > $n["SUPQTY"]) ? true : false;
				while($processs){
					foreach($rspsn_sup as &$x){
						$issubtituted = false;
						foreach($rsSpecial as $y){
							if($y['MITMSA_ITMCD'] == $n['PIS3_MPART'] && $y['MITMSA_ITMCDS'] == $x['SPLSCN_ITMCD'] && $x['SPLSCN_QTY']>0){
								$issubtituted = true;
								break;
							}
						}
						if($issubtituted){
							#third side sub
							$process2 = true;
							while($process2){
								if(($n['PIS3_REQQTSUM'] > $n["SUPQTY"]) ){
									if($x['SPLSCN_QTY']>0 ){
										$n["SUPQTY"]+=1;
										$x['SPLSCN_QTY']--;
										$qtper = $n['MYPER'][0]=="." ? "0".$n['MYPER'] : $n['MYPER'];
										$islotfound = false;
										foreach($rspsn_req_d as &$u){
											if(($u["SERD_JOB"] == $n["PIS3_WONO"]) 
											&& ($u["SERD_ITMCD"]== $x["SPLSCN_ITMCD"]) 
											&& ($u["SERD_LOTNO"]== $x["SPLSCN_LOTNO"])
											&& ($u['SERD_MC'] == $n['PIS3_MC'])  
											&& ($u['SERD_MCZ'] == $n['PIS3_MCZ']) 
											&& ($u['SERD_PROCD'] == $n['PIS3_PROCD'])
											&& ($u['SERD_FR'] == $n['PIS3_FR'] ) 
											&& ($u['SERD_QTPER'] == $qtper)
											&& ($u['SERD_REMARK'] == 'SpeAcp' )){
												$u["SERD_QTY"]+=1;
												$islotfound=true;break;
											}
										}
										unset($u);

										if(!$islotfound){
											$rspsn_req_d[] = [
												"SERD_JOB" => $n["PIS3_WONO"]
												,"SERD_QTPER" => $qtper 
												,"SERD_ITMCD" => $x['SPLSCN_ITMCD']
												,"SERD_QTY" => 1 
												,"SERD_LOTNO" => $x['SPLSCN_LOTNO']
												,"SERD_PSNNO" => $x['SPLSCN_DOC']
												,"SERD_LINENO" => $n['PIS3_LINENO']
												,"SERD_CAT" => ''
												,"SERD_FR" => $n['PIS3_FR']
												,"SERD_QTYREQ" => intval($n['PIS3_REQQTSUM'])
												,"SERD_MSCANTM" => $x['SCNTIME']
												,"SERD_MC" => $n['PIS3_MC']
												,"SERD_MCZ" => $n['PIS3_MCZ']
												,"SERD_PROCD" => $n['PIS3_PROCD']
												,"SERD_MSFLG" => $x['PPSN2_MSFLG']
												,"SERD_MPART" => $n['PIS3_MPART']
												,"SERD_USRID" => $cuserid
												, "SERD_LUPDT" => $crnt_dt,
												"SERD_REMARK" => "SpeAcp" 
											];
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
							$processs = false;
						}
					}
					unset($x);
				}
			}
			unset($n);
			#sub5 CALCULATION Special Acceptance two ways
			foreach($rspsnjob_req as &$n){	
				$processs = ($n['PIS3_REQQTSUM'] > $n["SUPQTY"]) ? true : false;
				while($processs){
					foreach($rspsn_sup as &$x){
						$issubtituted = false;
						foreach($rsSpecial as $y){
							if($y['MITMSA_ITMCDS'] == $n['PIS3_MPART'] && $y['MITMSA_ITMCD'] == $x['SPLSCN_ITMCD'] && $x['SPLSCN_QTY']>0){
								$issubtituted = true;
								break;
							}
						}
						if($issubtituted){
							#third side sub
							$process2 = true;
							while($process2){
								if(($n['PIS3_REQQTSUM'] > $n["SUPQTY"]) ){
									if($x['SPLSCN_QTY']>0 ){
										$n["SUPQTY"]+=1;
										$x['SPLSCN_QTY']--;
										$qtper = $n['MYPER'][0]=="." ? "0".$n['MYPER'] : $n['MYPER'];
										$islotfound = false;
										foreach($rspsn_req_d as &$u){
											if(($u["SERD_JOB"] == $n["PIS3_WONO"]) 
											&& ($u["SERD_ITMCD"]== $x["SPLSCN_ITMCD"]) 
											&& ($u["SERD_LOTNO"]== $x["SPLSCN_LOTNO"])
											&& ($u['SERD_MC'] == $n['PIS3_MC'])  
											&& ($u['SERD_MCZ'] == $n['PIS3_MCZ']) 
											&& ($u['SERD_PROCD'] == $n['PIS3_PROCD'])
											&& ($u['SERD_FR'] == $n['PIS3_FR'] ) 
											&& ($u['SERD_QTPER'] == $qtper)
											&& ($u['SERD_REMARK'] == 'SpeAcp' )){
												$u["SERD_QTY"]+=1;
												$islotfound=true;break;
											}
										}
										unset($u);

										if(!$islotfound){
											$rspsn_req_d[] = [
												"SERD_JOB" => $n["PIS3_WONO"]
												,"SERD_QTPER" => $qtper 
												,"SERD_ITMCD" => $x['SPLSCN_ITMCD']
												,"SERD_QTY" => 1 
												,"SERD_LOTNO" => $x['SPLSCN_LOTNO']
												,"SERD_PSNNO" => $x['SPLSCN_DOC']
												,"SERD_LINENO" => $n['PIS3_LINENO']
												,"SERD_CAT" => ''
												,"SERD_FR" => $n['PIS3_FR']
												,"SERD_QTYREQ" => intval($n['PIS3_REQQTSUM'])
												,"SERD_MSCANTM" => $x['SCNTIME']
												,"SERD_MC" => $n['PIS3_MC']
												,"SERD_MCZ" => $n['PIS3_MCZ']
												,"SERD_PROCD" => $n['PIS3_PROCD']
												,"SERD_MSFLG" => $x['PPSN2_MSFLG']
												,"SERD_MPART" => $n['PIS3_MPART']
												,"SERD_USRID" => $cuserid
												, "SERD_LUPDT" => $crnt_dt,
												"SERD_REMARK" => "SpeAcp" 
											];
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
							$processs = false;
						}
					}
					unset($x);
				}
			}
			unset($n);

			if(count($rspsn_req_d) >0){
				$this->CI->SERD_mod->insertb($rspsn_req_d);
				$myar[] = ['cd' => 1, 'msg' => 'ok inserted ?'];
				return('{"status" : '.json_encode($myar).'}');
			} else {
				$myar[] = ['cd' => 0, 'msg' => 'calculated result is 0'];
				return('{"status" : '.json_encode($myar).'}');
			}
		} else {
			$myar[]= ['cd' => 0, 'msg' => 'PSN is not found'];
			return('{"status" : '.json_encode($myar).'}');
		}
	}

	public function calculate_only_raw_material_resume($post_ser, $post_serqty, $post_job){
		header('Content-Type: application/json');
		ini_set('max_execution_time', '-1'); 
		$pser = $post_ser;
		$pserqty = $post_serqty;
		$pjob = $post_job;
		$myar = [];
		$strser = '';
		$showed_cols = ['SERD2_FGQTY'];
		if(is_array($pser)){
			$cnt = count($pser);
			if($cnt>0){
				for($u=0;$u<$cnt; $u++){				
					$strser .= "'".trim($pser[$u])."',";				
					if($this->CI->SERD_mod->check_Primary(['SERD_JOB' => $pjob[$u]]) ==0){
						$res0 = $this->get_usage_rm_perjob($pjob[$u]);
						$res = json_decode($res0);
						if($res->status[0]->cd!=0){
							$rscalculated = $this->CI->SERD_mod->select_calculatedlabel_where($showed_cols, ['SERD2_SER' => $pser[$u]]);
							if(count($rscalculated) >0){// if already calculated beforehand
								$issame = true;
								foreach($rscalculated as $r){
									if($pserqty[$u]!=$r['SERD2_FGQTY']){
										$issame = false;
										break;
									}
								}
								if(!$issame){
									$this->CI->SERD_mod->deletebyID_label(['SERD2_SER' => $pser[$u]]);
									$res2 = json_decode($this->get_usage_rm_perjob_peruniq($pser[$u], $pserqty[$u], $pjob[$u]));
									if($res2->status[0]->cd!=0){
										$myar[] = ['cd' => 1, 'msg' => 'recalculated ok', 'reffno' => $pser[$u]];								
									} else {
										$myar[] = ['cd' => 0, 'msg' => 'recalculating is failed', 'reffno' => $pser[$u]];
									}
								} else {
									$myar[]= ['cd' => 1, 'msg' => 'just get', 'reffno' => $pser[$u]];
									
								}
							} else { //if not yet calculated beforehand
								$res2 = json_decode($this->get_usage_rm_perjob_peruniq($pser[$u], $pserqty[$u], $pjob[$u]));
								if($res2->status[0]->cd!=0){
									$myar[] = ['cd' => 1, 'msg' => 'recalculated ok', 'reffno' => $pser[$u] ];								
								} else {
									$myar[] = ['cd' => 0, 'msg' => 'calculating is failed', 'reffno' => $pser[$u] ];
								}
							}
						} else { //if not yet calculated per job
							$myar[] = ['cd' => 0, 'msg' => 'calculating per job is failed ('.$res->status[0]->msg.')', 'reffno' => $pser[$u], 'qty' => $pserqty[$u], 'job' => $pjob[$u] ];
						}
					} else {
						$rscalculated = $this->CI->SERD_mod->select_calculatedlabel_where($showed_cols, ['SERD2_SER' => $pser[$u]]);
						if(count($rscalculated) >0){// if already calculated beforehand
							$issame = true;
							foreach($rscalculated as $r){
								if($pserqty[$u]!=$r['SERD2_FGQTY']){
									$issame = false;
									break;
								}
							}
							if(!$issame){
								$this->CI->SERD_mod->deletebyID_label(['SERD2_SER' => $pser[$u]]);
								$res2 = json_decode($this->get_usage_rm_perjob_peruniq($pser[$u], $pserqty[$u], $pjob[$u]));
								if($res2->status[0]->cd!=0){
									$myar[] = ['cd' => 1, 'msg' => 'recalculated, ok.', 'reffno' => $pser[$u]];								
								} else {
									$myar[] = ['cd' => 0, 'msg' => 'recalculating is failed.', 'reffno' => $pser[$u]];
								}
							} else {
								$myar[] = ['cd' => 1, 'msg' => 'just get.', 'reffno' => $pser[$u]];							
							}
						} else { //if not yet calculated beforehand
							$res2 = json_decode($this->get_usage_rm_perjob_peruniq($pser[$u], $pserqty[$u], $pjob[$u]));
							if($res2->status[0]->cd!=0){
								$myar[] = ['cd' => 1, 'msg' => 'recalculated ok.', 'reffno' => $pser[$u]];							
							} else {
								$myar[] = ['cd' => 0, 'msg' => 'calculating is failed.', 'reffno' => $pser[$u]];
							}
						}
					}
					#CHECK IS SUB ASSY
					if($this->CI->SERML_mod->check_Primary(['SERML_NEWID' => $pser[$u] ])){
						$rssubassy = $this->CI->SERML_mod->select_for_calculation($pser[$u]);
						foreach($rssubassy as $sa){
							if($this->CI->SERD_mod->check_Primary(['SERD_JOB' => $sa['SER_DOC']]) ==0){
								$res0 = $this->get_usage_rm_perjob($sa['SER_DOC']);
								$res = json_decode($res0);
								if($res->status[0]->cd!=0){
									$rscalculated = $this->CI->SERD_mod->select_calculatedlabel_where($showed_cols, ['SERD2_SER' => $sa['SER_ID']]);
									if(count($rscalculated) >0){// if already calculated beforehand
										$issame = true;
										foreach($rscalculated as $r){
											if($sa['SER_QTY']!=$r['SERD2_FGQTY']){
												$issame = false;
												break;
											}
										}
										if(!$issame){
											$this->CI->SERD_mod->deletebyID_label(['SERD2_SER' => $sa['SER_ID']]);
											$this->get_usage_rm_perjob_peruniq($sa['SER_ID'], $sa['SER_QTY'], $sa['SER_DOC']);
										}
									} else { //if not yet calculated beforehand
										$this->get_usage_rm_perjob_peruniq($sa['SER_ID'], $sa['SER_QTY'], $sa['SER_DOC']);
									}
								} 
							} else {
								$rscalculated = $this->CI->SERD_mod->select_calculatedlabel_where($showed_cols, ['SERD2_SER' => $sa['SER_ID']]);
								if(count($rscalculated) >0){// if already calculated beforehand
									$issame = true;
									foreach($rscalculated as $r){
										if($sa['SER_QTY']!=$r['SERD2_FGQTY']){
											$issame = false;
											break;
										}
									}
									if(!$issame){
										$this->CI->SERD_mod->deletebyID_label(['SERD2_SER' => $sa['SER_ID']]);
										$this->get_usage_rm_perjob_peruniq($sa['SER_ID'], $sa['SER_QTY'], $sa['SER_DOC']);	
									}
								} else { //if not yet calculated beforehand
									$this->get_usage_rm_perjob_peruniq($sa['SER_ID'], $sa['SER_QTY'], $sa['SER_DOC']);									
								}
							}
						}
					}
					#end check subassy
				}				
			} else {
				$myar[] = ['cd' => 0, 'msg' => 'nothing to be calculated'];
			}
		} else {
			$myar[] = ['cd' => 0, 'msg' => 'not array please contact admin'];
		}
		return '{"status": '.json_encode($myar).'}';		
	}
	public function calculate_raw_material_resume($post_ser, $post_serqty, $post_job){
		header('Content-Type: application/json');
		ini_set('max_execution_time', '-1'); 
		$pser = $post_ser;
		$pserqty = $post_serqty;
		$pjob = $post_job;
		$myar = [];
		$rsusage = [];			
		$kittingnull = [];		
		if(is_array($pser)){
			$cnt = count($pser);			
			if($cnt>0){
				$rsqty = $this->CI->SERD_mod->select_fgqty_in($pser);
				for($u=0;$u<$cnt; $u++){									
					if($this->CI->SERD_mod->check_Primary(['SERD_JOB' => $pjob[$u]]) ==0){
						$res0 = $this->get_usage_rm_perjob($pjob[$u]);
						$res = json_decode($res0);
						if($res->status[0]->cd!=0){
							$__isfound = false;
							foreach($rsqty as $q){
								if($q['SERD2_SER']==$pser[$u]){ $__isfound = true; break;}
							}
							if($__isfound){
								$issame = true;
								foreach($rsqty as $r){
									if($pser[$u] == $r['SERD2_SER']){
										if($pserqty[$u]!=$r['SERD2_FGQTY']) {
											$issame = false; break;
										}
									}
								}
								if(!$issame){
									$this->CI->SERD_mod->deletebyID_label(['SERD2_SER' => $pser[$u]]);
									$res2 = json_decode($this->get_usage_rm_perjob_peruniq($pser[$u], $pserqty[$u], $pjob[$u]));									
									$myar[] = $res2->status[0]->cd!=0 ? ['cd' => 1, 'msg' => 'recalculated ok', 'reffno' => $pser[$u]] : 
												['cd' => 0, 'msg' => 'recalculating is failed', 'reffno' => $pser[$u]];								
								} else {
									$myar[]= ['cd' => 1, 'msg' => 'just get', 'reffno' => $pser[$u]];									
								}
							} else {
								$res2 = json_decode($this->get_usage_rm_perjob_peruniq($pser[$u], $pserqty[$u], $pjob[$u]));								
								$myar[] = $res2->status[0]->cd!=0 ?  ['cd' => 1, 'msg' => 'recalculated ok', 'reffno' => $pser[$u] ] : 
											['cd' => 0, 'msg' => 'calculating is failed', 'reffno' => $pser[$u] ];																
							}							
						} else { //if not yet calculated per job
							$kittingnull = $res; #$res->data[0] ? $res->data[0] : [];
							$myar[] = ['cd' => 0, 'msg' => 'calculating per job is failed ('.$res->status[0]->msg.')', 'reffno' => $pser[$u], 'qty' => $pserqty[$u], 'job' => $pjob[$u] ];
						}
					} else {
						$__isfound = false;
						foreach($rsqty as $q){
							if($q['SERD2_SER']==$pser[$u]){ $__isfound = true; break;}
						}
						if($__isfound){
							$issame = true;
							foreach($rsqty as $r){
								if($pser[$u] == $r['SERD2_SER']){
									if($pserqty[$u]!=$r['SERD2_FGQTY']) {
										$issame = false; break;
									}
								}
							}
							if(!$issame){
								$this->CI->SERD_mod->deletebyID_label(['SERD2_SER' => $pser[$u]]);
								$res2 = json_decode($this->get_usage_rm_perjob_peruniq($pser[$u], $pserqty[$u], $pjob[$u]));									
								$myar[] = $res2->status[0]->cd!=0 ? ['cd' => 1, 'msg' => 'recalculated ok.', 'reffno' => $pser[$u]] : 
											['cd' => 0, 'msg' => 'recalculating is failed..', 'reffno' => $pser[$u]];								
							} else {
								$myar[]= ['cd' => 1, 'msg' => 'just get', 'reffno' => $pser[$u]];									
							}
						} else {
							$res2 = json_decode($this->get_usage_rm_perjob_peruniq($pser[$u], $pserqty[$u], $pjob[$u]));								
							$myar[] = $res2->status[0]->cd!=0 ?  ['cd' => 1, 'msg' => 'recalculated ok.', 'reffno' => $pser[$u] ] : 
										['cd' => 0, 'msg' => 'calculating is failed...', 'reffno' => $pser[$u] ];
						}						
					}
					#CHECK IS SUB ASSY
					if($this->CI->SERML_mod->check_Primary(['SERML_NEWID' => $pser[$u] ])){
						$rssubassy = $this->CI->SERML_mod->select_for_calculation($pser[$u]);
						foreach($rssubassy as $sa){
							if($this->CI->SERD_mod->check_Primary(['SERD_JOB' => $sa['SER_DOC']]) ==0){
								$res0 = $this->get_usage_rm_perjob($sa['SER_DOC']);
								$res = json_decode($res0);
								if($res->status[0]->cd!=0){
									$rscalculated = $this->CI->SERD_mod->select_fgqty($sa['SER_ID']);
									if(count($rscalculated) >0){// if already calculated beforehand
										$issame = true;
										foreach($rscalculated as $r){
											if($sa['SER_QTY']!=$r['SERD2_FGQTY']){
												$issame = false;
												break;
											}
										}
										if(!$issame){
											$this->CI->SERD_mod->deletebyID_label(['SERD2_SER' => $sa['SER_ID']]);
											$this->get_usage_rm_perjob_peruniq($sa['SER_ID'], $sa['SER_QTY'], $sa['SER_DOC']);
										}
									} else { //if not yet calculated beforehand
										$this->get_usage_rm_perjob_peruniq($sa['SER_ID'], $sa['SER_QTY'], $sa['SER_DOC']);
									}
								} 
							} else {
								$rscalculated = $this->CI->SERD_mod->select_fgqty($sa['SER_ID']);
								if(count($rscalculated) >0){// if already calculated beforehand
									$issame = true;
									foreach($rscalculated as $r){
										if($sa['SER_QTY']!=$r['SERD2_FGQTY']){
											$issame = false;
											break;
										}
									}
									if(!$issame){
										$this->CI->SERD_mod->deletebyID_label(['SERD2_SER' => $sa['SER_ID']]);
										$this->get_usage_rm_perjob_peruniq($sa['SER_ID'], $sa['SER_QTY'], $sa['SER_DOC']);	
									}
								} else { //if not yet calculated beforehand
									$this->get_usage_rm_perjob_peruniq($sa['SER_ID'], $sa['SER_QTY'], $sa['SER_DOC']);									
								}
							}
						}
					}
					#end check subassy
				}
				$strser = implode("','", $pser);
				$strser = "'".$strser."'";
				$rsusage = $this->CI->SERD_mod->select_calculatedlabel_where_resume($strser);		
			} else {
				$myar[] = ['cd' => 0, 'msg' => 'nothing to be calculated'];
			}
		} else {
			$myar[] = ['cd' => 0, 'msg' => 'not array please contact admin'];
		}
		return '{"status": '.json_encode($myar).', "data": '.json_encode($rsusage).',"data2":'.json_encode($kittingnull).'}';
	}
	public function calculate_raw_material_oldata_resume($post_ser, $post_serqty, $post_job){
		header('Content-Type: application/json');
		ini_set('max_execution_time', '-1'); 
		$pser = $post_ser;
		$pserqty = $post_serqty;
		$pjob = $post_job;
		$myar = [];
		$rsusage = [];
		$strser = '';
		$showed_cols = ['SERD2_FGQTY'];
		$kittingnull = [];
		if(is_array($pser)){
			$cnt = count($pser);
			if($cnt>0){
				for($u=0;$u<$cnt; $u++){				
					$strser .= "'".trim($pser[$u])."',";				
					if($this->CI->SERD_mod->check_Primary(['SERD_JOB' => $pjob[$u]]) ==0){
						$res0 = $this->get_usage_rm_oldata_perjob($pjob[$u]);
						$res = json_decode($res0);
						if($res->status[0]->cd!=0){
							$rscalculated = $this->CI->SERD_mod->select_calculatedlabel_where($showed_cols, ['SERD2_SER' => $pser[$u]]);
							if(count($rscalculated) >0){// if already calculated beforehand
								$issame = true;
								foreach($rscalculated as $r){
									if($pserqty[$u]!=$r['SERD2_FGQTY']){
										$issame = false;
										break;
									}
								}
								if(!$issame){
									$retdel = $this->CI->SERD_mod->deletebyID_label(['SERD2_SER' => $pser[$u]]);
									$res2 = json_decode($this->get_usage_rm_perjob_peruniq($pser[$u], $pserqty[$u], $pjob[$u]));
									if($res2->status[0]->cd!=0){
										$myar[] = ['cd' => 1, 'msg' => 'recalculated ok', 'reffno' => $pser[$u]];								
									} else {
										$myar[] = ['cd' => 0, 'msg' => 'recalculating is failed', 'reffno' => $pser[$u]];
									}
								} else {
									$myar[]= ['cd' => 1, 'msg' => 'just get', 'reffno' => $pser[$u]];
									
								}
							} else { //if not yet calculated beforehand
								$res2 = json_decode($this->get_usage_rm_perjob_peruniq($pser[$u], $pserqty[$u], $pjob[$u]));
								if($res2->status[0]->cd!=0){
									$myar[] = ['cd' => 1, 'msg' => 'recalculated ok', 'reffno' => $pser[$u] ];								
								} else {
									$myar[] = ['cd' => 0, 'msg' => 'calculating is failed', 'reffno' => $pser[$u] ];
								}
							}
						} else { //if not yet calculated per job
							$kittingnull = $res;
							$myar[] = ['cd' => 0, 'msg' => 'calculating per job is failed ('.$res->status[0]->msg.')', 'reffno' => $pser[$u], 'qty' => $pserqty[$u], 'job' => $pjob[$u] ];
						}
					} else {
						$rscalculated = $this->CI->SERD_mod->select_calculatedlabel_where($showed_cols, ['SERD2_SER' => $pser[$u]]);
						if(count($rscalculated) >0){// if already calculated beforehand
							$issame = true;
							foreach($rscalculated as $r){
								if($pserqty[$u]!=$r['SERD2_FGQTY']){
									$issame = false;
									break;
								}
							}
							if(!$issame){
								$retdel = $this->CI->SERD_mod->deletebyID_label(['SERD2_SER' => $pser[$u]]);
								$res2 = json_decode($this->get_usage_rm_perjob_peruniq($pser[$u], $pserqty[$u], $pjob[$u]));
								if($res2->status[0]->cd!=0){
									$myar[] = ['cd' => 1, 'msg' => 'recalculated, ok.', 'reffno' => $pser[$u]];								
								} else {
									$myar[] = ['cd' => 0, 'msg' => 'recalculating is failed.', 'reffno' => $pser[$u]];
								}
							} else {
								$myar[] = ['cd' => 1, 'msg' => 'just get.', 'reffno' => $pser[$u]];							
							}
						} else { //if not yet calculated beforehand
							$res2 = json_decode($this->get_usage_rm_perjob_peruniq($pser[$u], $pserqty[$u], $pjob[$u]));
							if($res2->status[0]->cd!=0){
								$myar[] = ['cd' => 1, 'msg' => 'recalculated ok.', 'reffno' => $pser[$u]];							
							} else {
								$myar[] = ['cd' => 0, 'msg' => 'calculating is failed.', 'reffno' => $pser[$u]];
							}
						}
					}
					#CHECK IS SUB ASSY
					if($this->CI->SERML_mod->check_Primary(['SERML_NEWID' => $pser[$u] ])){
						$rssubassy = $this->CI->SERML_mod->select_for_calculation($pser[$u]);
						foreach($rssubassy as $sa){
							if($this->CI->SERD_mod->check_Primary(['SERD_JOB' => $sa['SER_DOC']]) ==0){
								$res0 = $this->get_usage_rm_oldata_perjob($sa['SER_DOC']);
								$res = json_decode($res0);
								if($res->status[0]->cd!=0){
									$rscalculated = $this->CI->SERD_mod->select_calculatedlabel_where($showed_cols, ['SERD2_SER' => $sa['SER_ID']]);
									if(count($rscalculated) >0){// if already calculated beforehand
										$issame = true;
										foreach($rscalculated as $r){
											if($sa['SER_QTY']!=$r['SERD2_FGQTY']){
												$issame = false;
												break;
											}
										}
										if(!$issame){
											$this->CI->SERD_mod->deletebyID_label(['SERD2_SER' => $sa['SER_ID']]);
											$this->get_usage_rm_perjob_peruniq($sa['SER_ID'], $sa['SER_QTY'], $sa['SER_DOC']);
										}
									} else { //if not yet calculated beforehand
										$this->get_usage_rm_perjob_peruniq($sa['SER_ID'], $sa['SER_QTY'], $sa['SER_DOC']);
									}
								} 
							} else {
								$rscalculated = $this->CI->SERD_mod->select_calculatedlabel_where($showed_cols, ['SERD2_SER' => $sa['SER_ID']]);
								if(count($rscalculated) >0){// if already calculated beforehand
									$issame = true;
									foreach($rscalculated as $r){
										if($sa['SER_QTY']!=$r['SERD2_FGQTY']){
											$issame = false;
											break;
										}
									}
									if(!$issame){
										$this->CI->SERD_mod->deletebyID_label(['SERD2_SER' => $sa['SER_ID']]);
										$this->get_usage_rm_perjob_peruniq($sa['SER_ID'], $sa['SER_QTY'], $sa['SER_DOC']);	
									}
								} else { //if not yet calculated beforehand
									$this->get_usage_rm_perjob_peruniq($sa['SER_ID'], $sa['SER_QTY'], $sa['SER_DOC']);									
								}
							}
						}
					}
					#end check subassy
				}
				$strjob = substr($strser,0,strlen($strser)-1);
				$rsusage = $this->CI->SERD_mod->select_calculatedlabel_where_resume($strjob);
			} else {
				$myar[] = ['cd' => 0, 'msg' => 'nothing to be calculated'];
			}
		} else {
			$myar[] = ['cd' => 0, 'msg' => 'not array please contact admin'];
		}
		return '{"status": '.json_encode($myar).', "data": '.json_encode($rsusage).', "data2" : '.json_encode($kittingnull).'}';
	}

	public function calculate_raw_material($pser, $pserqty, $pjob){
		header('Content-Type: application/json');		
		$myar = [];
		$rsusage = [];		
		$showed_cols = ['SERD2_PSNNO','SERD2_LINENO','SERD2_PROCD','SERD2_CAT','SERD2_FR','SERD2_ITMCD', 'SERD2_QTY','SERD2_FGQTY', 'RTRIM(MITM_ITMD1) MITM_ITMD1', 'RTRIM(MITM_SPTNO) MITM_SPTNO' , 'SERD2_QTPER MYPER', 'SERD2_MC', 'SERD2_MCZ'];
		if($this->CI->SERD_mod->check_Primary(['SERD_JOB' => $pjob]) ==0){
			$res0 = $this->get_usage_rm_perjob($pjob);
			$res = json_decode($res0);
			if($res->status[0]->cd!=0){
				$rscalculated = $this->CI->SERD_mod->select_calculatedlabel_where($showed_cols, ['SERD2_SER' => $pser]);
				if(count($rscalculated) >0){// if already calculated beforehand
					$issame = true;
					foreach($rscalculated as $r){
						if($pserqty!=$r['SERD2_FGQTY']){
							$issame = false;
							break;
						}
					}
					if(!$issame){
						$retdel = $this->CI->SERD_mod->deletebyID_label(['SERD2_SER' => $pser]);
						$res2 = json_decode($this->get_usage_rm_perjob_peruniq($pser, $pserqty, $pjob));
						if($res2->status[0]->cd!=0){
							$myar[] = ['cd' => 1, 'msg' => 'recalculated ok'];
							$rsusage = $this->CI->SERD_mod->select_calculatedlabel_where($showed_cols, ['SERD2_SER' => $pser]);
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
						$rsusage = $this->CI->SERD_mod->select_calculatedlabel_where($showed_cols, ['SERD2_SER' => $pser]);
					} else {
						$myar[] = ['cd' => 0, 'msg' => 'calculating is failed ('.$res->status[0]->msg.')'];
					}
				}
			} else { //if not yet calculated per job
				$myar[] = ['cd' => 0, 'msg' => 'calculating per job is failed ('.$res->status[0]->msg.')'];
			}
		} else {
			$rscalculated = $this->CI->SERD_mod->select_calculatedlabel_where($showed_cols, ['SERD2_SER' => $pser]);
			if(count($rscalculated) >0){// if already calculated beforehand
				$issame = true;
				foreach($rscalculated as $r){
					if($pserqty!=$r['SERD2_FGQTY']){
						$issame = false;
						break;
					}
				}
				if(!$issame){
					$retdel = $this->CI->SERD_mod->deletebyID_label(['SERD2_SER' => $pser]);
					$res2 = json_decode($this->get_usage_rm_perjob_peruniq($pser, $pserqty, $pjob));
					if($res2->status[0]->cd!=0){
						$myar[] = ['cd' => 1, 'msg' => 'recalculated, ok.'];
						$rsusage = $this->CI->SERD_mod->select_calculatedlabel_where($showed_cols, 	['SERD2_SER' => $pser]);
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
					$rsusage = $this->CI->SERD_mod->select_calculatedlabel_where($showed_cols, ['SERD2_SER' => $pser]);
				} else {
					$myar[] = ['cd' => 0, 'msg' => 'calculating is failed.'];
				}
			}
		}
		return '{"status": '.json_encode($myar).', "data": '.json_encode($rsusage).'}';
	}

	public function get_usage_rm_perjob_peruniq($pser,$pserqty,$pjob){
		#version : 3
		date_default_timezone_set('Asia/Jakarta');
		$currrtime = date('Y-m-d H:i:s');
		$rsjobrm_d = $this->CI->SERD_mod->selectd_byjob($pjob);
		$pserqty = str_replace(",", "", $pserqty);
		$rsjobrm = $this->CI->SERD_mod->selecth_byjob($pjob,$pserqty);
		$rsser_d = [];
		$myar = [];
		if(count($rsjobrm)>0){
			foreach($rsjobrm as &$n){
				foreach($rsjobrm_d as &$x){
					if( ($n['SERD_QTPER'] == $x['SERD_QTPER']) 
					&& ( $n['SERD_CAT'] == $x['SERD_CAT']) 
					&& ( $n['SERD_LINENO'] == $x['SERD_LINENO']) 
					&& ( $n['SERD_PROCD'] == $x['SERD_PROCD']) 
					&& ( $n['SERD_FR'] == $x['SERD_FR']) 
					&& ( $n['SERD_MC'] == $x['SERD_MC'])  
					&& ( $n['SERD_MCZ'] == $x['SERD_MCZ'])
					&& ( $n['SERD_MPART'] == $x['SERD_MPART'])
					) {
						if(($n['SERREQQTY'] > $n["SUPSERQTY"]) ){
							if($x['SERD_QTY']>0 ){
								$__balance = $n['SERREQQTY']-$n["SUPSERQTY"];
								$__qty = 0;
								if($x['SERD_QTY']>$__balance){
									$n["SUPSERQTY"]+= $__balance;
									$x['SERD_QTY']-= $__balance;
									$__qty = $__balance;
								} else {
									$n["SUPSERQTY"]+=$x['SERD_QTY'];
									$__qty =  $x['SERD_QTY'];
									$x['SERD_QTY']=0;
								}
								$islotfound = false;
								foreach($rsser_d as &$u){
									if( ($u["SERD2_JOB"] == $n['SERD_JOB'])
										&& ($u["SERD2_ITMCD"]== $x["SERD_ITMCD"]) 
										&& ($u['SERD2_QTPER'] == $n['SERD_QTPER']) 
										&& ($u["SERD2_LOTNO"]== $x["SERD_LOTNO"])
										&& ($u['SERD2_MC'] == $x['SERD_MC']) 
										&& ($u['SERD2_MCZ'] == $x['SERD_MCZ']) 
										&& ($u['SERD2_PROCD'] == $x['SERD_PROCD']) 
										&& ($u['SERD2_FR'] == $x['SERD_FR']) 
										)
									{										
										$u["SERD2_QTY"]+=$__qty;
										$islotfound=true;break;
									}
								}
								unset($u);
								if(!$islotfound){
									$rsser_d[] = ["SERD2_SER" => $pser
										, "SERD2_JOB" => $n['SERD_JOB']
										, "SERD2_QTPER" => $n['SERD_QTPER']
										, "SERD2_ITMCD" => $x['SERD_ITMCD']
										, "SERD2_FGQTY" => intval($pserqty)
										, "SERD2_QTY" => $__qty*1
										, "SERD2_LOTNO" => $x['SERD_LOTNO']
										, "SERD2_PSNNO" => $x['SERD_PSNNO']
										, "SERD2_LINENO" => $n['SERD_LINENO']
										, "SERD2_PROCD" => $n['SERD_PROCD']
										, "SERD2_CAT" => $n['SERD_CAT']
										, "SERD2_FR" => $n['SERD_FR']
										, "SERD2_MC" => $n['SERD_MC']
										, "SERD2_MCZ" => $n['SERD_MCZ']
										, "SERD2_MSCANTM" => $x['SERD_MSCANTM']
										,"SERD2_LUPDT" => $currrtime 
									];
								}
							}
						}
					}
				}
				unset($x);
			}
			unset($n);

			if(count($rsser_d)>0){
				$this->CI->SERD_mod->insertb2($rsser_d);
				$myar[] = ['cd' => 1, 'msg' => 'calculated per label ,ok'];
			} else {
				$myar[] = ['cd' => 0, 'msg' => 'calculated per label , not ok'];
			}
		} else {
			$myar[] = ['cd' => 0, 'msg' => 'Raw material usage has not been calculated yet'];
		}		
		return('{"status": '.json_encode($myar).'}');
	}
	public function get_usage_rm_perjob_peruniq_old2($pser,$pserqty,$pjob){
		#version : 2
		date_default_timezone_set('Asia/Jakarta');
		$currrtime = date('Y-m-d H:i:s');
		$rsjobrm_d = $this->CI->SERD_mod->selectd_byjob($pjob);
		$pserqty = str_replace(",", "", $pserqty);
		$rsjobrm = $this->CI->SERD_mod->selecth_byjob($pjob,$pserqty);
		$rsser_d = [];
		$myar = [];
		if(count($rsjobrm)>0){
			foreach($rsjobrm as &$n){							
				foreach($rsjobrm_d as &$x){
					if( ($n['SERD_QTPER'] == $x['SERD_QTPER']) 
					&& ( $n['SERD_CAT'] == $x['SERD_CAT']) 
					&& ( $n['SERD_LINENO'] == $x['SERD_LINENO']) 
					&& ( $n['SERD_PROCD'] == $x['SERD_PROCD']) 
					&& ( $n['SERD_FR'] == $x['SERD_FR']) 
					&& ( $n['SERD_MC'] == $x['SERD_MC'])  
					&& ( $n['SERD_MCZ'] == $x['SERD_MCZ'])
					&& ( $n['SERD_MPART'] == $x['SERD_MPART'])
					) {
						
						$process2 = true;
						while($process2){
							if(($n['SERREQQTY'] > $n["SUPSERQTY"]) ){
								if($x['SERD_QTY']>0 ){
									// $n["SUPSERQTY"]+=1;
									// $x['SERD_QTY']--;
									$thnumber = $x['SERD_QTY'] > $n['SERD_QTPER'] ? $n['SERD_QTPER'] : 1;
									$n["SUPSERQTY"]+=$thnumber;
									$x['SERD_QTY']-=$thnumber;																						
									$islotfound = false;
									foreach($rsser_d as &$u){
										if( ($u["SERD2_JOB"] == $n['SERD_JOB'])
											&& ($u["SERD2_ITMCD"]== $x["SERD_ITMCD"]) 
											&& ($u['SERD2_QTPER'] == $n['SERD_QTPER']) 
											&& ($u["SERD2_LOTNO"]== $x["SERD_LOTNO"])
											&& ($u['SERD2_MC'] == $x['SERD_MC']) 
											&& ($u['SERD2_MCZ'] == $x['SERD_MCZ']) 
											&& ($u['SERD2_PROCD'] == $x['SERD_PROCD']) 
											&& ($u['SERD2_FR'] == $x['SERD_FR']) 
											)
										{
											// $u["SERD2_QTY"]+=1;
											$u["SERD2_QTY"]+=$thnumber;
											$islotfound=true;break;
										}
									}
									unset($u);

									if(!$islotfound){
										$rsser_d[] = ["SERD2_SER" => $pser
											, "SERD2_JOB" => $n['SERD_JOB']
											, "SERD2_QTPER" => $n['SERD_QTPER']
											, "SERD2_ITMCD" => $x['SERD_ITMCD']
											, "SERD2_FGQTY" => intval($pserqty)
											, "SERD2_QTY" => $thnumber*1
											, "SERD2_LOTNO" => $x['SERD_LOTNO']
											, "SERD2_PSNNO" => $x['SERD_PSNNO']
											, "SERD2_LINENO" => $n['SERD_LINENO']
											, "SERD2_PROCD" => $n['SERD_PROCD']
											, "SERD2_CAT" => $n['SERD_CAT']
											, "SERD2_FR" => $n['SERD_FR']
											, "SERD2_MC" => $n['SERD_MC']
											, "SERD2_MCZ" => $n['SERD_MCZ']
											, "SERD2_MSCANTM" => $x['SERD_MSCANTM']
											,"SERD2_LUPDT" => $currrtime 
										];
									}											
								} else {
									$process2 = false;
								}
							} else {
								$process2 = false;									
							}
						}
					}
				}
				unset($x);									
			}
			unset($n);

			if(count($rsser_d)>0){
				$this->CI->SERD_mod->insertb2($rsser_d);
				$myar[] = ['cd' => 1, 'msg' => 'calculated per label ,ok'];
			} else {
				$myar[] = ['cd' => 0, 'msg' => 'calculated per label , not ok'];
			}
		} else {
			$myar[] = ['cd' => 0, 'msg' => 'Raw material usage has not been calculated yet'];
		}
		
		return('{"status": '.json_encode($myar).'}');
	}
	public function get_usage_rm_perjob_peruniq_old($pser,$pserqty,$pjob){
		#version : 1
		date_default_timezone_set('Asia/Jakarta');
		$currrtime = date('Y-m-d H:i:s');
		$rsjobrm_d = $this->CI->SERD_mod->selectd_byjob($pjob);
		$pserqty = str_replace(",", "", $pserqty);
		$rsjobrm = $this->CI->SERD_mod->selecth_byjob($pjob,$pserqty);
		$rsser_d = [];
		$myar = [];
		if(count($rsjobrm)>0){
			foreach($rsjobrm as &$n){
				$processs = true;
				while($processs){
					// $isready = false;
					// foreach($rsjobrm_d as $x){
					// 	if ( ($n['SERD_QTPER'] == $x['SERD_QTPER']) 
					// 	&& ( $n['SERD_CAT'] == $x['SERD_CAT'] ) 
					// 	&& ( $n['SERD_LINENO'] == $x['SERD_LINENO'])  
					// 	&& ( $n['SERD_PROCD'] == $x['SERD_PROCD']) 
					// 	&& ( $n['SERD_FR'] == $x['SERD_FR']) 
					// 	&& ( $n['SERD_MC'] == $x['SERD_MC'])
					// 	&& ( $n['SERD_MCZ'] == $x['SERD_MCZ'])
					// 	&& ( $n['SERD_MPART'] == $x['SERD_MPART'])
					// 	 ){
					// 		if($x['SERD_QTY']>0 ){
					// 			$isready=true;
					// 			break;
					// 		}
					// 	}
					// }
					// if($isready){
						foreach($rsjobrm_d as &$x){
							if( ($n['SERD_QTPER'] == $x['SERD_QTPER']) 
							&& ( $n['SERD_CAT'] == $x['SERD_CAT']) 
							&& ( $n['SERD_LINENO'] == $x['SERD_LINENO']) 
							&& ( $n['SERD_PROCD'] == $x['SERD_PROCD']) 
							&& ( $n['SERD_FR'] == $x['SERD_FR']) 
							&& ( $n['SERD_MC'] == $x['SERD_MC'])  
							&& ( $n['SERD_MCZ'] == $x['SERD_MCZ'])
							&& ( $n['SERD_MPART'] == $x['SERD_MPART'])
							) {
								$process2 = true;
								while($process2){
									if(($n['SERREQQTY'] > $n["SUPSERQTY"]) ){
										if($x['SERD_QTY']>0 ){
											$n["SUPSERQTY"]+=1;
											$x['SERD_QTY']--;
											$islotfound = false;
											foreach($rsser_d as &$u){
												if( ($u["SERD2_JOB"] == $n['SERD_JOB'])
													&& ($u["SERD2_ITMCD"]== $x["SERD_ITMCD"]) 
													&& ($u['SERD2_QTPER'] == $n['SERD_QTPER']) 
													&& ($u["SERD2_LOTNO"]== $x["SERD_LOTNO"])
													&& ($u['SERD2_MC'] == $x['SERD_MC']) 
													&& ($u['SERD2_MCZ'] == $x['SERD_MCZ']) 
													&& ($u['SERD2_PROCD'] == $x['SERD_PROCD']) 
													&& ($u['SERD2_FR'] == $x['SERD_FR']) 
													)
												{
													$u["SERD2_QTY"]+=1;
													$islotfound=true;break;
												}
											}
											unset($u);

											if(!$islotfound){
												$rsser_d[] = ["SERD2_SER" => $pser
															, "SERD2_JOB" => $n['SERD_JOB']
															, "SERD2_QTPER" => $n['SERD_QTPER']
															, "SERD2_ITMCD" => $x['SERD_ITMCD']
															, "SERD2_FGQTY" => intval($pserqty)
															, "SERD2_QTY" => 1
															, "SERD2_LOTNO" => $x['SERD_LOTNO']
															, "SERD2_PSNNO" => $x['SERD_PSNNO']
															, "SERD2_LINENO" => $n['SERD_LINENO']
															, "SERD2_PROCD" => $n['SERD_PROCD']
															, "SERD2_CAT" => $n['SERD_CAT']
															, "SERD2_FR" => $n['SERD_FR']
															, "SERD2_MC" => $n['SERD_MC']
															, "SERD2_MCZ" => $n['SERD_MCZ']
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
						$processs = false;
					// } else {
					// 	$processs = false;
					// }
				}
			}
			unset($n);

			if(count($rsser_d)>0){
				$this->CI->SERD_mod->insertb2($rsser_d);
				$myar[] = ['cd' => 1, 'msg' => 'calculated per label ,ok'];
			} else {
				$myar[] = ['cd' => 0, 'msg' => 'calculated per label , not ok'];
			}
		} else {
			$myar[] = ['cd' => 0, 'msg' => 'Raw material usage has not been calculated yet'];
		}
		
		return('{"status": '.json_encode($myar).'}');
	}

	#integrator
	public function sync_item_description1(){
		$affect = $this->CI->MSTITM_mod->update_all_d1();
		$myar = [];
		if($affect){
			$myar[] = ['cd' => 1, 'msg' => $affect.' updated'];
		} else {
			$myar[] = ['cd' => 0, 'msg' => $affect.' updated'];
		}
		return('{"status": '.json_encode($myar).'}');
	}

	public function sync_supplier(){
		$affect = $this->CI->MSTSUP_mod->sync();
		$myar = [];
		if($affect){
			$myar[] = ['cd' => 1, 'msg' => $affect.' updated'];
		} else {
			$myar[] = ['cd' => 0, 'msg' => $affect.' updated'];
		}
		return(json_encode(['status' => $myar]));
	}


	public function sync_new_item(){
		$rs = $this->CI->MSTITM_mod->selectdiff();
		$counterNew = 0;
		foreach($rs as $r){			
			$citem	= $r['MITM_ITMCD'];					
			if($this->CI->MSTITM_mod->check_Primary(['MITM_ITMCD' =>  $citem])==0){
				$toret = $this->CI->MSTITM_mod->insertsync($citem);
				if($toret>0){
					$counterNew++;
				}
			}
		}
		return($counterNew);
	}

	public function sync_new_itemGrade() {
		date_default_timezone_set('Asia/Jakarta');
		$crnt_dt = date('Y-m-d H:i:s');
		$cuserid = $this->CI->session->userdata('nama');
		$rs = $this->CI->MSTITM_mod->selectdiff_grade();
		$counterNew = 0;
		foreach($rs as $r){			
			$citem = $r['PGRELED_LEDGRP'];
			$citemGrade = $r['PGRELED_ITMCD'];
			$saveData = ['MITMGRP_ITMCD' =>  $citem , 'MITMGRP_ITMCD_GRD' => $citemGrade];
			if($this->CI->MSTITM_mod->check_PrimaryGrade($saveData)==0){
				$saveData['MITMGRP_LUPDT'] = $crnt_dt;
				$saveData['MITMGRP_USRID'] = $cuserid;
				$toret = $this->CI->MSTITM_mod->insertGrade($saveData);
				if($toret>0){
					$counterNew++;
				}
			}
		}
		return($counterNew);
	}

	public function calculate_later($pser, $pqty, $pjob){
		$fields = [
			'inunique' => $pser,
			'inunique_qty' => $pqty,
			'inunique_job' => $pjob
		];
		$fields_string = http_build_query($fields);
		$ch = curl_init();
		curl_setopt($ch, CURLOPT_URL, 'http://192.168.0.29:8081/api-report-custom/api/inventory/calculate_raw_material_resume');
		curl_setopt($ch, CURLOPT_POST, true);
		curl_setopt($ch, CURLOPT_POSTFIELDS,$fields_string);
		curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
		curl_setopt($ch, CURLOPT_CONNECTTIMEOUT, 300);//5sdfdfs
		curl_setopt($ch, CURLOPT_TIMEOUT, 660);
		$data = curl_exec($ch);	
		curl_close($ch);
		// return json_decode($data);
		return $data;
	}

}