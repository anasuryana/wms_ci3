<?php
defined('BASEPATH') or exit('No direct script access allowed');

class ADJ extends CI_Controller
{
    public function __construct()
    {
        parent::__construct();
        $this->load->helper('url');
        $this->load->library('session');
        $this->load->model('ITH_mod');
        date_default_timezone_set('Asia/Jakarta');
    }

    public function index()
    {
        die("sorry");
    }

    public function form_part()
    {
        $this->load->view('wms/vadjustment_part');
    }

    public function form_FG()
    {
        $this->load->view('wms/vadjustment_FG');
    }
}
