<?php
defined('BASEPATH') or exit('No direct script access allowed');

class INCFG extends CI_Controller
{
    public function __construct()
    {
        parent::__construct();
        $this->load->helper('url');
        $this->load->library('session');
        $this->load->model('ITH_mod');
        $this->load->model('SER_mod');
        $this->load->model('SPL_mod');
        $this->load->model('SPLSCN_mod');
        $this->load->model('MSTLOC_mod');
        $this->load->model('MSTITM_mod');
        $this->load->model('SERD_mod');
        $this->load->model('LOGSER_mod');
        $this->load->model('RCVFGSCN_mod');
        $this->load->model('SERML_mod');
        $this->load->model('PWOP_mod');
    }

    public function index()
    {
        echo "sorry";
    }

    public function create()
    {
        $this->load->view('wms/vincomingfg');
    }
    public function createwhrtn()
    {
        $this->load->view('wms/vincomingfg_wh_rtn');
    }
    public function createsa()
    {
        $this->load->view('wms/vincomingfg_subassy');
    }
    public function createml()
    {
        $this->load->view('wms/vincomingfg_multilayer');
    }
    public function createcwip()
    {
        $this->load->view('wms/vincomingfg_convert');
    }

    public function vcancelscanqc()
    {
        $this->load->view('wms/vcancelscanqc');
    }

    public function createprd()
    {
        $data['rsjob'] = $this->SER_mod->select_joblbl_ost();
        $this->load->view('wms/vincomingfg_prd', $data);
    }

    public function get_joblbl_ost()
    {
        header('Content-Type: application/json');
        $rs = $this->SER_mod->select_joblbl_ost();
        echo json_encode($rs);
    }

    public function createwh()
    {
        $this->load->view('wms/vincomingfg_wh');
    }
    public function createrc()
    {
        $this->load->view('wms/vincomingfg_rtn_rc');
    }
    public function createrc_oth()
    {
        $this->load->view('wms/vincomingfg_rtn_rc_oth');
    }

    public function checkSession()
    {
        $myar = [];
        if ($this->session->userdata('status') != "login") {
            $myar[] = ["cd" => "0", "msg" => "Session is expired please reload page"];
            exit(json_encode($myar));
        }
    }

