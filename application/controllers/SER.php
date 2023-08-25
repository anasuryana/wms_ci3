<?php
defined('BASEPATH') or exit('No direct script access allowed');

use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;

class SER extends CI_Controller
{
    private $AROMAWI = ['I', 'II', 'III', 'IV', 'V', 'VI', 'VII', 'VIII', 'IX', 'X', 'XI', 'XII'];
    private $WOExceptionList = [
        '21-YE13-216168400',
        '21-YE15-216168400',
        '21-YH17-216168700',
        '21-YI17-216168800',
        '21-YJ15-217902100',
        '21-YE16-216168400',
        '21-YE17-216168400',
        '21-YE19-216168400',
    ];
    public function __construct()
    {
        parent::__construct();
        $this->load->helper('url');
        $this->load->library('session');
        $this->load->library('RMCalculator');
        $this->load->library('Code39e128');
        $this->load->model('MSTITM_mod');
        $this->load->model('MADE_mod');
        $this->load->model('SER_mod');
        $this->load->model('SPL_mod');
        $this->load->model('ITH_mod');
        $this->load->model('RCV_mod');
        $this->load->model('RESIM_mod');
        $this->load->model('TXROUTE_mod');
        $this->load->model('MSTLOC_mod');
        $this->load->model('RLS_mod');
        $this->load->model('SISCN_mod');
        $this->load->model('LOGSER_mod');
        $this->load->model('SERC_mod');
        $this->load->model('XBGROUP_mod');
        $this->load->model(['PND_mod', 'PNDSCN_mod']);
        $this->load->model('SERD_mod');
        $this->load->model('PWOP_mod');
        $this->load->model('SERRC_mod');
        $this->load->model('RETFG_mod');
        $this->load->model('MSPP_mod');
        $this->load->model('C3LC_mod');
        $this->load->model('XWO_mod');
        $this->load->model('DLVPRC_mod');
        $this->load->model('DELV_mod');
        $this->load->model('ZRPSTOCK_mod');
        $this->load->model('DLVCK_mod');
        $this->load->model('refceisa/TPB_HEADER_imod');
        date_default_timezone_set('Asia/Jakarta');
    }
    public function index()
    {
        echo "sorry";
    }

    public function create()
    {
        $this->load->view('wms/vser_fg');
    }
    public function createstatus()
    {
        $this->load->view('wms/vser_fg_status');
    }
    public function createrm()
    {
        $data['lmade'] = $this->MADE_mod->selectAll();
        $this->load->view('wms/vser_rm', $data);
    }
    public function vsplitlabel_1()
    {
        $this->load->view('wms/vsplitlabel_1V1');
    }
    public function vdevsplit()
    {
        $this->load->view('wms/vsplitlabel_1V1');
    }
    public function vsplitlabel_2()
    {
        $this->load->view('wms/vsplitlabel_2');
    }
    public function vrelable()
    {
        $this->load->view('wms/vrelable');
    }
    public function vrelableexport()
    {
        $this->load->view('wms/vrelable_export');
    }
    public function vcombinelabel_1()
    {
        $this->load->view('wms/vcombinelabel1');
    }
    public function vcombinelabel_2()
    {
        $this->load->view('wms/vcombinelabel2');
    }
    public function vqcunconform()
    {
        $data['lgroup'] = $this->XBGROUP_mod->selectall();
        $this->load->view('wms_report/vqcunconform', $data);
    }
    public function vconvertlabel()
    {
        $this->load->view('wms/vconvertlabel');
    }
    public function vdeletelabel()
    {
        $this->load->view('wms/vdeletelabel');
    }

    public function form_change_rank()
    {
        $this->load->view('wms/vchange_rank');
    }

    public function convert_rank()
    {
        header('Content-Type: application/json');
        $oldreff = $this->input->post('oldreff');
        $oldrank = $this->input->post('oldrank');
        $newrank = $this->input->post('newrank');
        if ($this->SER_mod->updatebyId(['SER_GRADE' => $newrank], ['SER_ID' => $oldreff])) {
            $myar[] = ['cd' => 1, 'msg' => 'Saved successfully'];
            $this->LOGSER_mod->insert_([
                'LOGSER_KEYS' => $oldreff, 'LOGSER_USRID' => $this->session->userdata('nama'), 'LOGSER_DT' => date('Y-m-d H:i:s'), 'LOGSER_REMARK' => 'Change rank ' . $oldrank . ' to ' . $newrank,
            ]);
        } else {
            $myar[] = ['cd' => 0, 'msg' => 'Could not found the data'];
        }
        die(json_encode(['status' => $myar]));
    }

    public function get_fgser_rtn()
    {
        header('Content-Type: application/json');
        $cser = $this->input->get('inid');
        $cdocst = $this->input->get('indocst');
        $rs = $this->SER_mod->select_SER_byVAR(['SER_ID' => $cser]);
        $rsbalance = $this->SERRC_mod->select_xbalance_v1($cser, $cdocst);
        $rslocation = $this->ITH_mod->selectstock_ser($cser);
        foreach ($rsbalance as &$r) {
            foreach ($rs as $s) {
                $r['BALQTY'] = ($s['SER_QTY'] * 1) - $r['BALQTY'];
            }
        }
        unset($r);
        $isExist = false;
        foreach ($rslocation as $r) {
            if ($r['ITH_WH'] === 'AFQART' || $r['ITH_WH'] === 'AFQART2') {
                $isExist = true;
            }
        }
        if (empty($rs)) {
            $myar[] = ['cd' => 0, 'msg' => 'ID is not found'];
        } else {
            $myar[] = $isExist ? ['cd' => 1, 'msg' => 'Go ahead'] : ['cd' => 0, 'msg' => 'the ID is already used fully'];
        }
        die('{"status":' . json_encode($myar) . ', "data":' . json_encode($rs) . ',"data_bal": ' . json_encode($rsbalance) . '}');
    }

    public function get_rm_eu()
    {
        header('Content-Type: application/json');
        $citemcd = $this->input->post('initem');
        $rs = $this->PWOP_mod->select_exact_byVAR(['PWOP_MDLCD' => $citemcd]);
        $rs_s = $this->MSPP_mod->select_byvar(['MSPP_MDLCD' => $citemcd]);
        $rs_j = [];
        foreach ($rs as $r) {
            $rs_j[] = [
                'id' => $r['MITM_ITMCD'], 'description' => $r['MITM_ITMD1'],
            ];
            foreach ($rs_s as $s) {
                if (trim($s['MSPP_BOMPN']) == $r['MITM_ITMCD']) {
                    $rs_j[] = [
                        'id' => $s['MSPP_SUBPN'], 'description' => $s['XSUBNAME'],
                    ];
                }
            }
        }
        exit(json_encode($rs_j));
    }

    public function searchdok_rcqc()
    {
        header('Content-Type: application/json');
        $doc = $this->input->get('indoc');
        $doctype = $this->input->get('indoctype');
        $rs = [];
        switch ($doctype) {
            case 'ST':
                $rs = $this->SERRC_mod->select_serahterima_h_byst($doc);
                break;
            case 'DO':
                $rs = $this->SERRC_mod->select_serahterima_h_bydo($doc);
                break;
            case 'SL':
                $rs = $this->SERRC_mod->select_serahterima_h_bysl($doc);
                break;
        }
        $rsret = [];
        foreach ($rs as &$r) {
            $rsra = $this->SERRC_mod->select_ra_byst($r['SERRC_DOCST']);
            $rano = '';
            foreach ($rsra as $ra) {
                $rano .= $ra['SER_DOC'] . ",";
            }
            $r['RADOC'] = substr($rano, 0, strlen($rano) - 1);
            $rsret[] = $r;
        }
        unset($r);
        die('{"data":' . json_encode($rsret) . '}');
    }

    public function set_rc_bom()
    {
        header('Content-Type: application/json');
        $current_time = date('Y-m-d H:i:s');
        $jm_opt = $this->input->post('jmopt');
        $qalabel = $this->input->post('qalabel');
        $jmcode = $this->input->post('jmcode');
        $bompn = $this->input->post('bompn');
        $loc = $this->input->post('loc');
        $line = $this->input->post('line');
        $dok = $this->input->post('indok');
        $cdate = $this->input->post('indate');
        $cqty = $this->input->post('qty');
        $clotno = $this->input->post('lotno');

        $cx_reffno = $this->input->post('inext_reffno');
        $cx_id = $this->input->post('inext_id');
        $cx_itemcd = $this->input->post('inext_itemcode');
        $cx_qty = $this->input->post('inext_qty');
        $cx_rawtxt = $this->input->post('inext_rawtxt');

        $ttlx_data = 0;
        if (is_array($cx_reffno)) {
            $ttlx_data = count($cx_reffno);
        }

        $ttldata = count($bompn);
        $ttlsaved = 0;
        $ttlupdated = 0;
        $myar = [];

        if ($this->SERRC_mod->check_Primary(['SERRC_DOCST' => $dok]) == 0) {
            $adate = explode("-", $cdate);
            $mmonth = intval($adate[1]);
            $myear = $adate[0];
            $lastno = $this->SERRC_mod->select_lastnodo_patterned($mmonth, $myear) + 1;
            $monthroma = $this->AROMAWI[$mmonth - 1];
            $dok = substr('000' . $lastno, -3) . "/" . $monthroma . "/" . $myear . " RTN";
        }

        for ($i = 0; $i < $ttldata; $i++) {
            if ($line[$i] == '') {
                $lastline = $this->SERRC_mod->lastserialid($qalabel[$i]);
                $lastline++;
                $data = [
                    'SERRC_SER' => strtoupper($qalabel[$i]), 'SERRC_JM' => $jmcode[$i], 'SERRC_BOMPN' => $bompn[$i], 'SERRC_BOMPNQTY' => is_numeric($cqty[$i]) ? $cqty[$i] : null, 'SERRC_LOTNO' => $clotno[$i], 'SERRC_LINE' => $lastline, 'SERRC_LOC' => $loc[$i], 'SERRC_DOCST' => $dok, 'SERRC_DOCSTDT' => $cdate, 'SERRC_JMOPT' => $jm_opt, 'SERRC_USRID' => $this->session->userdata('nama'), 'SERRC_LUPDT' => $current_time,
                ];
                $ttlsaved += $this->SERRC_mod->insert($data);
            } else {
                $data = [
                    'SERRC_JM' => $jmcode[$i], 'SERRC_BOMPN' => $bompn[$i], 'SERRC_BOMPNQTY' => is_numeric($cqty[$i]) ? $cqty[$i] : null, 'SERRC_LOC' => $loc[$i], 'SERRC_USRID' => $this->session->userdata('nama'), 'SERRC_LUPDT' => $current_time,
                ];
                $ttlupdated += $this->SERRC_mod->update_where($data, ['SERRC_SER' => $qalabel[$i], 'SERRC_LINE' => $line[$i]]);
            }
        }

        for ($i = 0; $i < $ttlx_data; $i++) {
            #check is the reff no is already registered
            // if($this->SER_mod->check_Primary(['SER_ID' => $cx_reffno[$i]])==0 ){
            $this->SERRC_mod->update_where(
                [
                    'SERRC_NASSYCD' => $cx_itemcd[$i], 'SERRC_SERX' => $cx_reffno[$i], 'SERRC_SERXRAWTXT' => $cx_rawtxt[$i], 'SERRC_SERXQTY' => $cx_qty[$i],
                ],
                [
                    'SERRC_SER' => $cx_id[$i], 'SERRC_DOCST' => $dok,
                ]
            );
            // }
        }

        $myar[] = ['cd' => 1, 'msg' => "Saved", "reff" => $dok];
        die('{"status":' . json_encode($myar) . '}');
    }

    public function getlotno_rc()
    {
        header('Content-Type: application/json');
        $cdoc = $this->input->get('indoc');
        $cjm = $this->input->get('injm');
        $rs = $this->SERRC_mod->selectjm_where(['SERRC_JM' => $cjm, 'SERRC_DOCST' => $cdoc, 'SERRC_LOTNO IS NOT NULL' => null]);
        die('{"data":' . json_encode($rs) . '}');
    }

    public function print_st_rcqc()
    {
        $pser = '';
        if (isset($_COOKIE["PRINTDOC_DOCNO"])) {
            $pser = $_COOKIE["PRINTDOC_DOCNO"];
        } else {
            exit('nothing to be printed');
        }
        $rs = $this->SERRC_mod->select_serahterima_d($pser);
        $ddate = '';
        foreach ($rs as $r) {
            $ddate = date_create($r['SERRC_DOCSTDT']);
            $ddate = date_format($ddate, 'd-M-Y');
            break;
        }
        $pdf = new PDF_Code39e128('L', 'mm', 'A4');
        $pdf->AddPage();
        $hgt_p = $pdf->GetPageHeight();
        $wid_p = $pdf->GetPageWidth();
        $pdf->SetAutoPageBreak(true, 1);
        $pdf->SetMargins(0, 0);
        $pdf->SetFont('Arial', 'B', 13);
        $TITLE = "TRANSFER FINISH GOODS RETURN";
        $TITLE2 = "QA/RC -> PPIC";
        $widthtext = $pdf->GetStringWidth($TITLE);
        $theX = ($wid_p / 2) - ($widthtext / 2);
        $pdf->SetXY($theX, 10);
        $pdf->Cell(10, 5, $TITLE, 0, 0, 'L');
        $widthtext = $pdf->GetStringWidth($TITLE2);
        $theX = ($wid_p / 2) - ($widthtext / 2);
        $pdf->SetXY($theX, 15);
        $pdf->Cell(10, 5, $TITLE2, 0, 0, 'L');

        $pdf->SetFont('Arial', 'B', 10);
        $pdf->SetXY(6, 20);
        $pdf->Cell(10, 5, "NO     : " . $pser, 0, 0, 'L');
        $pdf->SetXY(6, 25);
        $pdf->Cell(10, 5, "DATE : " . $ddate, 0, 0, 'L');
        $pdf->setFillColor(240, 240, 240);
        $pdf->SetFont('Arial', 'B', 9);
        $pdf->SetXY(6, 30);
        $pdf->Cell(10, 10, 'No.', 1, 0, 'C', 1);
        $pdf->Cell(30, 10, '', 1, 0, 'L', 1);
        $pdf->Cell(25, 10, '', 1, 0, 'L', 1);
        $pdf->Cell(30, 10, 'ASSY CODE', 1, 0, 'C', 1);
        $pdf->Cell(20, 10, 'JM Label', 1, 0, 'C', 1);
        $pdf->Cell(25, 10, 'Model', 1, 0, 'C', 1);
        $pdf->Cell(10, 10, '', 1, 0, 'C', 1);
        $pdf->Cell(13, 10, 'CUST', 1, 0, 'C', 1);
        $pdf->SetXY(16, 30);
        $pdf->Cell(30, 5, 'No RA', 0, 0, 'C');
        $pdf->Cell(25, 5, 'DATE', 0, 0, 'C');
        $pdf->SetXY(16, 35);
        $pdf->Cell(30, 5, 'SURAT JALAN', 0, 0, 'C');
        $pdf->Cell(25, 5, 'SURAT JALAN', 0, 0, 'C');

        $pdf->SetXY(146, 30);
        $pdf->Cell(10, 10, 'QTY', 0, 0, 'C');

        $pdf->SetXY(169, 30);
        $pdf->Cell(70, 5, 'CHANGE PART', 1, 0, 'C', 1);
        $pdf->SetXY(169, 35);
        $pdf->Cell(25, 5, 'PART CODE', 1, 0, 'C', 1);
        $pdf->Cell(25, 5, 'PART NAME', 1, 0, 'C', 1);
        $pdf->Cell(10, 5, 'QTY', 1, 0, 'C', 1);
        $pdf->Cell(10, 5, 'LOC.', 1, 0, 'C', 1);

        $pdf->SetXY(239, 30);
        $pdf->Cell(25, 10, '', 1, 0, 'C', 1);
        $pdf->Cell(25, 10, 'REMARK', 1, 0, 'C', 1);
        $pdf->SetXY(239, 30);
        $pdf->Cell(25, 5, 'CHANGE', 0, 0, 'C');
        $pdf->SetXY(239, 35);
        $pdf->Cell(25, 5, 'ASSY CODE', 0, 0, 'C');

        $cury = 40;
        $no = 0;
        $h_content = 5;
        $pdf->SetFont('Arial', '', 9);
        $jmbefore = '-';
        $nodis = '-';
        $dokra_dis = '-';
        $dokradt_dis = '-';
        $assycd_dis = '-';
        $jmlabel_dis = '-';
        $model_dis = '-';
        $assyqty_dis = '-';
        $plant_dis = '-';
        $rmcd_dis = '-';
        $rmnm_dis = '-';
        $rmqty_dis = '-';
        $rmrk_dis = '-';
        $newassybefore = '-';
        $newassy_dis = '-';
        foreach ($rs as $r) {
            if ($cury >= ($hgt_p - 10)) {
                $pdf->AddPage();
                $pdf->SetFont('Arial', 'B', 13);
                $widthtext = $pdf->GetStringWidth($TITLE);
                $theX = ($wid_p / 2) - ($widthtext / 2);
                $pdf->SetXY($theX, 10);
                $pdf->Cell(10, 5, $TITLE, 0, 0, 'L');
                $widthtext = $pdf->GetStringWidth($TITLE2);
                $theX = ($wid_p / 2) - ($widthtext / 2);
                $pdf->SetXY($theX, 15);
                $pdf->Cell(10, 5, $TITLE2, 0, 0, 'L');

                $pdf->SetFont('Arial', 'B', 10);
                $pdf->SetXY(6, 20);
                $pdf->Cell(10, 5, "NO     : " . $pser, 0, 0, 'L');
                $pdf->SetXY(6, 25);
                $pdf->Cell(10, 5, "DATE : " . $ddate, 0, 0, 'L');
                $pdf->setFillColor(240, 240, 240);
                $pdf->SetFont('Arial', 'B', 9);
                $pdf->SetXY(6, 30);
                $pdf->Cell(10, 10, 'No.', 1, 0, 'C', 1);
                $pdf->Cell(30, 10, '', 1, 0, 'L', 1);
                $pdf->Cell(25, 10, '', 1, 0, 'L', 1);
                $pdf->Cell(30, 10, 'ASSY CODE', 1, 0, 'C', 1);
                $pdf->Cell(20, 10, 'JM Label', 1, 0, 'C', 1);
                $pdf->Cell(25, 10, 'Model', 1, 0, 'C', 1);
                $pdf->Cell(10, 10, '', 1, 0, 'C', 1);
                $pdf->Cell(13, 10, 'CUST', 1, 0, 'C', 1);
                $pdf->SetXY(16, 30);
                $pdf->Cell(30, 5, 'No RA', 0, 0, 'C');
                $pdf->Cell(25, 5, 'DATE', 0, 0, 'C');
                $pdf->SetXY(16, 35);
                $pdf->Cell(30, 5, 'SURAT JALAN', 0, 0, 'C');
                $pdf->Cell(25, 5, 'SURAT JALAN', 0, 0, 'C');

                $pdf->SetXY(146, 30);
                $pdf->Cell(10, 10, 'QTY', 0, 0, 'C');

                $pdf->SetXY(169, 30);
                $pdf->Cell(70, 5, 'CHANGE PART', 1, 0, 'C', 1);
                $pdf->SetXY(169, 35);
                $pdf->Cell(25, 5, 'PART CODE', 1, 0, 'C', 1);
                $pdf->Cell(25, 5, 'PART NAME', 1, 0, 'C', 1);
                $pdf->Cell(10, 5, 'QTY', 1, 0, 'C', 1);
                $pdf->Cell(10, 5, 'LOC.', 1, 0, 'C', 1);

                $pdf->SetXY(239, 30);
                $pdf->Cell(25, 10, '', 1, 0, 'C', 1);
                $pdf->Cell(25, 10, 'REMARK', 1, 0, 'C', 1);
                $pdf->SetXY(239, 30);
                $pdf->Cell(25, 5, 'CHANGE', 0, 0, 'C');
                $pdf->SetXY(239, 35);
                $pdf->Cell(25, 5, 'ASSY CODE', 0, 0, 'C');
                $cury = 40;
                $pdf->SetFont('Arial', '', 9);
            }
            $dmodel = str_replace("PCB ", "", $r['MITM_ITMD1']);
            $dmodel = str_replace("ASSY ", "", $dmodel);
            $stdt = date_create($r['SER_PRDDT']);
            $stdt = date_format($stdt, 'd-M-Y');
            $rmqty_dis = $r['RMQTY'];
            $no++;
            $nodis = $no;
            $jmbefore = $r['SERRC_JM'];

            $rmcd_dis = $r['SERRC_BOMPN'];
            $newassybefore = $r['SERRC_NASSYCD'] != $r['SER_ITMID'] ? $r['SERRC_NASSYCD'] : '';
            $newassy_dis = $newassybefore;
            $nodis = $no;
            $dokra_dis = $r['SER_DOC'];

            $dokradt_dis = $stdt;
            $assycd_dis = $r['SER_ITMID'];

            $jmlabel_dis = $jmbefore;
            $model_dis = $dmodel;
            $assyqty_dis = $r['SERRC_JM'] == '' ? $r['SERRC_SERXQTY'] : 1;
            $plant_dis = $r['PLANT'];
            $rmnm_dis = $r['MITM_SPTNO'];
            $rmrk_dis = $r['RMRK'];

            $pdf->SetXY(6, $cury);
            $pdf->Cell(10, $h_content, $nodis == '-' ? '' : $nodis, 1, 0, 'R');
            $pdf->Cell(30, $h_content, $dokra_dis == '-' ? '' : $dokra_dis, 1, 0, 'C');
            $pdf->Cell(25, $h_content, $dokradt_dis == '-' ? '' : $dokradt_dis, 1, 0, 'C');
            $pdf->Cell(30, $h_content, $assycd_dis, 1, 0, 'C');
            $pdf->SetFont('Arial', 'B', 9);
            $ttlwidth = $pdf->GetStringWidth($jmlabel_dis);
            if ($ttlwidth > 18) {
                $ukuranfont = 8.5;
                while ($ttlwidth > 18) {
                    $pdf->SetFont('Arial', 'B', $ukuranfont);
                    $ttlwidth = $pdf->GetStringWidth($jmlabel_dis);
                    $ukuranfont = $ukuranfont - 0.5;
                }
            }
            $pdf->Cell(20, $h_content, $jmlabel_dis, 1, 0, 'C');
            $pdf->SetFont('Arial', '', 7);
            $pdf->Cell(25, $h_content, $model_dis, 1, 0, 'L');
            $pdf->setFillColor(255, 255, 255);
            $pdf->Cell(10, $h_content, $assyqty_dis, 1, 0, 'C', 1);
            $pdf->SetFont('Arial', '', 9);
            $pdf->Cell(13, $h_content, $plant_dis, 1, 0, 'C', 1);
            $pdf->Cell(25, $h_content, $rmcd_dis, 1, 0, 'C', 1);
            $ttlwidth = $pdf->GetStringWidth($rmnm_dis);
            if ($ttlwidth > 25) {
                $ukuranfont = 8.5;
                while ($ttlwidth > 25) {
                    $pdf->SetFont('Arial', '', $ukuranfont);
                    $ttlwidth = $pdf->GetStringWidth($rmnm_dis);
                    $ukuranfont = $ukuranfont - 0.5;
                }
            }
            $pdf->Cell(25, $h_content, $rmnm_dis, 1, 0, 'C', 1);
            $pdf->SetFont('Arial', '', 9);
            $pdf->Cell(10, $h_content, $rmqty_dis == '0' ? '' : $rmqty_dis, 1, 0, 'C', 1);
            $pdf->Cell(10, $h_content, $r['SERRC_LOC'], 1, 0, 'C', 1);
            $pdf->Cell(25, $h_content, $newassy_dis, 1, 0, 'C', 1);
            $pdf->Cell(25, $h_content, $rmrk_dis, 1, 0, 'C', 1);
            $cury += $h_content;
        }

        $cury += 10;
        $pdf->SetXY(6, $cury);
        $pdf->Cell(30, 5, 'PREPARED', 1, 0, 'C');
        $pdf->Cell(30, 5, 'CHECKED', 1, 0, 'C');
        $pdf->Cell(30, 5, 'APPROVED', 1, 0, 'C');
        $pdf->SetXY(105, $cury);
        $pdf->Cell(30, 5, 'RECEIVED BY', 1, 0, 'C');
        $cury += 5;
        $pdf->SetXY(6, $cury);
        $pdf->Cell(30, 20, '', 1, 0, 'C');
        $pdf->Cell(30, 20, '', 1, 0, 'C');
        $pdf->Cell(30, 20, '', 1, 0, 'C');
        $pdf->SetXY(105, $cury);
        $pdf->Cell(30, 20, '', 1, 0, 'C');
        $cury += 20;
        $pdf->SetXY(6, $cury);
        $pdf->Cell(30, 5, '', 1, 0, 'C');
        $pdf->Cell(30, 5, '', 1, 0, 'C');
        $pdf->Cell(30, 5, '', 1, 0, 'C');
        $pdf->SetXY(105, $cury);
        $pdf->Cell(30, 5, '', 1, 0, 'C');

        #SAVE TO TRANSACTION
        $rsc = $this->SERRC_mod->select_where_group(['SERRC_DOCST' => $pser, "SERRC_SERX is NOT null" => null]);

        $rs_ext_u = [];
        $rs_ser_tbl = [];
        $rs_out = [];
        $currrtime = date('Y-m-d H:i:s');
        $current_date = date('Y-m-d');
        $jmmode = false;
        $bisgrup = '';
        $originWH = '';

        foreach ($rsc as $r) {
            if ($this->ITH_mod->check_Primary(['ITH_SER' => $r['SERRC_SER'], 'ITH_WH' => 'AFQART'])) {
                $originWH = 'NFWH4RT';
            } else {
                $originWH = 'AFWH3RT';
            }
            // die('sini2');
            break;
        }

        foreach ($rsc as $r) {
            $bisgrup = $r['MBSG_BSGRP'];
            $isfound = false;
            foreach ($rs_ext_u as $n) {
                if ($n['SER_ID'] == $r['SERRC_SERX']) {
                    $isfound = true;
                    break;
                }
            }
            if (!$isfound) {
                $clot = '';
                if ($r['SERRC_SERXRAWTXT'] != '') {
                    $astr = explode("|", $r['SERRC_SERXRAWTXT']);
                    $clot = $astr[2];
                    $clot = substr($clot, 2, strlen($clot));
                }
                $rs_ext_u[] = [
                    "SER_ID" => $r['SERRC_SERX'], "SER_REFNO" => $r['SERRC_SER'], "SER_ITMID" => $r['SERRC_NASSYCD'], "SER_LOTNO" => $clot, "SER_QTY" => $r['SERRC_SERXQTY'], "SER_RAWTXT" => $r['SERRC_SERXRAWTXT'], "SER_LUPDT" => $currrtime, "SER_USRID" => $this->session->userdata('nama'), "SER_ITMIDOLD" => $r['SER_ITMID'],
                ];
                $rs_ser_tbl[] = [
                    "SER_ID" => $r['SERRC_SERX'], "SER_DOC" => $pser, "SER_REFNO" => $r['SERRC_SER'], "SER_ITMID" => $r['SERRC_NASSYCD'], "SER_LOTNO" => $clot, "SER_QTY" => $r['SERRC_SERXQTY'], "SER_RAWTXT" => $r['SERRC_SERXRAWTXT'], "SER_LUPDT" => $currrtime, "SER_USRID" => $this->session->userdata('nama'),
                ];
            }
            if ($r['SERRC_JM'] == '') {
                $rs_out[] = [
                    'ITH_ITMCD' => $r['SER_ITMID']
                    , 'ITH_DATE' => $current_date
                    , 'ITH_FORM' => $r['SER_ITMID'] == $r['SERRC_NASSYCD'] ? 'OUT' : 'OUT-C'
                    , 'ITH_DOC' => $r['SER_DOC']
                    , 'ITH_QTY' => $r['SERRC_SERXQTY'] > $r['SER_QTY'] ? -$r['SER_QTY'] : -$r['SERRC_SERXQTY']
                    , 'ITH_WH' => $originWH === 'NFWH4RT' ? 'AFQART' : 'AFQART2'
                    , 'ITH_SER' => $r['SERRC_SER']
                    , 'ITH_REMARK' => $r['SERRC_SERX']
                    , 'ITH_LUPDT' => $currrtime
                    , 'ITH_USRID' => $this->session->userdata('nama'),
                ];
            } else {
                $jmmode = true;
            }
        }

        $RSNonJM = $this->SERRC_mod->selectWHBySerahTerimaRC($pser);
        foreach ($RSNonJM as $r) {
            if ($r['RCV_WH']) {
                $originWH = $r['RCV_WH'];
            }
        }

        if ($jmmode) {
            $rsjmmode = $this->SERRC_mod->select_out_usage($pser);
            # periksa contoh/sample 1 Reff Number
            $reffNumber = '';
            foreach ($rsjmmode as $r) {
                $reffNumber = $r['SERRC_SER'];
                break;
            }
            if ($this->ITH_mod->check_Primary(['ITH_SER' => $reffNumber, 'ITH_WH' => 'AFQART'])) {
                $originWH = 'NFWH4RT';
            } else {
                $originWH = 'AFWH3RT';
            }
            # akhir periksa
            foreach ($rsjmmode as $r) {
                $rs_out[] = [
                    'ITH_ITMCD' => $r['SER_ITMID']
                    , 'ITH_DATE' => $current_date
                    , 'ITH_FORM' => $r['SER_ITMID'] == $r['SERRC_NASSYCD'] ? 'OUT' : 'OUT-C'
                    , 'ITH_DOC' => $r['SER_DOC']
                    , 'ITH_QTY' => -$r['OUTQTY']
                    , 'ITH_WH' => $originWH === 'NFWH4RT' ? 'AFQART' : 'AFQART2'
                    , 'ITH_SER' => $r['SERRC_SER']
                    , 'ITH_REMARK' => $r['SERRC_SERX']
                    , 'ITH_LUPDT' => $currrtime
                    , 'ITH_USRID' => $this->session->userdata('nama'),
                ];
            }
        }

        if (count($rs_ext_u) > 0) {
            $rs_ser_val = [];
            $rs_tx_in = [];
            $rs_tx_out = [];
            #1 filter SER
            if ($bisgrup == 'PSI1PPZIEP') {
                foreach ($rs_ser_tbl as $r) {
                    if (!$this->SER_mod->check_Primary(['SER_ID' => $r['SER_ID']])) {
                        $rs_ser_val[] = $r;
                    }
                }

                if (count($rs_ser_val) > 0) {
                    $this->SER_mod->insertb($rs_ser_val);
                    #2 filter inc
                    foreach ($rs_ser_val as $s) {
                        foreach ($rs_ext_u as $r) {
                            if ($s['SER_ID'] == $r['SER_ID']) {
                                $rs_tx_in[] = [
                                    'ITH_ITMCD' => $r['SER_ITMID'], 'ITH_DATE' => $current_date, 'ITH_FORM' => 'INC-RCRTN-FG', 'ITH_DOC' => $pser, 'ITH_QTY' => $r['SER_QTY'], 'ITH_WH' => $originWH, 'ITH_LOC' => 'RC', 'ITH_SER' => $r['SER_ID'], 'ITH_LUPDT' => $currrtime, 'ITH_USRID' => $this->session->userdata('nama'),
                                ];
                            }
                        }
                    }

                    #3 filter out
                    foreach ($rs_ser_val as $s) {
                        foreach ($rs_out as $r) {
                            if ($s['SER_ID'] == $r['ITH_REMARK']) {
                                $rs_tx_out[] = $r;
                            }
                        }
                    }
                    $this->ITH_mod->insertb($rs_tx_out);
                    $this->ITH_mod->insertb($rs_tx_in);
                }
            } else {

                #1 filter inc
                foreach ($rs_ser_tbl as $r) {
                    $rs_ser_val[] = $r;
                }
                foreach ($rs_ser_val as $s) {
                    foreach ($rs_ext_u as $r) {
                        if ($s['SER_ID'] == $r['SER_ID']) {
                            $rs_tx_in[] = [
                                'ITH_ITMCD' => $r['SER_ITMID'], 'ITH_DATE' => $current_date, 'ITH_FORM' => 'INC-RCRTN-FG', 'ITH_DOC' => $pser, 'ITH_QTY' => $r['SER_QTY'], 'ITH_WH' => $originWH, 'ITH_LOC' => 'RC', 'ITH_SER' => $r['SER_ID'], 'ITH_LUPDT' => $currrtime, 'ITH_USRID' => $this->session->userdata('nama'),
                            ];
                        }
                    }
                }

                #2 filter out
                foreach ($rs_ser_val as $s) {
                    foreach ($rs_out as $r) {
                        if ($s['SER_ID'] == $r['ITH_REMARK']) {
                            $rs_tx_out[] = $r;
                        }
                    }
                }

                $this->ITH_mod->insertb($rs_tx_out);
                $this->ITH_mod->insertb($rs_tx_in);
            }
        }
        $pdf->Output('I', 'Serah Terima RC QC ' . $pser . " " . date("d-M-Y") . '.pdf');
    }

