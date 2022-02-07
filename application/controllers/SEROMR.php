<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class SER extends CI_Controller {
	public function __construct()
	{
		parent::__construct();		
		$this->load->helper('url');
		$this->load->library('session');
		$this->load->model('MSTITM_mod');
		$this->load->model('MADE_mod');
		$this->load->model('SER_mod');
		$this->load->model('SPL_mod');
		$this->load->model('ITH_mod');
		$this->load->model('RCV_mod');
		$this->load->model('TXROUTE_mod');
		$this->load->model('MSTLOC_mod');
		$this->load->model('RLS_mod');
		$this->load->model('SISCN_mod');
		$this->load->model('LOGSER_mod');
		$this->load->model('SERC_mod');
		$this->load->model('XBGROUP_mod');
		$this->load->library('Code39e128');
	}
	public function index()
	{
		echo "sorry";
	}

	public function create(){
		$this->load->view('wms/vser_fg');
	}
	public function createrm(){		
		$data['lmade'] = $this->MADE_mod->selectAll();
		$this->load->view('wms/vser_rm', $data);
	}
	public function vsplitlabel_1(){
		$this->load->view('wms/vsplitlabel_1V1');
	}
	public function vdevsplit(){
		$this->load->view('wms/vsplitlabel_1V1');
	}
	public function vsplitlabel_2(){
		$this->load->view('wms/vsplitlabel_2');
	}
	public function vrelable(){
		$this->load->view('wms/vrelable');
	}
	public function vcombinelabel_1(){
		$this->load->view('wms/vcombinelabel1');
	}
	public function vcombinelabel_2(){
		$this->load->view('wms/vcombinelabel2');
	}
	public function vqcunconform(){
		$data['lgroup'] = $this->XBGROUP_mod->selectall();
		$this->load->view('wms_report/vqcunconform', $data);
	}
	public function vconvertlabel(){
		$this->load->view('wms/vconvertlabel');
	}
	public function vdeletelabel(){
		$this->load->view('wms/vdeletelabel');
	}

	public function akutes(){
		$cin = "02";
		echo $this->getMonthDis($cin);
	}

	public function setremark(){
		$creff = $this->input->post('inid');
		$crmrk = $this->input->post('inrmrk');
		$myar = [];
		if($this->SER_mod->updatebyId(['SER_RMRK' => $crmrk], $creff) > 0 ){
			array_push($myar , ['cd' => 1, 'msg' => 'OK']);
		} else {
			array_push($myar , ['cd' => 0, 'msg' => 'Could not save']);
		}
		echo '{"status": '.json_encode($myar).'}';
	}

	public function remove(){
		$cid = $this->input->post('inid');
		$toret = 0;
		$myar = array();
		$remark = "";
		$retfail = 0;
		if(is_array($cid)){
			for($i=0;$i<count($cid) ; $i++){
				$datac = array(
					'ITH_SER' => $cid[$i]
				);
				if($this->ITH_mod->check_Primary($datac)>0){
					$remark .=  $cid[$i]." is already scanned <br>";
					$retfail +=1;
				} else {
					$datadel = array('SER_ID' => $cid[$i]);
					$toret += $this->SER_mod->deletebyID($datadel);					
				}
			}
			if($retfail==0 && $toret>0){
				$remark = "Deleted";
			}
		}
		$datar = array("cd" => $toret, "msg" => $remark);
		array_push($myar, $datar);
		echo '{"status":';
		echo json_encode($myar);
		echo '}';
	}

	public function getMonthDis($par){
		switch($par){
			case "01":
				return "A";break;
			case "02":
				return "B";break;
			case "03":
				return "C";break;
			case "04":
				return "D";break;
			case "05":
				return "E";break;
			case "06":
				return "F";break;
			case "07":
				return "G";break;
			case "08":
				return "H";break;			
			case "09":
				return "I";break;
			case "10":
				return "J";break;			
			case "11":
				return "K";break;
			case "12":
				return "L";break;
		}
	}

	public function setsync(){
		date_default_timezone_set('Asia/Jakarta');			
		$currrtime	= date('Y-m-d H:i:s');
		
		$csheet		= "1";
		$cproddt	= "2020-07-21";
		$cline		= "SMT";
		$cshift		= "M1";
		$cmdl		= 1;
		$rs = $this->SER_mod->selectsync();
		$pYear		= substr($cproddt,2,2); $pMonth = substr($cproddt,5,2); $pDay = substr($cproddt, -2);
		$pMonthdis	= $this->getMonthDis($pMonth);
		foreach ($rs as $r){
			$newid = $this->SER_mod->lastserialid($cmdl, $cproddt);
			$newid++;
			$newid = $cmdl.$pYear.$pMonthdis.$pDay.substr('000000000'.$newid, -10);
			$datas = array(
				'SER_ID' => $newid,
				'SER_DOC' => $r['PDPP_WONO'],
				'SER_DOCTYPE' => '1',
				'SER_ITMID' => $r['PDPP_MDLCD'],
				'SER_QTY' => $r['ADDQTY'],
				'SER_RMRK' => 'EMS1_#ADJ202007_1',
				'SER_SHEET' => $csheet,
				'SER_PRDDT' => $cproddt,
				'SER_PRDLINE' => $cline,
				'SER_PRDSHFT' => $cshift,
				'SER_LUPDT' => $currrtime,
				'SER_USRID' => $this->session->userdata('nama')
			);
			$toret  = $this->SER_mod->insert($datas);
		}
		echo "sudah";
	}

	public function setfg(){
		date_default_timezone_set('Asia/Jakarta');			
		$currrtime	= date('Y-m-d H:i:s');
		$citem		= $this->input->post('initemcd');
		$cjob		= $this->input->post('injob');
		$cqty		= $this->input->post('inqty');
		$csheet		= $this->input->post('insheet');
		$cproddt	= $this->input->post('inproddt');
		$cline		= $this->input->post('inline');
		$cshift		= $this->input->post('inshift');
		$cmdl		= 1;
		$myar = array();

		$rsjob		= $this->SPL_mod->selectWO($cjob);
		foreach($rsjob as $r){			
			if( ((int)$r['LBLTTL']+$cqty) > (int)$r['PDPP_WORQT']){
				$datar = array("cd" => 0, "msg" => "Over");
				array_push($myar, $datar);
				echo json_encode($myar);
				exit();
			}
		}
		$pYear		= substr($cproddt,2,2); $pMonth = substr($cproddt,5,2); $pDay = substr($cproddt, -2);
		$pMonthdis	= $this->getMonthDis($pMonth);		
		$newid = $this->SER_mod->lastserialid($cmdl, $cproddt);
		$newid++;
		$newid = $cmdl.$pYear.$pMonthdis.$pDay.substr('000000000'.$newid, -10);
		$datas = array(
			'SER_ID' => $newid,
			'SER_REFNO' => $newid,
			'SER_DOC' => $cjob,
			'SER_DOCTYPE' => '1',
			'SER_ITMID' => $citem,
			'SER_QTY' => $cqty,
			'SER_QTYLOT' => $cqty,
			'SER_SHEET' => $csheet == '' ? 0 : $csheet,
			'SER_PRDDT' => $cproddt,
			'SER_PRDLINE' => $cline,
			'SER_PRDSHFT' => $cshift,
			'SER_LUPDT' => $currrtime,
			'SER_USRID' => $this->session->userdata('nama')
		);
		$toret  = $this->SER_mod->insert($datas);
		
		if($toret>0){
			$datar = array("cd" => $toret, "msg" => "Saved successfully" , "itemcd" => $citem, "doc" => $cjob);
			///ITH SYNC
			////TXROUTE
			// $datafroute = array('TXROUTE_ID' => 'RECEIVING-FG-PRD' , 'TXROUTE_WH' => 'ARPRD1'); //NEXT GET FROM GUI
			// $rsroute = $this->TXROUTE_mod->selectbyVAR($datafroute);
			// $datac = array('ITH_DOC'=> $cjob, 'ITH_SER' => $newid);
			// if($this->ITH_mod->check_Primary($datac)==0){				
			// 	// foreach($rsroute as $rt){

			// 	// }
			// 	$datas = array(
			// 		'ITH_ITMCD' => $citem, 'ITH_DATE' => $currdate, 'ITH_FORM' => 'INC-PRD-FG',
			// 		'ITH_DOC' => $cjob, 'ITH_QTY' => $cqty, 'ITH_SER' => $newid, 'ITH_WH' => 'ARPRD1',
			// 		'ITH_LUPDT' => $currrtime, 'ITH_USRID' => $this->session->userdata('nama')
			// 	);
			// 	$retITH = $this->ITH_mod->insert($datas);
				
			// }
			///END ITH SYNC
		} else {
			$datar = array("cd" => $toret, "msg" => "Could not be saved" );
		}		
		
		array_push($myar, $datar);
		echo json_encode($myar);
	}

	public function setrm(){
		date_default_timezone_set('Asia/Jakarta');	
		$currdate	= date('Y-m-d');
		$currrtime	= date('Y-m-d H:i:s');
		$citem		= $this->input->post('initemcd');
		$cjob		= $this->input->post('injob');
		$cqty		= $this->input->post('inqty');
		$clot		= $this->input->post('inlot');
		$crohs		= $this->input->post('inrohs');
		$ccountry 	= $this->input->post('incountry');
		$cmdl		= 0;
		$myar = array();

		$rsjob		= $this->RCV_mod->selectDO($cjob);
		foreach($rsjob as $r){			
			if( ( trim($citem)==trim($r['RCV_ITMCD'])) && ((int)$r['LBLTTL']+$cqty) > (int)$r['RCV_QTY']){
				$datar = array("cd" => 0, "msg" => "Over $r[LBLTTL] $cqty $r[RCV_QTY]");
				array_push($myar, $datar);
				echo json_encode($myar);
				exit();
			}
		}
		$pYear		= substr($currdate,2,2); $pMonth = substr($currdate,5,2); $pDay = substr($currdate, -2);
		$pMonthdis	= $this->getMonthDis($pMonth) ;			
		$newid = $this->SER_mod->lastserialid($cmdl, $currdate);
		$newid++;
		$newid = $cmdl.$pYear.$pMonthdis.$pDay.substr('000000000'.$newid, -10);
		$datas = array(
			'SER_ID' => $newid,
			'SER_DOC' => $cjob,
			'SER_ITMID' => $citem,
			'SER_PRDDT' => $currdate,
			'SER_QTY' => $cqty,
			'SER_LOTNO' => $clot,
			'SER_DOCTYPE' => '0',
			'SER_ROHS' => $crohs,
			'SER_CNTRYID' => $ccountry,
			'SER_LUPDT' => $currrtime,
			'SER_USRID' => $this->session->userdata('nama')
		);
		$toret  = $this->SER_mod->insert($datas);
		
		if($toret>0){
			$datar = array("cd" => $toret, "msg" => "Saved successfully" , "itemcd" => $citem, "doc" => $cjob);			
		} else {
			$datar = array("cd" => $toret, "msg" => "Could not be saved" );
		}				
		array_push($myar, $datar);
		echo json_encode($myar);
	}

	function getdocg(){
		$cdoc = $this->input->get('inkey');
		$dataw = array(
			'SER_DOC' => $cdoc,'SER_DOCTYPE' => '1'
		);
		$rs = $this->SER_mod->selectbyVARg($dataw);
		echo json_encode($rs);
	}

	function getdocg_rm(){
		$cdoc = $this->input->get('inkey');
		$dataw = array(
			'SER_DOC' => $cdoc ,'SER_DOCTYPE' => '0'
		);
		$rs = $this->SER_mod->selectDOCbyVARg($dataw);
		echo json_encode($rs);
	}

	function getdoclike(){
		$cdoc = $this->input->get('indoc');
		$citem = $this->input->get('initm');		
		if($citem==''){
			echo '{"data":';
				echo json_encode(array());
				echo '}';
		} else {
			$dataw = array(
				'SER_DOC' => $cdoc, 'SER_ITMID' => $citem
			);
			$rs = $this->SER_mod->selectbyVAR($dataw);
			echo '{"data":';
			echo json_encode($rs);
			echo '}';
		}		
	}

	function getbydoc(){
		$cdoc = $this->input->get('indoc');		
		if($cdoc==''){
			echo '{"data":';
				echo json_encode(array());
				echo '}';
		} else {
			$dataw = array(
				'SER_DOC' => $cdoc
			);
			$rs = $this->SER_mod->selectbyVAR($dataw);
			echo '{"data":';
			echo json_encode($rs);
			echo '}';
		}		
	}

	function printfglabel(){
		global  $wid, $hgt, $padX, $padY,  $noseri, $ccustnm, $cmitmid, $cmitmd1, $cprodt, $cwo, $cprdline, $cprdshift, $cserqty, $csersheet, $cum, $crank,$cuscd;
		function fnLeft($pdf, $cleft, $pword){
            global  $wid, $hgt, $padX, $padY;
            if($cleft>0){
                return $cleft + ($wid/2 - ( $pdf->GetStringWidth($pword)/2 ));
            } else {
                return $wid / 2 - ($pdf->GetStringWidth($pword)/2);
            }
		}
		
		function printTag($pdf,$myleft,$mytop){
            global  $wid, $hgt, $padX, $padY,  $noseri, $ccustnm, $cmitmid, $cmitmd1, $cprodt, $cwo, $cprdline, $cprdshift , $cserqty, $csersheet, $cum, $crank,$cuscd;
			$th_x = $padX+$myleft+3;
			$th_y = $padY+$mytop+4;
			$pdf->AddFont('Tahoma','','tahoma.php');
			$pdf->AddFont('Tahoma','B','tahomabd.php');
			$pdf->SetFont('Tahoma','B',9);			
			$ttlwidth = $pdf->GetStringWidth('CUSTOMER : '.$ccustnm);	
			if($ttlwidth > 90){
				$ukuranfont = 8.5;
				while($ttlwidth>90){
					$pdf->SetFont('Tahoma','',$ukuranfont);
					$ttlwidth=$pdf->GetStringWidth('CUSTOMER : '.$ccustnm);
					$ukuranfont = $ukuranfont - 0.5;
				}
			}
			$pdf->Text($th_x+3,$th_y+5,'CUSTOMER : '.$ccustnm);						
			
			$pdf->Line($th_x+1, $th_y+9, $th_x+97.5, $th_y+9);
			$pdf->Line($th_x+79, $th_y+1, $th_x+79, $th_y+54); //VERICAL LINE
			$pdf->SetFont('Tahoma','',10);
			$pdf->Text($th_x+80, $th_y+4,'FPII-16-02');
			$pdf->Text($th_x+82, $th_y+8,'Rev : 02');
			$pdf->SetFont('Tahoma','',9);
			$pdf->Text($th_x+81, $th_y+13,'QC CHECK');
			$pdf->Line($th_x+79, $th_y+14, $th_x+97.5, $th_y+14);
			$pdf->Line($th_x+79, $th_y+31, $th_x+97.5, $th_y+31);
			$pdf->Text($th_x+79.5, $th_y+35,'PPIC CHECK');
			$pdf->Line($th_x+79, $th_y+36, $th_x+97.5, $th_y+36);
			$pdf->SetFont('Tahoma','',10);						
			$pdf->Text($th_x+2, $th_y+13,'PART NAME');	
			$pdf->Text($th_x+2, $th_y+17,'PART NUMBER');
			$pdf->SetFont('Tahoma','',10);
			$pdf->Text($th_x+2, $th_y+28,'PRODUCTION DATE');
			$pdf->Text($th_x+2, $th_y+33,'JOB NUMBER');
			$pdf->Text($th_x+2, $th_y+42,'PROD. LINE / SHIFT');
			$pdf->Text($th_x+2, $th_y+46,'QUANTITY');			
			$pdf->Text($th_x+35, $th_y+13, ":");//$cmitmd1
			$ttlwidth = $pdf->GetStringWidth($cmitmd1);	
			if($ttlwidth > 42.17){
				$ukuranfont = 9;
				while($ttlwidth>42.17){
					$pdf->SetFont('Tahoma','',$ukuranfont);
					$ttlwidth=$pdf->GetStringWidth($cmitmd1);
					$ukuranfont = $ukuranfont - 0.5;
				}
			}
			$pdf->Text($th_x+37, $th_y+13, trim($cmitmd1));//$cmitmd1
			$pdf->Text($th_x+35, $th_y+17, ":");//$cmitmd1
			$pdf->Code39($th_x+37, $th_y+14, trim(str_replace("^","",$cmitmid)),0.47);
			$pdf->SetFont('Tahoma','B',10);			
			$pdf->Text($th_x+36, $th_y+23, " ".trim($cmitmid)." / ". $crank);	
			$pdf->SetFont('Tahoma','',10);	
			$pdf->Text($th_x+35, $th_y+28, ":");	
			$pdf->Line($th_x+37, $th_y+25, $th_x+58, $th_y+25);
			$pdf->Line($th_x+37, $th_y+29, $th_x+58, $th_y+29);
			$pdf->Line($th_x+37, $th_y+25, $th_x+37, $th_y+29); //VERTICAL
			$pdf->Line($th_x+42.5, $th_y+25, $th_x+42.5, $th_y+29); //VERTICAL
			$pdf->Line($th_x+48, $th_y+25, $th_x+48, $th_y+29); //VERTICAL
			$pdf->Line($th_x+58, $th_y+25, $th_x+58, $th_y+29); //VERTICAL
			$pdf->SetFont('Tahoma','',10);
			$pdf->Text($th_x+37.5, $th_y+28,$cprodt[2]); 
			$pdf->Text($th_x+43.5, $th_y+28,$cprodt[1]);	$pdf->Text($th_x+49.5, $th_y+28,$cprodt[0]);
			// $clebar = $pdf->GetStringWidth($cwo)+25;
			$pdf->Text($th_x+35, $th_y+33, ":");
			$pdf->Code39($th_x+37, $th_y+30,$cwo);
			$pdf->SetFont('Tahoma','B',10);
			$pdf->Text($th_x+36, $th_y+38, " ".$cwo);
			$pdf->SetFont('Tahoma','',10);
			$pdf->Text($th_x+35, $th_y+42, ":");
			$pdf->SetFont('Tahoma','B',10);
			$pdf->Text($th_x+36, $th_y+42," ".$cprdline."/".$cprdshift);			
			$pdf->Code39($th_x+37, $th_y+43,number_format($cserqty,0,"",""));
			$pdf->SetFont('Tahoma','',10);
			$pdf->Text($th_x+35, $th_y+47,":");		
			$pdf->SetFont('Tahoma','B',10);
			$pdf->Text($th_x+37, $th_y+52,number_format($cserqty)." $cum ( ".$csersheet." sheets)");		
			    
			$pdf->Line($th_x +1,$th_y+54,$th_x+97.5, $th_y+54);
			$pdf->SetFont('Tahoma','',10);
			if($cuscd !='PSI2PPZOMI'){				
				$pdf->Text($th_x +2,$th_y+58,'OQC INSPECTOR');
				$pdf->Text($th_x +2,$th_y+62,'REMARK');
				$pdf->Text($th_x +35,$th_y+58,':');
				$pdf->Text($th_x +35,$th_y+62,':');
			} else {
				$AOMR_TGL = [];
				for($i=1;$i<=31;$i++){
					array_push($AOMR_TGL, $i);
				}
				$AOMR_TGL_ALIAS = [];
				for($i=1;$i<=9;$i++){
					array_push($AOMR_TGL_ALIAS, $i);
				}
				array_push($AOMR_TGL_ALIAS, 'A');
				array_push($AOMR_TGL_ALIAS, 'B');
				array_push($AOMR_TGL_ALIAS, 'C');
				array_push($AOMR_TGL_ALIAS, 'D');
				array_push($AOMR_TGL_ALIAS, 'E');
				array_push($AOMR_TGL_ALIAS, 'F');
				array_push($AOMR_TGL_ALIAS, 'G');
				array_push($AOMR_TGL_ALIAS, 'H');
				array_push($AOMR_TGL_ALIAS, 'J');				
				array_push($AOMR_TGL_ALIAS, 'K');
				array_push($AOMR_TGL_ALIAS, 'L');
				array_push($AOMR_TGL_ALIAS, 'M');
				array_push($AOMR_TGL_ALIAS, 'N');
				array_push($AOMR_TGL_ALIAS, 'P');
				array_push($AOMR_TGL_ALIAS, 'Q');
				array_push($AOMR_TGL_ALIAS, 'R');				
				array_push($AOMR_TGL_ALIAS, 'T');
				array_push($AOMR_TGL_ALIAS, 'U');
				array_push($AOMR_TGL_ALIAS, 'V');
				array_push($AOMR_TGL_ALIAS, 'W');
				array_push($AOMR_TGL_ALIAS, 'X');
				array_push($AOMR_TGL_ALIAS, 'Y');
				$OMR_UNIQUE_DATE = '';
				for($bb = 0; $bb<count($AOMR_TGL); $bb++){
					if($AOMR_TGL[$bb] == intval( $cprodt[2])){
						$OMR_UNIQUE_DATE = $AOMR_TGL_ALIAS[$bb];break;
					}
				}
				$OMR_UNIQUE_MONTH = '';
				for($bb = 0; $bb<count($AOMR_TGL); $bb++){
					if($AOMR_TGL[$bb] == intval( $cprodt[1])){
						$OMR_UNIQUE_MONTH = $AOMR_TGL_ALIAS[$bb];break;
					}
				}
				$OMR_UNIQUE_YEAR = '';
				for($bb = 0; $bb<count($AOMR_TGL); $bb++){
					if($AOMR_TGL[$bb] == intval( substr($cprodt[0],-2))){
						$OMR_UNIQUE_YEAR = $AOMR_TGL_ALIAS[$bb];break;
					}
				}
				$aomr_line = explode("-",$cprdline);
				$OMRQR = 'I00152';
				$OMR_PIM = substr("000000000".$cmitmid,-9);
				$OMR_QTY = substr("00000000".number_format($cserqty,0,"",""),-8);
				$OMR_DT = $cprodt[2].$cprodt[1].substr($cprodt[0],-2);
				$OMR_CVT = substr("000".substr($aomr_line[1],0,3),-3);
				$OMR_MC = '';
				$OMR_DIES = substr('00000'.substr($cprdshift,0,1),-5);
				$OMR_SHIFT = str_replace(['M','N'],"", $cprdshift);
				$OMR_NOURUT = substr($noseri,-5);
				$OMR_UNIQUE = $OMR_UNIQUE_YEAR.$OMR_UNIQUE_MONTH.$OMR_UNIQUE_DATE.$OMR_NOURUT;//substr($noseri,1,5).$OMR_NOURUT;
				$OMR_LOT = substr("0000000000000000000000000".$cwo.'-'.$cmitmid,-25);
				if(substr($aomr_line[1],0,3)=='ATH'){
					$OMR_MC = substr('000'.substr($aomr_line[1],-3),-3);
				} else {
					$OMR_MC = '000';
				}
				
				$pdf->Text($th_x +2+15,$th_y+58,'OQC INSPECTOR');
				$pdf->Text($th_x +2+15,$th_y+62,'REMARK');
				$pdf->Text($th_x +35+15,$th_y+58,':');
				$pdf->Text($th_x +35+15,$th_y+62,':');
				$pdf->Text($th_x +2+15,$th_y+66,'UNIQUE OMRON');
				$pdf->Text($th_x +35+15,$th_y+66,': '.$OMR_UNIQUE);
				$OMRQR.=$OMR_PIM.$OMR_QTY.$OMR_DT.$OMR_CVT.$OMR_MC.$OMR_DIES.$OMR_SHIFT.$OMR_UNIQUE.$OMR_LOT;
				$image_omr = $OMRQR;
				$cmd = escapeshellcmd("Python d:\Apache24\htdocs\wms_dev\smt.py \"$image_omr\" 1 ");				
				$op = shell_exec($cmd);
				$image_omr = str_replace("/","xxx", $image_omr);
				$image_omr = str_replace(" ","___", $image_omr);
				$image_omr = str_replace("|","lll", $image_omr);
				$pdf->SetFont('Tahoma','',5);
				//$pdf->TextWithDirection($th_x + 2,$th_y + 54.5,'QR Omron','D');
				$pdf->Text($th_x + 4,$th_y + 66.5,'QR OMRON');
				$pdf->Text($th_x + 86,$th_y + 66.5,'QR SMT');
				$pdf->Image(base_url("assets/imgs/".$image_omr.".png"), $th_x + 4, $th_y + 54.5);				
			}
			$pdf->SetFont('Tahoma','',10);

			$noserencode = "Z1".trim($cmitmid)."&".trim($crank)."|Z7".trim($cprdline)."&".$cprdshift."|Z2".$cwo."|Z3".number_format($cserqty,0,'','')."|Z4".trim($cmitmd1)."|Z5".$noseri."|Z6";
			$image_name = $noserencode; //$noseri; //'LB'.$cmitmid.'|'.$cwo.'|'.number_format($cserqty);
			$cmd = escapeshellcmd("Python d:\Apache24\htdocs\wms_dev\smt.py \"$image_name\" 1 ");				
			$op = shell_exec($cmd);
			$image_name = str_replace("/","xxx", $image_name);
			$image_name = str_replace(" ","___", $image_name);
			$image_name = str_replace("|","lll", $image_name);
			$pdf->SetFont('Tahoma','',5);
						
			$pdf->Image(base_url("assets/imgs/".$image_name.".png"), $th_x + 86, $th_y + 54.5);			
			$pdf->Line($th_x +1, $th_y+67, $th_x+97.5,  $th_y+67);
			$pdf->Rect($th_x +1, $th_y+1, $wid, $hgt); 
			$pdf->SetFont('Tahoma','B',8);
			$pdf->Text($th_x +3, $th_y+70,'PT. SMT INDONESIA');
			// $pdf->Code39($th_x +32, $th_y+71.5,$noseri,0.5,4);
			
			$pdf->Text($th_x +68, $th_y+70,$noseri);
        }
		$pser = '';		
        if(isset($_COOKIE["PRINTLABEL_FG"])){
            $pser = $_COOKIE["PRINTLABEL_FG"];
		}
		if ($pser=='')
		{ 
			die('stop');
		} else {
			$wid = 96.5;
        	$hgt = 70;
			$thegap  = 1.76;
			$padX = 0.35;
			$padY = 0.35;
			$pprsize = $_COOKIE["PRINTLABEL_FG_SIZE"];
			$lbltype = $_COOKIE["PRINTLABEL_FG_LBLTYPE"];
			$pser = str_replace(str_split('"[]'),'', $pser);
			$pser = explode(",", $pser);
			$rs = [];
			if($this->SERC_mod->check_Primary(['SERC_NEWID' => $pser[0] ])>0 ){
				$rs = $this->SER_mod->selectBCField_in_nomega($pser);				
				$rs_tprint = $this->SERC_mod->select_exact_byVAR(['SERC_NEWID' => $pser[0]]);
				foreach($rs_tprint as $k){
					foreach ($rs as &$u){
						$u->MCUS_CUSNM = $k['MCUS_CUSNM'];
						$u->MBOM_GRADE = $k['MBOM_GRADE'];
					}
					unset($u);
				}
			} else {
				$rs = $this->SER_mod->selectBCField_in($pser);
			}
			$pdf = new PDF_Code39e128('L','mm',array(104,77));				
			if($lbltype=='1'){
				$pdf = new PDF_Code39e128('P','mm',$pprsize);
				$pdf->AddPage();
				$hgt_p = $pdf->GetPageHeight();
				$wid_p = $pdf->GetPageWidth();
				$pdf->SetAutoPageBreak(true,10);
				//$pdf->SetMargins(0,0);
				$cY=0;$cX=0;
				foreach($rs as $r){	
					$awo = explode('-', $r->SER_DOC);
					$ccustnm 	= $r->MCUS_CUSNM;
					$cuscd 	= trim($r->PDPP_BSGRP);					
					$noseri		= $r->SER_ID;		
					$cmitmid	= trim($r->SER_ITMID);
					$cmitmd1	= trim($r->MITM_ITMD1);
					$cprodt 	= explode('-', $r->SER_PRDDT);
					$cwo		= $awo[0] . '-'. $awo[1];
					$cprdline	= trim($r->SER_PRDLINE);
					$cprdshift	= $r->SER_PRDSHFT;
					$cserqty	= $r->SER_QTY;
					$csersheet	= $r->SER_SHEET;
					$cum	= trim($r->MITM_STKUOM) =='' ? 'PCS' : trim($r->MITM_STKUOM) ;
					$crank	= trim($r->MBOM_GRADE);
					//check wheter the height is enough
					if(($hgt_p - ($cY+$thegap)) < $hgt){
						$pdf->AddPage();
						$cY=0;$cX=0;
						printTag($pdf,$cX,$cY);
						$cX+=$wid+$thegap;						
					} else {
						if (($wid_p - $cX)>$wid){// jika (lebar printer-posisi X)> lebar label
							printTag($pdf, $cX, $cY);
							$cX+=$wid+$thegap;
						} else {
							$cY+=$hgt+$thegap;
							if(($hgt_p - ($cY+$thegap)) < $hgt){
								$pdf->AddPage();
								$cX = 0;
								$cY = 0;
								printTag($pdf, $cX, $cY);
							} else {
								$cX = 0;
								printTag($pdf, $cX, $cY);
							}
							$cX+=$wid+$thegap;
						}
					}
				}
			} else {
				$hgt_p = $pdf->GetPageHeight();
				$wid_p = $pdf->GetPageWidth();
				$pdf->SetAutoPageBreak(true,1);
				$pdf->SetMargins(0,0);			
				foreach($rs as $r){
					$cwo	= substr(trim($r->SER_DOC), 0, 7);
					$cmitmid= trim($r->SER_ITMID);
					$cserqty= $r->SER_QTY;
					$ccustnm = trim($r->MCUS_CUSNM);
					// $cuscd 	= trim($r->PDPP_CUSCD);
					$cprodt = explode('-', $r->SER_PRDDT);
					$cum	= trim($r->MITM_STKUOM);
					$crank	= trim($r->MBOM_GRADE);
					
					$pdf->AddPage();
					$pdf->SetFont('Arial','B',8);
					$pdf->Text(3,5,'CUSTOMER :');
					
					$pdf->Text(21,5, $ccustnm);
					$pdf->Line(1,9,103,9);
					$pdf->Line(85,1,85,57); //VERICAL LINE
					$pdf->SetFont('Arial','',8);
					$pdf->Text(87,8,'Rev : 01');
					$pdf->Text(86.5,12,'QC CHECK');
					$pdf->Line(85,13,103,13);
					$pdf->Line(85,32,103,32);
					$pdf->Text(86.5,35,'QC CHECK');
					$pdf->Line(85,36,103,36);
					$pdf->Text(2,12,'PART NAME');	
					$pdf->SetFont('Arial','',7.5);			
					$pdf->Text(2,15,'PART NUMBER/RANK');
					$pdf->SetFont('Arial','',8);
					$pdf->Text(2,25,'PRODUCTION DATE');
					$pdf->Text(2,29,'JOB NUMBER');
					$pdf->Text(2,38,'PROD. LINE / SHIFT');
					$pdf->Text(2,42,'QUANTITY');
					$pdf->Text(30,12,':');
					$pdf->Text(30,15,':');
					$pdf->Text(30,25,':');
					$pdf->Text(30,29,':');
					$pdf->Text(30,38,':');
					$pdf->Text(30,42,':');
					$pdf->Text(32,12,trim($r->MITM_ITMD1));	
					$clebar = $pdf->GetStringWidth($cmitmid)+10;			
					if(strpos($r->SER_ITMID,"_")){
						$pdf->Code128(32,13,$cmitmid,$clebar,5);
					} else {
						$pdf->Code39(32,13,$cmitmid);
					}					
					$pdf->Text(32,21,$cmitmid." / ". $crank);
					$pdf->Line(32,22,53,22);
					$pdf->Line(32,26,53,26);
					$pdf->Line(32,22,32,26); //VERTICAL
					$pdf->Line(38,22,38,26); //VERTICAL
					$pdf->Line(44,22,44,26); //VERTICAL
					$pdf->Line(53,22,53,26); //VERTICAL
					$pdf->Text(33,25,$cprodt[2]); $pdf->Text(39,25,$cprodt[1]);	$pdf->Text(45,25,$cprodt[0]);
					$clebar = $pdf->GetStringWidth($cwo)+25;
					$pdf->Code39(32,27,$cwo);
					$pdf->Text(32,35,$cwo);
					$pdf->Text(32,38,$r->SER_PRDLINE."/".$r->SER_PRDSHFT);
					$clebar = $pdf->GetStringWidth($r->SER_QTY)+10;
					// $pdf->Code39(32,39,number_format($r->SER_QTY));
					$pdf->Text(32,47,number_format($r->SER_QTY)." $cum ( ".$r->SER_SHEET." sheets)");				
	
	
					$pdf->Line(1,57,103,57);
					$pdf->SetFont('Arial','',8);
					$pdf->Text(3,60,'OQC INSPECTOR');
					$pdf->Text(3,64,'REMARK');
					$pdf->Text(30,60,':');
					$pdf->Text(30,64,':');
					// $pdf->Text(3,74,'REMARK');
					$image_name = 'LB'.$cmitmid.'|'.$cwo.'|'.number_format($cserqty);
					$cmd = escapeshellcmd("Python d:\Apache24\htdocs\wms\smt.py \"$image_name\" ");				
					$op = shell_exec($cmd);
					$image_name = str_replace("/","xxx", $image_name);
					$image_name = str_replace(" ","___", $image_name);
					$image_name = str_replace("|","lll", $image_name);
					$pdf->Image(base_url("assets/imgs/".$image_name.".png"), 87,  58);
					$pdf->Line(1,71,103,71);
					$pdf->Rect(1, 1, 102, 75);
					$pdf->SetFont('Arial','B',8);
					$pdf->Text(3,74,'PT. SMT INDONESIA');
					// $pdf->Code39(32,71.5,$r->SER_ID,0.5,4);
					$pdf->Text(85,74,$r->SER_ID);
				}
			}
			
			$pdf->Output('I','FG LABEL '.date("d-M-Y").'.pdf');
		}
	}

	function printrmlabel(){
		date_default_timezone_set('Asia/Jakarta');
		$currrtime = date('d/m/Y H:i:s');
		global  $wid, $hgt, $padX, $padY,  $noseri,  $cmitmid, $cmitmsptno, $host, $cfristname, $c3n1, $v3n1, $cserqty, $currrtime, $c3n2, $v3n2, $c1p, $v1p,$cuserid, $crohs, $cmade;
		function fnLeftrm($pdf, $cleft, $pword){
            global  $wid, $hgt, $padX, $padY;
            if($cleft>0){
                return $cleft + ($wid/2 - ( $pdf->GetStringWidth($pword)/2 ));
            } else {
                return $wid / 2 - ($pdf->GetStringWidth($pword)/2);
            }
		}
		function printTagrm($pdf,$myleft,$mytop){
            global  $wid, $hgt, $padX, $padY,  $noseri,  $cmitmid, $cmitmsptno, $host, $cfristname, $c3n1, $v3n1 , $cserqty, $currrtime, $c3n2, $v3n2,$c1p, $v1p, $cuserid, $crohs, $cmade;
			$th_x = $padX+$myleft;
			$th_y = $padY+$mytop;
			$yads=5;
			$xfSQ=7;
			$gapcontent = 1;
			$gapcontent2 = 2;
			$pdf->Rect($th_x +6, $th_y+6, $wid, $hgt);
			$pdf->SetFont('Courier','',7);
				
			$pdf->Text($th_x+$xfSQ, $th_y+3.5+$yads, 'ITEM CODE : '.trim($cmitmid).'   '.$host);			
			$pdf->Text($th_x+$xfSQ, $th_y+6.5+$yads, 'QTY : '.$cserqty);
			$pdf->Text($th_x+$xfSQ, $th_y+9.5+$yads, $v3n1);
			$clebar = $pdf->GetStringWidth(trim($c3n1))+10;	
			$pdf->Code128($th_x+$xfSQ+1, $th_y+10.5+$yads,$c3n1, $clebar, 5);

			
			$pdf->Text($th_x+$xfSQ, $th_y+17+$yads+$gapcontent, $v3n2);
			$clebar = $pdf->GetStringWidth(trim($c3n2))+10;
			$pdf->Code128($th_x+$xfSQ+1, $th_y+18+$yads+$gapcontent,$c3n2, $clebar, 5);

		
			$pdf->Text($th_x+$xfSQ, $th_y+24.5+$yads+$gapcontent2, $v1p);
			$clebar = $pdf->GetStringWidth(trim($c1p))+10;
			$pdf->Code128($th_x+$xfSQ+1, $th_y+26+$yads+$gapcontent2,$c1p,$clebar,5);
			
			$pdf->Text($th_x+$xfSQ+1, $th_y+34+$yads+$gapcontent2, 'PART NO : '.$cmitmsptno);
			if($crohs=='1'){
				$pdf->Text($th_x+$xfSQ+1, $th_y+44, 'RoHS Compliant');
			}
			$pdf->Text($th_x+$xfSQ+40, $th_y+44, 'C/O : Made in '.trim($cmade));
			$pdf->Text($th_x+$xfSQ+1, $th_y+42+$yads, $cuserid." : ".$cfristname);
			$pdf->Text($th_x+40+$xfSQ, $th_y+38+$yads, $currrtime);
			$image_name = $noseri;
			$cmd = escapeshellcmd("Python d:\Apache24\htdocs\wms\smt.py \"$image_name\" ");				
			$op = shell_exec($cmd);
			$image_name = str_replace("/","xxx", $image_name);
			$image_name = str_replace(" ","___", $image_name);
			$pdf->Image(base_url("assets/imgs/".$image_name.".png"),$th_x+60+$xfSQ,  $th_y+5+$yads);
        }
		$pserial = '';
        if(isset($_COOKIE["PRINTLABEL_RM"])){
            $pserial      = $_COOKIE["PRINTLABEL_RM"];
		} else {
			exit('no data');
		}
		$wid = 68;
        $hgt = 48;
		$pprsize = $_COOKIE["PRINTLABEL_RM_SIZE"];
		$lbltype = $_COOKIE["PRINTLABEL_RM_LBLTYPE"];
		
		$host = gethostbyaddr($_SERVER['REMOTE_ADDR']);         
		$pser = str_replace(str_split('"[]'),'', $pserial);
		$pser = explode(",", $pser);
		
		$rs = $this->SER_mod->selectBCFieldRM_in($pser);		
		//echo json_encode($rs);
		$pdf = new PDF_Code39e128('L','mm',array(70,50));		
		if($lbltype=='1'){
			$pdf = new PDF_Code39e128('L','mm',$pprsize);
			$pdf->AddPage();
			$hgt_p = $pdf->GetPageHeight();
			$wid_p = $pdf->GetPageWidth();
			$pdf->SetAutoPageBreak(true,10);
			$cY=0;$cX=0;
			$thegap  = 8;
			foreach($rs as $r){		
				
				$v3n1 = '(3N1) '.trim($r->SER_ITMID);
				$c3n1 = '3N1'.trim($r->SER_ITMID);
				$v3n2 = '(3N2) '.number_format($r->SER_QTY)." ".trim($r->SER_LOTNO);	
				$c3n2 = '3N2 '.number_format($r->SER_QTY,0,"","")." ".trim($r->SER_LOTNO);	
				$v1p = '(1P) '.trim($r->MITM_SPTNO);
				$c1p = '1P'.trim($r->MITM_SPTNO);
				$noseri =  $r->SER_ID;
				$cserqty = number_format($r->SER_QTY);
				$cmitmid = $r->SER_ITMID;
				$cmitmsptno = trim($r->MITM_SPTNO);
				$cfristname = $r->MSTEMP_FNM;
				$cuserid = $r->SER_USRID;
				$crohs = $r->SER_ROHS;
				$cmade=$r->MMADE_NM;
				if(($hgt_p - ($cY+$thegap)) < $hgt){
					$pdf->AddPage();
					$cY=0;$cX=0;
					printTagrm($pdf,$cX,$cY);
					$cX+=$wid+$thegap;						
				} else {						
					if (($wid_p - $cX)>($wid+4)){// jika (lebar printer-posisi X)> lebar label
						printTagrm($pdf, $cX, $cY);
						$cX+=$wid+$thegap;
					} else {
						$cY+=$hgt+$thegap;
						if(($hgt_p - ($cY+$thegap)) < $hgt){
							$pdf->AddPage();
							$cX = 0;
							$cY = 0;
							printTagrm($pdf, $cX, $cY);
						} else {
							$cX = 0;
							printTagrm($pdf, $cX, $cY);
						}
						$cX+=$wid+$thegap;
					}
				}

			}
		} else {
			$pdf->SetAutoPageBreak(true,1);
			$pdf->SetMargins(0,0);
			foreach($rs as $r){		
				$image_name = $r->SER_ID;
				$cmd = escapeshellcmd("Python d:\Apache24\htdocs\wms\smt.py \"$image_name\" ");				
				$op = shell_exec($cmd);
				$image_name = str_replace("/","xxx", $image_name);
				$image_name = str_replace(" ","___", $image_name);
	
				$v3n1 = '(3N1) '.trim($r->SER_ITMID);
				$c3n1 = '3N1'.trim($r->SER_ITMID);
				$v3n2 = '(3N2) '.number_format($r->SER_QTY)." ".trim($r->SER_LOTNO);	
				$c3n2 = '3N2 '.number_format($r->SER_QTY,0,"","")." ".trim($r->SER_LOTNO);	
				$v1p = '(1P) '.trim($r->MITM_SPTNO);
				$c1p = '1P'.trim($r->MITM_SPTNO);
				
				$pdf->AddPage();							   
				$pdf->SetY(0); 
				$pdf->SetX(0);
				$pdf->SetFont('Courier','',7);		
				$clebar = $pdf->GetStringWidth($c3n1)+10;				
				$pdf->Text(2, 3.5, 'ITEM CODE : '.$r->SER_ITMID.'   '.$host);			
				$pdf->Text(2, 6.5, 'QTY : '.number_format($r->SER_QTY));
				$pdf->Text(2, 9.5, $v3n1);
				// if(strpos($c3n1,"_")){
					$pdf->Code128(2, 10.5,$c3n1,$clebar,5);
				// } else {
				// 	$pdf->Code39(2, 10.5,$c3n1);
				// }
					
				$clebar = $pdf->GetStringWidth($c3n2)+10;
				$pdf->Text(2, 18, $v3n2);
				// if(strpos($c3n1,"_")){
					$pdf->Code128(2, 19,$c3n2,$clebar,5);
				// } else {
				// 	$pdf->Code39(2, 19,$c3n2);
				// }
				$clebar = $pdf->GetStringWidth($c1p)+10;
				$pdf->Text(2, 26, $v1p);
				$pdf->Code128(2, 27,$c1p,$clebar,4);
				$pdf->Text(2, 33, 'PART NO : '.trim($r->MITM_SPTNO));
				if($r->SER_ROHS=='1'){
					$pdf->Text(2, 36, 'RoHS Compliant');
				}
				$pdf->Text(40, 36, 'C/O : Made in '.trim($r->MMADE_NM));
				$pdf->Text(2, 38, $r->SER_USRID." : ".$r->MSTEMP_FNM);
				$pdf->Text(40, 38, $currrtime);
	
				$pdf->Image(base_url("assets/imgs/".$image_name.".png"),60,  3);		
			}
		}		
		$pdf->Output('I','LBL-IN-DO '.date("d-M-Y").'.pdf');
	}

	public function gettodaylist_infg(){
		header('Content-Type: application/json');
		date_default_timezone_set('Asia/Jakarta');
		$currdt	= date('Y-m-d');
		$rs		= $this->ITH_mod->selectAll_by(array('convert(date,ITH_LUPDT)' => $currdt, 'ITH_FORM' => 'INC'));
		echo json_encode($rs);		
	}

	public function release_penfg(){
		header('Content-Type: application/json');
		date_default_timezone_set('Asia/Jakarta');
		$currdt	= date('Y-m-d');
		$currdt_ptn = date('Ymd');
		$currdt_time	= date('Y-m-d H:i:s');		
		$cpenddoc = $this->input->post('inpenddoc');
		$citmcd = $this->input->post('initmcd');
		$cserid = $this->input->post('inserid');
		$crelqty = $this->input->post('inrelqty');
		$cpendqty = $this->input->post('inpendqty');
		$creleasedoc = $this->input->post('inreleasedoc');
		$ctgl = $this->input->post('intgl');
		$myar = array();
		///validating is scanned (pending)
		$rspending_scannedqty = $this->ITH_mod->select_scanned_pend($cpenddoc, $cserid);
		if(count($rspending_scannedqty) >0 ){
			//#1 SET OUT SERIAL FROM ITH
			$datas = array(
				'ITH_ITMCD' => $citmcd, 'ITH_DATE' => $currdt, 'ITH_FORM' => 'OUT-PEN-FG-ADJ' , 'ITH_DOC' => $cpenddoc, 'ITH_QTY' => -$cpendqty, 'ITH_WH' => 'QAFG',
				'ITH_SER' => $cserid , 'ITH_LUPDT' => $currdt_time, 'ITH_USRID' => $this->session->userdata('nama')
			);
			$resITH = $this->ITH_mod->insert_rls($datas);						
			if($resITH>0){
				//#2 CREATE NEW SERIAL
				$cmdl		= 1;
				$cproddt = '';
				$cjob = '';
				$csheet = '';
				$cline = '';
				$cshift = '';
				$rsser = $this->SER_mod->select_exact_byVAR(array('SER_ID' => $cserid));
				foreach($rsser as  $r){
					$cproddt = $r['SER_PRDDT'];
					$cjob = $r['SER_DOC'];
					$csheet = $r['SER_SHEET'];
					$cline = $r['SER_PRDLINE'];
					$cshift = $r['SER_PRDSHFT'];
				}
				$pYear		= substr($cproddt,2,2); $pMonth = substr($cproddt,5,2); $pDay = substr($cproddt, -2);
				$pMonthdis	= $this->getMonthDis($pMonth);
				$newid = $this->SER_mod->lastserialid($cmdl, $cproddt);
				$newid++;
				$newid = $cmdl.$pYear.$pMonthdis.$pDay.substr('000'.$newid, -4);
				$datas2 = array(
					'SER_ID' => $newid,
					'SER_DOC' => $cjob,
					'SER_DOCTYPE' => '1',
					'SER_ITMID' => $citmcd,
					'SER_QTY' => $crelqty,
					'SER_SHEET' => $csheet,
					'SER_PRDDT' => $cproddt,
					'SER_PRDLINE' => $cline,
					'SER_PRDSHFT' => $cshift,
					'SER_REFNO' => $cserid,
					'SER_LUPDT' => $currdt_time,
					'SER_USRID' => $this->session->userdata('nama')
				);
				$resSER  = $this->SER_mod->insert($datas2);
				if($resSER>0){
					//#3 update current serial
					$data_u_ser = array('SER_QTY' => ($cpendqty-$crelqty) );
					$resSER_u = $this->SER_mod->updatebyId($data_u_ser, $cserid);
					if($resSER_u > 0){
						//#4 add to release doc
						if(trim($creleasedoc)==''){
							$mlastid = $this->RLS_mod->lastserialidser();
							$mlastid++;
							$creleasedoc = 'RLSS'.$currdt_ptn.$mlastid;
						}
						$datas_rls = array(
							'RLSSER_DOC' => $creleasedoc,
							'RLSSER_SER' => $newid,
							'RLSSER_DT' => $ctgl,
							'RLSSER_QTY' => $crelqty,
							'RLSSER_REFFDOC' => $cpenddoc,
							'RLSSER_REFFSER' => $cserid,
							'RLSSER_LUPDT' => $currdt_time,
							'RLSSER_USRID' => $this->session->userdata('nama')
						);
						$resRLS = $this->RLS_mod->insert($datas_rls);
						if ( $resRLS > 0 ){
							//#5 insert into ITH
							$datas_final_ith = array(
								'ITH_ITMCD' => $citmcd,
								'ITH_DATE' =>  $currdt,
								'ITH_FORM' => 'IN-PEN-FG-ADJ',
								'ITH_DOC' => $cpenddoc,
								'ITH_QTY' => ($cpendqty-$crelqty),
								'ITH_WH' => 'QAFG',
								'ITH_SER' => $cserid,
								'ITH_LUPDT' => $currdt_time,
								'ITH_USRID' => $this->session->userdata('nama')
							);
							$resITH1  = $this->ITH_mod->insert_rls($datas_final_ith);
							$datas_final_ith = array(
								'ITH_ITMCD' => $citmcd,
								'ITH_DATE' =>  $currdt,
								'ITH_FORM' => 'IN-PEN-FG-ADJ',
								'ITH_DOC' => $cpenddoc,
								'ITH_QTY' => $crelqty,
								'ITH_WH' => 'QAFG',
								'ITH_SER' => $newid,
								'ITH_REMARK' => $creleasedoc,
								'ITH_LUPDT' => $currdt_time,
								'ITH_USRID' => $this->session->userdata('nama')
							);
							$resITH2  = $this->ITH_mod->insert_rls($datas_final_ith);
							if(($resITH1+$resITH2)>=2){
								$datar = array("cd" => '1', "msg" => "OK", "reffdoc" => $creleasedoc, "reffser" => $newid );
							} else {
								$datar = array("cd" => '0', "msg" => "Could not add data back to transaction");
							}
						} else {
							$datar = array("cd" => '0', "msg" => "Could not add document of release Serial FG");
						}
					} else {
						$datar = array("cd" => '0', "msg" => "Could not update data current Serial FG");
					}
				} else {
					$datar = array("cd" => '0', "msg" => "Could not set add new Serial FG");
				}
			} else {
				$datar = array("cd" => '0', "msg" => "Could not set OUT Serial FG");
			}
		} else {
			$datar = array("cd" => '0', "msg" => "The serial have not been scanned yet");			
		}
		array_push($myar, $datar);
		echo json_encode($myar);
	}

	public function release_penfg1(){
		header('Content-Type: application/json');
		date_default_timezone_set('Asia/Jakarta');
		$currdt	= date('Y-m-d');
		$currdt_ptn = date('Ymd');
		$currdt_time	= date('Y-m-d H:i:s');		
		$cpenddoc = $this->input->post('inpenddoc');
		$citmcd = $this->input->post('initmcd');
		$cserid = $this->input->post('inserid');
		$cseridnew = $this->input->post('inseridnew');
		$crelqty = $this->input->post('inrelqty');
		$cpendqty = $this->input->post('inpendqty');
		$creleasedoc = $this->input->post('inreleasedoc');
		$ctgl = $this->input->post('intgl');
		$clot = $this->input->post('inlot');
		$myar = array();
		///validating is scanned (pending)
		$rspending_scannedqty = $this->ITH_mod->select_scanned_pend($cpenddoc, $cserid);
		if(count($rspending_scannedqty) >0 ){
			//#1 SET OUT SERIAL FROM ITH
			$datas = array(
				'ITH_ITMCD' => $citmcd, 'ITH_DATE' => $currdt, 'ITH_FORM' => 'OUT-PEN-FG-ADJ' , 'ITH_DOC' => $cpenddoc, 'ITH_QTY' => -$cpendqty, 'ITH_WH' => 'QAFG',
				'ITH_SER' => $cserid , 'ITH_LUPDT' => $currdt_time, 'ITH_USRID' => $this->session->userdata('nama')
			);
			$resITH = $this->ITH_mod->insert_rls($datas);						
			if($resITH>0){
				//#2 CREATE NEW SERIAL
				$newid ='';
				if(strpos($cseridnew,"|")){
					//#2.1 HANDLE REGULAR PART
					$anewserid = explode("|", $cseridnew);
					if(count($anewserid)==7){
						if(substr($anewserid[5],0,2)=="Z5"){
							$tempid = substr($anewserid[5],2,strlen($anewserid[5]));
							if($this->SER_mod->check_Primary(array("SER_ID" => $tempid)) ==0 ){
								$newid = $tempid;
							} else {
								$datar = array("cd" => '0', "msg" => "Serial Label is already registered");
								array_push($myar, $datar);
								exit(json_encode($myar));
							}
						} else {
							$datar = array("cd" => '0', "msg" => "Serial Label is not valid");
							array_push($myar, $datar);
							exit(json_encode($myar));
						}
					} else {
						$datar = array("cd" => '0', "msg" => "Label is not valid");
						array_push($myar, $datar);
						exit(json_encode($myar));
					}
				} else {
					//#2.2 HANDLE NON REGULAR
					if(strlen($cseridnew)==16){
						$tempid = $cseridnew;
						if($this->SER_mod->check_Primary(array("SER_ID" => $tempid)) ==0 ){
							$newid = $tempid;
						} else {
							$datar = array("cd" => '0', "msg" => "Serial Label is already registered");
							array_push($myar, $datar);
							exit(json_encode($myar));
						}
					} else {
						$datar = array("cd" => '0', "msg" => "Reff Label is not valid");
						array_push($myar, $datar);
						exit(json_encode($myar));
					}
				}
				$cproddt = '';
				$cjob = '';
				$csheet = '';
				$cline = '';
				$cshift = '';
				$crawtext = $cseridnew;
				$rsser = $this->SER_mod->select_exact_byVAR(array('SER_ID' => $cserid));
				foreach($rsser as  $r){
					$cproddt = $r['SER_PRDDT'];
					$cjob = $r['SER_DOC'];
					$csheet = $r['SER_SHEET'];
					$cline = $r['SER_PRDLINE'];
					$cshift = $r['SER_PRDSHFT'];
				}
				
				$datas2 = array(
					'SER_ID' => $newid,
					'SER_DOC' => $cjob,					
					'SER_ITMID' => $citmcd,
					'SER_QTY' => $crelqty,
					'SER_SHEET' => $csheet,
					'SER_PRDDT' => $cproddt,
					'SER_PRDLINE' => $cline,
					'SER_PRDSHFT' => $cshift,
					'SER_REFNO' => $cserid,
					'SER_LOTNO' => $clot,
					'SER_RAWTXT' => $crawtext,
					'SER_LUPDT' => $currdt_time,
					'SER_USRID' => $this->session->userdata('nama')
				);
				$resSER  = $this->SER_mod->insert($datas2);
				if($resSER>0){
					//#3 update current serial
					$data_u_ser = array('SER_QTY' => ($cpendqty-$crelqty) );
					$resSER_u = $this->SER_mod->updatebyId($data_u_ser, $cserid);
					if($resSER_u > 0){
						//#4 add to release doc
						if(trim($creleasedoc)==''){
							$mlastid = $this->RLS_mod->lastserialidser();
							$mlastid++;
							$creleasedoc = 'RLSS'.$currdt_ptn.$mlastid;
						}
						$datas_rls = array(
							'RLSSER_DOC' => $creleasedoc,
							'RLSSER_SER' => $newid,
							'RLSSER_DT' => $ctgl,
							'RLSSER_QTY' => $crelqty,
							'RLSSER_REFFDOC' => $cpenddoc,
							'RLSSER_REFFSER' => $cserid,
							'RLSSER_LUPDT' => $currdt_time,
							'RLSSER_USRID' => $this->session->userdata('nama')
						);
						$resRLS = $this->RLS_mod->insert($datas_rls);
						if ( $resRLS > 0 ){
							//#5 insert into ITH
							$datas_final_ith = array(
								'ITH_ITMCD' => $citmcd,
								'ITH_DATE' =>  $currdt,
								'ITH_FORM' => 'IN-PEN-FG-ADJ',
								'ITH_DOC' => $cpenddoc,
								'ITH_QTY' => ($cpendqty-$crelqty),
								'ITH_WH' => 'QAFG',
								'ITH_SER' => $cserid,
								'ITH_LUPDT' => $currdt_time,
								'ITH_USRID' => $this->session->userdata('nama')
							);
							$resITH1  = $this->ITH_mod->insert_rls($datas_final_ith);
							$datas_final_ith = array(
								'ITH_ITMCD' => $citmcd,
								'ITH_DATE' =>  $currdt,
								'ITH_FORM' => 'IN-PEN-FG-ADJ',
								'ITH_DOC' => $cpenddoc,
								'ITH_QTY' => $crelqty,
								'ITH_WH' => 'QAFG',
								'ITH_SER' => $newid,
								'ITH_REMARK' => $creleasedoc,
								'ITH_LUPDT' => $currdt_time,
								'ITH_USRID' => $this->session->userdata('nama')
							);
							$resITH2  = $this->ITH_mod->insert_rls($datas_final_ith);
							if(($resITH1+$resITH2)>=2){
								$datar = array("cd" => '1', "msg" => "OK", "reffdoc" => $creleasedoc, "reffser" => $newid );
							} else {
								$datar = array("cd" => '0', "msg" => "Could not add data back to transaction");
							}
						} else {
							$datar = array("cd" => '0', "msg" => "Could not add document of release Serial FG");
						}
					} else {
						$datar = array("cd" => '0', "msg" => "Could not update data current Serial FG");
					}
				} else {
					$datar = array("cd" => '0', "msg" => "Could not set add new Serial FG");
				}
			} else {
				$datar = array("cd" => '0', "msg" => "Could not set OUT Serial FG");
			}
		} else {
			$datar = array("cd" => '0', "msg" => "The serial have not been scanned yet");			
		}
		array_push($myar, $datar);
		echo json_encode($myar);
	}

	public function getproperties_n_tx(){
		header('Content-Type: application/json');
		$cold_reff = $this->input->get('inid');
		$rsold_reff = $this->SER_mod->select_exact_byVAR(array('SER_ID' => $cold_reff));
		$rxtx = $this->ITH_mod->selectAll_by(array('ITH_SER' => $cold_reff));
		$myar = array();
		if(count($rsold_reff)>0){
			
			if($this->SISCN_mod->check_Primary(array('SISCN_SER' => $cold_reff)) >0){
				$datar = array("cd" => '0', "msg" => "could not split delivered item label");
			} else {
				$datar = array("cd" => '1', "msg" => "go ahead");
			}
		} else {
			$datar = array("cd" => '0', "msg" => "Reff No is not found");
		}
		array_push($myar, $datar);
		echo '{"data":';
		echo json_encode($rsold_reff);
		echo ',"tx":'.json_encode($rxtx);
		echo ',"status":'.json_encode($myar);
		echo '}';
	}

	public function getproperties_n_tx_splitplant1(){
		header('Content-Type: application/json');
		$myar = array();
		$cold_reff = $this->input->get('inid');
		$rsold_reff = $this->SER_mod->select_exact_byVAR(array('SER_ID' => $cold_reff));
		foreach($rsold_reff as $r){
			if($r['PDPP_BSGRP']!='PSI1PPZIEP'){
				$datar = array("cd" => "0", "msg" => "Please split the label on Plant 2 Menu");
				array_push($myar, $datar);
				exit('{"status":'.json_encode($myar).'}');				
			}
		}
		$rslastwh = $this->ITH_mod->selectstock_ser($cold_reff);		
		$rxtx = $this->ITH_mod->selectAll_by(array('ITH_SER' => $cold_reff));
		if(count($rsold_reff)>0){
			
			if($this->SISCN_mod->check_Primary(array('SISCN_SER' => $cold_reff)) >0){
				$datar = array("cd" => '0', "msg" => "could not split delivered item label");
			} else {
				$datar = array("cd" => '1', "msg" => "go ahead");
			}
		} else {
			$datar = array("cd" => '0', "msg" => "Reff No is not found");
		}
		array_push($myar, $datar);
		echo '{"data":';
		echo json_encode($rsold_reff);
		echo ',"tx":'.json_encode($rxtx);
		echo ',"status":'.json_encode($myar);
		echo ',"lastwh":'.json_encode($rslastwh);
		echo '}';
	}

	public function validate_newreff(){
		header('Content-Type: application/json');		
		
		$crawtext = $this->input->get('inrawtext');
		$cremark = $this->input->get('inremark');
		$citmcd = $this->input->get('initemcd');
		$ctypefg = $this->input->get('intypefg');
		$clot = $this->input->get('inlot');
		$cqty = $this->input->get('inqty');
		$myar = array();
		if(strpos($crawtext, "|") !== false){ /// regular item
			$araw =  explode("|", $crawtext);
			$newitemcd = substr($araw[0],2,strlen($araw[0])-2).$cremark;
			$newreff = substr($araw[5],2,strlen($araw[5])-2);
			
			if($this->MSTITM_mod->check_Primary(['MITM_ITMCD' => $newitemcd]) == 0) {
				$datar = array("cd" => "0", "msg" => "Item Code is not registered [$newitemcd]");
				array_push($myar, $datar);
				exit('{"status":'.json_encode($myar).'}');
			}

			if($this->SER_mod->check_Primary(array("SER_ID" => $newreff))>0 ){
				$datar = array("cd" => "0", "msg" => "the reff no is already registered");
				array_push($myar, $datar);
				exit('{"status":'.json_encode($myar).'}');
			} else {

				$datar = array("cd" => "1", "msg" => "go ahead", "rawtext" => $crawtext);				
				array_push($myar, $datar);
				exit('{"status":'.json_encode($myar).'}');
			}
			
		} else { /// HANDLE NON REGULAR
			$newitmcd = $citmcd.$ctypefg.$cremark;
			if($this->MSTITM_mod->check_Primary(['MITM_ITMCD' => $newitmcd]) == 0) {
				$datar = array("cd" => "0", "msg" => "Item Code is not registered [$newitmcd]");
				array_push($myar, $datar);
				exit('{"status":'.json_encode($myar).'}');
			}
			if($this->SER_mod->check_Primary(array("SER_ID" => $crawtext)) > 0 ){
				$datar = array("cd" => "0", "msg" => "the reff no is already registered" );
				array_push($myar, $datar);
				exit('{"status":'.json_encode($myar).'}');
			} else {
				$datar = array("cd" => "1", "msg" => "go ahead" 
				,"reffno" => $crawtext,  "typefg" => $ctypefg
				,"assycode" => $newitmcd, "lot" => $clot 
				,"qty" => $cqty, "remark" => $cremark );
				array_push($myar, $datar);
				exit('{"status":'.json_encode($myar).'}');
			}
		}
	}
	public function validate_newreff2(){
		header('Content-Type: application/json');
		$crawtext = $this->input->get('inrawtext2');
		
		$myar = array();
		if(strpos($crawtext, "|") !== false){ /// regular item
			$araw =  explode("|", $crawtext);
			$newreff = substr($araw[5],2,strlen($araw[5])-2);			
			
			if($this->SER_mod->check_Primary(array("SER_ID" => $newreff))>0 ){
				$datar = array("cd" => "0", "msg" => "the reff no is already registered");
				array_push($myar, $datar);
				exit('{"status":'.json_encode($myar).'}');
			} else {
				$datar = array("cd" => "1", "msg" => "go ahead", "rawtext" => $crawtext);				
				array_push($myar, $datar);
				exit('{"status":'.json_encode($myar).'}');
			}
			
		} else { /// HANDLE NON REGULAR
			$datar = array("cd" => "0", "msg" => "the process is under construction ._." );
			array_push($myar, $datar);
			exit('{"status":'.json_encode($myar).'}');
		}
	}

	public function prc_splitplant1(){
		$this->checkSession();
		date_default_timezone_set('Asia/Jakarta');			
		$currrtime	= date('Y-m-d H:i:s');
		$currdate	= date('Y-m-d');
		$myar = array();
		$coldreff = $this->input->post('inoldreff');
		$colditem = $this->input->post('inolditem');
		$coldqty = $this->input->post('inoldqty');
		$coldjob = $this->input->post('inoldjob');

		$ca_reff = $this->input->post('ina_reff');
		$ca_fgtype = $this->input->post('ina_fgtype');
		$ca_itmcd = $this->input->post('ina_itmcd');
		$ca_lot = $this->input->post('ina_lot');
		$ca_qty = $this->input->post('ina_qty');
		$ca_ok = $this->input->post('ina_isok');
		$ca_remark = $this->input->post('ina_remark');
		$ca_rawtxt = $this->input->post('ina_rawtxt');
		$ca_count  = count($ca_reff);

		$CUSERID = $this->session->userdata('nama');

		if($this->SER_mod->check_Primary(array('SER_ID' => $coldreff)) ==0){
			$datar = array("cd" => '0', "msg" => "Old Label not found {$coldreff} or may be already splitted");			
			array_push($myar, $datar);
			exit('{"status":'.json_encode($myar).'}');
		}

		if($this->SER_mod->check_Primary(array('SER_ID' => $coldreff , 'SER_QTY >0' => null)) ==0){
			$datar = array("cd" => '0', "msg" => "qty old label = 0");			
			array_push($myar, $datar);
			exit('{"status":'.json_encode($myar).'}');
		}

		//get last warehouse , location ....
		if($this->SISCN_mod->check_Primary(array('SISCN_SER' => $coldreff)) >0){
			$datar = array("cd" => '0', "msg" => "could not split delivered item label");			
			array_push($myar, $datar);
			exit('{"status":'.json_encode($myar).'}');
		} else {			
			if($this->SER_mod->updatebyId(array("SER_QTY" => 0,"SER_QTYLOT" => 0, "SER_LUPDT" => $currrtime, "SER_USRID" => $CUSERID ), $coldreff) >0){
				$ser_insert_ok = 0;
				for($i=0; $i<$ca_count ; $i++){
					$ser_insert_ok += $this->SER_mod->insert(array(
						"SER_ID" => $ca_reff[$i],
						"SER_DOC" => $coldjob,
						"SER_ITMID" => strtoupper($ca_itmcd[$i]),
						"SER_QTY" => $ca_qty[$i],
						"SER_QTYLOT" => $ca_qty[$i],
						"SER_LOTNO" => $ca_lot[$i],
						"SER_REFNO" => $coldreff,
						"SER_RAWTXT" => $ca_rawtxt[$i],
						"SER_LUPDT" => $currrtime,
						"SER_USRID" => $this->session->userdata('nama')
					));
				}
				if($ser_insert_ok==$ca_count){
					$rsactive = $this->ITH_mod->select_active_ser($coldreff);
					$rsactive_wh =  $rsactive_loc = $rsactive_time = $rsactive_form = $rsactive_date =''  ;
					foreach($rsactive as $r){
						$rsactive_wh = trim($r['ITH_WH']);
						$rsactive_loc = trim($r['ITH_LOC']);
						$rsactive_date = trim($r['ITH_DATE']);
						$rsactive_time = trim($r['ITH_LUPDT']);
						$rsactive_form = trim($r['ITH_FORM']);
					}
					if($rsactive_form=="INC-QA-FG" && $rsactive_wh=="ARQA1"){
						$ret_min = $this->ITH_mod->insert(
							array(
								"ITH_ITMCD" => $colditem,
								"ITH_DATE" => $rsactive_date,
								"ITH_FORM" => $rsactive_form,
								"ITH_DOC" => $coldjob,
								"ITH_QTY" => -$coldqty,
								"ITH_SER" => $coldreff,
								"ITH_WH" => $rsactive_wh,
								"ITH_LUPDT" => $rsactive_time,
								"ITH_REMARK" => "WIL-SPLIT",
								"ITH_USRID" => $this->session->userdata('nama')
							)
						);
						if($ret_min>0){
							$ith_insert_ok = 0;
							for($i=0; $i<$ca_count ; $i++){
								if($ca_ok[$i] == "1"){
									$ith_insert_ok += $this->ITH_mod->insert(
										array(
											"ITH_ITMCD" => strtoupper($ca_itmcd[$i]),
											"ITH_DATE" => $currdate,
											"ITH_FORM" => "INC-QA-FG",
											"ITH_DOC" => $coldjob,
											"ITH_QTY" => $ca_qty[$i],
											"ITH_SER" => $ca_reff[$i],
											"ITH_WH" => $rsactive_wh,
											"ITH_LUPDT" => $currrtime,
											"ITH_REMARK" => "AFT-SPLIT",
											"ITH_USRID" => $this->session->userdata('nama')
										)
									);									
								} else {
									$ith_insert_ok += 1;
								}
							}
							if($ith_insert_ok==$ca_count){
								$datar = array("cd" => "1", "msg" => "ok QC" );
								array_push($myar, $datar);
								exit('{"status":'.json_encode($myar).'}');
							} else {
								$datar = array("cd" => "0", "msg" => "Not All label add to transaction, please contact Admin" );
								array_push($myar, $datar);
								exit('{"status":'.json_encode($myar).'}');
							}
						} else {
							$datar = array("cd" => "0", "msg" => "Could not minus transaction, please contact Admin" );
							array_push($myar, $datar);
							exit('{"status":'.json_encode($myar).'}');
						}
					} elseif($rsactive_wh=="ARPRD1") {
						$ret_min = $this->ITH_mod->insert(
							array(
								"ITH_ITMCD" => $colditem,
								"ITH_DATE" => $currdate,
								"ITH_FORM" => "OUT-PRD-FG",
								"ITH_DOC" => $coldjob,
								"ITH_QTY" => -$coldqty,
								"ITH_SER" => $coldreff,
								"ITH_WH" => $rsactive_wh,
								"ITH_LUPDT" => $currrtime,
								"ITH_REMARK" => "WIL-SPLIT",
								"ITH_USRID" => $CUSERID
							)
						);
						if($ret_min>0){
							$ith_insert_ok = 0;
							for($i=0; $i<$ca_count ; $i++){
								if($ca_ok[$i] == "1"){
									$ith_insert_ok += $this->ITH_mod->insert(
										array(
											"ITH_ITMCD" => strtoupper($ca_itmcd[$i]),
											"ITH_DATE" => $currdate,
											"ITH_FORM" => "INC-QA-FG",
											"ITH_DOC" => $coldjob,
											"ITH_QTY" => $ca_qty[$i],
											"ITH_SER" => $ca_reff[$i],
											"ITH_WH" => 'ARQA1',
											"ITH_LUPDT" => $currrtime,
											"ITH_REMARK" => "AFT-SPLIT",
											"ITH_USRID" => $CUSERID
										)
									);									
								} else {
									$ith_insert_ok += 1;
								}
							}
							if($ith_insert_ok==$ca_count){
								$datar = array("cd" => "1", "msg" => "ok ." );
								array_push($myar, $datar);
								exit('{"status":'.json_encode($myar).'}');
							} else {
								$datar = array("cd" => "0", "msg" => "Not All label add to transaction PRD, please contact Admin" );
								array_push($myar, $datar);
								exit('{"status":'.json_encode($myar).'}');
							}
						} else {
							$datar = array("cd" => "0", "msg" => "Could not minus transaction PRD, please contact Admin" );
							array_push($myar, $datar);
							exit('{"status":'.json_encode($myar).'}');
						}
					} elseif($rsactive_wh=="AFWH3") { 
						$ret_min = $this->ITH_mod->insert(
							array(
								"ITH_ITMCD" => $colditem,
								"ITH_DATE" => $currdate,
								"ITH_FORM" => 'SPLIT-FG-LBL',
								"ITH_DOC" => $coldjob,
								"ITH_QTY" => -$coldqty,
								"ITH_SER" => $coldreff,
								"ITH_WH" => $rsactive_wh,
								"ITH_LOC" => $rsactive_loc,
								"ITH_LUPDT" => $currrtime,
								"ITH_REMARK" => "WIL-SPLIT",
								"ITH_USRID" => $CUSERID
							)
						);
						if($ret_min>0){ 
							$ith_insert_ok = 0;
							for($i=0; $i<$ca_count ; $i++){
								// if($ca_ok[$i] == "1"){
									if((strpos(strtoupper($colditem),'KD') !== false || 
										strpos(strtoupper($colditem),'ASP') !== false 
										) && (!strpos(strtoupper($ca_itmcd[$i]),'KD') !==false &&
										!strpos(strtoupper($ca_itmcd[$i]),'ASP') !==false
										)
									) {										
										//echo "TRANSFER TO PRD";
										$ith_insert_ok += $this->ITH_mod->insert(
											array(
												"ITH_ITMCD" => strtoupper($ca_itmcd[$i]),
												"ITH_DATE" => $currdate,
												"ITH_FORM" => 'SPLIT-FG-LBL',
												"ITH_DOC" => $coldjob,
												"ITH_QTY" => $ca_qty[$i],
												"ITH_SER" => $ca_reff[$i],
												"ITH_WH" => 'ARPRD1',
												"ITH_LOC" => 'TEMP',
												"ITH_LUPDT" => $currrtime,
												"ITH_REMARK" => "AFT-SPLIT",
												"ITH_USRID" => $CUSERID
											)
										);
									} else {
										if((!strpos(strtoupper($colditem),'KD') !== false && 
											!strpos(strtoupper($colditem),'ASP') !== false 
											) && (strpos(strtoupper($ca_itmcd[$i]),'KD') !==false || 
											strpos(strtoupper($ca_itmcd[$i]),'ASP') !==false
											)
										) {
											$ith_insert_ok += $this->ITH_mod->insert(
												array(
													"ITH_ITMCD" => strtoupper($ca_itmcd[$i]),
													"ITH_DATE" => $currdate,
													"ITH_FORM" => 'SPLIT-FG-LBL',
													"ITH_DOC" => $coldjob,
													"ITH_QTY" => $ca_qty[$i],
													"ITH_SER" => $ca_reff[$i],
													"ITH_WH" => 'ARPRD1',
													"ITH_LOC" => 'TEMP',
													"ITH_LUPDT" => $currrtime,
													"ITH_REMARK" => "AFT-SPLIT",
													"ITH_USRID" => $CUSERID
												)
											);
										} else {
											//TO SAME WAREHOUSE
											$ith_insert_ok += $this->ITH_mod->insert(
												array(
													"ITH_ITMCD" => strtoupper($ca_itmcd[$i]),
													"ITH_DATE" => $currdate,
													"ITH_FORM" => 'SPLIT-FG-LBL',
													"ITH_DOC" => $coldjob,
													"ITH_QTY" => $ca_qty[$i],
													"ITH_SER" => $ca_reff[$i],
													"ITH_WH" => $rsactive_wh,
													"ITH_LOC" => $rsactive_loc,
													"ITH_LUPDT" => $currrtime,
													"ITH_REMARK" => "AFT-SPLIT",
													"ITH_USRID" => $CUSERID
												)
											);											
										}										
									}																		
								// } else {
								// 	$ith_insert_ok += 1;
								// }
							}
							if($ith_insert_ok==$ca_count){
								$datar = array("cd" => "1", "msg" => "ok .." );
								array_push($myar, $datar);
								exit('{"status":'.json_encode($myar).'}');
							} else {
								$datar = array("cd" => "0", "msg" => "Not All label add to transaction FG, please contact Admin" );
								array_push($myar, $datar);
								exit('{"status":'.json_encode($myar).'}');
							}
						} else {
							$datar = array("cd" => "0", "msg" => "Could not minus transaction FG, please contact Admin" );
							array_push($myar, $datar);
							exit('{"status":'.json_encode($myar).'}');
						}
					} else {
						$datar = array("cd" => "0", "msg" => "Could not process to transaction, please contact Admin" );
						array_push($myar, $datar);
						exit('{"status":'.json_encode($myar).'}');
					}
				} else {
					$datar = array("cd" => "0", "msg" => "Not All label registered, please contact Admin" );
					array_push($myar, $datar);
					exit('{"status":'.json_encode($myar).'}');
				}
			} else {
				$datar = array("cd" => "0", "msg" => "Could not update old reff data " );
				array_push($myar, $datar);
				exit('{"status":'.json_encode($myar).'}');
			}
		}

	}

	public function validate_newreffall(){
		$this->checkSession();
		date_default_timezone_set('Asia/Jakarta');			
		$currrtime	= date('Y-m-d H:i:s');
		$currdate	= date('Y-m-d');
		$myar = array();
		$coldreff = $this->input->post('inoldreff');
		$colditem = $this->input->post('inolditem');
		$coldqty = $this->input->post('inoldqty');
		$coldjob = $this->input->post('inoldjob');

		$creff1 = $this->input->post('innewreff1');
		$cqty1 = $this->input->post('innewqty1');
		$craw1 = $this->input->post('inraw1');
		$crmrk1 = $this->input->post('inrmrk1');
		$clot1 = '';

		$creff2 = $this->input->post('innewreff2');
		$cqty2 = $this->input->post('innewqty2');
		$craw2 = $this->input->post('inraw2');
		$crmrk2 = $this->input->post('inrmrk2');
		$ctypeg = $this->input->post('intypeg');

		$ctypefg = $this->input->post('intypefg');
		$creffka = $this->input->post('inreffcdka');
		$citemcdka = $this->input->post('initemcd');
		$clotka = $this->input->post('inlot');
		$cqtyka = $this->input->post('inqty');
		$cremarkka = $this->input->post('inremarkka');		
		
		$clot2 = '';

		
		//decrypt lot
		if(strpos($craw1, "|") !== false){
			$araw =  explode("|", $craw1);			
			$clot1 = substr($araw[2],2,strlen($araw[2])-2);
		}
		if(strpos($craw2, "|") !== false){
			$araw =  explode("|", $craw2);			
			$clot2 = substr($araw[2],2,strlen($araw[2])-2);
		}
		//end decrypt

		if($this->SER_mod->check_Primary(array('SER_ID' => $coldreff)) ==0){
			$datar = array("cd" => '0', "msg" => "Old Label not found {$coldreff}");			
			array_push($myar, $datar);
			exit('{"status":'.json_encode($myar).'}');
		}

		//get last warehouse , location ....
		if($this->SISCN_mod->check_Primary(array('SISCN_SER' => $coldreff)) >0){
			$datar = array("cd" => '0', "msg" => "could not split delivered item label");			
			array_push($myar, $datar);
			exit('{"status":'.json_encode($myar).'}');
		} else {			

			$rsactive = $this->ITH_mod->select_active_ser($coldreff);
			$rsactive_wh =  $rsactive_loc = $rsactive_time = $rsactive_form = $rsactive_date =''  ;
			foreach($rsactive as $r){
				$rsactive_wh = trim($r['ITH_WH']);
				$rsactive_loc = trim($r['ITH_LOC']);
				$rsactive_date = trim($r['ITH_DATE']);
				$rsactive_time = trim($r['ITH_LUPDT']);
				$rsactive_form = trim($r['ITH_FORM']);
			}
			if($creffka==''){
				$ret = $this->SER_mod->insert(array(
					"SER_ID" => $creff1,
					"SER_DOC" => $coldjob,
					"SER_ITMID" => $colditem,
					"SER_QTY" => $cqty1,
					"SER_QTYLOT" => $cqty1,
					"SER_LOTNO" => $clot1,
					"SER_REFNO" => $coldreff,
					"SER_RAWTXT" => $craw1,				
					"SER_LUPDT" => $currrtime,
					"SER_USRID" => $this->session->userdata('nama')
				));
			} else {
				$ret = $this->SER_mod->insert(array(
					"SER_ID" => $creffka,
					"SER_DOC" => $coldjob,
					"SER_ITMID" => $citemcdka.$ctypefg.$cremarkka,
					"SER_QTY" => $cqtyka,
					"SER_QTYLOT" => $cqtyka,
					"SER_LOTNO" => $clotka,
					"SER_REFNO" => $coldreff,					
					"SER_LUPDT" => $currrtime,
					"SER_USRID" => $this->session->userdata('nama')
				));
			}
			if($ret>0){
				$ret2 = $this->SER_mod->insert(array(
					"SER_ID" => $creff2,
					"SER_DOC" => $coldjob,
					"SER_ITMID" => $colditem,
					"SER_QTY" => $cqty2,
					"SER_QTYLOT" => $cqty2,
					"SER_LOTNO" => $clot2,
					"SER_REFNO" => $coldreff,
					"SER_RAWTXT" => $craw2,
					"SER_GORNG" => $ctypeg,
					"SER_LUPDT" => $currrtime,
					"SER_USRID" => $this->session->userdata('nama')
				));
				$ret3  = $this->SER_mod->updatebyId(array("SER_QTY" => 0,"SER_QTYLOT" => 0, "SER_LUPDT" => $currrtime), $coldreff);
				if($ret3>0){
					// START MINUS
					if($rsactive_form=="INC-QA-FG" && $rsactive_wh=="ARQA1"){
						$ret4 = $this->ITH_mod->insert(
							array(
								"ITH_ITMCD" => $colditem,
								"ITH_DATE" => $rsactive_date,
								"ITH_FORM" => $rsactive_form,
								"ITH_DOC" => $coldjob,
								"ITH_QTY" => -$coldqty,
								"ITH_SER" => $coldreff,
								"ITH_WH" => $rsactive_wh,
								"ITH_LUPDT" => $rsactive_time,
								"ITH_REMARK" => "WIL-SPLIT",
								"ITH_USRID" => $this->session->userdata('nama')
							)
						);
						if($ret4>0){
							//START PLUS
							$currdate	= date('Y-m-d');
							$ret5=0;
							if($creffka==''){
								$ret5 = $this->ITH_mod->insert(
									array(
										"ITH_ITMCD" => $colditem,
										"ITH_DATE" => $currdate,
										"ITH_FORM" => "INC-QA-FG",
										"ITH_DOC" => $coldjob,
										"ITH_QTY" => $cqty1,
										"ITH_SER" => $creff1,
										"ITH_WH" => $rsactive_wh,
										"ITH_LUPDT" => $currrtime,
										"ITH_REMARK" => "AFT-SPLIT",
										"ITH_USRID" => $this->session->userdata('nama')
									)
								);
							} else {
								$ret5 = $this->ITH_mod->insert(
									array(
										"ITH_ITMCD" => $citemcdka.$ctypefg.$cremarkka,
										"ITH_DATE" => $currdate,
										"ITH_FORM" => "INC-QA-FG",
										"ITH_DOC" => $coldjob,
										"ITH_QTY" => $cqtyka,
										"ITH_SER" => $creffka,
										"ITH_WH" => $rsactive_wh,
										"ITH_LUPDT" => $currrtime,
										"ITH_REMARK" => "AFT-SPLIT",
										"ITH_USRID" => $this->session->userdata('nama')
									)
								);
							}
							if($ctypeg=='1'){
								$ret6 = $this->ITH_mod->insert(
									array(
										"ITH_ITMCD" => $colditem,
										"ITH_DATE" => $currdate,
										"ITH_FORM" => "INC-QA-FG",
										"ITH_DOC" => $coldjob,
										"ITH_QTY" => $cqty2,
										"ITH_SER" => $creff2,
										"ITH_WH" => $rsactive_wh,
										"ITH_LUPDT" => $currrtime,
										"ITH_REMARK" => "AFT-SPLIT",
										"ITH_USRID" => $this->session->userdata('nama')
									)
								);
							}
							
							//END PLUS
							if($ret5>0){
								$datar = array("cd" => "1", "msg" => "ok QC" );
								array_push($myar, $datar);
								exit('{"status":'.json_encode($myar).'}');
							} else {
								$datar = array("cd" => "0", "msg" => "not ok QC" );
								array_push($myar, $datar);
								exit('{"status":'.json_encode($myar).'}');
							}
						}						
					} elseif($rsactive_wh=="ARPRD1") {
						$currdate	= date('Y-m-d');
						$ret4 = $this->ITH_mod->insert(
							array(
								"ITH_ITMCD" => $colditem,
								"ITH_DATE" => $currdate,
								"ITH_FORM" => "OUT-PRD-FG",
								"ITH_DOC" => $coldjob,
								"ITH_QTY" => -$coldqty,
								"ITH_SER" => $coldreff,
								"ITH_WH" => $rsactive_wh,
								"ITH_LUPDT" => $currrtime,
								"ITH_REMARK" => "WIL-SPLIT",
								"ITH_USRID" => $this->session->userdata('nama')
							)
						);
						if($ret4>0){
							//START PLUS
							$ret5 = 0;
							if($creffka==''){
								$ret5 = $this->ITH_mod->insert(
									array(
										"ITH_ITMCD" => $colditem,
										"ITH_DATE" => $currdate,
										"ITH_FORM" => "INC-QA-FG",
										"ITH_DOC" => $coldjob,
										"ITH_QTY" => $cqty1,
										"ITH_SER" => $creff1,
										"ITH_WH" => 'ARQA1',
										"ITH_LUPDT" => $currrtime,
										"ITH_REMARK" => "AFT-SPLIT",
										"ITH_USRID" => $this->session->userdata('nama')
									)
								);
							} else {
								$ret5 = $this->ITH_mod->insert(
									array(
										"ITH_ITMCD" => $citemcdka.$ctypefg.$cremarkka,
										"ITH_DATE" => $currdate,
										"ITH_FORM" => "INC-QA-FG",
										"ITH_DOC" => $coldjob,
										"ITH_QTY" => $cqtyka,
										"ITH_SER" => $creffka,
										"ITH_WH" => 'ARQA1',
										"ITH_LUPDT" => $currrtime,
										"ITH_REMARK" => "AFT-SPLIT",
										"ITH_USRID" => $this->session->userdata('nama')
									)
								);
							}						
							if($ctypeg=='1'){
								$ret6 = $this->ITH_mod->insert(
									array(
										"ITH_ITMCD" => $colditem,
										"ITH_DATE" => $currdate,
										"ITH_FORM" => "INC-QA-FG",
										"ITH_DOC" => $coldjob,
										"ITH_QTY" => $cqty2,
										"ITH_SER" => $creff2,
										"ITH_WH" => 'ARQA1',
										"ITH_LUPDT" => $currrtime,
										"ITH_REMARK" => "AFT-SPLIT",
										"ITH_USRID" => $this->session->userdata('nama')
									)
								);
							}
							if($ret5>0){
								$datar = array("cd" => "1", "msg" => "ok ." );
								array_push($myar, $datar);
								exit('{"status":'.json_encode($myar).'}');
							} else {
								$datar = array("cd" => "0", "msg" => "not ok QC" );
								array_push($myar, $datar);
								exit('{"status":'.json_encode($myar).'}');
							}
							//END PLUS
						}
					} else {
						$currdate	= date('Y-m-d');

						if($rsactive_wh!=''){ // if 1st parent has been included in tx
							$ret4 = $this->ITH_mod->insert(
								array(
									"ITH_ITMCD" => $colditem,
									"ITH_DATE" => $currdate,
									"ITH_FORM" => 'SPLIT-FG-LBL',
									"ITH_DOC" => $coldjob,
									"ITH_QTY" => -$coldqty,
									"ITH_SER" => $coldreff,
									"ITH_WH" => $rsactive_wh,
									"ITH_LOC" => $rsactive_loc,
									"ITH_LUPDT" => $currrtime,
									"ITH_REMARK" => "WIL-SPLIT",
									"ITH_USRID" => $this->session->userdata('nama')
								)
							);
							if($ret4>0){
								//START PLUS		
								if($citemcdka==''){
									$ret5 = $this->ITH_mod->insert(
										array(
											"ITH_ITMCD" => $colditem,
											"ITH_DATE" => $currdate,
											"ITH_FORM" => 'SPLIT-FG-LBL',
											"ITH_DOC" => $coldjob,
											"ITH_QTY" => $cqty1,
											"ITH_SER" => $creff1,
											"ITH_WH" => $rsactive_wh,
											"ITH_LOC" => $rsactive_loc,
											"ITH_LUPDT" => $currrtime,
											"ITH_REMARK" => "AFT-SPLIT",
											"ITH_USRID" => $this->session->userdata('nama')
										)
									);
								} else {
									$ret5 = $this->ITH_mod->insert(
										array(
											"ITH_ITMCD" => $colditem.$ctypefg.$cremarkka,
											"ITH_DATE" => $currdate,
											"ITH_FORM" => 'SPLIT-FG-LBL',
											"ITH_DOC" => $coldjob,
											"ITH_QTY" => $cqtyka,
											"ITH_SER" => $creffka,
											"ITH_WH" => $rsactive_wh,
											"ITH_LOC" => $rsactive_loc,
											"ITH_LUPDT" => $currrtime,
											"ITH_REMARK" => "AFT-SPLIT",
											"ITH_USRID" => $this->session->userdata('nama')
										)
									);
								}
								if($ctypeg=='1'){
									$ret6 = $this->ITH_mod->insert(
										array(
											"ITH_ITMCD" => $colditem,
											"ITH_DATE" => $currdate,
											"ITH_FORM" => 'SPLIT-FG-LBL',
											"ITH_DOC" => $coldjob,
											"ITH_QTY" => $cqty2,
											"ITH_SER" => $creff2,
											"ITH_WH" => $rsactive_wh,
											"ITH_LOC" => $rsactive_loc,
											"ITH_LUPDT" => $currrtime,
											"ITH_REMARK" => "AFT-SPLIT",
											"ITH_USRID" => $this->session->userdata('nama')
										)
									);
								}
								if($ret5>0){
									$datar = array("cd" => "1", "msg" => "ok .." );
									array_push($myar, $datar);
									exit('{"status":'.json_encode($myar).'}');
								} else {
									$datar = array("cd" => "0", "msg" => "not ok QC" );
									array_push($myar, $datar);
									exit('{"status":'.json_encode($myar).'}');
								}
								//END PLUS
							}
						}
						
					}
					//END MINUS
					
					// $datar = array("cd" => "1", "msg" => "ok" );
					// array_push($myar, $datar);
					// exit('{"status":'.json_encode($myar).'}');					
				} else {
					$datar = array("cd" => "0", "msg" => "Could not update old reff data " );
					array_push($myar, $datar);
					exit('{"status":'.json_encode($myar).'}');
				}			
			}
		}
		
	}

	public function validate_prep(){
		$this->checkSession();
		date_default_timezone_set('Asia/Jakarta');			
		$currrtime	= date('Y-m-d H:i:s');
		$myar = array();
		$cproddt = $this->input->post('inprddt');
		$coldreff = $this->input->post('inoldreff');
		$cjob = $this->input->post('inoldjob');
		$citem = $this->input->post('inolditem');
		$coldqty = $this->input->post('inoldqty');
		$coldsht = $this->input->post('inoldsht');
		$cqty = $this->input->post('innewqty');
		$csheet = $this->input->post('innewsht');
		$cline = $this->input->post('inline');
		$cshift = $this->input->post('inshift');
		$crestqty = $coldqty - $cqty;
		$crestsht = $coldsht - $csheet;
		if($crestsht<0){
			$crestsht = 0;
		}

		$cmdl		= 1;
		$pYear		= substr($cproddt,2,2); $pMonth = substr($cproddt,5,2); $pDay = substr($cproddt, -2);
		$pMonthdis	= $this->getMonthDis($pMonth);		
		$newid = $this->SER_mod->lastserialid($cmdl, $cproddt);
		$newid++;
		$newid = $cmdl.$pYear.$pMonthdis.$pDay.substr('000000000'.$newid, -10);
		if($this->SISCN_mod->check_Primary(array('SISCN_SER' => $coldreff)) >0){
			$datar = array("cd" => '0', "msg" => "could not split delivered item label");			
			array_push($myar, $datar);
			exit('{"status":'.json_encode($myar).'}');
		} else {
			$rsactive = $this->ITH_mod->select_active_ser($coldreff);
			$rsactive_wh =  $rsactive_loc  =''  ;
			foreach($rsactive as $r){
				$rsactive_wh = trim($r['ITH_WH']);
				$rsactive_loc = trim($r['ITH_LOC']);				
			}
			$datas = array(
				'SER_ID' => $newid,
				'SER_REFNO' => $coldreff,
				'SER_DOC' => $cjob,
				'SER_DOCTYPE' => '1',
				'SER_ITMID' => $citem,
				'SER_QTY' => $cqty,
				'SER_QTYLOT' => $cqty,
				'SER_SHEET' => $csheet == '' ? 0 : $csheet,
				'SER_PRDDT' => $cproddt,
				'SER_PRDLINE' => $cline,
				'SER_PRDSHFT' => $cshift,
				'SER_LUPDT' => $currrtime,
				'SER_USRID' => $this->session->userdata('nama')
			);
			if($this->SER_mod->insert($datas)>0){
				$ret3  = $this->SER_mod->updatebyId(array("SER_QTY" => $crestqty,"SER_QTYLOT" => $crestqty, "SER_LUPDT" => $currrtime , "SER_SHEET" => $crestsht), $coldreff);
				if($ret3>0){
					if(count($rsactive) == 0 ){ // handle if serial is not included in tx yet
						$datar = array("cd" => "1", "msg" => "Replaced" , "reffcode" => $newid );
						array_push($myar, $datar);
						exit('{"status":'.json_encode($myar).'}');
					} else { // handle if serial is already included in tx
						//start minus
						$currdate	= date('Y-m-d');
						$ret4 = $this->ITH_mod->insert(
							array(
								"ITH_ITMCD" => $citem,
								"ITH_DATE" => $currdate,
								"ITH_FORM" => 'SPLIT-FG-LBL',
								"ITH_DOC" => $cjob,
								"ITH_QTY" => -$cqty,
								"ITH_SER" => $coldreff,
								"ITH_WH" => $rsactive_wh,
								"ITH_LOC" => $rsactive_loc,
								"ITH_LUPDT" => $currrtime,
								"ITH_REMARK" => "WIL-SPLIT",
								"ITH_USRID" => $this->session->userdata('nama')
							)
						);						
						if($ret4>0){						
							$ret5 = $this->ITH_mod->insert(
								array(
									"ITH_ITMCD" => $citem,
									"ITH_DATE" => $currdate,
									"ITH_FORM" => "SPLIT-FG-LBL",
									"ITH_DOC" => $cjob,
									"ITH_QTY" => $cqty,
									"ITH_SER" => $newid,
									"ITH_WH" => $rsactive_wh,
									"ITH_LOC" => $rsactive_loc,
									"ITH_LUPDT" => $currrtime,
									"ITH_REMARK" => "AFT-SPLIT",
									"ITH_USRID" => $this->session->userdata('nama')
								)
							);
							if($ret5>0){
								$datar = array("cd" => "1", "msg" => "ok WH" , "reffcode" => $newid );
								array_push($myar, $datar);
								exit('{"status":'.json_encode($myar).'}');
							} else {
								$datar = array("cd" => "0", "msg" => "not ok WH" );
								array_push($myar, $datar);
								exit('{"status":'.json_encode($myar).'}');
							}
						} else {
							$datar = array("cd" => '0', "msg" => "could not minus old label, please contact admin");			
							array_push($myar, $datar);
							exit('{"status":'.json_encode($myar).'}');
						}
					}
					
				} else {
					$datar = array("cd" => '0', "msg" => "could not update old label, please contact admin");			
					array_push($myar, $datar);
					exit('{"status":'.json_encode($myar).'}');	
				}
			} else {
				$datar = array("cd" => '0', "msg" => "could not create new lable, please contact admin");			
				array_push($myar, $datar);
				exit('{"status":'.json_encode($myar).'}');
			}
		}
		
	}

	public function validate_newreff_relable(){
		header('Content-Type: application/json');
		$cold_reff = $this->input->get('inoldreff');
		$cold_job = $this->input->get('inoldjob');
		$cold_item = $this->input->get('inolditem');
		$cold_qty = $this->input->get('inoldqty');
		$oldyear = substr($cold_job,0,2);
		$crawtext = $this->input->get('inrawtext');
		$myar = array();
		if(strpos($crawtext, "|") !== false){ /// regular item
			$araw =  explode("|", $crawtext);
			$newreff = substr($araw[5],2,strlen($araw[5])-2);
			$newitem = substr($araw[0],2,strlen($araw[0])-2);
			$newqty = substr($araw[3],2,strlen($araw[3])-2);
			$thelot = substr($araw[2],2,strlen($araw[2])-2);
			$tempjob = substr($thelot,3,5);
			if(substr($tempjob,0,1)=='0'){
				$tempjob = substr($tempjob,1,4);
			}
			$newjob = $oldyear.'-'.$tempjob.'-'.$newitem;
			if($newitem!= $cold_item){
				$datar = array("cd" => "0", "msg" => "New Item is not same with old item, please compare the label !" );
				array_push($myar, $datar);
				exit('{"status":'.json_encode($myar).'}');
			}			
			if($newqty != $cold_qty){
				$datar = array("cd" => "0", "msg" => "qty must be same" );
				array_push($myar, $datar);
				exit('{"status":'.json_encode($myar).'}');
			}

			if($cold_job != $newjob){
				$datar = array("cd" => "0", "msg" => "job is not same, please check the label again, $cold_job  !=  $newjob");
				array_push($myar, $datar);
				exit('{"status":'.json_encode($myar).'}');
			}
			if($cold_reff==$newreff){
				$datar = array("cd" => "0", "msg" => "could not transfer to the same reff no");
				array_push($myar, $datar);
				exit('{"status":'.json_encode($myar).'}');
			}
			if($this->SER_mod->check_Primary(array("SER_ID" => $newreff))>0 ){
				$datar = array("cd" => "0", "msg" => "the reff no is already registered");
				array_push($myar, $datar);
				exit('{"status":'.json_encode($myar).'}');
			} else {

				$datar = array("cd" => "1", "msg" => "go ahead", "rawtext" => $crawtext);				
				array_push($myar, $datar);
				exit('{"status":'.json_encode($myar).'}');
			}
			
		} else { /// HANDLE NON REGULAR
			if($this->SER_mod->check_Primary(array("SER_ID" => $crawtext)) > 0 ){
				$datar = array("cd" => "0", "msg" => "the reff no is already registered" );
				array_push($myar, $datar);
				exit('{"status":'.json_encode($myar).'}');
			} else {
				$datar = array("cd" => "1", "msg" => "go ahead" );
				array_push($myar, $datar);
				exit('{"status":'.json_encode($myar).'}');
			}
		}
	}

	public function validate_relable(){
		date_default_timezone_set('Asia/Jakarta');			
		$currrtime	= date('Y-m-d H:i:s');
		$myar = array();		
		$coldreff = $this->input->post('inoldreff');
		$crawtext = $this->input->post('innewreff');
				
		
		if($this->SISCN_mod->check_Primary(array('SISCN_SER' => $coldreff)) >0){
			$datar = array("cd" => '0', "msg" => "could not split delivered item label");			
			array_push($myar, $datar);
			exit('{"status":'.json_encode($myar).'}');
		} else {
			if(strpos($crawtext, "|") !== false){ /// regular item
				$araw =  explode("|", $crawtext);
				$newreff = substr($araw[5],2,strlen($araw[5])-2);
				//log first
				$this->LOGSER_mod->insert_replace_log(array("LOGSER_KEYS" => $crawtext, "LOGSER_KEYS_RPLC" => $coldreff, "LOGSER_DT" => $currrtime
				, "LOGSER_USRID" =>$this->session->userdata('nama') ));
				//end log

				//update master serial
				$ret3  = $this->SER_mod->updatebyId(array("SER_ID" => $newreff,"SER_RAWTXT" => $crawtext, "SER_LUPDT" => $currrtime), $coldreff);
				//end update
				if($ret3>0){
					//update ith
					$cwhere = array("ITH_SER" => $coldreff);
					$ctoupdate = array("ITH_SER" => $newreff);
					$retith = $this->ITH_mod->updatebyId($cwhere, $ctoupdate);
					//end update
					if($retith>0){
						$datar = array("cd" => '1', "msg" => "ok, replaced..");
						array_push($myar, $datar);
						exit('{"status":'.json_encode($myar).'}');
					} else {
						$datar = array("cd" => '1', "msg" => "ok replaced");
						array_push($myar, $datar);
						exit('{"status":'.json_encode($myar).'}');
					}
				} else {
					$datar = array("cd" => '0', "msg" => "could not update, please contact admin");
					array_push($myar, $datar);
					exit('{"status":'.json_encode($myar).'}');
				}
			} else {
				$datar = array("cd" => '0', "msg" => "Under construction");			
				array_push($myar, $datar);
				exit('{"status":'.json_encode($myar).'}');
			}
		}		
	}

	public function qcunconform(){
		$cbg = $this->input->get('inbg');
		$rs = $this->SER_mod->select_qcunconform($cbg);
		$myar = array();
		if(count($rs)>0){
			$datar = array("cd" => '1', "msg" => "Under construction");			
			array_push($myar, $datar);
		} else {
			$datar = array("cd" => '0', "msg" => "Good, there is no list");
			array_push($myar, $datar);
		}
		echo '{"status": '.json_encode($myar);
		echo ',"data":'.json_encode($rs).'}';
	}

	public function del(){
		$rs= $this->SER_mod->todel();
		//echo json_encode($rs);
		foreach($rs as $k){
			$cid = $k['ITH_SER'];
			$myar = array();		
			if($this->SER_mod->check_Primary(array('SER_ID' => $cid)) > 0 ){
				$ret1 = $this->SER_mod->deletebyID(array('SER_ID' => $cid));
				if($ret1>0){
					if($this->ITH_mod->check_Primary( array('ITH_SER' => $cid)) > 0){
						//bak transaction
						$retbin  = $this->ITH_mod->tobin($this->session->userdata('nama'),$cid );
						///start delete tx	
									
						$ret2 = $this->ITH_mod->deletebyID( array('ITH_SER' => $cid, 'ITH_WH' => 'AFWH3'));
						$ret2 += $this->ITH_mod->deletebyID( array('ITH_SER' => $cid, 'ITH_WH' => 'ARQA1'));
						if($ret2>0){
							$datar = array("cd" => '1', "msg" => "Deleted..");
							array_push($myar, $datar);
						}
					} else {
						$datar = array("cd" => '1', "msg" => "Deleted");
						array_push($myar, $datar);	
					}
				} else {
					$datar = array("cd" => '0', "msg" => "Could not delete the label");
					array_push($myar, $datar);
				}
			} else {
				$datar = array("cd" => '0', "msg" => "the label not found");
				array_push($myar, $datar);
			}
			echo '{"status": '.json_encode($myar).'}';

		}
	}

	public function deletelabel1(){
		$this->checkSession();
		$cid = $this->input->post('inid');
		$myar = array();		
		if($this->SER_mod->check_Primary(array('SER_ID' => $cid)) > 0 ){
			$ret1 = $this->SER_mod->deletebyID(array('SER_ID' => $cid));
			if($ret1>0){
				if($this->ITH_mod->check_Primary( array('ITH_SER' => $cid)) > 0){
					//bak transaction
					$retbin  = $this->ITH_mod->tobin($this->session->userdata('nama'),$cid );
					///start delete tx					
					$ret2 = $this->ITH_mod->deletebyID( array('ITH_SER' => $cid));
					if($ret2>0){
						$datar = array("cd" => '1', "msg" => "Deleted..");
						array_push($myar, $datar);
					}
				} else {
					$datar = array("cd" => '1', "msg" => "Deleted");
					array_push($myar, $datar);	
				}
			} else {
				$datar = array("cd" => '0', "msg" => "Could not delete the label");
				array_push($myar, $datar);
			}
		} else {
			$datar = array("cd" => '0', "msg" => "the label not found");
			array_push($myar, $datar);
		}
		echo '{"status": '.json_encode($myar).'}';
	}

	public function checkSession(){
		$myar =array();
		if ($this->session->userdata('status') != "login")
        {
			$datar = array("cd" => "0", "msg" => "Session is expired please reload page" );
			array_push($myar, $datar);
			exit('{"status":'.json_encode($myar).'}');
        }
	}

	public function combine2_prep(){
		$creff = $this->input->post('inreffcd');
		$myar = array();
		$rs = array();
		$datar = array();
		if($this->SER_mod->check_Primary(array('SER_ID' => $creff))>0 ){
			if($this->SISCN_mod->check_Primary(array('SISCN_SER' => $creff))==0){
				$rsith = $this->ITH_mod->selectstock_ser($creff);
				if(count($rsith)>0){
					foreach($rsith as $r){
						if(trim($r['ITH_WH'])=='AFWH3' && $r['ITH_QTY']>0){
							$rs = $this->SER_mod->select_exact_byVAR(array('SER_ID' => $creff));
							foreach($rs as $r){
								if(trim($r['SER_PRDLINE'])!='' && substr($r['SER_ID'],0,1)=='1' ){ //validate plant 2 label specification

								} else {
									$datar = array("cd" => '0', "msg" => "this menu is only for Plant 2 Label");
								}
							}
						} else {
							$datar = array("cd" => '0', "msg" => "the label must be in AFWH3 first, currently it is in $r[ITH_WH]");
						}
					}
				} else {
					$datar = array("cd" => '0', "msg" => "the label has not been in scanning transaction");
				}
			} else {
				$datar = array("cd" => '0', "msg" => "could not split delivered item label");							
			}
		} else {
			$datar = array("cd" => '0', "msg" => "the label not found");
			
		}
		array_push($myar, $datar);
		echo '{"status":'.json_encode($myar);
		echo ',"data": '.json_encode($rs).'}';
	}

	public function combine2_validate(){
		$cid = $this->input->get('inid');
		$myar = [];
		if($this->SER_mod->check_Primary(['SER_ID' => $cid]) >0 ){
			array_push($myar, ['cd' => 0 , 'msg' => 'The reff no is already registered']);
		} else {
			array_push($myar, ['cd' => 1 , 'msg' => 'go ahead']);
		}
		exit('{"status": '.json_encode($myar).'}');
	}

	public function combine1_prep(){
		$creff = $this->input->post('inreffcd');
		$myar = array();
		$rs = array();
		$datar = array();
		if($this->SER_mod->check_Primary(array('SER_ID' => $creff))>0 ){
			if($this->SISCN_mod->check_Primary(array('SISCN_SER' => $creff))==0){
				$rsith = $this->ITH_mod->selectstock_ser($creff);
				if(count($rsith)>0){
					foreach($rsith as $r){
						if(trim($r['ITH_WH'])=='AFWH3' && $r['ITH_QTY']>0){
							$rs = $this->SER_mod->select_exact_byVAR(array('SER_ID' => $creff));
							foreach($rs as $r){
								if(trim($r['SER_PRDLINE'])=='' ){ //validate plant 2 label specification

								} else {
									$datar = array("cd" => '0', "msg" => "this menu is only for Plant 1 Label");
								}
							}
						} else {
							$datar = array("cd" => '0', "msg" => "the label must be in AFWH3 first, currently it is in $r[ITH_WH]");
						}
					}
				} else {
					$datar = array("cd" => '0', "msg" => "the label has not been in scanning transaction");
				}
			} else {
				$datar = array("cd" => '0', "msg" => "could not split delivered item label");							
			}
		} else {
			$datar = array("cd" => '0', "msg" => "the label not found");
			
		}
		array_push($myar, $datar);
		echo '{"status":'.json_encode($myar);
		echo ',"data": '.json_encode($rs).'}';
	}

	public function combine2_save(){
		$this->checkSession();
		date_default_timezone_set('Asia/Jakarta');			
		$currrtime	= date('Y-m-d H:i:s');
		$cproddt = date('Y-m-d');
		$cmdl	= 1;
		$myar = [];
		$caid= $this->input->post('inid');
		$cajob= $this->input->post('injob');
		$caitemcd= $this->input->post('initemcd');
		$cqty= $this->input->post('inqty');
		$cqtyt= $this->input->post('inqtyt');
		$csheetqtyt= $this->input->post('insheetqty');
		$au_job = array_unique($cajob);
		$cnt_au = count($au_job);
		$cnt_data = count($caid);
		$CUSERID = $this->session->userdata('nama');
		if($cnt_au>1){
			//#1 set old master label qty to zero
			$retupdate = 0;
			///#3.1 get bigest qty
			$tempqtybig = 0;
			$biggestjob = '';
			for($i=0; $i<$cnt_data; $i++){
				$retupdate += $this->SER_mod->updatebyId(['SER_QTY' => 0, 'SER_LUPDT' => $currrtime], $caid[$i]);
				if($cqty[$i] > $tempqtybig) {
					$tempqtybig = $cqty[$i];
					$biggestjob = $cajob[$i];
				}
			}
			$aa_job =explode('-', $biggestjob);
			$biggestjob = $aa_job[0]."-C".$aa_job[1]."-".$aa_job[2];
			if($retupdate ==$cnt_data ){
				//#2 minus in transaction
				$retminus = 0;
				for($i=0; $i<$cnt_data; $i++){
					$rsactive = $this->ITH_mod->select_active_ser($caid[$i]);
					$rsactive_wh =  $rsactive_loc  =''  ;
					foreach($rsactive as $r){
						$rsactive_wh = trim($r['ITH_WH']);
						$rsactive_loc = trim($r['ITH_LOC']);				
					}
					$retminus += $this->ITH_mod->insert(
						array(
							"ITH_ITMCD" => $caitemcd[0],
							"ITH_DATE" => $cproddt,
							"ITH_FORM" => 'JOIN_OUT',
							"ITH_DOC" => $au_job[0],
							"ITH_QTY" => -$cqty[$i],
							"ITH_SER" => $caid[$i],
							"ITH_WH" => $rsactive_wh,
							"ITH_LOC" => $rsactive_loc,
							"ITH_LUPDT" => $currrtime,
							"ITH_REMARK" => "WIL-JOIN",
							"ITH_USRID" => $this->session->userdata('nama')
						)
					);
				}
				//#3 create new label
				if($retminus==$cnt_data){
					$csheet = $csheetqtyt;
					$cline = '';
					$cshift = '';
					$rsinfo = $this->SER_mod->select_exact_byVAR(['SER_ID' => $caid[0]]);
					foreach($rsinfo as $r){
						$cline = $r['SER_PRDLINE'];
						$cshift = $r['SER_PRDSHFT'];
					}
										
					$pYear		= substr($cproddt,2,2); $pMonth = substr($cproddt,5,2); $pDay = substr($cproddt, -2);
					$pMonthdis	= $this->getMonthDis($pMonth);		
					$newid = $this->SER_mod->lastserialid($cmdl, $cproddt);
					$newid++;
					$newid = $cmdl.$pYear.$pMonthdis.$pDay.substr('000000000'.$newid, -10);
					$datas = array(
						'SER_ID' => $newid,
						'SER_REFNO' => 'JOIN_P2',
						'SER_DOC' => $biggestjob,
						'SER_DOCTYPE' => '1',
						'SER_ITMID' => $caitemcd[0],
						'SER_QTY' => $cqtyt,
						'SER_SHEET' => $csheet == '' ? 0 : $csheet,
						'SER_PRDDT' => $cproddt,
						'SER_PRDLINE' => $cline,
						'SER_PRDSHFT' => $cshift,
						'SER_LUPDT' => $currrtime,
						'SER_USRID' => $this->session->userdata('nama')
					);
					$retregis  = $this->SER_mod->insert($datas);
					if($retregis >0 ){
						//#4 register to map
						$retregistermap = 0;
						for($i=0; $i<$cnt_data; $i++){
							if($this->SERC_mod->check_Primary(['SERC_NEWID' => $newid, 'SERC_COMID' => $caid[$i]] ) ==0){
								$retregistermap += $this->SERC_mod->insert(
									['SERC_NEWID' => $newid
									,'SERC_COMID' => $caid[$i]
									,'SERC_COMJOB' => $cajob[$i]
									,'SERC_COMQTY' => $cqty[$i]
									,'SERC_USRID' => $CUSERID
									,'SERC_LUPDT' => $currrtime]);
							} 									
						}
						if($retregistermap==$cnt_data){
							//#5 plus	
							$retplus = $this->ITH_mod->insert(
								array(
									"ITH_ITMCD" => $caitemcd[0],
									"ITH_DATE" => $cproddt,
									"ITH_FORM" => 'JOIN_IN',
									"ITH_DOC" => $biggestjob,
									"ITH_QTY" => $cqtyt,
									"ITH_SER" => $newid,
									"ITH_WH" => $rsactive_wh,
									"ITH_LOC" => $rsactive_loc,
									"ITH_LUPDT" => $currrtime,
									"ITH_REMARK" => "JOINT",
									"ITH_USRID" => $this->session->userdata('nama')
								)
							);
							if($retplus>0){
								array_push($myar, ['cd' => '1', 'msg' => 'Different Job successfully joined', 'nreffcode' => $newid]);
							} else {
								array_push($myar, ['cd' => '0', 'msg' => 'could not add tranasaction for new label [DF]']);
							}
						} else {
							array_push($myar, ['cd' => '0', 'msg' => 'not all labels registered into mapping job [DF]']);
						}
					} else {
						array_push($myar, ['cd' => '0', 'msg' => 'could not register new label [DF]']);
					}
				} else {
					array_push($myar, ['cd' => '0', 'msg' => 'Not all old labels are out in transaction, please contact admin [DF]']);
				}				
			} else {
				array_push($myar, ['cd' => '0', 'msg' => 'Not all old labels are updated, please contact admin [DF]']);
			}
		} else {
			//#1 set old master label qty to zero
			$retupdate = 0;
			for($i=0; $i<$cnt_data; $i++){
				$retupdate += $this->SER_mod->updatebyId(['SER_QTY' => 0, 'SER_LUPDT' => $currrtime], $caid[$i]);
			}
			if($retupdate ==$cnt_data ){
				//#2 minus in transaction
				$retminus = 0;
				for($i=0; $i<$cnt_data; $i++){
					$rsactive = $this->ITH_mod->select_active_ser($caid[$i]);
					$rsactive_wh =  $rsactive_loc  =''  ;
					foreach($rsactive as $r){
						$rsactive_wh = trim($r['ITH_WH']);
						$rsactive_loc = trim($r['ITH_LOC']);				
					}
					$retminus += $this->ITH_mod->insert(
						array(
							"ITH_ITMCD" => $caitemcd[0],
							"ITH_DATE" => $cproddt,
							"ITH_FORM" => 'JOIN_OUT',
							"ITH_DOC" => $au_job[0],
							"ITH_QTY" => -$cqty[$i],
							"ITH_SER" => $caid[$i],
							"ITH_WH" => $rsactive_wh,
							"ITH_LOC" => $rsactive_loc,
							"ITH_LUPDT" => $currrtime,
							"ITH_REMARK" => "WIL-JOIN",
							"ITH_USRID" => $this->session->userdata('nama')
						)
					);
				}
				//#3 create new label
				if($retminus==$cnt_data){
					$csheet = $csheetqtyt;
					$cline = '';
					$cshift = '';
					$rsinfo = $this->SER_mod->select_exact_byVAR(['SER_ID' => $caid[0]]);
					foreach($rsinfo as $r){
						$cline = $r['SER_PRDLINE'];
						$cshift = $r['SER_PRDSHFT'];
					}
					$pYear		= substr($cproddt,2,2); $pMonth = substr($cproddt,5,2); $pDay = substr($cproddt, -2);
					$pMonthdis	= $this->getMonthDis($pMonth);		
					$newid = $this->SER_mod->lastserialid($cmdl, $cproddt);
					$newid++;
					$newid = $cmdl.$pYear.$pMonthdis.$pDay.substr('000000000'.$newid, -10);
					$datas = array(
						'SER_ID' => $newid,
						'SER_REFNO' => 'JOIN_P2',
						'SER_DOC' => $au_job[0],
						'SER_DOCTYPE' => '1',
						'SER_ITMID' => $caitemcd[0],
						'SER_QTY' => $cqtyt,
						'SER_SHEET' => $csheet == '' ? 0 : $csheet,
						'SER_PRDDT' => $cproddt,
						'SER_PRDLINE' => $cline,
						'SER_PRDSHFT' => $cshift,
						'SER_LUPDT' => $currrtime,
						'SER_USRID' => $this->session->userdata('nama')
					);
					$retregis  = $this->SER_mod->insert($datas);
					if($retregis >0 ){
						//#4 register to map
						$retregistermap = 0;
						for($i=0; $i<$cnt_data; $i++){
							if($this->SERC_mod->check_Primary(['SERC_NEWID' => $newid, 'SERC_COMID' => $caid[$i]] ) ==0){
								$retregistermap += $this->SERC_mod->insert(
									['SERC_NEWID' => $newid
									,'SERC_COMID' => $caid[$i]
									,'SERC_COMJOB' => $cajob[$i]
									,'SERC_COMQTY' => $cqty[$i]
									,'SERC_USRID' => $CUSERID
									,'SERC_LUPDT' => $currrtime]);
							} 							
						}
						
						if($retregistermap==$cnt_data){
							//#5 plus							
							$retplus = $this->ITH_mod->insert(
								array(
									"ITH_ITMCD" => $caitemcd[0],
									"ITH_DATE" => $cproddt,
									"ITH_FORM" => 'JOIN_IN',
									"ITH_DOC" => $au_job[0],
									"ITH_QTY" => $cqtyt,
									"ITH_SER" => $newid,
									"ITH_WH" => $rsactive_wh,
									"ITH_LOC" => $rsactive_loc,
									"ITH_LUPDT" => $currrtime,
									"ITH_REMARK" => "JOINT",
									"ITH_USRID" => $this->session->userdata('nama')
								)
							);
							if($retplus>0){
								array_push($myar, ['cd' => '1', 'msg' => 'Same Job successfully joined', 'nreffcode' => $newid]);
							} else {
								array_push($myar, ['cd' => '0', 'msg' => 'could not add tranasaction for new label']);
							}
						} else {
							array_push($myar, ['cd' => '0', 'msg' => 'not all labels registered into mapping job']);
						}
					} else {
						array_push($myar, ['cd' => '0', 'msg' => 'could not register new label']);
					}
				} else {
					array_push($myar, ['cd' => '0', 'msg' => 'Not all old labels are out in transaction, please contact admin']);
				}
			} else {
				array_push($myar, ['cd' => '0', 'msg' => 'Not all old labels are updated, please contact admin']);
			}
			
		}
		echo '{"status":'.json_encode($myar).'}';
	}
	public function combine1_save(){
		$this->checkSession();
		date_default_timezone_set('Asia/Jakarta');
		$currrtime	= date('Y-m-d H:i:s');
		$cproddt = date('Y-m-d');
		
		$myar = [];
		$caid= $this->input->post('inid');
		$cajob= $this->input->post('injob');
		$caitemcd= $this->input->post('initemcd');
		$cqty= $this->input->post('inqty');
		$cqtyt= $this->input->post('inqtyt');
		$cnewjob= $this->input->post('innewjob');
		$cnewrawtext= $this->input->post('innewrawtext');
		$cnewid= $this->input->post('innewid');
		$astr = explode("|",$cnewrawtext);
		$clot = $astr[2];
		$clot = substr($clot,2,strlen($clot));
		$cnt_data = count($caid);
		$CUSERID = $this->session->userdata('nama');
		
		//#1 set old master label qty to zero
		$retupdate = 0;
		for($i=0; $i<$cnt_data; $i++){
			$retupdate += $this->SER_mod->updatebyId(['SER_QTY' => 0, 'SER_LUPDT' => $currrtime], $caid[$i]);
		}
			if($retupdate ==$cnt_data ){
				//#2 minus in transaction
				$retminus = 0;
				for($i=0; $i<$cnt_data; $i++){
					$rsactive = $this->ITH_mod->select_active_ser($caid[$i]);
					$rsactive_wh =  $rsactive_loc  =''  ;
					foreach($rsactive as $r){
						$rsactive_wh = trim($r['ITH_WH']);
						$rsactive_loc = trim($r['ITH_LOC']);				
					}
					$retminus += $this->ITH_mod->insert(
						array(
							"ITH_ITMCD" => $caitemcd[0],
							"ITH_DATE" => $cproddt,
							"ITH_FORM" => 'JOIN_OUT',
							"ITH_DOC" => $cajob[$i],
							"ITH_QTY" => -$cqty[$i],
							"ITH_SER" => $caid[$i],
							"ITH_WH" => $rsactive_wh,
							"ITH_LOC" => $rsactive_loc,
							"ITH_LUPDT" => $currrtime,
							"ITH_REMARK" => "WIL-JOIN",
							"ITH_USRID" => $this->session->userdata('nama')
						)
					);
				}
				//#3 create new label
				if($retminus==$cnt_data){										
					$datas = array(
						'SER_ID' => $cnewid,
						'SER_REFNO' => 'JOIN_P2',
						'SER_DOC' => $cnewjob,
						'SER_DOCTYPE' => '1',
						'SER_ITMID' => $caitemcd[0],
						'SER_QTY' => $cqtyt,		
						"SER_LOTNO" => $clot,										
						'SER_RAWTXT' => $cnewrawtext,						
						'SER_LUPDT' => $currrtime,
						'SER_USRID' => $CUSERID
					);
					$retregis  = $this->SER_mod->insert($datas);
					if($retregis >0 ){
						//#4 register to map
						$retregistermap = 0;
						for($i=0; $i<$cnt_data; $i++){
							if($this->SERC_mod->check_Primary(['SERC_NEWID' => $cnewid, 'SERC_COMID' => $caid[$i]] ) ==0){
								$retregistermap += $this->SERC_mod->insert(
									['SERC_NEWID' => $cnewid
									,'SERC_COMID' => $caid[$i]
									,'SERC_COMJOB' => $cajob[$i]
									,'SERC_COMQTY' => $cqty[$i]
									,'SERC_USRID' => $CUSERID
									,'SERC_LUPDT' => $currrtime]);
							} 							
						}
						
						if($retregistermap==$cnt_data){
							//#5 plus							
							$retplus = $this->ITH_mod->insert(
								array(
									"ITH_ITMCD" => $caitemcd[0],
									"ITH_DATE" => $cproddt,
									"ITH_FORM" => 'JOIN_IN',
									"ITH_DOC" => $cnewjob,
									"ITH_QTY" => $cqtyt,
									"ITH_SER" => $cnewid,
									"ITH_WH" => $rsactive_wh,
									"ITH_LOC" => $rsactive_loc,
									"ITH_LUPDT" => $currrtime,
									"ITH_REMARK" => "JOINT",
									"ITH_USRID" => $this->session->userdata('nama')
								)
							);
							if($retplus>0){
								array_push($myar, ['cd' => '1', 'msg' => 'Plant 1 successfully joined']);
							} else {
								array_push($myar, ['cd' => '0', 'msg' => 'could not add tranasaction for new label']);
							}
						} else {
							array_push($myar, ['cd' => '0', 'msg' => 'not all labels registered into mapping job']);
						}
					} else {
						array_push($myar, ['cd' => '0', 'msg' => 'could not register new label']);
					}
				} else {
					array_push($myar, ['cd' => '0', 'msg' => 'Not all old labels are out in transaction, please contact admin']);
				}
			} else {
				array_push($myar, ['cd' => '0', 'msg' => 'Not all old labels are updated, please contact admin']);
			}					
		echo '{"status":'.json_encode($myar).'}';
	}

	public function combined2_list(){
		$csearch = $this->input->get('insearch');
		$csearch_by = $this->input->get('insearch_by');
		$myar = $rs = [];
		if($csearch_by=='1'){
			$rs = $this->SERC_mod->select_byVAR(['SERC_NEWID' => $csearch]);			
		} else {
			$rs = $this->SERC_mod->select_byVAR(['SERC_COMID' => $csearch]);
		}
		if(count($rs)>0){
			array_push($myar, ['cd' => 1, 'msg' => "go ahead"]);
		} else {
			array_push($myar, ['cd' => 0, 'msg' => "data is not found"]);
		}
		echo '{"status":'.json_encode($myar)
			.',"data": '.json_encode($rs)
			.'}';
	}

	public function convert_validate_newlabel(){
		$crawtext = $this->input->get('inrawtext');
		$cremark = $this->input->get('inremark');
		$cnewitem = $this->input->get('innewitem');
		$myar = [];
		if(strpos($crawtext, "|") !== false){ /// regular item
			$araw =  explode("|", $crawtext);
			$newreff = substr($araw[5],2,strlen($araw[5])-2);
			$theitem = substr($araw[0],2,strlen($araw[0])-2);
			$theitem.=$cremark;
			if($this->MSTITM_mod->check_Primary(['MITM_ITMCD' => $theitem])>0 ){
				if($this->SER_mod->check_Primary(array("SER_ID" => $newreff))>0 ){
					$datar = array("cd" => "0", "msg" => "the reff no is already registered");
					array_push($myar, $datar);
					exit('{"status":'.json_encode($myar).'}');
				} else {
					$datar = array("cd" => "1", "msg" => "go ahead", "rawtext" => $crawtext);				
					array_push($myar, $datar);
					exit('{"status":'.json_encode($myar).'}');
				}
			} else {
				$datar = array("cd" => "0", "msg" => "the item is not registered {$theitem}");
				array_push($myar, $datar);
				exit('{"status":'.json_encode($myar).'}');
			}
		} else {
			$datar = array("cd" => "0", "msg" => "Reff No is not valid..." );
			array_push($myar, $datar);
			exit('{"status":'.json_encode($myar).'}');
		}
	}

	public function convert_prep(){
		header('Content-Type: application/json');
		$cold_reff = $this->input->get('inid');
		$rsold_reff = $this->SER_mod->select_exact_byVAR(array('SER_ID' => $cold_reff));
		$rxtx = $this->ITH_mod->selectAll_by(array('ITH_SER' => $cold_reff));
		$myar = array();
		if(count($rsold_reff)>0){			
			if($this->SISCN_mod->check_Primary(array('SISCN_SER' => $cold_reff)) >0){
				$datar = array("cd" => '0', "msg" => "could not split delivered item label");
			} else {				
				$rsith = $this->ITH_mod->selectstock_ser($cold_reff);
				if(count($rsith)>0){
					foreach($rsith as $r){
						if(trim($r['ITH_WH'])=='AFWH3' && $r['ITH_QTY']>0){
							$datar = array("cd" => '1', "msg" => "go ahead");
						} else {
							$datar = array("cd" => '0', "msg" => "the label must be in AFWH3 first, currently it is in $r[ITH_WH]");
						}
					}
				} else {
					$datar = array("cd" => '0', "msg" => "the label has not been in scanning transaction or it is already converted");
				}
			}
		} else {
			$datar = array("cd" => '0', "msg" => "Reff No is not found");
		}
		array_push($myar, $datar);
		echo '{"data":';
		echo json_encode($rsold_reff);
		echo ',"tx":'.json_encode($rxtx);
		echo ',"status":'.json_encode($myar);
		echo '}';
	}

	public function convertlabel_save(){
		$this->checkSession();
		date_default_timezone_set('Asia/Jakarta');			
		$currrtime	= date('Y-m-d H:i:s');
		$currdate	= date('Y-m-d');
		$myar = [];
		//OLD_dataset
		$coldreff = $this->input->post('inoldreff');
		$coldjob = $this->input->post('inoldjob');
		$colditem = $this->input->post('inolditemcd');
		$coldqty = $this->input->post('inoldqty');
		//OLD_dataset_END

		//NEW_dataset
		$crg_reff = $this->input->post('inrg_reff');
		$crg_itmcd = $this->input->post('inrg_itemcd');
		$crg_remark = $this->input->post('inrg_remark');
		$crg_raw = $this->input->post('inrg_rawtxt');
		$crg_lot = '';

		$cka_itmcd = $this->input->post('inka_itmcd');
		$cka_lotno = $this->input->post('inka_lotno');		
		$cka_reffno = $this->input->post('inka_reff');
		$cka_remark = strtoupper($this->input->post('inka_remark'));

		$cfg_type = $this->input->post('infgtype');
		

		if($this->SER_mod->check_Primary(['SER_ID' => $coldreff])==0 ){
			$datar = array("cd" => '0', "msg" => "Old Reff No is not found");
			array_push($myar, $datar);
			exit('{"status":'.json_encode($myar).'}');
		}

		

		if(strpos($crg_raw, "|") !== false){
			$araw =  explode("|", $crg_raw);			
			$crg_lot = substr($araw[2],2,strlen($araw[2])-2);
		}
		$CUSERID = $this->session->userdata('nama');
		

		if($this->SISCN_mod->check_Primary(array('SISCN_SER' => $coldreff)) >0){
			$datar = array("cd" => '0', "msg" => "could not split delivered item label");			
			array_push($myar, $datar);
			exit('{"status":'.json_encode($myar).'}');
		} else { 
			if($cfg_type==''){//handle Regular
				$araw =  explode("|", $crg_raw);
				$theitem = substr($araw[0],2,strlen($araw[0])-2);
				$theitem.=$crg_remark;
				if($this->MSTITM_mod->check_Primary(['MITM_ITMCD' => $theitem])==0 ){
					$datar = array("cd" => '0', "msg" => "Item Code is not registered {$theitem}");
					array_push($myar, $datar);
					exit('{"status":'.json_encode($myar).'}');
				}
				if($this->SER_mod->check_Primary(['SER_ID' => $crg_reff])>0 ){
					$datar = array("cd" => '0', "msg" => "could not convert old label to registered reff number");
					array_push($myar, $datar);
					exit('{"status":'.json_encode($myar).'}');
				}
			} else {
				if($this->MSTITM_mod->check_Primary(['MITM_ITMCD' => $cka_itmcd.$cfg_type.$cka_remark]) == 0){
					$datar = array("cd" => '0', "msg" => "Item Code is not registered");
					array_push($myar, $datar);
					exit('{"status":'.json_encode($myar).'}');
				}
				if($this->SER_mod->check_Primary(['SER_ID' => $cka_reffno])>0 ){
					$datar = array("cd" => '0', "msg" => "could not convert old label to registered reff number");
					array_push($myar, $datar);
					exit('{"status":'.json_encode($myar).'}');
				}
			}
			

			$rsactive = $this->ITH_mod->select_active_ser($coldreff);
			$rsactive_wh =  $rsactive_loc  =''  ;
			foreach($rsactive as $r){
				$rsactive_wh = trim($r['ITH_WH']);
				$rsactive_loc = trim($r['ITH_LOC']);				
			}
			if($cfg_type=='KD' || $cfg_type=='ASP' || $cfg_type=='KDASP' ){ // TO NON REGULAR
				if($this->SER_mod->updatebyId(['SER_QTY' => 0, 'SER_QTYLOT' => 0, 'SER_LUPDT' => $currrtime, 'SER_USRID' => $CUSERID], $coldreff) > 0){
					//create new serial
									
					$retser = $this->SER_mod->insert([
						"SER_ID" => $cka_reffno,
						"SER_DOC" => $coldjob,
						"SER_ITMID" => $cka_itmcd.$cfg_type.$cka_remark,
						"SER_QTY" => $coldqty,
						"SER_QTYLOT" => $coldqty,
						"SER_LOTNO" => $cka_lotno,
						"SER_REFNO" => $coldreff,					
						"SER_LUPDT" => $currrtime,
						"SER_USRID" => $this->session->userdata('nama')
					]);
					if($retser>0){
						//minus
						$retmin = $this->ITH_mod->insert([
							"ITH_ITMCD" => $colditem,
							"ITH_DATE" => $currdate,
							"ITH_FORM" => 'CONVERT-OUT',
							"ITH_DOC" => $coldjob,
							"ITH_QTY" => -$coldqty,
							"ITH_SER" => $coldreff,
							"ITH_WH" => $rsactive_wh,
							"ITH_LOC" => $rsactive_loc,
							"ITH_LUPDT" => $currrtime,
							"ITH_REMARK" => "CONVERT",
							"ITH_USRID" => $CUSERID
						]);
						//end minus
						if($retmin >0 ){
							$retplus = $this->ITH_mod->insert([
								"ITH_ITMCD" =>  $cka_itmcd.$cfg_type.$cka_remark,
								"ITH_DATE" => $currdate,
								"ITH_FORM" => 'CONVERT-IN',
								"ITH_DOC" => $coldjob,
								"ITH_QTY" => $coldqty,
								"ITH_SER" => $cka_reffno,
								"ITH_WH" => $rsactive_wh,
								"ITH_LOC" => $rsactive_loc,
								"ITH_LUPDT" => $currrtime,
								"ITH_REMARK" => "CONVERT",
								"ITH_USRID" => $CUSERID
							]);
							if($retplus>0){
								$datar = array("cd" => '1', "msg" => "OK");
								array_push($myar, $datar);
								exit('{"status":'.json_encode($myar).'}');
							} else {
								$datar = array("cd" => '0', "msg" => "could not plus qty in transaction [REGULAR]");
								array_push($myar, $datar);
								exit('{"status":'.json_encode($myar).'}');
							}
						} else {
							$datar = array("cd" => '0', "msg" => "could not minus old qty in transaction [REGULAR]");
							array_push($myar, $datar);
							exit('{"status":'.json_encode($myar).'}');
						}
					} else {
						$datar = array("cd" => '0', "msg" => "could not register the reff no [REGULAR]");
						array_push($myar, $datar);
						exit('{"status":'.json_encode($myar).'}');
					}
				} else {
					$datar = array("cd" => '0', "msg" => "could not update old label [REGULAR]");
					array_push($myar, $datar);
					exit('{"status":'.json_encode($myar).'}');
				}				
			} else { //to REGULAR
				if($this->SER_mod->updatebyId(['SER_QTY' => 0, 'SER_QTYLOT' => 0, 'SER_LUPDT' => $currrtime, 'SER_USRID' => $CUSERID], $coldreff) > 0){
					//reg new REFF
					$retser = $this->SER_mod->insert(array(
						"SER_ID" => $crg_reff,
						"SER_DOC" => $coldjob,
						"SER_ITMID" => $crg_itmcd,
						"SER_QTY" => $coldqty,
						"SER_QTYLOT" => $coldqty,
						"SER_LOTNO" => $crg_lot,
						"SER_REFNO" => $coldreff,
						"SER_RAWTXT" => $crg_raw,						
						"SER_LUPDT" => $currrtime,
						"SER_USRID" => $CUSERID
					));
					//end reg
					if($retser>0){
						//minus
						$retmin = $this->ITH_mod->insert([
							"ITH_ITMCD" => $colditem,
							"ITH_DATE" => $currdate,
							"ITH_FORM" => 'CONVERT-OUT',
							"ITH_DOC" => $coldjob,
							"ITH_QTY" => -$coldqty,
							"ITH_SER" => $coldreff,
							"ITH_WH" => $rsactive_wh,
							"ITH_LOC" => $rsactive_loc,
							"ITH_LUPDT" => $currrtime,
							"ITH_REMARK" => "CONVERT",
							"ITH_USRID" => $CUSERID
						]);
						//end minus
						if($retmin >0 ){
							$retplus = $this->ITH_mod->insert([
								"ITH_ITMCD" => $crg_itmcd,
								"ITH_DATE" => $currdate,
								"ITH_FORM" => 'CONVERT-IN',
								"ITH_DOC" => $coldjob,
								"ITH_QTY" => $coldqty,
								"ITH_SER" => $crg_reff,
								"ITH_WH" => $rsactive_wh,
								"ITH_LOC" => $rsactive_loc,
								"ITH_LUPDT" => $currrtime,
								"ITH_REMARK" => "CONVERT",
								"ITH_USRID" => $CUSERID
							]);
							if($retplus>0){
								$datar = array("cd" => '1', "msg" => "OK");
								array_push($myar, $datar);
								exit('{"status":'.json_encode($myar).'}');
							} else {
								$datar = array("cd" => '0', "msg" => "could not plus qty in transaction [REGULAR]");
								array_push($myar, $datar);
								exit('{"status":'.json_encode($myar).'}');
							}
						} else {
							$datar = array("cd" => '0', "msg" => "could not minus old qty in transaction [REGULAR]");
							array_push($myar, $datar);
							exit('{"status":'.json_encode($myar).'}');
						}
					} else {
						$datar = array("cd" => '0', "msg" => "could not register the reff no [REGULAR]");
						array_push($myar, $datar);
						exit('{"status":'.json_encode($myar).'}');
					}
				} else {
					$datar = array("cd" => '0', "msg" => "could not update old label [REGULAR]");
					array_push($myar, $datar);
					exit('{"status":'.json_encode($myar).'}');
				}
			}
		}
	}
	public function qccancelscan(){
		$this->checkSession();
		$cid = $this->input->post('inreff');
		$myar = [];
		$rsith = $this->ITH_mod->selectstock_ser($cid);
		if(count($rsith)>0){			
			$retbin  = $this->ITH_mod->tobin_info('CANCELQC',$this->session->userdata('nama'),$cid );
			$isok =false;
			foreach($rsith as $r){
				if(trim($r['ITH_WH'])=='ARQA1' && $r['ITH_QTY']>0 && trim($r['ITH_FORM']) =='INC-QA-FG'){
					$isok = true;break;
				}
			}
			if($isok){
				$retdelith  = $this->ITH_mod->deletebyID(['ITH_SER' => $cid, 'ITH_WH' => 'ARQA1']);
				if($retdelith>0){
					array_push($myar, ['cd' => '1' , 'msg' => 'Canceled']);
				} else {
					array_push($myar, ['cd' => '0' , 'msg' => 'Could not cancel scan QC..']);	
				}
			} else {
				array_push($myar, ['cd' => '0' , 'msg' => 'Could not cancel scan QC.']);
			}
		} else {
			array_push($myar, ['cd' => '0' , 'msg' => 'Could not cancel scan QC']);
		}
		echo '{"status":'.json_encode($myar);
		echo '}';
	}
}
