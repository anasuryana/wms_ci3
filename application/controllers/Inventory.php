<?php
use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;
class Inventory extends CI_Controller {

    public function __construct()
    {
        parent::__construct();
        $this->load->helper('url_helper');
        $this->load->library('session');
        $this->load->model('Inventory_mod');
        $this->load->model('XBGROUP_mod');
        if ($this->session->userdata('status') != "login")
        {
            redirect(base_url(""));
        }
    }

    public function index()
    {
        echo "._,";
    }

    public function vlist(){
        $this->load->view('wms_report/vrpt_fg_inventory');
    }
    public function vlist_rm(){
        $rsbg = $this->XBGROUP_mod->selectall();
        $lbg = '';
        foreach($rsbg as $r){
            $lbg .= '<option value="'.trim($r->MBSG_BSGRP).'">'.trim($r->MBSG_DESC).'</option>';
        }
        $data['lbg'] = $lbg;
        $this->load->view('wms_report/vrpt_rm_inventory', $data);
    }

    public function createrm(){
        $this->load->view('wms/vrpt_fg_inventory');
    }

    public function getlist()
    {
        header('Content-Type: application/json');
        $rs = $this->Inventory_mod->selectAll();
        exit('{"data": '.json_encode($rs).'}');
    }
    public function getlist_rm()
    {
        header('Content-Type: application/json');
        $citem = $this->input->get('initemcode');
        $crack = $this->input->get('inrack');
        $clotno = $this->input->get('inlotno');
        $cbisgrup = $this->input->get('bisgrup');
        $rs = [];
        if (strpos($crack, '**') !== false){      
            $arack = explode("**", $crack);            
            $rs = $cbisgrup=='-' ? $this->Inventory_mod->selectAll_rm_rack_like($citem, $clotno, $arack) :
                $this->Inventory_mod->selectAll_rm_rack_like_with_bisgrup($citem, $clotno, $arack, $cbisgrup) ;
        } else {
            $clike = ['CPARTCODE' => $citem, 'CLOTNO' => $clotno, 'CLOC' => $crack];
            $rs = $cbisgrup=='-' ? $this->Inventory_mod->selectAll_rm_like($clike) : 
                $this->Inventory_mod->selectAll_rm_like_with_bisgrup($clike, $cbisgrup);
        }
        exit('{"data": '.json_encode($rs).'}');
    }

    public function deleterm(){
        $citem = $this->input->post('initem');
        $ctime = $this->input->post('intime');
        $crack = $this->input->post('inrack');
        $ttlrow = count($citem);
        $ret = 0;
        $myar = [];
        for($i=0;$i<$ttlrow; $i++){
            $ret += $this->Inventory_mod->delete_rm(['CPARTCODE' => $citem[$i],'CDATE' => $ctime[$i], 'CLOC' => $crack[$i] ]);
        }
        if($ret>0){
            $myar[] = ['cd' => 1, 'msg' => 'Deleted'];
        } else {
            $myar[] = ['cd' => 0, 'msg' => 'Could not be deleted'];
        }
        die('{"status": '.json_encode($myar).'}');
    }

