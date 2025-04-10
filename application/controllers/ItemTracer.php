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

    public function form_lot_report()
    {
        $this->load->view('wms_report/lot_tracer');
    }

    public function form_adjustment()
    {
        $this->load->view('wms/adjustment_tracer');
    }
}
