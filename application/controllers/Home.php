<?php

class Home extends CI_Controller {

    public function __construct()
    {
        parent::__construct();        
        $this->load->helper('url_helper');
        $this->load->library('session');
        $this->load->model('MSTLOCG_mod');
        if ($this->session->userdata('status') != "login")
        {
            redirect(base_url(""));
        }
    }

    public function index()
    {                        
        $data['judul'] = 'Home';
        $data['sapaDia'] = $this->session->userdata('sfname');
        $data['sapaDiaID'] = $this->session->userdata('nama'); 
        $data['wms_usergroup_id'] = $this->session->userdata('gid'); 
        $data['lwh'] = $this->MSTLOCG_mod->selectall();
        $data['lwhfg'] = $this->MSTLOCG_mod->selectall_by_dedict('FGWH');
        $this->load->view('templet/header', $data);
        $this->load->view('vhome',$data);
        $this->load->view('templet/footer',$data);
    }

    public function getinfo(){
        $tipe = '';
        if (!empty($_SERVER["HTTP_CLIENT_IP"]))
        {
            $ip = $_SERVER["HTTP_CLIENT_IP"]; $tipe='1';
        } elseif (!empty($_SERVER["HTTP_X_FORWARDED_FOR"]))
        {
            $ip = $_SERVER["HTTP_X_FORWARDED_FOR"]; $tipe='2';
        } else
        {
         $ip = $_SERVER["REMOTE_ADDR"];$tipe='3';
        }
        $host = gethostbyaddr($_SERVER['REMOTE_ADDR']);        
        echo $host;
    }
    

}
