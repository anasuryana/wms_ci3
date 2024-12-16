<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Trans extends CI_Controller {
	public function __construct()
	{
		parent::__construct();		
		$this->load->helper('url');
		$this->load->library('session');
		$this->load->model('Trans_mod');
	}
	public function index()
	{
		echo "sorry";
	}

	public function create(){
		$this->load->view('wms/vtrans');
    }
    
    public function getall(){
        header('Content-Type: application/json');
		$rs 	= $this->Trans_mod->selectallActive();
		echo '{"data":';
		echo json_encode($rs);
		echo '}';
    }

    public function search(){
        header('Content-Type: application/json');
        $ckey = $this->input->get('inkey');
        $rs = $this->Trans_mod->selectbyid_lk($ckey); echo json_encode($rs);
    }

    public function set(){
        date_default_timezone_set('Asia/Jakarta');
		$currrtime 	= date('Y-m-d H:i:s');
        $cid = $this->input->post('inid');
        $ctype = $this->input->post('intype');

        $datac = array(
            'MSTTRANS_ID' => $cid
        );

        if($this->Trans_mod->check_Primary($datac)>0){
            $datau = array(
                'MSTTRANS_TYPE' => $ctype,
                'MSTTRANS_LUPDT' => $currrtime,
                'MSTTRANS_USRID' => $this->session->userdata('nama')
            );
            $toret = $this->Trans_mod->updatebyId($datac, $datau);
            if($toret>0){
                echo 'updated successfully';
            } else {
                echo 'Could not update';
            }
        } else {
            $datas = array(
                'MSTTRANS_ID' => $cid,
                'MSTTRANS_TYPE' => $ctype,
                'MSTTRANS_LUPDT' => $currrtime,
                'MSTTRANS_USRID' => $this->session->userdata('nama')
            );
            $toret = $this->Trans_mod->insert($datas);
            if($toret>0){
                echo 'Saved successfully';
            } else {
                echo 'Could not save';
            }
        }
    }
}
