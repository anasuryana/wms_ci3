<?php
class ItemTracer extends CI_Controller
{

    public function __construct()
    {
        parent::__construct();
        $this->load->helper('url');
        $this->load->library('session');
    }
    public function index()
    {
        echo "sorry";
    }

    public function form_outstanding_scan()
    {
        $this->load->view('wms_report/outstanding_scan_tracer');
    }
}