    public function validate_extid_rc()
    {
        header('Content-Type: application/json');
        $cid = $this->input->post('inid');
        $myar = [];
        if ($this->SER_mod->check_Primary(['SER_ID' => $cid])) {
            $myar[] = ['cd' => 0, 'msg' => 'the ID is already registered'];
        } else {
            $myar[] = ['cd' => 1, 'msg' => 'go ahead'];
        }
        exit('{"status": ' . json_encode($myar) . '}');
    }

    public function vsplitrtnlabel()
    {
        $this->load->view('wms/vsplitlabel_rtn');
    }

    public function check_reg_extlabel()
    {
        header('Content-Type: application/json');
        $myar = [];
        if ($this->SERRC_mod->check_Primary(['SERRC_SERX' => $this->input->get('inid')])) {
            $myar[] = ['cd' => 0, 'msg' => 'Already used'];
        } else {
            $myar[] = ['cd' => 1, 'msg' => 'go ahead'];
        }
        die('{"status":' . json_encode($myar) . '}');
    }

    public function get_rm_rc()
    {
        header('Content-Type: application/json');
        $qalabel = $this->input->get('qalabel');
        $rs = $this->SERRC_mod->select_where(['SERRC_SER' => $qalabel]);
        die('{"data":' . json_encode($rs) . '}');
    }
    public function get_rm_rc_by_st()
    {
        header('Content-Type: application/json');
        $doc = $this->input->get('doc');
        $rs = $this->SERRC_mod->select_where(['SERRC_DOCST' => $doc]);
        $rsex = $this->SERRC_mod->select_where_group(['SERRC_DOCST' => $doc, 'SERRC_SERX is not null' => null]);
        $myar[] = ['input' => $doc];
        die('{"data":' . json_encode($rs)
            . ', "additional": ' . json_encode($myar)
            . ', "data_ex": ' . json_encode($rsex) . '}');
    }

    public function checkbalanceqalabel()
    {
        header('Content-Type: application/json');
        $doc_st = $this->input->get('indoc_st');
        $inqalabel = $this->input->get('inqalabel');
        $rs = $this->SERRC_mod->select_balanceqalabel($inqalabel, $doc_st);
        die('{"data":' . json_encode($rs) . '}');
    }
    public function checkbalanceqalabel_wjm()
    {
        header('Content-Type: application/json');
        $doc_st = $this->input->get('indoc_st');
        $inqalabel = $this->input->get('inqalabel');
        $rsser = $this->SER_mod->selectbyVAR_where(['SER_ID' => $inqalabel]);
        $rsbal = $this->SERRC_mod->select_balanceqalabel_wjm($inqalabel, $doc_st);
        die('{"data":' . json_encode($rsbal) . ',"dataser":' . json_encode($rsser) . '}');
    }

    public function remove_rm_rc()
    {
        header('Content-Type: application/json');
        $qalabel = $this->input->post('qalabel');
        $line = $this->input->post('line');
        $docno = $this->input->post('docno');
        $ret = $this->SERRC_mod->deletebyID([
            'SERRC_SER' => $qalabel, 'SERRC_LINE' => $line, 'SERRC_DOCST' => $docno, 'SERRC_SERX IS NULL' => null,
        ]);
        $myar = [];
        if ($ret > 0) {
            $myar[] = ['cd' => 1, 'msg' => 'Deleted'];
        } else {
            $myar[] = ['cd' => 0, 'msg' => 'Could not delete'];
        }
        die('{"status":' . json_encode($myar) . '}');
    }

    public function setremark()
    {
        $creff = $this->input->post('inid');
        $crmrk = $this->input->post('inrmrk');
        $myar = [];
        if ($this->SER_mod->updatebyId(['SER_RMRK' => $crmrk], $creff) > 0) {
            $myar[] = ['cd' => 1, 'msg' => 'OK'];
        } else {
            $myar[] = ['cd' => 0, 'msg' => 'Could not save'];
        }
        echo '{"status": ' . json_encode($myar) . '}';
    }

    public function remove()
    {
        $cid = $this->input->post('inid');
        $toret = 0;
        $myar = [];
        $remark = "";
        $retfail = 0;
        if (is_array($cid)) {
            for ($i = 0; $i < count($cid); $i++) {
                if ($this->ITH_mod->check_Primary(['ITH_SER' => $cid[$i]]) > 0) {
                    $remark .= $cid[$i] . " is already scanned <br>";
                    $retfail += 1;
                } else {
                    $toret += $this->SER_mod->deletebyID(['SER_ID' => $cid[$i]]);
                }
            }
            if ($retfail == 0 && $toret > 0) {
                $remark = "Deleted";
            }
        }
        $myar[] = ["cd" => $toret, "msg" => $remark];
        echo '{"status":' . json_encode($myar) . '}';
    }
    public function remove_status_label()
    {
        $cid = $this->input->post('inid');
        $toret = 0;
        $myar = [];
        $remark = "";
        $retfail = 0;
        if (is_array($cid)) {
            for ($i = 0; $i < count($cid); $i++) {
                $rs = $this->ITH_mod->selectstock_ser($cid[$i]);
                $rs = count($rs) > 0 ? reset($rs) : ['ITH_WH' => '??'];
                if ($rs['ITH_WH'] != 'ARPRD1') {
                    $remark .= $cid[$i] . " is already scanned <br> " . $rs['ITH_WH'];
                    $retfail += 1;
                } else {
                    $toret += $this->SER_mod->deletebyID(['SER_ID' => $cid[$i]]);
                    $retbin = $this->ITH_mod->tobin($this->session->userdata('nama'), $cid);
                    $toret += $this->ITH_mod->deletebyID(['ITH_SER' => $cid[$i]]);
                }
            }
            if ($retfail == 0 && $toret > 0) {
                $remark = "Deleted";
            }
        }
        $myar[] = ["cd" => $toret, "msg" => $remark];
        echo '{"status":' . json_encode($myar) . '}';
    }

    public function getMonthDis($par)
    {
        switch ($par) {
            case "01":
                return "A";
                break;
            case "02":
                return "B";
                break;
            case "03":
                return "C";
                break;
            case "04":
                return "D";
                break;
            case "05":
                return "E";
                break;
            case "06":
                return "F";
                break;
            case "07":
                return "G";
                break;
            case "08":
                return "H";
                break;
            case "09":
                return "I";
                break;
            case "10":
                return "J";
                break;
            case "11":
                return "K";
                break;
            case "12":
                return "L";
                break;
        }
    }

    public function setsync()
    {
        $currrtime = date('Y-m-d H:i:s');
        $csheet = "1";
        $cproddt = "2020-07-21";
        $cline = "SMT";
        $cshift = "M1";
        $cmdl = 1;
        $rs = $this->SER_mod->selectsync();
        $pYear = substr($cproddt, 2, 2);
        $pMonth = substr($cproddt, 5, 2);
        $pDay = substr($cproddt, -2);
        $pMonthdis = $this->getMonthDis($pMonth);
        foreach ($rs as $r) {
            $newid = $this->SER_mod->lastserialid($cmdl, $cproddt);
            $newid++;
            $newid = $cmdl . $pYear . $pMonthdis . $pDay . substr('000000000' . $newid, -10);
            $datas = [
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
                'SER_USRID' => $this->session->userdata('nama'),
            ];
            $toret = $this->SER_mod->insert($datas);
        }
        echo "sudah";
    }

    public function dummylabel()
    {
        $rs = $this->SER_mod->select_dummy_ser();
        $currrtime = date('Y-m-d H:i:s');
        foreach ($rs as $r) {
            $citem = $r['MIGSCR_ITMCD'];
            $cjob = 'DOCINV202112';
            $cqty = $r['MIGSCR_QTY'];
            $csheet = '';
            $cproddt = '2021-12-13';
            $cline = '';
            $cshift = 1;
            $cmdl = 5;

            $pYear = substr($cproddt, 2, 2);
            $pMonth = substr($cproddt, 5, 2);
            $pDay = substr($cproddt, -2);
            $pMonthdis = $this->getMonthDis($pMonth);
            $newid = $this->SER_mod->lastserialid($cmdl, $cproddt);
            $newid++;
            $newid = $cmdl . $pYear . $pMonthdis . $pDay . substr('000000000' . $newid, -10);
            $datas = [
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
                'SER_USRID' => $this->session->userdata('nama'),
            ];
            $toret = $this->SER_mod->insert($datas);
        }
        echo "selesai";
    }

    public function setfg()
    {
        $currrtime = date('Y-m-d H:i:s');
        $citem = $this->input->post('initemcd');
        $cjob = $this->input->post('injob');
        $cqty = $this->input->post('inqty');
        $csheet = $this->input->post('insheet');
        $cproddt = $this->input->post('inproddt');
        $cline = $this->input->post('inline');
        $cshift = $this->input->post('inshift');
        $crank = $this->input->post('inrank');
        $cmdl = 1;
        $bisgrup = '';
        $myar = [];

        $rsjob = $this->SPL_mod->selectWO($cjob);
        foreach ($rsjob as $r) {
            $bisgrup = $r['PDPP_BSGRP'];
            if (((int) $r['LBLTTL'] + $cqty) > (int) $r['PDPP_WORQT']) {
                $myar[] = ["cd" => 0, "msg" => "Over"];
                exit(json_encode($myar));
            }
        }
        $pYear = substr($cproddt, 2, 2);
        $pMonth = substr($cproddt, 5, 2);
        $pDay = substr($cproddt, -2);
        $pMonthdis = $this->getMonthDis($pMonth);
        $newid = $this->SER_mod->lastserialid($cmdl, $cproddt);
        $newid++;
        $newid = $cmdl . $pYear . $pMonthdis . $pDay . substr('000000000' . $newid, -10);
        $datas = [
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
            'SER_BSGRP' => $bisgrup,
            'SER_GRADE' => $crank,
            'SER_LUPDT' => $currrtime,
            'SER_USRID' => $this->session->userdata('nama'),
        ];
        $toret = $this->SER_mod->insert($datas);

        $datar = $toret > 0 ? ["cd" => $toret, "msg" => "Saved successfully", "itemcd" => $citem, "doc" => $cjob] : ["cd" => $toret, "msg" => "Could not be saved"];

        $myar[] = $datar;
        echo json_encode($myar);
    }
    public function setfg_status()
    {
        $this->checkSession();
        $current_date = date('Y-m-d');
        $currrtime = date('Y-m-d H:i:s');
        $citem = $this->input->post('initemcd');
        $cjob = $this->input->post('injob');
        $cqty = $this->input->post('inqty');
        $cproddt = $this->input->post('inproddt');
        $cline = $this->input->post('inline');
        $cshift = $this->input->post('inshift');
        $cremark = $this->input->post('inremark');
        $cmdl = 3; #label status
        $myar = [];

        $rsjob = $this->SPL_mod->selectWO($cjob);
        foreach ($rsjob as $r) {
            if (((int) $r['LBLTTL'] + $cqty) > (int) $r['PDPP_WORQT']) {
                $myar[] = ["cd" => 0, "msg" => "Over"];
                exit(json_encode($myar));
            }
        }
        $pYear = substr($cproddt, 2, 2);
        $pMonth = substr($cproddt, 5, 2);
        $pDay = substr($cproddt, -2);
        $pMonthdis = $this->getMonthDis($pMonth);
        $newid = $this->SER_mod->lastserialid($cmdl, $cproddt);
        $newid++;
        $newid = $cmdl . $pYear . $pMonthdis . $pDay . substr('000000000' . $newid, -10);
        $datas = [
            'SER_ID' => $newid,
            'SER_REFNO' => $newid,
            'SER_DOC' => $cjob,
            'SER_DOCTYPE' => '1',
            'SER_ITMID' => $citem,
            'SER_QTY' => $cqty,
            'SER_QTYLOT' => $cqty,
            'SER_SHEET' => '',
            'SER_PRDDT' => $cproddt,
            'SER_PRDLINE' => $cline,
            'SER_PRDSHFT' => $cshift,
            'SER_RMRK' => $cremark,
            'SER_LUPDT' => $currrtime,
            'SER_USRID' => $this->session->userdata('nama'),
        ];
        $toret = $this->SER_mod->insert($datas);
        if ($toret > 0) {
            if ($this->ITH_mod->check_Primary(['ITH_SER' => $newid])) {
            } else {
                $this->ITH_mod->insert([
                    'ITH_ITMCD' => $citem, 'ITH_DATE' => $current_date, 'ITH_FORM' => 'INC', 'ITH_DOC' => $cjob, 'ITH_QTY' => $cqty, 'ITH_WH' => 'ARPRD1', 'ITH_SER' => $newid, 'ITH_LUPDT' => $currrtime, 'ITH_USRID' => $this->session->userdata('nama'),
                ]);
            }
            $datar = ["cd" => $toret, "msg" => "Saved successfully", "itemcd" => $citem, "doc" => $cjob];
        } else {
            $datar = ["cd" => $toret, "msg" => "Could not be saved"];
        }
        $myar[] = $datar;
        echo json_encode($myar);
    }

    public function setfg_return()
    {
        $this->checkSession();
        $currrtime = date('Y-m-d H:i:s');
        $cproddt = $this->input->post('indate');
        $cjob = $this->input->post('indoc');
        $citemcd = $this->input->post('initemcd');
        $cqty = $this->input->post('inqty');
        $cline = $this->input->post('inline');
        $pYear = substr($cproddt, 2, 2);
        $pMonth = substr($cproddt, 5, 2);
        $pDay = substr($cproddt, -2);
        $pHour = date('H');
        $pMonthdis = $this->getMonthDis($pMonth);
        $cmdl = '4';
        $ttldata = count($citemcd);
        $rsLocation = $this->RETFG_mod->select_XVU_RTN(['STKTRND1_DOCNO' => $cjob]);
        $mega_location = '';
        foreach ($rsLocation as $r) {
            $mega_location = rtrim($r['STKTRND1_LOCCDFR']);
        }
        for ($i = 0; $i < $ttldata; $i++) {
            if ($this->SER_mod->check_Primary(["SER_DOC" => $cjob, "SER_ITMID" => $citemcd[$i], "SER_PRDLINE" => $cline[$i]]) == 0) {
                $newid = $this->SER_mod->lastserialid_ihour($cmdl, $cproddt);
                $newid++;
                $newid = $cmdl . $pYear . $pMonthdis . $pDay . $pHour . substr('0000000' . $newid, -8);
                $datas = [
                    'SER_ID' => $newid,
                    'SER_REFNO' => $newid,
                    'SER_DOC' => $cjob,
                    'SER_DOCTYPE' => '1',
                    'SER_ITMID' => $citemcd[$i],
                    'SER_QTY' => $cqty[$i],
                    'SER_QTYLOT' => $cqty[$i],
                    'SER_SHEET' => '',
                    'SER_PRDDT' => $cproddt,
                    'SER_PRDLINE' => $cline[$i],
                    'SER_LUPDT' => $currrtime,
                    'SER_USRID' => $this->session->userdata('nama'),
                ];
                $toret = $this->SER_mod->insert($datas);
                if ($toret > 0) {
                    $this->ITH_mod->insert([
                        'ITH_ITMCD' => $citemcd[$i], 'ITH_DATE' => $cproddt, 'ITH_FORM' => 'INC', 'ITH_DOC' => $cjob, 'ITH_QTY' => $cqty[$i], 'ITH_WH' => $mega_location === 'NFWH4RT' ? 'AFQART' : 'AFQART2', 'ITH_SER' => $newid, 'ITH_LUPDT' => $cproddt . " 07:30:00", 'ITH_USRID' => $this->session->userdata('nama'),
                    ]);
                }
            }
        }
        $rs = $this->SER_mod->selectBC_RTN_Field_in($cjob, $cline);
        die('{"data": ' . json_encode($rs) . '}');
    }

    public function print_return_control_label()
    {
        global $wid, $hgt, $padX, $padY, $noseri, $ccustnm, $cmitmid, $cmitmd1, $cprodt, $cwo, $cprdline, $cprdshift, $cserqty, $csersheet, $cum, $crank, $cuscd, $cremark;
        function fnLeftstatus_rtn($pdf, $cleft, $pword)
        {
            global $wid, $hgt, $padX, $padY;
            if ($cleft > 0) {
                return $cleft + ($wid / 2 - ($pdf->GetStringWidth($pword) / 2));
            } else {
                return $wid / 2 - ($pdf->GetStringWidth($pword) / 2);
            }
        }

        function printTagstatus_rtn($pdf, $myleft, $mytop)
        {
            global $wid, $hgt, $padX, $padY, $noseri, $cmitmid, $cmitmd1, $cprodt, $cwo, $cprdline, $cprdshift, $cserqty, $cum, $crank, $cremark;
            $th_x = $padX + $myleft + 3;
            $th_y = $padY + $mytop + 4;
            $pdf->AddFont('Tahoma', '', 'tahoma.php');
            $pdf->AddFont('Tahoma', 'B', 'tahomabd.php');
            $pdf->SetFont('Tahoma', '', 9 + 3);
            $pdf->Text($th_x + 3, $th_y + 5, 'PT SMT INDONESIA');
            $pdf->SetFont('Tahoma', '', 6 + 3);
            $pdf->Text($th_x + 81, $th_y + 5, 'PSI-');
            $pdf->SetFont('Tahoma', 'B', 8 + 4);
            $pdf->Text($th_x + 23, $th_y + 12, 'RETURN CONTROL LABEL');

            $pdf->SetXY($th_x + 3, $th_y + 15);
            $pdf->Cell(30, 5, 'RA NO.', 1, 0, 'L');
            $pdf->SetFont('Tahoma', '', 8 + 3);
            $pdf->Cell(63, 5, $cwo, 1, 0, 'L');
            $pdf->SetXY($th_x + 3, $th_y + 20);
            $pdf->SetFont('Tahoma', 'B', 8 + 3);
            $pdf->Cell(30, 5, 'MODEL', 1, 0, 'L');
            $pdf->SetFont('Tahoma', '', 8 + 3);
            $ttlwidth = $pdf->GetStringWidth($cmitmd1);
            if ($ttlwidth > 63) {
                $ukuranfont = 10.5;
                while ($ttlwidth > 63) {
                    $pdf->SetFont('Tahoma', '', $ukuranfont);
                    $ttlwidth = $pdf->GetStringWidth($cmitmd1);
                    $ukuranfont = $ukuranfont - 0.5;
                }
            }
            $pdf->Cell(63, 5, $cmitmd1, 1, 0, 'L');
            $pdf->SetXY($th_x + 3, $th_y + 25);
            $pdf->SetFont('Tahoma', 'B', 8 + 3);
            $pdf->Cell(30, 7, 'ASSY NO.', 1, 0, 'L');
            $pdf->SetFont('Tahoma', '', 8 + 8);
            $pdf->Cell(63, 7, $cmitmid, 1, 0, 'L');

            $pdf->SetXY($th_x + 3, $th_y + 39 - 7);
            $pdf->SetFont('Tahoma', 'B', 8 + 3);
            $pdf->Cell(30, 5, 'Rec. DATE', 1, 0, 'L');
            $pdf->SetFont('Tahoma', '', 8 + 3);
            $pdf->Cell(63, 5, $cprodt, 1, 0, 'L');
            $pdf->SetXY($th_x + 3, $th_y + 44 - 7);
            $pdf->SetFont('Tahoma', 'B', 8 + 3);
            $pdf->Cell(30, 5, 'QTY', 1, 0, 'L');
            $pdf->SetFont('Tahoma', '', 8 + 3);
            $pdf->Cell(63, 5, number_format($cserqty), 1, 0, 'L');
            $pdf->SetXY($th_x + 3, $th_y + 49 - 7);
            $pdf->SetFont('Tahoma', 'B', 8 + 3);
            $pdf->Cell(30, 10, 'REMARK', 1, 0, 'L');
            $pdf->SetFont('Tahoma', '', 8 + 3);
            $pdf->Cell(63, 10, $cremark, 1, 0, 'L');
            $pdf->SetXY($th_x + 3, $th_y + 54);

            $noserencode = "Z1" . trim($cmitmid) . "&" . trim($crank) . "|Z7" . trim($cprdline) . "&" . $cprdshift . "|Z2" . $cwo . "|Z3" . number_format($cserqty, 0, '', '') . "|Z4" . trim($cmitmd1) . "|Z5" . $noseri . "|Z6";
            $image_name = $noserencode;
            $cmd = escapeshellcmd("Python d:\Apache24\htdocs\wms\smt.py \"$image_name\" 1 ");
            $op = shell_exec($cmd);
            $image_name = str_replace("/", "xxx", $image_name);
            $image_name = str_replace(" ", "___", $image_name);
            $image_name = str_replace("|", "lll", $image_name);
            $pdf->SetFont('Tahoma', '', 10);

            $pdf->Image(base_url("assets/imgs/" . $image_name . ".png"), $th_x + 79, $th_y + 60);
            $clebar = $pdf->GetStringWidth($noseri) + 20;
            $pdf->Code128($th_x + ($wid / 2) - ($clebar / 2), $th_y + ($hgt - 9), $noseri, $clebar, 5);

            $pdf->Rect($th_x + 1, $th_y + 1, $wid, $hgt);
            $clebar = $pdf->GetStringWidth($noseri);
            $pdf->Text($th_x + ($wid / 2) - ($clebar / 2), $th_y + 70, $noseri);
        }
        $pser = '';
        if (isset($_COOKIE["PRINTLABEL_FGRTN"])) {
            $pser = $_COOKIE["PRINTLABEL_FGRTN"];
        }
        if ($pser == '') {
            die('stop');
        } else {
            $pser = str_replace(str_split('"[]'), '', $pser);
            $pser = explode(",", $pser);
            $wid = 96.5;
            $hgt = 70;
            $thegap = 1.76;
            $padX = 0.35;
            $padY = 0.35;
            $pprsize = $_COOKIE["PRINTLABEL_FG_SIZE"];
            $rs = $this->SER_mod->selectBC_RTN_Field_byid_in($pser);
            $pdf = new PDF_Code39e128('P', 'mm', $pprsize);
            $pdf->AddPage();
            $hgt_p = $pdf->GetPageHeight();
            $wid_p = $pdf->GetPageWidth();
            $pdf->SetAutoPageBreak(true, 10);
            //$pdf->SetMargins(0,0);
            $cY = 0;
            $cX = 0;
            foreach ($rs as $r) {
                $noseri = $r->SER_ID;
                $cmitmid = trim($r->SER_ITMID);
                $cmitmd1 = trim($r->MITM_ITMD1);
                $cprodt = $r->RETFG_STRDT;
                $cwo = $r->SER_DOC;
                $cserqty = $r->SER_QTY;
                $cum = trim($r->MITM_STKUOM) == '' ? 'PCS' : trim($r->MITM_STKUOM);
                $cremark = $r->RETFG_RMRK;
                //check wheter the height is enough
                if (($hgt_p - ($cY + $thegap)) < $hgt) {
                    $pdf->AddPage();
                    $cY = 0;
                    $cX = 0;
                    printTagstatus($pdf, $cX, $cY);
                    $cX += $wid + $thegap;
                } else {
                    if (($wid_p - $cX) > $wid) { // jika (lebar printer-posisi X)> lebar label
                        printTagstatus_rtn($pdf, $cX, $cY);
                        $cX += $wid + $thegap;
                    } else {
                        $cY += $hgt + $thegap;
                        if (($hgt_p - ($cY + $thegap)) < $hgt) {
                            $pdf->AddPage();
                            $cX = 0;
                            $cY = 0;
                            printTagstatus_rtn($pdf, $cX, $cY);
                        } else {
                            $cX = 0;
                            printTagstatus_rtn($pdf, $cX, $cY);
                        }
                        $cX += $wid + $thegap;
                    }
                }
            }
            $pdf->Output('I', 'FG RETURN CONTROL LABEL ' . date("d-M-Y") . '.pdf');
        }
    }

    public function setrm()
    {
        $currdate = date('Y-m-d');
        $currrtime = date('Y-m-d H:i:s');
        $citem = $this->input->post('initemcd');
        $cjob = $this->input->post('injob');
        $cqty = $this->input->post('inqty');
        $clot = $this->input->post('inlot');
        $crohs = $this->input->post('inrohs');
        $ccountry = $this->input->post('incountry');
        $cmdl = 0;
        $myar = array();

        $rsjob = $this->RCV_mod->selectDO($cjob);
        foreach ($rsjob as $r) {
            if ((trim($citem) == trim($r['RCV_ITMCD'])) && ((int) $r['LBLTTL'] + $cqty) > (int) $r['RCV_QTY']) {
                $datar = array("cd" => 0, "msg" => "Over $r[LBLTTL] $cqty $r[RCV_QTY]");
                array_push($myar, $datar);
                echo json_encode($myar);
                exit();
            }
        }
        $pYear = substr($currdate, 2, 2);
        $pMonth = substr($currdate, 5, 2);
        $pDay = substr($currdate, -2);
        $pMonthdis = $this->getMonthDis($pMonth);
        $newid = $this->SER_mod->lastserialid($cmdl, $currdate);
        $newid++;
        $newid = $cmdl . $pYear . $pMonthdis . $pDay . substr('000000000' . $newid, -10);
        $datas = [
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
            'SER_USRID' => $this->session->userdata('nama'),
        ];
        $toret = $this->SER_mod->insert($datas);

        if ($toret > 0) {
            $datar = array("cd" => $toret, "msg" => "Saved successfully", "itemcd" => $citem, "doc" => $cjob);
        } else {
            $datar = array("cd" => $toret, "msg" => "Could not be saved");
        }
        array_push($myar, $datar);
        echo json_encode($myar);
    }

    public function getdocg()
    {
        $cdoc = $this->input->get('inkey');
        $rs = $this->SER_mod->selectbyVARg(['SER_DOC' => $cdoc, 'SER_DOCTYPE' => '1', 'SUBSTRING(SER_ID,1,1)' => 1]);
        echo json_encode($rs);
    }

    public function getdocg_status()
    {
        $cdoc = $this->input->get('inkey');
        $rs = $this->SER_mod->selectbyVARg(['SER_DOC' => $cdoc, 'SER_DOCTYPE' => '1', 'SUBSTRING(SER_ID,1,1)' => 3]);
        echo json_encode($rs);
    }

    public function getdocg_rm()
    {
        $cdoc = $this->input->get('inkey');
        $dataw = ['SER_DOC' => $cdoc, 'SER_DOCTYPE' => '0'];
        $rs = $this->SER_mod->selectDOCbyVARg($dataw);
        echo json_encode($rs);
    }

    public function getdoclike()
    {
        $cdoc = $this->input->get('indoc');
        $citem = $this->input->get('initm');
        if ($citem == '') {
            echo '{"data":' . json_encode([]) . '}';
        } else {
            $rs = $this->SER_mod->selectbyVAR(['SER_DOC' => $cdoc, 'SER_ITMID' => $citem]);
            echo '{"data":' . json_encode($rs) . '}';
        }
    }
    public function getdoclike_lblstatus()
    {
        $cdoc = $this->input->get('indoc');
        $citem = $this->input->get('initm');
        if ($citem == '') {
            echo '{"data":' . json_encode([]) . '}';
        } else {
            $rs = $this->SER_mod->select_checklistprintstatus_label($cdoc, $citem);
            echo '{"data":' . json_encode($rs) . '}';
        }
    }

    public function getbydoc()
    {
        $cdoc = $this->input->get('indoc');
        if ($cdoc == '') {
            echo '{"data":' . json_encode([]) . '}';
        } else {
            $rs = $this->SER_mod->selectbyVAR(['SER_DOC' => $cdoc]);
            echo '{"data":' . json_encode($rs) . '}';
        }
    }

