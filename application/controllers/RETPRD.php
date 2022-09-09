<?php
defined('BASEPATH') OR exit('No direct script access allowed');
use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Csv;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;
class RETPRD extends CI_Controller {
    private $AMONTHPATRN = ['1','2','3', '4', '5', '6', '7', '8', '9', 'X', 'Y' , 'Z'];
    public function __construct()
    {
        parent::__construct();		
        $this->load->helper('url');
        $this->load->library('session');		
        $this->load->library('Code39e128');
        $this->load->model('MADE_mod');
        $this->load->model('SPLRET_mod');
        $this->load->model('SPL_mod');
        $this->load->model('SPLSCN_mod');
        $this->load->model('ITH_mod');	
        $this->load->model('C3LC_mod');
        $this->load->model('RETRM_mod');
    }
    public function index()
    {
        die("sorry");
    }

    public function listofcountry(){
        header('Content-Type: application/json');
        $rs = $this->MADE_mod->selectAll();
        echo '{"data":'.json_encode($rs).'}';
    }

    public function get_psn_list(){
        date_default_timezone_set('Asia/Jakarta');
        header('Content-Type: application/json');
        $currentYear = date('Y');
        $currentMonth = date('m');
        $rs = $this->SPLRET_mod->select_psn($currentYear, $currentMonth);
        $rs_j = [];
        foreach($rs as $r){
            $rs_j[] = [
                'id' => trim($r['RETSCN_SPLDOC'])
                ,'text' => trim($r['RETSCN_SPLDOC'])
            ];
        }
        exit(json_encode($rs_j));
    }

    public function revise(){
        $this->checkSession();
        date_default_timezone_set('Asia/Jakarta');
        header('Content-Type: application/json');
        $currentDate = date('Y-m-d');
        $currentDateTime = date('Y-m-d H:i:s');
        $cidscan = $this->input->post('inidscan');
        $cpsn = $this->input->post('inpsn');
        $cline = $this->input->post('inline');
        $cfr = $this->input->post('infr');
        $ccat = $this->input->post('incat');
        $citemcode = $this->input->post('initemcode');
        $coldactqty = $this->input->post('inoldactqty');
        $cqty = $this->input->post('inqty');
        $rs = $this->SPLRET_mod->selectby_filter(['RETSCN_ID' => $cidscan ,'RETSCN_SAVED' => '1']);	
        $rsconfirmed = $this->ITH_mod->select_confirmdate_psn($cpsn);		
        $confirmdatetime = '';
        foreach($rsconfirmed as $c){
            $confirmdatetime = $c['ITH_LUPDT'];
        }
        $rsbg = $this->SPL_mod->select_bg_ppsn([$cpsn]);
        $cwh_base = '';
        $cwh_plant = '';
        foreach($rsbg as $r){
            switch($r['PPSN1_BSGRP']){
                case 'PSI1PPZIEP':
                    $cwh_base = 'ARWH1';
                    $cwh_plant = 'PLANT1';
                    break;
                case 'PSI2PPZADI':
                    $cwh_base = 'ARWH2';
                    $cwh_plant = 'PLANT2';
                    break;
                case 'PSI2PPZINS':
                    $cwh_base = 'NRWH2';
                    $cwh_plant = 'PLANT_NA';
                    break;
                case 'PSI2PPZOMC':
                    $cwh_base = 'NRWH2';
                    $cwh_plant = 'PLANT_NA';
                    break;
                case 'PSI2PPZOMI':
                    $cwh_base = 'ARWH2';
                    $cwh_plant = 'PLANT2';
                    break;
                case 'PSI2PPZSSI':
                    $cwh_base = 'NRWH2';
                    $cwh_plant = 'PLANT_NA';
                    break;
                case 'PSI2PPZSTY':
                    $cwh_base = 'ARWH2';
                    $cwh_plant = 'PLANT2';
                    break;
                case 'PSI2PPZTDI':
                    $cwh_base = 'ARWH2';
                    $cwh_plant = 'PLANT2';
                    break;
            }				
        }

        $myar = [];
        $ithdoc = $cpsn.'|'.$ccat.'|'.$cline.'|'.$cfr;
        if(count($rs)>0){
            if($this->SPLRET_mod->updatebyVars(['RETSCN_QTYAFT' => $cqty], ['RETSCN_ID' => $cidscan, 'RETSCN_SPLDOC' => $cpsn])>0 ){				
                $diff = $cqty - $coldactqty;
                $datas[] = [
                    'ITH_ITMCD' => $citemcode,
                    'ITH_DATE' => $confirmdatetime == '' ? $currentDate : substr($confirmdatetime,0,10),
                    'ITH_FORM' => 'REV-RET',
                    'ITH_DOC' => $ithdoc,
                    'ITH_QTY' => $diff,
                    'ITH_WH' => $cwh_base,
                    'ITH_REMARK' => $cidscan,
                    'ITH_LUPDT' => $confirmdatetime == '' ? $currentDateTime : $confirmdatetime,
                    'ITH_USRID' => $this->session->userdata('nama')
                ];
                $datas[] = [
                    'ITH_ITMCD' => $citemcode,
                    'ITH_DATE' => $confirmdatetime == '' ? $currentDate : substr($confirmdatetime,0,10),
                    'ITH_FORM' => 'REV-RET',
                    'ITH_DOC' => $ithdoc,
                    'ITH_QTY' => -1*$diff,
                    'ITH_WH' => $cwh_plant,
                    'ITH_REMARK' => $cidscan,
                    'ITH_LUPDT' => $confirmdatetime == '' ? $currentDateTime : $confirmdatetime,
                    'ITH_USRID' => $this->session->userdata('nama')
                ];
                $this->ITH_mod->insertb($datas);
                $myar[] = ['cd' => 1, 'msg' => 'OK.'];
            } else {
                $myar[] = ['cd' => 0, 'msg' => 'Revision is failed.'];
            }
        } else {
            if($this->SPLRET_mod->updatebyVars(['RETSCN_QTYAFT' => $cqty], ['RETSCN_ID' => $cidscan, 'RETSCN_SPLDOC' => $cpsn])>0 ){				
                $myar[] = ['cd' => 1, 'msg' => 'OK'];
            } else {
                $myar[] = ['cd' => 0, 'msg' => 'Revision is failed'];
            }
        }
        exit(json_encode(['status' => $myar]));
    }

    public function get_data_bypsn(){
        header('Content-Type: application/json');
        $cpsn = $this->input->get('indoc');
        $rs = $this->SPLRET_mod->selectby_filter(['RETSCN_SPLDOC' => $cpsn]);
        exit('{"data":'.json_encode($rs).'}');
    }

    public function create(){
        $data['lmade'] = $this->MADE_mod->selectAll();
        $this->load->view('wms/vretprd', $data);
    }
    public function createrevision(){		
        $this->load->view('wms/vretprd_revision');
    }
    public function analyze(){
        $this->load->view('wms/vretprd_analyze');
    }	

    public function tesprint($pitem, $plot, $pqty, $pitemnm,$puserid, $pusernm, $pcountry, $prohs){
        $pqtyi = str_replace(",","", $pqty);
        $pip = $_SERVER['REMOTE_ADDR'];
        $host = gethostbyaddr($_SERVER['REMOTE_ADDR']);
        $statementRohs = $prohs=='1' ? '^FO20,310^FDRoHS Compliant^FS' : '';
        $datadesign = "^XA
        ^FX Second section with recipient address and permit information.
        ^CFA,15
        ^FO20,30^FDITEM CODE : $pitem  $host^FS
        ^FO20,55^FDQTY : $pqty^FS
        ^FO20,80^FD(3N1) $pitem^FS
        ^FO20,100^BY1
        ^BCN,45,N,N,N
        ^FD3N1$pitem^FS
        ^FO20,150^FD(3N2) $pqty $plot^FS
        ^FO20,170
        ^BCN,45,N,N,N
        ^FD3N2 $pqtyi $plot^FS
        ^FO20,220^FD(1P) $pitemnm^FS
        ^FO20,240
        ^BCN,45,N,N,N
        ^FD1P$pitemnm^FS
        ^FO20,290^FDPART NO : $pitemnm^FS
        $statementRohs
        ^FO290,310^FDC/O : Made in $pcountry^FS
        ^FO20,330^FD$puserid : $pusernm^FS
        ^FO290,330^FDtanggal^FS
        ^CFA,15
        ^XZ";
        file_put_contents("d:\dpd.prn", $datadesign);		
        try
        {			
            $str = 'COPY /B d:\dpd.prn "\\\\'.$pip.'\Citizen CL-S621Z"';			
            $cmd = escapeshellcmd($str);
            $op = shell_exec($cmd);				
        }
        catch (Exception $e) 
        {
            echo 'Caught exception: ',  $e->getMessage(), "\n";
        }
    }	
    