    public function setprd()
    {
        $this->checkSession();
        date_default_timezone_set('Asia/Jakarta');
        $currdate = date('Y-m-d');
        $currrtime = date('Y-m-d H:i:s');
        $nextYear = date('y');
        $prevYear = date('y');
        $prevYear--;
        $nextYear++;
        $ckeys = $this->input->post("inkey");

        $cjob = $this->input->post("injob");
        $cfgtype = $this->input->post("infgtype");
        $citemcd = $this->input->post("initemcd");
        $cinlot = $this->input->post("inlot");
        $cinqty = $this->input->post("inqty");
        $cremark = trim($this->input->post("inremark"));
        $cremark_ka = trim($this->input->post("inremark_ka"));
        $cismanual = trim($this->input->post("inismanual"));
        $creason = $this->input->post("inreason");
        $ckeys = trim($ckeys);
        $datalogser = [
            'LOGSER_KEYS' => $ckeys, 'LOGSER_ITEM' => $citemcd, 'LOGSER_QTY' => $cinqty, 'LOGSER_LOT' => $cinlot,
            'LOGSER_JOB' => $cjob, 'LOGSER_USRID' => $this->session->userdata('nama'), "LOGSER_REMARK" => null,
        ];
        $this->LOGSER_mod->insert($datalogser);

        $ckeys = str_replace("\u", "", $ckeys);
        $isinternal = false;
        if (strpos($ckeys, "|") !== false) {
            $xakey = explode("|", $ckeys);
            if (strpos($xakey[0], "&") !== false) {
                $isinternal = true;
            }
        }
        $myar = [];
        if ($isinternal) { // handle internal serial code
            $astr = explode("|", $ckeys);
            $creffcd = $astr[5];
            $creffcd = substr($creffcd, 2, strlen($creffcd));
            $rsser = $this->SER_mod->select_exact_byVAR(["SER_ID" => $creffcd]);
            if (count($rsser) > 0) {
                $jobno = '';
                $citem = '';
                $cqty = 0;
                foreach ($rsser as $r) {
                    $jobno = trim($r['SER_DOC']);
                    $citem = trim($r['SER_ITMID']);
                    $cqty = $r['SER_QTY'];
                }
                $datac = ['ITH_FORM' => 'INC-PRD-FG', 'ITH_SER' => $creffcd];
                if ($this->ITH_mod->check_Primary($datac) == 0) {
                    $datas = [
                        'ITH_ITMCD' => $citem, 'ITH_DATE' => $currdate, 'ITH_FORM' => 'INC-PRD-FG',
                        'ITH_DOC' => $jobno, 'ITH_QTY' => $cqty, 'ITH_SER' => $creffcd, 'ITH_WH' => 'ARPRD1',
                        'ITH_LUPDT' => $currrtime, 'ITH_USRID' => $this->session->userdata('nama'),
                    ];
                    $retITH = $this->ITH_mod->insert($datas);
                    if ($retITH > 0) {
                        $datar = ["cd" => "11", "msg" => "Saved", "typefg" => $cfgtype];
                    } else {
                        $datar = ["cd" => "0", "msg" => "Could not add stock"];
                    }
                } else {
                    $datar = ["cd" => "0", "msg" => "Serial is already scanned"];
                }
            } else {
                $datar = ["cd" => "0", "msg" => "Serial Not found"];
            }
        } else { // handle EPSON part
            if (strpos($ckeys, "|") !== false) {
                if ($cremark == '') {
                    //HANDLE ADDITIONAL VALIDATION FOR EPSON QR
                    $xajob = explode("-", $cjob);
                    $xakey = explode("|", $ckeys);
                    $itemfromkeys = substr($xakey[0], 2, strlen($xakey[0]));
                    if ($xajob[2] != $itemfromkeys) {
                        $cjob = $xajob[0] . "-" . $xajob[1] . "-" . $itemfromkeys;
                    }
                    //

                    $astr = explode("|", $ckeys);
                    $citem = $astr[0];
                    $citem = substr($citem, 2, strlen($citem));
                    $clot = $astr[2];
                    $clot = substr($clot, 2, strlen($clot));
                    $myjob = substr($astr[2], 5, 5);
                    if (substr($myjob, 0, 1) == '0') {
                        $myjob = substr($astr[2], 6, 4);
                    }
                    $ajob = explode('-', $cjob);
                    if ($myjob != $ajob[1]) {
                        $myar[] = ["cd" => "0", "msg" => "Job and Lot don't match"];
                        exit(json_encode($myar));
                    }
                    $cqty = str_replace(" ", "", $astr[3]);
                    $cqty = (int) substr($cqty, 2, strlen($cqty));
                    $creffcd = $astr[5];
                    $creffcd = substr($creffcd, 2, strlen($creffcd));
                    if (strlen($creffcd) != 16) {
                        $myar[] = ["cd" => "0", "msg" => "Reff No is not valid, please try again"];
                        exit(json_encode($myar));
                    }
                    if ($this->MSTITM_mod->check_Primary(["MITM_ITMCD" => trim($citem)]) == 0) {
                        if (strpos(strtoupper($citem), "WS") !== false) {
                            $citem = str_replace("_", " ", $citem);
                            $cjob = str_replace("_", " ", $cjob);
                        } else {
                            $RSItemLike = $this->MSTITM_mod->selectfgbyid_lk($citem);
                            if (empty($RSItemLike)) {
                                $myar[] = ["cd" => "0", "msg" => "Item is not registered in WMS Master Data ($citem)"];
                                exit(json_encode($myar));
                            }
                        }
                    }
                    $rsser = $this->SER_mod->selectbyVAR(["SER_ID" => $creffcd]);
                    if (count($rsser) > 0) {
                        $myar[] = ["cd" => "0", "msg" => "The label is already scanned"];
                        exit(json_encode($myar));
                    }
                    #Pengkhususan logika karena salah buat WO
                    if (strtoupper($cjob) === '23-5A16-222279401') {
                        $cjob = '23-5A16-22279401ES';
                        $citem = $citem . 'ES2';
                    }
                    # Akhir pengkhususan
                    $rsostqty = $this->SPL_mod->selectWOITEM($cjob, $citem);
                    $rsostku0 = $rsostqty;
                    if (!empty($rsostqty)) {
                    } else {
                        $newjob = substr($cjob, 2, 100);
                        $cjob = $nextYear . $newjob;
                        $rsostqty = $this->SPL_mod->selectWOITEM($cjob, $citem);
                        if (count($rsostqty) > 0) {
                        } else {
                            $newjob = substr($cjob, 2, 100);
                            $cjob = $prevYear . $newjob;
                            $rsostqty = $this->SPL_mod->selectWOITEM($cjob, $citem);
                        }
                    }
                    if (!empty($rsostqty)) {
                        $ostqty = 0;
                        $lotsizeqty = 0;
                        $grnqty = 0;
                        $flgclose = 0;

                        $bsgrp = '';
                        $cuscd = '';
                        foreach ($rsostqty as $r) {
                            $ostqty = $r['OSTQTY'] < $r['OSTQTYMG'] ? $r['OSTQTY'] : $r['OSTQTYMG'];
                            $lotsizeqty = $r['PDPP_WORQT'];
                            $grnqty = $r['PDPP_GRNQT'];
                            $bsgrp = trim($r['PDPP_BSGRP']);
                            $cuscd = trim($r['PDPP_CUSCD']);
                            $flgclose = trim($r['PDPP_COMFG']);
                            $FJOB = trim($r['PDPP_WONO']);
                        }
                        if ($lotsizeqty != $grnqty && $flgclose == 1) {
                            #START REPEAT
                            $newjob = substr($cjob, 2, 100);
                            $cjob = $nextYear . $newjob;
                            $rsostqty = $this->SPL_mod->selectWOITEM($cjob, $citem);
                            foreach ($rsostqty as $r) {
                                $ostqty = $r['OSTQTY'] < $r['OSTQTYMG'] ? $r['OSTQTY'] : $r['OSTQTYMG'];
                                $lotsizeqty = $r['PDPP_WORQT'];
                                $grnqty = $r['PDPP_GRNQT'];
                                $bsgrp = trim($r['PDPP_BSGRP']);
                                $cuscd = trim($r['PDPP_CUSCD']);
                                $flgclose = trim($r['PDPP_COMFG']);
                            }
                            #END REPEAT
                            if ($lotsizeqty != $grnqty && $flgclose == 1) {
                                #START REPEAT PREV
                                $newjob = substr($cjob, 2, 100);
                                $cjob = $prevYear . $newjob;
                                $rsostqty = $this->SPL_mod->selectWOITEM($cjob, $citem);
                                foreach ($rsostqty as $r) {
                                    $ostqty = $r['OSTQTY'] < $r['OSTQTYMG'] ? $r['OSTQTY'] : $r['OSTQTYMG'];
                                    $lotsizeqty = $r['PDPP_WORQT'];
                                    $grnqty = $r['PDPP_GRNQT'];
                                    $bsgrp = trim($r['PDPP_BSGRP']);
                                    $cuscd = trim($r['PDPP_CUSCD']);
                                    $flgclose = trim($r['PDPP_COMFG']);
                                }
                                #END REPEAT PREV
                                if ($lotsizeqty != $grnqty && $flgclose == 1) {
                                    $myar[] = ["cd" => "0", "msg" => "The JOB is closed directly"];
                                    exit(json_encode($myar));
                                }
                            }
                        }
                        if ($ostqty <= 0) {
                            #START REPEAT
                            $newjob = substr($cjob, 2, 100);
                            $cjob = $nextYear . $newjob;
                            $rsostqty = $this->SPL_mod->selectWOITEM($cjob, $citem);
                            foreach ($rsostqty as $r) {
                                $ostqty = $r['OSTQTY'] < $r['OSTQTYMG'] ? $r['OSTQTY'] : $r['OSTQTYMG'];
                                $lotsizeqty = $r['PDPP_WORQT'];
                                $grnqty = $r['PDPP_GRNQT'];
                                $bsgrp = trim($r['PDPP_BSGRP']);
                                $cuscd = trim($r['PDPP_CUSCD']);
                                $flgclose = trim($r['PDPP_COMFG']);
                            }
                            #END REPEAT
                            if ($ostqty <= 0) {
                                #START REPEAT PREV
                                $newjob = substr($cjob, 2, 100);
                                $cjob = $prevYear . $newjob;
                                $rsostqty = $this->SPL_mod->selectWOITEM($cjob, $citem);
                                foreach ($rsostqty as $r) {
                                    $ostqty = $r['OSTQTY'] < $r['OSTQTYMG'] ? $r['OSTQTY'] : $r['OSTQTYMG'];
                                    $lotsizeqty = $r['PDPP_WORQT'];
                                    $grnqty = $r['PDPP_GRNQT'];
                                    $bsgrp = trim($r['PDPP_BSGRP']);
                                    $cuscd = trim($r['PDPP_CUSCD']);
                                    $flgclose = trim($r['PDPP_COMFG']);
                                }
                                #END REPEAT PREV
                                if ($ostqty <= 0) {
                                    $myar[] = ["cd" => "0", "msg" => "The JOB is closed /", "data" => $rsostku0];
                                    exit(json_encode($myar));
                                }
                            }
                        }
                        if ($lotsizeqty == $grnqty) {
                            #START REPEAT
                            $newjob = substr($cjob, 2, 100);
                            $cjob = $nextYear . $newjob;
                            $rsostqty = $this->SPL_mod->selectWOITEM($cjob, $citem);
                            foreach ($rsostqty as $r) {
                                $ostqty = $r['OSTQTY'] < $r['OSTQTYMG'] ? $r['OSTQTY'] : $r['OSTQTYMG'];
                                $lotsizeqty = $r['PDPP_WORQT'];
                                $grnqty = $r['PDPP_GRNQT'];
                                $bsgrp = trim($r['PDPP_BSGRP']);
                                $cuscd = trim($r['PDPP_CUSCD']);
                                $flgclose = trim($r['PDPP_COMFG']);
                            }
                            #END REPEAT
                            if ($lotsizeqty == $grnqty) {
                                #START REPEAT PREV
                                $newjob = substr($cjob, 2, 100);
                                $cjob = $prevYear . $newjob;
                                $rsostqty = $this->SPL_mod->selectWOITEM($cjob, $citem);
                                foreach ($rsostqty as $r) {
                                    $ostqty = $r['OSTQTY'] < $r['OSTQTYMG'] ? $r['OSTQTY'] : $r['OSTQTYMG'];
                                    $lotsizeqty = $r['PDPP_WORQT'];
                                    $grnqty = $r['PDPP_GRNQT'];
                                    $bsgrp = trim($r['PDPP_BSGRP']);
                                    $cuscd = trim($r['PDPP_CUSCD']);
                                    $flgclose = trim($r['PDPP_COMFG']);
                                }
                                #END REPEAT PREV
                                if ($lotsizeqty == $grnqty) {
                                    $myar[] = ["cd" => "0", "msg" => "MEGA'S JOB is already closed"];
                                    exit(json_encode($myar));
                                }
                            }
                        }
                        if ($cqty > $ostqty) {
                            #START REPEAT
                            $newjob = substr($cjob, 2, 100);
                            $cjob = $nextYear . $newjob;
                            $rsostqty = $this->SPL_mod->selectWOITEM_open($cjob, $citem);
                            foreach ($rsostqty as $r) {
                                $ostqty = $r['OSTQTY'] < $r['OSTQTYMG'] ? $r['OSTQTY'] : $r['OSTQTYMG'];
                                $lotsizeqty = $r['PDPP_WORQT'];
                                $grnqty = $r['PDPP_GRNQT'];
                                $bsgrp = trim($r['PDPP_BSGRP']);
                                $cuscd = trim($r['PDPP_CUSCD']);
                                $flgclose = trim($r['PDPP_COMFG']);
                            }
                            #END REPEAT
                            if ($cqty > $ostqty) {
                                #START REPEAT PREV
                                $newjob = substr($cjob, 2, 100);
                                $cjob = $prevYear . $newjob;
                                $rsostqty = $this->SPL_mod->selectWOITEM_open($cjob, $citem);
                                foreach ($rsostqty as $r) {
                                    $ostqty = $r['OSTQTY'] < $r['OSTQTYMG'] ? $r['OSTQTY'] : $r['OSTQTYMG'];
                                    $lotsizeqty = $r['PDPP_WORQT'];
                                    $grnqty = $r['PDPP_GRNQT'];
                                    $bsgrp = trim($r['PDPP_BSGRP']);
                                    $cuscd = trim($r['PDPP_CUSCD']);
                                    $flgclose = trim($r['PDPP_COMFG']);
                                }
                                #END REPEAT PREV
                            }
                        }

                        if ($cqty > $ostqty) {
                            $datar = ["cd" => "0", "msg" => "Over JOB ..! $FJOB"];
                        } else {
                            #new rule 6 month from isudate
                            $current_ym = (int) date('Ym');
                            $_jobdate = '';
                            foreach ($rsostqty as $r) {
                                $xdate = date_create($r['PDPP_ISUDT']);
                                $jobyearmonth = (int) date_format($xdate, "Ym");
                                $_jobdate = new DateTime($r['PDPP_ISUDT']);
                            }
                            $_curdate = new DateTime(date('Y-m-d'));
                            $_interval = $_curdate->diff($_jobdate);

                            if ($_interval->format('%m') * 1 > 6) {
                                $myar[] = [
                                    "cd" => "0", "msg" => "the JOB should not be used anymore after 6 month", "data1" => $rsostqty, "current_ym" => $current_ym, "jobyearmonth" => $jobyearmonth, "_interval" => $_interval,
                                ];
                                die(json_encode($myar));
                            }
                            #end new rule
                            if ($flgclose == 1) {
                                $myar[] = ["cd" => "0", "msg" => "The JOB is closed ,.,"];
                                exit(json_encode($myar));
                            }

                            #new rule : consider Supplied PCB
                            $rsShortAgeSupply = $this->SPL_mod->select_ppsn2_xwo($cjob);
                            foreach ($rsShortAgeSupply as $sas) {
                                if ($ostqty > $sas['OSTSUPQTY']) {
                                    if ($cqty > $sas['OSTSUPQTY']) {
                                        $myar[] = ["cd" => "0", "msg" => "PCB is not enough, try use another JOB"];
                                        die(json_encode($myar));
                                    }
                                }
                            }
                            #end new rule

                            if (count($rsser) > 0) {
                                $datac = ['ITH_FORM' => 'INC-PRD-FG', 'ITH_SER' => $creffcd];
                                if ($this->ITH_mod->check_Primary($datac) == 0) {
                                    $datas = [
                                        'ITH_ITMCD' => $citem, 'ITH_DATE' => $currdate, 'ITH_FORM' => 'INC-PRD-FG',
                                        'ITH_DOC' => $cjob, 'ITH_QTY' => $cqty, 'ITH_SER' => $creffcd, 'ITH_WH' => 'ARPRD1',
                                        'ITH_LUPDT' => $currrtime, 'ITH_USRID' => $this->session->userdata('nama'),
                                    ];
                                    $retITH = $this->ITH_mod->insert($datas);
                                    if ($retITH > 0) {
                                        $datar = ["cd" => "11", "msg" => "Master OK, Saved directly to ITH", "typefg" => $cfgtype];
                                    } else {
                                        $datar = ["cd" => "0", "msg" => "Could not add stock"];
                                    }
                                } else {
                                    $datar = ["cd" => "0", "msg" => "Already scanned (regular)"];
                                }
                            } else {
                                $rsPSN = $this->SPL_mod->select_check_PSN_by_job($cjob);
                                if (count($rsPSN) == 0) {
                                    $myar[] = ["cd" => "0", "msg" => "Kitting for The Job is not ready reguler"];
                                    exit(json_encode($myar));
                                }
                                $datas = [
                                    "SER_ID" => $creffcd, "SER_DOC" => $cjob, "SER_REFNO" => $creffcd, "SER_ITMID" => $citem, "SER_LOTNO" => $clot, "SER_QTY" => $cqty, "SER_QTYLOT" => $cqty, "SER_RAWTXT" => $ckeys,
                                    "SER_BSGRP" => $bsgrp, "SER_CUSCD" => $cuscd, "SER_LUPDT" => $currrtime, "SER_USRID" => $this->session->userdata('nama'),
                                ];
                                if (strlen($creason) != 0) {
                                    $datas['SER_CAT'] = '2';
                                    $datas['SER_RMRK'] = $creason;
                                }
                                $retser = $this->SER_mod->insert($datas);
                                if ($retser > 0) {
                                    $datac = ['ITH_FORM' => 'INC-PRD-FG', 'ITH_SER' => $creffcd];
                                    if ($this->ITH_mod->check_Primary($datac) == 0) {
                                        $datas = [
                                            'ITH_ITMCD' => $citem, 'ITH_DATE' => $currdate, 'ITH_FORM' => 'INC-PRD-FG',
                                            'ITH_DOC' => $cjob, 'ITH_QTY' => $cqty, 'ITH_SER' => $creffcd, 'ITH_WH' => 'ARPRD1',
                                            'ITH_LUPDT' => $currrtime, 'ITH_USRID' => $this->session->userdata('nama'),
                                        ];
                                        $retITH = $this->ITH_mod->insert($datas);
                                        if ($retITH > 0) {

                                            $datar = ["cd" => "11", "msg" => "Saved", "typefg" => $cfgtype];
                                        } else {
                                            $datar = ["cd" => "0", "msg" => "Could not add stock"];
                                        }
                                    } else {
                                        $datar = ["cd" => "0", "msg" => "Successfully create label, but the serial is already in stock ?"];
                                    }
                                } else {
                                    $datar = ["cd" => "0", "msg" => "could not register master serial"];
                                }
                            }
                        }
                    } else {
                        $datar = ["cd" => "0", "msg" => "WO and Item don't match (regular)", "job" => $cjob, "item" => $citem];
                    }
                } else { // handle new model
                    //HANDLE ADDITIONAL VALIDATION FOR EPSON QR
                    $xajob = explode("-", $cjob);
                    $xakey = explode("|", $ckeys);
                    $itemfromkeys = substr($xakey[0], 2, strlen($xakey[0]));
                    if ($xajob[2] != $itemfromkeys) {
                        $cjob = $xajob[0] . "-" . $xajob[1] . "-" . $itemfromkeys;
                    }
                    $cjobWithoutYear = $xajob[1] . "-" . $itemfromkeys;
                    /// and handle
                    $astr = explode("|", $ckeys);
                    $citem = $astr[0];
                    $citem = substr($citem, 2, strlen($citem));
                    $clot = $astr[2];
                    $clot = substr($clot, 2, strlen($clot));
                    $myjob = substr($astr[2], 5, 5);
                    if (substr($myjob, 0, 1) == '0') {
                        $myjob = substr($astr[2], 6, 4);
                    }
                    $ajob = explode('-', $cjob);
                    if ($myjob != $ajob[1]) {
                        $myar[] = ["cd" => "0", "msg" => "Job and Lot don't match (New Model) [$myjob!=" . $ajob[1] . "]"];
                        exit(json_encode($myar));
                    }
                    $cqty = str_replace(" ", "", $astr[3]);
                    $cqty = (int) substr($cqty, 2, strlen($cqty));
                    $creffcd = $astr[5];
                    $creffcd = substr($creffcd, 2, strlen($creffcd));
                    if ($this->MSTITM_mod->check_Primary(["MITM_ITMCD" => trim($citem) . $cremark]) == 0) {
                        $myar[] = ["cd" => "0", "msg" => "Item is not registered in WMS Master Data (New Model)[" . trim($citem) . $cremark . "]"];
                        exit(json_encode($myar));
                    }
                    $rsser = $this->SER_mod->selectbyVAR(["SER_ID" => $creffcd]);
                    if (count($rsser) > 0) {
                        $myar[] = ["cd" => "0", "msg" => "The label is already scanned (New Model)"];
                        exit(json_encode($myar));
                    }
                    $rsostqty = $this->SPL_mod->selectWOITEM_es($cjobWithoutYear, $citem . $cremark);
                    if (count($rsostqty) > 0) {
                        $ostqty = 0;
                        $lotsizeqty = 0;
                        $grnqty = 0;
                        $flgclose = 0;

                        $bsgrp = '';
                        $cuscd = '';
                        $jobFromRs = '';
                        foreach ($rsostqty as $r) {
                            $ostqty = $r['OSTQTY'] < $r['OSTQTYMG'] ? $r['OSTQTY'] : $r['OSTQTYMG'];
                            $lotsizeqty = $r['PDPP_WORQT'];
                            $grnqty = $r['PDPP_GRNQT'];
                            $bsgrp = trim($r['PDPP_BSGRP']);
                            $cuscd = trim($r['PDPP_CUSCD']);
                            $flgclose = trim($r['PDPP_COMFG']);
                            $jobFromRs = $r['PDPP_WONO'];
                        }
                        if ($lotsizeqty != $grnqty && $flgclose == 1) {
                            $myar[] = ["cd" => "0", "msg" => "The JOB is closed directly"];
                            exit(json_encode($myar));
                        }
                        if ($ostqty <= 0) {
                            $myar[] = ["cd" => "0", "msg" => "The JOB is closed (New Model)"];
                            exit(json_encode($myar));
                        }
                        if ($lotsizeqty == $grnqty) {
                            $myar[] = ["cd" => "0", "msg" => "MEGA'S JOB is already closed (New Model)"];
                            exit(json_encode($myar));
                        }
                        if ($cqty > $ostqty) {
                            $datar = ["cd" => "0", "msg" => "Over JOB ! (New Model)"];
                        } else {
                            if (count($rsser) > 0) {
                                $datac = ['ITH_FORM' => 'INC-PRD-FG', 'ITH_SER' => $creffcd];
                                if ($this->ITH_mod->check_Primary($datac) == 0) {
                                    $datas = [
                                        'ITH_ITMCD' => $citem . $cremark, 'ITH_DATE' => $currdate, 'ITH_FORM' => 'INC-PRD-FG',
                                        'ITH_DOC' => $jobFromRs, 'ITH_QTY' => $cqty, 'ITH_SER' => $creffcd, 'ITH_WH' => 'ARPRD1',
                                        'ITH_LUPDT' => $currrtime, 'ITH_USRID' => $this->session->userdata('nama'),
                                    ];
                                    $retITH = $this->ITH_mod->insert($datas);
                                    if ($retITH > 0) {
                                        $datar = ["cd" => "11", "msg" => "Master OK, Saved directly to ITH (New Model)", "typefg" => $cfgtype];
                                    } else {
                                        $datar = ["cd" => "0", "msg" => "Could not add stock (New Model)"];
                                    }
                                } else {
                                    $datar = ["cd" => "0", "msg" => "Already scanned (New Model)"];
                                }
                            } else {
                                $rsPSN = $this->SPL_mod->select_check_PSN_by_job($jobFromRs);
                                if (count($rsPSN) == 0) {
                                    $myar[] = ["cd" => "0", "msg" => "Kitting for The Job is not ready"];
                                    exit(json_encode($myar));
                                }

                                $datas = [
                                    "SER_ID" => $creffcd, "SER_DOC" => $jobFromRs, "SER_REFNO" => $creffcd, "SER_ITMID" => $citem . $cremark, "SER_LOTNO" => $clot, "SER_QTY" => $cqty, "SER_QTYLOT" => $cqty, "SER_RAWTXT" => $ckeys,
                                    "SER_BSGRP" => $bsgrp, "SER_CUSCD" => $cuscd, "SER_LUPDT" => $currrtime, "SER_USRID" => $this->session->userdata('nama'),
                                ];
                                if (strlen($creason) != 0) {
                                    $datas['SER_CAT'] = '2';
                                    $datas['SER_RMRK'] = $creason;
                                }
                                $retser = $this->SER_mod->insert($datas);
                                if ($retser > 0) {
                                    $datac = ['ITH_FORM' => 'INC-PRD-FG', 'ITH_SER' => $creffcd];
                                    if ($this->ITH_mod->check_Primary($datac) == 0) {
                                        $datas = [
                                            'ITH_ITMCD' => $citem . $cremark, 'ITH_DATE' => $currdate, 'ITH_FORM' => 'INC-PRD-FG',
                                            'ITH_DOC' => $jobFromRs, 'ITH_QTY' => $cqty, 'ITH_SER' => $creffcd, 'ITH_WH' => 'ARPRD1',
                                            'ITH_LUPDT' => $currrtime, 'ITH_USRID' => $this->session->userdata('nama'),
                                        ];
                                        $retITH = $this->ITH_mod->insert($datas);
                                        if ($retITH > 0) {
                                            $datar = ["cd" => "11", "msg" => "Saved (New Model)", "typefg" => $cfgtype];
                                        } else {
                                            $datar = ["cd" => "0", "msg" => "Could not add stock (New Model)"];
                                        }
                                    } else {
                                        $datar = ["cd" => "0", "msg" => "Successfully create label, but the serial is already in stock ? (new model)"];
                                    }
                                } else {
                                    $datar = ["cd" => "0", "msg" => "could not register master serial (New Model)"];
                                }
                            }
                        }
                    } else {
                        $datar = ["cd" => "0", "msg" => "WO and Item don't match (new model).", 'rsos' => $rsostqty, 'job' => $cjob . $cremark, '$citem' => $citem, '$cjobWithoutYear' => $cjobWithoutYear];
                    }
                }
            } else { //handle KD ASP
                if ($cfgtype == "") {
                    $datar = ["cd" => "0", "msg" => "Please select KD or ASP"];
                } else {
                    if ($this->MSTITM_mod->check_Primary(["MITM_ITMCD" => trim($citemcd) . $cfgtype . $cremark_ka]) == 0) {
                        $myar[] = ["cd" => "0", "msg" => "Item is not registered in WMS Master Data [" . trim($citemcd) . $cfgtype . $cremark_ka . "]"];
                        exit(json_encode($myar));
                    }
                    $cjob = ($cismanual == '1') ? $cjob : $cjob . $cfgtype . $cremark_ka;

                    $rsostqty = $this->SPL_mod->selectWOITEM($cjob, $citemcd . $cfgtype);
                    if (count($rsostqty) > 0) {
                    } else {
                        $newjob = substr($cjob, 2, 100);
                        $cjob = $nextYear . $newjob;
                        $rsostqty = $this->SPL_mod->selectWOITEM($cjob, $citemcd . $cfgtype);
                        if (count($rsostqty) > 0) {
                        } else {
                            $newjob = substr($cjob, 2, 100);
                            $cjob = $prevYear . $newjob;
                            $rsostqty = $this->SPL_mod->selectWOITEM($cjob, $citemcd . $cfgtype);
                        }
                    }
                    if (count($rsostqty) > 0) {
                        $ostqty = 0;
                        $bsgrp = '';
                        $cuscd = '';
                        foreach ($rsostqty as $r) {
                            $ostqty = $r['OSTQTY'];
                            $lotsizeqty = $r['PDPP_WORQT'];
                            $grnqty = $r['PDPP_GRNQT'];
                            $bsgrp = trim($r['PDPP_BSGRP']);
                            $cuscd = trim($r['PDPP_CUSCD']);
                        }
                        if ($ostqty <= 0) {
                            $myar[] = ["cd" => "0", "msg" => "The JOB is closed"];
                            exit(json_encode($myar));
                        }
                        if ($lotsizeqty == $grnqty) {
                            $myar[] = ["cd" => "0", "msg" => "MEGA'S JOB is already closed"];
                            exit(json_encode($myar));
                        }
                        if ($cinqty > $ostqty) {
                            $datar = ["cd" => "0", "msg" => "Over WO !"];
                        } else {
                            $rsser = $this->SER_mod->selectbyVAR(["SER_ID" => $ckeys]);
                            if (count($rsser) > 0) {
                                $datac = ['ITH_FORM' => 'INC-PRD-FG', 'ITH_SER' => $ckeys];
                                if ($this->ITH_mod->check_Primary($datac) == 0) {
                                    $datas = [
                                        'ITH_ITMCD' => $citemcd . $cfgtype . $cremark_ka, 'ITH_DATE' => $currdate, 'ITH_FORM' => 'INC-PRD-FG',
                                        'ITH_DOC' => $cjob, 'ITH_QTY' => $cinqty, 'ITH_SER' => $ckeys, 'ITH_WH' => 'ARPRD1',
                                        'ITH_LUPDT' => $currrtime, 'ITH_USRID' => $this->session->userdata('nama'),
                                    ];
                                    $retITH = $this->ITH_mod->insert($datas);
                                    if ($retITH > 0) {
                                        $datar = ["cd" => "11", "msg" => "Master OK, Saved directly to ITH", "typefg" => $cfgtype . $cremark_ka];
                                    } else {
                                        $datar = ["cd" => "0", "msg" => "Could not add stock"];
                                    }
                                } else {
                                    $datar = ["cd" => "0", "msg" => "Successfully create label, but the serial is already in stock ?"];
                                }
                            } else {
                                $rsPSN = $this->SPL_mod->select_check_PSN_by_job($cjob);
                                if (count($rsPSN) == 0) {
                                    $myar[] = ["cd" => "0", "msg" => "Kitting for The Job is not ready KD ASP"];
                                    exit(json_encode($myar));
                                }
                                $datas = [
                                    "SER_ID" => $ckeys, "SER_DOC" => $cjob, "SER_REFNO" => $ckeys, "SER_ITMID" => $citemcd . $cfgtype . $cremark_ka, "SER_LOTNO" => $cinlot, "SER_QTY" => $cinqty, "SER_QTYLOT" => $cinqty,
                                    "SER_BSGRP" => $bsgrp, "SER_CUSCD" => $cuscd, "SER_LUPDT" => $currrtime, "SER_USRID" => $this->session->userdata('nama'),
                                ];
                                if (strlen($creason ? $creason : '') != 0) {
                                    $datas['SER_CAT'] = '2';
                                    $datas['SER_RMRK'] = $creason;
                                }
                                $retser = $this->SER_mod->insert($datas);
                                if ($retser > 0) {
                                    $datac = ['ITH_FORM' => 'INC-PRD-FG', 'ITH_SER' => $ckeys];
                                    if ($this->ITH_mod->check_Primary($datac) == 0) {
                                        $datas = [
                                            'ITH_ITMCD' => $citemcd . $cfgtype . $cremark_ka, 'ITH_DATE' => $currdate, 'ITH_FORM' => 'INC-PRD-FG',
                                            'ITH_DOC' => $cjob, 'ITH_QTY' => $cinqty, 'ITH_SER' => $ckeys, 'ITH_WH' => 'ARPRD1',
                                            'ITH_LUPDT' => $currrtime, 'ITH_USRID' => $this->session->userdata('nama'),
                                        ];
                                        $retITH = $this->ITH_mod->insert($datas);
                                        if ($retITH > 0) {
                                            $datar = ["cd" => "11", "msg" => "Saved", "typefg" => $cfgtype];
                                        } else {
                                            $datar = ["cd" => "0", "msg" => "Could not add stock"];
                                        }
                                    } else {
                                        $datar = ["cd" => "0", "msg" => "Successfully create label, but the serial is already in stock ?"];
                                    }
                                } else {
                                    $datar = ["cd" => "0", "msg" => "could not register master serial"];
                                }
                            }
                        }
                    } else {
                        $datar = ["cd" => "0", "msg" => "WO and Item don't match (KD.ASP) $cjob.$cfgtype, $citemcd"];
                    }
                }
            }
        }
        $myar[] = $datar;
        echo json_encode($myar);
    }

