<?php
defined('BASEPATH') or exit('No direct script access allowed');

class MSTLOC extends CI_Controller
{
    public function __construct()
    {
        parent::__construct();
        $this->load->helper('url');
        $this->load->helper('download');
        $this->load->library('session');
        $this->load->model('MSTLOCG_mod');
        $this->load->model('MSTLOC_mod');
        date_default_timezone_set('Asia/Jakarta');
    }
    public function index()
    {
        echo "sorry";
    }
    public function create()
    {
        $this->load->view('wms/vloc');
    }

    public function get_return_fg_warehouse()
    {
        header('Content-Type: application/json');
        $rs = $this->MSTLOCG_mod->select_cols_lk(['MSTLOCG_ID', 'MSTLOCG_NM'], ['MSTLOCG_NM' => 'RETURN']);
        $rs_j = [];
        foreach ($rs as $r) {
            $rs_j[] = [
                'id' => trim($r->MSTLOCG_ID)
                , 'text' => trim($r->MSTLOCG_NM),
            ];
        }
        exit(json_encode($rs_j));
    }

    public function setloc()
    {

        $currrtime = date('Y-m-d H:i:s');
        $cnm = $this->input->post('innm');
        $cid = $this->input->post('inid');
        //check primary
        $datac = ['MSTLOCG_ID' => $cid];
        if (strlen(trim($cid)) === 0) {
            die('Location Code is required');
        }
        if ($this->MSTLOCG_mod->check_Primary($datac) > 0) {
            $datau = [
                'MSTLOCG_NM' => $cnm, 'MSTLOCG_LUPDT' => $currrtime, 'MSTLOCG_USRID' => $this->session->userdata('nama'),
            ];
            $toret = $this->MSTLOCG_mod->updatebyId($datau, $cid);
            if ($toret > 0) {echo "Updated";}
        } else {
            $datas = [
                'MSTLOCG_ID' => $cid, 'MSTLOCG_NM' => $cnm,
                'MSTLOCG_LUPDT' => $currrtime, 'MSTLOCG_USRID' => $this->session->userdata('nama'),
            ];
            $toret = $this->MSTLOCG_mod->insert($datas);
            if ($toret > 0) {
                echo "Saved successfully";
            }
        }
    }

    public function EquipmentWarehouse()
    {
        header('Content-Type: application/json');
        $rs = $this->MSTLOCG_mod->selectall_where_CODE_in(['ENGEQUIP',
            'ENGLINEEQUIP',
            'ENGSCREQUIP',
            'MFG1EQUIP',
            'MFG1SCREQUIP',
            'MFG2EQUIP',
            'MFG2SCREQUIP',
            'PPICEQUIP',
            'PPICSCREQUIP',
            'PRCSCREQUIP',
            'PSIEQUIP',
            'QAEQUIP',
            'QASCREQUIP',
            'ICTEQUIP',
            'ICTSCREQUIP',
            'FCTEQUIP',
            'FCTSCREQUIP',
            'HRDEQUIP',
        ]);
        die(json_encode(['data' => $rs]));
    }

