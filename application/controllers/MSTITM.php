<?php
defined('BASEPATH') or exit('No direct script access allowed');
use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;
use WpOrg\Requests\Requests;

class MSTITM extends CI_Controller
{

    public function __construct()
    {
        parent::__construct();
        $this->load->model('MSTITM_mod');
        $this->load->model('MMDL_mod');
        $this->load->model('MITMSA_mod');
        $this->load->model('SER_mod');
        $this->load->model('RCV_mod');
        $this->load->model('DELV_mod');
        $this->load->model('MUM_mod');
        $this->load->model('MITMHSCD_HIS_mod');
        $this->load->helper('url');
        $this->load->helper('download');
        $this->load->library('session');
        date_default_timezone_set('Asia/Jakarta');
    }
    public function index()
    {
        echo "sorry";
    }

    public function createfg_exim()
    {
        $this->load->view('wms/vitmcd_fg_exim');
    }
    public function vbomcimsmega()
    {
        $this->load->view('wms_report/vbomcims_vs_mega');
    }
    public function form_rm_exim()
    {
        $this->load->view('wms/vitemcd_rm_exim');
    }

    public function sync_item_description1()
    {
        $affect = $this->MSTITM_mod->update_all_d1();
        $myar = [];
        if ($affect) {
            $myar[] = ['cd' => 1, 'msg' => $affect . ' updated'];
        } else {
            $myar[] = ['cd' => 0, 'msg' => $affect . ' updated'];
        }
        die('{"status": ' . json_encode($myar) . '}');
    }

    public function compare_bom_mega_cims()
    {
        header('Content-Type: application/json');
        $assycode = $this->input->post('assycode');
        $rev = $this->input->post('rev');
        $rs = $this->MSTITM_mod->select_bom_mega_cims($assycode, $rev);
        die('{"data":' . json_encode($rs) . '}');
    }

    public function form_sa()
    {
        $this->load->view('wms/vmitmsa');
    }

    public function get_sa()
    {
        header('Content-Type: application/json');
        $rs = $this->MITMSA_mod->selectAll();
        die('{"data":' . json_encode($rs) . '}');
    }

    public function create()
    {
        $rs = $this->MMDL_mod->select_all(['MMDL_CD', 'MMDL_NM']);
        $rsUM = $this->MUM_mod->selectAll();
        $strmdl = '';
        foreach ($rs as $r) {
            $strmdl .= "<option value='" . $r['MMDL_CD'] . "'>" . $r['MMDL_NM'] . "</option>";
        }
        $data['modell'] = $strmdl;
        $strmdl = '';
        foreach ($rsUM as $r) {
            $strmdl .= "<option value='" . $r['MUM_CD'] . "'>" . $r['MUM_NM'] . "</option>";
        }
        $data['UMl'] = $strmdl;
        $this->load->view('wms/vitemcd', $data);
    }

    public function create_oth()
    {
        $data['litem'] = $this->MSTITM_mod->selectother();
        $this->load->view('wms/vitemother', $data);
    }

    public function getall()
    {
        header('Content-Type: application/json');
        $rs = $this->MSTITM_mod->selectAll();
        echo '{"data":' . json_encode($rs) . '}';
    }

    public function getbyid()
    {
        header('Content-Type: application/json');
        $cid = $this->input->get('cid');
        $rs = $this->MSTITM_mod->selectbyid($cid);
        echo '{"data":' . json_encode($rs) . '}';
    }
    public function getbyid_desktop()
    {
        header('Content-Type: application/json');
        $cid = $this->input->get('cid');
        $rs = $this->MSTITM_mod->selectbyid($cid);
        $myar[] = count($rs) ? ['cd' => 1, 'msg' => 'go ahead'] : ['cd' => 0, 'msg' => 'not found'];
        echo '{"data":' . json_encode($rs) . ',"status":' . json_encode($myar) . '}';
    }

    public function getnamebyid()
    {
        header('Content-Type: application/json');
        $cid = $this->input->get('cid');
        $rs = $this->MSTITM_mod->selectnamebyid($cid);
        echo json_encode($rs);
    }

    public function search()
    {
        header('Content-Type: application/json');
        $cid = $this->input->get('cid');
        $csrchkey = $this->input->get('csrchby');
        $rs = [];
        switch ($csrchkey) {
            case 'ic':
                $rs = $this->MSTITM_mod->selectby_lk(['MITM_ITMCD' => $cid]);
                break;
            case 'in':
                $rs = $this->MSTITM_mod->selectby_lk(['MITM_ITMD1' => $cid]);
                break;
            case 'spt':
                $rs = $this->MSTITM_mod->selectby_lk(['MITM_SPTNO' => $cid]);
                break;
        }
        echo json_encode($rs);
    }
    public function search_itemlocation()
    {
        header('Content-Type: application/json');
        $cid = $this->input->get('cid');
        $csrchkey = $this->input->get('csrchby');
        $rs = [];
        switch ($csrchkey) {
            case 'ic':
                $rs = $this->MSTITM_mod->selectby_lk(['MITM_ITMCD' => $cid, 'MITM_MODEL' => 0]);
                break;
            case 'in':
                $rs = $this->MSTITM_mod->selectby_lk(['MITM_ITMD1' => $cid, 'MITM_MODEL' => 0]);
                break;
            case 'spt':
                $rs = $this->MSTITM_mod->selectby_lk(['MITM_SPTNO' => $cid, 'MITM_MODEL' => 0]);
                break;
        }
        echo json_encode($rs);
    }
    public function searchfg()
    {
        header('Content-Type: application/json');
        $cid = $this->input->get('cid');
        $csrchkey = $this->input->get('csrchby');
        $rs = array();
        switch ($csrchkey) {
            case 'ic':
                $rs = $this->MSTITM_mod->selectfgbyid_lk($cid);
                break;
            case 'in':
                $rs = $this->MSTITM_mod->selectfgbynm_lk($cid);
                break;
            case 'spt':
                $rs = $this->MSTITM_mod->selectfgbyspt_lk($cid);
                break;
        }
        echo json_encode($rs);
    }

