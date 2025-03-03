<?php
defined('BASEPATH') or exit('No direct script access allowed');

use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;

class SI extends CI_Controller
{
    public function __construct()
    {
        parent::__construct();
        $this->load->helper('url');
        $this->load->library('session');
        $this->load->model('SI_mod');
        $this->load->model('SISCN_mod');
        $this->load->model('MSTCUS_mod');
        $this->load->model('TXROUTE_mod');
        $this->load->model('MSTITM_mod');
        $this->load->model('ITH_mod');
        $this->load->model('XSO_mod');
        $this->load->model('DELV_mod');
        $this->load->model('MSLSPRC_mod');
        $this->load->model('XBGROUP_mod');
        $this->load->model('SISO_mod');
        $this->load->model('MSTLOCG_mod');
        $this->load->library('Code39e128');
    }
    public function index()
    {
        echo "sorry";
    }

    public function create()
    {
        $data['lcus'] = $this->XSO_mod->select_epro_customer();
        $rs           = $this->MSTLOCG_mod->selectall_by_dedict('FGWH');
        $lwh          = '';
        foreach ($rs as $r) {
            $lwh .= "<option value='" . $r['MSTLOCG_ID'] . "'>" . $r['MSTLOCG_ID'] . "</option>";
        }
        $data['lwh'] = $lwh;
        $this->load->view('wms/vshippinginfo', $data);
    }

    public function createoth()
    {
        $data['lbg'] = $this->XBGROUP_mod->selectall();
        $rs          = $this->MSTLOCG_mod->selectall_by_dedict('FGWH');
        $lwh         = '';
        foreach ($rs as $r) {
            $lwh .= "<option value='" . $r['MSTLOCG_ID'] . "'>" . $r['MSTLOCG_ID'] . "</option>";
        }
        $data['lwh'] = $lwh;
        $this->load->view('wms/vshippinginfooth', $data);
    }

    public function scan()
    {
        $this->load->view('wms/vshippinginfo_scan');
    }

    public function get_customer_ost_so()
    {
        $cbg  = $this->input->get('inbg');
        $rs   = $this->XSO_mod->selectcustomer_ost_so($cbg);
        $myar = [];
        if (count($rs) > 0) {
            $myar[] = ['cd' => '1', 'msg' => 'go ahead'];
        } else {
            $myar[] = ['cd' => '0', 'msg' => 'not found'];
        }
        die('{"status" : ' . json_encode($myar) . ', "data": ' . json_encode($rs) . '}');
    }

    public function tes()
    {
        $ccustomer = 'SME007U';
        $cbg       = 'PSI1PPZIEP';
        $columns   = ['SSO2_CPONO', 'SSO2_ORDQT', 'SSO2_DELQT', 'SSO2_DELCD', 'SSO2_MDLCD', 'SSO2_SLPRC', 'SSO2_SOLNO'];
        $where     = ['SSO2_BSGRP' => $cbg, 'SSO2_CUSCD' => $ccustomer, 'SSO2_CPOTYPE' => 'CPO', 'SSO2_COMFG' => '1'];
        $citems    = ['222093300'];
        $rs        = $this->XSO_mod->selectbyVAR_inmodel($columns, $where, $citems);
        die(json_encode($rs));
    }

    public function get_ostso()
    {
        date_default_timezone_set('Asia/Jakarta');
        $current_date = date('Y-m-d');
        $current_time = date('H:i:s');
        $fixdate      = $current_date;
        $fixY         = substr($fixdate, 0, 4);
        $fixM         = substr($fixdate, 5, 2);
        if ($current_time < '07:00:00') {
            $fixdate = strtotime($current_date . '-1 days');
            $fixdate = date('Y-m-d', $fixdate);
        }
        $cbg       = trim($this->input->get('inbg'));
        $ccustomer = $this->input->get('incustomer');
        $citems    = $this->input->get('initems');
        $wh        = $this->input->get('wh');
        $columns   = ['SSO2_CPONO', 'SSO2_ORDQT', 'SSO2_DELQT', 'SSO2_DELCD', 'SSO2_MDLCD', 'SSO2_SLPRC', 'SSO2_SOLNO'];
        $where     = ['SSO2_BSGRP' => $cbg, 'SSO2_CUSCD' => $ccustomer, 'SSO2_CPOTYPE' => 'CPO', 'SSO2_COMFG' => '0'];
        $rs        = $this->XSO_mod->selectbyVAR_inmodel($columns, $where, $citems);
        $myar      = [];
        $ddata     = [];
        $ddatadiff = [];
        if ($cbg == 'PSI1PPZIEP' && $ccustomer == 'SME007U') {
            if (count($rs) > 0) {
                $itemready = [];
                $itmecount = count($citems);
                $cpo       = '';
                foreach ($rs as &$r) {
                    $cpo             = trim($r['SSO2_CPONO']);
                    $r['SSO2_SLPRC'] = substr($r['SSO2_SLPRC'], 0, 1) == '.' ? '0' . $r['SSO2_SLPRC'] : $r['SSO2_SLPRC'];
                    for ($i = 0; $i < $itmecount; $i++) {
                        if ($citems[$i] == trim($r['SSO2_MDLCD'])) {
                            if (! in_array($citems[$i], $itemready)) {
                                $itemready[] = $citems[$i];
                            }
                        }
                    }
                }
                unset($r);

                $ddata      = $itemready;
                $a_diff     = array_values(array_diff($citems, $itemready));
                $ddatadiff  = $a_diff;
                $a_diff_cnt = count($a_diff);
                if ($a_diff_cnt > 0) {
                    $list = "";
                    for ($i = 0; $i < $a_diff_cnt; $i++) {
                        $list .= "'$a_diff[$i]',";
                    }
                    $list         = substr($list, 0, strlen($list) - 1);
                    $rs_mst_price = $wh == 'NFWH4RT' ? $this->XSO_mod->select_latestprice($cbg, $ccustomer, $list) : $this->XSO_mod->select_latestprice_period($cbg, $ccustomer, $list, $fixY, $fixM);
                    foreach ($rs_mst_price as $r) {
                        $rs[] = [
                            'SSO2_CPONO' => $cpo, 'SSO2_ORDQT' => 1, 'SSO2_DELQT' => 0, 'SSO2_DELCD' => null, 'SSO2_MDLCD' => $r['MSPR_ITMCD'], 'SSO2_SLPRC' => substr($r['MSPR_SLPRC'], 0, 1) == '.' ? '0' . $r['MSPR_SLPRC'] : $r['MSPR_SLPRC'], 'SSO2_SOLNO' => 'X',
                        ];
                    }
                }
                $myar[] = ['cd' => '1', 'msg' => 'go ahead.'];
            } else {
                $rs = $this->XSO_mod->selectbyVAR_inmodel_lastso($columns, $citems);
                if (! empty($rs)) {
                    foreach ($rs as &$r) {
                        $r['SSO2_ORDQT'] = 1;
                        $r['SSO2_DELQT'] = 0;
                        $r['SSO2_SOLNO'] = 'X';
                    }
                    unset($r);
                    $myar[] = ['cd' => '1', 'msg' => 'go ahead'];
                } else {
                    $myar[] = ['cd' => '0', 'msg' => 'no SO found'];
                }
            }
        } else {
            if (count($rs) > 0) {
                foreach ($rs as &$r) {
                    $r['SSO2_SLPRC'] = substr($r['SSO2_SLPRC'], 0, 1) == '.' ? '0' . $r['SSO2_SLPRC'] : $r['SSO2_SLPRC'];
                }
                unset($r);
                $myar[] = ['cd' => '1', 'msg' => 'go ahead'];
            } else {
                $myar[] = ['cd' => '0', 'msg' => 'no SO found'];
            }
        }
        echo '{"status": ' . json_encode($myar) . ', "data" : ' . json_encode($rs) . ', "refdata": ' . json_encode($ddata) . ', "refdatadiff":' . json_encode($ddatadiff) . '}';
    }

    public function set()
    {
        $this->checkSession();
        date_default_timezone_set('Asia/Jakarta');
        $currdate     = date('Ymd');
        $currrtime    = date('Y-m-d H:i:s');
        $ccus         = $this->input->post('incus');
        $cwh          = $this->input->post('inwh');
        $ckanban      = $this->input->post('inkanban');
        $cpurorg      = $this->input->post('inpurorg');
        $citem        = $this->input->post('initem');
        $cdescr       = $this->input->post('indescr');
        $cmodel       = $this->input->post('inmodel');
        $cfruser      = $this->input->post('infruser');
        $creqdate     = $this->input->post('inreqdate');
        $creqqty      = $this->input->post('inreqqty');
        $ctransitdate = $this->input->post('intransitdate');
        $cstrloc      = $this->input->post('instrloc');
        $cstrloc_u    = array_unique($cstrloc);
        $cstrloc_u    = array_values($cstrloc_u);

        $crmrk  = $this->input->post('inrmrk');
        $cplant = $this->input->post('inplant');
        $ceta   = $this->input->post('ineta');

        $cp_so     = $this->input->post('inp_so');
        $cp_soline = $this->input->post('inp_soline');
        $cp_soqty  = $this->input->post('inp_soqty');
        $cp_idx    = $this->input->post('inp_idx');
        $ttlplot   = count($cp_so);

        $ttlitem = count($citem);
        if ($ttlitem > 0) {
            $toret = 0;
            for ($u = 0; $u < count($cstrloc_u); $u++) {
                $mlastid = $this->SI_mod->lastserialid();
                $mlastid++;
                $newid = 'SI' . $currdate . $mlastid;
                $datas = [];
                for ($i = 0; $i < $ttlitem; $i++) {
                    if ($cstrloc_u[$u] == $cstrloc[$i]) {
                        for ($k = 0; $k < $ttlplot; $k++) {
                            if ($cp_idx[$k] == strval($i)) {
                                $datap = [
                                    'SISO_HLINE'  => $newid . "-" . ($i + 1),
                                    'SISO_CPONO'  => $cp_so[$k],
                                    'SISO_SOLINE' => $cp_soline[$k],
                                    'SISO_FLINE'  => $newid . "-" . ($i + 1) . "-" . $k,
                                    'SISO_LINE'   => $k,
                                    'SISO_QTY'    => $cp_soqty[$k],
                                ];
                                $this->SISO_mod->insert($datap);
                            }
                        }
                        $datas[] = [
                            'SI_DOC'        => $newid,
                            'SI_DOCREFF'    => $ckanban[$i],
                            'SI_PURORG'     => $cpurorg[$i],
                            'SI_DOCREFFDT'  => $creqdate[$i],
                            'SI_DOCREFFETA' => $ceta[$i],
                            'SI_CUSCD'      => $ccus,
                            'SI_ITMCD'      => $citem[$i],
                            'SI_DESCR'      => $cdescr[$i],
                            'SI_MDL'        => $cmodel[$i],
                            'SI_FRUSER'     => $cfruser[$i],
                            'SI_QTY'        => str_replace(',', '', $creqqty[$i]),
                            'SI_TRANSITDT'  => $ctransitdate[$i],
                            'SI_OTHRMRK'    => $cstrloc[$i],
                            'SI_REQDT'      => $creqdate[$i],
                            'SI_RMRK'       => $crmrk[$i],
                            'SI_HRMRK'      => $cplant[$i],
                            'SI_LINENO'     => $newid . "-" . ($i + 1),
                            'SI_LINE'       => ($i + 1),
                            'SI_BSGRP'      => 'PSI1PPZIEP',
                            'SI_WH'         => $cwh,
                            'SI_LUPDT'      => $currrtime,
                            'SI_CREATEDAT'  => $currrtime,
                            'SI_USRID'      => $this->session->userdata('nama'),
                        ];
                    }
                }
                $toret += $this->SI_mod->insertb($datas);
            }

            $myar = [];
            if ($toret > 0) {
                $myar[] = ["cd" => $toret, "msg" => "Saved successfully", "ref" => $newid];
                die('{"data":' . json_encode($myar) . '}');
            } else {
                $myar[] = ["cd" => 0, "msg" => "Could not save"];
                die('{"data":' . json_encode($myar) . '}');
            }
        }
    }

