<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class MTPB extends CI_Controller {

	public function __construct()
	{
		parent::__construct();
        $this->load->model('MTPB_imod');
        $this->load->model('MTPB_mod');
		$this->load->helper('url');
		$this->load->library('session');
	}
	public function index()
	{
		echo "sorry";
    }

    public function setsync(){
		header('Content-Type: application/json');
		$cindex = $this->input->post('inx');
        $citem	= $this->input->post('initem');
        $cdesc	= $this->input->post('indesc');
				
		$datas = array(
			'MTPB_CD' =>  trim($citem)
		);
		$msg = "";
		if($this->MTPB_mod->check_Primary($datas)>0){
			$msg = "Already Synchronized";
		} else {
            $datas = array(
                'MTPB_CD' =>  trim($citem),
                'MTPB_DESC' => $cdesc
            );
			$toret = $this->MTPB_mod->insert($datas);
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
    
    public function finddiff(){        
        header('Content-Type: application/json');
        $rs = $this->MTPB_imod->selectAll();
        $rsm = $this->MTPB_mod->selectAll();
        $a_id = array();
        $a_diff = array();
        foreach($rs as $r){
            array_push($a_id, trim($r['KODE_JENIS_TPB']));
        }
        $a_idm = array();        
        foreach($rsm as $r){
            array_push($a_idm, trim($r['MTPB_CD']));
        }
        foreach($rs as $r){
            if(!in_array($r['KODE_JENIS_TPB'],$a_idm)){                
                array_push( $a_diff, array('ID' =>$r['KODE_JENIS_TPB'], 'DESC' => $r['URAIAN_JENIS_TPB']) );
            }
        }
        echo json_encode($a_diff);
    }

    public function create(){
        
            $rs = $this->MTPB_imod->selectAll();
            $rsm = $this->MTPB_mod->selectAll();
            $a_id = array();
            $a_diff = array();
            foreach($rs as $r){
                array_push($a_id, $r['KODE_JENIS_TPB']);
            }
            $a_idm = array();        
            foreach($rsm as $r){
                array_push($a_idm, $r['MTPB_CD']);
            }
            foreach($rs as $r){
                if(!in_array($r['KODE_JENIS_TPB'],$a_idm)){                
                    array_push( $a_diff, array('ID' =>$r['KODE_JENIS_TPB'], 'DESC' => $r['URAIAN_JENIS_TPB']) );
                }
            }
            
            $data['ldiff'] = $a_diff;    
            $this->load->view('wms/vmtpb', $data);     
        
    }
    public function search(){
		header('Content-Type: application/json');
		$cid        = $this->input->get('cid');
		$csrchkey   = $this->input->get('csrchby');
		$rs = array();
		switch($csrchkey){
			case 'ic':
				$rs = $this->MTPB_mod->selectbyid_lk($cid);break;
			case 'in':
				$rs = $this->MTPB_mod->selectbynm_lk($cid);break;			
		}					
		echo json_encode($rs);
    }
    
    public function set(){
        $cid = $this->input->post('initmcd');
		$cnm1 = $this->input->post('initmnm1');		
		$datac = array('MTPB_CD'=> $cid);
		if($this->MTPB_mod->check_Primary($datac)==0){
			$datas = array(
				'MTPB_CD' => $cid,
				'MTPB_DESC' => $cnm1,				
			);
			$toret = $this->MTPB_mod->insert($datas);
			if($toret>0){ echo "Saved successfully";}
		} else {
			$datau = array(
				'MTPB_DESC' => $cnm1,							
			);
			$toret = $this->MTPB_mod->updatebyId($datau, $cid);
			if($toret>0){ echo "Updated successfully";}
		}
    }
}