    public function save_desktop(){
        date_default_timezone_set('Asia/Jakarta');
        $currdate = date('Ymd');
        
        $currrtime = date('Y-m-d H:i:s');
        $cpsn = $this->input->post('inpsn');
        $ccat = $this->input->post('incat');
        $clne = $this->input->post('inline');
        
        $citm = $this->input->post('initmcd');
        $crhs = $this->input->post('inrohs');
        $cqbf = $this->input->post('inqtybef');
        $cqaf = $this->input->post('inqtyaft');
        $clot = $this->input->post('inlot');
        $ccry = $this->input->post('incountry');
        $cuser = $this->input->post('inuser');
        $myar = [];
            
        $dataw = ['SPL_DOC' => $cpsn , 'SPL_CAT' => $ccat, 'SPL_LINE' => $clne,  'SPL_ITMCD' => $citm];
        if($this->SPL_mod->check_Primary($dataw)>0){
            //validate from SPLSCN_TBL
            $datascn = [
                'SPLSCN_DOC' => $cpsn, 'SPLSCN_CAT' => $ccat, 'SPLSCN_LINE' => $clne, 
                'SPLSCN_ITMCD' => $citm, 'SPLSCN_LOTNO' => $clot
            ];
            $ttldata_psnscan = $this->SPLSCN_mod->check_Primary($datascn);
            if($ttldata_psnscan==0){
                $myar[] = ['cd' => '01', 'msg' => 'PSN and label does not match'];
                // die('{"status":'.json_encode($myar).'}');
                die('PSN and label does not match');
            }
            //end validate

            // validate ttlqty_psn_scan vs ttlqty_psn_ret
            $rs_vrs = $this->SPLRET_mod->selectsp_vs_ret_nofr($cpsn, $ccat, $clne, $citm);
            $allow =false;
            $orderno = '';
            $fr = '';
            foreach($rs_vrs as $r){
                if((trim($r['SPLSCN_ITMCD'])==trim($citm)) && (trim($r['SPLSCN_LOTNO'])==trim($clot)) && ($r['SPLSCN_QTY']>=$cqbf) ){
                    if($r['SPLSCN_QTY']>=($r['RETQTY'] + $cqaf) ){
                        $orderno = $r['SPLSCN_ORDERNO'];
                        $fr = trim($r['SPLSCN_FEDR']);
                        $allow=true;
                        break;
                    }
                }
            }
            if(!$allow){
                $allow2=false;
                foreach($rs_vrs as $r){
                    if((trim($r['SPLSCN_ITMCD'])==trim($citm)) && (trim($r['SPLSCN_LOTNO'])==trim($clot))){
                        if($r['SPLSCN_QTY']>=($r['RETQTY'] + $cqaf) ){
                            $orderno = $r['SPLSCN_ORDERNO'];
                            $fr = trim($r['SPLSCN_FEDR']);
                            $allow2=true;
                            break;
                        }
                    }
                }
                if(!$allow2){
                    // $myar[] = ['cd' => '02', 'msg' => 'could not add because the label is already returned'];
                    // // die('{"status":'.json_encode($myar).'}');
                    die('could not add because the label is already returned');					
                }
            }
            //end validate

            $mlastid = $this->SPLRET_mod->lastserialid();
            $mlastid++;
            $newid = $currdate.$mlastid;
            $datas = [
                'RETSCN_ID' =>  $newid, 'RETSCN_SPLDOC' => $cpsn, 'RETSCN_CAT' => $ccat, 'RETSCN_LINE' => $clne, 'RETSCN_FEDR' => $fr, 'RETSCN_ORDERNO' => $orderno ,
                'RETSCN_ITMCD' => $citm, 'RETSCN_LOT' => trim($clot), 'RETSCN_QTYBEF' => $cqbf, 'RETSCN_QTYAFT' => $cqaf, 'RETSCN_CNTRYID' => $ccry, 
                'RETSCN_ROHS' => $crhs , 'RETSCN_LUPDT' => $currrtime, 'RETSCN_USRID' => $cuser
            ];
            $toret = $this->SPLRET_mod->insert($datas);
            if($toret>0){									
                $myar[] = ['cd' => '11' , 'msg' => 'Saved'];
                // die('{"status":'.json_encode($myar).'}');
                die('Saved');
            }
        } else {
            $myar[] = ['cd' => '00' , 'msg' => 'Sorry, Item not found in PSN'];
            // die('{"status":'.json_encode($myar).'}');
            die('Sorry, Item not found in PSN');
        }
    }

    public function save_desktop_v02(){
        header('Content-Type: application/json');
        date_default_timezone_set('Asia/Jakarta');
        $currdate = date('Ymd');
        
        $currrtime = date('Y-m-d H:i:s');
        $cpsn = $this->input->post('inpsn');
        $ccat = $this->input->post('incat');
        $clne = $this->input->post('inline');
        
        $citm = $this->input->post('initmcd');
        $crhs = $this->input->post('inrohs');
        $cqbf = $this->input->post('inqtybef');
        $cqaf = $this->input->post('inqtyaft');
        $clot = $this->input->post('inlot');
        $ccry = $this->input->post('incountry');
        $cuser = $this->input->post('inuser');
        $myar = [];
            
        $dataw = ['SPL_DOC' => $cpsn , 'SPL_CAT' => $ccat, 'SPL_LINE' => $clne,  'SPL_ITMCD' => $citm];
        if($this->SPL_mod->check_Primary($dataw)>0){
            //validate from SPLSCN_TBL
            $datascn = [
                'SPLSCN_DOC' => $cpsn, 'SPLSCN_CAT' => $ccat, 'SPLSCN_LINE' => $clne, 
                'SPLSCN_ITMCD' => $citm, 'SPLSCN_LOTNO' => $clot
            ];
            $ttldata_psnscan = $this->SPLSCN_mod->check_Primary($datascn);
            if($ttldata_psnscan==0){
                $myar[] = ['cd' => '01', 'msg' => 'PSN and label does not match'];
                die('{"status":'.json_encode($myar).'}');				
            }
            //end validate

            // validate ttlqty_psn_scan vs ttlqty_psn_ret
            $rs_vrs = $this->SPLRET_mod->selectsp_vs_ret_nofr($cpsn, $ccat, $clne, $citm);
            $allow = false;
            $orderno = '';
            $fr = '';
            foreach($rs_vrs as $r){
                if((trim($r['SPLSCN_ITMCD'])==trim($citm)) && (trim($r['SPLSCN_LOTNO'])==trim($clot)) && ($r['SPLSCN_QTY']>=$cqbf) ){
                    if($r['SPLSCN_QTY']>=($r['RETQTY'] + $cqaf) ){
                        $orderno = $r['SPLSCN_ORDERNO'];
                        $fr = trim($r['SPLSCN_FEDR']);
                        $allow=true;
                        break;
                    }
                }
            }
            if(!$allow){
                $allow2=false;
                foreach($rs_vrs as $r){
                    if((trim($r['SPLSCN_ITMCD'])==trim($citm)) && (trim($r['SPLSCN_LOTNO'])==trim($clot))){
                        if($r['SPLSCN_QTY']>=($r['RETQTY'] + $cqaf) ){
                            $orderno = $r['SPLSCN_ORDERNO'];
                            $fr = trim($r['SPLSCN_FEDR']);
                            $allow2=true;
                            break;
                        }
                    }
                }
                if(!$allow2){					
                    $rs_vrs = $this->SPLRET_mod->select_balance_peritem($cpsn, $clne, $ccat, $citm);				
                    $rs_vrs = count($rs_vrs) >0 ? reset($rs_vrs) : ['BALQTY' => 0];
                    if($rs_vrs['BALQTY']>= $cqaf){
                        #GET FR , ORDERNO
                        $rsbefore = $this->SPLSCN_mod
                        ->selectby_filter(['SPLSCN_DOC' => $cpsn, 'SPLSCN_CAT' => $ccat, 'SPLSCN_LINE' => $clne
                         , 'SPLSCN_ITMCD' => $citm, 'SPLSCN_LOTNO' => $clot,'SPLSCN_QTY' => $cqbf
                         ]);
                        #END
                        if(!empty($rsbefore)){
                            $rsbefore = reset($rsbefore);
                            $orderno = $rsbefore['SPLSCN_ORDERNO'];
                            $fr = trim($rsbefore['SPLSCN_FEDR']);																					
                        } else {
                            $myar[] = ['cd' => '00', 'msg' => 'could not get FR and ORDER NO'];	
                            die('{"status":'.json_encode($myar).'}');							
                        }
                    } else {
                        $myar[] = ['cd' => '00', 'msg' => 'Balance Qty < Return Qty'];	
                        die('{"status":'.json_encode($myar).'}');										
                    }					
                }
            }
            //end validate

            $mlastid = $this->SPLRET_mod->lastserialid();
            $mlastid++;
            $newid = $currdate.$mlastid;
            $datas = [
                'RETSCN_ID' =>  $newid, 'RETSCN_SPLDOC' => $cpsn, 'RETSCN_CAT' => $ccat, 'RETSCN_LINE' => $clne, 'RETSCN_FEDR' => $fr, 'RETSCN_ORDERNO' => $orderno ,
                'RETSCN_ITMCD' => $citm, 'RETSCN_LOT' => trim($clot), 'RETSCN_QTYBEF' => $cqbf, 'RETSCN_QTYAFT' => $cqaf, 'RETSCN_CNTRYID' => $ccry, 
                'RETSCN_ROHS' => $crhs , 'RETSCN_LUPDT' => $currrtime, 'RETSCN_USRID' => $cuser
            ];
            $toret = $this->SPLRET_mod->insert($datas);
            if($toret>0){									
                $myar[] = ['cd' => '11' , 'msg' => 'Saved'];
                die('{"status":'.json_encode($myar).'}');				
            }
        } else {
            $myar[] = ['cd' => '00' , 'msg' => 'Sorry, Item not found in PSN'];
            die('{"status":'.json_encode($myar).'}');			
        }
    }