    public function setqa()
    {
        $this->checkSession();
        date_default_timezone_set('Asia/Jakarta');
        $currdate = date('Y-m-d');
        $currrtime = date('Y-m-d H:i:s');
        $cwh_inc = 'ARQA1';
        $cwh_out = 'ARPRD1';
        $cfm_inc = 'INC-QA-FG';
        $cfm_out = 'OUT-PRD-FG';
        $ccode = $this->input->get('incode') ? trim($this->input->get('incode')) : '??';
        $rsith = $this->ITH_mod->selectstock_ser($ccode);
        $whok = true;
        $myar = [];
        $openingLocation = '';
        foreach ($rsith as $r) {
            $openingLocation = trim($r['ITH_WH']);
            if ($openingLocation == 'QAFG') {
                $whok = false;
                break;
            } elseif (in_array($openingLocation, ['AFQART', 'AFQART2'])) {
                $myar[] = ['cd' => 0, 'msg' => 'denied, because the serial should not be here'];
                die('{"status":' . json_encode($myar) . '}');
            }
        }
        if (!$whok) {
            $myar[] = ['cd' => 0, 'msg' => 'denied, because the serial is in pending area'];
            die('{"status":' . json_encode($myar) . '}');
        }

        # penentu apakah sebuah reff berasal dari FG-RTN atau bukan
        if (in_array($openingLocation, ['NFWH4RT', 'AFWH3RT'])) {
            $rs_stktrn = $this->SER_mod->select_whcd_rtn($ccode);
            $rs_stktrn = count($rs_stktrn) > 0 ? reset($rs_stktrn) : ['STKTRND1_LOCCDFR' => '??'];
            $cwh_inc = $rs_stktrn['STKTRND1_LOCCDFR'];
            $rstes = $this->ITH_mod->selectstock_ser_rtn($ccode);
            $rstes = !empty($rstes) ? reset($rstes) : ['ITH_WH' => '??', 'ITH_LOC' => '???'];
            if ($rstes['ITH_WH'] == $cwh_inc && $rstes['ITH_LOC'] === 'QC') {
                $myar[] = ['cd' => 1, 'msg' => 'Serial is already scanned'];
            } else {
                $cfm_inc = 'INC-QCRTN-FG';
                $cfm_out = 'OUT-RC-FG';
                $datac = ['ITH_FORM' => $cfm_inc, 'ITH_SER' => $ccode];
                $rscode = $this->SER_mod->select_SER_byVAR(['SER_ID' => $ccode]);
                if (!empty($rscode)) {
                    $strstatus = false;
                    foreach ($rscode as $r) {
                        if ($r['SER_QTY'] > 0) {
                            $strstatus = true;
                        }
                        $datac['ITH_ITMCD'] = trim($r['SER_ITMID']);
                        $datac['ITH_FORM'] = $cfm_out;
                        $datac['ITH_DATE'] = $currdate;
                        $datac['ITH_DOC'] = $r['SER_DOC'];
                        $datac['ITH_QTY'] = -$r['SER_QTY'];
                        $datac['ITH_WH'] = $cwh_inc;
                        $datac['ITH_LOC'] = 'RC';
                        $datac['ITH_LUPDT'] = $currrtime;
                        $datac['ITH_USRID'] = $this->session->userdata('nama');
                    }
                    if ($strstatus) {
                        $this->ITH_mod->insert($datac);
                        $datac['ITH_FORM'] = $cfm_inc;
                        $datac['ITH_DATE'] = $currdate;
                        $datac['ITH_QTY'] = $r['SER_QTY'];
                        $datac['ITH_WH'] = $cwh_inc;
                        $datac['ITH_LOC'] = 'QC';
                        $datac['ITH_LUPDT'] = $currrtime;
                        $datac['ITH_USRID'] = $this->session->userdata('nama');
                        $this->ITH_mod->insert($datac);
                        $myar[] = ['cd' => 1, 'msg' => 'OK'];
                    } else {
                        $myar[] = ['cd' => 0, 'msg' => 'Could not scan splitted Label'];
                    }
                } else {
                    $myar[] = ['cd' => 0, 'msg' => 'Serial not found'];
                }
            }
        } else {
            $datac = ['ITH_FORM' => $cfm_inc, 'ITH_SER' => $ccode];
            if ($this->ITH_mod->check_Primary(["ITH_SER" => $ccode, "ITH_WH" => "AFWH3"]) > 0) {
                $myar[] = ['cd' => 0, 'msg' => 'The serial is already at Finished Goods Warehouse'];
                die('{"status":' . json_encode($myar) . '}');
            }
            if ($this->ITH_mod->check_Primary(["ITH_SER" => $ccode, "ITH_WH" => "ARSHP"]) > 0) {
                $myar[] = ['cd' => 0, 'msg' => 'The serial is already at Delivery Preparation Area'];
                die('{"status":' . json_encode($myar) . '}');
            }
            if (substr($ccode, 0, 1) == "3") {
                $myar[] = ['cd' => 0, 'msg' => 'this menu is not for status label'];
                die('{"status":' . json_encode($myar) . '}');
            }
            if ($this->SER_mod->check_Primary(["SER_ID" => $ccode]) > 0) {
                $rstes = $this->ITH_mod->selectstock_ser($ccode);
                $rstes = count($rstes) > 0 ? reset($rstes) : ['ITH_WH' => '??'];
                if ($rstes['ITH_WH'] == $cwh_inc) {
                    $myar[] = ['cd' => 1, 'msg' => 'Serial is already scanned'];
                } else {
                    #new validation check complete flag MEGA
                    $rsflag = $this->SER_mod->select_exact_byVAR([
                        'SER_ID' => $ccode, 'PDPP_COMFG' => '1',
                    ]);
                    if (!empty($rsflag)) {
                        $isalloww = true;
                        foreach ($rsflag as $t) {
                            if ($t['SER_ID'] == $t['SER_REFNO']) {
                                $isalloww = false;
                            }
                        }
                        if (!$isalloww) {
                            $myar[] = ['cd' => 0, 'msg' => 'the job number is already flagged as closed'];
                            die('{"status":' . json_encode($myar) . '}');
                        }
                    }
                    $dataf = ['SER_ID' => $ccode];
                    $rscode = $this->SER_mod->select_SER_byVAR($dataf);
                    if (!empty($rscode)) {
                        $strstatus = false;
                        $labelCategory = null;
                        foreach ($rscode as $r) {
                            if ($this->PWOP_mod->isMainUnit(trim($r['SER_ITMID']))) {
                                $myar[] = ['cd' => 0, 'msg' => 'You should use Scan Multilayer Menu for this label'];
                                die('{"status":' . json_encode($myar) . '}');
                            }
                            if ($r['SER_QTY'] > 0) {
                                $strstatus = true;
                            }
                            $datac['ITH_ITMCD'] = trim($r['SER_ITMID']);
                            $datac['ITH_FORM'] = $cfm_out;
                            $datac['ITH_DATE'] = $currdate;
                            $datac['ITH_DOC'] = $r['SER_DOC'];
                            $datac['ITH_QTY'] = -$r['SER_QTY'];
                            $datac['ITH_WH'] = $cwh_out;
                            $datac['ITH_LUPDT'] = $currrtime;
                            $datac['ITH_USRID'] = $this->session->userdata('nama');
                            $labelCategory = $r['SER_CAT'];
                        }
                        if ($strstatus) {
                            $this->ITH_mod->insert($datac);
                            $datac['ITH_FORM'] = $cfm_inc;
                            $datac['ITH_DATE'] = $currdate;
                            $datac['ITH_QTY'] = $r['SER_QTY'];
                            $datac['ITH_WH'] = $cwh_inc;
                            $datac['ITH_LUPDT'] = $currrtime;
                            $datac['ITH_USRID'] = $this->session->userdata('nama');
                            $this->ITH_mod->insert($datac);
                            $myar[] = ['cd' => 1, 'msg' => 'OK'];
                        } else {
                            $myar[] = ['cd' => 0, 'msg' => 'Could not scan splitted Label'];
                        }
                        if (!empty($labelCategory)) {
                            $this->SER_mod->updatebyId(['SER_CAT' => null, 'SER_RMRK' => 'AFTER REPAIR'], $ccode);
                        }
                    } else {
                        $myar[] = ['cd' => 0, 'msg' => 'Serial not found'];
                    }
                }
            } else {
                $myar[] = ['cd' => 0, 'msg' => "Serial is not registered $ccode"];
            }
        }
        die('{"status":' . json_encode($myar) . '}');
    }