    public function setoth()
    {
        $this->checkSession();
        date_default_timezone_set('Asia/Jakarta');
        $currdate  = date('Ymd');
        $currrtime = date('Y-m-d H:i:s');
        $cbg       = $this->input->post('inbg');
        $cwh       = $this->input->post('inwh');
        $ccus      = $this->input->post('incus');
        $ckanban   = $this->input->post('inkanban');
        $citem     = $this->input->post('initem');
        $cmodel    = $this->input->post('inmodel');
        $creqdate  = $this->input->post('inreqdate');
        $creqqty   = $this->input->post('inreqqty');
        $crmrk     = $this->input->post('inrmrk');
        $cplant    = $this->input->post('inplant');
        $ceta      = $this->input->post('ineta');
        $myar      = [];
        $ttlitem   = count($citem);

        $cp_so     = $this->input->post('inp_so');
        $cp_soline = $this->input->post('inp_soline');
        $cp_soqty  = $this->input->post('inp_soqty');
        $cp_idx    = $this->input->post('inp_idx');
        $ttlplot   = is_array($cp_so) ? count($cp_so) : 0;

        if ($ttlitem > 0) {
            //validate master item
            for ($i = 0; $i < $ttlitem; $i++) {
                if ($this->MSTITM_mod->check_Primary(["MITM_ITMCD" => trim($citem[$i])]) == 0) {
                    $myar[] = ["cd" => "0", "msg" => "Item (" . trim($citem[$i]) . ") is not registered"];
                    exit('{"data":' . json_encode($myar) . '}');
                }
            }

            $mlastid = $this->SI_mod->lastserialid();
            $mlastid++;
            $newid = 'SI' . $currdate . $mlastid;
            $datas = [];
            for ($i = 0; $i < $ttlitem; $i++) {
                for ($k = 0; $k < $ttlplot; $k++) {
                    if ($cp_idx[$k] == strval($i)) {
                        $datap = [
                            'SISO_HLINE'  => $newid . "-" . ($i + 1),
                            'SISO_CPONO'  => $cp_so[$k],
                            'SISO_SOLINE' => $cp_soline[$k],
                            'SISO_FLINE'  => $newid . "-" . ($i + 1) . "-" . $k,
                            'SISO_LINE'   => $k,
                            'SISO_QTY'    => $cp_soqty[$k],
                        ];
                        $this->SISO_mod->insert($datap);
                    }
                }
                $datas[] = [
                    'SI_DOC'        => $newid,
                    'SI_DOCREFF'    => $ckanban[$i],
                    'SI_DOCREFFDT'  => trim($creqdate[$i]),
                    'SI_DOCREFFETA' => $ceta[$i],
                    'SI_BSGRP'      => $cbg,
                    'SI_CUSCD'      => $ccus,
                    'SI_ITMCD'      => $citem[$i],
                    'SI_MDL'        => $cmodel[$i],
                    'SI_QTY'        => str_replace(',', '', $creqqty[$i]),
                    'SI_REQDT'      => trim($creqdate[$i]),
                    'SI_RMRK'       => $crmrk[$i],
                    'SI_HRMRK'      => $cplant[$i],
                    'SI_LINENO'     => $newid . "-" . ($i + 1),
                    'SI_LINE'       => ($i + 1),
                    'SI_WH'         => $cwh,
                    'SI_LUPDT'      => $currrtime,
                    'SI_CREATEDAT'  => $currrtime,
                    'SI_USRID'      => $this->session->userdata('nama'),
                ];
            }
            $toret = $this->SI_mod->insertb($datas);

            if ($toret > 0) {
                $myar[] = ["cd" => $toret, "msg" => "Saved successfully", "ref" => $newid];
                die('{"data":' . json_encode($myar) . '}');
            } else {
                $myar[] = ["cd" => "0", "msg" => "Could not save"];
                die('{"data":' . json_encode($myar) . '}');
            }
        }
    }

    public function checkSI()
    {
        header('Content-Type: application/json');
        $csi     = $this->input->get('insi');
        $myar    = [];
        $ttlrows = $this->SI_mod->check_Primary(['SI_DOC' => $csi]);
        $myar[]  = $ttlrows > 0 ? ["cd" => $ttlrows, "msg" => "GO AHEAD"] : ["cd" => $ttlrows, "msg" => "SI Doc not found"];
        die(json_encode($myar));
    }

    public function checkSIItem()
    {
        header('Content-Type: application/json');
        $citem   = $this->input->get('initem');
        $myar    = [];
        $ttlrows = $this->SI_mod->check_Primary(['SI_LINENO' => $citem]);
        $rssug   = [];
        $cwh_out = $_COOKIE["CKPSI_WH"];
        if ($ttlrows > 0) {
            $rsitem = $this->SI_mod->selectItem_by(['SI_LINENO' => $citem]);
            foreach ($rsitem as $r) {
                $citem = trim($r['SI_ITMCD']);
            }
            $rssug  = $this->SI_mod->selectserfg_sugg($citem, $cwh_out);
            $myar[] = ["cd" => $ttlrows, "msg" => "GO AHEAD", "ITMCD" => $citem, "WH" => $cwh_out];
        } else {
            $myar[] = ["cd" => $ttlrows, "msg" => "The item is not found"];
        }
        die(json_encode(['data' => $myar, 'datasug' => $rssug]));
    }

    public function search()
    {
        date_default_timezone_set('Asia/Jakarta');
        $currdt = date('Y-m-d');
        header('Content-Type: application/json');
        $ckey  = $this->input->get('inid');
        $cby   = $this->input->get('inby');
        $clist = $this->input->get('inlist');

        if ($clist == 'today') {
            $rs = $cby == 'si' ? $this->SI_mod->selectAllg_byDATEVAR($currdt, $ckey) : $this->SI_mod->selectAllg_byDATEVARCUS($currdt, $ckey);
        } else {
            $datal = $cby == 'si' ? ['SI_DOC' => $ckey] : ['MCUS_CUSNM' => $ckey];
            $rs    = $this->SI_mod->selectAllg_byVAR($datal);
        }
        echo json_encode($rs);
    }
    public function searchoth()
    {
        date_default_timezone_set('Asia/Jakarta');
        $currdt = date('Y-m-d');
        header('Content-Type: application/json');
        $ckey  = $this->input->get('inid');
        $cby   = $this->input->get('inby');
        $clist = $this->input->get('inlist');

        if ($clist == 'today') {
            $rs = $cby == 'si' ? $this->SI_mod->selectAllg_byDATEVARoth($currdt, $ckey) : $this->SI_mod->selectAllg_byDATEVARCUSoth($currdt, $ckey);
        } else {
            $rs = $cby == 'si' ? $this->SI_mod->selectAllg_byVARoth(['SI_DOC' => $ckey]) : $this->SI_mod->selectAllg_byVARoth(['MCUS_CUSNM' => $ckey]);
        }
        echo json_encode($rs);
    }

    public function getlocation($pitem, $pwh)
    {
        $rs = [];
        switch ($pwh) {
            case 'AFWH3':
                $rs = $this->ITH_mod->selectlocation_fg($pitem);
                break;
            case 'NFWH4RT':
                $rs = $this->ITH_mod->selectlocation_fg_NFWH4RT($pitem);
                break;
            case 'AFWH3RT':
                $rs = $this->ITH_mod->selectlocation_fg_AFWH3RT($pitem);
                break;
        }
        $lok = '';
        foreach ($rs as $r) {
            $lok = $r['LOC'];
        }
        return trim($lok);
    }

    public function printlabel()
    {
        $pser   = '';
        $whcode = '';
        if (isset($_COOKIE["PRINTLABEL_SI"])) {
            $pser   = $_COOKIE["PRINTLABEL_SI"];
            $whcode = $_COOKIE["PRINTLABEL_SIWH"];
        } else {
            exit('nothing to be printed');
        }
        $dataw = ['SI_DOC' => $pser];
        $rs    = [];
        switch ($whcode) {
            case 'AFWH3':
                $rs = $this->SI_mod->selectAll_by($dataw);
                break;
            case 'AFWH3RT':
                $rs = $this->SI_mod->selectAll_AFWH3RT_by($dataw);
                break;
            case 'NFWH4RT':
                $rs = $this->SI_mod->selectAll_NFWH4RT_by($dataw);
                break;
        }
        $myplant    = '';
        $mybsgrp    = '';
        $mycustomer = '';
        foreach ($rs as $r) {
            switch (trim($r['SI_OTHRMRK'])) {
                case 'D116':
                    $myplant = 'EPSON 1/2';
                    break;
                case 'D120':
                    $myplant = 'EPSON 3';
                    break;
                case 'D114':
                    $myplant = 'EPSON 4';
                    break;
            }
            $mybsgrp    = trim($r['MBSG_DESC']);
            $mycustomer = trim($r['MCUS_CUSNM']);
            break;
        }
        $pdf = new PDF_Code39e128('L', 'mm', 'A4');
        $pdf->AddPage();
        $hgt_p = $pdf->GetPageHeight();
        $pdf->SetAutoPageBreak(true, 1);
        $pdf->SetMargins(0, 0);
        $pdf->SetFont('Arial', 'B', 8);
        $pdf->Text(6, 7, 'SI :');
        $pdf->Text(70, 7, 'Shipping Information Epson');
        $pdf->SetFont('Arial', 'B', 5);
        $pdf->Text(265, 3, 'Form : FPI-13-05');
        $pdf->Text(265, 5, 'REV   : 01');
        $pdf->SetFont('Arial', 'B', 8);
        $pdf->Text(135, 7, 'Business Group : ' . $mybsgrp);
        $pdf->Text(135, 12, 'Customer            : ' . $mycustomer);

        $clebar = $pdf->GetStringWidth($whcode) + 13;
        $pdf->Code128(230, 4, $whcode, $clebar, 4);
        $pdf->Text(230, 12, $whcode);

        $pdf->SetXY(6, 17);
        $pdf->Cell(10, 5, 'No', 1, 0, 'L');
        $pdf->Cell(20, 5, 'Doc No', 1, 0, 'L');
        $pdf->Cell(60, 5, 'Item Code', 1, 0, 'C');
        $pdf->Cell(50, 5, 'Model', 1, 0, 'C');
        $pdf->Cell(25, 5, 'ETA', 1, 0, 'C');
        $pdf->Cell(15, 5, 'QTY', 1, 0, 'C');
        $pdf->Cell(15, 5, 'Stock', 1, 0, 'C');
        $pdf->Cell(35, 5, 'Location', 1, 0, 'C');
        $clebar = $pdf->GetStringWidth($pser) + 13;
        $pdf->Code128(12, 4, trim($pser), $clebar, 4);
        $pdf->Text(12, 12, $pser);

        $pdf->Text(70, 12, $myplant);
        $cury      = 22;
        $no        = 1;
        $h_content = 10;
        $wd2col    = 10 + 20 + 60;
        $pdf->SetFont('Arial', '', 8);
        foreach ($rs as $r) {
            if ($cury >= ($hgt_p - 20)) {
                $pdf->AddPage();
                $pdf->SetFont('Arial', 'B', 8);
                $pdf->Text(3, 7, 'SI :');
                $pdf->Text(70, 7, 'Shipping Information Epson');
                $pdf->SetFont('Arial', 'B', 5);
                $pdf->Text(265, 3, 'Form : FPI-13-05');
                $pdf->Text(265, 5, 'REV   : 01');
                $pdf->SetFont('Arial', 'B', 8);
                $pdf->Text(150, 7, 'Business Group : ' . $mybsgrp);
                $pdf->Text(150, 12, 'Customer            : ' . $mycustomer);

                $clebar = $pdf->GetStringWidth($whcode) + 13;
                $pdf->Code128(230, 4, $whcode, $clebar, 4);
                $pdf->Text(230, 12, $whcode);

                $pdf->SetXY(6, 17);
                $pdf->Cell(10, 5, 'No', 1, 0, 'L');
                $pdf->Cell(20, 5, 'Doc No', 1, 0, 'L');
                $pdf->Cell(60, 5, 'Item Code', 1, 0, 'C');
                $pdf->Cell(50, 5, 'Model', 1, 0, 'C');
                $pdf->Cell(25, 5, 'ETA', 1, 0, 'C');
                $pdf->Cell(15, 5, 'QTY', 1, 0, 'C');
                $pdf->Cell(15, 5, 'Stock', 1, 0, 'C');
                $pdf->Cell(35, 5, 'Location', 1, 0, 'C');
                $clebar = $pdf->GetStringWidth($pser) + 13;
                $pdf->Code128(12, 4, trim($pser), $clebar, 4);
                $pdf->Text(12, 12, $pser);
                $pdf->Text(70, 12, $myplant);
                $cury      = 22;
                $no        = 1;
                $h_content = 10;
                $wd2col    = 10 + 20 + 60;
                $pdf->SetFont('Arial', '', 8);
            }
            $clebar   = $pdf->GetStringWidth(trim($r['SI_LINENO'])) + 13;
            $lebar    = $pdf->GetStringWidth(trim($r['SI_LINENO'])) + 12;
            $strx     = $wd2col - $lebar;
            $creqdt   = explode(" ", $r['SI_DOCREFFETA']);
            $mreqdate = date_create($creqdt[0]);
            $pdf->SetXY(6, $cury);

            $pdf->Cell(10, $h_content, $no, 1, 0, 'L');
            $pdf->Cell(20, $h_content, trim($r['SI_DOCREFF']), 1, 0, 'L');
            if (($no % 2) > 0) {
                $pdf->Cell(60, $h_content, trim($r['SI_ITMCD']), 1, 0, 'R');
                $pdf->Code128(40, $cury + 2, $r['SI_LINENO'], $clebar, 5);
            } else {
                $pdf->Cell(60, $h_content, trim($r['SI_ITMCD']), 1, 0, 'L');
                $pdf->Code128($strx, $cury + 2, $r['SI_LINENO'], $clebar, 5);
            }
            $ttlwidth = $pdf->GetStringWidth(trim($r['MITM_ITMD1']));
            if ($ttlwidth > 50) {
                $ukuranfont = 7.5;
                while ($ttlwidth > 50) {
                    $pdf->SetFont('Arial', '', $ukuranfont);
                    $ttlwidth   = $pdf->GetStringWidth(trim($r['MITM_ITMD1']));
                    $ukuranfont = $ukuranfont - 0.5;
                }
            }
            $pdf->Cell(50, $h_content, trim($r['MITM_ITMD1']), 1, 0, 'L');
            $pdf->SetFont('Arial', '', 8);
            $pdf->Cell(25, $h_content, date_format($mreqdate, "d/m/Y") . " " . substr($creqdt[1], 0, 5), 1, 0, 'C');
            $pdf->Cell(15, $h_content, number_format($r['SI_QTY']), 1, 0, 'R');
            $pdf->Cell(15, $h_content, number_format($r['STOCKQTY']), 1, 0, 'R');
            $thelok   = $this->getlocation(trim($r['SI_ITMCD']), $whcode);
            $ttlwidth = $pdf->GetStringWidth($thelok);
            if ($ttlwidth > 35) {
                $ukuranfont = 7.5;
                while ($ttlwidth > 35) {
                    $pdf->SetFont('Arial', '', $ukuranfont);
                    $ttlwidth   = $pdf->GetStringWidth($thelok);
                    $ukuranfont = $ukuranfont - 0.5;
                }
            }
            $pdf->Cell(35, $h_content, $thelok, 1, 0, 'C');
            $pdf->SetFont('Arial', '', 8);
            $cury += $h_content;
            $no++;
        }
        $pdf->Output('I', 'SI Doc ' . $pser . " " . date("d-M-Y") . '.pdf');
    }

