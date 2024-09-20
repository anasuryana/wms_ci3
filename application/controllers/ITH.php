<?php
defined('BASEPATH') or exit('No direct script access allowed');

use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Style\Border;
use PhpOffice\PhpSpreadsheet\Style\Color;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;

class ITH extends CI_Controller
{
    private $FG_RETURN_WH = ['AFWH3RT', 'NFWH4RT'];
    public function __construct()
    {
        parent::__construct();
        $this->load->model('ITH_mod');
        $this->load->model('MSTLOCG_mod');
        $this->load->model('SER_mod');
        $this->load->model('MSTITM_mod');
        $this->load->model('BGROUP_mod');
        $this->load->model('LOGXDATA_mod');
        $this->load->model('RETFG_mod');
        $this->load->model('BCBLC_mod');
        $this->load->model('SCR_mod');
        $this->load->model('RCV_mod');
        $this->load->model('MSPP_mod');
        $this->load->model('CSMLOG_mod');
        $this->load->model('RPSAL_INVENTORY_mod');
        $this->load->model('ZRPSTOCK_mod');
        $this->load->model('XITRN_mod');
        $this->load->model('XFTRN_mod');
        $this->load->model('XICYC_mod');
        $this->load->model('XPGRN_mod');
        $this->load->model('SPL_mod');
        $this->load->model('PWOP_mod');
        $this->load->model('SPLRET_mod');
        $this->load->helper('url');
        $this->load->library('session');
        $this->load->library('RMCalculator');
        date_default_timezone_set('Asia/Jakarta');
    }
    public function index()
    {
        echo "sorry";
    }

    public function create()
    {
        $rs = $this->BGROUP_mod->selectall();
        $todis = '<option value="-">ALL</option>';
        foreach ($rs as $r) {
            $todis .= '<option value="' . trim($r->MBSG_BSGRP) . '">' . trim($r->MBSG_DESC) . '</option>';
        }
        $data['lgroup'] = $todis;
        $todiswh = '';
        $rs = $this->MSTLOCG_mod->selectall();
        foreach ($rs as $r) {
            $todiswh .= '<option value="' . $r['MSTLOCG_ID'] . '">' . $r['MSTLOCG_NM'] . '</option>';
        }
        $data['lwh'] = $todiswh;
        $this->load->view('wms/vith', $data);
    }

    public function form_fg_slow_moving()
    {
        $this->load->view('wms_report/vfg_slowmoving');
    }
    public function form_change_ith_date()
    {
        $this->checkSession();
        $this->load->view('wms/vchangedate_ith');
    }

    public function form_minus_stock()
    {
        $this->load->view('wms_report/vminus_stock');
    }

    public function minus_stock()
    {
        header('Content-Type: application/json');
        $rs = $this->ITH_mod->select_minus_stock();
        die(json_encode(['data' => $rs]));
    }

    public function fg_slow_moving()
    {
        header('Content-Type: application/json');
        $cbgroup = $this->input->get('bsgrp');
        $sbgroup = "";
        $absgrp = [];
        if (is_array($cbgroup)) {
            for ($i = 0; $i < count($cbgroup); $i++) {
                $sbgroup .= "'$cbgroup[$i]',";
                $absgrp[] = $cbgroup[$i];
            }
            $sbgroup = substr($sbgroup, 0, strlen($sbgroup) - 1);
            if ($sbgroup == '') {
                $sbgroup = "''";
            }
        } else {
            $sbgroup = "''";
        }
        $rs = count($absgrp) ? $this->ITH_mod->select_slow_moving_fg_bg($absgrp) : $this->ITH_mod->select_slow_moving_fg();
        die(json_encode(['data' => $rs]));
    }

    public function form_report_internal()
    {
        $this->load->view('wms_report/vrpt_internal');
    }

    public function get_bs_group()
    {
        header('Content-Type: application/json');
        $rs = $this->BGROUP_mod->selectall();
        $rs_j = [];
        foreach ($rs as $r) {
            $rs_j[] = [
                'id' => trim($r->MBSG_BSGRP), 'text' => trim($r->MBSG_DESC),
            ];
        }
        exit('{"data":' . json_encode($rs_j) . '}');
    }

    public function get_bs_group_ith()
    {
        header('Content-Type: application/json');
        $rs = $this->BGROUP_mod->selectall();
        $rs_j = [];
        foreach ($rs as $r) {
            $rs_j[] = [
                'id' => trim($r->MBSG_BSGRP), 'text' => trim($r->MBSG_DESC),
            ];
        }
        exit(json_encode($rs_j));
    }

    public function vroutput_prd_daily()
    {
        $data['lgroup'] = $this->BGROUP_mod->selectall();
        $this->load->view('wms_report/vrpt_output_prd', $data);
    }
    public function vroutput_qc_daily()
    {
        $data['lgroup'] = $this->BGROUP_mod->selectall();
        $this->load->view('wms_report/vrpt_output_qc', $data);
    }
    public function vroutput_qcsa_daily()
    {
        $data['lgroup'] = $this->BGROUP_mod->selectall();
        $this->load->view('wms_report/vrpt_output_qcsubassy', $data);
    }
    public function vroutput_prdsa_daily()
    {
        $data['lgroup'] = $this->BGROUP_mod->selectall();
        $this->load->view('wms_report/vrpt_output_prdsubassy', $data);
    }
    public function vroutput_wh_daily()
    {
        $data['lgroup'] = $this->BGROUP_mod->selectall();
        $this->load->view('wms_report/vrpt_output_wh', $data);
    }
    public function vroutput_wh_rtn_daily()
    {
        $data['lgroup'] = $this->BGROUP_mod->selectall();
        $this->load->view('wms_report/vrpt_output_whrtn', $data);
    }
    public function vroutgoing_wh()
    {
        $data['lgroup'] = $this->BGROUP_mod->selectall();
        $this->load->view('wms_report/vrpt_outgoing_fg', $data);
    }
    public function vunscan_qcwh()
    {
        $this->load->view('wms_report/vunscan_qcwh');
    }
    public function vtxhistory()
    {
        $todiswh = '';
        $rs = $this->MSTLOCG_mod->selectall();
        foreach ($rs as $r) {
            $todiswh .= '<option value="' . $r['MSTLOCG_ID'] . '">' . $r['MSTLOCG_NM'] . ' (' . $r['MSTLOCG_ID'] . ')</option>';
        }
        $data['lwh'] = $todiswh;
        $this->load->view('wms_report/vrpt_txhistory', $data);
    }
    public function form_txhistory()
    {
        $todiswh = '';
        $rs = $this->MSTLOCG_mod->selectall();
        foreach ($rs as $r) {
            $todiswh .= '<option value="' . $r['MSTLOCG_ID'] . '">' . $r['MSTLOCG_NM'] . ' (' . $r['MSTLOCG_ID'] . ')</option>';
        }
        $data['lwh'] = $todiswh;
        $this->load->view('wms_report/vrpt_txhistory_parent', $data);
    }
    public function vtxhistory_customs()
    {
        $this->load->view('wms_report/vrpt_txhistory_customs');
    }

    public function get_output_prd()
    {
        header('Content-Type: application/json');
        $cdtfrom = $this->input->get('indate');
        $cdtto = $this->input->get('indate2');
        $creport = $this->input->get('inreport');
        $cassy = $this->input->get('inassy');
        $cbgroup = $this->input->get('inbgroup');
        $csearchby = $this->input->get('insearchby');
        $sbgroup = "";
        if (is_array($cbgroup)) {
            for ($i = 0; $i < count($cbgroup); $i++) {
                $sbgroup .= "'$cbgroup[$i]',";
            }
        } else {
            $sbgroup = "'" . $cbgroup . "',";
        }

        $sbgroup = substr($sbgroup, 0, strlen($sbgroup) - 1);
        if ($sbgroup == '') {
            $sbgroup = "''";
        }
        $dtto = '';
        if ($cdtfrom == $cdtto) {
            if ($creport == 'a' || $creport == 'n') {
                $thedate = strtotime($cdtfrom . '+1 days');
                $dtto = date('Y-m-d', $thedate) . " 06:59:00";
            } else {
                $dtto = $cdtfrom . " 18:59:00";
            }

            if ($creport == 'a' || $creport == 'm') {
                $cdtfrom .= ' 07:00:00';
            } else {
                $cdtfrom .= ' 19:00:00';
            }
        } else {
            $thedate = strtotime($cdtto . '+1 days');
            $dtto = date('Y-m-d', $thedate) . " 06:59:00";
            $cdtfrom .= ' 07:00:00';
        }
        $rs = [];
        if ($csearchby == 'assy') {
            $rs = $this->ITH_mod->select_output_prd($cdtfrom, $dtto, $cassy, $sbgroup);
        } else {
            $rs = $this->ITH_mod->select_output_prd_byjob($cdtfrom, $dtto, $cassy, $sbgroup);
        }
        echo '{"data":';
        echo json_encode($rs);
        echo '}';
    }

    public function get_output_prd_xls()
    {
        date_default_timezone_set('Asia/Jakarta');
        if (!isset($_COOKIE["CKPSI_DDATE"]) || !isset($_COOKIE["CKPSI_DREPORT"]) || !isset($_COOKIE["CKPSI_DASSY"]) || !isset($_COOKIE["CKPSI_BSGROUP"])) {
            exit('no data to be found');
        }
        $cdtfrom = $_COOKIE["CKPSI_DDATE"];
        $cdtto = $_COOKIE["CKPSI_DDATE2"];
        $creport = $_COOKIE["CKPSI_DREPORT"];
        $cassy = $_COOKIE["CKPSI_DASSY"];
        $cbsgroup = $_COOKIE["CKPSI_BSGROUP"];
        if ($cbsgroup == '') {
            $cbsgroup = "''";
        } else {
            if (strpos($cbsgroup, "|") !== false) {
                $agroup = explode("|", $cbsgroup);
                $cbsgroup = "";
                for ($i = 0; $i < count($agroup); $i++) {
                    $cbsgroup .= "'$agroup[$i]',";
                }
                $cbsgroup = substr($cbsgroup, 0, strlen($cbsgroup) - 1);
            } else {
                $cbsgroup = "'$cbsgroup'";
            }
        }
        $dtto = '';

        if ($cdtfrom == $cdtto) {
            if ($creport == 'a' || $creport == 'n') {
                $thedate = strtotime($cdtfrom . '+1 days');
                $dtto = date('Y-m-d', $thedate) . " 06:59:00";
            } else {
                $dtto = $cdtfrom . " 18:59:00";
            }

            if ($creport == 'a' || $creport == 'm') {
                $cdtfrom .= ' 07:00:00';
            } else {
                $cdtfrom .= ' 19:00:00';
            }
        } else {
            $thedate = strtotime($cdtto . '+1 days');
            $dtto = date('Y-m-d', $thedate) . " 06:59:00";
            $cdtfrom .= ' 07:00:00';
            $creport = "a";
        }

        $rs = $this->ITH_mod->select_output_prd($cdtfrom, $dtto, $cassy, $cbsgroup);
        $spreadsheet = new Spreadsheet();
        $sheet = $spreadsheet->getActiveSheet();
        $sheet->setTitle('OUTPUT_PRD');
        $sheet->setCellValueByColumnAndRow(1, 1, 'Assy Number');
        $sheet->setCellValueByColumnAndRow(2, 1, 'Model');
        $sheet->setCellValueByColumnAndRow(3, 1, 'Lot');
        $sheet->setCellValueByColumnAndRow(4, 1, 'Job Number');
        $sheet->setCellValueByColumnAndRow(5, 1, 'QTY');
        $sheet->setCellValueByColumnAndRow(6, 1, 'Reff. Number');
        $sheet->setCellValueByColumnAndRow(7, 1, 'PIC');
        $sheet->setCellValueByColumnAndRow(8, 1, 'Business Group');
        $sheet->setCellValueByColumnAndRow(9, 1, 'Time');
        $no = 2;
        foreach ($rs as $r) {
            $sheet->setCellValueByColumnAndRow(1, $no, trim($r['ITH_ITMCD']));
            $sheet->setCellValueByColumnAndRow(2, $no, trim($r['MITM_ITMD1']));
            $sheet->setCellValueByColumnAndRow(3, $no, $r['SER_LOTNO']);
            $sheet->setCellValueByColumnAndRow(4, $no, trim($r['SER_DOC']));
            $sheet->setCellValueByColumnAndRow(5, $no, $r['ITH_QTY']);
            $sheet->setCellValueByColumnAndRow(6, $no, $r['ITH_SER']);
            $sheet->setCellValueByColumnAndRow(7, $no, $r['PIC']);
            $sheet->setCellValueByColumnAndRow(8, $no, trim($r['PDPP_BSGRP']));
            $sheet->setCellValueByColumnAndRow(9, $no, $r['ITH_LUPDT']);
            $no++;
        }
        $cdtfrom = substr($cdtfrom, 0, 10);
        $stringjudul = "output produksi $cdtfrom ($creport) " . date(' H i');
        $writer = new Xlsx($spreadsheet);
        $filename = $stringjudul; //save our workbook as this file name

        header('Content-Type: application/vnd.ms-excel');
        header('Content-Disposition: attachment;filename="' . $filename . '.xlsx"');
        header('Cache-Control: max-age=0');
        $writer->save('php://output');
    }

    public function get_output_qc()
    {
        header('Content-Type: application/json');
        $cdtfrom = $this->input->get('indate');
        $cdtto = $this->input->get('indate2');
        $creport = $this->input->get('inreport');
        $cassy = $this->input->get('inassy');
        $cbgroup = $this->input->get('inbgroup');
        $csearchby = $this->input->get('insearchby');
        $sbgroup = "";
        if (is_array($cbgroup)) {
            for ($i = 0; $i < count($cbgroup); $i++) {
                $sbgroup .= "'$cbgroup[$i]',";
            }
        }
        $sbgroup = substr($sbgroup, 0, strlen($sbgroup) - 1);
        if ($sbgroup == '') {
            $sbgroup = "''";
        }
        $dtto = '';
        if ($cdtfrom == $cdtto) {
            if ($creport == 'a' || $creport == 'n') {
                $thedate = strtotime($cdtfrom . '+1 days');
                $dtto = date('Y-m-d', $thedate) . " 06:59:00";
            } else {
                $dtto = $cdtfrom . " 18:59:00";
            }

            if ($creport == 'a' || $creport == 'm') {
                $cdtfrom .= ' 07:00:00';
            } else {
                $cdtfrom .= ' 19:00:00';
            }
        } else {
            $thedate = strtotime($cdtto . '+1 days');
            $dtto = date('Y-m-d', $thedate) . " 06:59:00";
            $cdtfrom .= ' 07:00:00';
        }
        $rs = [];
        if ($csearchby == 'assy') {
            $rs = $this->ITH_mod->select_output_qc($cdtfrom, $dtto, $cassy, $sbgroup);
        } else {
            $rs = $this->ITH_mod->select_output_qc_byjob($cdtfrom, $dtto, $cassy, $sbgroup);
        }
        echo '{"data":' . json_encode($rs) . '}';
    }

    public function get_output_qcsa()
    {
        header('Content-Type: application/json');
        $cdtfrom = $this->input->get('indate');
        $cdtto = $this->input->get('indate2');
        $creport = $this->input->get('inreport');
        $cassy = $this->input->get('inassy');
        $cbgroup = $this->input->get('inbgroup');
        $csearchby = $this->input->get('insearchby');
        $sbgroup = "";
        if (is_array($cbgroup)) {
            for ($i = 0; $i < count($cbgroup); $i++) {
                $sbgroup .= "'$cbgroup[$i]',";
            }
        } else {
            $sbgroup .= "'$cbgroup',";
        }
        $sbgroup = substr($sbgroup, 0, strlen($sbgroup) - 1);
        if ($sbgroup == '') {
            $sbgroup = "''";
        }
        $dtto = '';
        if ($cdtfrom == $cdtto) {
            if ($creport == 'a' || $creport == 'n') {
                $thedate = strtotime($cdtfrom . '+1 days');
                $dtto = date('Y-m-d', $thedate) . " 06:59:00";
            } else {
                $dtto = $cdtfrom . " 18:59:00";
            }

            if ($creport == 'a' || $creport == 'm') {
                $cdtfrom .= ' 07:00:00';
            } else {
                $cdtfrom .= ' 19:00:00';
            }
        } else {
            $thedate = strtotime($cdtto . '+1 days');
            $dtto = date('Y-m-d', $thedate) . " 06:59:00";
            $cdtfrom .= ' 07:00:00';
        }
        $rs = [];
        if ($csearchby == 'assy') {
            $rs = $this->ITH_mod->select_output_qcsa($cdtfrom, $dtto, $cassy, $sbgroup);
        } else {
            $rs = $this->ITH_mod->select_output_qcsa_byjob($cdtfrom, $dtto, $cassy, $sbgroup);
        }
        echo '{"data":' . json_encode($rs) . '}';
    }

    public function get_output_prdsa()
    {
        header('Content-Type: application/json');
        $cdtfrom = $this->input->get('indate');
        $cdtto = $this->input->get('indate2');
        $creport = $this->input->get('inreport');
        $cassy = $this->input->get('inassy');
        $cbgroup = $this->input->get('inbgroup');
        $csearchby = $this->input->get('insearchby');
        $sbgroup = "";
        if (is_array($cbgroup)) {
            for ($i = 0; $i < count($cbgroup); $i++) {
                $sbgroup .= "'$cbgroup[$i]',";
            }
        } else {
            $sbgroup .= "'$cbgroup',";
        }
        $sbgroup = substr($sbgroup, 0, strlen($sbgroup) - 1);
        if ($sbgroup == '') {
            $sbgroup = "''";
        }
        $dtto = '';
        if ($cdtfrom == $cdtto) {
            if ($creport == 'a' || $creport == 'n') {
                $thedate = strtotime($cdtfrom . '+1 days');
                $dtto = date('Y-m-d', $thedate) . " 06:59:00";
            } else {
                $dtto = $cdtfrom . " 18:59:00";
            }

            if ($creport == 'a' || $creport == 'm') {
                $cdtfrom .= ' 07:00:00';
            } else {
                $cdtfrom .= ' 19:00:00';
            }
        } else {
            $thedate = strtotime($cdtto . '+1 days');
            $dtto = date('Y-m-d', $thedate) . " 06:59:00";
            $cdtfrom .= ' 07:00:00';
        }
        $rs = [];
        if ($csearchby == 'assy') {
            $rs = $this->ITH_mod->select_output_prdsa($cdtfrom, $dtto, $cassy, $sbgroup);
        } else {
            $rs = $this->ITH_mod->select_output_prdsa_byjob($cdtfrom, $dtto, $cassy, $sbgroup);
        }
        echo '{"data":' . json_encode($rs) . '}';
    }

    public function get_output_qc_xls()
    {
        date_default_timezone_set('Asia/Jakarta');
        if (!isset($_COOKIE["CKPSI_DDATE"]) && !isset($_COOKIE["CKPSI_DREPORT"]) && !isset($_COOKIE["CKPSI_DASSY"]) || !isset($_COOKIE["CKPSI_BSGROUP"])) {
            exit('no data to be found');
        }
        $cdtfrom = $_COOKIE["CKPSI_DDATE"];
        $cdtto = $_COOKIE["CKPSI_DDATE2"];
        $creport = $_COOKIE["CKPSI_DREPORT"];
        $cassy = $_COOKIE["CKPSI_DASSY"];
        $cbsgroup = $_COOKIE["CKPSI_BSGROUP"];
        if ($cbsgroup == '') {
            $cbsgroup = "''";
        } else {
            if (strpos($cbsgroup, "|") !== false) {
                $agroup = explode("|", $cbsgroup);
                $cbsgroup = "";
                for ($i = 0; $i < count($agroup); $i++) {
                    $cbsgroup .= "'$agroup[$i]',";
                }
                $cbsgroup = substr($cbsgroup, 0, strlen($cbsgroup) - 1);
            } else {
                $cbsgroup = "'$cbsgroup'";
            }
        }
        $dtto = '';

        if ($cdtfrom == $cdtto) {
            if ($creport == 'a' || $creport == 'n') {
                $thedate = strtotime($cdtfrom . '+1 days');
                $dtto = date('Y-m-d', $thedate) . " 06:59:00";
            } else {
                $dtto = $cdtfrom . " 18:59:00";
            }

            if ($creport == 'a' || $creport == 'm') {
                $cdtfrom .= ' 07:00:00';
            } else {
                $cdtfrom .= ' 19:00:00';
            }
        } else {
            $thedate = strtotime($cdtto . '+1 days');
            $dtto = date('Y-m-d', $thedate) . " 06:59:00";
            $cdtfrom .= ' 07:00:00';
        }

        $rs = $this->ITH_mod->select_output_qc($cdtfrom, $dtto, $cassy, $cbsgroup);
        $spreadsheet = new Spreadsheet();
        $sheet = $spreadsheet->getActiveSheet();
        $sheet->setTitle('OUTPUT_PRD');
        $sheet->setCellValueByColumnAndRow(1, 1, 'Assy Number');
        $sheet->setCellValueByColumnAndRow(2, 1, 'Model');
        $sheet->setCellValueByColumnAndRow(3, 1, 'Lot');
        $sheet->setCellValueByColumnAndRow(4, 1, 'Job Number');
        $sheet->setCellValueByColumnAndRow(5, 1, 'QTY');
        $sheet->setCellValueByColumnAndRow(6, 1, 'Reff. Number');
        $sheet->setCellValueByColumnAndRow(7, 1, 'PIC');
        $sheet->setCellValueByColumnAndRow(8, 1, 'Time');
        $no = 2;
        foreach ($rs as $r) {
            $sheet->setCellValueByColumnAndRow(1, $no, $r['ITH_ITMCD']);
            $sheet->setCellValueByColumnAndRow(2, $no, $r['MITM_ITMD1']);
            $sheet->setCellValueByColumnAndRow(3, $no, $r['SER_LOTNO']);
            $sheet->setCellValueByColumnAndRow(4, $no, $r['SER_DOC']);
            $sheet->setCellValueByColumnAndRow(5, $no, $r['ITH_QTY']);
            $sheet->setCellValueByColumnAndRow(6, $no, $r['ITH_SER']);
            $sheet->setCellValueByColumnAndRow(7, $no, $r['PIC']);
            $sheet->setCellValueByColumnAndRow(8, $no, $r['ITH_LUPDT']);
            $no++;
        }
        $stringjudul = "output QC $cdtfrom ($creport) ";
        $writer = new Xlsx($spreadsheet);
        $filename = $stringjudul . date(' H i'); //save our workbook as this file name

        header('Content-Type: application/vnd.ms-excel');
        header('Content-Disposition: attachment;filename="' . $filename . '.xlsx"');
        header('Cache-Control: max-age=0');
        $writer->save('php://output');
    }