    public function searchfg_exim()
    {
        header('Content-Type: application/json');
        $search = $this->input->get('insearch');
        $searchby = $this->input->get('insearchby');
        $rs = [];
        $responApi = Requests::request($_ENV['APP_INTERNAL_API'] . 'item/searchFGExim?insearch=' . $search
            . '&insearchby=' . $searchby, [], [], 'GET', ['timeout' => 900, 'connect_timeout' => 900]);
        $rs = json_decode($responApi->body, true);
        die(json_encode($rs));
    }
    public function searchrm_exim()
    {
        header('Content-Type: application/json');
        $search = $this->input->get('insearch');
        $searchby = $this->input->get('insearchby');
        $responApi = Requests::request($_ENV['APP_INTERNAL_API'] . 'item/searchRMExim?insearch=' . $search
            . '&insearchby=' . $searchby, [], [], 'GET', ['timeout' => 900, 'connect_timeout' => 900]);
        $rs = json_decode($responApi->body, true);
        die(json_encode($rs));
    }

    public function searchrm_exim_xls()
    {
        $search = $searchby = '';
        if (isset($_COOKIE["CKPSEARCH"])) {
            $search = $_COOKIE["CKPSEARCH"];
        } else {
            exit('nothing to be exported');
        }

        $search = $_COOKIE["CKPSEARCH"];
        $searchby = $_COOKIE["CKPSEARCH_BY"];
        $rs = [];
        switch ($searchby) {
            case 'itemcd':
                $rs = $this->MSTITM_mod->select_rm_exim(['MITM_ITMCD' => $search]);
                break;
            case 'itemnm':
                $rs = $this->MSTITM_mod->select_rm_exim(['MITM_ITMD1' => $search]);
                break;
        }
        $spreadsheet = new Spreadsheet();
        $sheet = $spreadsheet->getActiveSheet();
        $sheet->setTitle('master_hscode');
        $sheet->setCellValueByColumnAndRow(1, 1, 'Item Code');
        $sheet->setCellValueByColumnAndRow(2, 1, 'Item Name');
        $sheet->setCellValueByColumnAndRow(3, 1, 'HS Code');
        $sheet->setCellValueByColumnAndRow(4, 1, 'Net Weight');
        $sheet->setCellValueByColumnAndRow(5, 1, 'Gross Weight');
        $sheet->setCellValueByColumnAndRow(6, 1, 'BM');
        $sheet->setCellValueByColumnAndRow(7, 1, 'PPN');
        $sheet->setCellValueByColumnAndRow(8, 1, 'PPH');
        $n = 2;
        foreach ($rs as &$r) {
            $r['MITM_NWG'] = substr($r['MITM_NWG'], 0, 1) == '.' ? '0' . $r['MITM_NWG'] : $r['MITM_NWG'];
            $r['MITM_GWG'] = substr($r['MITM_GWG'], 0, 1) == '.' ? '0' . $r['MITM_GWG'] : $r['MITM_GWG'];
            $sheet->setCellValueByColumnAndRow(1, $n, $r['MITM_ITMCD']);
            $sheet->setCellValueByColumnAndRow(2, $n, $r['MITM_ITMD1']);
            $sheet->setCellValueByColumnAndRow(3, $n, $r['MITM_HSCD']);
            $sheet->setCellValueByColumnAndRow(4, $n, $r['MITM_NWG']);
            $sheet->setCellValueByColumnAndRow(5, $n, $r['MITM_GWG']);
            $sheet->setCellValueByColumnAndRow(6, $n, $r['MITM_BM']);
            $sheet->setCellValueByColumnAndRow(7, $n, $r['MITM_PPN']);
            $sheet->setCellValueByColumnAndRow(8, $n, $r['MITM_PPH']);
            $n++;
        }
        unset($r);
        foreach (range('A', 'H') as $v) {
            $sheet->getColumnDimension($v)->setAutoSize(true);
        }
        $sheet->getStyle('A1:A' . $n)->getAlignment()->setHorizontal('left');
        $stringjudul = "master hscode";
        $writer = new Xlsx($spreadsheet);
        $filename = $stringjudul; //save our workbook as this file name

        header('Content-Type: application/vnd.ms-excel');
        header('Content-Disposition: attachment;filename="' . $filename . '.xlsx"');
        header('Cache-Control: max-age=0');
        $writer->save('php://output');

    }
    public function searchfg_exim_xls()
    {
        $search = $searchby = '';
        if (isset($_COOKIE["CKPSEARCH"])) {
            $search = $_COOKIE["CKPSEARCH"];
        } else {
            exit('nothing to be exported');
        }

        $search = $_COOKIE["CKPSEARCH"];
        $searchby = $_COOKIE["CKPSEARCH_BY"];
        $rs = [];
        switch ($searchby) {
            case 'itemcd':
                $rs = $this->MSTITM_mod->select_fg_exim(['MITM_ITMCD' => $search]);
                break;
            case 'itemnm':
                $rs = $this->MSTITM_mod->select_fg_exim(['MITM_ITMD1' => $search]);
                break;
        }
        $spreadsheet = new Spreadsheet();
        $sheet = $spreadsheet->getActiveSheet();
        $sheet->setTitle('master_hscode');
        $sheet->setCellValueByColumnAndRow(1, 1, 'Item Code');
        $sheet->setCellValueByColumnAndRow(2, 1, 'Item Name');
        $sheet->setCellValueByColumnAndRow(3, 1, 'HS Code');
        $sheet->setCellValueByColumnAndRow(4, 1, 'Net Weight');
        $sheet->setCellValueByColumnAndRow(5, 1, 'Gross Weight');
        $sheet->setCellValueByColumnAndRow(6, 1, 'BM');
        $sheet->setCellValueByColumnAndRow(7, 1, 'PPN');
        $sheet->setCellValueByColumnAndRow(8, 1, 'PPH');
        $n = 2;
        foreach ($rs as &$r) {
            $r['MITM_NWG'] = substr($r['MITM_NWG'], 0, 1) == '.' ? '0' . $r['MITM_NWG'] : $r['MITM_NWG'];
            $r['MITM_GWG'] = substr($r['MITM_GWG'], 0, 1) == '.' ? '0' . $r['MITM_GWG'] : $r['MITM_GWG'];
            $sheet->setCellValueByColumnAndRow(1, $n, $r['MITM_ITMCD']);
            $sheet->setCellValueByColumnAndRow(2, $n, $r['MITM_ITMD1']);
            $sheet->setCellValueByColumnAndRow(3, $n, $r['MITM_HSCD']);
            $sheet->setCellValueByColumnAndRow(4, $n, $r['MITM_NWG']);
            $sheet->setCellValueByColumnAndRow(5, $n, $r['MITM_GWG']);
            $sheet->setCellValueByColumnAndRow(6, $n, $r['MITM_BM']);
            $sheet->setCellValueByColumnAndRow(7, $n, $r['MITM_PPN']);
            $sheet->setCellValueByColumnAndRow(8, $n, $r['MITM_PPH']);
            $n++;
        }
        unset($r);
        foreach (range('A', 'H') as $v) {
            $sheet->getColumnDimension($v)->setAutoSize(true);
        }
        $sheet->getStyle('A1:A' . $n)->getAlignment()->setHorizontal('left');
        $stringjudul = "master hscode fg";
        $writer = new Xlsx($spreadsheet);
        $filename = $stringjudul; //save our workbook as this file name

        header('Content-Type: application/vnd.ms-excel');
        header('Content-Disposition: attachment;filename="' . $filename . '.xlsx"');
        header('Cache-Control: max-age=0');
        $writer->save('php://output');

    }