    public function setloc_d()
    {
        $currrtime = date('Y-m-d H:i:s');
        $cgroup = $this->input->post('ingroup');
        $crack = $this->input->post('inrack');

        $cno = $this->input->post('inno');
        $ccat = $this->input->post('incat');
        $cisnew = $this->input->post('inisnew');
        $ccd = $this->input->post('inrackcd');
        $crackold = $this->input->post('inoldrack');
        $corderno = $this->input->post('inorderno');
        $corderno = ($corderno == '' || $corderno == '0' ? null : $corderno);
        if ($cisnew == 'true') {
            $datac = array(
                'MSTLOC_CD' => $ccd, 'MSTLOC_GRP' => $cgroup,
            );
            if ($this->MSTLOC_mod->check_Primary($datac) == 0) {
                $datas = array(
                    'MSTLOC_CD' => $ccd,
                    'MSTLOC_GRP' => $cgroup, 'MSTLOC_RACK' => $crack,
                    'MSTLOC_NO' => $cno, 'MSTLOC_CAT' => $ccat,
                    'MSTLOC_LUPDT' => $currrtime, 'MSTLOC_USRID' => $this->session->userdata('nama'),
                );
                $toret = $this->MSTLOC_mod->insert($datas);
                if ($toret > 0) {
                    echo "Saved successfully";
                }
            } else {
                echo "Rack is already registered";
            }
        } else {
            $datau = array(
                'MSTLOC_CD' => $ccd,
                'MSTLOC_GRP' => $cgroup, 'MSTLOC_RACK' => $crack,
                'MSTLOC_NO' => $cno, 'MSTLOC_CAT' => $ccat,
                'MSTLOC_LUPDT' => $currrtime, 'MSTLOC_USRID' => $this->session->userdata('nama'),
            );
            $datacr = array(
                'MSTLOC_GRP' => $cgroup, 'MSTLOC_CD' => $crackold,
            );
            $toret = $this->MSTLOC_mod->updatebyId($datau, $datacr);
            if ($toret > 0) {echo "Updated";}
        }
    }
    public function search()
    {
        header('Content-Type: application/json');
        $ckey = $this->input->get('insearch');
        $rs = $this->MSTLOCG_mod->selectbynm_lk($ckey);
        echo json_encode($rs);
    }
    public function getall()
    {
        header('Content-Type: application/json');
        $rs = $this->MSTLOC_mod->selectAll();
        echo '{"data":';
        echo json_encode($rs);
        echo '}';
    }
    public function getbygroup()
    {
        header('Content-Type: application/json');
        $cgrp = $this->input->get('ingrp');
        $rs = $this->MSTLOC_mod->selectwithparent_byid($cgrp);
        echo '{"data":';
        echo json_encode($rs);
        echo '}';
    }
    public function import()
    {
        date_default_timezone_set('Asia/Jakarta');
        $currrtime = date('Y-m-d H:i:s');
        $cgroup = $this->input->post('ingroup');
        $crack = $this->input->post('inrack');
        $crackcd = $this->input->post('inrackcd');
        $cno = $this->input->post('inno');
        $ccat = $this->input->post('incat');
        $crow = $this->input->post('inrow');
        $datac = array(
            'MSTLOC_CD' => $crackcd, 'MSTLOC_GRP' => $cgroup,
        );
        if ($this->MSTLOC_mod->check_Primary($datac) == 0) {
            $datas = array(
                'MSTLOC_CD' => $crackcd,
                'MSTLOC_GRP' => $cgroup, 'MSTLOC_RACK' => $crack,
                'MSTLOC_NO' => $cno, 'MSTLOC_CAT' => $ccat,
                'MSTLOC_LUPDT' => $currrtime, 'MSTLOC_USRID' => $this->session->userdata('nama'),
            );
            $toret = $this->MSTLOC_mod->insert($datas);
            if ($toret > 0) {
                $anar = array("indx" => $crow, "status" => 'Saved successfully');
                $myar = array();
                array_push($myar, $anar);
                echo json_encode($myar);
            }
        } else {
            $datau = array(
                'MSTLOC_CD' => $crackcd,
                'MSTLOC_GRP' => $cgroup, 'MSTLOC_RACK' => $crack,
                'MSTLOC_NO' => $cno, 'MSTLOC_CAT' => $ccat,
                'MSTLOC_LUPDT' => $currrtime, 'MSTLOC_USRID' => $this->session->userdata('nama'),
            );
            $toret = $this->MSTLOC_mod->updatebyUnique($datau, $datac);
            if ($toret > 0) {
                $anar = array("indx" => $crow, "status" => 'Updated');
                $myar = array();
                array_push($myar, $anar);
                echo json_encode($myar);
            }
        }
    }

    public function getlinkitemtemplate()
    {
        $murl = ((isset($_SERVER['HTTPS']) && $_SERVER['HTTPS'] == "on") ? "https" : "http");
        $murl .= "://" . $_SERVER['HTTP_HOST'];
        echo $murl . "/wms/MSTLOC/downloadtemplate";
    }

    public function downloadtemplate()
    {
        $theurl = 'assets/userxls_template/tmpl_location.xls';
        force_download($theurl, null);
        echo $theurl;
    }

    public function checkisExist()
    {
        $cid = $this->input->get('inid');
        $toret = $this->MSTLOC_mod->check_Primary(array('MSTLOC_ID' => $cid));
        echo $toret;
    }

    public function checkisExist2()
    {
        $cid = $this->input->get('incd');
        $cwh = $_COOKIE["CKPSI_WH"];
        $toret = $this->MSTLOC_mod->check_Primary(array('MSTLOC_GRP' => $cwh, 'MSTLOC_CD' => $cid));
        echo $toret;
    }
}