    public function save_combine_desktop_v01(){
        header('Content-Type: application/json');
        date_default_timezone_set('Asia/Jakarta');
        $currdate = date('Ymd');
        $myar = [];
        $currrtime = date('Y-m-d H:i:s');
        $cpsn = $this->input->post('inpsn');
        $ccat = $this->input->post('incat');
        $clne = $this->input->post('inline');

        $citm = $this->input->post('initmcd');
        $clot = $this->input->post('inlot');
        $cqbf = $this->input->post('inqtybef');
        $cqaf = $this->input->post('inqtyaft');
        $crhs = $this->input->post('inrohs');
        $cuser = $this->input->post('inuser');
        $ccry = $this->input->post('incountry');
        if(is_array($citm)){
            $dataw = ['SPL_DOC' => $cpsn , 'SPL_CAT' => $ccat, 'SPL_LINE' => $clne,  'SPL_ITMCD' => $citm[0]];
            if($this->SPL_mod->check_Primary($dataw)>0){
                $ttldata = count($citm);
                $isItemlotOK = true;
                $C3Data = [];

                $lotasHome = $clot[0];
                if($cqaf > $cqbf[0] && $clot[0]!= $clot[1] ){
                    $lotasHome = substr($clot[0], 0, 10);
                    $lotasHome.= '#C';
                }
                #PREPARE NEW ROW ID
                $mlastid = $this->SPLRET_mod->lastserialid();
                $mlastid++;
                $newid = $currdate.$mlastid;
                #END
                for($i=0; $i< $ttldata; $i++){
                    $datascn = [
                        'SPLSCN_DOC' => $cpsn, 'SPLSCN_CAT' => $ccat, 'SPLSCN_LINE' => $clne, 
                        'SPLSCN_ITMCD' => $citm[$i], 'SPLSCN_LOTNO' => $clot[$i]
                    ];
                    $ttldata_psnscan = $this->SPLSCN_mod->check_Primary($datascn);
                    if($ttldata_psnscan==0){						
                        $isItemlotOK = false;
                        break;
                    }
                    $C3Data[] = ['C3LC_ITMCD' => $citm[0], 'C3LC_NLOTNO' => $lotasHome, 'C3LC_NQTY' => $cqaf,'C3LC_LOTNO' => $clot[$i]
                    , 'C3LC_QTY' => $cqbf[$i], 'C3LC_REFF' => $newid, 'C3LC_LINE' => $i,  'C3LC_USRID' => $cuser, 'C3LC_LUPTD' => $currrtime];
                }
                if($isItemlotOK){
                    $rs_vrs = $this->SPLRET_mod->select_balance_peritem($cpsn, $clne, $ccat, $citm[0]);				
                    $rs_vrs = count($rs_vrs) >0 ? reset($rs_vrs) : ['BALQTY' => 0];
                    if($rs_vrs['BALQTY']>= $cqaf){
                        #GET FR , ORDERNO
                        $rsbefore = $this->SPLSCN_mod
                        ->selectby_filter(['SPLSCN_DOC' => $cpsn, 'SPLSCN_CAT' => $ccat, 'SPLSCN_LINE' => $clne
                         , 'SPLSCN_ITMCD' => $citm[0], 'SPLSCN_LOTNO' => $clot[0],'SPLSCN_QTY' => $cqbf[0]
                         ]);
                        #END
                        if(count($rsbefore)>0){
                            $this->C3LC_mod->insertb($C3Data);
                            $rsbefore = reset($rsbefore);							
                            $datas = [
                                'RETSCN_ID' =>  $newid, 'RETSCN_SPLDOC' => $cpsn, 'RETSCN_CAT' => $ccat, 'RETSCN_LINE' => $clne, 'RETSCN_FEDR' => $rsbefore['SPLSCN_FEDR'], 'RETSCN_ORDERNO' => $rsbefore['SPLSCN_ORDERNO'] ,
                                'RETSCN_ITMCD' => $citm[0], 'RETSCN_LOT' => $lotasHome, 'RETSCN_QTYBEF' => $cqbf[0], 'RETSCN_QTYAFT' => $cqaf, 'RETSCN_CNTRYID' => $ccry, 
                                'RETSCN_ROHS' => $crhs , 'RETSCN_LUPDT' => $currrtime, 'RETSCN_USRID' => $cuser
                            ];
                            $toret = $this->SPLRET_mod->insert($datas);
                            if($toret>0){
                                $myar[] = ['cd' => '11' , 'msg' => 'Saved', 'lotno' => $lotasHome];
                                die('{"status":'.json_encode($myar).'}');
                            }
                        } else {
                            $myar[] = ['cd' => '00', 'msg' => 'could not get FR and ORDER NO'];	
                        }
                    } else {
                        $myar[] = ['cd' => '00', 'msg' => 'Balance Qty < Return Qty'];					
                    }
                } else {
                    $myar[] = ['cd' => '00', 'msg' => 'PSN and Item Lot does not match'];
                }			
            } else {
                $myar[] = ['cd' => '00', 'msg' => 'PSN and Item does not match'];
            }
        } else {
            $myar[] = ['cd' => '00', 'msg' => 'It seems You are using wrong menu or function'];			
        }
        die('{"status":'.json_encode($myar).'}');
    }		