    public function printfglabel()
    {
        global $wid, $hgt, $padX, $padY, $noseri, $ccustnm, $cmitmid, $cmitmd1, $cprodt, $cwo, $cprdline, $cprdshift, $cserqty, $csersheet, $cum, $crank, $cuscd;
        function fnLeft($pdf, $cleft, $pword)
        {
            global $wid, $hgt, $padX, $padY;
            if ($cleft > 0) {
                return $cleft + ($wid / 2 - ($pdf->GetStringWidth($pword) / 2));
            } else {
                return $wid / 2 - ($pdf->GetStringWidth($pword) / 2);
            }
        }

        function printTag($pdf, $myleft, $mytop)
        {
            global $wid, $hgt, $padX, $padY, $noseri, $ccustnm, $cmitmid, $cmitmd1, $cprodt, $cwo, $cprdline, $cprdshift, $cserqty, $csersheet, $cum, $crank, $cuscd;
            $th_x = $padX + $myleft + 3;
            $th_y = $padY + $mytop + 4;
            $pdf->AddFont('Tahoma', '', 'tahoma.php');
            $pdf->AddFont('Tahoma', 'B', 'tahomabd.php');
            $pdf->SetFont('Tahoma', 'B', 9);
            $ttlwidth = $pdf->GetStringWidth('CUSTOMER : ' . $ccustnm);
            if ($ttlwidth > 90) {
                $ukuranfont = 8.5;
                while ($ttlwidth > 90) {
                    $pdf->SetFont('Tahoma', '', $ukuranfont);
                    $ttlwidth = $pdf->GetStringWidth('CUSTOMER : ' . $ccustnm);
                    $ukuranfont = $ukuranfont - 0.5;
                }
            }
            $pdf->Text($th_x + 3, $th_y + 5, 'CUSTOMER : ' . $ccustnm);

            $pdf->Line($th_x + 1, $th_y + 9, $th_x + 97.5, $th_y + 9);
            $pdf->Line($th_x + 79, $th_y + 1, $th_x + 79, $th_y + 54); //VERICAL LINE
            $pdf->SetFont('Tahoma', '', 10);
            $pdf->Text($th_x + 80, $th_y + 4, 'FPII-16-02');
            $pdf->Text($th_x + 82, $th_y + 8, 'Rev : 02');
            $pdf->SetFont('Tahoma', '', 9);
            $pdf->Text($th_x + 81, $th_y + 13, 'QC CHECK');
            $pdf->Line($th_x + 79, $th_y + 14, $th_x + 97.5, $th_y + 14);
            $pdf->Line($th_x + 79, $th_y + 31, $th_x + 97.5, $th_y + 31);
            $pdf->Text($th_x + 79.5, $th_y + 35, 'PPIC CHECK');
            $pdf->Line($th_x + 79, $th_y + 36, $th_x + 97.5, $th_y + 36);
            $pdf->SetFont('Tahoma', '', 10);
            $pdf->Text($th_x + 2, $th_y + 13, 'PART NAME');
            $pdf->Text($th_x + 2, $th_y + 17, 'PART NUMBER');
            $pdf->SetFont('Tahoma', '', 10);
            $pdf->Text($th_x + 2, $th_y + 28, 'PRODUCTION DATE');
            $pdf->Text($th_x + 2, $th_y + 33, 'JOB NUMBER');
            $pdf->Text($th_x + 2, $th_y + 42, 'PROD. LINE / SHIFT');
            $pdf->Text($th_x + 2, $th_y + 46, 'QUANTITY');
            $pdf->Text($th_x + 35, $th_y + 13, ":"); //$cmitmd1
            $ttlwidth = $pdf->GetStringWidth($cmitmd1);
            if ($ttlwidth > 42.17) {
                $ukuranfont = 9;
                while ($ttlwidth > 42.17) {
                    $pdf->SetFont('Tahoma', '', $ukuranfont);
                    $ttlwidth = $pdf->GetStringWidth($cmitmd1);
                    $ukuranfont = $ukuranfont - 0.5;
                }
            }
            $pdf->Text($th_x + 37, $th_y + 13, trim($cmitmd1)); //$cmitmd1
            $pdf->Text($th_x + 35, $th_y + 17, ":"); //$cmitmd1
            $pdf->Code39($th_x + 37, $th_y + 14, trim(str_replace("^", "", $cmitmid)), 0.47);
            $pdf->SetFont('Tahoma', 'B', 10);
            $pdf->Text($th_x + 36, $th_y + 23, " " . trim($cmitmid) . " / " . $crank); #
            $pdf->SetFont('Tahoma', '', 10);
            $pdf->Text($th_x + 35, $th_y + 28, ":");
            $pdf->Line($th_x + 37, $th_y + 25, $th_x + 58, $th_y + 25);
            $pdf->Line($th_x + 37, $th_y + 29, $th_x + 58, $th_y + 29);
            $pdf->Line($th_x + 37, $th_y + 25, $th_x + 37, $th_y + 29); //VERTICAL
            $pdf->Line($th_x + 42.5, $th_y + 25, $th_x + 42.5, $th_y + 29); //VERTICAL
            $pdf->Line($th_x + 48, $th_y + 25, $th_x + 48, $th_y + 29); //VERTICAL
            $pdf->Line($th_x + 58, $th_y + 25, $th_x + 58, $th_y + 29); //VERTICAL
            $pdf->SetFont('Tahoma', '', 10);
            $pdf->Text($th_x + 37.5, $th_y + 28, $cprodt[2]);
            $pdf->Text($th_x + 43.5, $th_y + 28, $cprodt[1]);
            $pdf->Text($th_x + 49.5, $th_y + 28, $cprodt[0]);
            // $clebar = $pdf->GetStringWidth($cwo)+25;
            $pdf->Text($th_x + 35, $th_y + 33, ":");
            $pdf->Code39($th_x + 37, $th_y + 30, $cwo);
            $pdf->SetFont('Tahoma', 'B', 10);
            $pdf->Text($th_x + 36, $th_y + 38, " " . $cwo);
            $pdf->SetFont('Tahoma', '', 10);
            $pdf->Text($th_x + 35, $th_y + 42, ":");
            $pdf->SetFont('Tahoma', 'B', 10);
            $pdf->Text($th_x + 36, $th_y + 42, " " . $cprdline . "/" . $cprdshift);
            $pdf->Code39($th_x + 37, $th_y + 43, number_format($cserqty, 0, "", ""));
            $pdf->SetFont('Tahoma', '', 10);
            $pdf->Text($th_x + 35, $th_y + 47, ":");
            $pdf->SetFont('Tahoma', 'B', 10);
            $pdf->Text($th_x + 37, $th_y + 52, number_format($cserqty) . " $cum ( " . $csersheet . " sheets)");

            $pdf->Line($th_x + 1, $th_y + 54, $th_x + 97.5, $th_y + 54);
            $pdf->SetFont('Tahoma', '', 10);
            if ($cuscd != 'PSI2PPZOMI' && $cuscd != 'PSI2PPZOMC') {
                $pdf->Text($th_x + 2, $th_y + 58, 'OQC INSPECTOR');
                $pdf->Text($th_x + 2, $th_y + 62, 'REMARK');
                $pdf->Text($th_x + 35, $th_y + 58, ':');
                $pdf->Text($th_x + 35, $th_y + 62, ':');
            } else {
                $AOMR_TGL = [];
                for ($i = 1; $i <= 31; $i++) {
                    $AOMR_TGL[] = $i;
                }
                // $AOMR_TGL_ALIAS = [];
                // for($i=1;$i<=9;$i++){
                //     array_push($AOMR_TGL_ALIAS, $i);
                // }
                $AOMR_TGL_ALIAS = range(1, 9);
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
                for ($bb = 0; $bb < count($AOMR_TGL); $bb++) {
                    if ($AOMR_TGL[$bb] == intval($cprodt[2])) {
                        $OMR_UNIQUE_DATE = $AOMR_TGL_ALIAS[$bb];
                        break;
                    }
                }
                $OMR_UNIQUE_MONTH = '';
                for ($bb = 0; $bb < count($AOMR_TGL); $bb++) {
                    if ($AOMR_TGL[$bb] == intval($cprodt[1])) {
                        $OMR_UNIQUE_MONTH = $AOMR_TGL_ALIAS[$bb];
                        break;
                    }
                }
                $OMR_UNIQUE_YEAR = '';
                for ($bb = 0; $bb < count($AOMR_TGL); $bb++) {
                    if ($AOMR_TGL[$bb] == intval(substr($cprodt[0], -2))) {
                        $OMR_UNIQUE_YEAR = $AOMR_TGL_ALIAS[$bb];
                        break;
                    }
                }
                $aomr_line = explode("-", $cprdline);
                $OMRQR = 'I00152';
                $OMR_PIM = substr("000000000" . $cmitmid, -9);
                $OMR_QTY = substr("00000000" . number_format($cserqty, 0, "", ""), -8);
                $OMR_DT = $cprodt[2] . $cprodt[1] . substr($cprodt[0], -2);
                $OMR_CVT = substr("000" . substr($aomr_line[1], 0, 3), -3);
                $OMR_MC = '';
                $OMR_DIES = "    -";
                $OMR_SHIFT = str_replace(['M', 'N'], "", $cprdshift);
                $OMR_NOURUT = substr($noseri, -5);
                $OMR_UNIQUE = $OMR_UNIQUE_YEAR . $OMR_UNIQUE_MONTH . $OMR_UNIQUE_DATE . $OMR_NOURUT; //substr($noseri,1,5).$OMR_NOURUT;
                $OMR_LOT = substr("0000000000000000000000000" . $cwo . '-' . $cmitmid, -25);
                if (substr($aomr_line[1], 0, 3) == 'ATH') {
                    $OMR_MC = substr('000' . substr($aomr_line[1], -3), -3);
                } else {
                    $OMR_MC = '000';
                }

                $pdf->Text($th_x + 2 + 15, $th_y + 58, 'OQC INSPECTOR');
                $pdf->Text($th_x + 2 + 15, $th_y + 62, 'REMARK');
                $pdf->Text($th_x + 35 + 15, $th_y + 58, ':');
                $pdf->Text($th_x + 35 + 15, $th_y + 62, ':');
                $pdf->Text($th_x + 2 + 15, $th_y + 66, 'UNIQUE OMRON');
                $pdf->Text($th_x + 35 + 15, $th_y + 66, ': ' . $OMR_UNIQUE);
                $OMRQR .= $OMR_PIM . $OMR_QTY . $OMR_DT . $OMR_CVT . $OMR_MC . $OMR_DIES . $OMR_SHIFT . $OMR_UNIQUE . $OMR_LOT;
                $image_omr = $OMRQR;
                $cmd = escapeshellcmd("Python d:\Apache24\htdocs\wms\smt.py \"$image_omr\" 1 ");
                $op = shell_exec($cmd);
                $image_omr = str_replace("/", "xxx", $image_omr);
                $image_omr = str_replace(" ", "___", $image_omr);
                $image_omr = str_replace("|", "lll", $image_omr);
                $pdf->SetFont('Tahoma', '', 5);
                //$pdf->TextWithDirection($th_x + 2,$th_y + 54.5,'QR Omron','D');
                $pdf->Text($th_x + 4, $th_y + 66.5, 'QR OMRON');
                $pdf->Text($th_x + 86, $th_y + 66.5, 'QR SMT');
                $pdf->Image(base_url("assets/imgs/" . $image_omr . ".png"), $th_x + 4, $th_y + 54.5);
            }
            $pdf->SetFont('Tahoma', '', 10);

            $noserencode = "Z1" . trim($cmitmid) . "&" . trim($crank) . "|Z7" . trim($cprdline) . "&" . $cprdshift . "|Z2" . $cwo . "|Z3" . number_format($cserqty, 0, '', '') . "|Z4" . trim($cmitmd1) . "|Z5" . $noseri . "|Z6";
            $image_name = $noserencode; //$noseri; //'LB'.$cmitmid.'|'.$cwo.'|'.number_format($cserqty);
            $cmd = escapeshellcmd("Python d:\Apache24\htdocs\wms\smt.py \"$image_name\" 1 ");
            $op = shell_exec($cmd);
            $image_name = str_replace("/", "xxx", $image_name);
            $image_name = str_replace(" ", "___", $image_name);
            $image_name = str_replace("|", "lll", $image_name);
            $pdf->SetFont('Tahoma', '', 5);

            $pdf->Image(base_url("assets/imgs/" . $image_name . ".png"), $th_x + 86, $th_y + 54.5);
            $pdf->Line($th_x + 1, $th_y + 67, $th_x + 97.5, $th_y + 67);
            $pdf->Rect($th_x + 1, $th_y + 1, $wid, $hgt);
            $pdf->SetFont('Tahoma', 'B', 8);
            $pdf->Text($th_x + 3, $th_y + 70, 'PT. SMT INDONESIA');
            // $pdf->Code39($th_x +32, $th_y+71.5,$noseri,0.5,4);

            $pdf->Text($th_x + 68, $th_y + 70, $noseri);
        }
        $pser = '';
        if (isset($_COOKIE["PRINTLABEL_FG"])) {
            $pser = $_COOKIE["PRINTLABEL_FG"];
        }
        if ($pser == '') {
            die('stop');
        } else {
            $wid = 96.5;
            $hgt = 70;
            $thegap = 1.76;
            $padX = 0.35;
            $padY = 0.35;
            $pprsize = $_COOKIE["PRINTLABEL_FG_SIZE"];
            $lbltype = $_COOKIE["PRINTLABEL_FG_LBLTYPE"];
            $pser = str_replace(str_split('"[]'), '', $pser);
            $pser = explode(",", $pser);
            $rs = [];
            if ($this->SERC_mod->check_Primary(['SERC_NEWID' => $pser[0]]) > 0) {
                $rs = $this->SER_mod->selectBCField_in_nomega($pser);
                $rs_tprint = $this->SERC_mod->select_exact_byVAR(['SERC_NEWID' => $pser[0]]);
                foreach ($rs_tprint as $k) {
                    foreach ($rs as &$u) {
                        $u->MCUS_CUSNM = $k['MCUS_CUSNM'];
                    }
                    unset($u);
                }
            } else {
                $rs = $this->SER_mod->selectBCField_in($pser);
            }
            $pdf = new PDF_Code39e128('L', 'mm', array(104, 77));
            if ($lbltype == '1') {
                $pdf = new PDF_Code39e128('P', 'mm', $pprsize);
                $pdf->AddPage();
                $hgt_p = $pdf->GetPageHeight();
                $wid_p = $pdf->GetPageWidth();
                $pdf->SetAutoPageBreak(true, 10);
                //$pdf->SetMargins(0,0);
                $cY = 0;
                $cX = 0;
                foreach ($rs as $r) {
                    $awo = explode('-', $r->SER_DOC == '20-2A21-F65255-05-1.00' ? '23-2A21-F65255-05-1.00' : $r->SER_DOC);
                    $ccustnm = $r->MCUS_CUSNM;
                    $cuscd = trim($r->PDPP_BSGRP);
                    $noseri = $r->SER_ID;
                    $cmitmid = trim($r->SER_ITMID);
                    $cmitmd1 = trim($r->MITM_ITMD1);
                    $cprodt = explode('-', $r->SER_PRDDT);
                    $cwo = $awo[0] . '-' . $awo[1];
                    $cprdline = trim($r->SER_PRDLINE);
                    $cprdshift = $r->SER_PRDSHFT;
                    $cserqty = $r->SER_QTY;
                    $csersheet = $r->SER_SHEET;
                    $cum = trim($r->MITM_STKUOM) == '' ? 'PCS' : trim($r->MITM_STKUOM);
                    $crank = trim($r->MBOM_GRADE);
                    //check wheter the height is enough
                    if (($hgt_p - ($cY + $thegap)) < $hgt) {
                        $pdf->AddPage();
                        $cY = 0;
                        $cX = 0;
                        printTag($pdf, $cX, $cY);
                        $cX += $wid + $thegap;
                    } else {
                        if (($wid_p - $cX) > $wid) { // jika (lebar printer-posisi X)> lebar label
                            printTag($pdf, $cX, $cY);
                            $cX += $wid + $thegap;
                        } else {
                            $cY += $hgt + $thegap;
                            if (($hgt_p - ($cY + $thegap)) < $hgt) {
                                $pdf->AddPage();
                                $cX = 0;
                                $cY = 0;
                                printTag($pdf, $cX, $cY);
                            } else {
                                $cX = 0;
                                printTag($pdf, $cX, $cY);
                            }
                            $cX += $wid + $thegap;
                        }
                    }
                }
            } else {
                $hgt_p = $pdf->GetPageHeight();
                $wid_p = $pdf->GetPageWidth();
                $pdf->SetAutoPageBreak(true, 1);
                $pdf->SetMargins(0, 0);
                foreach ($rs as $r) {
                    $cwo = substr(trim($r->SER_DOC), 0, 7);
                    $cmitmid = trim($r->SER_ITMID);
                    $cserqty = $r->SER_QTY;
                    $ccustnm = trim($r->MCUS_CUSNM);
                    // $cuscd     = trim($r->PDPP_CUSCD);
                    $cprodt = explode('-', $r->SER_PRDDT);
                    $cum = trim($r->MITM_STKUOM);
                    $crank = trim($r->MBOM_GRADE);

                    $pdf->AddPage();
                    $pdf->SetFont('Arial', 'B', 8);
                    $pdf->Text(3, 5, 'CUSTOMER :');

                    $pdf->Text(21, 5, $ccustnm);
                    $pdf->Line(1, 9, 103, 9);
                    $pdf->Line(85, 1, 85, 57); //VERICAL LINE
                    $pdf->SetFont('Arial', '', 8);
                    $pdf->Text(87, 8, 'Rev : 01');
                    $pdf->Text(86.5, 12, 'QC CHECK');
                    $pdf->Line(85, 13, 103, 13);
                    $pdf->Line(85, 32, 103, 32);
                    $pdf->Text(86.5, 35, 'QC CHECK');
                    $pdf->Line(85, 36, 103, 36);
                    $pdf->Text(2, 12, 'PART NAME');
                    $pdf->SetFont('Arial', '', 7.5);
                    $pdf->Text(2, 15, 'PART NUMBER/RANK');
                    $pdf->SetFont('Arial', '', 8);
                    $pdf->Text(2, 25, 'PRODUCTION DATE');
                    $pdf->Text(2, 29, 'JOB NUMBER');
                    $pdf->Text(2, 38, 'PROD. LINE / SHIFT');
                    $pdf->Text(2, 42, 'QUANTITY');
                    $pdf->Text(30, 12, ':');
                    $pdf->Text(30, 15, ':');
                    $pdf->Text(30, 25, ':');
                    $pdf->Text(30, 29, ':');
                    $pdf->Text(30, 38, ':');
                    $pdf->Text(30, 42, ':');
                    $pdf->Text(32, 12, trim($r->MITM_ITMD1));
                    $clebar = $pdf->GetStringWidth($cmitmid) + 10;
                    if (strpos($r->SER_ITMID, "_")) {
                        $pdf->Code128(32, 13, $cmitmid, $clebar, 5);
                    } else {
                        $pdf->Code39(32, 13, $cmitmid);
                    }
                    $pdf->Text(32, 21, $cmitmid . " / " . $crank);
                    $pdf->Line(32, 22, 53, 22);
                    $pdf->Line(32, 26, 53, 26);
                    $pdf->Line(32, 22, 32, 26); //VERTICAL
                    $pdf->Line(38, 22, 38, 26); //VERTICAL
                    $pdf->Line(44, 22, 44, 26); //VERTICAL
                    $pdf->Line(53, 22, 53, 26); //VERTICAL
                    $pdf->Text(33, 25, $cprodt[2]);
                    $pdf->Text(39, 25, $cprodt[1]);
                    $pdf->Text(45, 25, $cprodt[0]);
                    $clebar = $pdf->GetStringWidth($cwo) + 25;
                    $pdf->Code39(32, 27, $cwo);
                    $pdf->Text(32, 35, $cwo);
                    $pdf->Text(32, 38, $r->SER_PRDLINE . "/" . $r->SER_PRDSHFT);
                    $clebar = $pdf->GetStringWidth($r->SER_QTY) + 10;
                    // $pdf->Code39(32,39,number_format($r->SER_QTY));
                    $pdf->Text(32, 47, number_format($r->SER_QTY) . " $cum ( " . $r->SER_SHEET . " sheets)");

                    $pdf->Line(1, 57, 103, 57);
                    $pdf->SetFont('Arial', '', 8);
                    $pdf->Text(3, 60, 'OQC INSPECTOR');
                    $pdf->Text(3, 64, 'REMARK');
                    $pdf->Text(30, 60, ':');
                    $pdf->Text(30, 64, ':');
                    // $pdf->Text(3,74,'REMARK');
                    $image_name = 'LB' . $cmitmid . '|' . $cwo . '|' . number_format($cserqty);
                    $cmd = escapeshellcmd("Python d:\Apache24\htdocs\wms\smt.py \"$image_name\" ");
                    $op = shell_exec($cmd);
                    $image_name = str_replace("/", "xxx", $image_name);
                    $image_name = str_replace(" ", "___", $image_name);
                    $image_name = str_replace("|", "lll", $image_name);
                    $pdf->Image(base_url("assets/imgs/" . $image_name . ".png"), 87, 58);
                    $pdf->Line(1, 71, 103, 71);
                    $pdf->Rect(1, 1, 102, 75);
                    $pdf->SetFont('Arial', 'B', 8);
                    $pdf->Text(3, 74, 'PT. SMT INDONESIA');
                    // $pdf->Code39(32,71.5,$r->SER_ID,0.5,4);
                    $pdf->Text(85, 74, $r->SER_ID);
                }
            }

            $pdf->Output('I', 'FG LABEL ' . date("d-M-Y") . '.pdf');
        }
    }
    public function printfgstatuslabel()
    {
        global $wid, $hgt, $padX, $padY, $noseri, $ccustnm, $cmitmid, $cmitmd1, $cprodt, $cwo, $cprdline, $cprdshift, $cserqty, $csersheet, $cum, $crank, $cuscd, $cremark;
        function fnLeftstatus($pdf, $cleft, $pword)
        {
            global $wid, $hgt, $padX, $padY;
            if ($cleft > 0) {
                return $cleft + ($wid / 2 - ($pdf->GetStringWidth($pword) / 2));
            } else {
                return $wid / 2 - ($pdf->GetStringWidth($pword) / 2);
            }
        }

        function printTagstatus($pdf, $myleft, $mytop)
        {
            global $wid, $hgt, $padX, $padY, $noseri, $cmitmid, $cmitmd1, $cprodt, $cwo, $cprdline, $cprdshift, $cserqty, $cum, $crank, $cremark;
            $th_x = $padX + $myleft + 3;
            $th_y = $padY + $mytop + 4;
            $yearCODE = '';
            $monthCODE = '';
            switch ($cprodt[0]) {
                case '2020':
                    $yearCODE = 'A';
                    break;
                case '2021':
                    $yearCODE = 'B';
                    break;
                case '2022':
                    $yearCODE = 'C';
                    break;
                case '2023':
                    $yearCODE = 'D';
                    break;
                case '2024':
                    $yearCODE = 'E';
                    break;
                case '2025':
                    $yearCODE = 'F';
                    break;
                case '2026':
                    $yearCODE = 'G';
                    break;
            }
            $cmonth = intval($cprodt[1]);
            if ($cmonth == 10) {
                $monthCODE = 'X';
            } else if ($cmonth == 11) {
                $monthCODE = 'Y';
            } else if ($cmonth == 12) {
                $monthCODE = 'Z';
            } else {
                $monthCODE = $cmonth;
            }
            $clineNAME = preg_replace('/[0-9]+/', '', $cprdline);
            $pdf->AddFont('Tahoma', '', 'tahoma.php');
            $pdf->AddFont('Tahoma', 'B', 'tahomabd.php');
            $pdf->SetFont('Tahoma', '', 9 + 3);
            $pdf->Text($th_x + 3, $th_y + 5, 'PT SMT INDONESIA');
            $pdf->SetFont('Tahoma', '', 6 + 3);
            $pdf->Text($th_x + 120, $th_y + 5, 'FPI-16-03');
            $pdf->Text($th_x + 120, $th_y + 8, 'REV-00');
            $pdf->SetFont('Tahoma', 'B', 8 + 4);
            $pdf->Text($th_x + 55, $th_y + 10, 'LABEL STATUS');

            $pdf->SetXY($th_x + 3, $th_y + 15);
            $pdf->Cell(30, 5, 'GROUP/LINE', 1, 0, 'L');
            $pdf->SetFont('Tahoma', '', 8 + 3);
            $pdf->Cell(100, 5, $cprdshift . ' / ' . $cprdline, 1, 0, 'L');
            $pdf->SetXY($th_x + 3, $th_y + 20);
            $pdf->SetFont('Tahoma', 'B', 8 + 3);
            $pdf->Cell(30, 5, 'MODEL', 1, 0, 'L');
            $pdf->SetFont('Tahoma', '', 8 + 3);
            $pdf->Cell(100, 5, $cmitmd1, 1, 0, 'L');
            $pdf->SetXY($th_x + 3, $th_y + 25);
            $pdf->SetFont('Tahoma', 'B', 8 + 3);
            $pdf->Cell(30, 7, 'ASSY NO.', 1, 0, 'L');
            $pdf->SetFont('Tahoma', '', 8 + 8);
            $pdf->Cell(100, 7, $cmitmid, 1, 0, 'L');
            $pdf->SetXY($th_x + 3, $th_y + 32);
            $pdf->SetFont('Tahoma', 'B', 8 + 3);
            $pdf->Cell(30, 7, 'JOB NO.', 1, 0, 'L');
            $pdf->SetFont('Tahoma', '', 8 + 8);
            $pdf->Cell(100, 7, $cwo, 1, 0, 'L');

            $pdf->SetXY($th_x + 3, $th_y + 39);
            $pdf->SetFont('Tahoma', 'B', 8 + 3);
            $pdf->Cell(30, 5, 'PROD. DATE', 1, 0, 'L');
            $pdf->SetFont('Tahoma', '', 8 + 3);
            $pdf->Cell(12.5, 5, 'J', 1, 0, 'C');
            $pdf->Cell(12.5, 5, 'M', 1, 0, 'C');
            $pdf->Cell(12.5, 5, $yearCODE, 1, 0, 'C');
            $pdf->Cell(12.5, 5, $monthCODE, 1, 0, 'C');
            $pdf->Cell(12.5, 5, $cprodt[2][0], 1, 0, 'C');
            $pdf->Cell(12.5, 5, $cprodt[2][1], 1, 0, 'C');
            $pdf->Cell(12.5, 5, $clineNAME, 1, 0, 'C');
            $pdf->Cell(12.5, 5, $cprdshift, 1, 0, 'C');
            $pdf->SetXY($th_x + 3, $th_y + 44);
            $pdf->SetFont('Tahoma', 'B', 8 + 3);
            $pdf->Cell(30, 5, 'QTY', 1, 0, 'L');
            $pdf->SetFont('Tahoma', '', 8 + 3);
            $pdf->Cell(100, 5, number_format($cserqty), 1, 0, 'L');
            $pdf->SetXY($th_x + 3, $th_y + 49);
            $pdf->SetFont('Tahoma', 'B', 8 + 3);
            $pdf->Cell(30, 10, 'REMARK', 1, 0, 'L');
            $pdf->SetFont('Tahoma', '', 8 + 3);
            $pdf->Cell(100, 10, $cremark, 1, 0, 'L');
            $pdf->SetXY($th_x + 3, $th_y + 54);

            $noserencode = "Z1" . trim($cmitmid) . "&" . trim($crank) . "|Z7" . trim($cprdline) . "&" . $cprdshift . "|Z2" . $cwo . "|Z3" . number_format($cserqty, 0, '', '') . "|Z4" . trim($cmitmd1) . "|Z5" . $noseri . "|Z6";
            $image_name = $noserencode;
            $cmd = escapeshellcmd("Python d:\Apache24\htdocs\wms\smt.py \"$image_name\" 2 ");
            $op = shell_exec($cmd);
            $image_name = str_replace("/", "xxx", $image_name);
            $image_name = str_replace(" ", "___", $image_name);
            $image_name = str_replace("|", "lll", $image_name);
            $pdf->SetFont('Tahoma', '', 10);

            $pdf->Image(base_url("assets/imgs/" . $image_name . ".png"), $th_x + 57, $th_y + 60);
            $clebar = $pdf->GetStringWidth($noseri) + 20;
            $pdf->Code128($th_x + ($wid / 2) - ($clebar / 2), $th_y + ($hgt - 9), $noseri, $clebar, 5);

            $pdf->Rect($th_x + 1, $th_y + 1, $wid, $hgt);
            $clebar = $pdf->GetStringWidth($noseri);
            $pdf->Text($th_x + ($wid / 2) - ($clebar / 2), $th_y + 95, $noseri);
        }
        $pser = '';
        if (isset($_COOKIE["PRINTLABEL_FG"])) {
            $pser = $_COOKIE["PRINTLABEL_FG"];
        }
        if ($pser == '') {
            die('stop');
        } else {
            $wid = 135;
            $hgt = 95;
            $thegap = 5;
            $padX = 0.35;
            $padY = 0.35;
            $pprsize = $_COOKIE["PRINTLABEL_FG_SIZE"];
            $pser = str_replace(str_split('"[]'), '', $pser);
            $pser = explode(",", $pser);
            $rs = $this->SER_mod->selectBCField_in($pser);
            $pdf = new PDF_Code39e128('L', 'mm', $pprsize);
            $pdf->AddPage();
            $hgt_p = $pdf->GetPageHeight();
            $wid_p = $pdf->GetPageWidth();
            $pdf->SetAutoPageBreak(true, 10);
            //$pdf->SetMargins(0,0);
            $cY = 0;
            $cX = 0;
            foreach ($rs as $r) {
                $awo = explode('-', $r->SER_DOC);
                $ccustnm = $r->MCUS_CUSNM;
                $cuscd = trim($r->PDPP_BSGRP);
                $noseri = $r->SER_ID;
                $cmitmid = trim($r->SER_ITMID);
                $cmitmd1 = trim($r->MITM_ITMD1);
                $cprodt = explode('-', $r->SER_PRDDT);
                $cwo = $awo[0] . '-' . $awo[1];
                $cprdline = strpos(trim($r->SER_PRDLINE), 'SMT-') !== false ? substr(trim($r->SER_PRDLINE), 4, strlen(trim($r->SER_PRDLINE))) : trim($r->SER_PRDLINE);
                $cprdshift = $r->SER_PRDSHFT[0] == 'M' ? 'A' : 'B';
                $cserqty = $r->SER_QTY;
                $csersheet = $r->SER_SHEET;
                $cum = trim($r->MITM_STKUOM) == '' ? 'PCS' : trim($r->MITM_STKUOM);
                $crank = trim($r->MBOM_GRADE);
                $cremark = $r->SER_RMRK;
                //check wheter the height is enough
                if (($hgt_p - ($cY + $thegap)) < $hgt) {
                    $pdf->AddPage();
                    $cY = 0;
                    $cX = 0;
                    printTagstatus($pdf, $cX, $cY);
                    $cX += $wid + $thegap;
                } else {
                    if (($wid_p - $cX) > $wid) { // jika (lebar printer-posisi X)> lebar label
                        printTagstatus($pdf, $cX, $cY);
                        $cX += $wid + $thegap;
                    } else {
                        $cY += $hgt + $thegap;
                        if (($hgt_p - ($cY + $thegap)) < $hgt) {
                            $pdf->AddPage();
                            $cX = 0;
                            $cY = 0;
                            printTagstatus($pdf, $cX, $cY);
                        } else {
                            $cX = 0;
                            printTagstatus($pdf, $cX, $cY);
                        }
                        $cX += $wid + $thegap;
                    }
                }
            }
            $pdf->Output('I', 'FG LABEL ' . date("d-M-Y") . '.pdf');
        }
    }

