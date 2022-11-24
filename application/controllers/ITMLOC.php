<?php
defined('BASEPATH') or exit('No direct script access allowed');

use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;
use PhpOffice\PhpSpreadsheet\Writer\Xls;

class ITMLOC extends CI_Controller
{

    public function __construct()
    {
        parent::__construct();
        $this->load->model('ITMLOC_mod');
        $this->load->model('MSTLOCG_mod');
        $this->load->model('MSTLOC_mod');
        $this->load->model('XBGROUP_mod');
        $this->load->helper('url');
        $this->load->helper('download');
        $this->load->library('session');
    }
    public function index()
    {
        echo "sorry";
    }

    public function create()
    {
        $data['lloc'] = $this->MSTLOCG_mod->selectall_where_CODE_in(['ARWH1', 'ARWH2', 'NRWH2']);
        $rsbg = $this->XBGROUP_mod->selectall();
        $lbg = '';
        foreach ($rsbg as $r) {
            $lbg .= '<option value="' . trim($r->MBSG_BSGRP) . '">' . trim($r->MBSG_DESC) . '</option>';
        }
        $data['lbg'] = $lbg;
        $this->load->view('wms/vitmloc', $data);
    }

    public function set()
    {
        date_default_timezone_set('Asia/Jakarta');
        $currrtime = date('Y-m-d H:i:s');
        $business = $this->input->post('inbg');
        $citem = $this->input->post('initem');
        $clocg = $this->input->post('inlocg');
        $cloca = $this->input->post('inloc');
        $datac = ['ITMLOC_ITM' => $citem, 'ITMLOC_LOC' => $cloca];
        if ($this->ITMLOC_mod->check_Primary($datac) == 0) {
            $datas = [
                'ITMLOC_BG' => $business, 'ITMLOC_ITM' => $citem, 'ITMLOC_LOCG' => $clocg, 'ITMLOC_LOC' => $cloca,
                'ITMLOC_LUPDT' => $currrtime, 'ITMLOC_USRID' => $this->session->userdata('nama')
            ];
            $toret = $this->ITMLOC_mod->insert($datas);
            if ($toret > 0) {
                echo "Added successfully";
            } else {
                echo "Could not add the data";
            }
        } else {
            echo "The data is already added";
        }
    }

    function search()
    {
        header('Content-Type: application/json');
        $search = $this->input->get('search');
        $rs = $this->MSTLOC_mod->select_subloc($search);
        die(json_encode(['data' => $rs]));
    }

    public function getbyitemcd()
    {
        header('Content-Type: application/json');
        $ccd = $this->input->get('incd');
        $rs = $this->ITMLOC_mod->selectbyitemcd($ccd);
        echo json_encode($rs);
    }

    public function remove()
    {
        $citem = $this->input->get('initem');
        $clocg = $this->input->get('inlocg');
        $cloc = $this->input->get('inloc');
        $datad = ['ITMLOC_ITM' => $citem, 'ITMLOC_LOC' => $cloc, 'ITMLOC_LOCG' => $clocg];
        $toret  = $this->ITMLOC_mod->deletebyID($datad);
        if ($toret > 0) {
            echo "Deleted successfully";
        } else {
            echo "No data deleted";
        }
    }

    public function getlinkitemtemplate()
    {
        $murl = ((isset($_SERVER['HTTPS']) && $_SERVER['HTTPS'] == "on") ? "https" : "http");
        $murl .= "://" . $_SERVER['HTTP_HOST'];
        echo $murl . "/wms/ITMLOC/downloadtemplate";
    }

    function template()
    {
        $spreadsheet = new Spreadsheet();
        $sheet = $spreadsheet->getActiveSheet();
        $sheet->setTitle('EM');
        $sheet->setCellValueByColumnAndRow(1, 1, 'ITEM');
        $sheet->setCellValueByColumnAndRow(2, 1, 'LOCATIONID');
        $sheet->setCellValueByColumnAndRow(3, 1, 'LOCATIONGROUP');
        $sheet->setCellValueByColumnAndRow(4, 1, 'BUSINESSGROUP');
        foreach (range('A', 'D') as $v) {
            $sheet->getColumnDimension($v)->setAutoSize(true);
        }
        $sheet->getComment('B1')
            ->setAuthor('Ana');
        $objComment = $sheet->getComment('B1')
            ->getText()->createTextRun('Ana too:');
        $objComment->getFont()->setBold(true);
        $sheet->getComment('B1')
            ->getText()->createTextRun("\r\n");
        $sheet->getComment('B1')
            ->getText()->createTextRun("Rack");
        $stringjudul = "tmpl_itemloc";
        $writer = new Xls($spreadsheet);
        $filename = $stringjudul; //save our workbook as this file name

        header('Content-Type: application/vnd.ms-excel');
        header('Content-Disposition: attachment;filename="' . $filename . '.xls"');
        header('Cache-Control: max-age=0');
        $writer->save('php://output');
    }

    function downloadtemplate()
    {
        $theurl = 'assets/userxls_template/tmpl_itemloc.xls';
        force_download($theurl, null);
        echo $theurl;
    }

    public function import()
    {
        date_default_timezone_set('Asia/Jakarta');
        $currrtime = date('Y-m-d H:i:s');
        $citem     = $this->input->post('initem');
        $cloc      = $this->input->post('inloc');
        $clocg     = $this->input->post('inlocg');
        $cBG     = $this->input->post('inBG');
        $crowid    = $this->input->post('inrowid');
        $datac = [
            'ITMLOC_ITM' => $citem, 'ITMLOC_LOC' => $cloc, 'ITMLOC_LOCG' => $clocg, 'ITMLOC_BG' => $cBG
        ];
        $myar = [];
        if ($this->ITMLOC_mod->check_Primary($datac) == 0) {
            $datas = [
                'ITMLOC_ITM' => $citem, 'ITMLOC_LOC' => $cloc, 'ITMLOC_LOCG' => $clocg, 'ITMLOC_BG' => $cBG,
                'ITMLOC_LUPDT' => $currrtime, 'ITMLOC_USRID' => $this->session->userdata('nama')
            ];
            $anar = $this->ITMLOC_mod->insert($datas) > 0 ? ["indx" => $crowid, "status" => 'Saved successfully'] : ["indx" => $crowid, "status" => 'Could not insert'];
        } else {
            $anar = ["indx" => $crowid, "status" => 'Already exist'];
        }
        $myar[] = $anar;
        echo json_encode($myar);
    }

    public function check_item_WithoutPSN()
    {
        header('Content-Type: application/json');
        $itemcd = $this->input->get('itemcd');
        $rs = $this->ITMLOC_mod->select_item_WithoutPSN(['ITMLOC_ITM' => $itemcd]);
        $myar = [];
        $myar[] = count($rs) ? ['cd' => '1', 'msg' => 'found'] : ['cd' => '0', 'msg' => 'not found'];
        die(json_encode(['data' => $rs, 'status' => $myar]));
    }
}
