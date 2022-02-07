<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class MPurposeDLV extends CI_Controller {

	public function __construct()
	{
		parent::__construct();
		$this->load->model('refceisa/MPurposeDLV_mod');
		$this->load->model('refceisa/MPurposeDLV_imod');
		$this->load->helper('url');
		$this->load->library('session');
	}
	public function index()
	{
		echo "sorry";
	}	

    public function getbyPar(){
        header('Content-Type: application/json');	
        $cid = 	$this->input->get('inid');
        $dataw = array(
            'KODE_DOKUMEN' => $cid
        );
		$rs 	= $this->MPurposeDLV_mod->selectbyvar($dataw);
		echo json_encode($rs);		
	}
	
	public function create(){
		$this->load->view('refceisa/vprpsdlv');
	}        
	
	public function finddiff(){        
        header('Content-Type: application/json');
        $rs = $this->MPurposeDLV_imod->selectAll();
        $rsm = $this->MPurposeDLV_mod->selectAll();
        $a_id = array();
        $a_diff = array();
        foreach($rs as $r){
            array_push($a_id, trim($r['KODE_DOKUMEN']).trim($r['KODE_TUJUAN_PENGIRIMAN']));
        }
		$a_idm = array();
		$a_cd = array();
        foreach($rsm as $r){
			array_push($a_idm, trim($r['KODE_DOKUMEN']).trim($r['KODE_TUJUAN_PENGIRIMAN']));			
        }
        foreach($rs as $r){
            if(!in_array(trim($r['KODE_DOKUMEN']).trim($r['KODE_TUJUAN_PENGIRIMAN']),$a_idm)){ 
                array_push( $a_diff, array('ID' =>$r['KODE_DOKUMEN'], 'CD' => $r['KODE_TUJUAN_PENGIRIMAN'] ,'DESC' => $r['URAIAN_TUJUAN_PENGIRIMAN']) );
            }
        }
        echo json_encode($a_diff);
    }

    public function search(){
		header('Content-Type: application/json');
		$cid        = $this->input->get('cid');
		$csrchkey   = $this->input->get('csrchby');
		$rs = array();
		switch($csrchkey){
			case 'cd':
				$rs = $this->MPurposeDLV_mod->selectbyid_lk($cid);break;
			case 'in':
				$rs = $this->MPurposeDLV_mod->selectbynm_lk($cid);break;			
		}					
		echo json_encode($rs);
    }

    public function set(){
        $cdoccd = $this->input->post('initmcd');
        $cprpscd = $this->input->post('inprpscode');
        $cdsc = $this->input->post('indesc');
		$datac = array('KODE_DOKUMEN'=> $cdoccd, 'KODE_TUJUAN_PENGIRIMAN' => $cprpscd);
		if($this->MPurposeDLV_mod->check_Primary($datac)==0){
            $lastid = $this->MPurposeDLV_mod->selectlastid();
            $lastid++;
			$datas = array(
                'ID' => $lastid,
				'KODE_DOKUMEN' => $cdoccd,
                'KODE_TUJUAN_PENGIRIMAN' => $cprpscd,
                'URAIAN_TUJUAN_PENGIRIMAN' => $cdsc		
			);
			$toret = $this->MPurposeDLV_mod->insert($datas);
			if($toret>0){ echo "Saved successfully";}
		} else {
			$datau = array(
				'URAIAN_TUJUAN_PENGIRIMAN' => $cdsc,
			);
			$toret = $this->MPurposeDLV_mod->updatebyId($datau, $datac);
			if($toret>0){ 
                echo "Updated successfully";
            } else {
                echo "Could not be updated";
            }
		}
	}  
	
	public function setsync(){
		header('Content-Type: application/json');
		$cindex 	= $this->input->post('inx');
        $cdoccd		= $this->input->post('indoccd');
		$cprpscd	= $this->input->post('inprpscd');
		$cdesc		= $this->input->post('indesc');
				
		$datas = array(
			'KODE_DOKUMEN' =>  trim($cdoccd),
			'KODE_TUJUAN_PENGIRIMAN' =>  trim($cprpscd)
		);
		$msg = "";
		if($this->MPurposeDLV_mod->check_Primary($datas)>0){
			$msg = "Already Synchronized";
		} else {
			$lastid = $this->MPurposeDLV_mod->selectlastid();
            $lastid++;
            $datas = array(
                'KODE_DOKUMEN' =>  trim($cdoccd),
				'KODE_TUJUAN_PENGIRIMAN' =>  trim($cprpscd),
				'URAIAN_TUJUAN_PENGIRIMAN' => $cdesc,
				'ID' => $lastid
            );
			$toret = $this->MPurposeDLV_mod->insert($datas);
			if($toret>0){
				$msg = "Synchronized";
			} else {
				$msg = "Try again";
			}
		}			
		$anar = array("indx" => $cindex, "status"=> $msg);
		$myar = array();
		array_push($myar, $anar);
		echo json_encode($myar);
    }
	
}