    public function finddiff()
    {
        header('Content-Type: application/json');
        $rs = $this->MSTITM_mod->selectdiff();
        echo json_encode($rs);
    }

    public function setsync()
    {
        header('Content-Type: application/json');
        $cindex = $this->input->post('inx');
        $citem = $this->input->post('initem');

        $datas = ['MITM_ITMCD' => trim($citem)];
        $msg = "";
        if ($this->MSTITM_mod->check_Primary($datas) > 0) {
            $msg = "Already Synchronized";
        } else {
            $toret = $this->MSTITM_mod->insertsync($citem);
            if ($toret > 0) {
                $msg = "Synchronized";
            } else {
                $msg = "Try again";
            }
        }
        $anar = array("indx" => $cindex, "status" => $msg);
        $myar = array();
        array_push($myar, $anar);
        echo json_encode($myar);
    }

    public function checkSession()
    {
        $myar = [];
        if ($this->session->userdata('status') != "login") {
            $myar[] = ["cd" => 0, "msg" => "Session is expired please reload page"];
            die(json_encode($myar));
        }
    }

    public function set()
    {
        $this->checkSession();
        $currrtime = date('Y-m-d H:i:s');
        $cid = $this->input->post('initmcd');
        $cid_old = $this->input->post('initmcd_old');
        $mitmcd_Ext = $this->input->post('mitmcd_Ext');
        $cnm1 = $this->input->post('initmnm1');
        $cnm2 = $this->input->post('initmnm2');
        $cspt = $this->input->post('inspt');
        $ctype = $this->input->post('intype');
        $cisdirect = $this->input->post('inisdirect');
        $chscode = $this->input->post('inhscode');
        $chscodet = $this->input->post('inhscodet');
        $cisdirect = $this->input->post('inisdirect');
        $cnet = $this->input->post('innetwg');
        $cgrs = $this->input->post('ingrswg');

        $cbox = $this->input->post('inbox');
        $cspq = $this->input->post('inspq') == '' ? 0 : $this->input->post('inspq');
        $cheight = $this->input->post('inheight');
        $clength = $this->input->post('inlength');
        $ccolor = $this->input->post('incolor');
        $cshtqty = $this->input->post('inshtqty');
        $incategory = $this->input->post('incategory');
        $mstkuom = $this->input->post('mstkuom');
        $datac = ['MITM_ITMCD' => $cid];
        $myar = [];
        if ($this->MSTITM_mod->check_Primary($datac) == 0) {
            $datas = [
                'MITM_ITMCD' => $cid,
                'MITM_ITMD1' => $cnm1,
                'MITM_ITMD2' => $cnm2,
                'MITM_SPTNO' => $cspt,
                'MITM_MODEL' => $ctype,
                'MITM_LUPDT' => $currrtime,
                'MITM_USRID' => $this->session->userdata('nama'),
                'MITM_INDIRMT' => $cisdirect,
                'MITM_HSCD' => $chscode,
                'MITM_HSCODET' => $chscodet,
                'MITM_GWG' => $cgrs,
                'MITM_NWG' => $cnet,
                'MITM_BOXTYPE' => $cbox,
                'MITM_SPQ' => str_replace(',', '', $cspq),
                'MITM_MXHEIGHT' => $cheight,
                'MITM_MXLENGTH' => $clength,
                'MITM_LBLCLR' => $ccolor,
                'MITM_SHTQTY' => $cshtqty,
            ];
            $toret = $this->MSTITM_mod->insert($datas);
            $myar[] = $toret > 0 ? ['cd' => 1, 'msg' => 'Saved successfully'] : ['cd' => 0, 'msg' => 'Could not saved'];
        } else {
            $datau = [
                'MITM_BOXTYPE' => $cbox,
                'MITM_SPQ' => str_replace(',', '', $cspq),
                'MITM_MXHEIGHT' => $cheight,
                'MITM_MXLENGTH' => $clength,
                'MITM_LBLCLR' => $ccolor,
                'MITM_SHTQTY' => $cshtqty,
                'MITM_NCAT' => $incategory,
                'MITM_ITMCDCUS' => $mitmcd_Ext,
            ];
            if (in_array($this->session->userdata('gid'), ['ROOT', 'ADMIN']) || $ctype == '6') {
                $datau['MITM_ITMD1'] = $cnm1;
                $datau['MITM_ITMD2'] = $cnm2;
                $datau['MITM_STKUOM'] = $mstkuom;
                $datau['MITM_SPTNO'] = $cspt;
                $datau['MITM_ITMCD'] = $cid;
            }
            if (in_array($this->session->userdata('gid'), ['EXMS', 'EXMV'])) {
                $datau['MITM_ITMD2'] = $cnm2;
            }
            $toret = $this->MSTITM_mod->updatebyId($datau, $cid_old);
            $myar[] = $toret > 0 ? ['cd' => 1, 'msg' => 'Updated successfully'] : ['cd' => 0, 'msg' => 'Could not updated'];
        }
        die(json_encode(['status' => $myar]));
    }

