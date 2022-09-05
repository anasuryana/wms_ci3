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
    function form()
    {
        $this->load->view('wms/vmprocd');
    }
}