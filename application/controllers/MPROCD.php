<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class MPROCD extends CI_Controller {
    public function __construct()
    {
        parent::__construct();
        $this->load->helper('url');
        $this->load->library('session');        
    }
    function form()
    {
        $this->load->view('scrap_report/vform_mprocess');
    }
}