    public function printlabeloth()
    {
        $pser   = '';
        $whcode = '';
        if (isset($_COOKIE["PRINTLABEL_SIOTH"])) {
            $pser   = $_COOKIE["PRINTLABEL_SIOTH"];
            $whcode = $_COOKIE["PRINTLABEL_SIWH"];
        } else {
            exit('nothing to be printed');
        }
        $dataw = ['SI_DOC' => $pser];
        $rs    = [];
        switch ($whcode) {
            case 'AFWH3':
                $rs = $this->SI_mod->selectAll_by($dataw);
                break;
            case 'AFWH3RT':
                $rs = $this->SI_mod->selectAll_AFWH3RT_by($dataw);
                break;
            case 'NFWH4RT':
                $rs = $this->SI_mod->selectAll_NFWH4RT_by($dataw);
                break;
        }
        $myplant    = '';
        $mybsgrp    = '';
        $mycustomer = '';
        foreach ($rs as $r) {
            $myplant    = $r['SI_HRMRK'];
            $mybsgrp    = trim($r['MBSG_DESC']);
            $mycustomer = trim($r['MCUS_CUSNM']);
            break;
        }
        $pdf = new PDF_Code39e128('L', 'mm', 'A4');
        $pdf->AddPage();
        $hgt_p = $pdf->GetPageHeight();
        $pdf->SetAutoPageBreak(true, 1);
        $pdf->SetMargins(0, 0);
        $pdf->SetFont('Arial', 'B', 8);
        $pdf->Text(6, 7, 'SI :');
        $pdf->Text(73, 7, 'Shipping Information Other');
        $pdf->SetFont('Arial', 'B', 5);
        $pdf->Text(265, 3, 'Form : FPI-13-05');
        $pdf->Text(265, 5, 'REV   : 01');
        $pdf->SetFont('Arial', 'B', 8);
        $pdf->Text(150, 7, 'Business Group : ' . $mybsgrp);
        $pdf->Text(150, 12, 'Customer            : ' . $mycustomer);

        $clebar = $pdf->GetStringWidth($whcode) + 13;
        $pdf->Code128(230, 4, $whcode, $clebar, 4);
        $pdf->Text(230, 12, $whcode);

        $pdf->SetXY(6, 17);
        $pdf->Cell(10, 5, 'No', 1, 0, 'L');
        $pdf->Cell(25, 5, 'Doc No', 1, 0, 'L');
        $pdf->Cell(83, 5, 'Item Code', 1, 0, 'C');
        $pdf->Cell(50, 5, 'Model', 1, 0, 'C');
        $pdf->Cell(25, 5, 'ETA', 1, 0, 'C');
        $pdf->Cell(15, 5, 'QTY', 1, 0, 'R');
        $pdf->Cell(15, 5, 'Stock', 1, 0, 'C');
        $pdf->Cell(35, 5, 'Location', 1, 0, 'C');
        $clebar = $pdf->GetStringWidth($pser) + 10;
        $pdf->Code39(12, 4, $pser, 0.75);
        $pdf->Text(12, 12, $pser);

        $pdf->Text(73, 12, $myplant);
        $cury      = 22;
        $no        = 1;
        $h_content = 10;
        $wd2col    = 10 + 25 + 83;
        $pdf->SetFont('Arial', '', 8);
        foreach ($rs as $r) {
            if ($cury >= ($hgt_p - 10)) {
                $pdf->AddPage();
                $pdf->SetFont('Arial', 'B', 8);
                $pdf->Text(3, 7, 'SI :');
                $pdf->Text(73, 7, 'Shipping Information Other');
                $pdf->SetFont('Arial', 'B', 5);
                $pdf->Text(265, 3, 'Form : FPI-13-05');
                $pdf->Text(265, 5, 'REV   : 01');
                $pdf->SetFont('Arial', 'B', 8);
                $pdf->Text(150, 7, 'Business Group : ' . $mybsgrp);
                $pdf->Text(150, 12, 'Customer            : ' . $mycustomer);

                $clebar = $pdf->GetStringWidth($whcode) + 13;
                $pdf->Code128(230, 4, $whcode, $clebar, 4);
                $pdf->Text(230, 12, $whcode);

                $pdf->SetXY(6, 17);
                $pdf->Cell(10, 5, 'No', 1, 0, 'L');
                $pdf->Cell(25, 5, 'Doc No', 1, 0, 'L');
                $pdf->Cell(83, 5, 'Item Code', 1, 0, 'C');
                $pdf->Cell(50, 5, 'Model', 1, 0, 'C');
                $pdf->Cell(25, 5, 'ETA', 1, 0, 'C');
                $pdf->Cell(15, 5, 'QTY', 1, 0, 'R');
                $pdf->Cell(15, 5, 'Stock', 1, 0, 'C');
                $pdf->Cell(35, 5, 'Location', 1, 0, 'C');
                $clebar = $pdf->GetStringWidth($pser) + 10;
                $pdf->Code39(12, 4, $pser, 0.75);
                $pdf->Text(12, 12, $pser);
                $pdf->Text(73, 12, $myplant);
                $cury      = 22;
                $h_content = 10;
                $wd2col    = 10 + 25 + 83;
                $pdf->SetFont('Arial', '', 8);
            }
            $clebar   = $pdf->GetStringWidth(trim($r['SI_LINENO'])) + 15;
            $lebar    = $pdf->GetStringWidth(trim($r['SI_LINENO'])) + 13;
            $strx     = $wd2col - $lebar;
            $creqdt   = explode(" ", $r['SI_DOCREFFETA']);
            $mreqdate = date_create($creqdt[0]);
            $pdf->SetXY(6, $cury);

            $pdf->Cell(10, $h_content, $no, 1, 0, 'L');
            $pdf->Cell(25, $h_content, trim($r['SI_DOCREFF']), 1, 0, 'L');
            if (($no % 2) > 0) {
                $pdf->Cell(83, $h_content, trim($r['SI_ITMCD']), 1, 0, 'R');
                $pdf->Code128(45, $cury + 2, $r['SI_LINENO'], $clebar, 5);
            } else {
                $pdf->Cell(83, $h_content, trim($r['SI_ITMCD']), 1, 0, 'L');
                $pdf->Code128($strx, $cury + 2, $r['SI_LINENO'], $clebar, 5);
            }
            $ttlwidth = $pdf->GetStringWidth(trim($r['SI_MDL']));
            if ($ttlwidth > 50) {
                $ukuranfont = 7.5;
                while ($ttlwidth > 50) {
                    $pdf->SetFont('Arial', '', $ukuranfont);
                    $ttlwidth   = $pdf->GetStringWidth(trim($r['SI_MDL']));
                    $ukuranfont = $ukuranfont - 0.5;
                }
            }
            $pdf->Cell(50, $h_content, trim($r['SI_MDL']), 1, 0, 'L');
            $pdf->SetFont('Arial', '', 8);
            $pdf->Cell(25, $h_content, date_format($mreqdate, "d/m/Y") . " " . substr($creqdt[1], 0, 5), 1, 0, 'C');
            $pdf->Cell(15, $h_content, number_format($r['SI_QTY']), 1, 0, 'R');
            $pdf->Cell(15, $h_content, number_format($r['STOCKQTY']), 1, 0, 'R');
            $thelok   = $this->getlocation(trim($r['SI_ITMCD']), $whcode);
            $ttlwidth = $pdf->GetStringWidth($thelok);
            if ($ttlwidth > 35) {
                $ukuranfont = 7.5;
                while ($ttlwidth > 35) {
                    $pdf->SetFont('Arial', '', $ukuranfont);
                    $ttlwidth   = $pdf->GetStringWidth($thelok);
                    $ukuranfont = $ukuranfont - 0.5;
                }
            }
            $pdf->Cell(35, $h_content, $thelok, 1, 0, 'C');
            $pdf->SetFont('Arial', '', 8);
            $cury += $h_content;
            $no++;
        }
        $pdf->Output('I', 'SI Doc Other ' . $pser . " " . date("d-M-Y") . '.pdf');
    }

    public function getbyid()
    {
        header('Content-Type: application/json');
        $csi   = $this->input->get('insi');
        $dataw = ['SI_DOC' => $csi];
        $rs    = $this->SI_mod->selectAllWithoutLocation_by($dataw);
        $rsso  = $this->SISO_mod->selectall_where($dataw);
        foreach ($rsso as &$r) {
            $r['SSO2_SLPRC'] = substr($r['SSO2_SLPRC'], 0, 1) == '.' ? '0' . $r['SSO2_SLPRC'] : $r['SSO2_SLPRC'];
            if (! $r['SSO2_SLPRC']) {
                $pprice       = 0;
                $rs_mst_price = $this->XSO_mod->select_latestprice($r['SI_BSGRP'], $r['SI_CUSCD'], "'" . $r['SI_ITMCD'] . "'");
                foreach ($rs_mst_price as $v) {
                    $pprice = $v['MSPR_SLPRC'];
                }
                $r['SSO2_SLPRC'] = $pprice;
            }
        }
        unset($r);
        die('{"mdata": ' . json_encode($rs) . ', "sodata" : ' . json_encode($rsso) . '}');
    }