    public function getlinkitemtemplate()
    {
        $murl = ((isset($_SERVER['HTTPS']) && $_SERVER['HTTPS'] == "on") ? "https" : "http");
        $murl .= "://" . $_SERVER['HTTP_HOST'];
        echo $murl . "/wms/MSTITM/downloadtemplate";
    }

    public function downloadtemplate()
    {
        $theurl = 'assets/userxls_template/tmpl_item.xls';
        force_download($theurl, null);
        echo $theurl;
    }

    public function getboxlist()
    {
        header('Content-Type: application/json');
        $ckeys = $this->input->get('inkeys');
        $rs = $this->MSTITM_mod->selectboxes($ckeys);
        echo json_encode($rs);
    }

    public function import()
    {
        header('Content-Type: application/json');
        $currrtime = date('Y-m-d H:i:s');
        $citem = $this->input->post('initem');
        $cname = $this->input->post('innm');
        $csprtno = $this->input->post('insprtno');
        $cum = $this->input->post('inum');
        $cisdirect = $this->input->post('inisdirect');
        $chscd = $this->input->post('inhscd');
        $chscdt = $this->input->post('inhscdt');
        $cmodel = $this->input->post('inmodel');
        $cnet = $this->input->post('innet');
        $cgrs = $this->input->post('ingrs');
        $cboxtype = $this->input->post('inboxtype');
        $cspq = $this->input->post('inspq');
        $cspq = (is_numeric($cspq) ? $cspq : 0);
        $cmxheight = $this->input->post('inmxheight');
        $cmxlength = $this->input->post('inmxlength');
        $cmodel = $this->input->post('inmodel');
        $cshtqty = $this->input->post('inshtqty');
        $ccolor = $this->input->post('incolor');
        $crowid = $this->input->post('inrowid');
        $datac = array('MITM_ITMCD' => $citem);
        $cnet = (is_numeric($cnet) ? $cnet : 0);
        $cgrs = (is_numeric($cgrs) ? $cgrs : 0);
        $myar = array();
        if ($this->MSTITM_mod->check_Primary($datac) == 0) {
            $datas = array(
                'MITM_ITMCD' => $citem, 'MITM_ITMD1' => $cname, 'MITM_SPTNO' => $csprtno, 'MITM_STKUOM' => $cum, 'MITM_INDIRMT' => $cisdirect,
                'MITM_HSCD' => $chscd, 'MITM_HSCODET' => $chscdt, 'MITM_MODEL' => $cmodel,
                'MITM_NWG' => $cnet, 'MITM_GWG' => $cgrs, 'MITM_BOXTYPE' => $cboxtype, 'MITM_SPQ' => $cspq, 'MITM_MXHEIGHT' => $cmxheight, 'MITM_MXLENGTH' => $cmxlength,
                'MITM_SHTQTY' => $cshtqty, 'MITM_LBLCLR' => $ccolor,
                'MITM_LUPDT' => $currrtime, 'MITM_USRID' => $this->session->userdata('nama'),
            );
            $toret = $this->MSTITM_mod->insert($datas);
            if ($toret > 0) {
                $anar = array("indx" => $crowid, "status" => 'Saved successfully');
            } else {
                $anar = array("indx" => $crowid, "status" => 'Could not insert');
            }
        } else {
            $datau = array(
                'MITM_BOXTYPE' => $cboxtype, 'MITM_SPQ' => $cspq,
                'MITM_MXHEIGHT' => $cmxheight, 'MITM_MXLENGTH' => $cmxlength, 'MITM_SHTQTY' => $cshtqty, 'MITM_LBLCLR' => $ccolor,
            );
            $toret = $this->MSTITM_mod->updatebyId($datau, $citem);
            if ($toret > 0) {
                $anar = array("indx" => $crowid, "status" => 'Updated');
            } else {
                $anar = array("indx" => $crowid, "status" => 'Could not update');
            }

        }
        array_push($myar, $anar);
        echo json_encode($myar);
    }

