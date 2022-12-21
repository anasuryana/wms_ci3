<?php
defined('BASEPATH') or exit('No direct script access allowed');

class BGROUPHistory extends CI_Controller
{
    public function __construct()
    {
        parent::__construct();
        $this->load->helper('url');
        $this->load->library('session');
        $this->load->model('BGROUP_mod');
    }

    public function index()
    {
        die("sorry");
    }

    public function list()
    {
        header('Content-Type: application/json');
        $rs = $this->BGROUP_mod->selectall();
        die(json_encode(['data' => $rs]));
    }
}