    public function save_alt(){
        header('Content-Type: application/json');
        date_default_timezone_set('Asia/Jakarta');
        $currdate = date('Ymd');		
        $currrtime = date('Y-m-d H:i:s');
        $cpsn = $this->input->post('inpsn');
        $ccat = $this->input->post('incat');
        $clne = $this->input->post('inline');
        
        $citm = trim($this->input->post('initmcd'));
        $crhs = $this->input->post('inrohs');
        $cqbf = "";
        $cqaf = $this->input->post('inqtyaft');
        $clot = "";
        $ccry = $this->input->post('incountry');
        $citemnm = '';

        $rs_vrs = $this->SPLRET_mod->selectsp_vs_ret_nofr($cpsn, $ccat, $clne, $citm);
        $allow = false;
        $orderno = '';
        $fr = '';
        $myar = [];
        foreach($rs_vrs as $r){
            if((trim($r['SPLSCN_ITMCD'])==$citm) && ($r['RLOGICQTY']>$cqaf)){
                $orderno = $r['SPLSCN_ORDERNO'];
                $fr = trim($r['SPLSCN_FEDR']);
                $clot = trim($r['SPLSCN_LOTNO']);
                $cqbf = trim($r['SPLSCN_QTY']);
                $citemnm = trim($r['MITM_SPTNO']);
                $allow = true;
                break;
            }
        }
        if($allow){
            $mlastid = $this->SPLRET_mod->lastserialid();
            $mlastid++;
            $newid = $currdate.$mlastid;
            $datas = [
                'RETSCN_ID' =>  $newid, 'RETSCN_SPLDOC' => $cpsn, 'RETSCN_CAT' => $ccat, 'RETSCN_LINE' => $clne, 'RETSCN_FEDR' => $fr, 'RETSCN_ORDERNO' => $orderno ,
                'RETSCN_ITMCD' => $citm, 'RETSCN_LOT' => $clot, 'RETSCN_QTYBEF' => $cqbf, 'RETSCN_QTYAFT' => $cqaf, 'RETSCN_CNTRYID' => $ccry, 
                'RETSCN_ROHS' => $crhs , 'RETSCN_LUPDT' => $currrtime, 'RETSCN_RMRK' => 'ANYWAY' , 'RETSCN_USRID' => $this->session->userdata('nama')
            ];
            $toret = $this->SPLRET_mod->insert($datas);
            if($toret>0){
                $myar[] = ['cd' => '11' , 'msg' => 'Saved'
                            , "xitem" => $citm, "xqty" => $cqaf, "xlot" => $clot, "xitemnm" =>  $citemnm];
                die('{"status":'.json_encode($myar).'}');
            }
        } else {
            $myar[] = ['cd' => '00', 'msg' => 'could not return, please contact Mr. H '];
            die('{"status":'.json_encode($myar).'}');
        }
    }
    public function save_alt_desktop(){
        header('Content-Type: application/json');
        date_default_timezone_set('Asia/Jakarta');
        $currdate = date('Ymd');		
        $currrtime = date('Y-m-d H:i:s');
        $cpsn = $this->input->post('inpsn');
        $ccat = $this->input->post('incat');
        $clne = $this->input->post('inline');
        
        $citm = trim($this->input->post('initmcd'));
        $crhs = $this->input->post('inrohs');
        $cqbf = "";
        $cqaf = $this->input->post('inqtyaft');
        $clot = "";
        $ccry = $this->input->post('incountry');
        $citemnm = '';
        $cuser = $this->input->post('inuser');

        $rs_vrs = $this->SPLRET_mod->selectsp_vs_ret_nofr($cpsn, $ccat, $clne, $citm);
        $allow = false;
        $orderno = '';
        $fr = '';
        $myar = [];
        foreach($rs_vrs as $r){
            if((trim($r['SPLSCN_ITMCD'])==$citm) && ($r['RLOGICQTY']>$cqaf)){
                $orderno = $r['SPLSCN_ORDERNO'];
                $fr = trim($r['SPLSCN_FEDR']);
                $clot = trim($r['SPLSCN_LOTNO']);
                $cqbf = trim($r['SPLSCN_QTY']);
                $citemnm = trim($r['MITM_SPTNO']);
                $allow = true;
                break;
            }
        }
        if($allow){
            $mlastid = $this->SPLRET_mod->lastserialid();
            $mlastid++;
            $newid = $currdate.$mlastid;
            $datas = [
                'RETSCN_ID' =>  $newid, 'RETSCN_SPLDOC' => $cpsn, 'RETSCN_CAT' => $ccat, 'RETSCN_LINE' => $clne, 'RETSCN_FEDR' => $fr, 'RETSCN_ORDERNO' => $orderno ,
                'RETSCN_ITMCD' => $citm, 'RETSCN_LOT' => $clot, 'RETSCN_QTYBEF' => $cqbf, 'RETSCN_QTYAFT' => $cqaf, 'RETSCN_CNTRYID' => $ccry, 
                'RETSCN_ROHS' => $crhs , 'RETSCN_LUPDT' => $currrtime, 'RETSCN_RMRK' => 'ANYWAY' , 'RETSCN_USRID' => $cuser
            ];
            $toret = $this->SPLRET_mod->insert($datas);
            if($toret>0){
                $myar[] = ['cd' => '11' , 'msg' => 'Saved'
                            , "xitem" => $citm, "xqty" => $cqaf, "xlot" => $clot, "xitemnm" =>  $citemnm];
                die('{"status":'.json_encode($myar).'}');
            }
        } else {
            $myar[] = ['cd' => '00', 'msg' => 'could not return, please contact Mr. H '];
            die('{"status":'.json_encode($myar).'}');
        }
    }

    public function save(){
        header('Content-Type: application/json');
        date_default_timezone_set('Asia/Jakarta');
        $currdate = date('Ymd');
        
        $currrtime = date('Y-m-d H:i:s');
        $cpsn = $this->input->post('inpsn');
        $ccat = $this->input->post('incat');
        $clne = $this->input->post('inline');
        
        $citm = trim($this->input->post('initmcd'));
        $crhs = $this->input->post('inrohs');
        $cqbf = $this->input->post('inqtybef');
        $cqaf = $this->input->post('inqtyaft');
        $clot = trim($this->input->post('inlot'));
        $ccry = $this->input->post('incountry');

        $myar = [];
            
        $dataw = ['SPL_DOC' => $cpsn , 'SPL_CAT' => $ccat, 'SPL_LINE' => $clne,  'SPL_ITMCD' => $citm];
        if($this->SPL_mod->check_Primary($dataw)>0){
            //validate from SPLSCN_TBL
            $datascn = [
                'SPLSCN_DOC' => $cpsn, 'SPLSCN_CAT' => $ccat, 'SPLSCN_LINE' => $clne, 
                'SPLSCN_ITMCD' => $citm, 'SPLSCN_LOTNO' => $clot
            ];
            $ttldata_psnscan = $this->SPLSCN_mod->check_Primary($datascn);
            if($ttldata_psnscan==0){
                $myar[] = ['cd' => '01', 'msg' => 'PSN and label does not match'];
                die('{"status":'.json_encode($myar).'}');
            }
            //end validate

            // validate ttlqty_psn_scan vs ttlqty_psn_ret
            $rs_vrs = $this->SPLRET_mod->selectsp_vs_ret_nofr($cpsn, $ccat, $clne, $citm);
            $allow = false;
            $orderno = '';
            $fr = '';
            foreach($rs_vrs as $r){
                if((trim($r['SPLSCN_ITMCD'])==$citm) && (trim($r['SPLSCN_LOTNO'])==$clot) && ($r['SPLSCN_QTY']==$cqbf)){
                    if($r['SPLSCN_QTY']>=($r['RETQTY'] + $cqaf) ){
                        $orderno = $r['SPLSCN_ORDERNO'];
                        $fr = trim($r['SPLSCN_FEDR']);
                        $allow = true;
                        break;
                    }
                }
            }
            if(!$allow){
                $allow2=false;
                foreach($rs_vrs as $r){
                    if((trim($r['SPLSCN_ITMCD'])==$citm) && (trim($r['SPLSCN_LOTNO'])==$clot)){
                        if($r['SPLSCN_QTY']>=($r['RETQTY'] + $cqaf) ){
                            $orderno = $r['SPLSCN_ORDERNO'];
                            $fr = trim($r['SPLSCN_FEDR']);
                            $allow2 = true;
                            break;
                        }
                    }
                }
                if(!$allow2){
                    $myar[] = ['cd' => '02', 'msg' => 'could not add because the label is already returned'];
                    die('{"status":'.json_encode($myar).'}');
                }
            }
            //end validate

            $mlastid = $this->SPLRET_mod->lastserialid();
            $mlastid++;
            $newid = $currdate.$mlastid;
            $datas = [
                'RETSCN_ID' =>  $newid, 'RETSCN_SPLDOC' => $cpsn, 'RETSCN_CAT' => $ccat, 'RETSCN_LINE' => $clne, 'RETSCN_FEDR' => $fr, 'RETSCN_ORDERNO' => $orderno ,
                'RETSCN_ITMCD' => $citm, 'RETSCN_LOT' => $clot, 'RETSCN_QTYBEF' => $cqbf, 'RETSCN_QTYAFT' => $cqaf, 'RETSCN_CNTRYID' => $ccry, 
                'RETSCN_ROHS' => $crhs , 'RETSCN_LUPDT' => $currrtime, 'RETSCN_USRID' => $this->session->userdata('nama')
            ];
            $toret = $this->SPLRET_mod->insert($datas);
            if($toret>0){
                $myar[] = ['cd' => '11' , 'msg' => 'Saved'];
                die('{"status":'.json_encode($myar).'}');
            }
        } else {
            $myar[] = ['cd' => '00' , 'msg' => 'Sorry, Item not found in PSN'];
            die('{"status":'.json_encode($myar).'}');
        }		
    }

    public function checklot(){
        header('Content-Type: application/json');
        $cpsn = $this->input->get('inpsn');
        $ccat = $this->input->get('incat');
        $cline = $this->input->get('inline');
        $cfeeder = $this->input->get('infeeder');
        $citem = $this->input->get('initem');
        $clot = $this->input->get('inlot');
        $cqty = $this->input->get('inqty');
        $dataf = [
            'SPLSCN_DOC' => $cpsn, 'SPLSCN_CAT' => $ccat, 'SPLSCN_LINE' => $cline,
            'SPLSCN_FEDR' => $cfeeder, 'SPLSCN_ITMCD' => $citem, 'SPLSCN_LOTNO' => $clot , "SPLSCN_QTY >= $cqty" => null
        ];
        $ttlrows = $this->SPLSCN_mod->check_Primary($dataf);
        $myar = [];		
        if($ttlrows>0){
            $datar = ["cd" => $ttlrows, "msg" => "GO AHEAD"];
        } else {
            $datar = ["cd" => $ttlrows, "msg" => "Lot No not found"];
        }
        $myar[] = $datar;
        echo json_encode($myar);
    }

