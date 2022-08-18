<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class MPROCD extends CI_Controller {
    public function __construct()
    {
        parent::__construct();
        $this->load->helper('url');
        $this->load->library('session');
        $this->load->model('MPROCD_mod');
    }
    function form() {
        $this->load->view('wms/vmprocd');
    }

    function search() {
        header('Content-Type: application/json');
        $search = $this->input->get('search');
        $rs = $this->MPROCD_mod->select_header(['MITM_ITMCD' => $search]);
        die(json_encode(['data' => $rs]));
    }
}