    public function setwhrtn()
    {
        $this->checkSession();
        date_default_timezone_set('Asia/Jakarta');
        $currdate = date('Y-m-d');
        $currrtime = date('Y-m-d H:i:s');
        $cfm_inc = 'INC-WHRTN-FG';
        $cfm_out = 'OUT-QCRTN-FG';
        $ccode = $this->input->get('incode') ? $this->input->get('incode') : '??';
        $rsith = $this->ITH_mod->selectstock_ser($ccode);
        $whok = true;
        $myar = [];
        foreach ($rsith as $r) {
            if (trim($r['ITH_WH']) == 'QAFG') {
                $whok = false;
                break;
            }
        }
        if (!$whok) {
            $myar[] = ['cd' => 0, 'msg' => 'denied, because the serial is in pending area'];
            die('{"status":' . json_encode($myar) . '}');
        } else {
            $rsrtn = $this->SER_mod->select_fgrtn_oldassy_newassy($ccode);
            foreach ($rsrtn as $n) {
                if ($n['NEWFG'] != $n['OLDFG']) {
                    $cfm_inc = 'INC-WHRTN-FG-C';
                }
            }
            $rs_stktrn = $this->SER_mod->select_whcd_rtn($ccode);
            $rs_stktrn = count($rs_stktrn) > 0 ? reset($rs_stktrn) : ['STKTRND1_LOCCDFR' => '??'];
            $cwh_inc = $rs_stktrn['STKTRND1_LOCCDFR'];
            $cwh_out = $rs_stktrn['STKTRND1_LOCCDFR'];
            $rstes = $this->ITH_mod->selectstock_ser_rtn($ccode);
            $rstes = !empty($rstes) ? reset($rstes) : ['ITH_WH' => '??', 'ITH_LOC' => '???'];
            if ($cwh_inc == '??') {
                $myar[] = ['cd' => 0, 'msg' => 'the destination warehouse code is not found'];
            } else {
                if ($this->ITH_mod->check_Primary(['ITH_SER' => $ccode, 'ITH_WH' => $cwh_inc, 'ITH_LOC' => 'WH'])) {
                    $myar[] = ['cd' => 0, 'msg' => 'the label is already scanned'];
                } else {
                    $rstes = $this->ITH_mod->selectstock_ser_rtn($ccode);
                    $rstes = count($rstes) > 0 ? reset($rstes) : ['ITH_WH' => '??'];
                    if ($rstes['ITH_WH'] == $cwh_out && $rstes['ITH_LOC'] === 'QC') {
                        #check is already scanned by QC
                        $this->ITH_mod->insert([
                            'ITH_ITMCD' => $rs_stktrn['SER_ITMID']
                            , 'ITH_DATE' => $currdate
                            , 'ITH_FORM' => $cfm_out
                            , 'ITH_DOC' => $rs_stktrn['SER_DOC']
                            , 'ITH_QTY' => -$rs_stktrn['SER_QTY']
                            , 'ITH_WH' => $cwh_out
                            , 'ITH_LOC' => 'QC'
                            , 'ITH_SER' => $ccode
                            , 'ITH_LUPDT' => $currrtime
                            , 'ITH_USRID' => $this->session->userdata('nama'),
                        ]);
                        $this->ITH_mod->insert([
                            'ITH_ITMCD' => $rs_stktrn['SER_ITMID']
                            , 'ITH_DATE' => $currdate
                            , 'ITH_FORM' => $cfm_inc
                            , 'ITH_DOC' => $rs_stktrn['SER_DOC']
                            , 'ITH_QTY' => $rs_stktrn['SER_QTY']
                            , 'ITH_WH' => $cwh_inc
                            , 'ITH_LOC' => 'WH'
                            , 'ITH_SER' => $ccode
                            , 'ITH_LUPDT' => $currrtime
                            , 'ITH_USRID' => $this->session->userdata('nama'),
                        ]);
                        $myar[] = ['cd' => 1, 'msg' => 'OK'];
                    } else {
                        $myar[] = ['cd' => 0, 'msg' => 'the label is not passed qc '];
                    }
                }
            }
        }
        die('{"status":' . json_encode($myar) . '}');
    }

