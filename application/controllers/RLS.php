<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class RLS extends CI_Controller {

	public function __construct()
	{
		parent::__construct();	
		$this->load->model('ITH_mod');
		$this->load->model('RLS_mod');
		$this->load->model('SER_mod');
        $this->load->model('SISCN_mod');
        $this->load->model('PND_mod');
        $this->load->model('PNDSCN_mod');
		$this->load->helper('url');
		$this->load->library('session');
		$this->load->library('Code39e128');
	}
	public function index()
	{
		echo "sorry";
    }

    public function release(){
		$this->load->view('wms/vreleaserm');
	}

    public function searchser(){
		date_default_timezone_set('Asia/Jakarta');		
		header('Content-Type: application/json');
		$ckey = $this->input->get('inid');
		$cby = $this->input->get('inby');		
							
        if($cby=='no'){
            $datal = ['RLSSER_DOC' => $ckey];
            $rs = $this->RLS_mod->selectserAllg_byVAR($datal);
        } else {
            $datal = ['RLSSER_REMARK' => $ckey];
            $rs = $this->RLS_mod->selectserAllg_byVAR($datal);
        }
		echo json_encode($rs);
    }

    function search(){
        header('Content-Type: application/json');
        $csearch = $this->input->get('insearch');
        $rs = $this->RLS_mod->select_rm_resume($csearch);
        die('{"data":'.json_encode($rs).'}');
    }

    public function getbyidser(){
        header('Content-Type: application/json');
        $cdoc = $this->input->get('indoc');
        $dataw = ['RLSSER_DOC' => $cdoc];
        $rs = $this->RLS_mod->selectserAll_by($dataw);
        echo json_encode($rs);
    }

    public function getrm_bydoc(){
        header('Content-Type: application/json');
        $cdoc = $this->input->get('indoc');
        $rs = $this->RLS_mod->select_rm_where(['RLS_DOC' => $cdoc]);
        die('{"data":'.json_encode($rs).'}');
    }

    public function save_release(){
        $this->checkSession();
        header('Content-Type: application/json');
        date_default_timezone_set('Asia/Jakarta');
        $current_datetime	= date('Y-m-d H:i:s');
        $current_date	= date('Y-m-d');
        $formatidpndscn	= date('Ymd');
        $myar = [];
        $doc_of_pending =  $this->input->post('inpenddoc');
        $coldreff = $this->input->post('inoldid');
		$colditem = $this->input->post('inolditemcd');
		$coldqty = $this->input->post('inoldqty');        
        $cold_split = $this->input->post('inoldsplit');
        
        $cnt_parent = count($coldreff);
        $cnt_shouldsplit =0;
        $cnt_ttlrelease = 0;
        
        $newid = $this->input->post('innewid');
        $newid_parent = $this->input->post('innewid_parent');
        $newitem = $this->input->post('innewitem');
        $newlot = $this->input->post('innewlot');
        $newqty = $this->input->post('innewqty');
        
        $newrawtxt = $this->input->post('innewrawtxt');
        $newjob = $this->input->post('innewjob');
        $newisrelease = $this->input->post('innewisrelease');        
        $cnt_child = is_array($newid) ? count($newid) : 0;
        
        for($i=0;$i<$cnt_parent;$i++){
            if($cold_split[$i]=='s'){
                $cnt_shouldsplit++;
                for($y=0;$y<$cnt_child;$y++){
                    if($coldreff[$i]==$newid_parent[$y]){
                        $cnt_ttlrelease++;
                    }
                }
            } else {
                $cnt_ttlrelease++;
            }
        }

        $CUSERID = $this->session->userdata('nama');
        
        for($i=0;$i<$cnt_parent;$i++){
            if($this->SER_mod->check_Primary(['SER_ID' => $coldreff[$i]]) ==0){
                $myar[] = ["cd" => '0', "msg" => "Old Label not found {".$coldreff[$i]."} or might be already splitted"];            
                exit('{"status":'.json_encode($myar).'}');
            }
        }

        for($i=0;$i<$cnt_parent;$i++){
            if($this->SER_mod->check_Primary(['SER_ID' => $coldreff[$i] , 'SER_QTY >0' => null]) ==0){
                $myar[] = ["cd" => '0', "msg" => "qty old label = 0"];
                exit('{"status":'.json_encode($myar).'}');
            }
        }

        for($i=0;$i<$cnt_parent;$i++){
            if($this->SISCN_mod->check_Primary(['SISCN_SER' => $coldreff[$i]]) >0){
                $myar[] = ["cd" => '0', "msg" => "could not split delivered item label"];
                exit('{"status":'.json_encode($myar).'}');
            }
        }
        for($i=0;$i<$cnt_parent;$i++){
            $rsith = $this->ITH_mod->selectstock_ser($coldreff[$i]);
            $whok = true;
            foreach($rsith as $r){
                if(trim($r['ITH_WH']) != 'QAFG'){
                    $whok = false; break;
                }
            }
            if(!$whok){
                $myar[] = ["cd" => '0', "msg" => "could not split released item. Reff NO : ".$coldreff[$i]];                
                exit('{"status":'.json_encode($myar).'}');
            }
        }

        #1
        $rslt_update_ser = 0;
        for($i=0;$i<$cnt_parent;$i++){
            if($cold_split[$i]=='s'){
                $rslt_update_ser += $this->SER_mod->updatebyId(["SER_QTY" => 0, "SER_LUPDT" => $current_datetime, "SER_USRID" => $CUSERID], $coldreff[$i]);                            
            }
        }
        if($rslt_update_ser!=$cnt_shouldsplit){
            $myar[] = ["cd" => '0', "msg" => "not all of old label updated, contact your admin"];
            exit('{"status":'.json_encode($myar).'}');
        }
        #1#

        #2
        $rslt_insert_ser = 0;
        for($i=0; $i<$cnt_child ; $i++){
            $rslt_insert_ser += $this->SER_mod->insert([
                "SER_ID" => $newid[$i],
                "SER_DOC" => $newjob[$i],
                "SER_ITMID" => strtoupper($newitem[$i]),
                "SER_QTY" => $newqty[$i],                
                "SER_LOTNO" => $newlot[$i],
                "SER_REFNO" => $newid_parent[$i],
                "SER_RAWTXT" => $newrawtxt[$i],
                "SER_LUPDT" => $current_datetime,
                "SER_USRID" => $CUSERID
            ]);
        }
        if($rslt_insert_ser!=$cnt_child){
            $myar[] = ["cd" => '0', "msg" => "not all of new label registered, contact your admin"];            
            exit('{"status":'.json_encode($myar).'}');
        }
        #2#


        #3
        $rslt_out_ith =0;
        for($i=0;$i<$cnt_parent;$i++){
            if($cold_split[$i]=='s'){
                $rslt_out_ith+= $this->ITH_mod->insert(
                    [
                        "ITH_ITMCD" => $colditem[$i],
                        "ITH_DATE" => $current_date,
                        "ITH_FORM" => 'SPLIT-PENDING',
                        "ITH_DOC" => $doc_of_pending,
                        "ITH_QTY" => -$coldqty[$i],
                        "ITH_SER" => $coldreff[$i],
                        "ITH_WH" => 'QAFG',
                        "ITH_LUPDT" => $current_datetime,
                        "ITH_REMARK" => "WIL-SPLIT",
                        "ITH_USRID" => $CUSERID
                    ]
                );
            }
        }
        if($rslt_out_ith!=$cnt_shouldsplit){
            $myar[] = ["cd" => '0', "msg" => "not all of new label registered to OUT-transaction, contact your admin"];
            exit('{"status":'.json_encode($myar).'}');
        }
        #3#

        #4
        $rslt_inc_ith = 0;
        for($i=0; $i<$cnt_child ; $i++){
            $rslt_inc_ith += $this->ITH_mod->insert(
                [
                    "ITH_ITMCD" => strtoupper($newitem[$i]),
                    "ITH_DATE" => $current_date,
                    "ITH_FORM" => "SPLIT-PENDING",
                    "ITH_DOC" => $doc_of_pending,
                    "ITH_QTY" => $newqty[$i],
                    "ITH_SER" => $newid[$i],
                    "ITH_WH" => 'QAFG',
                    "ITH_LUPDT" => $current_datetime,
                    "ITH_REMARK" => "AFT-SPLIT",
                    "ITH_USRID" => $this->session->userdata('nama')
                ]
            );
        }
        if($rslt_inc_ith!=$cnt_child){
            $myar[] = ["cd" => '0', "msg" => "not all of new label registered to IN-transaction, contact your admin"];
            exit('{"status":'.json_encode($myar).'}');
        }
        #4#

        #5 ADD TO PND TBL
        $rslt_add_pnd = 0;
        for($i=0; $i<$cnt_child ; $i++){
            $datat = [
                'PNDSER_DOC' => $doc_of_pending,
                'PNDSER_DT' => $current_date,					
                'PNDSER_QTY' => $newqty[$i],
                'PNDSER_SER' => $newid[$i],
                'PNDSER_LUPDT' => $current_datetime,
                'PNDSER_USRID' => $CUSERID
            ];
            $rslt_add_pnd += $this->PND_mod->insertser($datat);            
        }
        if($rslt_add_pnd!=$cnt_child){
            $myar[] = ["cd" => '0', "msg" => "not all of new label registered to the document of pending, contact your admin"];            
            exit('{"status":'.json_encode($myar).'}');
        }
        #5#

        #6 ADD TO PNDSCN TBL
        $rslt_add_pndscn = 0;
        for($i=0; $i<$cnt_child ; $i++){
            $mlastid = $this->PNDSCN_mod->lastserialid();
            $mlastid++;
            $idscn = $formatidpndscn.substr('00000000'.$mlastid,-9);
            $datat = [
                'PNDSCN_ID' => $idscn,
                'PNDSCN_DOC' => $doc_of_pending,					
                'PNDSCN_ITMCD' => $newitem[$i],
                'PNDSCN_LOTNO' => $newjob[$i],
                'PNDSCN_QTY' => $newqty[$i],
                'PNDSCN_SER' => $newid[$i],
                'PNDSCN_SAVED' => '1',
                'PNDSCN_LUPDT' => $current_datetime,
                'PNDSCN_USRID' => $CUSERID
            ];
            $rslt_add_pndscn += $this->PNDSCN_mod->insert($datat);            
        }
        if($rslt_add_pndscn!=$cnt_child){
            $myar[] = ["cd" => '0', "msg" => "not all of new label registered to the document of pending scan, contact your admin"];            
            exit('{"status":'.json_encode($myar).'}');
        }
        #6# EDIT OLD PND

        #7 EDIT PNDSER
        $rslt_edit_pnd = 0;
        for($i=0;$i<$cnt_parent;$i++){
            if($cold_split[$i]=='s'){
                $rslt_edit_pnd += $this->PND_mod->updateserbyId(['PNDSER_QTY' => 0], ['PNDSER_DOC' => $doc_of_pending, 'PNDSER_SER' => $coldreff[$i]]);
            }
        }
        if($rslt_edit_pnd!=$cnt_shouldsplit){
            $myar[] = ["cd" => '0', "msg" => "not all of old label edited in the document, contact your admin"];
            exit('{"status":'.json_encode($myar).'}');
        }
        #7#

        #8 EDIT PNDSCN
        $rslt_edit_pndscn = 0;
        for($i=0;$i<$cnt_parent;$i++){
            if($cold_split[$i]=='s'){
                $rslt_edit_pndscn += $this->PNDSCN_mod->updatebyId(['PNDSCN_QTY' => 0], ['PNDSCN_DOC' => $doc_of_pending, 'PNDSCN_SER' => $coldreff[$i]]);
            }
        }
        if($rslt_edit_pndscn!=$cnt_shouldsplit){
            $myar[] = ["cd" => '0', "msg" => "not all of old label edited in the document scan, contact your admin"];
            exit('{"status":'.json_encode($myar).'}');
        }
        #8#

        #9
        $rslt_add_rls = 0;
        $mlastidrls = $this->RLS_mod->lastserialidser();
        $mlastidrls++;
        $idrlsnew = 'RLSS'.$formatidpndscn.$mlastidrls;
        for($i=0;$i<$cnt_parent;$i++){
            if($cold_split[$i]=='s'){
                for($y=0;$y<$cnt_child;$y++){
                    if($coldreff[$i]==$newid_parent[$y] && $newisrelease[$y]=='1'){
                        $rslt_add_rls += $this->RLS_mod->insert([
                            'RLSSER_DOC' => $idrlsnew,
                            'RLSSER_SER' => $newid[$y],
                            'RLSSER_DT' => $current_date,
                            'RLSSER_QTY' => $newqty[$y],
                            'RLSSER_REFFDOC' => $doc_of_pending,
                            'RLSSER_REFFSER' => $newid_parent[$y],
                            'RLSSER_LUPDT' => $current_datetime,
                            'RLSSER_USRID' => $CUSERID
                        ]);
                    }
                }
            } else {
                $rslt_add_rls += $this->RLS_mod->insert([
                    'RLSSER_DOC' => $idrlsnew,
                    'RLSSER_SER' => $coldreff[$i],
                    'RLSSER_DT' => $current_date,
                    'RLSSER_QTY' => $coldqty[$i],
                    'RLSSER_REFFDOC' => $doc_of_pending,
                    'RLSSER_REFFSER' => $coldreff[$i],
                    'RLSSER_LUPDT' => $current_datetime,
                    'RLSSER_USRID' => $CUSERID
                ]);
            }
        }        
        
        if($rslt_add_rls!=$cnt_ttlrelease){
            $myar[] = ["cd" => '0', "msg" => "OK, "];//but not all of label are registered to release document            
            exit('{"status":'.json_encode($myar).'}');
        } else {
            $myar[] = ["cd" => '1', "msg" => "OK"];
            exit('{"status":'.json_encode($myar).'}');
        }
        #9#        
    }

    public function searchby_pnddoc()
    {
        header('Content-Type: application/json');
        $cdoc = $this->input->get('indoc');
        $rs = $this->RLS_mod->selectser_by(['RLSSER_REFFDOC' => $cdoc]);
        $myar = [];
        array_push($myar, ['cd' => 1, 'msg' => count($rs).' row(s) found']);
        echo '{"status": '.json_encode($myar).', "data": '.json_encode($rs).'}';
    }

    public function printdocser(){
		$pser = '';		
        if(isset($_COOKIE["CKRLSDOCSER_NO"])){
            $pser = $_COOKIE["CKRLSDOCSER_NO"];
		} else {
			exit('nothing to be printed');
		}		
		$rs = $this->RLS_mod->selectser_by(['RLSSER_DOC' => $pser]);
		$cdate = '';
        $cuser = '';
        foreach($rs as $r){
            $cdate=$r['RLSSER_DT'];
            $cuser=$r['FULLNM'];
            break;
        }
		$pdf = new PDF_Code39e128('L','mm','A4');
		$pdf->AliasNbPages();
		$pdf->AddPage();
		$hgt_p = $pdf->GetPageHeight();
		$wid_p = $pdf->GetPageWidth();
		$pdf->SetAutoPageBreak(true,1);
		$pdf->SetMargins(0,0);
		$pdf->SetFont('Arial','BU',9);
        $clebar = $pdf->GetStringWidth($pser)+21;
        $pdf->SetXY(80,5);
        $pdf->Cell(50,4,'Document of FG Release',0,0,'L');
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
				$pdf->Cell(50,4,'Document of FG Release',0,0,'L');
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
			$pdf->Cell(35,4,trim($r['RLSSER_SER']),1,0,'L');						
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
			$pdf->Cell(10,4,number_format($r['RLSSER_QTY']),1,0,'R');
			$pdf->Cell(15,4,'',1,0,'L');
			$pdf->Cell(30,4,'',1,0,'L');
			$cury+=4; $no++;
		}
		$pdf->Output('I','Release FG Doc '.$pser.' ' .date("d-M-Y").'.pdf');
	}

    public function search_pnd_doc_balance(){
        header('Content-Type: application/json');
        $search_by = $this->input->get('insearchby');
        $search = $this->input->get('insearch');
        $rs = [];
        switch($search_by){
            case 'no':
                $rs = $this->RLS_mod->select_rm_balance_by_PNDDoc($search);
                break;
            case 'lot':
                $rs = $this->RLS_mod->select_rm_balance_by_LotNo($search);
                break;
        }
        die('{"data":'.json_encode($rs).'}');
    }

    public function setrm(){
        header('Content-Type: application/json');
        $this->checkSession();
        date_default_timezone_set('Asia/Jakarta');
        $currrtime = date('Y-m-d H:i:s');        
        $currdate = date('Ymd');
        $RLSDoc = $this->input->post('inrlsdoc');
        $RLSDT = $this->input->post('inrlsdt');
        $PNDDoc = $this->input->post('inapnddoc');
        $PNDItem = $this->input->post('inaitem');
        $PNDLot = $this->input->post('inalot');
        $RLSQty = $this->input->post('inaqty');
        $DLine = $this->input->post('inline');
        $DATACount = count($PNDDoc);
        $myar = [];
        $CUSERID = $this->session->userdata('nama');
        $isok = true;
        $notokdata = '';

        if($DATACount==0){
            $myar[] = ['cd' => 0, 'msg' => 'nothing to be saved'];
            die('{"status":'.json_encode($myar).'}');
        }

        # validate make sure balanc qty >= release
        for($i=0; $i<$DATACount; $i++){
            if($this->RLS_mod->select_rm_balance_only_by_DocItemLotNo($PNDDoc[$i], $PNDItem[$i], $PNDLot[$i], $RLSDoc) < $RLSQty[$i]){
                $notokdata = ['PNDDOC' => $PNDDoc[$i], 'PNDITEM' =>  $PNDItem[$i], 'PNDLOT' => $PNDLot[$i]];
                $isok = false; break;
            }
        }
        if($isok){
            if($this->RLS_mod->checkRM_Primary(['RLS_DOC' => $RLSDoc]) >0){
                $savedC = 0;
                $updatedC = 0;
                for($i=0; $i<$DATACount; $i++){
                    if($DLine[$i]==''){
                        $newline = $this->RLS_mod->select_lastline($RLSDoc)+1;
                        $data = [
                            'RLS_DOC' => $RLSDoc
                            ,'RLS_DT' => $RLSDT
                            ,'RLS_ITMCD' => $PNDItem[$i]
                            ,'RLS_ITMLOT' => $PNDLot[$i]
                            ,'RLS_QTY' => $RLSQty[$i]
                            ,'RLS_REFFDOC' => $PNDDoc[$i]
                            ,'RLS_LINE' => $newline
                            ,'RLS_LUPDT' => $currrtime
                            ,'RLS_USRID' => $CUSERID
                        ];
                        $savedC += $this->RLS_mod->insertRM($data);
                    } else {
                        $updatedC += $this->RLS_mod->updatebyVAR([
                            'RLS_QTY' => $RLSQty[$i]
                        ], [
                            'RLS_DOC' => $RLSDoc
                            ,'RLS_LINE' => $DLine[$i]
                        ]);
                    }
                }
                $myar[] = ['cd' => 1, 'msg' => $savedC.' saved, '.$updatedC.' updated', 'doc' => $RLSDoc];
            } else {
                $mlastid = $this->RLS_mod->lastserialid();
                $mlastid++;
                $RLSDoc = 'RLS'.$currdate.$mlastid;
                for($i=0; $i<$DATACount; $i++){
                    $datas[] = [
                        'RLS_DOC' => $RLSDoc
                        ,'RLS_DT' => $RLSDT
                        ,'RLS_ITMCD' => $PNDItem[$i]
                        ,'RLS_ITMLOT' => $PNDLot[$i]
                        ,'RLS_QTY' => $RLSQty[$i]
                        ,'RLS_REFFDOC' => $PNDDoc[$i]
                        ,'RLS_LINE' => $i
                        ,'RLS_LUPDT' => $currrtime
                        ,'RLS_USRID' => $CUSERID
                    ];
                }
                $this->RLS_mod->insertRMb($datas);
                $myar[] = ['cd' => 1, 'msg' => 'OK', 'doc' => $RLSDoc];
            }
        } else {
            $myar[] = ['cd' => 0, 'msg' => 'Over...'];
        }
        die('{"status":'.json_encode($myar).', "datanotok":'.json_encode($notokdata).'}');
    }

    function removerm(){
        header('Content-Type: application/json');
        $docno = $this->input->post('inrlsdoc');
        $line = $this->input->post('inline');
        $myar = [];
        if($this->RLS_mod->delete_where(['RLS_DOC' => $docno, 'RLS_LINE' => $line])){
            $myar[] = ['cd' => 1, 'msg' => 'Deleted'];
        } else {            
            $myar[] = ['cd' => 0, 'msg' => 'Could not delete'];
        }
        die('{"status":'.json_encode($myar).'}');
    }

    public function checkSession(){
		$myar = [];
		if ($this->session->userdata('status') != "login")
        {
			$myar[] = ["cd" => "0", "msg" => "Session is expired please reload page"];
			exit('{"status":'.json_encode($myar).'}');
        }
	}

    public function printdoc(){
		$pser = '';		
        if(isset($_COOKIE["CKRLSRMDOC_NO"])){
            $pser = $_COOKIE["CKRLSRMDOC_NO"];
		} else {
			exit('nothing to be printed');
		}		
		$rs = $this->RLS_mod->select_rm_rpt_where(['RLS_DOC' => $pser]);
		$cdate = '';
        $cuser = '';
        foreach($rs as $r){
            $cdate=$r['RLS_DT'];
            $cuser=$r['FULLNM'];
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
        $pdf->Cell(50,4,'Document of Release RM',0,0,'L');
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
		$pdf->Cell(25,4,'Rack No.',1,0,'L');
		$pdf->Cell(90,4,'Remark',1,0,'L');
		$cury = 30;
		$no = 1;

		foreach($rs as $r){
			if(($cury+10)>$hgt_p){
				$pdf->AddPage();
				$pdf->SetFont('Arial','BU',13);
				$clebar = $pdf->GetStringWidth($pser)+10;
				$pdf->SetXY(80,5);
				$pdf->Cell(50,4,'Document of Release RM',0,0,'L');
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
			$pdf->Cell(35,4,trim($r['RLS_ITMCD']),1,0,'L');
			
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
			$pdf->Cell(40,4,trim($r['RLS_ITMLOT']),1,0,'L');
			$pdf->Cell(15,4,number_format($r['RLS_QTY']),1,0,'R');
			$pdf->Cell(25,4,trim($r['ITMLOC_LOC']),1,0,'L');
			$pdf->Cell(90,4,'',1,0,'L');
			$cury+=4; $no++;
		}

		$pdf->Output('I','Release Doc '.$pser.' ' .date("d-M-Y").'.pdf');
	}

}