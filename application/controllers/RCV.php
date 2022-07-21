<?php
defined('BASEPATH') OR exit('No direct script access allowed');

use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;


class RCV extends CI_Controller {
	public function __construct()
	{
		parent::__construct();
		$this->load->helper('url');
		$this->load->helper('download');
		$this->load->library('session');
		$this->load->library('Code39e128');
		$this->load->model('RCVSTXI_mod');
		$this->load->model('RCV_mod');
		$this->load->model('RCVPKG_mod');
		$this->load->model('RCVNI_mod');
		$this->load->model('RCVSCN_mod');
		$this->load->model('RCVMEGA_mod');
		$this->load->model('ITMLOC_mod');
		$this->load->model('MSTSUP_mod');
		$this->load->model('MSTCUS_mod');
		$this->load->model('XBGROUP_mod');
		$this->load->model('BGROUP_mod');
		$this->load->model('ITH_mod');
		$this->load->model('FIFORM_mod');
		$this->load->model('XSO_mod');
		$this->load->model('XPGRN_mod');
		$this->load->model('SI_mod');
		$this->load->model('DELV_mod');
		$this->load->model('STKTRN_mod');
		$this->load->model('RETFG_mod');
		$this->load->model('MSTLOCG_mod');
		$this->load->model('SER_mod');
		$this->load->model('SERRC_mod');
		$this->load->model('PPO_mod');
		$this->load->model('MMDL_mod');
		$this->load->model('refceisa/REFERENSI_KEMASAN_imod');
		$this->load->model('refceisa/MTPB_mod');				
		$this->load->model('refceisa/ZOffice_mod');
		$this->load->model('refceisa/MPurposeDLV_mod');
		$this->load->model('refceisa/AKTIVASIAPLIKASI_imod');
		$this->load->model('refceisa/TPB_HEADER_imod');
		$this->load->model('refceisa/TPB_DOKUMEN_imod');
		$this->load->model('refceisa/TPB_BARANG_imod');
		$this->load->model('refceisa/TPB_KEMASAN_imod');
		$this->load->model('refceisa/TPB_RESPON_imod');
	}
	public function index()
	{
		echo "sorry";
	}

	public function create(){		
		$this->load->view('wms/vrcv');
	}
	public function form_nonitem(){
		$this->load->view('wms/vrcv_nonitem');
	}
	public function create_fg(){		
		$this->load->view('wms/vfg_ret_inc');
	}
	public function vtracelot(){		
		$this->load->view('wms_report/vtracelot_inc');
	}

	public function vr_scandis(){
		$this->load->view('wms_report/vrpt_rminc_scandis');
	}

	public function form_megatemplate() {
		$data['lbg'] = $this->PPO_mod->select_os_business();
		$this->load->view('wms/vrm_mega_template', $data);
	}

	public function tracelot(){
		$myar = [];
		$cdo = $this->input->get('indo');
		$citemcd = $this->input->get('initmcd');
		$citemlot = $this->input->get('initmlot');
		$rs = $this->RCVSCN_mod->selectby_filter_like(['RCVSCN_DONO' => $cdo, 'RCVSCN_ITMCD' => $citemcd, "isnull(RCVSCN_LOTNO,'')" => $citemlot]);		
		$myar[] = count($rs) >0 ? ['cd' => 1, 'msg' => 'go ahead'] : ['cd' => 0, 'msg' => 'not found'];		
		exit('{"status" : '.json_encode($myar).', "data" : '.json_encode($rs).'}');
	}

	public function zgetsts_rcv(){
		$cid = $this->input->get('inid');
		$csts = $this->input->get('instst');
		$myar = [];
		$rs = $this->MPurposeDLV_mod->selectbyvar(['KODE_DOKUMEN' => $cid]);		
		$myar[] = count($rs) > 0 ? ['cd' => 1, 'msg' => "Go ahead", 'reff' => $csts] : ['cd' => 0, 'msg' => "Data Not found", 'reff' => $csts];		
		echo '{"status": '.json_encode($myar).', "data": '.json_encode($rs).'}';
	}

	public function customs(){
		$data['ltpb_type'] = $this->MTPB_mod->selectAll();
		$data['officelist'] = $this->ZOffice_mod->selectAll();
		$rsaktivasi = $this->AKTIVASIAPLIKASI_imod->selectAll();
		$rsbg = $this->BGROUP_mod->selectall();		
		$rssupplier = $this->MSTSUP_mod->selectAll();
		$rsReferensiKemasan = $this->REFERENSI_KEMASAN_imod->selectAll();

		$supdis = '<option value="-">ALL</option>';
		$bgdis = $kemasan = '';
		foreach($rssupplier as $r){
			$supdis .= '<option value="'.trim($r->MSUP_SUPCD).'">['.trim($r->MSUP_SUPCR).'] '.$r->MSUP_SUPNM.'</option>';
		}
		foreach($rsbg as $r){
			$bgdis .= '<option value="'.trim($r->MBSG_BSGRP).'">'.$r->MBSG_DESC.'</option>';
		}
		foreach($rsReferensiKemasan as $r){
			$kemasan .= '<option value="'.$r->KODE_KEMASAN.'">'.$r->URAIAN_KEMASAN.'</option>';
		}
		foreach($rsaktivasi as $r){
			$data['KPPBC'] = $r['KPPBC'];
			$data['ID_MODUL'] = substr('00000'.$r['ID_MODUL'],-6);
		}
		$data['lbg'] = $bgdis;
		$data['lsupplier'] = $supdis;
		$data['lkemasan'] = $kemasan;
		$data['sapaDia'] = $this->session->userdata('sfname');
		
		$rslocfrom = $this->MSTLOCG_mod->selectall_where_CODE_in(['ARWH1','ARWH2','NRWH2']);
		$rslocfrom_str = '';
		foreach($rslocfrom as $r){
			$rslocfrom_str .= "<option value='".$r['MSTLOCG_ID']."'>".$r['MSTLOCG_NM']." (".$r['MSTLOCG_ID'].")</option>";
		}
		$data['llocto'] = $rslocfrom_str;
		$this->load->view('wms/vrcv_customs', $data);
	}

	public function customs_fg(){
		$data['ltpb_type'] = $this->MTPB_mod->selectAll();
		$data['officelist'] = $this->ZOffice_mod->selectAll();
		$rssupplier = $this->XBGROUP_mod->selectall();
		$supdis = '<option value="-">ALL</option>';
		foreach($rssupplier as $r){
			$supdis .= '<option value="'.trim($r->MBSG_BSGRP).'">'.$r->MBSG_DESC.'</option>';
		}
		$data['lsupplier'] = $supdis;
		$this->load->view('wms/vrcv_fgreturn', $data);
	}

	public function vprintlabel(){
		$this->load->view('wms/vprintlabel');
	}

	public function vsplit_do(){
		$this->load->view('wms/vsplitdo');
	}

	public function get_consign_history(){
		$cbg = $this->input->get('inbg');
		$rs = $this->DELV_mod->select_consign_history(['DLV_BSGRP' => $cbg]);
		die('{"data":'.json_encode($rs).'}');
	}

	public function printlabel(){
		$pdo = '';
		global  $image_name, $dono,$wid, $hgt,$padX, $padY, $supnm, $rcvdate;
        if(isset($_COOKIE["PRINTLABEL_DO"])){
            $pdo = $_COOKIE["PRINTLABEL_DO"];
		}
		if ($pdo=='')
		{ 
			die('stop');
		} else {
			function printTagDO($pdf,$myleft,$mytop){
				global  $wid, $hgt,  $dono, $padX, $padY,  $image_name, $supnm, $rcvdate, $cmitmd1, $cprodt, $cwo, $cprdline, $cprdshift , $cserqty, $csersheet, $cum;
				$th_x = $padX+$myleft;
				$th_y = $padY+$mytop;
				$pdf->SetY(0); 
				$pdf->SetX(0);
				$pdf->SetFont('Courier','',11);		
				$clebar = $pdf->GetStringWidth($image_name)+50;							
				$pdf->Code128($th_x + (($wid/2)-($clebar/2)), $th_y +11,trim($image_name),$clebar,15);					
				$clebar = $pdf->GetStringWidth($image_name);
				$pdf->Text($th_x +($wid/2)-($clebar/2), $th_y +30, $dono);	
				$clebar = $pdf->GetStringWidth($supnm);
				$pdf->Text($th_x +($wid/2)-($clebar/2), $th_y +35, $supnm);
				$clebar = $pdf->GetStringWidth($rcvdate);
				$pdf->Text($th_x +($wid/2)-($clebar/2), $th_y +40, $rcvdate);
				$pdf->Rect($th_x +6, $th_y+5, $wid, $hgt);
			}
			$wid = 140;
			$hgt = 97;
			$padX = 0.35;
			$padY = 0.35;
			$thegap  = 1.76;
			$pdo = str_replace(str_split('"[]'),'', $pdo);
			$pdo = explode(",", $pdo);
			$rs =  $this->RCV_mod->selectBCField_in($pdo);
			$pdf = new PDF_Code39e128('L','mm','A4');
			$pdf->AddPage();
			$hgt_p = $pdf->GetPageHeight();
			$wid_p = $pdf->GetPageWidth();
			$pdf->SetAutoPageBreak(true,1);
			$pdf->SetMargins(0,0);
			$cY=0;$cX=0;
			foreach($rs as $r){	
				$image_name = trim($r->RCV_DONO);
				$dono = $image_name;
				$supnm = trim($r->MSUP_SUPNM);
				$rcvdate = $r->RCV_RCVDATE;

				if(($hgt_p - ($cY+$thegap)) < $hgt){
					$pdf->AddPage();
					$cY=0;$cX=0;
					printTagDO($pdf,$cX,$cY);
					$cX+=$wid+$thegap;						
				} else {
					if (($wid_p - $cX)>$wid){// jika (lebar printer-posisi X)> lebar label
						printTagDO($pdf, $cX, $cY);
						$cX+=$wid+$thegap;
					} else {
						$cY+=$hgt+$thegap;
						if(($hgt_p - ($cY+$thegap)) < $hgt){
							$pdf->AddPage();
							$cX = 0;
							$cY = 0;
							printTagDO($pdf, $cX, $cY);
						} else {
							$cX = 0;
							printTagDO($pdf, $cX, $cY);
						}
						$cX+=$wid+$thegap;
					}
				}			
			}
			$pdf->Output('I','DO LABEL '.date("d-M-Y").'.pdf');								
		}
	}

	public function delete(){
		$citem 	= $this->input->get('initem');
		$cdo 	= $this->input->get('indo');
		$toret  = $this->RCV_mod->deletebyDOandItem($cdo, $citem);
		if($toret>0){
			echo "Deleted successfully";
		}
	}

	public function getdiscrepancy_scan(){
		$myar  = [];
		$rs = $this->RCVSCN_mod->select_discrepancy_h();
		if(count($rs) >0){
			$myar[] = ['cd' => 1 , 'msg' => 'Go ahead'];
		} else {
			$myar[] =  ['cd' => 0 , 'msg' => 'Good, there is no discrepancy data'];
		}
		exit('{"status" : '.json_encode($myar).', "data" : '.json_encode($rs).'}');
	}
	public function getdiscrepancy_scan_d(){
		$myar  = [];
		$cdo = $this->input->get('indo');
		$rs = $this->RCVSCN_mod->select_discrepancy($cdo);
		if(count($rs) >0){
			$myar[] = ['cd' => 1 , 'msg' => 'Go ahead'];
		} else {
			$myar[]= ['cd' => 0 , 'msg' => 'Good, there is no discrepancy data'];
		}
		exit('{"status" : '.json_encode($myar).', "data" : '.json_encode($rs).'}');
	}

	public function set(){
		date_default_timezone_set('Asia/Jakarta');
		$currrtime = date('Y-m-d H:i:s');		
		////////////DETAIL/////////////
		$cpo	= $this->input->post('inpo');
		$citem 	= $this->input->post('initem');
		$cqty 	= $this->input->post('inqty');
		$cprice = $this->input->post('inprice');
		//////////END DETAIL///////////

		$cdate 	= $this->input->post('indate');
		$cdo 	= $this->input->post('indo');
		$cven 	= $this->input->post('inven');		

		$ttldatas = count($cpo);
		$toret = 0 ;
		for($i=0;$i<$ttldatas;$i++){
			$datacheck = [
				'RCV_ITMCD' => $citem[$i],
				'RCV_DONO' => $cdo
			];
			if($this->RCV_mod->check_Primary($datacheck)>0){
				$dtu = [
					'RCV_QTY' 		=> $cqty[$i],
					'RCV_AMT' 		=> $cprice[$i],
					'RCV_LUPDT' 	=> $currrtime,
					'RCV_USRID' 	=> $this->session->userdata('nama')
				];
				$toret += $this->RCV_mod->updatebyId($dtu, $cdo, $citem[$i]);
			} else {
				$dts = [
					'RCV_PO' 		=> $cpo[$i],
					'RCV_ITMCD' 	=> $citem[$i],
					'RCV_QTY' 		=> $cqty[$i],
					'RCV_AMT' 		=> $cprice[$i],
					'RCV_SUPID' 	=> $cven,
					'RCV_DONO'		=> $cdo,
					'RCV_RCVDATE' 	=> $cdate,
					'RCV_LUPDT' 	=> $currrtime,
					'RCV_USRID' 	=> $this->session->userdata('nama')
				];
				$toret += $this->RCV_mod->insert($dts);
			}
		}
		if($toret>0){
			echo "Saved successfully";
		}
	}