    public function internal_setqa($pid)
    {
        $this->checkSession();
        date_default_timezone_set('Asia/Jakarta');
        $currdate = date('Y-m-d');
        $currrtime = date('Y-m-d H:i:s');
        $cwh_inc = 'ARQA1';
        $cwh_out = 'ARPRD1';
        $cfm_inc = 'INC-QA-FG';
        $cfm_out = 'OUT-PRD-FG';
        $ccode = $pid;
        $rsith = $this->ITH_mod->selectstock_ser($ccode);
        $whok = true;
        foreach ($rsith as $r) {
            if (trim($r['ITH_WH']) == 'QAFG') {
                $whok = false;
                break;
            }
        }
        if (!$whok) {
            return 'denied, because the serial is in pending area';
        }
        $datac = ['ITH_FORM' => $cfm_inc, 'ITH_SER' => $ccode];
        if ($this->ITH_mod->check_Primary(["ITH_SER" => $ccode, "ITH_WH" => "AFWH3"]) > 0) {
            return 'The serial is already at Finished Goods Warehouse ';
        }
        if ($this->SER_mod->check_Primary(["SER_ID" => $ccode]) > 0) {
            if ($this->ITH_mod->check_Primary($datac) > 0) {
                return "Serial is already scanned";
            } else {
                $dataf = ['SER_ID' => $ccode];
                $rscode = $this->SER_mod->select_SER_byVAR($dataf);
                if (count($rscode) > 0) {
                    $strstatus = false;
                    foreach ($rscode as $r) {
                        if ($r['SER_QTY'] > 0) {
                            $strstatus = true;
                        }
                        $datac['ITH_ITMCD'] = trim($r['SER_ITMID']);
                        $datac['ITH_FORM'] = $cfm_out;
                        $datac['ITH_DATE'] = $currdate;
                        $datac['ITH_DOC'] = $r['SER_DOC'];
                        $datac['ITH_QTY'] = -$r['SER_QTY'];
                        $datac['ITH_WH'] = $cwh_out;
                        $datac['ITH_LUPDT'] = $currrtime;
                        $datac['ITH_USRID'] = $this->session->userdata('nama');
                    }
                    if ($strstatus) {
                        $toret = $this->ITH_mod->insert($datac);
                        $datac['ITH_FORM'] = $cfm_inc;
                        $datac['ITH_DATE'] = $currdate;
                        $datac['ITH_QTY'] = $r['SER_QTY'];
                        $datac['ITH_WH'] = $cwh_inc;
                        $datac['ITH_LUPDT'] = $currrtime;
                        $datac['ITH_USRID'] = $this->session->userdata('nama');
                        $toret = $this->ITH_mod->insert($datac);
                        return "OK";
                    } else {
                        return "Could not scan splitted Label";
                    }
                } else {
                    return "Serial not found";
                }
            }
        } else {
            return "Serial is not registered $ccode";
        }
    }

