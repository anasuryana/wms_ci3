<?php

class MSLSPRC extends CI_Controller {  
    public function __construct()
    {
        parent::__construct();
        $this->load->model('MSLSPRC_mod');
        $this->load->model('MSTCUS_mod');
        $this->load->helper('url_helper');        
        $this->load->helper('security'); 
        $this->load->helper('download');
        $this->load->library('session');
    }

    public function index(){
        echo "sorry";
    }

    public function create(){
        $data['lcus'] = $this->MSTCUS_mod->selectAll();
        $this->load->view('wms/vmsls_price', $data);
    }

    public function set(){
        $citem = $this->input->post('initem');
        $ccus = $this->input->post('incus');
        $cprc = $this->input->post('inprc');
        $datac = array(
            'MSLSPRICE_ITMCD' => $citem, 'MSLSPRICE_CUSCD' => $ccus
        );
        if($this->MSLSPRC_mod->check_Primary($datac)==0){
            $datac['MSLSPRICE_PRICE'] = $cprc;
            $h = $this->MSLSPRC_mod->insert($datac);
            if($h){
                echo "Saved successfully";
            } else {
                echo "Sorry, We could not save";
            }
        } else {
            $datau = array('MSLSPRICE_PRICE' => $cprc);
            $h = $this->MSLSPRC_mod->updatebyId($datau, $datac);
            if($h){
                echo "updated successfully";
            } else {
                echo "Sorry, We could not update";
            }
        }
    }

    public function getbyitemcd(){
        header('Content-Type: application/json');
        $citem =$this->input->get('incd');
        $dataw = array('MSLSPRICE_ITMCD'=> $citem);
        $rs = $this->MSLSPRC_mod->selectbyVAR($dataw);
        echo json_encode($rs);
    }

    public function getlinkitemtemplate(){
        $murl   = ((isset($_SERVER['HTTPS']) && $_SERVER['HTTPS'] == "on") ? "https" : "http");
        $murl   .= "://".$_SERVER['HTTP_HOST'];
        echo $murl."/wms/MSLSPRC/downloadtemplate";
    }

    function downloadtemplate(){
        $theurl = 'assets/userxls_template/tmpl_sales_price.xls' ;//base_url("assets/userdata_templates/tmp_wip.xlsx");
        force_download($theurl , null );
        echo $theurl;
    }

    public function import(){
        date_default_timezone_set('Asia/Jakarta');
        $currrtime = date('Y-m-d H:i:s');        
        $citem     = $this->input->post('initem');
        $ccus      = $this->input->post('incus');
        $cprice    = $this->input->post('inprc');
        $crowid    = $this->input->post('inrowid');        
        $datac = array(
            'MSLSPRICE_ITMCD' => $citem, 'MSLSPRICE_CUSCD' => $ccus
        );
        $myar = array();
        if($this->MSLSPRC_mod->check_Primary($datac)==0){                   
            $datac['MSLSPRICE_LUPDT'] = $currrtime;
            $datac['MSLSPRICE_USRID'] = $this->session->userdata('nama');
            $datac['MSLSPRICE_PRICE'] = $cprice;
            $toret = $this->MSLSPRC_mod->insert($datac);            
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