    public function register()
    {
        header('Content-Type: application/json');
        $current_Date = date('Y-m-d');
        $itemcd = trim($this->input->post('itmcd'));
        $itmcd_ext = trim($this->input->post('itmcd_ext'));
        $itmnm = $this->input->post('itmnm');
        $itmunitmeasure = $this->input->post('itmunitmeasure');
        $itmconsignee = trim($this->input->post('itmconsignee'));
        $myar = [];
        if (strlen($itemcd) == 0) {
            if (strlen($itmconsignee) == 0) {
                $myar[] = ['cd' => 0, 'msg' => 'Consignee is required'];
            } else {
                $lastno = $this->MSTITM_mod->select_lastnopm($itmconsignee);
                $newitem = 'PM' . $itmconsignee . date('Y') . '-' . substr('000000' . $lastno, -6);
                $this->MSTITM_mod->insert([
                    'MITM_ITMCD' => $newitem, 'MITM_ITMD1' => $itmnm, 'MITM_ITMCDCUS' => $itemcd
                    , 'MITM_MODEL' => '6', 'MITM_STKUOM' => $itmunitmeasure, 'MITM_PMNO' => $lastno
                    , 'MITM_ITMCDCUS' => $itmcd_ext, 'MITM_PMREGDT' => $current_Date, 'MITM_PMCONSIGN' => $itmconsignee,
                ]);
                $myar[] = ['cd' => 1, 'msg' => 'registered successfully', 'newcd' => $newitem];
            }
        } else {
            if ($this->MSTITM_mod->check_Primary(['MITM_ITMCD' => $itemcd])) {
                $myar[] = ['cd' => 0, 'msg' => 'The item is already registered'];
            } else {
                $insert = $this->MSTITM_mod->insert(['MITM_ITMCD' => $itemcd, 'MITM_ITMD1' => $itmnm, 'MITM_ITMCDCUS' => $itemcd
                    , 'MITM_MODEL' => '6', 'MITM_STKUOM' => $itmunitmeasure]);
                $myar[] = ['cd' => 1, 'msg' => 'registered successfully', 'newcd' => $itemcd];
            }
        }
        die(json_encode(['status' => $myar]));
    }

    public function checkexist()
    {
        $citem = $this->input->get('initem');
        $citem = trim($citem);
        $myar = [];
        if ($this->MSTITM_mod->check_Primary(["MITM_ITMCD" => $citem]) > 0) {
            $anar = ["cd" => "11", "msg" => 'go ahead'];
        } else {
            $anar = ["cd" => "00", "msg" => 'Item is not registered'];
        }
        $myar[] = $anar;
        $rswo = $this->SER_mod->select_joblbl_ost_pitem($citem);
        echo '{"data":';
        echo json_encode($myar);
        echo ',"datahead":';
        echo json_encode($rswo);
        echo '}';
    }

