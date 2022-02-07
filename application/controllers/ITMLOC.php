<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class ITMLOC extends CI_Controller {

	public function __construct()
	{
		parent::__construct();
        $this->load->model('ITMLOC_mod');
        $this->load->model('MSTLOCG_mod');
        $this->load->model('XBGROUP_mod');
        $this->load->helper('url');
        $this->load->helper('download');
		$this->load->library('session');
	}
	public function index()
	{
		echo "sorry";
	}

	public function create(){
        $data['lloc'] = $this->MSTLOCG_mod->selectall_where_CODE_in( ['ARWH1', 'ARWH2','NRWH2']);
        $rsbg = $this->XBGROUP_mod->selectall();
        $lbg = '';
        foreach($rsbg as $r){
            $lbg .= '<option value="'.trim($r->MBSG_BSGRP).'">'.trim($r->MBSG_DESC).'</option>';
        }
        $data['lbg'] = $lbg;
		$this->load->view('wms/vitmloc', $data);
	}	
	
	public function set(){
        date_default_timezone_set('Asia/Jakarta');
        $currrtime = date('Y-m-d H:i:s');
        $business = $this->input->post('inbg');
        $citem = $this->input->post('initem');
        $clocg = $this->input->post('inlocg');
        $cloca = $this->input->post('inloc');
        $datac = ['ITMLOC_ITM' => $citem, 'ITMLOC_LOC' => $cloca];
        if($this->ITMLOC_mod->check_Primary($datac)==0){
            $datas = [
                'ITMLOC_BG' => $business,'ITMLOC_ITM' => $citem, 'ITMLOC_LOCG' => $clocg, 'ITMLOC_LOC' => $cloca, 
                'ITMLOC_LUPDT' => $currrtime, 'ITMLOC_USRID' => $this->session->userdata('nama')
            ];
            $toret = $this->ITMLOC_mod->insert($datas);
            if($toret>0){
                echo "Added successfully";
            } else {
                echo "Could not add the data";
            }
        } else {
            echo "The data is already added";
        }
    }
    
    public function getbyitemcd(){
        header('Content-Type: application/json');
        $ccd = $this->input->get('incd');
        $rs = $this->ITMLOC_mod->selectbyitemcd($ccd);
        echo json_encode($rs);
    }

    public function remove(){
        $citem = $this->input->get('initem');$clocg = $this->input->get('inlocg'); $cloc = $this->input->get('inloc');
        $datad = ['ITMLOC_ITM' => $citem , 'ITMLOC_LOC' => $cloc, 'ITMLOC_LOCG' => $clocg];
        $toret  = $this->ITMLOC_mod->deletebyID($datad);
        if($toret>0){
            echo "Deleted successfully";
        } else {
            echo "No data deleted";
        }
    }

    public function getlinkitemtemplate(){
        $murl   = ((isset($_SERVER['HTTPS']) && $_SERVER['HTTPS'] == "on") ? "https" : "http");
        $murl   .= "://".$_SERVER['HTTP_HOST'];
        echo $murl."/wms/ITMLOC/downloadtemplate";
    }

    function downloadtemplate(){
        $theurl = 'assets/userxls_template/tmpl_itemloc.xls' ;//base_url("assets/userdata_templates/tmp_wip.xlsx");
        force_download($theurl , null );
        echo $theurl;
    }

    public function import(){
        date_default_timezone_set('Asia/Jakarta');
        $currrtime = date('Y-m-d H:i:s');        
        $citem     = $this->input->post('initem');
        $cloc      = $this->input->post('inloc');
        $clocg     = $this->input->post('inlocg');
        $crowid    = $this->input->post('inrowid');        
        $datac = array(
            'ITMLOC_ITM' => $citem, 'ITMLOC_LOC' => $cloc, 'ITMLOC_LOCG' => $clocg
        );
        $myar = array();
        if($this->ITMLOC_mod->check_Primary($datac)==0){         
            $datas = array(                
                'ITMLOC_ITM' => $citem, 'ITMLOC_LOC' => $cloc, 'ITMLOC_LOCG' => $clocg,
                'ITMLOC_LUPDT' => $currrtime, 'ITMLOC_USRID' =>$this->session->userdata('nama')
            );
            $toret = $this->ITMLOC_mod->insert($datas);            
            if($toret>0){
                $anar = array("indx" => $crowid, "status"=> 'Saved successfully');                                
            } else {
                $anar = array("indx" => $crowid, "status"=> 'Could not insert');
            }            
        } else {
            $anar = array("indx" => $crowid, "status"=> 'Already exist');
        }
        array_push($myar, $anar);
        echo json_encode($myar);
    }

    
}
