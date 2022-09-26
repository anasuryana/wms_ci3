<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class PND extends CI_Controller {

   public function __construct()
   {
      parent::__construct();
      $this->load->model('PND_mod');
      $this->load->model('PNDSCN_mod');
      $this->load->model('MSTLOCG_mod');
      $this->load->model('MSTITM_mod');
      $this->load->model('TXROUTE_mod');
      $this->load->model('ITH_mod');
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
      $this->load->view('wms/vpendingrm', $data);
    }
   public function createser(){
        date_default_timezone_set('Asia/Jakarta');
      $data['dt'] = date('Y-m-d H:i:s');
      $data['lwh'] = $this->MSTLOCG_mod->selectbynm_lk_pnd();
      $this->load->view('wms/vpendingser', $data);
   }
   
   public function releaseser(){
      $this->load->view('wms/vreleasefg');
   }

   public function checkexist_ser(){
      $cdoc = $this->input->get('indoc');
      $ttlrows = $this->PND_mod->check_Primary_ser(['PNDSER_DOC' => $cdoc]);
      $myar = [];
      if($ttlrows>0){
         $myar[] = ['cd' => 1, 'msg' => 'go ahead'];
      } else {
         $myar[] = ['cd' => 0, 'msg' => 'not found'];
      }
      echo '{"status" : '.json_encode($myar).'}';
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
        $this->load->view('wms/vpendingrm_scan', $data);
    }
   
   public function set(){
        header('Content-Type: application/json');
        date_default_timezone_set('Asia/Jakarta');
        $currrtime = date('Y-m-d H:i:s');
        $currdate = date('Ymd');
        $citem = $this->input->post('initem');
        $citemlot = $this->input->post('inlot');
        $cqty = $this->input->post('inqty');
        $cremark = $this->input->post('inremark');
        $cdate = $this->input->post('indate');
        $ttldata = count($citem);
        if($ttldata>0){
            $mlastid = $this->PND_mod->lastserialid();
         $mlastid++;
            $newid = 'PND'.$currdate.$mlastid;
            $datas = [];
            for($i=0;$i<$ttldata;$i++){
            if($this->MSTITM_mod->check_Primary(['MITM_ITMCD' => $citem[$i]])==0){
               $myar[] = ['cd' => 0, "msg" => $citem[$i]." is not registered"];
               die('{"data":'.json_encode($myar).'}');
            }
                $datas[] = [
                    'PND_DOC' => $newid,
                    'PND_DT' => $cdate,
               'PND_ITMCD' => trim($citem[$i]),
               'PND_ITMLOT' => trim($citemlot[$i]),
                    'PND_QTY' => $cqty[$i],
                    'PND_REMARK' => $cremark[$i],
                    'PND_LUPDT' => $currrtime,
                    'PND_USRID' => $this->session->userdata('nama')
            ];                
            }
            $toret = $this->PND_mod->insertb($datas);
            $myar[] = $toret>0 ? ["cd" => $toret, "msg" => "Saved successfully" , "ref" => $newid] 
               : ["cd" => 0, "msg" => "Could not save"];            
         die('{"data":'.json_encode($myar).'}');
        }        
   }	
   
   public function setser(){
        header('Content-Type: application/json');
        date_default_timezone_set('Asia/Jakarta');
        $currrtime = date('Y-m-d H:i:s');
        $currdate = date('Ymd');
        $citem = $this->input->post('initem');
        
        $cqty = $this->input->post('inqty');
        $cser = $this->input->post('inser');
        $cdate = $this->input->post('indate');
        $cremark = $this->input->post('inremark');
        $ttldata = count($citem);
        if($ttldata>0){
            $mlastid = $this->PND_mod->lastserialidser();
         $mlastid++;
            $newid = 'PNDS'.$currdate.$mlastid;
            $datas = array();
            for($i=0;$i<$ttldata;$i++){
                $datat = array(
                    'PNDSER_DOC' => $newid,
                    'PNDSER_DT' => $cdate,					
                    'PNDSER_QTY' => $cqty[$i],
                    'PNDSER_SER' => $cser[$i],
                    'PNDSER_REMARK' => $cremark[$i],
                    'PNDSER_LUPDT' => $currrtime,
                    'PNDSER_USRID' => $this->session->userdata('nama')
                );
                array_push($datas, $datat);
            }
            $toret = $this->PND_mod->insertbser($datas);
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
                'PND_DOC' => $ckey
            );
            $rs = $this->PND_mod->selectAllg_byVAR($datal);
        } else {
            $datal = array(
                'PND_REMARK' => $ckey
            );
            $rs = $this->PND_mod->selectAllg_byVAR($datal);
        }		
      echo json_encode($rs);
   }
    public function searchser(){
      date_default_timezone_set('Asia/Jakarta');		
      header('Content-Type: application/json');
      $ckey = $this->input->get('inid');
      $cby = $this->input->get('inby');
                     
        if($cby=='no'){
            $datal = [ 'PNDSER_DOC' => $ckey];
            $rs = $this->PND_mod->selectserAllg_byVAR($datal);
        } else {
            $datal = ['PNDSER_REMARK' => $ckey ];
            $rs = $this->PND_mod->selectserAllg_byVAR($datal);
        }		
      echo json_encode($rs);
   }
   
   public function search_sc(){
      header('Content-Type: application/json');
      $ckeys = $this->input->get('inkeys');
      $dataw = array('PND_DOC' => $ckeys);
      $rs = $this->PND_mod->selectAll_like_by($dataw);
      echo json_encode($rs);
   }
   public function searchser_sc(){
      header('Content-Type: application/json');
      $ckeys = $this->input->get('inkeys');
      $dataw = array('PNDSER_DOC' => $ckeys);
      $rs = $this->PND_mod->selectserAll_like_by($dataw);
      echo json_encode($rs);
   }
   public function searchser_sc_exact(){
      header('Content-Type: application/json');
      $cdoc = $this->input->get('indoc');
      $dataw = array('PNDSER_DOC' => $cdoc);
      $rs = $this->PND_mod->selectserAll_exact_by($dataw);
      echo json_encode($rs);
   }
    
    public function getbyid(){
        header('Content-Type: application/json');
        $cdoc = $this->input->get('indoc');        
        $rs = $this->PND_mod->selectAll_by(['PND_DOC' => $cdoc]);
        echo json_encode($rs);
    }
    public function getbyidser(){
        header('Content-Type: application/json');
        $cdoc = $this->input->get('indoc');
        $dataw = array('PNDSER_DOC' => $cdoc, 'PNDSER_QTY > 0' => null, 'RLSSER_SER IS NULL' => null) ;
        $rs = $this->PND_mod->selectserAll_by($dataw);
        echo json_encode($rs);
   }
   
   public function editser(){
      header('Content-Type: application/json');
        date_default_timezone_set('Asia/Jakarta');
        $currrtime = date('Y-m-d H:i:s');        
        $citem = $this->input->post('initem');
        $citemlot = $this->input->post('inlot');
        $cqty = $this->input->post('inqty');
        $cremark = $this->input->post('inremark');
        $cdate = $this->input->post('indate');
        $cdoc = $this->input->post('indoc');
        $cser = $this->input->post('inser');
        $cremark = $this->input->post('inremark');
        $ttldata = count($citem);
        if($ttldata>0){            
            $toret = 0;
            for($i=0;$i<$ttldata;$i++){
                $datat = array(                    
                    'PNDSER_DT' => $cdate,                                            
                    'PNDSER_REMARK' => $cremark[$i],
                    'PNDSER_LUPDT' => $currrtime,
                    'PNDSER_USRID' => $this->session->userdata('nama')
                );
                $dataw = array(
                    'PNDSER_DOC' => $cdoc, 'PNDSER_SER' => $cser[$i]
                );
                $toret += $this->PND_mod->updateserbyId($datat, $dataw);
            }            
            $myar = [];
            if($toret>0){
            $datar = array("cd" => $toret, "msg" => "Saved successfully" );
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

    public function edit(){
        header('Content-Type: application/json');
        date_default_timezone_set('Asia/Jakarta');
        $currrtime = date('Y-m-d H:i:s');        
        $citem = $this->input->post('initem');
        $citemlot = $this->input->post('inlot');
        $cqty = $this->input->post('inqty');
        $cremark = $this->input->post('inremark');
        $cdate = $this->input->post('indate');
        $cdoc = $this->input->post('indoc');
        $ttldata = count($citem);
        if($ttldata>0){
            $toret = 0;
            for($i=0;$i<$ttldata;$i++){
                $datat = [
                    'PND_DT' => $cdate,
                    'PND_QTY' => $cqty[$i],
                    'PND_REMARK' => $cremark[$i],
                    'PND_LUPDT' => $currrtime,
                    'PND_USRID' => $this->session->userdata('nama')
            ];
                $dataw = ['PND_DOC' => $cdoc, 'PND_ITMCD' => trim($citem[$i]), 'PND_ITMLOT' => trim($citemlot[$i])];
                $toret += $this->PND_mod->updatebyId($datat, $dataw);
            }
            $myar[] = $toret>0 ? ["cd" => $toret, "msg" => "Saved successfully"] : ["cd" => 0, "msg" => "Could not update"];            
         die('{"data":'.json_encode($myar).'}');			
        }
    }

    public function scn_balancing(){
      header('Content-Type: application/json');
      $cdo = $this->input->get('inDO');		
      $rs = $this->PND_mod->selectscan_balancingv2($cdo);
      $rsh =$this->PND_mod->selectprog_scan($cdo);
      $rss =$this->PND_mod->selectprog_save($cdo);
      echo '{"data":';
      echo json_encode($rs);
      echo ',"datahead":';
      echo json_encode($rsh);
      echo ',"datasave":';
      echo json_encode($rss);
      echo '}';
    }
    
    public function scn_itemdo_check(){
      $cdo = $this->input->get('inDO');		
      $citem = $this->input->get('inITEM');		
      if($this->PND_mod->check_Primary(['PND_ITMCD' => $citem, 'PND_DOC' => $cdo])>0){
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
         'PND_ITMCD' => $citm, 'PND_DOC' => $cdo , 'PND_ITMLOT' => trim($clot)
      );
      if($this->PND_mod->check_Primary($datac)>0){			
            echo "1"; //location valid
            $mlastid	= $this->PNDSCN_mod->lastserialid();
            $mlastid++;
            $newid 		= $currdate.$mlastid;				
            //check if scanned qty is more than balance value
            $datac_bal 	= array(
                'PND_DOC' => $cdo, 'PND_ITMCD' => $citm
            );
            $rs 		= $this->PND_mod->selectscan_balancing($datac_bal);
            $mget_bal = 0;
            foreach($rs as $r){
                $mget_bal = $r['PND_QTY']-$r['SCAN_QTY']*1;				
            }
            if($cqty>$mget_bal){
                echo "0";
            } else {
                $datas = array(
                    'PNDSCN_ID' => $newid, 'PNDSCN_DOC' => $cdo, 
                    'PNDSCN_ITMCD' => $citm, 'PNDSCN_LOTNO' => $clot, 'PNDSCN_QTY' => $cqty,
                    'PNDSCN_LUPDT' => $currrtime , 'PNDSCN_USRID' => $this->session->userdata('nama')
                );
                $toret = $this->PNDSCN_mod->insert($datas);
                if($toret>0){ 
                    echo "1"; //insert RCVSCN success
                    $rs = $this->PND_mod->selectbalancebyDOITEM($cdo, $citm);
                    foreach($rs as $r){
                        echo "_".($r['PND_QTY']-$r['SCAN_QTY']); // GET BALANCE
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
      $dataf 	= ['PNDSCN_DOC' => $cdo, 'PNDSCN_ITMCD'=> $citem];
      $rs 	= $this->PNDSCN_mod->selectby_filter($dataf);
      echo json_encode($rs);
    }
    
    public function scnd_remove(){
      $cid 	= $this->input->get('inID');
      $dataw 	= array('PNDSCN_ID' => $cid);
      $toret 	= $this->PNDSCN_mod->deleteby_filter($dataw);
      if( $toret>0){ 
         echo "Deleted";
      } else {
         echo "could not deleted";
      }
    }

   function remove_pnd_doc()
   {
      header('Content-Type: application/json');
      $doc = $this->input->post('doc');
      $itemcd = $this->input->post('itemcd');
      $itemlot = $this->input->post('itemlot');
      $myar = [];
      if($this->PNDSCN_mod->check_Primary(['PNDSCN_DOC' => $doc, 'PNDSCN_ITMCD' => $itemcd, 'PNDSCN_LOTNO' => $itemlot]))
      {
         $myar = ['cd' => '0', 'msg' => 'Already scanned, please try delete scanning data first'];
      } else {
         $this->PND_mod->deleteby_filter(['PND_DOC' => $doc, 'PND_ITMCD' => $itemcd, 'PND_ITMLOT' => $itemlot]);
         $myar = ['cd' => '1', 'msg' => 'OK'];
      }
      die(json_encode(['status' => $myar]));
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
        $dataf_txroute = array('TXROUTE_ID' => 'OUTGOING-RM-PEN', 'TXROUTE_WH' => $cwh);
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
      $rsunsaved = $this->PNDSCN_mod->selectunsaved($cdo);
      foreach($rsunsaved as $r){
         $datas 	= array(
            'ITH_ITMCD' => trim($r['PNDSCN_ITMCD']), 'ITH_WH' => $cwh_out,
            'ITH_DOC' 	=> $cdo, 'ITH_DATE' => $currdate,
            'ITH_FORM' 	=> $cfm_out, 'ITH_QTY' => -$r['PNDSCN_QTY'], 'ITH_LUPDT' => $currrtime,
            'ITH_USRID' =>  $this->session->userdata('nama')
         );
         $toret 		= $this->ITH_mod->insert_pending($datas);
         $datas 		= array(
            'ITH_ITMCD' => trim($r['PNDSCN_ITMCD']), 'ITH_WH' => $cwh_inc,
            'ITH_DOC' 	=> $cdo, 'ITH_DATE' => $currdate,
            'ITH_FORM' 	=> $cfm_inc, 'ITH_QTY' => $r['PNDSCN_QTY'], 'ITH_LUPDT' => $currrtime,
            'ITH_USRID' =>  $this->session->userdata('nama')
         );
         $toret 		= $this->ITH_mod->insert_pending($datas);			
         $flag_insert+=$toret;
         $datau = array('PNDSCN_SAVED' => '1');
         $dataw = array('PNDSCN_ID' => $r['PNDSCN_ID']);
         $toret = $this->PNDSCN_mod->updatebyId($datau,$dataw);
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
        if(isset($_COOKIE["CKPNDDOC_NO"])){
            $pser = $_COOKIE["CKPNDDOC_NO"];
      } else {
         exit('nothing to be printed');
      }
      $cwhere  = array('PND_DOC' => $pser);
      $rs = $this->PND_mod->selectAll_by($cwhere);
      $cdate = '';
        $cuser = '';
        foreach($rs as $r){
            $cdate=$r['PND_DT'];
            $cuser=$r['MSTEMP_FNM'];
        }
      $pdf = new PDF_Code39e128('L','mm','A4');
      $pdf->AliasNbPages();
      $pdf->AddPage();
      $hgt_p = $pdf->GetPageHeight();
      $wid_p = $pdf->GetPageWidth();
      $pdf->SetAutoPageBreak(true,1);
      $pdf->SetMargins(0,0);
      $pdf->SetFont('Arial','BU',13);
      $clebar = $pdf->GetStringWidth($pser)+10;
      $pdf->SetXY(80,5);
        $pdf->Cell(50,4,'Document of RM Pending',0,0,'L');
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
      $pdf->Cell(90,4,'Remark',1,0,'L');
      $cury = 30;
      $no = 1;

      foreach($rs as $r){
         if(($cury+10)>$hgt_p){
            $pdf->AddPage();
            $pdf->SetFont('Arial','BU',13);
            $clebar = $pdf->GetStringWidth($pser)+10;
            $pdf->SetXY(80,5);
            $pdf->Cell(50,4,'Document of RM Pending',0,0,'L');
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
            $pdf->Cell(90,4,'Remark',1,0,'L');
            $cury = 30;
         }
         $pdf->SetFont('Arial','',9);
         $pdf->SetXY(5,$cury);
         $pdf->Cell(8,4,$no,1,0,'L');
         $pdf->Cell(35,4,trim($r['PND_ITMCD']),1,0,'L');
         
         $ttlwidth = $pdf->GetStringWidth(trim($r['MITM_SPTNO']));
         if($ttlwidth > 35){
            $ukuranfont = 8.5;
            while($ttlwidth>34){
               $pdf->SetFont('Arial','',$ukuranfont);
               $ttlwidth=$pdf->GetStringWidth(trim($r['MITM_SPTNO']));
               $ukuranfont = $ukuranfont - 0.5;
            }
         }
         $pdf->Cell(35,4,trim($r['MITM_SPTNO']),1,0,'L');

         $pdf->SetFont('Arial','',9);
         $pdf->Cell(40,4,trim($r['PND_ITMLOT']),1,0,'L');
         $pdf->Cell(15,4,number_format($r['PND_QTY']),1,0,'R');
         $pdf->Cell(25,4,trim($r['ITMLOC_LOC']),1,0,'L');
         $pdf->Cell(90,4,$r['PND_REMARK'],1,0,'L');
         $cury+=4; $no++;
      }

      $pdf->Output('I','PENDING Doc '.$pser.' ' .date("d-M-Y").'.pdf');
   }

   public function searchfg(){
      header('Content-Type: application/json');
      $cid = $this->input->get('inid');
      $csearchby = $this->input->get('insearchby');

      $rs = $csearchby=='reffno' ? $this->PNDSCN_mod->select_grp_doc_item_byreff($cid) : $this->PNDSCN_mod->select_grp_doc_item($cid);
      $myar = [];
      if(count($rs)>0){
         array_push($myar, ['cd' => 1, 'msg' => 'go ahead']);			
      } else {
         array_push($myar, ['cd' => 0 , 'msg' => 'Not found']);
      }
      echo '{"status" : '.json_encode($myar).', "data": '.json_encode($rs).'}';		
   }

   public function printdocser(){
      $pser = '';		
        if(isset($_COOKIE["CKPNDDOCSER_NO"])){
            $pser = $_COOKIE["CKPNDDOCSER_NO"];
      } else {
         exit('nothing to be printed');
      }
      $cwhere  = array('PNDSER_DOC' => $pser);
      $rs = $this->PND_mod->selectserAll_by($cwhere);
      $cdate = '';
        $cuser = '';
        foreach($rs as $r){
            $cdate=$r['PNDSER_DT'];
            $cuser=$r['MSTEMP_FNM'];
        }
      $pdf = new PDF_Code39e128('L','mm','A4');
      $pdf->AliasNbPages();
      $pdf->AddPage();
      $hgt_p = $pdf->GetPageHeight();		
      $pdf->SetAutoPageBreak(true,1);
      $pdf->SetMargins(0,0);
      $pdf->SetFont('Arial','BU',9);
        $clebar = $pdf->GetStringWidth($pser)+10;
        $pdf->SetXY(80,5);
        $pdf->Cell(50,4,'Document of FG Pending',0,0,'L');
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
      $pdf->Cell(53,4,'Item Name',1,0,'L');
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
            $pdf->Cell(50,4,'Document of FG Pending',0,0,'L');
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
            $pdf->Cell(53,4,'Item Name',1,0,'L');
            $pdf->Cell(35,4,'Job No',1,0,'L');
            $pdf->Cell(10,4,'QTY',1,0,'R');
            $pdf->Cell(15,4,'Rack No',1,0,'L');
            $pdf->Cell(30,4,'Remark',1,0,'L');
            $cury = 30;
         }
         $pdf->SetFont('Arial','',8);
         $pdf->SetXY(5,$cury);
         $pdf->Cell(8,4,$no,1,0,'L');
         $pdf->Cell(35,4,trim($r['PNDSER_SER']),1,0,'L');						
         $pdf->Cell(35,4,trim($r['SER_ITMID']),1,0,'L');

         
         $ttlwidth = $pdf->GetStringWidth(trim($r['MITM_ITMD1']));
         if($ttlwidth > 53){
            $ukuranfont = 7.5;
            while($ttlwidth>53){
               $pdf->SetFont('Arial','',$ukuranfont);
               $ttlwidth=$pdf->GetStringWidth(trim($r['MITM_ITMD1']));
               $ukuranfont = $ukuranfont - 0.5;
            }
         }
         $pdf->Cell(53,4,trim($r['MITM_ITMD1']),1,0,'L');
         $pdf->SetFont('Arial','',8);
         
         $pdf->Cell(35,4,trim($r['SER_DOC']),1,0,'L');
         $pdf->Cell(10,4,number_format($r['PNDSER_QTY']),1,0,'R');
         $pdf->Cell(15,4,trim($r['ITH_LOC']),1,0,'L');
         $pdf->Cell(30,4,$r['PNDSER_REMARK'],1,0,'L');
         $cury+=4; $no++;
      }
      $pdf->Output('I','PENDING Doc '.$pser.' ' .date("d-M-Y").'.pdf');
   }

   public function getser(){
      header('Content-Type: application/json');
      $csearch = $this->input->get('insearch');
      $cwh = $this->input->get('inwh');
      $cby = $this->input->get('inby');
      $rs = array();
      switch($cby){
         case 'id':
            $rs = $this->ITH_mod->select_id_willbepending_list_byser($cwh, $csearch);
            break;
         case 'cd':
            $rs = $this->ITH_mod->select_id_willbepending_list_byitem($cwh, $csearch);
            break;
         case 'doc':
            $rs = $this->ITH_mod->select_id_willbepending_list_bydoc($cwh, $csearch);
            break;
      }
      echo '{"data":';
      echo json_encode($rs);
      echo '}';
   }

   public function remove(){
      header('Content-Type: application/json');
      $cid 	= $this->input->get('inid');
      $dataw 	= array('PNDSCN_SER' => $cid);
      $rsscan = $this->PNDSCN_mod->check_Primary($dataw);
      $myar = array();
      if($rsscan>0){
         $datar = array("cd" => "00", "msg" => "Could not delete, because the item is already scanned" );
         array_push($myar, $datar);
         echo '{"data":';
         echo json_encode($myar);
         echo '}';
      } else {
         $toret = $this->PND_mod->deleteby_filter(array("PNDSER_SER" => $cid));
         if($toret>0){
            $datar = array("cd" => "11", "msg" => "Deleted " );
            array_push($myar, $datar);
            echo '{"data":';
            echo json_encode($myar);
            echo '}';
         } else {
            $datar = array("cd" => "01", "msg" => "Could not be deleted " );
            array_push($myar, $datar);
            echo '{"data":';
            echo json_encode($myar);
            echo '}';
         }
      }
   }

   public function get_scn_vs_rls(){
      $cdoc = $this->input->get('indoc');
      $rs = $this->PNDSCN_mod->select_fg_scn_vs_rls($cdoc);
      $myar = [];
      if(count($rs)>0){
         array_push($myar , ['cd' => 1, 'msg' => 'go ahead']);			
      } else {			
         array_push($myar , ['cd' => 0, 'msg' => 'not found']);
      }
      echo '{"status": '.json_encode($myar).', "data": '.json_encode($rs).'}';
   }
   public function get_scn_vs_rls_with_reffno(){
      $cdoc = $this->input->get('indoc');
      $creffno = $this->input->get('inreffno');
      $rs = $this->PNDSCN_mod->select_fg_scn_vs_rls_with_reffno($cdoc, $creffno);
      $myar = [];
      if(count($rs)>0){
         array_push($myar , ['cd' => 1, 'msg' => 'go ahead']);			
      } else {			
         array_push($myar , ['cd' => 0, 'msg' => 'not found']);
      }
      echo '{"status": '.json_encode($myar).', "data": '.json_encode($rs).'}';
   }
}