    public function downloadsa()
    {
        $rs = $this->MITMSA_mod->selectAll();
        $spreadsheet = new Spreadsheet();
        $sheet = $spreadsheet->getActiveSheet();
        $sheet->setTitle('registered_part');
        $sheet->setCellValueByColumnAndRow(1, 1, 'Assy Code');
        $sheet->setCellValueByColumnAndRow(2, 1, 'Item Code');
        $sheet->setCellValueByColumnAndRow(3, 1, 'Item Code SA');
        $i = 2;
        foreach ($rs as $r) {
            $sheet->setCellValueByColumnAndRow(1, $i, $r['FG']);
            $sheet->setCellValueByColumnAndRow(2, $i, $r['MITMSA_ITMCD']);
            $sheet->setCellValueByColumnAndRow(3, $i, $r['MITMSA_ITMCDS']);
            $i++;
        }
        foreach (range('A', 'C') as $v) {
            $sheet->getColumnDimension($v)->setAutoSize(true);
        }
        $rang = "A1:C" . $sheet->getHighestDataRow();
        $sheet->getStyle($rang)->getAlignment()->setHorizontal(\PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_LEFT);

        $stringjudul = "registered SA Part";
        $writer = new Xlsx($spreadsheet);
        $filename = $stringjudul; //save our workbook as this file name

        header('Content-Type: application/vnd.ms-excel');
        header('Content-Disposition: attachment;filename="' . $filename . '.xlsx"');
        header('Cache-Control: max-age=0');
        $writer->save('php://output');
    }

    public function sync_hscode_mega()
    {
        header('Content-Type: application/json');
        $rs = $this->MSTITM_mod->selecthscodemega();
        foreach ($rs as $r) {
            $datas = ['MITM_HSCD' => trim($r['MITM_HSCD']), 'MITM_ITMD1' => trim($r['MITM_ITMD1'])
                , 'MITM_SPTNO' => trim($r['MITM_SPTNO']), 'MITM_STKUOM' => trim($r['MITM_STKUOM']),
            ];
            $this->MSTITM_mod->updatebyId($datas, trim($r['MITM_ITMCD']));
        }
        echo "ok";
    }