    public function checksubassy()
    {
        header('Content-Type: application/json');
        $myar = [];
        $cid = $this->input->get('inid');
        $rsmaster = $this->SER_mod->selectBCField_in([$cid]);
        if (count($rsmaster) > 0) {
            #IS ALREADY USED/PLOTTED
            if ($this->ITH_mod->check_Primary(['ITH_SER' => $cid, 'ITH_FORM' => 'OUT-USE']) > 0) {
                $myar[] = ['cd' => 0, 'msg' => 'the data is already used'];
            } else {
                $rs = $this->ITH_mod->selectstock_ser($cid);
                $rs = count($rs) > 0 ? reset($rs) : ['ITH_WH' => '??'];
                if ($rs['ITH_WH'] == 'AWIP1') {
                    $myar[] = ['cd' => 1, 'msg' => 'go ahead'];
                } else {
                    $myar[] = ['cd' => 0, 'msg' => 'the data is not scanned in "Scan Sub-Assy menu" '];
                }
            }
        } else {
            $myar[] = ['cd' => 0, 'msg' => 'the data is not found'];
        }
        exit('{"status": ' . json_encode($myar) . ', "data": ' . json_encode($rsmaster) . '}');
    }

    public function setqc_status()
    {
        $this->checkSession();
        date_default_timezone_set('Asia/Jakarta');
        $currdate = date('Y-m-d');
        $currrtime = date('Y-m-d H:i:s');
        $cwh_inc = 'AWIP1';
        $cwh_out = 'ARPRD1';
        $cfm_inc = 'INC';
        $cfm_out = 'OUT';
        $ccode = $this->input->get('incode');
        if ($ccode[0] != '3') {
            exit('this menu is for "status label" only');
        }
        $rsith = $this->ITH_mod->selectstock_ser($ccode);
        $whok = true;
        foreach ($rsith as $r) {
            if (trim($r['ITH_WH']) == 'QAFG') {
                $whok = false;
                break;
            }
        }
        if (!$whok) {
            exit('denied, because the serial is in pending area');
        }
        $datac = ['ITH_FORM' => $cfm_inc, 'ITH_SER' => $ccode];
        if ($this->ITH_mod->check_Primary(["ITH_SER" => $ccode, "ITH_WH" => "AWIP1"]) > 0) {
            exit('The serial is already scanned');
        }
        if ($this->SER_mod->check_Primary(["SER_ID" => $ccode]) > 0) {
            $rs = $this->ITH_mod->selectstock_ser($ccode);
            $rs = count($rs) > 0 ? reset($rs) : ['ITH_WH' => '??'];
            if ($rs['ITH_WH'] == $cwh_inc) { #
                echo "Serial is already scanned";
            } else {
                if ($this->ITH_mod->check_Primary(['ITH_SER' => $ccode, 'ITH_FORM' => 'OUT-USE'])) {
                    exit("Serial is already used");
                }
                $dataf = ['SER_ID' => $ccode];
                $rscode = $this->SER_mod->select_SER_byVAR($dataf);
                if (count($rscode) > 0) {
                    $strstatus = false;
                    foreach ($rscode as $r) {
                        if ($r['SER_QTY'] > 0) {
                            $strstatus = true;
                        }
                        $datac['ITH_ITMCD'] = trim($r['SER_ITMID']);
                        $datac['ITH_FORM'] = $cfm_out;
                        $datac['ITH_DATE'] = $currdate;
                        $datac['ITH_DOC'] = $r['SER_DOC'];
                        $datac['ITH_QTY'] = -$r['SER_QTY'];
                        $datac['ITH_WH'] = $cwh_out;
                        $datac['ITH_LUPDT'] = $currrtime;
                        $datac['ITH_USRID'] = $this->session->userdata('nama');
                    }
                    if ($strstatus) {
                        $this->ITH_mod->insert($datac);
                        $datac['ITH_FORM'] = $cfm_inc;
                        $datac['ITH_DATE'] = $currdate;
                        $datac['ITH_QTY'] = $r['SER_QTY'];
                        $datac['ITH_WH'] = $cwh_inc;
                        $datac['ITH_LUPDT'] = $currrtime;
                        $datac['ITH_USRID'] = $this->session->userdata('nama');
                        $this->ITH_mod->insert($datac);
                        echo "OK";
                    } else {
                        echo "Could not scan splitted Label";
                    }
                } else {
                    echo "Serial not found";
                }
            }
        } else {
            echo "Serial is not registered";
        }
    }

