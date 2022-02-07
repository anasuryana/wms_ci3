<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class MCNSGN extends CI_Controller {

	public function __construct()
	{
		parent::__construct();
		$this->load->model('MCNSGN_mod');
		$this->load->helper('url');
		$this->load->library('session');
	}
	public function index()
	{
		echo "sorry";
	}	

	public function getbyparent(){
        header('Content-Type: application/json');
        $cpar   = $this->input->get('inpar');
		$rs     = $this->MCNSGN_mod->selectbyID($cpar);
		echo json_encode($rs);
    }
    
	
	public function set(){
		$ccd	= $this->input->post('incd');
        $cnm	= $this->input->post('innm');
        $cnm_old	= $this->input->post('innm_old');
        
        if(trim($cnm_old)==''){
            $datac =array(
                'MCNSGN_CUSCD' => $ccd,
                'MCNSGN_NM' => $cnm
            );
            if($this->MCNSGN_mod->check_Primary($datac)>0){
                echo "Data is already exist";
            } else{
                $toret = $this->MCNSGN_mod->insert($datac);
                if($toret>0){
                    echo "Saved successfully";
                } else {
                    echo "Could not be saved";
                }
            }
        } else {
            $datau = array(				
                'MCNSGN_NM' => $cnm                
            );
            $dataw = array(
                'MCNSGN_CUSCD' => $ccd,
                'MCNSGN_NM' => $cnm_old
            );
			$toret = $this->MCNSGN_mod->updatebyId($datau, $dataw);
			if($toret>0){ echo "Updated successfully";}
        }		
    }
    
    function remove(){
        $cid  = $this->input->get('inid');
        $cnm  = $this->input->get('innm');
        $datak = array(
            'MCNSGN_CUSCD'=> $cid,
            'MCNSGN_NM' => $cnm
        );
        $toret = $this->MCNSGN_mod->getdeleted($datak);
        if($toret>0){
            echo "deleted";
        } else {
            echo "could not be delete";
        }
    }
}
