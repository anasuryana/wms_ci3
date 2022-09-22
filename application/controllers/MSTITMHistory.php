<?php
defined('BASEPATH') OR exit('No direct script access allowed');
use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;
class MSTITMHistory extends CI_Controller {

    public function __construct()
    {
        parent::__construct();
        $this->load->helper('url');
        $this->load->helper('download');
        $this->load->library('session');
    }
    public function index()
    {
        echo "sorry";
    }

    function file_tmp_hscode()
    {
        $spreadsheet = new Spreadsheet();
        $sheet = $spreadsheet->getActiveSheet();
        $sheet->setTitle('master_hscode');
        $sheet->setCellValueByColumnAndRow(1,1, 'ITEM_CODE');
        $sheet->setCellValueByColumnAndRow(2,1, 'HS_CODE');
        $stringjudul = "master hscode";
        $writer = new Xlsx($spreadsheet);
        $filename = $stringjudul; //save our workbook as this file name
        
        header('Content-Type: application/vnd.ms-excel');
        header('Content-Disposition: attachment;filename="'. $filename .'.xlsx"'); 
        header('Cache-Control: max-age=0');
        $writer->save('php://output');
    }
}