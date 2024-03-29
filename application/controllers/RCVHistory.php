<?php
defined('BASEPATH') or exit('No direct script access allowed');

use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Style\Border;
use PhpOffice\PhpSpreadsheet\Style\Color;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;

class RCVHistory extends CI_Controller
{

    public function __construct()
    {
        parent::__construct();
        $this->load->model('RCVNI_mod');
        $this->load->helper('url');
    }

    public function index()
    {
        echo "sorry";
    }

    public function form_receiving()
    {
        $this->load->view('wms_report/vrpt_receiving_list');
    }

    public function receiving()
    {
        header('Content-Type: application/json');
        $searchby = $this->input->get('searchby');
        $search = $this->input->get('search');
        $dateFrom = $this->input->get('date0');
        $dateTo = $this->input->get('date1');
        switch ($searchby) {
            case 'id':
                $likeFilter = ['ITEMCODE' => $search];
                break;
            case 'name':
                $likeFilter = ['ITEMNAME' => $search];
                break;
            case 'supname':
                $likeFilter = ['SUPNM' => $search];
                break;
        }
        $rs = $this->RCVNI_mod->select_receiving_like($likeFilter, $dateFrom, $dateTo);
        die(json_encode(['data' => $rs]));
    }

    public function receivingAsXLS()
    {
        $searchby = $this->input->get('searchby');
        $search = $this->input->get('search');
        $dateFrom = $this->input->get('date0');
        $dateTo = $this->input->get('date1');
        switch ($searchby) {
            case 'id':
                $likeFilter = ['ITEMCODE' => $search];
                break;
            case 'name':
                $likeFilter = ['ITEMNAME' => $search];
                break;
            case 'supname':
                $likeFilter = ['SUPNM' => $search];
                break;
        }
        $rs = $this->RCVNI_mod->select_receiving_like($likeFilter, $dateFrom, $dateTo);
        $spreadsheet = new Spreadsheet();
        $sheet = $spreadsheet->getActiveSheet();
        $sheet->setTitle('REPORT');
        $sheet->setCellValueByColumnAndRow(1, 1, 'PT. SMT INDONESIA');
        $sheet->setCellValueByColumnAndRow(1, 2, 'Kawasan EJIP Plot 5C2 Cikarang Selatan');
        $sheet->setCellValueByColumnAndRow(1, 3, 'RECEIVING LIST REPORT');
        $sheet->setCellValueByColumnAndRow(1, 4, 'PERIOD : ' . $dateFrom . ' - ' . $dateTo);
        $sheet->setCellValueByColumnAndRow(1, 5, 'Type BC');
        $sheet->setCellValueByColumnAndRow(2, 5, 'Nomor Pengajuan');
        $sheet->setCellValueByColumnAndRow(3, 5, 'Nomor Pendaftaran');
        $sheet->setCellValueByColumnAndRow(4, 5, 'PPN');
        $sheet->setCellValueByColumnAndRow(5, 5, 'Tanggal Pendaftaran');
        $sheet->setCellValueByColumnAndRow(6, 5, 'Subject');
        $sheet->setCellValueByColumnAndRow(7, 5, 'Department');
        $sheet->setCellValueByColumnAndRow(8, 5, 'Receiving Number');
        $sheet->setCellValueByColumnAndRow(9, 5, 'Receiving Date');
        $sheet->setCellValueByColumnAndRow(10, 5, 'PO Number');
        $sheet->setCellValueByColumnAndRow(11, 5, 'Vendor Code');
        $sheet->setCellValueByColumnAndRow(12, 5, 'Vendor Name');
        $sheet->setCellValueByColumnAndRow(13, 5, 'Item Code');
        $sheet->setCellValueByColumnAndRow(14, 5, 'Item Name');
        $sheet->setCellValueByColumnAndRow(15, 5, 'Received Qty');
        $sheet->setCellValueByColumnAndRow(16, 5, 'Unit Measurement');
        $sheet->setCellValueByColumnAndRow(17, 5, 'Currency');
        $sheet->setCellValueByColumnAndRow(18, 5, 'Unit Price');
        $sheet->setCellValueByColumnAndRow(19, 5, 'Amount');
        $sheet->freezePane('A6');
        $i = 6;
        $sheet->getStyle("E5:E" . 6 + count($rs))->getNumberFormat()->setFormatCode(\PhpOffice\PhpSpreadsheet\Style\NumberFormat::FORMAT_DATE_DDMMYYYY);
        foreach ($rs as $r) {
            $BCDATE = '';
            if ($r['RCV_RPDATE']) {
                $ArrayBCDATE = explode('-', $r['RCV_RPDATE']);
                $BCDATE = $ArrayBCDATE[2] . '-' . $ArrayBCDATE[1] . '-' . $ArrayBCDATE[0];
            }
            $RCVDate = '';
            if ($r['RCV_RCVDATE']) {
                $ArrayRCVDate = explode('-', $r['RCV_RCVDATE']);
                $RCVDate = $ArrayRCVDate[2] . '-' . $ArrayRCVDate[1] . '-' . $ArrayRCVDate[0];
            }
            $sheet->setCellValueByColumnAndRow(1, $i, $r['RCV_BCTYPE']);
            $sheet->setCellValueByColumnAndRow(2, $i, $r['RCV_RPNO']);
            $sheet->setCellValueByColumnAndRow(3, $i, $r['RCV_BCNO']);
            $sheet->setCellValueByColumnAndRow(4, $i, $r['PO_VAT']);
            $sheet->setCellValueByColumnAndRow(5, $i, $BCDATE);
            $sheet->setCellValueByColumnAndRow(6, $i, $r['POSUBJECT']);
            $sheet->setCellValueByColumnAndRow(7, $i, $r['PODEPT']);
            $sheet->setCellValueByColumnAndRow(8, $i, $r['RECEIVINGNO']);
            $sheet->setCellValueByColumnAndRow(9, $i, $RCVDate);
            $sheet->setCellValueByColumnAndRow(10, $i, $r['RCV_PO']);
            $sheet->setCellValueByColumnAndRow(11, $i, $r['RCV_SUPCD']);
            $sheet->setCellValueByColumnAndRow(12, $i, $r['SUPNM']);
            $sheet->setCellValueByColumnAndRow(13, $i, $r['ITEMCODE']);
            $sheet->setCellValueByColumnAndRow(14, $i, $r['ITEMNAME']);
            $sheet->setCellValueByColumnAndRow(15, $i, $r['RQT']);
            $sheet->setCellValueByColumnAndRow(16, $i, $r['UOM']);
            $sheet->setCellValueByColumnAndRow(17, $i, $r['MSUP_SUPCR']);
            $sheet->setCellValueByColumnAndRow(18, $i, $r['RCV_PRPRC']);
            $sheet->setCellValueByColumnAndRow(19, $i, $r['AMOUNT']);
            $i++;
        }
        foreach (range('B', 'S') as $v) {
            $sheet->getColumnDimension($v)->setAutoSize(true);
        }
        $rang = "A5:S" . $i;
        $sheet->getStyle($rang)->getBorders()->getAllBorders()->setBorderStyle(Border::BORDER_THIN)
            ->setColor(new Color('1F1812'));
        $sheet->getStyle("Q5:R" . $i)->getNumberFormat()->setFormatCode('#,##0');
        $writer = new Xlsx($spreadsheet);
        $stringjudul = "Receiving List Report";
        $filename = $stringjudul; //save our workbook as this file name
        header('Content-Type: application/vnd.ms-excel');
        header('Content-Disposition: attachment;filename="' . $filename . '.xlsx"');
        header('Cache-Control: max-age=0');
        $writer->save('php://output');
    }

    function form_ics_receiving() {
        $this->load->view('wms_report/ics_receiving');
    }
}