    public function printrmlabel()
    {
        $currrtime = date('d/m/Y H:i:s');
        global $wid, $hgt, $padX, $padY, $noseri, $cmitmid, $cmitmsptno, $host, $cfristname, $c3n1, $v3n1, $cserqty, $currrtime, $c3n2, $v3n2, $c1p, $v1p, $cuserid, $crohs, $cmade;
        function fnLeftrm($pdf, $cleft, $pword)
        {
            global $wid, $hgt, $padX, $padY;
            if ($cleft > 0) {
                return $cleft + ($wid / 2 - ($pdf->GetStringWidth($pword) / 2));
            } else {
                return $wid / 2 - ($pdf->GetStringWidth($pword) / 2);
            }
        }
        function printTagrm($pdf, $myleft, $mytop)
        {
            global $wid, $hgt, $padX, $padY, $noseri, $cmitmid, $cmitmsptno, $host, $cfristname, $c3n1, $v3n1, $cserqty, $currrtime, $c3n2, $v3n2, $c1p, $v1p, $cuserid, $crohs, $cmade;
            $th_x = $padX + $myleft;
            $th_y = $padY + $mytop;
            $yads = 5;
            $xfSQ = 7;
            $gapcontent = 1;
            $gapcontent2 = 2;
            $pdf->Rect($th_x + 6, $th_y + 6, $wid, $hgt);
            $pdf->SetFont('Courier', '', 7);

            $pdf->Text($th_x + $xfSQ, $th_y + 3.5 + $yads, 'ITEM CODE : ' . trim($cmitmid) . '   ' . $host);
            $pdf->Text($th_x + $xfSQ, $th_y + 6.5 + $yads, 'QTY : ' . $cserqty);
            $pdf->Text($th_x + $xfSQ, $th_y + 9.5 + $yads, $v3n1);
            $clebar = $pdf->GetStringWidth(trim($c3n1)) + 10;
            $pdf->Code128($th_x + $xfSQ + 1, $th_y + 10.5 + $yads, $c3n1, $clebar, 5);

            $pdf->Text($th_x + $xfSQ, $th_y + 17 + $yads + $gapcontent, $v3n2);
            $clebar = $pdf->GetStringWidth(trim($c3n2)) + 10;
            $pdf->Code128($th_x + $xfSQ + 1, $th_y + 18 + $yads + $gapcontent, $c3n2, $clebar, 5);

            $pdf->Text($th_x + $xfSQ, $th_y + 24.5 + $yads + $gapcontent2, $v1p);
            $clebar = $pdf->GetStringWidth(trim($c1p)) + 10;
            $pdf->Code128($th_x + $xfSQ + 1, $th_y + 26 + $yads + $gapcontent2, $c1p, $clebar, 5);

            $pdf->Text($th_x + $xfSQ + 1, $th_y + 34 + $yads + $gapcontent2, 'PART NO : ' . $cmitmsptno);
            if ($crohs == '1') {
                $pdf->Text($th_x + $xfSQ + 1, $th_y + 44, 'RoHS Compliant');
            }
            $pdf->Text($th_x + $xfSQ + 40, $th_y + 44, 'C/O : Made in ' . trim($cmade));
            $pdf->Text($th_x + $xfSQ + 1, $th_y + 42 + $yads, $cuserid . " : " . $cfristname);
            $pdf->Text($th_x + 40 + $xfSQ, $th_y + 38 + $yads, $currrtime);
            $image_name = $noseri;
            $cmd = escapeshellcmd("Python d:\Apache24\htdocs\wms\smt.py \"$image_name\" ");
            $op = shell_exec($cmd);
            $image_name = str_replace("/", "xxx", $image_name);
            $image_name = str_replace(" ", "___", $image_name);
            $pdf->Image(base_url("assets/imgs/" . $image_name . ".png"), $th_x + 60 + $xfSQ, $th_y + 5 + $yads);
        }
        $pserial = '';
        if (isset($_COOKIE["PRINTLABEL_RM"])) {
            $pserial = $_COOKIE["PRINTLABEL_RM"];
        } else {
            exit('no data');
        }
        $wid = 68;
        $hgt = 48;
        $pprsize = $_COOKIE["PRINTLABEL_RM_SIZE"];
        $lbltype = $_COOKIE["PRINTLABEL_RM_LBLTYPE"];

        $host = gethostbyaddr($_SERVER['REMOTE_ADDR']);
        $pser = str_replace(str_split('"[]'), '', $pserial);
        $pser = explode(",", $pser);

        $rs = $this->SER_mod->selectBCFieldRM_in($pser);
        //echo json_encode($rs);
        $pdf = new PDF_Code39e128('L', 'mm', array(70, 50));
        if ($lbltype == '1') {
            $pdf = new PDF_Code39e128('L', 'mm', $pprsize);
            $pdf->AddPage();
            $hgt_p = $pdf->GetPageHeight();
            $wid_p = $pdf->GetPageWidth();
            $pdf->SetAutoPageBreak(true, 10);
            $cY = 0;
            $cX = 0;
            $thegap = 8;
            foreach ($rs as $r) {

                $v3n1 = '(3N1) ' . trim($r->SER_ITMID);
                $c3n1 = '3N1' . trim($r->SER_ITMID);
                $v3n2 = '(3N2) ' . number_format($r->SER_QTY) . " " . trim($r->SER_LOTNO);
                $c3n2 = '3N2 ' . number_format($r->SER_QTY, 0, "", "") . " " . trim($r->SER_LOTNO);
                $v1p = '(1P) ' . trim($r->MITM_SPTNO);
                $c1p = '1P' . trim($r->MITM_SPTNO);
                $noseri = $r->SER_ID;
                $cserqty = number_format($r->SER_QTY);
                $cmitmid = $r->SER_ITMID;
                $cmitmsptno = trim($r->MITM_SPTNO);
                $cfristname = $r->MSTEMP_FNM;
                $cuserid = $r->SER_USRID;
                $crohs = $r->SER_ROHS;
                $cmade = $r->MMADE_NM;
                if (($hgt_p - ($cY + $thegap)) < $hgt) {
                    $pdf->AddPage();
                    $cY = 0;
                    $cX = 0;
                    printTagrm($pdf, $cX, $cY);
                    $cX += $wid + $thegap;
                } else {
                    if (($wid_p - $cX) > ($wid + 4)) { // jika (lebar printer-posisi X)> lebar label
                        printTagrm($pdf, $cX, $cY);
                        $cX += $wid + $thegap;
                    } else {
                        $cY += $hgt + $thegap;
                        if (($hgt_p - ($cY + $thegap)) < $hgt) {
                            $pdf->AddPage();
                            $cX = 0;
                            $cY = 0;
                            printTagrm($pdf, $cX, $cY);
                        } else {
                            $cX = 0;
                            printTagrm($pdf, $cX, $cY);
                        }
                        $cX += $wid + $thegap;
                    }
                }
            }
        } else {
            $pdf->SetAutoPageBreak(true, 1);
            $pdf->SetMargins(0, 0);
            foreach ($rs as $r) {
                $image_name = $r->SER_ID;
                $cmd = escapeshellcmd("Python d:\Apache24\htdocs\wms\smt.py \"$image_name\" ");
                $op = shell_exec($cmd);
                $image_name = str_replace("/", "xxx", $image_name);
                $image_name = str_replace(" ", "___", $image_name);

                $v3n1 = '(3N1) ' . trim($r->SER_ITMID);
                $c3n1 = '3N1' . trim($r->SER_ITMID);
                $v3n2 = '(3N2) ' . number_format($r->SER_QTY) . " " . trim($r->SER_LOTNO);
                $c3n2 = '3N2 ' . number_format($r->SER_QTY, 0, "", "") . " " . trim($r->SER_LOTNO);
                $v1p = '(1P) ' . trim($r->MITM_SPTNO);
                $c1p = '1P' . trim($r->MITM_SPTNO);

                $pdf->AddPage();
                $pdf->SetY(0);
                $pdf->SetX(0);
                $pdf->SetFont('Courier', '', 7);
                $clebar = $pdf->GetStringWidth($c3n1) + 10;
                $pdf->Text(2, 3.5, 'ITEM CODE : ' . $r->SER_ITMID . '   ' . $host);
                $pdf->Text(2, 6.5, 'QTY : ' . number_format($r->SER_QTY));
                $pdf->Text(2, 9.5, $v3n1);
                // if(strpos($c3n1,"_")){
                $pdf->Code128(2, 10.5, $c3n1, $clebar, 5);
                // } else {
                //     $pdf->Code39(2, 10.5,$c3n1);
                // }

                $clebar = $pdf->GetStringWidth($c3n2) + 10;
                $pdf->Text(2, 18, $v3n2);
                // if(strpos($c3n1,"_")){
                $pdf->Code128(2, 19, $c3n2, $clebar, 5);
                // } else {
                //     $pdf->Code39(2, 19,$c3n2);
                // }
                $clebar = $pdf->GetStringWidth($c1p) + 10;
                $pdf->Text(2, 26, $v1p);
                $pdf->Code128(2, 27, $c1p, $clebar, 4);
                $pdf->Text(2, 33, 'PART NO : ' . trim($r->MITM_SPTNO));
                if ($r->SER_ROHS == '1') {
                    $pdf->Text(2, 36, 'RoHS Compliant');
                }
                $pdf->Text(40, 36, 'C/O : Made in ' . trim($r->MMADE_NM));
                $pdf->Text(2, 38, $r->SER_USRID . " : " . $r->MSTEMP_FNM);
                $pdf->Text(40, 38, $currrtime);

                $pdf->Image(base_url("assets/imgs/" . $image_name . ".png"), 60, 3);
            }
        }
        $pdf->Output('I', 'LBL-IN-DO ' . date("d-M-Y") . '.pdf');
    }

    public function gettodaylist_infg()
    {
        header('Content-Type: application/json');
        $currdt = date('Y-m-d');
        $rs = $this->ITH_mod->selectAll_by(array('convert(date,ITH_LUPDT)' => $currdt, 'ITH_FORM' => 'INC'));
        echo json_encode($rs);
    }