	public function setscn(){
		date_default_timezone_set('Asia/Jakarta');
		
		$currdate = date('Y-m-d');
		$cdo = $this->input->post('indo');		
		$cwh = $this->input->post('inwh');		

		$flag_insert = 0;
		$flag_update = 0;		

		$rsunsaved = $this->RCVSCN_mod->select_unsaved($cdo);
		foreach($rsunsaved as $r){
			$resscn = $this->RCVSCN_mod->update_unsaved($cdo, $r['RCVSCN_ID']);
			$flag_update+=$resscn;
			if($resscn >0){
				$datas = [
					'ITH_ITMCD' => trim($r['RCVSCN_ITMCD']), 'ITH_WH' => $cwh ,
					'ITH_DOC' => $cdo, 'ITH_DATE' => $currdate,
					'ITH_FORM' => 'INC-DO', 'ITH_QTY' => $r['RCVSCN_QTY'], 
					'ITH_USRID' =>  $this->session->userdata('nama')
				];
				$resith = $this->ITH_mod->insert_incdo($datas);
				$flag_insert+=$resith;
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

	public function getdostxi_list(){
		header('Content-Type: application/json');
		$dono 	= $this->input->get('pdo');
		$rs 	= $this->RCVSTXI_mod->selectbyid($dono);
		echo json_encode($rs);
	}

	public function getsaveddo_list(){
		header('Content-Type: application/json');
		$dono 	= $this->input->get('pdo');
		$rs 	= $this->RCV_mod->selectbyid($dono);
		echo json_encode($rs);
	}

	public function updatecategory(){
		header('Content-Type: application/json');
		$cdoc = $this->input->post('indoc');
		$cline = $this->input->post('inline');
		$ccat = $this->input->post('incat');
		$ret = $this->RETFG_mod->update_where(['RETFG_CAT' => $ccat], ['RETFG_DOCNO' => $cdoc, 'RETFG_LINE' => $cline]);
		$myar = [];
		if($ret>0){
			$myar[] = ['cd' => 1, 'msg' => 'Updated'];
		} else {
			$myar[] = ['cd' => 0, 'msg' => 'Could not update'];
		}
		die('{"status": '.json_encode($myar).'}');
	}

	public function getsaveddo_list_jdt(){
		header('Content-Type: application/json');		
		$cyear 	= $this->input->get('inyear');
		$cmonth	= $this->input->get('inmonth');
		$csts	= $this->input->get('insts');
		$rs 	= $csts=='all' ?  $this->RCV_mod->selectbyYM($cyear, $cmonth) : $this->RCV_mod->selectbyYM_open($cyear, $cmonth);
		echo '{"data":'.json_encode($rs).'}';
	}

	public function getdetaildo(){
		header('Content-Type: application/json');
		$dono 	= $this->input->get('indo');
		$rs 	= $this->RCVSTXI_mod->selectbydo($dono);
		echo json_encode($rs);
	}

	public function getdetailsaveddo(){
		header('Content-Type: application/json');
		$dono 	= $this->input->get('indo');
		$rs 	= $this->RCV_mod->selectbydo($dono);
		echo json_encode($rs);
	}

	public function getBCField(){
		header('Content-Type: application/json');
		$dono 	= $this->input->get('indo');
		$rs 	= $this->RCV_mod->selectBCField($dono);
		echo json_encode($rs);
	}

	public function checkSession(){
		$myar = [];
		if ($this->session->userdata('status') != "login")
        {
			$myar[] = ["cd" => "0", "msg" => "Session is expired please reload page"];			
			exit(json_encode($myar));
        }
	}

	public function saveManually() {
		$myar = [];
		if ($this->session->userdata('status') != "login")
        {
			$myar[] = ["cd" => "0", "msg" => "Session is expired please reload page"];			
			exit(json_encode(['status' => $myar]));
        }
		header('Content-Type: application/json');
		date_default_timezone_set('Asia/Jakarta');		
		$h_do = $this->input->post('h_do');
		$h_bctype = $this->input->post('h_bctype');
		$h_bcstatus = $this->input->post('h_bcstatus');
		$h_aju = $this->input->post('h_aju');		
		$h_nopen = $this->input->post('h_nopen');
		$h_date_bc = $this->input->post('h_date_bc');
		$h_date_rcv = $this->input->post('h_date_rcv');
		$h_type_tpb = $this->input->post('h_type_tpb');
		$h_kppbc = $this->input->post('h_kppbc');
		$h_amount = $this->input->post('h_amount');
		$h_nw = $this->input->post('h_nw');	
		$h_gw = $this->input->post('h_gw');	
		$h_bisgrup = $this->input->post('h_bisgrup');
		$h_cona = $this->input->post('h_cona');
		$h_mconaDate = $this->input->post('h_mconaDate');
		$h_mconaDueDate = $this->input->post('h_mconaDueDate');
		$h_supcd = $this->input->post('h_supcd');
		$h_minvNo = $this->input->post('h_minvNo');
		$h_tax_invoice = $this->input->post('h_tax_invoice');
		$d_grlno = $this->input->post('d_grlno');
		$d_nourut = $this->input->post('d_nourut');
		$d_pono = $this->input->post('d_pono');
		$d_itemcode = $this->input->post('d_itemcode');
		$d_qty = $this->input->post('d_qty');
		$d_price = $this->input->post('d_price');
		$d_hscode = $this->input->post('d_hscode');
		$d_bm = $this->input->post('d_bm');
		$d_ppn = $this->input->post('d_ppn');
		$d_pph = $this->input->post('d_pph');
		$d_prNW = $this->input->post('d_prNW');
		$d_prGW = $this->input->post('d_prGW');
		$d_assetnum = $this->input->post('d_assetnum');
		$d_pkg_idrow = $this->input->post('d_pkg_idrow');
		$d_pkg_jml = $this->input->post('d_pkg_jml');
		$d_pkg_kd = $this->input->post('d_pkg_kd');
		$ttlrowsPKG = is_array($d_pkg_kd) ? count($d_pkg_kd) : 0;
		$dataCount = count($d_itemcode);
		$myar = [];
		$ttlupdated = 0;
		$datas = [];
		if($this->RCV_mod->check_Primary(['RCV_DONO' => $h_do])) {
			$lastLine = $this->RCV_mod->select_lastLinePerDO($h_do)+1;
			for($i=0; $i<$dataCount; $i++) {
				$dataw = ['RCV_DONO' => $h_do, 'RCV_ITMCD' => $d_itemcode[$i],'RCV_GRLNO' => $d_grlno[$i] ];
				if($this->RCV_mod->check_Primary($dataw) ) {
					$datau = [
						'RCV_RPNO' => $h_aju,
						'RCV_RPDATE' => $h_date_bc,
						'RCV_BCTYPE' => $h_bctype,
						'RCV_BCNO' => $h_nopen,
						'RCV_BCDATE' => $h_date_bc,
						'RCV_QTY' => $d_qty[$i],
						'RCV_PRPRC' => $d_price[$i],
						'RCV_AMT' => $d_price[$i] * $d_qty[$i],
						'RCV_TTLAMT' => ($h_amount=='' ? NULL: $h_amount),
						'RCV_TPB' => $h_type_tpb,						
						'RCV_SUPCD' => $h_supcd,
						'RCV_RCVDATE' => $h_date_rcv=='' ? NULL : $h_date_rcv,
						'RCV_NW' => ($h_nw==''? NULL:$h_nw),
						'RCV_GW' => ($h_gw==''? NULL:$h_gw),
						'RCV_PRNW' => ($d_prNW[$i]=='' || $d_prNW[$i]=='-'? NULL:$d_prNW[$i]),
						'RCV_PRGW' => ($d_prGW[$i]=='' || $d_prGW[$i]=='-'? NULL:$d_prGW[$i]),
						'RCV_KPPBC' => $h_kppbc,					
						'RCV_HSCD' => $d_hscode[$i],
						'RCV_ZSTSRCV' => $h_bcstatus,
						'RCV_BM' => is_numeric($d_bm[$i]) ? $d_bm[$i] :0 ,
						'RCV_PPN' => is_numeric($d_ppn[$i]) ? $d_ppn[$i]: 0,
						'RCV_PPH' => is_numeric($d_pph[$i]) ? $d_pph[$i]: 0,
						'RCV_ZNOURUT' => $d_nourut[$i],
						'RCV_ASSETNUM' => $d_assetnum[$i],
						'RCV_CONA' => $h_cona,
						'RCV_DUEDT' => $h_mconaDueDate == '' ? NULL : $h_mconaDueDate,
						'RCV_CONADT' => $h_mconaDate == '' ? NULL : $h_mconaDate,
						'RCV_INVNO' => $h_minvNo,
						'RCV_TAXINVOICE' => $h_tax_invoice,
						'RCV_BSGRP' => $h_bisgrup,
						'RCV_LUPDT' => date('Y-m-d H:i:s'),
						'RCV_USRID' => $this->session->userdata('nama')						
					];
					$ttlupdated+= $this->RCV_mod->updatebyVAR($datau, $dataw);
				} else {
					$datas[] = [
						'RCV_PO' => $d_pono[$i],
						'RCV_DONO' => $h_do,
						'RCV_INVNO' => $h_minvNo,
						'RCV_TAXINVOICE' => $h_tax_invoice,
						'RCV_ITMCD' => trim($d_itemcode[$i]),
						'RCV_RPNO' => $h_aju,
						'RCV_RPDATE' => $h_date_bc,
						'RCV_BCTYPE' => $h_bctype,
						'RCV_BCNO' => $h_nopen,
						'RCV_BCDATE' => $h_date_bc,
						'RCV_QTY' => $d_qty[$i],
						'RCV_PRPRC' => $d_price[$i],
						'RCV_AMT' => $d_price[$i] * $d_qty[$i] ,
						'RCV_TTLAMT' => ($h_amount=='' ? NULL: $h_amount),
						'RCV_TPB' => $h_type_tpb,
						'RCV_WH' => 'PSIEQUIP',
						'RCV_SUPCD' => $h_supcd,
						'RCV_RCVDATE' => $h_date_rcv=='' ? NULL : $h_date_rcv,
						'RCV_NW' => ($h_nw==''? NULL:$h_nw),						
						'RCV_GW' => ($h_gw==''? NULL:$h_gw),
						'RCV_PRNW' => ($d_prNW[$i]=='' || $d_prNW[$i]=='-'? NULL:$d_prNW[$i]),
						'RCV_PRGW' => ($d_prGW[$i]=='' || $d_prGW[$i]=='-'? NULL:$d_prGW[$i]),
						'RCV_KPPBC' => $h_kppbc,
						'RCV_GRLNO' => $lastLine,
						'RCV_HSCD' => $d_hscode[$i],
						'RCV_ZSTSRCV' => $h_bcstatus,
						'RCV_BM' => is_numeric($d_bm[$i]) ? $d_bm[$i] :0 ,
						'RCV_PPN' => is_numeric($d_ppn[$i]) ? $d_ppn[$i]: 0,
						'RCV_PPH' => is_numeric($d_pph[$i]) ? $d_pph[$i]: 0,
						'RCV_ZNOURUT' => $d_nourut[$i],
						'RCV_ASSETNUM' => $d_assetnum[$i],
						'RCV_CONA' => $h_cona,
						'RCV_DUEDT' => $h_mconaDueDate == '' ? NULL : $h_mconaDueDate,
						'RCV_CONADT' => $h_mconaDate == '' ? NULL : $h_mconaDate,
						'RCV_BSGRP' => $h_bisgrup,
						'RCV_LUPDT' => date('Y-m-d H:i:s'),
						'RCV_USRID' => $this->session->userdata('nama'),
						'RCV_CREATEDBY' => $this->session->userdata('nama')
					];
					$lastLine++;
				}
				if(count($datas)) {
					$this->RCV_mod->insertb($datas);
					$myar[] = ['cd' => 1, 'msg' => 'Saved successfully'];
				}
			}
		} else {
			for($i=0; $i<$dataCount; $i++) {
				$datas[] = [
					'RCV_PO' => $d_pono[$i],
					'RCV_INVNO' => $h_minvNo,
					'RCV_TAXINVOICE' => $h_tax_invoice,
					'RCV_DONO' => $h_do,
					'RCV_ITMCD' => trim($d_itemcode[$i]),
					'RCV_RPNO' => $h_aju,
					'RCV_RPDATE' => $h_date_bc,
					'RCV_BCTYPE' => $h_bctype,
					'RCV_BCNO' => $h_nopen,
					'RCV_BCDATE' => $h_date_bc,
					'RCV_QTY' => $d_qty[$i],
					'RCV_PRPRC' => $d_price[$i],
					'RCV_AMT' => $d_price[$i] * $d_qty[$i] ,
					'RCV_TTLAMT' => ($h_amount=='' ? NULL: $h_amount),
					'RCV_TPB' => $h_type_tpb,
					'RCV_WH' => 'PSIEQUIP',
					'RCV_SUPCD' => $h_supcd,
					'RCV_RCVDATE' => $h_date_rcv=='' ? NULL : $h_date_rcv,
					'RCV_NW' => ($h_nw==''? NULL:$h_nw),						
					'RCV_GW' =>($h_gw==''? NULL:$h_gw),
					'RCV_PRNW' => ($d_prNW[$i]=='' || $d_prNW[$i]=='-'? NULL:$d_prNW[$i]),
					'RCV_PRGW' => ($d_prGW[$i]=='' || $d_prGW[$i]=='-'? NULL:$d_prGW[$i]),
					'RCV_KPPBC' => $h_kppbc,
					'RCV_GRLNO' => $i,
					'RCV_HSCD' => $d_hscode[$i],
					'RCV_ZSTSRCV' => $h_bcstatus,
					'RCV_BM' => is_numeric($d_bm[$i]) ? $d_bm[$i] :0 ,
					'RCV_PPN' => is_numeric($d_ppn[$i]) ? $d_ppn[$i]: 0,
					'RCV_PPH' => is_numeric($d_pph[$i]) ? $d_pph[$i]: 0,
					'RCV_ZNOURUT' => $d_nourut[$i],
					'RCV_ASSETNUM' => $d_assetnum[$i],
					'RCV_CONA' => $h_cona,
					'RCV_DUEDT' => $h_mconaDueDate == '' ? NULL : $h_mconaDueDate,
					'RCV_CONADT' => $h_mconaDate == '' ? NULL : $h_mconaDate,
					'RCV_BSGRP' => $h_bisgrup,					
					'RCV_LUPDT' => date('Y-m-d H:i:s'),
					'RCV_USRID' => $this->session->userdata('nama'),
					'RCV_CREATEDBY' => $this->session->userdata('nama')
				];
			}
			if(count($datas)) {
				$this->RCV_mod->insertb($datas);
				$myar[] = ['cd' => 1, 'msg' => 'Saved successfully'];
			}
		}
		$newLine = $this->RCVPKG_mod->select_maxline($h_do, $h_aju)+1;
		for($i=0;$i<$ttlrowsPKG; $i++){
			if(trim($d_pkg_idrow[$i])===''){
				$this->RCVPKG_mod->insert([
					'RCVPKG_AJU' => $h_aju,
					'RCVPKG_LINE' => $newLine++,
					'RCVPKG_DOC' => $h_do,
					'RCVPKG_JUMLAH_KEMASAN' => $d_pkg_jml[$i],
					'RCVPKG_KODE_JENIS_KEMASAN' => $d_pkg_kd[$i],
					'RCVPKG_CREATED_AT' => date('Y-m-d H:i:s'),
					'RCVPKG_CREATED_BY' => $this->session->userdata('nama')
				]);
			} else {
				$this->RCVPKG_mod->updatebyId(
					['RCVPKG_JUMLAH_KEMASAN' => $d_pkg_jml[$i],	'RCVPKG_KODE_JENIS_KEMASAN' => $d_pkg_kd[$i]],
					['RCVPKG_AJU' => $h_aju, 'RCVPKG_DOC' => $h_do,'RCVPKG_LINE' => $d_pkg_idrow[$i]]
				);
			}
		}

		$this->toITH(['DOC' => $h_do, 'WH' => 'PSIEQUIP' 
		, 'DATE' => $h_date_bc , 'LUPDT' => $h_date_bc.' 07:01:00'
		, 'USRID' => $this->session->userdata('nama')]);

		$api_result =  $h_nopen!='' ?  $this->gotoque($h_do) : [];
		if(count($datas)==0){
			$myar[] = ['cd' => 1, 'msg' => 'Updated successfully'];
		}
		die(json_encode(['status' => $myar, 'API' => $api_result]));
	}

	public function trigger_toITH(){
		ini_set('max_execution_time', '-1');
		$qry = "SELECT * FROM
		(select RCV_DONO,RCV_BCDATE,RCV_WH from RCV_TBL left join MITM_TBL on RCV_ITMCD=MITM_ITMCD
		WHERE MITM_MODEL='6' and RCV_LUPDT='2021-12-24 18:00:00'
		group by RCV_DONO,RCV_BCDATE,RCV_WH) VRCVG
		ORDER BY RCV_DONO";
		// $qry = "SELECT RCV_DONO,RCV_BCDATE,RCV_WH FROM
		// (
		// select RCV_DONO,MAX(RCV_BCDATE) RCV_BCDATE,RCV_ITMCD,SUM(RCV_QTY) RQT,RCV_WH from RCV_TBL 
		// WHERE RCV_BCDATE>='2021-12-01' 
		// GROUP BY RCV_DONO,RCV_ITMCD,RCV_WH
		// ) VRCV
		// LEFT JOIN
		// (SELECT ITH_DOC,ITH_ITMCD,SUM(ITH_QTY) IQT FROM ITH_TBL WHERE ITH_FORM='INC-DO' and ITH_DATE>='2021-12-01'
		// GROUP BY ITH_DOC,ITH_ITMCD) VITH ON VRCV.RCV_DONO=ITH_DOC AND RCV_ITMCD=ITH_ITMCD
		// WHERE RQT>ISNULL(IQT,0) AND RCV_DONO LIKE '%OMI-V-21Z-SMT-0207%'
		// GROUP BY RCV_DONO,RCV_BCDATE,RCV_WH	";
		$rs = $this->RCV_mod->qry($qry);
		$api_result = '';
		foreach($rs as $r){
			$api_result =$this->gotoque($r['RCV_DONO']);
			// $this->toITH(['DOC' => $r['RCV_DONO'], 'WH' => $r['RCV_WH']
			// , 'DATE' => $r['RCV_BCDATE'] , 'LUPDT' => $r['RCV_BCDATE'].' 07:01:00'
			// , 'USRID' => $this->session->userdata('nama')]);
		}
		die('done'.$api_result.'('.date('Y-m-d H:i:s').')');
	}

	public function dump_to_rcv(){
		ini_set('max_execution_time', '-1');
		$qry = "SELECT PO_NO,ITMCD,NOAJU,BCDATE,BCTYPE,BCNO,BCDATE,BCDATE,DO_NO,QTY
		,QTY*PRICE AMTK,SUPCD,'PSIEQUIP' WH,'1' TPB,PRICE,KPPBC,GRLNO,STATUSRCV,GRLNO,'PSI-COMM' BG,'2021-12-05 18:00:00' LUPTD,'ane' USR from SPAREPART_202001_12_READY
		left join RCV_TBL on DO_NO=RCV_DONO and NOAJU=RCV_RPNO and ITMCD=RCV_ITMCD and BCNO=RCV_BCNO
		where RCV_ITMCD is null";
		$rs = $this->RCV_mod->qry($qry);
		foreach($rs as $r){
			$this->RCV_mod->insert(
				[
					'RCV_PO' => $r['PO_NO']
					,'RCV_ITMCD' => $r['ITMCD']
					,'RCV_RPNO' => $r['NOAJU']
					,'RCV_RPDATE' => $r['BCDATE']
					,'RCV_BCTYPE' => $r['BCTYPE']
					,'RCV_BCNO' => $r['BCNO']
					,'RCV_BCDATE' => $r['BCDATE']
					,'RCV_RCVDATE' => $r['BCDATE']
					,'RCV_DONO' => $r['DO_NO']
					,'RCV_QTY' => $r['QTY']
					,'RCV_AMT' => $r['AMTK']
					,'RCV_SUPCD' => $r['SUPCD']
					,'RCV_WH' => $r['WH']
					,'RCV_TPB' => $r['TPB']
					,'RCV_PRPRC' => $r['PRICE']
					,'RCV_KPPBC' => $r['KPPBC']
					,'RCV_GRLNO' => $r['GRLNO']
					,'RCV_ZSTSRCV' => $r['STATUSRCV']
					,'RCV_ZNOURUT' => $r['GRLNO']
					,'RCV_BSGRP' => $r['BG']
					,'RCV_LUPDT' => $r['LUPTD']
					,'RCV_USRID' => $r['USR']
				]
			);
		}
		die('done');
	}


	public function saveManually2() {
		$myar = [];
		if ($this->session->userdata('status') != "login")
        {
			$myar[] = ["cd" => "0", "msg" => "Session is expired please reload page"];			
			exit(json_encode(['status' => $myar]));
        }
		header('Content-Type: application/json');
		date_default_timezone_set('Asia/Jakarta');
		$h_do = $this->input->post('h_do');
		$h_bctype = $this->input->post('h_bctype');
		$h_bcstatus = $this->input->post('h_bcstatus');
		$h_aju = $this->input->post('h_aju');
		$h_nopen = $this->input->post('h_nopen');
		$h_date_bc = $this->input->post('h_date_bc');
		$h_date_rcv = $this->input->post('h_date_rcv');
		$h_type_tpb = $this->input->post('h_type_tpb');
		$h_kppbc = $this->input->post('h_kppbc');
		$h_amount = $this->input->post('h_amount');
		$h_nw = $this->input->post('h_nw');	
		$h_gw = $this->input->post('h_gw');	
		$h_bisgrup = $this->input->post('h_bisgrup');		
		$h_supcd = $this->input->post('h_supcd');
		$h_minvNo = $this->input->post('h_minvNo');
		$h_warehouse = $this->input->post('h_warehouse');
		$d_grlno = $this->input->post('d_grlno');
		$d_nourut = $this->input->post('d_nourut');
		$d_pono = $this->input->post('d_pono');
		$d_itemcode = $this->input->post('d_itemcode');
		$d_qty = $this->input->post('d_qty');
		$d_price = $this->input->post('d_price');
		$d_hscode = $this->input->post('d_hscode');
		$d_bm = $this->input->post('d_bm');
		$d_ppn = $this->input->post('d_ppn');
		$d_pph = $this->input->post('d_pph');
		$dataCount = count($d_itemcode);
		$myar = [];
		$ttlupdated = 0;
		$datas = [];
		if($this->RCV_mod->check_Primary(['RCV_DONO' => $h_do])) {
			$lastLine = $this->RCV_mod->select_lastLinePerDO($h_do)+1;
			for($i=0; $i<$dataCount; $i++) {
				$dataw = ['RCV_DONO' => $h_do, 'RCV_ITMCD' => $d_itemcode[$i],'RCV_GRLNO' => $d_grlno[$i] ];
				if($this->RCV_mod->check_Primary($dataw) ) {
					$datau = [
						'RCV_RPNO' => $h_aju,
						'RCV_RPDATE' => $h_date_bc,
						'RCV_BCTYPE' => $h_bctype,
						'RCV_BCNO' => $h_nopen,
						'RCV_BCDATE' => $h_date_bc,
						'RCV_QTY' => $d_qty[$i],
						'RCV_PRPRC' => $d_price[$i]==='-' ? 0: $d_price[$i],
						'RCV_AMT' => $d_price[$i] * $d_qty[$i],
						'RCV_TTLAMT' => ($h_amount=='' ? NULL: $h_amount),
						'RCV_TPB' => $h_type_tpb,						
						'RCV_SUPCD' => $h_supcd,
						'RCV_RCVDATE' => $h_date_rcv=='' ? NULL : $h_date_rcv,
						'RCV_NW' => ($h_nw==''? NULL:$h_nw),
						'RCV_GW' => ($h_gw==''? NULL:$h_gw),
						'RCV_KPPBC' => $h_kppbc,					
						'RCV_HSCD' => $d_hscode[$i],
						'RCV_ZSTSRCV' => $h_bcstatus,
						'RCV_BM' => is_numeric($d_bm[$i]) ? $d_bm[$i] :0 ,
						'RCV_PPN' => is_numeric($d_ppn[$i]) ? $d_ppn[$i]: 0,
						'RCV_PPH' => is_numeric($d_pph[$i]) ? $d_pph[$i]: 0,
						'RCV_ZNOURUT' => $d_nourut[$i],
						'RCV_WH' => $h_warehouse,
						'RCV_INVNO' => $h_minvNo,
						'RCV_BSGRP' => $h_bisgrup,
						'RCV_LUPDT' => date('Y-m-d H:i:s'),
						'RCV_USRID' => $this->session->userdata('nama'),
						'RCV_CREATEDBY' => $this->session->userdata('nama')
					];
					$ttlupdated+= $this->RCV_mod->updatebyVAR($datau, $dataw);
				} else {
					$datas[] = [
						'RCV_PO' => $d_pono[$i],
						'RCV_DONO' => $h_do,
						'RCV_INVNO' => $h_minvNo,
						'RCV_ITMCD' => trim($d_itemcode[$i]),
						'RCV_RPNO' => $h_aju,
						'RCV_RPDATE' => $h_date_bc,
						'RCV_BCTYPE' => $h_bctype,
						'RCV_BCNO' => $h_nopen,
						'RCV_BCDATE' => $h_date_bc,
						'RCV_QTY' => $d_qty[$i],
						'RCV_PRPRC' => $d_price[$i]==='-' ? 0: $d_price[$i],
						'RCV_AMT' => $d_price[$i] * $d_qty[$i] ,
						'RCV_TTLAMT' => ($h_amount=='' ? NULL: $h_amount),
						'RCV_TPB' => $h_type_tpb,
						'RCV_WH' => $h_warehouse,
						'RCV_SUPCD' => $h_supcd,
						'RCV_RCVDATE' => $h_date_rcv=='' ? NULL : $h_date_rcv,
						'RCV_NW' => ($h_nw==''? NULL:$h_nw),						
						'RCV_GW' => ($h_gw==''? NULL:$h_gw),
						'RCV_KPPBC' => $h_kppbc,
						'RCV_GRLNO' => $lastLine,
						'RCV_HSCD' => $d_hscode[$i],
						'RCV_ZSTSRCV' => $h_bcstatus,
						'RCV_BM' => is_numeric($d_bm[$i]) ? $d_bm[$i] :0 ,
						'RCV_PPN' => is_numeric($d_ppn[$i]) ? $d_ppn[$i]: 0,
						'RCV_PPH' => is_numeric($d_pph[$i]) ? $d_pph[$i]: 0,
						'RCV_ZNOURUT' => $d_nourut[$i],						
						'RCV_BSGRP' => $h_bisgrup,
						'RCV_LUPDT' => date('Y-m-d H:i:s'),
						'RCV_USRID' => $this->session->userdata('nama'),
						'RCV_CREATEDBY' => $this->session->userdata('nama')
					];
					$lastLine++;
				}
				if(count($datas)) {
					$this->RCV_mod->insertb($datas);
					$myar[] = ['cd' => 1, 'msg' => 'Saved successfully'];
				}
			}
		} else {
			for($i=0; $i<$dataCount; $i++) {
				$datas[] = [
					'RCV_PO' => $d_pono[$i],
					'RCV_INVNO' => $h_minvNo,
					'RCV_DONO' => $h_do,
					'RCV_ITMCD' => trim($d_itemcode[$i]),
					'RCV_RPNO' => $h_aju,
					'RCV_RPDATE' => $h_date_bc,
					'RCV_BCTYPE' => $h_bctype,
					'RCV_BCNO' => $h_nopen,
					'RCV_BCDATE' => $h_date_bc,
					'RCV_QTY' => $d_qty[$i],
					'RCV_PRPRC' => $d_price[$i]==='-' ? 0: $d_price[$i],
					'RCV_AMT' => $d_price[$i] * $d_qty[$i] ,
					'RCV_TTLAMT' => ($h_amount=='' ? NULL: $h_amount),
					'RCV_TPB' => $h_type_tpb,
					'RCV_WH' => $h_warehouse,
					'RCV_SUPCD' => $h_supcd,
					'RCV_RCVDATE' => $h_date_rcv=='' ? NULL : $h_date_rcv,
					'RCV_NW' => ($h_nw==''? NULL:$h_nw),						
					'RCV_GW' =>($h_gw==''? NULL:$h_gw),
					'RCV_KPPBC' => $h_kppbc,
					'RCV_GRLNO' => $i,
					'RCV_HSCD' => $d_hscode[$i],
					'RCV_ZSTSRCV' => $h_bcstatus,
					'RCV_BM' => is_numeric($d_bm[$i]) ? $d_bm[$i] :0 ,
					'RCV_PPN' => is_numeric($d_ppn[$i]) ? $d_ppn[$i]: 0,
					'RCV_PPH' => is_numeric($d_pph[$i]) ? $d_pph[$i]: 0,
					'RCV_ZNOURUT' => $d_nourut[$i],					
					'RCV_BSGRP' => $h_bisgrup,					
					'RCV_LUPDT' => date('Y-m-d H:i:s'),
					'RCV_USRID' => $this->session->userdata('nama'),
					'RCV_CREATEDBY' => $this->session->userdata('nama')
				];
			}
			if(count($datas)) {
				$this->RCV_mod->insertb($datas);
				$myar[] = ['cd' => 1, 'msg' => 'Saved successfully'];
			}
		}
		$this->toITH(['DOC' => $h_do, 'WH' => $h_warehouse
		, 'DATE' => $h_date_bc , 'LUPDT' => $h_date_bc.' 07:01:00'
		, 'USRID' => $this->session->userdata('nama')]);

		$api_result =$this->gotoque($h_do);
		if(count($datas)==0){
			$myar[] = ['cd' => 1, 'msg' => 'Updated successfully'];
		}
		die(json_encode(['status' => $myar, 'API' => $api_result]));
	}

	public function updateBCDoc(){
		$this->checkSession();
		date_default_timezone_set('Asia/Jakarta');			
		$currrtime 	= date('Y-m-d H:i:s');
		$cdo 		= $this->input->post('indo');
		$cconaNum = $this->input->post('inconaNum');
		$cbctype 	= $this->input->post('inbctype');
		$caju  = $this->input->post('inbcno');
		$cbcdate 	= $this->input->post('inbcdate');
		$crcvdate 	= $this->input->post('inrcvdate');
		$cregno = $this->input->post('inregno');
		$ctpb 		= $this->input->post('intpb');
		$cttlamt	= $this->input->post('inttl_amt');
		$c_nw		= $this->input->post('inNW');
		$c_gw		= $this->input->post('inGW');
		$ckppbc		= $this->input->post('inkppbc');
		$cbisgrup	= $this->input->post('inbisgrup');
		//detail
		$cpo 		= $this->input->post('inpo');		
		$csup		= $this->input->post('insupcd');
		$citem		= $this->input->post('initm');
		$cqty		= $this->input->post('inqty');
		$cprice		= $this->input->post('inprice');
		$camt		= $this->input->post('inamt');
		$cwh		= $this->input->post('inwh');
		$cgrlno		= $this->input->post('ingrlno');
		$chscd		= $this->input->post('inhscode');
		$cbm		= $this->input->post('inbm');
		$cppn		= $this->input->post('inppn');
		$cpph		= $this->input->post('inpph');
		$cnourut	= $this->input->post('innomorurut');
		$cstsrcv	= $this->input->post('instsrcv');

		$rsPGRN = $this->XPGRN_mod->selec_where_group(['RTRIM(PGRN_USRID) PGRN_USRID'], 'PGRN_USRID', ['PGRN_SUPNO' => $cdo]);
		$MEGAUser = '';
		foreach($rsPGRN as $r) {
			$MEGAUser = $r->PGRN_USRID;
		}

		$myctr_edited = 0;
		$myctr_saved = 0;		
		if(is_array($cpo)){
			$ttlar = count($cpo);
			for($i=0;$i<$ttlar;$i++){
				$dataw = [
					'RCV_PO' => $cpo[$i],
					'RCV_DONO' => $cdo,
					'RCV_ITMCD' => $citem[$i],
					'RCV_GRLNO' => $cgrlno[$i]					
				];
				if($this->RCV_mod->check_Primary($dataw)>0){					
					$datau = [
						'RCV_RPNO' => $caju,
						'RCV_RPDATE' => $cbcdate,
						'RCV_BCTYPE' => $cbctype,
						'RCV_BCNO' => $cregno,
						'RCV_BCDATE' => $cbcdate,
						'RCV_QTY' => $cqty[$i],
						'RCV_PRPRC' => $cprice[$i],
						'RCV_AMT' => $camt[$i],
						'RCV_TTLAMT' => ($cttlamt=='' ? NULL: $cttlamt),
						'RCV_TPB' => $ctpb,
						'RCV_WH' => $cwh[$i],
						'RCV_SUPCD' => $csup[$i],
						'RCV_RCVDATE' => $crcvdate=='' ? NULL : $crcvdate,
						'RCV_NW' => ($c_nw==''? NULL:$c_nw),
						'RCV_GW' => ($c_gw==''? NULL:$c_gw),
						'RCV_KPPBC' => $ckppbc,						
						'RCV_HSCD' => $chscd[$i],
						'RCV_ZSTSRCV' => $cstsrcv,
						'RCV_BM' => $cbm[$i],
						'RCV_PPN' => $cppn[$i],
						'RCV_PPH' => $cpph[$i],
						'RCV_ZNOURUT' => $cnourut[$i],
						'RCV_CONA' => $cconaNum,
						'RCV_BSGRP' => $cbisgrup,						
						'RCV_USRID' => $this->session->userdata('nama'),
						'RCV_CREATEDBY' => $MEGAUser
					];					
					$cret = $this->RCV_mod->updatebyVAR($datau, $dataw);
					$myctr_edited += $cret;					
				} else {
					$datas = [
						'RCV_PO' => $cpo[$i],
						'RCV_DONO' => $cdo,
						'RCV_ITMCD' => $citem[$i],
						'RCV_RPNO' => $caju,
						'RCV_RPDATE' => $cbcdate,
						'RCV_BCTYPE' => $cbctype,
						'RCV_BCNO' => $cregno,
						'RCV_BCDATE' => $cbcdate,
						'RCV_QTY' => $cqty[$i],
						'RCV_PRPRC' => $cprice[$i],
						'RCV_AMT' => $camt[$i],
						'RCV_TTLAMT' => ($cttlamt=='' ? NULL: $cttlamt),
						'RCV_TPB' => $ctpb,
						'RCV_WH' => $cwh[$i],
						'RCV_SUPCD' => $csup[$i],
						'RCV_RCVDATE' => $crcvdate=='' ? NULL : $crcvdate,
						'RCV_NW' => ($c_nw==''? NULL:$c_nw),						
						'RCV_GW' =>($c_gw==''? NULL:$c_gw),
						'RCV_KPPBC' => $ckppbc,
						'RCV_GRLNO' => $cgrlno[$i],
						'RCV_HSCD' => $chscd[$i],
						'RCV_ZSTSRCV' => $cstsrcv,
						'RCV_BM' => $cbm[$i],
						'RCV_PPN' => $cppn[$i],
						'RCV_PPH' => $cpph[$i],
						'RCV_ZNOURUT' => $cnourut[$i],
						'RCV_CONA' => $cconaNum,
						'RCV_BSGRP' => $cbisgrup,
						'RCV_LUPDT' => $currrtime,
						'RCV_USRID' => $this->session->userdata('nama'),
						'RCV_CREATEDBY' => $MEGAUser
					];
					$cret = $this->RCV_mod->insert($datas);					
					$myctr_saved +=  $cret;
				}
			}		
		} else {
			$dataw = [
				'RCV_PO' => $cpo,
				'RCV_DONO' => $cdo,
				'RCV_ITMCD' => $citem,
				'RCV_GRLNO' => $cgrlno
			];
			if($this->RCV_mod->check_Primary($dataw)>0){
				$datau = [
					'RCV_RPNO' => $caju,
					'RCV_RPDATE' => $cbcdate,
					'RCV_BCTYPE' => $cbctype,
					'RCV_BCNO' => $cregno,
					'RCV_BCDATE' => $cbcdate,
					'RCV_QTY' => $cqty,
					'RCV_PRPRC' => $cprice,
					'RCV_AMT' => $camt,
					'RCV_TTLAMT' => ($cttlamt=='' ? NULL: $cttlamt),
					'RCV_TPB' => $ctpb,
					'RCV_WH' => $cwh,
					'RCV_SUPCD' => $csup,
					'RCV_RCVDATE' => $crcvdate=='' ? NULL : $crcvdate,
					'RCV_NW' => ($c_nw==''? NULL:$c_nw),					
					'RCV_GW' =>($c_gw==''? NULL:$c_gw),
					'RCV_KPPBC' => $ckppbc,
					'RCV_GRLNO' => $cgrlno,
					'RCV_HSCD' => $chscd,
					'RCV_ZSTSRCV' => $cstsrcv,
					'RCV_BM' => $cbm,
					'RCV_PPN' => $cppn,
					'RCV_PPH' => $cpph,
					'RCV_ZNOURUT' => $cnourut,					
					'RCV_USRID' => $this->session->userdata('nama'),
					'RCV_CREATEDBY' => $MEGAUser
				];
				$cret = $this->RCV_mod->updatebyVAR($datau, $dataw);
				$myctr_edited += $cret;				
			} else {
				$datas = [
					'RCV_PO' => $cpo,
					'RCV_DONO' => $cdo,
					'RCV_ITMCD' => $citem,
					'RCV_RPNO' => $caju,
					'RCV_RPDATE' => $cbcdate,
					'RCV_BCTYPE' => $cbctype,
					'RCV_BCNO' => $cregno,
					'RCV_BCDATE' => $cbcdate,
					'RCV_QTY' => $cqty,
					'RCV_PRPRC' => $cprice,
					'RCV_AMT' => $camt,
					'RCV_TTLAMT' => ($cttlamt=='' ? NULL: $cttlamt),
					'RCV_TPB' => $ctpb,
					'RCV_WH' => $cwh,
					'RCV_SUPCD' => $csup,
					'RCV_RCVDATE' => $crcvdate=='' ? NULL : $crcvdate,
					'RCV_NW' => ($c_nw==''? NULL:$c_nw),					
					'RCV_GW' =>($c_gw==''? NULL:$c_gw),
					'RCV_KPPBC' => $ckppbc,
					'RCV_GRLNO' => $cgrlno,
					'RCV_HSCD' => $chscd,
					'RCV_ZSTSRCV' => $cstsrcv,
					'RCV_BM' => $cbm,
					'RCV_PPN' => $cppn,
					'RCV_PPH' => $cpph,
					'RCV_ZNOURUT' => $cnourut,
					'RCV_LUPDT' => $currrtime,
					'RCV_USRID' => $this->session->userdata('nama'),
					'RCV_CREATEDBY' => $MEGAUser
				];
				$cret = $this->RCV_mod->insert($datas);
				$myctr_saved +=  $cret;				
			}
		}

		$this->toITH(['DOC' => $cdo, 'WH' => $cwh[0]
		, 'DATE' => $cbcdate , 'LUPDT' => $cbcdate.' 07:01:00'
		, 'USRID' => $this->session->userdata('nama')]);
				
		$catccc =$this->gotoque($cdo);
		$myar = [];	
		$myar[] = ["cd" => "1"
		, "msg" => $myctr_saved." saved, " .$myctr_edited." edited [".$catccc."]"
		, "extra" => trim($cwh[0])];
		echo json_encode($myar);
	}

	public function toITH($p){
		$rsdlv = $this->RCV_mod->select_itemtotal($p['DOC']);
		$rsith = $this->ITH_mod->select_itemtotal($p['DOC'], $p['WH']);
		$rstosave = [];
		if(count($rsith)){
			foreach($rsdlv as $r){
				$isfound = false;
				foreach($rsith as $n){
					if($r['RCV_ITMCD']==$n['ITH_ITMCD'] ) {
						$isfound = true;
						if($r['RCV_QTY']!=$n['ITH_QTY']) {
							//update in
							$where = ['ITH_WH' => $p['WH']
								, 'ITH_DOC' => $n['ITH_DOC']
								, 'ITH_ITMCD' => $n['ITH_ITMCD']
								];
							$this->ITH_mod->updatebyId($where, ['ITH_QTY' => 1*$r['RCV_QTY']]);							
						}
						break;
					} 
				}
				if(!$isfound){
					$rstosave[] = [
						'ITH_ITMCD' => $r['RCV_ITMCD']
						,'ITH_DATE' => $p['DATE']
						,'ITH_FORM' => 'INC-DO'
						,'ITH_DOC' => $p['DOC']
						,'ITH_QTY' => 1*$r['RCV_QTY']
						,'ITH_WH' => $p['WH']						
						,'ITH_LUPDT' => $p['LUPDT']
						,'ITH_USRID' => $p['USRID']
					];					
				}
			}
		} else {
			foreach($rsdlv as $r){
				$rstosave[] = [
					'ITH_ITMCD' => $r['RCV_ITMCD']
					,'ITH_DATE' => $p['DATE']
					,'ITH_FORM' => 'INC-DO'
					,'ITH_DOC' => $p['DOC']
					,'ITH_QTY' => 1*$r['RCV_QTY']
					,'ITH_WH' => $p['WH']					
					,'ITH_LUPDT' => $p['LUPDT']
					,'ITH_USRID' => $p['USRID']
				];				
			}
		}
		if(count($rstosave)){
			$this->ITH_mod->insertb($rstosave);
		}
	}

	public function vold_data(){
		$this->load->view('wms/vrcv_old_data');
	}
	public function form_invoice(){
		$this->load->view('wms/vinvoice');
	}
	public function form_report_summary_inv(){
		$this->load->view('wms_report/vsummary_sup_invoice');
	}

	public function report_summary_inv(){
		header('Content-Type: application/json');
		$pdate1 = $this->input->get('date1');
		$pdate2 = $this->input->get('date2');
		$cbgroup = $this->input->get('bsgrp');
		$searchby = $this->input->get('searchby');
		$search = $this->input->get('search');
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
			$sbgroup = "''";
		}
		$rs = $searchby =='DO' ? $this->RCV_mod->select_deliv_invo_byDO($pdate1, $pdate2, $sbgroup, $search) 
		: $this->RCV_mod->select_deliv_invo($pdate1, $pdate2, $sbgroup, $search);
		die(json_encode(['data' => $rs]));
	}

	public function report_summary_inv_as_xls() {
		$bsgrp = '';
		$pdate1 = '';
		$pdate2 = '';
		if(isset($_COOKIE["CKPSI_BG"])){
            $bsgrp = $_COOKIE["CKPSI_BG"];
		} else {
			exit('nothing to be exported');
		}
		$pdate1 = $_COOKIE["CKPSI_DATE1"];
		$pdate2 = $_COOKIE["CKPSI_DATE2"];
		$sbgroup =$bsgrp;		
		$rs = $this->RCV_mod->select_deliv_invo($pdate1, $pdate2, $sbgroup,'');
		$spreadsheet = new Spreadsheet();
		$sheet = $spreadsheet->getActiveSheet();
		$sheet->setTitle('RESUME');
		$sheet->setCellValueByColumnAndRow(1,1, 'ACTUAL RECEIVE PART');
		$sheet->setCellValueByColumnAndRow(1,2, 'PERIOD : '.str_replace('-','/',$pdate1). ' - '.str_replace('-', '/', $pdate2));
		$sheet->setCellValueByColumnAndRow(1,3, 'BUSINESS : '.str_replace("'","",$sbgroup));

		$sheet->setCellValueByColumnAndRow(1,4, 'Supplier Code');
		$sheet->setCellValueByColumnAndRow(2,4, 'Supplier Name');
		$sheet->setCellValueByColumnAndRow(3,4, 'GRN No');
		$sheet->setCellValueByColumnAndRow(4,4, 'DO No');
		$sheet->setCellValueByColumnAndRow(5,4, 'Invoice No.');
		$sheet->setCellValueByColumnAndRow(6,4, 'PO No');
		$sheet->setCellValueByColumnAndRow(7,4, 'Parts Code');
		$sheet->setCellValueByColumnAndRow(8,4, 'Maker Parts Code');
		$sheet->setCellValueByColumnAndRow(9,4, 'Parts Name');
		$sheet->setCellValueByColumnAndRow(10,4, 'Receive Date');
		$sheet->setCellValueByColumnAndRow(11,4, 'Qty');
		$sheet->setCellValueByColumnAndRow(12,4, 'Unit');
		$sheet->setCellValueByColumnAndRow(13,4, 'Currency');
		$sheet->setCellValueByColumnAndRow(14,4, 'Unit Price');
		$sheet->setCellValueByColumnAndRow(15,4, 'Amount');
		$sheet->setCellValueByColumnAndRow(16,4, 'Warehouse');
		$inx=5;
		foreach($rs as $r) {
			$sheet->setCellValueByColumnAndRow(1,$inx, $r['PGRN_SUPCD']);
			$sheet->setCellValueByColumnAndRow(2,$inx, $r['MSUP_SUPNM']);
			$sheet->setCellValueByColumnAndRow(3,$inx, $r['PGRN_GRLNO']);
			$sheet->setCellValueByColumnAndRow(4,$inx, $r['PGRN_SUPNO']);
			$sheet->setCellValueByColumnAndRow(5,$inx, $r['PNGR_INVNO']);
			$sheet->setCellValueByColumnAndRow(6,$inx, $r['PGRN_PONO']);
			$sheet->setCellValueByColumnAndRow(7,$inx, $r['PGRN_ITMCD']);
			$sheet->setCellValueByColumnAndRow(8,$inx, $r['MITM_SPTNO']);
			$sheet->setCellValueByColumnAndRow(9,$inx, $r['MITM_ITMD1']);
			$sheet->setCellValueByColumnAndRow(10,$inx, $r['PGRN_RCVDT']);
			$sheet->setCellValueByColumnAndRow(11,$inx, $r['PGRN_ROKQT']);
			$sheet->setCellValueByColumnAndRow(12,$inx, $r['MITM_STKUOM']);
			$sheet->setCellValueByColumnAndRow(13,$inx, $r['PGRN_CURCD']);
			$sheet->setCellValueByColumnAndRow(14,$inx, $r['PGRN_PRPRC']);
			$sheet->setCellValueByColumnAndRow(15,$inx, $r['PGRN_AMT']);
			$sheet->setCellValueByColumnAndRow(16,$inx, $r['PGRN_LOCCD']);
			$inx++;
		}
		foreach(range('A','P') as $r){
			$sheet->getColumnDimension($r)->setAutoSize(true);
		}
		$rang = "P1:P".$inx;
		$sheet->getStyle($rang)->getNumberFormat()->setFormatCode(\PhpOffice\PhpSpreadsheet\Style\NumberFormat::FORMAT_NUMBER_COMMA_SEPARATED1);
		$dodis = $pdate1 . " to ".$pdate2;
		$stringjudul = "Summary Supplier Invoice ". $dodis;
		$writer = new Xlsx($spreadsheet);
		$filename=$stringjudul; //save our workbook as this file name
		
		header('Content-Type: application/vnd.ms-excel');
		header('Content-Disposition: attachment;filename="'. $filename .'.xlsx"'); 
		header('Cache-Control: max-age=0');
		$writer->save('php://output');
	}

	public function ostinv(){
		header('Content-Type: application/json');
		$supcd = $this->input->get('supcd');
		$bisgrup = $this->input->get('bisgrup');
		$where = $bisgrup =='' ? ['RCV_SUPCD' => $supcd] : ['RCV_SUPCD' => $supcd, 'RCV_BSGRP' => $bisgrup];
		$rs = $this->RCV_mod->select_ostso($where);
		die(json_encode(['data' => $rs]));
	}
	public function pltinv(){
		header('Content-Type: application/json');
		$supcd = $this->input->get('supcd');
		$bisgrup = $this->input->get('bisgrup');
		$invno = $this->input->get('invno');
		$where = $bisgrup =='' ? ['RCV_SUPCD' => $supcd, 'RCV_INVNOACT' => $invno] : ['RCV_SUPCD' => $supcd, 'RCV_BSGRP' => $bisgrup];
		$rs = $this->RCV_mod->select_pltinv($where);
		die(json_encode(['data' => $rs]));
	}

	public function send_to_it_inv(){
		ini_set('max_execution_time', '-1');
		$rs=$this->RCV_mod->select_do_only();
		foreach($rs as $r){
			$catccc =$this->gotoque(trim($r->RCV_DONO));
		}
		die('done');
	}

	function storeto_fiform($dataw, $datas, $datau){
		if($this->FIFORM_mod->check_Primary($dataw)>0){
			$toret = $this->FIFORM_mod->updatebyVAR($datau,$dataw);
		} else {
			$toret = $this->FIFORM_mod->insert($datas);
		}
	}

	function removemanual(){
		header('Content-Type: application/json');
		$lineId = $this->input->post('lineId');
		$docNum = $this->input->post('docNum');
		$this->RCV_mod->deletebyID(['RCV_DONO' => $docNum, 'RCV_GRLNO' => $lineId]);
		$myar[] = ['cd' => '1', 'msg' => 'Deleted successfully'];
		die(json_encode(['status' => $myar]));
	}

	public function vscan(){
		$this->load->view('wms/vrcv_scn');
	}

	public function export_to_spreadsheet(){
		$stringjudul = 'Export Data RM Receiving';
		$spreadsheet = new Spreadsheet();
		$sheet = $spreadsheet->getActiveSheet();
		$sheet->setTitle('sampai');
		$sheet->setCellValueByColumnAndRow(1,1, 'INCOMING RM ');
		$sheet->setCellValueByColumnAndRow(1,2, 'PO Number ');
		$sheet->setCellValueByColumnAndRow(2,2, ': ');
		$sheet->setCellValueByColumnAndRow(1,3, 'DO Number ');
		$sheet->setCellValueByColumnAndRow(2,3, ': ');

		$writer = new Xlsx($spreadsheet);		
		$filename='Export INCOMING RM '.$stringjudul.date(' i s'); //save our workbook as this file name
		
		header('Content-Type: application/vnd.ms-excel');
		header('Content-Disposition: attachment;filename="'. $filename .'.xlsx"'); 
		header('Cache-Control: max-age=0');
		$writer->save('php://output');
	}

	public function scn_balancing(){
		header('Content-Type: application/json');
		$cdo = $this->input->get('inDO');		
		$rs = $this->RCV_mod->selectscan_balancingv2($cdo);
		$rsh =$this->RCV_mod->selectprog_scan($cdo);
		$rss =$this->RCV_mod->selectprog_save($cdo);
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
		$cwh = $this->input->get('inWH');
		$crack = $this->input->get('inRACK');
		$citem = $this->input->get('inITEM');
		$datac  = ['RCV_ITMCD' => $citem, 'RCV_DONO' => $cdo];
		if($this->RCV_mod->check_Primary($datac)>0){			
			echo "1";
			$datac_loc = ['ITMLOC_ITM' => $citem, 'ITMLOC_LOC' => $crack, 'ITMLOC_LOCG' => $cwh];
			if($this->ITMLOC_mod->check_Primary($datac_loc)>0){
				echo "1";				
			}
		} else {
			echo "0";
		}
	}	

	public function set_rtn_fg(){
		header('Content-Type: application/json');
		date_default_timezone_set('Asia/Jakarta');		
		$current_datetime = date('Y-m-d H:i:s');
		$cdoc = $this->input->post('indoc');
		$cdocinternal = $this->input->post('indocinternal');
		$cdocinternal_date = $this->input->post('indocinternaldate');
		$ccust_cd = $this->input->post('incuscd');
		$cplant = $this->input->post('inplant');
		$cconsign = $this->input->post('inconsign');
		$ca_item = $this->input->post('initem');
		$ca_qty = $this->input->post('inqty');
		$ca_remark = $this->input->post('inremark');
		$ca_rowid = $this->input->post('inrowid');
		$ca_notice = $this->input->post('innotice');
		$ca_category = $this->input->post('incat');
		$ttldata = count($ca_item);
		$ttldata_saved = 0;
		$myar = [];
		$aromawi = ['I','II','III', 'IV', 'V', 'VI', 'VII', 'VIII', 'IX', 'X', 'XI' , 'XII'];
		// if($cdocinternal==''){
		if($this->RETFG_mod->check_Primary(['RETFG_DOCNO' => $cdoc])==0){
			$adate = explode("-", $cdocinternal_date);
			$mmonth = intval($adate[1]);
			$myear = $adate[0];
			$lastno = $this->RETFG_mod->select_lastnodo_patterned($mmonth, $myear)+1;
			$monthroma = '';
			for($i=0;$i<count($aromawi); $i++){
				if(($i+1) == $mmonth){
					$monthroma = $aromawi[$i];
					break;
				}
			}
			$ctxid = substr('0000'.$lastno,-4)."/".$monthroma."/".$myear."/RTN-".$cconsign;
			for($i=0; $i< $ttldata; $i++){
				$datac = ['RETFG_DOCNO' => $cdoc
					,'RETFG_ITMCD' => $ca_item[$i]
					,'RETFG_QTY' => str_replace(",", "",$ca_qty[$i])
					,'RETFG_RMRK' => $ca_remark[$i]
					,'RETFG_CUSCD' => $ccust_cd
					,'RETFG_PLANT' => $cplant
					,'RETFG_STRDOC' => $ctxid
					,'RETFG_CONSIGN' => $cconsign
				];
				// if($this->RETFG_mod->check_Primary($datac) == 0 ){
					$datac['RETFG_USRID'] = $this->session->userdata('nama');
					$datac['RETFG_LUPDT'] = $current_datetime;
					$datac['RETFG_STRDT'] = $cdocinternal_date;
					$datac['RETFG_NTCNO'] = $ca_notice[$i];
					$datac['RETFG_CAT'] = $ca_category[$i];
					$mlastid = $this->RETFG_mod->lastserialid($cdoc);
					$mlastid++;
					$datac['RETFG_LINE'] = $mlastid;
					$ttldata_saved += $this->RETFG_mod->insert($datac);
				// }
			}
			$myar[] = ['cd' => 1, 'msg' => 'Saved'];
			die('{"status": '.json_encode($myar).'}');
		} else {
			for($i=0; $i< $ttldata; $i++){
				$ret = $this->RETFG_mod->update_where(['RETFG_RMRK' => $ca_remark[$i] , 'RETFG_NTCNO' => $ca_notice[$i]], ['RETFG_DOCNO' => $cdoc, 'RETFG_LINE' => $ca_rowid[$i] ]);
				if($ret==0){
					$datac = ['RETFG_DOCNO' => $cdoc
						,'RETFG_ITMCD' => $ca_item[$i]
						,'RETFG_QTY' => $ca_qty[$i]
						,'RETFG_RMRK' => $ca_remark[$i]
						,'RETFG_CUSCD' => $ccust_cd
						,'RETFG_PLANT' => $cplant
						,'RETFG_STRDOC' => $cdocinternal
						,'RETFG_CONSIGN' => $cconsign
					];
					// if($this->RETFG_mod->check_Primary($datac) == 0 ){
						$datac['RETFG_USRID'] = $this->session->userdata('nama');
						$datac['RETFG_LUPDT'] = $current_datetime;
						$datac['RETFG_STRDT'] = $cdocinternal_date;
						$datac['RETFG_NTCNO'] = $ca_notice[$i];
						$datac['RETFG_CAT'] = $ca_category[$i];
						$mlastid = $this->RETFG_mod->lastserialid($cdoc);
						$mlastid++;
						$datac['RETFG_LINE'] = $mlastid;
						$ttldata_saved += $this->RETFG_mod->insert($datac);
					// }
				}
			}
			$myar[] = ['cd' => 1, 'msg' => 'Updated'];
			die('{"status": '.json_encode($myar).'}');
		}				
	}

	public function get_rtn_fg(){
		header('Content-Type: application/json');
		$cdo = $this->input->get('indo');
		$COLUMNS = ['RTRIM(MITM_ITMCD) MITM_ITMCD', 'RTRIM(MITM_ITMD1) MITM_ITMD1', 'RTRIM(MITM_STKUOM) MITM_STKUOM', 'RETFG_QTY', 'RETFG_RMRK', 
		'RETFG_CUSCD', 'RETFG_PLANT', 'RETFG_LINE', 'RETFG_STRDOC' , 'RETFG_STRDT', 
		"ISNULL(RETFG_CONSIGN,'-') RETFG_CONSIGN", "RETFG_NTCNO", "RETFG_CAT"];
		$rs = $this->RETFG_mod->select_cols_where($COLUMNS, ['RETFG_DOCNO' => $cdo]);
		die('{"data":'.json_encode($rs).'}');
	}
	public function get_h_serah_terima_doc(){
		header('Content-Type: application/json');	
		$cdo = $this->input->get('insearch');
		$rs = $this->RETFG_mod->select_h_cols_like(['RETFG_STRDOC' => $cdo]);
		die('{"data":'.json_encode($rs).'}');
	}
	public function get_serah_terima_doc(){
		header('Content-Type: application/json');
		$cdo = $this->input->get('indo');
		$COLUMNS = ['MITM_ITMCD', 'MITM_ITMD1', 'RTRIM(MITM_STKUOM) MITM_STKUOM', 'RETFG_QTY', 'RETFG_RMRK', 'RETFG_CUSCD', 'RETFG_PLANT', 'RETFG_LINE', 'RETFG_STRDOC' , 'RETFG_STRDT'];
		$rs = $this->RETFG_mod->select_cols_where($COLUMNS, ['RETFG_DOCNO' => $cdo]);
		die('{"data":'.json_encode($rs).'}');
	}

	public function remove_rtn_fg(){
		header('Content-Type: application/json');
		$cdoc = $this->input->post('indoc');
		$cline = $this->input->post('inline');
		$rs = $this->SER_mod->selectbyVAR(['SER_DOC' => $cdoc, 'SER_PRDLINE' => $cline]);
		$reffid = '';		
		foreach($rs as $r){
			$reffid = $r['SER_ID'];			
			break;
		}
		if(count($rs)>=2){
			$myar[] = ['cd' => 0, 'msg' => 'Could not delete because the label is already splitted'];
		} else {
			if($this->SERRC_mod->check_Primary(['SERRC_SER' => $reffid])>0 ){
				$myar[] = ['cd' => 0, 'msg' => 'Could not delete because it has RM replacement'];
			} else {
				$rswh = $this->ITH_mod->selectstock_ser($reffid);
				$rswh = count($rswh)>0 ? reset($rswh) : ['ITH_WH' => '??'];
				if($rswh['ITH_WH']=='AFQART'){
					$af = $this->RETFG_mod->delete_where(['RETFG_DOCNO' => $cdoc, 'RETFG_LINE' => $cline]);
					$this->SER_mod->deletebyID(['SER_DOC' => $cdoc, 'SER_PRDLINE' => $cline]);			
					$myar = [];
					if($af>0){
						if($this->ITH_mod->deletebyID(['ITH_SER' => $reffid])){
							$myar[] = ['cd' => 1, 'msg' => 'Deleted'];					
						} else {
							$myar[] = ['cd' => 1, 'msg' => 'Deleted..'];
						}
					} else {				
						$myar[] = ['cd' => 0, 'msg' => 'Nothing to be deleted'];
					}
				} else {	
					if($reffid!='')	{
						$myar[] = ['cd' => 0, 'msg' => 'Could not delete because it was already scanned'];
					} else {
						$af = $this->RETFG_mod->delete_where(['RETFG_DOCNO' => $cdoc, 'RETFG_LINE' => $cline]);
						$myar[] = ['cd' => 1, 'msg' => 'Deleted..'];
					}
				}
			}
		}		
		die('{"status":'.json_encode($myar).'}');
	}

	public function scn_set(){
		date_default_timezone_set('Asia/Jakarta');
		$currdate = date('Ymd');
		$currrtime = date('Y-m-d H:i:s');

		$cdo 	= $this->input->post('inDO');
		$citm 	= $this->input->post('inITM');
		$cqty 	= $this->input->post('inQTY');
		$clot 	= $this->input->post('inLOT');
		$crack 	= $this->input->post('inRACK');
		$datac  = ['RCV_ITMCD' => $citm, 'RCV_DONO' => $cdo];
		if($this->RCV_mod->check_Primary($datac)>0){			
			echo "1";
			$datac_loc = ['ITMLOC_ITM' => $citm, 'ITMLOC_LOC' => $crack];
			if($this->ITMLOC_mod->check_Primary($datac_loc)>0){
				echo "1"; //location valid
				$mlastid	= $this->RCVSCN_mod->lastserialid();
				$mlastid++;
				$newid 		= $currdate.$mlastid;				
				//check if scanned qty is more than balance value
				$datac_bal 	= ['RCV_DONO' => $cdo, 'RCV_ITMCD' => $citm];
				$rs 		= $this->RCV_mod->selectscan_balancing($datac_bal);
				$mget_bal = 0;
				foreach($rs as $r){
					$mget_bal = $r['RCV_QTY']-$r['SCAN_QTY']*1;				
				}
				if($cqty>$mget_bal){
					echo "0";
				} else {
					$datas = array(
						'RCVSCN_ID' => $newid, 'RCVSCN_DONO' => $cdo, 'RCVSCN_LOCID' => $crack,
						'RCVSCN_ITMCD' => $citm, 'RCVSCN_LOTNO' => $clot, 'RCVSCN_QTY' => $cqty,
						'RCVSCN_LUPDT' => $currrtime , 'RCVSCN_USRID' => $this->session->userdata('nama')
					);
					$toret = $this->RCVSCN_mod->insert($datas);
					if($toret>0){ 
						echo "1"; //insert RCVSCN success
						$rs = $this->RCV_mod->selectbalancebyDOITEM($cdo, $citm);
						foreach($rs as $r){
							echo "_".($r['RCV_QTY']-$r['SCAN_QTY']); // GET BALANCE
						}
					}
				}
				//end check
			} else {
				echo "0";
			}
		} else {
			echo "0";
		}
	}

	public function rcv_to_rcvscn(){
		ini_set('max_execution_time', '-1');
		date_default_timezone_set('Asia/Jakarta');
		$currdate = date('Ymd');
		$currrtime = date('Y-m-d H:i:s');
		// $do_number = $this->input->get('indo');
		// if(trim($do_number)==''){return 'not found';}
		// $rsunscan = $this->RCV_mod->selectDOunscanbutHasBC();
		// $rsunscan[] = ['RPSTOCK_DOC' => 'SHPT-162615'];
		// $rsunscan[] = ['RPSTOCK_DOC' => 'SHPT-162622'];
		// $rsunscan[] = ['RPSTOCK_DOC' => 'SHPT-162629'];

		// $rsunscan[] = ['RPSTOCK_DOC' => 'SHPT-162662'];
		// $rsunscan[] = ['RPSTOCK_DOC' => 'SHPT-162667'];
		// $rsunscan[] = ['RPSTOCK_DOC' => 'SHPT-162670'];
		// $rsunscan[] = ['RPSTOCK_DOC' => 'SHPT-162595'];
		// $rsunscan[] = ['RPSTOCK_DOC' => 'SHPT-162608'];
		// $rsunscan[] = ['RPSTOCK_DOC' => 'SHPT-162615'];
		// $rsunscan[] = ['RPSTOCK_DOC' => 'SHPT-162622'];
		// $rsunscan[] = ['RPSTOCK_DOC' => 'SHPT-162629'];
		$rsunscan[] = ['RPSTOCK_DOC' => 'OMI-U-219-SMT-4533'];

		foreach($rsunscan as $z){
			$rs_rcv = $this->RCV_mod->select_where_group(['RCV_ITMCD', 'RCV_DONO'], ['RCV_DONO' => $z['RPSTOCK_DOC']]);
			foreach($rs_rcv as $r){								
				//check if scanned qty is more than balance value
				$datac_bal = ['RCV_DONO' => trim($r['RCV_DONO']), 'RCV_ITMCD' => trim($r['RCV_ITMCD'])];
				$rs = $this->RCV_mod->selectscan_balancing($datac_bal);
				$mget_bal = 0;
				foreach($rs as $k){
					$mget_bal = $k['RCV_QTY']-$k['SCAN_QTY']*1;				
				}				
				if($mget_bal<=0){
					echo "0";
				} else {
					$mlastid = $this->RCVSCN_mod->lastserialid();
					$mlastid++;
					$newid 	= $currdate.$mlastid;
					$datas = [
						'RCVSCN_ID' => $newid, 'RCVSCN_DONO' => trim($r['RCV_DONO']), 'RCVSCN_LOCID' => NULL,
						'RCVSCN_ITMCD' => trim($r['RCV_ITMCD']), 'RCVSCN_LOTNO' =>NULL, 'RCVSCN_QTY' => $mget_bal,
						'RCVSCN_SAVED' => '1',
						'RCVSCN_LUPDT' => $currrtime , 'RCVSCN_USRID' => $this->session->userdata('nama')
					];
					if(!$this->RCVSCN_mod->check_Primary(['RCVSCN_ID' => $newid])) {
						$mlastid = $this->RCVSCN_mod->lastserialid();
						$mlastid++;
						
					} else {

					}
					$newid 	= $currdate.$mlastid;
					$datas = [
						'RCVSCN_ID' => $newid, 'RCVSCN_DONO' => trim($r['RCV_DONO']), 'RCVSCN_LOCID' => NULL,
						'RCVSCN_ITMCD' => trim($r['RCV_ITMCD']), 'RCVSCN_LOTNO' =>NULL, 'RCVSCN_QTY' => $mget_bal,
						'RCVSCN_SAVED' => '1',
						'RCVSCN_LUPDT' => $currrtime , 'RCVSCN_USRID' => $this->session->userdata('nama')
					];
					$toret = $this->RCVSCN_mod->insert($datas);
					if($toret>0){ 
						echo "1 <br>"; //insert RCVSCN success					
					}
				}
			}

		}
	}

	public function scnd_list_bydo_item(){
		header('Content-Type: application/json');
		$cdo 	= $this->input->get('inDO');
		$citem 	= $this->input->get('inITEM');
		$dataf 	= ['RCVSCN_DONO' => $cdo, 'RCVSCN_ITMCD'=> $citem];
		$rs 	= $this->RCVSCN_mod->selectby_filter($dataf);
		echo json_encode($rs);
	}

	public function scnd_remove(){
		$cid 	= $this->input->get('inID');
		$dataw 	= ['RCVSCN_ID' => $cid];
		$toret 	= $this->RCVSCN_mod->deleteby_filter($dataw);
		if( $toret>0){ 
			echo "Deleted";
		} else {
			echo "could not deleted";
		}
	}
	
	public function MGGetDO(){
		header('Content-Type: application/json');
		$ckey = $this->input->get('inid');		
		$cby = $this->input->get('inby');
		$cpermonth = $this->input->get('inpermonth');
		$csup = $this->input->get('insup');
		$cdatefilter = $this->input->get('indatefilter');
		$cdate1 = $this->input->get('inyear') . '-'. $this->input->get('inmonth') . '-01';
		$cdate2 = date("Y-m-t", strtotime($cdate1));
		$rs = [];
		if($cby=='do'){			
			if($cdatefilter!=''){
				$cdate1 = $cdatefilter;
				$cdate2 = $cdatefilter;
			}
			if($cpermonth == 'y'){
				if($csup!='-'){ 
					$rs =   $this->RCV_mod->MGSelectDO_dateSup($ckey, $cdate1, $cdate2, $csup);
				} else {
					$rs =   $this->RCV_mod->MGSelectDO_date($ckey, $cdate1, $cdate2);
				}
			} else {
				if($csup!='-'){
					$rs = $this->RCV_mod->MGSelectDOSup($ckey, $csup);
				} else {
					$rs = $this->RCV_mod->MGSelectDO($ckey);
				}
			}
		} else {
			if($csup!='-'){
				$rs = $this->RCV_mod->MGSelectDObyItemSup($ckey, $csup);
			} else {
				$rs = $this->RCV_mod->MGSelectDObyItem($ckey);
			}
		}		
		echo json_encode($rs);
	}
	public function GetDO1(){
		header('Content-Type: application/json');
		$ckey = $this->input->get('inid');		
		$cby = $this->input->get('inby');
		$cpermonth = $this->input->get('inpermonth');				
		$cdate1 = $this->input->get('inyear') . '-'. $this->input->get('inmonth') . '-01';
		$cdate2 = date("Y-m-t", strtotime($cdate1));
		$rs = [];
		if($cby=='do'){
			if($cpermonth == 'y'){
				$rs = $this->RCV_mod->SelectDO_date1($ckey, $cdate1, $cdate2);
			} else {
				$rs = $this->RCV_mod->SelectDO1($ckey);
			}
		} else {
			$rs = $this->RCV_mod->SelectDObyItem1($ckey);
		}		
		die(json_encode(['data' => $rs]));
	}
	public function GetDO2(){
		header('Content-Type: application/json');
		$ckey = $this->input->get('inid');		
		$cby = $this->input->get('inby');
		$cpermonth = $this->input->get('inpermonth');				
		$cdate1 = $this->input->get('inyear') . '-'. $this->input->get('inmonth') . '-01';
		$cdate2 = date("Y-m-t", strtotime($cdate1));
		$rs = [];
		if($cby=='do'){
			if($cpermonth == 'y'){
				$rs = $this->RCV_mod->SelectDO_date2($ckey, $cdate1, $cdate2);
			} else {
				$rs = $this->RCV_mod->SelectDO2($ckey);
			}
		} else {
			$rs = $this->RCV_mod->SelectDObyItem2($ckey);
		}		
		die(json_encode(['data' => $rs]));
	}

	public function GetDODetail1(){
		header('Content-Type: application/json');
		$do = $this->input->get('do');
		$supcd = $this->input->get('supcd');
		$nomorAju = $this->input->get('nomorAju');
		$columns = [
			'RTRIM(RCV_GRLNO) RCV_GRLNO'
			,'RTRIM(RCV_ZNOURUT) RCV_ZNOURUT'
			,'RTRIM(RCV_PO) RCV_PO'
			,'RTRIM(RCV_ITMCD) RCV_ITMCD'
			,'RTRIM(MITM_ITMD1) MITM_ITMD1'
			,'RTRIM(RCV_QTY) RCV_QTY'
			,'RTRIM(MITM_STKUOM) MITM_STKUOM'
			,'RTRIM(RCV_PRPRC) RCV_PRPRC'
			,'RTRIM(RCV_HSCD) RCV_HSCD'
			,'RCV_BM RCV_BM'
			,'RCV_PPN RCV_PPN'
			,'RCV_PPH RCV_PPH'
			,'RCV_PRNW'
			,'RCV_PRGW'
			,'RCV_ASSETNUM'
		];
		$rs = $this->RCV_mod->select_where($columns, ['RCV_DONO' => $do, 'RCV_SUPCD' => $supcd]);
		$rsPKG = $this->RCVPKG_mod->select_where(["RCVPKG_LINE","RCVPKG_JUMLAH_KEMASAN","RCVPKG_KODE_JENIS_KEMASAN", "URAIAN_KEMASAN"]
			,['RCVPKG_AJU' => $nomorAju, 'RCVPKG_DOC' => $do]);
		die(json_encode(['data' => $rs, 'pkg' => $rsPKG]));
	}
	public function GetDODetail2(){
		header('Content-Type: application/json');
		$do = $this->input->get('do');
		$columns = [
			'RTRIM(RCV_GRLNO) RCV_GRLNO'
			,'RTRIM(RCV_ZNOURUT) RCV_ZNOURUT'
			,'RTRIM(RCV_PO) RCV_PO'
			,'RTRIM(RCV_ITMCD) RCV_ITMCD'
			,'RTRIM(MITM_ITMD1) MITM_ITMD1'
			,'RTRIM(RCV_QTY) RCV_QTY'
			,'RTRIM(MITM_STKUOM) MITM_STKUOM'
			,'RTRIM(RCV_PRPRC) RCV_PRPRC'
			,'RTRIM(RCV_HSCD) RCV_HSCD'
			,'RTRIM(RCV_BM) RCV_BM'
			,'RTRIM(RCV_PPN) RCV_PPN'
			,'RTRIM(RCV_PPH) RCV_PPH'
		];
		$rs = $this->RCV_mod->select_where($columns, ['RCV_DONO' => $do, 'MITM_MODEL' => '0']);
		die(json_encode(['data' => $rs]));
	}

	public function MGGetDOReturn_status(){
		header('Content-Type: application/json');
		$ckey = $this->input->get('indo');
		$rs = $this->RCV_mod->MGSelectDO_return_fg($ckey);
		die('{"data":'.json_encode($rs).'}');
	}
	public function MGGetDOReturn(){
		header('Content-Type: application/json');
		$ckey = $this->input->get('inid');
		$cby = $this->input->get('inby');
		$cpermonth = $this->input->get('inpermonth');
		$csup = $this->input->get('insup');
		$cdatefilter = $this->input->get('indatefilter');
		$cdate1 = $this->input->get('inyear') . '-'. $this->input->get('inmonth') . '-01';
		$cdate2 = date("Y-m-t", strtotime($cdate1));
		$rs = [];
		if($cby=='do'){			
			if($cdatefilter!=''){
				$cdate1 = $cdatefilter;
				$cdate2 = $cdatefilter;
			}
			if($cpermonth == 'y'){				
				$rs = $csup!='-' ? $this->RCV_mod->MGSelectDO_dateSup_return_fg($ckey, $cdate1, $cdate2, $csup)
					: $this->RCV_mod->MGSelectDO_date_return_fg($ckey, $cdate1, $cdate2);
			} else {
				if($csup!='-'){
					$rs = $this->RCV_mod->MGSelectDOSup_return_fg($ckey, $csup);
				} else {					
					$rs = $cdatefilter !='' ? $this->RCV_mod->MGSelectDO_date_return_fg($ckey, $cdate1, $cdate2) :
					 $this->RCV_mod->MGSelectDO_return_fg($ckey);
				}
			}
		} else {			
			$rs = $csup!='-' ? $this->RCV_mod->MGSelectDObyItemSup_return_fg($ckey, $csup) : 
				$this->RCV_mod->MGSelectDObyItem_return_fg($ckey);			
		}		
		echo json_encode($rs);
	}

	public function GetDO_split(){
		header('Content-Type: application/json');
		$ckey = $this->input->get('inid');
		$cby = $this->input->get('inby');		
		$rs = $cby=='do' ? $this->RCV_mod->SelectDO_split($ckey) : $this->RCV_mod->SelectDObyItem_split($ckey);		
		echo json_encode($rs);
	}

	public function get_rank(){
		$cdo = $this->input->get('indo');
		$rs = $this->RCV_mod->select_rank($cdo);
		echo '{"data":'.json_encode($rs).'}';		
	}

	public function print_serah_terima_return(){
		if(isset($_COOKIE["CKPSI_RADOC"])){
            $pid = $_COOKIE["CKPSI_RADOC"];
		} else {
			exit('nothing to be printed');
		}
		$COLUMNS = ['RTRIM(MITM_ITMCD) MITM_ITMCD', 'RTRIM(MITM_ITMD1) MITM_ITMD1', 'RTRIM(MITM_STKUOM) MITM_STKUOM', 'RETFG_QTY', 'RETFG_RMRK'
		, 'RETFG_CUSCD', 'RETFG_PLANT', 'RETFG_LINE', 'RETFG_STRDOC' , 'RETFG_STRDT', 'CONVERT(DATE,ISUDT) STKTRND1_ISUDT'];
		$rs = $this->RETFG_mod->select_cols_where($COLUMNS, ['RETFG_DOCNO' => $pid]);
		$h_no_serahterima = '';
		$h_tgl_serahterima ='';
		$h_tgl_isu ='';
		foreach($rs as $r){
			$h_no_serahterima = $r['RETFG_STRDOC'];
			$tgl = date_create($r['RETFG_STRDT']);
			$h_tgl_serahterima = date_format($tgl, "d-M-Y");
			$tgl = date_create($r['STKTRND1_ISUDT']);
			$h_tgl_isu = date_format($tgl, "d-M-Y");
			break;
		}
		$pdf = new PDF_Code39e128('P','mm','A4');
		$pdf->AddPage();		
		$wid_p = $pdf->GetPageWidth();
		$hight_p = $pdf->GetPageHeight();
		$pdf->SetAutoPageBreak(true,1);
		$pdf->SetFont('Arial','BU',16);
		$DOCTITLE = "SERAH TERIMA RETURN";
		$strwidth = $pdf->GetStringWidth($DOCTITLE);
		//header
		$pdf->SetFont('Arial','BU',16);
		$pdf->SetXY(($wid_p /2) - $strwidth/2,20);
		$pdf->Cell($strwidth,4,$DOCTITLE,0,0,'C');
		$pdf->SetFont('Arial','B',11);
		$pdf->SetXY(10,30);
		$pdf->Cell(10,4,'NO',0,0,'L');
		$pdf->Cell(50,4,': '.$h_no_serahterima,0,0,'L');
		$pdf->SetXY(10,34);
		$pdf->Cell(10,4,'TGL',0,0,'L');
		$pdf->Cell(50,4,': '.$h_tgl_serahterima,0,0,'L');
		
		$pdf->SetXY(103,30);
		$pdf->Cell(43,4,'NO REJECT ADVICE',0,0,'L');
		$pdf->Cell(50,4,': '.$pid,0,0,'L');
		$pdf->SetXY(103,34);
		$pdf->Cell(43,4,'TGL REJECT ADVICE',0,0,'L');
		$pdf->Cell(50,4,': '.$h_tgl_isu,0,0,'L');

		$pdf->SetFont('Arial','B',10);
		$pdf->setFillColor(217,217,217); 
		$pdf->SetXY(10,40);
		$pdf->Cell(10,7,'NO',1,0,'C',1);
		$pdf->Cell(35,7,'ASSY CODE',1,0,'C',1);
		$pdf->Cell(67,7,'MODEL',1,0,'C',1);
		$pdf->Cell(20,7,'QTY (PCS)',1,0,'C',1);
		$pdf->Cell(25,7,'CUSTOMER',1,0,'C',1);
		$pdf->Cell(27,7,'KETERANGAN',1,0,'C',1);

		$pdf->setFillColor(255,255,255);
		$pdf->SetFont('Arial','',10);
		//end header
		$Y = 47;
		$no = 1;
		$ttlqty = 0;
		foreach($rs as $r){
			if($Y > ($hight_p*0.9)) {
				$pdf->AddPage();
				//header
				$pdf->SetFont('Arial','BU',16);
				$pdf->SetXY(($wid_p /2) - $strwidth/2,20);
				$pdf->Cell($strwidth,4,$DOCTITLE,0,0,'C');
				$pdf->SetFont('Arial','B',11);
				$pdf->SetXY(10,30);
				$pdf->Cell(10,4,'NO',0,0,'L');
				$pdf->Cell(50,4,': '.$h_no_serahterima,0,0,'L');
				$pdf->SetXY(10,34);
				$pdf->Cell(10,4,'TGL',0,0,'L');
				$pdf->Cell(50,4,': '.$h_tgl_serahterima,0,0,'L');
				
				$pdf->SetXY(103,30);
				$pdf->Cell(43,4,'NO REJECT ADVICE',0,0,'L');
				$pdf->Cell(50,4,': '.$pid,0,0,'L');
				$pdf->SetXY(103,34);
				$pdf->Cell(43,4,'TGL REJECT ADVICE',0,0,'L');
				$pdf->Cell(50,4,': '.$h_tgl_isu,0,0,'L');

				$pdf->SetFont('Arial','B',10);
				$pdf->setFillColor(217,217,217); 
				$pdf->SetXY(10,40);
				$pdf->Cell(10,7,'NO',1,0,'C',1);
				$pdf->Cell(35,7,'ASSY CODE',1,0,'C',1);
				$pdf->Cell(67,7,'MODEL',1,0,'C',1);
				$pdf->Cell(20,7,'QTY (PCS)',1,0,'C',1);
				$pdf->Cell(25,7,'CUSTOMER',1,0,'C',1);
				$pdf->Cell(27,7,'KETERANGAN',1,0,'C',1);

				$pdf->setFillColor(255,255,255);
				$pdf->SetFont('Arial','',10);
				//end header
				$Y = 47;
			}
			$pdf->SetXY(10,$Y);
			$pdf->Cell(10,5,$no,1,0,'C',1);
			$pdf->Cell(35,5,$r['MITM_ITMCD'],1,0,'C',1);
			$pdf->Cell(67,5,$r['MITM_ITMD1'],1,0,'L',1);
			$pdf->Cell(20,5,number_format($r['RETFG_QTY']),1,0,'R',1);
			$pdf->Cell(25,5,$r['RETFG_PLANT'],1,0,'C',1);
			$pdf->Cell(27,5,$r['RETFG_RMRK'],1,0,'C',1);
			$Y+=5; $no++; $ttlqty+=$r['RETFG_QTY'];
		}
		$pdf->SetFont('Arial','B',10);
		$pdf->SetXY(10,$Y);
		$pdf->Cell(10,5,"",1,0,'C',1);
		$pdf->Cell(102,5,"TOTAL QTY",1,0,'C',1);	
		$pdf->SetFont('Arial','',10);	
		$pdf->Cell(20,5,number_format($ttlqty),1,0,'R',1);
		$pdf->Cell(25,5,'',1,0,'C',1);
		$pdf->Cell(27,5,'',1,0,'C',1);
		$Y=250;		
		$pdf->SetFont('Arial','',7);
		$pdf->SetXY(10,$Y);
		$pdf->Cell(25,5,"PREPARED",1,0,'C',1);
		$pdf->Cell(25,5,"CHECKED",1,0,'C',1);
		$pdf->Cell(25,5,"APPROVED",1,0,'C',1);
		$pdf->SetXY(10,$Y+5);
		$pdf->Cell(25,20,"",1,0,'C',1);
		$pdf->Cell(25,20,"",1,0,'C',1);
		$pdf->Cell(25,20,"",1,0,'C',1);
		$pdf->SetXY(10,$Y+25);
		$pdf->Cell(25,10,"",1,0,'C',1);
		$pdf->Cell(25,10,"",1,0,'C',1);
		$pdf->Cell(25,10,"",1,0,'C',1);
		$pdf->SetXY(10,$Y+25);
		$pdf->Cell(8,5,"NAME",0,0,'L',0);
		$pdf->Cell(17,5,":",0,0,'L',0);
		$pdf->Cell(8,5,"NAME",0,0,'L',0);
		$pdf->Cell(17,5,":",0,0,'L',0);
		$pdf->Cell(8,5,"NAME",0,0,'L',0);
		$pdf->Cell(17,5,":",0,0,'L',0);
		$pdf->SetXY(10,$Y+30);
		$pdf->Cell(8,5,"DATE",0,0,'L',0);
		$pdf->Cell(17,5,":",0,0,'L',0);
		$pdf->Cell(8,5,"DATE",0,0,'L',0);
		$pdf->Cell(17,5,":",0,0,'L',0);
		$pdf->Cell(8,5,"DATE",0,0,'L',0);
		$pdf->Cell(17,5,":",0,0,'L',0);

		$Y=250;				
		$pdf->SetXY(150,$Y);
		$pdf->Cell(25,5,"RECEIVED BY",1,0,'C',1);	
		$pdf->SetXY(150,$Y+5);
		$pdf->Cell(25,20,"",1,0,'C',1);
		$pdf->SetXY(150,$Y+25);
		$pdf->Cell(25,10,"",1,0,'C',1);
		$pdf->SetXY(150,$Y+25);
		$pdf->Cell(8,5,"NAME",0,0,'L',0);
		$pdf->Cell(17,5,":",0,0,'L',0);
		$pdf->SetXY(150,$Y+30);
		$pdf->Cell(8,5,"DATE",0,0,'L',0);
		$pdf->Cell(17,5,":",0,0,'L',0);
		
		$pdf->Output('I','Serah Terima Docs '.$pid.'.pdf');		
	}

	public function sync_rank(){
		$this->checkSession();
		header('Content-Type: application/json');
		date_default_timezone_set('Asia/Jakarta');
		$currrtime = date('Y-m-d H:i:s');
		$cdo = $this->input->post('indo');
		$cdon = $cdo."-RNK";
		if($this->RCV_mod->check_Primary(["RCV_DONO" => $cdon]) >0){
			$this->RCV_mod->deletebyDO($cdon);
		}
		$rs = $this->RCV_mod->select_rank($cdo);
		$myar = [];
		$thewh = '';
		$bcdate = '';
		$rsminusmain = [];
		$formatyear = '';
		$formatmonth = '';
		$formatdate = '';
		$nourut = 1;
		foreach($rs as $r){
			$thewh = $r['RCV_WH'];
			$bcdate = $r['RCV_BCDATE'];
			$adate = explode('-',$r['RCV_BCDATE']);
			$formatyear = substr($adate[0],-2);
			$formatmonth = $adate[1];
			$formatdate = $adate[2];
			$rw = [
				"RCV_PO" => trim($r['RCV_PO']),
				"RCV_ITMCD" => trim($r['PGRELED_ITMCD']),
				"RCV_ITMCD_REFF" => trim($r['RCV_ITMCD']),
				"RCV_DONO" => $cdon,
				"RCV_RCVDATE" => $r['RCV_RCVDATE'],
				"RCV_DONO_REFF" => $cdo,
				"RCV_QTY" => $r['PGRELED_GRDQT'],
				"RCV_SUPCD" => $r['RCV_SUPCD'],
				"RCV_RPNO" => $r['RCV_RPNO'],
				"RCV_GRLNO" => $r['PGRELED_GRLNO'],
				"RCV_WH" => $r['RCV_WH'],
				"RCV_BSGRP" => $r['RCV_BSGRP'],
				"RCV_BCDATE" => $r['RCV_BCDATE'],
				"RCV_RPDATE" => $r['RCV_RPDATE'],
				"RCV_BCTYPE" => $r['RCV_BCTYPE'],
				"RCV_BCNO" => $r['RCV_BCNO'],
				"RCV_LUPDT" => $currrtime,
				"RCV_USRID" => $this->session->userdata('nama')
			];
			$myar[] = $rw;
			$key =[
				'ITH_ITMCD' => $r['RCV_ITMCD'],
				'ITH_QTY' => -$r['PGRELED_GRDQT'],
				'ITH_DOC' => $cdo,
				'ITH_WH' => $thewh,
				'ITH_DATE' => $r['RCV_BCDATE'],
				'ITH_LUPDT' => $r['RCV_BCDATE']." 08:00:00",
				'ITH_LINE' => 'ITH'.$formatyear.$formatmonth.$formatdate.$nourut,
				'ITH_FORM' => 'ADJ-OUT'
			];
			if(!$this->ITH_mod->check_Primary($key)){
				$rsminusmain[] = $key;
			}
			$nourut++;			
		}		
		$res = $this->RCV_mod->insertb($myar);		
		$this->toITH(['DOC' => $cdon, 'WH' => $thewh
		, 'DATE' => $bcdate , 'LUPDT' => $bcdate.' 07:01:00'
		, 'USRID' => $this->session->userdata('nama')]);
		if(count($rsminusmain)>0){
			$this->ITH_mod->insertb($rsminusmain);
		}
		$todis = [];
		$rdis = $res>0 ? ['cd' => $res, 'msg'=> "New DO will be $cdon "] : ['cd' => 0, 'msg'=> "Please contact your Admin"];		
		$todis[] = $rdis;
		echo json_encode($todis);
	}

	public function MGGetDODetail(){
		header('Content-Type: application/json');
		$cdo = $this->input->get('indo');
		$cbisgrup = $this->input->get('inbisgrup');
		$rs = $this->RCV_mod->MGSelectDODetail(['PGRN_SUPNO' => $cdo, 'PGRN_BSGRP' => $cbisgrup]);
		foreach($rs as &$r){
			$dataw = [
				'RCV_DONO' => trim($cdo),
				'RCV_ITMCD' => trim($r['PGRN_ITMCD'])
			];
			$r['SYNC_STS'] = ($this->RCV_mod->check_Primary($dataw) > 0) ? 'Synchronized': 'Not yet';

			//SAVED CUSTOMS DOC
			$dataw = [
				'RCV_DONO' => trim($cdo),
				'RCV_ITMCD' => trim($r['PGRN_ITMCD']),				
				'RCV_PO' => trim($r['PGRN_PONO']),		
				'RCV_GRLNO' => trim($r['PGRN_GRLNO'])			
			];
			$rs2 = $this->RCV_mod->selectbypar($dataw);
			$r['PROFNO'] = '';
			$r['HSCD'] = '';
			$r['HSCD'] = '';
			$r['BEAMASUK'] = 0;
			$r['PPN'] = 0;
			$r['PPH'] = 0;
			$r['NOURUT'] = '';
			foreach($rs2 as $r2){				
				$r['PROFNO'] = $r2['RCV_BCNO'];
				$r['HSCD'] = $r2['RCV_HSCD'];
				$r['BEAMASUK'] = $r2['RCV_BM'] ? $r2['RCV_BM'] : 0;
				$r['PPN'] = $r2['RCV_PPN'] ? $r2['RCV_PPN'] : 0;
				$r['PPH'] = $r2['RCV_PPH'] ? $r2['RCV_PPH'] : 0;
				$r['NOURUT'] = $r2['RCV_ZNOURUT'] ? $r2['RCV_ZNOURUT'] : '';
			}
		}
		unset($r);
		echo json_encode($rs);
	}
	public function MGGetDODetailReturn(){
		header('Content-Type: application/json');
		$cdo = $this->input->get('indo');
		$rs	= $this->RCV_mod->MGSelectDODetailReturn($cdo);
		$plant = '';
		$customer_cd = '';
		$customer_name = '';
		$customer_currency = '';
		foreach($rs as $r){
			$plant = $r['PLANT'] =='IEI' ? 'IEP' : $r['PLANT'] ;
			break;
		}
		$rscustomer = $this->MSTCUS_mod->selectbycd_lk($plant);
		foreach($rscustomer as $r){
			$customer_cd = $r->MCUS_CUSCD;
			$customer_name = $r->MCUS_CUSNM;
			$customer_currency = $r->MCUS_CURCD;
		}

		foreach($rs as &$r){
			$dataw = [
				'RCV_DONO' => trim($cdo),
				'RCV_ITMCD' => trim($r['STKTRND2_ITMCD'])
			];
			$r['SYNC_STS'] = ($this->RCV_mod->check_Primary($dataw) > 0) ? 'Synchronized': 'Not yet';

			//SAVED CUSTOMS DOC
			$dataw = [
				'RCV_DONO' => trim($cdo),
				'RCV_ITMCD' => trim($r['STKTRND2_ITMCD']),									
			];
			$rs2 = $this->RCV_mod->selectbypar($dataw);
			$r['PROFNO'] = '';
			$r['HSCD'] = '';
			$r['HSCD'] = '';
			$r['BEAMASUK'] = 0;
			$r['PPN'] = 0;
			$r['PPH'] = 0;
			$r['NOURUT'] = '';
			$r['MCUS_CUSCD'] = $customer_cd;
			$r['MCUS_CUSNM'] = $customer_name;
			$r['MCUS_CURCD'] = $customer_currency;
			foreach($rs2 as $r2){
				$r['PROFNO'] = $r2['RCV_BCNO'];
				$r['HSCD'] = $r2['RCV_HSCD'];
				$r['BEAMASUK'] = $r2['RCV_BM'] ? $r2['RCV_BM'] : 0;
				$r['PPN'] = $r2['RCV_PPN'] ? $r2['RCV_PPN'] : 0;
				$r['PPH'] = $r2['RCV_PPH'] ? $r2['RCV_PPH'] : 0;
				$r['NOURUT'] = $r2['RCV_ZNOURUT'] ? $r2['RCV_ZNOURUT'] : '';
			}
		}
		unset($r);
		echo json_encode($rs);
	}
	public function GetDODetail_split(){
		header('Content-Type: application/json');
		$cdo	= $this->input->get('indo');
		$rs		= $this->RCV_mod->SelectDODetail_split($cdo);	
		echo '{"data":'.json_encode($rs).'}';		
	}

	public function WMSGetDODetail(){
		$cdo = $this->input->get('indo');
		$cbisgrup = $this->input->get('inbisgrup');
		$dataw = ['RCV_DONO' => $cdo, 'RCV_BSGRP' => $cbisgrup];
		$rs = $this->RCV_mod->selectbypar($dataw);
		echo json_encode($rs);
	}

	public function getDO(){
		header('Content-Type: application/json');
		$cwo	= $this->input->get('inwo');
		$rs		= $this->RCV_mod->selectDO($cwo);
		echo json_encode($rs);
	}

	public function searchDO(){
		$cdo = $this->input->get('indo');
		$csearch_type = $this->input->get('insearchtype');
		$rs = $csearch_type=='all' ? $this->RCV_mod->select_sp_searchdo_all($cdo) : $this->RCV_mod->select_sp_searchdo_open($cdo);
		echo '{"data":'.json_encode($rs).'}';		
	}

	public function vrevise_lot(){
		$this->load->view('wms/vrevise_lotno');
	}

	public function revise_lot(){
		header('Content-Type: application/json');
		$cid = $this->input->post('inid');
		$cnewlot = $this->input->post('innewlot');
		$cnt = count($cid);
		$myar = [];
		$affected = 0;
		for($i=0;$i<$cnt;$i++){
			$affected += $this->RCVSCN_mod->updatebyrowId($cid[$i], ['RCVSCN_LOTNO' => $cnewlot[$i]]);
		}
		if($affected>0){
			$myar[] = ['cd' => 1, 'msg' => 'Revised successfully'];
		} else {
			$myar[] = ['cd' => 0, 'msg' => 'could not update'];
		}
		echo '{"status": '.json_encode($myar).'}';
	}

	public function vreport_pab(){
		$data['lsup'] = $this->MSTSUP_mod->select3();
		$data['ltpb_type'] = $this->MTPB_mod->selectAll();
		$rs = $this->MMDL_mod->select_all(['MMDL_CD', 'MMDL_NM']);
		$strmdl = '';
		foreach($rs as $r){
			$strmdl .= "<option value='".$r['MMDL_CD']."'>".$r['MMDL_NM']."</option>";
		}
		$data['modell'] = $strmdl;
		$this->load->view('wms_report/vrpt_pab_inc', $data);
	}	

	public function searchby_do_lot(){
		header('Content-Type: application/json');
		$csearch = $this->input->get('insearch');
		$csearch_by = $this->input->get('insearchby');
		$rs = $csearch_by=='do' ? $this->RCVSCN_mod->select_DOCUST_byDO($csearch) : $this->RCVSCN_mod->select_DOCUST_byLOT($csearch);
		$myar = [];
		if(count($rs)>0){
			$myar[] = ['cd' => 1, 'msg' => 'go ahead'];
		} else {
			$myar[] = ['cd' => 0, 'msg' => 'not found'];
		}
		exit('{"status" : '.json_encode($myar).', "data" : '.json_encode($rs).'}');
	}

	public function search_scn_orderbyitemlot(){
		header('Content-Type: application/json');
		$cdo = $this->input->get('indo');
		$citemcd = $this->input->get('initemcd');
		$rs = $this->RCVSCN_mod->selectby_filter_orderby_itemlot(['RCVSCN_DONO' => $cdo, 'RCVSCN_ITMCD' => $citemcd]);
		$myar = [];
		if(count($rs)>0){
			$myar[] = ['cd' => 1, 'msg' => 'go ahead'];
		} else {
			$myar[] = ['cd' => 0, 'msg' => 'not found'];
		}
		exit('{"status" : '.json_encode($myar).', "data" : '.json_encode($rs).'}');
	}

	public function dr_pab_inc(){
		$cdoctype = $this->input->get('indoctype');
		$ctpbtype = $this->input->get('intpbtype');
		$citmcd = $this->input->get('initmcd');
		$csup = $this->input->get('insup');
		$cdate0 = $this->input->get('indate0');
		$cdate1 = $this->input->get('indate1');
		$cnoaju = $this->input->get('innoaju');
		$cstatus = $this->input->get('instatus');
		$itemtype = $this->input->get('itemtype');
		if($csup=='-'){$csup='';}
		if($cdoctype=='-'){$cdoctype='';}
		if($ctpbtype=='-'){$ctpbtype='';}
		if($cstatus=='-'){$cstatus='';}
		if($itemtype=='-'){$itemtype='';}
		$rs = $this->RCV_mod->select_sp_report_inc_pab($cdoctype, $ctpbtype, $citmcd, $csup, $cdate0, $cdate1, $cnoaju, $cstatus, $itemtype);		
		echo '{"data":'.json_encode($rs).'}';
	}

	public function get_customer(){
		header('Content-Type: application/json');
		$cbg = $this->input->post('inbg');
		$rs = $this->XSO_mod->selectcustomer_so($cbg);
		$rs_j = [];
		foreach($rs as $r){
			$rs_j[] = [
				'id' => trim($r['SSO2_CUSCD'])
				,'text' => trim($r['MCUS_CUSNM'])
			];
		}
		exit(json_encode($rs_j));
	}

	public function get_strlocation(){
		header('Content-Type: application/json');
		$cbg = $this->input->post('inbg');
		$ccustomer = $this->input->post('incust');
		$rs = $this->SI_mod->select_location($cbg, $ccustomer);
		$rs_j = [];
		foreach($rs as $r){
			$rs_j[] = [
				'id' => trim($r['SI_OTHRMRK'])
				,'text' => trim($r['SI_OTHRMRK'])			
			];
		}
		exit(json_encode($rs_j));
	}

	public function get_fg(){
		header('Content-Type: application/json');
		$cdo = $this->input->post('indo');
		$rs = $this->STKTRN_mod->select_itemonly($cdo);
		$rs_j = [];
		foreach($rs as $r){
			$rs_j[] = [
				'id' => trim($r['STKTRND2_ITMCD'])
				,'text' => trim($r['STKTRND2_ITMCD'])
				,'description' => trim($r['MITM_ITMD1'])
				,'um' => trim($r['MITM_STKUOM'])
				,'maxqty' => $r['TRNQT']
			];
		}
		exit(json_encode($rs_j));
	}

	public function dr_pab_inc_as_excel(){
		date_default_timezone_set('Asia/Jakarta');
		$currdate = date('d-m-Y');
		$cdoctype = isset($_COOKIE["RP_PAB_DOCTYPE"]) ? $_COOKIE["RP_PAB_DOCTYPE"] : '';
		$ctpbtype = isset($_COOKIE["RP_PAB_TPBTYPE"]) ? $_COOKIE["RP_PAB_TPBTYPE"] : '';
		$ctpbtypes = isset($_COOKIE["RP_PAB_TPBTYPES"]) ? $_COOKIE["RP_PAB_TPBTYPES"] : '';
		$citmcd = isset($_COOKIE["RP_PAB_ITMCD"]) ? $_COOKIE["RP_PAB_ITMCD"] : '';
		$csup = isset($_COOKIE["RP_PAB_SUP"]) ? $_COOKIE["RP_PAB_SUP"] : '';
		$cdate0 = isset($_COOKIE["RP_PAB_DATE0"]) ? $_COOKIE["RP_PAB_DATE0"] : '';
		$cdate1 = isset($_COOKIE["RP_PAB_DATE1"]) ? $_COOKIE["RP_PAB_DATE1"] : '';
		$cnoaju = isset($_COOKIE["RP_PAB_NOAJU"]) ? $_COOKIE["RP_PAB_NOAJU"] : '';
		$cstatus = isset($_COOKIE["RP_PAB_RCVSTATUS"]) ? $_COOKIE["RP_PAB_RCVSTATUS"] : '';
		$citmtype = isset($_COOKIE["RP_PAB_ITMTYPE"]) ? $_COOKIE["RP_PAB_ITMTYPE"] : '';
		if($csup=='-'){$csup='';}
		if($cdoctype=='-'){$cdoctype='';}
		if($ctpbtype=='-'){$ctpbtype='';}
		if($cstatus=='-'){$cstatus='';}
		if($citmtype=='-'){$citmtype='';}
		$rs = $this->RCV_mod->select_sp_report_inc_pab($cdoctype, $ctpbtype, $citmcd, $csup, $cdate0, $cdate1, $cnoaju, $cstatus, $citmtype);	
		$stringjudul = 'PEMBUKUAN MASUK';
		$spreadsheet = new Spreadsheet();
		$sheet = $spreadsheet->getActiveSheet();
		$sheet->setTitle('SHEETMASUK');
		$sheet->mergeCells('C1:O1');
		$sheet->setCellValueByColumnAndRow(3,1, 'Pembukuan Masuk');
		$sheet->getStyle('C1')->getAlignment()->setHorizontal('center')	;
		$sheet->setCellValueByColumnAndRow(2,2, 'PT. SMT INDONESIA');	
		$sheet->setCellValueByColumnAndRow(2,3, 'Kawasan EJIP Plot 5C2 Cikarang Selatan');
		$sheet->setCellValueByColumnAndRow(2,4, 'PERIOD : '.$cdate0.' to '.$cdate1);
		$sheet->getStyle('R2')->getAlignment()->setHorizontal('right');
		$sheet->setCellValueByColumnAndRow(18,2, 'Print Date : '.$currdate);

		$sheet->setCellValueByColumnAndRow(2,6, 'NO');
		$sheet->mergeCells('C6:E6');
		$sheet->setCellValueByColumnAndRow(3,6, 'DOKUMEN ');
		$sheet->setCellValueByColumnAndRow(6,6, 'PENDAFTARAN');
		$sheet->setCellValueByColumnAndRow(7,6, 'NO. URUT');



		$sheet->mergeCells('H6:H7');
		$sheet->setCellValueByColumnAndRow(8,6, 'URAIAN JENIS BARANG');
		$sheet->mergeCells('I6:I7');
		$sheet->setCellValueByColumnAndRow(9,6, 'KODE BARANG');
		$sheet->mergeCells('J6:J7');
		$sheet->setCellValueByColumnAndRow(10,6, 'HS CODE');
		$sheet->mergeCells('K6:K7');
		$sheet->setCellValueByColumnAndRow(11,6, 'JUMLAH');
		$sheet->mergeCells('L6:L7');
		$sheet->setCellValueByColumnAndRow(12,6, 'SATUAN');
		$sheet->mergeCells('M6:M7');
		$sheet->setCellValueByColumnAndRow(13,6, 'NILAI PABEAN');
		$sheet->mergeCells('N6:N7');
		$sheet->setCellValueByColumnAndRow(14,6, 'BERAT (KG)');
		$sheet->mergeCells('O6:P6');
		$sheet->setCellValueByColumnAndRow(15,6, 'ASAL PERUSAHAAN');
		$sheet->mergeCells('Q6:Q7');
		$sheet->getStyle('B6:R7')->getAlignment()->setHorizontal('center')	;
		$sheet->getStyle('B6:R7')->getAlignment()->setVertical('center');
		$sheet->setCellValueByColumnAndRow(17,6, 'Surat Jalan');
		
		$sheet->mergeCells('R6:R7');
		$sheet->setCellValueByColumnAndRow(18,6, 'Keterangan');
		
		$sheet->setCellValueByColumnAndRow(3,7, 'NOMOR');
		$sheet->setCellValueByColumnAndRow(4,7, 'TANGGAL DOKUMEN');
		$sheet->setCellValueByColumnAndRow(5,7, 'TANGGAL PENERIMAAN');
		$sheet->setCellValueByColumnAndRow(6,7, 'NOMOR');
		$sheet->setCellValueByColumnAndRow(7,7, 'BARANG');
		$sheet->setCellValueByColumnAndRow(15,7, 'NAMA');
		$sheet->setCellValueByColumnAndRow(16,7, 'ALAMAT');
		
		$y = 8;
		$mnomor = 0;
		$mnomorin = 0;
		$jmlpab = 0;
		$jmlberat = 0;
		$mnomordis = $mnomorpab = $mnomorpabdis = $mdatepabdis = $mdatercv = $mnilaipab = $mnilaipabdis = $mberatpab = $mberatpabdis = $msup = $msupdis = $malam = $malamdis = $mdo= $mdodis = $nopen = '' ;
		$flgcolor = '';
		foreach ($rs as $r){
			if($mnomorpab != $r['RCV_RPNO']){
				$flgcolor = 'b';
				$mnomorpab = $r['RCV_RPNO'];
				$mnomor++;
				$mnomordis = $mnomor;
				$mnomorpabdis =$mnomorpab;
				$mdatepabdis = $r['RCV_RPDATE'];
				$mdatercv = $r['RCV_RCVDATE'];
				$mnomorin=1; 
				$mnilaipab = $r['RCV_TTLAMT'];
				$mnilaipabdis = number_format($mnilaipab, 2);
				$mberatpab = $r['RCV_NW'];
				$mberatpabdis = number_format($mberatpab,2);
				$msup = $r['MSUP_SUPNM'];
				$msupdis = trim($msup);
				$malam = $r['MSUP_ADDR1'];
				$malamdis = $malam;
				$mdo = $r['RCV_DONO'];
				$nopen = $r['RCV_BCNO'];
				$mdodis = trim($mdo);
				$jmlpab += $mnilaipab;
				$jmlberat += $mberatpab;
			} else {
				$flgcolor = 'w';
				$mnomorin++;
				$mnomordis = '';
				//$mnomorpabdis = '';
				$mdatepabdis = '';	
				$mdatercv = '';					
				if($mdo!=$r['RCV_DONO']){
					$mdo = $r['RCV_DONO'];
					$mnilaipab = $r['RCV_TTLAMT'];
					$mberatpab = $r['RCV_NW'];
					$msup = $r['MSUP_SUPNM'];
					$malam = $r['MSUP_ADDR1'];
					$mnilaipabdis = number_format($mnilaipab, 2);
					$mberatpabdis = number_format($mberatpab,2);
					$msupdis = trim($msup);
					$malamdis = $malam;
					$mdodis = trim($mdo);
				} else {
					$mnilaipabdis = '';
					$mberatpabdis = '';
					$msupdis='';
					$malamdis = '';	
					$mdodis = '';
				}
			}
			$sheet->setCellValueByColumnAndRow(2,$y, $mnomordis);
			if($flgcolor=='w'){
				$sheet->getStyle('C'.$y)->getFont()->getColor()->setARGB(\PhpOffice\PhpSpreadsheet\Style\Color::COLOR_WHITE);
			}
			$sheet->setCellValueByColumnAndRow(3,$y, $mnomorpabdis);
			$sheet->setCellValueByColumnAndRow(4,$y, $mdatepabdis);
			$sheet->setCellValueByColumnAndRow(5,$y, $mdatercv);
			$sheet->setCellValueByColumnAndRow(6,$y, $nopen);
			$sheet->setCellValueByColumnAndRow(7,$y, $mnomorin);
			$sheet->setCellValueByColumnAndRow(8,$y, trim($r['MITM_ITMD1']));
			$sheet->setCellValueByColumnAndRow(9,$y, trim($r['RCV_ITMCD']));
			$sheet->setCellValueByColumnAndRow(10,$y, $r['RCV_HSCD']);
			$sheet->setCellValueByColumnAndRow(11,$y, number_format($r['RCV_QTY']));
			$sheet->setCellValueByColumnAndRow(12,$y, trim($r['MITM_STKUOM']));
			$sheet->setCellValueByColumnAndRow(13,$y, $mnilaipabdis);
			$sheet->setCellValueByColumnAndRow(14,$y, $mberatpabdis);
			$sheet->setCellValueByColumnAndRow(15,$y, $msupdis);
			$sheet->setCellValueByColumnAndRow(16,$y, trim($malamdis));
			$sheet->setCellValueByColumnAndRow(17,$y, $mdodis);
			$sheet->setCellValueByColumnAndRow(18,$y, $r['URAIAN_TUJUAN_PENGIRIMAN']);
			$y++;
		}
		
		$sheet->getStyle('B8:J'.$y)->getAlignment()->setHorizontal('center');
		$sheet->getStyle('K8:K'.$y)->getAlignment()->setHorizontal('right');
		$sheet->getStyle('L8:L'.$y)->getAlignment()->setHorizontal('center');
		$sheet->getStyle('M8:M'.$y)->getAlignment()->setHorizontal('right');
		$sheet->getStyle('H8:H'.$y)->getAlignment()->setHorizontal('left');
		
		$sheet->mergeCells('H'.($y+1).':J'.($y+1));
		$sheet->getStyle('H'.($y+1).':H'.($y+1))->getAlignment()->setHorizontal('center');
		$sheet->setCellValueByColumnAndRow(8,($y+1), 'Jumlah');
		$sheet->setCellValueByColumnAndRow(13,($y+1), number_format($jmlpab,2));
		$sheet->setCellValueByColumnAndRow(14,($y+1), number_format($jmlberat,2));
		
		$sheet->getStyle('B2:B4')->getFont()->setBold(true);
		$sheet->getStyle('C1:C1')->getFont()->setBold(true);
		$BStyle = [
			'borders' => [
			  'outline' => [
				'borderStyle' => \PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THICK
			  ]
			]
		];
		$sheet->getStyle('B6:R'.$y)->applyFromArray($BStyle);
		$sheet->freezePane('F8');
		$sheet->getPageSetup()->setOrientation(\PhpOffice\PhpSpreadsheet\Worksheet\PageSetup::ORIENTATION_LANDSCAPE);
		$sheet->getPageSetup()->setPaperSize(\PhpOffice\PhpSpreadsheet\Worksheet\PageSetup::PAPERSIZE_A4);
		foreach(range("C","R") as $r) {
			$sheet->getColumnDimension($r)->setAutoSize(true);
		}
		$sheet->getStyle('B6:R7')->getFill()->setFillType(\PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID)->getStartColor()->setARGB('d4d4d4');
		$sheet->removeColumn('A');
		$writer = new Xlsx($spreadsheet);		
		$filename='Export '.$stringjudul.date(' H i'); //save our workbook as this file name
		
		header('Content-Type: application/vnd.ms-excel');
		header('Content-Disposition: attachment;filename="'. $filename .'.xlsx"'); 
		header('Cache-Control: max-age=0');
		$writer->save('php://output');
	}

	public function getlinkitemtemplate(){
        $murl   = ((isset($_SERVER['HTTPS']) && $_SERVER['HTTPS'] == "on") ? "https" : "http");
        $murl   .= "://".$_SERVER['HTTP_HOST'];
        echo $murl."/wms/RCV/downloadtemplate";
    }

    function downloadtemplate(){
        $theurl = 'assets/userxls_template/tmpl_hscode_receiving.xls' ;
        force_download($theurl , null );
        echo $theurl;
	}

	public function import(){
		header('Content-Type: application/json');
        $cdo = $this->input->post('indo');
        $citemcode = $this->input->post('initemcode');
		$chscode = $this->input->post('inhscode');
		$cbm = $this->input->post('inbm');
		$cppn = $this->input->post('inppn');
		$cpph = $this->input->post('inpph');
		$cnomor_urut = $this->input->post('innourut');
		$cqty = $this->input->post('inqty');
		$crowid = $this->input->post('inrowid');
		$datac = ['RCV_DONO' => $cdo, 'RCV_ITMCD' => $citemcode];
		$myar = [];
		
        if($this->RCV_mod->check_Primary($datac)==0){          
            $anar = ["indx" => $crowid, "status"=> 'Not found'];
        } else {
			$datau = [
				'RCV_HSCD' => $chscode
				,'RCV_BM' => $cbm
				,'RCV_PPN' => $cppn
				,'RCV_PPH' => $cpph
				,'RCV_ZNOURUT' => $cnomor_urut
			];
			$toret = $this->RCV_mod->updatebyId_new($datau, $cdo, $citemcode, $cqty);
			if($toret>0){
				$anar = ["indx" => $crowid, "status"=> 'Updated'];
			} else {
				$toret = $this->RCV_mod->updatebyId_new_no_qty($datau, $cdo, $citemcode);
				if($toret>0){					
					$anar = ["indx" => $crowid, "status"=> 'Updated 2'];
				} else {					
					$anar = ["indx" => $crowid, "status"=> 'Could not update'];
				}
			}            
        }
        array_push($myar, $anar);
        echo json_encode($myar);
	}

	public function migrate_do(){
		ini_set('max_execution_time', '-1');
		header('Content-Type: application/json');
		date_default_timezone_set('Asia/Jakarta');			
		$currrtime 	= date('Y-m-d H:i:s');
		$rs = $this->RCV_mod->MGSelectDODetail_formigration();
		$rsDO = $this->RCV_mod->DOList_formigration();
		$citem = [];
		$myctr_edited = 0;
		$myctr_saved = 0;
		$cdo = [];
		foreach($rsDO as $b){
			$citem = [];
			$cpo = [];
			$cgrlno = [];
			$cqty = [];
			$cprice = [];
			$camt = [];
			$cwh = [];
			$csup = [];
			$cbisgrup = [];
			foreach($rs as $i){
				if($b['MIGDO']==$i['PGRN_SUPNO']){
					$cpo[] = $i['PGRN_PONO'];
					$cgrlno[] = $i['PGRN_GRLNO'];
					$cqty[] = $i['PGRN_ROKQT'];
					$cprice[] = $i['PGRN_PRPRC'];
					$camt[] = $i['PGRN_AMT'];
					$citem[] = $i['PGRN_ITMCD'];
					$cwh[] = $i['PGRN_LOCCD'];
					$csup[] = $i['PGRN_SUPCD'];
					$cbisgrup[] = $i['PGRN_BSGRP'];
				}
			}
			$ttlar = count($cpo);
			if($ttlar>0){				
				$cdo[] = $b['MIGDO'];
			}
			for($i=0;$i<$ttlar;$i++){
				$dataw = [
					'RCV_PO' => $cpo[$i],
					'RCV_DONO' => $b['MIGDO'],
					'RCV_ITMCD' => $citem[$i],
					'RCV_GRLNO' => $cgrlno[$i]					
				];
				if($this->RCV_mod->check_Primary($dataw)>0){
					$datau = [
						'RCV_RPNO' => $b['MIGAJU'],
						'RCV_RPDATE' => $b['MIGTGLPEN'],
						'RCV_BCTYPE' => $b['MIGBCTYPE'],
						'RCV_BCNO' => $b['MIGNOPEN'],
						'RCV_BCDATE' => $b['MIGTGLPEN'],
						'RCV_QTY' => $cqty[$i],
						'RCV_PRPRC' => $cprice[$i],
						'RCV_AMT' => $camt[$i],
						'RCV_TTLAMT' => NULL,
						'RCV_TPB' => NULL,
						'RCV_WH' => $cwh[$i],
						'RCV_SUPCD' => $csup[$i],
						'RCV_RCVDATE' => NULL,
						'RCV_NW' => NULL,
						'RCV_GW' => NULL,
						'RCV_KPPBC' => $b['KPPBC'],
						'RCV_GRLNO' => $cgrlno[$i],
						'RCV_HSCD' => NULL,
						'RCV_ZSTSRCV' => NULL,
						'RCV_BM' => 0,
						'RCV_PPN' => 10,
						'RCV_PPH' => 2.5,
						'RCV_ZNOURUT' => NULL,
						'RCV_USRID' => 'ane'
					];
					$cret = $this->RCV_mod->updatebyVAR($datau, $dataw);
					$myctr_edited += $cret;					
				} else {
					$datas = [
						'RCV_PO' => $cpo[$i],
						'RCV_DONO' => $b['MIGDO'],
						'RCV_ITMCD' => $citem[$i],
						'RCV_RPNO' => $b['MIGAJU'],
						'RCV_RPDATE' => $b['MIGTGLPEN'],
						'RCV_BCTYPE' => $b['MIGBCTYPE'],
						'RCV_BCNO' => $b['MIGNOPEN'],
						'RCV_BCDATE' => $b['MIGTGLPEN'],
						'RCV_QTY' => $cqty[$i],
						'RCV_PRPRC' => $cprice[$i],
						'RCV_AMT' => $camt[$i],
						'RCV_TTLAMT' => NULL,
						'RCV_TPB' => NULL,
						'RCV_WH' => $cwh[$i],
						'RCV_SUPCD' => $csup[$i],
						'RCV_RCVDATE' => NULL,
						'RCV_NW' => NULL,
						'RCV_GW' => NULL,
						'RCV_KPPBC' => $b['KPPBC'],
						'RCV_GRLNO' => $cgrlno[$i],
						'RCV_HSCD' => NULL,
						'RCV_ZSTSRCV' => NULL,
						'RCV_BM' => 0,
						'RCV_PPN' => 10,
						'RCV_PPH' => 2.5,
						'RCV_ZNOURUT' => NULL,
						'RCV_BSGRP' => $cbisgrup[$i],
						'RCV_LUPDT' => $currrtime,
						'RCV_USRID' => 'ane'
					];
					$cret = $this->RCV_mod->insert($datas);					
					$myctr_saved +=  $cret;
				}
			}			
		}
		die('{"dono":'.json_encode($cdo).'}');
	}

	function osPO() {
		$searchby = $this->input->get('searchby');
		$searchvalue = $this->input->get('searchvalue');
		$bisgrup = $this->input->get('bisgrup');
		$rs = [];
		switch($searchby) {
			case 'nm':
				$rs = $this->PPO_mod->select_os_supplier(['MBSG_BSGRP' => $bisgrup,'MSUP_SUPNM' => $searchvalue]);
				break;
			case 'cd':
				$rs = $this->PPO_mod->select_os_supplier(['MBSG_BSGRP' => $bisgrup,'MSUP_SUPCD' => $searchvalue]);
				break;
			case 'ad':
				$rs = $this->PPO_mod->select_os_supplier(['MBSG_BSGRP' => $bisgrup,'MSUP_ADDR1' => $searchvalue]);
				break;
		}
		die('{"data":'.json_encode($rs).'}');
	}

	function simulatePO() {
		header('Content-Type: application/json');
		$bisgrup = $this->input->post('bisgrup');
		$supcd = $this->input->post('supcd');
		$item = $this->input->post('item');
		$itemqty = $this->input->post('itemqty');
		$itemprice = $this->input->post('itemprice');
		$itemqty_plot = [];
		$itemCount = count($item);
		$rs = $itemCount ? $this->PPO_mod->select_os_item(['PPO2_BSGRP' => $bisgrup, 'PPO2_SUPCD' => $supcd], $item) : [];
		for($i=0; $i< $itemCount; $i++) { 
			$itemqty_plot[] = 0;
		}
		$rsreturn = [];
		for($i=0; $i< $itemCount; $i++) {
			foreach($rs as &$r) {				
				if($item[$i]==$r['PPO2_ITMCD'] && $itemqty[$i] > $itemqty_plot[$i] && $r['BALQTY'] >0) {					
					$balancePlot = $itemqty[$i]-$itemqty_plot[$i];
					$theqty = 0;
					if($balancePlot > $r['BALQTY']) {
						$theqty = $r['BALQTY'];
						$itemqty_plot[$i] += $r['BALQTY'];
						$r['BALQTY'] = 0;
					} else {
						$theqty = $balancePlot;
						$r['BALQTY'] -= $balancePlot;
						$itemqty_plot[$i] += $balancePlot;
					}
					$rsreturn[] = [
						'PO' => $r['PPO2_PONO'],
						'ITEMCD' => $item[$i],
						'ITEMNM' => $r['MITM_ITMD1'],
						'QTY' => $theqty,
						'PRICE' => str_replace('$','',$itemprice[$i]),
						'PRICEMEGA' => substr($r['PPO2_PRPRC'],0,1) === '.' ? '0'.$r['PPO2_PRPRC'] : $r['PPO2_PRPRC']
					];
					if($itemqty[$i]==$itemqty_plot[$i]) {
						break;
					}
				}
			}
			unset($r);			
		}
		for($i=0; $i< $itemCount; $i++) { 
			if ($itemqty[$i] != $itemqty_plot[$i]) {
				$balancePlot = $itemqty[$i]-$itemqty_plot[$i];
				$rsreturn[] = [
					'PO' => '',
					'ITEMCD' => $item[$i],
					'ITEMNM' => '',
					'QTY' => $balancePlot,
					'PRICE' => str_replace('$','',$itemprice[$i]),
					'PRICEMEGA' => 0
				];
			}
		}
		die('{"data":'.json_encode($rsreturn).'}');
	}

	public function savesimulatePO() {
		$invoiceno = $this->input->post('invoiceno');
		$invoicedate = str_replace('-','',$this->input->post('invoicedate'));
		$currency = $this->input->post('currency');
		$ina_po = $this->input->post('ina_po');
		$ina_itemcd = $this->input->post('ina_itemcd');
		$ina_itemnm = $this->input->post('ina_itemnm');
		$ina_spq = $this->input->post('ina_spq');
		$ina_qty = $this->input->post('ina_qty');
		$ina_price = $this->input->post('ina_price');
		$ina_pricem = $this->input->post('ina_pricem');
		$inaCount = count($ina_itemcd);
		$differenceList = [];
		$spreadsheet = new Spreadsheet();		
		$sheet = $spreadsheet->getActiveSheet();
		$sheet->setTitle('DATA');
		$sheet->setCellValueByColumnAndRow(1,2, 'Upload Supplier Incoming GRN Data');
		$sheet->setCellValueByColumnAndRow(1,3, 'PO No');
		$sheet->setCellValueByColumnAndRow(2,3, 'Invoice Date');
		$sheet->setCellValueByColumnAndRow(3,3, 'Part No');
		$sheet->setCellValueByColumnAndRow(4,3, 'Part Name');
		$sheet->setCellValueByColumnAndRow(5,3, 'SPQ');
		$sheet->setCellValueByColumnAndRow(6,3, 'Delivery Qty');
		$sheet->setCellValueByColumnAndRow(7,3, 'Unit Price');
		$sheet->setCellValueByColumnAndRow(8,3, 'Amount');
		$sheet->setCellValueByColumnAndRow(9,3, 'Currency');
		$sheet->setCellValueByColumnAndRow(10,3, 'Invoice No');		
		$sheet->getStyle('A3:J3')->getFill()->setFillType(\PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID)->getStartColor()->setARGB('f0f0f0');
		$sheet->getStyle('A3:J3')->getFont()->setBold(true);
		$y=4;
		$isExist = false;
		for($i=0; $i<$inaCount; $i++) {
			$theprice = str_replace('$', '', $ina_pricem[$i]);
			if($ina_price[$i]!=$ina_pricem[$i]) {
				$differenceList[] = [
					'PO' => $ina_po[$i]
					,'INVOICEDATE' => $invoicedate
					,'PARTCD' => $ina_itemcd[$i]
					,'PARTNM' => $ina_itemnm[$i]
					,'SPQ' => $ina_spq[$i]
					,'QTY' => $ina_qty[$i]
					,'PRICE' => $ina_price[$i]
					,'PRICEM' => $ina_pricem[$i]					
				];
				$sheet->getStyle('A'.$y.':J'.$y)->getFill()->setFillType(\PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID)->getStartColor()->setARGB('fa2c22');
				$isExist = true;
			}
			$sheet->setCellValueByColumnAndRow(1,$y, $ina_po[$i]);
			$sheet->setCellValueByColumnAndRow(2,$y, $invoicedate);
			$sheet->setCellValueByColumnAndRow(3,$y, $ina_itemcd[$i]);
			$sheet->setCellValueByColumnAndRow(4,$y, $ina_itemnm[$i]);
			$sheet->setCellValueByColumnAndRow(5,$y, $ina_spq[$i]);
			$sheet->setCellValueByColumnAndRow(6,$y, $ina_qty[$i]);
			$sheet->setCellValueByColumnAndRow(7,$y, $theprice);
			$sheet->setCellValueByColumnAndRow(8,$y, $theprice * $ina_qty[$i]);
			$sheet->setCellValueByColumnAndRow(9,$y, $currency);
			$sheet->setCellValueByColumnAndRow(10,$y, $invoiceno);
			$y++;
		}		
		if($isExist) {
			$sheet = $spreadsheet->createSheet();
        	$sheet->setTitle('DISCREPANCY');
			$sheet->setCellValueByColumnAndRow(1,3, 'PO No');
			$sheet->setCellValueByColumnAndRow(2,3, 'Invoice Date');
			$sheet->setCellValueByColumnAndRow(3,3, 'Part No');
			$sheet->setCellValueByColumnAndRow(4,3, 'Part Name');
			$sheet->setCellValueByColumnAndRow(5,3, 'SPQ');
			$sheet->setCellValueByColumnAndRow(6,3, 'Delivery Qty');
			$sheet->setCellValueByColumnAndRow(7,3, 'Unit Price');
			$sheet->setCellValueByColumnAndRow(8,3, 'Unit Price Mega');
			$sheet->setCellValueByColumnAndRow(9,3, 'Currency');
			$sheet->setCellValueByColumnAndRow(10,3, 'Invoice No');
			$y=4;
			foreach($differenceList as $r) {
				$sheet->setCellValueByColumnAndRow(1,$y, $r['PO']);
				$sheet->setCellValueByColumnAndRow(2,$y, $invoicedate);
				$sheet->setCellValueByColumnAndRow(3,$y, $r['PARTCD']);
				$sheet->setCellValueByColumnAndRow(4,$y, $r['PARTNM']);
				$sheet->setCellValueByColumnAndRow(5,$y, $r['SPQ']);
				$sheet->setCellValueByColumnAndRow(6,$y, $r['QTY']);
				$sheet->setCellValueByColumnAndRow(7,$y, $r['PRICE']);
				$sheet->setCellValueByColumnAndRow(8,$y, $r['PRICEM']);
				$sheet->setCellValueByColumnAndRow(9,$y, $currency);
				$sheet->setCellValueByColumnAndRow(10,$y, $invoiceno);
				$y++;
			}
		}
		$writer = new Xlsx($spreadsheet);
		$filename=$invoiceno; //save our workbook as this file name
		
		header('Content-Type: application/vnd.ms-excel');
		header('Content-Disposition: attachment;filename="'. $filename .'.xlsx"'); 
		header('Cache-Control: max-age=0');
		$writer->save('php://output');
	}

	public function searchexbc_balance() {
		header('Content-Type: application/json');
		$itemcd = $this->input->post('itemcd');
		$searchby = $this->input->post('searchby');
		$searchval = $this->input->post('searchval');
		$rs = [];
		switch($searchby) {
			case 'itemname':
				$rs = $this->RCV_mod->select_balanceEXBC_like([$itemcd], ['MITM_ITMD1' => $searchval]);break;
			case 'do':
				$rs = $this->RCV_mod->select_balanceEXBC_like([$itemcd], ['RPSTOCK_DOC' => $searchval]);break;
			default:
				$rs = $this->RCV_mod->select_balanceEXBC_like([$itemcd], ['PRICE' => $searchval]);
		}		
		die(json_encode(['data' => $rs]));
	}

	public function balancePK() {
		header('Content-Type: application/json');
		$pknum = $this->input->get('pkno');
		$rs = $this->RCV_mod->select_balanceEXBC_byPK([$pknum]);
		die(json_encode(['data' => $rs]));
	}

	public function simulatefifo(){
		header('Content-Type: application/json');
		$iteml = $this->input->post('iteml');
		$qtyl = $this->input->post('qtyl');
		if(!is_array($iteml)){
			$myar[] = ['cd' => 0, 'msg' => 'could not find that data'];
			die(json_encode(['status' => $myar ]));
		}
		$itemlcount = count($iteml);
		$rsreq = [];
		for($i=0;$i<$itemlcount; $i++){
			$isfound = false;
			foreach($rsreq as &$r) {
				if($iteml[$i] == $r['ITEM']) {
					$isfound=true;
					$r['QTY']+=$qtyl[$i];
					break;
				}
			}
			unset($r);
			if(!$isfound) {
				$rsreq[] = [
					'ITEM' => $iteml[$i], 'QTY' => $qtyl[$i], 'PLOTQT' => 0
				];
			}
		}
		$rs = $this->RCV_mod->select_balanceEXBC_like($iteml);
		// $rs = $this->RCV_mod->select_balanceEXBC_fromBook($iteml, ['RPSTOCK_REMARK' => 'BOOK2021-11-19']);
		$rsfix = [];
		foreach($rsreq as &$r){
			foreach($rs as &$a){
				if($r['ITEM'] == $a['ITMNUM'] && $r['QTY']!=$r['PLOTQT'] && $a['STK']){
					$bal = $r['QTY']-$r['PLOTQT'];
					$theqty = $bal;
					if($bal>$a['STK']){
						$r['PLOTQT']+= $a['STK'];
						$theqty = $a['STK'];
						$a['STK'] = 0;
					} else {
						$a['STK']-=$bal;
						$r['PLOTQT']+=$bal;
					}
					$rsfix[] = [
						'NOAJU' => $a['RPSTOCK_NOAJU']
						,'NOPEN' => $a['RPSTOCK_BCNUM']
						,'DO' => $a['RPSTOCK_DOC']
						,'TGLPEN' => $a['RCV_BCDATE']
						,'ITMNUM' => $a['ITMNUM']
						,'PRICE' => substr($a['PRICE'],0,1) =='.' ? '0'.$a['PRICE'] : $a['PRICE']
						,'QTY' => $theqty
						,'BCTYPE' => $a['RCV_BCTYPE']
					];
				}
			}
			unset($a);
		}
		unset($r);
		$rsNE = []; #EXBC NOT ENOUGH
		foreach($rsreq as $r){
			$bal = $r['QTY']-$r['PLOTQT'];
			if($bal>0) {
				$rsNE[] = [
					'ITMCD' => $r['ITEM'],
					'QTY' => $bal
				];
			}
		}
		die(json_encode(['datar' => $rsreq, 'data' => $rsfix, 'needEXBC' => $rsNE]));
	}

	public function booked_vs_stock(){
		header('Content-Type: application/json');
		$rsbooked = $this->RCV_mod->select_balanceEXBC_fromSCRBook(['21/12/04/0001',
		'21/12/04/0002',
		'21/12/04/0003',
		'21/12/04/0005']);
		$search = "";
		$date0 = '2021-10-31';
		$rsstk = $this->ITH_mod->select_fordispose(['MITM_ITMD1' => $search], $date0);
		foreach($rsstk as &$s){
			$s['STKF'] = 0;
			foreach($rsbooked as &$b){				
				$balancestk = $s['STKQTY']-$s['STKF'];
				if(($b['ITMNUM']==$s['ITH_ITMCD'] || $b['ITMNUM']==$s['MITMGRP_ITMCD'] ) && $balancestk>0 && $b['STK']>0){
					if($balancestk>$b['STK']){
						$s['STKF']+=$b['STK'];
						$b['STK']=0;
					} else {
						$s['STKF']+=$balancestk;
						$b['STK']-=$balancestk;
					}
					if($s['STKQTY']==$s['STKF']){
						break;
					}
				}
			}
			unset($b);
		}
		unset($s);
		die(json_encode(['rsbook' => $rsbooked, 'rsstk' => $rsstk]));
	}
	public function booked_vs_stock_detail(){
		header('Content-Type: application/json');
		$rsbooked = $this->RCV_mod->select_balanceEXBC_fromSCRBook_detail(['21/12/04/0001',
		'21/12/04/0002',
		'21/12/04/0003',
		'21/12/04/0005']);
		$search = "";
		$date0 = '2021-10-31';
		$rsstk = $this->ITH_mod->select_fordispose(['MITM_ITMD1' => $search], $date0);
		$rsFIX = [];
		foreach($rsstk as &$s){
			$s['STKF'] = 0;
			foreach($rsbooked as &$b){				
				$balancestk = $s['STKQTY']-$s['STKF'];
				if(($b['ITMNUM']==$s['ITH_ITMCD'] || $b['ITMNUM']==$s['MITMGRP_ITMCD'] ) && $balancestk>0 && $b['STK']>0){
					$qtyuse = $balancestk;
					if($balancestk>$b['STK']){
						$qtyuse = $b['STK'];
						$s['STKF']+=$b['STK'];
						$b['STK']=0;
					} else {
						$s['STKF']+=$balancestk;
						$b['STK']-=$balancestk;
					}
					$rsFIX[] = [
						'RPSTOCK_BCTYPE' => $b['RPSTOCK_BCTYPE'],
						'RPSTOCK_BCNUM' => $b['RPSTOCK_BCNUM'],
						'RPSTOCK_BCDATE' => $b['RPSTOCK_BCDATE'],
						'RPSTOCK_DOC' => $b['RPSTOCK_DOC'],
						'RPSTOCK_NOAJU' => $b['RPSTOCK_NOAJU'],
						'ITMNUM' => $b['ITMNUM'],
						'QTY' => $qtyuse,
						'RCV_PRPRC' => $b['RCV_PRPRC']
					];
					if($s['STKQTY']==$s['STKF']){
						break;
					}
				}
			}
			unset($b);
		}
		unset($s);
		die(json_encode([			
			 'rsstk' => $rsstk
			,'rsFIX' => $rsFIX
		]));
	}

	public function booked_vs_stock_fg(){
		header('Content-Type: application/json');
		$rsbooked = $this->RCV_mod->select_balanceEXBC_fromSCRBook(['21/12/04/0006','21/12/04/0006-R']);
		$date0 = '2021-10-31';
		$rsstk = $this->ITH_mod->select_fordispose_fromfg($date0);
		foreach($rsstk as &$s){
			$s['STKF'] = 0;
			foreach($rsbooked as &$b){
				$balancestk = $s['STKQTY']-$s['STKF'];
				if(($b['ITMNUM']==$s['ITH_ITMCD'] || $b['ITMNUM']==$s['MITMGRP_ITMCD'] ) && $balancestk>0 && $b['STK']>0){
					if($balancestk>$b['STK']){
						$s['STKF']+=$b['STK'];
						$b['STK']=0;
					} else {
						$s['STKF']+=$balancestk;
						$b['STK']-=$balancestk;
					}
					if($s['STKQTY']==$s['STKF']){
						break;
					}
				}
			}
			unset($b);
		}
		unset($s);
		die(json_encode(['rsbook' => $rsbooked, 'rsstk' => $rsstk]));
	}

	public function booked_vs_stock_detail_fg(){
		header('Content-Type: application/json');
		$rsbooked = $this->RCV_mod->select_balanceEXBC_fromSCRBook_detail(['21/12/04/0006','21/12/04/0006-R']);
		$search = "";
		$date0 = '2021-10-31';
		$rsstk = $this->ITH_mod->select_fordispose_fromfg($date0);
		$rsFIX = [];
		foreach($rsstk as &$s){
			$s['STKF'] = 0;
			foreach($rsbooked as &$b){				
				$balancestk = $s['STKQTY']-$s['STKF'];
				if(($b['ITMNUM']==$s['ITH_ITMCD'] || $b['ITMNUM']==$s['MITMGRP_ITMCD'] ) && $balancestk>0 && $b['STK']>0){
					$qtyuse = $balancestk;
					if($balancestk>$b['STK']){
						$qtyuse = $b['STK'];
						$s['STKF']+=$b['STK'];
						$b['STK']=0;
					} else {
						$s['STKF']+=$balancestk;
						$b['STK']-=$balancestk;
					}
					$rsFIX[] = [
						'RPSTOCK_BCTYPE' => $b['RPSTOCK_BCTYPE'],
						'RPSTOCK_BCNUM' => $b['RPSTOCK_BCNUM'],
						'RPSTOCK_BCDATE' => $b['RPSTOCK_BCDATE'],
						'RPSTOCK_DOC' => $b['RPSTOCK_DOC'],
						'RPSTOCK_NOAJU' => $b['RPSTOCK_NOAJU'],
						'ITMNUM' => $b['ITMNUM'],
						'QTY' => $qtyuse,
						'RCV_PRPRC' => $b['RCV_PRPRC']
					];
					if($s['STKQTY']==$s['STKF']){
						break;
					}
				}
			}
			unset($b);
		}
		unset($s);
		die(json_encode([
			// 'rsbook' => $rsbooked
			 'rsstk' => $rsstk
			,'rsFIX' => $rsFIX
		]));
	}
	public function booked_vs_stock_detail_fg_serial(){
		header('Content-Type: application/json');
		$rsbooked = $this->RCV_mod->select_balanceEXBC_fromSCRBook_detail(['21/12/04/0006','21/12/04/0006-R']);		
		$date0 = '2021-10-31';
		$rsstk = $this->ITH_mod->select_fordispose_fromfg_serial($date0);
		$rsFIX = [];
		foreach($rsstk as &$s){
			$s['STKF'] = 0;
			foreach($rsbooked as &$b){				
				$balancestk = $s['STKQTY']-$s['STKF'];
				if(($b['ITMNUM']==$s['ITH_ITMCD'] || $b['ITMNUM']==$s['MITMGRP_ITMCD'] ) && $balancestk>0 && $b['STK']>0){
					$qtyuse = $balancestk;
					if($balancestk>$b['STK']){
						$qtyuse = $b['STK'];
						$s['STKF']+=$b['STK'];
						$b['STK']=0;
					} else {
						$s['STKF']+=$balancestk;
						$b['STK']-=$balancestk;
					}
					$rsFIX[] = [
						'SER_ITMID' => $s['SER_ITMID'],
						'RPSTOCK_BCTYPE' => $b['RPSTOCK_BCTYPE'],
						'RPSTOCK_BCNUM' => $b['RPSTOCK_BCNUM'],
						'RPSTOCK_BCDATE' => $b['RPSTOCK_BCDATE'],
						'RPSTOCK_DOC' => $b['RPSTOCK_DOC'],
						'RPSTOCK_NOAJU' => $b['RPSTOCK_NOAJU'],
						'ITMNUM' => $b['ITMNUM'],
						'QTY' => $qtyuse,
						'RCV_PRPRC' => $b['RCV_PRPRC']
					];
					if($s['STKQTY']==$s['STKF']){
						break;
					}
				}
			}
			unset($b);
		}
		unset($s);
		die(json_encode([			
			 'rsstk' => $rsstk
			,'rsFIX' => $rsFIX
		]));
	}

	public function del_vs_nextdel(){
		header('Content-Type: application/json');
		$rsbooked = $this->RCV_mod->select_balanceEXBC_fromSCRBook_detail(['0121Z0105']);
		$rsstk = $this->DELV_mod->select_aftersales('0121Z0105', '219487102');
		$rsFIX = [];
		foreach($rsstk as &$s){
			$s['STKF'] = 0;
			foreach($rsbooked as &$b){
				$balancestk = $s['STKQTY']-$s['STKF'];
				if(($b['ITMNUM']==$s['ITH_ITMCD'] || $b['ITMNUM']==$s['MITMGRP_ITMCD'] ) && $balancestk>0 && $b['STK']>0){
					$qtyuse = $balancestk;
					if($balancestk>$b['STK']){
						$qtyuse = $b['STK'];
						$s['STKF']+=$b['STK'];
						$b['STK']=0;
					} else {
						$s['STKF']+=$balancestk;
						$b['STK']-=$balancestk;
					}
					$rsFIX[] = [
						'RPSTOCK_BCTYPE' => $b['RPSTOCK_BCTYPE'],
						'RPSTOCK_BCNUM' => $b['RPSTOCK_BCNUM'],
						'RPSTOCK_BCDATE' => $b['RPSTOCK_BCDATE'],
						'RPSTOCK_DOC' => $b['RPSTOCK_DOC'],
						'RPSTOCK_NOAJU' => $b['RPSTOCK_NOAJU'],
						'ITMNUM' => $b['ITMNUM'],
						'QTY' => $qtyuse,
						'RCV_PRPRC' => $b['RCV_PRPRC']
					];
					if($s['STKQTY']==$s['STKF']){
						break;
					}
				}
			}
			unset($b);
		}
		unset($s);
		die(json_encode([
			// 'rsbook' => $rsbooked
			 'rsstk' => $rsstk
			,'rsFIX' => $rsFIX
		]));
	}	
	
	public function set_nonitem(){
		header('Content-Type: application/json');
		date_default_timezone_set('Asia/Jakarta');
		$donum = $this->input->post('donum');
		$rcvdate = $this->input->post('rcvdate');
		$arowid = $this->input->post('arowid');
		$apo = $this->input->post('apo');
		$aitem = $this->input->post('aitem');
		$aqty = $this->input->post('aqty');
		$createat = date('Y-m-d H:i:s');
		$user = $this->session->userdata('nama');
		$myar = [];
		if(is_array($apo)){
			$ttlupdated = 0;
			$ttlsaved = 0;
			$datalength = count($apo);
			$save = [] ;
			$lastline  = $this->RCVNI_mod->select_maxline($donum)+1;
			for($i=0;$i<$datalength; $i++){
				if($arowid[$i]===''){
					$save[] = [
						'RCVNI_LINE' => $lastline++,
						'RCVNI_PO' => $apo[$i],
						'RCVNI_DO' => $donum,
						'RCVNI_ITMNM' => $aitem[$i],
						'RCVNI_QTY' => $aqty[$i],
						'RCVNI_CRTDBY' => $user,
						'RCVNI_CRTDAT' => $createat,
						'RCVNI_RCVDATE' => $rcvdate
					];
				} else {
					$ttlupdated+=$this->RCVNI_mod->update_where([
						'RCVNI_QTY' => $aqty[$i],
						'RCVNI_LUPDTAT' => $createat,
						'RCVNI_LUPDTBY' => $user
					], [
						'RCVNI_LINE' => $arowid[$i],
						'RCVNI_DO' => $donum
					]);
				}
			}
			if(count($save)){
				$ttlsaved += $this->RCVNI_mod->insertb($save);
			}
			$myar[] = ['cd' => '1', 'msg' => "$ttlsaved saved , $ttlupdated updated"];
		} else {
			$myar[] = ['cd' => '0', 'msg' => 'sorry we could not process'];
		}
		die(json_encode(['status' => $myar]));
	}

	public function search_doni(){
		header('Content-Type: application/json');
		$search = $this->input->get('search');
		$searchBY = $this->input->get('searchBY');
		$rs =  $this->RCVNI_mod->select_header($searchBY === '0' ? ['RCVNI_DO' => $search] : ['MSUP_SUPNM' => $search]);
		die(json_encode(['data' => $rs]));
	}
	
	public function doni(){
		header('Content-Type: application/json');
		$doc = $this->input->get('doc');
		$rs = $this->RCVNI_mod->select_where(['RCVNI_DO' => $doc]);
		die(json_encode(['data' => $rs]));
	}

	public function remove_pkg(){
		header('Content-Type: application/json');
		$rowid = $this->input->post("rowid");
		$doc = $this->input->post("doc");
		$aju = $this->input->post("aju");
		$rtn = $this->RCVPKG_mod->deletebyID(['RCVPKG_AJU' => $aju, 'RCVPKG_LINE' => $rowid, 'RCVPKG_DOC' => $doc]);
		$myar = [];
		$myar[] = $rtn ? ['cd' => '1', 'msg' => 'deleted'] : ['cd' => '0', 'msg' => 'could not be deleted'];
		die(json_encode(['status' => $myar]));
	}

	public function posting40(){
		date_default_timezone_set('Asia/Jakarta');
		header('Content-Type: application/json');
		$createdat = date('Y-m-d');
		$donumber = $this->input->post('donum');
		$aju = $this->input->post('aju');
		$myar = [];
		$rs = $this->RCV_mod->select_for_posting(['RCV_DONO' => $donumber]);		
		$ttldata = count($rs);
		$rsaktivasi = [];
		if($ttldata){			
			$nomorAju = "";
			$statusPengiriman = "";
			$idPengirim = "";
			$namaPengirim = "";
			$alamatPengirim = "";
			$nomorInvoice = "";
			$fakturPajak = "";
			$dodate = "";
			$rskemasan = $this->RCVPKG_mod->select_where(["RCVPKG_JUMLAH_KEMASAN", "RCVPKG_KODE_JENIS_KEMASAN"],['RCVPKG_AJU' => $aju, 'RCVPKG_DOC' => $donumber]);
			$rsaktivasi = $this->AKTIVASIAPLIKASI_imod->selectAll();
			foreach($rs as $r){
				$nomorAju = $r['RCV_RPNO'];
				$idPengirim = str_replace(['.','-'],'',$r['MSUP_TAXREG']);
				$namaPengirim = $r['SUPNM'];
				$alamatPengirim = $r['ADDR'];
				$dodate = $r['RCV_RCVDATE'];
				$statusPengiriman = $r['RCV_ZSTSRCV'];
				$nomorInvoice = $r['RCV_INVNO'];
				$fakturPajak = $r['RCV_TAXINVOICE'];
				break;
			}
			foreach($rsaktivasi as $r){
				$czkantorasal = $r['KPPBC'];
				$czidmodul_asli = $r['ID_MODUL'];
				$czidpengusaha = $r['NPWP'];
				$cznmpengusaha = $r['NAMA_PENGUSAHA'];
				$czalamatpengusaha = $r['ALAMAT_PENGUSAHA'];
				$czizinpengusaha = $r['NOMOR_SKEP'];		
			}
			if(strlen($nomorAju)!=26){
				$myar[] = ['cd' => 0 ,'msg' => 'NOMOR AJU is not valid, please re-check ('.$nomorAju.')'];
				die('{"status" : '.json_encode($myar).'}');
			}
			if($this->TPB_HEADER_imod->check_Primary(['NOMOR_AJU' => $nomorAju])>0){
				$myar[] = ['cd' => 0 ,'msg' => 'the NOMOR AJU is already posted'];
				die('{"status" : '.json_encode($myar).'}');
			}
			
			$tpb_barang = [];
			$seri_barang = 1;
			$hBruto = 0;
			$hNetto = 0;
			$TotalhargaPenyerahan = 0;
			foreach($rs as $r){
				$hargaPenyerahan = $r['RCV_PRPRC']*$r['RCV_QTY'];
				$tpb_barang[] = [
					'KODE_BARANG' => $r['RCV_ITMCD'],
					'URAIAN' => trim($r['MITM_ITMD1']),
					'JUMLAH_SATUAN' => $r['RCV_QTY'],
					'HARGA_PENYERAHAN' => $hargaPenyerahan,
					'SERI_BARANG' => $seri_barang++,
					'KODE_SATUAN' => trim($r['MITM_STKUOM'])=='PCS' ? 'PCE' : trim($r['MITM_STKUOM']),
					'NETTO' => $r['RCV_PRNW']
				];
				$hNetto+=$r['RCV_PRNW'];
				$hBruto+=$r['RCV_PRGW'];
				$TotalhargaPenyerahan+=$hargaPenyerahan;
			}
			$tpb_header = [
				"NOMOR_AJU" => $nomorAju, "KODE_KANTOR" => $czkantorasal , "KODE_DOKUMEN_PABEAN" => 40,
				"KODE_JENIS_TPB" => 1 ,  "KODE_TUJUAN_PENGIRIMAN" => $statusPengiriman,
				
				"KODE_ID_PENGUSAHA" => "1", "ID_PENGUSAHA" => $czidpengusaha, "NAMA_PENGUSAHA" => $cznmpengusaha,
				"ALAMAT_PENGUSAHA" => $czalamatpengusaha, "NOMOR_IJIN_TPB" => $czizinpengusaha , 
	
				"KODE_ID_PENGIRIM" => "1", "ID_PENGIRIM" => $idPengirim	, "NAMA_PENGIRIM" => $namaPengirim,
				"ALAMAT_PENGIRIM" => $alamatPengirim,
				"JUMLAH_BARANG" => $ttldata,"KODE_STATUS" => '00', "ID_MODUL" => $czidmodul_asli, "VERSI_MODUL" => NULL,
				"KOTA_TTD" => "CIKARANG","NAMA_PENGANGKUT" => "TRUCK", "NAMA_TTD" => "GUSTI AYU KETUT Y", "JABATAN_TTD" => "J.SUPERVISOR", "TANGGAL_TTD" => $createdat,
				"NETTO"	=> $hNetto, "BRUTO" => $hBruto, "HARGA_PENYERAHAN" => $TotalhargaPenyerahan
			];
			$tpb_dokumen = [];
			$seridokumen = 1;
			if(!empty($nomorInvoice)){
				$tpb_dokumen[] = ["KODE_JENIS_DOKUMEN" => "380", "NOMOR_DOKUMEN" => $nomorInvoice , "TANGGAL_DOKUMEN" => $dodate, "TIPE_DOKUMEN" => "02", "SERI_DOKUMEN" => $seridokumen++];
			}
			if(!empty($nomorInvoice)){
				$tpb_dokumen[] = ["KODE_JENIS_DOKUMEN" => "388", "NOMOR_DOKUMEN" => $fakturPajak ,"TANGGAL_DOKUMEN" => null,  "TIPE_DOKUMEN" => "02", "SERI_DOKUMEN" => $seridokumen++];
			}
			$tpb_dokumen[] = ["KODE_JENIS_DOKUMEN" => "640", "NOMOR_DOKUMEN" => $donumber , "TANGGAL_DOKUMEN" => $dodate, "TIPE_DOKUMEN" => "02", "SERI_DOKUMEN" => 1];

			$tpb_kemasan = [];$serikemasan = 1;
			foreach($rskemasan as $r){
				$tpb_kemasan[] = ["JUMLAH_KEMASAN" =>$r->RCVPKG_JUMLAH_KEMASAN ,"KODE_JENIS_KEMASAN" => $r->RCVPKG_KODE_JENIS_KEMASAN, "SERI_KEMASAN" => $serikemasan++];
			}
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
			if(count($tpb_kemasan))	$this->TPB_KEMASAN_imod->insertb($tpb_kemasan);
			##N

			##4 TPB BARANG
			foreach($tpb_barang as &$n){
				$n['ID_HEADER'] = $ZR_TPB_HEADER;
				$this->TPB_BARANG_imod->insert($n);	
			}
			unset($n);			
			##N
			$myar[] = ['cd' => '1', 'msg' => 'Done, Please check the TPB'];
			die(json_encode(['status' => $myar, 'tpb_header' => $tpb_header, 'tpb_barang' => $tpb_barang]));
		} else {
			$myar[] = ['cd' => '0', 'msg' => 'Not found'];
			die(json_encode(['status' => $myar]));
		}
	}

	public function get_info_pendaftaran(){
		header('Content-Type: application/json');
		$cinsj = $this->input->get('insj');
		$rs_head_dlv = $this->RCV_mod->select_header_bydo($cinsj);		
		$myar = [];
		$result_data = [];
		$response_data = [];
		foreach($rs_head_dlv as $r){
			$nomorajufull = $r['RCV_RPNO'];
		}
		$result_data = $this->TPB_HEADER_imod->select_where(
			["TANGGAL_DAFTAR" ,"coalesce(NOMOR_DAFTAR,0) NOMOR_DAFTAR"],
			['NOMOR_AJU' => $nomorajufull]
		);
		$response_data = $this->TPB_RESPON_imod->select_where(
			["NOMOR_RESPON"],
			['NOMOR_AJU' => $nomorajufull, 'NOMOR_RESPON !=' => '']
		);
		if(count($result_data) > 0){
			$myar[] = ['cd' => 1, 'msg' => 'go ahead'];
		} else {
			$myar[] = ['cd' => 0, 'msg' => 'NOMOR AJU is not found in ceisa local data', 'aju' => $nomorajufull];
		}		
		die(json_encode(['status' => $myar, 'data' => $result_data, 'data2' => $response_data]));		
	}

	public function gotoque($pdo){
		$mdo = base64_encode($pdo);
		$ch = curl_init();
				
		curl_setopt($ch, CURLOPT_URL, 'http://192.168.0.29:8081/api_inventory/api/stock/incomingPabean/'.$mdo);
		curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
		curl_setopt($ch, CURLOPT_CONNECTTIMEOUT, 5);
		$data = curl_exec($ch);	
		curl_close($ch);
		return $data;
	}
}