    public function edit()
    {
        $this->checkSession();
        date_default_timezone_set('Asia/Jakarta');
        $currrtime    = date('Y-m-d H:i:s');
        $cwh          = $this->input->post('inwh');
        $cbg          = $this->input->post('inbg');
        $ccus         = $this->input->post('incus');
        $ckanban      = $this->input->post('inkanban');
        $cpurorg      = $this->input->post('inpurorg');
        $citem        = $this->input->post('initem');
        $cdescr       = $this->input->post('indescr');
        $cmodel       = $this->input->post('inmodel');
        $cfruser      = $this->input->post('infruser');
        $creqdate     = $this->input->post('inreqdate');
        $creqqty      = str_replace("\n", "", $this->input->post('inreqqty'));
        $ctransitdate = $this->input->post('intransitdate');
        $cstrloc      = $this->input->post('instrloc');
        $crmrk        = $this->input->post('inrmrk');
        $cplant       = $this->input->post('inplant');
        $ceta         = $this->input->post('ineta');
        $csi          = $this->input->post('insi');
        $clineno      = $this->input->post('inlineno');
        $alinenoindex = $this->input->post('alinenoindex');

        $cflineno  = $this->input->post('inflineno');
        $cp_so     = $this->input->post('inp_so');
        $cp_soline = $this->input->post('inp_soline');
        $cp_soqty  = $this->input->post('inp_soqty');
        $csiline   = $this->input->post('inp_siline');
        $cp_idx    = $this->input->post('inp_idx');
        $ttlplot   = count($cflineno);
        $ttlitem   = count($citem);
        $ADDAFF    = 0;
        $EDITAFF   = 0;

        if ($ttlitem > 0) {
            $this->SI_mod->updatebyId(['SI_BSGRP' => $cbg, 'SI_CUSCD' => $ccus], ['SI_DOC' => $csi]);
            $toret = 0;
            for ($i = 0; $i < $ttlitem; $i++) {
                $datak = ['SI_LINENO' => $clineno[$i]];
                $datat = [
                    'SI_DOCREFFDT'  => $creqdate[$i],
                    'SI_DOCREFFETA' => $ceta[$i],
                    'SI_BSGRP'      => $cbg,
                    'SI_CUSCD'      => $ccus,
                    'SI_MDL'        => $cmodel[$i],
                    'SI_QTY'        => str_replace(',', '', $creqqty[$i]),
                    'SI_REQDT'      => $creqdate[$i],
                    'SI_RMRK'       => $crmrk[$i],
                    'SI_HRMRK'      => $cplant[$i],
                    'SI_LUPDT'      => $currrtime,
                    'SI_WH'         => $cwh,
                ];

                $tempscannedqty = 0;
                $rsscanned      = $this->SISCN_mod->select_groupbyline($clineno[$i]);
                foreach ($rsscanned as $n) {
                    $tempscannedqty = $n['SCNQTY'];
                }
                if ($tempscannedqty > $creqqty[$i]) {
                    $toret = 0;
                } else {
                    $toret = $this->SI_mod->updatebyId($datat, $datak);
                    for ($j = 0; $j < $ttlplot; $j++) {
                        if ($csiline[$j] == $clineno[$i]) {
                            $this->SISO_mod->updatebyId(['SISO_QTY' => $cp_soqty[$i]], ['SISO_HLINE' => $clineno[$i], 'SISO_FLINE' => $cflineno[$j]]);
                        }

                    }
                }
                $EDITAFF += $toret;
                if ($toret == 0) {
                    $ttldata = $this->SI_mod->select_lastsiline($csi);
                    for ($k = 0; $k < $ttlplot; $k++) {
                        $lastsisoline = $this->SISO_mod->select_lastsisoline($csi . "-" . ($ttldata + 1));
                        $lastsisoline++;
                        if ($cp_idx[$k] == strval($i) and $csiline[$k] == '') {
                            $datap = [
                                'SISO_HLINE'  => $csi . "-" . ($ttldata + 1),
                                'SISO_CPONO'  => $cp_so[$k],
                                'SISO_SOLINE' => $cp_soline[$k],
                                'SISO_FLINE'  => $csi . "-" . ($ttldata + 1) . "-" . $lastsisoline,
                                'SISO_LINE'   => $lastsisoline,
                                'SISO_QTY'    => $cp_soqty[$k],
                            ];
                            $this->SISO_mod->insert($datap);
                        }
                    }

                    $datas = [
                        'SI_DOC'        => $csi,
                        'SI_DOCREFF'    => $ckanban[$i],
                        'SI_PURORG'     => $cpurorg[$i],
                        'SI_DOCREFFDT'  => $creqdate[$i],
                        'SI_DOCREFFETA' => $ceta[$i],
                        'SI_BSGRP'      => $cbg,
                        'SI_CUSCD'      => $ccus,
                        'SI_ITMCD'      => $citem[$i],
                        'SI_DESCR'      => $cdescr[$i],
                        'SI_MDL'        => $cmodel[$i],
                        'SI_FRUSER'     => $cfruser[$i],
                        'SI_QTY'        => str_replace(',', '', $creqqty[$i]),
                        'SI_TRANSITDT'  => $ctransitdate[$i],
                        'SI_OTHRMRK'    => $cstrloc[$i],
                        'SI_REQDT'      => $creqdate[$i],
                        'SI_RMRK'       => $crmrk[$i],
                        'SI_HRMRK'      => $cplant[$i],
                        'SI_LINENO'     => $csi . "-" . ($ttldata + 1),
                        'SI_LINE'       => ($ttldata + 1),
                        'SI_WH'         => $cwh,
                        'SI_LUPDT'      => $currrtime,
                        'SI_CREATEDAT'  => $currrtime,
                        'SI_USRID'      => $this->session->userdata('nama'),
                    ];
                    $toret = $this->SI_mod->insert($datas);
                    $ADDAFF += $toret;
                } else {

                    $lastsisoline = $this->SISO_mod->select_lastsisoline($clineno[$i]);
                    if ($lastsisoline == '') {
                        for ($k = 0; $k < $ttlplot; $k++) {
                            if ($cp_idx[$k] == strval($i)) {
                                $datap = [
                                    'SISO_HLINE'  => $clineno[$i],
                                    'SISO_CPONO'  => $cp_so[$k],
                                    'SISO_SOLINE' => $cp_soline[$k],
                                    'SISO_FLINE'  => $clineno[$i] . "-" . $k,
                                    'SISO_LINE'   => $k,
                                    'SISO_QTY'    => $cp_soqty[$k],
                                ];
                                $this->SISO_mod->insert($datap);
                            }
                        }
                    } else {
                        for ($k = 0; $k < $ttlplot; $k++) {
                            $lastsisoline++;
                            if ($cp_idx[$k] == $alinenoindex[$i] and $csiline[$k] == '') {
                                $datap = [
                                    'SISO_HLINE'  => $clineno[$i],
                                    'SISO_CPONO'  => $cp_so[$k],
                                    'SISO_SOLINE' => $cp_soline[$k],
                                    'SISO_FLINE'  => $clineno[$i] . "-" . $lastsisoline,
                                    'SISO_LINE'   => $lastsisoline,
                                    'SISO_QTY'    => $cp_soqty[$k],
                                ];
                                $this->SISO_mod->insert($datap);
                            }
                        }
                    }
                }
            }

            $myar = [];
            if (($ADDAFF > 0) || ($EDITAFF > 0)) {
                $toret = 1;
                $datar = ["cd" => $toret, "msg" => "Updated ($EDITAFF), Saved ($ADDAFF)", "ref" => $csi];
            } else {
                $datar = ["cd" => 0, "msg" => "Could not Update"];
            }
            $myar[] = $datar;
            die(json_encode(['data' => $myar]));
        }
    }

    public function scanbalancing()
    {
        header('Content-Type: application/json');
        $csi = $this->input->get('insi');
        $rs  = $this->SI_mod->selectScanbySI($csi);
        die(json_encode(['data' => $rs]));
    }

    public function scanlabel()
    {
        date_default_timezone_set('Asia/Jakarta');
        $currrtime = date('Y-m-d H:i:s');
        $cser      = $this->input->get('inser');
        $csersug   = $this->input->get('insersug');
        $clineno   = $this->input->get('initem');
        $citem     = '';
        $csi       = $this->input->get('insi');
        $cwh_out   = $_COOKIE["CKPSI_WH"];
        $rsitem    = $this->SI_mod->selectItem_by(["SI_LINENO" => $clineno]);
        foreach ($rsitem as $r) {
            $citem = trim($r['SI_ITMCD']);
        }
        if ($citem == '') {
            die("SI Line is not found, please scan the barcode inside cell");
        }
        if ($cser != $csersug) {
            $rs    = $this->SI_mod->selectserfg_sugg_alt($citem, $csersug, $cwh_out);
            $ok    = false;
            $okqty = 0;
            if (count($rs) == 0) {
                exit('There is no serial or serial is already scanned');
            }
            foreach ($rs as $r) {
                if (trim($r['ITH_SER']) == trim($cser)) {
                    $ok    = true;
                    $okqty = $r['ITH_QTY'];
                    break;
                }
            }
            if ($ok) {
                $datac = ['SISCN_SER' => $cser];
                if ($this->SISCN_mod->check_Primary($datac) > 0) {
                    echo "Serial is already scanned";
                } else {
                    $rs = $this->SI_mod->selectscan_balancing($csi, $citem);
                    if (count($rs) > 0) {
                        $datas = [];
                        foreach ($rs as $r) {
                            if (($r['TTLSCN'] + $okqty) > $r['SI_QTY']) {
                                exit('total scan QTY must be <= Req. Qty');
                            } else {
                                $datas = [
                                    'SISCN_DOC'     => $csi,
                                    'SISCN_DOCREFF' => trim($r['SI_DOCREFF']),
                                    'SISCN_SER'     => trim($cser),
                                    'SISCN_SERQTY'  => $okqty,
                                    'SISCN_LINENO'  => $clineno,
                                    'SISCN_LUPDT'   => $currrtime,
                                    'SISCN_USRID'   => $this->session->userdata('nama'),
                                ];
                            }
                        }
                        $tr = $this->SISCN_mod->insert($datas);
                        if ($tr > 0) {
                            echo "OK";
                        } else {
                            echo "Could not save, please contact your admin";
                        }
                    } else {
                        echo "Already Finished";
                    }
                }
            } else {
                echo "Serial not valid";
            }
        } else {
            $rs    = $this->SI_mod->selectserfg_sugg_all($citem, $csersug);
            $ok    = false;
            $okqty = 0;
            if (count($rs) == 0) {
                exit('There is no serial');
            }
            foreach ($rs as $r) {
                if (trim($r['ITH_SER']) == trim($cser)) {
                    $ok    = true;
                    $okqty = $r['ITH_QTY'];
                    break;
                }
            }
            if ($ok) {
                $datac = ['SISCN_SER' => $cser];
                if ($this->SISCN_mod->check_Primary($datac) > 0) {
                    echo "Serial is already scanned";
                } else {
                    $rs = $this->SI_mod->selectscan_balancing($csi, $citem);
                    if (count($rs) > 0) {
                        $datas = [];
                        foreach ($rs as $r) {
                            $datas = [
                                'SISCN_DOC'     => $csi,
                                'SISCN_DOCREFF' => trim($r['SI_DOCREFF']),
                                'SISCN_SER'     => trim($cser),
                                'SISCN_SERQTY'  => $okqty,
                                'SISCN_LINENO'  => $clineno,
                                'SISCN_LUPDT'   => $currrtime,
                                'SISCN_USRID'   => $this->session->userdata('nama'),
                            ];
                        }
                        $tr = $this->SISCN_mod->insert($datas);
                        if ($tr > 0) {
                            echo "OK";
                        } else {
                            echo "Could not save, please contact your admin";
                        }
                    } else {
                        echo "Already Finished";
                    }
                }
            } else {
                echo "Serial not valid";
            }
        }
    }