    public function release_penfg()
    {
        header('Content-Type: application/json');
        $currdt = date('Y-m-d');
        $currdt_ptn = date('Ymd');
        $currdt_time = date('Y-m-d H:i:s');
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
        if (count($rspending_scannedqty) > 0) {
            //#1 SET OUT SERIAL FROM ITH
            $datas = [
                'ITH_ITMCD' => $citmcd, 'ITH_DATE' => $currdt, 'ITH_FORM' => 'OUT-PEN-FG-ADJ', 'ITH_DOC' => $cpenddoc, 'ITH_QTY' => -$cpendqty, 'ITH_WH' => 'QAFG',
                'ITH_SER' => $cserid, 'ITH_LUPDT' => $currdt_time, 'ITH_USRID' => $this->session->userdata('nama'),
            ];
            $resITH = $this->ITH_mod->insert_rls($datas);
            if ($resITH > 0) {
                //#2 CREATE NEW SERIAL
                $cmdl = 1;
                $cproddt = '';
                $cjob = '';
                $csheet = '';
                $cline = '';
                $cshift = '';
                $rsser = $this->SER_mod->select_exact_byVAR(array('SER_ID' => $cserid));
                foreach ($rsser as $r) {
                    $cproddt = $r['SER_PRDDT'];
                    $cjob = $r['SER_DOC'];
                    $csheet = $r['SER_SHEET'];
                    $cline = $r['SER_PRDLINE'];
                    $cshift = $r['SER_PRDSHFT'];
                }
                $pYear = substr($cproddt, 2, 2);
                $pMonth = substr($cproddt, 5, 2);
                $pDay = substr($cproddt, -2);
                $pMonthdis = $this->getMonthDis($pMonth);
                $newid = $this->SER_mod->lastserialid($cmdl, $cproddt);
                $newid++;
                $newid = $cmdl . $pYear . $pMonthdis . $pDay . substr('000' . $newid, -4);
                $datas2 = [
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
                    'SER_USRID' => $this->session->userdata('nama'),
                ];
                $resSER = $this->SER_mod->insert($datas2);
                if ($resSER > 0) {
                    //#3 update current serial
                    $data_u_ser = ['SER_QTY' => ($cpendqty - $crelqty)];
                    $resSER_u = $this->SER_mod->updatebyId($data_u_ser, $cserid);
                    if ($resSER_u > 0) {
                        //#4 add to release doc
                        if (trim($creleasedoc) == '') {
                            $mlastid = $this->RLS_mod->lastserialidser();
                            $mlastid++;
                            $creleasedoc = 'RLSS' . $currdt_ptn . $mlastid;
                        }
                        $datas_rls = [
                            'RLSSER_DOC' => $creleasedoc,
                            'RLSSER_SER' => $newid,
                            'RLSSER_DT' => $ctgl,
                            'RLSSER_QTY' => $crelqty,
                            'RLSSER_REFFDOC' => $cpenddoc,
                            'RLSSER_REFFSER' => $cserid,
                            'RLSSER_LUPDT' => $currdt_time,
                            'RLSSER_USRID' => $this->session->userdata('nama'),
                        ];
                        $resRLS = $this->RLS_mod->insert($datas_rls);
                        if ($resRLS > 0) {
                            //#5 insert into ITH
                            $datas_final_ith = [
                                'ITH_ITMCD' => $citmcd,
                                'ITH_DATE' => $currdt,
                                'ITH_FORM' => 'IN-PEN-FG-ADJ',
                                'ITH_DOC' => $cpenddoc,
                                'ITH_QTY' => ($cpendqty - $crelqty),
                                'ITH_WH' => 'QAFG',
                                'ITH_SER' => $cserid,
                                'ITH_LUPDT' => $currdt_time,
                                'ITH_USRID' => $this->session->userdata('nama'),
                            ];
                            $resITH1 = $this->ITH_mod->insert_rls($datas_final_ith);
                            $datas_final_ith = [
                                'ITH_ITMCD' => $citmcd,
                                'ITH_DATE' => $currdt,
                                'ITH_FORM' => 'IN-PEN-FG-ADJ',
                                'ITH_DOC' => $cpenddoc,
                                'ITH_QTY' => $crelqty,
                                'ITH_WH' => 'QAFG',
                                'ITH_SER' => $newid,
                                'ITH_REMARK' => $creleasedoc,
                                'ITH_LUPDT' => $currdt_time,
                                'ITH_USRID' => $this->session->userdata('nama'),
                            ];
                            $resITH2 = $this->ITH_mod->insert_rls($datas_final_ith);
                            if (($resITH1 + $resITH2) >= 2) {
                                $datar = array("cd" => '1', "msg" => "OK", "reffdoc" => $creleasedoc, "reffser" => $newid);
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

    public function release_penfg1()
    {
        header('Content-Type: application/json');
        $currdt = date('Y-m-d');
        $currdt_ptn = date('Ymd');
        $currdt_time = date('Y-m-d H:i:s');
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
        if (count($rspending_scannedqty) > 0) {
            //#1 SET OUT SERIAL FROM ITH
            $datas = array(
                'ITH_ITMCD' => $citmcd, 'ITH_DATE' => $currdt, 'ITH_FORM' => 'OUT-PEN-FG-ADJ', 'ITH_DOC' => $cpenddoc, 'ITH_QTY' => -$cpendqty, 'ITH_WH' => 'QAFG',
                'ITH_SER' => $cserid, 'ITH_LUPDT' => $currdt_time, 'ITH_USRID' => $this->session->userdata('nama'),
            );
            $resITH = $this->ITH_mod->insert_rls($datas);
            if ($resITH > 0) {
                //#2 CREATE NEW SERIAL
                $newid = '';
                if (strpos($cseridnew, "|")) {
                    //#2.1 HANDLE REGULAR PART
                    $anewserid = explode("|", $cseridnew);
                    if (count($anewserid) == 7) {
                        if (substr($anewserid[5], 0, 2) == "Z5") {
                            $tempid = substr($anewserid[5], 2, strlen($anewserid[5]));
                            if ($this->SER_mod->check_Primary(array("SER_ID" => $tempid)) == 0) {
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
                    if (strlen($cseridnew) == 16) {
                        $tempid = $cseridnew;
                        if ($this->SER_mod->check_Primary(array("SER_ID" => $tempid)) == 0) {
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
                foreach ($rsser as $r) {
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
                    'SER_USRID' => $this->session->userdata('nama'),
                );
                $resSER = $this->SER_mod->insert($datas2);
                if ($resSER > 0) {
                    //#3 update current serial
                    $data_u_ser = array('SER_QTY' => ($cpendqty - $crelqty));
                    $resSER_u = $this->SER_mod->updatebyId($data_u_ser, $cserid);
                    if ($resSER_u > 0) {
                        //#4 add to release doc
                        if (trim($creleasedoc) == '') {
                            $mlastid = $this->RLS_mod->lastserialidser();
                            $mlastid++;
                            $creleasedoc = 'RLSS' . $currdt_ptn . $mlastid;
                        }
                        $datas_rls = array(
                            'RLSSER_DOC' => $creleasedoc,
                            'RLSSER_SER' => $newid,
                            'RLSSER_DT' => $ctgl,
                            'RLSSER_QTY' => $crelqty,
                            'RLSSER_REFFDOC' => $cpenddoc,
                            'RLSSER_REFFSER' => $cserid,
                            'RLSSER_LUPDT' => $currdt_time,
                            'RLSSER_USRID' => $this->session->userdata('nama'),
                        );
                        $resRLS = $this->RLS_mod->insert($datas_rls);
                        if ($resRLS > 0) {
                            //#5 insert into ITH
                            $datas_final_ith = array(
                                'ITH_ITMCD' => $citmcd,
                                'ITH_DATE' => $currdt,
                                'ITH_FORM' => 'IN-PEN-FG-ADJ',
                                'ITH_DOC' => $cpenddoc,
                                'ITH_QTY' => ($cpendqty - $crelqty),
                                'ITH_WH' => 'QAFG',
                                'ITH_SER' => $cserid,
                                'ITH_LUPDT' => $currdt_time,
                                'ITH_USRID' => $this->session->userdata('nama'),
                            );
                            $resITH1 = $this->ITH_mod->insert_rls($datas_final_ith);
                            $datas_final_ith = array(
                                'ITH_ITMCD' => $citmcd,
                                'ITH_DATE' => $currdt,
                                'ITH_FORM' => 'IN-PEN-FG-ADJ',
                                'ITH_DOC' => $cpenddoc,
                                'ITH_QTY' => $crelqty,
                                'ITH_WH' => 'QAFG',
                                'ITH_SER' => $newid,
                                'ITH_REMARK' => $creleasedoc,
                                'ITH_LUPDT' => $currdt_time,
                                'ITH_USRID' => $this->session->userdata('nama'),
                            );
                            $resITH2 = $this->ITH_mod->insert_rls($datas_final_ith);
                            if (($resITH1 + $resITH2) >= 2) {
                                $datar = ["cd" => '1', "msg" => "OK", "reffdoc" => $creleasedoc, "reffser" => $newid];
                            } else {
                                $datar = ["cd" => '0', "msg" => "Could not add data back to transaction"];
                            }
                        } else {
                            $datar = ["cd" => '0', "msg" => "Could not add document of release Serial FG"];
                        }
                    } else {
                        $datar = ["cd" => '0', "msg" => "Could not update data current Serial FG"];
                    }
                } else {
                    $datar = ["cd" => '0', "msg" => "Could not set add new Serial FG"];
                }
            } else {
                $datar = ["cd" => '0', "msg" => "Could not set OUT Serial FG"];
            }
        } else {
            $datar = ["cd" => '0', "msg" => "The serial have not been scanned yet"];
        }
        $myar[] = $datar;
        echo json_encode($myar);
    }

    public function properties_c_rank()
    {
        header('Content-Type: application/json');
        $cold_reff = $this->input->get('inid');
        $rsold_reff = $this->SER_mod->select_exact_byVAR(['SER_ID' => $cold_reff]);
        $myar = [];
        $myar[] = count($rsold_reff) > 0 ? ["cd" => '1', "msg" => "go ahead"] : ["cd" => '0', "msg" => "Reff No is not found"];
        die(json_encode(['data' => $rsold_reff, 'status' => $myar]));
    }

    public function getproperties_n_tx()
    {
        header('Content-Type: application/json');
        $cold_reff = $this->input->get('inid');
        $rsold_reff = $this->SER_mod->select_exact_byVAR(['SER_ID' => $cold_reff]);
        $rxtx = $this->ITH_mod->selectAll_by(['ITH_SER' => $cold_reff]);
        $myar = [];
        if (count($rsold_reff) > 0) {
            if ($this->ITH_mod->check_Primary(['ITH_SER' => $cold_reff, 'ITH_FORM' => 'OUT-SHP-FG']) > 0) {
                $myar[] = ["cd" => '0', "msg" => "could not split/relable delivered item label"];
            } else {
                $myar[] = ["cd" => '1', "msg" => "go ahead"];
            }
        } else {
            $myar[] = ["cd" => '0', "msg" => "Reff No is not found"];
        }
        echo '{"data":'
        . json_encode($rsold_reff)
        . ',"tx":' . json_encode($rxtx)
        . ',"status":' . json_encode($myar)
            . '}';
    }
    public function getproperties_n_tx_statuslbl()
    {
        header('Content-Type: application/json');
        $cold_reff = $this->input->get('inid');
        $rsold_reff = $this->SER_mod->select_exact_byVAR(['SER_ID' => $cold_reff]);
        $rxtx = $this->ITH_mod->selectAll_by(['ITH_SER' => $cold_reff]);
        $myar = [];
        if (count($rsold_reff) > 0) {
            if ($this->ITH_mod->check_Primary(['ITH_FORM' => 'OUT-USE', 'ITH_SER' => $cold_reff]) > 0) {
                $datar = ["cd" => '0', "msg" => "could not split used label"];
            } else {
                $datar = ["cd" => '1', "msg" => "go ahead"];
            }
        } else {
            $datar = ["cd" => '0', "msg" => "Reff No is not found"];
        }
        $myar[] = $datar;
        echo '{"data":';
        echo json_encode($rsold_reff);
        echo ',"tx":' . json_encode($rxtx);
        echo ',"status":' . json_encode($myar);
        echo '}';
    }

    public function getproperties_n_tx_splitplant1()
    {
        header('Content-Type: application/json');
        $myar = [];
        $cold_reff = $this->input->get('inid');
        $rsold_reff = $this->SER_mod->select_exact_byVAR(['SER_ID' => $cold_reff]);
        foreach ($rsold_reff as $r) {
            // if($r['PDPP_BSGRP']!='PSI1PPZIEP'){
            if ($r['DOCTYPE'] == '1') {
                $datar = array("cd" => "0", "msg" => "Please split the label on Plant 2 Menu");
                array_push($myar, $datar);
                exit('{"status":' . json_encode($myar) . '}');
            }
        }
        $rslastwh = $this->ITH_mod->selectstock_ser($cold_reff);
        $rxtx = $this->ITH_mod->selectAll_by(array('ITH_SER' => $cold_reff));
        if (count($rsold_reff) > 0) {

            if ($this->SISCN_mod->check_Primary(array('SISCN_SER' => $cold_reff)) > 0) {
                $datar = ["cd" => '0', "msg" => "could not split delivered item label"];
            } else {
                $qty = '';
                foreach ($rsold_reff as $r) {
                    $qty = $r['SER_QTY'];
                }
                if (intval($qty) > 0) {
                    #check is area at QAFG (PENDING)
                    $rsactive = $this->ITH_mod->select_active_ser($cold_reff);
                    $rsactive_wh = '';
                    foreach ($rsactive as $r) {
                        $rsactive_wh = trim($r['ITH_WH']);
                    }
                    if ($rsactive_wh == 'QAFG') {
                        $datar = ["cd" => '0', "msg" => "could not split using this menu, ask MR. H or Mr. Z"];
                    } else {
                        $datar = ["cd" => '1', "msg" => "go ahead"];
                    }
                } else {
                    $datar = ["cd" => '0', "msg" => "the label might be already splited"];
                }
            }
        } else {
            $datar = ["cd" => '0', "msg" => "Reff No is not found"];
        }
        array_push($myar, $datar);
        echo '{"data":';
        echo json_encode($rsold_reff);
        echo ',"tx":' . json_encode($rxtx);
        echo ',"status":' . json_encode($myar);
        echo ',"lastwh":' . json_encode($rslastwh);
        echo '}';
    }

    public function validate_newreff()
    {
        header('Content-Type: application/json');
        $crawtext = $this->input->get('inrawtext');
        $cremark = $this->input->get('inremark');
        $citmcd = $this->input->get('initemcd');
        $ctypefg = $this->input->get('intypefg');
        $clot = $this->input->get('inlot');
        $cqty = $this->input->get('inqty');
        $myar = [];
        if (strpos($crawtext, "|") !== false) { /// regular item
            $araw = explode("|", $crawtext);
            $newitemcd = substr($araw[0], 2, strlen($araw[0]) - 2) . $cremark;
            $newreff = substr($araw[5], 2, strlen($araw[5]) - 2);

            if ($this->MSTITM_mod->check_Primary(['MITM_ITMCD' => $newitemcd]) == 0) {
                $myar[] = ["cd" => "0", "msg" => "Item Code is not registered [$newitemcd]"];
                exit('{"status":' . json_encode($myar) . '}');
            }

            if (strlen($newreff) != 16) {
                $myar[] = ["cd" => "0", "msg" => "New reff number is not valid"];
                die(json_encode(['status' => $myar]));
            }

            if ($this->SER_mod->check_Primary(array("SER_ID" => $newreff)) > 0) {
                $myar[] = ["cd" => "0", "msg" => "the reff no is already registered"];
                exit('{"status":' . json_encode($myar) . '}');
            } else {

                $myar[] = ["cd" => "1", "msg" => "go ahead", "rawtext" => $crawtext];
                exit('{"status":' . json_encode($myar) . '}');
            }
        } else { /// HANDLE NON REGULAR
            $newitmcd = $citmcd . $ctypefg . $cremark;
            if ($this->MSTITM_mod->check_Primary(['MITM_ITMCD' => $newitmcd]) == 0) {
                $myar[] = ["cd" => "0", "msg" => "Item Code is not registered [$newitmcd]"];
                exit('{"status":' . json_encode($myar) . '}');
            }
            if ($this->SER_mod->check_Primary(array("SER_ID" => $crawtext)) > 0) {
                $myar[] = ["cd" => "0", "msg" => "the reff no is already registered"];
                exit('{"status":' . json_encode($myar) . '}');
            } else {
                $myar[] = [
                    "cd" => "1", "msg" => "go ahead", "reffno" => $crawtext, "typefg" => $ctypefg, "assycode" => $newitmcd, "lot" => $clot, "qty" => $cqty, "remark" => $cremark,
                ];
                exit('{"status":' . json_encode($myar) . '}');
            }
        }
    }
    public function validate_newreff2()
    {
        header('Content-Type: application/json');
        $crawtext = $this->input->get('inrawtext2');

        $myar = [];
        if (strpos($crawtext, "|") !== false) { /// regular item
            $araw = explode("|", $crawtext);
            $newreff = substr($araw[5], 2, strlen($araw[5]) - 2);

            if ($this->SER_mod->check_Primary(["SER_ID" => $newreff]) > 0) {
                $myar[] = ["cd" => "0", "msg" => "the reff no is already registered"];
                exit('{"status":' . json_encode($myar) . '}');
            } else {
                $myar[] = ["cd" => "1", "msg" => "go ahead", "rawtext" => $crawtext];
                exit('{"status":' . json_encode($myar) . '}');
            }
        } else { /// HANDLE NON REGULAR
            $myar[] = ["cd" => "0", "msg" => "the process is under construction ._."];
            exit('{"status":' . json_encode($myar) . '}');
        }
    }

    public function test_prc_splitplant1()
    {
        header('Content-Type: application/json');
        $this->checkSession();
        $currrtime = date('Y-m-d H:i:s');
        $currdate = date('Y-m-d');
        $myar = array();
        $coldreff = $this->input->post('inoldreff');
        $colditem = $this->input->post('inolditem');
        $coldqty = $this->input->post('inoldqty');
        $coldqty = intval(str_replace(',', '', $coldqty));
        $coldjob = $this->input->post('inoldjob');

        $ca_reff = $this->input->post('ina_reff');
        $ca_itmcd = $this->input->post('ina_itmcd');
        $ca_lot = $this->input->post('ina_lot');
        $ca_qty = $this->input->post('ina_qty');
        $ca_ok = $this->input->post('ina_isok');
        $ca_remark = $this->input->post('ina_remark');
        $ca_rawtxt = $this->input->post('ina_rawtxt');
        $ca_count = count($ca_reff);

        $CUSERID = $this->session->userdata('nama');

        if ($this->SER_mod->check_Primary(['SER_ID' => $coldreff]) == 0) {
            $myar[] = ["cd" => '0', "msg" => "Old Label not found {$coldreff} or may be already splitted"];
            exit('{"status":' . json_encode($myar) . '}');
        }

        if ($this->SER_mod->check_Primary(['SER_ID' => $coldreff, 'SER_QTY >0' => null]) == 0) {
            $myar[] = ["cd" => '0', "msg" => "qty old label = 0"];
            exit('{"status":' . json_encode($myar) . '}');
        }

        //get last warehouse , location ....
        if ($this->SISCN_mod->check_Primary(['SISCN_SER' => $coldreff]) > 0) {
            $myar[] = ["cd" => '0', "msg" => "could not split delivered item label"];
            exit('{"status":' . json_encode($myar) . '}');
        } else {
            $rsactive = $this->ITH_mod->select_active_ser($coldreff);
            $rsser = $this->SER_mod->selectbyVAR(['SER_ID' => $coldreff]);
            $bsgrp = '';
            foreach ($rsser as $r) {
                $bsgrp = $r['SER_BSGRP'];
                break;
            }
            $rsactive_wh = $rsactive_loc = $rsactive_time = $rsactive_form = $rsactive_date = '';
            foreach ($rsactive as $r) {
                $rsactive_wh = trim($r['ITH_WH']);
                $rsactive_loc = trim($r['ITH_LOC']);
                $rsactive_date = trim($r['ITH_DATE']);
                $rsactive_time = trim($r['ITH_LUPDT']);
                $rsactive_form = trim($r['ITH_FORM']);
            }
            if ($rsactive_wh == 'QAFG') {
                $myar[] = ["cd" => '0', "msg" => "could not split using this menu, ask MR. H or Mr. Z"]; //07 OKT 2020
                exit('{"status":' . json_encode($myar) . '}');
            }
            // if($this->SER_mod->updatebyId(["SER_QTY" => 0, "SER_LUPDT" => $currrtime, "SER_USRID" => $CUSERID, "SER_CAT" => NULL ], $coldreff) >0){
            //     $this->SERD_mod->deletebyID_label(['SERD2_SER' => $coldreff]);
            $ser_insert_ok = 0;
            $test_newreff = [];
            for ($i = 0; $i < $ca_count; $i++) {
                $preparedStatement = [
                    "SER_ID" => $ca_reff[$i],
                    "SER_DOC" => $coldjob,
                    "SER_ITMID" => strtoupper($ca_itmcd[$i]),
                    "SER_QTY" => $ca_qty[$i],
                    "SER_LOTNO" => $ca_lot[$i],
                    "SER_REFNO" => $coldreff,
                    "SER_RAWTXT" => $ca_rawtxt[$i],
                    "SER_GORNG" => $ca_ok[$i],
                    "SER_LUPDT" => $currrtime,
                    "SER_BSGRP" => ($rsactive_wh == 'AFWH3') ? null : $bsgrp,
                    "SER_USRID" => $this->session->userdata('nama'),
                ];
                if ($ca_ok[$i] === '0') {
                    $preparedStatement['SER_CAT'] = '2';
                    $preparedStatement['SER_RMRK'] = $ca_remark[$i];
                }
                $test_newreff[] = $preparedStatement;
                // $ser_insert_ok += $this->SER_mod->insert($preparedStatement);
            }
            // if($ser_insert_ok==$ca_count){
            if (empty($rsactive)) {
                $myar[] = ["cd" => "1", "msg" => "ok ok"];
                exit('{"status":' . json_encode($myar) . '}');
            }
            if ($rsactive_form == "INC-QA-FG" && $rsactive_wh == "ARQA1") {
                echo 'lokasi arqa1';
            } elseif ($rsactive_wh == "ARPRD1") {
                echo 'lokasi arprd1';
            } elseif ($rsactive_wh == "AFWH3") {
                #insert minus transaction
                #need to separate row [split,split-convert]
                $_tmp = [
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
                    "ITH_USRID" => $CUSERID,
                ];
                $ret_min = 0; # $this->ITH_mod->insert($_tmp);
                #enhance out
                $uniqueAssy = array_values(array_unique($ca_itmcd));
                $test_out_tx = [];
                foreach ($uniqueAssy as $u) {
                    for ($i = 0; $i < $ca_count; $i++) {
                        if (strtoupper($u) === strtoupper($ca_itmcd[$i])) {
                            $isfound = false;
                            foreach ($test_out_tx as &$s) {
                                if ($s['ITH_ITMCD'] === strtoupper($u)) {
                                    $s['ITH_QTY'] -= $ca_qty[$i];
                                    $isfound = true;
                                    break;
                                }
                            }
                            unset($s);

                            if (!$isfound) {
                                $test_out_tx[] = [
                                    "ITH_ITMCD" => strtoupper($u),
                                    "ITH_DATE" => $currdate,
                                    "ITH_FORM" => strtoupper($u) === strtoupper($colditem) ? 'SPLIT-FG-LBL' : 'SPLIT-CNV-FG-OUT',
                                    "ITH_DOC" => $coldjob,
                                    "ITH_QTY" => -$ca_qty[$i],
                                    "ITH_SER" => $coldreff,
                                    "ITH_WH" => $rsactive_wh,
                                    "ITH_LOC" => $rsactive_loc,
                                    "ITH_LUPDT" => $currrtime,
                                    "ITH_REMARK" => "WIL-SPLIT",
                                    "ITH_USRID" => $CUSERID,
                                ];
                            }
                        }
                    }
                }
                #end
                if ($ret_min > 0) {
                    $ith_insert_ok = 0;
                    for ($i = 0; $i < $ca_count; $i++) {
                        if ((strpos(strtoupper($colditem), 'KD') !== false ||
                            strpos(strtoupper($colditem), 'ASP') !== false
                        ) && (!strpos(strtoupper($ca_itmcd[$i]), 'KD') !== false &&
                            !strpos(strtoupper($ca_itmcd[$i]), 'ASP') !== false
                        )
                        ) {
                            //echo "TRANSFER TO PRD";
                            // $ith_insert_ok += $this->ITH_mod->insert(
                            //     [
                            //         "ITH_ITMCD" => strtoupper($ca_itmcd[$i]),
                            //         "ITH_DATE" => $currdate,
                            //         "ITH_FORM" => 'SPLIT-FG-LBL',
                            //         "ITH_DOC" => $coldjob,
                            //         "ITH_QTY" => $ca_qty[$i],
                            //         "ITH_SER" => $ca_reff[$i],
                            //         "ITH_WH" => 'ARPRD1',
                            //         "ITH_LOC" => 'TEMP',
                            //         "ITH_LUPDT" => $currrtime,
                            //         "ITH_REMARK" => "AFT-SPLIT",
                            //         "ITH_USRID" => $CUSERID
                            //     ]
                            // );
                        } else {
                            if ((!strpos(strtoupper($colditem), 'KD') !== false &&
                                !strpos(strtoupper($colditem), 'ASP') !== false
                            ) && (strpos(strtoupper($ca_itmcd[$i]), 'KD') !== false ||
                                strpos(strtoupper($ca_itmcd[$i]), 'ASP') !== false
                            )
                            ) {
                                // $ith_insert_ok += $this->ITH_mod->insert(
                                //     [
                                //         "ITH_ITMCD" => strtoupper($ca_itmcd[$i]),
                                //         "ITH_DATE" => $currdate,
                                //         "ITH_FORM" => 'SPLIT-FG-LBL',
                                //         "ITH_DOC" => $coldjob,
                                //         "ITH_QTY" => $ca_qty[$i],
                                //         "ITH_SER" => $ca_reff[$i],
                                //         "ITH_WH" => 'ARPRD1',
                                //         "ITH_LOC" => 'TEMP',
                                //         "ITH_LUPDT" => $currrtime,
                                //         "ITH_REMARK" => "AFT-SPLIT",
                                //         "ITH_USRID" => $CUSERID
                                //     ]
                                // );
                            } else {
                                //TO SAME WAREHOUSE
                                // $ith_insert_ok += $this->ITH_mod->insert(
                                //     [
                                //         "ITH_ITMCD" => strtoupper($ca_itmcd[$i]),
                                //         "ITH_DATE" => $currdate,
                                //         "ITH_FORM" => 'SPLIT-FG-LBL',
                                //         "ITH_DOC" => $coldjob,
                                //         "ITH_QTY" => $ca_qty[$i],
                                //         "ITH_SER" => $ca_reff[$i],
                                //         "ITH_WH" => $rsactive_wh,
                                //         "ITH_LOC" => $rsactive_loc,
                                //         "ITH_LUPDT" => $currrtime,
                                //         "ITH_REMARK" => "AFT-SPLIT",
                                //         "ITH_USRID" => $CUSERID
                                //     ]
                                // );
                            }
                        }
                    }
                    if ($ith_insert_ok == $ca_count) {
                        $myar[] = ["cd" => "1", "msg" => "ok .."];
                        exit('{"status":' . json_encode($myar) . '}');
                    } else {
                        $myar[] = ["cd" => "0", "msg" => "Not All label add to transaction FG, please contact Admin"];
                        exit('{"status":' . json_encode($myar) . '}');
                    }
                } else {
                    $myar[] = ["cd" => "0", "msg" => "Could not minus transaction FG, please contact Admin"];
                    die(json_encode(['status' => $myar, '$test_newreff' => $test_newreff, '$test_out_tx' => $test_out_tx]));
                }
            } else {
                $myar[] = ["cd" => "0", "msg" => "Could not process to transaction, please contact Admin"];
                die(json_encode(['status' => $myar, '$test_newreff' => $test_newreff]));
            }
            // } else {
            //     $myar[] = ["cd" => "0", "msg" => "Not All label registered, please contact Admin"];
            //     die(json_encode(['status' => $myar, '$test_newreff' => $test_newreff]));
            // }
            // } else {
            //     $myar[] = ["cd" => "0", "msg" => "Could not update old reff data "];
            //     exit('{"status":'.json_encode($myar).'}');
            // }
        }
    }

    public function prc_splitplant1()
    {
        $this->checkSession();
        $currrtime = date('Y-m-d H:i:s');
        $currdate = date('Y-m-d');
        $myar = [];
        $coldreff = $this->input->post('inoldreff');
        $colditem = $this->input->post('inolditem');
        $coldqty = $this->input->post('inoldqty');
        $coldqty = intval(str_replace(',', '', $coldqty));
        $coldjob = $this->input->post('inoldjob');

        $ca_reff = $this->input->post('ina_reff');
        $ca_itmcd = $this->input->post('ina_itmcd');
        $ca_lot = $this->input->post('ina_lot');
        $ca_qty = $this->input->post('ina_qty');
        $ca_ok = $this->input->post('ina_isok');
        $ca_remark = $this->input->post('ina_remark');
        $ca_rawtxt = $this->input->post('ina_rawtxt');
        $ca_count = count($ca_reff);

        $CUSERID = $this->session->userdata('nama');

        if ($this->SER_mod->check_Primary(['SER_ID' => $coldreff]) == 0) {
            $myar[] = ["cd" => '0', "msg" => "Old Label not found {$coldreff} or may be already splitted"];
            exit('{"status":' . json_encode($myar) . '}');
        }

        if ($this->SER_mod->check_Primary(['SER_ID' => $coldreff, 'SER_QTY >0' => null]) == 0) {
            $myar[] = ["cd" => '0', "msg" => "qty old label = 0"];
            exit('{"status":' . json_encode($myar) . '}');
        }

        //get last warehouse , location ....
        if ($this->SISCN_mod->check_Primary(['SISCN_SER' => $coldreff]) > 0) {
            $myar[] = ["cd" => '0', "msg" => "could not split delivered item label"];
            exit('{"status":' . json_encode($myar) . '}');
        } else {
            $rsactive = $this->ITH_mod->select_active_ser($coldreff);
            $rsser = $this->SER_mod->selectbyVAR(['SER_ID' => $coldreff]);
            $bsgrp = '';
            foreach ($rsser as $r) {
                $bsgrp = $r['SER_BSGRP'];
                break;
            }
            $rsactive_wh = $rsactive_loc = $rsactive_time = $rsactive_form = $rsactive_date = '';
            foreach ($rsactive as $r) {
                $rsactive_wh = trim($r['ITH_WH']);
                $rsactive_loc = trim($r['ITH_LOC']);
                $rsactive_date = trim($r['ITH_DATE']);
                $rsactive_time = trim($r['ITH_LUPDT']);
                $rsactive_form = trim($r['ITH_FORM']);
            }
            if ($rsactive_wh == 'QAFG') {
                $myar[] = ["cd" => '0', "msg" => "could not split using this menu, ask MR. H or Mr. Z"]; //07 OKT 2020
                exit('{"status":' . json_encode($myar) . '}');
            }

            if ($rsactive_wh == "ARPRD1") {
                for ($i = 0; $i < $ca_count; $i++) {
                    if (strtoupper($colditem) != strtoupper($ca_itmcd[$i])) {
                        $myar[] = ["cd" => "0", "msg" => "Could not convert in production area"];
                        exit('{"status":' . json_encode($myar) . '}');
                        break;
                    }
                }
            }
            if ($this->SER_mod->updatebyId(["SER_QTY" => 0, "SER_LUPDT" => $currrtime, "SER_USRID" => $CUSERID, "SER_CAT" => null], $coldreff) > 0) {
                $this->SERD_mod->deletebyID_label(['SERD2_SER' => $coldreff]);
                $ser_insert_ok = 0;

                for ($i = 0; $i < $ca_count; $i++) {
                    $preparedStatement = [
                        "SER_ID" => $ca_reff[$i],
                        "SER_DOC" => $coldjob,
                        "SER_ITMID" => strtoupper($ca_itmcd[$i]),
                        "SER_QTY" => $ca_qty[$i],
                        "SER_LOTNO" => $ca_lot[$i],
                        "SER_REFNO" => $coldreff,
                        "SER_RAWTXT" => $ca_rawtxt[$i],
                        "SER_GORNG" => $ca_ok[$i],
                        "SER_LUPDT" => $currrtime,
                        "SER_BSGRP" => ($rsactive_wh == 'AFWH3') ? null : $bsgrp,
                        "SER_USRID" => $this->session->userdata('nama'),
                    ];
                    if ($ca_ok[$i] === '0') {
                        $preparedStatement['SER_CAT'] = '2';
                        $preparedStatement['SER_RMRK'] = $ca_remark[$i];
                    }
                    $ser_insert_ok += $this->SER_mod->insert($preparedStatement);
                }
                if ($ser_insert_ok == $ca_count) {
                    if (count($rsactive) == 0) {
                        $myar[] = ["cd" => "1", "msg" => "ok ok"];
                        exit('{"status":' . json_encode($myar) . '}');
                    }
                    if ($rsactive_form == "INC-QA-FG" && $rsactive_wh == "ARQA1") {
                        $ret_min = $this->ITH_mod->insert(
                            [
                                "ITH_ITMCD" => $colditem,
                                "ITH_DATE" => $rsactive_date,
                                "ITH_FORM" => $rsactive_form,
                                "ITH_DOC" => $coldjob,
                                "ITH_QTY" => -$coldqty,
                                "ITH_SER" => $coldreff,
                                "ITH_WH" => $rsactive_wh,
                                "ITH_LUPDT" => $rsactive_time,
                                "ITH_REMARK" => "WIL-SPLIT",
                                "ITH_USRID" => $this->session->userdata('nama'),
                            ]
                        );
                        if ($ret_min > 0) {
                            $ith_insert_ok = 0;
                            for ($i = 0; $i < $ca_count; $i++) {
                                if ($ca_ok[$i] == "1") {
                                    $ith_insert_ok += $this->ITH_mod->insert(
                                        [
                                            "ITH_ITMCD" => strtoupper($ca_itmcd[$i]),
                                            "ITH_DATE" => $currdate,
                                            "ITH_FORM" => "INC-QA-FG",
                                            "ITH_DOC" => $coldjob,
                                            "ITH_QTY" => $ca_qty[$i],
                                            "ITH_SER" => $ca_reff[$i],
                                            "ITH_WH" => $rsactive_wh,
                                            "ITH_LUPDT" => $currrtime,
                                            "ITH_REMARK" => "AFT-SPLIT",
                                            "ITH_USRID" => $this->session->userdata('nama'),
                                        ]
                                    );
                                } else {
                                    $ith_insert_ok += 1;
                                }
                            }
                            if ($ith_insert_ok == $ca_count) {
                                $myar[] = ["cd" => "1", "msg" => "ok QC"];
                                exit('{"status":' . json_encode($myar) . '}');
                            } else {
                                $myar[] = ["cd" => "0", "msg" => "Not All label add to transaction, please contact Admin"];
                                exit('{"status":' . json_encode($myar) . '}');
                            }
                        } else {
                            $myar[] = ["cd" => "0", "msg" => "Could not minus transaction, please contact Admin"];
                            exit('{"status":' . json_encode($myar) . '}');
                        }
                    } elseif ($rsactive_wh == "ARPRD1") {

                        $ret_min = $this->ITH_mod->insert(
                            [
                                "ITH_ITMCD" => $colditem,
                                "ITH_DATE" => $currdate,
                                "ITH_FORM" => "OUT-PRD-FG",
                                "ITH_DOC" => $coldjob,
                                "ITH_QTY" => -$coldqty,
                                "ITH_SER" => $coldreff,
                                "ITH_WH" => $rsactive_wh,
                                "ITH_LUPDT" => $currrtime,
                                "ITH_REMARK" => "WIL-SPLIT",
                                "ITH_USRID" => $CUSERID,
                            ]
                        );
                        if ($ret_min > 0) {
                            $ith_insert_ok = 0;
                            for ($i = 0; $i < $ca_count; $i++) {
                                $ith_insert_ok += $this->ITH_mod->insert(
                                    [
                                        "ITH_ITMCD" => strtoupper($ca_itmcd[$i]),
                                        "ITH_DATE" => $currdate,
                                        "ITH_FORM" => "SPLIT-FG-LBL",
                                        "ITH_DOC" => $coldjob,
                                        "ITH_QTY" => $ca_qty[$i],
                                        "ITH_SER" => $ca_reff[$i],
                                        "ITH_WH" => 'ARPRD1',
                                        "ITH_LUPDT" => $currrtime,
                                        "ITH_REMARK" => "AFT-SPLIT",
                                        "ITH_USRID" => $CUSERID,
                                    ]
                                );
                            }
                            if ($ith_insert_ok == $ca_count) {
                                $myar[] = ["cd" => "1", "msg" => "ok ."];
                                exit('{"status":' . json_encode($myar) . '}');
                            } else {
                                $myar[] = ["cd" => "0", "msg" => "Not All label add to transaction PRD, please contact Admin"];
                                exit('{"status":' . json_encode($myar) . '}');
                            }
                        } else {
                            $myar[] = ["cd" => "0", "msg" => "Could not minus transaction PRD, please contact Admin"];
                            exit('{"status":' . json_encode($myar) . '}');
                        }
                    } elseif ($rsactive_wh == "AFWH3") {
                        #Resume Out Transaction
                        $uniqueAssy = array_values(array_unique($ca_itmcd));
                        $test_out_tx = [];
                        foreach ($uniqueAssy as $u) {
                            for ($i = 0; $i < $ca_count; $i++) {
                                if (strtoupper($u) === strtoupper($ca_itmcd[$i])) {
                                    $isfound = false;
                                    foreach ($test_out_tx as &$s) {
                                        if (strtoupper($ca_itmcd[$i]) === strtoupper($colditem)) {
                                            if ($s['ITH_FORM'] === 'SPLIT-FG-LBL') {
                                                $s['ITH_QTY'] -= $ca_qty[$i];
                                                $isfound = true;
                                                break;
                                            }
                                        } else {
                                            if ($s['ITH_FORM'] === 'SPLIT-CNV-FG-OUT') {
                                                $s['ITH_QTY'] -= $ca_qty[$i];
                                                $isfound = true;
                                                break;
                                            }
                                        }
                                    }
                                    unset($s);

                                    if (!$isfound) {
                                        $test_out_tx[] = [
                                            "ITH_ITMCD" => $colditem,
                                            "ITH_DATE" => $currdate,
                                            "ITH_FORM" => strtoupper($u) === strtoupper($colditem) ? 'SPLIT-FG-LBL' : 'SPLIT-CNV-FG-OUT',
                                            "ITH_DOC" => $coldjob,
                                            "ITH_QTY" => -$ca_qty[$i],
                                            "ITH_SER" => $coldreff,
                                            "ITH_WH" => $rsactive_wh,
                                            "ITH_LOC" => $rsactive_loc,
                                            "ITH_LUPDT" => $currrtime,
                                            "ITH_REMARK" => "WIL-SPLIT",
                                            "ITH_USRID" => $CUSERID,
                                        ];
                                    }
                                }
                            }
                        }
                        $ret_min = $this->ITH_mod->insertb($test_out_tx);

                        if ($ret_min > 0) {
                            $ith_insert_ok = 0;
                            for ($i = 0; $i < $ca_count; $i++) {
                                if ((strpos(strtoupper($colditem), 'KD') !== false ||
                                    strpos(strtoupper($colditem), 'ASP') !== false
                                ) && (!strpos(strtoupper($ca_itmcd[$i]), 'KD') !== false &&
                                    !strpos(strtoupper($ca_itmcd[$i]), 'ASP') !== false
                                )
                                ) {
                                    $ith_insert_ok += $this->ITH_mod->insert(
                                        [
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
                                            "ITH_USRID" => $CUSERID,
                                        ]
                                    );
                                } else {
                                    if ((!strpos(strtoupper($colditem), 'KD') !== false &&
                                        !strpos(strtoupper($colditem), 'ASP') !== false
                                    ) && (strpos(strtoupper($ca_itmcd[$i]), 'KD') !== false ||
                                        strpos(strtoupper($ca_itmcd[$i]), 'ASP') !== false
                                    )
                                    ) {
                                        $ith_insert_ok += $this->ITH_mod->insert(
                                            [
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
                                                "ITH_USRID" => $CUSERID,
                                            ]
                                        );
                                    } else {
                                        //TO SAME WAREHOUSE
                                        $ith_insert_ok += $this->ITH_mod->insert(
                                            [
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
                                                "ITH_USRID" => $CUSERID,
                                            ]
                                        );
                                    }
                                }
                            }
                            if ($ith_insert_ok == $ca_count) {
                                $myar[] = ["cd" => "1", "msg" => "ok .."];
                                exit('{"status":' . json_encode($myar) . '}');
                            } else {
                                $myar[] = ["cd" => "0", "msg" => "Not All label add to transaction FG, please contact Admin"];
                                exit('{"status":' . json_encode($myar) . '}');
                            }
                        } else {
                            $myar[] = ["cd" => "0", "msg" => "Could not minus transaction FG, please contact Admin"];
                            exit('{"status":' . json_encode($myar) . '}');
                        }
                    } else {
                        $myar[] = ["cd" => "0", "msg" => "Could not process to transaction, please contact Admin"];
                        exit('{"status":' . json_encode($myar) . '}');
                    }
                } else {
                    $myar[] = ["cd" => "0", "msg" => "Not All label registered, please contact Admin"];
                    exit('{"status":' . json_encode($myar) . '}');
                }
            } else {
                $myar[] = ["cd" => "0", "msg" => "Could not update old reff data "];
                exit('{"status":' . json_encode($myar) . '}');
            }
        }
    }

    public function validate_wip()
    {
        header('Content-Type: application/json');
        $cid = $this->input->get('inid');
        $myar = [];
        $rsret = [];
        if ($this->SER_mod->check_Primary(['SER_ID' => $cid]) > 0) {
            $rs = $this->ITH_mod->selectstock_ser($cid);
            $rs = count($rs) > 0 ? reset($rs) : ['ITH_WH' => '??'];
            if ($rs['ITH_WH'] == 'AWIP1') {
                $rsret = $this->SER_mod->selectBCField_in_nomega([$cid]);
                $myar[] = ['cd' => 1, 'msg' => 'GO AHEAD'];
            } else {
                $myar[] = ['cd' => 0, 'msg' => 'ID is already used'];
            }
        } else {
            $myar[] = ['cd' => 0, 'msg' => 'ID is not found'];
        }
        die('{"status": ' . json_encode($myar) . ', "data": ' . json_encode($rsret) . '}');
    }

    public function validate_newreffall()
    {
        $this->checkSession();
        $currrtime = date('Y-m-d H:i:s');
        $currdate = date('Y-m-d');
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
        if (strpos($craw1, "|") !== false) {
            $araw = explode("|", $craw1);
            $clot1 = substr($araw[2], 2, strlen($araw[2]) - 2);
        }
        if (strpos($craw2, "|") !== false) {
            $araw = explode("|", $craw2);
            $clot2 = substr($araw[2], 2, strlen($araw[2]) - 2);
        }
        //end decrypt

        if ($this->SER_mod->check_Primary(array('SER_ID' => $coldreff)) == 0) {
            $datar = array("cd" => '0', "msg" => "Old Label not found {$coldreff}");
            array_push($myar, $datar);
            exit('{"status":' . json_encode($myar) . '}');
        }

        //get last warehouse , location ....
        if ($this->SISCN_mod->check_Primary(array('SISCN_SER' => $coldreff)) > 0) {
            $datar = array("cd" => '0', "msg" => "could not split delivered item label");
            array_push($myar, $datar);
            exit('{"status":' . json_encode($myar) . '}');
        } else {

            $rsactive = $this->ITH_mod->select_active_ser($coldreff);
            $rsactive_wh = $rsactive_loc = $rsactive_time = $rsactive_form = $rsactive_date = '';
            foreach ($rsactive as $r) {
                $rsactive_wh = trim($r['ITH_WH']);
                $rsactive_loc = trim($r['ITH_LOC']);
                $rsactive_date = trim($r['ITH_DATE']);
                $rsactive_time = trim($r['ITH_LUPDT']);
                $rsactive_form = trim($r['ITH_FORM']);
            }
            if ($creffka == '') {
                $ret = $this->SER_mod->insert([
                    "SER_ID" => $creff1,
                    "SER_DOC" => $coldjob,
                    "SER_ITMID" => $colditem,
                    "SER_QTY" => $cqty1,
                    "SER_QTYLOT" => $cqty1,
                    "SER_LOTNO" => $clot1,
                    "SER_REFNO" => $coldreff,
                    "SER_RAWTXT" => $craw1,
                    "SER_LUPDT" => $currrtime,
                    "SER_USRID" => $this->session->userdata('nama'),
                ]);
            } else {
                $ret = $this->SER_mod->insert(array(
                    "SER_ID" => $creffka,
                    "SER_DOC" => $coldjob,
                    "SER_ITMID" => $citemcdka . $ctypefg . $cremarkka,
                    "SER_QTY" => $cqtyka,
                    "SER_QTYLOT" => $cqtyka,
                    "SER_LOTNO" => $clotka,
                    "SER_REFNO" => $coldreff,
                    "SER_LUPDT" => $currrtime,
                    "SER_USRID" => $this->session->userdata('nama'),
                ));
            }
            if ($ret > 0) {
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
                    "SER_USRID" => $this->session->userdata('nama'),
                ));
                $ret3 = $this->SER_mod->updatebyId(array("SER_QTY" => 0, "SER_QTYLOT" => 0, "SER_LUPDT" => $currrtime), $coldreff);
                if ($ret3 > 0) {
                    // START MINUS
                    if ($rsactive_form == "INC-QA-FG" && $rsactive_wh == "ARQA1") {
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
                                "ITH_USRID" => $this->session->userdata('nama'),
                            )
                        );
                        if ($ret4 > 0) {
                            //START PLUS
                            $currdate = date('Y-m-d');
                            $ret5 = 0;
                            if ($creffka == '') {
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
                                        "ITH_USRID" => $this->session->userdata('nama'),
                                    )
                                );
                            } else {
                                $ret5 = $this->ITH_mod->insert(
                                    array(
                                        "ITH_ITMCD" => $citemcdka . $ctypefg . $cremarkka,
                                        "ITH_DATE" => $currdate,
                                        "ITH_FORM" => "INC-QA-FG",
                                        "ITH_DOC" => $coldjob,
                                        "ITH_QTY" => $cqtyka,
                                        "ITH_SER" => $creffka,
                                        "ITH_WH" => $rsactive_wh,
                                        "ITH_LUPDT" => $currrtime,
                                        "ITH_REMARK" => "AFT-SPLIT",
                                        "ITH_USRID" => $this->session->userdata('nama'),
                                    )
                                );
                            }
                            if ($ctypeg == '1') {
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
                                        "ITH_USRID" => $this->session->userdata('nama'),
                                    )
                                );
                            }

                            //END PLUS
                            if ($ret5 > 0) {
                                $datar = array("cd" => "1", "msg" => "ok QC");
                                array_push($myar, $datar);
                                exit('{"status":' . json_encode($myar) . '}');
                            } else {
                                $datar = array("cd" => "0", "msg" => "not ok QC");
                                array_push($myar, $datar);
                                exit('{"status":' . json_encode($myar) . '}');
                            }
                        }
                    } elseif ($rsactive_wh == "ARPRD1") {
                        $currdate = date('Y-m-d');
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
                                "ITH_USRID" => $this->session->userdata('nama'),
                            )
                        );
                        if ($ret4 > 0) {
                            //START PLUS
                            $ret5 = 0;
                            if ($creffka == '') {
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
                                        "ITH_USRID" => $this->session->userdata('nama'),
                                    )
                                );
                            } else {
                                $ret5 = $this->ITH_mod->insert(
                                    array(
                                        "ITH_ITMCD" => $citemcdka . $ctypefg . $cremarkka,
                                        "ITH_DATE" => $currdate,
                                        "ITH_FORM" => "INC-QA-FG",
                                        "ITH_DOC" => $coldjob,
                                        "ITH_QTY" => $cqtyka,
                                        "ITH_SER" => $creffka,
                                        "ITH_WH" => 'ARQA1',
                                        "ITH_LUPDT" => $currrtime,
                                        "ITH_REMARK" => "AFT-SPLIT",
                                        "ITH_USRID" => $this->session->userdata('nama'),
                                    )
                                );
                            }
                            if ($ctypeg == '1') {
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
                                        "ITH_USRID" => $this->session->userdata('nama'),
                                    )
                                );
                            }
                            if ($ret5 > 0) {
                                $datar = array("cd" => "1", "msg" => "ok .");
                                array_push($myar, $datar);
                                exit('{"status":' . json_encode($myar) . '}');
                            } else {
                                $datar = array("cd" => "0", "msg" => "not ok QC");
                                array_push($myar, $datar);
                                exit('{"status":' . json_encode($myar) . '}');
                            }
                            //END PLUS
                        }
                    } else {
                        $currdate = date('Y-m-d');

                        if ($rsactive_wh != '') { // if 1st parent has been included in tx
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
                                    "ITH_USRID" => $this->session->userdata('nama'),
                                )
                            );
                            if ($ret4 > 0) {
                                //START PLUS
                                if ($citemcdka == '') {
                                    $ret5 = $this->ITH_mod->insert(
                                        [
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
                                            "ITH_USRID" => $this->session->userdata('nama'),
                                        ]
                                    );
                                } else {
                                    $ret5 = $this->ITH_mod->insert(
                                        array(
                                            "ITH_ITMCD" => $colditem . $ctypefg . $cremarkka,
                                            "ITH_DATE" => $currdate,
                                            "ITH_FORM" => 'SPLIT-FG-LBL',
                                            "ITH_DOC" => $coldjob,
                                            "ITH_QTY" => $cqtyka,
                                            "ITH_SER" => $creffka,
                                            "ITH_WH" => $rsactive_wh,
                                            "ITH_LOC" => $rsactive_loc,
                                            "ITH_LUPDT" => $currrtime,
                                            "ITH_REMARK" => "AFT-SPLIT",
                                            "ITH_USRID" => $this->session->userdata('nama'),
                                        )
                                    );
                                }
                                if ($ctypeg == '1') {
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
                                            "ITH_USRID" => $this->session->userdata('nama'),
                                        )
                                    );
                                }
                                if ($ret5 > 0) {
                                    $datar = array("cd" => "1", "msg" => "ok ..");
                                    array_push($myar, $datar);
                                    exit('{"status":' . json_encode($myar) . '}');
                                } else {
                                    $datar = array("cd" => "0", "msg" => "not ok QC");
                                    array_push($myar, $datar);
                                    exit('{"status":' . json_encode($myar) . '}');
                                }
                                //END PLUS
                            }
                        }
                    }
                    //END MINUS
                } else {
                    $myar[] = ["cd" => "0", "msg" => "Could not update old reff data "];
                    exit('{"status":' . json_encode($myar) . '}');
                }
            }
        }
    }

    public function split_returncontrol_label()
    {
        $this->checkSession();
        $currrtime = date('Y-m-d H:i:s');
        $myar = [];
        $cproddt = $this->input->post('inprddt');
        $coldreff = $this->input->post('inoldreff');
        $cjob = $this->input->post('inoldjob');
        $citem = $this->input->post('inolditem');
        $coldqty = $this->input->post('inoldqty');
        $cqty = $this->input->post('innewqty');
        $cline = $this->input->post('inline');
        $cproddt = $this->input->post('inprd_date');
        $crestqty = $coldqty - $cqty;

        $cmdl = 4;
        $pYear = substr($cproddt, 2, 2);
        $pMonth = substr($cproddt, 5, 2);
        $pDay = substr($cproddt, -2);
        $pHour = date('H');
        $pMonthdis = $this->getMonthDis($pMonth);
        $newid = $this->SER_mod->lastserialid_ihour($cmdl, $cproddt);
        $newid++;
        $newid = $cmdl . $pYear . $pMonthdis . $pDay . $pHour . substr('0000000' . $newid, -8);
        if ($this->SISCN_mod->check_Primary(['SISCN_SER' => $coldreff]) > 0) {
            $myar[] = ["cd" => '0', "msg" => "could not split delivered item label"];
            exit('{"status":' . json_encode($myar) . '}');
        } else {
            $rsactive = $this->ITH_mod->select_active_ser($coldreff);
            $rsactive_wh = $rsactive_loc = '';
            foreach ($rsactive as $r) {
                $rsactive_wh = trim($r['ITH_WH']);
                $rsactive_loc = trim($r['ITH_LOC']);
            }
            $datas = [
                'SER_ID' => $newid,
                'SER_REFNO' => $coldreff,
                'SER_DOC' => $cjob,
                'SER_DOCTYPE' => '1',
                'SER_ITMID' => $citem,
                'SER_QTY' => $cqty,
                'SER_SHEET' => '',
                'SER_PRDDT' => $cproddt,
                'SER_PRDLINE' => $cline,
                'SER_LUPDT' => $currrtime,
                'SER_USRID' => $this->session->userdata('nama'),
            ];
            if ($this->SER_mod->insert($datas) > 0) {
                $ret3 = $this->SER_mod->updatebyId(["SER_QTY" => $crestqty, "SER_LUPDT" => $currrtime], $coldreff);
                if ($ret3 > 0) {
                    if (count($rsactive) == 0) { // handle if serial is not included in tx yet
                        $myar[] = ["cd" => "1", "msg" => "Saved", "reffcode" => $newid];
                        exit('{"status":' . json_encode($myar) . '}');
                    } else { // handle if serial is already included in tx
                        //start minus
                        $currdate = date('Y-m-d');
                        $ret4 = $this->ITH_mod->insert(
                            [
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
                                "ITH_USRID" => $this->session->userdata('nama'),
                            ]
                        );
                        if ($ret4 > 0) {
                            $ret5 = $this->ITH_mod->insert(
                                [
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
                                    "ITH_USRID" => $this->session->userdata('nama'),
                                ]
                            );
                            if ($ret5 > 0) {
                                $myar[] = ["cd" => "1", "msg" => "ok", "reffcode" => $newid];
                                exit('{"status":' . json_encode($myar) . '}');
                            } else {
                                $myar[] = ["cd" => "0", "msg" => "not ok WH"];
                                exit('{"status":' . json_encode($myar) . '}');
                            }
                        } else {
                            $myar[] = ["cd" => '0', "msg" => "could not minus old label, please contact admin"];
                            exit('{"status":' . json_encode($myar) . '}');
                        }
                    }
                } else {
                    $myar[] = ["cd" => '0', "msg" => "could not update old label, please contact admin"];
                    exit('{"status":' . json_encode($myar) . '}');
                }
            } else {
                $myar[] = ["cd" => '0', "msg" => "could not create new lable, please contact admin"];
                exit('{"status":' . json_encode($myar) . '}');
            }
        }
    }

    public function validate_prep()
    {
        $this->checkSession();
        $currrtime = date('Y-m-d H:i:s');
        $myar = [];
        $cproddt = $this->input->post('inprddt');
        $coldreff = $this->input->post('inoldreff');
        $cjob = $this->input->post('inoldjob');
        $citem = $this->input->post('inolditem');
        $coldqty = $this->input->post('inoldqty');
        $coldsht = $this->input->post('inoldsht');
        $cqty = $this->input->post('innewqty');
        $csheet = $this->input->post('innewsht') == '' ? 0 : $this->input->post('innewsht');
        $cline = $this->input->post('inline');
        $cshift = $this->input->post('inshift');
        $rank = $this->input->post('rank');
        $crestqty = $coldqty - $cqty;
        $crestsht = $coldsht - $csheet;
        if ($crestsht < 0) {
            $crestsht = 0;
        }

        $cmdl = 1;
        $pYear = substr($cproddt, 2, 2);
        $pMonth = substr($cproddt, 5, 2);
        $pDay = substr($cproddt, -2);
        $pMonthdis = $this->getMonthDis($pMonth);
        $newid = $this->SER_mod->lastserialid($cmdl, $cproddt);
        $newid++;
        $newid = $cmdl . $pYear . $pMonthdis . $pDay . substr('000000000' . $newid, -10);
        if ($this->SISCN_mod->check_Primary(['SISCN_SER' => $coldreff]) > 0) {
            $myar[] = ["cd" => '0', "msg" => "could not split delivered item label"];
            exit('{"status":' . json_encode($myar) . '}');
        } else {
            $rsactive = $this->ITH_mod->select_active_ser($coldreff);
            $rsactive_wh = $rsactive_loc = '';
            foreach ($rsactive as $r) {
                $rsactive_wh = trim($r['ITH_WH']);
                $rsactive_loc = trim($r['ITH_LOC']);
            }
            $datas = [
                'SER_ID' => $newid,
                'SER_REFNO' => $coldreff,
                'SER_DOC' => $cjob,
                'SER_DOCTYPE' => '1',
                'SER_ITMID' => $citem,
                'SER_QTY' => $cqty,
                'SER_SHEET' => $csheet == '' ? 0 : $csheet,
                'SER_PRDDT' => $cproddt,
                'SER_PRDLINE' => $cline,
                'SER_PRDSHFT' => $cshift,
                'SER_GRADE' => $rank === '' ? null : $rank,
                'SER_LUPDT' => $currrtime,
                'SER_USRID' => $this->session->userdata('nama'),
            ];
            if ($this->SER_mod->insert($datas) > 0) {
                $ret3 = $this->SER_mod->updatebyId(["SER_QTY" => $crestqty, "SER_LUPDT" => $currrtime, "SER_SHEET" => $crestsht], $coldreff);
                $this->SERD_mod->deletebyID_label(['SERD2_SER' => $coldreff]);
                if ($ret3 > 0) {
                    if (count($rsactive) == 0) { // handle if serial is not included in tx yet
                        $myar[] = ["cd" => "1", "msg" => "Replaced", "reffcode" => $newid];
                        exit('{"status":' . json_encode($myar) . '}');
                    } else { // handle if serial is already included in tx
                        //start minus
                        $currdate = date('Y-m-d');
                        $ret4 = $this->ITH_mod->insert(
                            [
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
                                "ITH_USRID" => $this->session->userdata('nama'),
                            ]
                        );
                        if ($ret4 > 0) {
                            $ret5 = $this->ITH_mod->insert(
                                [
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
                                    "ITH_USRID" => $this->session->userdata('nama'),
                                ]
                            );
                            if ($ret5 > 0) {
                                $myar[] = ["cd" => "1", "msg" => "ok WH", "reffcode" => $newid];
                                exit('{"status":' . json_encode($myar) . '}');
                            } else {
                                $myar[] = ["cd" => "0", "msg" => "not ok WH"];
                                exit('{"status":' . json_encode($myar) . '}');
                            }
                        } else {
                            $myar[] = ["cd" => '0', "msg" => "could not minus old label, please contact admin"];
                            exit('{"status":' . json_encode($myar) . '}');
                        }
                    }
                } else {
                    $myar[] = ["cd" => '0', "msg" => "could not update old label, please contact admin"];
                    exit('{"status":' . json_encode($myar) . '}');
                }
            } else {
                $myar[] = ["cd" => '0', "msg" => "could not create new lable, please contact admin"];
                exit('{"status":' . json_encode($myar) . '}');
            }
        }
    }

    public function split_label_status()
    {
        $this->checkSession();
        $currrtime = date('Y-m-d H:i:s');
        $myar = [];
        $cproddt = $this->input->post('inprddt');
        $coldreff = $this->input->post('inoldreff');
        $cjob = $this->input->post('inoldjob');
        $citem = $this->input->post('inolditem');
        $coldqty = $this->input->post('inoldqty');
        $cqty = $this->input->post('innewqty');
        $cline = $this->input->post('inline');
        $cshift = $this->input->post('inshift');
        $crestqty = $coldqty - $cqty;

        $cmdl = 3;
        $pYear = substr($cproddt, 2, 2);
        $pMonth = substr($cproddt, 5, 2);
        $pDay = substr($cproddt, -2);
        $pMonthdis = $this->getMonthDis($pMonth);
        $newid = $this->SER_mod->lastserialid($cmdl, $cproddt);
        $newid++;
        $newid = $cmdl . $pYear . $pMonthdis . $pDay . substr('000000000' . $newid, -10);
        if ($this->SISCN_mod->check_Primary(['SISCN_SER' => $coldreff]) > 0) {
            $myar[] = ["cd" => '0', "msg" => "could not split delivered item label"];
            exit('{"status":' . json_encode($myar) . '}');
        } else {
            $rsactive = $this->ITH_mod->select_active_ser($coldreff);
            $rsactive_wh = $rsactive_loc = '';
            foreach ($rsactive as $r) {
                $rsactive_wh = trim($r['ITH_WH']);
                $rsactive_loc = trim($r['ITH_LOC']);
            }
            $datas = [
                'SER_ID' => $newid,
                'SER_REFNO' => $coldreff,
                'SER_DOC' => $cjob,
                'SER_DOCTYPE' => '1',
                'SER_ITMID' => $citem,
                'SER_QTY' => $cqty,
                'SER_QTYLOT' => $cqty,
                'SER_SHEET' => '',
                'SER_PRDDT' => $cproddt,
                'SER_PRDLINE' => $cline,
                'SER_PRDSHFT' => $cshift,
                'SER_LUPDT' => $currrtime,
                'SER_USRID' => $this->session->userdata('nama'),
            ];
            if ($this->SER_mod->insert($datas) > 0) {
                $ret3 = $this->SER_mod->updatebyId(["SER_QTY" => 0, "SER_QTYLOT" => 0, "SER_LUPDT" => $currrtime], $coldreff);
                $this->SERD_mod->deletebyID_label(['SERD2_SER' => $coldreff]);
                #genereate 2nd unique
                $newid2 = $this->SER_mod->lastserialid($cmdl, $cproddt);
                $newid2++;
                $newid2 = $cmdl . $pYear . $pMonthdis . $pDay . substr('000000000' . $newid2, -10);
                $datas['SER_ID'] = $newid2;
                $datas['SER_QTY'] = $crestqty;
                $datas['SER_QTYLOT'] = $crestqty;
                $this->SER_mod->insert($datas);
                if ($ret3 > 0) {
                    if (count($rsactive) == 0) { // handle if serial is not included in tx yet
                        $myar[] = ["cd" => "1", "msg" => "OK", "reffcode" => $newid];
                        exit('{"status":' . json_encode($myar) . '}');
                    } else { // handle if serial is already included in tx
                        //start minus
                        $currdate = date('Y-m-d');
                        $ret4 = $this->ITH_mod->insert(
                            [
                                "ITH_ITMCD" => $citem,
                                "ITH_DATE" => $currdate,
                                "ITH_FORM" => 'SPLIT-STS-LBL',
                                "ITH_DOC" => $cjob,
                                "ITH_QTY" => -$coldqty,
                                "ITH_SER" => $coldreff,
                                "ITH_WH" => $rsactive_wh,
                                "ITH_LOC" => $rsactive_loc,
                                "ITH_LUPDT" => $currrtime,
                                "ITH_REMARK" => "WIL-SPLIT",
                                "ITH_USRID" => $this->session->userdata('nama'),
                            ]
                        );
                        if ($ret4 > 0) {
                            $ret5 = $this->ITH_mod->insert(
                                [
                                    "ITH_ITMCD" => $citem,
                                    "ITH_DATE" => $currdate,
                                    "ITH_FORM" => "SPLIT-STS-LBL",
                                    "ITH_DOC" => $cjob,
                                    "ITH_QTY" => $cqty,
                                    "ITH_SER" => $newid,
                                    "ITH_WH" => $rsactive_wh,
                                    "ITH_LOC" => $rsactive_loc,
                                    "ITH_LUPDT" => $currrtime,
                                    "ITH_REMARK" => "AFT-SPLIT",
                                    "ITH_USRID" => $this->session->userdata('nama'),
                                ]
                            );
                            $this->ITH_mod->insert(
                                [
                                    "ITH_ITMCD" => $citem,
                                    "ITH_DATE" => $currdate,
                                    "ITH_FORM" => "SPLIT-STS-LBL",
                                    "ITH_DOC" => $cjob,
                                    "ITH_QTY" => $crestqty,
                                    "ITH_SER" => $newid2,
                                    "ITH_WH" => $rsactive_wh,
                                    "ITH_LOC" => $rsactive_loc,
                                    "ITH_LUPDT" => $currrtime,
                                    "ITH_REMARK" => "AFT-SPLIT",
                                    "ITH_USRID" => $this->session->userdata('nama'),
                                ]
                            );
                            if ($ret5 > 0) {
                                $myar[] = ["cd" => "1", "msg" => "ok WH", "reffcode" => $newid, "reffcode2" => $newid2];
                                exit('{"status":' . json_encode($myar) . '}');
                            } else {
                                $myar[] = ["cd" => "0", "msg" => "not ok WH"];
                                exit('{"status":' . json_encode($myar) . '}');
                            }
                        } else {
                            $myar[] = ["cd" => '0', "msg" => "could not minus old label, please contact admin"];
                            exit('{"status":' . json_encode($myar) . '}');
                        }
                    }
                } else {
                    $myar[] = ["cd" => '0', "msg" => "could not update old label, please contact admin"];
                    exit('{"status":' . json_encode($myar) . '}');
                }
            } else {
                $myar[] = ["cd" => '0', "msg" => "could not create new lable, please contact admin"];
                exit('{"status":' . json_encode($myar) . '}');
            }
        }
    }

    public function validate_newreff_relable()
    {
        header('Content-Type: application/json');
        $cold_reff = $this->input->get('inoldreff');
        $cold_job = $this->input->get('inoldjob');
        $cold_item = $this->input->get('inolditem');
        $cold_qty = $this->input->get('inoldqty');
        $crawtext = $this->input->get('inrawtext');
        $tempoldjob = explode("-", $cold_job);
        $tempoldjob = $tempoldjob[1];
        $myar = [];
        if (strpos($crawtext, "|") !== false) { /// regular item
            $araw = explode("|", $crawtext);
            $newreff = substr($araw[5], 2, strlen($araw[5]) - 2);
            $newitem = substr($araw[0], 2, strlen($araw[0]) - 2);
            $newqty = substr($araw[3], 2, strlen($araw[3]) - 2);
            $thelot = substr($araw[2], 2, strlen($araw[2]) - 2);
            $tempjob = substr($thelot, 3, 5);
            if (substr($tempjob, 0, 1) == '0') {
                $tempjob = substr($tempjob, 1, 4);
            }

            $newitem_ = substr($newitem, 0, 9);
            $cold_item_ = substr($cold_item, 0, 9);
            if ($newitem_ != $cold_item_) {
                $myar[] = ["cd" => "0", "msg" => "New Item is not same with old item, please compare the label !"];
                exit('{"status":' . json_encode($myar) . '}');
            }
            if ($newqty != $cold_qty) {
                $myar[] = ["cd" => "0", "msg" => "qty must be same"];
                exit('{"status":' . json_encode($myar) . '}');
            }
            $com_oldjob = str_replace("KD", "", $cold_job);
            $com_oldjob = str_replace("ES2", "", $com_oldjob);
            $com_oldjob = str_replace("ES", "", $com_oldjob);
            $com_oldjob = str_replace("ASP", "", $com_oldjob);

            if ($tempoldjob != $tempjob) {
                $myar[] = ["cd" => "0", "msg" => "job is not same, please check the label again, $tempoldjob  !=  $tempjob"];
                exit('{"status":' . json_encode($myar) . '}');
            }
            if ($cold_reff == $newreff) {
                $myar[] = ["cd" => "0", "msg" => "could not transfer to the same reff no"];
                exit('{"status":' . json_encode($myar) . '}');
            }
            if ($this->SER_mod->check_Primary(array("SER_ID" => $newreff)) > 0) {
                $myar[] = ["cd" => "0", "msg" => "the reff no is already registered"];
                exit('{"status":' . json_encode($myar) . '}');
            } else {
                $myar[] = ["cd" => "1", "msg" => "go ahead", "rawtext" => $crawtext];
                exit('{"status":' . json_encode($myar) . '}');
            }
        } else { /// HANDLE NON REGULAR
            if ($this->SER_mod->check_Primary(array("SER_ID" => $crawtext)) > 0) {
                $myar[] = ["cd" => "0", "msg" => "the reff no is already registered"];
                exit('{"status":' . json_encode($myar) . '}');
            } else {
                $myar[] = ["cd" => "1", "msg" => "go ahead"];
                exit('{"status":' . json_encode($myar) . '}');
            }
        }
    }

    public function validate_relable()
    {
        $currrtime = date('Y-m-d H:i:s');
        $myar = [];
        $coldreff = $this->input->post('inoldreff');
        $crawtext = $this->input->post('innewreff');

        if ($this->ITH_mod->check_Primary(['ITH_FORM' => 'OUT-SHP-FG', 'ITH_SER' => $coldreff]) > 0) {
            $myar[] = ["cd" => '0', "msg" => "could not split delivered item label"];
            exit('{"status":' . json_encode($myar) . '}');
        } else {
            if (strpos($crawtext, "|") !== false) { /// regular item
                $araw = explode("|", $crawtext);
                $newreff = substr($araw[5], 2, strlen($araw[5]) - 2);
                //log first
                $this->LOGSER_mod->insert_replace_log([
                    "LOGSER_KEYS" => $crawtext, "LOGSER_KEYS_RPLC" => $coldreff, "LOGSER_DT" => $currrtime, "LOGSER_USRID" => $this->session->userdata('nama'),
                ]);
                //end log

                $rsbef = $this->SER_mod->select_exact_byVAR(['SER_ID' => $coldreff]);
                $shouldreplace_reff = true;

                foreach ($rsbef as $cv) {
                    if (trim($cv['SER_ID']) != trim($cv['SER_REFNO'])) {
                        $shouldreplace_reff = false;
                    }
                }
                $ret3 = 0;
                if ($shouldreplace_reff) {
                    $ret3 = $this->SER_mod->updatebyId(["SER_ID" => $newreff, "SER_REFNO" => $newreff, "SER_RAWTXT" => $crawtext], $coldreff);
                } else {
                    $ret3 = $this->SER_mod->updatebyId(["SER_ID" => $newreff, "SER_RAWTXT" => $crawtext], $coldreff);
                }
                //end update
                if ($ret3 > 0) {
                    $this->SERD_mod->updatebyId(['SERD2_SER' => $newreff], ['SERD2_SER' => $coldreff]);
                    $this->SISCN_mod->updatebyId(['SISCN_SER' => $newreff], ['SISCN_SER' => $coldreff]);
                    $this->DELV_mod->updatebyVAR(['DLV_SER' => $newreff], ['DLV_SER' => $coldreff]);
                    $this->DLVPRC_mod->updatebyId(['DLVPRC_SER' => $newreff], ['DLVPRC_SER' => $coldreff]);
                    $this->DLVCK_mod->updateWMSDeliveryCheckByVAR(['dlv_refno' => $newreff], ['dlv_refno' => $coldreff]);
                    //update ith
                    $cwhere = ["ITH_SER" => $coldreff];
                    $ctoupdate = ["ITH_SER" => $newreff];
                    $retith = $this->ITH_mod->updatebyId($cwhere, $ctoupdate);
                    //end update
                    if ($retith > 0) {
                        if ($this->PND_mod->updateserbyId(['PNDSER_SER' => $newreff], ['PNDSER_SER' => $coldreff]) > 0) {
                            if ($this->PNDSCN_mod->updatebyId(['PNDSCN_SER' => $newreff], ['PNDSCN_SER' => $coldreff]) > 0) {
                                $this->RLS_mod->update_ser_where(['RLSSER_SER' => $newreff, 'RLSSER_REFFSER' => $newreff], ['RLSSER_SER' => $coldreff]);
                                $myar[] = ["cd" => '1', "msg" => "ok, replaced...."];
                                exit('{"status":' . json_encode($myar) . '}');
                            } else {
                                $myar[] = ["cd" => '1', "msg" => "ok, replaced..."];
                                exit('{"status":' . json_encode($myar) . '}');
                            }
                        } else {
                            $myar[] = ["cd" => '1', "msg" => "ok, replaced.."];
                            exit('{"status":' . json_encode($myar) . '}');
                        }
                    } else {
                        $myar[] = ["cd" => '1', "msg" => "ok replaced"];
                        exit('{"status":' . json_encode($myar) . '}');
                    }
                } else {
                    $myar[] = ["cd" => '0', "msg" => "could not update, please contact admin"];
                    exit('{"status":' . json_encode($myar) . '}');
                }
            } else {
                //log first
                $this->LOGSER_mod->insert_replace_log([
                    "LOGSER_KEYS" => $crawtext, "LOGSER_KEYS_RPLC" => $coldreff, "LOGSER_DT" => $currrtime, "LOGSER_USRID" => $this->session->userdata('nama'),
                ]);
                //end log

                $rsbef = $this->SER_mod->select_exact_byVAR(['SER_ID' => $coldreff]);
                $shouldreplace_reff = true;

                foreach ($rsbef as $cv) {
                    if (trim($cv['SER_ID']) != trim($cv['SER_REFNO'])) {
                        $shouldreplace_reff = false;
                    }
                }
                $ret3 = 0;
                if ($shouldreplace_reff) {
                    $ret3 = $this->SER_mod->updatebyId(["SER_ID" => $crawtext, "SER_REFNO" => $crawtext, "SER_RAWTXT" => $crawtext], $coldreff);
                } else {
                    $ret3 = $this->SER_mod->updatebyId(["SER_ID" => $crawtext, "SER_RAWTXT" => $crawtext], $coldreff);
                }
                if ($ret3 > 0) {
                    $this->SERD_mod->updatebyId(['SERD2_SER' => $crawtext], ['SERD2_SER' => $coldreff]);
                    //update ith
                    $cwhere = ["ITH_SER" => $coldreff];
                    $ctoupdate = ["ITH_SER" => $crawtext];
                    $retith = $this->ITH_mod->updatebyId($cwhere, $ctoupdate);
                    //end update
                    if ($retith > 0) {
                        if ($this->PND_mod->updateserbyId(['PNDSER_SER' => $crawtext], ['PNDSER_SER' => $coldreff]) > 0) {
                            if ($this->PNDSCN_mod->updatebyId(['PNDSCN_SER' => $crawtext], ['PNDSCN_SER' => $coldreff]) > 0) {
                                $this->RLS_mod->update_ser_where(['RLSSER_SER' => $crawtext, 'RLSSER_REFFSER' => $crawtext], ['RLSSER_SER' => $coldreff]);
                                $myar[] = ["cd" => '1', "msg" => "ok, replaced,,,,"];
                                exit('{"status":' . json_encode($myar) . '}');
                            } else {
                                $myar[] = ["cd" => '1', "msg" => "ok, replaced,,,"];
                                exit('{"status":' . json_encode($myar) . '}');
                            }
                        } else {
                            $myar[] = ["cd" => '1', "msg" => "ok, replaced,,"];
                            exit('{"status":' . json_encode($myar) . '}');
                        }
                    } else {
                        $myar[] = ["cd" => '1', "msg" => "ok replaced,"];
                        exit('{"status":' . json_encode($myar) . '}');
                    }
                } else {
                    $myar[] = ["cd" => '0', "msg" => "could not update, please contact admin"];
                    exit('{"status":' . json_encode($myar) . '}');
                }
            }
        }
    }

    public function qcunconform()
    {
        $cbg = $this->input->get('inbg');
        $rs = $this->SER_mod->select_qcunconform($cbg);
        $myar = [];
        if (count($rs) > 0) {
            $myar[] = ["cd" => '1', "msg" => "Under construction"];
        } else {
            $myar[] = ["cd" => '0', "msg" => "Good, there is no list"];
        }
        echo '{"status": ' . json_encode($myar) . ',"data":' . json_encode($rs) . '}';
    }

    public function del()
    {
        $rs = $this->SER_mod->todel();
        foreach ($rs as $k) {
            $cid = $k['ITH_SER'];
            $myar = [];
            if ($this->SER_mod->check_Primary(['SER_ID' => $cid]) > 0) {
                $ret1 = $this->SER_mod->deletebyID(['SER_ID' => $cid]);
                if ($ret1 > 0) {
                    if ($this->ITH_mod->check_Primary(['ITH_SER' => $cid]) > 0) {
                        //bak transaction
                        $this->ITH_mod->tobin($this->session->userdata('nama'), $cid);
                        ///start delete tx

                        $ret2 = $this->ITH_mod->deletebyID(['ITH_SER' => $cid, 'ITH_WH' => 'AFWH3']);
                        $ret2 += $this->ITH_mod->deletebyID(['ITH_SER' => $cid, 'ITH_WH' => 'ARQA1']);
                        if ($ret2 > 0) {
                            $myar[] = ["cd" => '1', "msg" => "Deleted.."];
                        }
                    } else {
                        $myar[] = ["cd" => '1', "msg" => "Deleted"];
                    }
                } else {
                    $myar[] = ["cd" => '0', "msg" => "Could not delete the label"];
                }
            } else {
                $myar[] = ["cd" => '0', "msg" => "the label not found"];
            }
            echo '{"status": ' . json_encode($myar) . '}';
        }
    }

    public function deletelabel1()
    {
        $this->checkSession();
        $cid = $this->input->post('inid');
        $myar = [];
        if ($this->ITH_mod->check_Primary(['ITH_SER' => $cid, 'ITH_WH' => 'AFWH3']) == 0) {
            if ($this->SER_mod->check_Primary(['SER_ID' => $cid]) > 0) {
                $ret1 = $this->SER_mod->deletebyID(['SER_ID' => $cid]);
                if ($ret1 > 0) {
                    if ($this->ITH_mod->check_Primary(['ITH_SER' => $cid]) > 0) {
                        //bak transaction
                        $this->ITH_mod->tobin($this->session->userdata('nama'), $cid);
                        ///start delete tx
                        $ret2 = $this->ITH_mod->deletebyID(['ITH_SER' => $cid]);
                        if ($ret2 > 0) {
                            $this->SERD_mod->deletebyID_label_undelivered($cid);
                            $myar[] = ["cd" => '1', "msg" => "Deleted.."];
                        }
                    } else {
                        $myar[] = ["cd" => '1', "msg" => "Deleted"];
                    }
                } else {
                    $myar[] = ["cd" => '0', "msg" => "Could not delete the label"];
                }
            } else {
                $myar[] = ["cd" => '0', "msg" => "the label not found"];
            }
        } else {
            $myar[] = ["cd" => '0', "msg" => "Could not delete"];
        }
        echo '{"status": ' . json_encode($myar) . '}';
    }

    public function checkSession()
    {
        $myar = [];
        if ($this->session->userdata('status') != "login") {
            $myar[] = ["cd" => "0", "msg" => "Session is expired please reload page"];
            exit('{"status":' . json_encode($myar) . '}');
        }
    }

    public function combine2_prep()
    {
        $creff = $this->input->post('inreffcd');
        $myar = array();
        $rs = array();
        $datar = array();
        if ($this->SER_mod->check_Primary(array('SER_ID' => $creff)) > 0) {
            if ($this->SISCN_mod->check_Primary(array('SISCN_SER' => $creff)) == 0) {
                $rsith = $this->ITH_mod->selectstock_ser($creff);
                if (count($rsith) > 0) {
                    foreach ($rsith as $r) {
                        if (trim($r['ITH_WH']) == 'AFWH3' && $r['ITH_QTY'] > 0) {
                            $rs = $this->SER_mod->select_exact_byVAR(array('SER_ID' => $creff));
                            foreach ($rs as $r) {
                                if (trim($r['SER_PRDLINE']) != '' && substr($r['SER_ID'], 0, 1) == '1') { //validate plant 2 label specification

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
        echo '{"status":' . json_encode($myar);
        echo ',"data": ' . json_encode($rs) . '}';
    }

    public function combine2_validate()
    {
        $cid = $this->input->get('inid');
        $myar = [];
        if ($this->SER_mod->check_Primary(['SER_ID' => $cid]) > 0) {
            array_push($myar, ['cd' => 0, 'msg' => 'The reff no is already registered']);
        } else {
            array_push($myar, ['cd' => 1, 'msg' => 'go ahead']);
        }
        exit('{"status": ' . json_encode($myar) . '}');
    }

    public function combine1_prep()
    {
        $creff = $this->input->post('inreffcd');
        $myar = array();
        $rs = array();
        $datar = array();
        if ($this->SER_mod->check_Primary(array('SER_ID' => $creff)) > 0) {
            if ($this->SISCN_mod->check_Primary(array('SISCN_SER' => $creff)) == 0) {
                $rsith = $this->ITH_mod->selectstock_ser($creff);
                if (count($rsith) > 0) {
                    foreach ($rsith as $r) {
                        if (trim($r['ITH_WH']) == 'AFWH3' && $r['ITH_QTY'] > 0) {
                            $rs = $this->SER_mod->select_exact_byVAR(['SER_ID' => $creff]);
                            foreach ($rs as $n) {
                                $prdLine = $n['SER_PRDLINE'] ? trim($n['SER_PRDLINE']) : '';
                                if ($prdLine == '') { //validate plant 2 label specification

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
        echo '{"status":' . json_encode($myar);
        echo ',"data": ' . json_encode($rs) . '}';
    }

    public function combine2_save()
    {
        $this->checkSession();
        $currrtime = date('Y-m-d H:i:s');
        $cproddt = date('Y-m-d');
        $cmdl = 1;
        $myar = [];
        $caid = $this->input->post('inid');
        $cajob = $this->input->post('injob');
        $caitemcd = $this->input->post('initemcd');
        $cqty = $this->input->post('inqty');
        $cqtyt = $this->input->post('inqtyt');
        $csheetqtyt = $this->input->post('insheetqty');
        $au_job = array_unique($cajob);
        $cnt_au = count($au_job);
        $cnt_data = count($caid);
        $CUSERID = $this->session->userdata('nama');
        if ($cnt_au > 1) {
            //#1 set old master label qty to zero
            $retupdate = 0;
            ///#3.1 get bigest qty
            $tempqtybig = 0;
            $biggestjob = '';
            for ($i = 0; $i < $cnt_data; $i++) {
                $retupdate += $this->SER_mod->updatebyId(['SER_QTY' => 0, 'SER_LUPDT' => $currrtime], $caid[$i]);
                if ($cqty[$i] > $tempqtybig) {
                    $tempqtybig = $cqty[$i];
                    $biggestjob = $cajob[$i];
                }
            }
            $aa_job = explode('-', $biggestjob);
            $biggestjob = $aa_job[0] . "-C" . $aa_job[1] . "-" . $aa_job[2];
            if ($retupdate == $cnt_data) {
                //#2 minus in transaction
                $retminus = 0;
                for ($i = 0; $i < $cnt_data; $i++) {
                    $rsactive = $this->ITH_mod->select_active_ser($caid[$i]);
                    $rsactive_wh = $rsactive_loc = '';
                    foreach ($rsactive as $r) {
                        $rsactive_wh = trim($r['ITH_WH']);
                        $rsactive_loc = trim($r['ITH_LOC']);
                    }
                    $retminus += $this->ITH_mod->insert(
                        [
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
                            "ITH_USRID" => $this->session->userdata('nama'),
                        ]
                    );
                }
                //#3 create new label
                if ($retminus == $cnt_data) {
                    $csheet = $csheetqtyt;
                    $cline = '';
                    $cshift = '';
                    $crank = '';
                    $rsinfo = $this->SER_mod->select_exact_byVAR(['SER_ID' => $caid[0]]);
                    foreach ($rsinfo as $r) {
                        $cline = $r['SER_PRDLINE'];
                        $cshift = $r['SER_PRDSHFT'];
                        $crank = $r['SER_GRADE'] ?? null;
                    }

                    $pYear = substr($cproddt, 2, 2);
                    $pMonth = substr($cproddt, 5, 2);
                    $pDay = substr($cproddt, -2);
                    $pMonthdis = $this->getMonthDis($pMonth);
                    $newid = $this->SER_mod->lastserialid($cmdl, $cproddt);
                    $newid++;
                    $newid = $cmdl . $pYear . $pMonthdis . $pDay . substr('000000000' . $newid, -10);
                    $datas = [
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
                        'SER_GRADE' => $crank,
                        'SER_LUPDT' => $currrtime,
                        'SER_USRID' => $this->session->userdata('nama'),
                    ];
                    $retregis = $this->SER_mod->insert($datas);
                    if ($retregis > 0) {
                        //#4 register to map
                        $retregistermap = 0;
                        for ($i = 0; $i < $cnt_data; $i++) {
                            if ($this->SERC_mod->check_Primary(['SERC_NEWID' => $newid, 'SERC_COMID' => $caid[$i]]) == 0) {
                                $retregistermap += $this->SERC_mod->insert(
                                    [
                                        'SERC_NEWID' => $newid, 'SERC_COMID' => $caid[$i], 'SERC_COMJOB' => $cajob[$i], 'SERC_COMQTY' => $cqty[$i], 'SERC_USRID' => $CUSERID, 'SERC_LUPDT' => $currrtime,
                                    ]
                                );
                            }
                        }
                        if ($retregistermap == $cnt_data) {
                            //#5 plus
                            $retplus = $this->ITH_mod->insert(
                                [
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
                                    "ITH_USRID" => $this->session->userdata('nama'),
                                ]
                            );
                            if ($retplus > 0) {
                                $myar[] = ['cd' => '1', 'msg' => 'Different Job successfully joined', 'nreffcode' => $newid];
                            } else {
                                $myar[] = ['cd' => '0', 'msg' => 'could not add tranasaction for new label [DF]'];
                            }
                        } else {
                            $myar[] = ['cd' => '0', 'msg' => 'not all labels registered into mapping job [DF]'];
                        }
                    } else {
                        $myar[] = ['cd' => '0', 'msg' => 'could not register new label [DF]'];
                    }
                } else {
                    $myar[] = ['cd' => '0', 'msg' => 'Not all old labels are out in transaction, please contact admin [DF]'];
                }
            } else {
                $myar[] = ['cd' => '0', 'msg' => 'Not all old labels are updated, please contact admin [DF]'];
            }
        } else {
            //#1 set old master label qty to zero
            $retupdate = 0;
            for ($i = 0; $i < $cnt_data; $i++) {
                $retupdate += $this->SER_mod->updatebyId(['SER_QTY' => 0, 'SER_LUPDT' => $currrtime], $caid[$i]);
                $this->SERD_mod->deletebyID_label(['SERD2_SER' => $caid[$i]]);
            }
            if ($retupdate == $cnt_data) {
                //#2 minus in transaction
                $retminus = 0;
                for ($i = 0; $i < $cnt_data; $i++) {
                    $rsactive = $this->ITH_mod->select_active_ser($caid[$i]);
                    $rsactive_wh = $rsactive_loc = '';
                    foreach ($rsactive as $r) {
                        $rsactive_wh = trim($r['ITH_WH']);
                        $rsactive_loc = trim($r['ITH_LOC']);
                    }
                    $retminus += $this->ITH_mod->insert(
                        [
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
                            "ITH_USRID" => $this->session->userdata('nama'),
                        ]
                    );
                }
                //#3 create new label
                if ($retminus == $cnt_data) {
                    $csheet = $csheetqtyt;
                    $cline = '';
                    $cshift = '';
                    $crank = '';
                    $rsinfo = $this->SER_mod->select_exact_byVAR(['SER_ID' => $caid[0]]);
                    foreach ($rsinfo as $r) {
                        $cline = $r['SER_PRDLINE'];
                        $cshift = $r['SER_PRDSHFT'];
                        $crank = $r['SER_GRADE'] ? $r['SER_GRADE'] : null;
                    }
                    $pYear = substr($cproddt, 2, 2);
                    $pMonth = substr($cproddt, 5, 2);
                    $pDay = substr($cproddt, -2);
                    $pMonthdis = $this->getMonthDis($pMonth);
                    $newid = $this->SER_mod->lastserialid($cmdl, $cproddt);
                    $newid++;
                    $newid = $cmdl . $pYear . $pMonthdis . $pDay . substr('000000000' . $newid, -10);
                    $datas = [
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
                        'SER_GRADE' => $crank,
                        'SER_LUPDT' => $currrtime,
                        'SER_USRID' => $this->session->userdata('nama'),
                    ];
                    $retregis = $this->SER_mod->insert($datas);
                    if ($retregis > 0) {
                        //#4 register to map
                        $retregistermap = 0;
                        for ($i = 0; $i < $cnt_data; $i++) {
                            if ($this->SERC_mod->check_Primary(['SERC_NEWID' => $newid, 'SERC_COMID' => $caid[$i]]) == 0) {
                                $retregistermap += $this->SERC_mod->insert(
                                    [
                                        'SERC_NEWID' => $newid, 'SERC_COMID' => $caid[$i], 'SERC_COMJOB' => $cajob[$i], 'SERC_COMQTY' => $cqty[$i], 'SERC_USRID' => $CUSERID, 'SERC_LUPDT' => $currrtime,
                                    ]
                                );
                            }
                        }

                        if ($retregistermap == $cnt_data) {
                            //#5 plus
                            $retplus = $this->ITH_mod->insert(
                                [
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
                                    "ITH_USRID" => $this->session->userdata('nama'),
                                ]
                            );
                            if ($retplus > 0) {
                                $myar[] = ['cd' => '1', 'msg' => 'Same Job successfully joined', 'nreffcode' => $newid];
                            } else {
                                $myar[] = ['cd' => '0', 'msg' => 'could not add tranasaction for new label'];
                            }
                        } else {
                            $myar[] = ['cd' => '0', 'msg' => 'not all labels registered into mapping job'];
                        }
                    } else {
                        $myar[] = ['cd' => '0', 'msg' => 'could not register new label'];
                    }
                } else {
                    $myar[] = ['cd' => '0', 'msg' => 'Not all old labels are out in transaction, please contact admin'];
                }
            } else {
                $myar[] = ['cd' => '0', 'msg' => 'Not all old labels are updated, please contact admin'];
            }
        }
        echo '{"status":' . json_encode($myar) . '}';
    }
    public function combine1_save()
    {
        $this->checkSession();
        $currrtime = date('Y-m-d H:i:s');
        $cproddt = date('Y-m-d');

        $myar = [];
        $caid = $this->input->post('inid');
        $cajob = $this->input->post('injob');
        $caitemcd = $this->input->post('initemcd');
        $cqty = $this->input->post('inqty');
        $cqtyt = $this->input->post('inqtyt');
        $cnewjob = $this->input->post('innewjob');
        $initial = strtoupper($cnewjob[3]);
        $cnewrawtext = $this->input->post('innewrawtext');
        $cnewid = $this->input->post('innewid');
        $astr = explode("|", $cnewrawtext);
        $clot = $astr[2];
        $clot = substr($clot, 2, strlen($clot));
        $cnt_data = count($caid);
        $CUSERID = $this->session->userdata('nama');

        //#1 set old master label qty to zero
        $retupdate = 0;
        for ($i = 0; $i < $cnt_data; $i++) {
            $retupdate += $this->SER_mod->updatebyId(['SER_QTY' => 0, 'SER_LUPDT' => $currrtime], $caid[$i]);
        }
        if ($retupdate == $cnt_data) {
            //#2 minus in transaction
            $retminus = 0;
            for ($i = 0; $i < $cnt_data; $i++) {
                if ($initial != 'C') {
                    $this->SERD_mod->deletebyID_label(['SERD2_SER' => $caid[$i]]);
                }
                $rsactive = $this->ITH_mod->select_active_ser($caid[$i]);
                $rsactive_wh = $rsactive_loc = '';
                foreach ($rsactive as $r) {
                    $rsactive_wh = trim($r['ITH_WH']);
                    $rsactive_loc = trim($r['ITH_LOC']);
                }
                $retminus += $this->ITH_mod->insert(
                    [
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
                        "ITH_USRID" => $this->session->userdata('nama'),
                    ]
                );
            }
            //#3 create new label
            if ($retminus == $cnt_data) {
                $datas = [
                    'SER_ID' => $cnewid,
                    'SER_REFNO' => 'JOIN_P2',
                    'SER_DOC' => $cnewjob,
                    'SER_ITMID' => $caitemcd[0],
                    'SER_QTY' => $cqtyt,
                    "SER_LOTNO" => $clot,
                    'SER_RAWTXT' => $cnewrawtext,
                    'SER_LUPDT' => $currrtime,
                    'SER_USRID' => $CUSERID,
                ];
                $retregis = $this->SER_mod->insert($datas);
                if ($retregis > 0) {
                    //#4 register to map
                    $retregistermap = 0;
                    for ($i = 0; $i < $cnt_data; $i++) {
                        if ($this->SERC_mod->check_Primary(['SERC_NEWID' => $cnewid, 'SERC_COMID' => $caid[$i]]) == 0) {
                            $retregistermap += $this->SERC_mod->insert(
                                [
                                    'SERC_NEWID' => $cnewid, 'SERC_COMID' => $caid[$i], 'SERC_COMJOB' => $cajob[$i], 'SERC_COMQTY' => $cqty[$i], 'SERC_USRID' => $CUSERID, 'SERC_LUPDT' => $currrtime,
                                ]
                            );
                        }
                    }

                    if ($retregistermap == $cnt_data) {
                        //#5 plus
                        $retplus = $this->ITH_mod->insert(
                            [
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
                                "ITH_USRID" => $this->session->userdata('nama'),
                            ]
                        );
                        if ($retplus > 0) {
                            $myar[] = ['cd' => '1', 'msg' => 'Plant 1 successfully joined'];
                        } else {
                            $myar[] = ['cd' => '0', 'msg' => 'could not add tranasaction for new label'];
                        }
                    } else {
                        $myar[] = ['cd' => '0', 'msg' => 'not all labels registered into mapping job'];
                    }
                } else {
                    $myar[] = ['cd' => '0', 'msg' => 'could not register new label'];
                }
            } else {
                $myar[] = ['cd' => '0', 'msg' => 'Not all old labels are out in transaction, please contact admin'];
            }
        } else {
            $myar[] = ['cd' => '0', 'msg' => 'Not all old labels are updated, please contact admin'];
        }
        echo '{"status":' . json_encode($myar) . '}';
    }

    public function combined2_list()
    {
        $csearch = $this->input->get('insearch');
        $csearch_by = $this->input->get('insearch_by');
        $myar = $rs = [];
        if ($csearch_by == '1') {
            $rs = $this->SERC_mod->select_byVAR(['SERC_NEWID' => $csearch]);
        } else {
            $rs = $this->SERC_mod->select_byVAR(['SERC_COMID' => $csearch]);
        }
        if (count($rs) > 0) {
            array_push($myar, ['cd' => 1, 'msg' => "go ahead"]);
        } else {
            array_push($myar, ['cd' => 0, 'msg' => "data is not found"]);
        }
        echo '{"status":' . json_encode($myar)
        . ',"data": ' . json_encode($rs)
            . '}';
    }

    public function convert_validate_newlabel()
    {
        $crawtext = $this->input->get('inrawtext');
        $cremark = $this->input->get('inremark');
        $myar = [];
        if (strpos($crawtext, "|") !== false) { /// regular item
            $araw = explode("|", $crawtext);
            $newreff = substr($araw[5], 2, strlen($araw[5]) - 2);
            $theitem = substr($araw[0], 2, strlen($araw[0]) - 2);
            $theitem .= $cremark;
            if ($this->MSTITM_mod->check_Primary(['MITM_ITMCD' => $theitem]) > 0) {
                if ($this->SER_mod->check_Primary(array("SER_ID" => $newreff)) > 0) {
                    $myar[] = ["cd" => "0", "msg" => "the reff no is already registered"];
                    exit('{"status":' . json_encode($myar) . '}');
                } else {
                    $myar[] = ["cd" => "1", "msg" => "go ahead", "rawtext" => $crawtext];
                    exit('{"status":' . json_encode($myar) . '}');
                }
            } else {
                $myar[] = ["cd" => "0", "msg" => "the item is not registered {$theitem}"];
                exit('{"status":' . json_encode($myar) . '}');
            }
        } else {
            $myar[] = ["cd" => "0", "msg" => "Reff No is not valid..."];
            exit('{"status":' . json_encode($myar) . '}');
        }
    }

    public function convert_prep()
    {
        header('Content-Type: application/json');
        $cold_reff = $this->input->get('inid');
        $rsold_reff = $this->SER_mod->select_exact_byVAR(['SER_ID' => $cold_reff]);
        $rxtx = $this->ITH_mod->selectAll_by(['ITH_SER' => $cold_reff]);
        $current_location = null;
        $myar = array();
        if (count($rsold_reff) > 0) {
            if ($this->SISCN_mod->check_Primary(['SISCN_SER' => $cold_reff]) > 0) {
                $myar[] = ["cd" => '0', "msg" => "could not split delivered item label"];
            } else {
                $rsith = $this->ITH_mod->selectstock_ser($cold_reff);
                if (count($rsith) > 0) {
                    foreach ($rsith as $r) {
                        $current_location = trim($r['ITH_WH']);
                        if ((trim($r['ITH_WH']) == 'AFWH3' || trim($r['ITH_WH']) == 'NFWH4RT') && $r['ITH_QTY'] > 0) {
                            $myar[] = ["cd" => '1', "msg" => "go ahead"];
                        } else {
                            $myar[] = ["cd" => '0', "msg" => "the label must be in AFWH3 first, currently it is in $r[ITH_WH]"];
                        }
                    }
                } else {
                    $myar[] = ["cd" => '0', "msg" => "the label has not been in scanning transaction or it is already converted"];
                }
            }
        } else {
            $myar[] = ["cd" => '0', "msg" => "Reff No is not found"];
        }
        die(json_encode(['data' => $rsold_reff, 'tx' => $rxtx, 'status' => $myar, 'current_location' => $current_location]));
    }

    public function convertlabel_save()
    {
        $this->checkSession();
        $currrtime = date('Y-m-d H:i:s');
        $currdate = date('Y-m-d');
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

        if ($this->SER_mod->check_Primary(['SER_ID' => $coldreff]) == 0) {
            $myar[] = ["cd" => '0', "msg" => "Old Reff No is not found"];
            exit('{"status":' . json_encode($myar) . '}');
        }

        if (strpos($crg_raw, "|") !== false) {
            $araw = explode("|", $crg_raw);
            $crg_lot = substr($araw[2], 2, strlen($araw[2]) - 2);
        }
        $CUSERID = $this->session->userdata('nama');

        if ($this->SISCN_mod->check_Primary(array('SISCN_SER' => $coldreff)) > 0) {
            $myar[] = ["cd" => '0', "msg" => "could not split delivered item label"];
            exit('{"status":' . json_encode($myar) . '}');
        } else {
            if ($cfg_type == '') { //handle Regular
                $araw = explode("|", $crg_raw);
                $theitem = substr($araw[0], 2, strlen($araw[0]) - 2);
                $theitem .= $crg_remark;
                if ($this->MSTITM_mod->check_Primary(['MITM_ITMCD' => $theitem]) == 0) {
                    $myar[] = ["cd" => '0', "msg" => "Item Code is not registered {$theitem}"];
                    exit('{"status":' . json_encode($myar) . '}');
                }
                if ($this->SER_mod->check_Primary(['SER_ID' => $crg_reff]) > 0) {
                    $myar[] = ["cd" => '0', "msg" => "could not convert old label to registered reff number"];
                    exit('{"status":' . json_encode($myar) . '}');
                }
            } else {
                if ($this->MSTITM_mod->check_Primary(['MITM_ITMCD' => $cka_itmcd . $cfg_type . $cka_remark]) == 0) {
                    $myar[] = ["cd" => '0', "msg" => "Item Code is not registered"];
                    exit('{"status":' . json_encode($myar) . '}');
                }
                if ($this->SER_mod->check_Primary(['SER_ID' => $cka_reffno]) > 0) {
                    $myar[] = ["cd" => '0', "msg" => "could not convert old label to registered reff number"];
                    exit('{"status":' . json_encode($myar) . '}');
                }
            }

            $rsactive = $this->ITH_mod->select_active_ser($coldreff);
            $rsactive_wh = $rsactive_loc = '';
            foreach ($rsactive as $r) {
                $rsactive_wh = trim($r['ITH_WH']);
                $rsactive_loc = trim($r['ITH_LOC']);
            }
            if ($cfg_type == 'KD' || $cfg_type == 'ASP' || $cfg_type == 'KDASP' || $cfg_type == 'KDES') { // TO NON REGULAR
                if ($this->SER_mod->updatebyId(['SER_QTY' => 0, 'SER_LUPDT' => $currrtime, 'SER_USRID' => $CUSERID], $coldreff) > 0) {
                    //create new serial
                    $this->SERD_mod->deletebyID_label(['SERD2_SER' => $coldreff]);
                    $retser = $this->SER_mod->insert([
                        "SER_ID" => $cka_reffno,
                        "SER_DOC" => $coldjob,
                        "SER_ITMID" => $cka_itmcd . $cfg_type . $cka_remark,
                        "SER_QTY" => $coldqty,
                        "SER_LOTNO" => $cka_lotno,
                        "SER_REFNO" => $coldreff,
                        "SER_LUPDT" => $currrtime,
                        "SER_USRID" => $this->session->userdata('nama'),
                    ]);
                    if ($retser > 0) {
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
                            "ITH_USRID" => $CUSERID,
                        ]);
                        //end minus
                        if ($retmin > 0) {
                            $retplus = $this->ITH_mod->insert([
                                "ITH_ITMCD" => $cka_itmcd . $cfg_type . $cka_remark,
                                "ITH_DATE" => $currdate,
                                "ITH_FORM" => 'CONVERT-IN',
                                "ITH_DOC" => $coldjob,
                                "ITH_QTY" => $coldqty,
                                "ITH_SER" => $cka_reffno,
                                "ITH_WH" => 'ARPRD1',
                                "ITH_LUPDT" => $currrtime,
                                "ITH_REMARK" => "CONVERT",
                                "ITH_USRID" => $CUSERID,
                            ]);
                            if ($retplus > 0) {
                                $myar[] = ["cd" => '1', "msg" => "OK"];
                                exit('{"status":' . json_encode($myar) . '}');
                            } else {
                                $myar[] = ["cd" => '0', "msg" => "could not plus qty in transaction [REGULAR]"];
                                exit('{"status":' . json_encode($myar) . '}');
                            }
                        } else {
                            $myar[] = ["cd" => '0', "msg" => "could not minus old qty in transaction [REGULAR]"];
                            exit('{"status":' . json_encode($myar) . '}');
                        }
                    } else {
                        $myar[] = ["cd" => '0', "msg" => "could not register the reff no [REGULAR]"];
                        exit('{"status":' . json_encode($myar) . '}');
                    }
                } else {
                    $myar[] = ["cd" => '0', "msg" => "could not update old label [REGULAR]"];
                    exit('{"status":' . json_encode($myar) . '}');
                }
            } else { //to REGULAR
                if ($this->SER_mod->updatebyId(['SER_QTY' => 0, 'SER_LUPDT' => $currrtime, 'SER_USRID' => $CUSERID], $coldreff) > 0) {
                    //reg new REFF
                    $retser = $this->SER_mod->insert([
                        "SER_ID" => $crg_reff,
                        "SER_DOC" => $coldjob,
                        "SER_ITMID" => $crg_itmcd,
                        "SER_QTY" => $coldqty,
                        "SER_LOTNO" => $crg_lot,
                        "SER_REFNO" => $coldreff,
                        "SER_RAWTXT" => $crg_raw,
                        "SER_LUPDT" => $currrtime,
                        "SER_USRID" => $CUSERID,
                    ]);
                    //end reg
                    if ($retser > 0) {
                        $this->SERD_mod->deletebyID_label(['SERD2_SER' => $coldreff]);
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
                            "ITH_USRID" => $CUSERID,
                        ]);
                        //end minus
                        if ($retmin > 0) {
                            if (strpos($colditem, "KD") !== false || strpos($colditem, "ASP") !== false) {
                                $rsactive_wh = "ARPRD1";
                            }
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
                                "ITH_USRID" => $CUSERID,
                            ]);
                            if ($retplus > 0) {
                                $myar[] = ["cd" => '1', "msg" => "OK"];
                                exit('{"status":' . json_encode($myar) . '}');
                            } else {
                                $myar[] = ["cd" => '0', "msg" => "could not plus qty in transaction [REGULAR]"];
                                exit('{"status":' . json_encode($myar) . '}');
                            }
                        } else {
                            $myar[] = ["cd" => '0', "msg" => "could not minus old qty in transaction [REGULAR]"];
                            exit('{"status":' . json_encode($myar) . '}');
                        }
                    } else {
                        $myar[] = ["cd" => '0', "msg" => "could not register the reff no [REGULAR]"];
                        exit('{"status":' . json_encode($myar) . '}');
                    }
                } else {
                    $myar[] = ["cd" => '0', "msg" => "could not update old label [REGULAR]"];
                    exit('{"status":' . json_encode($myar) . '}');
                }
            }
        }
    }

    public function validate_export_label()
    {
        $creff = $this->input->get('innewreff');
        $myar = [];
        if ($this->SER_mod->check_Primary(['SER_ID' => $creff]) > 0) {
            $myar[] = ['cd' => 0, 'msg' => 'The reff no is already exist'];
        } else {
            $myar[] = ['cd' => 1, 'msg' => 'go ahead'];
        }
        die('{"status":' . json_encode($myar) . '}');
    }

    public function qccancelscan()
    {
        $this->checkSession();
        $cid = $this->input->post('inreff');
        $myar = [];
        $rsith = $this->ITH_mod->selectstock_ser($cid);
        if (count($rsith) > 0) {
            $retbin = $this->ITH_mod->tobin_info('CANCELQC', $this->session->userdata('nama'), $cid);
            $isok = false;
            foreach ($rsith as $r) {
                if (trim($r['ITH_WH']) == 'ARQA1' && $r['ITH_QTY'] > 0 && trim($r['ITH_FORM']) == 'INC-QA-FG') {
                    $isok = true;
                    break;
                }
            }
            if ($isok) {
                $retdelith = $this->ITH_mod->deletebyID(['ITH_SER' => $cid, 'ITH_WH' => 'ARQA1']);
                $retdelith_prd = $this->ITH_mod->deletePRD($cid);
                if ($retdelith > 0) {
                    array_push($myar, ['cd' => '1', 'msg' => 'Canceled']);
                } else {
                    array_push($myar, ['cd' => '0', 'msg' => 'Could not cancel scan QC..']);
                }
            } else {
                array_push($myar, ['cd' => '0', 'msg' => 'Could not cancel scan QC.']);
            }
        } else {
            array_push($myar, ['cd' => '0', 'msg' => 'Could not cancel scan QC']);
        }
        echo '{"status":' . json_encode($myar) . '}';
    }

    public function vtrace_unique()
    {
        $this->load->view('wms_report/vrpttrace_uniquekey');
    }

    public function vsplitstatuslabel()
    {
        $this->load->view('wms/vsplitstatuslabel');
    }

    public function traceid()
    {
        header('Content-Type: application/json');
        $cid = $this->input->get('inid');
        $rs = $this->SER_mod->selectbyVAR_where(['SER_ID' => $cid]);
        $myar = [];
        $rsrelabel = [];
        $rsrelabel_fix = [];
        $rsdel = $this->ITH_mod->selectbin_history(['ITH_SER' => $cid]);
        $rsjm = $this->SER_mod->select_jm($cid);
        $rscombine = $this->SER_mod->select_combine($cid);
        $rsSI = $this->SISCN_mod->select_trace(['SISCN_SER' => $cid]);
        if (!empty($rs)) {
            $myar[] = ['cd' => 1, 'msg' => 'go ahead'];
        } else {
            $rsrelabel = $this->LOGSER_mod->select_new_label($cid);
            $rsrelabel_fix = [];
            if (count($rsrelabel)) {
                foreach ($rsrelabel as $r) {
                    $ar = explode("|", $r['LOGSER_KEYS']);
                    if (count($ar) > 1) {
                        $serid_new = substr($ar[5], 2, strlen($ar[5]));
                        $serqty = substr($ar[3], 2, strlen($ar[3]));
                        $rsrelabel_fix[] = [
                            'SER_OLDID' => $cid, 'SER_NEWID' => $serid_new, 'SER_QTY' => $serqty, 'PIC' => $r['PIC'], 'LOGSER_DT' => $r['LOGSER_DT'],
                        ];
                    } else {
                        $serid_new = $r['LOGSER_KEYS'];
                        $serqty = 0;
                        $rsrelabel_fix[] = [
                            'SER_OLDID' => $cid, 'SER_NEWID' => $serid_new, 'SER_QTY' => $serqty, 'PIC' => $r['PIC'], 'LOGSER_DT' => $r['LOGSER_DT'],
                        ];
                    }
                }
            }
            $myar[] = ['cd' => 0, 'msg' => 'not found'];
        }
        die(json_encode([
            'status' => $myar,
            'data' => $rs,
            'data_relable' => $rsrelabel_fix,
            'data_delete' => $rsdel,
            'jm' => $rsjm,
            'combine' => $rscombine,
            'si' => $rsSI,
        ]));
    }

    public function calculateCombinedJob()
    {
        header('Content-Type: application/json');
        $pser = $this->input->post('inunique');
        $pserqty = $this->input->post('inunique_qty');
        $pjob = $this->input->post('inunique_job');

        #check the calculation
        $rscombine = $this->SER_mod->select_combine($pser);
        $rsUncalculated = $rsCalculated = $rsSummary = [];
        foreach ($rscombine as $r) {
            if (!$r->SERD2_SER) {
                $rsUncalculated[] = $r;
            } else {
                $rsCalculated[] = $r->SERD2_SER;
            }
        }
        if (empty($rsUncalculated)) {
            $strCalculated = is_array($rsCalculated) ? "'" . implode("','", $rsCalculated) . "'" : "''";
            $rsSummary = $this->SERD_mod->select_calculation_perID($strCalculated, $pser, $pjob, $pserqty);
            if ($this->SERD_mod->check_Primary_label(['SERD2_SER' => $pser])) {
                $myar = ['cd' => 1, 'msg' => 'already inserted', 'msgreff' => 'Available'];
            } else {
                $this->SERD_mod->insertb2($rsSummary);
                $myar = ['cd' => 1, 'msg' => 'OK', 'msgreff' => 'Available'];
            }
        } else {
            #calculate first
            $Calc_lib = new RMCalculator();
            $calculationResult = [];
            foreach ($rsUncalculated as $r) {
                $calculationResult[] = $Calc_lib->calculate_raw_material_resume([$r->SERC_COMID], [$r->SERC_COMQTY], [$r->SERC_COMJOB]);
            }
            $myar = ['cd' => 0, 'msg' => 'try again please', 'calculationResult' => $calculationResult];
        }
        die(json_encode(['status' => $myar, 'rsSummary' => $rsSummary, 'rsUncalculated' => $rsUncalculated]));
    }

    public function getchilds()
    {
        header('Content-Type: application/json');
        $cid = $this->input->get('inid');
        $rs = $this->SER_mod->selectbyVAR_where(['SER_REFNO' => $cid, 'SER_ID!=SER_REFNO' => null]);
        $myar = [];
        $rsmulti = $this->SERML_mod->select_trace(["SERML_NEWID" => $cid]);
        if (substr($cid, 0, 1) == "3") {
            $rsmulti = $this->SERML_mod->select_trace(["SERML_COMID" => $cid]);
            $serid = '';
            foreach ($rsmulti as $r) {
                $serid = $r['SERML_NEWID'];
            }
            $rsmulti = $this->SERML_mod->select_trace(["SERML_NEWID" => $serid]);
        }
        if (count($rs) > 0) {
            $myar[] = ['cd' => 1, 'msg' => 'go ahead'];
        } else {
            $myar[] = ['cd' => 0, 'msg' => 'not found'];
        }
        die('{"status": ' . json_encode($myar)
            . ', "data": ' . json_encode($rs)
            . ', "data_multi":' . json_encode($rsmulti)
            . '}');
    }

    public function add_rm_to_boxID()
    {
        $currtime = date('Y-m-d H:i:s');
        header('Content-Type: application/json');
        $cpsn = $this->input->post('inpsn');
        $cprocd = $this->input->post('inprocd');
        $cline = $this->input->post('inline');
        $citmcat = $this->input->post('initmcat');
        $cfr = $this->input->post('infr');
        $cjob = $this->input->post('injob');
        $cactqt = $this->input->post('inactqt');
        $cper = $this->input->post('inper');
        $cmc = $this->input->post('inmc');
        $cmcz = $this->input->post('inmcz');
        $citmcd = $this->input->post('initmcd');
        $ttluse = $this->input->post('inttluse');
        $usrid = $this->session->userdata('nama');
        $myar = [];
        $rsalike = $this->SPL_mod->select_jobper_wmscal_alike($cjob, $ttluse);
        $ttladded = 0;
        $flagku = 0;
        foreach ($rsalike as $r) {
            $flagku = 1;
            if (count($this->SPL_mod->select_jobper_wmscal_byseruse($cjob, $r['SERD2_SER'], $r['SERD2_QTPER'])) > 0) { #$ttluse
                $flagku = 2;
                if ($this->SERD_mod->insert2([
                    'SERD2_PSNNO' => $cpsn, 'SERD2_LINENO' => $cline, 'SERD2_PROCD' => $cprocd, 'SERD2_CAT' => '', 'SERD2_FR' => $cfr, 'SERD2_SER' => $r['SERD2_SER'], 'SERD2_FGQTY' => $r['SERD2_FGQTY'], 'SERD2_JOB' => $cjob, 'SERD2_QTPER' => $cper, 'SERD2_MC' => $cmc, 'SERD2_MCZ' => $cmcz, 'SERD2_ITMCD' => $citmcd, 'SERD2_QTY' => $cper * $r['SERD2_FGQTY']#($cactqt > 0) ?: 0
                    , 'SERD2_REMARK' => 'MANUAL ADDED', 'SERD2_LOTNO' => '', 'SERD2_USRID' => $usrid, 'SERD2_LUPDT' => $currtime,
                ])) {
                    $ttladded++;
                }
            }
        }
        if ($ttladded > 0) {
            $myar[] = ['cd' => 1, 'msg' => $ttladded . ' data revised, please click refresh button (is next to calculate button) to get new status'];
        } else {
            $myar[] = ['cd' => '0', 'msg' => 'nothing revised ' . count($rsalike), 'flagku' => $flagku];
        }
        die('{"status": ' . json_encode($myar) . '}');
    }
    public function add_rm_to_boxID_special()
    {
        $currtime = date('Y-m-d H:i:s');
        header('Content-Type: application/json');
        $cpsn = $this->input->post('inpsn');
        $cprocd = $this->input->post('inprocd');
        $cline = $this->input->post('inline');
        $citmcat = $this->input->post('initmcat');
        $cfr = $this->input->post('infr');
        $cjob = $this->input->post('injob');
        $cqtytosave = $this->input->post('inqtytosave');
        $cper = $this->input->post('inper');
        $cmc = $this->input->post('inmc');
        $cmcz = $this->input->post('inmcz');
        $citmcd = $this->input->post('initmcd');
        $cser = $this->input->post('inser');
        $cserqty = $this->input->post('inserqty');
        $usrid = $this->session->userdata('nama');
        $myar = [];
        $ttladded = 0;
        if ($this->SERD_mod->insert2([
            'SERD2_PSNNO' => $cpsn, 'SERD2_LINENO' => $cline, 'SERD2_PROCD' => $cprocd, 'SERD2_CAT' => $citmcat, 'SERD2_FR' => $cfr, 'SERD2_SER' => $cser, 'SERD2_FGQTY' => $cserqty, 'SERD2_JOB' => $cjob, 'SERD2_QTPER' => $cper, 'SERD2_MC' => $cmc, 'SERD2_MCZ' => $cmcz, 'SERD2_ITMCD' => $citmcd, 'SERD2_QTY' => $cqtytosave, 'SERD2_REMARK' => 'MANUAL ADDED', 'SERD2_LOTNO' => '', 'SERD2_USRID' => $usrid, 'SERD2_LUPDT' => $currtime,
        ])) {
            $ttladded++;
        }
        if ($ttladded > 0) {
            $myar[] = ['cd' => 1, 'msg' => $ttladded . ' data revised, please click refresh button (is next to calculate button) to get new status'];
        } else {
            $myar[] = ['cd' => '0', 'msg' => 'nothing revised'];
        }
        die('{"status": ' . json_encode($myar) . '}');
    }

    public function flag_rmuse_ok()
    {
        $this->checkSession();
        $currtime = date('Y-m-d H:i:s');
        $cjob = $this->input->get('injob');
        $ttluse = $this->input->get('inttluse');
        $myar = [];
        $rsalike = $this->SPL_mod->select_jobper_wmscal_alike($cjob, $ttluse);
        $userid = $this->session->userdata('nama');
        $ttlupdated = 0;
        foreach ($rsalike as $r) {
            if ($this->SER_mod->updatebyId(
                [
                    'SER_RMUSE_COMFG' => 'flg_ok', 'SER_RMUSE_COMFG_DT' => $currtime, 'SER_RMUSE_COMFG_USRID' => $userid,
                ],
                $r['SERD2_SER']
            ) > 0) {
                $ttlupdated++;
            }
        }
        if ($ttlupdated > 0) {
            $myar[] = ['cd' => 1, 'msg' => $ttlupdated . ' data updated'];
        } else {
            $myar[] = ['cd' => 0, 'msg' => 'Nothing updated'];
        }
        die('{"status": ' . json_encode($myar) . '}');
    }

    public function vcheckrm_fgwh()
    {
        $this->load->view('wms/vcheckrm_fgwh');
    }

    public function get_check_susuan_bb()
    {
        header('Content-Type: application/json');
        $searchby = $this->input->get('insearchby');
        $searchval = $this->input->get('insearchval');
        $rs = [];
        switch ($searchby) {
            case 'wh':
                $rs = $this->SER_mod->select_sususan_bahan_baku('AFWH3');
                break;
            case 'tx':
                $rs = $this->SER_mod->select_sususan_bahan_baku_by_txid($searchval);
                break;
            case 'job':
                $rs = $this->SER_mod->select_sususan_bahan_baku_by_job($searchval);
                break;
            case 'us_qc':
                $rs = $this->SER_mod->select_sususan_bahan_baku('ARPRD1');
                break;
        }
        die(json_encode(['data' => $rs]));
    }
    public function get_check_susuan_bb_filter()
    {
        header('Content-Type: application/json');
        $cjobs = is_array($this->input->get('injobs')) ? $this->input->get('injobs') : [];
        $citems = is_array($this->input->get('initems')) ? $this->input->get('initems') : [];
        $citems_c = count($citems);
        if (count($cjobs) > 0) {
            if ($citems_c > 0) {
                $rs = $this->SER_mod->select_sususan_bahan_baku_filter_jobsitems($cjobs, $citems);
            } else {
                $rs = $this->SER_mod->select_sususan_bahan_baku_filter_jobs($cjobs);
            }
        } else {
            if ($citems_c > 0) {
                $rs = $this->SER_mod->select_sususan_bahan_baku_filter_items($citems);
            } else {
                $rs = $this->SER_mod->select_sususan_bahan_baku('AFWH3');
            }
        }
        die('{"data":' . json_encode($rs) . '}');
    }

    public function tobexported_list_for_serd_test()
    {
        header('Content-Type: application/json');
        $pjob = $this->input->post('injob');
        $rspsn = $this->SPL_mod->select_z_getpsn_byjob("'" . $pjob . "'");
        $apsn = [];
        foreach ($rspsn as $r) {
            $apsn[] = $r['PPSN1_PSNNO'];
        }
        $Calc_lib = new RMCalculator();
        $rs = $Calc_lib->tobexported_list_for_serd($apsn);
        die('{"data":' . json_encode($rs) . ',"psna": ' . json_encode($apsn) . '}');
    }

    public function testRMCalculator()
    {
        header('Content-Type: application/json');
        $doc = $this->input->get('doc');
        $Calc_lib = new RMCalculator();
        $rs = $Calc_lib->get_usage_rm_perjob($doc);
        die(json_encode(['data' => $rs]));
    }

    public function resetcalculation()
    {
        header('Content-Type: application/json');
        $cjob = $this->input->post('injob');
        $cser = $this->input->post('inser');
        $cqty = $this->input->post('inqty');
        $myar = [];

        $jobunique = array_unique($cjob);
        foreach ($jobunique as $r) {
            if (in_array($r, $this->WOExceptionList)) {
                # simulation of the job is not ok
                $myar[] = ['cd' => 0, 'msg' => 'could reset calculation for this job'];
                die(json_encode(['status' => $myar]));
            }
        }
        foreach ($jobunique as $r) {
            $this->SERD_mod->deletebyID(['SERD_JOB' => $r]);
        }
        foreach ($cser as $r) {
            if (strlen($r) > 5) {
                if ($r[0] == '3') {
                    $this->SERD_mod->deletebyID_label_unused($r);
                } else {
                    $this->SERD_mod->deletebyID_label_undelivered($r);
                }
            }
        }
        $Calc_lib = new RMCalculator();
        $Calc_lib->calculate_raw_material_resume($cser, $cqty, $cjob);
        $myar[] = ['cd' => 1, 'msg' => 'ok'];
        die('{"status":' . json_encode($myar) . '}');
    }

    public function calculate_raw_material_oldata_resume()
    {
        header('Content-Type: application/json');
        ini_set('max_execution_time', '-1');
        $pser = $this->input->post('inunique');
        $pserqty = $this->input->post('inunique_qty');
        $pjob = $this->input->post('inunique_job');
        $Calc_lib = new RMCalculator();
        $rs = $Calc_lib->calculate_raw_material_oldata_resume($pser, $pserqty, $pjob);
        die($rs);
    }

    public function cancel_confirmed_rc()
    {
        $cint_label = $this->input->get('inint_label');
        $cext_label = $this->input->get('inext_label');
        $myar = [];
        if ($this->SERRC_mod->check_Primary(['SERRC_SERX' => $cext_label])) {
            if ($this->ITH_mod->check_Primary(['ITH_SER' => $cext_label])) { #is state already confirmed
                $rs_ith = $this->ITH_mod->selectstock_ser($cext_label);
                $rs_ith = count($rs_ith) > 0 ? reset($rs_ith) : ['ITH_WH' => '??'];
                if ($rs_ith['ITH_WH'] == 'ARRCRT') {
                    #confirmed state
                    #1 CANCEL ITH
                    $ret_ith = $this->ITH_mod->deletebyID(['ITH_SER' => $cext_label]);
                    if ($ret_ith) {
                        #1.1 CANCEL ITH
                        $ret_ith2 = $this->ITH_mod->deletebyID(['ITH_SER' => $cint_label, 'ITH_REMARK' => $cext_label]);
                        if ($ret_ith2) {
                            #2 CANCEL EXTLBL IN SERRC_TBL
                            $res_rc = $this->SERRC_mod->update_where(
                                [
                                    'SERRC_NASSYCD' => null, 'SERRC_SERX' => null, 'SERRC_SERXRAWTXT' => null, 'SERRC_SERXQTY' => null,
                                ],
                                [
                                    'SERRC_SERX' => $cext_label,
                                ]
                            );
                            if ($res_rc) {
                                if ($this->SER_mod->check_Primary(['SER_ID' => $cext_label])) {
                                    #3 CANCEL EXTLBL IN SER_TBL
                                    $res_ser = $this->SER_mod->deletebyID(['SER_ID' => $cext_label]);
                                    if ($res_ser) {
                                        $myar[] = ['cd' => 1, 'msg' => 'Canceled successfully'];
                                    } else {
                                        $myar[] = ['cd' => 0, 'msg' => 'Could not delete customer label'];
                                    }
                                } else {
                                    $myar[] = ['cd' => 0, 'msg' => 'Customer label is not found'];
                                }
                            } else {
                                $myar[] = ['cd' => 1, 'msg' => 'Canceled..'];
                            }
                        } else {
                            $myar[] = ['cd' => 0, 'msg' => 'Could not cancel internal label in transaction'];
                        }
                    } else {
                        $myar[] = ['cd' => 0, 'msg' => 'Could not cancel customer label in transaction'];
                    }
                } else {
                    $myar[] = ['cd' => 0, 'msg' => 'Could not cancel'];
                }
            } else { #state is not confirmed
                $res_rc = $this->SERRC_mod->update_where(
                    [
                        'SERRC_NASSYCD' => null, 'SERRC_SERX' => null, 'SERRC_SERXRAWTXT' => null, 'SERRC_SERXQTY' => null,
                    ],
                    [
                        'SERRC_SERX' => $cext_label,
                    ]
                );
                $myar[] = ['cd' => 1, 'msg' => 'Canceled.'];
            }
        } else {
            $myar[] = ['cd' => 1, 'msg' => 'Canceled'];
        }
        die('{"status":' . json_encode($myar) . '}');
    }

    public function validate_oldlbl_wip()
    {
        header('Content-Type: application/json');
        $cid = $this->input->get('inid');
        $myar = [];
        $rsser = $this->SER_mod->selectbyVAR_where(['SER_ID' => $cid]);
        if (count($rsser)) {
            if ($this->ITH_mod->check_Primary(['ITH_FORM' => 'OUT-USE', 'ITH_SER' => $cid])) {
                $myar[] = ['cd' => 0, 'msg' => 'it is already used'];
            } else {
                $isqtyzero = false;
                foreach ($rsser as $r) {
                    if ($r['SER_QTY'] == 0) {
                        $isqtyzero = true;
                        break;
                    }
                }
                if ($isqtyzero) {
                    $myar[] = ['cd' => 0, 'msg' => 'Could not process Zero qty'];
                } else {
                    $rs = $this->ITH_mod->selectstock_ser($cid);
                    $rs = count($rs) > 0 ? reset($rs) : ['ITH_WH' => '??'];
                    if ($rs['ITH_WH'] == 'AWIP1') {
                        $myar[] = ['cd' => 1, 'msg' => 'GO AHEAD'];
                    } else {
                        $myar[] = ['cd' => 0, 'msg' => 'Please scan in AWIP area first'];
                    }
                }
            }
        } else {
            $myar[] = ['cd' => 0, 'msg' => 'ID is not found'];
        }
        die('{"status":' . json_encode($myar) . ',"data":' . json_encode($rsser) . '}');
    }

    public function setwiptowh()
    {
        header('Content-Type: application/json');
        $current_datetime = date('Y-m-d H:i:s');
        $current_date = date('Y-m-d');
        $aReffNo = $this->input->post('areffno');
        $aNewItem = $this->input->post('aitemcd');
        $aID = $this->input->post('aID');
        $aLotNo = $this->input->post('alotno');
        $aReffQty = $this->input->post('areffqty');
        $aINTQty = $this->input->post('aintqty');
        $aNewJob = $this->input->post('anewjob');
        $aOldJob = $this->input->post('aoldjob');
        $aOldItem = $this->input->post('aolditemcd');
        $fReff_in = [];
        $myar = [];
        $userid = $this->session->userdata('nama');
        $rsout = [];
        $rsinc = [];
        $rsser = [];
        $rscjt = [];
        if (is_array($aReffNo)) {
            $RowsCount = count($aReffNo);
            $Calc_lib = new RMCalculator();
            #initialize out qty
            for ($i = 0; $i < $RowsCount; $i++) {
                $rsout[] = [
                    'ITH_ITMCD' => $aOldItem[$i], 'ITH_DATE' => $current_date, 'ITH_FORM' => 'OUT-USE', 'ITH_DOC' => $aOldJob[$i], 'ITH_QTY' => -$aINTQty[$i], 'ITH_WH' => 'AWIP1', 'ITH_SER' => $aID[$i], 'ITH_REMARK' => $aReffNo[$i] . " : Convert", 'ITH_LUPDT' => $current_datetime, 'ITH_USRID' => $userid,
                ];
                if (!in_array($aReffNo[$i], $fReff_in)) {
                    $fReff_in[] = $aReffNo[$i];
                    $rsser[] = [
                        'SER_ID' => $aReffNo[$i], 'SER_DOC' => $aNewJob[$i], 'SER_ITMID' => $aNewItem[$i], 'SER_QTY' => $aReffQty[$i], 'SER_LOTNO' => $aLotNo[$i], 'SER_REFNO' => 'SN', 'SER_LUPDT' => $current_datetime, 'SER_USRID' => 'ane',
                    ];
                    $rsinc[] = [
                        'ITH_ITMCD' => $aNewItem[$i], 'ITH_DATE' => $current_date, 'ITH_FORM' => 'INC-QA-FG', 'ITH_DOC' => $aNewJob[$i], 'ITH_QTY' => $aReffQty[$i], 'ITH_WH' => 'ARQA1', 'ITH_SER' => $aReffNo[$i], 'ITH_REMARK' => "AWIP1", 'ITH_LUPDT' => $current_datetime, 'ITH_USRID' => $userid,
                    ];
                }
            }
            if ($RowsCount > 0) {
                $this->SER_mod->insertb($rsser);
                $this->ITH_mod->insertb($rsout);
                $this->ITH_mod->insertb($rsinc);
                foreach ($rsser as $r) {
                    if (substr($r['SER_DOC'], 3, 1) == 'C') {
                        for ($i = 0; $i < $RowsCount; $i++) {
                            if ($r['SER_ID'] == $aReffNo[$i]) {
                                $rscjt[] = [
                                    'SERC_NEWID' => $r['SER_ID'], 'SERC_COMID' => $aID[$i], 'SERC_COMJOB' => $aOldJob[$i], 'SERC_COMQTY' => $aINTQty[$i], 'SERC_USRID' => $userid, 'SERC_LUPDT' => $current_datetime,
                                ];
                                $Calc_lib->calculate_later([$aID[$i]], [$aINTQty[$i]], [$aOldJob[$i]]);
                            }
                        }
                    } else {
                        $Calc_lib->calculate_later([$r['SER_ID']], [$r['SER_QTY']], [$r['SER_DOC']]);
                    }
                }
                if (count($rscjt) > 0) {
                    $this->SERC_mod->insertb($rscjt);
                }
            }
            $myar[] = ['cd' => 1, 'msg' => 'OK'];
        } else {
            $myar[] = ['cd' => 0, 'msg' => 'The data is not valid'];
        }
        die('{"status":' . json_encode($myar)
            . ',"data_out":' . json_encode($rsout)
            . ',"data_inc":' . json_encode($rsinc)
            . '}');
    }

    public function show_reffno_rad()
    {
        header('Content-Type: application/json');
        $docno = $this->input->get('docno');
        $itemcd = $this->input->get('itemcd');
        $rs = $this->SER_mod->select_balance_ref_rad($docno, $itemcd);
        die('{"data":' . json_encode($rs) . '}');
    }

    public function prepare_fgtowip()
    {
        header('Content-Type: application/json');
        $reffno = $this->input->get('reffno');
        $rsold_reff = $this->SER_mod->select_exact_byVAR(['SER_ID' => $reffno]);
        $myar = [];
        $rstx = $this->ITH_mod->selectAll_by(['ITH_SER' => $reffno]);
        $lastwh = ['code' => ''];
        if (count($rsold_reff)) {
            $myar[] = ["cd" => 1, "msg" => "go ahead"];
            $rslastwh = $this->ITH_mod->selectstock_ser($reffno);
            foreach ($rslastwh as $r) {
                $lastwh = ['code' => $r['ITH_WH']];
            }
        } else {
            $myar[] = ["cd" => '0', "msg" => "Reff No is not found"];
        }
        echo '{"data":'
        . json_encode($rsold_reff)
        . ',"tx":' . json_encode($rstx)
        . ',"status":' . json_encode($myar)
        . ',"lastwh":' . json_encode($lastwh)
            . '}';
    }

    public function vcombine_rmlbl()
    {
        $this->load->view('wms/vjoinrmlabel');
    }

    public function combine_rmlbl_desktop()
    {
        header('Content-Type: application/json');
        $currdate = date('YmdHis');
        $myar = [];
        $currrtime = date('Y-m-d H:i:s');

        $citm = $this->input->post('initmcd');
        $clot = $this->input->post('inlot');
        $cqty_com = $this->input->post('inqtycom');
        $cuser = $this->input->post('inuser');
        if (is_array($citm)) {
            $ttldata = count($citm);
            $lot_distinc = array_values(array_unique($clot));
            $C3Data = [];
            $newqty = 0;
            $lotasHome = $clot[0];
            if (count($lot_distinc) > 1) {
                $lotasHome = substr($clot[0], 0, 10);
                $lotasHome .= '$C';
            }
            for ($i = 0; $i < $ttldata; $i++) {
                $newqty += $cqty_com[$i];
            }
            #PREPARE NEW ROW ID
            $newid = "CM" . $currdate; #combine manual
            #END
            for ($i = 0; $i < $ttldata; $i++) {
                $C3Data[] = [
                    'C3LC_ITMCD' => $citm[0], 'C3LC_NLOTNO' => $lotasHome, 'C3LC_NQTY' => $newqty, 'C3LC_LOTNO' => $clot[$i], 'C3LC_QTY' => $cqty_com[$i], 'C3LC_REFF' => $newid, 'C3LC_LINE' => $i, 'C3LC_USRID' => $cuser, 'C3LC_LUPTD' => $currrtime,
                ];
            }
            $this->C3LC_mod->insertb($C3Data);
            $printdata[] = ['NEWQTY' => $newqty, 'NEWLOT' => $lotasHome];
            $myar[] = ['cd' => '1', 'msg' => 'Saved successfully'];
        } else {
            $myar[] = ['cd' => '0', 'msg' => 'It seems You are using wrong menu or function'];
        }
        die('{"status":' . json_encode($myar) . ',"data":' . json_encode($printdata) . '}');
    }

    public function validate_emergency()
    {
        header('Content-Type: application/json');
        $id = $this->input->get('id');
        $rs = $this->SER_mod->select_validate_emergency($id);
        die(json_encode(['data' => $rs]));
    }

    public function updatecalculation()
    {
        $this->checkSession();
        header('Content-Type: application/json');
        $reffid = $this->input->post('reffid');
        $oldqty = $this->input->post('oldqty');
        $newqty = $this->input->post('newqty');
        $itemcode = $this->input->post('itemcode');
        $cuser = $this->session->userdata('nama');
        $result = $this->SERD_mod->updatebyId(
            ['SERD2_QTY' => $newqty, 'SERD2_REMARK' => 'edit qty', 'SERD2_USRID' => $cuser],
            ['SERD2_SER' => $reffid, 'SERD2_ITMCD' => $itemcode, 'SERD2_QTY' => $oldqty]
        );
        $myar[] = $result ? ['cd' => '1', 'msg' => 'updated successfully'] : ['cd' => '0', 'msg' => 'could not update'];
        die('{"status":' . json_encode($myar) . '}');
    }

    public function showCalculationPerItem()
    {
        header('Content-Type: application/json');
        $reffno = $this->input->get('reffno');
        $itemCD = $this->input->get('itemCD');
        $mcz = $this->input->get('mcz');
        $rs = $this->SERD_mod->selectbyVAR_with_cols(
            [
                'SERD2_SER' => $reffno,
                'SERD2_ITMCD' => $itemCD,
                'SERD2_MCZ' => $mcz,
            ],
            [
                'SERD2_LINENO',
                'SERD2_PROCD',
                'SERD2_FR',
                'SERD2_MC',
                'SERD2_MCZ',
                'SERD2_QTPER',
                'SERD2_ITMCD',
                'RTRIM(MITM_SPTNO) MITM_SPTNO',
                'SERD2_QTY',
                'RTRIM(MITM_ITMD1) MITM_ITMD1',
                'SERD2_LUPDT',
            ]
        );
        die('{"data":' . json_encode($rs) . '}');
    }

    public function removerowcalculation()
    {
        header('Content-Type: application/json');
        $reffno = $this->input->post('reffno');
        $itemCD = $this->input->post('itemCD');
        $mcz = $this->input->post('mcz');
        $qty = $this->input->post('qty');
        $luptd = $this->input->post('luptd');
        $result = $this->SERD_mod->deletebyID_label([
            'SERD2_SER' => $reffno,
            'SERD2_ITMCD' => $itemCD,
            'SERD2_MCZ' => $mcz,
            'SERD2_QTY' => $qty,
            'SERD2_LUPDT' => $luptd,
        ]);
        $myar[] = $result ? ['cd' => '1', 'msg' => 'Deleted'] : ['cd' => '0', 'msg' => 'could not be deleted'];
        die('{"status":' . json_encode($myar) . '}');
    }

    public function form_search_rm_in_fg()
    {
        $this->load->view('wms_report/vrpt_rm_in_fg');
    }
    public function form_conversion_test()
    {
        $this->load->view('wms_report/vrpt_conversion_test');
    }
    public function conversion_test()
    {
        header('Content-Type: application/json');
        header("Access-Control-Allow-Origin: *");
        $date = $this->input->get('ajuDate');
        $date2 = $this->input->get('ajuDate2');
        $model = $this->input->get('model');
        $nomoraju = $this->input->get('nomoraju');
        $rs = $this->conversion_test_data([
            'date' => $date, 'date2' => $date2, 'model' => $model, 'nomaju' => $nomoraju,
        ]);
        die(json_encode($rs));
    }
    public function conversion_test_as_xls()
    {
        $date = '';
        $date2 = '';
        $model = '';
        $nomoraju = '';
        if (isset($_COOKIE["CKPSI_DATE1"])) {
            $date = $_COOKIE["CKPSI_DATE1"];
        } else {
            exit('nothing to be exported');
        }
        $date2 = $_COOKIE["CKPSI_DATE2"];
        $model = $_COOKIE["CKPSI_MODEL"];
        $nomoraju = $_COOKIE["CKPSI_AJU"];
        $rs = $this->conversion_test_data([
            'date' => $date, 'date2' => $date2, 'model' => $model, 'nomaju' => $nomoraju,
        ]);
        $spreadsheet = new Spreadsheet();

        #INTERNAL SHEET
        $sheet = $spreadsheet->getActiveSheet();
        $sheet->setTitle('RESUME');
        $sheet->setCellValueByColumnAndRow(1, 3, 'Nomor Aju');
        $sheet->setCellValueByColumnAndRow(2, 3, 'Model Code');
        $sheet->setCellValueByColumnAndRow(3, 3, 'Model Qty');
        $sheet->setCellValueByColumnAndRow(4, 3, 'Part Code');
        $sheet->setCellValueByColumnAndRow(5, 3, 'Part Description');
        $sheet->setCellValueByColumnAndRow(6, 3, 'PER');
        $sheet->setCellValueByColumnAndRow(7, 3, 'Part Qty');
        $sheet->setCellValueByColumnAndRow(8, 3, 'Bom Revision');
        $i = 4;
        foreach ($rs['data'] as $r) {
            $sheet->setCellValueByColumnAndRow(1, $i, $r['DLV_ZNOMOR_AJU']);
            $sheet->setCellValueByColumnAndRow(2, $i, $r['SER_ITMID']);
            $sheet->setCellValueByColumnAndRow(3, $i, $r['DLVQT']);
            $sheet->setCellValueByColumnAndRow(4, $i, $r['SERD2_ITMCD']);
            $sheet->setCellValueByColumnAndRow(5, $i, $r['PARTDESCRIPTION']);
            $sheet->setCellValueByColumnAndRow(6, $i, $r['PER']);
            $sheet->setCellValueByColumnAndRow(7, $i, $r['RMQT']);
            $sheet->setCellValueByColumnAndRow(8, $i, $r['PPSN1_BOMRV']);
            $i++;
        }
        $i2 = $i;
        foreach ($rs['data_'] as $r) {
            $sheet->setCellValueByColumnAndRow(1, $i, $r['DLV_ZNOMOR_AJU']);
            $sheet->setCellValueByColumnAndRow(2, $i, $r['SER_ITMID']);
            $sheet->setCellValueByColumnAndRow(3, $i, $r['DLVQT']);
            $sheet->setCellValueByColumnAndRow(4, $i, $r['SERD2_ITMCD']);
            $sheet->setCellValueByColumnAndRow(5, $i, $r['PARTDESCRIPTION']);
            $sheet->setCellValueByColumnAndRow(6, $i, $r['PER']);
            $sheet->setCellValueByColumnAndRow(7, $i, $r['RMQT']);
            $sheet->setCellValueByColumnAndRow(8, $i, $r['PPSN1_BOMRV']);
            $i++;
        }
        if (count($rs['data_'])) {
            $sheet->getStyle('A' . $i2 . ':H' . ($i - 1))->getFill()->setFillType(\PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID)
                ->getStartColor()->setARGB('9EF9FF');
        }
        foreach (range('A', 'O') as $r) {
            $sheet->getColumnDimension($r)->setAutoSize(true);
        }

        #CEISA SHEET
        foreach ($rs['data_ceisa'] as &$r) {
            $r['PER'] = '';
            foreach ($rs['data'] as $m) {
                if (
                    $r['NOMOR_AJU'] === $m['DLV_ZNOMOR_AJU'] && $r['FG'] === $m['SER_ITMID']
                    && ($r['KODE_BARANG'] === $m['SERD2_ITMCD'] || $r['KODE_BARANG'] === $m['MITMGRP_ITMCD'])
                ) {
                    $r['PER'] = $m['PER'];
                    break;
                }
            }
        }
        unset($r);
        $sheet = $spreadsheet->createSheet();
        $sheet->setTitle('CEISA');
        if (!empty($rs['data_ceisa'])) {
            $sheet->fromArray(array_keys($rs['data_ceisa'][0]), null, 'A1');
            $sheet->fromArray($rs['data_ceisa'], null, 'A2');
        }

        $stringjudul = "Uji Konversi";
        $writer = new Xlsx($spreadsheet);
        $filename = $stringjudul; //save our workbook as this file name

        header('Content-Type: application/vnd.ms-excel');
        header('Content-Disposition: attachment;filename="' . $filename . '.xlsx"');
        header('Cache-Control: max-age=0');
        $writer->save('php://output');
    }

    public function conversion_test_data($params = [])
    {
        $rs = $this->SER_mod->select_conversion_test($params['date'], $params['date2'], $params['model'], $params['nomaju']);
        $arIndex = [];
        $arAJU = [];
        $arAJUUnique = [];
        $arFG = [];
        $rsCeisa = [];
        $arrayDeliveryOrderNumber = [];
        $i = 0;
        foreach ($rs as $r) {
            if (!in_array($r['DLV_ZNOMOR_AJU'], $arAJUUnique)) {
                $arAJUUnique[] = $r['DLV_ZNOMOR_AJU'];
                $arrayDeliveryOrderNumber[] = $r['DLV_ID'];
            }
            if (!$r['PER']) {
                $arIndex[] = $i;
                $arAJU[] = $r['DLV_ZNOMOR_AJU'];
                $arFG[] = $r['SER_ITMID'];
            }
            $i++;
        }

        $rsnull = count($arAJU) && count($arFG) ? $this->SER_mod->select_combine_byAju_and_FG($arAJU, $arFG) : [];
        $arrayBC = [];
        if (!empty($arrayDeliveryOrderNumber)) {
            $arrayBC = $this->ZRPSTOCK_mod->selectColumnsWhereRemarkIn($arrayDeliveryOrderNumber);
        }
        if (count($rs)) {
            foreach ($rs as &$r) {
                $r['PART_PRICE'] = null;
                $r['PLOTQT'] = 0;
                foreach ($arrayBC as &$b) {
                    # Jika item rank
                    if ($r['MITMGRP_ITMCD']) {
                        if ($r['DLV_ID'] === $b['RPSTOCK_REMARK'] && $r['MITMGRP_ITMCD'] === $b['RPSTOCK_ITMNUM'] && $b['BCQT'] > 0) {
                            $need = $r['RMQT'] - $r['PLOTQT'];
                            if ($need > $b['BCQT']) {
                                $r['PLOTQT'] += $b['BCQT'];
                                $b['BCQT'] = 0;
                            } else {
                                $r['PLOTQT'] += $need;
                                $b['BCQT'] -= $need;
                            }
                            $r['PART_PRICE'] = (float) $b['RCV_PRPRC'];
                            if ($r['RMQT'] == $r['PLOTQT']) {
                                break;
                            }
                        }
                    } else {
                        if ($r['DLV_ID'] === $b['RPSTOCK_REMARK'] && $r['SERD2_ITMCD'] === $b['RPSTOCK_ITMNUM'] && $b['BCQT'] > 0) {
                            $need = $r['RMQT'] - $r['PLOTQT'];
                            if ($need > $b['BCQT']) {
                                $r['PLOTQT'] += $b['BCQT'];
                                $b['BCQT'] = 0;
                            } else {
                                $r['PLOTQT'] += $need;
                                $b['BCQT'] -= $need;
                            }
                            $r['PART_PRICE'] = (float) $b['RCV_PRPRC'];
                            if ($r['RMQT'] == $r['PLOTQT']) {
                                break;
                            }
                        }
                    }
                }
                unset($b);
            }
            unset($r);
            $sort = [];
            foreach ($rs as $k => $v) {
                $sort['DLV_ZNOMOR_AJU'][$k] = $v['DLV_ZNOMOR_AJU'];
                $sort['SER_ITMID'][$k] = $v['SER_ITMID'];
            }
            array_multisort($sort['DLV_ZNOMOR_AJU'], SORT_ASC, $sort['SER_ITMID'], SORT_ASC, $rs);
            $rsCeisa = $this->TPB_HEADER_imod->select_uji_konversi([
                'A.NOMOR_AJU', 'B.KODE_BARANG FG', 'B.JUMLAH_SATUAN FGQTY', 'C.*',
            ], $arAJUUnique);
        }

        # Plot Combined RS
        foreach ($rsnull as &$r) {
            $r['PART_PRICE'] = null;
            $r['PLOTQT'] = 0;
            foreach ($arrayBC as &$b) {
                # Jika item rank
                if ($r['MITMGRP_ITMCD']) {
                    if ($r['DLV_ID'] === $b['RPSTOCK_REMARK'] && $r['MITMGRP_ITMCD'] === $b['RPSTOCK_ITMNUM'] && $b['BCQT'] > 0) {
                        $need = $r['RMQT'] - $r['PLOTQT'];
                        if ($need > $b['BCQT']) {
                            $r['PLOTQT'] += $b['BCQT'];
                            $b['BCQT'] = 0;
                        } else {
                            $r['PLOTQT'] += $need;
                            $b['BCQT'] -= $need;
                        }
                        $r['PART_PRICE'] = (float) $b['RCV_PRPRC'];
                        if ($r['RMQT'] == $r['PLOTQT']) {
                            break;
                        }
                    }
                } else {
                    if ($r['DLV_ID'] === $b['RPSTOCK_REMARK'] && $r['SERD2_ITMCD'] === $b['RPSTOCK_ITMNUM'] && $b['BCQT'] > 0) {
                        $need = $r['RMQT'] - $r['PLOTQT'];
                        if ($need > $b['BCQT']) {
                            $r['PLOTQT'] += $b['BCQT'];
                            $b['BCQT'] = 0;
                        } else {
                            $r['PLOTQT'] += $need;
                            $b['BCQT'] -= $need;
                        }
                        $r['PART_PRICE'] = (float) $b['RCV_PRPRC'];
                        if ($r['RMQT'] == $r['PLOTQT']) {
                            break;
                        }
                    }
                }
            }
            unset($b);
        }
        unset($r);
        return ['data' => $rs, 'data_' => $rsnull, 'data_ceisa' => $rsCeisa];
    }

    public function search_rm_in_fg()
    {
        header('Content-Type: application/json');
        $itemCD = $this->input->post('itemCD');
        $itemLOT = $this->input->post('itemLOT');
        $stock = $this->input->post('stock');
        $rs = $this->ITH_mod->select_rm_in_fg($itemCD, $itemLOT, $stock);
        $rsfix = [];
        $rsDelivered = [];
        foreach ($rs as $r) {
            if ($r['STKQTY'] > 0) {
                $rsfix[] = $r;
            } else {
                $isfound = false;
                foreach ($rsDelivered as &$d) {
                    if ($r['ITH_ITMCD'] === $d['ITH_ITMCD'] && $r['SERD2_JOB'] === $d['SERD2_JOB']) {
                        $isfound = true;
                        break;
                    }
                }
                unset($d);
                if (!$isfound) {
                    $rsDelivered[] = [
                        'ITH_SER' => '-',
                        'ITH_ITMCD' => $r['ITH_ITMCD'],
                        'MITM_ITMD1' => $r['MITM_ITMD1'],
                        'STKQTY' => 0,
                        'SERD2_JOB' => $r['SERD2_JOB'],
                        'ITH_WH' => $r['ITH_WH'],
                    ];
                }
            }
        }
        $rsfix = array_merge($rsfix, $rsDelivered);
        die('{"data":' . json_encode($rsfix) . '}');
    }

    public function removecalculation_byid()
    {
        header('Content-Type: application/json');
        $pin = $this->input->get('pin');
        $id = $this->input->get('id');
        $myar = [];
        if ($pin === 'WEAGREE_TOREMOVE') {
            $ret = $this->SERD_mod->deletebyID_label(['SERD2_SER' => $id]);
            $myar[] = $ret > 0 ? ['cd' => 1, 'msg' => 'deleted'] : ['cd' => 0, 'msg' => 'could not delete'];
        } else {
            $myar[] = ['cd' => 0, 'msg' => 'pin is not valid'];
        }
        die(json_encode(['data' => $myar]));
    }

    public function removeCalculationPerJob()
    {
        header('Content-Type: application/json');
        $id = $this->input->post('job');
        $AffectedRows = $this->SERD_mod->deletebyID(['SERD_JOB' => $id]);
        $result = $AffectedRows ? ['msg' => 'OK'] : ['msg' => 'Sorry could not reset'];
        die(json_encode($result));
    }
}
