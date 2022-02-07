<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class ZWAYTRANS extends CI_Controller {

	public function __construct()
	{
		parent::__construct();
		$this->load->model('refceisa/ZWAYTRANS_mod');		
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
		$rs 	= $this->ZWAYTRANS_mod->selectbyvar($dataw);
		echo json_encode($rs);		
	}
	
	public function create(){
		$this->load->view('refceisa/vwayoftransport');
	}        
	
    public function search(){
		header('Content-Type: application/json');
		$cid        = $this->input->get('cid');
		$csrchkey   = $this->input->get('csrchby');
		$rs = array();
		switch($csrchkey){
			case 'cd':
				$rs = $this->ZWAYTRANS_mod->selectbyid_lk($cid);break;
			case 'in':
				$rs = $this->ZWAYTRANS_mod->selectbynm_lk($cid);break;			
		}					
		echo json_encode($rs);
    }

    public function set(){
        $cdoccd = $this->input->post('initmcd');        
        $cdsc   = $this->input->post('indesc');
		$datac  = array('KODE_CARA_ANGKUT'=> $cdoccd);
		if($this->ZWAYTRANS_mod->check_Primary($datac)==0){            
			$datas = array(                
				'KODE_CARA_ANGKUT' => $cdoccd,                
                'URAIAN_CARA_ANGKUT' => $cdsc		
			);
			$toret = $this->ZWAYTRANS_mod->insert($datas);
			if($toret>0){ echo "Saved successfully";}
		} else {
			$datau = array(
				'URAIAN_CARA_ANGKUT' => $cdsc,
			);
			$toret = $this->ZWAYTRANS_mod->updatebyId($datau, $datac);
			if($toret>0){ 
                echo "Updated successfully";
            } else {
                echo "Could not be updated";
            }
		}
	}  		
	
}