    public function savescan()
    {
        date_default_timezone_set('Asia/Jakarta');
        $currrtime = date('Y-m-d H:i:s');
        //==START DEFINE WAREHOUSE
        $cwh_inc       = '';
        $cwh_out       = '';
        $cfm_inc       = '';
        $cfm_out       = '';
        $dataf_txroute = [
            'TXROUTE_ID' => 'RECEIVING-FG-SHP', 'TXROUTE_WH' => $_COOKIE["CKPSI_WH"],
        ];
        $rs_txroute = $this->TXROUTE_mod->selectbyVAR($dataf_txroute);
        foreach ($rs_txroute as $r) {
            $cwh_inc = $r->TXROUTE_WHINC;
            $cwh_out = $r->TXROUTE_WHOUT;
            $cfm_inc = $r->TXROUTE_FORM_INC;
            $cfm_out = $r->TXROUTE_FORM_OUT;
        }
        //==END
        $csi       = $this->input->post('insi');
        $dataw     = ['SISCN_DOC' => $csi, 'SISCN_PLLT IS NULL' => null];
        $rsunsaved = $this->SISCN_mod->selectAll_by($dataw);
        $ttlrows   = count($rsunsaved);
        if ($ttlrows > 0) {
            //to ith
            $strser = '';
            foreach ($rsunsaved as $r) {
                $strser .= "'$r[SISCN_SER]',";
            }
            $strser = substr($strser, 0, strlen($strser) - 1);
            $rsloc  = $this->ITH_mod->selectincfgloc($strser);
            foreach ($rsloc as $r) {
                foreach ($rsunsaved as &$k) {
                    if ($r['ITH_SER'] == $k['SISCN_SER']) {
                        $k['SER_LASTLOC'] = $r['ITH_LOC'];
                        break;
                    }
                }
                unset($k);
            }
            $tr      = 0; //catch retured value when saving process
            $trsaved = 0; //catch saved tx
            foreach ($rsunsaved as $r) {
                $rsstock = $this->ITH_mod->select_ser_stock($r['SISCN_SER'], $cwh_out);
                if (count($rsstock) > 0) {
                    $currrtime = date('Y-m-d H:i:s');

                    $tr += $this->ITH_mod->insert_si_scan(
                        trim($r['SER_ITMID']),
                        $cfm_out,
                        $csi,
                        -$r['SISCN_SERQTY'],
                        $cwh_out,
                        $r['SER_LASTLOC'],
                        $r['SISCN_SER'],
                        $this->session->userdata('nama')
                    );

                    $tr += $this->ITH_mod->insert_si_scan(
                        trim($r['SER_ITMID']),
                        $cfm_inc,
                        $csi,
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
            $toret = $this->SISCN_mod->updatebyId($datau, $dataw);
            if ($toret > 0 || $tr > 1) {
                echo "Saved successfully";
            } else {
                if ($trsaved > 0) {
                    echo "Already saved";
                } else {
                    echo "Could not save";
                }
            }
        } else {
            exit('nothing to be saved');
        }
    }

    public function remove()
    {
        $csi      = $this->input->get('insi');
        $line     = $this->input->get('inline');
        $datac    = ['SISCN_LINENO' => $line];
        $rssiscan = $this->SISCN_mod->selectAll_by($datac);
        $myar     = [];
        $ttlscan  = count($rssiscan);
        if ($ttlscan > 0) {
            $myar[] = ["cd" => "00", "msg" => "Could not delete, because the item is already scanned"];
            die(json_encode(['data' => $myar]));
        } else {
            $datac = ['SI_LINENO' => $line];
            $toret = $this->SI_mod->deleteby_filter($datac);
            if ($toret > 0) {
                $toret_plot     = $this->SISO_mod->deleteby_filter(['SISO_HLINE' => $line]);
                $additionalinfo = ($toret_plot > 0) ? '.' : '';
                $myar[]         = ["cd" => "11", "msg" => "Deleted " . $additionalinfo, "ref" => $csi, "siline" => $line];
                die(json_encode(['data' => $myar]));
            } else {
                $myar[] = ["cd" => "01", "msg" => "Could not be deleted ", "ref" => $csi];
                die(json_encode(['data' => $myar]));
            }
        }
    }

    public function getsi()
    {
        #Purpose : to get list undocumented scanning FG (for delivery) result
        header('Content-Type: application/json');
        $ckey      = $this->input->get('inkey');
        $csearchby = $this->input->get('insearchby');
        $ccus      = $this->input->get('incus');
        $cbg       = $this->input->get('inbg');
        $cstrloc   = $this->input->get('instrloc');
        $rs        = [];
        switch ($csearchby) {
            case 'si':
                $rs       = $this->SISCN_mod->selectAll_for_delivery_by_si($ckey, $ccus, $cbg, $cstrloc);
                $itemList = [];
                foreach ($rs as $r) {
                    if (! in_array($r['SER_ITMID'], $itemList)) {
                        $itemList[] = $r['SER_ITMID'];
                    }
                }
                $itemListStr = '';
                foreach ($itemList as $r) {
                    $itemListStr .= "'" . $r . "',";
                }
                if (count($rs) == 0) {
                    $itemListStr .= "''";
                } else {
                    $itemListStr = substr($itemListStr, 0, strlen($itemListStr) - 1);
                }
                $rs_mst_price = $this->XSO_mod->select_latestprice($cbg, $ccus, $itemListStr);
                foreach ($rs as &$r) {
                    if ($r['SISO_SOLINE'] === 'X' && ! $r['SISO_SOLINE']) {
                        foreach ($rs_mst_price as $k) {
                            $r['SI_DOCREFFPRC'] = substr($k['MSPR_SLPRC'], 0, 1) == '.' ? '0' . $k['MSPR_SLPRC'] : $k['MSPR_SLPRC'];
                        }
                    }
                }
                unset($r);
                break;
            case 'cd':
                $rs       = $this->SISCN_mod->selectAll_for_delivery_by_itemcode($ckey, $ccus, $cbg, $cstrloc);
                $itemList = [];
                foreach ($rs as $r) {
                    if (! in_array($r['SER_ITMID'], $itemList)) {
                        $itemList[] = $r['SER_ITMID'];
                    }
                }
                $itemListStr = '';
                foreach ($itemList as $r) {
                    $itemListStr .= "'" . $r . "',";
                }
                $itemListStr  = substr($itemListStr, 0, strlen($itemListStr) - 1);
                $rs_mst_price = $this->XSO_mod->select_latestprice($cbg, $ccus, $itemListStr);
                foreach ($rs as &$r) {
                    if ($r['SISO_SOLINE'] == 'X') {
                        foreach ($rs_mst_price as $k) {
                            $r['SI_DOCREFFPRC'] = substr($k['MSPR_SLPRC'], 0, 1) == '.' ? '0' . $k['MSPR_SLPRC'] : $k['MSPR_SLPRC'];
                        }
                    }
                }
                unset($r);
                break;
            case 'nm':
                $rs = $this->SISCN_mod->selectAll_for_delivery_by_itemname($ckey, $ccus, $cbg, $cstrloc);
                foreach ($rs as &$r) {
                    if ($r['SISO_SOLINE'] == 'X') {
                        $rs_mst_price = $this->XSO_mod->select_latestprice($cbg, $ccus, "'" . $r['SER_ITMID'] . "'");
                        foreach ($rs_mst_price as $k) {
                            $r['SI_DOCREFFPRC'] = $k['MSPR_SLPRC'];
                        }
                    }
                }
                break;
        }
        die(json_encode(['data' => $rs]));
    }

    public function print_pickingdoc()
    {
        $pser = '';
        if (isset($_COOKIE["PRINTLABEL_SI"])) {
            $pser = $_COOKIE["PRINTLABEL_SI"];
        } else {
            exit('nothing to be printed');
        }
        $whdoc = $this->SI_mod->select_wh_top1($pser);
        if ($whdoc == '??') {
            exit('Document is not found');
        } else {
            $documentTitle = 'Shipping Information';
            if ($whdoc == 'AFWH3') {
                $rspickingres = $this->SISCN_mod->select_serah_terima($pser);
                $pdf          = new PDF_Code39e128('P', 'mm', 'A4');
                $pdf->AliasNbPages();
                $pdf->AddPage();
                $wid_p = $pdf->GetPageWidth();
                $pdf->SetAutoPageBreak(true, 1);
                $pdf->SetMargins(0, 0);
                $pdf->SetFont('Arial', '', 8);
                $pdf->SetXY(180, 6);
                $pdf->Cell(15, 4, 'Page ' . $pdf->PageNo() . ' of {nb}', 0, 0, 'R');
                $pdf->SetFont('Arial', 'B', 11);
                $widtex = 50;
                $pdf->SetXY(($wid_p / 2) - ($widtex / 2), 6);
                $pdf->Cell($widtex, 4, $documentTitle, 0, 0, 'C');
                $pdf->SetFont('Arial', 'B', 9);
                $pdf->SetXY(($wid_p / 2) - ($widtex / 2), 11);
                $pdf->Cell($widtex, 4, $pser, 0, 0, 'C');
                $pdf->SetFont('Arial', '', 9);

                $h_date     = '';
                $h_bsgroup  = '';
                $h_customer = '';
                $h_consign  = '';
                foreach ($rspickingres as $r) {
                    $h_date     = $r['SCAN_DATE'];
                    $h_bsgroup  = trim($r['MBSG_DESC']);
                    $h_customer = trim($r['MCUS_CUSNM']);
                    $h_consign  = $r['SI_HRMRK'];
                    break;
                }
                $pdf->SetXY(7, 15);
                $pdf->Cell(9, 4, 'Date : ' . $h_date, 0, 0, 'L');
                $pdf->SetXY(7, 19);
                $pdf->Cell(9, 4, 'Consignee : ' . $h_consign, 0, 0, 'L');

                $pdf->SetXY(130, 15);
                $pdf->Cell(9, 4, 'BG : ' . $h_bsgroup, 0, 0, 'L');
                $pdf->SetXY(130, 19);
                $pdf->Cell(9, 4, 'Customer : ', 0, 0, 'L');
                $lblCustomerwidth = $pdf->GetStringWidth('Customer : ');
                $pdf->SetXY(130 + $lblCustomerwidth, 19);
                $ttlwidth = $pdf->GetStringWidth($h_customer);
                if ($ttlwidth > 60) {
                    $ukuranfont = 8.5;
                    while ($ttlwidth > 60) {
                        $pdf->SetFont('Arial', '', $ukuranfont);
                        $ttlwidth   = $pdf->GetStringWidth($h_customer);
                        $ukuranfont = $ukuranfont - 0.5;
                    }
                }
                $pdf->Cell(9, 4, $h_customer, 0, 0, 'L');

                $pdf->SetXY(7, 25);
                $pdf->SetFont('Arial', 'B', 9);
                $pdf->Cell(10, 5, 'No.', 1, 0, 'L');
                $pdf->Cell(30, 5, 'Item Code', 1, 0, 'L');
                $pdf->Cell(15, 5, 'Rank', 1, 0, 'C');
                $pdf->Cell(65, 5, 'Item Name', 1, 0, 'L');
                $pdf->Cell(16, 5, 'Total QTY', 1, 0, 'C');
                $pdf->Cell(16, 5, 'Total BOX', 1, 0, 'C');
                $pdf->Cell(16, 5, '@ BOX', 1, 0, 'R');
                $pdf->Cell(30, 5, 'Remark', 1, 0, 'C');

                $pdf->SetFont('Arial', '', 9);

                $cY           = 30;
                $no           = 0;
                $todis        = '';
                $todis_nm     = '';
                $todis_ttlqty = '';
                $todis_no     = '';
                $rank         = '';
                $todis_rank   = '';
                $itembef      = '';
                $nobef        = '';
                $ttlbox       = 0;
                $ASPFLAG      = false;
                foreach ($rspickingres as $r) {
                    if (strpos($r['SI_ITMCD'], 'ASP') !== false) {
                        $ASPFLAG = true;
                    }
                    break;
                }
                if ($ASPFLAG) {
                    $rspickingres = $this->SISCN_mod->select_serah_terima_asp($pser);
                }
                foreach ($rspickingres as $r) {
                    $ttlbox += $r['TTLBOX'];
                    if ($itembef != $r['SI_ITMCD'] . $r['MBOM_GRADE']) {
                        $rank         = $r['MBOM_GRADE'];
                        $todis_rank   = $r['MBOM_GRADE'];
                        $itembef      = $r['SI_ITMCD'] . $r['MBOM_GRADE'];
                        $todis        = $r['SI_ITMCD'];
                        $todis_nm     = $r['MITM_ITMD1'];
                        $todis_ttlqty = number_format($r['TTLQTY'], 0, "", ",");
                        $no++;
                    } else {
                        $todis        = '';
                        $todis_nm     = '';
                        $todis_ttlqty = '';
                        if ($rank != $r['MBOM_GRADE']) {
                            $rank       = $r['MBOM_GRADE'];
                            $todis_rank = $r['MBOM_GRADE'];
                        } else {
                            $todis_rank = '';
                        }
                    }
                    if ($nobef != $no) {
                        $todis_no = $no;
                        $nobef    = $no;
                    } else {
                        $todis_no = '';
                    }

                    if ($cY > 281) {
                        $pdf->AddPage();
                        $pdf->SetFont('Arial', '', 8);
                        $pdf->SetXY(180, 6);
                        $pdf->Cell(15, 4, 'Page ' . $pdf->PageNo() . ' of {nb}', 0, 0, 'R');
                        $pdf->SetFont('Arial', 'B', 11);
                        $widtex = 50;
                        $pdf->SetXY(($wid_p / 2) - ($widtex / 2), 6);
                        $pdf->Cell($widtex, 4, $documentTitle, 0, 0, 'C');
                        $pdf->SetFont('Arial', 'B', 9);
                        $pdf->SetXY(($wid_p / 2) - ($widtex / 2), 11);
                        $pdf->Cell($widtex, 4, $pser, 0, 0, 'C');
                        $pdf->SetFont('Arial', '', 9);

                        $pdf->SetXY(7, 15);
                        $pdf->Cell(9, 4, 'Date : ' . $h_date, 0, 0, 'L');
                        $pdf->SetXY(7, 19);
                        $pdf->Cell(9, 4, 'Consignee : ' . $h_consign, 0, 0, 'L');

                        $pdf->SetXY(130, 15);
                        $pdf->Cell(9, 4, 'BG : ' . $h_bsgroup, 0, 0, 'L');
                        $pdf->SetXY(130, 19);
                        $pdf->Cell(9, 4, 'Customer : ', 0, 0, 'L');
                        $lblCustomerwidth = $pdf->GetStringWidth('Customer : ');
                        $pdf->SetXY(130 + $lblCustomerwidth, 19);
                        $ttlwidth = $pdf->GetStringWidth($h_customer);
                        if ($ttlwidth > 60) {
                            $ukuranfont = 8.5;
                            while ($ttlwidth > 60) {
                                $pdf->SetFont('Arial', '', $ukuranfont);
                                $ttlwidth   = $pdf->GetStringWidth($h_customer);
                                $ukuranfont = $ukuranfont - 0.5;
                            }
                        }
                        $pdf->Cell(9, 4, $h_customer, 0, 0, 'L');

                        $pdf->SetXY(7, 25);
                        $pdf->SetFont('Arial', 'B', 9);
                        $pdf->Cell(10, 5, 'No.', 1, 0, 'L');
                        $pdf->Cell(30, 5, 'Item Code', 1, 0, 'L');
                        $pdf->Cell(15, 5, 'Rank', 1, 0, 'C');
                        $pdf->Cell(65, 5, 'Item Name', 1, 0, 'L');
                        $pdf->Cell(16, 5, 'Total QTY', 1, 0, 'C');
                        $pdf->Cell(16, 5, 'Total BOX', 1, 0, 'C');
                        $pdf->Cell(16, 5, '@ BOX', 1, 0, 'R');
                        $pdf->Cell(30, 5, 'Remark', 1, 0, 'C');
                        $pdf->SetFont('Arial', '', 9);

                        $cY = 30;
                    }

                    $cellHeight = 5;
                    $line       = 1;

                    $pdf->SetXY(7, $cY);
                    $pdf->SetFillColor(255, 255, 255);
                    $pdf->Cell(10, ($line * $cellHeight), $todis_no, 1, 0, 'L', true);
                    $pdf->Cell(30, ($line * $cellHeight), $todis, 1, 0, 'L');
                    $pdf->Cell(15, ($line * $cellHeight), $todis_rank, 1, 0, 'C');
                    $ttlwidth = $pdf->GetStringWidth($todis_nm);
                    if ($ttlwidth > 65) {
                        $ukuranfont = 8.5;
                        while ($ttlwidth > 65) {
                            $pdf->SetFont('Arial', '', $ukuranfont);
                            $ttlwidth   = $pdf->GetStringWidth($todis_nm);
                            $ukuranfont = $ukuranfont - 0.5;
                        }
                    }
                    $pdf->Cell(65, ($line * $cellHeight), $todis_nm, 1, 0, 'L');
                    $pdf->SetFont('Arial', '', 9);
                    $pdf->Cell(16, ($line * $cellHeight), $todis_ttlqty == '' ? '' : $todis_ttlqty, 1, 0, 'R');
                    $pdf->Cell(16, ($line * $cellHeight), number_format($r['TTLBOX'], 0, "", ""), 1, 0, 'R');
                    $pdf->Cell(16, ($line * $cellHeight), number_format($r['SISCN_SERQTY']), 1, 0, 'R');

                    $ttlwidth = $pdf->GetStringWidth($r['SER_RMRK']);
                    if ($ttlwidth > 30) {
                        $ukuranfont = 8.5;
                        while ($ttlwidth > 30) {
                            $pdf->SetFont('Arial', '', $ukuranfont);
                            $ttlwidth   = $pdf->GetStringWidth($r['SER_RMRK']);
                            $ukuranfont = $ukuranfont - 0.5;
                        }
                    }
                    $pdf->Cell(30, 5, $r['SER_RMRK'], 1, 0, 'C');

                    $pdf->SetFont('Arial', '', 9);
                    $cY += 5;
                }
                $pdf->SetXY(7, $cY);
                $pdf->Cell(136, 5, 'Total', 1, 0, 'R');
                $pdf->Cell(16, 5, $ttlbox, 1, 0, 'R');
                $pdf->Cell(46, 5, '', 1, 0, 'R');
            } else {
                $pdf = new PDF_Code39e128('L', 'mm', 'A4');
                $pdf->AliasNbPages();
                $rspickingres = $this->SISCN_mod->select_serah_terima_rtn($pser);
                $h_date       = '';
                $h_bsgroup    = '';
                $h_customer   = '';
                $h_consign    = '';
                $h_loc        = '';
                $h_a_consign  = [];
                foreach ($rspickingres as $r) {
                    $h_date     = $r['SCAN_DATE'];
                    $h_bsgroup  = $r['MBSG_DESC'];
                    $h_customer = $r['MCUS_CUSNM'];
                    $h_consign  = $r['RETFG_PLANT'];
                    $h_loc      = $r['SI_WH'];
                    break;
                }

                foreach ($rspickingres as $r) {
                    if (! in_array($r['RETFG_PLANT'], $h_a_consign)) {
                        $h_a_consign[] = $r['RETFG_PLANT'];
                    }
                }

                $ConsignCount = count($h_a_consign);

                for ($b = 0; $b < $ConsignCount; $b++) {
                    foreach ($rspickingres as $z) {
                        if ($h_a_consign[$b] == $z['RETFG_PLANT']) {
                            $pdf->AddPage();
                            $wid_p = $pdf->GetPageWidth();
                            $pdf->SetAutoPageBreak(true, 1);
                            $pdf->SetMargins(0, 0);
                            $pdf->SetFont('Arial', '', 8);
                            $pdf->SetXY(270, 6);
                            $pdf->Cell(15, 4, 'Page ' . $pdf->PageNo() . ' of {nb}', 0, 0, 'R');
                            $pdf->SetFont('Arial', 'B', 11);
                            $widtex = 50;
                            $pdf->SetXY(($wid_p / 2) - ($widtex / 2), 6);
                            $pdf->Cell($widtex, 4, $documentTitle, 0, 0, 'C');
                            $pdf->SetXY(($wid_p / 2) - ($widtex / 2), 19);
                            $pdf->Cell($widtex, 4, $h_loc, 0, 0, 'C');
                            $pdf->SetFont('Arial', 'B', 9);
                            $pdf->SetXY(($wid_p / 2) - ($widtex / 2), 11);
                            $pdf->Cell($widtex, 4, $pser, 0, 0, 'C');
                            $pdf->SetFont('Arial', '', 9);

                            $pdf->SetXY(7, 15);
                            $pdf->Cell(9, 4, 'Date : ' . $h_date, 0, 0, 'L');
                            $pdf->SetXY(7, 19);
                            $pdf->Cell(9, 4, 'Consignee : ' . $h_a_consign[$b], 0, 0, 'L');

                            $pdf->SetXY(200, 15);
                            $pdf->Cell(9, 4, 'BG : ' . $h_bsgroup, 0, 0, 'L');
                            $pdf->SetXY(200, 19);
                            $pdf->Cell(9, 4, 'Customer : ' . $h_customer, 0, 0, 'L');

                            $pdf->SetXY(7, 25);
                            $pdf->SetFont('Arial', 'B', 9);
                            $pdf->Cell(10, 5, 'No.', 1, 0, 'L');
                            $pdf->Cell(30, 5, 'Item Code', 1, 0, 'L');
                            $pdf->Cell(25, 5, 'Old Item Code', 1, 0, 'L');
                            $pdf->Cell(77, 5, 'Item Name', 1, 0, 'L');
                            $pdf->Cell(16, 5, 'Total QTY', 1, 0, 'C');
                            $pdf->Cell(16, 5, 'Total BOX', 1, 0, 'C');
                            $pdf->Cell(14, 5, '@ BOX', 1, 0, 'C');
                            $pdf->Cell(35, 5, 'RA', 1, 0, 'C');
                            $pdf->Cell(60, 5, 'Customs', 1, 0, 'C');
                            $pdf->SetFont('Arial', '', 9);

                            $cY           = 30;
                            $no           = 0;
                            $todis        = '';
                            $todis_nm     = '';
                            $todis_ttlqty = '';
                            $todis_no     = '';
                            $todis_rank   = '';
                            $itembef      = '';
                            $nobef        = '';
                            $ra_dis       = '';
                            $ttlbox       = 0;
                            $t_intqty     = '';
                            $t_ttlbox     = '';
                            $perbox       = '';
                            $perbox_dis   = '';
                            $sttlbox_dis  = '';
                            $olditem      = '';
                            $olditem_bef  = '';
                            $olditem_diff = false;

                            foreach ($rspickingres as $r) {
                                if ($h_a_consign[$b] == $r['RETFG_PLANT']) {
                                    $ra_use = $r['INTQTY'];
                                    $ra_dis = $r['SER_DOC'] . ": " . number_format($ra_use);
                                    if ($olditem_bef != $r['OLDITEM']) {
                                        $olditem_bef  = $r['OLDITEM'];
                                        $olditem_diff = true;
                                    } else {
                                        $olditem_diff = false;
                                    }
                                    if ($itembef != $r['SI_ITMCD']) {
                                        $itembef      = $r['SI_ITMCD'];
                                        $todis        = $r['SI_ITMCD'];
                                        $todis_nm     = $r['MITM_ITMD1'];
                                        $todis_ttlqty = number_format($r['TTLQTY'], 0, "", ",");

                                        $no++;
                                        $t_intqty    = $r['INTQTY'];
                                        $t_ttlbox    = $r['BOX'];
                                        $sttlbox_dis = $t_ttlbox;
                                        $ttlbox += $r['BOX'];

                                        $perbox     = $r['PERBOX'];
                                        $perbox_dis = number_format($perbox, 0, "", ",");
                                    } else {

                                        $todis        = '';
                                        $todis_nm     = '';
                                        $todis_ttlqty = '';

                                        if ($t_intqty != $r['INTQTY']) {
                                            $t_intqty = $r['INTQTY'];
                                        }
                                        if ($t_ttlbox != $r['BOX'] && $perbox != $r['PERBOX']) {
                                            $t_ttlbox = $r['BOX'];
                                            $ttlbox += $t_ttlbox;
                                            $perbox      = $r['PERBOX'];
                                            $perbox_dis  = number_format($perbox, 0, "", ",");
                                            $sttlbox_dis = $t_ttlbox;
                                        } elseif ($t_ttlbox == $r['BOX'] && $perbox != $r['PERBOX']) {
                                            $t_ttlbox = $r['BOX'];
                                            $ttlbox += $t_ttlbox;
                                            $perbox      = $r['PERBOX'];
                                            $perbox_dis  = number_format($perbox, 0, "", ",");
                                            $sttlbox_dis = $t_ttlbox;
                                        } else {
                                            $perbox_dis  = '';
                                            $sttlbox_dis = '';
                                        }
                                    }
                                    if ($nobef != $no) {
                                        $todis_no = $no;
                                        $nobef    = $no;
                                    } else {
                                        $todis_no = '';
                                    }
                                    if ($todis != '' && $todis != $r['OLDITEM']) {
                                        $olditem = $r['OLDITEM'];
                                    } else {
                                        $olditem = '';
                                    }
                                    if ($olditem_diff && $todis == '') {
                                        $olditem = $r['OLDITEM'];
                                    }

                                    if ($cY > 190) {
                                        $pdf->AddPage();
                                        $pdf->SetFont('Arial', '', 8);
                                        $pdf->SetXY(270, 6);
                                        $pdf->Cell(15, 4, 'Page ' . $pdf->PageNo() . ' of {nb}', 0, 0, 'R');
                                        $pdf->SetFont('Arial', 'B', 11);
                                        $widtex = 50;
                                        $pdf->SetXY(($wid_p / 2) - ($widtex / 2), 6);
                                        $pdf->Cell($widtex, 4, $documentTitle, 0, 0, 'C');
                                        $pdf->SetFont('Arial', 'B', 9);
                                        $pdf->SetXY(($wid_p / 2) - ($widtex / 2), 11);
                                        $pdf->Cell($widtex, 4, $pser, 0, 0, 'C');
                                        $pdf->SetFont('Arial', '', 9);

                                        $pdf->SetXY(7, 15);
                                        $pdf->Cell(9, 4, 'Date : ' . $h_date, 0, 0, 'L');
                                        $pdf->SetXY(7, 19);
                                        $pdf->Cell(9, 4, 'Consignee : ' . $h_consign, 0, 0, 'L');

                                        $pdf->SetXY(200, 15);
                                        $pdf->Cell(9, 4, 'BG : ' . $h_bsgroup, 0, 0, 'L');
                                        $pdf->SetXY(200, 19);
                                        $pdf->Cell(9, 4, 'Customer : ' . $h_customer, 0, 0, 'L');

                                        $pdf->SetXY(7, 25);
                                        $pdf->SetFont('Arial', 'B', 9);
                                        $pdf->Cell(10, 5, 'No.', 1, 0, 'L');
                                        $pdf->Cell(30, 5, 'Item Code', 1, 0, 'L');
                                        $pdf->Cell(25, 5, 'Old Item Code', 1, 0, 'L');
                                        $pdf->Cell(77, 5, 'Item Name', 1, 0, 'L');
                                        $pdf->Cell(16, 5, 'Total QTY', 1, 0, 'C');
                                        $pdf->Cell(16, 5, 'Total BOX', 1, 0, 'C');
                                        $pdf->Cell(14, 5, '@ BOX', 1, 0, 'C');
                                        $pdf->Cell(35, 5, 'RA', 1, 0, 'C');
                                        $pdf->Cell(60, 5, 'Customs', 1, 0, 'C');
                                        $pdf->SetFont('Arial', '', 9);

                                        $cY = 30;
                                    }

                                    $pdf->SetXY(7, $cY);
                                    $pdf->Cell(10, 5, $todis_no, 1, 0, 'L');
                                    $pdf->Cell(30, 5, $todis, 1, 0, 'L');
                                    $pdf->Cell(25, 5, $olditem, 1, 0, 'L');
                                    $ttlwidth = $pdf->GetStringWidth($todis_nm);
                                    if ($ttlwidth > 75) {
                                        $ukuranfont = 7.5;
                                        while ($ttlwidth > 75) {
                                            $pdf->SetFont('Arial', '', $ukuranfont);
                                            $ttlwidth   = $pdf->GetStringWidth($todis_nm);
                                            $ukuranfont = $ukuranfont - 0.5;
                                        }
                                    }
                                    $pdf->Cell(77, 5, $todis_nm, 1, 0, 'L');
                                    $pdf->SetFont('Arial', '', 9);
                                    $pdf->Cell(16, 5, $todis_ttlqty == '' ? '' : $todis_ttlqty, 1, 0, 'R');
                                    $pdf->Cell(16, 5, $sttlbox_dis, 1, 0, 'R');
                                    $pdf->Cell(14, 5, $perbox_dis, 1, 0, 'R');
                                    $ttlwidth = $pdf->GetStringWidth($ra_dis);
                                    if ($ttlwidth > 35) {
                                        $ukuranfont = 8.5;
                                        while ($ttlwidth > 35) {
                                            $pdf->SetFont('Arial', '', $ukuranfont);
                                            $ttlwidth   = $pdf->GetStringWidth($ra_dis);
                                            $ukuranfont = $ukuranfont - 0.5;
                                        }
                                    }
                                    $pdf->Cell(35, 5, $ra_dis, 1, 0, 'R');
                                    $pdf->SetFont('Arial', '', 9);
                                    $pdf->Cell(60, 5, $r['RCV_RPNO'] . ":" . $r['RCV_BCNO'], 1, 0, 'L');

                                    $cY += 5;
                                }
                            }
                            $pdf->SetXY(7, $cY);
                            $pdf->Cell(158, 5, 'Total', 1, 0, 'R');
                            $pdf->Cell(16, 5, $ttlbox, 1, 0, 'R');
                            $pdf->Cell(109, 5, '', 1, 0, 'R');
                            break;
                        }
                    }
                }
            }
        }
        $pdf->Output('I', ' FG Picking Result Doc  ' . date("d-M-Y") . '.pdf');
    }

    public function getscanned()
    {
        header('Content-Type: application/json');
        $csi  = $this->input->get('insiline');
        $myar = [];
        $rs   = $this->SISCN_mod->selectAll_by(['SISCN_LINENO' => $csi]);
        if (count($rs) > 0) {
            $myar[] = ['cd' => 1, 'msg' => 'Go ahead'];
        } else {
            $myar[] = ['cd' => 0, 'msg' => 'No data scanned'];
        }
        exit('{"status" : ' . json_encode($myar) . ', "data": ' . json_encode($rs) . '}');
    }

    public function removescan()
    {
        $this->checkSession();
        header('Content-Type: application/json');
        date_default_timezone_set('Asia/Jakarta');
        $myar    = [];
        $creffno = $this->input->post('inreffno');
        $cwh_out = "";
        $WHBASE  = $this->SI_mod->select_wh_top1_byreffno($creffno);
        if ($WHBASE == "AFWH3") {
            $cwh_out = "ARSHP";
        } elseif ($WHBASE == "NFWH4RT") {
            $cwh_out = "ARSHPRTN";
        } else {
            $cwh_out = "ARSHPRTN2";
        }
        if ($this->DELV_mod->check_Primary(['DLV_SER' => $creffno]) == 0) {
            $rsdet = $this->SISCN_mod->selectAll_by(['SISCN_SER' => $creffno]);
            $sino  = $siitmcd  = $siqty  = $silocation_bef  = $siwhbef  = '';
            foreach ($rsdet as $r) {
                $sino    = $r['SISCN_DOC'];
                $siitmcd = trim($r['SER_ITMID']);
                $siqty   = $r['SISCN_SERQTY'];
            }
            $rslocation_bef = $this->ITH_mod->selectAll_by(['ITH_FORM' => 'OUT-WH-FG', 'ITH_SER' => $creffno, 'ITH_DOC' => $sino]);
            foreach ($rslocation_bef as $r) {
                $siwhbef        = $r['ITH_WH'];
                $silocation_bef = $r['ITH_LOC'];
            }
            if ($this->SISCN_mod->deleteby_filter(['SISCN_SER' => $creffno]) > 0) {
                $rslastwh = $this->ITH_mod->selectstock_ser($creffno);
                $wh       = '';
                foreach ($rslastwh as $r) {
                    $wh = trim($r['ITH_WH']);
                }
                if ($wh == $cwh_out) {
                    $retith = $this->ITH_mod->insert_si_scan(
                        $siitmcd,
                        'CANCEL-SHIP',
                        $sino,
                        -$siqty,
                        $cwh_out,
                        null,
                        $creffno,
                        $this->session->userdata('nama')
                    );
                    if ($retith > 0) {
                        $retith = $this->ITH_mod->insert_si_scan_with_remark(
                            $siitmcd,
                            'CANCEL-SHIP',
                            $sino,
                            $siqty,
                            $siwhbef,
                            $silocation_bef,
                            $creffno,
                            $this->session->userdata('nama'),
                            'CANCEL SHIPPING'
                        );
                        if ($retith > 0) {
                            $myar[] = ['cd' => 1, 'msg' => 'OK...'];
                        } else {
                            $myar[] = ['cd' => 0, 'msg' => 'Could not add stock to FG Warehouse , please contact Admin'];
                        }
                    } else {
                        $myar[] = ['cd' => 0, 'msg' => 'Could not minus transaction , please contact Admin'];
                    }
                } else {
                    $myar[] = ['cd' => 1, 'msg' => 'OK'];
                }
            } else {
                $myar[] = ['cd' => 0, 'msg' => 'Could not remove , please contact Admin'];
            }
        } else {
            $myar[] = ['cd' => 0, 'msg' => 'the reff no is already included in Surat Jalan'];
        }
        exit('{"status" : ' . json_encode($myar) . '}');
    }

    public function checkSession()
    {
        $myar = [];
        if ($this->session->userdata('status') != "login") {
            $myar[] = ["cd" => "0", "msg" => "Session is expired please reload page"];
            exit(json_encode($myar));
        }
    }

    public function vrdiscrepancyso()
    {
        $this->load->view('wms_report/vrpt_discrepancyso');
    }

    public function so_vs_stock()
    {
        header('Content-Type: application/json');
        date_default_timezone_set('Asia/Jakarta');
        $cdate  = date('Y-m-d');
        $citem  = '';
        $cwh    = 'AFSMT';
        $rs     = $this->ITH_mod->select_psi_stock_date_wbg($cwh, $citem, $cdate);
        $rsosso = $this->XSO_mod->select_ost_so_perItem();
        foreach ($rs as &$r) {
            $r['PLOTQTY'] = 0;
            $r['PRICE']   = 0;
            $r['COLOR']   = '';
            foreach ($rsosso as &$o) {
                $need = $r['STOCKQTY'] - $r['PLOTQTY'];
                if (trim($r['ITH_ITMCD']) == $o['MDLCD'] && $need > 0 && $o['OSTQT'] > 0) {
                    $r['PRICE'] = $o['SLPRC'];
                    if ($need > $o['OSTQT']) {
                        $r['PLOTQTY'] += $o['OSTQT'];
                        $o['OSTQT'] = 0;
                    } else {
                        $r['PLOTQTY'] += $need;
                        $o['OSTQT'] -= $need;
                    }
                    if ($r['STOCKQTY'] == $r['PLOTQTY']) {
                        break;
                    }
                }
            }
            unset($o);
        }
        unset($r);
        $resume = [];
        $items  = "";
        foreach ($rs as &$r) {
            if ($r['PLOTQTY'] < (int) $r['STOCKQTY']) {
                $r['COLOR'] = 'YELLOW';
                if ($r['PLOTQTY'] == 0) {
                    $r['COLOR'] = 'RED';
                    $items .= "'" . $r['ITH_ITMCD'] . "',";
                }
                $resume[] = $r;
            }
        }
        unset($r);

        $items         = substr($items, 0, strlen($items) - 1);
        $rsmasterprice = [];
        if ($items != '') {
            $rsmasterprice = $this->XSO_mod->select_latestprice_bymodels($items);
        }
        foreach ($resume as &$r) {
            foreach ($rsmasterprice as $m) {
                if (strtoupper(trim($r['ITH_ITMCD'])) == strtoupper($m['MSPR_ITMCD'])) {
                    $r['COLOR'] = 'YELLOW';
                    $r['PRICE'] = $m['MSPR_SLPRC'];
                    break;
                }
            }
        }
        unset($r);

        usort($resume, function ($a, $b) {
            return $a['COLOR'] <=> $b['COLOR'];
        });
        die(json_encode(['data' => $resume, 'master_price' => $rsmasterprice]));
    }

    public function getdiscrepancyso()
    {
        header('Content-Type: application/json');
        $this->SISO_mod->deletetempby_filter();
        $rs       = $this->SISO_mod->selectgreatersothansi();
        $rsheader = [];
        foreach ($rs as $r) {
            $isfound = false;
            foreach ($rsheader as $h) {
                if ($h['SILINE'] == $r['SISO_HLINE']) {
                    $isfound = true;
                    break;
                }
            }
            if (! $isfound) {
                $rsheader[] = ['SILINE' => $r['SISO_HLINE'], 'SIQTY' => intval($r['SI_QTY'])];
            }
        }
        foreach ($rs as &$r) {
            foreach ($rsheader as &$h) {
                if ($r['SISO_HLINE'] == $h['SILINE'] && $h['SIQTY'] > 0) {
                    if ($h['SIQTY'] > $r['SISO_QTY']) {
                        $r['SISO_QTYPLOT'] = $r['SISO_QTY'];
                        $h['SIQTY'] -= $r['SISO_QTY'];
                    } else {
                        $r['SISO_QTYPLOT'] = $h['SIQTY'];
                    }
                }
            }
            unset($h);
        }
        unset($r);

        foreach ($rsheader as &$h) {
            if ($h['SIQTY'] > 0) {
                foreach ($rs as &$r) {
                    if ($h['SILINE'] == $r['SISO_HLINE']) {
                        $r['SISO_QTYPLOT'] += $h['SIQTY'];
                        $h['SIQTY'] = 0;
                        break;
                    }
                }
                unset($r);
            }
        }
        unset($h);
        $datas = [];
        foreach ($rs as $r) {
            $datas[] = [
                'TEMPFSO_NO'      => $r['SISO_CPONO'],
                'TEMPFSO_LINE'    => $r['SISO_SOLINE'],
                'TEMPFSO_MDL'     => $r['SI_ITMCD'],
                'TEMPFSO_QTY'     => $r['SISO_QTY'],
                'TEMPFSO_PLOTQTY' => $r['SISO_QTYPLOT'],
                'TEMPFSO_SILINE'  => $r['SISO_HLINE'],
            ];
        }
        $this->SISO_mod->insertb_temp($datas);
        $rs = $this->SISO_mod->selecttempfifoso();

        $myar = [];
        if (! empty($rs)) {
            $myar[] = ['cd' => '1', 'msg' => 'go ahead'];
        } else {
            $myar[] = ['cd' => '0', 'msg' => 'No discrepancy'];
        }
        die(json_encode(['data' => $rs, 'status' => $myar]));
    }

    public function getsobyreffno()
    {
        header('Content-Type: application/json');
        $caser        = $this->input->post('inser');
        $ttlrows      = is_array($caser) ? count($caser) : 0;
        $myar         = [];
        $rs           = [];
        $rsresume     = [];
        $rsmultiprice = [];
        if ($ttlrows > 0) {
            $in = "";
            for ($i = 0; $i < $ttlrows; $i++) {
                $in .= "'" . $caser[$i] . "',";
            }
            $in = substr($in, 0, strlen($in) - 1);
            $rs = $this->SISCN_mod->selectso_byser($in);
            foreach ($rs as &$k) {
                if ($k['SISO_SOLINE'] == 'X') {
                    $rs_mst_price = $this->XSO_mod->select_latestprice($k['SI_BSGRP'], $k['SI_CUSCD'], "'" . $k['SI_ITMCD'] . "'");
                    foreach ($rs_mst_price as $r) {
                        $k['SSO2_SLPRC'] = substr($r['MSPR_SLPRC'], 0, 1) == '.' ? '0' . $r['MSPR_SLPRC'] : $r['MSPR_SLPRC'];
                    }
                }
                $isfound = false;
                foreach ($rsresume as &$n) {
                    if ($n['RSI_ITMCD'] == $k['SI_ITMCD'] && $n['RSSO2_SLPRC'] != $k['SSO2_SLPRC']) {
                        $n['RCOUNT']++;
                        $isfound = true;
                        break;
                    }
                }
                unset($n);

                if (! $isfound) {
                    $rsresume[] = [
                        'RSI_ITMCD' => $k['SI_ITMCD'], 'RSSO2_SLPRC' => $k['SSO2_SLPRC'], 'RCOUNT' => 1,
                    ];
                }
            }
            unset($k);

            foreach ($rsresume as $k) {
                if ($k['RCOUNT'] > 1) {
                    $rsmultiprice[] = $k;
                }
            }

            $myar[] = ['cd' => 1, 'msg' => 'go ahead'];
        } else {
            $myar[] = ['cd' => 0, 'msg' => 'go ahead'];
        }
        die(json_encode([
            'status' => $myar, 'data' => $rs, 'dataresume' => $rsresume, 'datamultiprice' => $rsmultiprice,
        ]));
    }

    public function get_outgoing()
    {
        header('Content-Type: application/json');
        $csearchby = $this->input->get('insearchby');
        $cdtfrom   = $this->input->get('indate');
        $cdtto     = $this->input->get('indate2');
        $creport   = $this->input->get('inreport');
        $cassy     = $this->input->get('inassy');
        $cbsgroup  = trim($this->input->get('inbsgrp'));
        $dtto      = '';
        if ($cdtfrom == $cdtto) {
            if ($creport == 'a' || $creport == 'n') {
                $thedate = strtotime($cdtfrom . '+1 days');
                $dtto    = date('Y-m-d', $thedate) . " 06:59:00";
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
            $dtto    = date('Y-m-d', $thedate) . " 06:59:00";
            $cdtfrom .= ' 07:00:00';
        }
        $rs = [];
        if ($cbsgroup == '-') {
        } else {
            switch ($csearchby) {
                case 'assy':
                    $rs = $this->SI_mod->select_outgoing_wh_byassy_bg($cbsgroup, $cdtfrom, $dtto, $cassy);
                    break;
                case 'job':
                    $rs = $this->SI_mod->select_outgoing_wh_byjob_bg($cbsgroup, $cdtfrom, $dtto, $cassy);
                    break;
                case 'reff':
                    $rs = $this->SI_mod->select_outgoing_wh_byreff_bg($cbsgroup, $cdtfrom, $dtto, $cassy);
                    break;
                case 'si':
                    $rs = $this->SI_mod->select_outgoing_wh_bySI_bg($cbsgroup, $cdtfrom, $dtto, $cassy);
                    break;
                case 'txid':
                    $rs = $this->SI_mod->select_outgoing_wh_byTXID_bg($cbsgroup, $cdtfrom, $dtto, $cassy);
                    break;
            }
        }
        echo json_encode(['data' => $rs]);
    }
    public function get_outgoing_as_spreadsheet()
    {
        $csearchby = $this->input->get('insearchby');
        $cdtfrom   = $this->input->get('indate');
        $cdtto     = $this->input->get('indate2');
        $creport   = $this->input->get('inreport');
        $cassy     = $this->input->get('inassy');
        $cbsgroup  = trim($this->input->get('inbsgrp'));
        $dtto      = '';
        if ($cdtfrom == $cdtto) {
            if ($creport == 'a' || $creport == 'n') {
                $thedate = strtotime($cdtfrom . '+1 days');
                $dtto    = date('Y-m-d', $thedate) . " 06:59:00";
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
            $dtto    = date('Y-m-d', $thedate) . " 06:59:00";
            $cdtfrom .= ' 07:00:00';
        }
        $rs = [];
        if ($cbsgroup === '-') {
        } else {
            switch ($csearchby) {
                case 'assy':
                    $rs = $this->SI_mod->select_outgoing_wh_byassy_bg($cbsgroup, $cdtfrom, $dtto, $cassy);
                    break;
                case 'job':
                    $rs = $this->SI_mod->select_outgoing_wh_byjob_bg($cbsgroup, $cdtfrom, $dtto, $cassy);
                    break;
                case 'reff':
                    $rs = $this->SI_mod->select_outgoing_wh_byreff_bg($cbsgroup, $cdtfrom, $dtto, $cassy);
                    break;
                case 'si':
                    $rs = $this->SI_mod->select_outgoing_wh_bySI_bg($cbsgroup, $cdtfrom, $dtto, $cassy);
                    break;
                case 'txid':
                    $rs = $this->SI_mod->select_outgoing_wh_byTXID_bg($cbsgroup, $cdtfrom, $dtto, $cassy);
                    break;
            }
        }

        $spreadsheet = new Spreadsheet();
        $sheet       = $spreadsheet->getActiveSheet();
        $sheet->setTitle('Outgoing Finished Goods');
        $sheet->setCellValueByColumnAndRow(1, 2, 'Assy Code');
        $sheet->setCellValueByColumnAndRow(2, 2, 'Model');
        $sheet->setCellValueByColumnAndRow(3, 2, 'SI');
        $sheet->setCellValueByColumnAndRow(4, 2, 'TX ID');
        $sheet->setCellValueByColumnAndRow(5, 2, 'Job Number');
        $sheet->setCellValueByColumnAndRow(6, 2, 'Qty');
        $sheet->setCellValueByColumnAndRow(7, 2, 'Reff. Number');
        $sheet->setCellValueByColumnAndRow(8, 2, 'ETA');
        $sheet->setCellValueByColumnAndRow(9, 2, 'ETD');
        $sheet->setCellValueByColumnAndRow(10, 2, 'Scanning Time');
        $sheet->setCellValueByColumnAndRow(11, 2, 'Plant');
        $sheet->setCellValueByColumnAndRow(12, 2, 'Business');
        $sheet->setCellValueByColumnAndRow(13, 2, 'Remark');
        $sheet->freezePane('A3');
        $y = 3;
        foreach ($rs as $r) {
            $sheet->setCellValueByColumnAndRow(1, $y, $r['SI_ITMCD']);
            $sheet->setCellValueByColumnAndRow(2, $y, $r['MITM_ITMD1']);
            $sheet->setCellValueByColumnAndRow(3, $y, $r['SI_DOC']);
            $sheet->setCellValueByColumnAndRow(4, $y, $r['DLV_ID']);
            $sheet->setCellValueByColumnAndRow(5, $y, $r['SER_DOC']);
            $sheet->setCellValueByColumnAndRow(6, $y, $r['SISCN_SERQTY']);
            $sheet->setCellValueByColumnAndRow(7, $y, $r['SISCN_SER']);
            $sheet->setCellValueByColumnAndRow(8, $y, $r['SI_DOCREFFETA']);
            $sheet->setCellValueByColumnAndRow(9, $y, $r['ITH_LUPDT']);
            $sheet->setCellValueByColumnAndRow(10, $y, $r['SISCN_LUPDT']);
            $sheet->setCellValueByColumnAndRow(11, $y, $r['SI_OTHRMRK']);
            $sheet->setCellValueByColumnAndRow(12, $y, $r['SI_BSGRP']);
            $sheet->setCellValueByColumnAndRow(13, $y, $r['SER_RMRK']);
            $y++;
        }
        foreach (range('A', 'L') as $v) {
            $sheet->getColumnDimension($v)->setAutoSize(true);
        }
        $stringjudul = 'Outgoing FG ' . $cbsgroup;
        $writer      = new Xlsx($spreadsheet);
        $filename    = $stringjudul; //save our workbook as this file name
        header('Content-Type: application/vnd.ms-excel');
        header('Content-Disposition: attachment;filename="' . $filename . '.xlsx"');
        header('Cache-Control: max-age=0');
        $writer->save('php://output');
    }
}