    public function checklot_nofr(){
        header('Content-Type: application/json');
        $cpsn = $this->input->get('inpsn');
        $ccat = $this->input->get('incat');
        $cline = $this->input->get('inline');
        
        $citem = $this->input->get('initem');
        $clot = $this->input->get('inlot');
        $cqty = $this->input->get('inqty');		
        $dataf = [
            'SPLSCN_DOC' => $cpsn, 'SPLSCN_CAT' => $ccat, 'SPLSCN_LINE' => $cline,
             'SPLSCN_ITMCD' => $citem, 'SPLSCN_LOTNO' => $clot , "SPLSCN_QTY >= $cqty" => null
        ];				
        $ttlrows = $this->SPLSCN_mod->check_Primary($dataf);
        $myar = [];
        if($ttlrows>0){
            $myar[] = ["cd" => $ttlrows, "msg" => "GO AHEAD"];
        } else {
            $myar[] = ["cd" => $ttlrows, "msg" => "Lot No not found"];
        }		
        echo '{"status":'.json_encode($myar).'}';
    }
    public function checklot_nofr_new(){
        header('Content-Type: application/json');
        $cpsn = $this->input->post('inpsn');
        $ccat = $this->input->post('incat');
        $cline = $this->input->post('inline');
        
        $citem = $this->input->post('initem');
        $clot = $this->input->post('inlot');
        $cqty = $this->input->post('inqty');
        $clot = str_replace(" ", "+", $clot);
        
        $dataf = [
            'SPLSCN_DOC' => $cpsn, 'SPLSCN_CAT' => $ccat, 'SPLSCN_LINE' => $cline,
             'SPLSCN_ITMCD' => $citem, 'SPLSCN_LOTNO' => $clot , 'SPLSCN_QTY >= '.$cqty => null
        ];				
        $ttlrows = $this->SPLSCN_mod->check_Primary($dataf, $cqty);
        $myar = [];
        if($ttlrows>0){
            $myar[] = ["cd" => $ttlrows, "msg" => "GO AHEAD"];
        } else {
            $myar[] = ["cd" => $ttlrows, "msg" => "Lot No not found $clot"];
        }		
        echo '{"status":'.json_encode($myar).'}';
    }

    public function getlist(){
        header('Content-Type: application/json');
        $cpsn 	= $this->input->get('inpsn');
        $ccat 	= $this->input->get('incat');
        $cline 	= $this->input->get('inline');
        $cfr 	= $this->input->get('infr');
        $dataw = ['RETSCN_SPLDOC' => $cpsn, 'RETSCN_CAT' => $ccat, 'RETSCN_LINE' => $cline, 'RETSCN_FEDR' => $cfr];
        $rs = $this->SPLRET_mod->selectby_filter($dataw);
        echo '{"data":'.json_encode($rs).'}';		
    }

    public function getlist_nofr(){
        header('Content-Type: application/json');
        $cpsn 	= $this->input->get('inpsn');
        $ccat 	= $this->input->get('incat');
        $cline 	= $this->input->get('inline');
        
        $dataw = ['RETSCN_SPLDOC' => $cpsn, 'RETSCN_CAT' => $ccat, 'RETSCN_LINE' => $cline];
        $rs = $this->SPLRET_mod->selectby_filter($dataw);
        echo '{"data":'.json_encode($rs).'}';		
    }

    public function getlistrecap(){
        header('Content-Type: application/json');
        $cpsn 	= $this->input->get('inpsn');
        $ccat 	= $this->input->get('incat');
        $cline 	= $this->input->get('inline');
        $cfr 	= $this->input->get('infr');		
        $rs = $this->SPLRET_mod->selectspl_sup_ret($cpsn, $ccat, $cline, $cfr);
        echo '{"data":'.json_encode($rs).'}';
    }

    public function getlistrecap_psnonly(){
        header('Content-Type: application/json');
        $cpsn 	= $this->input->get('inpsn');		
        $rs = $this->SPLRET_mod->selectspl_sup_ret_psnonly($cpsn);
        echo '{"data":'.json_encode($rs).'}';		
    }

    public function getdetail(){
        header('Content-Type: application/json');
        
        $cpsn 	= $this->input->get('inpsn');
        $ccat 	= $this->input->get('incat');
        $cline 	= $this->input->get('inline');
        $cfr 	= $this->input->get('infr');
        $citem = $this->input->get('initem');
        $cwhere = ['RETSCN_SPLDOC' => $cpsn, 'RETSCN_CAT' => $ccat, 'RETSCN_LINE' => $cline, 'RETSCN_FEDR' => $cfr , 'RETSCN_ITMCD' => $citem];
        $rs = $this->SPLRET_mod->selectfor_analyze($cwhere);
        echo '{"data":'.json_encode($rs).'}';
    }

    public function getdetail_psnonly(){
        header('Content-Type: application/json');
        
        $cpsn 	= $this->input->get('inpsn');		
        $citem = $this->input->get('initem');
        $cwhere = ['RETSCN_SPLDOC' => $cpsn, 'RETSCN_ITMCD' => $citem];
        $rs = $this->SPLRET_mod->selectfor_analyze($cwhere);
        echo '{"data":'.json_encode($rs).'}';		
    }

    function printlabel(){
        $pserial = '';
        if(isset($_COOKIE["CKPSI_IDRET"])){
            $pserial = $_COOKIE["CKPSI_IDRET"];
        } else {
            exit('no data');
        }
        $currrtime = date('d/m/Y H:i:s');
        $host = gethostbyaddr($_SERVER['REMOTE_ADDR']);         
        $a_ser = str_replace(".","','",$pserial);
        $a_ser = "'".$a_ser."'";
        $rs = $this->SPLRET_mod->selectbyid_in($a_ser);				

        $pdf = new PDF_Code39e128('L','mm',[70,50]);					
        $pdf->SetAutoPageBreak(true,1);
        $pdf->SetMargins(0,0);
        foreach($rs as $r){	
            $this->tesprint(trim($r->RETSCN_ITMCD),trim($r->RETSCN_LOT), number_format($r->RETSCN_QTYAFT), trim($r->MITM_SPTNO),
             $r->RETSCN_USRID,$r->MSTEMP_FNM,trim($r->MMADE_NM),$r->RETSCN_ROHS );
            $v3n1 = '(3N1) '.trim($r->RETSCN_ITMCD);
            $c3n1 = '3N1'.trim($r->RETSCN_ITMCD);
            $v3n2 = '(3N2) '.number_format($r->RETSCN_QTYAFT)." ".trim($r->RETSCN_LOT);	
            $c3n2 = '3N2 '.number_format($r->RETSCN_QTYAFT,0,"","")." ".trim($r->RETSCN_LOT);	
            $v1p = '(1P) '.trim($r->MITM_SPTNO);
            $c1p = '1P'.trim($r->MITM_SPTNO);
            
            $pdf->AddPage();							   
            $pdf->SetY(0); 
            $pdf->SetX(0);
            $pdf->SetFont('Courier','',7);					
            $pdf->Text(2, 3.5, 'ITEM CODE : '.$r->RETSCN_ITMCD.'   '.$host);			
            $pdf->Text(2, 6.5, 'QTY : '.number_format($r->RETSCN_QTYAFT));
            $pdf->Text(2, 9.5, $v3n1);
            $clebar = $pdf->GetStringWidth(trim($c3n1))+17;
            $pdf->Code128(2, 10.5,$c3n1, $clebar, 5);
            

            $pdf->Text(2, 18, $v3n2);
            $clebar = $pdf->GetStringWidth(trim($c3n2))+10;
            $pdf->Code128(2, 18.5,$c3n2, $clebar, 5);

            $pdf->Text(2, 26, $v1p);
            $clebar = $pdf->GetStringWidth(trim($c1p))+10;
            $pdf->Code128(2,26.5,trim($c1p),$clebar,5);
            
            $pdf->Text(2, 34, 'PART NO : '.trim($r->MITM_SPTNO));
            if($r->RETSCN_ROHS=='1'){
                $pdf->Text(2, 36, 'RoHS Compliant');
            }
            $pdf->Text(40, 36, 'C/O : Made in '.trim($r->MMADE_NM));
            $pdf->Text(2, 38, $r->RETSCN_USRID." : ".$r->MSTEMP_FNM);
            $pdf->Text(40, 38, $currrtime);
        }
        $pdf->Output('I','LBL-RET-PRD '.date("d-M-Y").'.pdf');		
    }