    public function tesaja()
    {
        $itemcode = $this->input->get('item');
        echo $this->PWOP_mod->isMainUnit($itemcode);
    }

    public function setml()
    {
        $this->checkSession();
        $PIC = $this->session->userdata('nama');
        header('Content-Type: application/json');
        date_default_timezone_set('Asia/Jakarta');
        $currentDate = date('Y-m-d');
        $currrtime = date('Y-m-d H:i:s');
        $caItemCode = $this->input->post('initmcd');
        $caItemJob = $this->input->post('initmjob');
        $caUniq = $this->input->post('inuniq');
        $cAssyUniq = $this->input->post('inuniqassy');
        $caQty = $this->input->post('inqty');
        $caItemCodeCount = count($caUniq);
        $myar = [];
        if ($caItemCodeCount > 1) {
            $rsser = $this->SER_mod->select_SER_byVAR(['SER_ID' => $cAssyUniq]);
            if (count($rsser) > 0) {
                $rsser = reset($rsser);
                #check is sub assy valid, WO
                $is_AssyQty_ok = true;
                $arows = [];
                for ($i = 0; $i < $caItemCodeCount; $i++) {
                    $isfound = false;
                    foreach ($arows as $b) {
                        if ($b['ITEMCD'] == $caItemCode[$i]) {
                            $isfound = true;
                            break;
                        }
                    }
                    if (!$isfound) {
                        $arows[] = ['ITEMCD' => $caItemCode[$i], 'QTY' => 0];
                    }
                }
                foreach ($arows as &$b) {
                    for ($i = 0; $i < $caItemCodeCount; $i++) {
                        if ($b['ITEMCD'] == $caItemCode[$i]) {
                            $b['QTY'] += $caQty[$i];
                        }
                    }
                }
                unset($b);

                if (count($arows) > 1) {
                    $befqty = '';
                    foreach ($arows as $b) {
                        $befqty = $b['QTY'];
                        break;
                    }
                    foreach ($arows as $b) {
                        if ($befqty != $b['QTY']) {
                            $is_AssyQty_ok = false;
                        }
                    }
                } else {
                    $is_AssyQty_ok = false;
                }
                if ($is_AssyQty_ok) {
                    $is_subassy_ok = true;
                    for ($i = 0; $i < $caItemCodeCount; $i++) {
                        if ($this->PWOP_mod->check_Primary(['PWOP_WONO' => $rsser['SER_DOC'], 'PWOP_BOMPN' => $caItemCode[$i]]) == 0) {
                            $is_subassy_ok = false;
                            break;
                        }
                    }

                    if ($is_subassy_ok) {
                        #INSERT TO MAP, ASSY <=> SUB ASSY
                        $datas = [];
                        for ($i = 0; $i < $caItemCodeCount; $i++) {
                            $datas[] = [
                                'SERML_NEWID' => $cAssyUniq,
                                'SERML_COMID' => $caUniq[$i],
                                'SERML_USRID' => $PIC,
                                'SERML_LUPDT' => $currrtime,
                            ];
                        }

                        $this->SERML_mod->insertb($datas);

                        #OUT SUB ASSY FROM WIP
                        $isAllOUT = true;
                        for ($i = 0; $i < $caItemCodeCount; $i++) {
                            $rs = $this->ITH_mod->selectstock_ser($caUniq[$i]);
                            $rs = count($rs) > 0 ? reset($rs) : ['ITH_WH' => '??'];
                            if ($rs['ITH_WH'] == "AWIP1") {
                                $this->ITH_mod->insert([
                                    'ITH_ITMCD' => $caItemCode[$i], 'ITH_FORM' => 'OUT-USE', 'ITH_DATE' => $currentDate, 'ITH_DOC' => $caItemJob[$i], 'ITH_QTY' => -$caQty[$i], 'ITH_WH' => $rs['ITH_WH'], 'ITH_SER' => $caUniq[$i], 'ITH_LUPDT' => $currrtime, 'ITH_USRID' => $PIC,
                                ]);
                            } else {
                                $myar[] = ['cd' => 0, 'msg' => 'could not set out sub assy'];
                                $isAllOUT = false;
                                $this->SERML_mod->deletebyID(['SERML_NEWID' => $cAssyUniq]);
                                break;
                            }
                        }
                        if ($isAllOUT) {
                            #INSERT TO ITH LIKE SINGLE ASSY
                            $info = $this->internal_setqa($cAssyUniq);
                            if ($info == 'OK') {
                                $myar[] = ['cd' => 1, 'msg' => 'OK.....'];
                            } else {
                                $myar[] = ['cd' => 0, 'msg' => $info];
                                #roll back
                                $this->SERML_mod->deletebyID(['SERML_NEWID' => $cAssyUniq]);
                                for ($i = 0; $i < $caItemCodeCount; $i++) {
                                    $this->ITH_mod->deletebyID([
                                        'ITH_SER' => $caUniq[$i], 'ITH_WH' => $rs['ITH_WH'], 'ITH_FORM' => 'OUT-USE',
                                    ]);
                                }
                            }
                        } else {
                            for ($i = 0; $i < $caItemCodeCount; $i++) {
                                $this->ITH_mod->deletebyID([
                                    'ITH_SER' => $caUniq[$i], 'ITH_WH' => $rs['ITH_WH'], 'ITH_FORM' => 'OUT-USE',
                                ]);
                            }
                            $myar[] = ['cd' => 0, 'msg' => 'could not set out sub assy, roll back...'];
                        }
                    } else {
                        $myar[] = ['cd' => 0, 'msg' => "SUB-ASSY and ASSY don't match"];
                    }
                } else {
                    $myar[] = ['cd' => 0, 'msg' => "SUB-ASSY QTY and ASSY QTY don't match"];
                }
            } else {
                $myar[] = ['cd' => 0, 'msg' => 'Assy ID have not passed PRD Scanning Area yet'];
            }
        } else {
            $myar[] = ['cd' => 0, 'msg' => 'we could not proces'];
        }
        exit('{"status":' . json_encode($myar) . '}');
    }