    public function export_rm_xls(){
        $citem = $this->input->get('initemcode');
        $crack = $this->input->get('inrack');
        $clotno = $this->input->get('inlotno');
        $cbisgrup = $this->input->get('inbg');
        $rs = [];
        $rs2 = [];
        if (strpos($crack, '**') !== false){
            $arack = explode("**", $crack);
            // $rs = $this->Inventory_mod->selectAll_rm_rack_like($citem, $clotno, $arack); 
            if($cbisgrup=='-'){
                $rs = $this->Inventory_mod->selectAll_rm_rack_like($citem, $clotno, $arack);
                $rs2 = $this->Inventory_mod->selectAll_rm_rack_like_group($citem, $clotno, $arack);           
            } else {
                $rs = $this->Inventory_mod->selectAll_rm_rack_like_with_bisgrup($citem, $clotno, $arack, $cbisgrup) ;           
                $rs2 = $this->Inventory_mod->selectAll_rm_rack_like_group_with_bisgrup($citem, $clotno, $arack,$cbisgrup);
            }
        } else {
            $clike = ['CPARTCODE' => $citem, 'CLOTNO' => $clotno, 'CLOC' => $crack];
            // $rs = $this->Inventory_mod->selectAll_rm_like($clike);
            if($cbisgrup=='-' ){
                $rs = $this->Inventory_mod->selectAll_rm_like($clike);
                $rs2 = $this->Inventory_mod->selectAll_rm_group($clike);
            } else {
                $rs = $this->Inventory_mod->selectAll_rm_like_with_bisgrup($clike, $cbisgrup);
                $rs2 = $this->Inventory_mod->selectAll_rm_group_with_bisgrup($clike, $cbisgrup);
            }            
        }      
        // return;
        // $rs = $this->Inventory_mod->selectAll_rm();
        $spreadsheet = new Spreadsheet();
		$sheet = $spreadsheet->getActiveSheet();
		$sheet->setTitle('DETAIL');
		$sheet->setCellValueByColumnAndRow(1,1, 'ITEM_CODE');
		$sheet->setCellValueByColumnAndRow(2,1, 'ITEM_NAME');
		$sheet->setCellValueByColumnAndRow(3,1, 'LOT_NUMBER');
		$sheet->setCellValueByColumnAndRow(4,1, 'QTY');
		$sheet->setCellValueByColumnAndRow(5,1, 'FIFO');
		$sheet->setCellValueByColumnAndRow(6,1, 'PIC');
		$sheet->setCellValueByColumnAndRow(7,1, 'TIME');
		$sheet->setCellValueByColumnAndRow(8,1, 'LOCATION');
		$no=2;
		foreach($rs as $r){
			$sheet->setCellValueByColumnAndRow(1,$no, $r['CPARTCODE']);
			$sheet->setCellValueByColumnAndRow(2,$no, $r['MITM_SPTNO']);
			$sheet->setCellValueByColumnAndRow(3,$no, $r['CLOTNO']);
			$sheet->setCellValueByColumnAndRow(4,$no, $r['CQTY']);
			$sheet->setCellValueByColumnAndRow(5,$no, '');
			$sheet->setCellValueByColumnAndRow(6,$no, $r['FULLNAME']);
			$sheet->setCellValueByColumnAndRow(7,$no, $r['CDATE']);
			$sheet->setCellValueByColumnAndRow(8,$no, $r['CLOC']);
			$no++;
        }
        $rang = "A1:H".$sheet->getHighestDataRow();
        $myConditi = new \PhpOffice\PhpSpreadsheet\Style\Conditional;
        $myConditi->setConditionType(\PhpOffice\PhpSpreadsheet\Style\Conditional::CONDITION_EXPRESSION)
        ->setOperatorType(\PhpOffice\PhpSpreadsheet\Style\Conditional::OPERATOR_EQUAL)
        ->addCondition('MOD(ROW(),2)=0');
        $myConditi->getStyle()->getFill()->setFillType(\PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID)
        ->getStartColor()->setARGB('FFFF0000');

        $myConditi2 = new \PhpOffice\PhpSpreadsheet\Style\Conditional;
        $myConditi2->setConditionType(\PhpOffice\PhpSpreadsheet\Style\Conditional::CONDITION_EXPRESSION)
        ->setOperatorType(\PhpOffice\PhpSpreadsheet\Style\Conditional::OPERATOR_EQUAL)
        ->addCondition('MOD(ROW(),2)=0');        
        $myConditi2->getStyle()->getFill()->setFillType(\PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID)
        ->getEndColor()->setARGB('f0f0f0');
        $aconditions[] = $myConditi;
        $aconditions[] = $myConditi2;
        $sheet->getStyle($rang)->setConditionalStyles($aconditions);
        $sheet->getStyle($rang)->getBorders()->getAllBorders()->setBorderStyle(\PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN);
        $sheet->getColumnDimension('A')->setAutoSize(true);
        $sheet->getColumnDimension('B')->setAutoSize(true);
        $sheet->getColumnDimension('C')->setAutoSize(true);
        $sheet->getColumnDimension('D')->setAutoSize(true);
        $sheet->getColumnDimension('E')->setAutoSize(true);
        $sheet->getColumnDimension('F')->setAutoSize(true);
        $sheet->getColumnDimension('G')->setAutoSize(true);
        $sheet->getColumnDimension('H')->setAutoSize(true);

        $sheet = $spreadsheet->createSheet();
        $sheet->setTitle('RESUME');
        
        $sheet->setCellValueByColumnAndRow(1,1, 'ITEM_CODE');
		$sheet->setCellValueByColumnAndRow(2,1, 'ITEM_NAME');
		$sheet->setCellValueByColumnAndRow(3,1, 'QTY');				
		$sheet->setCellValueByColumnAndRow(4,1, 'LOCATION');
        $no=2;
        foreach($rs2 as $r){
			$sheet->setCellValueByColumnAndRow(1,$no, $r['CPARTCODE']);
			$sheet->setCellValueByColumnAndRow(2,$no, $r['MITM_SPTNO']);
			$sheet->setCellValueByColumnAndRow(3,$no, $r['CQTY']);
			$sheet->setCellValueByColumnAndRow(4,$no, $r['CLOC']);
			$no++;
        }
        $rang = "A1:D".$sheet->getHighestDataRow();
        $sheet->getStyle($rang)->setConditionalStyles($aconditions);
        $sheet->getStyle($rang)->getBorders()->getAllBorders()->setBorderStyle(\PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN);
        $sheet->getColumnDimension('A')->setAutoSize(true);
        $sheet->getColumnDimension('B')->setAutoSize(true);
        $sheet->getColumnDimension('C')->setAutoSize(true);
        $sheet->getColumnDimension('D')->setAutoSize(true);
       
		$writer = new Xlsx($spreadsheet);
		$filename="RM Inventory "; //save our workbook as this file name
		
		header('Content-Type: application/vnd.ms-excel');
		header('Content-Disposition: attachment;filename="'. $filename .'.xlsx"'); 
		header('Cache-Control: max-age=0');
		$writer->save('php://output');
    }

    public function clearFG(){
        header('Content-Type: application/json');
        date_default_timezone_set('Asia/Jakarta');
		$currrtime = date('ymdH');
        $cpin = $this->input->get('inpin');
        $myar = [];
        if($cpin=='MTHSMTMTH'){
            $this->Inventory_mod->insert_fg_for_backup($currrtime);
            $this->Inventory_mod->delete_all_fg();
            $myar[] = ['cd' => 1, 'msg' => 'OK, please refresh'];
        } else {            
            $myar[] = ['cd' => 0, 'msg' => 'PIN is not valid'];
        }
        die('{"status": '.json_encode($myar).'}');
    }

    public function clearRM(){
        header('Content-Type: application/json');
        date_default_timezone_set('Asia/Jakarta');
		$currrtime = date('ymdH');
        $myar = [];
        $this->Inventory_mod->insert_rm_for_backup($currrtime);
        $this->Inventory_mod->delete_all_rm();
        $myar[] = ['cd' => 1, 'msg' => 'cleared'];
        die('{"status": '.json_encode($myar).'}');
    }
}