    public function uploadSA()
    {
        header('Content-Type: application/json');
        $arm_assycd = $this->input->post('arm_assycd');
        $arm_itemCd = $this->input->post('arm_itemCd');
        $arm_itemCdsa = $this->input->post('arm_itemCdsa');
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, 'http://192.168.0.29:8081/wms/MSTITM/saveSA');
        curl_setopt($ch, CURLOPT_POST, true);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
        curl_setopt($ch, CURLOPT_CONNECTTIMEOUT, 0);
        curl_setopt($ch, CURLOPT_TIMEOUT, 3600);
        $ttlrows = 0;
        if (is_array($arm_assycd)) {
            $ttlrows = count($arm_assycd);
        }
        $aresult = [];
        if ($ttlrows) {
            for ($i = 0; $i < $ttlrows; $i++) {
                $fields = [
                    'newassy' => $arm_assycd[$i],
                    'newpart' => $arm_itemCd[$i],
                    'newpartSA' => $arm_itemCdsa[$i],
                ];
                $fields_string = http_build_query($fields);
                curl_setopt($ch, CURLOPT_POSTFIELDS, $fields_string);
                $data = curl_exec($ch);
                $datad = json_decode($data);
                foreach ($datad->status as $n) {
                    $fields['status'] = $n->msg;
                }
                $aresult[] = $fields;
            }
        }
        echo json_encode(['data' => $aresult]);
        curl_close($ch);
    }

    public function saveSA()
    {
        header('Content-Type: application/json');
        $currrtime = date('Y-m-d H:i:s');
        $oldassy = $this->input->post('oldassy');
        $oldpart = $this->input->post('oldpart');
        $oldpartSA = $this->input->post('oldpartSA');

        $newassy = $this->input->post('newassy');
        $newpart = $this->input->post('newpart');
        $newpartSA = $this->input->post('newpartSA');
        $remark = $this->input->post('remark');

        if ($this->MSTITM_mod->check_Primary(['MITM_ITMCD' => $newassy]) == 0) {
            $myar[] = ['cd' => '0', 'msg' => 'Assy Code is not found'];
            die('{"status":' . json_encode($myar) . '}');
        }
        if ($this->MSTITM_mod->check_Primary(['MITM_ITMCD' => $newpart]) == 0) {
            $myar[] = ['cd' => '0', 'msg' => 'Part Code is not found'];
            die('{"status":' . json_encode($myar) . '}');
        }
        if ($this->MSTITM_mod->check_Primary(['MITM_ITMCD' => $newpartSA]) == 0) {
            $myar[] = ['cd' => '0', 'msg' => 'Part Code SA is not found'];
            die('{"status":' . json_encode($myar) . '}');
        }
        $data = [
            'MITMSA_ITMCD' => trim($newpart)
            , 'MITMSA_ITMCDS' => $newpartSA
            , 'MITMSA_MDLCD' => $newassy,
        ];
        if ($this->MITMSA_mod->check_Primary($data)) {
            $myar[] = ['cd' => '0', 'msg' => 'The data is already exist'];
            die('{"status":' . json_encode($myar) . '}');
        } else {
            $data['MITMSA_LUPDT'] = $currrtime;
            $data['MITMSA_USRID'] = $this->session->userdata('nama');
            $data['MITMSA_RMRK'] = $remark;
            $this->MITMSA_mod->insert($data);
            $myar[] = ['cd' => '1', 'msg' => 'Saved successfully'];
            die('{"status":' . json_encode($myar) . '}');
        }
    }

    public function remove()
    {
        header('Content-Type: application/json');
        $itm = $this->input->post('itm');
        $myar = [];
        if ($this->MSTITM_mod->check_Primary_unused_item(['MITM_ITMCD' => $itm])) {
            $myar[] = $this->MSTITM_mod->deleteby_filter(['MITM_ITMCD' => $itm, 'MITM_MODEL' => '6']) ?
            ['cd' => 1, 'msg' => 'deleted'] : ['cd' => 0, 'msg' => 'could not be deleted'];
        } else {
            $myar[] = ['cd' => 0, 'msg' => 'sorry, the data could not be deleted.'];
        }
        die(json_encode(['status' => $myar]));
    }

    public function removeSA()
    {
        header('Content-Type: application/json');
        $currrtime = date('Y-m-d H:i:s');
        $assyCDold = $this->input->post('assyCDold');
        $partCDold = $this->input->post('partCDold');
        $partCDSAold = $this->input->post('partCDSAold');
        $userid = $this->session->userdata('nama');
        $data = [
            'MITMSA_ITMCD' => $partCDold
            , 'MITMSA_ITMCDS' => $partCDSAold
            , 'MITMSA_MDLCD' => $assyCDold,
        ];
        if ($this->MITMSA_mod->check_Primary($data)) {
            $this->MITMSA_mod->delete_where($userid, $currrtime, $data);
            $myar[] = ['cd' => '1', 'msg' => 'Deleted'];
        } else {
            $myar[] = ['cd' => '0', 'msg' => 'the data is not found'];
        }
        die('{"status":' . json_encode($myar) . '}');
    }

    public function search_internal_item()
    {
        header('Content-Type: application/json');
        $searchBy = $this->input->get('searchBy');
        $search = $this->input->get('search');
        $ITH_WH = $this->input->get('ITH_WH');
        $rs = [];
        switch ($searchBy) {
            case 'in':
                $rs = $this->MSTITM_mod->select_columns_like_with_stock(['RTRIM(MITM_ITMCD) MITM_ITMCD', 'RTRIM(MITM_ITMD1) MITM_ITMD1', 'MITM_ITMCDCUS', "RTRIM(ISNULL(MITM_STKUOM,'')) MITM_STKUOM", "ISNULL(STOCKQT,0) STOCKQT"],
                    ['MITM_ITMD1' => $search],
                    $ITH_WH);
                break;
            case 'ic':
                $rs = $this->MSTITM_mod->select_columns_like_with_stock(['RTRIM(MITM_ITMCD) MITM_ITMCD', 'RTRIM(MITM_ITMD1) MITM_ITMD1', 'MITM_ITMCDCUS', "RTRIM(ISNULL(MITM_STKUOM,'')) MITM_STKUOM", "ISNULL(STOCKQT,0) STOCKQT"],
                    ['MITM_ITMCD' => $search],
                    $ITH_WH);
                break;
            case 'po':
                $rs = $this->RCV_mod->selectItemLikeWithStock(['RCV_PO' => $search], $ITH_WH);
                break;
            case 'do':
                $rs = $this->RCV_mod->selectItemLikeWithStock(['RCV_DONO' => $search], $ITH_WH);
                break;
            default:
                $rs = $this->MSTITM_mod->select_columns_like_with_stock(['RTRIM(MITM_ITMCD) MITM_ITMCD', 'RTRIM(MITM_ITMD1) MITM_ITMD1', 'MITM_ITMCDCUS', "RTRIM(ISNULL(MITM_STKUOM,'')) MITM_STKUOM"], ['MITM_ITMCDCUS' => $search]);
        }
        die(json_encode(['data' => $rs]));
    }

    public function fg_exim()
    {
        header('Content-Type: application/json');
        $itemcd = $this->input->post('itemcd');
        $itemhscd = $this->input->post('itemhscd');
        $netweight = $this->input->post('netweight');
        $grosweight = $this->input->post('grosweight');
        $beamasuk = trim($this->input->post('beamasuk'));
        $ppn = trim($this->input->post('ppn'));
        $pph = trim($this->input->post('pph'));
        $ret = $this->MSTITM_mod->updatebyId([
            'MITM_HSCD' => $itemhscd,
            'MITM_NWG' => $netweight == 'null' ? null : $netweight,
            'MITM_BOXWEIGHT' => $grosweight == 'null' ? null : $grosweight,
            'MITM_BM' => $beamasuk,
            'MITM_PPN' => $ppn,
            'MITM_PPH' => $pph,
            'MITM_LUPDT' => date('Y-m-d H:i:s'),
        ]
            , $itemcd);
        if ($ret) {
            $myar[] = ['cd' => 1, 'msg' => 'OK'];
        } else {
            $myar[] = ['cd' => 0, 'msg' => 'Could not update'];
        }
        die(json_encode(['status' => $myar]));
    }

    public function updateFGExim()
    {
        header('Content-Type: application/json');
        $itemcd = $this->input->post('ItemCode');
        $itemhscd = $this->input->post('HSCode');
        $netweight = $this->input->post('NetWeight');
        $grosweight = $this->input->post('BoxWeight');
        $BM = $this->input->post('BM');
        $PPN = $this->input->post('PPN');
        $PPH = $this->input->post('PPH');
        if (!is_array($itemcd)) {
            die(json_encode(['message' => 'Input is not valid']));
        }
        $ttlRows = count($itemcd);

        for ($i = 0; $i < $ttlRows; $i++) {
            $this->saveHistoryHSCODE(['ITEMCD' => $itemcd[$i]]);
            $affectedRow = $this->MSTITM_mod->updatebyIdAndModel([
                'MITM_HSCD' => $itemhscd[$i],
                'MITM_NWG' => $netweight[$i] == 'null' ? null : $netweight[$i],
                'MITM_BOXWEIGHT' => $grosweight[$i] == 'null' ? null : $grosweight[$i],
                'MITM_BM' => $BM[$i],
                'MITM_PPN' => $PPN[$i],
                'MITM_PPH' => $PPH[$i],
                'MITM_LUPDT' => date('Y-m-d H:i:s'),
                'MITM_USRID' => $this->session->userdata('nama'),
            ], $itemcd[$i], '1');
            if ($affectedRow) {
                $message = 'updated';
            } else {
                $message = 'could not be updated';
            }
            $data[] = ['ItemCode' => $itemcd[$i], 'message' => $message];
        }

        die(json_encode(['message' => 'Processed', 'data' => $data]));

    }

    public function saveHistoryHSCODE($data)
    {
        $rsbefore = $this->MSTITM_mod->selectbyid($data['ITEMCD']);
        foreach ($rsbefore as $r) {
            $lastLine = $this->MITMHSCD_HIS_mod->lastserialid($data['ITEMCD']) + 1;
            $this->MITMHSCD_HIS_mod->insert([
                'MITMHSCD_HIS_ITMCD' => $r->MITM_ITMCD,
                'MITMHSCD_HIS_HSCD' => $r->MITM_HSCD,
                'MITMHSCD_HIS_BM' => $r->MITM_BM,
                'MITMHSCD_HIS_PPN' => $r->MITM_PPN,
                'MITMHSCD_HIS_PPH' => $r->MITM_PPH,
                'MITMHSCD_HIS_UPDATED_AT' => $r->MITM_LUPDT,
                'MITMHSCD_HIS_UPDATED_BY' => $r->MITM_USRID,
                'MITMHSCD_HIS_LINE' => $lastLine,
            ]);
        }
    }

    public function rm_exim()
    {
        header('Content-Type: application/json');
        $itemcd = $this->input->post('itemcd');
        $itemhscd = $this->input->post('itemhscd');
        $netweight = $this->input->post('netweight');
        $grosweight = $this->input->post('grosweight');
        $beamasuk = $this->input->post('beamasuk');
        $ppn = $this->input->post('ppn');
        $pph = $this->input->post('pph');
        $currrtime = date('Y-m-d H:i:s');
        #record old data
        $this->saveHistoryHSCODE(['ITEMCD' => $itemcd]);

        $ret = $this->MSTITM_mod->updatebyId([
            'MITM_HSCD' => $itemhscd,
            'MITM_BM' => $beamasuk,
            'MITM_PPN' => $ppn,
            'MITM_PPH' => $pph,
            'MITM_LUPDT' => $currrtime,
            'MITM_USRID' => $this->session->userdata('nama'),
        ]
            , $itemcd);
        if ($ret) {
            $retrcv = $this->RCV_mod->update_itemcustoms_prop_by_itemcd($itemcd, $itemhscd);
            $myar[] = ['cd' => 1, 'msg' => 'OK', 'reff' => $retrcv];
        } else {
            $myar[] = ['cd' => 0, 'msg' => 'Could not update'];
        }
        die(json_encode(['status' => $myar]));
    }

    public function form_new_item()
    {
        $rsUM = $this->MUM_mod->selectAll();
        $strmdl = '';
        $data['modell'] = $strmdl;
        foreach ($rsUM as $r) {
            $strmdl .= "<option value='" . $r['MUM_CD'] . "'>" . $r['MUM_NM'] . "</option>";
        }
        $data['UMl'] = $strmdl;
        $data['ldeliverycode'] = $this->DELV_mod->select_delv_code();
        $this->load->view('wms/vitem_reg', $data);
    }

    public function form_process()
    {       
        $this->load->view('wms/vitem_process_master');
    }

    public function updatencat()
    {
        header('Content-Type: application/json');
        $i = $this->input->post('i');
        $item_code = $this->input->post('item_code');
        $category = $this->input->post('category');
        $ret = $this->MSTITM_mod->updatebyId(['MITM_NCAT' => trim($category)], $item_code);
        $myar = [];
        $myar[] = $ret ? ['cd' => 1, 'msg' => 'OK', 'reff' => $i * 1] : ['cd' => 0, 'msg' => 'not ok', 'reff' => $i * 1];
        die(json_encode(['status' => $myar]));
    }

    public function import_hscode()
    {
        header('Content-Type: application/json');
        $itemcode = $this->input->post('itemcode');
        $hscode = $this->input->post('hscode');
        if (!empty($hscode)) {
            $affected = $this->MSTITM_mod->updatebyId(['MITM_HSCD' => $hscode], $itemcode);
            $myar[] = $affected ? ['cd' => '1', 'msg' => 'ok'] : ['cd' => '0', 'msg' => 'could not update'];
        }
        die(json_encode(['status' => $myar]));
    }
}