    public function get_output_wh()
    {
        header('Content-Type: application/json');
        $csearchby = $this->input->get('insearchby');
        $cdtfrom = $this->input->get('indate');
        $cdtto = $this->input->get('indate2');
        $creport = $this->input->get('inreport');
        $cassy = $this->input->get('inassy');
        $cbsgroup = trim($this->input->get('inbsgrp'));
        $dtto = '';
        if ($cdtfrom == $cdtto) {
            if ($creport == 'a' || $creport == 'n') {
                $thedate = strtotime($cdtfrom . '+1 days');
                $dtto = date('Y-m-d', $thedate) . " 06:59:00";
            } else {
                $dtto = $cdtfrom . " 18:59:00";
            }

            if ($creport == 'a' || $creport == 'm') {
                $cdtfrom .= ' 07:00:00';
            } else {
                $cdtfrom .= ' 19:00:00';
            }
        } else {
            $thedate = strtotime($cdtto . '+1 days');
            $dtto = date('Y-m-d', $thedate) . " 06:59:00";
            $cdtfrom .= ' 07:00:00';
        }
        $rs = [];
        if ($cbsgroup == '-') {
            switch ($csearchby) {
                case 'assy':
                    $rs = $this->ITH_mod->select_output_wh_byassy($cdtfrom, $dtto, $cassy);
                    break;
                case 'job':
                    $rs = $this->ITH_mod->select_output_wh_byjob($cdtfrom, $dtto, $cassy);
                    break;
                case 'reff':
                    $rs = $this->ITH_mod->select_output_wh_byreff($cdtfrom, $dtto, $cassy);
                    break;
            }
        } else {
            switch ($csearchby) {
                case 'assy':
                    $rs = $this->ITH_mod->select_output_wh_byassy_bg($cdtfrom, $dtto, $cassy, $cbsgroup);
                    break;
                case 'job':
                    $rs = $this->ITH_mod->select_output_wh_byjob_bg($cdtfrom, $dtto, $cassy, $cbsgroup);
                    break;
                case 'reff':
                    $rs = $this->ITH_mod->select_output_wh_byreff_bg($cdtfrom, $dtto, $cassy, $cbsgroup);
                    break;
            }
        }
        echo '{"data":' . json_encode($rs) . '}';
    }
    public function get_output_whrtn()
    {
        header('Content-Type: application/json');
        $csearchby = $this->input->get('insearchby');
        $cdtfrom = $this->input->get('indate');
        $cdtto = $this->input->get('indate2');
        $creport = $this->input->get('inreport');
        $cassy = $this->input->get('inassy');
        $cbsgroup = trim($this->input->get('inbsgrp'));
        $dtto = '';
        if ($cdtfrom == $cdtto) {
            if ($creport == 'a' || $creport == 'n') {
                $thedate = strtotime($cdtfrom . '+1 days');
                $dtto = date('Y-m-d', $thedate) . " 06:59:00";
            } else {
                $dtto = $cdtfrom . " 18:59:00";
            }

            if ($creport == 'a' || $creport == 'm') {
                $cdtfrom .= ' 07:00:00';
            } else {
                $cdtfrom .= ' 19:00:00';
            }
        } else {
            $thedate = strtotime($cdtto . '+1 days');
            $dtto = date('Y-m-d', $thedate) . " 06:59:00";
            $cdtfrom .= ' 07:00:00';
        }
        $rs = [];
        if ($cbsgroup == '-') {
            switch ($csearchby) {
                case 'assy':
                    $rs = $this->ITH_mod->select_output_whrtn_byassy($cdtfrom, $dtto, $cassy);
                    break;
                case 'job':
                    $rs = $this->ITH_mod->select_output_whrtn_byjob($cdtfrom, $dtto, $cassy);
                    break;
                case 'reff':
                    $rs = $this->ITH_mod->select_output_whrtn_byreff($cdtfrom, $dtto, $cassy);
                    break;
            }
        } else {
            switch ($csearchby) {
                case 'assy':
                    $rs = $this->ITH_mod->select_output_whrtn_byassy_bg($cdtfrom, $dtto, $cassy, $cbsgroup);
                    break;
                case 'job':
                    $rs = $this->ITH_mod->select_output_whrtn_byjob_bg($cdtfrom, $dtto, $cassy, $cbsgroup);
                    break;
                case 'reff':
                    $rs = $this->ITH_mod->select_output_whrtn_byreff_bg($cdtfrom, $dtto, $cassy, $cbsgroup);
                    break;
            }
        }
        echo '{"data":' . json_encode($rs) . '}';
    }

    public function get_qcwh_unscan()
    {
        $rs = $this->ITH_mod->select_qcwh_unscan();
        $rsscrap = $this->SCR_mod->select_scrapreport_balance();
        $rsdisplay = [];
        foreach ($rs as &$r) {
            foreach ($rsscrap as &$s) {
                if ($r['ITH_DOC'] == $s['DOC_NO'] && $s['SCRQTY'] > 0) {
                    if ($r['ITH_QTY'] >= $s['SCRQTY']) {
                        $r['ITH_QTY'] -= $s['SCRQTY'];
                        $s['SCRQTY'] = 0;
                    } else {
                        $r['ITH_QTY'] -= $r['ITH_QTY'];
                        $s['SCRQTY'] -= $r['ITH_QTY'];
                    }
                }
            }
            unset($s);
            if ($r['ITH_QTY'] > 0) {
                $rsdisplay[] = $r;
            }
        }
        unset($r);
        die('{"data":' . json_encode($rsdisplay) . ',"data_scr":' . json_encode($rsscrap) . '}');
    }

    public function get_output_wh_xls()
    {
        date_default_timezone_set('Asia/Jakarta');
        if (!isset($_COOKIE["CKPSI_DDATE"]) && !isset($_COOKIE["CKPSI_DREPORT"]) && !isset($_COOKIE["CKPSI_DASSY"])) {
            exit('no data to be found');
        }
        $csearchby = $_COOKIE["CKPSI_SEARCHBY"];
        $cdtfrom = $_COOKIE["CKPSI_DDATE"];
        $cdtto = $_COOKIE["CKPSI_DDATE2"];
        $creport = $_COOKIE["CKPSI_DREPORT"];
        $cassy = $_COOKIE["CKPSI_DASSY"];
        $cbsgroup = $_COOKIE["CKPSI_BSGRP"];
        $dtto = '';
        $grpid = $this->session->userdata('gid');
        $usrid = $this->session->userdata('nama');
        if ($cdtfrom == $cdtto) {
            if ($creport == 'a' || $creport == 'n') {
                $thedate = strtotime($cdtfrom . '+1 days');
                $dtto = date('Y-m-d', $thedate) . " 06:59:00";
            } else {
                $dtto = $cdtfrom . " 18:59:00";
            }

            if ($creport == 'a' || $creport == 'm') {
                $cdtfrom .= ' 07:00:00';
            } else {
                $cdtfrom .= ' 19:00:00';
            }
        } else {
            $thedate = strtotime($cdtto . '+1 days');
            $dtto = date('Y-m-d', $thedate) . " 06:59:00";
            $cdtfrom .= ' 07:00:00';
        }

        $rs = [];
        if ($cbsgroup == '-') {
            switch ($csearchby) {
                case 'assy':
                    $rs = $this->ITH_mod->select_output_wh_byassy($cdtfrom, $dtto, $cassy);
                    break;
                case 'job':
                    $rs = $this->ITH_mod->select_output_wh_byjob($cdtfrom, $dtto, $cassy);
                    break;
                case 'reff':
                    $rs = $this->ITH_mod->select_output_wh_byreff($cdtfrom, $dtto, $cassy);
                    break;
            }
        } else {
            switch ($csearchby) {
                case 'assy':
                    $rs = $this->ITH_mod->select_output_wh_byassy_bgxp($cdtfrom, $dtto, $cassy, $cbsgroup);
                    break;
                case 'job':
                    $rs = $this->ITH_mod->select_output_wh_byjob_bgxp($cdtfrom, $dtto, $cassy, $cbsgroup);
                    break;
                case 'reff':
                    $rs = $this->ITH_mod->select_output_wh_byreff_bgxp($cdtfrom, $dtto, $cassy, $cbsgroup);
                    break;
            }
        }
        $this->LOGXDATA_mod->insert(['LOGXDATA_USER' => $usrid, 'LOGXDATA_MENU' => 'EXPORT PPC DATA INC TO MEGA']);
        $spreadsheet = new Spreadsheet();
        $sheet = $spreadsheet->getActiveSheet();
        $sheet->setTitle('OUTPUT_WH');
        $sheet->setCellValueByColumnAndRow(1, 1, 'WO NO');
        $sheet->setCellValueByColumnAndRow(2, 1, 'MODEL CODE');
        $sheet->setCellValueByColumnAndRow(3, 1, 'GRN QTY');
        $no = 2;

        $arow = [];
        if ($grpid == 'INC' || $grpid == 'ROOT') {
            $aser = [];
            $aqty = [];
            $ajob = [];
            foreach ($rs as $r) {
                if ($r['SER_QTY'] > 0) {
                    $aser[] = $r['ITH_SER'];
                    $aqty[] = $r['ITH_QTY'];
                    $ajob[] = $r['SER_DOC'];
                }

                $this->ITH_mod->update_exported_fg_wh($r['ITH_SER']);

                $isfound = false;
                foreach ($arow as &$k) {
                    if ($k['JOB'] == trim($r['SER_DOC']) && $k['ITEM'] == trim($r['ITH_ITMCD'])) {
                        $k['QTY'] += $r['ITH_QTY'];
                        $isfound = true;
                        break;
                    }
                }
                if ($isfound == false) {
                    $arow[] = ['JOB' => trim($r['SER_DOC']), 'ITEM' => trim($r['ITH_ITMCD']), 'QTY' => $r['ITH_QTY']];
                }
                unset($k);
            }
            foreach ($arow as $k) {
                $sheet->setCellValueByColumnAndRow(1, $no, $k['JOB']);
                $sheet->setCellValueByColumnAndRow(2, $no, $k['ITEM']);
                $sheet->setCellValueByColumnAndRow(3, $no, $k['QTY']);
                $no++;
            }
        } else {
            $sheet->setCellValueByColumnAndRow(1, $no, '??');
            $sheet->setCellValueByColumnAndRow(2, $no, '??');
            $sheet->setCellValueByColumnAndRow(3, $no, '??');
            $no++;
        }

        $tgl = str_replace('-', '', substr($cdtfrom, 0, 10));

        $stringjudul = "$cbsgroup $tgl ";
        $writer = new Xlsx($spreadsheet);
        $filename = $stringjudul . date('H:::i'); //save our workbook as this file name

        header('Content-Type: application/vnd.ms-excel');
        header('Content-Disposition: attachment;filename="' . $filename . '.xlsx"');
        header('Cache-Control: max-age=0');
        $writer->save('php://output');
    }

    public function createfile()
    {
        echo base_url() . "<br>";
        echo site_url() . "<br>";
        echo FCPATH . "<br>";
        echo $_SERVER['DOCUMENT_ROOT'] . "<br>";

        #cratetxtfile
        $contentText = "kamu kamu cinta kamu";
        $fp = fopen($_SERVER['DOCUMENT_ROOT'] . "/wms/assets/data_calculation.txt", "wb");
        fwrite($fp, $contentText);
        fclose($fp);
    }

    public function get_output_qcsa_xls()
    {
        date_default_timezone_set('Asia/Jakarta');
        if (!isset($_COOKIE["CKPSI_DDATE"]) && !isset($_COOKIE["CKPSI_DREPORT"]) && !isset($_COOKIE["CKPSI_DASSY"])) {
            exit('no data to be found');
        }

        $cdtfrom = $_COOKIE["CKPSI_DDATE"];
        $cdtto = $_COOKIE["CKPSI_DDATE2"];
        $creport = $_COOKIE["CKPSI_DREPORT"];
        $cbsgroup = $_COOKIE["CKPSI_BSGROUP"];
        $dtto = '';
        $grpid = $this->session->userdata('gid');
        $usrid = $this->session->userdata('nama');
        if ($cdtfrom == $cdtto) {
            if ($creport == 'a' || $creport == 'n') {
                $thedate = strtotime($cdtfrom . '+1 days');
                $dtto = date('Y-m-d', $thedate) . " 06:59:00";
            } else {
                $dtto = $cdtfrom . " 18:59:00";
            }

            if ($creport == 'a' || $creport == 'm') {
                $cdtfrom .= ' 07:00:00';
            } else {
                $cdtfrom .= ' 19:00:00';
            }
        } else {
            $thedate = strtotime($cdtto . '+1 days');
            $dtto = date('Y-m-d', $thedate) . " 06:59:00";
            $cdtfrom .= ' 07:00:00';
        }

        $rs = $this->ITH_mod->select_output_qcsa_byreff_bgxp($cdtfrom, $dtto, $cbsgroup);

        $this->LOGXDATA_mod->insert(['LOGXDATA_USER' => $usrid, 'LOGXDATA_MENU' => 'EXPORT SUB ASSY INC TO MEGA']);
        $spreadsheet = new Spreadsheet();
        $sheet = $spreadsheet->getActiveSheet();
        $sheet->setTitle('OUTPUT_WH');
        $sheet->setCellValueByColumnAndRow(1, 1, 'WO NO');
        $sheet->setCellValueByColumnAndRow(2, 1, 'MODEL CODE');
        $sheet->setCellValueByColumnAndRow(3, 1, 'GRN QTY');
        $no = 2;

        $arow = [];
        if ($grpid == 'OQC2' || $grpid == 'ROOT') {
            $aser = [];
            $aqty = [];
            $ajob = [];
            foreach ($rs as $r) {
                $aser[] = $r['ITH_SER'];
                $aqty[] = urlencode($r['ITH_QTY']);
                $ajob[] = urlencode($r['SER_DOC']);

                $this->ITH_mod->update_exported_qcsa_wh($r['ITH_SER']);

                $isfound = false;
                foreach ($arow as &$k) {
                    if ($k['JOB'] == trim($r['SER_DOC']) && $k['ITEM'] == trim($r['ITH_ITMCD'])) {
                        $k['QTY'] += $r['ITH_QTY'];
                        $isfound = true;
                        break;
                    }
                }
                if ($isfound == false) {
                    $arow[] = ['JOB' => trim($r['SER_DOC']), 'ITEM' => trim($r['ITH_ITMCD']), 'QTY' => $r['ITH_QTY']];
                }
                unset($k);
            }
            foreach ($arow as $k) {
                $sheet->setCellValueByColumnAndRow(1, $no, $k['JOB']);
                $sheet->setCellValueByColumnAndRow(2, $no, $k['ITEM']);
                $sheet->setCellValueByColumnAndRow(3, $no, $k['QTY']);
                $no++;
            }
            if (count($aser) > 0) {
                $Calc_lib = new RMCalculator();
                $Calc_lib->calculate_only_raw_material_resume($aser, $aqty, $ajob);
            }
        } else {
            $sheet->setCellValueByColumnAndRow(1, $no, '??');
            $sheet->setCellValueByColumnAndRow(2, $no, '??');
            $sheet->setCellValueByColumnAndRow(3, $no, '??');
            $no++;
        }

        $tgl = str_replace('-', '', substr($cdtfrom, 0, 10));

        $stringjudul = "$cbsgroup AWIP1 $tgl ";
        $writer = new Xlsx($spreadsheet);
        $filename = $stringjudul . date('H:::i'); //save our workbook as this file name

        header('Content-Type: application/vnd.ms-excel');
        header('Content-Disposition: attachment;filename="' . $filename . '.xlsx"');
        header('Cache-Control: max-age=0');
        $writer->save('php://output');
    }

    public function vtfid()
    {
        $this->load->view('wms/vtransferlocation');
    }

    public function vrincfg()
    {
        $this->load->view('wms/vr_incoming_fg');
    }
    public function vrincfgrtn()
    {
        $data['lgroup'] = $this->RETFG_mod->select_businessgroup();
        $this->load->view('wms_report/vr_incoming_fgrtn', $data);
    }

    public function vrwarehouse_map()
    {
        $this->load->view('wms_report/vfg_wh');
    }

    public function getdataincfg()
    {
        header('Content-Type: application/json');
        $cjob = $this->input->post('injob');
        $csts = $this->input->post('insts');
        $cbg = $this->input->post('inbg');
        $rs = [];
        $a_cjob = explode("#", $cjob);
        $joblist_distinct = [];
        if (count($a_cjob) == 2) {
            if (is_array($cbg)) {
                $therevision = $a_cjob[1] == '' ? 0 : $a_cjob[1];
                $thejob = $a_cjob[0];
                $ttldata = count($cbg);
                for ($i = 0; $i < $ttldata; $i++) {
                    $cr_bg = $cbg[$i];
                    $rst = $this->ITH_mod->selectincfg_with_revision($thejob, $csts, $cr_bg, $therevision);
                    foreach ($rst as $r) {
                        $rs[] = $r;
                        if (!in_array($r['SER_DOC'], $joblist_distinct)) {
                            $joblist_distinct[] = $r['SER_DOC'];
                        }
                    }
                }
            }
        } else {
            if (is_array($cbg)) {
                $ttldata = count($cbg);
                for ($i = 0; $i < $ttldata; $i++) {
                    $cr_bg = $cbg[$i];
                    $rst = $this->ITH_mod->selectincfg($cjob, $csts, $cr_bg);
                    foreach ($rst as $r) {
                        $rs[] = $r;
                        if (!in_array($r['SER_DOC'], $joblist_distinct)) {
                            $joblist_distinct[] = $r['SER_DOC'];
                        }
                    }
                }
            }
        }
        #find split production
        $rssplit = $this->ITH_mod->select_split_production($joblist_distinct);
        foreach ($rs as &$r) {
            foreach ($rssplit as $l) {
                if (strtoupper($r['SER_DOC']) == strtoupper($l['ITH_DOC'])) {
                    switch ($l['ITH_WH']) {
                        case 'ARQA1':
                            $r['QTY_QA'] += $l['ITH_QTY'];
                            break;
                        case 'AFWH3':
                            $r['QTY_WH'] += $l['ITH_QTY'];
                            break;
                    }
                }
            }
        }
        unset($r);
        die(json_encode(['data' => $rs, 'job_distinct' => $joblist_distinct]));
    }
    public function getdataincfgrtn()
    {
        header('Content-Type: application/json');
        $cdoc = $this->input->post('indoc');
        $citem = $this->input->post('initem');
        $cbg = $this->input->post('inbg');
        $csts = $this->input->post('insts');
        $rst = $this->ITH_mod->selectincfgrtn($cbg, $cdoc, $citem, $csts);
        die('{"data":' . json_encode($rst) . '}');
    }
    public function getdataincfg_prd_qc()
    {
        header('Content-Type: application/json');
        $cjob = $this->input->post('injob');
        $csts = $this->input->post('insts');
        $cbg = $this->input->post('inbg');

        $rs = [];
        if (is_array($cbg)) {
            $ttldata = count($cbg);
            for ($i = 0; $i < $ttldata; $i++) {
                $cr_bg = $cbg[$i];
                $rst = $this->ITH_mod->selectincfg_prd_qc($cjob, $csts, $cr_bg);
                foreach ($rst as $r) {
                    $rs[] = $r;
                }
            }
        }
        die('{"data":' . json_encode($rs) . '}');
    }

    public function r_incfg_mega()
    {
        $cjob = $_COOKIE["CKR_INCFG_JOB"];
        $cdt = $_COOKIE["CKR_INCFG_DT"];
        $thedate = strtotime($cdt . '+1 days');
        $dtto = date('Y-m-d', $thedate) . " 06:59:00";
        $cdt .= ' 07:00:00';
        $rs = $this->ITH_mod->selectincfg_mega($cjob, $cdt, $dtto);
        $spr = new Spreadsheet();
        $sht = $spr->getActiveSheet();
        $sht->setTitle("INC_FG");
        $sht->setCellValueByColumnAndRow(1, 1, "WO NO");
        $sht->setCellValueByColumnAndRow(2, 1, "MODEL CODE");
        $sht->setCellValueByColumnAndRow(3, 1, "GRN QTY");
        $no = 2;
        foreach ($rs as $r) {
            $qty = is_null($r['QTY_WH']) ? 0 : number_format($r['QTY_WH']);
            $sht->setCellValueByColumnAndRow(1, $no, trim($r['SER_DOC']));
            $sht->setCellValueByColumnAndRow(2, $no, trim($r['SER_ITMID']));
            $sht->setCellValueByColumnAndRow(3, $no, $qty);
            $no++;
        }
        $writer = new Xlsx($spr);
        $filename = "INC FG FOR MEGA.xlsx";
        header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
        header('Content-Disposition: attachment;filename="' . $filename . '"');
        header('Cache-Control: max-age=0');
        $writer->save('php://output');
    }

    public function vroutfg()
    {
        echo 'baking....';
    }

    public function search()
    {
        header('Content-Type: application/json');
        $cid = $this->input->get('inItem');
        $rs = $this->ITH_mod->selectAll($cid);
        echo json_encode($rs);
    }

    public function vclosing()
    {
        $data['lwh'] = $this->MSTLOCG_mod->selectall();
        $this->load->view('wms/vclosinginv', $data);
    }

    public function compareinventory()
    {
        header('Content-Type: application/json');
        $cwh = $this->input->get('inwh');
        $cwh = $cwh === 'AFWH3' ? 'AFSMT' : $cwh;
        $cdate = $this->input->get('indate');
        switch ($cwh) {
            case 'NFWH4RT':
                $rs = $this->ITH_mod->select_compare_inventory_fg_rtn($cwh, $cdate);
                break;
            case 'QAFG':
                $rs = $this->ITH_mod->select_compare_inventory_fg_qa($cwh, $cdate);
                break;
            case 'AWIP1':
                $rs = $this->ITH_mod->select_compare_inventory_fg_qa($cwh, $cdate);
                break;
            case 'AFWH3':
                $rs = $this->ITH_mod->select_compare_inventory_fg_fresh($cwh, $cdate);
                break;
            case 'AFWH3RT':
                $rs = $this->ITH_mod->select_compare_inventory_fg_rtn_asset($cwh, $cdate);
                break;
            default:
                $rs = $this->ITH_mod->select_compare_inventory($cwh, $cdate);
        }
        die(json_encode(['data' => $rs]));
    }

    public function form_report_critical_part()
    {
        $data['lgroup'] = $this->BGROUP_mod->selectall();
        $this->load->view('wms_report/vcritical_part', $data);
    }