    public function export_to_csv(){
        if(!isset($_COOKIE["CKPSI_DPSN"]) && !isset($_COOKIE["CKPSI_DCAT"]) && !isset($_COOKIE["CKPSI_DLNE"]) && !isset($_COOKIE["CKPSI_DFDR"]) ){
            exit('no data to be found');
        }
        $cpsn = $_COOKIE["CKPSI_DPSN"];
        $ccat = $_COOKIE["CKPSI_DCAT"];
        $cline = $_COOKIE["CKPSI_DLNE"];
        $cfedr = $_COOKIE["CKPSI_DFDR"];
        $dataw = ['RETSCN_SPLDOC' => $cpsn, 'RETSCN_CAT' => $ccat, 'RETSCN_LINE' => $cline, 'RETSCN_FEDR' => $cfedr];
        $rs=$this->SPLRET_mod->selectformega($dataw);
        $stringjudul = 'Export Data RET-PSN';
        $spreadsheet = new Spreadsheet();
        $sheet = $spreadsheet->getActiveSheet();
        $sheet->setTitle('RET-PSN');
        $sheet->setCellValueByColumnAndRow(1,1, 'ITEMCODE');
        $sheet->setCellValueByColumnAndRow(2,1, 'GRN QTY');
        $no=2;
        foreach($rs as $r){
            $sheet->setCellValueByColumnAndRow(1,$no, $r['RETSCN_ITMCD']);
            $sheet->setCellValueByColumnAndRow(2,$no, $r['RETQTY']);
            $no++;
        }
        $writer = new Csv($spreadsheet);		
        $writer->setDelimiter(',');
        $writer->setEnclosure('');
        $writer->setLineEnding("\r\n");
        $writer->setSheetIndex(0);
        $filename=$stringjudul.date(' i s'); //save our workbook as this file name
        
        header('Content-Type: text/csv');
        header('Content-Disposition: attachment;filename="'. $filename .'.csv"'); 
        header('Cache-Control: max-age=0');
        $writer->save('php://output');
    }

    public function setholdrelease(){
        header('Content-Type: application/json');
        $cidscan = $this->input->post('inid');
        $csatus = $this->input->post('instatus');
        $myar = [];
        $ret = $this->SPLRET_mod->update_as_holdrelease($cidscan, $csatus);
        if($ret>0){
            $myar[] = ['cd' => 1, 'msg' => 'OK'];
        } else {
            $myar[] = ['cd' => 0, 'msg' => 'Could not Hold/Release'];
        }
        die('{"status":'.json_encode($myar).'}');
    }

    public function export_to_xls_desktop(){
        $cpsn = $this->input->get('inpsn');
        $rs = [];		
        $dataw = ['RETSCN_SPLDOC' => $cpsn, "ISNULL(RETSCN_HOLD,'0')" => "0"];
        $rs = $this->SPLRET_mod->selectformega($dataw);
        echo '{"data":'.json_encode($rs).'}';
    }

    public function export_to_xls(){
        if(!isset($_COOKIE["CKPSI_DPSN"])  ){
            exit('no data to be found');
        }
        date_default_timezone_set('Asia/Jakarta');
        $cpsn = $_COOKIE["CKPSI_DPSN"];
        
        $dataw = ['RETSCN_SPLDOC' => $cpsn];
        $rs=$this->SPLRET_mod->selectformega($dataw);
        $stringjudul = $cpsn;
        $spreadsheet = new Spreadsheet();
        $sheet = $spreadsheet->getActiveSheet();
        $sheet->setTitle('RET-PSN');
        $sheet->setCellValueByColumnAndRow(1,1, 'ITEMCODE');
        $sheet->setCellValueByColumnAndRow(2,1, 'GRN QTY');
        $no=2;
        foreach($rs as $r){
            $sheet->setCellValueByColumnAndRow(1,$no, $r['RETSCN_ITMCD']);
            $sheet->setCellValueByColumnAndRow(2,$no, $r['RETQTY']);
            $no++;
        }
        $writer = new Xlsx($spreadsheet);
        $filename=$stringjudul; //save our workbook as this file name
        
        header('Content-Type: application/vnd.ms-excel');
        header('Content-Disposition: attachment;filename="'. $filename .'.xlsx"'); 
        header('Cache-Control: max-age=0');
        $writer->save('php://output');
    }

    public function editing(){
        date_default_timezone_set('Asia/Jakarta');		
        
        $cwh_inc = $_COOKIE["CKPSI_WH"];		
        $cpsn 	= $this->input->post('inpsn');
        $ccat 	= $this->input->post('incat');
        $cline 	= $this->input->post('inline');
        $cfr 	= $this->input->post('infedr');
        $ithdoc = $cpsn.'|'.$ccat.'|'.$cline.'|'.$cfr;
        $citemcd 	= $this->input->post('initemcd');
        $citemid = $this->input->post('inid');
        $cqty = $this->input->post('inqty');		
        $cdate = $this->input->post('indate');

        $ttlrows = count($citemid);
        $rowaff = 0;
        $ithrowaff = 0;
        for($i=0;$i<$ttlrows;$i++){
            $datau = [
                'RETSCN_QTYAFT' => $cqty[$i],
                'RETSCN_SAVED' => '1'				
            ];
            $toret = $this->SPLRET_mod->updatebyId($datau, $citemid[$i]);
            if($toret>0){				
                $datas = [
                    'ITH_ITMCD' => $citemcd,
                    'ITH_DATE' => $cdate,
                    'ITH_FORM' => 'INC-RET',
                    'ITH_DOC' => $ithdoc,
                    'ITH_QTY' => $cqty[$i],
                    'ITH_WH' => $cwh_inc,
                    'ITH_REMARK' => $citemid[$i],
                    'ITH_USRID' =>  $this->session->userdata('nama')
                ];
                $aff =  $this->ITH_mod->insert_ret($datas);
                $ithrowaff += $aff;
            }
            $rowaff += $toret;
        }

        echo $rowaff." row(s) updated ".$ithrowaff." row(s) saved";
    }

    public function editing_byitempsn(){
        date_default_timezone_set('Asia/Jakarta');	
        $rowaff = 0;
        $ithrowaff = 0;
        $cwh_inc = $_COOKIE["CKPSI_WH"];
        $cpsn = $this->input->post('inpsn');
        $cdate = $this->input->post('indate');
        $citemcd = $this->input->post('initemcd');
        $thetime = date('H:i:s');
        $thelupdt = $cdate." ".$thetime;
        $ttlitem = count($citemcd);
        if($ttlitem>0){
            $rsbg = $this->SPL_mod->select_bg_ppsn([$cpsn]);			
            foreach($rsbg as $r){
                switch($r['PPSN1_BSGRP']){
                    case 'PSI1PPZIEP':
                        $cwh_inc = 'ARWH1';
                        break;
                    case 'PSI2PPZADI':
                        $cwh_inc = 'ARWH2';
                        break;
                    case 'PSI2PPZINS':
                        $cwh_inc = 'NRWH2';
                        break;
                    case 'PSI2PPZOMC':
                        $cwh_inc = 'NRWH2';
                        break;
                    case 'PSI2PPZOMI':
                        $cwh_inc = 'ARWH2';
                        break;
                    case 'PSI2PPZSSI':
                        $cwh_inc = 'NRWH2';
                        break;
                    case 'PSI2PPZSTY':
                        $cwh_inc = 'ARWH2';
                        break;
                    case 'PSI2PPZTDI':
                        $cwh_inc = 'ARWH2';
                        break;
                }				
            }			
        }
        for($b=0;$b<$ttlitem; $b++){
            $rsret = $this->SPLRET_mod->selectby_filter(['RETSCN_SPLDOC' => $cpsn, 'RETSCN_ITMCD' => $citemcd[$b]]);
            foreach($rsret as $r){
                $datau = [
                    'RETSCN_QTYAFT' => $r['RETSCN_QTYAFT'],
                    'RETSCN_SAVED' => '1'				
                ];
                $toret = $this->SPLRET_mod->updatebyId($datau, $r['RETSCN_ID']);
                if($toret>0){
                    $ithdoc = $cpsn.'|'.trim($r['RETSCN_CAT']).'|'.trim($r['RETSCN_LINE']).'|'.trim($r['RETSCN_FEDR']);					
                    $datas = [
                        'ITH_ITMCD' => $citemcd[$b],
                        'ITH_DATE' => $cdate,
                        'ITH_FORM' => 'INC-RET',
                        'ITH_DOC' => $ithdoc,
                        'ITH_QTY' => $r['RETSCN_QTYAFT'],
                        'ITH_WH' => $cwh_inc,
                        'ITH_REMARK' => $r['RETSCN_ID'],
                        'ITH_LUPDT' => $thelupdt,
                        'ITH_USRID' =>  $this->session->userdata('nama')
                    ];
                    $aff =  $this->ITH_mod->insert_ret($datas);
                    $ithrowaff += $aff;
                }
                $rowaff += $toret;
            }
        }
        echo $rowaff." row(s) updated ".$ithrowaff." row(s) saved";
    }

    function auto_confirm()
    {
        date_default_timezone_set('Asia/Jakarta');
        $current_date = date('Y-m');
        $psn_last_month = date('Y-m', strtotime("first day of previous month"));
        $psn_current_month = date('Y-m-d');

        $rspsn_last_month = $this->ITH_mod->select_unconfirmed_psn($psn_last_month);
        $rspsn_current_month = $this->ITH_mod->select_unconfirmed_psn($psn_current_month);        
        
        $rspsn_union = array_merge($rspsn_last_month,$rspsn_current_month);
        
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, 'http://192.168.0.29:8081/wms/RETPRD/editing_byitempsn_desktop');
        curl_setopt($ch, CURLOPT_POST, true);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
        curl_setopt($ch, CURLOPT_CONNECTTIMEOUT, 0);
        curl_setopt($ch, CURLOPT_TIMEOUT, 3600);