    public function tes()
    {
        header('Content-Type: application/json');
        $dataf = ['SER_ID' => 'FTK4I9GSJHIXMOYG'];
        $rscode = $this->SER_mod->select_SER_byVAR($dataf);
        $strstatus = false;
        foreach ($rscode as $r) {
            if ($r['SER_QTY'] > 0) {
                $strstatus = true;
            }
        }
        die('{"data": ' . json_encode($rscode) . ', "status": "' . $strstatus . '"}');
    }

    public function setwh()
    {
        $this->checkSession();
        date_default_timezone_set('Asia/Jakarta');
        $currdate = date('Y-m-d');
        $currrtime = date('Y-m-d H:i:s');
        $cwh_inc = $_COOKIE["CKPSI_WH"];
        $cwh_out = 'ARQA1';
        $cfm_inc = 'INC-WH-FG';
        $cfm_out = 'OUT-QA-FG';
        $cfm_bef = 'INC-QA-FG';
        $ccode = $this->input->get('incode');
        $cloc = $this->input->get('inloc');
        $datac = ['ITH_FORM' => $cfm_out, 'ITH_SER' => $ccode];

        //check location-wh
        $dataloc = ['MSTLOC_GRP' => $cwh_inc, 'MSTLOC_CD' => $cloc];
        if ($this->MSTLOC_mod->check_Primary($dataloc) == 0) {
            exit('Location not found !');
        }
        //end check

        // check is serial have passed qc
        $datacb = ['ITH_FORM' => $cfm_bef, 'ITH_SER' => trim($ccode)];
        if ($this->ITH_mod->check_Primary($datacb) == 0) {
            exit('The serial have not passed QC Area yet!');
        }
        //en check

        if ($this->RCVFGSCN_mod->check_Primary(['RCVFGSCN_SER' => $ccode, 'RCVFGSCN_SAVED' => '1']) > 0) {
            exit('Serial is already scanned by HT');
        }

        if ($this->ITH_mod->check_Primary($datac) > 0) {
            echo "Serial is already scanned";
        } else {
            $rscode = $this->SER_mod->select_exact_byVAR(['SER_ID' => $ccode]);
            if (count($rscode) > 0) {
                foreach ($rscode as $r) {
                    $datac['ITH_ITMCD'] = trim($r['SER_ITMID']);
                    $datac['ITH_DATE'] = $currdate;
                    $datac['ITH_DOC'] = $r['SER_DOC'];
                    $datac['ITH_QTY'] = -$r['SER_QTY'];
                    $datac['ITH_WH'] = $cwh_out;
                    $datac['ITH_LUPDT'] = $currrtime;
                    $datac['ITH_USRID'] = $this->session->userdata('nama');
                }
                $toret = $this->ITH_mod->insert($datac);

                $datac['ITH_FORM'] = $cfm_inc;
                $datac['ITH_DATE'] = $currdate;
                $datac['ITH_QTY'] = abs($datac['ITH_QTY']);
                $datac['ITH_WH'] = $cwh_inc;
                $datac['ITH_LOC'] = $cloc;
                $datac['ITH_LUPDT'] = $currrtime;
                $datac['ITH_USRID'] = $this->session->userdata('nama');
                $toret = $this->ITH_mod->insert($datac);
                echo "OK";
            } else {
                echo "Serial not found";
            }
        }
    }

    public function gettodayscanqa()
    {
        header('Content-Type: application/json');
        $rs = $this->ITH_mod->selecttodayscanqa();
        echo '{"data":' . json_encode($rs) . '}';
    }
    public function gettodayscanwhrtn()
    {
        header('Content-Type: application/json');
        $rs = $this->ITH_mod->selecttodayscanwhrtn();
        echo '{"data":' . json_encode($rs) . '}';
    }
    public function gettodayscanWIP()
    {
        header('Content-Type: application/json');
        $rs = $this->ITH_mod->selecttodayscanWIP();
        echo '{"data":' . json_encode($rs) . '}';
    }
    public function gettodayscanprd()
    {
        header('Content-Type: application/json');
        $rs = $this->ITH_mod->selecttodayscanprd();
        echo '{"data":' . json_encode($rs) . '}';
    }
    public function gettodayscanwh()
    {
        header('Content-Type: application/json');
        $rs = $this->ITH_mod->selecttodayscanwh();
        echo '{"data":' . json_encode($rs) . '}';
    }

    public function form_fgrtn_to_scr()
    {
        $this->load->view('wms/vfgrtn_scrap');
    }

    public function vchg_inc_date()
    {
        $this->load->view('wms/vchg_incfg_date');
    }
    public function vfgtowip()
    {
        $this->load->view('wms/vfgtowip');
    }
    public function form_scanprd_ng()
    {
        $data['rsjob'] = $this->SER_mod->select_joblbl_ost();
        $this->load->view('wms/vincomingfg_prd_ng', $data);
    }
}