    public function getstock_wh()
    {
        date_default_timezone_set('Asia/Jakarta');
        header('Content-Type: application/json');

        $citem = $this->input->get('initem');
        $cdate = $this->input->get('indate');
        $cbgroup = $this->input->get('inbgroup');
        $inSearchBy = $this->input->get('insearch_by');
        $sbgroup = "";
        if (is_array($cbgroup)) {
            for ($i = 0; $i < count($cbgroup); $i++) {
                $sbgroup .= "'$cbgroup[$i]',";
            }
            $sbgroup = substr($sbgroup, 0, strlen($sbgroup) - 1);
            if ($sbgroup == '') {
                $sbgroup = "''";
            }
        } else {
            $sbgroup = "-";
        }
        $odate = strtotime($cdate . ' +1 days');
        $tomorrow = date("Y-m-d 07:00:00", $odate);
        $cwh = $_COOKIE["CKPSI_WH"];
        $main_wh = null;
        $inc_wh = null;
        $preparation_wh = null;
        switch ($cwh) {
            case 'NFWH4RT':
                $main_wh = ['AFQART', 'NFWH4RT', 'ARSHPRTN'];
                $inc_wh = ['AFQART', 'NFWH4RT'];
                $preparation_wh = 'ARSHPRTN';
                break;
            case 'AFWH3RT':
                $main_wh = ['AFQART2', 'AFWH3RT', 'ARSHPRTN2'];
                $inc_wh = ['AFQART2', 'AFWH3RT'];
                $preparation_wh = 'ARSHPRTN2';
                break;
        }
        if ($inSearchBy === 'item_code') {
            if ($sbgroup == "-") {
                $rs = in_array($cwh, $this->FG_RETURN_WH) ? $this->ITH_mod->select_psi_stock_date_wbg_fg_rtn($main_wh, $inc_wh, $main_wh, $preparation_wh, $citem, $cdate) :
                $this->ITH_mod->select_psi_stock_date_wbg($cwh, $citem, $cdate);
            } else {
                $rs = in_array($cwh, $this->FG_RETURN_WH) ? $this->ITH_mod->select_psi_stock_date_fg_rtn($main_wh, $inc_wh, $main_wh, $preparation_wh, $citem, $sbgroup, $cdate) :
                $this->ITH_mod->select_psi_stock_date($cwh, $citem, $sbgroup, $cdate);
            }
        } else {
            if ($sbgroup == "-") {
                $rs = in_array($cwh, $this->FG_RETURN_WH) ? $this->ITH_mod->select_psi_stock_date_wbg_fg_rtn($main_wh, $inc_wh, $main_wh, $preparation_wh, $citem, $cdate) :
                $this->ITH_mod->selectPSIStockDateWBGByDescription($cwh, $citem, $cdate);
            } else {
                $rs = in_array($cwh, $this->FG_RETURN_WH) ? $this->ITH_mod->select_psi_stock_date_fg_rtn($main_wh, $inc_wh, $main_wh, $preparation_wh, $citem, $sbgroup, $cdate) :
                $this->ITH_mod->selectPSIStockAtDateByDescripton($cwh, $citem, $sbgroup, $cdate);
            }
        }
        $atomorrow = ['date' => $tomorrow];
        die('{"data":' . json_encode($rs) . ',"info": ' . json_encode($atomorrow) . '}');
    }

    public function test_getstock_1sql()
    {
        header('Content-Type: application/json');
        $citem = $this->input->post('initem');
        $rsfinal = [];
        foreach ($citem as $i) {
            $rsfinal[] = $this->ITH_mod->select_psi_stock_date_wbg_query('AFWH3', $i, '2021-08-11');
        }
        die('{"request":' . json_encode($citem) . ',"response":' . json_encode($rsfinal) . '}');
    }

    public function test_getstock_allsql()
    {
        header('Content-Type: application/json');
        $citem = $this->input->post('initem');
        $rsfinal = [];
        foreach ($citem as $i) {
            $rsfinal[] = $this->ITH_mod->select_psi_stock_date_wbg_query('AFWH3', $i, '2021-08-11');
        }
        die('{"request":' . json_encode($citem) . ',"response":' . json_encode($rsfinal) . '}');
    }

    public function getqtyrack()
    {
        $cwh = $_COOKIE["CKPSI_WH"];
        $cid = $this->input->get('inid');
        $dataw = ['SER_ID' => $cid];
        if ($this->SER_mod->check_Primary($dataw) > 0) {
        } else {
            $myar = [];
            $myar[] = ["cd" => "00", "msg" => "ID not found"];
            echo '{"data":' . json_encode($myar), '}';
        }
    }

    public function vdisposerm()
    {
        $this->load->view('wms/vdispose_rm');
    }
    public function vdisposefg()
    {
        $this->load->view('wms/vdispose_fg');
    }

    public function getdisposerm()
    {
        header('Content-Type: application/json');
        $rs = $this->ITH_mod->select_stock_scrap_rm();
        echo '{"data":';
        echo json_encode($rs);
        echo '}';
    }
    public function getdisposefg()
    {
        header('Content-Type: application/json');
        $rs = $this->ITH_mod->select_stock_scrap_fg();
        echo '{"data":';
        echo json_encode($rs);
        echo '}';
    }

    public function dispose_fg_save()
    {
        date_default_timezone_set('Asia/Jakarta');
        $currdate = date('Ymd');
        $aid = $this->input->post('inid');
        $aitem = $this->input->post('initem');
        $aqty = $this->input->post('inqty');
        $cdate = $this->input->post('indate');
        $cwh = $this->input->post('inwh');
        $myar = array();
        $ttlrows = count($aitem);
        $rs = $this->ITH_mod->select_stock_scrap_fg();
        $lsno = $this->ITH_mod->lastsdoc_fg_dispose();
        $lsno++;
        $lastdoc = "DSPS" . $currdate . $lsno;
        $ttlinserted = 0;
        for ($i = 0; $i < $ttlrows; $i++) {
            foreach ($rs as $r) {
                if (trim($aid[$i]) == trim($r['ITH_SER'])) {
                    if ((float) $aqty[$i] <= (float) $r['ITH_QTY']) {
                        $datas = [
                            'ITH_SER' => $aid[$i],
                            'ITH_ITMCD' => $aitem[$i],
                            'ITH_DATE' => $cdate,
                            'ITH_FORM' => 'OUT-SCR-FG',
                            'ITH_DOC' => $lastdoc,
                            'ITH_QTY' => -(float) $aqty[$i],
                            'ITH_WH' => $cwh,
                            'ITH_USRID' => $this->session->userdata('nama'),
                        ];
                        $resith = $this->ITH_mod->insert_disposefg($datas);
                        $ttlinserted += $resith;
                    }
                }
            }
        }
        if ($ttlinserted > 0) {
            $datar = array("cd" => "1", "msg" => "Saved " . $ttlinserted);
        } else {
            $datar = array("cd" => "0", "msg" => "Nothing saved");
        }
        array_push($myar, $datar);
        echo '{"info":';
        echo json_encode($myar);
        echo '}';
    }

    public function dispose_save()
    {
        date_default_timezone_set('Asia/Jakarta');
        $currdate = date('Ymd');
        $aitem = $this->input->post('initem');
        $aqty = $this->input->post('inqty');
        $cdate = $this->input->post('indate');
        $cwh = $this->input->post('inwh');
        $myar = array();
        $ttlrows = count($aitem);
        $rs = $this->ITH_mod->select_stock_scrap_rm();
        $lsno = $this->ITH_mod->lastsdoc_dispose();
        $lsno++;
        $lastdoc = "DSP" . $currdate . $lsno;
        $ttlinserted = 0;
        for ($i = 0; $i < $ttlrows; $i++) {
            foreach ($rs as $r) {
                if (trim($aitem[$i]) == trim($r['ITH_ITMCD'])) {
                    if ((float) $aqty[$i] <= (float) $r['ITH_QTY']) {
                        $datas = array(
                            'ITH_ITMCD' => $aitem[$i],
                            'ITH_DATE' => $cdate,
                            'ITH_FORM' => 'OUT-SCR-RM',
                            'ITH_DOC' => $lastdoc,
                            'ITH_QTY' => -(float) $aqty[$i],
                            'ITH_WH' => $cwh,
                            'ITH_USRID' => $this->session->userdata('nama'),
                        );
                        $resith = $this->ITH_mod->insert_disposerm($datas);
                        $ttlinserted += $resith;
                    }
                }
            }
        }
        if ($ttlinserted > 0) {
            $datar = array("cd" => "1", "msg" => "Saved " . $ttlinserted);
        } else {
            $datar = array("cd" => "0", "msg" => "Nothing saved");
        }
        array_push($myar, $datar);
        echo '{"info":';
        echo json_encode($myar);
        echo '}';
    }

    public function get_vis_fg_location()
    {
        $citem = $this->input->get('initem');
        $rs = $this->ITH_mod->select_fg_location_ploc($citem);
        $myar = [];
        $myar[] = (count($rs) > 0) ? ["cd" => "1", "msg" => "go ahead"] : ["cd" => "0", "msg" => "not found"];
        die('{"status":' . json_encode($myar) . ',"data":' . json_encode($rs) . '}');
    }

    public function get_unscanned_FG_v1()
    {
        $this->checkSession();
        //SUMMARIZE UNSCANNED
        $myar = [];
        $rs = $this->ITH_mod->select_qcwh_unscan_recap_lastscan();
        if (count($rs) > 0) {
            $rack = '';
            foreach ($rs as $r) {
                $rack = $r['ITH_LOC'];
                break;
            }
            $msgtodis = '=== unscannded list ===<br>';
            $msgtodis .= '=== Loc. : ' . $rack . ' ===<br>';
            foreach ($rs as $r) {
                $msgtodis .= $r['ITH_ITMCD'] . ' ' . $r['TTLBOX'] . ' BOX <br>';
            }
            $myar[] = ['cd' => '1', 'msg' => $msgtodis];
        } else {
            $myar[] = ['cd' => '0', 'msg' => ''];
        }
        exit('{"status" : ' . json_encode($myar) . '}');
    }
    public function get_unscanned_FG_v2()
    {
        $this->checkSession();
        //SUMMARIZE UNSCANNED
        $myar = [];
        $rs = $this->ITH_mod->select_qcwh_unscan_recap();
        if (count($rs) > 0) {
            $rack = '';
            foreach ($rs as $r) {
                $rack = $r['ITH_LOC'];
                break;
            }
            $msgtodis = '=== unscannded list ===<br>';
            $msgtodis .= '=== Loc. : ' . $rack . ' ===<br>';
            foreach ($rs as $r) {
                $msgtodis .= $r['ITH_ITMCD'] . ' ' . $r['TTLBOX'] . ' BOX <br>';
            }
            $myar[] = ['cd' => '1', 'msg' => $msgtodis];
        } else {
            $myar[] = ['cd' => '0', 'msg' => ''];
        }
        exit('{"status" : ' . json_encode($myar) . '}');
    }

    public function gettxhistory()
    {
        header('Content-Type: application/json');
        $cwh = $this->input->get('inwh');
        $citemcd = trim($this->input->get('initemcode'));
        $cdate1 = $this->input->get('indate1');
        $cdate2 = $this->input->get('indate2');
        $inSearchBy = $this->input->get('inSearchBy');
        if ($inSearchBy === 'item_code') {
            $rsbef = $this->ITH_mod->select_txhistory_bef($cwh, $citemcd, $cdate1);
            $rs = $this->ITH_mod->select_txhistory($cwh, $citemcd, $cdate1, $cdate2);
        } else {
            $rsbef = $this->ITH_mod->selectTXHistoryBeforeByItemDescription($cwh, $citemcd, $cdate1);
            $rs = $this->ITH_mod->selectTXHistoryByDescription($cwh, $citemcd, $cdate1, $cdate2);
        }
        $rstoret = [];
        $myar = [];
        if (count($rsbef) > 0) {
            foreach ($rsbef as $t) {
                $rstoret[] = ['ITH_ITMCD' => $t['ITH_ITMCD'], 'MITM_ITMD1' => $t['MITM_ITMD1'], 'ITH_FORM' => '', 'ITH_DOC' => '', 'ITH_DATEKU' => '', 'INCQTY' => '', 'OUTQTY' => '', 'ITH_BAL' => $t['BALQTY'], 'UOM' => $t['UOM'], 'ITH_DATEC' => '' , 'MITM_ACTIVE'=> $t['MITM_ACTIVE']];
                foreach ($rs as $r) {
                    if ($r['ITH_ITMCD'] === $t['ITH_ITMCD']) {
                        $rstoret[] = $r;
                    }
                }
            }
            $current_balance = 0;
            foreach ($rstoret as &$r) {
                if ($r['ITH_FORM'] == '') {
                    $current_balance = $r['ITH_BAL'];
                } else {
                    $r['ITH_BAL'] = $current_balance + $r['INCQTY'] + $r['OUTQTY'];
                    $current_balance = $r['ITH_BAL'];
                }
                $r['OUTQTY'] = abs((float) $r['OUTQTY']);
            }
            unset($r);
            $myar[] = ['cd' => 1, 'msg' => 'go ahead'];
        } else {
            $myar[] = ['cd' => 0, 'msg' => 'not found'];
        }
        die(json_encode([
            'status' => $myar,
            'data' => $rstoret,
        ]));
    }
    public function gettxhistory_parent()
    {
        header('Content-Type: application/json');
        $fg_wh = ['AFWH3', 'AFWH3RT', 'NFWH4', 'NFWH4RT', 'AWIP1', 'QAFG', 'AFWH9SC', 'NFWH9SC'];
        $cwh = $this->input->get('inwh');
        $citemcd = trim($this->input->get('initemcode'));
        $cdate1 = $this->input->get('indate1');
        $cdate2 = $this->input->get('indate2');
        if (in_array($cwh, $fg_wh)) {
            if ($cwh === 'NFWH4RT') {
                $rsbef = $this->ITH_mod->select_txhistory_bef_parent_fg_with_additional_wh($cwh, $citemcd, $cdate1, [$cwh, 'ARSHPRTN', 'AFQART']);
                $rs = $this->ITH_mod->select_txhistory_parent_fg_with_additional_wh($cwh, $citemcd, $cdate1, $cdate2, [$cwh, 'ARSHPRTN', 'AFQART']);
            } elseif ($cwh === 'AFWH3RT') {
                $rsbef = $this->ITH_mod->select_txhistory_bef_parent_fg_with_additional_wh($cwh, $citemcd, $cdate1, [$cwh, 'ARSHPRTN2', 'AFQART2']);
                $rs = $this->ITH_mod->select_txhistory_parent_fg_with_additional_wh($cwh, $citemcd, $cdate1, $cdate2, [$cwh, 'ARSHPRTN2', 'AFQART2']);
            } elseif ($cwh === 'AWIP1') {
                $rsbef = $this->ITH_mod->select_txhistory_bef_parent_fg_with_additional_wh($cwh, $citemcd, $cdate1, [$cwh]);
                $rs = $this->ITH_mod->select_txhistory_parent_fg_with_additional_wh($cwh, $citemcd, $cdate1, $cdate2, [$cwh]);
            } elseif ($cwh === 'QAFG') {
                $rsbef = $this->ITH_mod->select_txhistory_bef_parent_fg_with_additional_wh($cwh, $citemcd, $cdate1, [$cwh]);
                $rs = $this->ITH_mod->select_txhistory_parent_fg_with_additional_wh($cwh, $citemcd, $cdate1, $cdate2, [$cwh]);
            } elseif ($cwh === 'AFWH3') {
                $rsbef = $this->ITH_mod->select_txhistory_bef_parent_fg_with_additional_wh($cwh, $citemcd, $cdate1, [$cwh, 'ARSHP']);
                $rs = $this->ITH_mod->select_txhistory_parent_fg_with_additional_wh($cwh, $citemcd, $cdate1, $cdate2, [$cwh, 'ARSHP']);
            } else {
                $rsbef = $this->ITH_mod->select_txhistory_bef_parent_fg($cwh, $citemcd, $cdate1);
                $rs = $this->ITH_mod->select_txhistory_parent_fg($cwh, $citemcd, $cdate1, $cdate2);
            }
        } else {
            $rsbef = $this->ITH_mod->select_txhistory_bef_parent($cwh, $citemcd, $cdate1);
            $rs = $this->ITH_mod->select_txhistory_parent($cwh, $citemcd, $cdate1, $cdate2);
        }
        $rstoret = [];
        $myar = [];
        if (!empty($rs) && empty($rsbef)) {
            $rsbef = [
                ['ITRN_ITMCD' => $citemcd, 'MGAQTY' => 0, 'WQT' => 0],
            ];
        }
        if (count($rsbef) > 0) {
            foreach ($rsbef as $t) {
                $rstoret[] = ['ITRN_ITMCD' => $t['ITRN_ITMCD'], 'ISUDT' => '', 'MGAQTY' => '', 'WQT' => '', 'MGABAL' => $t['MGAQTY'], 'WBAL' => $t['WQT']];
                foreach ($rs as $r) {
                    if (strtoupper($r['ITRN_ITMCD']) == strtoupper($t['ITRN_ITMCD'])) {
                        $rstoret[] = $r;
                    }
                }
            }
            $current_balance = 0;
            $current_balanceWMS = 0;
            foreach ($rstoret as &$r) {
                if ($r['ISUDT'] == '') {
                    $current_balance = $r['MGABAL'];
                    $current_balanceWMS = $r['WBAL'];
                } else {
                    $r['MGABAL'] = $current_balance + $r['MGAQTY'];
                    $r['WBAL'] = $current_balanceWMS + $r['WQT'];
                    $current_balance = $r['MGABAL'];
                    $current_balanceWMS = $r['WBAL'];
                }
            }
            unset($r);
            $myar[] = ['cd' => 1, 'msg' => 'go ahead'];
        } else {
            $myar[] = ['cd' => 0, 'msg' => 'not found'];
        }
        die(json_encode(['status' => $myar, 'data' => $rstoret]));
    }
    public function gettxhistory_customs()
    {
        header('Content-Type: application/json');
        $searchby = $this->input->get('searchby');
        $citemcd = trim($this->input->get('initemcode'));
        $cdate1 = $this->input->get('indate1');
        $cdate2 = $this->input->get('indate2');
        $rsbef = [];
        $rs = [];
        switch ($searchby) {
            case 'itm':
                $rsbef = $this->ITH_mod->select_txhistory_customs_bef_d1($citemcd, $cdate1);
                $rs = $this->ITH_mod->select_txhistory_customs(['RPSTOCK_ITMNUM' => $citemcd], $cdate2);
                break;
            case 'aju':
                $rsbef = $this->ITH_mod->select_txhistory_customs_bef_d1_byaju($citemcd);
                $rs = $this->ITH_mod->select_txhistory_customs(['RPSTOCK_NOAJU' => $citemcd], $cdate2);
                break;
            case 'daf':
                $rsbef = $this->ITH_mod->select_txhistory_customs_bef_d1_bydaftar($citemcd, $cdate1);
                $rs = $this->ITH_mod->select_txhistory_customs(['RPSTOCK_BCNUM' => $citemcd], $cdate2);
                break;
        }
        $rstoret = [];
        $myar = [];
        if (count($rsbef) > 0) {
            foreach ($rsbef as $t) {
                $rstoret[] = [
                    'RPSTOCK_ITMNUM' => $t['RPSTOCK_ITMNUM'], 'MITM_ITMD1' => $t['MITM_ITMD1'], 'AJU' => $t['RPSTOCK_NOAJU'], 'DAFTAR' => $t['RPSTOCK_BCNUM'], 'DOC' => $t['RPSTOCK_DOC'], 'IODATE' => $t['INCDATE'], 'INCQTY' => '', 'OUTQTY' => '', 'BAL' => $t['BALQTY'], 'INCDATE' => $t['INCDATE'], 'HEADER' => '1',
                ];
                foreach ($rs as &$r) {
                    if ($r['ITMCD'] == $t['RPSTOCK_ITMNUM'] && $r['RPSTOCK_NOAJU'] == $t['RPSTOCK_NOAJU'] && $r['RPSTOCK_DOC'] == $t['RPSTOCK_DOC'] && $r['MUSED'] == '') {
                        $rstoret[] = $r;
                        $r['MUSED'] = 'PASS';
                    }
                }
                unset($r);
            }
            $current_balance = 0;
            foreach ($rstoret as &$r) {
                if ($r['HEADER'] == '1') {
                    $current_balance = $r['BAL'];
                } else {
                    $r['BAL'] = $current_balance + $r['INCQTY'] + $r['OUTQTY'];
                    $current_balance = $r['BAL'];
                }
                $r['OUTQTY'] = abs((int) $r['OUTQTY']);
            }
            unset($r);
            $myar[] = ['cd' => 1, 'msg' => 'go ahead'];
        } else {
            $myar[] = ['cd' => 0, 'msg' => 'not found'];
        }
        exit('{"status": ' . json_encode($myar) . ', "data" : ' . json_encode($rstoret) . '}');
    }

    public function bcstock()
    {
        header('Content-Type: application/json');
        $incdoc = $this->input->post('incdoc');
        $incitem = $this->input->post('incitem');
        $outdoc = $this->input->post('outdoc');
        $rs = $this->ZRPSTOCK_mod->select_all_where(['RPSTOCK_REMARK' => $outdoc, 'RPSTOCK_ITMNUM' => $incitem, 'RPSTOCK_DOC' => $incdoc]);
        die(json_encode(['data' => $rs]));
    }
    public function bcstock_cancel()
    {
        if ($this->session->userdata('status') != "login") {
            $myar[] = ["cd" => 0, "msg" => "Session is expired please reload page"];
            die(json_encode(['status' => $myar]));
        }
        date_default_timezone_set('Asia/Jakarta');
        header('Content-Type: application/json');
        $incdoc = $this->input->post('incdoc');
        $incitem = $this->input->post('incitem');
        $outdoc = $this->input->post('outdoc');
        $inpin = $this->input->post('inpin');
        $deleted_at = date('Y-m-d') . ' 18:00:00';
        if ($inpin != 'WEAGREE') {
            $myar[] = ['cd' => 0, 'msg' => 'PIN is not valid'];
            die('{"status":' . json_encode($myar) . '}');
        }
        $respon = $this->ZRPSTOCK_mod->updatebyId(['RPSTOCK_REMARK' => $outdoc, 'RPSTOCK_ITMNUM' => $incitem, 'RPSTOCK_DOC' => $incdoc, 'deleted_at is null' => null], ['deleted_at' => $deleted_at]);
        $this->LOGXDATA_mod->insert(['LOGXDATA_MENU' => 'CANCEL EXBC TX', 'LOGXDATA_USER' => $this->session->userdata('nama')]);
        $myar[] = ['cd' => 1, 'msg' => 'OK', 'respon' => $respon];
        die('{"status":' . json_encode($myar) . '}');
    }

