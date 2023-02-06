<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class SCR extends CI_Controller {

	public function __construct()
	{
		parent::__construct();
		$this->load->model('SCR_mod');
		$this->load->model('SCRSCN_mod');
		$this->load->model('MSTLOCG_mod');
		$this->load->model('TXROUTE_mod');
		$this->load->model('ITH_mod');
		$this->load->model('ZRPSCRAP_HIST_mod');
		$this->load->helper('url');
        $this->load->library('session');
        $this->load->library('Code39e128');
	}
	public function index()
	{
		echo "sorry";
	}

	public function create(){
        date_default_timezone_set('Asia/Jakarta');
        $data['dt'] = date('Y-m-d H:i:s');
		$this->load->view('wms/vscraprm', $data);
    }
	public function createser(){
        date_default_timezone_set('Asia/Jakarta');
        $data['dt'] = date('Y-m-d H:i:s');
		$this->load->view('wms/vscrapser', $data);
    }

    public function form_convert() {
        $this->load->view('scrap_report/vform_convert');
    }
    public function form_upload() {
        $data['userID'] = $this->session->userdata('nama');
        $this->load->view('scrap_report/vform_upload', $data);
    }
    
    public function scan(){
        $wh = $_COOKIE['CKPSI_WH'];
        $whnm = '';
        $rs= $this->MSTLOCG_mod->selectbyID($wh);
        foreach($rs as $r){
            $whnm = $r->MSTLOCG_NM;
        }
        $data['mwh'] = $wh;
        $data['mwhnm'] = $whnm;
        $this->load->view('wms/vscraprm_scan', $data);
    }
	
	public function set(){
        header('Content-Type: application/json');
        date_default_timezone_set('Asia/Jakarta');
        $currrtime = date('Y-m-d H:i:s');
        $currdate = date('Ymd');
        $cdocreff = $this->input->post('indocreff');
        $citem = $this->input->post('initem');
        $clot = $this->input->post('inlot');
        $cqty = $this->input->post('inqty');
        $cremark = $this->input->post('inremark');
        $cdate = $this->input->post('indate');
        $ttldata = count($citem);
        $toret =0;
        if($ttldata>0){
            $mlastid = $this->SCR_mod->lastserialid();
			$mlastid++;
            $newid = 'SCR'.$currdate.$mlastid;            
            for($i=0;$i<$ttldata;$i++){
                $rsck = $this->SCR_mod->selectvspend($cdocreff[$i], trim($citem[$i]), trim($clot[$i]));
                $datat = array();
                if(count($rsck)>0){
                    $isallow = false;
                    foreach($rsck as $r){
                        if($r['OSQTY']>=$cqty[$i]){
                            $isallow = true;
                        }
                    }
                    if($isallow){
                        $datat = array(
                            'SCR_DOC' => $newid,
                            'SCR_REFFDOC' => $cdocreff[$i],
                            'SCR_DT' => $cdate,
                            'SCR_ITMCD' => trim($citem[$i]),
                            'SCR_ITMLOT' => trim($clot[$i]),
                            'SCR_QTY' => $cqty[$i],
                            'SCR_REMARK' => $cremark[$i],
                            'SCR_LUPDT' => $currrtime,
                            'SCR_USRID' => $this->session->userdata('nama')
                        );
                        $toret += $this->SCR_mod->insert($datat);
                    }
                } else {
                    $datat = array(
                        'SCR_DOC' => $newid,
                        'SCR_REFFDOC' => $cdocreff[$i],
                        'SCR_DT' => $cdate,
                        'SCR_ITMCD' => trim($citem[$i]),
                        'SCR_ITMLOT' => trim($clot[$i]),
                        'SCR_QTY' => $cqty[$i],
                        'SCR_REMARK' => $cremark[$i],
                        'SCR_LUPDT' => $currrtime,
                        'SCR_USRID' => $this->session->userdata('nama')
                    );
                    $toret += $this->SCR_mod->insert($datat);
                }                  
            }            
            $myar = array();
            if($toret>0){
				$datar = array("cd" => $toret, "msg" => "Saved successfully" , "ref" => $newid);
				array_push($myar, $datar);
				echo '{"data":';
				echo json_encode($myar);
				echo '}';
			} else{
				$datar = array("cd" => 0, "msg" => "Could not save" );
				array_push($myar, $datar);
				echo '{"data":';
				echo json_encode($myar);
				echo '}';
			}
        }        
    }

    public function setser(){
        header('Content-Type: application/json');
        date_default_timezone_set('Asia/Jakarta');
        $currrtime = date('Y-m-d H:i:s');
        $currdate = date('Ymd');
        $cdocreff = $this->input->post('indocreff');
        $citem = $this->input->post('initem');
        $cser = $this->input->post('inser');        
        $cqty = $this->input->post('inqty');
        $cremark = $this->input->post('inremark');
        $cdate = $this->input->post('indate');
        $ttldata = count($citem);
        $toret =0;
        if($ttldata>0){
            $mlastid = $this->SCR_mod->lastserialidser();
			$mlastid++;
            $newid = 'SCRS'.$currdate.$mlastid;            
            for($i=0;$i<$ttldata;$i++){
                $rsck = $this->SCR_mod->selectservspend($cdocreff[$i], trim($cser[$i]));
                $datat = array();
                if(count($rsck)>0){
                    $isallow = false;
                    foreach($rsck as $r){
                        if($r['OSQTY']>=$cqty[$i]){
                            $isallow = true;
                        }
                    }
                    if($isallow){
                        $datat = array(
                            'SCRSER_DOC' => $newid,
                            'SCRSER_REFFDOC' => $cdocreff[$i],
                            'SCRSER_DT' => $cdate,
                            'SCRSER_SER' => trim($cser[$i]),                            
                            'SCRSER_QTY' => $cqty[$i],
                            'SCRSER_REMARK' => $cremark[$i],
                            'SCRSER_LUPDT' => $currrtime,
                            'SCRSER_USRID' => $this->session->userdata('nama')
                        );
                        $toret += $this->SCR_mod->insertser($datat);
                    }
                } else {
                    $datat = array(
                        'SCRSER_DOC' => $newid,
                        'SCRSER_REFFDOC' => $cdocreff[$i],
                        'SCRSER_DT' => $cdate,
                        'SCRSER_SER' => trim($cser[$i]),                            
                        'SCRSER_QTY' => $cqty[$i],
                        'SCRSER_REMARK' => $cremark[$i],
                        'SCRSER_LUPDT' => $currrtime,
                        'SCRSER_USRID' => $this->session->userdata('nama')
                    );
                    $toret += $this->SCR_mod->insertser($datat);
                }                  
            }            
            $myar = array();
            if($toret>0){
				$datar = array("cd" => $toret, "msg" => "Saved successfully" , "ref" => $newid);
				array_push($myar, $datar);
				echo '{"data":';
				echo json_encode($myar);
				echo '}';
			} else{
				$datar = array("cd" => 0, "msg" => "Could not save" );
				array_push($myar, $datar);
				echo '{"data":';
				echo json_encode($myar);
				echo '}';
			}
        }        
    }

    
    public function search(){
		date_default_timezone_set('Asia/Jakarta');		
		header('Content-Type: application/json');
		$ckey = $this->input->get('inid');
		$cby = $this->input->get('inby');							
        if($cby=='no'){
            $datal = array(
                'SCR_DOC' => $ckey
            );
            $rs = $this->SCR_mod->selectAllg_byVAR($datal);
        } else {
            $datal = array(
                'SCR_REMARK' => $ckey
            );
            $rs = $this->SCR_mod->selectAllg_byVAR($datal);
        }		
		echo json_encode($rs);
    }

    public function searchser(){
		date_default_timezone_set('Asia/Jakarta');		
		header('Content-Type: application/json');
		$ckey = $this->input->get('inid');
		$cby = $this->input->get('inby');		

        switch($cby){
            case 'no':
                $datal = ['SCRSER_DOC' => $ckey];
                $rs = $this->SCR_mod->selectserAllg_byVAR($datal);
                break;
            case 'rmrk':
                $datal = ['SCRSER_REMARK' => $ckey];
                $rs = $this->SCR_mod->selectserAllg_byVAR($datal);
                break;
            case 'pnddoc':
                $datal = ['PNDSCN_DOC' => $ckey];
                $rs = $this->SCR_mod->selectserAllg_byVAR($datal);
                break;
        }       
	    exit(json_encode($rs));
    }
    
    public function getbyid(){
        header('Content-Type: application/json');
        $cdoc = $this->input->get('indoc');
        $dataw = array('SCR_DOC' => $cdoc);
        $rs = $this->SCR_mod->selectAll_by($dataw);
        echo json_encode($rs);
    }

    public function getbyidser(){
        header('Content-Type: application/json');
        $cdoc = $this->input->get('indoc');
        $dataw = array('SCRSER_DOC' => $cdoc);
        $rs = $this->SCR_mod->selectserAll_by($dataw);
        echo json_encode($rs);
    }

    public function edit(){
        header('Content-Type: application/json');
        date_default_timezone_set('Asia/Jakarta');
        $currrtime = date('Y-m-d H:i:s');        
        $cdocreff = $this->input->post('indocreff');
        $citem = $this->input->post('initem');
        $clot = $this->input->post('inlot');
        $cqty = $this->input->post('inqty');
        $cremark = $this->input->post('inremark');
        $cdate = $this->input->post('indate');
        $cdoc = $this->input->post('indoc');
        $ttldata = count($citem);
        $ADDAFF = 0;
        $EDITAFF = 0;
        if($ttldata>0){            
            $toret = 0;
            for($i=0;$i<$ttldata;$i++){
                $rsck = $this->SCR_mod->selectvspend_edit($cdoc,$cdocreff[$i], trim($citem[$i]), trim($clot[$i]));
                $savedqty = 0;
                $pndqty = 0;
                if(count($rsck)>0){
                    foreach($rsck as $r){
                        $savedqty = $r['SCR_QTY'];
                        $pndqty = $r['PND_QTY'];
                    }
                    if(($savedqty+$cqty) <= $pndqty ){                
                        $datat = array(                    
                            'SCR_DT' => $cdate,                    
                            'SCR_QTY' => $cqty[$i],
                            'SCR_REMARK' => $cremark[$i],
                            'SCR_LUPDT' => $currrtime,
                            'SCR_USRID' => $this->session->userdata('nama')
                        );
                        $dataw = array(
                            'SCR_DOC' => $cdoc, 'SCR_ITMCD' => trim($citem[$i]) , 'SCR_ITMLOT' => $clot[$i]
                        );
                        $toret = $this->SCR_mod->updatebyId($datat, $dataw);
                        $EDITAFF+=$toret;
                        if($toret==0){
                            $datas = array(
                                'SCR_DOC' => $cdoc,
                                'SCR_REFFDOC' => $cdocreff[$i],
                                'SCR_ITMCD' => trim($citem[$i]),
                                'SCR_ITMLOT' => trim($clot[$i]),
                                'SCR_DT' => $cdate,                    
                                'SCR_QTY' => $cqty[$i],
                                'SCR_REMARK' => $cremark[$i],
                                'SCR_LUPDT' => $currrtime,
                                'SCR_USRID' => $this->session->userdata('nama')
                            );
                            $toret = $this->SCR_mod->insert($datas);
                            $ADDAFF+=$toret;
                        }
                    }
                } else {                    
                    $rsck = $this->SCR_mod->selectvspend($cdocreff[$i], trim($citem[$i]), trim($clot[$i]));
                    $datat = array();
                    if(count($rsck)>0){                        
                        $isallow = false;
                        foreach($rsck as $r){
                            if($r['OSQTY']>=$cqty[$i]){
                                $isallow = true;
                            }
                        }
                        if($isallow){
                            $datas = array(
                                'SCR_DOC' => $cdoc,
                                'SCR_REFFDOC' => $cdocreff[$i],
                                'SCR_ITMCD' => trim($citem[$i]),
                                'SCR_ITMLOT' => trim($clot[$i]),
                                'SCR_DT' => $cdate,                    
                                'SCR_QTY' => $cqty[$i],
                                'SCR_REMARK' => $cremark[$i],
                                'SCR_LUPDT' => $currrtime,
                                'SCR_USRID' => $this->session->userdata('nama')
                            );
                            $toret = $this->SCR_mod->insert($datas);
                            $ADDAFF+=$toret;
                        }
                    } else {
                        $datas = array(
                            'SCR_DOC' => $cdoc,
                            'SCR_REFFDOC' => $cdocreff[$i],
                            'SCR_ITMCD' => trim($citem[$i]),
                            'SCR_ITMLOT' => trim($clot[$i]),
                            'SCR_DT' => $cdate,                    
                            'SCR_QTY' => $cqty[$i],
                            'SCR_REMARK' => $cremark[$i],
                            'SCR_LUPDT' => $currrtime,
                            'SCR_USRID' => $this->session->userdata('nama')
                        );
                        $toret = $this->SCR_mod->insert($datas);
                        $ADDAFF+=$toret;
                    }
                }
                
            }            
            $myar = array();
            if(($ADDAFF>0) || ($EDITAFF>0)){
				$datar = array("cd" => $toret, "msg" => "Saved ($ADDAFF) , Updated ($EDITAFF)" );
				array_push($myar, $datar);
				echo '{"data":';
				echo json_encode($myar);
				echo '}';
			} else{
				$datar = array("cd" => 0, "msg" => "Could not update" );
				array_push($myar, $datar);
				echo '{"data":';
				echo json_encode($myar);
				echo '}';
			}
        }
    }

    public function scn_balancing(){
		header('Content-Type: application/json');
		$cdo = $this->input->get('inDO');		
		$rs = $this->SCR_mod->selectscan_balancingv2($cdo);
		$rsh =$this->SCR_mod->selectprog_scan($cdo);
		$rss =$this->SCR_mod->selectprog_save($cdo);
		die('{"data":'.json_encode($rs)
		.',"datahead":'.json_encode($rsh)
		.',"datasave":'.json_encode($rss)
		.'}');
    }
    
    public function scn_itemdo_check(){
		$cdo = $this->input->get('inDO');		
		$citem = $this->input->get('inITEM');
		$datac  = ['SCR_ITMCD' => $citem, 'SCR_DOC' => $cdo ];
		if($this->SCR_mod->check_Primary($datac)>0){
			echo "1";
		} else {
			echo "0";
		}
    }
    
    public function scn_set(){
		date_default_timezone_set('Asia/Jakarta');
		$currdate = date('Ymd');
		$currrtime = date('Y-m-d H:i:s');

		$cdo 	= $this->input->post('inDO');
		$citm 	= $this->input->post('inITM');
		$cqty 	= $this->input->post('inQTY');
		$clot 	= $this->input->post('inLOT');		
		$datac  = array (
			'SCR_ITMCD' => $citm, 'SCR_DOC' => $cdo
		);
		if($this->SCR_mod->check_Primary($datac)>0){
            echo "1"; //location valid
            $mlastid	= $this->SCRSCN_mod->lastserialid();
            $mlastid++;
            $newid 		= $currdate.$mlastid;				
            //check if scanned qty is more than balance value
            $datac_bal 	= array(
                'SCR_DOC' => $cdo, 'SCR_ITMCD' => $citm
            );
            $rs 		= $this->SCR_mod->selectscan_balancing($datac_bal);
            $mget_bal = 0;
            foreach($rs as $r){
                $mget_bal = $r['SCR_QTY']-$r['SCAN_QTY']*1;				
            }
            if($cqty>$mget_bal){
                echo "0";
            } else {
                $datas = array(
                    'SCRSCN_ID' => $newid, 'SCRSCN_DOC' => $cdo, 
                    'SCRSCN_ITMCD' => $citm, 'SCRSCN_LOTNO' => $clot, 'SCRSCN_QTY' => $cqty,
                    'SCRSCN_LUPDT' => $currrtime , 'SCRSCN_USRID' => $this->session->userdata('nama')
                );
                $toret = $this->SCRSCN_mod->insert($datas);
                if($toret>0){ 
                    echo "1"; //insert RCVSCN success
                    $rs = $this->SCR_mod->selectbalancebyDOITEM($cdo, $citm);
                    foreach($rs as $r){
                        echo "_".($r['SCR_QTY']-$r['SCAN_QTY']); // GET BALANCE
                    }
                }
            }
		} else {
			echo "0";
		}
    }
    
    public function scnd_list_bydo_item(){
		header('Content-Type: application/json');
		$cdo 	= $this->input->get('inDO');
		$citem 	= $this->input->get('inITEM');
		$dataf 	= array('SCRSCN_DOC' => $cdo, 'SCRSCN_ITMCD'=> $citem);
		$rs 	= $this->SCRSCN_mod->selectby_filter($dataf);
		echo json_encode($rs);
    }
    
    public function scnd_remove(){
		$cid 	= $this->input->get('inID');
		$dataw 	= array('SCRSCN_ID' => $cid);
		$toret 	= $this->SCRSCN_mod->deleteby_filter($dataw);
		if( $toret>0){ 
			echo "Deleted";
		} else {
			echo "could not deleted";
		}
    }

    public function remove(){
        $ckey = $this->input->get('inkey');
        $cdoc = $this->input->get('indoc');
        $datac_scn =array(
            'SCRSCN_DOC' => $cdoc, 'SCRSCN_ITMCD' => $ckey
        );
        if($this->SCRSCN_mod->check_Primary($datac_scn)>0){
            exit('0');
        } else {
            $dataf= array(
                'SCR_DOC' => $cdoc, 'SCR_ITMCD' => $ckey
            );
            if($this->SCR_mod->deleteby_filter($dataf)>0){
                exit('1');
            } else {
                exit('00');
            }
        }
    }
    public function removeser(){
        $ckey = $this->input->get('inkey');
        $cdoc = $this->input->get('indoc');
        $datac_scn =array(
            'SCRSCN_DOC' => $cdoc, 'SCRSCN_SER' => $ckey
        );
        if($this->SCRSCN_mod->check_Primary($datac_scn)>0){
            exit('0');
        } else {
            $dataf= array(
                'SCRSER_DOC' => $cdoc, 'SCRSER_SER' => $ckey
            );
            if($this->SCR_mod->deleteserby_filter($dataf)>0){
                exit('1');
            } else {
                exit('00');
            }
        }
    }
    
    public function setscn(){
		date_default_timezone_set('Asia/Jakarta');
		$currrtime 	= date('Y-m-d H:i:s');
		$currdate	= date('Y-m-d');
		$cdo		= $this->input->post('indo');
		$citem 		= $this->input->post('initm');		
		$cscanqty 	= $this->input->post('inscanqty');
		$csaveqty 	= $this->input->post('insavedqty');
		$cwh 	= $this->input->post('inwh');		
        //==START DEFINE WAREHOUSE
        $cwh_inc = '';
        $cwh_out = '';
        $cfm_inc = '';
        $cfm_out = '';
        $dataf_txroute = array('TXROUTE_ID' => 'RECEIVING-RM-SCR');
        $rs_txroute = $this->TXROUTE_mod->selectbyVAR($dataf_txroute);
        if(count($rs_txroute)==0){exit('Please config your transaction route');}
        foreach($rs_txroute as $r){
            $cwh_inc = $r->TXROUTE_WHINC;
            $cwh_out = $r->TXROUTE_WHOUT;
            $cfm_inc = $r->TXROUTE_FORM_INC;
            $cfm_out = $r->TXROUTE_FORM_OUT;
        }		
        //==END
		$flag_insert = 0;
		$flag_update = 0;
		for($i=0;$i<count($citem);$i++){
			//check to ith is already row
			$cwhere = array(
				'ITH_ITMCD' => $citem[$i], 
				'ITH_DOC' => $cdo
			);
			if( $this->ITH_mod->check_Primary($cwhere)>0 ){
				$cwhere_pdt = array(
					'ITH_ITMCD' => $citem[$i], 'ITH_FORM' => $cfm_out,
					'ITH_DOC' => $cdo, 'ITH_DATE' => $currdate
				);
				if($this->ITH_mod->check_Primary($cwhere_pdt)>0){
					$cqtysavedday = $this->ITH_mod->selectqtyperdocitemday($cdo,$citem[$i],$currdate, $cfm_out);					
					$qtytostore = $cqtysavedday+($cscanqty[$i] - $csaveqty[$i]);
					$datau = array(
						'ITH_QTY' => -$qtytostore, 'ITH_WH' => $cwh_out
					);
                    $toret = $this->ITH_mod->updatebyId($cwhere_pdt, $datau); 
                    
                    $cwhere_pdt = array(
                        'ITH_ITMCD' => $citem[$i], 'ITH_FORM' => $cfm_inc,
                        'ITH_DOC' => $cdo, 'ITH_DATE' => $currdate
                    );
                    $datau = array(
						'ITH_QTY' => $qtytostore, 'ITH_WH' => $cwh_inc
					);
                    $toret = $this->ITH_mod->updatebyId($cwhere_pdt, $datau); 
                    $flag_update+=$toret;
				} else {
					$qtytostore = $cscanqty[$i] - $csaveqty[$i];
					if($qtytostore>0){
						$datas 		= array(
							'ITH_ITMCD' => $citem[$i], 'ITH_WH' => $cwh_out,
							'ITH_DOC' 	=> $cdo, 'ITH_DATE' => $currdate,
							'ITH_FORM' 	=> $cfm_out, 'ITH_QTY' => -$qtytostore, 'ITH_LUPDT' => $currrtime,
							'ITH_USRID' =>  $this->session->userdata('nama')
						);
                        $toret 		= $this->ITH_mod->insert($datas);
                        $datas 		= array(
							'ITH_ITMCD' => $citem[$i], 'ITH_WH' => $cwh_inc,
							'ITH_DOC' 	=> $cdo, 'ITH_DATE' => $currdate,
							'ITH_FORM' 	=> $cfm_inc, 'ITH_QTY' => $qtytostore, 'ITH_LUPDT' => $currrtime,
							'ITH_USRID' =>  $this->session->userdata('nama')
						);
						$toret 		= $this->ITH_mod->insert($datas);
						$flag_insert+=$toret;
                        $dataup = array('SCRSCN_SAVED' => '1');
                        $dataww = array(
                            'SCRSCN_DOC' => $cdo, 'SCRSCN_ITMCD' => $citem[$i]
                        );
						$toret = $this->SCRSCN_mod->updatebyId($dataup, $dataww);
					}					
				}
			} else {
				$datas = array(
					'ITH_ITMCD' => $citem[$i], 'ITH_WH' => $cwh_out , 
					'ITH_DOC' => $cdo, 'ITH_DATE' => $currdate,
					'ITH_FORM' => $cfm_out, 'ITH_QTY' => -$cscanqty[$i], 'ITH_LUPDT' => $currrtime,
					'ITH_USRID' =>  $this->session->userdata('nama')
				);
				$toret = $this->ITH_mod->insert($datas);
				$datas = array(
					'ITH_ITMCD' => $citem[$i], 'ITH_WH' => $cwh_inc , 
					'ITH_DOC' => $cdo, 'ITH_DATE' => $currdate,
					'ITH_FORM' => $cfm_inc, 'ITH_QTY' => $cscanqty[$i], 'ITH_LUPDT' => $currrtime,
					'ITH_USRID' =>  $this->session->userdata('nama')
				);
				$toret = $this->ITH_mod->insert($datas);
				$flag_insert+= $toret;
                $dataup = array('SCRSCN_SAVED' => '1');
                $dataww = array(
                    'SCRSCN_DOC' => $cdo, 'SCRSCN_ITMCD' => $citem[$i]
                );
                $toret = $this->SCRSCN_mod->updatebyId($dataup, $dataww);                
			}
		}
		if($flag_update>0 && $flag_insert>0){
			echo "Saved and updated";
		} elseif ($flag_update<=0 && $flag_insert>0) {
			echo "Saved";
		} elseif ($flag_update>0 && $flag_insert<=0) {
			echo "Updated";
		}		
    }
    
    public function printdoc(){
		$pser = '';		
        if(isset($_COOKIE["CKSCRDOC_NO"])){
            $pser = $_COOKIE["CKSCRDOC_NO"];
		} else {
			exit('nothing to be printed');
		}
		$cwhere  = array('SCR_DOC' => $pser);
        $rs = $this->SCR_mod->selectAll_by($cwhere);
        $cdate = '';
        $cuser = '';
        foreach($rs as $r){
            $cdate=$r['SCR_DT'];
            $cuser=$r['MSTEMP_FNM'];
        }
		$pdf = new PDF_Code39e128('L','mm','A5');
		$pdf->AliasNbPages();
		$pdf->AddPage();
		$hgt_p = $pdf->GetPageHeight();
		$wid_p = $pdf->GetPageWidth();
		$pdf->SetAutoPageBreak(true,1);
		$pdf->SetMargins(0,0);
		$pdf->SetFont('Arial','BU',13);
		$clebar = $pdf->GetStringWidth($pser)+10;
		$pdf->SetXY(80,5);
        $pdf->Cell(50,4,'Document of RM Scrap',0,0,'L');
		$pdf->SetFont('Arial','B',8);
		$pdf->SetXY(5,10);
		$pdf->Cell(15,4,'Doc No : ',0,0,'L');
		$pdf->SetXY(150,10);
        $pdf->Cell(15,4,'Created Date : '.$cdate,0,0,'L');
        $pdf->SetXY(150,15);
        $pdf->Cell(15,4,'Created By : '.$cuser,0,0,'L');
		$pdf->Code128(20, 10,$pser,$clebar,5);
		$pdf->SetXY(20,15);
		$pdf->Cell($clebar,4,$pser,0,0,'C');
		$pdf->SetXY(5,26);
		$pdf->Cell(8,4,'No',1,0,'L');
		$pdf->Cell(35,4,'Item Code',1,0,'L');		
		$pdf->Cell(35,4,'Item Name',1,0,'L');
		$pdf->Cell(40,4,'Lot No',1,0,'L');
		$pdf->Cell(15,4,'QTY',1,0,'R');
		$pdf->Cell(25,4,'Rack No',1,0,'L');
		$pdf->Cell(30,4,'Remark',1,0,'L');
		$cury = 30;
		$no = 1;

		foreach($rs as $r){
			if(($cury+10)>$hgt_p){
				$pdf->AddPage();
				$pdf->SetFont('Arial','BU',13);
                $clebar = $pdf->GetStringWidth($pser)+10;
                $pdf->SetXY(80,5);
                $pdf->Cell(50,4,'Document of RM Scrap',0,0,'L');
                $pdf->SetFont('Arial','B',8);
                $pdf->SetXY(5,10);
                $pdf->Cell(15,4,'Doc No : ',0,0,'L');
                $pdf->SetXY(150,10);
                $pdf->Cell(15,4,'Created Date : '.$cdate,0,0,'L');
                $pdf->SetXY(150,15);
                $pdf->Cell(15,4,'Created By : '.$cuser,0,0,'L');
                $pdf->Code128(20, 10,$pser,$clebar,5);
                $pdf->SetXY(20,15);
                $pdf->Cell($clebar,4,$pser,0,0,'C');
                $pdf->SetXY(5,26);
                $pdf->Cell(8,4,'No',1,0,'L');
                $pdf->Cell(35,4,'Item Code',1,0,'L');		
                $pdf->Cell(35,4,'Item Name',1,0,'L');
                $pdf->Cell(40,4,'Lot No',1,0,'L');
                $pdf->Cell(15,4,'QTY',1,0,'R');
                $pdf->Cell(25,4,'Rack No',1,0,'L');
                $pdf->Cell(30,4,'Remark',1,0,'L');
                $cury = 30;
			}
			$pdf->SetFont('Arial','',9);
			$pdf->SetXY(5,$cury);
			$pdf->Cell(8,4,$no,1,0,'L');
			$pdf->Cell(35,4,trim($r['SCR_ITMCD']),1,0,'L');
			
			$ttlwidth = $pdf->GetStringWidth(trim($r['MITM_ITMD1']));
			if($ttlwidth > 35){
				$ukuranfont = 8.5;
				while($ttlwidth>34){
					$pdf->SetFont('Arial','',$ukuranfont);
					$ttlwidth=$pdf->GetStringWidth(trim($r['MITM_ITMD1']));
					$ukuranfont = $ukuranfont - 0.5;
				}
			}
			$pdf->Cell(35,4,trim($r['MITM_ITMD1']),1,0,'L');

			$pdf->SetFont('Arial','',9);
			$pdf->Cell(40,4,trim($r['SCR_ITMLOT']),1,0,'L');
			$pdf->Cell(15,4,number_format($r['SCR_QTY']),1,0,'R');
			$pdf->Cell(25,4,trim($r['ITMLOC_LOC']),1,0,'L');
			$pdf->Cell(30,4,'Remark',1,0,'L');
			$cury+=4; $no++;
		}

		$pdf->Output('I','SCRAPING Doc '.$pser.' ' .date("d-M-Y").'.pdf');
    }
    
    public function printdocser(){
		$pser = '';		
        if(isset($_COOKIE["CKSCRDOCSER_NO"])){
            $pser = $_COOKIE["CKSCRDOCSER_NO"];
		} else {
			exit('nothing to be printed');
		}
		$cwhere  = ['SCRSER_DOC' => $pser];
        $rs = $this->SCR_mod->selectserAll_by($cwhere);
        $cdate = '';
        $cuser = '';
        foreach($rs as $r){
            $cdate=$r['SCRSER_DT'];
            $cuser=$r['MSTEMP_FNM'];
        }
		$pdf = new PDF_Code39e128('L','mm','A5');
		$pdf->AliasNbPages();
		$pdf->AddPage();
		$hgt_p = $pdf->GetPageHeight();
		$wid_p = $pdf->GetPageWidth();
		$pdf->SetAutoPageBreak(true,1);
		$pdf->SetMargins(0,0);
		$pdf->SetFont('Arial','BU',9);
        $clebar = $pdf->GetStringWidth($pser)+10;
        $pdf->SetXY(80,5);
        $pdf->Cell(50,4,'Document of FG Scrap',0,0,'L');
        $pdf->SetFont('Arial','B',8);
		$pdf->SetXY(5,10);
        $pdf->Cell(15,4,'Doc No : ',0,0,'L');
        $pdf->SetXY(150,10);
        $pdf->Cell(15,4,'Created Date : '.$cdate,0,0,'L');
        $pdf->SetXY(150,15);
        $pdf->Cell(15,4,'Created By : '.$cuser,0,0,'L');
        $pdf->Code128(20, 10,$pser,$clebar,5);        
		$pdf->SetXY(20,15);
		$pdf->Cell($clebar,4,$pser,0,0,'C');
		$pdf->SetXY(5,26);
		$pdf->Cell(8,4,'No',1,0,'L');
		$pdf->Cell(35,4,'Serial No',1,0,'L');		
		$pdf->Cell(35,4,'Item Code',1,0,'L');		
		$pdf->Cell(30,4,'Item Name',1,0,'L');
		$pdf->Cell(35,4,'Job No',1,0,'L');
		$pdf->Cell(10,4,'QTY',1,0,'R');
		$pdf->Cell(15,4,'Rack No',1,0,'L');
		$pdf->Cell(30,4,'Remark',1,0,'L');
		$cury = 30;
		$no = 1;

		foreach($rs as $r){
			if(($cury+10)>$hgt_p){
				$pdf->AddPage();
				$pdf->SetFont('Arial','BU',9);
                $clebar = $pdf->GetStringWidth($pser)+10;
                $pdf->SetXY(80,5);
                $pdf->Cell(50,4,'Document of FG Scrap',0,0,'L');
                $pdf->SetFont('Arial','B',8);
                $pdf->SetXY(5,10);
                $pdf->Cell(15,4,'Doc No : ',0,0,'L');
                $pdf->SetXY(150,10);
                $pdf->Cell(15,4,'Created Date : '.$cdate,0,0,'L');
                $pdf->SetXY(150,15);
                $pdf->Cell(15,4,'Created By : '.$cdate,0,0,'L');
                $pdf->Code128(20, 10,$pser,$clebar,5);        
                $pdf->SetXY(20,15);
                $pdf->Cell($clebar,4,$pser,0,0,'C');
                $pdf->SetXY(5,26);
                $pdf->Cell(8,4,'No',1,0,'L');
                $pdf->Cell(35,4,'Serial No',1,0,'L');		
                $pdf->Cell(35,4,'Item Code',1,0,'L');		
                $pdf->Cell(30,4,'Item Name',1,0,'L');
                $pdf->Cell(35,4,'Job No',1,0,'L');
                $pdf->Cell(10,4,'QTY',1,0,'R');
                $pdf->Cell(15,4,'Rack No',1,0,'L');
                $pdf->Cell(30,4,'Remark',1,0,'L');
                $cury = 30;
			}
			$pdf->SetFont('Arial','',8);
			$pdf->SetXY(5,$cury);
			$pdf->Cell(8,4,$no,1,0,'L');
			$pdf->Cell(35,4,trim($r['SCRSER_SER']),1,0,'L');
			
			
			$pdf->Cell(35,4,trim($r['SER_ITMID']),1,0,'L');

			
            $ttlwidth = $pdf->GetStringWidth(trim($r['MITM_SPTNO']));
			if($ttlwidth > 30){
				$ukuranfont = 8.5;
				while($ttlwidth>29){
					$pdf->SetFont('Arial','',$ukuranfont);
					$ttlwidth=$pdf->GetStringWidth(trim($r['MITM_SPTNO']));
					$ukuranfont = $ukuranfont - 0.5;
				}
			}
			$pdf->Cell(30,4,trim($r['MITM_SPTNO']),1,0,'L');
            $pdf->SetFont('Arial','',9);
			$pdf->Cell(35,4,trim($r['SER_DOC']),1,0,'L');
			$pdf->Cell(10,4,number_format($r['SCRSER_QTY']),1,0,'R');
			$pdf->Cell(15,4,trim($r['ITH_LOC']),1,0,'L');
			$pdf->Cell(30,4,'',1,0,'L');
			$cury+=4; $no++;
		}
		$pdf->Output('I','Scraping Doc '.$pser.' ' .date("d-M-Y").'.pdf');
	}
    function vscrapreport(){
        $data['userID'] = $this->session->userdata('nama'); 
        $this->load->view('scrap_report/rpscrap', $data);
    }
    function vscrapreportlist(){
        $data['userID'] = $this->session->userdata('nama'); 
        $this->load->view('scrap_report/rpscraplist', $data);
    }
    function vuploadmapping(){
        $data['userID'] = $this->session->userdata('nama'); 
        $this->load->view('scrap_report/rpscrap_upload_mapping', $data);
    }
    function form_dispose(){
        $data['userID'] = $this->session->userdata('nama'); 
        $this->load->view('scrap_report/vform_dispose', $data);
    }

    function scrapreport_search(){
        header('Content-Type: application/json');
        $search = $this->input->get('search');
        $rs = $this->ZRPSCRAP_HIST_mod->select_header(['ID_TRANS' => $search]);
        die(json_encode(['data' => $rs]));
    }
}