        foreach($rspsn_union as &$r)
        {
            $rstemp = $this->SPLRET_mod->selectspl_sup_ret_psnonly($r['PPSN2_PSNNO']);
            if(!empty($rstemp))
            {                
                $aitem = [];
                foreach($rstemp as $k)
                {
                    $aitem[] = $k->SPL_ITMCD;
                }
                $fields = [
                    'inpsn' => $r['PPSN2_PSNNO'],
                    'indate' => $r['ISUDT'],
                    'initemcd' => $aitem,
                ];
                $fields_string = http_build_query($fields);
                curl_setopt($ch, CURLOPT_POSTFIELDS,$fields_string);
                $status = curl_exec($ch);
                $r['status'] = $status;
                log_message('error', $r['PPSN2_PSNNO'].', status:'.$status);
            }
        }
        unset($r);
        curl_close($ch);

        header('Content-Type: application/json');
        die(json_encode(
            ['param' => 
                [
                    'current_date' => $current_date ,
                    'psn_last_month' => $psn_last_month,
                    'psn_current_month' => $psn_current_month,
                ]
            ,'data' =>
                [
                    'rs_last_month' => $rspsn_last_month
                    ,'rs_current_month' => $rspsn_current_month
                ]
            ,'data_detail' => $rspsn_union
            ]
        ));
    }

    public function editing_byitempsn_desktop(){
        date_default_timezone_set('Asia/Jakarta');	
        $rowaff = 0;
        $ithrowaff = 0;
        $cwh_inc = '';
        $cpsn  = $this->input->post('inpsn');
        $cdate  = $this->input->post('indate');
        $citemcd  = $this->input->post('initemcd');
        $cuser  = $this->input->post('inuser');
        $thetime = '07:01:00';
        if(!is_array($citemcd)) {
            die('there is no part code returned');
        }
        $ttlitem = count($citemcd);
        $cwh_out = '';
        if($ttlitem>0){
            $rsbg = substr($cpsn,0,3)=="PR-" ? $this->SPL_mod->select_bg_partreq([$cpsn]) : $this->SPL_mod->select_bg_ppsn([$cpsn]);
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
            }			
        }
        
        $thelupdt = $cdate." ".$thetime;
        for($b=0;$b<$ttlitem; $b++){
            $rsret = $this->SPLRET_mod->selectby_filter(['RETSCN_SPLDOC' => $cpsn, 'RETSCN_ITMCD' => $citemcd[$b],"ISNULL(RETSCN_HOLD,'0')" => "0" ]);
            foreach($rsret as $r){
                $datau = [
                    'RETSCN_QTYAFT' => $r['RETSCN_QTYAFT'],
                    'RETSCN_SAVED' => '1',
                    'RETSCN_CNFRMDT' => $cdate
                ];
                $toret = $this->SPLRET_mod->updatebyId($datau, $r['RETSCN_ID']);
                if($toret>0){
                    $ithdoc = $cpsn.'|'.trim($r['RETSCN_CAT']).'|'.trim($r['RETSCN_LINE']).'|'.trim($r['RETSCN_FEDR']);					
                    $datas = [
                        'ITH_ITMCD' => $citemcd[$b],
                        'ITH_DATE' => $cdate,
                        'ITH_FORM' => 'INC-RET',
                        'ITH_DOC' => $ithdoc,
                        'ITH_QTY' => $r['RETSCN_QTYAFT'],
                        'ITH_WH' => $cwh_inc,
                        'ITH_REMARK' => $r['RETSCN_ID'],
                        'ITH_LUPDT' => $thelupdt,
                        'ITH_USRID' =>  $cuser
                    ];
                    $aff =  $this->ITH_mod->insert_ret($datas);
                    $ithrowaff += $aff;

                    $datas = [
                        'ITH_ITMCD' => $citemcd[$b],
                        'ITH_DATE' => $cdate,
                        'ITH_FORM' => 'OUT-RET',
                        'ITH_DOC' => $ithdoc,
                        'ITH_QTY' => -$r['RETSCN_QTYAFT'],
                        'ITH_WH' => $cwh_out,
                        'ITH_REMARK' => $r['RETSCN_ID'],
                        'ITH_LUPDT' => $thelupdt,
                        'ITH_USRID' =>  $cuser
                    ];
                    $this->ITH_mod->insert_ret($datas);					
                }
                $rowaff += $toret;
            }
        }
        echo $rowaff." row(s) updated ".$ithrowaff." row(s) saved";
    }

    public function remove(){
        $cid = $this->input->get('inid');
        $resRET = $this->SPLRET_mod->delete_unsaved($cid);
        $this->C3LC_mod->deleteby_filter(['C3LC_REFF' => $cid]);
        $myar = [];
        if($resRET>0){
            $datar = ["cd" => "1", "msg" => "Deleted successfully"];
        } else {
            $datar = ["cd" => "0", "msg" => "could not be deleted, please refresh the page"];
        }
        $myar[] = $datar;		
        echo '{"status":'.json_encode($myar).'}';
    }

    public function checkSession(){
        $myar = [];
        if ($this->session->userdata('status') != "login")
        {
            $myar[] = ["cd" => "0", "msg" => "Session is expired please reload page"];
            exit(json_encode($myar));
        }
    }

    public function gotoque($pitem, $ppsn, $pcat, $pline, $pfr, $pmachine, $plot){		
        $ch = curl_init();		
        curl_setopt($ch, CURLOPT_URL, 'http://192.168.0.29:8081/api_inventory/api/stock/onKittingReturn/'.$pitem.'/'.$ppsn.'/'.$pcat.'/'.$pline.'/'.$pfr.'/'.$pmachine.'/'.$plot);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
        curl_setopt($ch, CURLOPT_CONNECTTIMEOUT, 5);
        $data = curl_exec($ch);	
        curl_close($ch);
    }

    public function vresume(){
        $this->load->view('wms_report/vreturnrm_resume');
    }
    public function form_confirmreport(){
        $this->load->view('wms_report/vrpt_rtnrm_confirmation');
    }
    public function form_requestresume(){
        $this->load->view('wms_report/vrpt_rtnrm_pr_confirmation');
    }

    public function getconfirmation(){
        header('Content-Type: application/json');
        $date1 = $this->input->get('date1');
        $date2 = $this->input->get('date2');
        $bisgrups = $this->input->get('bisgrups');
        $qrybg = "";
        if(strpos($bisgrups, ',')!== false){
            $abg = explode(",", $bisgrups);
            foreach($abg as $b) {
                $qrybg .= "'".$b."',";
            }
            $qrybg = substr($qrybg,0,strlen($qrybg)-1);
        } else {
            $qrybg = "'".$bisgrups."'";
        }
        $rs = $this->SPLRET_mod->select_returnConfirmation($date1, $date2, $qrybg);
        die(json_encode(['data' => $rs]));
    }
    public function getconfirmation_PR(){
        header('Content-Type: application/json');
        $date1 = $this->input->get('date1');
        $date2 = $this->input->get('date2');
        $bisgrups = $this->input->get('bisgrups');
        $qrybg = "";
        if(strpos($bisgrups, ',')!== false){
            $abg = explode(",", $bisgrups);
            foreach($abg as $b) {
                $qrybg .= "'".$b."',";
            }
            $qrybg = substr($qrybg,0,strlen($qrybg)-1);
        } else {
            $qrybg = "'".$bisgrups."'";
        }
        $rs = $this->SPLRET_mod->select_returnConfirmation_PR($date1, $date2, $qrybg);
        die(json_encode(['data' => $rs]));
    }

    public function getconfirmation_xls(){		
        $date1 = $_COOKIE["CKPSI_DATE1"];
        $date2 = $_COOKIE["CKPSI_DATE2"];
        $bisgrups = $_COOKIE["CKPSI_BISGRUPS"];
        $qrybg = "";
        if(strpos($bisgrups, ',')!== false){
            $abg = explode(",", $bisgrups);
            foreach($abg as $b) {
                $qrybg .= "'".$b."',";
            }
            $qrybg = substr($qrybg,0,strlen($qrybg)-1);
        } else {
            $qrybg = "'".$bisgrups."'";
        }		
        $rs = $this->SPLRET_mod->select_returnConfirmation($date1, $date2, $qrybg);
        $spreadsheet = new Spreadsheet();
        $sheet = $spreadsheet->getActiveSheet();
        $sheet->setTitle('resume');
        $sheet->setCellValueByColumnAndRow(1,1, 'Date');		
        $sheet->setCellValueByColumnAndRow(2,1, 'Document');
        $sheet->setCellValueByColumnAndRow(3,1, 'Line');
        $sheet->setCellValueByColumnAndRow(4,1, 'Part Code');
        $sheet->setCellValueByColumnAndRow(5,1, 'Part Name');
        $sheet->setCellValueByColumnAndRow(6,1, 'Model');
        $sheet->setCellValueByColumnAndRow(7,1, 'Req. QT');	
        $sheet->setCellValueByColumnAndRow(8,1, 'Sup. QT');	
        $sheet->setCellValueByColumnAndRow(9,1, 'Ret. QT');
        $i=2;
        foreach($rs as $r) {			
            $sheet->setCellValueByColumnAndRow(1,$i, $r['RETSCN_CNFRMDT']);
            $sheet->setCellValueByColumnAndRow(2,$i, $r['VREQPSN']);
            $sheet->setCellValueByColumnAndRow(3,$i, $r['SPL_LINE']);
            $sheet->setCellValueByColumnAndRow(4,$i, $r['SPL_ITMCD']);
            $sheet->setCellValueByColumnAndRow(5,$i, $r['MITM_SPTNO']);
            $sheet->setCellValueByColumnAndRow(6,$i, $r['MITM_ITMD1']);
            $sheet->setCellValueByColumnAndRow(7,$i, $r['REQQTY']);
            $sheet->setCellValueByColumnAndRow(8,$i, $r['SUPQTY']);
            $sheet->setCellValueByColumnAndRow(9,$i, $r['RETQTY']);
            $i++;			
            
        }
        foreach(range('A', 'I') as $v) {
            $sheet->getColumnDimension($v)->setAutoSize(true);			
        }
        $stringjudul = "Return RM Resume period ".$date1." to ".$date2 . "($bisgrups)";
        $writer = new Xlsx($spreadsheet);
        $filename=$stringjudul; //save our workbook as this file name
        
        header('Content-Type: application/vnd.ms-excel');
        header('Content-Disposition: attachment;filename="'. $filename .'.xlsx"'); 
        header('Cache-Control: max-age=0');
        $writer->save('php://output');
    }

    public function resume_desktop(){
        header('Content-Type: application/json');
        $date1 = $this->input->get('date1');
        $date2 = $this->input->get('date2');
        $rs = $this->SPLRET_mod->select_resume($date1, $date2);
        $myar = [];
        if(count($rs)){
            $myar[] = ['cd' => 1, 'msg' => 'Go ahead'];
        } else {			
            $myar[] = ['cd' => 0, 'msg' => 'Not found'];
        }
        die('{"data":'.json_encode($rs).', "status":'.json_encode($myar).'}');
    }

    public function form_rtn_without_psn(){
        $this->load->view('wms/vretprd_without_psn');
    }

    public function rtn_without_psn(){
        header('Content-Type: application/json');
        date_default_timezone_set('Asia/Jakarta');
        $itemcd = $this->input->post('itmcd');
        $oldqty = $this->input->post('oldqty');
        $newqty = $this->input->post('newqty');
        $lotnum = $this->input->post('lotnum');
        $usrid = $this->input->post('usrid');
        $currentDateTime = date('Y-m-d H:i:s');
        $currentDate = date('Y-m-d');
        $_year = substr(date('Y'),-2);
        $_month = (int)date('m');
        $_day = date('d');
        $cwh_inc = '';
        $cwh_out = '';
        $myar = [];
        $rsbg = $this->SPL_mod->select_bg_item([$itemcd]);
        foreach($rsbg as $r){
            switch($r['SPL_BG']){
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
        if($this->RETRM_mod->check_Primary(['RETRM_ITMCD' => $itemcd, 'RETRM_OLDQTY' => $oldqty, 'RETRM_LOTNUM' => $lotnum, 'CONVERT(DATE, RETRM_CREATEDAT)=' => $currentDate])){
            $myar[]= ['cd' => '0', 'msg' => 'it was already returned'];
        } else {
            $lastNumber = $this->RETRM_mod->lastserialid()+1;
            $doc = "RWP".$_year.$this->AMONTHPATRN[($_month-1)].$_day.$lastNumber;
            $data = ['RETRM_DOC' => $doc
                , 'RETRM_LINE' => 1
                , 'RETRM_ITMCD' => $itemcd
                , 'RETRM_OLDQTY' => $oldqty
                , 'RETRM_NEWQTY' => $newqty
                , 'RETRM_LOTNUM' => $lotnum
                , 'RETRM_CREATEDAT' => $currentDateTime
                , 'RETRM_USRID' => $usrid
            ];
            $rv = $this->RETRM_mod->insert($data);
    
            $datab = [
                'ITH_ITMCD' => $itemcd, 'ITH_WH' =>  $cwh_inc , 
                'ITH_DOC' 	=> $doc, 'ITH_DATE' => $currentDate,
                'ITH_FORM' 	=> 'INCRTN-NO-PSN', 'ITH_QTY' => $newqty, 
                'ITH_REMARK' => $lotnum,
                'ITH_USRID' =>  $this->session->userdata('nama')
            ];
            $this->ITH_mod->insert_spl($datab);
            $datab = [
                'ITH_ITMCD' => $itemcd, 'ITH_WH' =>  $cwh_out, 
                'ITH_DOC' 	=> $doc, 'ITH_DATE' => $currentDate,
                'ITH_FORM' 	=> 'OUTRTN-NO-PSN', 'ITH_QTY' => -1*$newqty, 
                'ITH_REMARK' => $lotnum,
                'ITH_USRID' =>  $this->session->userdata('nama')
            ];
            $this->ITH_mod->insert_spl($datab);			
            $myar[] = $rv>0 ? ['cd' => '1', 'msg' => 'OK'] : ['cd' => '0', 'msg' => 'could not be saved'];
        }
        die(json_encode(['status' => $myar]));
    }

    public function rtn_without_psn_list(){
        header('Content-Type: application/json');
        date_default_timezone_set('Asia/Jakarta');
        $currentDate = date('Y-m-d');
        $rs = $this->RETRM_mod->selectWithRack(['CONVERT(DATE, RETRM_CREATEDAT)=' => $currentDate]);
        die(json_encode(['data' => $rs]));
    }

    public function cancel_rtn_without_psn(){
        header('Content-Type: application/json');
        date_default_timezone_set('Asia/Jakarta');		
        $currentDate = date('Y-m-d');
        $idscan = $this->input->post('inid');
        $itemcd = $this->input->post('itemcd');
        $rs = $this->RETRM_mod->select_where(['*'],['RETRM_DOC' => $idscan] );
        $rsbg = $this->SPL_mod->select_bg_item([$itemcd]);
        $cwh_inc = '';
        $cwh_out = '';
        foreach($rsbg as $r){
            switch($r['SPL_BG']){
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
        $myar = [];
        if(count($rs) > 0){
            if($this->ITH_mod->check_Primary(['ITH_DOC' => $idscan])){
                $newqty = 0;
                $lotnum = "";
                foreach($rs as $r){
                    $newqty = $r['RETRM_NEWQTY'];
                    $lotnum = $r['RETRM_LOTNUM'];
                }
                if($this->RETRM_mod->delete_where(['RETRM_DOC' => $idscan, 'RETRM_ITMCD' => $itemcd ])){
                    $datab = [
                        'ITH_ITMCD' => $itemcd, 'ITH_WH' =>  $cwh_inc , 
                        'ITH_DOC' 	=> $idscan, 'ITH_DATE' => $currentDate,
                        'ITH_FORM' 	=> 'CANCEL-INCRTN-NO-PSN', 'ITH_QTY' => -1*$newqty, 
                        'ITH_REMARK' => $lotnum,
                        'ITH_USRID' =>  $this->session->userdata('nama')
                    ];
                    $this->ITH_mod->insert_spl($datab);
                    $datab = [
                        'ITH_ITMCD' => $itemcd, 'ITH_WH' =>  $cwh_out, 
                        'ITH_DOC' 	=> $idscan, 'ITH_DATE' => $currentDate,
                        'ITH_FORM' 	=> 'CANCEL-OUTRTN-NO-PSN', 'ITH_QTY' => $newqty, 
                        'ITH_REMARK' => $lotnum,
                        'ITH_USRID' =>  $this->session->userdata('nama')
                    ];
                    $this->ITH_mod->insert_spl($datab);
                    $myar[] = ['cd' => '1' , 'msg' => 'OK'];
                } else {
                    $myar[] = ['cd' => '1' , 'msg' => 'OK.'];
                }				
            } else {
                if($this->RETRM_mod->delete_where(['RETRM_DOC' => $idscan, 'RETRM_ITMCD' => $itemcd ])){
                    $myar[] = ['cd' => '1' , 'msg' => 'ok'];
                } else {
                    $myar[] = ['cd' => '0' , 'msg' => 'could not delete, try reopen the menu'];
                }
            }
        } else {
            $myar[] = ['cd' => '0' , 'msg' => 'not ok'];
        }		 
        die(json_encode(['status' => $myar, 'data' => $rs]));
    }
}