    public function bcstock_rev()
    {
        header('Content-Type: application/json');
        $rsneed = $this->ZRPSTOCK_mod->query("SELECT RTRIM(RPSTOCK_ITMNUM) ITM,abs(SUM(RPSTOCK_QTY)) ITMQT FROM RPSAL_BCSTOCK WHERE deleted_at IS NULL AND RPSTOCK_REMARK='21/12/04/0006-R' AND RPSTOCK_BCDATE>='2021-10-31'
        GROUP BY RPSTOCK_ITMNUM
        order by 1");

        $rscandidate = $this->ZRPSTOCK_mod->query("SELECT * FROM RPSAL_BCSTOCK WHERE RPSTOCK_ITMNUM IN (
            SELECT RTRIM(RPSTOCK_ITMNUM) FROM RPSAL_BCSTOCK WHERE deleted_at IS NULL AND RPSTOCK_REMARK='21/12/04/0006-R' AND RPSTOCK_BCDATE>='2021-10-31'
            GROUP BY RPSTOCK_ITMNUM
            ) AND deleted_at IS NULL AND (RPSTOCK_REMARK LIKE '%adj%' OR RPSTOCK_REMARK LIKE '%MIG%')
            order by RPSTOCK_BCDATE desc");

        $rscanfix = [];
        foreach ($rsneed as &$r) {
            foreach ($rscandidate as &$c) {
                if ($r['ITM'] == RTRIM($c['RPSTOCK_ITMNUM'])) {
                    $tobe = $c['RPSTOCK_QTY'] + $r['ITMQT'];
                    $c['RPSTOCK_QTY'] = $tobe;
                    $this->ZRPSTOCK_mod->updatebyId(['id' => $c['id']], ['RPSTOCK_QTY' => $tobe]);

                    // $c['RPSTOCK_QTY'] += $r['ITMQT'];
                    $rscanfix[] = $c;
                    break;
                }
            }
            unset($c);
        }
        unset($r);
        die(json_encode(['data' => $rsneed, 'canfix' => $rscanfix]));
    }

    public function gettxhistory_to_xls()
    {
        date_default_timezone_set('Asia/Jakarta');
        if (!isset($_COOKIE["CKPSI_DDT1"]) || !isset($_COOKIE["CKPSI_DDT2"]) || !isset($_COOKIE["CKPSI_DITEMCD"]) || !isset($_COOKIE["CKPSI_DWH"])) {
            exit('no data to be found');
        }
        ini_set('max_execution_time', '-1');
        $cwh = $_COOKIE["CKPSI_DWH"];
        $citemcd = trim($_COOKIE["CKPSI_DITEMCD"]);
        $cdate1 = $_COOKIE["CKPSI_DDT1"];
        $cdate2 = $_COOKIE["CKPSI_DDT2"];
        $searchBy = $_COOKIE["CKPSI_SEARCHBY"];
        if ($searchBy === 'item_code') {
            $rsbef = $this->ITH_mod->select_txhistory_bef($cwh, $citemcd, $cdate1);
            $rs = $this->ITH_mod->select_txhistory($cwh, $citemcd, $cdate1, $cdate2);
        } else {
            $rsbef = $this->ITH_mod->selectTXHistoryBeforeByItemDescription($cwh, $citemcd, $cdate1);
            $rs = $this->ITH_mod->selectTXHistoryByDescription($cwh, $citemcd, $cdate1, $cdate2);
        }
        $rstoret = [];
        $myar = [];
        if (count($rs) > 0) {
            foreach ($rsbef as $t) {
                $rstoret[] = ['ITH_ITMCD' => $t['ITH_ITMCD'], 'MITM_ITMD1' => $t['MITM_ITMD1'], 'ITH_FORM' => '', 'ITH_DOC' => '', 'ITH_DATEKU' => '', 'INCQTY' => '', 'OUTQTY' => '', 'ITH_BAL' => $t['BALQTY']];
                foreach ($rs as $r) {
                    if ($r['ITH_ITMCD'] == $t['ITH_ITMCD']) {
                        $rstoret[] = $r;
                    }
                }
            }
            $current_balance = 0;
            foreach ($rstoret as &$r) {
                if ($r['ITH_FORM'] == '') {
                    $current_balance = $r['ITH_BAL'];
                } else {
                    $r['ITH_BAL'] = $current_balance + $r['INCQTY'] + $r['OUTQTY'];
                    $current_balance = $r['ITH_BAL'];
                }
            }
            unset($r);
            $myar[] = ['cd' => 1, 'msg' => 'go ahead'];
        } else {
            foreach ($rsbef as $t) {
                $rstoret[] = ['ITH_ITMCD' => $t['ITH_ITMCD'], 'MITM_ITMD1' => $t['MITM_ITMD1'], 'ITH_FORM' => '', 'ITH_DOC' => '', 'ITH_DATEKU' => '', 'INCQTY' => '', 'OUTQTY' => '', 'ITH_BAL' => $t['BALQTY']];
            }
        }
        $rs = null;
        unset($rs);
        $rsbef = null;
        unset($rsbef);
        $spreadsheet = new Spreadsheet();
        $sheet = $spreadsheet->getActiveSheet();
        $sheet->setTitle('TXHISTORY');
        $sheet->setCellValueByColumnAndRow(1, 1, 'Date');
        $sheet->setCellValueByColumnAndRow(2, 1, 'Item Code');
        $sheet->setCellValueByColumnAndRow(3, 1, 'Item Name');
        $sheet->setCellValueByColumnAndRow(4, 1, 'Warehouse');
        $sheet->setCellValueByColumnAndRow(5, 1, 'Event');
        $sheet->setCellValueByColumnAndRow(6, 1, 'Document');
        $sheet->setCellValueByColumnAndRow(7, 1, 'QTY');
        $sheet->setCellValueByColumnAndRow(7, 2, 'IN');
        $sheet->setCellValueByColumnAndRow(8, 2, 'OUT');
        $sheet->setCellValueByColumnAndRow(9, 2, 'BALANCE');
        $sheet->mergeCells('A1:A2');
        $sheet->mergeCells('B1:B2');
        $sheet->mergeCells('C1:C2');
        $sheet->mergeCells('D1:D2');
        $sheet->mergeCells('E1:E2');
        $sheet->mergeCells('F1:F2');
        $sheet->mergeCells('G1:I1');
        $sheet->getStyle('A1')->getAlignment()->setHorizontal('center');
        $sheet->getStyle('B1')->getAlignment()->setHorizontal('center');
        $sheet->getStyle('C1')->getAlignment()->setHorizontal('center');
        $sheet->getStyle('D1')->getAlignment()->setHorizontal('center');
        $sheet->getStyle('E1')->getAlignment()->setHorizontal('center');
        $sheet->getStyle('F1')->getAlignment()->setHorizontal('center');
        $sheet->getStyle('G1')->getAlignment()->setHorizontal('center');
        $sheet->getStyle('A1')->getAlignment()->setVertical(\PhpOffice\PhpSpreadsheet\Style\Alignment::VERTICAL_CENTER);
        $sheet->getStyle('B1')->getAlignment()->setVertical(\PhpOffice\PhpSpreadsheet\Style\Alignment::VERTICAL_CENTER);
        $sheet->getStyle('C1')->getAlignment()->setVertical(\PhpOffice\PhpSpreadsheet\Style\Alignment::VERTICAL_CENTER);
        $sheet->getStyle('D1')->getAlignment()->setVertical(\PhpOffice\PhpSpreadsheet\Style\Alignment::VERTICAL_CENTER);
        $sheet->getStyle('E1')->getAlignment()->setVertical(\PhpOffice\PhpSpreadsheet\Style\Alignment::VERTICAL_CENTER);
        $sheet->getStyle('F1')->getAlignment()->setVertical(\PhpOffice\PhpSpreadsheet\Style\Alignment::VERTICAL_CENTER);
        $sheet->getStyle('G1')->getAlignment()->setVertical(\PhpOffice\PhpSpreadsheet\Style\Alignment::VERTICAL_CENTER);
        $no = 3;
        foreach ($rstoret as $r) {
            $sheet->setCellValueByColumnAndRow(1, $no, $r['ITH_DATEKU']);
            $sheet->setCellValueByColumnAndRow(2, $no, trim($r['ITH_ITMCD']));
            $sheet->setCellValueByColumnAndRow(3, $no, $r['MITM_ITMD1']);
            $sheet->setCellValueByColumnAndRow(4, $no, $cwh);
            $sheet->setCellValueByColumnAndRow(5, $no, $r['ITH_FORM']);
            $sheet->setCellValueByColumnAndRow(6, $no, $r['ITH_DOC']);
            $sheet->setCellValueByColumnAndRow(7, $no, $r['INCQTY']);
            $sheet->setCellValueByColumnAndRow(8, $no, abs((float) $r['OUTQTY']));
            $sheet->setCellValueByColumnAndRow(9, $no, $r['ITH_BAL']);
            $no++;
        }
        $sheet->getColumnDimension('A')->setAutoSize(true);
        $sheet->getColumnDimension('B')->setAutoSize(true);
        $sheet->getColumnDimension('C')->setAutoSize(true);
        $sheet->getColumnDimension('D')->setAutoSize(true);
        $sheet->getColumnDimension('E')->setAutoSize(true);
        $sheet->getColumnDimension('F')->setAutoSize(true);
        $sheet->getColumnDimension('G')->setAutoSize(true);
        $sheet->getColumnDimension('H')->setAutoSize(true);
        $sheet->getColumnDimension('I')->setAutoSize(true);
        $cdtfrom = substr($cdate1, 0, 10);
        $stringjudul = "TX HISTORY $cwh, $cdtfrom TO $cdate2 " . date(' H i');
        $writer = new Xlsx($spreadsheet);
        $filename = $stringjudul; //save our workbook as this file name

        header('Content-Type: application/vnd.ms-excel');
        header('Content-Disposition: attachment;filename="' . $filename . '.xlsx"');
        header('Cache-Control: max-age=0');
        $writer->save('php://output');
    }

    public function getlogexport($pn = 10)
    {
        header('Content-Type: application/json');
        $rs = $this->LOGXDATA_mod->selectlast_n($pn);
        echo '{"data": ' . json_encode($rs) . ' }';
    }

    public function checkSession()
    {
        $myar = [];
        if ($this->session->userdata('status') != "login") {
            $myar[] = ["cd" => "0", "msg" => "Session is expired please reload page"];
            exit(json_encode($myar));
        }
    }

    public function traceid()
    {
        header('Content-Type: application/json');
        $cid = $this->input->get('inid');
        $rs = $this->ITH_mod->selectbyunique($cid);
        $myar = [];
        if (count($rs) > 0) {
            $myar[] = ['cd' => 1, 'msg' => 'go ahead'];
        } else {
            $myar[] = ['cd' => 0, 'msg' => 'there is no transaction'];
        }
        die('{"status": ' . json_encode($myar) . ', "data":' . json_encode($rs) . '}');
    }

    public function sync_parent_based_doc()
    {
        header('Content-Type: application/json');
        $doc = $this->input->post('doc');
        $loccd = $this->input->post('loccd');
        $item = $this->input->post('item');
        $qty = $this->input->post('qty');
        $reff = $this->input->post('reff');
        $isudt = $this->input->post('isudt');
        $docCode = $this->input->post('docCode');
        $docBG = $this->input->post('docBG');
        $rs = [];
        $myar = [];
        if ($docCode == 'TRF') {
            $rs[] = [
                'ITH_ITMCD' => $item,
                'ITH_DATE' => $isudt,
                'ITH_FORM' => $qty > 0 ? 'TRFIN-RM' : 'TRFOUT-RM',
                'ITH_DOC' => $doc,
                'ITH_WH' => $loccd,
                'ITH_QTY' => $qty,
                'ITH_REMARK' => $reff,
                'ITH_LUPDT' => $isudt . ' 08:08:08',
                'ITH_USRID' => $this->session->userdata('nama'),
            ];

            if (strtoupper(substr($doc, 0, 3)) === 'SP-') {
                switch ($docBG) {
                    case 'PSI1PPZIEP':
                        $cwh_plant = 'PLANT1';
                        break;
                    case 'PSI2PPZADI':
                        $cwh_plant = 'PLANT2';
                        break;
                    case 'PSI2PPZINS':
                        $cwh_plant = 'PLANT_NA';
                        break;
                    case 'PSI2PPZOMC':
                        $cwh_plant = 'PLANT_NA';
                        break;
                    case 'PSI2PPZOMI':
                        $cwh_plant = 'PLANT2';
                        break;
                    case 'PSI2PPZSSI':
                        $cwh_plant = 'PLANT_NA';
                        break;
                    case 'PSI2PPZSTY':
                        $cwh_plant = 'PLANT2';
                        break;
                    case 'PSI2PPZTDI':
                        $cwh_plant = 'PLANT2';
                        break;
                }

                $rs[] = [
                    'ITH_ITMCD' => $item,
                    'ITH_DATE' => $isudt,
                    'ITH_FORM' => $qty * -1 > 0 ? 'TRFIN-RM' : 'TRFOUT-RM',
                    'ITH_DOC' => $doc,
                    'ITH_WH' => $cwh_plant,
                    'ITH_QTY' => $qty * -1,
                    'ITH_REMARK' => $loccd,
                    'ITH_LUPDT' => $isudt . ' 08:08:08',
                    'ITH_USRID' => $this->session->userdata('nama'),
                ];
            } else {
                $rs[] = [
                    'ITH_ITMCD' => $item,
                    'ITH_DATE' => $isudt,
                    'ITH_FORM' => $qty * -1 > 0 ? 'TRFIN-RM' : 'TRFOUT-RM',
                    'ITH_DOC' => $doc,
                    'ITH_WH' => $reff,
                    'ITH_QTY' => $qty * -1,
                    'ITH_REMARK' => $loccd,
                    'ITH_LUPDT' => $isudt . ' 08:08:08',
                    'ITH_USRID' => $this->session->userdata('nama'),
                ];
            }
            $myar[] = ['cd' => 1, 'msg' => 'OK, please refresh to see the changes'];
            $this->ITH_mod->insertb($rs);
        } else {
            $rs[] = [
                'ITH_ITMCD' => $item,
                'ITH_DATE' => $isudt,
                'ITH_FORM' => $qty > 0 ? 'ADJ-INC' : 'ADJ-OUT',
                'ITH_DOC' => $doc,
                'ITH_WH' => $loccd,
                'ITH_QTY' => $qty,
                'ITH_REMARK' => $reff,
                'ITH_LUPDT' => $isudt . ' 08:08:08',
                'ITH_USRID' => $this->session->userdata('nama'),
            ];
            $this->ITH_mod->insertb($rs);
            $myar[] = ['cd' => 1, 'msg' => 'OK Adjusted, please refresh to see the changes'];
        }
        die(json_encode(['status' => $myar, 'data' => $rs]));
    }

    public function transaction()
    {
        header('Content-Type: application/json');
        $fg_wh = ['AFWH3', 'AFWH3RT', 'NFWH4', 'NFWH4RT', 'QAFG', 'AWIP1', 'AFWH9SC', 'NFWH9SC'];

        $date = $this->input->get('date');
        $item = $this->input->get('item');
        $location = $this->input->get('location');

        if (in_array($location, $fg_wh)) {
            $rsParent = $this->XFTRN_mod->select_where(
                ['CONVERT(DATE,FTRN_ISUDT) ITRN_ISUDT', 'RTRIM(FTRN_DOCCD) ITRN_DOCCD', 'RTRIM(FTRN_DOCNO) ITRN_DOCNO', "IOQT QTY", 'RTRIM(FTRN_REFNO1) ITRN_REFNO1', 'RTRIM(FTRN_DOCCD) ITRN_DOCCD', 'RTRIM(FTRN_BSGRP) ITRN_BSGRP'],
                ['FTRN_ISUDT' => $date, 'FTRN_ITMCD' => $item, 'FTRN_LOCCD' => $location]
            );
        } else {
            $rsParent = $this->XITRN_mod->select_where(
                ['CONVERT(DATE,ITRN_ISUDT) ITRN_ISUDT', 'RTRIM(ITRN_DOCCD) ITRN_DOCCD', 'RTRIM(ITRN_DOCNO) ITRN_DOCNO', "IOQT QTY", 'RTRIM(ITRN_REFNO1) ITRN_REFNO1', 'RTRIM(ITRN_DOCCD) ITRN_DOCCD', 'RTRIM(ITRN_BSGRP) ITRN_BSGRP'],
                ['ITRN_ISUDT' => $date, 'ITRN_ITMCD' => $item, 'ITRN_LOCCD' => $location]
            );
        }

        switch ($location) {
            case 'NFWH4RT':
                $rsChild = $this->ITH_mod->select_view_where_and_locationIn(
                    ['ITH_DATEC' => $date, 'ITH_ITMCD' => $item],
                    [$location, 'ARSHPRTN', 'AFQART']
                );
                break;
            case 'AFWH3RT':
                $rsChild = $this->ITH_mod->select_view_where_and_locationIn(
                    ['ITH_DATEC' => $date, 'ITH_ITMCD' => $item],
                    [$location, 'ARSHPRTN2', 'AFQART2']
                );
                break;
            case 'AFWH3':
                $rsChild = $this->ITH_mod->select_view_where_and_locationIn(
                    ['ITH_DATEC' => $date, 'ITH_ITMCD' => $item],
                    [$location, 'ARSHP']
                );
                break;
            default:
                $rsChild = $this->ITH_mod->select_view_where(['ITH_DATEC' => $date, 'ITH_ITMCD' => $item, 'ITH_WH' => $location]);
        }

        die(json_encode(['parent' => $rsParent, 'child' => $rsChild]));
    }

    public function get_bcblc_tx()
    {
        header('Content-Type: application/json');
        $citem = $this->input->get('initem');
        $rs = $this->BCBLC_mod->select_balance($citem);
        die('{"data":' . json_encode($rs) . '}');
    }

    public function searchbin()
    {
        header('Content-Type: application/json');
        $csearchby = $this->input->get('insearch_by');
        $csearchval = $this->input->get('insearch_val');
        $rs = [];
        switch ($csearchby) {
            case 'date':
                $rs = $this->ITH_mod->selectbin_history(['CONVERT(DATE,ITH_DATE)' => $csearchval]);
                break;
            case 'job':
                $rs = $this->ITH_mod->selectbin_history(['ITH_DOC' => $csearchval]);
                break;
            case 'reff':
                $rs = $this->ITH_mod->selectbin_history(['ITH_SER' => $csearchval]);
                break;
        }
        $myar[] = count($rs) > 0 ? ['cd' => 1, 'msg' => 'Go ahead'] : ['cd' => 0, 'msg' => 'not found'];
        die('{"tx":' . json_encode($rs) . ', "status":' . json_encode($myar) . '}');
    }

    public function change_dt_byreff()
    {
        $this->checkSession();
        header('Content-Type: application/json');
        $myar = [];
        $reffno = $this->input->post('inreffno');
        $forminc = $this->input->post('inform_trigger_inc');
        $formout = $this->input->post('inform_trigger_out');
        $newdate = $this->input->post('innewdate');
        $result = 0;
        $this->ITH_mod->tobin_backdate($this->session->userdata('nama'), $reffno, $forminc);
        $this->ITH_mod->tobin_backdate($this->session->userdata('nama'), $reffno, $formout);
        $result += $this->ITH_mod->updatebyId(['ITH_FORM' => $forminc, 'ITH_SER' => $reffno], ['ITH_LUPDT' => $newdate]);
        $result += $this->ITH_mod->updatebyId(['ITH_FORM' => $formout, 'ITH_SER' => $reffno], ['ITH_LUPDT' => $newdate]);
        $myar[] = $result > 1 ? ['cd' => 1, 'msg' => 'OK'] : ['cd' => 0, 'msg' => 'Please contact admin'];
        die('{"status":' . json_encode($myar) . '}');
    }

    public function calculate_rm_cutoff()
    {
        header('Content-Type: application/json');
        $cdate = $this->input->get('date');
        $rs = $this->SERD_mod->select_reff_cutoff_not_calculated($cdate);
        $pser = [];
        $pserqty = [];
        $pjob = [];
        foreach ($rs as $r) {
            $pser[] = $r['ITH_SER'];
            $pserqty[] = $r['STKQTY'];
            $pjob[] = $r['SER_DOC'];
        }
        if (count($pser)) {
            $Calc_lib = new RMCalculator();
            $rs = $Calc_lib->calculate_raw_material_resume($pser, $pserqty, $pjob);
        }
        die('{"data":' . json_encode($rs) . '}');
    }

    public function smtstock()
    {
        header('Content-Type: application/json');
        $cutoffdate = $this->input->get('date');
        $rschild = [];
        $rs_rm_null_c = $this->ITH_mod->select_rm_null_bo_zeroed_combined($cutoffdate);
        $rs_rm_null = $this->ITH_mod->select_rm_null_bo_zeroed($cutoffdate); #because of set 0
        $rs_rm_notnull = $this->SERD_mod->select_nonnull_rm($cutoffdate);
        $rs_rm_subassy = $this->SERD_mod->select_nonnull_rm_subassy($cutoffdate);
        $a_sample = [];
        foreach ($rs_rm_null as $r) {
            if (!in_array($r['SERD2SER_SMP'], $a_sample)) {
                $a_sample[] = $r['SERD2SER_SMP'];
            }
        }
        $rstemp = count($a_sample) > 0 ? $this->SERD_mod->select_group_byser($a_sample) : [];
        if (count($rstemp)) {
            foreach ($rs_rm_null as $r) {
                foreach ($rstemp as $b) {
                    if ($r['SERD2SER_SMP'] == $b['SERD2_SER']) {
                        $rschild[] = [
                            'ITH_ITMCD' => $b['ITH_ITMCD'],
                            'BEFQTY' => $b['QTPER'] * $r['BEFQTYFG'],
                            'PLOTQTY' => 0,
                            'ITH_WH' => $r['ITH_WH'],
                            'REMARK' => 'FROM SAMPLE ' . $r['SERD2SER_SMP'],
                        ];
                    }
                }
            }
        }
        $rssummary = [];
        if (count($rs_rm_notnull)) {
            $rschild = array_merge($rschild, $rs_rm_notnull, $rs_rm_subassy, $rs_rm_null_c);
            foreach ($rschild as $n) {
                $isfound = false;
                foreach ($rssummary as &$s) {
                    if ($n['ITH_ITMCD'] === $s['ITH_ITMCD']) {
                        $s['BEFQTY'] += $n['BEFQTY'];
                        $isfound = true;
                        break;
                    }
                }
                unset($s);
                if (!$isfound) {
                    $rssummary[] = [
                        'ITH_ITMCD' => $n['ITH_ITMCD'],
                        'BEFQTY' => $n['BEFQTY'],
                    ];
                }
            }
        }
        die(json_encode(['data' => $rssummary]));
    }

    public function getRMFromDeliveredFG()
    {
        header('Content-Type: application/json');
        $date1 = $this->input->get('date1');
        $date2 = $this->input->get('date2');
        $rs = $this->ITH_mod->select_RMFromDeliveredFG($date1, $date2);
        $rsCJ = $this->ITH_mod->select_RMFromDeliveredFG_CJ($date1, $date2);
        $isCalculationOk = true;
        foreach ($rs as &$r) {
            if (!$r['ITH_ITMCD']) {
                $isCalculationOk = false;
            }
            foreach ($rsCJ as &$n) {
                if ($r['ITH_ITMCD'] === $n['ITH_ITMCD'] && $n['BEFQTY'] > 0) {
                    $r['BEFQTY'] += $n['BEFQTY'];
                    $n['BEFQTY'] = 0;
                    break;
                }
            }
            unset($n);
        }
        unset($r);
        $myar = $isCalculationOk ? ['cd' => 1, 'msg' => 'Calculations is OK'] : ['cd' => 0, 'some Calculations are not OK'];
        die(json_encode(['data' => $rs, 'status' => $myar]));
    }

    public function out_wor_wip()
    {
        ini_set('max_execution_time', '-1');
        header('Content-Type: application/json');
        $date = $this->input->get('date');
        $msg = '';
        if (empty($date)) {
            $msg = 'out_wor with current date';
            log_message('error', $msg);
            $date = date('Y-m-d');
        } else {
            $msg = 'out_wor with selected date (' . $date . ')';
            log_message('error', $msg);
        }
        $rs = $this->ITH_mod->select_out_wip($date);
        $rszero = []; #$this->ITH_mod->select_out_wip_zeroed($date);
        $a_sample = [];
        foreach ($rszero as $r) {
            if (!in_array($r['SERD2_SER_SMP'], $a_sample)) {
                $a_sample[] = $r['SERD2_SER_SMP'];
            }
        }

        $rstemp = !empty($a_sample) ? $this->SERD_mod->select_group_byser($a_sample) : [];
        $tosave = [];
        if (!empty($rstemp)) {
            foreach ($rszero as $r) {
                foreach ($rstemp as $b) {
                    if ($r['SERD2_SER_SMP'] == $b['SERD2_SER']) {
                        if ($r['SER_QTYLOT'] * $b['QTPER'] > 0) {
                            $tosave[] = [
                                'ITH_FORM' => 'WOR', 'ITH_DATE' => substr($r['ITH_LUPDT'], 0, 10), 'ITH_LUPDT' => $r['ITH_LUPDT'], 'ITH_ITMCD' => $b['ITH_ITMCD'], 'ITH_DOC' => $r['SER_DOC'], 'ITH_QTY' => -$r['SER_QTYLOT'] * $b['QTPER'], 'ITH_WH' => $r['OUTWH'], 'ITH_REMARK' => $r['SER_ID'],
                            ];
                        }
                    }
                }
            }
        }

        $rowaffected = 0;
        foreach ($rs as $r) {
            $tosave[] = [
                'ITH_FORM' => 'WOR', 'ITH_DATE' => substr($r['ITH_LUPDT'], 0, 10), 'ITH_LUPDT' => $r['ITH_LUPDT'], 'ITH_ITMCD' => $r['SERD2_ITMCD'], 'ITH_DOC' => $r['SER_DOC'], 'ITH_QTY' => -$r['QTY'], 'ITH_WH' => $r['OUTWH'], 'ITH_REMARK' => $r['SER_ID'],
            ];
        }
        if ($tosave) {
            $rowaffected = $this->ITH_mod->insertb($tosave);
        }
        $myar[] = ['cd' => 1, 'msg' => $msg, 'rowa' => $rowaffected];
        log_message('error', 'finish out_wor');
        die(json_encode(['status' => $myar]));
    }

    public function out_wor_wip_subassy()
    {
        ini_set('max_execution_time', '-1');
        header('Content-Type: application/json');
        date_default_timezone_set('Asia/Jakarta');
        $date = $this->input->get('date');
        $msg = '';
        if (empty($date)) {
            $msg = 'out_wor with current date';
            log_message('error', $msg);
            $date = date('Y-m-d');
        } else {
            $msg = 'out_wor with selected date (' . $date . ')';
            log_message('error', $msg);
        }
        $rs = $this->ITH_mod->select_out_wip_fromsubassy($date);
        $tosave = [];

        $rowaffected = 0;
        foreach ($rs as $r) {
            $tosave[] = [
                'ITH_FORM' => 'WOR', 'ITH_DATE' => substr($r['ITH_LUPDT'], 0, 10), 'ITH_LUPDT' => $r['ITH_LUPDT'], 'ITH_ITMCD' => $r['SERD2_ITMCD'], 'ITH_DOC' => $r['SER_DOC'], 'ITH_QTY' => -$r['QTY'], 'ITH_WH' => $r['OUTWH'], 'ITH_REMARK' => $r['SERML_COMID'],
            ];
        }
        if ($tosave) {
            $rowaffected = $this->ITH_mod->insertb($tosave);
        }
        $myar[] = ['cd' => 1, 'msg' => $msg, 'rowa' => $rowaffected];
        log_message('error', 'finish out_wor');
        die(json_encode(['status' => $myar]));
    }

    public function compare_tx_vs_customs()
    {
        header('Content-Type: application/json');
        $cutoffdate = $this->input->get('cutoffdate');
        $rschild = [];
        $rs_rm_null_c = $this->ITH_mod->select_rm_null_bo_zeroed_combined($cutoffdate);
        $rs_rm_null = $this->ITH_mod->select_rm_null_bo_zeroed($cutoffdate); #because of set 0
        $rs_rm_notnull = $this->SERD_mod->select_nonnull_rm($cutoffdate);
        $rs_rm_subassy = $this->SERD_mod->select_nonnull_rm_subassy($cutoffdate);
        $rs_bcstock = $this->RCV_mod->select_bcstock($cutoffdate);
        $rsGrade = $this->MSTITM_mod->selectAllGrade(['MITMGRP_ITMCD', 'MITMGRP_ITMCD_GRD']);
        $a_sample = [];
        foreach ($rs_rm_null as $r) {
            if (!in_array($r['SERD2SER_SMP'], $a_sample)) {
                $a_sample[] = $r['SERD2SER_SMP'];
            }
        }
        $rstemp = count($a_sample) > 0 ? $this->SERD_mod->select_group_byser($a_sample) : [];
        if (count($rstemp)) {
            foreach ($rs_rm_null as $r) {
                foreach ($rstemp as $b) {
                    if ($r['SERD2SER_SMP'] == $b['SERD2_SER']) {
                        $rschild[] = [
                            'ITH_ITMCD' => $b['ITH_ITMCD'],
                            'BEFQTY' => $b['QTPER'] * $r['BEFQTYFG'],
                            'PLOTQTY' => 0,
                            'ITH_WH' => $r['ITH_WH'],
                            'REMARK' => 'FROM SAMPLE ' . $r['SERD2SER_SMP'],
                        ];
                    }
                }
            }
        }
        if (count($rs_rm_notnull)) {
            $rschild = array_merge($rschild, $rs_rm_notnull, $rs_rm_subassy, $rs_rm_null_c);
        }
        #1st Filter
        foreach ($rschild as &$r) {
            foreach ($rs_bcstock as &$b) {
                if ($r['ITH_ITMCD'] == $b['RPSTOCK_ITMNUM'] && $r['BEFQTY'] != $r['PLOTQTY'] && $b['CURRENTSTOCK'] > 0) {
                    $balance = $r['BEFQTY'] - $r['PLOTQTY'];
                    if ($balance > $b['CURRENTSTOCK']) {
                        $r['PLOTQTY'] += $b['CURRENTSTOCK'];
                        $b['CURRENTSTOCK'] = 0;
                    } else {
                        $b['CURRENTSTOCK'] -= $balance;
                        $r['PLOTQTY'] += $balance;
                    }
                    if ($r['BEFQTY'] == $r['PLOTQTY']) {
                        break;
                    }
                }
            }
            unset($b);
        }
        unset($r);
        #1st Filter rank
        foreach ($rschild as &$r) {
            if ($r['BEFQTY'] != $r['PLOTQTY']) {
                foreach ($rsGrade as $g) {
                    if ($g['MITMGRP_ITMCD_GRD'] === $r['ITH_ITMCD']) {
                        foreach ($rs_bcstock as &$b) {
                            if ($g['MITMGRP_ITMCD'] == $b['RPSTOCK_ITMNUM'] && $r['BEFQTY'] != $r['PLOTQTY'] && $b['CURRENTSTOCK'] > 0) {
                                $balance = $r['BEFQTY'] - $r['PLOTQTY'];
                                if ($balance > $b['CURRENTSTOCK']) {
                                    $r['PLOTQTY'] += $b['CURRENTSTOCK'];
                                    $b['CURRENTSTOCK'] = 0;
                                } else {
                                    $b['CURRENTSTOCK'] -= $balance;
                                    $r['PLOTQTY'] += $balance;
                                }
                                $r['REMARK'] .= 'BY ' . $g['MITMGRP_ITMCD'];
                                if ($r['BEFQTY'] === $r['PLOTQTY']) {
                                    break;
                                }
                            }
                        }
                        unset($b);
                        break;
                    }
                }
            }
        }
        unset($r);
        #end

        #2nd Filter
        $a_itemcdonly = [];
        foreach ($rschild as $r) {
            if ($r['BEFQTY'] != $r['PLOTQTY']) {
                if (!in_array($r['ITH_ITMCD'], $a_itemcdonly)) {
                    $a_itemcdonly[] = $r['ITH_ITMCD'];
                }

                foreach ($rsGrade as $g) {
                    if ($g['MITMGRP_ITMCD_GRD'] === $r['ITH_ITMCD']) {
                        if (!in_array($g['MITMGRP_ITMCD'], $a_itemcdonly)) {
                            $a_itemcdonly[] = $g['MITMGRP_ITMCD'];
                        }

                        break;
                    }
                }
            }
        }
        $a_itemcdonly_count = count($a_itemcdonly);
        $str = "";
        for ($i = 0; $i < $a_itemcdonly_count; $i++) {
            $str .= "'" . $a_itemcdonly[$i] . "',";
        }
        $str = substr($str, 0, strlen($str) - 1);
        $rsrcv = $this->RCV_mod->select_do_formigration($str, $cutoffdate);
        $rsrcv2 = [];

        foreach ($rschild as &$r) {
            foreach ($rsrcv as &$b) {
                if ($r['ITH_ITMCD'] == $b['PGRN_ITMCD'] && $r['BEFQTY'] != $r['PLOTQTY'] && $b['CURRENTSTOCK'] > 0) {
                    $balance = $r['BEFQTY'] - $r['PLOTQTY'];
                    if ($balance > $b['CURRENTSTOCK']) {
                        $r['PLOTQTY'] += $b['CURRENTSTOCK'];
                        $b['CURRENTSTOCK'] = 0;
                    } else {
                        $b['CURRENTSTOCK'] -= $balance;
                        $r['PLOTQTY'] += $balance;
                    }
                    if ($r['BEFQTY'] == $r['PLOTQTY']) {
                        break;
                    }
                }
            }
            unset($b);
        }
        unset($r);

        #2nd filter rank
        foreach ($rschild as &$r) {
            if ($r['BEFQTY'] != $r['PLOTQTY']) {
                foreach ($rsGrade as $g) {
                    if ($g['MITMGRP_ITMCD_GRD'] === $r['ITH_ITMCD']) {
                        foreach ($rsrcv as &$b) {
                            if ($g['MITMGRP_ITMCD'] == $b['PGRN_ITMCD'] && $r['BEFQTY'] != $r['PLOTQTY'] && $b['CURRENTSTOCK'] > 0) {
                                $balance = $r['BEFQTY'] - $r['PLOTQTY'];
                                if ($balance > $b['CURRENTSTOCK']) {
                                    $r['PLOTQTY'] += $b['CURRENTSTOCK'];
                                    $b['CURRENTSTOCK'] = 0;
                                } else {
                                    $b['CURRENTSTOCK'] -= $balance;
                                    $r['PLOTQTY'] += $balance;
                                }
                                if ($r['BEFQTY'] == $r['PLOTQTY']) {
                                    break;
                                }
                            }
                        }
                        unset($b);
                        break;
                    }
                }
            }
        }
        unset($r);

        foreach ($rsrcv as $r) {
            if ($r['CURRENTSTOCK'] != $r['TTLRCV']) {
                $rsrcv2[] = $r;
            }
        }
        #end
        #find from ICS
        $itemFindInICS = [];
        foreach ($rschild as $r) {
            if ($r['BEFQTY'] != $r['PLOTQTY']) {
                $theitem = $r['ITH_ITMCD'];
                foreach ($rsGrade as $g) {
                    if ($g['MITMGRP_ITMCD_GRD'] === $r['ITH_ITMCD']) {
                        $theitem = $g['MITMGRP_ITMCD'];
                        break;
                    }
                }
                if (!in_array($theitem, $itemFindInICS)) {
                    $itemFindInICS[] = $theitem;
                }
            }
        }
        $rsics = $this->RCV_mod->select_ics($itemFindInICS, $cutoffdate);
        foreach ($rschild as &$r) {
            foreach ($rsics as &$b) {
                if ($r['ITH_ITMCD'] == $b['RPSTOCK_ITMNUM'] && $r['BEFQTY'] != $r['PLOTQTY'] && $b['CURRENTSTOCK'] > 0) {
                    $balance = $r['BEFQTY'] - $r['PLOTQTY'];
                    if ($balance > $b['CURRENTSTOCK']) {
                        $r['PLOTQTY'] += $b['CURRENTSTOCK'];
                        $b['CURRENTSTOCK'] = 0;
                    } else {
                        $b['CURRENTSTOCK'] -= $balance;
                        $r['PLOTQTY'] += $balance;
                    }
                    if ($r['BEFQTY'] == $r['PLOTQTY']) {
                        break;
                    }
                }
            }
            unset($b);
        }
        unset($r);
        #filter rank ics
        foreach ($rschild as &$r) {
            if ($r['BEFQTY'] != $r['PLOTQTY']) {
                foreach ($rsGrade as $g) {
                    if ($g['MITMGRP_ITMCD_GRD'] === $r['ITH_ITMCD']) {
                        foreach ($rsics as &$b) {
                            if ($g['MITMGRP_ITMCD'] == $b['RPSTOCK_ITMNUM'] && $r['BEFQTY'] != $r['PLOTQTY'] && $b['CURRENTSTOCK'] > 0) {
                                $balance = $r['BEFQTY'] - $r['PLOTQTY'];
                                if ($balance > $b['CURRENTSTOCK']) {
                                    $r['PLOTQTY'] += $b['CURRENTSTOCK'];
                                    $b['CURRENTSTOCK'] = 0;
                                } else {
                                    $b['CURRENTSTOCK'] -= $balance;
                                    $r['PLOTQTY'] += $balance;
                                }
                                if ($r['BEFQTY'] == $r['PLOTQTY']) {
                                    break;
                                }
                            }
                        }
                        unset($b);
                        break;
                    }
                }
            }
        }
        unset($r);
        #end filter rank

        $rsics3 = $this->RCV_mod->select_ics3($itemFindInICS, $cutoffdate);
        foreach ($rschild as &$r) {
            foreach ($rsics3 as &$b) {
                if ($r['ITH_ITMCD'] == $b['RPSTOCK_ITMNUM'] && $r['BEFQTY'] != $r['PLOTQTY'] && $b['CURRENTSTOCK'] > 0) {
                    $balance = $r['BEFQTY'] - $r['PLOTQTY'];
                    if ($balance > $b['CURRENTSTOCK']) {
                        $r['PLOTQTY'] += $b['CURRENTSTOCK'];
                        $b['CURRENTSTOCK'] = 0;
                    } else {
                        $b['CURRENTSTOCK'] -= $balance;
                        $r['PLOTQTY'] += $balance;
                    }
                    if ($r['BEFQTY'] == $r['PLOTQTY']) {
                        break;
                    }
                }
            }
            unset($b);
        }
        unset($r);
        $rsicsused = [];
        foreach ($rsics as $r) {
            if ($r['CURRENTSTOCK'] != $r['rcv_qty']) {
                $r['REMARK'] = 'ics';
                $rsicsused[] = $r;
            }
        }
        foreach ($rsics3 as $r) {
            if ($r['CURRENTSTOCK'] != $r['rcv_qty']) {
                $r['REMARK'] = 'ics3';
                $rsicsused[] = $r;
            }
        }
        die('{"data": ' . json_encode($rschild) . ',"rsrcv2":' . json_encode($rsrcv2) . ',"icsdata":' . json_encode($rsicsused) . '}');
    }

    public function form_st_itinventory()
    {
        $this->load->view('wms/vst_itinventory');
    }

    public function adjust_base_mega()
    {
        $this->checkSession();
        header('Content-Type: application/json');
        date_default_timezone_set('Asia/Jakarta');
        $current_datetime = date('Y-m-d H:i:s');
        $cwh = $this->input->post('inwh');
        $cwh = $cwh === 'AFWH3' ? 'AFSMT' : $cwh;
        $cdate = $this->input->post('indate');
        $cpin = $this->input->post('inpin');
        $usrid = $this->session->userdata('nama');
        $myar = [];
        #validate PIN
        if ($cpin != 'NAHCLOSINGBROX') {
            $myar[] = ['cd' => "0", 'msg' => 'PIN is not valid'];
            die('{"status":' . json_encode($myar) . '}');
        }
        #end

        $dateObj = new DateTime($cdate);
        $dateObj->modify('+1 day');
        $dateTosave = $dateObj->format('Y-m-d');
        $dateTimeTosave = $dateObj->format('Y-m-d 06:59:59');
        $dateformat = $dateObj->format('Ym');
        $rs = $this->ITH_mod->select_compare_inventory($cwh, $cdate);
        $rsadj = [];
        foreach ($rs as $r) {
            $balance = $r['STOCKQTY'] - $r['MGAQTY'];
            if ($balance != 0) {
                if ($balance > 0) {
                    $ith_form = 'ADJ-I-OUT';
                    $balance = -$balance;
                } else {
                    $ith_form = 'ADJ-I-INC';
                    $balance = abs($balance);
                }
                $rsadj[] = [
                    'ITH_ITMCD' => $r['ITH_ITMCD'] ? $r['ITH_ITMCD'] : $r['ITRN_ITMCD'],
                    'ITH_QTY' => $balance,
                    'ITH_DATE' => $dateTosave,
                    'ITH_LUPDT' => $dateTimeTosave,
                    'ITH_WH' => $cwh,
                    'ITH_DOC' => 'DOCINV' . $dateformat,
                    'ITH_FORM' => $ith_form,
                    'ITH_USRID' => $usrid,
                    'ITH_REMARK' => 'do at ' . $current_datetime,
                ];
            }
        }
        if ($this->ITH_mod->insertb($rsadj)) {
            $myar[] = ['cd' => "1", 'msg' => 'OK, just regenerate it'];
        } else {
            $myar[] = ['cd' => "0", 'msg' => 'Nothing adjusted'];
        }
        die('{"status":' . json_encode($myar) . ',"data":' . json_encode($rsadj) . '}');
    }
    public function saveadjust_base_mega()
    {
        $this->checkSession();
        header('Content-Type: application/json');
        date_default_timezone_set('Asia/Jakarta');
        $cwh = $this->input->post('inwh');
        $cdate = $this->input->post('indate');
        $cpin = $this->input->post('inpin');
        $myar = [];
        #validate PIN
        if ($cpin != 'NAHCLOSINGBROX_SAVE') {
            $myar[] = ['cd' => "0", 'msg' => 'PIN is not valid'];
            die('{"status":' . json_encode($myar) . '}');
        }
        #end
        $cwh_inv = $cwh == 'AFSMT' ? 'AFWH3' : $cwh;

        $dateObj = new DateTime($cdate);
        $_MONTH = $dateObj->format('m');
        $_YEAR = $dateObj->format('Y');
        $WHERE = ['INV_MONTH' => $_MONTH, 'INV_YEAR' => $_YEAR, 'INV_WH' => $cwh_inv];
        $rssaved = $this->RPSAL_INVENTORY_mod->select_compare_where($WHERE);
        $dateObj->modify('+1 day');
        $dateTimeTosave = $dateObj->format('Y-m-d 06:59:59');
        switch ($cwh) {
            case 'NFWH4RT':
                $rs = $this->ITH_mod->select_compare_inventory_fg_rtn($cwh, $cdate);
                break;
            case 'AFWH3RT':
                $rs = $this->ITH_mod->select_compare_inventory_fg_rtn_asset($cwh, $cdate);
                break;
            default:
                $rs = $this->ITH_mod->select_compare_inventory($cwh, $cdate);
        }
        $rsadj = [];
        foreach ($rs as $r) {
            $balance = $r['STOCKQTY'] - $r['MGAQTY'];
            if ($balance != 0) {
                if ($cwh == 'AFSMT' || $cwh == 'AFWH3' || $cwh == 'PSIEQUIP') {
                } else {
                    $res = $this->RPSAL_INVENTORY_mod->check_Primary($WHERE);
                    $myar[] = [
                        'cd' => "0", 'msg' => 'there is a discrepancy, please fix it first', 'res' => $res, 'where' => $WHERE, 'rssaved' => $rssaved, 'diff1row' => $r,
                    ];
                    die(json_encode(['status' => $myar]));
                    break;
                }
            }
        }
        $ttlupdated = 0;
        $ttlsaved = 0;
        $saverows = [];
        foreach ($rs as $r) {
            $isfound = false;
            foreach ($rssaved as $s) {
                if (strtoupper($r['ITH_ITMCD']) == strtoupper($s['INV_ITMNUM'])) {
                    if ($r['STOCKQTY'] * 1 != $s['INV_QTY'] * 1) {
                        $WHERE['INV_ITMNUM'] = $s['INV_ITMNUM'];
                        $ttlupdated += $this->RPSAL_INVENTORY_mod->updatebyVAR(['INV_QTY' => $r['STOCKQTY'] * 1, 'INV_DATE' => $cdate], $WHERE);
                    }
                    $isfound = true;
                    break;
                }
            }
            if (!$isfound) {
                $saverows[] = [
                    'INV_ITMNUM' => $r['ITH_ITMCD'], 'INV_MONTH' => $WHERE['INV_MONTH'], 'INV_YEAR' => $WHERE['INV_YEAR'], 'INV_WH' => $cwh == 'AFSMT' ? 'AFWH3' : $cwh, 'INV_QTY' => $r['STOCKQTY'] * 1, 'INV_DATE' => $cdate, 'created_at' => $dateTimeTosave,
                ];
            }
        }
        if (count($saverows)) {
            $ttlsaved = $this->RPSAL_INVENTORY_mod->insertb($saverows);
        }
        if ($ttlsaved > 0 || $ttlupdated > 0) {
            $myar[] = [
                'cd' => "1", 'msg' => 'done, Total saved : ' . $ttlsaved . ' , Total updated :' . $ttlupdated, 'rs' => $rs,
            ];
        } else {
            $myar[] = [
                'cd' => "1", 'msg' => 'done, nothing changes',
            ];
        }
        die('{"status":' . json_encode($myar) . ',"data":' . json_encode($rsadj) . '}');
    }

    public function inventory_date()
    {
        header('Content-Type: application/json');
        $invYear = $this->input->get('year');
        $invMonth = $this->input->get('month');
        $rs = $this->XICYC_mod->select_date(['YEAR(ICYC_STKDT)' => $invYear, 'MONTH(ICYC_STKDT)' => $invMonth]);
        die(json_encode(['data' => $rs]));
    }

    public function MEGAToInventory()
    {
        header('Content-Type: application/json');
        date_default_timezone_set('Asia/Jakarta');
        $current_datetime = date('Y-m-d H:i:s');
        $cwh = $this->input->post('inwh');
        $cdate = $this->input->post('indate');
        $dateObj = new DateTime($cdate);
        $_MONTH = $dateObj->format('m');
        $_YEAR = $dateObj->format('Y');
        $cwh_inv = $cwh;
        $WHERE = ['INV_MONTH' => $_MONTH, 'INV_YEAR' => $_YEAR, 'INV_WH' => $cwh_inv];
        $rssaved = $this->RPSAL_INVENTORY_mod->select_compare_where($WHERE);
        $rs = $this->XICYC_mod->select_for_it_inventory(['ICYC_STKDT' => $cdate, 'ICYC_WHSCD' => $cwh]);
        $rsadj = [];
        $ttlupdated = 0;
        $ttlsaved = 0;
        $saverows = [];
        foreach ($rs as $r) {
            $isfound = false;
            foreach ($rssaved as $s) {
                if (strtoupper($r['ITH_ITMCD']) == strtoupper($s['INV_ITMNUM'])) {
                    if ($r['STOCKQTY'] * 1 != $s['INV_QTY'] * 1) {
                        $WHERE['INV_ITMNUM'] = $s['INV_ITMNUM'];
                        $ttlupdated += $this->RPSAL_INVENTORY_mod->updatebyVAR(['INV_QTY' => $r['STOCKQTY'] * 1, 'INV_DATE' => $cdate], $WHERE);
                    }
                    $isfound = true;
                    break;
                }
            }
            if (!$isfound) {
                $saverows[] = [
                    'INV_ITMNUM' => $r['ITH_ITMCD'], 'INV_MONTH' => $WHERE['INV_MONTH'], 'INV_YEAR' => $WHERE['INV_YEAR'], 'INV_WH' => $cwh == 'AFSMT' ? 'AFWH3' : $cwh, 'INV_QTY' => $r['STOCKQTY'] * 1, 'INV_DATE' => $cdate, 'created_at' => $current_datetime,
                ];
            }
        }
        if (count($saverows)) {
            $ttlsaved = $this->RPSAL_INVENTORY_mod->insertb($saverows);
        }
        if ($ttlsaved > 0 || $ttlupdated > 0) {
            $myar[] = [
                'cd' => "1", 'msg' => 'done, Total saved : ' . $ttlsaved . ' , Total updated :' . $ttlupdated, 'rs' => $rs,
            ];
        } else {
            $myar[] = [
                'cd' => "1", 'msg' => 'done, nothing changes', 'data' => $saverows,
            ];
        }
        die('{"status":' . json_encode($myar) . ',"data":' . json_encode($rsadj) . '}');
    }

    public function MEGAAllLocationToInventory()
    {
        header('Content-Type: application/json');
        date_default_timezone_set('Asia/Jakarta');
        ini_set('max_execution_time', '-1');
        $current_datetime = date('Y-m-d H:i:s');
        $cdate = $this->input->post('indate');
        $dateObj = new DateTime($cdate);
        $_MONTH = $dateObj->format('m');
        $_YEAR = $dateObj->format('Y');
        $WHERE = ['INV_MONTH' => $_MONTH, 'INV_YEAR' => $_YEAR];
        $rssaved = $this->RPSAL_INVENTORY_mod->select_for_compare_mega($WHERE);
        $rs = $this->XICYC_mod->select_for_it_inventory(['ICYC_STKDT' => $cdate]);
        $rsadj = [];
        $ttlupdated = 0;
        $ttlsaved = 0;
        $saverows = [];
        foreach ($rs as $r) {
            $isfound = false;
            foreach ($rssaved as $s) {
                if ($r['ITH_ITMCD'] === $s['INV_ITMNUM'] && $r['ICYC_WHSCD'] === $s['INV_WH']) {
                    if ($r['STOCKQTY'] * 1 != $s['INV_QTY'] * 1) {
                        $WHERE['INV_ITMNUM'] = $s['INV_ITMNUM'];
                        $WHERE['INV_WH'] = $s['INV_WH'];
                        $ttlupdated += $this->RPSAL_INVENTORY_mod->updatebyVAR(['INV_QTY' => $r['STOCKQTY'] * 1, 'INV_DATE' => $cdate], $WHERE);
                    }
                    $isfound = true;
                    break;
                }
            }
            if (!$isfound) {
                $saverows[] = [
                    'INV_ITMNUM' => $r['ITH_ITMCD'], 'INV_MONTH' => $WHERE['INV_MONTH'], 'INV_YEAR' => $WHERE['INV_YEAR'], 'INV_WH' => $r['ICYC_WHSCD'] == 'AFSMT' ? 'AFWH3' : $r['ICYC_WHSCD'], 'INV_QTY' => $r['STOCKQTY'] * 1, 'INV_DATE' => $cdate, 'created_at' => $current_datetime,
                ];
            }
        }
        if (count($saverows)) {
            $ttlsaved = $this->RPSAL_INVENTORY_mod->insertb($saverows);
        }
        if ($ttlsaved > 0 || $ttlupdated > 0) {
            $myar[] = [
                'cd' => "1", 'msg' => 'Uploaded successfully', 'rs' => $rs,
            ];
        } else {
            if (count($rs)) {
                $myar[] = [
                    'cd' => "1", 'msg' => 'nothing changes', 'data' => $saverows,
                ];
            } else {
                $myar[] = [
                    'cd' => "0", 'msg' => 'Stock taking on ' . $cdate . ' is empty', 'data' => $saverows,
                ];
            }
        }
        die('{"status":' . json_encode($myar) . ',"data":' . json_encode($rsadj) . '}');
    }

    public function adjustment_ParentBased()
    {
        ini_set('max_execution_time', '-1');
        header('Content-Type: application/json');
        date_default_timezone_set('Asia/Jakarta');
        if ($this->session->userdata('status') != "login") {
            $myar = ["cd" => "0", "msg" => "Session is expired please reload page"];
            die(json_encode(['status' => $myar]));
        }
        $whException = ['AFWH3', 'AWIP1', 'PSIEQUIP',
            'ENGEQUIP',
            'ENGLINEEQUIP',
            'FCTEQUIP',
            'ICTEQUIP',
            'MFG1EQUIP',
            'MFG2EQUIP',
            'PPICEQUIP',
            'PRCSCREQUIP',
            'PSIEQUIP',
            'QAEQUIP',
            'QASCREQUIP',
            'SCREQUIP',
        ];
        $date = $this->input->post('date');
        $location = $this->input->post('location');
        $adjtype = $this->input->post('adjtype');
        $form_prefix = $adjtype === 'P' ? 'ADJ-I-' : 'ADJ-O-';
        $usrid = $this->session->userdata('nama');
        $current_datetime = date('Y-m-d H:i:s');
        if ($location === 'NFWH4RT' && substr($date, 0, 4) !== '2021') {
            $whException[] = 'NFWH4RT';
        }
        if (!in_array($location, $whException)) {
            $dateObj = new DateTime($date);
            $dateObj->modify('+1 day');
            $dateTosave = $dateObj->format('Y-m-d');
            $dateTimeTosave = $dateObj->format('Y-m-d 06:59:59');
            $dateformat = $dateObj->format('Ym');
            switch ($location) {
                case 'NFWH4RT':
                    $rs = $this->ITH_mod->select_compare_inventory_fg_rtn($location, $date);
                    break;
                case 'QAFG':
                    $rs = $this->ITH_mod->select_compare_inventory_fg_qa($location, $date);
                    break;
                case 'AFWH9SC':
                    $rs = $this->ITH_mod->select_compare_inventory_fg_qa($location, $date);
                    break;
                case 'AFWH3':
                    $rs = $this->ITH_mod->select_compare_inventory_fg_fresh($location, $date);
                    break;
                case 'AFWH3RT':
                    $rs = $this->ITH_mod->select_compare_inventory_fg_rtn_asset($location, $date);
                    break;
                default:
                    $rs = $this->ITH_mod->select_compare_inventory($location, $date);
            }
            $rsadj = [];
            foreach ($rs as $r) {
                $balance = $r['STOCKQTY'] - $r['MGAQTY'];
                if ($balance != 0) {
                    if ($balance > 0) {
                        $ith_form = $form_prefix . 'OUT';
                        $balance = -$balance;
                    } else {
                        $ith_form = $form_prefix . 'INC';
                        $balance = abs($balance);
                    }
                    $rsadj[] = [
                        'ITH_ITMCD' => $r['ITH_ITMCD'],
                        'ITH_QTY' => $balance,
                        'ITH_DATE' => $dateTosave,
                        'ITH_LUPDT' => $dateTimeTosave,
                        'ITH_WH' => $location,
                        'ITH_DOC' => 'DOCINV' . $dateformat,
                        'ITH_FORM' => $ith_form,
                        'ITH_USRID' => $usrid,
                        'ITH_REMARK' => 'do at ' . $current_datetime,
                    ];
                }
            }
            if (!empty($rsadj)) {
                $this->ITH_mod->insertb($rsadj);
                $myar = ['cd' => "1", 'msg' => 'done', 'reff' => $location, 'rsadj' => $rsadj];
            } else {
                $myar = ['cd' => "0", 'msg' => 'done nothing adjusted', 'reff' => $location, 'rsadj' => $rsadj];
            }
        } else {
            $myar = ['cd' => "0", 'msg' => 'done nothing adjusted...', 'reff' => $location];
        }
        die(json_encode(['status' => $myar]));
    }

    public function upload_to_itinventory()
    {
        ini_set('max_execution_time', '-1');
        header('Content-Type: application/json');
        $date = $this->input->post('date');
        $location = $this->input->post('location');
        $upltype = $this->input->post('upltype');
        $cwh_inv = $location == 'AFSMT' ? 'AFWH3' : $location;
        $cwh_wms = $location == 'AFWH3' ? 'AFSMT' : $location;
        $dateObj = new DateTime($date);
        $_MONTH = $dateObj->format('m');
        $_YEAR = $dateObj->format('Y');
        $WHERE = ['INV_MONTH' => $_MONTH, 'INV_YEAR' => $_YEAR, 'INV_WH' => $cwh_inv];
        switch ($cwh_wms) {
            case 'NFWH4RT':
                $rs = $this->ITH_mod->select_compare_inventory_fg_rtn($cwh_wms, $date);
                break;
            case 'QAFG':
                $rs = $this->ITH_mod->select_compare_inventory_fg_qa($cwh_wms, $date);
                break;
            case 'AFWH3':
                $rs = $this->ITH_mod->select_compare_inventory_fg_fresh($cwh_wms, $date);
                break;
            case 'AFWH3RT':
                $rs = $this->ITH_mod->select_compare_inventory_fg_rtn_asset($cwh_wms, $date);
                break;
            case 'PSIEQUIP':
                $rs = $this->ITH_mod->select_compare_inventory_machine($cwh_wms, $date);
                break;
            case 'ENGEQUIP':
                $rs = $this->ITH_mod->select_compare_inventory_machine($cwh_wms, $date);
                break;
            case 'ENGLINEEQUIP':
                $rs = $this->ITH_mod->select_compare_inventory_machine($cwh_wms, $date);
                break;
            case 'FCTEQUIP':
                $rs = $this->ITH_mod->select_compare_inventory_machine($cwh_wms, $date);
                break;
            case 'ICTEQUIP':
                $rs = $this->ITH_mod->select_compare_inventory_machine($cwh_wms, $date);
                break;
            case 'MFG1EQUIP':
                $rs = $this->ITH_mod->select_compare_inventory_machine($cwh_wms, $date);
                break;
            case 'MFG2EQUIP':
                $rs = $this->ITH_mod->select_compare_inventory_machine($cwh_wms, $date);
                break;
            case 'PPICEQUIP':
                $rs = $this->ITH_mod->select_compare_inventory_machine($cwh_wms, $date);
                break;
            case 'PRCSCREQUIP':
                $rs = $this->ITH_mod->select_compare_inventory_machine($cwh_wms, $date);
                break;
            case 'QAEQUIP':
                $rs = $this->ITH_mod->select_compare_inventory_machine($cwh_wms, $date);
                break;
            case 'QASCREQUIP':
                $rs = $this->ITH_mod->select_compare_inventory_machine($cwh_wms, $date);
                break;
            case 'SCREQUIP':
                $rs = $this->ITH_mod->select_compare_inventory_machine($cwh_wms, $date);
                break;
            default:
                $rs = $this->ITH_mod->select_compare_inventory($cwh_wms, $date);
        }
        $rssaved = $this->RPSAL_INVENTORY_mod->select_compare_where($WHERE);
        $dateTimeTosave = date('Y-m-d H:i:s');
        $ttlupdated = 0;
        $ttlsaved = 0;
        $saverows = [];
        foreach ($rs as $r) {
            $isfound = false;
            foreach ($rssaved as $s) {
                if (strtoupper($r['ITH_ITMCD']) === strtoupper($s['INV_ITMNUM'])) {
                    if ($upltype === 'P') {
                        if ($r['STOCKQTY'] * 1 != $s['INV_PHY_QTY'] * 1) {
                            $WHERE['INV_ITMNUM'] = $s['INV_ITMNUM'];
                            $ttlupdated += $this->RPSAL_INVENTORY_mod->updatebyVAR(['INV_PHY_QTY' => $r['STOCKQTY'] * 1, 'INV_PHY_DATE' => $date], $WHERE);
                        }
                    } else {
                        if ($r['STOCKQTY'] * 1 != $s['INV_QTY'] * 1) {
                            $WHERE['INV_ITMNUM'] = $s['INV_ITMNUM'];
                            $ttlupdated += $this->RPSAL_INVENTORY_mod->updatebyVAR(['INV_QTY' => $r['STOCKQTY'] * 1, 'INV_DATE' => $date], $WHERE);
                        }
                    }
                    $isfound = true;
                    break;
                }
            }
            if (!$isfound) {
                if ($upltype === 'P') {
                    $saverows[] = [
                        'INV_ITMNUM' => $r['ITH_ITMCD'], 'INV_MONTH' => $WHERE['INV_MONTH'], 'INV_YEAR' => $WHERE['INV_YEAR'], 'INV_WH' => $cwh_inv, 'INV_PHY_QTY' => $r['STOCKQTY'] * 1, 'INV_PHY_DATE' => $date, 'created_at' => $dateTimeTosave,
                    ];
                } else {
                    $saverows[] = [
                        'INV_ITMNUM' => $r['ITH_ITMCD'], 'INV_MONTH' => $WHERE['INV_MONTH'], 'INV_YEAR' => $WHERE['INV_YEAR'], 'INV_WH' => $cwh_inv, 'INV_QTY' => $r['STOCKQTY'] * 1, 'INV_DATE' => $date, 'created_at' => $dateTimeTosave,
                    ];
                }
            }
        }
        if (count($saverows)) {
            $ttlsaved = $this->RPSAL_INVENTORY_mod->insertb($saverows);
        }
        if ($ttlsaved > 0 || $ttlupdated > 0) {
            $myar = [
                'cd' => "1", 'msg' => 'done,', 'reff' => $location,
            ];
        } else {
            $myar = [
                'cd' => "1", 'msg' => 'done, nothing changes', 'reff' => $location, 'data' => $saverows,
            ];
        }
        die(json_encode(['status' => $myar, 'data' => $saverows]));
    }

    public function scrap_balance()
    {
        header('Content-Type: application/json');
        $search = $this->input->get('search');
        $date0 = $this->input->get('date0');
        $rs = $this->ITH_mod->select_fordispose(['MITM_ITMD1' => $search], $date0);
        die(json_encode(['data' => $rs]));
    }

    public function balanceRA()
    {
        header('Content-Type: application/json');
        $rs = $this->ITH_mod->select_balanceRA();
        die(json_encode(['data' => $rs]));
    }

    public function form_unscan_prd_qc()
    {
        $this->load->view('wms_report/vrpt_unscan_prd_qc');
    }
    public function form_report_kka_mega()
    {
        $this->load->view('wms_report/vkka_mega');
    }

    public function sync_abnormal_transaction($pdate)
    {
        $dateTimesBin = date('Y-m-d H:i:s');
        $date = $pdate;
        $rs = $this->ITH_mod->select_abnormal_kitting_tx($date);
        $adocs = [];
        $aitems = [];
        $isExist = false;
        $myar = [];
        $rsPatch = [];
        $rsPatchBin = [];
        foreach ($rs as $r) {
            if (!in_array($r['ITH_DOC'], $adocs)) {
                $adocs[] = $r['ITH_DOC'];
                $isExist = true;
            }
            if (!in_array($r['ITH_ITMCD'], $aitems)) {
                $aitems[] = $r['ITH_ITMCD'];
                $isExist = true;
            }
        }
        if ($isExist) {
            $qtylist = [];
            $rsdetail = $this->ITH_mod->select_abnormal_kitting_tx_detail($adocs, $aitems, $date);
            foreach ($rs as $r) {
                if ($r['TTLROWS'] === 1) {
                    foreach ($rsdetail as $d) {
                        if ($r['ITH_ITMCD'] === trim($d['ITH_ITMCD']) && $r['ITH_DOC'] === trim($d['ITH_DOC'])) {
                            $wh = '_';
                            switch (trim($d['ITH_WH'])) {
                                case 'PLANT1':
                                    $wh = 'ARWH1';
                                    break;
                                case 'ARWH1':
                                    $wh = 'PLANT1';
                                    break;
                                case 'PLANT2':
                                    $wh = 'ARWH2';
                                    break;
                                case 'ARWH2':
                                    $wh = 'PLANT2';
                                    break;
                            }
                            $rsPatch[] = [
                                'ITH_ITMCD' => $r['ITH_ITMCD'],
                                'ITH_DATE' => $d['ITH_DATE'],
                                'ITH_FORM' => $d['ITH_FORM'] === 'INC-PRD-RM' ? 'OUT-WH-RM' : 'INC-PRD-RM',
                                'ITH_DOC' => $r['ITH_DOC'],
                                'ITH_QTY' => $d['ITH_QTY'] * -1,
                                'ITH_WH' => $wh,
                                'ITH_LUPDT' => $d['ITH_LUPDT'],
                                'ITH_USRID' => $d['ITH_USRID'],
                            ];
                            $rsPatchBin[] = [
                                'ITH_ITMCD' => $r['ITH_ITMCD'],
                                'ITH_DATE' => $d['ITH_DATE'],
                                'ITH_FORM' => $d['ITH_FORM'] === 'INC-PRD-RM' ? 'OUT-WH-RM' : 'INC-PRD-RM',
                                'ITH_DOC' => $r['ITH_DOC'],
                                'ITH_QTY' => $d['ITH_QTY'] * -1,
                                'ITH_WH' => $wh,
                                'ITH_LUPDT' => $d['ITH_LUPDT'],
                                'ITH_USRID' => $d['ITH_USRID'],
                                'ITH_LUPDTBIN' => $dateTimesBin,
                                'ITH_REASON' => 'PATCH',
                            ];
                            break;
                        }
                    }
                } else {
                    foreach ($rsdetail as $d) {
                        if ($r['ITH_ITMCD'] === trim($d['ITH_ITMCD']) && $r['ITH_DOC'] === trim($d['ITH_DOC'])) {
                            $isfound = false;
                            foreach ($qtylist as &$n) {
                                if (
                                    $r['ITH_DOC'] === $n['ITH_DOC']
                                    && $r['ITH_ITMCD'] === $n['ITH_ITMCD']
                                    && abs($d['ITH_QTY']) === abs($n['ITH_QTY'])
                                ) {
                                    $n['COUNTQT']++;
                                    $isfound = true;
                                    break;
                                }
                            }
                            unset($n);
                            if (!$isfound) {
                                $qtylist[] = [
                                    'ITH_ITMCD' => $r['ITH_ITMCD'],
                                    'ITH_DOC' => $r['ITH_DOC'],
                                    'ITH_QTY' => abs($d['ITH_QTY']),
                                    'COUNTQT' => 1,
                                ];
                            }
                        }
                    }
                }
            }

            # patch for TTLROWS !=1
            $rs2 = array_filter($qtylist, function ($value) {
                return ($value['COUNTQT'] == 1);
            });

            # apply patch
            foreach ($rs2 as $r) {
                foreach ($rsdetail as $d) {
                    if (
                        $r['ITH_ITMCD'] === trim($d['ITH_ITMCD']) && $r['ITH_DOC'] === trim($d['ITH_DOC'])
                        && abs($r['ITH_QTY']) === abs($d['ITH_QTY'])
                    ) {
                        $wh = '_';
                        switch (trim($d['ITH_WH'])) {
                            case 'PLANT1':
                                $wh = 'ARWH1';
                                break;
                            case 'ARWH1':
                                $wh = 'PLANT1';
                                break;
                            case 'PLANT2':
                                $wh = 'ARWH2';
                                break;
                            case 'ARWH2':
                                $wh = 'PLANT2';
                                break;
                        }
                        $rsPatch[] = [
                            'ITH_ITMCD' => $r['ITH_ITMCD'],
                            'ITH_DATE' => $d['ITH_DATE'],
                            'ITH_FORM' => $d['ITH_FORM'] === 'INC-PRD-RM' ? 'OUT-WH-RM' : 'INC-PRD-RM',
                            'ITH_DOC' => $r['ITH_DOC'],
                            'ITH_QTY' => $d['ITH_QTY'] * -1,
                            'ITH_WH' => $wh,
                            'ITH_LUPDT' => $d['ITH_LUPDT'],
                            'ITH_USRID' => $d['ITH_USRID'],
                        ];
                        $rsPatchBin[] = [
                            'ITH_ITMCD' => $r['ITH_ITMCD'],
                            'ITH_DATE' => $d['ITH_DATE'],
                            'ITH_FORM' => $d['ITH_FORM'] === 'INC-PRD-RM' ? 'OUT-WH-RM' : 'INC-PRD-RM',
                            'ITH_DOC' => $r['ITH_DOC'],
                            'ITH_QTY' => $d['ITH_QTY'] * -1,
                            'ITH_WH' => $wh,
                            'ITH_LUPDT' => $d['ITH_LUPDT'],
                            'ITH_USRID' => $d['ITH_USRID'],
                            'ITH_LUPDTBIN' => $dateTimesBin,
                            'ITH_REASON' => 'PATCH',
                        ];
                        break;
                    }
                }
            }

            if (!empty($rsPatchBin)) {
                $this->ITH_mod->insertb_bin($rsPatchBin);
                $this->ITH_mod->insertb($rsPatch);
            }

            $myar[] = [
                'cd' => '1', 'mst' => 'ready to be inserted', 'data' => $rsPatch, '$rs' => $rs, '$rsdetail' => $rsdetail, 'rs2' => $rs2, 'qtylist' => $qtylist,
            ];
        } else {
            $myar[] = ['cd' => '1', 'msg' => 'ok'];
        }
        return ['status' => $myar];
    }

    public function sync_today_abnormal_transaction()
    {
        header('Content-Type: application/json');
        date_default_timezone_set('Asia/Jakarta');
        $date = date('Y-m-d');
        log_message('error', $_SERVER['REMOTE_ADDR'] . ', step1#, start, sync abnomral tx (today)');
        $result = $this->sync_abnormal_transaction($date);
        log_message('error', $_SERVER['REMOTE_ADDR'] . ', step1#, finish');
        die(json_encode($result));
    }

    public function sync_yesterday_abnormal_transaction()
    {
        header('Content-Type: application/json');
        date_default_timezone_set('Asia/Jakarta');
        $date = date('Y-m-d', strtotime("-1 days"));
        log_message('error', $_SERVER['REMOTE_ADDR'] . ', step1#, start, sync abnomral tx (yesterday)');
        $result = $this->sync_abnormal_transaction($date);
        log_message('error', $_SERVER['REMOTE_ADDR'] . ', step1#, finish');
        die(json_encode($result));
    }

    public function validateDate($date, $format = 'Y-m-d')
    {
        $d = DateTime::createFromFormat($format, $date);
        // The Y ( 4 digits year ) returns TRUE for any integer with any number of digits so changing the comparison from == to === fixes the issue.
        return $d && $d->format($format) === $date;
    }

    public function sync_atdate_abnormal_transaction()
    {
        header('Content-Type: application/json');
        date_default_timezone_set('Asia/Jakarta');
        $date = $this->input->get('date');
        if (!$this->validateDate($date)) {
            die('valid date is required');
        }
        log_message('error', $_SERVER['REMOTE_ADDR'] . ', step1#, start, sync abnomral tx (' . $date . ')');
        $result = $this->sync_abnormal_transaction($date);
        log_message('error', $_SERVER['REMOTE_ADDR'] . ', step1#, finish');
        die(json_encode($result));
    }

    public function report_kka_mega()
    {
        if (!isset($_COOKIE["CKPSI_DDATE"]) || !isset($_COOKIE["CKPSI_DREPORT"]) && !isset($_COOKIE["CKPSI_DDATE2"])) {
            exit('no data to be found');
        }
        $date1 = $_COOKIE["CKPSI_DDATE"];
        $date2 = $_COOKIE["CKPSI_DDATE2"];
        $reportType = $_COOKIE["CKPSI_DREPORT"];
        $rs = [];
        $title = '';
        $spreadsheet = new Spreadsheet();
        $sheet = $spreadsheet->getActiveSheet();
        $sheet->setTitle('RESUME');
        $sheet->setCellValueByColumnAndRow(1, 2, 'Item Code');
        $sheet->setCellValueByColumnAndRow(2, 2, 'Item Description');
        $sheet->setCellValueByColumnAndRow(3, 2, 'Saldo Awal');
        $sheet->setCellValueByColumnAndRow(4, 2, 'Pemasukan');
        switch ($reportType) {
            case 'FG':
                $rs = $this->ITH_mod->select_KKA_MEGA_FG($date1, $date2);
                $title = 'Finished Goods';
                $sheet->setCellValueByColumnAndRow(5, 2, 'Penyesuaian Pemasukan');
                $sheet->setCellValueByColumnAndRow(6, 2, 'Pengeluaran');
                $sheet->setCellValueByColumnAndRow(7, 2, 'Penyesuaian Pengeluaran');
                $sheet->setCellValueByColumnAndRow(8, 2, 'Saldo Akhir');
                $i = 3;
                foreach ($rs as $r) {
                    $sheet->setCellValueByColumnAndRow(1, $i, $r['ITRN_ITMCD']);
                    $sheet->setCellValueByColumnAndRow(2, $i, $r['MGMITM_ITMD1']);
                    $sheet->setCellValueByColumnAndRow(3, $i, $r['B4QTY']); #C
                    $sheet->setCellValueByColumnAndRow(4, $i, $r['INCQTY']); #D
                    $sheet->setCellValueByColumnAndRow(5, $i, $r['ADJINCQTY']); #E
                    $sheet->setCellValueByColumnAndRow(6, $i, $r['DLVQTY']); #F
                    $sheet->setCellValueByColumnAndRow(7, $i, $r['ADJOUTQTY']); #G
                    $sheet->setCellValueByColumnAndRow(8, $i, "=C$i+D$i+E$i-F$i-G$i");
                    $i++;
                }
                foreach (range('A', 'O') as $r) {
                    $sheet->getColumnDimension($r)->setAutoSize(true);
                }
                $sheet->getStyle('A:A')->getAlignment()->setHorizontal(\PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_LEFT);
                $sheet->getStyle('A2:G' . ($i - 1))->getBorders()->getAllBorders()->setBorderStyle(\PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN);
                $sheet->setAutoFilter('A2:H2');
                $sheet->freezePane('C3');
                break;
            case 'FG-RTN':
                $rs = $this->ITH_mod->select_KKA_MEGA_FG_RTN($date1, $date2);
                $title = 'Return Finished Goods';
                $sheet->setCellValueByColumnAndRow(5, 2, 'Penyesuaian Pemasukan');
                $sheet->setCellValueByColumnAndRow(6, 2, 'Pengeluaran');
                $sheet->setCellValueByColumnAndRow(7, 2, 'Penyesuaian Pengeluaran');
                $sheet->setCellValueByColumnAndRow(8, 2, 'Saldo Akhir');
                $i = 3;
                foreach ($rs as $r) {
                    $sheet->setCellValueByColumnAndRow(1, $i, $r['ITRN_ITMCD']);
                    $sheet->setCellValueByColumnAndRow(2, $i, $r['MGMITM_ITMD1']);
                    $sheet->setCellValueByColumnAndRow(3, $i, $r['B4QTY']);
                    $sheet->setCellValueByColumnAndRow(4, $i, $r['INCQTY']);
                    $sheet->setCellValueByColumnAndRow(5, $i, $r['ADJINCQTY']);
                    $sheet->setCellValueByColumnAndRow(6, $i, $r['DLVQTY']);
                    $sheet->setCellValueByColumnAndRow(7, $i, $r['ADJOUTQTY']);
                    $sheet->setCellValueByColumnAndRow(8, $i, "=C$i+D$i+E$i-F$i-G$i");
                    $i++;
                }
                foreach (range('A', 'O') as $r) {
                    $sheet->getColumnDimension($r)->setAutoSize(true);
                }
                $sheet->getStyle('A:A')->getAlignment()->setHorizontal(\PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_LEFT);
                $sheet->getStyle('A2:G' . ($i - 1))->getBorders()->getAllBorders()->setBorderStyle(\PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN);
                $sheet->setAutoFilter('A2:H2');
                $sheet->freezePane('C3');
                break;
            case 'RM':
                $rs = $this->ITH_mod->select_KKA_MEGA_RM($date1, $date2);
                $title = 'Raw Material';
                $sheet->setCellValueByColumnAndRow(5, 2, 'Pemasukan dari Produksi');
                $sheet->setCellValueByColumnAndRow(6, 2, 'Penyesuaian Pemasukan');
                $sheet->setCellValueByColumnAndRow(7, 2, 'Pengeluaran');
                $sheet->setCellValueByColumnAndRow(8, 2, 'Penyesuaian Pengeluaran');
                $sheet->setCellValueByColumnAndRow(9, 2, 'Saldo Akhir');
                $i = 3;
                foreach ($rs as $r) {
                    $sheet->setCellValueByColumnAndRow(1, $i, $r['ITRN_ITMCD']);
                    $sheet->setCellValueByColumnAndRow(2, $i, $r['MGMITM_ITMD1']);
                    $sheet->setCellValueByColumnAndRow(3, $i, $r['B4QTY']);
                    $sheet->setCellValueByColumnAndRow(4, $i, $r['INCQTY']);
                    $sheet->setCellValueByColumnAndRow(5, $i, $r['PRDINCQTY']);
                    $sheet->setCellValueByColumnAndRow(6, $i, $r['ADJINCQTY']);
                    $sheet->setCellValueByColumnAndRow(7, $i, $r['DLVQTY']);
                    $sheet->setCellValueByColumnAndRow(8, $i, $r['ADJOUTQTY']);
                    $sheet->setCellValueByColumnAndRow(9, $i, "=C$i+D$i+E$i+F$i-G$i-H$i");
                    $i++;
                }
                foreach (range('A', 'O') as $r) {
                    $sheet->getColumnDimension($r)->setAutoSize(true);
                }
                $sheet->getStyle('A:A')->getAlignment()->setHorizontal(\PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_LEFT);
                $sheet->getStyle('A2:I' . ($i - 1))->getBorders()->getAllBorders()->setBorderStyle(\PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN);
                $sheet->setAutoFilter('A2:I2');
                $sheet->freezePane('C3');
                break;
        }
        $stringjudul = "KKA " . $title . " $date1 to $date2";
        $writer = new Xlsx($spreadsheet);
        $filename = $stringjudul; //save our workbook as this file name

        header('Content-Type: application/vnd.ms-excel');
        header('Content-Disposition: attachment;filename="' . $filename . '.xlsx"');
        header('Cache-Control: max-age=0');
        $writer->save('php://output');
    }

    public function get_prdqc_unscan()
    {
        header('Content-Type: application/json');
        $rs = $this->ITH_mod->select_discrepancy_prd_qc();
        die(json_encode(['data' => $rs]));
    }

    public function calculate_WO_today()
    {
        date_default_timezone_set('Asia/Jakarta');
        header('Content-Type: application/json');
        $rsWO = $this->ITH_mod->select_WO_PRD_uncalculated();
        $Calc_lib = new RMCalculator();
        $myar = [];
        $libresponses = [];
        foreach ($rsWO as $r) {
            $libresponses[] = $Calc_lib->get_usage_rm_perjob($r->ITH_DOC);
        }
        $myar[] = ['cd' => 1, 'msg' => 'done', 'reff' => $libresponses];
        log_message('error', "TODAY WO-PRD Calculation occur [" . count($libresponses) . "]");
        die(json_encode(['data' => $myar]));
    }

    public function breakdown_estimation()
    {
        ini_set('max_execution_time', '-1');
        date_default_timezone_set('Asia/Jakarta');
        $date = $this->input->post('date');
        $fglist = $this->input->post('fg');
        $rmlist = $this->input->post('rm');
        $bg = $this->input->post('bg');
        $saveOutput = $this->input->post('save');
        if ($date == '') {
            die('could not continue');
        }

        $fgstring = is_array($fglist) ? "'" . implode("','", $fglist) . "'" : "''";
        $rmstring = is_array($rmlist) ? "'" . implode("','", $rmlist) . "'" : "''";
        $startDate = date('Y-m-d', strtotime($date . " - 60 days"));
        $spreadsheet = new Spreadsheet();
        $sheet = $spreadsheet->getActiveSheet();
        $sheet->setTitle('FG_RESUME');
        $sheet->setCellValueByColumnAndRow(1, 2, 'Assy Code');
        $sheet->setCellValueByColumnAndRow(2, 2, 'Description');
        $sheet->setCellValueByColumnAndRow(3, 2, 'Location');
        $sheet->setCellValueByColumnAndRow(3, 3, 'SCAN PRODUCTION');
        $sheet->setCellValueByColumnAndRow(4, 3, 'SCAN QC');
        $sheet->setCellValueByColumnAndRow(5, 3, 'FRESH WAREHOUSE');
        $sheet->setCellValueByColumnAndRow(6, 3, 'PENDING');
        $sheet->setCellValueByColumnAndRow(7, 3, 'QCRTN');
        $sheet->setCellValueByColumnAndRow(8, 3, 'NFWH4RT');
        $sheet->setCellValueByColumnAndRow(9, 3, 'AFWH3RT');
        $sheet->mergeCells('A2:A3');
        $sheet->getStyle('A2')->getAlignment()->setVertical(\PhpOffice\PhpSpreadsheet\Style\Alignment::VERTICAL_CENTER);
        $sheet->mergeCells('B2:B3');
        $sheet->getStyle('B2')->getAlignment()->setVertical(\PhpOffice\PhpSpreadsheet\Style\Alignment::VERTICAL_CENTER);
        $sheet->mergeCells('C2:I2');
        $sheet->getStyle('A2:I3')->getAlignment()->setHorizontal('center');
        $sheet->freezePane('C4');
        $y = 4;
        if (strlen($fgstring) > 5) {
            log_message('error', $_SERVER['REMOTE_ADDR'] . ', step0#, BG:OTHER, init FG with item code');
            $rsFG = $this->ITH_mod->select_fg_byItemCodeArray($date, $bg, $fgstring);
        } else {
            log_message('error', $_SERVER['REMOTE_ADDR'] . ', step0#, BG:OTHER, init FG');
            $rsFG = $this->ITH_mod->select_fg($date, $bg);
        }
        foreach ($rsFG as $r) {
            $sheet->setCellValueByColumnAndRow(1, $y, $r['ITH_ITMCD']);
            $sheet->setCellValueByColumnAndRow(2, $y, $r['ITMD1']);
            $sheet->setCellValueByColumnAndRow(3, $y, 0);
            $sheet->setCellValueByColumnAndRow(4, $y, $r['LOC_QC']);
            $sheet->setCellValueByColumnAndRow(5, $y, "=" . $r['LOC_AFWH3'] . "+" . $r['LOC_ARSHP']);
            $sheet->setCellValueByColumnAndRow(6, $y, $r['LOC_QAFG']);
            $sheet->setCellValueByColumnAndRow(7, $y, $r['LOC_AFQART']);
            $sheet->setCellValueByColumnAndRow(8, $y, "=" . $r['LOC_AFQART'] . "+" . $r['LOC_NFWH4RT'] . "+" . $r['LOC_ARSHPRTN']);
            $sheet->setCellValueByColumnAndRow(9, $y, "=" . $r['LOC_AFQART2'] . "+" . $r['LOC_AFWH3RT'] . "+" . $r['LOC_ARSHPRTN2']);
            $y++;
        }
        foreach (range('A', 'I') as $v) {
            $sheet->getColumnDimension($v)->setAutoSize(true);
        }
        #FORMAT NUMBER
        $rang = "C4:I" . $sheet->getHighestDataRow();
        $sheet->getStyle($rang)->getNumberFormat()->setFormatCode('#,##0');

        if (strlen($rmstring) > 5) {
            log_message('error', $_SERVER['REMOTE_ADDR'] . ', step1#, BG:OTHER, get rsWIP, with parts');
            $rswip = $bg === 'PSI1PPZIEP' ? $this->ITH_mod->select_allwip_plant1_byBG_and_Part($date, $bg, $rmstring) : $this->ITH_mod->select_allwip_plant2_byBG_and_Part($date, $bg, $rmstring);
        } else {
            if (is_array($fglist)) {
                #set FG's part code as additional data for rmlist
                #based on Hadi's (PPIC) request
                if (!is_array($rmlist)) {
                    $rmlist = [];
                }
                $rsPWOP = $this->PWOP_mod->select_mainsub_byModelArray($fglist);
                foreach ($rsPWOP as $r) {
                    if (!in_array($r['PWOP_BOMPN'], $rmlist)) {
                        $rmlist[] = $r['PWOP_BOMPN'];
                    }
                    if (!in_array($r['PWOP_SUBPN'], $rmlist)) {
                        $rmlist[] = $r['PWOP_SUBPN'];
                    }
                }
                $rmstring = "'" . implode("','", $rmlist) . "'";
                if (!empty($rmlist)) {
                    log_message('error', $_SERVER['REMOTE_ADDR'] . ', step1#, BG:OTHER, get rsWIP, with parts from FG');
                    $rswip = $bg === 'PSI1PPZIEP' ? $this->ITH_mod->select_allwip_plant1_byBG_and_Part($date, $bg, $rmstring) : $this->ITH_mod->select_allwip_plant2_byBG_and_Part($date, $bg, $rmstring);
                } else {
                    log_message('error', $_SERVER['REMOTE_ADDR'] . ', step1#, BG:OTHER, get rsWIP, without parts.');
                    $rswip = $bg === 'PSI1PPZIEP' ? $this->ITH_mod->select_allwip_plant1_byBG($date, $bg) : $this->ITH_mod->select_allwip_plant2_byBG($date, $bg);
                }
            } else {
                log_message('error', $_SERVER['REMOTE_ADDR'] . ', step1#, BG:OTHER, get rsWIP, without parts');
                $rswip = $bg === 'PSI1PPZIEP' ? $this->ITH_mod->select_allwip_plant1_byBG($date, $bg) : $this->ITH_mod->select_allwip_plant2_byBG($date, $bg);
            }
        }

        log_message('error', $_SERVER['REMOTE_ADDR'] . ', step2#, BG:OTHER, get rsPSN, without parts');
        $rspsn = $this->ITH_mod->select_psn_period_byBG($startDate, $date, $bg);

        $psnstring = "";
        $osWO = [];
        foreach ($rspsn as $r) {
            $psnstring .= "'" . $r['DOC'] . "',";
        }
        $psnstring = $psnstring == "" ? "''" : substr($psnstring, 0, strlen($psnstring) - 1);

        log_message('error', $_SERVER['REMOTE_ADDR'] . ', step2.1#, BG:OTHER, get osWO');
        if (strlen($fgstring) > 5) {
            log_message('error', $_SERVER['REMOTE_ADDR'] . ', step2.2#, BG:OTHER, --with FG');
            $osWO = $this->ITH_mod->select_wo_side_detail_BGOther($date, $fgstring, $psnstring);
        } else {
            log_message('error', $_SERVER['REMOTE_ADDR'] . ', step2.2#, BG:OTHER, --without FG');
            $osWO = $this->ITH_mod->select_wo_side_detail_byPSN_BGOther($date, $psnstring); // sampai sini
        }
        $rsPlot = [];
        $_aWO = [];
        $_aPart = [];

        log_message('error', $_SERVER['REMOTE_ADDR'] . ', step2.3#, BG:OTHER, resume WO');
        foreach ($osWO as $o) {
            if (!in_array($o['PDPP_WONO'], $_aWO)) {
                $_aWO[] = $o['PDPP_WONO'];
            }
        }

        log_message('error', $_SERVER['REMOTE_ADDR'] . ', step2.4#, BG:OTHER, resume Parts');
        foreach ($rswip as $w) {
            if ($w['PLANT2'] > 0) {
                if (!in_array($w['ITRN_ITMCD'], $_aPart)) {
                    $_aPart[] = $w['ITRN_ITMCD'];
                }
            }
        }

        #get PPSN2
        $_sWO = "'" . implode("','", $_aWO) . "'";
        $_sPart = "'" . implode("','", $_aPart) . "'";
        log_message('error', $_SERVER['REMOTE_ADDR'] . ', step2.5#, BG:OTHER, get PPSN2');
        $rsPPSN2 = $this->SPL_mod->select_ppsn2_byArrayOf_WO_and_part($_sWO, $_sPart);

        #get PPSN1
        log_message('error', $_SERVER['REMOTE_ADDR'] . ', step2.6#, BG:OTHER, get PPSN1');
        $rsPPSN1 = $this->SPL_mod->select_wo_byArrayOf_WO($_sWO);

        #initialize rsplot
        foreach ($rswip as &$w) {
            $w['B4QTY'] = $w['PLANT2'];
            $w['LOTSIZE'] = 0;
            $w['COMMENTS'] = 'code:' . $w['B4QTY'];
            $w['STOCK'] = $w['ARWH'] + $w['NRWH2'] + $w['ARWH0PD'] + $w['QA'];
            foreach ($osWO as &$o) {
                if (($w['ITRN_ITMCD'] === $o['PWOP_BOMPN'] || $w['ITRN_ITMCD'] === $o['PWOP_SUBPN'])) {
                    #PSN CHECK
                    $isRightPSN = false;
                    foreach ($rsPPSN1 as $p) {
                        if ($o['PDPP_WONO'] === $p['WONO']) {
                            foreach ($rsPPSN2 as $p2) {
                                if ($p['PSN'] === $p2['PSN'] && $w['ITRN_ITMCD'] === $p2['SUBPN']) {
                                    $isRightPSN = true;
                                    break;
                                }
                            }
                            if ($isRightPSN) {
                                break;
                            }
                        }
                    }
                    #END PSN CHECK
                    if ($isRightPSN) {
                        $balneed = $o['NEEDQTY'] - $o['PLOTQTY'];
                        if ($balneed == 0) {
                            break;
                        }

                        $fixqty = $balneed;
                        if ($balneed > $w['PLANT2']) {
                            $w['PLANT2'] = 0;
                        } else {
                            $w['PLANT2'] -= $balneed;
                        }
                        $o['PLOTQTY'] = $o['NEEDQTY'];

                        $isfound = false;
                        foreach ($rsPlot as &$r) {
                            if ($r['WO'] == $o['PDPP_WONO'] && $r['PARTCD'] == $w['ITRN_ITMCD']) {
                                $r['PARTQTY'] += $fixqty;
                                $isfound = true;
                                break;
                            }
                        }
                        unset($r);

                        if (!$isfound) {
                            $rsPlot[] = ['WO' => $o['PDPP_WONO'], 'ISSUEDATE' => $o['PDPP_ISUDT'], 'LOTSIZE' => $o['PDPP_WORQT'], 'UNIT' => $o['NEEDQTY'] / $o['PWOP_PER'], 'PER' => $o['PWOP_PER'], 'PARTCD' => $w['ITRN_ITMCD'], 'REQQTY' => $o['NEEDQTY'], 'PARTQTY' => $fixqty, 'PSN' => ''];
                        }
                    } else {
                        break;
                    }
                }
            }
            unset($o);
        }
        unset($w);
        
        if (!empty($rsPlot)) {
            #add detail on specific index
            foreach ($rsPlot as $r) {
                $theIndex = 0;
                $sampleRow = [];
                foreach ($rswip as $index => &$w) {
                    if ($r['PARTCD'] === $w['ITRN_ITMCD']) {
                        if ($w['PLANT2'] > 0) {
                            $w['LOGRTN'] = 0;
                            $w['PLANT2'] = 0;
                        }

                        $theIndex = $index + 1;
                        $sampleRow = $w;
                        break;
                    }
                }
                unset($w);

                if ($theIndex != 0) {
                    #SET PSN
                    foreach ($rsPPSN2 as $k) {
                        if ($k['SUBPN'] === $r['PARTCD']) {
                            $isfound = false;
                            foreach ($rsPPSN1 as $y) {
                                if ($r['WO'] === $y['WONO'] && $k['PSN'] === $y['PSN']) {
                                    $sampleRow['PSN'] = $y['PSN'];
                                    $isfound = true;
                                    break;
                                }
                            }
                            if ($isfound) {
                                #check is logical return per PSN is already plotted
                                $isPlotted = false;
                                foreach ($rswip as $g) {
                                    if ($g['PSN'] === $sampleRow['PSN'] && $g['ITRN_ITMCD'] === $sampleRow['ITRN_ITMCD']) {
                                        $isPlotted = true;
                                        break;
                                    }
                                }
                                if (!$isPlotted) {
                                    $sampleRow['LOGRTN'] = $k['LOGRTNQT'];
                                } else {
                                    $sampleRow['LOGRTN'] = 0;
                                }
                                break;
                            }
                        }
                    }

                    $rswip[$theIndex - 1]['STOCK'] += ($sampleRow['LOGRTN']);
                    $sampleRow['ITMD1'] = '';
                    $sampleRow['MITM_SPTNO'] = '';
                    $sampleRow['ARWH'] = 0;
                    $sampleRow['NRWH2'] = 0;
                    $sampleRow['ARWH0PD'] = 0;
                    $sampleRow['JOB'] = $r['WO'];
                    $sampleRow['LOTSIZE'] = $r['LOTSIZE'];
                    $sampleRow['JOBUNIT'] = $r['UNIT'];
                    $sampleRow['PLANT2'] = $r['PARTQTY'];
                    $sampleRow['STOCK'] = 0;
                    $sampleRow['QA'] = 0;
                    $sampleRow['COMMENTS'] = '';
                    $rswip = array_merge(array_slice($rswip, 0, $theIndex), [$sampleRow], array_slice($rswip, $theIndex));
                }
            }
        }
        

        log_message('error', $_SERVER['REMOTE_ADDR'] . ', step2.7#, analyze data when WO Closed but PSN is not returned');
        $arrPSNUnplotted = [];
        foreach ($rspsn as &$p) {
            $isfound = false;
            foreach ($rswip as $w) {
                if ($p['DOC'] === $w['PSN']) {
                    $isfound = true;
                    break;
                }
            }
            $p['PLOTTED'] = $isfound;
            if (!$isfound) {
                $arrPSNUnplotted[] = $p['DOC'];
            }

        }
        unset($p);

        $strPSNUnplotted = "'" . implode("','", $arrPSNUnplotted) . "'";
        $rsPSNBalance = $this->SPLSCN_mod->select_logical_return_byPSN($strPSNUnplotted);
        foreach ($rsPSNBalance as $r) {
            $theIndex = 0;
            $sampleRow = [];
            foreach ($rswip as $index => &$w) {
                if ($r['ITMCD'] === $w['ITRN_ITMCD']) {
                    $theIndex = $index + 1;
                    $sampleRow = $w;
                    $w['STOCK'] += $r['LOGRET'];
                    break;
                }
            }
            unset($w);
            if ($theIndex != 0) {
                $sampleRow['ITMD1'] = '';
                $sampleRow['MITM_SPTNO'] = '';
                $sampleRow['ARWH'] = 0;
                $sampleRow['NRWH2'] = 0;
                $sampleRow['ARWH0PD'] = 0;
                $sampleRow['PSN'] = $r['PSNNO'];
                $sampleRow['JOB'] = '';
                $sampleRow['JOBUNIT'] = '';
                $sampleRow['PLANT2'] = '';
                $sampleRow['LOGRTN'] = $r['LOGRET'];
                $sampleRow['STOCK'] = 0;
                $sampleRow['QA'] = 0;
                $sampleRow['LOTSIZE'] = '';
                $sampleRow['COMMENTS'] = '';
                $rswip = array_merge(array_slice($rswip, 0, $theIndex), [$sampleRow], array_slice($rswip, $theIndex));
            }
        }
        
        # next filter set logical return = 0 when PSN already returned
        $_uniquePSN = [];
        foreach($rswip as $r) {
            if(!in_array($r['PSN'], $_uniquePSN)) {
                $_uniquePSN[] = $r['PSN'];
            }
        }
       

        $ReturnedPSN = empty($_uniquePSN) ? [] : $this->SPLRET_mod->select_psn_where_psn_in($_uniquePSN);

        foreach($ReturnedPSN as $s) {
            foreach($rswip as &$r) {
                if($r['PSN'] == $s['RETSCN_SPLDOC']) {
                    # deep search
                    foreach($rswip as &$_d) {
                        if($r['ITRN_ITMCD'] == $_d['ITRN_ITMCD'] && empty($_d['PSN'])) {
                            $_d['STOCK']-=$r['LOGRTN'];
                            break;
                        }
                    }
                    unset($_d);

                    $r['LOGRTN'] = 0;

                    break;
                }
            }
            unset($r);
        }
        $rang = "A1:A" . $sheet->getHighestDataRow();
        if (!empty($rswip)) {
            log_message('error', $_SERVER['REMOTE_ADDR'] . ', step3#, BG:OTHER, rsPSN to Spreadsheet');
            $sheet = $spreadsheet->createSheet();
            $sheet->setTitle('RM_RESUME');
            $sheet->setCellValueByColumnAndRow(1, 2, 'Part Code');
            $sheet->mergeCells('A2:A4'); #rowspan3
            $sheet->setCellValueByColumnAndRow(2, 2, 'Part Name');
            $sheet->mergeCells('B2:B4'); #rowspan3
            $sheet->setCellValueByColumnAndRow(3, 2, 'Location');
            $sheet->mergeCells('C2:M2'); #colspan11
            $sheet->setCellValueByColumnAndRow(3, 3, 'RM Warehouse');
            $sheet->mergeCells('C3:D3'); #colspan2
            $sheet->setCellValueByColumnAndRow(5, 3, 'ARWH0PD');
            $sheet->mergeCells('E3:E4'); #rowspan2
            $sheet->setCellValueByColumnAndRow(6, 3, 'Plant');
            $sheet->mergeCells('F3:K3'); #colspan6
            $sheet->setCellValueByColumnAndRow(12, 3, 'QA');
            $sheet->mergeCells('L3:L4'); #rowspan2
            $sheet->setCellValueByColumnAndRow(13, 3, 'STOCK');
            $sheet->mergeCells('M3:M4'); #rowspan2

            $sheet->setCellValueByColumnAndRow(3, 4, 'ARWH');
            $sheet->setCellValueByColumnAndRow(4, 4, 'NRWH2');

            $sheet->setCellValueByColumnAndRow(6, 4, 'PSN');
            $sheet->setCellValueByColumnAndRow(7, 4, 'Job');
            $sheet->setCellValueByColumnAndRow(8, 4, 'Lot Size');
            $sheet->setCellValueByColumnAndRow(9, 4, 'OS Job (Unit)');
            $sheet->setCellValueByColumnAndRow(10, 4, 'OS Job (Pcs)');
            $sheet->setCellValueByColumnAndRow(11, 4, 'Logical Return');
            $sheet->freezePane('C5');
            $y = 5;
            foreach ($rswip as $r) {
                if (!empty($r['COMMENTS'])) {
                    $sheet->getComment('J' . $y)->getText()->createTextRun($r['COMMENTS']);
                }
                $sheet->setCellValueByColumnAndRow(1, $y, $r['ITRN_ITMCD']);
                $sheet->setCellValueByColumnAndRow(2, $y, $r['MITM_SPTNO']);
                $sheet->setCellValueByColumnAndRow(3, $y, $r['ARWH']);
                $sheet->setCellValueByColumnAndRow(4, $y, $r['NRWH2']);
                $sheet->setCellValueByColumnAndRow(5, $y, $r['ARWH0PD']);
                $sheet->setCellValueByColumnAndRow(6, $y, $r['PSN']);
                $sheet->setCellValueByColumnAndRow(7, $y, $r['JOB']);
                $sheet->setCellValueByColumnAndRow(8, $y, $r['LOTSIZE']);
                $sheet->setCellValueByColumnAndRow(9, $y, $r['JOBUNIT']);
                $sheet->setCellValueByColumnAndRow(10, $y, $r['ITMD1'] != '' ? 0 : $r['PLANT2']);
                $sheet->setCellValueByColumnAndRow(11, $y, $r['LOGRTN']);
                $sheet->setCellValueByColumnAndRow(12, $y, $r['QA']);
                $sheet->setCellValueByColumnAndRow(13, $y, $r['STOCK']);
                $y++;
            }
            $sheet->getStyle($rang)->getAlignment()->setHorizontal(\PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_LEFT);
            foreach (range('A', 'M') as $v) {
                $sheet->getColumnDimension($v)->setAutoSize(true);
            }

            #FORMAT HEADER
            $sheet->getStyle("A2:M4")->getAlignment()
                ->setHorizontal(\PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER)
                ->setVertical(\PhpOffice\PhpSpreadsheet\Style\Alignment::VERTICAL_CENTER);

            #FORMAT BORDER
            $rang = "A2:M" . $sheet->getHighestDataRow();
            $sheet->getStyle($rang)->getBorders()->getAllBorders()->setBorderStyle(Border::BORDER_THIN)
                ->setColor(new Color('1F1812'));

            #FORMAT NUMBER
            $rang = "C5:M" . $sheet->getHighestDataRow();
            $sheet->getStyle($rang)->getNumberFormat()->setFormatCode('#,##0');
        }

        log_message('error', $_SERVER['REMOTE_ADDR'] . ', step4#, BG:OTHER, done');
        $current_datetime = date('Y-m-d H:i:s');
        $spreadsheet->getProperties()
            ->setCreator('WMS')
            ->setTitle('CP ' . $current_datetime);
        $stringjudul = $bg === 'PSI2PPZOMI' ? "Daily Stock Report On $date" : "Critical Part $bg On $date";
        $writer = new Xlsx($spreadsheet);
        $filename = $stringjudul; //save our workbook as this file name

        header('Content-Type: application/vnd.ms-excel');
        header('Content-Disposition: attachment;filename="' . $filename . '.xlsx"');
        header('Cache-Control: max-age=0');
        
        if ($saveOutput == 1) {            
            $writer->save('php://output');
        } else {
            $writer->save($_ENV['APP_CRITICAL_PART_FILE_PATH'] . $filename . '.xlsx');
            echo "done";
        }
    }

    public function change_adj_qty()
    {
        header('Content-Type: application/json');
        $itemcd = $this->input->post('itemcd');
        $old_qty = $this->input->post('old_qty');
        $new_qty = $this->input->post('new_qty');
        $lupdt = $this->input->post('lupdt');
        $wh = $this->input->post('wh');
        $form = $this->input->post('form');
        $where = [
            'ITH_ITMCD' => $itemcd, 'ITH_FORM' => $form, 'ITH_WH' => $wh, 'ITH_QTY' => $old_qty, 'ITH_LUPDT' => $lupdt,
        ];
        $respon = $this->ITH_mod->updatebyId(
            $where,
            ['ITH_QTY' => $new_qty]
        );
        $myar = $respon ? ['cd' => '1', 'msg' => 'OK'] : ['cd' => '0', 'msg' => 'Could not updated'];
        log_message('error', $_SERVER['REMOTE_ADDR'] . ', change adj-qty value ' . json_encode($where));
        die(json_encode(['status' => $myar]));
    }

    public function raw_change_date_cancel()
    {
        header('Content-Type: application/json');
        $date = $this->input->get('date');
        $item_doc = $this->input->get('doc');
        $rs = $this->ITH_mod->select_doc_vs_datec_about_change_date_of_cancel($item_doc, $date);
        $tmpTime = strtotime($date . ' +1 days');
        $date0 = date('Y-m-d', $tmpTime);
        $rsTobeSaved = [];
        foreach ($rs as &$r) {
            $r['TIME'] = substr($r['ITH_LUPDT'], 11, 8);
            if ($r['TIME'] >= '00:00:00' && $r['TIME'] <= '07:00:00') {
                $r['TO_LUPDT'] = $date0 . " " . $r['TIME'];
            } else {
                $r['TO_LUPDT'] = $date . " " . $r['TIME'];
            }

            $r['ITH_QTY'] = abs($r['ITH_QTY']);
            $rsTobeSaved[] = $r;
        }
        unset($r);
        die(json_encode(['data' => $rsTobeSaved]));
    }

    public function raw_change_date_return()
    {
        header('Content-Type: application/json');
        $date = $this->input->get('date');
        $item_doc = $this->input->get('doc');
        $rs = $this->ITH_mod->select_doc_vs_datec_about_change_date_of_return($item_doc, $date);
        $tmpTime = strtotime($date . ' +1 days');
        $date0 = date('Y-m-d', $tmpTime);
        $rsTobeSaved = [];
        foreach ($rs as &$r) {
            $r['TIME'] = substr($r['ITH_LUPDT'], 11, 8);
            if ($r['TIME'] >= '00:00:00' && $r['TIME'] <= '07:00:00') {
                $r['TO_LUPDT'] = $date0 . " " . $r['TIME'];
            } else {
                $r['TO_LUPDT'] = $date . " " . $r['TIME'];
            }

            $r['ITH_QTY'] = abs($r['ITH_QTY']);
            $rsTobeSaved[] = $r;
        }
        unset($r);
        die(json_encode(['data' => $rsTobeSaved]));
    }

    public function change_kitting_date()
    {
        date_default_timezone_set('Asia/Jakarta');
        header('Content-Type: application/json');
        $doc = $this->input->post('doc');
        $date = $this->input->post('date');
        $dateTime = $this->input->post('dateTime');
        $items = $this->input->post('items');
        $rs = $this->ITH_mod->select_doc_vs_datec_withIn_itemsAndDates($doc, $date, $items, $dateTime);
        $tmpTime = strtotime($date . ' +1 days');
        $date0 = date('Y-m-d', $tmpTime);
        $thedate = $date;
        $affectedRows = 0;
        foreach ($rs as &$r) {
            $r['TIME'] = substr($r['ITH_LUPDT'], 11, 8);
            if ($r['TIME'] >= '00:00:00' && $r['TIME'] <= '07:00:00') {
                $r['TO_LUPDT'] = $date0 . " " . $r['TIME'];
                $thedate = $date0;
            } else {
                $r['TO_LUPDT'] = $date . " " . $r['TIME'];
            }
            $affectedRows += $this->ITH_mod->update_kitting_date(
                $doc,
                $thedate,
                $r['TO_LUPDT'],
                substr($r['ITH_LUPDT'], 0, 19),
                $r['ITH_ITMCD']
            );
        }
        unset($r);

        $message = $affectedRows > 0 && fmod($affectedRows, 2) == 0 ? 'Changed successfully' : 'Changed, but please contact Admin';

        die(json_encode([
            'status' => ['cd' => '1', 'msg' => $message, 'affectedRows' => $affectedRows], 'data' => $rs,
        ]));
    }
    public function change_cancelling_date()
    {
        $this->checkSession();
        date_default_timezone_set('Asia/Jakarta');
        header('Content-Type: application/json');
        $doc = $this->input->post('doc');
        $date = $this->input->post('date');
        $items = $this->input->post('items');
        $dates = $this->input->post('dates');
        $rs = $this->ITH_mod->selectForCancellingwithIn_items($doc, $date, $items, $dates);
        $tmpTime = strtotime($date . ' +1 days');
        $date0 = date('Y-m-d', $tmpTime);
        $thedate = $date;
        $affectedRows = 0;
        foreach ($rs as &$r) {
            $r['TIME'] = substr($r['ITH_LUPDT'], 11, 8);
            if ($r['TIME'] >= '00:00:00' && $r['TIME'] <= '07:00:00') {
                $r['TO_LUPDT'] = $date0 . " " . $r['TIME'];
                $thedate = $date0;
            } else {
                $r['TO_LUPDT'] = $date . " " . $r['TIME'];
                $thedate = $date;
            }
            $affectedRows += $this->ITH_mod->update_cancel_kitting_date(
                $doc,
                $thedate,
                $r['TO_LUPDT'],
                $r['ITH_LUPDT'],
                $r['ITH_ITMCD']
            );
            $lastLineLog = $this->CSMLOG_mod->select_lastLine($doc, '') + 1;
            $this->CSMLOG_mod->insert([
                'CSMLOG_DOCNO' => $doc,
                'CSMLOG_SUPZAJU' => '',
                'CSMLOG_SUPZNOPEN' => '',
                'CSMLOG_DESC' => 'change date of cancel, psn ' . $doc . ', part code ' . $r['ITH_ITMCD'],
                'CSMLOG_LINE' => $lastLineLog,
                'CSMLOG_TYPE' => 'INC',
                'CSMLOG_CREATED_AT' => date('Y-m-d H:i:s'),
                'CSMLOG_CREATED_BY' => $this->session->userdata('nama'),
            ]);
        }
        unset($r);
        die(json_encode([
            'status' => ['cd' => '1', 'msg' => $affectedRows . ' row(s) updated'], 'data' => $rs,
        ]));
    }
    public function change_returned_psn_date()
    {
        $this->checkSession();
        date_default_timezone_set('Asia/Jakarta');
        header('Content-Type: application/json');
        $doc = $this->input->post('doc');
        $date = $this->input->post('date');
        $items = $this->input->post('items');
        $dates = $this->input->post('dates');
        $remarks = $this->input->post('remarks');
        $rs = $this->ITH_mod->selectForReturningwithIn_items($doc, $date, $items, $dates, $remarks);
        $tmpTime = strtotime($date . ' +1 days');
        $date0 = date('Y-m-d', $tmpTime);
        $thedate = $date;
        $affectedRows = 0;
        foreach ($rs as &$r) {
            $r['TIME'] = substr($r['ITH_LUPDT'], 11, 8);
            if ($r['TIME'] >= '00:00:00' && $r['TIME'] <= '07:00:00') {
                $r['TO_LUPDT'] = $date0 . " " . $r['TIME'];
                $thedate = $date0;
            } else {
                $r['TO_LUPDT'] = $date . " " . $r['TIME'];
                $thedate = $date;
            }
            if ($this->ITH_mod->update_return_kitting_date(
                $doc,
                $thedate,
                $r['TO_LUPDT'],
                $r['ITH_LUPDT'],
                $r['ITH_ITMCD'],
                $r['ITH_REMARK'],
            )) {
                $affectedRows += 1;
                $lastLineLog = $this->CSMLOG_mod->select_lastLine($doc, '') + 1;
                $this->CSMLOG_mod->insert([
                    'CSMLOG_DOCNO' => $doc,
                    'CSMLOG_SUPZAJU' => '',
                    'CSMLOG_SUPZNOPEN' => '',
                    'CSMLOG_DESC' => 'change date of return, psn ' . $doc . ', part code ' . $r['ITH_ITMCD'],
                    'CSMLOG_LINE' => $lastLineLog,
                    'CSMLOG_TYPE' => 'INC',
                    'CSMLOG_CREATED_AT' => date('Y-m-d H:i:s'),
                    'CSMLOG_CREATED_BY' => $this->session->userdata('nama'),
                ]);
            }
        }
        unset($r);

        die(json_encode([
            'status' => ['cd' => '1', 'msg' => $affectedRows . ' row(s) updated'], 'data' => $rs,
        ]));
    }

    public function raw_ith_remove()
    {
        header('Content-Type: application/json');
        $item_code = $this->input->post('item_code');
        $item_location = $this->input->post('item_location');
        $item_event = $this->input->post('item_event');
        $item_qty = $this->input->post('item_qty');
        $datetime_tx = $this->input->post('datetime_tx');
        $where = [
            'ITH_ITMCD' => $item_code,
            'ITH_WH' => $item_location,
            'ITH_FORM' => $item_event,
            'ITH_QTY' => $item_qty,
            'ITH_LUPDT' => $datetime_tx,
        ];
        $affectedRows = $this->ITH_mod->deletebyID($where);
        $myar[] = $affectedRows ? ['cd' => '1', 'msg' => 'ok'] : ['cd' => '0', 'msg' => 'could not be deleted'];
        die(json_encode(['status' => $myar, 'filter' => $where]));
    }

    public function remove_uploaded_stock_it_inventory()
    {
        header('Content-Type: application/json');
        $date = $this->input->post('date');
        $stockType = $this->input->post('stocktype');
        $result = 0;
        $paramdel = null;
        if ($stockType === 'P') {
            $result = $this->RPSAL_INVENTORY_mod->deletebyID(['INV_PHY_DATE' => $date]);
            $paramdel = ['INV_PHY_DATE' => $date];
        } else {
            $result = $this->RPSAL_INVENTORY_mod->deletebyID(['INV_DATE' => $date]);
            $paramdel = ['INV_DATE' => $date];
        }
        $myar = $result ? ['cd' => '1', 'msg' => 'Deleted'] : ['cd' => '0', 'msg' => 'Could not be deleted'];
        die(json_encode(['status' => $myar, 'paramdel' => $paramdel]));
    }
}
