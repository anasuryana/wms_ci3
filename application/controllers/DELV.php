<?php
defined('BASEPATH') or exit('No direct script access allowed');

use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xls;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;

class DELV extends CI_Controller
{
    private $AMONTHPATRN = ['1', '2', '3', '4', '5', '6', '7', '8', '9', 'X', 'Y', 'Z'];
    private $MSG_USERS = ['2200120', '1190031', 'ane', '206224', '200993', '28250', '1160016', '13221'];
    private $PATTERFLG = '2';
    public function __construct()
    {
        parent::__construct();
        $this->load->helper('url');
        $this->load->library('session');
        $this->load->library('Code39e128');
        $this->load->library('RMCalculator');
        $this->load->library('TXBridge');
        $this->load->library('user_agent');
        $this->load->model('Usergroup_mod');
        $this->load->model('DELV_mod');
        $this->load->model('DLVCK_mod');
        $this->load->model('DLVSO_mod');
        $this->load->model('DLVPRC_mod');
        $this->load->model('DLVSCR_mod');
        $this->load->model('SPL_mod');
        $this->load->model('SER_mod');
        $this->load->model('SPLSCN_mod');
        $this->load->model('ITH_mod');
        $this->load->model('Trans_mod');
        $this->load->model('MEXRATE_mod');
        $this->load->model('SERD_mod');
        $this->load->model('SERC_mod');
        $this->load->model('MMDL_mod');
        $this->load->model('RCV_mod');
        $this->load->model('DLVRMDOC_mod');
        $this->load->model('DLVRMSO_mod');
        $this->load->model('refceisa/ZWAYTRANS_mod');
        $this->load->model('refceisa/ZOffice_mod');
        $this->load->model('refceisa/AKTIVASIAPLIKASI_imod');
        $this->load->model('refceisa/MTPB_mod');
        $this->load->model('refceisa/MPurposeDLV_imod');
        $this->load->model('XBGROUP_mod');
        $this->load->model('BGROUP_mod');
        $this->load->model('SI_mod');
        $this->load->model('PTTRN_mod');
        $this->load->model('XSO_mod');
        $this->load->model('MSPP_mod');
        $this->load->model('SISO_mod');
        $this->load->model('TXROUTE_mod');
        $this->load->model('SISCN_mod');
        $this->load->model('MSTITM_mod');
        $this->load->model('ZRPSTOCK_mod');
        $this->load->model('PST_LOG_mod');
        $this->load->model('MSG_mod');
        $this->load->model('MSTLOCG_mod');
        $this->load->model('XITRN_mod');
        $this->load->model('DisposeDraft_mod');
        $this->load->model('POSTING_mod');
        $this->load->model(['CSMLOG_mod', 'DLVH_mod', 'RPSAL_BCSTOCK_mod']);
        $this->load->model('refceisa/TPB_HEADER_imod');
        $this->load->model('refceisa/TPB_KEMASAN_imod');
        $this->load->model('refceisa/TPB_DOKUMEN_imod');
        $this->load->model('refceisa/TPB_BARANG_imod');
        $this->load->model('refceisa/TPB_BARANG_TARIF_imod');
        $this->load->model('refceisa/TPB_BAHAN_BAKU_imod');
        $this->load->model('refceisa/TPB_BAHAN_BAKU_TARIF_imod');
        $this->load->model('refceisa/TPB_NPWP_BILLING_imod');
        $this->load->model('refceisa/TPB_PUNGUTAN_imod');
        $this->load->model('refceisa/TPB_DETIL_STATUS_imod');
        $this->load->model('refceisa/TPB_RESPON_imod');
        date_default_timezone_set('Asia/Jakarta');
    }

    public function checkBrowser()
    {
        echo $this->agent->browser();
    }

    public function exception_handler($exception)
    {
        echo $exception->getMessage();
    }
    public function fatal_handler()
    {
        $errfile = 'unknown file';
        $errstr = 'shutdown';
        $errno = E_CORE_ERROR;
        $errline = 0;
        $error = error_get_last();
        if ($error !== null) {
            $errno = $error['type'];
            $errfile = $error['file'];
            $errline = $error['line'];
            $errstr = $error['message'];
        }
    }
    public function log_error($errno = '', $errstr = '', $errfile = '', $errline = '')
    {
        throw new Exception('ERROR NO : ' . $errno . ' MESSAGE : ' . $errstr . ' LINE : ' . $errline);
    }

    public function testCalculator()
    {
        $calc = new RMCalculator();
        $apsn = ['SP-IEI-2021-11-0580'];
        $rspsn_sup = $calc->tobexported_list_for_serd($apsn);
        $rsused = $this->SERD_mod->selectall_where_psn_in($apsn);

        #decrease usage by Used RM of another JOB
        foreach ($rsused as &$d) {
            if (!array_key_exists("USED", $d)) {
                $d["USED"] = false;
            }
        }
        unset($d);

        foreach ($rspsn_sup as &$r) {
            $r['SPLSCN_QTY_BAK'] = $r['SPLSCN_QTY'];
            foreach ($rsused as &$k) {
                if (
                    $r['SPLSCN_FEDR'] == trim($k['SERD_FR'])
                    && $r['SPLSCN_DOC'] == trim($k['SERD_PSNNO'])
                    && $r['PPSN2_MSFLG'] == trim($k['SERD_MSFLG'])
                    && $r['SPLSCN_ITMCD'] == trim($k['SERD_ITMCD'])
                    && $r['SPLSCN_LOTNO'] == trim($k['SERD_LOTNO'])
                    && $r['SPLSCN_ORDERNO'] == trim($k['SERD_MCZ'])
                    && $r['SPLSCN_LINE'] == trim($k['SERD_LINENO'])
                    && $r['PPSN2_MC'] == trim($k['SERD_MC'])
                    && $r['PPSN2_PROCD'] == trim($k['SERD_PROCD'])
                    && !$k['USED']
                ) {
                    if (intval($r['SPLSCN_QTY']) > $k['SERD_QTY']) {
                        $r['SPLSCN_QTY'] -= $k['SERD_QTY'];
                        $k['SERD_QTY'] = 0;
                        $k['USED'] = true;
                        $r['SPLSCN_QTY_BAK'] = $r['SPLSCN_QTY'];
                    } else {
                        $k['SERD_QTY'] -= $r['SPLSCN_QTY'] * 1;
                        $r['SPLSCN_QTY'] = 0;
                        $r['SPLSCN_QTY_BAK'] = $r['SPLSCN_QTY'];
                        if ($k['SERD_QTY'] == 0) {
                            $k['USED'] = true;
                        }
                        break;
                    }
                }
            }
            unset($k);
        }
        unset($r);
        die(json_encode($rspsn_sup));
    }

    public function index()
    {
        die("sorry");
    }

    public function remove_delv_rm()
    {
        header('Content-Type: application/json');
        $txid = $this->input->post('txid');
        $line = $this->input->post('line');
        if ($this->ZRPSTOCK_mod->check_Primary(['RPSTOCK_REMARK' => $txid])) {
            $myar[] = ['cd' => '0', 'msg' => 'could not be deleted because already booked'];
        } else {
            $ret = $this->DLVRMDOC_mod->deleteby_filter(['DLVRMDOC_TXID' => $txid, 'DLVRMDOC_LINE' => $line]);
            if ($ret) {
                $myar[] = ['cd' => 1, 'msg' => 'OK'];
            } else {
                $myar[] = ['cd' => 0, 'msg' => 'Could not be deleted'];
            }
        }
        die(json_encode(['status' => $myar]));
    }
    public function remove_delv_rm_per_txid()
    {
        header('Content-Type: application/json');
        $txid = $this->input->post('txid');
        if ($this->ZRPSTOCK_mod->check_Primary(['RPSTOCK_REMARK' => $txid])) {
            $myar[] = ['cd' => '0', 'msg' => 'could not be deleted because already booked'];
        } else {
            $ret = $this->DLVRMDOC_mod->deleteby_filter(['DLVRMDOC_TXID' => $txid]);
            if ($ret) {
                $myar[] = ['cd' => 1, 'msg' => 'OK'];
            } else {
                $myar[] = ['cd' => 0, 'msg' => 'Could not be deleted'];
            }
        }
        die(json_encode(['status' => $myar]));
    }

    public function form_report_summary_invoice()
    {
        $this->load->view('wms_report/vsummary_invoice');
    }
    public function form_preparation_checking()
    {
        $this->load->view('wms/vpreparation_checking');
    }

    public function form_report_summary_part_return()
    {
        $this->load->view('wms_report/vrpt_summary_part_return');
    }

    public function report_summary_part_return()
    {
        header('Content-Type: application/json');
        $pdate1 = $this->input->get('date1');
        $pdate2 = $this->input->get('date2');
        $cbgroup = $this->input->get('bsgrp');
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
            $sbgroup = "''";
        }
        $rs = $this->ITH_mod->select_deliv_part_to3rdparty($pdate1, $pdate2, $sbgroup);
        die(json_encode(['data' => $rs]));
    }

    public function report_summary_inv()
    {
        header('Content-Type: application/json');
        $pdate1 = $this->input->get('date1');
        $pdate2 = $this->input->get('date2');
        $cbgroup = $this->input->get('bsgrp');
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
            $sbgroup = "''";
        }
        $rs = $this->ITH_mod->select_deliv_invo($pdate1, $pdate2, $sbgroup);
        die(json_encode(['data' => $rs]));
    }
    public function report_summary_inv_as_xls()
    {
        $bsgrp = '';
        $pdate1 = '';
        $pdate2 = '';
        if (isset($_COOKIE["CKPSI_BG"])) {
            $bsgrp = $_COOKIE["CKPSI_BG"];
        } else {
            exit('nothing to be exported');
        }
        $pdate1 = $_COOKIE["CKPSI_DATE1"];
        $pdate2 = $_COOKIE["CKPSI_DATE2"];
        $sbgroup = $bsgrp;
        $rs = $this->ITH_mod->select_deliv_invo($pdate1, $pdate2, $sbgroup);
        $spreadsheet = new Spreadsheet();
        $sheet = $spreadsheet->getActiveSheet();
        $sheet->setTitle('RESUME');
        $sheet->setCellValueByColumnAndRow(1, 1, 'SALES');
        $sheet->setCellValueByColumnAndRow(1, 2, 'PERIOD : ' . str_replace('-', '/', $pdate1) . ' - ' . str_replace('-', '/', $pdate2));
        $sheet->setCellValueByColumnAndRow(1, 3, 'BUSINESS : ' . str_replace("'", "", $sbgroup));

        $sheet->setCellValueByColumnAndRow(1, 4, 'SHIP DATE');
        $sheet->setCellValueByColumnAndRow(2, 4, 'DO NO');
        $sheet->setCellValueByColumnAndRow(3, 4, 'Customer DO NO');
        $sheet->setCellValueByColumnAndRow(4, 4, 'DELIVERY CODE');
        $sheet->setCellValueByColumnAndRow(5, 4, 'No. Aju');
        $sheet->setCellValueByColumnAndRow(6, 4, 'Nopen');
        $sheet->setCellValueByColumnAndRow(7, 4, 'SPPB No');
        $sheet->setCellValueByColumnAndRow(8, 4, 'Invoice Date (Confirmation)');
        $sheet->setCellValueByColumnAndRow(9, 4, 'Invoice Date (Document)');
        $sheet->setCellValueByColumnAndRow(10, 4, 'NO. Invoice STX');
        $sheet->setCellValueByColumnAndRow(11, 4, 'No. Invoice SMT');
        $sheet->setCellValueByColumnAndRow(12, 4, 'MODEL CODE');
        $sheet->setCellValueByColumnAndRow(13, 4, 'MODEL DESCRIPTION');
        $sheet->setCellValueByColumnAndRow(14, 4, 'CPO NO');
        $sheet->setCellValueByColumnAndRow(15, 4, 'SHIP QTY');
        $sheet->setCellValueByColumnAndRow(16, 4, 'SALES PRICE');
        $sheet->setCellValueByColumnAndRow(17, 4, 'AMOUNT');
        $inx = 5;
        foreach ($rs as $r) {
            $sheet->setCellValueByColumnAndRow(1, $inx, $r['ITH_DATEC']);
            $sheet->setCellValueByColumnAndRow(2, $inx, $r['ITH_DOC']);
            $sheet->setCellValueByColumnAndRow(3, $inx, $r['DLV_CUSTDO']);
            $sheet->setCellValueByColumnAndRow(4, $inx, $r['DLV_CONSIGN']);
            $sheet->setCellValueByColumnAndRow(5, $inx, $r['NOMAJU']);
            $sheet->setCellValueByColumnAndRow(6, $inx, $r['NOMPEN']);
            $sheet->setCellValueByColumnAndRow(7, $inx, $r['DLV_SPPBDOC']);
            $sheet->setCellValueByColumnAndRow(8, $inx, $r['ITH_DATEC']);
            $sheet->setCellValueByColumnAndRow(9, $inx, $r['INVDT']);
            $sheet->setCellValueByColumnAndRow(10, $inx, $r['DLV_INVNO']);
            $sheet->setCellValueByColumnAndRow(11, $inx, $r['DLV_SMTINVNO']);
            $sheet->setCellValueByColumnAndRow(12, $inx, $r['ITH_ITMCD']);
            $sheet->setCellValueByColumnAndRow(13, $inx, $r['ITMDESCD']);
            $sheet->setCellValueByColumnAndRow(14, $inx, $r['DLVPRC_CPO']);
            $sheet->setCellValueByColumnAndRow(15, $inx, $r['DLVPRC_QTY']);
            $sheet->setCellValueByColumnAndRow(16, $inx, $r['DLVPRC_PRC']);
            $sheet->setCellValueByColumnAndRow(17, $inx, "=ROUND(" . $r['AMOUNT'] . ",2)");
            $inx++;
        }
        foreach (range('A', 'P') as $r) {
            $sheet->getColumnDimension($r)->setAutoSize(true);
        }
        $rang = "P1:P" . $inx;
        $sheet->getStyle($rang)->getNumberFormat()->setFormatCode(\PhpOffice\PhpSpreadsheet\Style\NumberFormat::FORMAT_NUMBER_COMMA_SEPARATED1);
        $dodis = $pdate1 . " to " . $pdate2;
        $stringjudul = "Summary Sales Invoice " . $dodis;
        $writer = new Xlsx($spreadsheet);
        $filename = $stringjudul; //save our workbook as this file name

        header('Content-Type: application/vnd.ms-excel');
        header('Content-Disposition: attachment;filename="' . $filename . '.xlsx"');
        header('Cache-Control: max-age=0');
        $writer->save('php://output');
    }
    public function report_summary_return_as_xls()
    {
        $bsgrp = '';
        $pdate1 = '';
        $pdate2 = '';
        if (isset($_COOKIE["CKPSI_BG"])) {
            $bsgrp = $_COOKIE["CKPSI_BG"];
        } else {
            exit('nothing to be exported');
        }
        $pdate1 = $_COOKIE["CKPSI_DATE1"];
        $pdate2 = $_COOKIE["CKPSI_DATE2"];
        $sbgroup = $bsgrp;
        $rs = $this->ITH_mod->select_deliv_part_to3rdparty($pdate1, $pdate2, $sbgroup);
        $spreadsheet = new Spreadsheet();
        $sheet = $spreadsheet->getActiveSheet();
        $sheet->setTitle('RESUME');
        $sheet->setCellValueByColumnAndRow(1, 1, 'RETURN');
        $sheet->setCellValueByColumnAndRow(1, 2, 'PERIOD : ' . str_replace('-', '/', $pdate1) . ' - ' . str_replace('-', '/', $pdate2));
        $sheet->setCellValueByColumnAndRow(1, 3, 'BUSINESS : ' . str_replace("'", "", $sbgroup));

        $sheet->setCellValueByColumnAndRow(1, 4, 'Business');
        $sheet->setCellValueByColumnAndRow(2, 4, 'Trans Date');
        $sheet->setCellValueByColumnAndRow(3, 4, 'TX ID');
        $sheet->setCellValueByColumnAndRow(4, 4, 'Invoice');
        $sheet->setCellValueByColumnAndRow(5, 4, 'MEGA Doc');
        $sheet->setCellValueByColumnAndRow(6, 4, 'Consignee');
        $sheet->setCellValueByColumnAndRow(7, 4, 'Reff Document');
        $sheet->setCellValueByColumnAndRow(8, 4, 'Description');
        $sheet->setCellValueByColumnAndRow(9, 4, 'Item Code');
        $sheet->setCellValueByColumnAndRow(10, 4, 'Item Name');
        $sheet->setCellValueByColumnAndRow(11, 4, 'Return Qty');
        $sheet->setCellValueByColumnAndRow(12, 4, 'Location From');
        $sheet->setCellValueByColumnAndRow(13, 4, 'Price');
        $sheet->setCellValueByColumnAndRow(14, 4, 'Amount');
        $sheet->setCellValueByColumnAndRow(15, 4, '3rd Party');
        $inx = 5;
        foreach ($rs as $r) {
            $sheet->setCellValueByColumnAndRow(1, $inx, $r['DLV_BSGRP']);
            $sheet->setCellValueByColumnAndRow(2, $inx, $r['DLV_DATE']);
            $sheet->setCellValueByColumnAndRow(3, $inx, $r['ITH_DOC']);
            $sheet->setCellValueByColumnAndRow(4, $inx, $r['DLV_SMTINVNO']);
            $sheet->setCellValueByColumnAndRow(5, $inx, $r['DLV_PARENTDOC']);
            $sheet->setCellValueByColumnAndRow(6, $inx, $r['DLV_CONSIGN']);
            $sheet->setCellValueByColumnAndRow(7, $inx, $r['DLV_RPRDOC']);
            $sheet->setCellValueByColumnAndRow(8, $inx, $r['DLV_DSCRPTN']);
            $sheet->setCellValueByColumnAndRow(9, $inx, $r['ITH_ITMCD']);
            $sheet->setCellValueByColumnAndRow(10, $inx, $r['ITMDESCD']);
            $sheet->setCellValueByColumnAndRow(11, $inx, $r['DLVPRC_QTY']);
            $sheet->setCellValueByColumnAndRow(12, $inx, $r['DLV_LOCFR']);
            $sheet->setCellValueByColumnAndRow(13, $inx, $r['DLVPRC_PRC']);
            $sheet->setCellValueByColumnAndRow(14, $inx, $r['AMOUNT']);
            $sheet->setCellValueByColumnAndRow(15, $inx, $r['MSUP_SUPCR']);
            $inx++;
        }
        foreach (range('B', 'N') as $r) {
            $sheet->getColumnDimension($r)->setAutoSize(true);
        }
        $rang = "M1:M" . $inx;
        $sheet->getStyle($rang)->getNumberFormat()->setFormatCode(\PhpOffice\PhpSpreadsheet\Style\NumberFormat::FORMAT_NUMBER_COMMA_SEPARATED1);
        $dodis = $pdate1 . " to " . $pdate2;
        $stringjudul = "Summary Part Return " . $dodis;
        $writer = new Xlsx($spreadsheet);
        $filename = $stringjudul; //save our workbook as this file name

        header('Content-Type: application/vnd.ms-excel');
        header('Content-Disposition: attachment;filename="' . $filename . '.xlsx"');
        header('Cache-Control: max-age=0');
        $writer->save('php://output');
    }

    public function vreturn_rm()
    {
        $data['lbg'] = $this->BGROUP_mod->selectall();
        $data['lplatno'] = $this->Trans_mod->selectall();
        $data['ldeliverycode'] = $this->DELV_mod->select_delv_code();
        $data['ldestoffice'] = $this->ZOffice_mod->selectAll();
        $data['lkantorpabean'] = $this->MTPB_mod->selectAll();
        $data['lwaytransport'] = $this->ZWAYTRANS_mod->selectAll();
        $data['ltujuanpengiriman'] = $this->MPurposeDLV_imod->selectAll();
        $rslocfrom = $this->MSTLOCG_mod->selectall_where_CODE_in(['ARWH1', 'QA', 'ARWH2', 'NRWH2', 'PSIEQUIP', 'AIWH1', 'ARWH9SC']);
        $rslocfrom_str = '';
        foreach ($rslocfrom as $r) {
            $rslocfrom_str .= "<option value='" . $r['MSTLOCG_ID'] . "'>" . $r['MSTLOCG_NM'] . " (" . $r['MSTLOCG_ID'] . ")</option>";
        }
        $data['llocfrom'] = $rslocfrom_str;
        $this->load->view('wms/vrm_ret_out', $data);
    }

    public function get_usage_rmwelcat_perjob($pjob)
    {
        $crnt_dt = date('Y-m-d H:i:s');
        $cuserid = $this->session->userdata('nama');
        $rspsn = $this->SPL_mod->select_z_getpsn_byjob("'" . $pjob . "'");
        $myar = [];
        $strpsn = '';
        $strdocno = '';
        $strmdlcd = '';
        foreach ($rspsn as $r) {
            $strpsn .= "'" . trim($r['PPSN1_PSNNO']) . "',";
            $strdocno = trim($r['PPSN1_DOCNO']);
            $strmdlcd = $r['PPSN1_MDLCD'];
        }
        $strpsn = substr($strpsn, 0, strlen($strpsn) - 1);
        if (trim($strpsn) != '') {
            $rsMSPP = $this->MSPP_mod->select_byvar(['MSPP_MDLCD' => $strmdlcd]);
            $rspsnjob_req = $this->SPL_mod->select_psnjob_req($strdocno, $pjob);
            if (count($rspsnjob_req) == 0) {
                $myar[] = ['cd' => 0, 'msg' => 'pis3 null (welcat)'];
                return ('{"status": ' . json_encode($myar) . '}');
            }
            foreach ($rspsnjob_req as &$n) {
                if (!array_key_exists("SUPQTY", $n)) {
                    $n["SUPQTY"] = 0;
                }
            }
            unset($n);

            $rspsn_sup = $this->SPLSCN_mod->select_scannedwelcat_bypsn($strpsn);
            $rspsn_req_d = [];
            if (count($rspsn_sup) == 0) {
                $myar[] = ['cd' => 1, 'msg' => 'scanned kitting null ' . $pjob];
                return ('{"status": ' . json_encode($myar) . '}');
            }

            #MAIN CALCULATION
            foreach ($rspsnjob_req as &$n) {
                $processs = true;
                while ($processs) {
                    $isready = false;
                    foreach ($rspsn_sup as $x) {
                        if ((trim($n['PIS3_LINENO']) == trim($x['SPLSCN_LINE'])) && (trim($n['PIS3_FR']) == trim($x['SPLSCN_FEDR']))
                            && (trim($n['PIS3_MC']) == trim($x['PPSN2_MC'])) && (trim($n['PIS3_MCZ']) == trim($x['SPLSCN_ORDERNO']))
                            && (trim($n['PIS3_PROCD']) == trim($x['PPSN2_PROCD'])) && (trim($n['PIS3_MPART']) == trim($x['SPLSCN_ITMCD'])) && (trim($x['PPSN2_MSFLG']) == 'M')
                        ) {
                            if ($x['SPLSCN_QTY'] > 0) {
                                $isready = true;
                                break;
                            }
                        }
                    }
                    if ($isready) {
                        foreach ($rspsn_sup as &$x) {
                            if ((trim($n['PIS3_LINENO']) == trim($x['SPLSCN_LINE'])) && (trim($n['PIS3_FR']) == trim($x['SPLSCN_FEDR'])) && (trim($n['PIS3_MC']) == trim($x['PPSN2_MC']))
                                && (trim($n['PIS3_MCZ']) == trim($x['SPLSCN_ORDERNO'])) && (trim($n['PIS3_PROCD']) == trim($x['PPSN2_PROCD'])) && (trim($n['PIS3_MPART']) == trim($x['SPLSCN_ITMCD']))
                                && (trim($x['PPSN2_MSFLG']) == 'M')
                            ) {
                                $process2 = true;
                                while ($process2) {
                                    if (($n['PIS3_REQQTSUM'] > $n["SUPQTY"])) { //sinii
                                        if ($x['SPLSCN_QTY'] > 0) {
                                            $n["SUPQTY"] += 1;
                                            $x['SPLSCN_QTY']--;
                                            $qtper = $n['MYPER'][0] == "." ? "0" . $n['MYPER'] : $n['MYPER'];
                                            $islotfound = false;
                                            foreach ($rspsn_req_d as &$u) {
                                                if (($u["SERD_JOB"] == trim($n["PIS3_WONO"])) && ($u["SERD_ITMCD"] == trim($x["SPLSCN_ITMCD"]))
                                                    && ($u['SERD_MC'] == trim($x['PPSN2_MC'])) && ($u['SERD_MCZ'] == trim($x['SPLSCN_ORDERNO'])) && ($u['SERD_PROCD'] == trim($x['PPSN2_PROCD']))
                                                    && ($u['SERD_CAT'] == trim($x['SPLSCN_CAT'])) && ($u['SERD_MSFLG'] == trim($x['PPSN2_MSFLG']))
                                                    && ($u['SERD_FR'] == trim($n['PIS3_FR'])) && ($u['SERD_QTPER'] == $qtper)
                                                    && ($u['SERD_REMARK'] == 'fmain_w_mcz')
                                                ) {
                                                    $u["SERD_QTY"] += 1;
                                                    $islotfound = true;
                                                    break;
                                                }
                                            }
                                            unset($u);

                                            if (!$islotfound) {
                                                $rspsn_req_d[] = [
                                                    "SERD_JOB" => trim($n["PIS3_WONO"]), "SERD_QTPER" => $qtper, "SERD_ITMCD" => trim($x['SPLSCN_ITMCD']), "SERD_QTY" => 1,
                                                    "SERD_PSNNO" => strtoupper(trim($x['SPLSCN_DOC'])), "SERD_LINENO" => trim($n['PIS3_LINENO']),
                                                    "SERD_CAT" => trim($x['SPLSCN_CAT']), "SERD_FR" => trim($n['PIS3_FR']), "SERD_QTYREQ" => intval($n['PIS3_REQQTSUM']),
                                                    "SERD_MC" => trim($n['PIS3_MC']), "SERD_MCZ" => trim($x['SPLSCN_ORDERNO']), "SERD_PROCD" => trim($x['PPSN2_PROCD']),
                                                    "SERD_MSFLG" => trim($x['PPSN2_MSFLG']), "SERD_USRID" => $cuserid, "SERD_LUPDT" => $crnt_dt,
                                                    "SERD_REMARK" => "fmain_w_mcz",
                                                ];
                                            }
                                        } else {
                                            $process2 = false;
                                        }
                                    } else {
                                        $process2 = false;
                                        $processs = false;
                                    }
                                }
                            } else {
                                $processs = false;
                            }
                        }
                        unset($x);
                    } else {
                        $processs = false;
                    }
                }
            }
            unset($n);

            #SUB CALCULATION
            foreach ($rspsnjob_req as &$n) {
                $processs = true;
                while ($processs) {
                    $isready = false;
                    foreach ($rspsn_sup as $x) {
                        #CHECK MASTER SUB PART
                        $issubtitue = false;
                        foreach ($rsMSPP as $ms) {
                            if ($ms['MSPP_BOMPN'] == $n['PIS3_MPART'] && $ms['MSPP_SUBPN'] == $x['SPLSCN_ITMCD']) {
                                $issubtitue = true;
                                break;
                            }
                        }
                        #END CHECK
                        if ((trim($n['PIS3_LINENO']) == trim($x['SPLSCN_LINE'])) && (trim($n['PIS3_FR']) == trim($x['SPLSCN_FEDR']))
                            && (trim($n['PIS3_MC']) == trim($x['PPSN2_MC'])) && (trim($n['PIS3_MCZ']) == trim($x['SPLSCN_ORDERNO']))
                            && (trim($n['PIS3_PROCD']) == trim($x['PPSN2_PROCD'])) && (trim($x['PPSN2_MSFLG']) == 'S')
                            && $issubtitue
                        ) {
                            if ($x['SPLSCN_QTY'] > 0) {
                                $isready = true;
                                break;
                            }
                        }
                    }
                    if ($isready) {
                        foreach ($rspsn_sup as &$x) {
                            #CHECK MASTER SUB PART
                            $issubtitue = false;
                            foreach ($rsMSPP as $ms) {
                                if ($ms['MSPP_BOMPN'] == $n['PIS3_MPART'] && $ms['MSPP_SUBPN'] == $x['SPLSCN_ITMCD']) {
                                    $issubtitue = true;
                                    break;
                                }
                            }
                            #END CHECK
                            if ((trim($n['PIS3_LINENO']) == trim($x['SPLSCN_LINE'])) && (trim($n['PIS3_FR']) == trim($x['SPLSCN_FEDR'])) && (trim($n['PIS3_MC']) == trim($x['PPSN2_MC']))
                                && (trim($n['PIS3_MCZ']) == trim($x['SPLSCN_ORDERNO'])) && (trim($n['PIS3_PROCD']) == trim($x['PPSN2_PROCD']))
                                && (trim($x['PPSN2_MSFLG']) == 'S') && $issubtitue
                            ) {
                                $process2 = true;
                                while ($process2) {
                                    if (($n['PIS3_REQQTSUM'] > $n["SUPQTY"])) { //sinii
                                        if ($x['SPLSCN_QTY'] > 0) {
                                            $n["SUPQTY"] += 1;
                                            $x['SPLSCN_QTY']--;
                                            $qtper = substr($n['MYPER'], 0, 1) == "." ? "0" . $n['MYPER'] : $n['MYPER'];
                                            $islotfound = false;
                                            foreach ($rspsn_req_d as &$u) {
                                                if (($u["SERD_JOB"] == trim($n["PIS3_WONO"])) && ($u["SERD_ITMCD"] == trim($x["SPLSCN_ITMCD"]))
                                                    && ($u['SERD_MC'] == trim($x['PPSN2_MC'])) && ($u['SERD_MCZ'] == trim($x['SPLSCN_ORDERNO'])) && ($u['SERD_PROCD'] == trim($x['PPSN2_PROCD']))
                                                    && ($u['SERD_CAT'] == trim($x['SPLSCN_CAT'])) && ($u['SERD_MSFLG'] == trim($x['PPSN2_MSFLG']))
                                                    && ($u['SERD_FR'] == trim($n['PIS3_FR']))
                                                ) {
                                                    $u["SERD_QTY"] += 1;
                                                    $islotfound = true;
                                                    break;
                                                }
                                            }
                                            unset($u);

                                            if (!$islotfound) {
                                                $rspsn_req_d[] = [
                                                    "SERD_JOB" => trim($n["PIS3_WONO"]), "SERD_QTPER" => $qtper, "SERD_ITMCD" => trim($x['SPLSCN_ITMCD']), "SERD_QTY" => 1,
                                                    "SERD_PSNNO" => strtoupper(trim($x['SPLSCN_DOC'])), "SERD_LINENO" => trim($n['PIS3_LINENO']),
                                                    "SERD_CAT" => trim($x['SPLSCN_CAT']), "SERD_FR" => trim($n['PIS3_FR']), "SERD_QTYREQ" => intval($n['PIS3_REQQTSUM']),
                                                    "SERD_MC" => trim($n['PIS3_MC']), "SERD_MCZ" => trim($x['SPLSCN_ORDERNO']), "SERD_PROCD" => trim($x['PPSN2_PROCD']),
                                                    "SERD_MSFLG" => trim($x['PPSN2_MSFLG']), "SERD_USRID" => $cuserid, "SERD_LUPDT" => $crnt_dt,
                                                    "SERD_REMARK" => "fsub_w_mcz",
                                                ];
                                            }
                                        } else {
                                            $process2 = false;
                                        }
                                    } else {
                                        $process2 = false;
                                        $processs = false;
                                    }
                                }
                            } else {
                                $processs = false;
                            }
                        }
                        unset($x);
                    } else {
                        $processs = false;
                    }
                }
            }
            unset($n);

            ##MAIN WITHOUT MCZ
            foreach ($rspsnjob_req as &$n) {
                $processs = true;
                while ($processs) {
                    $isready = false;
                    foreach ($rspsn_sup as $x) {
                        if ((trim($n['PIS3_LINENO']) == trim($x['SPLSCN_LINE'])) && (trim($n['PIS3_FR']) == trim($x['SPLSCN_FEDR']))
                            && (trim($n['PIS3_MC']) == trim($x['PPSN2_MC']))
                            && (trim($n['PIS3_PROCD']) == trim($x['PPSN2_PROCD'])) && (trim($n['PIS3_MPART']) == trim($x['SPLSCN_ITMCD'])) && (trim($x['PPSN2_MSFLG']) == 'M')
                        ) {
                            if ($x['SPLSCN_QTY'] > 0) {
                                $isready = true;
                                break;
                            }
                        }
                    }
                    if ($isready) {
                        foreach ($rspsn_sup as &$x) {
                            if ((trim($n['PIS3_LINENO']) == trim($x['SPLSCN_LINE'])) && (trim($n['PIS3_FR']) == trim($x['SPLSCN_FEDR'])) && (trim($n['PIS3_MC']) == trim($x['PPSN2_MC']))
                                && (trim($n['PIS3_PROCD']) == trim($x['PPSN2_PROCD'])) && (trim($n['PIS3_MPART']) == trim($x['SPLSCN_ITMCD']))
                                && (trim($x['PPSN2_MSFLG']) == 'M')
                            ) {
                                $process2 = true;
                                while ($process2) {
                                    if (($n['PIS3_REQQTSUM'] > $n["SUPQTY"])) { //sinii
                                        if ($x['SPLSCN_QTY'] > 0) {
                                            $n["SUPQTY"] += 1;
                                            $x['SPLSCN_QTY']--;
                                            $qtper = $n['MYPER'][0] == "." ? "0" . $n['MYPER'] : $n['MYPER'];
                                            $islotfound = false;
                                            foreach ($rspsn_req_d as &$u) {
                                                if (($u["SERD_JOB"] == trim($n["PIS3_WONO"])) && ($u["SERD_ITMCD"] == trim($x["SPLSCN_ITMCD"]))
                                                    && ($u['SERD_MC'] == trim($x['PPSN2_MC'])) && ($u['SERD_MCZ'] == trim($x['SPLSCN_ORDERNO'])) && ($u['SERD_PROCD'] == trim($x['PPSN2_PROCD']))
                                                    && ($u['SERD_CAT'] == trim($x['SPLSCN_CAT'])) && ($u['SERD_MSFLG'] == trim($x['PPSN2_MSFLG']))
                                                    && ($u['SERD_FR'] == trim($n['PIS3_FR']))
                                                ) {
                                                    $u["SERD_QTY"] += 1;
                                                    $islotfound = true;
                                                    break;
                                                }
                                            }
                                            unset($u);

                                            if (!$islotfound) {
                                                $rspsn_req_d[] = [
                                                    "SERD_JOB" => trim($n["PIS3_WONO"]), "SERD_QTPER" => $qtper, "SERD_ITMCD" => trim($x['SPLSCN_ITMCD']), "SERD_QTY" => 1,
                                                    "SERD_PSNNO" => strtoupper(trim($x['SPLSCN_DOC'])), "SERD_LINENO" => trim($n['PIS3_LINENO']),
                                                    "SERD_CAT" => trim($x['SPLSCN_CAT']), "SERD_FR" => trim($n['PIS3_FR']), "SERD_QTYREQ" => intval($n['PIS3_REQQTSUM']),
                                                    "SERD_MC" => trim($n['PIS3_MC']), "SERD_MCZ" => trim($x['SPLSCN_ORDERNO']), "SERD_PROCD" => trim($x['PPSN2_PROCD']),
                                                    "SERD_MSFLG" => trim($x['PPSN2_MSFLG']), "SERD_USRID" => $cuserid, "SERD_LUPDT" => $crnt_dt,
                                                    "SERD_REMARK" => "fmain_w/o_mcz",
                                                ];
                                            }
                                        } else {
                                            $process2 = false;
                                        }
                                    } else {
                                        $process2 = false;
                                        $processs = false;
                                    }
                                }
                            } else {
                                $processs = false;
                            }
                        }
                        unset($x);
                    } else {
                        $processs = false;
                    }
                }
            }
            unset($n);
            #n MAIN CALCULATION

            ##SUB WITHOUT MCZ
            foreach ($rspsnjob_req as &$n) {
                $processs = true;
                while ($processs) {
                    $isready = false;
                    foreach ($rspsn_sup as $x) {
                        #CHECK MASTER SUB PART
                        $issubtitue = false;
                        foreach ($rsMSPP as $ms) {
                            if ($ms['MSPP_BOMPN'] == $n['PIS3_MPART'] && $ms['MSPP_SUBPN'] == $x['SPLSCN_ITMCD']) {
                                $issubtitue = true;
                                break;
                            }
                        }
                        #END CHECK
                        if ((trim($n['PIS3_LINENO']) == trim($x['SPLSCN_LINE'])) && (trim($n['PIS3_FR']) == trim($x['SPLSCN_FEDR']))
                            && (trim($n['PIS3_MC']) == trim($x['PPSN2_MC']))
                            && (trim($n['PIS3_PROCD']) == trim($x['PPSN2_PROCD'])) && (trim($x['PPSN2_MSFLG']) == 'S')
                            && $issubtitue
                        ) {
                            if ($x['SPLSCN_QTY'] > 0) {
                                $isready = true;
                                break;
                            }
                        }
                    }
                    if ($isready) {
                        foreach ($rspsn_sup as &$x) {
                            #CHECK MASTER SUB PART
                            $issubtitue = false;
                            foreach ($rsMSPP as $ms) {
                                if ($ms['MSPP_BOMPN'] == $n['PIS3_MPART'] && $ms['MSPP_SUBPN'] == $x['SPLSCN_ITMCD']) {
                                    $issubtitue = true;
                                    break;
                                }
                            }
                            #END CHECK
                            if ((trim($n['PIS3_LINENO']) == trim($x['SPLSCN_LINE'])) && (trim($n['PIS3_FR']) == trim($x['SPLSCN_FEDR'])) && (trim($n['PIS3_MC']) == trim($x['PPSN2_MC']))
                                && (trim($n['PIS3_PROCD']) == trim($x['PPSN2_PROCD']))
                                && (trim($x['PPSN2_MSFLG']) == 'S') && $issubtitue
                            ) {
                                $process2 = true;
                                while ($process2) {
                                    if (($n['PIS3_REQQTSUM'] > $n["SUPQTY"])) { //sinii
                                        if ($x['SPLSCN_QTY'] > 0) {
                                            $n["SUPQTY"] += 1;
                                            $x['SPLSCN_QTY']--;
                                            $qtper = substr($n['MYPER'], 0, 1) == "." ? "0" . $n['MYPER'] : $n['MYPER'];
                                            $islotfound = false;
                                            foreach ($rspsn_req_d as &$u) {
                                                if (($u["SERD_JOB"] == trim($n["PIS3_WONO"])) && ($u["SERD_ITMCD"] == trim($x["SPLSCN_ITMCD"]))
                                                    && ($u['SERD_MC'] == trim($x['PPSN2_MC'])) && ($u['SERD_MCZ'] == trim($x['SPLSCN_ORDERNO'])) && ($u['SERD_PROCD'] == trim($x['PPSN2_PROCD']))
                                                    && ($u['SERD_CAT'] == trim($x['SPLSCN_CAT'])) && ($u['SERD_MSFLG'] == trim($x['PPSN2_MSFLG']))
                                                    && ($u['SERD_FR'] == trim($n['PIS3_FR']))
                                                ) {
                                                    $u["SERD_QTY"] += 1;
                                                    $islotfound = true;
                                                    break;
                                                }
                                            }
                                            unset($u);

                                            if (!$islotfound) {
                                                $rspsn_req_d[] = [
                                                    "SERD_JOB" => trim($n["PIS3_WONO"]), "SERD_QTPER" => $qtper, "SERD_ITMCD" => trim($x['SPLSCN_ITMCD']), "SERD_QTY" => 1,
                                                    "SERD_PSNNO" => strtoupper(trim($x['SPLSCN_DOC'])), "SERD_LINENO" => trim($n['PIS3_LINENO']),
                                                    "SERD_CAT" => trim($x['SPLSCN_CAT']), "SERD_FR" => trim($n['PIS3_FR']), "SERD_QTYREQ" => intval($n['PIS3_REQQTSUM']),
                                                    "SERD_MC" => trim($n['PIS3_MC']), "SERD_MCZ" => trim($x['SPLSCN_ORDERNO']), "SERD_PROCD" => trim($x['PPSN2_PROCD']),
                                                    "SERD_MSFLG" => trim($x['PPSN2_MSFLG']), "SERD_USRID" => $cuserid, "SERD_LUPDT" => $crnt_dt,
                                                    "SERD_REMARK" => "fsub_w/o_mcz",
                                                ];
                                            }
                                        } else {
                                            $process2 = false;
                                        }
                                    } else {
                                        $process2 = false;
                                        $processs = false;
                                    }
                                }
                            } else {
                                $processs = false;
                            }
                        }
                        unset($x);
                    } else {
                        $processs = false;
                    }
                }
            }
            unset($n);
            #n SUB CALCULATION

            #COMMON CALCULATION
            foreach ($rspsnjob_req as &$n) {
                $processs = true;
                while ($processs) {
                    $isready = false;
                    foreach ($rspsn_sup as $x) {
                        if ((trim($n['PIS3_LINENO']) == trim($x['SPLSCN_LINE'])) && (trim($n['PIS3_FR']) == trim($x['SPLSCN_FEDR']))
                            && (trim($n['PIS3_MC']) == trim($x['PPSN2_MC'])) && (trim($n['PIS3_MCZ']) == trim($x['SPLSCN_ORDERNO']))
                            && (trim($n['PIS3_PROCD']) == trim($x['PPSN2_PROCD']))
                        ) {
                            if ($x['SPLSCN_QTY'] > 0) {
                                $isready = true;
                                break;
                            }
                        }
                    }
                    if ($isready) {
                        foreach ($rspsn_sup as &$x) {
                            if ((trim($n['PIS3_LINENO']) == trim($x['SPLSCN_LINE'])) && (trim($n['PIS3_FR']) == trim($x['SPLSCN_FEDR'])) && (trim($n['PIS3_MC']) == trim($x['PPSN2_MC']))
                                && (trim($n['PIS3_MCZ']) == trim($x['SPLSCN_ORDERNO'])) && (trim($n['PIS3_PROCD']) == trim($x['PPSN2_PROCD']))
                            ) {
                                $process2 = true;
                                while ($process2) {
                                    if (($n['PIS3_REQQTSUM'] > $n["SUPQTY"])) { //sinii
                                        if ($x['SPLSCN_QTY'] > 0) {
                                            $n["SUPQTY"] += 1;
                                            $x['SPLSCN_QTY']--;
                                            $qtper = substr($n['MYPER'], 0, 1) == "." ? "0" . $n['MYPER'] : $n['MYPER'];
                                            $islotfound = false;
                                            foreach ($rspsn_req_d as &$u) {
                                                if (($u["SERD_JOB"] == trim($n["PIS3_WONO"])) && ($u["SERD_ITMCD"] == trim($x["SPLSCN_ITMCD"]))
                                                    && ($u['SERD_MC'] == trim($x['PPSN2_MC'])) && ($u['SERD_MCZ'] == trim($x['SPLSCN_ORDERNO'])) && ($u['SERD_PROCD'] == trim($x['PPSN2_PROCD']))
                                                    && ($u['SERD_CAT'] == trim($x['SPLSCN_CAT'])) && ($u['SERD_MSFLG'] == trim($x['PPSN2_MSFLG']))
                                                    && ($u['SERD_FR'] == trim($n['PIS3_FR']))
                                                ) {
                                                    $u["SERD_QTY"] += 1;
                                                    $islotfound = true;
                                                    break;
                                                }
                                            }
                                            unset($u);

                                            if (!$islotfound) {
                                                $rspsn_req_d[] = [
                                                    "SERD_JOB" => trim($n["PIS3_WONO"]), "SERD_QTPER" => $qtper, "SERD_ITMCD" => trim($x['SPLSCN_ITMCD']), "SERD_QTY" => 1,
                                                    "SERD_PSNNO" => strtoupper(trim($x['SPLSCN_DOC'])), "SERD_LINENO" => trim($n['PIS3_LINENO']),
                                                    "SERD_CAT" => trim($x['SPLSCN_CAT']), "SERD_FR" => trim($n['PIS3_FR']), "SERD_QTYREQ" => intval($n['PIS3_REQQTSUM']),
                                                    "SERD_MC" => trim($n['PIS3_MC']), "SERD_MCZ" => trim($x['SPLSCN_ORDERNO']), "SERD_PROCD" => trim($x['PPSN2_PROCD']),
                                                    "SERD_MSFLG" => trim($x['PPSN2_MSFLG']), "SERD_USRID" => $cuserid, "SERD_LUPDT" => $crnt_dt,
                                                    "SERD_REMARK" => "fcommon",
                                                ];
                                            }
                                        } else {
                                            $process2 = false;
                                        }
                                    } else {
                                        $process2 = false;
                                        $processs = false;
                                    }
                                }
                            } else {
                                $processs = false;
                            }
                        }
                        unset($x);
                    } else {
                        $processs = false;
                    }
                }
            }
            unset($n);
            #n COMMON CALCULATION

            if (count($rspsn_req_d) > 0) {
                $this->SERD_mod->insertb($rspsn_req_d);
                $myar[] = ['cd' => 1, 'msg' => 'ok inserted (welcat)'];
                return ('{"status" : ' . json_encode($myar) . ',"data": ' . json_encode($rspsn_req_d) . '}');
            } else {
                $myar[] = ['cd' => 0, 'msg' => 'calculated result is 0 (welcat) sinii'];
                return ('{"status" : ' . json_encode($myar) . '}');
            }
        } else {
            $myar[] = ['cd' => 0, 'msg' => 'Data kurang lengkap (welcat)'];
            return ('{"status" : ' . json_encode($myar) . '}');
        }
    }

    public function rm_extract()
    {
        $rs = $this->SERD_mod->select_uncalculated();
        ini_set('max_execution_time', '-1');
        foreach ($rs as $r) {
            if ($this->SERD_mod->check_Primary(['SERD_JOB' => $r['SER_DOC']]) == 0) {
                $this->get_usage_rmwelcat_perjob($r['SER_DOC']);
            }
        }
        die('finish');
    }

    public function calculate_raw_material()
    {
        header('Content-Type: application/json');
        $pser = $this->input->get('inunique');
        $pserqty = $this->input->get('inunique_qty');
        $pjob = $this->input->get('inunique_job');
        $Calc_lib = new RMCalculator();
        $rs = $Calc_lib->calculate_raw_material($pser, $pserqty, $pjob);
        die($rs);
    }

    public function calculate_raw_material_welcat()
    {
        header('Content-Type: application/json');
        $pser = $this->input->get('inunique');
        $pserqty = $this->input->get('inunique_qty');
        $pjob = $this->input->get('inunique_job');
        $Calc_lib = new RMCalculator();

        $myar = [];
        $rsusage = [];
        $showed_cols = ['SERD2_PSNNO', 'SERD2_LINENO', 'SERD2_PROCD', 'SERD2_CAT', 'SERD2_FR', 'SERD2_ITMCD', 'SERD2_QTY', 'SERD2_FGQTY', 'RTRIM(MITM_ITMD1) MITM_ITMD1', 'RTRIM(MITM_SPTNO) MITM_SPTNO', 'SERD2_QTPER MYPER', 'SERD2_MC', 'SERD2_MCZ'];
        if (count($this->SERC_mod->select_combined_jobs_definition_byreff($pser)) > 1) { #
            $comb_cols = ['SERC_COMID', 'SERC_COMJOB', 'SERC_COMQTY'];
            $rscomb_d = $this->SERC_mod->select_cols_where_id($comb_cols, $pser);
            $serlist = "";
            foreach ($rscomb_d as $n) {
                $serlist .= "'" . $n['SERC_COMID'] . "',";
            }
            $serlist = substr($serlist, 0, strlen($serlist) - 1);
            $rsusage = $this->SERD_mod->select_calculatedlabel_where_in($showed_cols, $serlist); //check child reffno rm
            if (count($rsusage) == 0) {
                #check usage per JOB
                $ttlcalculated = 0;
                foreach ($rscomb_d as $n) {
                    if ($this->SERD_mod->check_Primary(['SERD_JOB' => $n['SERC_COMJOB']]) == 0) {
                        $res0 = $this->get_usage_rmwelcat_perjob($n['SERC_COMJOB']);
                        $res = json_decode($res0);
                        if ($res->status[0]->cd != 0) {
                            $res2 = json_decode($Calc_lib->get_usage_rm_perjob_peruniq($n['SERC_COMID'], $n['SERC_COMQTY'], $n['SERC_COMJOB']));
                            if ($res2->status[0]->cd != 0) {
                                $ttlcalculated++;
                            }
                        }
                    } else {
                        $res2 = json_decode($Calc_lib->get_usage_rm_perjob_peruniq($n['SERC_COMID'], $n['SERC_COMQTY'], $n['SERC_COMJOB']));
                        if ($res2->status[0]->cd != 0) {
                            $ttlcalculated++;
                        }
                    }
                }
                if ($ttlcalculated > 0) {
                    $myar[] = ['cd' => 0, 'msg' => 'combined jobs are recalculated ok'];
                } else {
                    $myar[] = ['cd' => 0, 'msg' => 'calculating is failed. different jobs are combined'];
                }
            } else {
                $myar[] = ['cd' => 1, 'msg' => 'combined job list found'];
            }
        } else {
            if ($this->SERD_mod->check_Primary(['SERD_JOB' => $pjob]) == 0) {
                $res0 = $this->get_usage_rmwelcat_perjob($pjob);
                $res = json_decode($res0);
                if ($res->status[0]->cd != 0) {
                    $rscalculated = $this->SERD_mod->select_calculatedlabel_where($showed_cols, ['SERD2_SER' => $pser]);
                    if (count($rscalculated) > 0) {
                        $issame = false;
                        foreach ($rscalculated as $r) {
                            if ($pser != $r['SERD2_FGQTY']) {
                                $issame = true;
                                break;
                            }
                        }
                        if (!$issame) {
                            $retdel = $this->SERD_mod->deletebyID_label(['SERD2_SER' => $pser]);
                            $res2 = json_decode($Calc_lib->get_usage_rm_perjob_peruniq($pser, $pserqty, $pjob));
                            if ($res2->status[0]->cd != 0) {
                                $myar[] = ['cd' => 1, 'msg' => 'recalculated ok'];
                                $rsusage = $this->SERD_mod->select_calculatedlabel_where($showed_cols, ['SERD2_SER' => $pser]);
                            } else {
                                $myar[] = ['cd' => 0, 'msg' => 'recalculating is failed'];
                            }
                        } else {
                            $myar[] = ['cd' => 1, 'msg' => 'just get'];
                            $rsusage = $rscalculated;
                        }
                    } else { //if not yet calculated beforehand
                        $res2 = json_decode($Calc_lib->get_usage_rm_perjob_peruniq($pser, $pserqty, $pjob));
                        if ($res2->status[0]->cd != 0) {
                            $myar[] = ['cd' => 1, 'msg' => 'recalculated ok'];
                            $rsusage = $this->SERD_mod->select_calculatedlabel_where($showed_cols, ['SERD2_SER' => $pser]);
                        } else {
                            $myar[] = ['cd' => 0, 'msg' => 'calculating is failed (' . $res->status[0]->msg . ')'];
                        }
                    }
                } else { //if not yet calculated per job
                    $myar[] = ['cd' => 0, 'msg' => 'calculating per job is failed (' . $res->status[0]->msg . ')'];
                }
            } else {
                $rscalculated = $this->SERD_mod->select_calculatedlabel_where($showed_cols, ['SERD2_SER' => $pser]);
                if (count($rscalculated) > 0) { // if already calculated beforehand
                    $issame = false;
                    foreach ($rscalculated as $r) {
                        if ($pser != $r['SERD2_FGQTY']) {
                            $issame = true;
                            break;
                        }
                    }
                    if (!$issame) {
                        $retdel = $this->SERD_mod->deletebyID_label(['SERD2_SER' => $pser]);
                        $res2 = json_decode($Calc_lib->get_usage_rm_perjob_peruniq($pser, $pserqty, $pjob));
                        if ($res2->status[0]->cd != 0) {
                            $myar[] = ['cd' => 1, 'msg' => 'recalculated, ok.'];
                            $rsusage = $this->SERD_mod->select_calculatedlabel_where($showed_cols, ['SERD2_SER' => $pser]);
                        } else {
                            $myar[] = ['cd' => 0, 'msg' => 'recalculating is failed.'];
                        }
                    } else {
                        $myar[] = ['cd' => 1, 'msg' => 'just get.'];
                        $rsusage = $rscalculated;
                    }
                } else { //if not yet calculated beforehand
                    $res2 = json_decode($Calc_lib->get_usage_rm_perjob_peruniq($pser, $pserqty, $pjob));
                    if ($res2->status[0]->cd != 0) {
                        $myar[] = ['cd' => 1, 'msg' => 'recalculated ok.'];
                        $rsusage = $this->SERD_mod->select_calculatedlabel_where($showed_cols, ['SERD2_SER' => $pser]);
                    } else {
                        $myar[] = ['cd' => 0, 'msg' => 'calculating is failed.'];
                    }
                }
            }
        }

        die('{"status": ' . json_encode($myar) . ', "data": ' . json_encode($rsusage) . '}');
    }

    public function calculate_raw_material_resume()
    {
        header('Content-Type: application/json');
        ini_set('max_execution_time', '-1');
        $pser = $this->input->post('inunique');
        $pserqty = $this->input->post('inunique_qty');
        $pjob = $this->input->post('inunique_job');
        $Calc_lib = new RMCalculator();
        $rs = $Calc_lib->calculate_raw_material_resume($pser, $pserqty, $pjob);
        die($rs);
    }

    public function calculate_per_uniq()
    {
        $pser = $this->input->post('inunique');
        $pserqty = $this->input->post('inunique_qty');
        $pjob = $this->input->post('inunique_job');
        $Calc_lib = new RMCalculator();
        $rs = $Calc_lib->get_usage_rm_perjob_peruniq($pser, $pserqty, $pjob);
        die($rs);
    }

    public function get_newdono()
    {
        header('Content-Type: application/json');
        $cip = gethostbyaddr($_SERVER['REMOTE_ADDR']);
        $cuserid = $this->session->userdata('nama');
        $myar = [];
        $rs = [];
        if ($this->PTTRN_mod->select_total_rows() > 0) {
            $searchpar = ['PTTRN_USRID' => $cuserid, 'PTTRN_ID' => $cip];
            if ($this->PTTRN_mod->check_Primary($searchpar) > 0) {
                $rs = $this->PTTRN_mod->select_all_where($searchpar);
                $myar[] = ['cd' => 1, 'msg' => 'go ahead'];
            } else {
            }
        } else {
        }
        die('{"status" : ' . json_encode($myar) . ', "data" : ' . json_encode($rs) . '}');
    }

    public function searchcustomer_si()
    {
        header('Content-Type: application/json');
        $cbg = $this->input->get('cbg');
        $ccolumn = $this->input->get('csrchby');
        $csearch = $this->input->get('cid');
        $myar = [];
        $rs = [];
        switch ($ccolumn) {
            case 'nm':
                $rs = $this->SI_mod->select_customer_like(['SI_BSGRP' => $cbg, 'MCUS_CUSNM' => $csearch]);
                break;
            case 'cd':
                $rs = $this->SI_mod->select_customer_like(['SI_BSGRP' => $cbg, 'MCUS_CUSCD' => $csearch]);
                break;
            case 'ab':
                $rs = $this->SI_mod->select_customer_like(['SI_BSGRP' => $cbg, 'MCUS_ABBRV' => $csearch]);
                break;
            case 'ad':
                $rs = $this->SI_mod->select_customer_like(['SI_BSGRP' => $cbg, 'MCUS_ADDR1' => $csearch]);
                break;
        }
        if (count($rs) > 0) {
            $myar[] = ['cd' => 1, 'msg' => 'go ahead'];
        } else {
            $myar[] = ['cd' => 0, 'msg' => 'not found'];
        }
        die('{"status": ' . json_encode($myar) . ', "data": ' . json_encode($rs) . '}');
    }

    public function activation()
    {
        header('Content-Type: application/json');
        $rs = $this->AKTIVASIAPLIKASI_imod->selectAll();
        echo json_encode($rs);
    }

    public function create()
    {
        $data['lwaytransport'] = $this->ZWAYTRANS_mod->selectAll();
        $data['lplatno'] = $this->Trans_mod->selectall();
        $data['ldestoffice'] = $this->ZOffice_mod->selectAll();
        $data['lbg'] = $this->XBGROUP_mod->selectall();
        $data['ldeliverycode'] = $this->DELV_mod->select_delv_code_where(['MDEL_3RDP_AS' => 1]);
        $data['lkantorpabean'] = $this->MTPB_mod->selectAll();
        $data['ltujuanpengiriman'] = $this->MPurposeDLV_imod->selectAll();
        $this->load->view('wms/vdelv', $data);
    }

    public function print_pickingdoc()
    {
        $Year = date('Y');
        $Month = intval(date('m'));
        $pser = '';
        if (isset($_COOKIE["PRINTLABEL_SI"])) {
            $pser = $_COOKIE["PRINTLABEL_SI"];
        } else {
            exit('nothing to be printed');
        }
        $rspickingres = $this->DELV_mod->selectAllg_by($pser);
        $cpono = '';
        $rs = $this->SISO_mod->selectSObyYear('PSI1PPZIEP', 'SME007U', $Year, $Month);
        if (count($rs) > 0) {
            foreach ($rs as $r) {
                $cpono = trim($r['SSO2_CPONO']);
            }
        } else {
            if (($Month - 1) == 0) {
                $Month = 12;
                $rs = $this->SISO_mod->selectSObyYear('PSI1PPZIEP', 'SME007U', ($Year - 1), $Month);
                foreach ($rs as $r) {
                    $cpono = trim($r['SSO2_CPONO']);
                }
            } else {
                $Month -= 1;
                $rs = $this->SISO_mod->selectSObyYear('PSI1PPZIEP', 'SME007U', ($Year), $Month);
                foreach ($rs as $r) {
                    $cpono = trim($r['SSO2_CPONO']);
                }
            }
        }
        $pdf = new PDF_Code39e128('P', 'mm', 'A4');
        $pdf->AliasNbPages();

        $no = 1;
        $cury = 24;
        $h_content = 16;

        $a_plant = [];
        $a_sidoc = [];
        $custDo = '';
        foreach ($rspickingres as $r) {
            if (!in_array(trim($r['SI_OTHRMRK']), $a_plant)) {
                $a_plant[] = trim($r['SI_OTHRMRK']);
                $custDo = $r['DLV_CUSTDO'];
            }
            if (!in_array($r['SI_DOC'], $a_sidoc)) {
                $a_sidoc[] = $r['SI_DOC'];
            }
        }
        $s_sidoc = '';
        foreach ($a_sidoc as $r) {
            $s_sidoc .= $r . "|";
        }
        $s_sidoc = substr($s_sidoc, 0, strlen($s_sidoc) - 1);

        $ttlplant = count($a_plant);

        for ($n = 0; $n < $ttlplant; $n++) {
            $myplant = '';
            switch ($a_plant[$n]) {
                case 'D116':
                    $myplant = 'EPSON 1/2';
                    break;
                case 'D120':
                    $myplant = 'EPSON 3';
                    break;
                case 'D114':
                    $myplant = 'EPSON 4';
                    break;
                default:
                    $myplant = '';
            }
            $pdf->AddPage();
            $hgt_p = $pdf->GetPageHeight();
            $wid_p = $pdf->GetPageWidth();

            $pdf->SetAutoPageBreak(true, 1);
            $pdf->SetMargins(0, 0);
            $pdf->SetFont('Arial', '', 8);
            $pdf->SetXY(180, 6);
            $pdf->Cell(15, 4, 'Page ' . $pdf->PageNo() . ' of {nb}', 0, 0, 'R');
            $pdf->SetFont('Arial', 'B', 11);

            $widtex = 50;
            $pdf->SetXY(6, 6);
            $pdf->Cell(23, 4, $myplant, 1, 0, 'C');
            $pdf->SetXY(($wid_p / 2) - ($widtex / 2), 6);
            $pdf->Cell($widtex, 4, 'WMS PICKING RESULT', 0, 0, 'C');
            $pdf->SetFont('Arial', 'B', 9);
            $pdf->SetXY(($wid_p / 2) - ($widtex / 2), 11);
            $pdf->Cell($widtex, 4, $pser . " ($custDo) ($s_sidoc)", 0, 0, 'C');
            $clebar = $pdf->GetStringWidth($pser) + 13;
            $pdf->Code128(($wid_p / 2) - ($clebar / 2), 15, $pser, $clebar, 3);
            $pdf->SetFont('Arial', '', 9);
            $pdf->SetXY(7, 20);
            $pdf->Cell(9, 4, 'No', 1, 0, 'C');
            $pdf->Cell(25, 4, 'Item Code', 1, 0, 'C');
            $pdf->Cell(20, 4, 'Quantity', 1, 0, 'C');
            $pdf->Cell(17, 4, 'Remark', 1, 0, 'C');
            $pdf->Cell(20, 4, 'QR', 1, 0, 'C');
            $pdf->SetXY(100, 20);
            $pdf->Cell(9, 4, 'No', 1, 0, 'C');
            $pdf->Cell(25, 4, 'Item Code', 1, 0, 'C');
            $pdf->Cell(20, 4, 'Quantity', 1, 0, 'C');
            $pdf->Cell(17, 4, 'Remark', 1, 0, 'C');
            $pdf->Cell(20, 4, 'QR', 1, 0, 'C');

            $no = 1;
            $cury = 24;
            $cury_r = 24;
            $left_y = 24;
            $right_y = 24;
            $h_content = 16;
            $ttlrows_perplant = 0;
            foreach ($rspickingres as $r) {
                if ($a_plant[$n] == trim($r['SI_OTHRMRK'])) {
                    $ttlrows_perplant++;
                }
            }
            $ASPFLAG = false;
            $KDFLAG = false;
            foreach ($rspickingres as $r) {
                if ($a_plant[$n] == trim($r['SI_OTHRMRK'])) {
                    if (($left_y >= ($hgt_p - $h_content)) && ($right_y >= ($hgt_p - $h_content))) {
                        $pdf->AddPage();
                        $hgt_p = $pdf->GetPageHeight();
                        $wid_p = $pdf->GetPageWidth();
                        $pdf->SetAutoPageBreak(true, 1);
                        $pdf->SetMargins(0, 0);
                        $pdf->SetFont('Arial', '', 8);
                        $pdf->SetXY(180, 6);
                        $pdf->Cell(15, 4, 'Page ' . $pdf->PageNo() . ' of {nb}', 0, 0, 'R');
                        $pdf->SetFont('Arial', 'B', 11);
                        $widtex = 50;
                        $pdf->SetXY(6, 6);
                        $pdf->Cell(23, 4, $myplant, 1, 0, 'C');
                        $pdf->SetXY(($wid_p / 2) - ($widtex / 2), 6);
                        $pdf->Cell($widtex, 4, 'WMS PICKING RESULT', 0, 0, 'C');
                        $pdf->SetFont('Arial', 'B', 9);
                        $pdf->SetXY(($wid_p / 2) - ($widtex / 2), 11);
                        $pdf->Cell($widtex, 4, $pser, 0, 0, 'C');
                        $pdf->SetFont('Arial', '', 9);
                        $pdf->SetXY(7, 20);
                        $pdf->Cell(9, 4, 'No', 1, 0, 'C');
                        $pdf->Cell(25, 4, 'Item Code', 1, 0, 'C');
                        $pdf->Cell(20, 4, 'Quantity', 1, 0, 'C');
                        $pdf->Cell(17, 4, 'Remark', 1, 0, 'C');
                        $pdf->Cell(20, 4, 'QR', 1, 0, 'C');
                        $pdf->SetXY(100, 20);
                        $pdf->Cell(9, 4, 'No', 1, 0, 'C');
                        $pdf->Cell(25, 4, 'Item Code', 1, 0, 'C');
                        $pdf->Cell(20, 4, 'Quantity', 1, 0, 'C');
                        $pdf->Cell(17, 4, 'Remark', 1, 0, 'C');
                        $pdf->Cell(20, 4, 'QR', 1, 0, 'C');

                        // $no =1;
                        $cury = 24;
                        $cury_r = 24;
                        $left_y = 24;
                        $right_y = 24;
                    }
                    if (strpos($r['SI_ITMCD'], 'ASP') !== false) {
                        $ASPFLAG = true;
                    } else {
                        $ASPFLAG = false;
                    }
                    if (strpos($r['SI_ITMCD'], 'KD') !== false) {
                        $KDFLAG = true;
                    } else {
                        $KDFLAG = false;
                    }
                    if ($no == $ttlrows_perplant) {
                        if ($ASPFLAG || $KDFLAG) {
                            $image_name = trim($r['SI_ITMCD']) . "\t\t\t\t\t" . number_format($r['TTLQTY'], 0, "", "") . "\t" . number_format($r[$ASPFLAG ? 'TTLQTY' : 'REMARK'], 0, "", "") . " BOX " . $cpono;
                            $image_name = str_replace(["KD", "ASP"], "", $image_name);
                        } else {
                            $image_name = trim($r['SI_ITMCD']) . "\t\t\t\t\t" . number_format($r['TTLQTY'], 0, "", "") . "\t" . number_format($r['REMARK'], 0, "", "") . " BOX " . $cpono . " RFID";
                        }
                        $cmd = escapeshellcmd("Python d:\Apache24\htdocs\wms\smt.py \"$image_name\" 1 ");
                    } else {
                        if ($ASPFLAG || $KDFLAG) {
                            $image_name = trim($r['SI_ITMCD']) . "\t\t\t\t\t" . number_format($r['TTLQTY'], 0, "", "") . "\t" . number_format($r[$ASPFLAG ? 'TTLQTY' : 'REMARK'], 0, "", "") . " BOX";
                            $image_name = str_replace(["KD", "ASP"], "", $image_name);
                        } else {
                            $image_name = trim($r['SI_ITMCD']) . "\t\t\t\t\t" . number_format($r['TTLQTY'], 0, "", "") . "\t" . number_format($r['REMARK'], 0, "", "") . " BOX";
                        }
                        $cmd = escapeshellcmd("Python d:\Apache24\htdocs\wms\smt.py \"$image_name\" 1 ");
                    }
                    $op = shell_exec($cmd);
                    $image_name = str_replace("/", "xxx", $image_name);
                    $image_name = str_replace(" ", "___", $image_name);
                    $image_name = str_replace("|", "lll", $image_name);
                    $image_name = str_replace("\t", "ttt", $image_name);

                    if ($left_y >= ($hgt_p - $h_content)) {
                        $pdf->SetXY(100, $cury_r);
                        $pdf->Cell(9, $h_content, $no, 1, 0, 'C');
                        $pdf->Cell(25, $h_content, trim($r['SI_ITMCD']), 1, 0, 'C');
                        $pdf->Cell(20, $h_content, number_format($r['TTLQTY']), 1, 0, 'C');
                        $pdf->Cell(17, $h_content, number_format($ASPFLAG ? $r['TTLQTY'] : $r['REMARK']) . " BOX", 1, 0, 'C');
                        $pdf->Cell(20, $h_content, '', 1, 0, 'C');
                        $pdf->Image(base_url("assets/imgs/" . $image_name . ".png"), 174, $cury_r + 1);
                        $right_y += $h_content;
                        $cury_r += $h_content;
                    } else {
                        $pdf->SetXY(7, $cury);
                        $pdf->Cell(9, $h_content, $no, 1, 0, 'C');
                        $pdf->Cell(25, $h_content, trim($r['SI_ITMCD']), 1, 0, 'C');
                        $pdf->Cell(20, $h_content, number_format($r['TTLQTY']), 1, 0, 'C');
                        $pdf->Cell(17, $h_content, number_format($ASPFLAG ? $r['TTLQTY'] : $r['REMARK']) . " BOX", 1, 0, 'C');
                        $pdf->Image(base_url("assets/imgs/" . $image_name . ".png"), 81, $cury + 1);
                        $pdf->Cell(20, $h_content, '', 1, 0, 'C');
                        $left_y += $h_content;
                        $cury += $h_content;
                    }
                    $no++;
                }
            }
        }
        $pdf->Output('I', ' FG Picking Result Doc  ' . date("d-M-Y") . '.pdf');
    }

    public function create_confirmation()
    {
        $rs = $this->DELV_mod->select_unconfirmed();
        $strtemp = '';
        foreach ($rs as $r) {
            $strtemp .= "<tr>" .
                "<td>$r->DLV_ID</td>" .
                "<td>$r->DLV_DATE</td>" .
                "<td>$r->MSTEMP_FNM</td>" .
                "<td>$r->DLV_POSTTM</td>" .
                "<td class='text-center'></td>" .
                "<td class='text-center'><button class='btn btn-sm btn-primary'>Confirm</button></td>" .
                "</tr>";
        }
        $data['ldata'] = $strtemp;
        $this->load->view('wms/vshipping_confirmation', $data);
    }

    public function removeun()
    {
        $cser = $this->input->post('inser');
        #validate Already Posted to TPB
        $ttlPostedRows = $this->DELV_mod->check_Primary(['DLV_SER' => $cser, 'DLV_POSTTM IS NOT NULL' => null]);
        if ($ttlPostedRows > 0) {
            $myar[] = ["cd" => '0', "msg" => "Could not delete because the data is already posted"];
            die(json_encode(['status' => $myar]));
        }
        #end
        $resdelv = $this->DELV_mod->deleteby_filter(["DLV_SER" => $cser]);
        $myar[] = $resdelv > 0 ? ["cd" => strval($resdelv), "msg" => "Deleted"] :
        ["cd" => '0', "msg" => "Data not found, or may be it has been deleted"];
        die('{"status":' . json_encode($myar) . '}');
    }

    public function removeun_by_txid_item()
    {
        $txid = $this->input->post('txid');
        $itemid = $this->input->post('itemid');
        #validate Already Posted to TPB
        $ttlPostedRows = $this->DELV_mod->check_Primary(['DLV_ID' => $txid, 'DLV_POSTTM IS NOT NULL' => null]);
        if ($ttlPostedRows > 0) {
            $myar[] = ["cd" => '0', "msg" => "Could not delete because the data is already posted"];
            die(json_encode(['status' => $myar]));
        }
        #end
        $rsser = $this->DELV_mod->select_for_delete(['DLV_ID' => $txid, 'SER_ITMID' => $itemid]);
        $ttlrows = count($rsser);
        $ttldeleted = 0;
        foreach ($rsser as $r) {
            $ttldeleted += $this->DELV_mod->deleteby_filter(["DLV_SER" => $r['DLV_SER']]);
        }
        if ($ttldeleted > 0) {
            $res = ["cd" => 1, "msg" => "$ttldeleted Deleted"];
        } else {
            if ($ttlrows > 0) {
                $res = ["cd" => '0', "msg" => "Data not found, or may be it has been deleted"];
            } else {
                $res = ["cd" => '0', "msg" => "Data not found, or it is already posted"];
            }
        }
        $myar[] = $res;
        die('{"status":' . json_encode($myar) . '}');
    }

    public function remove_rm()
    {
        header('Content-Type: application/json');
        $docNum = $this->input->post('docNum');
        $lineId = $this->input->post('lineId');
        $myar = [];
        if (strlen($docNum) === 0) {
            $myar[] = ['cd' => '0', 'msg' => 'document is required'];
        } else {
            if ($this->ZRPSTOCK_mod->check_Primary(['RPSTOCK_REMARK' => $docNum])) {
                $myar[] = ['cd' => '0', 'msg' => 'could not be deleted because already booked'];
            } else {
                $result = $this->DELV_mod->deleteby_filter(['DLV_ID' => $docNum, 'DLV_LINE' => $lineId]);
                $myar[] = $result ? ['cd' => '1', 'msg' => 'Deleted'] : ['cd' => '0', 'msg' => 'could not be deleted'];
            }
        }
        die('{"status":' . json_encode($myar) . '}');
    }

    public function remove_pkg()
    {
        header('Content-Type: application/json');
        $docNum = $this->input->post('docNum');
        $lineId = $this->input->post('lineId');
        $result = $this->DELV_mod->deleteby_filter_pkg(['DLV_PKG_DOC' => $docNum, 'DLV_PKG_LINE' => $lineId]);
        $myar[] = $result ? ['cd' => '1', 'msg' => 'Deleted'] : ['cd' => '0', 'msg' => 'could not be deleted'];
        die('{"status":' . json_encode($myar) . '}');
    }

    public function edit()
    {
        if ($this->session->userdata('status') != "login") {
            $myar[] = ["cd" => "00", "msg" => "Session is expired please reload page"];
            exit(json_encode($myar));
        }
        $crnt_dt = date('Y-m-d H:i:s');
        $ctxid = $this->input->post('intxid');
        $ctxcustDO = $this->input->post('incustdo');
        $ctxdt = $this->input->post('intxdt');
        $ctxinv = $this->input->post('ininv');
        $ctxdodt = $this->input->post('indodt');
        $ctxinvsmt = $this->input->post('ininvsmt');
        $ctxconsig = $this->input->post('inconsig');
        $ctxcus = $this->input->post('incus');
        $ctxtrans = $this->input->post('intrans');
        $ctxremark = $this->input->post('inremark');
        $ctxcustomsdoc = $this->input->post('incustoms_doc');
        $ctxa_ser = $this->input->post('ina_ser');
        $ctxa_qty = $this->input->post('ina_qty');

        $ctxa_so = $this->input->post('ina_so');
        $cbg = $this->input->post('inbg');

        $ctxa_sono = $this->input->post('ina_sono');
        $ctxa_soitem = $this->input->post('ina_soitem');
        $ctxa_soqty = $this->input->post('ina_soqty');
        $cconfirmation = $this->input->post('iconfirmation');
        $soCount = 0;
        log_message('error', $_SERVER['REMOTE_ADDR'] . 'DELV/edit, step0#, DO:' . $ctxid);
        if (isset($ctxa_sono)) {
            if (is_array($ctxa_sono)) {
                $soCount = count($ctxa_sono);
            }
        }
        if (strlen($ctxinv) > 2) {
            if ($this->DELV_mod->check_Primary(['DLV_ID !=' => $ctxid, 'DLV_INVNO' => $ctxinv])) {
                $datar = ["cd" => '00', "msg" => "Invoice Number is already used,"];
                $myar[] = $datar;
                die(json_encode($myar));
            }
        }
        if (strlen($ctxinvsmt) > 2) {
            if ($this->DELV_mod->check_Primary(['DLV_ID !=' => $ctxid, 'DLV_SMTINVNO' => $ctxinvsmt])) {
                $datar = ["cd" => '00', "msg" => "Invoice Number is already used,,"];
                $myar[] = $datar;
                die(json_encode($myar));
            }
        }

        if (substr($ctxid, -3) != 'RTN') {
            /*
            context : compare selected do and issue date Sales Order
            purpose : to warn user if they select the right Sales Order
             */
            if ($cbg === 'PSI1PPZIEP') {
                $monthOfDO = explode('-', $ctxdodt)[1];
                $reffnoStr = is_array($ctxa_ser) ? "'" . implode("','", $ctxa_ser) . "'" : "''";
                $rssiso = $this->SISO_mod->select_currentDiffPrice_byReffno($reffnoStr, $monthOfDO);
                if (!empty($rssiso) && $cconfirmation === 'n') {
                    $myar[] = ["cd" => '22', "msg" => "Month of Sales Order is different than Month of Delivery Order.
                                Are you sure want to continue ?"];
                    die(json_encode($myar));
                }
            }
        }

        $xa_qty = $this->input->post('xa_qty');
        $xa_so = $this->input->post('xa_so');
        $xa_soline = $this->input->post('xa_soline');
        $xa_siline = $this->input->post('xa_siline');
        $xsoCount = 0;
        if (isset($xa_so)) {
            if (is_array($xa_so)) {
                $xsoCount = count($xa_so);
            }
        }

        $myar = [];
        $ttlrow = count($ctxa_ser);
        $datas = [];

        log_message('error', $_SERVER['REMOTE_ADDR'] . 'DELV/edit, step1# start check existence DLV_sER in DLV_TBL, DO:' . $ctxid);
        for ($i = 0; $i < $ttlrow; $i++) {
            $ttlrows = $this->DELV_mod->check_Primary(["DLV_SER" => $ctxa_ser[$i]]);
            if ($ttlrows == 0) {
                $qty = $ctxa_qty[$i];
                $qty = str_replace(',', '', $qty);
                $datas[] = [
                    'DLV_ID' => $ctxid,
                    'DLV_CUSTDO' => $ctxcustDO,
                    'DLV_BCDATE' => $ctxdt,
                    'DLV_CUSTCD' => $ctxcus,
                    'DLV_BSGRP' => $cbg,
                    'DLV_CONSIGN' => $ctxconsig,
                    'DLV_INVNO' => $ctxinv,
                    'DLV_INVDT' => $ctxdt,
                    'DLV_BCTYPE' => $ctxcustomsdoc,
                    'DLV_SMTINVNO' => $ctxinvsmt,
                    'DLV_TRANS' => $ctxtrans,
                    'DLV_DOCREFF' => $ctxa_so[$i],
                    'DLV_SER' => $ctxa_ser[$i],
                    'DLV_ISPTTRND' => $this->PATTERFLG,
                    'DLV_QTY' => $qty,
                    'DLV_RMRK' => $ctxremark,
                    'DLV_CRTD' => $this->session->userdata('nama'),
                    'DLV_CRTDTM' => $crnt_dt,
                    'DLV_DATE' => $ctxdodt,
                    'DLV_ITMCD' => '',
                ];
            }
        }
        log_message('error', $_SERVER['REMOTE_ADDR'] . 'DELV/edit, step1# finish check existence DLV_sER in DLV_TBL, DO:' . $ctxid);
        $lastLineLog = $this->CSMLOG_mod->select_lastLine($ctxid, '') + 1;
        $dlvUpdated = 0;
        log_message('error', $_SERVER['REMOTE_ADDR'] . 'DELV/edit, step2# start update header in DLV_TBL, DO:' . $ctxid);
        #update docs properties
        $dlvUpdated = $this->DELV_mod->updatebyVAR(
            [
                'DLV_INVNO' => $ctxinv,
                'DLV_SMTINVNO' => $ctxinvsmt,
                'DLV_BCTYPE' => $ctxcustomsdoc,
                'DLV_CUSTDO' => $ctxcustDO,
                'DLV_BCDATE' => $ctxdt,
                'DLV_INVDT' => $ctxdt,
                'DLV_DATE' => $ctxdodt,
                'DLV_CONSIGN' => $ctxconsig,
                'DLV_RMRK' => $ctxremark,
            ],
            ['DLV_ID' => $ctxid]
        );
        log_message('error', $_SERVER['REMOTE_ADDR'] . 'DELV/edit, step2# finish update header in DLV_TBL, DO:' . $ctxid);
        if (!empty($datas)) {
            log_message('error', $_SERVER['REMOTE_ADDR'] . 'DELV/edit, step3# start insert CSMLOG, DO:' . $ctxid);
            $this->CSMLOG_mod->insert([
                'CSMLOG_DOCNO' => $ctxid,
                'CSMLOG_SUPZAJU' => '',
                'CSMLOG_SUPZNOPEN' => '',
                'CSMLOG_DESC' => 'update transaction, add new transaction',
                'CSMLOG_LINE' => $lastLineLog,
                'CSMLOG_TYPE' => 'OUT',
                'CSMLOG_CREATED_AT' => date('Y-m-d H:i:s'),
                'CSMLOG_CREATED_BY' => $this->session->userdata('nama'),
            ]);
            log_message('error', $_SERVER['REMOTE_ADDR'] . 'DELV/edit, step3# finish insert CSMLOG, DO:' . $ctxid);
            log_message('error', $_SERVER['REMOTE_ADDR'] . 'DELV/edit, step4# start insert DLV_TBL, DO:' . $ctxid);
            $cret = $this->DELV_mod->insertb($datas);
            log_message('error', $_SERVER['REMOTE_ADDR'] . 'DELV/edit, step4# finish insert DLV_TBL, DO:' . $ctxid);
            $myar[] = $cret > 0 ? ["cd" => '11', "msg" => "Saved successfully"] : ["cd" => '11', "msg" => "Could not add new Data"];
        } else {
            $myar[] = $dlvUpdated > 0 ? ["cd" => '11', "msg" => "Updated"] : ["cd" => '11', "msg" => "nothing updated"];
        }

        if ($this->DLVSO_mod->check_Primary(['DLVSO_DLVID' => $ctxid])) {
            $this->DLVSO_mod->deletebyID(['DLVSO_DLVID' => $ctxid]);
            $setSODLV = [];
            for ($i = 0; $i < $soCount; $i++) {
                $setSODLV[] = [
                    'DLVSO_DLVID' => $ctxid, 'DLVSO_ITMCD' => $ctxa_soitem[$i], 'DLVSO_CPONO' => $ctxa_sono[$i], 'DLVSO_QTY' => $ctxa_soqty[$i],
                ];
            }
            if (count($setSODLV)) {
                $this->DLVSO_mod->insertb($setSODLV);
            }
        } else {
            $setSODLV = [];
            for ($i = 0; $i < $soCount; $i++) {
                $setSODLV[] = [
                    'DLVSO_DLVID' => $ctxid, 'DLVSO_ITMCD' => $ctxa_soitem[$i], 'DLVSO_CPONO' => $ctxa_sono[$i], 'DLVSO_QTY' => $ctxa_soqty[$i],
                ];
            }
            if (count($setSODLV)) {
                $this->DLVSO_mod->insertb($setSODLV);
            }
        }

        #SO MEGA
        if ($xsoCount) {
            #DISTINCT SILINENO
            # fa = Filtered Array
            $fa_siline = [];
            foreach ($xa_siline as $i) {
                if (!in_array($i, $fa_siline)) {
                    $fa_siline[] = $i;
                }
            }
            if (count($fa_siline)) {
                $this->SISO_mod->delete_H_in($fa_siline);
            }
            $datap = [];
            for ($i = 0; $i < $xsoCount; $i++) {
                $datap[] = [
                    'SISO_HLINE' => $xa_siline[$i],
                    'SISO_CPONO' => $xa_so[$i],
                    'SISO_SOLINE' => $xa_soline[$i],
                    'SISO_FLINE' => $xa_siline[$i] . "-" . $i,
                    'SISO_LINE' => $i,
                    'SISO_QTY' => $xa_qty[$i],
                ];
            }
            $this->SISO_mod->insertb($datap);
        }
        #END

        echo json_encode($myar);

        #SAVE UNSAVED SI
        $cwh_inc = '';
        $cwh_out = '';
        $cfm_inc = '';
        $cfm_out = '';

        $rssiwh = $this->SI_mod->select_wh($ctxa_ser[0]);
        $siwh = '';
        foreach ($rssiwh as $r) {
            $siwh = $r['SI_WH'];
        }

        $dataf_txroute = ['TXROUTE_ID' => 'RECEIVING-FG-SHP', 'TXROUTE_WH' => $siwh];
        $rs_txroute = $this->TXROUTE_mod->selectbyVAR($dataf_txroute);
        foreach ($rs_txroute as $r) {
            $cwh_inc = $r->TXROUTE_WHINC;
            $cwh_out = $r->TXROUTE_WHOUT;
            $cfm_inc = $r->TXROUTE_FORM_INC;
            $cfm_out = $r->TXROUTE_FORM_OUT;
        }
        //==END
        $dataw = ['SISCN_PLLT IS NULL' => null];
        $rsunsaved = $this->SISCN_mod->selectAll_byserin($dataw, $ctxa_ser);
        $ttlrows = count($rsunsaved);
        if ($ttlrows > 0) {
            //to ith
            $strser = '';
            foreach ($rsunsaved as $r) {
                $strser .= "'$r[SISCN_SER]',";
            }
            $strser = substr($strser, 0, strlen($strser) - 1);
            $rsloc = $this->ITH_mod->selectincfgloc($strser);
            foreach ($rsloc as $r) {
                foreach ($rsunsaved as &$k) {
                    if ($r['ITH_SER'] == $k['SISCN_SER']) {
                        $k['SER_LASTLOC'] = $r['ITH_LOC'];
                        break;
                    }
                }
                unset($k);
            }
            $tr = 0; //catch retured value when saving process
            $currrtime = date('Y-m-d H:i:s');
            foreach ($rsunsaved as $r) {
                $rsstock = $this->ITH_mod->select_ser_stock($r['SISCN_SER'], $cwh_out);
                if (count($rsstock) > 0) {
                    $currrtime = date('Y-m-d H:i:s');

                    $tr += $this->ITH_mod->insert_si_scan(
                        trim($r['SER_ITMID']),
                        $cfm_out,
                        $r['SISCN_DOC'],
                        -$r['SISCN_SERQTY'],
                        $cwh_out,
                        $r['SER_LASTLOC'],
                        $r['SISCN_SER'],
                        $this->session->userdata('nama')
                    );

                    $tr += $this->ITH_mod->insert_si_scan(
                        trim($r['SER_ITMID']),
                        $cfm_inc,
                        $r['SISCN_DOC'],
                        $r['SISCN_SERQTY'],
                        $cwh_inc,
                        $r['SER_LASTLOC'],
                        $r['SISCN_SER'],
                        $this->session->userdata('nama')
                    );
                }
            }
            $mlastid = $this->SISCN_mod->lastserialid();
            $mlastid++;
            $newid = $mlastid;
            $datau = ['SISCN_PLLT' => $newid, 'SISCN_LUPDT' => $currrtime];
            $this->SISCN_mod->updatebyId_serin($datau, $dataw, $ctxa_ser);
        }
        #N SAVE UNSAVED SI
    }

    public function inv()
    {
        $respon = $this->DELV_mod->check_Primary(['DLV_ID !=' => '0321Z0364', 'DLV_INVNO' => '1019/12/21']);
        echo $respon;
    }

    public function set()
    {
        if ($this->session->userdata('status') != "login") {
            $myar[] = ["cd" => "00", "msg" => "Session is expired please reload page"];
            exit(json_encode($myar));
        }
        $crnt_dt = date('Y-m-d H:i:s');
        $ctxid = $this->input->post('intxid');
        $ctxcustDO = $this->input->post('incustdo');
        $ctxbctype = $this->input->post('incustoms_doc');
        $ctxdt = $this->input->post('intxdt');
        $ctxinv = $this->input->post('ininv');
        $ctxdodt = $this->input->post('indodt');
        $ctxinvsmt = $this->input->post('ininvsmt');
        $ctxconsig = $this->input->post('inconsig');
        $ctxcus = $this->input->post('incus');
        $ctxbg = $this->input->post('inbg');
        $ctxtrans = $this->input->post('intrans');
        $ctxremark = $this->input->post('inremark');
        $ctxa_ser = $this->input->post('ina_ser');
        $ctxa_qty = $this->input->post('ina_qty');
        $ctxa_so = $this->input->post('ina_so');

        $ctxa_sono = $this->input->post('ina_sono');
        $ctxa_soitem = $this->input->post('ina_soitem');
        $ctxa_soqty = $this->input->post('ina_soqty');
        $cconfirmation = $this->input->post('iconfirmation');
        $soCount = 0;
        if (isset($ctxa_sono)) {
            if (is_array($ctxa_sono)) {
                $soCount = count($ctxa_sono);
            }
        }
        $myar = [];
        $delcd_cust = '';
        $rsdelcd = $this->DELV_mod->selectDELCD_where(['MDEL_DELCD' => $ctxconsig]);
        foreach ($rsdelcd as $r) {
            $delcd_cust = $r['MDEL_TXCD'];
        }
        if ($delcd_cust === '') {
            $datar = ["cd" => '00', "msg" => "Please define customer code"];
            $myar[] = $datar;
            die(json_encode($myar));
        }

        $REMARKTXID = '';
        $rsremark = $this->SI_mod->select_wh_top1_byser($ctxa_ser[0]);
        if (count($rsremark)) {
            foreach ($rsremark as $r) {
                if (strpos($r['ITH_ITMCD'], 'KDES') !== false) {
                    $REMARKTXID .= "KDES";
                } else {
                    if (strpos($r['ITH_ITMCD'], 'ES') !== false) {
                        $REMARKTXID .= "ES";
                    }
                }
                if (strpos($r['ITH_ITMCD'], 'TS') !== false) {
                    $REMARKTXID .= "TS";
                }
                if (strpos($r['ITH_ITMCD'], 'WS') !== false) {
                    $REMARKTXID .= "WS";
                }
            }
            /*
            context : compare selected do and issue date Sales Order
            purpose : to warn user if they select the right Sales Order
             */
            if ($ctxbg === 'PSI1PPZIEP') {
                $monthOfDO = explode('-', $ctxdodt)[1];
                $reffnoStr = is_array($ctxa_ser) ? "'" . implode("','", $ctxa_ser) . "'" : "''";
                $rssiso = $this->SISO_mod->select_currentDiffPrice_byReffno($reffnoStr, $monthOfDO);
                if (!empty($rssiso) && $cconfirmation === 'n') {
                    $myar[] = ["cd" => '22', "msg" => "Month of Sales Order is different than Month of Delivery Order.
                                Are you sure want to continue ?"];
                    die(json_encode($myar));
                }
            }
        } else {
            $REMARKTXID = 'RTN';
            $rsmaster_ser = $this->SER_mod->selectbyVARg(['SER_ID' => $ctxa_ser[0]]);
            foreach ($rsmaster_ser as $r) {
                if (strpos($r['SER_ITMID'], 'KDES') !== false) {
                    $REMARKTXID .= "KDES";
                } else {
                    if (strpos($r['SER_ITMID'], 'ES') !== false) {
                        $REMARKTXID .= "ES";
                    }
                }

                if (strpos($r['SER_ITMID'], 'TS') !== false) {
                    $REMARKTXID .= "TS";
                }
                if (strpos($r['SER_ITMID'], 'WS') !== false) {
                    $REMARKTXID .= "WS";
                }
            }
        }

        $datacheck = ['DLV_ID' => $ctxid];
        if ($this->DELV_mod->check_Primary($datacheck) > 0) {
            $datar = ["cd" => '00', "msg" => "TX ID is already exist"];
            $myar[] = $datar;
            die(json_encode($myar));
        } else {
            if (strlen($ctxinv) > 2) {
                if ($this->DELV_mod->check_Primary(['DLV_INVNO' => $ctxinv])) {
                    $datar = ["cd" => '00', "msg" => "Invoice Number is already used"];
                    $myar[] = $datar;
                    die(json_encode($myar));
                }
            }
            if (strlen($ctxinvsmt) > 2) {
                if ($this->DELV_mod->check_Primary(['DLV_SMTINVNO' => $ctxinvsmt])) {
                    $datar = ["cd" => '00', "msg" => "Invoice Number is already used."];
                    $myar[] = $datar;
                    die(json_encode($myar));
                }
            }
            $adate = explode("-", $ctxdt);
            $mmonth = intval($adate[1]);
            $myear = $adate[0];
            $display_year = substr($myear, -2);
            $monthroma = '';
            for ($i = 0; $i < count($this->AMONTHPATRN); $i++) {
                if (($i + 1) == $mmonth) {
                    $monthroma = $this->AMONTHPATRN[$i];
                    break;
                }
            }
            $lastno = $this->DELV_mod->select_lastnodo_patterned_V3($mmonth, $myear, $monthroma) + 1;
            $ctxid = $delcd_cust . $display_year . $monthroma . substr('0000' . $lastno, -4) . $REMARKTXID;
        }

        $ttlrow = count($ctxa_ser);
        $datas = [];
        $serid_sample = '';
        $first_ret = 0;
        $lastLineLog = $this->CSMLOG_mod->select_lastLine($ctxid, '') + 1;
        for ($i = 0; $i < 1; $i++) {
            $dlvh = ['DLVH_ID' => $ctxid];
            if (!$this->DLVH_mod->check_Primary($dlvh)) {
                $this->DLVH_mod->insert($dlvh);
            }
            if ($this->DELV_mod->check_Primary(['DLV_SER' => $ctxa_ser[$i]]) == 0) {
                $qty = $ctxa_qty[$i];
                $qty = str_replace(',', '', $qty);
                $datak = [
                    'DLV_ID' => $ctxid,
                    'DLV_CUSTDO' => $ctxcustDO,
                    'DLV_DATE' => $ctxdodt,
                    'DLV_CUSTCD' => $ctxcus,
                    'DLV_BSGRP' => $ctxbg,
                    'DLV_CONSIGN' => $ctxconsig,
                    'DLV_INVNO' => $ctxinv,
                    'DLV_INVDT' => $ctxdt,
                    'DLV_SMTINVNO' => $ctxinvsmt,
                    'DLV_TRANS' => $ctxtrans,
                    'DLV_DOCREFF' => $ctxa_so[$i],
                    'DLV_SER' => $ctxa_ser[$i],
                    'DLV_ISPTTRND' => $this->PATTERFLG,
                    'DLV_QTY' => $qty,
                    'DLV_RMRK' => $ctxremark,
                    'DLV_BCTYPE' => $ctxbctype,
                    'DLV_BCDATE' => $ctxdt, #AJU
                    'DLV_CRTD' => $this->session->userdata('nama'),
                    'DLV_CRTDTM' => $crnt_dt,
                    'DLV_ITMCD' => '',
                ];
                //check TXID again
                if ($this->DELV_mod->check_Primary($datacheck) > 0) {
                    $datar = ["cd" => '00', "msg" => "TX ID is already exist. Please try again"];
                    $myar[] = $datar;
                    die(json_encode($myar));
                }
                //END
                $first_ret = $this->DELV_mod->insert($datak);
                $serid_sample = $ctxa_ser[$i];
            }
        }

        for ($i = 1; $i < $ttlrow; $i++) {
            if ($this->DELV_mod->check_Primary(['DLV_SER' => $ctxa_ser[$i]]) == 0) {
                $qty = $ctxa_qty[$i];
                $qty = str_replace(',', '', $qty);
                $datas[] = [
                    'DLV_ID' => $ctxid,
                    'DLV_CUSTDO' => $ctxcustDO,
                    'DLV_DATE' => $ctxdodt,
                    'DLV_CUSTCD' => $ctxcus,
                    'DLV_BSGRP' => $ctxbg,
                    'DLV_CONSIGN' => $ctxconsig,
                    'DLV_INVNO' => $ctxinv,
                    'DLV_INVDT' => $ctxdt,
                    'DLV_SMTINVNO' => $ctxinvsmt,
                    'DLV_TRANS' => $ctxtrans,
                    'DLV_DOCREFF' => $ctxa_so[$i],
                    'DLV_SER' => $ctxa_ser[$i],
                    'DLV_ISPTTRND' => $this->PATTERFLG,
                    'DLV_QTY' => $qty,
                    'DLV_RMRK' => $ctxremark,
                    'DLV_BCTYPE' => $ctxbctype,
                    'DLV_BCDATE' => $ctxdt, #AJU
                    'DLV_CRTD' => $this->session->userdata('nama'),
                    'DLV_CRTDTM' => $crnt_dt,
                    'DLV_ITMCD' => '',
                ];
            }
        }

        if (count($datas) > 0 || $first_ret) {
            $cret = count($datas) ? $this->DELV_mod->insertb($datas) : $first_ret;
            if ($cret > 0) {
                $msg = 'add new transaction';
                $this->CSMLOG_mod->insert([
                    'CSMLOG_DOCNO' => $ctxid,
                    'CSMLOG_SUPZAJU' => '',
                    'CSMLOG_SUPZNOPEN' => '',
                    'CSMLOG_DESC' => $msg,
                    'CSMLOG_LINE' => $lastLineLog,
                    'CSMLOG_TYPE' => 'OUT',
                    'CSMLOG_CREATED_AT' => date('Y-m-d H:i:s'),
                    'CSMLOG_CREATED_BY' => $this->session->userdata('nama'),
                ]);
                #SAVE SO OTHER/ NON MEGA
                $setSODLV = [];
                for ($i = 0; $i < $soCount; $i++) {
                    $setSODLV[] = [
                        'DLVSO_DLVID' => $ctxid, 'DLVSO_ITMCD' => $ctxa_soitem[$i], 'DLVSO_CPONO' => $ctxa_sono[$i], 'DLVSO_QTY' => $ctxa_soqty[$i],
                    ];
                }
                if (count($setSODLV)) {
                    $this->DLVSO_mod->insertb($setSODLV);
                }
                #END
                #SAVE UNSAVED SI
                //==START DEFINE WAREHOUSE
                $cwh_inc = '';
                $cwh_out = '';
                $cfm_inc = '';
                $cfm_out = '';
                $rssiwh = $this->SI_mod->select_wh($serid_sample);
                $siwh = '';
                foreach ($rssiwh as $r) {
                    $siwh = $r['SI_WH'];
                }
                $dataf_txroute = ['TXROUTE_ID' => 'RECEIVING-FG-SHP', 'TXROUTE_WH' => $siwh];
                $rs_txroute = $this->TXROUTE_mod->selectbyVAR($dataf_txroute);
                foreach ($rs_txroute as $r) {
                    $cwh_inc = $r->TXROUTE_WHINC;
                    $cwh_out = $r->TXROUTE_WHOUT;
                    $cfm_inc = $r->TXROUTE_FORM_INC;
                    $cfm_out = $r->TXROUTE_FORM_OUT;
                }
                //==END
                $dataw = ['SISCN_PLLT IS NULL' => null];
                $rsunsaved = $this->SISCN_mod->selectAll_byserin($dataw, $ctxa_ser);
                $ttlrows = count($rsunsaved);
                if ($ttlrows > 0) {
                    //to ith
                    $strser = '';
                    $serin = [];
                    foreach ($rsunsaved as $r) {
                        $strser .= "'$r[SISCN_SER]',";
                        $serin[] = $r['SISCN_SER'];
                    }
                    $strser = substr($strser, 0, strlen($strser) - 1);
                    $rsloc = $this->ITH_mod->selectincfgloc($strser);
                    foreach ($rsloc as $r) {
                        foreach ($rsunsaved as &$k) {
                            if ($r['ITH_SER'] == $k['SISCN_SER']) {
                                $k['SER_LASTLOC'] = $r['ITH_LOC'];
                                break;
                            }
                        }
                        unset($k);
                    }
                    $tr = 0; //catch retured value when saving process
                    foreach ($rsunsaved as $r) {
                        $rsstock = $this->ITH_mod->select_ser_stock($r['SISCN_SER'], $cwh_out);
                        if (count($rsstock) > 0) {
                            $currrtime = date('Y-m-d H:i:s');
                            $tr += $this->ITH_mod->insert_si_scan(
                                trim($r['SER_ITMID']),
                                $cfm_out,
                                $r['SISCN_DOC'],
                                -$r['SISCN_SERQTY'],
                                $cwh_out,
                                $r['SER_LASTLOC'],
                                $r['SISCN_SER'],
                                $this->session->userdata('nama')
                            );

                            $tr += $this->ITH_mod->insert_si_scan(
                                trim($r['SER_ITMID']),
                                $cfm_inc,
                                $r['SISCN_DOC'],
                                $r['SISCN_SERQTY'],
                                $cwh_inc,
                                $r['SER_LASTLOC'],
                                $r['SISCN_SER'],
                                $this->session->userdata('nama')
                            );
                        }
                    }
                    $mlastid = $this->SISCN_mod->lastserialid();
                    $mlastid++;
                    $newid = $mlastid;
                    $currrtime = date('Y-m-d H:i:s');
                    $datau = ['SISCN_PLLT' => $newid, 'SISCN_LUPDT' => $currrtime];
                    $this->SISCN_mod->updatebyId_serin($datau, $dataw, $serin);
                }
                #N SAVE UNSAVED SI
                $myar[] = ["cd" => '11', "msg" => "Saved successfully", "dono" => $ctxid];
                die(json_encode($myar));
            } else {
                $myar[] = ["cd" => '00', "msg" => "Could not save.."];
                die(json_encode($myar));
            }
        } else {
            $myar[] = ["cd" => '00', "msg" => "Could not save"];
            die(json_encode($myar));
        }
    }

    public function remove_delv_rmso()
    {
        header('Content-Type: application/json');
        $txid = $this->input->post('txid');
        $line = $this->input->post('line');
        if ($this->ZRPSTOCK_mod->check_Primary(['RPSTOCK_REMARK' => $txid])) {
            $myar[] = ['cd' => 0, 'msg' => 'Could not be deleted, it was already booked'];
        } else {
            $ret = $this->DLVRMSO_mod->deleteby_filter(['DLVRMSO_TXID' => $txid, 'DLVRMSO_LINE' => $line]);
            if ($ret) {
                $myar[] = ['cd' => 1, 'msg' => 'OK'];
            } else {
                $myar[] = ['cd' => 0, 'msg' => 'Could not be deleted'];
            }
        }
        die(json_encode(['status' => $myar]));
    }
    public function remove_delv_scr()
    {
        header('Content-Type: application/json');
        $txid = $this->input->post('txid');
        $line = $this->input->post('line');
        if ($this->ZRPSTOCK_mod->check_Primary(['RPSTOCK_REMARK' => $txid])) {
            $myar[] = ['cd' => 0, 'msg' => 'Could not be deleted, it was already booked'];
        } else {
            $ret = $this->DLVSCR_mod->deletebyID(['DLVSCR_TXID' => $txid, 'DLVSCR_LINE' => $line]);
            if ($ret) {
                $myar[] = ['cd' => 1, 'msg' => 'OK'];
            } else {
                $myar[] = ['cd' => 0, 'msg' => 'Could not be deleted'];
            }
        }
        die(json_encode(['status' => $myar]));
    }

    public function set_rm()
    {
        header('Content-Type: application/json');
        $currentDate = date('Y-m-d H:i:s');
        $doNum = $this->input->post('indoc');
        $indescription = $this->input->post('indescription');
        $doDate = $this->input->post('indocdate');
        $customsDate = $this->input->post('incustomsdate');
        $bisgrup = $this->input->post('inbisgrup');
        $consignee = $this->input->post('inconsign');
        $transportNum = $this->input->post('intransportNum');
        $customerCode = $this->input->post('incuscd');
        $dokumenPabean = $this->input->post('indokumenpab');
        $invNum = $this->input->post('ininvno');
        $invNumSMT = $this->input->post('ininvnosmt');
        $inlocfrom = $this->input->post('inlocfrom');
        $inrprdoc = $this->input->post('inrprdoc');
        $megadoc = $this->input->post('megadoc');
        $incustDO = $this->input->post('incustDO');
        $pknum41 = $this->input->post('pknum41');
        $aItemNum = $this->input->post('initem');
        $aItemQty = $this->input->post('inqty');
        $aItemRemark = $this->input->post('inremark');
        $aitemdesc = $this->input->post('aitemdesc');
        $aitemsptno = $this->input->post('aitemsptno');

        $aItemRowID = $this->input->post('inrowid');

        $itemCount = count($aItemNum);
        $aPKG_Line = $this->input->post('inpkg_line');
        $aPKG_P = $this->input->post('inpkg_p');
        $aPKG_L = $this->input->post('inpkg_l');
        $aPKG_T = $this->input->post('inpkg_t');
        $apkg_item = $this->input->post('apkg_item');
        $apkg_qty = $this->input->post('apkg_qty');
        $apkg_netw = $this->input->post('apkg_netw');
        $apkg_grossw = $this->input->post('apkg_grossw');
        $apkg_measure = $this->input->post('apkg_measure');
        $apkg_itmtype = $this->input->post('apkg_itmtype');

        $armdoc_itmLINE = $this->input->post('armdoc_itmLINE');
        $armdoc_itmNOAJU = $this->input->post('armdoc_itmNOAJU');
        $armdoc_itmNOPEN = $this->input->post('armdoc_itmNOPEN');
        $armdoc_itmDO = $this->input->post('armdoc_itmDO');
        $armdoc_itmID = $this->input->post('armdoc_itmID');
        $armdoc_itmQT = $this->input->post('armdoc_itmQT');
        $armdoc_itmPRC = $this->input->post('armdoc_itmPRC');
        $armdoc_TYPE = $this->input->post('armdoc_TYPE');

        $armso_itmID = $this->input->post('armso_itmID');
        $armso_itmQT = $this->input->post('armso_itmQT');
        $armso_itmCPO = $this->input->post('armso_itmCPO');
        $armso_itmCPOLINE = $this->input->post('armso_itmCPOLINE');
        $armso_itmPRC = $this->input->post('armso_itmPRC');
        $armso_itmLINE = $this->input->post('armso_itmLINE');

        $arscr_itmid = $this->input->post('armscr_itmID');
        $armscr_itmQT = $this->input->post('armscr_itmQT');
        $armscr_itmPRC = $this->input->post('armscr_itmPRC');
        $armscr_itmLINE = $this->input->post('armscr_itmLINE');

        $cona = '';
        if ($pknum41 != '') {
            $cona = $pknum41;
        }

        $PKGCount = is_array($aPKG_Line) ? count($aPKG_Line) : 0;
        $aRMDOCCount = is_array($armdoc_itmNOAJU) ? count($armdoc_itmNOAJU) : 0;
        $aRMSOCount = is_array($armso_itmID) ? count($armso_itmID) : 0;
        $aRMSCRCount = is_array($arscr_itmid) ? count($arscr_itmid) : 0;

        $delcd_cust = '';
        $rsdelcd = $this->DELV_mod->selectDELCD_where(['MDEL_DELCD' => $consignee]);
        foreach ($rsdelcd as $r) {
            $delcd_cust = $r['MDEL_TXCD'];
        }
        if ($delcd_cust === '') {
            $myar[] = ["cd" => '00', "msg" => "Please define customer code"];
            die(json_encode(['status' => $myar]));
        }
        $this->txbridge->syncParentDocument(['DOC' => $megadoc]);

        $rmbooked = $this->ZRPSTOCK_mod->check_Primary(['RPSTOCK_REMARK' => $doNum]);
        if ($this->DELV_mod->check_Primary(['DLV_ID' => $doNum])) {
            $ttlrow = $this->DELV_mod->check_Primary(['DLV_ID' => $doNum, "ISNULL(DLV_NOPEN,'')" => '']);
            if ($ttlrow === 0 && $indescription !== 'DISPOSE') {
                $myar[] = ["cd" => '00', "msg" => "Read-Only"];
                die(json_encode(['status' => $myar]));
            }
            $ttlUpdated = 0;
            $ttlSaved = 0;
            $newLine = $this->DELV_mod->select_lastline($doNum) + 1;
            $newLine_pkg = $this->DELV_mod->select_lastline_pkg($doNum) + 1;
            $newLine_rmFromDO = $this->DLVRMDOC_mod->select_lastline($doNum) + 1;
            $newLine_rmFromSO = $this->DLVRMSO_mod->select_lastline($doNum) + 1;
            $newLine_rmFromSCR = $this->DLVSCR_mod->select_lastline($doNum) + 1;

            #insert & update delivery
            if ($rmbooked == 0) {
                $saveRows = [];
                for ($i = 0; $i < $itemCount; $i++) {
                    $qty = $aItemQty[$i];
                    $qty = str_replace(',', '', $qty);
                    $where = ['DLV_ID' => $doNum, 'DLV_LINE' => $aItemRowID[$i]];
                    if (strlen($aItemRowID[$i]) >= 1 && $this->DELV_mod->check_Primary($where)) {
                        $ttlUpdated += $this->DELV_mod->updatebyVAR(
                            [
                                'DLV_CUSTCD' => $customerCode,
                                'DLV_BSGRP' => $bisgrup,
                                'DLV_TRANS' => $transportNum,
                                'DLV_QTY' => $qty,
                                'DLV_RMRK' => $aItemRemark[$i],
                                'DLV_ITMCD' => $aItemNum[$i],
                                'DLV_ITMD1' => $aitemdesc[$i],
                                'DLV_ITMSPTNO' => $aitemsptno[$i],
                                'DLV_USRID' => $this->session->userdata('nama'),
                                'DLV_LUPDT' => $currentDate,
                                'DLV_DSCRPTN' => $indescription,
                                'DLV_LOCFR' => $inlocfrom,
                                'DLV_CUSTDO' => $incustDO,
                                'DLV_RPRDOC' => $inrprdoc,
                            ],
                            $where
                        );
                    } else {
                        $_itemdesc = '';
                        $_itemsptno = '';
                        $saveRows[] = [
                            'DLV_ID' => $doNum,
                            'DLV_DATE' => $doDate,
                            'DLV_CUSTCD' => $customerCode,
                            'DLV_BSGRP' => $bisgrup,
                            'DLV_CONSIGN' => $consignee,
                            'DLV_INVNO' => $invNum,
                            'DLV_INVDT' => $doDate,
                            'DLV_SMTINVNO' => $invNumSMT,
                            'DLV_TRANS' => $transportNum,
                            'DLV_DOCREFF' => '',
                            'DLV_ISPTTRND' => $this->PATTERFLG,
                            'DLV_QTY' => $qty,
                            'DLV_RMRK' => $aItemRemark[$i],
                            'DLV_BCTYPE' => $dokumenPabean,
                            'DLV_BCDATE' => $customsDate,
                            'DLV_CRTD' => $this->session->userdata('nama'),
                            'DLV_CRTDTM' => $currentDate,
                            'DLV_ITMCD' => $aItemNum[$i],
                            'DLV_ITMD1' => $_itemdesc,
                            'DLV_ITMSPTNO' => $_itemsptno,
                            'DLV_LINE' => $newLine,
                            'DLV_SER' => '',
                            'DLV_DSCRPTN' => $indescription,
                            'DLV_LOCFR' => $inlocfrom,
                            'DLV_RPRDOC' => $inrprdoc,
                            'DLV_CUSTDO' => $incustDO,
                            'DLV_PARENTDOC' => $megadoc,
                            'DLV_CONA' => $cona,
                        ];
                        $newLine++;
                    }
                }
                if (count($saveRows)) {
                    $ttlSaved += $this->DELV_mod->insertb($saveRows);
                }
            } else {
                for ($i = 0; $i < $itemCount; $i++) {
                    $qty = $aItemQty[$i];
                    $qty = str_replace(',', '', $qty);
                    $where = ['DLV_ID' => $doNum, 'DLV_LINE' => $aItemRowID[$i]];
                    if ($this->DELV_mod->check_Primary($where)) {
                        $ttlUpdated += $this->DELV_mod->updatebyVAR(
                            [
                                'DLV_ITMD1' => $aitemdesc[$i],
                                'DLV_ITMSPTNO' => $aitemsptno[$i],
                                'DLV_DATE' => $doDate,
                                'DLV_INVDT' => $doDate,
                                'DLV_TRANS' => $transportNum,
                                'DLV_USRID' => $this->session->userdata('nama'),
                                'DLV_CUSTDO' => $incustDO,
                            ],
                            $where
                        );
                    }
                }
            }
            #end

            #update header
            $rshead = $this->DELV_mod->select_header_rm($doNum);
            $this->DELV_mod->updatebyVAR([
                'DLV_ZJENIS_TPB_ASAL' => $rshead['DLV_ZJENIS_TPB_ASAL'],
                'DLV_ZJENIS_TPB_TUJUAN' => $rshead['DLV_ZJENIS_TPB_TUJUAN'],
                'DLV_FROMOFFICE' => $rshead['DLV_FROMOFFICE'],
                'DLV_NOAJU' => $rshead['DLV_NOAJU'],
                'DLV_NOPEN' => $rshead['DLV_NOPEN'],
                'DLV_ZKODE_CARA_ANGKUT' => $rshead['DLV_ZKODE_CARA_ANGKUT'],
                'DLV_ZSKB' => $rshead['DLV_ZSKB'],

            ], ['DLV_ID' => $doNum]);
            #end

            #update docs properties
            $this->DELV_mod->updatebyVAR(
                [
                    'DLV_INVNO' => $invNum,
                    'DLV_SMTINVNO' => $invNumSMT,
                    'DLV_BCTYPE' => $dokumenPabean,
                    'DLV_BCDATE' => $customsDate,
                    'DLV_INVDT' => $doDate,
                    'DLV_DATE' => $doDate,
                    'DLV_CONSIGN' => $consignee,
                    'DLV_CUSTDO' => $incustDO,
                    'DLV_DSCRPTN' => $indescription,
                    'DLV_ISPTTRND' => $this->PATTERFLG,
                ],
                ['DLV_ID' => $doNum]
            );
            #end

            #insert & update packaging
            $saveRows = [];
            for ($i = 0; $i < $PKGCount; $i++) {
                if (is_numeric($aPKG_Line[$i])) {
                    $this->DELV_mod->updatebyVAR_pkg(
                        [
                            'DLV_PKG_P' => $aPKG_P[$i] == '' ? 0 : $aPKG_P[$i], 'DLV_PKG_L' => $aPKG_L[$i] == '' ? 0 : $aPKG_L[$i], 'DLV_PKG_T' => $aPKG_T[$i] == '' ? 0 : $aPKG_T[$i], 'DLV_PKG_ITM' => $apkg_item[$i], 'DLV_PKG_QTY' => str_replace(',', '', $apkg_qty[$i]), 'DLV_PKG_NWG' => $apkg_netw[$i] == '' ? 0 : $apkg_netw[$i] * 1, 'DLV_PKG_GWG' => $apkg_grossw[$i] == '' ? 0 : $apkg_grossw[$i] * 1, 'DLV_PKG_MEASURE' => $apkg_measure[$i], 'DLV_PKG_ITMTYPE' => $apkg_itmtype[$i],
                        ],
                        [
                            'DLV_PKG_DOC' => $doNum, 'DLV_PKG_LINE' => $aPKG_Line[$i],
                        ]
                    );
                } else {
                    $saveRows[] = [
                        'DLV_PKG_DOC' => $doNum, 'DLV_PKG_LINE' => $newLine_pkg, 'DLV_PKG_P' => $aPKG_P[$i] == '' ? 0 : $aPKG_P[$i], 'DLV_PKG_L' => $aPKG_L[$i] == '' ? 0 : $aPKG_L[$i], 'DLV_PKG_T' => $aPKG_T[$i] == '' ? 0 : $aPKG_T[$i], 'DLV_PKG_ITM' => $apkg_item[$i], 'DLV_PKG_QTY' => str_replace(',', '', $apkg_qty[$i]), 'DLV_PKG_NWG' => $apkg_netw[$i] == '' ? 0 : $apkg_netw[$i] * 1, 'DLV_PKG_GWG' => $apkg_grossw[$i] == '' ? 0 : $apkg_grossw[$i] * 1, 'DLV_PKG_MEASURE' => $apkg_measure[$i], 'DLV_PKG_ITMTYPE' => $apkg_itmtype[$i],
                    ];
                    $newLine_pkg++;
                }
            }
            if (count($saveRows)) {
                $ttlSaved += $this->DELV_mod->insertb_pkg($saveRows);
            }
            #end

            #insert & update rawmaterial incoming
            $saveRows = [];
            for ($i = 0; $i < $aRMDOCCount; $i++) {
                if (is_numeric($armdoc_itmLINE[$i])) {
                    $this->DLVRMDOC_mod->updatebyId(
                        [
                            'DLVRMDOC_ITMQT' => str_replace(',', '', $armdoc_itmQT[$i]), 'DLVRMDOC_TYPE' => $armdoc_TYPE[$i], 'DLVRMDOC_PRPRC' => $armdoc_itmPRC[$i], 'DLVRMDOC_ZPRPRC' => $armdoc_itmPRC[$i],
                        ],
                        [
                            'DLVRMDOC_TXID' => $doNum, 'DLVRMDOC_LINE' => $armdoc_itmLINE[$i],
                        ]
                    );
                } else {
                    $saveRows[] = [
                        'DLVRMDOC_TXID' => $doNum, 'DLVRMDOC_ITMID' => $armdoc_itmID[$i], 'DLVRMDOC_ITMQT' => str_replace(',', '', $armdoc_itmQT[$i]), 'DLVRMDOC_DO' => $armdoc_itmDO[$i], 'DLVRMDOC_AJU' => $armdoc_itmNOAJU[$i], 'DLVRMDOC_NOPEN' => $armdoc_itmNOPEN[$i], 'DLVRMDOC_PRPRC' => $armdoc_itmPRC[$i], 'DLVRMDOC_ZPRPRC' => $armdoc_itmPRC[$i], 'DLVRMDOC_LINE' => $newLine_rmFromDO, 'DLVRMDOC_TYPE' => $armdoc_TYPE[$i],
                    ];
                    $newLine_rmFromDO++;
                }
            }
            if (count($saveRows)) {
                $ttlSaved += $this->DLVRMDOC_mod->insertb($saveRows);
            }
            #end

            #insert & update price rawmaterial from SO
            $saveRows = [];
            for ($i = 0; $i < $aRMSOCount; $i++) {
                if (is_numeric($armso_itmLINE[$i])) {
                    $this->DLVRMSO_mod->updatebyId(
                        [
                            'DLVRMSO_ITMQT' => str_replace(',', '', $armso_itmQT[$i]),
                        ],
                        [
                            'DLVRMSO_TXID' => $doNum, 'DLVRMSO_LINE' => $armso_itmLINE[$i],
                        ]
                    );
                } else {
                    $saveRows[] = [
                        'DLVRMSO_TXID' => $doNum, 'DLVRMSO_ITMID' => $armso_itmID[$i], 'DLVRMSO_ITMQT' => str_replace(',', '', $armso_itmQT[$i]), 'DLVRMSO_CPO' => $armso_itmCPO[$i], 'DLVRMSO_CPOLINE' => $armso_itmCPOLINE[$i], 'DLVRMSO_PRPRC' => $armso_itmPRC[$i], 'DLVRMSO_LINE' => $newLine_rmFromSO,
                    ];
                    $newLine_rmFromSO++;
                }
            }
            if (count($saveRows)) {
                $ttlSaved += $this->DLVRMSO_mod->insertb($saveRows);
            }
            #end

            $saveRows = [];
            for ($i = 0; $i < $aRMSCRCount; $i++) {
                if ($armscr_itmLINE[$i] === '') {
                    $saveRows[] = [
                        'DLVSCR_TXID' => $doNum, 'DLVSCR_ITMID' => $arscr_itmid[$i], 'DLVSCR_ITMQT' => str_replace(',', '', $armscr_itmQT[$i]), 'DLVSCR_PRPRC' => $armscr_itmPRC[$i], 'DLVSCR_LINE' => $newLine_rmFromSCR++,
                    ];
                } else {
                    $this->DLVSCR_mod->updatebyVAR(
                        [
                            'DLVSCR_ITMQT' => str_replace(',', '', $armscr_itmQT[$i]),
                            'DLVSCR_PRPRC' => $armscr_itmPRC[$i],
                        ],
                        ['DLVSCR_TXID' => $doNum, 'DLVSCR_LINE' => $armscr_itmLINE[$i]]
                    );
                }
            }
            if (!empty($saveRows)) {
                $this->DLVSCR_mod->insertb($saveRows);
            }

            $myar[] = ['cd' => '11', 'msg' => 'Updated'];
            die('{"status":' . json_encode($myar) . '}');
        } else {
            $saveRows = [];
            $adate = explode("-", $doDate);
            $mmonth = intval($adate[1]);
            $myear = $adate[0];
            $display_year = substr($myear, -2);
            $monthroma = '';
            for ($i = 0; $i < count($this->AMONTHPATRN); $i++) {
                if (($i + 1) == $mmonth) {
                    $monthroma = $this->AMONTHPATRN[$i];
                    break;
                }
            }
            $lastno = $this->DELV_mod->select_lastnodo_patterned_V3($mmonth, $myear, $monthroma) + 1;
            $ctxid = $delcd_cust . $display_year . $monthroma . substr('0000' . $lastno, -4);
            for ($i = 0; $i < $itemCount; $i++) {
                $qty = $aItemQty[$i];
                $qty = str_replace(',', '', $qty);
                $saveRows[] = [
                    'DLV_ID' => $ctxid,
                    'DLV_DATE' => $doDate,
                    'DLV_CUSTDO' => $incustDO,
                    'DLV_CUSTCD' => $customerCode,
                    'DLV_BSGRP' => $bisgrup,
                    'DLV_CONSIGN' => $consignee,
                    'DLV_INVNO' => $invNum,
                    'DLV_INVDT' => $doDate,
                    'DLV_SMTINVNO' => $invNumSMT,
                    'DLV_TRANS' => $transportNum,
                    'DLV_DOCREFF' => '',
                    'DLV_ISPTTRND' => $this->PATTERFLG,
                    'DLV_QTY' => $qty,
                    'DLV_RMRK' => $aItemRemark[$i],
                    'DLV_BCTYPE' => $dokumenPabean,
                    'DLV_BCDATE' => $customsDate,
                    'DLV_CRTD' => $this->session->userdata('nama'),
                    'DLV_CRTDTM' => $currentDate,
                    'DLV_ITMCD' => $aItemNum[$i],
                    'DLV_DSCRPTN' => $indescription,
                    'DLV_LINE' => $i,
                    'DLV_SER' => '',
                    'DLV_DSCRPTN' => $indescription,
                    'DLV_LOCFR' => $inlocfrom,
                    'DLV_RPRDOC' => $inrprdoc,
                    'DLV_PARENTDOC' => $megadoc,
                    'DLV_CONA' => $cona,
                ];
            }
            if (count($saveRows)) {
                $this->DELV_mod->insertb($saveRows);
            }
            $saveRows = [];
            for ($i = 0; $i < $PKGCount; $i++) {
                $saveRows[] = [
                    'DLV_PKG_DOC' => $ctxid, 'DLV_PKG_LINE' => $i, 'DLV_PKG_P' => $aPKG_P[$i] == '' ? 0 : $aPKG_P[$i], 'DLV_PKG_L' => $aPKG_L[$i] == '' ? 0 : $aPKG_L[$i], 'DLV_PKG_T' => $aPKG_T[$i] == '' ? 0 : $aPKG_T[$i], 'DLV_PKG_ITM' => $apkg_item[$i], 'DLV_PKG_QTY' => $apkg_qty[$i], 'DLV_PKG_NWG' => $apkg_netw[$i] == '' ? 0 : $apkg_netw[$i], 'DLV_PKG_GWG' => $apkg_grossw[$i] == '' ? 0 : $apkg_grossw[$i], 'DLV_PKG_MEASURE' => $apkg_measure[$i],
                ];
            }
            if (count($saveRows)) {
                $this->DELV_mod->insertb_pkg($saveRows);
            }
            $saveRows = [];
            for ($i = 0; $i < $aRMDOCCount; $i++) {
                $saveRows[] = [
                    'DLVRMDOC_TXID' => $ctxid, 'DLVRMDOC_ITMID' => $armdoc_itmID[$i], 'DLVRMDOC_ITMQT' => str_replace(',', '', $armdoc_itmQT[$i]), 'DLVRMDOC_DO' => $armdoc_itmDO[$i], 'DLVRMDOC_AJU' => $armdoc_itmNOAJU[$i], 'DLVRMDOC_NOPEN' => $armdoc_itmNOPEN[$i], 'DLVRMDOC_PRPRC' => $armdoc_itmPRC[$i], 'DLVRMDOC_ZPRPRC' => $armdoc_itmPRC[$i], 'DLVRMDOC_LINE' => $i,
                ];
            }
            if (count($saveRows)) {
                $this->DLVRMDOC_mod->insertb($saveRows);
            }
            $saveRows = [];
            for ($i = 0; $i < $aRMSOCount; $i++) {
                $saveRows[] = [
                    'DLVRMSO_TXID' => $ctxid, 'DLVRMSO_ITMID' => $armso_itmID[$i], 'DLVRMSO_ITMQT' => str_replace(',', '', $armso_itmQT[$i]), 'DLVRMSO_CPO' => $armso_itmCPO[$i], 'DLVRMSO_CPOLINE' => $armso_itmCPOLINE[$i], 'DLVRMSO_PRPRC' => $armso_itmPRC[$i], 'DLVRMSO_LINE' => $i,
                ];
            }
            if (count($saveRows)) {
                $this->DLVRMSO_mod->insertb($saveRows);
            }

            $saveRows = [];
            for ($i = 0; $i < $aRMSCRCount; $i++) {
                $saveRows[] = [
                    'DLVSCR_TXID' => $ctxid, 'DLVSCR_ITMID' => $arscr_itmid[$i], 'DLVSCR_ITMQT' => str_replace(',', '', $armscr_itmQT[$i]), 'DLVSCR_PRPRC' => $armscr_itmPRC[$i], 'DLVSCR_LINE' => $i + 1,
                ];
            }
            if (count($saveRows)) {
                $this->DLVSCR_mod->insertb($saveRows);
            }

            $myar[] = ['cd' => '1', 'msg' => 'Saved', 'dono' => $ctxid];
            die('{"status":' . json_encode($myar) . '}');
        }
    }

    public function search()
    {
        header('Content-Type: application/json');
        $ckeys = $this->input->get('inkey');
        $cs_by = $this->input->get('insearchby');
        $csearchperiod = $this->input->get('insearchperiod');
        $cmonth = $this->input->get('inmonth');
        $cyear = $this->input->get('inyear');
        if ($this->input->get('insearchtype') == '1') {
            if ($csearchperiod == 0) {
                switch ($cs_by) {
                    case 'tx':
                        $rs = $this->DELV_mod->select_bytx($ckeys);
                        break;
                    case 'txdate':
                        $rs = $this->DELV_mod->select_bydt($ckeys);
                        break;
                    case 'cusnm':
                        $rs = $this->DELV_mod->select_bycustomer($ckeys);
                        break;
                }
            } else {
                switch ($cs_by) {
                    case 'tx':
                        $rs = $this->DELV_mod->select_bytx_period($ckeys, $cmonth, $cyear);
                        break;
                    case 'txdate':
                        $rs = $this->DELV_mod->select_bydt($ckeys);
                        break;
                    case 'cusnm':
                        $rs = $this->DELV_mod->select_bycustomer($ckeys);
                        break;
                }
            }
        } else {
            if ($csearchperiod == 0) {
                switch ($cs_by) {
                    case 'tx':
                        $rs = $this->DELV_mod->select_bytx_rm($ckeys);
                        break;
                    case 'txdate':
                        $rs = $this->DELV_mod->select_bydt_rm($ckeys);
                        break;
                    case 'cusnm':
                        $rs = $this->DELV_mod->select_bycustomer_rm($ckeys);
                        break;
                }
            } else {
                switch ($cs_by) {
                    case 'tx':
                        $rs = $this->DELV_mod->select_bytx_period_rm($ckeys, $cmonth, $cyear);
                        break;
                    case 'txdate':
                        $rs = $this->DELV_mod->select_bydt_rm($ckeys);
                        break;
                    case 'cusnm':
                        $rs = $this->DELV_mod->select_bycustomer_rm($ckeys);
                        break;
                }
            }
        }
        die('{"data":' . json_encode($rs) . '}');
    }

    public function xrpr()
    {
        header('Content-Type: application/json');
        $doc = $this->input->get('doc');
        $rs = $this->XITRN_mod->select_where(
            [
                'RTRIM(ITRN_ITMCD) ITRN_ITMCD', 'RTRIM(MITM_ITMD1) MITM_ITMD1', 'RTRIM(MITM_SPTNO) MITM_SPTNO', 'RTRIM(ITRN_REFNO1) ITRN_REFNO1', 'ITRN_TRNQT',
            ],
            ['ITRN_LOCCD' => 'ARWH0PD', 'ITRN_DOCNO' => $doc]
        );
        die(json_encode(['data' => $rs]));
    }

    public function getdetails()
    {
        header('Content-Type: application/json');
        $cid = $this->input->get('intxid');
        $ctype = $this->input->get('intype');
        if ($ctype === '1') {
            $rs = $this->DELV_mod->select_det_byid($cid);
            $rsrm_checker = $this->SER_mod->select_sususan_bahan_baku_by_txid_v2($cid);
            $rsrm_notOK = [];
            foreach ($rsrm_checker as $r) {
                if ($r['CALPER'] == 0 || $r['CALPER'] < $r['MYPER']) {
                    if ($r['FLG'] != 'flg_ok') {
                        if (substr($r['SER_DOC'], 2, 2) != '-C') {
                            $rsrm_notOK[] = $r;
                        }
                    }
                } elseif ($r['CALPER'] > 0 && abs($r['CALPER'] - $r['MYPER']) > 1) {
                    $rsrm_notOK[] = $r;
                }
            }
            die(json_encode(['data' => $rs, 'datafocus' => $rsrm_notOK]));
        } else {
            $rs = $this->DELV_mod->select_det_byid_rm($cid);
            $rspkg = $this->DELV_mod->select_pkg($cid);
            $rsRMFromDO = $this->DLVRMDOC_mod->select_where([
                'DLVRMDOC_TXID', 'DLVRMDOC_ITMID', 'DLVRMDOC_ITMQT', 'DLVRMDOC_DO', 'DLVRMDOC_AJU', 'DLVRMDOC_NOPEN', 'DLVRMDOC_PRPRC', 'DLVRMDOC_LINE', 'RCV_BCDATE', 'RCV_BCTYPE', 'DLVRMDOC_TYPE',
            ], ['DLVRMDOC_TXID' => $cid]);
            $rsRMFromSO = $this->DLVRMSO_mod->select_where([
                'DLVRMSO_TXID', 'DLVRMSO_ITMID', 'DLVRMSO_ITMQT', 'DLVRMSO_CPO', 'DLVRMSO_CPOLINE', 'DLVRMSO_PRPRC', 'DLVRMSO_LINE',
            ], ['DLVRMSO_TXID' => $cid]);
            $rsscr = $this->DLVSCR_mod->select_where(['DLVSCR_TXID' => $cid]);
            die(json_encode([
                'data' => $rs, 'data_rmdo' => $rsRMFromDO, 'data_pkg' => $rspkg, 'data_rmso' => $rsRMFromSO, 'data_scr' => $rsscr,
            ]));
        }
    }

    public function generate_total_use()
    {
        header('Content-Type: application/json');
        ini_set('max_execution_time', '1200');
        $date = date('Y-m-d');
        $location = $this->input->post('location');
        $wo = $this->input->post('wo');
        $rs_wo = [];
        $by = '';
        if (is_array($wo)) {
            $by = 'wo';
            foreach ($wo as $w) {
                $rs_wo[] = ['SER_DOC' => $w];
            }
        } else {
            $by = 'location';
            $rs_wo = $this->ITH_mod->select_available_wo($date, $location);
        }
        $Calc_lib = new RMCalculator();
        foreach ($rs_wo as &$r) {
            $r['status'] = $Calc_lib->generate_total_use($r['SER_DOC']);
        }
        unset($r);
        die(json_encode(['data' => $rs_wo, 'by' => $by]));
    }

    public function doc_rm_as_xls()
    {
        $pid = '';
        if (isset($_COOKIE["CKPDLV_NO"])) {
            $pid = $_COOKIE["CKPDLV_NO"];
            $currency = $_COOKIE["CKPDLV_CURRENCY"];
            $tglaju = $_COOKIE["CKPDLV_TGLAJU"];
        } else {
            exit('nothing to be printed');
        }
        if (isset($_COOKIE["CKPDLV_FORMS"])) {
        } else {
            exit('Please select at least one document type');
        }
        $spreadsheet = new Spreadsheet();
        $sheet = $spreadsheet->getActiveSheet();
        $sheet->setTitle('INVOICE');
        //INVOICE
        $rsrmdoc = $this->DLVRMDOC_mod->select_invoice($pid);
        $rsrmdocFromSO = $this->DLVRMSO_mod->select_invoice($pid);
        $rs_rcv = $this->RCV_mod->select_for_rmrtn_bytxid($pid);
        $MultipliedNumber = 1;
        $shouldRound = false;
        foreach ($rs_rcv as $a) {
            if ($a['MSUP_SUPCR'] != "RPH" && $currency == "RPH") {
                $shouldRound = true;
                $rscurrency = $this->MEXRATE_mod->selectfor_posting($tglaju, $a['MSUP_SUPCR']);
                if (count($rscurrency) == 0) {
                    die('exchange rate is required ' . $tglaju);
                } else {
                    foreach ($rscurrency as $n) {
                        $MultipliedNumber = $n->MEXRATE_VAL;
                        break;
                    }
                }
            }
            break;
        }
        $rsfixINV = [];
        foreach ($rsrmdoc as &$r) {
            $r['PLOTQT'] = 0;
            foreach ($rs_rcv as &$x) {
                if (
                    $r['DLVRMDOC_ITMID'] == $x['RCV_ITMCD']
                    && $r['DLVRMDOC_AJU'] == $x['RCV_RPNO']
                    && $r['DLVRMDOC_DO'] == $x['RCV_DONO']
                    && $x['RCV_QTY'] > 0
                    && $r['PLOTQT'] != $r['ITMQT']
                ) {
                    $reqbal = $r['ITMQT'] - $r['PLOTQT'];
                    $useqt = 0;
                    if ($reqbal > $x['RCV_QTY']) {
                        $useqt = $x['RCV_QTY'];
                        $r['PLOTQT'] += $x['RCV_QTY'];
                        $x['RCV_QTY'] = 0;
                    } else {
                        $useqt = $reqbal;
                        $r['PLOTQT'] += $reqbal;
                        $x['RCV_QTY'] -= $reqbal;
                    }
                    $rsfixINV[] = [
                        'ITMQT' => $useqt, 'DLVRMDOC_PRPRC' => $r['DLVRMDOC_PRPRC'], 'DLV_ITMD1' => $r['DLV_ITMD1'], 'DLVRMDOC_TYPE' => $r['DLVRMDOC_TYPE'], 'MITM_STKUOM' => $r['MITM_STKUOM'], 'DLVRMDOC_PRPRC' => $r['DLVRMDOC_PRPRC'], 'DLVRMDOC_ITMID' => $r['DLVRMDOC_ITMID'],
                    ];
                    if ($r['ITMQT'] == $r['PLOTQT']) {
                        break;
                    }
                }
            }
            unset($x);
        }
        unset($r);

        if (count($rsrmdoc) && count($rsrmdocFromSO) <= 0) {
            $h_delnm = '';
            $h_deladdress = '';
            $h_invno = '';
            $hinv_currency = '';
            foreach ($rsrmdoc as $r) {
                $h_delnm = $r['MDEL_ZNAMA'];
                $h_deladdress = $r['MDEL_ADDRCUSTOMS'];
                $h_invno = $r['DLV_INVNO'];
                $hinv_currency = $r['MCUS_CURCD'];
                $hinv_date = $r['DLV_INVDT'];
                $hinv_date = date_create($hinv_date);
                $hinv_date = date_format($hinv_date, "d/m/Y");
                $hinv_nopen = $r['DLV_NOPEN'];
                break;
            }

            $sheet->setCellValueByColumnAndRow(7, 1, 'Nopen : ');
            $sheet->setCellValueByColumnAndRow(8, 1, $hinv_nopen);
            $sheet->setCellValueByColumnAndRow(1, 2, $h_invno);
            $sheet->setCellValueByColumnAndRow(8, 2, $hinv_date);
            $sheet->setCellValueByColumnAndRow(1, 3, trim($h_delnm));
            $sheet->setCellValueByColumnAndRow(1, 4, trim($h_deladdress));
            $sheet->setCellValueByColumnAndRow(5, 6, trim($hinv_currency));

            $inx = 7;
            $no = 1;
            foreach ($rsfixINV as $r) {
                switch (trim($r['MITM_STKUOM'])) {
                    case 'GMS':
                        $uom = 'GRM';
                        break;
                    case 'KG':
                        $uom = 'KGM';
                        break;
                    default:
                        $uom = $r['MITM_STKUOM'];
                }
                if ($shouldRound) {
                    $amount_ = $r['ITMQT'] * round($r['DLVRMDOC_PRPRC'] * $MultipliedNumber);
                    $perprice_ = round($r['DLVRMDOC_PRPRC'] * $MultipliedNumber);
                } else {
                    $amount_ = $r['ITMQT'] * ($r['DLVRMDOC_PRPRC'] * $MultipliedNumber);
                    $perprice_ = ($r['DLVRMDOC_PRPRC'] * $MultipliedNumber);
                }
                $sheet->setCellValueByColumnAndRow(1, $inx, $no);
                $sheet->setCellValueByColumnAndRow(2, $inx, $r['DLV_ITMD1']);
                $sheet->setCellValueByColumnAndRow(2, $inx + 1, $r['DLVRMDOC_ITMID']);
                $sheet->setCellValueByColumnAndRow(3, $inx, $uom);
                $sheet->setCellValueByColumnAndRow(4, $inx, $r['ITMQT']);
                $sheet->setCellValueByColumnAndRow(5, $inx, $perprice_);
                $sheet->setCellValueByColumnAndRow(6, $inx, $amount_);
                $inx += 2;
                $no++;
            }
        } else {
            $rsrmdoc = $rsrmdocFromSO;
            if (count($rsrmdoc)) {
                foreach ($rsrmdoc as $r) {
                    $h_delnm = $r['MDEL_ZNAMA'];
                    $h_deladdress = $r['MDEL_ADDRCUSTOMS'];
                    $h_invno = $r['DLV_INVNO'];
                    $hinv_currency = $r['MCUS_CURCD'];
                    $hinv_date = $r['DLV_INVDT'];
                    $hinv_date = date_create($hinv_date);
                    $hinv_date = date_format($hinv_date, "d/m/Y");
                    $hinv_nopen = $r['DLV_NOPEN'];
                    break;
                }
                $sheet->setCellValueByColumnAndRow(7, 1, 'Nopen : ');
                $sheet->setCellValueByColumnAndRow(8, 1, $hinv_nopen);
                $sheet->setCellValueByColumnAndRow(1, 2, $h_invno);
                $sheet->setCellValueByColumnAndRow(8, 2, $hinv_date);
                $sheet->setCellValueByColumnAndRow(1, 3, trim($h_delnm));
                $sheet->setCellValueByColumnAndRow(1, 4, trim($h_deladdress));
                $sheet->setCellValueByColumnAndRow(5, 6, trim($hinv_currency));

                $inx = 7;
                $no = 1;
                foreach ($rsrmdoc as $r) {
                    $sheet->setCellValueByColumnAndRow(1, $inx, $no);
                    $sheet->setCellValueByColumnAndRow(2, $inx, $r['DLV_ITMD1']);
                    $sheet->setCellValueByColumnAndRow(2, $inx + 1, $r['DLVRMSO_ITMID']);
                    $sheet->setCellValueByColumnAndRow(3, $inx, $r['MITM_STKUOM']);
                    $sheet->setCellValueByColumnAndRow(4, $inx, $r['ITMQT']);
                    $sheet->setCellValueByColumnAndRow(5, $inx, $r['DLVRMSO_PRPRC']);
                    $sheet->setCellValueByColumnAndRow(6, $inx, $r['ITMQT'] * $r['DLVRMSO_PRPRC']);
                    $inx += 2;
                    $no++;
                }
            } else {
                die('posting first to get price');
            }
        }
        $sheet->setCellValueByColumnAndRow(4, $inx, "=SUM(D7:D" . ($inx - 1) . ")");
        $sheet->setCellValueByColumnAndRow(6, $inx, "=SUM(F7:F" . ($inx - 1) . ")");
        foreach (range('B', 'F') as $columnID) {
            $sheet->getColumnDimension($columnID)
                ->setAutoSize(true);
        }
        $sheet->getStyle('B7:B' . ($inx - 1))->getAlignment()->setHorizontal('left');

        //PACKING LIST
        $sheet = $spreadsheet->createSheet();
        $sheet->setTitle('PACKINGLIST');
        $rs = $this->DELV_mod->select_for_pkg_rm_rtn($pid);
        $h_delnm = '';
        $h_deladdress = '';

        foreach ($rs as $r) {
            $hinv_date = $r['DLV_INVDT'];
            $hinv_date = date_create($hinv_date);
            $hinv_date = date_format($hinv_date, "d/m/Y");
            $h_delnm = $r['MDEL_ZNAMA'];
            $h_deladdress = $r['MDEL_ADDRCUSTOMS'];
            $h_invno = $r['DLV_INVNO'];
            break;
        }
        $sheet->setCellValueByColumnAndRow(7, 1, $hinv_date);
        $sheet->setCellValueByColumnAndRow(1, 2, $h_delnm);
        $sheet->setCellValueByColumnAndRow(1, 3, $h_deladdress);
        $sheet->setCellValueByColumnAndRow(7, 4, $h_invno);

        $dis_item = '';
        $dis_itemnm = '';
        $dis_no = '';
        $dis_qty = '';
        $inx = 7;
        $no = 0;
        foreach ($rs as $r) {
            $dis_item = $r['DLV_PKG_ITM'];
            $dis_itemnm = $r['DLV_ITMD1'];
            $dis_qty = number_format($r['DLV_PKG_QTY'], 0);
            $no++;
            $dis_no = $no;
            $sheet->setCellValueByColumnAndRow(1, $inx, $dis_no);
            if ($dis_itemnm != '') {
                $sheet->setCellValueByColumnAndRow(2, $inx, $dis_itemnm);
            }
            if ($dis_item != '') {
                $sheet->setCellValueByColumnAndRow(3, $inx, $dis_item);
            }
            $sheet->setCellValueByColumnAndRow(4, $inx, $dis_qty);
            $sheet->setCellValueByColumnAndRow(5, $inx, $r['DLV_PKG_NWG']);
            $sheet->setCellValueByColumnAndRow(6, $inx, $r['DLV_PKG_GWG']);
            $sheet->setCellValueByColumnAndRow(7, $inx, $r['DLV_PKG_MEASURE']);
            $inx += 1;
        }
        //DELIVERY ORDER
        $rs_do = $this->DELV_mod->select_for_do_rm_rtn_v1($pid);
        $rs_xbc = $this->RCV_mod->select_for_rmrtn_bytxid($pid);
        $h_delnm = '';
        $h_deladdress = '';

        $h_bctype = '';

        $h_invno = '';
        $hdlv_date = '';
        $tempItem = '';
        $ItemDis = '';
        $ItemDis2 = '';

        $nourutDO = 0;

        foreach ($rs_do as $r) {
            $hdlv_date = $r['DLV_DATE'];
            $hdlv_date = date_create($hdlv_date);
            $hdlv_date = date_format($hdlv_date, "d/m/Y");
            $h_delnm = $r['MDEL_ZNAMA'];
            $h_deladdress = $r['MDEL_ADDRCUSTOMS'];
            $h_bctype = $r['DLV_BCTYPE'];
            $h_invno = $r['DLV_INVNO'];
            break;
        }
        $rsfix = [];
        foreach ($rs_do as $r) {
            $r['PLOTQT'] = 0;
            foreach ($rs_xbc as &$x) {
                if (
                    $r['DLVRMDOC_ITMID'] == $x['RCV_ITMCD']
                    && $r['DLVRMDOC_AJU'] == $x['RCV_RPNO']
                    && $r['DLVRMDOC_DO'] == $x['RCV_DONO']
                    && $x['RCV_QTY'] > 0
                    && $r['PLOTQT'] != $r['DLVRMDOC_ITMQT']
                ) {
                    $reqbal = $r['DLVRMDOC_ITMQT'] - $r['PLOTQT'];
                    $useqt = 0;
                    if ($reqbal > $x['RCV_QTY']) {
                        $useqt = $x['RCV_QTY'];
                        $r['PLOTQT'] += $x['RCV_QTY'];
                        $x['RCV_QTY'] = 0;
                    } else {
                        $useqt = $reqbal;
                        $r['PLOTQT'] += $reqbal;
                        $x['RCV_QTY'] -= $reqbal;
                    }
                    $rsfix[] = [
                        'DLVRMDOC_ITMID' => $r['DLVRMDOC_ITMID'], 'DLV_ITMD1' => $r['DLV_ITMD1'], 'MITM_ITMD1' => $r['MITM_ITMD1'], 'DLV_ITMSPTNO' => $r['DLV_ITMSPTNO'], 'MITM_SPTNO' => $r['MITM_SPTNO'], 'DLVRMDOC_ITMQT' => $r['DLVRMDOC_ITMQT'], 'DLVRMDOC_AJU' => $r['DLVRMDOC_AJU'], 'DLVRMDOC_NOPEN' => $r['DLVRMDOC_NOPEN'], 'DLVRMDOC_ITMQT' => $useqt, 'MITM_STKUOM' => $r['MITM_STKUOM'], 'RCV_BCDATE' => $x['RCV_BCDATE'], 'MITM_ITMCDCUS' => $r['MITM_ITMCDCUS'],
                    ];
                    if ($r['DLVRMDOC_ITMQT'] == $r['PLOTQT']) {
                        break;
                    }
                }
            }
            unset($x);
        }
        unset($r);

        $sheet = $spreadsheet->createSheet();
        $sheet->setTitle('DO');
        $sheet->setCellValueByColumnAndRow(1, 1, $h_delnm);
        $sheet->setCellValueByColumnAndRow(1, 2, $h_deladdress);
        $sheet->setCellValueByColumnAndRow(7, 1, $pid);
        $sheet->setCellValueByColumnAndRow(7, 3, $hinv_date);
        $sheet->setCellValueByColumnAndRow(7, 5, 'BC ' . $h_bctype . ' : ' . $hinv_nopen);
        $sheet->setCellValueByColumnAndRow(7, 6, 'INV NO : ' . $h_invno);

        $inx = 7;
        foreach ($rsfix as $r) {
            $nourutDO++;

            $tempItem = $r['MITM_ITMCDCUS'] != '' ? $r['MITM_ITMCDCUS'] : $r['DLVRMDOC_ITMID'];
            $ItemDis = $tempItem;
            $ItemDis2 = rtrim($r['DLV_ITMD1']);
            $nourutDODis = $nourutDO;

            $xbcdate = date_create($r['RCV_BCDATE']);
            $xbcdate = date_format($xbcdate, "d-M-Y");

            $sheet->setCellValueByColumnAndRow(1, $inx, $nourutDODis);
            $sheet->setCellValueByColumnAndRow(2, $inx, $ItemDis2);
            $sheet->setCellValueByColumnAndRow(3, $inx, $ItemDis);
            $sheet->setCellValueByColumnAndRow(4, $inx, $r['DLVRMDOC_ITMQT']);
            $sheet->setCellValueByColumnAndRow(5, $inx, $r['MITM_STKUOM']);
            $inx++;
        }
        $dodis = str_replace("/", "#", $pid);
        $stringjudul = $dodis;
        $writer = new Xlsx($spreadsheet);
        $filename = $stringjudul; //save our workbook as this file name

        header('Content-Type: application/vnd.ms-excel');
        header('Content-Disposition: attachment;filename="' . $filename . '.xlsx"');
        header('Cache-Control: max-age=0');
        $writer->save('php://output');
    }

    public function doc_as_xls()
    {
        $pid = '';
        if (isset($_COOKIE["CKPDLV_NO"])) {
            $pid = $_COOKIE["CKPDLV_NO"];
        } else {
            exit('nothing to be printed');
        }
        $rs = $this->DELV_mod->select_det_byid_p($pid);
        //start of data base
        $hinv_date = '';
        $hdlv_date = '';
        $hinv_inv = '';
        $hinv_nopen = '';
        $hinv_customer = '';
        $hinv_address = '';

        $hinv_currency = '';
        $hinv_bctype = '';

        $ar_item = [];
        $ar_itemdesc = [];
        $ar_itemUM = [];
        $ar_qty = [];

        $ATTN = '';

        foreach ($rs as $r) {
            $hinv_date = $r['DLV_INVDT'];
            $hinv_date = date_create($hinv_date);
            $hinv_date = date_format($hinv_date, "d/m/Y");
            $hdlv_date = $r['DLV_DATE'];
            $hdlv_date = date_create($hdlv_date);
            $hdlv_date = date_format($hdlv_date, "d/m/Y");
            $hinv_inv = $r['DLV_INVNO'];
            $hinv_nopen = $r['DLV_NOPEN'];
            $hinv_address = $r['MDEL_ADDRCUSTOMS'];
            $hinv_currency = $r['MCUS_CURCD'];
            $hinv_customer = $r['MDEL_DELNM'];
            $hinv_bctype = $r['DLV_BCTYPE'];
            $ATTN = $r['ATTN'];
            break;
        }

        $spreadsheet = new Spreadsheet();
        $sheet = $spreadsheet->getActiveSheet();
        $sheet->setTitle('INVOICE');
        $sheet->setCellValueByColumnAndRow(7, 1, 'Nopen : ');
        $sheet->setCellValueByColumnAndRow(8, 1, $hinv_nopen);
        $sheet->setCellValueByColumnAndRow(1, 2, $hinv_inv);
        $sheet->setCellValueByColumnAndRow(8, 2, $hinv_date);
        $sheet->setCellValueByColumnAndRow(1, 3, trim($hinv_customer));
        $sheet->setCellValueByColumnAndRow(1, 4, trim($hinv_address));
        $sheet->setCellValueByColumnAndRow(1, 5, trim($ATTN));
        $sheet->setCellValueByColumnAndRow(5, 6, trim($hinv_currency));

        $rsinv = $this->setPriceRS(base64_encode($pid));
        $rsresume = [];
        $rsmultiprice = [];
        foreach ($rsinv as &$k) {
            if ($k['SISO_SOLINE'] == 'X') {
                $rs_mst_price = $this->XSO_mod->select_latestprice($k['SI_BSGRP'], $k['SI_CUSCD'], "'" . $k['SSO2_MDLCD'] . "'");
                foreach ($rs_mst_price as $r) {
                    $k['SSO2_SLPRC'] = substr($r['MSPR_SLPRC'], 0, 1) == '.' ? '0' . $r['MSPR_SLPRC'] : $r['MSPR_SLPRC'];
                    $k['CIF'] = $k['SSO2_SLPRC'] * $k['SISOQTY'];
                }
            }
            $isfound = false;
            foreach ($rsresume as &$n) {
                if ($n['RSI_ITMCD'] == $k['SSO2_MDLCD'] && $n['RSSO2_SLPRC'] != $k['SSO2_SLPRC']) {
                    $n['RCOUNT']++;
                    $isfound = true;
                    break;
                }
            }
            unset($n);

            if (!$isfound) {
                $rsresume[] = [
                    'RSI_ITMCD' => $k['SSO2_MDLCD'], 'RSSO2_SLPRC' => $k['SSO2_SLPRC'], 'RCOUNT' => 1,
                ];
            }
        }
        unset($k);
        foreach ($rsresume as $k) {
            if ($k['RCOUNT'] > 1) {
                $rsmultiprice[] = $k;
            }
        }

        if (count($rsmultiprice) > 0) {
            if (strpos(strtolower($hinv_customer), 'epson') !== false) {
                $myar[] = ["cd" => "0", "msg" => "Multi price detected please, click 'Price Detail' to confirm "];
                die('{"status":' . json_encode($myar) . ',"data":' . json_encode($rsinv) . ',"data2":' . json_encode($rsmultiprice) . '}');
            }
        }
        $inx = 7;
        $no = 1;
        foreach ($rsinv as $r) {
            $sheet->setCellValueByColumnAndRow(1, $inx, $no);
            $sheet->setCellValueByColumnAndRow(2, $inx, $r['MITM_ITMD1']);
            $sheet->setCellValueByColumnAndRow(2, $inx + 1, $r['SSO2_MDLCD']);
            $sheet->setCellValueByColumnAndRow(3, $inx, $r['MITM_STKUOM']);
            $sheet->setCellValueByColumnAndRow(4, $inx, $r['SISOQTY']);
            $sheet->setCellValueByColumnAndRow(5, $inx, $r['SSO2_SLPRC']);
            $sheet->setCellValueByColumnAndRow(6, $inx, $r['SISOQTY'] * $r['SSO2_SLPRC']);
            $inx += 2;
            $no++;
        }

        $sheet = $spreadsheet->createSheet();
        $sheet->setTitle('PACKINGLIST');
        $rspackinglist = $this->DELV_mod->select_packinglist_bydono($pid);
        $ar_grup_item = [];
        $ar_grup_item_ttl = [];
        $ASPFLAG = false;
        foreach ($rspackinglist as $r) {
            if (strpos($r['SI_ITMCD'], 'ASP') !== false) {
                $ASPFLAG = true;
            }
            if (!in_array($r['SI_ITMCD'], $ar_grup_item)) {
                $ar_grup_item[] = $r['SI_ITMCD'];
                $ar_grup_item_ttl[] = 1;
            } else {
                for ($h = 0; $h < count($ar_grup_item); $h++) {
                    if ($ar_grup_item[$h] == $r['SI_ITMCD']) {
                        $ar_grup_item_ttl[$h]++;
                        break;
                    }
                }
            }
        }
        foreach ($rspackinglist as &$r) {
            for ($h = 0; $h < count($ar_grup_item); $h++) {
                if ($ar_grup_item[$h] == $r['SI_ITMCD']) {
                    $r['TTLBARIS'] = $ar_grup_item_ttl[$h];
                    break;
                }
            }
        }
        unset($r);

        $sheet->setCellValueByColumnAndRow(7, 1, $hinv_date);
        $sheet->setCellValueByColumnAndRow(1, 2, $hinv_customer);
        $sheet->setCellValueByColumnAndRow(1, 3, $hinv_address);
        $sheet->setCellValueByColumnAndRow(7, 4, $hinv_inv);

        $inx = 7;
        $no = 0;
        $par_item = '';
        $dis_item = '';
        $dis_itemnm = '';
        $dis_no = '';
        $dis_qty = '';
        if ($ASPFLAG) {
            $rspickingres = $this->DELV_mod->select_serah_terima_asp($pid);
            foreach ($rspickingres as $r) {
                if ($par_item != $r['SI_ITMCD']) {
                    $par_item = $r['SI_ITMCD'];
                    $dis_item = $r['SI_ITMCD'];
                    $dis_itemnm = $r['MITM_ITMD1'];
                    $dis_qty = number_format($r['TTLBOX'], 0);
                    $no++;
                    $dis_no = $no;
                } else {
                    $dis_item = '';
                    $dis_itemnm = '';
                    $dis_no = '';
                    $dis_qty = '';
                }
                $sheet->setCellValueByColumnAndRow(1, $inx, $dis_no);
                if ($dis_itemnm != '') {
                    $sheet->setCellValueByColumnAndRow(2, $inx, $dis_itemnm);
                }
                if ($dis_item != '') {
                    $sheet->setCellValueByColumnAndRow(3, $inx, $dis_item);
                }
                $sheet->setCellValueByColumnAndRow(4, $inx, $dis_qty);
                $sheet->setCellValueByColumnAndRow(5, $inx, $r['MITM_NWG']);
                $sheet->setCellValueByColumnAndRow(6, $inx, $r['MITM_GWG']);
                $sheet->setCellValueByColumnAndRow(7, $inx, number_format($r['TTLBOX']) . " BOX (X) " . number_format($r['SISCN_SERQTY']));

                $inx += $r['TTLBARIS'] == 0 ? 4 : 1;
            }
        } else {
            foreach ($rspackinglist as $r) {
                if ($par_item != $r['SI_ITMCD']) {
                    $par_item = $r['SI_ITMCD'];
                    $dis_item = $r['SI_ITMCD'];
                    $dis_itemnm = $r['MITM_ITMD1'];
                    $dis_qty = number_format($r['TTLDLV'], 0);
                    $no++;
                    $dis_no = $no;
                } else {
                    $dis_item = '';
                    $dis_itemnm = '';
                    $dis_no = '';
                    $dis_qty = '';
                }
                $sheet->setCellValueByColumnAndRow(1, $inx, $dis_no);
                if ($dis_itemnm != '') {
                    $sheet->setCellValueByColumnAndRow(2, $inx, $dis_itemnm);
                }
                if ($dis_item != '') {
                    $sheet->setCellValueByColumnAndRow(3, $inx, $dis_item);
                }
                $sheet->setCellValueByColumnAndRow(4, $inx, $dis_qty);
                $sheet->setCellValueByColumnAndRow(5, $inx, $r['MITM_NWG']);
                $sheet->setCellValueByColumnAndRow(6, $inx, $r['MITM_GWG']);
                $sheet->setCellValueByColumnAndRow(7, $inx, number_format($r['TTLBOX']) . " BOX (X) " . number_format($r['SISCN_SERQTY']));
                $inx += $r['TTLBARIS'] == 0 ? 4 : 1;
            }
        }

        $sheet = $spreadsheet->createSheet();
        $sheet->setTitle('DO');
        $sheet->setCellValueByColumnAndRow(1, 1, $hinv_customer);
        $sheet->setCellValueByColumnAndRow(1, 2, $hinv_address);
        $sheet->setCellValueByColumnAndRow(7, 1, $pid);
        $sheet->setCellValueByColumnAndRow(7, 3, $hinv_date);
        $sheet->setCellValueByColumnAndRow(7, 5, 'BC ' . $hinv_bctype . ' : ' . $hinv_nopen);
        $sheet->setCellValueByColumnAndRow(7, 6, 'INV NO : ' . $hinv_inv);

        $ar_po = [];
        $ar_item = [];
        $ar_itemdesc = [];
        $ar_itemUM = [];
        $ar_qty = [];
        $rsdo = $this->DLVSO_mod->select_for_DO($pid);
        foreach ($rs as $r) {
            $isexist = false;
            for ($i = 0; $i < count($ar_item); $i++) {
                if (($r['SER_ITMID'] == $ar_item[$i]) && ($r['SISCN_DOCREFF'] == $ar_po[$i])) {
                    $isexist = true;
                    $ar_qty[$i] = $ar_qty[$i] + $r['SISCN_SERQTY'];
                    break;
                }
            }
            if (!$isexist) {
                $ar_item[] = $r['SER_ITMID'];
                $ar_itemdesc[] = $r['MITM_ITMD1'];
                $ar_itemUM[] = $r['MITM_STKUOM'];
                $ar_qty[] = $r['SISCN_SERQTY'];
                $ar_po[] = $r['SISCN_DOCREFF'];
            }
        }
        $ttlrows = count($ar_item);
        $tempItem = '';
        $nourutDO = 0;
        if (count($rsdo) > 0) {
            $inx = 8;
            foreach ($rsdo as $n) {
                if ($tempItem != $n['DLVSO_ITMCD']) {
                    $nourutDO++;
                    $tempItem = $n['DLVSO_ITMCD'];
                    $ItemDis = $tempItem;
                    $ItemDis2 = $n['MITM_ITMD1'];
                    $nourutDODis = $nourutDO;
                } else {
                    $ItemDis = '';
                    $ItemDis2 = '';
                    $nourutDODis = '';
                }
                $sheet->setCellValueByColumnAndRow(1, $inx, $nourutDODis);
                $sheet->setCellValueByColumnAndRow(2, $inx, $ItemDis2);
                $sheet->setCellValueByColumnAndRow(3, $inx, $ItemDis);
                $sheet->setCellValueByColumnAndRow(4, $inx, $n['PLOTQTY']);
                $sheet->setCellValueByColumnAndRow(5, $inx, $n['MITM_STKUOM']);
                $sheet->setCellValueByColumnAndRow(6, $inx, "PO NO : " . $n['DLVSO_CPONO']);
                $sheet->setCellValueByColumnAndRow(7, $inx, "OS : " . number_format($n['PLOTQTMAIN']) . "/" . number_format($n['ORDQT']));
                $inx++;
            }
        } else {
            $inx = 8;
            for ($i = 0; $i < $ttlrows; $i++) {
                $sheet->setCellValueByColumnAndRow(1, $inx, ($i + 1));
                $sheet->setCellValueByColumnAndRow(2, $inx, $ar_itemdesc[$i]);
                $sheet->setCellValueByColumnAndRow(3, $inx, $ar_item[$i]);
                $sheet->setCellValueByColumnAndRow(4, $inx, $ar_qty[$i]);
                $sheet->setCellValueByColumnAndRow(5, $inx, $ar_itemUM[$i]);
                $sheet->setCellValueByColumnAndRow(6, $inx, "PO NO : " . $ar_po[$i]);
                $inx++;
            }
        }

        $dodis = str_replace("/", "#", $pid);
        $stringjudul = $dodis;
        $writer = new Xlsx($spreadsheet);
        $filename = $stringjudul; //save our workbook as this file name

        header('Content-Type: application/vnd.ms-excel');
        header('Content-Disposition: attachment;filename="' . $filename . '.xlsx"');
        header('Cache-Control: max-age=0');
        $writer->save('php://output');
    }
    public function doc_as_omi_xls()
    {
        $pid = '';
        if (isset($_COOKIE["CKPDLV_NO"])) {
            $pid = $_COOKIE["CKPDLV_NO"];
        } else {
            exit('nothing to be printed');
        }
        $rs = $this->DELV_mod->select_det_byid_p($pid);
        //start of data base
        $hinv_date = '';
        $hdlv_date = '';
        $hinv_inv = '';
        $hinv_customer = '';

        $hinv_bctype = '';
        $hinv_noaju = '';

        foreach ($rs as $r) {
            $hinv_date = $r['DLV_INVDT'];
            $hinv_date = date_create($hinv_date);
            $hinv_date = date_format($hinv_date, "d/m/Y");
            $hdlv_date = $r['DLV_DATE'];
            $hdlv_date = date_create($hdlv_date);
            $hdlv_date = date_format($hdlv_date, "d/m/Y");
            $hinv_inv = $r['DLV_INVNO'];
            $hinv_customer = $r['MDEL_DELNM'];
            $hinv_bctype = $r['DLV_BCTYPE'];
            $hinv_noaju = $r['DLV_NOAJU'];
            break;
        }

        $spreadsheet = new Spreadsheet();
        $sheet = $spreadsheet->getActiveSheet();
        $sheet->setTitle('Create_Doc');
        $sheet->setCellValueByColumnAndRow(1, 2, 'Doc Date');
        $sheet->setCellValueByColumnAndRow(2, 2, 'Do No');
        $sheet->setCellValueByColumnAndRow(3, 2, 'BC Type');
        $sheet->setCellValueByColumnAndRow(4, 2, 'BC No');
        $sheet->setCellValueByColumnAndRow(5, 2, 'PO Slip No');
        $sheet->setCellValueByColumnAndRow(6, 2, 'Seq');
        $sheet->setCellValueByColumnAndRow(7, 2, 'Qty');
        $sheet->setCellValueByColumnAndRow(8, 2, 'Invoice No');
        $sheet->setCellValueByColumnAndRow(9, 2, 'Faktur Pajak');
        $sheet->setCellValueByColumnAndRow(10, 2, 'Packing List No');
        $sheet->setCellValueByColumnAndRow(11, 2, 'Net (Kg)');
        $sheet->setCellValueByColumnAndRow(12, 2, 'Gross (Kg)');
        $sheet->setCellValueByColumnAndRow(13, 2, 'Package');
        $sheet->setCellValueByColumnAndRow(14, 2, 'DO NO');
        $sheet->setCellValueByColumnAndRow(15, 2, 'Item No');
        $sheet->setCellValueByColumnAndRow(16, 2, 'Item Name');
        $sheet->setCellValueByColumnAndRow(17, 2, 'Qty');
        $sheet->setCellValueByColumnAndRow(18, 2, 'Price');
        $sheet->setCellValueByColumnAndRow(19, 2, 'Curr');
        $sheet->setCellValueByColumnAndRow(20, 2, 'Amount');
        $sheet->setCellValueByColumnAndRow(21, 2, 'Check');
        $sheet->setCellValueByColumnAndRow(22, 2, 'Backlog');

        $rsinv = $this->setPriceRS(base64_encode($pid));
        $rsresume = [];
        $rsmultiprice = [];
        foreach ($rsinv as &$k) {
            if ($k['SISO_SOLINE'] == 'X') {
                $rs_mst_price = $this->XSO_mod->select_latestprice($k['SI_BSGRP'], $k['SI_CUSCD'], "'" . $k['SSO2_MDLCD'] . "'");
                foreach ($rs_mst_price as $r) {
                    $k['SSO2_SLPRC'] = substr($r['MSPR_SLPRC'], 0, 1) == '.' ? '0' . $r['MSPR_SLPRC'] : $r['MSPR_SLPRC'];
                    $k['CIF'] = $k['SSO2_SLPRC'] * $k['SISOQTY'];
                }
            }
            $isfound = false;
            foreach ($rsresume as &$n) {
                if ($n['RSI_ITMCD'] == $k['SSO2_MDLCD'] && $n['RSSO2_SLPRC'] != $k['SSO2_SLPRC']) {
                    $n['RCOUNT']++;
                    $isfound = true;
                    break;
                }
            }
            unset($n);

            if (!$isfound) {
                $rsresume[] = [
                    'RSI_ITMCD' => $k['SSO2_MDLCD'], 'RSSO2_SLPRC' => $k['SSO2_SLPRC'], 'RCOUNT' => 1,
                ];
            }
        }
        unset($k);
        foreach ($rsresume as $k) {
            if ($k['RCOUNT'] > 1) {
                $rsmultiprice[] = $k;
            }
        }

        if (count($rsmultiprice) > 0) {
            if (strpos(strtolower($hinv_customer), 'epson') !== false) {
                $myar[] = ["cd" => "0", "msg" => "Multi price detected please, click 'Price Detail' to confirm "];
                die('{"status":' . json_encode($myar) . ',"data":' . json_encode($rsinv) . ',"data2":' . json_encode($rsmultiprice) . '}');
            }
        }

        $rspackinglist = $this->DELV_mod->select_packinglist_bydono($pid);
        $rspl = [];
        foreach ($rspackinglist as $r) {
            $isfound = false;
            foreach ($rspl as &$p) {
                if ($r['SI_ITMCD'] == $p['ITEM']) {
                    $isfound = true;
                    $p['QTY'] += $r['SISCN_SERQTY'];
                    $p['NWG'] += $r['MITM_NWG'];
                    $p['GWG'] += $r['MITM_GWG'];
                    $p['BOX'] += $r['TTLBOX'];
                    break;
                }
            }
            if (!$isfound) {
                $rspl[] = [
                    'ITEM' => $r['SI_ITMCD'],
                    'QTY' => $r['SISCN_SERQTY'],
                    'NWG' => $r['MITM_NWG'],
                    'GWG' => $r['MITM_GWG'],
                    'BOX' => $r['TTLBOX'],
                ];
            }
            unset($p);
        }

        $inx = 3;
        $rsku = $this->DELV_mod->select_shipping_for_mega($pid);
        foreach ($rsku as $r) {
            $sheet->setCellValueByColumnAndRow(1, $inx, $hinv_date);
            $sheet->setCellValueByColumnAndRow(2, $inx, $pid);
            $sheet->setCellValueByColumnAndRow(3, $inx, $hinv_bctype);
            $sheet->setCellValueByColumnAndRow(4, $inx, $hinv_noaju);
            $sheet->setCellValueByColumnAndRow(5, $inx, $r['DLVPRC_CPO']);

            $sheet->setCellValueByColumnAndRow(7, $inx, $r['PLTQTY']);
            $sheet->setCellValueByColumnAndRow(8, $inx, $hinv_inv);
            $sheet->setCellValueByColumnAndRow(10, $inx, $pid);
            $nw = $gw = $box = '';
            foreach ($rspl as $p) {
                if ($r['SER_ITMID'] == $p['ITEM']) {
                    $nw = $p['NWG'];
                    $gw = $p['GWG'];
                    $box = $p['BOX'];
                    break;
                }
            }
            $sheet->setCellValueByColumnAndRow(11, $inx, $nw);
            $sheet->setCellValueByColumnAndRow(12, $inx, $gw);
            $sheet->setCellValueByColumnAndRow(13, $inx, $box);
            $inx++;
        }
        foreach (range('A', 'V') as $columnID) {
            $sheet->getColumnDimension($columnID)
                ->setAutoSize(true);
        }

        $sheet->getStyle('A3:E27')->getFill()->setFillType(\PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID)
            ->getStartColor()->setRGB('00ff00');
        $sheet->getStyle('G3:M27')->getFill()->setFillType(\PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID)
            ->getStartColor()->setRGB('00ff00');
        $sheet->getStyle('A2:V28')->getBorders()->getAllBorders()->setBorderStyle(\PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN);

        $dodis = str_replace("/", "#", $pid);
        $stringjudul = $dodis;
        $writer = new Xls($spreadsheet);
        $filename = $stringjudul . " OMI"; //save our workbook as this file name

        header('Content-Type: application/vnd.ms-excel');
        header('Content-Disposition: attachment;filename="' . $filename . '.xls"');
        header('Cache-Control: max-age=0');
        $writer->save('php://output');
    }

    public function printdocs_rm()
    {
        $pid = $currency = $tglaju = '';
        $pforms = '';
        if (isset($_COOKIE["CKPDLV_NO"])) {
            $pid = $_COOKIE["CKPDLV_NO"];
            $currency = $_COOKIE["CKPDLV_CURRENCY"];
            $tglaju = $_COOKIE["CKPDLV_TGLAJU"];
        } else {
            exit('nothing to be printed');
        }
        if (isset($_COOKIE["CKPDLV_FORMS"])) {
            $pforms = $_COOKIE["CKPDLV_FORMS"];
        } else {
            exit('Please select at least one document type');
        }
        $pdf = new PDF_Code39e128('P', 'mm', 'A4');
        $pdf->SetFont('Arial', '', 9);
        if (substr($pforms, 1, 1) == '1') {
            //INVOICE
            $Y_adj = 20;
            $MAX_INVD_PERPAGE = 6;

            $rsrmdoc = $this->DLVRMDOC_mod->select_invoice($pid);
            $rsrmdocFromSO = $this->DLVRMSO_mod->select_invoice($pid);
            $rs_rcv = $this->RCV_mod->select_for_rmrtn_bytxid($pid);
            $MultipliedNumber = 1;
            $shouldRound = false;
            $isYEN = false;
            foreach ($rs_rcv as $a) {
                if ($a['MSUP_SUPCR'] != "RPH" && $currency == "RPH") {
                    $shouldRound = true;
                    $rscurrency = $this->MEXRATE_mod->selectfor_posting($tglaju, $a['MSUP_SUPCR']);
                    if (count($rscurrency) == 0) {
                        die('exchange rate is required ' . $tglaju);
                    } else {
                        foreach ($rscurrency as $n) {
                            $MultipliedNumber = $n->MEXRATE_VAL;
                            break;
                        }
                    }
                } elseif ($currency == "YEN") {
                    $isYEN = true;
                }

                break;
            }
            $rsfixINV = [];
            $description = "";
            foreach ($rsrmdoc as &$r) {
                $description = $r['DLV_DSCRPTN'];
                $r['PLOTQT'] = 0;
                foreach ($rs_rcv as &$x) {
                    if (
                        $r['DLVRMDOC_ITMID'] == $x['RCV_ITMCD']
                        && $r['DLVRMDOC_AJU'] == $x['RCV_RPNO']
                        && $r['DLVRMDOC_DO'] == $x['RCV_DONO']
                        && $x['RCV_QTY'] > 0
                        && $r['PLOTQT'] != $r['ITMQT']
                    ) {
                        $reqbal = $r['ITMQT'] - $r['PLOTQT'];
                        $useqt = 0;
                        if ($reqbal > $x['RCV_QTY']) {
                            $useqt = $x['RCV_QTY'];
                            $r['PLOTQT'] += $x['RCV_QTY'];
                            $x['RCV_QTY'] = 0;
                        } else {
                            $useqt = $reqbal;
                            $r['PLOTQT'] += $reqbal;
                            $x['RCV_QTY'] -= $reqbal;
                        }
                        $rsfixINV[] = [
                            'ITMQT' => $useqt, 'DLVRMDOC_PRPRC' => $r['DLVRMDOC_PRPRC'], 'DLV_ITMD1' => $r['DLV_ITMD1'], 'DLVRMDOC_TYPE' => $r['DLVRMDOC_TYPE'], 'MITM_STKUOM' => $r['MITM_STKUOM'], 'DLVRMDOC_PRPRC' => $r['DLVRMDOC_PRPRC'], 'DLVRMDOC_ITMID' => $r['DLVRMDOC_ITMID'], 'MITM_ITMCDCUS' => $r['MITM_ITMCDCUS'],
                        ];
                        if ($r['ITMQT'] == $r['PLOTQT']) {
                            break;
                        }
                    }
                }
                unset($x);
            }
            unset($r);

            if (count($rsrmdoc) && count($rsrmdocFromSO) <= 0) {
                $h_delnm = '';
                $h_deladdress = '';
                $h_invno = '';
                $hinv_currency = '';
                foreach ($rsrmdoc as $r) {
                    $h_delnm = $r['MDEL_ZNAMA'];
                    $h_deladdress = $r['MDEL_ADDRCUSTOMS'];
                    $h_invno = $r['DLV_INVNO'];
                    $hinv_currency = $r['MCUS_CURCD'];
                    $hinv_date = $r['DLV_INVDT'];
                    $hinv_date = date_create($hinv_date);
                    $hinv_date = date_format($hinv_date, "d/m/Y");
                    $hinv_nopen = $r['DLV_NOPEN'];
                    break;
                }
                $pdf->AddPage();
                $pdf->SetAutoPageBreak(true, 1);
                $pdf->SetMargins(0, 0);
                $ttlbrs = 1;
                $pdf->SetFont('Arial', '', 9);
                $pdf->Text(144, 67, 'Nopen : ' . $hinv_nopen);
                $pdf->Text(162, 78 + 5, $h_invno);
                $pdf->Text(30, 78 + 5, $hinv_date);
                $pdf->SetXY(10, 80.9 + 15);
                $pdf->MultiCell(85.67, 4, trim($h_delnm), 0);
                $pdf->SetXY(10, 80.9 + 4 + $Y_adj);
                $pdf->MultiCell(85.67, 4, trim($h_deladdress), 0);
                $pdf->Text(110, 133 + 10, $hinv_currency);
                $curY = 152 + 15;

                $no = 1;
                $ttlqty_ = 0;
                $ttlamount_ = 0;
                $isDecimal = false;
                foreach ($rsfixINV as $r) {
                    $tempItem = $r['MITM_ITMCDCUS'] != '' ? $r['MITM_ITMCDCUS'] : $r['DLVRMDOC_ITMID'];
                    $ItemDis = $tempItem;
                    switch (trim($r['MITM_STKUOM'])) {
                        case 'GMS':
                            $uom = 'GRM';
                            $isDecimal = true;
                            break;
                        case 'KG':
                            $uom = 'KGM';
                            $isDecimal = true;
                            break;
                        default:
                            $uom = $r['MITM_STKUOM'];
                            $isDecimal = false;
                    }
                    if ($shouldRound) {
                        $amount_ = $r['ITMQT'] * round($r['DLVRMDOC_PRPRC'] * $MultipliedNumber);
                        $perprice_ = round($r['DLVRMDOC_PRPRC'] * $MultipliedNumber);
                    } else {
                        $amount_ = $r['ITMQT'] * ($r['DLVRMDOC_PRPRC'] * $MultipliedNumber);
                        $perprice_ = ($r['DLVRMDOC_PRPRC'] * $MultipliedNumber);
                    }
                    if ($ttlbrs > $MAX_INVD_PERPAGE) {
                        $pdf->AddPage();
                        $pdf->SetAutoPageBreak(true, 1);
                        $pdf->SetMargins(0, 0);
                        $ttlbrs = 1;
                        $pdf->SetFont('Arial', '', 9);
                        $pdf->Text(144, 67, 'Nopen : ' . $hinv_nopen);
                        $pdf->Text(162, 78 + 5, $h_invno);
                        $pdf->Text(30, 78 + 5, $hinv_date);
                        $pdf->SetXY(10, 80.9 + 15);
                        $pdf->MultiCell(85.67, 4, trim($h_delnm), 0);
                        $pdf->SetXY(10, 80.9 + 4 + $Y_adj);
                        $pdf->MultiCell(85.67, 4, trim($h_deladdress), 0);
                        $pdf->Text(110, 133 + 10, $hinv_currency);
                        $curY = 152 + 15;
                    }
                    $pdf->SetXY(10, $curY - 3);
                    $pdf->Cell(27, 4, $no, 0, 0, 'L');
                    $pdf->SetXY(43, $curY - 3);
                    $pdf->MultiCell(51, 4, $r['DLV_ITMD1'], 0, 'L');
                    $YExtra_candidate = $pdf->GetY();
                    if ($YExtra_candidate != ($curY - 3)) {
                        $additionalRow = 1;
                        $YExtra = $YExtra = $YExtra_candidate - ($curY - 3) - 4;
                    } else {
                        $YExtra = 0;
                        $additionalRow = 0;
                    }
                    $pdf->SetFont('Arial', '', 9);
                    $pdf->Text(45, $curY + 4 + $YExtra, $tempItem);
                    $pdf->Text(45, $curY + 8 + $YExtra, trim($r['DLVRMDOC_TYPE']));
                    $pdf->Text(100, $curY, $uom);
                    $pdf->SetXY(110, $curY - 3);
                    if ($isDecimal) {
                        $pdf->Cell(20.55, 4, number_format($r['ITMQT'], 2), 0, 0, 'R');
                    } else {
                        $pdf->Cell(20.55, 4, number_format($r['ITMQT']), 0, 0, 'R');
                    }
                    if ($isYEN) {
                        $pdf->SetXY(138, $curY - 3);
                        $pdf->Cell(17.5, 4, number_format($perprice_, 0), 0, 0, 'R');
                        $pdf->SetXY(155, $curY - 3);
                        $pdf->Cell(41.56, 4, number_format($amount_, 0), 0, 0, 'R');
                        $ttlamount_ += number_format($amount_, 0);
                    } else {
                        $pdf->SetXY(138, $curY - 3);
                        if ($hinv_currency === 'RPH') {
                            $pdf->Cell(17.5, 4, number_format($perprice_, 0), 0, 0, 'R');
                        } else {
                            $pdf->Cell(17.5, 4, number_format($perprice_, 5), 0, 0, 'R');
                        }
                        $pdf->SetXY(155, $curY - 3);
                        $pdf->Cell(41.56, 4, number_format($amount_, 2), 0, 0, 'R');
                        $ttlamount_ += str_replace(',', '', number_format($amount_, 2));
                    }

                    $no++;
                    $curY += (15 + $YExtra);
                    $ttlbrs++;
                    $ttlbrs += $additionalRow;
                    $ttlqty_ += $r['ITMQT'];
                }
                $pdf->SetXY(115, 240 + 13);
                if ($isDecimal) {
                    $pdf->Cell(20.55, 4, number_format($ttlqty_, 2), 0, 0, 'R');
                } else {
                    $pdf->Cell(20.55, 4, number_format($ttlqty_, 0), 0, 0, 'R');
                }
                $pdf->SetXY(155, 240 + 13);
                $pdf->Cell(41.56, 4, number_format($ttlamount_, 2), 0, 0, 'R');
                if ($description !== "DIJUAL") {
                    $pdf->Text(35, 240 + 25, "Non Commercial Value For Customs Purpose Only");
                }
            } else {
                $rsrmdoc = $rsrmdocFromSO;
                if (count($rsrmdoc)) {
                    foreach ($rsrmdoc as $r) {
                        $h_delnm = $r['MDEL_ZNAMA'];
                        $h_deladdress = $r['MDEL_ADDRCUSTOMS'];
                        $h_invno = $r['DLV_INVNO'];
                        $hinv_currency = $r['MCUS_CURCD'];
                        $hinv_date = $r['DLV_INVDT'];
                        $hinv_date = date_create($hinv_date);
                        $hinv_date = date_format($hinv_date, "d/m/Y");
                        $hinv_nopen = $r['DLV_NOPEN'];
                        break;
                    }
                    $pdf->AddPage();
                    $pdf->SetAutoPageBreak(true, 1);
                    $pdf->SetMargins(0, 0);
                    $ttlbrs = 1;
                    $pdf->SetFont('Arial', '', 8);
                    $pdf->Text(144, 67, 'Nopen : ' . $hinv_nopen);
                    $pdf->Text(162, 78 + 5, $h_invno);
                    $pdf->Text(30, 78 + 5, $hinv_date);
                    $pdf->SetXY(13, 80.9 + 15);
                    $pdf->MultiCell(85.67, 4, trim($h_delnm), 0);
                    $pdf->SetXY(13, 80.9 + 4 + $Y_adj);
                    $pdf->MultiCell(85.67, 4, trim($h_deladdress), 0);
                    $pdf->Text(110, 133 + 10, $hinv_currency);
                    $curY = 152 + 20;
                    $no = 1;
                    foreach ($rsrmdoc as $r) {
                        if ($ttlbrs > $MAX_INVD_PERPAGE) {
                            $pdf->AddPage();
                            $pdf->SetAutoPageBreak(true, 1);
                            $pdf->SetMargins(0, 0);
                            $ttlbrs = 1;
                            $pdf->SetFont('Arial', '', 8);
                            $pdf->Text(144, 67, 'Nopen : ' . $hinv_nopen);
                            $pdf->Text(162, 78 + 5, $h_invno);
                            $pdf->Text(30, 78 + 5, $hinv_date);
                            $pdf->SetXY(13, 80.9 + 15);
                            $pdf->MultiCell(85.67, 4, trim($h_delnm), 0);
                            $pdf->SetXY(13, 80.9 + 4 + $Y_adj);
                            $pdf->MultiCell(85.67, 4, trim($h_deladdress), 0);
                            $pdf->Text(110, 133 + 10, $hinv_currency);
                            $curY = 152 + 20;
                        }
                        $pdf->SetXY(14, $curY - 3);
                        $pdf->Cell(27, 4, $no, 0, 0, 'L');
                        $pdf->SetXY(43, $curY - 3);
                        $ttlwidth = $pdf->GetStringWidth(trim($r['DLV_ITMD1']));
                        if ($ttlwidth > 51) {
                            $ukuranfont = 7.5;
                            while ($ttlwidth > 50) {
                                $pdf->SetFont('Arial', '', $ukuranfont);
                                $ttlwidth = $pdf->GetStringWidth(trim($r['DLV_ITMD1']));
                                $ukuranfont = $ukuranfont - 0.5;
                            }
                        }
                        $pdf->Cell(51, 4, $r['DLV_ITMD1'], 0, 0, 'L');
                        $pdf->SetFont('Arial', '', 8);
                        $pdf->Text(45, $curY + 4, "(" . trim($r['DLVRMSO_ITMID']) . ")");
                        $pdf->Text(45, $curY + 8, trim($r['DLV_ITMSPTNO']));
                        $pdf->Text(100, $curY, $r['MITM_STKUOM']);
                        $pdf->SetXY(115, $curY - 3);
                        $pdf->Cell(20.55, 4, number_format($r['ITMQT']), 0, 0, 'R');
                        $pdf->SetXY(137, $curY - 3);
                        $pdf->Cell(17.5, 4, substr($r['DLVRMSO_PRPRC'], 0, 1) == '.' ? number_format('0' . $r['DLVRMSO_PRPRC'], 5) : number_format($r['DLVRMSO_PRPRC'], 5), 0, 0, 'R');
                        $pdf->SetXY(155, $curY - 3);
                        $pdf->Cell(41.56, 4, number_format($r['ITMQT'] * $r['DLVRMSO_PRPRC'], 2), 0, 0, 'R');
                        $no++;
                        $curY += 15;
                        $ttlbrs++;
                    }
                } else {
                    die('posting first to get price');
                }
            }
        }
        if (substr($pforms, -1) == '1') {
            //PACKING LIST
            $rs = $this->DELV_mod->select_for_pkg_rm_rtn($pid);
            $h_delnm = '';
            $h_deladdress = '';
            foreach ($rs as $r) {
                $hinv_date = $r['DLV_INVDT'];
                $hinv_date = date_create($hinv_date);
                $hinv_date = date_format($hinv_date, "d/m/Y");
                $h_delnm = $r['MDEL_ZNAMA'];
                $h_deladdress = $r['MDEL_ADDRCUSTOMS'];
                $h_invno = $r['DLV_INVNO'];
                break;
            }
            if (count($rs) == 0) {
                die('Please entry packing list first');
            }
            $pdf->AddPage();
            $pdf->Text(155, 59 + 8, $hinv_date);
            $pdf->Text(11, 70 + 10, $h_delnm);
            $pdf->SetXY(10, 71 + 10);
            $pdf->MultiCell(105, 4, $h_deladdress, 0, 'L');
            $pdf->Text(140, 91 + 8, $h_invno);
            $curY = 110 + 15;
            $TTLQTY = 0;
            $TTLNW = 0;
            $TTLGW = 0;
            $nom = 1;
            foreach ($rs as $r) {
                switch (trim($r['MITM_STKUOM'])) {
                    case 'GMS':
                        $isDecimal = true;
                        break;
                    case 'KG':
                        $isDecimal = true;
                        break;
                    default:
                        $uom = $r['MITM_STKUOM'];
                        $isDecimal = false;
                }
                if ($curY > 200) {
                    $pdf->AddPage();
                    $pdf->Text(155, 59 + 10, $hinv_date);
                    $pdf->Text(11, 70 + 10, $h_delnm);
                    $pdf->SetXY(10, 71 + 10);
                    $pdf->MultiCell(105, 4, $h_deladdress, 0, 'L');
                    $pdf->Text(140, 91 + 8, $h_invno);
                    $curY = 110 + 15;
                }
                $ITEMDESC = $r['DLV_ITMD1'];
                $ITEMSPTNO = $r['DLV_PKG_ITMTYPE'];

                $pdf->SetXY(6, $curY - 3);
                $pdf->Cell(21, 4, $nom, 0, 0, 'C');
                $pdf->SetXY(28, $curY - 3);
                $pdf->MultiCell(63.73, 4, $ITEMDESC, 0, 'L');
                $YExtra_candidate = $pdf->GetY();
                $YExtra = $YExtra_candidate != ($curY - 3) ? $YExtra = $YExtra_candidate - ($curY - 3) - 4 : 0;
                $pdf->SetFont('Arial', '', 9);
                $pdf->SetXY(28, $curY + 2 + $YExtra);
                $pdf->Cell(63.73, 4, $r['DLV_PKG_ITM'] != $r['MITM_ITMCDCUS'] ? $r['MITM_ITMCDCUS'] : $r['DLV_PKG_ITM'], 0, 0, 'L');
                $pdf->SetXY(28, $curY + 6 + $YExtra);
                $pdf->Cell(63.73, 4, $ITEMSPTNO, 0, 0, 'L');
                $pdf->SetXY(92, $curY - 3);
                if ($isDecimal) {
                    $pdf->Cell(16.63, 4, number_format($r['DLV_PKG_QTY'], 2), 0, 0, 'R');
                } else {
                    $pdf->Cell(16.63, 4, number_format($r['DLV_PKG_QTY']), 0, 0, 'R');
                }
                $pdf->SetXY(110, $curY - 3);
                $pdf->Cell(24.71, 4, $r['DLV_PKG_NWG'] * 1 == 0 ? '' : number_format($r['DLV_PKG_NWG'], 2), 0, 0, 'R');
                $pdf->SetXY(137.07, $curY - 3);
                $pdf->Cell(29.33, 4, $r['DLV_PKG_GWG'] * 1 == 0 ? '' : number_format($r['DLV_PKG_GWG'], 2), 0, 0, 'R');
                $pdf->SetXY(166.4, $curY - 3);
                $pdf->Cell(31.17, 4, $r['DLV_PKG_MEASURE'], 0, 0, 'R');
                $curY += 15 + $YExtra;
                $TTLQTY += $r['DLV_PKG_QTY'];
                $TTLNW += $r['DLV_PKG_NWG'];
                $TTLGW += $r['DLV_PKG_GWG'];
                $nom++;
            }
            $pdf->SetXY(32, 235);
            $pdf->Cell(63.73, 4, "Total", 0, 0, 'L');
            $pdf->SetXY(92, 235);
            if ($isDecimal) {
                $pdf->Cell(16.63, 4, number_format($TTLQTY, 2), 0, 0, 'R');
            } else {
                $pdf->Cell(16.63, 4, number_format($TTLQTY), 0, 0, 'R');
            }
            $pdf->Cell(24.71, 4, number_format($TTLNW, 2), 0, 0, 'R');
            $pdf->Cell(29.33, 4, number_format($TTLGW, 2), 0, 0, 'R');
        }
        if (substr($pforms, 0, 1) == '1') {
            //DELIVERY ORDER
            $rs_do = $this->DELV_mod->select_for_do_rm_rtn_v1($pid);
            $rs_xbc = $this->RCV_mod->select_for_rmrtn_bytxid($pid);
            $h_delnm = '';
            $h_deladdress = '';
            $h_description = '';
            $h_bctype = '';
            $h_nopen = '';
            $h_invno = '';
            $hdlv_date = '';
            $tempItem = '';
            $ItemDis = '';
            $ItemDis2 = '';
            $nourutDO = 0;
            $ttlbaris = 1;
            $ttldoqty = 0;
            foreach ($rs_do as $r) {
                $hdlv_date = $r['DLV_DATE'];
                $hdlv_date = date_create($hdlv_date);
                $hdlv_date = date_format($hdlv_date, "d/m/Y");
                $h_delnm = $r['MDEL_ZNAMA'];
                $h_deladdress = $r['MDEL_ADDRCUSTOMS'];
                $h_description = $r['DLV_DSCRPTN'];
                $h_bctype = $r['DLV_BCTYPE'];
                $h_nopen = $r['DLV_NOPEN'];
                $h_invno = $r['DLV_INVNO'];
                break;
            }
            $rsfix = [];
            $isDecimal = false;
            foreach ($rs_do as &$r) {
                $r['PLOTQT'] = 0;
                foreach ($rs_xbc as &$x) {
                    if (
                        $r['DLVRMDOC_ITMID'] == $x['RCV_ITMCD']
                        && $r['DLVRMDOC_AJU'] == $x['RCV_RPNO']
                        && $r['DLVRMDOC_DO'] == $x['RCV_DONO']
                        && $x['RCV_QTY'] > 0
                        && $r['PLOTQT'] != $r['DLVRMDOC_ITMQT']
                    ) {
                        $reqbal = $r['DLVRMDOC_ITMQT'] - $r['PLOTQT'];
                        $useqt = 0;
                        if ($reqbal > $x['RCV_QTY']) {
                            $useqt = $x['RCV_QTY'];
                            $r['PLOTQT'] += $x['RCV_QTY'];
                            $x['RCV_QTY'] = 0;
                        } else {
                            $useqt = $reqbal;
                            $r['PLOTQT'] += $reqbal;
                            $x['RCV_QTY'] -= $reqbal;
                        }
                        switch (trim($r['MITM_STKUOM'])) {
                            case 'GM':
                                $uom = 'GRM';
                                $isDecimal = true;
                                break;
                            case 'KG':
                                $uom = 'KGM';
                                $isDecimal = true;
                                break;
                            default:
                                $uom = $r['MITM_STKUOM'];
                                $isDecimal = false;
                        }
                        $rsfix[] = [
                            'DLVRMDOC_ITMID' => $r['DLVRMDOC_ITMID'], 'DLV_ITMD1' => $r['DLV_ITMD1'], 'MITM_ITMD1' => $r['MITM_ITMD1'], 'DLV_ITMSPTNO' => $r['DLV_ITMSPTNO'], 'MITM_SPTNO' => $r['MITM_SPTNO'], 'DLVRMDOC_ITMQT' => $r['DLVRMDOC_ITMQT'], 'DLVRMDOC_AJU' => $r['DLVRMDOC_AJU'], 'DLVRMDOC_NOPEN' => $r['DLVRMDOC_NOPEN'], 'DLVRMDOC_ITMQT' => $useqt, 'MITM_STKUOM' => $uom, 'RCV_BCDATE' => $x['RCV_BCDATE'], 'RCV_BCTYPE' => $x['RCV_BCTYPE'], 'MITM_ITMCDCUS' => $r['MITM_ITMCDCUS'], 'TYPE' => $r['DLVRMDOC_TYPE'],
                        ];
                        if ($r['DLVRMDOC_ITMQT'] == $r['PLOTQT']) {
                            break;
                        }
                    }
                }
                unset($x);
            }
            unset($r);
            $pdf->AddPage();
            $pdf->SetAutoPageBreak(true, 1);
            $pdf->SetMargins(0, 0);
            $pdf->SetXY(48, 63);
            $pdf->MultiCell(76.04, 4, trim($h_delnm) . " \n" . $h_deladdress, 0);
            $pdf->SetXY(155, 62);
            $pdf->Cell(31.17, 4, $pid, 0, 0, 'L');
            $pdf->SetXY(155, 60 + 5 + 3);
            $pdf->Cell(31.17, 4, $hdlv_date, 0, 0, 'L');

            #BIG REMARK
            $pdf->SetXY(100, 203);
            $pdf->SetAlpha(1);
            $pdf->SetFont('Arial', 'B', 13);
            $_textwidth = $pdf->GetStringWidth($h_description) + 3;
            if ($h_description == 'DIJUAL') {
            } else {
                $pdf->Cell($_textwidth, 7, $h_description, 1, 0, 'L');
            }
            $pdf->SetAlpha(1);
            $pdf->SetFont('Arial', '', 9);
            #END

            $pdf->SetXY(155, 70 + 10);
            $pdf->Cell(31.17, 4, "BC " . $h_bctype . " : " . $h_nopen, 0, 0, 'L');
            $pdf->SetXY(155, 75 + 10);
            $pdf->Cell(31.17, 4, "INV NO : " . $h_invno, 0, 0, 'L');
            $curY = 93 + 20;
            $pdf->SetFont('Arial', '', 9);
            $UM = '';
            $a_stkuom = [];
            foreach ($rsfix as $r) {
                $nourutDO++;
                $tempItem = $r['MITM_ITMCDCUS'] != '' ? $r['MITM_ITMCDCUS'] : $r['DLVRMDOC_ITMID'];
                $ItemDis = $tempItem;
                $ItemDis2 = rtrim($r['DLV_ITMD1']);
                $nourutDODis = $nourutDO;
                if ($ttlbaris > 6) {
                    $pdf->AddPage();
                    $pdf->SetAutoPageBreak(true, 1);
                    $pdf->SetMargins(0, 0);
                    $pdf->SetXY(48, 63);
                    $pdf->MultiCell(76.04, 4, trim($h_delnm) . " \n" . $h_deladdress, 0);
                    $pdf->SetXY(155, 63);
                    $pdf->Cell(31.17, 4, $pid, 0, 0, 'L');
                    $pdf->SetXY(155, 60 + 5 + 5);
                    $pdf->Cell(31.17, 4, $hdlv_date, 0, 0, 'L');

                    #BIG REMARK
                    $pdf->SetXY(100, 203);
                    $pdf->SetAlpha(0.5);
                    $pdf->SetFont('Arial', 'B', 15);
                    $_textwidth = $pdf->GetStringWidth($h_description) + 3;
                    if ($h_description == 'DIJUAL') {
                    } else {
                        $pdf->Cell($_textwidth, 7, $h_description, 1, 0, 'L');
                    }
                    $pdf->SetAlpha(1);
                    $pdf->SetFont('Arial', '', 9);
                    #END

                    $pdf->SetXY(155, 70 + 10);
                    $pdf->Cell(31.17, 4, "BC " . $h_bctype . " : " . $h_nopen, 0, 0, 'L');
                    $pdf->SetXY(155, 75 + 10);
                    $pdf->Cell(31.17, 4, "INV NO : " . $h_invno, 0, 0, 'L');
                    $curY = 93 + 20;
                    $ttlbaris = 1;
                }

                $xbcdate = date_create($r['RCV_BCDATE']);
                $xbcdate = date_format($xbcdate, "d-M-Y");
                $pdf->SetXY(10, $curY);
                $pdf->Cell(14.75, 4, $nourutDODis, 0, 0, 'C');

                $ttlwidth = $pdf->GetStringWidth(trim($ItemDis2));
                if ($ttlwidth > 85) {
                    $ukuranfont = 8.5;
                    while ($ttlwidth > 85) {
                        $pdf->SetFont('Arial', '', $ukuranfont);
                        $ttlwidth = $pdf->GetStringWidth(trim($ItemDis2));
                        $ukuranfont = $ukuranfont - 0.5;
                    }
                }
                $pdf->SetXY(30.75, $curY);
                $pdf->Cell(85, 4, $ItemDis2, 0, 0, 'L');

                $pdf->SetFont('Arial', '', 9);
                $pdf->SetXY(30.75, $curY + 4);
                $pdf->Cell(85, 4, $ItemDis, 0, 0, 'L');
                $pdf->SetXY(30.75, $curY + 8);
                $pdf->Cell(85, 4, $r['TYPE'], 0, 0, 'L');
                $pdf->SetXY(90, $curY);
                if ($isDecimal) {
                    $pdf->Cell(43.43, 4, number_format($r['DLVRMDOC_ITMQT'], 2) . " " . $r['MITM_STKUOM'], 0, 0, 'R');
                } else {
                    $pdf->Cell(43.43, 4, number_format($r['DLVRMDOC_ITMQT']) . " " . $r['MITM_STKUOM'], 0, 0, 'R');
                }
                $pdf->SetXY(140, $curY);
                $pdf->Cell(43.43, 4, "Ex.BC." . $r['RCV_BCTYPE'] . ":" . $r['DLVRMDOC_NOPEN'], 0, 0, 'L');
                $pdf->SetXY(140, $curY + 4);
                $pdf->Cell(43.43, 4, "date:" . $xbcdate, 0, 0, 'L');
                $ttldoqty += $r['DLVRMDOC_ITMQT'];
                $UM = $r['MITM_STKUOM'];
                if (!in_array($r['MITM_STKUOM'], $a_stkuom)) {
                    $a_stkuom[] = $r['MITM_STKUOM'];
                }
                $curY += 16;
                $ttlbaris++;
            }
            $pdf->SetXY(90, 220);
            if (count($a_stkuom) > 1) {
                $pdf->Cell(43.43, 4, number_format($ttldoqty), 0, 0, 'R');
            } else {
                if ($isDecimal) {
                    $pdf->Cell(43.43, 4, number_format($ttldoqty, 2) . " " . $UM, 0, 0, 'R');
                } else {
                    $pdf->Cell(43.43, 4, number_format($ttldoqty) . " " . $UM, 0, 0, 'R');
                }
            }
        }
        $pdf->Output('I', 'Delivery Docs RM ' . date("d-M-Y") . '.pdf');
    }

    public function printdocs()
    {
        $pid = $pBarcode = '';
        $pforms = '';
        if (isset($_COOKIE["CKPDLV_NO"])) {
            $pid = $_COOKIE["CKPDLV_NO"];
        } else {
            exit('nothing to be printed');
        }
        if (isset($_COOKIE["CKPDLV_BARCODE"])) {
            $pBarcode = $_COOKIE["CKPDLV_BARCODE"];
        } else {
            exit('need to be reload or refresh the browser');
        }
        if (isset($_COOKIE["CKPDLV_FORMS"])) {
            $pforms = $_COOKIE["CKPDLV_FORMS"];
        } else {
            exit('Please select at least one document type');
        }
        $rs = $this->DELV_mod->select_det_byid_p($pid);
        $rswhSI = $this->SI_mod->select_wh_by_txid($pid);
        $pdf = new PDF_Code39e128('P', 'mm', 'A4');
        $pdf->SetFont('Arial', '', 8);
        //start of data base
        $hinv_date = '';
        $hdlv_date = '';
        $hinv_inv = '';
        $hinv_smtinv = '';
        $hinv_nopen = '';
        $hinv_customer = '';
        $hinv_address = '';

        $hinv_currency = '';
        $hinv_bctype = '';
        $h_tujuanPengiriman = '';
        $ar_item = [];
        $ar_itemdesc = [];
        $ar_itemUM = [];
        $ar_qty = [];
        $ar_price = [];
        $customer_hasPO = '';
        $ATTN = '';
        foreach ($rs as $r) {
            $hinv_date = $r['DLV_INVDT'];
            $hinv_date = date_create($hinv_date);
            $hinv_date = date_format($hinv_date, "d/m/Y");
            $hdlv_date = $r['DLV_DATE'];
            $hdlv_date = date_create($hdlv_date);
            $hdlv_date = date_format($hdlv_date, "d/m/Y");
            $hinv_inv = $r['DLV_INVNO'];
            $hinv_smtinv = $r['DLV_SMTINVNO'];
            $hinv_nopen = $r['DLV_NOPEN'];
            $hinv_address = $r['MDEL_ADDRCUSTOMS'];
            $hinv_currency = $r['MCUS_CURCD'];
            $hinv_customer = $r['MDEL_DELNM'];
            $hinv_bctype = $r['DLV_BCTYPE'];
            $customer_hasPO = $r['MCUS_CUSNM'];
            $ATTN = $r['ATTN'];
            $h_tujuanPengiriman = $r['DLV_PURPOSE'];
            break;
        }
        //end of data base
        if (substr($pforms, 1, 1) == '1') {
            //START INVOICE
            $Y_adj = 20;
            $pdf->AddPage();
            $pdf->SetAutoPageBreak(true, 1);
            $pdf->SetMargins(0, 0);
            $rsinv = [];
            if ($rswhSI === 'AFWH3RT' || $rswhSI === 'AFWH3') {
                $rsinv = $this->setPriceRS(base64_encode($pid));
                $rsresume = [];
                $rsmultiprice = [];
                foreach ($rsinv as &$k) {
                    if ($k['SISO_SOLINE'] == 'X') {
                        $rs_mst_price = $this->XSO_mod->select_latestprice($k['SI_BSGRP'], $k['SI_CUSCD'], "'" . $k['SSO2_MDLCD'] . "'");
                        foreach ($rs_mst_price as $r) {
                            $k['SSO2_SLPRC'] = substr($r['MSPR_SLPRC'], 0, 1) == '.' ? '0' . $r['MSPR_SLPRC'] : $r['MSPR_SLPRC'];
                            $k['CIF'] = $k['SSO2_SLPRC'] * $k['SISOQTY'];
                        }
                    }
                    $isfound = false;
                    foreach ($rsresume as &$n) {
                        if ($n['RSI_ITMCD'] == $k['SSO2_MDLCD'] && $n['RSSO2_SLPRC'] != $k['SSO2_SLPRC']) {
                            $n['RCOUNT']++;
                            $isfound = true;
                            break;
                        }
                    }
                    unset($n);

                    if (!$isfound) {
                        $rsresume[] = [
                            'RSI_ITMCD' => $k['SSO2_MDLCD'], 'RSSO2_SLPRC' => $k['SSO2_SLPRC'], 'RCOUNT' => 1,
                        ];
                    }
                }
                unset($k);
                foreach ($rsresume as $k) {
                    if ($k['RCOUNT'] > 1) {
                        $rsmultiprice[] = $k;
                    }
                }
            } elseif ($rswhSI === 'NFWH4RT') {
                $rspickingres = $this->SISCN_mod->select_exbc_fgrtn($pid);
                $tpb_barang_temp = [];
                foreach ($rspickingres as $r) {
                    $isplot = false;
                    $CIF = $r['RCV_PRPRC'] * $r['INTQTY'];
                    foreach ($tpb_barang_temp as &$p) {
                        if ($r['SI_ITMCD'] == $p['KODE_BARANG'] && $r['RCV_PRPRC'] == $p['PERPRICE']) {
                            $p['JUMLAH_SATUAN'] += $r['INTQTY'];
                            $p['CIF'] = $p['JUMLAH_SATUAN'] * $p['PERPRICE'];
                            $p['NETTO'] += $r['NWG'];
                            $isplot = true;
                            break;
                        }
                    }
                    unset($p);
                    if (!$isplot) {
                        $tpb_barang_temp[] = [
                            'KODE_BARANG' => $r['SI_ITMCD'], 'POS_TARIF' => $r['RCV_HSCD'], 'URAIAN' => $r['MITM_ITMD1'], 'JUMLAH_SATUAN' => $r['INTQTY'], 'KODE_SATUAN' => $r['MITM_STKUOM'] == 'PCS' ? 'PCE' : $r['MITM_STKUOM'], 'NETTO' => $r['NWG'] * 1, 'CIF' => round($CIF, 2), 'KODE_STATUS' => '02', 'PERPRICE' => $r['RCV_PRPRC'],
                        ];
                    }
                }
                foreach ($tpb_barang_temp as $r) {
                    $rsinv[] = [
                        'DLV_ID' => $pid, 'SISOQTY' => $r['JUMLAH_SATUAN'], 'SSO2_SLPRC' => $r['PERPRICE'], 'SSO2_MDLCD' => $r['KODE_BARANG']#
                        , 'CIF' => $r['CIF'], 'NWG' => $r['NETTO'], 'MITM_HSCD' => $r['POS_TARIF'], 'MITM_STKUOM' => $r['KODE_SATUAN'], 'MITM_ITMD1' => $r['URAIAN'],
                    ];
                }
            }

            $pdf->SetFont('Arial', '', 8);
            $pdf->Text(144, 67, 'Nopen : ' . $hinv_nopen);
            $pdf->Text(162, 78 + 5, $hinv_inv);
            $pdf->Text(30, 78 + 5, $hinv_date);
            $pdf->SetXY(13, 80.9 + 15);
            $pdf->MultiCell(85.67, 4, trim($hinv_customer), 0);
            $pdf->SetXY(13, 80.9 + 4 + $Y_adj);
            $pdf->MultiCell(85.67, 4, trim($hinv_address), 0);
            $pdf->SetXY(13, 80.9 + 12 + $Y_adj);
            $pdf->MultiCell(85.67, 4, $ATTN, 0);
            $pdf->Text(110, 133 + 10, $hinv_currency);
            $pdf->SetLineWidth(0.4);
            $curY = 143 + $Y_adj;
            $pdf->SetXY(14, $curY);
            $curY = 152 + 20;
            $no = 1;
            $ttlbrs = 1;
            $gtotalamount = 0;
            $curLine = 147;
            $MAX_INVD_PERPAGE = 8;
            $MAX_INVL_PERPAGE = $curLine + (10 * $MAX_INVD_PERPAGE);
            $ttlqty_ = 0;
            foreach ($rsinv as $r) {
                if ($ttlbrs > $MAX_INVD_PERPAGE) {
                    $ttlbrs = 1;
                    $pdf->AddPage();
                    $pdf->SetFont('Arial', '', 8);
                    $pdf->Text(144, 67, 'Nopen : ' . $hinv_nopen);
                    $pdf->Text(162, 78 + 5, $hinv_inv);
                    $pdf->Text(30, 78 + 5, $hinv_date);
                    $pdf->SetXY(13, 80.9 + 15);
                    $pdf->MultiCell(85.67, 4, trim($hinv_customer), 0);
                    $pdf->SetXY(13, 80.9 + 4 + $Y_adj);
                    $pdf->MultiCell(85.67, 4, trim($hinv_address), 0);
                    $pdf->SetXY(13, 80.9 + 12 + $Y_adj);
                    $pdf->MultiCell(85.67, 4, $ATTN, 0);
                    $pdf->Text(110, 133 + 10, $hinv_currency);
                    $pdf->SetLineWidth(0.4);
                    $curY = 143 + $Y_adj;
                    $pdf->SetXY(14, $curY);
                    $curY = 152 + 20;
                    $curLine = 147;
                }
                $pdf->SetXY(14, $curY - 3);
                $pdf->Cell(27, 4, $no, 0, 0, 'L');
                $pdf->SetXY(43, $curY - 3);
                $ttlwidth = $pdf->GetStringWidth(trim($r['MITM_ITMD1']));
                if ($ttlwidth > 51) {
                    $ukuranfont = 7.5;
                    while ($ttlwidth > 50) {
                        $pdf->SetFont('Arial', '', $ukuranfont);
                        $ttlwidth = $pdf->GetStringWidth(trim($r['MITM_ITMD1']));
                        $ukuranfont = $ukuranfont - 0.5;
                    }
                }
                $pdf->Cell(51, 4, $r['MITM_ITMD1'], 0, 0, 'L');
                $pdf->SetFont('Arial', '', 8);
                $pdf->Text(45, $curY + 4, "(" . trim($r['SSO2_MDLCD']) . ")");
                $pdf->Text(103, $curY, $r['MITM_STKUOM']);
                $pdf->SetXY(115, $curY - 3);
                $pdf->Cell(20.55, 4, number_format($r['SISOQTY']), 0, 0, 'R');
                $pdf->SetXY(137, $curY - 3);
                if (substr($r['SSO2_SLPRC'], -5) == '00000') {
                    $pdf->Cell(18, 4, substr($r['SSO2_SLPRC'], 0, 1) == '.' ? number_format('0' . $r['SSO2_SLPRC'], 5) : number_format($r['SSO2_SLPRC'], 0), 0, 0, 'R');
                } else {
                    $pdf->Cell(18, 4, substr($r['SSO2_SLPRC'], 0, 1) == '.' ? number_format('0' . $r['SSO2_SLPRC'], 5) : number_format($r['SSO2_SLPRC'], 5), 0, 0, 'R');
                }
                $pdf->SetXY(155, $curY - 3);
                $pdf->Cell(41.56, 4, number_format($r['SISOQTY'] * $r['SSO2_SLPRC'], 2), 0, 0, 'R');
                $curY += 10;
                $curLine += 10;
                $no++;
                $ttlbrs++;
                $gtotalamount += ($r['SISOQTY'] * $r['SSO2_SLPRC']);
                $ttlqty_ += $r['SISOQTY'];
            }
            $pdf->SetXY(115, 240 + 13);
            $pdf->Cell(20.55, 4, number_format($ttlqty_, 2), 0, 0, 'R');
            $pdf->SetXY(155, 240 + 13);
            $pdf->Cell(41.56, 4, number_format($gtotalamount, 2), 0, 0, 'R');
            if ($hinv_bctype == '27') {
                if (strpos(strtolower($customer_hasPO), 'sumi') !== false) {
                    //non Commercial Value For Customs
                    $pdf->Text(35, 240 + 25, "Non Commercial Value For Customs Purpose Only, On Behalf PT. Sumitronics Indonesia");
                    $pdf->Text(76, 246 + 25, "Commercial Invoice No are");
                    $pdf->Text(76, 246 + 5 + 25, "1) From PT. SUMITRONIC INDONESIA To");
                    $pdf->Text(76, 246 + 10 + 25, trim($hinv_customer) . " " . $hinv_inv);
                    $pdf->Text(76, 246 + 15 + 25, "2) From PT. SMT INDONESIA To");
                    $pdf->Text(76, 246 + 20 + 25, "PT. SUMITRONIC INDONESIA " . $hinv_smtinv);
                }
            }
            //END OF INVOICE
        }

        if (substr($pforms, -1) == '1') {
            //START PACKING LIST
            $pdf->AddPage();
            $pdf->SetAutoPageBreak(true, 1);
            $pdf->SetMargins(0, 0);
            unset($ar_item);
            unset($ar_itemdesc);
            unset($ar_qty);
            unset($ar_itemUM);
            unset($ar_price);
            $rspackinglist = $this->DELV_mod->select_packinglist_bydono($pid);
            $ar_item = [];
            $ar_itemdesc = [];

            $ar_grup_item = [];
            $ar_grup_item_ttl = [];
            $ASPFLAG = false;
            foreach ($rspackinglist as $r) {
                if (strpos($r['SI_ITMCD'], 'ASP') !== false) {
                    $ASPFLAG = true;
                }
                if (!in_array($r['SI_ITMCD'], $ar_grup_item)) {
                    $ar_grup_item[] = $r['SI_ITMCD'];
                    $ar_grup_item_ttl[] = 1;
                } else {
                    for ($h = 0; $h < count($ar_grup_item); $h++) {
                        if ($ar_grup_item[$h] == $r['SI_ITMCD']) {
                            $ar_grup_item_ttl[$h]++;
                            break;
                        }
                    }
                }
            }

            foreach ($rspackinglist as &$r) {
                for ($h = 0; $h < count($ar_grup_item); $h++) {
                    if ($ar_grup_item[$h] == $r['SI_ITMCD']) {
                        $r['TTLBARIS'] = $ar_grup_item_ttl[$h];
                        break;
                    }
                }
            }
            unset($r);

            $clebar = $pdf->GetStringWidth($pid) + 17;
            $pdf->Code128(140, 40, $pid, $clebar, 5);
            $pdf->Text(155, 59 + 7, $hinv_date);
            $pdf->Text(16, 70 + 10, $hinv_customer);
            $pdf->SetXY(15, 81);
            $pdf->MultiCell(85.67, 4, trim($hinv_address), 0);
            $pdf->Text(140, 91 + 10, $hinv_inv);
            $curY = 110 + 15;
            $no = 0;

            $TTLQTY = 0;
            $TTLNET = 0;
            $TTLGRS = 0;
            $TTLBOX = 0;

            $par_item = '';
            $dis_item = '';
            $dis_itemnm = '';
            $dis_no = '';
            $dis_qty = '';
            if ($ASPFLAG) {
                $rspickingres = $this->DELV_mod->select_serah_terima_asp($pid);
                foreach ($rspickingres as $r) {
                    if ($curY > 200) {
                        $pdf->AddPage();
                        $pdf->Code128(140, 40, $pid, $clebar, 5);
                        $pdf->Text(155, 59 + 7, $hinv_date);
                        $pdf->Text(16, 70 + 10, $hinv_customer);
                        $pdf->SetXY(15, 81);
                        $pdf->MultiCell(85.67, 4, trim($hinv_address), 0);
                        $pdf->Text(140, 91 + 10, $hinv_inv);
                        $curY = 110 + 15;
                    }
                    if ($par_item != $r['SI_ITMCD']) {
                        $par_item = $r['SI_ITMCD'];
                        $dis_item = $r['SI_ITMCD'];
                        $dis_itemnm = $r['MITM_ITMD1'];
                        $dis_qty = number_format($r['TTLBOX'], 0);
                        $no++;
                        $dis_no = $no;
                    } else {
                        $dis_item = '';
                        $dis_itemnm = '';
                        $dis_no = '';
                        $dis_qty = '';
                    }
                    $pdf->SetXY(6, $curY - 3);
                    $pdf->Cell(21, 4, $dis_no, 0, 0, 'C');
                    $pdf->SetXY(32, $curY - 3);
                    $ttlwidth = $pdf->GetStringWidth(trim($dis_itemnm));
                    if ($ttlwidth > 63.73) {
                        $ukuranfont = 7.5;
                        while ($ttlwidth > 63.73) {
                            $pdf->SetFont('Arial', '', $ukuranfont);
                            $ttlwidth = $pdf->GetStringWidth(trim($dis_itemnm));
                            $ukuranfont = $ukuranfont - 0.5;
                        }
                    }
                    $pdf->Cell(63.73, 4, $dis_itemnm, 0, 0, 'L');
                    $pdf->SetFont('Arial', '', 8);
                    $pdf->SetXY(32, $curY + 2);
                    $pdf->Cell(63.73, 4, $dis_item, 0, 0, 'L');
                    $pdf->SetXY(90, $curY - 3);
                    $pdf->Cell(16.63, 4, $dis_qty, 0, 0, 'R');
                    $pdf->SetXY(110, $curY - 3);
                    $pdf->Cell(24.71, 4, number_format($r['MITM_NWG'], 2), 0, 0, 'R');
                    $pdf->SetXY(137.07, $curY - 3);
                    $pdf->Cell(29.33, 4, number_format($r['MITM_GWG'], 2), 0, 0, 'R');
                    $pdf->SetXY(166.4, $curY - 3);
                    $pdf->Cell(31.17, 4, number_format($r['TTLBOX']) . " BOX (X) " . number_format($r['SISCN_SERQTY']), 0, 0, 'R');

                    $TTLQTY += ($r['TTLBOX']);
                    $TTLNET += $r['MITM_NWG'];
                    $TTLGRS += $r['MITM_GWG'];
                    $TTLBOX += $r['TTLBOX'];
                    $curY += $r['TTLBARIS'] == 1 ? 10 : 5;
                }
                $pdf->SetXY(32, 235);
                $pdf->Cell(63.73, 4, "Total", 0, 0, 'L');
                $pdf->SetXY(95.73, 235);
                $pdf->Cell(16.63, 4, number_format($TTLQTY), 0, 0, 'R');
                $pdf->SetXY(112.36, 235);
                $pdf->Cell(24.71, 4, number_format($TTLNET, 2), 0, 0, 'R');
                $pdf->SetXY(137.07, 235);
                $pdf->Cell(29.33, 4, number_format($TTLGRS, 2), 0, 0, 'R');
                $pdf->SetXY(166.4, 235);
                $pdf->Cell(31.17, 4, number_format($TTLBOX), 0, 0, 'R');
            } else {
                foreach ($rspackinglist as $r) {
                    if ($curY > 200) {
                        $pdf->AddPage();
                        $pdf->Code128(140, 40, $pid, $clebar, 5);
                        $pdf->Text(155, 59 + 7, $hinv_date);
                        $pdf->Text(16, 70 + 10, $hinv_customer);
                        $pdf->SetXY(15, 81);
                        $pdf->MultiCell(85.67, 4, trim($hinv_address), 0);
                        $pdf->Text(140, 91 + 10, $hinv_inv);
                        $curY = 110 + 15;
                    }
                    if ($par_item != $r['SI_ITMCD']) {
                        $par_item = $r['SI_ITMCD'];
                        $dis_item = $r['SI_ITMCD'];
                        $dis_itemnm = $r['MITM_ITMD1'];
                        $dis_qty = number_format($r['TTLDLV'], 0);
                        $no++;
                        $dis_no = $no;
                    } else {
                        $dis_item = '';
                        $dis_itemnm = '';
                        $dis_no = '';
                        $dis_qty = '';
                    }
                    $pdf->SetXY(6, $curY - 3);
                    $pdf->Cell(21, 4, $dis_no, 0, 0, 'C');
                    $pdf->SetXY(32, $curY - 3);
                    $ttlwidth = $pdf->GetStringWidth(trim($dis_itemnm));
                    if ($ttlwidth > 63.73) {
                        $ukuranfont = 7.5;
                        while ($ttlwidth > 63.73) {
                            $pdf->SetFont('Arial', '', $ukuranfont);
                            $ttlwidth = $pdf->GetStringWidth(trim($dis_itemnm));
                            $ukuranfont = $ukuranfont - 0.5;
                        }
                    }
                    $pdf->Cell(63.73, 4, $dis_itemnm, 0, 0, 'L');
                    $pdf->SetFont('Arial', '', 8);
                    $pdf->SetXY(32, $curY + 2);
                    $pdf->Cell(63.73, 4, $dis_item, 0, 0, 'L');
                    $pdf->SetXY(90, $curY - 3);
                    $pdf->Cell(16.63, 4, $dis_qty, 0, 0, 'R'); #number_format($r['SISCN_SERQTY'] * $r['TTLBOX'])
                    $pdf->SetXY(110, $curY - 3);
                    $pdf->Cell(24.71, 4, number_format($r['MITM_NWG'], 4), 0, 0, 'R');
                    $pdf->SetXY(137.07, $curY - 3);
                    $pdf->Cell(29.33, 4, number_format($r['MITM_GWG'], 4), 0, 0, 'R');
                    $pdf->SetXY(166.4, $curY - 3);
                    $pdf->Cell(31.17, 4, number_format($r['TTLBOX']) . " BOX (X) " . number_format($r['SISCN_SERQTY']), 0, 0, 'R');

                    $TTLQTY += ($r['SISCN_SERQTY'] * $r['TTLBOX']);
                    $TTLNET += $r['MITM_NWG'];
                    $TTLGRS += $r['MITM_GWG'];
                    $TTLBOX += $r['TTLBOX'];
                    $curY += $r['TTLBARIS'] == 1 ? 10 : 5;
                }
                $pdf->SetXY(32, 235);
                $pdf->Cell(63.73, 4, "Total", 0, 0, 'L');
                $pdf->SetXY(95.73, 235);
                $pdf->Cell(16.63, 4, number_format($TTLQTY), 0, 0, 'R');
                $pdf->SetXY(112.36, 235);
                $pdf->Cell(24.71, 4, number_format($TTLNET, 4), 0, 0, 'R');
                $pdf->SetXY(137.07, 235);
                $pdf->Cell(29.33, 4, number_format($TTLGRS, 4), 0, 0, 'R');
                $pdf->SetXY(166.4, 235);
                $pdf->Cell(31.17, 4, number_format($TTLBOX), 0, 0, 'R');
            }
            //END OF PACKING LIST
        }

        if (substr($pforms, 0, 1) == '1') {
            //START DO
            unset($ar_item);
            unset($ar_itemdesc);
            unset($ar_qty);
            unset($ar_itemUM);
            $ar_po = [];
            $ar_item = [];
            $ar_itemdesc = [];
            $ar_itemUM = [];
            $ar_qty = [];
            $rsdo = $this->DLVSO_mod->select_for_DO($pid);
            foreach ($rs as $r) {
                $isexist = false;
                for ($i = 0; $i < count($ar_item); $i++) {
                    if (($r['SER_ITMID'] == $ar_item[$i]) && ($r['SISCN_DOCREFF'] == $ar_po[$i])) {
                        $isexist = true;
                        $ar_qty[$i] = $ar_qty[$i] + $r['SISCN_SERQTY'];
                        break;
                    }
                }
                if (!$isexist) {
                    $ar_item[] = $r['SER_ITMID'];
                    $ar_itemdesc[] = $r['MITM_ITMD1'];
                    $ar_itemUM[] = $r['MITM_STKUOM'];
                    $ar_qty[] = $r['SISCN_SERQTY'];
                    $ar_po[] = $r['SISCN_DOCREFF'];
                }
            }
            $pdf->AddPage();
            $pdf->SetAutoPageBreak(true, 1);
            $pdf->SetMargins(0, 0);
            $pdf->SetXY(48, 63);
            $pdf->MultiCell(76.04, 4, trim($hinv_customer) . " \n" . $hinv_address, 0);
            if ($pBarcode === '1') {
                $clebar = $pdf->GetStringWidth($pid) + 13;
                $pdf->Code128(155, 40, $pid, $clebar, 7);
            }
            $pdf->SetXY(155, 63);
            $pdf->Cell(31.17, 4, $pid, 0, 0, 'L');
            $pdf->SetXY(155, 60 + 5 + 5);
            $pdf->Cell(31.17, 4, $hdlv_date, 0, 0, 'L');
            $pdf->SetXY(155, 70 + 10);
            $pdf->Cell(31.17, 4, "BC " . $hinv_bctype . " : " . $hinv_nopen, 0, 0, 'L');
            $pdf->SetXY(155, 75 + 10);
            $pdf->Cell(31.17, 4, "INV NO : " . $hinv_inv, 0, 0, 'L');
            $curY = 93 + 20;
            $ttlrows = count($ar_item);
            $ttlbaris = 1;
            $tempItem = '';
            $ItemDis = '';
            $ItemDis2 = '';
            $nourutDO = 0;
            $nourutDODis = 0;
            if ($hinv_bctype === '41') {
                if ($h_tujuanPengiriman == '5') {
                    $rs41 = $this->setPriceRS(base64_encode($pid));
                    foreach ($rs41 as $n) {
                        if ($tempItem != $n['SSO2_MDLCD']) {
                            $nourutDO++;
                            $tempItem = $n['SSO2_MDLCD'];
                            $ItemDis = $tempItem;
                            $ItemDis2 = $n['MITM_ITMD1'];
                            $nourutDODis = $nourutDO;
                        } else {
                            $ItemDis = '';
                            $ItemDis2 = '';
                            $nourutDODis = '';
                        }
                        if ($ttlbaris > 6) {
                            $ttlbaris = 1;
                            $curY = 93;
                            $pdf->AddPage();
                        }
                        $pdf->SetXY(16, $curY);
                        $pdf->Cell(14.75, 4, $nourutDODis, 0, 0, 'C');
                        $pdf->SetXY(30.75, $curY);
                        $pdf->Cell(63.91, 4, $ItemDis2, 0, 0, 'L');
                        $pdf->SetXY(30.75, $curY + 4);
                        $pdf->Cell(63.91, 4, $ItemDis, 0, 0, 'L'); //LINE2
                        $pdf->SetXY(94.66, $curY);
                        $pdf->Cell(43.43, 4, number_format($n['SISOQTY']) . " " . $n['MITM_STKUOM'], 0, 0, 'R');
                        $pdf->SetXY(138.09, $curY);
                        $pdf->Cell(58.34, 4, "PO : " . $n['CPO'], 0, 0, 'L');
                        $curY += 16;
                        $ttlbaris++;
                    }
                } else {
                    $rs41 = $this->DELV_mod->select_DO41($pid);
                    foreach ($rs41 as $n) {
                        if ($tempItem != $n['SER_ITMID']) {
                            $nourutDO++;
                            $tempItem = $n['SER_ITMID'];
                            $ItemDis = $tempItem;
                            $ItemDis2 = $n['MITM_ITMD1'];
                            $nourutDODis = $nourutDO;
                        } else {
                            $ItemDis = '';
                            $ItemDis2 = '';
                            $nourutDODis = '';
                        }
                        if ($ttlbaris > 6) {
                            $ttlbaris = 1;
                            $curY = 93;
                            $pdf->AddPage();
                        }
                        $pdf->SetXY(16, $curY);
                        $pdf->Cell(14.75, 4, $nourutDODis, 0, 0, 'C');
                        $pdf->SetXY(30.75, $curY);
                        $pdf->Cell(63.91, 4, $ItemDis2, 0, 0, 'L');
                        $pdf->SetXY(30.75, $curY + 4);
                        $pdf->Cell(63.91, 4, $ItemDis, 0, 0, 'L'); //LINE2
                        $pdf->SetXY(94.66, $curY);
                        $pdf->Cell(43.43, 4, number_format($n['TTLDLV']) . " " . $n['MITM_STKUOM'], 0, 0, 'R');
                        $pdf->SetXY(138.09, $curY);
                        $pdf->Cell(58.34, 4, "EX.BC 40 : " . $n['RCV_BCNO'], 0, 0, 'L');
                        $pdf->SetXY(138.09, $curY + 4);
                        $pdf->Cell(58.34, 4, "LOT. : " . number_format($n['TTLALLDLV']) . " / " . number_format($n['MTTL']), 0, 0, 'L'); //LINE2
                        $pdf->SetXY(138.09, $curY + 8);
                        $pdf->Cell(58.34, 4, "Sisa. : " . number_format($n['MTTL'] - $n['TTLALLDLV']), 0, 0, 'L'); //LINE
                        if ($n['DLV_CONA'] != '') {
                            $pdf->SetXY(138.09, $curY + 12);
                            $pdf->Cell(58.34, 4, "PK. NO : " . $n['DLV_CONA'], 0, 0, 'L'); //LINE
                        }
                        $curY += 16;
                        $ttlbaris++;
                    }
                }
            } else {
                if (count($rsdo) > 0) {
                    $ttldoqty = 0;
                    foreach ($rsdo as $n) {
                        if ($tempItem != $n['DLVSO_ITMCD']) {
                            $nourutDO++;
                            $tempItem = $n['DLVSO_ITMCD'];
                            $ItemDis = $tempItem;
                            $ItemDis2 = $n['MITM_ITMD1'];
                            $nourutDODis = $nourutDO;
                        } else {
                            $ItemDis = '';
                            $ItemDis2 = '';
                            $nourutDODis = '';
                        }
                        if ($ttlbaris > 6) {
                            $ttlbaris = 1;
                            $pdf->AddPage();
                            $pdf->SetXY(51, 60);
                            $pdf->MultiCell(76.04, 4, trim($hinv_customer) . " \n" . $hinv_address, 0);
                            $pdf->SetXY(155, 60);
                            $pdf->Cell(31.17, 4, $pid, 0, 0, 'L');
                            $pdf->SetXY(155, 60 + 5 + 5);
                            $pdf->Cell(31.17, 4, $hdlv_date, 0, 0, 'L');
                            $pdf->SetXY(155, 70 + 10);
                            $pdf->Cell(31.17, 4, "BC " . $hinv_bctype . " : " . trim($hinv_nopen), 0, 0, 'L');
                            $pdf->SetXY(155, 75 + 10);
                            $pdf->Cell(31.17, 4, "INV NO : " . $hinv_inv, 0, 0, 'L');
                            $curY = 93 + 20;
                        }
                        $pdf->SetXY(10, $curY);
                        $pdf->Cell(14.75, 4, $nourutDODis, 0, 0, 'C');
                        $pdf->SetXY(30.75, $curY);
                        $pdf->Cell(63.91, 4, $ItemDis2, 0, 0, 'L');
                        $pdf->SetXY(30.75, $curY + 4);
                        $pdf->Cell(63.91, 4, $ItemDis, 0, 0, 'L'); //LINE2
                        $pdf->SetXY(90, $curY);
                        $pdf->Cell(43.43, 4, number_format($n['PLOTQTY']) . " " . $n['MITM_STKUOM'], 0, 0, 'R');
                        $pdf->SetXY(138.09, $curY);
                        $pdf->Cell(58.34, 4, "PO NO : " . $n['DLVSO_CPONO'], 0, 0, 'L');
                        $pdf->SetXY(138.09, $curY + 4);
                        $pdf->Cell(58.34, 4, "OS : " . number_format($n['PLOTQTMAIN']) . "/" . number_format($n['ORDQT']), 0, 0, 'L'); //LINE2
                        $curY += 8;
                        $ttlbaris++;
                        $ttldoqty += $n['PLOTQTY'];
                    }
                    if (strpos(strtoupper($hinv_customer), 'EPSON') !== false) {
                        $pdf->SetXY(90, $curY + 11);
                        $pdf->Cell(43.43, 4, 'OFFLINE', 1, 0, 'C');
                    }
                    $pdf->SetXY(90, 220);
                    $pdf->Cell(43.43, 4, number_format($ttldoqty) . " " . $n['MITM_STKUOM'], 0, 0, 'R');
                } else {
                    $ttlqty = 0;
                    $rs_ = $this->setPriceRS_PERSO(base64_encode($pid));
                    unset($ar_itemdesc);
                    unset($ar_item);
                    unset($ar_qty);
                    unset($ar_po);
                    unset($ar_itemUM);
                    $ar_itemdesc = [];
                    $ar_item = [];
                    $ar_qty = [];
                    $ar_po = [];
                    $ar_itemUM = [];

                    foreach ($rs_ as $r) {
                        $ar_itemdesc[] = $r['MITM_ITMD1'];
                        $ar_item[] = $r['SSO2_MDLCD'];
                        $ar_qty[] = $r['SISOQTY'];
                        $ar_po[] = $r['CPO'];
                        $ar_itemUM[] = $r['MITM_STKUOM'];
                    }
                    $ttlrows = count($ar_item);
                    for ($i = 0; $i < $ttlrows; $i++) {
                        if ($ttlbaris > 6) {
                            $ttlbaris = 1;
                            $pdf->AddPage();
                            $pdf->SetXY(48, 63);
                            $pdf->MultiCell(76.04, 4, trim($hinv_customer) . " \n" . $hinv_address, 0);
                            $pdf->SetXY(155, 63);
                            $pdf->Cell(31.17, 4, $pid, 0, 0, 'L');
                            $pdf->SetXY(155, 60 + 5 + 5);
                            $pdf->Cell(31.17, 4, $hdlv_date, 0, 0, 'L');
                            $pdf->SetXY(155, 70 + 10);
                            $pdf->Cell(31.17, 4, "BC " . $hinv_bctype . " : " . trim($hinv_nopen), 0, 0, 'L');
                            $pdf->SetXY(155, 75 + 10);
                            $pdf->Cell(31.17, 4, "INV NO : " . $hinv_inv, 0, 0, 'L');
                            $curY = 93 + 20;
                        }
                        $pdf->SetXY(11, $curY);
                        $pdf->Cell(14.75, 4, ($i + 1), 0, 0, 'C');
                        $pdf->SetXY(30.75, $curY);
                        $pdf->Cell(63.91, 4, trim($ar_itemdesc[$i]), 0, 0, 'L');
                        $pdf->SetXY(30.75, $curY + 4);
                        $pdf->Cell(63.91, 4, trim($ar_item[$i]), 0, 0, 'L'); //LINE2
                        $pdf->SetXY(90, $curY);
                        $pdf->Cell(43.43, 4, number_format($ar_qty[$i]) . " " . trim($ar_itemUM[$i]), 0, 0, 'R');
                        if ($pid === '0622Y0918RTN') {
                        } else {
                            $pdf->SetXY(138.09, $curY);
                            $pdf->Cell(58.34, 4, "PO NO : " . $ar_po[$i], 0, 0, 'L');
                        }
                        $curY += 8;
                        $ttlbaris++;
                        $ttlqty += $ar_qty[$i];
                    }
                    $pdf->SetXY(90, 215);
                    $pdf->Cell(43.43, 4, number_format($ttlqty) . " " . trim($ar_itemUM[0]), 0, 0, 'R');
                }
            }
            //END OF DO
        }
        $pdf->Output('I', 'Delivery Docs ' . date("d-M-Y") . '.pdf');
    }
    public function change25()
    {
        $crnt_dt = date('Y-m-d H:i:s');
        $cid = $this->input->get('inid');
        $cnoaju = $this->input->get('inaju');
        $cnopen = $this->input->get('innopen');
        $ctglpen = $this->input->get('intglpen');
        $cfromoffice = $this->input->get('infromoffice');
        $ctpb_asal = $this->input->get('injenis_tpb_asal');
        $cskb = $this->input->get('inskb');
        $cskb_tgl = $this->input->get('inskb_tgl');
        $sppbdoc = $this->input->get('sppbdoc');
        $pemberitahu = $this->input->get('inPemberitahu');
        $jabatan = $this->input->get('inJabatan');
        $cjenis_sarana_pengangkut = $this->input->get('injenis_sarana');
        $rs_head_dlv = $this->DELV_mod->select_header_bydo($cid);
        $customsyear = '';
        foreach ($rs_head_dlv as $r) {
            $czdocbctype = $r['DLV_BCTYPE'];
            $ccustdate = $r['DLV_BCDATE'];
            $customsyear = substr($ccustdate, 0, 4);
        }
        if (strlen($cnoaju) > 2) {
            if ($this->DELV_mod->check_Primary(['DLV_ID !=' => $cid, 'DLV_NOAJU' => $cnoaju, 'DLV_BCTYPE' => $czdocbctype, 'YEAR(DLV_BCDATE)' => $customsyear])) {
                $myar[] = ["cd" => '00', "msg" => "Nomor Aju is already used"];
                die(json_encode($myar));
            }
        }
        if ($cnopen == 'null') {
            $cnopen = '';
        }

        $rsaktivasi = $this->AKTIVASIAPLIKASI_imod->selectAll();
        $rs_head_dlv = $this->DELV_mod->select_header_bydo($cid);
        $czidmodul = '';
        foreach ($rsaktivasi as $r) {
            $czkantorasal = $r['KPPBC'];
            $czidmodul = substr('00000' . $r['ID_MODUL'], -6);
        }
        if ($czidmodul == '') {
            $myar[] = ["cd" => '00', "msg" => "Please check Aktivasi CEISA Data"];
        } else {
            # set value, pemberitahu & jabatan
            if ($this->DLVH_mod->check_Primary(['DLVH_ID' => $cid])) {
                $this->DLVH_mod->updatebyVAR(['DLVH_PEMBERITAHU' => $pemberitahu, 'DLVH_JABATAN' => $jabatan], ['DLVH_ID' => $cid]);
            } else {
                $this->DLVH_mod->insert(['DLVH_PEMBERITAHU' => $pemberitahu, 'DLVH_JABATAN' => $jabatan, 'DLVH_ID' => $cid]);
            }
            # end set

            $ocustdate = date_create($ccustdate);
            $cz_ymd = date_format($ocustdate, 'Ymd');
            $znomor_aju = substr($czkantorasal, 0, 4) . $czdocbctype . $czidmodul . $cz_ymd . $cnoaju;
            $ctglpen = strlen(trim($ctglpen)) == 10 ? $ctglpen : null;
            $cskb_tgl = strlen(trim($cskb_tgl)) == 10 ? $cskb_tgl : null;
            $keys = ['DLV_ID' => $cid];
            $vals = [
                'DLV_NOPEN' => $cnopen, 'DLV_NOAJU' => $cnoaju, 'DLV_ZNOMOR_AJU' => $znomor_aju,
                'DLV_ZJENIS_TPB_ASAL' => $ctpb_asal, 'DLV_ZSKB' => $cskb, 'DLV_ZTANGGAL_SKB' => $cskb_tgl,
                'DLV_FROMOFFICE' => $cfromoffice, "DLV_RPDATE" => $ctglpen,
                'DLV_ZKODE_CARA_ANGKUT' => $cjenis_sarana_pengangkut, 'DLV_SPPBDOC' => $sppbdoc,
                'DLV_LUPDT' => $crnt_dt, 'DLV_USRID' => $this->session->userdata('nama'),
            ];
            $ret = $this->DELV_mod->updatebyVAR($vals, $keys);
            $myar[] = $ret > 0 ? ["cd" => '11', "msg" => "Updated successfully", 'info' => $customsyear] : ["cd" => '00', "msg" => "No data to be updated"];
            if (!empty($cnopen)) {
                if ($this->DELV_mod->check_Primary(['DLV_ID' => $cid, 'DLV_SER' => ''])) {
                    $rs_head_dlv = $this->DELV_mod->select_for_rm_h($cid);
                    foreach ($rs_head_dlv as $r) {
                        if (trim($r['MCUS_CURCD']) === 'RPH') {
                            $myar[] = ['xmsg' => 'update price ok'];
                            $this->DELV_mod->update_zprice($cid, $ccustdate);
                        }
                        break;
                    }
                }
                $this->gotoque($cid);
                # for RPR transaction deduction start from here
                if ($ctglpen) {
                    if ($this->DELV_mod->check_Primary(['DLV_ID' => $cid, 'DLV_SER' => '']) > 0) {
                        $this->NonReffnumberDeliveryConfirmation(['DOC' => $cid, 'DATE' => $ctglpen, 'DATETIME' => $ctglpen . ' 15:15:15']);
                    }
                }
            }
        }
        die(json_encode($myar));
    }

    public function relink_it_inventory()
    {
        header('Content-Type: application/json');
        $cid = $this->input->post('doc');
        $rs = $this->DELV_mod->select_top1_with_columns_where(["ISNULL(DLV_NOPEN,'') DLV_NOPEN"], ['DLV_ID' => $cid]);
        $nomor_pendaftaran = '';
        foreach ($rs as $r) {
            $nomor_pendaftaran = $r['DLV_NOPEN'];
        }
        if (empty($nomor_pendaftaran)) {
            $myar[] = ["cd" => '0', "msg" => "Nomor Pendaftaran is empty"];
        } else {
            $res = $this->gotoque($cid);
            $myar[] = ["cd" => '1', "msg" => "Done, please check IT Inventory", "res" => $res];
        }
        die(json_encode(['status' => $myar]));
    }

    public function change27()
    {
        $crnt_dt = date('Y-m-d H:i:s');
        $cid = $this->input->get('inid');
        $cnoaju = $this->input->get('inaju');
        $cnopen = $this->input->get('innopen');
        $cfromoffice = $this->input->get('infromoffice');
        $cdestoffice = $this->input->get('indestoffice');
        $cpurpose = $this->input->get('inpurpose');
        $ctpb_asal = $this->input->get('injenis_tpb_asal');
        $ctpb_tujuan = $this->input->get('injenis_tpb_tujuan');
        $ctpb_tgl_daftar = $this->input->get('intgldaftar');
        $pemberitahu = $this->input->get('inPemberitahu');
        $jabatan = $this->input->get('inJabatan');
        $cona = $this->input->get('incona');
        $sppbdoc = $this->input->get('sppbdoc');
        $rsaktivasi = $this->AKTIVASIAPLIKASI_imod->selectAll();
        $rs_head_dlv = $this->DELV_mod->select_header_bydo($cid);
        $customsyear = '';
        foreach ($rs_head_dlv as $r) {
            $czdocbctype = $r['DLV_BCTYPE'];
            $ccustdate = $r['DLV_BCDATE'];
            $customsyear = substr($ccustdate, 0, 4);
        }
        if ($cnopen == 'null') {
            $cnopen = '';
        }
        if (strlen($cnoaju) > 2) {
            $where = ['DLV_ID !=' => $cid, 'DLV_NOAJU' => $cnoaju, 'DLV_BCTYPE' => $czdocbctype, 'YEAR(DLV_BCDATE)' => $customsyear, 'DLV_BCDATE' => $ccustdate];
            if ($this->DELV_mod->check_Primary($where)) {
                $myar[] = ["cd" => '00', "msg" => "Nomor Aju is already used"];
                die(json_encode($myar));
            }
        }
        $czidmodul = '';
        $myar = [];
        foreach ($rsaktivasi as $r) {
            $czkantorasal = $r['KPPBC'];
            $czidmodul = substr('00000' . $r['ID_MODUL'], -6);
        }
        $res = '';
        if ($czidmodul == '') {
            $myar[] = ["cd" => '00', "msg" => "Please check Aktivasi CEISA Data"];
        } else {
            # set value, pemberitahu & jabatan
            if ($this->DLVH_mod->check_Primary(['DLVH_ID' => $cid])) {
                $this->DLVH_mod->updatebyVAR(['DLVH_PEMBERITAHU' => $pemberitahu, 'DLVH_JABATAN' => $jabatan], ['DLVH_ID' => $cid]);
            } else {
                $this->DLVH_mod->insert(['DLVH_PEMBERITAHU' => $pemberitahu, 'DLVH_JABATAN' => $jabatan, 'DLVH_ID' => $cid]);
            }
            # end set

            $ocustdate = date_create($ccustdate);
            $cz_ymd = date_format($ocustdate, 'Ymd');
            $znomor_aju = substr($czkantorasal, 0, 4) . $czdocbctype . $czidmodul . $cz_ymd . $cnoaju;
            $ctpb_tgl_daftar = strlen(trim($ctpb_tgl_daftar)) == 10 ? $ctpb_tgl_daftar : null;
            $lastLineLog = $this->CSMLOG_mod->select_lastLine($cid, $znomor_aju) + 1;
            $keys = ['DLV_ID' => $cid];
            $vals = [
                'DLV_NOPEN' => $cnopen, 'DLV_NOAJU' => $cnoaju, 'DLV_ZNOMOR_AJU' => $znomor_aju,
                'DLV_ZJENIS_TPB_ASAL' => $ctpb_asal, 'DLV_ZJENIS_TPB_TUJUAN' => $ctpb_tujuan,
                'DLV_LUPDT' => $crnt_dt, 'DLV_USRID' => $this->session->userdata('nama'),
                'DLV_CONA' => $cona, 'DLV_SPPBDOC' => $sppbdoc,
                'DLV_FROMOFFICE' => $cfromoffice, 'DLV_DESTOFFICE' => $cdestoffice, 'DLV_PURPOSE' => $cpurpose,
                'DLV_ZID_MODUL' => $czidmodul, 'DLV_RPDATE' => $ctpb_tgl_daftar,
            ];
            $ret = $this->DELV_mod->updatebyVAR($vals, $keys);
            $msg = $ret > 0 ? 'update transaction' : '';
            $this->CSMLOG_mod->insert([
                'CSMLOG_DOCNO' => $cid,
                'CSMLOG_SUPZAJU' => $znomor_aju,
                'CSMLOG_SUPZNOPEN' => $ctpb_tgl_daftar,
                'CSMLOG_DESC' => $msg,
                'CSMLOG_LINE' => $lastLineLog,
                'CSMLOG_TYPE' => 'OUT',
                'CSMLOG_CREATED_AT' => $crnt_dt,
                'CSMLOG_CREATED_BY' => $this->session->userdata('nama'),
            ]);
            if (!empty($cnopen)) {
                $res = $this->gotoque($cid);
                # for RPR transaction deduction start from here
                if ($ctpb_tgl_daftar) {
                    if ($this->DELV_mod->check_Primary(['DLV_ID' => $cid, 'DLV_SER' => '']) > 0) {
                        $this->NonReffnumberDeliveryConfirmation(['DOC' => $cid, 'DATE' => $ctpb_tgl_daftar, 'DATETIME' => $ctpb_tgl_daftar . ' 15:15:15']);
                    }
                }
            }
            $myar[] = $ret > 0 ? ["cd" => '11', "msg" => "Updated successfully", "res" => $res] : ["cd" => '00', "msg" => "No data to be updated", "res" => $res];
        }
        die(json_encode($myar));
    }

    public function testIsNonUnique()
    {
        $cid = $this->input->get('doc');
        echo $this->DELV_mod->check_Primary(['DLV_ID' => $cid, 'DLV_SER' => '']);
    }

    public function NonReffnumberDeliveryConfirmation($data)
    {
        $dedicatedWarehouse = ['AIWH1', 'NRWH2', 'ARWH9SC'];
        $rsrm = $this->DELV_mod->select_det_rm_byid($data['DOC']);
        $resith = 0;
        foreach ($rsrm as $r) {
            if ($this->ITH_mod->check_Primary(["ITH_ITMCD" => $r['DLV_ITMCD'], "ITH_FORM" => "OUT-SHP-RM", "ITH_DOC" => $data['DOC']]) == 0) {
                # for MITM_MODEL, set warehouse to PSIEQUIP
                # to prevent user from wrong choise in setting warehouse
                if ($r['MITM_MODEL'] == '6') {
                    $datam = [
                        "ITH_ITMCD" => $r['DLV_ITMCD'],
                        "ITH_DATE" => $data['DATE'],
                        "ITH_FORM" => "OUT-SHP-RM",
                        "ITH_DOC" => $data['DOC'],
                        "ITH_QTY" => -1 * $r['BCQT'],
                        "ITH_WH" => 'PSIEQUIP',
                        "ITH_LUPDT" => $data['DATETIME'],
                        "ITH_USRID" => $this->session->userdata('nama'),
                    ];
                } else {
                    if (in_array($r['DLV_LOCFR'], $dedicatedWarehouse)) {
                        $datam = [
                            "ITH_ITMCD" => $r['DLV_ITMCD'],
                            "ITH_DATE" => $data['DATE'],
                            "ITH_FORM" => "OUT-SHP-RM",
                            "ITH_DOC" => $data['DOC'],
                            "ITH_QTY" => -1 * $r['BCQT'],
                            "ITH_WH" => $r['DLV_LOCFR'],
                            "ITH_LUPDT" => $data['DATETIME'],
                            "ITH_USRID" => $this->session->userdata('nama'),
                        ];
                    } else {
                        $datam = [
                            "ITH_ITMCD" => $r['DLV_ITMCD'],
                            "ITH_DATE" => $data['DATE'],
                            "ITH_FORM" => "OUT-SHP-RM",
                            "ITH_DOC" => $data['DOC'],
                            "ITH_QTY" => -1 * $r['BCQT'],
                            "ITH_WH" => "ARWH0PD",
                            "ITH_LUPDT" => $data['DATETIME'],
                            "ITH_USRID" => $this->session->userdata('nama'),
                        ];
                    }
                }
                $resith += $this->ITH_mod->insert($datam);
            }
        }
        return $resith;
    }

    public function testdeduction()
    {
        $ctpb_tgl_daftar = $this->input->get('intgldaftar');
        $cid = $this->input->get('inid');

        if ($ctpb_tgl_daftar) {
            if ($this->DELV_mod->check_Primary(['DLV_ID' => $cid, 'DLV_SER' => '']) > 0) {
                $this->NonReffnumberDeliveryConfirmation(['DOC' => $cid, 'DATE' => $ctpb_tgl_daftar, 'DATETIME' => $ctpb_tgl_daftar . ' 15:15:15']);
            }
        }
        echo 'done ' . date('Y-m-d H:i:s');
    }

    public function change41()
    {
        $crnt_dt = date('Y-m-d H:i:s');
        $cid = $this->input->get('inid');
        $cnoaju = $this->input->get('inaju');
        $cnopen = $this->input->get('innopen');
        $cfromoffice = $this->input->get('infromoffice');
        $cpurpose = $this->input->get('inpurpose');
        $ctpb_asal = $this->input->get('injenis_tpb_asal');
        $ctpb_tgl_daftar = $this->input->get('intgldaftar');
        $cona = $this->input->get('incona');
        $sppbdoc = $this->input->get('sppbdoc');
        $pemberitahu = $this->input->get('inPemberitahu');
        $jabatan = $this->input->get('inJabatan');
        $rsaktivasi = $this->AKTIVASIAPLIKASI_imod->selectAll();
        $rs_head_dlv = $this->DELV_mod->select_header_bydo($cid);
        $ttlPostedRows = $this->DELV_mod->check_Primary(['DLV_ID' => $cid, 'DLV_POSTTM IS NOT NULL' => null]);
        $ttlPostedAndHasbc = $this->DELV_mod->check_Primary(['DLV_ID' => $cid, "ISNULL(DLV_NOPEN,'')" => '']);
        $customsyear = '';
        foreach ($rs_head_dlv as $r) {
            $czdocbctype = $r['DLV_BCTYPE'];
            $ccustdate = $r['DLV_BCDATE'];
            $customsyear = substr($ccustdate, 0, 4);
        }
        if (strlen($cnoaju) > 2) {
            if ($this->DELV_mod->check_Primary(['DLV_ID !=' => $cid, 'DLV_NOAJU' => $cnoaju, 'DLV_BCTYPE' => $czdocbctype, 'YEAR(DLV_BCDATE)' => $customsyear])) {
                $myar[] = ["cd" => '00', "msg" => "Nomor Aju is already used"];
                die(json_encode($myar));
            }
        }
        if ($cnopen == 'null') {
            $cnopen = '';
        }
        if ($ttlPostedRows > 0 && $ttlPostedAndHasbc === 0) {
            if (!empty($cnopen)) {
                $this->gotoque($cid);
            }
            $myar[] = ["cd" => '00', "msg" => "could not update, because the data is already posted"];
            die(json_encode($myar));
        }
        $czidmodul = '';
        $myar = [];

        foreach ($rsaktivasi as $r) {
            $czkantorasal = $r['KPPBC'];
            $czidmodul = substr('00000' . $r['ID_MODUL'], -6);
        }
        if ($czidmodul == '') {
            $myar[] = ["cd" => '00', "msg" => "Please check Aktivasi CEISA Data"];
        } else {
            # set value, pemberitahu & jabatan
            if ($this->DLVH_mod->check_Primary(['DLVH_ID' => $cid])) {
                $this->DLVH_mod->updatebyVAR(['DLVH_PEMBERITAHU' => $pemberitahu, 'DLVH_JABATAN' => $jabatan], ['DLVH_ID' => $cid]);
            } else {
                $this->DLVH_mod->insert(['DLVH_PEMBERITAHU' => $pemberitahu, 'DLVH_JABATAN' => $jabatan, 'DLVH_ID' => $cid]);
            }
            # end set
            $ocustdate = date_create($ccustdate);
            $cz_ymd = date_format($ocustdate, 'Ymd');
            $znomor_aju = substr($czkantorasal, 0, 4) . $czdocbctype . $czidmodul . $cz_ymd . $cnoaju;
            $ctpb_tgl_daftar = strlen(trim($ctpb_tgl_daftar)) == 10 ? $ctpb_tgl_daftar : null;
            $keys = ['DLV_ID' => $cid];
            $vals = [
                'DLV_NOPEN' => $cnopen, 'DLV_NOAJU' => $cnoaju, 'DLV_ZNOMOR_AJU' => $znomor_aju,
                'DLV_ZJENIS_TPB_ASAL' => $ctpb_asal,
                'DLV_CONA' => $cona, 'DLV_SPPBDOC' => $sppbdoc,
                'DLV_LUPDT' => $crnt_dt, 'DLV_USRID' => $this->session->userdata('nama'),
                'DLV_FROMOFFICE' => $cfromoffice, 'DLV_PURPOSE' => $cpurpose,
                'DLV_ZID_MODUL' => $czidmodul, 'DLV_RPDATE' => $ctpb_tgl_daftar,
            ];
            $ret = $this->DELV_mod->updatebyVAR($vals, $keys);
            $myar[] = $ret > 0 ? ["cd" => '11', "msg" => "Updated successfully"] : ["cd" => '00', "msg" => "No data to be updated"];
            if (!empty($cnopen)) {
                $this->gotoque($cid);
                if ($ctpb_tgl_daftar) {
                    if ($this->DELV_mod->check_Primary(['DLV_ID' => $cid, 'DLV_SER' => '']) > 0) {
                        $this->NonReffnumberDeliveryConfirmation(['DOC' => $cid, 'DATE' => $ctpb_tgl_daftar, 'DATETIME' => $ctpb_tgl_daftar . ' 15:15:15']);
                    }
                }
            }
        }
        die(json_encode($myar));
    }

    public function change261()
    {
        $crnt_dt = date('Y-m-d H:i:s');
        $cid = $this->input->get('inid');
        $cnoaju = $this->input->get('inaju');
        $cnopen = $this->input->get('innopen');
        $cfromoffice = $this->input->get('infromoffice');
        $cpurpose = $this->input->get('inpurpose');
        $ctpb_asal = $this->input->get('injenis_tpb_asal');
        $ctpb_tgl_daftar = $this->input->get('intgldaftar');
        $cona = $this->input->get('incona');
        $cjenis_sarana_pengangkut = $this->input->get('injenis_sarana');
        $rsaktivasi = $this->AKTIVASIAPLIKASI_imod->selectAll();
        $rs_head_dlv = $this->DELV_mod->select_header_bydo($cid);
        $ttlPostedRows = $this->DELV_mod->check_Primary(['DLV_ID' => $cid, 'DLV_POSTTM IS NOT NULL' => null]);
        $ttlPostedAndHasbc = $this->DELV_mod->check_Primary(['DLV_ID' => $cid, "ISNULL(DLV_NOPEN,'')" => '']);
        $customsyear = '';
        foreach ($rs_head_dlv as $r) {
            $czdocbctype = $r['DLV_BCTYPE'];
            $ccustdate = $r['DLV_BCDATE'];
            $customsyear = substr($ccustdate, 0, 4);
        }
        if (strlen($cnoaju) > 2) {
            if ($this->DELV_mod->check_Primary(['DLV_ID !=' => $cid, 'DLV_NOAJU' => $cnoaju, 'DLV_BCTYPE' => $czdocbctype, 'YEAR(DLV_BCDATE)' => $customsyear])) {
                $myar[] = ["cd" => '00', "msg" => "Nomor Aju is already used"];
                die(json_encode($myar));
            }
        }
        if ($cnopen == 'null') {
            $cnopen = '';
        }
        if ($ttlPostedRows > 0 && $ttlPostedAndHasbc === 0) {
            $myar[] = ["cd" => '00', "msg" => "could not update, because the data is already posted"];
            die(json_encode($myar));
        }
        $czidmodul = '';
        $myar = [];

        foreach ($rsaktivasi as $r) {
            $czkantorasal = $r['KPPBC'];
            $czidmodul = substr('00000' . $r['ID_MODUL'], -6);
        }
        if ($czidmodul == '') {
            $myar[] = ["cd" => '00', "msg" => "Please check Aktivasi CEISA Data"];
        } else {
            $ocustdate = date_create($ccustdate);
            $cz_ymd = date_format($ocustdate, 'Ymd');
            $znomor_aju = substr($czkantorasal, 0, 4) . substr($czdocbctype, 0, 2) . $czidmodul . $cz_ymd . $cnoaju;
            $ctpb_tgl_daftar = strlen(trim($ctpb_tgl_daftar)) == 10 ? $ctpb_tgl_daftar : null;
            $keys = ['DLV_ID' => $cid];
            $vals = [
                'DLV_NOPEN' => $cnopen, 'DLV_NOAJU' => $cnoaju, 'DLV_ZNOMOR_AJU' => $znomor_aju,
                'DLV_ZJENIS_TPB_ASAL' => $ctpb_asal,
                'DLV_CONA' => $cona,
                'DLV_ZKODE_CARA_ANGKUT' => $cjenis_sarana_pengangkut,
                'DLV_LUPDT' => $crnt_dt, 'DLV_USRID' => $this->session->userdata('nama'),
                'DLV_FROMOFFICE' => $cfromoffice, 'DLV_PURPOSE' => $cpurpose,
                'DLV_ZID_MODUL' => $czidmodul, 'DLV_RPDATE' => $ctpb_tgl_daftar,
            ];
            $ret = $this->DELV_mod->updatebyVAR($vals, $keys);
            $myar[] = $ret > 0 ? ["cd" => '11', "msg" => "Updated successfully"] : ["cd" => '00', "msg" => "No data to be updated"];
            if (!empty($cnopen)) {
                $this->gotoque($cid);
                # for RPR transaction deduction start from here
                if ($ctpb_tgl_daftar) {
                    if ($this->DELV_mod->check_Primary(['DLV_ID' => $cid, 'DLV_SER' => '']) > 0) {
                        $this->NonReffnumberDeliveryConfirmation(['DOC' => $cid, 'DATE' => $ctpb_tgl_daftar, 'DATETIME' => $ctpb_tgl_daftar . ' 15:15:15']);
                    }
                }
            }
        }
        die(json_encode($myar));
    }
    public function change30()
    {
        $crnt_dt = date('Y-m-d H:i:s');
        $cid = $this->input->get('inid');
        $cnoaju = $this->input->get('inaju');
        $cnopen = $this->input->get('innopen');
        $ctpb_tgl_daftar = $this->input->get('intgldaftar');
        if ($cnopen == 'null') {
            $cnopen = '';
        }

        $myar = [];

        $ctpb_tgl_daftar = strlen(trim($ctpb_tgl_daftar)) == 10 ? $ctpb_tgl_daftar : null;
        $keys = ['DLV_ID' => $cid];
        $vals = [
            'DLV_NOPEN' => $cnopen, 'DLV_NOAJU' => substr($cnoaju, -6), 'DLV_ZNOMOR_AJU' => $cnoaju,
            'DLV_LUPDT' => $crnt_dt, 'DLV_USRID' => $this->session->userdata('nama'),
            'DLV_RPDATE' => $ctpb_tgl_daftar,
        ];
        $ret = $this->DELV_mod->updatebyVAR($vals, $keys);
        $myar[] = $ret > 0 ? ["cd" => '11', "msg" => "Updated successfully"] : ["cd" => '00', "msg" => "No data to be updated"];
        if (!empty($cnopen)) {
            $this->gotoque($cid);
        }
        die(json_encode($myar));
    }

    public function checkSession()
    {
        $myar = [];
        if ($this->session->userdata('status') != "login") {
            $myar[] = ["cd" => "0", "msg" => "Session is expired please reload page"];
            exit(json_encode($myar));
        }
    }

    public function approve()
    {
        $this->checkSession();
        $crnt_dt = date('Y-m-d H:i:s');
        $cid = $this->input->post('inid');
        $cdata = ['DLV_APPRV' => $this->session->userdata('nama'), 'DLV_APPRVTM' => $crnt_dt];
        $cwhere = ['DLV_ID' => $cid, 'DLV_APPRV IS NULL' => null];
        $myar = [];
        $cret = $this->DELV_mod->updatebyVAR($cdata, $cwhere);
        $myar[] = ($cret > 0) ? ["cd" => "1", "msg" => "Approved", "approved_time" => $crnt_dt] : ["cd" => "0", "msg" => "Could not approve, because it has been approved"];
        die(json_encode($myar));
    }

    public function get_do_bom_as_xls()
    {
        if (!isset($_COOKIE["CKPSI_DOBOM"])) {
            exit('no data to be found');
        }
        $do = $_COOKIE["CKPSI_DOBOM"];
        $rs = $this->DELV_mod->select_bom($do);
        $spreadsheet = new Spreadsheet();
        $sheet = $spreadsheet->getActiveSheet();
        $sheet->setTitle('DOBOM');
        $sheet->setCellValueByColumnAndRow(1, 1, 'DO : ' . $do);
        $sheet->setCellValueByColumnAndRow(1, 2, 'ASSY CODE');
        $sheet->setCellValueByColumnAndRow(2, 2, 'FG QTY');
        $sheet->setCellValueByColumnAndRow(3, 2, 'ITEM CODE');
        $sheet->setCellValueByColumnAndRow(4, 2, 'USE QTY');
        $sheet->setCellValueByColumnAndRow(5, 2, 'SUPPLIED QTY');
        $sheet->setCellValueByColumnAndRow(6, 2, 'REFF NO');
        $sheet->setCellValueByColumnAndRow(7, 2, 'JOB');

        $no = 3;
        $isSubAssyFound = false;
        $smtFontBold = ['font' => ['bold' => true]];
        foreach ($rs as $r) {
            if ($r['MITM_MODEL'] == '1') {
                $isSubAssyFound = true;
                $sheet->getStyle('C' . $no . ':C' . $no)->applyFromArray($smtFontBold);
            }
            $sheet->setCellValueByColumnAndRow(1, $no, $r['SER_ITMID']);
            $sheet->setCellValueByColumnAndRow(2, $no, $r['SERD2_FGQTY']);
            $sheet->setCellValueByColumnAndRow(3, $no, $r['SERD2_ITMCD']);
            $sheet->setCellValueByColumnAndRow(4, $no, $r['MYPER']);
            $sheet->setCellValueByColumnAndRow(5, $no, $r['SERD2_FGQTY'] * $r['MYPER']);
            $sheet->setCellValueByColumnAndRow(6, $no, $r['SERD2_SER']);
            $sheet->setCellValueByColumnAndRow(7, $no, $r['SER_DOC']);
            $no++;
        }
        $sheet->getColumnDimension('A')->setAutoSize(true);
        $sheet->getColumnDimension('B')->setAutoSize(true);
        $sheet->getColumnDimension('C')->setAutoSize(true);
        $sheet->getColumnDimension('D')->setAutoSize(true);
        $sheet->getColumnDimension('E')->setAutoSize(true);
        $sheet->getColumnDimension('F')->setAutoSize(true);
        $sheet->getColumnDimension('G')->setAutoSize(true);

        #CREATE NEW SHEET FOR SUB ASSY DATA, IF EXIST
        if ($isSubAssyFound) {
            $sheet = $spreadsheet->createSheet();
            $sheet->setTitle("SUB_ASSY");
            $sheet->setCellValueByColumnAndRow(1, 1, 'DO : ' . $do);
            $sheet->setCellValueByColumnAndRow(1, 2, 'ASSY CODE');
            $sheet->setCellValueByColumnAndRow(2, 2, 'FG QTY');
            $sheet->setCellValueByColumnAndRow(3, 2, 'ITEM CODE');
            $sheet->setCellValueByColumnAndRow(4, 2, 'USE QTY');
            $sheet->setCellValueByColumnAndRow(5, 2, 'SUPPLIED QTY');
            $sheet->setCellValueByColumnAndRow(6, 2, 'REFF NO');
            $sheet->setCellValueByColumnAndRow(7, 2, 'JOB');
            $sheet->setCellValueByColumnAndRow(8, 2, 'MAIN REFF NO');
            $rs = $this->DELV_mod->select_bom_subassy($do);
            $no = 3;
            foreach ($rs as $r) {
                $sheet->setCellValueByColumnAndRow(1, $no, $r['SER_ITMID']);
                $sheet->setCellValueByColumnAndRow(2, $no, $r['SERD2_FGQTY']);
                $sheet->setCellValueByColumnAndRow(3, $no, $r['SERD2_ITMCD']);
                $sheet->setCellValueByColumnAndRow(4, $no, $r['MYPER']);
                $sheet->setCellValueByColumnAndRow(5, $no, $r['SERD2_FGQTY'] * $r['MYPER']);
                $sheet->setCellValueByColumnAndRow(6, $no, $r['SERD2_SER']);
                $sheet->setCellValueByColumnAndRow(7, $no, $r['SER_DOC']);
                $sheet->setCellValueByColumnAndRow(8, $no, $r['DLV_SER']);
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
        }
        #END CREATE

        $dodis = str_replace("/", "#", $do);
        $stringjudul = "DO BOM " . $dodis;
        $writer = new Xlsx($spreadsheet);
        $filename = $stringjudul; //save our workbook as this file name

        header('Content-Type: application/vnd.ms-excel');
        header('Content-Disposition: attachment;filename="' . $filename . '.xlsx"');
        header('Cache-Control: max-age=0');
        $writer->save('php://output');
    }
    public function export_to_so_mega_as_xls()
    {
        if (!isset($_COOKIE["CKPSI_DOBOM"])) {
            exit('no data to be found');
        }
        $do = $_COOKIE["CKPSI_DOBOM"];

        $this->setPrice(base64_encode($do));
        $rs = $this->DELV_mod->select_shipping_for_mega($do);
        $spreadsheet = new Spreadsheet();
        $sheet = $spreadsheet->getActiveSheet();
        $sheet->setTitle('shippingmega');

        $sheet->setCellValueByColumnAndRow(1, 1, 'TRANS NO');
        $sheet->setCellValueByColumnAndRow(2, 1, 'TRANS DATE');
        $sheet->setCellValueByColumnAndRow(3, 1, 'ITEM CODE');
        $sheet->setCellValueByColumnAndRow(4, 1, 'DELIVERY QTY');
        $sheet->setCellValueByColumnAndRow(5, 1, 'ORDER NO.');
        $sheet->setCellValueByColumnAndRow(6, 1, 'CPO Line No');
        $sheet->getStyle('A1:F1')->getFill()->setFillType(\PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID)
            ->getStartColor()->setARGB('98D1FC');
        foreach (range('A', 'F') as $columnID) {
            $sheet->getColumnDimension($columnID)
                ->setAutoSize(true);
        }
        $no = 2;
        foreach ($rs as $r) {
            $sheet->setCellValueByColumnAndRow(1, $no, $do);
            $sheet->setCellValueByColumnAndRow(2, $no, $r['DLV_BCDATE']);
            $sheet->setCellValueByColumnAndRow(3, $no, trim($r['SER_ITMID']));
            $sheet->setCellValueByColumnAndRow(4, $no, $r['PLTQTY']);
            $sheet->setCellValueByColumnAndRow(5, $no, $r['DLVPRC_CPO']);
            $sheet->setCellValueByColumnAndRow(6, $no, $r['DLVPRC_CPOLINE']);
            $no++;
        }

        $dodis = str_replace("/", " ", $do);

        $stringjudul = $dodis;
        $writer = new Xlsx($spreadsheet);
        $filename = $stringjudul; //save our workbook as this file name

        header('Content-Type: application/vnd.ms-excel');
        header('Content-Disposition: attachment;filename="' . $filename . '.xlsx"');
        header('Cache-Control: max-age=0');
        $writer->save('php://output');
    }

    public function posting27rtn()
    {
        set_exception_handler([$this, 'exception_handler']);
        set_error_handler([$this, 'log_error']);
        register_shutdown_function([$this, 'fatal_handler']);

        header('Content-Type: application/json');
        ini_set('max_execution_time', '-1');
        $currentDate = date('Y-m-d H:i:s');
        $csj = $this->input->get('insj');
        $czsj = $csj;
        $rsaktivasi = $this->AKTIVASIAPLIKASI_imod->selectAll();
        $rs_head_dlv = $this->DELV_mod->select_header_bydo($csj);
        $cz_KODE_JENIS_TPB = '';
        $czkantortujuan = '';
        $cz_KODE_TUJUAN_TPB = '';
        $czidpenerima = '';
        $cznmpenerima = '';
        $czalamatpenerima = '';
        $czizinpenerima = '';
        $czcurrency = '';
        $cznamapengangkut = '';
        $cznomorpolisi = '';
        $ccustdate = '';
        $czdocbctype = '';
        $czinvoice = '';
        $czinvoicedt = '';
        $cztujuanpengiriman = '';
        $consignee = '-';
        $nomoraju = '';
        $cbusiness_group = '';
        $ccustomer_do = '';
        foreach ($rs_head_dlv as $r) {
            if ($r['DLV_BCDATE']) {
                $consignee = $r['DLV_CONSIGN'];
                $czdocbctype = $r['DLV_BCTYPE'];
                $ccustdate = $r['DLV_BCDATE'];
                $nomoraju = $r['DLV_NOAJU'];
                $ccustomer_do = $r['DLV_CUSTDO'];
                $czinvoice = trim($r['DLV_INVNO']);
                $cbusiness_group = $r['DLV_BSGRP'];
                $czcurrency = trim($r['MCUS_CURCD']);
                $cz_KODE_JENIS_TPB = $r['DLV_ZJENIS_TPB_ASAL'];
                $cz_KODE_TUJUAN_TPB = $r['DLV_ZJENIS_TPB_TUJUAN'];
                $cznmpenerima = $r['MDEL_ZNAMA'];
                $czalamatpenerima = $r['MDEL_ADDRCUSTOMS'];
                $czizinpenerima = $r['MDEL_ZSKEP'];
                $cznomorpolisi = $r['DLV_TRANS'];
                $cznamapengangkut = $r['MSTTRANS_TYPE'];
                $czinvoicedt = $r['DLV_INVDT'];
                $czidpenerima = str_replace([".", "-"], "", $r['MCUS_TAXREG']);
                $cztujuanpengiriman = $r['DLV_PURPOSE'];
                $czkantortujuan = $r['DLV_DESTOFFICE'];
                break;
            }
        }
        if ($cztujuanpengiriman == '-') {
            $myar[] = ['cd' => 0, 'msg' => 'please set TUJUAN PENGIRIMAN (' . $cztujuanpengiriman . ')'];
            die('{"status" : ' . json_encode($myar) . '}');
        }

        if ($cztujuanpengiriman == '1' || $cztujuanpengiriman == '6') {
        } else {
            $myar[] = ['cd' => 0, 'msg' => 'please set TUJUAN PENGIRIMAN correctly'];
            die('{"status" : ' . json_encode($myar) . '}');
        }

        if ($consignee == '-') {
            $myar[] = ['cd' => 0, 'msg' => 'please set consignment (' . $consignee . ')'];
            die('{"status" : ' . json_encode($myar) . '}');
        }

        if ($czinvoice == '') {
            $myar[] = ['cd' => 0, 'msg' => 'please add invoice number (' . $czinvoice . ') custdate (' . $ccustdate . ') consign (' . $consignee . ')'];
            die('{"status" : ' . json_encode($myar) . '}');
        }
        $ocustdate = date_create($ccustdate);
        $cz_ymd = date_format($ocustdate, 'Ymd');
        $czidpengusaha = '';
        $cznmpengusaha = '';
        $czalamatpengusaha = '';
        $czizinpengusaha = '';
        foreach ($rsaktivasi as $r) {
            $czkantorasal = $r['KPPBC'];
            $czidmodul = substr('00000' . $r['ID_MODUL'], -6);
            $czidmodul_asli = $r['ID_MODUL'];
            $czidpengusaha = $r['NPWP'];
            $cznmpengusaha = $r['NAMA_PENGUSAHA'];
            $czalamatpengusaha = $r['ALAMAT_PENGUSAHA'];
            $czizinpengusaha = $r['NOMOR_SKEP'];
        }

        if ($czdocbctype == '-') {
            $myar[] = ['cd' => 0, 'msg' => 'please select bc type!'];
            die('{"status" : ' . json_encode($myar) . '}');
        }

        if (strlen($nomoraju) != 6) {
            $myar[] = ['cd' => 0, 'msg' => 'NOMOR AJU is not valid, please re-check (' . $nomoraju . ')'];
            die('{"status" : ' . json_encode($myar) . '}');
        }

        $cnoaju = substr($czkantorasal, 0, 4) . $czdocbctype . $czidmodul . $cz_ymd . $nomoraju;

        if ($this->TPB_HEADER_imod->check_Primary(['NOMOR_AJU' => $cnoaju]) > 0) {
            $myar[] = ['cd' => 0, 'msg' => 'the NOMOR AJU is already posted'];
            die('{"status" : ' . json_encode($myar) . '}');
        }

        if (($cbusiness_group == 'PSI2PPZOMC' || $cbusiness_group == 'PSI2PPZOMI')) {
            if (strlen($ccustomer_do) < 5) {
                $myar[] = ['cd' => 0, 'msg' => 'please enter valid Customer DO!'];
                die('{"status" : ' . json_encode($myar) . '}');
            }
            $czsj = $ccustomer_do;
        }
        if (strlen($nomoraju) != 6) {
            $myar[] = ['cd' => 0, 'msg' => 'please enter valid Nomor Pengajuan!'];
            die('{"status" : ' . json_encode($myar) . '}');
        }

        if (strlen($ccustdate) != 10) {
            $myar[] = ['cd' => 0, 'msg' => 'please enter valid customs date!'];
            die('{"status" : ' . json_encode($myar) . '}');
        }

        if ($this->DELV_mod->check_Primary(['DLV_ID' => $csj]) == 0) {
            $myar[] = ['cd' => 0, 'msg' => 'DO is not found'];
            die('{"status" : ' . json_encode($myar) . '}');
        }

        $rscurr = $this->MEXRATE_mod->selectfor_posting($ccustdate, $czcurrency);
        if (count($rscurr) == 0) {
            $myar[] = ["cd" => "0", "msg" => "Please fill exchange rate data !"];
            die('{"status":' . json_encode($myar) . '}');
        } else {
            foreach ($rscurr as $r) {
                $czharga_matauang = $r->MEXRATE_VAL;
                break;
            }
        }

        $rswhSI = $this->SI_mod->select_wh_by_txid($csj);

        #HEADER_HARGA
        $cz_h_CIF_FG = 0;
        $cz_h_HARGA_PENYERAHAN_FG = 0;
        if ($rswhSI === '??') {
            $myar[] = ["cd" => "0", "msg" => "SI WH is not recognized"];
            die(json_encode(['status' => $myar]));
        }
        $rsitem_p_price = [];
        $rspackinglist = $this->DELV_mod->select_packinglist_bydono($csj);
        $rspickingres = $this->SISCN_mod->select_exbc_fgrtn($csj);
        $cz_h_NETTO = 0;
        $cz_h_BRUTO = 0;
        $SERI_BARANG = 1;
        $aspBOX = 0;
        $tpb_barang = [];
        $tpb_bahan_baku = [];
        $tpb_barang_temp = [];
        $cz_JUMLAH_KEMASAN = 0;
        if ($rswhSI === 'AFWH3RT') {
            $rsitem_p_price = $this->setPriceRS(base64_encode($csj));
            $cz_h_JUMLAH_BARANG = count($rsitem_p_price);
            #INIT PRICE
            $rsresume = [];
            $rsmultiprice = [];
            foreach ($rsitem_p_price as &$k) {
                if ($k['SISO_SOLINE'] == 'X') {
                    $rs_mst_price = $this->XSO_mod->select_latestprice($k['SI_BSGRP'], $k['SI_CUSCD'], "'" . $k['SSO2_MDLCD'] . "'");
                    foreach ($rs_mst_price as $r) {
                        $k['SSO2_SLPRC'] = substr($r['MSPR_SLPRC'], 0, 1) == '.' ? '0' . $r['MSPR_SLPRC'] : $r['MSPR_SLPRC'];
                        $k['CIF'] = $k['SSO2_SLPRC'] * $k['SISOQTY'];
                    }
                }
                $isfound = false;
                foreach ($rsresume as &$n) {
                    if ($n['RSI_ITMCD'] == $k['SSO2_MDLCD'] && $n['RSSO2_SLPRC'] != $k['SSO2_SLPRC']) {
                        $n['RCOUNT']++;
                        $isfound = true;
                        break;
                    }
                }
                unset($n);

                if (!$isfound) {
                    $rsresume[] = [
                        'RSI_ITMCD' => $k['SSO2_MDLCD'], 'RSSO2_SLPRC' => $k['SSO2_SLPRC'], 'RCOUNT' => 1,
                    ];
                }
            }
            unset($k);

            foreach ($rsresume as $k) {
                if ($k['RCOUNT'] > 1) {
                    $rsmultiprice[] = $k;
                }
            }

            if (count($rsmultiprice) > 0) {
                $myar[] = ["cd" => "0", "msg" => "Multi price detected please, click 'Price Detail' to confirm "];
                die('{"status":' . json_encode($myar) . ',"data":' . json_encode($rsitem_p_price) . ',"data2":' . json_encode($rsmultiprice) . '}');
            }

            foreach ($rsitem_p_price as $r) {
                $t_HARGA_PENYERAHAN = $r['CIF'] * $czharga_matauang;
                $cz_h_NETTO += $r['NWG'];
                $cz_h_HARGA_PENYERAHAN_FG += $t_HARGA_PENYERAHAN;
                $tpb_barang_temp[] = [
                    'KODE_BARANG' => $r['SSO2_MDLCD'], 'POS_TARIF' => $r['MITM_HSCD'], 'URAIAN' => $r['MITM_ITMD1'], 'JUMLAH_SATUAN' => $r['SISOQTY'], 'KODE_SATUAN' => $r['MITM_STKUOM'] == 'PCS' ? 'PCE' : $r['MITM_STKUOM'], 'NETTO' => 1, 'CIF' => round($r['CIF'], 2), 'HARGA_PENYERAHAN' => $t_HARGA_PENYERAHAN, 'SERI_BARANG' => $SERI_BARANG, 'KODE_STATUS' => '02', 'PERPRICE' => $r['SSO2_SLPRC'],
                ];
                $SERI_BARANG++;
            }
            foreach ($tpb_barang_temp as $r) {
                $tpb_barang[] = [
                    'KODE_BARANG' => $r['KODE_BARANG'], 'POS_TARIF' => $r['POS_TARIF'], 'URAIAN' => $r['URAIAN'], 'JUMLAH_SATUAN' => $r['JUMLAH_SATUAN'], 'KODE_SATUAN' => $r['KODE_SATUAN'], 'NETTO' => $r['NETTO'], 'CIF' => $r['CIF'], 'HARGA_PENYERAHAN' => $r['HARGA_PENYERAHAN'], 'SERI_BARANG' => $r['SERI_BARANG'], 'KODE_STATUS' => $r['KODE_STATUS'],
                ];
            }
        } elseif ($rswhSI === 'NFWH4RT') {
            foreach ($rspickingres as $r) {
                $isplot = false;
                $CIF = $r['RCV_PRPRC'] * $r['INTQTY'];
                $t_HARGA_PENYERAHAN = $CIF * $czharga_matauang;
                $cz_JUMLAH_KEMASAN += $r['BOX'];
                foreach ($tpb_barang_temp as &$p) {
                    if ($r['SI_ITMCD'] == $p['KODE_BARANG'] && $r['RCV_PRPRC'] == $p['PERPRICE']) {
                        $p['JUMLAH_SATUAN'] += $r['INTQTY'];
                        $p['CIF'] = $p['JUMLAH_SATUAN'] * $p['PERPRICE'];
                        $p['HARGA_PENYERAHAN'] = $p['CIF'] * $czharga_matauang;
                        $p['NETTO'] += $r['NWG'];
                        $isplot = true;
                        break;
                    }
                }
                unset($p);
                if (!$isplot) {
                    $tpb_barang_temp[] = [
                        'KODE_BARANG' => $r['SI_ITMCD'], 'POS_TARIF' => $r['RCV_HSCD'], 'URAIAN' => $r['MITM_ITMD1'], 'JUMLAH_SATUAN' => $r['INTQTY'], 'KODE_SATUAN' => $r['MITM_STKUOM'] == 'PCS' ? 'PCE' : $r['MITM_STKUOM'], 'NETTO' => $r['NWG'] * 1, 'CIF' => round($CIF, 2), 'HARGA_PENYERAHAN' => $t_HARGA_PENYERAHAN, 'SERI_BARANG' => $SERI_BARANG, 'KODE_STATUS' => '02', 'PERPRICE' => $r['RCV_PRPRC'],
                    ];
                    $SERI_BARANG++;
                }
            }
            foreach ($tpb_barang_temp as $r) {
                $cz_h_HARGA_PENYERAHAN_FG += $r['HARGA_PENYERAHAN'];
                $tpb_barang[] = [
                    'KODE_BARANG' => $r['KODE_BARANG'], 'POS_TARIF' => $r['POS_TARIF'], 'URAIAN' => $r['URAIAN'], 'JUMLAH_SATUAN' => $r['JUMLAH_SATUAN'], 'KODE_SATUAN' => $r['KODE_SATUAN'], 'NETTO' => $r['NETTO'], 'CIF' => $r['CIF'], 'HARGA_PENYERAHAN' => $r['HARGA_PENYERAHAN'], 'SERI_BARANG' => $r['SERI_BARANG'], 'KODE_STATUS' => $r['KODE_STATUS'],
                ];
            }
        } else {
            $myar[] = ["cd" => "0", "msg" => "SI WH should be AFWH3RT or NFWH4RT"];
            die(json_encode(['status' => $myar]));
        }
        foreach ($rspackinglist as $r) {
            $cz_h_BRUTO += $r['MITM_GWG'];
        }

        if ($aspBOX) {
            $cz_JUMLAH_KEMASAN = $aspBOX;
        }

        $cz_h_JUMLAH_BARANG = count($tpb_barang_temp);

        #START REQUEST EXBC_ITEM CHANGES
        $rsitemchanges = $this->DELV_mod->select_rm_rtn_bydoc($csj);
        $rsallitem_cd = [];
        $rsallitem_hscd = [];
        $rsallitem_qty = [];
        $rsallitem_qtyplot = [];
        foreach ($rsitemchanges as $r) {
            $itemtosend = $r['ITMGR'] == '' ? trim($r['SERRC_BOMPN']) : $r['ITMGR'];
            $i = array_search($itemtosend, $rsallitem_cd);
            if ($i !== false) {
                $rsallitem_qty[$i] += $r['BOMPNQT'];
            } else {
                $rsallitem_cd[] = $itemtosend;
                $rsallitem_hscd[] = '';
                $rsallitem_qty[] = $r['BOMPNQT'];
                $rsallitem_qtyplot[] = 0;
            }
        }
        $count_rsallitem = count($rsallitem_cd);
        if ($count_rsallitem) {
            $rshscd = $this->MSTITM_mod->select_forcustoms($rsallitem_cd);
            foreach ($rshscd as $b) {
                for ($i = 0; $i < $count_rsallitem; $i++) {
                    if ($b['MITM_ITMCD'] == $rsallitem_cd[$i]) {
                        $rsallitem_hscd[$i] = $b['MITM_HSCD'];
                        break;
                    }
                }
            }
        }

        if ($rswhSI === 'AFWH3RT') {
            foreach ($tpb_barang_temp as $n) {
                $cz_h_CIF_FG += $n['CIF'];
                $cz_h_NETTO += $n['NETTO'];
                foreach ($rspickingres as $p) {
                    if ($n['KODE_BARANG'] == $p['SI_ITMCD']) {
                        $tpb_bahan_baku[] = [
                            'KODE_JENIS_DOK_ASAL' => $p['RCV_BCTYPE'], 'NOMOR_DAFTAR_DOK_ASAL' => $p['RCV_BCNO'], 'TANGGAL_DAFTAR_DOK_ASAL' => $p['RCV_RPDATE'], 'KODE_KANTOR' => $p['RCV_KPPBC'], 'NOMOR_AJU_DOK_ASAL' => strlen($p['RCV_RPNO']) == 6 ? substr('000000000000000000000000', 0, 26) : $p['RCV_RPNO'], 'SERI_BARANG_DOK_ASAL' => empty($p['RCV_ZNOURUT']) ? 0 : $p['RCV_ZNOURUT'], 'SPESIFIKASI_LAIN' => null, 'CIF' => ($n['PERPRICE'] * $p['INTQTY']), 'HARGA_PENYERAHAN' => 0, 'KODE_BARANG' => $p['OLDITEM'], 'KODE_STATUS' => "03", 'POS_TARIF' => $p['RCV_HSCD'], 'URAIAN' => $p['MITM_ITMD1'], 'TIPE' => $p['MITM_SPTNO'], 'JUMLAH_SATUAN' => $p['PERBOX'] * $p['BOX'], 'JENIS_SATUAN' => ($p['MITM_STKUOM'] == 'PCS') ? 'PCE' : $p['MITM_STKUOM'], 'KODE_ASAL_BAHAN_BAKU' => ($p['RCV_BCTYPE'] == '27' || $p['RCV_BCTYPE'] == '23') ? '0' : '1', 'RASSYCODE' => $n['KODE_BARANG'], 'RPRICEGROUP' => $n['CIF'], 'RBM' => substr($p['RCV_BM'], 0, 1) == '.' ? ('0' . $p['RCV_BM']) : ($p['RCV_BM']),
                        ];
                    }
                }
            }
        } elseif ($rswhSI === 'NFWH4RT') {
            foreach ($tpb_barang_temp as $n) {
                $cz_h_CIF_FG += $n['CIF'];
                $cz_h_NETTO += $n['NETTO'];
                foreach ($rspickingres as $p) {
                    if ($n['KODE_BARANG'] == $p['SI_ITMCD'] && $n['PERPRICE'] == $p['RCV_PRPRC']) {
                        $tpb_bahan_baku[] = [
                            'KODE_JENIS_DOK_ASAL' => $p['RCV_BCTYPE'], 'NOMOR_DAFTAR_DOK_ASAL' => $p['RCV_BCNO'], 'TANGGAL_DAFTAR_DOK_ASAL' => $p['RCV_RPDATE'], 'KODE_KANTOR' => $p['RCV_KPPBC'], 'NOMOR_AJU_DOK_ASAL' => strlen($p['RCV_RPNO']) == 6 ? substr('000000000000000000000000', 0, 26) : $p['RCV_RPNO'], 'SERI_BARANG_DOK_ASAL' => empty($p['RCV_ZNOURUT']) ? 0 : $p['RCV_ZNOURUT'], 'SPESIFIKASI_LAIN' => null, 'CIF' => substr($p['RCV_PRPRC'], 0, 1) == '.' ? ('0' . $p['RCV_PRPRC'] * $p['INTQTY']) : ($p['RCV_PRPRC'] * $p['INTQTY']), 'HARGA_PENYERAHAN' => 0, 'KODE_BARANG' => $p['OLDITEM'], 'KODE_STATUS' => "03", 'POS_TARIF' => $p['RCV_HSCD'], 'URAIAN' => $p['MITM_ITMD1'], 'TIPE' => $p['MITM_SPTNO'], 'JUMLAH_SATUAN' => $p['PERBOX'] * $p['BOX'], 'JENIS_SATUAN' => ($p['MITM_STKUOM'] == 'PCS') ? 'PCE' : $p['MITM_STKUOM'], 'KODE_ASAL_BAHAN_BAKU' => ($p['RCV_BCTYPE'] == '27' || $p['RCV_BCTYPE'] == '23') ? '0' : '1', 'RASSYCODE' => $n['KODE_BARANG'], 'RPRICEGROUP' => $n['CIF'], 'RBM' => substr($p['RCV_BM'], 0, 1) == '.' ? ('0' . $p['RCV_BM']) : ($p['RCV_BM']),
                        ];
                    }
                }
            }
        }

        foreach ($tpb_barang_temp as $r) {
            $nomor = 1;
            foreach ($tpb_bahan_baku as &$n) {
                if (
                    $r['KODE_BARANG'] == $n['RASSYCODE']
                    && $r['CIF'] == $n['RPRICEGROUP']
                ) {
                    $n['SERI_BAHAN_BAKU'] = $nomor;
                    $nomor++;
                }
            }
            unset($n);
        }

        #BAHAN BAKU DOKUMEN
        $tpb_bahan_baku_tarif = [];
        foreach ($tpb_bahan_baku as $r) {
            $tpb_bahan_baku_tarif[] = [
                'JENIS_TARIF' => 'BM', 'KODE_TARIF' => 1, 'NILAI_BAYAR' => 0, 'NILAI_FASILITAS' => 0, 'KODE_FASILITAS' => 2, 'TARIF_FASILITAS' => 100, 'TARIF' => $r['RBM'], 'SERI_BAHAN_BAKU' => $r['SERI_BAHAN_BAKU'], 'RASSYCODE' => $r['RASSYCODE'], 'RPRICEGROUP' => $r['RPRICEGROUP'], 'RITEMCD' => $r['KODE_BARANG'],
            ];
            $tpb_bahan_baku_tarif[] = [
                'JENIS_TARIF' => 'PPN', 'KODE_TARIF' => 1, 'NILAI_BAYAR' => 0, 'NILAI_FASILITAS' => 0, 'KODE_FASILITAS' => 2, 'TARIF_FASILITAS' => 100, 'TARIF' => 10, 'SERI_BAHAN_BAKU' => $r['SERI_BAHAN_BAKU'], 'RASSYCODE' => $r['RASSYCODE'], 'RPRICEGROUP' => $r['RPRICEGROUP'], 'RITEMCD' => $r['KODE_BARANG'],
            ];
            $tpb_bahan_baku_tarif[] = [
                'JENIS_TARIF' => 'PPH', 'KODE_TARIF' => 1, 'NILAI_BAYAR' => 0, 'NILAI_FASILITAS' => 0, 'KODE_FASILITAS' => 2, 'TARIF_FASILITAS' => 100, 'TARIF' => 2.5, 'SERI_BAHAN_BAKU' => $r['SERI_BAHAN_BAKU'], 'RASSYCODE' => $r['RASSYCODE'], 'RPRICEGROUP' => $r['RPRICEGROUP'], 'RITEMCD' => $r['KODE_BARANG'],
            ];
        }
        #N

        $tpb_header = [
            "NOMOR_AJU" => $cnoaju, "KODE_KANTOR" => $czkantorasal, "KODE_KANTOR_TUJUAN" => $czkantortujuan,
            "KODE_JENIS_TPB" => $cz_KODE_JENIS_TPB, "KODE_TUJUAN_TPB" => $cz_KODE_TUJUAN_TPB, "KODE_TUJUAN_PENGIRIMAN" => $cztujuanpengiriman,

            "KODE_ID_PENGUSAHA" => "1", "ID_PENGUSAHA" => $czidpengusaha, "NAMA_PENGUSAHA" => $cznmpengusaha,
            "ALAMAT_PENGUSAHA" => $czalamatpengusaha, "NOMOR_IJIN_TPB" => $czizinpengusaha,

            "KODE_ID_PENERIMA_BARANG" => "1", "ID_PENERIMA_BARANG" => $czidpenerima,
            "NAMA_PENERIMA_BARANG" => $cznmpenerima, "ALAMAT_PENERIMA_BARANG" => $czalamatpenerima,
            "NOMOR_IJIN_TPB_PENERIMA" => $czizinpenerima,

            "KODE_VALUTA" => $czcurrency, "CIF" => round($cz_h_CIF_FG, 2), "HARGA_PENYERAHAN" => $cz_h_HARGA_PENYERAHAN_FG,

            "NAMA_PENGANGKUT" => $cznamapengangkut, "NOMOR_POLISI" => $cznomorpolisi,

            "BRUTO" => $cz_h_BRUTO, "NETTO" => $cz_h_NETTO, "JUMLAH_BARANG" => $cz_h_JUMLAH_BARANG,

            "KOTA_TTD" => "CIKARANG", "TANGGAL_TTD" => $ccustdate,

            "KODE_DOKUMEN_PABEAN" => $czdocbctype, "ID_MODUL" => $czidmodul_asli, "VERSI_MODUL" => null,

            "VOLUME" => 0, "KODE_STATUS" => '00',
        ];

        $tpb_kemasan = [];
        $tpb_kemasan[] = ["JUMLAH_KEMASAN" => $cz_JUMLAH_KEMASAN, "KODE_JENIS_KEMASAN" => "BX"];

        $tpb_dokumen = [];
        $tpb_dokumen[] = ["KODE_JENIS_DOKUMEN" => "380", "NOMOR_DOKUMEN" => $czinvoice, "TANGGAL_DOKUMEN" => $czinvoicedt, "TIPE_DOKUMEN" => "02", "SERI_DOKUMEN" => 1]; //invoice
        $tpb_dokumen[] = ["KODE_JENIS_DOKUMEN" => "217", "NOMOR_DOKUMEN" => $czinvoice, "TANGGAL_DOKUMEN" => $czinvoicedt, "TIPE_DOKUMEN" => "02", "SERI_DOKUMEN" => 2]; //packing list
        $tpb_dokumen[] = ["KODE_JENIS_DOKUMEN" => "640", "NOMOR_DOKUMEN" => $czsj, "TANGGAL_DOKUMEN" => $ccustdate, "TIPE_DOKUMEN" => "02", "SERI_DOKUMEN" => 3]; //surat jalan
        #INSERT CEISA
        ##1 TPB HEADER
        $ZR_TPB_HEADER = $this->TPB_HEADER_imod->insert($tpb_header);
        ##N

        ##2 TPB DOKUMEN
        foreach ($tpb_dokumen as &$n) {
            $n['ID_HEADER'] = $ZR_TPB_HEADER;
        }
        unset($n);
        $ZR_TPB_DOKUMEN = $this->TPB_DOKUMEN_imod->insertb($tpb_dokumen);
        ##N

        ##3 TPB KEMASAN
        foreach ($tpb_kemasan as &$n) {
            $n['ID_HEADER'] = $ZR_TPB_HEADER;
        }
        unset($n);
        $ZR_TPB_KEMASAN = $this->TPB_KEMASAN_imod->insertb($tpb_kemasan);
        ##N

        ##4 TPB BARANG
        foreach ($tpb_barang as &$n) {
            $n['ID_HEADER'] = $ZR_TPB_HEADER;
            foreach ($tpb_bahan_baku as $j) {
                if ($n['KODE_BARANG'] == $j['RASSYCODE'] && $n['CIF'] == $j['RPRICEGROUP']) {
                    if (!isset($n['JUMLAH_BAHAN_BAKU'])) {
                        $n['JUMLAH_BAHAN_BAKU'] = 1;
                    } else {
                        $n['JUMLAH_BAHAN_BAKU']++;
                    }
                }
            }
        }
        unset($n);
        ##N

        ##4 TPB BARANG & BAHAN BAKU
        foreach ($tpb_barang as $n) {
            $ZR_TPB_BARANG = $this->TPB_BARANG_imod->insert($n);
            foreach ($tpb_bahan_baku as $b) {
                if ($n['KODE_BARANG'] == $b['RASSYCODE'] && $n['CIF'] == $b['RPRICEGROUP']) {
                    $ZR_TPB_BAHAN_BAKU = $this->TPB_BAHAN_BAKU_imod
                        ->insert([
                            'KODE_JENIS_DOK_ASAL' => $b['KODE_JENIS_DOK_ASAL'], 'NOMOR_DAFTAR_DOK_ASAL' => $b['NOMOR_DAFTAR_DOK_ASAL'], 'TANGGAL_DAFTAR_DOK_ASAL' => $b['TANGGAL_DAFTAR_DOK_ASAL'], 'KODE_KANTOR' => $b['KODE_KANTOR'], 'NOMOR_AJU_DOK_ASAL' => $b['NOMOR_AJU_DOK_ASAL'], 'SERI_BARANG_DOK_ASAL' => $b['SERI_BARANG_DOK_ASAL'], 'SPESIFIKASI_LAIN' => $b['SPESIFIKASI_LAIN'], 'CIF' => ($b['CIF']), 'NDPBM' => $czharga_matauang, 'HARGA_PENYERAHAN' => ($b['CIF'] * $czharga_matauang), 'KODE_BARANG' => $b['KODE_BARANG'], 'KODE_STATUS' => $b['KODE_STATUS'], 'POS_TARIF' => $b['POS_TARIF'], 'URAIAN' => $b['URAIAN'], 'TIPE' => $b['TIPE'], 'JUMLAH_SATUAN' => $b['JUMLAH_SATUAN'], 'JENIS_SATUAN' => $b['JENIS_SATUAN'], 'KODE_ASAL_BAHAN_BAKU' => $b['KODE_ASAL_BAHAN_BAKU'], 'NDPBM' => 0, 'NETTO' => 0, 'SERI_BAHAN_BAKU' => $b['SERI_BAHAN_BAKU'], 'SERI_BARANG' => $n['SERI_BARANG'], 'ID_BARANG' => $ZR_TPB_BARANG, 'ID_HEADER' => $ZR_TPB_HEADER,
                        ]);

                    foreach ($tpb_bahan_baku_tarif as $t) {
                        if (
                            $b['RASSYCODE'] == $t['RASSYCODE'] && $b['RPRICEGROUP'] == $t['RPRICEGROUP']
                            && $b['KODE_BARANG'] == $t['RITEMCD'] && $b['SERI_BAHAN_BAKU'] == $t['SERI_BAHAN_BAKU']
                        ) {
                            $ZR_TPB_BAHAN_BAKU_TARIF = $this->TPB_BAHAN_BAKU_TARIF_imod
                                ->insert([
                                    'JENIS_TARIF' => $t['JENIS_TARIF'], 'KODE_TARIF' => $t['KODE_TARIF'], 'NILAI_BAYAR' => $t['NILAI_BAYAR'], 'NILAI_FASILITAS' => $t['NILAI_FASILITAS'], 'KODE_FASILITAS' => $t['KODE_FASILITAS'], 'TARIF_FASILITAS' => $t['TARIF_FASILITAS'], 'TARIF' => $t['TARIF'], 'SERI_BAHAN_BAKU' => $t['SERI_BAHAN_BAKU'], 'ID_BAHAN_BAKU' => $ZR_TPB_BAHAN_BAKU, 'ID_BARANG' => $ZR_TPB_BARANG, 'ID_HEADER' => $ZR_TPB_HEADER,
                                ]);
                        }
                    }
                }
            }
        }
        ##N
        $this->DELV_mod->updatebyVAR(['DLV_POST' => $this->session->userdata('nama'), 'DLV_POSTTM' => $currentDate], ['DLV_ID' => $csj]);
        $myar[] = [
            'cd' => '1', 'msg' => 'Done, check your TPB', 'rspickingres' => $rspickingres, 'tpb_barang' => $tpb_barang, 'tpb_bahan_baku' => $tpb_bahan_baku, 'rsitem_p_price' => $rsitem_p_price,
        ];
        die(json_encode(['status' => $myar]));
    }
    public function posting41rtn()
    {
        set_exception_handler([$this, 'exception_handler']);
        set_error_handler([$this, 'log_error']);
        register_shutdown_function([$this, 'fatal_handler']);

        header('Content-Type: application/json');
        ini_set('max_execution_time', '-1');
        $currentDate = date('Y-m-d H:i:s');
        $csj = $this->input->get('insj');
        $czsj = $csj;
        $rsaktivasi = $this->AKTIVASIAPLIKASI_imod->selectAll();
        $rs_head_dlv = $this->DELV_mod->select_header_bydo($csj);
        $cztujuanpengiriman = '-';
        $consignee = '-';
        $czinvoice = '';
        $ccustdate = '';
        $nomoraju = '';
        $czdocbctype = '-';
        $cbusiness_group = '';
        $ccustomer_do = '';
        $czcurrency = '';
        $cz_KODE_JENIS_TPB = '';
        $czkantortujuan = '';
        $cz_KODE_TUJUAN_TPB = '';
        $cznmpenerima = '';
        $czidpenerima = '';
        $czalamatpenerima = '';
        $czizinpenerima = '';
        $cznomorpolisi = '';
        $cznamapengangkut = '';
        $czinvoicedt = '';
        foreach ($rs_head_dlv as $r) {
            if ($r['DLV_BCDATE']) {
                $consignee = $r['DLV_CONSIGN'];
                $czdocbctype = $r['DLV_BCTYPE'];
                $ccustdate = $r['DLV_BCDATE'];
                $nomoraju = $r['DLV_NOAJU'];
                $ccustomer_do = $r['DLV_CUSTDO'];
                $czinvoice = trim($r['DLV_INVNO']);
                $cbusiness_group = $r['DLV_BSGRP'];
                $czcurrency = trim($r['MCUS_CURCD']);
                $cz_KODE_JENIS_TPB = $r['DLV_ZJENIS_TPB_ASAL'];
                $cz_KODE_TUJUAN_TPB = $r['DLV_ZJENIS_TPB_TUJUAN'];
                $cznmpenerima = $r['MDEL_ZNAMA'];
                $czalamatpenerima = $r['MDEL_ADDRCUSTOMS'];
                $czizinpenerima = $r['MDEL_ZSKEP'];
                $cznomorpolisi = $r['DLV_TRANS'];
                $cznamapengangkut = $r['MSTTRANS_TYPE'];
                $czinvoicedt = $r['DLV_INVDT'];
                $czidpenerima = str_replace([".", "-"], "", $r['MCUS_TAXREG']);
                $cztujuanpengiriman = $r['DLV_PURPOSE'];
                $czkantortujuan = $r['DLV_DESTOFFICE'];
                break;
            }
        }
        if ($cztujuanpengiriman == '-') {
            $myar[] = ['cd' => 0, 'msg' => 'please set TUJUAN PENGIRIMAN (' . $cztujuanpengiriman . ')'];
            die('{"status" : ' . json_encode($myar) . ',"data":' . json_encode($rs_head_dlv) . '}');
        }

        if ($cztujuanpengiriman == '5' || $cztujuanpengiriman == '3') {
        } else {
            $myar[] = ['cd' => 0, 'msg' => 'please set TUJUAN PENGIRIMAN correctly'];
            die('{"status" : ' . json_encode($myar) . ',"data":' . json_encode($rs_head_dlv) . '}');
        }

        if ($consignee == '-') {
            $myar[] = ['cd' => 0, 'msg' => 'please set consignment (' . $consignee . ')'];
            die('{"status" : ' . json_encode($myar) . '}');
        }

        if ($czinvoice == '') {
            $myar[] = ['cd' => 0, 'msg' => 'please add invoice number (' . $czinvoice . ') custdate (' . $ccustdate . ') consign (' . $consignee . ')'];
            die('{"status" : ' . json_encode($myar) . '}');
        }
        $ocustdate = date_create($ccustdate);
        $cz_ymd = date_format($ocustdate, 'Ymd');
        $czidpengusaha = '';
        $cznmpengusaha = '';
        $czalamatpengusaha = '';
        $czizinpengusaha = '';
        foreach ($rsaktivasi as $r) {
            $czkantorasal = $r['KPPBC'];
            $czidmodul = substr('00000' . $r['ID_MODUL'], -6);
            $czidmodul_asli = $r['ID_MODUL'];
            $czidpengusaha = $r['NPWP'];
            $cznmpengusaha = $r['NAMA_PENGUSAHA'];
            $czalamatpengusaha = $r['ALAMAT_PENGUSAHA'];
            $czizinpengusaha = $r['NOMOR_SKEP'];
        }

        if ($czdocbctype == '-') {
            $myar[] = ['cd' => 0, 'msg' => 'please select bc type!'];
            die('{"status" : ' . json_encode($myar) . '}');
        }

        if (strlen($nomoraju) != 6) {
            $myar[] = ['cd' => 0, 'msg' => 'NOMOR AJU is not valid, please re-check (' . $nomoraju . ')'];
            die('{"status" : ' . json_encode($myar) . '}');
        }

        $cnoaju = substr($czkantorasal, 0, 4) . $czdocbctype . $czidmodul . $cz_ymd . $nomoraju;

        if ($this->TPB_HEADER_imod->check_Primary(['NOMOR_AJU' => $cnoaju]) > 0) {
            $myar[] = ['cd' => 0, 'msg' => 'the NOMOR AJU is already posted'];
            die('{"status" : ' . json_encode($myar) . '}');
        }

        if (($cbusiness_group == 'PSI2PPZOMC' || $cbusiness_group == 'PSI2PPZOMI')) {
            if (strlen($ccustomer_do) < 5) {
                $myar[] = ['cd' => 0, 'msg' => 'please enter valid Customer DO!'];
                die('{"status" : ' . json_encode($myar) . '}');
            }
            $czsj = $ccustomer_do;
        }
        if (strlen($nomoraju) != 6) {
            $myar[] = ['cd' => 0, 'msg' => 'please enter valid Nomor Pengajuan!'];
            die('{"status" : ' . json_encode($myar) . '}');
        }

        if (strlen($ccustdate) != 10) {
            $myar[] = ['cd' => 0, 'msg' => 'please enter valid customs date!'];
            die('{"status" : ' . json_encode($myar) . '}');
        }

        if ($this->DELV_mod->check_Primary(['DLV_ID' => $csj]) == 0) {
            $myar[] = ['cd' => 0, 'msg' => 'DO is not found'];
            die('{"status" : ' . json_encode($myar) . '}');
        }

        $rscurr = $this->MEXRATE_mod->selectfor_posting($ccustdate, $czcurrency);
        if (count($rscurr) == 0) {
            $myar[] = ["cd" => "0", "msg" => "Please fill exchange rate data !"];
            die('{"status":' . json_encode($myar) . '}');
        } else {
            foreach ($rscurr as $r) {
                $czharga_matauang = $r->MEXRATE_VAL;
                break;
            }
        }

        $rswhSI = $this->SI_mod->select_wh_by_txid($csj);

        #HEADER_HARGA
        $cz_h_CIF_FG = 0;
        $cz_h_HARGA_PENYERAHAN_FG = 0;
        if ($rswhSI === '??') {
            $myar[] = ["cd" => "0", "msg" => "SI WH is not recognized"];
            die(json_encode(['status' => $myar]));
        }
        $rsitem_p_price = [];
        $rspackinglist = $this->DELV_mod->select_packinglist_bydono($csj);
        $rspickingres = $this->SISCN_mod->select_exbc_fgrtn($csj);
        $cz_h_NETTO = 0;
        $cz_h_BRUTO = 0;
        $SERI_BARANG = 1;
        $aspBOX = 0;
        $tpb_barang = [];
        $tpb_bahan_baku = [];
        $tpb_barang_temp = [];
        $cz_JUMLAH_KEMASAN = 0;
        if ($rswhSI === 'AFWH3RT') {
            $rsitem_p_price = $this->setPriceRS(base64_encode($csj));
            $cz_h_JUMLAH_BARANG = count($rsitem_p_price);
            #INIT PRICE
            $rsresume = [];
            $rsmultiprice = [];
            foreach ($rsitem_p_price as &$k) {
                if ($k['SISO_SOLINE'] == 'X') {
                    $rs_mst_price = $this->XSO_mod->select_latestprice($k['SI_BSGRP'], $k['SI_CUSCD'], "'" . $k['SSO2_MDLCD'] . "'");
                    foreach ($rs_mst_price as $r) {
                        $k['SSO2_SLPRC'] = substr($r['MSPR_SLPRC'], 0, 1) == '.' ? '0' . $r['MSPR_SLPRC'] : $r['MSPR_SLPRC'];
                        $k['CIF'] = $k['SSO2_SLPRC'] * $k['SISOQTY'];
                    }
                }
                $isfound = false;
                foreach ($rsresume as &$n) {
                    if ($n['RSI_ITMCD'] == $k['SSO2_MDLCD'] && $n['RSSO2_SLPRC'] != $k['SSO2_SLPRC']) {
                        $n['RCOUNT']++;
                        $isfound = true;
                        break;
                    }
                }
                unset($n);

                if (!$isfound) {
                    $rsresume[] = [
                        'RSI_ITMCD' => $k['SSO2_MDLCD'], 'RSSO2_SLPRC' => $k['SSO2_SLPRC'], 'RCOUNT' => 1,
                    ];
                }
            }
            unset($k);

            foreach ($rsresume as $k) {
                if ($k['RCOUNT'] > 1) {
                    $rsmultiprice[] = $k;
                }
            }

            if (count($rsmultiprice) > 0) {
                $myar[] = ["cd" => "0", "msg" => "Multi price detected please, click 'Price Detail' to confirm "];
                die('{"status":' . json_encode($myar) . ',"data":' . json_encode($rsitem_p_price) . ',"data2":' . json_encode($rsmultiprice) . '}');
            }

            foreach ($rsitem_p_price as $r) {
                $t_HARGA_PENYERAHAN = $r['CIF'] * $czharga_matauang;
                $cz_h_NETTO += $r['NWG'];
                $cz_h_HARGA_PENYERAHAN_FG += $t_HARGA_PENYERAHAN;
                $tpb_barang_temp[] = [
                    'KODE_BARANG' => $r['SSO2_MDLCD'], 'POS_TARIF' => $r['MITM_HSCD'], 'URAIAN' => $r['MITM_ITMD1'], 'JUMLAH_SATUAN' => $r['SISOQTY'], 'KODE_SATUAN' => $r['MITM_STKUOM'] == 'PCS' ? 'PCE' : $r['MITM_STKUOM'], 'NETTO' => 1, 'CIF' => round($r['CIF'], 2), 'HARGA_PENYERAHAN' => $t_HARGA_PENYERAHAN, 'SERI_BARANG' => $SERI_BARANG, 'KODE_STATUS' => '02', 'PERPRICE' => $r['SSO2_SLPRC'],
                ];
                $SERI_BARANG++;
            }
            foreach ($tpb_barang_temp as $r) {
                $tpb_barang[] = [
                    'KODE_BARANG' => $r['KODE_BARANG'], 'POS_TARIF' => $r['POS_TARIF'], 'URAIAN' => $r['URAIAN'], 'JUMLAH_SATUAN' => $r['JUMLAH_SATUAN'], 'KODE_SATUAN' => $r['KODE_SATUAN'], 'NETTO' => $r['NETTO'], 'CIF' => $r['CIF'], 'HARGA_PENYERAHAN' => $r['HARGA_PENYERAHAN'], 'SERI_BARANG' => $r['SERI_BARANG'], 'KODE_STATUS' => $r['KODE_STATUS'],
                ];
            }
        } elseif ($rswhSI === 'NFWH4RT') {
            foreach ($rspickingres as $r) {
                $isplot = false;
                $CIF = $r['RCV_PRPRC'] * $r['INTQTY'];
                $t_HARGA_PENYERAHAN = $CIF * $czharga_matauang;
                $cz_JUMLAH_KEMASAN += $r['BOX'];
                foreach ($tpb_barang_temp as &$p) {
                    if ($r['SI_ITMCD'] == $p['KODE_BARANG'] && $r['RCV_PRPRC'] == $p['PERPRICE']) {
                        $p['JUMLAH_SATUAN'] += $r['INTQTY'];
                        $p['CIF'] = $p['JUMLAH_SATUAN'] * $p['PERPRICE'];
                        $p['HARGA_PENYERAHAN'] = $p['CIF'] * $czharga_matauang;
                        $p['NETTO'] += $r['NWG'];
                        $isplot = true;
                        break;
                    }
                }
                unset($p);
                if (!$isplot) {
                    $tpb_barang_temp[] = [
                        'KODE_BARANG' => $r['SI_ITMCD'], 'POS_TARIF' => $r['RCV_HSCD'], 'URAIAN' => $r['MITM_ITMD1'], 'JUMLAH_SATUAN' => $r['INTQTY'], 'KODE_SATUAN' => $r['MITM_STKUOM'] == 'PCS' ? 'PCE' : $r['MITM_STKUOM'], 'NETTO' => $r['NWG'] * 1, 'CIF' => round($CIF, 2), 'HARGA_PENYERAHAN' => $t_HARGA_PENYERAHAN, 'SERI_BARANG' => $SERI_BARANG, 'KODE_STATUS' => '02', 'PERPRICE' => $r['RCV_PRPRC'],
                    ];
                    $SERI_BARANG++;
                }
            }
            foreach ($tpb_barang_temp as $r) {
                $cz_h_HARGA_PENYERAHAN_FG += $r['HARGA_PENYERAHAN'];
                $tpb_barang[] = [
                    'KODE_BARANG' => $r['KODE_BARANG'], 'POS_TARIF' => $r['POS_TARIF'], 'URAIAN' => $r['URAIAN'], 'JUMLAH_SATUAN' => $r['JUMLAH_SATUAN'], 'KODE_SATUAN' => $r['KODE_SATUAN'], 'NETTO' => $r['NETTO'], 'CIF' => $r['CIF'], 'HARGA_PENYERAHAN' => $r['HARGA_PENYERAHAN'], 'SERI_BARANG' => $r['SERI_BARANG'], 'KODE_STATUS' => $r['KODE_STATUS'],
                ];
            }
        } else {
            $myar[] = ["cd" => "0", "msg" => "SI WH should be AFWH3RT or NFWH4RT"];
            die(json_encode(['status' => $myar]));
        }
        foreach ($rspackinglist as $r) {
            $cz_h_BRUTO += $r['MITM_GWG'];
        }

        if ($aspBOX) {
            $cz_JUMLAH_KEMASAN = $aspBOX;
        }

        $cz_h_JUMLAH_BARANG = count($tpb_barang_temp);

        #START REQUEST EXBC_ITEM CHANGES
        $rsitemchanges = $this->DELV_mod->select_rm_rtn_bydoc($csj);
        $rsallitem_cd = [];
        $rsallitem_hscd = [];
        $rsallitem_qty = [];
        $rsallitem_qtyplot = [];
        foreach ($rsitemchanges as $r) {
            $itemtosend = $r['ITMGR'] == '' ? trim($r['SERRC_BOMPN']) : $r['ITMGR'];
            $i = array_search($itemtosend, $rsallitem_cd);
            if ($i !== false) {
                $rsallitem_qty[$i] += $r['BOMPNQT'];
            } else {
                $rsallitem_cd[] = $itemtosend;
                $rsallitem_hscd[] = '';
                $rsallitem_qty[] = $r['BOMPNQT'];
                $rsallitem_qtyplot[] = 0;
            }
        }
        $count_rsallitem = count($rsallitem_cd);
        if ($count_rsallitem) {
            $rshscd = $this->MSTITM_mod->select_forcustoms($rsallitem_cd);
            foreach ($rshscd as $b) {
                for ($i = 0; $i < $count_rsallitem; $i++) {
                    if ($b['MITM_ITMCD'] == $rsallitem_cd[$i]) {
                        $rsallitem_hscd[$i] = $b['MITM_HSCD'];
                        break;
                    }
                }
            }
        }

        if ($rswhSI === 'AFWH3RT') {
            foreach ($tpb_barang_temp as $n) {
                $cz_h_CIF_FG += $n['CIF'];
                $cz_h_NETTO += $n['NETTO'];
                foreach ($rspickingres as $p) {
                    if ($n['KODE_BARANG'] == $p['SI_ITMCD']) {
                        $tpb_bahan_baku[] = [
                            'KODE_JENIS_DOK_ASAL' => $p['RCV_BCTYPE'], 'NOMOR_DAFTAR_DOK_ASAL' => $p['RCV_BCNO'], 'TANGGAL_DAFTAR_DOK_ASAL' => $p['RCV_RPDATE'], 'KODE_KANTOR' => $p['RCV_KPPBC'], 'NOMOR_AJU_DOK_ASAL' => strlen($p['RCV_RPNO']) == 6 ? substr('000000000000000000000000', 0, 26) : $p['RCV_RPNO'], 'SERI_BARANG_DOK_ASAL' => empty($p['RCV_ZNOURUT']) ? 0 : $p['RCV_ZNOURUT'], 'SPESIFIKASI_LAIN' => null, 'CIF' => ($n['PERPRICE'] * $p['INTQTY']), 'HARGA_PENYERAHAN' => 0, 'KODE_BARANG' => $p['OLDITEM'], 'KODE_STATUS' => "03", 'POS_TARIF' => $p['RCV_HSCD'], 'URAIAN' => $p['MITM_ITMD1'], 'TIPE' => $p['MITM_SPTNO'], 'JUMLAH_SATUAN' => $p['PERBOX'] * $p['BOX'], 'JENIS_SATUAN' => ($p['MITM_STKUOM'] == 'PCS') ? 'PCE' : $p['MITM_STKUOM'], 'KODE_ASAL_BAHAN_BAKU' => ($p['RCV_BCTYPE'] == '27' || $p['RCV_BCTYPE'] == '23') ? '0' : '1', 'RASSYCODE' => $n['KODE_BARANG'], 'RPRICEGROUP' => $n['CIF'], 'RBM' => substr($p['RCV_BM'], 0, 1) == '.' ? ('0' . $p['RCV_BM']) : ($p['RCV_BM']),
                        ];
                    }
                }
            }
        } elseif ($rswhSI === 'NFWH4RT') {
            foreach ($tpb_barang_temp as $n) {
                $cz_h_CIF_FG += $n['CIF'];
                $cz_h_NETTO += $n['NETTO'];
                foreach ($rspickingres as $p) {
                    if ($n['KODE_BARANG'] == $p['SI_ITMCD'] && $n['PERPRICE'] == $p['RCV_PRPRC']) {
                        $tpb_bahan_baku[] = [
                            'KODE_JENIS_DOK_ASAL' => $p['RCV_BCTYPE'], 'NOMOR_DAFTAR_DOK_ASAL' => $p['RCV_BCNO'], 'TANGGAL_DAFTAR_DOK_ASAL' => $p['RCV_RPDATE'], 'KODE_KANTOR' => $p['RCV_KPPBC'], 'NOMOR_AJU_DOK_ASAL' => strlen($p['RCV_RPNO']) == 6 ? substr('000000000000000000000000', 0, 26) : $p['RCV_RPNO'], 'SERI_BARANG_DOK_ASAL' => empty($p['RCV_ZNOURUT']) ? 0 : $p['RCV_ZNOURUT'], 'SPESIFIKASI_LAIN' => null, 'CIF' => substr($p['RCV_PRPRC'], 0, 1) == '.' ? ('0' . $p['RCV_PRPRC'] * $p['INTQTY']) : ($p['RCV_PRPRC'] * $p['INTQTY']), 'HARGA_PENYERAHAN' => 0, 'KODE_BARANG' => $p['OLDITEM'], 'KODE_STATUS' => "03", 'POS_TARIF' => $p['RCV_HSCD'], 'URAIAN' => $p['MITM_ITMD1'], 'TIPE' => $p['MITM_SPTNO'], 'JUMLAH_SATUAN' => $p['PERBOX'] * $p['BOX'], 'JENIS_SATUAN' => ($p['MITM_STKUOM'] == 'PCS') ? 'PCE' : $p['MITM_STKUOM'], 'KODE_ASAL_BAHAN_BAKU' => ($p['RCV_BCTYPE'] == '27' || $p['RCV_BCTYPE'] == '23') ? '0' : '1', 'RASSYCODE' => $n['KODE_BARANG'], 'RPRICEGROUP' => $n['CIF'], 'RBM' => substr($p['RCV_BM'], 0, 1) == '.' ? ('0' . $p['RCV_BM']) : ($p['RCV_BM']),
                        ];
                    }
                }
            }
        }

        foreach ($tpb_barang_temp as $r) {
            $nomor = 1;
            foreach ($tpb_bahan_baku as &$n) {
                if (
                    $r['KODE_BARANG'] == $n['RASSYCODE']
                    && $r['CIF'] == $n['RPRICEGROUP']
                ) {
                    $n['SERI_BAHAN_BAKU'] = $nomor;
                    $nomor++;
                }
            }
            unset($n);
        }

        #BAHAN BAKU DOKUMEN
        $tpb_bahan_baku_tarif = [];
        foreach ($tpb_bahan_baku as $r) {
            $tpb_bahan_baku_tarif[] = [
                'JENIS_TARIF' => 'BM', 'KODE_TARIF' => 1, 'NILAI_BAYAR' => 0, 'NILAI_FASILITAS' => 0, 'KODE_FASILITAS' => 2, 'TARIF_FASILITAS' => 100, 'TARIF' => $r['RBM'], 'SERI_BAHAN_BAKU' => $r['SERI_BAHAN_BAKU'], 'RASSYCODE' => $r['RASSYCODE'], 'RPRICEGROUP' => $r['RPRICEGROUP'], 'RITEMCD' => $r['KODE_BARANG'],
            ];
            $tpb_bahan_baku_tarif[] = [
                'JENIS_TARIF' => 'PPN', 'KODE_TARIF' => 1, 'NILAI_BAYAR' => 0, 'NILAI_FASILITAS' => 0, 'KODE_FASILITAS' => 2, 'TARIF_FASILITAS' => 100, 'TARIF' => 10, 'SERI_BAHAN_BAKU' => $r['SERI_BAHAN_BAKU'], 'RASSYCODE' => $r['RASSYCODE'], 'RPRICEGROUP' => $r['RPRICEGROUP'], 'RITEMCD' => $r['KODE_BARANG'],
            ];
            $tpb_bahan_baku_tarif[] = [
                'JENIS_TARIF' => 'PPH', 'KODE_TARIF' => 1, 'NILAI_BAYAR' => 0, 'NILAI_FASILITAS' => 0, 'KODE_FASILITAS' => 2, 'TARIF_FASILITAS' => 100, 'TARIF' => 2.5, 'SERI_BAHAN_BAKU' => $r['SERI_BAHAN_BAKU'], 'RASSYCODE' => $r['RASSYCODE'], 'RPRICEGROUP' => $r['RPRICEGROUP'], 'RITEMCD' => $r['KODE_BARANG'],
            ];
        }
        #N

        $tpb_header = [
            "NOMOR_AJU" => $cnoaju, "KODE_KANTOR" => $czkantorasal, "KODE_KANTOR_TUJUAN" => $czkantortujuan,
            "KODE_JENIS_TPB" => $cz_KODE_JENIS_TPB, "KODE_TUJUAN_TPB" => $cz_KODE_TUJUAN_TPB, "KODE_TUJUAN_PENGIRIMAN" => $cztujuanpengiriman,

            "KODE_ID_PENGUSAHA" => "1", "ID_PENGUSAHA" => $czidpengusaha, "NAMA_PENGUSAHA" => $cznmpengusaha,
            "ALAMAT_PENGUSAHA" => $czalamatpengusaha, "NOMOR_IJIN_TPB" => $czizinpengusaha,

            "KODE_ID_PENERIMA_BARANG" => "1", "ID_PENERIMA_BARANG" => $czidpenerima,
            "NAMA_PENERIMA_BARANG" => $cznmpenerima, "ALAMAT_PENERIMA_BARANG" => $czalamatpenerima,
            "NOMOR_IJIN_TPB_PENERIMA" => $czizinpenerima,

            "KODE_VALUTA" => $czcurrency, "CIF" => round($cz_h_CIF_FG, 2), "HARGA_PENYERAHAN" => $cz_h_HARGA_PENYERAHAN_FG,

            "NAMA_PENGANGKUT" => $cznamapengangkut, "NOMOR_POLISI" => $cznomorpolisi,

            "BRUTO" => $cz_h_BRUTO, "NETTO" => $cz_h_NETTO, "JUMLAH_BARANG" => $cz_h_JUMLAH_BARANG,

            "KOTA_TTD" => "CIKARANG", "TANGGAL_TTD" => $ccustdate,

            "KODE_DOKUMEN_PABEAN" => $czdocbctype, "ID_MODUL" => $czidmodul_asli, "VERSI_MODUL" => null,

            "VOLUME" => 0, "KODE_STATUS" => '00',
        ];

        $tpb_kemasan = [];
        $tpb_kemasan[] = ["JUMLAH_KEMASAN" => $cz_JUMLAH_KEMASAN, "KODE_JENIS_KEMASAN" => "BX"];

        $tpb_dokumen = [];
        $tpb_dokumen[] = ["KODE_JENIS_DOKUMEN" => "380", "NOMOR_DOKUMEN" => $czinvoice, "TANGGAL_DOKUMEN" => $czinvoicedt, "TIPE_DOKUMEN" => "02", "SERI_DOKUMEN" => 1]; //invoice
        $tpb_dokumen[] = ["KODE_JENIS_DOKUMEN" => "217", "NOMOR_DOKUMEN" => $czinvoice, "TANGGAL_DOKUMEN" => $czinvoicedt, "TIPE_DOKUMEN" => "02", "SERI_DOKUMEN" => 2]; //packing list
        $tpb_dokumen[] = ["KODE_JENIS_DOKUMEN" => "640", "NOMOR_DOKUMEN" => $czsj, "TANGGAL_DOKUMEN" => $ccustdate, "TIPE_DOKUMEN" => "02", "SERI_DOKUMEN" => 3]; //surat jalan
        #INSERT CEISA
        ##1 TPB HEADER
        $ZR_TPB_HEADER = $this->TPB_HEADER_imod->insert($tpb_header);
        ##N

        ##2 TPB DOKUMEN
        foreach ($tpb_dokumen as &$n) {
            $n['ID_HEADER'] = $ZR_TPB_HEADER;
        }
        unset($n);
        $ZR_TPB_DOKUMEN = $this->TPB_DOKUMEN_imod->insertb($tpb_dokumen);
        ##N

        ##3 TPB KEMASAN
        foreach ($tpb_kemasan as &$n) {
            $n['ID_HEADER'] = $ZR_TPB_HEADER;
        }
        unset($n);
        $ZR_TPB_KEMASAN = $this->TPB_KEMASAN_imod->insertb($tpb_kemasan);
        ##N

        ##4 TPB BARANG
        foreach ($tpb_barang as &$n) {
            $n['ID_HEADER'] = $ZR_TPB_HEADER;
            foreach ($tpb_bahan_baku as $j) {
                if ($n['KODE_BARANG'] == $j['RASSYCODE'] && $n['CIF'] == $j['RPRICEGROUP']) {
                    if (!isset($n['JUMLAH_BAHAN_BAKU'])) {
                        $n['JUMLAH_BAHAN_BAKU'] = 1;
                    } else {
                        $n['JUMLAH_BAHAN_BAKU']++;
                    }
                }
            }
        }
        unset($n);
        ##N

        ##4 TPB BARANG & BAHAN BAKU
        foreach ($tpb_barang as $n) {
            $ZR_TPB_BARANG = $this->TPB_BARANG_imod->insert($n);
            foreach ($tpb_bahan_baku as $b) {
                if ($n['KODE_BARANG'] == $b['RASSYCODE'] && $n['CIF'] == $b['RPRICEGROUP']) {
                    $ZR_TPB_BAHAN_BAKU = $this->TPB_BAHAN_BAKU_imod
                        ->insert([
                            'KODE_JENIS_DOK_ASAL' => $b['KODE_JENIS_DOK_ASAL'], 'NOMOR_DAFTAR_DOK_ASAL' => $b['NOMOR_DAFTAR_DOK_ASAL'], 'TANGGAL_DAFTAR_DOK_ASAL' => $b['TANGGAL_DAFTAR_DOK_ASAL'], 'KODE_KANTOR' => $b['KODE_KANTOR'], 'NOMOR_AJU_DOK_ASAL' => $b['NOMOR_AJU_DOK_ASAL'], 'SERI_BARANG_DOK_ASAL' => $b['SERI_BARANG_DOK_ASAL'], 'SPESIFIKASI_LAIN' => $b['SPESIFIKASI_LAIN'], 'CIF' => ($b['CIF']), 'NDPBM' => $czharga_matauang, 'HARGA_PENYERAHAN' => ($b['CIF'] * $czharga_matauang), 'KODE_BARANG' => $b['KODE_BARANG'], 'KODE_STATUS' => $b['KODE_STATUS'], 'POS_TARIF' => $b['POS_TARIF'], 'URAIAN' => $b['URAIAN'], 'TIPE' => $b['TIPE'], 'JUMLAH_SATUAN' => $b['JUMLAH_SATUAN'], 'JENIS_SATUAN' => $b['JENIS_SATUAN'], 'KODE_ASAL_BAHAN_BAKU' => $b['KODE_ASAL_BAHAN_BAKU'], 'NDPBM' => 0, 'NETTO' => 0, 'SERI_BAHAN_BAKU' => $b['SERI_BAHAN_BAKU'], 'SERI_BARANG' => $n['SERI_BARANG'], 'ID_BARANG' => $ZR_TPB_BARANG, 'ID_HEADER' => $ZR_TPB_HEADER,
                        ]);

                    foreach ($tpb_bahan_baku_tarif as $t) {
                        if (
                            $b['RASSYCODE'] == $t['RASSYCODE'] && $b['RPRICEGROUP'] == $t['RPRICEGROUP']
                            && $b['KODE_BARANG'] == $t['RITEMCD'] && $b['SERI_BAHAN_BAKU'] == $t['SERI_BAHAN_BAKU']
                        ) {
                            $this->TPB_BAHAN_BAKU_TARIF_imod
                                ->insert([
                                    'JENIS_TARIF' => $t['JENIS_TARIF'], 'KODE_TARIF' => $t['KODE_TARIF'], 'NILAI_BAYAR' => $t['NILAI_BAYAR'], 'NILAI_FASILITAS' => $t['NILAI_FASILITAS'], 'KODE_FASILITAS' => $t['KODE_FASILITAS'], 'TARIF_FASILITAS' => $t['TARIF_FASILITAS'], 'TARIF' => $t['TARIF'], 'SERI_BAHAN_BAKU' => $t['SERI_BAHAN_BAKU'], 'ID_BAHAN_BAKU' => $ZR_TPB_BAHAN_BAKU, 'ID_BARANG' => $ZR_TPB_BARANG, 'ID_HEADER' => $ZR_TPB_HEADER,
                                ]);
                        }
                    }
                }
            }
        }
        ##N
        $this->DELV_mod->updatebyVAR(['DLV_POST' => $this->session->userdata('nama'), 'DLV_POSTTM' => $currentDate], ['DLV_ID' => $csj]);
        $myar[] = [
            'cd' => '1', 'msg' => 'Done, check your TPB', 'rspickingres' => $rspickingres, 'tpb_barang' => $tpb_barang, 'tpb_bahan_baku' => $tpb_bahan_baku, 'rsitem_p_price' => $rsitem_p_price,
        ];
        die(json_encode(['status' => $myar]));
    }

    public function posting27()
    {
        set_exception_handler([$this, 'exception_handler']);
        set_error_handler([$this, 'log_error']);
        register_shutdown_function([$this, 'fatal_handler']);

        header('Content-Type: application/json');
        ini_set('max_execution_time', '-1');
        $currentDate = date('Y-m-d H:i:s');
        $csj = $this->input->get('insj');
        $now = DateTime::createFromFormat('U.u', microtime(true))->setTimezone(new DateTimeZone(date('T')));
        $startTM = $now->format('Y-m-d H:i:s:v');
        $rsUnfinished = $this->POSTING_mod->select_unfinished($csj);
        if (empty($rsUnfinished)) {
            $this->POSTING_mod->insert([
                'POSTING_DOC' => $csj,
                'POSTING_STATUS' => 1,
                'POSTING_STARTED_AT' => $startTM,
                'POSTING_BY' => $this->session->userdata('nama'),
                'POSTING_IP' => $_SERVER['REMOTE_ADDR'],
            ]);
        } else {
            $myar[] = ['cd' => '130', 'msg' => 'there is another user posting this document'];
        }
        $czsj = $csj;
        $rs_head_dlv = $this->DELV_mod->select_header_bydo($csj);
        $rs_rm_null = $this->DELV_mod->select_rm_null($csj);
        if (count($rs_rm_null) > 0) {
            #check com job
            $ser_com_calcualted = [];
            foreach ($rs_rm_null as $r) {
                $rs_def = $this->SERC_mod->select_cols_where_id(['SERC_COMID', 'SERC_COMJOB', 'SERC_COMQTY'], $r['DLV_SER']); #detail combined ser
                if (count($rs_def)) {
                    foreach ($rs_def as $k) {
                        $countrm_ = $this->SERD_mod->select_perlabel_resume_item($k['SERC_COMID']); #detail combined ser -> rm count
                        if ($countrm_ == 0) {
                            $ser_com_calcualted[] = ['NEWID' => $r['DLV_SER'], 'COMID' => $k['SERC_COMID'], 'RM' => $countrm_];
                        }
                    }
                } else {
                    $rs_def = $this->SERC_mod->select_cols_where_id(['SERC_COMID', 'SERC_COMJOB', 'SERC_COMQTY'], $r['SER_REFNO']); #detail combined ser
                    foreach ($rs_def as $k) {
                        $countrm_ = $this->SERD_mod->select_perlabel_resume_item($k['SERC_COMID']); #detail combined ser -> rm count
                        if ($countrm_ == 0) {
                            $ser_com_calcualted[] = ['NEWID' => $r['DLV_SER'], 'COMID' => $k['SERC_COMID'], 'RM' => $countrm_];
                        }
                    }
                }
            }

            #filter rs_rm_null
            $rs_filtered = [];
            foreach ($rs_rm_null as $r) {
                foreach ($ser_com_calcualted as $n) {
                    if ($r['DLV_SER'] == $n['NEWID']) {
                        $isfound = false;
                        foreach ($rs_filtered as $n) {
                            if ($n['DLV_SER'] == $r['DLV_SER']) {
                                $isfound = true;
                                break;
                            }
                        }
                        if (!$isfound) {
                            $rs_filtered[] = $r;
                        }
                    }
                }
            }
            if (count($rs_filtered) > 0) {
                $myar[] = ['cd' => 100, 'msg' => 'RM is null, please check again data in the table below'];
                die('{"status" : ' . json_encode($myar) . ', "data": ' . json_encode($rs_filtered) . '}');
            } else {
                #go ahead
            }
        }

        $rsaktivasi = $this->AKTIVASIAPLIKASI_imod->selectAll();
        $ccustdate = '';
        $nomoraju = '';
        $ccustomer_do = '';
        $cbusiness_group = '';
        $czcurrency = '';
        $czdocbctype = '-';
        $cz_KODE_JENIS_TPB = '';
        $cz_KODE_TUJUAN_TPB = '';
        $czinvoice = '';
        $czkantorasal = $czidmodul = $czidmodul_asli = '';
        $consignee = '-';
        $cznmpenerima = '';
        $czalamatpenerima = '';
        $czizinpenerima = '';
        $cznomorpolisi = '-';
        $cznamapengangkut = '';
        $czinvoicedt = '';
        $czidpenerima = '';
        $cztujuanpengiriman = '-';
        $czkantortujuan = '';
        $czConaNo = '';
        $czConaDate = '';
        $czPemberitahu = '';
        $czJabatan = '';
        foreach ($rs_head_dlv as $r) {
            if ($r['DLV_BCDATE']) {
                $consignee = $r['DLV_CONSIGN'];
                $czdocbctype = $r['DLV_BCTYPE'];
                $ccustdate = $r['DLV_BCDATE'];
                $nomoraju = $r['DLV_NOAJU'];
                $ccustomer_do = $r['DLV_CUSTDO'];
                $czinvoice = trim($r['DLV_INVNO']);
                $cbusiness_group = $r['DLV_BSGRP'];
                $czcurrency = trim($r['MCUS_CURCD']);
                $cz_KODE_JENIS_TPB = $r['DLV_ZJENIS_TPB_ASAL'];
                $cz_KODE_TUJUAN_TPB = $r['DLV_ZJENIS_TPB_TUJUAN'];
                $cznmpenerima = $r['MDEL_ZNAMA'];
                $czalamatpenerima = $r['MDEL_ADDRCUSTOMS'];
                $czizinpenerima = $r['MDEL_ZSKEP'];
                $cznomorpolisi = $r['DLV_TRANS'];
                $cznamapengangkut = $r['MSTTRANS_TYPE'];
                $czinvoicedt = $r['DLV_INVDT'];
                $czidpenerima = str_replace([".", "-"], "", $r['MCUS_TAXREG']);
                $cztujuanpengiriman = $r['DLV_PURPOSE'];
                $czkantortujuan = $r['DLV_DESTOFFICE'];
                $czConaNo = $r['DLV_CONA'];
                $czConaDate = $r['MCONA_DATE'];
                $czPemberitahu = $r['DLVH_PEMBERITAHU'];
                $czJabatan = $r['DLVH_JABATAN'];
                break;
            }
        }

        if ($cztujuanpengiriman == '-') {
            $myar[] = ['cd' => 0, 'msg' => 'please set TUJUAN PENGIRIMAN (' . $cztujuanpengiriman . ')'];
            die('{"status" : ' . json_encode($myar) . '}');
        }

        if ($consignee == '-') {
            $myar[] = ['cd' => 0, 'msg' => 'please set consignment (' . $consignee . ')'];
            die('{"status" : ' . json_encode($myar) . '}');
        }

        if ($czinvoice == '') {
            $myar[] = ['cd' => 0, 'msg' => 'please add invoice number (' . $czinvoice . ') custdate (' . $ccustdate . ') consign (' . $consignee . ')'];
            die('{"status" : ' . json_encode($myar) . '}');
        }
        $ocustdate = date_create($ccustdate);
        $cz_ymd = date_format($ocustdate, 'Ymd');

        $czidpengusaha = '';
        $cznmpengusaha = '';
        $czalamatpengusaha = '';
        $czizinpengusaha = '';

        foreach ($rsaktivasi as $r) {
            $czkantorasal = $r['KPPBC'];
            $czidmodul = substr('00000' . $r['ID_MODUL'], -6);
            $czidmodul_asli = $r['ID_MODUL'];
            $czidpengusaha = $r['NPWP'];
            $cznmpengusaha = $r['NAMA_PENGUSAHA'];
            $czalamatpengusaha = $r['ALAMAT_PENGUSAHA'];
            $czizinpengusaha = $r['NOMOR_SKEP'];
        }

        if ($czdocbctype == '-') {
            $myar[] = ['cd' => 0, 'msg' => 'please select bc type!'];
            die('{"status" : ' . json_encode($myar) . '}');
        }

        if (strlen($nomoraju) != 6) {
            $myar[] = ['cd' => 0, 'msg' => 'NOMOR AJU is not valid, please re-check (' . $nomoraju . ')'];
            die('{"status" : ' . json_encode($myar) . '}');
        }

        $cnoaju = substr($czkantorasal, 0, 4) . $czdocbctype . $czidmodul . $cz_ymd . $nomoraju;
        if (($cbusiness_group == 'PSI2PPZOMC' || $cbusiness_group == 'PSI2PPZOMI')) {
            if (strlen($ccustomer_do) < 5) {
                $myar[] = ['cd' => 0, 'msg' => 'please enter valid Customer DO!'];
                die('{"status" : ' . json_encode($myar) . '}');
            }
            $czsj = $ccustomer_do;
        }
        if (strlen($nomoraju) != 6) {
            $myar[] = ['cd' => 0, 'msg' => 'please enter valid Nomor Pengajuan!'];
            die('{"status" : ' . json_encode($myar) . '}');
        }
        if (strlen($ccustdate) != 10) {
            $myar[] = ['cd' => 0, 'msg' => 'please enter valid customs date!'];
            die('{"status" : ' . json_encode($myar) . '}');
        }

        if ($this->DELV_mod->check_Primary(['DLV_ID' => $csj]) == 0) {
            $myar[] = ['cd' => 0, 'msg' => 'DO is not found'];
            die('{"status" : ' . json_encode($myar) . '}');
        }

        if ($this->TPB_HEADER_imod->check_Primary(['NOMOR_AJU' => $cnoaju])) {
            $myar[] = ['cd' => 0, 'msg' => 'Already posted'];
            die('{"status" : ' . json_encode($myar) . '}');
        }

        $rsdlv = $this->DELV_mod->select_det_byid_p($csj);
        $cz_JUMLAH_KEMASAN = count($rsdlv);

        $myar = [];

        $rscurr = $this->MEXRATE_mod->selectfor_posting($ccustdate, $czcurrency);
        if (count($rscurr) == 0) {
            $myar[] = ["cd" => "0", "msg" => "Please fill exchange rate data !"];
            die('{"status":' . json_encode($myar) . '}');
        } else {
            foreach ($rscurr as $r) {
                $czharga_matauang = $r->MEXRATE_VAL;
                break;
            }
        }

        #HEADER_HARGA
        $cz_h_CIF_FG = 0;
        $cz_h_HARGA_PENYERAHAN_FG = 0;
        $rsitem_p_price = $this->setPriceRS(base64_encode($csj));

        #INIT PRICE
        $rsresume = [];
        $rsmultiprice = [];
        foreach ($rsitem_p_price as &$k) {
            if ($k['SISO_SOLINE'] == 'X') {
                $rs_mst_price = $this->XSO_mod->select_latestprice($k['SI_BSGRP'], $k['SI_CUSCD'], "'" . $k['SSO2_MDLCD'] . "'");
                foreach ($rs_mst_price as $r) {
                    $k['SSO2_SLPRC'] = substr($r['MSPR_SLPRC'], 0, 1) == '.' ? '0' . $r['MSPR_SLPRC'] : $r['MSPR_SLPRC'];
                    $k['CIF'] = $k['SSO2_SLPRC'] * $k['SISOQTY'];
                }
            }
            $isfound = false;
            foreach ($rsresume as &$n) {
                if ($n['RSI_ITMCD'] == $k['SSO2_MDLCD'] && $n['RSSO2_SLPRC'] != $k['SSO2_SLPRC']) {
                    $n['RCOUNT']++;
                    $isfound = true;
                    break;
                }
            }
            unset($n);

            if (!$isfound) {
                $rsresume[] = [
                    'RSI_ITMCD' => $k['SSO2_MDLCD'], 'RSSO2_SLPRC' => $k['SSO2_SLPRC'], 'RCOUNT' => 1,
                ];
            }
        }
        unset($k);

        foreach ($rsresume as $k) {
            if ($k['RCOUNT'] > 1) {
                $rsmultiprice[] = $k;
            }
        }

        if (count($rsmultiprice) > 0) {
            if ($consignee === 'IEI') {
                $myar[] = ["cd" => "0", "msg" => "Multi price detected please, click 'Price Detail' to confirm "];
                die('{"status":' . json_encode($myar) . ',"data":' . json_encode($rsitem_p_price) . ',"data2":' . json_encode($rsmultiprice) . '}');
            }
        }
        #END INIT PRICE
        log_message('error', $_SERVER['REMOTE_ADDR'] . ', step0#, DO:' . $csj);
        log_message('error', $_SERVER['REMOTE_ADDR'] . ', step1#, start, group by assy code , price, item');
        $rspackinglist = $this->DELV_mod->select_packinglist_bydono($csj);
        $rsplotrm_per_fgprice = $this->perprice($csj, $rsitem_p_price);
        $cz_h_JUMLAH_BARANG = count($rsitem_p_price);
        $cz_h_NETTO = 0;
        $cz_h_BRUTO = 0;
        $tpb_barang = [];
        $SERI_BARANG = 1;
        $aspBOX = 0;

        foreach ($rspackinglist as $r) {
            $cz_h_BRUTO += $r['MITM_GWG'];
        }
        foreach ($rsitem_p_price as $r) {
            if ($r['MITM_HSCD'] == '') {
                $myar[] = ["cd" => "0", "msg" => "HSCODE FG is empty"];
                die('{"status":' . json_encode($myar) . ',"data":' . json_encode($rsitem_p_price) . '}');
            }
            $t_HARGA_PENYERAHAN = $r['CIF'] * $czharga_matauang;
            $cz_h_CIF_FG += $r['CIF'];
            $cz_h_NETTO += $r['NWG'];
            $cz_h_HARGA_PENYERAHAN_FG += $t_HARGA_PENYERAHAN;
            $tpb_barang[] = [
                'KODE_BARANG' => $r['SSO2_MDLCD']
                , 'POS_TARIF' => $r['MITM_HSCD']
                , 'URAIAN' => $r['MITM_ITMD1']
                , 'JUMLAH_SATUAN' => $r['SISOQTY']
                , 'KODE_SATUAN' => $r['MITM_STKUOM'] == 'PCS' ? 'PCE' : $r['MITM_STKUOM']
                , 'NETTO' => $r['NWG']
                , 'CIF' => round($r['CIF'], 2)
                , 'HARGA_PENYERAHAN' => $t_HARGA_PENYERAHAN
                , 'SERI_BARANG' => $SERI_BARANG
                , 'KODE_STATUS' => '02',
            ];
            $SERI_BARANG++;
            if (strpos(strtoupper($r['SSO2_MDLCD']), 'ASP') !== false) {
                $aspBOX += $r['SISOQTY'];
            }
        }
        if ($aspBOX) {
            $cz_JUMLAH_KEMASAN = $aspBOX;
        }
        #N
        log_message('error', $_SERVER['REMOTE_ADDR'] . ',step1#, finish, group by assy code , price, item');
        #BAHAN BAKU
        $tpb_bahan_baku = [];
        $responseResume = [];
        $rsaju_need_hscode = [];
        try {
            log_message('error', $_SERVER['REMOTE_ADDR'] . ',step2#, start, posting group by assy code , price, item');
            $rsRMOnly = $this->DELV_mod->select_pertxid_rmOnly($csj);
            $rsSubOnly = $this->DELV_mod->select_pertxid_subOnly($csj);
            $rsnull = $this->DELV_mod->select_dlv_ser_rm_null_v1($csj);
            $rs = array_merge($rsRMOnly, $rsSubOnly);
            foreach ($rsnull as $r) {
                $rscomb_d = $this->SERC_mod->select_cols_where_id(['SERC_COMID'], $r['DLV_SER']);
                $serlist = [];
                if (count($rscomb_d)) {
                    foreach ($rscomb_d as $n) {
                        $serlist[] = $n['SERC_COMID'];
                    }
                    if (count($serlist) > 0) {
                        $rscom = $this->DELV_mod->select_pertxid_byser($serlist);
                        $rs = array_merge($rs, $rscom);
                    }
                } else {
                    $rscomb_d = $this->SERC_mod->select_cols_where_id(['SERC_COMID'], $r['SER_REFNO']);
                    foreach ($rscomb_d as $n) {
                        $serlist[] = $n['SERC_COMID'];
                    }
                    if (count($serlist) > 0) {
                        $rscom = $this->DELV_mod->select_pertxid_byser($serlist);
                        $rs = array_merge($rs, $rscom);
                    }
                }
            }
            $rsallitem_cd = [];
            $rsallitem_hscd = [];
            $rsallitem_qty = [];
            $rsallitem_qtyplot = [];
            $rsallitem_ppn = [];
            $rsallitem_pph = [];
            $rsallitem_bm = [];
            foreach ($rs as $r) {
                $itemtosend = $r['ITMGR'] == '' ? trim($r['SERD2_ITMCD']) : $r['ITMGR'];
                $i = array_search($itemtosend, $rsallitem_cd);
                if ($i !== false) {
                    $rsallitem_qty[$i] += $r['DLVQT'];
                } else {
                    $rsallitem_cd[] = $itemtosend;
                    $rsallitem_hscd[] = '';
                    $rsallitem_qty[] = $r['DLVQT'];
                    $rsallitem_qtyplot[] = 0;
                    $rsallitem_ppn[] = '';
                    $rsallitem_pph[] = '';
                    $rsallitem_bm[] = '';
                }
            }
            $count_rsallitem = count($rsallitem_cd);
            if ($count_rsallitem) {
                $rshscd = $this->MSTITM_mod->select_forcustoms($rsallitem_cd);
                foreach ($rshscd as $b) {
                    for ($i = 0; $i < $count_rsallitem; $i++) {
                        if ($b['MITM_ITMCD'] == $rsallitem_cd[$i]) {
                            $rsallitem_hscd[$i] = $b['MITM_HSCD'];
                            $rsallitem_ppn[$i] = $b['MITM_PPN'];
                            $rsallitem_pph[$i] = $b['MITM_PPH'];
                            $rsallitem_bm[$i] = $b['MITM_BM'];
                            break;
                        }
                    }
                }
            }
            $isResponseIterable = false;

            log_message('error', $_SERVER['REMOTE_ADDR'] . ',step2#, finish, posting group by assy code , price, item');
            log_message('error', $_SERVER['REMOTE_ADDR'] . ',step3#, start, send request');
            $rstemp = $this->inventory_getstockbc_v2($czdocbctype, $cztujuanpengiriman, $csj, $rsallitem_cd, $rsallitem_qty, [], $ccustdate);
            $rsbc = json_decode($rstemp);
            log_message('error', $_SERVER['REMOTE_ADDR'] . ',step3#, start, receive request');
            if (!is_null($rsbc)) {
                if (count($rsbc) > 0) {
                    $isResponseIterable = true;
                    foreach ($rsbc as &$o) {
                        foreach ($o->data as &$v) {
                            #resume respone
                            $isfound = false;
                            foreach ($responseResume as &$n) {
                                if ($n['ITEM'] == $v->BC_ITEM) {
                                    $n['BALRES'] += $v->BC_QTY;
                                    $isfound = true;
                                }
                            }
                            unset($n);
                            if (!$isfound) {
                                $responseResume[] = ['ITEM' => $v->BC_ITEM, 'BALRES' => $v->BC_QTY];
                            }
                            #end
                        }
                        unset($v);
                    }
                    unset($o);
                } else {
                    $myar[] = ["cd" => "0", "msg" => "Could not find exbc, please contact admin !", "api_respon" => $rstemp];
                    $this->inventory_cancelDO($csj);
                    die('{"status":' . json_encode($myar) . '}');
                }
            } else {
                $this->inventory_cancelDO($csj);
                $myar[] = ["cd" => "0", "msg" => "Could not find exbc, please contact admin", "api_respon" => $rstemp];
                die('{"status":' . json_encode($myar) . '}');
            }
            #CHECK IS REQ!=RES
            $listNeedExBC = []; #outstanding list
            for ($i = 0; $i < $count_rsallitem; $i++) {
                foreach ($responseResume as &$r) {
                    if ($rsallitem_cd[$i] === $r['ITEM']) {
                        $bal = $rsallitem_qty[$i] - $rsallitem_qtyplot[$i];
                        if ($bal > $r['BALRES']) {
                            $rsallitem_qtyplot[$i] += $r['BALRES'];
                            $r['BALRES'] = 0;
                        } else {
                            $rsallitem_qtyplot[$i] += $bal;
                            $r['BALRES'] -= $bal;
                        }
                        if ($rsallitem_qty[$i] == $rsallitem_qtyplot[$i]) {
                            break;
                        }
                    }
                }
                unset($r);
                $bal = $rsallitem_qty[$i] - $rsallitem_qtyplot[$i];
                if ($bal) {
                    $listNeedExBC[] = ['ITMCD' => $rsallitem_cd[$i], 'QTY' => $bal, 'LOTNO' => '?'];
                }
            }
            if (count($listNeedExBC) > 0) {
                $this->inventory_cancelDO($csj);
                $myar[] = ['cd' => 110, 'msg' => 'EX-BC for ' . count($listNeedExBC) . ' item(s) is not found. ', "doctype" => $czdocbctype, "tujuankirim" => $cztujuanpengiriman];
                die('{"status" : ' . json_encode($myar) . ', "data":' . json_encode($listNeedExBC)
                    . ',"rawdata":' . json_encode($rstemp)
                    . ',"itemsend":' . json_encode($rsallitem_cd)
                    . ',"itemqtysend":' . json_encode($rsallitem_qty)
                    . ',"responresume":' . json_encode($responseResume) . '}');
            }

            #PLOT FROM RESPONSE TO REQUEST
            foreach ($rsplotrm_per_fgprice as &$r) {
                $r['PLOTRQTY'] = 0;
                if ($isResponseIterable) {
                    foreach ($rsbc as &$o) {
                        foreach ($o->data as &$v) {
                            if ($r['RITEMCDGR'] === $v->BC_ITEM || $r['RITEMCD'] === $v->BC_ITEM && $v->BC_QTY > 0) {
                                #_plot
                                $balreq = $r['RQTY'] - $r['PLOTRQTY'];
                                $theqty = 0;

                                if ($balreq == 0) {
                                    break;
                                }

                                if ($balreq > $v->BC_QTY) {
                                    $theqty = $v->BC_QTY;
                                    $r['PLOTRQTY'] += $v->BC_QTY;
                                    $v->BC_QTY = 0;
                                } else {
                                    $theqty = $balreq;
                                    $r['PLOTRQTY'] += $balreq;
                                    $v->BC_QTY -= $balreq;
                                }
                                $thehscode = '';
                                $theppn = '';
                                $thepph = '';
                                $thebm = '';

                                if (!empty($v->RCV_HSCD)) {
                                    $thehscode = $v->RCV_HSCD;
                                    $theppn = $v->RCV_PPN;
                                    $thepph = $v->RCV_PPH;
                                    $thebm = substr($v->RCV_BM, 0, 1) == '.' ? ('0' . $v->RCV_BM) : ($v->RCV_BM);
                                } else {
                                    for ($h = 0; $h < $count_rsallitem; $h++) {
                                        if ($v->BC_ITEM == $rsallitem_cd[$h]) {
                                            $thehscode = $rsallitem_hscd[$h];
                                            $theppn = $rsallitem_ppn[$h];
                                            $thepph = $rsallitem_pph[$h];
                                            $thebm = $rsallitem_bm[$h];
                                            break;
                                        }
                                    }
                                }

                                if ($v->RCV_KPPBC != '-') {
                                    $tpb_bahan_baku[] = [
                                        'KODE_JENIS_DOK_ASAL' => $v->BC_TYPE
                                        , 'NOMOR_DAFTAR_DOK_ASAL' => $v->BC_NUM
                                        , 'TANGGAL_DAFTAR_DOK_ASAL' => $v->BC_DATE
                                        , 'KODE_KANTOR' => $v->RCV_KPPBC
                                        , 'NOMOR_AJU_DOK_ASAL' => strlen($v->BC_AJU) == 6 ? substr('000000000000000000000000', 0, 26) : $v->BC_AJU
                                        , 'SERI_BARANG_DOK_ASAL' => empty($v->RCV_ZNOURUT) ? 0 : $v->RCV_ZNOURUT
                                        , 'SPESIFIKASI_LAIN' => null
                                        , 'CIF' => substr($v->RCV_PRPRC, 0, 1) == '.' ? ('0' . $v->RCV_PRPRC * $theqty) : ($v->RCV_PRPRC * $theqty)
                                        , 'HARGA_PENYERAHAN' => 0
                                        , 'KODE_BARANG' => $v->BC_ITEM
                                        , 'KODE_STATUS' => "03"
                                        , 'POS_TARIF' => $thehscode
                                        , 'URAIAN' => $v->MITM_ITMD1
                                        , 'TIPE' => $v->MITM_SPTNO
                                        , 'JUMLAH_SATUAN' => $theqty
                                        , 'JENIS_SATUAN' => ($v->MITM_STKUOM == 'PCS') ? 'PCE' : $v->MITM_STKUOM
                                        , 'KODE_ASAL_BAHAN_BAKU' => ($v->BC_TYPE == '27' || $v->BC_TYPE == '23') ? '0' : '1'
                                        , 'RASSYCODE' => $r['RASSYCODE']
                                        , 'RPRICEGROUP' => $r['RPRICEGROUP']
                                        , 'RBM' => $thebm, 'PPN' => 11//bu gusti, terkait peraturan 1 april
                                        , 'PPH' => $thepph,
                                    ];
                                } else {
                                    $tpb_bahan_baku[] = [
                                        'KODE_JENIS_DOK_ASAL' => $v->BC_TYPE, 'NOMOR_DAFTAR_DOK_ASAL' => $v->BC_NUM, 'TANGGAL_DAFTAR_DOK_ASAL' => $v->BC_DATE, 'KODE_KANTOR' => null, 'NOMOR_AJU_DOK_ASAL' => strlen($v->BC_AJU) == 6 ? substr('000000000000000000000000', 0, 26) : $v->BC_AJU, 'SERI_BARANG_DOK_ASAL' => empty($v->RCV_ZNOURUT) ? 0 : $v->RCV_ZNOURUT, 'SPESIFIKASI_LAIN' => null, 'CIF' => 0, 'HARGA_PENYERAHAN' => 0, 'KODE_BARANG' => trim($v->BC_ITEM), 'KODE_STATUS' => "02", 'POS_TARIF' => $thehscode, 'URAIAN' => $v->MITM_ITMD1, 'TIPE' => $v->MITM_SPTNO, 'JUMLAH_SATUAN' => $theqty, 'JENIS_SATUAN' => 'PCE', 'KODE_ASAL_BAHAN_BAKU' => 0, 'RASSYCODE' => $r['RASSYCODE'], 'RPRICEGROUP' => $r['RPRICEGROUP'], 'RBM' => 0, 'PPN' => $theppn //bu gusti, terkait peraturan 1 april
                                        , 'PPH' => $thepph,
                                    ];
                                }
                                #end
                            }
                        }
                        unset($v);
                    }
                    unset($o);
                }
            }
            unset($r);
            #END
            log_message('error', $_SERVER['REMOTE_ADDR'] . ',step3#, finish, receive request');
        } catch (Exception $e) {
            $this->inventory_cancelDO($csj);
            $myar[] = ['cd' => 110, 'msg' => $e->getMessage()];
            die('{"status" : ' . json_encode($myar) . ',"data":"' . $rstemp . '"}');
        }

        if ($rsaju_need_hscode) {
            $rsajudo = $this->RCV_mod->select_ajudo_byAJU_in($rsaju_need_hscode);
            $this->inventory_cancelDO($csj);
            $myar[] = ['cd' => 120, 'msg' => 'Need to update HSCODE'];
            die('{"status" : ' . json_encode($myar) . ', "data":' . json_encode($rsajudo) . '}');
        }

        foreach ($rsplotrm_per_fgprice as $r) {
            $nomor = 1;
            foreach ($tpb_bahan_baku as &$n) {
                if (
                    $r['RASSYCODE'] == $n['RASSYCODE']
                    && $r['RPRICEGROUP'] == $n['RPRICEGROUP']
                ) {
                    $n['SERI_BAHAN_BAKU'] = $nomor;
                    $nomor++;
                }
            }
            unset($n);
        }
        #N

        #BAHAN BAKU DOKUMEN
        $tpb_bahan_baku_tarif = [];
        foreach ($tpb_bahan_baku as $r) {
            $tpb_bahan_baku_tarif[] = [
                'JENIS_TARIF' => 'BM'
                , 'KODE_TARIF' => 1
                , 'NILAI_BAYAR' => 0
                , 'NILAI_FASILITAS' => 0
                , 'KODE_FASILITAS' => 2
                , 'TARIF_FASILITAS' => 100
                , 'TARIF' => $r['RBM']
                , 'SERI_BAHAN_BAKU' => $r['SERI_BAHAN_BAKU'], 'RASSYCODE' => $r['RASSYCODE'], 'RPRICEGROUP' => $r['RPRICEGROUP'], 'RITEMCD' => $r['KODE_BARANG'],
            ];
            $tpb_bahan_baku_tarif[] = [
                'JENIS_TARIF' => 'PPN', 'KODE_TARIF' => 1, 'NILAI_BAYAR' => 0, 'NILAI_FASILITAS' => 0, 'KODE_FASILITAS' => 2, 'TARIF_FASILITAS' => 100, 'TARIF' => $r['PPN'], 'SERI_BAHAN_BAKU' => $r['SERI_BAHAN_BAKU'], 'RASSYCODE' => $r['RASSYCODE'], 'RPRICEGROUP' => $r['RPRICEGROUP'], 'RITEMCD' => $r['KODE_BARANG'],
            ];
            $tpb_bahan_baku_tarif[] = [
                'JENIS_TARIF' => 'PPH', 'KODE_TARIF' => 1, 'NILAI_BAYAR' => 0, 'NILAI_FASILITAS' => 0, 'KODE_FASILITAS' => 2, 'TARIF_FASILITAS' => 100, 'TARIF' => $r['PPH'], 'SERI_BAHAN_BAKU' => $r['SERI_BAHAN_BAKU'], 'RASSYCODE' => $r['RASSYCODE'], 'RPRICEGROUP' => $r['RPRICEGROUP'], 'RITEMCD' => $r['KODE_BARANG'],
            ];
        }
        #N

        $tpb_header = [
            "NOMOR_AJU" => $cnoaju, "KODE_KANTOR" => $czkantorasal, "KODE_KANTOR_TUJUAN" => $czkantortujuan,
            "KODE_JENIS_TPB" => $cz_KODE_JENIS_TPB, "KODE_TUJUAN_TPB" => $cz_KODE_TUJUAN_TPB, "KODE_TUJUAN_PENGIRIMAN" => $cztujuanpengiriman,

            "KODE_ID_PENGUSAHA" => "1", "ID_PENGUSAHA" => $czidpengusaha, "NAMA_PENGUSAHA" => $cznmpengusaha,
            "ALAMAT_PENGUSAHA" => $czalamatpengusaha, "NOMOR_IJIN_TPB" => $czizinpengusaha,

            "KODE_ID_PENERIMA_BARANG" => "1", "ID_PENERIMA_BARANG" => $czidpenerima,
            "NAMA_PENERIMA_BARANG" => $cznmpenerima, "ALAMAT_PENERIMA_BARANG" => $czalamatpenerima,
            "NOMOR_IJIN_TPB_PENERIMA" => $czizinpenerima,

            "KODE_VALUTA" => $czcurrency, "CIF" => round($cz_h_CIF_FG, 2), "HARGA_PENYERAHAN" => $cz_h_HARGA_PENYERAHAN_FG,

            "NAMA_PENGANGKUT" => $cznamapengangkut, "NOMOR_POLISI" => $cznomorpolisi,

            "BRUTO" => $cz_h_BRUTO, "NETTO" => $cz_h_NETTO, "JUMLAH_BARANG" => $cz_h_JUMLAH_BARANG,

            "KOTA_TTD" => "CIKARANG", "TANGGAL_TTD" => $ccustdate, "NAMA_TTD" => $czPemberitahu, "JABATAN_TTD" => $czJabatan,

            "KODE_DOKUMEN_PABEAN" => $czdocbctype, "ID_MODUL" => $czidmodul_asli, "VERSI_MODUL" => null,

            "VOLUME" => 0, "KODE_STATUS" => '00',
        ];
        $tpb_kemasan = [];
        $tpb_kemasan[] = ["JUMLAH_KEMASAN" => $cz_JUMLAH_KEMASAN, "KODE_JENIS_KEMASAN" => "BX"];

        $tpb_dokumen = [];
        $tpb_dokumen[] = ["KODE_JENIS_DOKUMEN" => "380", "NOMOR_DOKUMEN" => $czinvoice, "TANGGAL_DOKUMEN" => $czinvoicedt, "TIPE_DOKUMEN" => "02", "SERI_DOKUMEN" => 1]; //invoice
        $tpb_dokumen[] = ["KODE_JENIS_DOKUMEN" => "217", "NOMOR_DOKUMEN" => $czinvoice, "TANGGAL_DOKUMEN" => $czinvoicedt, "TIPE_DOKUMEN" => "02", "SERI_DOKUMEN" => 2]; //packing list
        $tpb_dokumen[] = ["KODE_JENIS_DOKUMEN" => "640", "NOMOR_DOKUMEN" => $czsj, "TANGGAL_DOKUMEN" => $ccustdate, "TIPE_DOKUMEN" => "02", "SERI_DOKUMEN" => 3]; //surat jalan
        if (strlen($czConaNo) > 3) {
            $tpb_dokumen[] = ["KODE_JENIS_DOKUMEN" => "315", "NOMOR_DOKUMEN" => $czConaNo, "TANGGAL_DOKUMEN" => $czConaDate, "TIPE_DOKUMEN" => "02", "SERI_DOKUMEN" => 4]; //kontrak
        }

        log_message('error', $_SERVER['REMOTE_ADDR'] . ',step4#, start, INSERT TPB');
        #INSERT CEISA
        ##1 TPB HEADER
        $ZR_TPB_HEADER = $this->TPB_HEADER_imod->insert($tpb_header);
        ##N

        ##2 TPB DOKUMEN
        foreach ($tpb_dokumen as &$n) {
            $n['ID_HEADER'] = $ZR_TPB_HEADER;
        }
        unset($n);
        $ZR_TPB_DOKUMEN = $this->TPB_DOKUMEN_imod->insertb($tpb_dokumen);
        ##N

        ##3 TPB KEMASAN
        foreach ($tpb_kemasan as &$n) {
            $n['ID_HEADER'] = $ZR_TPB_HEADER;
        }
        unset($n);
        $ZR_TPB_KEMASAN = $this->TPB_KEMASAN_imod->insertb($tpb_kemasan);
        ##N

        ##4 TPB BARANG
        foreach ($tpb_barang as &$n) {
            $n['ID_HEADER'] = $ZR_TPB_HEADER;
            foreach ($tpb_bahan_baku as $j) {
                if ($n['KODE_BARANG'] == $j['RASSYCODE'] && $n['CIF'] == $j['RPRICEGROUP']) {
                    if (!isset($n['JUMLAH_BAHAN_BAKU'])) {
                        $n['JUMLAH_BAHAN_BAKU'] = 1;
                    } else {
                        $n['JUMLAH_BAHAN_BAKU']++;
                    }
                }
            }
        }
        unset($n);

        ##N

        ##4 TPB BARANG & BAHAN BAKU
        foreach ($tpb_barang as $n) {
            $ZR_TPB_BARANG = $this->TPB_BARANG_imod->insert($n);
            foreach ($tpb_bahan_baku as $b) {
                if ($n['KODE_BARANG'] == $b['RASSYCODE'] && $n['CIF'] == $b['RPRICEGROUP']) {
                    $ZR_TPB_BAHAN_BAKU = $this->TPB_BAHAN_BAKU_imod
                        ->insert([
                            'KODE_JENIS_DOK_ASAL' => $b['KODE_JENIS_DOK_ASAL']
                            , 'NOMOR_DAFTAR_DOK_ASAL' => $b['NOMOR_DAFTAR_DOK_ASAL']
                            , 'TANGGAL_DAFTAR_DOK_ASAL' => $b['TANGGAL_DAFTAR_DOK_ASAL']
                            , 'KODE_KANTOR' => $b['KODE_KANTOR']
                            , 'NOMOR_AJU_DOK_ASAL' => $b['NOMOR_AJU_DOK_ASAL']
                            , 'SERI_BARANG_DOK_ASAL' => $b['SERI_BARANG_DOK_ASAL']
                            , 'SPESIFIKASI_LAIN' => $b['SPESIFIKASI_LAIN']
                            , 'CIF' => ($b['CIF'])
                            , 'NDPBM' => $czharga_matauang
                            , 'HARGA_PENYERAHAN' => ($b['CIF'] * $czharga_matauang)
                            , 'KODE_BARANG' => $b['KODE_BARANG']
                            , 'KODE_STATUS' => $b['KODE_STATUS']
                            , 'POS_TARIF' => $b['POS_TARIF']
                            , 'URAIAN' => $b['URAIAN']
                            , 'TIPE' => $b['TIPE']
                            , 'JUMLAH_SATUAN' => $b['JUMLAH_SATUAN']
                            , 'JENIS_SATUAN' => $b['JENIS_SATUAN']
                            , 'KODE_ASAL_BAHAN_BAKU' => $b['KODE_ASAL_BAHAN_BAKU']
                            , 'NDPBM' => 0, 'NETTO' => 0
                            , 'SERI_BAHAN_BAKU' => $b['SERI_BAHAN_BAKU']
                            , 'SERI_BARANG' => $n['SERI_BARANG']
                            , 'ID_BARANG' => $ZR_TPB_BARANG
                            , 'ID_HEADER' => $ZR_TPB_HEADER,
                        ]);

                    foreach ($tpb_bahan_baku_tarif as $t) {
                        if (
                            $b['RASSYCODE'] == $t['RASSYCODE'] && $b['RPRICEGROUP'] == $t['RPRICEGROUP']
                            && $b['KODE_BARANG'] == $t['RITEMCD'] && $b['SERI_BAHAN_BAKU'] == $t['SERI_BAHAN_BAKU']
                        ) {
                            $this->TPB_BAHAN_BAKU_TARIF_imod
                                ->insert([
                                    'JENIS_TARIF' => $t['JENIS_TARIF']
                                    , 'KODE_TARIF' => $t['KODE_TARIF']
                                    , 'NILAI_BAYAR' => $t['NILAI_BAYAR']
                                    , 'NILAI_FASILITAS' => $t['NILAI_FASILITAS']
                                    , 'KODE_FASILITAS' => $t['KODE_FASILITAS']
                                    , 'TARIF_FASILITAS' => $t['TARIF_FASILITAS']
                                    , 'TARIF' => $t['TARIF']
                                    , 'SERI_BAHAN_BAKU' => $t['SERI_BAHAN_BAKU']
                                    , 'ID_BAHAN_BAKU' => $ZR_TPB_BAHAN_BAKU
                                    , 'ID_BARANG' => $ZR_TPB_BARANG
                                    , 'ID_HEADER' => $ZR_TPB_HEADER,
                                ]);
                        }
                    }
                }
            }
        }
        ##N

        #set finished time
        $now = DateTime::createFromFormat('U.u', microtime(true))->setTimezone(new DateTimeZone(date('T')));
        $finishTM = $now->format('Y-m-d H:i:s:v');
        $this->POSTING_mod->update_where(
            ['POSTING_FINISHED_AT' => $finishTM],
            [
                'POSTING_DOC' => $csj,
                'POSTING_IP' => $_SERVER['REMOTE_ADDR'],
                'POSTING_FINISHED_AT' => null,
            ]
        );
        #end

        log_message('error', $_SERVER['REMOTE_ADDR'] . ',step4#, finish, INSERT TPB ');
        $this->DELV_mod->updatebyVAR(['DLV_POST' => $this->session->userdata('nama'), 'DLV_POSTTM' => $currentDate], ['DLV_ID' => $csj]);
        $this->setPrice(base64_encode($csj));
        //delivery checking
        if ($consignee != 'IEI') {
            $this->sendto_delivery_checking($csj);
        }

        $myar[] = [
            'cd' => '1', 'msg' => 'Done, check your TPB', 'rsbc' => $rsbc, 'tpb_barang' => $tpb_barang, 'tpb_bahan_baku' => $tpb_bahan_baku, 'rsitem_p_price' => $rsitem_p_price,
        ];
        die('{"status" : ' . json_encode($myar) . '}');
    }

    public function sendto_delivery_checking($ptxid)
    {
        $rsbase = $this->DELV_mod->select_group(
            ['SER_ITMID', 'sum(SER_QTY) DLVQT'],
            ['SER_ITMID'],
            ['DLV_ID' => $ptxid]
        );

        $this->DLVCK_mod->deleteby_filter(['DLVCK_TXID' => $ptxid]);
        $i = 1;
        $tosave = [];
        foreach ($rsbase as $r) {
            $tosave[] = [
                'DLVCK_TXID' => $ptxid, 'DLVCK_CUSTDO' => $ptxid, 'DLVCK_ITMCD' => $r['SER_ITMID'], 'DLVCK_QTY' => (int) $r['DLVQT'], 'DLVCK_LINE' => $i,
            ];
            $i++;
        }
        $ret = '';
        if (count($tosave) > 0) {
            $ret = $this->DLVCK_mod->insertb($tosave);
        } else {
            $ret = 0;
        }
        return $ret;
    }

    public function setprice_manual()
    {
        $csj = $this->input->get('txid');
        $this->setPrice(base64_encode($csj));
        echo 'done' . $csj;
    }

    public function posting41()
    {
        set_exception_handler([$this, 'exception_handler']);
        set_error_handler([$this, 'log_error']);
        register_shutdown_function([$this, 'fatal_handler']);

        header('Content-Type: application/json');
        ini_set('max_execution_time', '-1');
        $currentDate = date('Y-m-d H:i:s');
        $csj = $this->input->get('insj');
        $czsj = $csj;
        $rs_head_dlv = $this->DELV_mod->select_header_bydo($csj);
        $rs_rm_null = $this->DELV_mod->select_rm_null($csj);
        if (count($rs_rm_null) > 0) {
            #check com job
            $ser_com_calcualted = [];
            foreach ($rs_rm_null as $r) {
                $rs_def = $this->SERC_mod->select_cols_where_id(['SERC_COMID', 'SERC_COMJOB', 'SERC_COMQTY'], $r['DLV_SER']); #detail combined ser
                foreach ($rs_def as $k) {
                    $countrm_ = $this->SERD_mod->select_perlabel_resume_item($k['SERC_COMID']); #detail combined ser -> rm count
                    if ($countrm_ == 0) {
                        $ser_com_calcualted[] = ['NEWID' => $r['DLV_SER'], 'COMID' => $k['SERC_COMID'], 'RM' => $countrm_];
                    }
                }
            }

            #filter rs_rm_null
            $rs_filtered = [];
            foreach ($rs_rm_null as $r) {
                foreach ($ser_com_calcualted as $n) {
                    if ($r['DLV_SER'] == $n['NEWID']) {
                        $isfound = false;
                        foreach ($rs_filtered as $n) {
                            if ($n['DLV_SER'] == $r['DLV_SER']) {
                                $isfound = true;
                                break;
                            }
                        }
                        if (!$isfound) {
                            $rs_filtered[] = $r;
                        }
                    }
                }
            }
            if (count($rs_filtered) > 0) {
                $myar[] = ['cd' => 100, 'msg' => 'RM is null, please check again data in the table below'];
                die('{"status" : ' . json_encode($myar) . ', "data": ' . json_encode($rs_filtered) . '}'); #$rs_rm_null
            } else {
                #go ahead
            }
        }
        $rsaktivasi = $this->AKTIVASIAPLIKASI_imod->selectAll();
        $rs_rm_null = $this->DELV_mod->select_rm_null($csj);
        if (count($rs_rm_null) > 0) {
            $myar[] = ['cd' => 100, 'msg' => 'RM is null, please check again data in the table below'];
            die('{"status" : ' . json_encode($myar) . ', "data": ' . json_encode($rs_rm_null) . '}');
        }
        $ccustdate = '';
        $nomoraju = '';
        $czcurrency = '';
        $czdocbctype = '-';
        $cz_KODE_JENIS_TPB = '';
        $czinvoice = '';
        $czkantorasal = $czidmodul = $czidmodul_asli = '';
        $consignee = '-';
        $cznmpenerima = '';
        $czalamatpenerima = '';
        $cznomorpolisi = '-';
        $cznamapengangkut = '';
        $czinvoicedt = '';
        $czidpenerima = '';
        $cztujuanpengiriman = '-';
        $czConaNo = '';
        $czConaDate = '';
        $czPemberitahu = '';
        $czJabatan = '';
        foreach ($rs_head_dlv as $r) {
            if ($r['DLV_BCDATE']) {
                $consignee = $r['DLV_CONSIGN'];
                $czdocbctype = $r['DLV_BCTYPE'];
                $ccustdate = $r['DLV_BCDATE'];
                $nomoraju = $r['DLV_NOAJU'];
                $czinvoice = trim($r['DLV_INVNO']);
                $czcurrency = trim($r['MCUS_CURCD']);
                $cz_KODE_JENIS_TPB = $r['DLV_ZJENIS_TPB_ASAL'];
                $cznmpenerima = $r['MDEL_ZNAMA'];
                $czalamatpenerima = $r['MDEL_ADDRCUSTOMS'];
                $cznomorpolisi = $r['DLV_TRANS'];
                $cznamapengangkut = $r['MSTTRANS_TYPE'];
                $czinvoicedt = $r['DLV_INVDT'];
                $czidpenerima = str_replace([".", "-"], "", $r['MCUS_TAXREG']);
                $cztujuanpengiriman = $r['DLV_PURPOSE'];
                $czConaNo = $r['DLV_CONA'];
                $czConaDate = $r['MCONA_DATE'];
                $czPemberitahu = $r['DLVH_PEMBERITAHU'];
                $czJabatan = $r['DLVH_JABATAN'];
                break;
            }
        }

        if ($cztujuanpengiriman == '-') {
            $myar[] = ['cd' => 0, 'msg' => 'please set TUJUAN PENGIRIMAN (' . $cztujuanpengiriman . ')'];
            die('{"status" : ' . json_encode($myar) . '}');
        }

        if ($consignee == '-') {
            $myar[] = ['cd' => 0, 'msg' => 'please set consignment (' . $consignee . ')'];
            die('{"status" : ' . json_encode($myar) . '}');
        }

        if ($czinvoice == '') {
            $myar[] = ['cd' => 0, 'msg' => 'please add invoice number (' . $czinvoice . ') custdate (' . $ccustdate . ') consign (' . $consignee . ')'];
            die('{"status" : ' . json_encode($myar) . '}');
        }
        $ocustdate = date_create($ccustdate);
        $cz_ymd = date_format($ocustdate, 'Ymd');

        $czidpengusaha = '';
        $cznmpengusaha = '';
        $czalamatpengusaha = '';
        $czizinpengusaha = '';

        foreach ($rsaktivasi as $r) {
            $czkantorasal = $r['KPPBC'];
            $czidmodul = substr('00000' . $r['ID_MODUL'], -6);
            $czidmodul_asli = $r['ID_MODUL'];
            $czidpengusaha = $r['NPWP'];
            $cznmpengusaha = $r['NAMA_PENGUSAHA'];
            $czalamatpengusaha = $r['ALAMAT_PENGUSAHA'];
            $czizinpengusaha = $r['NOMOR_SKEP'];
        }

        if ($czdocbctype == '-') {
            $myar[] = ['cd' => 0, 'msg' => 'please select bc type!'];
            die('{"status" : ' . json_encode($myar) . '}');
        }

        if (strlen($nomoraju) != 6) {
            $myar[] = ['cd' => 0, 'msg' => 'NOMOR AJU is not valid, please re-check (' . $nomoraju . ')'];
            die('{"status" : ' . json_encode($myar) . '}');
        }

        $cnoaju = substr($czkantorasal, 0, 4) . $czdocbctype . $czidmodul . $cz_ymd . $nomoraju;

        if ($this->TPB_HEADER_imod->check_Primary(['NOMOR_AJU' => $cnoaju]) > 0) {
            $myar[] = ['cd' => 0, 'msg' => 'the NOMOR AJU is already posted'];
            die('{"status" : ' . json_encode($myar) . '}');
        }

        $rsdlv = $this->DELV_mod->select_det_byid_p($csj);
        $cz_JUMLAH_KEMASAN = count($rsdlv);

        $myar = [];

        if (strlen($ccustdate) != 10) {
            $myar[] = ['cd' => 0, 'msg' => 'please enter valid customs date!'];
            die('{"status" : ' . json_encode($myar) . '}');
        }

        if ($this->DELV_mod->check_Primary(['DLV_ID' => $csj]) == 0) {
            $myar[] = ['cd' => 0, 'msg' => 'DO is not found'];
            die('{"status" : ' . json_encode($myar) . '}');
        }

        $rscurr = $this->MEXRATE_mod->selectfor_posting($ccustdate, $czcurrency);
        if (count($rscurr) == 0) {
            $dar = ["cd" => "0", "msg" => "Please fill exchange rate data !"];
            $myar[] = $dar;
            die('{"status":' . json_encode($myar) . '}');
        } else {
            foreach ($rscurr as $r) {
                $czharga_matauang = $r->MEXRATE_VAL;
                break;
            }
        }

        #HEADER_HARGA
        log_message('error', $_SERVER['REMOTE_ADDR'] . ', step0#, DO:' . $csj);
        log_message('error', $_SERVER['REMOTE_ADDR'] . ', step1#, start, group by assy code , price, item');
        $cz_h_HARGA_PENYERAHAN_FG = 0;
        $rsitem_p_price = $this->setPriceRS(base64_encode($csj));
        $rspackinglist = $this->DELV_mod->select_packinglist_bydono($csj);
        $rsplotrm_per_fgprice = $this->perprice($csj, $rsitem_p_price);
        $cz_h_JUMLAH_BARANG = count($rsitem_p_price);
        $cz_h_NETTO = 0;
        $cz_h_BRUTO = 0;
        $tpb_barang = [];
        $SERI_BARANG = 1;
        foreach ($rspackinglist as $r) {
            $cz_h_BRUTO += $r['MITM_GWG'];
        }
        foreach ($rsitem_p_price as $r) {
            $t_HARGA_PENYERAHAN = $r['CIF'];
            $cz_h_NETTO += $r['NWG'];
            $cz_h_HARGA_PENYERAHAN_FG += $t_HARGA_PENYERAHAN;
            $tpb_barang[] = [
                'KODE_BARANG' => $r['SSO2_MDLCD'], 'POS_TARIF' => $r['MITM_HSCD'], 'URAIAN' => $r['MITM_ITMD1'], 'JUMLAH_SATUAN' => $r['SISOQTY'], 'KODE_SATUAN' => $r['MITM_STKUOM'] == 'PCS' ? 'PCE' : $r['MITM_STKUOM'], 'NETTO' => $r['NWG'], 'HARGA_PENYERAHAN' => round($r['CIF'], 2), 'SERI_BARANG' => $SERI_BARANG, 'KODE_STATUS' => '02',
            ];
            $SERI_BARANG++;
        }
        #N
        log_message('error', $_SERVER['REMOTE_ADDR'] . ',step1#, finish, group by assy code , price, item');
        #BAHAN BAKU
        $tpb_bahan_baku = [];
        $requestResume = [];
        $responseResume = [];
        $responseTemparray = [];
        try {
            $requestGroup = [];
            log_message('error', $_SERVER['REMOTE_ADDR'] . ',step2#, start, posting group by assy code , price, item');
            foreach ($rsplotrm_per_fgprice as $r) {
                $isfound = false;
                foreach ($requestResume as &$n) {
                    if ($n['ITEM'] == $r['RITEMCD']) {
                        $n['QTY'] += $r['RQTY'];
                        $isfound = true;
                    }
                }
                unset($n);
                if (!$isfound) {
                    $requestResume[] = ['ITEM' => $r['RITEMCD'], 'QTY' => $r['RQTY']];
                }

                $isfound = false;
                foreach ($requestGroup as &$n) {
                    if ($n['XASSY'] == $r['RASSYCODE'] && $n['XPRICE'] == $r['RPRICEGROUP']) {
                        $isfound = true;
                        break;
                    }
                }
                unset($n);
                if (!$isfound) {
                    $requestGroup[] = ['XASSY' => $r['RASSYCODE'], 'XPRICE' => $r['RPRICEGROUP']];
                }
            }
            log_message('error', $_SERVER['REMOTE_ADDR'] . ',step2#, finish, posting group by assy code , price, item');
            foreach ($requestGroup as $k) {
                $ary_item = [];
                $ary_qty = [];
                $ary_lot = [];
                foreach ($rsplotrm_per_fgprice as $r) {
                    if ($k['XASSY'] == $r['RASSYCODE'] && $k['XPRICE'] == $r['RPRICEGROUP']) {
                        $ary_item[] = $r['RITEMCD'];
                        $ary_qty[] = $r['RQTY'];
                        $ary_lot[] = $r['RLOTNO'];
                    }
                }
                log_message('error', $_SERVER['REMOTE_ADDR'] . ',step3#, start, send request');
                $rstemp = $this->inventory_getstockbc_v2($czdocbctype, $cztujuanpengiriman, $csj, $ary_item, $ary_qty, $ary_lot, $ccustdate, $czConaNo);
                $responseTemparray[] = $rstemp;
                log_message('error', $_SERVER['REMOTE_ADDR'] . ',step3#, start, receive request');
                $rsbc = json_decode($rstemp);
                if (!is_null($rsbc)) {
                    if (count($rsbc) > 0) {
                        foreach ($rsbc as $o) {
                            foreach ($o->data as $v) {
                                $isfound = false;
                                foreach ($responseResume as &$n) {
                                    if ($n['ITEM'] == $v->BC_ITEM) {
                                        $n['QTY'] += $v->BC_QTY;
                                        $isfound = true;
                                    }
                                }
                                unset($n);
                                if (!$isfound) {
                                    $responseResume[] = ['ITEM' => $v->BC_ITEM, 'QTY' => $v->BC_QTY];
                                }
                                //THE ADDITIONAL INFO
                                if ($v->RCV_KPPBC != '-') {
                                    $tpb_bahan_baku[] = [
                                        'KODE_JENIS_DOK_ASAL' => $v->BC_TYPE, 'NOMOR_DAFTAR_DOK_ASAL' => $v->BC_NUM, 'TANGGAL_DAFTAR_DOK_ASAL' => $v->BC_DATE, 'KODE_KANTOR' => $v->RCV_KPPBC, 'NOMOR_AJU_DOK_ASAL' => $v->BC_AJU, 'SERI_BARANG_DOK_ASAL' => $v->RCV_ZNOURUT, 'HARGA_PENYERAHAN' => substr($v->RCV_PRPRC, 0, 1) == '.' ? ('0' . $v->RCV_PRPRC * $v->BC_QTY) : ($v->RCV_PRPRC * $v->BC_QTY), 'KODE_BARANG' => trim($v->BC_ITEM), 'KODE_STATUS' => "03", 'URAIAN' => $v->MITM_ITMD1, 'JUMLAH_SATUAN' => $v->BC_QTY, 'JENIS_SATUAN' => ($v->MITM_STKUOM == 'PCS') ? 'PCE' : $v->MITM_STKUOM, 'KODE_ASAL_BAHAN_BAKU' => 1, 'RASSYCODE' => $k['XASSY'], 'RPRICEGROUP' => $k['XPRICE'], 'RQTY' => $r['RQTY'], 'RBM' => substr($v->RCV_BM, 0, 1) == '.' ? ('0' . $v->RCV_BM) : ($v->RCV_BM),

                                    ];
                                } else {
                                    $tpb_bahan_baku[] = [
                                        'KODE_JENIS_DOK_ASAL' => $v->BC_TYPE, 'NOMOR_DAFTAR_DOK_ASAL' => $v->BC_NUM, 'TANGGAL_DAFTAR_DOK_ASAL' => $v->BC_DATE, 'KODE_KANTOR' => null, 'NOMOR_AJU_DOK_ASAL' => $v->BC_AJU, 'SERI_BARANG_DOK_ASAL' => $v->RCV_ZNOURUT, 'HARGA_PENYERAHAN' => 0, 'KODE_BARANG' => $v->BC_ITEM, 'KODE_STATUS' => "02", 'URAIAN' => $v->MITM_ITMD1, 'JUMLAH_SATUAN' => $v->BC_QTY, 'JENIS_SATUAN' => 'PCE', 'KODE_ASAL_BAHAN_BAKU' => 1, 'RASSYCODE' => $k['XASSY'], 'RPRICEGROUP' => $k['XPRICE'], 'RQTY' => $r['RQTY'], 'RBM' => 0,
                                    ];
                                }
                            }
                        }
                    } else {
                        $this->inventory_cancelDO($csj);
                        $myar[] = ["cd" => "0", "msg" => "Could not find exbc, please contact admin !"];
                        die('{"status":' . json_encode($myar) . '}');
                    }
                } else {
                    $this->inventory_cancelDO($csj);
                    $myar[] = ["cd" => "0", "msg" => "Could not find exbc, please contact admin"];
                    die(json_encode([
                        'status' => $myar, 'request_h' => ['doc_type' => $czdocbctype], 'request_d' => ['item' => $ary_item, 'qty' => $ary_qty, 'lot' => $ary_lot], 'respon' => $rstemp,
                    ]));
                }
                log_message('error', $_SERVER['REMOTE_ADDR'] . ',step3#, finish, receive request');
            }
            $listNeedExBC = [];
            foreach ($requestResume as $r) {
                $isfound = false;
                foreach ($responseResume as $n) {
                    if ($r['ITEM'] == $n['ITEM']) {
                        $isfound = true;
                        if ($r['QTY'] != $n['QTY']) {
                            $listNeedExBC[] = ['ITMCD' => $r['ITEM'], 'QTY' => $r['QTY'] - $n['QTY'], 'LOTNO' => '?'];
                        }
                    }
                }
                if (!$isfound) {
                    $listNeedExBC[] = ['ITMCD' => $r['ITEM'], 'QTY' => $r['QTY'], 'LOTNO' => '?'];
                }
            }
            if (count($listNeedExBC) > 0) {
                $this->inventory_cancelDO($csj);
                $myar[] = ['cd' => 110, 'msg' => 'EX-BC for ' . count($listNeedExBC) . ' item(s) is not found. ', "doctype" => $czdocbctype, "tujuankirim" => $cztujuanpengiriman];
                die(json_encode([
                    'status' => $myar, 'data' => $listNeedExBC, 'rawdata' => $rstemp, 'czdocbctype' => $czdocbctype, 'cztujuanpengiriman' => $cztujuanpengiriman, 'responseTemparray' => $responseTemparray, 'requestResume' => $requestResume, 'responresume' => $responseResume,
                ]));
            }
            #clear
            $ary_item = [];
            $ary_qty = [];
            $ary_lot = [];
            $requestResume = [];
            $responseResume = [];

            unset($ary_item);
            unset($ary_qty);
            unset($ary_lot);
            unset($requestResume);
            unset($responseResume);
            #end
        } catch (Exception $e) {
            $this->inventory_cancelDO($csj);
            $myar[] = ['cd' => 110, 'msg' => $e->getMessage()];
            die('{"status" : ' . json_encode($myar) . ',"data":"' . $rstemp . '"}');
        }
        foreach ($rsplotrm_per_fgprice as $r) {
            $nomor = 1;
            foreach ($tpb_bahan_baku as &$n) {
                if (
                    $r['RASSYCODE'] == $n['RASSYCODE']
                    && $r['RPRICEGROUP'] == $n['RPRICEGROUP']
                ) {
                    $n['SERI_BAHAN_BAKU'] = $nomor;
                    $nomor++;
                }
            }
            unset($n);
        }
        #N

        $tpb_header = [
            "NOMOR_AJU" => $cnoaju, "KODE_KANTOR" => $czkantorasal,
            "KODE_JENIS_TPB" => $cz_KODE_JENIS_TPB, "KODE_TUJUAN_PENGIRIMAN" => $cztujuanpengiriman,

            "KODE_ID_PENGUSAHA" => "1", "ID_PENGUSAHA" => $czidpengusaha, "NAMA_PENGUSAHA" => $cznmpengusaha,
            "ALAMAT_PENGUSAHA" => $czalamatpengusaha, "NOMOR_IJIN_TPB" => $czizinpengusaha,

            "KODE_ID_PENERIMA_BARANG" => "1", "ID_PENERIMA_BARANG" => $czidpenerima,
            "NAMA_PENERIMA_BARANG" => $cznmpenerima, "ALAMAT_PENERIMA_BARANG" => $czalamatpenerima,

            "HARGA_PENYERAHAN" => $cz_h_HARGA_PENYERAHAN_FG,

            "NAMA_PENGANGKUT" => $cznamapengangkut, "NOMOR_POLISI" => $cznomorpolisi,

            "BRUTO" => $cz_h_BRUTO, "NETTO" => $cz_h_NETTO, "JUMLAH_BARANG" => $cz_h_JUMLAH_BARANG,

            "KOTA_TTD" => "CIKARANG", "TANGGAL_TTD" => $ccustdate, "NAMA_TTD" => $czPemberitahu, "JABATAN_TTD" => $czJabatan,

            "KODE_DOKUMEN_PABEAN" => $czdocbctype, "ID_MODUL" => $czidmodul_asli, "VERSI_MODUL" => null,

            "VOLUME" => 0, "KODE_STATUS" => '00',
        ];
        $tpb_kemasan = [];
        $tpb_kemasan[] = ["JUMLAH_KEMASAN" => $cz_JUMLAH_KEMASAN, "KODE_JENIS_KEMASAN" => "BX"];

        $tpb_dokumen = [];
        $tpb_dokumen[] = ["KODE_JENIS_DOKUMEN" => "380", "NOMOR_DOKUMEN" => $czinvoice, "TANGGAL_DOKUMEN" => $czinvoicedt, "TIPE_DOKUMEN" => "02", "SERI_DOKUMEN" => 1]; //invoice
        $tpb_dokumen[] = ["KODE_JENIS_DOKUMEN" => "217", "NOMOR_DOKUMEN" => $czinvoice, "TANGGAL_DOKUMEN" => $czinvoicedt, "TIPE_DOKUMEN" => "02", "SERI_DOKUMEN" => 2]; //packing list
        $tpb_dokumen[] = ["KODE_JENIS_DOKUMEN" => "640", "NOMOR_DOKUMEN" => $czsj, "TANGGAL_DOKUMEN" => $ccustdate, "TIPE_DOKUMEN" => "02", "SERI_DOKUMEN" => 3]; //surat jalan
        $tpb_dokumen[] = ["KODE_JENIS_DOKUMEN" => "315", "NOMOR_DOKUMEN" => $czConaNo, "TANGGAL_DOKUMEN" => $czConaDate, "TIPE_DOKUMEN" => "02", "SERI_DOKUMEN" => 4]; //kontrak

        #exbc list
        $tpb_dokumen_40 = [];
        $tpb_dokumen_40_tgl = [];
        foreach ($tpb_bahan_baku as $k) {
            if (!in_array($k['NOMOR_DAFTAR_DOK_ASAL'], $tpb_dokumen_40)) {
                $tpb_dokumen_40[] = $k['NOMOR_DAFTAR_DOK_ASAL'];
                $tpb_dokumen_40_tgl[] = $k['TANGGAL_DAFTAR_DOK_ASAL'];
            }
        }

        $noseri = 5;
        $cO_exBC = count($tpb_dokumen_40);
        for ($i = 0; $i < $cO_exBC; $i++) {
            $tpb_dokumen[] = [
                "KODE_JENIS_DOKUMEN" => "40", "NOMOR_DOKUMEN" => $tpb_dokumen_40[$i], "TANGGAL_DOKUMEN" => $tpb_dokumen_40_tgl[$i], "TIPE_DOKUMEN" => "01", "SERI_DOKUMEN" => $noseri,
            ];
            $noseri++;
        }
        log_message('error', $_SERVER['REMOTE_ADDR'] . ',step4#, start, INSERT TPB');
        #INSERT CEISA
        ##1 TPB HEADER
        $ZR_TPB_HEADER = $this->TPB_HEADER_imod->insert($tpb_header);
        ##N

        ##2 TPB DOKUMEN
        foreach ($tpb_dokumen as &$n) {
            $n['ID_HEADER'] = $ZR_TPB_HEADER;
        }
        unset($n);
        $this->TPB_DOKUMEN_imod->insertb($tpb_dokumen);
        ##N

        ##3 TPB KEMASAN
        foreach ($tpb_kemasan as &$n) {
            $n['ID_HEADER'] = $ZR_TPB_HEADER;
        }
        unset($n);
        $this->TPB_KEMASAN_imod->insertb($tpb_kemasan);
        ##N

        ##4 TPB BARANG
        foreach ($tpb_barang as &$n) {
            $n['ID_HEADER'] = $ZR_TPB_HEADER;
            foreach ($tpb_bahan_baku as $j) {
                if ($n['KODE_BARANG'] == $j['RASSYCODE'] && $n['HARGA_PENYERAHAN'] == $j['RPRICEGROUP']) {
                    if (!isset($n['JUMLAH_BAHAN_BAKU'])) {
                        $n['JUMLAH_BAHAN_BAKU'] = 1;
                    } else {
                        $n['JUMLAH_BAHAN_BAKU']++;
                    }
                }
            }
        }
        unset($n);

        ##N

        ##4 TPB BARANG & BAHAN BAKU
        foreach ($tpb_barang as $n) {
            $ZR_TPB_BARANG = $this->TPB_BARANG_imod->insert($n);
            foreach ($tpb_bahan_baku as $b) {
                if ($n['KODE_BARANG'] == $b['RASSYCODE'] && $n['HARGA_PENYERAHAN'] == $b['RPRICEGROUP']) {
                    $this->TPB_BAHAN_BAKU_imod
                        ->insert([
                            'KODE_JENIS_DOK_ASAL' => $b['KODE_JENIS_DOK_ASAL'], 'NOMOR_DAFTAR_DOK_ASAL' => $b['NOMOR_DAFTAR_DOK_ASAL'], 'TANGGAL_DAFTAR_DOK_ASAL' => $b['TANGGAL_DAFTAR_DOK_ASAL'], 'KODE_KANTOR' => $b['KODE_KANTOR'], 'NOMOR_AJU_DOK_ASAL' => $b['NOMOR_AJU_DOK_ASAL'], 'SERI_BARANG_DOK_ASAL' => $b['SERI_BARANG_DOK_ASAL'], 'HARGA_PENYERAHAN' => $b['HARGA_PENYERAHAN'], 'KODE_BARANG' => $b['KODE_BARANG'], 'KODE_STATUS' => $b['KODE_STATUS'], 'URAIAN' => $b['URAIAN'], 'JUMLAH_SATUAN' => $b['JUMLAH_SATUAN'], 'JENIS_SATUAN' => $b['JENIS_SATUAN'], 'KODE_ASAL_BAHAN_BAKU' => $b['KODE_ASAL_BAHAN_BAKU'], 'NETTO' => 0, 'SERI_BAHAN_BAKU' => $b['SERI_BAHAN_BAKU'], 'SERI_BARANG' => $n['SERI_BARANG'], 'ID_BARANG' => $ZR_TPB_BARANG, 'ID_HEADER' => $ZR_TPB_HEADER,
                        ]);
                }
            }
        }
        log_message('error', $_SERVER['REMOTE_ADDR'] . ',step4#, finish, INSERT TPB ');
        ##N
        $this->DELV_mod->updatebyVAR(['DLV_POST' => $this->session->userdata('nama'), 'DLV_POSTTM' => $currentDate], ['DLV_ID' => $csj]);
        if ($consignee != 'IEI') {
            $this->sendto_delivery_checking($csj);
        }
        #N
        $myar[] = ['cd' => 1, 'msg' => 'Done, check your TPB'];
        $this->setPrice(base64_encode($csj));
        die('{"status" : ' . json_encode($myar) . '}');
    }

    public function set_log_finish_posting($DONumber)
    {
        #set finished time
        $now = DateTime::createFromFormat('U.u', microtime(true))->setTimezone(new DateTimeZone(date('T')));
        $finishTM = $now->format('Y-m-d H:i:s:v');
        $this->POSTING_mod->update_where(
            ['POSTING_FINISHED_AT' => $finishTM],
            [
                'POSTING_DOC' => $DONumber,
                'POSTING_IP' => $_SERVER['REMOTE_ADDR'],
                'POSTING_FINISHED_AT' => null,
            ]
        );
        #end
    }

    public function posting25()
    {
        set_exception_handler([$this, 'exception_handler']);
        set_error_handler([$this, 'log_error']);
        register_shutdown_function([$this, 'fatal_handler']);

        header('Content-Type: application/json');
        ini_set('max_execution_time', '-1');
        $currentDate = date('Y-m-d H:i:s');
        $csj = $this->input->get('insj');
        $now = DateTime::createFromFormat('U.u', microtime(true))->setTimezone(new DateTimeZone(date('T')));
        $startTM = $now->format('Y-m-d H:i:s:v');
        $rsUnfinished = $this->POSTING_mod->select_unfinished($csj);
        if (empty($rsUnfinished)) {
            $this->POSTING_mod->insert([
                'POSTING_DOC' => $csj,
                'POSTING_STATUS' => 1,
                'POSTING_STARTED_AT' => $startTM,
                'POSTING_BY' => $this->session->userdata('nama'),
                'POSTING_IP' => $_SERVER['REMOTE_ADDR'],
            ]);
        } else {
            $myar[] = ['cd' => '130', 'msg' => 'there is another user posting this document'];
        }

        $czsj = $csj;
        $rs_head_dlv = $this->DELV_mod->select_header_bydo($csj);
        $rs_rm_null = $this->DELV_mod->select_rm_null($csj);
        if (count($rs_rm_null) > 0) {
            #check com job
            $ser_com_calcualted = [];
            foreach ($rs_rm_null as $r) {
                $rs_def = $this->SERC_mod->select_cols_where_id(['SERC_COMID', 'SERC_COMJOB', 'SERC_COMQTY'], $r['DLV_SER']); #detail combined ser
                foreach ($rs_def as $k) {
                    $countrm_ = $this->SERD_mod->select_perlabel_resume_item($k['SERC_COMID']); #detail combined ser -> rm count
                    if ($countrm_ == 0) {
                        $ser_com_calcualted[] = ['NEWID' => $r['DLV_SER'], 'COMID' => $k['SERC_COMID'], 'RM' => $countrm_];
                    }
                }
            }

            #filter rs_rm_null
            $rs_filtered = [];
            foreach ($rs_rm_null as $r) {
                foreach ($ser_com_calcualted as $n) {
                    if ($r['DLV_SER'] == $n['NEWID']) {
                        $isfound = false;
                        foreach ($rs_filtered as $n) {
                            if ($n['DLV_SER'] == $r['DLV_SER']) {
                                $isfound = true;
                                break;
                            }
                        }
                        if (!$isfound) {
                            $rs_filtered[] = $r;
                        }
                    }
                }
            }
            if (count($rs_filtered) > 0) {
                $this->set_log_finish_posting($csj);
                $myar[] = ['cd' => 100, 'msg' => 'RM is null, please check again data in the table below'];
                die('{"status" : ' . json_encode($myar) . ', "data": ' . json_encode($rs_filtered) . '}'); #$rs_rm_null
            } else {
                #go ahead
            }
        }
        $rsaktivasi = $this->AKTIVASIAPLIKASI_imod->selectAll();

        $ccustdate = '';
        $nomoraju = '';
        $ccustomer_do = '';
        $cbusiness_group = '';
        $czcurrency = '';
        $czdocbctype = '';
        $cz_KODE_JENIS_TPB = '';
        $czinvoice = '';
        $czkantorasal = $czidmodul = $czidmodul_asli = '';
        $consignee = '-';
        $cznmpenerima = '';
        $czalamatpenerima = '';
        $czKODE_CARA_ANGKUT = '';
        $czSKB = '';
        $czinvoicedt = '';
        $czidpenerima = '';
        $cz_h_NDPBM = 0;
        $czPemberitahu = '';
        $czJabatan = '';
        foreach ($rs_head_dlv as $r) {
            if ($r['DLV_BCDATE']) {
                $consignee = $r['DLV_CONSIGN'];
                $czdocbctype = $r['DLV_BCTYPE'];
                $ccustdate = $r['DLV_BCDATE'];
                $nomoraju = $r['DLV_NOAJU'];
                $ccustomer_do = $r['DLV_CUSTDO'];
                $czinvoice = trim($r['DLV_INVNO']);
                $cbusiness_group = $r['DLV_BSGRP'];
                $czcurrency = trim($r['MCUS_CURCD']);
                $cz_KODE_JENIS_TPB = $r['DLV_ZJENIS_TPB_ASAL'];
                $cznmpenerima = $r['MDEL_ZNAMA'];
                $czalamatpenerima = $r['MDEL_ADDRCUSTOMS'];
                $czKODE_CARA_ANGKUT = $r['DLV_ZKODE_CARA_ANGKUT'];
                $czSKB = $r['DLV_ZSKB'];
                $czinvoicedt = $r['DLV_INVDT'];
                $czidpenerima = str_replace([".", "-"], "", $r['MCUS_TAXREG']);
                $czPemberitahu = $r['DLVH_PEMBERITAHU'];
                $czJabatan = $r['DLVH_JABATAN'];
                break;
            }
        }

        if ($consignee == '-') {
            $this->set_log_finish_posting($csj);
            $myar[] = ['cd' => 0, 'msg' => 'please set consignment (' . $consignee . ')'];
            die('{"status" : ' . json_encode($myar) . '}');
        }

        if ($czinvoice == '') {
            $this->set_log_finish_posting($csj);
            $myar[] = ['cd' => 0, 'msg' => 'please add invoice number (' . $czinvoice . ') custdate (' . $ccustdate . ') consign (' . $consignee . ')'];
            die('{"status" : ' . json_encode($myar) . '}');
        }
        $ocustdate = date_create($ccustdate);
        $cz_ymd = date_format($ocustdate, 'Ymd');

        $czidpengusaha = '';
        $cznmpengusaha = '';
        $czalamatpengusaha = '';
        $czizinpengusaha = '';
        $czharga_matauang = '';

        foreach ($rsaktivasi as $r) {
            $czkantorasal = $r['KPPBC'];
            $czidmodul = substr('00000' . $r['ID_MODUL'], -6);
            $czidmodul_asli = $r['ID_MODUL'];
            $czidpengusaha = $r['NPWP'];
            $cznmpengusaha = $r['NAMA_PENGUSAHA'];
            $czalamatpengusaha = $r['ALAMAT_PENGUSAHA'];
            $czizinpengusaha = $r['NOMOR_SKEP'];
        }

        if ($czdocbctype == '') {
            $this->set_log_finish_posting($csj);
            $myar[] = ['cd' => 0, 'msg' => 'please select bc type!'];
            die('{"status" : ' . json_encode($myar) . '}');
        }

        if (strlen($nomoraju) != 6) {
            $this->set_log_finish_posting($csj);
            $myar[] = ['cd' => 0, 'msg' => 'NOMOR AJU is not valid, please re-check (' . $nomoraju . ')'];
            die('{"status" : ' . json_encode($myar) . '}');
        }

        $cnoaju = substr($czkantorasal, 0, 4) . $czdocbctype . $czidmodul . $cz_ymd . $nomoraju;

        if ($this->TPB_HEADER_imod->check_Primary(['NOMOR_AJU' => $cnoaju]) > 0) {
            $this->set_log_finish_posting($csj);
            $myar[] = ['cd' => 0, 'msg' => 'the NOMOR AJU is already posted'];
            die('{"status" : ' . json_encode($myar) . '}');
        }

        if (($cbusiness_group == 'PSI2PPZOMC' || $cbusiness_group == 'PSI2PPZOMI')) {
            if (strlen($ccustomer_do) < 5) {
                $this->set_log_finish_posting($csj);
                $myar[] = ['cd' => 0, 'msg' => 'please enter valid Customer DO!'];
                die('{"status" : ' . json_encode($myar) . '}');
            }
            $czsj = $ccustomer_do;
        }
        if (strlen($nomoraju) != 6) {
            $this->set_log_finish_posting($csj);
            $myar[] = ['cd' => 0, 'msg' => 'please enter valid Nomor Pengajuan!'];
            die('{"status" : ' . json_encode($myar) . '}');
        }

        $rsdlv = $this->DELV_mod->select_det_byid_p($csj);
        $r_barang_kemasan = [];
        foreach ($rsdlv as $r) {
            $isfound = false;
            foreach ($r_barang_kemasan as &$h) {
                if ($r['SER_ITMID'] == $h['SER_ITMID']) {
                    $h['JUMLAH_KEMASAN']++;
                    $isfound = true;
                }
            }
            unset($h);
            if (!$isfound) {
                $r_barang_kemasan[] = ['SER_ITMID' => $r['SER_ITMID'], 'JUMLAH_KEMASAN' => 1];
            }
        }
        $cz_JUMLAH_KEMASAN = count($rsdlv);

        if (strlen($ccustdate) != 10) {
            $this->set_log_finish_posting($csj);
            $myar[] = ['cd' => 0, 'msg' => 'please enter valid customs date!'];
            die('{"status" : ' . json_encode($myar) . '}');
        }

        if ($this->DELV_mod->check_Primary(['DLV_ID' => $csj]) == 0) {
            $this->set_log_finish_posting($csj);
            $myar[] = ['cd' => 0, 'msg' => 'DO is not found'];
            die('{"status" : ' . json_encode($myar) . '}');
        }

        if ($this->TPB_HEADER_imod->check_Primary(['NOMOR_AJU' => $cnoaju])) {
            $this->set_log_finish_posting($csj);
            $myar[] = ['cd' => 0, 'msg' => 'Already posted'];
            die('{"status" : ' . json_encode($myar) . '}');
        }

        $rscurr = $this->MEXRATE_mod->selectfor_posting($ccustdate, $czcurrency);
        if (count($rscurr) == 0) {
            $this->set_log_finish_posting($csj);
            $dar = ["cd" => "0", "msg" => "Please fill exchange rate data !"];
            $myar[] = $dar;
            die('{"status":' . json_encode($myar) . '}');
        } else {
            foreach ($rscurr as $r) {
                if ($czcurrency == 'RPH') {
                    $czharga_matauang = 1;
                    $cz_h_NDPBM = $r->MEXRATE_VAL;
                    break;
                } else {
                    $czharga_matauang = $r->MEXRATE_VAL;
                    break;
                }
            }
        }

        #HEADER_HARGA
        log_message('error', $_SERVER['REMOTE_ADDR'] . ', step0#, DO:' . $csj);
        log_message('error', $_SERVER['REMOTE_ADDR'] . ', step1#, start, group by assy code , price, item');
        $cz_h_CIF_FG = 0;
        $cz_h_HARGA_PENYERAHAN_FG = 0;
        $rsitem_p_price = $this->setPriceRS(base64_encode($csj));
        $rspackinglist = $this->DELV_mod->select_packinglist_bydono($csj);
        $rsplotrm_per_fgprice = $this->perprice($csj, $rsitem_p_price);

        $cz_h_JUMLAH_BARANG = count($rsitem_p_price);
        $cz_h_NETTO = 0;
        $cz_h_BRUTO = 0;
        $tpb_barang = [];
        $SERI_BARANG = 1;
        $cz_h_totalCIF = 0;
        foreach ($rspackinglist as $r) {
            $cz_h_BRUTO += $r['MITM_GWG'];
        }
        foreach ($rsitem_p_price as $r) {
            #handle multiprice
            foreach ($rspackinglist as $p) {
                if ($r['SSO2_MDLCD'] === $p['SI_ITMCD']) {
                    $r['NWG'] = $r['SISOQTY'] * $p['MSTNWG'];
                    break;
                }
            }
            #handle multiprice
            $t_HARGA_PENYERAHAN = $r['CIF'] * $czharga_matauang;
            $cz_h_CIF_FG += $r['CIF'];
            $cz_h_NETTO += $r['NWG'];
            $cz_h_HARGA_PENYERAHAN_FG += $t_HARGA_PENYERAHAN;
            $tpb_barang[] = [
                'KODE_GUNA' => '3'
                , 'KATEGORI_BARANG' => '1'
                , 'KONDISI_BARANG' => '1'
                , 'KODE_BARANG' => $r['SSO2_MDLCD']
                , 'KODE_NEGARA_ASAL' => 'ID'
                , 'POS_TARIF' => $r['MITM_HSCD']
                , 'URAIAN' => $r['MITM_ITMD1']
                , 'JUMLAH_SATUAN' => $r['SISOQTY']
                , 'KODE_SATUAN' => $r['MITM_STKUOM'] == 'PCS' ? 'PCE' : $r['MITM_STKUOM']
                , 'NETTO' => $r['NWG'], 'KODE_KEMASAN' => 'BX'
                , 'CIF' => $r['CIF']
                , 'HARGA_PENYERAHAN' => $t_HARGA_PENYERAHAN
                , 'SERI_BARANG' => $SERI_BARANG
                , 'KODE_STATUS' => '02'
                , 'KODE_PERHITUNGAN' => '0'
                , 'BM' => $r['BM']
                , 'PPN' => $r['PPN']
                , 'PPH' => $r['PPH'],
            ];
            $SERI_BARANG++;
        }
        log_message('error', $_SERVER['REMOTE_ADDR'] . ',step1#, finish, group by assy code , price, item');
        foreach ($tpb_barang as &$r) {
            foreach ($r_barang_kemasan as $k) {
                if ($r['KODE_BARANG'] == $k['SER_ITMID']) {
                    $r['JUMLAH_KEMASAN'] = $k['JUMLAH_KEMASAN'];
                    break;
                }
            }
        }
        unset($r);
        #N

        #BAHAN BAKU
        $tpb_bahan_baku = [];
        $requestResume = [];
        $responseResume = [];
        try {
            $requestGroup = [];
            log_message('error', $_SERVER['REMOTE_ADDR'] . ',step2#, start, posting group by assy code , price, item');
            foreach ($rsplotrm_per_fgprice as $r) {
                $isfound = false;
                foreach ($requestResume as &$n) {
                    if ($n['ITEM'] == $r['RITEMCD']) {
                        $n['QTY'] += $r['RQTY'];
                        $isfound = true;
                    }
                }
                unset($n);
                if (!$isfound) {
                    $requestResume[] = ['ITEM' => $r['RITEMCD'], 'QTY' => $r['RQTY'], 'ITEMGR' => $r['RITEMCDGR'], 'QTYPLOT' => 0];
                }

                $isfound = false;
                foreach ($requestGroup as &$n) {
                    if ($n['XASSY'] == $r['RASSYCODE'] && $n['XPRICE'] == $r['RPRICEGROUP']) {
                        $isfound = true;
                        break;
                    }
                }
                unset($n);
                if (!$isfound) {
                    $requestGroup[] = ['XASSY' => $r['RASSYCODE'], 'XPRICE' => $r['RPRICEGROUP']];
                }
            }
            log_message('error', $_SERVER['REMOTE_ADDR'] . ',step2#, finish, posting group by assy code , price, item');
            foreach ($requestGroup as $k) {
                $ary_item = [];
                $ary_hscd = [];
                $ary_qty = [];
                $ary_lot = [];
                $ary_bm = [];
                $ary_ppn = [];
                $ary_pph = [];
                foreach ($rsplotrm_per_fgprice as $r) {
                    if ($k['XASSY'] == $r['RASSYCODE'] && $k['XPRICE'] == $r['RPRICEGROUP']) {
                        $ary_item[] = $r['RITEMCDGR'] == '' ? $r['RITEMCD'] : $r['RITEMCDGR'];
                        $ary_hscd[] = '';
                        $ary_bm[] = '';
                        $ary_ppn[] = '';
                        $ary_pph[] = '';
                        $ary_qty[] = $r['RQTY'];
                        $ary_lot[] = $r['RLOTNO'];
                    }
                }
                $rshscd = $this->MSTITM_mod->select_forcustoms($ary_item);
                $count_rsallitem = count($ary_item);
                foreach ($rshscd as $b) {
                    for ($i = 0; $i < $count_rsallitem; $i++) {
                        if ($b['MITM_ITMCD'] == $ary_item[$i]) {
                            $ary_hscd[$i] = $b['MITM_HSCD'];
                            $ary_bm[$i] = $b['MITM_BM'];
                            $ary_ppn[$i] = $b['MITM_PPN'];
                            $ary_pph[$i] = $b['MITM_PPH'];
                            break;
                        }
                    }
                }
                log_message('error', $_SERVER['REMOTE_ADDR'] . ',step3#, start, send request');
                $rstemp = $this->inventory_getstockbc_v2($czdocbctype, "-", $csj, $ary_item, $ary_qty, $ary_lot, $ccustdate);
                log_message('error', $_SERVER['REMOTE_ADDR'] . ',step3#, start, receive request');
                $rsbc = json_decode($rstemp);
                $fields = [
                    'bc' => $czdocbctype,
                    'date_out' => $ccustdate,
                    'doc' => $csj,
                    'item_num' => $ary_item,
                    'qty' => $ary_qty,
                    'lot' => $ary_lot,
                ];
                if (!is_null($rsbc)) {
                    if (count($rsbc) > 0) {
                        #assign currency
                        $ajuList = [];
                        foreach ($rsbc as $o) {
                            foreach ($o->data as $v) {
                                if (!in_array($v->BC_AJU, $ajuList)) {
                                    $ajuList[] = $v->BC_AJU;
                                }
                            }
                        }

                        $ajustring = is_array($ajuList) ? "'" . implode("','", $ajuList) . "'" : "''";
                        $rsRCVCurrency = $this->RCV_mod->select_currency_byaju($ajustring);
                        if (!empty($ajuList)) {
                            foreach ($rsbc as $o) {
                                foreach ($o->data as $v) {
                                    foreach ($rsRCVCurrency as $r_) {
                                        if ($v->BC_AJU === $r_['RCV_RPNO']) {
                                            $v->CURRENCY = $r_['MSUP_SUPCR'];
                                            break;
                                        }
                                    }
                                }
                            }
                        }
                        #end

                        foreach ($rsbc as $o) {
                            foreach ($o->data as $v) {
                                if ($v->BC_QTY > 0) {
                                    $isfound = false;
                                    foreach ($responseResume as &$n) {
                                        if ($n['ITEM'] == strtoupper($v->BC_ITEM)) {
                                            $n['QTY'] += $v->BC_QTY;
                                            $n['BALRES'] += $v->BC_QTY;
                                            $isfound = true;
                                        }
                                    }
                                    unset($n);
                                    if (!$isfound) {
                                        $responseResume[] = ['ITEM' => strtoupper($v->BC_ITEM), 'QTY' => $v->BC_QTY, 'BALRES' => $v->BC_QTY];
                                    }
                                    //THE ADDITIONAL INFO
                                    $tCIF = ($v->RCV_PRPRC * $v->BC_QTY);
                                    $cz_h_totalCIF += $tCIF;

                                    $thehscode = '';
                                    $theppn = '';
                                    $thepph = '';
                                    $thebm = '';

                                    for ($h = 0; $h < $count_rsallitem; $h++) {
                                        if ($v->BC_ITEM == $ary_item[$h]) {
                                            $thehscode = $ary_hscd[$h];
                                            $thebm = $ary_bm[$h];
                                            $theppn = $ary_ppn[$h];
                                            $thepph = $ary_pph[$h];
                                            break;
                                        }
                                    }
                                    if (trim($thehscode) == '') {
                                        $thehscode = $v->RCV_HSCD;
                                        $thepph = $v->RCV_PPH;
                                        $theppn = $v->RCV_PPN;
                                        $thebm = $v->RCV_BM;
                                    }
                                    if ($v->RCV_KPPBC != '-') {
                                        if ($v->CURRENCY === 'RPH') {
                                            $harga_perolehan = $v->BC_TYPE == '40' ? $tCIF / $cz_h_NDPBM : null;
                                            $harga_penyerahan = $v->BC_TYPE == '40' ? $tCIF : null;
                                        } else {
                                            $harga_perolehan = $v->BC_TYPE == '40' ? $tCIF : null;
                                            $harga_penyerahan = $v->BC_TYPE == '40' ? $tCIF * $cz_h_NDPBM : null;
                                        }
                                        $tpb_bahan_baku[] = [
                                            'KODE_JENIS_DOK_ASAL' => $v->BC_TYPE, 'NOMOR_DAFTAR_DOK_ASAL' => $v->BC_NUM, 'TANGGAL_DAFTAR_DOK_ASAL' => $v->BC_DATE, 'KODE_KANTOR' => $v->RCV_KPPBC, 'NOMOR_AJU_DOK_ASAL' => strlen($v->BC_AJU) == 6 ? substr('000000000000000000000000', 0, 26) : $v->BC_AJU, 'SERI_BARANG_DOK_ASAL' => !$v->RCV_ZNOURUT || $v->RCV_ZNOURUT == '' ? 0 : $v->RCV_ZNOURUT, 'CIF' => $v->BC_TYPE == '40' ? null : $tCIF, 'CIF_RUPIAH' => $v->BC_TYPE == '40' ? null : ($tCIF * $cz_h_NDPBM), 'NDPBM' => $cz_h_NDPBM, 'KODE_BARANG' => trim(strtoupper($v->BC_ITEM)), 'KODE_STATUS' => "03", 'POS_TARIF' => $thehscode, 'URAIAN' => $v->MITM_ITMD1, 'TIPE' => $v->MITM_SPTNO, 'JUMLAH_SATUAN' => $v->BC_QTY, 'JENIS_SATUAN' => ($v->MITM_STKUOM == 'PCS') ? 'PCE' : $v->MITM_STKUOM, 'KODE_ASAL_BAHAN_BAKU' => ($v->BC_TYPE == '27' || $v->BC_TYPE == '23') ? '0' : '1', 'HARGA_PEROLEHAN' => $harga_perolehan, 'HARGA_PENYERAHAN' => $harga_penyerahan, 'RASSYCODE' => $k['XASSY'], 'RPRICEGROUP' => $k['XPRICE'], 'RBM' => $thebm, 'PPH' => $thepph, 'PPN' => $theppn,
                                        ];
                                    } else {
                                        $tpb_bahan_baku[] = [
                                            'KODE_JENIS_DOK_ASAL' => $v->BC_TYPE, 'NOMOR_DAFTAR_DOK_ASAL' => $v->BC_NUM, 'TANGGAL_DAFTAR_DOK_ASAL' => $v->BC_DATE, 'KODE_KANTOR' => null, 'NOMOR_AJU_DOK_ASAL' => strlen($v->BC_AJU) == 6 ? substr('000000000000000000000000', 0, 26) : $v->BC_AJU, 'SERI_BARANG_DOK_ASAL' => !$v->RCV_ZNOURUT || $v->RCV_ZNOURUT == '' ? 0 : $v->RCV_ZNOURUT, 'SPESIFIKASI_LAIN' => null, 'CIF' => $tCIF, 'CIF_RUPIAH' => ($tCIF * $cz_h_NDPBM), 'NDPBM' => $cz_h_NDPBM, 'HARGA_PENYERAHAN' => 0, 'KODE_BARANG' => strtoupper($v->BC_ITEM), 'KODE_STATUS' => "02", 'POS_TARIF' => $thehscode, 'URAIAN' => $v->MITM_ITMD1, 'TIPE' => $v->MITM_SPTNO, 'JUMLAH_SATUAN' => $v->BC_QTY, 'JENIS_SATUAN' => 'PCE', 'KODE_ASAL_BAHAN_BAKU' => 0, 'RASSYCODE' => $k['XASSY'], 'RPRICEGROUP' => $k['XPRICE'], 'RBM' => $thebm, 'PPH' => $thepph, 'PPN' => $theppn,
                                        ];
                                    }
                                }
                            }
                        }
                    } else {
                        $this->inventory_cancelDO($csj);
                        $myar[] = ["cd" => "0", "msg" => "Could not find exbc, please contact admin !"];
                        die('{"status":' . json_encode($myar) . '}');
                    }
                } else {
                    $this->inventory_cancelDO($csj);
                    $myar[] = ["cd" => "0", "msg" => "Could not find exbc, please contact admin", "api_respon" => $rstemp, "request" => $fields];
                    die('{"status":' . json_encode($myar) . '}');
                }
                log_message('error', $_SERVER['REMOTE_ADDR'] . ',step3#, finish, receive request');
            }
            $listNeedExBC = [];
            foreach ($requestResume as &$r) {
                foreach ($responseResume as &$n) {
                    if ($r['ITEM'] == $n['ITEM'] || $r['ITEMGR'] == $n['ITEM'] && $n['BALRES'] > 0) {
                        $balreq = $r['QTY'] - $r['QTYPLOT'];
                        if ($balreq > $n['BALRES']) {
                            $r['QTYPLOT'] += $n['BALRES'];
                            $n['BALRES'] = 0;
                        } else {
                            $n['BALRES'] -= $balreq;
                            $r['QTYPLOT'] += $balreq;
                        }
                        if ($r['QTY'] == $r['QTYPLOT']) {
                            break;
                        }
                    }
                }
                unset($n);
            }
            unset($r);
            foreach ($requestResume as $r) {
                if ($r['QTY'] != $r['QTYPLOT']) {
                    $listNeedExBC[] = ['ITMCD' => $r['ITEM'], 'QTY' => $r['QTY'], 'LOTNO' => '?'];
                }
            }

            if (count($listNeedExBC)) {
                $this->inventory_cancelDO($csj);
                $myar[] = ['cd' => 110, 'msg' => 'EX-BC for ' . count($listNeedExBC) . ' item(s) is not found. ', "doctype" => $czdocbctype];
                die('{"status" : ' . json_encode($myar)
                    . ', "data":' . json_encode($listNeedExBC)
                    . ',"rawdata":' . json_encode($tpb_bahan_baku)
                    . ',"req":' . json_encode($requestResume)
                    . ',"res":' . json_encode($responseResume) . '}');
            }
            #clear
            $ary_item = [];
            $ary_qty = [];
            $ary_lot = [];
            $requestResume = [];
            $responseResume = [];

            unset($ary_item);
            unset($ary_qty);
            unset($ary_lot);
            unset($requestResume);
            unset($responseResume);
            #end
        } catch (Exception $e) {
            $this->inventory_cancelDO($csj);
            $myar[] = ['cd' => 110, 'msg' => $e->getMessage()];
            die('{"status" : ' . json_encode($myar) . ',"data":"' . $rstemp . '"}');
        }
        foreach ($rsplotrm_per_fgprice as $r) {
            $nomor = 1;
            foreach ($tpb_bahan_baku as &$n) {
                if (
                    $r['RASSYCODE'] == $n['RASSYCODE']
                    && $r['RPRICEGROUP'] == $n['RPRICEGROUP']
                ) {
                    $n['SERI_BAHAN_BAKU'] = $nomor;
                    $nomor++;
                }
            }
            unset($n);
        }
        #N

        #BAHAN BAKU DOKUMEN
        $tpb_bahan_baku_tarif = [];
        foreach ($tpb_bahan_baku as $r) {
            if ($r['KODE_JENIS_DOK_ASAL'] == '40') {
                $tpb_bahan_baku_tarif[] = [
                    'JENIS_TARIF' => 'PPN', 'KODE_ASAL_BAHAN_BAKU' => $r['KODE_ASAL_BAHAN_BAKU'], 'KODE_TARIF' => 1, 'NILAI_BAYAR' => $r['CIF_RUPIAH'] * 10 / 100, 'NILAI_FASILITAS' => 0, 'KODE_FASILITAS' => 0, 'TARIF_FASILITAS' => 100, 'TARIF' => $r['PPN'], 'SERI_BAHAN_BAKU' => $r['SERI_BAHAN_BAKU'], 'RASSYCODE' => $r['RASSYCODE'], 'RPRICEGROUP' => $r['RPRICEGROUP'], 'RITEMCD' => $r['KODE_BARANG'],
                ];
            } else {
                $tpb_bahan_baku_tarif[] = [
                    'JENIS_TARIF' => 'BM', 'KODE_ASAL_BAHAN_BAKU' => $r['KODE_ASAL_BAHAN_BAKU'], 'KODE_TARIF' => 1, 'NILAI_BAYAR' => $r['CIF_RUPIAH'] * $r['RBM'] / 100, 'NILAI_FASILITAS' => 0, 'KODE_FASILITAS' => 0, 'TARIF_FASILITAS' => 100, 'TARIF' => $r['RBM'], 'SERI_BAHAN_BAKU' => $r['SERI_BAHAN_BAKU'], 'RASSYCODE' => $r['RASSYCODE'], 'RPRICEGROUP' => $r['RPRICEGROUP'], 'RITEMCD' => $r['KODE_BARANG'],
                ];
                $tpb_bahan_baku_tarif[] = [
                    'JENIS_TARIF' => 'PPN', 'KODE_ASAL_BAHAN_BAKU' => $r['KODE_ASAL_BAHAN_BAKU'], 'KODE_TARIF' => 1, 'NILAI_BAYAR' => $r['CIF_RUPIAH'] * 10 / 100, 'NILAI_FASILITAS' => 0, 'KODE_FASILITAS' => 0, 'TARIF_FASILITAS' => 100, 'TARIF' => $r['PPN'], 'SERI_BAHAN_BAKU' => $r['SERI_BAHAN_BAKU'], 'RASSYCODE' => $r['RASSYCODE'], 'RPRICEGROUP' => $r['RPRICEGROUP'], 'RITEMCD' => $r['KODE_BARANG'],
                ];
                $tpb_bahan_baku_tarif[] = [
                    'JENIS_TARIF' => 'PPH', 'KODE_ASAL_BAHAN_BAKU' => $r['KODE_ASAL_BAHAN_BAKU'], 'KODE_TARIF' => 1, 'NILAI_BAYAR' => $r['CIF_RUPIAH'] * 2.5, 'NILAI_FASILITAS' => 0, 'KODE_FASILITAS' => 0, 'TARIF_FASILITAS' => 100, 'TARIF' => $r['PPH'], 'SERI_BAHAN_BAKU' => $r['SERI_BAHAN_BAKU'], 'RASSYCODE' => $r['RASSYCODE'], 'RPRICEGROUP' => $r['RPRICEGROUP'], 'RITEMCD' => $r['KODE_BARANG'],
                ];
            }
        }
        #N

        $tpb_header = [
            "NOMOR_AJU" => $cnoaju, "KODE_KANTOR" => $czkantorasal,
            "KODE_JENIS_TPB" => $cz_KODE_JENIS_TPB,

            "KODE_ID_PENGUSAHA" => "1", "ID_PENGUSAHA" => $czidpengusaha, "NAMA_PENGUSAHA" => $cznmpengusaha,
            "ALAMAT_PENGUSAHA" => $czalamatpengusaha, "NOMOR_IJIN_TPB" => $czizinpengusaha,
            "KODE_JENIS_API_PENGUSAHA" => 2, "API_PENGUSAHA" => "101600449-B",

            "KODE_ID_PENERIMA_BARANG" => "1", "ID_PENERIMA_BARANG" => $czidpenerima,
            "NAMA_PENERIMA_BARANG" => $cznmpenerima, "ALAMAT_PENERIMA_BARANG" => $czalamatpenerima,
            "NDPBM" => $cz_h_NDPBM,

            "KODE_VALUTA" => "USD", "CIF" => $cz_h_totalCIF, "HARGA_PENYERAHAN" => $cz_h_HARGA_PENYERAHAN_FG,

            "KODE_CARA_ANGKUT" => $czKODE_CARA_ANGKUT,

            "BRUTO" => $cz_h_BRUTO, "NETTO" => $cz_h_NETTO, "JUMLAH_BARANG" => $cz_h_JUMLAH_BARANG,

            "KODE_LOKASI_BAYAR" => 1, "KODE_PEMBAYAR" => 1,

            "KOTA_TTD" => "CIKARANG", "TANGGAL_TTD" => $ccustdate, "NAMA_TTD" => $czPemberitahu, "JABATAN_TTD" => $czJabatan,

            "KODE_DOKUMEN_PABEAN" => $czdocbctype, "ID_MODUL" => $czidmodul_asli, "VERSI_MODUL" => null,

            "VOLUME" => 0, "KODE_STATUS" => '00',
        ];
        $tpb_kemasan = [];
        $tpb_kemasan[] = ["JUMLAH_KEMASAN" => $cz_JUMLAH_KEMASAN, "KODE_JENIS_KEMASAN" => "BX"];

        $tpb_dokumen = [];
        $tpb_dokumen[] = ["KODE_JENIS_DOKUMEN" => "380", "NOMOR_DOKUMEN" => $czinvoice, "TANGGAL_DOKUMEN" => $czinvoicedt, "TIPE_DOKUMEN" => "02", "SERI_DOKUMEN" => 1]; //invoice
        $tpb_dokumen[] = ["KODE_JENIS_DOKUMEN" => "217", "NOMOR_DOKUMEN" => $czinvoice, "TANGGAL_DOKUMEN" => $czinvoicedt, "TIPE_DOKUMEN" => "02", "SERI_DOKUMEN" => 2]; //packing list
        $tpb_dokumen[] = ["KODE_JENIS_DOKUMEN" => "640", "NOMOR_DOKUMEN" => $czsj, "TANGGAL_DOKUMEN" => $ccustdate, "TIPE_DOKUMEN" => "02", "SERI_DOKUMEN" => 3]; //surat jalan
        if ($czSKB != '') {
            $tpb_dokumen[] = ["KODE_JENIS_DOKUMEN" => "457", "NOMOR_DOKUMEN" => $czSKB, "TANGGAL_DOKUMEN" => $ccustdate, "TIPE_DOKUMEN" => "02", "SERI_DOKUMEN" => 4]; //surat keterangan bebas
        }

        log_message('error', $_SERVER['REMOTE_ADDR'] . ',step4#, start, INSERT TPB');
        #INSERT CEISA
        ##1 TPB HEADER
        $ZR_TPB_HEADER = $this->TPB_HEADER_imod->insert($tpb_header);
        ##N

        ##2 TPB DOKUMEN
        foreach ($tpb_dokumen as &$n) {
            $n['ID_HEADER'] = $ZR_TPB_HEADER;
        }
        unset($n);
        $this->TPB_DOKUMEN_imod->insertb($tpb_dokumen);
        ##N

        ##3 TPB KEMASAN
        foreach ($tpb_kemasan as &$n) {
            $n['ID_HEADER'] = $ZR_TPB_HEADER;
        }
        unset($n);
        $this->TPB_KEMASAN_imod->insertb($tpb_kemasan);
        ##N

        ##4 TPB BARANG
        foreach ($tpb_barang as &$n) {
            $n['ID_HEADER'] = $ZR_TPB_HEADER;
            foreach ($tpb_bahan_baku as $j) {
                if ($n['KODE_BARANG'] == $j['RASSYCODE'] && $n['CIF'] == $j['RPRICEGROUP']) {
                    if (!isset($n['JUMLAH_BAHAN_BAKU'])) {
                        $n['JUMLAH_BAHAN_BAKU'] = 1;
                    } else {
                        $n['JUMLAH_BAHAN_BAKU']++;
                    }
                }
            }
        }
        unset($n);

        ##N

        ##4 TPB BARANG & BAHAN BAKU
        $tpb_barang_tarif = [];
        foreach ($tpb_barang as $n) {
            $ZR_TPB_BARANG = $this->TPB_BARANG_imod->insert([
                'KODE_GUNA' => $n['KODE_GUNA']
                , 'KATEGORI_BARANG' => $n['KATEGORI_BARANG']
                , 'KONDISI_BARANG' => $n['KONDISI_BARANG']
                , 'KODE_BARANG' => $n['KODE_BARANG']
                , 'KODE_NEGARA_ASAL' => $n['KODE_NEGARA_ASAL']
                , 'POS_TARIF' => $n['POS_TARIF']
                , 'URAIAN' => $n['URAIAN']
                , 'JUMLAH_SATUAN' => $n['JUMLAH_SATUAN']
                , 'KODE_SATUAN' => $n['KODE_SATUAN']
                , 'NETTO' => $n['NETTO']
                , 'KODE_KEMASAN' => $n['KODE_KEMASAN']
                , 'CIF' => $n['CIF']
                , 'HARGA_PENYERAHAN' => $n['HARGA_PENYERAHAN']
                , 'SERI_BARANG' => $n['SERI_BARANG']
                , 'KODE_STATUS' => $n['KODE_STATUS']
                , 'KODE_PERHITUNGAN' => $n['KODE_PERHITUNGAN']
                , 'ID_HEADER' => $n['ID_HEADER']
                , 'JUMLAH_BAHAN_BAKU' => $n['JUMLAH_BAHAN_BAKU']
                , 'JUMLAH_KEMASAN' => $n['JUMLAH_KEMASAN']
            ]);
            $tpb_barang_tarif[] = [
                'JENIS_TARIF' => 'BM', 'KODE_FASILITAS' => 0, 'KODE_TARIF' => 1, 'NILAI_BAYAR' => 0, 'NILAI_FASILITAS' => 0, 'NILAI_SUDAH_DILUNASI' => 0, 'SERI_BARANG' => $n['SERI_BARANG'], 'TARIF' => $n['BM'], 'TARIF_FASILITAS' => 100, 'ID_BARANG' => $ZR_TPB_BARANG, 'ID_HEADER' => $ZR_TPB_HEADER,
            ];
            $tpb_barang_tarif[] = [
                'JENIS_TARIF' => 'PPN', 'KODE_FASILITAS' => 0, 'KODE_TARIF' => 1, 'NILAI_BAYAR' => 0, 'NILAI_FASILITAS' => 0, 'NILAI_SUDAH_DILUNASI' => 0, 'SERI_BARANG' => $n['SERI_BARANG'], 'TARIF' => $n['PPN'], 'TARIF_FASILITAS' => 100, 'ID_BARANG' => $ZR_TPB_BARANG, 'ID_HEADER' => $ZR_TPB_HEADER,
            ];
            $tpb_barang_tarif[] = [
                'JENIS_TARIF' => 'PPH', 'KODE_FASILITAS' => 0, 'KODE_TARIF' => 1, 'NILAI_BAYAR' => 0, 'NILAI_FASILITAS' => 0, 'NILAI_SUDAH_DILUNASI' => 0, 'SERI_BARANG' => $n['SERI_BARANG'], 'TARIF' => $n['PPH'], 'TARIF_FASILITAS' => 100, 'ID_BARANG' => $ZR_TPB_BARANG, 'ID_HEADER' => $ZR_TPB_HEADER,
            ];
            $this->TPB_BARANG_TARIF_imod->insertb($tpb_barang_tarif);
            foreach ($tpb_bahan_baku as $b) {
                if ($n['KODE_BARANG'] == $b['RASSYCODE'] && $n['CIF'] == $b['RPRICEGROUP']) {
                    $ZR_TPB_BAHAN_BAKU = $this->TPB_BAHAN_BAKU_imod
                        ->insert([
                            'KODE_JENIS_DOK_ASAL' => $b['KODE_JENIS_DOK_ASAL'], 'NOMOR_DAFTAR_DOK_ASAL' => $b['NOMOR_DAFTAR_DOK_ASAL'], 'TANGGAL_DAFTAR_DOK_ASAL' => $b['TANGGAL_DAFTAR_DOK_ASAL'], 'KODE_KANTOR' => $b['KODE_KANTOR'], 'NOMOR_AJU_DOK_ASAL' => $b['NOMOR_AJU_DOK_ASAL'], 'SERI_BARANG_DOK_ASAL' => $b['SERI_BARANG_DOK_ASAL'], 'CIF' => ($b['CIF']), 'CIF_RUPIAH' => ($b['CIF_RUPIAH']), 'HARGA_PENYERAHAN' => $b['HARGA_PENYERAHAN'], 'HARGA_PEROLEHAN' => $b['HARGA_PEROLEHAN'], 'KODE_BARANG' => $b['KODE_BARANG'], 'KODE_STATUS' => $b['KODE_STATUS'], 'POS_TARIF' => $b['POS_TARIF'], 'URAIAN' => $b['URAIAN'], 'TIPE' => $b['TIPE'], 'JUMLAH_SATUAN' => $b['JUMLAH_SATUAN'], 'JENIS_SATUAN' => $b['JENIS_SATUAN'], 'KODE_ASAL_BAHAN_BAKU' => $b['KODE_ASAL_BAHAN_BAKU'], 'NDPBM' => $b['NDPBM'], 'NETTO' => 0, 'SERI_BAHAN_BAKU' => $b['SERI_BAHAN_BAKU'], 'SERI_BARANG' => $n['SERI_BARANG'], 'ID_BARANG' => $ZR_TPB_BARANG, 'ID_HEADER' => $ZR_TPB_HEADER,
                        ]);

                    foreach ($tpb_bahan_baku_tarif as $t) {
                        if (
                            $b['RASSYCODE'] == $t['RASSYCODE'] && $b['RPRICEGROUP'] == $t['RPRICEGROUP']
                            && $b['KODE_BARANG'] == $t['RITEMCD'] && $b['SERI_BAHAN_BAKU'] == $t['SERI_BAHAN_BAKU']
                        ) {
                            $this->TPB_BAHAN_BAKU_TARIF_imod
                                ->insert([
                                    'JENIS_TARIF' => $t['JENIS_TARIF'], 'KODE_ASAL_BAHAN_BAKU' => $t['KODE_ASAL_BAHAN_BAKU'], 'KODE_TARIF' => $t['KODE_TARIF'], 'NILAI_BAYAR' => $t['NILAI_BAYAR'], 'NILAI_FASILITAS' => $t['NILAI_FASILITAS'], 'KODE_FASILITAS' => $t['KODE_FASILITAS'], 'TARIF_FASILITAS' => $t['TARIF_FASILITAS'], 'TARIF' => $t['TARIF'], 'SERI_BAHAN_BAKU' => $t['SERI_BAHAN_BAKU'], 'SERI_BARANG' => $n['SERI_BARANG'], 'ID_BAHAN_BAKU' => $ZR_TPB_BAHAN_BAKU, 'ID_BARANG' => $ZR_TPB_BARANG, 'ID_HEADER' => $ZR_TPB_HEADER,
                                ]);
                        }
                    }
                }
            }
        }

        $this->set_log_finish_posting($csj);

        ##N
        if ($consignee != 'IEI') {
            $this->sendto_delivery_checking($csj);
        }
        #N
        log_message('error', $_SERVER['REMOTE_ADDR'] . ',step4#, finish, INSERT TPB ');
        $this->DELV_mod->updatebyVAR(['DLV_POST' => $this->session->userdata('nama'), 'DLV_POSTTM' => $currentDate], ['DLV_ID' => $csj]);
        $myar[] = ['cd' => 1, 'msg' => 'Done, check your TPB', 'tpb_barang' => $tpb_barang, 'tpb_bahan_baku' => $tpb_bahan_baku];
        $this->setPrice(base64_encode($csj));
        die('{"status" : ' . json_encode($myar) . '}');
    }

    public function control_multibom()
    {
        header('Content-Type: application/json');
        $DOno = $this->input->get('insj');
        $rs = $this->DELV_mod->select_dlv_ser_rm($DOno);
        $rsnull = $this->DELV_mod->select_dlv_ser_rm_null($DOno);
        $comb_cols = ['SERC_COMID'];
        $serlist = [];
        $rs_reconditional = [];
        foreach ($rsnull as $r) {
            $rscomb_d = $this->SERC_mod->select_cols_where_id($comb_cols, $r['DLV_SER']);
            $serlist = [];
            foreach ($rscomb_d as $n) {
                $serlist[] = $n['SERC_COMID'];
            }
            $rscom = $this->DELV_mod->select_dlv_ser_rm_byreff($serlist);
            foreach ($rscom as $f) {
                $rs_reconditional[] = $f;
            }
        }
        die('{"olddata": ' . json_encode($rs) . ',"newdata":' . json_encode($rs_reconditional) . '}');
    }

    public function searchv()
    {
        header('Content-Type: application/json');
        ini_set('max_execution_time', '-1');
        $csearch = $this->input->get('insearch');
        $cispermonth = $this->input->get('inispermonth');
        $cmonth = $this->input->get('inmonth');
        $cyear = $this->input->get('inyear');
        $cdate = $this->input->get('indate');
        $rs = [];
        if (strlen($cdate) == 10) {
            $rs = $this->DELV_mod->select_searchv_bydate($csearch, $cdate);
        } else {
            if ($cispermonth == 'y') {
                $rs = $this->DELV_mod->select_searchv_bymonth($csearch, $cmonth, $cyear);
            } else {
                $rs = $this->DELV_mod->select_searchv($csearch);
            }
        }
        foreach ($rs as &$m) {
            $m['STATUS'] = $this->getjobstatus_resume($m['DLV_ID']);
        }
        unset($m);
        die('{"data": ' . json_encode($rs) . '}');
    }

    public function perprice($psj, $prs)
    {
        $sj = $psj;
        $rsprice = $prs;
        $rsrm = $this->DELV_mod->select_dlv_ser_rm_only($sj);
        $rssub = $this->DELV_mod->select_dlv_ser_sub_only($sj);
        $rsnull = $this->DELV_mod->select_dlv_ser_rm_null_v1($sj);

        foreach ($rsnull as $r) {
            $rscomb_d = $this->SERC_mod->select_cols_where_id(['SERC_COMID'], $r['DLV_SER']);
            $serlist = [];
            if (count($rscomb_d)) {
                foreach ($rscomb_d as $n) {
                    $serlist[] = $n['SERC_COMID'];
                }

                if (count($serlist) > 0) {
                    $rscom = $this->DELV_mod->select_dlv_ser_rm_byreff_forpost($serlist);
                    $rsrm = array_merge($rsrm, $rscom);
                }
            } else {
                $rscomb_d = $this->SERC_mod->select_cols_where_id(['SERC_COMID'], $r['SER_REFNO']);
                foreach ($rscomb_d as $n) {
                    $serlist[] = $n['SERC_COMID'];
                }
                if (count($serlist) > 0) {
                    $rscom = $this->DELV_mod->select_dlv_ser_rm_byreff_forpost($serlist);
                    $rsrm = array_merge($rsrm, $rscom);
                }
            }
        }
        $rsrm = array_merge($rsrm, $rssub);
        $result = [];
        foreach ($rsprice as &$r) {
            foreach ($rsrm as &$ra) {
                if ($r['SSO2_MDLCD'] == $ra['SER_ITMID']) {
                    if (intval($ra['SERD2_FGQTY']) > 0) {
                        $thereffno = $ra['SERD2_SER'];
                        $osreq = $r['SISOQTY'] - $r['SISOQTY_X'];
                        $plot = 0;
                        if ($osreq > 0) {
                            foreach ($rsrm as &$x) {
                                if ($thereffno == $x['SERD2_SER']) {
                                    if ($osreq > $x['SERD2_FGQTY']) {
                                        $plot = $x['SERD2_FGQTY'];
                                        $x['SERD2_FGQTY'] = 0;
                                    } else {
                                        $plot = $osreq;
                                        $x['SERD2_FGQTY'] -= $osreq;
                                    }
                                    $x['PRICEFOR'] = $r['SSO2_SLPRC'];
                                    $x['QTYFOR'] = $plot;
                                    $x['PRICEGROUP'] = $r['SSO2_SLPRC'] * $r['SISOQTY'];
                                    $result[] = $x;
                                }
                            }
                            unset($x);
                        }
                        $r['SISOQTY_X'] += $plot;
                    }
                }
            }
            unset($ra);
        }
        unset($r);

        $result_resume = [];
        foreach ($result as $r) {
            $isfound = false;
            foreach ($result_resume as &$v) {
                if (
                    $v['RITEMCD'] == $r['SERD2_ITMCD'] && $v['RLOTNO'] == $r['LOTNO']
                    && $v['RASSYCODE'] == $r['SER_ITMID'] && $v['RPRICEGROUP'] == round($r['PRICEGROUP'], 2)
                ) {
                    $v['RQTY'] += ($r['SERD2_QTPER'] * $r['QTYFOR']);
                    $isfound = true;
                    break;
                }
            }
            if (!$isfound) {
                $result_resume[] = [
                    'RASSYCODE' => $r['SER_ITMID'], 'RPRICEGROUP' => round($r['PRICEGROUP'], 2), 'RITEMCD' => $r['SERD2_ITMCD'], 'RITEMCDGR' => $r['ITMGR'], 'RLOTNO' => $r['LOTNO'], 'RQTY' => $r['SERD2_QTPER'] * $r['QTYFOR'],
                ];
            }
            unset($v);
        }
        return $result_resume;
    }

    public function getadditional_infobc($bcno, $itemcd)
    {
        $rs = $this->RCV_mod->select_cols_where(
            ['RCV_KPPBC', 'RCV_ZNOURUT', 'RCV_HSCD', 'RTRIM(MITM_ITMD1) MITM_ITMD1', 'RTRIM(MITM_STKUOM) MITM_STKUOM', 'RCV_PRPRC', 'isnull(RCV_BM,0) RCV_BM'],
            ['RCV_RPNO' => $bcno, 'RCV_ITMCD' => $itemcd]
        );
        if (count($rs) > 0) {
            foreach ($rs as $r) {
                return $r;
            }
        } else {
            return (object) ['RCV_KPPBC' => '-'];
        }
    }

    public function get_info_pendaftaran()
    {
        header('Content-Type: application/json');
        $cinsj = $this->input->get('insj');
        $rs_head_dlv = $this->DELV_mod->select_header_bydo($cinsj);
        $czkantorasal = '-';
        $myar = [];
        $result_data = [];
        $response_data = [];
        foreach ($rs_head_dlv as $r) {
            $czkantorasal = $r['DLV_FROMOFFICE'];
            $nomorajufull = $r['DLV_ZNOMOR_AJU'];
        }
        if ($czkantorasal == '-') {
            $myar[] = ['cd' => 0, 'msg' => 'Please set KANTOR ASAL first !'];
        } else {
            $result_data = $this->TPB_HEADER_imod->select_where(
                ["TANGGAL_DAFTAR", "coalesce(NOMOR_DAFTAR,0) NOMOR_DAFTAR"],
                ['NOMOR_AJU' => $nomorajufull]
            );
            $response_data = $this->TPB_RESPON_imod->select_where(
                ["NOMOR_RESPON"],
                ['NOMOR_AJU' => $nomorajufull, 'NOMOR_RESPON !=' => '']
            );
            if (count($result_data) > 0) {
                $myar[] = ['cd' => 1, 'msg' => 'go ahead'];
            } else {
                $myar[] = ['cd' => 0, 'msg' => 'NOMOR AJU is not found in ceisa local data', 'aju' => $nomorajufull];
            }
        }
        die(json_encode(['status' => $myar, 'data' => $result_data, 'data2' => $response_data]));
    }

    public function getjobstatus()
    {
        header('Content-Type: application/json');
        $cdo = $this->input->get('indo');
        $rs = $this->DELV_mod->select_job($cdo);
        foreach ($rs as &$r) {
            $rspsn = $this->SPL_mod->select_z_getpsn_byjob("'" . $r['SER_DOC'] . "'");
            $strdocno = '';
            foreach ($rspsn as $k) {
                $strdocno = trim($k['PPSN1_DOCNO']);
            }
            if (trim($strdocno) != '') {
                #1. requirement
                $rsjobper_req = $this->SPL_mod->select_jobper_req($strdocno, $r['SER_DOC']);
                $ttlqtper_req = 0;
                foreach ($rsjobper_req as $n) {
                    $ttlqtper_req = $n['MYPER'];
                }

                #2. calculated by wms
                $rsjobper_wmscal = $this->SPL_mod->select_jobper_wmscal($r['SER_DOC'], $cdo);
                $ttlqtper_wms = 0;
                $calculatedser = '';
                $calculatedserqty = '';
                $repairstatus = '-';
                foreach ($rsjobper_wmscal as $n) {
                    $ttlqtper_wms = $n['SERD2_QTPER'];
                    $calculatedser = $n['SERD2_SER'];
                    $calculatedserqty = $n['SERD2_FGQTY'];
                    $repairstatus = $n['SER_RMUSE_COMFG'];
                }
                if ($ttlqtper_req == $ttlqtper_wms) {
                    $r['STATUS'] = 'OK';
                } else {
                    if ($ttlqtper_req > $ttlqtper_wms) {
                        if ($repairstatus == '-') {
                            $r['STATUS'] = 'Editing is required';
                        } else {
                            $r['STATUS'] = 'OK.';
                        }
                        $r['REQUIREMENT'] = $ttlqtper_req;
                        $r['CALCULATED'] = $ttlqtper_wms;
                        $r['SER'] = $calculatedser;
                        $r['SERQTY'] = $calculatedserqty;
                    } else {
                        $r['STATUS'] = 'OK'; #Checking is required
                        $r['REQUIREMENT'] = $ttlqtper_req;
                        $r['CALCULATED'] = $ttlqtper_wms;
                        $r['SER'] = $calculatedser;
                        $r['SERQTY'] = $calculatedserqty;
                    }
                }
            } else {
                $r['STATUS'] = 'PSN NOT FOUND';
            }
        }
        unset($r);
        die('{"data": ' . json_encode($rs) . '}');
    }

    public function getjobstatus_resume($cdo)
    {
        $rs = $this->DELV_mod->select_job($cdo);
        $ttlok = 0;
        foreach ($rs as &$r) {
            $rspsn = $this->SPL_mod->select_z_getpsn_byjob("'" . $r['SER_DOC'] . "'");
            $strdocno = '';
            foreach ($rspsn as $k) {
                $strdocno = trim($k['PPSN1_DOCNO']);
            }
            if (trim($strdocno) != '') {
                #1. requirement
                $rsjobper_req = $this->SPL_mod->select_jobper_req($strdocno, $r['SER_DOC']);
                $ttlqtper_req = 0;
                foreach ($rsjobper_req as $n) {
                    $ttlqtper_req = $n['MYPER'];
                }

                #2. calculated by wms
                $rsjobper_wmscal = $this->SPL_mod->select_jobper_wmscal($r['SER_DOC'], $cdo);
                $ttlqtper_wms = 0;
                $repairstatus = '-';
                foreach ($rsjobper_wmscal as $n) {
                    $ttlqtper_wms = $n['SERD2_QTPER'];
                    $repairstatus = $n['SER_RMUSE_COMFG'];
                }
                if ($ttlqtper_req == $ttlqtper_wms) {
                    $ttlok++;
                } else {
                    if ($ttlqtper_req > $ttlqtper_wms && $repairstatus != '-') {
                        $ttlok++;
                    }
                }
            } else {
                $r['STATUS'] = 'PSN NOT FOUND';
            }
        }
        unset($r);
        $ttlrows = count($rs);
        $toret = $ttlok == $ttlrows ? "ALL OK" : "Checking is required";
        return $toret;
    }

    public function getjobstatus_compare()
    {
        header('Content-Type: application/json');
        $cdo = $this->input->get('indo');
        $cjob = $this->input->get('injob');
        $rspsn = $this->SPL_mod->select_z_getpsn_byjob("'" . $cjob . "'");
        $strdocno = '';
        $rsreq = [];
        $findpsn = [];
        $rspsndetail = [];
        $strmdlcd = '';
        foreach ($rspsn as $k) {
            $strdocno = trim($k['PPSN1_DOCNO']);
            $findpsn[] = $k['PPSN1_PSNNO'];
            $strmdlcd = $k['PPSN1_MDLCD'];
        }
        if (trim($strdocno) != '') {
            #1. requirement
            $rsreq = $this->SPL_mod->select_compare_psnjob_req($strdocno, $cjob);
            $rspsndetail = $this->SPL_mod->select_allxppsn2_bypsn($findpsn);
            $rsjobper_wmscal = $this->SPL_mod->select_job_wmscal($cjob, $cdo);
            $rsMSPP = $this->MSPP_mod->select_byvar(['MSPP_MDLCD' => $strmdlcd]);
        }
        $docno = ['docno' => $strdocno];
        die('{
            "datareq": ' . json_encode($rsreq) . '
            , "datapsn": ' . json_encode($rspsndetail) . '
            , "datacal": ' . json_encode($rsjobper_wmscal) . '
            , "datadoc": ' . json_encode($docno) . '
            , "datamsp": ' . json_encode($rsMSPP) . '
        }');
    }
    public function getjobstatus_compare_byser()
    {
        header('Content-Type: application/json');
        $cid = $this->input->get('inid');
        $cjob = $this->input->get('injob');
        $rspsn = $this->SPL_mod->select_z_getpsn_byjob("'" . $cjob . "'");
        $strdocno = '';
        $rsreq = [];
        $findpsn = [];
        $rspsndetail = [];
        $rspsndetail_ops = [];
        $strmdlcd = '';
        foreach ($rspsn as $k) {
            $strdocno = trim($k['PPSN1_DOCNO']);
            $findpsn[] = $k['PPSN1_PSNNO'];
            $strmdlcd = $k['PPSN1_MDLCD'];
        }
        if (trim($strdocno) != '') {
            #1. requirement
            $rsreq = $this->SPL_mod->select_compare_psnjob_req($strdocno, $cjob);
            $rspsndetail = $this->SPL_mod->select_allxppsn2_bypsn($findpsn);
            $rspsndetail_ops = $this->SPL_mod->select_allxppsn2_by_ops_psn($findpsn);
            $rsjobper_wmscal = $this->SPL_mod->select_job_wmscalculation_byser($cid);
            $rsMSPP = $this->MSPP_mod->select_byvar(['MSPP_MDLCD' => $strmdlcd]);
        }
        $rspsn_united = array_merge($rspsndetail, $rspsndetail_ops);
        $docno = ['docno' => $strdocno];
        die('{
            "datareq": ' . json_encode($rsreq) . '
            , "datapsn": ' . json_encode($rspsn_united) . '
            , "datacal": ' . json_encode($rsjobper_wmscal) . '
            , "datadoc": ' . json_encode($docno) . '
            , "datamsp": ' . json_encode($rsMSPP) . '
        }');
    }

    public function setSO()
    {
        header('Content-Type: application/json');
        $cdo = $this->input->get('indo');
        $cso = $this->input->get('inso');
        $myar = [];
        $rs = [];
        if ($this->DLVSO_mod->check_Primary(['DLVSO_DLVID' => $cdo]) == 0) {
            $rs = $this->SISCN_mod->selectso_bydo($cdo);
            $rstosave = [];
            foreach ($rs as &$k) {
                if ($k['SISO_SOLINE'] == 'X') {
                    $rs_mst_price = $this->XSO_mod->select_latestprice($k['SI_BSGRP'], $k['SI_CUSCD'], "'" . $k['SI_ITMCD'] . "'");
                    foreach ($rs_mst_price as $r) {
                        $k['SSO2_SLPRC'] = substr($r['MSPR_SLPRC'], 0, 1) == '.' ? '0' . $r['MSPR_SLPRC'] : $r['MSPR_SLPRC'];
                    }
                }
                $rstosave[] = [
                    'DLVSO_DLVID' => $cdo,
                    'DLVSO_ITMCD' => $k['SI_ITMCD'],
                    'DLVSO_QTY' => intval($k['SCNQTY']),
                    'DLVSO_CPONO' => $cso,
                    'DLVSO_PRICE' => $k['SSO2_SLPRC'],
                ];
            }
            unset($k);
            if (count($rstosave) > 0) {
                $this->DLVSO_mod->insertb($rstosave);
                $myar[] = ['cd' => 1, 'msg' => 'Saved Successfully'];
            }
        } else {
            $myar[] = ['cd' => 1, 'msg' => 'Saved Successfully.'];
        }

        die('{"status": ' . json_encode($myar)
            . ',"data": ' . json_encode($rs)
            . ',"so": "' . $cso . '"}');
    }

    public function get_confirmed_so()
    {
        header('Content-Type: application/json');
        $cdo = $this->input->get('indo');
        $rs = $this->DLVSO_mod->select_SO(['DLVSO_DLVID' => $cdo]);
        die('{"data": ' . json_encode($rs) . '}');
    }

    public function getunconfirmed()
    {
        header('Content-Type: application/json');
        $rs = $this->DELV_mod->select_delivery_unconfirmed();
        $rs_rm = $this->DELV_mod->select_delivery_rm_unconfirmed();
        $rs = array_merge($rs, $rs_rm);
        die('{"data": ' . json_encode($rs) . '}');
    }

    public function confirm_delivery()
    {
        $this->checkSession();
        ini_set('max_execution_time', '-1');
        header('Content-Type: application/json');
        $ITHLUPDT = date('Y-m-d H:i:s');
        $ITHDATE = date('Y-m-d');
        $cdo = $this->input->get('indo');
        $rsser_si = $this->DELV_mod->select_det_byid($cdo);
        $resith = 0;
        foreach ($rsser_si as $r) {
            if ($this->ITH_mod->check_Primary(["ITH_SER" => $r['DLV_SER'], "ITH_FORM" => "OUT-SHP-FG"]) == 0) {
                $thewh = '';
                if ($r['SI_WH'] == "AFWH3") {
                    $thewh = "ARSHP";
                } elseif ($r['SI_WH'] == "AFWH3RT") {
                    $thewh = "ARSHPRTN2";
                } else {
                    $thewh = "ARSHPRTN";
                }
                $datam = [
                    "ITH_ITMCD" => trim($r['SER_ITMID']),
                    "ITH_DATE" => $ITHDATE,
                    "ITH_FORM" => "OUT-SHP-FG",
                    "ITH_DOC" => $cdo,
                    "ITH_QTY" => -$r['SISCN_SERQTY'],
                    "ITH_WH" => $thewh,
                    "ITH_SER" => $r['DLV_SER'],
                    "ITH_LUPDT" => $ITHLUPDT,
                    "ITH_USRID" => $this->session->userdata('nama'),
                ];
                $resith += $this->ITH_mod->insert($datam);
            }
        }

        if ($resith > 0) {
            $current_date = date('Y-m-d');
            $current_time = date('H:i:s');
            $fixdate = $current_date;
            if ($current_time < '07:00:00') {
                $fixdate = strtotime($current_date . '-1 days');
                $fixdate = date('Y-m-d', $fixdate);
            }
            $this->DELV_mod->updatebyVAR(['DLV_DATE' => $fixdate], ['DLV_ID' => $cdo]);
            $this->setPrice(base64_encode($cdo));
            $rsstatus_ith[] = ["cd" => "1", "msg" => "Confirmed", "time" => $ITHLUPDT];
        } else {
            $resith = $this->NonReffnumberDeliveryConfirmation(['DOC' => $cdo, 'DATE' => $ITHDATE, 'DATETIME' => $ITHLUPDT]);
            if ($resith > 0) {
                $current_date = date('Y-m-d');
                $current_time = date('H:i:s');
                $fixdate = $current_date;
                if ($current_time < '07:00:00') {
                    $fixdate = strtotime($current_date . '-1 days');
                    $fixdate = date('Y-m-d', $fixdate);
                }
                $this->DELV_mod->updatebyVAR(['DLV_DATE' => $fixdate], ['DLV_ID' => $cdo]);
                $rsstatus_ith[] = ["cd" => "1", "msg" => "Confirmed", "time" => $ITHLUPDT];
            } else {
                $rsstatus_ith[] = ["cd" => "0", "msg" => "It is weird, nothing saved"];
            }
        }
        die('{"status": ' . json_encode($rsstatus_ith) . '}');
    }

    public function setPrice_BAK($pdoc = '')
    {
        header('Content-Type: application/json');
        $doc = base64_decode($pdoc);
        $this->DLVPRC_mod->deleteby_filter(['DLVPRC_TXID' => $doc]);
        $createdAT = date('Y-m-d H:i:s');
        $rs = $this->SISCN_mod->select_forsetPrice($doc);
        $rsPriceItem = [];
        $rsPriceItemSer = [];
        foreach ($rs as &$k) {
            $k['PLOTPRCQTY'] = 0;
            if ($k['SISO_SOLINE'] == 'X') {
                $rs_mst_price = $this->XSO_mod->select_latestprice($k['SI_BSGRP'], $k['SI_CUSCD'], "'" . $k['SI_ITMCD'] . "'");
                foreach ($rs_mst_price as $r) {
                    $k['SSO2_SLPRC'] = substr($r['MSPR_SLPRC'], 0, 1) == '.' ? '0' . $r['MSPR_SLPRC'] : $r['MSPR_SLPRC'];
                }
            }

            $isfound = false;
            foreach ($rsPriceItem as $p) {
                if ($p['SILINE'] === $k['SISCN_LINENO'] && $p['PRC'] === $k['SSO2_SLPRC']) {
                    $isfound = true;
                    break;
                }
            }
            if (!$isfound) {
                $rsPriceItem[] = ['SILINE' => $k['SISCN_LINENO'], 'PRC' => $k['SSO2_SLPRC'], 'QTY' => $k['PLOTQTY']];
            }
        }
        unset($k);
        $line = 1;

        foreach ($rs as &$k) {
            $isfound = false;
            foreach ($rsPriceItem as &$s) {
                $bal = $k['SCNQTY'] - $k['PLOTPRCQTY'];
                if ($k['SISCN_LINENO'] === $s['SILINE'] && $bal > 0 && $s['QTY'] > 0) {
                    if ($bal > $s['QTY']) {
                        $k['PLOTPRCQTY'] += $s['QTY'];
                        $rsPriceItemSer[] = [
                            'DLVPRC_TXID' => $doc, 'DLVPRC_SILINE' => $k['SISCN_LINENO'], 'DLVPRC_SER' => $k['SISCN_SER'], 'DLVPRC_QTY' => $s['QTY'], 'DLVPRC_PRC' => $s['PRC'], 'DLVPRC_LINENO' => $line++, 'DLVPRC_CPO' => $k['CPO'], 'DLVPRC_CREATEDAT' => $createdAT, 'DLVPRC_CREATEDBY' => $this->session->userdata('nama'),
                        ];
                        $isready = true;
                        $s['QTY'] = 0;
                    } else {
                        $s['QTY'] -= $bal;
                        $k['PLOTPRCQTY'] += $bal;
                        $rsPriceItemSer[] = [
                            'DLVPRC_TXID' => $doc, 'DLVPRC_SILINE' => $k['SISCN_LINENO'], 'DLVPRC_SER' => $k['SISCN_SER'], 'DLVPRC_QTY' => $bal, 'DLVPRC_PRC' => $s['PRC'], 'DLVPRC_LINENO' => $line++, 'DLVPRC_CPO' => $k['CPO'], 'DLVPRC_CREATEDAT' => $createdAT, 'DLVPRC_CREATEDBY' => $this->session->userdata('nama'),
                        ];
                        $isready = true;
                    }
                    if ($k['SCNQTY'] === $k['PLOTPRCQTY']) {
                        break;
                    }
                }
            }
            unset($s);
        }
        unset($k);
        if ($isready) {
            $this->DLVPRC_mod->insertb($rsPriceItemSer);
        }
    }

    public function setPriceRS($pdoc = '')
    {
        $doc = base64_decode($pdoc);
        $rs = $this->DELV_mod->select_item_per_price_V2($doc);
        $rscurrentPrice_plot = $this->SISO_mod->select_currentPlot($doc);
        $rsPriceItemSer = [];
        $bsgrp = '';
        $cuscd = '';
        foreach ($rs as &$k) {
            $k['PLOTPRCQTY'] = 0;
            $bsgrp = $k['SI_BSGRP'];
            $cuscd = $k['SI_CUSCD'];
        }
        unset($k);
        $rsitem_iprice = []; //resume item|price|count
        foreach ($rscurrentPrice_plot as &$k) {
            if ($k['SISO_SOLINE'] == 'X') {
                $rs_mst_price = $this->XSO_mod->select_latestprice($bsgrp, $cuscd, "'" . $k['ITMID'] . "'");
                foreach ($rs_mst_price as $r) {
                    $k['SSO2_SLPRC'] = substr($r['MSPR_SLPRC'], 0, 1) == '.' ? '0' . $r['MSPR_SLPRC'] : $r['MSPR_SLPRC'];
                    $k['PLOTQTY'] = $k['SCNQT'];
                }
            } else {
                if ($k['PLOTQTY'] < $k['SCNQT']) {
                    $isfound = false;
                    foreach ($rsitem_iprice as &$i) {
                        if ($k['ITMID'] == $i['ITMID'] && $k['SSO2_SLPRC'] == $i['PRICE']) {
                            $i['COUNTER']++;
                            $isfound = true;
                        }
                    }
                    unset($i);
                    if (!$isfound) {
                        $rsitem_iprice[] = [
                            'ITMID' => $k['ITMID'], 'PRICE' => $k['SSO2_SLPRC'], 'COUNTER' => 1,
                        ];
                    }
                }
            }
        }
        unset($k);

        //1.0 filter which item use >1 price
        $rsitem_iprice_unique = [];
        foreach ($rsitem_iprice as $i) {
            $isfound = false;
            foreach ($rsitem_iprice_unique as &$u) {
                if ($i['ITMID'] == $u['ITMID']) {
                    $u['COUNTER']++;
                    $isfound = true;
                }
            }
            unset($u);
            if (!$isfound) {
                $rsitem_iprice_unique[] = [
                    'ITMID' => $i['ITMID'], 'COUNTER' => 1,
                ];
            }
        }
        //1.1 if it has multiprice then do not autocomplete plot
        foreach ($rscurrentPrice_plot as &$k) {
            if ($k['PLOTQTY'] < $k['SCNQT']) {
                $isfound = false;
                foreach ($rsitem_iprice_unique as $n) {
                    if ($k['ITMID'] === $n['ITMID']) {
                        if ($n['COUNTER'] > 1) {
                            $isfound = true;
                            break;
                        }
                    }
                }
                if (!$isfound) {
                    if ($k['SISO_SOLINE'] == 'X' || $k['SSO2_BSGRP'] == 'PSI1PPZIEP') {
                        $k['PLOTQTY'] = $k['SCNQT'];
                    }
                }
            }
        }
        unset($k);
        foreach ($rs as &$k) {
            foreach ($rscurrentPrice_plot as &$s) {
                $bal = $k['SISOQTY'] - $k['SISOQTY_X'];
                if ($k['SSO2_MDLCD'] === $s['ITMID'] && $bal > 0 && $s['PLOTQTY'] > 0) {
                    $qtyuse = 0;
                    if ($bal > $s['PLOTQTY']) {
                        $qtyuse = $s['PLOTQTY'];
                        $k['SISOQTY_X'] += $s['PLOTQTY'];
                        $s['PLOTQTY'] = 0;
                    } else {
                        $qtyuse = $bal;
                        $s['PLOTQTY'] -= $bal;
                        $k['SISOQTY_X'] += $bal;
                    }
                    $isfound = false;
                    foreach ($rsPriceItemSer as &$b) {
                        if ($b['SSO2_MDLCD'] == $s['ITMID'] && $b['SSO2_SLPRC'] == $s['SSO2_SLPRC']) {
                            $b['SISOQTY'] += $qtyuse;
                            $b['CIF'] = $b['SISOQTY'] * $s['SSO2_SLPRC'];
                            $isfound = true;
                            break;
                        }
                    }
                    unset($b);
                    if (!$isfound) {
                        $rsPriceItemSer[] = [
                            'DLV_ID' => $doc
                            , 'SISOQTY' => $qtyuse
                            , 'SSO2_SLPRC' => $s['SSO2_SLPRC']
                            , 'SSO2_MDLCD' => $s['ITMID']#
                            , 'CIF' => $qtyuse * $s['SSO2_SLPRC']
                            , 'NWG' => $k['NWG']
                            , 'MITM_HSCD' => $k['MITM_HSCD']
                            , 'MITM_STKUOM' => $k['MITM_STKUOM']
                            , 'SISOQTY_X' => 0
                            , 'MITM_ITMD1' => $k['MITM_ITMD1']
                            , 'SISO_SOLINE' => $s['SISO_SOLINE']
                            , 'SI_BSGRP' => $k['SI_BSGRP']
                            , 'SI_CUSCD' => $k['SI_CUSCD']
                            , 'CPO' => $s['SISO_CPONO']
                            , 'BM' => $k['MITM_BM']
                            , 'PPN' => $k['MITM_PPN']
                            , 'PPH' => $k['MITM_PPH'],
                        ];
                    }
                    if ($k['SISOQTY'] === $k['SISOQTY_X']) {
                        break;
                    }
                }
            }
            unset($s);
        }
        unset($k);
        return $rsPriceItemSer;
    }

    public function setPriceRS_PERSO($pdoc = '')
    {
        $doc = base64_decode($pdoc);
        $rs = $this->DELV_mod->select_item_per_price_V2($doc);
        $rscurrentPrice_plot = $this->SISO_mod->select_currentPlot($doc);
        $rsPriceItemSer = [];
        $bsgrp = '';
        $cuscd = '';
        foreach ($rs as &$k) {
            $k['PLOTPRCQTY'] = 0;
            $bsgrp = $k['SI_BSGRP'];
            $cuscd = $k['SI_CUSCD'];
        }
        unset($k);
        $rsitem_iprice = []; //resume item|price|count
        foreach ($rscurrentPrice_plot as &$k) {
            if ($k['SISO_SOLINE'] == 'X') {
                $rs_mst_price = $this->XSO_mod->select_latestprice($bsgrp, $cuscd, "'" . $k['ITMID'] . "'");
                foreach ($rs_mst_price as $r) {
                    $k['SSO2_SLPRC'] = substr($r['MSPR_SLPRC'], 0, 1) == '.' ? '0' . $r['MSPR_SLPRC'] : $r['MSPR_SLPRC'];
                    $k['PLOTQTY'] = $k['SCNQT'];
                }
            } else {
                if ($k['PLOTQTY'] < $k['SCNQT']) {
                    $isfound = false;
                    foreach ($rsitem_iprice as &$i) {
                        if ($k['ITMID'] == $i['ITMID'] && $k['SSO2_SLPRC'] == $i['PRICE']) {
                            $i['COUNTER']++;
                            $isfound = true;
                        }
                    }
                    unset($i);
                    if (!$isfound) {
                        $rsitem_iprice[] = [
                            'ITMID' => $k['ITMID'], 'PRICE' => $k['SSO2_SLPRC'], 'COUNTER' => 1,
                        ];
                    }
                }
            }
        }
        unset($k);

        //1.0 filter which item use >1 price
        $rsitem_iprice_unique = [];
        foreach ($rsitem_iprice as $i) {
            $isfound = false;
            foreach ($rsitem_iprice_unique as &$u) {
                if ($i['ITMID'] == $u['ITMID']) {
                    $u['COUNTER']++;
                    $isfound = true;
                }
            }
            unset($u);
            if (!$isfound) {
                $rsitem_iprice_unique[] = [
                    'ITMID' => $i['ITMID'], 'COUNTER' => 1,
                ];
            }
        }
        //1.1 if it has multiprice then do not autocomplete plot
        foreach ($rscurrentPrice_plot as &$k) {
            if ($k['PLOTQTY'] < $k['SCNQT']) {
                $isfound = false;
                foreach ($rsitem_iprice_unique as $n) {
                    if ($k['ITMID'] === $n['ITMID']) {
                        if ($n['COUNTER'] > 1) {
                            $isfound = true;
                            break;
                        }
                    }
                }
                if (!$isfound) {
                    if ($k['SISO_SOLINE'] == 'X' || $k['SSO2_BSGRP'] == 'PSI1PPZIEP') {
                        $k['PLOTQTY'] = $k['SCNQT'];
                    }
                }
            }
        }
        unset($k);
        foreach ($rs as &$k) {
            foreach ($rscurrentPrice_plot as &$s) {
                $bal = $k['SISOQTY'] - $k['SISOQTY_X'];
                if ($k['SSO2_MDLCD'] === $s['ITMID'] && $bal > 0 && $s['PLOTQTY'] > 0) {
                    $qtyuse = 0;
                    if ($bal > $s['PLOTQTY']) {
                        $qtyuse = $s['PLOTQTY'];
                        $k['SISOQTY_X'] += $s['PLOTQTY'];
                        $s['PLOTQTY'] = 0;
                    } else {
                        $qtyuse = $bal;
                        $s['PLOTQTY'] -= $bal;
                        $k['SISOQTY_X'] += $bal;
                    }
                    $isfound = false;
                    foreach ($rsPriceItemSer as &$b) {
                        if ($b['SSO2_MDLCD'] == $s['ITMID'] && $b['SSO2_SLPRC'] == $s['SSO2_SLPRC'] && $b['CPO'] == $s['SISO_CPONO']) {
                            $b['SISOQTY'] += $qtyuse;
                            $b['CIF'] += $b['SISOQTY'] * $s['SSO2_SLPRC'];
                            $isfound = true;
                            break;
                        }
                    }
                    unset($b);
                    if (!$isfound) {
                        $rsPriceItemSer[] = [
                            'DLV_ID' => $doc, 'SISOQTY' => $qtyuse, 'SSO2_SLPRC' => $s['SSO2_SLPRC'], 'SSO2_MDLCD' => $s['ITMID']#
                            , 'CIF' => $qtyuse * $s['SSO2_SLPRC'], 'NWG' => $k['NWG'], 'MITM_HSCD' => $k['MITM_HSCD'], 'MITM_STKUOM' => $k['MITM_STKUOM'], 'SISOQTY_X' => 0, 'MITM_ITMD1' => $k['MITM_ITMD1'], 'CPO' => $s['SISO_CPONO'], 'SISO_SOLINE' => $s['SISO_SOLINE'], 'SI_BSGRP' => $k['SI_BSGRP'], 'SI_CUSCD' => $k['SI_CUSCD'],
                        ];
                    }
                    if ($k['SISOQTY'] === $k['SISOQTY_X']) {
                        break;
                    }
                }
            }
            unset($s);
        }
        unset($k);
        return $rsPriceItemSer;
    }

    public function setPrice($pdoc = '')
    {
        header('Content-Type: application/json');
        $doc = base64_decode($pdoc);
        $this->DLVPRC_mod->deleteby_filter(['DLVPRC_TXID' => $doc]);
        $createdAT = date('Y-m-d H:i:s');
        $rs = $this->SISCN_mod->select_forsetPrice($doc);
        $rscurrentPrice_plot = $this->SISO_mod->select_currentPlot($doc);
        $rsPriceItemSer = [];
        $bsgrp = '';
        $cuscd = '';
        $rsser_calc = [];
        foreach ($rs as &$k) {
            $k['PLOTPRCQTY'] = 0;
            $bsgrp = $k['SI_BSGRP'];
            $cuscd = $k['SI_CUSCD'];
            $isfound = false;
            foreach ($rsser_calc as $r) {
                if ($k['SISCN_SER'] == $r['SER']) {
                    $isfound = true;
                    break;
                }
            }
            if (!$isfound) {
                $rsser_calc[] = ['SER' => $k['SISCN_SER'], 'QTY' => $k['SCNQTY'], 'QTYPLOT' => 0];
            }
        }
        unset($k);
        $rsitem_iprice = []; //resume item|price|count
        foreach ($rscurrentPrice_plot as &$k) {
            if ($k['SISO_SOLINE'] == 'X') {
                $rs_mst_price = $this->XSO_mod->select_latestprice($bsgrp, $cuscd, "'" . $k['ITMID'] . "'");
                foreach ($rs_mst_price as $r) {
                    $k['SSO2_SLPRC'] = substr($r['MSPR_SLPRC'], 0, 1) == '.' ? '0' . $r['MSPR_SLPRC'] : $r['MSPR_SLPRC'];
                    $k['PLOTQTY'] = $k['SCNQT'];
                }
            } else {
                if ($k['PLOTQTY'] < $k['SCNQT']) {
                    $isfound = false;
                    foreach ($rsitem_iprice as &$i) {
                        if ($k['ITMID'] == $i['ITMID'] && $k['SSO2_SLPRC'] == $i['PRICE']) {
                            $i['COUNTER']++;
                            $isfound = true;
                        }
                    }
                    unset($i);
                    if (!$isfound) {
                        $rsitem_iprice[] = [
                            'ITMID' => $k['ITMID'], 'PRICE' => $k['SSO2_SLPRC'], 'COUNTER' => 1,
                        ];
                    }
                }
            }
        }
        unset($k);

        //1.0 filter which item use >1 price
        $rsitem_iprice_unique = [];
        foreach ($rsitem_iprice as $i) {
            $isfound = false;
            foreach ($rsitem_iprice_unique as &$u) {
                if ($i['ITMID'] == $u['ITMID']) {
                    $u['COUNTER']++;
                    $isfound = true;
                }
            }
            unset($u);
            if (!$isfound) {
                $rsitem_iprice_unique[] = [
                    'ITMID' => $i['ITMID'], 'COUNTER' => 1,
                ];
            }
        }

        //1.1 if it has multiprice then do not autocomplete plot
        foreach ($rscurrentPrice_plot as &$k) {
            if ($k['PLOTQTY'] < $k['SCNQT']) {
                $isfound = false;
                foreach ($rsitem_iprice_unique as $n) {
                    if ($k['ITMID'] === $n['ITMID']) {
                        if ($n['COUNTER'] > 1) {
                            $isfound = true;
                            break;
                        }
                    }
                }
                if (!$isfound) {
                    if ($k['SISO_SOLINE'] == 'X' || $k['SSO2_BSGRP'] == 'PSI1PPZIEP') {
                        $k['PLOTQTY'] = $k['SCNQT'];
                    }
                }
            }
        }
        unset($k);

        $line = 1;
        $isready = false;
        foreach ($rs as &$k) {
            $isfound = false;
            foreach ($rscurrentPrice_plot as &$s) {
                $bal = $k['SCNQTY'] - $k['PLOTPRCQTY'];
                if ($k['SISCN_LINENO'] === $s['SISO_HLINE'] && $bal > 0 && $s['PLOTQTY'] > 0) {
                    $shouldContinue = false;
                    foreach ($rsser_calc as $c) {
                        if ($c['SER'] == $k['SISCN_SER']) {
                            $balser = $c['QTY'] - $c['QTYPLOT'];
                            if ($balser > 0) {
                                $bal = $balser;
                                $shouldContinue = true;
                                break;
                            }
                        }
                    }
                    unset($c);
                    if ($shouldContinue) {
                        $isready = true;
                        $qtyuse = 0;
                        if ($bal > $s['PLOTQTY']) {
                            $qtyuse = $s['PLOTQTY'];
                            $k['PLOTPRCQTY'] += $s['PLOTQTY'];
                            $s['PLOTQTY'] = 0;
                        } else {
                            $qtyuse = $bal;
                            $s['PLOTQTY'] -= $bal;
                            $k['PLOTPRCQTY'] += $bal;
                        }
                        $rsPriceItemSer[] = [
                            'DLVPRC_TXID' => $doc, 'DLVPRC_SILINE' => $k['SISCN_LINENO'], 'DLVPRC_SER' => $k['SISCN_SER'], 'DLVPRC_QTY' => $qtyuse, 'DLVPRC_PRC' => $s['SSO2_SLPRC'], 'DLVPRC_LINENO' => $line++, 'DLVPRC_CPO' => $s['SISO_CPONO'], 'DLVPRC_CPOLINE' => $s['SISO_SOLINE'], 'DLVPRC_CREATEDAT' => $createdAT, 'DLVPRC_CREATEDBY' => $this->session->userdata('nama'),
                        ];
                        foreach ($rsser_calc as &$c) {
                            if ($c['SER'] == $k['SISCN_SER']) {
                                $c['QTYPLOT'] += $qtyuse;
                                break;
                            }
                        }
                        unset($c);

                        if ($k['SCNQTY'] === $k['PLOTPRCQTY']) {
                            break;
                        }
                    }
                }
            }
            unset($s);
        }
        unset($k);
        if ($isready) {
            $this->DLVPRC_mod->insertb($rsPriceItemSer);
        }
    }

    public function book_rm27()
    {
        ini_set('max_execution_time', '-1');
        $csj = $this->input->get('insj');
        if ($this->ZRPSTOCK_mod->check_Primary(['RPSTOCK_REMARK' => $csj])) {
            $myar[] = ['cd' => 0, 'msg' => 'It was already booked'];
            die(json_encode(['status' => $myar]));
        }
        $rs_head_dlv = $this->DELV_mod->select_for_rm_h($csj);
        foreach ($rs_head_dlv as $r) {
            $cztujuanpengiriman = 1; #$r['DLV_PURPOSE'];
            $consignee = $r['DLV_CONSIGN'];
            $czinvoice = trim($r['DLV_INVNO']);
            $ccustdate = $r['DLV_BCDATE'];
            $czdocbctype = $r['DLV_BCTYPE'];
            $nomoraju = $r['DLV_NOAJU'];
            $cbusiness_group = $r['DLV_BSGRP'];
            $ccustomer_do = $r['DLV_CUSTDO'];
            $czcurrency = trim($r['MCUS_CURCD']);
        }
        if ($czdocbctype == '-') {
            $myar[] = ['cd' => 0, 'msg' => 'please select bc type!'];
            die('{"status" : ' . json_encode($myar) . '}');
        }
        $rscurr = $this->MEXRATE_mod->selectfor_posting($ccustdate, $czcurrency);
        if (count($rscurr) == 0) {
            $myar[] = ["cd" => "0", "msg" => "Please fill exchange rate data !"];
            die('{"status":' . json_encode($myar) . '}');
        } else {
            foreach ($rscurr as $r) {
                $czharga_matauang = $r->MEXRATE_VAL;
                break;
            }
        }
        $requestResume = [];
        $responseResume = [];
        $rsrmmanualDO = $this->DLVRMDOC_mod->select_invoice_posting($csj);
        $rsrm_fromSO = $this->DLVRMSO_mod->select_invoice($csj);
        $SERI_BARANG = 1;
        $DATA_POST_TYPE = ''; /// 1=DO MANUAL, 2=SO, 3=AUTO
        $arx_item = [];
        $arx_qty = [];
        $arx_lot = [];
        $arx_do = [];
        $rsbc = '';
        if (count($rsrmmanualDO) && count($rsrm_fromSO) <= 0) {
            $DATA_POST_TYPE = 1;
            $rsitem_p_price = $rsrmmanualDO;
            foreach ($rsitem_p_price as $r) {
                $t_HARGA_PENYERAHAN = $r['AMNT'] * $czharga_matauang;
                $tpb_barang[] = [
                    'KODE_BARANG' => $r['DLVRMDOC_ITMID'], 'POS_TARIF' => '', 'URAIAN' => $r['DLV_ITMD1'], 'JUMLAH_SATUAN' => $r['ITMQT'], 'KODE_SATUAN' => $r['MITM_STKUOM'] == 'PCS' ? 'PCE' : $r['MITM_STKUOM'], 'NETTO' => 1, 'CIF' => round($r['AMNT'], 2), 'HARGA_PENYERAHAN' => $t_HARGA_PENYERAHAN, 'SERI_BARANG' => $SERI_BARANG, 'KODE_STATUS' => '02',
                ];
                $SERI_BARANG++;
                $arx_item[] = $r['DLVRMDOC_ITMID'];
                $arx_qty[] = $r['ITMQT'];
                $arx_do[] = $r['DLVRMDOC_DO'];

                $isfound = false;
                foreach ($requestResume as &$n) {
                    if ($n['ITEM'] == $r['DLVRMDOC_ITMID']) {
                        $n['QTY'] += $r['ITMQT'];
                        $isfound = true;
                    }
                }
                unset($n);
                if (!$isfound) {
                    $requestResume[] = ['ITEM' => $r['DLVRMDOC_ITMID'], 'QTY' => $r['ITMQT']];
                }
            }
        } else {
            $DATA_POST_TYPE = 3;
            $rsrm_fromDO = $this->DELV_mod->select_det_byid_rm($csj);
            if (count($rsrm_fromDO)) {
                foreach ($rsrm_fromDO as $r) {
                    #set request data
                    $arx_item[] = $r['DLV_ITMCD'];
                    $arx_qty[] = $r['DLV_QTY'];
                    $arx_lot[] = '';

                    $isfound = false;
                    foreach ($requestResume as &$n) {
                        if ($n['ITEM'] == $r['DLV_ITMCD']) {
                            $n['QTY'] += $r['DLV_QTY'];
                            $isfound = true;
                        }
                    }
                    unset($n);
                    if (!$isfound) {
                        $requestResume[] = ['ITEM' => $r['DLV_ITMCD'], 'QTY' => $r['DLV_QTY']];
                    }
                    #end
                }
            } else {
                $myar[] = ["cd" => "0", "msg" => "there is no data source"];
                die('{"status":' . json_encode($myar) . '}');
            }
        }
        $rstemp = '';
        if (count($arx_item) == 0) {
            $myar[] = ["cd" => "0", "msg" => "there is no data to be posted"];
            die('{"status":' . json_encode($myar) . '}');
        }
        $tpb_bahan_baku = [];
        $rshscd = $this->MSTITM_mod->select_forcustoms($arx_item);
        switch ($DATA_POST_TYPE) {
            case 1:
                $rstemp = $this->inventory_getstockbc_v2_witDO($czdocbctype, $cztujuanpengiriman, $csj, $arx_item, $arx_qty, $ccustdate, $arx_do);
                break;
            case 2:
                $rstemp = $this->inventory_getstockbc_v2($czdocbctype, $cztujuanpengiriman, $csj, $arx_item, $arx_qty, [], $ccustdate);
                break;
            case 3:
                $rstemp = $this->inventory_getstockbc_v2($czdocbctype, $cztujuanpengiriman, $csj, $arx_item, $arx_qty, [], $ccustdate);
                break;
        }
        $rsbc = json_decode($rstemp);
        if (!is_null($rsbc)) {
            if (count($rsbc) > 0) {
                foreach ($rsbc as $o) {
                    foreach ($o->data as $v) {
                        $isfound = false;
                        foreach ($responseResume as &$n) {
                            if ($n['ITEM'] == $v->BC_ITEM) {
                                $n['QTY'] += $v->BC_QTY;
                                $isfound = true;
                            }
                        }
                        unset($n);
                        if (!$isfound) {
                            $responseResume[] = ['ITEM' => $v->BC_ITEM, 'QTY' => $v->BC_QTY];
                        }
                        //THE ADDITIONAL INFO

                        $thehscode = '';

                        if (!$v->RCV_HSCD || rtrim($v->RCV_HSCD) === '') {
                            foreach ($rshscd as $h) {
                                if ($v->BC_ITEM == $h['MITM_ITMCD']) {
                                    $thehscode = $h['MITM_HSCD'];
                                    break;
                                }
                            }
                        } else {
                            $thehscode = $v->RCV_HSCD;
                        }
                        if ($v->RCV_KPPBC != '-') {
                            $tpb_bahan_baku[] = [
                                'KODE_JENIS_DOK_ASAL' => $v->BC_TYPE, 'NOMOR_DAFTAR_DOK_ASAL' => $v->BC_NUM, 'TANGGAL_DAFTAR_DOK_ASAL' => $v->BC_DATE, 'KODE_KANTOR' => $v->RCV_KPPBC, 'NOMOR_AJU_DOK_ASAL' => strlen($v->BC_AJU) == 6 ? substr('000000000000000000000000', 0, 26) : $v->BC_AJU, 'SERI_BARANG_DOK_ASAL' => empty($v->RCV_ZNOURUT) ? 0 : $v->RCV_ZNOURUT, 'SPESIFIKASI_LAIN' => null, 'CIF' => substr($v->RCV_PRPRC, 0, 1) == '.' ? ('0' . $v->RCV_PRPRC * $v->BC_QTY) : ($v->RCV_PRPRC * $v->BC_QTY), 'HARGA_PENYERAHAN' => 0, 'KODE_BARANG' => $v->BC_ITEM, 'KODE_STATUS' => "03", 'POS_TARIF' => $thehscode, 'URAIAN' => $v->MITM_ITMD1, 'TIPE' => $v->MITM_SPTNO, 'JUMLAH_SATUAN' => $v->BC_QTY, 'JENIS_SATUAN' => ($v->MITM_STKUOM == 'PCS') ? 'PCE' : $v->MITM_STKUOM, 'KODE_ASAL_BAHAN_BAKU' => ($v->BC_TYPE == '27' || $v->BC_TYPE == '23') ? '0' : '1', 'RBM' => substr($v->RCV_BM, 0, 1) == '.' ? ('0' . $v->RCV_BM) : ($v->RCV_BM),
                            ];
                        } else {
                            $tpb_bahan_baku[] = [
                                'KODE_JENIS_DOK_ASAL' => $v->BC_TYPE, 'NOMOR_DAFTAR_DOK_ASAL' => $v->BC_NUM, 'TANGGAL_DAFTAR_DOK_ASAL' => $v->BC_DATE, 'KODE_KANTOR' => null, 'NOMOR_AJU_DOK_ASAL' => strlen($v->BC_AJU) == 6 ? substr('000000000000000000000000', 0, 26) : $v->BC_AJU, 'SERI_BARANG_DOK_ASAL' => empty($v->RCV_ZNOURUT) ? 0 : $v->RCV_ZNOURUT, 'SPESIFIKASI_LAIN' => null, 'CIF' => 0, 'HARGA_PENYERAHAN' => 0, 'KODE_BARANG' => trim($v->BC_ITEM), 'KODE_STATUS' => "02", 'POS_TARIF' => $thehscode, 'URAIAN' => $v->MITM_ITMD1, 'TIPE' => $v->MITM_SPTNO, 'JUMLAH_SATUAN' => $v->BC_QTY, 'JENIS_SATUAN' => 'PCE', 'KODE_ASAL_BAHAN_BAKU' => 0, 'RBM' => 0,
                            ];
                        }
                    }
                }
                $listNeedExBC = [];
                foreach ($requestResume as $r) {
                    $isfound = false;
                    foreach ($responseResume as $n) {
                        if ($r['ITEM'] == $n['ITEM']) {
                            $isfound = true;
                            if ($r['QTY'] != $n['QTY']) {
                                $listNeedExBC[] = ['ITMCD' => $r['ITEM'], 'QTY' => $r['QTY'] - $n['QTY'], 'LOTNO' => '?'];
                            }
                        }
                    }
                    if (!$isfound) {
                        $listNeedExBC[] = ['ITMCD' => $r['ITEM'], 'QTY' => $r['QTY'], 'LOTNO' => '?'];
                    }
                }
                if (count($listNeedExBC) > 0) {
                    $this->inventory_cancelDO($csj);
                    $myar[] = ['cd' => '110', 'msg' => 'EX-BC for ' . count($listNeedExBC) . ' item(s) is not found. ', "doctype" => $czdocbctype, "tujuankirim" => $cztujuanpengiriman];
                    die('{"status" : ' . json_encode($myar) . ', "data":' . json_encode($listNeedExBC)
                        . ',"rawdata":' . json_encode($tpb_bahan_baku)
                        . ',"itemcdsend":' . json_encode($arx_item)
                        . ',"itemqtysend":' . json_encode($arx_qty)
                        . ',"itemdosend":' . json_encode($arx_do)
                        . ',"response":' . $rstemp . '}');
                }
            } else {
                $myar[] = ["cd" => "0", "msg" => "Could not find exbc, please contact admin !"];
                die('{"status":' . json_encode($myar) . '}');
            }
        } else {
            $myar[] = ["cd" => "0", "msg" => "Could not find exbc, please contact admin"];
            die('{"status":' . json_encode($myar) . '}');
        }
        $myar[] = [
            'cd' => '1', 'msg' => 'Done, Booked', 'rsbc' => $rsbc, 'tpb_bahan_baku' => $tpb_bahan_baku, 'datasource' => $DATA_POST_TYPE,
        ];
        die('{"status" : ' . json_encode($myar) . '}');
    }
    public function book_rm25()
    {
        ini_set('max_execution_time', '-1');
        $csj = $this->input->get('insj');
        if ($this->ZRPSTOCK_mod->check_Primary(['RPSTOCK_REMARK' => $csj])) {
            $myar[] = ['cd' => 0, 'msg' => 'It was already booked'];
            die(json_encode(['status' => $myar]));
        }
        $rs_head_dlv = $this->DELV_mod->select_for_rm_h($csj);
        foreach ($rs_head_dlv as $r) {
            $cztujuanpengiriman = 1; #$r['DLV_PURPOSE'];
            $consignee = $r['DLV_CONSIGN'];
            $czinvoice = trim($r['DLV_INVNO']);
            $ccustdate = $r['DLV_BCDATE'];
            $czdocbctype = $r['DLV_BCTYPE'];
            $nomoraju = $r['DLV_NOAJU'];
            $cbusiness_group = $r['DLV_BSGRP'];
            $ccustomer_do = $r['DLV_CUSTDO'];
            $czcurrency = trim($r['MCUS_CURCD']);
        }
        if ($czdocbctype == '-') {
            $myar[] = ['cd' => 0, 'msg' => 'please select bc type!'];
            die('{"status" : ' . json_encode($myar) . '}');
        }
        $rscurr = $this->MEXRATE_mod->selectfor_posting($ccustdate, $czcurrency);
        if (count($rscurr) == 0) {
            $myar[] = ["cd" => "0", "msg" => "Please fill exchange rate data !"];
            die('{"status":' . json_encode($myar) . '}');
        } else {
            foreach ($rscurr as $r) {
                $czharga_matauang = $r->MEXRATE_VAL;
                break;
            }
        }
        $requestResume = [];
        $responseResume = [];
        $rsrmmanualDO = $this->DLVRMDOC_mod->select_invoice_posting($csj);
        $rsrm_fromSO = $this->DLVRMSO_mod->select_invoice($csj);
        $SERI_BARANG = 1;
        $DATA_POST_TYPE = ''; /// 1=DO MANUAL, 2=SO, 3=AUTO
        $arx_item = [];
        $arx_qty = [];
        $arx_lot = [];
        $arx_do = [];
        $rsbc = '';
        if (count($rsrmmanualDO) && count($rsrm_fromSO) <= 0) {
            $DATA_POST_TYPE = 1;
            $rsitem_p_price = $rsrmmanualDO;
            foreach ($rsitem_p_price as $r) {
                $t_HARGA_PENYERAHAN = $r['AMNT'] * $czharga_matauang;
                $tpb_barang[] = [
                    'KODE_BARANG' => $r['DLVRMDOC_ITMID'], 'POS_TARIF' => '', 'URAIAN' => $r['DLV_ITMD1'], 'JUMLAH_SATUAN' => $r['ITMQT'], 'KODE_SATUAN' => $r['MITM_STKUOM'] == 'PCS' ? 'PCE' : $r['MITM_STKUOM'], 'NETTO' => 1, 'CIF' => round($r['AMNT'], 2), 'HARGA_PENYERAHAN' => $t_HARGA_PENYERAHAN, 'SERI_BARANG' => $SERI_BARANG, 'KODE_STATUS' => '02',
                ];
                $SERI_BARANG++;
                $arx_item[] = $r['DLVRMDOC_ITMID'];
                $arx_qty[] = $r['ITMQT'];
                $arx_do[] = $r['DLVRMDOC_DO'];

                $isfound = false;
                foreach ($requestResume as &$n) {
                    if ($n['ITEM'] == $r['DLVRMDOC_ITMID']) {
                        $n['QTY'] += $r['ITMQT'];
                        $isfound = true;
                    }
                }
                unset($n);
                if (!$isfound) {
                    $requestResume[] = ['ITEM' => $r['DLVRMDOC_ITMID'], 'QTY' => $r['ITMQT']];
                }
            }
        } else {
            $DATA_POST_TYPE = 3;
            $rsrm_fromDO = $this->DELV_mod->select_det_byid_rm($csj);
            if (count($rsrm_fromDO)) {
                foreach ($rsrm_fromDO as $r) {
                    #set request data
                    $arx_item[] = $r['DLV_ITMCD'];
                    $arx_qty[] = $r['DLV_QTY'];
                    $arx_lot[] = '';

                    $isfound = false;
                    foreach ($requestResume as &$n) {
                        if ($n['ITEM'] == $r['DLV_ITMCD']) {
                            $n['QTY'] += $r['DLV_QTY'];
                            $isfound = true;
                        }
                    }
                    unset($n);
                    if (!$isfound) {
                        $requestResume[] = ['ITEM' => $r['DLV_ITMCD'], 'QTY' => $r['DLV_QTY']];
                    }
                    #end
                }
            } else {
                $myar[] = ["cd" => "0", "msg" => "there is no data source"];
                die('{"status":' . json_encode($myar) . '}');
            }
        }
        $rstemp = '';
        if (count($arx_item) == 0) {
            $myar[] = ["cd" => "0", "msg" => "there is no data to be posted"];
            die('{"status":' . json_encode($myar) . '}');
        }
        $tpb_bahan_baku = [];
        $rshscd = $this->MSTITM_mod->select_forcustoms($arx_item);
        switch ($DATA_POST_TYPE) {
            case 1:
                $rstemp = $this->inventory_getstockbc_v2_witDO($czdocbctype, $cztujuanpengiriman, $csj, $arx_item, $arx_qty, $ccustdate, $arx_do);
                break;
            case 2:
                $rstemp = $this->inventory_getstockbc_v2($czdocbctype, $cztujuanpengiriman, $csj, $arx_item, $arx_qty, [], $ccustdate);
                break;
            case 3:
                $rstemp = $this->inventory_getstockbc_v2($czdocbctype, $cztujuanpengiriman, $csj, $arx_item, $arx_qty, [], $ccustdate);
                break;
        }
        $rsbc = json_decode($rstemp);
        if (!is_null($rsbc)) {
            if (count($rsbc) > 0) {
                foreach ($rsbc as $o) {
                    foreach ($o->data as $v) {
                        $isfound = false;
                        foreach ($responseResume as &$n) {
                            if ($n['ITEM'] == $v->BC_ITEM) {
                                $n['QTY'] += $v->BC_QTY;
                                $isfound = true;
                            }
                        }
                        unset($n);
                        if (!$isfound) {
                            $responseResume[] = ['ITEM' => $v->BC_ITEM, 'QTY' => $v->BC_QTY];
                        }
                        //THE ADDITIONAL INFO

                        $thehscode = '';

                        if (!$v->RCV_HSCD || rtrim($v->RCV_HSCD) === '') {
                            foreach ($rshscd as $h) {
                                if ($v->BC_ITEM == $h['MITM_ITMCD']) {
                                    $thehscode = $h['MITM_HSCD'];
                                    break;
                                }
                            }
                        } else {
                            $thehscode = $v->RCV_HSCD;
                        }
                        if ($v->RCV_KPPBC != '-') {
                            $tpb_bahan_baku[] = [
                                'KODE_JENIS_DOK_ASAL' => $v->BC_TYPE, 'NOMOR_DAFTAR_DOK_ASAL' => $v->BC_NUM, 'TANGGAL_DAFTAR_DOK_ASAL' => $v->BC_DATE, 'KODE_KANTOR' => $v->RCV_KPPBC, 'NOMOR_AJU_DOK_ASAL' => strlen($v->BC_AJU) == 6 ? substr('000000000000000000000000', 0, 26) : $v->BC_AJU, 'SERI_BARANG_DOK_ASAL' => empty($v->RCV_ZNOURUT) ? 0 : $v->RCV_ZNOURUT, 'SPESIFIKASI_LAIN' => null, 'CIF' => substr($v->RCV_PRPRC, 0, 1) == '.' ? ('0' . $v->RCV_PRPRC * $v->BC_QTY) : ($v->RCV_PRPRC * $v->BC_QTY), 'HARGA_PENYERAHAN' => 0, 'KODE_BARANG' => $v->BC_ITEM, 'KODE_STATUS' => "03", 'POS_TARIF' => $thehscode, 'URAIAN' => $v->MITM_ITMD1, 'TIPE' => $v->MITM_SPTNO, 'JUMLAH_SATUAN' => $v->BC_QTY, 'JENIS_SATUAN' => ($v->MITM_STKUOM == 'PCS') ? 'PCE' : $v->MITM_STKUOM, 'KODE_ASAL_BAHAN_BAKU' => ($v->BC_TYPE == '27' || $v->BC_TYPE == '23') ? '0' : '1', 'RBM' => substr($v->RCV_BM, 0, 1) == '.' ? ('0' . $v->RCV_BM) : ($v->RCV_BM),
                            ];
                        } else {
                            $tpb_bahan_baku[] = [
                                'KODE_JENIS_DOK_ASAL' => $v->BC_TYPE, 'NOMOR_DAFTAR_DOK_ASAL' => $v->BC_NUM, 'TANGGAL_DAFTAR_DOK_ASAL' => $v->BC_DATE, 'KODE_KANTOR' => null, 'NOMOR_AJU_DOK_ASAL' => strlen($v->BC_AJU) == 6 ? substr('000000000000000000000000', 0, 26) : $v->BC_AJU, 'SERI_BARANG_DOK_ASAL' => empty($v->RCV_ZNOURUT) ? 0 : $v->RCV_ZNOURUT, 'SPESIFIKASI_LAIN' => null, 'CIF' => 0, 'HARGA_PENYERAHAN' => 0, 'KODE_BARANG' => trim($v->BC_ITEM), 'KODE_STATUS' => "02", 'POS_TARIF' => $thehscode, 'URAIAN' => $v->MITM_ITMD1, 'TIPE' => $v->MITM_SPTNO, 'JUMLAH_SATUAN' => $v->BC_QTY, 'JENIS_SATUAN' => 'PCE', 'KODE_ASAL_BAHAN_BAKU' => 0, 'RBM' => 0,
                            ];
                        }
                    }
                }
                $listNeedExBC = [];
                foreach ($requestResume as $r) {
                    $isfound = false;
                    foreach ($responseResume as $n) {
                        if ($r['ITEM'] == $n['ITEM']) {
                            $isfound = true;
                            if ($r['QTY'] != $n['QTY']) {
                                $listNeedExBC[] = ['ITMCD' => $r['ITEM'], 'QTY' => $r['QTY'] - $n['QTY'], 'LOTNO' => '?'];
                            }
                        }
                    }
                    if (!$isfound) {
                        $listNeedExBC[] = ['ITMCD' => $r['ITEM'], 'QTY' => $r['QTY'], 'LOTNO' => '?'];
                    }
                }
                if (count($listNeedExBC) > 0) {
                    $this->inventory_cancelDO($csj);
                    $myar[] = ['cd' => '110', 'msg' => 'EX-BC for ' . count($listNeedExBC) . ' item(s) is not found. ', "doctype" => $czdocbctype, "tujuankirim" => $cztujuanpengiriman];
                    die('{"status" : ' . json_encode($myar) . ', "data":' . json_encode($listNeedExBC)
                        . ',"rawdata":' . json_encode($tpb_bahan_baku)
                        . ',"itemcdsend":' . json_encode($arx_item)
                        . ',"itemqtysend":' . json_encode($arx_qty)
                        . ',"itemdosend":' . json_encode($arx_do)
                        . ',"response":' . $rstemp . '}');
                }
            } else {
                $myar[] = ["cd" => "0", "msg" => "Could not find exbc, please contact admin !"];
                die('{"status":' . json_encode($myar) . '}');
            }
        } else {
            $myar[] = ["cd" => "0", "msg" => "Could not find exbc, please contact admin"];
            die('{"status":' . json_encode($myar) . '}');
        }
        $myar[] = [
            'cd' => '1', 'msg' => 'Done, Booked', 'rsbc' => $rsbc, 'tpb_bahan_baku' => $tpb_bahan_baku, 'datasource' => $DATA_POST_TYPE,
        ];
        die('{"status" : ' . json_encode($myar) . '}');
    }
    public function book_rm41()
    {
        ini_set('max_execution_time', '-1');
        $csj = $this->input->get('insj');
        if ($this->ZRPSTOCK_mod->check_Primary(['RPSTOCK_REMARK' => $csj])) {
            $myar[] = ['cd' => 0, 'msg' => 'It was already booked'];
            die(json_encode(['status' => $myar]));
        }
        $rs_head_dlv = $this->DELV_mod->select_for_rm_h($csj);
        foreach ($rs_head_dlv as $r) {
            $cztujuanpengiriman = 1;
            $consignee = $r['DLV_CONSIGN'];
            $czinvoice = trim($r['DLV_INVNO']);
            $ccustdate = $r['DLV_BCDATE'];
            $czdocbctype = $r['DLV_BCTYPE'];
            $nomoraju = $r['DLV_NOAJU'];
            $cbusiness_group = $r['DLV_BSGRP'];
            $ccustomer_do = $r['DLV_CUSTDO'];
            $czcurrency = trim($r['MCUS_CURCD']);
        }
        if ($czdocbctype == '-') {
            $myar[] = ['cd' => 0, 'msg' => 'please select bc type!'];
            die('{"status" : ' . json_encode($myar) . '}');
        }
        $rscurr = $this->MEXRATE_mod->selectfor_posting($ccustdate, $czcurrency);
        if (count($rscurr) == 0) {
            $myar[] = ["cd" => "0", "msg" => "Please fill exchange rate data !"];
            die('{"status":' . json_encode($myar) . '}');
        } else {
            foreach ($rscurr as $r) {
                $czharga_matauang = $r->MEXRATE_VAL;
                break;
            }
        }
        $requestResume = [];
        $responseResume = [];
        $rsrmmanualDO = $this->DLVRMDOC_mod->select_invoice_posting($csj);
        $rsrm_fromSO = $this->DLVRMSO_mod->select_invoice($csj);
        $SERI_BARANG = 1;
        $DATA_POST_TYPE = ''; /// 1=DO MANUAL, 2=SO, 3=AUTO
        $arx_item = [];
        $arx_qty = [];
        $arx_lot = [];
        $arx_do = [];
        $rsbc = '';
        if (count($rsrmmanualDO) && count($rsrm_fromSO) <= 0) {
            $DATA_POST_TYPE = 1;
            $rsitem_p_price = $rsrmmanualDO;
            foreach ($rsitem_p_price as $r) {
                $t_HARGA_PENYERAHAN = $r['AMNT'] * $czharga_matauang;
                $tpb_barang[] = [
                    'KODE_BARANG' => $r['DLVRMDOC_ITMID'], 'POS_TARIF' => '', 'URAIAN' => $r['DLV_ITMD1'], 'JUMLAH_SATUAN' => $r['ITMQT'], 'KODE_SATUAN' => $r['MITM_STKUOM'] == 'PCS' ? 'PCE' : $r['MITM_STKUOM'], 'NETTO' => 1, 'CIF' => round($r['AMNT'], 2), 'HARGA_PENYERAHAN' => $t_HARGA_PENYERAHAN, 'SERI_BARANG' => $SERI_BARANG, 'KODE_STATUS' => '02',
                ];
                $SERI_BARANG++;
                $arx_item[] = $r['DLVRMDOC_ITMID'];
                $arx_qty[] = $r['ITMQT'];
                $arx_do[] = $r['DLVRMDOC_DO'];

                $isfound = false;
                foreach ($requestResume as &$n) {
                    if ($n['ITEM'] == $r['DLVRMDOC_ITMID']) {
                        $n['QTY'] += $r['ITMQT'];
                        $isfound = true;
                    }
                }
                unset($n);
                if (!$isfound) {
                    $requestResume[] = ['ITEM' => $r['DLVRMDOC_ITMID'], 'QTY' => $r['ITMQT']];
                }
            }
        } else {
            $DATA_POST_TYPE = 3;
            $rsrm_fromDO = $this->DELV_mod->select_det_byid_rm($csj);
            if (count($rsrm_fromDO)) {
                foreach ($rsrm_fromDO as $r) {
                    #set request data
                    $arx_item[] = $r['DLV_ITMCD'];
                    $arx_qty[] = $r['DLV_QTY'];
                    $arx_lot[] = '';

                    $isfound = false;
                    foreach ($requestResume as &$n) {
                        if ($n['ITEM'] == $r['DLV_ITMCD']) {
                            $n['QTY'] += $r['DLV_QTY'];
                            $isfound = true;
                        }
                    }
                    unset($n);
                    if (!$isfound) {
                        $requestResume[] = ['ITEM' => $r['DLV_ITMCD'], 'QTY' => $r['DLV_QTY']];
                    }
                    #end
                }
            } else {
                $myar[] = ["cd" => "0", "msg" => "there is no data source"];
                die('{"status":' . json_encode($myar) . '}');
            }
        }
        $rstemp = '';
        if (count($arx_item) == 0) {
            $myar[] = ["cd" => "0", "msg" => "there is no data to be posted"];
            die('{"status":' . json_encode($myar) . '}');
        }
        $tpb_bahan_baku = [];
        $rshscd = $this->MSTITM_mod->select_forcustoms($arx_item);
        switch ($DATA_POST_TYPE) {
            case 1:
                $rstemp = $this->inventory_getstockbc_v2_witDO($czdocbctype, $cztujuanpengiriman, $csj, $arx_item, $arx_qty, $ccustdate, $arx_do);
                break;
            case 2:
                $rstemp = $this->inventory_getstockbc_v2($czdocbctype, $cztujuanpengiriman, $csj, $arx_item, $arx_qty, [], $ccustdate);
                break;
            case 3:
                $rstemp = $this->inventory_getstockbc_v2($czdocbctype, $cztujuanpengiriman, $csj, $arx_item, $arx_qty, [], $ccustdate);
                break;
        }
        $rsbc = json_decode($rstemp);
        if (!is_null($rsbc)) {
            if (count($rsbc) > 0) {
                foreach ($rsbc as $o) {
                    foreach ($o->data as $v) {
                        $isfound = false;
                        foreach ($responseResume as &$n) {
                            if ($n['ITEM'] == $v->BC_ITEM) {
                                $n['QTY'] += $v->BC_QTY;
                                $isfound = true;
                            }
                        }
                        unset($n);
                        if (!$isfound) {
                            $responseResume[] = ['ITEM' => $v->BC_ITEM, 'QTY' => $v->BC_QTY];
                        }
                        //THE ADDITIONAL INFO

                        $thehscode = '';

                        if (!$v->RCV_HSCD || rtrim($v->RCV_HSCD) === '') {
                            foreach ($rshscd as $h) {
                                if ($v->BC_ITEM == $h['MITM_ITMCD']) {
                                    $thehscode = $h['MITM_HSCD'];
                                    break;
                                }
                            }
                        } else {
                            $thehscode = $v->RCV_HSCD;
                        }
                        if ($v->RCV_KPPBC != '-') {
                            $tpb_bahan_baku[] = [
                                'KODE_JENIS_DOK_ASAL' => $v->BC_TYPE, 'NOMOR_DAFTAR_DOK_ASAL' => $v->BC_NUM, 'TANGGAL_DAFTAR_DOK_ASAL' => $v->BC_DATE, 'KODE_KANTOR' => $v->RCV_KPPBC, 'NOMOR_AJU_DOK_ASAL' => strlen($v->BC_AJU) == 6 ? substr('000000000000000000000000', 0, 26) : $v->BC_AJU, 'SERI_BARANG_DOK_ASAL' => empty($v->RCV_ZNOURUT) ? 0 : $v->RCV_ZNOURUT, 'SPESIFIKASI_LAIN' => null, 'CIF' => substr($v->RCV_PRPRC, 0, 1) == '.' ? ('0' . $v->RCV_PRPRC * $v->BC_QTY) : ($v->RCV_PRPRC * $v->BC_QTY), 'HARGA_PENYERAHAN' => 0, 'KODE_BARANG' => $v->BC_ITEM, 'KODE_STATUS' => "03", 'POS_TARIF' => $thehscode, 'URAIAN' => $v->MITM_ITMD1, 'TIPE' => $v->MITM_SPTNO, 'JUMLAH_SATUAN' => $v->BC_QTY, 'JENIS_SATUAN' => ($v->MITM_STKUOM == 'PCS') ? 'PCE' : $v->MITM_STKUOM, 'KODE_ASAL_BAHAN_BAKU' => ($v->BC_TYPE == '27' || $v->BC_TYPE == '23') ? '0' : '1', 'RBM' => substr($v->RCV_BM, 0, 1) == '.' ? ('0' . $v->RCV_BM) : ($v->RCV_BM),
                            ];
                        } else {
                            $tpb_bahan_baku[] = [
                                'KODE_JENIS_DOK_ASAL' => $v->BC_TYPE, 'NOMOR_DAFTAR_DOK_ASAL' => $v->BC_NUM, 'TANGGAL_DAFTAR_DOK_ASAL' => $v->BC_DATE, 'KODE_KANTOR' => null, 'NOMOR_AJU_DOK_ASAL' => strlen($v->BC_AJU) == 6 ? substr('000000000000000000000000', 0, 26) : $v->BC_AJU, 'SERI_BARANG_DOK_ASAL' => empty($v->RCV_ZNOURUT) ? 0 : $v->RCV_ZNOURUT, 'SPESIFIKASI_LAIN' => null, 'CIF' => 0, 'HARGA_PENYERAHAN' => 0, 'KODE_BARANG' => trim($v->BC_ITEM), 'KODE_STATUS' => "02", 'POS_TARIF' => $thehscode, 'URAIAN' => $v->MITM_ITMD1, 'TIPE' => $v->MITM_SPTNO, 'JUMLAH_SATUAN' => $v->BC_QTY, 'JENIS_SATUAN' => 'PCE', 'KODE_ASAL_BAHAN_BAKU' => 0, 'RBM' => 0,
                            ];
                        }
                    }
                }
                $listNeedExBC = [];
                foreach ($requestResume as $r) {
                    $isfound = false;
                    foreach ($responseResume as $n) {
                        if ($r['ITEM'] == $n['ITEM']) {
                            $isfound = true;
                            if ($r['QTY'] != $n['QTY']) {
                                $listNeedExBC[] = ['ITMCD' => $r['ITEM'], 'QTY' => $r['QTY'] - $n['QTY'], 'LOTNO' => '?'];
                            }
                        }
                    }
                    if (!$isfound) {
                        $listNeedExBC[] = ['ITMCD' => $r['ITEM'], 'QTY' => $r['QTY'], 'LOTNO' => '?'];
                    }
                }
                if (count($listNeedExBC) > 0) {
                    $this->inventory_cancelDO($csj);
                    $myar[] = ['cd' => '110', 'msg' => 'EX-BC for ' . count($listNeedExBC) . ' item(s) is not found. ', "doctype" => $czdocbctype, "tujuankirim" => $cztujuanpengiriman];
                    die('{"status" : ' . json_encode($myar) . ', "data":' . json_encode($listNeedExBC)
                        . ',"rawdata":' . json_encode($tpb_bahan_baku)
                        . ',"itemcdsend":' . json_encode($arx_item)
                        . ',"itemqtysend":' . json_encode($arx_qty)
                        . ',"itemdosend":' . json_encode($arx_do)
                        . ',"response":' . $rstemp . '}');
                }
            } else {
                $myar[] = ["cd" => "0", "msg" => "Could not find exbc, please contact admin !"];
                die('{"status":' . json_encode($myar) . '}');
            }
        } else {
            $myar[] = ["cd" => "0", "msg" => "Could not find exbc, please contact admin"];
            die('{"status":' . json_encode($myar) . '}');
        }
        $myar[] = [
            'cd' => '1', 'msg' => 'Done, Booked', 'rsbc' => $rsbc, 'tpb_bahan_baku' => $tpb_bahan_baku, 'datasource' => $DATA_POST_TYPE,
        ];
        die('{"status" : ' . json_encode($myar) . '}');
    }
    public function book_rm261()
    {
        ini_set('max_execution_time', '-1');
        $csj = $this->input->get('insj');
        if ($this->ZRPSTOCK_mod->check_Primary(['RPSTOCK_REMARK' => $csj])) {
            $myar[] = ['cd' => 0, 'msg' => 'It was already booked'];
            die(json_encode(['status' => $myar]));
        }
        $rs_head_dlv = $this->DELV_mod->select_for_rm_h($csj);
        foreach ($rs_head_dlv as $r) {
            $cztujuanpengiriman = 1; #$r['DLV_PURPOSE'];
            $consignee = $r['DLV_CONSIGN'];
            $czinvoice = trim($r['DLV_INVNO']);
            $ccustdate = $r['DLV_BCDATE'];
            $czdocbctype = $r['DLV_BCTYPE'];
            $nomoraju = $r['DLV_NOAJU'];
            $cbusiness_group = $r['DLV_BSGRP'];
            $ccustomer_do = $r['DLV_CUSTDO'];
            $czcurrency = trim($r['MCUS_CURCD']);
        }
        if ($czdocbctype == '-') {
            $myar[] = ['cd' => 0, 'msg' => 'please select bc type!'];
            die('{"status" : ' . json_encode($myar) . '}');
        }
        $rscurr = $this->MEXRATE_mod->selectfor_posting($ccustdate, $czcurrency);
        if (count($rscurr) == 0) {
            $myar[] = ["cd" => "0", "msg" => "Please fill exchange rate data !"];
            die('{"status":' . json_encode($myar) . '}');
        } else {
            foreach ($rscurr as $r) {
                $czharga_matauang = $r->MEXRATE_VAL;
                break;
            }
        }
        $requestResume = [];
        $responseResume = [];
        $rsrmmanualDO = $this->DLVRMDOC_mod->select_invoice_posting($csj);
        $rsrm_fromSO = $this->DLVRMSO_mod->select_invoice($csj);
        $SERI_BARANG = 1;
        $DATA_POST_TYPE = ''; /// 1=DO MANUAL, 2=SO, 3=AUTO
        $arx_item = [];
        $arx_qty = [];
        $arx_lot = [];
        $arx_do = [];
        $rsbc = '';
        if (count($rsrmmanualDO) && count($rsrm_fromSO) <= 0) {
            $DATA_POST_TYPE = 1;
            $rsitem_p_price = $rsrmmanualDO;
            foreach ($rsitem_p_price as $r) {
                $t_HARGA_PENYERAHAN = $r['AMNT'] * $czharga_matauang;
                $tpb_barang[] = [
                    'KODE_BARANG' => $r['DLVRMDOC_ITMID'], 'POS_TARIF' => '', 'URAIAN' => $r['DLV_ITMD1'], 'JUMLAH_SATUAN' => $r['ITMQT'], 'KODE_SATUAN' => $r['MITM_STKUOM'] == 'PCS' ? 'PCE' : $r['MITM_STKUOM'], 'NETTO' => 1, 'CIF' => round($r['AMNT'], 2), 'HARGA_PENYERAHAN' => $t_HARGA_PENYERAHAN, 'SERI_BARANG' => $SERI_BARANG, 'KODE_STATUS' => '02',
                ];
                $SERI_BARANG++;
                $arx_item[] = $r['DLVRMDOC_ITMID'];
                $arx_qty[] = $r['ITMQT'];
                $arx_do[] = $r['DLVRMDOC_DO'];

                $isfound = false;
                foreach ($requestResume as &$n) {
                    if ($n['ITEM'] == $r['DLVRMDOC_ITMID']) {
                        $n['QTY'] += $r['ITMQT'];
                        $isfound = true;
                    }
                }
                unset($n);
                if (!$isfound) {
                    $requestResume[] = ['ITEM' => $r['DLVRMDOC_ITMID'], 'QTY' => $r['ITMQT']];
                }
            }
        } else {

            $DATA_POST_TYPE = 3;
            $rsrm_fromDO = $this->DELV_mod->select_det_byid_rm($csj);
            if (count($rsrm_fromDO)) {
                foreach ($rsrm_fromDO as $r) {
                    #set request data
                    $arx_item[] = $r['DLV_ITMCD'];
                    $arx_qty[] = $r['DLV_QTY'];
                    $arx_lot[] = '';

                    $isfound = false;
                    foreach ($requestResume as &$n) {
                        if ($n['ITEM'] == $r['DLV_ITMCD']) {
                            $n['QTY'] += $r['DLV_QTY'];
                            $isfound = true;
                        }
                    }
                    unset($n);
                    if (!$isfound) {
                        $requestResume[] = ['ITEM' => $r['DLV_ITMCD'], 'QTY' => $r['DLV_QTY']];
                    }
                    #end
                }
            } else {
                $myar[] = ["cd" => "0", "msg" => "there is no data source"];
                die('{"status":' . json_encode($myar) . '}');
            }
        }
        $rstemp = '';
        if (count($arx_item) == 0) {
            $myar[] = ["cd" => "0", "msg" => "there is no data to be posted"];
            die('{"status":' . json_encode($myar) . '}');
        }
        $tpb_bahan_baku = [];
        $rshscd = $this->MSTITM_mod->select_forcustoms($arx_item);
        switch ($DATA_POST_TYPE) {
            case 1:
                $rstemp = $this->inventory_getstockbc_v2_witDO($czdocbctype, $cztujuanpengiriman, $csj, $arx_item, $arx_qty, $ccustdate, $arx_do);
                break;
            case 2:
                $rstemp = $this->inventory_getstockbc_v2($czdocbctype, $cztujuanpengiriman, $csj, $arx_item, $arx_qty, [], $ccustdate);
                break;
            case 3:
                $rstemp = $this->inventory_getstockbc_v2($czdocbctype, $cztujuanpengiriman, $csj, $arx_item, $arx_qty, [], $ccustdate);
                break;
        }
        $rsbc = json_decode($rstemp);
        if (!is_null($rsbc)) {
            if (count($rsbc) > 0) {
                foreach ($rsbc as $o) {
                    foreach ($o->data as $v) {
                        $isfound = false;
                        foreach ($responseResume as &$n) {
                            if ($n['ITEM'] == $v->BC_ITEM) {
                                $n['QTY'] += $v->BC_QTY;
                                $isfound = true;
                            }
                        }
                        unset($n);
                        if (!$isfound) {
                            $responseResume[] = ['ITEM' => $v->BC_ITEM, 'QTY' => $v->BC_QTY];
                        }
                        //THE ADDITIONAL INFO

                        $thehscode = '';

                        if (!$v->RCV_HSCD || rtrim($v->RCV_HSCD) === '') {
                            foreach ($rshscd as $h) {
                                if ($v->BC_ITEM == $h['MITM_ITMCD']) {
                                    $thehscode = $h['MITM_HSCD'];
                                    break;
                                }
                            }
                        } else {
                            $thehscode = $v->RCV_HSCD;
                        }
                        if ($v->RCV_KPPBC != '-') {
                            $tpb_bahan_baku[] = [
                                'KODE_JENIS_DOK_ASAL' => $v->BC_TYPE, 'NOMOR_DAFTAR_DOK_ASAL' => $v->BC_NUM, 'TANGGAL_DAFTAR_DOK_ASAL' => $v->BC_DATE, 'KODE_KANTOR' => $v->RCV_KPPBC, 'NOMOR_AJU_DOK_ASAL' => strlen($v->BC_AJU) == 6 ? substr('000000000000000000000000', 0, 26) : $v->BC_AJU, 'SERI_BARANG_DOK_ASAL' => empty($v->RCV_ZNOURUT) ? 0 : $v->RCV_ZNOURUT, 'SPESIFIKASI_LAIN' => null, 'CIF' => substr($v->RCV_PRPRC, 0, 1) == '.' ? ('0' . $v->RCV_PRPRC * $v->BC_QTY) : ($v->RCV_PRPRC * $v->BC_QTY), 'HARGA_PENYERAHAN' => 0, 'KODE_BARANG' => $v->BC_ITEM, 'KODE_STATUS' => "03", 'POS_TARIF' => $thehscode, 'URAIAN' => $v->MITM_ITMD1, 'TIPE' => $v->MITM_SPTNO, 'JUMLAH_SATUAN' => $v->BC_QTY, 'JENIS_SATUAN' => ($v->MITM_STKUOM == 'PCS') ? 'PCE' : $v->MITM_STKUOM, 'KODE_ASAL_BAHAN_BAKU' => ($v->BC_TYPE == '27' || $v->BC_TYPE == '23') ? '0' : '1', 'RBM' => substr($v->RCV_BM, 0, 1) == '.' ? ('0' . $v->RCV_BM) : ($v->RCV_BM),
                            ];
                        } else {
                            $tpb_bahan_baku[] = [
                                'KODE_JENIS_DOK_ASAL' => $v->BC_TYPE, 'NOMOR_DAFTAR_DOK_ASAL' => $v->BC_NUM, 'TANGGAL_DAFTAR_DOK_ASAL' => $v->BC_DATE, 'KODE_KANTOR' => null, 'NOMOR_AJU_DOK_ASAL' => strlen($v->BC_AJU) == 6 ? substr('000000000000000000000000', 0, 26) : $v->BC_AJU, 'SERI_BARANG_DOK_ASAL' => empty($v->RCV_ZNOURUT) ? 0 : $v->RCV_ZNOURUT, 'SPESIFIKASI_LAIN' => null, 'CIF' => 0, 'HARGA_PENYERAHAN' => 0, 'KODE_BARANG' => trim($v->BC_ITEM), 'KODE_STATUS' => "02", 'POS_TARIF' => $thehscode, 'URAIAN' => $v->MITM_ITMD1, 'TIPE' => $v->MITM_SPTNO, 'JUMLAH_SATUAN' => $v->BC_QTY, 'JENIS_SATUAN' => 'PCE', 'KODE_ASAL_BAHAN_BAKU' => 0, 'RBM' => 0,
                            ];
                        }
                    }
                }
                $listNeedExBC = [];
                foreach ($requestResume as $r) {
                    $isfound = false;
                    foreach ($responseResume as $n) {
                        if ($r['ITEM'] == $n['ITEM']) {
                            $isfound = true;
                            if ($r['QTY'] != $n['QTY']) {
                                $listNeedExBC[] = ['ITMCD' => $r['ITEM'], 'QTY' => $r['QTY'] - $n['QTY'], 'LOTNO' => '?'];
                            }
                        }
                    }
                    if (!$isfound) {
                        $listNeedExBC[] = ['ITMCD' => $r['ITEM'], 'QTY' => $r['QTY'], 'LOTNO' => '?'];
                    }
                }
                if (count($listNeedExBC) > 0) {
                    $this->inventory_cancelDO($csj);
                    $myar[] = ['cd' => '110', 'msg' => 'EX-BC for ' . count($listNeedExBC) . ' item(s) is not found. ', "doctype" => $czdocbctype, "tujuankirim" => $cztujuanpengiriman];
                    die('{"status" : ' . json_encode($myar) . ', "data":' . json_encode($listNeedExBC)
                        . ',"rawdata":' . json_encode($tpb_bahan_baku)
                        . ',"itemcdsend":' . json_encode($arx_item)
                        . ',"itemqtysend":' . json_encode($arx_qty)
                        . ',"itemdosend":' . json_encode($arx_do)
                        . ',"response":' . $rstemp . '}');
                }
            } else {
                $myar[] = ["cd" => "0", "msg" => "Could not find exbc, please contact admin !"];
                die('{"status":' . json_encode($myar) . '}');
            }
        } else {
            $myar[] = ["cd" => "0", "msg" => "Could not find exbc, please contact admin"];
            die('{"status":' . json_encode($myar) . '}');
        }
        $myar[] = [
            'cd' => '1', 'msg' => 'Done, Booked', 'rsbc' => $rsbc, 'tpb_bahan_baku' => $tpb_bahan_baku, 'datasource' => $DATA_POST_TYPE,
        ];
        die('{"status" : ' . json_encode($myar) . '}');
    }

    public function posting_rm25()
    {
        set_exception_handler([$this, 'exception_handler']);
        set_error_handler([$this, 'log_error']);
        register_shutdown_function([$this, 'fatal_handler']);
        header('Content-Type: application/json');
        ini_set('max_execution_time', '-1');
        $currentDate = date('Y-m-d H:i:s');
        $csj = $this->input->get('insj');
        $rsaktivasi = $this->AKTIVASIAPLIKASI_imod->selectAll();
        $cztujuanpengiriman = '';
        $consignee = '';
        $czinvoice = '';
        $ccustdate = '';
        $czdocbctype = '';
        $nomoraju = '';
        $deliveryDescription = '';

        $czkantortujuan = '';
        $cz_KODE_JENIS_TPB = '';
        $cz_KODE_TUJUAN_TPB = '';
        $czidpenerima = '';
        $cznmpenerima = '';
        $czalamatpenerima = '';
        $czizinpenerima = '';
        $cznamapengangkut = '';
        $cznomorpolisi = '';

        $czinvoicedt = '';
        $rs_head_dlv = $this->DELV_mod->select_for_rm_h($csj);
        foreach ($rs_head_dlv as $r) {
            $cztujuanpengiriman = $r['DLV_PURPOSE'];
            $consignee = $r['DLV_CONSIGN'];
            $czinvoice = trim($r['DLV_INVNO']);
            $ccustdate = $r['DLV_BCDATE'];
            $czdocbctype = $r['DLV_BCTYPE'];
            $nomoraju = $r['DLV_NOAJU'];
            $deliveryDescription = $r['DLV_DSCRPTN'];
            $czcurrency = trim($r['MCUS_CURCD']);

            $czkantortujuan = $r['DLV_DESTOFFICE'];
            $cz_KODE_JENIS_TPB = $r['DLV_ZJENIS_TPB_ASAL'];
            $cz_KODE_TUJUAN_TPB = $r['DLV_ZJENIS_TPB_TUJUAN'];
            $czidpenerima = str_replace([".", "-"], "", $r['MCUS_TAXREG']);

            $cznmpenerima = $r['MDEL_ZNAMA'];
            $czalamatpenerima = $r['MDEL_ADDRCUSTOMS'];
            $czizinpenerima = $r['MDEL_ZSKEP'];

            $cznamapengangkut = $r['MSTTRANS_TYPE'];
            $cznomorpolisi = $r['DLV_TRANS'];

            $czinvoicedt = $r['DLV_INVDT'];
        }
        if ($this->ZRPSTOCK_mod->check_Primary(['RPSTOCK_REMARK' => $csj]) == 0) {
            $myar[] = ['cd' => 0, 'msg' => 'Please book EXBC first'];
            die('{"status" : ' . json_encode($myar) . '}');
        }

        if ($consignee == '-') {
            $myar[] = ['cd' => 0, 'msg' => 'please set consignment (' . $consignee . ')'];
            die('{"status" : ' . json_encode($myar) . '}');
        }

        if ($czinvoice == '') {
            $myar[] = ['cd' => 0, 'msg' => 'please add invoice number (' . $czinvoice . ') custdate (' . $ccustdate . ') consign (' . $consignee . ')'];
            die('{"status" : ' . json_encode($myar) . '}');
        }

        if ($czdocbctype == '-') {
            $myar[] = ['cd' => 0, 'msg' => 'please select bc type!'];
            die('{"status" : ' . json_encode($myar) . '}');
        }

        if (strlen($nomoraju) != 6) {
            $myar[] = ['cd' => 0, 'msg' => 'NOMOR AJU is not valid, please re-check (' . $nomoraju . ')'];
            die('{"status" : ' . json_encode($myar) . '}');
        }

        $ocustdate = date_create($ccustdate);
        $cz_ymd = date_format($ocustdate, 'Ymd');

        $czidpengusaha = '';
        $cznmpengusaha = '';
        $czalamatpengusaha = '';
        $czizinpengusaha = '';
        foreach ($rsaktivasi as $r) {
            $czkantorasal = $r['KPPBC'];
            $czidmodul = substr('00000' . $r['ID_MODUL'], -6);
            $czidmodul_asli = $r['ID_MODUL'];
            $czidpengusaha = $r['NPWP'];
            $cznmpengusaha = $r['NAMA_PENGUSAHA'];
            $czalamatpengusaha = $r['ALAMAT_PENGUSAHA'];
            $czizinpengusaha = $r['NOMOR_SKEP'];
        }

        $cnoaju = substr($czkantorasal, 0, 4) . $czdocbctype . $czidmodul . $cz_ymd . $nomoraju;
        if ($this->TPB_HEADER_imod->check_Primary(['NOMOR_AJU' => $cnoaju]) > 0) {
            $myar[] = ['cd' => 0, 'msg' => 'the NOMOR AJU is already posted'];
            die('{"status" : ' . json_encode($myar) . '}');
        }

        if (strlen($ccustdate) != 10) {
            $myar[] = ['cd' => 0, 'msg' => 'please enter valid customs date!'];
            die('{"status" : ' . json_encode($myar) . '}');
        }

        if ($this->DELV_mod->check_Primary(['DLV_ID' => $csj]) == 0) {
            $myar[] = ['cd' => 0, 'msg' => 'DO is not found'];
            die('{"status" : ' . json_encode($myar) . '}');
        }

        $rspkg = $this->DELV_mod->select_pkg($csj);
        $cz_JUMLAH_KEMASAN = 0;
        $netweight_represent = 0;
        $cz_h_BRUTO = 0;
        $cz_h_NETTO = 0;
        foreach ($rspkg as $r) {
            if ($r['DLV_PKG_NWG'] > 0) {
                $netweight_represent = $r['DLV_PKG_NWG'];
                $cz_h_BRUTO += $r['DLV_PKG_GWG'];
                $cz_h_NETTO += $r['DLV_PKG_NWG'];
            }
            if ($r['DLV_PKG_MEASURE']) {
                $cz_JUMLAH_KEMASAN++;
            }
        }

        $flg_sell = strpos($deliveryDescription, 'DIKEMBALIKAN') !== false ? false : true;
        $cz_h_NDPBM = 0;
        $rscurr = $this->MEXRATE_mod->selectfor_posting_in([$ccustdate], [$czcurrency]);
        if ($flg_sell) {
            if (count($rscurr) == 0) {
                $myar[] = ["cd" => "0", "msg" => "Please fill exchange rate data !,"];
                die('{"status":' . json_encode($myar) . '}');
            } else {
                foreach ($rscurr as $r) {
                    if ($czcurrency == 'RPH') {
                        $cz_h_NDPBM = $r->MEXRATE_VAL;
                        break;
                    }
                }
            }
        }

        #HEADER_HARGA
        $cz_h_CIF_FG = 0;
        $cz_h_HARGA_PENYERAHAN_FG = 0;

        #check price source
        $rsrmmanualDO = $this->DLVRMDOC_mod->select_invoice_posting($csj);
        $rsrm_fromSO = $this->DLVRMSO_mod->select_invoice($csj);
        $SERI_BARANG = 1;
        if (count($rsrmmanualDO) && count($rsrm_fromSO) <= 0) {
        } else {
            $rsrm_fromSO = $this->DLVRMSO_mod->select_invoice($csj);
            if (count($rsrm_fromSO)) {
            } else {
                $rsrm_fromDO = $this->DELV_mod->select_det_byid_rm($csj);
                if (count($rsrm_fromDO)) {
                } else {
                    $myar[] = ["cd" => "0", "msg" => "there is no data source"];
                    die('{"status":' . json_encode($myar) . '}');
                }
            }
        }
        $tpb_bahan_baku = [];
        $rs_do = $this->DELV_mod->select_for_do_rm_rtn_v1($csj);
        $rs_xbc = $this->RCV_mod->select_for_rmrtn_bytxid($csj);
        $IncDateList = [];
        $IncCRList = []; #Currency
        $IncDateCR_FLGList = []; #Currency
        $resumeXBCDoc = [];
        foreach ($rs_do as $r) {
            $r['PLOTQT'] = 0;
            foreach ($rs_xbc as &$x) {
                if (
                    $r['DLVRMDOC_ITMID'] == $x['RCV_ITMCD']
                    && $r['DLVRMDOC_AJU'] == $x['RCV_RPNO']
                    && $r['DLVRMDOC_DO'] == $x['RCV_DONO']
                    && $x['RCV_QTY'] > 0
                    && $r['PLOTQT'] != $r['DLVRMDOC_ITMQT']
                ) {
                    $reqbal = $r['DLVRMDOC_ITMQT'] - $r['PLOTQT'];
                    $useqt = 0;
                    if ($reqbal > $x['RCV_QTY']) {
                        $useqt = $x['RCV_QTY'];
                        $r['PLOTQT'] += $x['RCV_QTY'];
                        $x['RCV_QTY'] = 0;
                    } else {
                        $useqt = $reqbal;
                        $r['PLOTQT'] += $reqbal;
                        $x['RCV_QTY'] -= $reqbal;
                    }
                    $tpb_bahan_baku[] = [
                        'KODE_JENIS_DOK_ASAL' => $x['RCV_BCTYPE'], 'NOMOR_DAFTAR_DOK_ASAL' => $r['DLVRMDOC_NOPEN'], 'TANGGAL_DAFTAR_DOK_ASAL' => $x['RCV_BCDATE'], 'KODE_KANTOR' => $x['RCV_KPPBC'], 'NOMOR_AJU_DOK_ASAL' => strlen($r['DLVRMDOC_AJU']) == 6 ? substr('000000000000000000000000', 0, 26) : $r['DLVRMDOC_AJU'], 'SERI_BARANG_DOK_ASAL' => empty($x['RCV_ZNOURUT']) ? 0 : $x['RCV_ZNOURUT'], 'SPESIFIKASI_LAIN' => null, 'CIF' => round($x['RCV_PRPRC'] * $cz_h_NDPBM) * $useqt, 'CIF_RUPIAH' => round($x['RCV_PRPRC'] * $cz_h_NDPBM) * $useqt, 'HARGA_PENYERAHAN' => null, 'NDPBM' => 1, 'KODE_BARANG' => $r['DLVRMDOC_ITMID'], 'KODE_STATUS' => "03", 'POS_TARIF' => $x['RCV_HSCD'], 'URAIAN' => rtrim($r['DLV_ITMD1']) == '' ? $r['MITM_ITMD1'] : $r['DLV_ITMD1'], 'TIPE' => rtrim($r['DLV_ITMSPTNO']), 'JUMLAH_SATUAN' => $useqt, 'SERI_BAHAN_BAKU' => 1, 'JENIS_SATUAN' => ($r['MITM_STKUOM'] == 'PCS') ? 'PCE' : $r['MITM_STKUOM'], 'KODE_ASAL_BAHAN_BAKU' => ($x['RCV_BCTYPE'] == '27' || $x['RCV_BCTYPE'] == '23') ? '0' : '1', 'RBM' => $x['RCV_BM'] * 1, 'CURRENCY' => $x['MSUP_SUPCR'], 'PPN' => 11, //bu gusti, terkait peraturan 1 april
                    ];
                    $IncDateList[] = $x['RCV_BCDATE'];
                    $IncCRList[] = $x['MSUP_SUPCR'];
                    $IncDateCR_FLGList[] = 0;
                    if ($r['DLVRMDOC_ITMQT'] == $r['PLOTQT']) {
                        break;
                    }
                }
            }
            unset($x);
        }
        unset($r);
        $tpb_barang = [];
        $no = 1;
        if (!$flg_sell) {
            $rscurr = $this->MEXRATE_mod->selectfor_posting_in($IncDateList, $IncCRList);
            $listcount = count($IncDateCR_FLGList);
            foreach ($rscurr as $r) {
                for ($i = 0; $i < $listcount; $i++) {
                    if ($r->MEXRATE_CURR == $IncCRList[$i] && $r->MEXRATE_DT == $IncDateList[$i]) {
                        $IncDateCR_FLGList[$i] = 1;
                    }
                }
            }

            #validate exchange rate for incoming date
            for ($i = 0; $i < $listcount; $i++) {
                if ($IncDateCR_FLGList[$i] == 0) {
                    $dar = [
                        "cd" => "0", "msg" => "Please fill exchange rate data for " . $IncCRList[$i] . " on " . $IncDateList[$i] . " !", "deliveryDescription" => $deliveryDescription,
                    ];
                    $myar[] = $dar;
                    die('{"status":' . json_encode($myar) . '}');
                }
            }
            #end

            foreach ($tpb_bahan_baku as $r) {
                $tpb_barang[] = [
                    'KODE_BARANG' => $r['KODE_BARANG'], 'POS_TARIF' => $r['POS_TARIF'], 'URAIAN' => $r['URAIAN'], 'TIPE' => $r['TIPE'], 'JUMLAH_SATUAN' => $r['JUMLAH_SATUAN'], 'KODE_SATUAN' => $r['JENIS_SATUAN'], 'NETTO' => $no == 1 ? $netweight_represent : 0, 'CIF' => $r['CIF'], 'HARGA_PENYERAHAN' => $r['CIF'], 'SERI_BARANG' => $SERI_BARANG, 'KODE_STATUS' => '02', 'JUMLAH_BAHAN_BAKU' => 1, 'KODE_GUNA' => '0', 'KATEGORI_BARANG' => '2', 'KONDISI_BARANG' => '1', 'KODE_NEGARA_ASAL' => 'ID', 'KODE_PERHITUNGAN' => '0', 'KODE_KEMASAN' => 'BX',
                ];
                $no++;
                $SERI_BARANG++;
            }
        } else {
            $rscurr = $this->MEXRATE_mod->selectfor_posting_in($IncDateList, $IncCRList);
            $listcount = count($IncDateCR_FLGList);
            foreach ($rscurr as $r) {
                for ($i = 0; $i < $listcount; $i++) {
                    if ($r->MEXRATE_CURR == $IncCRList[$i] && $r->MEXRATE_DT == $IncDateList[$i]) {
                        $IncDateCR_FLGList[$i] = 1;
                    }
                }
            }

            foreach ($tpb_bahan_baku as $r) {
                $tpb_barang[] = [
                    'KODE_BARANG' => $r['KODE_BARANG'], 'POS_TARIF' => $r['POS_TARIF'], 'URAIAN' => $r['URAIAN'], 'TIPE' => $r['TIPE'], 'JUMLAH_SATUAN' => $r['JUMLAH_SATUAN'], 'KODE_SATUAN' => $r['JENIS_SATUAN'], 'NETTO' => $no == 1 ? $netweight_represent : 0, 'CIF' => $r['CIF'], 'HARGA_PENYERAHAN' => $r['CIF'], 'SERI_BARANG' => $SERI_BARANG, 'KODE_STATUS' => '02', 'JUMLAH_BAHAN_BAKU' => 1, 'KODE_GUNA' => '0', 'KATEGORI_BARANG' => '2', 'KONDISI_BARANG' => '1', 'KODE_NEGARA_ASAL' => 'ID', 'KODE_PERHITUNGAN' => '0', 'KODE_KEMASAN' => 'BX', 'KODE_JENIS_DOK_ASAL' => $r['KODE_JENIS_DOK_ASAL'], 'NOMOR_DAFTAR_DOK_ASAL' => $r['NOMOR_DAFTAR_DOK_ASAL'], 'TANGGAL_DAFTAR_DOK_ASAL' => $r['TANGGAL_DAFTAR_DOK_ASAL'], 'KODE_KANTOR' => $r['KODE_KANTOR'], 'NOMOR_AJU_DOK_ASAL' => $r['NOMOR_AJU_DOK_ASAL'], 'SERI_BARANG_DOK_ASAL' => $r['SERI_BARANG_DOK_ASAL'], 'CIF_RUPIAH' => $r['CIF_RUPIAH'], 'NDPBM' => 1, 'KODE_STATUS' => $r['KODE_STATUS'], 'POS_TARIF' => $r['POS_TARIF'], 'URAIAN' => $r['URAIAN'], 'JUMLAH_SATUAN' => $r['JUMLAH_SATUAN'], 'SERI_BAHAN_BAKU' => 1, 'JENIS_SATUAN' => $r['JENIS_SATUAN'], 'KODE_ASAL_BAHAN_BAKU' => $r['KODE_ASAL_BAHAN_BAKU'], 'SPESIFIKASI_LAIN' => null, 'HARGA_PENYERAHAN' => null, 'RBM' => $r['RBM'], 'CURRENCY' => $r['CURRENCY'],
                ];
                $no++;
                $SERI_BARANG++;
            }
        }
        $cz_h_JUMLAH_BARANG = count($tpb_barang);
        foreach ($tpb_barang as $r) {
            $cz_h_CIF_FG += $r['CIF'];
            $cz_h_HARGA_PENYERAHAN_FG += $r['HARGA_PENYERAHAN'];
        }

        foreach ($tpb_bahan_baku as $r) {
            $isfound = false;
            foreach ($resumeXBCDoc as $c) {
                if (
                    $r['NOMOR_DAFTAR_DOK_ASAL'] == $c['NOPEN'] && $r['TANGGAL_DAFTAR_DOK_ASAL'] == $c['TGLPEN']
                    && $r['KODE_JENIS_DOK_ASAL'] == $c['BCTYPE']
                ) {
                    $isfound = true;
                    break;
                }
            }
            if (!$isfound) {
                $resumeXBCDoc[] = [
                    'NOPEN' => $r['NOMOR_DAFTAR_DOK_ASAL'], 'TGLPEN' => $r['TANGGAL_DAFTAR_DOK_ASAL'], 'BCTYPE' => $r['KODE_JENIS_DOK_ASAL'],
                ];
            }
        }

        #BAHAN BAKU TARIF
        $tpb_bahan_baku_tarif = [];
        foreach ($tpb_bahan_baku as $r) {
            $tpb_bahan_baku_tarif[] = [
                'JENIS_TARIF' => 'BM', 'KODE_TARIF' => 1, 'NILAI_BAYAR' => 0, 'NILAI_FASILITAS' => 0, 'KODE_FASILITAS' => 2, 'TARIF_FASILITAS' => 100, 'TARIF' => $r['RBM'], 'SERI_BAHAN_BAKU' => $r['SERI_BAHAN_BAKU'], 'RITEMCD' => $r['KODE_BARANG'], 'RITEMQT' => $r['JUMLAH_SATUAN'],
            ];
            $tpb_bahan_baku_tarif[] = [
                'JENIS_TARIF' => 'PPN', 'KODE_TARIF' => 1, 'NILAI_BAYAR' => 0, 'NILAI_FASILITAS' => 0, 'KODE_FASILITAS' => 2, 'TARIF_FASILITAS' => 100, 'TARIF' => $r['PPN'], 'SERI_BAHAN_BAKU' => $r['SERI_BAHAN_BAKU'], 'RITEMCD' => $r['KODE_BARANG'], 'RITEMQT' => $r['JUMLAH_SATUAN'],
            ];
            $tpb_bahan_baku_tarif[] = [
                'JENIS_TARIF' => 'PPH', 'KODE_TARIF' => 1, 'NILAI_BAYAR' => 0, 'NILAI_FASILITAS' => 0, 'KODE_FASILITAS' => 2, 'TARIF_FASILITAS' => 100, 'TARIF' => 2.5, 'SERI_BAHAN_BAKU' => $r['SERI_BAHAN_BAKU'], 'RITEMCD' => $r['KODE_BARANG'], 'RITEMQT' => $r['JUMLAH_SATUAN'],
            ];
        }
        #END

        $tpb_header = [
            "NOMOR_AJU" => $cnoaju, "KODE_KANTOR" => $czkantorasal, "KODE_KANTOR_TUJUAN" => $czkantortujuan,
            "KODE_JENIS_TPB" => $cz_KODE_JENIS_TPB, "KODE_TUJUAN_TPB" => $cz_KODE_TUJUAN_TPB, "KODE_TUJUAN_PENGIRIMAN" => $cztujuanpengiriman,

            "KODE_ID_PENGUSAHA" => "1", "ID_PENGUSAHA" => $czidpengusaha, "NAMA_PENGUSAHA" => $cznmpengusaha,
            "ALAMAT_PENGUSAHA" => $czalamatpengusaha, "NOMOR_IJIN_TPB" => $czizinpengusaha,

            "KODE_ID_PENERIMA_BARANG" => "1", "ID_PENERIMA_BARANG" => $czidpenerima,
            "NAMA_PENERIMA_BARANG" => $cznmpenerima, "ALAMAT_PENERIMA_BARANG" => $czalamatpenerima,
            "NDPBM" => 1,
            "NOMOR_IJIN_TPB_PENERIMA" => $czizinpenerima,

            "KODE_VALUTA" => 'IDR', "CIF" => round($cz_h_CIF_FG, 2), "HARGA_PENYERAHAN" => $cz_h_HARGA_PENYERAHAN_FG,

            "NAMA_PENGANGKUT" => $cznamapengangkut, "NOMOR_POLISI" => $cznomorpolisi,

            "BRUTO" => $cz_h_BRUTO, "NETTO" => $cz_h_NETTO, "JUMLAH_BARANG" => $cz_h_JUMLAH_BARANG,

            "KOTA_TTD" => "CIKARANG", "TANGGAL_TTD" => $ccustdate,

            "KODE_DOKUMEN_PABEAN" => $czdocbctype, "ID_MODUL" => $czidmodul_asli, "VERSI_MODUL" => null,

            "VOLUME" => 0, "KODE_STATUS" => '00',
        ];

        $tpb_kemasan = [];
        $tpb_kemasan[] = ["JUMLAH_KEMASAN" => $cz_JUMLAH_KEMASAN, "KODE_JENIS_KEMASAN" => "BX"];

        $tpb_dokumen = [];
        $tpb_dokumen[] = ["KODE_JENIS_DOKUMEN" => "380", "NOMOR_DOKUMEN" => $czinvoice, "TANGGAL_DOKUMEN" => $czinvoicedt, "TIPE_DOKUMEN" => "02", "SERI_DOKUMEN" => 1]; //invoice
        $tpb_dokumen[] = ["KODE_JENIS_DOKUMEN" => "217", "NOMOR_DOKUMEN" => $czinvoice, "TANGGAL_DOKUMEN" => $czinvoicedt, "TIPE_DOKUMEN" => "02", "SERI_DOKUMEN" => 2]; //packing list
        $tpb_dokumen[] = ["KODE_JENIS_DOKUMEN" => "640", "NOMOR_DOKUMEN" => $csj, "TANGGAL_DOKUMEN" => $ccustdate, "TIPE_DOKUMEN" => "02", "SERI_DOKUMEN" => 3]; //surat jalan
        $seridokumen = 4;
        foreach ($resumeXBCDoc as $n) {
            $tpb_dokumen[] = [
                "KODE_JENIS_DOKUMEN" => $n['BCTYPE'], "NOMOR_DOKUMEN" => $n['NOPEN'], "TANGGAL_DOKUMEN" => $n['TGLPEN'], "TIPE_DOKUMEN" => "02", "SERI_DOKUMEN" => $seridokumen,
            ];
            $seridokumen++;
        }

        #INSERT CEISA
        ##1 TPB HEADER
        $ZR_TPB_HEADER = $this->TPB_HEADER_imod->insert($tpb_header);
        ##N

        ##2 TPB DOKUMEN
        foreach ($tpb_dokumen as &$n) {
            $n['ID_HEADER'] = $ZR_TPB_HEADER;
        }
        unset($n);
        $ZR_TPB_DOKUMEN = $this->TPB_DOKUMEN_imod->insertb($tpb_dokumen);
        ##N

        ##3 TPB KEMASAN
        foreach ($tpb_kemasan as &$n) {
            $n['ID_HEADER'] = $ZR_TPB_HEADER;
        }
        unset($n);
        $ZR_TPB_KEMASAN = $this->TPB_KEMASAN_imod->insertb($tpb_kemasan);
        ##N

        ##4 TPB BARANG
        foreach ($tpb_barang as &$n) {
            $n['ID_HEADER'] = $ZR_TPB_HEADER;
        }
        unset($n);
        ##N

        ##4 TPB BARANG & BAHAN BAKU
        foreach ($tpb_barang as $n) {
            // $ZR_TPB_BARANG = $this->TPB_BARANG_imod->insert($n);
            $_barang = [
                'KODE_BARANG' => substr($n['KODE_BARANG'], 0, 2) == 'PM' ? '-' : $n['KODE_BARANG'], 'POS_TARIF' => $n['POS_TARIF'], 'URAIAN' => $n['URAIAN'], 'TIPE' => $n['TIPE'], 'JUMLAH_SATUAN' => $n['JUMLAH_SATUAN'], 'KODE_SATUAN' => $n['KODE_SATUAN'], 'NETTO' => $n['NETTO'], 'CIF' => $n['CIF'], 'HARGA_PENYERAHAN' => $n['HARGA_PENYERAHAN'], 'SERI_BARANG' => $n['SERI_BARANG'], 'KODE_STATUS' => $n['KODE_STATUS'], 'JUMLAH_BAHAN_BAKU' => $n['JUMLAH_BAHAN_BAKU'], 'ID_HEADER' => $n['ID_HEADER'], 'KODE_GUNA' => $n['KODE_GUNA'], 'KATEGORI_BARANG' => $n['KATEGORI_BARANG'], 'KONDISI_BARANG' => $n['KONDISI_BARANG'], 'KODE_NEGARA_ASAL' => $n['KODE_NEGARA_ASAL'], 'KODE_PERHITUNGAN' => $n['KODE_PERHITUNGAN'], 'KODE_KEMASAN' => $n['KODE_KEMASAN'],
            ];
            $ZR_TPB_BARANG = $this->TPB_BARANG_imod->insert($_barang);
            $tpb_barang_tarif[] = [
                'JENIS_TARIF' => 'BM', 'KODE_FASILITAS' => 0, 'KODE_TARIF' => 1, 'NILAI_BAYAR' => 0, 'NILAI_FASILITAS' => 0, 'NILAI_SUDAH_DILUNASI' => 0, 'SERI_BARANG' => $n['SERI_BARANG'], 'TARIF' => 0, 'TARIF_FASILITAS' => 100, 'ID_BARANG' => $ZR_TPB_BARANG, 'ID_HEADER' => $ZR_TPB_HEADER,
            ];
            $tpb_barang_tarif[] = [
                'JENIS_TARIF' => 'PPN', 'KODE_FASILITAS' => 0, 'KODE_TARIF' => 1, 'NILAI_BAYAR' => 0, 'NILAI_FASILITAS' => 0, 'NILAI_SUDAH_DILUNASI' => 0, 'SERI_BARANG' => $n['SERI_BARANG'], 'TARIF' => 11, 'TARIF_FASILITAS' => 100, 'ID_BARANG' => $ZR_TPB_BARANG, 'ID_HEADER' => $ZR_TPB_HEADER,
            ];
            $tpb_barang_tarif[] = [
                'JENIS_TARIF' => 'PPH', 'KODE_FASILITAS' => 0, 'KODE_TARIF' => 1, 'NILAI_BAYAR' => 0, 'NILAI_FASILITAS' => 0, 'NILAI_SUDAH_DILUNASI' => 0, 'SERI_BARANG' => $n['SERI_BARANG'], 'TARIF' => 2.5, 'TARIF_FASILITAS' => 100, 'ID_BARANG' => $ZR_TPB_BARANG, 'ID_HEADER' => $ZR_TPB_HEADER,
            ];
            $this->TPB_BARANG_TARIF_imod->insertb($tpb_barang_tarif);

            $ZR_TPB_BAHAN_BAKU = $this->TPB_BAHAN_BAKU_imod
                ->insert([
                    'KODE_JENIS_DOK_ASAL' => $n['KODE_JENIS_DOK_ASAL'], 'NOMOR_DAFTAR_DOK_ASAL' => $n['NOMOR_DAFTAR_DOK_ASAL'], 'TANGGAL_DAFTAR_DOK_ASAL' => $n['TANGGAL_DAFTAR_DOK_ASAL'], 'KODE_KANTOR' => $n['KODE_KANTOR'], 'NOMOR_AJU_DOK_ASAL' => $n['NOMOR_AJU_DOK_ASAL'], 'SERI_BARANG_DOK_ASAL' => $n['SERI_BARANG_DOK_ASAL'], 'SPESIFIKASI_LAIN' => $n['SPESIFIKASI_LAIN'], 'CIF' => ($n['CIF']), 'NDPBM' => $n['NDPBM'], 'HARGA_PENYERAHAN' => $n['HARGA_PENYERAHAN'], 'KODE_BARANG' => substr($n['KODE_BARANG'], 0, 2) == 'PM' ? '-' : $n['KODE_BARANG'], 'KODE_STATUS' => $n['KODE_STATUS'], 'POS_TARIF' => $n['POS_TARIF'], 'URAIAN' => $n['URAIAN'], 'TIPE' => $n['TIPE'], 'JUMLAH_SATUAN' => $n['JUMLAH_SATUAN'], 'JENIS_SATUAN' => $n['JENIS_SATUAN'], 'KODE_ASAL_BAHAN_BAKU' => $n['KODE_ASAL_BAHAN_BAKU'], 'NDPBM' => 0, 'NETTO' => 0, 'SERI_BAHAN_BAKU' => $n['SERI_BAHAN_BAKU'], 'SERI_BARANG' => $n['SERI_BARANG'], 'ID_BARANG' => $ZR_TPB_BARANG, 'ID_HEADER' => $ZR_TPB_HEADER,
                ]);
            $iteration = 0;
            foreach ($tpb_bahan_baku_tarif as $t) {
                if (
                    $n['KODE_BARANG'] == $t['RITEMCD'] && $n['JUMLAH_SATUAN'] == $t['RITEMQT']
                    && $n['SERI_BAHAN_BAKU'] == $t['SERI_BAHAN_BAKU']
                ) {
                    if ($iteration == 3) {
                        break;
                    }
                    $this->TPB_BAHAN_BAKU_TARIF_imod
                        ->insert([
                            'JENIS_TARIF' => $t['JENIS_TARIF'], 'KODE_TARIF' => $t['KODE_TARIF'], 'NILAI_BAYAR' => $t['NILAI_BAYAR'], 'NILAI_FASILITAS' => $t['NILAI_FASILITAS'], 'KODE_FASILITAS' => $t['KODE_FASILITAS'], 'TARIF_FASILITAS' => $t['TARIF_FASILITAS'], 'TARIF' => $t['TARIF'], 'SERI_BAHAN_BAKU' => $t['SERI_BAHAN_BAKU'], 'ID_BAHAN_BAKU' => $ZR_TPB_BAHAN_BAKU, 'ID_BARANG' => $ZR_TPB_BARANG, 'ID_HEADER' => $ZR_TPB_HEADER,
                        ]);
                    $iteration++;
                }
            }
        }
        ##N
        $this->DELV_mod->updatebyVAR(['DLV_POST' => $this->session->userdata('nama'), 'DLV_POSTTM' => $currentDate], ['DLV_ID' => $csj]);
        $myar[] = [
            'cd' => '1', 'msg' => 'Done, check your TPB', 'tpb_barang' => $tpb_barang, 'tpb_bahan_baku' => $tpb_bahan_baku,
        ];
        $this->setPrice(base64_encode($csj));
        die('{"status" : ' . json_encode($myar) . '}');
    }
    public function posting_rm27()
    {
        set_exception_handler([$this, 'exception_handler']);
        set_error_handler([$this, 'log_error']);
        register_shutdown_function([$this, 'fatal_handler']);
        header('Content-Type: application/json');
        ini_set('max_execution_time', '-1');
        $currentDate = date('Y-m-d H:i:s');
        $csj = $this->input->get('insj');
        $rsaktivasi = $this->AKTIVASIAPLIKASI_imod->selectAll();
        $cztujuanpengiriman = '';
        $consignee = '';
        $czinvoice = '';
        $ccustdate = '';
        $czdocbctype = '';
        $nomoraju = '';
        $deliveryDescription = '';

        $czkantortujuan = '';
        $cz_KODE_JENIS_TPB = '';
        $cz_KODE_TUJUAN_TPB = '';
        $czidpenerima = '';
        $cznmpenerima = '';
        $czalamatpenerima = '';
        $czizinpenerima = '';
        $cznamapengangkut = '';
        $cznomorpolisi = '';

        $czinvoicedt = '';
        $locfrom = '';
        $rs_head_dlv = $this->DELV_mod->select_for_rm_h($csj);
        foreach ($rs_head_dlv as $r) {
            $cztujuanpengiriman = $r['DLV_PURPOSE'];
            $consignee = $r['DLV_CONSIGN'];
            $czinvoice = trim($r['DLV_INVNO']);
            $ccustdate = $r['DLV_BCDATE'];
            $czdocbctype = $r['DLV_BCTYPE'];
            $nomoraju = $r['DLV_NOAJU'];
            $locfrom = $r['DLV_LOCFR'];
            $deliveryDescription = $r['DLV_DSCRPTN'];
            $czcurrency = trim($r['MCUS_CURCD']);

            $czkantortujuan = $r['DLV_DESTOFFICE'];
            $cz_KODE_JENIS_TPB = $r['DLV_ZJENIS_TPB_ASAL'];
            $cz_KODE_TUJUAN_TPB = $r['DLV_ZJENIS_TPB_TUJUAN'];
            $czidpenerima = str_replace([".", "-"], "", $r['MCUS_TAXREG']);

            $cznmpenerima = $r['MDEL_ZNAMA'];
            $czalamatpenerima = $r['MDEL_ADDRCUSTOMS'];
            $czizinpenerima = $r['MDEL_ZSKEP'];

            $cznamapengangkut = $r['MSTTRANS_TYPE'];
            $cznomorpolisi = $r['DLV_TRANS'];

            $czinvoicedt = $r['DLV_INVDT'];
        }
        if ($this->ZRPSTOCK_mod->check_Primary(['RPSTOCK_REMARK' => $csj]) == 0) {
            $myar[] = ['cd' => 0, 'msg' => 'Please book EXBC first'];
            die('{"status" : ' . json_encode($myar) . '}');
        }
        if ($cztujuanpengiriman == '-') {
            $myar[] = ['cd' => 0, 'msg' => 'please set TUJUAN PENGIRIMAN (' . $cztujuanpengiriman . ')'];
            die('{"status" : ' . json_encode($myar) . '}');
        }

        if ($consignee == '-') {
            $myar[] = ['cd' => 0, 'msg' => 'please set consignment (' . $consignee . ')'];
            die('{"status" : ' . json_encode($myar) . '}');
        }

        if ($czinvoice == '' && $locfrom != 'PSIEQUIP') {
            $myar[] = ['cd' => 0, 'msg' => 'please add invoice number (' . $czinvoice . ') custdate (' . $ccustdate . ') consign (' . $consignee . ')'];
            die('{"status" : ' . json_encode($myar) . '}');
        }

        if ($czdocbctype == '-') {
            $myar[] = ['cd' => 0, 'msg' => 'please select bc type!'];
            die('{"status" : ' . json_encode($myar) . '}');
        }

        if (strlen($nomoraju) != 6) {
            $myar[] = ['cd' => 0, 'msg' => 'NOMOR AJU is not valid, please re-check (' . $nomoraju . ')'];
            die('{"status" : ' . json_encode($myar) . '}');
        }

        $ocustdate = date_create($ccustdate);
        $cz_ymd = date_format($ocustdate, 'Ymd');

        $czidpengusaha = '';
        $cznmpengusaha = '';
        $czalamatpengusaha = '';
        $czizinpengusaha = '';
        foreach ($rsaktivasi as $r) {
            $czkantorasal = $r['KPPBC'];
            $czidmodul = substr('00000' . $r['ID_MODUL'], -6);
            $czidmodul_asli = $r['ID_MODUL'];
            $czidpengusaha = $r['NPWP'];
            $cznmpengusaha = $r['NAMA_PENGUSAHA'];
            $czalamatpengusaha = $r['ALAMAT_PENGUSAHA'];
            $czizinpengusaha = $r['NOMOR_SKEP'];
        }

        $cnoaju = substr($czkantorasal, 0, 4) . $czdocbctype . $czidmodul . $cz_ymd . $nomoraju;
        if ($this->TPB_HEADER_imod->check_Primary(['NOMOR_AJU' => $cnoaju]) > 0) {
            $myar[] = ['cd' => 0, 'msg' => 'the NOMOR AJU is already posted'];
            die('{"status" : ' . json_encode($myar) . '}');
        }

        if (strlen($ccustdate) != 10) {
            $myar[] = ['cd' => 0, 'msg' => 'please enter valid customs date!'];
            die('{"status" : ' . json_encode($myar) . '}');
        }

        if ($this->DELV_mod->check_Primary(['DLV_ID' => $csj]) == 0) {
            $myar[] = ['cd' => 0, 'msg' => 'DO is not found'];
            die('{"status" : ' . json_encode($myar) . '}');
        }

        $rspkg = $this->DELV_mod->select_pkg($csj);
        $cz_JUMLAH_KEMASAN = 0;
        $netweight_represent = 0;
        $cz_h_BRUTO = 0;
        $cz_h_NETTO = 0;
        $initialCodePKG = '';
        foreach ($rspkg as $r) {
            if ($r['DLV_PKG_NWG'] > 0) {
                $netweight_represent = $r['DLV_PKG_NWG'];
                $cz_h_BRUTO += $r['DLV_PKG_GWG'];
                $cz_h_NETTO += $r['DLV_PKG_NWG'];
            }
            if ($r['DLV_PKG_MEASURE']) {
                $initialCodePKG = strpos(strtoupper($r['DLV_PKG_MEASURE']), 'PALLET') !== false ? 'PX' : 'BX';
                $cz_JUMLAH_KEMASAN++;
            }
        }

        $flg_sell = strpos($deliveryDescription, 'DIKEMBALIKAN') !== false ? false : true;

        $rscurr = $this->MEXRATE_mod->selectfor_posting_in([$ccustdate], [$czcurrency]);
        if ($flg_sell) {
            if (count($rscurr) == 0) {
                $myar[] = ["cd" => "0", "msg" => "Please fill exchange rate data !"];
                die('{"status":' . json_encode($myar) . '}');
            } else {
                foreach ($rscurr as $r) {
                    $czharga_matauang = $r->MEXRATE_VAL;
                    break;
                }
            }
        }

        #HEADER_HARGA
        $cz_h_CIF_FG = 0;
        $cz_h_HARGA_PENYERAHAN_FG = 0;

        #check price source
        $rsrmmanualDO = $this->DLVRMDOC_mod->select_invoice_posting($csj);
        $rsrm_fromSO = $this->DLVRMSO_mod->select_invoice($csj);
        $SERI_BARANG = 1;
        if (count($rsrmmanualDO) && count($rsrm_fromSO) <= 0) {
            $DATA_POST_TYPE = 1;
        } else {
            $rsrm_fromSO = $this->DLVRMSO_mod->select_invoice($csj);
            if (count($rsrm_fromSO)) {
                $DATA_POST_TYPE = 2;
            } else {
                $rsrm_fromDO = $this->DELV_mod->select_det_byid_rm($csj);
                if (count($rsrm_fromDO)) {
                    $DATA_POST_TYPE = 3;
                } else {
                    $myar[] = ["cd" => "0", "msg" => "there is no data source"];
                    die('{"status":' . json_encode($myar) . '}');
                }
            }
        }
        $tpb_bahan_baku = [];
        $rs_do = $this->DELV_mod->select_for_do_rm_rtn_v1($csj);
        $rs_xbc = $this->RCV_mod->select_for_rmrtn_bytxid($csj);
        $IncDateList = [];
        $IncCRList = []; #Currency
        $IncDateCR_FLGList = []; #Currency
        $resumeXBCDoc = [];
        foreach ($rs_do as $r) {
            $r['PLOTQT'] = 0;
            foreach ($rs_xbc as &$x) {
                if (
                    $r['DLVRMDOC_ITMID'] == $x['RCV_ITMCD']
                    && $r['DLVRMDOC_AJU'] == $x['RCV_RPNO']
                    && $r['DLVRMDOC_DO'] == $x['RCV_DONO']
                    && $x['RCV_QTY'] > 0
                    && $r['PLOTQT'] != $r['DLVRMDOC_ITMQT']
                ) {
                    $reqbal = $r['DLVRMDOC_ITMQT'] - $r['PLOTQT'];
                    $useqt = 0;
                    if ($reqbal > $x['RCV_QTY']) {
                        $useqt = $x['RCV_QTY'];
                        $r['PLOTQT'] += $x['RCV_QTY'];
                        $x['RCV_QTY'] = 0;
                    } else {
                        $useqt = $reqbal;
                        $r['PLOTQT'] += $reqbal;
                        $x['RCV_QTY'] -= $reqbal;
                    }
                    $thecif = number_format($x['RCV_PRPRC'] * $useqt, 2, ".", "");
                    $tpb_bahan_baku[] = [
                        'KODE_JENIS_DOK_ASAL' => $x['RCV_BCTYPE'], 'NOMOR_DAFTAR_DOK_ASAL' => $r['DLVRMDOC_NOPEN'], 'TANGGAL_DAFTAR_DOK_ASAL' => $x['RCV_BCDATE'], 'KODE_KANTOR' => $x['RCV_KPPBC'], 'NOMOR_AJU_DOK_ASAL' => strlen($r['DLVRMDOC_AJU']) == 6 ? substr('000000000000000000000000', 0, 26) : $r['DLVRMDOC_AJU'], 'SERI_BARANG_DOK_ASAL' => empty($x['RCV_ZNOURUT']) ? 0 : $x['RCV_ZNOURUT'], 'SPESIFIKASI_LAIN' => null, 'CIF' => $thecif, 'HARGA_PENYERAHAN' => 0, 'KODE_BARANG' => $r['DLVRMDOC_ITMID'], 'KODE_STATUS' => "03", 'POS_TARIF' => $x['RCV_HSCD'], 'URAIAN' => rtrim($r['DLV_ITMD1']), 'TIPE' => rtrim($r['DLV_ITMSPTNO']), 'JUMLAH_SATUAN' => $useqt, 'SERI_BAHAN_BAKU' => 1, 'JENIS_SATUAN' => ($r['MITM_STKUOM'] == 'PCS') ? 'PCE' : $r['MITM_STKUOM'], 'KODE_ASAL_BAHAN_BAKU' => ($x['RCV_BCTYPE'] == '27' || $x['RCV_BCTYPE'] == '23') ? '0' : '1', 'RBM' => $x['RCV_BM'] * 1, 'CURRENCY' => $x['MSUP_SUPCR'], 'PPN' => 11, //bu gusti, terkait peraturan 1 april
                    ];
                    $IncDateList[] = $x['RCV_BCDATE'];
                    $IncCRList[] = $x['MSUP_SUPCR'];
                    $IncDateCR_FLGList[] = 0;
                    if ($r['DLVRMDOC_ITMQT'] == $r['PLOTQT']) {
                        break;
                    }
                }
            }
            unset($x);
        }
        unset($r);
        $tpb_barang = [];
        $no = 1;
        $rscurr = $this->MEXRATE_mod->selectfor_posting_in($IncDateList, $IncCRList);
        $listcount = count($IncDateCR_FLGList);
        foreach ($rscurr as $r) {
            for ($i = 0; $i < $listcount; $i++) {
                if ($r->MEXRATE_CURR == $IncCRList[$i] && $r->MEXRATE_DT == $IncDateList[$i]) {
                    $IncDateCR_FLGList[$i] = 1;
                }
            }
        }

        #validate exchange rate for incoming date
        for ($i = 0; $i < $listcount; $i++) {
            if ($IncDateCR_FLGList[$i] == 0) {
                $dar = ["cd" => "0", "msg" => "Please fill exchange rate data for " . $IncCRList[$i] . " on " . $IncDateList[$i] . " !"];
                $myar[] = $dar;
                die('{"status":' . json_encode($myar) . '}');
            }
        }
        #end

        foreach ($tpb_bahan_baku as &$r) {
            if ($cztujuanpengiriman != '7') {
                foreach ($rscurr as $c) {
                    if ($r['CURRENCY'] == $c->MEXRATE_CURR && $r['TANGGAL_DAFTAR_DOK_ASAL'] == $c->MEXRATE_DT) {
                        if ($c->MEXRATE_CURR == 'RPH') {
                            $czharga_matauang = 1;
                        } else {
                            $czharga_matauang = $c->MEXRATE_VAL;
                        }
                        break;
                    }
                }
            }
            $r['HARGA_PENYERAHAN'] = $r['CIF'] * $czharga_matauang;
            $tpb_barang[] = [
                'KODE_BARANG' => $r['KODE_BARANG'], 'POS_TARIF' => $r['POS_TARIF'], 'URAIAN' => $r['URAIAN'], 'TIPE' => $r['TIPE'], 'JUMLAH_SATUAN' => $r['JUMLAH_SATUAN'], 'KODE_SATUAN' => $r['JENIS_SATUAN'], 'NETTO' => $no == 1 ? $netweight_represent : 0, 'CIF' => $r['CIF'], 'HARGA_PENYERAHAN' => $r['CIF'] * $czharga_matauang, 'SERI_BARANG' => $SERI_BARANG, 'KODE_STATUS' => '02', 'JUMLAH_BAHAN_BAKU' => 1, 'KODE_JENIS_DOK_ASAL' => $r['KODE_JENIS_DOK_ASAL'], 'NOMOR_DAFTAR_DOK_ASAL' => $r['NOMOR_DAFTAR_DOK_ASAL'], 'TANGGAL_DAFTAR_DOK_ASAL' => $r['TANGGAL_DAFTAR_DOK_ASAL'], 'KODE_KANTOR' => $r['KODE_KANTOR'], 'NOMOR_AJU_DOK_ASAL' => $r['NOMOR_AJU_DOK_ASAL'], 'SERI_BARANG_DOK_ASAL' => $r['SERI_BARANG_DOK_ASAL'], 'KODE_STATUS' => $r['KODE_STATUS'], 'SERI_BAHAN_BAKU' => 1, 'JENIS_SATUAN' => $r['JENIS_SATUAN'], 'KODE_ASAL_BAHAN_BAKU' => $r['KODE_ASAL_BAHAN_BAKU'], 'SPESIFIKASI_LAIN' => null, 'RBM' => $r['RBM'], 'CURRENCY' => $r['CURRENCY'],
            ];
            $no++;
            $SERI_BARANG++;
        }
        unset($r);

        $cz_h_JUMLAH_BARANG = count($tpb_barang);
        foreach ($tpb_barang as $r) {
            $cz_h_CIF_FG += $r['CIF'];
            $cz_h_HARGA_PENYERAHAN_FG += $r['HARGA_PENYERAHAN'];
        }

        foreach ($tpb_bahan_baku as $r) {
            $isfound = false;
            foreach ($resumeXBCDoc as $c) {
                if (
                    $r['NOMOR_DAFTAR_DOK_ASAL'] == $c['NOPEN'] && $r['TANGGAL_DAFTAR_DOK_ASAL'] == $c['TGLPEN']
                    && $r['KODE_JENIS_DOK_ASAL'] == $c['BCTYPE']
                ) {
                    $isfound = true;
                    break;
                }
            }
            if (!$isfound) {
                $resumeXBCDoc[] = [
                    'NOPEN' => $r['NOMOR_DAFTAR_DOK_ASAL'], 'TGLPEN' => $r['TANGGAL_DAFTAR_DOK_ASAL'], 'BCTYPE' => $r['KODE_JENIS_DOK_ASAL'],
                ];
            }
        }

        #BAHAN BAKU TARIF
        $tpb_bahan_baku_tarif = [];
        foreach ($tpb_bahan_baku as $r) {
            $tpb_bahan_baku_tarif[] = [
                'JENIS_TARIF' => 'BM', 'KODE_TARIF' => 1, 'NILAI_BAYAR' => 0, 'NILAI_FASILITAS' => 0, 'KODE_FASILITAS' => 2, 'TARIF_FASILITAS' => 100, 'TARIF' => $r['RBM'], 'SERI_BAHAN_BAKU' => $r['SERI_BAHAN_BAKU'], 'RITEMCD' => $r['KODE_BARANG'], 'RITEMQT' => $r['JUMLAH_SATUAN'],
            ];
            $tpb_bahan_baku_tarif[] = [
                'JENIS_TARIF' => 'PPN', 'KODE_TARIF' => 1, 'NILAI_BAYAR' => 0, 'NILAI_FASILITAS' => 0, 'KODE_FASILITAS' => 2, 'TARIF_FASILITAS' => 100, 'TARIF' => $r['PPN'], 'SERI_BAHAN_BAKU' => $r['SERI_BAHAN_BAKU'], 'RITEMCD' => $r['KODE_BARANG'], 'RITEMQT' => $r['JUMLAH_SATUAN'],
            ];
            $tpb_bahan_baku_tarif[] = [
                'JENIS_TARIF' => 'PPH', 'KODE_TARIF' => 1, 'NILAI_BAYAR' => 0, 'NILAI_FASILITAS' => 0, 'KODE_FASILITAS' => 2, 'TARIF_FASILITAS' => 100, 'TARIF' => 2.5, 'SERI_BAHAN_BAKU' => $r['SERI_BAHAN_BAKU'], 'RITEMCD' => $r['KODE_BARANG'], 'RITEMQT' => $r['JUMLAH_SATUAN'],
            ];
        }
        #END

        $tpb_header = [
            "NOMOR_AJU" => $cnoaju, "KODE_KANTOR" => $czkantorasal, "KODE_KANTOR_TUJUAN" => $czkantortujuan,
            "KODE_JENIS_TPB" => $cz_KODE_JENIS_TPB, "KODE_TUJUAN_TPB" => $cz_KODE_TUJUAN_TPB, "KODE_TUJUAN_PENGIRIMAN" => $cztujuanpengiriman,

            "KODE_ID_PENGUSAHA" => "1", "ID_PENGUSAHA" => $czidpengusaha, "NAMA_PENGUSAHA" => $cznmpengusaha,
            "ALAMAT_PENGUSAHA" => $czalamatpengusaha, "NOMOR_IJIN_TPB" => $czizinpengusaha,

            "KODE_ID_PENERIMA_BARANG" => "1", "ID_PENERIMA_BARANG" => $czidpenerima,
            "NAMA_PENERIMA_BARANG" => $cznmpenerima, "ALAMAT_PENERIMA_BARANG" => $czalamatpenerima,
            "NOMOR_IJIN_TPB_PENERIMA" => $czizinpenerima,

            "KODE_VALUTA" => $czcurrency, "CIF" => round($cz_h_CIF_FG, 2), "HARGA_PENYERAHAN" => $cz_h_HARGA_PENYERAHAN_FG,

            "NAMA_PENGANGKUT" => $cznamapengangkut, "NOMOR_POLISI" => $cznomorpolisi,

            "BRUTO" => $cz_h_BRUTO, "NETTO" => $cz_h_NETTO, "JUMLAH_BARANG" => $cz_h_JUMLAH_BARANG,

            "KOTA_TTD" => "CIKARANG", "TANGGAL_TTD" => $ccustdate,

            "KODE_DOKUMEN_PABEAN" => $czdocbctype, "ID_MODUL" => $czidmodul_asli, "VERSI_MODUL" => null,

            "VOLUME" => 0, "KODE_STATUS" => '00',
        ];

        $tpb_kemasan = [];
        $tpb_kemasan[] = ["JUMLAH_KEMASAN" => $cz_JUMLAH_KEMASAN, "KODE_JENIS_KEMASAN" => $initialCodePKG];

        $tpb_dokumen = [];
        $tpb_dokumen[] = ["KODE_JENIS_DOKUMEN" => "380", "NOMOR_DOKUMEN" => $czinvoice, "TANGGAL_DOKUMEN" => $czinvoicedt, "TIPE_DOKUMEN" => "02", "SERI_DOKUMEN" => 1]; //invoice
        $tpb_dokumen[] = ["KODE_JENIS_DOKUMEN" => "217", "NOMOR_DOKUMEN" => $czinvoice, "TANGGAL_DOKUMEN" => $czinvoicedt, "TIPE_DOKUMEN" => "02", "SERI_DOKUMEN" => 2]; //packing list
        $tpb_dokumen[] = ["KODE_JENIS_DOKUMEN" => "640", "NOMOR_DOKUMEN" => $csj, "TANGGAL_DOKUMEN" => $ccustdate, "TIPE_DOKUMEN" => "02", "SERI_DOKUMEN" => 3]; //surat jalan
        $seridokumen = 4;
        foreach ($resumeXBCDoc as $n) {
            $tpb_dokumen[] = ["KODE_JENIS_DOKUMEN" => $n['BCTYPE'], "NOMOR_DOKUMEN" => $n['NOPEN'], "TANGGAL_DOKUMEN" => $n['TGLPEN'], "TIPE_DOKUMEN" => "02", "SERI_DOKUMEN" => $seridokumen];
            $seridokumen++;
        }

        #INSERT CEISA
        ##1 TPB HEADER
        $ZR_TPB_HEADER = $this->TPB_HEADER_imod->insert($tpb_header);
        ##N

        ##2 TPB DOKUMEN
        foreach ($tpb_dokumen as &$n) {
            $n['ID_HEADER'] = $ZR_TPB_HEADER;
        }
        unset($n);
        $ZR_TPB_DOKUMEN = $this->TPB_DOKUMEN_imod->insertb($tpb_dokumen);
        ##N

        ##3 TPB KEMASAN
        foreach ($tpb_kemasan as &$n) {
            $n['ID_HEADER'] = $ZR_TPB_HEADER;
        }
        unset($n);
        $ZR_TPB_KEMASAN = $this->TPB_KEMASAN_imod->insertb($tpb_kemasan);
        ##N

        ##4 TPB BARANG
        foreach ($tpb_barang as &$n) {
            $n['ID_HEADER'] = $ZR_TPB_HEADER;
        }
        unset($n);
        ##N

        ##4 TPB BARANG & BAHAN BAKU
        foreach ($tpb_barang as $n) {
            $_barang = [
                'KODE_BARANG' => substr($n['KODE_BARANG'], 0, 2) == 'PM' ? '-' : $n['KODE_BARANG'], 'POS_TARIF' => $n['POS_TARIF'], 'URAIAN' => $n['URAIAN'], 'TIPE' => $n['TIPE'], 'JUMLAH_SATUAN' => $n['JUMLAH_SATUAN'], 'KODE_SATUAN' => $n['KODE_SATUAN'], 'NETTO' => $n['NETTO'], 'CIF' => $n['CIF'], 'HARGA_PENYERAHAN' => $n['HARGA_PENYERAHAN'], 'SERI_BARANG' => $n['SERI_BARANG'], 'KODE_STATUS' => $n['KODE_STATUS'], 'JUMLAH_BAHAN_BAKU' => $n['JUMLAH_BAHAN_BAKU'], 'ID_HEADER' => $n['ID_HEADER'],
            ];
            $ZR_TPB_BARANG = $this->TPB_BARANG_imod->insert($_barang);
            $ZR_TPB_BAHAN_BAKU = $this->TPB_BAHAN_BAKU_imod
                ->insert([
                    'KODE_JENIS_DOK_ASAL' => $n['KODE_JENIS_DOK_ASAL'], 'NOMOR_DAFTAR_DOK_ASAL' => $n['NOMOR_DAFTAR_DOK_ASAL'], 'TANGGAL_DAFTAR_DOK_ASAL' => $n['TANGGAL_DAFTAR_DOK_ASAL'], 'KODE_KANTOR' => $n['KODE_KANTOR'], 'NOMOR_AJU_DOK_ASAL' => $n['NOMOR_AJU_DOK_ASAL'], 'SERI_BARANG_DOK_ASAL' => $n['SERI_BARANG_DOK_ASAL'], 'SPESIFIKASI_LAIN' => $n['SPESIFIKASI_LAIN'], 'CIF' => ($n['CIF']), 'HARGA_PENYERAHAN' => $n['HARGA_PENYERAHAN'], 'KODE_BARANG' => substr($n['KODE_BARANG'], 0, 2) == 'PM' ? '-' : $n['KODE_BARANG'], 'KODE_STATUS' => $n['KODE_STATUS'], 'POS_TARIF' => $n['POS_TARIF'], 'URAIAN' => $n['URAIAN'], 'TIPE' => $n['TIPE'], 'JUMLAH_SATUAN' => $n['JUMLAH_SATUAN'], 'JENIS_SATUAN' => $n['JENIS_SATUAN'], 'KODE_ASAL_BAHAN_BAKU' => $n['KODE_ASAL_BAHAN_BAKU'], 'NDPBM' => 0, 'NETTO' => 0, 'SERI_BAHAN_BAKU' => $n['SERI_BAHAN_BAKU'], 'SERI_BARANG' => $n['SERI_BARANG'], 'ID_BARANG' => $ZR_TPB_BARANG, 'ID_HEADER' => $ZR_TPB_HEADER,
                ]);
            $iteration = 0;
            foreach ($tpb_bahan_baku_tarif as $t) {
                if (
                    $n['KODE_BARANG'] == $t['RITEMCD'] && $n['JUMLAH_SATUAN'] == $t['RITEMQT']
                    && $n['SERI_BAHAN_BAKU'] == $t['SERI_BAHAN_BAKU']
                ) {
                    if ($iteration == 3) {
                        break;
                    }
                    $this->TPB_BAHAN_BAKU_TARIF_imod
                        ->insert([
                            'JENIS_TARIF' => $t['JENIS_TARIF'], 'KODE_TARIF' => $t['KODE_TARIF'], 'NILAI_BAYAR' => $t['NILAI_BAYAR'], 'NILAI_FASILITAS' => $t['NILAI_FASILITAS'], 'KODE_FASILITAS' => $t['KODE_FASILITAS'], 'TARIF_FASILITAS' => $t['TARIF_FASILITAS'], 'TARIF' => $t['TARIF'], 'SERI_BAHAN_BAKU' => $t['SERI_BAHAN_BAKU'], 'ID_BAHAN_BAKU' => $ZR_TPB_BAHAN_BAKU, 'ID_BARANG' => $ZR_TPB_BARANG, 'ID_HEADER' => $ZR_TPB_HEADER,
                        ]);
                    $iteration++;
                }
            }
        }
        ##N
        $this->DELV_mod->updatebyVAR(['DLV_POST' => $this->session->userdata('nama'), 'DLV_POSTTM' => $currentDate], ['DLV_ID' => $csj]);
        $myar[] = [
            'cd' => '1', 'msg' => 'Done, check your TPB', 'tpb_barang' => $tpb_barang, 'tpb_bahan_baku' => $tpb_bahan_baku,
        ];
        $this->setPrice(base64_encode($csj));
        $this->gotoque($csj);
        die('{"status" : ' . json_encode($myar) . '}');
    }

    public function dispose_draft()
    {
        header('Content-Type: application/json');
        // $rsRM = $this->ITH_mod->select_fordispose_v2(['ITH_ITMCD' => ''], '2022-12-02');
        $rsRM = $this->DisposeDraft_mod->select_arwh9sc('2022-12-02');

        $itemcd = [];
        foreach ($rsRM as $r) {
            if (!in_array($r['PART_CODE'], $itemcd)) {
                $itemcd[] = $r['PART_CODE'];
            }
        }
        $itemcdstr = "'" . implode("','", $itemcd) . "'";
        $rsRCV = $this->RCV_mod->select_raw_balanceEXBC($itemcdstr);
        $rsfix = [];
        #FIFO
        foreach ($rsRM as &$r) {
            $r['PLOTQTY'] = 0;
            foreach ($rsRCV as &$v) {
                $balneed = $r['QTY'] - $r['PLOTQTY'];
                if ($balneed > 0 && $r['PART_CODE'] === $v['ITMNUM'] && $v['STK']) {
                    $fixqty = $balneed;
                    if ($balneed > $v['STK']) {
                        $fixqty = $v['STK'];
                        $r['PLOTQTY'] += $v['STK'];
                        $v['STK'] = 0;
                    } else {
                        $r['PLOTQTY'] += $balneed;
                        $v['STK'] -= $balneed;
                    }
                    $rsfix[] = [
                        'NOAJU' => $v['RPSTOCK_NOAJU'], 'NOPEN' => $v['RPSTOCK_BCNUM'], 'DO' => $v['RPSTOCK_DOC'], 'TGLPEN' => $v['RCV_BCDATE'], 'ITMNUM' => $v['ITMNUM'], 'PRICE' => substr($v['PRICE'], 0, 1) == '.' ? '0' . $v['PRICE'] : $v['PRICE'], 'QTY' => $fixqty, 'BCTYPE' => $v['RCV_BCTYPE'],
                    ];
                    if ($r['QTY'] === $r['PLOTQTY']) {
                        break;
                    }

                }
            }
            unset($v);
        }
        unset($r);

        die(json_encode([
            'rsRM' => $rsRM, 'rsfix' => $rsfix,
        ]));
    }

    public function dispose_lock_one()
    {
        header('Content-Type: application/json');
        ini_set('max_execution_time', '-1');
        $rsRM = $this->DisposeDraft_mod->select_arwh9sc('2022-12-02');
        $czdocbctype = '27';
        $cztujuanpengiriman = '1';
        $csj = $this->input->post('doc'); //DISD221202_final1_sup1
        $rsallitem_cd = [];
        $rsallitem_qty = [];
        $rsallitem_qtyplot = [];
        $responseResume = [];

        $rsallitem_cd[] = $this->input->post('itemcode');
        $rsallitem_qty[] = $this->input->post('qty');
        $rsallitem_qtyplot[] = 0;

        $ccustdate = $this->input->post('date');
        $count_rsallitem = count($rsallitem_cd);
        $rstemp = $this->inventory_getstockbc_v2($czdocbctype, $cztujuanpengiriman, $csj, $rsallitem_cd, $rsallitem_qty, [], $ccustdate);
        $rsbc = json_decode($rstemp);
        if (!is_null($rsbc)) {
            if (count($rsbc) > 0) {
                foreach ($rsbc as &$o) {
                    foreach ($o->data as &$v) {
                        #resume respone
                        $isfound = false;
                        foreach ($responseResume as &$n) {
                            if ($n['ITEM'] == $v->BC_ITEM) {
                                $n['BALRES'] += $v->BC_QTY;
                                $isfound = true;
                            }
                        }
                        unset($n);
                        if (!$isfound) {
                            $responseResume[] = ['ITEM' => $v->BC_ITEM, 'BALRES' => $v->BC_QTY];
                        }
                        #end
                    }
                    unset($v);
                }
                unset($o);
            } else {
            }
        } else {
        }
        #CHECK IS REQ!=RES
        $listNeedExBC = []; #outstanding list
        for ($i = 0; $i < $count_rsallitem; $i++) {
            foreach ($responseResume as &$r) {
                if ($rsallitem_cd[$i] === $r['ITEM']) {
                    $bal = $rsallitem_qty[$i] - $rsallitem_qtyplot[$i];
                    if ($bal > $r['BALRES']) {
                        $rsallitem_qtyplot[$i] += $r['BALRES'];
                        $r['BALRES'] = 0;
                    } else {
                        $rsallitem_qtyplot[$i] += $bal;
                        $r['BALRES'] -= $bal;
                    }
                    if ($rsallitem_qty[$i] == $rsallitem_qtyplot[$i]) {
                        break;
                    }
                }
            }
            unset($r);
            $bal = $rsallitem_qty[$i] - $rsallitem_qtyplot[$i];
            if ($bal) {
                $listNeedExBC[] = ['ITMCD' => $rsallitem_cd[$i], 'QTY' => $bal, 'LOTNO' => '?'];
            }
        }
        if (count($listNeedExBC) > 0) {
            $myar[] = ['cd' => 110, 'msg' => 'EX-BC for ' . count($listNeedExBC) . ' item(s) is not found. ', "doctype" => $czdocbctype, "tujuankirim" => $cztujuanpengiriman];
            die('{"status" : ' . json_encode($myar) . ', "data":' . json_encode($listNeedExBC)
                . ',"rawdata":' . json_encode($rstemp)
                . ',"itemsend":' . json_encode($rsallitem_cd)
                . ',"itemqtysend":' . json_encode($rsallitem_qty)
                . ',"responresume":' . json_encode($responseResume) . '}');
        } else {
            $myar[] = ['cd' => 110, 'msg' => 'OK ', "doctype" => $czdocbctype, "tujuankirim" => $cztujuanpengiriman];
            die('{"status" : ' . json_encode($myar) . ', "data":' . json_encode($listNeedExBC)
                . ',"rawdata":' . json_encode($rstemp)
                . ',"itemsend":' . json_encode($rsallitem_cd)
                . ',"itemqtysend":' . json_encode($rsallitem_qty)
                . ',"responresume":' . json_encode($responseResume) . '}');
        }
    }

    public function dispose_lock()
    {
        header('Content-Type: application/json');
        ini_set('max_execution_time', '-1');
        // $rsRM = $this->DisposeDraft_mod->select_resume_rm_additional1();
        $rsRM = $this->DisposeDraft_mod->select_arwh9sc('2022-12-02');
        $czdocbctype = '27';
        $cztujuanpengiriman = '1';
        $csj = 'DISD221202_final1';
        $rsallitem_cd = [];
        $rsallitem_qty = [];
        $rsallitem_qtyplot = [];
        $responseResume = [];
        foreach ($rsRM as $r) {
            $rsallitem_cd[] = $r['PART_CODE'];
            $rsallitem_qty[] = $r['QTY'];
            $rsallitem_qtyplot[] = 0;
        }
        $ccustdate = '2023-01-21';
        $count_rsallitem = count($rsallitem_cd);
        $rstemp = $this->inventory_getstockbc_v2($czdocbctype, $cztujuanpengiriman, $csj, $rsallitem_cd, $rsallitem_qty, [], $ccustdate);
        $rsbc = json_decode($rstemp);
        if (!is_null($rsbc)) {
            if (count($rsbc) > 0) {
                foreach ($rsbc as &$o) {
                    foreach ($o->data as &$v) {
                        #resume respone
                        $isfound = false;
                        foreach ($responseResume as &$n) {
                            if ($n['ITEM'] == $v->BC_ITEM) {
                                $n['BALRES'] += $v->BC_QTY;
                                $isfound = true;
                            }
                        }
                        unset($n);
                        if (!$isfound) {
                            $responseResume[] = ['ITEM' => $v->BC_ITEM, 'BALRES' => $v->BC_QTY];
                        }
                        #end
                    }
                    unset($v);
                }
                unset($o);
            } else {
                // $myar[] = ["cd" => "0", "msg" => "Could not find exbc, please contact admin !", "api_respon" => $rstemp];
                // $this->inventory_cancelDO($csj);
                // die('{"status":' . json_encode($myar) . '}');
            }
        } else {
            // $this->inventory_cancelDO($csj);
            // $myar[] = ["cd" => "0", "msg" => "Could not find exbc, please contact admin", "api_respon" => $rstemp];
            // die('{"status":' . json_encode($myar) . '}');
        }
        #CHECK IS REQ!=RES
        $listNeedExBC = []; #outstanding list
        for ($i = 0; $i < $count_rsallitem; $i++) {
            foreach ($responseResume as &$r) {
                if ($rsallitem_cd[$i] === $r['ITEM']) {
                    $bal = $rsallitem_qty[$i] - $rsallitem_qtyplot[$i];
                    if ($bal > $r['BALRES']) {
                        $rsallitem_qtyplot[$i] += $r['BALRES'];
                        $r['BALRES'] = 0;
                    } else {
                        $rsallitem_qtyplot[$i] += $bal;
                        $r['BALRES'] -= $bal;
                    }
                    if ($rsallitem_qty[$i] == $rsallitem_qtyplot[$i]) {
                        break;
                    }
                }
            }
            unset($r);
            $bal = $rsallitem_qty[$i] - $rsallitem_qtyplot[$i];
            if ($bal) {
                $listNeedExBC[] = ['ITMCD' => $rsallitem_cd[$i], 'QTY' => $bal, 'LOTNO' => '?'];
            }
        }
        if (count($listNeedExBC) > 0) {
            $myar[] = ['cd' => 110, 'msg' => 'EX-BC for ' . count($listNeedExBC) . ' item(s) is not found. ', "doctype" => $czdocbctype, "tujuankirim" => $cztujuanpengiriman];
            die('{"status" : ' . json_encode($myar) . ', "data":' . json_encode($listNeedExBC)
                . ',"rawdata":' . json_encode($rstemp)
                . ',"itemsend":' . json_encode($rsallitem_cd)
                . ',"itemqtysend":' . json_encode($rsallitem_qty)
                . ',"responresume":' . json_encode($responseResume) . '}');
        } else {
            $myar[] = ['cd' => 110, 'msg' => 'OK ', "doctype" => $czdocbctype, "tujuankirim" => $cztujuanpengiriman];
            die('{"status" : ' . json_encode($myar) . ', "data":' . json_encode($listNeedExBC)
                . ',"rawdata":' . json_encode($rstemp)
                . ',"itemsend":' . json_encode($rsallitem_cd)
                . ',"itemqtysend":' . json_encode($rsallitem_qty)
                . ',"responresume":' . json_encode($responseResume) . '}');
        }
    }

    public function dispose_fromFG_draft()
    {
        header('Content-Type: application/json');
        $date = '2022-12-02';
        // $rsRM = $this->DisposeDraft_mod->select_resume_fg($date);
        $str = "
        'G31ZJ1YFT41I1P8P',
        'G3R3D802A51I1FQE',
        'G4PUO3QA1YIX16FD',
        ";
        $rsRM = $this->DisposeDraft_mod->select_resume_fg_dedicated($str);
        $itemcd = [];
        foreach ($rsRM as $r) {
            if (!in_array($r['PART_CODE'], $itemcd)) {
                $itemcd[] = $r['PART_CODE'];
            }
        }
        $itemcdstr = "'" . implode("','", $itemcd) . "'";
        $rsRCV = $this->RCV_mod->select_raw_balanceEXBC($itemcdstr);
        $rsfix = [];
        #FIFO
        foreach ($rsRM as &$r) {
            $r['PLOTQTY'] = 0;
            foreach ($rsRCV as &$v) {
                $balneed = $r['QTY'] - $r['PLOTQTY'];
                if ($balneed > 0 && $r['PART_CODE'] === $v['ITMNUM'] && $v['STK']) {
                    $fixqty = $balneed;
                    if ($balneed > $v['STK']) {
                        $fixqty = $v['STK'];
                        $r['PLOTQTY'] += $v['STK'];
                        $v['STK'] = 0;
                    } else {
                        $r['PLOTQTY'] += $balneed;
                        $v['STK'] -= $balneed;
                    }
                    $rsfix[] = [
                        'NOAJU' => $v['RPSTOCK_NOAJU'], 'NOPEN' => $v['RPSTOCK_BCNUM'], 'DO' => $v['RPSTOCK_DOC'], 'TGLPEN' => $v['RCV_BCDATE'], 'ITMNUM' => $v['ITMNUM'], 'PRICE' => substr($v['PRICE'], 0, 1) == '.' ? '0' . $v['PRICE'] : $v['PRICE'], 'QTY' => $fixqty, 'BCTYPE' => $v['RCV_BCTYPE'], 'FG' => $r['ITH_ITMCD'],
                    ];
                    if ($r['QTY'] === $r['PLOTQTY']) {
                        break;
                    }

                }
            }
            unset($v);
        }
        unset($r);

        die(json_encode([
            'rsRM' => $rsRM,
            // , 'rsfix' => $rsfix,
        ]));
    }

    public function dispose_lock_FG()
    {
        header('Content-Type: application/json');
        # Period FG
        // $date = '2022-12-02';
        // $rsRM = $this->DisposeDraft_mod->select_resume_fg($date);

        # dedicated FG
        // $str = "'G31ZJ1YFT41I1P8P',
        // 'G3R3D802A51I1FQE',
        // 'G4PUO3QA1YIX16FD',
        // 'GEXJ3S9TG6IX2SG6',
        // ";
        // $rsRM = $this->DisposeDraft_mod->select_resume_fg_dedicated($str);

        # MAIN SUB FG
        $RSSubOnly = $this->DELV_mod->selectCalculationSubOnlyBySerID([
            'GHSWX6UWE81I1NPI',
            'GHSWX6UWE31I28TO',
            'GHSWX6UWE51II2CQ',
            'GHSWX6UWDZ1I2K1G',
            'GHSWX6UWE71I3EI1',
        ]);
        $RSMaterialOnly = $this->DELV_mod->selectCalculationSerRmOnlyBySerID(['GHSWX6UWE81I1NPI',
            'GHSWX6UWE31I28TO',
            'GHSWX6UWE51II2CQ',
            'GHSWX6UWDZ1I2K1G',
            'GHSWX6UWE71I3EI1']);

        $rsRM = array_merge($RSSubOnly, $RSMaterialOnly);

        $czdocbctype = '27';
        $cztujuanpengiriman = '1';
        $ccustdate = '2023-05-17';
        // $csj = 'DISD2212_FG_AGING';
        $csj = 'DISD2212_FG_ADDITIONAL';
        $rsallitem_cd = [];
        $rsallitem_qty = [];
        $rsallitem_qtyplot = [];
        $responseResume = [];
        foreach ($rsRM as $r) {
            $isfound = false;
            for ($i = 0; $i < count($rsallitem_cd); $i++) {
                if ($rsallitem_cd[$i] === $r['PART_CODE']) {
                    $rsallitem_qty[$i] += $r['QTY'];
                    $isfound = true;
                    break;
                }
            }
            if (!$isfound) {
                $rsallitem_cd[] = $r['PART_CODE'];
                $rsallitem_qty[] = $r['QTY'];
                $rsallitem_qtyplot[] = 0;
            }
        }

        $count_rsallitem = count($rsallitem_cd);
        $rstemp = $this->inventory_getstockbc_v2($czdocbctype, $cztujuanpengiriman, $csj, $rsallitem_cd, $rsallitem_qty, [], $ccustdate);
        $rsbc = json_decode($rstemp);
        if (!is_null($rsbc)) {
            if (count($rsbc) > 0) {
                foreach ($rsbc as &$o) {
                    foreach ($o->data as &$v) {
                        #resume respone
                        $isfound = false;
                        foreach ($responseResume as &$n) {
                            if ($n['ITEM'] == $v->BC_ITEM) {
                                $n['BALRES'] += $v->BC_QTY;
                                $isfound = true;
                            }
                        }
                        unset($n);
                        if (!$isfound) {
                            $responseResume[] = ['ITEM' => $v->BC_ITEM, 'BALRES' => $v->BC_QTY];
                        }
                        #end
                    }
                    unset($v);
                }
                unset($o);
            } else {
                $myar[] = ["cd" => "0", "msg" => "Could not find exbc, please contact admin !", "api_respon" => $rstemp];
                // $this->inventory_cancelDO($csj);
                die('{"status":' . json_encode($myar) . '}');
            }
        } else {
            // $this->inventory_cancelDO($csj);
            $myar[] = ["cd" => "0", "msg" => "Could not find exbc, please contact admin", "api_respon" => $rstemp];
            die('{"status":' . json_encode($myar) . '}');
        }
        #CHECK IS REQ!=RES
        $listNeedExBC = []; #outstanding list
        for ($i = 0; $i < $count_rsallitem; $i++) {
            foreach ($responseResume as &$r) {
                if ($rsallitem_cd[$i] === $r['ITEM']) {
                    $bal = $rsallitem_qty[$i] - $rsallitem_qtyplot[$i];
                    if ($bal > $r['BALRES']) {
                        $rsallitem_qtyplot[$i] += $r['BALRES'];
                        $r['BALRES'] = 0;
                    } else {
                        $rsallitem_qtyplot[$i] += $bal;
                        $r['BALRES'] -= $bal;
                    }
                    if ($rsallitem_qty[$i] == $rsallitem_qtyplot[$i]) {
                        break;
                    }
                }
            }
            unset($r);
            $bal = $rsallitem_qty[$i] - $rsallitem_qtyplot[$i];
            if ($bal) {
                $listNeedExBC[] = ['ITMCD' => $rsallitem_cd[$i], 'QTY' => $bal, 'LOTNO' => '?'];
            }
        }
        if (count($listNeedExBC) > 0) {
            $myar[] = ['cd' => 110, 'msg' => 'EX-BC for ' . count($listNeedExBC) . ' item(s) is not found. ', "doctype" => $czdocbctype, "tujuankirim" => $cztujuanpengiriman];
            die('{"status" : ' . json_encode($myar) . ', "data":' . json_encode($listNeedExBC)
                . ',"rawdata":' . json_encode($rstemp)
                . ',"itemsend":' . json_encode($rsallitem_cd)
                . ',"itemqtysend":' . json_encode($rsallitem_qty)
                . ',"responresume":' . json_encode($responseResume) . '}');
        } else {
            $myar[] = ['cd' => 110, 'msg' => 'OK ', "doctype" => $czdocbctype, "tujuankirim" => $cztujuanpengiriman];
            die('{"status" : ' . json_encode($myar) . ', "data":' . json_encode($listNeedExBC)
                . ',"rawdata":' . json_encode($rstemp)
                . ',"itemsend":' . json_encode($rsallitem_cd)
                . ',"itemqtysend":' . json_encode($rsallitem_qty)
                . ',"responresume":' . json_encode($responseResume) . '}');
        }
    }

    public function cancelBookExbcById()
    {
        header('Content-Type: application/json');
        $id = $this->input->post('id');
        $respon = $this->RPSAL_BCSTOCK_mod->updatebyVAR(['deleted_at' => date('Y-m-d H:i:s')], ['id' => $id]);
        $myar = $respon ? ['cd' => 1, 'msg' => 'OK'] : ['cd' => 0, 'msg' => 'sorry could not fix'];
        die(json_encode(['status' => $myar]));
    }

    public function dispose_report()
    {
        header('Content-Type: application/json');
        $date = '2022-03-18';
        $rsRM = $this->DisposeDraft_mod->select_detail_fg($date);
        $rsEXBC = $this->ZRPSTOCK_mod->select_columns_where(
            [
                "rtrim(RPSTOCK_ITMNUM) ITMNUM", "ABS(RPSTOCK_QTY) BCQTY", "RPSTOCK_DOC", "RPSTOCK_NOAJU", "RPSTOCK_BCNUM", "RPSTOCK_BCDATE", "RPSTOCK_BCTYPE",
            ],
            ["RPSTOCK_REMARK" => "DISD2206_FG"]
        );
        $rsfix = [];
        foreach ($rsRM as &$r) {
            $r['QTYPLOT'] = 0;
            foreach ($rsEXBC as &$k) {
                $reqPlot = $r['QTY'] - $r['QTYPLOT'];
                if ($r['PART_CODE'] === $k['ITMNUM'] && $reqPlot > 0 && $k['BCQTY'] > 0) {
                    $theqty = $reqPlot;
                    if ($reqPlot > $k['BCQTY']) {
                        $r['QTYPLOT'] += $k['BCQTY'];
                        $theqty = $k['BCQTY'];
                        $k['BCQTY'] = 0;
                    } else {
                        $k['BCQTY'] -= $reqPlot;
                        $r['QTYPLOT'] += $reqPlot;
                    }
                    $rsfix[] = [
                        'ASSY_DESCRIPTION' => $r['FGDESC'],
                        'ASSY_CODE' => $r['ITH_ITMCD'],
                        'ASSY_QTY' => $r['LQT'],
                        'ASSY_KEY' => $r['ITH_SER'],
                        'ITEM_DESCRIPTION' => $r['RMDESC'],
                        'ITEM_CODE' => $r['SERD2_ITMCD'],
                        'USE' => $r['SERD2_QTY'] / $r['LQT'],
                        'ITEM_QTY' => $theqty,
                        'BCNO' => $k['RPSTOCK_BCNUM'],
                        'BCDATE' => $k['RPSTOCK_BCDATE'],
                    ];
                    if ($r['QTY'] == $r['QTYPLOT']) {
                        break;
                    }
                }
            }
            unset($k);
        }
        unset($r);
        $spreadsheet = new Spreadsheet();
        $sheet = $spreadsheet->getActiveSheet();
        $sheet->setTitle('DISD2206_FG');
        $sheet->setCellValueByColumnAndRow(1, 2, 'Assy Name');
        $sheet->setCellValueByColumnAndRow(2, 2, 'Assy Code');
        $sheet->setCellValueByColumnAndRow(3, 2, 'Assy Qty');
        $sheet->setCellValueByColumnAndRow(4, 2, 'Assy UM');
        $sheet->setCellValueByColumnAndRow(5, 2, 'Part Name');
        $sheet->setCellValueByColumnAndRow(6, 2, 'Part Code');
        $sheet->setCellValueByColumnAndRow(7, 2, 'Use');
        $sheet->setCellValueByColumnAndRow(8, 2, 'Part UM');
        $sheet->setCellValueByColumnAndRow(9, 2, 'BC Qty');
        $sheet->setCellValueByColumnAndRow(10, 2, 'BC Type');
        $sheet->setCellValueByColumnAndRow(11, 2, 'BC No');
        $sheet->setCellValueByColumnAndRow(12, 2, 'BC Date');
        $y = 3;
        $passycode = '';
        $passyname = '';
        $passyqty = '';
        $passyuom = '';
        $tempSER = '';
        foreach ($rsfix as $r) {
            if ($tempSER != $r['ASSYREFF']) {
                $tempSER = $r['ASSYREFF'];
                $passycode = $r['ASSYCODE'];
                $passyname = $r['ASSYDESC'];
                $passyqty = $r['ASSYQT'];
                $passyuom = $r['ASSYUOM'];
            } else {
                $passycode = '';
                $passyname = '';
                $passyqty = '';
                $passyuom = '';
            }
            $sheet->setCellValueByColumnAndRow(1, $y, $passyname);
            $sheet->setCellValueByColumnAndRow(2, $y, $passycode);
            $sheet->setCellValueByColumnAndRow(3, $y, $passyqty);
            $sheet->setCellValueByColumnAndRow(4, $y, $passyuom);
            $sheet->setCellValueByColumnAndRow(5, $y, $r['RMDESC']);
            $sheet->setCellValueByColumnAndRow(6, $y, $r['RMCODE']);
            $sheet->setCellValueByColumnAndRow(7, $y, $r['USE']);
            $sheet->setCellValueByColumnAndRow(8, $y, $r['RMUOM']);
            $sheet->setCellValueByColumnAndRow(9, $y, $r['BCQT']);
            $sheet->setCellValueByColumnAndRow(10, $y, $r['BCTYPE']);
            $sheet->setCellValueByColumnAndRow(11, $y, $r['BCNO']);
            $sheet->setCellValueByColumnAndRow(12, $y, $r['BCDATE']);
            $y++;
        }
        foreach (range('A', 'L') as $v) {
            $sheet->getColumnDimension($v)->setAutoSize(true);
        }
        $sheet->freezePane('A3');
        $stringjudul = "DISD2206_FG";
        $writer = new Xlsx($spreadsheet);
        $filename = $stringjudul; //save our workbook as this file name

        header('Content-Type: application/vnd.ms-excel');
        header('Content-Disposition: attachment;filename="' . $filename . '.xlsx"');
        header('Cache-Control: max-age=0');
        $writer->save('php://output');
        // die(json_encode(['data'=>$rsfix]));
    }
    public function dispose_report2()
    {
        // header('Content-Type: application/json');

        $str = "'F4YG801IVT1UMJWW',
        'FJYG4B3KGAIX1J5D',
        'G7AMR3B8BEMC2K54',
        'G7F0YFI9DYMC2QN7',
        'G7F0YFI9E2MCAR50'";
        $rsRM = $this->DisposeDraft_mod->select_resume_fg_dedicated($str);
        $rsEXBC = $this->ZRPSTOCK_mod->select_columns_where(
            [
                "rtrim(RPSTOCK_ITMNUM) ITMNUM", "ABS(RPSTOCK_QTY) BCQTY", "RPSTOCK_DOC", "RPSTOCK_NOAJU", "RPSTOCK_BCNUM", "RPSTOCK_BCDATE", "RPSTOCK_BCTYPE",
            ],
            ["RPSTOCK_REMARK" => "DISD2212_FG_AGING"]
        );
        $rsfix = [];
        foreach ($rsRM as &$r) {
            $r['QTYPLOT'] = 0;
            foreach ($rsEXBC as &$k) {
                $reqPlot = $r['QTY'] - $r['QTYPLOT'];
                if ($r['PART_CODE'] === $k['ITMNUM'] && $reqPlot > 0 && $k['BCQTY'] > 0) {
                    $theqty = $reqPlot;
                    if ($reqPlot > $k['BCQTY']) {
                        $r['QTYPLOT'] += $k['BCQTY'];
                        $theqty = $k['BCQTY'];
                        $k['BCQTY'] = 0;
                    } else {
                        $k['BCQTY'] -= $reqPlot;
                        $r['QTYPLOT'] += $reqPlot;
                    }
                    $rsfix[] = [
                        'ASSYDESC' => $r['FGDESC'],
                        'ASSYCODE' => $r['ITH_ITMCD'],
                        'ASSYQT' => $r['STOCKQTY'],
                        'ASSYREFF' => $r['ITH_SER'],
                        'ASSYUOM' => $r['FGUOM'],
                        'RMDESC' => $r['RMDESC'],
                        'RMCODE' => $r['PART_CODE'],
                        'USE' => $r['QTY'] / $r['STOCKQTY'],
                        'RMUOM' => $r['RMUOM'],
                        'BCQT' => $theqty,
                        'BCTYPE' => $k['RPSTOCK_BCTYPE'],
                        'BCNO' => $k['RPSTOCK_BCNUM'],
                        'BCDATE' => $k['RPSTOCK_BCDATE'],
                    ];
                    if ($r['QTY'] == $r['QTYPLOT']) {
                        break;
                    }
                }
            }
            unset($k);
        }
        unset($r);
        $spreadsheet = new Spreadsheet();
        $sheet = $spreadsheet->getActiveSheet();
        $sheet->setTitle('DISD2212_FG_AGING');
        $sheet->setCellValueByColumnAndRow(1, 2, 'Assy Name');
        $sheet->setCellValueByColumnAndRow(2, 2, 'Assy Code');
        $sheet->setCellValueByColumnAndRow(3, 2, 'Assy Qty');
        $sheet->setCellValueByColumnAndRow(4, 2, 'Assy UM');
        $sheet->setCellValueByColumnAndRow(5, 2, 'Part Name');
        $sheet->setCellValueByColumnAndRow(6, 2, 'Part Code');
        $sheet->setCellValueByColumnAndRow(7, 2, 'Use');
        $sheet->setCellValueByColumnAndRow(8, 2, 'Part UM');
        $sheet->setCellValueByColumnAndRow(9, 2, 'BC Qty');
        $sheet->setCellValueByColumnAndRow(10, 2, 'BC Type');
        $sheet->setCellValueByColumnAndRow(11, 2, 'BC No');
        $sheet->setCellValueByColumnAndRow(12, 2, 'BC Date');
        $y = 3;
        $passycode = '';
        $passyname = '';
        $passyqty = '';
        $passyuom = '';
        $tempSER = '';
        foreach ($rsfix as $r) {
            if ($tempSER != $r['ASSYREFF']) {
                $tempSER = $r['ASSYREFF'];
                $passycode = $r['ASSYCODE'];
                $passyname = $r['ASSYDESC'];
                $passyqty = $r['ASSYQT'];
                $passyuom = $r['ASSYUOM'];
            } else {
                $passycode = '';
                $passyname = '';
                $passyqty = '';
                $passyuom = '';
            }
            $sheet->setCellValueByColumnAndRow(1, $y, $passyname);
            $sheet->setCellValueByColumnAndRow(2, $y, $passycode);
            $sheet->setCellValueByColumnAndRow(3, $y, $passyqty);
            $sheet->setCellValueByColumnAndRow(4, $y, $passyuom);
            $sheet->setCellValueByColumnAndRow(5, $y, $r['RMDESC']);
            $sheet->setCellValueByColumnAndRow(6, $y, $r['RMCODE']);
            $sheet->setCellValueByColumnAndRow(7, $y, $r['USE']);
            $sheet->setCellValueByColumnAndRow(8, $y, $r['RMUOM']);
            $sheet->setCellValueByColumnAndRow(9, $y, $r['BCQT']);
            $sheet->setCellValueByColumnAndRow(10, $y, $r['BCTYPE']);
            $sheet->setCellValueByColumnAndRow(11, $y, $r['BCNO']);
            $sheet->setCellValueByColumnAndRow(12, $y, $r['BCDATE']);
            $y++;
        }
        foreach (range('A', 'L') as $v) {
            $sheet->getColumnDimension($v)->setAutoSize(true);
        }
        $sheet->freezePane('A3');
        $stringjudul = "DISD2212_FG_AGING";
        $writer = new Xlsx($spreadsheet);
        $filename = $stringjudul; //save our workbook as this file name

        header('Content-Type: application/vnd.ms-excel');
        header('Content-Disposition: attachment;filename="' . $filename . '.xlsx"');
        header('Cache-Control: max-age=0');
        $writer->save('php://output');
        // die(json_encode(['data'=>$rsfix]));
    }

    public function dispose_report202212()
    {
        header('Content-Type: application/json');
        $rsSer = $this->ITH_mod->select_report_dispose202212();
        $rsBooked = $this->ZRPSTOCK_mod->select_booked_dispose_202212();
        $rsFix = [];
        foreach ($rsSer as &$r) {
            $r['PLOTQTY'] = 0;
            if ($r['SERD2_ITMCD']) {
                foreach ($rsBooked as &$b) {
                    $reqPlot = $r['SERD2_QTY'] - $r['PLOTQTY'];
                    if ($r['SERD2_ITMCD'] === $b['ITMNUM'] && $reqPlot && $b['BCQT']) {
                        $theqt = $reqPlot;
                        if ($reqPlot > $b['BCQT']) {
                            $theqt = $b['BCQT'];
                            $r['PLOTQTY'] += $theqt;
                            $b['BCQT'] = 0;
                        } else {
                            $b['BCQT'] -= $reqPlot;
                            $r['PLOTQTY'] += $reqPlot;
                        }
                        $rsFix[] = [
                            'ASSY_DESCRIPTION' => $r['FGDESC'],
                            'ASSY_CODE' => $r['ITH_ITMCD'],
                            'ASSY_QTY' => $r['LQT'],
                            'ASSY_KEY' => $r['ITH_SER'],
                            'ITEM_DESCRIPTION' => $r['RMDESC'],
                            'ITEM_CODE' => $r['SERD2_ITMCD'],
                            'USE' => $r['SERD2_QTY'] / $r['LQT'],
                            'ITEM_QTY' => $theqt,
                            'AJU' => $b['RPSTOCK_NOAJU'],
                            'BCNO' => $b['RPSTOCK_BCNUM'],
                            'BCDATE' => $b['RPSTOCK_BCDATE'],
                            'ITEM_HSCODE' => $b['HSCD'],
                            'BM' => $b['BM'],
                            'PPN' => $b['PPN'],
                            'PPH' => $b['PPH'],
                        ];
                        if ($r['SERD2_QTY'] == $r['PLOTQTY']) {
                            break;
                        }
                    }
                }
                unset($b);
            } else {
                $rsFix[] = [
                    'ASSY_DESCRIPTION' => $r['FGDESC'],
                    'ASSY_CODE' => $r['ITH_ITMCD'],
                    'ASSY_QTY' => $r['LQT'],
                    'ASSY_KEY' => $r['ITH_SER'],
                    'ITEM_DESCRIPTION' => $r['RMDESC'],
                    'ITEM_CODE' => $r['SERD2_ITMCD'],
                    'USE' => 0,
                    'ITEM_QTY' => 0,
                    'AJU' => "-",
                    'BCNO' => "-",
                    'BCDATE' => "",
                    'ITEM_HSCODE' => "",
                    'BM' => "",
                    'PPN' => "",
                    'PPH' => "",
                ];
            }
        }
        unset($r);
        die(json_encode([
            'rsFix' => $rsFix,
        ]));
    }

    public function posting_rm41()
    {
        set_exception_handler([$this, 'exception_handler']);
        set_error_handler([$this, 'log_error']);
        register_shutdown_function([$this, 'fatal_handler']);
        header('Content-Type: application/json');
        ini_set('max_execution_time', '-1');
        $currentDate = date('Y-m-d H:i:s');
        $csj = $this->input->get('insj');
        $rsaktivasi = $this->AKTIVASIAPLIKASI_imod->selectAll();
        $cztujuanpengiriman = '';
        $consignee = '';
        $czinvoice = '';
        $ccustdate = '';
        $czdocbctype = '';
        $nomoraju = '';
        $deliveryDescription = '';

        $cz_KODE_JENIS_TPB = '';
        $czidpenerima = '';
        $cznmpenerima = '';
        $czalamatpenerima = '';
        $cznamapengangkut = '';
        $cznomorpolisi = '';

        $czinvoicedt = '';
        $czConaNo = '';
        $rs_head_dlv = $this->DELV_mod->select_for_rm_h($csj);
        foreach ($rs_head_dlv as $r) {
            $cztujuanpengiriman = $r['DLV_PURPOSE'];
            $consignee = $r['DLV_CONSIGN'];
            $czinvoice = trim($r['DLV_INVNO']);
            $ccustdate = $r['DLV_BCDATE'];
            $czdocbctype = $r['DLV_BCTYPE'];
            $nomoraju = $r['DLV_NOAJU'];
            $deliveryDescription = $r['DLV_DSCRPTN'];
            $czcurrency = trim($r['MCUS_CURCD']);

            $cz_KODE_JENIS_TPB = $r['DLV_ZJENIS_TPB_ASAL'];
            $czidpenerima = str_replace([".", "-"], "", $r['MCUS_TAXREG']);

            $cznmpenerima = $r['MDEL_ZNAMA'];
            $czalamatpenerima = $r['MDEL_ADDRCUSTOMS'];

            $cznamapengangkut = $r['MSTTRANS_TYPE'];
            $cznomorpolisi = $r['DLV_TRANS'];

            $czinvoicedt = $r['DLV_INVDT'];
            $czConaNo = $r['DLV_CONA'];
        }
        if ($this->ZRPSTOCK_mod->check_Primary(['RPSTOCK_REMARK' => $csj]) == 0) {
            $myar[] = ['cd' => 0, 'msg' => 'Please book EXBC first'];
            die('{"status" : ' . json_encode($myar) . '}');
        }
        if ($cztujuanpengiriman == '-') {
            $myar[] = ['cd' => 0, 'msg' => 'please set TUJUAN PENGIRIMAN (' . $cztujuanpengiriman . ')'];
            die('{"status" : ' . json_encode($myar) . '}');
        }

        if ($consignee == '-') {
            $myar[] = ['cd' => 0, 'msg' => 'please set consignment (' . $consignee . ')'];
            die('{"status" : ' . json_encode($myar) . '}');
        }

        if ($czinvoice == '') {
            $myar[] = ['cd' => 0, 'msg' => 'please add invoice number (' . $czinvoice . ') custdate (' . $ccustdate . ') consign (' . $consignee . ')'];
            die('{"status" : ' . json_encode($myar) . '}');
        }

        if ($czdocbctype == '-') {
            $myar[] = ['cd' => 0, 'msg' => 'please select bc type!'];
            die('{"status" : ' . json_encode($myar) . '}');
        }

        if (strlen($nomoraju) != 6) {
            $myar[] = ['cd' => 0, 'msg' => 'NOMOR AJU is not valid, please re-check (' . $nomoraju . ')'];
            die('{"status" : ' . json_encode($myar) . '}');
        }

        $ocustdate = date_create($ccustdate);
        $cz_ymd = date_format($ocustdate, 'Ymd');

        $czidpengusaha = '';
        $cznmpengusaha = '';
        $czalamatpengusaha = '';
        $czizinpengusaha = '';
        foreach ($rsaktivasi as $r) {
            $czkantorasal = $r['KPPBC'];
            $czidmodul = substr('00000' . $r['ID_MODUL'], -6);
            $czidmodul_asli = $r['ID_MODUL'];
            $czidpengusaha = $r['NPWP'];
            $cznmpengusaha = $r['NAMA_PENGUSAHA'];
            $czalamatpengusaha = $r['ALAMAT_PENGUSAHA'];
            $czizinpengusaha = $r['NOMOR_SKEP'];
        }

        $cnoaju = substr($czkantorasal, 0, 4) . $czdocbctype . $czidmodul . $cz_ymd . $nomoraju;
        if ($this->TPB_HEADER_imod->check_Primary(['NOMOR_AJU' => $cnoaju]) > 0) {
            $myar[] = ['cd' => 0, 'msg' => 'the NOMOR AJU is already posted'];
            die('{"status" : ' . json_encode($myar) . '}');
        }

        if (strlen($ccustdate) != 10) {
            $myar[] = ['cd' => 0, 'msg' => 'please enter valid customs date!'];
            die('{"status" : ' . json_encode($myar) . '}');
        }

        if ($this->DELV_mod->check_Primary(['DLV_ID' => $csj]) == 0) {
            $myar[] = ['cd' => 0, 'msg' => 'DO is not found'];
            die('{"status" : ' . json_encode($myar) . '}');
        }

        $rspkg = $this->DELV_mod->select_pkg($csj);
        $cz_JUMLAH_KEMASAN = 0;
        $netweight_represent = 0;
        $cz_h_BRUTO = 0;
        $cz_h_NETTO = 0;
        foreach ($rspkg as $r) {
            if ($r['DLV_PKG_NWG'] > 0) {
                $netweight_represent = $r['DLV_PKG_NWG'];
                $cz_h_BRUTO += $r['DLV_PKG_GWG'];
                $cz_h_NETTO += $r['DLV_PKG_NWG'];
            }
            if ($r['DLV_PKG_MEASURE']) {
                $cz_JUMLAH_KEMASAN++;
            }
        }

        $flg_sell = strpos($deliveryDescription, 'DIKEMBALIKAN') !== false ? false : true;

        $rscurr = $this->MEXRATE_mod->selectfor_posting_in([$ccustdate], [$czcurrency]);
        if ($flg_sell) {
            if (count($rscurr) == 0) {
                $myar[] = ["cd" => "0", "msg" => "Please fill exchange rate data !"];
                die('{"status":' . json_encode($myar) . '}');
            } else {
                foreach ($rscurr as $r) {
                    $czharga_matauang = $r->MEXRATE_VAL;
                    break;
                }
            }
        }

        #HEADER_HARGA
        $cz_h_CIF_FG = 0;
        $cz_h_HARGA_PENYERAHAN_FG = 0;

        #check price source
        $rsrmmanualDO = $this->DLVRMDOC_mod->select_invoice_posting($csj);
        $rsrm_fromSO = $this->DLVRMSO_mod->select_invoice($csj);
        $SERI_BARANG = 1;
        if (count($rsrmmanualDO) && count($rsrm_fromSO) <= 0) {
        } else {
            $rsrm_fromSO = $this->DLVRMSO_mod->select_invoice($csj);
            if (count($rsrm_fromSO)) {
            } else {
                $rsrm_fromDO = $this->DELV_mod->select_det_byid_rm($csj);
                if (count($rsrm_fromDO)) {
                } else {
                    $myar[] = ["cd" => "0", "msg" => "there is no data source"];
                    die('{"status":' . json_encode($myar) . '}');
                }
            }
        }
        $tpb_bahan_baku = [];
        $rs_do = $this->DELV_mod->select_for_do_rm_rtn_v1($csj);
        $rs_xbc = $this->RCV_mod->select_for_rmrtn_bytxid($csj);
        $IncDateList = [];
        $IncCRList = []; #Currency
        $IncDateCR_FLGList = []; #Currency
        $resumeXBCDoc = [];
        foreach ($rs_do as $r) {
            $r['PLOTQT'] = 0;
            foreach ($rs_xbc as &$x) {
                if (
                    $r['DLVRMDOC_ITMID'] == $x['RCV_ITMCD']
                    && $r['DLVRMDOC_AJU'] == $x['RCV_RPNO']
                    && $r['DLVRMDOC_DO'] == $x['RCV_DONO']
                    && $x['RCV_QTY'] > 0
                    && $r['PLOTQT'] != $r['DLVRMDOC_ITMQT']
                ) {
                    $reqbal = $r['DLVRMDOC_ITMQT'] - $r['PLOTQT'];
                    $useqt = 0;
                    if ($reqbal > $x['RCV_QTY']) {
                        $useqt = $x['RCV_QTY'];
                        $r['PLOTQT'] += $x['RCV_QTY'];
                        $x['RCV_QTY'] = 0;
                    } else {
                        $useqt = $reqbal;
                        $r['PLOTQT'] += $reqbal;
                        $x['RCV_QTY'] -= $reqbal;
                    }
                    $tpb_bahan_baku[] = [
                        'KODE_JENIS_DOK_ASAL' => $x['RCV_BCTYPE'], 'NOMOR_DAFTAR_DOK_ASAL' => $r['DLVRMDOC_NOPEN'], 'TANGGAL_DAFTAR_DOK_ASAL' => $x['RCV_BCDATE'], 'KODE_KANTOR' => $x['RCV_KPPBC'], 'NOMOR_AJU_DOK_ASAL' => strlen($r['DLVRMDOC_AJU']) == 6 ? substr('000000000000000000000000', 0, 26) : $r['DLVRMDOC_AJU'], 'SERI_BARANG_DOK_ASAL' => empty($x['RCV_ZNOURUT']) ? 0 : $x['RCV_ZNOURUT'], 'SPESIFIKASI_LAIN' => null, 'CIF' => number_format($x['RCV_PRPRC'] * $useqt, 2, '.', ''), 'HARGA_PENYERAHAN' => 0, 'KODE_BARANG' => $r['DLVRMDOC_ITMID'], 'KODE_STATUS' => "03", 'POS_TARIF' => $x['RCV_HSCD'], 'URAIAN' => rtrim($r['DLV_ITMD1']), 'TIPE' => rtrim($r['DLV_ITMSPTNO']), 'JUMLAH_SATUAN' => $useqt, 'SERI_BAHAN_BAKU' => 1, 'JENIS_SATUAN' => ($r['MITM_STKUOM'] == 'PCS') ? 'PCE' : $r['MITM_STKUOM'], 'KODE_ASAL_BAHAN_BAKU' => ($x['RCV_BCTYPE'] == '27' || $x['RCV_BCTYPE'] == '23') ? '0' : '1', 'RBM' => $x['RCV_BM'] * 1, 'CURRENCY' => $x['MSUP_SUPCR'], 'ITMCDCUS' => $r['MITM_ITMCDCUS'],
                    ];
                    $IncDateList[] = $x['RCV_BCDATE'];
                    $IncCRList[] = $x['MSUP_SUPCR'];
                    $IncDateCR_FLGList[] = 0;
                    if ($r['DLVRMDOC_ITMQT'] == $r['PLOTQT']) {
                        break;
                    }
                }
            }
            unset($x);
        }
        unset($r);
        $tpb_barang = [];
        $no = 1;
        if (!$flg_sell) {
            $rscurr = $this->MEXRATE_mod->selectfor_posting_in($IncDateList, $IncCRList);
            $listcount = count($IncDateCR_FLGList);
            foreach ($rscurr as $r) {
                for ($i = 0; $i < $listcount; $i++) {
                    if ($r->MEXRATE_CURR == $IncCRList[$i] && $r->MEXRATE_DT == $IncDateList[$i]) {
                        $IncDateCR_FLGList[$i] = 1;
                    }
                }
            }

            #validate exchange rate for incoming date
            for ($i = 0; $i < $listcount; $i++) {
                if ($IncDateCR_FLGList[$i] == 0) {
                    $dar = ["cd" => "0", "msg" => "Please fill exchange rate data for " . $IncCRList[$i] . " on " . $IncDateList[$i] . " !", 'data' => $rs_xbc];
                    $myar[] = $dar;
                    die('{"status":' . json_encode($myar) . '}');
                }
            }
            #end

            foreach ($tpb_bahan_baku as $r) {
                foreach ($rscurr as $c) {
                    if ($r['CURRENCY'] == $c->MEXRATE_CURR && $r['TANGGAL_DAFTAR_DOK_ASAL'] == $c->MEXRATE_DT) {
                        if ($c->MEXRATE_CURR == 'RPH') {
                            $czharga_matauang = 1;
                        } else {
                            $czharga_matauang = $c->MEXRATE_VAL;
                        }
                        break;
                    }
                }
                $tpb_barang[] = [
                    'KODE_BARANG' => $r['KODE_BARANG'], 'POS_TARIF' => $r['POS_TARIF'], 'URAIAN' => $r['URAIAN'], 'TIPE' => $r['TIPE'], 'JUMLAH_SATUAN' => $r['JUMLAH_SATUAN'], 'KODE_SATUAN' => $r['JENIS_SATUAN'], 'NETTO' => $no == 1 ? $netweight_represent : 0, 'CIF' => $r['CIF'], 'HARGA_PENYERAHAN' => str_replace(",", "", $r['CIF']) * $czharga_matauang, 'SERI_BARANG' => $SERI_BARANG, 'KODE_STATUS' => '02', 'JUMLAH_BAHAN_BAKU' => 1, 'ITMCDCUS' => $r['ITMCDCUS'],

                ];
                $no++;
                $SERI_BARANG++;
            }
        } else {
            foreach ($tpb_bahan_baku as $r) {
                $tpb_barang[] = [
                    'KODE_BARANG' => $r['KODE_BARANG'], 'POS_TARIF' => $r['POS_TARIF'], 'URAIAN' => $r['URAIAN'], 'JUMLAH_SATUAN' => $r['JUMLAH_SATUAN'], 'KODE_SATUAN' => $r['JENIS_SATUAN'], 'NETTO' => $no == 1 ? $netweight_represent : 0, 'CIF' => $r['CIF'], 'HARGA_PENYERAHAN' => $r['CIF'] * $czharga_matauang, 'SERI_BARANG' => $SERI_BARANG, 'KODE_STATUS' => '02', 'JUMLAH_BAHAN_BAKU' => 1, 'ITMCDCUS' => $r['ITMCDCUS'], 'TIPE' => $r['TIPE'],
                ];
                $no++;
                $SERI_BARANG++;
            }
        }
        $cz_h_JUMLAH_BARANG = count($tpb_barang);
        foreach ($tpb_barang as $r) {
            $cz_h_CIF_FG += str_replace(",", "", $r['CIF']);
            $cz_h_HARGA_PENYERAHAN_FG += $r['HARGA_PENYERAHAN'];
        }

        foreach ($tpb_bahan_baku as $r) {
            $isfound = false;
            foreach ($resumeXBCDoc as $c) {
                if (
                    $r['NOMOR_DAFTAR_DOK_ASAL'] == $c['NOPEN'] && $r['TANGGAL_DAFTAR_DOK_ASAL'] == $c['TGLPEN']
                    && $r['KODE_JENIS_DOK_ASAL'] == $c['BCTYPE']
                ) {
                    $isfound = true;
                    break;
                }
            }
            if (!$isfound) {
                $resumeXBCDoc[] = [
                    'NOPEN' => $r['NOMOR_DAFTAR_DOK_ASAL'], 'TGLPEN' => $r['TANGGAL_DAFTAR_DOK_ASAL'], 'BCTYPE' => $r['KODE_JENIS_DOK_ASAL'],
                ];
            }
        }
        $tpb_header = [
            "NOMOR_AJU" => $cnoaju, "KODE_KANTOR" => $czkantorasal, "KODE_JENIS_TPB" => $cz_KODE_JENIS_TPB, "KODE_TUJUAN_PENGIRIMAN" => $cztujuanpengiriman,

            "KODE_ID_PENGUSAHA" => "1", "ID_PENGUSAHA" => $czidpengusaha, "NAMA_PENGUSAHA" => $cznmpengusaha,
            "ALAMAT_PENGUSAHA" => $czalamatpengusaha, "NOMOR_IJIN_TPB" => $czizinpengusaha,

            "KODE_ID_PENERIMA_BARANG" => "1", "ID_PENERIMA_BARANG" => $czidpenerima,
            "NAMA_PENERIMA_BARANG" => $cznmpenerima, "ALAMAT_PENERIMA_BARANG" => $czalamatpenerima,

            "HARGA_PENYERAHAN" => $cz_h_HARGA_PENYERAHAN_FG,

            "NAMA_PENGANGKUT" => $cznamapengangkut, "NOMOR_POLISI" => $cznomorpolisi,

            "BRUTO" => $cz_h_BRUTO, "NETTO" => $cz_h_NETTO, "JUMLAH_BARANG" => $cz_h_JUMLAH_BARANG,

            "KOTA_TTD" => "CIKARANG", "TANGGAL_TTD" => $ccustdate,

            "KODE_DOKUMEN_PABEAN" => $czdocbctype, "ID_MODUL" => $czidmodul_asli, "VERSI_MODUL" => null,

            "VOLUME" => 0, "KODE_STATUS" => '00',
        ];

        $tpb_kemasan = [];
        $tpb_kemasan[] = ["JUMLAH_KEMASAN" => $cz_JUMLAH_KEMASAN, "KODE_JENIS_KEMASAN" => "BX"];

        $tpb_dokumen = [];
        $tpb_dokumen[] = ["KODE_JENIS_DOKUMEN" => "380", "NOMOR_DOKUMEN" => $czinvoice, "TANGGAL_DOKUMEN" => $czinvoicedt, "TIPE_DOKUMEN" => "02", "SERI_DOKUMEN" => 1]; //invoice
        $tpb_dokumen[] = ["KODE_JENIS_DOKUMEN" => "217", "NOMOR_DOKUMEN" => $czinvoice, "TANGGAL_DOKUMEN" => $czinvoicedt, "TIPE_DOKUMEN" => "02", "SERI_DOKUMEN" => 2]; //packing list
        $tpb_dokumen[] = ["KODE_JENIS_DOKUMEN" => "640", "NOMOR_DOKUMEN" => $csj, "TANGGAL_DOKUMEN" => $ccustdate, "TIPE_DOKUMEN" => "02", "SERI_DOKUMEN" => 3]; //surat jalan
        $tpb_dokumen[] = ["KODE_JENIS_DOKUMEN" => "315", "NOMOR_DOKUMEN" => $czConaNo, "TANGGAL_DOKUMEN" => null, "TIPE_DOKUMEN" => "02", "SERI_DOKUMEN" => 4]; //kontrak
        $seridokumen = 5;
        foreach ($resumeXBCDoc as $n) {
            $tpb_dokumen[] = ["KODE_JENIS_DOKUMEN" => $n['BCTYPE'], "NOMOR_DOKUMEN" => $n['NOPEN'], "TANGGAL_DOKUMEN" => $n['TGLPEN'], "TIPE_DOKUMEN" => "02", "SERI_DOKUMEN" => $seridokumen];
            $seridokumen++;
        }

        #INSERT CEISA
        ##1 TPB HEADER
        $ZR_TPB_HEADER = $this->TPB_HEADER_imod->insert($tpb_header);
        ##N

        ##2 TPB DOKUMEN
        foreach ($tpb_dokumen as &$n) {
            $n['ID_HEADER'] = $ZR_TPB_HEADER;
        }
        unset($n);
        $this->TPB_DOKUMEN_imod->insertb($tpb_dokumen);
        ##N

        ##3 TPB KEMASAN
        foreach ($tpb_kemasan as &$n) {
            $n['ID_HEADER'] = $ZR_TPB_HEADER;
        }
        unset($n);
        $this->TPB_KEMASAN_imod->insertb($tpb_kemasan);
        ##N

        ##4 TPB BARANG
        foreach ($tpb_barang as &$n) {
            $n['ID_HEADER'] = $ZR_TPB_HEADER;
        }
        unset($n);
        ##N

        ##4 TPB BARANG & BAHAN BAKU
        foreach ($tpb_barang as $n) {
            // $ZR_TPB_BARANG = $this->TPB_BARANG_imod->insert($n);
            $_barang = [
                'KODE_BARANG' => $n['KODE_BARANG'] != $n['ITMCDCUS'] ? $n['ITMCDCUS'] : $n['KODE_BARANG']//substr($n['KODE_BARANG'],0,2) == 'PM' ? '-' : $n['KODE_BARANG']
                , 'POS_TARIF' => $n['POS_TARIF'], 'URAIAN' => $n['URAIAN'], 'TIPE' => $n['TIPE'], 'JUMLAH_SATUAN' => $n['JUMLAH_SATUAN'], 'KODE_SATUAN' => $n['KODE_SATUAN'], 'NETTO' => $n['NETTO'], 'CIF' => $n['CIF'], 'HARGA_PENYERAHAN' => $n['HARGA_PENYERAHAN'], 'SERI_BARANG' => $n['SERI_BARANG'], 'KODE_STATUS' => $n['KODE_STATUS'], 'JUMLAH_BAHAN_BAKU' => $n['JUMLAH_BAHAN_BAKU'], 'ID_HEADER' => $n['ID_HEADER'],
            ];
            $ZR_TPB_BARANG = $this->TPB_BARANG_imod->insert($_barang);
            foreach ($tpb_bahan_baku as $b) {
                if ($n['KODE_BARANG'] == $b['KODE_BARANG'] && $n['CIF'] == $b['CIF']) {
                    $this->TPB_BAHAN_BAKU_imod
                        ->insert([
                            'KODE_JENIS_DOK_ASAL' => $b['KODE_JENIS_DOK_ASAL'], 'NOMOR_DAFTAR_DOK_ASAL' => $b['NOMOR_DAFTAR_DOK_ASAL'], 'TANGGAL_DAFTAR_DOK_ASAL' => $b['TANGGAL_DAFTAR_DOK_ASAL'], 'KODE_KANTOR' => $b['KODE_KANTOR'], 'NOMOR_AJU_DOK_ASAL' => $b['NOMOR_AJU_DOK_ASAL'], 'SERI_BARANG_DOK_ASAL' => $b['SERI_BARANG_DOK_ASAL'], 'SPESIFIKASI_LAIN' => $b['SPESIFIKASI_LAIN'], 'HARGA_PENYERAHAN' => (str_replace(",", "", $b['CIF']) * $czharga_matauang), 'KODE_BARANG' => $b['KODE_BARANG'] != $b['ITMCDCUS'] ? $b['ITMCDCUS'] : $b['KODE_BARANG'], 'KODE_STATUS' => $b['KODE_STATUS'], 'URAIAN' => $b['URAIAN'], 'TIPE' => $b['TIPE'], 'JUMLAH_SATUAN' => $b['JUMLAH_SATUAN'], 'JENIS_SATUAN' => $b['JENIS_SATUAN'], 'KODE_ASAL_BAHAN_BAKU' => $b['KODE_ASAL_BAHAN_BAKU'], 'NETTO' => 0, 'SERI_BAHAN_BAKU' => $b['SERI_BAHAN_BAKU'], 'SERI_BARANG' => $n['SERI_BARANG'], 'ID_BARANG' => $ZR_TPB_BARANG, 'ID_HEADER' => $ZR_TPB_HEADER,
                        ]);
                }
            }
        }
        ##N
        $this->DELV_mod->updatebyVAR(['DLV_POST' => $this->session->userdata('nama'), 'DLV_POSTTM' => $currentDate], ['DLV_ID' => $csj]);
        $myar[] = [
            'cd' => '1', 'msg' => 'Done, check your TPB', 'tpb_barang' => $tpb_barang, 'tpb_bahan_baku' => $tpb_bahan_baku,
        ];
        $this->setPrice(base64_encode($csj));
        $this->gotoque($csj);
        die('{"status" : ' . json_encode($myar) . '}');
    }
    public function posting_rm261()
    {
        header('Content-Type: application/json');
        ini_set('max_execution_time', '-1');
        $currentDate = date('Y-m-d H:i:s');
        $csj = $this->input->get('insj');
        $rsaktivasi = $this->AKTIVASIAPLIKASI_imod->selectAll();
        $cztujuanpengiriman = '';
        $consignee = '';
        $czinvoice = '';
        $ccustdate = '';
        $czdocbctype = '';
        $nomoraju = '';
        $deliveryDescription = '';

        $czidpenerima = '';
        $cznmpenerima = '';
        $czalamatpenerima = '';
        $cznamapengangkut = '';
        $cznomorpolisi = '';

        $czinvoicedt = '';
        $rs_head_dlv = $this->DELV_mod->select_for_rm_h($csj);
        $czKODE_CARA_ANGKUT = '';
        foreach ($rs_head_dlv as $r) {
            $cztujuanpengiriman = $r['DLV_PURPOSE'];
            $consignee = $r['DLV_CONSIGN'];
            $czinvoice = trim($r['DLV_INVNO']);
            $ccustdate = $r['DLV_BCDATE'];
            $czdocbctype = $r['DLV_BCTYPE'];
            $nomoraju = $r['DLV_NOAJU'];
            $deliveryDescription = $r['DLV_DSCRPTN'];
            $czcurrency = trim($r['MCUS_CURCD']);

            $czidpenerima = str_replace([".", "-"], "", $r['MCUS_TAXREG']);

            $cznmpenerima = $r['MDEL_ZNAMA'];
            $czalamatpenerima = $r['MDEL_ADDRCUSTOMS'];

            $cznamapengangkut = $r['MSTTRANS_TYPE'];
            $cznomorpolisi = $r['DLV_TRANS'];

            $czinvoicedt = $r['DLV_INVDT'];
            $czKODE_CARA_ANGKUT = $r['DLV_ZKODE_CARA_ANGKUT'];
            $czConaNo = $r['DLV_CONA'];
        }
        if ($this->ZRPSTOCK_mod->check_Primary(['RPSTOCK_REMARK' => $csj]) == 0) {
            $myar[] = ['cd' => 0, 'msg' => 'Please book EXBC first'];
            die('{"status" : ' . json_encode($myar) . '}');
        }
        if ($cztujuanpengiriman == '-') {
            $myar[] = ['cd' => 0, 'msg' => 'please set TUJUAN PENGIRIMAN (' . $cztujuanpengiriman . ')'];
            die('{"status" : ' . json_encode($myar) . '}');
        }

        if ($consignee == '-') {
            $myar[] = ['cd' => 0, 'msg' => 'please set consignment (' . $consignee . ')'];
            die('{"status" : ' . json_encode($myar) . '}');
        }

        if ($czinvoice == '') {
            $myar[] = ['cd' => 0, 'msg' => 'please add invoice number (' . $czinvoice . ') custdate (' . $ccustdate . ') consign (' . $consignee . ')'];
            die('{"status" : ' . json_encode($myar) . '}');
        }

        if ($czdocbctype == '-') {
            $myar[] = ['cd' => 0, 'msg' => 'please select bc type!'];
            die('{"status" : ' . json_encode($myar) . '}');
        }

        if (strlen($nomoraju) != 6) {
            $myar[] = ['cd' => 0, 'msg' => 'NOMOR AJU is not valid, please re-check (' . $nomoraju . ')'];
            die('{"status" : ' . json_encode($myar) . '}');
        }

        $ocustdate = date_create($ccustdate);
        $cz_ymd = date_format($ocustdate, 'Ymd');

        $czidpengusaha = '';
        $cznmpengusaha = '';
        $czalamatpengusaha = '';
        $czizinpengusaha = '';
        $cztanggalskep = '';
        foreach ($rsaktivasi as $r) {
            $czkantorasal = $r['KPPBC'];
            $czidmodul = substr('00000' . $r['ID_MODUL'], -6);
            $czidmodul_asli = $r['ID_MODUL'];
            $czidpengusaha = $r['NPWP'];
            $cznmpengusaha = $r['NAMA_PENGUSAHA'];
            $czalamatpengusaha = $r['ALAMAT_PENGUSAHA'];
            $czizinpengusaha = $r['NOMOR_SKEP'];
            $cztanggalskep = $r['TANGGAL_SKEP'];
        }

        $cnoaju = substr($czkantorasal, 0, 4) . substr($czdocbctype, 0, 2) . $czidmodul . $cz_ymd . $nomoraju;
        if ($this->TPB_HEADER_imod->check_Primary(['NOMOR_AJU' => $cnoaju]) > 0) {
            $myar[] = ['cd' => 0, 'msg' => 'the NOMOR AJU is already posted'];
            die('{"status" : ' . json_encode($myar) . '}');
        }

        if (strlen($ccustdate) != 10) {
            $myar[] = ['cd' => 0, 'msg' => 'please enter valid customs date!'];
            die('{"status" : ' . json_encode($myar) . '}');
        }

        if ($this->DELV_mod->check_Primary(['DLV_ID' => $csj]) == 0) {
            $myar[] = ['cd' => 0, 'msg' => 'DO is not found'];
            die('{"status" : ' . json_encode($myar) . '}');
        }

        $rspkg = $this->DELV_mod->select_pkg($csj);
        $cz_JUMLAH_KEMASAN = 0;
        $netweight_represent = 0;
        $cz_h_BRUTO = 0;
        $cz_h_NETTO = 0;
        foreach ($rspkg as $r) {
            if ($r['DLV_PKG_NWG'] > 0) {
                $netweight_represent = $r['DLV_PKG_NWG'];
                $cz_h_BRUTO += $r['DLV_PKG_GWG'];
                $cz_h_NETTO += $r['DLV_PKG_NWG'];
            }
            if ($r['DLV_PKG_MEASURE']) {
                $cz_JUMLAH_KEMASAN++;
            }
        }

        $flg_sell = strpos($deliveryDescription, 'DIKEMBALIKAN') !== false || $deliveryDescription == 'DIPERBAIKI' ? false : true;

        $rscurr = $this->MEXRATE_mod->selectfor_posting_in([$ccustdate], [$czcurrency]);
        if ($flg_sell) {
            if (count($rscurr) == 0) {
                $myar[] = ["cd" => "0", "msg" => "Please fill exchange rate data !"];
                die('{"status":' . json_encode($myar) . '}');
            } else {
                foreach ($rscurr as $r) {
                    $czharga_matauang = $r->MEXRATE_VAL;
                    break;
                }
            }
        }

        #HEADER_HARGA
        $cz_h_CIF_FG = 0;
        $cz_h_HARGA_PENYERAHAN_FG = 0;

        #check price source
        $rsrmmanualDO = $this->DLVRMDOC_mod->select_invoice_posting($csj);
        $rsrm_fromSO = $this->DLVRMSO_mod->select_invoice($csj);
        $SERI_BARANG = 1;
        if (count($rsrmmanualDO) && count($rsrm_fromSO) <= 0) {
        } else {
            $rsrm_fromSO = $this->DLVRMSO_mod->select_invoice($csj);
            if (count($rsrm_fromSO)) {
            } else {
                $rsrm_fromDO = $this->DELV_mod->select_det_byid_rm($csj);
                if (count($rsrm_fromDO)) {
                } else {
                    $myar[] = ["cd" => "0", "msg" => "there is no data source"];
                    die('{"status":' . json_encode($myar) . '}');
                }
            }
        }
        $tpb_bahan_baku = [];
        $rs_do = $this->DELV_mod->select_for_do_rm_rtn_v1($csj);
        $rs_xbc = $this->RCV_mod->select_for_rmrtn_bytxid($csj);
        $IncDateList = [];
        $IncCRList = []; #Currency
        $IncDateCR_FLGList = []; #Currency
        $resumeXBCDoc = [];
        foreach ($rs_do as $r) {
            $r['PLOTQT'] = 0;
            foreach ($rs_xbc as &$x) {
                if (
                    $r['DLVRMDOC_ITMID'] == $x['RCV_ITMCD']
                    && $r['DLVRMDOC_AJU'] == $x['RCV_RPNO']
                    && $r['DLVRMDOC_DO'] == $x['RCV_DONO']
                    && $x['RCV_QTY'] > 0
                    && $r['PLOTQT'] != $r['DLVRMDOC_ITMQT']
                ) {
                    $reqbal = $r['DLVRMDOC_ITMQT'] - $r['PLOTQT'];
                    $useqt = 0;
                    if ($reqbal > $x['RCV_QTY']) {
                        $useqt = $x['RCV_QTY'];
                        $r['PLOTQT'] += $x['RCV_QTY'];
                        $x['RCV_QTY'] = 0;
                    } else {
                        $useqt = $reqbal;
                        $r['PLOTQT'] += $reqbal;
                        $x['RCV_QTY'] -= $reqbal;
                    }
                    $tpb_bahan_baku[] = [
                        'KODE_JENIS_DOK_ASAL' => $x['RCV_BCTYPE'], 'NOMOR_DAFTAR_DOK_ASAL' => $r['DLVRMDOC_NOPEN'], 'TANGGAL_DAFTAR_DOK_ASAL' => $x['RCV_BCDATE'], 'KODE_KANTOR' => $x['RCV_KPPBC'], 'NOMOR_AJU_DOK_ASAL' => strlen($r['DLVRMDOC_AJU']) == 6 ? substr('000000000000000000000000', 0, 26) : $r['DLVRMDOC_AJU'], 'SERI_BARANG_DOK_ASAL' => empty($x['RCV_ZNOURUT']) ? 0 : $x['RCV_ZNOURUT'], 'SPESIFIKASI_LAIN' => null, 'CIF' => 0, 'HARGA_PENYERAHAN' => 0, 'KODE_BARANG' => $r['DLVRMDOC_ITMID'], 'KODE_STATUS' => "03", 'POS_TARIF' => $x['RCV_HSCD'], 'URAIAN' => rtrim($r['DLV_ITMD1']), 'TIPE' => rtrim($r['DLV_ITMSPTNO']), 'JUMLAH_SATUAN' => $useqt, 'SERI_BAHAN_BAKU' => 1, 'JENIS_SATUAN' => ($r['MITM_STKUOM'] == 'PCS') ? 'PCE' : $r['MITM_STKUOM'], 'KODE_ASAL_BAHAN_BAKU' => ($x['RCV_BCTYPE'] == '27' || $x['RCV_BCTYPE'] == '23') ? '0' : '1', 'RBM' => $x['RCV_BM'] * 1, 'CURRENCY' => $x['MSUP_SUPCR'],
                    ];
                    $IncDateList[] = $x['RCV_BCDATE'];
                    $IncCRList[] = $x['MSUP_SUPCR'];
                    $IncDateCR_FLGList[] = 0;
                    if ($r['DLVRMDOC_ITMQT'] == $r['PLOTQT']) {
                        break;
                    }
                }
            }
            unset($x);
        }
        unset($r);
        $tpb_barang = [];
        $no = 1;
        if (!$flg_sell) {
            $rscurr = $this->MEXRATE_mod->selectfor_posting_in($IncDateList, $IncCRList);
            $listcount = count($IncDateCR_FLGList);
            foreach ($rscurr as $r) {
                for ($i = 0; $i < $listcount; $i++) {
                    if ($r->MEXRATE_CURR == $IncCRList[$i] && $r->MEXRATE_DT == $IncDateList[$i]) {
                        $IncDateCR_FLGList[$i] = 1;
                    }
                }
            }

            #validate exchange rate for incoming date
            for ($i = 0; $i < $listcount; $i++) {
                if ($IncDateCR_FLGList[$i] == 0) {
                    $dar = ["cd" => "0", "msg" => "Please fill exchange rate data for " . $IncCRList[$i] . " on " . $IncDateList[$i] . " !"];
                    $myar[] = $dar;
                    die('{"status":' . json_encode($myar) . '}');
                }
            }
            #end

            foreach ($tpb_bahan_baku as $r) {
                foreach ($rscurr as $c) {
                    if ($r['CURRENCY'] == $c->MEXRATE_CURR && $r['TANGGAL_DAFTAR_DOK_ASAL'] == $c->MEXRATE_DT) {
                        if ($c->MEXRATE_CURR == 'RPH') {
                            $czharga_matauang = 1;
                        } else {
                            $czharga_matauang = $c->MEXRATE_VAL;
                        }
                        break;
                    }
                }
                $tpb_barang[] = [
                    'KODE_BARANG' => $r['KODE_BARANG'], 'POS_TARIF' => $r['POS_TARIF'], 'URAIAN' => $r['URAIAN'], 'TIPE' => $r['TIPE'], 'JUMLAH_SATUAN' => $r['JUMLAH_SATUAN'], 'KODE_SATUAN' => $r['JENIS_SATUAN'], 'NETTO' => $no == 1 ? $netweight_represent : 0, 'CIF' => $r['CIF'], 'HARGA_PENYERAHAN' => $r['CIF'] * $czharga_matauang, 'SERI_BARANG' => $SERI_BARANG, 'KODE_STATUS' => '02', 'JUMLAH_BAHAN_BAKU' => 1, 'JUMLAH_KEMASAN' => 1, 'KODE_KEMASAN' => 'BX',
                ];
                $no++;
                $SERI_BARANG++;
            }
        } else {
            foreach ($tpb_bahan_baku as $r) {
                $tpb_barang[] = [
                    'KODE_BARANG' => $r['KODE_BARANG'], 'POS_TARIF' => $r['POS_TARIF'], 'URAIAN' => $r['URAIAN'], 'TIPE' => $r['TIPE'], 'JUMLAH_SATUAN' => $r['JUMLAH_SATUAN'], 'KODE_SATUAN' => $r['JENIS_SATUAN'], 'NETTO' => $no == 1 ? $netweight_represent : 0, 'CIF' => $r['CIF'], 'HARGA_PENYERAHAN' => $r['CIF'] * $czharga_matauang, 'SERI_BARANG' => $SERI_BARANG, 'KODE_STATUS' => '02', 'JUMLAH_BAHAN_BAKU' => 1, 'JUMLAH_KEMASAN' => 1, 'KODE_KEMASAN' => 'BX',
                ];
                $no++;
                $SERI_BARANG++;
            }
        }
        $cz_h_JUMLAH_BARANG = count($tpb_barang);
        foreach ($tpb_barang as $r) {
            $cz_h_CIF_FG += $r['CIF'];
            $cz_h_HARGA_PENYERAHAN_FG += $r['HARGA_PENYERAHAN'];
        }

        foreach ($tpb_bahan_baku as $r) {
            $isfound = false;
            foreach ($resumeXBCDoc as $c) {
                if (
                    $r['NOMOR_DAFTAR_DOK_ASAL'] == $c['NOPEN'] && $r['TANGGAL_DAFTAR_DOK_ASAL'] == $c['TGLPEN']
                    && $r['KODE_JENIS_DOK_ASAL'] == $c['BCTYPE']
                ) {
                    $isfound = true;
                    break;
                }
            }
            if (!$isfound) {
                $resumeXBCDoc[] = [
                    'NOPEN' => $r['NOMOR_DAFTAR_DOK_ASAL'], 'TGLPEN' => $r['TANGGAL_DAFTAR_DOK_ASAL'], 'BCTYPE' => $r['KODE_JENIS_DOK_ASAL'],
                ];
            }
        }

        $tpb_bahan_baku_tarif = [];
        foreach ($tpb_bahan_baku as $r) {
            $tpb_bahan_baku_tarif[] = [
                'JENIS_TARIF' => 'BM', 'KODE_TARIF' => 1, 'NILAI_BAYAR' => 0, 'NILAI_FASILITAS' => 0, 'KODE_FASILITAS' => 2, 'TARIF_FASILITAS' => 100, 'TARIF' => $r['RBM'], 'SERI_BAHAN_BAKU' => $r['SERI_BAHAN_BAKU'], 'RITEMCD' => $r['KODE_BARANG'],
            ];
            $tpb_bahan_baku_tarif[] = [
                'JENIS_TARIF' => 'PPN', 'KODE_TARIF' => 1, 'NILAI_BAYAR' => 0, 'NILAI_FASILITAS' => 0, 'KODE_FASILITAS' => 2, 'TARIF_FASILITAS' => 100, 'TARIF' => 10, 'SERI_BAHAN_BAKU' => $r['SERI_BAHAN_BAKU'], 'RITEMCD' => $r['KODE_BARANG'],
            ];
            $tpb_bahan_baku_tarif[] = [
                'JENIS_TARIF' => 'PPH', 'KODE_TARIF' => 1, 'NILAI_BAYAR' => 0, 'NILAI_FASILITAS' => 0, 'KODE_FASILITAS' => 2, 'TARIF_FASILITAS' => 100, 'TARIF' => 2.5, 'SERI_BAHAN_BAKU' => $r['SERI_BAHAN_BAKU'], 'RITEMCD' => $r['KODE_BARANG'],
            ];
        }
        #N
        $tpb_header = [
            "NOMOR_AJU" => $cnoaju, "KODE_KANTOR" => $czkantorasal, "KODE_TUJUAN_PENGIRIMAN" => $cztujuanpengiriman,

            "KODE_ID_PENGUSAHA" => "1", "ID_PENGUSAHA" => $czidpengusaha, "NAMA_PENGUSAHA" => $cznmpengusaha,
            "ALAMAT_PENGUSAHA" => $czalamatpengusaha, "NOMOR_IJIN_TPB" => $czizinpengusaha, "TANGGAL_IJIN_TPB" => $cztanggalskep,
            "KODE_JENIS_API_PENGUSAHA" => 2, "API_PENGUSAHA" => "101600449-B",

            "KODE_ID_PENERIMA_BARANG" => "1", "ID_PENERIMA_BARANG" => $czidpenerima,
            "NAMA_PENERIMA_BARANG" => $cznmpenerima, "ALAMAT_PENERIMA_BARANG" => $czalamatpenerima,
            "NDPBM" => 1,

            "KODE_VALUTA" => $czcurrency == 'RPH' ? 'IDR' : $czcurrency,
            "KODE_CARA_ANGKUT" => $czKODE_CARA_ANGKUT,

            "HARGA_PENYERAHAN" => $cz_h_HARGA_PENYERAHAN_FG,

            "NAMA_PENGANGKUT" => $cznamapengangkut, "NOMOR_POLISI" => $cznomorpolisi,

            "BRUTO" => $cz_h_BRUTO, "NETTO" => $cz_h_NETTO, "JUMLAH_BARANG" => $cz_h_JUMLAH_BARANG,

            "KOTA_TTD" => "CIKARANG", "TANGGAL_TTD" => $ccustdate,

            "KODE_DOKUMEN_PABEAN" => $czdocbctype, "ID_MODUL" => $czidmodul_asli, "VERSI_MODUL" => null,

            "VOLUME" => 0, "KODE_STATUS" => '00',
        ];

        $tpb_kemasan = [];
        $tpb_kemasan[] = ["JUMLAH_KEMASAN" => $cz_JUMLAH_KEMASAN, "KODE_JENIS_KEMASAN" => "BX"];

        $tpb_dokumen = [];
        $tpb_dokumen[] = ["KODE_JENIS_DOKUMEN" => "380", "NOMOR_DOKUMEN" => $czinvoice, "TANGGAL_DOKUMEN" => $czinvoicedt, "TIPE_DOKUMEN" => "02", "SERI_DOKUMEN" => 1]; //invoice
        $tpb_dokumen[] = ["KODE_JENIS_DOKUMEN" => "217", "NOMOR_DOKUMEN" => $czinvoice, "TANGGAL_DOKUMEN" => $czinvoicedt, "TIPE_DOKUMEN" => "02", "SERI_DOKUMEN" => 2]; //packing list
        $tpb_dokumen[] = ["KODE_JENIS_DOKUMEN" => "640", "NOMOR_DOKUMEN" => $csj, "TANGGAL_DOKUMEN" => $ccustdate, "TIPE_DOKUMEN" => "02", "SERI_DOKUMEN" => 3]; //surat jalan
        $tpb_dokumen[] = ["KODE_JENIS_DOKUMEN" => "315", "NOMOR_DOKUMEN" => $czConaNo, "TANGGAL_DOKUMEN" => null, "TIPE_DOKUMEN" => "02", "SERI_DOKUMEN" => 4]; //kontrak
        $seridokumen = 5;
        foreach ($resumeXBCDoc as $n) {
            $tpb_dokumen[] = ["KODE_JENIS_DOKUMEN" => $n['BCTYPE'], "NOMOR_DOKUMEN" => $n['NOPEN'], "TANGGAL_DOKUMEN" => $n['TGLPEN'], "TIPE_DOKUMEN" => "02", "SERI_DOKUMEN" => $seridokumen];
            $seridokumen++;
        }

        #INSERT CEISA
        ##1 TPB HEADER
        $ZR_TPB_HEADER = $this->TPB_HEADER_imod->insert($tpb_header);
        ##N

        ##2 TPB DOKUMEN
        foreach ($tpb_dokumen as &$n) {
            $n['ID_HEADER'] = $ZR_TPB_HEADER;
        }
        unset($n);
        $this->TPB_DOKUMEN_imod->insertb($tpb_dokumen);
        ##N

        ##3 TPB KEMASAN
        foreach ($tpb_kemasan as &$n) {
            $n['ID_HEADER'] = $ZR_TPB_HEADER;
        }
        unset($n);
        $this->TPB_KEMASAN_imod->insertb($tpb_kemasan);
        ##N

        ##4 TPB BARANG
        foreach ($tpb_barang as &$n) {
            $n['ID_HEADER'] = $ZR_TPB_HEADER;
        }
        unset($n);
        ##N

        ##4 TPB BARANG & BAHAN BAKU
        foreach ($tpb_barang as $n) {
            $_barang = [
                'KODE_BARANG' => substr($n['KODE_BARANG'], 0, 2) == 'PM' ? '-' : $n['KODE_BARANG'], 'POS_TARIF' => $n['POS_TARIF'], 'URAIAN' => $n['URAIAN'], 'TIPE' => $n['TIPE'], 'JUMLAH_SATUAN' => $n['JUMLAH_SATUAN'], 'KODE_SATUAN' => $n['KODE_SATUAN'], 'NETTO' => $n['NETTO'], 'CIF' => $n['CIF'], 'HARGA_PENYERAHAN' => $n['HARGA_PENYERAHAN'], 'SERI_BARANG' => $n['SERI_BARANG'], 'KODE_STATUS' => $n['KODE_STATUS'], 'JUMLAH_BAHAN_BAKU' => $n['JUMLAH_BAHAN_BAKU'], 'ID_HEADER' => $n['ID_HEADER'], 'JUMLAH_KEMASAN' => $n['JUMLAH_KEMASAN'], 'KODE_KEMASAN' => $n['KODE_KEMASAN'],
            ];
            $ZR_TPB_BARANG = $this->TPB_BARANG_imod->insert($_barang);
            foreach ($tpb_bahan_baku as $b) {
                if ($n['KODE_BARANG'] == $b['KODE_BARANG'] && $n['CIF'] == $b['CIF']) {
                    $ZR_TPB_BAHAN_BAKU = $this->TPB_BAHAN_BAKU_imod
                        ->insert([
                            'KODE_JENIS_DOK_ASAL' => $b['KODE_JENIS_DOK_ASAL'], 'NOMOR_DAFTAR_DOK_ASAL' => $b['NOMOR_DAFTAR_DOK_ASAL'], 'TANGGAL_DAFTAR_DOK_ASAL' => $b['TANGGAL_DAFTAR_DOK_ASAL'], 'KODE_KANTOR' => $b['KODE_KANTOR'], 'NOMOR_AJU_DOK_ASAL' => $b['NOMOR_AJU_DOK_ASAL'], 'SERI_BARANG_DOK_ASAL' => $b['SERI_BARANG_DOK_ASAL'], 'SPESIFIKASI_LAIN' => $b['SPESIFIKASI_LAIN'], 'POS_TARIF' => $n['POS_TARIF'], 'HARGA_PENYERAHAN' => ($b['CIF'] * $czharga_matauang), 'KODE_BARANG' => substr($b['KODE_BARANG'], 0, 2) == 'PM' ? '-' : $b['KODE_BARANG'], 'KODE_STATUS' => $b['KODE_STATUS'], 'URAIAN' => $b['URAIAN'], 'TIPE' => $b['TIPE'], 'JUMLAH_SATUAN' => $b['JUMLAH_SATUAN'], 'JENIS_SATUAN' => $b['JENIS_SATUAN'], 'KODE_ASAL_BAHAN_BAKU' => $b['KODE_ASAL_BAHAN_BAKU'], 'NETTO' => 0, 'SERI_BAHAN_BAKU' => $b['SERI_BAHAN_BAKU'], 'SERI_BARANG' => $n['SERI_BARANG'], 'ID_BARANG' => $ZR_TPB_BARANG, 'ID_HEADER' => $ZR_TPB_HEADER,
                        ]);
                    foreach ($tpb_bahan_baku_tarif as $t) {
                        if ($b['KODE_BARANG'] == $t['RITEMCD'] && $b['SERI_BAHAN_BAKU'] == $t['SERI_BAHAN_BAKU']) {
                            $this->TPB_BAHAN_BAKU_TARIF_imod
                                ->insert([
                                    'JENIS_TARIF' => $t['JENIS_TARIF'], 'KODE_TARIF' => $t['KODE_TARIF'], 'NILAI_BAYAR' => $t['NILAI_BAYAR'], 'NILAI_FASILITAS' => $t['NILAI_FASILITAS'], 'KODE_FASILITAS' => $t['KODE_FASILITAS'], 'TARIF_FASILITAS' => $t['TARIF_FASILITAS'], 'TARIF' => $t['TARIF'], 'SERI_BAHAN_BAKU' => $t['SERI_BAHAN_BAKU'], 'ID_BAHAN_BAKU' => $ZR_TPB_BAHAN_BAKU, 'ID_BARANG' => $ZR_TPB_BARANG, 'ID_HEADER' => $ZR_TPB_HEADER,
                                ]);
                        }
                    }
                }
            }
        }
        ##N
        $this->DELV_mod->updatebyVAR(['DLV_POST' => $this->session->userdata('nama'), 'DLV_POSTTM' => $currentDate], ['DLV_ID' => $csj]);
        $myar[] = [
            'cd' => '1', 'msg' => 'Done, check your TPB', 'tpb_barang' => $tpb_barang, 'tpb_bahan_baku' => $tpb_bahan_baku,
        ];
        $this->setPrice(base64_encode($csj));
        $this->gotoque($csj);
        die('{"status" : ' . json_encode($myar) . '}');
    }

    public function out_rm_sp_xls()
    { #sp = sparepart
        $txid = '';
        if (isset($_COOKIE["CKPDLV_NO"])) {
            $txid = $_COOKIE["CKPDLV_NO"];
        } else {
            exit('nothing to be exported');
        }
        $rs = [];

        $rs_do = $this->DELV_mod->select_for_do_rm_rtn_v1($txid);
        $rs_xbc = $this->RCV_mod->select_for_rmrtn_bytxid($txid);
        $delv_description = '';
        foreach ($rs_do as $r) {
            $delv_description = $r['DLV_DSCRPTN'];
            $r['PLOTQT'] = 0;
            foreach ($rs_xbc as &$x) {
                if (
                    $r['DLVRMDOC_ITMID'] == $x['RCV_ITMCD']
                    && $r['DLVRMDOC_AJU'] == $x['RCV_RPNO']
                    && $r['DLVRMDOC_DO'] == $x['RCV_DONO']
                    && $x['RCV_QTY'] > 0
                    && $r['PLOTQT'] != $r['DLVRMDOC_ITMQT']
                ) {
                    $reqbal = $r['DLVRMDOC_ITMQT'] - $r['PLOTQT'];
                    $useqt = 0;
                    if ($reqbal > $x['RCV_QTY']) {
                        $useqt = $x['RCV_QTY'];
                        $r['PLOTQT'] += $x['RCV_QTY'];
                        $x['RCV_QTY'] = 0;
                    } else {
                        $useqt = $reqbal;
                        $r['PLOTQT'] += $reqbal;
                        $x['RCV_QTY'] -= $reqbal;
                    }
                    $rs[] = [
                        'NOMOR_DAFTAR_DOK_ASAL' => $r['DLVRMDOC_NOPEN'], 'TANGGAL_DAFTAR_DOK_ASAL' => $x['RCV_BCDATE'], 'NOMOR_AJU_DOK_ASAL' => strlen($r['DLVRMDOC_AJU']) == 6 ? substr('000000000000000000000000', 0, 26) : $r['DLVRMDOC_AJU'], 'SERI_BARANG_DOK_ASAL' => empty($x['RCV_ZNOURUT']) ? 0 : $x['RCV_ZNOURUT'], 'DONO' => $r['DLVRMDOC_DO'], 'KODE_BARANG' => $r['DLVRMDOC_ITMID'], 'URAIAN' => rtrim($r['MITM_ITMD1']), 'JUMLAH_SATUAN' => $useqt, 'INCDOCQTY' => $x['DOCQTY'], 'PRICE' => $x['RCV_PRPRC'], 'BCTYPE' => $x['RCV_BCTYPE'], 'HSCD' => $x['RCV_HSCD'], 'INVOICE' => $x['RCV_INVNO'],
                    ];
                    if ($r['DLVRMDOC_ITMQT'] == $r['PLOTQT']) {
                        break;
                    }
                }
            }
            unset($x);
        }
        unset($r);
        $spreadsheet = new Spreadsheet();
        $sheet = $spreadsheet->getActiveSheet();
        $sheet->setTitle('RESUME');
        $sheet->setCellValueByColumnAndRow(1, 1, 'DAFTAR BARANG YANG AKAN ' . $delv_description);
        $sheet->mergeCells('A1:K1');
        $sheet->getStyle('A1')->getFont()->setSize(18);
        $sheet->getStyle('A1')->getFont()->setBold(true);
        $sheet->setCellValueByColumnAndRow(3, 2, $txid);
        $sheet->setCellValueByColumnAndRow(1, 3, 'NO URUT');
        $sheet->setCellValueByColumnAndRow(2, 3, 'HS CODE');
        $sheet->setCellValueByColumnAndRow(3, 3, 'URAIAN Barang Material / Bahan Baku');
        $sheet->setCellValueByColumnAndRow(4, 3, 'Kode Barang');
        $sheet->setCellValueByColumnAndRow(5, 3, 'Qty');
        $sheet->setCellValueByColumnAndRow(6, 3, 'Unit Price');
        $sheet->setCellValueByColumnAndRow(7, 3, 'Amount');
        $sheet->setCellValueByColumnAndRow(8, 3, 'AJU');
        $sheet->setCellValueByColumnAndRow(9, 3, 'NO Urut dokumen');
        $sheet->setCellValueByColumnAndRow(10, 3, 'Qty Dokumen');
        $sheet->setCellValueByColumnAndRow(11, 3, 'Jenis BC');
        $sheet->setCellValueByColumnAndRow(12, 3, 'EX.NOPEN');
        $sheet->setCellValueByColumnAndRow(13, 3, 'TGL EX.BC');
        $sheet->setCellValueByColumnAndRow(14, 3, 'NO SURAT JALAN');
        $sheet->setCellValueByColumnAndRow(15, 3, 'INVOICE');
        $sheet->getStyle('A1')->getAlignment()->setHorizontal(\PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER);
        $sheet->getStyle('G:G')->getAlignment()->setHorizontal(\PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER);
        $nourut = 1;
        $i = 4;
        foreach ($rs as $r) {
            $sheet->setCellValueByColumnAndRow(1, $i, $nourut);
            $sheet->setCellValueByColumnAndRow(2, $i, $r['HSCD']);
            $sheet->setCellValueByColumnAndRow(3, $i, $r['URAIAN']);
            $sheet->setCellValueByColumnAndRow(4, $i, $r['KODE_BARANG']);
            $sheet->setCellValueByColumnAndRow(5, $i, $r['JUMLAH_SATUAN']);
            $sheet->setCellValueByColumnAndRow(6, $i, $r['PRICE']);
            $sheet->setCellValueByColumnAndRow(7, $i, $r['PRICE'] * $r['JUMLAH_SATUAN']);
            $sheet->setCellValueByColumnAndRow(8, $i, $r['NOMOR_AJU_DOK_ASAL']);
            $sheet->setCellValueByColumnAndRow(9, $i, $r['SERI_BARANG_DOK_ASAL']);
            $sheet->setCellValueByColumnAndRow(10, $i, $r['INCDOCQTY']);
            $sheet->setCellValueByColumnAndRow(11, $i, $r['BCTYPE']);
            $sheet->setCellValueByColumnAndRow(12, $i, $r['NOMOR_DAFTAR_DOK_ASAL']);
            $sheet->setCellValueByColumnAndRow(13, $i, $r['TANGGAL_DAFTAR_DOK_ASAL']);
            $sheet->setCellValueByColumnAndRow(14, $i, $r['DONO']);
            $sheet->setCellValueByColumnAndRow(15, $i, $r['INVOICE']);
            $nourut++;
            $i++;
        }
        $sheet->getStyle('A3:O' . ($i - 1))->getBorders()->getAllBorders()->setBorderStyle(\PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN);
        $i++;
        $sheet->setCellValueByColumnAndRow(8, $i, 'Approved By');
        $sheet->setCellValueByColumnAndRow(9, $i, 'Checked By');
        $sheet->setCellValueByColumnAndRow(10, $i, 'Prepared By');
        $i += 3;
        $sheet->setCellValueByColumnAndRow(8, $i, 'Indra Andesa');
        $sheet->setCellValueByColumnAndRow(9, $i, 'Sri Wahyu');
        $sheet->setCellValueByColumnAndRow(10, $i, 'Gusti Ayu');
        foreach (range('A', 'O') as $r) {
            $sheet->getColumnDimension($r)->setAutoSize(true);
        }
        $dodis = $txid;
        $stringjudul = "Item List " . $dodis;
        $writer = new Xlsx($spreadsheet);
        $filename = $stringjudul; //save our workbook as this file name

        header('Content-Type: application/vnd.ms-excel');
        header('Content-Disposition: attachment;filename="' . $filename . '.xlsx"');
        header('Cache-Control: max-age=0');
        $writer->save('php://output');
        // die(json_encode(['data' => $rs]));
    }

    public function setck()
    {
        header('Content-Type: application/json');
        $indoc = $this->input->post('indoc');
        $intxid = $this->input->post('intxid');
        $initem = $this->input->post('initem');
        $inqty = $this->input->post('inqty');
        $inline = $this->input->post('inline');
        $itemCount = count($initem);
        $myar = [];
        $tosave = [];
        $ttlsaved = 0;
        $ttlupdated = 0;
        if ($this->DELV_mod->check_Primary(['DLV_ID' => $intxid]) == 0) {
            $myar[] = ['cd' => 0, 'msg' => 'TX ID is not found'];
            die(json_encode(['status' => $myar]));
        }
        if ($this->DLVCK_mod->check_Primary(['DLVCK_TXID' => $intxid])) {
            $newLine = $this->DLVCK_mod->select_lastline($intxid) + 1;
            for ($i = 0; $i < $itemCount; $i++) {
                if ($inline[$i] !== '') {
                    $pwhere = ['DLVCK_TXID' => $intxid, 'DLVCK_LINE' => $inline[$i]];
                    if ($this->DLVCK_mod->check_Primary($pwhere)) {
                        $ttlupdated += $this->DLVCK_mod->updatebyVAR(['DLVCK_QTY' => $inqty[$i], 'DLVCK_ITMCD' => $initem[$i]], $pwhere);
                    } else {
                        $tosave[] = [
                            'DLVCK_TXID' => $intxid, 'DLVCK_CUSTDO' => $indoc, 'DLVCK_ITMCD' => $initem[$i], 'DLVCK_QTY' => $inqty[$i], 'DLVCK_LINE' => $newLine,
                        ];
                        $newLine++;
                    }
                } else {
                    $tosave[] = [
                        'DLVCK_TXID' => $intxid, 'DLVCK_CUSTDO' => $indoc, 'DLVCK_ITMCD' => $initem[$i], 'DLVCK_QTY' => $inqty[$i], 'DLVCK_LINE' => $newLine,
                    ];
                    $newLine++;
                }
            }
            if (count($tosave) > 0) {
                $ttlsaved = $this->DLVCK_mod->insertb($tosave);
            }
        } else {
            for ($i = 0; $i < $itemCount; $i++) {
                $tosave[] = [
                    'DLVCK_TXID' => $intxid, 'DLVCK_CUSTDO' => $indoc, 'DLVCK_ITMCD' => $initem[$i], 'DLVCK_QTY' => $inqty[$i], 'DLVCK_LINE' => $i,
                ];
            }
            $ttlsaved = $this->DLVCK_mod->insertb($tosave);
        }
        $myar[] = ['cd' => '1', 'msg' => $ttlsaved . ' Saved, ' . $ttlupdated . ' Updated'];
        die(json_encode(['status' => $myar]));
    }

    public function getck()
    {
        header('Content-Type: application/json');
        $ptxid = $this->input->get('txid');
        $rs = $this->DLVCK_mod->select_display($ptxid);
        $rsbase = $this->DELV_mod->select_group(
            ['SER_ITMID', 'sum(SER_QTY) DLVQT'],
            ['SER_ITMID'],
            ['DLV_ID' => $ptxid]
        );
        foreach ($rs as &$r) {
            $r['QTY'] = 0;
            $r['STATUS'] = '';
            foreach ($rsbase as &$b) {
                $thebal = $r['DLVCK_QTY'] - $r['QTY'];
                if ($r['DLVCK_ITMCD'] == $b['SER_ITMID'] && $thebal > 0 && $b['DLVQT'] > 0) {
                    if ($thebal > $b['DLVQT']) {
                        $r['QTY'] += $b['DLVQT'];
                        $b['DLVQT'] = 0;
                    } else {
                        $r['QTY'] += $thebal;
                        $b['DLVQT'] -= $thebal;
                    }
                    if ($r['DLVCK_QTY'] == $r['QTY']) {
                        break;
                    }
                }
            }
            unset($b);
            $r['STATUS'] = $r['QTY'] == $r['DLVCK_QTY'] ? 'OK' : 'NOT OK';
        }
        unset($r);
        die(json_encode(['data' => $rs, 'rsbase' => $rsbase]));
    }
    public function getck_h()
    {
        header('Content-Type: application/json');
        $ptxid = $this->input->get('inval');
        $rs = $this->DLVCK_mod->select_display_header(['DLVCK_TXID' => $ptxid]);
        die(json_encode(['data' => $rs]));
    }

    public function cancelposting()
    {
        header('Content-Type: application/json');
        $txid = $this->input->post('msj');
        $rs = $this->DELV_mod->select_bytx($txid);

        #raw material delivered
        if ($this->ITH_mod->check_Primary(['ITH_DOC' => $txid, 'ITH_FORM' => 'OUT-SHP-RM'])) {
            $myar[] = ['cd' => 0, 'msg' => 'It was already delivered'];
            die(json_encode(['status' => $myar]));
        }

        $bctype = '';
        $nomoraju = '';
        foreach ($rs as $r) {
            $bctype = $r['DLV_BCTYPE'];
            $nomoraju = $r['DLV_ZNOMOR_AJU'];
        }

        $id_header = '';
        $tanggal_daftar = null;
        $rsceisa = $this->TPB_HEADER_imod->select_where(['ID', 'TANGGAL_DAFTAR'], ['NOMOR_AJU' => $nomoraju]);
        foreach ($rsceisa as $r) {
            $id_header = $r['ID'];
            $tanggal_daftar = $r['TANGGAL_DAFTAR'];
        }
        if (!empty($tanggal_daftar)) {
            $myar[] = ['cd' => 0, 'msg' => 'Could not cancel, because NOMOR PENDAFTARAN is already exist'];
            die(json_encode(['status' => $myar]));
        }
        $myar[] = ['cd' => 1, 'msg' => 'OK'];
        $param = ['pbctype' => $bctype, 'pnomor_aju' => $nomoraju, 'id_header' => $id_header];
        log_message('error', $_SERVER['REMOTE_ADDR'] . ', step0#, start cancel posting DO:' . $txid);

        switch ($bctype) {
            case '27':
                break;
            case '25':
                log_message('error', $_SERVER['REMOTE_ADDR'] . ', step1#, start cancel posting DO:' . $txid . ' [BARANG_TARIF]');
                $this->TPB_BARANG_TARIF_imod->deleteby_filter(['ID_HEADER' => $id_header]);
                log_message('error', $_SERVER['REMOTE_ADDR'] . ', step1#, finish cancel posting DO:' . $txid . ' [BARANG_TARIF]');

                log_message('error', $_SERVER['REMOTE_ADDR'] . ', step2#, start cancel posting DO:' . $txid . ' [BILLING]');
                $this->TPB_NPWP_BILLING_imod->deleteby_filter(['ID_HEADER' => $id_header]);
                log_message('error', $_SERVER['REMOTE_ADDR'] . ', step2#, finish cancel posting DO:' . $txid . ' [BILLING]');

                log_message('error', $_SERVER['REMOTE_ADDR'] . ', step3#, start cancel posting DO:' . $txid . ' [PUNGUTAN]');
                $this->TPB_PUNGUTAN_imod->deleteby_filter(['ID_HEADER' => $id_header]);
                log_message('error', $_SERVER['REMOTE_ADDR'] . ', step3#, finish cancel posting DO:' . $txid . ' [PUNGUTAN]');
                break;
            case '41':
                break;
        }
        log_message('error', $_SERVER['REMOTE_ADDR'] . ', step4#, start cancel posting DO:' . $txid . ' [DETIL_STATUS]');
        $this->TPB_DETIL_STATUS_imod->deleteby_filter(['ID_HEADER' => $id_header]);
        log_message('error', $_SERVER['REMOTE_ADDR'] . ', step4#, start cancel posting DO:' . $txid . ' [DETIL_STATUS]');

        log_message('error', $_SERVER['REMOTE_ADDR'] . ', step5#, start cancel posting DO:' . $txid . ' [RESPON]');
        $this->TPB_RESPON_imod->deleteby_filter(['ID_HEADER' => $id_header]);
        log_message('error', $_SERVER['REMOTE_ADDR'] . ', step5#, finish cancel posting DO:' . $txid . ' [RESPON]');

        log_message('error', $_SERVER['REMOTE_ADDR'] . ', step6#, start cancel posting DO:' . $txid . ' [BAHAN_BAKU_TARIF]');
        $this->TPB_BAHAN_BAKU_TARIF_imod->deleteby_filter(['ID_HEADER' => $id_header]);
        log_message('error', $_SERVER['REMOTE_ADDR'] . ', step6#, finish cancel posting DO:' . $txid . ' [BAHAN_BAKU_TARIF]');

        log_message('error', $_SERVER['REMOTE_ADDR'] . ', step7#, start cancel posting DO:' . $txid . ' [BAHAN_BAKU]');
        $this->TPB_BAHAN_BAKU_imod->deleteby_filter(['ID_HEADER' => $id_header]);
        log_message('error', $_SERVER['REMOTE_ADDR'] . ', step7#, finish cancel posting DO:' . $txid . ' [BAHAN_BAKU]');

        log_message('error', $_SERVER['REMOTE_ADDR'] . ', step8#, start cancel posting DO:' . $txid . ' [BARANG]');
        $this->TPB_BARANG_imod->deleteby_filter(['ID_HEADER' => $id_header]);
        log_message('error', $_SERVER['REMOTE_ADDR'] . ', step8#, finish cancel posting DO:' . $txid . ' [BARANG]');

        log_message('error', $_SERVER['REMOTE_ADDR'] . ', step9#, start cancel posting DO:' . $txid . ' [KEMASAN]');
        $this->TPB_KEMASAN_imod->deleteby_filter(['ID_HEADER' => $id_header]);
        log_message('error', $_SERVER['REMOTE_ADDR'] . ', step9#, finish cancel posting DO:' . $txid . ' [KEMASAN]');

        log_message('error', $_SERVER['REMOTE_ADDR'] . ', step10#, start cancel posting DO:' . $txid . ' [DOKUMEN]');
        $this->TPB_DOKUMEN_imod->deleteby_filter(['ID_HEADER' => $id_header]);
        log_message('error', $_SERVER['REMOTE_ADDR'] . ', step10#, finish cancel posting DO:' . $txid . ' [DOKUMEN]');

        log_message('error', $_SERVER['REMOTE_ADDR'] . ', step11#, start cancel posting DO:' . $txid . ' [HEADER]');
        $this->TPB_HEADER_imod->deleteby_filter(['ID' => $id_header]);
        log_message('error', $_SERVER['REMOTE_ADDR'] . ', step11#, finish cancel posting DO:' . $txid . ' [HEADER]');

        log_message('error', $_SERVER['REMOTE_ADDR'] . ', step12#, start cancel posting DO:' . $txid . ' [DLVPRC]');
        $this->DLVPRC_mod->deleteby_filter(['DLVPRC_TXID' => $txid]);
        log_message('error', $_SERVER['REMOTE_ADDR'] . ', step12#, finish cancel posting DO:' . $txid . ' [DLVPRC]');

        log_message('error', $_SERVER['REMOTE_ADDR'] . ', step13#, start cancel posting DO:' . $txid . ' [API]');
        $respon = $this->inventory_cancelDO($txid);
        log_message('error', $_SERVER['REMOTE_ADDR'] . ', step13#, finish cancel posting DO:' . $txid . ' [API]');

        log_message('error', $_SERVER['REMOTE_ADDR'] . ', step14#, start cancel posting DO:' . $txid . ' [DLV]');
        $this->DELV_mod->updatebyVAR(['DLV_POST' => null, 'DLV_POSTTM' => null], ['DLV_ID' => $txid]);
        log_message('error', $_SERVER['REMOTE_ADDR'] . ', step14#, finish cancel posting DO:' . $txid . ' [DLV]');

        die(json_encode(['status' => $myar, 'param' => $param, 'api' => $respon]));
    }

    public function exbclist()
    {
        header('Content-Type: application/json');
        $txid = $this->input->get('txid');
        $rs = strlen($txid == 0) ? [] : $this->ZRPSTOCK_mod->select_byTXID($txid);
        die(json_encode(['data' => $rs]));
    }

    public function so_dlv()
    {
        header('Content-Type: application/json');
        $doc = $this->input->get('doc');
        $rs = $this->DLVSO_mod->select_bytxid($doc);
        die(json_encode(['data' => $rs]));
    }

    public function so_mega_dlv()
    {
        header('Content-Type: application/json');
        $doc = $this->input->get('doc');
        $rs = $this->DELV_mod->select_plotted_so_mega($doc);
        foreach ($rs as &$k) {
            if ($k['SISO_SOLINE'] == 'X') {
                $rs_mst_price = $this->XSO_mod->select_latestprice($k['DLV_BSGRP'], $k['DLV_CUSTCD'], "'" . $k['SER_ITMID'] . "'");
                foreach ($rs_mst_price as $r) {
                    $k['SSO2_SLPRC'] = substr($r['MSPR_SLPRC'], 0, 1) == '.' ? '0' . $r['MSPR_SLPRC'] : $r['MSPR_SLPRC'];
                }
            }
        }
        unset($k);
        die(json_encode(['data' => $rs]));
    }

    public function setflag()
    {
        header('Content-Type: application/json');
        $crnt_dt = date('Y-m-d H:i:s');
        $serlist = $this->input->post('inser');
        $inpin = $this->input->post('inpin');
        $myar = [];
        if ($inpin === "WEAGREE") {
            #update DLV_TBL
            $this->DELV_mod->updatebySER(
                ['DLV_CALCU_REMARK' => 'flg_ok', 'DLV_CALCU_USR' => $this->session->userdata('nama'), 'DLV_CALCU_DT' => $crnt_dt],
                $serlist
            );
            #end
            #update SER_TBL
            $this->SER_mod->updatebySER(
                ['SER_RMUSE_COMFG' => 'flg_ok', 'SER_RMUSE_COMFG_USRID' => $this->session->userdata('nama'), 'SER_RMUSE_COMFG_DT' => $crnt_dt],
                $serlist
            );
            #end
            $myar[] = ['cd' => 1, 'msg' => 'go ahead'];
            $rs1 = [];

            foreach ($serlist as $r) {
                $rs1[] = ['ID' => $r];
            }

            $msgs = [];
            $nom = 1;
            foreach ($this->MSG_USERS as $r) {
                $msgs[] = [
                    'MSG_FR' => 'ane', 'MSG_TO' => $r, 'MSG_TXT' => 'A User set some FG  Calculation as OK', 'MSG_REFFDATA' => json_encode($rs1), 'MSG_LINE' => $nom, 'MSG_TOPIC' => 'SKIP_COMPONENT', 'MSG_CREATEDAT' => $crnt_dt,
                ];
                $nom++;
            }
            $this->MSG_mod->insertb($msgs);
        } else {
            $myar[] = ['cd' => 0, 'msg' => 'The PIN is not valid'];
        }
        die(json_encode(['status' => $myar]));
    }

    public function weight()
    {
        header('Content-Type: application/json');
        $txid = $this->input->get('txid');
        $rspackinglist = $this->DELV_mod->select_packinglist_bydono($txid);
        $rs = [];
        foreach ($rspackinglist as $r) {
            $isfound = false;
            foreach ($rs as &$p) {
                if ($r['SI_ITMCD'] == $p['ITEM']) {
                    $isfound = true;
                    $p['QTY'] += $r['SISCN_SERQTY'];
                    $p['NWG'] += $r['MITM_NWG'];
                    $p['GWG'] += $r['MITM_GWG'];
                    break;
                }
            }
            if (!$isfound) {
                $rs[] = [
                    'ITEM' => $r['SI_ITMCD'],
                    'QTY' => $r['SISCN_SERQTY'],
                    'NWG' => $r['MITM_NWG'],
                    'GWG' => $r['MITM_GWG'],
                ];
            }
            unset($p);
        }
        die(json_encode(['data' => $rs]));
    }

    public function form_pab_out()
    {
        $data['ltpb_type'] = $this->MTPB_mod->selectAll();
        $rs = $this->MMDL_mod->select_all(['MMDL_CD', 'MMDL_NM']);
        $strmdl = '';
        foreach ($rs as $r) {
            $strmdl .= "<option value='" . $r['MMDL_CD'] . "'>" . $r['MMDL_NM'] . "</option>";
        }
        $data['modell'] = $strmdl;
        $this->load->view('wms_report/vrpt_pab_out', $data);
    }

    public function pab_out()
    {
        header('Content-Type: application/json');
        $bctype = $this->input->get('indoctype');
        $itemcd = $this->input->get('initmcd');
        $date1 = $this->input->get('indate0');
        $date2 = $this->input->get('indate1');
        $jenis_tpb_tujuan = $this->input->get('intpbtype');
        $tujuan_pengiriman = $this->input->get('instatus');
        $nomaju = $this->input->get('innoaju');
        $itemtype = $this->input->get('itemtype');
        $where = ['TGLPEN >=' => $date1, 'TGLPEN <= ' => $date2];
        if ($nomaju != '') {
            $where['NOMAJU'] = $nomaju;
        }
        if ($bctype != '-') {
            $where['DLV_BCTYPE'] = $bctype;
        }
        if ($itemcd != '') {
            $where['SER_ITMID'] = $itemcd;
        }
        if ($tujuan_pengiriman != '-') {
            $where['DLV_PURPOSE'] = $tujuan_pengiriman;
        }
        if ($jenis_tpb_tujuan != '-') {
            $where['DLV_ZJENIS_TPB_TUJUAN'] = $jenis_tpb_tujuan;
        }
        $like = [];
        $rs = [];
        $trace = '';
        if ($itemtype != '-') {
            if ($itemtype == '10') {
                $trace = '#1';
                $where['MITM_MODEL'] = '1';
                $like['DLV_ID'] = 'RTN';
                $rs = $this->DELV_mod->select_out_pabean_like($where, $like);
            } elseif ($itemtype == '11') {
                $trace = '#2';
                $where['MITM_MODEL'] = '1';
                $like['DLV_ID'] = 'RTN';
                $rs = $this->DELV_mod->select_out_pabean_notlike($where, $like);
            } else {
                $trace = '#3';
                $where['MITM_MODEL'] = $itemtype;
                $rs = $this->DELV_mod->select_out_pabean($where);
            }
        } else {
            $trace = '#4';
            $rs = $this->DELV_mod->select_out_pabean($where);
        }
        die(json_encode(['data' => $rs, 'trace' => $trace]));
    }

    public function remove_ck()
    {
        header('Content-Type: application/json');
        $lineId = $this->input->post('lineId');
        $docNum = $this->input->post('docNum');
        $myar = [];
        if ($this->DLVCK_mod->deleteby_filter(['DLVCK_TXID' => $docNum, 'DLVCK_LINE' => $lineId])) {
            $myar[] = ['cd' => '1', 'msg' => 'Deleted'];
        } else {
            $myar[] = ['cd' => '0', 'msg' => 'Could not delete'];
        }
        die(json_encode(['status' => $myar]));
    }

    public function ceisa_spreadsheet()
    {
        ini_set('max_execution_time', '-1');
        $doc = $this->input->post('doc');
        $rs_head_dlv = $this->DELV_mod->select_header_bydo($doc);
        $NOMOR_AJU = '';
        $KODE_DOKUMEN = '';
        $KODE_KANTOR = '';
        $KODE_KANTOR_BONGKAR = '';
        $KODE_KANTOR_PERIKSA = '';
        $KODE_KANTOR_TUJUAN = '';
        $KODE_KANTOR_EKSPOR = '';
        $KODE_JENIS_IMPOR = '';
        $KODE_JENIS_EKSPOR = '';
        $KODE_JENIS_TPB = '';
        $KODE_JENIS_PLB = '';
        $KODE_JENIS_PROSEDUR = '';
        $KODE_TUJUAN_PEMASUKAN = '';
        $KODE_TUJUAN_PENGIRIMAN = '';
        $KODE_TUJUAN_TPB = '';
        $KODE_CARA_DAGANG = '';
        $KODE_CARA_BAYAR = '';
        $KODE_CARA_BAYAR_LAINNYA = '';
        $KODE_GUDANG_ASAL = '';
        $KODE_GUDANG_TUJUAN = '';
        $KODE_JENIS_KIRIM = '';
        $KODE_JENIS_PENGIRIMAN = '';
        $KODE_KATEGORI_EKSPOR = '';
        $KODE_KATEGORI_MASUK_FTZ = '';
        $KODE_KATEGORI_KELUAR_FTZ = '';
        $KODE_KATEGORI_BARANG_FTZ = '';
        $KODE_LOKASI = '';
        $KODE_LOKASI_BAYAR = '';
        $LOKASI_ASAL = '';
        $LOKASI_TUJUAN = '';
        $KODE_DAERAH_ASAL = '';
        $KODE_GUDANG_ASAL = '';
        $KODE_GUDANG_TUJUAN = '';
        $KODE_NEGARA_TUJUAN = '';
        $KODE_TUTUP_PU = '';
        $NOMOR_BC11 = '';
        $TANGGAL_BC11 = '';
        $NOMOR_POS = '';
        $NOMOR_SUB_POS = '';
        $KODE_PELABUHAN_BONGKAR = '';
        $KODE_PELABUHAN_MUAT = '';
        $KODE_PELABUHAN_MUAT_AKHIR = '';
        $KODE_PELABUHAN_TRANSIT = '';
        $KODE_PELABUHAN_TUJUAN = '';
        $KODE_PELABUHAN_EKSPOR = '';
        $KODE_TPS = '';
        $TANGGAL_BERANGKAT = '';
        $TANGGAL_EKSPOR = '';
        $TANGGAL_MASUK = '';
        $TANGGAL_MUAT = '';
        $TANGGAL_TIBA = '';
        $TANGGAL_PERIKSA = '';
        $TEMPAT_STUFFING = '';
        $TANGGAL_STUFFING = '';
        $KODE_TANDA_PENGAMAN = '';
        $JUMLAH_TANDA_PENGAMAN = '';
        $FLAG_CURAH = '';
        $FLAG_SDA = '';
        $FLAG_VD = '';
        $FLAG_AP_BK = '';
        $FLAG_MIGAS = '';
        $KODE_ASURANSI = '';
        $ASURANSI = '';
        $NILAI_BARANG = '';
        $NILAI_INCOTERM = '';
        $NILAI_MAKLON = '';
        $CIF = 0;
        $HARGA_PENYERAHAN = 0;
        $NDPBM = 0;
        $BRUTO = 0;
        $NETTO = 0;
        $KOTA_PERNYATAAN = 'CIKARANG';
        $TANGGAL_PERNYATAAN = '';
        $NAMA_PERNYATAAN = '';
        $JABATAN_PERNYATAAN = '';
        $KODE_VALUTA = '';
        $KODE_INCOTERM = '';
        $KODE_JASA_KENA_PAJAK = '';
        $NOMOR_BUKTI_BAYAR = '';
        $TANGGAL_BUKTI_BAYAR = '';
        $KODE_JENIS_NILAI = '';
        $ccustdate = '';
        $czcurrency = '';
        foreach ($rs_head_dlv as $r) {
            $NOMOR_AJU = $r['DLV_ZNOMOR_AJU'];
            $KODE_DOKUMEN = $r['DLV_BCTYPE'];
            $KODE_KANTOR = $r['DLV_FROMOFFICE'];
            $KODE_KANTOR_TUJUAN = $r['DLV_DESTOFFICE'];
            $ccustdate = $r['DLV_BCDATE'];
            $TANGGAL_PERNYATAAN = $ccustdate;
            $czcurrency = trim($r['MCUS_CURCD']);
            $KODE_VALUTA = $czcurrency;
            $KODE_JENIS_TPB = $r['DLV_ZJENIS_TPB_ASAL'];
            $KODE_TUJUAN_PENGIRIMAN = $r['DLV_PURPOSE'];
            $KODE_TUJUAN_TPB = $r['DLV_ZJENIS_TPB_TUJUAN'];
            $NAMA_PERNYATAAN = $r['DLVH_PEMBERITAHU'];
            $JABATAN_PERNYATAAN = $r['DLVH_JABATAN'];
        }
        $message = '';
        if ($this->DELV_mod->check_Primary(['DLV_ID' => $doc]) == 0) {
            $message = 'DO is not found';
        }
        $SERI_BARANG = 1;
        $aspBOX = 0;
        $rsitem_p_price = $this->setPriceRS(base64_encode($doc));
        $rspackinglist = $this->DELV_mod->select_packinglist_bydono($doc);
        $rsplotrm_per_fgprice = $this->perprice($doc, $rsitem_p_price);
        $cz_h_JUMLAH_BARANG = count($rsitem_p_price);
        $rscurr = $this->MEXRATE_mod->selectfor_posting($ccustdate, $czcurrency);
        if (count($rscurr) == 0) {
            $message = "Please fill exchange rate data !";
        } else {
            foreach ($rscurr as $r) {
                $czharga_matauang = $r->MEXRATE_VAL;
                $NDPBM = $czharga_matauang;
                break;
            }
        }
        foreach ($rspackinglist as $r) {
            $BRUTO += $r['MITM_GWG'];
        }
        foreach ($rsitem_p_price as $r) {
            if ($r['MITM_HSCD'] == '') {
                $myar[] = ["cd" => "0", "msg" => "HSCODE FG is empty"];
                die('{"status":' . json_encode($myar) . ',"data":' . json_encode($rsitem_p_price) . '}');
            }
            $t_HARGA_PENYERAHAN = $r['CIF'] * $czharga_matauang;
            $CIF += $r['CIF'];
            $NETTO += $r['NWG'];
            $HARGA_PENYERAHAN += $t_HARGA_PENYERAHAN;
            $tpb_barang[] = [
                'KODE_BARANG' => $r['SSO2_MDLCD'], 'POS_TARIF' => $r['MITM_HSCD'], 'URAIAN' => $r['MITM_ITMD1'], 'JUMLAH_SATUAN' => $r['SISOQTY'], 'KODE_SATUAN' => $r['MITM_STKUOM'] == 'PCS' ? 'PCE' : $r['MITM_STKUOM'], 'NETTO' => $r['NWG'], 'CIF' => round($r['CIF'], 2), 'HARGA_PENYERAHAN' => $t_HARGA_PENYERAHAN, 'SERI_BARANG' => $SERI_BARANG, 'KODE_STATUS' => '02',
            ];
            $SERI_BARANG++;
            if (strpos(strtoupper($r['SSO2_MDLCD']), 'ASP') !== false) {
                $aspBOX += $r['SISOQTY'];
            }
        }
        if ($aspBOX) {
            $cz_JUMLAH_KEMASAN = $aspBOX;
        }
        $spreadsheet = new Spreadsheet();
        $sheet = $spreadsheet->getActiveSheet();
        $sheet->setTitle('HEADER');
        #define column
        $sheet->setCellValueByColumnAndRow(1, 1, 'NOMOR AJU');
        $sheet->setCellValueByColumnAndRow(2, 1, 'KODE DOKUMEN');
        $sheet->setCellValueByColumnAndRow(3, 1, 'KODE KANTOR');
        $sheet->setCellValueByColumnAndRow(4, 1, 'KODE KANTOR BONGKAR');
        $sheet->setCellValueByColumnAndRow(5, 1, 'KODE KANTOR PERIKSA');
        $sheet->setCellValueByColumnAndRow(6, 1, 'KODE KANTOR TUJUAN');
        $sheet->setCellValueByColumnAndRow(7, 1, 'KODE KANTOR EKSPOR');
        $sheet->setCellValueByColumnAndRow(8, 1, 'KODE JENIS IMPOR');
        $sheet->setCellValueByColumnAndRow(9, 1, 'KODE JENIS EKSPOR');
        $sheet->setCellValueByColumnAndRow(10, 1, 'KODE JENIS TPB');
        $sheet->setCellValueByColumnAndRow(11, 1, 'KODE JENIS PLB');
        $sheet->setCellValueByColumnAndRow(12, 1, 'KODE JENIS PROSEDUR');
        $sheet->setCellValueByColumnAndRow(13, 1, 'KODE TUJUAN PEMASUKAN');
        $sheet->setCellValueByColumnAndRow(14, 1, 'KODE TUJUAN PENGIRIMAN');
        $sheet->setCellValueByColumnAndRow(15, 1, 'KODE TUJUAN TPB');
        $sheet->setCellValueByColumnAndRow(16, 1, 'KODE CARA DAGANG');
        $sheet->setCellValueByColumnAndRow(17, 1, 'KODE CARA BAYAR');
        $sheet->setCellValueByColumnAndRow(18, 1, 'KODE CARA BAYAR LAINNYA');
        $sheet->setCellValueByColumnAndRow(19, 1, 'KODE GUDANG ASAL');
        $sheet->setCellValueByColumnAndRow(20, 1, 'KODE GUDANG TUJUAN');
        $sheet->setCellValueByColumnAndRow(21, 1, 'KODE JENIS KIRIM');
        $sheet->setCellValueByColumnAndRow(22, 1, 'KODE JENIS PENGIRIMAN');
        $sheet->setCellValueByColumnAndRow(23, 1, 'KODE KATEGORI EKSPOR');
        $sheet->setCellValueByColumnAndRow(24, 1, 'KODE KATEGORI MASUK FTZ');
        $sheet->setCellValueByColumnAndRow(25, 1, 'KODE KATEGORI KELUAR FTZ');
        $sheet->setCellValueByColumnAndRow(26, 1, 'KODE KATEGORI BARANG FTZ');
        $sheet->setCellValueByColumnAndRow(27, 1, 'KODE LOKASI');
        $sheet->setCellValueByColumnAndRow(28, 1, 'KODE LOKASI BAYAR');
        $sheet->setCellValueByColumnAndRow(29, 1, 'LOKASI ASAL');
        $sheet->setCellValueByColumnAndRow(30, 1, 'LOKASI TUJUAN');
        $sheet->setCellValueByColumnAndRow(31, 1, 'KODE DAERAH ASAL');
        $sheet->setCellValueByColumnAndRow(32, 1, 'KODE GUDANG ASAL');
        $sheet->setCellValueByColumnAndRow(33, 1, 'KODE GUDANG TUJUAN');
        $sheet->setCellValueByColumnAndRow(34, 1, 'KODE NEGARA TUJUAN');
        $sheet->setCellValueByColumnAndRow(35, 1, 'KODE TUTUP PU');
        $sheet->setCellValueByColumnAndRow(36, 1, 'NOMOR BC11');
        $sheet->setCellValueByColumnAndRow(37, 1, 'TANGGAL BC11');
        $sheet->setCellValueByColumnAndRow(38, 1, 'NOMOR POS');
        $sheet->setCellValueByColumnAndRow(39, 1, 'NOMOR SUB POS');
        $sheet->setCellValueByColumnAndRow(40, 1, 'KODE PELABUHAN BONGKAR');
        $sheet->setCellValueByColumnAndRow(41, 1, 'KODE PELABUHAN MUAT');
        $sheet->setCellValueByColumnAndRow(42, 1, 'KODE PELABUHAN MUAT AKHIR');
        $sheet->setCellValueByColumnAndRow(43, 1, 'KODE PELABUHAN TRANSIT');
        $sheet->setCellValueByColumnAndRow(44, 1, 'KODE PELABUHAN TUJUAN');
        $sheet->setCellValueByColumnAndRow(45, 1, 'KODE PELABUHAN EKSPOR');
        $sheet->setCellValueByColumnAndRow(46, 1, 'KODE TPS');
        $sheet->setCellValueByColumnAndRow(47, 1, 'TANGGAL BERANGKAT');
        $sheet->setCellValueByColumnAndRow(48, 1, 'TANGGAL EKSPOR');
        $sheet->setCellValueByColumnAndRow(49, 1, 'TANGGAL MASUK');
        $sheet->setCellValueByColumnAndRow(50, 1, 'TANGGAL MUAT');
        $sheet->setCellValueByColumnAndRow(51, 1, 'TANGGAL TIBA');
        $sheet->setCellValueByColumnAndRow(52, 1, 'TANGGAL PERIKSA');
        $sheet->setCellValueByColumnAndRow(53, 1, 'TEMPAT STUFFING');
        $sheet->setCellValueByColumnAndRow(54, 1, 'TANGGAL STUFFING');
        $sheet->setCellValueByColumnAndRow(55, 1, 'KODE TANDA PENGAMAN');
        $sheet->setCellValueByColumnAndRow(56, 1, 'JUMLAH TANDA PENGAMAN');
        $sheet->setCellValueByColumnAndRow(57, 1, 'FLAG CURAH');
        $sheet->setCellValueByColumnAndRow(58, 1, 'FLAG SDA');
        $sheet->setCellValueByColumnAndRow(59, 1, 'FLAG VD');
        $sheet->setCellValueByColumnAndRow(60, 1, 'FLAG AP BK');
        $sheet->setCellValueByColumnAndRow(61, 1, 'FLAG MIGAS');
        $sheet->setCellValueByColumnAndRow(62, 1, 'KODE ASURANSI');
        $sheet->setCellValueByColumnAndRow(63, 1, 'ASURANSI');
        $sheet->setCellValueByColumnAndRow(64, 1, 'NILAI BARANG');
        $sheet->setCellValueByColumnAndRow(65, 1, 'NILAI INCOTERM');
        $sheet->setCellValueByColumnAndRow(66, 1, 'NILAI MAKLON');
        $sheet->setCellValueByColumnAndRow(67, 1, 'ASURANSI');
        $sheet->setCellValueByColumnAndRow(68, 1, 'FREIGHT');
        $sheet->setCellValueByColumnAndRow(69, 1, 'FOB');
        $sheet->setCellValueByColumnAndRow(70, 1, 'BIAYA TAMBAHAN');
        $sheet->setCellValueByColumnAndRow(71, 1, 'BIAYA PENGURANG');
        $sheet->setCellValueByColumnAndRow(72, 1, 'VD');
        $sheet->setCellValueByColumnAndRow(73, 1, 'CIF');
        $sheet->setCellValueByColumnAndRow(74, 1, 'HARGA_PENYERAHAN');
        $sheet->setCellValueByColumnAndRow(75, 1, 'NDPBM');
        $sheet->setCellValueByColumnAndRow(76, 1, 'TOTAL DANA SAWIT');
        $sheet->setCellValueByColumnAndRow(77, 1, 'DASAR PENGENAAN PAJAK');
        $sheet->setCellValueByColumnAndRow(78, 1, 'NILAI JASA');
        $sheet->setCellValueByColumnAndRow(79, 1, 'UANG MUKA');
        $sheet->setCellValueByColumnAndRow(80, 1, 'BRUTO');
        $sheet->setCellValueByColumnAndRow(81, 1, 'NETTO');
        $sheet->setCellValueByColumnAndRow(82, 1, 'VOLUME');
        $sheet->setCellValueByColumnAndRow(83, 1, 'KOTA PERNYATAAN');
        $sheet->setCellValueByColumnAndRow(84, 1, 'TANGGAL PERNYATAAN');
        $sheet->setCellValueByColumnAndRow(85, 1, 'NAMA PERNYATAAN');
        $sheet->setCellValueByColumnAndRow(86, 1, 'JABATAN PERNYATAAN');
        $sheet->setCellValueByColumnAndRow(87, 1, 'KODE VALUTA');
        $sheet->setCellValueByColumnAndRow(88, 1, 'KODE INCOTERM');
        $sheet->setCellValueByColumnAndRow(89, 1, 'KODE JASA KENA PAJAK');
        $sheet->setCellValueByColumnAndRow(90, 1, 'NOMOR BUKTI BAYAR');
        $sheet->setCellValueByColumnAndRow(91, 1, 'TANGGAL BUKTI BAYAR');
        $sheet->setCellValueByColumnAndRow(92, 1, 'KODE JENIS NILAI');
        # populate column
        $sheet->setCellValueByColumnAndRow(1, 2, empty($NOMOR_AJU) ? $message : $NOMOR_AJU);
        $sheet->setCellValueByColumnAndRow(2, 2, $KODE_DOKUMEN);
        $sheet->setCellValueByColumnAndRow(3, 2, $KODE_KANTOR);
        $sheet->setCellValueByColumnAndRow(4, 2, $KODE_KANTOR_BONGKAR);
        $sheet->setCellValueByColumnAndRow(5, 2, $KODE_KANTOR_PERIKSA);
        $sheet->setCellValueByColumnAndRow(6, 2, $KODE_KANTOR_TUJUAN);
        $sheet->setCellValueByColumnAndRow(7, 2, $KODE_KANTOR_EKSPOR);
        $sheet->setCellValueByColumnAndRow(8, 2, $KODE_JENIS_IMPOR);
        $sheet->setCellValueByColumnAndRow(9, 2, $KODE_JENIS_EKSPOR);
        $sheet->setCellValueByColumnAndRow(10, 2, $KODE_JENIS_TPB);
        $sheet->setCellValueByColumnAndRow(11, 2, $KODE_JENIS_PLB);
        $sheet->setCellValueByColumnAndRow(12, 2, $KODE_JENIS_PROSEDUR);
        $sheet->setCellValueByColumnAndRow(13, 2, $KODE_TUJUAN_PEMASUKAN);
        $sheet->setCellValueByColumnAndRow(14, 2, $KODE_TUJUAN_PENGIRIMAN);
        $sheet->setCellValueByColumnAndRow(15, 2, $KODE_TUJUAN_TPB);
        $sheet->setCellValueByColumnAndRow(16, 2, $KODE_CARA_DAGANG);
        $sheet->setCellValueByColumnAndRow(17, 2, $KODE_CARA_BAYAR);
        $sheet->setCellValueByColumnAndRow(18, 2, $KODE_CARA_BAYAR_LAINNYA);
        $sheet->setCellValueByColumnAndRow(19, 2, $KODE_GUDANG_ASAL);
        $sheet->setCellValueByColumnAndRow(20, 2, $KODE_GUDANG_TUJUAN);
        $sheet->setCellValueByColumnAndRow(21, 2, $KODE_JENIS_KIRIM);
        $sheet->setCellValueByColumnAndRow(22, 2, $KODE_JENIS_PENGIRIMAN);
        $sheet->setCellValueByColumnAndRow(23, 2, $KODE_KATEGORI_EKSPOR);
        $sheet->setCellValueByColumnAndRow(24, 2, $KODE_KATEGORI_MASUK_FTZ);
        $sheet->setCellValueByColumnAndRow(25, 2, $KODE_KATEGORI_KELUAR_FTZ);
        $sheet->setCellValueByColumnAndRow(26, 2, $KODE_KATEGORI_BARANG_FTZ);
        $sheet->setCellValueByColumnAndRow(27, 2, $KODE_LOKASI);
        $sheet->setCellValueByColumnAndRow(28, 2, $KODE_LOKASI_BAYAR);
        $sheet->setCellValueByColumnAndRow(29, 2, $LOKASI_ASAL);
        $sheet->setCellValueByColumnAndRow(30, 2, $LOKASI_TUJUAN);
        $sheet->setCellValueByColumnAndRow(31, 2, $KODE_DAERAH_ASAL);
        $sheet->setCellValueByColumnAndRow(32, 2, $KODE_GUDANG_ASAL);
        $sheet->setCellValueByColumnAndRow(33, 2, $KODE_GUDANG_TUJUAN);
        $sheet->setCellValueByColumnAndRow(34, 2, $KODE_NEGARA_TUJUAN);
        $sheet->setCellValueByColumnAndRow(35, 2, $KODE_TUTUP_PU);
        $sheet->setCellValueByColumnAndRow(36, 2, $NOMOR_BC11);
        $sheet->setCellValueByColumnAndRow(37, 2, $TANGGAL_BC11);
        $sheet->setCellValueByColumnAndRow(38, 2, $NOMOR_POS);
        $sheet->setCellValueByColumnAndRow(39, 2, $NOMOR_SUB_POS);
        $sheet->setCellValueByColumnAndRow(40, 2, $KODE_PELABUHAN_BONGKAR);
        $sheet->setCellValueByColumnAndRow(41, 2, $KODE_PELABUHAN_MUAT);
        $sheet->setCellValueByColumnAndRow(42, 2, $KODE_PELABUHAN_MUAT_AKHIR);
        $sheet->setCellValueByColumnAndRow(43, 2, $KODE_PELABUHAN_TRANSIT);
        $sheet->setCellValueByColumnAndRow(44, 2, $KODE_PELABUHAN_TUJUAN);
        $sheet->setCellValueByColumnAndRow(45, 2, $KODE_PELABUHAN_EKSPOR);
        $sheet->setCellValueByColumnAndRow(46, 2, $KODE_TPS);
        $sheet->setCellValueByColumnAndRow(47, 2, $TANGGAL_BERANGKAT);
        $sheet->setCellValueByColumnAndRow(48, 2, $TANGGAL_EKSPOR);
        $sheet->setCellValueByColumnAndRow(49, 2, $TANGGAL_MASUK);
        $sheet->setCellValueByColumnAndRow(50, 2, $TANGGAL_MUAT);
        $sheet->setCellValueByColumnAndRow(51, 2, $TANGGAL_TIBA);
        $sheet->setCellValueByColumnAndRow(52, 2, $TANGGAL_PERIKSA);
        $sheet->setCellValueByColumnAndRow(53, 2, $TEMPAT_STUFFING);
        $sheet->setCellValueByColumnAndRow(54, 2, $TANGGAL_STUFFING);
        $sheet->setCellValueByColumnAndRow(55, 2, $KODE_TANDA_PENGAMAN);
        $sheet->setCellValueByColumnAndRow(56, 2, $JUMLAH_TANDA_PENGAMAN);
        $sheet->setCellValueByColumnAndRow(57, 2, $FLAG_CURAH);
        $sheet->setCellValueByColumnAndRow(58, 2, $FLAG_SDA);
        $sheet->setCellValueByColumnAndRow(59, 2, $FLAG_VD);
        $sheet->setCellValueByColumnAndRow(60, 2, $FLAG_AP_BK);
        $sheet->setCellValueByColumnAndRow(61, 2, $FLAG_MIGAS);
        $sheet->setCellValueByColumnAndRow(62, 2, $KODE_ASURANSI);
        $sheet->setCellValueByColumnAndRow(63, 2, $ASURANSI);
        $sheet->setCellValueByColumnAndRow(64, 2, $NILAI_BARANG);
        $sheet->setCellValueByColumnAndRow(65, 2, $NILAI_INCOTERM);
        $sheet->setCellValueByColumnAndRow(66, 2, $NILAI_MAKLON);
        $sheet->setCellValueByColumnAndRow(67, 2, '');
        $sheet->setCellValueByColumnAndRow(68, 2, '');
        $sheet->setCellValueByColumnAndRow(69, 2, '');
        $sheet->setCellValueByColumnAndRow(70, 2, '');
        $sheet->setCellValueByColumnAndRow(71, 2, '');
        $sheet->setCellValueByColumnAndRow(72, 2, '');
        $sheet->setCellValueByColumnAndRow(73, 2, $CIF);
        $sheet->setCellValueByColumnAndRow(74, 2, $HARGA_PENYERAHAN);
        $sheet->setCellValueByColumnAndRow(75, 2, $NDPBM);
        $sheet->setCellValueByColumnAndRow(76, 2, '');
        $sheet->setCellValueByColumnAndRow(77, 2, '');
        $sheet->setCellValueByColumnAndRow(78, 2, '');
        $sheet->setCellValueByColumnAndRow(79, 2, '');
        $sheet->setCellValueByColumnAndRow(80, 2, $BRUTO);
        $sheet->setCellValueByColumnAndRow(81, 2, $NETTO);
        $sheet->setCellValueByColumnAndRow(82, 2, '');
        $sheet->setCellValueByColumnAndRow(83, 2, $KOTA_PERNYATAAN);
        $sheet->setCellValueByColumnAndRow(84, 2, $TANGGAL_PERNYATAAN);
        $sheet->setCellValueByColumnAndRow(85, 2, $NAMA_PERNYATAAN);
        $sheet->setCellValueByColumnAndRow(86, 2, $JABATAN_PERNYATAAN);
        $sheet->setCellValueByColumnAndRow(87, 2, $KODE_VALUTA);
        $sheet->setCellValueByColumnAndRow(88, 2, $KODE_INCOTERM);
        $sheet->setCellValueByColumnAndRow(89, 2, $KODE_JASA_KENA_PAJAK);
        $sheet->setCellValueByColumnAndRow(90, 2, $NOMOR_BUKTI_BAYAR);
        $sheet->setCellValueByColumnAndRow(91, 2, $TANGGAL_BUKTI_BAYAR);
        $sheet->setCellValueByColumnAndRow(92, 2, $KODE_JENIS_NILAI);
        foreach (range('A', 'Z') as $v) {
            $sheet->getColumnDimension($v)->setAutoSize(true);
        }

        $sheet = $spreadsheet->createSheet();
        $sheet->setTitle('ENTITAS');
        $sheet->setCellValueByColumnAndRow(1, 1, 'NOMOR AJU');
        $sheet->setCellValueByColumnAndRow(2, 1, 'SERI');
        $sheet->setCellValueByColumnAndRow(3, 1, 'KODE ENTITAS');
        $sheet->setCellValueByColumnAndRow(4, 1, 'KODE JENIS IDENTITAS');
        $sheet->setCellValueByColumnAndRow(5, 1, 'NOMOR IDENTITAS');
        $sheet->setCellValueByColumnAndRow(6, 1, 'NAMA ENTITAS');
        $sheet->setCellValueByColumnAndRow(7, 1, 'ALAMAT ENTITAS');
        $sheet->setCellValueByColumnAndRow(8, 1, 'NIB ENTITAS');
        $sheet->setCellValueByColumnAndRow(9, 1, 'KODE JENIS API');
        $sheet->setCellValueByColumnAndRow(10, 1, 'KODE STATUS');
        $sheet->setCellValueByColumnAndRow(11, 1, 'NOMOR IJIN ENTITAS');
        $sheet->setCellValueByColumnAndRow(12, 1, 'TANGGAL IJIN ENTITAS');
        $sheet->setCellValueByColumnAndRow(13, 1, 'KODE NEGARA');
        $sheet->setCellValueByColumnAndRow(14, 1, 'NIPER ENTITAS');
        $sheet->setCellValueByColumnAndRow(1, 2, $NOMOR_AJU);
        $sheet->setCellValueByColumnAndRow(2, 2, 1);
        foreach (range('A', 'Z') as $v) {
            $sheet->getColumnDimension($v)->setAutoSize(true);
        }

        $sheet = $spreadsheet->createSheet();
        $sheet->setTitle('DOKUMEN');
        $sheet->setCellValueByColumnAndRow(1, 1, 'NOMOR AJU');
        $sheet->setCellValueByColumnAndRow(2, 1, 'SERI');
        $sheet->setCellValueByColumnAndRow(3, 1, 'KODE DOKUMEN');
        $sheet->setCellValueByColumnAndRow(4, 1, 'NOMOR DOKUMEN');
        $sheet->setCellValueByColumnAndRow(5, 1, 'TANGGAL DOKUMEN');
        $sheet->setCellValueByColumnAndRow(6, 1, 'KODE FASILITAS');
        $sheet->setCellValueByColumnAndRow(7, 1, 'KODE IJIN');
        foreach (range('A', 'Z') as $v) {
            $sheet->getColumnDimension($v)->setAutoSize(true);
        }

        $sheet = $spreadsheet->createSheet();
        $sheet->setTitle('PENGANGKUT');
        $sheet->setCellValueByColumnAndRow(1, 1, 'NOMOR AJU');
        $sheet->setCellValueByColumnAndRow(2, 1, 'SERI');
        $sheet->setCellValueByColumnAndRow(3, 1, 'KODE CARA ANGKUT');
        $sheet->setCellValueByColumnAndRow(4, 1, 'NAMA PENGANGKUT');
        $sheet->setCellValueByColumnAndRow(5, 1, 'NOMOR PENGANGKUT');
        $sheet->setCellValueByColumnAndRow(6, 1, 'KODE BENDERA');
        $sheet->setCellValueByColumnAndRow(7, 1, 'CALL SIGN');
        $sheet->setCellValueByColumnAndRow(8, 1, 'FLAG ANGKUT PLB');
        foreach (range('A', 'Z') as $v) {
            $sheet->getColumnDimension($v)->setAutoSize(true);
        }

        $sheet = $spreadsheet->createSheet();
        $sheet->setTitle('KEMASAN');
        $sheet->setCellValueByColumnAndRow(1, 1, 'NOMOR AJU');
        $sheet->setCellValueByColumnAndRow(2, 1, 'SERI');
        $sheet->setCellValueByColumnAndRow(3, 1, 'KODE KEMASAN');
        $sheet->setCellValueByColumnAndRow(4, 1, 'JUMLAH KEMASAN');
        $sheet->setCellValueByColumnAndRow(5, 1, 'MEREK');
        foreach (range('A', 'Z') as $v) {
            $sheet->getColumnDimension($v)->setAutoSize(true);
        }

        $sheet = $spreadsheet->createSheet();
        $sheet->setTitle('KONTAINER');
        $sheet->setCellValueByColumnAndRow(1, 1, 'NOMOR AJU');
        $sheet->setCellValueByColumnAndRow(2, 1, 'SERI');
        $sheet->setCellValueByColumnAndRow(3, 1, 'NOMOR KONTINER');
        $sheet->setCellValueByColumnAndRow(4, 1, 'KODE UKURAN KONTAINER');
        $sheet->setCellValueByColumnAndRow(5, 1, 'KODE JENIS KONTAINER');
        $sheet->setCellValueByColumnAndRow(6, 1, 'KODE TIPE KONTAINER');
        foreach (range('A', 'Z') as $v) {
            $sheet->getColumnDimension($v)->setAutoSize(true);
        }

        $sheet = $spreadsheet->createSheet();
        $sheet->setTitle('BARANG');
        $sheet->setCellValueByColumnAndRow(1, 1, 'NOMOR AJU');
        $sheet->setCellValueByColumnAndRow(2, 1, 'SERI BARANG');
        $sheet->setCellValueByColumnAndRow(3, 1, 'HS');
        $sheet->setCellValueByColumnAndRow(4, 1, 'KODE BARANG');
        $sheet->setCellValueByColumnAndRow(5, 1, 'URAIAN');
        $sheet->setCellValueByColumnAndRow(6, 1, 'MEREK');
        $sheet->setCellValueByColumnAndRow(7, 1, 'TIPE');
        $sheet->setCellValueByColumnAndRow(8, 1, 'UKURAN');
        $sheet->setCellValueByColumnAndRow(9, 1, 'SPESIFIKASI LAIN');
        $sheet->setCellValueByColumnAndRow(10, 1, 'KODE SATUAN');
        $sheet->setCellValueByColumnAndRow(11, 1, 'JUMLAH SATUAN');
        $sheet->setCellValueByColumnAndRow(12, 1, 'KODE KEMASAN');
        $sheet->setCellValueByColumnAndRow(13, 1, 'JUMLAH KEMASAN');
        $sheet->setCellValueByColumnAndRow(14, 1, 'KODE DOKUMEN ASAL');
        $sheet->setCellValueByColumnAndRow(15, 1, 'KODE KANTOR ASAL');
        $sheet->setCellValueByColumnAndRow(16, 1, 'NOMOR DAFTAR ASAL');
        $sheet->setCellValueByColumnAndRow(17, 1, 'TANGGAL DAFTAR ASAL');
        $sheet->setCellValueByColumnAndRow(18, 1, 'NOMOR AJU ASAL');
        $sheet->setCellValueByColumnAndRow(19, 1, 'SERI BARANG ASAL');
        $sheet->setCellValueByColumnAndRow(20, 1, 'NETTO');
        $sheet->setCellValueByColumnAndRow(21, 1, 'BRUTO');
        $sheet->setCellValueByColumnAndRow(22, 1, 'VOLUME');
        $sheet->setCellValueByColumnAndRow(23, 1, 'SALDO AWAL');
        $sheet->setCellValueByColumnAndRow(24, 1, 'SALDO AKHIR');
        $sheet->setCellValueByColumnAndRow(25, 1, 'JUMLAH REALISASI');
        $sheet->setCellValueByColumnAndRow(26, 1, 'CIF');
        $sheet->setCellValueByColumnAndRow(27, 1, 'CIF RUPIAH');
        $sheet->setCellValueByColumnAndRow(28, 1, 'NDPBM');
        $sheet->setCellValueByColumnAndRow(29, 1, 'FOB');
        $sheet->setCellValueByColumnAndRow(30, 1, 'ASURANSI');
        $sheet->setCellValueByColumnAndRow(31, 1, 'FREIGHT');
        $sheet->setCellValueByColumnAndRow(32, 1, 'NILAI TAMBAH');
        $sheet->setCellValueByColumnAndRow(33, 1, 'DISKON');
        $sheet->setCellValueByColumnAndRow(34, 1, 'HARGA PENYERAHAN');
        $sheet->setCellValueByColumnAndRow(35, 1, 'HARGA PEROLEHAN');
        $sheet->setCellValueByColumnAndRow(36, 1, 'HARGA SATUAN');
        $sheet->setCellValueByColumnAndRow(37, 1, 'HARGA EKSPOR');
        $sheet->setCellValueByColumnAndRow(38, 1, 'HARGA PATOKAN');
        $sheet->setCellValueByColumnAndRow(39, 1, 'NILAI BARANG');
        $sheet->setCellValueByColumnAndRow(40, 1, 'NILAI JASA');
        $sheet->setCellValueByColumnAndRow(41, 1, 'NILAI DANA SAWIT');
        $sheet->setCellValueByColumnAndRow(42, 1, 'NILAI DEVISA');
        $sheet->setCellValueByColumnAndRow(43, 1, 'PERSENTASE IMPOR');
        $sheet->setCellValueByColumnAndRow(44, 1, 'KODE ASAL BARANG');
        $sheet->setCellValueByColumnAndRow(45, 1, 'KODE DAERAH ASAL');
        $sheet->setCellValueByColumnAndRow(46, 1, 'KODE GUNA BARANG');
        $sheet->setCellValueByColumnAndRow(47, 1, 'KODE JENIS NILAI');
        $sheet->setCellValueByColumnAndRow(48, 1, 'JATUH TEMPO ROYALTI');
        $sheet->setCellValueByColumnAndRow(49, 1, 'KODE KATEGORI BARANG');
        $sheet->setCellValueByColumnAndRow(50, 1, 'KODE KONDISI BARANG');
        $sheet->setCellValueByColumnAndRow(51, 1, 'KODE NEGARA ASAL');
        $sheet->setCellValueByColumnAndRow(52, 1, 'KODE PERHITUNGAN');
        $sheet->setCellValueByColumnAndRow(53, 1, 'PERNYATAAN LARTAS');
        $sheet->setCellValueByColumnAndRow(54, 1, 'FLAG 4 TAHUN');
        $sheet->setCellValueByColumnAndRow(55, 1, 'SERI IZIN');
        $sheet->setCellValueByColumnAndRow(56, 1, 'TAHUN PEMBUATAN');
        $sheet->setCellValueByColumnAndRow(57, 1, 'KAPASITAS SILINDER');
        $sheet->setCellValueByColumnAndRow(58, 1, 'KODE BKC');
        $sheet->setCellValueByColumnAndRow(59, 1, 'KODE KOMODITI BKC');
        $sheet->setCellValueByColumnAndRow(60, 1, 'KODE SUB KOMODITI BKC');
        $sheet->setCellValueByColumnAndRow(61, 1, 'FLAG TIS');
        $sheet->setCellValueByColumnAndRow(62, 1, 'ISI PER KEMASAN');
        $sheet->setCellValueByColumnAndRow(63, 1, 'JUMLAH DILEKATKAN');
        $sheet->setCellValueByColumnAndRow(64, 1, 'JUMLAH PITA CUKAI');
        $sheet->setCellValueByColumnAndRow(65, 1, 'HJE CUKAI');
        $sheet->setCellValueByColumnAndRow(66, 1, 'TARIF CUKAI');
        foreach (range('A', 'Z') as $v) {
            $sheet->getColumnDimension($v)->setAutoSize(true);
        }

        $sheet = $spreadsheet->createSheet();
        $sheet->setTitle('BARANGTARIF');
        $sheet->setCellValueByColumnAndRow(1, 1, 'NOMOR AJU');
        $sheet->setCellValueByColumnAndRow(2, 1, 'SERI BARANG');
        $sheet->setCellValueByColumnAndRow(3, 1, 'KODE PUNGUTAN');
        $sheet->setCellValueByColumnAndRow(4, 1, 'KODE TARIF');
        $sheet->setCellValueByColumnAndRow(5, 1, 'TARIF');
        $sheet->setCellValueByColumnAndRow(6, 1, 'KODE FASILITAS');
        $sheet->setCellValueByColumnAndRow(7, 1, 'TARIF FASILITAS');
        $sheet->setCellValueByColumnAndRow(8, 1, 'NILAI BAYAR');
        $sheet->setCellValueByColumnAndRow(9, 1, 'NILAI FASILITAS');
        $sheet->setCellValueByColumnAndRow(10, 1, 'NILAI SUDAH DILUNASI');
        $sheet->setCellValueByColumnAndRow(11, 1, 'KODE SATUAN');
        $sheet->setCellValueByColumnAndRow(12, 1, 'JUMLAH SATUAN');
        $sheet->setCellValueByColumnAndRow(13, 1, 'FLAG BMT SEMENTARA');
        $sheet->setCellValueByColumnAndRow(14, 1, 'KODE KOMODITI CUKAI');
        $sheet->setCellValueByColumnAndRow(15, 1, 'KODE SUB KOMODITI CUKAI');
        $sheet->setCellValueByColumnAndRow(16, 1, 'FLAG TIS');
        $sheet->setCellValueByColumnAndRow(17, 1, 'FLAG PELEKATAN');
        $sheet->setCellValueByColumnAndRow(18, 1, 'KODE KEMASAN');
        $sheet->setCellValueByColumnAndRow(19, 1, 'JUMLAH KEMASAN');
        foreach (range('A', 'Z') as $v) {
            $sheet->getColumnDimension($v)->setAutoSize(true);
        }

        $sheet = $spreadsheet->createSheet();
        $sheet->setTitle('BARANGDOKUMEN');
        $sheet->setCellValueByColumnAndRow(1, 1, 'NOMOR AJU');
        $sheet->setCellValueByColumnAndRow(2, 1, 'SERI BARANG');
        $sheet->setCellValueByColumnAndRow(3, 1, 'SERI DOKUMEN');
        $sheet->setCellValueByColumnAndRow(4, 1, 'SERI IZIN');
        foreach (range('A', 'Z') as $v) {
            $sheet->getColumnDimension($v)->setAutoSize(true);
        }

        $sheet = $spreadsheet->createSheet();
        $sheet->setTitle('BARANGENTITAS');
        $sheet->setCellValueByColumnAndRow(1, 1, 'NOMOR AJU');
        $sheet->setCellValueByColumnAndRow(2, 1, 'SERI BARANG');
        $sheet->setCellValueByColumnAndRow(3, 1, 'SERI DOKUMEN');
        foreach (range('A', 'Z') as $v) {
            $sheet->getColumnDimension($v)->setAutoSize(true);
        }

        $sheet = $spreadsheet->createSheet();
        $sheet->setTitle('BARANGSPEKKHUSUS');
        $sheet->setCellValueByColumnAndRow(1, 1, 'NOMOR AJU');
        $sheet->setCellValueByColumnAndRow(2, 1, 'SERI BARANG');
        $sheet->setCellValueByColumnAndRow(3, 1, 'KODE');
        $sheet->setCellValueByColumnAndRow(4, 1, 'URAIAN');
        foreach (range('A', 'Z') as $v) {
            $sheet->getColumnDimension($v)->setAutoSize(true);
        }

        $sheet = $spreadsheet->createSheet();
        $sheet->setTitle('BARANGVD');
        $sheet->setCellValueByColumnAndRow(1, 1, 'NOMOR AJU');
        $sheet->setCellValueByColumnAndRow(2, 1, 'SERI BARANG');
        $sheet->setCellValueByColumnAndRow(3, 1, 'KODE VD');
        $sheet->setCellValueByColumnAndRow(4, 1, 'NILAI BARANG');
        $sheet->setCellValueByColumnAndRow(5, 1, 'BIAYA TAMBAHAN');
        $sheet->setCellValueByColumnAndRow(6, 1, 'BIAYA PENGURANG');
        $sheet->setCellValueByColumnAndRow(7, 1, 'JATUH TEMPO');
        foreach (range('A', 'Z') as $v) {
            $sheet->getColumnDimension($v)->setAutoSize(true);
        }

        $sheet = $spreadsheet->createSheet();
        $sheet->setTitle('BAHANBAKU');
        $sheet->setCellValueByColumnAndRow(1, 1, 'NOMOR AJU');
        $sheet->setCellValueByColumnAndRow(2, 1, 'SERI BARANG');
        $sheet->setCellValueByColumnAndRow(3, 1, 'SERI BAHAN BAKU');
        $sheet->setCellValueByColumnAndRow(4, 1, 'KODE ASAL BAHAN BAKU');
        $sheet->setCellValueByColumnAndRow(5, 1, 'HS');
        $sheet->setCellValueByColumnAndRow(6, 1, 'KODE BARANG');
        $sheet->setCellValueByColumnAndRow(7, 1, 'URAIAN');
        $sheet->setCellValueByColumnAndRow(8, 1, 'MEREK');
        $sheet->setCellValueByColumnAndRow(9, 1, 'TIPE');
        $sheet->setCellValueByColumnAndRow(10, 1, 'UKURAN');
        $sheet->setCellValueByColumnAndRow(11, 1, 'SPESIFIKASI LAIN');
        $sheet->setCellValueByColumnAndRow(12, 1, 'KODE SATUAN');
        $sheet->setCellValueByColumnAndRow(13, 1, 'JUMLAH SATUAN');
        $sheet->setCellValueByColumnAndRow(14, 1, 'KODE KEMASAN');
        $sheet->setCellValueByColumnAndRow(15, 1, 'JUMLAH KEMASAN');
        $sheet->setCellValueByColumnAndRow(16, 1, 'KODE DOKUMEN ASAL');
        $sheet->setCellValueByColumnAndRow(17, 1, 'KODE KANTOR ASAL');
        $sheet->setCellValueByColumnAndRow(18, 1, 'NOMOR DAFTAR ASAL');
        $sheet->setCellValueByColumnAndRow(19, 1, 'TANGGAL DAFTAR ASAL');
        $sheet->setCellValueByColumnAndRow(20, 1, 'NOMOR AJU ASAL');
        $sheet->setCellValueByColumnAndRow(21, 1, 'SERI BARANG ASAL');
        $sheet->setCellValueByColumnAndRow(22, 1, 'NETTO');
        $sheet->setCellValueByColumnAndRow(23, 1, 'BRUTO');
        $sheet->setCellValueByColumnAndRow(24, 1, 'VOLUME');
        $sheet->setCellValueByColumnAndRow(25, 1, 'CIF');
        $sheet->setCellValueByColumnAndRow(26, 1, 'CIF RUPIAH');
        $sheet->setCellValueByColumnAndRow(27, 1, 'NDPBM');
        $sheet->setCellValueByColumnAndRow(28, 1, 'HARGA PENYERAHAN');
        $sheet->setCellValueByColumnAndRow(29, 1, 'HARGA PEROLEHAN');
        $sheet->setCellValueByColumnAndRow(30, 1, 'NILAI JASA');
        $sheet->setCellValueByColumnAndRow(31, 1, 'SERI IZIN');
        $sheet->setCellValueByColumnAndRow(32, 1, 'KODE BKC');
        $sheet->setCellValueByColumnAndRow(33, 1, 'KODE KOMODITI BKC');
        $sheet->setCellValueByColumnAndRow(34, 1, 'KODE SUB KOMODITI BKC');
        $sheet->setCellValueByColumnAndRow(35, 1, 'FLAG TIS');
        $sheet->setCellValueByColumnAndRow(36, 1, 'ISI PER KEMASAN');
        $sheet->setCellValueByColumnAndRow(37, 1, 'JUMLAH DILEKATKAN');
        $sheet->setCellValueByColumnAndRow(38, 1, 'JUMLAH PITA CUKAI');
        $sheet->setCellValueByColumnAndRow(39, 1, 'HJE CUKAI');
        $sheet->setCellValueByColumnAndRow(40, 1, 'TARIF CUKAI');
        foreach (range('A', 'Z') as $v) {
            $sheet->getColumnDimension($v)->setAutoSize(true);
        }

        $sheet = $spreadsheet->createSheet();
        $sheet->setTitle('BAHANBAKUTARIF');
        $sheet->setCellValueByColumnAndRow(1, 1, 'NOMOR AJU');
        $sheet->setCellValueByColumnAndRow(2, 1, 'SERI BARANG');
        $sheet->setCellValueByColumnAndRow(3, 1, 'SERI BAHAN BAKU');
        $sheet->setCellValueByColumnAndRow(4, 1, 'KODE ASAL BAHAN BAKU');
        $sheet->setCellValueByColumnAndRow(5, 1, 'KODE PUNGUTAN');
        $sheet->setCellValueByColumnAndRow(6, 1, 'KODE TARIF');
        $sheet->setCellValueByColumnAndRow(7, 1, 'TARIF');
        $sheet->setCellValueByColumnAndRow(8, 1, 'KODE FASILITAS');
        $sheet->setCellValueByColumnAndRow(9, 1, 'TARIF FASILITAS');
        $sheet->setCellValueByColumnAndRow(10, 1, 'NILAI BAYAR');
        $sheet->setCellValueByColumnAndRow(11, 1, 'NILAI FASILITAS');
        $sheet->setCellValueByColumnAndRow(12, 1, 'NILAI SUDAH DILUNASI');
        $sheet->setCellValueByColumnAndRow(13, 1, 'KODE SATUAN');
        $sheet->setCellValueByColumnAndRow(14, 1, 'JUMLAH SATUAN');
        $sheet->setCellValueByColumnAndRow(15, 1, 'FLAG BMT SEMENTARA');
        $sheet->setCellValueByColumnAndRow(16, 1, 'KODE KOMODITI CUKAI');
        $sheet->setCellValueByColumnAndRow(17, 1, 'KODE SUB KOMODITI CUKAI');
        $sheet->setCellValueByColumnAndRow(18, 1, 'FLAG TIS');
        $sheet->setCellValueByColumnAndRow(19, 1, 'FLAG PELEKATAN');
        $sheet->setCellValueByColumnAndRow(20, 1, 'KODE KEMASAN');
        $sheet->setCellValueByColumnAndRow(21, 1, 'KODE SUB KOMODITI CUKAI');
        $sheet->setCellValueByColumnAndRow(22, 1, 'FLAG TIS');
        $sheet->setCellValueByColumnAndRow(23, 1, 'FLAG PELEKATAN');
        $sheet->setCellValueByColumnAndRow(24, 1, 'KODE KEMASAN');
        $sheet->setCellValueByColumnAndRow(25, 1, 'JUMLAH KEMASAN');
        foreach (range('A', 'Z') as $v) {
            $sheet->getColumnDimension($v)->setAutoSize(true);
        }

        $sheet = $spreadsheet->createSheet();
        $sheet->setTitle('BAHANBAKUDOKUMEN');
        $sheet->setCellValueByColumnAndRow(1, 1, 'NOMOR AJU');
        $sheet->setCellValueByColumnAndRow(2, 1, 'SERI BARANG');
        $sheet->setCellValueByColumnAndRow(3, 1, 'SERI BAHAN BAKU');
        $sheet->setCellValueByColumnAndRow(4, 1, 'KODE_ASAL_BAHAN_BAKU');
        $sheet->setCellValueByColumnAndRow(5, 1, 'SERI DOKUMEN');
        $sheet->setCellValueByColumnAndRow(6, 1, 'SERI IZIN');
        foreach (range('A', 'Z') as $v) {
            $sheet->getColumnDimension($v)->setAutoSize(true);
        }

        $sheet = $spreadsheet->createSheet();
        $sheet->setTitle('PUNGUTAN');
        $sheet->setCellValueByColumnAndRow(1, 1, 'NOMOR AJU');
        $sheet->setCellValueByColumnAndRow(2, 1, 'KODE FASILITAS TARIF');
        $sheet->setCellValueByColumnAndRow(3, 1, 'KODE JENIS PUNGUTAN');
        $sheet->setCellValueByColumnAndRow(4, 1, 'NILAI PUNGUTAN');
        foreach (range('A', 'Z') as $v) {
            $sheet->getColumnDimension($v)->setAutoSize(true);
        }

        $sheet = $spreadsheet->createSheet();
        $sheet->setTitle('JAMINAN');
        $sheet->setCellValueByColumnAndRow(1, 1, 'NOMOR AJU');
        $sheet->setCellValueByColumnAndRow(2, 1, 'KODE KANTOR');
        $sheet->setCellValueByColumnAndRow(3, 1, 'KODE JAMINAN');
        $sheet->setCellValueByColumnAndRow(4, 1, 'NOMOR JAMINAN');
        $sheet->setCellValueByColumnAndRow(5, 1, 'TANGGAL JAMINAN');
        $sheet->setCellValueByColumnAndRow(6, 1, 'NILAI JAMINAN');
        $sheet->setCellValueByColumnAndRow(7, 1, 'PENJAMIN');
        $sheet->setCellValueByColumnAndRow(8, 1, 'TANGGAL JATUH TEMPO');
        $sheet->setCellValueByColumnAndRow(9, 1, 'NOMOR BPJ');
        $sheet->setCellValueByColumnAndRow(10, 1, 'TANGGAL BPJ');
        foreach (range('A', 'Z') as $v) {
            $sheet->getColumnDimension($v)->setAutoSize(true);
        }

        $sheet = $spreadsheet->createSheet();
        $sheet->setTitle('BANKDEVISA');
        $sheet->setCellValueByColumnAndRow(1, 1, 'NOMOR AJU');
        $sheet->setCellValueByColumnAndRow(2, 1, 'SERI');
        $sheet->setCellValueByColumnAndRow(3, 1, 'KODE');
        $sheet->setCellValueByColumnAndRow(4, 1, 'NAMA');
        foreach (range('A', 'Z') as $v) {
            $sheet->getColumnDimension($v)->setAutoSize(true);
        }

        $sheet = $spreadsheet->createSheet();
        $sheet->setTitle('VERSI');
        $sheet->setCellValueByColumnAndRow(1, 1, 'VERSI');
        $sheet->setCellValueByColumnAndRow(1, 2, '1');

        $stringjudul = "nama file";
        $filename = $stringjudul; //save our workbook as this file name
        header('Content-Type: application/vnd.ms-excel');
        header('Content-Disposition: attachment;filename="' . $filename . '.xlsx"');
        header('Cache-Control: max-age=0');
        $writer = new Xlsx($spreadsheet);
        $writer->save('php://output');
    }

    public function postingCEISA40BC27()
    {
        header('Content-Type: application/json');
        $doc = $this->input->post('doc');
        $RSHeader = $this->DELV_mod->selectPostedDocument(['DLV_ID'], ['DLV_ID' => $doc]);
        $data = [];
        if (empty($RSHeader)) {
            $data[] = ['message' => 'Please posting to local first'];
        } else {
            $cz_h_CIF_FG = 0;
            $cz_h_HARGA_PENYERAHAN_FG = 0;
            $t_HARGA_PENYERAHAN = 0;
            $cz_h_NETTO = 0;
            $tpb_barang = [];
            $SERI_BARANG = 1;
            $aspBOX = 0;
            $cz_JUMLAH_KEMASAN = $this->DELV_mod->check_Primary(['DLV_ID' => $doc]);
            $rsitem_p_price = $this->setPriceRS(base64_encode($doc));
            $rsplotrm_per_fgprice = $this->perprice($doc, $rsitem_p_price);
            foreach ($rsitem_p_price as &$r) {
                $cz_h_CIF_FG += $r['CIF'];
                $cz_h_NETTO += $r['NWG'];
                $cz_h_HARGA_PENYERAHAN_FG += $t_HARGA_PENYERAHAN;
                if (strpos(strtoupper($r['SSO2_MDLCD']), 'ASP') !== false) {
                    $aspBOX += $r['SISOQTY'];
                }
                $tpb_barang[] = [
                    'KODE_BARANG' => $r['SSO2_MDLCD']
                    , 'POS_TARIF' => $r['MITM_HSCD']
                    , 'URAIAN' => $r['MITM_ITMD1']
                    , 'JUMLAH_SATUAN' => $r['SISOQTY']
                    , 'KODE_SATUAN' => $r['MITM_STKUOM'] == 'PCS' ? 'PCE' : $r['MITM_STKUOM']
                    , 'NETTO' => $r['NWG']
                    , 'CIF' => round($r['CIF'], 2)
                    , 'HARGA_PENYERAHAN' => $t_HARGA_PENYERAHAN
                    , 'SERI_BARANG' => $SERI_BARANG
                    , 'KODE_STATUS' => '02',
                ];
                $SERI_BARANG++;
            }
            unset($r);
            if ($aspBOX) {
                $cz_JUMLAH_KEMASAN = $aspBOX;
            }

            # cari HSCODE
            $rsRMOnly = $this->DELV_mod->select_pertxid_rmOnly($doc);
            $rsSubOnly = $this->DELV_mod->select_pertxid_subOnly($doc);
            $rsnull = $this->DELV_mod->select_dlv_ser_rm_null_v1($doc);
            $rsMerge = array_merge($rsRMOnly, $rsSubOnly);
            foreach ($rsnull as $r) {
                $rscomb_d = $this->SERC_mod->select_cols_where_id(['SERC_COMID'], $r['DLV_SER']);
                $serlist = [];
                if (count($rscomb_d)) {
                    foreach ($rscomb_d as $n) {
                        $serlist[] = $n['SERC_COMID'];
                    }
                    if (count($serlist) > 0) {
                        $rscom = $this->DELV_mod->select_pertxid_byser($serlist);
                        $rs = array_merge($rs, $rscom);
                    }
                } else {
                    $rscomb_d = $this->SERC_mod->select_cols_where_id(['SERC_COMID'], $r['SER_REFNO']);
                    foreach ($rscomb_d as $n) {
                        $serlist[] = $n['SERC_COMID'];
                    }
                    if (count($serlist) > 0) {
                        $rscom = $this->DELV_mod->select_pertxid_byser($serlist);
                        $rs = array_merge($rs, $rscom);
                    }
                }
            }
            $rsallitem_cd = [];
            $rsallitem_hscd = [];
            $rsallitem_qty = [];
            $rsallitem_qtyplot = [];
            $rsallitem_ppn = [];
            $rsallitem_pph = [];
            $rsallitem_bm = [];
            foreach ($rsMerge as $r) {
                $itemtosend = $r['ITMGR'] == '' ? trim($r['SERD2_ITMCD']) : $r['ITMGR'];
                $i = array_search($itemtosend, $rsallitem_cd);
                if ($i !== false) {
                    $rsallitem_qty[$i] += $r['DLVQT'];
                } else {
                    $rsallitem_cd[] = $itemtosend;
                    $rsallitem_hscd[] = '';
                    $rsallitem_qty[] = $r['DLVQT'];
                    $rsallitem_qtyplot[] = 0;
                    $rsallitem_ppn[] = '';
                    $rsallitem_pph[] = '';
                    $rsallitem_bm[] = '';
                }
            }
            $count_rsallitem = count($rsallitem_cd);

            # susun array bahan baku
            $rsbc = $this->DELV_mod->selectBookeEXCBByTXID($doc);
            foreach ($rsplotrm_per_fgprice as &$r) {
                $r['PLOTRQTY'] = 0;
                foreach ($rsbc as &$v) {
                    if ($r['RITEMCDGR'] === $v->BC_ITEM || $r['RITEMCD'] === $v->BC_ITEM && $v->BC_QTY > 0) {
                        $balreq = $r['RQTY'] - $r['PLOTRQTY'];
                        $theqty = 0;

                        if ($balreq == 0) {
                            break;
                        }

                        if ($balreq > $v->BC_QTY) {
                            $theqty = $v->BC_QTY;
                            $r['PLOTRQTY'] += $v->BC_QTY;
                            $v->BC_QTY = 0;
                        } else {
                            $theqty = $balreq;
                            $r['PLOTRQTY'] += $balreq;
                            $v->BC_QTY -= $balreq;
                        }
                        $thehscode = '';
                        $theppn = '';
                        $thepph = '';
                        $thebm = '';

                        if (!empty($v->RCV_HSCD)) {
                            $thehscode = $v->RCV_HSCD;
                            $theppn = $v->RCV_PPN;
                            $thepph = $v->RCV_PPH;
                            $thebm = substr($v->RCV_BM, 0, 1) == '.' ? ('0' . $v->RCV_BM) : ($v->RCV_BM);
                        } else {
                            for ($h = 0; $h < $count_rsallitem; $h++) {
                                if ($v->BC_ITEM == $rsallitem_cd[$h]) {
                                    $thehscode = $rsallitem_hscd[$h];
                                    $theppn = $rsallitem_ppn[$h];
                                    $thepph = $rsallitem_pph[$h];
                                    $thebm = $rsallitem_bm[$h];
                                    break;
                                }
                            }
                        }

                        if ($v->RCV_KPPBC != '-') {
                            $tpb_bahan_baku[] = [
                                'KODE_JENIS_DOK_ASAL' => $v->BC_TYPE
                                , 'NOMOR_DAFTAR_DOK_ASAL' => $v->BC_NUM
                                , 'TANGGAL_DAFTAR_DOK_ASAL' => $v->BC_DATE
                                , 'KODE_KANTOR' => $v->RCV_KPPBC
                                , 'NOMOR_AJU_DOK_ASAL' => strlen($v->BC_AJU) == 6 ? substr('000000000000000000000000', 0, 26) : $v->BC_AJU
                                , 'SERI_BARANG_DOK_ASAL' => empty($v->RCV_ZNOURUT) ? 0 : $v->RCV_ZNOURUT
                                , 'SPESIFIKASI_LAIN' => null
                                , 'CIF' => substr($v->RCV_PRPRC, 0, 1) == '.' ? ('0' . $v->RCV_PRPRC * $theqty) : ($v->RCV_PRPRC * $theqty)
                                , 'HARGA_PENYERAHAN' => 0
                                , 'KODE_BARANG' => $v->BC_ITEM
                                , 'KODE_STATUS' => "03"
                                , 'POS_TARIF' => $thehscode
                                , 'URAIAN' => $v->MITM_ITMD1
                                , 'TIPE' => $v->MITM_SPTNO
                                , 'JUMLAH_SATUAN' => $theqty
                                , 'JENIS_SATUAN' => ($v->MITM_STKUOM == 'PCS') ? 'PCE' : $v->MITM_STKUOM
                                , 'KODE_ASAL_BAHAN_BAKU' => ($v->BC_TYPE == '27' || $v->BC_TYPE == '23') ? '0' : '1'
                                , 'RASSYCODE' => $r['RASSYCODE']
                                , 'RPRICEGROUP' => $r['RPRICEGROUP']
                                , 'RBM' => $thebm
                                , 'PPN' => 11
                                , 'PPH' => $thepph,
                            ];
                        } else {
                            $tpb_bahan_baku[] = [
                                'KODE_JENIS_DOK_ASAL' => $v->BC_TYPE
                                , 'NOMOR_DAFTAR_DOK_ASAL' => $v->BC_NUM
                                , 'TANGGAL_DAFTAR_DOK_ASAL' => $v->BC_DATE
                                , 'KODE_KANTOR' => null
                                , 'NOMOR_AJU_DOK_ASAL' => strlen($v->BC_AJU) == 6 ? substr('000000000000000000000000', 0, 26) : $v->BC_AJU
                                , 'SERI_BARANG_DOK_ASAL' => empty($v->RCV_ZNOURUT) ? 0 : $v->RCV_ZNOURUT
                                , 'SPESIFIKASI_LAIN' => null
                                , 'CIF' => 0
                                , 'HARGA_PENYERAHAN' => 0
                                , 'KODE_BARANG' => $v->BC_ITEM
                                , 'KODE_STATUS' => "02"
                                , 'POS_TARIF' => $thehscode
                                , 'URAIAN' => $v->MITM_ITMD1
                                , 'TIPE' => $v->MITM_SPTNO
                                , 'JUMLAH_SATUAN' => $theqty
                                , 'JENIS_SATUAN' => 'PCE'
                                , 'KODE_ASAL_BAHAN_BAKU' => 0
                                , 'RASSYCODE' => $r['RASSYCODE']
                                , 'RPRICEGROUP' => $r['RPRICEGROUP']
                                , 'RBM' => 0
                                , 'PPN' => $theppn
                                , 'PPH' => $thepph,
                            ];
                        }
                        #end
                    }
                }
                unset($v);
            }
            unset($r);

            # atur seri bahan baku
            foreach ($rsplotrm_per_fgprice as $r) {
                $nomor = 1;
                foreach ($tpb_bahan_baku as &$n) {
                    if (
                        $r['RASSYCODE'] == $n['RASSYCODE']
                        && $r['RPRICEGROUP'] == $n['RPRICEGROUP']
                    ) {
                        $n['SERI_BAHAN_BAKU'] = $nomor;
                        $nomor++;
                    }
                }
                unset($n);
            }

            # atur bahan baku tarif
            foreach ($tpb_bahan_baku as &$r) {
                $tpb_bahan_baku_tarif = [
                    [
                        'JENIS_TARIF' => 'BM'
                        , 'KODE_TARIF' => 1
                        , 'NILAI_BAYAR' => 0
                        , 'NILAI_FASILITAS' => 0
                        , 'KODE_FASILITAS' => 2
                        , 'TARIF_FASILITAS' => 100
                        , 'TARIF' => $r['RBM']
                        , 'SERI_BAHAN_BAKU' => $r['SERI_BAHAN_BAKU'], 'RASSYCODE' => $r['RASSYCODE'], 'RPRICEGROUP' => $r['RPRICEGROUP'], 'RITEMCD' => $r['KODE_BARANG'],
                    ],
                    [
                        'JENIS_TARIF' => 'PPN'
                        , 'KODE_TARIF' => 1
                        , 'NILAI_BAYAR' => 0
                        , 'NILAI_FASILITAS' => 0
                        , 'KODE_FASILITAS' => 2
                        , 'TARIF_FASILITAS' => 100
                        , 'TARIF' => $r['PPN']
                        , 'SERI_BAHAN_BAKU' => $r['SERI_BAHAN_BAKU'], 'RASSYCODE' => $r['RASSYCODE'], 'RPRICEGROUP' => $r['RPRICEGROUP'], 'RITEMCD' => $r['KODE_BARANG'],
                    ],
                    [
                        'JENIS_TARIF' => 'PPH'
                        , 'KODE_TARIF' => 1
                        , 'NILAI_BAYAR' => 0
                        , 'NILAI_FASILITAS' => 0
                        , 'KODE_FASILITAS' => 2
                        , 'TARIF_FASILITAS' => 100
                        , 'TARIF' => $r['PPH']
                        , 'SERI_BAHAN_BAKU' => $r['SERI_BAHAN_BAKU'], 'RASSYCODE' => $r['RASSYCODE'], 'RPRICEGROUP' => $r['RPRICEGROUP'], 'RITEMCD' => $r['KODE_BARANG'],
                    ],
                ];
                $r['tarif'] = $tpb_bahan_baku_tarif;
            }
            unset($r);

            foreach ($tpb_barang as $n) {
                foreach ($tpb_bahan_baku as &$b) {
                    if ($n['KODE_BARANG'] == $b['RASSYCODE'] && $n['CIF'] == $b['RPRICEGROUP']) {
                        $b['SERI_BARANG'] = $n['SERI_BARANG'];
                    }
                }
                unset($b);
            }

            $data[] = ['data' => [
                'txid' => $doc,
                'cif' => $cz_h_CIF_FG,
                'hargaPenyerahan' => $cz_h_HARGA_PENYERAHAN_FG,
                'netto' => $cz_h_NETTO,
                'jumlahKemasan' => $cz_JUMLAH_KEMASAN,
                'ListBarangByPrice' => $tpb_barang,
                'ListBahanBakuList' => $tpb_bahan_baku,
            ]];
        }
        die(json_encode($data));
    }

    public function postingCEISA40BC27Return()
    {
        header('Content-Type: application/json');
        $doc = $this->input->post('doc');
        $RSHeader = $this->DELV_mod->selectPostedDocument(['DLV_ID'], ['DLV_ID' => $doc]);
        $data = [];
        if (empty($RSHeader)) {
            $data[] = ['message' => 'Please posting to local first'];
        } else {
            $rswhSI = $this->SI_mod->select_wh_by_txid($doc);
            if ($rswhSI === '??') {
                $myar[] = ["cd" => "0", "msg" => "SI WH is not recognized"];
                die(json_encode(['status' => $myar]));
            }
            $rsitem_p_price = [];
            $data[] = ['data' => [
                'txid' => $doc,
                'cif' => $cz_h_CIF_FG,
                'hargaPenyerahan' => $cz_h_HARGA_PENYERAHAN_FG,
                'netto' => $cz_h_NETTO,
                'jumlahKemasan' => $cz_JUMLAH_KEMASAN,
                'ListBarangByPrice' => $tpb_barang,
                'ListBahanBakuList' => $tpb_bahan_baku,
            ]];
        }
        die(json_encode($data));
    }

    public function gotoque($psj)
    {
        $mdo = base64_encode($psj);
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, 'http://192.168.0.29:8080/api_inventory/api/stock/onDelivery/' . $mdo);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
        curl_setopt($ch, CURLOPT_CONNECTTIMEOUT, 5);
        $data = curl_exec($ch);
        curl_close($ch);
        return $data;
    }

    public function inventory___getstockbc($pbc_type, $ptujuan, $psj, $prm, $pqty, $plot, $pbcdate)
    {
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, 'http://192.168.0.29:8080/api_inventory/api/inventory/getStockBC');
        curl_setopt($ch, CURLOPT_POST, true);
        curl_setopt($ch, CURLOPT_POSTFIELDS, "bc=$pbc_type&tujuan=$ptujuan&doc=$psj&date_out=$pbcdate&item_num=$prm&qty=$pqty&lot=$plot");
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
        curl_setopt($ch, CURLOPT_CONNECTTIMEOUT, 300);
        curl_setopt($ch, CURLOPT_TIMEOUT, 660);
        $data = curl_exec($ch);
        curl_close($ch);
        return $data;
    }
    public function inventory_getstockbc_v2($pbc_type, $ptujuan, $psj, $prm, $pqty, $plot, $pbcdate, $pkontrak = "")
    {
        $fields = [
            'bc' => $pbc_type,
            'tujuan' => $ptujuan,
            'date_out' => $pbcdate,
            'doc' => $psj,
            'kontrak' => $pkontrak,
            'item_num' => $prm,
            'qty' => $pqty,
        ];
        $fields_string = http_build_query($fields);
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, 'http://192.168.0.29:8080/api_inventory/api/inventory/getStockBCArray');
        curl_setopt($ch, CURLOPT_POST, true);
        curl_setopt($ch, CURLOPT_POSTFIELDS, $fields_string);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
        curl_setopt($ch, CURLOPT_CONNECTTIMEOUT, 0);
        curl_setopt($ch, CURLOPT_TIMEOUT, 3600);
        $data = curl_exec($ch);
        curl_close($ch);
        return $data;
    }
    public function inventory_getstockbc_v2_witDO($pbc_type, $ptujuan, $psj, $prm, $pqty, $pbcdate, $pdoNum)
    {
        $fields = [
            'isNotSaved' => true,
            'bc' => $pbc_type,
            'tujuan' => $ptujuan,
            'date_out' => $pbcdate,
            'doc' => $psj,
            'item_num' => $prm,
            'qty' => $pqty //,
            , 'do' => $pdoNum, //,
        ];
        $fields_string = http_build_query($fields);
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, 'http://192.168.0.29:8080/api_inventory/api/inventory/getStockBCArray');
        curl_setopt($ch, CURLOPT_POST, true);
        curl_setopt($ch, CURLOPT_POSTFIELDS, $fields_string);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
        curl_setopt($ch, CURLOPT_CONNECTTIMEOUT, 0);
        curl_setopt($ch, CURLOPT_TIMEOUT, 3600);
        $data = curl_exec($ch);
        curl_close($ch);
        return $data;
    }
    public function inventory_cancelDO($pdo)
    {
        $mdo = base64_encode($pdo);
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, 'http://192.168.0.29:8080/api_inventory/api/inventory/cancelDO/' . $mdo);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
        curl_setopt($ch, CURLOPT_CONNECTTIMEOUT, 300); //5sdfdfs
        curl_setopt($ch, CURLOPT_TIMEOUT, 660);
        $data = curl_exec($ch);
        curl_close($ch);
        return $data;
    }
    public function inventory_ceisa40($data)
    {
        $fields = [
            'txid' => $data['txid'],
            'cif' => $data['cif'],
            'hargaPenyerahan' => $data['hargaPenyerahan'],
            'netto' => $data['netto'],
            'jumlahKemasan' => $data['jumlahKemasan'],
            'ListBarangByPrice' => $data['RSBarang'],
            'ListBahanBakuList' => $data['RSBahanBaku'],
        ];
        $fields_string = http_build_query($fields);
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, 'http://192.168.0.29:8080/api_inventory/public/api/ceisafour/sendPosting/' . $data['BCTYPE']);
        curl_setopt($ch, CURLOPT_POST, true);
        curl_setopt($ch, CURLOPT_POSTFIELDS, $fields_string);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
        curl_setopt($ch, CURLOPT_CONNECTTIMEOUT, 0);
        curl_setopt($ch, CURLOPT_TIMEOUT, 3600);
        $data = curl_exec($ch);
        curl_close($ch);
        return $data;
    }
}
